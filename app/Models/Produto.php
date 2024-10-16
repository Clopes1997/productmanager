<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'valor', 'estoque', 'marca_id', 'cidade_id'];

    // Relacionamento com Marca
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    // Relacionamento com Cidade
    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
	
	public static function boot()
    {
        parent::boot();

        static::saving(function ($produto) {
            if ($produto->estoque < 0) {
                throw new \Exception('O estoque não pode ser negativo');
            }

            if ($produto->valor < 0) {
                throw new \Exception('O valor do produto não pode ser negativo');
            }
        });
    }
}
