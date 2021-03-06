<?php 
/*
    COJM Courier Online Operations Management
	index.php - main job schedule
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

include "C4uconnect.php";

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php"; // eg in case sent here via job deletion

echo '<!DOCTYPE html> <html lang="en"> <head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<title>COJM : '. ($cyclistid).'</title>';


if($mobdevice) { // already coded as js variable ?
    $numberofresults=$globalprefrow['numjobsm'];
} else {
    $numberofresults=$globalprefrow['numjobs'];
}


?>
<script>



var formbirthday=<?php echo microtime(TRUE); ?>;
var numberofresults=<?php echo $numberofresults; ?>;
var offset=0;
var seeifnextday=0;
var dayflag=0;
var flag = true;
var allloaded = false;


function isEven(n) {
  return n == parseFloat(n)? !(n%2) : void 0;
}


function addlines() {
    var statuscheck='';
    var oldstatus=0;
    var oldday=-1;
    var afterhtml="";
    var dayflag=1;
    var counted=0;
    $( ".indexstatuschange" ).remove();
    $(".indexlisttr").removeClass("ui-state-default").removeClass("ui-state-highlight");
    $(".indexstatus").removeClass("ui-state-default").removeClass("ui-state-highlight");
    $(".indexrider").removeClass("ui-state-default").removeClass("ui-state-highlight");
    
    $( ".indexrider" ).each(function( i ) {
        afterhtml="";
        var id = this.id;
        orderid = parseInt(id.match(/(\d+)$/)[0], 10);

        if ($( "#stat"+orderid).is(":visible")) {
            counted++;
            var newday=$('#index'+orderid).data('day');
            if (oldday!==newday) {
                if (oldday>-1) {
                    afterhtml=' <div class="linevpad indexstatuschange" </div> ';
                }
                oldday=newday;
            }
            var status=$("#stat"+orderid).val();
            if (oldstatus!==status){
                if (oldstatus>0) {
                    afterhtml=' <hr class="indexstatuschange"/> ';
                }
                oldstatus=status;
            }
            $("#index"+orderid).before(afterhtml);
            
            if (afterhtml){
                dayflag++;
            }
            if (isEven(dayflag)) {
                $("#index"+orderid).addClass("ui-state-highlight");
                $("#stat"+orderid).addClass("ui-state-highlight");
                $("#cyc"+orderid).addClass("ui-state-highlight");
            } else {
                $("#index"+orderid).addClass("ui-state-default");
                $("#stat"+orderid).addClass("ui-state-default");
                $("#cyc"+orderid).addClass("ui-state-default");
            }
        }
    });
    $("#indexcounted").html(counted + ' of ');
}



$(document).on('change', '#topmenutimeoutchoose', function(){
    pagetimeout=$("#topmenutimeoutchoose").val();
    initialpagetimeout=$("#topmenutimeoutchoose").val();
    $("span[id=cdtext]").addClass('hideuntilneeded');
});

function pagetimeoutfunc(){
    $("#toploader").show();
    // alert(" index timeout function ");
    numberofresults=offset;
    offset=0;
    seeifnextday=0;
    dayflag=0;
    $("#allindexresults").addClass('hideuntilneeded');
    $("#indexajax").html("");
    refreshindex(indexfilter);
    pagetimeout=initialpagetimeout;
    $("span[id=cdtext]").addClass('hideuntilneeded');
}



function alldisplayed() {
    $("#allindexresults").show();
    flag = false;
    var numItems = $('.indexlisttr ').length;
    if(numItems>0) {
        $("#indextotal").html(numItems);
    } else {
        $("#allindexresults").html(" <h1> No Jobs Outstanding or Scheduled </h1> ");
    }
    allloaded = true;
}

function refreshindex(callback) {
    $("#allindexresults").hide();
    dataString = "lookuppage=indexlist" +  
    "&numberofresults=" + numberofresults +
    "&offset=" + offset +
    "&seeifnextday=" + seeifnextday +
    "&dayflag=" + dayflag;

    $.ajax({
        type: "POST",
        url:"ajax_lookup.php",
        data: dataString,
        success: function (data){
            $("#indexajax").append(data);
            
        },
        complete: function () {
            pagetimeout=$("#topmenutimeoutchoose").val();
            
            $("#toploader").fadeOut();
            if (callback && typeof(callback) === "function") {
                callback();
            } else {
                $("#toploader").fadeOut();
                addlines();
            }
            
        }
    });
}

refreshindex();
$(window).scroll(function() {
	if($(window).scrollTop() + $(window).height() + 250 > $(document).height()){
		if(flag){
			flag = false;
			$('#toploader').show();
            // alert(" here " + offset);
            refreshindex();
		}
	}
});

$(document).on('change', '.indexstatus', function(e){
    // $("#allindexresults").hide();
    flag = false;
    var id = e.target.id;
    orderid = parseInt(id.match(/(\d+)$/)[0], 10);
    $("#menusearchinput").val(orderid);
    $("#toploader").show();
    var newstatus=$("#"+id).val();
    $.ajax({
        url: 'ajaxchangejob.php',
        data: {
            page: 'ajaxorderstatus',
            formbirthday: formbirthday,
            id: orderid,
            newstatus: newstatus
        },
        type: 'post',
        success: function (data) {
            $('#Post').prepend(data);
        },
        complete: function () {
            showmessage();
            
            if (newstatus<77) {
                numberofresults=offset;
                offset=0;
                seeifnextday=0;
                dayflag=0;
                $("#allindexresults").hide();
                $("#indexajax").html("");
                refreshindex(indexfilter);
            } else {
                if (allok==1) {
                    // alert(" beer ");
                    $("#index" + orderid).remove();
                    // alert("deleted, lines ");
                    offset--;
                    addlines();
                    $("#toploader").fadeOut();
                } else {
                    alert("Error, refresh whole page.");
                }
                
                
            }
        }
    });
}); 
    
$(document).on('change', '.indexrider', function(e){
    var id = e.target.id;
    orderid = parseInt(id.match(/(\d+)$/)[0], 10);
    $("#menusearchinput").val(orderid);
    $("#toploader").show();
    var newrider=$("#"+id).val();
    $.ajax({
        url: 'ajaxchangejob.php',
        data: {
            page: 'ajaxchangerider',
            formbirthday: formbirthday,
            id: orderid,
            newrider: newrider
        },
        type: 'post',
        success: function (data) {
            $('#Post').prepend(data);
        },
        complete: function () {
            showmessage();
            $("#toploader").fadeOut();
        }
    });
});


function indexfilter() {
    var selectedrider=$("#topmenuselectrider").val();
    if (selectedrider==='') {
        $( ".indexlisttr" ).show();
    } else {
        $( ".indexrider" ).each(function( i ) {
            var id = this.id;
            orderid = parseInt(id.match(/(\d+)$/)[0], 10);
            var newrider=$("#"+id).val();
            
            if (newrider===selectedrider) {
                $("#index"+orderid).show();
                
            } else {
                $("#index"+orderid).hide();
            }
        });
    }
    addlines();
    $("#toploader").fadeOut();
}


$(document).on('change', '#topmenuselectrider', function(){
    indexfilter();
    if ( allloaded == false) {
        numberofresults=500;
        refreshindex(function() {
            indexfilter();
        });
    }
});




</script>

<style>
#toploader { display:block; }
</style>

<?php 

echo '

</head>
<body id="bodytop" >';



$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$riderdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
    

//////////     CYCLIST   DROPDOWN     ///////////////////////////////////
$menuhtml= ' <select id="topmenuselectrider" class="ui-corner-left ui-state-default left" title="Filter by '.$globalprefrow['glob5'].'" >';
$menuhtml.= ' <option value=""> All '.$globalprefrow['glob5'].'s </option> ';

foreach ($riderdata as $ridernum => $ridername) {
    $ridername=htmlspecialchars($ridername);
    $menuhtml.= ("<option ");
    $menuhtml.= ("value=\"$ridernum\">$ridername</option>");
}
$menuhtml.= '</select> ';



// one option needs to match global pref for form timeouts
$menuhtml.='

<select id="topmenutimeoutchoose" class="ui-corner-left ui-state-default left">
<option ';

if ($globalprefrow['formtimeout']==125) { $menuhtml.=' selected '; }
$menuhtml.='value="125">2m </option>
<option ';
if ($globalprefrow['formtimeout']==300) { $menuhtml.=' selected '; }
$menuhtml.='
value="300">5m </option>
<option ';
if ($globalprefrow['formtimeout']==600) { $menuhtml.=' selected '; }
$menuhtml.='
value="600">10m </option>
<option ';
if ($globalprefrow['formtimeout']==900) { $menuhtml.=' selected '; }
$menuhtml.='value="900">15m </option>
<option ';
if ($globalprefrow['formtimeout']==1200) { $menuhtml.=' selected '; }
$menuhtml.='value="1200">20m </option>
</select> ';



$hasforms='1';
$filename="index.php";
include "cojmmenu.php";



?>
<div id="Post" class="Post c9 lh16">
    <div id="indexajax"> </div>  
    <div id="allindexresults" class="hideuntilneeded">
        <hr />
        <div class="ui-state-highlight ui-corner-all p15">
            <h2> <span id="indexcounted">All</span> <span id="indextotal"> </span> Results Displayed.</h2>
            <p>Last Updated <span id="indexlastupdated"></span> </p>
        </div>
        <div class="vpad "> </div>
        <hr />
    </div>
</div>
<?php include "footer.php";
echo ' </body></html> ';
