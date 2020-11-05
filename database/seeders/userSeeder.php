<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use illuminate\Database\Console\Factories;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(30)->create();
    }
}
