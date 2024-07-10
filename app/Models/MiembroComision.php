<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiembroComision extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'miembros_comision'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idComision','idUsuario', 'fechaTomaPosesion', 'fechaCese', 'idRepresentacion', 'responsable'];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idComision');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function representacion()
    {
        return $this->belongsTo(RepresentacionGeneral::class, 'idRepresentacion');
    }
}
