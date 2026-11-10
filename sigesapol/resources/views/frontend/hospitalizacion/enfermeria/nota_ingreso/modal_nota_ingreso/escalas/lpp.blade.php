<!-- Modal -->
<div class="modal fade" id="modalListarEscalaLPP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaLPP"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaLPP" name="FormularioListarEscalaLPP"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <table id="tabla1LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5" style="width: 40%;">
                                                    <b style="text-align: center">
                                                        PERCEPCIÓN SENSORIAL
                                                    </b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. COMPLETAMENTE LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. MUY LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. LIGERAMENTE LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>4. SIN LIMITACIONES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="F" type="radio" name="F" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla2LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">EXPOSICIÓN A LA HUMEDAD</b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. CONSTANTEMENTE HÚMEDA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. A MENUDO HÚMEDA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. OCASIONALMENTE HÚMEDA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>4. RARAMENTE HÚMEDA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FF" type="radio" name="FF" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tabla3LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">ACTIVIDAD FÍSICA</b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. PACIENTE CONSTANTEMENTE ENCAMADO</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. EN SILLA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. DEAMBULA OCASIONALMENTE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>4. DEAMBULA FRECUENTEMENTE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FA" type="radio" name="FA" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla4LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">MOVILIDAD</b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. COMPLETAMENTE INMÓVIL</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. MUY LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. LIGERAMENTE LIMITADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>4. SIN LIMITACIONES</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAA" type="radio" name="FAA" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla5LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="5"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">NUTRICIÓN</b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS" type="radio" name="FAAS" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. MUY POBRE</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS" type="radio" name="FAAS" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. PROBABLEMENTE INADECUADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS" type="radio" name="FAAS" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. ADECUADA</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS" type="radio" name="FAAS" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>4. EXCELENTE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAAS" type="radio" name="FAAS" value="4" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                    <table id="tabla6LPP" border="1" class="table table-striped table-list-search"
                                        style="margin-bottom: 0px !important; border: 1px solid #ddd;  !important">
                                        <tbody>
                                            <tr>
                                                <td colspan="1" rowspan="4"
                                                    style="background-color: #f1f1f1; width: 40%;">
                                                    <b style="text-align: center">ROCE Y PELIGRO DE LESIONES</b>
                                                </td>
                                                {{-- <td style="width: 40%;"><b>NINGUNA</b></td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASD" type="radio" name="FAASD" value="0" style="cursor: pointer;">
                                                    </left>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>1. PROBLEMA</b>
                                                </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASD" type="radio" name="FAASD" value="1" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>2. PROBLEMA POTENCIAL</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASD" type="radio" name="FAASD" value="2" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;"><b>3. NO EXISTE PROBLEMA APARENTE</b> </td>
                                                <td style="width: 10%;">
                                                    <left><input id="FAASD" type="radio" name="FAASD" value="3" style="cursor: pointer;">
                                                    </left>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="total_LPP" name="total_LPP" size="4">  
                            <input type="hidden" id="descripcion_detalle_riesgolpp" name="descripcion_detalle_riesgolpp" size="4"> 
                            <button type="submit" class="btn btn-success" onclick="LPP1(this.form)" value="create"
                                id="procesar_EscalaLPP">PROCESAR</button>
                        </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>