<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Produto;

class ProdutoTest extends TestCase
{
    // Teste de criação de produto com estoque negativo
    public function testProdutoCreationWithNegativeStock()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('O estoque não pode ser negativo');

        // Tenta criar um produto com estoque negativo
        Produto::create([
            'nome' => 'Produto Negativo',
            'valor' => 10.00,
            'estoque' => -5,
            'marca_id' => 1,
            'cidade_id' => 1
        ]);
    }

    // Teste de criação de produto com valor negativo
    public function testProdutoCreationWithNegativePrice()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('O valor do produto não pode ser negativo');

        // Tenta criar um produto com valor negativo
        Produto::create([
            'nome' => 'Produto Preço Negativo',
            'valor' => -10.00,
            'estoque' => 10,
            'marca_id' => 1,
            'cidade_id' => 1
        ]);
    }

    // Teste de criação de produto sem nome
    public function testProdutoCreationWithoutName()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Tenta criar um produto sem nome
        Produto::create([
            'valor' => 99.99,
            'estoque' => 10,
            'marca_id' => 1,
            'cidade_id' => 1
        ]);
    }
}
