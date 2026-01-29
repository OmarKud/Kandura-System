<?php

namespace App\Http\Controllers\Web\Superadmin;

use App\Models\User;
use App\service\Web\Superadmin\AdminService;
use Illuminate\Http\Request;

class AdminController extends BaseSuperadminController
{
    public function __construct(private AdminService $service)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $q = trim((string)$request->query('q',''));
        $perPage = max(10, min((int)$request->query('per_page', 15), 50));

        $admins = $this->service->paginateAdmins($q, $perPage);

        return view('dashboard.superadmin.admins.index', compact('admins','q','perPage'));
    }

    public function create()
    {
        $roles = $this->service->rolesForSelect();
        return view('dashboard.superadmin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:190'],
            'email' => ['required','email','max:190','unique:users,email'],
            'phone' => ['required','string','max:40','unique:users,phone'],
            'status' => ['required','in:active,inactive'],
            'role_id' => ['required','integer','exists:roles,id'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $this->service->createAdmin($data, (int)$data['role_id']);

        return redirect()->route('dashboard.superadmin.admins.index')
            ->with('success','Admin created successfully.');
    }

    public function edit(User $admin)
    {
        $roles = $this->service->rolesForSelect();
        return view('dashboard.superadmin.admins.edit', compact('admin','roles'));
    }



public function editRole(User $admin)
{
    $roles = $this->service->rolesForSelect();

    return view('dashboard.superadmin.admins.edit-role', compact('admin', 'roles'));
}

public function updateRole(Request $request, User $admin)
{
    $data = $request->validate([
        'role_id' => ['required', 'integer', 'exists:roles,id'],
    ]);

    $this->service->updateAdminRole($admin, (int)$data['role_id']);

    return redirect()->route('dashboard.superadmin.admins.index')
        ->with('success', 'Role updated successfully.');
}

public function destroy(User $admin)
{
    $this->service->deleteAdmin($admin);

    return redirect()->route('dashboard.superadmin.admins.index')
        ->with('success', 'Admin deleted successfully.');
}

}
