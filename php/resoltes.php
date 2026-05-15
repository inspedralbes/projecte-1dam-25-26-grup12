<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}

require_once 'connexio.php';
require_once 'header.php';
include_once 'mongo.php';

$sort = $_GET['sort'] ?? 'prioridad';
$order = $_GET['order'] ?? 'desc';

$start = isset($_GET['start']) ? (int)$_GET['start'] : 1;
$limit = 8;
$page = ($start - 1) * $limit;

$sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, i.fecha_fin, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NOT NULL ORDER BY $sort $order
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $page);
$stmt->execute();
$result = $stmt->get_result();

$countSql = "SELECT COUNT(*) as total FROM INCIDENCIA WHERE fecha_fin IS NOT NULL";
$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>

<style>
    body {
        background-color: #e9ecef;
    }
    .main-container {
        background-color: white;
        border-radius: 15px; 
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 30px;
        margin-bottom: 30px;
    }
    h1 {
        font-weight: 700;
        color: #212529;
        margin-bottom: 25px;
    }
</style>

<div class="container d-flex justify-content-center">
    <div class="col-12 main-container">

        <?php if ($result->num_rows > 0) { ?>
            <h1>Llistat d'Incidències resoltes</h1>
            
            <div class="mb-3">
                <span class="me-2">Prioritat</span>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=desc">↓</a>
                <span class="ms-3 me-2">Data</span>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=desc">↓</a>
            </div>

            <div class="table-responsive shadow-sm rounded">
                <table class="table table-dark table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripció</th>
                            <th>Data</th>
                            <th>Departament</th>
                            <th>Tipologia</th>
                            <th>Prioritat</th>
                            <th>Tècnic</th>
                            <th>Finalització</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        $clase_prioridad = "";
                        if ($row["prioridad"] == "alta") $clase_prioridad = "table-danger";
                        elseif ($row["prioridad"] == "media") $clase_prioridad = "table-warning";
                        elseif ($row["prioridad"] == "baja") $clase_prioridad = "table-info";
                    ?>
                        <tr class="<?= $clase_prioridad ?>">
                            <td><?= $row["id_incidencia"] ?></td>
                            <td><?= htmlspecialchars($row["descripcio"]) ?></td>
                            <td><?= $row["fecha"] ?></td>
                            <td><?= $row["departament_nom"] ?></td>
                            <td><?= $row["tipologia_nom"] ?></td>
                            <td><?= $row["prioridad"] ?></td>
                            <td><?= $row["tecnic_nom"] ?></td>
                            <td><?= $row["fecha_fin"] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <?php if ($start > 1): ?>
                    <a href="?start=<?= $start - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="btn btn-outline-dark">← Anterior</a>
                <?php endif; ?>

                <?php
                $maxButtons = 5;
                $inicio = max(1, $start - 2);
                $fin = min($totalPages, $inicio + $maxButtons - 1);
                $inicio = max(1, $fin - $maxButtons + 1);
                for ($i = $inicio; $i <= $fin; $i++): ?>
                    <a href="?start=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>"
                       class="btn <?= ($i == $start) ? 'btn-success fw-bold' : 'btn-outline-success' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($start < $totalPages): ?>
                    <a href="?start=<?= $start + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="btn btn-outline-dark">Següent →</a>
                <?php endif; ?>
            </div>

        <?php } else {
            echo "<p class='alert alert-secondary'>No hi ha dades a mostrar.</p>";
        } ?>

        <br>
        <div>
            <a href="llistar.php" class="btn btn-dark btn-sm px-4">Tornar</a>
        </div>

    </div>
</div>

<?php
$conn->close();
require_once 'footer.php';
?>