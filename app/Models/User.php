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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @brief Clase que contiene los datos que hacen referencia al modelo de un usuario
 * 
 * @author Javier Ruiz Jurado
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
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

    /**
     * @brief Método que devuelve los miembros de gobierno con respecto a un usuario
     * @return HasMany miembros
     */
    public function miembrosGobierno(){
        return $this->hasMany(MiembroGobierno::class, 'idUsuario');
    }

    /**
     * @brief Método que devuelve los miembros de junta con respecto a un usuario
     * @return HasMany miembros
     */
    public function miembrosJunta(){
        return $this->hasMany(MiembroJunta::class, 'idUsuario');
    }

    /**
     * @brief Método que devuelve los miembros de comisión con respecto a un usuario
     * @return HasMany miembros
     */
    public function miembrosComision(){
        return $this->hasMany(MiembroComision::class, 'idUsuario');
    }

    /**
     * @brief Método que devuelve los convocados con respecto a un usuario
     * @return HasMany convocados
     */
    public function convocados(){
        return $this->hasMany(Convocado::class, 'idUsuario');
    }

    /**
     * @brief Método que devuelve los datos a los que tendría acceso un responsable de centro, junta o comisión
     * @param lista lista de los datos que queremos obtener
     * @return resultado datos con acceso de responsable
     */
    public function esResponsableDatos($lista){

        $resultado=[
            'centros' => [],
            'juntas' => [],
            'comisiones' => [],
        ];

        $lista = explode("|", $lista);

        foreach ($lista as $r) {

            switch($r){
                case 'centro':
                    foreach ($this->miembrosGobierno as $miembro) {
                        if($miembro->responsable==1){
                            $resultado['centros']['idCentros'][]=$miembro->idCentro;
                        }
                    }              
                    break;
                case 'junta':
                    foreach ($this->miembrosJunta as $miembro) {
                        if($miembro->responsable==1){
                            $resultado['juntas']['idJuntas'][]=$miembro->idJunta;
                            $resultado['juntas']['idCentros'][]=$miembro->junta->idCentro;
                        }
                    }  
                    break;
                case 'comision':
                    foreach ($this->miembrosComision as $miembro) {
                        if($miembro->responsable==1){
                            $resultado['comisiones']['idComisiones'][]=$miembro->idComision;
                            $resultado['comisiones']['idJuntas'][]=$miembro->comision->idJunta;
                        }
                    }  
                    break;
            }
        }

        return $resultado;
    }

    /**
     * @brief Método que devuelve si un usuario es responsable de centro, junta o comisión
     * @param lista lista de los datos que queremos obtener
     * @return booleano si es responsable o no
     */
    public function esResponsable($lista){

        $lista = explode("|", $lista);

        foreach ($lista as $t) {

            switch($t){
                case 'admin':
                    if($this->hasRole('admin')){
                        return true;
                    }
                    break;
                case 'centro':
                    foreach ($this->miembrosGobierno as $miembro) {
                        if($miembro->responsable==1){
                            return true;
                        }
                    }               
                    break;
                case 'junta':
                    foreach ($this->miembrosJunta as $miembro) {
                        if($miembro->responsable==1){
                            return true;
                        }
                    }   
                    break;
                case 'comision':
                    foreach ($this->miembrosComision as $miembro) {
                        if($miembro->responsable==1){
                            return true;
                        }
                    }     
                    break;
            }
        }

        return false;
    }

    /**
     * @brief Método que devuelve si un usuario es miembro de centro, junta o comisión
     * @param lista lista de los datos que queremos obtener
     * @return booleano si es miembro o no
     */
    public function esMiembro($lista){

        $lista = explode("|", $lista);

        foreach ($lista as $t) {

            switch($t){
                case 'admin':
                    if($this->hasRole('admin')){
                        return true;
                    }
                    break;
                case 'centro':
                    if($this->miembrosGobierno->count()>0) {
                        return true;
                    }              
                    break;
                case 'junta':
                    if($this->miembrosJunta->count()>0) {
                        return true;
                    } 
                    break;
                case 'comision':
                    if($this->miembrosComision->count()>0) {
                        return true;
                    }   
                    break;
            }
            
        }

        return false;
    }

    /**
     * @brief Método que devuelve los datos a los que tendría acceso un miembro de centro, junta o comisión
     * @param lista lista de los datos que queremos obtener
     * @return resultado datos con acceso de miembro
     */
    public function esMiembroDatos($lista){

        $resultado=[
            'centros' => [],
            'juntas' => [],
            'comisiones' => [],
        ];

        $lista = explode("|", $lista);

        foreach ($lista as $r) {

            switch($r){
                case 'centro':
                    foreach ($this->miembrosGobierno as $miembro) {
                            $resultado['centros']['idCentros'][]=$miembro->idCentro;
                    }              
                    break;
                case 'junta':
                    foreach ($this->miembrosJunta as $miembro) {
                            $resultado['juntas']['idJuntas'][]=$miembro->idJunta;
                            $resultado['juntas']['idCentros'][]=$miembro->junta->idCentro;
                    }  
                    break;
                case 'comision':
                    foreach ($this->miembrosComision as $miembro) {
                            $resultado['comisiones']['idComisiones'][]=$miembro->idComision;
                            $resultado['comisiones']['idJuntas'][]=$miembro->comision->idJunta;
                    }  
                    break;
            }
        }

        return $resultado;
    }

    /**
     * @brief Método que devuelve los nombres de los roles de la aplicación 
     * @return string res nombres de roles
     */
    public function getRoleName(){

        $res = '';
        $roles = $this->getRoleNames();

        foreach($roles as $rol){
            $res .= $rol;
        }

        return $res;
    }

    /**
     * @brief Método que devuelve los permisos de los roles de la aplicación 
     * @return string res permisos de roles
     */
    public function getRoleNameType(){
        
        $roles = $this->getRoleNames();
        $res = '';

        foreach($roles as $rol){

            switch($rol){

                case 'admin':
                    $res =  'Todos los permisos';
                    break;

                case 'responsable_centro':

                    $centros = MiembroGobierno::where('miembros_gobierno.idUsuario', $this->id)
                    ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                    ->join('centros', 'miembros_gobierno.idCentro', '=', 'centros.id')
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
