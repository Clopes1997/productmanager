<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\UtilsController;

// Root health check route
Route::get('/', function () {
    return response()->json(['message' => 'API WORKS!'], 200);
});

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

    // Utility routes (support both GET and POST for easy testing)
    Route::match(['get', 'post'], '/utils/seed-test-data', [UtilsController::class, 'seedTestData']);
    Route::match(['get', 'post'], '/utils/clear-database', [UtilsController::class, 'clearDatabase']);

});