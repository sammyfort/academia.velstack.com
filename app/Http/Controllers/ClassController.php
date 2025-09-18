<?php

namespace App\Http\Controllers;

use App\Enums\ClassGroup;
use App\Enums\ClassLevel;
use App\Http\Requests\Class\storeClassRequest;
use App\Http\Requests\Class\updateClassRequest;
use Google\Service\Classroom\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Class\AttendanceRequest;

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
        return Inertia::render('Class/ClassShow', [
            'classroom' => $class->loadMissing([
            'students.subjects', 
            'students.attendances.term', 
            'staff', 'subjects', 
            'scoreTypes', 'subjects.scoreTypes', 'subjects.students']),
            'semesters' => toOption($terms, 'name', 'id', false),
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
        //
    }

       public function recordAttendance(AttendanceRequest $request)
    {
        $data = $request->validated();
        $student = school()->students()->findOrFail($data['student_id']);
        $term = $student->school->semesters()->findOrFail($data['term_id']);
        recordAttendance($student, $data['date'], $term, $data['present']);
        return back()->with(successRes("Attendance recorded."));
    }
}
