<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://root:example@mongo:27017/?authSource=admin");

$collection = $client->demo->users;

// Obtenim l'adreça IP origen de la petció.
// Teniu informació sobre l'operador ?? a 
// https://phpsensei.es/operadores-en-php-null-coalesce-operator/
// "Si no es pot obtenir, es fa servir 'unknown' com a valor per defecte"

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$hora = date("Y-m-d H:i:s");
$metod = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
$file = $_SERVER['PHP_SELF'] ?? 'unknown';
$user = 'null';
$r_time = $_SERVER['REQUEST_TIME_FLOAT'] ?? 'unknown';
$user_agent = obtenerNavegador($_SERVER['HTTP_USER_AGENT']) ?? 'unknown';


$collection->insertOne([
    'user' => $user,
    'age' => 28,
    'ip_origin' => $ip,
    'date' => $hora,
    'metodo' => $metod,
    'uri' => $file,
    'rtime' => $r_time,
    'nav' => $user_agent

]);
echo "Dades inserides a demo .\n";


// Obtenir tots els documents de la col·lecció users de la BBDD demo
// $collection = $client->demo->users; #no cal, ja que ho hem fet abans
$documents = $collection->find();

foreach ($documents as $document) {
    echo "<p>";
    echo htmlspecialchars($document['date'] ?? "x");
    echo " ( " . htmlspecialchars($document['ip_origin'] ?? "x") . " )";
    echo " : " . htmlspecialchars($document['user']);
    echo " : " . $document['metodo'];
    echo " : " . $document['uri'];
    echo " : " . $document['rtime'];
    echo " : " . $document['nav'];
    echo "</p>";

}

function obtenerNavegador($user_agent) {
    if (strpos($user_agent, 'MSIE') !== FALSE || strpos($user_agent, 'Trident') !== FALSE) return 'Internet Explorer';
    if (strpos($user_agent, 'Edge') !== FALSE) return 'Edge';
    if (strpos($user_agent, 'Chrome') !== FALSE) return 'Chrome';
    if (strpos($user_agent, 'Firefox') !== FALSE) return 'Firefox';
    if (strpos($user_agent, 'Safari') !== FALSE) return 'Safari';
    if (strpos($user_agent, 'Opera') !== FALSE || strpos($user_agent, 'OPR') !== FALSE) return 'Opera';
    
    return 'Desconocido';
}

