<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Exports\RolesExport;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->name)[1] ?? 'other';
        });
        
        return view('settings.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        
        if (isset($validated['permissions'])) {
            $role->givePermissionTo($validated['permissions']);
        }

        return redirect()->route('settings.roles.index')
            ->with('success', 'Rol muvaffaqiyatli yaratildi!');
    }

    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('settings.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->name)[1] ?? 'other';
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('settings.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('settings.roles.index')
            ->with('success', 'Rol muvaffaqiyatli yangilandi!');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('settings.roles.index')
                ->with('error', 'Bu rolga biriktirilgan foydalanuvchilar mavjud!');
        }

        $role->delete();

        return redirect()->route('settings.roles.index')
            ->with('success', 'Rol muvaffaqiyatli o\'chirildi!');
    }

    public function export()
    {
        $roles = Role::with('permissions')->latest()->take(50)->get();
        return Excel::download(new RolesExport($roles), 'roles.xlsx');
    }
}
