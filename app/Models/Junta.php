<?php

namespace App\Models;

use App\Models\Centro;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Junta extends Model
{
    use HasFactory;

     // Tabla
     protected $table = 'juntas'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idCentro','fechaConstitucion', 'fechaDisolucion', 'estado'];

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }
}
