<?php
session_start();
$error = "";
require_once 'connexio.php';


if (isset($_SESSION["email"])) {
    if($_SESSION['rol'] == "admin"){
        header("Location: admin.php");
    }elseif($_SESSION['rol'] == "user"){
        header("Location: usuari.php");
    }elseif($_SESSION['rol'] == "tecnic"){
        header("Location: tecnic.php");
    }

    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuari= $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM USERS WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuari);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 

    if($row['email'] == $usuari){
        if($row['pass'] == $password){
            $_SESSION['email'] = $usuari;
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['id_tecnic'] = $row['id_tecnic'];
            header("Location: index.php");
        }else{
            $error = "Contraseña incorrecta";
        }
        
    }else{
        $error = "No existeix l'usuari";
    }

}


//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer

require_once 'header.php' ;
include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<div class="container" style="max-width: 750px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4 text-center">

        <h2 class="mb-4">Identifica't</h2>

        <hr class="mb-4">
        <span class="d-flex justify-content-center gap-3">
            <h1>Inici de sessió</h1>

            <?php
            if ($error != "") {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>

            <form method="POST" action="index.php">
                <label>Usuari:</label><br>
                <input type="text" name="email" required><br><br>
                <label>Contrasenya:</label><br>
                <input type="password" name="password" required><br><br>
                <button type="submit">Entrar</button>

            </form>
        </span>

    </div>
</div>