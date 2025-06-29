<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    public function index(): Response
    {
        $businesses = Business::all();
        return Inertia::render('Businesses/Businesses', [
            'businesses' => $businesses,
        ]);
    }

    public function myBusinesses(): Response
    {
        $businesses = request()->user()->businesses()->latest()->get();
        return Inertia::render('Businesses/MyBusinesses', [
            'businesses' => $businesses,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {

        $data = $request->validate( $data = $request->validate($this->rules()));
        $request->user()->businesses()->create($data);
        return back()->with(successRes("Business created successfully."));
    }
    public function show(Business $business): Response
    {
        Gate::authorize('view', [$business, request()->user()]);
        return inertia('Businesses/MyBusinessShow', [
            'business' => $business,
        ]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        Gate::authorize('update', [$business, request()->user()]);
        $data = $request->validate($this->rules());
        $business->update($data);
        return back()->with(successRes("Business updated successfully."));
    }


    public function delete(Business $business): RedirectResponse
    {
        Gate::authorize('delete', [$business, request()->user()]);

        $business->delete();
        return redirect()->route('my-businesses.index')
            ->with(successRes("Business deleted successfully."));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'digits:10'],
            'description' => ['required'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'x' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
        ];
    }


}
