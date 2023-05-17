<?php

use App\Models\User;
use App\Models\Creator;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JoinerController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendenceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

date_default_timezone_set("Africa/Cairo");

Route::get('/', function () {
    return view('auth.login');
});

Route::get('test', function () {

    $x =  Creator::latest()->first();
    foreach ($x->subject as $a) {
        echo "$a->id  -" . $a->sub_name . "<br>";
    }
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::get('user/{id}', 'getUser');
        Route::get('create-user', 'create');
        Route::post('user-store', 'store')->name('store');
        Route::get('user/{id}/edit', 'edit');
        Route::put('user/{id}/update', 'update')->name('update');
        Route::get('user/{id}/destroy', 'destroy');
        Route::get('user/{id}/restore', 'restoreUser');
        Route::get('users-restoreAll', 'restoreAll');
    });

    Route::controller(SubjectController::class)->group(function () {
        Route::get('subjects', 'index')->name('subjects');
        Route::get('all-subjects', 'show')->name('allSubjects');
        Route::get('subject/{id}', 'getSubject');
        Route::get('subject/{id}/edit', 'edit');
        Route::put('subject/{id}/update', 'update')->name('updateSub');
        Route::get('subject/{id}/destroy', 'destroy');
        Route::get('subject/{id}/restore', 'restoreSubject');
        Route::get('subject-restoreAll', 'restoreAll');

        ///////////////////////////////////////////////////////////////
        Route::get('select-subject', 'selectSubject')->name('selectSubject');
        Route::post('generate-code', 'GenerateCode')->name('generateCode');
        Route::get('attend-me/{id}', 'attendMe')->name('attendMe');
        Route::post('join-subject', 'joinSubject')->name('join');
        Route::post('attend', 'checkCode')->name('attend');
        Route::get('show-subject/{close?}', 'OpenTimer')->name('openTimer');
        Route::post('start-timer/{close?}', 'start')->name('start-timer');
        ///////////////////////////////////////////////////////////////
    });

    Route::controller(CreatorController::class)->group(function () {
        Route::get('create-subject', 'create');
        Route::post('store-subject', 'store')->name('storeSubject');
        Route::get('user/{id}/createSubjects', 'getSubjectsCreatedByUser')->name('createdSubject');
        Route::get('subject/{id}/createdBy', 'getUserCreatedSubject');
    });

    Route::controller(JoinerController::class)->group(function () {
        Route::get('sub/{id}/join', 'joinSubject');
        Route::post('Joiner-store', 'store')->name('storeJoiner');
        Route::get('joiner/{id}/joinSubjects', 'getSubjectsJoinedByUser')->name('joinedSubject');
        Route::get('subject/{id}/joinedBy', 'getUsersJoinedTOSubject');
    });
    ///////////////////////////////////////////////////////////////////////////////
    Route::controller(AttendenceController::class)->group(function () {
        Route::get('attendences', "Attendance")->name('attendences');
        Route::post('all-attendences', 'getAllAttendances')->name('getAttendances');
    });
    ///////////////////////////////////////////////////////////////////////////////
});

require __DIR__ . '/auth.php';
