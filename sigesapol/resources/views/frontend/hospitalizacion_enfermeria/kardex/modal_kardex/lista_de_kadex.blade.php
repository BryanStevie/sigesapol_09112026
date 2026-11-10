<!-- Modal -->
<div class="modal fade" id="modalListarKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 12px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarKardex" name="FormularioListarKardex" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion" name="id_hospitalizacion">
                    <input type="hidden" id="id_asegurado" name="id_asegurado">
                    <input type="hidden" id="id_establecimiento" name="id_establecimiento">
                    <input type="hidden" id="id_prestacion" name="id_prestacion">
                    <input type="hidden" id="id_kardex" name="id_kardex">
                    
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading text-center">
                                <b>DATOS DEL PACIENTE</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-5">
                                        <label>DNI-PACIENTE:</label>
                                        <input class="form-control input-sm" type="text" name="mk_paciente" id="mk_paciente" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1">
                                        <label>G.S.:</label>
                                        <input class="form-control input-sm" type="text" name="mk_gs" id="mk_gs" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1">
                                        <label>ALERGÍA:</label>
                                        <input class="form-control input-sm" type="text" name="mk_alergia" id="mk_alergia" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1">
                                        <label>EDAD:</label>
                                        <input class="form-control input-sm" type="text" name="mk_edad" id="mk_edad" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2">
                                        <label>SEXO :</label>
                                        <input class="form-control input-sm" type="text" name="mk_sexo" id="mk_sexo" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2">
                                        <label>H.C.:</label>
                                        <input class="form-control input-sm" type="text" name="mk_hc" id="mk_hc" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Cabecera de pacientes -->
                        {{-- @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') --}} 
                    </fieldset>
                    
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                                <div class="row" id="btnCreateKardexContainer" style="display: none">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2"></div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2"></div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2"></div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2"></div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2"></div>
                                    {{-- <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <label for="tipo">FECHA INICIAL</label>
                                        <input type="text" class="form-control datepicker input-sm" name="kardex_filter_fecha_inicio" id="kardex_filter_fecha_inicio" autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <label for="tipo">FECHA FINAL</label>
                                        <input type="text" class="form-control datepicker input-sm" name="kardex_filter_fecha_fin" id="kardex_filter_fecha_fin" autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <label for="tipo">TIPO DE ATENCIÓN</label>
                                        <select class="form-control input-sm" id="kardex_filter_tipo" name="kardex_filter_tipo" disabled>
                                            <option value="1">EMERGENCIA</option>
                                            <option value="2">HOSPITALIZACION</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <label for="kardex_filter_turno">TURNO</label>
                                        <select class="form-control input-sm" id="kardex_filter_turno" name="kardex_filter_turno">
                                        </select>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <br>
                                        <button type="button" id="buscarKardex" class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> BUSCAR</button>
                                    </div> --}}
                                    <div class="form-group col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <button type="button" id="crearKardex" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO KARDEX</button>
                                    </div>
                                    <hr>
                                </div>
                                <div class="row" style="margin: 0px" id="tableListKardexContainer" style="display: none">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="listKardexsTable" class="table table-striped" style="width: 100%; font-size: 12px">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>FECHA/HORA KARDEX</th>
                                                        <th>DATOS LA ATENCIÓN</th> 
                                                        <th>UBICACIÓN</th> 
                                                        <th>ENFERMERA QUE REALIZO EL KARDEX</th> 
                                                        <th>VER/EDITAR</th>
                                                        <th>ANULAR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>