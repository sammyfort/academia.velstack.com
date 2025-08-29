<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\{CountryController,
    DownloadController,
    JobPublicController,
    ProductController,
    ServiceController,
    PromotionController,
    HomeController,
    ProfileController,
    ProductPublicController,
    RatingController,
    ServicePublicController,
    SignboardController,
    JobController,
    SignboardPublicController};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home/search-directory', [HomeController::class, 'searchDirectory'])->name('home.search');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::patch('edit/personal', [ProfileController::class, 'editPersonalDetails'])->name('edit-personal');
        Route::patch('edit/socials', [ProfileController::class, 'editSocials'])->name('edit-socials');
        Route::post('edit/avatar', [ProfileController::class, 'uploadAvatar'])->name('edit-avatar');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('my-businesses')->name('my-businesses.')->group(function () {
        Route::get('/', [BusinessController::class, 'myBusinesses'])->name('index');
        Route::post('/create', [BusinessController::class, 'create'])->name('create');
        Route::get('/{business:slug}', [BusinessController::class, 'show'])->name('show');
        Route::put('/{business}', [BusinessController::class, 'update'])->name('update');
        Route::delete('/{business}', [BusinessController::class, 'delete'])->name('delete');
    });

    Route::prefix('my-signboards')->as('my-signboards.')->group(function () {
        Route::get('/', [SignboardController::class, 'mySignboards'])->name('index');
        Route::get('/create', [SignboardController::class, 'create'])->name('create');
        Route::post('/store', [SignboardController::class, 'store'])->name('store');
        Route::get('/details/{signboard:slug}', [SignboardController::class, 'showMySignboard'])->name('show');
        Route::get('/edit/{signboard:slug}', [SignboardController::class, 'edit'])->name('edit');
        Route::post('/{signboard}', [SignboardController::class, 'update'])->name('update');
        Route::delete('/{signboard}', [SignboardController::class, 'delete'])->name('delete');
    });

    Route::resources([
        'my-services' => ServiceController::class,
        'my-jobs' => JobController::class,
        'my-products' => ProductController::class,
    ]);

    Route::post('rate', [RatingController::class, 'rate'])->name('ratings.rate');
});

Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::get('/', [BusinessController::class, 'index'])->name('index');
});


Route::prefix('service-providers')->as('services.')->group(callback: function () {
    Route::get('/', [ServicePublicController::class, 'index'])->name('index');
    Route::get('/promoted', [ServicePublicController::class, 'getPromotedSignboards'])->name('promoted');
    Route::get('/details/{service:slug}', [ServicePublicController::class, 'show'])->name('show');
});


Route::prefix('signboards')->as('signboards.')->group(function () {
    Route::get('/', [SignboardPublicController::class, 'index'])->name('index');
    Route::get('/{signboard:slug}/details', [SignboardPublicController::class, 'show'])->name('show');
    Route::get('/promoted', [SignboardPublicController::class, 'getPromotedSignboards'])->name('promoted');
});


Route::prefix('jobs')->as('jobs.')->group(callback: function () {
    Route::get('/', [JobPublicController::class, 'index'])->name('index');
    Route::get('/promoted', [JobPublicController::class, 'getPromotedJobs'])->name('promoted');
    Route::get('/details/{job:slug}', [JobPublicController::class, 'show'])->name('show');
});


Route::prefix('products')->as('products.')->group(callback: function () {
    Route::get('/', [ProductPublicController::class, 'index'])->name('index');
    Route::get('/promoted', [ProductPublicController::class, 'getPromotedProducts'])->name('promoted');
    Route::get('/details/{product:slug}', [ProductPublicController::class, 'show'])->name('show');
});


Route::post('contact-us', [ContactUsController::class, 'store'])->name('contact-us');
Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('about-us', fn()=> Inertia::render('AboutUs'))->name('about-us');
Route::get('privacy-policy', fn()=> Inertia::render('PrivacyPolicy'))->name('privacy-policy');


Route::post('promotions/payment/fiat', [PromotionController::class, 'initializeHubtel'])
    ->middleware(['auth', 'verified'])
    ->name('promotions.payment.initialize');
Route::post('promotions/payment/points', [PromotionController::class, 'payUsingPoints'])
    ->middleware(['auth', 'verified'])
    ->name('promotions.payment.points');

Route::prefix('countries')->as('countries.')->group(callback: function () {
    Route::get('/', [CountryController::class, 'index'])->name('index');
    Route::get('/regions', [CountryController::class, 'regions'])->name('regions');
});

Route::get('download/apk', [DownloadController::class, 'downloadAPK'])->name('download.apk');


require __DIR__.'/auth.php';
