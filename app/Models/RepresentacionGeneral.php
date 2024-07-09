<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepresentacionGeneral extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'representaciones_general'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre'];

    public function miembrosJunta()
    {
        return $this->hasMany(MiembroJunta::class, 'idTipo');
    }
}
