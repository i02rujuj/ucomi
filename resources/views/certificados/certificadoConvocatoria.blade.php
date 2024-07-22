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
        @if($dateDesde!=null && $dateHasta!=null) Desde la fecha {{$dateDesde}} hasta la fecha {{$dateHasta}} @else A fecha de hoy, {{date('d/m/Y')}} @endif
        , figuran las siguientes convocatorias a las que {{$usuario}} ha sido convocado como representante de los siguientes órganos de representación de la Universidad de Córdoba:</p>
    
    @if(isset($dataJunta))
        @if($dataJunta->count())

            <table>
                <tr>
                    <th style="width: 160px;">Convocatoria de Junta</th>
                    <th style="width: 75px;">Tipo</th>
                    <th style="width: 150px;">Lugar</th>
                    <th style="width: 60px;">Celebración</th>
                    <th style="width: 40px;">Asistencia</th>
                </tr>
                
                @foreach ($dataJunta as $convocado)
                    <tr>
                        <td>Convocatoria Junta de {{ $convocado->convocatoria->junta->centro->tipo->nombre }} {{ $convocado->convocatoria->junta->centro->nombre }} constituida en fecha {{ $convocado->convocatoria->junta->fecha_constitucion_format}}</td>
                        <td>{{ $convocado->convocatoria->tipo->nombre }}</td>
                        <td>{{ $convocado->convocatoria->lugar }}</td>
                        <td>{{ $convocado->convocatoria->fecha_format }}</td>
                        <td>@if ($convocado->asiste==1) Sí @else No @endif</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="text-indent: 40px; text-align: justify;">No existen datos de convocatorias de junta.</p>
        @endif
    @endif

    @if(isset($dataComision))
        @if($dataComision->count())

            <table>
                <tr>
                    <th style="width: 160px;">Convocatoria de Comision</th>
                    <th style="width: 75px;">Tipo</th>
                    <th style="width: 150px;">Lugar</th>
                    <th style="width: 60px;">Celebración</th>
                    <th style="width: 40px;">Asistencia</th>
                </tr>
                
                @foreach ($dataComision as $convocado)
                    <tr>
                        <td>Convocatoria Comisión {{ $convocado->convocatoria->comision->nombre}} constituida en fecha {{ $convocado->convocatoria->comision->fecha_constitucion_format}}</td>
                        <td>{{ $convocado->convocatoria->tipo->nombre }}</td>
                        <td>{{ $convocado->convocatoria->lugar }}</td>
                        <td>{{ $convocado->convocatoria->fecha_format }}</td>
                        <td>@if ($convocado->asiste==1) Sí @else No @endif</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="text-indent: 40px; text-align: justify;">No existen datos de convocatorias de comisiones.</p>
        @endif
    @endif

    <h5 style="text-align: right">Documento generado con fecha {{date('d/m/Y')}}</h5>

</body>
</html>