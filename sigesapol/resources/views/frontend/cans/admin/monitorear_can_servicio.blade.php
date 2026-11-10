@extends('frontend.layouts.master2')
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

    function modalActivarTodos(id_can,id_establecimiento){
        $("#showActivarTodos .modal-dialog").css("width","100%");
        
        $.ajax({
            url: "../../activar_todo_establecimiento_servicio/"+id_can+"/"+id_establecimiento,
            type: "GET",
            success: function (result) {  
                $('#diveditpreg1').html(result);
                $('#showActivarTodos').modal('show');
            }
        });
        
    }

    

    function modalHabilitar(id,id_can,id_establecimiento,id_servicio){
        $("#showHabilitar .modal-dialog").css("width","30%");
        
        $.ajax({
            url: "../../activar_establecimiento_servicio/"+id+"/"+id_can+"/"+id_establecimiento+"/"+id_servicio+"/2",
            type: "GET",
            success: function (result) {  
                $('#diveditpreg4').html(result);
                $('#showHabilitar').modal('show');
            }
        });
        
    }

    function modalAmpliarTiempo(id,id_can,id_establecimiento,id_servicio){
        $("#showAmpliarTiempo .modal-dialog").css("width","30%");
        
        $.ajax({
            url: "../../ampliar_tiempo_establecimiento_servicio/"+id+"/"+id_can+"/"+id_establecimiento+"/"+id_servicio,
            type: "GET",
            success: function (result) {  
                $('#diveditpreg2').html(result);
                $('#showAmpliarTiempo').modal('show');
            }
        });
        
    }

    function modalAmpliarTiempoTotal(id_can,id_establecimiento){
        $("#showAmpliarTiempoTotal .modal-dialog").css("width","30%");
        
        $.ajax({
            url: "../../ampliar_tiempo_total_establecimiento/"+id_can+"/"+id_establecimiento,
            type: "GET",
            success: function (result) {  
                $('#diveditpreg5').html(result);
                $('#showAmpliarTiempoTotal').modal('show');
            }
        });
        
    }

