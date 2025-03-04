<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $fillable = ['task_id', 'sender_id', 'text', 'type', 'is_sent', 'is_seen'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'sender_id');
    }
}