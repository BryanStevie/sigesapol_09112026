
@extends('frontend.layouts.master2')
@section('renderjs')

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

@endsection
@section('content')

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        
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
                            <h3 class="panel-title">MÓDULO DE AUDITORÍA - DETALLES PRESTACION</h3>
                          </div>
                          <div class="panel-body">

                            <form class="form-horizontal" method="post" action=" " id="frmPrestacion"
                              autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{ $prestacion->id }}" name="id_prestacion_trama" id="id_prestacion_trama">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>TRAMA PRESTACION</b>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="panel-body" id="collapseprestacionespaciente">
                                        <div class="form-group">
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Codigo Renipress</label>
                                              <span class="form-control" style="text-align:left"><?php echo $prestacion->codigo_renipress ?>
                                              </span>
                                              </div>
                                              <div class="col-md-2">
                                                <label>Numero Lote</label>
                                                <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_lote ?>
      
                                              </div>
                                            <div class="col-md-2">
                                              <label>Numero Correlativo</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_correlativo ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Procedencia Prestacion</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->procedencia_prestacion ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Referencia</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ipress_referencia_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Hoja Referencia FUPS</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->hoja_referencia_fups ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                          
                                            <div class="col-md-2">
                                              <label>Tipo Documento Identidad Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->tipo_doc_identidad.' - '.$nombre_docu?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Documento Identidad Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_nro_doc_identidad ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Historia Clinica Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_hist_clinica ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Paterno Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_apellido_paterno ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Materno Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_apellido_materno ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Nombres Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_nombres ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Tipo Cobertura</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipo_cobert.' - '.$nombre_cobertura?>
    
                                            </div>
                                            <div class="col-md-4">
                                              <label>UPSS Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_upss_fups.' - '.$nombre_upss?> 
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Atencion Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_atencion_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Ingreso Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_ingreso_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Inicio Administrativo Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_inicio_adm ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Feche Cierre Administrativo Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_cierre_adm_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Egreso Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_egreso_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Renioress Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->renipress_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Lote Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_lote_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Estado Paciente Mujer Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->estado_paci_mujer_fups ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                          <div class="col-md-2">
                                              <label>Fecha Probable Parto Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_probable_parto_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Parto Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_parto_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Destino Asegurado Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_destino_aseg_fups.' - '.$nombre_destino ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Destino Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ipress_destino_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Hoja Destino Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_hoja_destino_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                            <label>Fecha Fallecimiento</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_fallece ?>
    
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Numero Acreditacion Siteds</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_acreditacion_siteds ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Tipo Documento Identidad Med.</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipodoc_ident.' - '.$nombre_docu_m?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Documento Identidad Med.</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numdoc_ident ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Paterno Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_ape_paterno ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Materno Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_ape_materno ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Nombres Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_nombres ?>
    
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Tipo Profesional</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipo_prof ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Colegiatura</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_coleg ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Especialidad Profecional</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_especialidad_prof ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero RNE</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_rne ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>RUC IPRESS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ruc_ipress ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Tipo Documento Pago</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
    
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">

                                          <div class="col-md-2">
                                            <label>Id Cups</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_cups ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Codigo Cups</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo "<b>".$nombre_cups. "</b>"?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado Trama</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $nombre_estado?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado del Asegurado</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo "<b>".$estado_asegurado. "</b>"?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Situacion del Asegurado</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $estado_situacion?>
    
                                          </div>
                                         
                                        </div>
                                       
                                        </div>
                                        </div>
                                      </div>
                                </fieldset>
                                
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading" style="background-color: red; color: black;">
                                      <div class="panel-title text-center" style="background-color: red; color: black;">
                                          <h3 class="panel-title" style="font-weight: bold;">RESPUESTA DE SALUDPOL</h3>
                                      </div>
                                        
                                      </div>
                                      <div class="row">
                                        <br>
                                          <div class="col-md-12">
                                            <div class="col-md-4">
                                              <label>Estatus Saludpol</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $estadoPaquete?>
                                            </div>
                                            <div class="col-md-4">
                                              <label>Resultado Saludpol </label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $nombre_estado?>
                                            </div>                                  
                                          </div>
                                        <br>
                                      </div>
                                      
                                      <div class="row">
                                        <br>
                                        @if (!empty($observacion_saludpol))
                                          <div class="col-md-12">
                                            <div class="panel-body">               
                                                <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px">
                                                  <thead>
                                                    <tr>
                                                      <th width="15%">Campo Observado</th>
                                                      <th width="30%">Valor Observado</th>
                                                      <th width="10%">Codigo Regla</th>
                                                      <th width="45%">Definicion</th>
                                                  </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                    <?php foreach($observacion_saludpol as $row):?>
                                                    <tr>
                                                      <td><?php echo $row['tipoRegla']?></td>
                                                      <td><?php echo $row['descripcion']?></td>
                                                      <td><?php echo $row['codRegla']?></td>
                                                      <td><?php echo $row['valorObservado']?></td>                                                                                                       
                                                    </tr>
                                                    <?php endforeach?>
                                                   
                                                  </tbody>
                                                  <td>
                                                    <br>
                                                    <a href="/editar_all_auditoria/{{$prestacion->id}}" class="btn btn-primary pull-left"  id="btnPrestacion">Ver Detallado</a>
                                                  </td>
                                                </table>               
                                              </div>
                                          </div>
                                          @else
                                            <div class="col-md-4">
                                              <label>Observacion </label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                            </div>
                                            @endif
                                        <br>
                                      </div>
                                    <br>
                                </fieldset>

                              <a href="/all_auditoria" class="btn btn-primary pull-right"  id="btnPrestacion">Atras </a>
                                

                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          
            </fieldset>
            
          </div>
        </div>
      </div>
    </div>
  </div>



  @include('frontend.includes.footer')
  @endsection