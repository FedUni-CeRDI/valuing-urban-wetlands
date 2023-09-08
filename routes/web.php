<?php

use App\Http\Controllers\LandUseController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SidebarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::prefix('content')->group(function () {
    Route::get('intro', [SidebarController::class, 'intro'])->name('intro');
    Route::get('contact', [SidebarController::class, 'contact'])->name('contact');
    Route::get('terms', [SidebarController::class, 'terms'])->name('terms');
    Route::get('about', [SidebarController::class, 'about'])->name('about');
});

Route::prefix('app')->group(function () {
    Route::get('wetlands', [PublicController::class, 'getWetlandNames']);
    Route::post('area/ala-birds', [PublicController::class, 'alaWaterbirdCounts']);
    Route::get('species-info', [PublicController::class, 'speciesInfo']);
    Route::get('config', [PublicController::class, 'config']);
    Route::prefix('landuse')->group(function () {
        Route::prefix('planning')->group(function () {
            Route::post('zones', [LandUseController::class, 'getPlanningZones']);
            Route::post('overlays', [LandUseController::class, 'getPlanningOverlays']);
        });
        Route::post('catchment', [LandUseController::class, 'getCatchmentLandUse']);
        Route::prefix('vluis')->group(function () {
            Route::post('property', [LandUseController::class, 'getVluisPropertyClassification']);
            Route::post('alum', [LandUseController::class, 'getVluisLandUse']);
            Route::post('landcover', [LandUseController::class, 'getVluisLandCover']);
        });
    });

    Route::post('snipe/seasonal-counts', [PublicController::class, 'snipeSeasonalCounts']);
    Route::post('snipe/ala-seasonal-counts', [PublicController::class, 'snipeAlaSeasonalCounts']);
});
