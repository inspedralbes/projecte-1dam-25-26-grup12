<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

    $sort = $_GET['sort'] ?? 'prioridad';

    $order = $_GET['order'] ?? 'desc';

    $sort1 = $_GET['sort1'] ?? 'fecha';

    $order1 = $_GET['order1'] ?? 'desc';

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
    FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
    LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS NOT NULL  ORDER BY $sort $order";

    $result = $conn->query($sql);

    $sql1 = "SELECT i.id_incidencia, i.descripcio, i.fecha, d.nom AS departament_nom, t.nom AS tipologia_nom, i.prioridad, tec.nom AS tecnic_nom
    FROM INCIDENCIA AS i LEFT JOIN DEPARTAMENT AS d ON i.id_dept=d.id_dept LEFT JOIN TIPO AS t ON i.id_tipo=t.id_tipo  
    LEFT JOIN TECNIC AS tec ON i.id_tecnic=tec.id_tecnic WHERE fecha_fin IS NULL AND prioridad IS  NULL  ORDER BY $sort1 $order1";


    $result1 = $conn->query($sql1);


    // Comprovar si hi ha resultats
    if ($result1->num_rows > 0) {
        ?>
         <h1>Incidencies Pendents</h1>
        
        <th>
            Data
            <a class="btn btn-primary" href="?sort1=fecha&order1=asc">↑</a>
            <a class="btn btn-primary" href="?sort1=fecha&order1=desc">↓</a>
        </th>
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
        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
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




    // Comprovar si hi ha resultats
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
        // Llistar els resultats. ATENCIÓ, heu de construir el codi HTML d'una llista correctament
        while ($row = $result->fetch_assoc()) { 
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