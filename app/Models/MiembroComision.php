<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiembroComision extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'miembros_comision'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idComision','idUsuario', 'fechaTomaPosesion', 'fechaCese', 'idRepresentacion', 'responsable', 'presidente'];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idComision');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function representacion()
    {
        return $this->belongsTo(RepresentacionGeneral::class, 'idRepresentacion');
    }
 
    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroCentro') && $request->filtroCentro!=null, function($builder) use ($request){
                return $builder
                ->whereHas('comision', function($builder) use ($request){
                    return $builder
                    ->whereHas('junta', function($builder) use ($request){
                        $builder->where('idCentro', $request->filtroCentro);
                    });
                }); 
            })->when($request->has('filtroJunta') && $request->filtroJunta!=null, function($builder) use ($request){
                return $builder
                ->whereHas('comision', function($query) use ($request){
                    $query->where('idJunta', $request->filtroJunta);
                });       
            })->when($request->has('filtroComision') && $request->filtroComision!=null, function($builder) use ($request){
                return $builder
                ->where('idComision', $request->filtroComision);
            })->when($request->has('filtroRepresentacion') && $request->filtroRepresentacion!=null, function($builder) use ($request){
                return $builder->where('idRepresentacion', $request->filtroRepresentacion);       
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
