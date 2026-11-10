<!-- Modal -->
<div class="modal fade" id="modalListar_interconsultaKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListar_interconsultaKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListar_interconsultaKardex" name="FormularioListar_interconsultaKardex"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_kardex" name="id_hospitalizacion_kardex">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') 
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <div class="col-md-12">
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab0defaultInterconsultaKardex"
                                                            data-toggle="tab" style="font-size:12px"
                                                            aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                                    aria-hidden="true"></i>
                                                                INTERCONSULTAS</b></a></li>
                                                    <li><a href="#tab1defaultInterconsultaKardex" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                AYUDA AL DIAGNÓTICO</b></a></li>
                                                    <li><a href="#tab2defaultInterconsultaKardex" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                INTERVENCIÓN QUIRÚGICA</b></a></li>
                                                    <li><a href="#tab3defaultInterconsultaKardex" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                HEMODIÁLISIS / UF</b></a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body" style="padding-bottom: 0px !important;">
                                                <div class="tab-content">

                                                    <div class="tab-pane fade active in"
                                                        id="tab0defaultInterconsultaKardex">
                                                        <fieldset>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>DESCRIPCIÓN DEL SERVICIO DE DESTINO
                                                                                </th>
                                                                                <th>FECHA DE ENVÍO</th>
                                                                                <th>FECHA DE RESPUESTA</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @for($i = 1; $i <= 5; $i++) <tr>
                                                                                <td>MEDICINA FISICA Y REHABILITACION
                                                                                    {{$i}}</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                                </tr>
                                                                                @endfor
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab1defaultInterconsultaKardex">
                                                        <fieldset>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>REGISTRO DE EXAMENES DE AYUDA AL
                                                                                    DIAGNÓTICO</th>
                                                                                <th>FECHA DE ENVÍO</th>
                                                                                <th>FECHA DE RESPUESTA</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>TAC</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>ECONGRAFÍA</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>RESONANCIA MAGNETICA</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                                <td>01/04/2023 - 02:20:42</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab2defaultInterconsultaKardex">
                                                        <fieldset>  
                                                            <div class="col-md-12">
                                                                <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                    <a  href="javascript:void(0)" id="add_INTERVE_QUIRURJICA" name="add_INTERVE_QUIRURJICA" 
                                                                        class="btn btn-success btn-sm add_INTERVE_QUIRURJICA">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> AGREGAR
                                                                        INTERVENCIÓN QUIRÚGICA
                                                                    </a>
                                                                </div>
                                                                <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                    <table id="tbl_INTERVE_QUIRURJICA" style="margin-bottom: 10px !important;"> 
                                                                        <tbody  id="cuerpo_INTERVE_QUIRURJICA" name="cuerpo_INTERVE_QUIRURJICA" 
                                                                                class="cuerpo_INTERVE_QUIRURJICA">
                                                                        </tbody>
                                                                    </table> 
                                                                </div>  
                                                            </div> 
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab3defaultInterconsultaKardex">
                                                        <fieldset>
                                                            <div class="form-group"> 
                                                                <div class="col-md-3">
                                                                    <label for="tipo">FECHA</label>
                                                                    <input type="text" class="form-control datepicker"
                                                                    style="height: 28px !important;"
                                                                    name="fecha_int_kardex_hemod" id="fecha_int_kardex_hemod"
                                                                    autocomplete="off"
                                                                    value="{{\Carbon\Carbon::now()->format('d/m/Y')}}"> 
                                                                </div>
                                                                <div class="col-md-3"> 
                                                                    <label for="tipo">VOLUMEN TOTAL DE UF</label>
                                                                    <input value="" type="text" class="form-control"
                                                                    style="height: 28px !important;" required>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>


                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>