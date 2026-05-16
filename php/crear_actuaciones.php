<?php
session_start();// Iniciem la sessió


// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();

// Si el rol no és admin, no té permisos per accedir a aquesta pàgina i i redirigim a index.php.
}elseif (!$_SESSION["rol"] == "tecnic") {
   if ($_SESSION["rol"] == "admin") {
    exit(); 
   }
    header("Location: index.php");
    exit();     
}


// Connectem a la BD
require_once 'connexio.php';

// Carreguem el header diferent segons el rol que té l'ususari al iniciar sessio
if($_SESSION["rol"] == "tecnic"){
    include_once 'header-tecnic.php' ; 
}elseif ($_SESSION["rol"] == "admin") {
    include_once 'header.php' ;  
}elseif ($_SESSION["rol"] == "user") {
    include_once 'header-user.php' ; 
}


include_once 'mongo.php'; // Connectem a MongoDB

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova actuació a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_actuaciones($conn) //Funcio per crear actuacions
{
    // Agafem les dades enviades pel formulari
    $descripcio = $_POST['descripcio'];       // Descripció de l'actuació
    $id_incidencia = $_POST['id_incidencia']; // ID de la incidència a la qual pertany
    $duracio = $_POST['duracio'];             // Duració en minuts de l'actuació
    $visible = isset($_POST['visible']) ? 0 : 1; // Si el checkbox és marcat, l'actuació és visible per l'usuari

   


    // Preparem la consulta SQL per inserir la nova actuació a la BD
    $sql = "INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha, duracio, visible) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("isii", $id_incidencia, $descripcio, $duracio, $visible);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) { ?>
        <!-- Misstage d'exit al crear l'actuacio -->
        <div class="alert alert-success mt-3">Actuació creada amb èxit!</div>
        <!-- Botó per tornar al detall de la incidència -->
        <p class="mt-3"><a class="btn btn-dark" href='actuacions.php?id_incidencia=<?= $id_incidencia ?>'>Retorna</a></p>  
    
    <?php 
    } else { ?>
    <!-- Si hi ha hagut un error, mostrem el missatge que retorna la BD -->
      <div class="alert alert-danger mt-3">Error al crear la Actuació: <?=  htmlspecialchars($stmt->error) ?></div>
    <?php 
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>

<!-- Estils del formulari -->
<style>

    .main-container {
        background-color: white;
        border-radius: 15px;
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
            // Si el formulari s'ha enviat, cridem la funció per crear l'actuació
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                crear_actuaciones($conn);
            // Si arriba un ID d'incidència per la URL, mostrem el formulari
            } elseif (isset($_GET['id_incidencia'])) {
                $id_incidencia = $_GET['id_incidencia'];
                // Comprovar si l'ID és un número vàlid
                if (is_numeric($id_incidencia)) {
        ?>
                    <!-- Formulari per crear una nova actuació -->
                    <form name="actuacion" method="POST" action="crear_actuaciones.php" onsubmit="return valActua()">
                        <div class="mb-3">
                            <!-- Camp de text per escriure la descripció de l'actuació -->
                            <label for="descripcio" class="form-label fw-bold">Descripció</label>
                            <textarea name="descripcio" class="form-control" rows="5" placeholder="Escriu els detalls de l'actuació..." required></textarea>
                            <!-- Camp ocult per enviar l'ID de la incidència amb el formulari -->
                            <input type="hidden" name="id_incidencia" value="<?= htmlspecialchars($id_incidencia) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <!-- Camp per introduir la duració de l'actuació en minuts -->
                                <label for="duracio" class="form-label fw-bold">Duració (minuts)</label>
                                <input type="number" class="form-control" name="duracio" placeholder="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                                <div class="form-check form-switch">
                                    <!-- Checkbox per indicar si l'actuació serà visible per l'usuari -->
                                    <input type="checkbox" class="form-check-input" name="visible" id="visible" required checked>
                                    <label for="visible" class="form-check-label fw-bold ms-2">Visible per l'usuari</label>
                                </div>
                            </div>
                        </div>
                        <!-- Botons per enviar el formulari o cancelar i tornar a la incidència -->
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