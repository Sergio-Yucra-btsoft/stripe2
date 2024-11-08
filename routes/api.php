<?php

use App\Http\Controllers\PaymentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('checkout',[PaymentController::class, 'checkout']);
Route::get('webhook',[PaymentController::class, 'webhook']);
Route::get('webhookSubscription' ,[PaymentController::class, 'webhookSubscription']);
Route::get('cancel-subscription', [PaymentController::class, 'cancelSubscription']);
