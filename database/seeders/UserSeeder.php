<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::destroy([1, 2, 3]);

        User::factory()->withCnpj()->create(['id' => 1]);
        User::factory()->withCpf()->create(['id' => 2]);
        User::factory()->withCpf()->create(['id' => 3]);
    }
}
