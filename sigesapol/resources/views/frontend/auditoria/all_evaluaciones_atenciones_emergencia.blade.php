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

.swal2-icon.swal2-warning {
    font-size: 15px;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
function ver_det_evaluacion(id, id_tipo_atencion){
    $.ajax({
    url: '/mostrar_evaluacion_auditoria/' + id+"/"+id_tipo_atencion,
            async: false,
            success: function(result){
            $("#muestra-det-evaluacion").html(result);
            },
            complete: function () {
            $("#myModalEvolucion").modal();
            }
    });
}
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

function modalHistorial(){
  var id_principal = $("#id_paciente").val();
  
  $.ajax({
      url: "../../../modal_historial_evaluaciones_atenciones/"+id_principal+'/'+2,
      type: "GET",
      success: function (result) {  
          $("#divHistorial").html(result);
          $('#tablemodal1').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

$(document).ready(function() {
  modalHistorial();
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
    "sAjaxSource": "/listar_evaluacion_ajax",
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
      var dni_beneficiario = $('#beneficiario_dni').val();
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
          estado: estado,
          id_paciente: id_paciente,
          id_medico: id_medico,
          id_atencione: id_atencione,
          dni_beneficiario: dni_beneficiario,
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
          var fecha_evaluacion = "";
          if (row.fecha_evaluacion != null) fecha_evaluacion = row.fecha_evaluacion;
          return fecha_evaluacion;
        },
        "bSortable": false,
        "aTargets": [1]
      },
      {
        "mRender": function(data, type, row) {
          var hora_evaluacion = "";
          if (row.hora_evaluacion != null) hora_evaluacion = row.hora_evaluacion;
          return hora_evaluacion;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          var prioridad = "";
          if (row.prioridad != null) prioridad = row.prioridad;
          return prioridad;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var topico = "";
          if (row.topico != null) topico = row.topico;
          return topico;
        },
        "bSortable": false,
        "aTargets": [4]
      },
      {
        "mRender": function(data, type, row) {
          var medico = "";
          if (row.apepaterno_profesional != null) medico += row.apepaterno_profesional;
          if (row.apematerno_profesional != null) medico += " " + row.apematerno_profesional;
          if (row.nombre_profesional != null) medico += " ," + row.nombre_profesional;
          return medico;
        },
        "bSortable": false,
        "aTargets": [5]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
           
             html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Evaluación">';
            html += '<a href="javascript:void(0)" onClick="ver_det_evaluacion(\'' + row.id+ '\', \'' + row.id_tipo_atencion + '\')" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';                                    
           
       
            // html +=
            //   '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluacion</b>">';
            // html += '<a href="/ver_evaluacion/<?php echo Auth::user()->id ?>/' + row.id +
            //   '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [6],
        "className": "text-center",
      },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";

    //       if (row.ruta_evaluacione != null && row.id_estado_reg == 1) {
    //         var ruta_evaluacione = row.ruta_evaluacione.replace("{", "").replace("}", "");
    //         var ruta = ruta_evaluacione.split(",");
    //         let flag_evaluacione = row.flag_evaluacione.replace("{", "").replace("}", "");
    //         let flag_firma = flag_evaluacione.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             //if(row.status_token==1){
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [7],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_receta != null && row.id_estado_reg == 1) {
    //         var ruta_receta = row.ruta_receta.replace("{", "").replace("}", "");
    //         var ruta = ruta_receta.split(",");
    //         let flag_receta = row.flag_receta.replace("{", "").replace("}", "");
    //         let flag_firma = flag_receta.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             //if(row.status_token==1){
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [8],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_laboratorio != null && row.id_estado_reg == 1) {
    //         var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
    //         var ruta = ruta_laboratorio.split(",");
    //         let flag_laboratorio = row.flag_laboratorio.replace("{", "").replace("}", "");
    //         let flag_firma = flag_laboratorio.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             //if(row.status_token==1){
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [9],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_radiologia != null && row.id_estado_reg == 1) {
    //         var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
    //         var ruta = ruta_radiologia.split(",");
    //         let flag_radiologia = row.flag_radiologia.replace("{", "").replace("}", "");
    //         let flag_firma = flag_radiologia.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             //if(row.status_token==1){
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [10],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_interconsulta != null && row.id_estado_reg == 1) {
    //         var ruta_interconsulta = row.ruta_interconsulta.replace("{", "").replace("}", "");
    //         var ruta = ruta_interconsulta.split(",");
    //         let flag_interconsulta = row.flag_interconsulta.replace("{", "").replace("}", "");
    //         let flag_firma = flag_interconsulta.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '"  class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [11],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_rpta_interconsulta != null && row.id_estado_reg == 1) {
    //         var ruta_interconsulta = row.ruta_rpta_interconsulta.replace("{", "").replace("}", "");
    //         var ruta = ruta_interconsulta.split(",");
    //         let flag_interconsulta = row.flag_rpta_interconsulta.replace("{", "").replace("}", "");
    //         let flag_firma = flag_interconsulta.split(",");

    //         for (var r = 0; r < ruta.length; r++) {
    //           if (ruta[r] != "NULL") {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
    //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
    //               '"  class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
    //           } else {
    //             html +=
    //               '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
    //           }
    //         }
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [12],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
    //   {
    //     "mRender": function(data, type, row) {
    //       var html = "";
    //       if (row.ruta_evaluacion == null  && row.id_estado_reg == 1) {
    //         if (row.estado ==3 ) {
    //         html +=
    //           '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
    //         html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
    //           .id + ')"><i class="fa fa-remove"></i></a></div>';
    //         }
    //         else{
    //           html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
    //         }
    //       } else {
    //         html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
    //       }
    //       return html;
    //     },
    //     "bSortable": false,
    //     "aTargets": [13],
    //     "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
    //     "className": "text-center",
    //   },
      {
        "mRender": function(data, type, row) {
          let htmlString = "";
          if (row.cod_siteds_susalud) {
              if (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === "T" || String(row.cod_siteds_susalud).length <= 6) {
                  htmlString = `
                          <p data-toggle="tooltip" data-placement="top" title="Código Siteds Temporal">
                              <span class="label label-warning" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud).toUpperCase()}</span>
                          </p>
                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Regularizar</b>" class="contenido-btn-div">
                              <button class="btn btn-primary btn-xs" onclick="regularizeCodeSitedsEvaluacion(${row.id}, '${row.fecha_atencion}')">
                                  <i class="fa fa-check-circle-o"></i> 
                              </button>
                          </div>`;
              } else {
                  htmlString = `<span class="label label-success" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud)}</span>`
              }
          }
          return htmlString;
        },
        "bSortable": false,
        "aTargets": [7],
        // "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
    ]
  });
}

function fn_ListarBusqueda() {
  datatablenew();
};

  function regularizeCodeSitedsEvaluacion(id, fechaAtencion, nuAutorizacion = null) {
    Swal.close();
    Swal.fire ({
      title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO CÓDIGO SITEDS</span></div>",
      html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false, showConfirmButton: false, allowOutsideClick: false, allowEscapeKey: false,
      onBeforeOpen: () => Swal.showLoading()
    })

    const id_servicio = $('#id_servicio').val();
    const id_establecimiento = $('#id_establecimiento').val();
    const dateAttention = moment(fechaAtencion, "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD");
    const body = {
      "recordId": id,
      "moduleId": 4,
      "dateAttention": dateAttention,
      "establecimientoId": id_establecimiento,
      "tipoAtencionId": "2",
      "servicioId": id_servicio,
    };
    if (nuAutorizacion) {
      body.nuAutorizacion = nuAutorizacion;
      body.flagGenerateOutOfDate = true;
    }
    $.ajax({
      type: "POST",
      url: "/regularize-multiple-siteds-temporary",
      data: JSON.stringify(body),
      contentType: "application/json",
      dataType: "json",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        const messageSuccess = response.message || "¡La regularización de los códigos SITEDS temporales se completó con éxito!"
        Swal.fire({
          icon: 'success',
          title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
          html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageSuccess}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
        });
        modalHistorial();
        datatablenew();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        const errorStatus = jqXHR.status || 500;
        let messageError = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";

        const session = {!! json_encode(Auth::user()) !!}
        let hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;

        if (parseInt(errorStatus) !== 500 && hasPermissionUpdateSitedsManual) {
            messageError += "<br><br><span style='font-size: 15px; color: #d9534f'>Sin embargo, puedes ingresar manualmente al SITEDS, pero recuerda que cualquier acción será bajo tu responsabilidad.</span>";
        }

        if (errorStatus && parseInt(errorStatus) === 500) {
          hasPermissionUpdateSitedsManual = 0;
        }

        Swal.fire({
            icon: 'warning',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
            showCancelButton: true,
            showConfirmButton:  hasPermissionUpdateSitedsManual,
            cancelButtonText: hasPermissionUpdateSitedsManual ? "CANCELAR" : "ENTENDIDO",
            confirmButtonColor: "#5cb85c",
            cancelButtonColor: "#d9534f",
            confirmButtonText: "INGRESAR MANUALMENTE",
            allowOutsideClick: false,
            allowEscapeKey: false 
        }).then((result) => {
            if (result.isConfirmed) {
              const session = {!! json_encode(Auth::user()) !!}
              const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
              showModalSitedsManual().then((resultModal) => {
                if (resultModal && resultModal.isConfirmed && resultModal.value) {
                  regularizeCodeSitedsEvaluacion(id, fechaAtencion, resultModal.value)
                }
              })
            }
        });
      },
      complete: function(jqXHR, status) {
          Swal.hideLoading();
      }
    });
  }

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
    message: "&iquest;Deseas anular la evaluaci&oacute;n medica?",
    callback: function(result) {
      if (result == true) {
        fn_anularAtencion(id_atencion);
      }
    }
  });
}

