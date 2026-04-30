<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.
require_once 'header.php';
/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */



?>

<?php

    // Consulta SQL per obtenir totes les files de la taula 'tecnic'
    $sql = "SELECT id_tecnic, nom FROM TECNIC";
    $result = $conn->query($sql);
    ?>

    <?php $id = ""; ?>

        <form method="POST" action="">
            <fieldset>
                <legend>Tècnic</legend>

                <label for="nom">Nom</label>
                <select name="tecnic_id" id="tecnic">
                    <option value=""> Selecciona </option>
                    <?php while ($tec = $result->fetch_assoc()) { ?>
                        <option value="<?= $tec['id_tecnic'] ?>">
                            <?= htmlspecialchars($tec['nom']) ?>
                        </option>
                    <?php } ?>
                </select>

                <input type="submit" value="Entrar">
            </fieldset>
        </form>


        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = htmlspecialchars($_POST["tecnic_id"]);

        echo "<h3> Les teves incidències: </h3><br>";

        // Consulta SQL per obtenir totes les files de la taula 'cases'
        $sql = "SELECT id_incidencia, descripcio, id_dept, fecha
        FROM INCIDENCIA WHERE id_tecnic = $id" ;
        $result = $conn->query($sql);

        // Comprovar si hi ha resultats
        if ($result->num_rows > 0) {

        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) { ?>
            
            <h2> INCIDÈNCIA  <?= $row["id_incidencia"] ?> </h2> 

            <p> <strong>- Descripció: </strong> <?= htmlspecialchars($row["descripcio"]) ?> </p> 
            <p> <strong>- ID Departament: </strong> <?= $row["id_dept"] ?> </p> 
            <p> <strong>- Data: </strong> <?= $row["fecha"] ?> </p> 
            
            <br>
            <br>

            <p>TEMPS DEDICAT TOTAL: </p>
            
        <?php
        }

    } else {
        echo "<p>No hi ha incidencies a mostrar.</p>";
    }

    // Tancar la connexió
    $conn->close();

}
    
?>

<?php

require_once 'footer.php';

?>

