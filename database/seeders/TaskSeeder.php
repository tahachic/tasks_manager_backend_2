<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        Task::insert([
            [
                'id' => 115,
                'title' => 'إرسال الطابعة',
                'employee_id' => 200,
                'supervisors_ids' => json_encode([215]),
                'validated' => 0,
                'priority' => 1,
                'status' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6968,
                'title' => 'إرسال الطابعة',
                'employee_id' => 215,
                'supervisors_ids' => json_encode([215]),
                'validated' => 0,
                'priority' => 1,
                'status' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 858,
                'title' => 'إرسال الطابعة',
                'employee_id' => 215,
                'supervisors_ids' => json_encode([156]),
                'validated' => 0,
                'priority' => 1,
                'status' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 500,
                'title' => 'إرسال الطابعة',
                'employee_id' => 200,
                'supervisors_ids' => json_encode([156, 215]),
                'validated' => 0,
                'priority' => 1,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
