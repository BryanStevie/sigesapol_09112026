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

.text-center-vertical {
  text-align: center;
  vertical-align: middle;
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

.btn-info-ant {
  color: #fff;
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info-ant:hover {
  color: #fff;
  background-color: #31B0D5;
}

.btn-ga_baja {
  color: #fff;
  background-color: #572364;
  border-color: #42214b;
}
.btn-ga_baja:hover {
  color: #fff;
  background-color: #42214b;
}

.btn-ga_alta {
  color: #fff;
  background-color: #000080;
  border-color: #0a0a5c;
}
.btn-ga_alta:hover {
  color: #fff;
  background-color: #0a0a5c;
} {
  color: #fff;
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info-ant:hover {
  color: #fff;
  background-color: #31B0D5;
}

.
.swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
        }


.swal-wide{
    width: 1000px !important;
    padding: 0px 20px !important;
  }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
//--------------------------------------------------------------------------
$(document).ready(function() {

  $('#openModalSiteds').on('hidden.bs.modal', function() {
      location.reload();
  })

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

function modalSiteds(id,dni) {

    $(".modal-dialog").css("width", "80%");
    $('#openModalSiteds').modal('show');
    $('#openModalSiteds.modal-body').css('height', 'auto');

    $.ajax({
        url: "modal_siteds/" + id+'/'+ dni+'/'+2,
        type: "GET",
        success: function(result) {
            $(".modal-siteds").html(result);
        }
    });

}

function validateSitedsFrm(){   

            var msg = "";
            var cod_siteds= $('#codigo_siteds').val();  
            if ($('#frmsiteds').find("#codigo_siteds").val() == "") {
                bootbox.alert("Recuerde que tiene que Ingresar el CÓDIGO SITEDS", function(){     
                document.getElementById("codigo_siteds").click();
                  setTimeout(function (){ 
                    $('#codigo_siteds').focus();
                }, 500);
              });
              return false;
            }else{
              ///tomamos el numero ingresado en el codigo siteds
              $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
            }
            
            $("#frmsiteds").submit();
            /*var codSiteds= $('#codigo_siteds').val();  
              $.ajax({
                url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
                type:'GET',
                dataType:'JSON', 
                  success: function(data){   
                    if(data!=null){ 
                      console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
                      if(data.cantidad_siteds!=0){ 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Registro </p></div>',
                            html: '<p class="descripcion-alert"><span>EL código SITEDS <b style="color:red;">'+codSiteds+'</b> YA EXISTE, verifique que sea correcto y vuelva a intentarlo.<br><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                          }); 
                          $('#codigo_siteds').val(''); 
                          setTimeout(function (){ 
                              $('#codigo_siteds').focus();
                          }, 2500);
                      } else{ 
                        $("#frmsiteds").submit();
                        return false;
                      } 
                    } else{ 
                      $("#frmsiteds").submit();
                      return false;
                    }
                  }
              });*/
        };
    function completarConCeros(numero, longitud) {
          let numeroString = numero.toString();
          while (numeroString.length < longitud) {
            numeroString = '0' + numeroString;
          }
          return numeroString;
        }

function reporteAtencion(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();
  var id_servicio = $('#id_servicio').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_medico = $('#id_medico').val();
  var id_atencione = $('#id_atencione').val();

  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;
  if (id_servicio == "") id_servicio = 0;
  if (id_consultorio == "") id_consultorio = 0;
  if (id_medico == "") id_medico = 0;
  if (id_atencione == "") id_atencione = 0;
   console.log(estado);
location.href = 'exportar_reporte_atencion_estadistica/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
   paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico + '/' + id_atencione;

}

function reporteAtencionDiagnostico(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var dni = $('#dni_beneficiario').val();
  var paciente = $('#nombre_beneficiario').val();
  var estado = $('#estado').val();
  var id_servicio = $('#id_servicio').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_medico = $('#id_medico').val();
  var id_atencione = $('#id_atencione').val();

  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (dni == "") dni = 0;
  if (paciente == "") paciente = 0;
  if (estado == "") estado = 0;
  if (id_servicio == "") id_servicio = 0;
  if (id_consultorio == "") id_consultorio = 0;
  if (id_medico == "") id_medico = 0;
  if (id_atencione == "") id_atencione = 0;
   console.log(estado);
location.href = 'exportar_reporte_atencion_estadistica_diagnostico/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
   paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico + '/' + id_atencione;

}

function reporteAtencionDetallado(idUser) {

Swal.fire({
  title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
  allowOutsideClick: false,
  showConfirmButton: false,
  customClass: {
    header : 'swal2-header-generar'
  },
  willOpen: () => {
    $('#blur-container').append('<div class="blur-background"></div>');

    let fecha_ini = $('#fecha_ini').val();
    let fecha_fin = $('#fecha_fin').val();
    let dni = $('#dni_beneficiario').val();
    let paciente = $('#nombre_beneficiario').val();
    let estado = $('#estado').val();
    let id_servicio = $('#id_servicio').val();
    let id_consultorio = $('#id_consultorio').val();
    let id_medico = $('#id_medico').val();
    let id_atencione = $('#id_atencione').val();

    if (fecha_ini == "") fecha_ini = 0;
    if (fecha_fin == "") fecha_fin = 0;
    if (dni == "") dni = 0;
    if (paciente == "") paciente = 0;
    if (estado == "") estado = 0;
    if (id_servicio == "") id_servicio = 0;
    if (id_consultorio == "") id_consultorio = 0;
    if (id_medico == "") id_medico = 0;
    if (id_atencione == "") id_atencione = 0;
    console.log(estado);
    let ruta = (estado != "2") ? "exportar_reporte_atencion_detallado_estadistica" : "exportar_reporte_atencion_detallado_estadistica";
    console.log(ruta);
    Swal.showLoading();
    $.ajax({
      type: "POST",
      url: ruta,
      data: {
        "id_user" : idUser,
        "fecha_ini" : fecha_ini,
        "fecha_fin" : fecha_fin,
        "dni" : dni,
        "paciente" : paciente,
        "estado" : estado,
        "id_servicio" : id_servicio,
        "id_consultorio" : id_consultorio,
        "id_medico" : id_medico,
        "id_atencione" : id_atencione
      },
      success: function (response) {
        
        if (response.excelNameResponse) {
            
          let fileUrl = `/download_reportes/${response.excelNameResponse}`;
          
          let link = document.createElement('a');
          link.href = fileUrl;
          link.download = 'Exportar Reportes de Atenciones Detallado.xlsx';
          link.innerText = 'Descargar archivo';

          let divLinkReporte = document.getElementById('link-reporte');
          divLinkReporte.appendChild(link);
          link.click();
          divLinkReporte.removeChild(link);

          Swal.hideLoading();
          Swal.fire({
            icon: 'success',
            title: 'El reporte se ha generado correctamente!',
            showConfirmButton: false,
            showConfirmButton: false,
            timer: 1500,
            willClose: () => {
              $('.blur-background').remove();
            }
              
          });
        }
      },
      error: function() {
        Swal.hideLoading();
        Swal.fire({
          icon: 'error',
          title: 'Error al generar el reporte',
          text: 'Por favor, inténtelo de nuevo más tarde',
          confirmButtonText: 'Cerrar',
          customClass: {
              confirmButton : 'custom-swal-button-cerrar'
          },
          willClose: () => {
            $('.blur-background').remove(); 
          }
        });
      }
    });
  }
});
}

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
  <?php 
  if(intval(Auth::user()->flagfiscalizador) === 1 && intval(Auth::user()->flagservicios) === 1){ ?>
    $("#id_medico").select2({
      width: 'resolve'
    });
    <?php } ?>
  $("#id_consultorio").select2({
    width: 'resolve'
  });
  $("#id_atencione").select2({
    width: 'resolve'
  });
  $("#estado").select2({
    width: 'resolve'
  });
  datatablenew();
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_prestacion_ajax",
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
      var id_consultorio = $('#id_consultorio').val();
      var id_medico = $('#id_medico').val();
      var id_establecimiento = $('#id_establecimiento').val();
      var id_atencione = $('#id_atencione').val();
      var id_condicion_establecimiento = $('#id_condicion_establecimiento').val();
      var id_condicion_servicio = $('#id_condicion_servicio').val();
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
          id_establecimiento: id_establecimiento,
          id_servicio: id_servicio, 
          id_consultorio: id_consultorio,
          id_medico: id_medico,
          id_atencione: id_atencione,
          id_condicion_establecimiento:id_condicion_establecimiento,
          id_condicion_servicio:id_condicion_servicio,
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
          var tipo_doc_ident = "";
          if (row.tipo_doc_ident != null) tipo_doc_ident = row.tipo_doc_ident;
          return tipo_doc_ident;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var nro_doc_ident = "";
          if (row.nro_doc_ident != null) nro_doc_ident = row.nro_doc_ident;
          return nro_doc_ident;
        },
        "bSortable": false,
        "aTargets": [4],
        "className": 'control'
      },
      {
        "mRender": function(data, type, row) {
          var nro_historia = "";
          var newRow = "";
          if (row.nro_historia != null) newRow += row.nro_historia;
          <?php if ($flagemergenciareferencia == 1) : ?>
          newRow +=
            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
          newRow += '<a href="javascript:void(0)" onclick="modalHistoria(' + row.id_asegurado +
            ')" class="btn btn-success btn-xs">';
          newRow += '<i class="fa fa-edit"></i></a></div>';
          <?php endif; ?>
          return newRow;
        },
        "bSortable": false,
        "aTargets": [5]
      },
      {
        "mRender": function(data, type, row) {
          var asegurado = "";
          if (row.asegurado_paterno != null) asegurado += row.asegurado_paterno;
          if (row.asegurado_materno != null) asegurado += " " + row.asegurado_materno;
          if (row.asegurado_nombre != null) asegurado += " ," + row.asegurado_nombre;
          return asegurado;
        },
        "bSortable": false,
        "aTargets": [6]
      },
      {
        "mRender": function(data, type, row) {
          var atencion = "";
          if (row.atencion != null) atencion = row.atencion;
          return atencion;
        },
        "bSortable": false,
        "aTargets": [7]
      },
      {
        "mRender": function(data, type, row) {
          var establecimiento = "";
          if (row.establecimiento != null) establecimiento = row.establecimiento;
          return establecimiento;
        },
        "bSortable": false,
        "aTargets": [8]
      },
      {
        "mRender": function(data, type, row) {
          var servicio = "";
          if (row.servicio != null) servicio = row.servicio;
          return servicio;
        },
        "bSortable": false,
        "aTargets": [9]
      },

      {
        "mRender": function(data, type, row) {
          var consultorio = "";
          if (row.consultorio != null) consultorio = row.consultorio;
          return consultorio;
        },
        "bSortable": false,
        "aTargets": [10]
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
        "aTargets": [11]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.fecha_hora_update != null) html += row.fecha_hora_update;
          return html;
        },
        "bSortable": false,
        "aTargets": [12]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_profesional_update != null) 
            html += row.profesional_paterno_update + " " + row.profesional_materno_update + ", " + row.profesional_nombre_update;
          return html;
        },
        "bSortable": false,
        "aTargets": [13]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            html += '<a href="/ver_prestacion/<?php echo Auth::user()->id ?>/' + row.id +
              '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [14],
        "visible": <?php echo ($flagemergenciareferencia == 1) ? "false" : "true" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";

          if (row.ruta_prestacion != null && row.id_estado_reg == 1) {
            html += '<table><tr><td>'
            if(row.id>999999){
              html +=
                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
              html += '<a href="/' + row.flag + '/' + row.ruta_prestacion +
              '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            }else{
               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
                html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+row.ruta_prestacion+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';

            //  html +=
            //'<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            //html += '<a href="/' + row.ruta_prestacion +
            //              '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //
            }                      
            if (row.id_consultorio == 102) {
              if (row.alta == 1 && row.alta_ruta_firma!= null) {
                html +=
                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe de Endoscopia Alta</b>">';
                html += '<a href="/' + row.alta_ruta_firma +
              '" target="_blank" class="btn btn-ga_alta btn-xs"><i class="fa fa-download"></i></a></div>';
              }
              
              
              if (row.baja == 1 && row.baja_ruta_firma!= null) {
                html +=
                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe de Endoscopia Baja</b>">';
                html += '<a href="/' + row.baja_ruta_firma +
              '" target="_blank" class="btn btn-ga_baja btn-xs"><i class="fa fa-download"></i></a></div>';
              }
              
            }
            if (row.ruta_firma_update != null) {
              // Boton de PDF activado
              html += '</td><td style="padding-left:5px;">';
              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF Modificado</b>">';
              html += '<a href="/' + row.flag_firma_update + '/' + row.ruta_firma_update + '" target="_blank" class="btn btn-info-ant btn-xs"><i class="fa fa-download"></i></a></div>';
            } else if (row.ruta_firma_update == null && row.estado_update == 1) {
              // Boton de PDF bloqueado
              html += '</td><td style="padding-left:5px;">';
              html += '<div data-toggle="tooltip" data-placement="top" data-html="true">';
              html += '<button class="btn btn-info-ant btn-xs" disabled><i class="fa fa-download"></i></button></div>';
            }
            html += '</td></tr></table>';
          } else {
            html +=
              '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
              
              if (row.id_consultorio == 102) {
                if (row.alta == 1 && row.alta_ruta_firma == null) {
                  html +='<button class="btn btn-ga_alta btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                }
                if (row.baja == 1 && row.baja_ruta_firma == null) {
                  html +='<button class="btn btn-ga_baja btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                }
              }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [15],
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
                if(row.id>999999){
                  html +=
                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                  html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }else{
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                    html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+ruta[r]+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [16],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.informe_pp) {
            html += '<span style="color: green; font-size: 15px;"><i title="Se cuenta con un informe de procedimiento registrado." class="fa fa-check-circle" aria-hidden="true"></i></span>';
          } else {
            html += '<span style="color: red; font-size: 15px;"><i title="No se cuenta con un informe de procedimiento registrado." class="fa fa-times-circle" aria-hidden="true"></i></span>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [17],
        "className": "text-center-vertical",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_laboratorio != null && row.id_estado_reg == 1) {
            var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
            var ruta = ruta_laboratorio.split(",");
            let flag_laboratorio = row.lid.replace("{", "").replace("}", "");
            let flag_firma = flag_laboratorio.split(",");

            let arrayIdsLab = [];
            if (row.lid) {
              arrayIdsLab = row.lid.replace(/[{}]/g, "").split(",").map(Number);
            }
            let href = "";
            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                href = parseInt(row.nivel_establecimiento) === 3 ? `/ver_laboratorio_imprimir/${flag_firma[r]}` : `/laboratorio/rua/pdf/${arrayIdsLab[r]}`;
                if(row.id>999999){
                  html += `<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px">
                              <a href="${href}" target="_blank" class="btn btn-primary btn-xs">
                                <i class="fa fa-download"></i>
                              </a>
                            </div>`;
                }else{
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px">';
                    html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+ruta[r]+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [18],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_radiologia != null) {
            var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
            var ruta = ruta_radiologia.split(",");
            let flag_radiologia = row.rid.replace("{", "").replace("}", "");
            let flag_firma = flag_radiologia.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                if(row.id>999999){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px">';
                  html += '<a href="/ver_radiologia_imprimir/' + flag_firma[r] +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }else{
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px">';
                    html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+ruta[r]+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [19],
        "className": "text-center",
      },
      
      /*{
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_interconsulta != null) {
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
        "aTargets": [17],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_referencia != null) {
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
        "aTargets": [18],
        "className": "text-center",
      }, */
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_prestacion == null && row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
            html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row.id + ',\'' + row.fecha_atencion + '\')"><i class="fa fa-remove"></i></a></div>';
          } else {
            html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [20],
        "visible": <?php echo ($flagemergenciareferencia == 1) ? "false" : "true" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          let htmlString = '<div style="text-align: center">';
            if (row.id_user_anulacion_atencion) {
                let tooltipContent = `
                    Anulado por: ${row.anulo_medico_nombre} ${row.anulo_medico_paterno} ${row.anulo_medico_materno}
                    <br> Motivo: ${row.motivo_anulacion_atencion}
                `;
                htmlString += `
                    <i class="fa fa-user-times" 
                        data-toggle="tooltip" 
                        data-placement="top"
                        data-html="true"
                        title="${tooltipContent.replace(/"/g, '&quot;')}">
                    </i>
                `;
            }

            htmlString += "</div>";
            return htmlString;
        },
        "bSortable": false,
        "aTargets": [21],
        "visible": <?php echo ($flagemergenciareferencia == 1) ? "false" : "true" ?>,
        "className": "text-center",
      },
      {
          "mRender": function(data, type, row) {
              var newRow = "";
              let typeLabel = "label-success";
              let showTooltip = "";
              if (String(row.codigo_siteds).includes("T") || String(row.codigo_siteds).length <= 10) {
                typeLabel = "label-warning";
                showTooltip = 'data-toggle="tooltip" data-placement="top" title="Código Siteds Temporal"';
              }
              if (row.codigo_siteds != null) {
                newRow += `<p ${showTooltip}><span class="label ${typeLabel}" style='font-weight: lighter;'">${String(row.codigo_siteds).toUpperCase()}</span></p>`;
              }

              if (!row.codigo_siteds || String(row.codigo_siteds).includes("T") || String(row.codigo_siteds).length <= 10) {
                newRow +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Regularizar código siteds</b>" style="float:right">';
                newRow += `<a href="javascript:void(0)" onclick="onClickRegularizeSiteds('${row.id_cita}', ${row.id_asegurado}, ${row.id_establecimiento}, ${row.tipo_atencion_id}, ${row.consultorio_id}, '${row.fecha_atencion}')" class="btn btn-warning btn-xs">`;
                newRow += '<i class="fa fa-hospital-o"></i></a></div>';
              }

/*               if (row.id_estado_cita == 4){
                newRow +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>generar siteds</b>" style="float:right">';
                // newRow += '<a href="javascript:void(0)" onclick="modalSiteds(' + row.id_cita +',' + row.id_asegurado +')" class="btn btn-warning btn-xs">';
                newRow += '<a href="javascript:void(0)" onclick="generarCodigoSiteds(' + row.id_cita + ', ' + row.id_asegurado + ', ' + row.id_establecimiento + ', ' + row.tipo_atencion_id + ', ' + row.consultorio_id + ')" class="btn btn-warning btn-xs">';
                // newRow += '<i class="fa fa-edit"></i></a></div>';

              } else {
                newRow +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>generar siteds</b>" style="float:right">';
                // newRow += '<a href="javascript:void(0)" onclick="modalSiteds(' + row.id_cita +',' + row.id_asegurado +')" class="btn btn-warning btn-xs">';
                newRow += '<a href="javascript:void(0)" onclick="generarCodigoSiteds(' + row.id_cita + ', ' + row.id_asegurado + ', ' + row.id_establecimiento + ', ' + row.tipo_atencion_id + ', ' + row.consultorio_id + ')" class="btn btn-warning btn-xs">';
                // newRow += '<i class="fa fa-edit"></i></a></div>';
              } */

              
              return newRow;
          },
          "bSortable": false,
          "aTargets": [22],
      },
      {
        "mRender": function(data, type, row) {
          var newRow = "";
          if (row.id_estado_reg == 1 && row.estado_update == 1) {
            newRow += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>">';
            newRow += '<a href="{{url("/")}}/ver_prestacion_modificada/'+row.id+'" class="btn btn-info-ant btn-xs">';
            newRow += '<i class="fa fa-eye"></i></a></div>';
          } else if (row.id_estado_reg == 1 && row.ruta_prestacion != null && row.para_modificar == "1") {
            newRow += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>">';
            newRow += '<a href="{{url("/")}}/edit_prestacion/'+row.id+'" class="btn btn-info-ant btn-xs">';
            newRow += '<i class="fa fa-edit"></i></a></div>';
          }
          return newRow;
        },
        "bSortable": false,
        "aTargets": [23],
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

function anularAtencion(id_atencion,fecha_atencion) {
  if(validacionFechaDelDia(convertirFecha(fecha_atencion))){
    bootbox.dialog({
        title: "Anulación de Atención Médica",
        message: '<p>¿Deseas anular la atención médica?</p>' +
                '<textarea id="motivo_anulacion" class="form-control" placeholder="Ingrese el motivo de la anulación..." required></textarea>',
        buttons: {
            cancel: {
                label: "Cancelar",
                className: "btn-secondary"
            },
            confirm: {
                label: "Confirmar",
                className: "btn-danger",
                callback: function() {
                  var motivo = document.getElementById("motivo_anulacion").value.trim();
                  if (motivo === "") {
                    document.getElementById("error_motivo").style.display = "block";
                    return false; 
                  }
                  fn_anularAtencion(id_atencion,motivo);
                }
            }
        }
    });
  }
}

function fn_anularAtencion(id_prestacion,motivo) {
  
    $.ajax({
        url: '/anular_prestacion',
        type: "POST",
        data: {
            id_prestacion: id_prestacion,
            motivo: motivo,
            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token de Laravel
        },
        dataType: "json",
        success: function(result) {
            if (result.sw == true) {
                location.href = "all_citas_medico_atenciones";
            } else {
                bootbox.alert("Ocurrió un error al anular la prestación.");
            }
        },
        error: function() {
            bootbox.alert("Error en la solicitud. Intente nuevamente.");
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



$(document).ready(function() {
      $("#btn_show_errors").click(function() {
        $("#table_errors").toggle(500);
        if ($("#table_errors").is(":visible")) {
          $(this).text("Ocultar Errores");
        } else {
          $(this).text("Mostrar Errores");
        }
      })
    })

  async function onClickRegularizeSiteds(id, aseguradoId, id_establecimiento, id_tipo_atencion, servicioId, dateCita) {
    const dateCitaMoment = moment(dateCita, "DD/MM/YYYY");
    const currentDate = moment();
    if (dateCitaMoment.isSame(currentDate, 'day')) {
        generarCodigoSiteds(id, aseguradoId, id_establecimiento, id_tipo_atencion, servicioId);
    } else {
      const session = {!! json_encode(Auth::user()) !!}
      const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
      if (hasPermissionUpdateSitedsManual) {
          const resultModal = await showModalSitedsManual();
          if (resultModal && resultModal.isConfirmed && resultModal.value) {
              handlerSaveCodeSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
          }
      } else {
          Swal.fire({
              icon: 'warning',
              title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>PLAZO DE ACREDITACIÓN EXPIRADO</span></div>",
              html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>${dateCita}</b>.</span></div>`,
              confirmButtonText: 'ENTENDIDO',
          });
      }
    }
  }

  // TAGSITEDS
  function generarCodigoSiteds(id, aseguradoId, id_establecimiento, id_tipo_atencion, servicioId) {
      Swal.fire ({
          title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
          html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          onBeforeOpen: () => {
              Swal.showLoading()
          },
      })
      const body = {
          "idCorrelativo": id,
          "aseguradoId": aseguradoId,
          "establecimientoId": id_establecimiento,
          "tipoAtencionId": id_tipo_atencion,
          "servicioId": servicioId
      };
      $.ajax({
          url:'/v2/ws-dirsapol-siteds',
          type:'POST',
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(body),
          success: (result, textStatus, jqXHR) => {
                if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
                    handlerSaveCodeSiteds(id, result.nuAutorizacion, result.message, jqXHR.status);
                } else {
                  const session = {!! json_encode(Auth::user()) !!}
                  const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                  if (hasPermissionUpdateSitedsManual) {
                    showModalSitedsManual()
                    .then((resultModal) => {
                      if (resultModal && resultModal.isConfirmed && resultModal.value) {
                          handlerSaveCodeSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
                      }
                    })
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                      html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                              <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para generar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                              </div>`,
                      confirmButtonText: 'ENTENDIDO',
                    });
                  }
                }
          },
          error: function(xhr, status, error){
              const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
          }, 
          complete: function(xhr, status) {
              Swal.hideLoading();
          }
      })
  }

  function handlerSaveCodeSiteds(id, nuAutorizacion, message = null, status = null) {
      Swal.fire ({
          title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
          html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Guardando el codigo siteds...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          onOpen: () => {
              Swal.showLoading();
          }
      })
      const auth = {!! json_encode(Auth::user()) !!};
      const actionUrl = "{{ route('update_siteds') }}";
      let frmsiteds = $('<form/>', {
          id: 'frmsiteds',
          name: 'frmsiteds',
          method: 'POST',
          action: actionUrl,
      });

      frmsiteds.append($('<input>', { type: 'hidden', name: "_token", value: "{{ csrf_token() }}" }));
      frmsiteds.append($('<input>', { type: 'hidden', id: 'id', name: 'id', value: id }));
      frmsiteds.append($('<input>', { type: 'hidden', id: 'id_user', name: 'id_user', value: auth.id }));
      frmsiteds.append($('<input>', { type: 'hidden', id: 'codigo_siteds', name: 'codigo_siteds', value: nuAutorizacion }));
      frmsiteds.append($('<input>', { type: 'hidden', id: 'retorno', name: 'retorno', value: 2 }));

      $('body').append(frmsiteds);

      frmsiteds.on('submit', function(event) {
          event.preventDefault();
          $.ajax({
              url: frmsiteds.attr('action'),
              type: 'POST',
              data: $(this).serialize(),
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(result) {
                Swal.fire({
                  icon: (parseInt(status) === 201 || parseInt(status) === 200) ? "success" : 'warning',
                  title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                  html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${message || " Operación Exitosa"}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                });
                fn_ListarBusqueda();
              },
              error: function(jqXHR, textStatus, errorThrown) {},
              complete: function(xhr, status) {
                  Swal.hideLoading();
                  frmsiteds.remove();
              }
          }); 
      });

      frmsiteds.trigger('submit');
  };

  function generarCodigoSitedsMassive() {
    Swal.fire ({
        title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading ()
        },
    })
    $.ajax({
      url:'/generate-siteds-massive-citas',
      type:'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify({}),
      success: (result) => {
        console.log(result);
          let htmlString = `
              <p style="font-family: Arial, Helvetica, sans-serif; font-weight: light; font-size: 16px">${result.message || " - "}</p>
              <hr>
              <div style="display: flex; flex-direction: row; justify-content: center; font-size: 16px">
                  <table style="width: 70%; border-collapse: separate; border-spacing: 15px;">
                      <tr style="text-align: left">
                          <td style="border-bottom: 1px solid #060606; width: 55%">
                              <strong><i class="fa fa-list"></i> REGISTROS TOTALES:</strong>
                          </td>
                          <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_total ?? 0}</td>
                      </tr>

                      <tr style="text-align: left">
                          <td style="border-bottom: 1px solid #060606; width: 55%">
                              <strong><i class="fa fa-check-square-o"></i> CASOS EXITOSOS:</strong>
                          </td>
                          <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_exito ?? 0}</td>
                      </tr>

                      <tr style="text-align: left">
                          <td style="border-bottom: 1px solid #060606; width: 55%">
                              <strong><i class="fa fa-exclamation-triangle"></i> CASOS FALLIDOS:</strong>
                          </td>
                          <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_error ?? 0}</td>
                      </tr>
                  </table>
              </div>
              
          `;
          let customClass = "";
          if (result && result.respuesta_error && result.respuesta_error.length > 0) {
            customClass = "swal-wide";
            let tableBodyString = "";
            result.respuesta_error.forEach((element, index) => {
              tableBodyString += `<tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${index + 1}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.cita_id || "-"}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.nroDocIdent || "-"}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; color: red ">${element.message || "-"}</td>
                                  </tr>`;
            });
            htmlString += `<div id="div_errors" >
                              <a href="#" id="btn_show_errors" style="text-decoration: none; font-family: Arial, Helvetica, sans-serif; font-weight: 500; font-size: 14px; margin-bottom: 20px">
                                <i class="fa fa-eye"></i> MOSTRAR DETALLE ERRORES
                              </a>
                              <hr>
                              <div style="max-height: 300px; overflow-y: auto;">
                                <table hidden id="table_errors" style="width: 100%; text-align: center; font-size: 16px; border-collapse: collapse">
                                  <thead style="background-color: #f2f2f2; position: sticky; top: -2px; z-index: 10;">
                                    <tr>
                                      <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 5%">#</th>
                                      <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO. CITA</th>
                                      <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO.DOC.ASEGURADO</th>
                                      <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 65%">OBSERVACIÓN</th>
                                    </tr>
                                  </thead>
                                    <tbody id="table_errors_body" style="text-align: left">${tableBodyString}</tbody>
                                </table>
                              </div>
                          </div>`;             
          }
          Swal.fire({
              icon: parseInt(result.cantidad_generados_exito) === 0 ? "warning" : "success",
              title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
              html: htmlString,
              confirmButtonText: 'ENTENDIDO',
              allowOutsideClick: false,
              allowEscapeKey: false,
              customClass: customClass,
              didOpen: function() {
                $("#btn_show_errors").click(function() {
                    $("#table_errors").toggle();
                    if ($("#table_errors").is(":visible")) {
                        $(this).html('<i class="fa fa-eye-slash"></i> OCULTAR DETALLES DE LAS OBSERVACIONES');
                    } else {
                        $(this).html('<i class="fa fa-eye"></i> MOSTRAR DETALLES DE LAS OBSERVACIONES');
                    }
                });
              }
          });
          fn_ListarBusqueda();
      },
      error: function(xhr, status, error){
          const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
          Swal.fire({
              icon: 'error',
              title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
              html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
              confirmButtonText: 'ENTENDIDO',
          });
      }, 
      complete: function(xhr, status) {
          Swal.hideLoading();
      }
    })
  }

  function showModalSitedsManual(id) {
    return new Promise((resolve, reject) => {
      Swal.fire({
          title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
          html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>La regularización manual de los códigos SITEDS queda bajo su responsabilidad. Asegúrese de revisar y actualizar correctamente los códigos.</span></div>",
          input: 'text',
          inputLabel: 'Código SITEDS',
          inputPlaceholder: "000000000001",
          showCancelButton: true,
          cancelButtonText: 'CANCELAR',
          confirmButtonText: 'GUARDAR',
          allowOutsideClick: false,
          allowEscapeKey: false,
          customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger',
              popup: 'swal-wide-form',
          },
          inputAttributes: {
              maxlength: 12,
              pattern: '[A-Za-z0-9 ]*'
          },
          buttonsStyling: false,
          inputValidator: (value) => {
              if (!value) {
                  return 'Ingresa el código SITED para continuar';
              } else if (value.length < 12) {
                  return 'El código SITEDS debe tener 12 caracteres';
              }
          }
      }).then((result) => {
          if (result.isConfirmed) {
              resolve(result);
          } else {
              reject(result);
          }
      }).catch((error) => {
          reject(error);
      });
    });
  }
  
  function validacionFechaDelDia(fecha_atencion) {
    var now = new Date();
    var inicio_dia = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0);
    var fecha_limite = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

    console.log("Inicio del día:", inicio_dia);
    console.log("Fecha límite:", fecha_limite);
    console.log("Fecha atención recibida:", fecha_atencion);

    var partes = fecha_atencion.split("/");
    if (partes.length === 3) {
        fecha_atencion = `${partes[2]}-${partes[1]}-${partes[0]}`;
    }
    
    // Crear la fecha de atención en la zona horaria local sin afectar la comparación
    var fecha_atencion_obj = new Date(partes[2], partes[1] - 1, partes[0], 0, 0, 0);
    console.log("Fecha atención convertida:", fecha_atencion_obj);

    // Verificar si la conversión fue válida
    if (isNaN(fecha_atencion_obj.getTime())) {
        bootbox.alert("Error: La fecha de atención no es válida.");
        return false;
    }

    // Comparar si la fecha de atención está dentro del rango del día actual
    if (fecha_atencion_obj >= inicio_dia && fecha_atencion_obj <= fecha_limite) {
        return true;
    } else {
        bootbox.alert({
            title: "Información",
            message: `
                <div style="text-align: center;">
                    <i class="fa fa-info-circle" style="font-size: 24px; color: #007bff;"></i>
                    <p style="margin-top: 10px;">Las atenciones solo pueden ser anuladas el mismo día hasta las 23:59 horas (antes de la medianoche).</p>
                </div>
            `,
            className: "bootbox-info"
        });
        return false;
    }
  }
  function convertirFecha(fecha) {
    if (fecha.includes("/")) {
        return fecha.split(" ")[0]; // Elimina la hora si la tiene
    }
    let fecha_solo = fecha.split(" ")[0]; // Extrae solo la fecha
    let partes = fecha_solo.split("-");
    if (partes.length === 3) {
        return `${partes[2]}/${partes[1]}/${partes[0]}`; // Formato DD/MM/YYYY
    } else {
        return "Fecha inválida";
    }
  }
</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container-fluid">
  <div class="panel panel-primary filterable">
  <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(109, 218, 132); color: green;">CE</span> Rerporte Atenciones Medicas - {{$farmacia->nombre}}</h3>
    </div>
    <div class="panel-body">

      <form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_atenciones')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{$farmacia->id_establecimiento}}">



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
            <label class="form-control-sm">Nombres</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
          </div>
          <input type="hidden" name="id_condicion_establecimiento" id="id_condicion_establecimiento" value="0" />
          <input type="hidden" name="id_condicion_servicio" id="id_condicion_servicio" value="0" />
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                        <div class="col-md-6">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencion('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
            <div class="col-md-6" style="padding-right:0px;padding-left:0px">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencionDetallado('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
              </a>
            </div>
          </div>

        </div>

        <div class="row">

          @if(intval(Auth::user()->flagfiscalizador) === 1 && intval(Auth::user()->flagservicios) === 1)
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Medico</label>
            <select class="form-control input-sm" name="id_medico" id="id_medico" style="width: 100%">
            <option value="0">-Seleccionar-</option>
            <?php foreach ($medicos as $row) : ?>
                <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?>, <?php echo $row->paterno ?> <?php echo $row->materno ?></option>
                <?php endforeach; ?>
                </select>
          </div>
          @else
          <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico ?>">
          @endif
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Atenciones</label>
            <!--nivel 2 !-->
            @if (intval(Auth::user()->id_farmacia) == 14 || intval(Auth::user()->id_farmacia) == 22 || intval(Auth::user()->id_farmacia) == 107 || intval(Auth::user()->id_farmacia) == 111)
            <select class="form-control input-sm" name="id_atencione" id="id_atencione" onchange="getCargaServicioAtencion()" style="width: 100%">
              <option value="0">-Seleccionar-</option>
              <?php foreach ($atencione as $row) : ?>
              @if (intval($row->id) !== 2 && intval($row->id) !== 6 && intval($row->id) !== 7 && intval($row->id) !== 8)
              <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
              @endif
              <?php endforeach; ?>
            </select>
            @else
            <select class="form-control input-sm" name="id_atencione" id="id_atencione" onchange="getCargaServicioAtencion()" style="width: 100%">
              <option value="0">-Seleccionar-</option>
              <?php foreach ($atencione as $row) : ?>
              @if (intval($row->id) !== 2 && intval($row->id) !== 3 && intval($row->id) !== 6 && intval($row->id) !== 8)
              <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
              @endif
              <?php endforeach; ?>
            </select>
            @endif
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Servicio</label>
            <select class="form-control input-sm" name="id_servicio" id="id_servicio" onchange="getSubConsultorio()" style="width: 100%">
              <option value="0">-Seleccionar-</option>
            </select>
          </div>
          
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
              <div id="muestra-campos2" style="display: none;">
              <label class="form-control-sm">Consultorio</label>
              <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" required="" style="width: 100%">
                <option value="0">-Seleccionar-</option>
              </select>
            </div>
             </div>
             <div class="col-lg-2"></div>
                        <div class="col-lg-2">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencionDiagnostico('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Prestacion Diagnostico
              </a>
            </div>
             </div>
          </div>
        </div>
      </form>
      <div class="col-md-12">

      </div>
    </div>
  </div>
</div>

<div id="link-reporte"></div>

@include('frontend.includes.footer')
@endsection
