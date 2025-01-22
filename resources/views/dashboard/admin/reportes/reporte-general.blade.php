<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1, h2, h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary {
            margin-bottom: 30px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p><strong>Fecha de generación:</strong> {{ $date }}</p>

    <!-- Resumen de métricas -->
    <div class="summary">
        <p><strong>Kilómetros totales recorridos:</strong> {{ $allDistance }}</p>
        <p><strong>Rutas activas en este momento:</strong> {{ $activeRoutes }}</p>
        <p><strong>Rutas completadas:</strong> {{ $completedRoutes }}</p>
        <p><strong>Total de órdenes procesadas:</strong> {{ $orders }}</p>
    </div>

    <!-- Tabla de detalles -->
    <h2>Detalle de Rutas</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID Ruta</th>
                <th>Estado</th>
                <th>Distancia</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $route)
                <tr>
                    <td>{{ $route->id }}</td>
                    <td>{{ $route->status }}</td>
                    <td>{{ number_format($route->distance) }} km</td>
                    <td>{{ $route->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        <p>Reporte generado automáticamente por el sistema el {{ $date }}.</p>
    </div>
</body>
</html>
