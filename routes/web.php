<?php

use App\Events\WebsocketDemoEvent;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    broadcast(new WebsocketDemoEvent('Some Data'));
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/chat',[App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
Route::get('/messages',[App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
Route::post('/messages',[App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');