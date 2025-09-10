<?php

namespace App\Console\Commands;

use App\Models\Staff;
use Illuminate\Console\Command;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Staff::all() as $staff) {
            $directPermissions = $staff->getDirectPermissions();

            // Remove only the direct permissions
            foreach ($directPermissions as $permission) {
                $staff->revokePermissionTo($permission);
            }
        }
    }
}
