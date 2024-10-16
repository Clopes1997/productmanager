<?php

namespace App\Http\Controllers;

use App\Models\Cidade;

class CidadeController extends Controller
{
    public function index()
    {
        $cidades = Cidade::all();
        return response()->json($cidades);
    }
}
