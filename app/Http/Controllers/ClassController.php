<?php

namespace App\Http\Controllers;

use App\Enums\ClassGroup;
use App\Enums\ClassLevel;
use App\Enums\ClassRole;
use App\Http\Requests\Class\storeClassRequest;
use App\Http\Requests\Class\SubjectToClassRequest;
use App\Http\Requests\Class\updateClassRequest;
use App\Models\StaffClassroomSubjectPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Requests\Class\AttendanceRequest;
use App\Http\Requests\Class\StaffClassRequest;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date = $request->input('date');
        $classes = school()->classes()
            ->withCount(['students'])
            ->when($search, function ($q, $search) {
                $q->search($search);
            })
            ->when($date, function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Class/ClassIndex', [
            'classes' => $classes,
            'filters' => [
                'search' => $search,
                'page' => $request->input('page', 1),
                'date' => $date
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'levels' => toOption(ClassLevel::toArray()),
            'groups' => toOption(ClassGroup::toArray()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeClassRequest $request)
    {
        school()->classes()->create($request->validated());
        return back()->with(successRes("Class created successfully."));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $terms = school()->semesters()->orderBy('created_at', 'desc')->get();
        $class = school()->classes()->where('slug', $slug)->firstOrFail();

        $class->loadMissing([
            'students.subjects',
            'students.attendances.term',
            'scoreTypes',
            'subjects.scoreTypes',
            'subjects.students',
            'subjects.staff' => function ($q) use ($class) {
                $q->wherePivot('classroom_id', $class->id)
                    ->whereNotNull('subject_id');
            },

            'staff' => function ($query) use ($class) {
                $query->with(['subjects' => function ($q) use ($class) {
                    $q->wherePivot('classroom_id', $class->id);
                }])
                    ->wherePivot('classroom_id', $class->id);
            },
        ]);

        // 🔑 Deduplicate staff collection
        $class->setRelation(
            'staff',
            $class->staff->unique('id')->values()
        );

        return Inertia::render('Class/ClassShow', [
            'classroom'  => $class,
            'semesters'  => toOption($terms, 'name', 'id', false),
            'staffRoles' => toOption(ClassRole::toArray()),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateClassRequest $request, string $id)
    {
        $class = school()->classes()->findOrFail($id);
        $class->update($request->validated());
        return back()->with(successRes("Class updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->classes()->findOrFail($id)->delete();
        return back()->with(successRes("Class deleted successfully."));
    }

    public function recordAttendance(AttendanceRequest $request)
    {
        $data = $request->validated();
        $student = school()->students()->findOrFail($data['student_id']);
        $term = $student->school->semesters()->findOrFail($data['term_id']);
        recordAttendance($student, $data['date'], $term, $data['present']);
    }

    public function addSubjectToClass(SubjectToClassRequest $request)
    {
        $data = $request->validated();
        $classroom = school()->classes()->where('id', $data['class_id'])->firstOrFail();
        $subjects = school()->subjects()->whereIn('id', $data['subjects'] ?? [])->get();
        DB::beginTransaction();

        try {
            $classroom->subjects()->sync(
                $subjects->mapWithKeys(fn($subject) => [
                    $subject->id => [
                        'uuid' => Str::uuid(),
                        'school_id' => school()->id,
                    ]
                ])->toArray()
            );

            DB::commit();
            return back()->with(successRes("Subject attached to class"));
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
            return back()->with(errorRes($exception->getMessage()));
        }
    }


    public function assignStaff(StaffClassRequest $request)
    {
        $data = $request->validated();

        $class = school()->classes()->findOrFail($data['class_id']);
        $staff = school()->staff()->findOrFail($data['staff_id']);

        DB::beginTransaction();
        try {
            assignClassSubjectPermission(
                $staff,
                $class,
                $data['role'],
                $data['subjects'] ?? []
            );

            DB::commit();
            return back()->with(successRes("Staff assigned to class successfully."));
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw $e; // or return a response
        }
    }
}
