<?php

require __DIR__ . '/./vendor/autoload.php';


$uri = "mongodb://root:example@mongo:27017/";
$client = new MongoDB\Client($uri);
$collection = $client->Logs->dades;

$restaurants = [
    ['name' => 'Mongo\'s Burgers'],
    ['name' => 'Mongo\'s Pizza'],
];
$result = $collection->insertMany($restaurants);



?>