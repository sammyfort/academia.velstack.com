<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Enums\YesNo;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Country;
use App\Models\ProductCategory;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ProductController extends Controller
{
    public function __construct(protected $props = [])
    {
        $this->props = [
            'regions'    => toLabelValue(Region::query()->select(['id', 'name'])->get(), 'name', 'id'),
            'categories' => toLabelValue(ProductCategory::query()->select(['id', 'name'])->get(), 'name', 'id'),
            'choices'    => toLabelValue(YesNo::toArray()),
            'statuses'    => toLabelValue(ProductStatus::toArray()),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),

        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sort = request('sort', 'desc');

        return Inertia::render('Products/MyProducts', [
            'products' => auth()->user()->products()
                ->with(['user',])
                ->orderBy('created_at', $sort)
                ->paginate(12)
                ->withQueryString(),
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

        return Inertia::render('Products/ProductCreate', $this->props);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_negotiable'] = YesNo::from($data['is_negotiable'])->toBool();

        $product = null;
        DB::transaction(function () use ($data, $request, &$product) {
            $product = auth()->user()->products()->create(
                Arr::except($data, ['featured', 'gallery', 'categories'])
            );
            $product->categories()->sync($data['categories']);

            $product->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);
        });

        if ($product) {
            return to_route('my-products.show', $product->slug);
        }
        return back()->with(successRes("Please try again."));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product): Response
    {
        $product = auth()->user()->products()->whereSlug($product)->firstOrFail();

        $product->views_count = views($product)->count();
        $plans = PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);
        $product = $product->loadMissing(['user', 'region', 'promotions.plan', 'reviews']);

        // check if it has payment
        $paymentStatus = Promotion::routeCallback();
        $distributions = RatingService::getDistributions($product);

        return Inertia::render('Products/ProductShow', [
            'product' => $product->toArrayWithMedia(),
            'plans' => $plans,
            'payment_status' => $paymentStatus,
            'ratings' => $product->averageRatings(),
            'distributions' => $distributions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $product): Response
    {
        $product = auth()->user()->products()->whereSlug($product)->firstOrFail();
        return Inertia::render('Products/ProductEdit', array_merge($this->props, [
            'product' => $product->load(['user', 'region', 'categories'])->toArrayWithMedia(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(UpdateProductRequest $request, string $product): RedirectResponse
    {
        $product = auth()->user()->products()->findOrFail($product);
        $data = $request->validated();
        $data['is_negotiable'] = YesNo::from($data['is_negotiable'])->toBool();

        DB::transaction(function () use ($product, $data, $request) {
            $product->update(Arr::except($data, ['featured', 'gallery', 'categories', 'removed_gallery_urls']));

            $product->categories()->sync($data['categories']);
            $product->handleMediaUpdate($request);
        });

        return back()->with(successRes("Product updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product): RedirectResponse
    {
        auth()->user()->products()->findOrFail($product)->delete();
        return redirect()->route('my-products.index')
            ->with(successRes("Product deleted successfully."));
    }
}
