<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentType::upsert([
            ['id' => 1, 'name' => 'CPF'],
            ['id' => 2, 'name' => 'CNPJ']
        ], 'id');
    }
}
