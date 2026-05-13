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
            $_SESSION['id_user'] = $row['id_user'];
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


include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

?>

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'incidencies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f0f2f5;
            padding-top: 150px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<div class="fixed-top bg-white shadow-sm">

    <div class="text-center py-3 border-bottom">
        <h4 class="mb-0 fw-semibold text-secondary">Gestió d'incidències informàtiques Institut Pedralbes</h4>
    </div>
</div>

<main class="flex-grow-1">
<div class="container">
<div class="container" style="max-width: 750px;">
    <div class="bg-white rounded-4 shadow-sm p-5 mt-4 text-center">

        <span >
            <h1>Inici de sessió</h1>

            <?php
            if ($error != "") {
                echo "<p style='color:red;'>$error</p>";
            }
            ?>
            <div>
                <form method="POST" action="index.php">
                    <label>Usuari:</label><br>
                    <input type="text" name="email" required><br><br>
                    <label>Contrasenya:</label><br>
                    <input type="password" name="password" required><br><br>
                    <button class="btn btn-dark px-4" type="submit">Entrar</button>

                </form>
            </div>
            <br>
            <div class="menu-grid">
                    <a class="btn btn-dark px-4" href="formulari_invi.php">Invitat</a>
            </div>
        </span>

    </div>
</div>


<?php include_once 'footer.php'; ?>