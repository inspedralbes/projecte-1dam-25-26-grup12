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
    $sql = "SELECT id_tecnic, nom FROM TECNIC ORDER BY nom";
    $result = $conn->query($sql);
    ?>

    <?php $id = ""; ?>

        <form method="POST" action="">
            <div class="mb-3">
            <fieldset>
                <legend>Tècnic</legend>

                <label for="nom"  class="form-label">Nom</label>
                <br>
                <select name="tecnic_id" id="tecnic" class="form-select" aria-label="Default select example" required>
                    <option value="" > Selecciona </option>
                    <?php while ($tec = $result->fetch_assoc()) { ?>
                        <option value="<?= $tec['id_tecnic'] ?>">
                            <?= htmlspecialchars($tec['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
                <br>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </fieldset>
        </div>
        </form>


        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = htmlspecialchars($_POST["tecnic_id"]);

    echo "<h3> Les teves incidències: </h3><br>";

    $sql = "SELECT i.id_incidencia, d.nom, i.fecha, i.prioridad, SUM(a.duracio) AS temps_total
            FROM INCIDENCIA i
            LEFT JOIN ACTUACIO a 
            ON i.id_incidencia = a.id_incidencia
            JOIN DEPARTAMENT d
            ON i.id_dept = d.id_dept
            WHERE i.fecha_fin IS NULL AND i.id_tecnic = $id
            GROUP BY i.id_incidencia, d.nom, i.fecha, i.prioridad";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {?>

        <table class="table table-striped table-dark">
            <tr>
                <th> INCIDENCIA </th>
                <th> DESCRIPCIÓ </th>
                <th> DATA </th>
                <th> TEMPS TOTAL DEDICAT</th>
                <th> PRIORITAT </th>

            <tr>

        <?php 
        while ($row = $result->fetch_assoc()) {

                if ($row["prioridad"] == "alta" ){?>
                    <tr class="table-danger">

                <?php }elseif ($row["prioridad"] == "media") { ?>
                        <tr class="table-warning">

                <?php }elseif ($row["prioridad"] == "baja") { ?>
                        <tr class="table-info">

                <?php } ?>

                <td> <?= $row["id_incidencia"] ?> </td> 
                <td> <?= $row["nom"] ?> </td> 
                <td> <?= $row["fecha"] ?> </td> 
                <td> <?= $row["temps_total"] ?> minuts </td>
                <td> <?= $row["prioridad"] ?> </td> 

            </tr>
            

            <br><br>
            
        <?php
        }
        ?>
        </table>

        <?php

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

