<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Helpers\FirebaseHelper;
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
        if ($headOfDepartment && $headOfDepartment->id != $employee->id && !in_array($headOfDepartment->id, $supervisors)) {
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
       // FirebaseHelper::sendWithCurl('employee_'.$validated['employee_id'],"مهمة جديدة",$validated['title']);
       
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
      //  FirebaseHelper::sendWithCurl('employee_'.$task->employee_id,"مهمة جديدة",$task->title);
        return response()->json(new TaskResource($task));
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Task deleted']);
    }
    public function storeMultiple(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string',
        'employees_ids' => 'required|array',
        'employees_ids.*' => 'exists:employees,id',
        'validated' => 'required|integer',
        'priority' => 'required|integer',
        'status' => 'required|integer'
    ]);

    $tasks = [];

    foreach ($validated['employees_ids'] as $employee_id) {
        $employee = Employee::findOrFail($employee_id);

        // Récupérer le chef du département
        $headOfDepartment = Employee::where('department_id', $employee->department_id)
                                    ->where('account_type', 1)
                                    ->first();
    
        // Construire la liste des superviseurs
        $supervisors = [];
        if ($headOfDepartment) {
            $supervisors[] = $headOfDepartment->id;
        }

        // Créer la tâche
        $task = Task::create([
            'title' => $validated['title'],
            'employee_id' => $employee_id,
            'supervisors_ids' => json_encode($supervisors), // Stocker en JSON
            'validated' => $validated['validated'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
        ]);

        $tasks[] = $task;
    }

    return response()->json($tasks, 201);
}

        public function getNotValidateTasksByEmployee($employee_id)
    {
        // Récupère les tâches associées à l'employé donné
        $tasks = Task::where('employee_id', $employee_id)->where('validated',0)->orderBy('created_at','desc')->get();

        // Retourne les tâches sous forme de ressource

        return response()->json(TaskResource::collection($tasks)); 
    }
    public function getValidatedTasksByEmployee($employee_id)
    {
        // Récupère les tâches associées à l'employé donné
        $tasks = Task::where('employee_id', $employee_id)->where('validated',1)->orderBy('created_at','desc')->get();

        // Retourne les tâches sous forme de ressource

        return response()->json(TaskResource::collection($tasks)); 
    }
    public function getSupervisedTasks()
    {
        $user = Auth::user(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Rechercher les tâches où l'ID de l'utilisateur est dans supervisors_ids
        //$tasks = Task::whereJsonContains('supervisors_ids', (string) $user->id)->get();
        $tasks = Task::whereRaw("supervisors_ids::jsonb @> ?", [json_encode([$user->id])])->where('validated',0)->orderBy('created_at','desc')->get();
        return response()->json(TaskResource::collection($tasks), 200);
    }
}
