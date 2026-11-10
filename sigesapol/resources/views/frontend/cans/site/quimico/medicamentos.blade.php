@extends('frontend.layouts.master2')
@push('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
<style type="text/css">
  .swal-wide{
    width: 1000px !important;
    padding: 0px 20px !important;
  }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
</style>
@section('renderjs')
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
$(document).ready(function() {
    datatablenew();
});

    function modalActivar(id,id_can,id_establecimiento,id_servicio){
        $("#showActivar .modal-dialog").css("width","100%");
        
        $.ajax({
            url: "../../activar_establecimiento_servicio/"+id+"/"+id_can+"/"+id_establecimiento+"/"+id_servicio+"/1",
            type: "GET",
            success: function (result) {  
                $('#diveditpreg3').html(result);
                $('#showActivar').modal('show');
            }
        });
        
    }

    function editForm(id){
        $("#showActivar .modal-dialog").css("width","80%");
        
        $.ajax({
            url: "../../edit_producto_can/"+id,
            type: "GET",
            success: function (result) {  
                $('#diveditpreg3').html(result);
                $('#showActivar').modal('show');
            }
        });
        
    }

    function eliminar(id){
      bootbox.confirm("¿Se eliminará el producto, desea continuar?", function(result) {
        if (result==true) {             
          $.ajax({
            url: '/eliminar_producto_can/'+id,
          }).done(function(res){
            $('#tblCan').DataTable().ajax.reload();
            $('#showActivar').modal('hide');
            Swal.fire({
                title: '<div class="titulo-padding"><p>Excelente</p></div>',
                html: '<p class="descripcion-alert"><span><b>se elimino correctamente.</b><br></span></p>', 
                allowOutsideClick: false,
                timer: 1500,
                icon: 'success',
                timerProgressBar: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            });
    
        })
        }
      });
       
    }

    /*function CerrarPetitorio(can_id,establecimiento_id,tipo){
      bootbox.confirm("¿Desea cerrar el petitorio del CAN..., desea continuar?", function(result) {
        if (result==true) {             
          $.ajax({
            url: '/cerrar_petitorio_can/' + can_id + '/' + establecimiento_id + '/'+ tipo,
          })
        }
      });
    }
    */
    function CerrarPetitorio(can_id,establecimiento_id,tipo) {
      bootbox.confirm({
        message: "¿Desea cerrar el petitorio del CAN..., desea continuar?",
        buttons: {
          confirm: {
            label: 'Si',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function (result) {
          if (result == true) {
            
            location.href = '/cerrar_petitorio_can/' + can_id + '/' + establecimiento_id + '/'+ tipo; 
          }
        }
      });
    }

  var m = 0;

    function datatablenew() {
        var oTable1 = $('#tblCan').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_productos_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": true,
            "bSort": false,
            "info": true,
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
          var tipo = $('#tipo').val();
          var _token = $('#_token').val();
          oSettings.jqXHR = $.ajax({
            "dataType": 'json',
            "type": "POST",
            "url": sSource,
            "data": {
              NumeroPagina: iNroPagina,
              NumeroRegistros: iCantMostrar,
              id_establecimiento: id_establecimiento,
              tipo: tipo,
              id_can: id_can,
              _token: _token
            },
            "success": function(result) {
              fnCallback(result);
            },
            "error": function(msg, textStatus, errorThrown) {
            }
          });
        },
        "aoColumnDefs": [{
            "mRender": function(data, type, row) {
              var html = "";
                html +='<table><tr><td>';
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Producto</b>">';
                html += '<a href="javascript:void(0)" onclick="editForm('+ row.id+')" class="btn btn-warning btn-xs" style="background-color: #2980b9; border: 1px solid white;"><i class="fa fa-edit" ></i></a></div></td>';
                html +='<td><div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Eliminar Producto</b>">';
                html += '<a href="javascript:void(0)" onclick="eliminar('+ row.id+')" class="btn btn-danger btn-xs"><i class="fa fa-fw fa-trash"></i></a></div></td></tr></table>';
              return html;
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
              var cpma = "";
                if (row.cpma != null) cpma = row.cpma;
                return cpma;               
                
            },
            "bSortable": false,
            "aTargets": [2],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var necesidad_anual = "";
                if (row.necesidad_anual != null) necesidad_anual = row.necesidad_anual;
                return necesidad_anual;               
                
            },
            "bSortable": false,
            "aTargets": [3],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes1 = "";
                if (row.mes1 != null) mes1 = row.mes1;
                return mes1; 
            },
            
            "bSortable": false,
            "aTargets": [4],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes2 = "";
                if (row.mes2 != null) mes2 = row.mes2;
                return mes2; 
            },
            
            "bSortable": false,
            "aTargets": [5],
            "className": "text-center",
          },
          
          {
            "mRender": function(data, type, row) {
              var mes3 = "";
                if (row.mes3 != null) mes3 = row.mes3;
                return mes3; 
            },
            "bSortable": false,
            "aTargets": [6],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes4 = "";
                if (row.mes4 != null) mes4 = row.mes4;
                return mes4; 
            },
            "bSortable": false,
            "aTargets": [7],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes5 = "";
                if (row.mes5 != null) mes5 = row.mes5;
                return mes5; 
            },
            "bSortable": false,
            "aTargets": [8],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes6 = "";
                if (row.mes6 != null) mes6 = row.mes6;
                return mes6; 
            },
            "bSortable": false,
            "aTargets": [9],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes7 = "";
                if (row.mes7 != null) mes7 = row.mes7;
                return mes7; 
            },
            "bSortable": false,
            "aTargets": [10],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes8 = "";
                if (row.mes8 != null) mes8 = row.mes8;
                return mes8; 
            },
            "bSortable": false,
            "aTargets": [11],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes9 = "";
                if (row.mes9 != null) mes9 = row.mes9;
                return mes9; 
            },
            "bSortable": false,
            "aTargets": [12],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes10 = "";
                if (row.mes10 != null) mes10 = row.mes10;
                return mes10; 
            },
            "bSortable": false,
            "aTargets": [13],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes11 = "";
                if (row.mes11 != null) mes11 = row.mes11;
                return mes11; 
            },
            "bSortable": false,
            "aTargets": [14],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var mes12 = "";
                if (row.mes12 != null) mes12 = row.mes12;
                return mes12; 
            },
            "bSortable": false,
            "aTargets": [15],
            "className": "text-center",
          }
        ]
      });
    }
