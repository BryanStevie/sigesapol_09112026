<?php
  $datos_gastro = $pp_gastro;
  $pp_gastro_campo = function ($campo) use ($datos_gastro) {
    $valor = e($datos_gastro->$campo);
    return '<span class="pp-gastro-valor">' . $valor . '</span>'
      . '<textarea class="form-control pp-gastro-campo" data-campo="' . $campo . '" rows="2" style="display:none;width:100%">' . $valor . '</textarea>';
  };
?>
<div class="panel-body" id="VerGastro">
  <div id="fsGastro" >
    <div class="panel with-nav-tabs panel-default">
      <div class="panel-heading">
        <ul class="nav nav-tabs">
          @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
          <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
          @else
            @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
            <li class="active"><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
            @else
              @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
              <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
              <li><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
              @endif
            @endif
          @endif
        </ul>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
          <div class="tab-pane fade in active" id="tab_baja">
          @else
            @if($datos_gastro->baja==0 && $datos_gastro->alta==1)
            <div class="tab-pane fade" id="tab_baja">
            @else
              @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
              <div class="tab-pane fade in active" id="tab_baja">
              @endif
            @endif
          @endif
            <fieldset>
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-list-search" id="tblGastroBaja" cellspacing="0" width="100%" style="margin-top:5px;">
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <tr>
                          <td width="15%"><b>INDICACIONES</b></td>
                          <td width="35%" colspan="4">
                            {!! $pp_gastro_campo('baja_indicaciones') !!}
                          </td>
                          <td width="15%"><b>MEDICACION:</b></td>
                          <td width="35%" colspan="3">
                            {!! $pp_gastro_campo('baja_medicacion') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>INSPECCION:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="15%"><b>ESTATICA:</b></td>
                          <td width="85%" colspan="7">
                            {!! $pp_gastro_campo('baja_estatica') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="15%"><b>DINAMICA:</b></td>
                          <td width="85%" colspan="7">
                            {!! $pp_gastro_campo('baja_dinamica') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="15%"><b>TACTO RECTAL:</b></td>
                          <td width="85%" colspan="7">
                            {!! $pp_gastro_campo('baja_tacto_rectal') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>PREPARACION:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_preparacion') !!}
                          </td>
                      </tr>
                      <tr>
                          <td><b>ESCALA DE BOSTON:</b></td>
                          <td>CD</td>
                          <td>
                            {!! $pp_gastro_campo('baja_escala_cd') !!}
                          </td>
                          <td>CT</td>
                          <td>
                            {!! $pp_gastro_campo('baja_escala_ct') !!}
                          </td>
                          <td>CI</td>
                          <td>
                            {!! $pp_gastro_campo('baja_escala_ci') !!}
                          </td>
                          <td>TOTAL</td>
                          <td>
                            {!! $pp_gastro_campo('baja_escala_total') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>INSERCION:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_insercion') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>HALLAZGOS:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>CANAL ANAL:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_canal_anal') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>RECTO:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_recto') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>SIGMOIDES:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_sigmoide') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>DESCENDENTE:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_descendente') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Transverso:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_transverso') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Ascendente:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_ascendente') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Ciego:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('baja_ciego') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>BIOPSIA:</b></td>
                          <td width="30%" colspan="3">
                            {!! $pp_gastro_campo('baja_biopsia') !!}
                          </td>
                          <td width="15%"><b>COMPLICACIONES</b></td>
                          <td width="30%" colspan="4">
                            {!! $pp_gastro_campo('baja_complicaciones') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                          <td width="85%" colspan="5">
                            {!! $pp_gastro_campo('baja_observaciones') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="50%" colspan="4"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                          <td width="50%" colspan="5">
                          </td>
                      </tr>
                      <tr>
                          <td width="100%" colspan="9">
                            {!! $pp_gastro_campo('baja_diag_endoscopico') !!}
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </fieldset>
          </div>
          @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
          <div class="tab-pane fade in active" id="tab_alta">
          @else
            @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
            <div class="tab-pane fade" id="tab_alta">
            @else
              @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
              <div class="tab-pane fade" id="tab_alta">
              @endif
            @endif
          @endif
          
            <fieldset>
              <div class="panel-body">
                <div class="row">
                  <table class="table table-striped table-list-search" id="tblGastroAlta" cellspacing="0" width="100%" style="margin-top:5px;">
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <tr>
                          <td width="15%"><b>INDICACIONES</b></td>
                          <td width="30%" colspan="4">
                            {!! $pp_gastro_campo('alta_indicaciones') !!}
                          </td>
                          <td width="15%"><b>MEDICACION:</b></td>
                          <td width="30%" colspan="3">
                            {!! $pp_gastro_campo('alta_medicacion') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>HALLAZGOS</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>ESOFAGO:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_esofago') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>ESTOMAGO</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Cardias:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_cardias') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Fondo:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_fondo') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Cuerpo:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_cuerpo') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Antro:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_antro') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>DUODENO</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Bulbo:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_bulbo') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Segunda porción:</b></td>
                          <td width="85%" colspan="8">
                            {!! $pp_gastro_campo('alta_seg_porcion') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>BIOPSIA:</b></td>
                          <td width="30%" colspan="3">
                            {!! $pp_gastro_campo('alta_biopsia') !!}
                          </td>
                          <td width="15%"><b>COMPLICACIONES</b></td>
                          <td width="30%" colspan="4">
                            {!! $pp_gastro_campo('alta_complicaciones') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                          <td width="85%" colspan="5">
                            {!! $pp_gastro_campo('alta_observaciones') !!}
                          </td>
                      </tr>
                      <tr>
                          <td width="50%" colspan="3"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                          <td width="50%" colspan="6">
                          </td>
                      </tr>
                      <tr>
                          <td width="100%" colspan="9">
                            {!! $pp_gastro_campo('alta_diag_endoscopico') !!}
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>    
