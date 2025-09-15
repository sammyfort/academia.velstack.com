<?php

namespace App\Livewire\School;

use Livewire\Component;

class SchoolPreferences extends Component
{
    public array $preference = [
        'show_class_average' => '',
        'show_overall_position' => '',
        'show_overall_percentage' => '',
        'show_student_image_on_report' => '',
        'show_school_image_on_report' => '',


        'show_staff_attendance_on_payslip' => '',
        'open_for_transfer' => '',
    ];

    public function mount(): void
    {
        $this->preference['show_student_image_on_report'] = (bool) school()->preference->show_student_image_on_report;
        $this->preference['show_school_image_on_report'] = (bool) school()->preference->show_school_image_on_report;

        $this->preference['show_class_average'] = (bool) school()->preference->show_class_average;
        $this->preference['show_overall_position'] = (bool) school()->preference->show_overall_position;
        $this->preference['show_overall_percentage'] = (bool) school()->preference->show_overall_percentage;
        $this->preference['show_staff_attendance_on_payslip'] = (bool) school()->preference->show_staff_attendance_on_payslip;
        $this->preference['open_for_transfer'] = (bool) school()->preference->open_for_transfer;
    }

    public function updatedPreference($value): void
    {
        school()->preference()->update($this->preference);

    }
    public function render()
    {
        return view('livewire.school.school-preferences');
    }
}
