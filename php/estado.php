<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>
    <h1>Llistat d'incidències</h1>
    
<?php $id = ""; ?>

<form method="post" action="">
    <fieldset>
        ID INCIDÈNCIA: <input type="number" name="id" required value="<?php echo $id; ?>"><br><br>

        <input type="submit" value="Enviar">
    </fieldset>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
$id = htmlspecialchars($_POST["id"]);

echo "<h2> Estat de l'incidencia $id</h2>";

// Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT id_incidencia, descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING (id_dept) WHERE id_incidencia = $id ";

    $result = $conn->query($sql);

    // Comprovar si hi ha resultats
    if ($result->num_rows > 0) {

        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<b> - ID: </b> " . $row["id_incidencia"] . "<br><br>";
            echo "<b>  - Descripció: </b> " . htmlspecialchars($row["descripcio"]) . "<br><br>";
            echo "<b>  - Departament: </b> " . $row["nom"] . "<br><br>";
            echo "<b>  - Data: </b> " . $row["fecha"] . "<br><br>";
            echo "<br>";
            echo "</div>";
        }
    }else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

            
    // Preparar la consulta SQL per obtenir la casa a esborrar
    $sql = "SELECT id_actuacio, descripcio, fecha FROM ACTUACIO WHERE visible = 0 and id_incidencia = $id";
    $result = $conn->query($sql);
    
    // Comprovar si s'ha trobat la casa
    if ($result->num_rows > 0) {
            
        echo "<h2> ACTUACIONS: </h2> ";
                

        while ($row = $result->fetch_assoc()) {
            echo "<table style='border-collapse:collapse; border:1px solid;'>";
            
                echo "<tr>";
                    echo "<th> ID </th>";
                    echo "<th> Descripció </th>";
                    echo "<th> Data </th>";
                echo "</tr>";

                echo "<tr>";
                    echo "<td>" . $row["id_actuacio"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["descripcio"]) . "</td>";
                    echo "<td>" . $row["fecha"] . "</td>";
                echo "</tr>";
            
            echo "</table>";
            echo "<br>";
            echo "<br>";
            }

                    

    }else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }



            // Tancar la connexió
            $conn->close();

}
?>

<?php

require_once 'footer.php';

?>