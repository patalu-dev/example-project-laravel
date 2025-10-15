<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name');
        return Inertia::render('users/table/actions/SyncRole', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::find($id);
        $user->syncRoles($request->roles);

        return to_route('users.index');
    }
}
