<div class="modal fade" id="myModalAtencionEvaluacionAjax" role="dialog" style="z-index: 100000000000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION</h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion">
                    <div class="container" style="width:100%">
                        <div class="row">
                          <div class="col-sm-12 col-md-12">
                            <div class="row">
                              <div class="panel-body">
                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="col-sm-12 col-md-12">
                                    <fieldset>
                                      <div>
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                                            <p id="prest_tipoAtencion"></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label for="dni_beneficiario">FECHA DE ATENCION</label>
                                            <p id="prest_fechaAtencion"></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>N° HISTORIA CLINICA</label>
                                            <p id="prest_nroHistoria"></p>
                                          </div>
                                          <div class="col-md-3">
                                            <label for="dni_beneficiario">SERVICIO</label>
                                            <p id="prest_servicio"></p>
                                          </div>
                                          <div class="col-md-1">
                                            <label>CONSULTORIO</label>
                                            <p id="prest_consultorio"></p>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label for="tipo">NOMBRE DE LA IPRESS</label>
                                            <p id="prest_establecimiento"></p>
                                          </div>
                                          <div class="" id="swHandler" hidden>
                                              <div class="col-md-2">
                                                <label for="tipo">PABELLON</label><br/>
                                                <p id="datoAdicional_pabellon"></p>
                                              </div>
                                              <div class="col-md-2">
                                                <label for="dni_beneficiario">PISO</label><br/>
                                                <p id="datoAdicional_piso"></p>
                                              </div>
                                              <div class="col-md-3">
                                                <label>CUARTO</label><br/>
                                                <p id="datoAdicional_cuarto"></p>
                                              </div>
                                              <div class="col-md-1">
                                                <label>CAMA</label><br/>
                                                <p id="datoAdicional_cama"></p>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b class="">INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-3">
                                              <label for="tipo">TIPO DE DOCUMENTO</label>
                                              <p>DNI</p>
                                            </div>
                                            <div class="col-md-2">
                                              <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                                              <p id="prest_dni"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>N° COLEGIATURA</label>
                                              <p id="prest_colegiatura"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>N° RNE</label>
                                              <p id="prest_rne"></p>
                                            </div>
                                            <div class="col-md-3">
                                              <label>PROFESION</label>
                                              <p id="prest_profesion"></p>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-md-3">
                                              <label>APELLIDO PATERNO</label>
                                              <p id="prest_medico_paterno"></p>
                                            </div>
                                            <div class="col-md-3">
                                              <label>APELLIDO MATERNO</label>
                                              <p id="prest_medico_materno"></p>
                                            </div>
                                            <div class="col-md-3">
                                              <label>NOMBRES</label>
                                              <p id="prest_medico_nombre"></p>
                                            </div>
                                            <div class="col-md-3">
                                              <label>ESPECIALIDAD</label>
                                              <p id="prest_especializacion"></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>DATOS TRIAJE</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-2">
                                              <label>TEMPERATURA (°C) &nbsp;</label>
                                              <p id="dt_temperatura"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>TALLA (cm) &nbsp;</label>
                                              <p id="dt_talla_cm"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>PESO (Kg) &nbsp;</label>
                                              <p id="dt_peso_kg"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>IMC &nbsp;</label>
                                              <p id="dt_imc"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>PESO IDEAL &nbsp;</label>
                                              <p id="dt_peso_ideal"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Sa02 (%) &nbsp;</label>
                                              <p id="dt_sa02_porcentaje"></p>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-md-2">
                                              <label>TAMAÑO DE CINTURA (cm)</label>
                                              <p id="dt_tamano_cintura_cm"></p>
                                            </div>
                                            <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                              <label>FREC. CARDIACA FETAL(/min)</label>
                                              <p id="dt_frecuencia_cardiaca_fetal"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>PRESIÓN ARTERIAL &nbsp;</label>
                                              <div class="row">
                                                <div class="col-xs-5">                          
                                                  <p id="dt_presion_arterial_x"></p>
                                                </div>  
                                                  <div class="col-xs-2">
                                                     <label>/</label> 
                                                  </div>
                                                <div class="col-xs-5">
                                                  <p id="dt_presion_arterial_y"></p>
                                                </div>  
                                              </div>
                                          </div>
                                            <div class="col-md-4">
                                              <label>FREC.RESPIRATORIA(/min)</label>
                                              <p id="dt_frecuencia_respiratoria"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>FREC. CARDIACA (/min) &nbsp;</label>
                                              <p id="dt_frecuencia_cardiaca"></p>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fi02 (%) &nbsp;</label>
                                              <p id="dt_fi02"></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>ANTECEDENTES</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="col-md-12">
                                            <label>ATECEDENTE</label>
                                            <p id="prest_antecedente"></p>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <div class="col-md-12">
                                            <label>ANTECEDENTE FAMILIAR</label>
                                              <p id="prest_antecedente_familiar"></p>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>ANAMNESIS</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="col-md-12">
                                            <label>MOTIVO DE VISITA</label>
                                            <p id="prest_motivo_visita"></p>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <div class="col-md-12">
                                            <label>TIEMPO DE ENFERMEDAD/RELATO CRONOLOGICO</label>
                                              <p id="prest_tiempo_enfermedad"></p>
                                              <p id="prest_anamnesis"></p>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <div class="col-md-12">
                                            <label>EXAMENES AUXILIARES</label>
                                            <p id="prest_examen_auxiliar"></p>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>EXAMEN FISICO</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                              <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                            </div>
                                            <div class="panel-body">
                                              <div class="form-group">
                                                <div class="col-md-2">
                                                  <label>TEMPERATURA (°C) &nbsp;</label>
                                                  <p id="prest_temperatura"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>TALLA (cm) &nbsp;</label>
                                                  <p id="prest_talla_cm"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>PESO (Kg) &nbsp;</label>
                                                  <p id="prest_peso_kg"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>IMC &nbsp;</label>
                                                  <p id="prest_imc"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>PESO IDEAL &nbsp;</label>
                                                  <p id="prest_peso_ideal"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>Sa02 (%) &nbsp;</label>
                                                  <p id="prest_sa02_porcentaje"></p>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <div class="col-md-2">
                                                  <label>TAMAÑO DE CINTURA (cm)</label>
                                                  <p id="prest_tamano_cintura_cm"></p>
                                                </div>
                                                <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                                  <label>FREC. CARDIACA FETAL(/min)</label>
                                                  <p id="prest_frecuencia_cardiaca_fetal"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>PRESIÓN ARTERIAL &nbsp;</label>
                                                  <div class="row">
                                                    <div class="col-xs-5">                          
                                                      <p id="prest_presion_arterial_x"></p>
                                                    </div>  
                                                    <div class="col-xs-2">
                                                         <label>/</label> 
                                                    </div>
                                                    <div class="col-xs-5">
                                                      <p id="prest_presion_arterial_y"></p>
                                                    </div>  
                                                  </div>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>FREC.RESPIRATORIA(/min)</label>
                                                  <p id="prest_frecuencia_respiratoria"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                  <p id="prest_frecuencia_cardiaca"></p>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>Fi02 (%) &nbsp;</label>
                                                  <p id="prest_fi02"></p>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <label>EXAMEN FISICO</label>
                                                <p id="prest_examen_fisico"></p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <label>EXAMEN CLINICO</label>
                                              <p id="prest_examen_clinico"></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                      
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <p id="prest_tratamiento_auxiliar"></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>PLAN DE TRABAJO</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <p id="prest_plan_trabajo"></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>DIAGNÓSTICOS</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <table class="display" id="tblDiagnosticoAtencionEM" cellspacing="0" width="100%" style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="20%">Codigo</th>
                                                    <th width="60%">Nombre</th>
                                                    <th width="20%">Tipo</th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody id="tableDiagAtencion">
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>PROCEDIMIENTOS MÉDICOS</b>
                                        </div>
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <table class="display" id="tblProcedimientoAtencionEM" cellspacing="0" width="100%" style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="20%">Codigo</th>
                                                    <th width="60%">Nombre</th>
                                                    <th width="20%" class="text-center">Cantidad</th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody id="tableProcAtencion">
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                    <fieldset>
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                          <b>PRODUCTOS MEDICOS</b>
                                        </div>
                                        <div class="panel-body">
                                          <div hidden id="recetaEstaDispensada" style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold">
                                            LA RECETA HA SIDO DISPENSADA EL <span id="recet_fecha_expedicion"></span>          
                                          </div>
                                          <div hidden id="recetaNoEstaDispensada" style="padding-bottom:0px;line-height:30px;color:#FF5151;font-weight:bold">
                                            LA RECETA AUN NO HA SIDO DISPENSADA
                                          </div> 
                                          <br />
                                          <div style="clear:both"></div>
                                          <fieldset>
                                            <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-body">
                                                <div class="form-group">
                                                  <div class="col-md-12">
                                                    <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                                                    <div class="col-md-1">
                                                      <p id="recet_nro_receta"></p>
                                                    </div>
                                                    <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                    <div class="col-md-2">
                                                      <p id="recet_fecha_expedicion"></p>
                                                    </div>
                                                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                    <div class="col-md-4" style="padding:0px">
                                                      <p id="recet_farmacia"></p>
                                                    </div>
                                                  </div>
                                                </div>
                                                <table class="display" cellspacing="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th width="15%">Codigo</th>
                                                      <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                                      <th width="15%">Unidad Medida</th>
                                                      <th width="10%">Tiempo(Dias)</th>
                                                      <th width="15%">Posologia</th>
                                                      <th width="15%">Cantidad Prescrita</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody id="tableMedicamentosRecet">
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </fieldset>
                                        </div>
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
