<?php

namespace App\Livewire\Student;


use App\Enum\Gender;
use App\Enum\ParentType;
use App\Enum\Region;
use App\Enum\Religion;
use App\Jobs\SMSSenderJob;
use App\Models\_Parent;
use App\Models\School;
use App\Models\Student;
use App\Rules\FileSizeRule;
use App\Rules\ValidGhanaCard;
use App\Services\ParentService;
use App\Traits\AdmissionTrait;
use App\Traits\CacheStore;
use App\Traits\Toaster;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class StudentAdmit extends Component
{
    use Toaster, AdmissionTrait, WithFileUploads, CacheStore;

    public School $school;

    public array $student = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'index_number' => '',
        'email' => '',
        'phone' => '',
        'dob' => '',
        'city' => '',
        'bio' => '',
        'admission_term',
        'religion' => '',
        'region' => '',
        'gender' => '',
        'parent_id',
        'class_id',
    ];

    public function selectParent($id): void
    {
        $this->student['parent_id'] = $id;
    }

    public array|Collection $allSystemParents = [];
    public bool $localParents = true;

    public bool $newParent = true;

    public $image;

    public array $parents = [];

    public function addParent(): void
    {
        pushToArray($this->parents, [
            'type' => '',
            'name' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'identity_number' => '',
            'occupation' => '',
            ]);
    }

    public function mount(): void
    {
        $this->allSystemParents = $this->getParents();
    }

    public function updatedLocalParents(bool $value): void
    {
        if ($value) $this->allSystemParents = $this->getParents();
        else $this->allSystemParents = _Parent::query()->get();

    }

    public function removeParent($index): void
    {
        removeFromArray($this->parents, $index);
    }


    public function setParentType(bool $value): void
    {
        $this->newParent = $value;

    }
    protected function rules(): array
    {
        return [
            'image' => ['nullable', 'image', new FileSizeRule()],
            'student.first_name' => ['required'],
            'student.last_name' => ['required'],
            'student.middle_name' => ['nullable'],
            'student.index_number' => ['nullable',  Rule::unique('students', 'index_number')],
            'student.email' => ['nullable', 'email'],
            'student.phone' => ['nullable', 'numeric'],
            'student.dob' => ['required', 'date'],
            'student.bio' => ['required', 'string'],
            'student.city' => ['required', 'string'],

            'student.gender' => ['required', Rule::in(Gender::cases())],
            'student.religion' => ['required', Rule::in(Religion::cases())],
            'student.region' => ['required', Rule::in(Region::cases())],
            'student.parent_id' => [Rule::requiredIf($this->newParent === false), 'nullable', 'int'],
            'student.class_id' => ['required', Rule::exists('classrooms', 'id')->where('school_id', school()->id)],

            'parents.*.name' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string'],
            'parents.*.type' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string', Rule::in(ParentType::cases())],
            'parents.*.phone' => [Rule::requiredIf($this->newParent == true), 'nullable', 'numeric', Rule::unique('__parents', 'phone')],
            'parents.*.email' => ['nullable', 'email',  Rule::unique('__parents', 'email')],
            'parents.*.address' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string'],
            'parents.*.identity_number' => ['nullable', new ValidGhanaCard()],
            'parents.*.occupation' => [Rule::requiredIf($this->newParent == true), 'string'],
        ];
    }

    protected function getValidationAttributes(): array
    {
        return getValidationAttributesFor($this->rules());
    }



    public function admit(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $parentIds = $this->newParent === true
                ? $this->createParent()
                : _Parent::query()->findOrFail($this->student['parent_id'])->id;

            $student = $this->admitStudent();
            $student->parents()->attach($parentIds, [
                'uuid' => Str::uuid(),
                'school_id'=> school()->id
            ]);

            $this->sendSMS($student);

            DB::commit();
            $this->resetErrorBag();
            $this->reset('student', 'image', 'parents');
            $this->dispatch('success', __('Admitted Student successfully'));

        } catch (ValidationException $exception) {
            DB::rollBack();
            info($exception);
          // throw $exception;
            $this->dispatch('error', __("{$exception->getMessage()}"));
        }
    }

    public function createParent(): array
    {
        $parentIds = [];

        foreach ($this->parents as $parent) {
            $parent['password'] = Hash::make($parent['phone']);
            $parent['email'] = empty($parent['email']) ? null : $parent['email'];
            $newParent = school()->parents()->create($parent);
            $parentIds[] = $newParent->id;
        }
        return $parentIds;
    }

    /**
     * @throws Exception
     */
    public function admitStudent()
    {

        $student = school()->students()->create([
            'first_name' => $this->student['first_name'],
            'middle_name' => $this->student['middle_name'] ?? null,
            'last_name' => $this->student['last_name'],
            'index_number' => empty($this->student['index_number']) ?  generateString('STU', 8, 'number') : $this->student['index_number'],
            'class_id' =>  $this->student['class_id'],
            'password' => Hash::make(123),
            'gender' =>  $this->student['gender'] ,
            'phone' => $this->student['phone'],
            'religion' =>  $this->student['religion'],
            'dob' => $this->student['dob'],
            'region' => $this->student['region'],
            'city' => $this->student['city'],
            'email' => $this->student['email'],
            'bio' => $this->student['bio'],
        ]);

        if (isset($this->image)){
            $student->image = prepareForStorage($this->image, "student/$student->id");
            $student->save();
        }


        return $student;

    }

    public function sendSMS(Student $student){
        if ($student->school->communication->send_admission_alert){
            foreach($student->parents as $parent){
                $student->school->broadCastSMS(
                    $parent->phone,
                    "Hello $parent->name, we are pleased to inform you that your child, $student->fullname, has been successfully admitted to {$student->school->name}. Your child's index number is $student->index_number. Thank you!"
                );

            }

        }

    }

    public function render()
    {
        return view('livewire.student.student-admit', [
            'classes' => $this->getClasses(),
            'calenders' => $this->getTerms(),
        ]);
    }
}
