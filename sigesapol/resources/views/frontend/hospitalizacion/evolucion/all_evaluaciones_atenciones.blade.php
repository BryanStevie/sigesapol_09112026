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
  /* margin-top: -18px; */
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

  .form-control {
    height: 28px;
  }

  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle:before {
    content: 'NO';
    left: -4rem;
  }
  .btn-toggle:after {
    content: 'SI';
    right: -4rem;
    opacity: 0.5;
  }
  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.active:before {
    opacity: 0.5;
  }
  .btn-toggle.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }
  .btn-toggle.active {
    background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
    content: 'NO';
    left: -5rem;
  }
  .btn-toggle.btn-lg:after {
    content: 'SI';
    right: -5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }
  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }
  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }
  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }
  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }
  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }
  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }
  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }
  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }
  .form-group {
    margin-bottom: auto;
  }
  .btn-electronico {
    color: #fff;
    background-color: #5bc0de;
    border-color: #46b8da;
  }
  .btn-electronico:hover {
    color: #fff;
    background-color: #31B0D5;
  }
  .swal-hc{
    width: 350px !important;
    padding: 1rem !important;
  }
  .swal-hc-popup {
    border: 3px solid #e74c3c;
    border-radius: 16px;
  }
  .swal-hc-icon {
    transform: scale(0.5);
    margin: 0;
    font-size: 40px!important;
  }
  .swal-hc-title {
    color: #e74c3c !important;
    font-weight: 700 !important;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>
//--------------------------------------------------------------------------
$(document).ready(function() {

  $consul = $("#frmRequerimiento").find("#id_requerimiento");
  $consul.select2();

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

// function modalHistorial(){
//   var id_principal = $("#id_paciente").val();
  
//   $.ajax({
//       url: "{{url('/')}}/modal_historial_evaluaciones_atenciones/"+id_principal+'/'+3,
//       type: "GET",
//       success: function (result) {  
//           $("#divHistorial").html(result);
//           $('#tablemodal1').DataTable({
//             "sPaginationType": "full_numbers",
            
//             "language": {"url": "/js/Spanish.json"},
//           });
//       }
//   });
// }

$(document).ready(function() {
  // modalHistorial();
  // $('#openModalHistoria').on('hidden.bs.modal', function () {
  //   location.reload();
  // })
  
  datatablenew();
  listarLaboratorioOrdenesHistorial();
});

var m = 0;

function datatablenew() {
  if ($.fn.DataTable.isDataTable('#tblPrestacion')) {
    $('#tblPrestacion').DataTable().clear().destroy(); // Limpiar y destruir
  }
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_evolucion_hospi_ajax",
    "bProcessing": true,
    "sPaginationType": "full_numbers",
    "paging": true,
    "bFilter": false,
    "bSort": false,
    "info": true,
    "responsive": true,
    "iDisplayLength": 5,
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
      var estado = $('#estado').val();
      var id_servicio = $('#id_servicio').val();
      var id_paciente = $('#id_paciente').val();
      // var id_medico = $('#id_medico').val();
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
          // id_medico: id_medico,
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
          return row.numero;
        },
        "bSortable": false,
        "aTargets": [0]
      },
      {
        "mRender": function(data, type, row) {
          var id = "";
          if (row.id != null) id = row.id;
          return id;
        },
        "bSortable": false,
        "aTargets": [1],
        "visible": <?php echo $is_fiscalizador ?>
      },
      {
        "mRender": function(data, type, row) {
          var fecha_hora_evaluacion = "";
          if (row.fecha_evaluacion != null) fecha_hora_evaluacion = row.fecha_evaluacion + "  " + row.hora_evaluacion;
          return fecha_hora_evaluacion;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          var servicio_encargado = "";
          if (row.servicio_encargado_atencion != null) servicio_encargado = row.servicio_encargado_atencion;
          return servicio_encargado;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var servicio = "";
          if (row.servicio != null) servicio = row.servicio;
          return servicio;
        },
        "bSortable": false,
        "aTargets": [4]
      },
      {
        "mRender": function(data, type, row) {
          var cama = "";
          if (row.cama != null) cama = row.cama;
          return cama;
        },
        "bSortable": false,
        "aTargets": [5]
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
        "aTargets": [6]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html += '<table><tr><td>';
            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluacion</b>">';
            html += '<a href="/ver_evaluacion_hospitalizacion/<?php echo Auth::user()->id ?>/' + row.id + '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
            html += '</td>';

            if (parseInt(row.flag) === 0) { // 63: Neumo y 8: Medicina Interna
              html += '<td style="padding-left: 5px;">';
              // if (row.is_editable == '1') {
                <?php if (intval($permiso->modificar) == 1 && Auth::user()->tipo_registro==1): ?>
              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>">';
              html += '<a href="/hospitalizacion/evolucion/edit/' + row.id + '" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>';
              html += '</div>';
                <?php else:?>
                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Falta Permiso</b>">';
                  html += '<button class="btn btn-warning btn-xs" disabled><i class="fa fa-pencil"></i></button>';
                  html += '</div>';
                <?php endif; ?>
              /*} else {
                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Fuera de tiempo</b>">';
                html += '<button class="btn btn-warning btn-xs" disabled><i class="fa fa-pencil"></i></button>';
                html += '</div>';
              }*/
            // } else {
              html += '</td></tr><table>';
            }
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>';
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

          if (row.ruta_evaluacione != null && row.id_estado_reg == 1) {
            var ruta_evaluacione = row.ruta_evaluacione.replace("{", "").replace("}", "");
            var ruta = ruta_evaluacione.split(",");
            let flag_evaluacione = row.flag_evaluacione.replace("{", "").replace("}", "");
            let flag_firma = flag_evaluacione.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[r] + '/' + ruta[r] + '" target="_blank" class="btn ';
                if(flag_firma[r]==1){
                  html +=' btn-primary ';
                }else{
                  html +=' btn-electronico';
                }
                html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
                //html += '<a href="/print_hospitalizacion_evolucion/' + row.id + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [8],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
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
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[r] + '/' + ruta[r] +
                  '" target="_blank" class="btn ';
                if(flag_firma[r]==1){
                  html +=' btn-primary ';
                }else{
                  html +=' btn-electronico';
                }
                html +=' btn-xs"><i class="fa fa-download"></i></a></div>';

              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [9],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_laboratorio != null && row.id_estado_reg == 1) {
            var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
            var ruta = ruta_laboratorio.split(",");
            let flag_laboratorio = row.lid.replace("{", "").replace("}", "");
            let flag_firma = flag_laboratorio.split(",");
            let flag_lab = row.flag_laboratorio.replace("{", "").replace("}", "");
            let flag_lab_firma = flag_lab.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px">';
                html += '<a href="/ver_laboratorio_imprimir/' + flag_firma[r] +
                  '" target="_blank" class="btn ';
                if(flag_lab_firma[r]==1){
                  html +=' btn-primary ';
                }else{
                  html +=' btn-electronico';
                }
                html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [10],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_radiologia != null && row.id_estado_reg == 1) {
            var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
            var ruta = ruta_radiologia.split(",");
            let flag_radiologia = row.rid.replace("{", "").replace("}", "");
            let flag_firma = flag_radiologia.split(",");
            let flag_rad = row.flag_radiologia.replace("{", "").replace("}", "");
            let flag_rad_firma = flag_rad.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px">';
                html += '<a href="/ver_radiologia_imprimir/' + flag_firma[r] +
                  '" target="_blank" class="btn ';
                if(flag_rad_firma[r]==1){
                    html +=' btn-primary ';
                  }else{
                    html +=' btn-electronico';
                  }
                  html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Imagenología</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [11],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
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
                html += '<a href="/' + flag_firma[r] + '/' + ruta[r] +
                  '"  class="btn ';
                if(flag_firma[r]==1){
                  html +=' btn-primary ';
                }else{
                  html +=' btn-electronico';
                }
                html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [12],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_rpta_interconsulta != null && row.id_estado_reg == 1) {
            var ruta_interconsulta = row.ruta_rpta_interconsulta.replace("{", "").replace("}", "");
            var ruta = ruta_interconsulta.split(",");
            let flag_interconsulta = row.flag_rpta_interconsulta.replace("{", "").replace("}", "");
            let flag_firma = flag_interconsulta.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[r] + '/' + ruta[r] +
                  '"  class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [13],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_evaluacion == null && row.ruta_laboratorio == "{NULL}" && row.id_estado_reg == 1) {
            <?php if(Auth::user()->tipo_registro==1): ?>
            if (row.estado ==3 ) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
            html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
              .id + ')"><i class="fa fa-remove"></i></a></div>';
            }
            else{
              html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
            }
          <?php endif; ?>
          } else {
            html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [14],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          let htmlString = '<div style="text-align: center">';
            <?php if(Auth::user()->tipo_registro==1): ?>
            if (row.cod_siteds_susalud) {
              let isSitedsTemp = false;
              if (row.cod_siteds_susalud && (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === 'T' || row.cod_siteds_susalud.length === 4)) {
                  isSitedsTemp = true;
              }
              htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.cod_siteds_susalud}</span></p>`;
              if (isSitedsTemp) {
                  htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado})"><i class="fa fa-check-square"></i></button>`
              }
            } else {
              htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado})"><i class="fa fa-check-square"></i></button>`
            }
            <?php endif; ?>
          return htmlString += "</div>";
        },
        "bSortable": false,
        "aTargets": [15],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          if (row.flag_orden_traslado) {
            if (parseInt(row.estado_orden_traslado) === 0) {
              return `<div>
                        <p><span class="label label-warning">PENDIENTE A FIRMAR</span></p> 
                        <a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" title="PDF" target="_blank"><i class="fa fa-download"></i></a>
                        <button onClick="onFirmarTraslado(${row.id})" type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="FIRMAR ORDEN" target="_blank"><i class="fa fa-edit"></i></button>
                      </div>`;
            } else {
              if (row.ruta_orden_traslado) {
                return `<div>
                        <a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" title="PDF" target="_blank"><i class="fa fa-download"></i></a>
                        <a href="/${moment().format("YYYY/MM/DD")}/${row.ruta_orden_traslado}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="ORDEN FIRMADO" target="_blank">
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                      </div>`;
              }
            }
            // switch (parseInt(row.estado_orden_traslado)) {
            //   case 0:
            //     return `<a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-placement="top" title="Generar PDF" target="_blank"><i class="fa fa-download"></i></a>
            //             <button onClick="onRegularizarFirmaOrdenTraslado(${row.id})" type="button" class="btn btn-warning btn-xs" data-placement="top" title="Regularizar Firma" target="_blank"><i class="fa fa-edit"></i></button>`;
            //   case 1:
            //     return `<div>
            //               <p><span class="label label-warning">PENDIENTE</span></p>
            //               <a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-placement="top" title="Generar PDF" target="_blank"><i class="fa fa-download"></i></a>
            //               <button onClick="onChangeEstadoOrdenTraslado(${row.id}, 2)" type="button" class="btn btn-primary btn-xs" data-placement="top" title="Aprobar Traslado"><i class="fa fa-check-circle-o"></i></button>
            //               <button onClick="onChangeEstadoOrdenTraslado(${row.id}, 3)" type="button" class="btn btn-danger btn-xs" data-placement="top" title="Rechazar Traslado"><i class="fa fa-times-circle-o"></i></button>
            //             </div>`;
            //   case 2:
            //     return `<div>
            //               <span class="label label-success">APROBADO</span><br>
            //               <a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-placement="top" title="Generar PDF" target="_blank"><i class="fa fa-download"></i></a>
            //               <a href="/${moment().format("YYYY/MM/DD")}/${row.ruta_orden_traslado}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" target="_blank"><i class="fa fa-download"></i></a>
            //             </div>`;
            //   case 3:
            //     return `<div>
            //               <span class="label label-danger">CANCELADO</span><br>
            //               <a href="/hospitalizacion/evolucion/generar-orden-traslado/${row.id}" type="button" class="btn btn-default btn-xs" data-placement="top" title="Generar PDF" target="_blank"><i class="fa fa-download"></i></a>
            //               <a href="/${moment().format("YYYY/MM/DD")}/${row.ruta_orden_traslado}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" target="_blank"><i class="fa fa-download"></i></a>
            //             </div>`;
            //   default: 
            //     return "";
            // }
          }
          return "";
        },
        "bSortable": false,
        "aTargets": [16],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
    ]
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

