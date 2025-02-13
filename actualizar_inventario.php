<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";

if (!isset($conexion)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Agregar nueva mochila
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_mochila"])) {
    $codigo = $_POST["codigo"];
    $colores = implode(",", $_POST["colores"]);
    $precio = $_POST["precio"];
    $cantidad = $_POST["cantidad"];

    // Verificar si ya existe el código con los mismos colores
    $sql_check = "SELECT COUNT(*) FROM mochilas WHERE codigo = ? AND colores = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->execute([$codigo, $colores]);
    $existe = $stmt_check->fetchColumn();

    if ($existe) {
        echo "<script>alert('Error: Ya existe una mochila con este código y estos colores.');</script>";
    } else {
        $sql = "INSERT INTO mochilas (codigo, colores, precio, cantidad) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$codigo, $colores, $precio, $cantidad]);
    }
}

// Actualizar cantidad de mochila
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar_cantidad"])) {
    $id = $_POST["id"];
    $cantidad_cambio = $_POST["cantidad_cambio"];

    $sql = "UPDATE mochilas SET cantidad = cantidad + ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$cantidad_cambio, $id]);
}

// Eliminar mochila
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_mochila"])) {
    $id = $_POST["id"];

    $sql = "DELETE FROM mochilas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
}

// Obtener mochilas
$sql = "SELECT * FROM mochilas";
$mochilas = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0057B7, #FFD700, #FF3B3B);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: white;
        }
        .container {
            max-width: 900px;
            width: 95%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        h2, h4 {
            text-align: center;
            color: #0057B7;
        }
        .table {
            margin-top: 20px;
        }
        .btn-primary, .btn-danger {
            width: 100%;
        }
        .btn-dashboard {
            background: #FF3B3B;
            color: white;
            font-weight: bold;
        }
        .btn-dashboard:hover {
            background: #C70000;
        }
        label {
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Actualizar Inventario</h2>

    <!-- Formulario para agregar una nueva mochila -->
    <h4>Ingresar Nueva Mochila</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Código:</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Colores Disponibles:</label>
            <select name="colores[]" class="form-select" multiple required>
                <option value="Rojo">Rojo</option>
                <option value="Azul">Azul</option>
                <option value="Negro">Negro</option>
                <option value="Verde">Verde</option>
                <option value="Amarillo">Amarillo</option>
            </select>
            <small class="text-muted">Mantén presionada la tecla CTRL para seleccionar múltiples colores.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio ($):</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cantidad Inicial:</label>
            <input type="number" name="cantidad" class="form-control" required>
        </div>
        <button type="submit" name="agregar_mochila" class="btn btn-success">Agregar Mochila</button>
    </form>

    <!-- Tabla de mochilas existentes -->
    <h4 class="mt-4">Inventario Actual</h4>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Colores</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Actualizar Cantidad</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mochilas as $mochila): ?>
                <tr>
                    <td><?= htmlspecialchars($mochila["codigo"]) ?></td>
                    <td><?= htmlspecialchars($mochila["colores"]) ?></td>
                    <td>$<?= number_format($mochila["precio"], 2) ?></td>
                    <td><?= htmlspecialchars($mochila["cantidad"]) ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="id" value="<?= $mochila["id"] ?>">
                            <input type="number" name="cantidad_cambio" class="form-control me-2" required>
                            <button type="submit" name="actualizar_cantidad" class="btn btn-primary">Actualizar</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta mochila?');">
                            <input type="hidden" name="id" value="<?= $mochila["id"] ?>">
                            <button type="submit" name="eliminar_mochila" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón Volver al Dashboard al final -->
    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-dashboard">Volver al Dashboard</a>
    </div>
</div>

</body>
</html>
