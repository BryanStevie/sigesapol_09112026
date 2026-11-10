<div class="modal fade" id="modalListarHojaGrafica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 90%; max-width:none; height:90vh" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarHojaGrafica"></b>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ route('registrar_nota_ingreso_emergencia') }}"
                    <form id="FormularioListarHojaGrafica" name="FormularioListarHojaGrafica"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_hoja_grafica" name="id_hospitalizacion_hoja_grafica">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">

                                <fieldset style="border-collapse: collapse;padding:11px;">
                                    @include('frontend.emergencia_enfermeria.paciente_cabecera.paciente_cabecera')
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-body" id="collapseprestacionespaciente">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="tipo">TIPO</label>
                                                    <select class="form-control select2" style="width: 100%">
                                                        <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tipo_doc_medico">FECHA DE LA ATENCION</label>
                                                    <div tabindex="19" class="form-control" style="text-align:left;">
                                                    </div>
                                                </div>




                                                <div class="col-md-2">
                                                    <label>PROCESO</label><br>
                                                    <a href="javascript:void(0)" aria-hidden="true"
                                                        data-toggle="tooltip"
                                                        class="btn btn-success btn-sm btn-block crearHojaGrafica"
                                                        id="crearHojaGrafica">
                                                        NUEVO REGISTRO
                                                    </a>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>GRÁFICA</label><br>
                                                    <a href="javascript:void(0)" aria-hidden="true"
                                                        data-toggle="tooltip"
                                                        class="btn btn-success btn-sm  btn-block visualizargraficoHojaGrafica"
                                                        id="visualizargraficoHojaGrafica"> VISUALIZAR
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset style="border-collapse: collapse;padding:11px;">

                                    <div class="panel panel-info panel-bordered panel-sm">

                                        <div class="panel-body" id="collapseprestacionespaciente">

                                            <div id="global">
                                                <div class="table-responsive">
                                                    <table id="tblEmergenciaTriaje"
                                                        class="table table-striped table-list-search dataTable no-footer"
                                                        style="width:100%; font-size: 12px; border-collapse: collapse;">
                                                        <thead>
                                                            <tr>

                                                                @if ($id_user_consultorio != 17)
                                                                    <th
                                                                        style="width: 2px; text-align: middle; vertical-align: middle;">
                                                                        N</th>
                                                                @endif
                                                                <th
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                    FECHA/HORA<br>REGISTRO</th> {{-- FECHA DE ATENCION DEBE VENIR DEL REGISTRO CREADO --}}
                                                                <th
                                                                    style="width: 10px; text-align: middle; vertical-align: middle;">
                                                                    PROFESIONAL <br>DE SALUD <br>REPONSABLE</th>
                                                                {{-- NOMBRE DE PROFESIONAL DEBE VENIR DEL REGISTRO CREADO --}}
                                                                <th
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                    UBICACION</th>
                                                               
                                                                <th
                                                                    style="width: 2px;text-align: middle; vertical-align: middle;">
                                                                    RESP</th>
                                                                <th
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                    PULSO</th>
                                                                    <th
                                                                
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                    P ART</th>
                                                                    <th
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                    TEMP</th>
                                                                    <th
                                                                    style="width: 6px;text-align: middle; vertical-align: middle;">
                                                                     PESO</th>
                                                               
                                                                
                                                                   
                                                                   <th
                                                                
                                                                   style="width: 6px;text-align: left; vertical-align: middle;">
                                                                   DIURESIS</th>
                                                                   <th
                                                                
                                                                   style="width: 6px;text-align: left; vertical-align: middle;">
                                                                   DEPOSICION</th>
                                                                   <th
                                                                
                                                                   style="width: 6px;text-align: left; vertical-align: middle;">
                                                                   DRENAJE</th>
                                                                   <th
                                                                
                                                                   style="width: 6px;text-align: left; vertical-align: middle;">
                                                                   TURNO</th>
                                                                   <th
                                                                
                                                                   style="width: 6px;text-align: left; vertical-align: middle;">
                                                                   BR POR TURNO</th>
                                                                <th
                                                                
                                                                    style="width: 6px;text-align: left; vertical-align: middle;">
                                                                    FUNCIONES</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="bodyEmergenciaTriaje">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                        </div>
                    </div><br>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
