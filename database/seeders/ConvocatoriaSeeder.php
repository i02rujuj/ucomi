<?php

namespace Database\Seeders;

use App\Models\Convocado;
use App\Models\Convocatoria;
use App\Models\MiembroJunta;
use App\Models\MiembroComision;
use Illuminate\Database\Seeder;

class ConvocatoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $convocatoriaJunta = new Convocatoria();
        $convocatoriaJunta->idJunta = 1;
        $convocatoriaJunta->idTipo = config('constants.TIPOS_CONVOCATORIA.EXTRAORDINARIA');
        $convocatoriaJunta->lugar = 'Sala de Juntas José Agüera Soriano del Campus Universitario de Rabanales';
        $convocatoriaJunta->fecha = '2022-12-02';
        $convocatoriaJunta->hora = '09:30';
        $convocatoriaJunta->acta = asset('actas/EPSC_JE_20221202_ACTA.pdf');
        $convocatoriaJunta->save();

        $miembrosJunta = MiembroJunta::
            where('idJunta', $convocatoriaJunta->idJunta)
            ->get();

        foreach ($miembrosJunta as $miembro) {
            Convocado::create([
                "idConvocatoria" => $convocatoriaJunta->id,
                "idUsuario" => $miembro->usuario->id,
                "asiste" => 0,
                "notificado" => 0,
            ]);
        }

        $convocatoriaComision = new Convocatoria();
        $convocatoriaComision->idComision = 1;
        $convocatoriaComision->idTipo = config('constants.TIPOS_CONVOCATORIA.ORDINARIA');
        $convocatoriaComision->lugar = 'Videoconferencia mediante la plataforma Webex';
        $convocatoriaComision->fecha = '2022-07-14';
        $convocatoriaComision->hora = '09:30';
        $convocatoriaComision->acta = asset('actas/Acta_CAE-EPSC_2022-07-14_sf.pdf');
        $convocatoriaComision->save();

        $miembrosComision = MiembroComision::
            where('idComision', $convocatoriaComision->idComision)
            ->get();

        foreach ($miembrosComision as $miembro) {
            Convocado::create([
                "idConvocatoria" => $convocatoriaComision->id,
                "idUsuario" => $miembro->usuario->id,
                "asiste" => 0,
                "notificado" => 0,
            ]);
        }
    }
}
