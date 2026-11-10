
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
                            <h3 class="panel-title">MÓDULO DE AUDITORÍA DE PRESTACIONES</h3>
                          </div>
                          <div class="panel-body">

                            <form class="form-horizontal" method="post" action=" " id="frmPrestacion"
                              autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">

                        
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
                                              <label>Fecha Cierre Administrativo Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_cierre_adm_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Egreso Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_egreso_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Renipress Vincula Fups</label>
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
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
    
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
                                          <div class="col-md-3">
                                            <label>Codigo CUPS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo "<b>".$nombre_cups. "</b>"?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado</label>
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
                                          <div class="col-md-3">
                                            <label>Fecha de Caducidad del Seguro</label>
                                            <span class="form-control" style=" background-color: lightgreen;" name="consultorio" id="consultorio"><?php echo $dia_caducidad. '-' .$mes_caducidad. '-'.$año_caducidad?>
    
                                          </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                          <div class="col-md-3">
                                            <label>CODIGO EMPAQUETADO</label>
                                            <span class="form-control" style=" background-color: lightgreen;" name="consultorio" id="consultorio"><?php echo $prestacion->observaciones?>
                                            </div>
                                          </div>
                                      </div>
                                </fieldset>
                                <fieldset>
                                  <a target="_blank" href="/ver_prestacion_auditoria/<?php echo Auth::user()->id;?>/{{ $prestacion->id_prestacion }}" class="btn btn-primary pull-right" id="btnPrestacion">Ver mas información </a>
                                </fieldset>
                                <br>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>DIAGNOSTICOS DE ATENCION (CIE10)</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">               
                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" >
                                      <thead <?php if($diag_aten != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                        <tr>
                                          <th width="15%">Codigo</th>
                                          <th width="55%">Nombre</th>
                                          <th width="15%">Tipo</th>
                                          <th width="15%">Clasificación</th>
                                      </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                        <?php foreach($diagnosticos_prestacion as $row): ?>
                                                          <?php
                                                          $resaltar = false; 
                                                          if (!empty($cod_diag)) {
                                                              foreach ($cod_diag as $dato) {
                                                                  if ($dato == $row->codigo_cie10) {
                                                                      $resaltar = true; 
                                                                      break; 
                                                                  }
                                                              }
                                                          }
                                                          ?>
                                                          <tr <?php if ($resaltar) echo 'style="margin-top:5px; background-color: yellow"'; else echo 'style="margin-top:5px"'; ?>>
                                                            <td><?php echo $row->codigo_cie10?></td>
                                                            <td><?php echo $row->nombre_cie10?></td>
                                                            <td><?php echo $row->tipo_diagnostico?></td>
                                                            <td><?php echo $row->tipo_atencion?></td>
                                                          </tr>
                                                      <?php endforeach; ?>
                                      </tbody>
                                    </table>               
                                  </div>
                                </div>
                              </fieldset>
                             
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                   <div class="panel-heading">
                                   <div class="panel-title text-center" style="position: relative">
                                  <b>PROCEDIMIENTOS MEDICOS (CPMS)</b>
                                  <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                                </div>
                              </div>
                              <div class="panel-body">
                            
                                  <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" <?php if($proc != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                    <thead>
                                      <tr>
                                        <th width="5%" style="text-align: center;">Codigo</th>
                                        <th width="60%" style="text-align: center;">Nombre</th>
                                        <th width="5%" style="text-align: center;">Cantidad</th>
                                        <th width="10%" style="text-align: center;">Tipo Diag</th>
                                        <th width="5%" style="text-align: center;">Diagnostico</th>
                                        <th width="5%" style="text-align: center;">Precio</th>
                                        <th width="10%" style="text-align: center;">Tipo Proc</th>
                                    </tr>
                                    
                                    </thead>
                                    <tbody>
                                        <?php foreach($procedimientos_trama as $row):?>
                                        <tr>
                                        <tr>
                                        <td style="text-align: center;"><?php echo $row->codigo_procedimiento?></td>
                                        <td><?php echo $row->nombre_procedimiento?></td>
                                        <td style="text-align: center;"><?php echo $row->cantidad_indicada?></td>
                                        <td style="text-align: center;"> 
                                            <?php
                                                if ($row->flag_evolucion == 1) {
                                                    echo 'Atencion';
                                                } elseif ($row->flag_evolucion == 2) {
                                                    echo 'Evaluacion';
                                                } else {
                                                    echo ''; 
                                                }
                                            ?>
                                        </td>
                                        <td style="text-align: center;"><?php echo $row->diagnostico_relacionado?></td>
                                        <td style="text-align: center;"><?php echo $row->precio_procedimiento?></td>
                                        <td style="text-align: center;">
                                            <?php
                                                if ($row->tipo_procedimiento == 1) {
                                                    echo 'Medico';
                                                } elseif ($row->tipo_procedimiento == 2) {
                                                    echo 'Laboratorio';
                                                } elseif ($row->tipo_procedimiento == 3) {
                                                    echo 'Imagenes';
                                                } else {
                                                      echo ''; 
                                                }
                                            ?>
                                        </td>
                                        </tr>
                                        </tr>
                                        <?php endforeach;?>
                                        
                                      </tbody>
                                  
                                  </table>
                                
                              </div>
                            </div>
                          </fieldset>
                         
                          <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center" >
                                      <b>PRODUCTOS FARMACEUTICOS Y DISPOSITIVOS MEDICOS (PETITORIO)</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">               
                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" >
                                      <thead <?php if($prod != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                        <tr>
                                          <th width="10%">Codigo </th>
                                          <th width="50%">Nombre</th>
                                          <th width="10%">Cantidad Prescrita</th>
                                          <th width="10%">Cantidad Entregada</th>
                                          <th width="10%">Precio</th>
                                          <th width="10%">Valorizacion</th>
                                          <th width="10%">CIE10 </th>
                                      </tr>
                                      </thead>
                                      <tbody>

                                                      <?php foreach($productos_trama as $row): ?>
                                                          <?php
                                                          $resaltar = false; 
                                                          if (!empty($cod_prod)) {
                                                              foreach ($cod_prod as $dato) {
                                                                  if ($dato == $row->id_prod_med) {
                                                                      $resaltar = true; 
                                                                      break; 
                                                                  }
                                                              }
                                                          }
                                                          ?>
                                                          <tr <?php if ($resaltar) echo 'style="margin-top:5px; background-color: yellow"'; else echo 'style="margin-top:5px"'; ?>>
                                                              <td><?php echo $row->id_prod_med?></td>
                                                              <td><?php echo $row->nombre?></td>
                                                              <td><?php echo $row->cantidad_prescrita?></td>
                                                              <td><?php echo $row->cantidad_entregada?></td>
                                                              <td><?php echo $row->precio?></td>
                                                              <td><?php echo $row->valorizacion?></td>
                                                              <td><?php echo $row->id_cie10?></td>
                                                          </tr>
                                                      <?php endforeach; ?>
                                        </tbody>
                                    </table>               
                                  </div>
                                </div>
                              </fieldset>
                             
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PRECIOS TRAMA</b>
                                    </div>
                                  </div>
                                    <div class="panel-body">  
                                      <div class="col-md-2">
                                          <label>Precio Producto</label>
                                          <span class="form-control" style="background-color: lightpink;" name="consultorio" id="consultorio"><?php echo $dato_precio_productos?>
                                      </div>
                                      <div class="col-md-2">
                                          <label>Precio Procedimiento</label>
                                          <span class="form-control" style="background-color: lightpink;" name="consultorio" id="consultorio"><?php echo $dato_precio_procedimiento?>
                                      </div>
                                      <div class="col-md-2">
                                          <label>Precio Total Trama</label>
                                          <span class="form-control" style="background-color: #c62828; color: black; font-weight: bold;" name="consultorio" id="consultorio"><?php echo $dato_precio?>
                                      </div>
                                    </div>
                                </div>
                              </fieldset>
                              <a href="/all_auditoria" class="btn btn-primary pull-right"  id="btnPrestacion">LISTADO </a>
                                

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


  <div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

        <div class="modal-body">

          <div id="diveditpregOpc"></div>

        </div>

      </div>

    </div>
  </div>

  <div class="modal fade" id="showDetalleExamenPendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
      <div class="modal-content">
        <div class="modal-header modal-header-examen" style="background-color: #ffffff">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
              style="color: #494949">&times;</span></button>
          <h4 class="modal-title" style="background-color: #ffffff"><small
              style="color: #494949; font-weight: 700">Pendientes Laboratorio</small></h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive" id="contenido-table-pendiente">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="showDetalleExamenResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
      <div class="modal-content">
        <div class="modal-header modal-header-examen" style="background-color: #ffffff">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
              style="color: #494949">&times;</span></button>
          <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949">Resultados
              Laboratorio</small></h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive" id="contenido-table-result">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  @include('frontend.includes.footer')
  @endsection