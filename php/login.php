<?php

session_start();
$error = "";

/*

    Si ja està autenticat, el redirigim directament

*/

if (isset($_SESSION["email"])) {
    header("Location: login_success.php");
    exit();
}

/*

    Quan s'envia el formulari

*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    if(isset($_POST['email']) && isset($_POST['password'])){
        $login = new Login($_POST['email'], $_POST['password']);
    } else {
        $error = "Usuari o contrasenya incorrectes";
    }

   

    /*

        Comprovem si l'usuari existeix i si la contrasenya coincideix

    */

    if (isset($usuaris[$usuari]) && $usuaris[$usuari] == $password) {

        $_SESSION["usuari"] = $usuari;

        header("Location: login_success.php");

        exit();

    } else {

        $error = "Usuari o contrasenya incorrectes";

    }

}

?>

<!DOCTYPE html>

<html lang="ca">

<head>

    <meta charset="UTF-8">

    <title>Login</title>

</head>

<body>

    <h1>Inici de sessió</h1>

    <?php

    if ($error != "") {

        echo "<p style='color:red;'>$error</p>";

    }

    ?>

    <form method="POST" action="login.php">

        <label>Usuari:</label><br>

        <input type="text" name="usuari" required><br><br>

        <label>Contrasenya:</label><br>

        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>

    </form>

</body>

</html>