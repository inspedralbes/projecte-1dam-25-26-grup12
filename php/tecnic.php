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
            while ($row = $result->fetch_assoc()) {

        
                echo "<h2> INCIDÈNCIA  " .$row["id_incidencia"] . "</h2> " ;


                echo "<p> <strong>- Descripció: </strong> " . htmlspecialchars($row["descripcio"]); 
                echo "<p> <strong>- ID Departament: </strong>" . $row["id_dept"];
                echo "<p> <strong>- Data: </strong> " . $row["fecha"];
                
                echo "<br>";
                echo "<br>";

                echo "TEMPS DEDICAT TOTAL: ";

                echo "<br>";
                echo "<br>";

                echo '<input type="submit" value="Mostrar">';
                
                if (isset($_GET['id_incidencia'])) {
                // Comprovar si s'ha rebut  l'ID de la casa via GET (a la URL esborrar.php?id=XXX)
                $id = $_GET['id_incidencia'];

                    if (is_numeric($id)) {
                    // Preparar la consulta SQL per obtenir la casa a esborrar
                    $sql = "SELECT id_incidencia, descripcio, fecha FROM ACTUACIO WHERE id_incidencia = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    echo "ACTUACIONS: ";



                    }

                
            }

            



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