<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Role;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use function PHPSTORM_META\map;

class AdminDashboardController extends Controller
{
    public function index() {
        $allDistance = DB::table('routes')->sum('distance');
        $activeRoutes = DB::table('routes')->where('status', 'active')->count();
        $completedRoutes = DB::table('routes')->where('status', 'complete')->count();
        $orders = DB::table('orders')->select('id')->count();
        $routes = Route::all();
        $pendingOrders = DB::table('orders')->where('status', 'pending')->count();        
        $lastOrders = DB::table('orders')->orderBy('created_at', 'desc')->take(5)->get();
        $addresses = Address::all(['latitude', 'longitude']);



         // Obtener la fecha de hace 7 días
            $sevenDaysAgo = Carbon::now()->subDays(7);

            // Obtener el número de órdenes creadas en los últimos 7 días
            $ordersCount = Order::where('created_at', '>=', $sevenDaysAgo)
                ->count(); // Contamos el total de órdenes creadas en los últimos 7 días

            // Obtener el número de órdenes creadas por día en los últimos 7 días
            $ordersByDay = [];
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $count = Order::whereDate('created_at', $day->toDateString())->count();
                $ordersByDay[] = $count;
            }


             // Obtener los vehículos que están en uso o en mantenimiento
            // $vehicles = Vehicle::whereIn('status', ['en uso', 'en mantenimiento'])
            //     ->get();

            $vehicles = DB::table('vehicles as v')
                ->leftJoin('users as u', 'v.user_id', '=', 'u.id')
                ->select('v.model', 'v.make', 'v.status', 'u.name')
                ->whereIn('v.status', ['en uso', 'en mantenimiento'])
                ->get();

    
        return view('dashboard/admin/index', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Inicio', 'url' => null], 
            ],
            'metrics' => [
                'allDistance' => $allDistance,
                'activeRoutes' => $activeRoutes,
                'completedRoutes' => $completedRoutes,
                'pendingOrders' => $pendingOrders,
            ],
            'lastOrders' => $lastOrders,
            'ordersCount' => $ordersCount,
            'ordersByDay' => $ordersByDay,
            'vehicles' => $vehicles,
            'addresses' => $addresses,
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


    // public function routesview() {

    //     //
    //     // $rutaspendientes = DB::table('routes')
    //     //     ->leftJoin()


    //     return view('dashboard/admin/rutas/routelist', [
    //         'breadcrumbs' => [
    //             ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
    //             ['name' => 'Rutas', 'url' => null],
    //         ]
    //     ]);
    // }


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
            'status' => 'por asignar',
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

