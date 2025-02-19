<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;


class RoleController extends Controller
{
    // Show all users with their roles
    public function index()
    {
        $users = User::with('roles')->get(); // Load users with roles
        $roles = Role::all(); // Get all roles
    
        return view('roles.index', compact('users', 'roles'));
    }
    

    // Assign role to user
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role_id);

        $user->roles()->attach($role->id); // Assign role
        return redirect()->route('roles.index')->with('success', 'Role assigned successfully.');
    }

    // Remove role from user
    public function removeRole($userId, $roleId)
    {
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);
    
        Log::info("Removing role {$role->name} from user {$user->first_name}");
    
        if ($user->roles()->where('role_id', $roleId)->exists()) {
            $user->roles()->detach($roleId);
            Log::info("Role removed successfully.");
        } else {
            Log::warning("Role not found in pivot table!");
        }
    
        return redirect()->back()->with('success', 'Role removed successfully.');
    }


    public function manageRoles($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('roles.manage', compact('user', 'roles'));
    }
}
