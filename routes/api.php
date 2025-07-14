<?php

use App\Http\Controllers\GitController;
use App\Http\Controllers\SignboardSubscriptionPaymentController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('github-webhook', GitController::class);

Route::post('signboard-subscription/verify', [SignboardSubscriptionPaymentController::class, 'verifyHubtel'])
    ->name('payments.signboard-subscription.verify');
