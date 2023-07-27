<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroJunta extends Model
{
    use HasFactory;

     // Tabla
     protected $table = 'miembros_junta'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idJunta','idUsuario', 'fechaTomaPosesion', 'fechaCese', 'idRepresentacion', 'estado'];
 
     public function junta()
     {
         return $this->belongsTo(Junta::class, 'idJunta');
     }
 
     public function usuario()
     {
         return $this->belongsTo(User::class, 'idUsuario');
     }
 
     public function representacion()
     {
         return $this->belongsTo(RepresentacionGeneral::class, 'idRepresentacion');
     }
}
