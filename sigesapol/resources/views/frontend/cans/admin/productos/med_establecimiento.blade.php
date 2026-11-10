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
          var id_servicio = 0;
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
          {
            "mRender": function(data, type, row) {
              var observacion = "";
              //if (row.necesidad_anual != null) necesidad_anual = row.necesidad_anual;
              return observacion;
            },
            "bSortable": false,
            "aTargets": [2]
          },
          {
            "mRender": function(data, type, row) {
              var necesidad_anual = "";
              if (row.necesidad_anual != null) necesidad_anual = row.necesidad_anual;
              return necesidad_anual;
            },
            "bSortable": false,
            "aTargets": [3]
          },
          {
            "mRender": function(data, type, row) {
              var cpma = "";
              if (row.cpma != null) cpma = row.cpma;
              return cpma;
            },
            "bSortable": false,
            "aTargets": [4]
          },
          {
            "mRender": function(data, type, row) {
              var mes1 = "";
              if (row.mes1 != null) mes1 = row.mes1;
              return mes1;
            },
            "bSortable": false,
            "aTargets": [5]
          },
          {
            "mRender": function(data, type, row) {
              var mes2 = "";
              if (row.mes2 != null) mes2 = row.mes2;
              return mes2;
            },
            "bSortable": false,
            "aTargets": [6]
          },
          {
            "mRender": function(data, type, row) {
              var mes3 = "";
              if (row.mes3 != null) mes3 = row.mes3;
              return mes3;
            },
            "bSortable": false,
            "aTargets": [7]
          },
          {
            "mRender": function(data, type, row) {
              var mes4 = "";
              if (row.mes4 != null) mes4 = row.mes4;
              return mes4;
            },
            "bSortable": false,
            "aTargets": [8]
          },
          {
            "mRender": function(data, type, row) {
              var mes5 = "";
              if (row.mes5 != null) mes5 = row.mes5;
              return mes5;
            },
            "bSortable": false,
            "aTargets": [9]
          },
          {
            "mRender": function(data, type, row) {
              var mes6 = "";
              if (row.mes6 != null) mes6 = row.mes6;
              return mes6;
            },
            "bSortable": false,
            "aTargets": [10]
          },
          {
            "mRender": function(data, type, row) {
              var mes7 = "";
              if (row.mes7 != null) mes7 = row.mes7;
              return mes7;
            },
            "bSortable": false,
            "aTargets": [11]
          },
          {
            "mRender": function(data, type, row) {
              var mes8 = "";
              if (row.mes8 != null) mes8 = row.mes8;
              return mes8;
            },
            "bSortable": false,
            "aTargets": [12]
          },
          {
            "mRender": function(data, type, row) {
              var mes9 = "";
              if (row.mes9 != null) mes9 = row.mes9;
              return mes9;
            },
            "bSortable": false,
            "aTargets": [13]
          },
          {
            "mRender": function(data, type, row) {
              var mes10 = "";
              if (row.mes10 != null) mes10 = row.mes10;
              return mes10;
            },
            "bSortable": false,
            "aTargets": [14]
          },
          {
            "mRender": function(data, type, row) {
              var mes11 = "";
              if (row.mes11 != null) mes11 = row.mes11;
              return mes11;
            },
            "bSortable": false,
            "aTargets": [15]
          },
          {
            "mRender": function(data, type, row) {
              var mes12 = "";
              if (row.mes12 != null) mes12 = row.mes12;
              return mes12;
            },
            "bSortable": false,
            "aTargets": [16]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [17]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [18]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [19]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [20]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [21]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [22]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [23]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [24]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [25]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [26]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [27]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [28]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [29]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [30]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [31]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [32]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [33]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [34]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [35]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [36]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [37]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [38]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [39]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [40]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [41]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [42]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [43]
          },
          /*{
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [44]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [45]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [46]
          },*/
        ]
      });
    }
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
      <a href="/monitorear_can/{{$id_can}}" class="btn btn-default  pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Cuadro Anual de Necesidad - {{$nombre_establecimiento}} </h3>
                <input type="hidden" value="<?php echo $id_can?>" name="id_can" id="id_can" class="form-control">
                <input type="hidden" value="<?php echo $id_establecimiento?>" name="id_establecimiento" id="id_establecimiento" class="form-control">
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
                                <th rowspan="2"bgcolor="#D4E6F1" style="text-align:center;">Observacion</th>
                                <th rowspan="2" bgcolor="#D4E6F1" style="text-align:center;">Necesidad</th>
                                <th colspan="12" bgcolor="#D4E6F1" style="text-align:center;">
                                PRORRATEO</th>
                                <th rowspan="2"bgcolor="#3c8dbc" style="text-align:center;">Observacion</th>
                                <th rowspan="2" bgcolor="#3c8dbc" style="text-align:center;">Necesidad</th>
                                <th colspan="12" bgcolor="#3c8dbc" style="text-align:center;">
                                PRORRATEO</th>
                                <th rowspan="2"bgcolor="#3c763d" style="text-align:center;">Observacion</th>
                                <th rowspan="2" bgcolor="#3c763d" style="text-align:center;">Necesidad</th>
                                <!--th rowspan="2" style="text-align:center;">Justificación</th-->
                                <th colspan="12" bgcolor="#3c763d" style="text-align:center;">
                                PRORRATEO</th>
                            </tr>
                        <tr>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Enero</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Febrero</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Marzo</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Abril</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Mayo</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Junio</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Julio</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Agosto</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Setiembre</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Octubre</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Noviembre</th>
                            <th bgcolor="#D4E6F1" style="text-align:center;">Diciembre</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Enero</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Febrero</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Marzo</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Abril</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Mayo</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Junio</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Julio</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Agosto</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Setiembre</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Octubre</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Noviembre</th>
                            <th bgcolor="#3c8dbc" style="text-align:center;">Diciembre</th> 
                            <th bgcolor="#3c763d" style="text-align:center;">Enero</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Febrero</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Marzo</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Abril</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Mayo</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Junio</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Julio</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Agosto</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Setiembre</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Octubre</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Noviembre</th>
                            <th bgcolor="#3c763d" style="text-align:center;">Diciembre</th> 
                        </tr>
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
