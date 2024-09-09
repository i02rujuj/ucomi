<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de un tipo de centro
 * 
 * @author Javier Ruiz Jurado
 */
class TipoCentro extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'tipos_centro'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre'];

    /**
     * @brief Método que devuelve los centros de un tipo de centro
     * @return HasMany centros
     */
    public function centros()
    {
        return $this->hasMany(Centro::class, 'idTipo');
    }
}
