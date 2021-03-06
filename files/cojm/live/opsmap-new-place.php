<?php

/*
    COJM Courier Online Operations Management
	opsmap-new-place.php - edits single ops map place
    Copyright (C) 2017 S.Young cojm.co.uk

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
$filename="opsmap-new-area.php";

include "C4uconnect.php";
include "changejob.php";

if (isset($_POST['opsmapid'])) { $opsmapid=trim($_POST['opsmapid']);} else {$opsmapid=''; }

?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="HandheldFriendly" content="true" >
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link rel="stylesheet" type="text/css" href="../css/cojmmap.css">
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<script src="//maps.googleapis.com/maps/api/js?v=<?php echo $globalprefrow['googlemapver']; ?>&amp;libraries=geometry&amp;key=<?php echo $globalprefrow['googlemapapiv3key']; ?>" type="text/javascript"></script>
<script src="../js/maptemplate.js" type="text/javascript"></script>
<title>OpsMap Place <?php echo $opsmapid; ?></title>
<?php

$query = "SELECT * FROM opsmap WHERE opsmapid= ? ";
$statement = $dbh->prepare($query);
$statement->execute([$opsmapid]);
$row = $statement->fetch(PDO::FETCH_ASSOC);

$type=$row['type'];
$opsname=$row['opsname'];
$opsmapid=$row['opsmapid'];
$descrip=$row['descrip'];
$istoplayer=$row['istoplayer'];
$corelayer=$row['corelayer'];
$inarchive=$row['inarchive'];
$initiallat=$row['lat'];
$initiallon=$row['lng'];
$markertype=$row['markertype'];


if (!$initiallat) {  $initiallat=$globalprefrow['glob1']; }
if (!$initiallon) {  $initiallon=$globalprefrow['glob2']; }
?>
<script type="text/javascript"> 


        var globlat=<?php echo $globalprefrow['glob1']; ?>;
var globlon=<?php echo $globalprefrow['glob2']; ?>;


function custominitialize() {
    
    var toencode=atob("<?php echo base64_encode($descrip); ?>");
    
    var EditForm =
	'<form action="ajax-save.php" method="POST" name="EditMarker" id="EditMarker" class="EditMarker">'+
	'<div class="marker-edit">'+
    '<div class="fs"><div class="fsli"> Name </div>'+
	'<input type="text" name="pName" class="save-name ui-state-default ui-corner-all w170" '+
    'placeholder="Enter Title" maxlength="50" value="<?php echo $opsname; ?>" /></div>'+
    '<div class="fs"><div class="fsli"> Info</div>'+
	'<textarea name="pDesc" id="pDesc" class="save-desc w170 normal ui-state-highlight ui-corner-all " placeholder="Comments" maxlength="300">'+
	toencode + '</textarea></div>'+
    '<div class="fs"><div class="fsli">Type </div>'+
	'<select name="pType" class="save-type ui-state-highlight ui-corner-left">'+
	'<option <?php if ($markertype=='1') { echo ' selected="selected" '; } ?> value="1">General</option>'+
	'<option <?php if ($markertype=='2') { echo ' selected="selected" '; } ?> value="2">Access </option>'+
    '<option <?php if ($markertype=='3') { echo ' selected="selected" '; } ?> value="3">Safety</option>'+
	'</select></div> '+
    '<div class="fs"><div class="fsli">Archived </div> '+
	'<input type="checkbox" name="inarchive" value="1"  <?php if ($inarchive=='1') { echo " checked "; } ?> /> </div> '+
    '<input name="opsmapid" type="hidden" value="<?php echo $opsmapid; ?>"> ' + 
    '<input name="action" type="hidden" value="editmarker"> ' + 			
	'<input name="latlang" type="hidden" id="latlang" value="<?php echo $initiallat.','.$initiallon; ?>" >'+
    '</div> <div class="fs"><div class="fsli"> &nbsp; </div>'+
	'<button name="edit-marker" id="edit-marker" class="edit-marker">Edit Details</button> </div> '+
    '<div class="fs"> Click and drag the marker to relocate.</div> '+
	'</form>';
    


    


    var point = new google.maps.LatLng(<?php echo $initiallat.','.$initiallon; ?>);

    var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable:true,
        title: '".$opsname."'
    });

    var infowindow = new google.maps.InfoWindow({
		position:point,
    });
   
    infowindow.setContent(EditForm);
    infowindow.open(map,marker);  


    //add click listner to open infowindow     
    google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker); // click on marker opens info window 
    });


    google.maps.event.addListener(marker, 'dragend', function() {
        document.getElementById('latlang').value = marker.getPosition().toUrlValue();
    });

    google.maps.event.addListener(infowindow, 'domready', function() {
        $(function(){
            $('.normal').autosize();
        });
        
        $('#edit-marker').on('click', function (event) {
            event.preventDefault();
            var formdata=$('#EditMarker').serializeArray();
            
            $.ajax({
                type: 'POST',
                url: 'ajaxopsmap_process.php',
                data: formdata,
                success:function(data){
                    alert(data);
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError); //throw any errors
                }
            });
        });
    }); // ends infowindow listener
    

}


function loadmapfromtemplate() {
    
    initialize();
    $(document).ready(function () {
        
        custominitialize();
    });
}


google.maps.event.addDomListener(window, 'load', loadmapfromtemplate); 
</script>
</head>
<body>
<?php

$adminmenu='1';
include "cojmmenu.php";

echo '
<div id="gmap_wrapper" >
<div class="full_map" style="padding-left:0px;" id="search_map">
<div id="map-canvas" class="onehundred" ></div>
</div>
</div>'; 

include "footer.php";
echo ' </body> </html>';