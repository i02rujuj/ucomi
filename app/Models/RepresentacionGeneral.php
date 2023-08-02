<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepresentacionGeneral extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'representaciones_general'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre', 'estado'];

    public function miembrosJunta()
    {
        return $this->hasMany(MiembroJunta::class, 'idTipo');
    }
}
