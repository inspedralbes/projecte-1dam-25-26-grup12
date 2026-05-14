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
require_once 'header.php';
include_once 'mongo.php';
?>

<style>
    body {
        background-color: #e9ecef; /* Fondo gris azulado */
    }
    .main-card {
        background-color: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 30px;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    h1 {
        font-weight: 700;
        color: #212529;
        margin-bottom: 20px;
        text-align: center;
    }
    legend {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
    }
</style>

<div class="container">
    <div class="main-card">
        <h1>Modificar Incidència</h1>

        <?php
        // --- TU LÓGICA PHP (SIN TOCAR) ---
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $id = $_POST['id_incidencia'];
            $prioridad = $_POST['prioridad'];
            $tecnic = $_POST['id_tecnic'];
            $tipologia = $_POST['id_tipo'];

            if (empty($id) or empty($prioridad) or empty($tecnic) or empty($tipologia)) {
                echo "<div class='alert alert-danger'>Tots els camps són obligatoris.</div>";
                echo "<p><a class='btn btn-dark' href='modificar.php?id_incidencia=" . htmlspecialchars($id) . "'>Torna al formulari</a></p>";
                return;
            }

            if (is_numeric($id)) {
                $sql = "UPDATE INCIDENCIA SET prioridad = ?, id_tecnic = ?, id_tipo = ? WHERE id_incidencia = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siii", $prioridad, $tecnic, $tipologia, $id);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Incidència modificada amb èxit!</div>";
                    echo "<p><a class='btn btn-dark' href='llistar.php'>Retorna al llistat</a></p>";
                } else {
                    echo "<div class='alert alert-danger'>Error al modificar la incidencia: " . htmlspecialchars($stmt->error) . "</div>";
                }
                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>ID no vàlid.</div>";
            }
        } elseif (isset($_GET['id_incidencia'])) {
            $id = $_GET['id_incidencia'];

            if (is_numeric($id)) {
                $sql = "SELECT id_incidencia, descripcio FROM INCIDENCIA WHERE id_incidencia = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                $sql1 = "SELECT id_tecnic, nom FROM TECNIC";
                $tecnicos= $conn->query($sql1);
                
                $sql2 = "SELECT id_tipo, nom FROM TIPO";
                $tipologia= $conn->query($sql2);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    ?>
                    
                    <form method='POST' action='modificar.php'>
                        <fieldset class="border p-4 rounded shadow-sm">
                            <legend class="w-auto px-2">Incidència a modificar:</legend>
                            <p class="mb-4 p-3 bg-light rounded border">
                                <strong>Descripció:</strong> <?= htmlspecialchars($row["descripcio"]) ?>
                            </p>

                            <input type='hidden' name='id_incidencia' value='<?= htmlspecialchars($row["id_incidencia"]) ?>'>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Prioritat</label>
                                <select required name='prioridad' id='prioridad' class="form-select"> 
                                    <option value='baja'> Baixa </option>
                                    <option value='media'> Mitja </option>
                                    <option value='alta'> Alta </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tècnic assignat</label>
                                <select required name='id_tecnic' id='id_tecnic' class="form-select">
                                    <option value=''> Selecciona Tècnic </option>
                                    <?php while ($tec = $tecnicos->fetch_assoc()) { ?>
                                        <option value='<?= htmlspecialchars($tec['id_tecnic']) ?>'> 
                                            <?= htmlspecialchars($tec['nom'])?>
                                        </option>  
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Tipologia</label>
                                <select required name='id_tipo' id='id_tipo' class="form-select">
                                    <option value=''> Selecciona Tipologia </option>
                                    <?php while ($tipo = $tipologia->fetch_assoc()) { ?>
                                        <option value='<?= htmlspecialchars($tipo['id_tipo']) ?>'> 
                                            <?= htmlspecialchars($tipo['nom'])?>
                                        </option>  
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type='submit' class='btn btn-dark px-4'>Sí, modificar</button>
                                <a href="llistar.php" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </fieldset>
                    </form>

                    <?php
                } else {
                    echo "<div class='alert alert-danger'>No s'ha trobat la Incidencia amb ID: " . htmlspecialchars($id) . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ID no vàlid.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No s'ha especificat cap ID.</div>";
        }
        ?>
    </div> </div>

<?php
require_once 'footer.php';
?>