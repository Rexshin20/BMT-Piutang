<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/piutang', [ReceivableController::class, 'index'])->name('piutang.index');

    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/peminjam', [MasterDataController::class, 'index'])->name('borrowers');
        Route::post('/peminjam', [MasterDataController::class, 'store'])->name('borrowers.store');
        Route::put('/peminjam/{peminjam}', [MasterDataController::class, 'update'])->name('borrowers.update');
        Route::delete('/peminjam/{peminjam}', [MasterDataController::class, 'destroy'])->name('borrowers.destroy');
    });

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'profile'])->name('index');
        Route::get('/profile', [SettingsController::class, 'profile'])->name('profile');
        Route::get('/account', [SettingsController::class, 'account'])->name('account');
        Route::put('/account', [SettingsController::class, 'updateAccount'])->name('account.update');
        Route::get('/appearance', [SettingsController::class, 'appearance'])->name('appearance');
    });

    Route::post('/sign-out', [AuthController::class, 'signout'])->name('auth.signout');
});

Route::prefix('sign-in')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'signin'])->name('signin');
    Route::post('/', [AuthController::class, 'signinSubmit'])->name('signin.post');
});

Route::prefix('sign-up')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'signup'])->name('signup');
    Route::post('/', [AuthController::class, 'signupSubmit'])->name('signup.post');
});

Route::prefix('forgot-password')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'forgot'])->name('forgot');
    Route::post('/', [AuthController::class, 'forgotSubmit'])->name('forgot.post');
});

Route::prefix('otp')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'otp'])->name('otp');
    Route::post('/', [AuthController::class, 'otpSubmit'])->name('otp.post');
});

Route::get('/errors/{code}', [ErrorController::class, 'show'])
    ->whereIn('code', ['401', '403', '404', '500', '503'])
    ->name('errors.show');

Route::get('/help-center', [HelpCenterController::class, 'index'])->name('help.index');

Route::fallback(function () {
    return app(ErrorController::class)->show('404');
});
