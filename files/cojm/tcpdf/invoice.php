<?php

/*
    COJM Courier Online Operations Management
	invoice.php - Create a PDF invoice
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

// modded from :

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2010-08-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

 
$alpha_time = microtime(TRUE); 
 $filename="invoice.php";
 error_reporting(E_ALL);
ini_set('display_errors', '1');
 
 
// error_reporting( E_ERROR | E_WARNING | E_PARSE );
// include "../../administrator/cojm/updatetracking.php";

include "../live/C4uconnect.php";

$page=$_GET['page']; // preview , createpdf , addtodb
$setinvoiced=$_POST['setinvoiced'];



$expensedate=trim($_POST['expensedate']);
$expensedate = str_replace("/", ":", "$expensedate", $count);
$expensedate = str_replace(",", ":", "$expensedate", $count);
$expensedate = str_replace("-", ":", "$expensedate", $count);
$temp_ar=explode(":",$expensedate); 
$startday=$temp_ar['0']; 
$startmonth=$temp_ar['1']; 
$startyear=$temp_ar['2'];
$collectionsuntildate=date("Y-m-d 23:59:59", mktime(01, 01, 01, $startmonth, $startday, $startyear));

$setinvoiced=$_POST['setinvoiced'];
$newsetinvoiced=$_POST['setinvoiced'];
$hourly=$_POST['hourly'];
$exacttime=$_POST['exacttime'];
$year=$_POST['deliveryear'];
$month=$_POST['delivermonth'];
$day=$_POST['deliverday'];
$hour="00";
$minutes="00";
$collectionsfromdate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":00";
$showdelivery=$_POST['showdelivery'];
$showdeliveryaddress=$_POST['showdeliveryaddress'];
$clientid=trim($_POST['clientid']); 
$orderselectdep = trim($_POST['orderselectdep']);

$existinginvoiceref=$_POST['existinginvoiceref'];
$invdatemod=$_POST['invdate'];
$invcomments=trim($_POST['invcomments']);
$nowdate = date("Y-m-d H:i:s"); 
$orderselectdep = trim($_POST['orderselectdep']);
$addresstype= trim($_POST['addresstype']);
$ir='';
  



// $sql = "SELECT * FROM clientdep WHERE associatedclient = '$clientid' ORDER BY isactivedep DESC , depnumber DESC  ";


if ($orderselectdep<>'') {

$query = "SELECT associatedclient FROM clientdep  WHERE depnumber=$orderselectdep "; 
$clientid = mysql_result(mysql_query($query, $conn_id), 0); 

}

  
  
  
  
// $html='<h1>Page is '.$page.'. </h1>';
  
  
	// DOCUMENT_ROOT fix for IIS Webserver
	if ((!isset($_SERVER['DOCUMENT_ROOT'])) OR (empty($_SERVER['DOCUMENT_ROOT']))) {
		if(isset($_SERVER['SCRIPT_FILENAME'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
		} elseif(isset($_SERVER['PATH_TRANSLATED'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
		} else {
			// define here your DOCUMENT_ROOT path if the previous fails (e.g. '/var/www')
			$_SERVER['DOCUMENT_ROOT'] = '/';
		}
	}

	// Automatic calculation for the following K_PATH_MAIN constant
	$k_path_main = str_replace( '\\', '/', realpath(substr(dirname(__FILE__), 0, 0-strlen('config'))));
	if (substr($k_path_main, -1) != '/') {
		$k_path_main .= '/';
	}

	/**
	 * Installation path (/var/www/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_MAIN', $k_path_main);

//	define ('K_PATH_MAIN', '../../../cojm/tcpdf/');
	
	// Automatic calculation for the following K_PATH_URL constant
	$k_path_url = $k_path_main; // default value for console mode
	if (isset($_SERVER['HTTP_HOST']) AND (!empty($_SERVER['HTTP_HOST']))) {
		if(isset($_SERVER['HTTPS']) AND (!empty($_SERVER['HTTPS'])) AND strtolower($_SERVER['HTTPS'])!='off') {
			$k_path_url = 'https://';
		} else {
			$k_path_url = 'http://';
		}
		$k_path_url .= $_SERVER['HTTP_HOST'];
		$k_path_url .= str_replace( '\\', '/', substr(K_PATH_MAIN, (strlen($_SERVER['DOCUMENT_ROOT']) - 1)));
	}

	/**
	 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_URL', $k_path_url);

	
	/**
	 * path for PDF fonts
	 * use K_PATH_MAIN.'fonts/old/' for old non-UTF8 fonts
	 */
	define ('K_PATH_FONTS', 'fonts/');

	/**
	 * cache directory for temporary files (full path)
	 */
	define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');

	/**
	 * cache directory for temporary files (url path)
	 */
	define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');

	/**
	 *images directory
	 */
	define ('K_PATH_IMAGES', '');

	/**
	 * blank image
	 */
	define ('K_BLANK_IMAGE', K_PATH_IMAGES.'images/_blank.png');

	/**
	 * page format
	 */
	define ('PDF_PAGE_FORMAT', 'A4');

	/**
	 * page orientation (P=portrait, L=landscape)
	 */
	define ('PDF_PAGE_ORIENTATION', 'P');

	/**
	 * document creator
	 */
	define ('PDF_CREATOR', 'COJM / TCPDF');

	/**
	 * document author
	 */
	define ('PDF_AUTHOR', 'COJM / '.$globalprefrow["globalname"]);
	
	 $existinginvoiceref=$_POST['existinginvoiceref'];
	 $invoicedept=$_POST['orderselectdep'];

