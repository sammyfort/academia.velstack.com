<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\Region;
use App\Enums\Religion;
use App\Enums\StaffExperience;
use App\Enums\StaffQualification;
use App\Enums\StaffStatus;
use App\Enums\StudentStatus;
use App\Enums\Title;
use App\Http\Requests\storeStaffRequest;
use App\Http\Requests\updateStaffRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function __construct(protected $props = [])
    {
        $this->props = [
            'titles' => toOption(Title::toArray()),
            'regions' => toOption(Region::toArray()),
            'religions' => toOption(Religion::toArray()),
            'gender' => toOption(Gender::toArray()),
            'semesters' => toOption(school()->semesters()->get(), 'name', 'id'),
            'staffStatus' => toOption(StaffStatus::toArray()),
            'maritalStatus' => toOption(MaritalStatus::toArray()),
            'staffQualifications' => toOption(StaffQualification::toArray()),
            'staffExperiences' => toOption(StaffExperience::toArray()),
            'roles' => toOption(school()->roles()->get(), 'name', 'id')
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
        $date = $request->input('date');
        $paginate = $request->input('paginate', 10);
        $staffQuery = school()->staff()
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
            'staffStatus' => $this->props['staffStatus'],
            'filters' => [
                'search' => $search,
                'page' => $request->input('page', 1),
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
    public function store(storeStaffRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            if (empty($data['staff_id'])) {
                $data['staff_id'] = generateString('STAFF', 8, 'number');
            }
            $data['password'] = Hash::make($data['phone']);

            $staff = school()->staff()->create(Arr::except($data, ['image', 'roles']));
            $staff->syncRoles($data['roles'] ?? []);
            $staff->handleUploads($request, [
                'image' => 'image'
            ]);

            DB::commit();
            return back()->with(successRes("Staff added successfully."));

        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with(errorRes("{$exception->getMessage()}"));
            throw $exception;

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = school()->staff()->findOrFail($id);
        return Inertia::render('Staff/StaffEdit', array_merge($this->props, [
            'staff' => $staff
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateStaffRequest $request, string $id)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $staff = school()->staff()->findOrFail($id);
            $staff ->update(Arr::except($data, ['image', 'roles']));
            $staff->syncRoles($data['roles'] ?? []);
            $staff->handleUploads($request, [
                'image' => 'image'
            ]);

            DB::commit();
            return back()->with(successRes("Staff added successfully."));

        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with(errorRes("{$exception->getMessage()}"));
            throw $exception;

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->staff()->findOrFail($id)->delete();
        return back()->with(successRes("Staff deleted successfully."));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'keys' => ['required', 'array'],
            'keys.*' => ['integer', 'exists:staff,id'],
        ]);
        school()->staff()
            ->whereIn('id', $validated['keys'])
            ->delete();

        return back()->with(successRes("Selected staff deleted successfully."));
    }
}
