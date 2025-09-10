<?php

namespace App\Console\Commands;

use App\Enums\Permissions;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class PermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $exclude = ['Application', 'Transaction', 'User', 'Profile',
            'School', 'StaffClassroomSubjectPermission',
            'PaymentBill','Reply', 'Review', 'GradeScore',
            'LentBook', 'Communication', 'StudentSubject',
            'StudentTransportation', 'SubjectScoreType',
            'ClassSubject', 'AllowanceAndDeduction',
            'StaffAllowanceDeduction', 'scoreType', 'Subscription',
            'SettlementAccount', 'Preference', 'GradeCode','ParentStudent'

        ];
        $models = getAllModels($exclude);
        $actions = ['create','edit','delete', 'view'];
        foreach ($models as $model) {
            $modelName = class_basename($model);
            foreach ($actions as $action) {
                $permissionName = "$action.$modelName";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'staff']);
            }
        }

        foreach (Permissions::cases() as $case) {
            Permission::firstOrCreate(['name' => $case->value, 'guard_name' => 'staff']);
        }
    }
}
