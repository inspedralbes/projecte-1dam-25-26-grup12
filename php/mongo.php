<?php

require  '/../vendor/autoload.php';


$uri = getenv('MONGODB_URI') ?: throw new RuntimeException(
    'Set the MONGODB_URI environment variable to your Atlas URI',
);
$client = new MongoDB\Client($uri);
$collection = $client->Logs->dades;

$restaurants = [
    ['name' => 'Mongo\'s Burgers'],
    ['name' => 'Mongo\'s Pizza'],
];
$result = $collection->insertMany($restaurants);




?>