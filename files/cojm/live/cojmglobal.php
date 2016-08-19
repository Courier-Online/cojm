<?php
/*
    COJM Courier Online Operations Management
	cojmglobal.php - Main Change Settings File + Online License
    Copyright (C) 2016 S.Young cojm.co.uk

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

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {

 if ($serversecure=='') { 

echo 'ss : '.$serversecure;
 
// header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } 

 }
 }
 
$title = "COJM Settings";
?><!doctype html>
<html lang="en"><head>

<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" />
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>

<title><?php print ($title); ?> </title>
</head><body>
<?php 
$hasforms='0';
 include "changejob.php"; // just to get rider login id displayed !!!
$filename='cojmglobal.php';
$adminmenu=0; $settingsmenu=1;
include "cojmmenu.php"; 

$sql = "SELECT * FROM globalprefs"; 
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$globalprefrow=mysql_fetch_array($sql_result);





// spare is image 7 - 10

?>



<div class="Post Spaceout">

<p>You will need to do a full page refresh after some settings to check images / styles are displaying ok ( on the to-do list for version 2.1 ) </p>
<p>You are strongly advised to MAKE 1 CHANGE AT A TIME!! ( open up another broswer tab to view changes )<p>
<div id="tabs"><ul>
<li><a href="#tabs-3">System Info</a></li>
<li><a href="#tabs-4">General</a></li>
<li><a href="#tabs-1"><?php echo $globalprefrow['globalshortname']; /* companyname */ ?> Details</a></li>
<li><a href="#tabs-7">Theme</a></li>
<li><a href="#tabs-6">Maps / Tracking Icons</a></li>
<li><a href="#tabs-8">Favourite Tags</a></li>
<li><a href="#tabs-9">PDF Invoice</a></li>
<li><a href="#tabs-5">Financial</a></li>
<li><a href="#tabs-2">Advanced</a></li>
<li><a href="#tabs-10">License</a></li>
</ul>



<div id="tabs-3">   <!-- System Info -->


<fieldset><label class="fieldLabel"> COJM Version </label> <? echo $globalprefrow['cojmversion']; ?></fieldset>



<fieldset><label class="fieldLabel"> Currency </label> &<? echo $globalprefrow['currencysymbol']; ?>
</fieldset>

<fieldset><label class="fieldLabel"> Distance </label> <?
if ($globalprefrow['distanceunit']=='miles') { echo ' miles '; } 
if ($globalprefrow['distanceunit']=='km') { echo ' km '; } ?></fieldset>


<fieldset><label class="fieldLabel"> root http </label> <? echo $globalprefrow['httproot']; ?></fieldset>

<fieldset><label class="fieldLabel"> root https </label> <? echo $globalprefrow['httproots']; ?></fieldset>


<fieldset><label class="fieldLabel"> Backup sent from </label> <? echo $globalprefrow['backupemailfrom']; ?></fieldset>

<fieldset><label class="fieldLabel"> Backup sent to </label> <? echo $globalprefrow['backupemailto']; ?></fieldset>

<fieldset><label class="fieldLabel"> Location Quick Check </label>
<? echo $globalprefrow['locationquickcheck']; ?></fieldset>

<fieldset><label class="fieldLabel"> Location Client Invoice page </label>
<? echo $globalprefrow['clweb6']; ?></fieldset>




<fieldset><label class="fieldLabel"> Website usage policy location </label> 
<?php echo $globalprefrow['clweb2']; ?></fieldset>


<fieldset><label class="fieldLabel"> Rider CSS File : </label> 
<? echo $globalprefrow['courier1']; ?></fieldset>


<fieldset><label class="fieldLabel"> SERVER_PORT </label>
<?php echo $_SERVER['SERVER_PORT']; ?></fieldset>

<fieldset><label class="fieldLabel"> HTTPS </label>
<?php echo $_SERVER["HTTPS"]; ?></fieldset>


<fieldset><label class="fieldLabel"> SQL DB Name </label>
<?php 
$result=$dbh->query('select database()')->fetchColumn();
echo $result;

?></fieldset>


<fieldset><label class="fieldLabel"> IP Address </label>
<?php 

echo getHostByName(getHostName());

?></fieldset>





</div>


<div id="tabs-4"> <!-- general settings -->
<?php // general settings  ?>

<fieldset><label class="fieldLabel"> Number jobs displayed on homepage </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="numjobs" value="<? echo $globalprefrow['numjobs']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> Number jobs displayed on mobile </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="numjobsm" value="<? echo $globalprefrow['numjobsm']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> Index display Style </label> 
<select class="ui-state-default ui-corner-all pad" id="glob6" >
<option <?php if ( $globalprefrow['glob6']=='1' ) { echo 'SELECTED'; } ?> value="1"> Colour alternates on Individual job </option>
<option <?php if ( $globalprefrow['glob6']=='2' ) { echo 'SELECTED'; } ?> value="2"> Colour alternates on Day Difference </option>
</select>
</fieldset>









<fieldset><label class="fieldLabel"> Number jobs on <?php echo $globalprefrow['glob5']; ?> home </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="3" id="courier2" value="<? echo $globalprefrow['courier2']; ?>"></fieldset>

	


<fieldset><label for="glob11" class="fieldLabel"> Show Working Windows</label>
<input type="checkbox" id="glob11" value="1" <?php if ($globalprefrow['glob11']=='1') { echo 'checked';} ?>></fieldset>



<fieldset><label class="fieldLabel"> minutes difference </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="waitingtimedelay" value="<?php echo $globalprefrow['waitingtimedelay']; ?>">
on-site time to en-route with delivery before prompting to add waiting time
</fieldset>



<div class="line"> </div>
	
<fieldset><label class="fieldLabel"> Cyclist / Rider / Driver / Messenger </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="15" id="glob5" value="<?php echo $globalprefrow['glob5']; ?>"></fieldset>
	
	
	
<?php
		
