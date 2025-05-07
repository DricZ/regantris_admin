<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoucherApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');
Route::middleware('auth:api')->get('profile', fn(Request $r) => $r->user());
Route::post('register', [AuthController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/vouchers', [VoucherApiController::class, 'index']);
Route::get('/vouchers/{id}', [VoucherApiController::class, 'show']);
Route::middleware('auth:api')->post('/vouchers/redeem', [VoucherApiController::class, 'redeem']);