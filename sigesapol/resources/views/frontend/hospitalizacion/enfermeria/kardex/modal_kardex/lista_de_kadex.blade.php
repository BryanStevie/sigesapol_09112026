<!-- Modal -->
<div class="modal fade" id="modalListarKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarKardex" name="FormularioListarKardex" enctype="multipart/form-data"
                    method="POST">
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
                                    <div class="form-group"> 
                                        <div class="col-md-3">
                                            <label for="tipo">TIPO</label>
                                            <select class="form-control select2" style="width: 100%">
                                                <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                                <option value="EMERGENCIA">EMERGENCIA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_kardex_inicial" id="fecha_kardex_inicial" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_kardex_final" id="fecha_kardex_final" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>PROCESO</label><br>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm crearKardex" id="crearKardex"> Nuevo Kardex
                                            </a>
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
                                                        <th>USUARIO</th> 
                                                        <th>TIPO</th>
                                                        <th style="width: 20%;">FUNCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for($i = 1; $i <= 5; $i++) <tr>
                                                        <td>01/04/2023 - 02:20:42</td>
                                                        <td>SISTEMA</td> 
                                                        @if ($i%2==0)
                                                            <td>HOSPITALIZACION</td>
                                                        @else
                                                            <td>EMERGENCIA</td>
                                                        @endif
                                                        <td>
                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Visualizar"
                                                                class="btn btn-success btn-xs verKardex"> <i
                                                                    class="fa fa-eye"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Editar"
                                                                class="btn btn-warning btn-xs editarKardex"> <i
                                                                    class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Ver Interconsultas e Intervenciones"
                                                                class="btn btn-success btn-xs listarInterconsultaKardex"> <i
                                                                    class="fa fa-bars"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" aria-hidden="true"
                                                                data-toggle="tooltip" title="Ver Ex. Cult. Vent."
                                                                class="btn btn-success btn-xs listarExCulVenKardex"> <i
                                                                    class="fa fa-file"></i>
                                                            </a>
                                                        </td>
                                                        </tr>
                                                        @endfor
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