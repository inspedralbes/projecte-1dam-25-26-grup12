<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */



?>

<?php

    // Consulta SQL per obtenir totes les files de la taula 'tecnic'
    $sql = "SELECT id_tecnic, nom FROM TECNIC";
    $result = $conn->query($sql);
    ?>
        <form method="POST" action="">
            <fieldset>
                <legend>Tècnic</legend>

                <label for="nom">Nom</label>
                <select name="tecnic_id" id="tecnic">
                    <option value=""> Selecciona </option>
                    <?php while ($tec = $result->fetch_assoc()) { ?>
                        <option value="<?= $tec['id_tecnic'] ?>">
                            <?= htmlspecialchars($tec['nom']) ?>
                        </option>
                    <?php } ?>
                </select>

                <input type="submit" value="Entrar">
            </fieldset>
        </form>

        <?php

    // Tancar la connexió
    $conn->close();
    
?>

