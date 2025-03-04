<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
class TaskController extends Controller
{
    public function index()
    {
        return response()->json(TaskResource::collection(Task::all())); 
    }

    public function store(Request $request)
    {
        

        $validated = $request->validate([
            'title' => 'required|string',
            'employee_id' => 'required|exists:employees,id',
            'supervisors_ids' => 'required|array',
            'validated' => 'required|integer',
            'priority' => 'required|integer',
            'status' => 'required|integer'
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        // Récupérer le chef du département (premier employé avec account_type = 1)
        $headOfDepartment = Employee::where('department_id', $employee->department_id)
                                    ->where('account_type', 1)
                                    ->first();
    
        // Construire la liste des superviseurs
        $supervisors = $validated['supervisors_ids'] ?? [];
        if ($headOfDepartment && !in_array($headOfDepartment->id, $supervisors)) {
            $supervisors[] = $headOfDepartment->id;
        }
    
        $task = Task::create([
            'title' => $validated['title'],
            'employee_id' => $validated['employee_id'],
            'supervisors_ids' => $supervisors,
            'validated' => $validated['validated'] ?? 0,
            'priority' => $validated['priority'] ?? 0,
            'status' => $validated['status'] ?? 0,
        ]);
       
        // $task = Task::create($request->all());
        return response()->json(new TaskResource($task), 201);
    }

    public function show($id)
    {
        return response()->json(new TaskResource(Task::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return response()->json(new TaskResource($task));
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Task deleted']);
    }

        public function getTasksByEmployee($employee_id)
    {
        // Récupère les tâches associées à l'employé donné
        $tasks = Task::where('employee_id', $employee_id)->get();

        // Retourne les tâches sous forme de ressource

        return response()->json(TaskResource::collection($tasks)); 
    }
}
