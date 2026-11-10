@extends('frontend.layouts.master')
@section('renderjs')
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
$(document).ready(function() {
    datatablenew();
});

function datatablenew() {
        var oTable1 = $('#tblCan').dataTable({
            fixedColumns:   {
                    leftColumns: 2
            },
            "order": [[ 1, "asc" ]],
            "scrollX":        true,
            "scrollCollapse": true,
            "bServerSide": true,
            "sAjaxSource": "/listar_can_productos_establecimiento_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            //"paging":false,
            "bFilter": false,
            "bSort": false,
            "info": true,
            //"responsive": true,
            "language": {
              "url": "/js/Spanish.json"
            },
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [
              [10, 50, 100, 200, 60000],
              [10, 50, 100, 200, "Todos"]
            ],
            "aoColumns": [{}, ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
              $('[data-toggle="tooltip"]').tooltip();
        },

        "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

          var sEcho = aoData[0].value;
          var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
          var iCantMostrar = aoData[4].value;
          var id_can = $('#id_can').val();
          var id_establecimiento = $('#id_establecimiento').val();
          var id_servicio = $('#id_servicio').val();
          var tipo_producto = $('#tipo_producto').val();
          var _token = $('#_token').val();
          oSettings.jqXHR = $.ajax({
            "dataType": 'json',
            //"contentType": "application/json; charset=utf-8",
            "type": "POST",
            "url": sSource,
            "data": {
              NumeroPagina: iNroPagina,
              NumeroRegistros: iCantMostrar,
              id_establecimiento: id_establecimiento,
              id_can: id_can,
              tipo_producto: tipo_producto,
              id_servicio: id_servicio,
              id_can: id_can,
              _token: _token
            },
            "success": function(result) {
              fnCallback(result);
            },
            "error": function(msg, textStatus, errorThrown) {
              //location.href="login";
            }
          });
        },

        "aoColumnDefs": [{
            "mRender": function(data, type, row) {
              var id = "";
              if (row.id != null) id = row.id;
              return id;
            },
            "bSortable": false,
            "aTargets": [0]
          },
          {
            "mRender": function(data, type, row) {
              var descripcion = "";
              if (row.descripcion != null) descripcion = row.descripcion;
              return descripcion;
            },
            "bSortable": false,
            "aTargets": [1]
          },
          /*{
            "mRender": function(data, type, row) {
              var observacion = "";
              //if (row.necesidad_anual != null) necesidad_anual = row.necesidad_anual;
              return observacion;
            },
            "bSortable": false,
            "aTargets": [2]
          },*/
          {
            "mRender": function(data, type, row) {
              var necesidad_anual = "";
              if (row.necesidad_anual != null) necesidad_anual = row.necesidad_anual;
              return necesidad_anual;
            },
            "bSortable": false,
            "aTargets": [2]
          },
          <?php if($id_can<6): ?> 
          {
            "mRender": function(data, type, row) {
              var mes1 = "";
              if (row.mes1 != null) mes1 = row.mes1;
              return mes1;
            },
            "bSortable": false,
            "aTargets": [3]
          },
          {
            "mRender": function(data, type, row) {
              var mes2 = "";
              if (row.mes2 != null) mes2 = row.mes2;
              return mes2;
            },
            "bSortable": false,
            "aTargets": [4]
          },
          {
            "mRender": function(data, type, row) {
              var mes3 = "";
              if (row.mes3 != null) mes3 = row.mes3;
              return mes3;
            },
            "bSortable": false,
            "aTargets": [5]
          },
          {
            "mRender": function(data, type, row) {
              var mes4 = "";
              if (row.mes4 != null) mes4 = row.mes4;
              return mes4;
            },
            "bSortable": false,
            "aTargets": [6]
          },
          {
            "mRender": function(data, type, row) {
              var mes5 = "";
              if (row.mes5 != null) mes5 = row.mes5;
              return mes5;
            },
            "bSortable": false,
            "aTargets": [7]
          },
          {
            "mRender": function(data, type, row) {
              var mes6 = "";
              if (row.mes6 != null) mes6 = row.mes6;
              return mes6;
            },
            "bSortable": false,
            "aTargets": [8]
          },
          {
            "mRender": function(data, type, row) {
              var mes7 = "";
              if (row.mes7 != null) mes7 = row.mes7;
              return mes7;
            },
            "bSortable": false,
            "aTargets": [9]
          },
          {
            "mRender": function(data, type, row) {
              var mes8 = "";
              if (row.mes8 != null) mes8 = row.mes8;
              return mes8;
            },
            "bSortable": false,
            "aTargets": [10]
          },
          {
            "mRender": function(data, type, row) {
              var mes9 = "";
              if (row.mes9 != null) mes9 = row.mes9;
              return mes9;
            },
            "bSortable": false,
            "aTargets": [11]
          },
          {
            "mRender": function(data, type, row) {
              var mes10 = "";
              if (row.mes10 != null) mes10 = row.mes10;
              return mes10;
            },
            "bSortable": false,
            "aTargets": [12]
          },
          {
            "mRender": function(data, type, row) {
              var mes11 = "";
              if (row.mes11 != null) mes11 = row.mes11;
              return mes11;
            },
            "bSortable": false,
            "aTargets": [13]
          },
          {
            "mRender": function(data, type, row) {
              var mes12 = "";
              if (row.mes12 != null) mes12 = row.mes12;
              return mes12;
            },
            "bSortable": false,
            "aTargets": [14]
          }
        <?php endif; ?>
        ]
      });
    }
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
      <a href="/monitorear_can_servicio/{{$id_can}}/{{$id_establecimiento}}" class="btn btn-default  pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Cuadro Anual de Necesidad - {{$nombre_establecimiento}} @if($id_servicio!=0) - {{$servicio->nombre}} @endif</h3>
                <input type="hidden" value="<?php echo $id_can?>" name="id_can" id="id_can" class="form-control">
                <input type="hidden" value="<?php echo $id_establecimiento?>" name="id_establecimiento" id="id_establecimiento" class="form-control">
                <input type="hidden" value="<?php echo $id_servicio?>" name="id_servicio" id="id_servicio" class="form-control">
                <input type="hidden" value="1" name="tipo_producto" id="tipo_producto" class="form-control">
            </div>
            <div class="panel-body">
                
                
                
                <br /><br />
                <div class="table-responsive">
                    <?php $x=1; ?>
                    <table id="tblCan" class="table table-bordered">
                    <thead>
                        <tr>
                                <th rowspan="2" style="text-align:center;">#</th>
                                <th rowspan="2" style="text-align:center;">Descripción</th>
                                <!--th rowspan="2"bgcolor="#D4E6F1" style="text-align:center;">Observacion</th-->
                                <th rowspan="2" bgcolor="#D4E6F1" style="text-align:center;">Necesidad</th>
                                @if($id_can<6)
                                <th colspan="12" bgcolor="#D4E6F1" style="text-align:center;">
                                PRORRATEO</th>
                                @endif
                            </tr>
                        @if($id_can<6)
                        <tr>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 1</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 2</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 3</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 4</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 5</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 6</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 7</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 8</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 9</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 10</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 11</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mes 12</th>
                        </tr>
                        @endif
                    </thead>                
                    <tbody>
                    
                    </tbody>
                    
                </table>    
                </div>        
            </div>    
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
