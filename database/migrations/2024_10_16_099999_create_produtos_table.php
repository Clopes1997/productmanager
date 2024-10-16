<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
	public function up()
	{
		Schema::create('produtos', function (Blueprint $table) {
			$table->id();
			$table->string('nome');
			$table->float('valor');
			$table->unsignedBigInteger('marca_id'); // Define 'marca_id' uma vez
			$table->float('estoque');
			$table->unsignedBigInteger('cidade_id');
			$table->timestamps();

        // Chaves estrangeiras
			$table->foreign('marca_id')->references('id')->on('marcas');
			$table->foreign('cidade_id')->references('id')->on('cidades');
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
