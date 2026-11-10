
<script>
$(document).ready(function() {
    $('#table-historial-laboratorio').dataTable( {
            ordering: false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
});


function verResultadoLaboratorio(dni,dia,mes,ano,codOrden){
  window.open('/print_examen_laboratorio_lns/0/'+dni+'/'+dia+'/'+mes+'/'+ano+'/'+codOrden+'', '_blank');
}


var tabla_historial_lab = $('#table-historial-laboratorio').DataTable();
var tabla = $('#table-historial-laboratorio').DataTable();

    $('#table-historial-laboratorio tbody').on('click', '.btn-desplegar', function () {
        var fila = $(this).closest('tr');
        var contenido = fila.find('.lista-examenes').html();

        if (tabla_historial_lab.row(fila).child.isShown()) {
          tabla_historial_lab.row(fila).child.hide();
            $(this).text('Ver detalles');
        } else {
          tabla_historial_lab.row(fila).child('<div class="p-2" style="background-color: antiquewhite;">' + contenido + '</div>').show();
            $(this).text('Ocultar detalles');
        }
    });

</script>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <table class="table"  id="table-historial-laboratorio">
        <thead >
          <tr>
            <th>Fecha Creacion</th>
            <th>Fecha Cita</th>
            <th>Establecimiento</th>
            <th>Servicio</th>
            <th>Consultorio</th>
            <th>Servicio(s) Origen</th>
            <th>Registrado Por</th>
            <th>Observacion</th>
            <th>Resultados</th>
            <th>Estado</th>
            <th>Anular</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($citas as $cita)
          <tr>
            <td>{{$cita->cifa_fecha_creacion}}</td>
            <td>{{$cita->dia}} {{$cita->hora}}</td>
            <td>{{$cita->establecimiento}}</td>
            <td>{{$cita->servicio}}</td>
            <td>{{$cita->consultorio}}</td>
            <td>{{$cita->servicios_origen}}</td>
            <td>{{$cita->usuario_registro}}</td>
            <td>{{$cita->observacion}}</td>
            
            <td>
              <div class="btn btn-success btn-xs btn-desplegar">Ver detalles</div>
              <?php
                 $model_laboratorio = new \App\Models\Laboratorio();
                  $examenes = $model_laboratorio->obterInfromacionCita($cita->id);
              ?>
           
            <div class="lista-examenes " style="display: none;">
              <ul class="mb-2">

                @foreach($examenes as $examen)
                  <li> {{ $examen->consultorio_nombre }}  / {{ $examen->codigo }} - {{ $examen->descripcion }}</li>
                @endforeach
              </ul>
            </div>
              @if($cita->cod_orden_lab != null)
              <div class="btn btn-success btn-xs" onclick="verResultadoLaboratorio('{{$cita->nro_doc_ident}}', {{$cita->id}},{{$cita->id}},{{$cita->id}},{{$cita->cod_orden_lab}})" >Resultados</div>
              @endif
              
            </td>
            <td>{{$cita->estado_cita}}</td>
            <td>
              @if ($cita->id_estado_cita != 5 && $cita->id_establecimiento == Auth::user()->id_establecimiento && Auth::user()->id_consultorio == 113)
                  <button type="button" class="btn btn-danger btn-xs" onclick="fn_anularCita({{ $cita->id }})">X</button>
              @else
                  <button type="button" class="btn btn-danger btn-xs" disabled>X</button>
              @endif
            </td>
          </tr>
          @endforeach
          
        </tbody>
      </table>
    </div>
  </div>
</div>