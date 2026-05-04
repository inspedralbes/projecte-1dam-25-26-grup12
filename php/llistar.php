<?php

//conexio a la base de dades
require_once 'connexio.php';
//header
require_once 'header.php';

//seccio de consultes SQL

    //gets per ordenar les incidencies 
    $sort = $_GET['sort'] ?? 'prioridad';
    $order = $_GET['order'] ?? 'desc';

    //gets per ordenar les incidencies no assigandes
    $sort1 = $_GET['sort1'] ?? 'fecha';
    $order1 = $_GET['order1'] ?? 'desc';

    // Consulta SQL per obtenir  les files de la taula incidencies que tinguin prioritat assignada
    // i que no tinguin data de finalització (incidencies pendents)(te un JOIN amb tipo tecnic i departament per mostrar els noms en comptes dels ids)
    $sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
    FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
    LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS NOT NULL  ORDER BY $sort $order";

    // Executar la consulta i obtenir els resultats
    $result = $conn->query($sql);

    // Consulta SQL per obtenir  les files de la taula incidencies que no tinguin prioritat assignada
    // i que no tinguin data de finalització (incidencies pendents)(te un JOIN amb tipo tecnic i departament per mostrar els noms en comptes dels ids)
    $sql1 = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
    FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
    LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS  NULL  ORDER BY $sort1 $order1";

    // Executar la consulta i obtenir els resultats
    $result1 = $conn->query($sql1);


    // Comprovar si hi ha resultats per a les incidencies pendents sense prioritat assignada i sino no mostra resulats
    if ($result1->num_rows > 0) {
        ?>
         <h1>Incidencies Pendents</h1>
        
    <!--Seccio de botons per ordenar -->
        <th>
            Data
            <a class="btn btn-primary" href="?sort1=fecha&order1=asc">↑</a>
            <a class="btn btn-primary" href="?sort1=fecha&order1=desc">↓</a>
        </th>
        <!-- Taula per mostrar les incidencies pendents sense prioritat assignada -->
        <table class="table table-striped table-dark">
            <tr >
                <th>ID</th>
                <th>Descripcio</th>
                <th>Data</th>
                <th>Departament</th>
                <th>Tipologia</th>
                <th>Prioritat</th>
                <th>Tecnic</th>
                <th>Modificar</th>
                <th>Esborrar</th>
            </tr>
        <?php

        // bucle  per mostrar les dades en format taula
        while ($row = $result1->fetch_assoc()) { 
           ?>
            <tr>
                <td><?= $row["id_incidencia"] ?></td>
                <td><?= htmlspecialchars($row["descripcio"]) ?> </td>
                <td><?= $row["fecha"] ?></td>
                <td><?= $row["departament_nom"] ?></td>
                <td><?= $row["tipologia_nom"] ?></td>
                <td><?= $row["prioridad"] ?></td>
                <td><?= $row["tecnic_nom"] ?></td>
                <td><a class="btn btn-primary" href='modificar.php?id_incidencia= <?= $row["id_incidencia"] ?> '>Modificar</a></td>
                <td><a class="btn btn-danger" href='esborrar.php?id_incidencia= <?= $row["id_incidencia"] ?> '>Esborrar</a></p></td>
  
            </tr>
        <?php
                   
                
        }
        ?>
        </table>
        <hr>
        <?php
        }




    // // Comprovar si hi ha resultats per a les incidencies pendents sense prioritat assignada i sino no mostra resulats i et dona un missatge
    if ($result->num_rows > 0) {
        ?>
        <h1>Llistat de Incidencies</h1>
        <th>
            Prioritat
            <a class="btn btn-primary" href="?sort=prioridad&order=asc">↑</a>
            <a class="btn btn-primary" href="?sort=prioridad&order=desc">↓</a>
            </th>
            <th>
            Data
            <a class="btn btn-primary" href="?sort=fecha&order=asc">↑</a>
            <a class="btn btn-primary" href="?sort=fecha&order=desc">↓</a>
        </th>
        <table class="table table-striped table-dark">
            <!--Seccio de botons per ordenar -->
            <tr >
                <th>ID</th>
                <th>Descripcio</th>
                <th>Data</th>
                <th>Departament</th>
                <th>Tipologia</th>
                <th>Prioritat</th>
                <th>Tecnic</th>
                <th>Modificar</th>
                <th>Esborrar</th>
            </tr>
        <?php
        // bucle  per mostrar les dades en format taula
        while ($row = $result->fetch_assoc()) { 
            // Condicional per pintar les files de la taula de diferents colors segons la prioritat de la incidencia
            if ($row["prioridad"] == "alta" ){
            ?>
                <tr class="table-danger">
        <?php }elseif ($row["prioridad"] == "media") { ?>
                <tr class="table-warning">
        <?php }elseif ($row["prioridad"] == "baja") { ?>
                <tr class="table-info">
        <?php } ?> 

                <td><?= $row["id_incidencia"] ?></td>
                <td><?= htmlspecialchars($row["descripcio"]) ?> </td>
                <td><?= $row["fecha"] ?></td>
                <td><?= $row["departament_nom"] ?></td>
                <td><?= $row["tipologia_nom"] ?></td>
                <td><?= $row["prioridad"] ?></td>
                <td><?= $row["tecnic_nom"] ?></td>
                <td><a class="btn btn-primary" href='modificar.php?id_incidencia= <?= $row["id_incidencia"] ?> '>Modificar</a></td>
                <td><a class="btn btn-danger" href='esborrar.php?id_incidencia= <?= $row["id_incidencia"] ?> '>Esborrar</a></p></td>
  
            </tr>
        <?php
                   
                
        }
        ?>
        </table>
        <?php
        } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    // Tancar la connexió
    $conn->close();
    ?>

 <?php

require_once 'footer.php';

?>