@extends('layouts.AppLayout')

@section('title', 'Gestión de Vehículos')

@section('content')
<div class="container mx-auto px-4" x-data="vehicleManager()">
    <!-- Mensaje de éxito -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #EB5B38 , #F3A17E)",
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6">

            <div>
                <h1 class="text-3xl font-bold">Gestión de Vehículos</h1>
                <p class="mt-1 text-sm text-sText dark:text-sTextDark">
                    Aquí puedes gestionar los vehículos registrados en el sistema, incluyendo su estado y asignaciones.
                </p>
            </div>
            <div>
                <!-- Botón para agregar -->
                <button 
                    class="border border-flamingo-500 text-flamingo-500 hover:bg-flamingo-500 hover:text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150"
                    @click="openAddModal()">
                    Agregar Vehículo
                </button>
            </div>
            
        </div>

        <!-- Tabla de vehículos -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse overflow-hidden bg-[#FAFAFA] dark:bg-[#18181a] shadow-sm rounded-lg">
                <thead class="bg-accent dark:bg-pTxt text-gray-700 dark:text-gray-300">
                    <tr class="text-sm font-semibold">
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Marca</th>
                        <th class="p-4 text-left">Modelo</th>
                        <th class="p-4 text-left">Año</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-left">Usuario en uso</th>
                        <th class="p-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-base divide-gray-300 dark:divide-sText">
                    @foreach($vehicles as $vehicle)
                        <tr class="hover:bg-white dark:hover:bg-[#09090b42] transition-colors duration-150">
                            <td class="p-4">{{ $vehicle->id }}</td>
                            <td class="p-4">{{ $vehicle->make }}</td>
                            <td class="p-4">{{ $vehicle->model }}</td>
                            <td class="p-4">{{ $vehicle->year }}</td>
                            <td class="p-4">{{ ucfirst($vehicle->status) }}</td>
                            <td class="p-4">{{ $vehicle->user ? $vehicle->user->name : 'N/A' }}</td>
                            <td class="p-4 flex items-center space-x-4">
                                <button 
                                    class="text-blue-500 underline hover:text-blue-700"
                                    @click="openEditModal({{ $vehicle }})">
                                    Editar
                                </button>
                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline">
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

    <!-- Modal -->
    <div 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    x-show="modalOpen"
    @click.away="closeModal()">
        <div class="bg-white dark:bg-[#18181a] p-6 rounded-lg shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center border-b dark:border-sText border-sTextDark pb-4 mb-6 relative">
                <h2 class="text-2xl font-bold" x-text="modalForm.method === 'POST' ? 'Agregar Vehículo' : 'Editar Vehículo'"></h2>
                <button 
                    class="absolute top-0 right-0 p-2 "
                    @click="closeModal()">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <form method="POST" :action="modalForm.action">
                @csrf
                
                <template x-if="modalForm.method === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
            
                <!-- Campos del formulario -->
                <div class="mb-4">
                    <label class="block text-sm text-sText dark:text-sTextDark mb-2">Marca</label>
                    <input type="text" name="make" x-model="modalForm.make" required
                        class="w-full border border-accent dark:bg-[#222222] dark:border-sText rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-sText dark:text-sTextDark mb-2">Modelo</label>
                    <input type="text" name="model" x-model="modalForm.model" required
                        class="w-full border border-accent dark:bg-[#222222] dark:border-sText rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-sText dark:text-sTextDark mb-2">Año</label>
                    <input type="number" name="year" x-model="modalForm.year" required
                        class="w-full border border-accent dark:bg-[#222222] dark:border-sText rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-sText dark:text-sTextDark mb-2">Estado</label>
                    <select name="status" x-model="modalForm.status" required
                        class="w-full border border-accent dark:bg-[#222222] dark:border-sText rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                        <option value="disponible">Disponible</option>
                        <option value="en uso">En uso</option>
                        <option value="en mantenimiento">En mantenimiento</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-sText dark:text-sTextDark mb-2">Usuario (Solo "Drivers")</label>
                    <select name="user_id" x-model="modalForm.user_id"
                        class="w-full border border-accent dark:bg-[#222222] dark:border-sText rounded-md p-2 focus:ring-2 focus:ring-orange-400 text-gray-700 dark:text-gray-300">
                        <option value="">Ninguno</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Botón de guardar -->
                <button 
                    type="submit" 
                    class="w-full mt-4 bg-flamingo-400 dark:bg-flamingo-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-150">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    function vehicleManager() {
        return {
            modalOpen: false,
            modalForm: {
                action: '',
                method: 'POST',
                make: '',
                model: '',
                year: '',
                status: 'disponible',
                user_id: '',
            },

            openAddModal() {
                this.modalOpen = true;
                this.modalForm = {
                    action: "{{ route('admin.vehicles.store') }}",
                    method: 'POST',
                    make: '',
                    model: '',
                    year: '',
                    status: 'disponible',
                    user_id: '',
                };
            },

            openEditModal(vehicle) {
                this.modalOpen = true;
                this.modalForm = {
                    action: "{{ url('admin/dashboard/gestion/vehiculos') }}/" + vehicle.id,
                    method: 'PUT',
                    make: vehicle.make,
                    model: vehicle.model,
                    year: vehicle.year,
                    status: vehicle.status,
                    user_id: vehicle.user_id || '',
                };
            },

            closeModal() {
                this.modalOpen = false;
            },
        };
    }
</script>
@endsection
