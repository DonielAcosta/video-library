<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/videos/search', [VideoController::class, 'search'])->name('videos.search');
Route::resource('videos', VideoController::class)->except(['create', 'edit']);

Route::get('/videos/{video}/analytics',       [AnalyticsController::class, 'show']);
Route::post('/videos/{video}/analytics/views', [AnalyticsController::class, 'incrementViews']);
// Route::middleware('auth:sanctum')->group(function () {
    
//     // Otras rutas
//     Route::get('videos/search', [VideoController::class, 'search']);
//     Route::get('videos/{video}/analytics', [AnalyticsController::class, 'show']);
//     Route::post('videos/{video}/increment-views', [AnalyticsController::class, 'incrementViews']);
    
//     Route::middleware('admin')->group(function () {
//         Route::apiResource('videos', VideoController::class)->except(['index', 'show']);
//     });
    
//     Route::get('videos/{video}', [VideoController::class, 'show']);
  
// });

    // Route::middleware('admin')->group(function () {
    //     Route::apiResource('videos', VideoController::class)->except(['index', 'show']);
    // });
    


