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
$sort1 = $_GET['sort1'] ?? 'fecha';
$order1 = $_GET['order1'] ?? 'desc';

$sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, i.fecha_fin, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NOT NULL ORDER BY $sort $order";
$result = $conn->query($sql);

?>


<div class="container d-flex justify-content-center">
    <div class="col-12  juan">

        <?php if ($result->num_rows > 0) { ?>
            <h1 class="h1-b" >Llistat d'Incidències resoltes</h1>
            
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