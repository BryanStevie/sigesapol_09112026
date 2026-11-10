<div class="modal fade" id="modalHistorialEnfermeria" tabindex="-1" role="dialog"
    aria-labelledby="modalHistorialEnfermeriaLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 12px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>Historial Enfemería</b>
            </div>
            <div class="modal-body">
                <div class="panel panel-info panel-bordered panel-sm" id="panelInfoPacienteHistory"
                    style="display: none;">
                    <div class="panel-heading text-center">
                        <b>DATOS DEL PACIENTE</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                <label class="control-label">NRO DOCUMENTO</label>
                                <input class="form-control input-sm" type="text" name="mh_nro_doc_ident"
                                    id="mh_nro_doc_ident" readonly>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                <label>NOMBRE</label>
                                <input class="form-control input-sm" type="text" name="mh_nombre" id="mh_nombre"
                                    readonly>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                <label>APELLIDOS</label>
                                <input class="form-control input-sm" type="text" name="mh_apellidos"
                                    id="mh_apellidos" readonly>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                <label>SEXO</label>
                                <input class="form-control input-sm" type="text" name="mh_sexo" id="mh_sexo"
                                    readonly>
                            </div>
                            {{-- <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                      <label>EDAD</label>
                       <input class="form-control input-sm" type="text" name="mh_edad" id="mh_edad" readonly>
                  </div> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" style="padding: 0 5px;">
                            <li class="active"><a href="#tab300default" data-toggle="tab"
                                    style="font-size:12px">INGRESOS</a></li>
                            <li><a href="#tab301default" data-toggle="tab" style="font-size:12px">ATENCIONES MEDICAS</a>
                            </li>
                            <li><a href="#tab302default" data-toggle="tab" style="font-size:12px">EXAMENES DE
                                    LABORATORIO</a></li>
                            <li><a href="#tab303default" data-toggle="tab" style="font-size:12px">EXAMENES DE
                                    IMAGENOLOGIA</a></li>
                            <li><a href="#tab304default" data-toggle="tab" style="font-size:12px">INTERCONSULTA</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab300default">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tableHistoryEnfermeriaEmer" class="table table-striped"
                                        style="width: 100%; font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>TIPO ATENCIÓN</th>
                                                <th>FECHA INGRESO</th>
                                                <th>HORA INGRESO</th>
                                                <th>MEDICO</th>
                                                <th>SERVICIO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab301default">
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div id='divHistorial'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane fade" id="tab302default">
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div id='divLaboratorio'></div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane fade" id="tab303default">
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div id='divRadiologico'></div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane fade" id="tab304default">
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div id='divInterconsultaH'></div>
                                        </div>
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
