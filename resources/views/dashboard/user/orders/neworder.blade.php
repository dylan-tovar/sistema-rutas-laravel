
@extends('layouts.AppLayout')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mx-auto px-4">

    <!-- Tarjeta Principal -->
    <div class="bg-[#FAFAFA] border border-gray-200 dark:border-pTxt dark:bg-[#18181a] shadow-inner dark:shadow-[#222222] rounded-xl p-6">
        <div class="container">
            <h1 class="mb-4">Crear Nueva Orden</h1>
        
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form action="{{ route('user.order.store') }}" method="POST">
                @csrf
        
                <div class="mb-3">
                    <label for="user_id" class="form-label">Selecciona un Usuario</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <option value="" disabled selected>Selecciona un usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mb-3">
                    <label for="address_id" class="form-label">Selecciona una Dirección</label>
                    <select id="address_id" name="address_id" class="form-select" required>
                        <option value="" disabled selected>Selecciona una dirección</option>
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}">{{ $address->address_name }} - {{ $address->address }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="pending">Pendiente</option>
                        <option value="in_progress">En Progreso</option>
                        <option value="completed">Completada</option>
                    </select>
                </div>
        
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción (opcional)</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
        
                <button type="submit" class="btn btn-success">Crear Orden</button>
                <a href="{{ route('user.my.orders') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

@endsection