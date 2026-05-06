<?php

//Conexion a la base de dades i header
require_once 'connexio.php';
require_once 'header.php';
include_once 'mongo.php';


/**
 * Funció que llegeix els paràmetres del formulari i crea una nova incidencia a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    // Obtenir la descripció i el departament del formulari, i netejar les dades per evitar injeccions SQL
    $descripcio = htmlspecialchars($_POST['descripcio']);
    $departamento = htmlspecialchars($_POST['id_dept']);
    $data = date('Y-m-d H:i:s');

    //Comprovar que els camps no estiguin buits, i si ho estan mostrar un missatge d'error i un enllaç per tornar al formulari
    if (empty($descripcio) or empty($departamento)) {
        echo "<p class='error'>Tots els camps són obligatoris.</p>";
        echo "<p><a href='formulari.php'>Torna al formulari</a></p>";
        return;

    }

   
     
    // Preparar la consulta SQL per inserir una nova incidenccia(el valor de data es automatic amb now)
    $sql = "INSERT INTO INCIDENCIA (descripcio, id_dept, fecha) VALUES (?, ?, ?)";
    //Preparacio de la consulta.
    $stmt = $conn->prepare($sql);
    // Vincular els paràmetres a la consulta preparada  
    $stmt->bind_param("sis", $descripcio, $departamento, $data);

    


    

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        // Consulta SQL per obtenir l'id de la incidencia creada a partir de la descripcio i el departament.
        $sql1 = "SELECT id_incidencia FROM INCIDENCIA WHERE descripcio = ? AND id_dept = ? AND fecha = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("sis", $descripcio, $departamento, $data);
        $stmt1->execute();
        $result = $stmt1->get_result();
        $row = $result->fetch_assoc();  
        $id_incidencia = $row['id_incidencia']; 



        echo "<p class='info'>Incidencia creada amb èxit!</p>";
        echo "<p>ID de la incidencia creada: " . htmlspecialchars($id_incidencia) . "</p>";
        echo "<p><a href='index.php'>Retorna</a></p>";  
    } else {
        echo "<p class='error'>Error al crear la Incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>


    <h1>Registrar incidència</h1><br>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la incidencia
        crear_incidencia($conn);

    //Mostrem el formulari per crear una nova incidencia    
    } else {
    
        //Consulta per a recuperar les dades de departamets
        $sql = "SELECT id_dept, nom FROM DEPARTAMENT";
        $departaments = $conn->query($sql);

        
        ?>
        <form method="POST" action="formulari.php">
            <fieldset>
                <div class="mb-3">
                <label for="descripcio" class="form-label">Descripció</label><br>
                <textarea name="descripcio" class="form-control" rows="5" cols="50"></textarea><br>


                <label for="departament" class="form-label">Departament</label>
                <!-- Bucle per mostrar les opcions del select de departaments a partir de les dades obtingudes de la base de dades -->
                <select name="id_dept" id="id_dept" class="form-select" aria-label="Default select example" required>
                    <option  value="" > Selecciona </option>
                    <?php while ($dep = $departaments->fetch_assoc()) { ?>
                            <option value="<?= $dep['id_dept'] ?>" required>
                            <?= htmlspecialchars($dep['nom']) ?>
                            </option>
                    <?php } ?>
                </select>
               <br>
                <button type="submit" class="btn btn-primary">Crear</button>

                </div>
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>
   
<?php

require_once 'footer.php';

?>

