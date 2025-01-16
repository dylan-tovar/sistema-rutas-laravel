@extends('layouts.AppLayout')

@section('title', 'Nueva Ruta')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold">Crear una Nueva Ruta Optimizada</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Selecciona los pedidos para crear una ruta eficiente.
                </p>
            </div>
            <div>
                <button type="button" 
                    class="bg-flamingo-400 text-white dark:bg-flamingo-500 px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150"
                    onclick="document.querySelector('#rutaForm').submit()">
                    Calcular Ruta Automática
                </button>
            </div>
        </div>
        
        <form id="rutaForm" method="POST" action="{{ route('admin.route.store') }}"> {{-- {{ route('admin.route.store') }} --}}
            @csrf
            <h2 class="text-2xl font-semibold mb-4">Pedidos Pendientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pedidos as $pedido)
                    <div class="bg-white dark:bg-[#222222] border border-gray-300 dark:border-sText rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-150">
                        <label class="flex items-start space-x-2">
                            <input type="checkbox" name="orders[]" value="{{ $pedido->id }}" 
                                class="mt-1 rounded border-gray-300 dark:border-sText focus:ring-2 focus:ring-orange-400">
                            <div>
                                <p class="text-sm text-sText dark:text-sTextDark">
                                    <strong>Pedido ID:</strong> {{ $pedido->id }}<br>
                                    <strong>Cliente:</strong> {{ $pedido->email }}<br>
                                    <strong>Dirección:</strong> {{ $pedido->address }}
                                </p>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="flex mt-6">
                <button type="submit" name="calcular_manual" 
                    class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white  px-4 py-2 rounded-md shadow-md hover:shadow-lg transform transition-all duration-150">
                    Calcular Ruta Manual
                </button>
            </div>
        </form>
        
    </div>
</div>
@endsection
