<?php

namespace App\Models;

use App\Models\TipoCentro;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
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
    protected $fillable = ['nombre','direccion', 'idTipo', 'estado', 'logo'];

    public function juntas()
    {
        return $this->hasMany(Junta::class, 'idCentro');
    }

    public function miembros()
    {
        return $this->hasMany(MiembroGobierno::class, 'idCentro');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoCentro::class, 'idTipo');
    }

    public function scopeFilters(Builder $query, Request $request){
        return $query
            ->when($request->has('filtroTipo') && $request->filtroTipo!=null, function($query) use ($request){
                return $query->where('idTipo', $request->filtroTipo);
            })->when($request->has('filtroNombre'), function($query) use ($request){
                return $query
                    ->whereRaw('LOWER(nombre) LIKE ? ', ['%'.trim(strtolower($request->filtroNombre)).'%'])
                    ->orWhereRaw('LOWER(direccion) LIKE ? ', ['%'.trim(strtolower($request->filtroNombre)).'%']);
            });
    }
}
