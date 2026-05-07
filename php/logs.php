<?php

require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://root:example@mongo:27017/?authSource=admin");

$collection = $client->demo->users;

// Hem comptat tots els documents de la col·lecció. Cada document es igual a un accés registrat.
$accessos = $collection->countDocuments();


// Agrupem els accessos per URL (uri) i comptem quantes vegades apareix cada pàgina.
$pagines = $collection->aggregate([

    [
        '$group' => [
            // Agrupem pels valors del camp uri
            '_id' => '$uri',

            // Suma 1 per cada document del grup
            'total' => ['$sum' => 1]
        ]
    ],

    [
        // Ordenem de més visitades a menys visitades
        '$sort' => ['total' => -1]
    ],
    [
        // Mostrem només les 10 primeres pagines
        '$limit' => 10
    ]
]);

?>

<h1>Estadístiques d'Accès</h1>
<h2>Total d'accessos</h2>

<p>
    <?= $accessos ?>
</p>




