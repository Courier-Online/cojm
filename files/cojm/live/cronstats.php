<?php 

$df='H:i d M Y';

$nowsecs=date("U");
$nowday=date("j");
$nowmonth=date("n");
$nowyear=date("Y");
$nowhour=date("G");
$nowminute=date("i");

$sumtot='0';
$totalbytes='0';
$totalrows='0';
$totalbackups='0';

$lastran1='0';
$lastran2='0';
$lastran3='0';
$lastran4='0';
$lastran5='0';
$lastran6='0';
$lastran7='0';
$lastran8='0';
$lastran9='0';
$lastran10='0';
$lastran11='0';
$lastran12='0';
$lastran13='0';
$lastran14='0';
$lastran15='0';
$lastran16='0';
$lastran17='0';
$lastran18='0';
$lastran19='0';
$lastran20='0';





$sql = "SELECT * FROM cojm_cron "; $sql_result = mysql_query($sql,$conn_id)  or mysql_error();


 while ($cojmcron = mysql_fetch_array($sql_result)) {  extract($cojmcron);

 if ($cojmcron['id']=='1') { $lastran1=$cojmcron['time_last_fired'];  }
 if ($cojmcron['id']=='2') { $lastran2=$cojmcron['time_last_fired'];  }
 if ($cojmcron['id']=='3') { $lastran3=$cojmcron['time_last_fired'];  }
 if ($cojmcron['id']=='4') { $lastran4=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='5') { $lastran5=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='6') { $lastran6=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='7') { $lastran7=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='8') { $lastran8=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='9') { $lastran9=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='9') { $lastran9=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='10') { $lastran10=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='11') { $lastran11=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='12') { $lastran12=$cojmcron['time_last_fired'];  } 
 if ($cojmcron['id']=='13') { $lastran13=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='14') { $lastran14=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='15') { $lastran15=$cojmcron['time_last_fired'];  }   
 if ($cojmcron['id']=='16') { $lastran16=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='17') { $lastran17=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='18') { $lastran18=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='19') { $lastran19=$cojmcron['time_last_fired'];  }  
 if ($cojmcron['id']=='20') { $lastran20=$cojmcron['time_last_fired'];  }  
 
 
 
// echo $sumtot.' ';
// echo $cojmcron['currently_running'];
 
 $sumtot=$sumtot+$cojmcron['currently_running'];
 
}     // ends databse record loop	


// 3-12 is orders backup







// once a day after 1800
if ($nowhour>17) { $shouldhavelastran1= mktime( 18, 00, 00, $nowmonth, $nowday, $nowyear ); }
if ($nowhour<18) { $shouldhavelastran1= mktime( 18, 00, 00, $nowmonth, $nowday-1, $nowyear ); }

// on the hour each day
$shouldhavelastran2= mktime( $nowhour, 00, 00, $nowmonth, $nowday, $nowyear );

// 1am on a Sunday morning
$shouldhavelastran3= mktime( 01, 00, 00, $nowmonth, ($nowday-date("w")), $nowyear );


// half hourly
if ($nowminute>30 ) { $shouldhavelastran4= mktime( $nowhour, 30, 00, $nowmonth, $nowday, $nowyear ); }
else { $shouldhavelastran4= mktime( $nowhour, 00, 00, $nowmonth, $nowday, $nowyear ); }


// 2am first day of the month ( backup report )
if ($nowhour>2) {
$shouldhavelastran5= mktime( 02, 00, 00, $nowmonth, 01, $nowyear );
} else {
	$shouldhavelastran5= mktime( 02, 00, 00, $nowmonth-1, 01, $nowyear );
}




// one a day after 3am
if ($nowhour>03) {
$shouldhavelastran6= mktime( 03, 00, 00, $nowmonth, $nowday, $nowyear );
} else {
	$shouldhavelastran6= mktime( 03, 00, 00, $nowmonth, $nowday-1, $nowyear );
}



