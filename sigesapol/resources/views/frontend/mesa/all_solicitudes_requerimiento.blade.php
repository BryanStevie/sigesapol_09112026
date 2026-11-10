<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
.table-sortable tbody tr {
  cursor: move;
}
#global {
  min-height: 650px !important;
  /*width: auto;*/
  width: 100%;
  border: 1px solid #ddd;
  margin: 15px
    /* background: #f1f1f1;*/
    /*overflow-y: scroll !important;*/
}
.margin {

  margin-bottom: 20px;
}

.margin-buscar {
  margin-bottom: 5px;
  margin-top: 5px;
}

.row {
  margin-top: 10px;
  padding: 0 10px;
}

.clickable {
  cursor: pointer;
}

.panel-heading div {
  margin-top: -18px;
  font-size: 15px;
}

.panel-heading div span {
  margin-left: 5px;
}

.panel-body {
  display: block;
}

.dataTables_filter {
  display: none;
}

.help-block {
  display: block;
  margin-top: 1px;
  margin-bottom: 10px;
  color: #737373;
}

.modal-dialog {
  width: 70% !important;
}

.modal-dialog-hc{
  width: 40% !important;
}
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
//--------------------------------------------------------------------------
$(document).ready(function() {

  $consul = $("#frmRequerimiento").find("#id_requerimiento");
  $consul.select2();

  $('#fecha_ini').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
  });
  $('#fecha_fin').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
    //"dateFormat": "YYYY-MM-DD hh:mm",
  });
  $('#fecha_ini').click(function() {
    var fechaDesdeIni = $('#fecha_ini').val();
    $('#fecha_ini').change(function() {
      var fechaDesde = $('#fecha_ini').val();
      var fechaHasta = $('#fecha_fin').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f1 > f2) {
        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
        $('#fecha_ini').val(fechaDesdeIni);
        return false;
      }
    });
  });
  $('#fecha_fin').click(function() {
    var fechaHastaIni = $('#fecha_fin').val();
    $('#fecha_fin').change(function() {
      var fechaDesde = $('#fecha_ini').val();
      var fechaHasta = $('#fecha_fin').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f2 < f1) {
        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
        $('#fecha_fin').val(fechaHastaIni);
        return false;
      }
      /*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fecha_fin').val(fechaHastaIni);
    return false;
    }
	*/
    });
  });
  $('#fecha_fin').trigger('change');
  $('#fecha_ini').trigger('change');
  var activeSystemClass = $('.list-group-item.active');

});
$(document).ready(function() {
  $('#example').DataTable({
    /*"searching": false*/
    "language": {
      "emptyTable": "No se encontraron resultados"
    }
  });
  $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
  });
  $("#iddispensador").select2({
    width: 'resolve' // need to override the changed default
  });
  $("#iddispensador").trigger('change');
});




function reporteAtencion(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var hora_ini = $('#hora_ini').val();
  var hora_fin = $('#hora_fin').val();
  fecha_ini = fecha_ini+' '+hora_ini;
  fecha_fin = fecha_fin+' '+hora_fin;
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();
  var id_prioridad = $('#id_prioridad').val();
  var id_topico = $('#id_topico').val();
  
  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;
  if (id_prioridad == "") id_prioridad = 0;
  if (id_topico == "") id_topico = 0;
  location.href = 'exportar_reporte_atencion_emergencia/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
    paciente + '/' + estado + '/' + id_prioridad + '/' + id_topico ;

}

function reporteAtencion2(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var hora_ini = $('#hora_ini').val();
  var hora_fin = $('#hora_fin').val();
  fecha_ini = fecha_ini+' '+hora_ini;
  fecha_fin = fecha_fin+' '+hora_fin;
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();
  var id_prioridad = $('#id_prioridad').val();
  var id_topico = $('#id_topico').val();
  
  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;
  if (id_prioridad == "") id_prioridad = 0;
  if (id_topico == "") id_topico = 0;
  location.href = 'exportar_cuadro_atencion_emergencia/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
    paciente + '/' + estado + '/' + id_prioridad + '/' + id_topico ;

}

function reporteAtencionDetallado(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();
  var id_prioridad = $('#id_prioridad').val();
  var id_topico = $('#id_topico').val();
  
  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;
  if (id_prioridad == "") id_prioridad = 0;
  if (id_topico == "") id_topico = 0;
  location.href = 'exportar_reporte_atencion_detallado_emergencia/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni +
    '/' + paciente + '/' + estado + '/' + id_prioridad + '/' + id_topico ;

}

function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}





