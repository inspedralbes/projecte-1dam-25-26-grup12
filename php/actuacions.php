<?php
// Aqui iniciem la sessió
session_start();

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();

// Si el rol no és tecnic ni admin, no té permisos per accedir, i redirigim a index.php.
}elseif (!($_SESSION["rol"] == "tecnic") && !($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



require_once 'connexio.php'; // Connectem a la BD

// Carreguem el header diferent segons el rol que té l'ususari al iniciar sessio
if($_SESSION["rol"] == "tecnic"){
    include_once 'header-tecnic.php' ; 
}elseif ($_SESSION["rol"] == "admin") {
    include_once 'header.php' ;  
}

include_once 'mongo.php'; //Conectem amb MongoDB

function tancar_incidencia($conn){//Funció per tancar la incidencia.
    $id = $_POST['id_incidencia']; // Agafem l'ID enviat pel formulari
    
    // Actualitzem la incidència posant la data de fi a la actual.
    $sql = "UPDATE INCIDENCIA SET fecha_fin = NOW() WHERE id_incidencia = ?";
    $stmt = $conn->prepare($sql); // Preparem la query
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) { ?> 
        <!--Si ha anat bé, mostrem missatge d'èxit i un botó per tornar a l'inici -->
        <div class="container mt-4">
            <div class="alert alert-success shadow-sm">Incidència tancada amb èxit!</div>
            <a href='index.php' class="btn btn-dark">Retorna a l'inici</a>
        </div>
    <?php
    // Si ha fallat, mostrem el missatge d'error que retorna la BD.
    } else { ?>
       <div class="container mt-4">
            <div class="alert alert-danger">Error al tancar la Incidència: <?= htmlspecialchars($stmt->error) ?></div>
       </div>
    <?php
    }
    $stmt->close(); //aqui tanquem el statement
}   
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card">
                
                <?php
                // Comprovem si l'usuari ha enviat el formulari de tancar incidència
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    tancar_incidencia($conn); // Cridem la funció que fa el UPDATE a la BD
                
                } elseif (isset($_GET['id_incidencia'])){// Comprovem si l'ID de la incidència ve per  URL
                    $id = $_GET['id_incidencia'];
                    
                    // Mirem que sigui un número per evitar problemes amb  la BD
                    if (is_numeric($id)) {

                        // Consultem la incidència unint la taula INCIDENCIA amb DEPARTAMENT per obtenir també el nom del departament
                        $sql = "SELECT id_incidencia, descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING(id_dept) WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Si existeix la incidència, mostrem les seves dades per pantalla i dos botons: un per crear una actuació i un altre per tancar la incidència

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                
                                <div class="incidencia-header">
                                    <h1>Detall Incidència #<?= htmlspecialchars($row["id_incidencia"]) ?></h1>
                                </div>

                                <div class="mb-4">
                                    <div class="info-row"><span class="info-label">ID:</span> <?= htmlspecialchars($row["id_incidencia"]) ?></div>
                                    <div class="info-row"><span class="info-label">Descripció:</span> <?= htmlspecialchars($row["descripcio"]) ?></div>
                                    <div class="info-row"><span class="info-label">Departament:</span> <?= htmlspecialchars($row["nom"]) ?></div>
                                    <div class="info-row"><span class="info-label">Data:</span> <?=htmlspecialchars($row["fecha"]) ?></div>
                                </div>

                                <div class="d-flex gap-2 mb-2">
                                    <a href="crear_actuaciones.php?id_incidencia=<?= $row["id_incidencia"] ?>" class="btn btn-success px-4">Crear actuació</a>
                                    
                                    <form method='POST' action='actuacions.php'> 
                                        <input type='hidden' name='id_incidencia' value="<?= htmlspecialchars($id) ?>">
                                        <button type="submit" class="btn btn-dark px-4">Tancar Incidència</button>       
                                    </form>
                                </div>
                            <?php }
                        } 

                        // Fem una segona consulta per obtenir totes les actuacions que s'han fet sobre aquesta incidència

                        $sql = "SELECT descripcio, fecha, duracio FROM ACTUACIO WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        echo "<h3>ACTUACIONS:</h3>";

                        // Si hi ha actuacions, les mostrem en una taula amb descripció, data i minuts dedicats


                        if ($result->num_rows > 0) { ?>
                            <div class="table-responsive rounded shadow-sm">

                            <!--Mostrem les actuacions en una taula-->
                                <table class="table table-dark table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Descripció</th>
                                            <th>Data</th>
                                            <th>Temps dedicat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["descripcio"]) ?></td>
                                                <td><?= htmlspecialchars($row["fecha"]) ?></td>
                                                <td><?= $row["duracio"] ?> minuts</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php 
                        // Si no hi ha cap actuació encara, mostrem un missatge informatiu
                        } else {
                            echo "<p class='text-muted'>No hi ha actuacions a mostrar.</p>";
                        }
                        $conn->close(); /// Tanquem la connexió un cop acabat
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?> 