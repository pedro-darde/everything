<?php

use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Route;
Route::post('/validator', [ValidatorController::class, 'postCreate'])->name('validator.postCreate');
Route::get('/validator', [ValidatorController::class, 'index'])->name('validator.index');
Route::get('/validator/create', [ValidatorController::class, 'create'])->name('validator.create');
Route::get('/validator/createByJson', [ValidatorController::class, 'createByJson'])->name('validator.create-by-json');
Route::get('/validator/loadMore', [ValidatorController::class, 'loadMore'])->name('validator.loadMore');
Route::get('/validator/{id}', [ValidatorController::class, 'edit'])->name('validator.edit');
Route::put('/validator/{id}', [ValidatorController::class, 'postEdit'])->name('validator.postEdit');
Route::delete('/validator/{id}', [ValidatorController::class, 'delete'])->name('validator.delete');
Route::get('/validator/import/csv', [ValidatorController::class, 'import'])->name('validator.import');
Route::post('/validator/extractor/csv', [ValidatorController::class, 'identifyTemplates'])->name('validator.extract');
Route::post('/validator/extractor/json', [ValidatorController::class, 'identifyJsonData'])->name('validator.identify-json');
Route::post('/validator/createByJson', [ValidatorController::class, 'postCreateByJson'])->name('validator.identify-json');
