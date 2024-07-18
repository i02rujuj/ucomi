<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Junta extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'juntas'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro','fechaConstitucion', 'fechaDisolucion', 'descripcion'];

    public function getFechaConstitucionFormatAttribute()
    {  
        return Carbon::parse($this->fechaConstitucion)->format('d-m-Y');
    }

    public function getFechaDisolucionFormatAttribute()
    {  
        return Carbon::parse($this->fechaDisolucion)->format('d-m-Y');
    }

     public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function miembros($representacion=null)
    {
        $miembros = $this->hasMany(MiembroJunta::class, 'idJunta');

        if($representacion!=null){
            $miembros = $miembros->where('idRepresentacion', $representacion);
        }

        return $miembros
            ->orderBy('idRepresentacion')
            ->orderBy('fechaCese');
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idJunta');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idJunta');
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
