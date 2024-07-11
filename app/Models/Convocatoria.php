<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convocatoria extends Model
{
    use HasFactory, SoftDeletes;

     // Tabla
     protected $table = 'convocatorias'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idComision', 'idJunta', 'idTipo', 'lugar', 'fecha', 'hora', 'acta'];

     protected $casts = [
        'hora'  => 'datetime:H:i',
    ];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idComision');
    }

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoConvocatoria::class, 'idTipo');
    }

    public function scopeFilters(Builder $query, Request $request){
        return $query
           ->when($request->has('filtroJunta') && $request->filtroJunta!=null, function($builder) use ($request){
                return $builder->where('idJunta', $request->filtroJunta);       
            })->when($request->has('filtroEstado') && $request->filtroEstado!=null && $request->filtroEstado!=2, function($builder) use ($request){
                if($request->filtroEstado==0){
                    return $builder->whereNotNull('deleted_at');
                }
                elseif($request->filtroEstado==1){
                    return $builder->whereNull('deleted_at');
                }
            });
    }

}
