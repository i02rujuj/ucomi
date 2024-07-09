<?php

namespace App\Models;

use App\Models\MiembroGobierno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepresentacionGobierno extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'representaciones_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre'];

    public function miembrosGobierno()
    {
        return $this->hasMany(MiembroGobierno::class, 'idTipo');
    }
}
