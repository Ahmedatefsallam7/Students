<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
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

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/reset-password', [PasswordResetLinkController::class, 'store']);
});

Route::middleware(['jwt.verify'])->controller(ApiUserController::class)->group(function () {
"eee";
    Route::get('users', 'index');
    Route::get('user/{id}', 'getUser');
    Route::post('user/store', 'store');
    Route::put('user/update/{id}', 'update');
    Route::delete('user/destroy/{id}', 'destroy');
    Route::get('user/restore/{id}', 'restoreUser');
    Route::get('users/restore', 'restoreAll');
});
