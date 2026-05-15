<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "tecnic") && !($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



require_once 'connexio.php';

if($_SESSION["rol"] == "tecnic"){
    include_once 'header-tecnic.php' ; 
}elseif ($_SESSION["rol"] == "admin") {
    include_once 'header.php' ;  
}

include_once 'mongo.php';

function tancar_incidencia($conn){
    $id = $_POST['id_incidencia'];
    $sql = "UPDATE INCIDENCIA SET fecha_fin = NOW() WHERE id_incidencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) { ?>
        <div class="container mt-4">
            <div class="alert alert-success shadow-sm">Incidència tancada amb èxit!</div>
            <a href='index.php' class="btn btn-dark">Retorna a l'inici</a>
        </div>
    <?php
    } else { ?>
       <div class="container mt-4">
            <div class="alert alert-danger">Error al tancar la Incidència: <?= htmlspecialchars($stmt->error) ?></div>
       </div>
    <?php
    }
    $stmt->close();
}   
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card">
                
                <?php
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    tancar_incidencia($conn);
                } elseif (isset($_GET['id_incidencia'])){
                    $id = $_GET['id_incidencia'];

                    if (is_numeric($id)) {
                        $sql = "SELECT id_incidencia, descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING(id_dept) WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

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

                        $sql = "SELECT descripcio, fecha, duracio FROM ACTUACIO WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        echo "<h3>ACTUACIONS:</h3>";

                        if ($result->num_rows > 0) { ?>
                            <div class="table-responsive rounded shadow-sm">
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
                        } else {
                            echo "<p class='text-muted'>No hi ha actuacions a mostrar.</p>";
                        }
                        $conn->close();
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>