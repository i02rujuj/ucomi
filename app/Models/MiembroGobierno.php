<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiembroGobierno extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'miembros_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro', 'idUsuario', 'idJunta', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese'];

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    public function representacion()
    {
        return $this->belongsTo(RepresentacionGobierno::class, 'idRepresentacion');
    } 
}
