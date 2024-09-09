<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de una representación
 * 
 * @author Javier Ruiz Jurado
 */
class Representacion extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'representaciones'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre', 'tipoJunta', 'tipoComision'];

    /**
     * @brief Método que devuelve los miembros de junta de una representación
     * @return HasMany miembros
     */
    public function miembrosJunta()
    {
        return $this->hasMany(MiembroJunta::class, 'idTipo');
    }
}
