<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AccountsController::class)->group(function() {
    Route::get('/account', 'index')->name('index');
    Route::post('/account', 'store')->name('register');
    Route::get('/signIn', 'signInForm')->name('signInForm');
    Route::post('/signIn', 'signIn')->name('signIn');
    Route::get('/search', 'searchUser')->name('account.search');
    Route::post('/logout', 'logout')->name('account.logout');
    Route::get('/account/update', 'accountForm')->name('account.update.form');
    Route::patch('/account/update', 'update')->name('account.update');
    Route::post('/account/avatars', 'storeImage')->name('account.avatar');
});

Route::controller(MessagesController::class)->group(function() {
    // Route::get('/chat', 'index')->name('message.index');
    Route::post('/message', 'store')->name('message.store');
    Route::patch('/message', 'update')->name('message.update');
    Route::delete('/message', 'delete')->name('message.delete');
    // Route::get('/messages', 'getMessages')->name('message.messages');
});

Route::controller(ChatsController::class)->group(function() {
    Route::get('/', 'index')->name('chat.index');
    Route::post('/chat', 'store')->name('chat.store');
    Route::post('/chat/{chatName}', 'show')->name('chat.show');
});


