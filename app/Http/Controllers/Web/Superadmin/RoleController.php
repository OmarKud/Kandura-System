<?php

namespace App\Http\Controllers\Web\Superadmin;

use App\service\Web\Superadmin\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends BaseSuperadminController
{
    public function __construct(private RoleService $service)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $q = trim((string)$request->query('q',''));
        $perPage = max(10, min((int)$request->query('per_page', 15), 50));

        $roles = $this->service->paginate($q, $perPage);

        return view('dashboard.superadmin.roles.index', compact('roles','q','perPage'));
    }

    public function create()
    {
        $permissions = $this->service->allPermissions();
        return view('dashboard.superadmin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['integer','exists:permissions,id'],
        ]);

        $this->service->create($data['name'], $data['permissions'] ?? []);

        return redirect()->route('dashboard.superadmin.roles.index')
            ->with('success','Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = $this->service->allPermissions();
        $role->load('permissions:id,name');

        $selected = $role->permissions->pluck('id')->all();

        return view('dashboard.superadmin.roles.edit', compact('role','permissions','selected'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190',"unique:roles,name,{$role->id}"],
            'permissions' => ['array'],
            'permissions.*' => ['integer','exists:permissions,id'],
        ]);

        $this->service->update($role, $data['name'], $data['permissions'] ?? []);

        return redirect()->route('dashboard.superadmin.roles.index')
            ->with('success','Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'superadmin') {
            return back()->withErrors(['role' => 'You cannot delete superadmin role.']);
        }

        $role->delete();

        return redirect()->route('dashboard.superadmin.roles.index')
            ->with('success','Role deleted successfully.');
    }
}
