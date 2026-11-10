{{-- VER SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO INICIO --}}
<div class="modal fade" id="modalSolicitudHemocultivoAdulto" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">VER SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_hemocultivo_adulto_data_id" id="solicitud_hemocultivo_adulto_data_id">
                <div class="row" style="margin: 0 0.3rem;">
                  <div class="panel panel-info" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">TIEMPO DE ENFERMEDAD</h3>
                    </div>
                    <div class="panel-body">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Fecha</label>
                      </div>
                      <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                        <input type="text" style="text-align: center;" name="adulto_fecha_tiempo_enfermedad" id="adulto_fecha_tiempo_enfermedad" class="form-control" value="" readonly />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: flex; gap: 0.2rem; margin: 0 0.3rem;">
                  <div class="panel panel-info col-sm-3" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">ESCALA DE GLASGOW</h3>
                    </div>
                    <div class="panel-body" style="padding: 1rem;">
                      <div class="col-sm-8 formatearDiv1">
                        <label>Respuesta Ocular</label>
                      </div>
                      <div class="col-sm-4 formatearDiv1">
                        <input type="text" name="adulto_respuesta_ocular" id="adulto_respuesta_ocular" class="form-control" value="" readonly />
                      </div>
                      <div class="col-sm-12 c-e-m-1">
                        <span id="error_mensaje" class="error-mensaje-1 error-mensaje"></span>
                      </div>
                      <div class="col-sm-8 formatearDiv1">
                        <label>Respuesta Verbal</label>
                      </div>
                      <div class="col-sm-4 formatearDiv1">
                        <input type="text" name="adulto_respuesta_verbal" id="adulto_respuesta_verbal" class="form-control" value="" readonly />
                      </div>
                      <div class="col-sm-12 c-e-m-2">
                        <span id="error_mensaje" class="error-mensaje-2 error-mensaje"></span>
                      </div>
                      <div class="col-sm-8 formatearDiv1">
                        <label>Respuesta Motora</label>
                      </div>
                      <div class="col-sm-4 formatearDiv1">
                        <input type="text" name="adulto_respuesta_motora" id="adulto_respuesta_motora" class="form-control" value="" readonly />
                      </div>
                      <div class="col-sm-12 c-e-m-3">
                        <span id="error_mensaje" class="error-mensaje-3 error-mensaje"></span>
                      </div>
                      <div class="col-sm-8 formatearDiv1" >
                        <label>Total</label>
                      </div>
                      <div class="col-sm-4 formatearDiv1">
                        <input readonly type="text" name="adulto_total_escala_glasgow" id="adulto_total_escala_glasgow" class="form-control" value="" />
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-info col-sm-4" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">SIGNOS VITALES</h3>
                    </div>
                    <div class="panel-body" style="padding: 1rem;">
                      <div class="col-sm-12" style="padding: 0;">
                        <div class="col-sm-6 formatearDiv1">
                          <label>Presión Arterial</label>
                        </div>
                        <div class="col-sm-2 formatearDiv1">
                          <input type="text" name="adulto_presion_arterial_x" id="adulto_presion_arterial_x" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-2 formatearDiv1" style="text-align: center;">
                          /
                        </div>
                        <div class="col-sm-2 formatearDiv1">
                          <input type="text" name="adulto_presion_arterial_y" id="adulto_presion_arterial_y" class="form-control" value="" readonly />
                        </div>
                      </div>
                      <div class="col-sm-12 c-s-v-1">
                        <span id="error_mensaje" class="error-mensaje-4 error-mensaje"></span>
                      </div>
                      <div class="col-sm-12" style="padding: 0;">
                        <div class="col-sm-6 formatearDiv1">
                          <label>Frecuencia Cardiaca</label>
                        </div>
                        <div class="col-sm-6 formatearDiv1">
                          <input type="text" name="adulto_frecuencia_cardiaca" id="adulto_frecuencia_cardiaca" class="form-control" value="" readonly />
                        </div>
                      </div>
                      <div class="col-sm-12 c-s-v-2">
                        <span id="error_mensaje" class="error-mensaje-5 error-mensaje"></span>
                      </div>
                      <div class="col-sm-12" style="padding: 0;">
                        <div class="col-sm-6 formatearDiv1">
                          <label>Frecuencia Respiratoria</label>
                        </div>
                        <div class="col-sm-6 formatearDiv1">
                          <input type="text" name="adulto_frecuencia_respiratorio" id="adulto_frecuencia_respiratorio" class="form-control" value="" readonly />
                        </div>
                      </div>
                      <div class="col-sm-12 c-s-v-3">
                        <span id="error_mensaje" class="error-mensaje-6 error-mensaje"></span>
                      </div>
                      <div class="col-sm-12" style="padding: 0;">
                        <div class="col-sm-6 formatearDiv1">
                          <label>Temperatura</label>
                        </div>
                        <div class="col-sm-6 formatearDiv1">
                          <input type="text" name="adulto_temperatura" id="adulto_temperatura" class="form-control" value="" readonly />
                        </div> 
                      </div>
                      <div class="col-sm-12 c-s-v-4">
                        <span id="error_mensaje" class="error-mensaje-7 error-mensaje"></span>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-info col-sm-5" style="padding: 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">EXÁMENES DE LABORATORIO</h3>
                    </div>
                    <div class="panel-body" style="padding: 1rem;">
                      <div class="col-sm-6" style="padding: 0 2rem 0 0;">
                        <div class="col-sm-8 formatearDiv1">
                          <label>Creatinina</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_creatinina" id="adulto_creatinina" class="form-control" value=""  readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-1">
                          <span id="error_mensaje" class="error-mensaje-8 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>Diuresis</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_diuresis" id="adulto_diuresis" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-2">
                          <span id="error_mensaje" class="error-mensaje-9 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>Procalcitonina</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_procalcitonina" id="adulto_procalcitonina" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-3">
                          <span id="error_mensaje" class="error-mensaje-10 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>PCR</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_pcr" id="adulto_pcr" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-4">
                          <span id="error_mensaje" class="error-mensaje-11 error-mensaje"></span>
                        </div>
                      </div>
                      <div class="col-sm-6" style="padding: 0;">
                        <div class="col-sm-8 formatearDiv1">
                          <label>PAO2/FiO2</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_fiO2" id="adulto_fiO2" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-5">
                          <span id="error_mensaje" class="error-mensaje-12 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>Leucocitos</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_leucocitos" id="adulto_leucocitos" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-6">
                          <span id="error_mensaje" class="error-mensaje-13 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>Plaquetas</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_plaquetas" id="adulto_plaquetas" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-7">
                          <span id="error_mensaje" class="error-mensaje-14 error-mensaje"></span>
                        </div>
                        <div class="col-sm-8 formatearDiv1">
                          <label>Bilirrubina</label>
                        </div>
                        <div class="col-sm-4 formatearDiv1">
                          <input type="text" name="adulto_bilirrubina" id="adulto_bilirrubina" class="form-control" value="" readonly />
                        </div>
                        <div class="col-sm-12 c-e-l-8">
                          <span id="error_mensaje" class="error-mensaje-15 error-mensaje"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">ANTECEDENTES PATOLÓGICOS</h3>
                    </div>
                    <div class="panel-body"> 
                        <div class="col-sm-4 contenedor-colum">
                          <div class="col-sm-12 contenedor-1">
                            <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                              <label>Infarto de miocardio (IMA)</label>
                            </div>
                            <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="adulto_ima" id="adulto_ima" class="form-control" value="" maxlength="" readonly />
                            </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                            <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                              <label>Insuficiencia cardiaca congestiva (ICC)</label>
                            </div>
                            <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="adulto_icc" id="adulto_icc" class="form-control" value="" maxlength="" readonly />
                            </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Enfermedad vascular periferica (EVP)</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_evp" id="adulto_evp" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Enfermedad cerebro vascular (ECV)</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_ecv" id="adulto_ecv" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Demencia</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_demencia" id="adulto_demencia" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Dibetes Mellitus (DM)</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_dm" id="adulto_dm" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4 contenedor-colum">
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Diabetes con lesión orgánica</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_diabetes_lesion_organica" id="adulto_diabetes_lesion_organica" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Hemiplejía</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_hemiplejia" id="adulto_hemiplejia" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Enfermedad Pulmonar Crónica (EPOC)</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_epoc" id="adulto_epoc" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Patología del tejido conectivo</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_patologia_tejido_conectivo" id="adulto_patologia_tejido_conectivo" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Enfermedad ulcerosa</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_enfermedad_ulcerosa" id="adulto_enfermedad_ulcerosa" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Patología renal moderada o grave</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_patologia_renal_moderada_o_grave" id="adulto_patologia_renal_moderada_o_grave" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4 contenedor-colum">
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Neoplasias</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_neoplasias" id="adulto_neoplasias" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12">
                            <input type="text" name="adulto_neoplasias_especifique" id="adulto_neoplasias_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly />
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Leucemias</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_leucemias" id="adulto_leucemias" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12">
                            <input type="text" name="adulto_leucemias_especifique" id="adulto_leucemias_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly />
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Linfomas malignos</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_linfomas_malignos" id="adulto_linfomas_malignos" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Metástasis Cólida</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_metastasis_colida" id="adulto_metastasis_colida" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>SIDA</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_sida" id="adulto_sida" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Otros</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_antecedentes_patologicos_otros_1" id="adulto_antecedentes_patologicos_otros_1" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12">
                            <input type="text" name="adulto_antecedentes_patologicos_otros_1_especifique" id="adulto_antecedentes_patologicos_otros_1_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly />
                          </div>
                          <div class="col-sm-12 contenedor-1">
                              <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Otros</label>
                              </div>
                              <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="adulto_antecedentes_patologicos_otros_2" id="adulto_antecedentes_patologicos_otros_2" class="form-control" value="" maxlength="" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12">
                            <input type="text" name="adulto_antecedentes_patologicos_otros_2_especifique" id="adulto_antecedentes_patologicos_otros_2_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly />
                          </div>
                        </div>
                    </div>
                </div>  
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">TERAPIA ANTIMICROBIANA</h3>
                    </div>
                    <div class="panel-body">
                      <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Se inició antes de la recolección de muestras de Hemocultivo</label>
                      </div>
                      <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                        <input type="text" name="adulto_terapia_antimicrobiana" id="adulto_terapia_antimicrobiana" class="form-control" value="" maxlength="" readonly />
                      </div>
                    </div>
                </div>  
                <div class="panel panel-info">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">SOLICITUD DE FRASCO DE HEMOCULTIVO</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-sm-12 contenedor-colum">
                      <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>*Nota: La extracción de muestra debe de contener un volumen minimo de 5ml, siendo ideal 10 ml por cada frasco, las tomas deben realizarce de sitios diferentes.</label>
                      </div>
                      <div class="row">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>Frasco N°1</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <input type="text" name="adulto_frasco_1" id="adulto_frasco_1" class="form-control" value="" maxlength="" readonly />
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_1_opcion_1" name="adulto_frasco_1_opcion_1" class="form-control" style="display: none; width:100%;" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Catéter Periférico</option>
                            <option value="2">Catéter Transcateter</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_1_opcion_2" name="adulto_frasco_1_opcion_2" class="form-control" style="display: none; width:100%" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Brazo Izquierda</option>
                            <option value="2">Brazo Derecho</option>
                            <option value="3">Pierna Izquierda</option>
                            <option value="4">Pierna Derecho</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>Frasco N°2</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <input type="text" name="adulto_frasco_2" id="adulto_frasco_2" class="form-control" value="" maxlength="" readonly />
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_2_opcion_1" name="adulto_frasco_2_opcion_1" class="form-control" style="display: none; width:100%" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Catéter Periférico</option>
                            <option value="2">Catéter Transcateter</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_2_opcion_2" name="adulto_frasco_2_opcion_2" class="form-control" style="display: none;width:100%" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Brazo Izquierda</option>
                            <option value="2">Brazo Derecho</option>
                            <option value="3">Pierna Izquierda</option>
                            <option value="4">Pierna Derecho</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>Frasco N°3</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <input type="text" name="adulto_frasco_3" id="adulto_frasco_3" class="form-control" value="" maxlength="" readonly />
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_3_opcion_1" name="adulto_frasco_3_opcion_1" class="form-control" style="display: none; width:100%" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Catéter Periférico</option>
                            <option value="2">Catéter Transcateter</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <select id="adulto_frasco_3_opcion_2" name="adulto_frasco_3_opcion_2" class="form-control" style="display: none; width:100%" disabled>
                            <option value="0">-Seleccione-</option>
                            <option value="1">Brazo Izquierda</option>
                            <option value="2">Brazo Derecho</option>
                            <option value="3">Pierna Izquierda</option>
                            <option value="4">Pierna Derecho</option>
                          </select>
                        </div>
                      </div>
                      <div class="row"></div>
                      <div class="row"></div>
                      <div class="row">
                        <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                          <label>¿Este hemocultivo es  de seguimiento y vigilancia?</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                          <input type="text" name="adulto_hemocultivo_seguimiento_y_vigilancia" id="adulto_hemocultivo_seguimiento_y_vigilancia" class="form-control" value="" maxlength="" readonly />
                        </div>
                        <div class="col-sm-6" id="adulto_contenedor_fecha_primer_resultado_hemocultivo" style="display: none">
                          <div class="col-sm-7">
                            <label>Fecha del primer resultado de hemocultivo:</label>
                          </div>
                          <div class="col-sm-5">
                            <input type="text" style="text-align: center;" name="adulto_fecha_primer_resultado_hemocultivo" id="adulto_fecha_primer_resultado_hemocultivo" class="form-control" value="" readonly />
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>*Nota: No debe repetirse durante 2 o 5 días después de obtener los resultados del primer hemocultivo.</label>
                      </div>
                    </div>
                  </div>
                </div>  
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- VER SOLICITUD DE EXAMEN PARA HEMOCULTIVO NEONATO Y PEDRIATRÍA --}}
<div class="modal fade" id="modalSolicitudHemocultivoPedriatria" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header custom-header-cambiar-estado">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h5 class="modal-title" style="font-size: 2rem">SOLICITUD DE EXAMEN PARA HEMOCULTIVO NEONATO Y PEDRIATRÍA<span id="nropresmodal"></span></h5>
          </div>
          <div class="modal-body">
              <input type="hidden" name="solicitud_hemocultivo_pedriatria_data_id" id="solicitud_hemocultivo_pedriatria_data_id">
              <div class="row" style="margin: 0 0.3rem;">
                <div class="panel panel-info" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">TIEMPO DE ENFERMEDAD</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>Fecha</label>
                    </div>
                    <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                      <input type="text" name="pedriatria_fecha_tiempo_enfermedad" id="pedriatria_fecha_tiempo_enfermedad" class="form-control"  readonly/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="display: flex; gap: 0.2rem; margin: 0 0.3rem;">
                <div class="panel panel-info col-sm-3" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">EDAD GESTIONAL</h3>
                  </div>
                  <div class="panel-body" style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                      <div class="col-sm-10 formatearDiv1">
                        <label>Postérmino: 42 semanas a más</label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="checkbox" disabled name="pedriatria_postermino" id="pedriatria_postermino" value="" class="grupo_checkbox_edad_gestional"/>
                      </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                      <div class="col-sm-10 formatearDiv1">
                        <label>A término: 37 a 41 semanas</label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="checkbox" disabled name="pedriatria_termino" id="pedriatria_termino" value="" class="grupo_checkbox_edad_gestional"/>
                      </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                      <div class="col-sm-10 formatearDiv1">
                        <label>Prematuro leve: 35 a 36 semanas</label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="checkbox" name="pedriatria_prematuro_leve" id="pedriatria_prematuro_leve" value="" class="grupo_checkbox_edad_gestional" disabled/>
                      </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                      <div class="col-sm-10 formatearDiv1">
                        <label>Prematuro moderado: 32 a 34 semanas</label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="checkbox" name="pedriatria_prematuro_moderado" id="pedriatria_prematuro_moderado" value="" class="grupo_checkbox_edad_gestional" disabled/>
                      </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                      <div class="col-sm-10 formatearDiv1">
                        <label>Prematuro extremo: menos de 32 semanas </label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="checkbox" name="pedriatria_prematuro_extremo" id="pedriatria_prematuro_extremo" value="" class="grupo_checkbox_edad_gestional" disabled/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel panel-info col-sm-3" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">APGAR</h3>
                  </div>
                  <div class="panel-body" style="padding: 1rem;">
                    <div class="col-sm-8 formatearDiv1">
                      <label>Apariencia</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_apariencia" id="pedriatria_apariencia" class="form-control" value=""  readonly/>
                    </div>
                    <div class="col-sm-12 c-p-a-1">
                      <span id="error_mensaje" class="error-mensaje-16 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1">
                      <label>Pulso</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_pulso" id="pedriatria_pulso" class="form-control" value=""  readonly/>
                    </div>
                    <div class="col-sm-12 c-p-a-2">
                      <span id="error_mensaje" class="error-mensaje-17 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1">
                      <label>Gesto</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_gesto" id="pedriatria_gesto" class="form-control" value=""  readonly/>
                    </div>
                    <div class="col-sm-12 c-p-a-3">
                      <span id="error_mensaje" class="error-mensaje-18 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1">
                      <label>Actividad respiratoria</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_actividad_respiratoria" id="pedriatria_actividad_respiratoria" class="form-control" value=""  readonly/>
                    </div>
                    <div class="col-sm-12 c-p-a-4">
                      <span id="error_mensaje" class="error-mensaje-19 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1" >
                      <label>Total</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input readonly type="text" name="pedriatria_total_apgar" id="pedriatria_total_apgar" class="form-control" value=""  readonly/>
                    </div>
                  </div>
                </div>
                <div class="panel panel-info col-sm-3" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">ESCALA DE GLASGOW</h3>
                  </div>
                  <div class="panel-body" style="padding: 1rem;">
                    <div class="col-sm-8 formatearDiv1">
                      <label>Respuesta Ocular</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_respuesta_ocular" id="pedriatria_respuesta_ocular" class="form-control" value=""  readonly/>
                    </div>
                    <div class="col-sm-12 c-p-e-g-1">
                      <span id="error_mensaje" class="error-mensaje-20 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1">
                      <label>Respuesta Verbal</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_respuesta_verbal" id="pedriatria_respuesta_verbal" class="form-control" value="" readonly/>
                    </div>
                    <div class="col-sm-12 c-p-e-g-2">
                      <span id="error_mensaje" class="error-mensaje-21 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1">
                      <label>Respuesta Motora</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input type="text" name="pedriatria_respuesta_motora" id="pedriatria_respuesta_motora" class="form-control" value="" readonly/>
                    </div>
                    <div class="col-sm-12 c-p-e-g-3">
                      <span id="error_mensaje" class="error-mensaje-22 error-mensaje"></span>
                    </div>
                    <div class="col-sm-8 formatearDiv1" >
                      <label>Total</label>
                    </div>
                    <div class="col-sm-4 formatearDiv1">
                      <input readonly type="text" name="pedriatria_total_escala_glasgow" id="pedriatria_total_escala_glasgow" class="form-control" readonly />
                    </div>
                  </div>
                </div>
                <div class="panel panel-info col-sm-3" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">SIGNOS VITALES</h3>
                  </div>
                  <div class="panel-body" style="padding: 1rem;">
                    <div class="col-sm-12" style="padding: 0;">
                      <div class="col-sm-6 formatearDiv1">
                        <label>Presión Arterial</label>
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="text" name="pedriatria_presion_arterial_x" id="pedriatria_presion_arterial_x" class="form-control" readonly/>
                      </div>
                      <div class="col-sm-2 formatearDiv1" style="text-align: center;">
                        /
                      </div>
                      <div class="col-sm-2 formatearDiv1">
                        <input type="text" name="pedriatria_presion_arterial_y" id="pedriatria_presion_arterial_y" class="form-control" readonly/>
                      </div>
                    </div>
                    <div class="col-sm-12 c-p-s-v-1">
                      <span id="error_mensaje" class="error-mensaje-23 error-mensaje"></span>
                    </div>
                    <div class="col-sm-12" style="padding: 0;">
                      <div class="col-sm-6 formatearDiv1">
                        <label>Frecuencia Cardiaca</label>
                      </div>
                      <div class="col-sm-6 formatearDiv1">
                        <input type="text" name="pedriatria_frecuencia_cardiaca" id="pedriatria_frecuencia_cardiaca" class="form-control" readonly/>
                      </div>
                    </div>
                    <div class="col-sm-12 c-p-s-v-2">
                      <span id="error_mensaje" class="error-mensaje-24 error-mensaje"></span>
                    </div>
                    <div class="col-sm-12" style="padding: 0;">
                      <div class="col-sm-6 formatearDiv1">
                        <label>Frecuencia Respiratoria</label>
                      </div>
                      <div class="col-sm-6 formatearDiv1">
                        <input type="text" name="pedriatria_frecuencia_respiratorio" id="pedriatria_frecuencia_respiratorio" class="form-control" readonly/>
                      </div>
                    </div>
                    <div class="col-sm-12 c-p-s-v-3">
                      <span id="error_mensaje" class="error-mensaje-25 error-mensaje"></span>
                    </div>
                    <div class="col-sm-12" style="padding: 0;">
                      <div class="col-sm-6 formatearDiv1">
                        <label>Temperatura</label>
                      </div>
                      <div class="col-sm-6 formatearDiv1">
                        <input type="text" name="pedriatria_temperatura" id="pedriatria_temperatura" class="form-control" placeholder="00.00" readonly/>
                      </div> 
                    </div>
                    <div class="col-sm-12 c-p-s-v-4">
                      <span id="error_mensaje" class="error-mensaje-26 error-mensaje"></span>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="panel panel-info">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">EXÁMENES DE LABORATORIO</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-sm-12" style="padding: 0;">
                      <div class="col-sm-4 contenedor-colum">
                        <div class="col-sm-12" style="padding: 0 2rem 0 0;">
                          <div class="col-sm-8 formatearDiv1">
                            <label>Creatinina</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_creatinina" id="pedriatria_creatinina" class="form-control" placeholder="00.00" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-1">
                            <span id="error_mensaje" class="error-mensaje-27 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>Diuresis</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_diuresis" id="pedriatria_diuresis" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-2">
                            <span id="error_mensaje" class="error-mensaje-28 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>Procalcitonina</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_procalcitonina" id="pedriatria_procalcitonina" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-3">
                            <span id="error_mensaje" class="error-mensaje-29 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>PCR</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_pcr" id="pedriatria_pcr" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-4">
                            <span id="error_mensaje" class="error-mensaje-30 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>PAO2/FiO2</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_fiO2" id="pedriatria_fiO2" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-5">
                            <span id="error_mensaje" class="error-mensaje-31 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>Leucocitos</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_leucocitos" id="pedriatria_leucocitos" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-6">
                            <span id="error_mensaje" class="error-mensaje-32 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>Plaquetas</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_plaquetas" id="pedriatria_plaquetas" class="form-control" readonly />
                          </div>
                          <div class="col-sm-12 c-p-e-l-7">
                            <span id="error_mensaje" class="error-mensaje-33 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>Bilirrubina</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_bilirrubina" id="pedriatria_bilirrubina" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-8">
                            <span id="error_mensaje" class="error-mensaje-34 error-mensaje"></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-4 contenedor-colum">
                        <div class="col-sm-12" style="padding: 0 2rem 0 0;">
                          <div class="col-sm-8 formatearDiv1">
                            <label>Glucosa</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_glucosa" id="pedriatria_glucosa" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-9">
                            <span id="error_mensaje" class="error-mensaje-35 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>ALT</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_alt" id="pedriatria_alt" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-10">
                            <span id="error_mensaje" class="error-mensaje-36 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>VCG</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_vcg" id="pedriatria_vcg" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-11">
                            <span id="error_mensaje" class="error-mensaje-37 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>TP</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_tp" id="pedriatria_tp" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-12">
                            <span id="error_mensaje" class="error-mensaje-38 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>SODIO</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_sodio" id="pedriatria_sodio" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-13">
                            <span id="error_mensaje" class="error-mensaje-39 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>POTASIO</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_potasio" id="pedriatria_potasio" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-8">
                            <span id="error_mensaje" class="error-mensaje-40 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>CALCIO</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_calcio" id="pedriatria_calcio" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-8">
                            <span id="error_mensaje" class="error-mensaje-41 error-mensaje"></span>
                          </div>
                          <div class="col-sm-8 formatearDiv1">
                            <label>PCO2</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_pco2" id="pedriatria_pco2" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-8">
                            <span id="error_mensaje" class="error-mensaje-42 error-mensaje"></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-4 contenedor-colum">
                        <div class="col-sm-12" style="padding: 0 2rem 0 0;">
                          <div class="col-sm-8 formatearDiv1">
                            <label>TPTA</label>
                          </div>
                          <div class="col-sm-4 formatearDiv1">
                            <input type="text" name="pedriatria_tpta" id="pedriatria_tpta" class="form-control" readonly/>
                          </div>
                          <div class="col-sm-12 c-p-e-l-8">
                            <span id="error_mensaje" class="error-mensaje-43 error-mensaje"></span>
                          </div>
                          <div class="col-sm-12" style="padding: 0;">
                              <div class="col-sm-8" style="padding-left: 0; padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Otros</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <input type="text" name="pedriatria_examen_laboratorio_otros" id="pedriatria_examen_laboratorio_otros" class="form-control" readonly />
                              </div>
                          </div>
                          <div class="col-sm-12">
                            <input type="text" name="pedriatria_examen_laboratorio_otros_especifique" id="pedriatria_examen_laboratorio_otros_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>  
              <div class="panel panel-info">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">TERAPIA ANTIMICROBIANA</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-sm-8" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>Se inició antes de la recolección de muestras de Hemocultivo</label>
                    </div>
                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                      <input type="text" name="pedriatria_terapia_antimicrobiana" id="pedriatria_terapia_antimicrobiana" class="form-control" readonly />
                    </div>
                  </div>
              </div>  
              <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title size-titulo">SOLICITUD DE FRASCO DE HEMOCULTIVO</h3>
                </div>
                <div class="panel-body">
                  <div class="col-sm-12 contenedor-colum">
                    <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>*Nota: La extracción de muestra debe de contener un volumen en: neonatos de 0.5 a 1 ml, lactantes de 1 a 2 ml y niños de 1.5 ml.</label>
                    </div>
                    <div class="row">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Frasco N°1</label>
                      </div>
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                        <input type="text" name="pedriatria_frasco_1" id="pedriatria_frasco_1" class="form-control" readonly />
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_1_opcion_1" name="pedriatria_frasco_1_opcion_1" class="form-control" style="display: none; width:100%;" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Catéter Periférico</option>
                          <option value="2">Catéter Transcateter</option>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_1_opcion_2" name="pedriatria_frasco_1_opcion_2" class="form-control" style="display: none; width:100%" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Brazo Izquierda</option>
                          <option value="2">Brazo Derecho</option>
                          <option value="3">Pierna Izquierda</option>
                          <option value="4">Pierna Derecho</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Frasco N°2</label>
                      </div>
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                        <input type="text" name="pedriatria_frasco_2" id="pedriatria_frasco_2" class="form-control" readonly />
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_2_opcion_1" name="pedriatria_frasco_2_opcion_1" class="form-control" style="display: none; width:100%" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Catéter Periférico</option>
                          <option value="2">Catéter Transcateter</option>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_2_opcion_2" name="pedriatria_frasco_2_opcion_2" class="form-control" style="display: none;width:100%" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Brazo Izquierda</option>
                          <option value="2">Brazo Derecho</option>
                          <option value="3">Pierna Izquierda</option>
                          <option value="4">Pierna Derecho</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Frasco N°3</label>
                      </div>
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                        <input type="text" name="pedriatria_frasco_3" id="pedriatria_frasco_3" class="form-control" readonly />
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_3_opcion_1" name="pedriatria_frasco_3_opcion_1" class="form-control" style="display: none; width:100%" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Catéter Periférico</option>
                          <option value="2">Catéter Transcateter</option>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select id="pedriatria_frasco_3_opcion_2" name="pedriatria_frasco_3_opcion_2" class="form-control" style="display: none; width:100%" disabled>
                          <option value="0">-Seleccione-</option>
                          <option value="1">Brazo Izquierda</option>
                          <option value="2">Brazo Derecho</option>
                          <option value="3">Pierna Izquierda</option>
                          <option value="4">Pierna Derecho</option>
                        </select>
                      </div>
                    </div>
                    <div class="row"></div>
                    <div class="row"></div>
                    <div class="row">
                      <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                        <label>¿Este hemocultivo es  de seguimiento y vigilancia?</label>
                      </div>
                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                        <input type="text" name="pedriatria_hemocultivo_seguimiento_y_vigilancia" id="pedriatria_hemocultivo_seguimiento_y_vigilancia" class="form-control" readonly />
                      </div>
                      <div class="col-sm-6" id="pedriatria_contenedor_fecha_primer_resultado_hemocultivo" style="display: none">
                        <div class="col-sm-7">
                          <label>Fecha del primer resultado de hemocultivo:</label>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" name="pedriatria_fecha_primer_resultado_hemocultivo" id="pedriatria_fecha_primer_resultado_hemocultivo" class="form-control" readonly/>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>*Nota: No debe repetirse durante 2 o 5 días después de obtener los resultados del primer hemocultivo.</label>
                    </div>
                  </div>
                </div>
              </div>  
          </div>
          <div class="modal-footer">
              <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
