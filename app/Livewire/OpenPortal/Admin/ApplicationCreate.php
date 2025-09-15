<?php

namespace App\Livewire\OpenPortal\Admin;

use App\Enum\ApplicationStatus;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApplicationCreate extends Component
{
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
    public function create(): void
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

            $this->school['status'] = ApplicationStatus::PENDING->value;
            Application::create($this->school);
            DB::commit();
            $this->dispatch('success', 'Application submitted for review.');
            $this->reset('school');


        } catch (\Exception $exception) {
            $this->dispatch('error', $exception->getMessage());
            DB::rollBack();
        }

    }

    public function render()
    {
        return view('livewire.open-portal.admin.application-create');
    }
}
