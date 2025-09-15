<?php

namespace App\Livewire\System\Roles;

use App\Enum\RoleEnum;
use App\Models\Staff;
use App\Models\User;
use App\Services\DataTable;
use Exception;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Permission extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = [
        'open-staff' => 'loadStaff',
    ];
    public string $search = "";
    public string $roleSearch = '';
    public int $paginate = 10;

    public string $direction = "desc";

    public ?Staff $staffModel = null;

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
        $this->direction = 'desc';
    }

    public function resetStaff(): void
    {
        $this->staffModel = null;
    }

    public function loadStaff($staff_id): void
    {
        $this->staffModel = school()->staff()->findOrFail($staff_id);
    }

    public function assignRole($staff_id, $role_name): void
    {
        $user = school()->staff()->findOrFail($staff_id);
        $user->assignRole($role_name);

    }

    public function revokeRole($user_id, $role_id): void
    {
        $user = school()->staff()->findOrFail($user_id);
        $role = \Spatie\Permission\Models\Role::findOrFail($role_id);
        $role_name = $role->name;

        $protectedRoles = [RoleEnum::SUPER_ADMINISTRATOR->value];
        if (in_array($role_name, $protectedRoles)) {
            $count = $role->users()
                ->where('staff.school_id', school()->id)
                ->count();

            if ($count <= 1) {
                $this->dispatch('error', "Cannot remove the last user with the $role_name role in this school.");
                return;
            }
        }
        $user->removeRole($role_name);
    }



    public function assignAllRoles(): void
    {

        $this->staffModel->assignRole(\Spatie\Permission\Models\Role::all());

    }

    public function revokeAllRoles(): void
    {
        $protectedRoles = [RoleEnum::SUPER_ADMINISTRATOR->value,];

        foreach ($protectedRoles as $roleName) {
            if ($this->staffModel->hasRole($roleName)) {
                $count = \Spatie\Permission\Models\Role::findByName($roleName)
                    ->users()
                    ->where('staff.school_id', school()->id)
                    ->count();

                if ($count <= 1) {
                    $this->dispatch('error', "Cannot remove the last user with the $roleName role.");
                    return;
                }
            }
        }

        $this->staffModel->syncRoles([]);
    }

    public
    function render()
    {
        return view('livewire.system.roles.permission', [
            'users' => (new DataTable(new Staff()))
                ->query(function ($query) {

                })->searchable($this->search)
                ->with(['permissions'])
                ->where('school_id', school()->id)
                ->latest()
                ->paginate($this->paginate),

            'roles' => \Spatie\Permission\Models\Role::query()
                ->where('school_id', school()->id)
                ->when($this->roleSearch, function ($query) {
                    $query->where('name', 'like', '%' . $this->roleSearch . '%');
                })->get()
        ]);
    }
}
