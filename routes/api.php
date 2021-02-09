<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/create', [TestController::class, 'create']);
Route::post('/allTests', [TestController::class, 'viewTests']);
Route::post('/delete', [TestController::class, 'delete']);
Route::post('/update', [TestController::class, 'update']);
Route::post('/purpose', [TestController::class, 'purpose']);

Route::group([
    'prefix' => '/question'

], function ($router) {
    Route::post('/create', [QuestionController::class, 'create']);
    Route::post('/view', [QuestionController::class, 'view']);
    Route::post('/delete', [QuestionController::class, 'delete']);
    Route::post('/update', [QuestionController::class, 'update']);
});

Route::group([
    'prefix' => '/student'

], function ($router) {
    Route::post('/tests', [StudentController::class, 'viewTest']);
    Route::post('/testQuestion', [StudentController::class, 'getTestQuestion']);
    Route::post('/testCheck', [StudentController::class, 'testQuestionCheck']);
});

