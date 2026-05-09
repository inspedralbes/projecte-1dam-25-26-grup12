<?php

require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://root:example@mongo:27017/?authSource=admin");

$collection = $client->demo->users;

$accessos_total = $collection->countDocuments();

$pagines = $collection->aggregate([
    [
        '$group' => [
            '_id' => '$uri',
            'total' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => ['total' => -1]
    ],
    [
        '$limit' => 10
    ]
]);

$accessos_dia = $collection->aggregate([
    [
        '$group' => [
            '_id' => ['$substr' => ['$date', 0, 10]],
            'total' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => ['_id' => 1]
    ]
]);

$data = $_GET['data'] ?? '';
$pagina = $_GET['pagina'] ?? '';

$pipeline = [];

$pipeline[] = [
    '$project' => [
        'dia' => ['$substr' => ['$date', 0, 10]],
        'uri' => 1
    ]
];

$filtre = [];
if (!empty($data))   { 
    $filtre['dia'] = $data; 
}
if (!empty($pagina)) { 
    $filtre['uri'] = $pagina; 
}
if (!empty($filtre)) {
    $pipeline[] = [
        '$match' => $filtre
    ]; 
}

$pipeline[] = [
    '$group' => [
        '_id' => ['dia' => '$dia','pagina' => '$uri'],
        'accessos' => ['$sum' => 1]
    ]
];

$pipeline[] = [
    '$sort' => ['_id.dia' => -1]
];

$resultat = $collection->aggregate($pipeline);

?>

<h1>Estadístiques d'Accès</h1>

<h2>Total d'accessos:</h2>
<?= $accessos_total ?>

<h2>Pàgines més visitades</h2>

<table>
    <tr>
        <th>Pàgina</th>
        <th>Total visites</th>
    </tr>

    <?php foreach ($pagines as $enllaç): ?>
        <tr>
            <td><?= $enllaç['_id'] ?></td>
            <td><?= $enllaç['total'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Accessos agrupats per dia</h2>

<table>
    <tr>
        <th>Dia</th>
        <th>Total visites</th>
    </tr>

    <?php foreach ($accessos_dia as $dia): ?>
        <tr>
            <td><?= $dia['_id'] ?></td>
            <td><?= $dia['total'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
