<?php
session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();

// Si el rol no és admin, no té permisos per accedir a aquesta pàgina i i redirigim a index.php.

}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



// Connectem a la BD i carreguem el headaer i MongoDB
require_once 'connexio.php';
include_once 'header.php';
include_once 'mongo.php';
?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="main-card esborrar">
                
                <?php
                 // Si el formulari s'ha enviat, procedim a esborrar la incidència
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = $_POST['id_incidencia'];
                    //Mirem que el id sigui numeric per no tenir problemes amb la BD
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

                        // Comprovem si s'ha esborrat correctament i mostrem el missatge corresponent
                        if ($stmt->execute()) { 
                            echo "<h1 class='delete-title' style='color: #198754;'>Fet!</h1>";
                            echo "<div class='alert alert-success'>Incidència i les seves actuacions esborrades amb èxit.</div>";
                            echo "<p class='mt-4'><a href='llistar.php' class='btn-cancel'>Retorna al llistat</a></p>";
                        // Si hi ha hagut un error, mostrem el missatge que retorna la BD
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
                        }
                        $stmt->close();
                    }
                // Si arriba un ID per la URL, mostrem la pantalla de confirmació
            } elseif (isset($_GET['id_incidencia'])) {
                } elseif (isset($_GET['id_incidencia'])) {
                    $id = $_GET['id_incidencia'];
                    //mirem que el valor sigui numeric
                    if (is_numeric($id)) {
                        // Consultem si la incidència existeix a la BD
                        $sql = "SELECT id_incidencia FROM INCIDENCIA WHERE id_incidencia = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Si existeix, mostrem la pantalla de confirmació amb el formulari
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            ?>
                             <!-- Preguntem a l'usuari si realment vol esborrar la incidència -->
                            <h1 class="delete-title">Esborrar</h1>
                            <p class="mb-4">Realment vols esborrar la incidència ID: <strong><?= htmlspecialchars($row["id_incidencia"]) ?></strong>?</p>
                            
                            <!-- Formulari de confirmació amb l'ID ocult i els botons de confirmar o cancelar -->
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