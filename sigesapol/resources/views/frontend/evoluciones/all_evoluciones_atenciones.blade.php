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

function ver_det_receta(idUser, nro_receta, id_receta) {
  $('#nrorecmodal').text(nro_receta);
  $.ajax({
    url: '/ver_receta_atendida/2/' + idUser + '/' + nro_receta + '/' + id_receta,
    async: false,
    success: function(result) {
      $("#muestra-det-receta").html(result);
    },
    complete: function() {
      $("#myModalDetRV").modal();
    }
  });
}

function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}


function firmar() {
  //var rowindex = $('#Grd2 tbody tr td').length;
  //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
  //if (rowindex > 0) {

  $.ajax({
      url: "genera_csv",
      type: "post",
      //data: p,
      data: $("#frmBloque").serialize(),
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      //alert(resultado.sw);
      if (resultado.sw) {
        var csv = "<?php echo url() ?>/tmp/csv/firmar.csv";
        location.href = 'bpsign:?batch_csv=' + csv;
        //validarFirma();
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function() {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
    })
    .always(function() {
      //bootbox.hideAll();
    });

  //} else {

  //dialogWarning("Debe hacer check en minimo una solicitud");
  //}	


}

function validarFirma() {

  $('.loader').show();
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "../expedientes/validarFirma",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          $('.loader').hide();
          cargarSolicitudes();
          $('#Grd2 tbody tr').html("");
          $('#spanContador').html("");
          clearInterval(batchSignatureProcessPoller);

        }
      }
    });
  }, 3000);


}

