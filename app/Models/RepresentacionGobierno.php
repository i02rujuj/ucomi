<?php

namespace App\Models;

use App\Models\MiembroGobierno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepresentacionGobierno extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'representaciones_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre', 'estado'];

    public function miembrosGobierno()
    {
        return $this->hasMany(MiembroGobierno::class, 'id');
    }
}
