<script>

    $(document).ready(function(){
function validarSoloEnteros(element) {
$(element).on('input', function(){
this.value=this.value.replace(/[^0-9]/g, '');
})
}

    validarSoloEnteros('#res');
    validarSoloEnteros('#sistolica');
    validarSoloEnteros('#diastolica');
});
    
</script>

<div class="modal fade" id="modalHojaGrafica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioHojaGrafica"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioHojaGrafica" name="FormularioHojaGrafica" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_enfermeria_register" name="id_enfermeria_register">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <div class="panel-title text-center">
                                                <b>DATOS DE LA ATENCION SIGNOS VITALES</b>
                                              </div>
                                        </div>
                                        <div class="panel-body" id="collapseprestacionespaciente">
                                            <div class="form-group">

                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">DOCUMENTO DE IDETIDAD</label>
                                                    <input tabindex="22" type="text" class="form-control" name="document2" id="document2" style="text-align:left" readonly>
                                                </div>
                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">PACIENTE</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control" name="paciente2" id="paciente2" style="text-align:left"readonly>
                                                  
                                                  </div>
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">PARENTESCO</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control"name="parentesco2" id="parentesco2" style="text-align:left"readonly>
                                                  
                                                  </div>  
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">SEXO</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control" name="sexo2" id="sexo2" style="text-align:left"readonly>
                                                  
                                                  </div>  
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">EDAD</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control"  name="edad_corta2" id="edad_corta2" style="text-align:left"readonly>
                                                  
                                                  </div>  
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">T.SANGRE</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control" name="tipo_sangre2" id="tipo_sangre2" style="text-align:left"readonly>
                                                  
                                                  </div> 
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">H CLINICA</label>
                                                    
                                                    <input tabindex="22" type="text" class="form-control" name="nro_historia2" id="nro_historia2" style="text-align:left"readonly>
                                                  
                                                  </div> 
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="medico_nombre">ALERGIA</label>
                                                    <input tabindex="22" type="text" class="form-control" name="nro_historia2" id="nro_historia2" style="text-align:left" readonly>
                                                  </div>  
                                                  <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="tipo">TIPO</label>
                                                    <select class="form-control select2" style="width: 100%">
                                                        <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="tipo">FECHA REGISTRO</label>
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" name="fecha_registro"
                                                        id="fecha_registro" autocomplete="off"
                                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" readonly>
                                                </div>
                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="tipo">DIAS ESTANCIA</label>
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" name="dias_estancia"
                                                        id="dias_estancia" autocomplete="off">
                                                </div>
                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="tipo">FECHA FINAL</label>
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" name="fecha_evolucion_final"
                                                        id="fecha_evolucion_final" autocomplete="off"
                                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                                </div>

                                                <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
                                                    <label for="tipo">TURNO</label>
                                                    <select class="form-control select2" style="width: 100%">
                                                        <option value="HOSPITALIZACION">MAÑANA</option>
                                                        <option value="EMERGENCIA">TARDE</option>
                                                    </select>
                                                </div>



                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <div class="panel-title text-center">
                                                <b>SIGNOS VITALES</b>
                                            </div>
                                        </div>
                                        <div class="panel-body" id="collapseprestacionespaciente">
                                            <div class="form-group">
                                                <table id="example" class="table table-striped table-list-search"
                                                    style="margin-bottom: 0px !important;">
                                                    <tbody>
                                                       
                                                       
                                                        <div id="signosVitalesContainer" class="row">
                                                            
                                                            <!-- Campos de signos vitales se agregarán dinámicamente aquí -->
                                                        </div>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left"
                            data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarHojaGrafica"
                            value="create">Registrar</button>
                    </div>
            </div>
        </div>
        </form>
    </div>
</div>
