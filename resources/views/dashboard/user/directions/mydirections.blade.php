
@extends('layouts.AppLayout')

@section('title', 'Nueva Dirección')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold">Gestión de Direcciones</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Aquí puedes gestionar las direcciones registradas y añadir nuevas.
                </p>
            </div>
            <div>
                <!-- Botón para agregar -->
                <a href="{{ route('user.address')}}"
                class="border border-flamingo-500 text-flamingo-500 dark:border-flamingo-600 dark:text-flamingo-600 hover:bg-flamingo-400 hover:text-white hover:dark:bg-flamingo-600 hover:dark:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                Agregar Dirección
                </a>
            </div>
        </div>

        <!-- Tabla de direcciones -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse overflow-hidden bg-[#FAFAFA] dark:bg-[#18181a] shadow-sm rounded-lg">
                <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                    <tr class="text-sm font-semibold">
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Nombre</th>
                        <th class="p-4 text-left">Dirección</th>
                        <th class="p-4 text-left">Latitud</th>
                        <th class="p-4 text-left">Longitud</th>
                        <th class="p-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-base divide-gray-300 dark:divide-sText">
                    @foreach($addresses as $address)
                        <tr class="hover:bg-white dark:hover:bg-[#09090b42] transition-colors duration-150">
                            <td class="p-4">{{ $address->id }}</td>
                            <td class="p-4">{{ $address->address_name }}</td> <!-- Cambiar a address_name -->
                            <td class="p-4">{{ $address->address }}</td> <!-- Cambiar a address -->
                            <td class="p-4">{{ $address->latitude }}</td> <!-- Cambiar a latitude -->
                            <td class="p-4">{{ $address->longitude }}</td> <!-- Cambiar a longitude -->
                            <td class="p-4 flex items-center space-x-4">
                                <form action="{{ route('user.address.destroy', $address->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        class="text-red-500 underline hover:text-red-700">
                                        Eliminar
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
