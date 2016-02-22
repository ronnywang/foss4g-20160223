<?php

$pdo = new PDO('pgsql:user=foss4g host=localhost dbname=foss4g password=foss4g');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bbox = '121.48681640624999,25.045792240303445,121.5087890625,25.065697185535853';
list($x_min, $y_min, $x_max, $y_max) = explode(',', $bbox);
$stmt = $pdo->prepare("SELECT properties, ST_AsGeoJSON(geometry) FROM village WHERE geometry && ST_MakeEnvelope({$x_min}, {$y_min}, {$x_max}, {$y_max})");
$stmt->execute();

$collection = new StdClass;
$collection->type = 'FeatureCollection';
$collection->features = array();
while ($row = $stmt->fetch()) {
    $collection->features[] = array(
        'type' => 'Feature',
        'properties' => json_decode($row[0]),
        'geometry' => json_decode($row[1]),
    );
}
echo json_encode($collection);
