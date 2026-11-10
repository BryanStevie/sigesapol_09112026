<!-- Modal -->
<div class="modal fade" id="modalMonitoreoActualizar" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button id="closeMonitoreoActualizar" type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioMonitoreoActualizar"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioMonitoreoActualizar" name="FormularioMonitoreoActualizar" enctype="multipart/form-data" method="post">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_monitoreo_up" name="id_monitoreo_up">
                    <input type="hidden" id="date_monitoreo_up" name="date_monitoreo_up">
                    <input type="hidden" id="id_turno_up" name="id_turno_up">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    <div class="panel panel-info panel-bordered panel-sm" id="datosPacienteParaCargarMonitoreoRegistro"></div>
                                </fieldset>
                                <fieldset>
                                    <form action="">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-body" id="">
                                                <fieldset>
                                                    <div class="form-group" style="margin-bottom: 0; !important">
                                                        <div class="table-responsive">
                                                            <table id=""
                                                                class="table table-striped table-list-search"
                                                                style="margin-bottom: 0px !important;">
                                                                <tbody>
                                                                    <tr class="modal-tr">
                                                                        <td class="monitoreo-td">
                                                                            <label><b>FECHA & HORA:</b></label>
                                                                            <input readonly id="fecha_hora_monitoreo_up" name="fecha_hora_monitoreo_up" type="time" class="form-control"
                                                                                style="height: 30px !important;">
                                                                        </td>
                                                                        <td class="monitoreo-td">
                                                                            <label><b>TURNO</b></label>
                                                                            <select disabled class="form-control input-sm" name="id_turno_up" id="turno_up">
                                                                                <option value="">--Seleccionar--</option>
                                                                                <option value="2">MAÑANA</option>
                                                                                <option value="3">TARDE</option>
                                                                                <option value="4">NOCHE</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important;">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active accesTab_up">
                                                        <a href="#tab0defaultMonitoreo_up" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. HEMODINAMICO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_up">
                                                        <a href="#tab1defaultMonitoreo_up" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>SIGNOS VITALES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_up">
                                                        <a href="#tab2defaultMonitoreo_up" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. RESPIRATORIO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_up">
                                                        <a href="#tab3defaultMonitoreo_up" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>INFUSIONES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_up">
                                                        <a href="#tab4defaultMonitoreo_up" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>ADICIONAL
                                                            </b>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade active in tabMoni_up" id="tab0defaultMonitoreo_up">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search"
                                                                        style="margin-bottom: 0px !important;">
                                                                        <thead>
                                                                            <tr class="modal-tr">
                                                                                <th style="text-align: center;">INDICADORES</th>
                                                                                <th style="text-align: center;">VALOR</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            {{-- <tr class="modal-tr">
                                                                                <td><b>HORA</b></td>
                                                                                <td class="monitoreo-td"><input id="hora" name="hora" type="time" class="form-control"
                                                                                        style="height: 30px !important;"></td>
                                                                            </tr> --}}
                                                                            <tr class="modal-tr">
                                                                                <td><b>G. CONCIENCIA</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <select class="form-control input-sm" name="conciencia_monitoreo_up" id="conciencia_monitoreo_up">
                                                                                        <option value="">--Seleccionar--</option>
                                                                                        <option value="RELAJACION">RELAJACION</option>
                                                                                        <option value="ANALGESIA">ANALGESIA</option>
                                                                                        <option value="SEDACION">SEDACION</option>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>PUPILAS</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <table class="table table-striped table-list-search">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="cell-pupila"><b>Descripcion</b></th>
                                                                                                {{-- <th class="cell-pupila"><b>No evaluable</b></th> --}}
                                                                                                <th class="cell-pupila"><b>Cantidad</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr class="">
                                                                                                <td class="cell-pupila">Der</td>
                                                                                                {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_der_up" name="activo_pupila_der_up" checked></td> --}}
                                                                                                <td class="cell-pupila"><input class="form-control" type="text" id="pupila_derecha_up" name="pupila_derecha_up" onkeypress="return esEntero(event)"></td>
                                                                                            </tr>
                                                                                            <tr class="">
                                                                                                <td class="cell-pupila">Izq</td>
                                                                                                {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_izq_up" name="activo_pupila_der_up" checked></td> --}}
                                                                                                <td class="cell-pupila"><input class="form-control" type="text" id="pupila_izquierda_up" name="pupila_izquierda_up" onkeypress="return esEntero(event)"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>G. DEPENDENCIA</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <select class="form-control input-sm" id="grado_dependencia_up" name="grado_dependencia_up" style="width: 100%;">
                                                                                        <option value="">--Seleccionar--</option>
                                                                                        <option value="GRADO I">GRADO I</option>
                                                                                        <option value="GRADO II">GRADO II</option>
                                                                                        <option value="GRADO III">GRADO III</option>
                                                                                        <option value="GRADO IV">GRADO IV</option>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>ESCALA DE GLASGOW</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <label for="puntaje_glasgow" class="desc_glasgow">Descripcion GLASGOW: <b><span id="desc_glasgow_up"></span></b></label>
                                                                                    <div class="glasgow">
                                                                                        <input readonly type="text" class="form-control puntaje-glasgow" style="height: 30px !important;" id="glasgow_up" name="glasgow_up">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs calcularEscalaGlasgowMonitoreo_up" id=""
                                                                                            name="" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="hidden" value="" id="puntaje_calculado_glasgow_memoria_up"
                                                                                            name="puntaje_calculado_glasgow_memoria_up">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>ESCALA DE RASS</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <label for="puntaje_rass" class="desc_glasgow">Descripcion RASS: <b><span id="desc_rass_up"></span></b></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" id="puntaje_calculado_rass_up" name="puntaje_calculado_rass_up"
                                                                                        class="form-control puntaje_calculado_rass" style="height: 30px !important;"
                                                                                        readonly>
                                                                                        <input type="hidden" value="" id="rass_up" name="rass_up">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs calcularEscalaRass_up" id="calcularEscalaRass_up"
                                                                                            name="calcularEscalaRass_up" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_up" id="tab1defaultMonitoreo_up">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <table id="example1"
                                                                        class="table table-striped table-list-search"
                                                                        style="margin-bottom: 0px !important;">
                                                                        <thead>
                                                                            <tr class="modal-tr">
                                                                                <th style="text-align: center;">INDICADORES</th>
                                                                                <th style="text-align: center;">VALOR</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="modal-tr">
                                                                                <td><b>PRESION ARTERIAL (PA)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <label for=""><b>COMPLETAR CAMPOS EN CASO DE CAMBIAR EL PAM PREVIAMENTE INGRESADO.</b></label>
                                                                                        <input placeholder="P.A. Sisdolica" title="Presion arterial Sisdolica" class="form-control" type="text" id="pa_sisdolica_up" name="pa_sisdolica_up" onkeypress="return esEntero(event)">
                                                                                        <span class="input-divisor"> / </span>
                                                                                        <input placeholder="P.A. Diastolica" title="Presion arterial Diastolica" class="form-control" type="text" id="pa_diastolica_up" name="pa_diastolica_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>PRESION ARTERIAL MEDIA (PAM)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input title="PRIMERO INGRESE LA PRESION ARTERIAL" readonly class="form-control" type="text" id="pam_up" name="pam_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. CARDIACA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="pulso_fc_up" name="pulso_fc_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="frec_cardiaca-aceptado_up"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. RESPIRATORIA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="frecuencia_respiratoria_up" name="frecuencia_respiratoria_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="frecuencia_respiratoria-aceptado_up"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>TEMPERATURA (°C)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="temperatura_up" name="temperatura_up" oninput="filterInput(this)">
                                                                                    </div>
                                                                                    <span id="texto-aceptado_up"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Sa02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="sa02_up" name="sa02_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="saturacion-aceptado_up"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Fi02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="fi02_up" name="fi02_up" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="oxigenacion-aceptado_up"></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_up" id="tab2defaultMonitoreo_up">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>RESPIRACION ESPONTANEA</b></span>
                                                                            <input type="hidden" name="respiracion_espontanea_up" id="respiracion_espontanea_up" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="re_si_up" name="re_options_up" type="radio">
                                                                            <label for="re_si_up">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="re_no_up" name="re_options_up" type="radio" checked>
                                                                            <label for="re_no_up">NO</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>PRESENCIA DE TIRAJE</b></span>
                                                                            <input type="hidden" name="tiraje_up" id="tiraje_up" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="tiraje_si_up" name="tiraje_toogle_up" type="radio">
                                                                            <label for="tiraje_si_up">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="tiraje_no_up" name="tiraje_toogle_up" type="radio" checked>
                                                                            <label for="tiraje_no_up">NO</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>DISPOSITIVOS DE OXIGENACION</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="do_si_up" name="do_up" type="radio">
                                                                            <label for="do_si_up">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="do_no_up" name="do_up" type="radio" checked>
                                                                            <label for="do_no_up">NO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">DESCRIPCION</label>
                                                                            <input readonly class="form-control" id="do_desc_up" name="dispositivo_oxigenoterapia_up" type="text">
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>MODALIDAD:ASIS./CONTR.</b></span>
                                                                            <input type="hidden" name="modalidad_up" id="modalidad_final_up">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion_up" name="md_up" type="radio">
                                                                            <label for="md_presion_up">PRESION</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen_up" name="md_up" type="radio">
                                                                            <label for="md_volumen_up">VOLUMEN</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOLUMEN TIDAL</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="volumen_tiral_up" name="volumen_tiral_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PEEP</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" name="peed_up" id="peed_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>FR (M/P) ?</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="frecuencia_m_up" name="frecuencia_m_up" onkeypress="return esEntero(event)">
                                                                            <span style="margin: 0 5px;">/</span>
                                                                            <input type="text" class="form-control" id="frecuencia_p_up" name="frecuencia_p_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOL/MIN</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="vol_min_up" name="vol_min_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>T. INSPIRACION:T. EXPIRACION</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="i_e_up" name="i_e_up">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PRESION SOPORTE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="presion_soporte_up" name="presion_soporte_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. MAXIMA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_maxima_up" name="p_maxima_up">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. LIMITE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_limite_up" name="p_limite_up">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>CO2</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="co2_up" name="co2_up">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. PICCO</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_picco_up" name="p_picco_up">
                                                                        </div>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_up" id="tab3defaultMonitoreo_up">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <div class="row col-md-12">
                                                                        <button type="button" class="btn btn-info btn-xs" id="addInfusion_up">Agregar Infusiones</button>
                                                                    </div>
                                                                    <div class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                                                        <table id="tblInfusiones_up" class="display">
                                                                            <thead></thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_up" id="tab4defaultMonitoreo_up">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span style="color: green"><b>DIURESIS</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="diurisis_horaria_up" name="diurisis_horaria_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>HEMOGLUCOTEST</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="hemoglucotest_up" name="hemoglucotest_up" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form> 
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left"
                                data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" id="actualizarMonitoreo"
                                value="">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>