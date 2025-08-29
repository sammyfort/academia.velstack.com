<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use App\Services\RatingService;
use App\Services\SignboardService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;


class SignboardPublicController extends Controller
{
    public function index(): Response
    {
        $signboards = SignboardService::getSignboards();
        $regions = Region::query()->select(['id', 'name'])->get();
        $categories = SignboardCategory::query()->select(['id', 'name'])->get();

        return Inertia::render('Signboards/Signboards', [
            'signboardsData' => $signboards,
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function show(Signboard $signboard): Response
    {
        $signboard->loadMissing(['reviews.ratings', 'service.user', 'region.country', 'categories', 'media']);
        $averageRatings = $signboard->averageRatings();
        $distributions = RatingService::getDistributions($signboard);

        if (!auth() || auth()->id() != $signboard->created_by_id){
            $viewCooldown = now()->addHours(3);
            views($signboard)->cooldown($viewCooldown)->record();
        }
        $signboard->views_count = views($signboard)->count();

        return Inertia::render('Signboards/Signboard', [
            'signboard' => $signboard->toArrayWithMedia(),
            'ratings' => $averageRatings,
            'distributions' => $distributions,
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $signboards = SignboardService::getPromoted();
        return response()->success([
            'signboards' => $signboards
        ]);
    }
}
