<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{


    // 'name',
    // 'email',
    // 'password',
    // "role"
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "laylonghav",
                "email" => "laylonghav@gmail.com",
                "password" => Hash::make("laylonghav"),
                "role" => "user",
            ],
            [
                "name" => "longhav",
                "email" => "longhav@gmail.com",
                "password" => Hash::make("longhav"),
                "role" => "admin",
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
