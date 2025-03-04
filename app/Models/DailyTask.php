<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTask extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $fillable = ['title', 'employee_id', 'supervisor_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}