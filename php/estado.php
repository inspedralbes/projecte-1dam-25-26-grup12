<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>
    <h1>Llistat d'incidències</h1>
    <br>
    
<?php $id = ""; ?>

<form method="post" action="">
    <div class="mb-3">
    <fieldset>
        <label for="exampleInputEmail1" class="form-label">ID INCIDÈNCIA: </label>
        <input type="number" class="form-control" name="id" required value="<?php echo $id; ?>"><br>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </fieldset>
</div>
</form>
<br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
$id = htmlspecialchars($_POST["id"]); ?>

    <h2> Estat de l'incidència <?= $id ?> </h2>
    <br>

<?php
// Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT  descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING (id_dept) WHERE id_incidencia = $id ";

    $result = $conn->query($sql);

    // Comprovar si hi ha resultats
    if ($result->num_rows > 0) {

        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) { ?>
            <div>
                <ul class="list-group">
                    <li class="list-group-item "><b>Descripció: </b> <?= htmlspecialchars($row["descripcio"]) ?></li>
                    <li class="list-group-item"><b>Departament: </b> <?= $row["nom"] ?></li>
                    <li class="list-group-item"><b>Data: </b> <?= $row["fecha"] ?></li>
                </ul>
                <br>
            </div>

            <?php
        }
    }else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }?>

    <?php

            
    // Preparar la consulta SQL per obtenir la casa a esborrar
    $sql = "SELECT id_actuacio, descripcio, fecha FROM ACTUACIO WHERE visible = 0 and id_incidencia = $id ORDER BY fecha";
    $result = $conn->query($sql);
    
    // Comprovar si s'ha trobat la casa
    if ($result->num_rows > 0) { ?>
            
        <h2> ACTUACIONS: </h2>
        <table class="table table-striped table-dark">
            
                <tr>
                    <th> ID </th>
                    <th> Descripció </th>
                    <th> Data </th>
                </tr>
                

        <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>
                    <td> <?= $row["id_actuacio"] ?> </td>
                    <td> <?= htmlspecialchars($row["descripcio"]) ?> </td>
                    <td> <?= $row["fecha"] ?> </td>
                </tr>
                <br>

            <?php
            }
            ?>
            </table>

                    
<?php
    }else {
        echo "<p>No hi ha actuacions a mostrar.</p>";
    }

            // Tancar la connexió
            $conn->close();

}
?>

<?php

require_once 'footer.php';

?>