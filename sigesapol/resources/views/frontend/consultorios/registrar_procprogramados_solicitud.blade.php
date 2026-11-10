@extends('frontend.layouts.master')
@section('renderjs')
<script>
  $(document).ready(function() {

    $("#pp_id_establecimiento").select2({ width: "100%" });
    $("#requiere_cita").select2({ width: "100%", minimumResultsForSearch: Infinity });
    $("#pp_edit_requiere_cita").select2({ width: "100%", minimumResultsForSearch: Infinity, dropdownParent: $("#modalEditarPP") });

    $("#pp_id_establecimiento").on("change", function() {
      var id = $(this).val();
      location.href = location.pathname + (id ? "?id_establecimiento=" + id : "");
    });

    $("#pp_buscar_procedimiento").select2({
      width: "100%",
      placeholder: "Buscar por código o descripción",
      allowClear: true,
      minimumInputLength: 0,
      language: {
        noResults: function() { return "No se encontraron procedimientos"; },
        searching: function() { return "Buscando..."; }
      },
      ajax: {
        url: "/pp_buscar_procedimiento_convenio",
        dataType: "json",
        delay: 400,
        data: function(params) {
          return { keyword: params.term || "", id_establecimiento: $("#pp_id_establecimiento").val() };
        },
        processResults: function(data) {
          return {
            results: $.map(data, function(item) {
              return { id: item.id, text: item.label, codigo: item.codigo };
            })
          };
        }
      }
    });

    $("#pp_buscar_procedimiento").on("change", function() {
      $("#pp_id_procedimiento").val($(this).val() || "");
      var seleccionado = $(this).select2("data")[0];
      $("#pp_prefijo_codigo").text(seleccionado && seleccionado.codigo ? seleccionado.codigo + " -" : "Código");
    });

    $("#btnReg").on("click", function() {
      var mensaje = "";
      if ($("#pp_id_procedimiento").val() == "") {
        mensaje += "Falta seleccionar el Procedimiento.<br>";
      }
      if (String($("#orden").val()) == "") {
        mensaje += "Falta ingresar el N° de Orden.";
      }
      if (mensaje != "") {
        bootbox.alert(mensaje);
        return false;
      }
      $("#btnReg").prop("disabled", true);
      $.ajax({
        url: "/pp_agregar_procedimiento_servicio",
        type: "POST",
        data: {
          _token: $("#pp_token").val(),
          id_servicio: $("#id_servicio").val(),
          id_establecimiento: $("#pp_id_establecimiento").val(),
          id_procedimiento: $("#pp_id_procedimiento").val(),
          descripcion_corta: $("#descripcion_corta").val(),
          orden: $("#orden").val(),
          requiere_cita: $("#requiere_cita").val(),
          requiere_informe: $("#requiere_informe").is(":checked") ? 1 : 0
        },
        success: function(res) {
          if (res.success) {
            location.reload();
          } else {
            $("#btnReg").prop("disabled", false);
            bootbox.alert(res.message);
          }
        },
        error: function() {
          $("#btnReg").prop("disabled", false);
          bootbox.alert("No se pudo agregar el procedimiento.");
        }
      });
    });
  });

  function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
      return true;
    return /\d/.test(String.fromCharCode(keynum));
  }

  $(document).on("click", ".btnEditarPP", function() {
    var $btn = $(this);
    $("#pp_edit_id").val($btn.attr("data-id"));
    $("#pp_edit_procedimiento").text($btn.attr("data-procedimiento"));
    $("#pp_edit_prefijo_codigo").text($btn.attr("data-codigo") + " -");
    $("#pp_edit_descripcion_corta").val($btn.attr("data-descripcion-corta"));
    $("#pp_edit_orden").val($btn.attr("data-orden"));
    $("#pp_edit_requiere_cita").val($btn.attr("data-requiere-cita")).trigger("change");
    $("#modalEditarPP").modal("show");
  });

  $(document).on("keydown", "#pp_edit_descripcion_corta", function(e) {
    if (e.which == 13) {
      e.preventDefault();
    }
  });

  $(document).on("click", "#btnGuardarPP", function() {
    var mensaje = "";
    if ($.trim($("#pp_edit_orden").val()) == "") {
      mensaje += "Falta ingresar el N° de Orden.";
    }
    if (mensaje != "") {
      bootbox.alert(mensaje);
      return;
    }
    $("#btnGuardarPP").prop("disabled", true);
    $.ajax({
      url: "/pp_editar_procedimiento_servicio",
      type: "POST",
      data: {
        _token: $("#pp_token").val(),
        id: $("#pp_edit_id").val(),
        descripcion_corta: $("#pp_edit_descripcion_corta").val(),
        orden: $("#pp_edit_orden").val(),
        requiere_cita: $("#pp_edit_requiere_cita").val()
      },
      success: function(res) {
        if (res.success) {
          location.reload();
        } else {
          $("#btnGuardarPP").prop("disabled", false);
          bootbox.alert(res.message);
        }
      },
      error: function() {
        $("#btnGuardarPP").prop("disabled", false);
        bootbox.alert("No se pudo actualizar el procedimiento.");
      }
    });
  });

  $(document).on("change", ".chkRequiereInforme", function() {
    var $chk = $(this);
    var marcado = $chk.is(":checked");
    $.ajax({
      url: "/pp_requiere_informe_procedimiento_servicio",
      type: "POST",
      data: { _token: $("#pp_token").val(), id: $chk.data("id"), requiere_informe: marcado ? 1 : 0 },
      success: function(res) {
        if (!res.success) {
          $chk.prop("checked", !marcado);
          bootbox.alert(res.message);
        }
      },
      error: function() {
        $chk.prop("checked", !marcado);
        bootbox.alert("No se pudo actualizar si necesita informe.");
      }
    });
  });

  function desactivar(id, estado) {
    var msg = estado == 1 ? "¿Se desactivará el Procedimiento, desea continuar?" : "¿Se activará el Procedimiento, desea continuar?";
    bootbox.confirm(msg, function(result) {
      if (result == true) {
        $.ajax({
          url: "/pp_estado_procedimiento_servicio",
          type: "POST",
          data: { _token: $("#pp_token").val(), id: id },
          success: function(res) {
            if (res.success) {
              location.reload();
            } else {
              bootbox.alert(res.message);
            }
          }
        });
      }
    });
  }
