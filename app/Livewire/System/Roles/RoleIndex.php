<?php

namespace App\Livewire\System\Roles;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $listeners = [
        'open-role' => 'loadRole',
    ];
    public string $search = "";
    public string $permissionSearch = '';
    public int $paginate = 10;

    public string $direction = "desc";

    public ?Role $roleModel = null;

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
        $this->direction = 'desc';
    }

    public function resetRole()
    {
        $this->roleModel = null;
        //dd('reset');

    }

    public function loadRole($role_id): void
    {
        $this->roleModel = Role::query()->findOrFail($role_id);
    }

    public function assignPermission($role_id, $permission_name): void
    {
        $role = Role::query()->findOrFail($role_id);
        $role->givePermissionTo($permission_name);

    }

    public function revokePermission($role_id, $permission_id): void
    {
        $role = Role::query()->findOrFail($role_id);
        $role->revokePermissionTo($permission_id);
    }

    public function assignAllPermissions(): void
    {

        $this->roleModel->givePermissionTo(Permission::all());

        $this->dispatch('refreshPermissions');
    }
    public function revokeAllPermissions(): void
    {
        $this->roleModel->revokePermissionTo(Permission::all());
        $this->dispatch('refreshPermissions');
    }



    #[On('refreshPermissions')]
    public function render()
    {
        return view('livewire.system.roles.role-index', [
            'roles' => \Spatie\Permission\Models\Role::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->where('school_id', school()->id)
                ->paginate(),
            'permissions' =>  \Spatie\Permission\Models\Permission::query()
                ->when($this->permissionSearch, function ($query) {
                    $query->where('name', 'like', '%' . $this->permissionSearch . '%');
                })->get()
        ]);
    }
}
