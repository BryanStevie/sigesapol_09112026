<!-- Modal -->
<div class="modal fade" id="modalVerTriaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>Vista Rápida de Triaje</b>
            </div>

            <div class="modal-body">
                <form id="FormularioVerTriaje" name="FormularioVerTriaje" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">

                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    <div class="row">
                                        <div class="col-md-2">  
                                            <label>TIPO DE ATENCIÓN: </label><br>
                                            <r>TRIAJE - EMERGENCIA</r>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>N° DE REGISTRO: </label><br>
                                            <numero_registro id="numero_registro">-</numero_registro>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>FECHA DE REGISTRO: </label><br>
                                            <feha_triaje id="feha_triaje">-</feha_triaje>
                                        </div>
                                        <div class="col-md-6">  
                                            <label>NOMBRE DEL ESTABLECIMIENTO: </label><br>
                                            <nombre_establecimiento id="nombre_establecimiento" style="text-transform: uppercase;">-</nombre_establecimiento>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12"> 
                                            <p><b style="font-size: 14px;">INFORMACIÓN DEL PACIENTE</b></p>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>N° DE DOCUMENTO: </label><br>
                                            <tipo_documento id="tipo_documento">-</tipo_documento> <numero_documento id="numero_documento">-</numero_documento>
                                        </div>
                                        <div class="col-md-1">  
                                            <label>SEXO: </label><br>
                                            <sexo_paciente id="sexo_paciente">-</sexo_paciente>
                                        </div>
                                        <div class="col-md-1">  
                                            <label>EDAD: </label><br>
                                            <edad_paciente id="edad_paciente">-</edad_paciente>
                                        </div> 
                                        <div class="col-md-2    ">  
                                            <label>F. NACIMIENTO: </label><br>
                                            <fecha_nacimiento id="fecha_nacimiento">-</fecha_nacimiento>
                                        </div> 
                                        <div class="col-md-6">  
                                            <label>PACIENTE: </label><br>
                                            <nombre_paciente id="nombre_paciente"></nombre_paciente>
                                        </div> 
                                    </div> 
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12"> 
                                            <p><b style="font-size: 14px;">INFORMACIÓN DEL INGRESO</b></p>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>FORMA DE INGRESO: </label><br>
                                            <forma_ingreso id="forma_ingreso">-</forma_ingreso>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>ESTADO DE INGRESO: </label><br>
                                            <estado_ingreso id="estado_ingreso">-</estado_ingreso>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>MOTIVO DE INGRESO: </label><br>
                                            <motivo_ingreso id="motivo_ingreso"  style="text-transform: uppercase;">-</motivo_ingreso>
                                        </div>  
                                    </div> 
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12"> 
                                            <p><b style="font-size: 14px;">INFORMACIÓN DE SIGNOS VITALES</b></p>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>TEM (°C): </label><br>
                                            <temperatura_paciente id="temperatura_paciente">-</temperatura_paciente>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>TALLA (cm): </label><br>
                                            <talla_paciente id="talla_paciente">-</talla_paciente>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>PESO (Kg): </label><br>
                                            <peso_paciente id="peso_paciente">-</peso_paciente>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>IMC: </label><br>
                                            <imc_paciente id="imc_paciente">-</imc_paciente>
                                        </div>
                                        <div class="col-md-2">  
                                            <label>PESO IDEAL: </label><br>
                                            <peso_ideal_paciente id="peso_ideal_paciente">-</peso_ideal_paciente>
                                        </div> 
                                        <div class="col-md-2">  
                                            <label>Sa02 (%): </label><br>
                                            <sa02_paciente id="sa02_paciente">-</sa02_paciente>
                                        </div> 
                                        <div class="col-md-3">  <br>
                                            <label>TAMAÑO DE CINTURA (cm): </label><br>
                                            <tam_cintura_paciente id="tam_cintura_paciente">-</tam_cintura_paciente>
                                        </div>
                                        <div class="col-md-3">   <br>
                                            <label>FREC. CARDIACA FETAL (/min): </label><br>
                                            <fre_car_fetal id="fre_car_fetal">-</fre_car_fetal>
                                        </div>
                                        <div class="col-md-2">   <br>
                                            <label>PRESIÓN ARTERIAL: </label><br>
                                            <pre_art_paciente id="pre_art_paciente">-</pre_art_paciente>
                                        </div>
                                        <div class="col-md-3">   <br>
                                            <label>FREC. RESPIRATORIA (/min): </label><br>
                                            <fre_resp_paciente id="fre_resp_paciente">-</fre_resp_paciente>
                                        </div>
                                        <div class="col-md-3">   <br>
                                            <label>FREC. CARDIACA (/min): </label><br>
                                            <fre_car_paciente id="fre_car_paciente">-</fre_car_paciente>
                                        </div>
                                        <div class="col-md-2">   <br>
                                            <label>Fi02 (%): </label><br>
                                            <fio2_paciente id="fio2_paciente">-</fio2_paciente>
                                        </div> 
                                    </div> 
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12"> 
                                            <p><b style="font-size: 14px;">INFORMACIÓN ADICIONAL</b></p>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>PRIORIDAD: </label><br>
                                            <prioridad_paciente id="prioridad_paciente">-</prioridad_paciente>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>TÓPICO: </label><br>
                                            <topico_paciente id="topico_paciente">-</topico_paciente>
                                        </div>
                                        <div class="col-md-4">  
                                            <label>MOTIVO DE LA CONSULTA: </label><br>
                                            <motivo_paciente id="motivo_paciente"  style="text-transform: uppercase;">-</motivo_paciente>
                                        </div>
                                    </div> 

                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>