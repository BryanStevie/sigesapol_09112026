{{-- REGISTRAR SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS INICIO --}}
<div class="modal fade" id="modalSolicitudCitoquimicosLiquidosBiologicos" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_citoquimicos_liquidos_biologicos_data_id" id="solicitud_citoquimicos_liquidos_biologicos_data_id">
                <div class="row" style="display: flex; gap: 0.2rem; margin: 0 0.3rem;">
                  <div class="panel panel-info col-sm-6" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">ANTECEDENTES PATOLÓGICOS PERSONALES</h3>
                    </div>
                    <div class="panel-body" style="padding: 1rem;">
                      <div class="col-sm-12 formatearDiv1">
                        <textarea class="form-control" tabindex="57" rows="5" id="antecedentes_patologicos_personales" name="antecedentes_patologicos_personales" placeholder="Antecedentes Patológicos Personales"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-info col-sm-6" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">ANTECEDENTES PATOLÓGICOS FAMILIARES</h3>
                    </div>
                    <div class="panel-body" style="padding: 1rem;">
                        <div class="col-sm-12 formatearDiv1">
                        <textarea class="form-control" tabindex="57" rows="5" id="antecedentes_patologicos_familiares" name="antecedentes_patologicos_familiares" placeholder="Antecedentes Patológicos Familiares"></textarea>
                        </div>
                    </div>
                  </div>
                </div> 
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">SOLICITUD DE EXAMEN CITOQUÍMICO DE LÍQUIDO BIOLÓGICO</h3>
                    </div>
                    <div class="panel-body"> 
                        <div class="col-sm-6 contenedor-colum">
                            <div style="display: flex; gap: 0.5rem;">
                                <div class="col-sm-10 formatearDiv1">
                                    <label>Líquido Cefaloraquídeo (LCR)</label>
                                </div>
                                <div class="col-sm-2 formatearDiv1">
                                    <input type="checkbox" name="liquido_cefaloraquideo" id="liquido_cefaloraquideo" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos"/>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <div class="col-sm-10 formatearDiv1">
                                    <label>Líquido Pleural </label>
                                </div>
                                <div class="col-sm-2 formatearDiv1">
                                    <input type="checkbox" name="liquido_pleural" id="liquido_pleural" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos"/>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <div class="col-sm-10 formatearDiv1">
                                    <label>Líquido Ascitico </label>
                                </div>
                                <div class="col-sm-2 formatearDiv1">
                                    <input type="checkbox" name="liquido_ascitico" id="liquido_ascitico" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 contenedor-colum">
                            <div style="display: flex; gap: 0.5rem;">
                                <div class="col-sm-10 formatearDiv1">
                                    <label>Líquido Sinovial</label>
                                </div>
                                <div class="col-sm-2 formatearDiv1">
                                    <input type="checkbox" name="liquido_sinovial" id="liquido_sinovial" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos"/>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <div class="col-sm-10 formatearDiv1">
                                    <label>Otro</label>
                                </div>
                                <div class="col-sm-2 formatearDiv1">
                                    <input type="checkbox" name="liquido_otro" id="liquido_otro" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos"/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" name="liquido_otro_especifique" id="liquido_otro_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="panel panel-info">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">NÚMERO DE FRASCO DE MUESTRA</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>N° de Frasco de Muestra</label>
                        </div>
                        <div class="col-sm-6">
                            <select id="num_frasco_muestra_opcion" name="num_frasco_muestra_opcion" class="form-control" style="width:100%;">
                                <option value="0">-Seleccione-</option>
                                <option value="1">1ero</option>
                                <option value="2">2do</option>
                                <option value="3">3ero</option>
                            </select>
                        </div>
                    </div>
                  </div>
                </div>  
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarRegistroSolicitudCitoquimicosLiquidosBiologicos" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>