<?php
session_start(); // Iniciem la sessió
$error = "";
// Connectem a la BD per poder fer les consultes de login
require_once 'connexio.php';

// Si l'usuari ja ha iniciat sessió, el redirigim al seu panell segons el rol
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

// Si el formulari s'ha enviat, comprovem les credencials de l'usuari

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuari= $_POST["email"];
    $password = $_POST["password"];
    // Consultem si existeix un usuari amb aquest email a la BD
    $sql = "SELECT * FROM USERS WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuari);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 

        // Comprovem si l'usuari existeix a la BD

    if(!empty($row['email'])){
        if($row['email'] == $usuari){
            if($row['pass'] == $password){
                // Si tot és correcte, guardem les dades de l'usuari a la sessió
                $_SESSION['email'] = $usuari;
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION['id_tecnic'] = $row['id_tecnic'];
                header("Location: index.php");
            }else{
                // Si la contrasenya no coincideix, guardem el missatge d'error
                $error = "Contraseña incorrecta";
            }
            
        }else{
            // Si no existeix cap usuari amb aquest email, guardem el missatge d'error
            $error = "No existeix l'usuari";
        }
    }else{
        $error = "No existeix l'usuari";
    }

}


//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer

// Connectem a MongoDB per registrar el log d'accés
include_once 'mongo.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'incidencies</title>
    <!-- Carreguem Bootstrap i els estils propis -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link href="./CSS/style.css" rel="stylesheet">
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
            <div class="bg-white rounded-4 shadow-sm p-5 mt-4">

                <h1 class="fw-semibold text-center mb-4" style="font-size:1.6rem;">Inici de sessió</h1>

                <!-- Si hi ha un error de login, mostrem el missatge d'error -->
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger py-2 text-center"><?= $error ?></div>
                <?php endif; ?>

                <!-- Formulari de login amb validació javascript abans d'enviar -->
                <form name="loging" method="POST" action="index.php" onsubmit="return valLog()">
                    <!-- Camp per introduir l'email de l'usuari -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Usuari</label>
                        <input type="text" name="email" class="form-control" required>
                    </div>
                    <!-- Camp per introduir la contrasenya -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Contrasenya</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <!-- Botó per iniciar sessió o continuar com a invitat sense login -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-success botoncito" type="submit">Entrar</button>
                        <a class="btn btn-outline-dark" href="formulari_invi.php">Continuar com a invitat</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include_once 'footer.php'; ?>