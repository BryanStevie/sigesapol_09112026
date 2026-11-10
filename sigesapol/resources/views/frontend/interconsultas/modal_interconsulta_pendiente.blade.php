<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>Servicio Solicitante</th>
        <th>Fecha / Hora Solicitud</th>
        <th>Medico Solicitante</th>
        <th>Paciente</th>
        <th>Ubicación (Area - Sala - Cama)</th>
    </tr>
    <?php $i=1; ?>
    @foreach($datos as $d)
    <tr>
        <td>{{$i}}</td>
        <td>{{$d->servicio_solicitante}}</td>
        <td>{{$d->fecha_solicitud}} {{$d->hora_solicitud}}</td>
        <td>{{$d->medico_solicitante}}</td>
        <td>{{$d->ndocumento_paciente}} - {{$d->paciente}}</td>
        <td>{{$d->nombre_area}} - {{$d->cuarto}} - {{$d->cama}}</td>
    </tr>
    <?php $i++; ?>
    @endforeach
</table>