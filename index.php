<? php

$url = 'http://realtime.opensasa.info/positions';
$filetmp = "mappatmp.geojson";
$file = "mappa.geojson";
$src = fopen($url, 'r');
$dest = fopen($filetmp, 'w');
stream_copy_to_stream($src, $dest);

$search="\"features\"";
$replace="\"crs\": { \"type\": \"name\", \"properties\": { \"name\": \"urn:ogc:def:crs:EPSG:32632\" } },\"features\"";
$output = passthru("sed -e 's/$search/$replace/g' $filetmp > $file");


?>
<!DOCTYPE html>
<html lang="it">
  <head>
  <title>Trasporti Merano by Sasa</title>
  <link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
  <link rel="stylesheet" href="dist/MarkerCluster.css" />
  <link rel="stylesheet" href="dist/MarkerCluster.Default.css" />
  <meta property="og:image" content="http://www.piersoft.it/sasa/images/bus_.png"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
  <script src="lib/proj4-compressed.js"></script>
  <script src="src/proj4leaflet.js"></script>
  <script src="dist/leafletdin.js"></script>
  <script src="dist/leaflet.markercluster.js"></script>
  <script type="text/javascript">

function microAjax(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4 ){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else { if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest");C.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};
function microAjax1(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4 ){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else { if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest");C.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};

</script>
  <style>
  #mapdiv{
        position:fixed;
        top:0;
        right:0;
        left:0;
        bottom:0;
}
#infodiv{
background-color: rgba(255, 255, 255, 0.95);

font-family: Helvetica, Arial, Sans-Serif;
padding: 2px;


font-size: 10px;
bottom: 13px;
left:0px;


max-height: 50px;

position: fixed;

overflow-y: auto;
overflow-x: hidden;
}
#loader {
    position:absolute; top:0; bottom:0; width:100%;
    background:rgba(255, 255, 255, 1);
    transition:background 1s ease-out;
    -webkit-transition:background 1s ease-out;
}
#loader.done {
    background:rgba(255, 255, 255, 0);
}
#loader.hide {
    display:none;
}
#loader .message {
    position:absolute;
    left:50%;
    top:50%;
}
</style>
  </head>

<body>

  <div data-tap-disabled="true">

  <div id="mapdiv"></div>
<div id="infodiv" style="leaflet-popup-content-wrapper">
  <p><b>Posizioni Realtime Trasporti Merano<br></b>
  Mappa dei Trasporti Pubblici di Bolzano - Merano by @piersoft.  </br>Fonte dati Lic. CC-BY-SA <a href="http://sasabus.org/opendata">SASA</a> Icon: Mapicons</p>
