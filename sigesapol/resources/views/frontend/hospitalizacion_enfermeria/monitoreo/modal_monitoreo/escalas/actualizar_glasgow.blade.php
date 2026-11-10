<!-- Modal -->
<div class="modal fade" id="modalListarEscalaGlasgow_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaGlasgow_up"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaGlasgow_up" name="FormularioListarEscalaGlasgow_up"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token_up" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1Glasgow_up" border="1" class="table table-striped table-list-search"
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
                                                    <left><input id="F_up" type="radio" name="F_up" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>AL ESTÍMULO DOLOROSO</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_up" type="radio" name="F_up" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria1"><b>AL ESTÍMULO VERBAL</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_up" type="radio" name="F_up" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>ESPONTÁNEA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F_up" type="radio" name="F_up" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2Glasgow_up" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">RESPUESTA VERBAL</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF_up" type="radio" name="FF_up" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria2"><b>SONIDOS INCOMPRENSIBLES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF_up" type="radio" name="FF_up" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria3"><b>PALABRAS INADECUADAS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF_up" type="radio" name="FF_up" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria4"><b>CONFUSA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF_up" type="radio" name="FF_up" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria5"><b>ORIENTADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF_up" type="radio" name="FF_up" value="5" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3Glasgow_up" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="6"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">RESPUESTA MOTORA</b>
                                                </td>
                                                <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>EXTENSIÓN HIPERTÓNICA (DESCEREBRACIÓN)</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>FLEXIÓN HIPERTÓNICA (DECORTINACIÓN)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>MOVIMIENTO DE RETIRADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria6"><b>LOCALIZA EL DOLOR</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="5" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;" id="updateGlasgowPediatria7"><b>OBEDECE ÓRDENES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA_up" type="radio" name="FA_up" value="6" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="total_Glasgow_up" name="total_Glasgow_up" size="4"> 
                            <button type="submit" class="btn btn-success" onclick="GLASGOW1_up(this.form)" value="create"
                                id="procesar_EscalaGlasgow_up">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>