// twice day after 0800 and 1800
if ($nowhour<8) { $shouldhavelastran7= mktime( 18, 00, 00, $nowmonth, $nowday-1, $nowyear ); }
if ($nowhour<18) { $shouldhavelastran7= mktime( 08, 00, 00, $nowmonth, $nowday, $nowyear ); }
if ($nowhour>17) { $shouldhavelastran7= mktime( 18, 00, 00, $nowmonth, $nowday, $nowyear ); }
// if ($nowhour<18) { $shouldhavelastran7= mktime( 18, 00, 00, $nowmonth, $nowday-1, $nowyear ); }








/*

$infotext.=  '<br /> 1 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran1);
$infotext.=  '<br /> 1 last ran '. date ("H:i d M Y ", $lastran1);
$infotext.=  '<br /> 2 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran4);
$infotext.=  '<br /> 2 last ran '. date ("H:i d M Y ", $lastran2);
$infotext.=  '<br /> 3 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran3);
$infotext.=  '<br /> 3 last ran '. date ("H:i d M Y ", $lastran3);
$infotext.=  '<br /> 4 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran3);
$infotext.=  '<br /> 4 last ran '. date ("H:i d M Y ", $lastran4);
$infotext.=  '<br /> 5 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran3);
$infotext.=  '<br /> 5 last ran '. date ("H:i d M Y ", $lastran5);
$infotext.=  '<br /> 6 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran3);
$infotext.=  '<br /> 6 last ran '. date ("H:i d M Y ", $lastran6);
$infotext.=  '<br /> 7 shouldve ran '.date ("H:i d M Y ", $shouldhavelastran3);
$infotext.=  '<br /> 7 last ran '. date ("H:i d M Y ", $lastran7);

*/







// 1hr = 3600
// 1 day = 86400
// 7.5 days = 648000
// 33 days = 2851200


$run1=($lastran1-$shouldhavelastran1);if ($run1<'-94000') { $run1=' major-error '; } elseif ($run1<'0') { $run1=" minor-error "; } else { $run1=" all-good "; }
$run2=($lastran2-$shouldhavelastran4);if ($run2<'-30000') { $run2=' major-error '; } elseif ($run2<'0') { $run2=" minor-error "; } else { $run2=" all-good "; }
$run3=($lastran3-$shouldhavelastran3);if ($run3<'-648000') { $run3=' major-error '; } elseif ($run3<'0') { $run3=" minor-error "; } else { $run3=" all-good "; }
$run4=($lastran4-$shouldhavelastran3);if ($run4<'-648000') { $run4=' major-error '; } elseif ($run4<'0') { $run4=" minor-error "; } else { $run4=" all-good "; }
$run5=($lastran5-$shouldhavelastran3);if ($run5<'-648000') { $run5=' major-error '; } elseif ($run5<'0') { $run5=" minor-error "; } else { $run5=" all-good "; }
$run6=($lastran6-$shouldhavelastran6);if ($run6<'-648000') { $run6=' major-error '; } elseif ($run6<'0') { $run6=" minor-error "; } else { $run6=" all-good "; }
$run7=($lastran7-$shouldhavelastran3);if ($run7<'-648000') { $run7=' major-error '; } elseif ($run7<'0') { $run7=" minor-error "; } else { $run7=" all-good "; }
$run8=($lastran8-$shouldhavelastran3);if ($run8<'-648000') { $run8=' major-error '; } elseif ($run8<'0') { $run8=" minor-error "; } else { $run8=" all-good "; }
$run9=($lastran9-$shouldhavelastran3);if ($run9<'-648000') { $run9=' major-error '; } elseif ($run9<'0') { $run9=" minor-error "; } else { $run9=" all-good "; }
$run10=($lastran10-$shouldhavelastran3);if ($run10<'-648000') { $run10=' major-error '; } elseif ($run10<'0') { $run10=" minor-error "; } else { $run10=" all-good "; }
$run11=($lastran11-$shouldhavelastran3);if ($run11<'-648000') { $run11=' major-error '; } elseif ($run11<'0') { $run11=" minor-error "; } else { $run11=" all-good "; }
$run12=($lastran12-$shouldhavelastran3);if ($run12<'-648000') { $run12=' major-error '; } elseif ($run12<'0') { $run12=" minor-error "; } else { $run12=" all-good "; }
$run13=($lastran13-$shouldhavelastran5);if ($run13<'-2851200') { $run13=' major-error '; } elseif ($run13<'0') { $run13=" minor-error "; } else { $run13=" all-good "; }