$query = "SELECT * FROM Clients WHERE Clients.CustomerID = $clientid";

$result_id = mysql_query ($query, $conn_id);
$clientrow=mysql_fetch_array($result_id);






	 
$temp_ar=explode("-",$nowdate); $spltime_ar=explode(" ",$temp_ar[2]); 

$temptime_ar=explode(":",$spltime_ar[1]); if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { 
$temptime_ar[0] = 0; $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
$temp2= date("Ymd", mktime($hour, $minutes, $second, $month, $day+$invdatemod, $year));
$invoiceref=date("$temp2") . $clientid ;


if ($orderselectdep) { $invoiceref=$invoiceref.$orderselectdep; }
if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }
$temp1=$globalprefrow["globalname"].' Invoice Ref : ' . "$invoiceref" ;
	 $today = date("l jS F Y");  
	 $nowdate = date("Y-m-d H:i:s"); 

$temp_ar=explode("-",$nowdate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; 
$temptime_ar[1] = 0; $temptime_ar[2] = 0; }
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
$temp2= date("l jS F Y", mktime($hour, $minutes, $second, $month, $day+$invdatemod, $year));

$todayDate = date("Y-m-d"); // current date

//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . +$clientrow['invoiceterms']);

$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);
// echo "After adding one month: ".date('l dS \o\f F Y', $dateOneMonthAdded)."<br>After taking away a month : ". $dateamonthago."<br><br>";

$invduedate = date("l jS F Y", mktime($hour, $minutes, $second, $month, ($day+$invdatemod+$clientrow['invoiceterms']), $year));

