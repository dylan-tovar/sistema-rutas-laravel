
@extends('layouts.AppLayout')

@section('title', 'Nueva Dirección')

@push('head')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4">

    @if ($errors->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 max-h-[83%] overflow-y-auto scrollbar-custom">

        <div class="flex items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold">Añadir una Nueva Direccion</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Aquí puedes gestionar las direcciones registradas y añadir nuevas.
                </p>
            </div>

        </div>
    
       
        <form action="{{ route('user.address.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="address_name" class="block text-sm mb-1">Nombre de la Dirección:</label>
                <input type="text" id="address_name" name="address_name" placeholder="Ej: Mi Casa" required class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-flamingo-300 dark:bg-pTxt dark:border-sTextDark">
            </div>
        
            <div class="mb-4">
                <label for="autocomplete_address" class="block text-sm mb-1">Dirección Aproximada:</label>
                <input type="text" id="autocomplete_address" name="address" placeholder="Ingresa la dirección" required class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-flamingo-300 dark:bg-pTxt dark:border-sTextDark">
                <ul id="autocomplete-results" class="absolute text-black dark:bg-accent bg-white border border-gray-300 rounded-lg shadow-md z-10 hidden"></ul>
            </div>
        
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        
            <div class="mb-4">
                <div id="map" class="h-[360px] w-full rounded-lg border border-accent dark:border-sTextDark"></div>
            </div>
        
            <div class="flex justify-end">
                <button type="submit" class="text-sm border border-flamingo-600 text-white bg-flamingo-400 hover:bg-flamingo-500 dark:bg-flamingo-500 hover:dark:bg-flamingo-600 px-4 py-2 rounded-lg transition duration-300 flex items-center">
                    <span class="material-icons text-base mr-2">add</span>
                    Agregar Dirección
                </button>
            </div>
        </form>
        
        
        
    </div>
</div>

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
        center: [-66.9036, 10.4806], // Coordenadas iniciales
        zoom: 12
    });

    const marker = new mapboxgl.Marker({
        draggable: true,
        color: '#eb5b38'
    }).setLngLat([-66.9036, 10.4806]).addTo(map);

    marker.on('dragend', () => {
        const lngLat = marker.getLngLat();
        document.getElementById('latitude').value = lngLat.lat;
        document.getElementById('longitude').value = lngLat.lng;
    });

    map.on('click', (e) => {
        const lngLat = e.lngLat;
        marker.setLngLat(lngLat);
        document.getElementById('latitude').value = lngLat.lat;
        document.getElementById('longitude').value = lngLat.lng;
    });


    
    // Configurar autocompletado con Mapbox Geocoding API
    const autocompleteInput = document.getElementById('autocomplete_address');
    const resultsContainer = document.getElementById('autocomplete-results');

    autocompleteInput.addEventListener('input', async (e) => {
        const query = e.target.value;

        if (query.length < 3) {
            resultsContainer.classList.add('hidden');
            return;
        }

        const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token={{ config('services.mapbox.access_token') }}&autocomplete=true&limit=5`);
        const data = await response.json();

        resultsContainer.innerHTML = ''; // Limpiar resultados previos

        if (data.features && data.features.length > 0) {
            resultsContainer.classList.remove('hidden');
            data.features.forEach((feature) => {
                const li = document.createElement('li');
                li.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
                li.textContent = feature.place_name;

                li.addEventListener('click', () => {
                    // Actualizar el input, coordenadas y marcador
                    autocompleteInput.value = feature.place_name;
                    document.getElementById('latitude').value = feature.geometry.coordinates[1];
                    document.getElementById('longitude').value = feature.geometry.coordinates[0];
                    marker.setLngLat(feature.geometry.coordinates);
                    map.flyTo({ center: feature.geometry.coordinates, zoom: 14 });

                    resultsContainer.classList.add('hidden');
                });

                resultsContainer.appendChild(li);
            });
        } else {
            resultsContainer.classList.add('hidden');
        }
    }); 
    

    // Ocultar sugerencias al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!resultsContainer.contains(e.target) && e.target !== autocompleteInput) {
            resultsContainer.classList.add('hidden');
        }
    });

    // Escuchar cambios de tema y actualizar el estilo del mapa
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


    //consolelog
    document.querySelector('form').addEventListener('submit', (event) => {
    console.log({
        address: document.getElementById('autocomplete_address').value,
        latitude: document.getElementById('latitude').value,
        longitude: document.getElementById('longitude').value,
    });
});

</script>





@endsection