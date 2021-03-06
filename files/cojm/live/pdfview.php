<?php 
/*
    COJM Courier Online Operations Management
	pdfview.php.php - Create a PDF invoice
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


error_reporting( E_ERROR | E_WARNING | E_PARSE );

$alpha_time = microtime(TRUE);

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include 'changejob.php';

$title='COJM ';

?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Create Invoice</title></head>
<body>
<?php 
$adminmenu ="0";
$invoicemenu = "1";
$filename='pdfview.php';

$tempthree='0';
$trow='';
$tarow='';

$clientids = array('');
$depselectjs='';
$tablecost='0';

$todayDate = date("Y-m-d");// current date

//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");
$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);
// echo "After adding one month: ".date('l dS \o\f F Y', $dateOneMonthAdded)."<br>After taking away a month : ". $dateamonthago."<br><br>";

$sql = "SELECT * FROM Clients ORDER BY lastinvoicedate";
$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();
    
foreach ($stmt as $row) {
    $sql = "SELECT collectiondate FROM Orders WHERE CustomerID=? ORDER BY collectiondate DESC LIMIT 0 , 1";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$row['CustomerID']]);
    $stmt = $prep->fetchAll();
    foreach ($stmt as $lastrow) {

        $temptwo='0'; 

        $tempdate=$lastrow['collectiondate']; 
        
        
        $sql = "SELECT * FROM Orders WHERE CustomerID = ?
        AND status > '98' 
        AND status < '108' 
        AND collectiondate <= ?
        And FreightCharge <> '0.00' ";
        
        $prep = $dbh->prepare($sql);
        $prep->execute([$row['CustomerID'],$lastrow['collectiondate']]);
        $stmt = $prep->fetchAll();
        foreach ($stmt as $costrow) {
            $temptwo=$temptwo+$costrow['FreightCharge'];
        } 
        
        $tempthree=$tempthree+$temptwo;
        
        
        if (($temptwo<>'0.00') and ($temptwo)) {
            array_push($clientids, $row['CustomerID']);
            
            $tarow= ' ';
            
            $tarow.= '<tr><td><a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'. $row['CompanyName'].'</a></td><td>';
            

            $sql = "SELECT * FROM Orders WHERE CustomerID=?
            AND status < '108' 
            AND status > '98' 
            ORDER BY collectiondate ASC 
            LIMIT 0 , 1";
            
            $prep = $dbh->prepare($sql);
            $prep->execute([$row['CustomerID']]);
            $stmt = $prep->fetchAll();
            foreach ($stmt as $firstrow) {
                $tarow.= date('D j M Y', strtotime($firstrow['collectiondate']));
            }
            $tarow.='</td><td>'. date('D j M Y', strtotime($lastrow['collectiondate']));
            
            $temptwo= number_format($temptwo, 2, '.', '');
            
            $tarow.='</td><td class="rh">  &'. $globalprefrow['currencysymbol']; 
            $tarow.= $temptwo; 
            $tarow.= '</td></tr>';
            

            $trow.=$tarow;
            

        } // ends loop for uninvoiced jobs > 0.00
    } // end check for last collection date order loop
} // ends loop clients last databased invoice date





include "cojmmenu.php"; 
echo '<div class="Post clearfix">

<div class="hangleft ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">


<p>

<form id="f1" name="f1" action="invoice.php" method="post" target="_blank"> 
<fieldset><label for="pageclientid" class="fieldLabel"> Client : </label>
<select  class="ui-state-default ui-corner-left" id="pageclientid" name="clientid">';




$sql = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient=1 ORDER BY CompanyName";


$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();
    
foreach ($stmt as $crow) {
    if (in_array($crow['CustomerID'], $clientids)) {
        $CompanyName = htmlspecialchars ($crow['CompanyName']);
        print"<option ";
        echo ' value="'.$crow['CustomerID'].'">'.$CompanyName.'</option>';
    }
}

echo ' </select>';

echo '
<a id="clientlink" class="showclient" title="Client Details" target="_blank" href="new_cojm_client.php"> </a>
</fieldset>
<fieldset><label for="invoiceselectdep" class="fieldLabel"> Department : </label>';




/////////////
// echo 'orderdep is '.$row['orderdep']; 

$newjobclientid=$row['CustomerID'];

    echo '<select class="ui-state-default ui-corner-left" id="invoiceselectdep" name="invoiceselectdep" >
    <option value="">All Departments</option>';



$sql = "
 SELECT depnumber, depname, CompanyName, CustomerID 
 FROM clientdep, Clients
 WHERE clientdep.associatedclient = Clients.CustomerID 
 AND clientdep.isactivedep='1' 
 ORDER BY Clients.CompanyName, clientdep.depname"; 

$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();
foreach ($stmt as $drow) {
    
    $temptwod='0.00';
    
    $sql = "SELECT * FROM Orders WHERE orderdep = ?
    AND status > '98' 
    AND status < '108' ";
    
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$drow['depnumber']]);
    $stmt = $prep->fetchAll();
    foreach ($stmt as $costrowd) {
        $temptwod=floatval($temptwod+$costrowd['FreightCharge']);
    }
    
    $depcharge= number_format(floatval($temptwod), 2, '.', '');
    


    if ($depcharge<>0.00) {
    
        print'<option ';
        echo 'value="'.$drow['depnumber'].'">&'.$globalprefrow['currencysymbol'].' '.$depcharge.' '.$drow['CompanyName'].' -- '.$drow['depname'].'</option>';
        
        $depselectjs.='  
        
        if ( newdep=='.$drow['depnumber'].') { $("#pageclientid").val("'.$drow['CustomerID'].'"); } 
        ';
        
    
    } // ends check for charge>0

} // ends list of departments 

echo '</select> ';


//////////////////////////////////////////

?>

<a id="clientdeplink" class="showclient" title="Department Details" target="_blank" href="new_cojm_department.php"> </a>


</fieldset>


<fieldset title="Leave Blank to include All Jobs" ><label for="fromdate" class="fieldLabel"> Date to Invoice From</label> 
<input class="ui-state-default ui-corner-all caps" type="text" id="fromdate" size="12" name="fromdate"></fieldset>

<fieldset title="Including jobs on selected date"><label for="invoicetodate" class="fieldLabel"> Date to Invoice up-to </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="<?php echo date('d-m-Y', strtotime('now')); ?>"
id="invoicetodate" size="12" name="invoicetodate"></fieldset>

<fieldset><label for="existinginvoiceref" class="fieldLabel"> Existing invoice ref ? </label>
<input placeholder="Existing Invoice Ref" type="text" class="ui-state-default ui-corner-all caps" name="existinginvoiceref" id="existinginvoiceref"
 size="22" maxlength="20" >
</fieldset>

<fieldset><label for="exacttime" class="fieldLabel"> &nbsp; </label> 

<select class="ui-state-default ui-corner-left" name="exacttime" id="exacttime" >
<option selected value="1" >Exact Time</option>
<option value="0" >Just the day</option>
</select> </fieldset>

<fieldset><label for="hourly" class="fieldLabel"> &nbsp; </label>

<select class="ui-state-default ui-corner-left" name="hourly" id="hourly">
<option selected title="Collection/Delivery" value="0" >Delivery Job</option>
<option value="1" title="From/Until">Hourly Job</option>
</select></fieldset>

<fieldset><label for="showdelivery" class="fieldLabel"> &nbsp; </label>
<select class="ui-state-default ui-corner-left" name="showdelivery" id="showdelivery">
<option selected value="1" >Show Delivery Date</option>
<option  value="0" >Just Collection Date</option>
</select>
</fieldset>


<fieldset><label for="showdeliveryaddress" class="fieldLabel"> &nbsp; </label>
<select class="ui-state-default ui-corner-left" name="showdeliveryaddress" id="showdeliveryaddress">
<option selected value="1" >Show Delivery Address</option>
<option  value="0" >Hide Delivery Address</option>
</select>
</fieldset>
 
 
<fieldset><label for="addresstype" class="fieldLabel"> &nbsp; </label>
<select class="ui-state-default ui-corner-left" name="addresstype" id="addresstype" >
<option selected value="full" >Full Address</option>
<option  value="postcode" >Just Postcode</option>
<option  value="none" >No Addresses</option>
</select>
</fieldset>




<fieldset><label for="newinvdate" class="fieldLabel"> Invoice Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="<?php echo date('d-m-Y', strtotime('now')); ?>"
id="newinvdate" size="12" name="newinvdate"></fieldset>









<br>Invoice Comments : '<?php echo $globalprefrow['invoicefooter2']; ?>
<br>
<TEXTAREA class="ui-state-default ui-corner-left normal " style="padding-left:3px;" name="invcomments" rows="2" cols="50"></TEXTAREA> 
<br>


<hr />

<input id="page" name="page" type="hidden">

</form>


<button id="datesearchpreview">View in Date Search</button>

<button id="invoicepreview">Preview Invoice</button>

<button id="createpdf">Create PDF</button>

<button id="markinvoicesent" style="color: red;" >Mark as Sent</button>

<hr />

</div>



<div class="hangleft ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<table class="acc" ><tbody>
<tr>
<th scope="col">Client</th>
<th scope="col">Invoice From</th>
<th scope="col">Last Collection</th>
<th scope="col" class="rh" >Cost ex vat</th>
</tr>


<?php

echo $trow. '
<tr><td> </td><td> </td><td> <strong>Total : </strong></td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].'<strong>'.$tempthree.'</strong></td></tr>
</tbody></table>
<hr />
</div><br />';
?>
<script >

$(function () { // set column size
var max = 0;
$("label").each(function(){
    if ($(this).width() > max) {
        max = $(this).width();  
    }
});
$("label").width((max+15));
});

$(document).ready(function () {
	
    
    
    $(function () { // fromdate
		var newinvdatepicker = $( "#newinvdate" ).datepicker({
        });
	});    
    
    $(function () { // fromdate
		var fromdatepicker = $( "#fromdate" ).datepicker({
        });
	});
    
    
   	$(function () { // todate ( invoicetodate )
		var dates = $( "#invoicetodate" ).datepicker({
		});
	});
	
	
    $(function (){ // autosize
        $(".normal").autosize();
    });
	
	
	
	$("#clientdeplink").click(function (e) {
        e.preventDefault();
        var pagedepid=$("#invoiceselectdep").val();
        var datelink = "new_cojm_department.php?depid=" + pagedepid + "#tabs-" + pagedepid;
        if (pagedepid !== "") {  window.open(datelink,"_blank"); }
    });

    $("#clientlink").click(function (e) {
        e.preventDefault();  
        var pageclientid=$("#pageclientid").val();
        var datelink = "new_cojm_client.php?clientid=" + pageclientid;
        window.open(datelink,"_blank");
    });

    $("#datesearchpreview").click(function (e) {
        e.preventDefault(); 
        var pageclientid=$("#pageclientid").val();
        var pagedepid=$("#invoiceselectdep").val();
        var todate=$("#invoicetodate").val();
        var fromdate=$("#fromdate").val();
        var datelink = "clientviewtargetcollection.php?clientid=" + pageclientid;
        datelink = datelink + "&viewselectdep=" + pagedepid;
        datelink = datelink + "&from=" + fromdate + "&to=" + todate;
        datelink = datelink + "&newcyclistid=all&servicetype=all&deltype=all&orderby=targetcollection";
        datelink = datelink + "&clientview=normal&viewcomments=normal&statustype=notinvoicedcomp";
        window.open(datelink,"_blank");
    });


    $("#invoicepreview").click(function () {
    $("#page").val("preview");
    $("#f1").attr('target', '_blank');
    $("#f1").submit();       
    });


    $("#createpdf").click(function () {
    $("#page").val("createpdf");
    $("#f1").attr('target', '');
    $("#f1").submit();       
    });


    $("#markinvoicesent").click(function () {
    $("#page").val("addtodb");
    $("#f1").attr('target', '_blank');
    $("#f1").submit();       
    });


    $("#invoiceselectdep").change(function () {
    var newdep=$("#invoiceselectdep").val();
    <? echo $depselectjs; ?>
    });

function datepickeronchange() { }


}); // ends on pageload


</script>
<?php
echo '</div> ';

include "footer.php";

echo '</body>';