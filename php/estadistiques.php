<?php include("header.php");
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
.graficos-container {
    display: flex;
    gap: 30px;
    justify-content: center;
    margin: 30px 0;
}

.grafico {
    width: 600px;
    height: 600px;
}
</style>
<div class="table-responsive shadow-sm rounded">
<table class="table table-dark table-striped mb-0">
    <thead>
        <tr>
            <th>Departament</th>
            <th>Temps total</th>
            <th>Núm. incidències</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($departaments as $unDepartament) { 
            $tempsArray[] = $unDepartament["TEMPS_TOTAL"];
            $deptsArray[] = $unDepartament["departament_nom"];
            $numArray[] = $unDepartament["NUM_INCIDENCIES"];
        ?>
            <tr>
                <td scope="row"><?php echo $unDepartament["departament_nom"]; ?></td>
                <td><?php echo $unDepartament["TEMPS_TOTAL"]; ?> minuts</td>
                <td><?php echo $unDepartament["NUM_INCIDENCIES"]; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
        </div>

<div class="graficos-container">
    <div class="grafico">
        <canvas id="myChart"></canvas>
    </div>

    <div class="grafico">
        <canvas id="myChart2"></canvas>
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