<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
if (Auth::user()->id_farmacia == 19 ):
  $day = '-2d';
else:
  if (Auth::user()->id_farmacia == 128 ):
    $day = '-5d';
  else:
    $day = '-30d';
  endif;
endif;

?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
  .table-sortable tbody tr {
    cursor: move;
  }
  

  /*
#global {        
    width: 95%;        
    margin: 15px 15px 15px 15px;     
    height: 380px !important;        
    border: 1px solid #ddd;
    overflow-y: scroll !important;
}
    */
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

<script>
  //--------------------------------------------------------------------------
$(document).ready(function() {

  $consul = $("#frmRequerimiento").find("#id_requerimiento");
  $consul.select2();
  //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
  //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

  $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
  });
  $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
    //"dateFormat": "YYYY-MM-DD hh:mm",
  });
  $('#fechaDesde').click(function() {
    var fechaDesdeIni = $('#fechaDesde').val();
    $('#fechaDesde').change(function() {
      var fechaDesde = $('#fechaDesde').val();
      var fechaHasta = $('#fechaHasta').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f1 > f2) {
        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
        $('#fechaDesde').val(fechaDesdeIni);
        return false;
      }
    });
  });
  $('#fechaHasta').click(function() {
    var fechaHastaIni = $('#fechaHasta').val();
    $('#fechaHasta').change(function() {
      var fechaDesde = $('#fechaDesde').val();
      var fechaHasta = $('#fechaHasta').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f2 < f1) {
        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
        $('#fechaHasta').val(fechaHastaIni);
        return false;
      }
      /*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
	*/
    });
  });
  $('#fechaHasta').trigger('change');
  $('#fechaDesde').trigger('change');
  var activeSystemClass = $('.list-group-item.active');
  /*
   //something is entered in search form
   $('#system-search').keyup(function () {
   var that = this;
   // affect all table rows on in systems table
   var tableBody = $('.table-list-search tbody');
   var tableRowsClass = $('.table-list-search tbody tr');
   $('.search-sf').remove();
   tableRowsClass.each(function (i, val) {
   
   //Lower text for case insensitive
   var rowText = $(val).text().toLowerCase();
   var inputText = $(that).val().toLowerCase();
   if (inputText != '')
   {
   $('.search-query-sf').remove();
   
   } else
   {
   $('.search-query-sf').remove();
   }
   
   if (rowText.indexOf(inputText) == -1)
   {
   //hide rows
   tableRowsClass.eq(i).hide();
   
   } else
   {
   $('.search-sf').remove();
   tableRowsClass.eq(i).show();
   }
   });
   //all tr elements are hidden
   if (tableRowsClass.children(':visible').length == 0)
   {
   tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No existe la receta que esta buscando.</td></tr>');
   }
   });
   
   */
});
$("#id_consultorio").select2({
    width: 'resolve' // need to override the changed default
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

// PROGRAMACIÓN PROCEDIMIENTO RECETAS
$(document).on('click', '.btnVerAtencionPPReceta', function() {
  $('#modalVerAtencionPPBody').html('<div class="text-center" style="padding:40px"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
  $('#modalVerAtencionPP').modal('show');
  $.ajax({
    url: '/modal_ver_atencion_procprogramado/' + $(this).data('id-solicitud') + '/' + $(this).data('id-procedimiento'),
    type: 'GET',
    success: function(result) {
      $('#modalVerAtencionPPBody').html(result);
    },
    error: function() {
      $('#modalVerAtencionPPBody').html('<div class="text-danger text-center" style="padding:40px">No se pudo cargar la atención.</div>');
    }
  });
});
$(document).on('hidden.bs.modal', '#modalVerAtencionPP', function() {
  $('#modalVerAtencionPPBody').empty();
});
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Buscar Recetas Prescritas - {{$farmacia->nombre}}</h3>
    </div>
    <div class="panel-body">
      <div class="col-md-12">
        <form class="form-horizontal" method="post" action="{{ route('listar_recetas_prescrita')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
            <label class="col-md-2 control-label" for="textinput">Nro. Documento</label>
            <div class="col-md-2">
              <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
            </div>
            <label class="col-md-1 control-label" for="textinput">Nro. Receta</label>
            <div class="col-md-2">
              <input id="nro_receta" name="nro_receta" type="text" placeholder="" class="form-control input-sm"
              value="<?php echo $nro_receta ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
            </div>
            <label class="col-md-2 control-label">Servicio</label>
            <div class="col-md-3">
                @if($id_farmacia == 19)
                <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" tabindex="16" style="width: 100%">
                    <option value="">Todos</option>
                    <?php
                    foreach ($consultorio as $c) {
                        echo '<option value="' . $c->id . '"';
                        if (isset($id_consultorio)) {
                            if ($id_consultorio == $c->id)
                                echo " selected";
                        }
                        echo '>' . $c->codigo.' - '. $c->descripcion. '</option>';
                    }
                    ?>
                </select>
                @else
                <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" tabindex="16" style="width: 100%">
                    <option value="">Todos</option>
                    <?php
                    foreach ($consultorio as $c) {
                        echo '<option value="' . $c->id . '"';
                        if (isset($id_consultorio)) {
                            if ($id_consultorio == $c->id)
                                echo " selected";
                        }
                        echo '>' . $c->nombre. '</option>';
                    }
                    ?>
                </select>
                @endif
            </div>
          </div>
          <div style="clear:both;margin-bottom:10px"></div>
          <div class="row">
            
            <!--label class="col-md-2 control-label">Fecha Desde</label>
            <div class="col-md-3">
              <input required = "" class="form-control datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde"
                value="<?php echo $fechaDesde ?>" maxlength="10">
              <p class="help-block">Fecha Dispensaci&oacute;n</p>
              <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
              <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
            </div>
            <label class="col-md-2 control-label">Fecha Hasta</label>
            <div class="col-md-3">
              <input  required = "" class="form-control datepicker" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta"
                value="<?php echo $fechaHasta ?>" maxlength="10">
              <p class="help-block">Fecha Dispensaci&oacute;n</p>
            </div-->
            
            <label class="col-md-2 control-label">Fecha Desde</label>
            <div class="col-md-3">
              <input required = "" 
                        data-date-start-date="<?php echo $day ?>" data-date-end-date="0d" 
                      
                      class="form-control" data-provide="datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde"
                value="<?php echo $fechaDesde ?>" maxlength="10">
              <p class="help-block">Fecha Dispensaci&oacute;n</p>
              <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
              <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
            </div>
            <label class="col-md-2 control-label">Fecha Hasta</label>
            <div class="col-md-3">
              <input  required = ""  
                        data-date-start-date="<?php echo $day ?>" data-date-end-date="0d"  
                      
                      class="form-control" data-provide="datepicker" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta"
                value="<?php echo $fechaHasta ?>" maxlength="10">
              <p class="help-block">Fecha Dispensaci&oacute;n</p>
            </div>
            
            <div class="col-md-2">
              <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-12" style="margin-top: 10px">

      </div>
      <div class="col-md-12">
        <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar receta">
      </div>

      @if($variable==1)
        <div class="col-md-12" id="global">
          <div class="table-responsive">
            <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;">
              <thead>
                <tr>
                  <th>N</th>
                  <?php if ($farmacia->id == 21) : ?>
                  <th>TIPO USO </th>
                  <?php endif; ?>
                  <th>Nro. Receta</th>
                  <?php if ($farmacia->id == 22 || $farmacia->id == 14) : ?>
                  <th>RECETA</th>
                  <?php endif; ?>
                  <?php if ($farmacia->id == 128) : ?>
                  <th>Estado</th>
                  <?php endif; ?>
                  <th>Especialidad</th>
                  <th>Fecha<br />Firma</th>
                  <th>Fecha<br />Registro</th>
                  <th>Fecha<br />Expedición</th>
                  <th>Tipo Doc.</th>
                  <th>Nro. Doc.</th>
                  <th>Beneficiario</th>
                  <th>Tipo</th>
                  <th>Medico</th>
                  <th style="text-align:center">Pdf Atencion</th>
                  <th style="text-align:center">Pdf Receta</th>
                  <th>Dispensar</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        @else
      <div class="col-md-12" id="global">
        <!--<table  class="table table-list-search">-->
        <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;">
          <thead>
            <tr>
              <th>N</th>
              <?php if ($farmacia->id == 21) : ?>
              <th>TIPO USO </th>
              <?php endif; ?>
              <th>Farmacia a dispensar</th>
              <th>Nro. Receta</th>
              <?php if ($farmacia->id == 22 || $farmacia->id == 14) : ?>
              <th>RECETA</th>
              <?php endif; ?>
              <?php if ($farmacia->id == 128) : ?>
              <th>Estado</th>
              <?php endif; ?>
              <th>Especialidad</th>
              <th>Fecha<br />Firma</th>
              <th>Fecha<br />Dispensación</th>
              <th>Fecha<br />Expedición</th>
              <th>Tipo Doc.</th>
              <th>Nro. Doc.</th>
              <th>Beneficiario</th>
              <th>Tipo</th>
              <th>Medico</th>
              <th style="text-align:center">Pdf Atencion</th>
              <th style="text-align:center">Pdf Receta</th>
              <th>Dispensar</th>
            </tr>
          </thead>
          <tbody>
            <?php $n = 0; ?>
            @foreach($recetas as $r)
            <tr>
              <td><?php
                  $n++;
                  echo $n;
                  ?></td>
              <?php if ($farmacia->id == 21) : ?>
              <td>{{$r->tipo_via}}</td>
              <?php endif; ?>
              <td><?php if($r->id_farmacia!=Auth::user()->id_farmacia): ?>
                  <p style="color: red">{{$r->nombre}}</p>
                <?php else: ?>
                  <p>{{$r->nombre}}</p>
                <?php endif; ?>
                </td>

              <td><?php if($r->id_farmacia!=Auth::user()->id_farmacia): ?>
                  <p style="color: red">{{$r->nro_receta}}</p>
                <?php else: ?>
                  <p>{{$r->nro_receta}}</p>
                <?php endif; ?>
              </td>
              
              <?php if ($farmacia->id == 22 || $farmacia->id == 14) : ?>
              <td>{{$r->tipo_receta}}</td>
              <?php endif; ?>
              <?php if ($farmacia->id == 128) : ?>
              <td><?php 
                      switch($r->alta){
                        case 3: echo "Atención"; break;
                        case 4: echo "Evolución"; break;
                        case 6: echo "<p style='color: blue'><b>Alta médica</b></p>"; break;
                        case 7: echo "<p style='color: blue'><b>Alta Administrativa</b></p>"; break;
                      }
                  ?>
              </td>
              <?php endif; ?>
              <td><?php if($r->id_farmacia!=Auth::user()->id_farmacia): ?>
                  <p style="color: red">{{$r->consultorio}}</p>
                <?php else: ?>
                  <p>{{$r->consultorio}}</p>
                <?php endif; ?>
              </td>
              <td>
                <?php
                $fecha_firma = '';
                if($r->fecha_firma!=null){
                  $originalDate3 = $r->fecha_firma;
                  $fecha_firma = date("d-m-Y H:i:s", strtotime($originalDate3));
                  echo $fecha_firma;
                }
            	else
            	{
            	  echo '<p style="color: red">sin firmar</p>';
            	}
                
                ?>
              </td>
              <td>
                <?php
                $originalDate1 = $r->fecha_registro;
                $fechaE = date("d-m-Y", strtotime($originalDate1));
                echo $fechaE;
                ?>
              </td>
              <td>
                <?php
                $originalDate2 = $r->fecha_expedicion;
                $fecha = date("d-m-Y", strtotime($originalDate2));
                echo $fecha;
                ?>
              </td>
              <td>{{$r->tipodoc_beneficiario}}</td>
              <td>{{$r->dni_beneficiario}}</td>
              <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>
              <td>{{$r->tipo_beneficiario}}</td>
              <td>{{$r->paterno_usuario}} {{$r->materno_usuario}}, {{$r->nombre_usuario}}</td>
              <td class="text-center">
                {{-- -- SOLICITUD PROGRAMACIÓN PROCEDIMIENTO ÓRDENES --}}
                <?php $pp_det = isset($r->id_procp) && intval($r->id_procp) > 0 ? \DB::table('solicitud_procedimientos_detalle')->where('id', intval($r->id_procp))->first() : null; ?>
                @if($pp_det)
                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención del Procedimiento Programado</b>">
                  <button type="button" class="btn btn-primary btn-xs btnVerAtencionPPReceta" data-id-solicitud="{{ $pp_det->id_solicitud }}" data-id-procedimiento="{{ $pp_det->id_procedimiento }}"><i class="fa fa-download"></i></button>
                </div>
                @else
                <?php
                if ($r->ruta_prestacion == "") {
                ?>
                <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                <?php
                } else {
                    if($r->id <=4327923){
                      if($r->flag ==1){
                ?>
                      <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                        <a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/{{$r->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i
                            class="fa fa-download"></i></a>
                      </div>
                <?php   
                      }
                      else
                      {
                ?>
                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                        <a href="/{{$r->flag}}/{{$r->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i
                        class="fa fa-download"></i></a>
                      </div>      
                <?php }
                    }
                    else
                    {
                ?>
                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                      <a href="/{{$r->flag}}/{{$r->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i
                      class="fa fa-download"></i></a>
                    </div>
                <?php
                    }
                ?>
                <?php
                  }
                ?>
                @endif
              </td>
              <td class="text-center">
                <?php
                  if ($r->ruta_receta == "") {
                ?>
                    <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                <?php
                  } 
                  else 
                  {
                    if($r->id <=4327923)  //http://pdfsigesapol.dirsapolpnp.gob.pe:8084/
                    {
                      if($r->flag ==1){
                ?>
                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                    <a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/{{$r->ruta_receta}}" target="_blank" class="btn btn-primary btn-xs"><i
                        class="fa fa-download"></i></a> 
                  </div>
                <?php
                      }
                      else
                      {
                ?>
                      <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                        <a href="http://pdfsigesapol.dirsapolpnp.gob.pe:8084/{{$r->flag}}/{{$r->ruta_receta}}" target="_blank" class="btn btn-primary btn-xs"><i
                            class="fa fa-download"></i></a> 
                      </div>
                <?php  
                      }
                    }
                    else
                    {
                ?>
                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                    <a href="/{{$r->flag}}/{{$r->ruta_receta}}" target="_blank" class="btn btn-primary btn-xs"><i
                        class="fa fa-download"></i></a>
                  </div>
                <?php
                    }
                  }
                ?>
              </td>
              
              <td class="text-center">
                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender receta</b>">
                  <?php if ($farmacia->id == 128) : ?>
                  <!--<button type="button" class="btn btn-success btn-xs" onclick="ver_det_receta('{{ Auth::user()->id }}','{{$r->nro_receta}}','{{$r->id}}')"><i class="fa fa-search"></i></button>-->
                  <?php 
                      $originalDate3 = $r->fecha_expedicion;
                      if($r->alta>5):
                       $fecha = date("Y-m-d", strtotime($originalDate3));
                       $fecha2 = strtotime("$fecha + 7 day");
                      else:
                       $fecha = date("Y-m-d", strtotime($originalDate3));
                       $fecha2 = strtotime("$fecha + 2 day");
                      endif;
                      
                      $fechaHoy = date("Y-m-d"); 
                      $fechaHoy2 = strtotime("$fechaHoy ");
                      
                      $diasDiferencia = $fechaHoy2 - $fecha2;
                      
                      if($diasDiferencia <= 0){
                  ?>

                  <a href="/formulario_receta_dispension/{{$r->id}}" class="btn <?php if($r->id_farmacia!=Auth::user()->id_farmacia): echo 'btn-danger'; else: echo 'btn-success'; endif; ?> btn-xs"><i
                      class="fa fa-edit"></i></a>
                  <?php
                    }
                    else
                    {
                  ?>
                  <a href="#" disabled class="btn <?php if($r->id_farmacia!=Auth::user()->id_farmacia): echo 'btn-danger'; else: echo 'btn-success'; endif; ?> btn-xs"><i
                      class="fa fa-edit"></i></a>
                  <?php    
                  }
                ?>
              <?php else: ?>
                <a href="/formulario_receta_dispension/{{$r->id}}" class="btn <?php if($r->id_farmacia!=Auth::user()->id_farmacia): echo 'btn-danger'; else: echo 'btn-success'; endif; ?> btn-xs"><i
                      class="fa fa-edit"></i></a>
              <?php endif; ?>
              </div>
              </td>


            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
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

{{-- -- SOLICITUD PROGRAMACIÓN PROCEDIMIENTO ÓRDENES --}}
<div class="modal fade" id="modalVerAtencionPP" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 1200px; max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">VER ATENCIÓN DEL PROCEDIMIENTO PROGRAMADO</h5>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div id="modalVerAtencionPPBody"></div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
