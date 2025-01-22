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
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-size: 14px;
        }
        .table td {
            font-size: 12px;
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

    <h2>Detalles de la Ruta</h2>
    <table class="table">
        <tbody>
            <tr>
                <th>Distancia Total</th>
                <td>{{ $route->distance / 1000 }} km</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>{{ $route->status }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Paradas (Pedidos)</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID de Pedido</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stops as $stop)
                <tr>
                    <td>{{ $stop->order_id }}</td>
                    <td>{{ $stop->user_name }} ({{ $stop->user_email }})</td>
                    <td>{{ $stop->address_name }} ({{ $stop->address }})</td>
                    <td>{{ $stop->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el sistema el {{ $date }}.</p>
    </div>
</body>
</html>
