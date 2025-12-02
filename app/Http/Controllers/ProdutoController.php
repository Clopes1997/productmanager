<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
		$produtos = Produto::with(['marca', 'cidade'])->get();
        return response()->json($produtos);
    }

    public function show($id)
    {
        $produto = Produto::with(['marca', 'cidade'])->find($id);
        return $produto ? response()->json($produto) : response()->json(['message' => "Produto não encontrado com id: $id"], 404);
    }

	public function store(Request $request)
	{
    $validated = $request->validate([
        'nome' => 'required|string|max:255|unique:produtos',
        'valor' => 'required|numeric',
        'estoque' => 'required|integer|min:0',
        'marca_id' => 'required|exists:marcas,id',
        'cidade_id' => 'required|exists:cidades,id',
    ]);

    $produto = Produto::create($validated);
    return response()->json($produto, 201);
	}

    public function update(Request $request, $id)
    {
        $produto = Produto::find($id);
        if ($produto) {
            $produto->update($request->all());
            return response()->json($produto);
        }
        return response()->json(['message' => 'Produto não encontrado'], 404);
    }

	public function destroy($id)
	{
    $produto = Produto::find($id);
    if ($produto) {
        if ($produto->estoque > 0) {
            return response()->json(['message' => 'Não é possível excluir um produto com estoque.'], 400);
        }
        $produto->delete();
        return response()->json(['message' => 'Produto excluído']);
    }
    return response()->json(['message' => 'Produto não encontrado'], 404);
	}

	
	public function stats()
	{
    $produtos = Produto::all();

    $totalValor = $produtos->sum('valor');
    $mediaValor = $produtos->average('valor');

    return response()->json([
        'soma_total' => $totalValor,
        'media_valor' => $mediaValor,
    ]);
	}
	
	public function statsFiltered(Request $request)
	{
    // Obtém os parâmetros de entrada
    $min = $request->input('min');
    $max = $request->input('max');
    $cidade_id = $request->input('cidade_id'); // Recebe o ID da cidade
    $marca_id = $request->input('marca_id');   // Recebe o ID da marca

    // Inicia a consulta
    $query = Produto::query();

    // Aplica filtros de valor se fornecidos
    if (!is_null($min) || !is_null($max)) {
        $minValue = $min ?? 0;
        $maxValue = $max ?? PHP_FLOAT_MAX;
        $query->whereBetween('valor', [$minValue, $maxValue]);
    }

    // Filtra por cidade se o ID for fornecido
    if ($cidade_id) {
        $query->where('cidade_id', $cidade_id);
    }

    // Filtra por marca se o ID for fornecido
    if ($marca_id) {
        $query->where('marca_id', $marca_id);
    }

    // Obter todos os produtos filtrados
    $produtos = $query->with(['marca', 'cidade'])->get();

    // Calcula a soma e a média
    $soma_total = $produtos->sum('valor');
    $media_valor = $produtos->isEmpty() ? 0 : $produtos->avg('valor'); // Evita divisão por zero

    return response()->json([
        'soma_total' => (float)$soma_total,  // Converte para float
        'media_valor' => (float)$media_valor   // Converte para float
    ]);
	}

	public function filter(Request $request)
	{
    // Obtém os parâmetros de entrada
    $cidade_id = $request->input('cidade_id'); // ID da cidade
    $marca_id = $request->input('marca_id');   // ID da marca
    $min = $request->input('min') ?: 0;        // Valor mínimo
    $max = $request->input('max') ?: PHP_FLOAT_MAX; // Valor máximo

    // Inicia a consulta
    $query = Produto::query();

    // Filtra por cidade se o ID for fornecido
    if ($cidade_id) {
        $query->where('cidade_id', $cidade_id);
    }

    // Filtra por marca se o ID for fornecido
    if ($marca_id) {
        $query->where('marca_id', $marca_id);
    }

    // Filtra pelo intervalo de preço
    $query->whereBetween('valor', [$min, $max]);

    // Obtém os produtos filtrados
    $produtos = $query->with(['marca', 'cidade'])->get();

    return response()->json($produtos);
	}
	
	public function filterByValue(Request $request)
	{
    $min = $request->input('min', 0);
    $max = $request->input('max', PHP_INT_MAX);

    $produtos = Produto::with(['marca', 'cidade'])
        ->whereBetween('valor', [$min, $max])
        ->get();

    return response()->json($produtos);
	}
	
	public function filterByCity(Request $request)
	{
    $cidade_id = $request->input('cidade_id');
    $produtos = Produto::where('cidade_id', $cidade_id)->with(['marca', 'cidade'])->get();
    return response()->json($produtos);
	}
	
	public function filterByBrand(Request $request)
	{
    $marca_id = $request->input('marca_id');
    $produtos = Produto::where('marca_id', $marca_id)->with(['marca', 'cidade'])->get();
    return response()->json($produtos);
	}

}