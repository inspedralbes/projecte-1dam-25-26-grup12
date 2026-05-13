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
    body {
        background-color: #e9ecef; /* Fondo gris azulado como tu imagen */
    }
    .menu-card {
        background-color: white;
        border: none; /* Quitamos el borde azul */
        border-radius: 15px;
        padding: 50px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Sombra suave */
        margin-top: 50px;
        text-align: center;
    }
    .btn-menu {
        /* Estilo para que los botones se vean como el de "Crear" de tu imagen */
        background-color: #212529; 
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
    }
    .btn-menu:hover {
        background-color: #343a40;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <div class="menu-card">
                <h1 class="mb-4">Administració</h1>
                <p class="text-muted mb-5">Selecciona una de les opcions per gestionar els informes i estadístiques.</p>
                
                <div class="menu-grid">
                    <a class="btn btn-dark px-4" href="llistar.php">Llistar incidència</a>
                    <a class="btn btn-dark px-4" href="tecnotocar.php">Acces a actuacions</a>
                    <a class="btn btn-dark px-4" href="informeTecnic.php">Informe de Tècnics</a>
                    <a class="btn btn-dark px-4" href="consum.php">Consum per departaments</a>
                    <a class="btn btn-dark px-4" href="logs.php">Estadístiques d'Accés</a>
                </div>
            </div>
                </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>