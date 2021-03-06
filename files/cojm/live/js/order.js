//     COJM Courier Online Operations Management
//     order.js - Javascript File only used in order.php , the main edit job page
//     Copyright (C) 2017 S.Young cojm.co.uk
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

var $;
var id;
var message;
var oktosubmit;
var allok;
var oldclientorder;
var initialdeporder;
var initialstatus;
var initialtargetcollectiondate;
var initialdeliveryworkingwindow;
var initialduedate;
var initialstarttrackpause;
var initialfinishtrackpause;
var initialstarttravelcollectiontime;
var initialwaitingstarttime;
var initialcollectionworkingwindow;
var initialcollectiondate;
var initialShipDate;
var initialjobrequestedtime;
var initialjobcomments;
var initialprivatejobcomments;
var initialrequestor;
var initialclientjobreference;
var waitingtimedelay;
var waitingmins;
var haspod;
var podsurname;
var formbirthday;
var showmessage;
var statustoohigh;
var publictrackingref;
var canshowareafromservice;
var initialhassubarea;
var b64lineCoordinates;
var lineCoordinates=[];
var line;
var thismarkerloop=[];
var thisareamaxlat = -99;
var thisareamaxlng = -99;
var thisareaminlat = 999;
var thisareaminlng = 999;

var subareaselectedmaxlat = -99;
var subareaselectedmaxlng = -99;
var subareaselectedminlat = 999;
var subareaselectedminlng = 999;
                
                
console.log("started order.js");

