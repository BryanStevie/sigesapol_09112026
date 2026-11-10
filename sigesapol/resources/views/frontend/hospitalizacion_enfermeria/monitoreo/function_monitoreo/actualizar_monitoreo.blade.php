<style> 
  .leyenda_grafico {
      list-style-type: none;
      margin: 0;
      padding: 0;
  }

  .leyenda_grafico li {
      margin-bottom: 10px;
      padding-left: 30px;
      position: relative;
  }

  .leyenda_grafico li .icono {
      position: absolute;
      left: 0;
      top: 0;
      height: 20px;
      width: 20px;
  } 
  .various-inputs {
    display: flex;
    justify-content: center;
  }

  .input-divisor {
    display: flex;
    height: 34px;
    align-items: center;
    font-size: 15px;
    padding: 4px
  }

  .monitoreo-td .various-number {
    width: 120px;
  }

  .monitoreo-td .various {
    width: 52.5px;
  }

  .m-respiratorio {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    margin-bottom: 15px;
  }

  .m-respiratorio > div {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
  }

  input {
    margin: 0 !important;
    padding-right: 4px !important;
  }

  label {
    padding-right: 4px !important;
    margin: 0 !important;
  }

  .row-infusion_up {
    width: 100%;
  }

  #tblInfusiones {
    width: 100%;
  }
  #tblInfusiones_up {
    width: 100%;
  }

  .table_td_input-xl {
    width: 70%;
  }

  .table_td_input-sm {
    width: 20%;
  }

  .table_td_delete {
    width: 10%;
  }

  .table_td_input-xl > input {
    width: 98%;
  }
  .table_td_input-sm > input {
    width: 98%;
  }

  .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 7px;
            margin: 2px 0 0;
            list-style: none;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

  .ui-autocomplete>li>div {
      display: block;
      padding: 3px 20px;
      clear: both;
      font-weight: normal;
      line-height: 1.42857143;
      color: #333333;
      white-space: nowrap;
  }
</style>

