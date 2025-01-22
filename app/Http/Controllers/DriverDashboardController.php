<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverDashboardController extends Controller
{
    //

    public function index() {
        return view('dashboard/index', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/repartidor/dashboard'],
                ['name' => 'Inicio', 'url' => null], 
            ],
        ]);
    }



    /**
     * rutas activas
     */

    public function routesactive () {
       // Obtener el ID del usuario actualmente autenticado
    $userId = Auth::id();

    // Consultar la ruta activa asociada al usuario
    $route = Route::where('status', 'active')
        ->where('driver_id', $userId) // Filtrar por el usuario autenticado
        ->first();

    if (!$route) {
        return redirect()->route('admin.routes')->withErrors('No se encontró una ruta activa asociada al usuario actual.');
    }

    // Obtener las paradas asociadas a la ruta activa
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
            'users.email',
            'users.name',
            'orders.status',
        )
        ->where('route_orders.route_id', $route->id)
        ->get();

    return view('dashboard/driver/rutas', [
        'route' => $route,
        'stops' => $stops, // Paradas asociadas a la ruta
        'breadcrumbs' => [
            ['name' => 'Dashboard', 'url' => '/repartidor/dashboard'],
            ['name' => 'Ruta Activa', 'url' => null],
        ],
    ]);
    }

    



    public function routesupdate($id) {
        $order = DB::table('orders')->where('id', $id)->first();
    
        if (!$order) {
            return redirect()->back()->withErrors('La orden no existe.');
        }
    
        // Actualizar el estado de la orden a 'entregada'
        DB::table('orders')
            ->where('id', $id)
            ->update(['status' => 'entregada', 'updated_at' => now()]);
    
        // Obtener la ruta asociada a la orden
        $routeId = DB::table('route_orders')
            ->where('order_id', $id)
            ->value('route_id');
    
        // Depuración: Verificar el route_id
        dd("Route ID: " . $routeId);
    
        if ($routeId) {
            // Verificar si todas las órdenes asociadas a esta ruta están entregadas
            $allDelivered = DB::table('routes as r')
                ->join('route_orders as ro', 'ro.route_id', '=', 'r.id')
                ->join('orders as o', 'o.id', '=', 'ro.order_id')
                ->select(
                    'r.id as route_id',
                    DB::raw('COUNT(CASE WHEN o.status = "en ruta" THEN 1 END) as in_route_count')
                )
                ->where('r.id', $routeId)  // Aquí especificamos el ID de la ruta
                ->groupBy('r.id')
                ->first();
    
            // Depuración: Verificar si se obtuvo el resultado
            dd($allDelivered);
    
            // Verificamos si el conteo de órdenes en ruta es 0
            if ($allDelivered && $allDelivered->in_route_count == 0) {
                // Si todas las órdenes están entregadas, actualizamos el estado de la ruta a 'completada'
                DB::table('routes')
                    ->where('id', $routeId)
                    ->update(['status' => 'completada', 'updated_at' => now()]);
            }
        }
    
        return redirect()->back()->with('success', 'El estado de la orden se actualizó correctamente.');
    }
    
    
    
    
    

    

    //reporte
    public function reportroute($idRuta)
{
    // Obtener la información de la ruta
    $route = DB::table('routes')
        ->select('routes.*')
        ->where('routes.id', $idRuta)
        ->first();

    if (!$route) {
        return redirect()->route('driver.routes')->withErrors('Ruta no encontrada');
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

    // Datos del reporte
    $title = 'Reporte de Ruta ' . $idRuta;
    $date = date('d/m/Y');

    // Generar PDF
    $pdf = Pdf::loadView('dashboard/driver/reporteruta', compact('title', 'date', 'route', 'stops'));

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
                ['name' => 'Dashboard', 'url' => '/repartidor/dashboard'],
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
    
        return redirect()->route('driver.profile.edit')->with('success', 'Perfil actualizado correctamente.');
     }
}
