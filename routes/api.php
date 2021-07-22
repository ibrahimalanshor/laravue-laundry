<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{AuthController, DashboardController, PacketController, CustomerController, UserController, TransactionController, SettingController};

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

Route::group(['middleware' => 'auth:sanctum'], function ()
{

    Route::get('/setting', [SettingController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'profile']);

    Route::group(['middleware' => 'sanctum.abilities:operator'], function ()
    {
        Route::group(['prefix' => '/transactions'], function ()
        {
            Route::get('/note', [TransactionController::class, 'getLatestNote']);
            Route::get('/note/{note}', [TransactionController::class, 'findByNote']);
            Route::patch('/{transaction}/working-status', [TransactionController::class, 'updateWorkingStatus']);
            Route::patch('/{transaction}/payment-status', [TransactionController::class, 'updatePaymentStatus']);
        });

        Route::resource('packets', PacketController::class)->except(['create', 'edit']);
        Route::resource('customers', CustomerController::class)->except(['create', 'edit']);
        Route::resource('transactions', TransactionController::class)->except(['create', 'edit', 'update']);
    });

    Route::group(['middleware' => 'sanctum.abilities:admin'], function ()
    {
        Route::resource('users', UserController::class)->except(['create', 'edit']);

        Route::put('/setting', [SettingController::class, 'update']);
    });
});

Route::post('/login', [AuthController::class, 'login']);