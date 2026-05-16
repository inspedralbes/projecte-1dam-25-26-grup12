<?php
session_start(); // Iniciem la sessió

// Si no hi ha email a la sessió, vol dir que no es fa el login, i redirigim a index.php.

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
// Si el rol no és admin, no té permisos per accedir a aquesta pàgina i i redirigim a index.php.
}elseif (!($_SESSION["rol"] == "admin")) {
    header("Location: index.php");
    exit();  
}



//Carreguem el header i MongoDB
include_once 'header.php';
include_once 'mongo.php';
?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <div class="bg-white rounded-4 shadow-sm p-5 mt-5 text-center">
                <!-- Títol i descripció de la pàgina d'administració -->
                <h1 class="fw-semibold mb-3" style="font-size:2rem;">Administració</h1>
                <p class="text-muted mb-5">Selecciona una de les opcions per gestionar els informes i estadístiques.</p>

                <div class="row g-3 justify-content-center">
                    <div class="col-6 col-md-4">
                        <!-- Botó per veure el llistat de totes les incidències, per poder modificar i eliminar -->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="llistar.php">Llistat incidència</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <!-- Botó per poder seleccionar una incidencia i veure el seu estat i actuacions -->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="estadototal.php">Accés a incidencies</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <!-- Botó per veure actuacions -->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="tecnotocar.php">Accés a actuacions</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <!-- Botó per accedir al informe de tecnics -->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="informeTecnic.php">Informe de Tècnics</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <!-- Botó per veure el consum per departament-->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="consum.php">Consum per departaments</a>
                    </div>
                    <div class="col-6 col-md-4">
                        <!-- Botó per accedir a les estadístiques d'accés -->
                        <a class="btn btn-dark fw-medium py-3 w-100" href="logs.php">Estadístiques d'Accés</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; //Carreguem el footer ?>