            // actualiza las orders a "en ruta"
            DB::table('orders')
                ->whereIn('id', $idsPedidos)
                ->update([
                    'status' => 'en ruta',
                    'updated_at' => now(),
                ]);

        } else {
            return back()->withErrors(['error' => 'La respuesta de la API no contiene rutas válidas.']);
        }
    
        // Retornar con mensaje de éxito
        return redirect()->route('admin.routes')->with('success', 'Ruta optimizada creada exitosamente');
    }
    


    // Mostrar todas las rutas o los detalles de una ruta específica
    public function routesshow() {
        $routes = Route::with('driver')->whereIn('status', ['por asignar', 'active'])->get();
        // dd($routes); // Esto debería mostrarte las rutas que estás obteniendo.
        return view('dashboard/admin/rutas/routelist', [
            'routes' => $routes,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => null],
            ],
        ]);
    }
    
    

    public function routesshowdetails ($idRuta) {


        // mysql> select u.id, u.name, u.email, r.name as role from users u join role_user ru on u.id = ru.user_id join roles r on ru.role_id = r.id left join vehicles v on u.id = v.user_id where r.id = 3 and v.id is null;
        $drivers = DB::table('users as u')
            ->join('role_user as ru', 'u.id', '=', 'ru.user_id')
            ->join('roles as r', 'ru.role_id', '=', 'r.id')
            ->leftJoin('vehicles as v', 'u.id', '=', 'v.user_id')
            ->where('r.id', 3)
            ->whereNull('v.id')
            ->select('u.id', 'u.name', 'u.email', 'r.name as role')
            ->get();

        $vehicles = DB::table('vehicles')
            ->where('status', 'disponible')
            ->whereNull('user_id')
            ->select('id', 'make', 'model', 'status', 'user_id')
            ->get();
        

        
        $route = DB::table('routes')
            ->select('routes.*')
            ->where('routes.id', $idRuta)
            ->first();

        if (!$route) {
            return redirect()->route('admin.routes')->withErrors('Ruta no encontrada');
        }

        
        $stops = DB::table('route_orders')
            ->join('orders', 'route_orders.order_id', '=', 'orders.id')
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->join('users', 'addresses.user_id', '=', 'users.id' )
            ->select(
                'orders.id as order_id',
                'orders.description',
                'addresses.latitude',
                'addresses.longitude',
                'addresses.address_name',
                'addresses.address',
                'users.email',
                'users.name',
            )
            ->where('route_orders.route_id', $idRuta)
            ->get();
            
        return view('dashboard/admin/rutas/details', [
            'route' => $route,
            'stops' => $stops,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Rutas', 'url' => '/admin/dashboard/rutas'],
                ['name' => 'Ruta ' . $idRuta, 'url' => null],
            ],
        ]);
    }

    public function routeassign(Request $request, $id)
    {

        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);
    
        $route = Route::findOrFail($id);
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
    
        // Verifica que el vehículo no esté asignado
        if ($vehicle->user_id) {
            return back()->with('error', 'El vehículo ya está asignado.');
        }
    
        // Asigna el conductor y el vehículo a la ruta
        $route->driver_id = $request->driver_id;
        $route->vehicle_id = $request->vehicle_id;
        $route->status = 'active';
        $route->save();
    
        // Marca el vehículo como asignado
        $vehicle->user_id = $request->driver_id;
        $vehicle->status = 'en uso';
        $vehicle->save();
    
        return redirect()->route('admin.route.details', $id)->with('success', 'Repartidor y vehículo asignados exitosamente.');
    }


    public function routecancel ($id) {
        $route = Route::findOrFail($id);

        $route->status = 'cancelada';
        $route->save();

        return redirect()->route('admin.routes')->with('success', 'La ruta se ha eliminado');
    }
    

    public function routeshistory () {
        $routes = Route::with('driver')->get();

        return view('dashboard/admin/rutas/routehistory', [
            'routes' => $routes,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Historial de Rutas', 'url' => null],
            ],
        ]);
    }


    /**
     * REPORTES
     */


     public function reportsindex () {

        $routes = Route::all();

        return view('dashboard/admin/reportes/index', [
            'routes' => $routes,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['name' => 'Reportes', 'url' => null],
            ]
            ]);
     }

     public function reportvehicle() {

        $vehicles = Vehicle::all();

        $title = 'Reporte de Vehiculos';
        $date = date('d/m/Y');

        $pdf = Pdf::loadview('dashboard/admin/reportes/reporte-vehiculo', compact('title', 'date', 'vehicles'));

        return $pdf->stream();

     }


     public function reportgeneral () {

        $allDistance = DB::table('routes')->sum('distance');
        $activeRoutes = DB::table('routes')->where('status', 'active')->count();
        $completedRoutes = DB::table('routes')->where('status', 'complete')->count();
        $orders = DB::table('orders')->select('id')->count();
        $routes = Route::all();
        

        $title = 'Reporte General';
        $date = date('d/m/Y');

        $pdf = Pdf::loadview('dashboard/admin/reportes/reporte-general', compact('title', 'date', 'allDistance', 'activeRoutes', 'completedRoutes', 'orders', 'routes'));
    
            return $pdf->stream();
     }

     public function reportdrivers () {

        // mysql> select u.name, u.email from users as u join role_user as ru on u.id = ru.user_id where ru.role_id = 3;
        // despues puedo agregar consultas mas complejas
        $drivers = DB::table('users as u')
            ->join('role_user as ru', 'u.id', '=', 'ru.role_id')
            ->where('ru.role_id', 3)
            ->select('u.id', 'u.name', 'u.email')
            ->get();

            $title = 'Reporte de Conductores';
            $date = date('d/m/Y');
    
            $pdf = Pdf::loadview('dashboard/admin/reportes/reporte-conductor', compact('title', 'date', 'drivers'));
    
            return $pdf->stream();
     }

     public function reportroute ($idRuta) {

         // Obtener la información de la ruta
    $route = DB::table('routes')
    ->select('routes.*')
    ->where('routes.id', $idRuta)
    ->first();

if (!$route) {
    return redirect()->route('admin.routes')->withErrors('Ruta no encontrada');
}

// Obtener las paradas (pedidos) asociadas a la ruta
$stops = DB::table('route_orders')
    ->join('orders', 'route_orders.order_id', '=', 'orders.id')
    ->join('addresses', 'orders.address_id', '=', 'addresses.id')
    ->join('users', 'addresses.user_id', '=', 'users.id')
    ->select(
        'orders.id as order_id',
        'orders.description',
        'addresses.latitude',
        'addresses.longitude',
        'addresses.address_name',
        'addresses.address',
        'users.email as user_email',
        'users.name as user_name'
    )
    ->where('route_orders.route_id', $idRuta)
    ->get();

// Información del conductor asignado a la ruta (si aplica)
$driver = DB::table('users')
    ->join('routes', 'users.id', '=', 'routes.driver_id')
    ->select('users.id', 'users.name', 'users.email')
    ->where('routes.id', $idRuta)
    ->first();

// Información del vehículo asignado a la ruta (si aplica)
$vehicle = DB::table('vehicles')
    ->join('routes', 'vehicles.id', '=', 'routes.vehicle_id')
    ->select('vehicles.id', 'vehicles.make', 'vehicles.model', 'vehicles.status')
    ->where('routes.id', $idRuta)
    ->first();

// Datos del reporte
$title = 'Reporte de Ruta ' . $idRuta;
$date = date('d/m/Y');

// Generar PDF
$pdf = Pdf::loadview('dashboard/admin/reportes/reporte-ruta', compact('title', 'date', 'route', 'stops', 'driver', 'vehicle'));

// Retornar el PDF al navegador
return $pdf->stream('reporte-ruta-' . $idRuta . '.pdf');

     }





     /**
     * config edit perfil
     */

     public function settings() {
        $user = Auth::user();

        return view('dashboard/settings', [
            'user' => $user,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Editar Perfil', 'url' => null],
            ]
        ]);
     }

     public function updateprofile (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed', // solo requerido si se desea cambiar
        ]);
    
        // Obtiene el usuario autenticado
        $user = Auth::user();
    
        // Actualiza el nombre y el email
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Si la contraseña está presente, actualízala
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return redirect()->route('admin.profile.edit')->with('success', 'Perfil actualizado correctamente.');
     }
}


