<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>
    
    


    <div class="container" style="position: relative; width: 1050px; height: 520px; margin: 40px auto; border: 2px solid #3b5bdb;
    border-radius: 18px;
    padding: 40px 40px 60px 80px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;">
        <h1 style = "margin-bottom: 40px; text-align: center;">Benvingut!!</h1>

    <hr><br><br>
        <header><h2 style= "text-align: center;">Identifica't</h2></header>
        <br><br>
        <span class="container-flex" style= "display: flex; justify-content: center; gap: 16px;">
            <a class="btn btn-primary" href="usuari.php">Usuari</a>
            <a class="btn btn-primary"  href="admin.php">Admin</a>
            <a class="btn btn-primary" href="tecnic.php">Tècnic</a>
          </span>
    </div>
