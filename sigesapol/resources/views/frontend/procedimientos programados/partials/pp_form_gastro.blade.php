<fieldset id="VerGastroenterologia">
  <div class="panel panel-info panel-bordered panel-sm">
    <div class="panel-body">
      <div class="form-group">
        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
          <label>GASTROENTEROLOGIA</label>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          <button type="button" id="addGastro" class="btn btn-lg btn-toggle"
            data-toggle="button" aria-pressed="false" autocomplete="off">
            <div class="handle"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div id="fsGastro" style="display:none">
    <div class="panel with-nav-tabs panel-default">
      <div class="panel-heading">
        <?php $gastro_tipo = isset($pp_gastro_tipo) ? $pp_gastro_tipo : null; ?>
        <ul class="nav nav-tabs">
          @if($gastro_tipo != 'alta')
          <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
          @endif
          @if($gastro_tipo != 'baja')
          <li class="{{ $gastro_tipo == 'alta' ? 'active' : '' }}"><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
          @endif
        </ul>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div class="tab-pane fade {{ $gastro_tipo == 'alta' ? '' : 'in active' }}" id="tab_baja">
            <fieldset>
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-list-search" id="tblGastroBaja" cellspacing="0" width="100%" style="margin-top:5px;">
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <tr>
                          <td width="15%"><b>INDICACIONES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_indicaciones" id="baja_indicaciones" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>MEDICACION:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_medicacion" id="baja_medicacion" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>INSPECCION:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="10%"><b>Estatica:</b></td>
                          <td width="65%" colspan="6">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_estatica" id="baja_estatica" cols="30" rows="2"></textarea>
                          </td>
                          <td width="10%"></td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="10%"><b>Dinamica:</b></td>
                          <td width="65%" colspan="6">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_dinamica" id="baja_dinamica" cols="30" rows="2"></textarea>
                          </td>
                          <td width="10%"></td>
                      </tr>
                      <tr>
                          <td width="15%"></td>
                          <td width="10%"><b>Tacto Rectal:</b></td>
                          <td width="65%" colspan="6">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_tacto_rectal" id="baja_tacto_rectal" cols="30" rows="2"></textarea>
                          </td>
                          <td width="10%"></td>
                      </tr>
                      <tr>
                          <td width="15%"><b>PREPARACION:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_preparacion" id="baja_preparacion" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="20%"><b>ESCALA DE BOSTON:</b></td>
                          <td width="10%">CD</td>
                          <td width="10%">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_cd" id="baja_escala_cd" cols="30" rows="1"></textarea>
                          </td>
                          <td width="10%">CT</td>
                          <td width="10%">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_ct" id="baja_escala_ct" rows="1"></textarea>
                          </td>
                          <td width="10%">CI</td>
                          <td width="10%">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_ci" id="baja_escala_ci" rows="1"></textarea>
                          </td>
                          <td width="10%">TOTAL</td>
                          <td width="10%">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_total" id="baja_escala_total" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>INSERCION:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_insercion" id="baja_insercion" rows="2"></textarea>
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
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_canal_anal" id="baja_canal_anal" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>RECTO:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_recto" id="baja_recto" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>SIGMOIDES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_sigmoide" id="baja_sigmoide" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>DESCENDENTE:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_descendente" id="baja_descendente" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Transverso:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_transverso" id="baja_transverso" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Ascendente:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_ascendente" id="baja_ascendente" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Ciego:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_ciego" id="baja_ciego" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>COMPLICACIONES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_complicaciones" id="baja_complicaciones" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>BIOPSIA:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_biopsia" id="baja_biopsia" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>OBSERVACIONES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_observaciones" id="baja_observaciones" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="50%" colspan="4"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                          <td width="50%" colspan="5">
                          </td>
                      </tr>
                      <tr>
                          <td width="100%" colspan="9">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_diag_endoscopico" id="baja_diag_endoscopico" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="tab-pane fade {{ $gastro_tipo == 'alta' ? 'in active' : '' }}" id="tab_alta">
            <fieldset>
              <div class="panel-body">
                <div class="row">
                  <table class="table table-striped table-list-search" id="tblGastroAlta" cellspacing="0" width="100%" style="margin-top:5px;">
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <tr>
                          <td width="15%"><b>INDICACIONES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_indicaciones" id="alta_indicaciones" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>MEDICACION:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_medicacion" id="alta_medicacion" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>HALLAZGOS:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>ESOFAGO:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_esofago" id="alta_esofago" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>ESTOMAGO:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Cardias:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_cardias" id="alta_cardias" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Fondo:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_fondo" id="alta_fondo" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Cuerpo:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_cuerpo" id="alta_cuerpo" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Antro:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_antro" id="alta_antro" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>DUODENO:</b></td>
                          <td width="85%" colspan="8">
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Bulbo:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_bulbo" id="alta_bulbo" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>Segunda porción:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_seg_porcion" id="alta_seg_porcion" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>BIOPSIA:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_biopsia" id="alta_biopsia" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>COMPLICACIONES</b></td>
                          <td width="85%" colspan="4">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_complicaciones" id="alta_complicaciones" cols="30" rows="1"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"><b>OBSERVACIONES:</b></td>
                          <td width="85%" colspan="8">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_observaciones" id="alta_observaciones" cols="30" rows="2"></textarea>
                          </td>
                      </tr>
                      <tr>
                          <td width="50%" colspan="3"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                          <td width="50%" colspan="6">
                          </td>
                      </tr>
                      <tr>
                          <td width="100%" colspan="9">
                            <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_diag_endoscopico" id="alta_diag_endoscopico" cols="30" rows="2"></textarea>
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
</fieldset>