$ridername = mysql_result(mysql_query(" SELECT cojmname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
$ridernamf = mysql_result(mysql_query(" SELECT poshname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
  
 
	
?>
	
	
	

<fieldset><label class="fieldLabel"> Unallocated COJM <?php echo $globalprefrow['glob5']; ?> name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="unrider1" value="<?php echo $ridername; ?>"></fieldset>


<fieldset><label class="fieldLabel"> Unallocated Public <?php echo $globalprefrow['glob5']; ?> name</label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="unrider2" value="<?php echo $ridernamf; ?>"></fieldset>


<div class="line"> </div>
	
	<fieldset><label class="fieldLabel"> grams CO<sub>2</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="co2perdist" value="<?php echo $globalprefrow['co2perdist']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> grams PM<sub>10</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="pm10perdist" value="<?php echo $globalprefrow['pm10perdist']; ?>"></fieldset>


	
	

</div>


<div id="tabs-7"> <!-- theme -->
<?php // theme settings  ?>
<fieldset><label class="fieldLabel"> &nbsp; </label>

<a class="newwin" href="http://www.w3schools.com/html/html_colors.asp" target="_blank">Colour Guide</a>, use HEX.

</fieldset>

<fieldset><label class="fieldLabel"> COJM Theme </label> 
<select class="ui-state-default ui-corner-left" id="clweb8" >
<option <?php if ( $globalprefrow['clweb8']=='base' ) { echo 'SELECTED'; } ?> value="base">base</option>
<option <?php if ( $globalprefrow['clweb8']=='blitzer' ) { echo 'SELECTED'; } ?> value="blitzer">blitzer</option>
<option <?php if ( $globalprefrow['clweb8']=='control' ) { echo 'SELECTED'; } ?> value="control">Control</option>
<option <?php if ( $globalprefrow['clweb8']=='cupertino' ) { echo 'SELECTED'; } ?> value="cupertino">cupertino</option>
<option <?php if ( $globalprefrow['clweb8']=='eggplant' ) { echo 'SELECTED'; } ?> value="eggplant">eggplant</option>
<option <?php if ( $globalprefrow['clweb8']=='hot-sneaks' ) { echo 'SELECTED'; } ?> value="hot-sneaks">hot-sneaks</option>
<option <?php if ( $globalprefrow['clweb8']=='humanity' ) { echo 'SELECTED'; } ?> value="humanity">humanity</option>
<option <?php if ( $globalprefrow['clweb8']=='overcast' ) { echo 'SELECTED'; } ?> value="overcast">overcast</option>
<option <?php if ( $globalprefrow['clweb8']=='pepper-grinder' ) { echo 'SELECTED'; } ?> value="pepper-grinder">pepper-grinder</option>
<option <?php if ( $globalprefrow['clweb8']=='redmond' ) { echo 'SELECTED'; } ?> value="redmond">redmond</option>
<option <?php if ( $globalprefrow['clweb8']=='smoothness' ) { echo 'SELECTED'; } ?> value="smoothness">smoothness</option>
<option <?php if ( $globalprefrow['clweb8']=='sunny' ) { echo 'SELECTED'; } ?> value="sunny">sunny</option>
<option <?php if ( $globalprefrow['clweb8']=='ui-lightness' ) { echo 'SELECTED'; } ?> value="ui-lightness">ui-lightness</option>
</select>


</fieldset>


	<fieldset><label class="fieldLabel"> Rider Top menu selected colour </label> #<input 
class="ui-state-default ui-corner-all pad" type="text" size="8" id="courier3" value="<? echo $globalprefrow['courier3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider Logo Location </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" id="courier4" value="<? echo $globalprefrow['courier4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider Logo Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" id="courier5" value="<? echo $globalprefrow['courier5']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider COC or COD Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" id="courier6" value="<? echo $globalprefrow['courier6']; ?>"></fieldset>


<div class="line"></div>
<fieldset><label class="fieldLabel"> JPG Admin Logo Relative </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="70" id="adminlogo" value="<?php echo $globalprefrow['adminlogo']; ?>">
</fieldset>


<fieldset><label class="fieldLabel"> &nbsp; </label>
 default is  ../../images/my_logo_199x60.jpg

For PDF Invoices and other, aim for about 200px x 60px
</fieldset>

<fieldset><label class="fieldLabel"> JPG Admin Logo Absolute </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="70" id="adminlogoabs" value="<?php echo $globalprefrow['adminlogoabs']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> &nbsp; </label>

should look somethikng like  https://example.com/images/my_logo_199x60.jpg
For PDF Invoices and other, aim for about 200px x 60px

</fieldset>


<fieldset><label class="fieldLabel"> Admin Logo Width </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" id="adminlogowidth" value="<?php echo $globalprefrow['adminlogowidth']; ?>"></fieldset>



<fieldset><label class="fieldLabel"> Admin Logo Height </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" id="adminlogoheight" value="<?php echo $globalprefrow['adminlogoheight']; ?>">

</fieldset>


<fieldset><label class="fieldLabel">
Admin Logo Preview </label>

<img alt="Please check settings if you cant see 2 logos" title="Relative" src="<?php echo $globalprefrow['adminlogo']; ?>" />

<img alt="Please check settings if you cant see 2 logos" title="Absolute" src="<?php echo $globalprefrow['adminlogoabs']; ?>" />


</fieldset>
<div class="line"></div>








	

<fieldset><label class="fieldLabel"> Highlight Colour </label> #
<input type="text" class="ui-state-default ui-corner-all pad" size="7" id="highlightcolour" value="<?php echo $globalprefrow['highlightcolour']; ?>">
<span style="padding:5px; background-color:#<?php echo $globalprefrow['highlightcolour']; ?>"> Currently set to this </span>
</fieldset>




<fieldset><label class="fieldLabel"> No <?php echo $globalprefrow['glob5']; /* Rider  */ ?> or Postcode </label> 
<input type="text" class="ui-corner-all pad" style="<?php echo $globalprefrow['highlightcolourno']; ?>" size="70" 
id="highlightcolourno" value="<?php echo $globalprefrow['highlightcolourno']; ?>">
</fieldset>






<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Awaiting Scheduling Annoying Sound 
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="sound1" value="<?php echo $globalprefrow['sound1']; ?>">
<a target="_blank" href="<?php echo $globalprefrow['httproot']; ?>/sounds/" >Sound Gallery</a>
Only use the main part of the filename, ie without the .mp3 extenstion.
</fieldset>

</div>


<div id="tabs-1">   <!-- business details -->
	
<fieldset><label class="fieldLabel" style="width:250px;"> Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="globalname" value="<? echo $globalprefrow['globalname']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Short Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="globalshortname" value="<? echo $globalprefrow['globalshortname']; ?>"></fieldset>
	
<fieldset><label class="fieldLabel"> Address 1 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress1" value="<? echo $globalprefrow['myaddress1']; ?>"></fieldset>
	
<fieldset><label class="fieldLabel"> Address 2 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress2" value="<? echo $globalprefrow['myaddress2']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress3" value="<? echo $globalprefrow['myaddress3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress4" value="<? echo $globalprefrow['myaddress4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress5" value="<? echo $globalprefrow['myaddress5']; ?>"></fieldset>
		
</div>

<div id="tabs-6">  <!-- maps / tracking icons -->
<?php // tracking and maps setup ?>



<fieldset><label class="fieldLabel"> Job viewed 
<?php echo '<img class="littleset" alt="viewedicon" title="viewedicon" src="'.$globalprefrow['viewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="viewedicon" value="<?php echo $globalprefrow['viewedicon']; ?>">
</fieldset>





<fieldset><label class="fieldLabel"> Not yet viewed 
<?php echo '<img class="littleset" alt="unviewedicon" title="unviewedicon" src="'.$globalprefrow['unviewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="unviewedicon" value="<?php echo $globalprefrow['unviewedicon']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> ASAP Icon 
<?php echo '<img class="littleset" alt="asap" title="asap" src="'.$globalprefrow['image5'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image5" value="<?php echo $globalprefrow['image5']; ?>">
</fieldset>


<fieldset><label class="fieldLabel"> Cargo Icon 
<?php echo '<img class="littleset" alt="cargo" title="cargo" src="'.$globalprefrow['image6'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image6" value="<?php echo $globalprefrow['image6']; ?>">
</fieldset>

<div class="line"> </div>

<fieldset><label class="fieldLabel"> Awaiting Scheduling 
<?php echo '<img class="littleset" alt="image1" title="image1" src="'.$globalprefrow['image1'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image1" value="<?php echo $globalprefrow['image1']; ?>">

See the /cojm/live/images/ directory for all images

</fieldset>


<fieldset><label class="fieldLabel"> Awaiting Collection 
<?php echo '<img class="littleset" alt="image2" title="image2" src="'.$globalprefrow['image2'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image2" value="<?php echo $globalprefrow['image2']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> Awaiting Delivery 
<?php echo '<img class="littleset" alt="image3" title="image3" src="'.$globalprefrow['image3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image3" value="<?php echo $globalprefrow['image3']; ?>">
</fieldset>


<fieldset><label class="fieldLabel"> Cyclist Icon
<?php echo '<img class="littleset" alt="image4" title="image4" src="'.$globalprefrow['image4'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image4" value="<?php echo $globalprefrow['image4']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> Dot for Google Earth
<?php echo '<img class="littleset" alt="imagecge" title="clweb3" src="'.$globalprefrow['clweb3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb3" value="<?php echo $globalprefrow['clweb3']; ?>">
Needs full root https:// address
</fieldset>







<div class="line"> </div>


<fieldset><label class="fieldLabel"> googlemapapiv3key </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="Get from google for your domain" size="60" id="googlemapapiv3key" value="<? echo $globalprefrow['googlemapapiv3key']; ?>"></fieldset>




<fieldset><label class="fieldLabel"> Default Google Map Latitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" id="glob1" value="<?php echo $globalprefrow['glob1']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Default Google Map Longitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" id="glob2" value="<?php echo $globalprefrow['glob2']; ?>"></fieldset>



<fieldset>
<label class="fieldLabel"> Line style for Google Earth </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb4" value="<?php echo $globalprefrow['clweb4']; ?>">
For use in the download kml / kmz files.
</fieldset>



<fieldset>
<label class="fieldLabel"> Initial Google Earth View </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb5" value=" <?php echo $globalprefrow['clweb5']; ?>">
For use in the download kml file
</fieldset>

<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Default New Postcode Town </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" id="glob3" value="<?php echo $globalprefrow['glob3']; ?>"></fieldset>

<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Default New Postcode Locality </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" id="glob4" value="<?php echo $globalprefrow['glob4']; ?>"></fieldset>

</div>

<div id="tabs-8">  <!-- favourite tags -->

<p>
To be integrated into favourites menu in future release

</p>

<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn1" value="<?php echo $globalprefrow['favusrn1']; ?>" /> 

</label>
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn2" value="<?php echo $globalprefrow['favusrn2']; ?>" /> </fieldset>

<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn3" value="<?php echo $globalprefrow['favusrn3']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn4" value="<?php echo $globalprefrow['favusrn4']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn5" value="<?php echo $globalprefrow['favusrn5']; ?>" /> 

</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn6" value="<?php echo $globalprefrow['favusrn6']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn7" value="<?php echo $globalprefrow['favusrn7']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn8" value="<?php echo $globalprefrow['favusrn8']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn9" value="<?php echo $globalprefrow['favusrn9']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn10" value="<?php echo $globalprefrow['favusrn10']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn11" value="<?php echo $globalprefrow['favusrn11']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn12" value="<?php echo $globalprefrow['favusrn12']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn13" value="<?php echo $globalprefrow['favusrn13']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn14" value="<?php echo $globalprefrow['favusrn14']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn15" value="<?php echo $globalprefrow['favusrn15']; ?>" />
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn16" value="<?php echo $globalprefrow['favusrn16']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn17" value="<?php echo $globalprefrow['favusrn17']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn18" value="<?php echo $globalprefrow['favusrn18']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn19" value="<?php echo $globalprefrow['favusrn19']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn20" value="<?php echo $globalprefrow['favusrn20']; ?>" /> </fieldset>




</div>


<div id="tabs-9">  <!-- pdf invoice settings -->




<fieldset><label class="fieldLabel"> PDF Alternating Row Colour</label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="8" id="invoicefooter" 
value="<?php echo $globalprefrow['invoicefooter']; ?>">
 ( white is ffffff ) : 
</fieldset>

<fieldset><label class="fieldLabel"> Invoice Total Colour </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="8" id="invoicetotalcolour" 
value="<?php echo $globalprefrow['invoicetotalcolour']; ?>">
 ( white is ffffff ) : 
</fieldset>

<div class="line"></div>


<fieldset><label class="fieldLabel">Title Font Name </label>
<select  class="ui-state-default ui-corner-left"  id="invoice1" >
<option <?php if ($globalprefrow['invoice1']=='almohanad') { echo ' selected '; } ?> value="almohanad">Almohanad </option>
<option <?php if ($globalprefrow['invoice1']=='dejavusans') { echo ' selected '; } ?> value="dejavusans">Dejavusans </option>
<option <?php if ($globalprefrow['invoice1']=='freesans') { echo ' selected '; } ?> value="freesans">Freesans </option>
<option <?php if ($globalprefrow['invoice1']=='freeserif') { echo ' selected '; } ?> value="freeserif">Freeserif </option>
<option <?php if ($globalprefrow['invoice1']=='helvetica') { echo ' selected="selected" '; } ?> value="Helvetica">Helvetica </option>
<option <?php if ($globalprefrow['invoice1']=='times') { echo ' selected '; } ?> value="times">Times </option>
<option <?php if ($globalprefrow['invoice1']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd">Trade Gothic </option>
</select>
</fieldset>


<fieldset><label class="fieldLabel"> Title Font Size </label>
 <input class="ui-state-default ui-corner-all pad" type="text" size="5" id="invoice2" 
 value="<? echo $globalprefrow['invoice2']; ?>">
</fieldset>
 
 
<fieldset><label class="fieldLabel"> Body Font  </label>
<select  class="ui-state-default ui-corner-left"  id="invoice5" >
<option <?php if ($globalprefrow['invoice5']=='almohanad') { echo ' selected '; } ?> value="almohanad">Almohanad </option>
<option <?php if ($globalprefrow['invoice5']=='dejavusans') { echo ' selected '; } ?> value="dejavusans">Dejavusans </option>
<option <?php if ($globalprefrow['invoice5']=='freesans') { echo ' selected '; } ?> value="freesans">Freesans </option>
<option <?php if ($globalprefrow['invoice5']=='freeserif') { echo ' selected '; } ?> value="freeserif">Freeserif </option>
<option <?php if ($globalprefrow['invoice5']=='helvetica') { echo ' selected="selected" '; } ?> value="Helvetica"> Helvetica </option>
<option <?php if ($globalprefrow['invoice5']=='times') { echo ' selected '; } ?> value="times">Times </option>
<option <?php if ($globalprefrow['invoice5']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd">Trade Gothic </option>
</select>
</fieldset>

<fieldset><label class="fieldLabel"> Font Size</label>
<input class="ui-state-default ui-corner-all pad" type="text" size="5" id="invoice6" 
value="<? echo $globalprefrow['invoice6']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> Footer Font Name </label>

<select  class="ui-state-default ui-corner-left"  id="invoice3" >
<option <?php if ($globalprefrow['invoice3']=='almohanad') { echo ' selected '; } ?> value="almohanad"> Almohanad </option>
<option <?php if ($globalprefrow['invoice3']=='dejavusans') { echo ' selected '; } ?> value="dejavusans"> Dejavusans </option>
<option <?php if ($globalprefrow['invoice3']=='freesans') { echo ' selected '; } ?> value="freesans"> freesans </option>
<option <?php if ($globalprefrow['invoice3']=='freeserif') { echo ' selected '; } ?> value="freeserif"> freeserif </option>
<option <?php if ($globalprefrow['invoice3']=='helvetica') { echo ' selected="selected" '; } ?> value="helvetica"> Helvetica </option>
<option <?php if ($globalprefrow['invoice3']=='times') { echo ' selected '; } ?> value="times"> Times </option>
<option <?php if ($globalprefrow['invoice3']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd"> Trade Gothic </option>
</select>
</fieldset>

<fieldset><label class="fieldLabel">  Font Size </label>
 <input class="ui-state-default ui-corner-all pad" type="text" size="5" id="invoice4" 
 value="<? echo $globalprefrow['invoice4']; ?>">
 </fieldset>


<div class="line"></div>


<fieldset><label class="fieldLabel"> &nbsp; </label>
Phrases ready to copy / paste into invoice comments : </fieldset> 

<fieldset><label class="fieldLabel"> &nbsp; </label>

<textarea id="invoicefooter2" class="ui-state-default ui-corner-all autosize pad"   style="width: 65%; outline:none;" ><?php echo $globalprefrow['invoicefooter2']; ?>
</textarea></fieldset>
<div class="line"></div>


<fieldset><label class="fieldLabel"> Invoice Footer </label>


<textarea id="invoicefooter3" class="ui-state-default ui-corner-all autosize pad"  style="width: 65%; outline:none;"><?php echo $globalprefrow['invoicefooter3']; ?>
</textarea>
</fieldset>
<fieldset><label class="fieldLabel"> &nbsp; </label>
The invoice reference number will be shown here, followed by </fieldset>

<fieldset><label class="fieldLabel"> &nbsp; </label>

<textarea class="ui-state-default ui-corner-all autosize pad" id="invoicefooter4" style="width: 65%; outline:none;" ><?php echo $globalprefrow['invoicefooter4']; ?></textarea>

</fieldset>



<fieldset><label class="fieldLabel"> &nbsp; </label>
<p>nb, Images with transparent backgrounds can NOT be in the PDF.</p>
</fieldset>


</div>


<div id="tabs-5">   <!-- Financial Settings -->
<?php // financial setup ?>



<fieldset><label class="fieldLabel"> VAT Band A (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="vatbanda" value="<? echo $globalprefrow['vatbanda']; ?>">

The VAT charge within a job is set by which service is used.
</fieldset>

<fieldset><label class="fieldLabel"> VAT Band B (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="vatbandb" value="<? echo $globalprefrow['vatbandb']; ?>"></fieldset>

<h3>Expense Type Names, leave blank if not required</h3>

<fieldset><label class="fieldLabel"> Expense Type 1  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc1" value="<? echo $globalprefrow['gexpc1']; ?>">
(eg Petty Cash)
</fieldset>

<fieldset><label class="fieldLabel"> Expense Type 2  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc2" value="<? echo $globalprefrow['gexpc2']; ?>">
(eg Business Account)
</fieldset>

<fieldset><label class="fieldLabel"> Expense Type 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc3" value="<? echo $globalprefrow['gexpc3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc4" value="<? echo $globalprefrow['gexpc4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc5" value="<? echo $globalprefrow['gexpc5']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 6  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc6" value="<? echo $globalprefrow['gexpc6']; ?>">

(only expense type which will request cheque numbers)

</fieldset>


<hr />

<fieldset><label> Text before list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" id="courier9"
style="width:60%;"><? echo $globalprefrow['courier9']; ?></textarea>
</fieldset>


<fieldset><label> Text after list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" id="courier10"
style="width:60%;"><? echo $globalprefrow['courier10']; ?></textarea>
</fieldset>

</div>


<div id="tabs-2">  <!-- Advanced Settings -->
<fieldset><label class="fieldLabel"> Page Timeout </label> 
<select class="ui-state-default ui-corner-left" id="formtimeout">
<option <?php if ( $globalprefrow['formtimeout']=='125' ) { echo 'SELECTED'; } ?> value="125">2 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='300' ) { echo 'SELECTED'; } ?> value="300">5 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='600' ) { echo 'SELECTED'; } ?> value="600">10 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='900' ) { echo 'SELECTED'; } ?> value="900">15 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='1200' ) { echo 'SELECTED'; } ?> value="1200">20 mins</option>

</select>

 timeout reminder not shown on mobile devices, if another user has changed job then the page may have already timed out.

</fieldset>


<div class="line"></div>
<fieldset><label class="fieldLabel"> Show Page Load Times </label>
<input type="checkbox" id="glob7" value="1" <?php if ($globalprefrow['glob7']=='1') { echo 'checked';} ?>></fieldset>



<fieldset><label class="fieldLabel"> Force Show COJM Debug info </label>
<input type="checkbox" id="showdebug" value="1" <?php if ($globalprefrow['showdebug']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label class="fieldLabel"> Force admin https SSL </label>
<input type="checkbox" id="forcehttps" value="1" <?php if ($globalprefrow['forcehttps']>0) { echo 'checked';} ?> ></fieldset>


<fieldset><label class="fieldLabel"> Show Settings on Mobile device</label>
<input type="checkbox" id="showsettingsmobile" value="1" <?php if ($globalprefrow['showsettingsmobile']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label class="fieldLabel"> COJM JS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="glob9" value="<? echo $globalprefrow['glob9']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> COJM CSS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="glob10" value="<? echo $globalprefrow['glob10']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Show Licensed Mail Options</label>
<input type="checkbox" id="showpostcomm" value="1" <?php if ($globalprefrow['showpostcomm']>0) { echo 'checked';} ?>></fieldset>

<fieldset><label class="fieldLabel"> Inaccurate Postcodes </label> 
<input type="checkbox" id="inaccuratepostcode" value="1" <?php if ($globalprefrow['inaccuratepostcode']>0) { echo 'checked';} ?>></fieldset>

<div class="line"> </div>

<fieldset><label class="fieldLabel"> backup ftp server </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="backupftpserver" 
value="<?php echo $globalprefrow['backupftpserver']; ?>"></fieldset>
<fieldset><label class="fieldLabel"> backup ftp username </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="backupftpusername" 
value="<?php echo $globalprefrow['backupftpusername']; ?>">
If your username has @ symbol try %40, eg user%40host.co.uk
</fieldset>

<fieldset title="This can be changed by editing your DB Connection File">
<label class="fieldLabel"> backup ftp passwd </label><?php echo htmlspecialchars(REMOTEFTPPASSWD); ?></fieldset>




<div class="line"> </div>

	

	





<fieldset><label class="fieldLabel"> Alert Email Address </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="me@example.com" type="email" size="60" id="glob8" value="<? echo $globalprefrow['glob8']; ?>"></fieldset>



</div>



<div id="tabs-10">   <!-- License Info -->

<h3>COJM - GNU Affero</h3>
<p>COJM is licensed under GNU Affero</p>
<hr />
<p>This project also includes GNU software and fonts from :
<ul>
<li>
Freefont : Free UCS scalable fonts: http://savannah.gnu.org/projects/freefont/
</li><li>
TCPDF : www.sourceforge.net/projects/tcpdf
</li><li>
JQuery : http://jquery.com/ JQueryUI
</li><li>
jQuery-Plugin "daterangepicker.jQuery.js" by Scott Jehl
</li><li>
datejs http://www.datejs.com/ 
</li><li>
markerclusterer https://github.com/googlemaps/js-marker-clusterer
</li><li>
zebra dialogue http://stefangabos.ro/jquery/zebra-dialog/
</li><li>
Autosize http://www.jacklmoore.com/autosize
</li><li>
phpMySQLAutoBackup : http://www.dwalker.co.uk/phpmysqlautobackup/
</li><li>
Trackstats : https://www.openhub.net/p/trackstats
 </p>

<hr />

<h3 >GNU AFFERO GENERAL PUBLIC LICENSE</h3>
<p >Version 3, 19 November 2007</p>

<p>Copyright &copy; 2007 Free Software Foundation,
Inc. &lt;<a href="http://fsf.org/">http://fsf.org/</a>&gt;
 <br />
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.</p>

<h3><a name="preamble"></a>Preamble</h3>

<p>The GNU Affero General Public License is a free, copyleft license
for software and other kinds of works, specifically designed to ensure
cooperation with the community in the case of network server software.</p>

<p>The licenses for most software and other practical works are
designed to take away your freedom to share and change the works.  By
contrast, our General Public Licenses are intended to guarantee your
freedom to share and change all versions of a program--to make sure it
remains free software for all its users.</p>

<p>When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
them if you wish), that you receive source code or can get it if you
want it, that you can change the software or use pieces of it in new
free programs, and that you know you can do these things.</p>

<p>Developers that use our General Public Licenses protect your rights
with two steps: (1) assert copyright on the software, and (2) offer
you this License which gives you legal permission to copy, distribute
and/or modify the software.</p>

<p>A secondary benefit of defending all users' freedom is that
improvements made in alternate versions of the program, if they
receive widespread use, become available for other developers to
incorporate.  Many developers of free software are heartened and
encouraged by the resulting cooperation.  However, in the case of
software used on network servers, this result may fail to come about.
The GNU General Public License permits making a modified version and
letting the public access it on a server without ever releasing its
source code to the public.</p>

<p>The GNU Affero General Public License is designed specifically to
ensure that, in such cases, the modified source code becomes available
to the community.  It requires the operator of a network server to
provide the source code of the modified version running there to the
users of that server.  Therefore, public use of a modified version, on
a publicly accessible server, gives the public access to the source
code of the modified version.</p>

<p>An older license, called the Affero General Public License and
published by Affero, was designed to accomplish similar goals.  This is
a different license, not a version of the Affero GPL, but Affero has
released a new version of the Affero GPL which permits relicensing under
this license.</p>

<p>The precise terms and conditions for copying, distribution and
modification follow.</p>

<h3><a name="terms"></a>TERMS AND CONDITIONS</h3>

<h4><a name="section0"></a>0. Definitions.</h4>

<p>&quot;This License&quot; refers to version 3 of the GNU Affero General Public
License.</p>

<p>&quot;Copyright&quot; also means copyright-like laws that apply to other kinds
of works, such as semiconductor masks.</p>

<p>&quot;The Program&quot; refers to any copyrightable work licensed under this
License.  Each licensee is addressed as &quot;you&quot;.  &quot;Licensees&quot; and
&quot;recipients&quot; may be individuals or organizations.</p>

<p>To &quot;modify&quot; a work means to copy from or adapt all or part of the work
in a fashion requiring copyright permission, other than the making of an
exact copy.  The resulting work is called a &quot;modified version&quot; of the
earlier work or a work &quot;based on&quot; the earlier work.</p>

<p>A &quot;covered work&quot; means either the unmodified Program or a work based
on the Program.</p>

<p>To &quot;propagate&quot; a work means to do anything with it that, without
permission, would make you directly or secondarily liable for
infringement under applicable copyright law, except executing it on a
computer or modifying a private copy.  Propagation includes copying,
distribution (with or without modification), making available to the
public, and in some countries other activities as well.</p>

<p>To &quot;convey&quot; a work means any kind of propagation that enables other
parties to make or receive copies.  Mere interaction with a user through
a computer network, with no transfer of a copy, is not conveying.</p>

<p>An interactive user interface displays &quot;Appropriate Legal Notices&quot;
to the extent that it includes a convenient and prominently visible
feature that (1) displays an appropriate copyright notice, and (2)
tells the user that there is no warranty for the work (except to the
extent that warranties are provided), that licensees may convey the
work under this License, and how to view a copy of this License.  If
the interface presents a list of user commands or options, such as a
menu, a prominent item in the list meets this criterion.</p>

<h4><a name="section1"></a>1. Source Code.</h4>

<p>The &quot;source code&quot; for a work means the preferred form of the work
for making modifications to it.  &quot;Object code&quot; means any non-source
form of a work.</p>

<p>A &quot;Standard Interface&quot; means an interface that either is an official
standard defined by a recognized standards body, or, in the case of
interfaces specified for a particular programming language, one that
is widely used among developers working in that language.</p>

<p>The &quot;System Libraries&quot; of an executable work include anything, other
than the work as a whole, that (a) is included in the normal form of
packaging a Major Component, but which is not part of that Major
Component, and (b) serves only to enable use of the work with that
Major Component, or to implement a Standard Interface for which an
implementation is available to the public in source code form.  A
&quot;Major Component&quot;, in this context, means a major essential component
(kernel, window system, and so on) of the specific operating system
(if any) on which the executable work runs, or a compiler used to
produce the work, or an object code interpreter used to run it.</p>

<p>The &quot;Corresponding Source&quot; for a work in object code form means all
the source code needed to generate, install, and (for an executable
work) run the object code and to modify the work, including scripts to
control those activities.  However, it does not include the work's
System Libraries, or general-purpose tools or generally available free
programs which are used unmodified in performing those activities but
which are not part of the work.  For example, Corresponding Source
includes interface definition files associated with source files for
the work, and the source code for shared libraries and dynamically
linked subprograms that the work is specifically designed to require,
such as by intimate data communication or control flow between those
subprograms and other parts of the work.</p>

<p>The Corresponding Source need not include anything that users
can regenerate automatically from other parts of the Corresponding
Source.</p>

<p>The Corresponding Source for a work in source code form is that
same work.</p>

<h4><a name="section2"></a>2. Basic Permissions.</h4>

<p>All rights granted under this License are granted for the term of
copyright on the Program, and are irrevocable provided the stated
conditions are met.  This License explicitly affirms your unlimited
permission to run the unmodified Program.  The output from running a
covered work is covered by this License only if the output, given its
content, constitutes a covered work.  This License acknowledges your
rights of fair use or other equivalent, as provided by copyright law.</p>

<p>You may make, run and propagate covered works that you do not
convey, without conditions so long as your license otherwise remains
in force.  You may convey covered works to others for the sole purpose
of having them make modifications exclusively for you, or provide you
with facilities for running those works, provided that you comply with
the terms of this License in conveying all material for which you do
not control copyright.  Those thus making or running the covered works
for you must do so exclusively on your behalf, under your direction
and control, on terms that prohibit them from making any copies of
your copyrighted material outside their relationship with you.</p>

<p>Conveying under any other circumstances is permitted solely under
the conditions stated below.  Sublicensing is not allowed; section 10
makes it unnecessary.</p>

<h4><a name="section3"></a>3. Protecting Users' Legal Rights From Anti-Circumvention Law.</h4>

<p>No covered work shall be deemed part of an effective technological
measure under any applicable law fulfilling obligations under article
11 of the WIPO copyright treaty adopted on 20 December 1996, or
similar laws prohibiting or restricting circumvention of such
measures.</p>

<p>When you convey a covered work, you waive any legal power to forbid
circumvention of technological measures to the extent such circumvention
is effected by exercising rights under this License with respect to
the covered work, and you disclaim any intention to limit operation or
modification of the work as a means of enforcing, against the work's
users, your or third parties' legal rights to forbid circumvention of
technological measures.</p>

<h4><a name="section4"></a>4. Conveying Verbatim Copies.</h4>

<p>You may convey verbatim copies of the Program's source code as you
receive it, in any medium, provided that you conspicuously and
appropriately publish on each copy an appropriate copyright notice;
keep intact all notices stating that this License and any
non-permissive terms added in accord with section 7 apply to the code;
keep intact all notices of the absence of any warranty; and give all
recipients a copy of this License along with the Program.</p>

<p>You may charge any price or no price for each copy that you convey,
and you may offer support or warranty protection for a fee.</p>

<h4><a name="section5"></a>5. Conveying Modified Source Versions.</h4>

<p>You may convey a work based on the Program, or the modifications to
produce it from the Program, in the form of source code under the
terms of section 4, provided that you also meet all of these conditions:</p>

<ul>

<li>a) The work must carry prominent notices stating that you modified
    it, and giving a relevant date.</li>

<li>b) The work must carry prominent notices stating that it is
    released under this License and any conditions added under section
    7.  This requirement modifies the requirement in section 4 to
    &quot;keep intact all notices&quot;.</li>

<li>c) You must license the entire work, as a whole, under this
    License to anyone who comes into possession of a copy.  This
    License will therefore apply, along with any applicable section 7
    additional terms, to the whole of the work, and all its parts,
    regardless of how they are packaged.  This License gives no
    permission to license the work in any other way, but it does not
    invalidate such permission if you have separately received it.</li>

<li>d) If the work has interactive user interfaces, each must display
    Appropriate Legal Notices; however, if the Program has interactive
    interfaces that do not display Appropriate Legal Notices, your
    work need not make them do so.</li>

</ul>

<p>A compilation of a covered work with other separate and independent
works, which are not by their nature extensions of the covered work,
and which are not combined with it such as to form a larger program,
in or on a volume of a storage or distribution medium, is called an
&quot;aggregate&quot; if the compilation and its resulting copyright are not
used to limit the access or legal rights of the compilation's users
beyond what the individual works permit.  Inclusion of a covered work
in an aggregate does not cause this License to apply to the other
parts of the aggregate.</p>

<h4><a name="section6"></a>6. Conveying Non-Source Forms.</h4>

<p>You may convey a covered work in object code form under the terms
of sections 4 and 5, provided that you also convey the
machine-readable Corresponding Source under the terms of this License,
in one of these ways:</p>

<ul>

<li>a) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by the
    Corresponding Source fixed on a durable physical medium
    customarily used for software interchange.</li>

<li>b) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by a
    written offer, valid for at least three years and valid for as
    long as you offer spare parts or customer support for that product
    model, to give anyone who possesses the object code either (1) a
    copy of the Corresponding Source for all the software in the
    product that is covered by this License, on a durable physical
    medium customarily used for software interchange, for a price no
    more than your reasonable cost of physically performing this
    conveying of source, or (2) access to copy the
    Corresponding Source from a network server at no charge.</li>

<li>c) Convey individual copies of the object code with a copy of the
    written offer to provide the Corresponding Source.  This
    alternative is allowed only occasionally and noncommercially, and
    only if you received the object code with such an offer, in accord
    with subsection 6b.</li>

<li>d) Convey the object code by offering access from a designated
    place (gratis or for a charge), and offer equivalent access to the
    Corresponding Source in the same way through the same place at no
    further charge.  You need not require recipients to copy the
    Corresponding Source along with the object code.  If the place to
    copy the object code is a network server, the Corresponding Source
    may be on a different server (operated by you or a third party)
    that supports equivalent copying facilities, provided you maintain
    clear directions next to the object code saying where to find the
    Corresponding Source.  Regardless of what server hosts the
    Corresponding Source, you remain obligated to ensure that it is
    available for as long as needed to satisfy these requirements.</li>

<li>e) Convey the object code using peer-to-peer transmission, provided
    you inform other peers where the object code and Corresponding
    Source of the work are being offered to the general public at no
    charge under subsection 6d.</li>

</ul>

<p>A separable portion of the object code, whose source code is excluded
from the Corresponding Source as a System Library, need not be
included in conveying the object code work.</p>

<p>A &quot;User Product&quot; is either (1) a &quot;consumer product&quot;, which means any
tangible personal property which is normally used for personal, family,
or household purposes, or (2) anything designed or sold for incorporation
into a dwelling.  In determining whether a product is a consumer product,
doubtful cases shall be resolved in favor of coverage.  For a particular
product received by a particular user, &quot;normally used&quot; refers to a
typical or common use of that class of product, regardless of the status
of the particular user or of the way in which the particular user
actually uses, or expects or is expected to use, the product.  A product
is a consumer product regardless of whether the product has substantial
commercial, industrial or non-consumer uses, unless such uses represent
the only significant mode of use of the product.</p>

<p>&quot;Installation Information&quot; for a User Product means any methods,
procedures, authorization keys, or other information required to install
and execute modified versions of a covered work in that User Product from
a modified version of its Corresponding Source.  The information must
suffice to ensure that the continued functioning of the modified object
code is in no case prevented or interfered with solely because
modification has been made.</p>

<p>If you convey an object code work under this section in, or with, or
specifically for use in, a User Product, and the conveying occurs as
part of a transaction in which the right of possession and use of the
User Product is transferred to the recipient in perpetuity or for a
fixed term (regardless of how the transaction is characterized), the
Corresponding Source conveyed under this section must be accompanied
by the Installation Information.  But this requirement does not apply
if neither you nor any third party retains the ability to install
modified object code on the User Product (for example, the work has
been installed in ROM).</p>

<p>The requirement to provide Installation Information does not include a
requirement to continue to provide support service, warranty, or updates
for a work that has been modified or installed by the recipient, or for
the User Product in which it has been modified or installed.  Access to a
network may be denied when the modification itself materially and
adversely affects the operation of the network or violates the rules and
protocols for communication across the network.</p>

<p>Corresponding Source conveyed, and Installation Information provided,
in accord with this section must be in a format that is publicly
documented (and with an implementation available to the public in
source code form), and must require no special password or key for
unpacking, reading or copying.</p>

<h4><a name="section7"></a>7. Additional Terms.</h4>

<p>&quot;Additional permissions&quot; are terms that supplement the terms of this
License by making exceptions from one or more of its conditions.
Additional permissions that are applicable to the entire Program shall
be treated as though they were included in this License, to the extent
that they are valid under applicable law.  If additional permissions
apply only to part of the Program, that part may be used separately
under those permissions, but the entire Program remains governed by
this License without regard to the additional permissions.</p>

<p>When you convey a copy of a covered work, you may at your option
remove any additional permissions from that copy, or from any part of
it.  (Additional permissions may be written to require their own
removal in certain cases when you modify the work.)  You may place
additional permissions on material, added by you to a covered work,
for which you have or can give appropriate copyright permission.</p>

<p>Notwithstanding any other provision of this License, for material you
add to a covered work, you may (if authorized by the copyright holders of
that material) supplement the terms of this License with terms:</p>

<ul>

<li>a) Disclaiming warranty or limiting liability differently from the
    terms of sections 15 and 16 of this License; or</li>

<li>b) Requiring preservation of specified reasonable legal notices or
    author attributions in that material or in the Appropriate Legal
    Notices displayed by works containing it; or</li>

<li>c) Prohibiting misrepresentation of the origin of that material, or
    requiring that modified versions of such material be marked in
    reasonable ways as different from the original version; or</li>

<li>d) Limiting the use for publicity purposes of names of licensors or
    authors of the material; or</li>

<li>e) Declining to grant rights under trademark law for use of some
    trade names, trademarks, or service marks; or</li>

<li>f) Requiring indemnification of licensors and authors of that
    material by anyone who conveys the material (or modified versions of
    it) with contractual assumptions of liability to the recipient, for
    any liability that these contractual assumptions directly impose on
    those licensors and authors.</li>

</ul>

<p>All other non-permissive additional terms are considered &quot;further
restrictions&quot; within the meaning of section 10.  If the Program as you
received it, or any part of it, contains a notice stating that it is
governed by this License along with a term that is a further restriction,
you may remove that term.  If a license document contains a further
restriction but permits relicensing or conveying under this License, you
may add to a covered work material governed by the terms of that license
document, provided that the further restriction does not survive such
relicensing or conveying.</p>

<p>If you add terms to a covered work in accord with this section, you
must place, in the relevant source files, a statement of the
additional terms that apply to those files, or a notice indicating
where to find the applicable terms.</p>

<p>Additional terms, permissive or non-permissive, may be stated in the
form of a separately written license, or stated as exceptions;
the above requirements apply either way.</p>

<h4><a name="section8"></a>8. Termination.</h4>

<p>You may not propagate or modify a covered work except as expressly
provided under this License.  Any attempt otherwise to propagate or
modify it is void, and will automatically terminate your rights under
this License (including any patent licenses granted under the third
paragraph of section 11).</p>

<p>However, if you cease all violation of this License, then your
license from a particular copyright holder is reinstated (a)
provisionally, unless and until the copyright holder explicitly and
finally terminates your license, and (b) permanently, if the copyright
holder fails to notify you of the violation by some reasonable means
prior to 60 days after the cessation.</p>

<p>Moreover, your license from a particular copyright holder is
reinstated permanently if the copyright holder notifies you of the
violation by some reasonable means, this is the first time you have
received notice of violation of this License (for any work) from that
copyright holder, and you cure the violation prior to 30 days after
your receipt of the notice.</p>

<p>Termination of your rights under this section does not terminate the
licenses of parties who have received copies or rights from you under
this License.  If your rights have been terminated and not permanently
reinstated, you do not qualify to receive new licenses for the same
material under section 10.</p>

<h4><a name="section9"></a>9. Acceptance Not Required for Having Copies.</h4>

<p>You are not required to accept this License in order to receive or
run a copy of the Program.  Ancillary propagation of a covered work
occurring solely as a consequence of using peer-to-peer transmission
to receive a copy likewise does not require acceptance.  However,
nothing other than this License grants you permission to propagate or
modify any covered work.  These actions infringe copyright if you do
not accept this License.  Therefore, by modifying or propagating a
covered work, you indicate your acceptance of this License to do so.</p>

<h4><a name="section10"></a>10. Automatic Licensing of Downstream Recipients.</h4>

<p>Each time you convey a covered work, the recipient automatically
receives a license from the original licensors, to run, modify and
propagate that work, subject to this License.  You are not responsible
for enforcing compliance by third parties with this License.</p>

<p>An &quot;entity transaction&quot; is a transaction transferring control of an
organization, or substantially all assets of one, or subdividing an
organization, or merging organizations.  If propagation of a covered
work results from an entity transaction, each party to that
transaction who receives a copy of the work also receives whatever
licenses to the work the party's predecessor in interest had or could
give under the previous paragraph, plus a right to possession of the
Corresponding Source of the work from the predecessor in interest, if
the predecessor has it or can get it with reasonable efforts.</p>

<p>You may not impose any further restrictions on the exercise of the
rights granted or affirmed under this License.  For example, you may
not impose a license fee, royalty, or other charge for exercise of
rights granted under this License, and you may not initiate litigation
(including a cross-claim or counterclaim in a lawsuit) alleging that
any patent claim is infringed by making, using, selling, offering for
sale, or importing the Program or any portion of it.</p>

<h4><a name="section11"></a>11. Patents.</h4>

<p>A &quot;contributor&quot; is a copyright holder who authorizes use under this
License of the Program or a work on which the Program is based.  The
work thus licensed is called the contributor's &quot;contributor version&quot;.</p>

<p>A contributor's &quot;essential patent claims&quot; are all patent claims
owned or controlled by the contributor, whether already acquired or
hereafter acquired, that would be infringed by some manner, permitted
by this License, of making, using, or selling its contributor version,
but do not include claims that would be infringed only as a
consequence of further modification of the contributor version.  For
purposes of this definition, &quot;control&quot; includes the right to grant
patent sublicenses in a manner consistent with the requirements of
this License.</p>

<p>Each contributor grants you a non-exclusive, worldwide, royalty-free
patent license under the contributor's essential patent claims, to
make, use, sell, offer for sale, import and otherwise run, modify and
propagate the contents of its contributor version.</p>

<p>In the following three paragraphs, a &quot;patent license&quot; is any express
agreement or commitment, however denominated, not to enforce a patent
(such as an express permission to practice a patent or covenant not to
sue for patent infringement).  To &quot;grant&quot; such a patent license to a
party means to make such an agreement or commitment not to enforce a
patent against the party.</p>

<p>If you convey a covered work, knowingly relying on a patent license,
and the Corresponding Source of the work is not available for anyone
to copy, free of charge and under the terms of this License, through a
publicly available network server or other readily accessible means,
then you must either (1) cause the Corresponding Source to be so
available, or (2) arrange to deprive yourself of the benefit of the
patent license for this particular work, or (3) arrange, in a manner
consistent with the requirements of this License, to extend the patent
license to downstream recipients.  &quot;Knowingly relying&quot; means you have
actual knowledge that, but for the patent license, your conveying the
covered work in a country, or your recipient's use of the covered work
in a country, would infringe one or more identifiable patents in that
country that you have reason to believe are valid.</p>

<p>If, pursuant to or in connection with a single transaction or
arrangement, you convey, or propagate by procuring conveyance of, a
covered work, and grant a patent license to some of the parties
receiving the covered work authorizing them to use, propagate, modify
or convey a specific copy of the covered work, then the patent license
you grant is automatically extended to all recipients of the covered
work and works based on it.</p>

<p>A patent license is &quot;discriminatory&quot; if it does not include within
the scope of its coverage, prohibits the exercise of, or is
conditioned on the non-exercise of one or more of the rights that are
specifically granted under this License.  You may not convey a covered
work if you are a party to an arrangement with a third party that is
in the business of distributing software, under which you make payment
to the third party based on the extent of your activity of conveying
the work, and under which the third party grants, to any of the
parties who would receive the covered work from you, a discriminatory
patent license (a) in connection with copies of the covered work
conveyed by you (or copies made from those copies), or (b) primarily
for and in connection with specific products or compilations that
contain the covered work, unless you entered into that arrangement,
or that patent license was granted, prior to 28 March 2007.</p>

<p>Nothing in this License shall be construed as excluding or limiting
any implied license or other defenses to infringement that may
otherwise be available to you under applicable patent law.</p>

<h4><a name="section12"></a>12. No Surrender of Others' Freedom.</h4>

<p>If conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot convey a
covered work so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you may
not convey it at all.  For example, if you agree to terms that obligate you
to collect a royalty for further conveying from those to whom you convey
the Program, the only way you could satisfy both those terms and this
License would be to refrain entirely from conveying the Program.</p>

<h4><a name="section13"></a>13. Remote Network Interaction; Use with the GNU General Public License.</h4>

<p>Notwithstanding any other provision of this License, if you modify the
Program, your modified version must prominently offer all users
interacting with it remotely through a computer network (if your version
supports such interaction) an opportunity to receive the Corresponding
Source of your version by providing access to the Corresponding Source
from a network server at no charge, through some standard or customary
means of facilitating copying of software.  This Corresponding Source
shall include the Corresponding Source for any work covered by version 3
of the GNU General Public License that is incorporated pursuant to the
following paragraph.</p>

<p>Notwithstanding any other provision of this License, you have permission
to link or combine any covered work with a work licensed under version 3
of the GNU General Public License into a single combined work, and to
convey the resulting work.  The terms of this License will continue to
apply to the part which is the covered work, but the work with which it is
combined will remain governed by version 3 of the GNU General Public
License.</p>

<h4><a name="section14"></a>14. Revised Versions of this License.</h4>

<p>The Free Software Foundation may publish revised and/or new versions of
the GNU Affero General Public License from time to time.  Such new
versions will be similar in spirit to the present version, but may differ
in detail to address new problems or concerns.</p>

<p>Each version is given a distinguishing version number.  If the
Program specifies that a certain numbered version of the GNU Affero
General Public License &quot;or any later version&quot; applies to it, you have
the option of following the terms and conditions either of that
numbered version or of any later version published by the Free
Software Foundation.  If the Program does not specify a version number
of the GNU Affero General Public License, you may choose any version
ever published by the Free Software Foundation.</p>

<p>If the Program specifies that a proxy can decide which future
versions of the GNU Affero General Public License can be used, that
proxy's public statement of acceptance of a version permanently
authorizes you to choose that version for the Program.</p>

<p>Later license versions may give you additional or different
permissions.  However, no additional obligations are imposed on any
author or copyright holder as a result of your choosing to follow a
later version.</p>

<h4><a name="section15"></a>15. Disclaimer of Warranty.</h4>

<p>THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY
APPLICABLE LAW.  EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT
HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM &quot;AS IS&quot; WITHOUT WARRANTY
OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE.  THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM
IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF
ALL NECESSARY SERVICING, REPAIR OR CORRECTION.</p>

<h4><a name="section16"></a>16. Limitation of Liability.</h4>

<p>IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MODIFIES AND/OR CONVEYS
THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY
GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE
USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF
DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD
PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS),
EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF
SUCH DAMAGES.</p>

<h4><a name="section17"></a>17. Interpretation of Sections 15 and 16.</h4>

<p>If the disclaimer of warranty and limitation of liability provided
above cannot be given local legal effect according to their terms,
reviewing courts shall apply local law that most closely approximates
an absolute waiver of all civil liability in connection with the
Program, unless a warranty or assumption of liability accompanies a
copy of the Program in return for a fee.</p>

<p>END OF TERMS AND CONDITIONS</p>

<hr />










</div>




</div>

</div>

<script type="text/javascript">
	$(function(){ $(".autosize").autosize();	}); // needs to be called before tabs
	$(function() {	$("#tabs").tabs(); });
	
	$(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+5));
});
	


