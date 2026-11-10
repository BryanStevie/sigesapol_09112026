@extends('frontend.layouts.master')
@section('renderjs')
<style>
  .filterable {
    margin-top: 0px;
  }

  .filterable .panel-heading .pull-right {
    margin-top: -20px;
  }

  .filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
  }

  .filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
  }
</style>

<script>
  $(document).ready(function () {
      var tblPersonals = $('#tbldpersonals').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": "/listar_medicos_by_ipress",
              "type": "POST",
              "dataType": "json",
              "data":{ 
                _token: "{{csrf_token()}}",
                id_establecimiento: $("#id_establecimiento").val()
              }
          },
          "columnDefs": [
            { "orderable": false, "targets": [12,13] }
          ],
          "pageLength": 10,
          "dom": 'Bfrtip',
          "filter":true,
          "columns": [
              { "data": "row_number", "name": "N" },
              { "data": "tipo_documento", "name": "Tipo Doc" },
              { "data": "dni", "name": "DNI" },
              { "data": "nombre", "name":"Nombre"},
              { "data": "paterno", "name":"Paterno"},
              { "data": "materno", "name":"Materno"},
              { "data": "colegiatura", "name":"Colegiatura"},
              { "data": "rne", "name":"RNE"},
              { "data": "establecimiento", "name":"Establecimiento"},
              { "data": "especializacion", "name":"Especialización"},
              { "data": "profesion", "name":"Profesión"},
	      { "data": "servicio", "name":"Servicios"},              
	      { "data": "tipo_contrato", "name":"Tipo Contrato"},
              { "data": "descripcion_estado", "name":"Estado", "className": "text-center" }
              @if ($permiso->modificar == 1)
              ,{ "data": null, "name":"Editar", "className": "text-center", "render": function(data, type, row, meta){
                  if (row['estado'] == 1) {
                    return `<a class='btn btn-success btn-xs' href='/editar_medico/${row['id']}/{{ Auth::user()->id_farmacia }}'><i class='fa fa-edit'></i></a>`;  
                  }else {
                    return `<button type="button" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>`
                  }  
                }
              }
              @endif
              @if ($permiso->eliminar == 1)
              ,{ "data": null, "name":"Desactivar", "className": "text-center", "render": function(data, type, row, meta){
                  if (row['estado'] == 1) {
                    return `<a class="btn btn-warning btn-xs" href="javascript:void(0)"
                      onclick="eliminar(${row['id']},${row['estado']})"><i
                        class="fa fa-thumbs-up"></i></a>`;
                  } else {
                    return `<a class="btn btn-success btn-xs" href="javascript:void(0)"
                      onclick="eliminar(${row['id']},${row['estado']})"><i
                        class="fa fa-thumbs-up"></i></a>`
                  }
                } 
              }
            @endif
            , {
                    "data": null,
                    "name": "Ver Detalle",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {                     
                            return `<a class='btn btn-xs'
                                    style="border:1px solid #007bff; color:#007bff; background-color:transparent; cursor:pointer;"
                                    onclick="verDetalle(${row['id']})"
                                    onmouseover="this.style.backgroundColor='#007bff'; this.style.color='#fff';"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';">
                                        <i class='fa fa-eye'></i>
                                    </a>`;                       
                    }
                }
          ]
      }); 
      let currentUrl = "/listar_medicos_by_ipress";
      tblPersonals.on('search.dt', function () {
        var searchValue = tblPersonals.search().trim();
        if (searchValue === "") {
          if (currentUrl !== "/listar_medicos_by_ipress") {
              currentUrl = "/listar_medicos_by_ipress";
              tblPersonals.ajax.url(currentUrl).load();
          }
        } else {
          if (currentUrl !== "/listar_medicos_ajax_general") {
              currentUrl = "/listar_medicos_ajax_general";
              tblPersonals.ajax.url(currentUrl).load();
          }
        }
      });   
    });


    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el Tipo de Proceso</td></tr>'));
            }
        });
    });

    function eliminar(id, estado){
    if(estado == 3){
      bootbox.confirm("¿Se activará el personal de salud, desea continuar?", function(result) {
        if (result==true) {             
          $.ajax({
            url: '/desactivar_medico/'+id+'/'+estado,
            success: function(result){
              location.reload();
            }
          });
        }
      });
    } else {
        bootbox.confirm("¿Se desactivará el personal de salud y se cancelará la programación de sus atenciones a partir de mañana, desea continuar?", function(result) {
            if (result==true) {             
                $.ajax({
                    url: '/desactivar_medico/'+id+'/'+estado,
                    success: function(result){
                        location.reload();
                    }
                 });
            }
        });
    }
    }
    function verDetalle(id) {

    $('#modalDetalle').modal('show');
    $('#contenidoDetalle').html('Cargando...');

    $.ajax({
        url: '/detalle_medico_creacion/' + id,
        type: 'GET',
        success: function(response) {

            if (response.length === 0) {
                $('#contenidoDetalle').html('Sin datos');
                return;
            }

            let data = response[0];

            let html = `
                <div class="row">

                    <div class="col-md-12">
                        <h4>📌 Personal de Salud</h4>
                        <p><strong>Fecha creación:</strong> ${data.fecha_creacion ?? '-'}</p>
                        <p><strong>Creador:</strong> ${data.dato_creador ?? '-'}</p>
                        <p><strong>Fecha actualización:</strong> ${data.fecha_actualizacion ?? '-'}</p>
                        <p><strong>Actualizador:</strong> ${data.dato_actualizador ?? '-'}</p>
                    </div>
                </div>
            `;

            $('#contenidoDetalle').html(html);
        },
        error: function() {
            $('#contenidoDetalle').html('Error al cargar datos');
        }
    });
}
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Médicos</h3>
      </div>
      <div class="panel-body">
        <?php if ($permiso->crear == 1): ?>
        <a href="/buscar_medico" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
        <?php endif; ?>
        <br /><br />
        <div class="table-responsive">
          <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
          <table class="table" id="tbldpersonals">
            <thead>
              <tr class="filters">
                <th>N</th>
                <th>Tipo Doc</th>
                <th>Nro</th>
                <th style="width: 100px">Nombres</th>
                <th style="width: 50px">Paterno</th>
                <th style="width: 50px">Materno</th>
                <th>CPM</th>
                <th>RNE</th>
                <th>Establecimientos</th>
                <th>Especializacion</th>
                <th>Profesión</th>
                <th>Servicios</th>
		<th>Tipo de Contratos</th>
                <th>Estado</th>
                @if ($permiso->modificar == 1)
                <th>Editar</th>
                @endif
                @if ($permiso->eliminar == 1)
                <th>Desactivar</th>
                @endif
                <th>Detalle</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalle del Personal de Salud</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body" id="contenidoDetalle">
        Cargando...
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection