<!-- Modal -->
<div class="modal fade" id="modalMonitoreoDetalle" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button id="closeMonitoreoDetalle" type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioMonitoreoDetalle"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioMonitoreoDetalle" name="FormularioMonitoreoActualizar" enctype="multipart/form-data" method="">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_monitoreo_dt" name="id_monitoreo_dt">
                    <input type="hidden" id="date_monitoreo_dt" name="date_monitoreo_dt">
                    <input type="hidden" id="id_turno_dt" name="id_turno_dt">
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
                                                                            <input readonly id="fecha_hora_monitoreo_dt" name="fecha_hora_monitoreo_dt" type="time" class="form-control"
                                                                                style="height: 30px !important;">
                                                                        </td>
                                                                        <td class="monitoreo-td">
                                                                            <label><b>TURNO</b></label>
                                                                            <select disabled class="form-control input-sm" name="id_turno_dt" id="turno_dt">
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
                                                    <li class="active accesTab_dt">
                                                        <a href="#tab0defaultMonitoreo_dt" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. HEMODINAMICO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_dt">
                                                        <a href="#tab1defaultMonitoreo_dt" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>SIGNOS VITALES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_dt">
                                                        <a href="#tab2defaultMonitoreo_dt" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. RESPIRATORIO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_dt">
                                                        <a href="#tab3defaultMonitoreo_dt" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>INFUSIONES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab_dt">
                                                        <a href="#tab4defaultMonitoreo_dt" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>ADICIONAL
                                                            </b>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade active in tabMoni_dt" id="tab0defaultMonitoreo_dt">
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
                                                                                    <select class="form-control input-sm" name="conciencia_monitoreo_dt" id="conciencia_monitoreo_dt">
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
                                                                                                {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_der_dt" name="activo_pupila_der_dt" checked></td> --}}
                                                                                                <td class="cell-pupila"><input class="form-control" type="text" id="pupila_derecha_dt" name="pupila_derecha_dt"></td>
                                                                                            </tr>
                                                                                            <tr class="">
                                                                                                <td class="cell-pupila">Izq</td>
                                                                                                {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_izq_dt" name="activo_pupila_der_dt" checked></td> --}}
                                                                                                <td class="cell-pupila"><input class="form-control" type="text" id="pupila_izquierda_dt" name="pupila_izquierda_dt"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>G. DEPENDENCIA</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <select class="form-control input-sm" id="grado_dependencia_dt" name="grado_dependencia_dt" style="width: 100%;">
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
                                                                                    <label for="puntaje_glasgow" class="desc_glasgow">Descripcion GLASGOW: <b><span id="desc_glasgow_dt"></span></b></label>
                                                                                    <div class="glasgow">
                                                                                        <input readonly type="text" class="form-control puntaje-glasgow" style="height: 30px !important;" id="glasgow_dt" name="glasgow_dt">
                                                                                        <span class="input-group-btn">
                                                                                            {{-- <button class="btn btn-success btn-xs calcularEscalaGlasgowMonitoreo_dt" id=""
                                                                                            name="" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button> --}}
                                                                                        </span>
                                                                                        <input type="hidden" value="" id="puntaje_calculado_glasgow_memoria_dt"
                                                                                            name="puntaje_calculado_glasgow_memoria_dt">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>ESCALA DE RASS</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <label for="puntaje_rass" class="desc_glasgow">Descripcion RASS: <b><span id="desc_rass_dt"></span></b></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" id="puntaje_calculado_rass_dt" name="puntaje_calculado_rass_dt"
                                                                                        class="form-control puntaje_calculado_rass" style="height: 30px !important;"
                                                                                        readonly>
                                                                                        <input type="hidden" value="" id="rass_dt" name="rass_dt">
                                                                                        <span class="input-group-btn">
                                                                                            {{-- <button class="btn btn-success btn-xs calcularEscalaRass_dt" id="calcularEscalaRass_dt"
                                                                                            name="calcularEscalaRass_dt" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button> --}}
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
                                                    <div class="tab-pane tabMoni_dt" id="tab1defaultMonitoreo_dt">
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
                                                                                        <input placeholder="P.A. Sisdolica" title="Presion arterial Sisdolica" class="form-control" type="text" id="pa_sisdolica_dt" name="pa_sisdolica_dt" onkeypress="return esEntero(event)">
                                                                                        <span class="input-divisor"> / </span>
                                                                                        <input placeholder="P.A. Diastolica" title="Presion arterial Diastolica" class="form-control" type="text" id="pa_diastolica_dt" name="pa_diastolica_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>PRESION ARTERIAL MEDIA (PAM)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input title="PRIMERO INGRESE LA PRESION ARTERIAL" readonly class="form-control" type="text" id="pam_dt" name="pam_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. CARDIACA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="pulso_fc_dt" name="pulso_fc_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. RESPIRATORIA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="frecuencia_respiratoria_dt" name="frecuencia_respiratoria_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>TEMPERATURA (°C)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="temperatura_dt" name="temperatura_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Sa02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="sa02_dt" name="sa02_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Fi02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="fi02_dt" name="fi02_dt" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_dt" id="tab2defaultMonitoreo_dt">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>FIJACION DE TUBO (F/V)</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="ft_der_dt" name="ft_options_dt" type="radio">
                                                                            <input type="text" hidden id="tubo_der_dt" name="tubo_der_dt" value="0">
                                                                            <label for="ft_der_dt">DERECHO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="ft_izq_dt" name="ft_options_dt" type="radio">
                                                                            <input type="text" hidden id="tubo_izq_dt" name="tubo_izq_dt" value="0">
                                                                            <label for="ft_izq_dt">IZQUIERDO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="ft_valor_dt">VALOR</label>
                                                                            <input disabled class="form-control" id="ft_valor_dt" name="ft_valor_dt" type="text">
                                                                            <input class="form-control" id="fijacion_der_dt" name="fijacion_der_dt" type="hidden">
                                                                            <input class="form-control" id="fijacion_izq_dt" name="fijacion_izq_dt" type="hidden">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>MODOS DE VENTILACION</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmi_dt_toggle" name="vm_dt" type="radio" disabled>
                                                                            <input type="text" hidden id="active_vmi_dt" name="active_vmi_dt" value="0">
                                                                            <label for="vmi_dt">V.M.I.</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmni_dt_toggle" name="vm_dt" type="radio" disabled>
                                                                            <input type="text" hidden id="active_vmni_dt" name="active_vmni_dt" value="0">
                                                                            <label for="vmni_dt">V.M.N.I</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">DESCRIPCION</label>
                                                                            <input disabled class="form-control" id="vm_desc_dt" name="vm_desc_dt" type="text">
                                                                            <input class="form-control" id="vmi_dt" name="vmi_dt" type="hidden">
                                                                            <input class="form-control" id="vmni_dt" name="vmni_dt" type="hidden">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>MODALIDAD:ASIS./CONTR.</b></span>
                                                                            <input type="hidden" name="modalidad_dt" id="modalidad_final_dt">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion_dt" name="md_dt" type="radio">
                                                                            <label for="md_presion_dt">PRESION</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen_dt" name="md_dt" type="radio">
                                                                            <label for="md_volumen_dt">VOLUMEN</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOLUMEN TIDAL</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="volumen_tiral_dt" name="volumen_tiral_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PEEP</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" name="peed_dt" id="peed_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>FR (M/P) ?</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="frecuencia_m_dt" name="frecuencia_m_dt">
                                                                            <span style="margin: 0 5px;">/</span>
                                                                            <input type="text" class="form-control" id="frecuencia_p_dt" name="frecuencia_p_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOL/MIN</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="vol_min_dt" name="vol_min_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>T. INSPIRACION:T. EXPIRACION</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="i_e_dt" name="i_e_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PRESION SOPORTE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="presion_soporte_dt" name="presion_soporte_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. MAXIMA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_maxima_dt" name="p_maxima_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. LIMITE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_limite_dt" name="p_limite_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>CO2</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="co2_dt" name="co2_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. PICCO</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_picco_dt" name="p_picco_dt">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_dt" id="tab3defaultMonitoreo_dt">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <div class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                                                        <table id="tblInfusiones_dt" class="display">
                                                                            <thead></thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni_dt" id="tab4defaultMonitoreo_dt">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span style="color: green"><b>DIURESIS</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="diurisis_horaria_dt" name="diurisis_horaria_dt">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>HEMOGLUCOTEST</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="hemoglucotest_dt" name="hemoglucotest_dt">
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
                            <button type="button" data-monitoreo-date="" data-monitoreo-id="" class="btn btn-success firmarMonitoreo" id="firmaMonitoreo"
                                >FIRMAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>