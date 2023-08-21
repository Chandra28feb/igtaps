<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;

Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('file/upload', [FileController::class, 'upload']);
Route::post('xml/file/upload', [FileController::class, 'xmlUpload']);
Route::get('user/lists', [FileController::class, 'userLists']);

Route::middleware('auth:sanctum')->group( function () {

    Route::post('logout', [AuthController::class,'logout']);

    Route::get('user/details',[AuthController::class,'userDetails']);

});