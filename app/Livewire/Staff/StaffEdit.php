<?php

namespace App\Livewire\Staff;
use App\Enum\Gender;
use App\Enum\Region;
use App\Enum\Religion;
use App\Enum\StaffExperience;
use App\Enum\StaffQualification;
use App\Enum\StaffType;
use App\Enum\Title;
use App\Models\Staff;
use App\Rules\FileSizeRule;
use App\Rules\ValidGhanaCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

use Livewire\Component;
use Livewire\WithFileUploads;


class StaffEdit extends Component
{
    use WithFileUploads;

    public Staff $staffWorker;

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


    public function mount($uuid):void
    {
        $this->staffWorker = school()->staff()->where('uuid', $uuid)->firstOrFail();
        $this->staff = $this->staffWorker->toArray();
        $this->staffWorker->load('bank');
        $this->bank = $this->staffWorker->bank ? $this->staffWorker->bank->toArray() : [];
    }

    public function update(): void
    {
        $this->validate([
            'staff.title' => ['required', Rule::in(Title::cases())],
            'staff.licence_no' => ['nullable'],
            'staff.staff_id' => ['required'],
            'staff.first_name' => ['required'],
            'staff.last_name' => ['required'],
            'staff.middle_name' => ['nullable'],
            'staff.email' => ['required', 'email', Rule::unique('staff', 'email')->ignore($this->staffWorker)],
            'staff.phone' => ['required', 'numeric', Rule::unique('staff', 'phone')->ignore($this->staffWorker)],
            'staff.designation' => ['required', Rule::in(StaffType::cases())],
            'staff.national_id' => ['required', new ValidGhanaCard()],
            'staff.basic_salary' => ['required', 'numeric'],

            'staff.gender' => ['required', Rule::in(Gender::cases())],
            'staff.dob' => ['required', 'date'],
            'staff.region' => ['required', Rule::in(Region::cases())],
            'staff.city' => ['required'],
            'staff.religion' => ['required', Rule::in(Religion::cases())],
            'staff.marital_status' => ['required'],
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
            $this->staffWorker->update($this->staff);
            $this->uploadFiles($this->staffWorker);
            $this->staffWorker->bank()->updateOrCreate(
                ['bankable_id' => $this->staffWorker->id, 'bankable_type' => get_class($this->staffWorker)],
                $this->bank
            );


            DB::commit();
            $this->dispatch('success', "Staff updated");
        }catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('error', "Something went wrong");
        }

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

    public function render(): View
    {
        return view('livewire.staff.staff-edit');
    }
}