$invoicemysqldate= date ("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, ($day+$invdatemod), $year));

$invoiceduemysqldate= date ("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, ($day+$invdatemod+$clientrow['invoiceterms']), $year));

$pdfheaderstring='Invoice Date : ' . $temp2.', Due by ' . $invduedate;
		
	/**
	 * header title
	 */
	define ('PDF_HEADER_TITLE', $temp1);

	/**
	 * header description string
	 */
	define ('PDF_HEADER_STRING', "$pdfheaderstring");

	/**
	 * image logo
	 */
	define ('PDF_HEADER_LOGO', $globalprefrow['adminlogo']);
	
	$tempwidth=($globalprefrow['adminlogowidth']*25.4/150);
		
	/**
	 * header logo image width [mm]
	 */
	define ('PDF_HEADER_LOGO_WIDTH', $tempwidth);

	/**
	 *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
	 */
	define ('PDF_UNIT', 'mm');

	/**
	 * header margin
	 */
	define ('PDF_MARGIN_HEADER', 5);

	/**
	 * footer margin
	 */
	define ('PDF_MARGIN_FOOTER', 10);

	/**
	 * top margin
	 */
	define ('PDF_MARGIN_TOP', 25);

	/**
	 * bottom margin
	 */
	define ('PDF_MARGIN_BOTTOM', 25);

	/**
	 * left margin
	 */
	define ('PDF_MARGIN_LEFT', 15);

	/**
	 * right margin
	 */
	define ('PDF_MARGIN_RIGHT', 15);

	/**
	 * default main font name
	 */
	define ('PDF_FONT_NAME_MAIN', $globalprefrow['invoice1']);

	/*
	 * default main font size
	 */
	define ('PDF_FONT_SIZE_MAIN', $globalprefrow['invoice2']);

	/**
	 * default data font name
	 */
	define ('PDF_FONT_NAME_DATA', $globalprefrow['invoice3']);

	/**
	 * default data font size
	 */
	define ('PDF_FONT_SIZE_DATA', $globalprefrow['invoice4']);

	/**
	 * default monospaced font name
	 */
	define ('PDF_FONT_MONOSPACED', $globalprefrow['invoice5']);

	/**
	 * ratio used to adjust the conversion of pixels to user units
	 */
	define ('PDF_IMAGE_SCALE_RATIO', 1.25);

	/**
	 * magnification factor for titles
	 */
	define('HEAD_MAGNIFICATION', 1.1);

	/**
	 * height of cell repect font height
	 */
	define('K_CELL_HEIGHT_RATIO', 1.25);

	/**
	 * title magnification respect main font size
	 */
	define('K_TITLE_MAGNIFICATION', 1.3);

	/**
	 * reduction factor for small font
	 */
	define('K_SMALL_RATIO', 2/3);

	/**
	 * set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language
	 */
	define('K_THAI_TOPCHARS', false);

	/**
	 * if true allows to call TCPDF methods using HTML syntax
	 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
	 */
	define('K_TCPDF_CALLS_IN_HTML', false);
 
 	
	// uses these settings instead of default
 define('K_TCPDF_EXTERNAL_CONFIG', true);
  
  
  
  
  
  
  
if ($page=='createpdf') {
  
require_once('config/lang/eng.php');
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($globalprefrow['globalshortname']);
} // ends page pdf

if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }

$newinvoiceref=$invoiceref;
$pdfheadertitle=$globalprefrow['globalshortname'].' Invoice';

  if ($page=='createpdf') {
 

$pdf->SetTitle("$invoiceref");
$pdf->SetSubject("$temp1");
$pdf->SetKeywords($globalprefrow['globalshortname']);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE."", PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set font
$pdf->SetFont($globalprefrow['invoice5'], '', $globalprefrow['invoice6']);

// add a page
$pdf->AddPage();

} // ends page pdf


//    $tinvoicedept=$_POST['invoicedept'];
if ($invoicedept) {

$query = "SELECT * FROM clientdep WHERE clientdep.depnumber = $invoicedept";

$result_ids = mysql_query ($query, $conn_id);
$deprow=mysql_fetch_array($result_ids);

$depnm=$deprow['depname'];

// $html=$html.' Dep : '.$tinvoicedept.' '.$depnm;

}




$to = date('l jS F Y', strtotime($collectionsfromdate)); 
$from = date('l jS F Y', strtotime($collectionsuntildate)); 
$compnm=$clientrow['CompanyName'];





if ($orderselectdep>'0') { 

 $sql = "SELECT * FROM 
 Orders, Services 
 WHERE  Orders.ServiceID = Services.ServiceID 
 AND `Orders`.`orderdep` = '$orderselectdep' 
 AND `Orders`.`collectiondate` >= '1' 
 AND `Orders`.`collectiondate` <= '$collectionsuntildate'
 AND `Orders`.`status` < 110
 AND `Orders`.`status` > 78
 ORDER BY `Orders`.`collectiondate` ASC";

 } else {

 $sql = "SELECT * FROM 
 Orders, Services 
 WHERE  Orders.ServiceID = Services.ServiceID 
 AND CustomerID = '$clientid' 
 AND `Orders`.`collectiondate` >= '1' 
 AND `Orders`.`collectiondate` <= '$collectionsuntildate'
 AND `Orders`.`status` < 110
 AND `Orders`.`status` > 78
 ORDER BY `Orders`.`collectiondate` ASC";
 
 }
 
 $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 if ($todate) {} else { $todate=$row[collectiondate]; } }
 $to = date('l jS F Y', strtotime($todate)); 
