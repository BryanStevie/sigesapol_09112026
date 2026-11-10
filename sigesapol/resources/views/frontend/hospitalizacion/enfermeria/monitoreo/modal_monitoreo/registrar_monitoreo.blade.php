<!-- Modal -->
<div class="modal fade" id="modalMonitoreo" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioMonitoreo"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioMonitoreo" name="FormularioMonitoreo" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id" name="id">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') 
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset><br>
                                <div class="panel with-nav-tabs panel-default"
                                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                    <div class="panel-heading">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab0defaultMonitoreo" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        MON. HEMODINAMICO</b></a></li>
                                            <li><a href="#tab1defaultMonitoreo" data-toggle="tab" style="font-size:12px"
                                                    aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                            aria-hidden="true"></i>
                                                        TRANSFUSIONES</b></a></li>
                                            <li><a href="#tab2defaultMonitoreo" data-toggle="tab" style="font-size:12px"
                                                    aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                            aria-hidden="true"></i>
                                                        PRONACION</b></a></li>
                                            <li><a href="#tab3defaultMonitoreo" data-toggle="tab" style="font-size:12px"
                                                    aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                            aria-hidden="true"></i>
                                                        VAL. DE LINEA ARTERIAL</b></a></li>
                                            <li><a href="#tab4defaultMonitoreo" data-toggle="tab" style="font-size:12px"
                                                    aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                            aria-hidden="true"></i>
                                                        ESCALAS</b></a></li>
                                            <li><a href="#tab5defaultMonitoreo" data-toggle="tab" style="font-size:12px"
                                                    aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                            aria-hidden="true"></i>
                                                        INVASIVOS</b></a></li>
                                        </ul>
                                    </div>
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade active in" id="tab0defaultMonitoreo">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table id="example"
                                                                class="table table-striped table-list-search"
                                                                style="margin-bottom: 0px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;">INDICADORES</th>
                                                                        <th style="text-align: center;">PARAMETROS
                                                                            NORMALES</th>
                                                                        <th style="text-align: center;">VALOR</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><b>GASTO CARDIACO: GC</b></td>
                                                                        <td style="text-align: center;">4-6 L/MIN</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>INDICE CARDIACO: IC</b></td>
                                                                        <td style="text-align: center;">2.2-4l/min/m2
                                                                        </td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>FRECUENCA CARDIACA: FC</b></td>
                                                                        <td style="text-align: center;">60-100 lpm</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>TIEMPO DE FLUJO CORREGIDO: TFC</b></td>
                                                                        <td style="text-align: center;">330-360 mseg
                                                                        </td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>VOLUMEN LATIDO VL</b></td>
                                                                        <td style="text-align: center;">50-10 ml</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>INDICE RESISTENCIA VASCULAR SISTÉMICA:
                                                                                VRS</b> </td>
                                                                        <td style="text-align: center;">1200-2400
                                                                            dynas/m2</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>RESISTENCIA VASCULAR SISTEMICA: RVS</b>
                                                                        </td>
                                                                        <td style="text-align: center;">800-1200 dyna
                                                                        </td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>PRESION VENOSA CENTRAL: PVC mmHg</b></td>
                                                                        <td style="text-align: center;">-</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>IVRS</b> </td>
                                                                        <td style="text-align: center;">1200-2400
                                                                            dynas/m2</td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab1defaultMonitoreo">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table id="example"
                                                                class="table table-striped table-list-search"
                                                                style="margin-bottom: 0px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;">HEMODERIVADOS
                                                                        </th>
                                                                        <th style="text-align: center;">ACTUAL</th>
                                                                        <th style="text-align: center;">ACUMULADO</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><b>PAQUETE GLOBULAR</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>CRIOPRECIPITADOS</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>PLAQUETAS</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>PLASMA FRESCO CONGELA.</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>AFERESIS</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>POOL PLAQ.</b></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                style="height: 30px !important;"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab2defaultMonitoreo">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Agregar Apreciación"
                                                                class="btn btn-success btn-sm"> 
                                                                <i class="fa fa-plus" aria-hidden="true"></i> AGREGAR PRONACIÓN 
                                                            </a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <table id="example" class="table">
                                                                <tbody>
                                                                    @for($i = 1; $i <= 5; $i++) 
                                                                        <tr>
                                                                            <td style="text-align: center; padding-top: 15px;"><b>{{$i}}</b></td>
                                                                            <td><input type="text"
                                                                                    class="form-control datepicker"
                                                                                    style="height: 28px !important;"
                                                                                    name="fecha_kardex_traq_susp"
                                                                                    id="fecha_kardex_traq_susp"
                                                                                    autocomplete="off"
                                                                                    value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                            </td>
                                                                            <td><input type="time" class="form-control"
                                                                                    style="height: 28px !important;" /></td>
                                                                            <td>
                                                                                <a href="javascript:void(0)" aria-hidden="true"
                                                                                    data-toggle="tooltip" title="Finalizar Pronación"
                                                                                    class="btn btn-success btn-xs"> <i
                                                                                        class="fa fa-check"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td><input type="text"
                                                                                    class="form-control datepicker"
                                                                                    style="height: 28px !important;"
                                                                                    name="fecha_kardex_traq_susp"
                                                                                    id="fecha_kardex_traq_susp"
                                                                                    autocomplete="off"
                                                                                    value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                            </td>
                                                                            <td><input type="time" class="form-control"
                                                                                    style="height: 28px !important;" /></td>
                                                                        </tr> 
                                                                    @endfor
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div> 
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab3defaultMonitoreo">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table id="example"
                                                                class="table table-striped table-list-search"
                                                                style="margin-bottom: 0px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;">ARTERIA</th>
                                                                        <th style="text-align: center;">OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><b>RETORNO SANGRE ARTERIAL</b></td>
                                                                        <td>
                                                                            <select class="form-control select2"
                                                                                style="width: 100%">
                                                                                <option value="">SI</option>
                                                                                <option value="">NO</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>PULSO ARTERIAL</b></td>
                                                                        <td>
                                                                            <select class="form-control select2"
                                                                                style="width: 100%">
                                                                                <option value="">DEBIL</option>
                                                                                <option value="">FUERTE</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>COLORACION DE PIEL</b></td>
                                                                        <td>
                                                                            <select class="form-control select2"
                                                                                style="width: 100%">
                                                                                <option value="">CIANOSIS</option>
                                                                                <option value="">PALIDEZ</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>UBICACION</b></td>
                                                                        <td>
                                                                            <select class="form-control select2"
                                                                                style="width: 100%">
                                                                                <option value="">RADIAL</option>
                                                                                <option value="">PEDIAL</option>
                                                                                <option value="">BRAQUIAL</option>
                                                                                <option value="">FEMORAL</option>
                                                                                <option value="">PEDIAL</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>LLENADO CAPILAR <2 ¨</b>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control select2"
                                                                                style="width: 100%">
                                                                                <option value="">SI</option>
                                                                                <option value="">NO</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab4defaultMonitoreo">
                                                <fieldset>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <br>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12"
                                                                            style="text-align: center;">
                                                                            <b>PUNTAJE SEGÚN ESCALA TISS</b>
                                                                        </div>
                                                                        <table id="example"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <tbody>
                                                                                <tr>  
                                                                                    <td style="width: 30%;">
                                                                                        <div class="input-group">
                                                                                            <input type="text" 
                                                                                            id="putaje_calculado_tiss"
                                                                                            name="putaje_calculado_tiss"
                                                                                                class="form-control putaje_calculado_tiss"
                                                                                                style="height: 30px !important;"
                                                                                                disabled>
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                <button
                                                                                                    class="btn btn-success btn-xs calcularEscalaTiss"
                                                                                                    id="calcularEscalaTiss"
                                                                                                    name="calcularEscalaTiss"
                                                                                                    style="height: 30px !important;"
                                                                                                    type="button">
                                                                                                    <i class="fa fa-calculator"
                                                                                                        style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control valoracion_calculado_tiss" 
                                                                                        id="valoracion_calculado_tiss"
                                                                                        name="valoracion_calculado_tiss"
                                                                                        style="height: 30px !important;" disabled>
                                                                                    </td>
                                                                                </tr> 
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12"
                                                                            style="text-align: center;">
                                                                            <b>PUNTAJE SEGÚN ESCALA NORTON</b>
                                                                        </div>
                                                                        <table id="example"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <tbody>
                                                                                <tr>  
                                                                                    <td style="width: 30%;">
                                                                                        <div class="input-group">
                                                                                            <input type="text" 
                                                                                            id="putaje_calculado_norton"
                                                                                            name="putaje_calculado_norton"
                                                                                                class="form-control putaje_calculado_norton"
                                                                                                style="height: 30px !important;"
                                                                                                disabled>
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                <button
                                                                                                    class="btn btn-success btn-xs calcularEscalaNorton"
                                                                                                    id="calcularEscalaNorton"
                                                                                                    style="height: 30px !important;"
                                                                                                    type="button">
                                                                                                    <i class="fa fa-calculator"
                                                                                                        style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control valoracion_calculado_norton" 
                                                                                        id="valoracion_calculado_norton"
                                                                                        name="valoracion_calculado_norton"
                                                                                        style="height: 30px !important;" disabled>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12"
                                                                            style="text-align: center;">
                                                                            <b>PUNTAJE SEGÚN ESCALA CPOT</b>
                                                                        </div>
                                                                        <table id="example"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <tbody>
                                                                                <tr>  
                                                                                    <td style="width: 30%;">
                                                                                        <div class="input-group">
                                                                                            <input type="text" 
                                                                                            id="putaje_calculado_cpot"
                                                                                            name="putaje_calculado_cpot"
                                                                                                class="form-control putaje_calculado_cpot"
                                                                                                style="height: 30px !important;"
                                                                                                disabled>
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                <button
                                                                                                    class="btn btn-success btn-xs calcularEscalaCpot"
                                                                                                    id="calcularEscalaCpot"
                                                                                                    style="height: 30px !important;"
                                                                                                    type="button">
                                                                                                    <i class="fa fa-calculator"
                                                                                                        style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control valoracion_calculado_cpot" 
                                                                                        id="valoracion_calculado_cpot"
                                                                                        name="valoracion_calculado_cpot"
                                                                                        style="height: 30px !important;" disabled>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>  
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12"
                                                                            style="text-align: center;">
                                                                            <b>PUNTAJE SEGÚN ESCALA MORSE</b>
                                                                        </div>
                                                                        <table id="example"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <tbody>
                                                                                <tr>  
                                                                                    <td style="width: 30%;">
                                                                                        <div class="input-group">
                                                                                            <input type="text" 
                                                                                            id="putaje_calculado_morse"
                                                                                            name="putaje_calculado_morse"
                                                                                                class="form-control putaje_calculado_morse"
                                                                                                style="height: 30px !important;"
                                                                                                disabled>
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                <button
                                                                                                    class="btn btn-success btn-xs calcularEscalaMorse"
                                                                                                    id="calcularEscalaMorse"
                                                                                                    style="height: 30px !important;"
                                                                                                    type="button">
                                                                                                    <i class="fa fa-calculator"
                                                                                                        style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control valoracion_calculado_morse" 
                                                                                        id="valoracion_calculado_morse"
                                                                                        name="valoracion_calculado_morse"
                                                                                        style="height: 30px !important;" disabled>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody> 
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab5defaultMonitoreo">
                                                <fieldset>
                                                    
                                                    <div class="col-md-12" >
                                                        <form id="FormularioPunto" name="FormularioPunto" enctype="multipart/form-data" method="GET"> 
                                                        <div class="form-group">  
                                                            <div class="col-md-6"> 
                                                                <label for="tipo">SELECCIONE PROCESO</label>
                                                                <select class="form-control input-sm" id="tipo_proceso" name="tipo_proceso" style="width: 100%;"> 
                                                                </select>  
                                                            </div>
                                                            <div class="col-md-6"> 
                                                                <label for="tipo">TAMAÑO DE PUNTOS</label>
                                                                <select class="form-control input-sm" id="tamanio_proceso" name="tamanio_proceso" style="width: 100%;"> 
                                                                </select> 
                                                            </div>   
                                                            <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" >
                                                            <input type="hidden" name="tipo_registro" id="tipo_registro" value="MONITOREO">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>  
                                                            <div class="col-md-8" style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                                <div class="form-group">
                                                                    <div class="grid-block" style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                    <canvas id="plano" name="plano" width="512" height="512"></canvas>
                                                                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="x" id="x">
                                                                    <input type="hidden" name="y" id="y"> 
                                                                    <div id="control_div_puntos_monitoreo" name="control_div_puntos_monitoreo"> 
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-4" >
                                                              <ul class="leyenda_grafico" id="control_div_puntos_monitoreo_leyenda" name="control_div_puntos_monitoreo_leyenda"> 
                                                              </ul>
                                                            </div> 
                                                        </form>
                                                    </div>  
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left"
                                data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" id="registrarMonitoreo"
                                value="create">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>