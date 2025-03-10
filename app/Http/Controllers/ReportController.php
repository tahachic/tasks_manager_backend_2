<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
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
    $dailyTasks = Task::where('employee_id', $employee_id)
                      ->whereDate('created_at', $today)
                      ->get();

    // Récupérer toutes les tâches de cet employé
    $allTasks = Task::where('employee_id', $employee_id)->get();
    // Générer un PDF avec ces données
    $pdf = PDF::loadView('reports.employee_report',  compact('employee', 'dailyTasks', 'allTasks'));
        //return $pdf->inline();
    return $pdf->download('employee_report_' . $employee_id . '_' . $today . '.pdf');
    $pdf = Pdf::loadView('reports.employee_report', compact('employee', 'dailyTasks', 'allTasks'));
    $pdf->setOption('defaultFont', 'Cairo');
    
    
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
public function generateMonthlyEmployeeReport($employee_id)
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
    $pdf = PDF::loadView('shared/facture_prof', compact('order'));
    //return $pdf->inline();
    return $pdf->download('Shahri_N'.$order->code.'.pdf');
   // return View('reports.employee_report', compact('employee', 'dailyTasks', 'allTasks'));
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