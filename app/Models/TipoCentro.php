<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCentro extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'tipos_centro'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre'];

    public function centros()
    {
        return $this->hasMany(Centro::class, 'idTipo');
    }
}
