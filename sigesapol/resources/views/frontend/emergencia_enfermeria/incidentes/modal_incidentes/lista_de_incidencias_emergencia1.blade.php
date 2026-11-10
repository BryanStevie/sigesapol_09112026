<!-- Modal -->
<div class="modal fade" id="modalListarIncidentes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarIncidentes"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarIncidentes" name="FormularioListarIncidentes" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_incidencia" name="id_hospitalizacion_incidencia">
                    <input type="hidden" id="id_asegurado" name="id_asegurado">
                    <input type="hidden" id="id_servicio" name="id_servicio">
                    <input type="hidden" id="nombre_servicio" name="nombre_servicio">
                    <input type="hidden" id="id_nota_enfermeria" name="id_nota_enfermeria">
                  
                    <input type="hidden" id="numero_doc2">
                    <input type="hidden" id="nombres2">
                    <input type="hidden" id="tipo_sangre2">
                    <input type="hidden" id="edad2">
                    <input type="hidden" id="sexo2">
                    <input type="hidden" id="hc2">


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
                                                    <span id="numero_doc_inc"></span> - <span id="nombres_inc"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">G.S.: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="tipo_sangre_inc" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">ALERGIA: </label> <br>
                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                                                title="Alergia" class="listarAlergias" id="listarAlergias"><label id="" style="font-size: 14px;cursor: pointer;">SI</label></a>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">EDAD: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="edad_inc" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">SEXO: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="sexo_inc" ></span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">HC: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="hc_inc" ></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') --}}
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="tipo">TIPO</label>
                                            <select class="form-control input-sm select2 w-full" onchange="getListadoIncidencias()" style="width: 100%;" name="tipo_atenciones" id="tipo_atenciones"disabled>
                                                <option value="2" selected>EMERGENCIA</option>
                                                <!-- Aquí podrían ir otras opciones si es necesario -->
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" name="fecha_incidencia_inicial"
                                                id="fecha_incidencia_inicial" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" name="fecha_incidencia_final"
                                                id="fecha_incidencia_final" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>PROCESO</label><br>
                                           
                                                <a href="javascript:void(0)"  data-toggle="tooltip" title="Nueva Incidencia" class="btn btn-success btn-sm crearIncidencias"
                                                    id="crearIncidencias" style="pointer-events:none" disabled> Nueva Incidencia
                                                </a> 
                                          
                                                {{-- <input type="submit" id="btn-prueba" class="btn btn-success btn-sm crearIncidencias" value="Nueva Incidencia" title="Nueva Incidencia" data-toggle="tooltip" disabled>  --}}
                                             
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset><br>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-list-search">
                                                <thead>
                                                    <tr>
                                                        <th>FECHA</th>
                                                        <th>SERVICIO</th>
                                                        <th>USUARIO</th>
                                                        <th>TIPO</th>
                                                        <th>FUNCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listaDatosIncidenciasEventosA">
                                                    {{-- @for($i = 1; $i <= 10; $i++) <tr>
                                                        <td>01/04/2023 - 02:20:42</td>
                                                        <td>DESCRIPCION DE OBSERVACIONES</td>
                                                        <td>PERSONAL QUE NOTIFICA</td>
                                                        @if ($i%2==0)
                                                        <td>HOSPITALIZACION</td>
                                                        @else
                                                        <td>EMERGENCIA</td>
                                                        @endif
                                                        <td>
                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Visualizar"
                                                                class="btn btn-success btn-xs verIncidentes"> <i
                                                                    class="fa fa-eye"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Editar"
                                                                class="btn btn-warning btn-xs editarIncidentes"> <i
                                                                    class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Generar PDF"
                                                                class="btn btn-danger btn-xs generarPDFIncidentes"> <i
                                                                    class="fa fa-file-pdf-o"></i>
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