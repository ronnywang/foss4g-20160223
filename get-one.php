<?php

$pdo = new PDO('pgsql:user=foss4g host=localhost dbname=foss4g password=foss4g');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare("SELECT properties, ST_AsGeoJSON(geometry) FROM village LIMIT 1");
$stmt->execute();
while ($row = $stmt->fetch()) {
    echo "{\"type\":\"Feature\",\"properties\":{$row[0]},\"geometry\":{$row[1]}}";
}