<script>  


      // Solo puede ser de 3 a 15
      const valoracionGlasgow_up = {
        '3': 'TRAUMA GRAVE',
        '4': 'TRAUMA GRAVE',
        '5': 'TRAUMA GRAVE',
        '6': 'TRAUMA GRAVE',
        '7': 'TRAUMA GRAVE',
        '8': 'TRAUMA GRAVE',
        '9': 'TRAUMA MODERADO',
        '10': 'TRAUMA MODERADO',
        '11': 'TRAUMA MODERADO',
        '12': 'TRAUMA MODERADO',
        '13': 'TRAUMA LEVE',
        '14': 'TRAUMA LEVE',
        '15': 'TRAUMA LEVE',
      }

      const intrepretacionRass_up = {
          "4":'COMBATIVO',
          "3":'MUY AGITADO',
          "2":'AGITADO',
          "1":'INQUIETO',
          "0":'ALERTA Y TRANQUILO',
          "-1":'SOMNOLIENTO',
          "-2":'SEDACION LIGERA',
          "-3":'SEDACION MODERADA',
          "-4":'SEDACION PROFUNDA',
          "-5":'NO ESTIMULABLE',
      }

      const descripcionRass_up = {
          "4":'Violento o combativo, con riesgo para el personal',
          "3":'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
          "2":'Movimientos descoordinados o desadaptacion del respirador',
          "1":'Ansioso, pero sin movimientos agresivos o vigorosos',
          "0":'',
          "-1":'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
          "-2":'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
          "-3":'Movimientos (sin apertura de ojos) a la llamada',
          "-4":'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
          "-5":'Sin respuesta a la voz o al estimulo fisico',
      };

      // Para validar que el campo venga de la consulta y que tenga datos
      // validar aparte caso unico para tubos, vmi/vmni y modalidad
      const todosLosCamposActualizables = ['conciencia_monitoreo_up', 'pupila_derecha_up', 'pupila_izquierda_up', 'grado_dependencia_up', 
                                          'glasgow_up', 'rass_up', 'pa_sisdolica_up', 'pa_diastolica_up', 'pulso_fc_up', 'frecuencia_respiratoria_up', 
                                          'temperatura_up', 'sa02_up', 'fi02_up', 'volumen_tiral_up', 'peed_up', 'frecuencia_m_up', 'frecuencia_p_up',
                                          'vol_min_up', 'i_e_up', 'presion_soporte_up', 'p_maxima_up', 'p_limite_up', 'co2_up', 'p_picco_up', 
                                          'diurisis_horaria_up', 'hemoglucotest_up', 'pam_up', 'tiraje_dt', 'respiracion_esponteanea_dt', 'dispositivo_oxigenoterapia_dt'];

      function renderEditModal(fecha_hora, id_monitoreo) {
        $.ajax({
            url: `api/monitoreo/buscarMonitoreoParaEditar/${id_monitoreo}/${fecha_hora}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
              if(res.length > 0) {
                $("#actualizarMonitoreo").attr("disabled",false);
                $('#FormularioMonitoreoActualizar').trigger("reset");
                $('#tituloFormularioMonitoreoActualizar').html("Editar Monitoreo");
                $('#modalMonitoreoActualizar').modal('show');
                $("#tipo_proceso").select2({ width: 'resolve'}); 
                $("#tamanio_proceso").select2({ width: 'resolve'}); 
                $("#id_monitoreo_up").val(res[0].id_monitoreo);
                $("#date_monitoreo_up").val((res[0].hora_monitoreo)); // SOLO CARGAMOS LA HORA, YA QUE LA FECHA SE OBTIENE EN BASE A LA FECHA ACTUAL DEL SERVIDOR.
                $("#fecha_hora_monitoreo_up").val((res[0].hora_monitoreo).split(' ')[1]);
                $("#turno_up").val(res[0].turno).change();
                $("#id_turno_up").val(res[0].turno);
                const edadPacienteUpdate = parseInt($("#edadPaciente").val());
                renderUserData();
                if (edadPacienteUpdate <= 14) {
                  if (edadPacienteUpdate < 2) {
                      const cambio1 = $('#updateGlasgowPediatria1').html("<b>AL HABLA O UN GRITO</b>");
                      const cambio6 = $('#updateGlasgowPediatria6').html('<b>RETIRA AL CONTACTO</b>');
                      const cambio7 = $('#updateGlasgowPediatria7').html('<b>ESPONTANEA</b>');
                  }
                  if (edadPacienteUpdate < 2) {
                      const cambio2 = $('#updateGlasgowPediatria2').html('<b>GEMIDO AL DOLOR</b>');
                      const cambio3 = $('#updateGlasgowPediatria3').html('<b>LLORA AL DOLOR</b>');
                      const cambio4 = $('#updateGlasgowPediatria4').html('<b>LLANTO CONSOLABLE</b>');
                      const cambio5 = $('#updateGlasgowPediatria5').html('<b>SONRIE/BALBUCEA</b>');
                  } else if (edadPacienteUpdate >= 2 && edadPacienteUpdate <= 5) {
                      const cambio2 = $('#updateGlasgowPediatria2').html('<b>PALABRAS ADECUADAS</b>');
                      const cambio3 = $('#updateGlasgowPediatria3').html('<b>PALABRAS INADECUADAS</b>');
                      const cambio4 = $('#updateGlasgowPediatria4').html('<b>LLORA AL DOLOR O GRITA</b>');
                      const cambio5 = $('#updateGlasgowPediatria5').html('<b>GRUÑE AL DOLOR</b>');
                  }
                }
                const camposActualizablesNecesarios = res.map(detalle => {
                  const {keyword, descripcion} = detalle;
                  const keywordParaMostrarEnModal = keyword.concat("_up");
                  return {keywordParaMostrarEnModal, descripcion};
                });
                const camposActualizablesSoloKeyword = camposActualizablesNecesarios.map((campo) => {
                  return campo.keywordParaMostrarEnModal;
                });
                /* todosLosCamposActualizables.forEach((campo) => {
                  if (!camposActualizablesSoloKeyword.includes(campo)) {
                      $("#" + campo).prop('disabled', true);
                  }
                }); */

                /* if (!camposActualizablesSoloKeyword.includes('fijacion_izq_up')) {
                  $('#ft_izq_up').prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('fijacion_der_up')) {
                  $('#ft_der_up').prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('vmi_up')) {
                  $('#vmi_up').prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('vmni_up')) {
                  $('#vmni_up').prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('modalidad_up')) {
                  $("#md_volumen_up").prop('disabled', true);
                  $("#md_presion_up").prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('glasgow_up')) {
                  $('.calcularEscalaGlasgowMonitoreo_up').prop('disabled', true);
                }

                if (!camposActualizablesSoloKeyword.includes('rass_up')) {
                  $('#calcularEscalaRass_up').prop('disabled', true);
                } */

                camposActualizablesNecesarios.forEach(campo => {
                  if (campo.keywordParaMostrarEnModal === "conciencia_monitoreo_up" || campo.keywordParaMostrarEnModal === "grado_dependencia_up") {
                    $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion).change();
                  } else if (campo.keywordParaMostrarEnModal === "pupila_derecha_up"/*  && campo.descripcion != "" */) {
                    /* $("#activo_pupila_der_up").prop('checked', false); */
                    $("#pupila_derecha_up").removeAttr('readonly');
                    $("#pupila_derecha_up").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "pupila_izquierda_up"/*  && campo.descripcion != "" */) {
                    /* $("#activo_pupila_izq_up").prop('checked', false); */
                    $("#pupila_izquierda_up").removeAttr('readonly');
                    $("#pupila_izquierda_up").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "glasgow_up") {
                    $("#glasgow_up").val(campo.descripcion);
                    $("#desc_glasgow_up").html(valoracionGlasgow_up[campo.descripcion]);
                  } else if (campo.keywordParaMostrarEnModal === "rass_up" && campo.descripcion) {
                    $('#rass_up').val(campo.descripcion); 
                    $('#puntaje_calculado_rass_up').val(`${campo.descripcion} | ${intrepretacionRass_up[campo.descripcion]}`); 
                    $('#desc_rass_up').html(descripcionRass_up[campo.descripcion]);
                  } else if (campo.keywordParaMostrarEnModal === "pam_up") {
                    $("#pam_up").removeAttr('disabled');
                    $("#pam_up").val(campo.descripcion);
                  /* } else if (campo.keywordParaMostrarEnModal === "modalidad_up") {
                    $("#modalidad_final_up").val(campo.descripcion);
                    if (campo.descripcion == "PRESION") {
                      $("#md_presion_up").prop('checked', true);
                    }
                    if (campo.descripcion == "VOLUMEN") {
                      $("#md_volumen_up").prop('checked', true);
                    } */
                  /* } else if (campo.keywordParaMostrarEnModal === "vmni_up" && campo.descripcion != "") {
                    $("#vmni_up_toggle").prop('checked', true);
                    $("#vm_desc_up").val(campo.descripcion);
                    $("#vm_desc_up").removeAttr('disabled');
                    $('#active_vmni_up').val('1');
                    console.log('vmni');
                  } else if (campo.keywordParaMostrarEnModal === "vmi_up" && campo.descripcion != "") {
                    $("#vmi_up_toggle").prop('checked', true);
                    $("#vm_desc_up").val(campo.descripcion);
                    $("#vm_desc_up").removeAttr('disabled');
                    $('#active_vmi_up').val('1');
                    console.log('vmi');
                  } else if (campo.keywordParaMostrarEnModal === "fijacion_der_up" && campo.descripcion != '') {
                    $("#ft_der_up").prop('checked', true);
                    $("#ft_valor_up").val(campo.descripcion);
                    $("#ft_valor_up").removeAttr('disabled');
                    $('#tubo_der_up').val('1');
                    console.log('fd');
                  } else if (campo.keywordParaMostrarEnModal === "fijacion_izq_up" && campo.descripcion != '') {
                    $("#ft_izq_up").prop('checked', true);
                    $("#ft_valor_up").val(campo.descripcion);
                    $("#ft_valor_up").removeAttr('disabled');
                    $('#tubo_izq_up').val('1');
                    console.log('fi'); */
                  } else if (campo.keywordParaMostrarEnModal === "dispositivo_oxigenoterapia_up" && campo.descripcion != '') {
                    $('#do_si_up').prop('checked', true);
                    $('#do_desc_up').prop('readonly', false);
                    $('#do_desc_up').val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "respiracion_espontanea_up" && campo.descripcion == '1') {
                    $('#re_si_up').prop('checked', true);
                    $('#respiracion_espontanea_up').val('1');
                  } else if (campo.keywordParaMostrarEnModal === "tiraje_up" && campo.descripcion == '1') {
                    $('#tiraje_si_up').prop('checked', true);
                    $('#tiraje_up').val('1');
                  } else {
                    $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion);
                  }
                });
                const detalles_infusiones = camposActualizablesNecesarios.filter((detalle) => {
                  if (detalle.keywordParaMostrarEnModal == "infusiones_up") {
                    return detalle;
                  }
                });
                if (detalles_infusiones.length > 0) {
                  renderInfusionesParaEditar(detalles_infusiones);
                }
              } else {
                alert('no se encontraron registros de este monitoreo, porque se uso para realizar pruebas.');
              }
            },
            error: function(err) {
                alert('Ocurrio un error inesperado.');
            }
        });
      }

      function renderInfusionesParaEditar(detalles_infusiones) {
        console.log(detalles_infusiones);
        const [detalle_infusion] = detalles_infusiones;
        const infusiones = JSON.parse(detalle_infusion.descripcion);
        infusiones.forEach((infusion, i) => {
          let newRow = "";
              newRow += `<tr class="row-infusion_up col-md-12" row-counter="${i}">`;
              newRow +=
                  `<td class="table_td_input-xl"><input type="text" data-toggle="tooltip" data-placement="top" readonly title="No se puede editar esto." value="${infusion.nombre}" name="nombre_infusion_up[]" required="" id="nombre_infusion_up` +
                  i + '" class="input-sm" style="margin-left:4px; color:red; cursor:not-allowed;" />';
              newRow +=
                  `<td class="table_td_input-sm"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad" value="${infusion.cantidad}" name="infusiones_cantidad_up[]" required="" id="infusiones_cantidad_up` +
                  i + '" class="input-sm" style="margin-left:4px; text-align:left" onkeypress="return permitirSoloDecimales(event)"/>';
              newRow += '<input type="hidden" class="form-control typeahead tt-query" id="infusiones_up' + i +
                  `" name="infusiones_up[]" value="${infusion.id}"></td>`;
              newRow +=
                  '<td class="table_td_delete"><button disabled title="No se puede eliminar una infusion ya registrada." type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
              newRow += '</tr>';
  
              $('#tblInfusiones_up tbody').append(newRow);
        });
      }

      $('#closeMonitoreoActualizar').click(function() {
        $('#FormularioMonitoreoActualizar').trigger("reset");
        $('#modalMonitoreoActualizar').modal('hide');
        $("#conciencia_monitoreo_up").val('').change();
        $("#grado_dependencia_up").val('').change();
        $('#pupila_izquierda_up').prop('readonly', true);
        $('#pupila_derecha_up').prop('readonly', true);
        $('.calcularEscalaGlasgowMonitoreo_up').prop('disabled', false);
        $('#calcularEscalaRass_up').prop('disabled', false);
        $('#ft_valor_up').prop('disabled', true);
        $('#tblInfusiones_up tbody').html('');
        // CAMBIAR LOGICA DE REGISTRO Y ACTUALIZACION AL MODELO DE TUBO (esto es vmi y vmni)
        $('#respiracion_espontanea_up').val('0');
        $('#tiraje_up').val('0');
        $('#do_desc_up').prop('readonly', true);
        $('#dispositivo_oxigenoterapia_up').val('');
        $('#temperatura_up').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado_up').html('');
        $('#pulso_fc_up').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado_up').html('');
        $('#frecuencia_respiratoria_up').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado_up').html('');
        $('#sa02_up').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado_up').html('');
        $('#fi02_up').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado_up').html('');
        /* $('#vm_desc_up').prop('name', ''); */
        const allTabs = document.querySelectorAll('.tabMoni_up');
        const allAccessTabs = document.querySelectorAll('.accesTab_up');
        allTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allAccessTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allTabs[0].classList.add('active');
        allTabs[0].classList.add('in');
        allAccessTabs[0].classList.add('active');
        /* todosLosCamposActualizables.forEach((campo) => {
          $("#" + campo).prop('disabled', false);
        }); */
        console.log('El modal ha sido cerrado.');
      });

      $('#modalMonitoreoActualizar').on('hidden.bs.modal', function (e) {
        $('#FormularioMonitoreoActualizar').trigger("reset");
        $('#modalMonitoreoActualizar').modal('hide');
        $("#conciencia_monitoreo_up").val('').change();
        $("#grado_dependencia_up").val('').change();
        $('#pupila_izquierda_up').prop('readonly', true);
        $('#pupila_derecha_up').prop('readonly', true);
        $('.calcularEscalaGlasgowMonitoreo_up').prop('disabled', false);
        $('#calcularEscalaRass_up').prop('disabled', false);
        $('#ft_valor_up').prop('disabled', true);
        $('#tblInfusiones_up tbody').html('');
        $('#respiracion_espontanea_up').val('0');
        $('#tiraje_up').val('0');
        $('#do_desc_up').prop('readonly', true);
        $('#dispositivo_oxigenoterapia_up').val('');
        $('#temperatura_up').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado_up').html('');
        $('#pulso_fc_up').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado_up').html('');
        $('#frecuencia_respiratoria_up').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado_up').html('');
        $('#sa02_up').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado_up').html('');
        $('#fi02_up').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado_up').html('');
        // CAMBIAR LOGICA DE REGISTRO Y ACTUALIZACION AL MODELO DE TUBO (esto es vmi y vmni)
        $('#vm_desc_up').prop('disabled', true);
        /* $('#vm_desc_up').prop('name', ''); */
        const allTabs = document.querySelectorAll('.tabMoni_up');
        const allAccessTabs = document.querySelectorAll('.accesTab_up');
        allTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allAccessTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allTabs[0].classList.add('active');
        allTabs[0].classList.add('in');
        allAccessTabs[0].classList.add('active');
        /* todosLosCamposActualizables.forEach((campo) => {
          $("#" + campo).prop('disabled', false);
        }); */
        console.log('El modal ha sido cerrado.');
      });

      $('#addInfusion_up').on('click', function() {
          AddInfusion_up();
      });

      $('#tblInfusiones_up tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
      });
      
      $(document).ready(function () {
        $("#grado_dependencia_up").select2({
          width: '100%',
          height: '30px',
        });
        $("#conciencia_monitoreo_up").select2({
          width: '100%',
          height: '30px',
        });
        /* $('#FormularioMonitoreoActualizar input[type="text"]').on('input', function() {
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        }); */

        // MENSAJES DE ALERTA:
      $("#fi02_up").on('blur', function(event) {         
        if (this.value != '') {
          if (parseInt(this.value) < 21 || parseInt(this.value) > 100) {
            console.log('no esta vacio');
            $('#fi02_up').addClass('borde-temperatura-hipertermia');
            $('#oxigenacion-aceptado_up').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
            $('#oxigenacion-aceptado_up strong').addClass('borde-temperatura-valida');
          } 
        }
      });
      $("#fi02_up").on('input',function() {
        $('#fi02_up').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado_up').html('');
      });

      $("#sa02_up").on('blur', function(event) {         
        var value = $(this).val();
        if (value != '') {
          if (parseInt(this.value) < 60 || parseInt(this.value) > 100) {
            $('#sa02_up').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02_up').addClass('borde-temperatura-hipertermia');
            $('#saturacion-aceptado_up').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
            $('#saturacion-aceptado_up strong').addClass('borde-temperatura-valida');
          } else if (value >= 60 && value <= 85){
            //HIPOXIA GRAVE  - ROJO
            $('#sa02_up').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02_up').addClass('borde-saturacion-hipoxia-grave');
            $('#saturacion-aceptado_up').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
          } else if (value >= 86 && value <= 90){
            //HIPOXIA MODERADA - NARANJA
            $('#sa02_up').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02_up').addClass('borde-saturacion-hipoxia-moderada');
            $('#saturacion-aceptado_up').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
          } else if (value >= 91 && value <= 94){
            // HIPOXIA LEVE - AMARILLO
            $('#sa02_up').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02_up').addClass('borde-saturacion-hipoxia-leve');
            $('#saturacion-aceptado_up').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
          } else if (value >= 95 && value <= 100){
            //NORMAL - VERDE
            $('#sa02_up').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02_up').addClass('borde-saturacion-normal');
            $('#saturacion-aceptado_up').html("<strong>" + 'Saturación Normal' + "</strong>");
          }
        }
      });
      $("#sa02_up").on('input',function() {
        $('#sa02_up').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado_up').html('');
      });

      $("#temperatura_up").on('blur', function (event) {
        var value = $(this).val();
        if (value != '') {
          if(parseInt(this.value) < 35 || parseInt(this.value) > 40) {
            $('#temperatura_up').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado_up').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado_up strong').addClass('borde-temperatura-valida');
          } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
              $('#temperatura_up').addClass('borde-temperatura-hipotermia');
              $('#texto-aceptado_up').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

          } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
              $('#temperatura_up').addClass('borde-temperatura-normal');
              $('#texto-aceptado_up').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

          } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
              $('#temperatura_up').addClass('borde-temperatura-fiebre');
              $('#texto-aceptado_up').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

          } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
              $('#temperatura_up').addClass('borde-temperatura-fiebre-alta');
              $('#texto-aceptado_up').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

          } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
              $('#temperatura_up').addClass('borde-temperatura-hipertermia');
              $('#texto-aceptado_up').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
          }
        }
      });
      $("#temperatura_up").on('input',function() {
        $('#temperatura_up').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado_up').html('');
      });

      $("#pulso_fc_up").on('blur',function(event) {
        var value = $('#pulso_fc_up').val();
        if (value != '') {
          if (parseInt(value) < 50 || parseInt(value) > 150) {
            $('#pulso_fc_up').addClass('borde-temperatura-hipertermia');
            $('#frec_cardiaca-aceptado_up').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
            $('#frec_cardiaca-aceptado_up strong').addClass('borde-temperatura-valida');
          }
        }
      });
      $("#pulso_fc_up").on('input',function() {
        $('#pulso_fc_up').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado_up').html('');
      });

      $("#frecuencia_respiratoria_up").on('blur',function(event) {
        var value = $('#frecuencia_respiratoria_up').val();
        if (value != '') {
          if (parseInt(value) < 10 || parseInt(value) > 35) {
            $('#frecuencia_respiratoria_up').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_respiratoria-aceptado_up').html("<p><strong>" + 'El valor de la Frec. Respiratoria debe estar entre 10 a 35 ( /min )' + "</strong></p>");
            $('#frecuencia_respiratoria-aceptado_up strong').addClass('borde-temperatura-valida');
          }
        }
      });
      $("#frecuencia_respiratoria_up").on('input',function() {
        $('#frecuencia_respiratoria_up').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado_up').html('');
      });

        $('#actualizarMonitoreo').click(function (e) {
          /* const fr_m = $('#frecuencia_m_up').val();
          const fr_p = $('#frecuencia_p_up').val();
          $('#frecuencia_mp').val(`${fr_m}${fr_p}`); */
          if ($("#tubo_izq_up").val() == '1') {
            console.log('ti');
            $("#fijacion_izq_up").val($("#ft_valor_up").val());
          } else {
            $("#fijacion_izq_up").val('');
          }
          if ($("#do_si_up").val() == 'on') {
            $("#dispositivo_oxigenoterapia_up").val($("#do_desc_up").val());
          } else {
            $("#dispositivo_oxigenoterapia_up").val('');
          }
          if ($("#tubo_der_up").val() == '1') {
            console.log('td');
            $("#fijacion_der_up").val($("#ft_valor_up").val());
          } else {
            $("#fijacion_der_up").val('');
          }
          /* if ($("#active_vmi_up").val() == '1') {
            console.log('vmi');
            $("#vmi_up").val($("#vm_desc_up").val());
          } else {
            $("#vmi_up").val('');
          } */
          /* if ($("#active_vmni_up").val() == '1') {
            console.log('vmni');
            $("#vmni_up").val($("#vm_desc_up").val());
          } else {
            $("#vmni_up").val('');
          } */
          const formData = $("#FormularioMonitoreoActualizar").serialize();
          const formData2 = $("#FormularioMonitoreoActualizar").serializeArray();
          console.log({formData2});
          e.preventDefault(); 
          swal({
            buttons: true,
            title: "¿Desea Guardar los cambios?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete) { 
              $.ajax({
                    url: '/api/monitoreo/editarMonitoreo',
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        $('#FormularioMonitoreoActualizar').trigger("reset");
                        $('#modalMonitoreoActualizar').modal('hide');
                        $("#conciencia_monitoreo_up").val('').change();
                        $("#grado_dependencia_up").val('').change();
                        $('#pupila_izquierda_up').prop('readonly', true);
                        $('#pupila_derecha_up').prop('readonly', true);
                        $('.calcularEscalaGlasgowMonitoreo_up').prop('disabled', false);
                        $('#calcularEscalaRass_up').prop('disabled', false);
                        $('#ft_valor_up').prop('disabled', true);
                        $('#tblInfusiones_up tbody').html('');
                        $('#respiracion_espontanea_up').val('0');
                        $('#tiraje_up').val('0');
                        $('#do_desc_up').prop('readonly', true);
                        $('#dispositivo_oxigenoterapia_up').val('');
                        $('#temperatura_up').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
                        $('#texto-aceptado_up').html('');
                        $('#pulso_fc_up').removeClass('borde-temperatura-hipertermia');
                        $('#frec_cardiaca-aceptado_up').html('');
                        $('#frecuencia_respiratoria_up').removeClass('borde-temperatura-hipertermia');
                        $('#frecuencia_respiratoria-aceptado_up').html('');
                        $('#sa02_up').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
                        $('#saturacion-aceptado_up').html('');
                        $('#fi02_up').removeClass('borde-temperatura-hipertermia');
                        $('#oxigenacion-aceptado_up').html('');
                        const allTabs = document.querySelectorAll('.tabMoni_up');
                        const allAccessTabs = document.querySelectorAll('.accesTab_up');
                        allTabs.forEach((tab) => {
                          tab.classList.remove('active');
                        });
                        allAccessTabs.forEach((tab) => {
                          tab.classList.remove('active');
                        });
                        allTabs[0].classList.add('active');
                        allTabs[0].classList.add('in');
                        allAccessTabs[0].classList.add('active');
                        console.log('El modal ha sido cerrado.');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud:', textStatus, errorThrown);
                    }
              });
              swal({
                buttons: false,
                title: "Registro Exitoso",
                text: "Datos registrados Exitosamente.",
                icon: "success",
                timer: 1500,
            });
  
            } else {

              console.log("CERRANDO");
              
              $(this).attr('disabled', false);
              swal({
                buttons: false,
                title: "Operación Cancelada",
                text: "Proceso de Registro Cancelado.",
                icon: "error",
                timer: 1500,
              });
  
            }
          });
  
      
        });
      });

      function filterInput(inputElement) {
        let value = inputElement.value;

        // Expresión regular para permitir solo números y un punto decimal
        value = value.replace(/[^0-9.]/g, '');

        // Asegurarse de que solo haya un punto decimal
        const parts = value.split('.');
        if (parts.length > 2) {
            // Si hay más de un punto, solo mantener el primer punto
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        // Actualizar el valor del input
        inputElement.value = value;
      }

      function AddInfusion_up() {
        const allRowsRenderized = document.querySelectorAll('.row-infusion_up');
        const allInfusionesIngresadas = Array.from(allRowsRenderized).map(function(row) {
          return row.querySelector(`#infusiones_up${row.getAttribute('row-counter')}`).value;
        });
        const lastRow = allRowsRenderized[allRowsRenderized.length - 1];
        var ind = 0;

        if (lastRow) {
          const rowId = lastRow.getAttribute('row-counter');
          var lastValue = lastRow.querySelector(`#infusiones_up${rowId}`).value;
          if(lastValue == ""){
            bootbox.alert("Ingrese la infusion anterior primero.");
            return false;
          }
          var ind = parseInt(lastRow.getAttribute('row-counter')) + 1;
        }

        var newRow = "";
        newRow += `<tr class="row-infusion_up col-md-12" row-counter="${ind}">`;
        newRow +=
            '<td class="table_td_input-xl"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la infusion" name="nombre_infusion_up[]" required="" id="nombre_infusion_up' +
            ind + '" class="input-sm" style="margin-left:4px; color:red; text-align:left" />';
        newRow +=
            '<td class="table_td_input-sm"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad" name="infusiones_cantidad_up[]" required="" id="infusiones_cantidad_up' +
            ind + '" class="input-sm" style="margin-left:4px; text-align:left" onkeypress="return permitirSoloDecimales(event)"/>';
        newRow += '<input type="hidden" class="form-control typeahead tt-query" id="infusiones_up' + ind +
            '" name="infusiones_up[]" value=""></td>';
        newRow +=
            '<td class="table_td_delete"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';

        $('#tblInfusiones_up tbody').append(newRow);

        $("#nombre_infusion_up" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                $.ajax({
                    url: `/api/intervenciones-infusiones/listado?data=${$("#nombre_infusion_up" + ind).val().toUpperCase()}`,
                    dataType: "json",
                    success: function(data) {
                        let filtro = data.filter((element)=>!allInfusionesIngresadas.includes(`${element.id}`));
                        console.log(filtro);
                        response(filtro);
                    }
                })

            },
            select: function(e, ui) {
                var infusiones = ui.item.id;
                $("#infusiones_up" + ind).val(infusiones);
                $("#infusiones_up" + ind).prop("readonly", true);
                $("#nombre_infusion_up" + ind).prop("readonly", true);
            }
        });
      }

      function getHoraFromInput_up(datetime) {
        const {0: hora} = datetime.split("T").reduce((acc, curr, index) => {
          acc[index] = curr;
          return acc;
        }, {});
        const [hours, minutes] = hora.split(':');
        const formattedTime = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
        return {formattedTime};
      }

      function isTimeInRange_up(time, startTime, endTime) {
        const timeToCheck = new Date(`1970-01-01T${time}:00`);
        const start = new Date(`1970-01-01T${startTime}:00`);
        const end = new Date(`1970-01-01T${endTime}:00`);
        
        // Manejar el caso en que el rango de tiempo cruza la medianoche
        if (end < start) {
          return (timeToCheck >= start && timeToCheck < new Date('1970-01-02T00:00:00')) ||
                (timeToCheck >= new Date('1970-01-01T00:00:00') && timeToCheck <= end);
        } else {
          return timeToCheck >= start && timeToCheck <= end;
        }
      }

      const inputFechaConHora_up = document.getElementById('fecha_hora_monitoreo_up');
      inputFechaConHora_up.addEventListener('change', function(e) {
        const {formattedTime: hora} = getHoraFromInput_up(e.target.value);
        $("#turno_up").val(isTimeInRange_up(hora, "07:30", "13:29") ? "2" : isTimeInRange_up(hora, "13:30", "19:29") ? "3" : isTimeInRange_up(hora, "19:30", "07:29") ? "4" : "").change();
      });

      const selectTurno_up = document.getElementById('turno_up');
      selectTurno_up.addEventListener('change', function(e) {
        if (inputFechaConHora_up.value) {
          const {formattedTime: hora} =  getHoraFromInput_up(inputFechaConHora_up.value);
          if ($("#turno_up").val() == '2') {
            if(!isTimeInRange_up(hora, "07:30", "13:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno_up").val(isTimeInRange_up(hora, "07:30", "13:29") ? "1" : isTimeInRange_up(hora, "13:30", "19:29") ? "2" : isTimeInRange_up(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
          if ($("#turno_up").val() == '3') {
            console.log('turno tarde');
            if(!isTimeInRange_up(hora, "13:30", "19:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno_up").val(isTimeInRange_up(hora, "07:30", "13:29") ? "1" : isTimeInRange_up(hora, "13:30", "19:29") ? "2" : isTimeInRange_up(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
          if ($("#turno_up").val() == '4') {
            console.log('turno noche');
            if(!isTimeInRange_up(hora, "19:30", "07:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno_up").val(isTimeInRange_up(hora, "07:30", "13:29") ? "1" : isTimeInRange_up(hora, "13:30", "19:29") ? "2" : isTimeInRange_up(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
        }
      });

      /* const activoPupIzq_up = document.getElementById("activo_pupila_izq_up");
      activoPupIzq_up.addEventListener('change', function(e) {
        if (this.checked) {
          $("#pupila_izquierda_up").attr('disabled', 'disabled');
        } else {
          $("#pupila_izquierda_up").removeAttr('disabled');
        }
      });
      const activoPupDer_up = document.getElementById("activo_pupila_der_up");
      activoPupDer_up.addEventListener('change', function(e) {
        if (this.checked) {
          $("#pupila_derecha_up").attr('disabled', 'disabled');
        } else {
          $("#pupila_derecha_up").removeAttr('disabled');
        }
      }); */

      const vmi_up = document.getElementById('do_si_up');
      const vmni_up = document.getElementById('do_no_up');
      const vm_desc_up = document.getElementById('do_desc_up');

      vmi_up.addEventListener('change', function(e) {
        if (vm_desc_up.readOnly) {
          vm_desc_up.readOnly = false;
        }
      });
      vmni_up.addEventListener('change', function(e) {
        if (vmni_up.value == 'on') {
          vm_desc_up.readOnly = true;
          vm_desc_up.value = '';
        }
      });

      const pa_1_up = document.getElementById("pa_sisdolica_up");
      const pam_up = document.getElementById("pam_up");
      pa_1_up.addEventListener('keyup', function(e) {
        pam_up.value = (parseInt(pa_1_up.value == '' ? 0 : pa_1_up.value) + (parseInt(pa_2_up.value == '' ? 0 : pa_2_up.value) * 2))/3;
      });

      const pa_2_up = document.getElementById("pa_diastolica_up");
      pa_2_up.addEventListener('keyup', function(e) {
        pam_up.value = (parseInt(pa_1_up.value == '' ? 0 : pa_1_up.value) + (parseInt(pa_2_up.value == '' ? 0 : pa_2_up.value) * 2))/3;
      });

      const md_presion_up = document.getElementById('re_si_up');
      const md_volumen_up = document.getElementById('re_no_up');

      md_presion_up.addEventListener('change', function(e) {
        if (md_presion_up.value == 'on') {
          $('#respiracion_espontanea_up').val('1');
        }
      });
      md_volumen_up.addEventListener('change', function(e) {
        if (md_volumen_up.value == 'on') {
          $('#respiracion_espontanea_up').val('0');
        }
      });
      const md_presion_t_up = document.getElementById('tiraje_si_up');
      const md_volumen_t_up = document.getElementById('tiraje_no_up');

      md_presion_t_up.addEventListener('change', function(e) {
        if (md_presion_t_up.value == 'on') {
          $('#tiraje_up').val('1');
        }
      });
      md_volumen_t_up.addEventListener('change', function(e) {
        if (md_volumen_t_up.value == 'on') {
          $('#tiraje_up').val('0');
        }
      });

      /* const ft_der = document.getElementById('ft_der');
      const ft_izq = document.getElementById('ft_izq');
      const ft_valor = document.getElementById('ft_valor');

      ft_der.addEventListener('change', function(e) {
        if (ft_der.value == 'on') {
          $('#tubo_der').val('1');
          $('#tubo_izq').val('0');
        }
        if (ft_valor.disabled) {
          ft_valor.disabled = false;
        }
      });
      ft_izq.addEventListener('change', function(e) {
        if (ft_izq.value == 'on') {
          $('#tubo_izq').val('1');
          $('#tubo_der').val('0');
        }
        if (ft_valor.disabled) {
          ft_valor.disabled = false;
        }
      });

      

      
          
      function esNumero(evt,id)
      {
        try {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if(charCode==46) {
              var txt=document.getElementById(id).value;
              if(!(txt.indexOf(".") > -1)){
                  return true;
              }
          }
          if (charCode > 31 && (charCode < 48 || charCode > 57) ) {
            return false;
          }
          return true;
        } catch(w) {
            alert(w);
        }
      } */

      /* const fijacion_der_up = document.getElementById('ft_der_up');
      const fijacion_izq_up = document.getElementById('ft_izq_up');
      const fijacion_valor_up = document.getElementById('ft_valor_up');

      fijacion_der_up.addEventListener('change', function(e) {
        if (fijacion_der_up.value == 'on') {
          $('#tubo_der_up').val('1');
          console.log(fijacion_der_up.value);
          $('#tubo_izq_up').val('0');
        }
        if (fijacion_valor_up.disabled) {
          fijacion_valor_up.disabled = false;
        }
      });
      fijacion_izq_up.addEventListener('change', function(e) {
        if (fijacion_izq_up.value == 'on') {
          $('#tubo_der_up').val('0');
          console.log(fijacion_izq_up.value);
          $('#tubo_izq_up').val('1');
        }
        if (fijacion_valor_up.disabled) {
          fijacion_valor_up.disabled = false;
        }
      }); */
      /* fijacion_der_up.addEventListener('click', function(e) {
        fijacion_valor_up.setAttribute('name', 'fijacion_der_up');
      });
      fijacion_izq_up.addEventListener('click', function(e) {
        fijacion_valor_up.setAttribute('name', 'fijacion_izq_up');
      }); */

      /* const vmi_up = document.getElementById('vmi_up_toggle');
      const vmni_up = document.getElementById('vmni_up_toggle');
      const vm_desc_up = document.getElementById('vm_desc_up');

      vmi_up.addEventListener('change', function(e) {
        if (vmi_up.value == 'on') {
          $('#active_vmi_up').val('1');
          $('#active_vmni_up').val('0');
        }
        if (vm_desc_up.disabled) {
          vm_desc_up.disabled = false;
        }
      });
      vmni_up.addEventListener('change', function(e) {
        if (vmni_up.value == 'on') {
          $('#active_vmni_up').val('1');
          $('#active_vmi_up').val('0');
        }
        if (vm_desc_up.disabled) {
          vm_desc_up.disabled = false;
        }
      }); */
      /* vmi_up.addEventListener('click', function(e) {
          vm_desc_up.setAttribute('name', 'vmi_up');
      });
      vmni_up.addEventListener('click', function(e) {
        vm_desc_up.setAttribute('name', 'vmni_up');
      }); */

      /* const md_presion_up = document.getElementById('md_presion_up');
      const md_volumen_up = document.getElementById('md_volumen_up');

      md_presion_up.addEventListener('change', function(e) {
        if (md_presion_up.value == 'on') {
          $('#modalidad_final_up').val('PRESION');
        }
      });
      md_volumen_up.addEventListener('change', function(e) {
        if (md_volumen_up.value == 'on') {
          $('#modalidad_final_up').val('VOLUMEN');
        }
      }); */
      
  </script>