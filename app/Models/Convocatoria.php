<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de una convocatortia
 * 
 * @author Javier Ruiz Jurado
 */
class Convocatoria extends Model
{
    use HasFactory, SoftDeletes;

    // Tabla
    protected $table = 'convocatorias'; 

    //Primary Key
    protected $primaryKey = 'id';
    
    //Campos
    protected $fillable = ['idComision', 'idJunta', 'idTipo', 'lugar', 'fecha', 'hora', 'acta'];

    /**
     * @brief Método que devuelve la fecha de convocatoria formateada
     * @return string fecha
     */
    public function getFechaFormatAttribute()
    {  
        return Carbon::parse($this->fecha)->format('d-m-Y');
    }

    protected $casts = [
        'hora'  => 'datetime:H:i',
    ];

    /**
     * @brief Método que devuelve la comisión que pertenece una convocatoria
     * @return BelongsTo comisión
     */
    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idComision');
    }

    /**
     * @brief Método que devuelve la junta que pertenece una convocatoria
     * @return BelongsTo junta
     */
    public function junta()
    {
        return $this->belongsTo(Junta::class, 'idJunta');
    }

    /**
     * @brief Método que devuelve el tipo de una convocatoria
     * @return BelongsTo tipo
     */
    public function tipo()
    {
        return $this->belongsTo(TipoConvocatoria::class, 'idTipo');
    }

    /**
     * @brief Método que devuelve los convocados de una convocatoria
     * @return HasMany convocados
     */
    public function convocados(){
        return $this->hasMany(Convocado::class, 'idConvocatoria');
    }

    /**
     * @brief Método que devuelve el primero de los convocados de una convocatoria
     * @return HasMany convocados
     */
    public function convocado($user){
        return $this->hasMany(Convocado::class, 'idConvocatoria')
        ->where('idUsuario', $user)
        ->first();
    }

    /**
     * @brief Método que se encarga de filtrar los datos de una convocatoria
     * @param Builder $query con la consulta inicial a filtrar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return Builder query de convocatorias filtradas
     */
    public function scopeFilters(Builder $query, Request $request){
        return $query
           ->when($request->has('filtroComision') && $request->filtroComision!=null, function($builder) use ($request){
                return $builder->where('idComision', $request->filtroComision);       
            })->when($request->has('filtroJunta') && $request->filtroJunta!=null, function($builder) use ($request){
                return $builder->where('idJunta', $request->filtroJunta);       
            })->when($request->has('filtroTipo') && $request->filtroTipo!=null, function($builder) use ($request){
                return $builder->where('idTipo', $request->filtroTipo); 
                      
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
