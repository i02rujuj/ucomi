<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

     // Tabla
     protected $table = 'comisiones'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idJunta', 'nombre', 'descripcion', 'fechaConstitucion', 'fechaDisolucion', 'estado'];

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }
}