$cronjobtable="

<table class='backupinfo'>
<tr><th>ID </th>
<th>Backup Type</th> <th>Should've Run</th> <th> Last Run</th></tr> ";

if($lastran1){$cronjobtable.='<tr class="'.$run1.'"><td>1</td><td> Once per day settings </td><td>'.date("$df",$shouldhavelastran1).'</td><td>'.date("$df",$lastran1).'</td></tr>'; }
if($lastran2){$cronjobtable.='<tr class="'.$run2.'"><td>2</td><td> Half Hourly Jobs </td><td>'.date("$df",$shouldhavelastran4).'</td><td>'.date("$df",$lastran2).'</td></tr>'; }
// if($lastran3){$cronjobtable.='<tr class="'.$run3.'"><td>3</td><td> Weekly All Jobs </td><td>'.date ("$df", $shouldhavelastran3).'</td><td>'.date("$df",$lastran3).'</td></tr>'; }
// if($lastran4){$cronjobtable.='<tr class="'.$run4.'"><td>4</td><td> Weekly All Jobs </td><td>'.date ("$df", $shouldhavelastran3).'</td><td>'.date("$df",$lastran4).'</td></tr>'; }
// if($lastran5){$cronjobtable.='<tr class="'.$run5.'"><td>5</td><td> Weekly All Jobs </td><td>'.date ("$df", $shouldhavelastran3).'</td><td>'.date("$df",$lastran5).'</td></tr>'; }
// if($lastran6){$cronjobtable.='<tr class="'.$run6.'"><td>6</td><td> Weekly All Jobs </td><td>'.date ("$df", $shouldhavelastran3).'</td><td>'.date("$df",$lastran6).'</td></tr>'; }
// if($lastran7){$cronjobtable.='<tr class="'.$run7.'"><td>7</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran7).'</td></tr>'; }
// if($lastran8){$cronjobtable.='<tr class="'.$run8.'"><td>8</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran8).'</td></tr>'; }
// if($lastran9){$cronjobtable.='<tr class="'.$run9.'"><td>9</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran9).'</td></tr>'; }
// if($lastran10){$cronjobtable.='<tr class="'.$run10.'"><td>10</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran10).'</td></tr>'; }
// if($lastran11){$cronjobtable.='<tr class="'.$run11.'"><td>11</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran11).'</td></tr>'; }
// if($lastran12){$cronjobtable.='<tr class="'.$run12.'"><td>12</td><td> Weekly All Jobs </td><td>'.date ("H:i d M Y", $shouldhavelastran3).'</td><td>'.date("$df",$lastran12).'</td></tr>'; }
 if($lastran13){$cronjobtable.='<tr class="'.$run13.'"><td>13</td><td> Monthly Backup Report </td><td>'.date ("H:i d M Y", $shouldhavelastran5).'</td><td>'.date("$df",$lastran13).'</td></tr>'; }



$cronjobtable.=" </table><br /> ";




$statdatestart= mktime( 02, 01, 00, $nowmonth-1, 01, $nowyear );




$lastmonthandrecentlog='';

$sql="SELECT * FROM `phpmysqlautobackup_log` WHERE `date`> ".$statdatestart." ORDER BY `date` DESC ";

