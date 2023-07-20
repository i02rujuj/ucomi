<?php

namespace App\Models;

use App\Models\TipoCentro;
use App\Models\MiembroGobierno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Centro extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'centros'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre','direccion', 'idTipo', 'estado'];

    public function juntas()
    {
        return $this->hasMany(Junta::class, 'id');
    }

    public function miembrosGobierno()
    {
        return $this->hasMany(MiembroGobierno::class, 'id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoCentro::class, 'idTipo');
    }
}
