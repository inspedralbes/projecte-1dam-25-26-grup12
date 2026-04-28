<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

    <h1>Llistat de Incidencies</h1>
    <?php

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT id_incidencia, descripcio, fecha FROM INCIDENCIA WHERE fecha_fin IS NULL";
    $result = $conn->query($sql);

    // Comprovar si hi ha resultats
    if ($result->num_rows > 0) {

        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: " . $row["id_incidencia"] . " - Descripcio: " . htmlspecialchars($row["descripcio"]) . " - Fecha: "  . $row["fecha"] . "";
            echo " <a href='modificar.php?id_incidencia=" . $row["id_incidencia"] . "'>Modificar</a></p>";
            echo " <a href='esborrar.php?id_incidencia=" . $row["id_incidencia"] . "'>Esborrar</a></p>";
        }

    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    // Tancar la connexió
    $conn->close();
    ?>

    <div id="menu">
        <hr>
        <p><a href="index.php">Portada</a> </p>
        <p><a href="llistar.php">Llistar</a></p>
        <p><a href="crear.php">Crear</a></p>
    </div>

</body>

</html>