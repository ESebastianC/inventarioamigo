<?php
session_start();
require_once 'conexion.php'; // Conexión a la base de datos

if (!isset($conexion)) {
    die("Error: No se pudo conectar a la base de datos.");
}

require('fpdf186/fpdf.php'); // Cargar la librería FPDF

class PDF extends FPDF {
    function Header() {
        // Logo más pequeño (20x20) en la esquina superior derecha
        $this->Image('logo.png', 175, 10, 20, 20);
        
        // Título centrado en Times New Roman, tamaño 16
        $this->SetFont('Times', 'B', 16);
        $this->Cell(190, 10, 'Reporte de Inventario', 0, 1, 'C');
        $this->Ln(10); // Espacio antes de la tabla
    }

    function Footer() {
        $this->SetY(-25); // Ajustado para que el pie de página no se salga
        
        $this->SetFont('Arial', 'I', 10);
        
        // Número de página alineado a la izquierda
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'L');
        
        // Contacto alineado al centro y bien posicionado
        $this->SetY(-15); 
        $this->Cell(0, 10, 'Contacto: Mochilas Amigo - 0992591828 / 0958600915', 0, 0, 'C');
    }
}

function generarPDF($codigo = null) {
    global $conexion;
    
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    $query = "SELECT * FROM mochilas";
    if ($codigo) {
        $query .= " WHERE codigo = ?";
    }
    $query .= " ORDER BY codigo";
    
    $stmt = $conexion->prepare($query);
    if ($codigo) {
        $stmt->execute([$codigo]);
    } else {
        $stmt->execute();
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Posicionamos la tabla más abajo para centrarla verticalmente
    $pdf->SetY(50);

    // Centramos la tabla horizontalmente con una posición de inicio más a la derecha
    $pdf->SetX(35);

    // Encabezados de tabla con fondo gris y centrados
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(40, 12, 'Codigo', 1, 0, 'C', true);
    $pdf->Cell(40, 12, 'Colores', 1, 0, 'C', true);
    $pdf->Cell(30, 12, 'Precio', 1, 0, 'C', true);
    $pdf->Cell(30, 12, 'Cantidad', 1, 1, 'C', true);

    // Contenido de la tabla con más espacio entre filas
    $pdf->SetFont('Arial', '', 10);
    foreach ($result as $row) {
        $pdf->SetX(35); // Mantener la tabla centrada
        $pdf->Cell(40, 10, $row['codigo'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['colores'], 1, 0, 'C');
        $pdf->Cell(30, 10, '$' . number_format($row['precio'], 2), 1, 0, 'C');
        $pdf->Cell(30, 10, $row['cantidad'], 1, 1, 'C');
    }

    $pdf->Ln(10); // Espacio adicional antes del pie de página

    $pdf->Output('D', 'Reporte_Inventario.pdf');
    exit();
}

if (isset($_GET['reporte_general'])) {
    generarPDF();
}
if (isset($_GET['reporte_especifico']) && !empty($_GET['codigo'])) {
    generarPDF($_GET['codigo']);
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Inventario</title>
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
        h2 {
            text-align: center;
            color: #0057B7;
        }
        .table {
            margin-top: 20px;
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
            color: #0057B7;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #C70000;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Consultar Inventario</h2>
    
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="codigo" class="form-control" placeholder="Ingrese código de mochila" required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <a href="?reporte_general=true" class="btn btn-success">Generar Reporte</a>
    <a href="javascript:void(0)" onclick="generarReporteEspecifico()" class="btn btn-warning">Generar Reporte Específico</a>
    
    <table class="table table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Colores</th>
                <th>Precio</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['codigo'])) {
                $codigo = $_GET['codigo'];
                $query = "SELECT * FROM mochilas WHERE codigo = ? ORDER BY codigo";
                $stmt = $conexion->prepare($query);
                $stmt->execute([$codigo]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>{$row['codigo']}</td>";
                    echo "<td>{$row['colores']}</td>";
                    echo "<td>\${$row['precio']}</td>";
                    echo "<td>{$row['cantidad']}</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <!-- Botón de regresar al dashboard al final -->
    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-custom">Regresar al Dashboard</a>
    </div>
</div>

<script>
    function generarReporteEspecifico() {
        let codigo = document.querySelector('[name="codigo"]').value;
        if (codigo) {
            window.location.href = `?reporte_especifico=true&codigo=${codigo}`;
        } else {
            alert('Ingrese un código de mochila para generar el reporte específico.');
        }
    }
</script>
</body>
</html>
