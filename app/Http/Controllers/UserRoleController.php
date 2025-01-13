<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function view() {

        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('dashboard/admin/gestion/users', compact('users', 'roles'), [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => '/dashboard'],
                ['name' => 'Gestion', 'url' => null],
                ['name' => 'Usuarios', 'url' => null],
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Sincroniza el rol con el usuario
        $user->roles()->sync([$request->role_id]);

        return redirect()->route('admin.users')->with('success', 'Rol actualizado correctamente.');
    }
}