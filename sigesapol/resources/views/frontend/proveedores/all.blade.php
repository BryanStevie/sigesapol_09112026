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
      var tblProveedores = $('#tblproveedores').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": "/listar_proveedores_ajax",
              "type": "POST",
              "dataType": "json",
              "data":{ 
                _token: "{{csrf_token()}}"
              }
          },
          "columnDefs": [
            { "orderable": false, "targets": [7,8] }
          ],
          "pageLength": 10,
          "dom": 'Bfrtip',
          "filter":true,
          "columns": [
              { "data": "row_number", "name": "N" },
              { "data": "ruc_proveedor", "name": "RUC" },
              { "data": "nombre_proveedor", "name":"Descripcion"},
              { "data": "direccion_proveedor", "name":"Dirección"},
              { "data": "fax_proveedor", "name":"FAX"},
              { "data": "telefono_proveedor", "name":"TELEFONO"},
              { "data": "descripcion_estado", "name":"Estado", "className": "text-center" }
              @if ($permiso->modificar == 1)
              ,{ "data": null, "name":"Editar", "className": "text-center", "render": function(data, type, row, meta){
                  if (row['estado'] == 1) {
                    return `<a class='btn btn-success btn-xs' href='/editar_proveedor/${row['id']}'><i class='fa fa-edit'></i></a>`;  
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
          ]
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
      bootbox.confirm("¿Se activará al proveedor de salud, desea continuar?", function(result) {
        if (result==true) {             
          $.ajax({
            url: '/desactivar_proveedor/'+id+'/'+estado,
            success: function(result){
              location.reload();
            }
          });
        }
      });
    } else {
        bootbox.confirm("¿Se desactivará al proveedor de salud?", function(result) {
            if (result==true) {             
                $.ajax({
                    url: '/desactivar_proveedor/'+id+'/'+estado,
                    success: function(result){
                        location.reload();
                    }
                 });
            }
        });
    }
    }
    
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Proveedores</h3>
      </div>
      <div class="panel-body">
        <?php if ($permiso->crear == 1): ?>
        <a href="/formulario_proveedor" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
        <?php endif; ?>
        <br /><br />
        <div class="table-responsive">
          <table class="table" id="tblproveedores">
            <thead>
              <tr class="filters">
                <th>N</th>
                <th>RUC</th>
                <th style="width: 300px">Descripción</th>
                <th>Dirección</th>
                <th>FAX</th>
                <th>Teléfono</th>
                <th>Estado</th>
                @if ($permiso->modificar == 1)
                <th>Editar</th>
                @endif
                @if ($permiso->eliminar == 1)
                <th>Desactivar</th>
                @endif
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection