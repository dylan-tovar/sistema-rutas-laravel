@extends('layouts.AppLayout')

@section('title', 'Rutas Activas')

@push('head')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4">

    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <h1 class="text-3xl font-bold">Detalles de la Ruta</h1>
            <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes ver los detalles de la ruta activa.</p>
        </div>
        
        <!-- Mapa -->
        <div id="map" class="h-[400px] w-full rounded-lg border border-accent dark:border-sTextDark"></div>
        
        <script>
            mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';

            // Detectar el tema actual
            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            const mapStyle = savedTheme === 'dark' 
                ? 'mapbox://styles/mapbox/dark-v11' 
                : 'mapbox://styles/mapbox/light-v11';

            const map = new mapboxgl.Map({
                container: 'map',
                style: mapStyle, // Estilo dinámico basado en el tema
                center: [-66.9036, 10.4806], // Coordenadas iniciales (puedes cambiar la ubicación inicial si es necesario)
                zoom: 12
            });

            // Crear marcador para el depósito (inicio de la ruta)
            const depot = [-66.916664, 10.500000];
            const stops = @json($stops); // Los stops ya están pasados desde el controlador

            // Crear los waypoints con las paradas
            const waypoints = [depot].concat(stops.map(stop => [stop.longitude, stop.latitude]));

            // Agregar marcador al mapa
            const marker = new mapboxgl.Marker({ color: '#eb5b38' })
                .setLngLat(depot)
                .addTo(map);

            // Crear la ruta en el mapa utilizando los waypoints
            const routeGeoJSON = {
                type: 'Feature',
                geometry: {
                    type: 'LineString',
                    coordinates: waypoints
                }
            };

            // Al cargar el mapa, dibujar la ruta
            map.on('load', function() {
                // Dibujar la ruta en el mapa
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
            });

            // Agregar marcadores para cada parada en la ruta
            stops.forEach(stop => {
                new mapboxgl.Marker({ color: '#38a1db' })
                    .setLngLat([stop.longitude, stop.latitude])
                    .addTo(map);
            });

            // Actualizar marcador cuando la ruta se dibuje
            map.on('click', (e) => {
                const lngLat = e.lngLat;
                marker.setLngLat(lngLat);
                document.getElementById('latitude').value = lngLat.lat;
                document.getElementById('longitude').value = lngLat.lng;
            });

            // Configurar el estilo del mapa y el comportamiento dinámico según el tema
            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                const newTheme = isDark ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);

                // Cambiar estilo del mapa dinámicamente
                const newMapStyle = newTheme === 'dark' 
                    ? 'mapbox://styles/mapbox/dark-v11' 
                    : 'mapbox://styles/mapbox/light-v11';
                map.setStyle(newMapStyle);
            }
        </script>

        <!-- Detalles de la Ruta -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold">Detalles de la Ruta</h2>
            <p><strong>Distancia Total:</strong> {{ $route->distance / 1000 }} km</p>

            <h3 class="mt-4 text-lg font-semibold">Paradas</h3>
            <ul class="list-disc pl-6">
                @foreach($stops as $stop)
                    <li>
                        <strong>ID Pedido:</strong> {{ $stop->id }} | 
                        <strong>Latitud:</strong> {{ $stop->latitude }} | 
                        <strong>Longitud:</strong> {{ $stop->longitude }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection
