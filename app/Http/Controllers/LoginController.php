<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show() {
        return view('login/login'); // Vista del formulario de login
    }

    public function login(Request $request) {
        // Validaciones
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // regirige al dashboard
            $user = Auth::user();
            if ($user->roles->contains('name', 'admin')) {
                return redirect()->intended('admin/dashboard')->with('success', '¡Inicio de sesión exitoso!');
            } 

            if ($user->roles->contains('name', 'driver')) {
                return redirect()->intended('repartidor/dashboard')->with('success', '¡Inicio de sesión exitoso!');
            }

            
            

            return redirect()->intended('dashboard')->with('success', '¡Inicio de sesión exitoso!');
        }

        // catch errors
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', '¡Has cerrado sesión!');
    }
}
