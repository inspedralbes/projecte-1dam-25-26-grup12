<?php
session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';

// Carreguem el header diferent segons el rol de l'usuari
if($_SESSION["rol"] == "tecnic"){
    include_once 'header-tecnic.php' ; 
}elseif ($_SESSION["rol"] == "admin") {
    include_once 'header.php' ;  
}elseif ($_SESSION["rol"] == "user") {
    include_once 'header-user.php' ; 
}

// Connectem a MongoDB

include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<div class="container" style="max-width: 700px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4">

        <h1 class="mb-4">Llistat d'incidències</h1>
        <hr class="mb-4">

        <?php 
        // Agafem l'ID de l'usuari que ha iniciat sessió
        $id = "";
        $user = $_SESSION["id_user"];
        
        // Consultem totes les incidències que pertanyen a l'usuari
            $sql = "SELECT  id_incidencia, descripcio FROM INCIDENCIA  WHERE id_user = $user ";
            $result = $conn->query($sql);
        ?>


        <!-- Formulari per seleccionar una incidència del desplegable -->
        <form method="post" action="">
            <div class="mb-3">
                <fieldset>
                    <!-- Desplegable amb totes les incidències de l'usuari -->
                    <label for="exampleInputEmail1" class="form-label">INCIDÈNCIA:</label>
                        <select name="id_incidencia" id="id_incidencia" class="form-select mb-4" aria-label="Default select example" required>
                            <option value="">Selecciona</option>
                            <?php while ($row = $result->fetch_assoc()) { ?>            
                                <option value="<?= $row['id_incidencia'] ?>" required>
                                    <?= htmlspecialchars($row['descripcio']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    
                    <button type="submit" class="btn btn-success px-4">Enviar</button>
                </fieldset>
            </div>
        </form>

        <?php
        // Si l'usuari ha enviat el formulari, mostrem el detall de la incidència seleccionada
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = htmlspecialchars($_POST["id_incidencia"]); ?>

            <hr class="mt-4 mb-3">
            <h2 class="mb-3">Estat de l'incidència <?= $id ?></h2>

        <?php
        // Consultem les dades de la incidència unint amb DEPARTAMENT per obtenir el nom del dept
            $sql = "SELECT  descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING (id_dept) WHERE id_incidencia = $id ";
            $result = $conn->query($sql);

            // Si existeix la incidència, mostrem descripció, departament i data
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
                 // Si no hi ha dades, mostrem un missatge informatiu
                echo "<p class='text-muted'>No hi ha dades a mostrar.</p>";
            } ?>

        <?php
        // Consultem les actuacions vinculades a la incidència, ordenades per data
            $sql = "SELECT id_actuacio, descripcio, fecha, visible FROM ACTUACIO WHERE  id_incidencia = $id ORDER BY fecha";
            $result = $conn->query($sql);

            // Si hi ha actuacions, les mostrem en una taula
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
                            <td><?= $row["id_actuacio"] ?></td><?php
                            // Si l'actuació no és visible per l'usuari, amaguen la descripció amb asteriscos
                            if($row["visible"] == 1){
                               ?><td>***********************</td><?php
                            }else{
                                // Si és visible, mostrem la descripció normalment
                                ?><td><?= htmlspecialchars($row["descripcio"]) ?></td><?php
                            } ?>
                            <td><?= $row["fecha"] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

        <?php
            } else {
                // Si no hi ha actuacions, mostrem un missatge informatiu
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