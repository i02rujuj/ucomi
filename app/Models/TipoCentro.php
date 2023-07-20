<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCentro extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipos_centro'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre', 'estado'];

    public function centros()
    {
        return $this->hasMany(Centro::class, 'id');
    }
}