function fn_anularAtencion(id_evaluacion) {
  var id_paciente = $('#id_paciente').val();
  $.ajax({
    url: '/anular_evaluacion/' + id_evaluacion,
    dataType: "json",
    success: function(result) {

      if (result.sw == true) {
        location.href = "/ver_atenciones_evaluacion/"+id_paciente+ "/3/2";
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
      <h3 class="panel-title">Lista de Evaluaciones Medicas de un Paciente - {{$establecimientos[0]->nombre}}</h3>
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
              <p><?php echo $paciente->tipo_doc_ident?></p>
              <input type="hidden" value="<?php echo $paciente->tipo_doc_ident?>" name="tipodoc_beneficiario"
              id="tipodoc_beneficiario" class="form-control">
              <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular"
              class="form-control">
            </div>
            <div class="col-md-2">
              <label for="dni_beneficiario">N° DE DOCUMENTO</label>
              <p><?php echo $paciente->nro_doc_ident?></p>
              <input type="hidden" class="form-control" value="" name="beneficiario"
              id="beneficiario" style="text-align:left">
              <input type="hidden" class="form-control" value="<?php echo $paciente->nro_doc_ident?>" name="beneficiario_dni"
              id="beneficiario_dni" style="text-align:left">
              <input type="hidden" class="form-control" value="" name="cip_beneficiario"
              id="cip_beneficiario" readonly="" />
              <input type="hidden" class="form-control" value="<?php echo $paciente->nro_doc_ident?>" name="nrodocafiliado"
              id="nrodocafiliado" readonly="" />
              <input type="hidden" class="form-control" value="" name="nrodoctitular"
              id="nrodoctitular" readonly="" />
            </div>
            <div class="col-md-2">
              <label>N° HISTORIA CLINICA</label>
              <p><?php echo $paciente->nro_historia?></p>
            </div>
            <div class="col-md-2">
            <label for="dni_beneficiario">SEXO</label>
            <p><?php echo $paciente->sexo?></p>
          </div>
          <div class="col-md-4">
            <label for="tipo">NOMBRE DE LA IPRESS</label>
            <p><?php echo $paciente->establecimiento?></p>
            <input  value="<?php echo $id_atencion?>" type="hidden" name="id_paciente" id="id_paciente" class="form-control">
            <input  value="<?php echo $estado?>" type="hidden" name="estado" id="estado" class="form-control">
            <input  value="<?php echo $id_atencione?>" type="hidden" name="id_atencione" id="id_atencione" class="form-control">
            <input  value="<?php //echo $dni_beneficiario?>" type="hidden" name="dni_beneficiario" id="dni_beneficiario" class="form-control">
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
            <p><?php echo $paciente->asegurado_paterno?></p>
            <input type="hidden" class="form-control" value="" name="nombre_titular"
              id="nombre_titular" />
            <input type="hidden" class="form-control" value="" name="paterno_titular"
              id="paterno_titular" />
            <input type="hidden" class="form-control" value="" name="materno_titular"
              id="materno_titular" />
          </div>
          <div class="col-md-2"><br/>
            <label>APELLIDO MATERNO</label>
            <p><?php echo $paciente->asegurado_materno?></p>
          </div>
          <div class="col-md-2"><br/>
            <label>NOMBRES</label>
            <p><?php echo $paciente->asegurado_nombre?></p>
          </div>
          <div class="col-md-2"><br/>
            <label>FECHA DE NACIMIENTO</label>
            <p><?php echo $paciente->fecha_nac?></p>
          </div>
          <div class="col-md-4"><br/>
            <label>PARENTESCO &nbsp;</label>
            <p><?php echo $paciente->parentesco?></p>
          </div>
        </div>
        
          <div class="form-group">
            <div class="col-md-2"><br/>
              <label>GRADO &nbsp;</label>
              <p><?php echo $paciente->grado?></p>
            </div>
            <div class="col-md-2">
              <label for="tipo">PABELLON</label>
              <p><?php echo $datoadicional->pabellon?></p>
            </div>
            <div class="col-md-2">
              <label for="dni_beneficiario">PISO</label>
              <p><?php echo $datoadicional->piso?></p>
            </div>
            <div class="col-md-2">
              <label>HABITACION</label>
              <p><?php echo $datoadicional->cuarto?></p>
            </div>
            <div class="col-md-4">
              <label>CAMA</label>
              <p><?php echo $datoadicional->cama?></p>
            </div>
          </div>
          <div class="form-group">
            @if($tipo_parentesco != 'SOLO') 
              <div class="col-md-2"><br/>
                <label>CONDUCIDO POR:</label><br/>
                {{$tipo_parentesco}}
              </div>
              <div class="col-md-4"><br/>
                <label>Nro Doc y Nombres:</label><br/>
                {{ $paciente->dni_conducido }} - {{ $paciente->nombre_conducido }}
              </div>
              <div class="col-md-2"><br/>
                <label>Telefono:</label><br/>
                {{ $paciente->telf_conducido }}
              </div>
            @else
              <div class="col-md-3"><br/>
                <label>CONDUCIDO POR:</label><br/>
                VIENE SOLO
              </div>
            @endif
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="panel panel-info panel-bordered panel-sm">
          <div class="panel-heading">
            <div class="panel-title text-center"><br/>
              <b>ATENCION MEDICA</b>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group">
              <div class="col-md-12">
                <div id='divHistorial'></div>
              </div>
            </div>
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
                <th>Prioridad</th>
                <th>Area</th>
                <th>Medico</th>
                <th style="text-align:center">Evaluacion</th>
                <?php if($permiso->crear == 1): ?>
                {{-- <th style="text-align:center">Pdf Evaluacion</th>
                <th style="text-align:center;min-width:106px">Receta</th>
                <th style="text-align:center">Lab</th>
                <th style="text-align:center">Rad</th>
                <th style="text-align:center">Interc.</th>
                <th style="text-align:center">Interc. Rpta</th> --}}
                <th style="text-align:center">Siteds</th>
                <?php endif; ?> 
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
<div class="modal fade" id="myModalEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE LA EVALUACION MEDICA: <span id="nrorecmodal2"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-evaluacion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