</div>

{{-- VER SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS INICIO --}}
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
                      <textarea class="form-control" tabindex="57" rows="5" id="antecedentes_patologicos_personales" name="antecedentes_patologicos_personales" placeholder="Antecedentes Patológicos Personales" readonly></textarea>
                    </div>
                  </div>
                </div>
                <div class="panel panel-info col-sm-6" style="padding: 0;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">ANTECEDENTES PATOLÓGICOS FAMILIARES</h3>
                  </div>
                  <div class="panel-body" style="padding: 1rem;">
                      <div class="col-sm-12 formatearDiv1">
                      <textarea class="form-control" tabindex="57" rows="5" id="antecedentes_patologicos_familiares" name="antecedentes_patologicos_familiares" placeholder="Antecedentes Patológicos Familiares" readonly></textarea>
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
                                  <input type="checkbox" name="liquido_cefaloraquideo" id="liquido_cefaloraquideo" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos" disabled/>
                              </div>
                          </div>
                          <div style="display: flex; gap: 0.5rem;">
                              <div class="col-sm-10 formatearDiv1">
                                  <label>Líquido Pleural </label>
                              </div>
                              <div class="col-sm-2 formatearDiv1">
                                  <input type="checkbox" name="liquido_pleural" id="liquido_pleural" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos" disabled/>
                              </div>
                          </div>
                          <div style="display: flex; gap: 0.5rem;">
                              <div class="col-sm-10 formatearDiv1">
                                  <label>Líquido Ascitico </label>
                              </div>
                              <div class="col-sm-2 formatearDiv1">
                                  <input type="checkbox" name="liquido_ascitico" id="liquido_ascitico" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos" disabled/>
                              </div>
                          </div>
                      </div>
                      <div class="col-sm-6 contenedor-colum">
                          <div style="display: flex; gap: 0.5rem;">
                              <div class="col-sm-10 formatearDiv1">
                                  <label>Líquido Sinovial</label>
                              </div>
                              <div class="col-sm-2 formatearDiv1">
                                  <input type="checkbox" name="liquido_sinovial" id="liquido_sinovial" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos" disabled/>
                              </div>
                          </div>
                          <div style="display: flex; gap: 0.5rem;">
                              <div class="col-sm-10 formatearDiv1">
                                  <label>Otro</label>
                              </div>
                              <div class="col-sm-2 formatearDiv1">
                                  <input type="checkbox" name="liquido_otro" id="liquido_otro" value="" class="grupo_checkbox_citoquimico_liquidos_biologicos" disabled/>
                              </div>
                          </div>
                          <div class="col-sm-12">
                              <input type="text" name="liquido_otro_especifique" id="liquido_otro_especifique" class="form-control" value="" placeholder="Especifique." style="display: none;" readonly/>
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
                          <select id="num_frasco_muestra_opcion" name="num_frasco_muestra_opcion" class="form-control" style="width:100%;" disabled>
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
              </div>
          </div>
      </div>
  </div>
