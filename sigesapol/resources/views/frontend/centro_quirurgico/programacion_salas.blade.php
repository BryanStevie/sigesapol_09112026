@extends('frontend.layouts.master')

@section('rendercss')

<style>
  .filterable {
    margin-top: 0px;
  }

  .filterable .panel-heading .pull-right {
    margin-top: -20px;
  }

  .filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
  }

  .filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
  }

  #global {
    height: 480px !important;
    width: auto;
    border: 1px solid #ddd;
    overflow-y: scroll !important;
  }
</style>

@endsection

@section('renderjs')

<script src="{{asset('dateTimePicker/bootstrap-datetimepicker.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/dateTimePicker/bootstrap-datetimepicker.css')}}">

<script>
  $(document).ready(function() {
    $('#id_tipo_atencion').val("<?php echo $tipo_atenciones[3]['id'];?>");
    @if($flagservicios == 1)
    $('#id_consultorio').select2();
    @endif
    //$('#id_medico').select2();

    $('#fecha_atencion_busqueda').datepicker({
      //viewMode: 'years',
      //startView: 1,
      //minView: 2,
      format: 'mm/yyyy',
      viewMode: 'years',
      minViewMode: "months",
      language: "es",
      autoclose: true,
      weekStart: 0,
      todayBtn: "linked"
    });
    //alert("<?php //echo $mes."/".$ano
              ?>");
    $('#fecha_atencion_busqueda').val("<?php echo $mes . "/" . $ano ?>");
    $('#fecha_atencion_busqueda').datepicker('update');

    $('.filterable .btn-filter').click(function() {
      var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
      if ($filters.prop('disabled') == true) {
        $filters.prop('disabled', false);
        $filters.first().focus();
      } else {
        $filters.val('').prop('disabled', true);
        $tbody.find('.no-result').remove();
        $tbody.find('tr').show();
      }
    });

    $('.filterable .filters input').keyup(function(e) {
      /* Ignore tab key */
      var code = e.keyCode || e.which;
      if (code == '9')
        return;
      /* Useful DOM data and selectors */
      var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');
      /* Dirtiest filter function ever ;) */
      var $filteredRows = $rows.filter(function() {
        var value = $(this).find('td').eq(column).text().toLowerCase();
        return value.indexOf(inputContent) === -1;
      });
      /* Clean previous no-result if exist */
      $table.find('tbody .no-result').remove();
      /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
      $rows.show();
      $filteredRows.hide();
      /* Prepend no-result row if all rows are filtered */
      if ($filteredRows.length === $rows.length) {
        $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
      }
    });

  });

  function getSalasProgramacion() {
    var id_establecimiento = $('#id_establecimiento').val();
    var id_sala = $('#id_sala').val();
    let id_consultorio = $('#id_consultorio_quirurgico').val();
    var fecha_atencion = $('#fecha_atencion_busqueda').val().split("/");
    var mes = fecha_atencion[0];
    var ano = fecha_atencion[1];


    $('.table tbody').html("");
    $('#M').prop("checked", false);
    $('#T').prop("checked", false);
    $('#N').prop("checked", false);
    $('#MD').prop("checked", false);
    $('#marcar').hide();
    $.ajax({
      url: '/all_programacion_detalle/' + id_establecimiento + '/' + id_sala + '/' + mes + '/' + ano + '/' + id_consultorio,
      success: function(result) {
        $('#dataHorario').html(result);
      }
    });

  }

  function getPermisoByUsuario() {

    var id_usuario = $('#id_usuario').val();

    $('.table tbody').html("");

    $.ajax({
      url: '/obtener_permiso_por_idusuario/' + id_usuario,
      success: function(result) {
        var newFila = "";
        $(result).each(function(ii, oo) {
          newFila += "<tr>";
          var parentid = oo.parentid;

          if (parentid == 0) {
            newFila += "<td style='background:#D9EDF7;color:#31708F'><div class='col-lg-3'><b>" + oo.nombremenu + "</b><input type='hidden' name='idmenu[]' value='" + oo.idmenu + "'></div>";
            var chk = oo.lectura == '1' ? 'checked' : '';
            newFila += '<td width="10%" style="background:#D9EDF7" align="center"><input type="checkbox" id="' + oo.idmenu + '_lectura" name="lectura[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionGrupo(' + oo.idmenu + ',this,"lectura")></td>';
            var chk = oo.crear == '1' ? 'checked' : '';
            newFila += '<td width="10%" style="background:#D9EDF7" align="center"><input type="checkbox" id="' + oo.idmenu + '_crear" name="crear[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionGrupo(' + oo.idmenu + ',this,"crear")></td>';
            var chk = oo.modificar == '1' ? 'checked' : '';
            newFila += '<td width="10%" style="background:#D9EDF7" align="center"><input type="checkbox" id="' + oo.idmenu + '_modificar" name="modificar[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionGrupo(' + oo.idmenu + ',this,"modificar")></td>';
            var chk = oo.eliminar == '1' ? 'checked' : '';
            newFila += '<td width="10%" style="background:#D9EDF7" align="center"><input type="checkbox" id="' + oo.idmenu + '_eliminar" name="eliminar[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionGrupo(' + oo.idmenu + ',this,"eliminar")></td>';


          } else {
            newFila += "<td width='60%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + oo.nombremenu + "<input type='hidden' name='idmenu[]' value='" + oo.idmenu + "'></td>";
          }

          if (parentid > 0) {
            var chk = oo.lectura == '1' ? 'checked' : '';
            newFila += '<td width="10%" align="center"><input type="checkbox" parent=' + oo.parentid + ' idmenu=' + oo.idmenu + ' class="' + oo.parentid + '_lectura" id="' + oo.idmenu + '_lectura"  name="lectura[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionPadre("' + oo.parentid + '_lectura",this,' + oo.parentid + ',' + oo.idmenu + ')></td>';
            var chk = oo.crear == '1' ? 'checked' : '';
            newFila += '<td width="10%" align="center"><input type="checkbox" parent=' + oo.parentid + ' idmenu=' + oo.idmenu + ' class="' + oo.parentid + '_crear" id="' + oo.idmenu + '_crear" name="crear[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionPadre("' + oo.parentid + '_crear",this,' + oo.parentid + ',' + oo.idmenu + ')></td>';
            var chk = oo.modificar == '1' ? 'checked' : '';
            newFila += '<td width="10%" align="center"><input type="checkbox" parent=' + oo.parentid + ' idmenu=' + oo.idmenu + ' class="' + oo.parentid + '_modificar" id="' + oo.idmenu + '_modificar" name="modificar[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionPadre("' + oo.parentid + '_modificar",this,' + oo.parentid + ',' + oo.idmenu + ')></td>';
            var chk = oo.eliminar == '1' ? 'checked' : '';
            newFila += '<td width="10%" align="center"><input type="checkbox" parent=' + oo.parentid + ' idmenu=' + oo.idmenu + ' class="' + oo.parentid + '_eliminar" id="' + oo.idmenu + '_eliminar" name="eliminar[' + oo.idmenu + ']" ' + chk + ' value="1" onchange=seleccionPadre("' + oo.parentid + '_eliminar",this,' + oo.parentid + ',' + oo.idmenu + ')></td>';
          } else {
            newFila += '<td colspan=3></td>';
          }
          newFila += "</tr>";
        });
        $('.table tbody').html(newFila);

      }
    });

  }

  function seleccionGrupo(idmenu, obj, tipo) {
    if ($(obj).is(':checked')) {
      $('.' + idmenu + '_' + tipo).prop("checked", true);
      $('.' + idmenu + '_lectura').prop("checked", true);
      $('#' + idmenu + '_lectura').prop("checked", true);
    } else {
      $('.' + idmenu + '_' + tipo).prop("checked", false);
    }
  }

  function seleccionPadre(clsparentid, obj, parentid, idmenu) {

    var m = 0;
    var c = 0;
    $('input[idmenu=' + idmenu + ']').each(function(data) {
      if ($(this).attr('class') == parentid + '_crear' || $(this).attr('class') == parentid + '_modificar' || $(this).attr('class') == parentid + '_eliminar') {
        if ($(this).is(':checked')) {
          m++;
        }
      }

      if (clsparentid != parentid + '_lectura') {
        if (m > 0) $('#' + idmenu + '_lectura').prop("checked", true);
        else $('#' + idmenu + '_lectura').prop("checked", false);
      }
    })

    $('input[parent=' + parentid + ']').each(function(data) {
      if ($(this).is(':checked')) {
        c++;
      }
    })

    if (c > 0) $('#' + parentid + '_lectura').prop("checked", true);
    else $('#' + parentid + '_lectura').prop("checked", false);

    /**************************/
    var i = 0;
    $('.' + clsparentid).each(function(data) {
      if ($(this).is(':checked')) {
        i++;
      }
    })

    if (i > 0) $('#' + clsparentid).prop("checked", true);
    else $('#' + clsparentid).prop("checked", false);

  }


  function seleccionPadre_(parentid, obj) {

    var i = 0;
    $('.' + parentid).each(function(data) {
      if ($(this).is(':checked')) {
        i++;
      }
    })


    var m = 0;
    var idparent = 0;
    var idmenu = 0;
    if ($(obj).is(':checked')) {
      var clase = ($(obj).attr("class")).split("_");
      var menu = ($(obj).attr("id")).split("_");
      idparent = clase[0];
      opcion = clase[1];
      idmenu = menu[0];
      if (opcion == "crear" || opcion == "modificar" || opcion == "eliminar") {
        m = 0;
        $('input[parent=' + idparent + ']').each(function(data) {
          if ($(this).is(':checked')) {
            m++;
          }
        })

      }
    } else {
      var menu = ($(obj).attr("id")).split("_");
      idmenu = menu[0];
    }


    if (m > 0) $('#' + idmenu + '_lectura').prop("checked", true);

    if (i > 0) $('#' + parentid).prop("checked", true);
    else $('#' + parentid).prop("checked", false);

  }

  function limpieza() {
    $('.table tbody').html("");
  }

  function seleccionGr(obj, tipo) {
    if ($(obj).is(':checked')) {
  
      $('.' + tipo).each(function() {
        if ($(this).is(':enabled')) $(this).prop("checked", true);
      });
    } else {
      $('.' + tipo).prop("checked", false);
    }

  }

  function validateForm() {
    var msg = "";
    var sw = true;
  
    var id_consultorio = $('#id_consultorio').val();
    var id_sala = $('#id_sala').val();
    var cont = $('#tblProgramacion tbody').html();
    var id_consultorio_quirurgico = $('#id_consultorio_quirurgico').val();
  
    if(id_consultorio_quirurgico==0){
      msg += "Seleccione Servicio quirurgico.<br/>";
      sw = false
    }

    if (id_sala == "0") {
      msg += "Seleccione una sala.<br/>";
      sw = false
    }

    if (cont == "") {
      msg += "Realice la búsqueda de Programación de días.<br/>";
      sw = false
    }

    if (sw == false) {
      bootbox.alert(msg);
      return sw;
    } else {
      submitFrm();
    }
    return false;
  }

  function submitFrm() {
    bootbox.confirm({
      message: "Se registrarán o actualizarán los registros Seleccionados, ¿Está seguro de continuar?",
      buttons: {
        confirm: {
          label: 'Si',
          className: 'btn-success'
        },
        cancel: {
          label: 'No',
          className: 'btn-danger'
        }
      },
      callback: function(result) {
        if (result == true) {
          document.frm.submit();
        }
      }
    });
  }
