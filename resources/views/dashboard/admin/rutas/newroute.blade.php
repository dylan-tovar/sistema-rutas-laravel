@extends('layouts.AppLayout')

@section('title', 'Nueva Ruta')

@section('content')
<div class="container">
    <h1>Crear una Nueva Ruta Optimizada</h1>
    <form method="POST" action=""> {{-- {{ route('admin.route.store') }} --}}
        @csrf
        <h2>Pedidos Pendientes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($pedidos as $pedido)
                <div class="border p-4 rounded-lg">
                    <input type="checkbox" name="pedidos[]" value="{{ $pedido->id }}">
                    Pedido ID: {{ $pedido->id }} <br>
                    Cliente: {{ $pedido->email }} <br>
                    Dirección: {{ $pedido->address }} <br>
                    
                </div>
            @endforeach
        </div>
        <button type="submit" name="calcular_manual" class="btn btn-primary mt-4">Calcular Ruta Manual</button>
        <button type="submit" name="calculo_automatico" class="btn btn-secondary mt-4">Calcular Ruta Automática</button>
    </form>
</div>
@endsection
