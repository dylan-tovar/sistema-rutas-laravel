<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverDashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// dashboard
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


// admin
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    //users
    Route::get('/admin/dashboard/gestion/usuarios', [AdminDashboardController::class, 'userview'])->name('admin.users');
    Route::post('/admin/dashboard/gestion/usuarios/{user}', [AdminDashboardController::class, 'userupdate'])->name('admin.users.update');

    //vehicles
    Route::get('/admin/dashboard/gestion/vehiculos', [AdminDashboardController::class, 'vehicleview'])->name('admin.vehicles');
    Route::post('/admin/dashboard/gestion/vehiculos', [AdminDashboardController::class, 'vehiclestore'])->name('admin.vehicles.store');
    Route::put('/admin/dashboard/gestion/vehiculos/{id}', [AdminDashboardController::class, 'vehicleupdate'])->name('admin.vehicles.update');
    Route::delete('/admin/dashboard/gestion/vehiculos/{vehicle}', [AdminDashboardController::class, 'vehicledestroy'])->name('admin.vehicles.destroy');

    //rutas
    Route::get('/admin/dashboard/nueva_ruta', [AdminDashboardController::class, 'newroute'])->name('admin.new.route');
    Route::get('/admin/dashboard/rutas', [AdminDashboardController::class, 'routesshow'])->name('admin.routes');
    Route::post('admin/dashboard/nueva_ruta', [AdminDashboardController::class, 'routesstore'])->name('admin.route.store');
    Route::get('/admin/dashboard/ruta/{idRuta}', [AdminDashboardController::class, 'routesshowdetails'])->name('admin.route.details');
    Route::put('/admin/dashboard/ruta/{id}/asignar', [AdminDashboardController::class, 'routeassign'])->name('admin.route.assign');
    Route::put('/admin/dashboard/rutas/{id}/eliminar', [AdminDashboardController::class, 'routecancel'])->name('admin.route.cancel');
    Route::get('/admin/dashboard/rutas/historial', [AdminDashboardController::class, 'routeshistory'])->name('admin.routes.history');

    //reportes
    Route::get('/admin/dashboard/reportes', [AdminDashboardController::class, 'reportsindex'])->name('admin.reports');
    Route::get('/admin/dashboard/reportes/vehiculos', [AdminDashboardController::class, 'reportvehicle'])->name('admin.report.vehicle');
    Route::get('/admin/dashboard/reportes/conductores', [AdminDashboardController::class, 'reportdrivers'])->name('admin.report.driver');
    Route::get('/admin/dashboard/reportes/general', [AdminDashboardController::class, 'reportgeneral'])->name('admin.report.general');
    Route::get('/admin/dashboard/reportes/ruta/{id}', [AdminDashboardController::class, 'reportroute'])->name('admin.report.route');

     //config
     Route::get('/admin/dashboard/perfil', [UserDashboardController::class, 'settings'])->name('admin.profile.edit');
     Route::post('/admin/dashboard/perfil', [UserDashboardController::class, 'updateprofile'])->name('admin.profile.update');
});

// user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    //address
    Route::get('/dashboard/mis_direcciones', [UserDashboardController::class, 'myaddresses'])->name('user.my.address');

    Route::get('/dashboard/direcciones/nueva', [UserDashboardController::class, 'createaddress'])->name('user.address');
    Route::post('/dashboard/direcciones/nueva', [UserDashboardController::class, 'addressstore'])->name('user.address.store');
    Route::delete('/dashboard/direcciones/{id}', [UserDashboardController::class, 'addressdestroy'])->name('user.address.destroy');

    //pedidos
    Route::get('/dashboard/mis_pedidos', [UserDashboardController::class, 'myorders'])->name('user.my.orders');

    Route::get('/dashboard/pedidos/nuevo', [UserDashboardController::class, 'createorder'])->name('user.order');
    Route::post('/dashboard/pedidos/nuevo', [UserDashboardController::class, 'orderstore'])->name('user.order.store');
    Route::get('/dashboard/pedidos/historial', [UserDashboardController::class, 'orderhistory'])->name('user.orders.history');

    //config
    Route::get('/dashboard/perfil', [UserDashboardController::class, 'settings'])->name('user.profile.edit');
    Route::post('/dashboard/perfil', [UserDashboardController::class, 'updateprofile'])->name('user.profile.update');

});


//driver
Route::middleware('auth', 'role:driver')->group(function () {
    Route::get('/repartidor/dashboard', [DriverDashboardController::class, 'index'])->name('driver.dashboard');

    //rutas activas
    Route::get('/repartidor/dashboard/rutas_activas', [DriverDashboardController::class, 'routesactive'])->name('driver.routes');
    Route::put('/repartidor/dashboard/pedidos/{id}/update', [DriverDashboardController::class, 'routesupdate'])->name('driver.orders.update');
    Route::get('/repartidor/dashboard/pedidos/{idRuta}/reporte', [DriverDashboardController::class, 'reportroute'])->name('driver.route.report');




    //config
    Route::get('/repartidor/dashboard/perfil', [DriverDashboardController::class, 'settings'])->name('driver.profile.edit');
    Route::post('/repartidor/dashboard/perfil', [DriverDashboardController::class, 'updateprofile'])->name('driver.profile.update');
});


// Route::get('/manage-roles', [UserRoleController::class, 'index'])->name('manage.roles');
// Route::post('/update-role/{user}', [UserRoleController::class, 'update'])->name('update.role');

// Route::put('/post/{id}', function (string $id) {
//     // ...
// })->middleware(EnsureUserHasRole::class.':editor');
