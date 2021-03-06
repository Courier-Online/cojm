<?php 
/*
    COJM Courier Online Operations Management
	new_cojm_client.php - Edit Client / Add New Client
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
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>'0') {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
$hasforms='1';

?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<title>Client Details</title>
</head><body>';

include "changejob.php";

if (isset($clientid)) {} else { if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } else { $clientid=''; } }

if (!$clientid) { if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); }}


$adminmenu='1';
$filename="new_cojm_client.php";

include "cojmmenu.php"; 

echo '<div class="Post Spaceout">

<div class="ui-state-highlight ui-corner-all p15">

<form action="new_cojm_client.php" method="get">';

echo '
<select class="ui-state-default ui-corner-all pad"  id="combobox" name="clientid" ><option value="">Select one...</option>';


$sql = "SELECT CustomerID, CompanyName FROM Clients ORDER BY CompanyName";

$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();

foreach ($stmt as $yrow) {
    $CompanyName = htmlspecialchars ($yrow['CompanyName']);
    print"<option "; 
    if ($yrow['CustomerID'] == $clientid) { echo " SELECTED "; }
    echo ' value="'.$yrow['CustomerID'].'">'.$CompanyName.'</option>';
}
echo '</select> ';


echo ' <button type="submit"> Select Client </button> 
</form> ';


$sql = "SELECT * FROM Clients WHERE CustomerID = ? LIMIT 0,1";
$statement = $dbh->prepare($sql);
$statement->execute([$clientid]);
$row = $statement->fetch(PDO::FETCH_ASSOC);

if ($row['isdepartments']=='1') {
    echo ' <br/> 
    <form action="new_cojm_department.php" method="post">
    <input type="hidden" name="page" value="selectclientdepartment" >
    <input type="hidden" name="formbirthday" value="'.date("U").'">
    <input type="hidden" name="clientid" value="'.$row['CustomerID'].'">
    <button type="submit"> Switch to '.$row['CompanyName'].' departments</button>
    </form>';
}

if ((!$clientid) or (!$row)) {
    
    echo ' 
    <br />
    
    <form action="#" method="post">
    <input type="hidden" name="page" value="createnewcl" />
    <button type="submit"> Create new Client </button>
    <input type="hidden" name="formbirthday" value="'. date("U").'">
    Name : <input class="ui-state-default ui-corner-all pad" type="text" name="CompanyName" placeholder="New Client Name" size="15" />
    </form>';
}

echo '
</div>
<div class="vpad"> </div>';

    
if ($row)  {
    
    
    
    echo '
    <form action="#" method="post">
    <input type="hidden" name="formbirthday" value="'. date("U").'">
    <input type="hidden" name="page" value="editclient" >
    
    <input type="hidden" name="clientid" value="'.$row['CustomerID'].'">
    
    <div id="tabs">
    <ul>
    <li><a href="#tabs-7">'.$row['CompanyName'].'</a></li>
    <li><a href="#tabs-1">Details</a></li>
    <li><a href="#tabs-2">Contact</a></li>
    <li><a href="#tabs-3">Invoicing</a></li>
    <li><a href="#tabs-5">Favourites</a></li>
    <li><a href="#tabs-4">COJM Options</a></li>
    <li><a href="#tabs-6">CO2 Stats</a></li>
    </ul>
    
    
    
    <div id="tabs-1">
    
    
    <fieldset><label class="fieldLabel"> Client Name </label>
    <input type="text" class="ui-state-default ui-corner-all pad" name="CompanyName" value="'.$row['CompanyName'].'"></fieldset>
    
    
    <fieldset><label  class="fieldLabel">  Is Active Client </label>
    <input type="checkbox" name="isactiveclient" value="1" ';
    if ($row['isactiveclient']>0) { echo 'checked'; } 
    
    echo ' > </fieldset>
    
    <fieldset><label class="fieldLabel"> Uses Departments </label>
    <input type="checkbox" name="isdepartments" value="1" ';
    
    if ($row['isdepartments']>0) { echo 'checked'; } 
    
    echo ' >  </fieldset>
    
    <fieldset><label  class="fieldLabel"> Phone Number </label> 
    <input type="text" class="ui-state-default ui-corner-all pad" name="PhoneNumber" value="'.$row['PhoneNumber'].'"> </fieldset>
    
    <fieldset><label class="fieldLabel"> Client Email </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="emailaddress"
    value="'.$row['EmailAddress'].'"> </fieldset>
    
    <fieldset><label class="fieldLabel" >Notes   </label><textarea id="Notes" class="normal ui-state-default ui-corner-all pad" 
    name="Notes" style="width: 65%; outline: none; height:20px;">'.trim($row['Notes']).'</textarea> </fieldset>
    (shown in other staff COJM logins)
    
    <br /><div class="line"></div><br />
    <button type="submit" formaction="#tabs-1" > Edit Client Details </button>
    <br /><div class="line"></div><br />
    
    </div>
    <div id="tabs-2">
    
    <fieldset><label >  Title </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="6" name="Title" value="'. $row['Title'].'"> </fieldset>
    <fieldset><label >  Forename </label>
    <input type="text" class="ui-state-default ui-corner-all pad" name="Forname" value="'. $row['Forename'] . '"> </fieldset>
    <fieldset><label > Client Surname </label>
    <input type="text" class="ui-state-default ui-corner-all pad" name="Surname" value="'.$row['Surname'].'"> </fieldset>
    <fieldset><label > Client Mobile </label>
    <input type="text" class="ui-state-default ui-corner-all pad"  size="15" name="MobileNumber" value="'.$row['MobileNumber'].'"> </fieldset>
    
    <div class="line"> </div>
    
    <fieldset><label > St Number </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="Address" value="' . $row['Address'] . '"> </fieldset>
    <fieldset><label > St Name </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="Address2" value="'.$row['Address2'].'"> </fieldset>
    <fieldset><label  class="fieldLabel">  City </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="City" value="' . $row['City'] . '"> </fieldset>
    <fieldset><label > County  </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="County" value="' . $row['County'] . '"> </fieldset>
    <fieldset><label  > Country  </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="CountryOrRegion" value="' . $row['CountryOrRegion'] . '"> </fieldset>
    <fieldset><label >  Postcode </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="12" name="Postcode" value="' . $row['Postcode'] . '"> 
    </fieldset>';
    
    
    
    if (trim($row['Address']))  { echo $row['Address'] .', '; }
    if (trim($row['Address2'])) { echo $row['Address2'] .', '; }
    if (trim($row['City']))     { echo $row['City'] .', '; }
    if (trim($row['County']))   { echo $row['County'].', '; }
    
    if (trim($row['Postcode'])) { echo ' <a target="_blank" href="http://maps.google.co.uk/maps?q='. $row['Postcode']. '">'. $row['Postcode'].'</a>'; }
    
    
    
    
    
    
    
    echo '
    <br /><div class="line"></div><br />
    <button type="submit" formaction="#tabs-2" > Edit Client Details </button>
    <br /><div class="line"></div><br />
    
    
    </div>
    
    
    
    
    
    
    
    <div id="tabs-3">
    
    
    <fieldset><label > 
    <strong>Invoicing</strong> Terms : </label>
    <select name="invoicetype" class="ui-state-default ui-corner-left" >
    <option ';
    if ($row['invoicetype']=='0') { echo 'selected '; } 
    echo 'value="0" >Account - Payment after Invoice (monthly invoice)</option>
    <option ';
    if ($row['invoicetype']=='1') { echo 'selected '; } 
    echo ' value="1" >Website Booked Pre-pay</option>
    <option ';
    if ($row['invoicetype']=='2') { echo 'selected '; } 
    echo 'value="2" >Immediate - Payment on ordering (Visa over phone)</option>
    <option ';
    
    if ($row['invoicetype']=='3') { echo 'selected '; } 
    echo 'value="3" >Payment on Collection (via Courier)</option>
    <option ';
    if ($row['invoicetype']=='4') { echo 'selected '; } 
    echo 'value="4" >Payment on Delivery (via Courier)</option>
    <option ';
    if ($row['invoicetype']=='5') { echo 'selected '; } 
    echo 'value="5" >Client in Credit Prepay</option>
    </select>
    If account, due 
    <input type="text" size="2" class="ui-state-default ui-corner-all pad" name="invoiceterms" value="' . $row['invoiceterms'] . '"> days from sending.
    </fieldset>
    
    
    
    <fieldset><label >  Email </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="60" name="invoiceemailaddress" value="' . $row['invoiceEmailAddress']  . '">
    </fieldset>
    
    
    <fieldset><label > St Number </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="invoiceAddress" value="' . $row['invoiceAddress'] . '"> 
    </fieldset>
    
    <fieldset><label >   St Name  </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="invoiceAddress2" value="' . $row['invoiceAddress2'] . '"> 
    </fieldset>
    
    
    <fieldset><label >  City  </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCity" value="' . $row['invoiceCity'] . '">
    </fieldset>
    
    
    <fieldset><label >  County   </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCounty" value="' . $row['invoiceCounty'] . '">
    </fieldset>
    
    
    <fieldset><label > Country  </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCountryOrRegion" value="' . $row['invoiceCountryOrRegion'] . '">
    </fieldset>
    
    <fieldset><label  >  Postcode </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="12" name="invoicePostcode" value="' . $row['invoicePostcode'] . '">
    <a target="_blank" href="http://maps.google.co.uk/maps?q=' . $row['invoicePostcode'] . '">' . $row['invoicePostcode'] . '</a> 
    </fieldset>
    
    
    <fieldset><label >  VAT Reg </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="clientvatno" value="' . $row['clientvatno'] . '">
    </fieldset>
    
    <fieldset><label>  Comp House Reg </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="clientregno" value="' . $row['clientregno'] . '">
    </fieldset> 
    
    <fieldset><label  >  Percentage Discount </label>
    <input type="text" class="ui-state-default ui-corner-all pad" size="6" name="cbbdiscount" value="' . $row['cbbdiscount'] . '"> %.
    
    discount on each job for being good client 
    
    </fieldset>
    
    
    
    <br /><div class="line"></div><br />
    <button type="submit" formaction="#tabs-3" > Edit Client Details </button>
    <br /><div class="line"></div><br />
    
    </div>
    
    
    
    <div id="tabs-4">
    
    
    <fieldset><label > Joomla User </label>
    <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser" value="' . $row['JoomlaUser']. '"> 
    Test Login ' . $globalprefrow['testjoomlalogin']. '
    </fieldset>
    
    <fieldset><label > Joomla User2 </label>
    <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser2" value="' . $row['JoomlaUser2'] . '"> </fieldset>
    
    <fieldset><label > Joomla User3 </label>
    <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser3" value="' . $row['JoomlaUser3'] . '"> </fieldset>
    
    <fieldset><label > CO2 API Ref </label>
    <input class="ui-state-default ui-corner-all pad" type="text" size="11" name="co2apiref" value="' . $row['co2apiref'] . '"> (12) chars 
    </fieldset>
    
    <fieldset><label > COJM Client ID </label>'. $clientid . ' 
    </fieldset>
    <div class="vpad"> </div>
    
    
    <input type="hidden" name="htmlemail" value="0" /> 
    
    <fieldset><label>   </label>';
    
    
    // echo '<input type="checkbox" name="cemail1" value="1" '; 
    // if ($row['cemail1']>0) { echo 'checked';} 
    // echo '> ';
    
    echo 'Send auto email job complete - NOT YET Working Will  Send email to client / department when job goes from needing admin to complete.</fieldset>';
    
    echo ' 
    <input type="hidden" name="cemail2" value="0" />
    <input type="hidden" name="cemail3" value="0" /> 
    <input type="hidden" name="cemail4" value="0" />
    <input type="hidden" name="cemail5" value="0" />
    ';
    
    echo '
    <br /><div class="line"></div><br />
    <button type="submit" formaction="#tabs-4" > Edit Client Details </button>
    <br /><div class="line"></div><br />
    </div>';
    
    
    
    
    
    // sets defaults
    
    echo '<div id="tabs-5">';
    
    if ($row['isdepartments']=='1') {
    
    echo '<strong>
    
    These defaults will be only be used if there are no department defaults set.</strong><div class="vpad"> </div>';
    }
    
    echo '
    <fieldset><label > Default Requestor </label>
    <input type="text" class="caps ui-state-default ui-corner-all" size="20" name="defaultrequestor" value="'. $row['defaultrequestor'].'"></fieldset>
    <fieldset><label > Default Service </label>';
    
    
    print ("<select class=\"ui-state-default ui-corner-left\" name=\"defaultservice\">"); 
    
    
    ////////////   SERVICE           ////////////////
    $sql = "
    SELECT ServiceID, 
    Service 
    FROM Services 
    WHERE activeservice='1' 
    ORDER BY serviceorder DESC, ServiceID ASC";
    
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    
    
    // if (!$row['depservice']) {  }
    
    echo ' <option value=""> No Default </option> ';
    
    foreach ($stmt as $srow) {
        $Service = htmlspecialchars($srow['Service']); 
        print ("<option "); 
        {	if ($row['defaultservice'] == $srow['ServiceID']) echo " SELECTED "; }	
        
        echo ' value="'.$srow['ServiceID'].'">'.htmlspecialchars($srow['Service']).'</option>'; 
    } 
    
    print ("</select></fieldset> "); 
    /////////     ENDS    SERVICE ///////////////////////////////////////////////
    
    
    
    echo '<fieldset><label > Default PU </label>';
    
    $sql = "SELECT *
    FROM cojm_favadr 
    WHERE favadrisactive='1'
    AND favadrclient = ? "; 
    
    
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);
    $stmt = $prep->fetchAll();
    
    
    
    
    print ("<select class=\"ui-state-default ui-corner-left\" name=\"defaultfrom\">"); 
    echo '<option value="">No Default</option>';
    
    foreach ($stmt as $f) {        
        $favadrft = htmlspecialchars ($f['favadrft']);	
        $favadrpc = htmlspecialchars ($f['favadrpc']); 
        print ("<option "); 
        if ($row['defaultfromtext'] == $f['favadrid']) { echo " SELECTED "; }	
        echo '"value="' . $favadrid . '">' . $favadrft .' '.$favadrpc.' </option> ';
    }
    
    
    print ("</select>"); 
    /////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
    echo '</fieldset>';
    
    
    
    
    echo '<fieldset><label > Default Drop </label>';
    

    
    print ("<select class=\"ui-state-default  pad ui-corner-left\" name=\"defaultto\">"); 
    echo '<option value="">No Default</option>';
    
    foreach ($stmt as $f) {        
        $favadrft = htmlspecialchars ($f['favadrft']);	
        $favadrpc = htmlspecialchars ($f['favadrpc']); 
        print ("<option "); 
        if ($row['defaulttotext'] == $f['favadrid']) { echo " SELECTED "; }	
        echo '"value="' . $favadrid . '">' . $favadrft .' '.$favadrpc.' </option> ';
    }
    

    print ("</select>"); 
    /////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
    echo '</fieldset>';
    
    

    if ($stmt)  {
        
        echo '
        <div class="vpad"> </div>
        <table class="acc"><tbody>
        
        <tr>
        <td> Click Address to edit </td>
        <td>Postcode</td>
        <td>Comments</td>
        <td>Last Visited</td>
        <td>Tags</td>
        </tr>';
        
            
        foreach ($stmt as $f) {
            $PC=$f['favadrpc'];
            
            $PC= str_replace(" ", "%20", "$PC", $count);
            
            echo ' <tr><td>
            <a title="Edit Favourite" href="favusr.php?page=selectfavadr&amp;clientid='.$clientid.'&amp;thisfavadrid='.$f['favadrid'].'" >'.$f['favadrft'].'</a>
            </td><td>
            <a target="_blank" href="http://maps.google.co.uk/maps?q='. $PC. '">'. $f['favadrpc'].'</a>
            </td><td>
            '. $f['favadrcomments'].'
            </td><td>';
            
            if ($f['favadrlastvisit']>0) { echo date(' H:i D j M Y', strtotime($f['favadrlastvisit'])); }
            
            echo '
            </td>
            <td>
            ';
            
            if ($f['favusr1']=='1') { echo $globalprefrow['favusrn1'].' '; }
            if ($f['favusr2']=='1') { echo $globalprefrow['favusrn2'].' '; }
            if ($f['favusr3']=='1') { echo $globalprefrow['favusrn3'].' '; }
            if ($f['favusr4']=='1') { echo $globalprefrow['favusrn4'].' '; }
            if ($f['favusr5']=='1') { echo $globalprefrow['favusrn5'].' '; }
            if ($f['favusr6']=='1') { echo $globalprefrow['favusrn6'].' '; }
            if ($f['favusr7']=='1') { echo $globalprefrow['favusrn7'].' '; }
            if ($f['favusr8']=='1') { echo $globalprefrow['favusrn8'].' '; }
            if ($f['favusr9']=='1') { echo $globalprefrow['favusrn9'].' '; }
            if ($f['favusr10']=='1') { echo $globalprefrow['favusrn10'].' '; }
            if ($f['favusr11']=='1') { echo $globalprefrow['favusrn11'].' '; }
            if ($f['favusr12']=='1') { echo $globalprefrow['favusrn12'].' '; }
            if ($f['favusr13']=='1') { echo $globalprefrow['favusrn13'].' '; }
            if ($f['favusr14']=='1') { echo $globalprefrow['favusrn14'].' '; }
            if ($f['favusr15']=='1') { echo $globalprefrow['favusrn15'].' '; }
            if ($f['favusr16']=='1') { echo $globalprefrow['favusrn16'].' '; }
            if ($f['favusr17']=='1') { echo $globalprefrow['favusrn17'].' '; }
            if ($f['favusr18']=='1') { echo $globalprefrow['favusrn18'].' '; }
            if ($f['favusr19']=='1') { echo $globalprefrow['favusrn19'].' '; }
            if ($f['favusr20']=='1') { echo $globalprefrow['favusrn20'].' '; }
            
            
            echo ' </td></tr>';
            
            
        }
            
            
        echo '</table>';
        
        

        
        
    } else { // ends check for favourites
        
        echo ' No favourite adresses for this contact.';
    }
    
    echo '
    <br /><div class="line"></div>
    <button type="submit" formaction="#tabs-5" > Edit Client Details </button>
    <br /><div class="line"></div>
    </div>

    
    <div id="tabs-6">
    ';
    
    if ($row['lastinvoicedate']>'1') {
        echo '<fieldset><label > Invoiced until </label> '.date(' D j M Y', strtotime($row['lastinvoicedate'])) . '</fieldset>';
    }  
    
    
    
    
    $tableco2='';
    $yeartableco2=''; 
    $lasttableco2='';
    $tablepm10='';
    $yeartablepm10='';
    $lasttablepm10='';
    $thisyear = date("Y");
    $lastyear = $thisyear-'1';
    
    
    $sql="SELECT ShipDate, co2saving, CO2Saved, numberitems, pm10saving, PM10Saved FROM Orders, Services 
    WHERE Orders.ServiceID = Services.ServiceID 
    AND Orders.CustomerID = ?
    AND Orders.status >= 77 ";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);
    $stmt = $prep->fetchAll();
    
    foreach ($stmt as $corow) {
            
        $newSqlString = date('Y', strtotime($corow['ShipDate']));
            
        if ($corow['co2saving']) { // from job
            $tableco2=$tableco2+$corow["co2saving"];
            if ($newSqlString==$thisyear) { $yeartableco2=$yeartableco2+$corow["co2saving"]; }
            if ($newSqlString==$lastyear) { $lasttableco2=$lasttableco2+$corow["co2saving"]; }
        } else if ($corow['CO2Saved'])  { // from service
            $tableco2=$tableco2+($corow['CO2Saved']*$corow["numberitems"]);
            if ($newSqlString==$thisyear) { $yeartableco2=$yeartableco2+($corow['CO2Saved']*$corow["numberitems"]); }
            if ($newSqlString==$lastyear) { $lasttableco2=$lasttableco2+($corow['CO2Saved']*$corow["numberitems"]); }
        }
        
        if ($corow['pm10saving']>'0.01')  { 
        $tablepm10=$tablepm10+($corow['pm10saving']);
        if ($newSqlString==$thisyear) { $yeartablepm10=$yeartablepm10+$corow["pm10saving"]; }
        if ($newSqlString==$lastyear) { $lasttablepm10=$lasttablepm10+$corow["pm10saving"]; }
        
        } else if ($corow['PM10Saved']<>'0.0') { 
        $tablepm10=$tablepm10+($corow['PM10Saved']*$corow["numberitems"]);
        if ($newSqlString==$thisyear) { $yeartablepm10=$yeartablepm10+($corow['PM10Saved']*$corow["numberitems"]); }
        if ($newSqlString==$lastyear) { $lasttablepm10=$lasttablepm10+($corow['PM10Saved']*$corow["numberitems"]); }
        } 
    } // ends row loop
    
    
    
    
    
    // totals under here
    if ($tablepm10>'1000') {
        $tablepm10=($tablepm10/'1000');
        $tablepm10 = number_format($tablepm10, 1, '.', ',');
        $tablepm10= $tablepm10.' Kg '; 
    } else {
        if ($tablepm10>'1') {
            $tablepm10=$tablepm10.' grams'; 
        }
    }
    
    
    
    if ($yeartablepm10>'1000') {
        $yeartablepm10=($yeartablepm10/'1000');
        $yeartablepm10 = number_format($yeartablepm10, 1, '.', ',');
        $yeartablepm10= $yeartablepm10.' Kg '; 
    } else {
        if ($yeartablepm10>'1') {
            $yeartablepm10=$yeartablepm10.' grams'; 
        }
    }
    
    
    
    if ($lasttablepm10>'1000') {
        $lasttablepm10=($lasttablepm10/'1000');
        $lasttablepm10 = number_format($lasttablepm10, 1, '.', ',');
        $lasttablepm10= $lasttablepm10.' Kg '; 
    } else {
        if ($lasttablepm10>'1') {
            $lasttablepm10=$lasttablepm10.' grams'; 
        }
    }
    
    
    
    if ($tableco2>'1000') {
        $tableco2=($tableco2/'1000');
        $tableco2 = number_format($tableco2, 1, '.', ',');
        $tableco2= $tableco2.' Kg ';
    }
    else {
        if ($tableco2>'1') {
            $tableco2=$tableco2.' grams'; 
        }
    }
    
    if ($yeartableco2>'1000') {
        $yeartableco2=($yeartableco2/'1000');
        $yeartableco2 = number_format($yeartableco2, 1, '.', ',');
        $yeartableco2= $yeartableco2.' Kg '; 
    } else {
    if ($yeartableco2>'1') { $yeartableco2=$yeartableco2.' grams'; 
    }
    }
    
    
    if ($lasttableco2>'1000') {
    $lasttableco2=($lasttableco2/'1000');
    $lasttableco2 = number_format($lasttableco2, 1, '.', ',');
    $lasttableco2= $lasttableco2.' Kg '; 
    } else {
    if ($lasttableco2>'1') { $lasttableco2=$lasttableco2.' grams'; 
    }
    }
    
    
    
    
    
    
    
    if ($yeartableco2=='') { $yeartableco2='0 grams'; }
    if ($tablepm10=='') { $tablepm10='0 grams'; }
    if ($tableco2=='') { $tableco2='0 grams'; }
    
    
    //	echo $globalprefrow['adminlogo'];
    //  echo "/images/".basename($globalprefrow['adminlogo']);
    
    
        
    echo '
    
    <div class="vpad"> </div>
    
    <fieldset><label > Total CO<sub>2</sub> saved </label>'.$tableco2.'</fieldset><div class="vpad"> </div>
    <fieldset><label > CO<sub>2</sub> in '.$lastyear.' </label>'.$lasttableco2.'</fieldset><div class="vpad"> </div>
    <fieldset><label > CO<sub>2</sub> in '.$thisyear.' </label>'.$yeartableco2.'</fieldset><div class="vpad"> </div>
    <fieldset><label > Total PM<sub>10</sub> saved </label>'.$tablepm10.'</fieldset><div class="vpad"> </div>
    <fieldset><label > PM<sub>10</sub> in '.$lastyear.' </label>'.$lasttablepm10.'</fieldset><div class="vpad"> </div>
    <fieldset><label > PM<sub>10</sub> in '.$thisyear.' </label>'.$yeartablepm10.'</fieldset><br /><div class="line"></div><br />
    <button type="submit" formaction="#tabs-6" > Edit Client Details </button>
    <br /><div class="line"></div><br />
    
    </div> 
    
    <div id="tabs-7"> ';
    
    $less65vol='0';
    $less65net='0';
    $less65cnt='0';
    
    $c65vol='0';
    $c65net='0';
    $c65cnt='0';
    
    $c108vol='0';
    $c108net='0';
    $c108cnt='0';
    
    
    $totvol='0';
    $totnet='0';
    $totcnt='0';
    
    $thisyrvol='0';
    $thisyrnet='0';
    $thisyrcnt='0';
    
    
    $lastyrvol='0';
    $lastyrnet='0';
    $lastyrcnt='0';
    
    
    $order90vol='0';
    $order90net='0';
    $order90cnt='0';	
    
    
    $order180vol='0';
    $order180net='0';
    $order180cnt='0';	 
    
    $thisyear=date("Y");
    $lastyear=date("Y")-'1';
    
    $fromarray=array();
    $toarray=array();
    
    
    $format = 'Y-m-d 00:00:00';   
    
    $date = date ( $format );   
    
    
    $date90ago=  date ( $format, strtotime ( '-90 day' . $date ) );
    $date180ago=  date ( $format, strtotime ( '-180 day' . $date ) ); 
    
    
    $date90agou=  date ("U", strtotime ($date90ago));  
    $date180agou=  date ("U", strtotime ($date180ago));    
    // echo ' 900 days ago? '. $date180ago;   
    // echo ' 180 days ago? '. $date180ago;  
    
    
    $sql = "SELECT numberitems, FreightCharge, vatcharge, status , ShipDate, enrft0, enrpc0, enrft21, enrpc21 
    FROM Orders WHERE Orders.CustomerID = ? ";
    
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);
    $stmt = $prep->fetchAll();
    
    if ($stmt) {
        foreach ($stmt as $orow) {
            $checkdate= date ("U", strtotime ($orow['ShipDate']));  
            
            if ($orow['status']>77) {
                $tempaddress=$orow['enrft0'].' '.$orow['enrpc0'];
                if (trim($tempaddress)<>'') {
                    array_push($fromarray,$tempaddress);
                }
                    
                $tempaddress=$orow['enrft21'].' '.$orow['enrpc21'];
                if (trim($tempaddress)<>'') {
                    array_push($toarray,$tempaddress);
                }
            }
            
            if ($checkdate>=$date180agou) {
                $order180vol=$order180vol+$orow['numberitems'];
                $order180net=$order180net+$orow['FreightCharge']+$orow['vatcharge'];
                $order180cnt++;
                
                if ($checkdate>=$date90agou) {
                    $order90vol=$order90vol+$orow['numberitems'];
                    $order90net=$order90net+$orow['FreightCharge']+$orow['vatcharge'];
                    $order90cnt++;
                }
            }
    
            if ($orow['status']<59) {
                $less65vol=$less65vol+$orow['numberitems'];
                $less65net=$less65net+$orow['FreightCharge']+$orow['vatcharge'];
                $less65cnt++;
            }
            
            if (($orow['status']==60) or ($orow['status']==65)) {
                $c65vol=$c65vol+$orow['numberitems'];
                $c65net=$c65net+$orow['FreightCharge']+$orow['vatcharge'];
                $c65cnt++;
            }
            
            
            if (($orow['status']>65) and ($orow['status']<108)) {
                $c108vol=$c108vol+$orow['numberitems'];
                $c108net=$c108net+$orow['FreightCharge']+$orow['vatcharge'];
                $c108cnt++;
            }
            
            
            if (date('Y', strtotime($orow['ShipDate']))==$thisyear) { 
                $thisyrvol=$thisyrvol+$orow['numberitems'];
                $thisyrnet=$thisyrnet+$orow['FreightCharge']+$orow['vatcharge'];
                $thisyrcnt++;
            }
            
            
            if (date('Y', strtotime($orow['ShipDate']))==$lastyear) { 
                $lastyrvol=$lastyrvol+$orow['numberitems'];
                $lastyrnet=$lastyrnet+$orow['FreightCharge']+$orow['vatcharge'];
                $lastyrcnt++;
            }  
            
            $totvol=$totvol+$orow['numberitems'];
            $totnet=$totnet+$orow['FreightCharge']+$orow['vatcharge'];
            $totcnt++;
            

        } // ends orow loop
    } else {
        echo '<h1>No Job Data Available</h1>';
    }
    
    
    
    
    
    
    
    
    echo ' <table class="clientstats acc">
    <tbody>
    <tr>
    <th> Job Status </th>
    <th> Num Jobs</th>
    <th> Num Items </th>
    <th> Net Cost </th>
    </tr>

    
    <tr>
    <td>Scheduled </td>
    <td> '.formatMoney($less65cnt).'</td>
    <td> '.formatMoney($less65vol).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($less65net, 2, '.', ',').'</td>
    </tr>	

    
    <tr>
    <td> En-Route / Paused </td>
    <td> '.$c65cnt.'</td>
    <td> '.$c65vol.'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($c65net, 2, '.', ',').'</td>
    </tr>

    <tr>
    <td> Complete / Uninvoiced </td>
    <td> '.formatMoney($c108cnt).'</td>
    <td> '.formatMoney($c108vol).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($c108net, 2, '.', ',').'</td>
    </tr>	
        
    <tr>
    <td title="Completed Jobs"> 90 Day Avg </td>
    <td> ' .formatMoney(number_format((($order90cnt / '90')), 2, '.', '')).'</td>
    <td> '.formatMoney(number_format((($order90vol / '90')), 2, '.', '')).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format((($order90net / '90')), 2, '.', ',').'</td>
    </tr>
    
    <tr>
    <td title="Completed Jobs"> 180 Day Avg </td>
    <td> '.formatMoney(number_format((($order180cnt / '180')), 2, '.', '')).'</td>
    <td> '.formatMoney(number_format((($order180vol / '180')), 2, '.', '')).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format((($order180net / '180')), 2, '.', ',').'</td>
    </tr>	

    <tr>
    <td title="Completed Jobs"> '.$thisyear.' </td>
    <td> '.formatMoney($thisyrcnt).'</td>
    <td> '.formatMoney($thisyrvol).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($thisyrnet, 2, '.', ',').'</td>
    </tr>	
    
    
    <tr>
    <td title="Completed Jobs"> '.$lastyear.' </td>
    <td> '.formatMoney($lastyrcnt).'</td>
    <td> '.formatMoney($lastyrvol).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($lastyrnet, 2, '.', ',').'</td>
    </tr>	

    <tr>
    <td title="Completed Jobs" >Total All Time</td>
    <td> '.formatMoney($totcnt).'</td>
    <td> '.formatMoney($totvol).'</td>
    <td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($totnet, 2, '.', ',').'</td>
    </tr>

    </tbody>

    
    </table> 
    
    
    
    
    
    <table class="acc clientstats" style="display:none;">
    
    <tbody>
    <tr>
    <th> Invoice Status </th>
    <th> Debtor Days</th>
    <th> Num Items </th>
    <th> Net Cost </th>
    </tr>

    
    <tr>
    <td>Outstanding </td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>	

    
    <tr>
    <td> Paid This Year </td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>	
    
    <tr>
    <td> Paid Last Year </td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>
    
    
    <tr>
    <td> 90 Day Avg </td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>

    
    <tr>
    <td> 180 Day Avg </td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>
    
    <tr>
    <td> Total</td>
    <td> </td>
    <td> </td>
    <td class="rh"> </td>
    </tr>
    
    </tbody>
    </table> 
 ';
    

    $frequencies = array_count_values($fromarray);
    arsort($frequencies); // Sort by the most frequent matches first.
    $tenFrequencies = array_slice($frequencies, 0, 10, TRUE); // Only get the top 10 most frequent
    $topTenfrom = array_keys($tenFrequencies);
    
    
    if (count($tenFrequencies)) {
        
        echo ' <table class="clientstats acc">
        <tbody>
        <tr>
        <th colspan="2">Top '.count($tenFrequencies).'   PU locations </th></tr>'; 
        
        foreach ($tenFrequencies as $key => $value) {
            echo "<tr><td> 	$value </td><td> $key </td></tr>";
        }
        echo '</tbody></table>';
    }
    
    $frequencies = array_count_values($toarray);
    arsort($frequencies); // Sort by the most frequent matches first.
    $tenFrequencies = array_slice($frequencies, 0, 10, TRUE); // Only get the top 10 most frequent
    $topTento = array_keys($tenFrequencies);
    
    
    if (count($tenFrequencies)) {
        
        echo ' <table class="clientstats acc">
        <tbody>
        <tr>
        <th colspan="2">Top '.count($tenFrequencies).'   Drop locations </th></tr>';
        
        
        foreach ($tenFrequencies as $key => $value) {
            echo "<tr><td> 	$value </td><td> $key </td></tr>";
        }
        
        echo '</tbody></table>';
    
    }
    
    
    
    
    
    $sql = "SELECT *
    FROM `Orders`
    INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID
    INNER JOIN Services ON Orders.ServiceID = Services.ServiceID
    INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
    INNER JOIN status ON Orders.status = status.status
    LEFT JOIN clientdep ON Orders.orderdep=clientdep.depnumber
    WHERE `Orders`.`status` >70
    AND Orders.CustomerID = ?
    ORDER BY `Orders`.`Shipdate` DESC
    LIMIT 0 , 10";
    
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);
    $stmt = $prep->fetchAll();
    
    
    echo ' <table style="position:relative; float:left;" class="acc" >
    <tbody>
    <tr>
    <th scope="col">Last 10 Drops</th>
    <th scope="col"> </th>
    <th scope="col"> </th>';
    
    if (($row['isdepartments']=='1'))   { 
        echo ' <th scope="col">Dep</th> ';
    }
    
    echo '
    <th scope="col">Service</th>
    <th scope="col">Status</th>
    <th scope="col">Comments</th>
    </tr> ';
    
    
    
    foreach ($stmt as $row) {
        $enrpc0=$row['enrpc0'];
        $enrpc21=$row['enrpc21'];
        $prenrpc21= str_replace(" ", "%20", "$enrpc21", $count);
        $prenrpc0= str_replace(" ", "%20", "$enrpc0", $count);
        
        echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';
        echo date('H:i A D j M ', strtotime($row['ShipDate'])); 
        echo '</td><td>';
        echo $row['cojmname'].'</td>';
        echo ' <td> <a target="_blank" href="http://maps.google.co.uk/maps?q='. $prenrpc0.'">'. $enrpc0.'</a>';
        if ((!$enrpc21) or ($enrpc21==' ')) {} else {echo " to "; }
        echo '<a target="_blank" href="http://maps.google.co.uk/maps?q='. $prenrpc21.'">'. $enrpc21.'</a></td> ';
        
        if (($row['isdepartments']=='1'))   {
            echo ' <td> ';
            if ($row['depname']) {
                echo $row['depname'];
            }
            echo '</td>';
        }
        
        
        echo '
        <td>'.formatmoney($row["numberitems"]).' x '. $row['Service'].'</td>
        <td>'. $row['statusname'] .'</td>
        <td>'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td>
        </tr>';
    }
    
    
    echo '</tbody>  </table>
    <div style="clear:both;"> </div>


    </div>
    ';
    
    
    echo ' </div> </form>';
    
} // ends check for client selected or new client
    
 
echo '</div><br />';

 
if ($clientid=='') {
    echo '<script>
    $(document).ready(function() { setTimeout( function() { $("#comboboxbutton").click() }, 100 ); });			
    </script>';
}
 


echo '<script type="text/javascript">

    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+35));

$(function() {	$("#tabs").tabs();	});


$(document).ready(function() {


		
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});

			$(function(){ $(".normal").autosize();	});
	});
	

	function comboboxchanged() { }	
</script>';

include "footer.php";

echo '</body></html>';