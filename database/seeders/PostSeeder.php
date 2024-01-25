<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert([
            'caption' => "Post " . rand(1, 100),
            'photo' => "link gambar",
            'user_id' => rand(1, 2),
            'created_at' => Carbon::now()
        ]);

        DB::table('posts')->insert([
            'caption' => "Post " . rand(1, 100),
            'photo' => "link gambar",
            'user_id' => rand(1, 2),
            'created_at' => Carbon::now()
        ]);

        DB::table('posts')->insert([
            'caption' => "Post " . rand(1, 100),
            'photo' => "link gambar",
            'user_id' => rand(1, 2),
            'created_at' => Carbon::now()
        ]);

        DB::table('posts')->insert([
            'caption' => "Post " . rand(1, 100),
            'photo' => "link gambar",
            'user_id' => rand(1, 2),
            'created_at' => Carbon::now()
        ]);
    }
}
