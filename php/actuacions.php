<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<?php
if (isset($_GET['id_incidencia'])){
$id = $_GET['id_incidencia'];

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT id_incidencia, descripcio, id_dept, fecha FROM INCIDENCIA WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {
        

               while ($row = $result->fetch_assoc()) {
                    echo "<br>";
                    echo "<br>";
                    echo "<p>ID: " . $row["id_incidencia"] . " - Descripció: " . htmlspecialchars($row["descripcio"]) . " - ID Departament: " . $row["id_dept"] . " - Data: " . $row["fecha"];
                    echo " <a href='crear_actuaciones.php?id_incidencia=" . $row["id_incidencia"] . "'>Crear actuacio</a></p>";
                    echo "<br>";
                    echo "<br>";
                    
                    

                }

            } 

        // Preparar la consulta SQL per obtenir la casa a esborrar
            $sql = "SELECT id_actuacio, descripcio, fecha FROM ACTUACIO WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si s'ha trobat la casa
            if ($result->num_rows > 0) {

                 echo "<h2> ACTUACIONS: </h2> ";
        

               while ($row = $result->fetch_assoc()) {
                    echo "<p>ID: " . $row["id_actuacio"] . " - Descripció: " . htmlspecialchars($row["descripcio"]) . " - Data: " . $row["fecha"];
                    echo "<br>";
                    echo "<br>";
                   

                }

               

            }

             // Tancar la connexió
                $conn->close();
        }   
}
?>

<?php

require_once 'footer.php';

?>