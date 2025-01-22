@extends('layouts.AppLayout')

@section('title', 'Rutas Activas')

@push('head')
    <!-- Mapbox CSS y JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">
    <!-- Contenedor principal con bordes y scroll interno -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 max-h-[83%] overflow-y-auto scrollbar-custom">
        <!-- Encabezado -->
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
                <h1 class="text-3xl font-bold">Detalles de la Ruta</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes ver los detalles de la ruta activa.</p>
        </div>

        <!-- Mapa -->
        <div id="map" class="h-[400px] w-full rounded-lg border border-accent dark:border-sTextDark"></div>
        
        <script>
            // Configuración de Mapbox
            mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';

            // Detectar el tema actual
            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            const mapStyle = savedTheme === 'dark' 
                ? 'mapbox://styles/mapbox/dark-v11' 
                : 'mapbox://styles/mapbox/light-v11';

            const map = new mapboxgl.Map({
                container: 'map',
                style: mapStyle,
                center: [-66.9036, 10.4806], // Coordenadas iniciales
                zoom: 12
            });
            

            // Crear marcador para el depósito (inicio de la ruta)
            const depot = [-66.916664, 10.500000];
            const stops = @json($stops);

            // Crear los waypoints con las paradas
            const waypoints = [depot].concat(stops.map(stop => [stop.longitude, stop.latitude]));

            // Agregar marcador al mapa
            const depotMarker = new mapboxgl.Marker({ color: '#eb5b38' })
                .setLngLat(depot)
                .addTo(map);

            // Agregar marcadores para las paradas
            stops.forEach(stop => {
                new mapboxgl.Marker({ color: '#38a1db' })
                    .setLngLat([stop.longitude, stop.latitude])
                    .addTo(map);
            });

            // Función para obtener la ruta y mostrarla
            const getRoute = async (waypoints) => {
                const coordinatesString = waypoints.map(coord => coord.join(',')).join(';');
                const directionsUrl = `https://api.mapbox.com/directions/v5/mapbox/driving-traffic/${coordinatesString}?geometries=geojson&overview=full&access_token={{ config('services.mapbox.access_token') }}`;

                const response = await fetch(directionsUrl);
                const data = await response.json();

                if (data.routes && data.routes.length > 0) {
                    const routeGeoJSON = {
                        type: 'Feature',
                        geometry: data.routes[0].geometry
                    };

                    // Agregar la ruta al mapa
                    if (map.getSource('route')) {
                        map.getSource('route').setData(routeGeoJSON);
                    } else {
                        map.addSource('route', {
                            type: 'geojson',
                            data: routeGeoJSON
                        });

                        map.addLayer({
                            id: 'route',
                            type: 'line',
                            source: 'route',
                            layout: {
                                'line-join': 'round',
                                'line-cap': 'round'
                            },
                            paint: {
                                'line-color': '#FF5733',
                                'line-width': 5
                            }
                        });
                    }

                    // Ajustar la vista del mapa para encajar todos los puntos
                    const bounds = new mapboxgl.LngLatBounds();
                    waypoints.forEach(coord => bounds.extend(coord));
                    map.fitBounds(bounds, { padding: 50 });
                }
            };

            // Llamar a la función para obtener y mostrar la ruta
            map.on('load', function () {
                getRoute(waypoints);
            });

            // Cambiar el estilo del mapa dinámicamente según el tema
            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                const newTheme = isDark ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);

                const newMapStyle = newTheme === 'dark' 
                    ? 'mapbox://styles/mapbox/dark-v11' 
                    : 'mapbox://styles/mapbox/light-v11';
                map.setStyle(newMapStyle);
            }
        </script>

        <!-- Detalles de la Ruta -->
        <div class="mt-6">
            <h3 class="text-lg"><strong>Distancia Total:</strong> {{ $route->distance / 1000 }} km</h3>

            <!-- Lista de paradas -->
            <h3 class="mt-4 mb-2 text-lg font-semibold">Paradas</h3>
            <div class="border border-gray-200 bg-white dark:bg-[#09090b] dark:border-pTxt dark:shadow-pTxt p-0 rounded-lg shadow-sm ">
                <ul class="space-y-4">
                    {{-- @if (isset($route) && $route->distance == 0) --}}
                        @if ($stops && $stops->count() > 0)
                            @foreach($stops as $stop)
                                <li class="p-4 px-6 border-t-2 dark:border-pTxt flex flex-col">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-semibold text-lg">
                                            <strong>ID Pedido:</strong> {{ $stop->order_id }}
                                        </span>
                                        <span class="text-sText dark:text-sTextDark">
                                            <strong>Datos del Cliente:</strong> {{ $stop->name }}. {{ $stop->email }}
                                        </span>
                                    </div>
                                    
                                    <span>
                                        <strong>Dirección:</strong> {{ $stop->address_name }} ({{ $stop->address }})
                                    </span>
                                    <span class="mt-1 text-sText dark:text-sTextDark">
                                        <strong>Descripción:</strong> {{ $stop->description }}
                                    </span>
                                </li>
                            @endforeach
                        @else
                            <div class="p-4 px-6">
                                Hubo un problema al calcular la ruta
                            </div>
                        @endif
                    {{-- @else
                        <div class="p-4 px-6">
                            Hubo un problema al calcular la ruta.
                        </div>
                    @endif --}}
                </ul>
                
            </div>
        </div>

            {{-- form para asignar conductor y vehiculo --}}
            <div class="pt-6">
                <h2 class="text-lg font-semibold py-2">Asignar Repartidor y Vehículo a la Ruta</h2>
                <form method="POST" action="{{ route('admin.route.assign', $route->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
            
                    <div class="flex flex-col space-y-2">
                        <label for="driver" class="font-medium">Seleccionar Repartidor:</label>
                        <select name="driver_id" id="driver" required class="p-2 border border-accent rounded-lg dark:bg-[#222222] dark:border-sText">
                            <option value="">Seleccione un repartidor</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex flex-col space-y-2 pb-4">
                        <label for="vehicle" class="font-medium">Seleccionar Vehículo:</label>
                        <select name="vehicle_id" id="vehicle" required class="p-2 border border-accent rounded-lg dark:bg-[#222222] dark:border-sText">
                            <option value="">Seleccione un vehículo</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->make }} {{ $vehicle->model }}</option>
                            @endforeach
                        </select>
                    </div>  
                    
                    <button type="submit" class="w-full py-2 px-4 bg-flamingo-400 hover:bg-flamingo-500 text-white dark:bg-flamingo-500 dark:hover:bg-flamingo-600 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out">
                        Asignar Repartidor y Vehículo
                    </button>
                </form>
            </div>
            
        
    </div>
</div>
@endsection
