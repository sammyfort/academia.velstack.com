<?php

namespace App\Livewire\OpenPortal\Admin;

use App\Enum\ApplicationStatus;
use App\Enum\Gender;
use App\Enum\MaritalStatus;
use App\Enum\Region;
use App\Enum\Religion;
use App\Enum\RoleEnum;
use App\Enum\StaffExperience;
use App\Enum\StaffQualification;
use App\Enum\Title;
use App\Jobs\SendEmailNotification;
use App\Jobs\SMSSenderJob;
use App\Models\Application;

use App\Models\School;
use App\Models\Staff;
use App\Traits\SMSSender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApplicationEdit extends Component
{
    use SMSSender;

    public Application $application;
    public array $school = [
        'school_name' => '',
        'region' => '',
        'physical_address' => '',
        'digital_address' => '',
        'postal_address' => '',
        'tel_number' => '',
        'email_address' => '',
        'date_established' => '',
        'est_revenue' => '',
        'est_students' => '',
        'est_staff' => '',
        'applicant' => '',
        'applicant_position' => '',
        'applicant_phone' => '',
        'applicant_email' => '',

    ];

    public function mount($uuid): void
    {
        $this->application = Application::query()->where('uuid', $uuid)->firstOrFail();
        $this->school = $this->application->toArray();
    }

    public function update(): void
    {

        $this->validate([
            'school.school_name' => ['required'],
            'school.region' => ['required'],
            'school.physical_address' => ['required'],
            'school.digital_address' => ['required'],
            'school.postal_address' => ['required'],
            'school.tel_number' => ['required'],
            'school.email_address' => ['required'],
            'school.date_established' => ['required'],
            'school.est_revenue' => ['required'],
            'school.est_students' => ['required'],
            'school.est_staff' => ['required'],
            'school.applicant' => ['required'],
            'school.applicant_position' => ['required'],
            'school.applicant_phone' => ['required'],
            'school.applicant_email' => ['required'],
        ]);
        DB::beginTransaction();

        try {
            $this->application->update($this->school);
            switch ($this->application->status) {
                case ApplicationStatus::APPROVED->value:
                    $staff = $this->createSchool($this->application);
                    $this->sendConfirmation($staff);
                    $this->application->delete();
                    DB::commit();
                    $this->dispatch('success', 'Application updated.');
                    $this->redirectRoute('admin.applications');
                    break;
                    default:
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;

        }

    }

    public function sendConfirmation(Staff $staff): void
    {
        dispatch(new SendEmailNotification(
            $staff,
            "Application Confirmation",
            'application.accepted',
            ['staff' => $staff]
        ))->afterCommit();

        $this->send($staff->phone, "Your application to use OpenPortal has been approved. Kindly check your email for confirmation. ");
    }

    public function createSchool(Application $application)
    {
        $school = School::create([
            'name' => $application->school_name,
            'email' => $application->email_address,
            'phone' => $application->tel_number,
            'phone2' => $application->applicant_phone,
            'region' => $application->region,
            'district' => $application->district,
            'town' => $application->district,
            'postal_address' => $application->postal_address,
            'gps' => $application->digital_address,
            'favicon' => null,
            'cover_image' => null
        ]);

        $staff = $school->staff()->create([
            'title' => Title::DR->value,
            'basic_salary' => 2000,
            'designation' => 'Staff',
            'national_id'=> 'GHA-12345678809',
            'gender' => Gender::OTHER->value,
            'first_name' => $application->applicant,
            'last_name' => $application->applicant,
            'email' => $application->applicant_email,
            'phone' => $application->applicant_phone,
            'staff_id' => generateString('STF'),
            'password' => Hash::make($application->applicant_phone),
            'dob' => now(),
            'religion' => Religion::CHRISTIAN->value,
            'region' => Region::BONO,
            'city' => 'Accra',
            'marital_status' => MaritalStatus::SINGLE->value,
            'qualification' => StaffQualification::HND->value,
            'experience' => StaffExperience::EXPERIENCED->value,

        ]);

        $role = Role::create([
            'name' => RoleEnum::SUPER_ADMINISTRATOR->value,
            'school_id' => $staff->school_id,
            'guard_name' => 'staff',
        ]);

        $staff->assignRole($role->name);

        return $staff;
    }

    public function render()
    {
        return view('livewire.open-portal.admin.application-edit');
    }
}
