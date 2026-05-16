<?php
session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no ha fet login i es va a index.php
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();

// Si el rol no és admin, no té permisos per accedir a aquesta pàgina.
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}

// Connectem a la BD i carreguem el header i MongoDB
require_once 'connexio.php';
include_once 'header.php';
include_once 'mongo.php';

// Agafem els paràmetres d'ordenació de la URL, per defecte ordenem per prioritat descendent
$sort  = $_GET['sort']  ?? 'prioridad';
$order = $_GET['order'] ?? 'desc';
$sort1  = $_GET['sort1']  ?? 'fecha';
$order1 = $_GET['order1'] ?? 'desc';

// Gestionem la paginació i agafem la pàgina actual de la URL, per defecte la 1
$start = isset($_GET['start']) ? (int)$_GET['start'] : 1;
$limit = 8;                         // files per pàgina
$page  = ($start - 1) * $limit;    // Calculem l'OFFSET per a la consulta SQL

// Consultem les incidències obertes sense prioritat assignada (pendents de clasificar) fent JOIN amb departament, tipologia i tècnic per obtenir els seus noms
$sql1 = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS NULL ORDER BY $sort1 $order1 LIMIT ? OFFSET ?";

$stmt1 = $conn->prepare($sql1); 
$stmt1->bind_param("ii", $limit, $page);
$stmt1->execute();
$result1 = $stmt1->get_result();

// Comptem el total d'incidències sense prioritat per calcular el nombre de pàgines
$countSql1    = "SELECT COUNT(*) as total FROM INCIDENCIA WHERE fecha_fin IS NULL AND prioridad IS NULL";
$countResult1 = $conn->query($countSql1);
$totalRows1   = $countResult1->fetch_assoc()['total'];
$totalPages1  = ceil($totalRows1 / $limit); // Calculem el total de pàgines

// Consultem les incidències obertes amb prioritat assignada, aplicant l'ordenació escollida
$sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS NOT NULL ORDER BY $sort $order
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $page);
$stmt->execute();
$result = $stmt->get_result();

// Comptem el total d'incidències amb prioritat per calcular el nombre de pàgines
$countSql    = "SELECT COUNT(*) as total FROM INCIDENCIA WHERE fecha_fin IS NULL AND prioridad IS NOT NULL";
$countResult = $conn->query($countSql);
$totalRows   = $countResult->fetch_assoc()['total'];
$totalPages  = ceil($totalRows / $limit); // Calculem el total de pàgines
?>

