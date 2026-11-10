<!-- Modal -->
<div class="modal fade" id="modalShowDetailKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%;">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important; border-top-left-radius: 5px; border-top-right-radius: 5px">
                <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>VER DETALLES KARDEX</b>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="alert_kardex_anulado" style="display: none">El kardex ha sido anulado y no se puede realizar ninguna modificación</div>
                <fieldset>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_kardex" id="id_kardex">
                    <input type="hidden" id="argumentos_kardex" value="" />
                    <input type="hidden" name="doc_firmar" id="doc_firmar">
                    <input type="hidden" name="opt_firma" id="opt_firma">
                    <input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
                    <input type="hidden" name="flag_firmar" id="flag_firmar" value="1">
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>DATOS DEL PACIENTE</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                    <label class="control-label">DOCUMENTO DE IDENTIDAD</label>
                                    <input class="form-control input-sm" type="text" name="mkr_nro_doc_ident_detalle" id="mkr_nro_doc_ident_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                    <label>PACIENTE</label>
                                    <input class="form-control input-sm" type="text" name="mkr_asegurado_detalle" id="mkr_asegurado_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                    <label>PARENTESCO</label>
                                        <input class="form-control input-sm" type="text" name="mkr_parentesco_detalle" id="mkr_parentesco_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                    <label>SEXO</label>
                                        <input class="form-control input-sm" type="text" name="mkr_sexo_detalle" id="mkr_sexo_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                    <label>EDAD</label>
                                        <input class="form-control input-sm" type="text" name="mkr_edad_detalle" id="mkr_edad_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                    <label>T. SANG</label>
                                        <input class="form-control input-sm" type="text" name="mkr_sangre_detalle" id="mkr_sangre_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                    <label>H. CLINICA</label>
                                        <input class="form-control input-sm" type="text" name="mkr_historia_clinica_detalle" id="mkr_historia_clinica_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-1">
                                    <label>ALERGÍA</label>
                                        <input class="form-control input-sm" type="text" name="mkr_alergia_detalle" id="mkr_alergia_detalle" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>DATOS DE LA ATENCIÓN</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                    <label>TIPO DE ATENCIÓN</label>
                                    <input class="form-control input-sm" type="text" name="mkr_tipo_atencion_detalle" id="mkr_tipo_atencion_detalle" value="HOSPITALIZACION" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                    <label>FECHA DE ATENCIÓN</label>
                                    <input class="form-control input-sm" type="text" name="mkr_fecha_atencion_detalle" id="mkr_fecha_atencion_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2">
                                    <label>HORA DE ATENCIÓN</label>
                                        <input class="form-control input-sm" type="text" name="mkr_hora_atencion_detalle" id="mkr_hora_atencion_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                    <label>SERVICIO</label>
                                    <input class="form-control input-sm" type="text" name="mkr_prioridad_atencion_detalle" id="mkr_prioridad_atencion_detalle" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3">
                                    <label for="mkr_turno">TURNO</label>
                                    <input class="form-control input-sm" type="text" name="mkr_turno_detalle" id="mkr_turno_detalle" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{-- <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>DIAGNOSTICOS DE LA ATENCIÓN</b>
                        </div>
                        <div class="panel-body">
                            <table id="mkr_table_diagnostivos_atencion_detalle" class="table table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th>TIPO</th>
                                        <th>CLASIFICACIÓN</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                        </div>
                        <div class="panel-body">
                            <div class="row" style="padding: 0px 15px">
                                <div class="form-group col-12">
                                    <label for="mkr_tratamiento">Tratamiento Auxiliar</label>
                                    <textarea id="mkr_tratamiento_detalle" class="form-control" name="mkr_tratamiento_detalle" rows="2" readonly></textarea>
                                </div>
                            </div> 
                        </div>
                    </div>
                </fieldset> --}}
                
                <fieldset>
                    <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs" id="tabs_kardex_register_detalle">
                                <li class="active">
                                    <a href="#mkr_tab_kardex_detalle" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                        <b><i class="fa fa-list-ol" aria-hidden="true"></i> KARDEX</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="#mkr_tab_examenes_auxiliares_detalle" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                        <b><i class="fa fa-file-text-o" aria-hidden="true"></i> EXAMENES AUXILIARES</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="#mkr_tab_cuidado_enfermero_detalle" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                        <b><i class="fa fa-user-md" aria-hidden="true"></i> CUIDADO DEL ENFERMERO</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="#mkr_tab_interconsulta_detalle" data-toggle="tab" style="font-size:12px" aria-expanded="true">
                                        <b><i class="fa fa-phone" aria-hidden="true"></i> INTERCONSULTA</b>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body" style="padding-bottom: 0px !important;">
                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="mkr_tab_kardex_detalle">
                                    <div class="panel panel-info panel-bordered panel-sm" style="background-color: #F0F0F0">
                                        <div class="panel-body">
                                            <table id="tableMedicamentosKardexDetalle" class="table table-striped" style="font-size: 12px">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%">MEDICAMENTOS </th>
                                                        <th style="width: 30%">ENFERMER@ QUE REALIZO EL KARDEX</th>
                                                        <th style="width: 20%">VIA</th>
                                                        <th style="width: 10%">FRECUENCIA</th>
                                                        <th style="width: 10%">DIAS DE ADMINISTRACION</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="mkr_tab_examenes_auxiliares_detalle">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                            <b>EXAMENES DE LABORATORIO</b>
                                        </div>
                                        <div class="panel-body">
                                            <table id="tableExamAuxLabDetalle" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 15%">FECHA</th>
                                                        <th style="width: 10%">HORA</th>
                                                        <th style="width: 41%">NOMBRE EXAMEN</th>
                                                        <th style="width: 15%">TOMA DE MUESTRA</th>
                                                        <th style="width: 7%">RESULTADO</th>
                                                        <th style="width: 7%">SIN REACTIVO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading text-center">
                                            <b>EXAMENES DE IMAGENOLOGÍA</b>
                                        </div>
                                        <div class="panel-body">
                                            <table id="tableExamAuxImagDetalle" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%">FECHA</th>
                                                        <th style="width: 10%">HORA</th>
                                                        <th style="width: 70%">NOMBRE EXAMEN</th>
                                                        <th style="width: 10%">TOMA DE IMAGEN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="mkr_tab_cuidado_enfermero_detalle">
                                    <fieldset class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Control de Funciones Vitales (CFV) :</label>
                                            <div class="col-sm-9">
                                                <span id="control_funciones_vitales_detalle"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Balance Hídrico y Electrolítico (BHE) :</label>
                                            <div class="col-sm-9">
                                                <span id="barrera_hematoencefalica_detalle"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Cabecera :</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control input-sm" id="cabecera_detalle" name="cabecera_detalle" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Oxigenoterapia : </label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control input-sm" id="id_oxigeno_terapia_detalle" name="id_oxigeno_terapia_detalle" readonly>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control input-sm" id="oxigenoterapia_cantidad_detalle" name="oxigenoterapia_cantidad_detalle" readonly>
                                                </div>
                                                </div>    
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Movilización cada 2 horas : </label>
                                            <div class="col-sm-9">
                                                <span id="movilización_detalle"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Hemoglucotest :</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control input-sm" id="hemoglucotest_detalle" name="hemoglucotest_detalle" readonly>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="tab-pane fade" id="mkr_tab_interconsulta_detalle">
                                    <table id="tableInterconsultaDetalle" class="table table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 55%">NOMBRE DEL SERVICIO</th>
                                                <th style="width: 20%">FECHA ENVIO</th>
                                                <th style="width: 20%">FECHA RESPUESTA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="modal-footer">
                <div class="row" style="margin-bottom: 20px">
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id="btnOkContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>