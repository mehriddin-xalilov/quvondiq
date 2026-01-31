<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('settings.users.index')
            ->with('success', 'Foydalanuvchi muvaffaqiyatli yaratildi!');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'] ?? $user->is_active,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('settings.users.index')
            ->with('success', 'Foydalanuvchi muvaffaqiyatli yangilandi!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('settings.users.index')
                ->with('error', 'O\'zingizni o\'chira olmaysiz!');
        }

        $user->delete();

        return redirect()->route('settings.users.index')
            ->with('success', 'Foydalanuvchi muvaffaqiyatli o\'chirildi!');
    }

    public function export()
    {
        $users = User::with('roles')->latest()->take(50)->get();
        return Excel::download(new UsersExport($users), 'users.xlsx');
    }
}
