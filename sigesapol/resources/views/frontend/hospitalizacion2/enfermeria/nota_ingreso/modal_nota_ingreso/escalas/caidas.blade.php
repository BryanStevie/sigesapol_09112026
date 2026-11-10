<!-- Modal -->
<div class="modal fade" id="modalListarEscalaCAIDAS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaCAIDAS"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaCAIDAS" name="FormularioListarEscalaCAIDAS"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1CAIDAS" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="2" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        CAÍDAS
                                                    </b>
                                                </td>
                                                <td style="width: 40%;"><b>NO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SI</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla2CAIDAS" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="7"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">MEDICAMENTOS</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF0" type="radio" name="FF" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>TRANQUILIZANTES-SEDANTES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF1" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>DIURÉTICOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF2" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>HIPOTENSORES (NO DIURÉTICOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF3" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ANTIPARKINSONIANOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF4" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ANTIDEPRESIVOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF5" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>OTROS MEDICAMENTOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF6" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla3CAIDAS" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">DÉFICITS SONSORIALES</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA0" type="radio" name="FA" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>ALTERACIONES VISUALES</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA1" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ALTERACIONES AUDITIVAS</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA2" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>EXTREMIDADES (ICTUS)</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA3" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla4CAIDAS" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="2"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">ESTADO MENTAL</b>
                                                </td>
                                                <td style="width: 40%;"><b>ORIENTADO</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>CONFUSO</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla5CAIDAS" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">DEAMBULACIÓN</b>
                                                </td>
                                                <td style="width: 40%;"><b>NORMAL</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS0" type="radio" name="FAAS" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>SEGURA CON AYUDA</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS1" type="radio" name="FAAS" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>INSEGURA CON/SIN AYUDA</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS2" type="radio" name="FAAS" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>IMPOSIBLE</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS3" type="radio" name="FAAS" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>   
                                        </tbody>
                                    </table> 
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="total_CAIDAS" name="total_CAIDAS" size="4">  
                            <input type="hidden" id="descripcion_detalle_caidas" name="descripcion_detalle_caidas" size="4"> 
                            <input type="hidden" id="total_CAIDAS" name="total_CAIDAS" size="4">  
                            <button type="submit" class="btn btn-success" onclick="CAIDAS1(this.form)" value="create"
                                id="procesar_EscalaCAIDAS">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>