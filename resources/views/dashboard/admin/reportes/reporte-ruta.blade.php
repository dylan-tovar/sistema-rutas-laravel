<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1, h2 {
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Título del Reporte -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Fecha de Generación:</strong> {{ $date }}</p>
    </div>

    <!-- Información de la Ruta -->
    <div class="details">
        <h2>Información de la Ruta</h2>
        <p><strong>ID de la Ruta:</strong> {{ $route->id }}</p>
        <p><strong>Distancia:</strong> {{ number_format($route->distance, 2) }} km</p>
        <p><strong>Fecha de Creación:</strong> {{ $route->created_at }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($route->status) }}</p>
    </div>

    <!-- Información del Conductor -->
    @if($driver)
    <div class="details">
        <h2>Conductor Asignado</h2>
        <p><strong>Nombre:</strong> {{ $driver->name }}</p>
        <p><strong>Correo:</strong> {{ $driver->email }}</p>
    </div>
    @else
    <div class="details">
        <h2>Conductor Asignado</h2>
        <p>No hay conductor asignado para esta ruta.</p>
    </div>
    @endif

    <!-- Información del Vehículo -->
    @if($vehicle)
    <div class="details">
        <h2>Vehículo Asignado</h2>
        <p><strong>ID:</strong> {{ $vehicle->id }}</p>
        <p><strong>Modelo:</strong> {{ $vehicle->make }} {{ $vehicle->model }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($vehicle->status) }}</p>
    </div>
    @else
    <div class="details">
        <h2>Vehículo Asignado</h2>
        <p>No hay vehículo asignado para esta ruta.</p>
    </div>
    @endif

    <!-- Paradas de la Ruta -->
    <div class="details">
        <h2>Paradas en la Ruta</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stops as $stop)
                <tr>
                    <td>{{ $stop->order_id }}</td>
                    <td>{{ $stop->description }}</td>
                    <td>{{ $stop->address_name }} - {{ $stop->address }}</td>
                    <td>{{ $stop->user_name }} ({{ $stop->user_email }})</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
