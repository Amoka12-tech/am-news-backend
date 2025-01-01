<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;

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

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('api.register');

    Route::post('/login', [LoginController::class, 'login'])->name('api.login');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');
});


Route::get('/news-feed', [ArticleController::class, 'personalizedFeed']);
Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/categories-sources', [ArticleController::class, 'getCategoriesAndSources']);

// User Management Routes
Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');

    Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');

    Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');

    Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');

    Route::post('/preferences', [UserPreferenceController::class, 'savePreferences']);
    Route::post('/get/preferences', [UserPreferenceController::class, 'getPreferences']);

    Route::get('/articles/search', [ArticleController::class, 'search']);
    Route::get('/news-feed', [ArticleController::class, 'personalizedFeed']);
});
