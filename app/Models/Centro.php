<?php

namespace App\Models;

use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Centro extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'centros'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['nombre','direccion', 'idTipo', 'logo'];

    public function juntas()
    {
        return $this->hasMany(Junta::class, 'idCentro');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoCentro::class, 'idTipo');
    }

    public function miembros($representacion=null)
    {
        $miembros = $this->hasMany(MiembroGobierno::class, 'idCentro');

        if($representacion!=null){
            $miembros = $miembros->where('idRepresentacion', $representacion);
        }

        return $miembros
            ->orderBy('idRepresentacion')
            ->orderBy('fechaCese');
    }

    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroTipo') && $request->filtroTipo!=null, function($builder) use ($request){
                return $builder->where('idTipo', $request->filtroTipo);
            })->when($request->has('filtroNombre') && $request->filtroNombre!=null, function($builder) use ($request){
                return $builder
                    ->where(function($builder) use ($request){
                        $builder->whereRaw('LOWER(nombre) LIKE ? ', ['%'.trim(strtolower($request->filtroNombre)).'%'])
                        ->orWhereRaw('LOWER(direccion) LIKE ? ', ['%'.trim(strtolower($request->filtroNombre)).'%']);
                    });          
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
