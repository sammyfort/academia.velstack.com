<?php

namespace App\Http\Controllers;


use App\Http\Requests\Signboard\RateRequest;
use App\Http\Requests\Signboard\StoreSignboardRequest;
use App\Http\Requests\Signboard\UpdateSignboardRequest;
use App\Models\Country;
use App\Models\Promotion;
use App\Models\PromotionPlan;
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

    public function __construct(protected HelperService $helperService)
    {

    }

    public function rate(Signboard $signboard, RateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $reviewData = [
                'review' => $validatedData['review'],
                // 'recommend'  => true,
                'ratings' => [
                    'overall' => $validatedData['overall'],
                    'customer_service' => $validatedData['customer_service'],
                    'quality' => $validatedData['quality'],
                    'price' => $validatedData['price'],
                    'communication' => $validatedData['communication'],
                    'speed' => $validatedData['speed'],
                ],
            ];
            // check if user has already rated
            $review = $signboard->reviews()->where('user_id', auth()->id())->first();
            if ($review) {
                $signboard->updateReview($review->id, $reviewData);
            } else {
                $review = $signboard->addReview($reviewData, auth()->id());
            }
            DB::commit();
            $signboard->refresh();
            $signboard->loadMissing([
                'business',
                'region',
                'reviews' => function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                },
            ]);
            $data['signboard'] = $signboard;
            return back()->with(successRes('Signboard Rated Successfully', $data));
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with(errorRes());
        }
    }

    public function mySignboards(): Response
    {
        $user = auth()->user();
        return Inertia::render('Signboards/MySignboards', [
            'signboards' => $user->signboards()->with(['region', 'business'])->latest()->paginate(10),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Signboards/SignboardCreate',[
            'business' => $request->business,
            'categories' => $this->helperService->getCategories(),
            'regions' => $this->helperService->getRegions(),
            'businesses' => $this->helperService->getAuthBusinesses(),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),

        ]);
    }

    public function store(StoreSignboardRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $business = $request->user()->businesses()->findOrFail($data['business_id']);
        $signboard = null;

        DB::transaction(function () use ($business, $data, $request, &$signboard) {
            $signboard = $business->signboards()->create(
                Arr::except($data, ['featured_image', 'gallery_images', 'categories'])
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
                'featured' => 'featured_image',
                'gallery'  => 'gallery_images',
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
        $signboard->loadMissing(['reviews.ratings','business.user', 'region', 'reviews', 'categories', 'promotions.plan']);

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

        return Inertia::render('Signboards/SignboardEdit', [
            'signboard' => $signboard->load(['business', 'region', 'categories'])->toArrayWithMedia(),
            'categories' => $this->helperService->getCategories(),
            'regions' => $this->helperService->getRegions(),
            'businesses' => $this->helperService->getAuthBusinesses(),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),

        ]);
    }


    public function update(UpdateSignboardRequest $request, Signboard $signboard): RedirectResponse
    {
        Gate::authorize('update', $signboard);

        $data = $request->validated();

        //dd($data);
        DB::transaction(function () use ($signboard, $data, $request) {
            $signboard->update(Arr::except($data, ['featured_image', 'gallery_images', 'removed_gallery_urls', 'categories']));
            $signboard->categories()->sync($data['categories']);

            if ($request->hasFile('featured_image')) {
                $signboard->addMediaFromRequest('featured_image')->toMediaCollection('featured');
            }

            $removedUrls = $request->input('removed_gallery_urls', []);
            if (!empty($removedUrls)) {

                $galleryMedia = $signboard->getMedia('gallery');
                foreach ($galleryMedia as $media) {
                    if (in_array($media->getUrl(), $removedUrls)) {
                        $media->delete();
                    }
                }
            }
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $signboard->addMedia($file)->toMediaCollection('gallery');
                }
            }

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
