<?php

namespace App\Http\Controllers;


use App\Http\Requests\Role\rolePermissionRequest;
use App\Http\Requests\Role\storeRoleRequest;
use App\Http\Requests\Role\updateRoleRequest;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date   = $request->input('date');
        $paginate   = $request->input('paginate', 10);

        $rolesQuery = Role::query()
            ->with('permissions:id,name')
            ->when($search, fn($q) => $q->search($search))
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->latest();
        $roles = $paginate === 'all'
            ? [
                'data' => $rolesQuery->get(),
                'total' => $rolesQuery->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $rolesQuery->count(),
            ]
            : $rolesQuery->paginate(intval($paginate));
        return Inertia::render('Roles/RoleIndex', [
            'roles' => $roles,
            'filters' => [
                'search' =>$search,
                'page'   => $request->input('page', 1),
                'date' => $date,
                'paginate' => $paginate
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        sleep(1);
        return response()->json([
            'permissions' => toOption(Permission::query()->get(), 'name', 'name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeRoleRequest $request)
    {
        $data = $request->validated();
        Role::create([
            'name' => $data['name'],
            'school_id' => school()->id,
            'guard_name' => 'staff',
        ]);
        return back()->with(successRes("Role added successfully."));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateRoleRequest $request, string $id)
    {
        $role = school()->roles()->findOrFail($id);
        $role->update($request->validated());
        return back()->with(successRes("Role updated successfully."));
    }

    public function addPermissions(rolePermissionRequest $request)
    {
        $data = $request->validated();
        $role =  Role::query()->findOrFail($data['role_id']);
        $role->syncPermissions($data['permissions'] ?? []);
        return back()->with(successRes("Role permissions updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->roles()->findOrFail($id)->delete();
        return back()->with(successRes("Role deleted successfully."));
    }

    public function bulkDestroy(Request $request)
    {
        info($request);
        $validated = $request->validate([
            'keys' => ['required', 'array'],
            'keys.*' => ['integer', Rule::exists('roles', 'id')->where('school_id', school()->id)],
        ]);
        info($request);
        school()->roles()
            ->whereIn('id', $validated['keys'])
            ->delete();

        return back()->with(successRes("Selected roles deleted successfully."));
    }
}
