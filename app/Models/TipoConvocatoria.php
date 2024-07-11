<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoConvocatoria extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'tipos_convocatoria'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['nombre', 'estado'];
 
     public function convocatorias()
     {
         return $this->hasMany(Convocatoria::class, 'idTipo');
     }
}
