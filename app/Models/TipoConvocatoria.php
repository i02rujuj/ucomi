<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConvocatoria extends Model
{
    use HasFactory;

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
