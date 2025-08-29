<?php

namespace App\Http\Controllers;

use App\Enums\JobMode;
use App\Enums\JobModeOfApply;
use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\PromotionPlan;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class JobController extends Controller
{
    public function __construct(protected $props = [])
    {
        $this->props = [
            'categories' => toLabelValue(JobCategory::query()->select('id', 'name')->get(), 'name', 'id'),
            'statuses' => toLabelValue(JobStatus::toArray()),
            'types' => toLabelValue(JobType::toArray()),
            'modes' => toLabelValue(JobMode::toArray()),
            'regions' => toLabelValue(Region::query()->select('id', 'name')->get(), 'name', 'id'),
            'apply_modes' => toLabelValue(JobModeOfApply::toArray()),
            'countries' => toLabelValue(Country::query()->select('id', 'name')->get(), 'name', 'id'),

        ];
    }

    public function index(): Response
    {
        $sort = request('sort', 'desc');
        $status = request('status', 'all');
        $search = request('search');

        $query = auth()->user()->jobs()->orderBy('created_at', $sort);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return Inertia::render('Jobs/MyJobs', [
            'jobs' => $query->paginate(12)->withQueryString(),
            'sort' => $sort,
            'status' => $status,
            'search' => $search,
        ]);
    }


    public function show(string $job): Response
    {
        $job = auth()->user()->jobs()->whereSlug($job)->firstOrFail();;
        $job->views_count = views($job)->count();
        $job->loadMissing(['region', 'categories', 'promotions']);
        return Inertia::render('Jobs/JobShow', [
            'job' => $job,
            'plans' => PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price'])
        ]);
    }


    public function create(): Response
    {
        return Inertia::render('Jobs/JobCreate', $this->props);
    }


    /**
     * @param StoreJobRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $job = null;

        DB::transaction(function () use ($data, $request, &$job) {
            $job = $request->user()->jobs()->create(
                Arr::except($data, ['company_logo', 'categories', 'apply_mode'])
            );
            $job->handleUploads($request, [
                'company_logo' => 'company_logo',
            ]);
            $categoryIds = collect($data['categories'])->map(function ($new) {
                if (is_numeric($new)) {
                    return (int) $new;
                }
                $category = JobCategory::firstOrCreate(['name' => $new]);
                return $category->id;
            });
            $job->categories()->sync($categoryIds);
        });

        if ($job) {
            return to_route('my-jobs.show', $job->slug);
        }

        return back()->with(successRes("Please try again."));
    }


    public function edit(string $job): Response
    {
        $job = auth()->user()->jobs()->whereSlug($job)->firstOrFail();
        return Inertia::render('Jobs/JobEdit', array_merge($this->props, [
            'job' => $job->loadMissing(['categories'])->toArrayWithMedia(),
        ]));
    }

    public function update(UpdateJobRequest $request, string $job): RedirectResponse
    {
        $job = auth()->user()->jobs()->findOrFail($job);
        $data = $request->validated();


        //dd($data);
        DB::transaction(function () use ($request, $data, $job) {
            //$job->refresh();
            $job->update(Arr::except($data, ['company_logo', 'categories', 'apply_mode']));

            $job->categories()->sync($data['categories']);
            $job->handleUploads($request, [
                'company_logo' => 'company_logo',
            ]);
        });

        return back()->with(successRes("Job updated successfully."));
    }


    public function destroy(string $job): RedirectResponse
    {
        auth()->user()->jobs()->findOrFail($job)->delete();
        return redirect()->route('my-jobs.index')
            ->with(successRes("Job deleted successfully."));
    }
}
