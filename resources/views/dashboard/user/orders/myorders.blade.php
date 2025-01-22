@extends('layouts.AppLayout')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">
            @if (Route::is('user.my.orders'))
            <div>
                <h1 class="text-3xl font-bold">Lista de Órdenes</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Aquí puedes gestionar las órdenes y crear nuevas.
                </p>
            </div>
            <div>
                <!-- Botón para agregar nueva orden -->
                <a href="{{ route('user.order') }}" 
                   class="border border-flamingo-500 text-flamingo-500 dark:border-flamingo-600 dark:text-flamingo-600 hover:bg-flamingo-400 hover:text-white hover:dark:bg-flamingo-600 hover:dark:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                   Crear Nueva Orden
                </a>
            </div>
            @else
            <div>
                <h1 class="text-3xl font-bold">Historial de Órdenes</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Aquí puedes ver todos tus pedidos
                </p>
            </div>
            @endif
            
        </div>

        <!-- Tabla de órdenes -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse overflow-hidden bg-[#FAFAFA] dark:bg-[#18181a] shadow-sm rounded-lg">
                <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                    <tr class="text-sm font-semibold">
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Usuario</th>
                        <th class="p-4 text-left">Dirección</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-left">Descripción</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-base divide-gray-300 dark:divide-sText">
                    @forelse($orders as $order)
                        <tr class="hover:bg-white dark:hover:bg-[#09090b42] transition-colors duration-150">
                            <td class="p-4">{{ $order->id }}</td>
                            <td class="p-4">{{ $order->user->name }}</td>
                            <td class="p-4">{{ $order->address->address }}</td>
                            <td class="p-4">{{ ucfirst($order->status) }}</td>
                            <td class="p-4">{{ $order->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 border-b dark:border-sTextDark">No hay órdenes disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
