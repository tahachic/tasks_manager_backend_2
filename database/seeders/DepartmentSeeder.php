<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        Department::insert([
            [
                'id' => 15,
                'name' => 'قسم الإعلام الآلي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'name' => 'قسم المحاسبة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