$(function () { // Document is ready
    "use strict";


    
    
    
    
        
function onBoundsChanged() {
    
    var mapheight = $(map.getDiv()).children().eq(0).height();
    var mapwidth = $(map.getDiv()).children().eq(0).width();
    
    if ( (mapheight == window.innerHeight ) && ( mapwidth == window.innerWidth ) ) {
        // console.log( 'FULL SCREEN' );
    }
    else {
        // console.log ('NOT FULL SCREEN');
    }

    if (oldheight==mapheight) {
        // console.log ('MAP HEIGHT NOT CHANGED');
    }
    else {
        oldheight=mapheight;
        console.log ('MAP HEIGHT CHANGED in onBoundsChanged, running resetmapbounds');
        resetmapbounds();
    }
}



    
    
    function processordermap() {
        
        var mapcomments='';
        
        var areachanged=0;
        var subareachanged=0;
        
        
        console.log("Starting to process order map");
        
        var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
            strokeOpacity: 0.3
        };
        
        
        
        var image = {
            url: clweb3,
            size: new google.maps.Size(20, 20),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(10, 10)
        };
        

        var gmarkers = []; // container for all gmarkers to be included in 
        
        
        
        

        // locations - array of 1 min GPS tracks
        // b64lineCoordinates - b64 line co-ordinate array ( lat,lng ) 
        // oldb64lineCoordinates
        
        

        
        
        
        if (b64clientlocations == oldb64clientlocations) {
            console.log("b64clientlocations the same");
        } else {
            console.log("b64clientlocations different");
            oldb64clientlocations=b64clientlocations;
            
            var temp=b64DecodeUnicode(b64clientlocations);
            
            clientdetailarray = JSON.parse(temp);
            
            if (clientcoords.length>0) {  }
            
                for (var i=0; i<clientcoords.length; i++) {
                clientcoords[i].setMap(null);
            }
            
            
            console.log(" 0 : " + clientdetailarray[0]);
            console.log(" 1 : " + clientdetailarray[1]);
            console.log(" 2 : " + clientdetailarray[2]);
            
            if (clientdetailarray[0]) {

                console.log("client address found");
                
                var myLatLng = new google.maps.LatLng(clientdetailarray[0][0],clientdetailarray[0][1]);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Client Address',
                    zIndex: 2,
                    icon: goldStar,
                    class: 'clientdetailsicon',
                    optimized: false
                });
                
                clientcoords.push(marker);
                
            }
            
            if (( clientdetailarray[1][0] == 0 ) || ( clientdetailarray[1][0]==clientdetailarray[0][0] )) {
                console.log("No Invoice postcode ");
            } else {
                console.log("Different Invoice postcode");
                var myLatLng = new google.maps.LatLng(clientdetailarray[1][0],clientdetailarray[1][1]);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Client Invoice Address',
                    zIndex: 1,
                    icon: goldStar,
                    class: 'clientdetailsicon',
                    optimized: false
                });
                
                clientcoords.push(marker);              
            }
            
            if (( clientdetailarray[2][0] == 0 ) || ( clientdetailarray[2][0]==clientdetailarray[0][0] )) {
                console.log("No department postcode ");
            } else {
                console.log("Department postcode");
                
                
                var myLatLng = new google.maps.LatLng(clientdetailarray[2][0],clientdetailarray[2][1]);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Department Address',
                    zIndex: 1,
                    icon: goldStar,
                    class: 'clientdetailsicon',
                    optimized: false
                });
                
                clientcoords.push(marker);                
                
                
            }
            
            
            
        }
        
        
        
        
        
        if (b64jobstuff == oldb64jobstuff ) {
            // console.log("b64jobstuff the same");
        } else {
            // console.log("b64jobstuff changed");
            oldb64jobstuff=b64jobstuff;
            
            if (jobstuffcoords.length>0) {  }
            
                for (var i=0; i<jobstuffcoords.length; i++) {
                jobstuffcoords[i].setMap(null);
            }
            
            jobstuffcoords=[];
            
            var temp=b64DecodeUnicode(b64jobstuff);
            
            console.log("jobarray temp: " + temp);
            jobarray = JSON.parse(temp);
            
            console.log("jobarray length : " + jobarray.length);
            
            
            jobmaxlat = -99;
            jobmaxlng = -99;
            jobminlat = 999;
            jobminlng = 999;
            
            
            
            
            
            for (var i = 0; i < jobarray.length; i++) {
            
                if (jobarray[i][1]) { // address has latitude
                    // console.log("Address " + jobarray[i]);
                    
                    if(jobarray[i][2]>jobmaxlng) { jobmaxlng = jobarray[i][2]; }
                    if(jobarray[i][2]<jobminlng) { jobminlng = jobarray[i][2]; }
                    if(jobarray[i][1]>jobmaxlat) { jobmaxlat = jobarray[i][1]; }
                    if(jobarray[i][1]<jobminlat) { jobminlat = jobarray[i][1]; }
                    
                    var myLatLng = new google.maps.LatLng(jobarray[i][1], jobarray[i][2]);
                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        title: jobarray[i][0],
                        zIndex: 5,
                        icon: jobarray[i][3],
                        optimized: false
                    });
                    
                    jobstuffcoords.push(marker);
                    
                }
            
            }
            
        }
        
        
        
        
        
        
        if (b64lineCoordinates==oldb64lineCoordinates) {
            // console.log("rider line unchanged");
        } else {
            // console.log("rider line changed");
            
            if (lineCoordinates.length>0) { line.setMap(null); }
            
            // console.log("removed rider line");
            
            oldb64lineCoordinates=b64lineCoordinates;
            var templine=b64DecodeUnicode(b64lineCoordinates);
            
            // console.log("templine: " + templine);
            
            
            lineCoordinates = JSON.parse(templine);
            
            // console.log("number of line co-ordinates : " + lineCoordinates.length );
        
            for (var j = 0; j < lineCoordinates.length; j++) {
                var lat = lineCoordinates[j][0];
                var lng = lineCoordinates[j][1];
                var marker = new google.maps.LatLng(lat, lng);
                gmarkers.push(marker);
            }
            
            // console.log("finished rider coordinates array");
            
            line = new google.maps.Polyline({
                path: gmarkers,
                geodesic: true,
                strokeOpacity: 0.7,
                icons: [{
                    icon: lineSymbol,
                    repeat: "50px"
                }],
                map: map,
                zIndex: 1600,
                optimized: false
            });
            
        }
        
        // console.log("finished rider line");
        
        
        
        if (oldb64locations==b64locations) { // console.log("rider 1 min locations unchanged");
            
        } else {
            
            // console.log("rider 1 min locations changed");
            oldb64locations=b64locations;
            
            var temploc=b64DecodeUnicode(b64locations);
            // console.log("temploc: " + temploc);
            locations = JSON.parse(temploc);
            
            for (var i=0; i<markers.length; i++) {
                markers[i].setMap(null);
            }    
            // Reset the markers array
            markers = [];
            
            var infowindow = new google.maps.InfoWindow();
        
            var marker, i;


            
            console.log("total 1 min rider locations : " + locations.length);
            
            ridermaxlat = -99;
            ridermaxlng = -99;
            riderminlat = 999;
            riderminlng = 999;
            
            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    icon: image,
                    zIndex: 2000,
                    optimized: false
                });
                google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
                    return function() {
                    infowindow.setContent(" <div style=\" width: 110px; \"> "+locations[i][0] + " </div> " );
                    infowindow.setOptions({ disableAutoPan: true });
                    infowindow.open(map, marker);
                    }
                })(marker, i));
                
                markers.push(marker);
                                
                if(locations[i][2]>ridermaxlng) { ridermaxlng = locations[i][2]; }
                if(locations[i][2]<riderminlng) { riderminlng = locations[i][2]; }
                if(locations[i][1]>ridermaxlat) { ridermaxlat = locations[i][1]; }
                if(locations[i][1]<riderminlat) { riderminlat = locations[i][1]; }
            }
            
        }
        
        
        if (oldmainarea==b64mainarea) {
            // console.log("main area the same");
        } else {
            // console.log("main area different");
            
            // console.log("b64mainarea : " + b64mainarea);
            
            if (mainareamarkers.length>0) { polymainarea.setMap(null); }
            
            var templine=b64DecodeUnicode(b64mainarea);
            
            // console.log("area b4 json parse : " + templine);
            
            mainarea = JSON.parse(templine);
            
            console.log("total main area points : " + mainarea.length);
            
            mainareamaxlat = -99;
            mainareamaxlng = -99;
            mainareaminlat = 999;
            mainareaminlng = 999;
            
            mainareamarkers=[];
            
            if (mainarea.length>0) {
            
                for (i = 0; i < mainarea.length; i++) {
                    var areamarker = new google.maps.LatLng(mainarea[i][0], mainarea[i][1]);
                    mainareamarkers.push(areamarker);
                
                    if(mainarea[i][1]>mainareamaxlng) { mainareamaxlng = mainarea[i][1]; }
                    if(mainarea[i][1]<mainareaminlng) { mainareaminlng = mainarea[i][1]; }
                    if(mainarea[i][0]>mainareamaxlat) { mainareamaxlat = mainarea[i][0]; }
                    if(mainarea[i][0]<mainareaminlat) { mainareaminlat = mainarea[i][0]; }
                }
                
                // console.log("mainareamarkers points : " + mainareamarkers.length);

                polymainarea = new google.maps.Polygon({
                    paths: [worldCoords, mainareamarkers],
                    strokeWeight: 3,
                    strokeOpacity: 0.6,
                    fillColor: "#667788",
                    fillOpacity: 0.2,
                    strokeColor: "#000000",
                    clickable:false,
                    map:map
                    });
            }
            
            
            // get b64 sub area array in [[areaid,[co-ords]],[areaid,[co-ords]]],
            
            // console.log("process sub areas into array format");
            
            var temp=b64DecodeUnicode(subareaarrayb64);
       
            subareaarray = JSON.parse(temp);
            
            // console.log(subareaarray);
            
            // console.log("total sub areas : " + subareaarray.length);
        
        } // ends check main area data has changed
        
        
        allsubareamarkers=[];
        
        // reset areas
        
        if (mapofselectedopsmapsubarea) { mapofselectedopsmapsubarea.setMap(null); }
        
        // console.log(" existing sub area maps (excl. selected) " + mapsofsubareas.length);
        
        if (mapsofsubareas.length) {
            for (i = 0; i < mapsofsubareas.length; i++) {
                // console.log("existing overlay found");
                mapsofsubareas[i].setMap(null);
            }
        }
        
        
        
        if (subareaarray.length>0) {
            // console.log(" sub areas in array, about to loop through them all");
            
            for (i = 0; i < subareaarray.length; i++) {
            
                // console.log(subareaarray[i][3]);
                
                thismarkerloop=[];
                
                thisareamaxlat = -99;
                thisareamaxlng = -99;
                thisareaminlat = 999;
                thisareaminlng = 999;
                
                for (j = 0; j < subareaarray[i][3].length; j++) {
                    // console.log("Point in sub area found" + subareaarray[i][3][j][0] + " " + subareaarray[i][3][j][1]);
                    var areamarker = new google.maps.LatLng(subareaarray[i][3][j][0],subareaarray[i][3][j][1]);

                    allsubareamarkers.push(areamarker);
                    thismarkerloop.push(areamarker);
                    
                    if(subareaarray[i][3][j][1]>thisareamaxlng) { thisareamaxlng = subareaarray[i][3][j][1]; }
                    if(subareaarray[i][3][j][1]<thisareaminlng) { thisareaminlng = subareaarray[i][3][j][1]; }
                    if(subareaarray[i][3][j][0]>thisareamaxlat) { thisareamaxlat = subareaarray[i][3][j][0]; }
                    if(subareaarray[i][3][j][0]<thisareaminlat) { thisareaminlat = subareaarray[i][3][j][0]; }
                }
                
                
                
                var centlat=((thisareamaxlat-thisareaminlat)/2)+thisareaminlat;   
                var centlng=((thisareamaxlng-thisareaminlng)/2)+thisareaminlng;
                var cent = new google.maps.LatLng(centlat, centlng);
                
                // console.log("cent" + cent);

                if (opsmapsubarea==subareaarray[i][0]) { // console.log("Sub Area Selected");
                    
                    mapofselectedopsmapsubarea= new google.maps.Polygon({
                    paths: [worldCoords, thismarkerloop],
                    strokeWeight: 10,
                    strokeOpacity: 0.6,
                    fillColor: "#5555FF",
                    fillOpacity: 0.15,
                    strokeColor: "#00FF00",
                    clickable: false,
                    map: map
                    });
    
                    subareaselectedmaxlat = thisareamaxlat;
                    subareaselectedminlng = thisareaminlng;
                    subareaselectedminlat = thisareaminlat;
                    subareaselectedmaxlng = thisareamaxlng;
                
                } else {
                    // console.log("Not Selected");
                    
                    // console.log("thismarkerloop" + thismarkerloop)
                    
                    mapsofsubareas.push ( new google.maps.Polygon({
                    paths: [thismarkerloop],
                    strokeWeight: 3,
                    strokeOpacity: 0.3,
                    strokeColor: "#000000",
                    fillOpacity: 0,
                    clickable: false,
                    map: map
                    }));
                    
                    mapsofsubareas.push ( new RichMarker({
                    position: cent,
                    flat: true,
                    map: map,
                    draggable: false,
                    content: '<div class="map-sub-area-label"><a href="opsmap-new-area.php?areaid=' + subareaarray[i][0] + '">' + subareaarray[i][1] + '</a></div>'
                    }));
        
                }
               
            }
            
        }
        
        
        
        // all arays up to date
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        // update lat / lng + sub areas
        
        maxlat = -99;
        maxlng = -99;
        minlat = 999;
        minlng = 999;
        




        
        if (locations.length) { // rider tracking
            
            mapcomments=mapcomments  + '<div class="fs"> <div class="fsl"> Tracking </div>' + maptimes + ' ' + locations.length + ' markers, ' + lineCoordinates.length + ' points. </div>';
            
            if( ridermaxlat > maxlat) { maxlat = ridermaxlat; }
            if( riderminlng < minlng) { minlng = riderminlng; }
            if( riderminlat < minlat) { minlat = riderminlat; }
            if( ridermaxlng > maxlng) { maxlng = ridermaxlng; }
            
            // console.log("rider locations found with max / mins of : ");
            // console.log(ridermaxlng);
            // console.log(riderminlng);
            // console.log(ridermaxlat);
            // console.log(riderminlat);     
        } else {
            
            
            if (jobmaxlat>-98) {

                if( jobmaxlat > maxlat) { maxlat = jobmaxlat; }
                if( jobminlng < minlng) { minlng = jobminlng; }
                if( jobminlat < minlat) { minlat = jobminlat; }
                if( jobmaxlng > maxlng) { maxlng = jobmaxlng; }
                
                // console.log("job locations found with max / mins of : ");
                // console.log(jobmaxlng);
                // console.log(jobminlng);
                // console.log(jobmaxlat);
                // console.log(jobminlat);
            }
        }
        
        
        // console.log("opsmapsubarea: " + opsmapsubarea);
        
        



        if (opsmapsubarea>0) {
            console.log("using sub area min / max");
        
            if ( subareaselectedmaxlat > maxlat ) { maxlat = subareaselectedmaxlat; }
            if ( subareaselectedminlng < minlng ) { minlng = subareaselectedminlng; }
            if ( subareaselectedminlat < minlat ) { minlat = subareaselectedminlat; }
            if ( subareaselectedmaxlng > maxlng ) { maxlng = subareaselectedmaxlng; }
        
        } else if (mainareamarkers.length>0) {
            console.log("using main area min / max");
            if( mainareamaxlat > maxlat) { maxlat = mainareamaxlat; }
            if( mainareaminlng < minlng) { minlng = mainareaminlng; }
            if( mainareaminlat < minlat) { minlat = mainareaminlat; }
            if( mainareamaxlng > maxlng) { maxlng = mainareamaxlng; }
        }
        
        
        
        // console.log("new total max mins are : ");
        // 
        // console.log(maxlng);
        // console.log(minlng);                       
        // console.log(maxlat);
        // console.log(minlat);
        
        
        
        
        var needtoshowmap = locations.length + mainareamarkers.length + jobstuffcoords.length;
        
        console.log("locations : " + locations.length);
        console.log("mainareamarkers : " + mainareamarkers.length);
        console.log("jobstuffcoords : " + jobstuffcoords.length);
        console.log("needtoshowmap : " + needtoshowmap);
        
        
        if ((needtoshowmap)>0) { // add other array lengths if need to be in map
            
            console.log("locations present so show map, running resetmapbounds");

            $("#orderajaxmap").removeClass("hideuntilneeded");
            
            
            
            google.maps.event.trigger(map, 'resize');
                        resetmapbounds();
            if (existingmap==1) {
                // console.log("existing map");
            } else {
                // console.log("NEED TO initialise map");
                existingmap=1;
                google.maps.event.addListener( map, 'bounds_changed', onBoundsChanged );

            }
            
            

            
            
        } else {
            // console.log("NO locations present NO map");
            $("#orderajaxmap").addClass("hideuntilneeded");
        }
        
        

        if (mapcomments=='') {
            $("#mapcomments").hide();
        } else {
            $("#mapcomments").html(mapcomments).show();
        }
        
        console.log("finished processordermap function  ");
    }
 
 
    function resetmapbounds() {
        
        console.log("function resetmapbounds ");
        
        console.log(maxlng);
        console.log(minlng);                       
        console.log(maxlat);
        console.log(minlat);
        
        map.setZoom(17);
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(maxlat, minlng)); // upper left
        bounds.extend(new google.maps.LatLng(maxlat, maxlng)); // upper right
        bounds.extend(new google.maps.LatLng(minlat, maxlng)); // lower right
        bounds.extend(new google.maps.LatLng(minlat, minlng)); // lower left
        map.setOptions({ maxZoom: 17 });
        map.fitBounds(bounds);
        map.setOptions({ maxZoom: null });
        
    }

 
    
    function ordermapupdater() {
        $("#toploader").show();
        showhidebystatus();
        
        console.log(" trigger update order.js 63 ");
        
        $.ajax({
            url: 'ajax_lookup.php',
            data: {
                lookuppage: 'ordermap',
                formbirthday: formbirthday,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#mapajax').html(data);
            },
            complete: function (data) {
                
                processordermap();
                
                $("#toploader").fadeOut();
            }
        });
    }


    function loadScript(url, callback) {
        // Adding the script tag to the head as suggested before
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = url;
    
        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        script.onreadystatechange = callback;
        script.onload = callback;
    
        // Fire the loading
        head.appendChild(script);
        console.log("loading " + url);
    }
    
    
   
    
    
    
    
    var whenmaptemplateloaded = function() {
        initialize();
        console.log("map template loaded");

         ordermapupdater();
        
    };





