<!-- Modal -->
<div class="modal fade" id="modalMonitoreo" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button id="closeMonitoreo" type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioMonitoreo"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioMonitoreo" name="FormularioMonitoreo" enctype="multipart/form-data" method="post">
                    <input type="hidden" id="edadPaciente">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="asegurado_id_monitoreo" name="asegurado_id_monitoreo">
                    <input type="hidden" id="id_enfermeria" name="id_enfermeria">
                    <input type="hidden" id="turno_reg" name="turno_reg">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    <div class="panel panel-info panel-bordered panel-sm" id="datosPacienteParaCargarMonitoreoRegistro"></div>
                                </fieldset><br>
                                {{-- <fieldset> 
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <div class="panel-title text-center"><br>
                                                <b>DIAGNOSTICO DE LA ATENCION</b>
                                            </div>
                                        </div>
                                        <div class="panel-body" id="datosPacienteParaCargarMonitoreoRegistroDiagnosticos">  
                                        </div>
                                    </div>
                                </fieldset> --}}
                                {{-- <hr> --}}
                                <fieldset>
                                    <form action="">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            {{-- <div class="panel-heading">
                                                <div class="panel-title text-center"><br>
                                                    <b>DIAGNOSTICO DE LA ATENCION</b>
                                                </div>
                                            </div> --}}
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
                                                                            <label><b>HORA:</b></label>
                                                                            <input id="fecha_hora_monitoreo" name="fecha_hora_monitoreo" type="time" class="form-control"
                                                                                style="height: 30px !important;">
                                                                        </td>
                                                                        <td class="monitoreo-td">
                                                                            <label><b>TURNO</b></label>
                                                                            <select disabled class="form-control input-sm" name="id_turno" id="turno">
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
                                                    <li class="active accesTab">
                                                        <a href="#tab0defaultMonitoreo" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. HEMODINAMICO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab">
                                                        <a href="#tab1defaultMonitoreo" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>SIGNOS VITALES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab">
                                                        <a href="#tab2defaultMonitoreo" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>M. RESPIRATORIO
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab">
                                                        <a href="#tab3defaultMonitoreo" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>INFUSIONES
                                                            </b>
                                                        </a>
                                                    </li>
                                                    <li class="accesTab">
                                                        <a href="#tab4defaultMonitoreo" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                                            <b>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>ADICIONAL
                                                            </b>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade active in tabMoni" id="tab0defaultMonitoreo">
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
                                                                                    <select class="form-control input-sm" name="conciencia_monitoreo" id="grado_conciencia">
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
                                                                                                <th class="cell-pupila"><b>No evaluable</b></th>
                                                                                                <th class="cell-pupila"><b>Cantidad</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr class="">
                                                                                                <td class="cell-pupila">Der</td>
                                                                                                <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_der" name="activo_pupila_der" checked></td>
                                                                                                <td class="cell-pupila"><input readonly class="form-control" type="text" id="cantidad_pupila_der" name="pupila_derecha" onkeypress="return esEntero(event)"></td>
                                                                                            </tr>
                                                                                            <tr class="">
                                                                                                <td class="cell-pupila">Izq</td>
                                                                                                <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_izq" name="activo_pupila_der" checked></td>
                                                                                                <td class="cell-pupila"><input readonly class="form-control" type="text" id="cantidad_pupila_izq" name="pupila_izquierda" onkeypress="return esEntero(event)"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>G. DEPENDENCIA</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <select class="form-control input-sm" id="grado_dependencia" name="grado_dependencia" style="width: 100%;">
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
                                                                                    <label for="puntaje_glasgow" class="desc_glasgow">Descripcion GLASGOW: <b><span id="desc_glasgow"></span></b></label>
                                                                                    <div class="glasgow">
                                                                                        <input readonly type="text" class="form-control puntaje-glasgow" style="height: 30px !important;" id="glasgow" name="glasgow">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs calcularEscalaGlasgowMonitoreo" id=""
                                                                                            name="" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="hidden" value="" id="puntaje_calculado_glasgow_memoria"
                                                                                            name="puntaje_calculado_glasgow_memoria">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>ESCALA DE RASS</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <label for="puntaje_rass" class="desc_glasgow">Descripcion RASS: <b><span id="desc_rass"></span></b></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" id="puntaje_calculado_rass" name="puntaje_calculado_rass"
                                                                                        class="form-control puntaje_calculado_rass" style="height: 30px !important;"
                                                                                        readonly>
                                                                                        <input type="hidden" value="" id="puntaje_calculado_rass_memoria" name="rass">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs calcularEscalaRass" id="calcularEscalaRass"
                                                                                            name="calcularEscalaRass" style="height: 30px !important;" type="button">
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
                                                    <div class="tab-pane tabMoni" id="tab1defaultMonitoreo">
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
                                                                                        <input placeholder="P.A. Sistolica" title="Presion arterial Sisdolica" class="form-control" type="text" id="pa_1" name="pa_sisdolica" onkeypress="return esEntero(event)">
                                                                                        <span class="input-divisor"> / </span>
                                                                                        <input placeholder="P.A. Diastolica" title="Presion arterial Diastolica" class="form-control" type="text" id="pa_2" name="pa_diastolica" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>PRESION ARTERIAL MEDIA (PAM)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input title="PRIMERO INGRESE LA PRESION ARTERIAL" readonly class="form-control" type="text" id="pam" name="pam" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. CARDIACA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="fc" name="pulso_fc" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="frec_cardiaca-aceptado"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>FREC. RESPIRATORIA (/min)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="fr" name="frecuencia_respiratoria" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="frecuencia_respiratoria-aceptado"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>TEMPERATURA (°C)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input placeholder="00.0" class="form-control" type="text" id="temp" name="temperatura" oninput="filterInput(this)">
                                                                                    </div>
                                                                                    <span id="texto-aceptado"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Sa02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="sa02" name="sa02" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="saturacion-aceptado"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="modal-tr">
                                                                                <td><b>Fi02 (%)</b></td>
                                                                                <td class="monitoreo-td">
                                                                                    <div class="various-inputs">
                                                                                        <input class="form-control" type="text" id="fi02" name="fi02" onkeypress="return esEntero(event)">
                                                                                    </div>
                                                                                    <span id="oxigenacion-aceptado"></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni" id="tab2defaultMonitoreo">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>RESPIRACION ESPONTANEA</b></span>
                                                                            <input type="hidden" name="respiracion_espontanea" id="respiracion_espontanea" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion" name="md" type="radio">
                                                                            <label for="md_presion">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen" name="md" type="radio" checked>
                                                                            <label for="md_volumen">NO</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>PRESENCIA DE TIRAJE</b></span>
                                                                            <input type="hidden" name="tiraje" id="tiraje" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion_t" name="md_t" type="radio">
                                                                            <label for="md_presion_t">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen_t" name="md_t" type="radio" checked>
                                                                            <label for="md_volumen_t">NO</label>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>PRESENCIA DE TIRAJE</b></span>
                                                                            <input type="hidden" name="modalidad" id="modalidad_final">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion" name="md" type="radio">
                                                                            <label for="md_presion">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen" name="md" type="radio">
                                                                            <label for="md_volumen">NO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                        </div>
                                                                    </div> --}}
                                                                    {{-- <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>RESPIRACION ESPONTANEA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="ft_der" name="ft_options" type="radio">
                                                                            <input type="text" hidden id="tubo_der" name="tubo_der" value="0">
                                                                            <label for="ft_der">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="ft_izq" name="ft_options" type="radio">
                                                                            <input type="text" hidden id="tubo_izq" name="tubo_izq" value="0">
                                                                            <label for="ft_izq">NO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="ft_valor">VALOR</label>
                                                                            <input readonly class="form-control" id="ft_valor" name="ft_valor" type="text" onkeypress="return esEntero(event)">
                                                                            <input class="form-control" id="fijacion_der" name="fijacion_der" type="hidden">
                                                                            <input class="form-control" id="fijacion_izq" name="fijacion_izq" type="hidden">
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>DISPOSITIVOS DE OXIGENOTERAPIA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmi_toggle" name="vm" type="radio">
                                                                            <input type="text" hidden id="active_vmi" name="active_vmi" value="0">
                                                                            <label for="vmi_toggle">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmni_toggle" name="vm" type="radio" checked>
                                                                            <input type="text" hidden id="active_vmni" name="active_vmni" value="0">
                                                                            <label for="vmni_toggle">NO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="ft_valor">DESCRIPCION</label>
                                                                            <input readonly class="form-control" id="vm_desc" name="vm_desc" type="text">
                                                                            <input class="form-control" id="dispositivo_oxigenoterapia" name="dispositivo_oxigenoterapia" type="hidden">
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>DISPOSITIVO DE OXIGENOTERAPIA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmi_toggle" name="vm" type="radio">
                                                                            <input type="text" hidden id="active_vmi" name="active_vmi" value="0">
                                                                            <label for="vmi_toggle">SI</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="vmni_toggle" name="vm" type="radio">
                                                                            <input type="text" hidden id="active_vmni" name="active_vmni" value="0">
                                                                            <label for="vmni_toggle">NO</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="ft_valor">DETALLAR</label>
                                                                            <input readonly class="form-control" id="vm_desc" name="vm_desc" type="text">
                                                                            <input class="form-control" id="vmi" name="vmi" type="hidden">
                                                                            <input class="form-control" id="vmni" name="vmni" type="hidden">
                                                                        </div>
                                                                    </div> --}}
                                                                    {{-- <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span class=""><b>MODALIDAD:ASIS./CONTR.</b></span>
                                                                            <input type="hidden" name="modalidad" id="modalidad_final">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_presion" name="md" type="radio">
                                                                            <label for="md_presion">PRESION</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input class="" id="md_volumen" name="md" type="radio">
                                                                            <label for="md_volumen">VOLUMEN</label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOLUMEN TIDAL</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="volumen_tiral" name="volumen_tiral" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PEEP</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" name="peed" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>FR (M/P) ?</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="frecuencia_m" name="frecuencia_m" onkeypress="return esEntero(event)">
                                                                            <span style="margin: 0 5px;">/</span>
                                                                            <input type="text" class="form-control" id="frecuencia_p" name="frecuencia_p" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>VOL/MIN</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="vol_min" name="vol_min" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>T. INSPIRACION:T. EXPIRACION</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="i_e" name="i_e">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>PRESION SOPORTE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="presion_soporte" name="presion_soporte" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. MAXIMA</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_maxima" name="p_maxima">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. LIMITE</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_limite" name="p_limite">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>CO2</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="co2" name="co2">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>P. PICCO</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="p_picco" name="p_picco">
                                                                        </div>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni" id="tab3defaultMonitoreo">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <div class="row col-md-12">
                                                                        <button type="button" class="btn btn-info btn-xs" id="addInfusion">Agregar Infusiones</button>
                                                                    </div>
                                                                    <div class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                                                        <table id="tblInfusiones" class="display">
                                                                            <thead></thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane tabMoni" id="tab4defaultMonitoreo">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span style="color: green"><b>DIURESIS</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="diurisis_horaria" name="diurisis_horaria" onkeypress="return esEntero(event)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 m-respiratorio">
                                                                        <div class="col-md-3">
                                                                            <span><b>HEMOGLUCOTEST</b></span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="hemoglucotest" name="hemoglucotest" onkeypress="return esEntero(event)">
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
                            <button type="submit" class="btn btn-success" id="registrarMonitoreo"
                                value="create">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>