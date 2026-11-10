
@extends('frontend.layouts.master')
@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .modal-dialog {
    width: 90%;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  table {
    margin: 0 auto; 
    width: 80%;    
  }

  .title {
    font-size: 12px;
    text-align: center;
    font-weight: bold;
   }
    
   .mopri {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
   }


  .divider {
    border-top: 1px solid #000;
    margin: 10px 0;
   }


   .fechas {
    margin-top: 20px; 
   }

   .panel-body {
    padding: 40px;
  }  

   
  .priority-options {
    display: flex;
    align-items: center;
  }

  .priority-options .option {
    margin-left: 6%;
  }

  .operado{ 
    display: flex;
    align-items: center;
  }

  .operado .option{
    margin-left: 16.2%;
  }

  .intervencion{
    display: flex;
    align-items: center;
  }

  .intervencion .option{
    margin-left: 4.5%;
  }

  .post-quirurgica{
    display: flex;
    align-items: center;
  }

  .post-quirurgica .option{
    margin-left: 6.8%;
  }

  .historia-title {
    text-align: center;
    font-weight: bold;
    margin: 20px 0 10px;
    font-size: 14px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
  }
        
  table, th, td {
    border: 1px solid #000;
  }
        
  td {
    padding: 5px;
    vertical-align: top;
  }
        
  .label-cell {
    width: 25%;
  }
        
  .empty-cell {
    height: 40px;
  }
        
  .nested-table {
    margin-bottom: 0;
  }
        
  .risk-info {
    text-align: center;
    margin-top: 5px;
  }
        
  .arrow {
    text-align: center;
    margin: 5px 0;
  }
        
</style>
<script>

</script>

@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÃ‰REZ DE SERVICIO","ALFÃ‰REZ MAESTRO ARMERO","CAPITÃN","CAPITÃN DE SERVICIO","CAPITÃN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÃ‰CNICO DE 1RA","ESPECIALISTA TÃ‰CNICO DE 2DA","ESPECIALISTA TÃ‰CNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="panel panel-primary filterable">
    
            <div class="panel panel-info panel-bordered panel-sm panel-body">
                <form class="form-horizontal" method="post" id="frmBloque" action="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                    <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
                    <input type="hidden" value="<?php echo $id_intervencion?>" name="id_intervencion" id="id_intervencion">
                    <fieldset>
                        <div class="row text-center mb-3">
                          <div class="col-12">
                              <div class="title">HOSPITALIZACIÓN: CUPS: {{$codigo_cups}}</div>
                              <div class="mopri">MOPRI</div>
                          </div>
                        </div>
                        <table>
                            <b>HOSPITAL NACIONAL PNP LUIS N. SAENZ</b><br>
                            <b>DIRECCION DE SANIDAD PNP</b>
                        </table>
                        <div>
                            <div class="section">
                                <div class="fechas">
                                    <div class="option">FECHA DE EVALUACIÓN PREQUIRÚRGICA MOPRI - SNP</div>
                                    <div class="option">FECHA DE INICIO: {{ \Carbon\Carbon::parse($intervencion->fecha_inicio_intervencion)->format('Y-m-d') }}</div>
                                    <div class="option">FECHA DE CULMINO: {{ \Carbon\Carbon::parse($intervencion->fecha_fin_intervencion)->format('Y-m-d') }}</div>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-3">APELLIDOS Y NOMBRES: {{ $prestacion->asegurado_paterno . ' ' . $prestacion->asegurado_materno . ' ' . $prestacion->asegurado_nombre }}</div>
                                <div class="col-md-3">PARENTESCO: {{$prestacion->parentesco }}</div>
                                <div class="col-md-2">EDAD: {{$prestacion->edadpaciente }}</div>
                                <div class="col-md-2">SEXO: {{$prestacion->sexo }}</div>
                                <div class="col-md-2">ESTADO CIVIL: {{$admision[0]->estado_civil }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">HISTORIA CLÍNICA: {{$prestacion->nro_historia }}</div>
                                <div class="col-md-3">DNI: {{$prestacion->dni }}</div>
                                <div class="col-md-2">DOMICILIO: {{$prestacion->domicilio }}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col-md-3">TITULAR: {{ $dato_titular[0]->paterno . ' ' . $dato_titular[0]->materno . ' ' . $dato_titular[0]->nombre }}</div>
                                <div class="col-md-3">CIP: {{$prestacion->cip }}</div>
                                <div class="col-md-2">GRADO: {{$prestacion->grado }}</div>
                                <div class="col-md-2">SITUACIÓN: {{$prestacion->situacion_paciente }}</div>
                                <div class="col-md-2">UNIDAD: {{$admision[0]->unidad_pnptitular}}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="section">
                                <div>1). SERVICIO DE CIRUGÍA GENERAL</div>
                                <div>2). CIRUJANO QUE INDICA LA OPERACIÓN: {{ trim($cirujanos[0]->materno . ' ' . $cirujanos[0]->paterno . ' ' . $cirujanos[0]->nombre) }}</div>
                                <div>3). DATOS CLÍNICOS MÁS IMPORTANTES: {{$prestacion->plan_trabajo }}</div>
                                <div>4). DIAGNÓSTICO(S) PRE-OPERATORIO: </div>
                                <div>5). PROCEDIMIENTO QUIRÚRGICO: {{$procedimiento[0]->nombre }}</div>
                            </div>
                            <div class="section">
                                <div class="priority-options">
                                    <div>6). PRIORIDAD DE PROGRAMACIÓN EN SEMANAS:</div>
                                    <div class="option">{{$intervencion->prioridad_programacion}}</div>
                                </div>
                            </div>
                            <?php
                                $lugar_operacion = "";
                                switch ($intervencion->id_lugar_operacion) {
                                    case 1: $lugar_operacion = "HOSPITALIZADO"; break;
                                    case 2: $lugar_operacion = "CLÍNICA DE DÍA"; break;
                                }
                            ?>
                            <div class="section">
                                <div class="operado">
                                    <div>7). A SER OPERADO EN:</div>
                                    <div class="option">{{$lugar_operacion}}</p></div>
                                </div>
                            </div>
                            <div class="section">
                                <div class="intervencion">
                                <div>8). REQUIERE INTERVENCIÓN EN EL PRE-QUIRÚRGICO:</div>
                                    <div class="option">{{$intervencion->tiempo_internamiento_preqx}}</div>
                                </div>
                            </div>
                            <div class="section">
                                <div class="post-quirurgica">
                                <div>9). DÍAS DE HOSPITALIZADO POST-QUIRÚRGICA:</div>
                                    <div class="option">{{$intervencion->tiempo_hospitalizacion_postqx}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="historia-title">HISTORIA CLÍNICA</div>
                        <table>
                            <tr>
                                <td class="label-cell">1). ENFERMEDAD ACTUAL</td>
                                <td>{{$prestacion->anamnesis}}</td>
                            </tr>
                            <tr>
                              <td colspan="2" class="subtitulo">2). ANTECEDENTES PATOLÓGICOS</br> 
                                A) PERSONALES: (ENFERMEDAD)(OPERACIONES)/ALERGIAS, MEDICAMENTOS Y OTROS)</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="empty-cell">{{$prestacion->antecedente}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-top: none; border-bottom: none; padding: 4px 8px;">B) FAMILIARES</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="empty-cell">{{$prestacion->antecedente_familiar}}</td>
                            </tr>
                       
                            <tr>
                                <td class="label-cell">3). EXAMEN FÍSICO</td>
                                <td>{{$prestacion->examen_fisico}}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">4) IMPRESIÓN DIAGNÓSTICA:</td>
                                <td class="empty-cell"></td>
                            </tr>
                            <tr>
                              <td colspan="2" class="subtitulo">RIESGO QUIRÚRGICO:<br>
                               1). CARDIOLOGÍA</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="empty-cell"></td>
                            </tr>
                            <tr>
                              <td class="label-cell">2). NEUMOLOGÍA</td>
                              <td>
                                  <div></div>
                                  <div class="risk-info"></div>
                              </td>
                            </tr>
                            <tr>
                              <td class="label-cell">3). ANESTESIOLOGÍA</td>
                              <td>
                                  <div></div>
                                  <div class="risk-info"></div>
                              </td>
                            </tr>
                            <tr>
                              <td class="label-cell">4). OTRAS ESPECIALIDADES</td>
                              <td>
                                  <div></div>
                                  <div class="risk-info"></div>
                              </td>
                            </tr>
                        </table>
                    </fieldset>
                    <a class="btn btn-primary btn-sm hidden-print pull-right" href="/cq_lista_pendiente"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                </form>
          </div>
        </div>
      </div>
    </div>
</div>



















@include('frontend.includes.footer')
@endsection