<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cidade;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create cities
        Cidade::create(['nome' => 'Cidade A']);
        Cidade::create(['nome' => 'Cidade B']);
        Cidade::create(['nome' => 'Cidade C']);
        Cidade::create(['nome' => 'Cidade D']);
    }
}
