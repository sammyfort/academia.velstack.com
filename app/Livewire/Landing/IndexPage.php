<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class IndexPage extends Component
{
    public function render()
    {
        return view('livewire.landing.index-page');
    }
}
