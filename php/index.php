<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>
    <h1>Benvingut!!</h1>

    <hr>
    <h2>Identifica't</h2>

    <!--Hem afegit un menu per identificar-se-->
    <div id="menu" >
        <p><a href="usuari.php">Usuari</a></p>
        <p><a href="admin.php">Admin</a></p>
        <p><a href="tecnic.php">Tècnic</a></p>
    </div>

</body>

</html>