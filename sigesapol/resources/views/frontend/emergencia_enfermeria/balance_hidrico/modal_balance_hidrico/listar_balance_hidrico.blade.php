<!-- Modal -->
<style>
    .is-invalid {
        border: 2px solid red;
        /* Ajusta el grosor y color del borde según tus necesidades */
    }

    .icon-doc-firmado {
        color: #5cb85c;
        font-size: 3rem;
    }
</style>
</style>

<div class="modal fade" id="modalListarBalanceHidrico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarBalanceHidrico"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarBalanceHidrico" name="FormularioListarBalanceHidrico"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="peso_listado">
                    {{-- <input type="hidden" id="id_asegurado" name="id_asegurado"> --}}
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">

                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <div class="panel-title text-center"><br>
                                                <b>INFORMACIÓN DEL PACIENTE</b>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <tr>
                                                    <th>DOCUMENTO</th>
                                                    <th>PACIENTE</th>
                                                    <th>PARENTESCO</th>
                                                    <th>SEXO</th>
                                                    <th>EDAD</th>
                                                    <th>T. SANG.</th>
                                                    <th>H. CLINICA</th>
                                                    <th>ALERGIA: <a href="javascript:void(0)" aria-hidden="true"
                                                            data-toggle="tooltip" style="cursor: pointer; outline: none;"
                                                            title="Alergia" class="listarAlergiasV2"
                                                            id="listarAlergiasV2"><label id="alergia_bh"
                                                                style="cursor: pointer;"></label></a>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input_general" id="num_documento_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_paciente" id="nom_paciente_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_m" id="parentesgo_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_p" id="sexo_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_p" id="edad_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_p" id="tipo_sangre_bh"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input_hc" id="hc_bh"></div>
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">

                                        <div class="panel-body">
                                            <table class="table" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <tr>
                                                    <td width="20%">FECHA DESDE
                                                        <input type="text" class="form-control datepicker"
                                                            style="" autocomplete="off"
                                                            name="fecha_desde" id="fecha_desde"
                                                            value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                                    </td>
                                                    <td width="15%">
                                                        FECHA HASTA
                                                        <input type="text" class="form-control datepicker"
                                                            style="" autocomplete="off"
                                                            name="fecha_hasta" id="fecha_hasta"
                                                            value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                                    </td>
                                                    <td width="20%">
                                                        TURNO
                                                        <select class="form-control" style="width: 100%"
                                                            id="turnos_inicio" name="turno">
                                                        </select>
                                                    </td>
                                                    <td width="25%"><br>
                                                        <a href="javascript:void(0)" aria-hidden="true"
                                                            data-toggle="tooltip" title="Nuevo balance Hídrico"
                                                            class="btn btn-success"
                                                            id="crearBalanceHidrico">NUEVO BALANCE HÍDRICO
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset><br>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0"
                                                width="100%" style="margin-top:5px;">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">#</th>
                                                        <th width="15%" style="text-align: center;">FECHA / HORA
                                                            REGISTRO</th>
                                                        <th width="23%" style="text-align: center;">PROFESIONAL DE
                                                            SALUD RESPONSABLE</th>
                                                        <th width="18%" style="text-align: center;">UBICACIÓN</th>
                                                        <th width="5%" style="text-align: center;">PESO</th>
                                                        <th width="3%" style="text-align: center;">TURNO</th>
                                                        <th width="10%" style="text-align: center;">BH POR TURNO
                                                        </th>
                                                        <th width="10%" style="text-align: center;">BH 24 HORAS
                                                        </th>
                                                        <th width="20%" style="text-align: center;">VER / EDITAR
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody id="listaBalanceHidrico">

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
