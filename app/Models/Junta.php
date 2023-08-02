<?php

namespace App\Models;

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

    public function miembrosJunta()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta');
    }

    public function miembrosGobierno()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta');
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idJunta');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idJunta');
    }

    public function directores()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'));
    }

    public function secretarios()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO'));
    }
}
