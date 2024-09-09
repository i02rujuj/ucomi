<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de un miembro de un centro
 * 
 * @author Javier Ruiz Jurado
 */
class MiembroGobierno extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'miembros_gobierno'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'reponsable'];

    /**
     * @brief Método que devuelve la fecha de toma de posesión formateada
     * @return string fechaTomaPosesión
     */
    public function getFechaTomaPosesionFormatAttribute()
    {  
        return Carbon::parse($this->fechaTomaPosesion)->format('d-m-Y');
    }

    /**
     * @brief Método que devuelve la fecha de cese formateada
     * @return string fechaCese
     */
    public function getFechaCeseFormatAttribute()
    {  
        return Carbon::parse($this->fechaCese)->format('d-m-Y');
    }
    
    /**
     * @brief Método que devuelve el centro que pertenece un miembro
     * @return BelongsTo centro
     */
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    /**
     * @brief Método que devuelve el usuario que corresponde a un miembro
     * @return BelongsTo usaurio
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    /**
     * @brief Método que devuelve la representación que representa un miembro
     * @return BelongsTo representación
     */
    public function representacion()
    {
        return $this->belongsTo(Representacion::class, 'idRepresentacion');
    } 

    /**
     * @brief Método que se encarga de filtrar los datos de un miembro de gobierno
     * @param Builder $query con la consulta inicial a filtrar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return Builder query de miembros filtrados
     */
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