</div>

{{-- VER SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL  INICIO --}}
<div class="modal fade" id="modalSolicitudTestToleranciaGlucosaOral" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header custom-header-cambiar-estado">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h5 class="modal-title" style="font-size: 2rem">TEST DE TOLERANCIA A LA GLUCOSA ORAL <span id="nropresmodal"></span></h5>
          </div>
          <div class="modal-body">
              <input type="hidden" name="solicitud_test_tolerancia_glucosa_oral_data_id" id="solicitud_test_tolerancia_glucosa_oral_data_id">
              
              <div class="panel panel-info" id="tolerancia_paciente_pediatrico" style="display: none;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO</h3>
                  </div>
                  <div class="panel-body"> 
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Paciente se a programado para Nutrición previo a la toma de TTGO</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_pediatrico_ttgo" id="tolerancia_paciente_pediatrico_ttgo" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_pediatrico_ttgo" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Fecha:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" name="fecha_tolerancia_paciente_pediatrico_ttgo" id="fecha_tolerancia_paciente_pediatrico_ttgo" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Procedimiento con glucosa anhidra</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_pediatrico_glucosa_anhidra" id="tolerancia_paciente_pediatrico_glucosa_anhidra" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Cantidad:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra" id="cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra" class="form-control" readonly/>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>  
              <div class="panel panel-info" id="tolerancia_paciente_adulto" style="display: none;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO</h3>
                  </div>
                  <div class="panel-body"> 
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Paciente se a programado para Nutrición previo a la toma de TTGO</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_adulto_ttgo" id="tolerancia_paciente_adulto_ttgo" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_adulto_ttgo"  style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Fecha:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" name="fecha_tolerancia_paciente_adulto_ttgo" id="fecha_tolerancia_paciente_adulto_ttgo" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Procedimiento con glucosa anhidra </label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_adulto_glucosa_anhidra" id="tolerancia_paciente_adulto_glucosa_anhidra" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_adulto_glucosa_anhidra" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Cantidad:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_adulto_glucosa_anhidra" id="cantidad_tolerancia_paciente_adulto_glucosa_anhidra" class="form-control" readonly/>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>  
              <div class="panel panel-info" id="tolerancia_paciente_gestante" style="display: none;">
                  <div class="panel-heading">
                      <h3 class="panel-title size-titulo">SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE</h3>
                  </div>
                  <div class="panel-body"> 
                      <div class="row">
                          <div class="col-sm-8">
                              <div class="col-sm-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                  <label>Fórmula Gestacional</label>
                              </div>
                              <div class="col-sm-3">
                                  <div class="col-sm-6">
                                      <label>G:</label>
                                  </div>
                                  <div class="col-sm-6">
                                      <input type="text" name="tolerancia_paciente_gestante_g" id="tolerancia_paciente_gestante_g" class="form-control" maxlength="1" readonly/>
                                  </div>
                              </div>
                              <div class="col-sm-6" style="display: flex; gap: 0.5rem;">
                                  <div class="col-sm-2">
                                      <label>P:</label>
                                  </div>
                                  <div class="col-sm-2" style="padding: 0;">
                                      <input type="text" name="tolerancia_paciente_gestante_p_1" id="tolerancia_paciente_gestante_p_1" class="form-control" maxlength="1" readonly />
                                  </div>
                                  <div class="col-sm-2" style="padding: 0;">
                                      <input type="text" name="tolerancia_paciente_gestante_p_2" id="tolerancia_paciente_gestante_p_2" class="form-control" maxlength="1" readonly />
                                  </div>
                                  <div class="col-sm-2" style="padding: 0;">
                                      <input type="text" name="tolerancia_paciente_gestante_p_3" id="tolerancia_paciente_gestante_p_3" class="form-control" maxlength="1" readonly />
                                  </div>
                                  <div class="col-sm-2" style="padding: 0;">
                                      <input type="text" name="tolerancia_paciente_gestante_p_4" id="tolerancia_paciente_gestante_p_4" class="form-control" maxlength="1" readonly />
                                  </div>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="col-sm-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                  <label>Edad gestacional</label>
                              </div>
                              <div class="col-sm-3">
                                  <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_meses" id="tolerancia_paciente_gestante_edad_gestacional_meses" class="form-control" readonly />
                              </div>
                              <div class="col-sm-3">
                                  <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_semanas" id="tolerancia_paciente_gestante_edad_gestacional_semanas" class="form-control" readonly />
                              </div>
                              <div class="col-sm-3">
                                  <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_dias" id="tolerancia_paciente_gestante_edad_gestacional_dias" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                      <div class="row"><br></div>
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Paciente se a programado para Nutrición previo a la toma de TTGO</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_gestante_ttgo" id="tolerancia_paciente_gestante_ttgo" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_ttgo" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Fecha:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" name="fecha_tolerancia_paciente_gestante_ttgo" id="fecha_tolerancia_paciente_gestante_ttgo" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                      <div class="row"><br></div>
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Procedimiento con glucosa anhidra</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_gestante_glucosa_anhidra" id="tolerancia_paciente_gestante_glucosa_anhidra" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_glucosa_anhidra" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Cantidad:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_gestante_glucosa_anhidra" id="cantidad_tolerancia_paciente_gestante_glucosa_anhidra" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                      <div class="row"><br></div>
                      <div class="row">
                          <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                              <label>Presenta factores de riesgo</label>
                          </div>
                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                              <input type="text" name="tolerancia_paciente_gestante_factores_riesgo" id="tolerancia_paciente_gestante_factores_riesgo" class="form-control" readonly />
                          </div>
                          <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_factores_riesgo" style="display: none;" >
                              <div class="col-sm-7">
                                  <label>Especifique:</label>
                              </div>
                              <div class="col-sm-5">
                                  <input type="text" placeholder="especifique." name="especifique_tolerancia_paciente_gestante_factores_riesgo" id="especifique_tolerancia_paciente_gestante_factores_riesgo" class="form-control" readonly />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>    
          </div>
          <div class="modal-footer">
              <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
</div>