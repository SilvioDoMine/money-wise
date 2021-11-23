<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // System seeders
        $this->call([
            DocumentTypeSeeder::class,
        ]);

        if (config('app.env') === 'production') {
            return;
        }

        // Testing seeders
        $this->call([
            UserSeeder::class,
        ]);
    }
}
