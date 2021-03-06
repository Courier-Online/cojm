<?php 

/*
    COJM Courier Online Operations Management
	opsmap.php - Displays POI & Ops Areas
    Copyright (C) 2018 S.Young cojm.co.uk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

$alpha_time = microtime(TRUE);
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
include 'changejob.php';

if (isset($_GET['searchtype'])) { $searchtype=trim($_GET['searchtype']);} else { $searchtype=''; }

$markersfound='0';
$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';
$testc='';
$js='';

$hasforms='0';
?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php print ' Ops Map : '.($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="../css/cojmmap.css">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';
 ?>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<style>
/* starts spinner on page load, only for ajax pages  */
#toploader { display:block; }
</style>



</head><body>  <?php
//  $adminmenu="1";
$filename="opsmap.php";
include "cojmmenu.php";


echo '<script src="//maps.googleapis.com/maps/api/js?v='.$globalprefrow['googlemapver'].'&amp;libraries=geometry&amp;key='.$globalprefrow['googlemapapiv3key'].'" type="text/javascript"></script>

<script src="../js/maptemplate.js" type="text/javascript"></script>
'; 

if ($searchtype=='') { $query = "SELECT type, lat, lng, opsmapid, opsname, istoplayer, descrip, AsText(g) AS POLY FROM opsmap WHERE inarchive<>1 AND corelayer='0' "; }

// 

if ($searchtype=='archive') { $query = "SELECT type, lat, lng, opsmapid, opsname, istoplayer, descrip, AsText(g) AS POLY FROM opsmap WHERE corelayer='0' "; }

$showallrow='';
$tablerow='';
$clickrow='';

$stmt = $dbh->query($query);
  
foreach ($stmt as $row) {
    if ($row['type']=='1') {
        $markersfound++;
        $lat=$row['lat'];
        $lng=$row['lng'];
        if ($lat>$max_lat) { $max_lat=$lat; }
        if ($lat<$min_lat) { $min_lat=$lat; }
        if ($lng>$max_lon) { $max_lon=$lng; }
        if ($lng<$min_lon) { $min_lon=$lng; }
    } 

    elseif ($row['type']=='2') {
        $tablerow.= '
        <tr id="'.$row['opsmapid'].'" >
        <td><a href="opsmap-new-area.php?areaid='.$row['opsmapid'].'">'.$row['opsname'].'</a></td>';
        
        $tablerow.= '<td>';
        if ($row['istoplayer']=='1') {
            $tablerow.= ' <span class="album" title="Has Layers"> </span> ';
        }

        
        $tablerow.= $row['descrip'].'</td>';
        $tablerow.= '</tr>';
        
        $p=$row['POLY'];
        $areaid=$row['opsmapid'];
        if ($p) {

            $trans = array("POLYGON" => "", "((" => "", "))" => "");
            $p= strtr($p, $trans);
            $pexploded=explode( ',', $p );
            $js=$js.' 
            
            var polymarkers'.$areaid.' = '; 
            
            $jsloop = ' [ ';
            
            foreach ($pexploded as $v) {
            $transf = array(" " => ",");
            $v= strtr($v, $transf);
                $jsloop.='   
                new google.maps.LatLng('.$v.'),';
                $vexploded=explode( ',', $v );
                $tmpi='1';
                foreach ($vexploded as $testcoord) {	
            
                    if ($testcoord) {
                    
                        if ($tmpi % 2 == 0) {
                            if($testcoord>$max_lon) { $max_lon = $testcoord; }
                            if($testcoord<$min_lon)  { $min_lon = $testcoord; }
                        } else { 
                            if ($testcoord>$max_lat) { $max_lat = $testcoord; }
                            if ($testcoord<$min_lat)  {
                                $testc.='<br/>151 '.$testcoord;
                                $min_lat = $testcoord;
                            }
                        } $tmpi++;
                    } // ends test coord valid check
                }
            } // ends each in array

            $jsloop= rtrim($jsloop, ','). ' ] ';
    
            $js.=$jsloop.' ; ';  
        
            //  strokeColor: "#FF0000",
        
            $js.=' 
            var poly'.$areaid.' = new google.maps.Polygon({
                paths: polymarkers'.$areaid.',
                strokeWeight: 2,
                strokeOpacity: 0.8,
                fillColor: "#5555FF",
                fillOpacity: 0.25,
                strokeColor: "#000000",
                map:map,
                clickable: false
            });
            
            totalareas = totalareas + 1;
            ';
    
            $clickrow.=' 
            console.log(google.maps.geometry.poly.containsLocation(event.latLng, poly'.$areaid.'));
            if(google.maps.geometry.poly.containsLocation(event.latLng, poly'.$areaid.') === true) {
            areasfound=areasfound+1;
            
            
            // $( "#jssearch" ).append( "<p> Found individ '.$areaid.'</p>" );
            
            $( "#'.$areaid.'" ).addClass( " myClass " );
            $( "#'.$areaid.'" ).removeClass( " hidden " );
                }
            
            else { 
            $( "#'.$areaid.'" ).removeClass( " myClass " );
            $( "#'.$areaid.'" ).addClass( " hidden " );
            }
                ';
                
            $showallrow.=' 
            $( "#'.$areaid.'" ).removeClass( " hidden " ); ';
        }
    } // ends type=2
} // ends db row loop





        //Load Markers from the XML File, Check (ajaxopsmap_process.php)			
