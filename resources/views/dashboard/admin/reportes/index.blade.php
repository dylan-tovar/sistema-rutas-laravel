@extends('layouts.AppLayout')

@section('title', 'Vista General de Reportes')

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 max-h-[83%] overflow-y-auto scrollbar-custom">
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <h1 class="text-3xl font-bold">Vista General de Reportes</h1>
            <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes generar diferentes tipos de reportes del sistema.</p>
        </div>

        <!-- Sección de Reportes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Reporte de Vehículos -->
            <div class="bg-[#FAFAFA] dark:bg-[#1e1e20] border border-gray-200 dark:border-sText shadow-inner dark:shadow-[#2a2a2d] rounded-lg p-6 flex flex-col items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-300">Reporte de Vehículos</h3>
                <a href="{{ route('admin.report.vehicle') }}" target="_blank" 
                   class="mt-4 bg-flamingo-500 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Generar Reporte
                </a>
            </div>

            <!-- Reporte General -->
            <div class="bg-[#FAFAFA] dark:bg-[#1e1e20] border border-gray-200 dark:border-sText shadow-inner dark:shadow-[#2a2a2d] rounded-lg p-6 flex flex-col items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-300">Reporte General</h3>
                <a href="{{ route('admin.report.general') }}" target="_blank"
                   class="mt-4 bg-flamingo-500 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Generar Reporte
                </a>
            </div>

            <!-- Reporte de Conductores -->
            <div class="bg-[#FAFAFA] dark:bg-[#1e1e20] border border-gray-200 dark:border-sText shadow-inner dark:shadow-[#2a2a2d] rounded-lg p-6 flex flex-col items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-300">Reporte de Conductores</h3>
                <a href="{{ route('admin.report.driver') }}" target="_blank"
                   class="mt-4 bg-flamingo-500 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Rutas Individuales -->
        <div class="bg-[#FAFAFA] dark:bg-[#1e1e20] border border-gray-200 dark:border-sText shadow-inner dark:shadow-[#2a2a2d] rounded-lg p-6 mt-8">
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-300 mb-6 text-center">Reporte de Rutas Individuales</h3>
            <p class="text-sm text-sText dark:text-sTextDark mb-4 text-center">Selecciona una ruta para generar un reporte detallado.</p>

            <!-- Tabla de Rutas -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse bg-[#FAFAFA] dark:bg-[#1e1e20] shadow-sm rounded-lg">
                    <thead class="bg-accent dark:bg-pTxt text-gray-800 dark:text-gray-300">
                        <tr class="text-sm font-semibold">
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">Distancia</th>
                            <th class="p-4 text-left">Fecha</th>
                            <th class="p-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-sText">
                        @foreach($routes as $route)
                            <tr class="hover:bg-white dark:hover:bg-[#2a2a2d] transition-colors duration-150">
                                <td class="p-4 text-gray-800 dark:text-gray-300">{{ $route->id }}</td>
                                <td class="p-4 text-gray-800 dark:text-gray-300">
                                    {{ number_format($route->distance ?? 0) }} km
                                </td>
                                <td class="p-4 text-gray-800 dark:text-gray-300">
                                    {{ $route->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('admin.report.route', $route->id) }}" target="_blank"
                                        class="bg-flamingo-500 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                                        Generar Reporte
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