</script>
<style>
  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }

  /* Misma altura que los form-control de la fila */
  #pp_buscar_procedimiento + .select2-container .select2-selection--single,
  #requiere_cita + .select2-container .select2-selection--single,
  #pp_edit_requiere_cita + .select2-container .select2-selection--single {
    height: 34px;
  }

  #pp_buscar_procedimiento + .select2-container .select2-selection__rendered,
  #requiere_cita + .select2-container .select2-selection__rendered,
  #pp_edit_requiere_cita + .select2-container .select2-selection__rendered {
    line-height: 32px;
  }

  #pp_buscar_procedimiento + .select2-container .select2-selection__arrow,
  #requiere_cita + .select2-container .select2-selection__arrow,
  #pp_edit_requiere_cita + .select2-container .select2-selection__arrow {
    height: 32px;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Proc. Programados - {{$consultorio->nombre}}</h3>
          <div class="pull-right">
            <a class="btn btn-default btn-xs" href="/all_servicios">
              <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
            </a>
          </div>
        </div>
        <div class="panel-body">
          <input type="hidden" id="pp_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_servicio" value="{{$id_servicio}}">
          <input type="hidden" id="pp_id_procedimiento" value="">

          <div class="col-md-12" style="margin-top:15px">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">IPRESS</h3>
              </div>
              <div class="panel-body">
                <label for="pp_id_establecimiento">IPRESS</label>
                <select id="pp_id_establecimiento" class="form-control" style="width: 100%;">
                  <option value="">-Seleccionar-</option>
                  @foreach($establecimientos as $f)
                  <option value="{{$f->id}}" {{ intval($f->id) === $id_establecimiento ? 'selected' : '' }}>{{$f->codigo}} - {{$f->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          @if($id_establecimiento > 0)
          <div class="col-md-12" style="margin-top:15px">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Agregar Procedimiento (Nivel {{$nivel}})</h3>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-7">
                    <label for="pp_buscar_procedimiento">Procedimiento <span style="font-weight: bold; color: red;">(*)</span></label>
                    <select id="pp_buscar_procedimiento" class="form-control" style="width: 100%;">
                      <option value=""></option>
                    </select>
                  </div>
                  <div class="col-md-5">
                    <label for="descripcion_corta">Descripción corta</label>
                    <div class="input-group">
                      <span class="input-group-addon" id="pp_prefijo_codigo">Código</span>
                      <input id="descripcion_corta" type="text" class="form-control" maxlength="100">
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:10px">
                  <div class="col-md-2">
                    <label for="orden">Orden <span style="font-weight: bold; color: red;">(*)</span></label>
                    <input name="orden" id="orden" type="text" class="form-control" style="text-align:right" maxlength="4" onkeypress="return justNumbers(event);">
                  </div>
                  <div class="col-md-5">
                    <label for="requiere_cita">Cita</label>
                    <select id="requiere_cita" class="form-control">
                      @foreach($tipos_cita as $valor => $texto)
                      <option value="{{$valor}}">{{$texto}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-2 text-center">
                    <label for="requiere_informe">Informe</label>
                    <div><input type="checkbox" id="requiere_informe" value="1" style="transform: scale(1.4); margin-top:10px" title="Necesita subir informe"></div>
                  </div>
                  <div class="col-md-3">
                    <label>&nbsp;</label>
                    <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="button">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12" style="margin-top:15px">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Procedimientos que puede solicitar el Servicio en la IPRESS</h3>
              </div>
              <div class="panel-body">
                <table class="table" style="font-size: 12px">
                  <thead>
                    <tr>
                      <th width="5%">N</th>
                      <th width="7%">Código</th>
                      <th>Procedimiento</th>
                      <th width="25%">Descripción corta</th>
                      <th width="6%" class="text-right">Orden</th>
                      <th width="15%">Cita</th>
                      <th width="7%" class="text-center" title="Necesita subir informe">Informe</th>
                      <th width="7%" class="text-center">Estado</th>
                      <th width="7%" class="text-center">Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n = 0; ?>
                    @forelse($procedimientos_servicio as $e)
                    <?php $n++; ?>
                    <tr>
                      <td>{{$n}}</td>
                      <td>{{$e->codigo}}</td>
                      <td>{{$e->descripcion}}</td>
                      <td style="text-align:justify">{{ $e->descripcion_corta != '' ? $e->codigo . ' - ' . $e->descripcion_corta : '' }}</td>
                      <td class="text-right">{{$e->orden}}</td>
                      <td>{{$tipos_cita[intval($e->requiere_cita)]}}</td>
                      <td class="text-center">
                        <input type="checkbox" class="chkRequiereInforme" data-id="{{$e->id}}" value="1" style="transform: scale(1.4)" title="Necesita subir informe" {{ intval($e->requiere_informe) === 1 ? 'checked' : '' }}>
                      </td>
                      <td class="text-center">
                        <?php if ($e->id_estado_reg == 1) { ?>
                          <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>, <?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-up"></i></a>
                        <?php } else { ?>
                          <a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>, <?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-down"></i></a>
                        <?php } ?>
                      </td>
                      <td class="text-center">
                        <a class="btn btn-warning btnEditarPP" href="javascript:void(0)" title="Editar" data-id="{{$e->id}}" data-codigo="{{$e->codigo}}" data-procedimiento="{{$e->codigo}} - {{$e->descripcion}}" data-descripcion-corta="{{$e->descripcion_corta}}" data-orden="{{$e->orden}}" data-requiere-cita="{{intval($e->requiere_cita)}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="9" class="text-center">El servicio no tiene procedimientos configurados</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="modal fade" id="modalEditarPP" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Editar Procedimiento</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="pp_edit_id" value="">
                  <p id="pp_edit_procedimiento" style="font-weight:bold"></p>
                  <div class="form-group">
                    <label for="pp_edit_descripcion_corta">Descripción corta</label>
                    <div class="input-group">
                      <span class="input-group-addon" id="pp_edit_prefijo_codigo"></span>
                      <textarea id="pp_edit_descripcion_corta" class="form-control" rows="2" maxlength="100" style="resize:none"></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label for="pp_edit_orden">Orden <span style="font-weight: bold; color: red;">(*)</span></label>
                      <input id="pp_edit_orden" type="text" class="form-control" style="text-align:right" maxlength="4" onkeypress="return justNumbers(event);">
                    </div>
                    <div class="col-md-9">
                      <label for="pp_edit_requiere_cita">Cita</label>
                      <select id="pp_edit_requiere_cita" class="form-control">
                        @foreach($tipos_cita as $valor => $texto)
                        <option value="{{$valor}}">{{$texto}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                  <button type="button" id="btnGuardarPP" class="btn btn-success">Guardar</button>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
