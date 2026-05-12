<?php

session_start();

if (!isset($_SESSION["email"])) {

    header("Location: login.php");

    exit();

}

?>

<!DOCTYPE html>

<html lang="ca">

<head>

    <meta charset="UTF-8">

    <title>Pàgina privada</title>

</head>

<body>

    <h1>Has entrat correctament</h1>

    <p>Benvingut/da, <?php echo $_SESSION["usuari"]; ?></p>

    <p>Aquesta pàgina només es pot veure si has iniciat sessió.</p>

    <a href="logout.php">Tancar sessió</a>

</body>

</html>