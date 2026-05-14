<?php

session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}



require_once 'header.php';
include_once 'mongo.php';
?>

<style>
    body { background-color: #f0f2f5; }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-5 text-center">
                <h1 class="fw-semibold mb-3" style="font-size:2.5rem;">Gestió d'Incidències</h1>
                <p class="text-muted mb-5">Selecciona una opció per començar.</p>

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