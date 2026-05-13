<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<div class="container" style="max-width: 700px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4">

        <h1 class="mb-4">Llistat d'incidències</h1>
        <hr class="mb-4">

        <?php 
        $id = "";
        $user = $_SESSION["id_user"];
        
        
            $sql = "SELECT  id_incidencia, descripcio FROM INCIDENCIA  WHERE id_user = $user ";
            $result = $conn->query($sql);
        ?>



        <form method="post" action="">
            <div class="mb-3">
                <fieldset>
                    <label for="exampleInputEmail1" class="form-label">ID INCIDÈNCIA:</label>
                        <select name="id_incidencia" id="id_incidencia" class="form-select mb-4" aria-label="Default select example" required>
                            <option value="">Selecciona</option>
                            <?php while ($row = $result->fetch_assoc()) { ?>            
                                <option value="<?= $row['id_incidencia'] ?>" required>
                                    <?= htmlspecialchars($row['descripcio']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    
                    <button type="submit" class="btn btn-dark px-4">Enviar</button>
                </fieldset>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = htmlspecialchars($_POST["id_incidencia"]); ?>

            <hr class="mt-4 mb-3">
            <h2 class="mb-3">Estat de l'incidència <?= $id ?></h2>

        <?php
            $sql = "SELECT  descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING (id_dept) WHERE id_incidencia = $id ";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <div class="mb-4">
                        <ul class="list-group list-group-flush border rounded-3">
                            <li class="list-group-item"><b>Descripció: </b> <?= htmlspecialchars($row["descripcio"]) ?></li>
                            <li class="list-group-item"><b>Departament: </b> <?= $row["nom"] ?></li>
                            <li class="list-group-item"><b>Data: </b> <?= $row["fecha"] ?></li>
                        </ul>
                    </div>
                <?php }
            } else {
                echo "<p class='text-muted'>No hi ha dades a mostrar.</p>";
            } ?>

        <?php
            $sql = "SELECT id_actuacio, descripcio, fecha FROM ACTUACIO WHERE visible = 0 and id_incidencia = $id ORDER BY fecha";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) { ?>

                <h2 class="mb-3">Actuacions:</h2>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripció</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row["id_actuacio"] ?></td>
                            <td><?= htmlspecialchars($row["descripcio"]) ?></td>
                            <td><?= $row["fecha"] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

        <?php
            } else {
                echo "<p class='text-muted'>No hi ha actuacions a mostrar.</p>";
            }

            $conn->close();
        }
        ?>

    </div>
</div>

<?php

require_once 'footer.php';

?>