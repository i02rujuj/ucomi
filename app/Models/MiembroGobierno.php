<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiembroGobierno extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'miembros_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'reponsable'];

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function representacion()
    {
        return $this->belongsTo(Representacion::class, 'idRepresentacion');
    } 

    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroCentro') && $request->filtroCentro!=null, function($builder) use ($request){
                return $builder->where('idCentro', $request->filtroCentro);       
            })->when($request->has('filtroRepresentacion') && $request->filtroRepresentacion!=null, function($builder) use ($request){
                return $builder->where('idRepresentacion', $request->filtroRepresentacion);       
            })->when($request->has('filtroVigente') && $request->filtroVigente!=null, function($builder) use ($request){
                if($request->filtroVigente==1){
                    return $builder->whereNull('fechaCese');
                }
                elseif($request->filtroVigente==2){
                    return $builder->whereNotNull('fechaCese');
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