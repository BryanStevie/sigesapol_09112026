@extends('frontend.layouts.master3')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
  $(document).ready(function() {
    $("#id_servicio_destino").select2({
      width: 'resolve'
    });

    $('#fecha_ini').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });

    $('#fecha_fin').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });

    $('#fecha_ini_registro').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });

    $('#fecha_fin_registro').datepicker({
      useCurrent: false,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });


    $('#btnBuscar').click(function() {
      datatablenew();
    });

    datatablenew();

  });

  function datatablenew() {
    let estado = $('#estado').val();
    console.log("Estado seleccionado:", estado); 

    var oTable1 = $('#tblPacientesEmergencias').dataTable({
      "bServerSide": true,
      "sAjaxSource": "/all_emergencia_consejeria_ajax2",
      "bProcessing": true,
      "sPaginationType": "full_numbers",
      "bFilter": false,
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
        var fecha_ini = $('#fecha_ini').val();
        var fecha_fin = $('#fecha_fin').val();
        var fecha_ini_registro = $('#fecha_ini_registro').val();
        var fecha_fin_registro = $('#fecha_fin_registro').val();
        var ndocumento = $('#ndocumento').val();
        var busqueda_datos = $('#busqueda_datos').val();
        var id_servicio_destino = $('#id_servicio_destino').val();
        var estado = $('#estado').val();
        var _token = $('#_token').val();
        oSettings.jqXHR = $.ajax({
          "dataType": 'json',
          "type": "POST",
          "url": sSource,
          "data": {
            NumeroPagina: iNroPagina,
            NumeroRegistros: iCantMostrar,
            fecha_ini: fecha_ini,
            fecha_fin: fecha_fin,
            fecha_ini_registro: fecha_ini_registro,
            fecha_fin_registro: fecha_fin_registro,
            ndocumento: ndocumento,
            busqueda_datos: busqueda_datos,
            id_servicio_destino: id_servicio_destino,
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
            return row.id;
          },
          "bSortable": false,
          "aTargets": [0]
        },
        {
          "mRender": function(data, type, row) {
            return '<b>IPRESS:</b> ' + row.establecimiento + 
                  '<br><b>Tipo de Atención:</b> EMERGENCIA' + 
                  '<br><b>UBICACIÓN DE PACIENTE:</b> ' + row.servicio_cargo + ' / CAMA: ' + row.cama;
          },
          "bSortable": false,
          "aTargets": [1]
        },
        {
          "mRender": function(data, type, row) {
            var fecha_registro = '<b>Responsable: </b>' + row.medico_nombre_prestacion + " " + row.medico_paterno_prestacion + " " + row.medico_materno_prestacion + '<br>' + 
              '<b>Fecha/Hora: </b>' + row.fecha_ingreso + ' &nbsp;' + row.hora_ingreso + '<br>' + '<b>Especialidad: </b>' + row.especializacion_medico;
            return fecha_registro;
          },
          "bSortable": false,
          "aTargets": [2]
        },
        {
          "mRender": function(data, type, row) {
            var paciente = '<strong>Nombres:</strong> ' + row.asegurado_paterno + ' ' + row.asegurado_materno + ' ' + row.asegurado_nombre + '<br>';
            var edad = '';
            if (row.edadpaciente != null) {
              var edadObj = calcularEdadPersonaReturn(row.fecha_nac); 
              edad = '<strong>Edad:</strong> ' + 
                    edadObj.anios + ' a, ' + 
                    edadObj.meses + ' m Y ' + 
                    edadObj.dias + ' d';

              if (row.sexo) {
                let genero = '';
                if (row.sexo.toUpperCase() === 'M') {
                  genero = 'MASCULINO';
                } else if (row.sexo.toUpperCase() === 'F') {
                  genero = 'FEMENINO';
                } else {
                  genero = row.sexo.toUpperCase();
                }
                edad += ' / (' + genero + ')';
              }
              edad += '<br>';
            }
            var parentesco = '<strong>Parentesco:</strong> ' + row.tipo_beneficiario;
            return paciente + edad + parentesco;
          },
          "bSortable": false,
          "aTargets": [3]
        },
        // {
        //   "mRender": function(data, type, row) {
        //     var sexo = "";
        //     if (row.sexo != null) sexo = row.sexo;
        //     return sexo;
        //   },
        //   "bSortable": false,
        //   "aTargets": [4]

        // },
        // {
        //   "mRender": function(data, type, row) {
        //     var edad = "";
        //     if (row.edadpaciente != null) edad = calcularEdadPersonaReturn(row.fecha_nac);
        //     return edad;
        //   },
        //   "bSortable": false,
        //   "aTargets": [5]

        // },
        {
        "mRender": function(data, type, row) {
          var html = "";

          if (row.estado == 3 || row.estado == 6) {
              html += '<table><tr><td>';
              html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Registrar</b>">';
              html += ' <a href="formulario_atencion_emergencia_consejeria/' + row.id + '" class="btn btn-success btn-sm">';
              html += ' <i class="fa fa-edit"></i></a>';
              html += '</div>';
              html += '</td><td style="padding-left:8px;">';
              html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Historial</b>">';
              html += ' <a href="ver_prestaciones_emergencia_consejeria/' + row.id + '" class="btn btn-warning btn-sm">';
              html += ' <i class="fa fa-list"></i></a>';
              html += '</div>';
              html += '</td></tr></table>';
          }

          if (row.estado == 5) {
              html += '<table><tr><td>';
              html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Registrar</b>">';
              html += ' <a href="formulario_atencion_emergencia_consejeria/' + row.id + '" class="btn btn-success btn-sm">';
              html += ' <i class="fa fa-edit"></i></a>';
              html += '</div>';
              html += '</td><td style="padding-left:8px;">';
              html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Historial</b>">';
              html += ' <a href="ver_prestaciones_emergencia_consejeria/' + row.id + '" class="btn btn-warning btn-sm">';
              html += ' <i class="fa fa-list"></i></a>';
              html += '</div>';
              html += '</td></tr></table>';
          }
          return html;
        },
          "bSortable": false,
          "aTargets": [4],
          "className": "text-center",
        }
      ]
    });
  }

  function calcularEdadPersonaReturn(fechaNacimiento) {
    if (!fechaNacimiento) return { anios: 0, meses: 0, dias: 0 };

    if (fechaNacimiento.includes('/')) {
      var partes = fechaNacimiento.split('/');
      fechaNacimiento = `${partes[2]}-${partes[1]}-${partes[0]}`;
    }

    var nacimiento = new Date(fechaNacimiento);
    var hoy = new Date();

    if (isNaN(nacimiento.getTime())) {
      return { anios: 0, meses: 0, dias: 0 }; 
    }

    var anios = hoy.getFullYear() - nacimiento.getFullYear();
    var meses = hoy.getMonth() - nacimiento.getMonth();
    var dias = hoy.getDate() - nacimiento.getDate();

    if (dias < 0) {
      meses--;
      dias += new Date(hoy.getFullYear(), hoy.getMonth(), 0).getDate();
    }

    if (meses < 0) {
      anios--;
      meses += 12;
    }

    return {
      anios: anios,
      meses: meses,
      dias: dias
    };
  }


  function reporteAtencionDetalladoConsejeriaEmergencia(idUser) {
    let fecha_ini = $('#fecha_ini').val();
    let fecha_fin = $('#fecha_fin').val();
    let fecha_ini_registro = $('#fecha_ini_registro').val();
    let fecha_fin_registro = $('#fecha_fin_registro').val();
    let dni = $('#ndocumento').val();
    let estado = $('#estado').val();
    let id_medico = $('#id_medico').val();
    let id_servicio_destino = $('#id_servicio_destino').val();
    let busqueda_datos = $('#busqueda_datos').val();
    let tipo_atencion = "2";

    if (fecha_ini == "") fecha_ini = 0;
    if (fecha_fin == "") fecha_fin = 0;
    if (fecha_ini_registro == "") fecha_ini_registro = 0;
    if (fecha_fin_registro == "") fecha_fin_registro = 0;
    if (dni == "") dni = 0;
    if (busqueda_datos == "") busqueda_datos = 0; 

    location.href = 'exportar_reporte_atencion_detallado_emergencia_consejeria/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + fecha_ini_registro + '/' + fecha_fin_registro + '/' + dni + '/' + estado + '/' + id_medico + '/' + id_servicio_destino + '/' + busqueda_datos + '/' + tipo_atencion;
  }

  // function reporteAtencionDetalladoConsejeriaEmergencia2(idUser) {
  //   let fecha_ini_registro = $('#fecha_ini_registro').val();
  //   let fecha_fin_registro = $('#fecha_fin_registro').val();
  //   let dni = $('#ndocumento').val();
  //   let estado = $('#estado').val();
  //   let id_medico = $('#id_medico').val();
  //   let id_servicio_destino = $('#id_servicio_destino').val();
  //   let busqueda_datos = $('#busqueda_datos').val();
  //   let tipo_atencion = "2";

  //   if (fecha_ini_registro == "") fecha_ini_registro = 0;
  //   if (fecha_fin_registro == "") fecha_fin_registro = 0;
  //   if (dni == "") dni = 0;
  //   if (busqueda_datos == "") busqueda_datos = 0; 

  //   location.href = 'exportar_reporte_atencion_detallado_emergencia_consejeria2/' + idUser + '/' + fecha_ini_registro + '/' + fecha_fin_registro + '/' + dni + '/' + estado + '/' + id_medico + '/' + id_servicio_destino + '/' + busqueda_datos + '/' + tipo_atencion;
  // }

  $(function () {
      $("#fecha_ini_registro, #fecha_fin_registro").val("");

      $("#fecha_ini, #fecha_fin, #fecha_ini_registro, #fecha_fin_registro").datepicker({
          dateFormat: "yy-mm-dd"
      });

      $("#fecha_ini, #fecha_fin, #fecha_ini_registro, #fecha_fin_registro").on("click", function () {
          $(this).datepicker("show");
      });

      $("#fecha_ini, #fecha_fin").on("focus", function () {
          $("#fecha_ini_registro, #fecha_fin_registro").val("");
      });

      $("#fecha_ini_registro, #fecha_fin_registro").on("focus", function () {
          $("#fecha_ini, #fecha_fin").val("");
      });
  });

  // function exportarDetallado(userId) {
  //   const fechaIni = document.getElementById('fecha_ini').value.trim();
  //   const fechaFin = document.getElementById('fecha_fin').value.trim();
  //   const fechaIniReg = document.getElementById('fecha_ini_registro').value.trim();
  //   const fechaFinReg = document.getElementById('fecha_fin_registro').value.trim();

  //   if (fechaIni && fechaFin) {
  //     reporteAtencionDetalladoConsejeriaEmergencia(userId);
  //   } else if (fechaIniReg && fechaFinReg) {
  //     reporteAtencionDetalladoConsejeriaEmergencia2(userId);
  //   } else {
  //     alert('Debe ingresar un rango de fechas válido (atención o registro).');
  //   }
  // }


