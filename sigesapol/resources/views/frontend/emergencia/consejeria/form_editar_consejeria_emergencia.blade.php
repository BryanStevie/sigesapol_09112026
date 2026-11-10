@extends('frontend.layouts.master')
@section('renderjs')
<script src='../funciones/function.js'></script> 
<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script>
</script>
<script src="{{asset('emergencia/consejeria/js/editarConsejeriaEmergencia.js')}}"></script>

<style>
    .form-control {
      height: 28px;
      text-align: left !important;
    }
    .ui-autocomplete-loading {
      background: white url('{{ asset('images/loader.gif') }}') no-repeat right center !important; 
    }
    label {
      font-size: 11px;
    }
  
    .row {
      gap: 10px;
    }
  
    .separate-addon {
      background-color: #ffffff !important;
      border: none !important;
    }
  
    .table-error {
      border: 1px solid #dc3545;
    }
  
    .label-help {
      color: #dc3545; 
      font-size: 11px; 
      font-weight: bold
    }
  
    .label-help-success {
      color: #28a745; 
      font-size: 11px; 
      font-weight: bold
    }
  
    .label-antecedentes { 
      color: #060606; 
      font-size: 12px;
      font-weight: bold
    }
  
    .antecedentes-content { 
      color: #060606; 
      font-size: 11px;
      font-weight: 100
    }
  
    .has-error {
      border: 1px solid #dc3545 !important;
    }
  
    .swal-wide{
      width:500px !important;
    }
  
    .swal2-icon.swal2-warning {
      font-size: 15px;
    }

    .texto-tamaño{
      font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 12px;
    }

    .size-titulo {
      font-size: 1.2rem;
      font-weight: 700;
      text-align: center;
    }

    .table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
        padding: 2px 8px !important;
        border: 0px solid;
    }
    .table-borderless{
        margin-bottom: 0px !important;
    }

</style>
<script>
 function modalHistorial(){
    var dni_completo = $("#nro_doc_ident").text().trim();
    var dni_beneficiario = dni_completo.split('-')[1]?.trim();
    $.ajax({
        url: "../modal_historial_atenciones/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divHistorial").html(result);
            $('#tablemodalm').DataTable({
              "order": [],
              "sPaginationType": "full_numbers",
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

  function modalLaboratorio(){
    var dni_completo = $("#nro_doc_ident").text().trim();
    var dni_beneficiario = dni_completo.split('-')[1]?.trim();
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divLaboratorio").html(result);
            $('#tablemodalm1').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

  function modalRadiologico(){
    var dni_completo = $("#nro_doc_ident").text().trim();
    var dni_beneficiario = dni_completo.split('-')[1]?.trim();
    $.ajax({
        url: "../modal_historial_radiologia/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divRadiologico").html(result);
            $('#tablemodalm2').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

  function modalInterconsulta(){
    var dni_completo = $("#nro_doc_ident").text().trim();
    var dni_beneficiario = dni_completo.split('-')[1]?.trim();
    $.ajax({
        url: "../modal_historial_interconsulta/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divInterconsultaH").html(result);
            $('#tablemodalm5').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

  modalHistorial();
  modalLaboratorio();
  modalRadiologico();
  modalInterconsulta();

</script>

@endsection
@section('content')

<div class="container" style="width:90%">
<div class="row">
    <div class="col-md-12">
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
        <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a></li>
            <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
            <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
            <li><a href="#tab1007default" data-toggle="tab" style="font-size:12px">HISTORIAL DE INTERCONSULTA</a></li>
        </ul>
        </div>  
        <div id="diagnosticos" data-list='{!! json_encode($diagnosticos) !!}'></div>
        <div id="procedimientos" data-list='{!! json_encode($procedimientos) !!}'></div>
        <div id="diagnosticoHistory" data-list='{!! json_encode($diagnosticoHistory) !!}'></div> 
        <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
            <fieldset>
                <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12"> 
                    <div class="row">
                        <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">MODULO REGISTRO DE PROCEDIMIENTOS - EDITAR REGISTRO DE PROCEDIMIENTO</h3>
                        </div>
                        <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('consejeria.store_emergencia')}}" id="frmConsejeria" name="frmConsejeria" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                            <input type="hidden" name="consejeria_id" id="consejeria_id" value="{{$consejeria->consejeria_id}}">
                            <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel" value="{{$consejeria->establecimiento_nivel}}">
                            <input type="hidden" name="asegurado_sexo" id="asegurado_sexo" value="{{$consejeria->asegurado_sexo}}">
                            <input type="hidden" name="emergencias_id" id="emergencias_id" value="{{$consejeria->emergencias_id}}">
                            <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="{{$consejeria->tipo_atencion_id}}">
                            <input type="hidden" name="upss_especialista_id" id="upss_especialista_id" value="{{$consejeria->upss_especialista_id}}">
                            <input type="hidden" name="consejeria_fecha_atencion" id="consejeria_fecha_atencion" value="{{$consejeria->consejeria_fecha_atencion}}">
                            <input type="hidden" name="consultorio_id" id="consultorio_id" value="{{$consejeria->consultorio_id}}">
                            
                            <div class="row">
                                <div class="col-md-6">
                                  <div class="panel panel-info">
                                      <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                      </div>
                                      <div class="panel-body">
                                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;width: 24%;"><strong>N° DOCUMENTO</strong></th>
                                              <th style="text-align: left; border: 0px solid;width: 12%;"><strong>:</strong></th>
                                              <td style="border: 0px solid;width: 40%;">
                                                  <span id="nro_doc_ident" style="font-weight: bold; margin-left: -53px;"><?php echo $consejeria->tipo_documento_identidad?> - <?php echo $consejeria->asegurado_nro_doc_ident?> </span>
                                              </td>
                                              <th style="text-align: left; border: 0px solid;width: 9%;"><strong>N° DE HC</strong></th>
                                              <th style="text-align: left; border: 0px solid;width:2%;"><strong>:</strong></th>
                                              <td style="border: 0px solid; text-transform: uppercase;">
                                                  <span id="nro_historia" style="font-weight:bold;"><?php echo $consejeria->nro_historia?></span>
                                              </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-align: left;" colspan="4">
                                                <span id="nombre_paciente" style="margin-left: -53px;">
                                                    <?php echo trim("{$consejeria->asegurado_paterno} {$consejeria->asegurado_materno} {$consejeria->asegurado_nombre}"); ?>
                                                </span>
                                            </td>
                                          </tr>
                                          @if(intval($consejeria->id_tipo_beneficiario) === 8)
                                            <tr class="texto-tamaño">
                                              <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                              <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                              <td style="border: 0px solid; text-align: left; padding-left: 0;" colspan="4">
                                                  <span id="edad" style="margin-left: -53px">
                                                    <?php echo isset($consejeria->asegurado_fecha_nac) ? $consejeria->asegurado_fecha_nac : ''; ?>
                                                    / 
                                                        EDAD (
                                                        <?php
                                                            $asegurado_fecha_nac = DateTime::createFromFormat('d/m/Y', $consejeria->asegurado_fecha_nac);
                                                            $hoy = new DateTime();
                                                            if ($asegurado_fecha_nac !== false) {
                                                              $edad = $asegurado_fecha_nac->diff($hoy);
                                                              echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                            } else {
                                                                echo "Formato de fecha inválido";
                                                            }
                                                        ?>
                                                        )
                                                  </span>
                                              </td>
                                            </tr>
                                            <tr class="texto-tamaño">
                                                <th style="text-align: left; border: 0px solid;"><strong>SEXO</strong></th>
                                                <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                <td style="border: 0; position: relative; padding-left: 0;">
                                                <span id="sexo" style="position: relative; text-transform: uppercase; left: -52px;">
                                                    <?php echo isset($consejeria->asegurado_sexo ) ? ($consejeria->asegurado_sexo  == "M" ? "MASCULINO" : "FEMENINO") : ''; ?>
                                                </span>
                                                </td>
                                            </tr>
                                            <tr class="texto-tamaño">
                                              <th style="text-align: left; border: 0px solid;"><strong>TIPO DE BENEFICIARIO</strong></th>
                                              <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                              <td style="border: 0px solid; text-transform: uppercase;">
                                                  <span id="tipo_beneficiario" style="position: relative; text-transform: uppercase; left: -52px;"><?php echo $consejeria->tipo_beneficiario_descripcion; ?></span>
                                              </td>
                                            </tr>
                                            @if($consejeria->asegurado_situacion_paciente != 'CIVIL')
                                                <tr class="texto-tamaño">
                                                    <th style="text-align: left; border: 0px solid;"><strong>DATOS DEL TITULAR</strong></th>
                                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                    <td style="border: 0px solid; text-transform: uppercase;" colspan="4">
                                                        <span id="dato_titular_name" style="margin-left: -53px;"><?php echo trim("{$consejeria->titular_paterno} {$consejeria->titular_materno} {$consejeria->titular_nombre}");?></span>
                                                    </td>
                                                </tr>
                                                <tr class="texto-tamaño">
                                                    <th style="text-align: left; border: 0px solid;"><strong>CIP/DNI/GRADO</strong></th>
                                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                    <td style="border: 0px solid;" colspan="4">
                                                        <span id="nrodoctitular" style="margin-left: -53px;"><?php echo $consejeria->titular_cip;?> / <?php echo $consejeria->titular_nro_doc_ident;?> / <?php echo $consejeria->titular_grado;?></span>
                                                    </td>
                                                </tr>
                                            @endif
                                          @endif
                                        </table>
                                      </div>
                                   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel panel-info contenedor-datos-generales" style="height:215px;">
                                      <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                                      </div>
                                      <div class="panel-body">
                                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;width: 26%;"><strong>COD.SITEDS SUSALUD</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-transform: uppercase; font-weight:bold;">
                                                <span id="cod_siteds_susalud">{{ $consejeria->cod_siteds_susalud }}</span>
                                            </td>
                                            <th style="text-align: left; border: 0;"><strong>HORA DE ATENCIÓN</strong></th>
                                            <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                            <td style="text-align: right;border: 0;">
                                                <input type="time" name="hora_atencion" id="hora_atencion" class="form-control" style="margin-top:-11px;">
                                              </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>TIPO DE ATENCIÓN</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid;">
                                              <span id="tipo_atencion" style="font-weight:bold;">{{ $consejeria->tipo_atencion }}</span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>FECHA DE INGRESO</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid;">
                                              <span id="fecha_ingreso">           
                                                {{ \Carbon\Carbon::parse($consejeria->emergencias_fecha_registro_admision)->format('d-m-Y g:i A') }}
                                              </span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0;"><strong>FECHA DE ATENCIÓN</strong></th>
                                            <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                            <td style="border: 0px solid;">
                                              <span id="fecha_ingreso">   
                                                <input type="text" class="form-control datepicker" name="fecha_atencion" id="fecha_atencion" autocomplete="off" data-date-end-date="0d" style="width:58%;"/>
                                              </span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0;"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                            <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                            <td style="border: 0;" colspan="4">
                                              <p>{{$upss_encargado[0]->codigo}} - {{$upss_encargado[0]->descripcion_upss}}</p>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;width:25%"><strong>UBICACIÓN DE PACIENTE</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td colspan="5" style="border: 0; font-weight: bold;">
                                                {{ $consejeria->area }} / CAMA : {{ $cama->cama }}
                                            </td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info panel-bordered panel-sm" style="height:185px;">
                                <div class="panel-heading">
                                  <div class="panel-title size-titulo">
                                    <h3 class="panel-title size-titulo">DATOS DEL REGISTRO DE PROCEDIMIENTO</h3>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div class="panel-body">
                                    <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid; width:13%;"><strong>RESPONSABLE DE LA ATENCION</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid; width: 20%;">
                                          <span id="tipo_doc_medico" type="hidden">
                                            <?php echo $consejeria->tipo_documento_identidad ?> - <?php echo $consejeria->medico_dni ?> / <?php echo trim("{$consejeria->medico_paterno} {$consejeria->medico_materno} {$consejeria->medico_nombre}"); ?>
                                          </span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid;"><strong>PROFESION/ESPECIALIDAD</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                                          <span id="profesion_medico" style="margin-left: -2px;">
                                            <?php echo $consejeria->medico_profesion?> / <?php echo $consejeria->especializacion_medico?>
                                          </span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid;"><strong>N° CMP / N° RNE</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;" colspan="4">
                                            <span id="medico" style="margin-left: -2px;"><?php echo $consejeria->medico_colegiatura;?> / <?php echo $consejeria->medico_rne;?></span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0px solid;"><strong>DEPARTAMENTO</strong></th>
                                          <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                          <td style="border: 0px solid;" colspan="4">
                                            <div style="width: 65%; margin-left:-4px;">
                                                <select class="form-control" name="especializacion_medico" id="especializacion_medico">
                                                    <option value="0">--Seleccionar--</option>
                                                    @foreach ($consultorios_medicos as $consultorio)
                                                        <option value="{{$consultorio->id_consultorio}}" {{ intval($consultorio->id_consultorio) === intval($consejeria->consultorio_id) ? "selected" : "" }}>{{$consultorio->nombre_consultorio}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          </td>
                                          <th style="text-align: right; border: 0 solid;width:23%;"><strong>CODIGO UPSS ESPECIALIDAD</strong></th>
                                          <th style="text-align: left; border: 0 solid;width:-20%;"><strong>:</strong></th>
                                          <td style="text-align: left; border: 0;" colspan="4">
                                                <select class="form-control" name="codigo_upss_profesional" id="codigo_upss_profesional" style="width: 65%;>
                                                    <option value="0">--Seleccionar--</option>
                                                </select>
                                          </td>
                                      </tr>
                                    </table>
                                  </div>
                               </div> 
                            </div>
                            <fieldset>
                            {{-- DIAGNOSTICOS --}}
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title size-titulo">
                                        <b>DIAGNÓSTICOS</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                <div class="panel panel-default">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-condensed">
                                        <tr>
                                        <th></th>
                                        <th>Diagnóstico(s)</th>
                                        <th>Tipo</th>
                                        <th>Prioridad</th>
                                        </tr>
                                        @if (isset($diagnosticoHistory) && count($diagnosticoHistory) > 0)
                                        @foreach($diagnosticoHistory as $row)
                                            @if ($row->id_clasificacion_diagnosticos != 9)
                                            <?php
                                                $tipoDiagnostico = '';
                                                switch($row->id_tipo_diagnostico) {
                                                case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                                                case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                                                case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                                                }
                                                $categoria = '';
                                                if ($row->id_categoria != null) {
                                                $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                                                }
                                            ?>
                                            <tr class="row-diagnosticosfinal">
                                                <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipoDiagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input type="text" value="{{ $categoria }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @endif
                                    </table>
                                    </div>
                                </div>
                                <table>
                                    <tr><td><button type="button" id="addDiagnostico" class="btn btn-primary"><i class="fa fa-plus"></i> AGREGAR DIAGNOSTICO</button></td></tr>
                                </table>
                                <br />
                                <table id="tblDiagnostico" width="100%" class="table table-striped" style="font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th width="55%">DIAGNOSTICO</th>
                                        <th width="20%">TIPO</th>
                                        <th width="20%">PRIORIDAD</th>
                                        <th width="5%">ELIMINAR</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                </div>
                            </div>
                            {{-- PROCEDIMIENTOS --}}
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title size-titulo">
                                        <b>PROCEDIMIENTOS</b>
                                    </div>
                                </div>
                                <div class="panel-body">

                                <div class="panel panel-default">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-condensed">
                                        <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Diagnóstico</th>
                                        </tr>
                                        @if (isset($procedimientoHistory))
                                        @foreach($procedimientoHistory as $row)
                                            <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                                            <tr class="row2-{{ $row->cod }}">
                                            <td><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                            <td>
                                                {{$row->nombre}}
                                                <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                            </td>
                                            <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                            <td>
                                                <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly value="{{$row->codigo_diagnostico}}">
                                            </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </table>
                                    </div>
                                </div>

                                <table>
                                    <tr><td><button type="button" id="addProcedimiento" class="btn btn-primary"><i class="fa fa-plus"></i> AGREGAR PROCEDIMIENTO</button></td></tr>
                                </table>
                                <br />
                                <table id="tblProcedimiento" width="100%" class="table table-striped" style="font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th width="45%">PROCEDIMIENTO</th>
                                        <th width="6%">CANTIDAD</th>
                                        <th width="45%">DIAGNÓSTICO</th>
                                        <th width="5%">ELIMINAR</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                </div>
                            </div>
                            
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title size-titulo">
                                        <b>DESCRIPCIÓN</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <label for="medico_nombre">DESCRIPCIÓN</label>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="3">{{$consejeria->observacion}}</textarea>
                                </div>
                                </div>
                            </div>
                            <div style="text-align: right">
                                <button type="button" class="btn btn-danger" id="btnCancel"><i class="fa fa-ban"></i> CANCELAR</button>
                                <button type="button" class="btn btn-primary" id="btnSave"><i class="fa fa-floppy-o"></i> GUARDAR</button>
                            </div>
                            </form>
                         </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab4default">
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                    <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                    <div id='divHistorial'></div>
                    </div>
                </div>
                </div>
            </fieldset>
            </div>
            <div class="tab-pane fade" id="tab5default">
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                    <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                    <div id='divLaboratorio'></div>
                    </div>
                </div>
                </div>
            </fieldset>
            </div>
            <div class="tab-pane fade" id="tab6default">
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                    <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                    <div id='divRadiologico'></div>
                    </div>
                </div>
                </div>
            </fieldset>
            </div>
            <div class="tab-pane fade" id="tab1007default">
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                    <b>HISTORIAL DE INTERCONSULTA</b>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                    <div id='divInterconsultaH'></div>
                    </div>
                </div>
                </div>
            </fieldset>
            </div>
            </fieldset>
        </div>
        </div>
    </div>
    </div>
</div>
</div>

  
@include('frontend.includes.footer')
@endsection