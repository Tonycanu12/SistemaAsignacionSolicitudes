<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RequestController;


//obtener usuarios
Route::get('/users', [UserController::class, 'getUsers']);


//registrar request
Route::post('/request', [RequestController::class, 'createRequest']);
