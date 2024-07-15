<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Centro
 *
 * @property int $id
 * @property string $nombre
 * @property string $direccion
 * @property int $idTipo
 * @property string|null $logo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Junta> $juntas
 * @property-read int|null $juntas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroGobierno> $miembros
 * @property-read int|null $miembros_count
 * @property-read \App\Models\TipoCentro $tipo
 * @method static \Illuminate\Database\Eloquent\Builder|Centro filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Centro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Centro onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Centro query()
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereIdTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Centro withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Centro withoutTrashed()
 */
	class Centro extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comision
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property string $fechaConstitucion
 * @property string|null $fechaDisolucion
 * @property int $idJunta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Convocatoria> $convocatorias
 * @property-read int|null $convocatorias_count
 * @property-read \App\Models\Junta $junta
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroComision> $miembros
 * @property-read int|null $miembros_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroComision> $presidentes
 * @property-read int|null $presidentes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Comision filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comision onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Comision query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereFechaConstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereFechaDisolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereIdJunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comision withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Comision withoutTrashed()
 */
	class Comision extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Convocado
 *
 * @property int $id
 * @property int $idConvocatoria
 * @property int $idUsuario
 * @property int $asiste
 * @property int $notificado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Comision $convocatoria
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado query()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereAsiste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereIdConvocatoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereNotificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocado withoutTrashed()
 */
	class Convocado extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Convocatoria
 *
 * @property int $id
 * @property string $lugar
 * @property string $fecha
 * @property \Illuminate\Support\Carbon $hora
 * @property int $idTipo
 * @property int|null $idComision
 * @property int|null $idJunta
 * @property string|null $acta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Comision|null $comision
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Convocado> $convocados
 * @property-read int|null $convocados_count
 * @property-read \App\Models\Junta|null $junta
 * @property-read \App\Models\TipoConvocatoria $tipo
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereActa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereIdComision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereIdJunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereIdTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereLugar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Convocatoria withoutTrashed()
 */
	class Convocatoria extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Junta
 *
 * @property int $id
 * @property int $idCentro
 * @property string $fechaConstitucion
 * @property string|null $fechaDisolucion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $PAS
 * @property-read int|null $p_a_s_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $alumnado
 * @property-read int|null $alumnado_count
 * @property-read \App\Models\Centro $centro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comision> $comisiones
 * @property-read int|null $comisiones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Convocatoria> $convocatorias
 * @property-read int|null $convocatorias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $directores
 * @property-read int|null $directores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroGobierno> $miembrosGobierno
 * @property-read int|null $miembros_gobierno_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $miembrosJunta
 * @property-read int|null $miembros_junta_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $profesoradoOtro
 * @property-read int|null $profesorado_otro_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $profesoradoVinculacionPermanente
 * @property-read int|null $profesorado_vinculacion_permanente_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $secretarios
 * @property-read int|null $secretarios_count
 * @method static \Illuminate\Database\Eloquent\Builder|Junta filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Junta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Junta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Junta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereFechaConstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereFechaDisolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereIdCentro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Junta withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Junta withoutTrashed()
 */
	class Junta extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MiembroComision
 *
 * @property int $id
 * @property int $idComision
 * @property int $idUsuario
 * @property string $fechaTomaPosesion
 * @property string|null $fechaCese
 * @property int $responsable
 * @property int $presidente
 * @property int $idRepresentacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Comision $comision
 * @property-read \App\Models\RepresentacionGeneral $representacion
 * @property-read \App\Models\User $usuario
 * @method static \Database\Factories\MiembroComisionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision query()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereFechaCese($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereFechaTomaPosesion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereIdComision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereIdRepresentacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision wherePresidente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroComision withoutTrashed()
 */
	class MiembroComision extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MiembroGobierno
 *
 * @property int $id
 * @property int $idCentro
 * @property int $idUsuario
 * @property int $idRepresentacion
 * @property string $fechaTomaPosesion
 * @property string|null $fechaCese
 * @property int $responsable
 * @property string $vigente
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $activo
 * @property-read \App\Models\Centro $centro
 * @property-read \App\Models\RepresentacionGobierno $representacion
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno query()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereFechaCese($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereFechaTomaPosesion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereIdCentro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereIdRepresentacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno whereVigente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroGobierno withoutTrashed()
 */
	class MiembroGobierno extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MiembroJunta
 *
 * @property int $id
 * @property int $idJunta
 * @property int $idUsuario
 * @property string $fechaTomaPosesion
 * @property string|null $fechaCese
 * @property int $responsable
 * @property int $idRepresentacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Junta $junta
 * @property-read \App\Models\RepresentacionGeneral $representacion
 * @property-read \App\Models\User $usuario
 * @method static \Database\Factories\MiembroJuntaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta filters(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta query()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereFechaCese($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereFechaTomaPosesion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereIdJunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereIdRepresentacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MiembroJunta withoutTrashed()
 */
	class MiembroJunta extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RepresentacionGeneral
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $miembrosJunta
 * @property-read int|null $miembros_junta_count
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGeneral withoutTrashed()
 */
	class RepresentacionGeneral extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RepresentacionGobierno
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroGobierno> $miembrosGobierno
 * @property-read int|null $miembros_gobierno_count
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno query()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RepresentacionGobierno withoutTrashed()
 */
	class RepresentacionGobierno extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TipoCentro
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Centro> $centros
 * @property-read int|null $centros_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCentro withoutTrashed()
 */
	class TipoCentro extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TipoConvocatoria
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Convocatoria> $convocatorias
 * @property-read int|null $convocatorias_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoConvocatoria withoutTrashed()
 */
	class TipoConvocatoria extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Convocado> $convocados
 * @property-read int|null $convocados_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroComision> $miembrosComision
 * @property-read int|null $miembros_comision_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroGobierno> $miembrosGobierno
 * @property-read int|null $miembros_gobierno_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MiembroJunta> $miembrosJunta
 * @property-read int|null $miembros_junta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

