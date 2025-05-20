<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BankAccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');


Route::middleware(['auth:sanctum', 'check.api.key'])->group(function () {


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Groupe de routes pour les comptes bancaires
    Route::prefix('account')->group(function () {

        Route::post('/', [BankAccountController::class, 'store']);
        Route::post('/transaction', [BankAccountController::class, 'makeBankAccountTransaction']);
        Route::get('/balance', [BankAccountController::class, 'checkBankAccountBalance']);
        Route::get('/histories', [BankAccountController::class, 'bankAccountHistories']);

    });
});


