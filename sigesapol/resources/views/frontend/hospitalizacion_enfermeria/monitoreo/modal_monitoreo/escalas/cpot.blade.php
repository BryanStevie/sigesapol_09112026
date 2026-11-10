<!-- Modal -->
<div class="modal fade" id="modalListarEscalaCpot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaCpot"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaCpot" name="FormularioListarEscalaCpot" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1FF1M" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4" style="width:30% ;">
                                                    <b style="text-align: center">
                                                        EXPRESIÓN FACIAL
                                                    </b>
                                                </td>
                                                <td style="width: 70%;"><b>RELAJADO, NEUTRO (NO SE OBSERVA TENSIÓN
                                                        MUSCULAR)</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>TENSO (PRESENCIA DE CEÑO FRUNCIDO, CEJAS
                                                        BAJAS, ÓRBITAS DE OJOS CONTRAÍDOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>MUECA (TODOS LOS MOVIMIENTOS FACIALES
                                                        ANTERIORES MÁS LOS PÁRPADOS FUERTEMENTE CERRADOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="2" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2MM1M" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">MOVIMIENTO DEL CUERPO</b>
                                                </td>
                                                <td style="width: 70%;"><b>AUSENCIA DE MOVIMIENTO (NO SE MUEVE NADA,
                                                        ESTO NO SIGNIFICA NECESARIAMENTE AUSENCIA DE DOLOR)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>PROTECCIÓN (MOVIMIENTOS LENTOS, CAUTELOSOS,
                                                        SE TOCA O FROTA EL SITIO DONDE LE DUELE)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>AGITADO (INTENTOS DE SENTARSE, MUEVE LAS
                                                        EXTREMIDADES, NO OBEDECE ÓRDENES)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="M" type="radio" name="M" value="2" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3AA1M" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">TENSIÓN MUSCULAR: EVALUACIÓN POR
                                                        FLEXIÓN Y EXTENSIÓN PASIVA DE LAS EXTREMIDADES SUPERIORES</b>
                                                </td>
                                                <td style="width: 70%;"><b>RELAJADO (NO RESISTENCIA A MOVIMIENTOS
                                                        PASIVOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>TENSO / RÍGIDOS (RESISTENCIA A MOVIMIENTOS
                                                        PASIVOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 70%;"><b>MUY TENSO O RÍGIDO (FUERTE RESISTENCIA A
                                                        MOVIMIENTOS PASIVOS, INCAPACIDAD PARA TERMINARLOS)</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="A" type="radio" name="A" value="2" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla4MOMO1M" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">VOCALIZACIÓN (PACIENTE EXTUBADOS)</b>
                                                </td>
                                                <td style="width: 70%;"><b>HABLA CON TONO NORMAL O NO HABLA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>SUSPIROS, GEMIDOS</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>GRITOS, SOLLOZOS</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="MO" type="radio" name="MO" value="3" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla5II1M" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 30%;">
                                                    <b style="text-align: center">ADAPTACIÓN VENTILADOR (PACIENTES
                                                        INTUBADOS)</b>
                                                </td>
                                                <td style="width: 70%;"><b>BIEN ADPTADO AL VENTILADOR (NO SE ACTIVAN LAS
                                                        ALARMAS, FÁCIL VENTILADOR)</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="0" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%;"><b>TOSE PERO SE ADAPTAN (LAS ALARMAS PARAN
                                                        ESPONTANEAMENTE)</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="1" size="2"
                                                            style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 4070%;"><b>LUCHA CON EL VENTILADOR (ASINCRONÍA: LA
                                                        VENTILACIÓN SE PARA, LAS ALARMAS SE ACTIVAN FRECUENTEMENTE)</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="I" type="radio" name="I" value="2" size="2"
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
                            <input type="hidden" id="CPOT_VAL" name="CPOT_VAL" size="4">
                            <input type="hidden" id="CPOT_DESC" name="CPOT_DESC" size="4">
                            <button type="submit" class="btn btn-success" onclick="CPOT1(this.form)" value="create"
                                id="procesar_EscalaCpot">PROCESAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>