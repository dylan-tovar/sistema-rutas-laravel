<!-- resources/views/home.blade.php -->
@extends('layouts.AppLayout')

@section('title', 'Gestión de Roles')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Tarjeta Principal -->
        <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
            <div class="border-b dark:border-sText border-sTextDark pb-4 mb-6">
                <h1 class="text-3xl font-bold">Gestión de Roles</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">Aquí puedes ver y gestionar la lista de usuarios registrados en el sistema.</p>
            </div>
            
            <!-- Mensaje de éxito -->
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Toastify({
                            text: "{{ session('success') }}",
                            duration: 3000, // Duración en milisegundos
                            close: true, // Botón de cerrar
                            gravity: "bottom", // `top` o `bottom`
                            position: "right", // `left`, `center` o `right`
                            backgroundColor: "linear-gradient(to right, #EB5B38 , #F3A17E)", // Colores
                            stopOnFocus: true, // Detener si pasa el ratón encima
                        }).showToast();
                    });
                </script>
            @endif


            <!-- Tabla de usuarios -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse overflow-hidden bg-[#FAFAFA] dark:bg-[#18181a] shadow-sm rounded-lg">
                    <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                        <tr class="text-sm font-semibold">
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">Nombre</th>
                            <th class="p-4 text-left">Correo Electrónico</th>
                            <th class="p-4 text-left">Rol Actual</th>
                            <th class="p-4 text-left">Actualizar Rol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-base divide-gray-300 dark:divide-sText">
                        @foreach($users as $user)
                            <tr class="hover:bg-white dark:hover:bg-[#09090b42] transition-colors duration-150">
                                <td class="p-4">{{ $user->id }}</td>
                                <td class="p-4">{{ $user->name }}</td>
                                <td class="p-4">{{ $user->email }}</td>
                                <td class="p-4">
                                    @if($user->roles->isNotEmpty())
                                        <span class="bg-gray-200 text-gray-700 dark:bg-pTxt dark:text-gray-300 text-sm font-semibold px-2 py-1 rounded">
                                            {{ $user->roles->pluck('name')->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 italic">Sin Rol</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <select name="role_id" class="border-gray-300 dark:border-gray-700 rounded-md p-2 focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white dark:bg-[#18181A] text-gray-700 dark:text-gray-300">
                                            <option value="" disabled selected>Seleccionar Rol</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" 
                                                    {{ $user->roles->contains($role) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="ml-4 bg-flamingo-400 dark:bg-flamingo-500 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                                            Actualizar
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
