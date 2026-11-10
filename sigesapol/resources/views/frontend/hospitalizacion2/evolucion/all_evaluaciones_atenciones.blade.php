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
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_evolucion_hospi_ajax",
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
          var servicio = "";
          if (row.servicio != null) servicio = row.servicio;
          return servicio;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var cama = "";
          if (row.cama != null) cama = row.cama;
          return cama;
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
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluacion</b>">';
            html += '<a href="/ver_evaluacion_hospitalizacion/<?php echo Auth::user()->id ?>/' + row.id +
              '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
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

          if (row.ruta_evaluacione != null && row.id_estado_reg == 1) {
            var ruta_evaluacione = row.ruta_evaluacione.replace("{", "").replace("}", "");
            var ruta = ruta_evaluacione.split(",");
            let flag_evaluacione = row.flag_evaluacione.replace("{", "").replace("}", "");
            let flag_firma = flag_evaluacione.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                //if(row.status_token==1){
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
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
        "aTargets": [7],
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
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
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
        "aTargets": [9],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
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
        "aTargets": [10],
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
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
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
        "aTargets": [11],
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
                html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
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
        "aTargets": [12],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_evaluacion == null && row.ruta_laboratorio == "{NULL}" && row.id_estado_reg == 1) {
            if (row.estado ==3 ) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
            html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
              .id + ')"><i class="fa fa-remove"></i></a></div>';
            }
            else{
              html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
            }
          } else {
            html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [13],
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
        title:  "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
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

  function ver_det_atencion_evaluacion(id){
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
          <div class="panel-title text-center"><br>
            <b>INFORMACIÓN DEL PACIENTE</b>
          </div>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <div class="col-md-2">
              <label for="tipo">TIPO DE DOCUMENTO</label>
              <p><?php echo $paciente->tipo_doc_ident?></p>
            </div>
            <div class="col-md-2">
              <label for="">N° DE DOCUMENTO</label>
              <p><?php echo $paciente->nro_doc_ident?></p>
            </div>
            <div class="col-md-2">
              <label>N° HISTORIA CLINICA</label>
              <p><?php echo $paciente->nro_historia?></p>
            </div>
            <div class="col-md-2">
              <label for="">SEXO</label>
              <p><?php echo $paciente->sexo?></p>
            </div>
            <div class="col-md-4">
              <label for="tipo">NOMBRE DE LA IPRESS</label>
              <p><?php echo $paciente->establecimiento?></p>
              <input  value="<?php echo $id_hospitalizacion?>" type="hidden" name="id_paciente" id="id_paciente" class="form-control">
              <input  value="<?php echo $estado?>" type="hidden" name="estado" id="estado" class="form-control">
              <input  value="<?php echo $id_atencione?>" type="hidden" name="id_atencione" id="id_atencione" class="form-control">
              <input type="hidden" name="fecha_ini" id="fecha_ini" value="<?php echo $fechaDesde ?>">
              <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fechaHasta ?>">
              <input type="hidden" name="id_servicio" id="id_servicio" value="0">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-2">
              <label>APELLIDO PATERNO</label>
              <p><?php echo $paciente->asegurado_paterno?></p>
            </div>
            <div class="col-md-2">
              <label>APELLIDO MATERNO</label>
              <p><?php echo $paciente->asegurado_materno?></p>
            </div>
            <div class="col-md-2">
              <label>NOMBRES</label>
              <p><?php echo $paciente->asegurado_nombre?></p>
            </div>
            <div class="col-md-2">
              <label>FECHA DE NACIMIENTO</label>
              <p><?php echo $paciente->fecha_nac?></p>
            </div>
            <div class="col-md-4">
              <label>PARENTESCO</label>
              <p><?php echo $paciente->parentesco?></p>
            </div>
          </div>
        
          <div class="form-group">
            <div class="col-md-2">
              <label>GRADO </label>
              <p><?php echo $paciente->grado?></p>
            </div>
            <div class="col-md-2">
              <label for="">PABELLON</label>
              <p><?php echo $datoadicional->pabellon?></p>
            </div>
            <div class="col-md-2">
              <label for="">PISO</label>
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
        </div>
      </div>

      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center"><br>
            <b>ATENCION(ES) MÉDICA(S)</b>
          </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                    <th>FECHA</th>
                    <th>HORA</th>
                    <th>ESTABLECIMIENTO</th>
                    <th>TIPO ATENCION</th>
                    <th>SERVICIO</th>
                    <th>CAMA</th>
                    <th>MEDICO</th>
                    <th class="text-center">VER</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($atenciones as $rowHorario)
                <tr>
                  <td>{{$rowHorario->dia}}</td>
                  <td>{{$rowHorario->hora}}</td>
                  <td>{{$rowHorario->establecimiento}}</td>
                  <td>{{$rowHorario->tipo_atencion}}</td>
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
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-body">
          <div class="row">
            <div class="form-group">
              <div class="col-md-2">
                <a class="btn btn-primary btn-sm" href="/form_evolucion_hospitalizacion/{{$id_hospitalizacion}}"><i class="fa fa-edit"></i> Nueva Evolución</a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table id="tblPrestacion" class="table table-bordered">
              <thead>
                <tr>
                  <th>N</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Servicio</th>
                  <th>Cama</th>
                  <th>Medico</th>
                  <th style="text-align:center">Evolución</th>
                  <?php if($permiso->crear == 1): ?>
                  <th style="text-align:center">Pdf Evolución</th>
                  <th style="text-align:center;min-width:106px">Receta</th>
                  <th style="text-align:center">Lab</th>
                  <th style="text-align:center">Rad</th>
                  <th style="text-align:center">Interc.</th>
                  <th style="text-align:center">Interc. Rpta</th>
                  <th>Anular</th>
                  <!--th>Siteds  </th-->
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
@include('frontend.includes.footer')
@endsection
