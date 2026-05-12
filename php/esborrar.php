<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



require_once 'connexio.php';
require_once 'header.php';
include_once 'mongo.php';
?>

<style>
    body { background-color: #e9ecef; }
    .main-card {
        background-color: white;
        border-radius: 15px;
        padding: 50px 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 50px;
        text-align: center;
    }
    h1.delete-title { font-weight: 700; color: #dc3545; margin-bottom: 20px; font-size: 2.5rem; }
    .btn-delete { background-color: #dc3545; color: white; padding: 10px 25px; border-radius: 8px; border: none; font-weight: 600; }
    .btn-cancel { background-color: #f8f9fa; color: #6c757d; padding: 10px 25px; border-radius: 8px; border: 1px solid #dee2e6; text-decoration: none; font-weight: 600; }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="main-card">
                
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = $_POST['id_incidencia'];
                    if (is_numeric($id)) {
                        
                        // --- SOLUCIÓN AL ERROR DE FOREIGN KEY ---
                        // 1. Primero borramos todas las actuaciones relacionadas con esta incidencia
                        $sql_actuaciones = "DELETE FROM ACTUACIO WHERE id_incidencia = ?";
                        $stmt_act = $conn->prepare($sql_actuaciones);
                        $stmt_act->bind_param("i", $id);
                        $stmt_act->execute();
                        $stmt_act->close();

                        // 2. Ahora que ya no hay "hijos", ya podemos borrar el "padre" (la incidencia)
                        $sql = "DELETE FROM INCIDENCIA WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);

                        if ($stmt->execute()) { 
                            echo "<h1 class='delete-title' style='color: #198754;'>Fet!</h1>";
                            echo "<div class='alert alert-success'>Incidència i les seves actuacions esborrades amb èxit.</div>";
                            echo "<p class='mt-4'><a href='llistar.php' class='btn-cancel'>Retorna al llistat</a></p>";
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
                        }
                        $stmt->close();
                    }
                } elseif (isset($_GET['id_incidencia'])) {
                    $id = $_GET['id_incidencia'];
                    if (is_numeric($id)) {
                        $sql = "SELECT id_incidencia FROM INCIDENCIA WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            ?>
                            <h1 class="delete-title">Esborrar</h1>
                            <p class="mb-4">Realment vols esborrar la incidència ID: <strong><?= htmlspecialchars($row["id_incidencia"]) ?></strong>?</p>
                            
                            <form method='POST' action='esborrar.php'>
                                <input type='hidden' name='id_incidencia' value='<?= htmlspecialchars($row["id_incidencia"]) ?>'>
                                <div class="d-flex justify-content-center gap-3">
                                    <button type='submit' class='btn-delete'>Sí, esborrar</button>
                                    <a href="llistar.php" class="btn-cancel">Cancel·lar</a>
                                </div>
                            </form>
                        <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>