</script>
@endsection
@section('content')
<div class="container-fluid">

  <div class="row col-md-12">
    <div class="panel panel-primary filterable">
      <div class="panel-heading" style="padding: 0;">
        <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Modulo de Procedimientos Emergencia
        </h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" method="post" action="{{ route('all_emergencia_by_establecimiento_ajax')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
          <input type="hidden" name="id_establecimiento" id="id_establecimiento"
            value="{{Auth::user()->id_establecimiento}}">
          <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label class="form-control-sm">Fecha de Ingreso (Desde)</label>
              <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
                value="<?php echo $fechaDesde ?>" maxlength="10" data-date-end-date="0d">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label class="form-control-sm">Fecha de Ingreso (Hasta)</label>
              <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
                value="<?php echo $fechaHasta ?>" maxlength="10" data-date-end-date="0d">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label class="form-control-sm">Fecha de Registro CPMS (Inicio)</label>
              <input class="form-control input-sm" type="text" name="fecha_ini_registro" id="fecha_ini_registro" placeholder="Fecha Inicio"
                value="<?php echo $fechaInicio ?>" maxlength="10" data-date-end-date="0d">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label class="form-control-sm">Fecha de Registro CPMS (Fin)</label>
              <input class="form-control input-sm" type="text" name="fecha_fin_registro" id="fecha_fin_registro" placeholder="Fecha Fin"
                value="<?php echo $fechaFin ?>" maxlength="10" data-date-end-date="0d">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label>Servicio</label>
              <select id="id_servicio_destino" name="id_servicio_destino" class="form-control" style="width: 100%">
                <option value="0">--Seleccionar--</option>
                <?php
                foreach ($upss as $row):
                ?>
                  <option value="<?php echo $row->id_upss ?>">
                    <?php echo $row->nombre ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label class="form-control-sm">N° Documento</label>
              <input class="form-control input-sm" type="text" name="ndocumento" id="ndocumento" value="" style="width: 95%;">
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
              <label class="form-control-sm">Apellidos y Nombres</label>
              <input class="form-control input-sm" id="busqueda_datos" name="busqueda_datos">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
              <label>Estado</label>
              <select id="estado" name="estado" class="form-control input-sm" style="width: 100%">
                <option value="3">Atendido</option>
                <option value="5">Alta</option>
                <option value="6">Todos</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
              <label for="">&nbsp;</label>
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>
            
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
              <label for="">&nbsp;</label>
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencionDetalladoConsejeriaEmergencia('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
              </a>
            </div>

            {{-- <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
              <label for="">&nbsp;</label>
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                 onclick="exportarDetallado('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
              </a>
            </div> --}}
          </div>
        </form>
        <br>
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="tblPacientesEmergencias" class="table table-condensed table-bordered table-hover" style="width:100%">
              <thead>
                <tr>
                  <th>N</th>
                  <th id="columna_1">Datos de Atención</th>
                  <th id="columna_2">Datos del Profesional que Atiende</th>
                  <th id="columna_3">Datos del Paciente</th>
                  {{-- <th>Sexo</th>
                  <th>Edad</th> --}}
                  <th>Atención</th>
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


<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection