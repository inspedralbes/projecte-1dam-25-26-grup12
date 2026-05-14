<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}elseif (!($_SESSION["rol"] == "admin")) {
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
        <div class="col-md-10 col-lg-8">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-5 text-center">
                <h1 class="fw-semibold mb-3" style="font-size:2rem;">Administració</h1>
                <p class="text-muted mb-5">Selecciona una de les opcions per gestionar els informes i estadístiques.</p>

                <div class="row g-3 justify-content-center">
                    <div class="col-6 col-md-4">
                        <a class="btn btn-dark fw-medium py-3 w-100" href="llistar.php">Llistar incidència</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a class="btn btn-dark fw-medium py-3 w-100" href="tecnotocar.php">Acces a actuacions</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a class="btn btn-dark fw-medium py-3 w-100" href="informeTecnic.php">Informe de Tècnics</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a class="btn btn-dark fw-medium py-3 w-100" href="consum.php">Consum per departaments</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a class="btn btn-dark fw-medium py-3 w-100" href="logs.php">Estadístiques d'Accés</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>