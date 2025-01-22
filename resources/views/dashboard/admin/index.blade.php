@extends('layouts.AppLayout')

@section('title', 'Inicio')

@push('head')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css" rel="stylesheet" />
<style>
    #map {
        width: 100%;
        height: 500px;
        border-radius: 8px; /* Bordes redondeados en el mapa */
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 space-y-8 max-h-[83%] overflow-y-auto scrollbar-custom">
        <!-- Encabezado del Dashboard -->
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <p class="mt-1 text-sm text-sText dark:text-sTextDark">Bienvenido de nuevo, {{Auth::user()->name}}</p>
        </div>

        <!-- Widgets de métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['title' => 'Distancia Total Recorrida', 'value' => number_format($metrics['allDistance'], 0) . ' km'],
                ['title' => 'Rutas Activas', 'value' => $metrics['activeRoutes']],
                ['title' => 'Rutas Completadas', 'value' => $metrics['completedRoutes']],
                ['title' => 'Pedidos Pendientes', 'value' => $metrics['pendingOrders']]
            ] as $widget)
            <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt dark:text-flamingo-50 rounded-lg p-4 shadow-md"> {{-- bg-gradient-to-r from-flamingo-400 to-flamingo-600 --}}
                <p class="text-sm font-semibold">{{ $widget['title'] }}</p>
                <h2 class="text-2xl font-bold text-flamingo-700 dark:text-flamingo-400">{{ $widget['value'] }}</h2>
            </div>
            @endforeach
        </div>

        <!-- Widget con gráfico y estado de vehículos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gráfico de órdenes creadas -->
            <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg shadow-lg p-4 md:p-6">
                <div class="flex justify-between items-center pb-4">
                    <div>
                        <h5 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ordersCount }} Pedidos</h5>
                        <p class="text-base text-gray-500 dark:text-gray-400">Pedidos de esta semana</p>
                    </div>
                    <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                        12%
                        <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                        </svg>
                    </div>
                </div>
                <div id="area-chart" class="h-72"></div>
            </div>

            <!-- Estado de los vehículos -->
            <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg shadow-lg p-4 md:p-6">
                <h5 class="text-2xl font-bold text-gray-900 dark:text-white">Estado de Vehículos</h5>
                <p class="text-base text-gray-500 dark:text-gray-400 mb-4">Vehículos en uso o en mantenimiento</p>

                <!-- Lista de vehículos con scroll automático -->
                <div class="max-h-80 overflow-y-auto">
                    <ul class="space-y-2">
                        @foreach($vehicles as $vehicle)
                            <li class="flex justify-between items-center p-4 bg-flamingo-100 border-l-8 border-flamingo-500 rounded-lg dark:bg-[#18181a] dark:border-flamingo-400 hover:bg-flamingo-200 dark:hover:bg-pTxt">
                                <span class="text-sm font-semibold text-flamingo-950 dark:text-white">{{ $vehicle->make }} {{ $vehicle->model}}</span>
                                <span class="text-sm text-flamingo-900 dark:text-gray-400">
                                    {{ $vehicle->status }}
                                    @if ($vehicle->name != null)
                                        por {{ $vehicle->name }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white border border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg p-6 shadow-md">
            <h3 class="text-lg font-bold mb-4 ">Direcciones Agregadas</h3>
            <div id="map"></div>
        </div>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js"></script>

        <script>
           mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';

            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            const mapStyle = savedTheme === 'dark' 
                ? 'mapbox://styles/mapbox/dark-v11' 
                : 'mapbox://styles/mapbox/light-v11';

            // Inicialización del mapa
            const map = new mapboxgl.Map({
                container: 'map', // Contenedor donde se renderiza el mapa
                style: mapStyle,
                center: [-66.9036, 10.4806], // Coordenadas de centrado inicial (ajústalas si es necesario)
                zoom: 10 // Nivel de zoom
            });

            // Datos de coordenadas (latitud y longitud) pasados desde el backend
            const addresses = @json($addresses); // Los datos de la base de datos

            // Convertir las direcciones a formato GeoJSON para Mapbox
            const geojson = {
                "type": "FeatureCollection",
                "features": addresses.map(address => ({
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": [address.longitude, address.latitude] // [longitud, latitud]
                    }
                }))
            };

            // Añadir la capa de heatmap cuando se haya cargado el mapa
            map.on('load', function() {
                map.addLayer({
                    "id": "heatmap",
                    "type": "heatmap",
                    "source": {
                        "type": "geojson",
                        "data": geojson
                    },
                    "paint": {
                        "heatmap-intensity": {
                            "stops": [
                                [0, 0],
                                [1, 1]
                            ]
                        },
                        "heatmap-radius": 15, // Radio de dispersión de los puntos
                        "heatmap-opacity": 0.8 // Opacidad de la capa heatmap
                    }
                });
            });

            // Escuchar cambios de tema y actualizar el estilo del mapa
            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                const newTheme = isDark ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);

                // Recargar la página para aplicar el nuevo estilo y las capas
                window.location.reload();
            }

        </script>

        <!-- Tabla de últimos pedidos -->
        <div class="bg-white border border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg p-6 shadow-md">
            <h3 class="text-lg font-bold mb-4 ">Últimos Pedidos</h3>
            
                <table class="w-full text-left border-collapse overflow-hidden shadow-sm rounded-lg">
                    <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Cliente</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-sText">
                        @foreach($lastOrders as $order)
                        <tr class="hover:bg-[#FAFAFA] dark:hover:bg-[#18181a] transition-colors duration-150">
                            <td class="p-4">{{ $order->id }}</td>
                            <td class="p-4">{{ $order->customer_name ?? 'Sin Nombre' }}</td>
                            <td class="p-4">{{ ucfirst($order->status) }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
    </div>
</div>

<!-- Scripts para gráficas -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const maxY = Math.max(...@json($ordersByDay)) + 0; 
        
        const options = {
            chart: {
                height: 250, // Altura del gráfico
                type: "area",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#eb5b38",
                    gradientToColors: ["#eb5b38"],
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 2, // Grosor de la línea
                curve: 'smooth', // Curvatura más suave
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: 0
                },
            },
            series: [{
                name: "Orders Created",
                data: @json($ordersByDay), // Pasamos los datos desde el controlador
                color: "#db341b",
            }],
            xaxis: {
                categories: [
                    '7 days ago', '6 days ago', '5 days ago', '4 days ago', 
                    '3 days ago', '2 days ago', 'Yesterday'
                ],
                labels: {
                    show: true,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                min: 0,   // Valor mínimo del eje Y
                max: maxY, // Aquí asignamos el valor máximo dinámico
                show: true,
            },
        };

        if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("area-chart"), options);
            chart.render();
        }
    });
</script>
@endsection
