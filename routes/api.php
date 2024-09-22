<?php

use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/student', [StudentController::class, 'store']);
Route::get('/students', [StudentController::class, 'getStudents']);