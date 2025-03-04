<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use Illuminate\Http\Request;
use App\Http\Resources\DailyTaskResource;

class DailyTaskController extends Controller
{
    public function index()
    {
        return response()->json(DailyTaskResource::collection(DailyTask::with('employee')->get()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'employee_id' => 'required|exists:employees,id',
            'supervisor_id' => 'required|exists:employees,id'
        ]);

        $dailyTask = DailyTask::create($request->all());
        return response()->json(new DailyTaskResource(DailyTask::with('employee')->findOrFail($dailyTask->id)), 201);
    }

    public function show($id)
    {
        return response()->json(new DailyTaskResource(DailyTask::with('employee')->findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $dailyTask = DailyTask::findOrFail($id);
        $dailyTask->update($request->all());
        return response()->json(new DailyTaskResource(DailyTask::with('employee')->findOrFail($dailyTask->id)));
    }

    public function destroy($id)
    {
        DailyTask::destroy($id);
        return response()->json(['message' => 'Daily Task deleted']);
    }

    public function getDailyTasksByEmployee($employee_id)
    {
        // Récupère les tâches quotidiennes de l'employé donné
        $dailyTasks = DailyTask::with('employee')->where('employee_id', $employee_id)->get();

        // Retourne les tâches sous forme de ressource
        return response()->json(DailyTaskResource::collection($dailyTasks));
    }
}