</script>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <a href="/all_cans" class="btn btn-default pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Listado de Productos </h3>
                <input type="hidden" value="<?php echo $can_id?>" name="id_can" id="id_can" class="form-control">
                <input type="hidden" value="<?php echo $tipo?>" name="tipo" id="tipo" class="form-control">
                <input type="hidden" value="<?php echo $establecimiento_id?>" name="id_establecimiento" id="id_establecimiento" class="form-control">
            </div>
            <div class="panel-body">
                <!--a href="javascript:void(0)" onclick="modalActivarTodos({{$can_id}})" class="btn btn-primary" style="background-color: #001f3f; border: 1px solid white;"><span class="badge bg-yellow">{!!$numero_medicamentos!!}</span>
                              <i class="fa fa-file"></i> Nuevo </a-->
                <a href="javascript:void(0)" onclick="CerrarPetitorio({{$can_id}},{{$establecimiento_id}},{{$tipo}})" class="btn btn-danger" ><i class="fa fa-lock"></i> Cerrar Petitorio</a>
                <a class="btn btn-success" href="/descargar_avance/{{$can_id}}/{{$establecimiento_id}}/{{$tipo}}/'xlsx'/{{$valor}}">
                <i class="fa fa-file-excel-o"></i> Descargar Avance </a>
                <div class="table-responsive">
                    <table id="tblCan" class="table table-bordered">
                        <thead>
                            <tr>
                                
                                <th rowspan="2" style="text-align:center;">Editar/Eliminar</th>
                                <th rowspan="2" style="text-align:center;">Descripción</th>                                            
                                <th rowspan="2" style="text-align:center;">CPMA</th>
                                <th rowspan="2" style="text-align:center;">Necesidad</th>
                                <th colspan="12" bgcolor="#3c763d" style="text-align:center;">
                                <span style="color: white;"> PRORRATEO</span></th>
                                </tr>
                            <tr>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 1</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 2</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 3</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 4</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 5</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 6</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 7</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 8</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 9</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 10</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 11</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 12</small></span></th>
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
<div class="modal fade" id="showActivar" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg3"></div>
      </div>
    
  </div>
</div>
<!--div class="modal fade" id="showAmpliarTiempo" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg2"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showActivarTodos" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg1"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showHabilitar" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg4"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showAmpliarTiempoTotal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg5"></div>
      </div>
    
  </div>
</div-->
@include('frontend.includes.footer')
@endsection