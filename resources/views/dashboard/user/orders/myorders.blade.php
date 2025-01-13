
@extends('layouts.AppLayout')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="container">
            <h1 class="mb-4">Lista de Órdenes</h1>
        
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        
            <a href="{{ route('user.order') }}" class="btn btn-primary mb-3">Crear Nueva Orden</a>
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->address->address }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->description }}</td>
                            <td>
                                <form action="{{ route('user.order.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta orden?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay órdenes disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
