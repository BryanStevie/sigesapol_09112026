<!-- Modal -->
<div class="modal fade" id="modalListarEscalaNorton" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaNorton"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaNorton" name="FormularioListarEscalaNorton"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1FF" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4" style="width: 30%;">
                                                    <b style="text-align: center">ESTADO FÍSICO GENERAL</b>
                                                </td>
                                                <td style="width: 50%;"><b>BUENO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="4" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>REGULAR</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="3" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 50%;"><b>MALO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="2" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>MUY MALO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="1" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2MM" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">ESTADO MENTAL</b>
                                                </td>
                                                <td style="width: 50%;"><b>ALERTA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="4" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>APÁTICO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="3" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 50%;"><b>CONFUSO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="2" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>ESTUPOROSO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3AA" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">ACTIVIDAD</b>
                                                </td>
                                                <td style="width: 50%;"><b>PLENA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="4" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>ALGO LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="3" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 50%;"><b>MUY LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="2" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>NULA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="1" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla4MOMO" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">MOVILIDAD</b>
                                                </td>
                                                <td style="width: 50%;"><b>AMBULANTE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="4" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>DEAMBULA CON AYUDA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="3" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 50%;"><b>SILLA DE RUEDAS SIEMPRE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="2" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>NULA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla5II" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">INCONTINENCIA</b>
                                                </td>
                                                <td style="width: 50%;"><b>NO PRESENTA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="4" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>OCASIONAL</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="3" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 50%;"><b>VESICAL O FECAL</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="2" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;"><b>DOBLE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="1" size="2"
                                                            style="cursor: pointer;"></left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="P" name="P" size="4">
                            <input type="hidden" id="J" name="J" size="25">
                            <button type="submit" class="btn btn-success" onclick="NORTON1(this.form)" value="create"
                                id="procesar_EscalaNorton">PROCESAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>