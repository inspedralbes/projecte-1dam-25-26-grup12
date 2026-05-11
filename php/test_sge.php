<?php include("header.php");
require_once 'connexio.php';

$sql = "SELECT nomDepartament, tempsTotalDedicat, nombreIncidencies FROM vista_consum_departaments";

$resultat = $conn->query($sql);

$departaments = $resultat->fetch_all(MYSQLI_ASSOC);

$tempsArray = array();

$deptsArray = array();

?>

<?php foreach ($departaments as $unDepartament) {

$tempsArray[] = $unDepartament["tempsTotalDedicat"];

$deptsArray[] = $unDepartament["nomDepartament"];?>

<tbody>

<tr>

      <th scope="row"><?php echo $unDepartament["nomDepartament"] ?></th>

      <td><?php echo $unDepartament["tempsTotalDedicat"] ?> minuts</td>

      <td><?php echo $unDepartament["nombreIncidencies"] ?></td>

      </tr>

</tbody>

<?php } ?>


<canvas id="myChart" width="500" height="500"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

 const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_values(array_unique($deptsArray))); ?>,
        datasets: [{
            label: 'Incidències per departament',
            data: <?php
                echo json_encode(
                    array_values(array_count_values($deptsArray))
                );
            ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: false
    }
});

</script>

<?php



$resultat = $conn->query("SELECT id_incidencia, DEPARTAMENT.nom as aula, descripcio, DATE(fecha) as dataIni, prioridad FROM INCIDENCIA JOIN DEPARTAMENT ON DEPARTAMENT.id_dept = INCIDENCIA.id_dept WHERE fecha_fin IS NULL ORDER BY prioridad DESC");

$incidencies = $resultat->fetch_all(MYSQLI_ASSOC);

?>


