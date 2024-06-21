<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DeletePhoneDataController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'show']);
Route::post('/receive-message', [SmsController::class, 'receiveMessage']);
Route::post('/vue-message', [SmsController::class, 'VueMessage']);
Route::post('/media-message', [SmsController::class, 'MediaMessage']);
Route::post('/get-message', [MessageController::class, 'getData']);
Route::get('/get-message', [MessageController::class, 'getPhoneNumbers']);
Route::post('/delete-phone', [DeletePhoneDataController::class, 'deletePhone']);
Route::post('/delete-message', [DeletePhoneDataController::class, 'deleteMessage']);
// Route::get('/twilio/webhook', [TwilioController::class, 'receiveMedia']);
