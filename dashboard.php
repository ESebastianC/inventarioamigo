<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0057B7, #FFD700, #FF3B3B);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .dashboard-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-custom {
            width: 100%;
            max-width: 300px;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 15px;
            text-transform: uppercase;
        }
        .btn-inventario {
            background: #0057B7;
            color: white;
        }
        .btn-inventario:hover {
            background: #003F88;
        }
        .btn-actualizar {
            background: #FFD700;
            color: black;
        }
        .btn-actualizar:hover {
            background: #FFC107;
        }
        .btn-logout {
            background: #FF3B3B;
            color: white;
            font-weight: bold;
        }
        .btn-logout:hover {
            background: #C70000;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header">
        <h4 class="text-primary">Bienvenido, <?= $_SESSION["usuario"] ?>!</h4>
        <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
    </div>
    
    <button onclick="location.href='consultar_inventario.php'" class="btn btn-inventario btn-custom">Consultar Inventario</button>
    <button onclick="location.href='actualizar_inventario.php'" class="btn btn-actualizar btn-custom">Actualizar Inventario</button>
</div>

</body>
</html>
