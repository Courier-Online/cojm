<?php 
/*
    COJM Courier Online Operations Management
	batchhtmltracking.php - creates standalone html page with gps tracking & area maps
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

// if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) { ob_start("ob_gzhandler"); } else { ob_start(); }

if (!isset($_POST['gpxarray'])) { 

    echo " <h1> No Job ID's passed, please go back to last screen and re-submit. </h1> ";

}
else {

    include "C4uconnect.php";
    
    $cyclistid="createbatchhtmltracking";
    $linecoord='';
    $prevts='';
    $error='';
    $idsuccess=array();
    $areasuccess=array();
    $js=array();
    
    $foundjobs='0';
    $errorjobs='0';
    
    $max_lat = '-99999';
    $min_lat =  '99999';
    $max_lon = '-99999';
    $min_lon =  '99999';
    
    $areajs=' ';
    
    
    
    
    
    
    // $id=trim($_GET['id']); if ($id=="") { $id = trim($_POST['id']); }
    
    
    $gpxarray        = unserialize($_POST['gpxarray']);
    $areagpxarray   = unserialize($_POST['areagpxarray']);
    $sareagpxarray  = unserialize($_POST['sareagpxarray']);
    
    
    
    
    
    
    // $outputtype=$_POST['outputtype'];
    $projectname=trim($_POST['projectname']);
    
    
    // if ($projectname=='')  { $projectname==''; }
    
    
    $output = array('<!doctype html>');
    $output[] = '<html lang="en"><head>';
    $output[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
    $output[] = '<title>';
    
    
    if (!$projectname) {
        $txtprojectname =$globalprefrow['globalshortname']. ' Tracking';
    } else {
        $txtprojectname = $projectname;
    }
    
    
    $output[] = $txtprojectname;
    
    

    
    $output[] = '</title>';
    
    $output[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/> ';
    
    $output[] = '<link rel="stylesheet" type="text/css" href="'.$globalprefrow['glob12'].'">';
    
    
    $output[] = '<script src="https://maps.google.com/maps/api/js?libraries=geometry&amp;v='.$globalprefrow['googlemapver'].
    '&amp;key='.$globalprefrow['googlemapapiv3key'].'"></script> ';
    $output[] = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
    $output[] = '<script src="'.$globalprefrow['glob13'].'"></script>';
    
    
    $output[] = '  
    <script>
    
    
        var map = null;
        var geocoder = null;
        var max_lat = [];
        var min_lat = [];
        var max_lon = [];
        var min_lon = []; 
        var highlightcheck= "";
        var markercount = [];
        var lineplotscount = [];
        var geoXml = null;
        var marker = null;
        var geoXmlDoc = null;
        var gmarkers = [];
        var printtext;
        var bluedotlink = "'. $globalprefrow['glob14'].'";
        
    function custominitialize() { 

    ';
    
    $output[] = ' ';
    
    
    if (isset($_POST['showarea'])) {
    
        // reset($areagpxarray);
        // print_r($areagpxarray);
        // check if just 1 main area, show dark background on multiple areas?  test
        // while (list($myareaid) = each($areagpxarray)) {
            
        $arrlength = count($areagpxarray);
        // echo ' '.$arrlength.' areas found ';	
        $areagpxarray = array_values($areagpxarray);
        $areax = '0';
        while ( $areax < ($arrlength)) {
            
            // echo ' 186: '. $areagpxarray[$areax];
            //	echo ' 188: '.$areagpxarray['0'];
                
            $areaid=$areagpxarray[$areax];
                
            $btmareaquery = "SELECT opsname, descrip, AsText(g) AS POLY FROM opsmap WHERE opsmapid= ? "; 

            $parameters = array($areaid);
            $statement = $dbh->prepare($btmareaquery);
            $statement->execute($parameters);
            $trow = $statement->fetch(PDO::FETCH_ASSOC);
            
            // $idsuccess[]=' <script> console.log(" ____________________________        top area id '.$areaid.'"); </script> ';
            
            
            
            
            $idsuccess[]=' <p id="area'. $areaid.'" > '. $trow['opsname'].' </p> ';
            // $areasuccess[]=' <p id="area'. $areaid.'" title="'.$trow['descrip'].'"> '. $trow['opsname'].' </p> ';
            

            $p=$trow['POLY'];
            $trans = array("POLYGON" => "", "((" => "", "))" => "");
            $p= strtr($p, $trans);
            $pexploded=explode( ',', $p );
            $areajs.=' 
            
            var polymarkers'.$areaid.' = [ ';
            foreach ($pexploded as $v) {
                $transf = array(" " => ",");
                $v= strtr($v, $transf);
                $areajs=$areajs.'   
                new google.maps.LatLng('.$v.'),';
                
                $vexploded=explode( ',', $v );
                $tmpi='1';
                foreach ($vexploded as $testcoord) {
                    if ($tmpi % 2 == 0) {
                        if($testcoord>$max_lon) { $max_lon = $testcoord; }
                        if($testcoord<$min_lon)  { $min_lon = $testcoord; }
                    } else { 
                        if($testcoord>$max_lat) { $max_lat = $testcoord; }
                        if($testcoord<$min_lat)  { $min_lat = $testcoord; }
                    }
                    $tmpi++;
                }
            } // ends each in array
            
            $areajs = rtrim($areajs, ','); 
            $areajs=$areajs.' ]; ';
            $areax++;
        } // ends each area in passed array
        
        
        
        
        $areajs.='
        poly'.$areaid.' = new google.maps.Polygon({
            paths: [worldCoords, ';
        
        $arrlength = count($areagpxarray);
        // echo ' '.$arrlength.' areas found ';	
        $areagpxarray = array_values($areagpxarray);
        $areax = '0';
        while ( $areax < ($arrlength)) {
            $areajs.= 'polymarkers'.$areagpxarray[$areax].', ';
            $areax++;
        }
            
        $areajs.=' ],
            strokeWeight: 4,
            strokeOpacity: 0.4,
            fillColor: "#778899",
            fillOpacity: 0.75,
            strokeColor: "#000000",
            clickable: false
        });
            poly'.$areaid.'.setMap(map);
            max_lon.push("'.$max_lon.'"); 
            min_lon.push("'.$min_lon.'"); 
            max_lat.push("'.$max_lat.'"); 
            min_lat.push("'.$min_lat.'"); 	 
        ';
        
        $output[]= $areajs;
    
    } // ends check show main area
    
    if (isset($_POST['showsubarea'])) {
        reset($sareagpxarray);
        while (list(, $subareaid) = each($sareagpxarray)) {
            $idsuccess[]=' <p id="area"'. $subareaid.'" class="" title=""> Sub Area '. $subareaid.' </p> ';
        }
    }
    
    
    reset($gpxarray);
    
    while (list(, $id) = each($gpxarray)) {
    
        // $output[] = '<p>'.$globalprefrow['globalshortname'].' '.$id.'</p>';
        //$output[] = ' <Icon> <href>'.$globalprefrow['clweb3'].'</href></Icon>';
        
        $jobid=$id;
        $query="SELECT ID, ShipDate, status, collectiondate FROM Orders WHERE Orders.publictrackingref = ? LIMIT 0,1";
        
        $parameters = array($id);
        $statement = $dbh->prepare($query);
        $statement->execute($parameters);
        $orow = $statement->fetch(PDO::FETCH_ASSOC);


        if ($orow['status']<'100') {
            $error.=' <p class="error">'.$orow['ID'].' incomplete.</p>';
            $errorjobs++;
        }
        else {
        
            $testfile="cache/jstrack/".date('Y', strtotime($orow['ShipDate']))."/".date('m', strtotime($orow['ShipDate']))."/".$orow['ID'].'tracks.js';
            
            // $error.=' testfile :  '.$testfile;
            
            if (!file_exists($testfile)) {
                
                $errorjobs++;
                
                $tempID=$orow['ID'];
                
                $sql="SELECT cojmadmin_id FROM cojm_admin WHERE cojm_admin.cojm_admin_job_ref = ? AND cojm_admin_stillneeded = '1' AND cojmadmin_tracking = '1' LIMIT 0,1";

                $stmt = $dbh->prepare($sql);
                $stmt->execute([$orow['ID']]);
                $cojmadmin_id = $stmt->fetchColumn();
                
                if ($cojmadmin_id) { 
                    $error.='<p class="error"> '.$orow['ID'].' in queue.</p>'; 
                } else {
                    $sql="INSERT INTO cojm_admin 
                    (cojm_admin_stillneeded, cojm_admin_job_ref, cojmadmin_tracking) 
                        VALUES 
                    ('1', ?, '1' )   ";
                    
                    $stmt= $dbh->prepare($sql);
                    $stmt->execute([$orow['ID']]);
                    $error.='<p class="error">'.$orow['ID'].' cache queued.</p>';
                }
                // $error.='<p>No html tracking cache for '.$orow['ID'].'</p>';
                
            
            }
            else {
            
                // $error.=' Found ';
                $foundjobs++;
                
                // include js file
                $output[]  = file_get_contents($testfile);
                $js[] = '
                var marker, i;
                    var gmarkers'.$orow['ID'].' = [];
                    for (i = 0; i < markers'.$orow['ID'].'.length; i++) {
                        var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(markers'.$orow['ID'].'[i][1], markers'.$orow['ID'].'[i][2]),
                        map: map,
                        icon: image,
                        opacity: 1
                    });
                marker.mycategory='.$orow['ID'].';
                gmarkers'.$orow['ID'].'.push(marker);
                google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
                    return function() {
                        infowindow.setContent(" <div class='."'".' info '."'".'> "+markers'.$orow['ID'].'[i][0] + " </div> " );
                        infowindow.setOptions({ disableAutoPan: true });
                        infowindow.open(map, marker);
                        var	 highlightcheck = $(".highlight").attr("id");	  
                        if (!highlightcheck) { } else {
                            $( "p" ).removeClass( "highlight" );	  
                            eval("      highlightcheck = +highlightcheck ");
                            eval("var polyref = polyline" + highlightcheck );
                            polyref.setOptions({strokeColor: "#666666", strokeWeight: 2 });
                            eval("var highlightcheck = gmarkers" + highlightcheck );
                            for (var n=0; n<highlightcheck.length; n++) {  
                                highlightcheck[n].setIcon(image); 
                                highlightcheck[n].setZIndex(2);
                            }
                        }
                        $( "p#'.$orow['ID'].'" ).addClass( "highlight" );
                        polyline'.$orow['ID'].'.setOptions({strokeColor: "#339900", strokeWeight: 3 });
                        for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
                            gmarkers'.$orow['ID'].'[j].setIcon(imagehighlight);
                            gmarkers'.$orow['ID'].'[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                        }
                }; }
                )(marker, i));
                }
                var route'.$orow['ID'].' = [];
                for (var j = 0; j < line'.$orow['ID'].'.length; j++) {
                        var lat = line'.$orow['ID'].'[j][0];
                        var lng = line'.$orow['ID'].'[j][1];
                        var marker = new google.maps.LatLng(lat, lng);
                    route'.$orow['ID'].'.push(marker);
                }
                var polyline'.$orow['ID'].' = new google.maps.Polyline({
                    path: route'.$orow['ID'].',
                    geodesic: true,
                    strokeOpacity: 1,
                    strokeColor: "#666666",
                    strokeWeight: 2,
                    icons: [{
                    icon: lineSymbol,
                    repeat: "60px"
                    }],
                    map: map
                });
                $( "p#'.$orow['ID'].'" ).mouseover(function() {
                    var	 highlightcheck = $(".highlight").attr("id");	  
                    if (!highlightcheck) { } else { 
                        $( "p" ).removeClass( "highlight" );	  
                        eval(" highlightcheck = +highlightcheck ");
                        eval("var polyref = polyline" + highlightcheck );
                        polyref.setOptions({strokeColor: "#666666", strokeWeight: 2 });
                        eval("var highlightcheck = gmarkers" + highlightcheck );
                        for (var n=0; n<highlightcheck.length; n++) {  
                            highlightcheck[n].setIcon(image); 
                            highlightcheck[n].setZIndex(2);
                        }
                    }
                    $( "p#'.$orow['ID'].'" ).addClass( "highlight" );
                    polyline'.$orow['ID'].'.setOptions({strokeColor: "#339900", strokeWeight: 3 });
                    for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
                        gmarkers'.$orow['ID'].'[j].setIcon(imagehighlight);
                        gmarkers'.$orow['ID'].'[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                    }
                });
                $( "p#'.$orow['ID'].'" ).mouseout(function() {
                $( "p#'.$orow['ID'].'" ).removeClass( "highlight" );	
                polyline'.$orow['ID'].'.setOptions({strokeColor: "#666666", strokeWeight: 2 });
                    for (var k=0; k<gmarkers'.$orow['ID'].'.length; k++) {  
                        gmarkers'.$orow['ID'].'[k].setIcon(image); 
                        gmarkers'.$orow['ID'].'[k].setZIndex(2);
                    }
                });
                ';
                
                $idsuccess[]=' <p title="'.$id.' '.date('jS M Y', strtotime($orow['collectiondate'])).'" id="'.$orow['ID'].'" class="markers">'.date('D jS', strtotime($orow['collectiondate'])).' '.$orow['ID'].'</p>';
    
        }
        
    
        } // ends check job status
 
    } // ends individual job loop

 
 
 
    $output[] = '
    var lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        strokeOpacity: 0.7
    };
 
    var infowindow = new google.maps.InfoWindow();
    
    var image = {
        url: "'.$globalprefrow['httproots'].'/cojm/images/icon242.png",
        size: new google.maps.Size(20, 20),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(10, 10)
    };
  
    var imagehighlight = {
        url: "'.$globalprefrow['httproots'].'/cojm/images/plot-20-20-339900-square-pad.png",
        size: new google.maps.Size(20, 20),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(10, 10)
    };  
  
    var gmax_lon = Math.max.apply(Math, max_lon);
    var gmax_lat = Math.max.apply(Math, max_lat);
    var gmin_lon = Math.min.apply(Math, min_lon);
    var gmin_lat = Math.min.apply(Math, min_lat);
  ';
 
    $output[] = '
    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(gmax_lat, gmin_lon));
    bounds.extend(new google.maps.LatLng(gmax_lat, gmax_lon));
    bounds.extend(new google.maps.LatLng(gmin_lat, gmin_lon));
    bounds.extend(new google.maps.LatLng(gmin_lat, gmax_lon)); ';
 
    $output[] = join("\n", $js);
    
    
    // finishes initialise
    $output[] = ' map.fitBounds(bounds);
 
    searchinfowindow = new google.maps.InfoWindow({size: new google.maps.Size(150,50) }); 
    
    var totalmarkers=0;
    var totalpoints=0;
    
    for (var i=markercount.length; i--;) {
        totalmarkers = + totalmarkers;
        markercount[i]= +  markercount[i];
        totalmarkers = totalmarkers + markercount[i];
    }
    
    totalmarkers = +totalmarkers;
    
    
    for (var i=lineplotscount.length; i--;) {
        lineplotscount[i]= +  lineplotscount[i];
        totalpoints = +totalpoints;
        totalpoints+=lineplotscount[i];
    }
    
    totalpoints = +totalpoints ;
    
    $(document).ready(function() {
        $("span#markertype1span").html((formatNumber(totalmarkers)));	
        $("span#markertype2span").html((formatNumber(totalpoints)));
        

        $( ".plots" ).each(function( index ) {
            
            printtext=printtext + "<p class=mapprint >" + $( this ).text() + "</p> ";
        });
        
    });
    
	';

// console.log( index + ": " + $( this ).text() );



    $output[] = '  }


    function loadmap () {
        initialize();
        custominitialize();
    }    ';

    $output[] = '
    var globlat=' . $globalprefrow['glob1'].';
    var globlon=' . $globalprefrow['glob2'].';
    var globalshortname = "'.$globalprefrow['globalshortname'].'";
    var adminlogo = "'. $globalprefrow['adminlogoabs'] .'";
    
';
    
 
    $output[] = '  google.maps.event.addDomListener(window, "load", loadmap); ';



    
    
    $output[] = '</script> ';
    
    $output[] = '</head> <body>';
    $output[] = '<div id="map-canvas" class="batchhtmltracking"></div>';
    $output[] = '
    <div class="adminlogoclass batchhtmltracking">
    <img alt="'.$globalprefrow['globalshortname'].' Logo" src="'.($globalprefrow['adminlogoabs']).'" /> ';
    

    
    $output[] = '<p class="plots">'.$txtprojectname.'</p> ';
    
    if (($foundjobs + $errorjobs)<>'0') {
    
        $output[] = ' <p class="plots">'.$foundjobs;
        if ($errorjobs>'0') { $output[] = ' / '.( $foundjobs + $errorjobs); }
        if (($foundjobs + $errorjobs)=='1') { $output[] = ' Track'; } else { $output[] = 'Tracks'; }
        
        $output[] = '</p>
        <p class="plots"> <span id="markertype1span"> </span>  Info Points</p>
        <p class="plots"> <span id="markertype2span"> </span> Total Positions</p>
        ';
    }
    
    
        $output[] = '<div class="orders"> ';
    
    if ($error<>'') { $output[] = $error; }
    
    reset($areasuccess);
    while (list(, $ids) = each($areasuccess)) { 
        $output[] = ' '.$ids.' ';
    }
    reset($idsuccess);
    while (list(, $ids) = each($idsuccess)) { 
        $output[] = ' '.$ids.' ';
    }
    
    $output[] = '
    </div>
    ';
    

    
    
    $output[] = '
    </div>
    <div class="cojmcopyright"><a href="http://cojm.co.uk" target="_blank" title="Courier Online Job Management">COJM &copy;'.date("Y").'</a></div>
    ';  
    
    
    
    $output[] = '
    <noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
        However, it seems JavaScript is either disabled or not supported by your browser. 
        To view Google Maps, enable JavaScript by changing your browser options, and then 
        try again.
        </noscript>
    </body> </html>';
    
    
    $htmloutput = join("\n", $output);
    function sanitize_output($buffer) {
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }


    $projectname = strtoupper(str_replace(' ','-',$projectname)); 
    $projectname = strtoupper(str_replace("'",'-',$projectname)); 
    $projectname = strtoupper(str_replace('"','-',$projectname)); 


    if ($projectname<>'') {
        $filename=$projectname.'-'.$globalprefrow['globalshortname'].'-Tracking.html';
    } else {
        $filename=$globalprefrow['globalshortname'].'-Tracking-'.date("U").'.html';
    }


    if (($error=='') and ($_REQUEST['btn_submit']=="batchhtmltracking")) {
        
        
        $htmloutput=sanitize_output($htmloutput);  // disable for debugging,

        
        
        
        // if ($outputtype=='kml') { 
        header('Content-type: text/html');
        header('Content-Disposition:attachment; filename="'.$filename.'"');
        echo $htmloutput;
    } else {
        echo $htmloutput;
        include 'cojmcron.php';
    }


} // ends check for _POST passed

?>