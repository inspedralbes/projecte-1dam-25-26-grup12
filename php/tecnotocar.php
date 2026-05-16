<?php
session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
//Si no és l'usuari no es admin, redirigim a index.php
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



//Conectem a la BD i a MongoDB i carreguem el header
include_once 'header.php';
require_once 'connexio.php';
include_once 'mongo.php';
?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-4 mb-4">
                <h1 class="text-center fw-semibold mb-4" style="font-size:2rem;">Identifica't</h1>

                <?php

                $sql = "SELECT id_tecnic, nom FROM TECNIC ORDER BY nom";
                $result = $conn->query($sql);
                ?>

                <form method="POST" action="">
                    <fieldset class="border p-4 rounded-3">
                    <!--Consultem tots els tècnics de la BD ordenats per nom per mostrar-los al desplegable-->
                        <legend class="w-auto px-2 fw-semibold" style="font-size:1.1rem;">Tècnic</legend>

                        <!-- Formulari per seleccionar el tècnic que vol accedir -->
                        <div class="mb-3">
                            <!-- Desplegable amb tots els tècnics disponibles -->
                            <label for="tecnic" class="form-label fw-medium">Selecciona el teu nom</label>
                            <select name="tecnic_id" id="tecnic" class="form-select form-select-lg" required>
                                <option value="">Selecciona</option>
                                <?php while ($tec = $result->fetch_assoc()) { ?>
                                    <option value="<?= $tec['id_tecnic'] ?>">
                                        <?= htmlspecialchars($tec['nom']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <!-- Botó per enviar el formulari i accedir al sistema -->
                            <button type="submit" class="btn btn-success btn-lg">Entrar al sistema</button>
                        </div>
                    </fieldset>
                </form>

                <?php
                // Si el formulari s'ha enviat, consultem les incidències del tècnic seleccionat
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $id = htmlspecialchars($_POST["tecnic_id"]); // Agafem l'ID del tècnic escollit
                    echo "<hr class='my-5'>";
                    echo "<h3 class='mb-4 text-center fw-semibold' style='font-size:1.7rem;'>Les teves incidències</h3>";

                    // Consultem totes les incidències assignades al tècnic que encara no s'han tancat
                    $sql = "SELECT id_incidencia, descripcio, id_dept, fecha
                            FROM INCIDENCIA WHERE id_tecnic = $id AND fecha_fin IS NULL";
                    $result = $conn->query($sql);

                    // Si hi ha incidències pendents, les mostrem en targetes
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>

                            <div class="d-flex justify-content-between align-items-center bg-light border-start border-4 border-success rounded-3 shadow-sm p-4 mb-3">
                                <div>
                                     <!-- Mostrem l'ID i els primers 50 caràcters de la descripció -->
                                    <h5 class="mb-1 fw-semibold">Incidència #<?= $row["id_incidencia"] ?></h5>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars(substr($row["descripcio"], 0, 50)) ?>...</p>
                                </div>
                                <!-- Botó per accedir al detall i les actuacions de la incidència -->
                                <a href='actuacions.php?id_incidencia=<?= $row["id_incidencia"] ?>' class="btn btn-success btn-sm px-4">Mostrar</a>
                            </div>

                        <?php }
                    } else {
                        // Si no hi ha incidències pendents, mostrem un missatge informatiu
                        echo "<div class='alert alert-light text-center border'>No tens incidències pendents actualment.</div>";
                    }
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
$conn->close();
?>