<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produto::create([
            'nome' => 'Produto A',
            'valor' => 99.99, // Provide a value for the product
            'estoque' => 10, // Provide a stock quantity
            'marca_id' => 1, // Use a valid marca_id from the marcas table
            'cidade_id' => 1 // Use a valid cidade_id from the cidades table
        ]);

        Produto::create([
            'nome' => 'Produto B',
            'valor' => 49.99, // Provide a value for the product
            'estoque' => 5, // Provide a stock quantity
            'marca_id' => 2, // Use a valid marca_id from the marcas table
            'cidade_id' => 1 // Use a valid cidade_id from the cidades table
        ]);
    }
}
