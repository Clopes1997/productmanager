<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Marca;
use App\Models\Cidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class UtilsController extends Controller
{
    /**
     * Seed test data into all tables
     * Idempotent: clears existing data first, then seeds fresh data
     */
    public function seedTestData(): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Clear existing data in correct order (respect foreign keys)
            Produto::truncate();
            Marca::truncate();
            Cidade::truncate();

            // Seed Cidades
            $cidades = [
                ['nome' => 'São Paulo'],
                ['nome' => 'Rio de Janeiro'],
                ['nome' => 'Belo Horizonte'],
                ['nome' => 'Curitiba'],
                ['nome' => 'Porto Alegre'],
            ];
            foreach ($cidades as $cidade) {
                Cidade::create($cidade);
            }

            // Seed Marcas
            $marcas = [
                ['nome' => 'Marca Alpha', 'fabricante' => 'Fabricante Alpha'],
                ['nome' => 'Marca Beta', 'fabricante' => 'Fabricante Beta'],
                ['nome' => 'Marca Gamma', 'fabricante' => 'Fabricante Gamma'],
                ['nome' => 'Marca Delta', 'fabricante' => 'Fabricante Delta'],
            ];
            foreach ($marcas as $marca) {
                Marca::create($marca);
            }

            // Seed Produtos (using the seeded IDs)
            $produtos = [
                [
                    'nome' => 'Produto Teste 1',
                    'valor' => 99.99,
                    'estoque' => 10,
                    'marca_id' => 1,
                    'cidade_id' => 1,
                ],
                [
                    'nome' => 'Produto Teste 2',
                    'valor' => 149.50,
                    'estoque' => 5,
                    'marca_id' => 2,
                    'cidade_id' => 2,
                ],
                [
                    'nome' => 'Produto Teste 3',
                    'valor' => 79.99,
                    'estoque' => 15,
                    'marca_id' => 1,
                    'cidade_id' => 3,
                ],
                [
                    'nome' => 'Produto Teste 4',
                    'valor' => 199.99,
                    'estoque' => 8,
                    'marca_id' => 3,
                    'cidade_id' => 1,
                ],
            ];
            foreach ($produtos as $produto) {
                Produto::create($produto);
            }

            DB::commit();

            return response()->json([
                'message' => 'Test data seeded successfully',
                'cidades_count' => Cidade::count(),
                'marcas_count' => Marca::count(),
                'produtos_count' => Produto::count(),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error seeding test data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all data from all tables
     * Respects foreign key constraints by deleting in correct order
     */
    public function clearDatabase(): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Delete in order that respects foreign keys
            Produto::truncate();
            Marca::truncate();
            Cidade::truncate();

            DB::commit();

            return response()->json([
                'message' => 'Database cleared successfully',
                'cidades_count' => Cidade::count(),
                'marcas_count' => Marca::count(),
                'produtos_count' => Produto::count(),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error clearing database',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

