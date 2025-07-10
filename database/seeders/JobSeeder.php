<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'user_id'  => 2,
                'title'  => 'Senior Laravel Developer',
                'company' => 'Tech Corp',
                'salary' => 10000,
                'description' => 'Senior Laravel Developer',
                'location' => 'Surat',
            ],
            [
                'user_id'  => 3,
                'title'  => 'Frontend Developer',
                'company' => 'Web Solutions',
                'salary' => 20000,
                'description' => 'Frontend Developer',
                'location' => 'Bharuch',
            ],

        ];

        foreach ($jobs as $job) {
            $jobData = new Job();
            $jobData->user_id = $job['user_id'];
            $jobData->title = $job['title'];
            $jobData->company = $job['company'];
            $jobData->salary = $job['salary'];
            $jobData->description = $job['description'];
            $jobData->location = $job['location'];
            $jobData->save();
        }
    }
}
