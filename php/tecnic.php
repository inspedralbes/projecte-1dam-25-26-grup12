<?php

require_once 'header.php';
require_once 'connexio.php';

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>

<body>
    <h1>Identifica't</h1>

<?php
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
            ?>
                <br><h3> Les teves incidències: </h3><br>
            <?php

            // Consulta SQL per obtenir totes les files de la taula 'cases'
            $sql = "SELECT id_incidencia, descripcio, id_dept, fecha
            FROM INCIDENCIA WHERE id_tecnic = $id AND fecha_fin IS NULL" ;
            $result = $conn->query($sql);

            
            // Comprovar si hi ha resultats
            if ($result->num_rows > 0) {

                // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
                while ($row = $result->fetch_assoc()) { ?>
                
                    <h3> Incidència  <?= $row["id_incidencia"] ?> </h3> 
                    <br>
                    <a href='actuacions.php?id_incidencia= <?= $row["id_incidencia"] ?> ' class="btn btn-primary">Mostrar</a>
                    <br>
                    <br>

                


                <?php
                }

            }else {
                    echo "<p>No hi ha incidencies a mostrar.</p>";
            }

}
 
?>



<?php

require_once 'footer.php';
// Tancar la connexió
    $conn->close();
?>