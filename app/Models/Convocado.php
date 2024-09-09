<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de un convocado
 * 
 * @author Javier Ruiz Jurado
 */
class Convocado extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'convocados'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idConvocatoria','idUsuario', 'asiste', 'notificado'];
 
     /**
     * @brief Método que devuelve la convocatoria de un convocado
     * @return BelongsTo convocatoria
     */
    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class, 'idConvocatoria');
    }

     /**
     * @brief Método que devuelve el usuario que pertenece un convocado
     * @return BelongsTo usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }
}
