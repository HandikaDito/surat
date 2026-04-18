<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

// AUTH
use App\Http\Controllers\Auth\LoginController;

// DASHBOARD
use App\Http\Controllers\Dashboard\DashboardController;

// SURAT
use App\Http\Controllers\Surat\SuratMasukController;
use App\Http\Controllers\Surat\SuratKeluarController;

// DISPOSISI
use App\Http\Controllers\Disposition\DispositionController;
use App\Http\Controllers\Disposition\TrackingController;

// ADMIN
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ConfigController;

// PROFILE
use App\Http\Controllers\ProfileController;

// NOTIFICATION
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('home');


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */
    Route::get('/notif', [NotificationController::class, 'index'])->name('notif.get');
    Route::post('/notif/read', [NotificationController::class, 'read'])->name('notif.read');


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->middleware('role:0')->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | SURAT MASUK
    |--------------------------------------------------------------------------
    */
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {

        Route::get('/', [SuratMasukController::class, 'index'])->name('index');
        Route::get('/create', [SuratMasukController::class, 'create'])->name('create');
        Route::post('/', [SuratMasukController::class, 'store'])->name('store');
        Route::get('/{suratMasuk}', [SuratMasukController::class, 'show'])->name('show');
        Route::delete('/{suratMasuk}', [SuratMasukController::class, 'destroy'])->name('destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | SURAT KELUAR + WORKFLOW
    |--------------------------------------------------------------------------
    */
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {

        // CRUD
        Route::get('/', [SuratKeluarController::class, 'index'])->name('index');
        Route::get('/create', [SuratKeluarController::class, 'create'])->name('create');
        Route::post('/', [SuratKeluarController::class, 'store'])->name('store');
        Route::get('/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('show');
        Route::delete('/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('destroy');

        // WORKFLOW
        Route::post('/{suratKeluar}/submit', [SuratKeluarController::class, 'submit'])
            ->name('submit');

        Route::post('/{suratKeluar}/approve', [SuratKeluarController::class, 'approve'])
            ->name('approve')
            ->middleware('role:1,2');

        Route::post('/{suratKeluar}/reject', [SuratKeluarController::class, 'reject'])
            ->name('reject')
            ->middleware('role:1,2');

    });


    /*
    |--------------------------------------------------------------------------
    | DISPOSISI (FINAL)
    |--------------------------------------------------------------------------
    */
    Route::prefix('disposisi')->name('disposition.')->group(function () {

        // list
        Route::get('/', [DispositionController::class, 'index'])->name('index');

        // create
        Route::post('/', [DispositionController::class, 'store'])->name('store');

        // forward
        Route::post('/{disposition}/forward', [DispositionController::class, 'forward'])
            ->name('forward');

        // done + laporan
        Route::post('/{disposition}/done', [DispositionController::class, 'done'])
            ->name('done');

        // 🔥 tracking (gunakan surat_id dari disposition)
        Route::get('/{disposition}/tracking', [TrackingController::class, 'show'])
            ->name('tracking');

    });


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', function () {
            return view('profile.index');
        })->name('index');

        Route::put('/', [ProfileController::class, 'update'])->name('update');

    });

});


/*
|--------------------------------------------------------------------------
| SETTINGS (ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:0'])->group(function () {

    Route::get('/settings', [ConfigController::class, 'index'])->name('settings.index');
    Route::put('/settings', [ConfigController::class, 'update'])->name('settings.update');

});