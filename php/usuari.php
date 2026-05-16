<?php

session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}


// Si el rol és tecnic o admin, no tenen accés a aquesta pàgina d'usuari, els redirigim a index.php perquè vagin al seu propi panell

if($_SESSION["rol"] == "tecnic"){
    header("Location: index.php"); 
}elseif ($_SESSION["rol"] == "admin") {
    header("Location: index.php");
// Si el rol és user, carreguem la seva capçalera corresponent

}elseif ($_SESSION["rol"] == "user") {
    include_once 'header-user.php' ; 
}
// Connectem a MongoDB
include_once 'mongo.php';
?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-5 text-center">
                <h1 class="fw-semibold mb-3" style="font-size:2.5rem;">Gestió d'Incidències</h1>
                <p class="text-muted mb-5">Selecciona una opció per començar.</p>

            <!-- Dos botons: un per crear una nova incidència i un altre per consultar l'estat d'una existent -->
                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-success fw-medium px-4 py-3 rounded-3" href="formulari.php">Registrar nova incidència</a>
                    <a class="btn btn-success fw-medium px-4 py-3 rounded-3" href="estado.php">Veure estat incidència</a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>