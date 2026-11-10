<!-- Modal -->
<div class="modal fade" id="modalListarEscalaTiss" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaTiss"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaTiss" name="FormularioListarEscalaTiss" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="tablaTISS" class="table table-striped table-list-search"
                                                style="margin-bottom: 0px !important;">
                                                <tbody style="font-weight: 600; text-transform: uppercase !important;;">
                                                    <tr>
                                                        <td> Monitorización estandar, constantes horarias, balance
                                                            hídrico:
                                                        </td>
                                                        <td><input id="M1" type="CHECKBOX" name="M1" value="5" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Análisis de bioquímica y hematología: </td>
                                                        <td><input id="L" type="CHECKBOX" name="L" value="1" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Medicación única iv, im, sc, oral o por SNG: </td>
                                                        <td><input id="IV" type="radio" name="IV" value="2" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Medicación múltiple o continua intravenosa: </td>
                                                        <td><input id="IV" type="radio" name="IV" value="3" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Cambios rutinarios de apósitos, cuidados y prevención de
                                                            decúbitos: </td>
                                                        <td><input id="C1" type="radio" name="C" value="1" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Cambios frecuentes de apósitos. Cura de heridas extensas:
                                                        </td>
                                                        <td><input id="C2" type="radio" name="C" value="1" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Cuidados de drenajes, excepto SNG: </td>
                                                        <td><input id="D" type="CHECKBOX" name="D" value="3" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Ventilación Mecánica (cualquier modalidad), con o sin PEEP
                                                            o
                                                            relajantes: </td>
                                                        <td><input id="SV1" type="radio" name="SV" value="5" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Ventilación espontánea por tubo traqueal o traqueostomía
                                                            sin
                                                            CPAP, oxigenoterapia: </td>
                                                        <td><input id="SV2" type="radio" name="SV" value="2" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Cuidados de la vía aérea artificial: </td>
                                                        <td><input id="CV" type="CHECKBOX" name="CV" value="1" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Fisioterapia respiratoria, aerosolterapia, aspiración
                                                            secreciones por TOT: </td>
                                                        <td><input id="TMV" type="CHECKBOX" name="TMV" value="1"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Una medicación vasoactiva: </td>
                                                        <td><input id="MV" type="radio" name="MV" value="3" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Múltiples drogas vasoactivas: </td>
                                                        <td><input id="MV" type="radio" name="MV" value="4" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Reposición intravenosa de gran cantidad de líquidos (&gt; 3
                                                            L/m<sup>2</sup>/dia): </td>
                                                        <td><input id="L" type="CHECKBOX" name="L" value="4" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Catéter arterial periférico: </td>
                                                        <td><input id="CA" type="CHECKBOX" name="CA" value="5" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Catéter de Swan-Ganz con o sin medida del Gasto Cardiaco:
                                                        </td>
                                                        <td><input id="AI" type="CHECKBOX" name="AI" value="8" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Catéter venoso central: </td>
                                                        <td><input id="CVC" type="CHECKBOX" name="CVC" value="2"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> RCP en las últimas 24 h: </td>
                                                        <td><input id="RCP" type="CHECKBOX" name="RCP" value="3"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Técnicas de hemofiltración: </td>
                                                        <td><input id="HF" type="CHECKBOX" name="HF" value="3" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Monitorización de diuresis por sonda vesical: </td>
                                                        <td><input id="DSV" type="CHECKBOX" name="DSV" value="2"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Diuresis forzada por sobrecarga de fluídos: </td>
                                                        <td><input id="DSF" type="CHECKBOX" name="DSF" value="3"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Monitorización de Presión Intracraneal: </td>
                                                        <td><input id="PIC" type="CHECKBOX" name="PIC" value="4"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Tratamiento de complicaciones metabólicas, acidosis,
                                                            alcalosis:
                                                        </td>
                                                        <td><input id="TCM" type="CHECKBOX" name="TCM" value="4"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Nutrición Parenteral: </td>
                                                        <td><input id="NP" type="CHECKBOX" name="NP" value="3" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Nutrición Enteral: </td>
                                                        <td><input id="NE" type="CHECKBOX" name="NE" value="2" size="3"
                                                                style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Intervención única en UCI (*): </td>
                                                        <td><input id="IUCI1" type="radio" name="IUCI" value="3"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Intervenciones múltiples en UCI (más de una de las
                                                            anteriores):
                                                        </td>
                                                        <td><input id="IUCI2" type="radio" name="IUCI" value="5"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> Intervenciones específicas fuera de la UCI (intervención
                                                            quirúrgica o diagnóstica): </td>
                                                        <td><input id="IFU" type="CHECKBOX" name="IFU" value="5"
                                                                size="3" style="cursor: pointer;">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input type="hidden" id="TISS" name="TISS" size="2">
                                            <input type="hidden" id="TISS_J" name="TISS_J" size="2">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="TISS1(this.form)" value="create"
                                id="procesar_EscalaTiss">PROCESAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>