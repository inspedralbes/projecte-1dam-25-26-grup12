<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'incidencies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>


<body>
<br>
<br>
<h2>Gestió d'incidències informàtiques Institut Pedralbes</h2><br>
<br>
    <!--Menu principal fet amb Bootstrap-->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Inici</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="tecnic.php">Tècnic</a>
        </li>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="admin.php">Admin</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="usuari.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Usuari
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="formulari.php">Registrar nova incidència</a></li>
            <li><a class="dropdown-item" href="estado.php">Veure estat incidència</a></li>
           
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main>
  <br><br>
        
    <div class="container">
      
        
        
     