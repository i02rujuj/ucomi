<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Junta extends Model
{
    use HasFactory;

     // Tabla
     protected $table = 'juntas'; 

     //Primary Key
     protected $primaryKey = 'id';
     
     //Campos
     protected $fillable = ['idCentro','fechaConstitucion', 'fechaDisolucion', 'estado'];

     public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function miembrosJunta()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta');
    }

    public function miembrosGobierno()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta');
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idJunta');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idJunta');
    }

    public function directores()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'));
    }

    public function secretarios()
    {
        return $this->hasMany(MiembroGobierno::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO'));
    }

    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroCentro') && $request->filtroCentro!=null, function($builder) use ($request){
                return $builder->where('idCentro', $request->filtroCentro);       
            })->when($request->has('filtroVigente') && $request->filtroVigente!=null, function($builder) use ($request){
                if($request->filtroVigente==1){
                    return $builder->whereNull('fechaDisolucion');
                }
                elseif($request->filtroVigente==2){
                    return $builder->whereNotNull('fechaDisolucion');
                }
                return $builder->where('fechaDisolucion', null);
            })->when($request->has('filtroEstado') && $request->filtroEstado!=null && $request->filtroEstado!=2, function($builder) use ($request){
                return $builder->where('estado', $request->filtroEstado);
            });
    }
}
