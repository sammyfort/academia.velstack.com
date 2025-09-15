<?php

namespace App\Livewire\Staff;

use App\Enum\Gender;
use App\Enum\MaritalStatus;
use App\Enum\Region;
use App\Enum\Religion;
use App\Enum\StaffExperience;
use App\Enum\StaffQualification;
use App\Enum\StaffType;
use App\Enum\Title;
use App\Jobs\SMSSenderJob;
use App\Models\Staff;
use App\Rules\FileSizeRule;
use App\Rules\ValidGhanaCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffCreate extends Component
{
    use WithFileUploads;

    public array $staff = [
        'title' => '',
        'licence_no' => '',
        'staff_id' => '',
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'email' => '',
        'phone' => '',
        'gender' => '',
        'dob' => '',
        'region' => '',
        'city' => '',
        'religion' => '',
        'qualification' => '',
        'experience' => '',
        'marital_status' => '',
        'designation' => '',
        'national_id' => '',
        'basic_salary' => '',

    ];

    public array $bank = [
      'name' => '',
      'branch' => '',
      'account_number' => '',
    ];

    public $profileImage;
    public $certificateImage;
    public $appointmentLetter;




    public function create(): void
    {
        $this->validate([
            'staff.title' => ['required', Rule::in(Title::cases())],
            'staff.licence_no' => ['nullable', Rule::unique('staff', 'licence_no')],
            'staff.staff_id' => ['nullable', Rule::unique('staff', 'staff_id')],
            'staff.first_name' => ['required'],
            'staff.last_name' => ['required'],
            'staff.middle_name' => ['nullable'],
            'staff.email' => ['required', 'email', Rule::unique('staff', 'email')],
            'staff.phone' => ['required', 'numeric', Rule::unique('staff', 'phone')],
            'staff.designation' => ['required', Rule::in(StaffType::cases())],
            'staff.national_id' => ['required', new ValidGhanaCard()],
            'staff.basic_salary' => ['required', 'numeric'],

            'staff.gender' => ['required', Rule::in(Gender::cases())],
            'staff.dob' => ['required', 'date'],
            'staff.region' => ['required', Rule::in(Region::cases())],
            'staff.city' => ['required'],

            'staff.religion' => ['required', Rule::in(Religion::cases())],
            'staff.marital_status' => ['required', Rule::in(MaritalStatus::cases())],
            'staff.experience' => ['required', Rule::in(StaffExperience::cases())],
            'staff.qualification' => ['required', Rule::in(StaffQualification::cases())],

            'profileImage' => ['nullable', 'image', new FileSizeRule()],
            'certificateImage' => ['nullable', 'file', 'mimes:pdf', new FileSizeRule(90)],
            'appointmentLetter' => ['nullable', 'file', 'mimes:pdf', new FileSizeRule(90)],

            'bank.name' => ['required'],
            'bank.branch' => ['required'],
            'bank.account_number' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            if (empty($this->staff['staff_id'])) $this->staff['staff_id'] = generateString('', 8, 'number');
            $this->staff['password'] = Hash::make($this->staff['phone']);
            $this->staff['licence_no'] = empty($this->staff['licence_no']) ? null : $this->staff['licence_no'];
            $staff = school()->staff()->create($this->staff);
            $staff->bank()->create($this->bank);
            $this->uploadFiles($staff);
            $this->sendLoginDetails($staff);
            DB::commit();
            $this->reset();
            $this->dispatch('success', "Staff created successfully");
        }catch (\Exception $exception){
            $this->dispatch('error', "Something went wrong {$exception->getMessage()}");
            DB::rollBack();
        }
    }

    public function sendLoginDetails(Staff $staff): void
    {
        if ($this->staff['designation'] == StaffType::NON_TEACHING_STAFF->value) return;
        $staff->school->broadCastSMS(
            $staff->phone,
            "Welcome to the " . config('app.name') . " family, $staff->fullname! Your school, {$staff->school->name}, is now onboard.
             Here are your login credentials: Email: $staff->email Password: $staff->phone. Please reset your password before logging in for security. Visit " . config('app.url') . " to get started."
        );
    }

    public function uploadFiles(Staff $staff): void
    {

        if (isset($this->profileImage)){
            $staff->profile_image = prepareForStorage($this->profileImage, "staff/profile/$staff->id");
        }

        if (isset($this->certificateImage)){
            $staff->certificate_image = prepareForStorage($this->certificateImage, "staff/certificate/$staff->id");
        }

        if (isset($this->appointmentLetter)){
            $staff->appointment_letter = prepareForStorage($this->appointmentLetter, "staff/appointment-letter/$staff->id");
        }

        $staff->save();

    }


    public function render()
    {
        return view('livewire.staff.staff-create', [
            'classes' => school()->classes()->get()
        ]);
    }
}
