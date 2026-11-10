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
            "sAjaxSource": "/listar_can_productos_servicio_ajax",
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
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [1]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [2]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [3]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [4]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [5]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [6]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [7]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [8]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [9]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [10]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [11]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [12]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [13]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [14]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
            },
            "bSortable": false,
            "aTargets": [15]
          },
          {
            "mRender": function(data, type, row) {
              var codigo = "";
              if (row.cod_producto != null) codigo = row.cod_producto;
              return codigo;
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
                <div class="row">
                    <div class="col-xs-6">
                        <div class="box box-danger">
                            <div class="box-body">
                                <div class="box-header">
                                    <h3 class="box-title">Observaciones</h3>
                                </div>
                                <div class="box-body">
                                    <table id="example" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Descripcion de la Observacion</th>
                                                <th>Descargar</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($observaciones as $key => $observ)
                                            <tr>
                                                <td>{{$key+1}}</td>                            
                                                <td>{!! $observ->descripcion_archivo !!}</td>
                                                <td>
                                                    <?php 
                                                        if($observ->extension_archivo=='pdf'){
                                                            $imagen='fa fa-file-pdf-o';
                                                            $color='bg-red';
                                                        }else
                                                        {   if($observ->extension_archivo=='xls' || $observ->extension_archivo=='xlsx'){
                                                                $imagen='fa-file-excel-o';
                                                                $color='bg-green';
                                                            }
                                                            else
                                                            {
                                                                if($observ->extension_archivo=='doc' || $observ->extension_archivo=='docx'){
                                                                    $imagen='fa-file-word-o';
                                                                    $color='bg-blue';
                                                                }
                                                                else
                                                                {
                                                                    if($observ->extension_archivo=='jpg' || $observ->extension_archivo=='gif' || $observ->extension_archivo=='jpeg' || $observ->extension_archivo=='png' || $observ->extension_archivo=='svg' || $observ->extension_archivo=='eps' || $observ->extension_archivo=='psd' ){
                                                                        $imagen='fa-file-image-o';
                                                                        $color='bg-purple';
                                                                    }
                                                                    else
                                                                    {
                                                                        $imagen='fa-archive';
                                                                        $color='bg-orange';      
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <a data-toggle="tooltip" title="<?php echo $observ->descripcion_archivo?>" target="_blank" href='{{ asset ("$observ->descarga_archivo") }}' class='btn <?php echo $color; ?>'><i class="fa <?php echo $imagen; ?>"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>            
                            </div>        
                        </div>
                    </div>    
                    <div class="col-xs-6">
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="box-header">
                                    <h3 class="box-title">Archivos subido por los Servicios</h3>
                                </div>
                                <table id="example" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descripcion del Archivo</th>
                                            <th>Descargar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($archivos as $key => $file)
                                        <tr>
                                            <td>{{$key+1}}</td>                            
                                            <td>
                                                {!! $file->descripcion_archivo !!} 
                                            </td>
                                            <td>
                                                <?php 
                                                    if($file->extension_archivo=='pdf'){
                                                        $imagen='fa fa-file-pdf-o';
                                                        $color='bg-red';
                                                    }else
                                                    {   if($file->extension_archivo=='xls' || $file->extension_archivo=='xlsx'){
                                                            $imagen='fa-file-excel-o';
                                                            $color='bg-green';
                                                        }
                                                        else
                                                        {
                                                            if($file->extension_archivo=='doc' || $file->extension_archivo=='docx'){
                                                                $imagen='fa-file-word-o';
                                                                $color='bg-blue';
                                                            }
                                                            else
                                                            {
                                                                if($file->extension_archivo=='jpg' || $file->extension_archivo=='gif' || $file->extension_archivo=='jpeg' || $file->extension_archivo=='png' || $file->extension_archivo=='svg' || $file->extension_archivo=='eps' || $file->extension_archivo=='psd' ){
                                                                    $imagen='fa-file-image-o';
                                                                    $color='bg-purple';
                                                                }
                                                                else
                                                                {
                                                                    $imagen='fa-archive';
                                                                    $color='bg-orange';      
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <a data-toggle="tooltip" title="clic para descargar" target="_blank" href='{{ asset ("$file->descarga_archivo") }}' class='btn <?php echo $color; ?>'><i class="fa <?php echo $imagen; ?>"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>        
                        </div>
                        
                    </div>    
                </div>
            </div>    
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
