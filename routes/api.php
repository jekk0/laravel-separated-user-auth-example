<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\UserAuthController;

Route::group(['prefix' => '/auth/admin'], function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:jwt-admin');
    Route::post('/logout/all', [AdminAuthController::class, 'logoutFromAllDevices'])->middleware('auth:jwt-admin');
    Route::get('/profile', [AdminAuthController::class, 'profile'])->middleware('auth:jwt-admin');
});

Route::group(['prefix' => '/auth/company'], function () {
    Route::post('/login', [CompanyAuthController::class, 'login']);
    Route::post('/refresh', [CompanyAuthController::class, 'refresh']);
    Route::post('/logout', [CompanyAuthController::class, 'logout'])->middleware('auth:jwt-company');
    Route::post('/logout/all', [CompanyAuthController::class, 'logoutFromAllDevices'])->middleware('auth:jwt-company');
    Route::get('/profile', [CompanyAuthController::class, 'profile'])->middleware('auth:jwt-company');
});

Route::group(['prefix' => '/auth/user'], function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);
    Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:jwt-user');
    Route::post('/logout/all', [UserAuthController::class, 'logoutFromAllDevices'])->middleware('auth:jwt-user');
    Route::get('/profile', [UserAuthController::class, 'profile'])->middleware('auth:jwt-user');
});
