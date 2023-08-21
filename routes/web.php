<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;


Route::get('/',[IndexController::class,'index']);
Route::get('file/add',[IndexController::class,'imageAdd'])->name('file.add');
Route::get('xml/add',[IndexController::class,'xmlAdd'])->name('xml.add');