$html = $html. '<table border="0" cellspacing="2" cellpadding="1">
<tr>
<th><h4>To :</h4></th>
<th><h4>From :</h4></th>
</tr>

<tr>
<td>'.$clientrow['CompanyName'];


 if ($orderselectdep<>'')  {
 // $infotext=$infotext.'Is Department';  
 $orderdep=$row['orderdep']; $depquery="SELECT * FROM clientdep WHERE depnumber = '$orderselectdep' LIMIT 1";
 $result=mysql_query($depquery); $drow=mysql_fetch_array($result);
 // $infotext=$infotext.'<br />Dep is '.$drow['depname'];

$html=$html.' ('.$drow['depname'].') '; }  









$html=$html.'</td>
<td>'.$globalprefrow['globalname'].'</td>
</tr>

<tr>
<td>'.$clientrow['invoiceAddress'].'</td>
<td>'.$globalprefrow['myaddress1'].'</td>
</tr>

<tr>
<td>'.$clientrow['invoiceAddress2'].'</td>
<td>'.$globalprefrow['myaddress2'].'</td>
</tr>

<tr>
<td>'.$clientrow['invoiceCity'].'</td>
<td>'.$globalprefrow['myaddress3'].'</td>
</tr>

<tr>
<td>'.$clientrow['invoiceCounty'].'</td>
<td>'.$globalprefrow['myaddress4'].'</td>
</tr>

<tr>
<td>'.$clientrow['invoicePostcode'].'</td>
<td>'.$globalprefrow['myaddress5'].'</td>
</tr>

</table><hr />';


if ($to==$from)  { $html=$html.'<h4>For services on '.$from.'</h4>'; } 
else { $html=$html.'<h4>For services between '.$to.' and '.$from.'.</h4>'; }
if ($invcomments) { $html=$html.' <h4>'.$invcomments.'</h4>'; }
$html=$html.'<hr /><br /><h4> </h4><br />';

$html=$html.'<table width="100%" cellspacing="0" cellpadding="2" border="0" ><tr>';
if ($showdelivery) { $html=$html .'<th >'; } 
else { $html=$html . '<th >'; } $html=$html . '<strong>Our<br />Ref</strong></th>';
if ($showdelivery=="1" ) { $html=$html .'<th width="20%">'; } else { $html=$html . '<th width="20%">'; }
if ($hourly) { $html=$html . '<strong>From</strong>'; } else { $html=$html . '<strong>Collection</strong>'; } 
$html=$html.'</th>';
if ($showdelivery=="1") { $html=$html.'<th width="20%">';  
if ($hourly) { $html=$html . '<strong>Until</strong></th>'; } 
else { $html=$html . '<strong>Delivery</strong></th>'; } }
$html=$html.'<th ><strong>Service</strong></th>
<th ><strong>VAT<br />Element</strong></th>
<th ><strong>Total<br />Excl. VAT</strong></th></tr>'; $i='0'; 


if ($orderselectdep) { 

$sql = "SELECT * FROM 
Orders, 
Services ,
Cyclist
WHERE  Orders.ServiceID = Services.ServiceID 
AND `Orders`.`orderdep` = '$orderselectdep' 
AND Orders.CustomerID = '$clientid' 
AND Orders.CyclistID = Cyclist.CyclistID 
AND `Orders`.`collectiondate` >= '1' 
AND `Orders`.`collectiondate` <= '$collectionsuntildate'
AND `Orders`.`status` < 110
AND `Orders`.`status` > 90
ORDER BY `Orders`.`collectiondate` ASC";

} else {

$sql = "SELECT * FROM 
Orders, 
Services ,
Cyclist
WHERE  Orders.ServiceID = Services.ServiceID 
AND Orders.CustomerID = '$clientid' 
AND Orders.CyclistID = Cyclist.CyclistID 
AND `Orders`.`collectiondate` >= '1' 
AND `Orders`.`collectiondate` <= '$collectionsuntildate'
AND `Orders`.`status` < 110
AND `Orders`.`status` > 90
ORDER BY `Orders`.`collectiondate` ASC";

}


// $html=$html.'<tr><td>'.$sql.'</td></tr>';


$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