if ($searchtype=='archive') {
    $ajaxlocation='ajaxopsmap_process.php?archive=1';    
} else { 
    $ajaxlocation='ajaxopsmap_process.php?archive=0';
}
    

?>
<script>
var globlat=<?php echo $globalprefrow['glob1']; ?>;
var globlon=<?php echo $globalprefrow['glob2']; ?>;

printtext =' ';

function custominitialize() {



    $.get("<?php echo $ajaxlocation; ?>", function (data) {
        $(data).find("marker").each(function () {  //Get user input values for the marker from the form
            var name = $(this).attr('name');
            var address = $(this).attr('address');
            var markertype = $(this).attr('markertype');
            var point = new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
            var opsmapid = $(this).attr('opsmapid');
			  
            //call create_marker() function for xml loaded maker
            //create_marker(opsmapid, point, name, address, false, false, false, "<?php echo $globalprefrow['clweb3']; ?>");
            // var iconPath= 	"<?php echo $globalprefrow['clweb3']; ?>";			  
            //		alert(typeof markertype);			  


            if (markertype==1) {
                markertype1++;				  				  
                iconPath= {
                    url: "../images/info-50-50-trans.gif",
                    scaledSize: new google.maps.Size(30, 30), // scaled size
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(15, 15)
                }; 
            } else if (markertype==2) {
                markertype2++;
                iconPath= {
                    url: "../images/access-50-50-trans.gif",
                    scaledSize: new google.maps.Size(30, 30), // scaled size
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(15, 15)
                };
            } else if (markertype==3) {
                markertype3++;		
                iconPath= {
                url: "../images/alert-50-50-trans.gif",
                scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(15, 15)
                }; 
            }
        $(document).ready(function() {
            $("#markertype1span").html(markertype1);	
            $("#markertype2span").html(markertype2);	
            $("#markertype3span").html(markertype3);	
        });


    //new marker
    var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable:false,
        title: name ,
        icon: iconPath
    });
    
    //Content structure of info Window for the Markers
    var contentString = $('<div class="marker-info-win">'+
    '<h1 class="marker-heading">'+name+'</h1>'+
    '<p>' + address + ' </p> <br />'+
	'<form action="opsmap-new-place.php?" method="post" >' +
	'<button type="submit" name="remove-marker" class="remove-marker" title="Edit Place">Edit Marker</button>'+
    '<input name="opsmapid" type="hidden" value=\"' + opsmapid + '\"> ' + 
	'</form>' + 
    '</div>');    

    //Create an infoWindow
    var infowindow = new google.maps.InfoWindow();
    //set the content of infoWindow
    infowindow.setContent(contentString[0]);

    //Find remove button in infoWindow
    var removeBtn   = contentString.find('button.remove-marker')[0];

   //Find save button in infoWindow
    var saveBtn     = contentString.find('button.save-marker')[0];
        
    //add click listner to open infowindow     
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker); // click on marker opens info window 
    });
      				  
            });
        }); 
        
        //drop a new marker on right click
        google.maps.event.addListener(map, 'rightclick', function(event) {
            //Edit form to be displayed with new marker
            var EditForm = '<form action="ajaxopsmap_process.php" method="POST" name="SaveMarker" id="SaveMarker">'+
			'<div class="marker-edit">'+
            '<div class="fs"><div class="fsli"> Name </div>'+
			'<input type="text" name="pName" class="save-name ui-state-default ui-corner-all w170" placeholder="Enter Title" maxlength="40" />'+
			'</div>'+
          '<div class="fs"><div class="fsli"> Info</div>'+
			'<textarea name="pDesc" class="save-desc w170 normal ui-state-highlight ui-corner-all" placeholder="Comments" maxlength="150"></textarea>'+
			'</div>'+
            '<div class="fs"><div class="fsli">Type </div>'+
			'<select name="pType" class="save-type ui-state-highlight ui-corner-left">'+
			'<option value="1">General</option>'+
			'<option value="2">Access </option>'+
            '<option value="3">Safety</option></select>'+
            '<button name="save-marker" class="save-marker">Save Marker Details</button>'+
			'</div></form>';
			
			
			$(function(){ $('.normal').autosize();	});

            //call create_marker() function
          create_marker( 0, event.latLng, 'New Marker', EditForm, true, true, true, "<?php echo $globalprefrow['clweb3']; ?>");
			
        });


        
    <?php

