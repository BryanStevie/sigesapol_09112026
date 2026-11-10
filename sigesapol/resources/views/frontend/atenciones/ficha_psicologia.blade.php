<style>
    td.br-1 {
        border-right-width: 1px;
        border-right-color: rgb(221, 221, 221);
        border-right-style: solid;
    }
    td.bb-1 {
        border-bottom-width: 1px;
        border-bottom-color: rgb(221, 221, 221);
        border-bottom-style: solid;
    }
</style>
<script>

</script>
<?php
$tabActive = 0;
$tabIndex = 0;
?>
<div class="modal fade app-modal-reset" id="modalFichaPsicologia" tabindex="-1" role="dialog" aria-labelledby="modalEscalaLabel">
    <div class="modal-dialog app-modal-dialog-900" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">FICHA PSICOLÓGICA</h5>
            </div>
            <div class="modal-body app-model-escala">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">I GRADO FUNCIONAL:</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-condensed table-hover ">
                            <thead>
                                <tr>
                                    <th colspan="2">I GRADO FUNCIONAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0 Ninguna dificultad en la movilización</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>1 Ligera dificultad en la movilización</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>2 Con ayuda de bastón</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>3 Con ayuda de una persona, andador, incontinente ocasionalmente</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>4 Con ayuda de dos personas, incontinente habitualmente</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>5 Dependiente total, postración</td>
                                    <td>
                                        <input type="radio" name="optionsRadios" id="" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>OBSERVACION</p>
                                        <textarea class="form-control" name="" id="" cols="30" rows="2"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th colspan="4">DEFICET</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Auditivo <input type="checkbox" value=""></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td>Visual <input type="checkbox" value=""></td>
                                    <td><input type="text" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">II EXAMEN MENTAL:</div>
                    </div>
                    <div class="panel-body">
                        <!-- <div class="row"> -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover ">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Conciencia</th>
                                            <th colspan="3">Orientación</th>
                                            <th colspan="2">Compresión</th>
                                            <th colspan="2">Atención</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Despierto</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Persona</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="br-1"><input type="text" class="form-control"></td>
                                            <td>Total</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Atento</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                        <tr>
                                            <td>Somnoliento</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Espacio</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="br-1"><input type="text" class="form-control"></td>
                                            <td>Parcial</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Distraído</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                        <tr>
                                            <td class="bb-1">Confuso</td>
                                            <td class="br-1 bb-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="bb-1">Tiempo</td>
                                            <td class="bb-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="br-1 bb-1"><input type="text" class="form-control"></td>
                                            <td class="bb-1">No hay</td>
                                            <td class="br-1 bb-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="bb-1">Inestable</td>
                                            <td class="bb-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->

                        <!-- <div class="row"> -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="8">Lenguaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Fluido</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Repetitivo</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Vacilante</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Voz apagada</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                        <tr>
                                            <td class="bb-1">Coherente</td>
                                            <td class="bb-1 br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="bb-1">Incoherente</td>
                                            <td class="bb-1 br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="bb-1">Por momentos incoherente</td>
                                            <td class="bb-1 br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td class="bb-1">No hay comunicación verbal</td>
                                            <td class="bb-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->

                        <!-- <div class="row"> -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="8">Deterioro Cognoscitivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Normal (1-2)</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Leve (3-4)</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Moderado (5-7)</td>
                                            <td class="br-1"><input type="radio" name="optionsRadios" id="" value=""></td>
                                            <td>Severo (8,+)</td>
                                            <td><input type="radio" name="optionsRadios" id="" value=""></td>
                                        </tr>
                                        <tr>
                                            <td>Fecha</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Dia</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Lugar</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Teléfono</td>
                                            <td><input type="checkbox" value=""></td></td>
                                        </tr>
                                        <tr>
                                            <td>Edad</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Nacimiento</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Mama</td>
                                            <td class="br-1"><input type="checkbox" value=""></td></td>
                                            <td>Pres. Actual</td>
                                            <td><input type="checkbox" value=""></td></td>
                                        </tr>
                                        <tr>
                                            <td class="bb-1">Pres. Anterior</td>
                                            <td class="bb-1 br-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Resta</td>
                                            <td class="bb-1 br-1"><input type="checkbox" value=""></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">III CARACTERISTICAS DE PERSONALIDAD (Animo):</div>
                    </div>
                    <div class="panel-body">
                        <!-- <div class="row"> -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="12">III CARACTERISTICAS DE PERSONALIDAD (Animo):</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Receptivo</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Tranquilo</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Optimista</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Alegre</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Evasivo</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Resistente</td>
                                            <td><input type="checkbox" value=""></td>
                                        </tr>
                                        <tr>
                                            <td>Estresado</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Pesimista</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Irritable</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Temeroso</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Ansioso</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Agresivo</td>
                                            <td><input type="checkbox" value=""></td>
                                        </tr>
                                        <tr>
                                            <td>Preocupado</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Desanimado</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Aburrido</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Triste</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Frustado</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Cansado</td>
                                            <td class="bb-1"><input type="checkbox" value=""></td>
                                        </tr>
                                        <tr>
                                            <td class="bb-1">Labil</td>
                                            <td class="br-1 bb-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Perfeccionista</td>
                                            <td class="br-1 bb-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Bajo Nivel de Tolerancia</td>
                                            <td class="br-1 bb-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Disminución de Vitalidad</td>
                                            <td class="br-1 bb-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Falta de cooperación</td>
                                            <td class="br-1 bb-1"><input type="checkbox" value=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div> -->
                        <!-- /.row -->
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">IV AREA FAMILIAR - SOCIAL:</div>
                    </div>
                    <div class="panel-body">
                        <!-- <div class="row"> -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="8">IV AREA FAMILIAR - SOCIAL:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Preocupada</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td>Indiferente</td>
                                            <td class="br-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Disfuncional</td>
                                            <td class="bb-1 br-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Vive solo</td>
                                            <td class="bb-1"><input type="checkbox" value=""></td>
                                        </tr>
                                        <tr>
                                            <td class="bb-1">Tiene cuidador</td>
                                            <td class="bb-1 br-1"><input type="checkbox" value=""></td>
                                            <td class="bb-1">Participa en Grupo Social</td>
                                            <td class="bb-1 br-1"><input type="checkbox" value=""></td>
                                            
                                            <!-- <td>asdasdasd</td>
                                            <td><input type="checkbox" value=""></td> -->
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btnEscalaGuardar" id="btnEscalaGuardar">Aceptar</button>
            </div>
        </div>
    </div>
</div>