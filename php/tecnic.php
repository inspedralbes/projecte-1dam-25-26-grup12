<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "tecnic")) {
    if ($_SESSION["rol"] == "admin") {
        header("Location: tecnotocar.php");
        exit(); 
    }
    header("Location: index.php");
    exit();  
}




require_once 'header.php';
require_once 'connexio.php';
include_once 'mongo.php';
?>

<style>
    body { background-color: #f0f2f5; }
</style>

<?php
    $id = $_SESSION["id_tecnic"];

    $sql = "SELECT id_incidencia, descripcio, id_dept, fecha
            FROM INCIDENCIA WHERE id_tecnic = $id AND fecha_fin IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { ?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-4 mb-4">
               <h3 class='mb-4 text-center fw-semibold' style='font-size:2.3rem;'>Les teves incidències</h3><br>
        

            <?php while ($row = $result->fetch_assoc()) { ?>

                    <div class="d-flex justify-content-between align-items-center bg-light border-start border-4 border-success rounded-3 shadow-sm p-4 mb-3">
                        <div>
                            <h5 class="mb-1 fw-semibold">Incidència #<?= $row["id_incidencia"] ?></h5>
                            <p class="mb-0 text-muted small"><?= htmlspecialchars(substr($row["descripcio"], 0, 50)) ?>...</p>
                        </div>
                        <a href='actuacions.php?id_incidencia=<?= $row["id_incidencia"] ?>' class="btn btn-success btn-sm px-4">Mostrar</a>
                    </div>

            <?php } ?>

            </div>
        </div>
    </div>
<</div>

    <?php } else {
        echo "<div class='alert alert-light text-center border'>No tens incidències pendents actualment.</div>";
    }
?>

<?php
require_once 'footer.php';
$conn->close();
?>