echo $js;
  
echo '
  
  // bounds for polygons hardcoded via php
  var  bounds = new google.maps.LatLngBounds();
 
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$min_lon.')); // upper left
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$max_lon.')); // upper right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$max_lon.')); // lower right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$min_lon.')); // lower left
	 '; 
  
  
  
  echo "
google.maps.event.addListener(map, 'click', function(event) {
    var areasfound	   = 0;
    $( '#showAll' ).removeClass( ' hidden ' );
    $( '#jssearch' ).empty();	  
    $( '#jssearch' ).append (  ' ');
    ".$clickrow. "
    $( '#jssearch' ).append (' <p> ');
    $( '#jssearch' ).append (areasfound);
    $( '#jssearch' ).append (' areas found out of ');
    $( '#jssearch' ).append (totalareas);
    
    
    $( '#jssearch' ).append ('.</p> ');
    
    if (areasfound === 0 ) {
    
    
    $( '#opstable' ).addClass( ' hidden ' );
        }
    else {
        $( '#opstable' ).removeClass( ' hidden ' );
    }
});
 

 

  

$('#showAll').click(function() {
    ".$showallrow."
    $( '#opstable' ).removeClass( ' hidden ' );	
    $( '#showAll' ).addClass( ' hidden ' );
    $( '#jssearch' ).empty();	
});


";
?>

//############### Create Marker Function ##############
function create_marker(opsmapid, MapPos, MapTitle, MapDesc,  InfoOpenDefault, DragAble, Removable, iconPath)
{
    //new marker
    var marker = new google.maps.Marker({
        position: MapPos,
        map: map,
        draggable:DragAble,
        title: MapTitle ,
        icon: iconPath
    });
    
    //Content structure of info Window for the Markers
    var contentString = $('<div class="marker-info-win">'+
    '<div class="marker-inner-win"><span id=\" + opsmapid + \" class="info-content">'+
    '<h1 class="marker-heading">'+MapTitle+'</h1>'+
    MapDesc+ 
    '</span>' + 
    '</div></div>');    

    //Create an infoWindow
    var infowindow = new google.maps.InfoWindow();
    //set the content of infoWindow
    infowindow.setContent(contentString[0]);

    //Find remove button in infoWindow
    var removeBtn   = contentString.find('button.remove-marker')[0];

   //Find save button in infoWindow
    var saveBtn     = contentString.find('button.save-marker')[0];

			$(function(){ $('.normal').autosize();	});
    
    if(typeof saveBtn !== 'undefined') //continue only when save button is present
    {
 	
        //add click listner to save marker button
        google.maps.event.addDomListener(saveBtn, "click", function(event) {
			event.preventDefault();
            var mReplace = contentString.find('span.info-content'); //html to be replaced after success
            var mName = contentString.find('input.save-name')[0].value; //name input field value
            var mDesc  = contentString.find('textarea.save-desc')[0].value; //description input field value
            var mType = contentString.find('select.save-type')[0].value; //type of marker
            
            if(mName ==='' || mDesc ==='')
            {
                alert("Please enter Name and Description!");
            }else{
                //call save_marker function and save the marker details
                save_marker(marker, mName, mDesc, mType, mReplace);
            }
        });
    }
    
    //add click listner to save marker button        
    google.maps.event.addListener(marker, 'mouseover', function() {
            infowindow.open(map,marker); // click on marker opens info window 
    });
      
    if(InfoOpenDefault) //whether info window should be open by default
    {
      infowindow.open(map,marker);
	  
google.maps.event.addListener(infowindow, 'domready', function() {

$(function(){ $('.normal').autosize();	});	  
	  
});
	  
    }
}


