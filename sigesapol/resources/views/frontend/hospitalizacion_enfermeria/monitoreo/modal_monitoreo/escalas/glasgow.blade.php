<!-- Modal -->
<div class="modal fade" id="modalListarEscalaGlasgowMonitoreo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaGlasgow"></b>
            </div>
            <div class="modal-body">
                <form id="FormularioListarEscalaGlasgow" name="FormularioListarEscalaGlasgow"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1Glasgow" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        ABERTURA OCULAR
                                                    </b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>AL ESTÍMULO DOLOROSO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria1"><b>AL ESTÍMULO VERBAL</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>ESPONTÁNEA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2Glasgow" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">RESPUESTA VERBAL</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria2"><b>SONIDOS INCOMPRENSIBLES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria3"><b>PALABRAS INADECUADAS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria4"><b>CONFUSA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria5"><b>ORIENTADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="5" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3Glasgow" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="6"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">RESPUESTA MOTORA</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>EXTENSIÓN HIPERTÓNICA (DESCEREBRACIÓN)</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>FLEXIÓN HIPERTÓNICA (DECORTINACIÓN)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>MOVIMIENTO DE RETIRADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria6"><b>LOCALIZA EL DOLOR</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="5" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="glasgowPediatria7"><b>OBEDECE ÓRDENES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="6" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="total_Glasgow" name="total_Glasgow" size="4"> 
                            <input type="hidden" id="descripcion_total_Glasgow" name="descripcion_total_Glasgow" size="4"> 
                            <button type="submit" class="btn btn-success" onclick="GLASGOW1(this.form)" value="create"
                                id="procesar_EscalaGlasgow">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>