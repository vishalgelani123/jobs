<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'user_id' => 4,
                'job_id' => 1,
                'status' => 'pending'
            ],
            [
                'user_id' => 5,
                'job_id' => 2,
                'status' => 'accepted'
            ],
            [
                'user_id' => 4,
                'job_id' => 2,
                'status' => 'rejected'
            ],

        ];
    }
}