//############### Save Marker Function ##############
function save_marker(Marker, mName, mAddress, mType, replaceWin)
{
	
var savemarker="savemarker";	
    //Save new marker using jQuery Ajax
    var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
    var myData = {name : mName, address : mAddress, latlang : mLatLang, type : mType , action: savemarker}; //post variables
    console.log(replaceWin);        
    $.ajax({
      type: "POST",
      url: "ajaxopsmap_process.php",
      data: myData,
      success:function(data){
            replaceWin.html(data); //replace info window with new html
            Marker.setDraggable(false); //set marker to fixed
            Marker.setIcon('<?php echo $globalprefrow['clweb3']; ?>'); //replace icon
        },
        error:function (xhr, ajaxOptions, thrownError){
            alert(thrownError); //throw any errors
        }
    });
}

    $(window).resize(function () {
        var menuheight=0;
        $(".top_menu_line").each(function( index ) {
            if ($(this).is(':visible')) {
                menuheight = menuheight + $( this ).height();
            }
        });
//        var h = ;
        $("#gmap_wrapper").css("height", ($(window).height() - menuheight));
    }).resize();


 $('#searchtype').change(function() { $('#searchopsmap').submit(); }); 
 
 map.fitBounds(bounds);

 $(document).ready(function() {
    $('#uploadkmltonewopsmap').bind('click', function (e) {
        e.preventDefault();
        $.Zebra_Dialog(' ' +
            ' <input id="opsmapname" name="areaname" form="uploadkml" size="15" type="text" placeholder="Name" class="ui-state-default ui-corner-all "> ' +
            ' <br /> ' +
            ' <input name="file" id="file" form="uploadkml" type="file" accept=".kml"> ', {
                    "type": "question",
                    "title": "Upload KML File to New Opsmap Area",
                    "buttons": [{
                        caption: "Upload",
                        callback: function () {
                            // $('#editcomment').trigger('autosize.resize');
                            // var whichselected=$("#selectfavbox").val();
                            
                            var opsmapname=$('#opsmapname').val();
                            
                            if (opsmapname) {
                                $("#uploadkml").submit();
                            
                            } else {
                                alert("Please Add Area Name");
                            }
                        }
                    },{
                        caption: "Cancel"
                    }]
                });
        });
});
 
 
 
}

function loadmapfromtemplate() {
    $(document).ready(function () {
        initialize();
        custominitialize();
    });
}


google.maps.event.addDomListener(window, "load", loadmapfromtemplate);

</script>

<div id="gmap_wrapper" >
<div class="full_map" id="search_map">
<div id="map-canvas" class="onehundred" ></div></div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">
<form id="searchopsmap" action="opsmap.php">


<select id="searchtype" name="searchtype" class="ui-state-highlight ui-corner-left">
<option <?php if ($searchtype=='') {  echo ' selected="selected" '; } ?>
value="">Active</option>
<option 

<?php if ($searchtype=='archive') {  echo ' selected="selected" '; } ?>
 value="archive">Active + Archived</option>
</select>
</form>


<br />
<hr />

<div id="jssearch"></div>

<button class=" hidden " id="showAll">Show all Areas</button>

<table id="opstable" class="ord"><tbody><tr>
<th>Name </th>
<th> </th>
</tr>
<?php echo $tablerow; ?>


</tbody></table>
<br />
<table class="nolines">
<tbody>
<tr><td><img alt="Marker Type 1" class="opsmapicon" src="../images/info-50-50-trans.gif" /> </td><td> <span id="markertype1span"> </span> </td><td>General</td></tr>
<tr><td><img alt="Marker Type 2" class="opsmapicon" src="../images/access-50-50-trans.gif" /> </td><td>  <span id="markertype2span"> </span> </td><td> Access </td></tr>
<tr><td><img alt="Marker Type 3" class="opsmapicon" src="../images/alert-50-50-trans.gif" /> </td><td>  <span id="markertype3span"> </span>  </td><td> Safety </td></tr>

</tbody>
</table>

<?php 


$count = $dbh->query("SELECT count(1) FROM opsmap WHERE type='1' AND inarchive ='1' ")->fetchColumn();


?>


<p> <?php echo $markersfound; ?> Locations, <?php echo $count; ?> in archive.</p>

<p> Left click on map to search areas. </p>
<p> Left click on marker to view / edit. </p>
<p> Right click on map to add a marker. </p>

<br />
<hr />

 <form action="opsmap-new-area.php">
<button type="submit" title="New Area">Create Blank New Area</button>
</form>

<button id="uploadkmltonewopsmap"> Upoad KML area </button>

</div>		
</div>

</div>

<form action="opsmap-new-area.php" method="post" id="uploadkml" enctype="multipart/form-data">
<input type="hidden" name="page" value="uploadkml">
 <input type="hidden" name="formbirthday" value="<?php echo date("U"); ?>">
</form>


<script>

</script>
<?php

include "footer.php";

echo '</body></html>';
