<?php

namespace App\Models;

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

     public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    public function miembros()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->orderBy('idRepresentacion');
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idJunta');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idJunta');
    }

    public function miembrosDIR()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.DIR'));
    }

    public function miembrosSECRE()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.SECRE'));
    }

    public function miembrosPDI_VP()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.PDI_VP'));
    }

    public function miembrosPDI()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.PDI'));
    }

    public function miembrosPAS()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.PAS'));
    }

    public function miembrosEST()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.EST'));
    }

    public function miembrosLIBRE()
    {
        return $this->hasMany(MiembroJunta::class, 'idJunta')
            ->where('idRepresentacion', config('constants.REPRESENTACIONES.JUNTA.LIBRE'));
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
