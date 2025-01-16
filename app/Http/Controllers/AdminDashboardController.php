<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        // $rutaspendientes = DB::table('routes')
        //     ->leftJoin()


        return view('dashboard/admin/rutas/routelist', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => null],
            ]
        ]);
    }


    public function routesstore(Request $request) {
        
        // depot (la ubicación inicial de la ruta)
        $depot = [
            'lat' => 10.500000,
            'lon' => -66.916664
        ];
    
        // Inicializa las direcciones con el depot
        $direcciones = [$depot];
        $idsPedidos = [];
    
        // Obtener los repartidores disponibles
        $repartidor = DB::table('users as u')
            ->join('role_user as rl', 'u.id', '=', 'rl.user_id')
            ->leftJoin('vehicles as v', 'u.id', '=', 'v.user_id')
            ->where('rl.role_id', 3)
            ->whereNull('v.user_id')
            ->select('u.id', 'u.name')
            ->get();
    
        
        if ($repartidor->isEmpty()) {
            return back()->withErrors(['error' => 'No hay repartidores disponibles.']);
        }
    
        // valid para el cálculo manual o automático
        if ($request->has('calcular_manual')) {
            $orders = $request->input('orders', []); 
            if (!is_array($orders)) {
                return back()->withErrors(['error' => 'El formato de orders no es válido.']);
            }
    
            
            $pedidos = DB::table('orders as o')
                ->join('addresses as a', 'o.address_id', '=', 'a.id') 
                ->whereIn('o.id', $orders)
                ->select('o.id', 'a.latitude', 'a.longitude') 
                ->get();
        } else {
            
            $pedidos = DB::table('orders as o')
                ->join('addresses as a', 'o.address_id', '=', 'a.id')
                ->where('o.status', 'pending') 
                ->select('o.id', 'a.latitude', 'a.longitude') 
                ->get();
        }
    
        
        foreach ($pedidos as $pedido) {
            
            if (!is_numeric($pedido->latitude) || !is_numeric($pedido->longitude)) {
                return back()->withErrors(['error' => 'Una o más direcciones tienen coordenadas inválidas.']);
            }
    
            
            $direcciones[] = ['lat' => $pedido->latitude, 'lon' => $pedido->longitude];
            $idsPedidos[] = $pedido->id;
        }
    
        // LLamada a la API para optimizar la ruta
        try {
            $response = Http::post('http://127.0.0.1:5000/optimizar_ruta', [
                'direcciones' => $direcciones,
                'vehiculos_disponibles' => 1,
            ])->json();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al contactar con la API: ' . $e->getMessage()]);
        }
    
        // Verificar si la API devolvió un error
        if (isset($response['error'])) {
            return back()->withErrors(['error' => $response['error']]);
        }
    
        // Creación de ruta
        $idRuta = DB::table('routes')->insertGetId([
            'status' => 'active',
            'distance' => $response['distancia_total'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Guardar las órdenes en la tabla route_orders
        if (isset($response['rutas']) && is_array($response['rutas']) && isset($response['rutas'][0])) {
            foreach ($response['rutas'][0] as $orden => $parada) {
                $idPedido = $idsPedidos[$parada - 1] ?? null;
    
                if ($idPedido) {
                    DB::table('route_orders')->insert([
                        'route_id' => $idRuta,
                        'order_id' => $idPedido,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            return back()->withErrors(['error' => 'La respuesta de la API no contiene rutas válidas.']);
        }
    
        // Retornar con mensaje de éxito
        return redirect()->route('admin.routes')->with('success', 'Ruta optimizada creada exitosamente');
    }
    


    // Mostrar todas las rutas o los detalles de una ruta específica
    public function routesshow() {
        

            // Cargar todas las rutas activas
            $routes = Route::with('driver')->where('status', 'active')->get();

            return view('dashboard/admin/rutas/routelist', [
                'routes' => $routes,
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                    ['name' => 'Rutas', 'url' => null],
                ],
            ]);
    }
    

    public function routesshowdetails ($idRuta) {

        $route = Route::with(['driver', 'orders'])->find($idRuta);

        if (!$route) {
            return redirect()->route('admin.routes')->withErrors('Ruta no encontrada');
        }
    
        // Recuperar las paradas (pedidos) asociadas con la ruta
        $stops = $route->orders;
    
        return view('dashboard/admin/rutas/details', [
            'route' => $route,
            'stops' => $stops,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => '/admin/dashboard/rutas'],
                ['name' => 'Ruta ' . $idRuta, 'url' => null],
            ],
        ]);
    }


}


