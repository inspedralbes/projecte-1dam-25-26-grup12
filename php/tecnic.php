<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "tecnic")) {
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="admin-card">
                <h1 class="text-center mb-4">Identifica't</h1>

                <?php
                $sql = "SELECT id_tecnic, nom FROM TECNIC ORDER BY nom";
                $result = $conn->query($sql);
                ?>

                <form method="POST" action="">
                    <fieldset class="border p-4 rounded">
                        <legend class="w-auto px-2">Tècnic</legend>

                        <div class="mb-3">
                            <label for="tecnic" class="form-label fw-bold">Selecciona el teu nom</label>
                            <select name="tecnic_id" id="tecnic" class="form-select form-select-lg" required>
                                <option value=""> Selecciona </option>
                                <?php while ($tec = $result->fetch_assoc()) { ?>
                                    <option value="<?= $tec['id_tecnic'] ?>">
                                        <?= htmlspecialchars($tec['nom']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">Entrar al sistema</button>
                        </div>
                    </fieldset>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = htmlspecialchars($_POST["tecnic_id"]);
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
                }
                ?>
            </div> </div>
    </div>
</div>

<?php
require_once 'footer.php';
$conn->close();
?>