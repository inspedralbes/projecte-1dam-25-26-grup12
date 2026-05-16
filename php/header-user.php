<!--Menu fet solament per al usuari-->

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'incidencies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link href="./CSS/style.css" rel="stylesheet" >
   
</head>

<body class="d-flex flex-column min-vh-100">

<div class="fixed-top bg-white shadow-sm">

    <div class="text-center py-3 border-bottom">
        <h4 class="mb-0 fw-semibold text-secondary">Gestió d'incidències informàtiques Institut Pedralbes</h4>
    </div>

    <!--Menu principal fet amb Bootstrap-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
        <div class="container">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="btn btn-success btn-sm px-3" href="index.php">Inici</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="usuari.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Incidencies
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="formulari.php">Registrar nova incidència</a></li>
                            <li><a class="dropdown-item" href="estado.php">Veure estat incidència</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                            <a class="btn btn-danger btn-sm px-3" aria-current="page" href="logout.php">Logout</a> 
                         
                    </li>
                </ul>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

</div>

<main class="flex-grow-1">
    <div class="container">