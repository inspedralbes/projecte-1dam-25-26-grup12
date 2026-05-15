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
        '$sort' => ['_id' => -1]
    ],
    [
        // Mostrem només les 10 primeres pagines.
        '$limit' => 5
    ]

]);

$ultim_acces = $collection->aggregate([

    //Ordenem del access mes recent al mes antic
    [
        '$sort' => ['date' => -1]
    ],
    //posem un limit perque sol surti el ultim i un access
    [
        '$limit' => 1
    ],
    // i a la etapa de project mostrem sol la hora amb la funcio substr
    [
        '$project' => [
            'hora' => ['$substr' => ['$date', 11, 19]]
        ]
    ]
]);

foreach ($ultim_acces as $fila) {
    $hora = $fila['hora'];
}

//Variables php que s'afegeix al formulari i com a default no s'envia res.

$data = $_GET['data'] ?? '';
$pagina = $_GET['pagina'] ?? '';
$user = $_GET['user'] ?? '';

//Comencem a un pipeline buit
$pipeline = [];

//Afegim els camps: 'dia' sol amb la data, no el temps i uri s'agafa com es afegeix.
$pipeline[] = [
    '$project' => [
        'dia' => ['$substr' => ['$date', 0, 10]],
        'uri' => 1,
        'user' => 1
    ]
];
// Creem un array buit que equival al filtre
$filtre = [];

// Si la varibale php data no esta buida, afegim al filtre la condició que el camp "dia" de MongoDB ha de ser igual al valor de $data.
if (!empty($data)) {
    $filtre['dia'] = $data;
}

// Si la variable php $pagina no està buida, afegim al filtre la condició que el camp "uri" de MongoDB ha de ser igual al valor de $pagina.
if (!empty($pagina)) {
    $filtre['uri'] = $pagina;
}

if (!empty($user)) {
    $filtre['user'] = $user;
}

// Si el filtre té alguna condició, amb l'etapa $match li diem a MongoDB que només busqui els documents que coincideixin.
if (!empty($filtre)) {
    $pipeline[] = ['$match' => $filtre];
}

// Afegim l'etapa $count al pipeline perquè MongoDB compti quants documents han passat els filtres i guardi el resultat amb el nom "total".
$pipeline[] = ['$count' => 'total'];

// Executem tot el pipeline a MongoDB i guardem tot a $resultat.
$resultat = $collection->aggregate($pipeline);

// Amb el foreach mirem la variable $resultat i agafem el número i posem en una varibale php mostrar-lo a HTML.
$total = 0;
foreach ($resultat as $fila) {
    $total = $fila['total'];
}

// Pipeline per trobar els logs filtrats
$pipeline_logs = [];

// Afegim el camp 'dia' igual que abans
$pipeline_logs[] = [
    '$project' => [
        'dia' => ['$substr' => ['$date', 0, 10]],
        'uri' => 1,
        'user' => 1,
        'date' => 1,
        'metodo' => 1,
        'ip_origin' => 1
    ]
];

//Si el filtre té alguna condició, amb l'etapa $match li diem a MongoDB que només busqui els documents que coincideixin.
if (!empty($filtre)) {
    $pipeline_logs[] = ['$match' => $filtre];
}

// Ordenem del més recent al més antic
$pipeline_logs[] = ['$sort' => ['date' => -1]];

// Limitem a 10 resultats
$pipeline_logs[] = ['$limit' => 10];

$logs_filtrats = $collection->aggregate($pipeline_logs);




$ultims_logs = $collection->aggregate([
    [
        // Ordenem del més recent al més antic
        '$sort' => ['date' => -1]
    ],
    [
        // Agafem només els 10 primers
        '$limit' => 10
    ]
]);


$total_usuaris = $collection->countDocuments();
$usuaris = $collection->aggregate([

    [
        '$group' => [
            
            '_id' => '$user',

            //El contador que suma 1 cada vegada.
            'total' => ['$sum' => 1]
        ]
    ],

    [
        //Ordenar de més a menys acessos.
        '$sort' => ['total' => -1]
    ],
    [
        // Mostrem només les 10 primeres pagines.
        '$limit' => 5
    ]

]);
