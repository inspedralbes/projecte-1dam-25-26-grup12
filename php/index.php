<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
require_once 'header.php' ;
include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<div class="container" style="max-width: 750px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4 text-center">

        <h2 class="mb-4">Identifica't</h2>

        <hr class="mb-4">
        <span class="d-flex justify-content-center gap-3">
            <a class="btn btn-dark px-4" href="usuari.php">Usuari</a>
            <a class="btn btn-dark px-4" href="admin.php">Admin</a>
            <a class="btn btn-dark px-4" href="tecnic.php">Tècnic</a>
        </span>

    </div>
</div>