<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Artisan;

new class extends Component {

    public $command_output = "";

    public function mount(): void
    {
    }

    public function pullFromRemote(){
        $cmd = 'cd /home/hellodar/public_html/portal.edu.gh && git pull origin main';
        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = proc_open($cmd, $descriptors, $pipes);

        if (is_resource($process)) {
            while ($line = fgets($pipes[1])) {
                $this->command_output .= $line . "\n";
                $this->dispatch('updateOutput', $this->command_output);
                usleep(100000);
            }
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        } else {
            $this->output = 'Error: Unable to execute command';
        }
    }
    public function migrate($fresh = false){
        if ($fresh) Artisan::call('migrate:fresh --seed');
        else Artisan::call('migrate');
        $this->command_output = Artisan::output();
    }
    public function optimizeClear(){
        Artisan::call('optimize:clear');
        $this->command_output = Artisan::output();
    }
}; ?>
@section('title', 'Devop')
<div x-data="page">
    <style>
        .console{
            width: 90%;
            height: 350px;
            overflow-y: scroll;
            background: black;
            color: white;
            font-size: 13px;
            text-align: left;
            word-break: keep-all;
            font-family:monospace;
        }
    </style>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-4 mb-md-0">DevOp</h4>
        </div>
    </div>
    <div>
        <div class="d-flex gap-3 mt-3">
            <button class="btn btn-sm btn-primary" wire:click="pullFromRemote">Pull From Remote</button>
            <button class="btn btn-sm btn-warning" wire:click="migrate">Run Migration</button>
            <button class="btn btn-sm btn-danger" wire:click="migrate(true)">Run Fresh Migration</button>
            <button class="btn btn-sm btn-success" wire:click="optimizeClear">Optimize Clear</button>
        </div>
        <div class="mt-2">
            <span wire:loading>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">

        </span>
          Running...
    </span>


        </div>
        <div class="d-flex w-100 justify-content-center align-items-center mt-4">
            <div class="console p-4">{{ $command_output }}</div>
        </div>
    </div>

    <script>
        $wire.on('updateOutput', function(output) {
            // Scroll to the bottom of the output div to show real-time updates
            // let outputDiv = document.querySelector('.alert-info pre');
            // outputDiv.scrollTop = outputDiv.scrollHeight;
        });
        document.addEventListener('alpine:init', () => {
            Alpine.data('page', () => ({}))
        })
    </script>
</div>
@section('script')

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