$sql_result = mysql_query($sql,$conn_id)  or mysql_error();


 while ($cojmbackups = mysql_fetch_array($sql_result)) {  extract($cojmbackups);

 
 $totalbackups++;
 $totalbytes=$totalbytes+$cojmbackups['bytes'];
 $totalrows=$totalrows+$cojmbackups['lines'];
  
 $lastmonthandrecentlog.= '<tr class="alternate"> <td> ';
 
 $lastmonthandrecentlog.= $cojmbackups['type'];

 $lastmonthandrecentlog.= '</td><td> ';
 
$lastmonthandrecentlog.= date("$df",$cojmbackups['date']);

$lastmonthandrecentlog.= '</td><td> ';

// $lastmonthandrecentlog.= ' '.number_format(($cojmbackups['bytes']/1000), 2, '.', '') ." KB ";


if ($cojmbackups['bytes'] >= 1073741824)
        {
            $bytes = number_format(($cojmbackups['bytes']) / 1073741824, 2) . ' GB';
        }
        elseif (($cojmbackups['bytes']) >= 1048576)
        {
            $bytes=number_format((($cojmbackups['bytes']) / 1048576), 2) . ' MB';
        }
        elseif (($cojmbackups['bytes'])  >= 1024)
        {
            $bytes = number_format(($cojmbackups['bytes']) / 1024, 2) . ' kB';
        } 
	  elseif (($cojmbackups['bytes'])  > 1)
        {
            $bytes = $cojmbackups['bytes'] . ' bytes';
        }
          elseif (($cojmbackups['bytes'])  == 1)
        {
            $bytes = $cojmbackups['bytes'] . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }







$lastmonthandrecentlog.= $bytes;

$lastmonthandrecentlog.= '</td><td> ';

$lastmonthandrecentlog.= number_format($cojmbackups['lines'])." Rows ";

$lastmonthandrecentlog.= '</td> </tr>  ';

}




 $cojmbackups['bytes']=$totalbytes;
 
 
 if ($cojmbackups['bytes'] >= 1073741824)
        {
            $bytes = number_format(($cojmbackups['bytes']) / 1073741824, 2) . ' GB';
        }
        elseif (($cojmbackups['bytes']) >= 748576)
        {
            $bytes=number_format((($cojmbackups['bytes']) / 1048576), 2) . ' MB';
        }
        elseif (($cojmbackups['bytes'])  >= 1024)
        {
            $bytes = number_format(($cojmbackups['bytes']) / 1024, 2) . ' kB';
        } 
	  elseif (($cojmbackups['bytes'])  > 1)
        {
            $bytes = $cojmbackups['bytes'] . ' bytes';
        }
          elseif (($cojmbackups['bytes'])  == 1)
        {
            $bytes = $cojmbackups['bytes'] . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }


$lastmonthandrecentlog= '<table class="backupinfo">
<tr>
<th>Backup Type</th>
<th>Time</th>
<th>Size</th>
<th>Rows</th> </tr>
 <tr class="all-good"> <td> '.$totalbackups.' backups since </td> <td>'.date ("$df", $statdatestart).' </td><td> '.$bytes.' </td> <td>'.number_format($totalrows).' Rows</td> </tr>  

'. $lastmonthandrecentlog.= ' </table> ';




$auditstatdatestart= date("H:i d M Y ", $statdatestart);



// Issues with backups
$cronissue='0';
 $sql = "SELECT auditfilename FROM cojm_audit
 WHERE `cojm_audit`.`auditpage` = 'cojmcron.php' 
 AND  `cojm_audit`.`auditdatetime` > '". $auditstatdatestart ."' 
 AND `cojm_audit`.`auditfilename` = 'No Cron Ran'
 
 ";
 
 
// echo $sql;
 
 $sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); $cronissue=mysql_affected_rows(); 
// while ($row = mysql_fetch_array($sql_result)) { extract($row);  $cronissue++; }

$crontext= ' There were '.$cronissue.' ocassions since '.date("$df", $statdatestart).' where the cron failed due to jobs already running. '; 

?>