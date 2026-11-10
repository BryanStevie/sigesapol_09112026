<script>
// $('body').on('click', '#listarHojaGrafica', async function() {
//   var id_hospitalizacion = $(this).data('id');
//   var id_asegurado = $(this).data('id_asegurado');
//   console.log(id_asegurado);
//   $('#FormularioListarHojaGrafica').trigger("reset");
//   $('#tituloFormularioListarHojaGrafica').html("Lista de Signos Vitales");

//   try {
//       let response = await getValues(id_asegurado);
//       // Rellenar datos
//       $('#document').val(response.nro_doc_ident); // Asigna el valor al campo de entrada
//       $('#paciente').val(response.nombre); // Asigna el valor al campo de entrada
//       $('#parentesco').val(response.parentesgo); // Asigna el valor al campo de entrada
//       $('#sexo').val(response.sexo); // Asigna el valor al campo de entrada
//       $('#edad_corta').val(response.edad_corta); // Asigna el valor al campo de entrada
//       $('#tipo_sangre').val(response.tipo_sangre); // Asigna el valor al campo de entrada
//       $('#nro_historia').val(response.nro_historia); // Asigna el valor al campo de entrada

//       $('#cip').val(response.cip); // Asigna el valor al campo de entrada


//       $('#modalListarHojaGrafica').modal('show');
//       $('#id_hospitalizacion_hoja_grafica').val(id_hospitalizacion);
//   } catch (error) {
//       console.error(error);
//   }
// });
</script>

<div class="tab-pane fade active in" id="tab0DatosPaciente">
    <fieldset>
      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center">
            <b>DATOS DE LA ATENCION SIGNOS VITALES</b>
          </div>
        </div>
        <div class="panel-body" id="collapseprestacionespaciente">
          <input type="hidden" name="id_asegurado_hiden" id="id_asegurado_hiden">
          <div class="form-group">
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">DOCUMENTO DE IDENTIDAD</label>
              <input tabindex="22" type="text" class="form-control" name="document" id="document" style="text-align:left" readonly>
          </div>
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">PACIENTE</label>
              
              <input tabindex="22" type="text" class="form-control" name="paciente" id="paciente" style="text-align:left"readonly>
            
            </div>
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">PARENTESCO</label>
              
              <input tabindex="22" type="text" class="form-control"name="parentesco" id="parentesco" style="text-align:left"readonly>
            
            </div>  
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">SEXO</label>
              
              <input tabindex="22" type="text" class="form-control" name="sexo" id="sexo" style="text-align:left"readonly>
            
            </div>  
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">EDAD</label>
              
              <input tabindex="22" type="text" class="form-control"  name="edad_corta" id="edad_corta" style="text-align:left"readonly>
            
            </div>  

            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">T.SANGRE</label>
              
              <input tabindex="22" type="text" class="form-control" name="tipo_sangre" id="tipo_sangre" style="text-align:left"readonly>
            
            </div>  
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">H CLINICA</label>
              
              <input tabindex="22" type="text" class="form-control" name="nro_historia" id="nro_historia" style="text-align:left"readonly>
            
            </div>  
            <div class="col-xs-12 col-md-2 col-lg-3 col-xl-4">
              <label for="medico_nombre">ALERGIA</label>
              
              
            
            </div>  
          </div>

        </div>
      </div>
    </fieldset>
  </div>
