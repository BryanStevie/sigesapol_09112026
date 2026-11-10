{{-- REGISTRAR SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL  INICIO --}}
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
                                <button tabindex="90" type="button" id="tolerancia_paciente_pediatrico_ttgo" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_pediatrico_ttgo" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Fecha:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" name="fecha_tolerancia_paciente_pediatrico_ttgo" id="fecha_tolerancia_paciente_pediatrico_ttgo" class="form-control" value="Fecha lineal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                <label>Procedimiento con glucosa anhidra</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="90" type="button" id="tolerancia_paciente_pediatrico_glucosa_anhidra" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Cantidad:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra" id="cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra" class="form-control">
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
                                <button tabindex="90" type="button" id="tolerancia_paciente_adulto_ttgo" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_adulto_ttgo"  style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Fecha:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" name="fecha_tolerancia_paciente_adulto_ttgo" id="fecha_tolerancia_paciente_adulto_ttgo" class="form-control" value="Fecha lineal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                <label>Procedimiento con glucosa anhidra </label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="90" type="button" id="tolerancia_paciente_adulto_glucosa_anhidra" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_adulto_glucosa_anhidra" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Cantidad:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_adulto_glucosa_anhidra" id="cantidad_tolerancia_paciente_adulto_glucosa_anhidra" class="form-control">
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
                                        <input type="text" name="tolerancia_paciente_gestante_g" id="tolerancia_paciente_gestante_g" class="form-control" maxlength="1">
                                    </div>
                                </div>
                                <div class="col-sm-6" style="display: flex; gap: 0.5rem;">
                                    <div class="col-sm-2">
                                        <label>P:</label>
                                    </div>
                                    <div class="col-sm-2" style="padding: 0;">
                                        <input type="text" name="tolerancia_paciente_gestante_p_1" id="tolerancia_paciente_gestante_p_1" class="form-control" maxlength="1">
                                    </div>
                                    <div class="col-sm-2" style="padding: 0;">
                                        <input type="text" name="tolerancia_paciente_gestante_p_2" id="tolerancia_paciente_gestante_p_2" class="form-control" maxlength="1">
                                    </div>
                                    <div class="col-sm-2" style="padding: 0;">
                                        <input type="text" name="tolerancia_paciente_gestante_p_3" id="tolerancia_paciente_gestante_p_3" class="form-control" maxlength="1">
                                    </div>
                                    <div class="col-sm-2" style="padding: 0;">
                                        <input type="text" name="tolerancia_paciente_gestante_p_4" id="tolerancia_paciente_gestante_p_4" class="form-control" maxlength="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="col-sm-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                    <label>Edad gestacional</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_meses" id="tolerancia_paciente_gestante_edad_gestacional_meses" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_semanas" id="tolerancia_paciente_gestante_edad_gestacional_semanas" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="tolerancia_paciente_gestante_edad_gestacional_dias" id="tolerancia_paciente_gestante_edad_gestacional_dias" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row"><br></div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                <label>Paciente se a programado para Nutrición previo a la toma de TTGO</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="90" type="button" id="tolerancia_paciente_gestante_ttgo" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_ttgo" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Fecha:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" name="fecha_tolerancia_paciente_gestante_ttgo" id="fecha_tolerancia_paciente_gestante_ttgo" class="form-control" value="Fecha lineal">
                                </div>
                            </div>
                        </div>
                        <div class="row"><br></div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                <label>Procedimiento con glucosa anhidra</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="90" type="button" id="tolerancia_paciente_gestante_glucosa_anhidra" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_glucosa_anhidra" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Cantidad:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" placeholder="GR." name="cantidad_tolerancia_paciente_gestante_glucosa_anhidra" id="cantidad_tolerancia_paciente_gestante_glucosa_anhidra" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row"><br></div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                <label>Presenta factores de riesgo</label>
                            </div>
                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="90" type="button" id="tolerancia_paciente_gestante_factores_riesgo" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                                </button>
                            </div>
                            <div class="col-sm-6" id="contenedor_tolerancia_paciente_gestante_factores_riesgo" style="display: none;" >
                                <div class="col-sm-7">
                                    <label>Especifique:</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" placeholder="especifique." name="especifique_tolerancia_paciente_gestante_factores_riesgo" id="especifique_tolerancia_paciente_gestante_factores_riesgo" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarRegistroSolicitudTestToleranciaGlucosaOral" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>