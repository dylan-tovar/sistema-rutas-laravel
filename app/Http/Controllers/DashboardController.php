<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard/index', [
            'breadcrumbs' => [
                // ['name' => 'Inicio', 'url' => '/'],
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Inicio', 'url' => null], 
            ],
        ]);
    }
}
