<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comision extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'comisiones'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idJunta', 'nombre', 'fechaConstitucion', 'fechaDisolucion', 'descripcion'];

    public function getFechaConstitucionFormatAttribute()
    {  
        return Carbon::parse($this->fechaConstitucion)->format('d-m-Y');
    }

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    public function miembros($representacion=null)
    {
        $miembros = $this->hasMany(MiembroComision::class, 'idComision');

        if($representacion!=null){
            $miembros = $miembros->where('idRepresentacion', $representacion);
        }

        return $miembros
            ->orderBy('idRepresentacion')
            ->orderBy('fechaCese');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idComision');
    }

    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroCentro') && $request->filtroCentro!=null, function($builder) use ($request){
                return $builder
                ->whereHas('junta', function($query) use ($request){
                    $query->where('idCentro', $request->filtroCentro);
                });
            })->when($request->has('filtroJunta') && $request->filtroJunta!=null, function($builder) use ($request){
                return $builder->where('idJunta', $request->filtroJunta);       
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
