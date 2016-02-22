<?php

$pdo = new PDO('pgsql:user=foss4g host=localhost dbname=foss4g password=foss4g');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$pdo->exec("DROP TABLE village");
$pdo->exec("CREATE TABLE village (properties JSON, geometry GEOMETRY)");
$pdo->exec("CREATE INDEX village_geometry ON village USING GIST(geometry)");

// XXX 如果記憶體夠...
//$obj = json_decode(file_get_contents($_SERVER['argv'][1]));
//foreach ($obj->features as $feature) {

// XXX 如果記憶體不夠...
$fp = fopen($_SERVER['argv'][1], 'r');
while (false !== ($line = fgets($fp))) {
    if (strpos($line, '{ "type": "Feature"') === false) {
        continue;
    }
    $feature = json_decode(rtrim(trim($line), ','));
// XXX 分歧結束

    $pdo->exec(sprintf("INSERT INTO village (properties, geometry) VALUES (%s, ST_GeomFromGeoJSON(%s))", 
        $pdo->quote(json_encode($feature->properties)),
        $pdo->quote(json_encode($feature->geometry))
    ));
}
