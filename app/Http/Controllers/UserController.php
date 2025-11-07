<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        try {

            $query = User::query();

            if ($request->filled('keyword')) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
                $query->orWhere('username', 'like', '%' . $request->keyword . '%');
                $query->orWhere('email', 'like', '%' . $request->keyword . '%');
            }

            $users = $query->paginate($perPage, ['*'], 'page', $request->get('page', 1));

            return Inertia::render('users/Index', [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem() ?? 0,
                    'to' => $users->lastItem() ?? 0,
                ]
            ]);
        } catch (\Exception $e) {
            return Inertia::render('users/Index', [
                'error' => 'Kết nối thất bại: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        return Inertia::render('users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|lowercase|max:255|unique:'.User::class,
            'email' => 'email|string|lowercase|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => '1'
        ]);

        return to_route('users.index');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|string|lowercase|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return to_route('users.index');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return to_route('users.index');
    }

    public function changeStatus(string $id)
    {
        $user = User::findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back();
    }

    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);

        $user->password = Hash::make('123456');
        $user->save();

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
            $query->orWhere('username', 'like', '%' . $request->keyword . '%');
            $query->orWhere('email', 'like', '%' . $request->keyword . '%');
        }

        $users = $query->get(['name', 'username', 'email','created_at']);

        return (new FastExcel($users))->download('users.csv', function ($user) {
            return [
                'Name' => $user->name,
                'Email' => $user->email,
                'Username' => $user->username,
                'Created At' => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
