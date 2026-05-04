<?php 
require_once 'connexio.php';
require_once 'header.php';
?>

<h1>Modificar</h1>

<?php
    //Quant rep un metode post executa aquesta part del codi.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Varialbles necessaries per la modificacio.
        $id = $_POST['id_incidencia'];
        $prioridad = $_POST['prioridad'];
        $tecnic = $_POST['id_tecnic'];
        $tipologia = $_POST['id_tipo'];

        //Comprobacio de que tots els camps estiguin omplerts.
        if (empty($id) or empty($prioridad) or empty($tecnic) or empty($tipologia)) {
            echo "<p class='error'>Tots els camps són obligatoris.</p>";
            echo "<p><a href='modificar.php?id_incidencia=" . htmlspecialchars($id) . "'>Torna al formulari</a></p>";
            return;
        }

        

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {

            // Preparar la consulta SQL per modificar la incidencia
            $sql = "UPDATE  INCIDENCIA SET prioridad = ?, id_tecnic = ?, id_tipo = ? WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siii", $prioridad, $tecnic, $tipologia, $id);

            // Executar la consulta i comprovar si s'ha esborrat correctament
            if ($stmt->execute()) {
                echo "<p class='info'>Incidencia modificada amb èxit!</p>";
                echo "<p><a href='llistar.php'>Retorna</a></p>";
            } else {
                echo "<p class='error'>Error al modificar la incidencia: " . htmlspecialchars($stmt->error) . "</p>";
            }

            // Tancar la declaració
            $stmt->close();
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } elseif (isset($_GET['id_incidencia'])) {
        // Comprovar si s'ha rebut  l'ID de la casa via GET (a la URL modificar.php?id_incidencia=XXX)
        $id = $_GET['id_incidencia'];

        // Comprovar si l'ID és un número vàlid
        if (is_numeric($id)) {
            // Preparar la consulta SQL per obtenir la incidencia a modificar
            $sql = "SELECT id_incidencia, descripcio FROM INCIDENCIA WHERE id_incidencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            //consulta de tecnic i tipologia per mostrar les opcions al formulari de modificacio
            $sql1 = "SELECT id_tecnic, nom FROM TECNIC";
            $tecnicos= $conn->query($sql1);
            
            $sql2 = "SELECT id_tipo, nom FROM TIPO";
            $tipologia= $conn->query($sql2);
            


            // Comprovar si s'ha trobat la incidencia
            if ($result->num_rows > 0) {
                // Mostrar la incidencia a modificar
                $row = $result->fetch_assoc();
                // Mostrar el formulari, que s'enviarà per POST, per confirmar la modificació
                ?>

                
                    <form method='POST' action='modificar.php'>
                        <fieldset>
                            <legend>Incidencia a modificar:</legend> <?= htmlspecialchars($row["descripcio"]) ?> 

                            <br>
                            <input type='hidden' name='id_incidencia' value=' <?= htmlspecialchars($row["id_incidencia"]) ?> '>
                            <select required name='prioridad' id='prioridad'> 
                                <option value='baja'> Baja </option>
                                <option value='media'> Media </option>
                                <option value='alta'> Alta </option>
                            </select>
                            <select required name='id_tecnic' id='id_tecnic'>
                                <option value=''> Tecnic </option>
                                <?php
                                    //bucle per mostrar noms de tecnics.
                                    while ($tec = $tecnicos->fetch_assoc()) { ?>
                                <option value=' <?= htmlspecialchars($tec['id_tecnic']) ?> '> <?= htmlspecialchars($tec['nom'])?>
                                </option>  
                                <?php   } ?>
                            </select>   
                            <select required name='id_tipo' id='id_tipo'>
                                <option value=''> Tipologia </option>
                                <?php
                                    //bucle per mostrar noms de les tipologies.
                                    while ($tipo = $tipologia->fetch_assoc()) { ?>
                                <option value=' <?= htmlspecialchars($tipo['id_tipo']) ?> '> <?= htmlspecialchars($tipo['nom'])?>
                                </option>  
                                <?php   } ?>
                            </select>      
                            <input type='submit' value='Sí, modificar'>
                        </fieldset>
                    </form>

                <?php
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

   
<?php

require_once 'footer.php';

?>