function loadmaptemplate() {

     loadScript("../js/maptemplate.js", whenmaptemplateloaded);

}
    
 loadmaptemplate();    
    


    (function( $ ) {
        $.widget( "ui.orderselectdep", {
            _create: function() {
                var self = this,
                    select = this.element.hide(),
                    selected = select.children( ":selected" ),
                    value = selected.val() ? selected.text() : "";
                var input = this.input = $( "<input>" )
                    .insertAfter( select )
                    .val( value )
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: function( request, response ) {
                            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                            response( select.children( "option" ).map(function() {
                                var text = $( this ).text();
                                if ( this.value && ( !request.term || matcher.test(text) ) )
                                    return {
                                        label: text.replace(
                                            new RegExp(
                                                "(?![^&;]+;)(?!<[^<>]*)(" +
                                                $.ui.autocomplete.escapeRegex(request.term) +
                                                ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                            ), "<strong>$1</strong>" ),
                                        value: text,
                                        option: this
                                    };
                            }) );
                        },
                        select: function( event, ui ) {
                            ui.item.option.selected = true;
                            self._trigger( "selected", event, {
                                item: ui.item.option
                            });
                        depcomboboxchanged();
                        },
                        change: function( event, ui ) {
                            if ( !ui.item ) {
                                var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                                    valid = false;
                                select.children( "option" ).each(function() {
                                    if ( $( this ).text().match( matcher ) ) {
                                        this.selected = valid = true;
                                        return false;
                                    }
                                });
                                if ( !valid ) {
                                    // remove invalid value, as it didn't match anything
                                    $( this ).val( "" );
                                    select.val( "" );
                                    input.data( "autocomplete" ).term = "";
                                    return false;
                                }
                            }
                        }
                    })
                    .addClass( "ui-widget ui-widget-content ui-corner-left" ).attr('id', 'autocompleteorderselectdep');
                    
                    
                    
                input.data( "autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li></li>" )
                        .data( "item.autocomplete", item )
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
                };
                this.button = $( "<button type='button'>&nbsp;</button>" )
                    .attr( "tabIndex", -1 )
                    .attr( "title", "Show All" )
                    .attr( "id", "depcomboboxbutton")
                    .insertAfter( input )
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass( "ui-corner-all" )
                    .addClass( "ui-corner-right ui-button-icon" )
                    .click(function() {
                        // close if already visible
                        if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                            input.autocomplete( "close" );
                            return;
                        }
                        // work around a bug (likely same cause as #5265)
                        $( this ).blur();
                        // pass empty string as value to search for, displaying all results
                        input.autocomplete( "search", "" );
                            input.focus().setCursorPosition(99);
                    });
            },
            destroy: function() {
                this.input.remove();
                this.button.remove();
                this.element.show();
                $.Widget.prototype.destroy.call( this );
            }
        });
    })( jQuery );

    
    $("#orderselectdep").orderselectdep();


    function process(date){
    var newdate;
	var datetime = date.split(" ");  
    var parts = datetime[0].split("/");
    var timeparts = datetime[1].split(":");
    newdate = new Date(parts[2], parts[1] - 1, parts[0], timeparts[0], timeparts[1]);
    return newdate;
 }


    function showhidebystatus() { // or changes to time values for showing buttons for working windows
    

        if (initialdeporder < 1) {
            $("#autocompleteorderselectdep").addClass("autoinputerror");
        } else {
            $("#autocompleteorderselectdep").removeClass("autoinputerror");
        }



        if (initialstatus < 31) {
            $("#starttravelcollectiontimediv").hide();
        } else {
            $("#starttravelcollectiontimediv").show();
        }


        if (initialstatus < 49) {
            $("#waitingstarttimediv").hide();
        } else {
            $("#waitingstarttimediv").show();
        }


        if (initialstatus < 59) {
            $("#collectiondatediv").hide();
        } else {
            $("#collectiondatediv").show();
        }


        if (initialstatus < 69) {
            $("#ShipDatediv").hide();
        } else {
            $("#ShipDatediv").show();
        }



        if (initialstatus < 100 && initialdeliveryworkingwindow === "") {
            $("#allowdww").show();
        } else {
            $("#allowdww").hide();
        }




        if (initialstatus < 100 && initialcollectionworkingwindow === "") {
            $("#allowww").show();
        } else {
            $("#allowww").hide();
        }


        if (initialdeliveryworkingwindow !== "") {
            $("#untildww").show();
            $("#deliveryworkingwindow").show();
        } else {
            $("#untildww").hide();
            $("#deliveryworkingwindow").hide();
        }


        if (initialcollectionworkingwindow !== "") {
            $("#allowwwuntil").show();
            $("#collectionworkingwindow").show();
        } else {
            $("#allowwwuntil").hide();
            $("#collectionworkingwindow").hide();
        }



        if ((initialstatus > 99) && (initialjobcomments !== 1)) {
            $("#jobcommentsdiv").hide();
        } else {
            $("#jobcommentsdiv").show();
        }


        if ((initialstatus > 99) && (initialprivatejobcomments !== 1)) {
            $("#privatejobcommentsdiv").hide();
        } else {
            $("#privatejobcommentsdiv").show();
        }



        
        
        if (initialstatus > 99) {
            
            
            $("#Post").addClass("complete"); 
            $(".chngfav").hide();
            $(".activewheneditable").hide();
            $(".deleteord").hide();
        } else {

        $(".chngfav").show();
            $(".activewheneditable").show();
            $("#Post").removeClass("complete");
            $(".deleteord").show();
        }
        
        if (initialstatus > 99) {
            $("#buttoncancelpricelock").addClass("buttoncancelpricelocklocked");
        } else {
            $("#buttoncancelpricelock").removeClass("buttoncancelpricelocklocked");
        }



        if (initialstatus > 99) {
            var subarea = $("#opsmapsubarea").val();
            if (subarea === "") {
                $("#opsmapsubarea").hide();
            } else {
                $("#opsmapsubarea").show();
            }
        }
        else {
            if (initialhassubarea === 1) {
                $("#opsmapsubarea").show();
            } else {
                $("#opsmapsubarea").hide();                
            }
        }


        if (initialstatus < 31) {
            $("#currorsched").hide();
        } else {
            $("#currorsched").show();
        }





        if (initialstatus > 99) {
            $("#toggleresumechoose").hide();
        } else {
            
            if ((initialstarttrackpause !== "") || (initialfinishtrackpause !== "")) {
                $("#toggleresumechoose").hide();
            } else {
                $("#toggleresumechoose").show();
            }
        }


        if ((initialstatus > 99 && initialstarttravelcollectiontime === "") || (initialstatus < 40)) {
            $("#starttravelcollectiontimediv").hide();
        } else {
            $("#starttravelcollectiontimediv").show();
        }

        if ((initialstatus > 99 && initialwaitingstarttime === "") || (initialstatus < 50)) {
            $("#waitingstarttimediv").hide();
        } else {
            $("#waitingstarttimediv").show();
        }


        if (initialstatus > 99 && initialrequestor === "") {
            $("#requestordiv").hide();
        } else {
            $("#requestordiv").show();
        }



        if (initialstatus > 99 && initialclientjobreference === "") {
            $("#clientjobreferencediv").hide();
        } else {
            $("#clientjobreferencediv").show();
        }

        
        if (initialstatus > 99 && haspod === 0) {
            $("#podcontainer").hide();
        } else {
            $("#podcontainer").show();
        }
        
        if (initialstatus < 99 && haspod === 0) {
            $("#podcontainer").show();
            $("#uploadpodfile").show();
        } else {
            $("#uploadpodfile").hide();
        }
        
        if (initialstatus > 99) {
            $("#ajaxremovepod").hide();
            $("#uploadpodfile").hide();
        } else {
            if  (haspod === 1) {
                $("#uploadpodfile").hide();
            } else {
                // alert("haspod" + haspod);
                $("#uploadpodfile").show();
            }
        }






        
        var maparea= $("select#opsmaparea").val();
        
        if (initialstatus > 99 ) {
            if (maparea) {
                $("#areaselectors").show();
            }
            else {
                $("#areaselectors").hide();
            }
        } else {
            if (canshowareafromservice === 1) {
                $("#areaselectors").show();
            } else {
                $("#areaselectors").hide();
            }
        }
        


        
        if (initialstatus < 86 && mobdevice === 1) {
            $("#completeoption").hide();            
        } else {
            $("#completeoption").show();
        }

    } // ends showhidebystatus


    function testtimes() {
        showhidebystatus(); // updates which buttons to show
// if existing message start a new line
        if (message !== "") {
            message += "<br />";
        }
        oktosubmit = 1;

        if (initialtargetcollectiondate === "") {
            message += "No Target Collection Time <br /> ";
            oktosubmit = 0;
        }

        if (initialduedate === "") {
            message += "No Target Delivery Time <br /> ";
            oktosubmit = 0;
        }

        if (initialcollectiondate !== "") {
            if (initialShipDate !== "") {
                if (process(initialcollectiondate) > process(initialShipDate)) {
                    message += "Collection (" + initialcollectiondate + ") needs to be earlier than delivery (" + initialShipDate + ") <br /> ";
                    oktosubmit = 0;
                }
            }
        }


        if (initialduedate !== "") {
            if (initialdeliveryworkingwindow !== "") {
                if (process(initialdeliveryworkingwindow) < process(initialduedate)) {
                    message += "Delivery window start (" + initialduedate + ") needs to be earlier than Delivery window finish (" + initialdeliveryworkingwindow + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialcollectionworkingwindow !== "") {
            if (initialtargetcollectiondate !== "") {
                if (process(initialcollectionworkingwindow) < process(initialtargetcollectiondate)) {
                    message += "Collection window start (" + initialtargetcollectiondate + ") needs to be earlier than Collection window finish (" + initialcollectionworkingwindow + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialstarttrackpause !== "") {
            if (initialfinishtrackpause !== "") {
                if (process(initialstarttrackpause) > process(initialfinishtrackpause)) {
                    message += "Pause (" + initialstarttrackpause + ") needs to be earlier than Resume (" + initialfinishtrackpause + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialstarttrackpause !== "") {
            if (initialcollectiondate !== "") {
                if (process(initialcollectiondate) > process(initialstarttrackpause)) {
                    message += 'Collection (' + initialcollectiondate + ') needs to be earlier than Pause (' + initialstarttrackpause + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialfinishtrackpause !== "") {
            if (initialShipDate !== "") {
                if (process(initialfinishtrackpause) > process(initialShipDate)) {
                    message += 'Resume (' + initialfinishtrackpause + ') needs to be earlier than Complete (' + initialShipDate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }



        if (initialstarttrackpause === "") {
            if (initialfinishtrackpause !== "") {
                message += 'Resumed but no Paused time<br />';
                oktosubmit = 0;
            }
        }


        if (initialwaitingstarttime !== "") {
            if (initialcollectiondate !== "") {
                if (process(initialcollectiondate) < process(initialwaitingstarttime)) {
                    message += 'En Site at PU (' + initialwaitingstarttime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }



        if (initialwaitingstarttime !== "") {
            if (initialstarttravelcollectiontime !== "") {
                if (process(initialstarttravelcollectiontime) > process(initialwaitingstarttime)) {
                    message += 'En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than On Site (' + initialwaitingstarttime + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialcollectiondate !== "") {
            if (initialstarttravelcollectiontime !== "") {
                if (process(initialstarttravelcollectiontime) > process(initialcollectiondate)) {
                    message += 'En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialwaitingstarttime !== "") {
            if (initialcollectiondate !== "") {               
                var waitingminstest = parseInt(((process(initialcollectiondate) - process(initialwaitingstarttime)) / 1000 / 60), 10);
                if (waitingminstest > waitingtimedelay) { // global test minutes more than than diff so see if waiting mins in cbb
                    if (waitingmins < 4) {

                        message += waitingminstest + 'mins from en site to collection with no waiting time in dropdown <br />';
                        // message+=waitingmins + 'mins waiting time selected in dropdown <br />';
                        oktosubmit = 0;
                    }
                }
            }
        }



        if (initialduedate !== "") {
            if (initialtargetcollectiondate !== "") {
                if (process(initialduedate) < process(initialtargetcollectiondate)) {
                    message += 'Target Collection  (' + initialtargetcollectiondate + ') needs to be earlier than Target Delivery (' + initialduedate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }




        if (haspod === 1) {
            if (podsurname === "") {
                message += 'Needs POD Surname <br />';
                oktosubmit = 0;
            }
        }



        if (initialstatus > 59) {
            if (initialcollectiondate === "") {
                message += 'Needs PU Time <br />';
                oktosubmit = 0;
            }
        }


        if (initialstatus > 85) {
            if (initialShipDate === "") {
                message += 'Needs Delivery Time <br />';
                oktosubmit = 0;
            }
        }

        if (initialstatus > 61) {
            if (initialstarttrackpause !== "") {
                if (initialfinishtrackpause === "") {
                    message += 'Needs Resume Time <br />';
                    oktosubmit = 0;
                }
            }
        }


        // if ( oktosubmit==1 )  { } else { allok=0; }

    } // ends check time function



 


    function editnewcost() {
        var newcost = $("#newcost").val();
        $("#toploader").show();
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxeditcost',
                formbirthday: formbirthday,
                id: id,
                newcost: newcost
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                resizenewcost();
                $("#toploader").fadeOut();
            }
        });
    }


    
    
    
    
    $( "#orderviadiv" ).load( "ajax_lookup.php", { id: id, lookuppage: 'ajaxordervias' }, function() {
        // alert( "Load was performed." );

        $(document).ready(function(){
            
            $(".chngfav").bind("click", function (e) {
                
                var thisviaid = e.target.id;
                            
                e.preventDefault();
                $.Zebra_Dialog(' <select id="selectfavbox" > ' +
                    ' <option value=""> Select One ...</option> ' +
                    ' </select> ' +
                    ' <button name="showallfavo" onclick="return false" title="All Favourites" ' +
                    ' id="showallfavo" class="showallfav" > </button> ',
                    {
                    "type": "question",
                    "title": "Please select new address",
                    "buttons": [{
                        caption: "Select",
                        callback: function () {
                            $("#toploader").show();
                            var whichselected=$("#selectfavbox").val();

                            $.ajax({
                                type: "post",
                                data: { page: "addfavtoorder", 
                                    id: id, 
                                    selectfavbox:whichselected, 
                                    addr:thisviaid, 
                                    formbirthday: formbirthday
                                    },
                                url: "ajaxchangejob.php",
                                success: function (data) {
                                    $('#emissionsaving').append(data);
                                    },
                                complete: function() {
                                    ordermapupdater();
                                    showmessage();
                                    $("#toploader").fadeOut();
                                }
                            });
                        }
                    },{
                        caption: "Cancel"
                    } ]
                });
            
                if (mobdevice==0) { $( "#selectfavbox" ).selectfavbox(); }
            
                var toAppendto="";
                $("#selectfavbox").val("");
                $("#selectfavbox option").remove();
                $.ajax({
                    type: "post",
                    url: "ajax_lookup.php",
                    data: { 
                    lookuppage: "allfavjson",
                    clientid: oldclientorder 
                    },
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function () {
                            toAppendto += "<option value=" + this.oV + ">" + this.oD + "</option>";
                    });
                            return false;
                    },
                    complete: function() {
                        $("#selectfavbox").append(toAppendto);
                        $(document).ready(function(){
                            $("#favbutton").click();
                            $(document).on("click", "#showallfavo",	function (event) {
                                toAppendto = "";
                                event.preventDefault();
                                $("#selectfavbox option").remove();
                                $("#showallfavo").addClass("loader");
                                $.ajax({
                                    type: "post",
                                    data: { clientid: "all", lookuppage: "allfavjson" },
                                    url: "ajax_lookup.php",
                                    dataType: "json",
                                    success: function (data) {
                                        $.each(data, function () {
                                            toAppendto += "<option value=" + this.oV + ">" + this.oD + "</option>";
                                        });
                                    },
                                    complete: function() {
                                        $("#selectfavbox").prepend(toAppendto);
                                        $("#favbutton").click();
                                        $("#showallfavo").fadeOut();
                                    }
                                });
                                
                                return false;
                            });
                        });
                    }
                });
            });
            



            $(".addfield").bind("change", function (e) {
                $("#toploader").show();
                var thisviaid = e.target.id;
                var newvalue = e.target.value;

                $.ajax({
                    type: "post",
                    data: { page: "editorderaddress", 
                        id: id, 
                        newvalue:newvalue, 
                        addr:thisviaid, 
                        formbirthday: formbirthday
                        },
                    url: "ajaxchangejob.php",
                    success: function (data) {
                        $('#emissionsaving').append(data);
                        },
                    complete: function() {
                        ordermapupdater();
                        showmessage();
                        $("#toploader").fadeOut();
                    }
                });                
            });

            

            
            $(".editfav").bind("click", function (e) {
            
                var thisviaid = e.target.id;
                // var whichselected=$("#selectfavbox").val();
                var enrid = parseInt(thisviaid.match(/(\d+)$/)[0], 10);
                
                var pcid = 'enrpc' + enrid;
                var ftidt = 'enrft' + enrid;
                var commentid = 'favcomment' + enrid;
                
                
                var oldfreetext=$('#'+ ftidt).val();
                var oldpostcode=$('#'+ pcid).val();
                var oldcomment=$('#'+ commentid).text();
                // oldcomment=trim(oldcomment);
               
                e.preventDefault();
                var zd = new $.Zebra_Dialog(' <p>Address :</p> ' +
                    ' <input id="newfreetext" type="text" placeholder="Address" class="addfield caps ui-state-default ui-corner-all freetext" value="'+ oldfreetext.trim() + '"> ' +
                    '' +
                    '<input id="newpostcode" type="text" placeholder="Postcode" class="addfield caps ui-state-default ui-corner-all" size="9" value="'+ 
                    oldpostcode.trim() + '"> ' +
                    ' <hr /> <p title="Hidden from Client"> Comments: </p>' +
                    '  <textarea id="editcomment" title="Hidden from Client" class="normal caps ui-state-highlight ui-corner-all orderjobcomments" >' +
                    oldcomment.trim() + '</textarea>' +
                    '<div id="editfavfeedback"></div> ', {
                    "type": "question",
                    "title": "Add / Edit Favourite",
                    "buttons": [{
                        caption: "Add / Edit Favourite",
                        callback: function () {
                            // $('#editcomment').trigger('autosize.resize');
                            // var whichselected=$("#selectfavbox").val();
                            $("#toploader").show();
                            
                            var newfreetext=$('#newfreetext').val();
                            var newpostcode=$('#newpostcode').val();
                            var editcomment=$('#editcomment').val();
                            
                            $.ajax({
                                
                                type: "post",
                                data: { page: "editfav", 
                                    formbirthday: formbirthday,
                                    oldfreetext: oldfreetext,
                                    oldpostcode: oldpostcode,
                                    newfreetext: newfreetext,
                                    newpostcode: newpostcode,
                                    newcomment: editcomment,
                                    client: oldclientorder,
                                    enrid: enrid,
                                    id: id
                                    },
                                url: "ajaxchangejob.php",
                                success: function (data) {
                                    $('.ZebraDialog_Body').html(data);
                                    },
                                complete: function() {
                                    // ordermapupdater();
                                    $("#toploader").fadeOut();
                                }
                            });
                            e.preventDefault();
                            return false;
                        }
                    },{
                        caption: "Cancel"
                    }]
                });
                
                
                $(function () {
                    // alert(" zebra loaded ");
                    $("#editcomment").autosize();
                    
                });
            });
            
            
            $(".addpostcodebutton").bind("click", function (e) {
                e.preventDefault();
                var thisviaid = e.target.id;
                var enrid = parseInt(thisviaid.match(/(\d+)$/)[0], 10);
                var pcid = 'enrpc' + enrid;
                var selectpc = $('#'+ pcid).val();
                var url = 'newpc.php';
                var form = $('<form action="' + url + '" method="post">' +
                '<input type="text" name="selectpc" value="' + selectpc + '" />' +
                '<input type="text" name="id" value="' + id + '" />' +                
                '</form>');
                $('body').append(form);
                form.submit();
            });
        
            $("#togglenr1choose").click(function(){$("#togglenr1").show();$("#togglenr1choose").hide().removeClass("activewheneditable");$("#togglenr2choose").addClass("activewheneditable");});
            $("#togglenr2choose").click(function(){$("#togglenr2").show();$("#togglenr2choose").hide().removeClass("activewheneditable");$("#togglenr3choose").addClass("activewheneditable");});
            $("#togglenr3choose").click(function(){$("#togglenr3").show();$("#togglenr3choose").hide().removeClass("activewheneditable");$("#togglenr4choose").addClass("activewheneditable");});
            $("#togglenr4choose").click(function(){$("#togglenr4").show();$("#togglenr4choose").hide().removeClass("activewheneditable");$("#togglenr5choose").addClass("activewheneditable");});
            $("#togglenr5choose").click(function(){$("#togglenr5").show();$("#togglenr5choose").hide().removeClass("activewheneditable");$("#togglenr6choose").addClass("activewheneditable");});
            $("#togglenr6choose").click(function(){$("#togglenr6").show();$("#togglenr6choose").hide().removeClass("activewheneditable");$("#togglenr7choose").addClass("activewheneditable");});
            $("#togglenr7choose").click(function(){$("#togglenr7").show();$("#togglenr7choose").hide().removeClass("activewheneditable");$("#togglenr8choose").addClass("activewheneditable");});
            $("#togglenr8choose").click(function(){$("#togglenr8").show();$("#togglenr8choose").hide().removeClass("activewheneditable");$("#togglenr9choose").addClass("activewheneditable");});
            $("#togglenr9choose").click(function(){$("#togglenr9").show();$("#togglenr9choose").hide().removeClass("activewheneditable");$("#togglenr10choose").addClass("activewheneditable");});
            $("#togglenr10choose").click(function(){$("#togglenr10").show();$("#togglenr10choose").hide().removeClass("activewheneditable");$("#togglenr11choose").addClass("activewheneditable");});
            $("#togglenr11choose").click(function(){$("#togglenr11").show();$("#togglenr11choose").hide().removeClass("activewheneditable");$("#togglenr12choose").addClass("activewheneditable");});
            $("#togglenr12choose").click(function(){$("#togglenr12").show();$("#togglenr12choose").hide().removeClass("activewheneditable");$("#togglenr13choose").addClass("activewheneditable");});
            $("#togglenr13choose").click(function(){$("#togglenr13").show();$("#togglenr13choose").hide().removeClass("activewheneditable");$("#togglenr14choose").addClass("activewheneditable");});
            $("#togglenr14choose").click(function(){$("#togglenr14").show();$("#togglenr14choose").hide().removeClass("activewheneditable");$("#togglenr15choose").addClass("activewheneditable");});
            $("#togglenr15choose").click(function(){$("#togglenr15").show();$("#togglenr15choose").hide().removeClass("activewheneditable");$("#togglenr16choose").addClass("activewheneditable");});
            $("#togglenr16choose").click(function(){$("#togglenr16").show();$("#togglenr16choose").hide().removeClass("activewheneditable");$("#togglenr17choose").addClass("activewheneditable");});
            $("#togglenr17choose").click(function(){$("#togglenr17").show();$("#togglenr17choose").hide().removeClass("activewheneditable");$("#togglenr18choose").addClass("activewheneditable");});
            $("#togglenr18choose").click(function(){$("#togglenr18").show();$("#togglenr18choose").hide().removeClass("activewheneditable");$("#togglenr19choose").addClass("activewheneditable");});
            $("#togglenr19choose").click(function(){$("#togglenr19").show();$("#togglenr19choose").hide().removeClass("activewheneditable");$("#togglenr20choose").addClass("activewheneditable");});
            $("#togglenr20choose").click(function(){$("#togglenr20").show();$("#togglenr20choose").hide().removeClass("activewheneditable");});
            
            
            
            
            
            
            
            
            
            
            
    
        });
        
    });
    
    
    
    

    $("#newcost").change(function () {
        editnewcost();
    });

    
    


    function checktargetcollectiondate() {
        if (initialstatus < 100) {
            var targetcollectiondate = $("#targetcollectiondate").val().trim();
            if (targetcollectiondate !== initialtargetcollectiondate) {
                $("#toploader").show();
                initialtargetcollectiondate=targetcollectiondate;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxtargetcollectiondate',
                        formbirthday: formbirthday,
                        id: id,
                        targetcollectiondate: targetcollectiondate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        ordermapupdater();
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else { // status too high
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#targetcollectiondate').val(initialtargetcollectiondate);
        }
    }



    function checkwaitingstarttime() {
        if (initialstatus < 100) {
            var waitingstarttime = $("#waitingstarttime").val().trim();
            if (waitingstarttime !== initialwaitingstarttime) {
                initialwaitingstarttime = waitingstarttime;
                $("#toploader").show();
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxwaitingstarttime',
                        formbirthday: formbirthday,
                        id: id,
                        waitingstarttime: waitingstarttime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        $("#toploader").fadeOut();                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#waitingstarttime').val(initialwaitingstarttime);
        }
    }




    function checkcollectiondate() {
        if (initialstatus < 100) {
            var collectiondate = $("#collectiondate").val().trim();
            if (collectiondate !== initialcollectiondate) {
                initialcollectiondate = collectiondate;  // needed to stop firing twice on time change
                $("#toploader").show();
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxcollectiondate',
                        formbirthday: formbirthday,
                        id: id,
                        collectiondate: collectiondate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        ordermapupdater();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#collectiondate').val(initialcollectiondate);
        }
    }




    function checkstarttrackpause() {
        if (initialstatus < 100) {
            var starttrackpause = $("#starttrackpause").val().trim();
            if (starttrackpause !== initialstarttrackpause) {
                $("#toploader").show();
                initialstarttrackpause = starttrackpause;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxstarttrackpause',
                        formbirthday: formbirthday,
                        id: id,
                        starttrackpause: starttrackpause
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        ordermapupdater();
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#starttrackpause').val(initialstarttrackpause);
        }
    }




    function checkfinishtrackpause() {
        if (initialstatus < 100) {
            var finishtrackpause = $("#finishtrackpause").val().trim();
            if (finishtrackpause !== initialfinishtrackpause) {
                $("#toploader").show();
                initialfinishtrackpause = finishtrackpause;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxfinishtrackpause',
                        formbirthday: formbirthday,
                        id: id,
                        finishtrackpause: finishtrackpause
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        ordermapupdater();
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#finishtrackpause').val(initialfinishtrackpause);
        }
    }




    function checkduedate() {
        if (initialstatus < 100) {
            var duedate = $("#duedate").val().trim();
            if (duedate !== initialduedate) {
                $("#toploader").show();
                initialduedate = duedate;
                
                $.ajax({
                    url: "ajaxchangejob.php",
                    data: {
                        page: "ajaxduedate",
                        formbirthday: formbirthday,
                        id: id,
                        duedate: duedate
                    },
                    type: "post",
                    success: function (data) {
                        $("#emissionsaving").append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        ordermapupdater();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#duedate').val(initialduedate);
        }
    }



    function checkdeliveryworkingwindow() {
        if (initialstatus < 100) {
            var deliveryworkingwindow = $("#deliveryworkingwindow").val().trim();
            if (deliveryworkingwindow !== initialdeliveryworkingwindow) {
                $("#toploader").show();
                initialdeliveryworkingwindow = deliveryworkingwindow;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxdeliveryworkingwindow',
                        formbirthday: formbirthday,
                        id: id,
                        deliveryworkingwindow: deliveryworkingwindow
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        $("#toploader").fadeOut();                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#deliveryworkingwindow').val(initialdeliveryworkingwindow);
        }
    }


    function checkShipDate() {
        if (initialstatus < 100) {
            var ShipDate = $("#ShipDate").val().trim();
            if (ShipDate !== initialShipDate) {
                initialShipDate = ShipDate;  // needed to stop firing twice on time change
                $("#toploader").show();
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxShipDate',
                        formbirthday: formbirthday,
                        id: id,
                        ShipDate: ShipDate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        ordermapupdater();
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#ShipDate').val(initialShipDate);
        }
    }



    function checkjobrequestedtime() {
        if (initialstatus < 100) {
            var jobrequestedtime = $("#jobrequestedtime").val().trim();
            if (jobrequestedtime !== initialjobrequestedtime) {
                $("#toploader").show();
                initialjobrequestedtime = jobrequestedtime;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxjobrequestedtime',
                        formbirthday: formbirthday,
                        id: id,
                        jobrequestedtime: jobrequestedtime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        $("#toploader").fadeOut();
                        }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#jobrequestedtime').val(initialjobrequestedtime);
        }
    }



    $(function () {
        $("#targetcollectiondate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checktargetcollectiondate();
            }
        });
    });


    $(function () {
        $('#targetcollectiondate').on('blur', function () {
            checktargetcollectiondate();
        });
    });



    function checkcollectionworkingwindow() {
        if (initialstatus < 100) {
            var collectionworkingwindow = $("#collectionworkingwindow").val().trim();
            if (collectionworkingwindow !== initialcollectionworkingwindow) {
                $("#toploader").show();
                initialcollectionworkingwindow = collectionworkingwindow;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxcollectionworkingwindow',
                        formbirthday: formbirthday,
                        id: id,
                        collectionworkingwindow: collectionworkingwindow
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        $("#toploader").fadeOut();
                        }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#collectionworkingwindow').val(initialcollectionworkingwindow);
        }
    }




    $(function () {
        $("#collectionworkingwindow").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            beforeShow: function () {
                if (initialcollectionworkingwindow === "") {
                    $("#collectionworkingwindow").val(initialtargetcollectiondate);
                }
            },
            onClose: function () {
                checkcollectionworkingwindow();
            }

        });
    });



    function checkstarttravelcollectiontime() {
        if (initialstatus < 100) {
            var starttravelcollectiontime = $("#starttravelcollectiontime").val().trim();
            if (starttravelcollectiontime !== initialstarttravelcollectiontime) {
                $("#toploader").show();
                initialstarttravelcollectiontime = starttravelcollectiontime;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxstarttravelcollectiontime',
                        formbirthday: formbirthday,
                        id: id,
                        starttravelcollectiontime: starttravelcollectiontime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        $("#toploader").fadeOut();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#starttravelcollectiontime').val(initialstarttravelcollectiontime);
        }
    }



    $(function () {
        $("#starttravelcollectiontime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkstarttravelcollectiontime();
            }
        });
    });




    $(function () {
        $("#waitingstarttime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkwaitingstarttime();
            }
        });
    });


    $(function () {  $("#collectiondate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkcollectiondate();
            }
        });
    });


    $(function () {   $("#starttrackpause").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            beforeShow: function () {
                if (initialstarttrackpause === "") {
                    // alert(" delivery ww not set, adding main target collection ");
                    $("#starttrackpause").val(initialcollectiondate);
                }
            },
            onClose: function () {
                checkstarttrackpause();
            }
        });
    });



    $(function () {     $("#finishtrackpause").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            beforeShow: function () {
                if (initialfinishtrackpause === "") {
                    if (initialShipDate !== "") {
                        $("#finishtrackpause").val(initialShipDate);
                    } else if (initialstarttrackpause !== "") {
                        $("#finishtrackpause").val(initialstarttrackpause);
                    } else if (initialcollectiondate !== "") {
                        $("#finishtrackpause").val(initialcollectiondate);
                    }
                }
            },
            changeMonth: false,
            onClose: function () {
                checkfinishtrackpause();
            }
        });
    });



    $(function () {     $("#duedate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkduedate();
            }
        });
    });



    $(function () {     $("#deliveryworkingwindow").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            beforeShow: function () {
                if (initialdeliveryworkingwindow === "") {
                    // alert(" delivery ww not set, adding main target collection ");
                    $("#deliveryworkingwindow").val(initialduedate);
                }
            },
            onClose: function () {
                checkdeliveryworkingwindow();
            },
            changeMonth: false
        });
    });


    $(function () {   $("#ShipDate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            onClose: function () {
                checkShipDate();
            },
            changeMonth: false
        });
    });



    $(function () {    $("#jobrequestedtime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkjobrequestedtime();
            }
        });
    });

    function sendstatus() {
        var newstatus = $("select#newstatus").val();
        $("#toploader").show();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxorderstatus',
                formbirthday: formbirthday,
                newstatus: newstatus,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                ordermapupdater();
                showhidebystatus();
            }
        });
        // message=' Code to send status ';
    }



    function resizenewcost() {
    
//   alert(" about to resize ");
    
    var testsize=$("#newcost").val().length-1;
    
    $("#newcost").attr("style","");
    $("#newcost").attr("size", testsize);
}


    
    

    $("#newrider").change(function () {
        $("#toploader").show();
        var newrider = $("select#newrider").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangerider',
                formbirthday: formbirthday,
                id: id,
                newrider: newrider
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                ordermapupdater();
                showmessage();
            }
        });
    });


    

    $("#newstatus").change(function () {
        message = '';
        testtimes();
        if (oktosubmit === 1) {
            sendstatus();
        } else {
            $("select#newstatus").val(initialstatus);
            allok = 0;
            showmessage();
        }
    });



    $("#podsurname").change(function () {
        $("#toploader").show();
        podsurname = $("#podsurname").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxpodsurname',
                formbirthday: formbirthday,
                id: id,
                podsurname: podsurname
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });




    $(".cbbcheckbox").change(function () {
        $("#toploader").show();
        waitingmins = $("select#waitingmins").val();
        var cbbchecked;
        var cbbname = (this.name);
        if (this.checked) {
            cbbchecked = 1;
        } else {
            cbbchecked = 0;
        }
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxcbb',
                cbbname: cbbname,
                cbbchecked: cbbchecked,
                waitingmins: waitingmins,
                id: id,
                formbirthday: formbirthday
            },
            type: "post",
            success: function (data) {
                $("#emissionsaving").append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });





    $("#opsmaparea").change(function () {
        $("#toploader").show();
        var opsmaparea = $("select#opsmaparea").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeopsmaparea',
                formbirthday: formbirthday,
                id: id,
                opsmaparea: opsmaparea
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                ordermapupdater();
                showmessage();
            }
        });
    });


    
    
    $("#opsmapsubarea").change(function () {
        $("#toploader").show();
        var opsmapsubarea = $("select#opsmapsubarea").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeopsmapsubarea',
                formbirthday: formbirthday,
                id: id,
                opsmapsubarea: opsmapsubarea
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                showmessage();
                processordermap();
                $("#toploader").fadeOut();
            }
        });
    });




    $("#serviceid").change(function () {
        $("#toploader").show();
        var serviceid = $("select#serviceid").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeserviceid',
                formbirthday: formbirthday,
                id: id,
                serviceid: serviceid
            },
            type: 'post',
            success: function (data) {
                $('#ordernumberitemscontainer').append(data);
            },
            complete: function () {
                ordermapupdater();
                showmessage();
                resizenewcost();
            }
        });
    });


    $('#uploadpodfile').change(function () {

        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                $('progress').attr({
                    value: e.loaded,
                    max: e.total
                });
            }
        }



        $("#toploader").show();
        $('progress').attr({
            value: 0,
            max: 1
        });
        $('#uploadpodprogress').show();
        $("#formbirthday").val(formbirthday);


