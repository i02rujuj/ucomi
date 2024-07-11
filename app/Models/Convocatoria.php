<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convocatoria extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'convocatorias'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idComision', 'idJunta', 'idTipo', 'lugar', 'fecha', 'hora', 'acta'];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idComision');
    }

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoConvocatoria::class, 'idTipo');
    }

}
