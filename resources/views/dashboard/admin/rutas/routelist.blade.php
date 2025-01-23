@extends('layouts.AppLayout')

@section('title', 'Rutas Activas')

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">

    <!-- Tarjeta Principal con scroll interno -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 max-h-[83%] overflow-y-auto scrollbar-custom">
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <h1 class="text-3xl font-bold">Rutas Pendientes</h1>
            <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes ver y gestionar las rutas activas en el sistema.</p>
        </div>

        <!-- Tabla de Rutas -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse overflow-hidden bg-[#FAFAFA] dark:bg-[#18181a] shadow-sm rounded-lg">
                <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                    <tr class="text-sm font-semibold">
                        <th class="p-4 text-left">ID Ruta</th>
                        <th class="p-4 text-left">Repartidor</th>
                        <th class="p-4 text-left">Fecha Creación</th>
                        <th class="p-4 text-left">Distancia Total</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-base divide-gray-300 dark:divide-sText">
                    @foreach($routes as $route)
                        <tr class="hover:bg-white dark:hover:bg-[#09090b42] transition-colors duration-150">
                            <td class="p-4">{{ $route->id }}</td>
                            <td class="p-4">{{ $route->driver->name ?? 'No asignado' }}</td>
                            <td class="p-4">{{ $route->created_at->format('Y-m-d H:i') }}</td>
                            <td class="p-4">
                                @if ($route->distance > 0)
                                    {{ number_format($route->distance) }} km
                                @else
                                    <span class="text-gray-500">No disponible</span>
                                @endif
                            </td>
                            <td class="p-4">{{ $route->status }}</td>
                            <td class="p-4 flex items-center space-x-4">
                                <a href="{{ route('admin.route.details', ['idRuta' => $route->id]) }}" 
                                   class="bg-flamingo-400 dark:bg-flamingo-500 text-white px-4 py-2 rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                                    Ver detalles
                                </a>
                                <form action="{{ route('admin.route.cancel', $route->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button 
                                        class="border border-flamingo-400 dark:border-flamingo-500 px-3 py-2 rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150 flex items-center">
                                        <i class="material-icons text-base text-pTxt dark:text-pTxtDark">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
                
            </table>
        </div>
    </div>
</div>

@endsection
