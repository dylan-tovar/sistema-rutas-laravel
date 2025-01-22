<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    //
    public function index()
{
    $userId = Auth::id();

    $userOrders = DB::table('orders')->where('user_id', $userId)->count();
    $completedOrders = DB::table('orders')->where('user_id', $userId)->where('status', 'complete')->count();
    $pendingOrders = DB::table('orders')->where('user_id', $userId)->where('status', 'pending')->count();

    $lastUserOrders = DB::table('orders')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $userRoutes = DB::table('routes')
        ->join('route_orders', 'routes.id', '=', 'route_orders.route_id')
        ->join('orders', 'route_orders.order_id', '=', 'orders.id')
        ->where('orders.user_id', $userId)
        ->where('routes.status', 'active')
        ->select('routes.id', 'routes.distance') // Seleccionamos los campos necesarios
        ->distinct() // Aseguramos que las rutas sean únicas
        ->get();

    $userDistance = DB::table('routes')
        ->join('route_orders', 'routes.id', '=', 'route_orders.route_id')
        ->join('orders', 'route_orders.order_id', '=', 'orders.id')
        ->where('orders.user_id', $userId)
        ->distinct()
        ->sum('routes.distance');

    return view('dashboard/user/index', [
        'breadcrumbs' => [
            ['name' => 'Dashboard', 'url' => '/user/dashboard'],
            ['name' => 'Inicio', 'url' => null],
        ],
        'metrics' => [
            'userOrders' => $userOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'userDistance' => $userDistance,
        ],
        'lastUserOrders' => $lastUserOrders,
        'userRoutes' => $userRoutes,
    ]);
}

    


    public function myaddresses() {

        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();

        return view('dashboard/user/directions/mydirections', compact('addresses') ,[
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Direcciones', 'url' => null],
                ['name' => 'Mis Direcciones', 'url' => null],
            ],
        ]);
    }


    public function createaddress() {

        $mapboxToken = config('services.mapbox.access_token');

        return view('dashboard/user/directions/newdirection', compact('mapboxToken') ,[
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Direcciones', 'url' => null],
                ['name' => 'Nueva Dirección', 'url' => null],
            ],
        ]);
    }

    public function addressstore(Request $request) {

        $request->validate([
            'address_name' => 'required|string|max:255',
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            
        ]);

        $request->user()->addresses()->create([
            'address_name' => $request->address_name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('user.my.address')->with('success', 'Dirección creada correctamente.');
    }

        public function addressdestroy($id)
    {
        Address::findOrFail($id)->delete();    
        // Redirigir con un mensaje de éxito
        return redirect()->route('user.my.address')->with('success', 'Dirección eliminada correctamente.');
    }


    //pedidos
    public function myorders() {
        
        $user = Auth::user();
        $orders = Order::with('user', 'address')
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'en ruta'])
                    ->get();

        return view('dashboard/user/orders/myorders', compact('orders'), [
           'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Pedidos', 'url' => null],
                ['name' => 'Mis Pedidos', 'url' => null],
            ],
        ]);
    }

    public function createorder() {
        // $users = User::all(); // Usuarios disponibles
        // $addresses = Address::all(); // Direcciones disponibles
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('dashboard/user/orders/neworder', compact('user', 'addresses'), [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Pedidos', 'url' => null],
                ['name' => 'Nuevo Pedido', 'url' => null],
            ],
        ]);
    }

    public function orderstore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Order::create($request->all());

        return redirect()->route('user.my.orders')->with('success', 'Pedido creado correctamente.');
    }

    // public function orderupdate(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'address_id' => 'required|exists:addresses,id',
    //         'status' => 'required|string',
    //         'description' => 'nullable|string',
    //     ]);

    //     $order->update($request->all());

    //     return redirect()->route('user.my.orders')->with('success', 'Pedido actualizado correctamente.');
    // }

    // public function orderdestroy(Order $order)
    // {
    //     $order->delete();
    //     return redirect()->route('user.my.orders')->with('success', 'Pedido eliminado correctamente.');
    // }

    public function orderhistory () {

        $user = Auth::user();
        $orders = Order::with('user', 'address')
                    ->where('user_id', $user->id)
                    ->get();

        return view('dashboard/user/orders/myorders', compact('orders'), [
           'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Pedidos', 'url' => null],
                ['name' => 'Mis Pedidos', 'url' => null],
            ],
        ]);

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
    
        return redirect()->route('user.profile.edit')->with('success', 'Perfil actualizado correctamente.');
     }

}
