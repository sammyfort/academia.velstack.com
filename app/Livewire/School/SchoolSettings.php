<?php

namespace App\Livewire\School;

use App\Rules\FileSizeRule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SchoolSettings extends Component
{
    use WithFileUploads;

    public array $school = [];

    public array $grading = [
        'grade' => '',
        'min_score',
        'max_score',
        'remark',
    ];

    public array $alert = [
        'send_after_payment' => '',
        'send_upcoming_events' => '',
        'send_student_attendance' => '',
        'send_admission_alert' => '',
    ];

    public array $config = [
        'sender_id' => '',
        'api_key' => '',
    ];

    public function addItem(): void
    {
        pushToArray($this->grading, ['min_score' => 0, 'max_score' => 0, 'grade' => '', 'remark' => '']);
    }

    public function removeItem($index): void
    {
        removeFromArray($this->grading, $index);
    }

    public $cover;

    public $favicon;

    public function mount(): void
    {
        $this->school = school()->toArray();
        $this->grading = school()->gradeScores()->get()->toArray();
        $this->alert['send_after_payment'] = (bool) school()->communication->send_after_payment;
        $this->alert['send_student_attendance'] = (bool) school()->communication->send_student_attendance;
        $this->alert['send_admission_alert'] = (bool) school()->communication->send_admission_alert;
        $this->alert['send_upcoming_events'] = (bool) school()->communication->send_upcoming_events;
        $this->config['api_key'] = school()->communication->api_key;
        $this->config['sender_id'] = school()->communication->sender_id;
    }

    public function updateSchool(): void
    {
        $this->validate([
            'school.name' => ['required', 'string'],
            'school.email' => ['required', 'email', 'string'],
            'school.phone' => ['required', 'numeric', 'digits:10'],
            'school.town' => ['required', 'string'],
            'school.district' => ['required', 'string'],
            'school.postal_address' => ['required'],
            'school.region' => ['required', 'string'],
        ]);
         $school = school();
        $school->update($this->school);
        if ($this->cover) {
            $school
                ->addMedia($this->cover->getRealPath())
                ->usingFileName('cover-'.$school->id.'.'.$this->cover->getClientOriginalExtension())
                ->toMediaCollection('cover');
        }

        $this->dispatch('success', 'School settings updated.');
    }

    public function uploadImages(): void
    {
        $this->validate([
            'cover' => ['nullable', 'image', 'mimes:png,jpg,pdfs', 'max:1024', new FileSizeRule],
            'favicon' => ['nullable', 'image', 'max:1024', new FileSizeRule],
        ]);

        $school = school();
        

        if ($this->favicon) {
            $school
                ->addMedia($this->favicon->getRealPath())
                ->usingFileName('favicon-'.$school->id.'.'.$this->favicon->getClientOriginalExtension())
                ->toMediaCollection('favicon');

            $this->dispatch('success', 'Icon updated');
        }

    }

    public function updateGrading(): void
    {
        $this->validate([
            'grading.*.min_score' => ['required', 'numeric', 'min:0'],
            'grading.*.max_score' => ['required', 'numeric', 'min:0'],
            'grading.*.grade' => ['required', 'string'],
            'grading.*.remark' => ['required', 'string'],
        ]);

        // Get all existing grade IDs from DB
        $existingIds = school()->gradeScores()->pluck('id')->toArray();

        // Track IDs from the form
        $submittedIds = [];

        foreach ($this->grading as $grade) {
            if (isset($grade['id'])) {
                $submittedIds[] = $grade['id'];
                $existingItem = school()->gradeScores()->findOrFail($grade['id']);
                $existingItem->update($grade);
            } else {
                $newGrade = school()->gradeScores()->create($grade);
                $submittedIds[] = $newGrade->id;
            }
        }

        // Delete grades that were removed from UI
        $gradesToDelete = array_diff($existingIds, $submittedIds);
        if (! empty($gradesToDelete)) {
            school()->gradeScores()->whereIn('id', $gradesToDelete)->delete();
        }

        $this->dispatch('success', 'Grading updated successfully');
    }

    public function updatedAlert($value): void
    {
        school()->communication()->update($this->alert);

    }

    public function saveConfig(): void
    {
        $this->validate([
            'config.sender_id' => ['required', 'string'],
            'config.api_key' => ['required', 'string'],
        ]);

        school()->communication()->update($this->config);
        $this->dispatch('success', 'Notification config updated');
    }

    public function render()
    {
        return view('livewire.school.school-settings');
    }
}
