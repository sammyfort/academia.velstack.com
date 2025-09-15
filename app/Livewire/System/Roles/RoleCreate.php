<?php

namespace App\Livewire\System\Roles;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleCreate extends Component
{
    public string $name;

    public function create(): void
    {
        $this->validate([
            'name' => ['required', 'string', Rule::unique('roles', 'name')->where('school_id', school()->id)],
        ]);
        Role::create([
            'name' => $this->name,
            'school_id' => school()->id,
            'guard_name' => 'staff',
        ]);
        $this->reset('name');
        $this->dispatch('success', 'Role created.');
    }

    public function render()
    {
        return view('livewire.system.roles.role-create');
    }
}
