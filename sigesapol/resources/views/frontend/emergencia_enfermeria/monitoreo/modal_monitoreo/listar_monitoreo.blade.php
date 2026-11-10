<!-- Modal -->
<div class="modal fade" id="modalListarMonitoreo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarMonitoreo"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarMonitoreo" name="FormularioMonitoreo" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_listado_monitoreo" name="id_hospitalizacion_listado_monitoreo">
                    <input type="hidden" id="id_asegurado_listado" name="id_asegurado_listado">
                    <input type="hidden" id="id_enfermeria_moni" name="id_enfermeria_moni">
                    <input type="hidden" id="doc_firmar_monitoreo" value="" />
                    <input type="hidden" id="opt_firma_monitoreo" value="" />
                    <input type="hidden" id="argumentos_monitoreo" value="" />
                    <input type="hidden" id="id_monitoreo_firma" value="" />
                    <input type="hidden" id="fecha_hora_firma" value="" />
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm" id="datosPacienteParaCargarMonitoreo"></div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="tipo">TIPO</label>
                                            <select disabled class="form-control select2 w-full" style="width: 100%" disabled> 
                                                <option value="EMERGENCIA" selected>EMERGENCIA</option>
                                                <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"> 
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" name="fecha_monitoreo_inicial"
                                                id="fecha_monitoreo_inicial" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-2"> 
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" name="fecha_monitoreo_final"
                                                id="fecha_monitoreo_final" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">TURNO</label>
                                            <select class="form-control select2 w-full" style="width: 100%" name="listado_turno"> 
                                                <option value="2">MAÑANA</option>
                                                <option value="3">TARDE</option>
                                                <option value="4">NOCHE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">BUSCAR</label>
                                            <br>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm crearKardex" id="listarMonitoreoDetalles"> BUSCAR
                                            </a>
                                        </div>
                                        <div class="col-md-2">
                                            <label>PROCESO</label><br>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm crearKardex" id="crearMonitoreo"> NUEVO DETALLE
                                            </a>
                                            <span id="diagnosticosPrestacionPrevia" hidden></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2" style="margin-top: 10px">
                                            <label for="tipo">EXPORTAR DIARIO (en base a FECHA FINAL)</label>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm crearKardex" id="exportarMonitoreoPorDia"> GENERAR
                                            </a>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group"><br>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="" class="table table-striped table-list-search">
                                                    <thead>
                                                        <tr>
                                                            <th>N</th>
                                                            <th>FECHA / HORA REGISTRO</th>
                                                            <th>UBICACION</th>
                                                            <th>PROFESIONAL DE SALUD RESPONSABLE</th>
                                                            <th>BALANCE HIDRICO</th>
                                                            <th>VER/EDITAR</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblMonitoreos">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>