//            var file = this.files[0];
//            var name = file.name;
//            var size = file.size;
//            var type = file.type;
            // more validation

        var formData = new FormData($("#uploadpodform")[0]);
        $.ajax({
            url: "ajaxchangejob.php",
            type: "POST",
            xhr: function () { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //        beforeSend: beforeSendHandler,
            success: function (data) {
                $('#emissionsaving').append(data);

            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            },

            //        error: errorHandler,
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    });



    $("#ajaxremovepod").click(function () {
        $("#toploader").show();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxremovepod',
                formbirthday: formbirthday,
                id: id,
                publicid: publictrackingref
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });


    
    
    function depcomboboxchanged() {
        $("#toploader").show();
        var newdeporder = $("select#orderselectdep").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangedep',
                formbirthday: formbirthday,
                id: id,
                newdeporder: newdeporder,
                initialdeporder: initialdeporder
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                showhidebystatus();
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    }
    
    
    
    

    $("#jobcomments").change(function () {
        $("#toploader").show();
        var jobcomments = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxjobcomments',
                formbirthday: formbirthday,
                id: id,
                jobcomments: jobcomments
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });



    $("#privatejobcomments").change(function () {
        $("#toploader").show();
        var privatejobcomments = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxprivatejobcomments',
                formbirthday: formbirthday,
                id: id,
                privatejobcomments: privatejobcomments
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });




    $("#requestor").change(function () {
        $("#toploader").show();
        var requestor = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxrequestor',
                formbirthday: formbirthday,
                id: id,
                requestor: requestor
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });




    $("#clientjobreference").change(function () {
        $("#toploader").show();
        var clientjobreference = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxclientjobreference',
                formbirthday: formbirthday,
                id: id,
                clientjobreference: clientjobreference
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });

    $("#distance").change(function () {
        $("#toploader").show();
        var distance = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxmanualeditdistance',
                formbirthday: formbirthday,
                id: id,
                distance: distance
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    });

    
    


    $("#numberitems").change(function () {
        $("#toploader").show();
        var numberitems = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxnumberitems',
                formbirthday: formbirthday,
                id: id,
                numberitems: numberitems
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                resizenewcost();
                $("#toploader").fadeOut();
            }
        });
    });


    $(function () {
        $("#starttravelcollectiontime").on("blur", function () {
            checkstarttravelcollectiontime();
        });
    });

    $(function () {
        $("#waitingstarttime").on("blur", function () {
            checkwaitingstarttime();
        });
    });

    $(function () {
        $("#collectiondate").on("blur", function () {
            checkcollectiondate();
        });
    });

    $(function () {
        $("#starttrackpause").on("blur", function () {
            checkstarttrackpause();
        });
    });

    $(function () {
        $("#finishtrackpause").on("blur", function () {
            checkfinishtrackpause();
        });
    });

    $(function () {
        $("#duedate").on("blur", function () {
            checkduedate();
        });
    });

    $(function () {
        $("#deliveryworkingwindow").on("blur", function () {
            checkdeliveryworkingwindow();
        });
    });

    $(function () {
        $("#ShipDate").on("blur", function () {
            checkShipDate();
        });
    });

    $(function () {
        $("#jobrequestedtime").on("blur", function () {
            checkjobrequestedtime();
        });
    });



    $("#allowww").click(function () {
        $("#collectionworkingwindow").slideToggle("fast");
    });

    $("#allowdww").click(function () {
        $("#deliveryworkingwindow").slideToggle("fast");
    });

    $("#toggleresumechoose").click(function () {
        $("#toggleresume").slideToggle("fast");
        $("#toggleresumechoose").hide();
    });


    $(function () {
        $(".normal").autosize();

    });

    $(function () {
        $("#toggle").click(function () {
            $("#orderselectdep").toggle();
        });
    });

    $(function () {
        $("#combobox").combobox();
        $("#toggle").click(function () {
            $("#combobox").toggle();
        });
    });



    $('#orderaudit').bind('click', function (e) {
        e.preventDefault();
        $.Zebra_Dialog('', {
            'source': {
                'ajax': ('ajax_lookup.php?orderid=' + id + '&auditpage=order&lookuppage=cojmaudit')
            },
            'type': 'false',
            'width': '90%',
            'custom_class': 'orderaudit',
            'position': ['left+30','top'],
            'scrolling': 'yes',
            'title': ('Audit log for ' + id),
            'buttons': [
                {caption: 'Full Audit Log', callback: function () {
                    // empty callback does nothing
                    window.open('cojmaudit.php?orderid='+ id,"_self");
                }},
                {caption: 'Close', callback: function () {
                    // $("#frmdel").submit();
                }}

            ]
        });
    });






