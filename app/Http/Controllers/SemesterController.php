<?php

namespace App\Http\Controllers;

use App\Enums\AcademicTerm;
use App\Enums\TermStatus;
use App\Http\Requests\Semester\storeSemesterRequest;
use App\Http\Requests\Semester\updateSemesterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date   = $request->input('date');
        $semesters = school()->semesters()
            ->when($search, function ($q, $search) {
                $q->search($search);
            })
            ->when($date, function ($q) use ($date) {
                $q->whereDate('created_at', $date)
                    ->orWhere('start_date', $date)
                    ->orWhere('end_date', $date)
                    ->orWhere('next_term_begins', $date);
            })
            ->latest()->paginate();

        return Inertia::render('Semester/SemesterIndex', [
            'semesters' => $semesters,
            'available_semesters' => toOption(AcademicTerm::toArray(), format: false),
            'filters' => [
                'search' =>$search,
                'page'   => $request->input('page', 1),
                'date' => $date
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeSemesterRequest $request)
    {

        DB::beginTransaction();
        try {
            $semester = school()->semesters()->create($request->validated());

            if ($semester->status == TermStatus::ACTIVE->value) {
                $exists = school()->semesters()->where('status', TermStatus::ACTIVE->value)->get()->except($semester->id);
                foreach ($exists as $semester) {
                    $semester->update(['status' => TermStatus::ENDED->value]);
                }
            }
            DB::commit();
            return back()->with(successRes("Semester created successfully."));
        } catch (\Exception $exception) {
            DB::rollBack();
            // throw($exception);
            return back()->with(errorRes($exception->getMessage()));
        }
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
    public function update(updateSemesterRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $semester = school()->semesters()->findOrFail($id);
            $semester->update($request->validated());

            if ($semester->status == TermStatus::ACTIVE->value) {
                $exists = school()->semesters()->where('status', TermStatus::ACTIVE->value)->get()->except($semester->id);
                foreach ($exists as $semester) {
                    $semester->update(['status' => TermStatus::ENDED->value]);
                }
            }
            DB::commit();
            return back()->with(successRes("Semester updated successfully."));
        } catch (\Exception $exception) {
            DB::rollBack();
            // throw($exception);
            return back()->with(errorRes($exception->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->semesters()->findOrFail($id)->delete();
        return back()->with(successRes("Semester deleted successfully."));
    }
}
