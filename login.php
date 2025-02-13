<?php
session_start();
require 'conexion.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Usamos `prepare()` para evitar inyección SQL
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND password = :password";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION["usuario"] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Amigo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0057B7, #FFD700, #FF3B3B);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border-top: 8px solid #0057B7;
        }
        .login-container img {
            width: 120px;
            margin-bottom: 15px;
            background: transparent;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .form-control {
            background: #D1E8FF;
            border: 1px solid #0057B7;
            color: black;
        }
        .form-control::placeholder {
            color: black;
        }
        .form-control:focus {
            background: #B0D4FF;
            border-color: #003F88;
            box-shadow: none;
        }
        .btn-custom {
            background: #FFD700;
            border: none;
            color: black;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #FFC107;
        }
        .alert {
            background: rgba(255, 0, 0, 0.8);
            border: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="logo.png" alt="Logo Empresa">
    <h3 class="mb-3 text-primary">Iniciar Sesión</h3>
    
    <?php if ($error): ?>
        <div class="alert text-center"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">Ingresar</button>
    </form>
</div>

</body>
</html>