// table loop
while ($row = mysql_fetch_array($sql_result)) { extract($row);
 
// if ($row['status']<110) {
 
 if ($exacttime) {
 $to   = date('H:i D jS F Y', strtotime($row['ShipDate'])); 
 $from = date('H:i D jS F Y', strtotime($row['collectiondate'])); 
 } 
 else { 
 $to   = date('D jS F Y', strtotime($row['ShipDate'])); 
 $from = date('D jS F Y', strtotime($row['collectiondate'])); 
 }

 $tablevatcost =$tablevatcost  + $row["vatcharge"]; 
 $tablecost =$tablecost  + $row["FreightCharge"];
 $tableitems=$tableitems + $numberitems;
 $ir=$ir+1; if( $ir & 1 ) { $bgc=$globalprefrow['invoicefooter'];} else {$bgc='ffffff';}
$html=$html.'<tr style="background-color: #'.$bgc.'"><td>';

if ((trim($globalprefrow['locationquickcheck'])) and ($showdeliveryaddress=="1")) {
$html=$html.'<a href="'.$globalprefrow['locationquickcheck'].'?quicktrackref='.$row['publictrackingref'].'" target="_blank">'.$row['publictrackingref'].'</a>';
}

else { $html=$html.$row[ID]; }

$html=$html.'</td><td>'.$from.'</td>';
if ($showdelivery=="1") { $html=$html.'<td>'.$to.'</td>'; }
$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');

$tempvatcost= number_format($row['vatcharge'], 2, '.', ''); 

$html=$html.'<td>'.$numberitems.' x '.$Service.'</td>
<td>&'.$globalprefrow["currencysymbol"].$tempvatcost.'</td>
<td>&'.$globalprefrow["currencysymbol"].$row["FreightCharge"].'</td>
</tr>';
$html=$html.'<tr style="background-color: #'.$bgc.'">';

if ($addresstype=='none') { }

if ($showdelivery=="1") { $html=$html.'<td> </td>'; }
$html=$html.'<td colspan="5" >'; 

 $CollectPC=trim($row['CollectPC']);
 $ShipPC=trim($row['ShipPC']);
 $CollectPC2 = str_replace(" ", "%20", "$CollectPC", $count);
 $ShipPC2 = str_replace(" ", "%20", "$ShipPC", $count);
 
 
 
 
 $fromfreeaddresspc = str_replace(" ", "%20", "$fromfreeaddress", $count);
 $tofreeaddresspc = str_replace(" ", "%20", "$tofreeaddress", $count); 
$htmlb='';

if ($row['hourlyothercount']) { $htmlb=$htmlb.$globalprefrow['glob5'].' : '.$row['poshname'].'. '; }


// orderselectdep

 if (($row['orderdep']) and ($orderselectdep==''))  {
 // $infotext=$infotext.'Is Department';  
 $orderdep=$row['orderdep']; $depquery="SELECT * FROM clientdep WHERE depnumber = '$orderdep' LIMIT 1";
 $result=mysql_query($depquery); $drow=mysql_fetch_array($result);
 // $infotext=$infotext.'<br />Dep is '.$drow['depname'];

$htmlb=$htmlb.'Department : '.$drow['depname'].'. '; }  

 

if ((trim($row['CollectPC'])) or (trim($fromfreeaddress))) {

if ($addresstype=='postcode') { // display just postcode
 $htmlb=$htmlb.'<span title="PickUp">PU</span> <a target="_blank" href="http://maps.google.com/maps?q='.$fromfreeaddresspc.'%20'.$CollectPC2.'">'.$row['CollectPC'].'</a> '; 
} 

if ($addresstype=='full') { // use freetext details

$htmlb=$htmlb.'<span title="PickUp">PU</span> <a target="_blank" href="http://maps.google.com/maps?q='.$fromfreeaddresspc.'%20'.$CollectPC2.'">'.$fromfreeaddress.' '.$row['CollectPC']
.'</a> <br />'; } 






$n='0'; $i='1'; 
while ($i<'21') {
if (((trim($row["enrpc$i"]))<>'') or (trim($row["enrft$i"]<>''))) {
$n++;
}
$i++; 
}
if ($n=='1') { $htmlb=$htmlb. ' via '.$row["enrft1"] .' '.$row["enrpc1"].'<br />'; }
if ($n>'1') { $htmlb=$htmlb. ' via '.$n.' stops '; }


} // ends check for collection

if ($showdeliveryaddress=='1') {

if ((trim($row['ShipPC'])) or (trim($tofreeaddress))) {

if ($addresstype=='postcode') { // display just postcode


$htmlb=$htmlb.'To <a target="_blank" href="http://maps.google.com/maps?q='.$tofreeaddresspc.'%20'.$ShipPC2.'">'.$row["ShipPC"].'</a>. '; } 

if ($addresstype=='full') { // display full

$htmlb=$htmlb.'To <a target="_blank" href="http://maps.google.com/maps?q='.$tofreeaddresspc.'%20'.$ShipPC2.'">'.$tofreeaddress.' '.$row["ShipPC"].'</a>. <br />'; 

}

} // ends check for having text AND postcode

} // ends check for option to show delivery address







$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
$stmt->execute();
$haspod = $stmt->rowCount();
if  ($haspod) { $htmlb.= ' <a title="Proof of Delivery" href="'.$globalprefrow['httproots'].'/cojm/podimage.php?id='. $row['publictrackingref'].'" >POD</a> '; }








$query = "SELECT CyclistID, trackerid FROM Cyclist ORDER BY CyclistID"; 
$result_idt = mysql_query ($query, $conn_id); 
while (list ($CyclistID, $trackerid) = mysql_fetch_row ($result_idt)) { 
if ($row['CyclistID'] == $CyclistID) { $thistrackerid=$trackerid; }}






 $startpause=strtotime($row['starttrackpause']); 
 $finishpause=strtotime($row['finishtrackpause']);  
 $collecttime=strtotime($row['collectiondate']); 
 $delivertime=strtotime($row['ShipDate']); 
 if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
 if ($startpause <'10') { $startpause='9999999999'; } 
 if (($row['status']<'86') and ($delivertime < '200')) { $delivertime='9999999999'; } 
 if ($row['status']<'50') { $delivertime='0'; } 
 if ($collecttime < '10') { $collecttime='9999999999';} 
// $html=$html.' Start pause : '.$startpause.' collect : '.$collecttime.' trackerid : '.$thistrackerid.' delivertime : '.$delivertime.'';
$trasql = "SELECT * FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' AND '$finishpause' 
AND `timestamp` <= '$delivertime' "; 
$trasql_result = mysql_query($trasql,$conn_id)  or mysql_error(); 
$trsumtot=mysql_affected_rows();   
 if ($trsumtot>'1.5') { $htmlb=$htmlb.' <a href="'.$globalprefrow['httproots'].'/createkml.php?id='.
 $row['publictrackingref'].'">Tracking</a> '; }



















if ($row['requestor']) { $htmlb=$htmlb.'Requested by '. $row["requestor"].'. '; }

if (trim($row['clientjobreference'])) { $htmlb.='Your Ref : ' .$row['clientjobreference'].'.  '; }



if ($row['jobcomments']) { $htmlb=$htmlb.($row['jobcomments']).' '; }



////////////////////////////////////////////////////////////////////////

$compco2='';
$comppm10='';
$tableco2='';
$tablepm10='';


if ($row['co2saving']>'0.001')  {$tableco2=$row["co2saving"];   } else { $tableco2 =($row['numberitems'])*($row["CO2Saved"]);  }
if ($row['pm10saving']>'0.001') {$tablepm10=$row["pm10saving"]; } else { $tablepm10=($row['numberitems'])*($row["PM10Saved"]); }	
	 
	 
$compco2=$tableco2;
$comppm10=$tablepm10;	


 
if ($tablepm10>'1000') {
$tablepm10=($tablepm10/'1000');
$tablepm10 = number_format($tablepm10, 1, '.', ',');
$tablepm10= $tablepm10.'kg'; }
 else { if ($tablepm10>'0.001') { 
  $tablepm10 = number_format($tablepm10, 1, '.', ',');
 $tablepm10=$tablepm10.' grams'; 
}} 

if ($tableco2>'1000') {
$tableco2=($tableco2/'1000');
$tableco2 = number_format($tableco2, 1, '.', ',');
$tableco2= $tableco2.'kg'; }
 else {
 if ($tableco2>'0.001') { $tableco2=$tableco2.' grams'; 
}} 


//	 echo ' table co2 : '.$tableco2.' table pm10 : '.$tablepm10;
	
	
$CO2text='';
$pm10text='';	

if ($compco2>'0.001')  { $CO2text=" CO<sub>2</sub> Saved : ". $tableco2.'.';    }
if ($comppm10>'0.0001') { $pm10text=" PM<sub>10</sub> Saved : ".$tablepm10.'.'; }
 
 $htmlb=$htmlb. $CO2text . $pm10text; 

////////////////////////////////////////////////////////////////////////










 
// starts new style pricing
 
 
// $htmlb=$htmlb.'iscp:'. $row['iscustomprice'].'.';
 
 
if ($row['iscustomprice']<>'1') { //

// $htmlb=$htmlb.' Got to 770 ';
 
if ($row['chargedbybuild']=='1') {

$htmlb=$htmlb.'<br />';

$nquery = "SELECT * FROM chargedbybuild ORDER BY cbborder ASC"; 
$cbsql_result = mysql_query($nquery,$conn_id)  or mysql_error(); 
while ($cbrow = mysql_fetch_array($cbsql_result)) { extract($cbrow);

$cbr=$cbrow['chargedbybuildid'];

if (($row["cbbc$cbr"]>'0') and ($row["cbb$cbr"]<>'0.00')) {

$htmlb=$htmlb.= ' '.$cbrow['cbbname'].' &'. $globalprefrow['currencysymbol'] . $row["cbb$cbr"];
  
}
}
} // ends check to see if job is cbb
 
 
 
 if ($row['clientdiscount']>'0') {
  $htmlb=$htmlb.' Client Discount : &'. $globalprefrow['currencysymbol'].$row["clientdiscount"]; 
}
  
 
 } // ends check for custom price
 // finishes new style pricing
 
 
 
// $html=$html.$row['status'];
// $html=$html.' total tracks: '.$trsumtot;
$html=$html.$htmlb.'</td></tr>';


if ($htmlb) { 

$html=$html.'<tr style="background-color: #'.$bgc.'" >';
if ($showdelivery=="1") { $html=$html.'<td> </td>'; }
$html=$html.'<td colspan="5" > </td></tr>'; }




} // end loop individual job

