<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    // Obtenir el nom de la casa del formulari
    $descripcio = $_POST['descripcio'];
    $departamento = $_POST['id_dept'];
    $tipologia = $_POST['id_tipo'];


    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO INCIDENCIA (descripcio, id_dept, fecha, id_tipo) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("sii", $descripcio, $departamento, $tipologia);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
        echo "<p><a href='index.php'>Retorna</a></p>";  
    } else {
        echo "<p class='error'>Error al crear la Incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>


    <h1>Registrar incidencia</h1>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        $sql = "SELECT id_dept, nom FROM DEPARTAMENT";
        $departaments = $conn->query($sql);

        $sql1 = "SELECT id_tipo, nom FROM TIPO";
        $tipologia = $conn->query($sql1);
        ?>
        <form method="POST" action="formulari.php">
            <fieldset>
                <legend>Incidencia</legend>

                <label for="descripcio">Descripcio</label>
                <textarea name="descripcio" rows="10" cols="50"></textarea>
                <label for="departament">Departament</label>
                <select name="id_dept" id="id_dept">
                    <option value=""> Selecciona </option>
                    <?php while ($dep = $departaments->fetch_assoc()) { ?>
                        <option value="<?= $dep['id_dept'] ?>">
                            <?= htmlspecialchars($dep['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
                <label for="nom">Tipologia</label>
                <select name="id_tipo" id="id_tipo">
                    <option value=""> Selecciona </option>
                    <?php while ($tip = $tipologia->fetch_assoc()) { ?>
                        <option value="<?= $tip['id_tipo'] ?>">
                            <?= htmlspecialchars($tip['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
                
                
                
                <input type="submit" value="Crear">
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>
   
<?php

require_once 'footer.php';

?>

