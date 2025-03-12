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
use App\Http\Controllers\NotificationController;
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


Route::middleware('auth:sanctum')->post('/store-token', [NotificationController::class, 'storeToken']);

Route::apiResource('departments', DepartmentController::class)->middleware('auth:sanctum');
Route::apiResource('employees', EmployeeController::class)->middleware('auth:sanctum');
Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');
Route::apiResource('daily_tasks', DailyTaskController::class)->middleware('auth:sanctum');
Route::apiResource('messages', MessageController::class);


Route::get('/tasks/not_validated/employee/{employee_id}', [TaskController::class, 'getNotValidateTasksByEmployee'])->middleware('auth:sanctum');
Route::get('/tasks/validated/employee/{employee_id}', [TaskController::class, 'getValidatedTasksByEmployee'])->middleware('auth:sanctum');

Route::get('/daily-tasks/employee/{employee_id}', [DailyTaskController::class, 'getDailyTasksByEmployee'])->middleware('auth:sanctum');

Route::get('/employees/department/{department_id}', [EmployeeController::class, 'getEmployeesByDepartment'])->middleware('auth:sanctum');


Route::get('/messages/task/{task_id}', [MessageController::class, 'getMessagesByTask'])->middleware('auth:sanctum');


Route::post('/sign_in', [AuthController::class, 'signIn']);
Route::post('/sign_out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');

Route::get('/employee-report/daily/{employee_id}', [ReportController::class, 'generateDailyEmployeeReport']);
Route::get('/employee-report/monthly/{employee_id}', [ReportController::class, 'generateDailyEmployeeReport']);

Route::put('seen/messages/tasks/{task_id}', [MessageController::class, 'markOtherMessagesAsSeen'])->middleware('auth:sanctum');

Route::get('/heads-of-departments', [EmployeeController::class, 'getHeadsOfDepartments'])->middleware('auth:sanctum');

Route::get('/supervised_tasks', [TaskController::class, 'getSupervisedTasks'])->middleware('auth:sanctum');

Route::post('/send-notification/employee/{id}', [NotificationController::class, 'sendNotificationToEmployee']);
Route::post('/send-notification/department/{id}', [NotificationController::class, 'sendNotificationToDepartment']);
Route::post('/send-notification/employees', [NotificationController::class, 'sendNotificationToAll']);

Route::get('/report/{employee_id}', [ReportController::class, 'generateMonthlyEmployeeReport']);