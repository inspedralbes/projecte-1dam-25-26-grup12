<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "tecnic")) {
   
    header("Location: index.php");
    exit();  
}



require_once 'connexio.php';
require_once 'header.php' ;
include_once 'mongo.php';
/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_actuaciones($conn)
{
    // Obtenir el nom de la casa del formulari
    $descripcio = $_POST['descripcio'];
    $id_incidencia = $_POST['id_incidencia'];
    $duracio = $_POST['duracio'];
    $visible = isset($_POST['visible']) ? 0 : 1;
   


    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha, duracio, visible) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("isii", $id_incidencia, $descripcio, $duracio, $visible);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) { ?>
        <div class="alert alert-success mt-3">Actuació creada amb èxit!</div>
        <p class="mt-3"><a class="btn btn-dark" href='actuacions.php?id_incidencia=<?= $id_incidencia ?>'>Retorna</a></p>  
    
    <?php 
    } else { ?>
      <div class="alert alert-danger mt-3">Error al crear la Actuació: <?=  htmlspecialchars($stmt->error) ?></div>
    <?php 
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>

<style>
    body {
        background-color: #e9ecef; /* Fondo gris azulado */
    }
    .main-container {
        background-color: white;
        border-radius: 15px; /* Bordes redondeados */
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 30px;
        margin-bottom: 30px;
        max-width: 700px; 
    }
    h1 {
        font-weight: 700;
        color: #212529;
    }
    hr {
        border-top: 1px solid #dee2e6;
        opacity: 1;
        margin-bottom: 25px;
    }
</style>

<div class="container d-flex justify-content-center">
    <div class="col-12 main-container">
        
        <h1>Registrar actuació</h1>
        <hr>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                crear_actuaciones($conn);
        
            } elseif (isset($_GET['id_incidencia'])) {
                $id_incidencia = $_GET['id_incidencia'];
                // Comprovar si l'ID és un número vàlid
                if (is_numeric($id_incidencia)) {
        ?>
                    <form name="actuacion" method="POST" action="crear_actuaciones.php" onsubmit="return valActua()">
                        <div class="mb-3">
                            <label for="descripcio" class="form-label fw-bold">Descripció</label>
                            <textarea name="descripcio" class="form-control" rows="5" placeholder="Escriu els detalls de l'actuació..." required></textarea>
                            <input type="hidden" name="id_incidencia" value="<?= htmlspecialchars($id_incidencia) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duracio" class="form-label fw-bold">Duració (minuts)</label>
                                <input type="number" class="form-control" name="duracio" placeholder="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="visible" id="visible" required checked>
                                    <label for="visible" class="form-check-label fw-bold ms-2">Visible per l'usuari</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-start">
                            <button type="submit" class="btn btn-dark px-4 py-2">Crear Actuació</button>
                            <a href="actuacions.php?id_incidencia=<?= $id_incidencia ?>" class="btn btn-outline-secondary px-4 py-2 ms-2">Cancelar</a>
                        </div>
                    </form>
                            
        <?php   }
            }
        ?>

    </div>
</div>

<?php
require_once 'footer.php';
?>