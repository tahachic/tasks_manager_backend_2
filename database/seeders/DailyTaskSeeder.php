<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTask;

class DailyTaskSeeder extends Seeder
{
    public function run()
    {
        DailyTask::insert([
            [
                'id' => 115,
                'title' => 'إرسال الطابعة',
                'employee_id' => 200,
                'supervisor_id' => 215,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 116,
                'title' => 'إرسال الطابعة',
                'employee_id' => 215,
                'supervisor_id' => 156,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
