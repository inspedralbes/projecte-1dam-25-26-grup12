<?php 
require_once 'connexio.php';
require_once 'header.php';
?>

<h1>Modificar</h1>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviat (mètode POST), procedim a esborrar la casa 
        $id = $_POST['id_incidencia'];
        $prioridad = $_POST['prioridad'];
        $tecnic = $_POST['id_tecnic'];

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per esborrar la casa

            $sql = "UPDATE  INCIDENCIA SET prioridad = ?, id_tecnic = ? WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $prioridad, $tecnic, $id);

            // Executar la consulta i comprovar si s'ha esborrat correctament
            if ($stmt->execute()) {
                echo "<p class='info'>Incidencia modificada amb èxit!</p>";
            } else {
                echo "<p class='error'>Error al modificar la incidencia: " . htmlspecialchars($stmt->error) . "</p>";
            }

            // Tancar la declaració
            $stmt->close();
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } elseif (isset($_GET['id_incidencia'])) {
        // Comprovar si s'ha rebut  l'ID de la casa via GET (a la URL esborrar.php?id=XXX)
        $id = $_GET['id_incidencia'];

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT id_incidencia, descripcio FROM INCIDENCIA WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $sql1 = "SELECT id_tecnic, nom FROM TECNIC";
            $tecnicos= $conn->query($sql1);

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {
                // Mostrar la casa a esborrar
                $row = $result->fetch_assoc();

                // Mostrar el formulari, que s'enviarà per POST, per confirmar l'esborrat
                echo "<form method='POST' action='modificar.php'>";
                echo "<fieldset><legend>Incidencia a modificar:</legend>" . htmlspecialchars($row["descripcio"]) . "";

                echo "<br>";
                echo "<input type='hidden' name='id_incidencia' value='" . htmlspecialchars($row["id_incidencia"]) . "'>";
                echo "<select name='prioridad' id='prioridad'> ";
                echo "<option value='baja'> Baja </option>";
                echo "<option value='media'> Media </option>";
                echo "<option value='alta'> Alta </option>";
                echo "</select>";
                echo "<select name='id_tecnic' id='id_tecnic'>";
                echo "<option value=''> Selecciona </option>" ;
                    while ($tec = $tecnicos->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($tec['id_tecnic']) . "'>" . htmlspecialchars($tec['nom']);
                echo  "</option>";  
                    }
                echo "</select>";        
                echo "<input type='submit' value='Sí, modificar'>";
                echo "</fieldset>";
                echo "</form>";
            } else {
                echo "<p class='error'>No s'ha trobat la Incidencia amb ID: " . htmlspecialchars($id) . "</p>";
            }
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } else {
        echo "<p class='error'>No s'ha especificat cap ID.</p>";
    }
    ?>

   
</body>

</html>