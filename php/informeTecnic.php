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

    $sql = "SELECT 
                i.id_incidencia, 
                i.descripcio, 
                i.id_dept, 
                i.fecha,
                SUM(a.duracio) AS temps_total
            FROM INCIDENCIA i
            JOIN ACTUACIO a 
                ON i.id_incidencia = a.id_incidencia
            WHERE i.id_tecnic = $id
            GROUP BY 
                i.id_incidencia, 
                i.descripcio, 
                i.id_dept, 
                i.fecha";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) { ?>
            
            <h2> INCIDÈNCIA <?= $row["id_incidencia"] ?> </h2> 

            <p><strong>- Descripció: </strong> <?= htmlspecialchars($row["descripcio"]) ?> </p> 
            <p><strong>- ID Departament: </strong> <?= $row["id_dept"] ?> </p> 
            <p><strong>- Data: </strong> <?= $row["fecha"] ?> </p> 
            
            <p>
                <strong>TEMPS DEDICAT TOTAL: </strong>
                <?= $row["temps_total"] ?> minuts
            </p>

            <br><br>
            
        <?php
        }

    } else {
        echo "<p>No hi ha incidencies a mostrar.</p>";
    }
}

    // Tancar la connexió
    $conn->close();

    
?>

<?php

require_once 'footer.php';

?>

