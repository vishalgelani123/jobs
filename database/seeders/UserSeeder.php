<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'  => 'Admin',
                'role'  => 'admin',
                'email' => 'admin@admin.com'
            ],
            [
                'name'  => 'employer',
                'role'  => 'employer',
                'email' => 'employer@employer.com'
            ],
            [
                'name'  => 'employer2',
                'role'  => 'employer',
                'email' => 'employer2@employer.com'
            ],
            [
                'name'  => 'candidate',
                'role'  => 'candidate',
                'email' => 'candidate@candidate.com'
            ],
            [
                'name'  => 'candidate2',
                'role'  => 'candidate',
                'email' => 'candidate2@candidate.com'
            ],
        ];

        foreach ($users as $user) {
            $saveUser = new User;
            $saveUser->name = $user['name'];
            $saveUser->mobile = '12345678900';
            $saveUser->email = $user['email'];
            $saveUser->role = $user['role'];
            $saveUser->email_verified_at = Carbon::now();
            $saveUser->remember_token = Str::random(10);
            $saveUser->password = Hash::make('123456');
            $saveUser->save();

            $saveUser->assignRole($user['role']);
        }
    }
}
