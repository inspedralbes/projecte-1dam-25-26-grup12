<?php

session_start(); // Iniciem la sessió 

// Si no hi ha email a la sessió, vol dir que no ha fet login i es va a index.php
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
//Sii el rol no és admin, no té permisos per accedir a aquesta pàgina, redirigim a index.php
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



// Carreguem l'autoloader de Composer i el header
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
include_once 'header.php';

// Carreguem les variables d'entorn del fitxer .env i connectem a MongoDB
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

    $uri = $_ENV['MONGODB_URI'];

    $client = new MongoDB\Client($uri);

    $collection = $client->demo->users;


// Comptem el total de documents (accessos) que hi ha a la col·lecció
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

// Si la varibale php $data no esta buida, afegim al filtre la condició que el camp "dia" de MongoDB ha de ser igual al valor de $data.
if (!empty($data)) {
    $filtre['dia'] = $data;
}

// Si la variable php $pagina no està buida, afegim al filtre la condició que el camp "uri" de MongoDB ha de ser igual al valor de $pagina.
if (!empty($pagina)) {
    $filtre['uri'] = $pagina;
}

// Si la variable php $user no està buida, afegim al filtre la condició que el camp "user" de MongoDB ha de ser igual al valor de $user.
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

?>
    
<style>
    
    .main-card {
        background-color: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-top: 20px;
    }
    .text-total { font-size: 2rem; font-weight: bold; color: #212529; }
    h2 { font-size: 1.2rem; margin-top: 25px; margin-bottom: 15px; font-weight: bold; }
    table { margin-bottom: 0 !important; }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="main-card">
                <h1 class="text-center mb-4">Estadístiques d'Accés</h1>

            <!-- total d'accessos i hora de l'última visita -->
            <div class="row g-4 mb-2">
                <div class="col-6 col-md-6">
                    <div class="card text-center h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <p class="small mb-1">Total d'accessos</p>
                            <div class="text-total" style="color:#2e8754;"><?= $accessos_total ?></div>
                        </div>
                    </div>
                </div> 

                <div class="col-6 col-md-6">
                    <div class="card text-center h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <p class="small mb-1">Última visita</p>
                            <div class="text-total" style="color:#2e8754;"><?= $hora ?></div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h2 class="card-title ">Pàgines més visitades</h2><br>
                            <div class="mb-2">
                                <!-- Barres de progrés proporcionals al percentatge de visites de cada pàgina -->
                                <?php foreach ($pagines as $enllaç): 
                                    if ($accessos_total > 0) {
                                        // Calculem el percentatge de visites respecte al total
                                        $percentatge = ($enllaç['total'] / $accessos_total) * 100;
                                    } else {
                                        $percentatge = 0;
                                    }
                                ?>
                                <div class="d-flex justify-content-between small mb-1 mt-3">
                                    <span class="text-truncate me-2" style="max-width:200px" title="/pagina.php">
                                    <?= $enllaç['_id'] ?>
                                    </span>
                                    <span class="fw-bold">
                                    <?= $enllaç['total'] ?>
                                    </span>
                                </div>
                                <div class="progress mb-4" style="height:10px">
                                    <div class="progress-bar bg-success" style="width: <?= $percentatge ?>%"></div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-40">
                            <div class="card-body">
                                <h2 class="card-title">Accessos per dia</h2><br>
                                <!-- Taula amb el nombre d'accessos per dia -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="color:#2e8754; ">Dia</th>
                                            <th style="color:#2e8754;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accessos_dia as $dia): ?>
                                            <tr>
                                                <td><?= $dia['_id'] ?></td>
                                                <td class="fw-bold"><?= $dia['total'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            
                            <div class="card-body">
                                <!-- Barres de progrés dels usuaris més actius -->
                                <h2 class="card-title ">Usuari més actius</h2><br>
                            <div class="mb-2">
                                <?php foreach ($usuaris as $usu): 
                                // Calculem el percentatge d'accessos de cada usuari respecte al total
                                    if ($total_usuaris > 0) {
                                        $percent = ($usu['total'] / $total_usuaris) * 100;
                                    } else {
                                        $percentatge = 0;
                                    }
                                    
                                ?>
                                <!-- Taula amb els 10 últims accessos registrats sense cap filtre -->
                                <div class="d-flex justify-content-between small mb-1 mt-3">
                                    <span class="text-truncate me-2" style="max-width:200px" title="usuari">
                                    <?= $usu['_id'] ?>
                                    </span>
                                    <span class="fw-bold">
                                    <?= $usu['total'] ?>
                                    </span>
                                </div>
                                <div class="progress mb-4" style="height:10px">
                                <div class="progress-bar bg-success" style="width: <?= $percent ?>%"></div>
                                </div>
                            <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                </div>
               
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Últims accessos</h2><br>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Hora</th>
                                    <th>Mètode</th>
                                    <th>URL</th>
                                    <th class="d-none d-md-table-cell">IP</th>
                                    <th class="d-none d-md-table-cell">Usuari</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultims_logs as $log): ?>
                                    <tr>
                                        <td><?= $log['date'] ?></td>
                                            <td>
                                                <span class= "badge bg-success">
                                                <?= $log['metodo'] ?>
                                                </span>
                                    </td>
                                        <td><?= $log['uri'] ?></td>
                                        <td class="d-none d-md-table-cell"><?= $log['ip_origin'] ?></td>
                                        <td class="d-none d-md-table-cell"><?= $log['user'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
<!-- Formulari per filtrar els logs per data, pàgina o usuari -->
                <div class="card mb-3">
    <div class="card-body">
        <h2 class="card-title text-muted">Buscar accessos</h2>
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <!-- Camp per filtrar per data -->
                <div class="col-md-3">
                    <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data) ?>">
                </div>
                 <!-- Camp per filtrar per pàgina -->
                <div class="col-md-3">
                    <input type="text" name="pagina" class="form-control" placeholder="/inici" value="<?= htmlspecialchars($pagina) ?>">
                </div>
                <!-- Camp per filtrar per usuari -->
                <div class="col-md-3">
                    <input type="text" name="user" class="form-control" placeholder="usuari" value="<?= htmlspecialchars($user ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Si s'ha aplicat algun filtre, mostrem el total de resultats trobats -->
        <?php if (!empty($data) || !empty($pagina) || !empty($user)): ?>
            <div class="text-center mt-3">
                <p class="small mb-1">Accessos trobats</p>
                <div class="text-total" style="color:#2e8754;"><?= $total ?></div>
            </div>
        <?php else: ?>
            <p class="text-muted small">Introdueix almenys una data, una pàgina amb "/" al principi o un usuari per buscar.</p>
        <?php endif; ?>

        <!-- Si s'ha aplicat algun filtre, mostrem el total de resultats trobats -->

        <?php if (!empty($data) || !empty($pagina) || !empty($user)): ?>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Hora</th>
                    <th>Mètode</th>
                    <th>URL</th>
                    <th class="d-none d-md-table-cell">IP</th>
                    <th class="d-none d-md-table-cell">Usuari</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs_filtrats as $log): ?>
                <tr>
                    <td><?= $log['date'] ?></td>
                    <td>
                        <span class="badge bg-success">
                            <?= $log['metodo'] ?>
                        </span>
                    </td>
                    <td><?= $log['uri'] ?></td>
                    <td class="d-none d-md-table-cell"><?= $log['ip_origin'] ?></td>
                    <td class="d-none d-md-table-cell"><?= $log['user'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
    </div>
</div>
                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-dark btn-sm px-4">Tornar</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>