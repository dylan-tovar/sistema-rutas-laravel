@extends('layouts.AppLayout')

@section('title', 'Dashboard Usuario')

@section('content')
<div class="container mx-auto px-4 h-screen overflow-hidden">
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6 space-y-8 lg:max-h-[83%] overflow-y-auto scrollbar-custom">
        <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <p class="mt-1 text-sm text-sText dark:text-sTextDark">Bienvenido de nuevo, {{Auth::user()->name}}</p>
        </div>

        <!-- Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['title' => 'Pedidos Totales', 'value' => $metrics['userOrders']],
                ['title' => 'Pedidos Completados', 'value' => $metrics['completedOrders']],
                ['title' => 'Pedidos Pendientes', 'value' => $metrics['pendingOrders']],
                ['title' => 'Distancia Recorrida', 'value' => number_format($metrics['userDistance'], 0) . ' km']
            ] as $widget)
             <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt dark:text-flamingo-50 rounded-lg p-4 shadow-md">
                <p class="text-sm font-semibold">{{ $widget['title'] }}</p>
                <h2 class="text-2xl font-bold text-flamingo-700 dark:text-flamingo-400">{{ $widget['value'] }}</h2>
            </div>
            @endforeach
        </div>

        <!-- Últimos Pedidos -->
        <div class="bg-white border border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg p-6 shadow-md">
            <h3 class="text-lg font-bold mb-4">Tus Últimos Pedidos</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse overflow-hidden shadow-sm rounded-lg">
                    <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-sText">
                        @foreach($lastUserOrders as $order)
                        <tr class="hover:bg-[#FAFAFA] dark:hover:bg-[#18181a] transition-colors duration-150">
                            <td class="p-4">{{ $order->id }}</td>
                            <td class="p-4">{{ ucfirst($order->status) }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rutas Activas -->
        @if($userRoutes->isNotEmpty())
        <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg font-bold mb-4">Tus Rutas Activas</h3>
            <ul class="space-y-2">
                @foreach($userRoutes as $route)
                <li class="flex justify-between items-center p-4 bg-flamingo-100 border-l-8 border-flamingo-500 rounded-lg dark:bg-[#18181a] dark:border-flamingo-400 hover:bg-flamingo-200 dark:hover:bg-pTxt">
                    <span class="text-sm font-semibold">Ruta ID: {{ $route->id }}</span>
                    <span class="text-sm text-gray-500">({{ number_format($route->distance / 1000, 2) }} km)</span>
                </li>
                @endforeach
            </ul>
        </div>
        @else
        <div class="border bg-white border-accent dark:bg-[#09090b] dark:border-pTxt rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg font-bold mb-4">Tus Rutas Activas</h3>
            <p class="text-gray-500">No tienes rutas activas en este momento.</p>
        </div>
        @endif

    </div>
</div>
@endsection