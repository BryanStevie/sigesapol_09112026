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
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();

  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;

  if (id_atencione == "") id_atencione = 0;
  location.href = 'exportar_reporte_saludMaterna_puerperios/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
    paciente + '/' + estado ;

}

function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}


$(document).ready(function() {

  $('#openModalHistoria').on('hidden.bs.modal', function () {
    location.reload();
  })

  $('#btnBuscar').click(function() {
    fn_ListarBusqueda();
  });
  /*
  $('#example-select-all').on('click', function(){
  	var rows = $('#tblCita').DataTable().rows({ 'search': 'applied' }).nodes();
  	$('input[type="checkbox"]', rows).prop('checked', this.checked);
  	addMovimientoAll();
  });
  */
  $("#id_servicio").select2({
    width: 'resolve'
  });
  $("#id_consultorio").select2({
    width: 'resolve'
  });
  $("#id_atencione").select2({
    width: 'resolve'
  });
  datatablenew();
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_gestantes_individual_vista",
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
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nombre_beneficiario = $('#nombre_beneficiario').val();
      var estado = 1;
      var _token = $('#_token').val();
      oSettings.jqXHR = $.ajax({
        "dataType": 'json',
        //"contentType": "application/json; charset=utf-8",
        "type": "POST",
        "url": sSource,
        "data": {
          NumeroPagina: iNroPagina,
          NumeroRegistros: iCantMostrar,
          fecha_ini: fecha_ini,
          fecha_fin: fecha_fin,
          dni_beneficiario: dni_beneficiario,
          nombre_beneficiario: nombre_beneficiario,
          estado: estado,
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
          if (row.num != null) id = row.num;
          return id;
        },
        "bSortable": false,
        "aTargets": [0]
      },
      {
        "mRender": function(data, type, row) {
          var fecha_actualizacion = "";
          if (row.fecha_actualizacion != null) fecha_actualizacion = row.fecha_actualizacion;
          return fecha_actualizacion;
        },
        "bSortable": false,
        "aTargets": [1]
      },

      {
        "mRender": function(data, type, row) {
          var nro_doc_ident = "";
          if (row.dni_paciente != null) nro_doc_ident = row.dni_paciente;
          return nro_doc_ident;
        },
        "bSortable": false,
        "aTargets": [2],
        "className": 'control'
      },
      
      {
        "mRender": function(data, type, row) {
          var asegurado = "";
          if (row.nombre_paciente != null) asegurado += row.nombre_paciente;
          if (row.paterno_paciente != null) asegurado += ", " + row.paterno_paciente;
          if (row.materno_paciente != null) asegurado += " " + row.materno_paciente;
          return asegurado;
        },
        "bSortable": false,
        "aTargets": [3]
      },
  
      {
        "mRender": function(data, type, row) {
          var atencion = "";
          if (row.id_secuencia_estadios_salud_materna == '1') atencion = 'CONSULTA EXTERNA';
          if (row.id_secuencia_estadios_salud_materna != '1') atencion = ' EMERGENCIA';
          return atencion;
        },
        "bSortable": false,
        "aTargets": [4]
      },

      {
        "mRender": function(data, type, row) {
          var atencion = "";
          if (row.estado_salud_materna == '2') atencion = 'GESTANTE';
          if (row.estado_salud_materna == '3') atencion = 'PUERPERA';
          return atencion;
        },
        "bSortable": false,
        "aTargets": [5]
      },
      
      {
        "mRender": function(data, type, row) {
          var html = "";
           
              html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            html += '<a href="/hc_electronica_salud_materna/' + row.id_asegurado +
              '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
           
          return html;
        },
        "bSortable": false,
        "aTargets": [6],
        "className": "text-center",
      },
      
     
    ]
  });
}

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

function anularAtencion(id_atencion) {

  bootbox.confirm({
    size: "small",
    message: "&iquest;Deseas anular la atenci&oacute;n medica?",
    callback: function(result) {
      if (result == true) {
        fn_anularAtencion(id_atencion);
      }
    }
  });
}

function fn_anularAtencion(id_prestacion) {

  $.ajax({
    url: '/anular_prestacion/' + id_prestacion,
    dataType: "json",
    success: function(result) {

      if (result.sw == true) {
        location.href = "all_atenciones_puerpera";
      }
    }
  });
}

function modalHistoria(id) {
	$('#openModalHistoria').modal('show');

  $.ajax({
    url: "modal_historia/" + id,
    type: "GET",
    success: function(result) {
      $("#diveditpregOpc").html(result);
    }
  });
}
</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Salud Materna - {{$farmacia->nombre}}</h3>
    </div>
    <div class="panel-body">

      <form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_atenciones')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">

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
            <label class="form-control-sm">Nro. Documento</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
          </div>

          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-control-sm">Nombres � Apellidos</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
          </div>

          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
            <div class="col-md-6">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencion('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
            <div class="col-md-6">
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>
          </div> 

        </div>

      </form>
      <div class="col-md-12">
        <!--<table  class="table table-list-search">-->
        <!--<table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >-->
        <div class="table-responsive">
        <table id="tblPrestacion" class="table table-bordered">
            <thead>
              <tr>
                <th>N</th>
                <th>Fecha y Hora</th>
               

                <th>Numero Doc</th>
                <th>Paciente</th>
             
                <th>Servicio</th>
                <th>Estado Paciente</th>
                <th style="text-align:center">Atencion</th>
                
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
      	<div class="modal-body">
      		<div id="diveditpregOpc"></div>
      	</div>
    </div>
  </div>
</div>


<div class="modal fade" id="myModalDetRV" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
      </div>
      <div class="modal-body">
        <div id="muestra-det-receta"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div id="link-reporte"></div>

@include('frontend.includes.footer')
@endsection