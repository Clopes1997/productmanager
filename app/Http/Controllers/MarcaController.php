<?php

namespace App\Http\Controllers;

use App\Models\Marca;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::all();
        return response()->json($marcas);
    }
}
