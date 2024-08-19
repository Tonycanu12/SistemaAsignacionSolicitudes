<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\AssignmentsController;


//obtener usuarios
Route::get('/users', [UserController::class, 'getUsers']);


//registrar request
Route::post('/request', [RequestController::class, 'createRequest']);



//asignacion request
Route::post('/request/assign', [AssignmentsController::class, 'createAssignments']);

//listar Assignments
Route::get('/assignments', [AssignmentsController::class, 'getAllAsignments']);



