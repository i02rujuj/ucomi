<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de un tipo de convocatoria
 * 
 * @author Javier Ruiz Jurado
 */
class TipoConvocatoria extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'tipos_convocatoria'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['nombre'];
 
    /**
     * @brief Método que devuelve las convocatorias de un tipo de convocatoria
     * @return HasMany convocatorias
     */
     public function convocatorias()
     {
         return $this->hasMany(Convocatoria::class, 'idTipo');
     }
}
