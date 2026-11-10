<!-- Modal -->
<div class="modal fade" id="modalListarEscalaMorse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaMorse"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaMorse" name="FormularioListarEscalaMorse" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1FF1" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        ANTECEDENTES DE CAÍDAS RECIENTES <br>
                                                        O EN LOS ÚLTIMOS 3 MESES
                                                    </b>
                                                </td>
                                                <td style="width: 40%;"><b>SI</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="15" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>NO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2MM1" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">DIAGNÓTICO SECUNDARIO</b>
                                                </td>
                                                <td style="width: 40%;"><b>SI</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="15" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>NO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3AA1" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">AYUDA PARA LA DEAMBULACIÓN</b>
                                                </td>
                                                <td style="width: 40%;"><b>REPOSO EN CAMA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>BASTÓN / MULETAS / ANDADOR</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="15" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 40%;"><b>SE APOYA EN MUEBLES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="30" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla4MOMO1" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">CATÉTERES IV / VÍA
                                                        HEPARINIZADA</b>
                                                </td>
                                                <td style="width: 40%;"><b>NO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SI</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="20" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla5II1" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">EQUILIBRIO / TRASLADO</b>
                                                </td>
                                                <td style="width: 40%;"><b>NORMAL / INMOVILIZACIÓN / REPOSO EN
                                                        CAMA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>DÉBIL</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="15" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 40%;"><b>ALTERADA REQUIERE ASISTENCIA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="30" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla6II1I" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">ESTADO MENTAL</b>
                                                </td>
                                                <td style="width: 40%;"><b>CONSCIENTE</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="IF" type="radio" name="IF" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>NO CONSCIENTE</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="IF" type="radio" name="IF" value="15" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="MSE" name="MSE" size="4">
                            <input type="hidden" id="MSEDS" name="MSEDS" size="4">
                            <button type="submit" class="btn btn-success" onclick="MORSE1(this.form)" value="create"
                                id="procesar_EscalaMorse">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>