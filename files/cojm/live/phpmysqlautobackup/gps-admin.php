<?php
/*
    COJM Courier Online Operations Management
	gps-admin.php - caches job js / outline kml files
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

$infotext.= ' <br /> In gpsadmin ln 6';

$sql ="
SELECT cojmadmin_id, cojm_admin_job_ref FROM cojm_admin 
WHERE cojm_admin_stillneeded='1' AND cojmadmin_tracking='1' 
ORDER BY cojm_admin_job_ref DESC
LIMIT 1 ";

$statement = $dbh->prepare($sql);
$statement->execute();
$gpsadminrow = $statement->fetch(PDO::FETCH_ASSOC);

if($gpsadminrow) {
    $ID= $gpsadminrow['cojm_admin_job_ref']; 
    $cojm_admin_ref=$gpsadminrow['cojmadmin_id']; 
    
    $infotext.= '<br /> Job  '.$ID.' to be done on admin ref '.$cojm_admin_ref.'.  ';
    
    $sql =  " update cojm_admin set cojmadminstart = now() where cojmadmin_id = ? ";	
    $statement = $dbh->prepare($sql);
    $statement->execute([$cojm_admin_ref]);
    
    $sql="SELECT trackerid, publictrackingref, poshname, ShipDate, collectiondate, starttrackpause, finishtrackpause FROM Orders, Cyclist
    WHERE Orders.CyclistID = Cyclist.CyclistID
    AND Orders.ID = ? LIMIT 1";
    
    $statement = $dbh->prepare($sql);
    $statement->execute([$ID]);
    $orow = $statement->fetch(PDO::FETCH_ASSOC);
    
    
    $thistrackerid=$orow['trackerid'];
    
    $startpause=strtotime($orow['starttrackpause']); 
    $finishpause=strtotime($orow['finishtrackpause']); 
    $collecttime=strtotime($orow['collectiondate']); 
    $delivertime=strtotime($orow['ShipDate']); 
    if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
    if ($startpause <'10') { $startpause='9999999999'; }
    
    
    
    $sql = "SELECT latitude, longitude, speed, timestamp FROM `instamapper`  
    WHERE `device_key` = ?
    AND `timestamp` > ?
    AND `timestamp` 
    NOT BETWEEN ? AND ?
    AND `timestamp` < ?
    ORDER BY `timestamp` ASC"; 
    
    
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$thistrackerid,$collecttime,$startpause,$finishpause,$delivertime]);
    $data = $stmt->fetchAll();    

    
    if ($data) {
    
        $linecoords='';
        $prevts='';
        $markercount='0';
        $linecount='0';
        $customjs='';
        $kml=array();
        $markerout=array();
        $linarray=array();
        $kmllinarray=array();
        $linarray[]=' var line'.$ID.' = [ ';
        $markerout[]=' var markers'.$ID.' = [';
        $kmlmarker=array();
        
        $max_lat = '-99999';
        $min_lat =  '99999';
        $max_lon = '-99999';
        $min_lon =  '99999';
        
        $kml[] = ' <Document>';
        $kml[] = ' <name>'.$orow['publictrackingref'].'</name>';
        $kml[] = ' <open>0</open>';
        
        foreach ($data as $map) {
        
            $linecount++;
            $map['latitude']=round($map['latitude'],5);
            $map['longitude']=round($map['longitude'],5);
            
            $linarray[] = ' ['.$map['latitude'] . "," . $map['longitude'].'],';
            $kmllinarray[] = ' '.$map['longitude'] . "," . $map['latitude'];
            
            $thists=date('H:i A D j M ', $map['timestamp']);
            
            if ($thists<>$prevts) { // markers
            
                if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
                if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }
                if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
                if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }
                
                $markercount++;	 
                $comments= date(' H:i l jS F, Y ', $map['timestamp']).'<br />';
                
                $comments.= $orow['poshname'];
                
                /*
                
                if ($map['speed']) {
                    $comments.= ' <br /> '. round($map['speed']);
                if ($globalprefrow['distanceunit']=='miles') { $comments.=  'mph '; } 
                else if ($globalprefrow['distanceunit']=='km') { $comments.= 'km ph '; }
                }
                
                */
                
                $comments.= '<br />'.$ID;
                
                $markerout[]=" ['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . "," .$ID.'-'. $markercount ."],"; 
                
                
                $kmlmarker[] = ' <Placemark>';
                $kmlmarker[] = ' <name>' . date('H:i A D jS M', $map['timestamp']) . '</name>';
                $kmlmarker[] = '    <visibility>1</visibility> ';
                $tempc= '<description><![CDATA['. date ('D jS F, Y', $map['timestamp']).'<br />'. $orow['poshname'];
                if ($map['speed']) {
                    $tempc.=' <br>' .round($map['speed']) .' ';
                if ($globalprefrow['distanceunit']=='miles') { $tempc.= 'mph '; } 
                if ($globalprefrow['distanceunit']=='km') { $tempc.= 'km ph '; }
            }
            $tempc.=' ]]></description>';
            $kmlmarker[] = $tempc;
            $kmlmarker[] = ' <styleUrl>' .'#cojmstyle' .'</styleUrl>';
            $kmlmarker[] = ' <Point> <coordinates>' . $map['longitude'] . ","  . $map['latitude'] . ",0" .'</coordinates></Point>';
            $kmlmarker[] = ' </Placemark>';
            
            $tempa=$map['longitude'];
            $tempb=$map['latitude'];
            
            $prevts=date('H:i A D j M ', $map['timestamp']); 
                
            } // ends marker loop
        } // ends polyline loop
        
        
        $kml[] = '<LookAt>
            <longitude>'.$tempa.'</longitude>
            <latitude>'.$tempb.'</latitude>  
            <altitude>3000</altitude>
            <heading>0</heading>
            <tilt>10</tilt>
            <range>3500</range>
            <altitudeMode>relativeToGround</altitudeMode>
            </LookAt>';
        
        $kmlmarkerout = join("\n", $kmlmarker);
        $kml[] = $kmlmarkerout;
        
        $markerout = join("\n", $markerout);
        $markerout = rtrim($markerout, ',').'    ]; ';
        
        // echo ''.$markerout.'<hr />';
        // $linarray[]= ' ]; ';
        
        $lineout = join("\n", $linarray);
        $lineout = rtrim($lineout, ','); 
        $lineout=$lineout. ' ]; 
        max_lon.push("'.$max_lon.'"); 
        min_lon.push("'.$min_lon.'"); 
        max_lat.push("'.$max_lat.'"); 
        min_lat.push("'.$min_lat.'"); 
        markercount.push("'.$markercount.'");
        lineplotscount.push("'.$linecount.'"); ';
        
        $filecontent = $markerout.$lineout;
        
        $klineout = join("\n", $kmllinarray);
        
        
        
        
        $kml[] = " <Placemark>
            <styleUrl>#linebikestyle</styleUrl>
        <LineString>    
            <coordinates>".$klineout. " </coordinates>
        </LineString>
        </Placemark>";
        
        
        
        $kml[] = '<ScreenOverlay>
                <name>'.$globalprefrow['globalshortname'].'</name>
                <visibility>1</visibility>
                <Icon><href>'.($globalprefrow['adminlogo']).'</href></Icon>
                <overlayXY x="0" y="-1" xunits="fraction" yunits="fraction"/>
                <screenXY x="0" y="0" xunits="fraction" yunits="fraction"/>
                <rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>
                <size x="0" y="0" xunits="fraction" yunits="fraction"/>
            </ScreenOverlay>';
        
        
        $kml[] = '</Document>';
        
        
        
        $kmlcontent= join("\n", $kml);
        
        $mypath="cache/jstrack/".date('Y', strtotime($orow['ShipDate']))."/".date('m', strtotime($orow['ShipDate']))."/";
        
        if (!file_exists($mypath)) { mkdir($mypath, 0777, true); }
        
        
        $filename = $mypath.$ID.'tracks.js';
        $handle = fopen($filename,"w");
        fwrite($handle,$filecontent);
        fclose($handle);
        $infotext.= " <br />created JS CacheFile ".$filename.", ". $markercount." markers and ".$linecount." line points.";
        
        
        
        $filename = $mypath.$ID.'tracks.kml';
        $handle = fopen($filename,"w");
        fwrite($handle,$kmlcontent);
        fclose($handle);
        $infotext.= " <br />created KML CacheFile ".$filename.", ". $markercount." markers and ".$linecount." line points.";
    
    } // positions found check
    else {
        $infotext.=' No tracks found ';
    }
    
    $sql =  " update cojm_admin set cojm_admin_stillneeded = '0' , cojmadminfinish = now() where cojmadmin_id = ? ";	
    $statement = $dbh->prepare($sql);
    $statement->execute([$cojm_admin_ref]);
    
} // ends check for gps admin row