// collectionworkingwindow
    $(function () {
        $('#collectionworkingwindow').on('blur', function () {
            checkcollectionworkingwindow();
        });
    });




    function cancelpricelock() {
        $("#toploader").show();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxcancelpricelock',
                formbirthday: formbirthday,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                resizenewcost();
                showmessage();
                $("#toploader").fadeOut();
            }
        });
    }


    $("#buttoncancelpricelock").click(function () {
        cancelpricelock();
    });


    $('#deleteord').bind('click', function (e) {
        
        e.preventDefault();
        $.Zebra_Dialog('<strong>Are you sure ?</strong><br />This Job will be deleted.<br />This action CANNOT be undone.', {
            'type': 'warning',
            'title': 'Delete Job ?',
            'buttons': [
                {caption: 'Delete', callback: function () {
                    $("#frmdel").submit();
                }},
                {caption: 'Do NOT Delete', callback: function () {
                    // empty callback does nothing
                }}
            ]
        });
    });





    

}); // ends document ready



function comboboxchanged() {
    "use strict";
    $("#toploader").show();
    var newclientorder = $("select#combobox").val();
    $.ajax({
        url: 'ajaxchangejob.php',
        data: {
            page: 'ajaxchangeclient',
            formbirthday: formbirthday,
            id: id,
            newclientorder: newclientorder,
            oldclientorder: oldclientorder
        },
        type: 'post',
        success: function (data) {
            $('#client').append(data);
        },
        complete: function () {
            showmessage();
            $("#toploader").fadeOut();
        }
    });
}
