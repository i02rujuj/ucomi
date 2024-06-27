<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'estado'
 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function miembrosGobierno(){
        return $this->hasMany(MiembroGobierno::class, 'id');
    }

    public function getRoleName(){

        $res = '';
        $roles = $this->getRoleNames();

        foreach($roles as $rol){
            $res .= $rol;
        }

        return $res;
    }

    public function getRoleNameType(){
        $roles = $this->getRoleNames();

        foreach($roles as $rol){

            $res = '';

            switch($rol){

                case 'admin':
                    $res =  'Todos los permisos';
                    break;

                case 'responsable_centro':

                    $centros = MiembroGobierno::where('miembros_gobierno.idUsuario', $this->id)
                    ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                    ->join('centros', 'miembros_gobierno.idCentro', '=', 'centros.id')
                    ->where('centros.estado', 1)
                    ->select('centros.id', 'centros.nombre')
                    ->get();

                    foreach($centros as $c){
                        $res .=  $c->nombre;
                    }
                    
                    break;

                case 'responsable_junta':

                    $juntas = MiembroJunta::where('miembros_junta.idUsuario', $this->id)
                    ->join('users', 'miembros_junta.idUsuario', '=', 'users.id')
                    ->join('juntas', 'juntas.id', '=', 'miembros_junta.idJunta')
                    ->join('centros', 'centros.id', '=', 'juntas.idCentro')
                    ->where('miembros_junta.estado', 1)
                    ->where('juntas.estado', 1)
                    ->select('juntas.id', 'centros.nombre', 'juntas.fechaConstitucion')
                    ->get();

                    foreach($juntas as $j){
                        $res .=  $j->nombre.' ('.$j->fechaConstitucion.')';
                    }

                    break;

                case 'responsable_comision':

                     $comisiones = MiembroComision::where('miembros_comision.idUsuario', $this->id)
                    ->join('users', 'miembros_comision.idUsuario', '=', 'users.id')
                    ->join('comisiones', 'comisiones.id', '=', 'miembros_comision.idComision')
                    ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                    ->join('centros', 'centros.id', '=', 'juntas.idCentro')
                    ->where('miembros_comision.estado', 1)
                    ->where('juntas.estado', 1)
                    ->select('comisiones.nombre as nombre_comision', 'centros.nombre as nombre_centro', 'juntas.fechaConstitucion')
                    ->get();

                    foreach($comisiones as $c){
                        $res .=  $c->nombre_comision.' | '.$c->nombre_centro.' ('.$c->fechaConstitucion.')';
                    }

                    break;
            }
        }

        return $res;
    }
}
