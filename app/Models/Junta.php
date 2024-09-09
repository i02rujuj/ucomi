<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de una junta
 * 
 * @author Javier Ruiz Jurado
 */
class Junta extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'juntas'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idCentro','fechaConstitucion', 'fechaDisolucion', 'descripcion'];

    /**
     * @brief Método que devuelve la fecha de constitución formateada
     * @return string fechaConstitución
     */
    public function getFechaConstitucionFormatAttribute()
    {  
        return Carbon::parse($this->fechaConstitucion)->format('d-m-Y');
    }

    /**
     * @brief Método que devuelve la fecha de disolución formateada
     * @return string fechaDisolución
     */
    public function getFechaDisolucionFormatAttribute()
    {  
        return Carbon::parse($this->fechaDisolucion)->format('d-m-Y');
    }

    /**
     * @brief Método que devuelve el centro que pertenece una junta
     * @return BelongsTo centro
     */
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'idCentro');
    }

    /**
     * @brief Método que devuelve los miembros de una junta
     * @param representacion Opcional, si se indica se devolverán los miembros con la representación indicada
     * @return HasMany miembros
     */
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

    /**
     * @brief Método que devuelve las comisiones de una junta
     * @return HasMany comisiones
     */
    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idJunta');
    }

    /**
     * @brief Método que devuelve las convocatorias de una junta
     * @return HasMany convocatorias
     */
    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'idJunta');
    }

     /**
     * @brief Método que se encarga de filtrar los datos de una junta
     * @param Builder $query con la consulta inicial a filtrar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return Builder query de juntas filtrados
     */
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
