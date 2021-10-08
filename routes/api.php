<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return response()->json('Welcome api evermos');
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group([
    'middleware' => ['api', 'auth.jwt', 'log.route'],
    'prefix' => 'auth'

], function ($router) {
    Route::get('/user-profile', [ProfileController::class, 'index']);
});

Route::group([
    'middleware' => ['api', 'auth.jwt', 'log.route'],

], function ($router) {
    Route::post('/cart', [TransactionController::class, 'cart']);
    Route::put('/checkout/{id}', [TransactionController::class, 'checkout']);
    Route::put('/pay/{id}', [TransactionController::class, 'pay']);
    Route::put('/cancel/{id}', [TransactionController::class, 'cancel']);
});