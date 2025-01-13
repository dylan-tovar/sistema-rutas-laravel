<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestión de Roles</h1>

        @if(session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-400 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300 bg-white">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Nombre</th>
                    <th class="border border-gray-300 p-2">Correo Electrónico</th>
                    <th class="border border-gray-300 p-2">Rol Actual</th>
                    <th class="border border-gray-300 p-2">Actualizar Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $user->id }}</td>
                        <td class="border border-gray-300 p-2">{{ $user->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $user->email }}</td>
                        <td class="border border-gray-300 p-2">
                            @if($user->roles->isNotEmpty())
                                {{ $user->roles->pluck('name')->join(', ') }}
                            @else
                                <span class="text-gray-500">Sin Rol</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2">
                            <form action="{{ route('update.role', $user->id) }}" method="POST">
                                @csrf
                                <select name="role_id" class="border border-gray-300 rounded p-1">
                                    <option value="" disabled selected>Seleccionar Rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" 
                                            {{ $user->roles->contains($role) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="ml-2 bg-blue-500 text-white px-2 py-1 rounded">
                                    Actualizar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
