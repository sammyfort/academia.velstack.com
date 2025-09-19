
<?php

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:staff')->group(function () {
    Route::get('/api/subjects', [ ApiController::class, 'subjects'])->name('api.subjects');
    Route::get('/api/staff', [ ApiController::class, 'staff'])->name('api.staff');
});