$totalincvat=$tablevatcost+$tablecost;

// $tablecost=    number_format($tablecost, 2, '.', ','); 
// $tablevatcost= number_format($tablevatcost, 2, '.', ','); 
// $totalincvat= number_format($totalincvat, 2, '.', ',');

$html=$html.$htmlnew.'<tr><td colspan="2"> </td>';

if ($showdelivery=='1') { $html=$html.'<td> </td>';}




$html=$html.'




<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-top-width:    1px; border-top-color: #32649b; border-top-style: Solid;
border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].' "  >
VAT Total</td>






<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-top-width:    1px; border-top-color: #32649b; border-top-style: Solid;
border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].' "  >
ex VAT Total</td>

<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-top-width:    1px; border-top-color: #32649b; border-top-style: Solid;
border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].';"  > Grand Total
</td></tr>';

$bgc=$globalprefrow['invoicefooter']; 
$html=$html.'<tr style="background-color: #'.$bgc.'" ><td><strong>Total :</strong></td>';
$html=$html.'<td><strong>';


$modtableitems=$tableitems - floor($tableitems);

// $html.=' modtableitems:'.$modtableitems.' ';

if ($modtableitems=='0') {

$tableitems= number_format($tableitems, 0, '.', ',');

}

$html.=$tableitems;

