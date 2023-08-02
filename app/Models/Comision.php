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

    public function miembros()
    {
        return $this->hasMany(MiembroComision::class, 'idComision');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idComision');
    }
}
