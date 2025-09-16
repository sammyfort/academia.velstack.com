<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Region;
use App\Enums\Religion;
use App\Enums\StudentStatus;
use Illuminate\Http\Request;
use App\Enums\Subjects;
use App\Http\Requests\Student\storeStudentRequest;
use Exception;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
     public function __construct(protected $props = [])
    {
        $this->props = [
            'regions' => toOption(Region::toArray()),
            'religions' => toOption(Religion::toArray()),
            'gender' => toOption(Gender::toArray()),
            'classes' => toOption(school()->classes()->get(), 'name', 'id'),
            'semesters' => toOption(school()->semesters()->get(), 'name', 'id'),
            'parents' => toOption(school()->parents()->get(), 'name', 'id'),
            'studentStatus' => toOption(StudentStatus::toArray())
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $classroom = $request->input('classroom', []);
        $date   = $request->input('date');
        $paginate   = $request->input('paginate', 10);
        $studentsQuery =  school()->students()
            ->with('classroom:id,name')
            ->when($search, fn($q) => $q->search($search))
            ->when(!empty($status), fn($q) => $q->whereIn('status', $status))
            ->when(!empty($classroom), fn($q) => $q->whereIn('class_id', $classroom))
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->latest();
        $students = $paginate === 'all'
            ? [
                'data' => $studentsQuery->get(),
                'total' => $studentsQuery->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $studentsQuery->count(),
            ]
            : $studentsQuery->paginate(intval($paginate));

        return Inertia::render('Students/StudentIndex', [
            'students' => $students,
            'classes' => $this->props['classes'],
            'studentStatus' => $this->props['studentStatus'],
            'filters' => [
                'search' =>$search,
                'page'   => $request->input('page', 1),
                'date' => $date,
                'status' => $status,
                'classroom' => $classroom,
                'paginate' => $paginate
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Students/StudentCreate', array_merge($this->props, [

        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeStudentRequest $request)
    {
        $data = $request->validated();

         DB::beginTransaction();
        try {
            if(empty($data['index_number'])){
                $data['index_number'] = generateString('STU', 8, 'number');
            }

            $data['password'] =  Hash::make($data['index_number']);


            $student = school()->students()->create(Arr::except($data, ['image', 'parents']));
            $student->parents()->attach($data['parents'], [
                'uuid' => Str::uuid(),
                'school_id'=> school()->id
            ]);
            $student->handleUploads($request, [
                'image' => 'image'
            ]);

            DB::commit();
         return back()->with(successRes("Student added successfully."));

        } catch (Exception $exception) {
            DB::rollBack();

           throw $exception;

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Students/StudentShow', [

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Students/StudentEdit', [

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->students()->findOrFail($id)->delete();
        return back()->with(successRes("Student deleted successfully."));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'keys' => ['required', 'array'],
            'keys.*' => ['integer', 'exists:students,id'],
        ]);
        school()->students()
            ->whereIn('id', $validated['keys'])
            ->delete();

        return back()->with(successRes("Selected students deleted successfully."));
    }
}
