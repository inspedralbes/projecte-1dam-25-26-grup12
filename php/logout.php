<?php
session_start(); // Iniciem la sessió

// Eliminem les dades de l'usuari de la sessió
unset($_SESSION['email']);
unset($_SESSION['rol']);

// Destruïm la sessió
session_destroy();

// Redirigim a la pàgina de login
header("Location: index.php");
exit();
?>