function getTopico (){ 
    var id_prioridad = $('#id_prioridad').val(); 
    var sexo = $('#sexo').val();
    var edad = $('#edadpaciente').val();
    
    if(id_prioridad!=''){
      //valicion de tipo de listado
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad; 
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
        if(id_prioridad=='2'||id_prioridad=='3'){
            url_consulta='/get_topico_por_id_prioridad_sexo_edad/'+id_prioridad+'/'+sexo+'/'+edad;
        }  
       
        $.ajax({
          url: url_consulta,
          success: function(result) {   
            $('#id_topico').empty();  
            $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>"); 
            $(result).each(function(ii, oo) {  
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
            }); 
            $('#id_topico').val("").change();  
            if(id_prioridad=='1'){
                $('#id_topico').val("1").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='0'){
                $('#id_topico').val("0").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='2'){
                $('#id_topico').val("10").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='3'){
                $('#id_topico').val("11").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='4'){
                //$('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                $('#id_topico').val("19").change(); 
                $('#observacion').focus(); 
            }else{
                $('#id_topico').val("").change();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='5'){
              $('#id_topico').empty();  
              $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
              $('#id_topico').val("").change(); 
              $('#observacion').focus(); 
            }else{
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
              $('#id_topico').val("19").change(); 
              $('#observacion').focus(); 
            }
          }
        }); 
    }else{
      $('#id_topico').empty();  
      $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
      $('#id_topico').val("").change(); 
    }
  }


$(document).ready(function() {

  $('#openModalHistoria').on('hidden.bs.modal', function () {
    location.reload();
  })

  $('#btnBuscar').click(function() {
    fn_ListarBusqueda();
  });
  $("#id_prioridad").select2({
    width: 'resolve'
  });
  $("#id_topico").select2({
    width: 'resolve'
  });
  datatablenew();
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_solicitudes_paginadas",
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
      var fecha_ini = $('#fecha_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var dni = $('#dni').val();
      var prioridad_requerimiento = $('#prioridad_requerimiento').val();
      var tipo_atencion = $('#tipo_atencion').val();
      var servicio = $('#servicio').val();
      var _token = $('#_token').val();
      oSettings.jqXHR = $.ajax({
        "dataType": 'json',
        //"contentType": "application/json; charset=utf-8",
        "type": "POST",
        "url": sSource,
        "data": {
            numeroPagina: iNroPagina,
            numeroRegistros: iCantMostrar,
            fecha_ini: fecha_ini,
            fecha_fin: fecha_fin,
            dni: dni,
            prioridad_requerimiento: prioridad_requerimiento,
            tipo_atencion: tipo_atencion,
            servicio: servicio,
            _token: _token
        },
        "success": function(result) {
          fnCallback(result);
          console.log(result);
        },
        "error": function(msg, textStatus, errorThrown) {
          //location.href="login";
        }
      });
    },
    "aoColumnDefs": [
        {
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
                return row.created_at;
            },
            "bSortable": false,
            "aTargets": [1]
        },
        {
            "mRender": function(data, type, row) {
                return `${row.nombres} ${row.paterno} ${row.materno}`;
            },
            "bSortable": false,
            "aTargets": [2]
        },
        {
            "mRender": function(data, type, row) {
                const color =  row.id_prioridad_requerimiento == 1 ? 'color:red' : row.id_prioridad_requerimiento == 2 ? 'color:orange' : 'color:green';
                return `<span style="${color}"">${row.nombre_prioridad}</span>`;
            },
            "bSortable": false,
            "aTargets": [3],
            "className": 'control'
        },
        {
            "mRender": function(data, type, row) {
                return row.asunto;
            },
            "bSortable": false,
            "aTargets": [4],
            "className": 'control'
        },
        {
            "mRender": function(data, type, row) {
                return row.descripcion;
            },
            "bSortable": false,
            "aTargets": [5]
        },
        {
            "mRender": function(data, type, row) {
                let html = '';
                html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
                html += '<a href="/mesa/requerimiento/' + row.id+
                    '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
                return html;
            },
            "bSortable": false,
            "aTargets": [6],
            "className": "text-center",
        },
        {
            "mRender": function(data, type, row) {
                let html = '';
                html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Descargar PDF</b>">';
                html += '<a href="/mesa/pdf/' + row.id+
                    '" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i></a></div>';
                return html;
            },
            "bSortable": false,
            "aTargets": [7],
            "className": "text-center",
        }
    ]
  });
}

//lrojas
function fn_ListarBusqueda() {
  datatablenew();
};

function getSubConsultorio() {

  var id_establecimiento = $('#id_establecimiento').val();
  var id_servicio = $('#id_servicio').val();
  var id_tipo_atencion = $('#id_atencione').val();
  $.ajax({
    url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' +
      id_servicio+ '/' + id_tipo_atencion,
    success: function(result) {
      var newOption = "";
      var reg = result.length;
      if (reg >= 2) {
        newOption = "<option value='0'>--Seleccionar-</option>";
      }
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_consultorio').html(newOption);
      $("#id_consultorio").select2({
        max_selected_options: 4
      });

    }
  });

}