<div class="container d-flex justify-content-center">
    <div class="col-12 bg-white rounded-4 shadow-sm p-5 mt-4 mb-4">

        <!-- Secció d'incidències sense prioritat assignada (pendents de classificar) -->
        <?php if ($result1->num_rows > 0) { ?>
            <h1 class="fw-semibold mb-3" style="font-size:1.6rem;">Incidències Pendents</h1>

            <!-- Botons per ordenar les incidències pendents per data -->
            <div class="mb-3">
                <span class="me-2 fw-medium">Data</span>
                <a class="btn btn-dark btn-sm" href="?sort1=fecha&order1=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort1=fecha&order1=desc">↓</a>
            </div>

            <!-- Taula d'incidències sense prioritat amb botons per modificar i esborrar -->
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
                            <!-- Botons per modificar o esborrar la incidència -->
                            <td><a class="btn btn-primary btn-sm" href='modificar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Modificar</a></td>
                            <td><a class="btn btn-danger btn-sm" href='esborrar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Esborrar</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Botons de paginació per a les incidències sense prioritat -->
            <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <!-- Botó per anar a la pàgina anterior, només si no estem a la primera -->
                <?php if ($start > 1): ?>
                    <a href="?start=<?= $start - 1 ?>&sort=<?= $sort1 ?>&order=<?= $order1 ?>" class="btn btn-outline-dark">← Anterior</a>
                <?php endif; ?>

                <?php
                // Calculem quins botons de pàgina mostrem (màxim 5 centrats a la pàgina actual)
                $maxButtons = 5;
                $inicio = max(1, $start - 2);
                $fin    = min($totalPages1, $inicio + $maxButtons - 1);
                $inicio = max(1, $fin - $maxButtons + 1);

                for ($y = $inicio; $y <= $fin; $y++): ?>
                    <!-- Marquem en verd el botó de la pàgina actual -->
                    <a href="?start=<?= $y ?>&sort=<?= $sort1 ?>&order=<?= $order1 ?>"
                       class="btn <?= ($y == $start) ? 'btn-success fw-bold' : 'btn-outline-success' ?>">
                        <?= $y ?>
                    </a>
                <?php endfor; ?>

                <!-- Botó per anar a la pàgina següent, només si no estem a l'última -->
                <?php if ($start < $totalPages1): ?>
                    <a href="?start=<?= $start + 1 ?>&sort=<?= $sort1 ?>&order=<?= $order1 ?>" class="btn btn-outline-dark">Següent →</a>
                <?php endif; ?>
            </div>

            <hr class="my-5">
        <?php } ?>

        <!-- Secció d'incidències AMB prioritat assignada -->
        <?php if ($result->num_rows > 0) { ?>
            <h1 class="fw-semibold mb-3" style="font-size:1.6rem;">Llistat d'Incidències</h1>

            <!-- Botons per ordenar el llistat per prioritat o per data -->
            <div class="mb-3">
                <span class="me-2 fw-medium">Prioritat</span>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=prioridad&order=desc">↓</a>
                <span class="ms-3 me-2 fw-medium">Data</span>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=asc">↑</a>
                <a class="btn btn-dark btn-sm" href="?sort=fecha&order=desc">↓</a>
            </div>

            <!-- Taula d'incidències amb prioritat, cada fila té un color segons la prioritat -->
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
                        // Assignem un color de fila diferent segons la prioritat de la incidència
                        $clase_prioridad = "";
                        if ($row["prioridad"] == "alta")       $clase_prioridad = "table-danger";  // Vermell per alta
                        elseif ($row["prioridad"] == "media")  $clase_prioridad = "table-warning"; // Groc per mitjana
                        elseif ($row["prioridad"] == "baja")   $clase_prioridad = "table-info";    // Blau per baixa
                    ?>
                        <tr class="<?= $clase_prioridad ?>">
                            <td><?= $row["id_incidencia"] ?></td>
                            <td><?= htmlspecialchars($row["descripcio"]) ?></td>
                            <td><?= $row["fecha"] ?></td>
                            <td><?= $row["departament_nom"] ?></td>
                            <td><?= $row["tipologia_nom"] ?></td>
                            <td><?= $row["prioridad"] ?></td>
                            <td><?= $row["tecnic_nom"] ?></td>
                            <!-- Botons per modificar o esborrar la incidència -->
                            <td><a class="btn btn-primary btn-sm" href='modificar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Modificar</a></td>
                            <td><a class="btn btn-danger btn-sm" href='esborrar.php?id_incidencia=<?= $row["id_incidencia"] ?>'>Esborrar</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>

            <!-- Botons de paginació per al llistat d'incidències amb prioritat -->
            <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <!-- Botó per anar a la pàgina anterior, només si no estem a la primera -->
                <?php if ($start > 1): ?>
                    <a href="?start=<?= $start - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="btn btn-outline-dark">← Anterior</a>
                <?php endif; ?>

                <?php
                // Calculem quins botons de pàgina mostrem (màxim 5 centrats a la pàgina actual)
                $maxButtons = 5;
                $inicio = max(1, $start - 2);
                $fin    = min($totalPages, $inicio + $maxButtons - 1);
                $inicio = max(1, $fin - $maxButtons + 1);

                for ($i = $inicio; $i <= $fin; $i++): ?>
                    <!-- Marquem en verd el botó de la pàgina actual -->
                    <a href="?start=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>"
                       class="btn <?= ($i == $start) ? 'btn-success fw-bold' : 'btn-outline-success' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Botó per anar a la pàgina següent, només si no estem a l'última -->
                <?php if ($start < $totalPages): ?>
                    <a href="?start=<?= $start + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="btn btn-outline-dark">Següent →</a>
                <?php endif; ?>
            </div>

        <?php } else {
            // Si no hi ha incidències, mostrem un missatge informatiu
            echo "<p class='alert alert-secondary'>No hi ha dades a mostrar.</p>";
        } ?>

        <br><br>

        <!-- Botó per accedir al llistat d'incidències ja resoltes -->
        <a class="btn btn-dark px-4" href='resoltes.php'>Incidències resoltes</a>

    </div>
</div>

<?php
// Tanquem la connexió i carreguem el peu de pàgina comú
$conn->close();
require_once 'footer.php';
?>