<?php
use Illuminate\Support\Facades\Route;


Route::get('/movies', [\App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [\App\Http\Controllers\MovieController::class, 'view'])->name('movies.view');
