<?php

session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}





// Conexiones y headers (No tocamos lógica)
require_once 'connexio.php';
require_once 'header.php';
include_once 'mongo.php';

$sort = $_GET['sort'] ?? 'prioridad';
$order = $_GET['order'] ?? 'desc';
$sort1 = $_GET['sort1'] ?? 'fecha';
$order1 = $_GET['order1'] ?? 'desc';

$sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS NOT NULL  ORDER BY $sort $order";
$result = $conn->query($sql);

$sql1 = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS  NULL  ORDER BY $sort1 $order1";
$result1 = $conn->query($sql1);
?>

<style>
    body {
        background-color: #f0f2f5;
    }
</style>

<div class="container d-flex justify-content-center">
    <div class="col-12 bg-white rounded-4 shadow-sm p-5 mt-4 mb-4">

        <?php if ($result1->num_rows > 0) { ?>
            <h1 class="fw-semibold mb-3" style="font-size:1.6rem;">Incidències Pendents</h1>

            <div class="mb-3">
                <span class="me-2 fw-medium">Data</span>
                <a class="btn btn-dark btn-sm" href="?sort1=fecha&order1=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort1=fecha&order1=desc">↓</a>
            </div>

            <div class="table-responsive shadow-sm rounded mb-2">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Descripció</th>
                            <th>Data</th>
                            <th>Departament</th>
                            <th>Tipologia</th>
                            <th>Prioritat</th>
                            <th>Tècnic</th>
                            <th>Modificar</th>
                            <th>Esborrar</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php while ($row = $result1->fetch_assoc()) { ?>
                        <tr class="table-light">
                            <td><?= $row["id_incidencia"] ?></td>
                            <td><?= htmlspecialchars($row["descripcio"]) ?></td>
                            <td><?= $row["fecha"] ?></td>
                            <td><?= $row["departament_nom"] ?></td>
                            <td><?= $row["tipologia_nom"] ?></td>
                            <td><?= $row["prioridad"] ?></td>
                            <td><?= $row["tecnic_nom"] ?></td>
                            <td><a class="btn btn-primary btn-sm" href='modificar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Modificar</a></td>
                            <td><a class="btn btn-danger btn-sm" href='esborrar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Esborrar</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <hr class="my-5">
        <?php } ?>

        <?php if ($result->num_rows > 0) { ?>
            <h1 class="fw-semibold mb-3" style="font-size:1.6rem;">Llistat d'Incidències</h1>

            <div class="mb-3">
                <span class="me-2 fw-medium">Prioritat</span>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=desc">↓</a>
                <span class="ms-3 me-2 fw-medium">Data</span>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=desc">↓</a>
            </div>

            <div class="table-responsive shadow-sm rounded mb-2">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripció</th>
                            <th>Data</th>
                            <th>Departament</th>
                            <th>Tipologia</th>
                            <th>Prioritat</th>
                            <th>Tècnic</th>
                            <th>Modificar</th>
                            <th>Esborrar</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
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
                            <td><a class="btn btn-primary btn-sm" href='modificar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Modificar</a></td>
                            <td><a class="btn btn-danger btn-sm" href='esborrar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Esborrar</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else {
            echo "<p class='alert alert-secondary'>No hi ha dades a mostrar.</p>";
        } ?>

        <br><br>

        <a class="btn btn-dark px-4" href='resoltes.php'>Incidències resoltes</a>

    </div>
</div>

<?php
$conn->close();
require_once 'footer.php';
?>