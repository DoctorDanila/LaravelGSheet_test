<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;

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

Route::get('/', [RecordController::class, 'index']);
Route::post('/generate', [RecordController::class, 'generate'])->name('generate');
Route::post('/clear', [RecordController::class, 'clear'])->name('clear');
Route::get('/fetch/{count?}', [RecordController::class, 'fetch']);
Route::post('/sheet', [RecordController::class, 'updateSheet'])->name('sheet.update');
Route::post('/comment/{record}', [RecordController::class, 'saveComment'])->name('comment.save');
