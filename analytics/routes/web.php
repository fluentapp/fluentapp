<?php

use App\Http\Middleware\DomainValidationMiddleware;
use App\Support\Timezone;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['verify' => true]);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('language/{locale}', function ($locale) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    });

    Route::middleware(['domain.validation'])->group(function () {
        Route::get('/home/{domain}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::get('/{domain}/visitors', [App\Http\Controllers\HomeController::class, 'visitors'])->name('visitors');
        Route::get('/{domain}/main-stats', [App\Http\Controllers\HomeController::class, 'mainStat'])->name('main-stats');
        Route::get('/{domain}/connected-visitors', [App\Http\Controllers\HomeController::class, 'currentVisitors'])->name('current-visitors');
        Route::get('/{domain}/sources', [App\Http\Controllers\HomeController::class, 'sources'])->name('sources');
        Route::get('/{domain}/countries', [App\Http\Controllers\HomeController::class, 'countries'])->name('countries');
        Route::get('/{domain}/cities', [App\Http\Controllers\HomeController::class, 'cities'])->name('cities');
        Route::get('/{domain}/regions', [App\Http\Controllers\HomeController::class, 'regions'])->name('regions');
        Route::get('/{domain}/browsers', [App\Http\Controllers\HomeController::class, 'browsers'])->name('browsers');
        Route::get('/{domain}/operating-systems', [App\Http\Controllers\HomeController::class, 'operatingSystems'])->name('operating-systems');
        Route::get('/{domain}/device-sizes', [App\Http\Controllers\HomeController::class, 'deviceSizes'])->name('cities');
        Route::get('/{domain}/top-pages', [App\Http\Controllers\HomeController::class, 'topPages'])->name('top-pages');
        Route::get('/{domain}/entry-pages', [App\Http\Controllers\HomeController::class, 'entryPages'])->name('entry-pages');
        Route::get('/{domain}/exit-pages', [App\Http\Controllers\HomeController::class, 'exitPages'])->name('exit-pages');

        Route::get('/manage-site/{domain}', [App\Http\Controllers\SiteController::class, 'show'])->name('manage-site');
        Route::put('/sites/{domain}', [App\Http\Controllers\SiteController::class, 'update'])->name('sites');
        Route::delete('/sites/{domain}', [App\Http\Controllers\SiteController::class, 'destroy'])->name('sites');
        Route::get('/sites/settings/{domain}/page-not-found-titles', [App\Http\Controllers\SiteController::class, 'getPageNotFoundTitles']);
        Route::put('/sites/settings/{domain}/update-page-not-found-settings', [App\Http\Controllers\SiteController::class, 'updatePageNotFoundTitles']);
    });

    Route::get('timezones', function () {
        return response()->json(Timezone::getAllTimezones());
    });
    Route::get('/manage-sites', [App\Http\Controllers\SiteController::class, 'index'])->name('manage-sites');

    Route::get('/sites', [App\Http\Controllers\SiteController::class, 'list'])->name('sites');
    Route::post('/sites', [App\Http\Controllers\SiteController::class, 'store'])->name('sites');
});
