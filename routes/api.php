<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(CourseController::class)->group(function(){
    Route::get('/courses', 'index');
    Route::post('/course', 'store');
    Route::get('/course/{course}', 'show');
    Route::put('/course/{course}', 'update');
    Route::delete('/course/{course}', 'destroy');
    Route::post('/course/{course}/student/{student}', 'addStudent');
    Route::delete('/student/{student}/course/{course}', 'removeStudent');
    Route::get('/student/{student}/courses', 'getCourseByStudent');
    Route::get('/students/courses', 'getStudentsCourses');
    Route::get('');
});

Route::controller(UserController::class)->group(function(){
    Route::get('/students', 'index');
    Route::post('/student', 'store');
    Route::get('/student/{student}', 'show');
    Route::put('/student/{student}', 'update');
    Route::delete('/student/{student}', 'destroy');
});