if ($tableitems=='1') { $html=$html.' Item'; } else { $html=$html.' Items'; }
$html=$html.'</strong></td>';

if ($showdelivery) { $html=$html .'<td>Invoice Terms : '.$clientrow['invoiceterms'].' days.</td>'; }



$html=$html.'
<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-top-width:    1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].';" >

<strong>&'.$globalprefrow["currencysymbol"].number_format($tablevatcost, 2, '.', ',').'</strong></td>

<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
border-top-width:    1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
<strong>&'.$globalprefrow["currencysymbol"].number_format($tablecost, 2, '.', ',').'</strong></td>

<td style="
border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
border-top-width:    1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
<strong> &'.$globalprefrow["currencysymbol"].number_format($totalincvat, 2, '.', ',').'</strong></td>


</tr>
<tr>';

if ($showdelivery=='1') { $html=$html.'<td> </td>';}

$html=$html.'<td colspan="5"></td></tr>
</table><br /><hr />';

///////////////////////////////////////////////////////////







$totco2sql="SELECT * FROM Orders, Services 
WHERE Orders.ServiceID = Services.ServiceID 
AND Orders.status >= 90 
AND Orders.CustomerID='$CustomerID'";



$totco2sql_result = mysql_query($totco2sql,$conn_id);
while ($totco2row = mysql_fetch_array($totco2sql_result)) {
     extract($totco2row);
	 if ($totco2row['co2saving']>'0.001') { $ttableco2=$ttableco2+$totco2row["co2saving"]; }
	 else { $ttableco2 = $ttableco2 + (($totco2row['numberitems'])*($totco2row["CO2Saved"])); }
	 
	 if ($totco2row['pm10saving']>'0.001') {$ttablepm10=$ttablepm10+$totco2row["pm10saving"]; }
     else { $ttablepm10=$ttablepm10 + (($totco2row['numberitems'])*($totco2row["PM10Saved"])); }	 
}


