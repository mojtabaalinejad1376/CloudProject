<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\VisitTimeController;


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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('change-profile', [RegisterController::class, 'changeProfile']);
Route::post('change-password', [RegisterController::class, 'changePassword']);
Route::post('create-doctor', [DoctorController::class, 'create']);
Route::post('filter-doctor', [RegisterController::class, 'filter']);
Route::post('create-visit-time', [VisitTimeController::class, 'create_visit_time']);
Route::post('show-visit-time', [DoctorController::class, 'show_visit_time']);
