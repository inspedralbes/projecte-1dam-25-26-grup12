<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.


function tancar_incidencia($conn){
    $id = $_POST['id_incidencia'];
    $sql= "UPDATE  INCIDENCIA (fecha_fin) VALUES (NOW())";
    $sql= "UPDATE  INCIDENCIA SET fecha_fin = NOW() WHERE id_incidencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) { ?>
        <p class='info'>Incidencia tancada amb èxit!</p>
        <p><a href='index.php'>Retorna</a></p>  
        <?php
    } else { ?>
       <p class='error'>Error al tancar la Incidencia:  <?= htmlspecialchars($stmt->error) ?> </p>
       <?php
    }

    // Tancar la declaració i la connexió
    $stmt->close();
}   

?>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    tancar_incidencia($conn,$id);

} elseif (isset($_GET['id_incidencia'])){
    $id = $_GET['id_incidencia'];

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT id_incidencia, descripcio, nom, fecha FROM INCIDENCIA JOIN DEPARTAMENT USING(id_dept) WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {
        

               while ($row = $result->fetch_assoc()) {
                ?>

                <br>
                <br>
                <div>
                <ul class="list-group">
                    <li class="list-group-item "><b>ID: </b> <?= htmlspecialchars($row["id_incidencia"]) ?></li>
                    <li class="list-group-item "><b>Descripció: </b> <?= htmlspecialchars($row["descripcio"]) ?></li>
                    <li class="list-group-item"><b>Departament: </b> <?= $row["nom"] ?></li>
                    <li class="list-group-item"><b>Data: </b> <?= $row["fecha"] ?></li>
                </ul>
                <br>
            </div>
            <br>
                    <a href="crear_actuaciones.php?id_incidencia=<?= $row["id_incidencia"] ?>" class="btn btn-primary" >Crear actuacio</a></p>
                    <form method='POST' action='actuacions.php'> 

                    <input type='hidden' name='id_incidencia'  value=" <?= htmlspecialchars($id) ?> ">
                    <button type="submit" class="btn btn-primary">Tancar Incidència</button>       
                    </form>
                    <br>
                    <br>
                    
                    

         <?php  }

            } 

        // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT descripcio, fecha, duracio FROM ACTUACIO WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {?>

                <h3> ACTUACIONS: </h3>
                <table class="table table-striped table-dark">
            
                <tr>
                    <th> Descripció </th>
                    <th> Data </th>
                    <th> Temps dedicat </th>
                </tr>
                

        <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>
                    <td> <?= htmlspecialchars($row["descripcio"]) ?> </td>
                    <td> <?= $row["fecha"] ?> </td>
                    <td> <?= $row["duracio"] ?> </td>

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
}


require_once 'footer.php';

?>