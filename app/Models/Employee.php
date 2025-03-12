<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = ['name', 'password', 'department_id', 'account_type'];

    //protected $hidden = ['password'];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}