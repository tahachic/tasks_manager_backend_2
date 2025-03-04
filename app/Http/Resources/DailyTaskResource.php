<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'employee_id' => $this->employee_id,
            'supervisor_id' => $this->supervisor_id,
            'employee_name' => $this->employee->name,
        ];
    }
}