</script>

@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Programaci&oacute;n de Especialidades Quir&uacute;rgicas</h3>
        </div>
        <div class="panel-body">
          <form class="app-form" name="frm" id="frm" role="form" method="post" action="{{ route('registrar_programacion_salas') }}" onsubmit="return validateForm()" autocomplete="off">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $id_establecimiento ?>" />
            <div class="row form-horizontal">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="id_tipo_atencion" class="col-sm-2 control-label">Tipo de Atenci&oacute;n</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="id_tipo_atencion" name="id_tipo_atencion" disabled>
                      <option value="">-Seleccionar-</option>
                      @foreach ($tipo_atenciones as $item)
                      <option value="{{ $item['id'] }}" <?php echo $item['id'] == 1 ? ' selected' : '' ?>>{{ $item['nombre'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @if ($flagservicios == 1)
                <div class="form-group">
                  <label class="col-sm-2 control-label">Servicio</label>
                  <div class="col-sm-10">
                      <select class="form-control" name="id_consultorio" id="id_consultorio_quirurgico" required="">
                        <option value="0">-Seleccionar-</option>
                        <?php foreach ($consultorio_quirurgico as $row) : ?>
                          <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                  </div>
                </div>
                @else
                  <?php
                  $servicio_id = '';
                  $servicio_nombre = '';
                  if (count($consultorio) > 0) {
                    $servicio_id = $consultorio[0]->id;
                    $servicio_nombre = $consultorio[0]->nombre;
                  }
                  ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Servicio</label>
                    <div class="col-sm-10">
                      <span class="form-control tag_disabled">{{ $servicio_nombre }}</span>
                    </div>
                  </div>
                  <input type="hidden" name="id_consultorio" id="id_consultorio" value="{{ $servicio_id }}">
                @endif
                <div class="form-group">
                  <div id="combo_consultorio">
                    <label class="col-sm-2 control-label">Salas</label>
                    <div class="col-sm-10">
                      <select class="form-control" name="id_sala" id="id_sala">
                        <option value="0">-Seleccionar-</option>
                        <?php foreach ($cq_salas as $row) : ?>
                          <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                        <?php endforeach; ?>
                      </select>
                      </select>
                    </div>
                  </div>

                </div>
               
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Fecha</label>
                  <div class="col-sm-3">
                    <div class='input-group date' id='datetimepicker10'>
                      <input type='text' class="form-control input-sm" id="fecha_atencion_busqueda" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-info btn-circle input-sm" id="btnBuscar" value="Buscar" type="button" onclick="getSalasProgramacion()">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="app-panel-title app-mt-4 app-mb-3"><strong>Programaci&oacute;n de Especialidades Quir&uacute;rgicas</strong></div>
              </div>
            </div>
            <div class="row app-mt-4">
              <div class="col-sm-12">
                <table class="app-table-h table table-bordered table-hover" style="margin-bottom: 0px;" id="tblProgramacion">
                  <thead>
                    <tr>
                      <th style="vertical-align: top;">Programación Dias</th>
                      <th class="text-center app-turno">Madrugada<br><input id="MD" type="checkbox" value="1" onchange="seleccionGr(this,'MD')"></th>
                      <th class="text-center app-turno">Mañana<br><input id="M" type="checkbox" value="1" onchange="seleccionGr(this,'M')"></th>
                      <th class="text-center app-turno">Tarde<br><input id="T" type="checkbox" value="1" onchange="seleccionGr(this,'T')"></th>
                      <th class="text-center app-turno">Noche<br><input id="N" type="checkbox" value="1" onchange="seleccionGr(this,'N')"></th>
                    </tr>
                  </thead>
                  <tbody id="dataHorario"></tbody>
                </table>
              </div>
             </div>
             <div class="row app-mt-4">
               <div class="col-sm-12">
               <?php if ($permiso->crear == 1 || $permiso->modificar == 1) : ?>
                  <input type="submit" value="Guardar" class="btn btn-success">
                <?php endif; ?>
                {{--<a href="/all_medicos_programacion" class="btn btn-danger">Cancelar</a> --}}
               </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')

@endsection