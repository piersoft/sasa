<?php
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
