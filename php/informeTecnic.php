<?php
// Mantengo tus includes intactos
require_once 'connexio.php';
require_once 'header.php';
?>

<style>
    body { background-color: #e9ecef; } /* Fondo gris azulado */
    .contenedor-blanco {
        background-color: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 30px;
    }
    /* Para que las filas de prioridad se vean bien sobre el negro de la tabla */
    .table-dark .table-danger { background-color: #842029 !important; color: white; }
    .table-dark .table-warning { background-color: #664d03 !important; color: white; }
    .table-dark .table-info { background-color: #055160 !important; color: white; }
</style>

<div class="container">
    <div class="contenedor-blanco">
        <?php
        // TU CÓDIGO PHP EMPIEZA AQUÍ (SIN TOCAR)
        $sql = "SELECT id_tecnic, nom FROM TECNIC ORDER BY nom";
        $result = $conn->query($sql);
        $id = ""; 
        ?>

        <form method="POST" action="">
            <div class="mb-3">
            <fieldset>
                <legend>Tècnic</legend>
                <label for="nom" class="form-label">Nom</label>
                <br>
                <select name="tecnic_id" id="tecnic" class="form-select" required>
                    <option value="" > Selecciona </option>
                    <?php while ($tec = $result->fetch_assoc()) { ?>
                        <option value="<?= $tec['id_tecnic'] ?>">
                            <?= htmlspecialchars($tec['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
                <br>
                <button type="submit" class="btn btn-dark">Entrar</button>
            </fieldset>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = htmlspecialchars($_POST["tecnic_id"]);
            echo "<h3> Les teves incidències: </h3><br>";

            $sql = "SELECT i.id_incidencia, i.descripcio, d.nom, i.fecha, i.prioridad, IFNULL(SUM(a.duracio), 0) AS temps_total
                    FROM INCIDENCIA i
                    LEFT JOIN ACTUACIO a ON i.id_incidencia = a.id_incidencia
                    JOIN DEPARTAMENT d ON i.id_dept = d.id_dept
                    WHERE i.fecha_fin IS NULL AND i.id_tecnic = $id
                    GROUP BY i.id_incidencia, d.nom, i.fecha, i.prioridad";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th> INCIDENCIA </th>
                                <th> DESCRIPCIÓ </th>
                                <th> DEPARTAMENT </th>
                                <th> DATA </th>
                                <th> TEMPS TOTAL DEDICAT</th>
                                <th> PRIORITAT </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        while ($row = $result->fetch_assoc()) {
                            // Tu lógica de colores de fila intacta
                            if ($row["prioridad"] == "alta" ){
                                echo '<tr class="table-danger">';
                            } elseif ($row["prioridad"] == "media") {
                                echo '<tr class="table-warning">';
                            } elseif ($row["prioridad"] == "baja") {
                                echo '<tr class="table-info">';
                            } else {
                                echo '<tr>';
                            }
                            ?>
                            <td> <?= $row["id_incidencia"] ?> </td> 
                            <td> <?= $row["descripcio"] ?> </td> 
                            <td> <?= $row["nom"] ?> </td> 
                            <td> <?= $row["fecha"] ?> </td> 
                            <td> <?= $row["temps_total"] ?> minuts </td>
                            <td> <?= $row["prioridad"] ?> </td> 
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
                echo "<p>No hi ha incidencies a mostrar.</p>";
            }
        }
        $conn->close();
        ?>
    </div> </div>

<?php
require_once 'footer.php';
?>