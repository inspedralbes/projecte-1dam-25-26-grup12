<?php
require_once 'connexio.php';
require_once 'header.php' ;
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
        <p class='info'>Actuacio creada amb èxit!</p>
        <p><a href='actuacions.php?id_incidencia=<?= $id_incidencia ?> '>Retorna</a></p>  
    
    <?php 
    } else { ?>
      <p class='error'>Error al crear la Actuacio: <?=  htmlspecialchars($stmt->error) ?> </p>
    <?php 
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>
    <h1>Crear Actuacio</h1>

    <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            crear_actuaciones($conn);
    
        } elseif (isset($_GET['id_incidencia'])) {
            $id_incidencia = $_GET['id_incidencia'];
            // Comprovar si l'ID és un número vàlid
            if (is_numeric($id_incidencia)) {
    ?>
                <form method="POST" action="crear_actuaciones.php">
                    <fieldset>
                        <legend>Actuacion</legend>

                        <label for="descripcio">Descripcio</label><br>
                        <textarea name="descripcio" rows="10" cols="50"></textarea>
                        <input type="hidden" name="id_incidencia" value=" <?= htmlspecialchars($id_incidencia) ?> ">
                        <label for="duracio">Duracio</label>
                        <input type="number" name="duracio">
                        <label for="visible">Visible</label>
                        <input type="checkbox" name="visible">
                        <input type="submit" value="Crear">
                    </fieldset>
                </form>

                        
                        
    <?php   }
            
                

        }
    ?>




    
<?php
require_once 'footer.php';
?>