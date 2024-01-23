<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Admin\AdminProfileController;
use App\Http\Controllers\V1\Auth\AuthenticationController;
use App\Http\Controllers\V1\User\UserProfileController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function () {
  Route::controller(AuthenticationController::class)->prefix('auth')->group(function () {
    Route::post('logout', 'logout');
  });

  Route::controller(UserProfileController::class)->prefix('user/profile')->group(function () {
    Route::patch('update/{id}', 'updateProfile');
    Route::patch('update/password/{id}', 'updatePassword');
  });

  Route::controller(AdminProfileController::class)->prefix('admin/profile')->group(function () {
    Route::patch('update/{id}', 'update');
  });
});

Route::post('auth/signup', [AuthenticationController::class, 'signup']);
Route::post('auth/login', [AuthenticationController::class, 'login']);
