<?php

$pdo = new PDO('pgsql:user=foss4g host=localhost dbname=foss4g password=foss4g');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$bbox = '121.48681640624999,25.045792240303445,121.5087890625,25.065697185535853';
$bbox = $_GET['BBOX'];
list($x_min, $y_min, $x_max, $y_max) = explode(',', $bbox);
$pixel_delta = min($x_max - $x_min, $y_max - $y_min) / 400;

$stmt = $pdo->prepare("SELECT properties, ST_AsGeoJSON(ST_Simplify(geometry, $pixel_delta)) FROM village WHERE geometry && ST_MakeEnvelope({$x_min}, {$y_min}, {$x_max}, {$y_max})");
$stmt->execute();

$collection = new StdClass;
$collection->type = 'FeatureCollection';
$collection->features = array();
while ($row = $stmt->fetch()) {
    $feature = new StdClass;
    $feature->type = 'Feature';
    $feature->geometry = json_decode($row[1]);
    $feature->properties = new StdClass;
    $feature->properties->polygon_background_color = false;
    $feature->properties->polygon_border_color = array(0,0,0);
    $feature->properties->polygon_border_size = 2;

    $collection->features[] = $feature;
}
include(__DIR__ . '/GeoJSON2Image.php');
$ret = new GeoJSON2Image($collection);
$ret->setSize(400, 400);
$ret->setBoundry(array($x_min, $x_max, $y_min, $y_max));
$ret->draw();
