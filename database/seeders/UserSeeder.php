<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "amir",
            'email' => "amir@mail.com",
            'password' => bcrypt("password"),
        ]);

        DB::table('users')->insert([
            'name' => "adip",
            'email' => "adip@mail.com",
            'password' => bcrypt("password"),
        ]);
    }
}
