<?php

namespace App\Livewire\Student;
use App\Enum\ParentType;
use App\Enum\Region;
use App\Enum\Religion;
use App\Models\_Parent;
use App\Models\School;
use App\Models\Student;
use App\Rules\FileSizeRule;
use App\Rules\ValidGhanaCard;
use App\Traits\AdmissionTrait;
use App\Traits\Toaster;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentEdit extends Component
{
    use Toaster, AdmissionTrait, WithFileUploads;
    public School $school;

    public Student $student;

    public array $edit = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'index_number' => '',
        'email' => '',
        'phone' => '',
        'dob' => '',
        'city' => '',
        'bio' => '',
        'parent_id',
        'class_id',
        'religion' => '',
        'region' => '',
        'gender' => '',
    ];

    public $image;
    public bool $newParent = true;

    protected function rules(): array
    {
        return [
            'image' => ['nullable', 'image', new FileSizeRule()],
            'edit.first_name' => ['required'],
            'edit.last_name' => ['required'],
            'edit.middle_name' => ['nullable'],
            'edit.index_number' => ['required', Rule::unique('students', 'index_number')->ignore($this->student)],
            'edit.email' => ['nullable', 'email'],
            'edit.phone' => ['nullable'],
            'edit.dob' => ['required', 'date'],
            'edit.gender' => ['required'],
            'edit.religion' => ['required', Rule::in(Religion::cases())],
            'edit.bio' => ['required'],
            'edit.city' => ['required'],
            'edit.region' => ['required'],
            'edit.parent_id' => [Rule::requiredIf($this->newParent == false), 'nullable', 'int'],
            'edit.class_id' => ['required'],

            'parents.*.name' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string'],
            'parents.*.type' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string', Rule::in(ParentType::cases())],
            'parents.*.phone' => [Rule::requiredIf($this->newParent == true), 'nullable', 'numeric',
                Rule::requiredIf($this->newParent === true),
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;

                    $index = explode('.', $attribute)[1];
                    $parentData = $this->parents[$index];
                    if (isset($parentData['id'])) {
                        $exists = _Parent::query()->where('phone', '=', $value)
                            ->where('id', '!=', $parentData['id'])
                            ->exists();
                    } else {
                        $exists = _Parent::query()->where('phone', '=', $value)
                            ->exists();
                    }

                    if ($exists) {
                        $fail('This phone number is already exist.');
                    }
                }
            ],
            'parents.*.email' => [Rule::requiredIf($this->newParent === true), 'nullable', 'email',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;

                    $index = explode('.', $attribute)[1];
                    $parentData = $this->parents[$index];
                    if (isset($parentData['id'])) {
                        $exists = _Parent::query()->where('email', '=', $value)
                            ->where('id', '!=', $parentData['id'])
                            ->exists();
                    } else {
                        $exists = _Parent::query()->where('email', '=',$value)
                            ->exists();
                    }

                    if ($exists) {
                        $fail('This email is already exist.');
                    }
                }
            ],

            'parents.*.address' => [Rule::requiredIf($this->newParent == true), 'nullable', 'string'],
            'parents.*.identity_number' => [Rule::requiredIf($this->newParent == true), 'nullable', new ValidGhanaCard()],
            'parents.*.occupation' => [Rule::requiredIf($this->newParent == true), 'string'],
        ];
    }

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

    public function removeParent($index): void
    {

        removeFromArray($this->parents, $index);
    }

    public function setParentType(bool $value): void
    {
        $this->newParent = $value;

    }

    public function mount($uuid): void
    {
        $this->student = school()->students()->where('uuid', $uuid)->firstOrFail();
        $this->edit = $this->student->toArray();
        $this->parents = $this->student->parents()->get()->map(function ($parent) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'type' => $parent->type,
                'email' => $parent->email,
                'phone' => $parent->phone,
                'address' => $parent->address,
                'identity_number' => $parent->identity_number,
                'occupation' => $parent->occupation,
                'school_id' => $parent->school_id
            ];
        })->toArray();
    }
    protected function getValidationAttributes(): array
    {
        return getValidationAttributesFor($this->rules());
    }

    /**
     * @throws Exception
     */
    public function update(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->student->update($this->edit);

            if (isset($this->image)) {
                $this->student->image = prepareForStorage($this->image, "student/{$this->student->id}");
                $this->student->save();
            }
            $this->updateParent();
            DB::commit();
            $this->dispatch('success', __('Student Updated successfully'));

        } catch (Exception $exception) {
            DB::rollBack();
           // throw $exception;
            $this->dispatch('error', __("Something went wrong"));
        }
    }

    public function updateParent(): void
    {
        if (!$this->newParent) {
            $parent = _Parent::query()->findOrFail($this->edit['parent_id']);
            if (!$this->student->parents()->where('parent_id', $parent->id)->exists()) {
                $this->student->parents()->attach($parent->id, [
                    'uuid' => Str::uuid(),
                    'school_id' => school()->id
                ]);
            }

        }else{
            $currentParentIds = collect($this->parents)
                ->filter(fn($parent) => isset($parent['id']))
                ->pluck('id')
                ->toArray();

            $this->student->parents()
                ->wherePivotNotIn('parent_id', $currentParentIds)
                ->detach();
            foreach ($this->parents as $parentData) {
                if (isset($parentData['id'])) {
                    $parent = school()->parents()->find($parentData['id']);
                    if ($parent){
                        $this->student->parents()
                            ->where('__parents.id', $parentData['id'])
                            ->update(collect($parentData)
                                ->except(['id', 'school_id', 'password'])
                               ->toArray()
                            );
                    }

                } else {
                    $parentData['password'] = Hash::make($parentData['phone']);
                    $newParent = school()->parents()->create($parentData);
                    $this->student->parents()->attach($newParent->id, [
                        'uuid' => Str::uuid(),
                        'school_id' => school()->id
                    ]);
                }
            }
        }

    }

    public function render(): View
    {
        return view('livewire.student.student-edit',[
            'classes' => school()->classes()->get(),
            'allSystemParents' => _Parent::query()->get(),
        ]);
    }
}
