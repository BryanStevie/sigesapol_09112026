<!-- Modal -->
<div class="modal fade" id="modalHojaGrafica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioHojaGrafica"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioHojaGrafica" name="FormularioHojaGrafica" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id" name="id">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')  
                                    <div class="form-group">
                                        <div class="col-md-8">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">TURNO</label>
                                            <select class="form-control select2" style="width: 100%">
                                                <option value="M">MAÑANA</option>
                                                <option value="T">TARDE</option>
                                                <option value="N">NOCHE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">FECHA</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;"
                                                name="fecha_registro_hoja_grafica_inicial"
                                                id="fecha_registro_hoja_grafica_inicial" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>

                                <fieldset style="margin-bottom: 0px !important;">
                                    <div class="col-md-12">
                                        <br>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0px !important">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>MONITOREO HEMODINÁMICO</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>PUPILA (D)</b></td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        style="width: 100%;height: 30px !important;">
                                                                        @for($i = 0; $i < 10; $i++) <option
                                                                            value="{{$i}}">{{$i}}</option>
                                                                            @endfor
                                                                            <option value="NE">NE</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PUPILA (I)</b></td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        style="width: 100%;height: 30px !important;">
                                                                        @for($i = 0; $i < 10; $i++) <option
                                                                            value="{{$i}}">{{$i}}</option>
                                                                            @endfor
                                                                            <option value="NE">NE</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>GLASGOW</b></td>
                                                                <td>
                                                                    <input type="number" min="1" max="15"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>RASS</b></td>
                                                                <td>
                                                                    <input type="number" min="-5" max="4"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PR.ARTERIAL (PS)</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="300"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PR.ARTERIAL (PD)</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="300"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PR.ARTERIAL MED</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="300"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>FRE.CARDIACA</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="300"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr> 
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody> 
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_RITMO_CARDIACO" name="add_RITMO_CARDIACO" 
                                                                        class="btn btn-success btn-sm add_RITMO_CARDIACO"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> RITMO CARDIACO
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_RITMO_CARDIACO" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_RITMO_CARDIACO" name="cuerpo_RITMO_CARDIACO" class="cuerpo_RITMO_CARDIACO">
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>FRECUENCIA RESPIRATORIA</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>TEMPERATURA AXILAR</b></td>
                                                                <td>
                                                                    <input type="number" min="30" max="40"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PVC</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="30"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PIA</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="50"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PIC</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="30"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PRESIÓN DE CUFF</b></td>
                                                                <td>
                                                                    <input type="number" min="0" max="40"
                                                                        class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>MONITOREO RESPIRATORIO</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>VM</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VMNI</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>CNAF</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ESPONT</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr> 
                                                        </tbody>
                                                    </table> 
                                                    <table id="example" class="table table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody> 
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_MODO_MONITOREO" name="add_MODO_MONITOREO" 
                                                                        class="btn btn-success btn-sm add_MODO_MONITOREO"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> MODO
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_MODO_MONITOREO" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_MODO_MONITOREO" name="cuerpo_MODO_MONITOREO" class="cuerpo_MODO_MONITOREO">
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>FIO2</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>FR</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PEEP</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VTP</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VTE</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>FLUJO</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>LITROS</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VOL.MIN</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>I:E</b></td>
                                                                <td>
                                                                    <div class="col-md-12" style="padding-left: 0px !important;padding-right: 0px !important;">
                                                                        <div class="col-md-6" style="padding-left: 0px !important;padding-right: 5px !important;">
                                                                            <input value="" type="text"
                                                                                class="form-control"
                                                                                style="height: 28px !important;"
                                                                                required>
                                                                        </div>
                                                                        <div class="col-md-6" style="padding-left: 5px !important;padding-right: 0px !important;">
                                                                            <input value="" type="text"
                                                                                class="form-control"
                                                                                style="height: 28px !important;"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PRESIÓN SOPORTE</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>P.MÁXIMA</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>P.LÍMITE</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>P.CONTROL</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>P.INSPIRATORIO</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>P.PICCO</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>CO2</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SAT02</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>INDICE ROX</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarHojaGrafica"
                            value="create">Registrar</button>
                    </div>
            </div>
        </div>
        </form>
    </div>
</div>