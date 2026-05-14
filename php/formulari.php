<?php

session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}

//Conexion a la base de dades i header
require_once 'connexio.php';
require_once 'header.php';
include_once 'mongo.php';


/**
 * Funció que llegeix els paràmetres del formulari i crea una nova incidencia a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    // Obtenir la descripció i el departament del formulari, i netejar les dades per evitar injeccions SQL
    $descripcio = htmlspecialchars($_POST['descripcio']);
    $departamento = htmlspecialchars($_POST['id_dept']);
    $data = date('Y-m-d H:i:s');

    //Comprovar que els camps no estiguin buits, i si ho estan mostrar un missatge d'error i un enllaç per tornar al formulari
    if (empty($descripcio) or empty($departamento)) {
        echo "<p class='text-danger'>Tots els camps són obligatoris.</p>";
        echo "<p><a href='formulari.php'>Torna al formulari</a></p>";
        return;

    }
    
    $user = $_SESSION['id_user'];


    // Preparar la consulta SQL per inserir una nova incidenccia(el valor de data es automatic amb now)
    $sql = "INSERT INTO INCIDENCIA (descripcio, id_dept, fecha, id_user) VALUES (?, ?, ?, ?)";
    //Preparacio de la consulta.
    $stmt = $conn->prepare($sql);
    // Vincular els paràmetres a la consulta preparada  
    $stmt->bind_param("sisi", $descripcio, $departamento, $data, $user);



    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        // Consulta SQL per obtenir l'id de la incidencia creada a partir de la descripcio i el departament.
        $sql1 = "SELECT id_incidencia FROM INCIDENCIA WHERE descripcio = ? AND id_dept = ? AND fecha = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("sis", $descripcio, $departamento, $data);
        $stmt1->execute();
        $result = $stmt1->get_result();
        $row = $result->fetch_assoc();  
        $id_incidencia = $row['id_incidencia']; 

        echo "<p class='text-success fw-semibold'>Incidencia creada amb èxit!</p>";
        echo "<p>ID de la incidencia creada: " . htmlspecialchars($id_incidencia) . "</p>";
        echo "<div class='text-center mt-4'><a href='index.php' class='btn btn-dark px-4'>Retorna a l'inici</a></div>";
        } else {
        echo "<p class='text-danger'>Error al crear la Incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}

?>

<div class="container" style="max-width: 700px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4">

        <h1 class="fw-semibold mb-3" style="font-size:1.6rem;">Registrar incidència</h1>
        <hr class="mb-4">

        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            crear_incidencia($conn);

        } else {

            $sql = "SELECT id_dept, nom FROM DEPARTAMENT";
            $departaments = $conn->query($sql);

            ?>
            <form name="incidencia" method="POST" action="formulari.php" onsubmit="return formulari()">
                <fieldset>
                    <div class="mb-3">
                        <label for="descripcio" class="form-label fw-medium">Descripció</label>
                        <textarea name="descripcio" class="form-control mb-3" rows="5" required></textarea>
                        <label for="departament" class="form-label fw-medium">Departament</label>
                        <select name="id_dept" id="id_dept" class="form-select mb-4" aria-label="Default select example" required>
                            <option value="">Selecciona</option>
                            <?php while ($dep = $departaments->fetch_assoc()) { ?>
                                <option value="<?= $dep['id_dept'] ?>" required>
                                    <?= htmlspecialchars($dep['nom']) ?>
                                </option>
                            <?php } ?>
                        </select>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Crear</button>
                            
                    </div>
                </fieldset>
            </form>

            <?php
        }
        ?>

    </div>
</div>

<?php

require_once 'footer.php';

?>