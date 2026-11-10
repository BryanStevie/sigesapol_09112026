<div class="panel panel-info panel-bordered panel-sm" id="datosPacienteCargado" hidden>
    <div class="panel-heading">
        <div class="panel-title text-center"><br>
            <b>INFORMACIÓN DEL PACIENTE</b>
        </div>
    </div>
    <div class="panel-body">  
        <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
            <tr>
                <th>DOCUMENTO</th>
                <th>PACIENTE</th>
                <th>PARENTESCO</th>
                <th>SEXO</th>
                <th>EDAD</th>
                <th>T. SANG.</th>
                <th>H. CLINICA</th>
                <th>ALERGIA:</th>
            </tr>
            <tr>
                <td>
                    <div  class="input_general" id="dni_paciente"></div>
                </td>
                <td>
                    <div  class="input_paciente" id="nombre_paciente"></div>
                </td>
                <td>
                    <div  class="input_m" id="parentesco_paciente"></div>
                </td>
                <td>
                    <div  class="input_p" id="sexo_paciente"></div>
                </td>
                <td>
                    <div  class="input_p" id="edad_paciente"></div>
                </td>
                <td>
                    <div  class="input_p" id="ts_paciente"></div>
                </td>
                <td>
                    <div  class="input_hc" id="hc_paciente"></div>
                </td>
                <td>
                    {{-- <span id="es_alergico_paciente"></span> --}}
                    <a href="javascript:void(0)" aria-hidden="true"
                        data-toggle="tooltip" style="cursor: pointer; outline: none;"
                        title="Alergia" class="listarAlergiasV2"
                        ><label id="es_alergico_paciente"
                            style="cursor: pointer;"></label></a>
                </td>
            </tr>
        </table>
    </div>
</div>