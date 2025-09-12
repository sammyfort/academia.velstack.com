<?php

namespace App\Http\Controllers;

use App\Enums\Subjects;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\updateSubjectRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{


    public function __construct(protected $props = [])
    {
        $this->props = [
            'available_subjects' => toOption(Subjects::toArray()),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date   = $request->input('date');
        $subjects = school()->subjects()
            ->withCount(['classes', 'students'])
            ->when($search, function ($q, $search) {
                $q->search($search);

            })
            ->when($date, function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->paginate(10)
            ->withQueryString();


        return Inertia::render('Subject/SubjectIndex', array_merge($this->props, [
            'subjects' => $subjects,
            'filters' => [
                'search' =>$search,
                'page'   => $request->input('page', 1),
                'date' => $date
            ],
        ]));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $subjects = school()->subjects()
//            ->withCount(['classes', 'students'])
//            ->where(function ($q) use ($request) {
//                $q->search($request->input('search'))
//                    ->orWhereHas('classes', function ($q) use ($request) {
//                        $q->search($request->input('search'));
//                    })
//                    ->orWhereHas('students', function ($q) use ($request) {
//                        $q->search($request->input('search'));
//                    });
//            })
//            ->get();

    }

    /**Create', $this->props);
     * }
     *
     * /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();
        if (empty($data['code'])) $data['code'] = generateString('SUB', 4, 'number');
        school()->subjects()->create($data);

        return back()->with(successRes("Subject created successfully."));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(updateSubjectRequest $request, string $id)
    {
        school()->subjects()->findOrFail($id)->update($request->validated());
        return back()->with(successRes("Subject updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->subjects()->findOrFail($id)->delete();
        return back()->with(successRes("Subject deleted successfully."));
    }
}
