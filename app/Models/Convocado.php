<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convocado extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'convocados'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idConvocatoria','idUsuario', 'asiste', 'notificado'];
 
     public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class, 'idConvocatoria');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }
}
