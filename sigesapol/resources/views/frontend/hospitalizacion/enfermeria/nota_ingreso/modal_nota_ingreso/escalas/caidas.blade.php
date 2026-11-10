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
                                                    <left><input id="F" type="radio" name="F" value="0" style="cursor: pointer;" checked>
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
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF0" type="radio" name="FF" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>TRANQUILIZANTES-SEDANTES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF1" type="checkbox" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>DIURÉTICOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF2" type="checkbox" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>HIPOTENSORES (NO DIURÉTICOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF3" type="checkbox" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ANTIPARKINSONIANOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF4" type="checkbox" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ANTIDEPRESIVOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF5" type="checkbox" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>OTROS MEDICAMENTOS</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF6" type="checkbox" name="FF" value="1" style="cursor: pointer;">
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
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA0" type="radio" name="FA" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>ALTERACIONES VISUALES</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA1" type="checkbox" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>ALTERACIONES AUDITIVAS</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA2" type="checkbox" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>EXTREMIDADES (ICTUS)</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA3" type="checkbox" name="FA" value="1" style="cursor: pointer;">
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
                                                    <left><input id="FAA" type="radio" name="FAA" value="0" style="cursor: pointer;" checked>
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
                                                    <left><input id="FAAS0" type="radio" name="FAAS" value="0" style="cursor: pointer;" checked>
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
<div class="modal fade" id="modalListarEscalaCAIDAS_hd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaCAIDAS_hd"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaCAIDAS_hd" name="FormularioListarEscalaCAIDAS_hd"
                    enctype="multipart/form-data" method="#">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla0CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        EDAD
                                                    </b>
                                                </td>
                                                <td style="width: 40%;"><b>Menos de 3 años</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>De 3 - 7 años</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F1" type="radio" name="F" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>7-13 años</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F2" type="radio" name="F" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Más de 13 años</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F3" type="radio" name="F" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla1CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="2" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        GENERO
                                                    </b>
                                                </td>
                                                <td style="width: 40%;"><b>Hombre</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF1" type="radio" name="FF" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Mujer</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF2" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla2CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">DIAGNOSTICO</b>
                                                </td>
                                                <td style="width: 40%;"><b>Problemas Neurológicos</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF1" type="radio" name="FF1" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Alteraciones de oxigenación. Anemia,
                                                    anorexia, deshidratación, Problemas respiratorios, síncope vértigo
                                                    </b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF2" type="radio" name="FF1" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Trastornos psíquicos o de conducta</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF3" type="radio" name="FF1" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Otro diagnóstico</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF4" type="radio" name="FF1" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla3CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="3"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">DETERIORIO COGNITIVO</b>
                                                </td>
                                                <td style="width: 40%;"><b>No conoce sus limitaciones</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA0" type="radio" name="FA" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Se le olvida sus limitaciones</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA1" type="radio" name="FA" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Orientado en sus propias capacidades</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA2" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla4CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">FACTORES AMBIENTALES</b>
                                                </td>
                                                <td style="width: 40%;"><b>Historia de caídas previas de bebe o niño pequeño desde la cama.</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA1" type="radio" name="FAA" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Utiliza dispositivos de ayuda en la cuna,
                                                    iluminación, barandas, etc
                                                    </b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA2" type="radio" name="FAA" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Paciente en cama
                                                    </b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA3" type="radio" name="FAA" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Paciente ambulatorio
                                                    </b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA4" type="radio" name="FAA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla5CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="3"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">CIRUGIA O SEDACION ANESTESICA</b>
                                                </td>
                                                <td style="width: 40%;"><b>Dentro de las 24 horas</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS0" type="radio" name="FAAS" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Dentro de las 48 horas</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS1" type="radio" name="FAAS" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Más de 48 horas - ninguna</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS2" type="radio" name="FAAS" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    <table id="tabla6CAIDAS_hd" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="3"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">MEDICAMENTOS</b>
                                                </td>
                                                <td style="width: 40%;"><b>Uso de múltiples medicamentos sedantes (Se excluyen pacientes de UCIP con sedantes o relajantes)
                                                    hipnóticos, Barbitúricos, Fenotiazinas, Antidepresivos, laxantes - diuréticos,
                                                    Narcóticos
                                                    </b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASS0" type="radio" name="FAASS" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>Uno de los medicamentos antes mencionados</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASS1" type="radio" name="FAASS" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td style="width: 40%;"><b>Ninguno de los medicamentos antes mencionados</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASS2" type="radio" name="FAASS" value="1" style="cursor: pointer;">
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
                            <button type="button" class="btn btn-success" onclick="CAIDAS1_hd(this.form)" value="create"
                                id="procesar_EscalaCAIDAS_hd">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>