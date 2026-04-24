<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
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
    $departamento = $_POST['departamento'];
    $fecha = $_POST['fecha'];
    $tipologia = $_POST['tipologia'];


    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO INCIDENCIA (descripcio, id_dept, fecha, id_tipo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("ssss", $descripcio, $departamento, $fecha, $tipologia);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
    } else {
        echo "<p class='error'>Error al crear la Incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear</title>
</head>

<body>
    <h1>Registrar incidencia</h1>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>
        <form method="POST" action="formulari.php">
            <fieldset>
                <legend>Incidencia</legend>
                <label for="nom">Descripcio</label>
                <input type="text" id="descripcio" name="descripcio">
                <label for="nom">Departament</label>
                <input type="number" id="departamento" name="departamento">
                <label for="nom">Fecha</label>
                <input type="date" id="fecha" name="fecha">
                <label for="nom">Tipologia</label>
                <input type="number" id="tipologia" name="tipologia">
                <input type="submit" value="Crear">
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>
    <div id="menu">
        <hr>
        <p><a href="index.php">Portada</a> </p>
        <p><a href="llistar.php">Llistar</a></p>
        <p><a href="crear.php">Crear</a></p>
    </div>
</body>

</html>