$tcomppm10=$ttablepm10;
$tcompco2=$ttableco2;

if ($ttablepm10>'1000') {
$ttablepm10=($ttablepm10/'1000');
 $ttablepm10 = number_format($ttablepm10, 1, '.', ',');
$ttablepm10= $ttablepm10.'kg'; }
 else { if ($ttablepm10>'0.00001') { 
  $ttablepm10 = number_format($ttablepm10, 1, '.', ',');
 $ttablepm10=$ttablepm10.' grams'; 
}} 

if ($tcompco2>'1000000') {
$ttableco2=($ttableco2/'1000'); $ttableco2 = number_format($ttableco2, 0, '.', ',');
$ttableco2= $ttableco2.'kg'; }

if (($tcompco2>'1000') and ($tcompco2<'1000000')) {
$ttableco2=($ttableco2/'1000'); $ttableco2 = number_format($ttableco2, 1, '.', ',');
$ttableco2= $ttableco2.'kg'; }

if ($tcompco2<'1000') { $ttableco2=$ttableco2.' grams'; }

if ($tcompco2) {
$html=$html. '<p>We have helped '.$clientrow['CompanyName'].' to save '.$ttableco2 .' CO<sub>2</sub> to date, along with '.$ttablepm10.' of PM<sub>10</sub> (particulate) emissions.</p><hr />';
}




///////////////////////////////////////////////////////////


$html.=$globalprefrow['invoicefooter3'].$newinvoiceref.$globalprefrow['invoicefooter4'];



$html = str_replace ("&QUOT;", "&quot;", $html);
$html = str_replace ("&AMP;", "&amp;", $html);





























  if ($page=='createpdf') {
 

// output the HTML content
$pdf->writeHTML($html, true, false, false, false, '');
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------
//Close and output PDF document

$endfilename=$globalprefrow['globalshortname'].'_'.$compnm.'_';

if ($depnm) { $endfilename=$endfilename.$depnm.'_'; }

$endfilename=$endfilename.'Invoice_ref_'.$newinvoiceref.'.pdf';

 $pdf->Output($endfilename, 'D');
 
 }  else {
  
include ("../live/changejob.php");
 
 echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
 <head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <link href="../live/favicon.ico" rel="shortcut icon" type="image/x-icon" >
 <link rel="stylesheet" type="text/css" href="../live/cojm.css" >
<link rel="stylesheet" href="../live/js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="../live/js/jquery-1.7.1.min.js"></script>
 <title>Invoice </title>
 </head> <body>';
// include '../live/cojmmenu.php';


if ($pagetext)
{
echo $pagetext;
}


echo'<div class="Post"> <div class="ui-widget">
<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
';

if ($globalprefrow['adminlogoback']>'0') { echo $infotext; }

echo '
<h2>'. $pdfheaderstring.'</h2>'.$html.'
</div></div><br /></div>';

include '../live/footer.php';

echo '</body>
</html>';

}



 
 mysql_close();
// original $pdf->Output("$invoiceref" . '.pdf', 'I');
// $pdf->Output($invoiceref.'.pdf','F');
