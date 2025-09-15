<?php

namespace App\Livewire\Transportation;

use App\Enum\FeeType;
use App\Models\Student;
use App\Models\Transportation;
use App\Services\DataTable;
use Livewire\Component;

class TransportationAssignStudents extends Component
{
    protected $listeners = [
        'update-route' => 'updateStudentRoute',
    ];

    public array $transport = [
        'route_id' => ''
    ];
    public string $search = '';
    public int $paginate = 15;

    public ?int $route_id = null;


    public function updateRoute($student_id, $route_id): void
    {
        $student = school()->students()->findOrFail($student_id);

        if ($route_id) {
            // Fetch the new transportation route
            $transportation = school()->transportations()->findOrFail($route_id);

            // Update the student's transportation route
            $student->update(['transportation_id' => $transportation->id]);

            // Check for an existing bill
            $existingBill = $student->bills()
                ->where('fee_id', $transportation->fee->id)
                ->first();

            if ($existingBill) {
                // Update the amount if the bill exists
                $existingBill->update([
                    'amount' => $transportation->fee->amount,
                ]);
            } else {
                // Create a new bill if it doesn't exist
                $student->bills()->create([
                    'school_id' => $student->school_id,
                    'fee_id' => $transportation->fee->id,
                    'amount' => $transportation->fee->amount,
                    'term_id' => currentTerm()->id,
                ]);
            }

            $this->dispatch('success', 'Student transportation route updated successfully.');
        } else {
            // Handle case where no route is selected
            $transportation = $student->transportation;
            if ($transportation) {
                // Delete associated bills if transportation exists
                $bills = $student->bills()
                    ->where('fee_id', $transportation->fee->id)
                    ->get();

                foreach ($bills as $bill) {
                    $bill->delete();
                }

                // Remove the transportation assignment
                $student->update(['transportation_id' => null]);

                $this->dispatch('success', 'Student transportation and associated bill deleted successfully.');
            } else {
                // Handle case where no transportation exists
                $this->dispatch('error', 'No transportation assigned to the student.');
            }
        }
    }

    public function resetFilter(): void
    {
        $this->reset(['search', 'paginate']);
    }

    public function render()
    {
        return view('livewire.transportation.transportation-assign-students', [
            'students' => (new DataTable(new Student()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })->searchable($this->search)
                ->latest()
                ->paginate($this->paginate),
            'transportations' => school()->transportations()->get(),
        ]);
    }
}
