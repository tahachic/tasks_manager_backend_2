<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run()
    {
        Message::insert([
            [
                'id' => 15,
                'task_id' => 500,
                'text' => 'أنا كعبي أيمن',
                'type' => 0,
                'sender_id' => 200,
                'is_sent' => true,
                'is_seen' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'task_id' => 500,
                'text' => 'أنا شعبانة طه',
                'type' => 0,
                'sender_id' => 156,
                'is_sent' => false,
                'is_seen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
