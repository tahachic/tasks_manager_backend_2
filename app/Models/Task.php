<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $fillable = ['title', 'employee_id', 'supervisors_ids', 'validated', 'priority', 'status','validated_at'];
    protected $casts = [
        'supervisors_ids' => 'array', // ✅ Convertit JSON <-> Tableau PHP
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    
}