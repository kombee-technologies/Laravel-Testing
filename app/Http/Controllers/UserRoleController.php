<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('user_roles.index', compact('users', 'roles'));
    }

    public function attachRole(Request $request, User $user)
    {
        $user->roles()->attach($request->role_id);
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    public function detachRole(User $user, Role $role)
    {
        $user->roles()->detach($role->id);
        return redirect()->back()->with('success', 'Role removed successfully.');
    }
}

