<?php

require 'vendor/autoload.php';
require_once 'header.php';

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
if (!empty($data)) {
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

?>

<style>
    body { background-color: #e9ecef; }
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

                <div class="alert alert-light border text-center mb-4">
                    <p class="mb-0 text-muted small">Total d'accessos</p>
                    <div class="text-total"><?= $accessos_total ?></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h2>Pàgines més visitades</h2>
                        <table class="table table-sm table-striped table-dark rounded overflow-hidden">
                            <thead>
                                <tr>
                                    <th>Pàgina</th>
                                    <th>Visites</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagines as $enllaç): ?>
                                    <tr>
                                        <td class="small"><?= $enllaç['_id'] ?></td>
                                        <td class="fw-bold"><?= $enllaç['total'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h2>Accessos per dia</h2>
                        <table class="table table-sm table-striped table-dark rounded overflow-hidden">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Total</th>
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
                </div>

                <!-- Filtre -->
                <h2>Cerca d'accessos</h2>
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data) ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="pagina" class="form-control" placeholder="/inici" value="<?= htmlspecialchars($pagina) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Buscar</button>
                        </div>
                    </div>
                </form>

                <?php if (!empty($data) || !empty($pagina)): ?>
                    <div class="alert alert-light border text-center">
                        <p class="mb-0 text-muted small">Accessos trobats</p>
                        <div class="text-total"><?= $total ?></div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Introdueix almenys una data o una pàgina per buscar.</p>
                <?php endif; ?>

                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-dark btn-sm px-4">Tornar</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>