var m = 0;

    function datatablenew() {
        var oTable1 = $('#tblCan').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_can_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
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
          var accion = 2;
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
              accion: accion,
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
              var nombre = "";
              if (row.nombre != null) nombre = row.nombre;
              return nombre;
            },
            "bSortable": false,
            "aTargets": [1]
          },
          {
            "mRender": function(data, type, row) {
              var html = "";
                if (row.habilita_medicamento == 1) {
                    if (row.medicamento_cerrado == 1) {
                        html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Medicamento</b>">';
                        html += '<a href="/productos_servicios_can/' + row.id_can +'/'+ row.id_establecimiento +'/'+ row.id_servicio + '/1' + '" class="btn btn-success btn-xs"><i class="fa fa-medkit"></i></a></div>';
                    } else {
                        html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Medicamento</b>">';
                        html += '<a href="/productos_servicios_can/' + row.id_can +'/'+ row.id_establecimiento +'/'+ row.id_servicio + '/1' + '" class="btn btn-danger btn-xs"><i class="fa fa-medkit"></i></a></div>';
                    }
                }else
                {
                        html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Medicamento</b>">';
                        html += 'NH</div>';
                }                
                return html;
            },
            "bSortable": false,
            "aTargets": [2],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var html = "";
                if (row.habilita_dispositivo == 1) {

                    if (row.dispositivo_cerrado == 1) {
                        html +=
                            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Dispositivos</b>">';
                        html += '<a href="/productos_servicios_can/' + row.id_can +'/'+ row.id_establecimiento +'/'+ row.id_servicio + '/2' + '" class="btn btn-success btn-xs"><i class="fa fa-stethoscope"></i></a></div>';
                    } else {
                            html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Dispositivos</b>">';
                            html += '<a href="/productos_servicios_can/' + row.id_can +'/'+ row.id_establecimiento +'/'+ row.id_servicio + '/2' + '" class="btn btn-danger btn-xs"><i class="fa fa-stethoscope"></i></a></div>';
                    }
                }else
                {
                    html +=
                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Dispositivos</b>">';
                    html += 'NH</div>';
                }

                return html;
            },
            "bSortable": false,
            "aTargets": [3],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var html = "";
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Activar/Desactivar</b>">';
                html += '<a href="javascript:void(0)" onclick="modalActivar('+ row.id+','+ row.id_can +','+ row.id_establecimiento +','+ row.id_servicio +')" class="btn btn-warning btn-xs" style="background-color: #001f3f; border: 1px solid white;"><i class="fa fa-chain" ></i></a></div>';
              return html;
            },
            
            "bSortable": false,
            "aTargets": [4],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var html = "";
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Habilitar/Deshabilitar</b>">';
                html += '<a href="javascript:void(0)" onclick="modalHabilitar('+ row.id+','+ row.id_can +','+ row.id_establecimiento +','+ row.id_servicio +')" class="btn btn-warning btn-xs" style="background-color: #00BFFF; border: 1px solid white;"><i class="fa fa-fw fa-thumbs-up"></i></a></div>';
              return html;
            },
            
            "bSortable": false,
            "aTargets": [5],
            "className": "text-center",
          },
          
          {
            "mRender": function(data, type, row) {
              var html = "";
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Archivos</b>">';
                html += '<a href="/listar_archivos_servicios_can/' + row.id_can+'/'+ row.id_establecimiento +'/'+ row.id_servicio +'/1' +'" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a></div>';
              return html;
            },
            "bSortable": false,
            "aTargets": [6],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var html = "";
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ampliar Tiempo</b>">';
                html += '<a href="javascript:void(0)" onclick="modalAmpliarTiempo('+ row.id+','+ row.id_can +','+ row.id_establecimiento +','+ row.id_servicio +')" class="btn btn-danger btn-xs"><i class="fa fa-fw fa-clock-o"></i></a></div>';
              return html;
            },
            "bSortable": false,
            "aTargets": [7],
            "className": "text-center",
          },
          {
            "mRender": function(data, type, row) {
              var fin_tiempo = "";
              if (row.fin_first_login != null) fin_tiempo = row.fin_first_login;
              return fin_tiempo;
            },
            "bSortable": false,
            "aTargets": [8],
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
        <a href="/monitorear_can/{{$id_can}}" class="btn btn-default pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Lista de servicios del {{$nombre_establecimiento}} - {{$can->nombre_can}}</h3>
                <input type="hidden" value="<?php echo $id_can?>" name="id_can" id="id_can" class="form-control">
                <input type="hidden" value="<?php echo $id_establecimiento?>" name="id_establecimiento" id="id_establecimiento" class="form-control">

            </div>
            <div class="panel-body">

                <a href="javascript:void(0)" onclick="modalActivarTodos({{$id_can}},{{$id_establecimiento}})" class="btn btn-primary" style="background-color: #001f3f; border: 1px solid white;"><i class="fa fa-chain"></i> Act/Des </a>
                <a href="javascript:void(0)" onclick="modalAmpliarTiempoTotal({{$id_can}},{{$id_establecimiento}})" class="btn btn-danger" ><i class="fa fa-clock-o"></i> Ampliar Tiempo </a>
                <div class="table-responsive">
                    <table id="tblCan" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Descripcion</th>
                                <th>Med.</th>
                                <th>Disp.</th>
                                <th>Act/Des</th>
                                <th>Hab/Desh</th>                                
                                <th>Archivos</th>
                                <th>Ampliar Tiempo</th>
                                <th>Finaliza CAN</th>
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
<div class="modal fade" id="showAmpliarTiempo" role="dialog" aria-labelledby="myModalLabel">
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
</div>

@include('frontend.includes.footer')
@endsection