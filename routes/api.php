<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\AttendanceController as ApiAttendenceController;
use App\Http\Controllers\Api\JoinerController as ApiJoinerController;
use App\Http\Controllers\Api\CreatorController as ApiCreatorController;
use App\Http\Controllers\Api\SubjectController as ApiSubjectController;

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

Route::middleware(['auth', 'jwt.verify'])->group(function () {

    Route::controller(ApiUserController::class)->group(function () {

        Route::get('users', 'index');
        Route::get('user/{id}', 'getUser');
        Route::post('user/store', 'store');
        Route::put('user/update/{id}', 'update');
        Route::delete('user/destroy/{id}', 'destroy');
        Route::get('user/restore/{id}', 'restoreUser');
        Route::get('users/restore', 'restoreAll');
    });

    Route::controller(ApiSubjectController::class)->group(function () {
        Route::get('subjects', 'index');
        Route::get('subject/{id}', 'getSubject');
        Route::get('subject/{id}/edit', 'edit');
        Route::put('subject/{id}/update', 'update')->name('updateSub');
        Route::delete('subject/{id}/destroy', 'destroy');
        Route::get('subject/{id}/restore', 'restoreSubject');
        Route::get('subject-restoreAll', 'restoreAll');

        ///////////////////////////////////////////////////////////////
        Route::get('select-subject', 'selectSubject')->name('selectSubject');
        Route::post('generate-code', 'GenerateCode')->name('generateCode'); // done here
        Route::get('attend-me/{id}', 'attendMe')->name('attendMe'); // done here
        Route::post('join-subject', 'joinSubject')->name('join'); // done here
        Route::post('attend', 'checkCode')->name('attend'); // done here
        Route::get('show-subject/{close?}', 'OpenTimer')->name('openTimer');
        Route::post('start-timer/{close?}', 'start')->name('start-timer');
        ///////////////////////////////////////////////////////////////
    });


    Route::controller(ApiCreatorController::class)->group(function () {
        Route::get('create-subject', 'create');
        Route::post('store-subject', 'store')->name('storeSubject');
        Route::get('creator/{id}/createSubjects', 'getSubjectsCreatedByUser');
        Route::get('subject/{id}/createdBy', 'getUserCreatedSubject');
    });

    Route::controller(ApiJoinerController::class)->group(function () {

        Route::post('sub/{id}/join', 'joinSubject');
        Route::post('Joiner-store', 'store')->name('storeJoiner');
        Route::get('joiner/{id}/joinSubjects', 'getSubjectsJoinedByUser');
        Route::get('subject/{id}/joinedBy', 'getUsersJoinedTOSubject');
    });

    ///////////////////////////////////////////////////////////////////////////////
    Route::controller(ApiAttendenceController::class)->group(function () {
        Route::get('attendences', "Attendance")->name('attendences');
        Route::post('all-attendences', 'getAllAttendances')->name('getAttendances');
    });
    ///////////////////////////////////////////////////////////////////////////////
});
