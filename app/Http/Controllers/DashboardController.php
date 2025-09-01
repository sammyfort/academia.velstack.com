<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Signboard;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user()->loadMissing([
            'signboards.service',
           // 'businesses',
            'products',
            'services',
            'jobs',
        ]);

        $promotions = Signboard::getAllPromotionsQuery()
            ->where('created_by_id', $user->id)
            ->get();

        return Inertia::render('Dashboard/Dashboard', [
            'user' => $user,
            'promotions' => $promotions,
            'signboards' => $user->signboards,
           // 'businesses' => $user->businesses,
            'products' => $user->products,
            'services' => $user->services,
            'jobs' => $user->jobs,
        ]);
    }

}
