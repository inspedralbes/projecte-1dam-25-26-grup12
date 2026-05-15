<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}

require_once 'connexio.php';
include_once 'header.php';
include_once 'mongo.php';
?>



<div class="container">
    <div class="contenedor-blanco">
        <?php
        $sql = "SELECT id_tecnic, nom FROM TECNIC ORDER BY nom";
        $result = $conn->query($sql);
        $id = "";

        $start = isset($_GET['start']) ? (int)$_GET['start'] : 1;
        $limit = 5;
        $page = ($start - 1) * $limit;
        ?>

        <form method="POST" action="">
            <div class="mb-3">
            <fieldset>
                <legend>Tècnic</legend>
                <label for="nom" class="form-label">Nom</label>
                <br>
                <select name="tecnic_id" id="tecnic" class="form-select" required>
                    <option value="">Selecciona</option>
                    <?php while ($tec = $result->fetch_assoc()) { ?>
                        <option value="<?= $tec['id_tecnic'] ?>">
                            <?= htmlspecialchars($tec['nom']) ?>
                        </option>
                    <?php } ?>
                </select>
                <br>
                <button type="submit" class="btn btn-success">Entrar</button>
            </fieldset>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = htmlspecialchars($_POST["tecnic_id"]);
            echo "<br><h3> Les teves incidències: </h3><br>";

            $sql = "SELECT i.id_incidencia, i.descripcio, d.nom, i.fecha, i.prioridad, IFNULL(SUM(a.duracio), 0) AS temps_total
                    FROM INCIDENCIA i
                    LEFT JOIN ACTUACIO a ON i.id_incidencia = a.id_incidencia
                    JOIN DEPARTAMENT d ON i.id_dept = d.id_dept
                    WHERE i.fecha_fin IS NULL AND i.id_tecnic = $id
                    GROUP BY i.id_incidencia, d.nom, i.fecha, i.prioridad
                    LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $page);
            $stmt->execute();
            $result = $stmt->get_result();

            $countSql = "SELECT COUNT(*) as total FROM INCIDENCIA WHERE fecha_fin IS NULL AND id_tecnic = $id";
            $countResult = $conn->query($countSql);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $limit);

            if ($result->num_rows > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>INCIDENCIA</th>
                                <th>DESCRIPCIÓ</th>
                                <th>DEPARTAMENT</th>
                                <th>DATA</th>
                                <th>TEMPS TOTAL DEDICAT</th>
                                <th>PRIORITAT</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()) {
                            if ($row["prioridad"] == "alta") {
                                echo '<tr class="table-danger">';
                            } elseif ($row["prioridad"] == "media") {
                                echo '<tr class="table-warning">';
                            } elseif ($row["prioridad"] == "baja") {
                                echo '<tr class="table-info">';
                            } else {
                                echo '<tr>';
                            }
                        ?>
                            <td><?= $row["id_incidencia"] ?></td>
                            <td><?= $row["descripcio"] ?></td>
                            <td><?= $row["nom"] ?></td>
                            <td><?= $row["fecha"] ?></td>
                            <td><?= $row["temps_total"] ?> minuts</td>
                            <td><?= $row["prioridad"] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <?php if ($start > 1): ?>
                        <a href="?start=<?= $start - 1 ?>" class="btn btn-outline-dark">← Anterior</a>
                    <?php endif; ?>

                    <?php
                    $maxButtons = 5;
                    $inicio = max(1, $start - 2);
                    $fin = min($totalPages, $inicio + $maxButtons - 1);
                    $inicio = max(1, $fin - $maxButtons + 1);
                    for ($i = $inicio; $i <= $fin; $i++): ?>
                        <a href="?start=<?= $i ?>"
                           class="btn <?= ($i == $start) ? 'btn-success fw-bold' : 'btn-outline-success' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($start < $totalPages): ?>
                        <a href="?start=<?= $start + 1 ?>" class="btn btn-outline-dark">Següent →</a>
                    <?php endif; ?>
                </div>

            <?php } else {
                echo "<p>No hi ha incidencies a mostrar.</p>";
            }
        }
        $conn->close();
        ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>