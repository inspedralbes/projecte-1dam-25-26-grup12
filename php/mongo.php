<?php
require_once __DIR__ . '/vendor/autoload.php';

try {
    
    $client = new MongoDB\Client("mongodb://a25adrurbrub_db_user:DpOUDFTWJpYzCyNc@ac-f4c77ru-shard-00-00.uok03ie.mongodb.net:27017,ac-f4c77ru-shard-00-01.uok03ie.mongodb.net:27017,ac-f4c77ru-shard-00-02.uok03ie.mongodb.net:27017/?ssl=true&replicaSet=atlas-fzoqka-shard-0&authSource=admin&appName=Cluster0");

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
        'ip_origin' => $ip,
        'date' => $hora,
        'metodo' => $metod,
        'uri' => $file,
        'rtime' => $r_time,
        'nav' => $user_agent

    ]);


    // Obtenir tots els documents de la col·lecció users de la BBDD demo
    // $collection = $client->demo->users; #no cal, ja que ho hem fet abans
    $documents = $collection->find();


} catch (Exception $e) {
    echo 'Excepción recibida: ',  $e->getMessage(), "\n";
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

