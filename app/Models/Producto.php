<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['categoria_id', 'nombre', 'precio', 'stock', 'stock_minimo'];
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tieneStockBajo(): bool
    {
        return $this -> stock <= $this->stock_minimo;
    }
}