var formbirthday=<?php echo microtime(TRUE); ?>; 	
var globalname='';
var newvalue='';



$(function() {  // change function container


$("#numjobs").change(function () {
 globalname='numjobs';	
 newvalue=$("#numjobs").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#numjobsm").change(function () {
 globalname='numjobsm';	
 newvalue=$("#numjobsm").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob6").change(function () {
 globalname='glob6';	
 newvalue=$("#glob6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier2").change(function () {
 globalname='courier2';	
 newvalue=$("#courier2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier3").change(function () {
 globalname='courier3';	
 newvalue=$("#courier3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier4").change(function () {
 globalname='courier4';	
 newvalue=$("#courier4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier5").change(function () {
 globalname='courier5';	
 newvalue=$("#courier5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier6").change(function () {
 globalname='courier6';	
 newvalue=$("#courier6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});




$("#glob11").change(function () {
 globalname='glob11';
if($('#glob11').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob1").change(function () {
 globalname='glob1';	
 newvalue=$("#glob1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob2").change(function () {
 globalname='glob2';	
 newvalue=$("#glob2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob3").change(function () {
 globalname='glob3';	
 newvalue=$("#glob3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob4").change(function () {
 globalname='glob4';	
 newvalue=$("#glob4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob5").change(function () {
 globalname='glob5';	
 newvalue=$("#glob5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unrider1").change(function () {
 globalname='unrider1';	
 newvalue=$("#unrider1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unrider2").change(function () {
 globalname='unrider2';	
 newvalue=$("#unrider2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogo").change(function () {
 globalname='adminlogo';	
 newvalue=$("#adminlogo").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogoabs").change(function () {
 globalname='adminlogoabs';	
 newvalue=$("#adminlogoabs").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogowidth").change(function () {
 globalname='adminlogowidth';	
 newvalue=$("#adminlogowidth").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogoheight").change(function () {
 globalname='adminlogoheight';	
 newvalue=$("#adminlogoheight").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#highlightcolour").change(function () {
 globalname='highlightcolour';	
 newvalue=$("#highlightcolour").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#highlightcolourno").change(function () {
 globalname='highlightcolourno';	
 newvalue=$("#highlightcolourno").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#viewedicon").change(function () {
 globalname='viewedicon';	
 newvalue=$("#viewedicon").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unviewedicon").change(function () {
 globalname='unviewedicon';	
 newvalue=$("#unviewedicon").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image1").change(function () {
 globalname='image1';	
 newvalue=$("#image1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image2").change(function () {
 globalname='image2';	
 newvalue=$("#image2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image3").change(function () {
 globalname='image3';	
 newvalue=$("#image3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image4").change(function () {
 globalname='image4';	
 newvalue=$("#image4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image5").change(function () {
 globalname='image5';	
 newvalue=$("#image5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image6").change(function () {
 globalname='image6';	
 newvalue=$("#image6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#sound1").change(function () {
 globalname='sound1';	
 newvalue=$("#sound1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#globalname").change(function () {
 globalname='globalname';	
 newvalue=$("#globalname").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#globalshortname").change(function () {
 globalname='globalshortname';	
 newvalue=$("#globalshortname").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress1").change(function () {
 globalname='myaddress1';	
 newvalue=$("#myaddress1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress2").change(function () {
 globalname='myaddress2';	
 newvalue=$("#myaddress2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress3").change(function () {
 globalname='myaddress3';	
 newvalue=$("#myaddress3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress4").change(function () {
 globalname='myaddress4';	
 newvalue=$("#myaddress4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress5").change(function () {
 globalname='myaddress5';	
 newvalue=$("#myaddress5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb3").change(function () {
 globalname='clweb3';	
 newvalue=$("#clweb3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb4").change(function () {
 globalname='clweb4';	
 newvalue=$("#clweb4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb5").change(function () {
 globalname='clweb5';	
 newvalue=$("#clweb5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn1").change(function () {
 globalname='favusrn1';	
 newvalue=$("#favusrn1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn2").change(function () {
 globalname='favusrn2';	
 newvalue=$("#favusrn2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn3").change(function () {
 globalname='favusrn3';	
 newvalue=$("#favusrn3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn4").change(function () {
 globalname='favusrn4';	
 newvalue=$("#favusrn4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn5").change(function () {
 globalname='favusrn5';	
 newvalue=$("#favusrn5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn6").change(function () {
 globalname='favusrn6';	
 newvalue=$("#favusrn6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn7").change(function () {
 globalname='favusrn7';	
 newvalue=$("#favusrn7").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn8").change(function () {
 globalname='favusrn8';	
 newvalue=$("#favusrn8").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn9").change(function () {
 globalname='favusrn9';	
 newvalue=$("#favusrn9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn10").change(function () {
 globalname='favusrn10';	
 newvalue=$("#favusrn10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn11").change(function () {
 globalname='favusrn11';	
 newvalue=$("#favusrn11").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn12").change(function () {
 globalname='favusrn12';	
 newvalue=$("#favusrn12").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn13").change(function () {
 globalname='favusrn13';	
 newvalue=$("#favusrn13").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn14").change(function () {
 globalname='favusrn14';	
 newvalue=$("#favusrn14").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn15").change(function () {
 globalname='favusrn15';	
 newvalue=$("#favusrn15").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn16").change(function () {
 globalname='favusrn16';	
 newvalue=$("#favusrn16").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn17").change(function () {
 globalname='favusrn17';	
 newvalue=$("#favusrn17").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn18").change(function () {
 globalname='favusrn18';	
 newvalue=$("#favusrn18").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn19").change(function () {
 globalname='favusrn19';	
 newvalue=$("#favusrn19").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn20").change(function () {
 globalname='favusrn20';	
 newvalue=$("#favusrn20").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#vatbanda").change(function () {
 globalname='vatbanda';	
 newvalue=$("#vatbanda").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#vatbandb").change(function () {
 globalname='vatbandb';	
 newvalue=$("#vatbandb").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#gexpc1").change(function () {
 globalname='gexpc1';	
 newvalue=$("#gexpc1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc2").change(function () {
 globalname='gexpc2';	
 newvalue=$("#gexpc2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc3").change(function () {
 globalname='gexpc3';	
 newvalue=$("#gexpc3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc4").change(function () {
 globalname='gexpc4';	
 newvalue=$("#gexpc4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc5").change(function () {
 globalname='gexpc5';	
 newvalue=$("#gexpc5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc6").change(function () {
 globalname='gexpc6';	
 newvalue=$("#gexpc6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier9").change(function () {
 globalname='courier9';	
 newvalue=$("#courier9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier10").change(function () {
 globalname='courier10';	
 newvalue=$("#courier10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob7").change(function () {
 globalname='glob7';
if($('#glob7').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob8").change(function () {
 globalname='glob8';
 newvalue=$("#glob8").val();
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#glob9").change(function () {
 globalname='glob9';
 newvalue=$("#glob9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob10").change(function () {
 globalname='glob10';
 newvalue=$("#glob10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#showdebug").change(function () {
 globalname='showdebug';
if($('#showdebug').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#forcehttps").change(function () {
 globalname='forcehttps';
if($('#forcehttps').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#showsettingsmobile").change(function () {
 globalname='showsettingsmobile';
if($('#showsettingsmobile').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#formtimeout").change(function () {
 globalname='formtimeout';	
 newvalue=$("#formtimeout").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#showpostcomm").change(function () {
 globalname='showpostcomm';
if($('#showpostcomm').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#inaccuratepostcode").change(function () {
 globalname='inaccuratepostcode';
if($('#inaccuratepostcode').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#backupftpserver").change(function () {
 globalname='backupftpserver';	
 newvalue=$("#backupftpserver").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#backupftpusername").change(function () {
 globalname='backupftpusername';	
 newvalue=$("#backupftpusername").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#co2perdist").change(function () {
 globalname='co2perdist';	
 newvalue=$("#co2perdist").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#pm10perdist").change(function () {
 globalname='pm10perdist';	
 newvalue=$("#pm10perdist").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});





$("#waitingtimedelay").change(function () {
 globalname='waitingtimedelay';	
 newvalue=$("#waitingtimedelay").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#googlemapapiv3key").change(function () {
 globalname='googlemapapiv3key';	
 newvalue=$("#googlemapapiv3key").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb8").change(function () {
 globalname='clweb8';	
 newvalue=$("#clweb8").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#invoicefooter").change(function () {
 globalname='invoicefooter';	
 newvalue=$("#invoicefooter").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#invoicefooter2").change(function () {
 globalname='invoicefooter2';	
 newvalue=$("#invoicefooter2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#invoicefooter3").change(function () {
 globalname='invoicefooter3';	
 newvalue=$("#invoicefooter3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoicefooter4").change(function () {
 globalname='invoicefooter4';	
 newvalue=$("#invoicefooter4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoicetotalcolour").change(function () {
 globalname='invoicetotalcolour';	
 newvalue=$("#invoicetotalcolour").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#invoice1").change(function () {
 globalname='invoice1';	
 newvalue=$("#invoice1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoice2").change(function () {
 globalname='invoice2';	
 newvalue=$("#invoice2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoice3").change(function () {
 globalname='invoice3';	
 newvalue=$("#invoice3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoice4").change(function () {
 globalname='invoice4';	
 newvalue=$("#invoice4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoice5").change(function () {
 globalname='invoice5';	
 newvalue=$("#invoice5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#invoice6").change(function () {
 globalname='invoice6';	
 newvalue=$("#invoice6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


});	



function changedvar(){
// alert(globalname + ' ' + newvalue);
 newvalue = btoa(newvalue);
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditglobals',
		formbirthday:formbirthday,
		globalname:globalname,
		newvalue:newvalue},
		type:'post',
        success: function(data) {
$('#tabs').append(data);
	},
		complete: function(data) {
		showmessage();
		}
});
}
</script>
<?php  include 'footer.php'; echo '</body></html>';