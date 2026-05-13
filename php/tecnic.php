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
    body {
        background-color: #e9ecef; 
    }
    .admin-card {
        background-color: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 30px;
        margin-bottom: 30px;
    }
    .incidencia-item {
        background-color: #f8f9fa;
        border-left: 5px solid #212529;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    h1, h3 {
        font-weight: 700;
        color: #212529;
    }
    legend {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>


                <?php
            
                    $id = $_SESSION["id_tecnic"];
                    echo "<hr class='my-5'>";
                    echo "<h3 class='mb-4 text-center text-primary'>Les teves incidències</h3>";

                    $sql = "SELECT id_incidencia, descripcio, id_dept, fecha
                            FROM INCIDENCIA WHERE id_tecnic = $id AND fecha_fin IS NULL";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            
                            <div class="incidencia-item shadow-sm">
                                <div>
                                    <h5 class="mb-1">Incidència #<?= $row["id_incidencia"] ?></h5>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars(substr($row["descripcio"], 0, 50)) ?>...</p>
                                </div>
                                <a href='actuacions.php?id_incidencia=<?= $row["id_incidencia"] ?>' class="btn btn-primary btn-sm px-4">Mostrar</a>
                            </div>

                        <?php
                        }
                    } else {
                        echo "<div class='alert alert-light text-center border'>No tens incidències pendents actualment.</div>";
                    }
                
                ?>
            </div> </div>
    </div>
</div>

<?php
require_once 'footer.php';
$conn->close();
?>