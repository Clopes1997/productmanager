<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CidadeController;

Route::middleware('api')->group(function () {
    Route::get('/produtos', [ProdutoController::class, 'index']);
	Route::get('/produtos/stats', [ProdutoController::class, 'stats']);
	Route::get('/produtos/stats/filtered', [ProdutoController::class, 'statsFiltered']);
	Route::get('/produtos/filter', [ProdutoController::class, 'filterByValue']);
	Route::get('/produtos/filter/cidade', [ProdutoController::class, 'filterByCity']);
	Route::get('/produtos/filter/marca', [ProdutoController::class, 'filterByBrand']);
    Route::get('/produtos/{id}', [ProdutoController::class, 'show']);
    Route::post('/produtos', [ProdutoController::class, 'store']);
	Route::get('/marcas', [MarcaController::class, 'index']);
	Route::get('/cidades', [CidadeController::class, 'index']);
    Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);

});