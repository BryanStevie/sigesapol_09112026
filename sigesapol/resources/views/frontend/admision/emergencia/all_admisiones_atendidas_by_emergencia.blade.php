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

.select2-container {
    width: 100% !important;
    max-width: 100% !important;
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

  .swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
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
    "sAjaxSource": "/listar_prestacion_ajax_emergencia",
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
      var hora_ini = $('#hora_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var hora_fin = $('#hora_fin').val();
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nombre_beneficiario = $('#nombre_beneficiario').val();
      var estado = $('#estado').val();
      var id_prioridad = $('#id_prioridad').val();
      var id_topico = $('#id_topico').val();
      var id_establecimiento = $('#id_establecimiento').val();
      var id_prestacion_buscar = $('#id_prestacion_buscar').val();
      <?php if(Auth::user()->flagfarmacias==1 || $permiso->crear == 0): ?>
              var id_medico = 0;
      <?php else: ?>
          if(estado == 2){
            var id_medico = 0;
          }
          else{
            var id_medico = <?php echo Auth::user()->id; ?>;
          }
      <?php endif; ?>
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
          hora_ini: hora_ini,
          fecha_fin: fecha_fin,
          hora_fin: hora_fin,
          dni_beneficiario: dni_beneficiario,
          nombre_beneficiario: nombre_beneficiario,
          id_medico: id_medico, 
          estado: estado,
          id_establecimiento: id_establecimiento,
          id_topico: id_topico, 
          id_prioridad: id_prioridad,
          id_prestacion_buscar: id_prestacion_buscar,
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
          if (row.id_prestacion != null) id = row.id_prestacion;
          return id;
        },
        "bSortable": false,
        "aTargets": [0]
      },
      {
        "mRender": function(data, type, row) {
          var fecha_atencion = "";var horario = "";
          if (row.fecha_atencion != null) fecha_atencion = row.fecha_atencion + " - ";
          if (row.horario != null) fecha_atencion+= row.horario;
          return fecha_atencion;
        },
        "bSortable": false,
        "aTargets": [1]
      },
      {
        "mRender": function(data, type, row) {
          var fecha_ingreso = "";var hora_ingreso = "";
        	if(row.fecha_ingreso_admision!= null)fecha_ingreso = row.fecha_ingreso_admision + " - ";
          if(row.hora_ingreso_admision!= null)fecha_ingreso += row.hora_ingreso_admision;
          return fecha_ingreso;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          var nro_doc_ident = "";
          if (row.nro_doc_ident != null) nro_doc_ident = row.nro_doc_ident;
          return nro_doc_ident;
        },
        "bSortable": false,
        "aTargets": [3],
        "className": 'control'
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
        "aTargets": [4]
      },
      {
          "mRender": function (data, type, row) {
              var grado = "";
              if(row.grado!= null){ 
                  grado = row.grado;
              }
              else{
                if (row.parentesco!='TITULAR'){
                  grado = row.grado2;
                }
              }

              //if(row.grado!= null)grado = row.grado;
              return grado;
          },
          "bSortable": false,
          "aTargets": [5]
          
      },
      {
          "mRender": function (data, type, row) {
              var parentesco = "";
              if(row.parentesco!= null)parentesco = row.parentesco;
              return parentesco;
          },
          "bSortable": false,
          "aTargets": [6]
          
      },
      {
          "mRender": function (data, type, row) {
              var sexo = "";
              if(row.sexo!= null)sexo = row.sexo;
              return sexo;
          },
          "bSortable": false,
          "aTargets": [7]
          
      },
      {
          "mRender": function (data, type, row) {
              var edad = "";
              if(row.edadpaciente!= null)edad = row.edadpaciente;
              return edad;
          },
          "bSortable": false,
          "aTargets": [8] 
          
      },
      {
        "mRender": function(data, type, row) {
          var telefono = "";
          if (row.telefono_paciente != null) telefono = row.telefono_paciente;
          return telefono;
        },
        "bSortable": false,
        "aTargets": [9]
      },
      {
        "mRender": function(data, type, row) {
          var prioridad = "";
          if (row.prioridad != null) prioridad = row.prioridad;
          return prioridad;
        },
        "bSortable": false,
        "aTargets": [10]
      },
      {
        "mRender": function(data, type, row) {
          var descripcion = "";
          if (row.descripcion != null) descripcion = row.descripcion;
          return descripcion;
        },
        "bSortable": false,
        "aTargets": [11]
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
        "aTargets": [12]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          var estado_de = $('#estado').val();
          
          if (row.estado == 2 && estado_de == 2 ) {
            <?php if(Auth::user()->tipo_registro==1  && $permiso->crear == 1): ?>  

                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender</b>" style="float:left;padding-left:3px">';
    						html += '<a href="/formulario_atencion_emergencia/'+row.id+'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';       
              
            <?php endif; ?>
          } else {
            if (row.estado == 2 && estado_de == 0 ) {
              html +=
                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
              html += '<a href="/ver_prestacion_emergencias/<?php echo Auth::user()->id ?>/' + row.id_prestacion+
                    '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
                    
            }else{
              if (row.estado >= 3) {
                if (row.id_estado_reg == 0) {
                  <?php if($permiso->crear == 1): ?>
                      html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender</b>" style="float:left;padding-left:3px">';
                      html += '<a href="/formulario_atencion_emergencia/'+row.id+'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';       
                  <?php endif; ?>
                }else{
                  <?php if(Auth::user()->tipo_registro==1): ?>
                    html +=
                          '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
                    html += '<a href="/ver_prestacion_emergencias/<?php echo Auth::user()->id ?>/' + row.id_prestacion+
                          '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
                <?php endif; ?>
                }
                
              }else
              {
                  var observacion = "";
                  if (row.observacion_elimina_paciente != null) observacion = row.observacion_elimina_paciente;
                  return observacion;

                  //html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
                
              }
            }
          }


          return html;
        },
        "bSortable": false,
        "aTargets": [13],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";

          if (row.ruta_prestacion != null && row.id_estado_reg == 1) {
            if(row.id>0){
              html +=
                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
                html += '<a href="/' + row.flag + '/' + row.ruta_prestacion +
                '" target="_blank" class="btn '; 
                if(row.flag==1){
                  html +=' btn-primary ';
                }else{
                  html +=' btn-electronico';
                }
                html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
            }else{
               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
                html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+row.ruta_prestacion+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            }                      
          }else
          {
            if (row.estado >= 3) {
                html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-download"></i></a></button>';
            }
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
          var html = "";
          if (row.ruta_receta != null && row.id_estado_reg == 1) {
            var ruta_receta = row.ruta_receta.replace("{", "").replace("}", "");
            var ruta = ruta_receta.split(",");
            let flag_receta = row.flag_receta.replace("{", "").replace("}", "");
            let flag_firma = flag_receta.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                if(row.id>0){
                  html +=
                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                  html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                  '" target="_blank" class="btn ';
                  if(flag_firma[0]==1){
                    html +=' btn-primary ';
                  }else{
                    html +=' btn-electronico';
                  }
                  html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
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
        "aTargets": [15],
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

            let arrayIdsLab = [];
            if (row.lid) {
              arrayIdsLab = row.lid.replace(/[{}]/g, "").split(",").map(Number);
            }
            let href = "";
            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                href = parseInt(row.nivel_establecimiento) === 3 ? `/ver_laboratorio_imprimir/${flag_firma[r]}` : `/laboratorio/rua/pdf/${arrayIdsLab[r]}`;
                if(row.id>0) {
                  html += `<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Laboratorio</b>" style="float:left;padding-left:3px"><a href="${href}" target="_blank" class="btn`;
                  if(flag_lab_firma[0]==1){
                    html +=' btn-primary ';
                  }else{
                    html +=' btn-electronico';
                  }
                  html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
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
        "aTargets": [16],
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
                if(row.id>0){
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px">';
                html += '<a href="/ver_radiologia_imprimir/' + flag_firma[r] +
                  '" target="_blank" class="btn ';
                  if(flag_rad_firma[0]==1){
                    html +=' btn-primary ';
                  }else{
                    html +=' btn-electronico';
                  }
                  html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
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
        "aTargets": [17],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_interconsulta != null) {
            var ruta_interconsulta = row.ruta_interconsulta.replace("{", "").replace("}", "");
            var ruta = ruta_interconsulta.split(",");
            let flag_interconsulta = row.flag_interconsulta.replace("{", "").replace("}", "");
            let flag_firma = flag_interconsulta.split(",");

            for (var r = 0; r < ruta.length; r++) {
              if (ruta[r] != "NULL") {
                if(row.id>0){
                    html +=
                      '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                    html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +
                      '" target="_blank" class="btn ';
                    if(flag_firma[0]==1){
                      html +=' btn-primary ';
                    }else{
                      html +=' btn-electronico';
                    }
                    html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
                }else{
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px">';
                    html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+ruta[r]+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                }
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [18],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          if (row.ruta_rpta_interconsulta != null) {
            var ruta_rpta_interconsulta = row.ruta_rpta_interconsulta.replace("{", "").replace("}", "");
            var rutarpta = ruta_rpta_interconsulta.split(",");
            let flag_rpta_interconsulta = row.flag_rpta_interconsulta.replace("{", "").replace("}", "");
            let flag_rpta_firma = flag_rpta_interconsulta.split(",");

            for (var r = 0; r < rutarpta.length; r++) {
              if (rutarpta[r] != "NULL") {
                if(row.id>0){
                    html +=
                      '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                    html += '<a href="/' + flag_rpta_firma[0] + '/' + rutarpta[r] +
                      '" target="_blank" class="btn';
                    if(flag_rpta_firma[0]==1){
                      html +=' btn-warning ';
                    }else{
                      html +=' btn-electronico';
                    }
                    html +=' btn-xs"><i class="fa fa-download"></i></a></div>';
                }else{
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Radiologia</b>" style="float:left;padding-left:3px">';
                    html += '<a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/'+rutarpta[r]+'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a></div>';
                }
              } else {
                html +=
                  '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta Rpta</b>" style="float:left;padding-left:3px"><button class="btn btn-warning btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [19],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      /*{
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
          /*if (row.ruta_prestacion == null && row.id_estado_reg == 1) {
            <?php if(Auth::user()->tipo_registro==1): ?>
              html +=
                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
              html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
                .id_prestacion + ')"><i class="fa fa-remove"></i></a></div>';
            <?php else: ?>
              html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';  
            <?php endif; ?>
          } else {
            html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
          }*/
          var estado_de = $('#estado').val();

          if (row.estado == 2 && (estado_de == 2)) {
              <?php if(Auth::user()->tipo_registro==1): ?>    
                <?php if($permiso->crear == 1): ?>     

                  html +=
                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Paciente</b>">';
                  html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularPaciente(' + row
                    .id + ')"><i class="fa fa-remove"></i></a></div>';
                <?php endif; ?>
              <?php endif; ?>
          } else {
            if (row.id_estado_reg == 0 && estado_de == 0 ) {
              <?php if(Auth::user()->tipo_registro==1): ?>  
                <?php if($permiso->crear == 1): ?>
                html +=
                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Paciente</b>">';
                  html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularPaciente(' + row
                    .id + ')"><i class="fa fa-remove"></i></a></div>';
                <?php endif; ?>
              <?php endif; ?>     
              //alert(row.estado);
              //html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
            }else{
              if (row.estado >= 3) {
                if (row.ruta_prestacion == null && row.id_estado_reg == 1) {
                  <?php if(Auth::user()->tipo_registro==1): ?>
                    <?php if($permiso->crear == 1): ?>
                        html +=
                          '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atencion</b>">';
                        html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion(' + row
                        . id_prestacion + ',' + row.id + ')"><i class="fa fa-remove"></i></a></div>';
                    <?php endif; ?>
                  <?php endif; ?>
                }
                else{
                  html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                }
              }
            }              
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [20],
        "visible": <?php echo ($permiso->crear == 1) ? "true" : "false" ?>,
        "className": "text-center",
      },
      {
        "mRender": function (data, type, row) {
        	let htmlString = '<div style="text-align: center">';
          if (row.cod_siteds_susalud) {
            let isSitedsTemp = false;
            if (row.cod_siteds_susalud && (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === 'T' || row.cod_siteds_susalud.length === 4)) {
              isSitedsTemp = true;
            }
            htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.cod_siteds_susalud}</span></p>`;
            if (isSitedsTemp) {
              htmlString += `<button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_servicio})"><i class="fa fa-check-square"></i></button>`
            }
          }
			    return htmlString += "</div>";
        },
        "bSortable": false,
        "aTargets": [21]
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

/*function modalMotivoEliminacion(id) {
	$('#openModalHistoria').modal('show');

  $.ajax({
    url: "modal_historia/" + id,
    type: "GET",
    success: function(result) {
      $("#diveditpregOpc").html(result);
    }
  });
}*/

  // TAGSITEDS
  async function regularizeCodeSiteds(id, aseguradoId, establecimientoId, servicioId) {
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
    const body = {
        "idCorrelativo": id,
        "aseguradoId": aseguradoId,
        "establecimientoId": establecimientoId,
        "tipoAtencionId": "21",
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
            updateSiteds(id, result.nuAutorizacion, result.message);
          } else {
            const session = {!! json_encode(Auth::user()) !!}
            const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;

            Swal.fire({
              icon: 'warning',
              title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
              html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                      <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                    </div>`,
              showCancelButton: true,
              showConfirmButton: hasPermissionUpdateSitedsManual,
              cancelButtonText: hasPermissionUpdateSitedsManual ? "CANCELAR" : "ENTENDIDO",
              confirmButtonColor: "#5cb85c",
              cancelButtonColor: "#d9534f",
              confirmButtonText: "INGRESAR MANUALMENTE",
            }).then((result) => {
              if (result.isConfirmed) {
                const session = {!! json_encode(Auth::user()) !!}
                const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                showModalSitedsManual().then((resultModal) => {
                  if (resultModal && resultModal.isConfirmed && resultModal.value) {
                    updateSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.")
                  }
                })
              }
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
        url: `/emergencia/${id}/update-code-siteds`,
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
            fn_ListarBusqueda();
        },
        error: function(jqXHR, textStatus, errorThrown) {},
        complete: function(xhr, status) {
            Swal.hideLoading();
            Swal.close();
        }
    });
  };

  function showModalSitedsManual(id) {
    return new Promise((resolve, reject) => {
      Swal.fire({
          title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
          html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
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

</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Lista de Atenciones de Emergencia
      </h3>
    </div>
    <div class="panel-body">

      <form class="form-horizontal" method="post" action="#">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{$farmacia->id_establecimiento}}">
        <!--input type="hidden" name="id_medico" id="id_medico" value="<?php //echo $medico ?>"-->
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Desde</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde ?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
            <label class="control-label">Hora inicial</label>
            <input type="time" id="hora_ini" name="hora_ini" value="<?php echo $hora_ini; ?>" class="form-control" required>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Hasta</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaDesde ?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
            <label class="control-label">Hora Final</label>
            <input type="time" id="hora_fin" name="hora_fin" value="<?php echo $hora_fin; ?>" class="form-control" required>
          </div>
          
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Nro. Documento</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
          </div>

          <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
            <label class="form-control-sm">Nombres</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
          </div>
          <input type="hidden" name="id_condicion_establecimiento" id="id_condicion_establecimiento" value="0" />
          <input type="hidden" name="id_condicion_servicio" id="id_condicion_servicio" value="0" />
        </div>

        <div class="row">

          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Prioridad</label>
            <select id="id_prioridad" name="id_prioridad"   onchange="getTopico();" class="form-control">
              <option value="0">-Seleccionar-</option>
              <?php //foreach($opcionesprioridad as $e) ?>
                <!--option value="{//{$e->id}}">{//{$e->descripcion}}</option-->
                <option value="1">I - GRAVEDAD SÚBITA EXTREMA</option>
                <option value="2">II - URGENCIA MAYOR</option>
                <option value="3">III - URGENCIA MENOR</option>
                <option value="4">IV - PATOLOGÍA AGUDA COMÚN</option>
              <?php //endforeach ?>
            </select>
          </div>
          
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
              <label class="form-control-sm">Area</label>
              <select class="form-control input-sm" name="id_topico" id="id_topico" required="" style="width: 100%">
                <option value="0">-Seleccionar-</option>
                <option value="1">SHOCK TRAUMA</option>
                <option value="2">OBS TRAUMATOLOGIA</option>
                <option value="4">OBS PEDIATRIA</option>
                <option value="6">OBS GINECOLOGÍA Y OBSTETRICIA</option>
                <option value="10">OBS CIRUGIA</option>
                <option value="9">OBS MEDICINA</option>
                <option value="3">TOP TRAUMATOLOGIA</option>
                <option value="5">TOP PEDIATRIA</option>
                <option value="7">TOP GINECOLOGÍA Y OBSTETRICIA</option>
                <option value="11">TOP CIRUGIA</option>
                <option value="8">TOP MEDICINA</option>
                <option value="19">CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>
              </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Estado</label>
            <select class="form-control input-sm" id="estado" name="estado" tabindex="5" style="width: 100%">
              <option value="2" selected="selected">Pendiente</option>
              <option value="3">Atendido</option>
              <option value="0">Atencion Anulada</option>
              <option value="4">Paciente Eliminado</option>
              <?php if(Auth::user()->flagestablecimientos==1  ||  $permiso->crear == 0): ?>  
              <option value="5">Todos</option>
            <?php endif; ?>
            </select>
          </div>
          @if(intval(Auth::user()->flagfiscalizador) === 1)
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">ID. Prestación</label>
            <input id="id_prestacion_buscar" name="id_prestacion_buscar" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" value="<?php echo $id_prestacion_buscar ?>">
          </div>
          @else
            <input type="hidden" name="id_prestacion_buscar" id="id_prestacion_buscar" value="<?php //echo $id_prestacion_buscar ?>">
          @endif

          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="col-md-4"><br/>
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>
          
            <?php if($permiso->crear == 1): ?>
            <div class="col-md-4"><br/>
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencion('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
            <!--div class="col-md-4"><br/>
              <a class="btn btn-warning form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencion2('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Consolidado
              </a>
            </div-->
            <!--div class="col-md-4" style="padding-right:0px;padding-left:0px"><br/>
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencionDetallado('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
              </a>
            </div-->
            <?php endif; ?>
          </div>
      </form>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--<table  class="table table-list-search">-->
        <!--<table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >-->
        <div class="table-responsive">
          <table id="tblPrestacion" class="table table-bordered">
            <thead>
              <tr>
                <th>N</th>
                <th>Fecha y Hora de Atención</th>
                <th>Fecha y Hora de Admisión</th>
                <th>Numero Doc</th>
                <th>Paciente</th>
                <th>Grado</th>
                <th>Parentesco</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Telefono</th>
                <th>Prioridad</th>
                <th>Area</th>
                <th>Medico</th>
                <th style="text-align:center">Atencion</th>
                <?php if($permiso->crear == 1): ?>
                <th style="text-align:center">Pdf Atencion</th>
                <th style="text-align:center;min-width:106px">Receta</th>
                <th style="text-align:center">Lab</th>
                <th style="text-align:center">Rad</th>
                <th style="text-align:center">Inter</th>
                <th style="text-align:center">RptaInter</th>
                <!--th style="text-align:center">Ref</th-->
                <th>Anular</th>
                <th>Siteds</th>
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
