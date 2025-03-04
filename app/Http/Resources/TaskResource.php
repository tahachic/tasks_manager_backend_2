<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Employee;
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'supervisor' => $this->getFirstSupervisorName(),
            'employee_id' => $this->employee_id,
            'supervisors_ids' => $this->supervisors_ids,
            'validated' => $this->validated,
            'priority' => $this->priority,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
    private function getFirstSupervisorName()
    {
        // Vérifie s'il y a des superviseurs et récupère le premier
        $firstSupervisorId = $this->supervisors_ids[0] ?? null;
        if ($firstSupervisorId) {
            return Employee::where('id', $firstSupervisorId)->value('name'); // ✅ Récupère le nom
        }
        return null; // Si aucun superviseur, retourne `null`
    }
}