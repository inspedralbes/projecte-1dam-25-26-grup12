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

///Fem la consulta sql que mostra el departament, el numero de incidencies i el temps total.
$sql = ("SELECT DISTINCT i.id_dept, d.nom AS departament_nom, 
(SELECT COUNT(*) FROM INCIDENCIA i2 WHERE i2.id_dept = i.id_dept) AS NUM_INCIDENCIES, 
(SELECT COALESCE(SUM(a.duracio),0) 
 FROM ACTUACIO a 
 JOIN INCIDENCIA i3 ON i3.id_incidencia = a.id_incidencia 
 WHERE i3.id_dept = i.id_dept) AS TEMPS_TOTAL
FROM INCIDENCIA i 
JOIN DEPARTAMENT d ON d.id_dept = i.id_dept
ORDER BY i.id_dept");

///Executem la consulta a la base de dades
$resultat = $conn->query($sql);

///Guardem tots els resultats en un array associatiu
$departaments = $resultat->fetch_all(MYSQLI_ASSOC);

///Guardem tots els resultats associant-los en un array
$tempsArray = array();
$deptsArray = array();
$numArray = array();
?>



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

                        <!-- //Posem els canvas dels grafics en dues columnes -->
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

                        <!-- //Creem la taula per mostrar el departament, el numero de incidencies i el temps total. -->
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">Departament</th>
                                    <th class="text-center">Temps total</th>
                                    <th class="text-center">Núm. incidències</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php
                                //Fem un for i recorrem cada departament i anem afegint les dades dels tres arrays
                                foreach ($departaments as $unDepartament) { 
                                    $tempsArray[] = $unDepartament["TEMPS_TOTAL"];
                                    $deptsArray[] = $unDepartament["departament_nom"];
                                    $numArray[] = $unDepartament["NUM_INCIDENCIES"];
                                ?>
                                <!-- Aqui mostrem lo que treu el for dels arrays -->
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

<!-- //Aixo carrega la llibreria Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
///Convertertim el array php de departaments a json per poder utilitzar-los en javascript. array_inique elimina els duplicats
const labelsDepartaments = <?php echo json_encode(array_values(array_unique($deptsArray))); ?>;

///Convertim els arrays de numeros i temps a json també
const numIncidencies = <?php echo json_encode($numArray); ?>;
const tempsTotal = <?php echo json_encode($tempsArray); ?>;

///Amb això obtenim el canvas del primer grafic
const ctx = document.getElementById('myChart');

///Creem el primer gràfic de quesito amb el nombre d'incidències per departament
new Chart(ctx, {
    ///Defineix quin tipus de grafic es, i pie significa pastis
    type: 'pie',
    /// data es el bloc on es defineixen totes les dades grafic
    data: {
        /// les etiquetes dels noms dels departament
        labels: labelsDepartaments,
        /// datasets es un array 
        datasets: [{
            ///nom del dataset
            label: 'Incidències per departament',
            /// el valor que ha de ensenyar que es el nombre incidencies
            data: numIncidencies,
            ///aixo es per separar cada porcio del grafic un pixel
            borderWidth: 1
        }]
    },
    ///Bloc on es configuren les opcions visuals del gràfic
    options: {
        ///fa que el grafic s'adapti atomaticament a la mida de la pantalla
        responsive: true,
        ///Bloc per afegir complements addicionals del gràfic
        plugins: {
            ///Mostra un títol a la part superior del gràfic amb el text indicat
            title: {
                display: true,
                text: 'Incidències total per departament'
            }
        }
    }
});

///Amb això obtenim el canvas del segon grafic
const ctx2 = document.getElementById('myChart2');

///Creem el primer gràfic de quesito amb el temps total per departament
new Chart(ctx2, {
    ///Defineix quin tipus de grafic es, i pie significa pastis
    type: 'pie',
    /// data es el bloc on es defineixen totes les dades grafic
    data: {
        /// les etiquetes dels noms dels departament
        labels: labelsDepartaments,
        datasets: [{
            ///nom del dataset
            label: 'Incidències temps total',
            /// el valor que ha de ensenyar que es el temps total
            data: tempsTotal,
            ///aixo es per separar cada porcio del grafic un pixel
            borderWidth: 1
        }]
    },
    ///Bloc on es configuren les opcions visuals del gràfic
    options: {
        ///fa que el grafic s'adapti atomaticament a la mida de la pantalla
        responsive: true,
        ///Bloc per afegir complements addicionals del gràfic
        plugins: {
             ///Mostra un títol a la part superior del gràfic amb el text indicat
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

include_once 'footer.php';
?>