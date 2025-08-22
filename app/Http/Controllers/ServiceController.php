<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Models\ServiceCategory;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __construct(protected $props = [])
    {
        $this->props = [
            'categories' => toLabelValue(ServiceCategory::query()->select('id', 'name')->get(), 'name', 'id'),
            'regions' => toLabelValue(Region::query()->select('id', 'name')->get(), 'name', 'id'),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),
        ];
    }
    public function index(): Response
    {
        $sort = request('sort', 'desc');

        return Inertia::render('Services/MyServices',[
            'services' =>  auth()->user()->services()
                ->with(['region', 'category', 'user',])
                ->orderBy('created_at', $sort)
                ->paginate(12)
                ->withQueryString(),
            'sort' => $sort,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Services/ServiceCreate',$this->props);
    }

    public function show(string $service): Response
    {
        $service = auth()->user()->services()->whereSlug($service)->firstOrFail();
        $service->views_count = views($service)->count();
        $plans = PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);
        $service = $service->loadMissing(['user','category', 'region', 'promotions.plan']);

        // check if it has payment
        $paymentStatus = Promotion::routeCallback();
        $distributions = RatingService::getDistributions($service);
        return Inertia::render('Services/ServiceShow',[
            'service' => $service,
            'plans' => $plans,
            'payment_status' => $paymentStatus,

            'ratings' => $service->averageRatings(),
            'distributions' => $distributions,
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = null;
        DB::transaction(function () use ($data, $request, &$service) {
            $service = auth()->user()->services()->create(
                Arr::except($data, ['featured', 'gallery'])
            );
            $service->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);
        });
        if ($service) {
            return to_route('my-services.show', $service->slug);
        }

        return back()->with(errorRes("Please try again."));
    }

    public function edit(string $service): Response
    {
        $service = auth()->user()->services()->whereSlug($service)->firstOrFail();
        return Inertia::render('Services/ServiceEdit',array_merge($this->props,[
            'service' =>  $service->load(['user', 'region'])->toArrayWithMedia(),
        ]));
    }

    public function update(UpdateServiceRequest $request, string $service): RedirectResponse
    {
        $service = auth()->user()->services()->findOrFail($service);
        $data = $request->validated();
        DB::transaction(function () use ($service, $data, $request) {
            $service->update(Arr::except($data, ['featured', 'gallery', 'removed_gallery_urls']));
            $service->handleMediaUpdate($request);
        });

        return back()->with(successRes("Service updated successfully."));
    }

    public function destroy(string $service): RedirectResponse
    {
      auth()->user()->services()->findOrFail($service)->delete();
        return redirect()->route('my-services.index')
            ->with(successRes("Service deleted successfully."));
    }
}
