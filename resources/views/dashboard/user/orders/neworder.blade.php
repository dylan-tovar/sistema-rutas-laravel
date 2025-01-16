@extends('layouts.AppLayout')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        
        <!-- Título y Descripción -->
        <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold">Crear Nueva Orden</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Completa el formulario para crear una nueva orden en el sistema.
                </p>
            </div>
            <div>
                <a href="{{ route('user.my.orders') }}" 
                    class=" px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Ver a Mis Pedidos
                </a>
            </div>
        </div>

        <!-- Mensajes de Error -->
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg border border-red-300 dark:bg-[#361414] dark:text-red-300">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('user.order.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Usuario (oculto) -->
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <!-- Dirección -->
            <div>
                <label for="address_id" class="block text-sm font-semibold text-sText dark:text-sTextDark mb-2">Selecciona una Dirección</label>
                <select id="address_id" name="address_id" required
                    class="w-full border border-accent dark:border-sText dark:bg-[#222222] rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                    <option value="" disabled selected>Selecciona una dirección</option>
                    @foreach($addresses as $address)
                        <option value="{{ $address->id }}">{{ $address->address_name }} - {{ $address->address }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estado (oculto) -->
            <input type="hidden" name="status" value="pending">

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-semibold text-sText dark:text-sTextDark mb-2">Descripción (opcional)</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full border border-accent dark:border-sText dark:bg-[#222222] rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300"></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <button type="submit" 
                    class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Crear Orden
                </button>
                
            </div>
        </form>
    </div>
</div>
@endsection
