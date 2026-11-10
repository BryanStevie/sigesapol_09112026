<!-- Modal -->
<div class="modal fade" id="modalListarEscalaRass_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaRass_up"></b>
            </div>
            <div class="modal-body">
                <form id="FormularioListarEscalaRass_up" name="FormularioListarEscalaRass_up"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1Rass_up" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="10" style="width: 40%;">
                                                    <b style="text-align: center">CATEGORIAS</b>
                                                </td>
                                                <td style="width: 40%;"><b>COMBATIVO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>MUY AGITADO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>AGITADO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>INQUIETO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>ALERTA Y TRANQUILO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SOMNOLIENTO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="-1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SEDACION LIGERA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="-2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SEDACION MODERADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="-3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SEDACION PROFUNDA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="-4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>NO ESTIMULABLE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_r_up" type="radio" name="F_r_up" value="-5" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="total_rass_up" name="total_rass_up" size="4"> 
                            {{-- <input type="hidden" id="descripcion_total_rass" name="descripcion_total_rass" size="4"> --}} 
                            <button type="submit" class="btn btn-success" onclick="procesarRass_up(this.form)" value="create"
                                id="procesar_EscalaRass_up">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>