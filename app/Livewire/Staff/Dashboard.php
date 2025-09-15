<?php

namespace App\Livewire\Staff;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{


    public function render(): View
    {
        return view('livewire.staff.dashboard', [
            'school' => school()->firstOrFail()
        ]);
    }
}
