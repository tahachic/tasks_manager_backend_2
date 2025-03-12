<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\DailyTask;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;
class ReportController extends Controller
{
    public function generateDailyEmployeeReport($employee_id)
{
    // Vérifier si l'employé existe
    $employee = Employee::find($employee_id);
    if (!$employee) {
        return response()->json(['error' => 'Employé non trouvé'], 404);
    }

    // Date du jour
    $today = Carbon::today()->toDateString();

    // Récupérer les tâches du jour pour cet employé
    $dailyTasks = DailyTask::where('employee_id', $employee_id)
                      
                      ->get();

    // Récupérer toutes les tâches de cet employé
    $tasks = Task::where('employee_id', $employee_id)->whereDate('created_at', $today)->get();
    //return View('reports.employee_report', compact('employee', 'dailyTasks', 'tasks'));
    // Générer un PDF avec ces données
    $pdf = PDF::loadView('reports.employee_report', compact('employee', 'dailyTasks', 'tasks'));
   
    // Sauvegarder le PDF dans storage/app/public/reports
    $fileName = 'employee_report_' . $employee_id . '_' . $today . '.pdf';
    Storage::disk('public')->put('reports/' . $fileName, $pdf->output());

    // Retourner l'URL du fichier généré
    return response()->json([
        'message' => 'Rapport généré avec succès',
        'download_url' => asset('storage/reports/' . $fileName)
    ]);
}
public function generateMonthlyEmployeeReport($employee_id)
{
    $employee = Employee::find($employee_id);
    if (!$employee) {
        return response()->json(['error' => 'Employé non trouvé'], 404);
    }

    // Obtenir le mois et l'année actuels
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    // Récupérer les tâches du jour pour cet employé
    $dailyTasks = DailyTask::where('employee_id', $employee_id)->get();


    // Récupérer les tâches de chaque jour du mois pour cet employé
    $tasksByDay = Task::where('employee_id', $employee_id)
                      ->whereYear('created_at', $currentYear)
                      ->whereMonth('created_at', $currentMonth)
                      ->orderBy('created_at')
                      ->get()
                      ->groupBy(function ($task) {
                          return Carbon::parse($task->created_at)->format('Y-m-d');
                      });
    //return View('reports.employee_monthly_report', compact('employee','dailyTasks', 'tasksByDay'));
    // Générer le PDF avec les tâches de chaque jour sur une nouvelle page
    $pdf = PDF::loadView('reports.employee_monthly_report', compact('employee','dailyTasks', 'tasksByDay'))->setOptions([
        'enable-local-file-access' => true,
    ]);

    // Sauvegarder le PDF dans storage/app/public/reports
    $fileName = 'employee_report_' . $employee_id . '_' . $currentMonth . '.pdf';
    Storage::disk('public')->put('reports/' . $fileName, $pdf->output());

    // Retourner l'URL du fichier généré
    return response()->json([
        'message' => 'Rapport généré avec succès',
        'download_url' => asset('storage/reports/' . $fileName)
    ]);
}
}