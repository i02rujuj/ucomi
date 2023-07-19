<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroGobierno extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'miembros_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro','idUsuario', 'fechaInicio', 'fechaFin', 'idRepresentacion', 'estado'];

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function representacion()
    {
        return $this->belongsTo(RepresentacionGobierno::class, 'idUsuario');
    }
}
