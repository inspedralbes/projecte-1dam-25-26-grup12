<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://root:example@mongo:27017/?authSource=admin");

$collection = $client->demo->users;

// Obtenim l'adreça IP origen de la petció.
// Teniu informació sobre l'operador ?? a 
// https://phpsensei.es/operadores-en-php-null-coalesce-operator/
// "Si no es pot obtenir, es fa servir 'unknown' com a valor per defecte"

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$hora = date("H:i:s");

$collection->insertOne([
    'name' => 'Anna',
    'age' => 28,
    'ip_origin' => $ip,
    'date' => $hora
]);
echo "Dades inserides a demo .\n";


// Obtenir tots els documents de la col·lecció users de la BBDD demo
// $collection = $client->demo->users; #no cal, ja que ho hem fet abans
$documents = $collection->find();

foreach ($documents as $document) {
    echo "<p>";
    echo htmlspecialchars($document['date'] ?? "x");
    echo " ( " . htmlspecialchars($document['ip_origin'] ?? "x") . " )";
    echo " : " . htmlspecialchars($document['name']);
    echo "</p>";

}