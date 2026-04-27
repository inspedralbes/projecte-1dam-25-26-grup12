<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>
    <h1>Llistat de incidencies</h1>
    
<?php $id = ""; ?>

<form method="post" action="">
    <fieldset>
        ID_INCIDENCIA: <input type="number" name="id" required value="<?php echo $id; ?>"><br><br>

        <input type="submit" value="Enviar">
    </fieldset>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
$id = htmlspecialchars($_POST["id"]);

echo "<h3> Estat de l'incidencia $id</h3>";

// Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT id_incidencia, descripcio, id_dept, fecha FROM INCIDENCIA WHERE id_incidencia = $id ";
    $result = $conn->query($sql);

    // Comprovar si hi ha resultats
    if ($result->num_rows > 0) {

        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: " . $row["id_incidencia"] . " - Descripció: " . htmlspecialchars($row["descripcio"]) . " - ID Departament: " . $row["id_dept"] . " - Data: " . $row["fecha"];
        }

    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    // Tancar la connexió
    $conn->close();

}
?>

<?php

require_once 'footer.php';

?>