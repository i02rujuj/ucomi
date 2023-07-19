<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'centros'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre','direccion', 'tipo', 'estado'];

    public function juntas()
    {
        return $this->hasMany(Junta::class, 'id');
    }
}
