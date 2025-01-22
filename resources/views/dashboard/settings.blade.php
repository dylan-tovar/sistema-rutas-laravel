@extends('layouts.AppLayout')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <h1 class="text-3xl font-bold mb-4">Editar Perfil</h1>

        <!-- Mostrar mensaje de éxito -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg border border-green-300 dark:bg-[#213a2a] dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-sText dark:text-sTextDark">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-sText dark:bg-[#18181a] dark:text-white">
                @error('name') 
                    <p class="text-red-500 text-xs">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold text-sText dark:text-sTextDark">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-sText dark:bg-[#18181a] dark:text-white">
                @error('email') 
                    <p class="text-red-500 text-xs">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-sText dark:text-sTextDark">Contraseña (opcional)</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-sText dark:bg-[#18181a] dark:text-white">
                @error('password') 
                    <p class="text-red-500 text-xs">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-semibold text-sText dark:text-sTextDark">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-sText dark:bg-[#18181a] dark:text-white">
            </div>

            <!-- Botón para actualizar -->
            <button type="submit" class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection
