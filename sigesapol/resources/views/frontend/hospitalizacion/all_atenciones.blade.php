@extends('frontend.layouts.master2')
@section('renderjs')
<style>
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
@endsection
@section('content')
<script>
function irAAtencionHospitalizacion(idHospitalizacion, urlDestino) {
  $.ajax({
    url: '/ajax/verificar_hc_evolucion/' + idHospitalizacion,
    type: 'GET',
    dataType: 'json',
    success: function(resp) {
      if (resp.status === 'ok') {
        window.location.href = urlDestino;
      } else if (resp.status === 'completar') {
        window.location.href = '/form_atencion_prestacion_hspcu/<?php echo Auth::user()->id; ?>/' + idHospitalizacion;
      } else {
        Swal.fire({
          icon: 'warning',
          iconColor: '#e74c3c',
          title: 'ALERTA',
          html: '<span style="font-size:17px;">El registro de la Historia Clínica aún no está terminada, por lo que no se puede ingresar a esta atención.<br><br><b>Médico responsable del registro:</b><br><span style="color:#e74c3c;font-weight:bold;font-size:19px;">' + resp.medico + '</span></span>',
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
</script>
<div class="container-fluid">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Atenciones en Hospitalizacion de un Paciente</h3>
            <!-- <div class="pull-right">
                <a href="#" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
            </div> -->
        </div>
        <div class="panel-body">
            
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
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
                            <div class="form-group" >
                                <div class="col-lg-6 col-md-6 col-sm-4">
                                    <label>APELLIDOS Y NOMBRES DEL PACIENTE</label>
                                    <p>{{$paciente->asegurado_paterno}} {{$paciente->asegurado_materno}}, {{$paciente->asegurado_nombre}}</p>
                                </div>
                            </div>
                        </div>
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
                            @if (isset($cama) && isset($cama->cama))
                            <div class="form-group">
                                <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label for="">UBICACIÓN FÍSICA:</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label for="">SALA</label>
                                    <p>{{$cama->consultorio}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label for="">PABELLON</label>
                                    <p>{{$cama->pabellon}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label for="">PISO</label>
                                    <p>{{$cama->piso}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label>HABITACION</label>
                                    <p>{{$cama->cuarto}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label>CAMA</label>
                                    <p>{{$cama->cama}}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading"><b>HISTORIAL</b></div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblPrestacion" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        @if ($is_fiscalizador)
                                        <th rowspan="2">N</th>
                                        @endif
                                        <th rowspan="2">Fecha y Hora de Atención</th>
                                        <th rowspan="2">Servicio A Cargo</th>
                                        <th colspan="2">Ubicación Física de Atención</th>
                                        <th rowspan="2">Médico</th>
                                        <th rowspan="2" style="text-align:center">Ver Atención</th>
                                        <th rowspan="2" style="text-align:center">Pdf Atención</th>
                                        <th rowspan="2" style="text-align:center;min-width:106px">Receta</th>
                                        <th rowspan="2" style="text-align:center">Lab</th>
                                        <th rowspan="2" style="text-align:center">Rad</th>
                                        <th rowspan="2" style="text-align:center">Interc.</th>
                                        <th rowspan="2" style="text-align:center">Interc. Rpta</th>
                                        <th rowspan="2">Anular</th>
                                    </tr>
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Cama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = $atenciones != null ? count($atenciones) : 0; ?>
                                    @foreach ($atenciones as $a)
                                    <tr>
                                        <td>{{$i}}</td>
                                        @if ($is_fiscalizador)
                                        <td>{{$a->id_prestacion}}</td>
                                        @endif
                                        <td>{{$a->fecha_atencion.' '.$a->hora_atencion}}</td>
                                        <td>{{$a->servicio_encargado_atencion}}</td>
                                        <td>{{$a->servicio}}</td>
                                        <td>{{$a->nombre_cama}}</td>
                                        <td>{{$a->medico_paterno.' '.$a->medico_materno.' '.$a->medico_nombre}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Ver Atención</b>">
                                                <a href="/ver_prestacion_hospitalizacion/{{$id_user}}/{{$a->id_prestacion}}" <?php if($hospitalizacion->requiereValidacionHc()): ?>onclick="irAAtencionHospitalizacion({{$hospitalizacion->id}}, this.href); return false;"<?php endif; ?> class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($a->ruta_prestacion != null && $a->id_estado_reg == 1)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                                    <!--a href="/print_hospitalizacion_atencion/{{$a->id_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i>
                                                    </a-->
                                                    @if($a->flag==1)
                                                        <a href="/{{$a->flag}}/{{$a->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                    @else
                                                        <a href="/{{$a->flag}}/{{$a->ruta_prestacion}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a>
                                                    @endif
                                                </div>
                                            @else
                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-download"></i></a></button>
                                            @endif
                                            @if ($a->hoja_terapeutica == 1)
                                                @if ($a->ruta_terapeutica != null)
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Hoja Terapeutica</b>">
                                                        
                                                        <a href="/{{$a->flag_terapeutica}}/{{$a->ruta_terapeutica}}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a>
                                                    </div>
                                                @else
                                                    <button class="btn btn-warning btn-xs" disabled=""><i class="fa fa-download"></i></a></button>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_receta != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_receta = str_replace(['{', '}'], '', $a->ruta_receta);
                                                $ruta = explode(',', $ruta_receta);
                                                
                                                $flag_receta = str_replace(['{', '}'], '', $a->flag_receta);
                                                $flag_firma = explode(',', $flag_receta);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px">
                                                        @if($flag_firma[0]==1)
                                                            <a href="/{{$flag_firma[0]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                        @else
                                                            <a href="/{{$flag_firma[0]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a>
                                                        @endif
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Receta</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_laboratorio != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_laboratorio = str_replace(['{', '}'], '', $a->ruta_laboratorio);
                                                $ruta = explode(',', $ruta_laboratorio);
                                            
                                                $flag_laboratorio = str_replace(['{', '}'], '', $a->flag_laboratorio);
                                                $flag_firma = explode(',', $flag_laboratorio);

                                                $arrayIdsLab = [];
                                                if (!empty($a->lid)) {
                                                  $ids = str_replace(['{', '}'], '', $a->lid);
                                                  $arrayIdsLab = array_map('intval', explode(',', $ids));
                                                }
                                                $href = "";

                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    <?php
                                                      if (intval($a->nivel_establecimiento) === 3) {
                                                        $href = "/ver_laboratorio_imprimir/" . $arrayIdsLab[$i];
                                                      } else {
                                                        $href = "/laboratorio/rua/pdf/" . $arrayIdsLab[$i];
                                                      }
                                                    ?>
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            @if($flag_firma[$i]==1)
                                                                {{-- <a href="/ver_laboratorio_imprimir/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a> --}}
                                                                <a href="{{$href}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                            @else
                                                                {{-- <a href="/ver_laboratorio_imprimir/{{$ruta[$i]}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a> --}}
                                                                <a href="{{$href}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            
                                                <?php
                                                $ruta_radiologia = str_replace(['{', '}'], '', $a->rid);
                                                $ruta = explode(',', $ruta_radiologia);
                                                
                                                $flag_radiologia = str_replace(['{', '}'], '', $a->flag_radiologia);
                                                $flag_firma = explode(',', $flag_radiologia);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($flag_firma[$i] !=0)
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            @if($flag_firma[$i]==1)
                                                                <a href="/ver_radiologia_imprimir/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                            @else
                                                                <a href="/ver_radiologia_imprimir/{{$ruta[$i]}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        @if ($ruta[$i] != NULL)
                                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                                <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endfor
                                            
                                        </td>
                                        <td>
                                            @if ($a->ruta_interconsulta != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_interconsulta = str_replace(['{', '}'], '', $a->ruta_interconsulta);
                                                $ruta = explode(',', $ruta_interconsulta);
                                                
                                                $flag_interconsulta = str_replace(['{', '}'], '', $a->flag_interconsulta);
                                                $flag_firma = explode(',', $flag_interconsulta);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">
                                                            @if($flag_firma[$i]==1)
                                                                <a href="/{{$flag_firma[$i]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                            @else
                                                                <a href="/{{$flag_firma[$i]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-electronico btn-xs"><i class="fa fa-download"></i></a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_rpta_interconsulta != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_rpta_interconsulta = str_replace(['{', '}'], '', $a->ruta_rpta_interconsulta);
                                                $ruta = explode(',', $ruta_rpta_interconsulta);
                                                
                                                $flag_rpta_interconsulta = str_replace(['{', '}'], '', $a->flag_rpta_interconsulta);
                                                $flag_firma = explode(',', $flag_rpta_interconsulta);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Rpta Interconsulta</b>" style="float:left;padding-left:3px">
                                                            <a href="/{{$flag_firma[$i]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Rpta Interconsulta</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->estado == 0 || $a->estado === 6)
                                            @elseif ($a->estado == 3)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atención</b>" style="float:left;padding-left:3px"><button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button></div>
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
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection