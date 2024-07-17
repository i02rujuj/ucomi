<?php

namespace App\Models;

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

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    public function miembros()
    {
        return $this->hasMany(MiembroComision::class, 'idComision');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idComision');
    }

    public function presidentes()
    {
        return $this->hasMany(MiembroComision::class, 'idComision')
            ->where('presidente', 1);
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
