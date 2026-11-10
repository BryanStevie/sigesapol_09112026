<form id="FormularioHojaIntraoperatorio" name="FormularioHojaIntraoperatorio" enctype="multipart/form-data" method="POST">
    <input type="hidden" id="id_intraoperatorio-det" value="">
    <input type="hidden" id="id_resol" value="">
    <input type="hidden" name="flag_firma" id="flag_firma" value="">
    <input type="hidden" id="argumentos" />
    <input type="hidden" id="doc_firmar" />
    <input type="hidden" name="flag_firma_centro_quirurgico" id="flag_firma_centro_quirurgico" value="1">
    <div class="nav-tabs-custom">
        <div class="tab-content">
            <div class="tab-pane active" style="font-size: 12px; color: #606060">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <fieldset>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Fecha y hora de registro:</b>
                                    </span><span id="inter_fecha_registro-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Fecha Probable:</b>
                                    </span><span id="inter_fecha_probable-det"></span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Tarifa:</b>
                                    </span><span class="text-muted" style="font-style: italic;">No especifica</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-4 col-lg-8 col-xl-8"><span><b>Sala:</b>
                                    </span><span id="inter_sala-det"></span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"><span><b>Edad:</b>
                                    </span><span id="inter_edad_paciente-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Nombre del
                                            Paciente:</b> </span><span id="inter_nombre_paciente-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Anestesiologo:</b>
                                    </span>
                                    <span id="inter_anestesiologos-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Cirujanos:</b>
                                    </span>
                                    <span id="inter_cirujanos-det"></span>
                                </div>
                            </div>
                            <div class="row" id="instrumentistas-container">
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Cajas de
                                            Instrumental:</b>
                                    </span><span id="inter_cajas_instrumental-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <span><b>Disponibilidad de </b></span>
                                    <span>Equipos ( <span id="inter_equipos-det"></span> )</span>
                                    <span>Insumos ( <span id="inter_insumos-det"></span> )</span>
                                    <span>Instrumental ( <span id="inter_instrumental-det"></span> )</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <span><b>Enf I y Enf II verifica y controla:</b> </span>
                                    <span>Insumos ( <span id="inter_insumos2-det"></span> )</span>
                                    <span>Instrumental ( <span id="inter_instrumental2-det"></span> )</span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <fieldset>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Recepción del
                                            paciente:</b> </span><span id="inter_recep_pac_desc-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Tipo de
                                            Anestesia:</b>
                                    </span><span id="inter_tipo_anestesia-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Inicio de
                                            Anestesia:</b>
                                    </span><span id="inter_anestesia_inicio-det"></span></div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Término:</b>
                                    </span><span id="inter_anestesia_termino-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Inicio de Cirugía:</b>
                                    </span><span id="inter_cirugia_inicio-det"></span></div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Término:</b>
                                    </span><span id="inter_cirugia_termino-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Tiempo Real:</b>
                                    </span><span id="inter_tipo_real-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación
                                            Programada:</b>
                                    </span><span id="inter_operaciones_programadas-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación de
                                            Emergencia:</b>
                                    </span><span id="inter_operaciones_emergencia-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación
                                            Realizada:</b>
                                    </span><span id="inter_operaciones_realizadas-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Salida del
                                            Paciente:</b> </span><span id="inter_salida_paciente-det"></span> </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Hora llegada
                                            URPA:</b>
                                    </span><span id="inter_hora_llegada_urpa-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <span><b>COMPLICACIONES:</b>
                                    </span><span id="inter_complicaciones-det"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Maquina de
                                            Anestesia:</b> </span><span
                                        id="inter_maquina_anestesia_descripcion-det"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Oxigeno:</b>
                                    </span><span id="inter_oxigeno_descripcion-det"></span></div>
                            </div>
                    </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p style="text-align: center; font-size: 12px; font-weight: 700">DIAGNOSTICOS DE ENFERMERIA:</p>
                <table class="table table-sm">
                    <tbody id="lista_diagnosticos">
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p style="text-align: center; font-size: 12px; font-weight: 700">INTERVENCIÓN DE ENFERMERIA (NIC):
                </p>
                <table class="table table-sm">
                    <tbody id="lista_diagnosticos_nic">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p style="text-align: center; font-size: 12px; font-weight: 700">BALANCE DE FLUIDOS:</p>
                <table id="balance_fluidos" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-size: 12px;" width="40%">INGRESO</th>
                            <th style="text-align: center; font-size: 12px;" width="5%">EGRESO</th>
                            <th style="text-align: center; font-size: 12px;" width="5%">TERAPEUTICA</th>
                        </tr>
                    </thead>
                    <tbody id="lista_balance_fluidos">
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p style="text-align: center; font-size: 12px; font-weight: 700">RECUENTO DE GASAS:</p>
                <table id="recuento_gasas" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-size: 12px;" width="25%"></th>
                            <th style="text-align: center; font-size: 12px;" width="25%">ENF. I</th>
                            <th style="text-align: center; font-size: 12px;" width="25%">ENF. II</th>
                            <th style="text-align: center; font-size: 12px;" width="25%">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="lista_recuento_gasas">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <table width="100%">
            <tr>
                <td>
                    <div id="contenedor-firma" style="display: inline-block;"></div>
                </td>
            </tr>
        </table>
    </div>
</form>