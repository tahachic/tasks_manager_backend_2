<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'text' => $this->text,
            'type' => $this->type,
            'sender_id' => $this->sender_id,
            'sender_name' => $this->employee->name,
            'is_sent' => (bool) $this->is_sent,
            'is_seen' => (bool) $this->is_seen,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}