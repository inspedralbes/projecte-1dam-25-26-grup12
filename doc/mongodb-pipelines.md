<?php

// Hem comptat tots els documents de la col·lecció perquè cada document es igual a un accés.
$accessos_total = $collection->countDocuments();


// Agrupem els accessos per URI i comptem quantes vegades apareix cada pàgina.
$pagines = $collection->aggregate([

    [
        '$group' => [
            // Agrupem pel camp uri
            '_id' => '$uri',
            // Amb la funcio de suma fem un contador que suma 1 per cada document.
            'total' => ['$sum' => 1]
        ]
    ],

    [
        // Ordenem de més visitades a menys visitades.
        '$sort' => ['total' => -1]
    ],
    [
        // Mostrem només les 10 primeres pagines.
        '$limit' => 10
    ]
]);


$accessos_dia = $collection->aggregate([

    [
        '$group' => [
            //Com que la varibale date té el format Y-m-d H:i:s, amb la funcio substr sol agafem la data i no les temps.
            '_id' => ['$substr' => ['$date', 0, 10]],

            //El contador que suma 1 cada vegada.
            'total' => ['$sum' => 1]
        ]
    ],

    [
        //Ordenar de més a menys recents.
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
if (!empty($data)){
    $filtre['dia'] = $data;
}

if (!empty($pagina)) {
    $filtre['uri'] = $pagina;
}

if (!empty($filtre)) {
    $pipeline[] = ['$match' => $filtre];
}

$pipeline[] = ['$count' => 'total'];

$resultat = $collection->aggregate($pipeline);

$total = 0;
foreach ($resultat as $fila) {
    $total = $fila['total'];
}
