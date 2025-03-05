<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DailyTaskController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::apiResource('departments', DepartmentController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('daily_tasks', DailyTaskController::class);
Route::apiResource('messages', MessageController::class);


Route::get('/tasks/not_validated/employee/{employee_id}', [TaskController::class, 'getNotValidateTasksByEmployee']);
Route::get('/tasks/validated/employee/{employee_id}', [TaskController::class, 'getValidatedTasksByEmployee']);

Route::get('/daily-tasks/employee/{employee_id}', [DailyTaskController::class, 'getDailyTasksByEmployee'])->middleware('auth:sanctum');

Route::get('/employees/department/{department_id}', [EmployeeController::class, 'getEmployeesByDepartment'])->middleware('auth:sanctum');


Route::get('/messages/task/{task_id}', [MessageController::class, 'getMessagesByTask'])->middleware('auth:sanctum');


Route::post('/sign_in', [AuthController::class, 'signIn']);
Route::post('/sign_out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');

Route::get('/employee-report/{employee_id}', [ReportController::class, 'generateEmployeeReport']);

Route::put('seen/messages/tasks/{task_id}', [MessageController::class, 'markOtherMessagesAsSeen']);

Route::get('/heads-of-departments', [EmployeeController::class, 'getHeadsOfDepartments']);