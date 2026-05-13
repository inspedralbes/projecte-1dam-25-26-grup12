<?php 
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}





include("header.php");
require_once 'connexio.php';

$sql = ("SELECT DISTINCT i.id_dept, d.nom AS departament_nom, 
(SELECT COUNT(*) FROM INCIDENCIA i2 WHERE i2.id_dept = i.id_dept) AS NUM_INCIDENCIES, 
(SELECT COALESCE(SUM(a.duracio),0) 
 FROM ACTUACIO a 
 JOIN INCIDENCIA i3 ON i3.id_incidencia = a.id_incidencia 
 WHERE i3.id_dept = i.id_dept) AS TEMPS_TOTAL
FROM INCIDENCIA i 
JOIN DEPARTAMENT d ON d.id_dept = i.id_dept
ORDER BY i.id_dept");

$resultat = $conn->query($sql);
$departaments = $resultat->fetch_all(MYSQLI_ASSOC);

$tempsArray = array();
$deptsArray = array();
$numArray = array();
?>

<style>
.grafico {
    flex: 1;
    min-width: 0;
    height: 500px;
    position: relative;
}
.grafico canvas {
    width: 100% !important;
    height: 100% !important;
}
</style>

<div class="container-fluid px-2">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center py-3">
                    <h2 class="card-title mb-0 fw-semibold" style="font-size:1.6rem;">
                        Consum per departaments
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-6" style="height:800px; position:relative;">
                            <br><br><br>
                            <canvas id="myChart"></canvas>
                        </div>
                        <div class="col-6" style="height:800px; position:relative;">
                        <br><br><br>
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="table-responsive rounded">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">Departament</th>
                                    <th class="text-center">Temps total</th>
                                    <th class="text-center">Núm. incidències</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($departaments as $unDepartament) { 
                                    $tempsArray[] = $unDepartament["TEMPS_TOTAL"];
                                    $deptsArray[] = $unDepartament["departament_nom"];
                                    $numArray[] = $unDepartament["NUM_INCIDENCIES"];
                                ?>
                                <tr class="table-light">
                                    <td class="text-center"><?php echo $unDepartament["departament_nom"]; ?></td>
                                    <td class="text-center"><?php echo $unDepartament["TEMPS_TOTAL"]; ?> minuts</td>
                                    <td class="text-center"><?php echo $unDepartament["NUM_INCIDENCIES"]; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labelsDepartaments = <?php echo json_encode(array_values(array_unique($deptsArray))); ?>;
const numIncidencies = <?php echo json_encode($numArray); ?>;
const tempsTotal = <?php echo json_encode($tempsArray); ?>;

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labelsDepartaments,
        datasets: [{
            label: 'Incidències per departament',
            data: numIncidencies,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Incidències total per departament'
            }
        }
    }
});

const ctx2 = document.getElementById('myChart2');

new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: labelsDepartaments,
        datasets: [{
            label: 'Incidències temps total',
            data: tempsTotal,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Consum temps total per departament'
            }
        }
    }
});
</script>

<?php
$resultat = $conn->query("SELECT id_incidencia, DEPARTAMENT.nom as aula, descripcio, DATE(fecha) as dataIni, prioridad 
FROM INCIDENCIA 
JOIN DEPARTAMENT ON DEPARTAMENT.id_dept = INCIDENCIA.id_dept 
WHERE fecha_fin IS NULL 
ORDER BY prioridad DESC");

$incidencies = $resultat->fetch_all(MYSQLI_ASSOC);
?>