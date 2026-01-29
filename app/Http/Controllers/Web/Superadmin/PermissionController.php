<?php

namespace App\Http\Controllers\Web\Superadmin;

use App\service\Web\Superadmin\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseSuperadminController
{
    public function __construct(private PermissionService $service)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $q = trim((string)$request->query('q',''));
        $perPage = max(10, min((int)$request->query('per_page', 15), 50));

        $permissions = $this->service->paginate($q, $perPage);

        return view('dashboard.superadmin.permissions.index', compact('permissions','q','perPage'));
    }

    public function create()
    {
        return view('dashboard.superadmin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:permissions,name'],
        ]);

        $this->service->create($data['name']);

        return redirect()->route('dashboard.superadmin.permissions.index')
            ->with('success','Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('dashboard.superadmin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190',"unique:permissions,name,{$permission->id}"],
        ]);

        $this->service->update($permission, $data['name']);

        return redirect()->route('dashboard.superadmin.permissions.index')
            ->with('success','Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $this->service->delete($permission);

        return redirect()->route('dashboard.superadmin.permissions.index')
            ->with('success','Permission deleted successfully.');
    }
}