function irANuevaEvolucionHospitalizacion(idHospitalizacion) {
  $.ajax({
    url: '/ajax/verificar_hc_evolucion/' + idHospitalizacion,
    type: 'GET',
    dataType: 'json',
    success: function(resp) {
      if (resp.status === 'ok') {
        window.location.href = '/form_evolucion_hospitalizacion/' + idHospitalizacion;
      } else if (resp.status === 'completar') {
        window.location.href = '/form_atencion_prestacion_hspcu/<?php echo Auth::user()->id; ?>/' + idHospitalizacion;
      } else {
        Swal.fire({
          icon: 'warning',
          iconColor: '#e74c3c',
          title: 'ALERTA',
          html: '<span style="font-size:23px;">El registro de la Historia Clínica aún no está terminada, por lo que no es posible realizar esta acción.<br><br><b>Médico responsable del registro:</b><br><span style="color:#e74c3c;font-weight:bold;font-size:19px;">' + resp.medico + '</span></span>',
          width: 500,
          customClass: {
            popup: 'swal-hc-popup',
            icon: 'swal-hc-icon',
            title: 'swal-hc-title'
          }
        });
      }
    },
    error: function() {
      Swal.fire({
        icon: 'error',
        title: 'ERROR',
        text: 'No se pudo verificar el estado de la Historia Clínica. Intente nuevamente.'
      });
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

// function modalHistoria(id) {
// 	$('#openModalHistoria').modal('show');

//   $.ajax({
//     url: "modal_historia/" + id,
//     type: "GET",
//     success: function(result) {
//       $("#diveditpregOpc").html(result);
//     }
//   });
// }

  // TAGSITEDS
  function regularizeCodeSiteds(id, id_asegurado) {
    Swal.fire ({
        html: "<div style='margin: 0px 0px 20px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
    })
    const datoadicional = {!! json_encode($datoadicional) !!};
    const body = {
        "idCorrelativo": id,
        "aseguradoId": id_asegurado,
        "establecimientoId": $("#id_establecimiento").val(),
        "tipoAtencionId": "21",
        "servicioId": datoadicional.id_consultorio
    };
    $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result, textStatus, jqXHR) => {
			if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
				updateSiteds(id, result.nuAutorizacion, result.message);
			} else {
				Swal.fire({
					icon: 'error',
					title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
					html: `<div style='margin: 0px 0px; padding: 0px 20px'>
							<span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
							</div>`,
					confirmButtonText: 'ENTENDIDO',
				});
			}
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
        }, 
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
    })
  }

  function updateSiteds(id, nuAutorizacion, message) {
    Swal.fire ({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onOpen: () => {
            Swal.showLoading();
        }
    })
    const auth = {!! json_encode(Auth::user()) !!};
    const body = {
        cod_siteds_susalud: nuAutorizacion,
        updated_us_cod_siteds: auth.id
    };
    $.ajax({
        url: `/hospitalizacion-evaluacion/${id}/update-code-siteds`,
        type: 'PUT',
        data: body,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon:'success',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
                html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${message || "Código SITEDS actualizado correctamente"}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            datatablenew();
        },
        error: function(jqXHR, textStatus, errorThrown) {},
        complete: function(xhr, status) {
            Swal.hideLoading();
            Swal.close();
        }
    });
  };

  function mostrar_det_atencion_evaluacion(id){
    $('#nrorecmodal').text(id);
    $.ajax({
      url: '/mostrar_atencion_hospitalizacion/' + id,
      async: false,
      success: function(result){
        $("#muestra-det-atencion").html(result);
      },
      complete: function () {
        $("#myModalAtencionEvaluacion").modal();
      }
    });
  }

  function ver_det_atencion_evaluacion(id){
    var requiereValidacionHc = <?php echo $hospitalizacion->requiereValidacionHc() ? 'true' : 'false'; ?>;
    if (!requiereValidacionHc) {
      mostrar_det_atencion_evaluacion(id);
      return;
    }

    var idHospitalizacion = '{{$id_hospitalizacion}}';
    $.ajax({
      url: '/ajax/verificar_hc_evolucion/' + idHospitalizacion,
      type: 'GET',
      dataType: 'json',
      success: function(resp) {
        if (resp.status === 'ok') {
          mostrar_det_atencion_evaluacion(id);
        } else if (resp.status === 'completar') {
          window.location.href = '/form_atencion_prestacion_hspcu/<?php echo Auth::user()->id; ?>/' + idHospitalizacion;
        } else {
          Swal.fire({
            icon: 'warning',
            iconColor: '#e74c3c',
            title: 'ALERTA',
            html: '<span style="font-size:23px;">El registro de la Historia Clínica aún no está terminada, por lo que no es posible realizar esta acción.<br><br><b>Médico responsable del registro:</b><br><span style="color:#e74c3c;font-weight:bold;font-size:19px;">' + resp.medico + '</span></span>',
            width: 500,
            customClass: {
              popup: 'swal-hc-popup',
              icon: 'swal-hc-icon',
              title: 'swal-hc-title'
            }
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'ERROR',
          text: 'No se pudo verificar el estado de la Historia Clínica. Intente nuevamente.'
        });
      }
    });
  }

  function onChangeEstadoOrdenTraslado(id_evaluacion, nuevoEstadoId) {
    const body = { estado: nuevoEstadoId };
    $.ajax({
      type: "put",
      url: `/hospitalizacion/evolucion/actualizar-order-traslado/${id_evaluacion}`,
      contentType: 'application/json',
      data: JSON.stringify(body),
      dataType: "json",
      success: function (response) {
          Swal.fire({
              icon: "success",
              title: "<p style='font-size: 15px'>HOSPITALIZACION - EVALUACIÓN</p>",
              html: `<p style='font-size: 13px'>${response.message || "SE FIRMO EXITOSAMENTE LA ORDEN DE TRASLADO"}</p>`,
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
          });
      },
      error: (error) => {
        console.log(error);
      }
    });
  }

  function onFirmarTraslado(id_evaluacion) {
    $("#id_evaluacion_selected").val(id_evaluacion);
    $.ajax({
      type: "POST",
      url: `/hospitalizacion/evolucion/firmar-order-traslado/${id_evaluacion}`,
      dataType: "json",
      beforeSend: function() {
        Swal.fire({
          html: '<p>GENERAR Y FIRMAR ORDEN TRASLADO</p>',
          showLoaderOnConfirm: true,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function (response) {
        if (response.sw) {
          var csv = "<?php echo url()?>/tmp/csv/" + response.csv + ".csv";
          $("#doc_firmar").val(`${response.csv}.7z`);
          $("#opt_firma").val(`${response.opt}`);
          initInvoker('W');
        }
      },
      complete: () => {
        Swal.close();
        Swal.hideLoading();
      }
    });  
  }

  window.addEventListener('getArguments', async function(e) {
    await ObtieneArgumentosParaFirmaDesdeLaWeb();
  });

  window.addEventListener('invokerOk', function(e) {
    handlerFirmarTrasladoSuccess();
  }); 

  window.addEventListener('invokerCancel', function(e) {
    Swal.fire({
      icon: 'warning',
      title: "EVALUACIONES MEDICAS",
      html: '<p>El proceso de firma digital fue cancelado</p>',
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    });
  });

  async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient('sendArguments', arg);
  }

  async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
    const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("{{route('postArgumentsOrdenTrasladoSolicitar')}}", {
      _token: CSRF_TOKEN,
      type: "W",
      documentName: $('#doc_firmar').val(),
      entidad: $("#entidad_firma").val(),
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
        // await dispatchEventClient('sendArguments', data);
    });
  }

  function handlerFirmarTrasladoSuccess() {
    const id_evaluacion = $("#id_evaluacion_selected").val();
    const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.ajax({
      type: "put",
      url: `/hospitalizacion/evolucion/actualizar-order-traslado/${id_evaluacion}`,
      contentType: 'application/json',
      data: JSON.stringify({ estado: 1 }),
      dataType: "json",
      headers: { 'X-CSRF-TOKEN': CSRF_TOKEN},
      success: function (response) {
        datatablenew();
        Swal.fire({
          icon: "success",
          title: "<p style='font-size: 15px'>EVALUACIONES MEDICAS</p>",
          html: `<p style='font-size: 13px'>Se firmo la orden de traslado exitosamente</p>`,
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false
        });
      },
    });
  }


  function listarLaboratorioOrdenesHistorial() {
    const nivel = {{ $establecimiento->nivel }};
    const documento_numero = "{{ $paciente->nro_doc_ident }}";
    if (nivel && parseInt(nivel) === 1 || nivel && parseInt(nivel) === 2) {
      $.ajax({
        url: `/modal_historial_laboratorio_nivel_1_2/${documento_numero}`,
        type: "GET",
        success: function (result) {  
          $("#divLaboratorioHistorial").html(result);
          $('#tablemodalm1').DataTable({
            "sPaginationType": "full_numbers", 
            "language": {
              "url": "/js/Spanish.json"
            }
          });
        }
      });
    } else {
      $.ajax({
        url: "/modal_historial_laboratorio/"+documento_numero,
        type: "GET",
        success: function (result) {  
          $("#divLaboratorioHistorial").html(result);
          $('#tablemodalm1').DataTable({
            "sPaginationType": "full_numbers",
            "language": {
              "url": "/js/Spanish.json"
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
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Evoluciones Médicas de un Paciente</h3>
      @if($id_atencione==2)
        <div class="pull-right">
          <a href="/all_pacientes_evaluaciones_by_emergencia" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
        </div>
      @else
        <div class="pull-right">
          <a href="/all_pacientes_evoluciones_by_hospitalizacion" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
        </div>
      @endif
    </div>
    <div class="panel-body">
      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center" style="margin-top:-18px;"><br>
            <b>INFORMACIÓN DEL PACIENTE</b>
          </div>
        </div>
        <div class="panel-body">
          <form>

            <div class="row">
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-12">
                  <label>NOMBRE DE LA IPRESS</label>
                  <p>{{$paciente->establecimiento}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>DOCUMENTO</label>
                  <p>{{$paciente->tipo_doc_ident}} - {{$paciente->nro_doc_ident}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>N° HISTORIA CLINICA</label>
                  <p>{{$paciente->nro_historia}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-4">
                  <label>APELLIDOS Y NOMBRES DEL PACIENTE</label>
                  <p>{{$paciente->asegurado_paterno}} {{$paciente->asegurado_materno}}, {{$paciente->asegurado_nombre}}</p>
                </div>
              </div>
            </div>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input  value="<?php echo $id_hospitalizacion?>" type="hidden" name="id_paciente" id="id_paciente" class="form-control">
              <input  value="<?php echo $estado?>" type="hidden" name="estado" id="estado" class="form-control">
              <input  value="<?php echo $id_atencione?>" type="hidden" name="id_atencione" id="id_atencione" class="form-control">
              <input type="hidden" name="fecha_ini" id="fecha_ini" value="<?php echo $fechaDesde ?>">
              <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fechaHasta ?>">
              <input type="hidden" name="id_servicio" id="id_servicio" value="0">
              <input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">
              <input type="hidden" name="doc_firmar" id="doc_firmar">
              <input type="hidden" name="opt_firma" id="opt_firma">
              <input type="hidden" id="argumentos" value="" />
              <input type="hidden" name="id_evaluacion_selected" id="id_evaluacion_selected">
              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$farmacia->id_establecimiento}}">
            <div class="row">
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>PARENTESCO</label>
                  <p>{{$paciente->parentesco}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="dni_beneficiario">SEXO</label>
                  <p>{{$paciente->sexo}}</p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <p>&nbsp;</p>
                  <label for="">DATOS DE LA UBICACIÓN ACTUAL DEL PACIENTE</label>
                  <p>&nbsp;</p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label for="">SERVICIO A CARGO:</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <p>@if($servicio_encargado != null) {{$servicio_encargado->nombre}} @else No registrado @endif</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4" >
                  <p>&nbsp;</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label for="">UBICACIÓN FÍSICA:</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="tipo">SALA</label>
                  <p>{{$datoadicional->consultorio}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="tipo">PABELLON</label>
                  <p>{{$datoadicional->pabellon}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label for="dni_beneficiario">PISO</label>
                  <p>{{$datoadicional->piso}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label>HABITACION</label>
                  <p>{{$datoadicional->cuarto}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label>CAMA</label>
                  <p>{{$datoadicional->cama}}</p>
                </div>
              </div>
            </div>
          </form>
      </div>

      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center" style="margin-top:-18px;"><br>
            <b>ATENCION(ES) MÉDICA(S)</b>
          </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover">
              <thead>
                <tr>
                    <th rowspan="2">#</th>
                    @if ($is_fiscalizador)
                    <th rowspan="2">N</th>
                    @endif
                    <th rowspan="2">FECHA Y HORA DE ATENCIÓN</th>
                    <th rowspan="2">ESTABLECIMIENTO</th>
                    <th rowspan="2">TIPO ATENCION</th>
                    <th rowspan="2">SERVICIO A CARGO</th>
                    <th colspan="2">UBICACIÓN FÍSICA DE LA ATENCIÓN</th>
                    <th rowspan="2">MEDICO</th>
                    <th rowspan="2" class="text-center">VER</th>
                </tr>
                <tr>
                  <th>SERVICIO</th>
                  <th>CAMA</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = $atenciones != null ? count($atenciones) : 0; ?>
                @foreach ($atenciones as $rowHorario)
                <tr>
                  <td>{{$i}}</td>
                  @if ($is_fiscalizador)
                  <td>{{$rowHorario->id}}</td>
                  @endif
                  <td>{{$rowHorario->dia}} {{$rowHorario->hora}}</td>
                  <td>{{$rowHorario->establecimiento}}</td>
                  <td>{{$rowHorario->tipo_atencion}}</td>
                  <td>{{$rowHorario->servicio_encargado_atencion}}</td>
                  <td>{{$rowHorario->servicio}}</td>
                  <td>{{$rowHorario->cama}}</td>
                  <td>{{$rowHorario->medico_paterno." ".$rowHorario->medico_materno." ".$rowHorario->medico_nombre}}</td>
                  <td class="text-center"> 
                    @if ($rowHorario->id_estado_reg != 0)
                      <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                        <a href="javascript:void(0)" onClick="ver_det_atencion_evaluacion({{$rowHorario->id}})" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                      </div>
                    @else
                      <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                    @endif
                  </td>
                </tr>
                <?php $i--; ?>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-body">
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab1_evoluciones" aria-controls="tab1_evoluciones" role="tab" data-toggle="tab">EVOLUCIONES</a></li>
            <li role="presentation"><a href="#tab2_lab_historial" aria-controls="tab2_lab_historial" role="tab" data-toggle="tab">HISTORIAL DE ÓRDENES DE LABORATORIO</a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="tab1_evoluciones">
              @if ($permiso->crear == "1")
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-2">
                      <?php if(Auth::user()->tipo_registro==1): ?>
                      <a class="btn btn-primary btn-sm" href="/form_evolucion_hospitalizacion/{{$id_hospitalizacion}}" <?php if($hospitalizacion->requiereValidacionHc()): ?>onclick="irANuevaEvolucionHospitalizacion({{$id_hospitalizacion}}); return false;"<?php endif; ?>><i class="fa fa-edit"></i> Nueva Evolución</a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              @endif
              <div class="table-responsive">
                <table id="tblPrestacion" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th rowspan="2">#</th>
                      <th rowspan="2">N</th>
                      <th rowspan="2">Fecha y Hora de Atención</th>
                      <th rowspan="2">Servicio A Cargo</th>
                      <th colspan="2" style="text-align:center">Ubicación física de la Atención</th>
                      <th rowspan="2">Médico</th>
                      <th rowspan="2" style="text-align:center">Evolución</th>
                      <?php if($permiso->crear == 1): ?>
                        <th rowspan="2" style="text-align:center">Pdf Evolución</th>
                        <th rowspan="2" style="text-align:center;min-width:106px">Receta</th>
                        <th rowspan="2" style="text-align:center">Lab</th>
                        <th rowspan="2" style="text-align:center">Rad</th>
                        <th rowspan="2" style="text-align:center">Interc.</th>
                        <th rowspan="2" style="text-align:center">Interc. Rpta</th>
                        <th rowspan="2">Anular</th>
                        <th rowspan="2">Siteds</th>
                        <th rowspan="2" style="text-align:center">Orden Traslado</th>
                      <?php endif; ?> 
                    </tr>
                    <tr>
                      <th>Servicio</th>
                      <th>Cama</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab2_lab_historial">
              <div class="panel">
                <div class="panel-body">
                  <div id="divLaboratorioHistorial"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalAtencionEvaluacion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
            </div>
           
<div id="addComponent"></div>

@include('frontend.includes.footer')
@endsection
