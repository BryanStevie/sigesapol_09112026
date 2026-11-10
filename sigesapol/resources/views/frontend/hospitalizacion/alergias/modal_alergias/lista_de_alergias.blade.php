<!-- Modal -->
<div class="modal fade" id="modalListarAlergias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarAlergias"></b>
            </div>


            <div class="modal-body">
                <form id="FormularioListarAlergias" name="FormularioListarAlergias" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_Alergias" name="id_hospitalizacion_Alergias">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label for="tipo">DNI - PACIENTE: </label> <br>
                                                <label style="font-size: 14px;">
                                                    <span id="numerodoc_al"></span> - <span id="nombres_al"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">G.S.: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="tiposangre_al" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">ALERGIA: </label> <br>
                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                                                title="Alergia" class="listarAlergias" id="listarAlergias_a"><label id="" style="font-size: 14px;cursor: pointer;">SI</label></a>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">EDAD: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="edad_al" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">SEXO: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="sexo_al" ></span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">HC: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="hc_al" ></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')   --}}
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="tipo">DETECTADO EN </label>
                                            <select class="form-control input-sm select2 w-full" onchange="getListadoAlergias()" style="width: 100%;" name="tipoatenciones" id="tipoatenciones"> </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">ESTADO </label>
                                            <select class="form-control select2" style="width: 100%" name="estado" id="estado" onchange="getListadoAlergias()">
                                                <option value="">TODOS</option>
                                                <option value="true">VALIDADO</option>
                                                <option value="false">NO VALIDADO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>PROCESO</label><br>
                                            <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip"
                                                class="btn btn-success btn-sm crearAlergias" id="crearAlergias" style="pointer-events:none" disabled> Nueva
                                                Alergia
                                            </a>
                                        </div> 
                                    </div>
                                </fieldset>
                                <fieldset><br>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="example4" class="table table-striped table-list-search">
                                                <thead>
                                                    <tr>
                                                        <th>FECHA DE DETECCIÓN</th>
                                                        <th>ALERGICO A</th>
                                                        <th>PERSONAL MÉDICO</th>
                                                        <th>DETECTADO EN</th>
                                                        <th>FUNCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listaAlergiasMedicas">
                                                    {{-- @for ($i = 1; $i <= 3; $i++) <tr>
                                                        <td>01/04/2023 - 02:20:42</td>
                                                        <td>ALERGIA 1</td>
                                                        <td>NOMBRE DEL MÉDICO</td>
                                                        @if ($i % 2 == 0)
                                                        <td>HOSPITALIZACION</td>
                                                        @else
                                                        <td>EMERGENCIA</td>
                                                        @endif
                                                        <td>
                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Ver Detalles"
                                                                class="btn btn-success btn-xs verAlergias">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Editar"
                                                                class="btn btn-success btn-xs editarAlergias">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Validar"
                                                                class="btn btn-danger btn-xs">
                                                                <i class="fa fa-thumbs-o-up"></i>
                                                            </a>
                                                        </td>
                                                        </tr>
                                                        @endfor --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>