function getCargaServicioAtencion() {

  var id_establecimiento = $('#id_establecimiento').val();
  var id_tipo_atencione = $('#id_atencione').val();
  
  $.ajax({
    url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
    success: function(result) {
      var newOption = "";
      newOption = "<option value=''>--Seleccionar--</option>";
      $(result).each(function(ii, oo) {
        var sel = "";
        if (id_servicio == oo.id) sel = "selected='selected'";
        newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
      });
      $('#id_servicio').html(newOption);
      $("#id_servicio").select2({
        max_selected_options: 4
      });
    }
  });

  ocultar_datos()
}

function ocultar_datos(){
    if(document.getElementById('id_atencione').value==2){
        $('#muestra-campos').hide();
        $('#muestra-campos2').hide();
    }else 
    {
        $('#muestra-campos').show();
        $('#muestra-campos2').show();
    } 
}

function anularAtencion(id_atencion, id_emergencia) {

  bootbox.confirm({
    size: "small",
    message: "&iquest;Deseas anular la atenci&oacute;n medica del paciente, anulando esta atencion podra ser atendido por el medico?",
    callback: function(result) {
      if (result == true) {
        fn_anularAtencion(id_atencion, id_emergencia);
      }
    }
  });
}

function fn_anularAtencion(id_prestacion, id_emergencia) {

  $.ajax({
    url: '/anular_prestacion_emergencia/' + id_prestacion + '/' + id_emergencia,
    dataType: "json",
    success: function(result) {

      if (result.sw == true) {
        location.href = "all_admisiones_atendidas_by_emergencia";
      }
    }
  });
}

function anularPaciente2(id_emergencia) {

bootbox.confirm({
  size: "small",
  message: "&iquest;Deseas eliminar al paciente de la lista de atencion, ya no podra hacer atendido por ningun medico, a menos que inicie nuevamente el proceso de ingreso?",
  callback: function(result) {
    if (result == true) {
      fn_anularPaciente(id_emergencia);
    }
  }
});
}

function anularPaciente(id) {

$(".modal-dialog").css("width", "80%");
$('#openModalHistoria').modal('show');
$('#openModalHistoria .modal-body').css('height', 'auto');

$.ajax({
    url: "modal_paciente_emergencia/" + id,
    type: "GET",
    success: function(result) {
        $(".modal-historia").html(result);
    }
});

}


function fn_anularPaciente(id_emergencia) {

$.ajax({
  url: '/anular_paciente/' + id_emergencia,
  dataType: "json",
  success: function(result) {

    if (result.sw == true) {
      location.href = "all_admisiones_atendidas_by_emergencia";
    }
  }
});
}


function AsignarReceta(id_prestacion) {


  alert("el id de prestacion es " + JSON.stringify(id_prestacion));
$.ajax({
  url: '/anular_prestacion/' + id_prestacion,
  dataType: "json",
  success: function(result) {

    if (result.sw == true) {
      location.href = "all_citas_medico_atenciones";
    }
  }
});
}
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de solicitudes de requerimientos para el SIGESAPOL</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="#">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                <input value="{{ Auth::user()->dni }}" name="dni" type="hidden">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Fecha Desde</label>
                        <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
                        value="<?php echo $fechaDesde ?>" maxlength="10">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Fecha Hasta</label>
                        <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
                        value="<?php echo $fechaDesde ?>" maxlength="10">
                    </div>   
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Prioridad requerimiento</label>
                        <select id="prioridad_requerimiento" name="prioridad_requerimiento" onchange="" class="form-control input-sm">
                            <option value="">-Seleccionar-</option>
                            <option value="1">ALTA</option>
                            <option value="2">MEDIA</option>
                            <option value="3">BAJA</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Tipo de atencion</label>
                        <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion" required="">
                            <option value="">-Seleccionar-</option>
                            <option value="1">AMBULATORIO</option>
                            <option value="2">EMERGENCIA</option>
                            <option value="3">HOSPITALIZACION</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Servicio</label>
                        <select class="form-control input-sm" id="servicio" name="servicio" tabindex="5">
                            <option value="">-Seleccionar-</option>
                            @foreach ($consultorios as $consultorio)
                                <option value="{{$consultorio->id}}">{{$consultorio->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm" for="">Buscar</label>
                        <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="tblPrestacion" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Fecha y hora de solicitud</th>
                                    <th>Autor</th>
                                    <th>Prioridad</th>
                                    <th>Asunto</th>
                                    <th>Descripcion</th>
                                    <th>Revision</th>
                                    <th>Exportar</th>
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
</div>


<div id="openModalHistoria" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-hc">
        <div id="" class="modal-content" style="padding: 0px;margin: 0px;">
            <div class="modal-body modal-historia">
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
