<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Certificado {{$tipo}}</title>

    <style>
        @page {
            margin-left: 2.5cm;
            margin-right: 2.5cm;
            margin-top: 1.5cm;
            margin-bottom: 1.5cm;
        }

        table{
            border: 1px solid;
            font-size: 12px;
            margin-top: 20px; 
        }

        td, th {
            border: 1px solid;
            padding: 10px;
        }
    </style>
</head>
<body>

    <img src="{{ asset('img/inicio1.jpg') }}" alt="LogoUCO" width="180px"/>
    <img src="{{ asset('img/logo.jpg') }}" alt="LogoUCOMI" width="130px" style="padding-bottom:10px; margin-left: 290px"/>

    <hr style="margin-top:-10px">

	<h2>Certificado {{$tipo}}</h2>
    <p style="text-indent: 40px; text-align: justify;">
        @if($dateDesde!=null && $dateHasta!=null) Desde la fecha {{$dateDesde}} hasta la fecha {{$dateHasta}}@else A fecha de hoy, {{date('d/m/Y')}} @endif
        , {{$usuario}} figura como representante de los siguientes órganos de representación de la Universidad de Córdoba:  
    </p>

    @if(isset($dataCentro))
        @if($dataCentro->count())
            <table>
                @if ($idTipo==config('constants.TIPOS_CERTIFICADO.ACTUAL'))
                    <tr>
                        <th style="width: 200px;">Centro</th>
                        <th style="width: 190px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @else
                    <tr>
                        <th style="width: 175px;">Centro</th>
                        <th style="width: 140px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 60px;">Cese</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @endif
                
                @foreach ($dataCentro as $miembro)
                    <tr>
                        <td>{{ $miembro->centro->tipo->nombre }} {{ $miembro->centro->nombre }}</td>
                        <td>{{ $miembro->representacion->nombre }} 
                            @if($miembro->cargo) con cargo {{ $miembro->cargo }} @endif
                        </td>
                        <td>{{ $miembro->fecha_toma_posesion_format }}</td>
                        @if ($idTipo!=config('constants.TIPOS_CERTIFICADO.ACTUAL')) <td>{{ $miembro->fecha_cese_format }} </td> @endif 
                        <td>@if ($miembro->responsable==1) Sí @else No @endif</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="text-indent: 40px; text-align: justify;">No existen datos como miembro de representación en centros.</p>
        @endif
    @endif
    
    @if(isset($dataJunta))
        @if ($dataJunta->count())

            <table>
                @if ($idTipo==config('constants.TIPOS_CERTIFICADO.ACTUAL'))
                    <tr>
                        <th style="width: 200px;">Junta de centro</th>
                        <th style="width: 190px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @else
                    <tr>
                        <th style="width: 175px;">Junta de centro</th>
                        <th style="width: 140px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 60px;">Cese</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @endif
                
                @foreach ($dataJunta as $miembro)
                    <tr>
                        <td>Junta de {{ $miembro->junta->centro->tipo->nombre }} {{ $miembro->junta->centro->nombre }} constituida en fecha {{ $miembro->junta->fecha_constitucion_format}}</td>
                        <td>{{ $miembro->representacion->nombre }}</td>
                        <td>{{ $miembro->fecha_toma_posesion_format }}</td>
                        @if ($idTipo!=config('constants.TIPOS_CERTIFICADO.ACTUAL')) <td>{{ $miembro->fecha_cese_format }} </td> @endif 
                        <td>@if ($miembro->responsable==1) Sí @else No @endif</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="text-indent: 40px; text-align: justify;">No existen datos como miembro de representación en juntas.</p>
        @endif
    @endif

    @if(isset($dataComision))
        @if ($dataComision->count())

            <table>
                @if ($idTipo==config('constants.TIPOS_CERTIFICADO.ACTUAL'))
                    <tr>
                        <th style="width: 200px;">Comisión</th>
                        <th style="width: 190px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @else
                    <tr>
                        <th style="width: 175px;">Comisión</th>
                        <th style="width: 140px;">Representación</th>
                        <th style="width: 60px;">Toma posesión</th>
                        <th style="width: 60px;">Cese</th>
                        <th style="width: 40px;">Responsable</th>
                    </tr>
                @endif
                
                @foreach ($dataComision as $miembro)
                    <tr>
                        <td>{{ $miembro->comision->nombre }} constituida en fecha {{ $miembro->comision->fecha_constitucion_format}}</td>
                        <td>{{ $miembro->representacion->nombre }}
                            @if($miembro->cargo) con cargo {{ $miembro->cargo }} @endif
                        </td>
                        <td>{{ $miembro->fecha_toma_posesion_format }}</td>
                        @if ($idTipo!=config('constants.TIPOS_CERTIFICADO.ACTUAL')) <td>{{ $miembro->fecha_cese_format }} </td> @endif 
                        <td>@if ($miembro->responsable==1) Sí @else No @endif</td>
                    </tr>
                @endforeach
            </table>
        @else
        <p style="text-indent: 40px; text-align: justify;">No existen datos como miembro de representación en comisiones.</p>
        @endif
    @endif

    <h5 style="text-align: right">Documento generado con fecha {{date('d/m/Y')}}</h5>

</body>
</html>