<?php

namespace App\Livewire\System\Roles;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    public Role $role;
    public string $name;

    public function mount(): void
    {
        $this->name = $this->role->name;
    }
    public function update(): void
    {
        $this->validate([
            'name' => ['required', 'string', Rule::unique('roles', 'name')->where('school_id', school()->id)->ignore($this->role)],
        ]);
        $this->role->update([
            'name' => $this->name,
        ]);
        $this->dispatch('success', 'Role updated.');

    }
    public function render()
    {
        return view('livewire.system.roles.role-edit');
    }
}
