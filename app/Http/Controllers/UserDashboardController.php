<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    //
    public function myaddresses() {

        $addresses = Address::all();

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
        $orders = Order::with('user', 'address')->get();
        return view('dashboard/user/orders/myorders', compact('orders'), [
           'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Pedidos', 'url' => null],
                ['name' => 'Mis Pedidos', 'url' => null],
            ],
        ]);
    }

    public function createorder() {
        $users = User::all(); // Usuarios disponibles
        $addresses = Address::all(); // Direcciones disponibles
        return view('dashboard/user/orders/neworder', compact('users', 'addresses'), [
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

    public function orderupdate(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $order->update($request->all());

        return redirect()->route('user.my.orders')->with('success', 'Pedido actualizado correctamente.');
    }

    public function orderdestroy(Order $order)
    {
        $order->delete();
        return redirect()->route('user.my.orders')->with('success', 'Pedido eliminado correctamente.');
    }

}
