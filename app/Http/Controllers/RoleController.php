<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;

        try {

            $query = Role::query();

            if ($request->filled('keyword')) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            }

            $roles = $query->paginate($perPage, ['*'], 'page', $request->get('page', 1));

            return Inertia::render('roles/Index', [
                'roles' => $roles->items(),
                'pagination' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total(),
                    'from' => $roles->firstItem() ?? 0,
                    'to' => $roles->lastItem() ?? 0,
                ]
            ]);
        } catch (\Exception $e) {
            return Inertia::render('users/Index', [
                'error' => 'Kết nối thất bại: ' . $e->getMessage()
            ]);
        }
    }

    public function create() {}

    public function store(Request $request)
    {
        Role::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function show(string $id) {}
    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        Role::where('id', $id)
            ->update([
                'name' => $request->name
            ]);

        return back();
    }

    public function destroy(string $id)
    {
        Role::where('id', $id)
            ->delete();

        return back();
    }
}
