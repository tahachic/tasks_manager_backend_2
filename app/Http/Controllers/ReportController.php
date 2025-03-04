<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generateEmployeeReport($employee_id)
{
    // Vérifier si l'employé existe
    $employee = Employee::find($employee_id);
    if (!$employee) {
        return response()->json(['error' => 'Employé non trouvé'], 404);
    }

    // Date du jour
    $today = Carbon::today()->toDateString();

    // Récupérer les tâches du jour pour cet employé
    $dailyTasks = Task::where('employee_id', $employee_id)
                      ->whereDate('created_at', $today)
                      ->get();

    // Récupérer toutes les tâches de cet employé
    $allTasks = Task::where('employee_id', $employee_id)->get();
    // Générer un PDF avec ces données
    $pdf = Pdf::loadView('reports.employee_report', compact('employee', 'dailyTasks', 'allTasks'));
    $pdf->setOption('defaultFont', 'Amiri');
    
    
    //$pdf = Pdf::loadView('reports.employee_report', compact('employee', 'dailyTasks', 'allTasks'));

    // Sauvegarder le PDF dans storage/app/public/reports
    $fileName = 'employee_report_' . $employee_id . '_' . $today . '.pdf';
    Storage::disk('public')->put('reports/' . $fileName, $pdf->output());

    // Retourner l'URL du fichier généré
    return response()->json([
        'message' => 'Rapport généré avec succès',
        'download_url' => asset('storage/reports/' . $fileName)
    ]);
}

}