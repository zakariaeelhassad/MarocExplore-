<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ItinerariesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [AuthController::class, 'index']);
// register the user 
Route::post('/register', [AuthController::class , 'store'])->name('register');

Route::get('/itineraries', [ItinerariesController::class, 'index']);
Route::get('/getDestinationToItinerarie', [ItinerariesController::class, 'getDestinationToItinerarie']);

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post('/itineraries/add', [ItinerariesController::class , 'store']);
    Route::put('/itineraries/{id}/update' , [ItinerariesController::class , 'update']);
    Route::delete('/itineraries/{id}/delete' , [ItinerariesController::class , 'destroy']);
    Route::post('/itineraries/{id}/wishlist', [ItinerariesController::class, 'addToWishlist']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [ItinerariesController::class, 'getWishlist']); 
    Route::post('/itineraries/{itinerary_id}/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{id}/update' , [DestinationController::class , 'update']);
    Route::delete('/destinations/{id}/delete' , [DestinationController::class , 'destroy']);
    Route::get('/itineraries/search', [ItinerariesController::class, 'search']);
});
