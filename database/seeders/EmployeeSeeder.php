<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::insert([
            [
                'id' => 156,
                'department_id' => null,
                'name' => 'فرحاتي مراد',
                'password' => '123', 
                'account_type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 215,
                'department_id' => 15,
                'name' => 'طواهرية كريم',
                'password' => '123',
                'account_type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 200,
                'department_id' => 15,
                'name' => 'شعبانة طه',
                'password' => '123',
                'account_type' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 210,
                'department_id' => 16,
                'name' => 'غريب عيساوي',
                'password' =>'123',
                'account_type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