$(document).ready(function() {

  $('#openModalHistoria').on('hidden.bs.modal', function () {
    location.reload();
  })

  $('#btnBuscar').click(function() {
    fn_ListarBusqueda();
  });
  
  datatablenew();
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_evolucion_ajax",
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
      var estado = $('#estado').val();
      var id_servicio = $('#id_servicio').val();
      var id_paciente = $('#id_paciente').val();
      var id_medico = $('#id_medico').val();
      var id_establecimiento = $('#id_establecimiento').val();
      var id_atencione = $('#id_atencione').val();;
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
          estado: estado,
          id_establecimiento: id_establecimiento,
          id_servicio: id_servicio,
          id_paciente: id_paciente,
          id_medico: id_medico,
          id_atencione: id_atencione,
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
          var fecha_atencion = "";
          if (row.fecha_atencion != null) fecha_atencion = row.fecha_atencion;
          return fecha_atencion;
        },
        "bSortable": false,
        "aTargets": [1]
      },
      {
        "mRender": function(data, type, row) {
          var horario = "";
          if (row.horario != null) horario = row.horario;
          return horario;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          var servicio = "";
          if (row.servicio != null) servicio = row.servicio;
          return servicio;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var medico = "";
          if (row.medico_paterno != null) medico += row.medico_paterno;
          if (row.medico_materno != null) medico += " " + row.medico_materno;
          if (row.medico_nombre != null) medico += " ," + row.medico_nombre;
          return medico;
        },
        "bSortable": false,
        "aTargets": [4]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            html += '<a href="/ver_evolucion/<?php echo Auth::user()->id ?>/' + row.id +
              '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [5],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";

          if (row.ruta_evolucion != null && row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            html += '<a href="/' + row.flag + '/' + row.ruta_evolucion +
              '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
          } else {
            html +=
              '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [6],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_receta != null && row.id_estado_reg == 1) {
            var ruta_receta = row.ruta_receta.replace("{", "").replace("}", "");
            var ruta = ruta_receta.split(",");
            let flag_receta = row.flag_receta.replace("{", "").replace("}", "");
            let flag_firma = flag_receta.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [7],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_laboratorio != null && row.id_estado_reg == 1) {
            var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
            var ruta = ruta_laboratorio.split(",");
            let flag_laboratorio = row.flag_laboratorio.replace("{", "").replace("}", "");
            let flag_firma = flag_laboratorio.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [8],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_radiologia != null && row.id_estado_reg == 1) {
            var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
            var ruta = ruta_radiologia.split(",");
            let flag_radiologia = row.flag_radiologia.replace("{", "").replace("}", "");
            let flag_firma = flag_radiologia.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [9],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_interconsulta != null && row.id_estado_reg == 1) {
            var ruta_interconsulta = row.ruta_interconsulta.replace("{", "").replace("}", "");
            var ruta = ruta_interconsulta.split(",");
            let flag_interconsulta = row.flag_interconsulta.replace("{", "").replace("}", "");
            let flag_firma = flag_interconsulta.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [10],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_referencia != null && row.id_estado_reg == 1) {
            var ruta_referencia = row.ruta_referencia.replace("{", "").replace("}", "");
            var ruta = ruta_referencia.split(",");
            let flag_referencia = row.flag_referencia.replace("{", "").replace("}", "");
            let flag_firma = flag_referencia.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Referencia</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Referencia</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [11],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_prestacion == null && row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
            html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
              .id + ')"><i class="fa fa-remove"></i></a></div>';
          } else {
            html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [12],
        "visible": <?php echo ($flagemergenciareferencia == 1) ? "false" : "true" ?>,
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
  //var id_tipo_atencion = $('#id_tipo_atencion').val();
  $.ajax({
    url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' +
      id_servicio,
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
        location.href = "all_citas_medico_atenciones";
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
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Evoluciones Medicas de un Paciente - {{$establecimientos[0]->nombre}}</h3>
      @if($id_atencione==2)
        <div class="pull-right">
          <a href="/all_pacientes_evoluciones_by_emergencia" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
        </div>
      @else
        <div class="pull-right">
          <a href="/hospitalizado_by_evolucion" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
        </div>
      @endif
    </div>
    <div class="panel-body">
      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center"><br/>
            <b>INFORMACIÓN DEL PACIENTE</b>
          </div>
        </div>
        <div class="panel-body" id="collapseprestacionespaciente">
          <div class="form-group">
            <div class="col-md-2">
              <label for="tipo">TIPO DE DOCUMENTO</label>
              <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->tipo_doc_ident?>" name="tipodoc_beneficiario_bus"
              id="tipodoc_beneficiario_bus" style="text-align:left">
              <input type="hidden" value="<?php echo $paciente->tipo_doc_ident?>" name="tipodoc_beneficiario"
              id="tipodoc_beneficiario" class="form-control">
              <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular"
              class="form-control">
            </div>
            <div class="col-md-2">
              <label for="dni_beneficiario">N° DE DOCUMENTO</label>
              <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->nro_doc_ident?>" name="dni_beneficiario"
              id="dni_beneficiario" style="text-align:left">
              <input type="hidden" class="form-control" value="" name="beneficiario"
              id="beneficiario" style="text-align:left">
              <input type="hidden" class="form-control" value="" name="cip_beneficiario"
              id="cip_beneficiario" readonly="" />
              <input type="hidden" class="form-control" value="<?php echo $paciente->nro_doc_ident?>" name="nrodocafiliado"
              id="nrodocafiliado" readonly="" />
              <input type="hidden" class="form-control" value="" name="nrodoctitular"
              id="nrodoctitular" readonly="" />
            </div>
            <div class="col-md-2">
              <label>N° HISTORIA CLINICA</label>
              <input <?php echo ($paciente->nro_historia!="")?"readonly='readonly'":""?>
              type="text" class="form-control" value="<?php echo $paciente->nro_historia?>"
              name="nro_historia" id="nro_historia" style="text-align:left">
            </div>
            <div class="col-md-2">
            <label for="dni_beneficiario">SEXO</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->sexo?>" name="nomsexo" id="nomsexo"
              style="text-align:left">
          </div>
          <div class="col-md-4">
            <label for="tipo">NOMBRE DE LA IPRESS</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->establecimiento?>" name="cc" id="cc"
            style="text-align:left">
            <input  value="<?php echo $id_paciente?>" type="hidden" name="id_paciente" id="id_paciente" class="form-control">
            <input  value="<?php echo $estado?>" type="hidden" name="estado" id="estado" class="form-control">
            <input  value="<?php echo $id_atencione?>" type="hidden" name="id_atencione" id="id_atencione" class="form-control">
            <input  value="<?php echo $dni_beneficiario?>" type="hidden" name="dni_beneficiario" id="dni_beneficiario" class="form-control">
            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$farmacia->id_establecimiento}}">
            <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico ?>">
            <input type="hidden" name="fecha_ini" id="fecha_ini" value="<?php echo $fechaDesde ?>">
            <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fechaHasta ?>">
            <input type="hidden" name="id_servicio" id="id_servicio" value="0">

        </div>
        </div>
        <div class="form-group">
          <div class="col-md-2"><br/>
            <label>APELLIDO PATERNO</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->asegurado_paterno?>" name="paterno_beneficiario"
              id="paterno_beneficiario" style="text-align:left">
            <input type="hidden" class="form-control" value="" name="nombre_titular"
              id="nombre_titular" />
            <input type="hidden" class="form-control" value="" name="paterno_titular"
              id="paterno_titular" />
            <input type="hidden" class="form-control" value="" name="materno_titular"
              id="materno_titular" />
          </div>
          <div class="col-md-2"><br/>
            <label>APELLIDO MATERNO</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->asegurado_materno?>" name="materno_beneficiario"
              id="materno_beneficiario" style="text-align:left">
          </div>
          <div class="col-md-3"><br/>
            <label>NOMBRES</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->asegurado_nombre?>" name="nombre_beneficiario"
              id="nombre_beneficiario" style="text-align:left">
          </div>
          <div class="col-md-2"><br/>
            <label>FECHA DE NACIMIENTO</label>
            <input readonly="" type="text" class="form-control"
              value="<?php echo $paciente->fecha_nac?>" name="fecnacafiliado"
              id="fecnacafiliado" style="text-align:left">
          </div>
          <div class="col-md-3"><br/>
            <label>PARENTESCO &nbsp;</label>
            <input readonly="" value="<?php echo $paciente->parentesco?>" type="text"
              name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
          </div>
        </div>
        
          <div class="form-group">
            <div class="col-md-3"><br/>
              <label>GRADO &nbsp;</label>
              <input readonly="" value="<?php //echo $paciente->grado?>" type="text" name="grado"
                id="grado" class="form-control">
            </div>
            @if($id_atencione==3)
            <div class="col-md-2"><br/>
              <label for="tipo">PABELLON</label>
              <input readonly="" type="text" class="form-control"
                value="<?php echo $paciente->pabellon?>" name="cc" id="cc"
                style="text-align:left">
            </div>
            <div class="col-md-2"><br/>
              <label for="dni_beneficiario">PISO</label>
              <input readonly="" type="text" class="form-control"
                value="<?php echo $paciente->piso?>" name="cc" id="cc"
                style="text-align:left">
            </div>
            <div class="col-md-2"><br/>
              <label>CUARTO</label>
              <input readonly="" type="text" class="form-control"
                value="<?php echo $paciente->cuarto?>" name="cc" id="cc"
                style="text-align:left">
            </div>
            <div class="col-md-2"><br/>
              <label>CAMA</label>
              <input readonly="" type="text" class="form-control"
                value="<?php echo $paciente->cama?>" name="cc" id="cc"
                style="text-align:left">
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="table-responsive">
          <table id="tblPrestacion" class="table table-bordered">
            <thead>
              <tr>
                <th>N</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Servicio</th>
                <th>Medico</th>
                <th style="text-align:center">Evolucion</th>
                <th style="text-align:center">Pdf Atencion</th>
                <th style="text-align:center;min-width:106px">Receta</th>
                <th style="text-align:center">Lab</th>
                <th style="text-align:center">Rad</th>
                <th style="text-align:center">Interconsultas</th>
                <th style="text-align:center">Ref</th>
                <th>Anular</th>
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
