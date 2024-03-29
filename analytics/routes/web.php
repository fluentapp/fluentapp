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
        Route::get('/{domain}/utm-sources', [App\Http\Controllers\HomeController::class, 'utm_sources'])->name('utm-sources');
        Route::get('/{domain}/utm-mediums', [App\Http\Controllers\HomeController::class, 'utm_mediums'])->name('utm-mediums');
        Route::get('/{domain}/utm-campaigns', [App\Http\Controllers\HomeController::class, 'utm_campaigns'])->name('utm-campaigns');
        Route::get('/{domain}/utm-contents', [App\Http\Controllers\HomeController::class, 'utm_contents'])->name('utm-contents');
        Route::get('/{domain}/utm-terms', [App\Http\Controllers\HomeController::class, 'utm_terms'])->name('utm-term');
        Route::get('/{domain}/countries', [App\Http\Controllers\HomeController::class, 'countries'])->name('countries');
        Route::get('/{domain}/cities', [App\Http\Controllers\HomeController::class, 'cities'])->name('cities');
        Route::get('/{domain}/regions', [App\Http\Controllers\HomeController::class, 'regions'])->name('regions');
        Route::get('/{domain}/browsers', [App\Http\Controllers\HomeController::class, 'browsers'])->name('browsers');
        Route::get('/{domain}/operating-systems', [App\Http\Controllers\HomeController::class, 'operatingSystems'])->name('operating-systems');
        Route::get('/{domain}/device-sizes', [App\Http\Controllers\HomeController::class, 'deviceSizes'])->name('cities');
        Route::get('/{domain}/top-pages', [App\Http\Controllers\HomeController::class, 'topPages'])->name('top-pages');
        Route::get('/{domain}/entry-pages', [App\Http\Controllers\HomeController::class, 'entryPages'])->name('entry-pages');
        Route::get('/{domain}/exit-pages', [App\Http\Controllers\HomeController::class, 'exitPages'])->name('exit-pages');
        Route::get('/{domain}/not-found', [App\Http\Controllers\HomeController::class, 'notFound'])->name('not-found');
        Route::get('/{domain}/external-links', [App\Http\Controllers\HomeController::class, 'externalLinks'])->name('external-links');

        Route::get('/manage-site/{domain}', [App\Http\Controllers\SiteController::class, 'show'])->name('manage-site');
        Route::put('/sites/{domain}', [App\Http\Controllers\SiteController::class, 'update'])->name('sites');
        Route::delete('/sites/{domain}', [App\Http\Controllers\SiteController::class, 'destroy'])->name('sites');
        Route::get('/sites/settings/{domain}/', [App\Http\Controllers\SiteController::class, 'getSettings']);
        Route::put('/sites/settings/{domain}/update-page-not-found-settings', [App\Http\Controllers\SiteController::class, 'updatePageNotFoundTitles']);
        Route::put('/sites/settings/{domain}/update-external-links-settings', [App\Http\Controllers\SiteController::class, 'updateExternalLinkSetting']);
    });

    Route::get('timezones', function () {
        return response()->json(Timezone::getAllTimezones());
    });
    Route::get('/manage-sites', [App\Http\Controllers\SiteController::class, 'index'])->name('manage-sites');

    Route::get('/sites', [App\Http\Controllers\SiteController::class, 'list'])->name('sites');
    Route::post('/sites', [App\Http\Controllers\SiteController::class, 'store'])->name('sites');
});
