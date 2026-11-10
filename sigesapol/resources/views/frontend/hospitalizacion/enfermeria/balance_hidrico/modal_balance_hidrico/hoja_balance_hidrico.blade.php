<!-- Modal -->
<div class="modal fade" id="modalListarHojaBalanceHidrico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarHojaBalanceHidrico"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarHojaBalanceHidrico" name="FormularioListarHojaBalanceHidrico"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
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
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>INGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                                                <th style="text-align: center;">M</th>
                                                                <th style="text-align: center;">T</th>
                                                                <th style="text-align: center;">N</th>
                                                                <th style="text-align: center;">TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><b>HIDRATACIÓN</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>INYECTABLE 1</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>INYECTABLE 2</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>INYECTABLE 3</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ORAL 1</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ORAL 2</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ORAL 3</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SNG</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>AGUA PARA OXIDACIÓN</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>TOTAL</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>BALANCE</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"> - </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>EGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                                                <th style="text-align: center;">M</th>
                                                                <th style="text-align: center;">T</th>
                                                                <th style="text-align: center;">N</th>
                                                                <th style="text-align: center;">TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><b>DIURESIS</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VOMITOS</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>DRENAJE ASPIRACIÓN</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ASPIRACIÓN</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HECES 1</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HECES 2</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HECES 3</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HECES 4</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PERDIDAS INSENSIBLES</b></td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">VALOR</td>
                                                                <td style="text-align: center;">TOTAL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>TOTAL</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>BH 24 HORAS</b></td>
                                                                <td style="text-align: center;">-</td>
                                                                <td style="text-align: center;">-</td>
                                                                <td style="text-align: center;">-</td>
                                                                <td style="text-align: center;"><b>VALOR</b></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </fieldset>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
    </form>
</div>