@extends('layouts.AppLayout')

@section('title', 'Detalles de Ruta Activa')

@push('head')
    <!-- Mapbox CSS y JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">
    <!-- Contenedor principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner rounded-xl p-6 max-h-[83%] overflow-y-auto scrollbar-custom">
        <!-- Encabezado -->
        <div class="flex justify-between border-b border-sTextDark dark:border-sText pb-4 mb-6 items-center">
            <div>
                <h1 class="text-3xl font-bold">Detalles de la Ruta Activa</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes ver los detalles de la ruta activa asignada a ti.</p>
            </div>
            <div>
                <a href="{{ route('driver.route.report', $route->id) }}" target="_blank"
                    class="bg-flamingo-500 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Mapa -->
        {{-- <div id="map" class="h-[400px] w-full rounded-lg border border-accent dark:border-sTextDark"></div>
        <script>
            // Configuración de Mapbox
            mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';

            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            const mapStyle = savedTheme === 'dark' 
                ? 'mapbox://styles/mapbox/dark-v11' 
                : 'mapbox://styles/mapbox/light-v11';

            const map = new mapboxgl.Map({
                container: 'map',
                style: mapStyle,
                center: [-66.9036, 10.4806],
                zoom: 12
            });

            const depot = [-66.916664, 10.500000];
            const stops = @json($stops);

            const waypoints = [depot].concat(stops.map(stop => [stop.longitude, stop.latitude]));

            const depotMarker = new mapboxgl.Marker({ color: '#eb5b38' })
                .setLngLat(depot)
                .addTo(map);

            stops.forEach(stop => {
                new mapboxgl.Marker({ color: '#38a1db' })
                    .setLngLat([stop.longitude, stop.latitude])
                    .addTo(map);
            });

            const getRoute = async (waypoints) => {
                const coordinatesString = waypoints.map(coord => coord.join(',')).join(';');
                const directionsUrl = `https://api.mapbox.com/directions/v5/mapbox/driving/${coordinatesString}?geometries=geojson&overview=full&access_token={{ config('services.mapbox.access_token') }}`;

                const response = await fetch(directionsUrl);
                const data = await response.json();

                if (data.routes && data.routes.length > 0) {
                    const routeGeoJSON = {
                        type: 'Feature',
                        geometry: data.routes[0].geometry
                    };

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
                            layout: { 'line-join': 'round', 'line-cap': 'round' },
                            paint: { 'line-color': '#FF5733', 'line-width': 5 }
                        });
                    }

                    const bounds = new mapboxgl.LngLatBounds();
                    waypoints.forEach(coord => bounds.extend(coord));
                    map.fitBounds(bounds, { padding: 50 });
                }
            };

            map.on('load', function () {
                getRoute(waypoints);
            });
        </script> --}}

        <!-- Detalles de la Ruta -->
        <div class="mt-6">
            <h3 class="text-lg"><strong>Distancia Total:</strong> {{ $route->distance / 1000 }} km</h3>
            <h3 class="mt-4 mb-2 text-lg font-semibold">Paradas</h3>

            <div class="border border-gray-200 bg-white dark:bg-[#09090b] dark:border-pTxt rounded-lg p-4 shadow-sm">
                @if ($stops && $stops->count() > 0)
                    <ul class="space-y-4">
                        @foreach($stops as $stop)
                            <li class="p-4 bg-gray-50 dark:bg-[#1a1a1d] rounded-lg border border-gray-200 dark:border-pTxt">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <strong>ID Pedido:</strong> {{ $stop->order_id }} <br>
                                        <strong>Cliente:</strong> {{ $stop->name }} ({{ $stop->email }}) <br>
                                        <strong>Dirección:</strong> {{ $stop->address_name }} ({{ $stop->address }}) <br>
                                        <strong>Descripción:</strong> {{ $stop->description }}
                                    </div>
                                    <div>
                                        <strong>Estado:</strong> {{ $stop->status }}
                                    </div>
                                    <div>
                                        @if ($stop->status !== 'entregada')
                                            <form method="POST" action="{{ route('driver.orders.update', $stop->order_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-flamingo-500 text-white px-4 py-2 rounded-lg hover:bg-flamingo-600">
                                                    Marcar como Entregada
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-green-500">Entregada</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No se encontraron paradas para esta ruta.</p>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection
