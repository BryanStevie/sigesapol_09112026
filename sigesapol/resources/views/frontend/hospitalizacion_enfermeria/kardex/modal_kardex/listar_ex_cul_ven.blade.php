<!-- Modal -->
<div class="modal fade" id="modalListar_ExCulVenKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListar_ExCulVenKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListar_ExCulVenKardex" name="FormularioListar_ExCulVenKardex"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_kardex" name="id_hospitalizacion_kardex">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')
                                    <div class="form-group">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" autocomplete="off" disabled
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                    </div>
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
                                                    <li class="active"><a href="#tab0defaultExCulVenKardex"
                                                            data-toggle="tab" style="font-size:12px"
                                                            aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                                    aria-hidden="true"></i>
                                                                EXAMENES DE LABORATORIO</b></a></li>
                                                    <li><a href="#tab1defaultExCulVenKardex" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                CULTIVOS</b></a></li>
                                                    <li><a href="#tab2defaultExCulVenKardex" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                VENTILOTERAPIA</b></a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body" style="padding-bottom: 0px !important;">
                                                <div class="tab-content">

                                                    <div class="tab-pane fade active in" id="tab0defaultExCulVenKardex">
                                                        <fieldset>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 60%;">DESCRIPCIÓN</th>
                                                                                <th>HORA</th>
                                                                                <th>HORA</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b>AGA</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>ELECTROLITOS</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>HMG., HCTO</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>GLUCOSA, UREA, CREATININA</b>
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>PERFIL DE COAGULACIÓN</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>PERFIL HEPÁTICO</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>ECO</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>PCR - PCT</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>CA, P, MG</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>OTROS</b></td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                                <td><input type="time"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab1defaultExCulVenKardex">
                                                        <fieldset>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 70%;">DESCRIPCIÓN</th>
                                                                                <th>FECHA</th>
                                                                                <th>VER</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b>HEMOCULTIVO I - II</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>SECRECIÓN BRONQUIAL</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>UROCULTIVO</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>PUNTA DE CATÉTER</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>GRAM</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>COPROCULTIVO</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        autocomplete="off"
                                                                                        name="fecha_interconsulta_kardex_iq"
                                                                                        id="fecha_interconsulta_kardex_iq">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Resultados"
                                                                                        class="btn btn-success btn-xs"><i
                                                                                            class="fa fa-book"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab2defaultExCulVenKardex">
                                                        <fieldset>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 70%;">DESCRIPCIÓN</th>
                                                                                <th>DETALLE</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b>CÁNULA BINASAL (LITROS)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>MASCARA VENTURI (LITROS)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>MASCARA RESERVORIO (LITROS)</b>
                                                                                </td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>CNAF (LITROS)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>TUBO ENDOTRAQUEAL (N°)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>TRAQUEOSTOMIA (N°)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>TIPO VM</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>MODO</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>FIO2 (%)</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>PEEP</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>DIAS DE VM</b></td>
                                                                                <td><input type="text"
                                                                                        class="form-control"
                                                                                        style="height: 28px !important;" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
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