</div>
<div id='loader'><span class='message'><img src="http://www.piersoft.it/sasa/images/ajax-loader.gif"/></span></div>
</div>
  <script type="text/javascript">
		var lat=46.5711,
        lon=11.3386,
        zoom2=11;

    var osm = new L.TileLayer('http://{s}.tile.thunderforest.com/transport/{z}/{x}/{y}.png', {minZoom: 0, maxZoom: 20, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		var mapquest = new L.TileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {subdomains: '1234', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});


        var map = new L.Map('mapdiv',{
            editInOSMControl: true,
            editInOSMControlOptions: {
            position: "topright",
            },

            center: new L.LatLng(lat, lon),
            zoom: zoom2,
            layers: [osm]
        });


        var baseMaps = {
    "Mapnik": osm,
    "Mapquest Open": mapquest
        };
        L.control.layers(baseMaps).addTo(map);
       var icostops=L.icon({iconUrl:'images/iconstop.png', iconSize:[10,10],iconAnchor:[5,0]});
       var ico=L.icon({iconUrl:'images/bus.png', iconSize:[32,37],iconAnchor:[16,0]});
       var markers = L.markerClusterGroup({spiderfyOnMaxZoom: false, showCoverageOnHover: true,zoomToBoundsOnClick: true});
       var markers1 = L.markerClusterGroup({spiderfyOnMaxZoom: false, showCoverageOnHover: true,zoomToBoundsOnClick: true});
       var markersf = new L.FeatureGroup();
       var iss
            , timeElapsed = 0;
var marker;
var myLayer;
proj4.defs('urn:ogc:def:crs:EPSG:32632', '+proj=utm +zone=32 +datum=WGS84 +units=m +no_defs');

        function loadLayer(url)
        {
          proj4.defs('urn:ogc:def:crs:EPSG:32632', '+proj=utm +zone=32 +datum=WGS84 +units=m +no_defs');

               myLayer = L.Proj.geoJson(url,{

                        onEachFeature:function onEachFeature(feature, layer) {
                                if (feature.properties && feature.properties.id) {
  }

                        },
                        pointToLayer: function (feature, latlng) {

                         marker = new L.Marker(latlng, { icon: ico });


                        marker.bindPopup('<div style="width: 100%; height: 100%;">Linea: '+feature.properties.li_nr+'</br>Ritardo: '+feature.properties.delay_sec+' sec</br>Direzione: '+feature.properties.ort_name+'</br>Tratta: '+feature.properties.ort_ref_ort_name+'</div>',{maxWidth:300, autoPan:true});

                        return marker;
                        }
                })
                .addTo(map);

                markers.addLayer(myLayer);
              //  map.addLayer(markersf);
              //  markers.on('click',showMarker);
        }

        function loadLayerstops(url)
        {

                var myLayer1 = L.geoJson(url,{
                        onEachFeature:function onEachFeature(feature, layer) {
                                if (feature.properties && feature.properties.id) {
                                }

                        },
                        pointToLayer: function (feature, latlng) {
                        var marker1 = new L.Marker(latlng, { icon: icostops });

                        var streetaddress = feature.properties.Name.split(':')[0];
                        markers1[feature.properties.id] = marker1;
                    //  marker1.bindPopup('<div style="width: 100%; height: 100%;">Nome: '+feature.properties.Name+'</br>Linee: '+feature.properties.description+'</div>',{maxWidth:300, autoPan:true});
                    marker1.bindPopup('<img src="images/ajax-loader.gif">',{maxWidth:50, autoPan:true});


                        return marker1;
                        }
                })
                .addTo(map).on('click', popuporari );


        }
function updatejson(){
  var oReq = new XMLHttpRequest(); //New request object
     oReq.onload = function() {

     };
     oReq.open("get", "update.php", true);
     oReq.send();

}

function update_position() {

microAjax('mappa.geojson',function (res) {
  elapsedTime = new Date().getMilliseconds();

var feat=JSON.parse(res);

loadLayer(feat);
elapsedTime = new Date().getMilliseconds() - elapsedTime;

  finishedLoading();
  myLayer.bringToFront();
  updatejson();
  setTimeout(removeAllMarkers, 5000 - elapsedTime);

} );
}
//update_position();
function removeAllMarkers(){

    map.removeLayer(myLayer);
    update_position();


}

function bus_stop() {

microAjax1('sasa_ge_busdata.geojson',function (res1) {

var feat1=JSON.parse(res1);
loadLayerstops(feat1);

finishedLoading();
update_position();

} );
}
bus_stop();

function popuporari(marker){
  var streetaddress = marker.layer.feature.properties.Name.split(':')[0];
  $.ajax({
    url: 'http://stationboard.opensasa.info/?ORT_NR='+streetaddress+'&type=jsonp&callback=?',
    async:false,
    type: "GET",
    jsonpCallback: 'jsonCallback',
   contentType: "application/json",
   dataType: 'jsonp',
    success: function (json) {
      // JSON.parse(json);
        console.log(json.rides[0]);
       console.log("ok");
       var text;
       var i = 0;
       if(json.rides == "undefined"){
         text ="Nessun arrivo previsto";
         marker.layer.closePopup();
         marker.layer.bindPopup(text);
         marker.layer.openPopup();
       }
   if(json.rides.length == "0"){
       text ="Nessun arrivo previsto";
       marker.layer.closePopup();
       marker.layer.bindPopup(text);
       marker.layer.openPopup();
    //   console.log("Feat lenght non definita");
     }else{

       text ="Prossimi arrivi: <b>";
    // console.log("Feat lenght: "+json.rides.length);
    $arrivi=json.rides.length;
    if ($arrivi >5) $arrivi=5;
     for (i=0;i<$arrivi;i++){

         var last=json.rides[i];
         text +="<br />"+last['lidname'];
         var orario =last['arrival'];
         text+=" Arrivo: "+orario;
         marker.layer.closePopup();
         marker.layer.bindPopup(text);
         marker.layer.openPopup();
       }
     }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      //  console.log(xhr);
      console.log("errore");
    }
  });
}


function startLoading() {
    loader.className = '';
}

function finishedLoading() {

    loader.className = 'done';
    setTimeout(function() {

        loader.className = 'hide';
    }, 500);
}
</script>
</body>
</html>
