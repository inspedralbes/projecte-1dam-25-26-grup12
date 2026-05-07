<?php
require_once 'header.php';
?>

<style>
    body {
        background-color: #e9ecef; /* Fondo gris azulado de tu captura */
    }
    .main-card {
        background-color: white;
        border: none;
        border-radius: 15px; /* Bordes redondeados como la imagen */
        padding: 60px 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Sombra suave */
        margin-top: 50px;
        text-align: center;
    }
    /* Estilo para los botones oscuros */
    .btn-custom {
        background-color: #212529;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-weight: 500;
    }
    .btn-custom:hover {
        background-color: #343a40;
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="main-card">
                <h1 class="mb-4">Gestió d'Incidències</h1>
                <p class="text-muted mb-5">Benvingut al sistema de suport tècnic. Selecciona una opció per començar.</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <a class="btn-custom" href="formulari.php">Registrar nova incidència</a>
                    <a class="btn-custom" href="estado.php">Veure estat incidència</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>