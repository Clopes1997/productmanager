<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Marca::create(['nome' => 'Marca A', 'fabricante' => 'Fabricante A']);
        Marca::create(['nome' => 'Marca B', 'fabricante' => 'Fabricante B']);
    }
}