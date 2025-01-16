@extends('layouts.AppLayout')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="container">
            <h1 class="text-3xl font-bold mb-4">Lista de Órdenes</h1>
        
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg border border-green-300 dark:bg-[#213a2a] dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif
        
            <a href="{{ route('user.order') }}" 
                class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150 mb-3">
                Crear Nueva Orden
            </a>
        
            <table class="table table-bordered w-full">
                <thead>
                    <tr>
                        <th class="p-3 text-left border-b dark:border-sTextDark">ID</th>
                        <th class="p-3 text-left border-b dark:border-sTextDark">Usuario</th>
                        <th class="p-3 text-left border-b dark:border-sTextDark">Dirección</th>
                        <th class="p-3 text-left border-b dark:border-sTextDark">Estado</th>
                        <th class="p-3 text-left border-b dark:border-sTextDark">Descripción</th>
                        <th class="p-3 text-left border-b dark:border-sTextDark">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="bg-white dark:bg-[#222222]">
                            <td class="p-3 border-b dark:border-sTextDark">{{ $order->id }}</td>
                            <td class="p-3 border-b dark:border-sTextDark">{{ $order->user->name }}</td>
                            <td class="p-3 border-b dark:border-sTextDark">{{ $order->address->address }}</td>
                            <td class="p-3 border-b dark:border-sTextDark">{{ ucfirst($order->status) }}</td>
                            <td class="p-3 border-b dark:border-sTextDark">{{ $order->description }}</td>
                            <td class="p-3 border-b dark:border-sTextDark">
                                <form action="{{ route('user.order.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150"
                                        onclick="return confirm('¿Estás seguro de eliminar esta orden?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
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
