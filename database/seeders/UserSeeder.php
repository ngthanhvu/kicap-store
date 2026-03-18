<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@cc.cc',
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ],
            [
                'email' => 'user1@example.com',
                'name' => 'Nguyen Van A',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ],
            [
                'email' => 'user2@example.com',
                'name' => 'Tran Thi B',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
