<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parent\storeParentRequest;
use App\Http\Requests\Parent\updateParentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date   = $request->input('date');
        $parents = school()->parents()
            ->when($search, function ($q, $search) {
                $q->search($search);
            })
            ->when($date, function ($q, $date) {
                $q->whereDate('created_at', $date);
            })
            ->latest()
            ->paginate();

        return Inertia::render('Parents/ParentIndex', [
            'parents' => $parents,
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
    public function store(storeParentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['phone']);
        school()->parents()->create($data);
        return back()->with(successRes("Parent created successfully."));
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
    public function update(updateParentRequest $request, string $id)
    {
        $data = $request->validated();
        $parent = school()->parents()->findOrFail($id);
        $parent->update($data);
        return back()->with(successRes("Parent updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        school()->parents()->findOrFail($id)->delete();
        return back()->with(successRes("Parent deleted successfully."));
    }
}
