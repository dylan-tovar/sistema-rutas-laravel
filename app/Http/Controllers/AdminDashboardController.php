<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index() {
        return view('dashboard/index', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Inicio', 'url' => null], 
            ],
        ]);
    }


    /**
     * USUARIOS
     */

    public function userview() {

        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('dashboard/admin/gestion/users', compact('users', 'roles'), [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Gestion', 'url' => null],
                ['name' => 'Usuarios', 'url' => null],
            ],
        ]);
    }

    public function userupdate(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Sincroniza el rol con el usuario
        $user->roles()->sync([$request->role_id]);

        return redirect()->route('admin.users')->with('success', 'Rol actualizado correctamente.');
    }
    

    /**
     * VEHICULOS
     */
    
    public function vehicleview() {

        $vehicles = Vehicle::with('user')->get();
        $drivers = User::whereHas('roles', function ($query) {
            $query->where('name', 'driver');
        })->get();

        return view('dashboard/admin/gestion/vehicles', compact('vehicles', 'drivers') ,[
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Gestion', 'url' => null],
                ['name' => 'Vehículos', 'url' => null],
            ],
        ]);
    }

    public function vehiclestore(Request $request) {
        $request->validate([
            'make' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year' => ['required', 'integer'],
            'status' => ['required', 'in:disponible,en uso,en mantenimiento'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->status === 'en uso') {
            $this->validateDriver($request->user_id);
        }

        Vehicle::create($request->all());
        return back()->with('success', 'Vehículo agregado exitosamente.');
    }

    public function vehicleupdate(Request $request, $id)
    {
        $request->validate([
            'make' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year' => ['required', 'integer'],
            'status' => ['required', 'in:disponible,en uso,en mantenimiento'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->status === 'en uso') {
            $this->validateDriver($request->user_id);
        }

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());
        return back()->with('success', 'Vehículo actualizado exitosamente.');
    }


    public function vehicledestroy($id)
    {
        Vehicle::findOrFail($id)->delete();
        return back()->with('success', 'Vehículo eliminado exitosamente.');
    }

    private function validateDriver($user_id)
    {
        if (!User::where('id', $user_id)->whereHas('roles', function ($query) {
            $query->where('name', 'driver');
        })->exists()) {
            abort(400, 'El usuario asignado debe tener el rol "driver".');
        }
    }



    /**
     * RUTAS
     */

    public function newroute() {
        // mysql> select o.id, a.address, o.status, u.email from orders as o join addresses as a on o.address_id = a.id join users as u on o.user_id = u.id where o.status = 'pending';
        $pedidos = DB::table('orders as o')
            ->join('addresses as a', 'o.address_id', '=', 'a.id')
            ->join('users as u', 'o.user_id', '=', 'u.id')
            ->select('o.id', 'a.address', 'o.status', 'u.email')
            ->where('o.status', 'pending')
            ->get();

        return view('dashboard/admin/rutas/newroute', compact('pedidos'), [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => '/admin/dashboard/rutas'],
                ['name' => 'Nueva ruta', 'url' => null],
            ]
        ]);
    }


    public function routesview() {

        //
        return view('dashboard/admin/rutas/routelist', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => null],
            ]
        ]);
    }


    public function routesstore(Request $request) {
        
        // depot
        $depot = [
            'lat' => 10.500000,
            'lon' => -66.916664
        ];

        $direcciones = $depot;
        $idsPedidos = [];

        // mysql> select u.id, u.name from users as u join role_user as rl on u.id = rl.user_id left join vehicles as v on u.id = v.user_id where rl.role_id = 3 and v.user_id is null;
        $repartidor = DB::table('users as u')
            ->join('role_user as rl', 'u.id', '=', 'rl.user_id')
            ->leftJoin('vehicles as v', 'u.id', '=', 'v.user_id')
            ->where('rl.role_id', 3)
            ->whereNull('v.user_id')
            ->select('u,id', 'u.name')
            ->get();


        if ($repartidor->isEmpty()) {
            return back()->withErrors(['error' => 'No hay repartidores disponibles.']);
        }

        // automatico o manual
        if ($request->has('calcular_manual')) {
            # code...
        } else {
            # code...
        }
    }



}


