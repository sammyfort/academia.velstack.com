<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Region;
use App\Enums\Religion;
use App\Enums\StudentStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
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


    public function dashboard()
    {

        return Inertia::render('Staff/StaffDashboard');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $date   = $request->input('date');
        $paginate   = $request->input('paginate', 10);
        $staffQuery =  school()->staff()
            ->when($search, fn($q) => $q->search($search))
            ->when(!empty($status), fn($q) => $q->whereIn('status', $status))
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->latest();
        $staff = $paginate === 'all'
            ? [
                'data' => $staffQuery->get(),
                'total' => $staffQuery->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $staffQuery->count(),
            ]
            : $staffQuery->paginate(intval($paginate));

        return Inertia::render('Staff/StaffIndex', [
            'staff' => $staff,
            'filters' => [
                'search' =>$search,
                'page'   => $request->input('page', 1),
                'date' => $date,
                'status' => $status,
                'paginate' => $paginate
            ],
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Staff/StaffCreate', array_merge($this->props, [

        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
