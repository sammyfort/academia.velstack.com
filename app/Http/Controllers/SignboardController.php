<?php

namespace App\Http\Controllers;


use App\Http\Requests\Signboard\StoreSignboardRequest;
use App\Http\Requests\Signboard\UpdateSignboardRequest;
use App\Models\Country;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Models\ServiceCategory;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use App\Services\HelperService;
use App\Services\RatingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;


class SignboardController extends Controller
{

    public function __construct(protected $props = [])
    {
        $this->props = [
            'categories' => toLabelValue(SignboardCategory::query()->select('id', 'name')->get(), 'name', 'id'),
            'regions' => toLabelValue(Region::query()->select('id', 'name')->get(), 'name', 'id'),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),
        ];
    }

    public function mySignboards(): Response
    {
        $user = auth()->user();
        return Inertia::render('Signboards/MySignboards', [
            'signboards' => $user->signboards()->with(['region', 'service'])->latest()->paginate(10),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Signboards/SignboardCreate', array_merge($this->props, [
            'service' => $request->service,
            'services' => toLabelValue($request->user()->services()->select('id', 'title')->get(), 'title', 'id'),
        ]));
    }

    public function store(StoreSignboardRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = $request->user()->services()->findOrFail($data['service_id']);
        $signboard = null;
       // dd($data);
        DB::transaction(function () use ($service, $data, $request, &$signboard) {
            $signboard = $service->signboards()->create(
                Arr::except($data, ['featured', 'gallery', 'categories'])
            );
            $categoryIds = collect($data['categories'] ?? [])
                ->map(function ($category) {
                    if (is_numeric($category)) {
                        return (int) $category;
                    }
                    return SignboardCategory::firstOrCreate(['name' => trim($category)])->id;
                })
                ->all();

            $signboard->categories()->sync($categoryIds);
            $signboard->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);

        });

        if ($signboard) {
            return to_route('my-signboards.show', $signboard->slug);
        }

        return back()->with(errorRes("An error occurred, please try again later."));
    }


    public function showMySignboard(Signboard $signboard): Response
    {
        Gate::authorize('view', $signboard);
        $promotionPlans = PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);
        $signboard->loadMissing(['reviews.ratings','service.user', 'region', 'reviews', 'categories', 'promotions.plan']);

        // check if it has payment
        $paymentStatus = Promotion::routeCallback();

        $distributions = RatingService::getDistributions($signboard);

        return Inertia::render('Signboards/MySignboard', [
            'signboard' => $signboard->toArrayWithMedia(),
            'payment_status' => $paymentStatus,
            'plans' => $promotionPlans,
            'ratings' => $signboard->averageRatings(),
            'distributions' => $distributions,
        ]);
    }

    public function edit(Signboard $signboard): Response
    {
        Gate::authorize('update', $signboard);

        return Inertia::render('Signboards/SignboardEdit',array_merge($this->props, [
            'signboard' => $signboard->load(['service', 'region', 'categories'])->toArrayWithMedia(),
            'services' => toLabelValue(\request()->user()->services()->select('id', 'title')->get(), 'title', 'id'),

        ]));
    }


    public function update(UpdateSignboardRequest $request, Signboard $signboard): RedirectResponse
    {
        Gate::authorize('update', $signboard);

        $data = $request->validated();

        //dd($data);
        DB::transaction(function () use ($signboard, $data, $request) {
            $signboard->update(Arr::except($data, ['featured', 'gallery', 'removed_gallery_urls', 'categories']));
            $signboard->categories()->sync($data['categories']);

            $signboard->handleMediaUpdate($request);

        });

        return back()->with(successRes("Signboard updated successfully."));
    }

    public function delete(Signboard $signboard): RedirectResponse
    {
        Gate::authorize('delete', $signboard);
        $signboard->delete();
        return redirect()->route('my-signboards.index')
            ->with(successRes("Signboard deleted successfully."));
    }

}
