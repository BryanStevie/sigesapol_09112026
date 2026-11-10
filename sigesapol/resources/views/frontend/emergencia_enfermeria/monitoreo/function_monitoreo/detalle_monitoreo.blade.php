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

  .row-infusion_dt {
    width: 100%;
  }

  #tblInfusiones {
    width: 100%;
  }
  #tblInfusiones_dt {
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
      const valoracionGlasgow_dt = {
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

      const intrepretacionRass_dt = {
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

      const descripcionRass_dt = {
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
      const todosLosCamposActualizables_dt = ['conciencia_monitoreo_dt', 'pdtila_derecha_dt', 'pdtila_izquierda_dt', 'grado_dependencia_dt', 
                                          'glasgow_dt', 'rass_dt', 'pa_sisdolica_dt', 'pa_diastolica_dt', 'pulso_fc_dt', 'frecuencia_respiratoria_dt', 
                                          'temperatura_dt', 'sa02_dt', 'fi02_dt', 'volumen_tiral_dt', 'peed_dt', 'frecuencia_m_dt', 'frecuencia_p_dt',
                                          'vol_min_dt', 'i_e_dt', 'presion_soporte_dt', 'p_maxima_dt', 'p_limite_dt', 'co2_dt', 'p_picco_dt', 
                                          'diurisis_horaria_dt', 'hemoglucotest_dt', 'pam_dt'];

      function renderDetalleModal(fecha_hora, id_monitoreo) {
        $.ajax({
            url: `api/monitoreo/detalleMonitoreo/${id_monitoreo}/${fecha_hora}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
              if(res.length > 0) {
                $("#firmarMonitoreo").attr("disabled",false);
                $('#FormularioMonitoreoDetalle').trigger("reset");
                $('#tituloFormularioMonitoreoDetalle').html("Detalle Monitoreo");
                $('#modalMonitoreoDetalle').modal('show');
                /* $("#tipo_proceso").select2({ width: 'resolve'}); 
                $("#tamanio_proceso").select2({ width: 'resolve'});  */
                $("#id_monitoreo_dt").val(res[0].id_monitoreo);
                $("#date_monitoreo_dt").val((res[0].hora_monitoreo)); // SOLO CARGAMOS LA HORA, YA QUE LA FECHA SE OBTIENE EN BASE A LA FECHA ACTUAL DEL SERVIDOR.
                $("#fecha_hora_monitoreo_dt").val((res[0].hora_monitoreo).split(' ')[1]);
                $("#turno_dt").val(res[0].turno).change();
                $("#id_turno_dt").val(res[0].turno);
                renderUserData();
                validarExistenciaFirma(res[0].ruta_firma, res[0].id_monitoreo, res[0].hora_monitoreo);
                const camposActualizablesNecesarios = res.map(detalle => {
                  const {keyword, descripcion} = detalle;
                  const keywordParaMostrarEnModal = keyword.concat("_dt");
                  return {keywordParaMostrarEnModal, descripcion};
                });
                camposActualizablesNecesarios.forEach(campo => {
                  if (campo.keywordParaMostrarEnModal === "conciencia_monitoreo_dt" || campo.keywordParaMostrarEnModal === "grado_dependencia_dt") {
                    $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion).change();
                  } else if (campo.keywordParaMostrarEnModal === "pupila_derecha_dt"/*  && campo.descripcion != "" */) {
                    /* $("#activo_pupila_der_dt").prop('checked', false); */
                    $("#pupila_derecha_dt").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "pupila_izquierda_dt"/*  && campo.descripcion != "" */) {
                    /* $("#activo_pupila_izq_dt").prop('checked', false); */
                    $("#pupila_izquierda_dt").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "glasgow_dt") {
                    $("#glasgow_dt").val(campo.descripcion);
                    $("#desc_glasgow_dt").html(valoracionGlasgow_dt[campo.descripcion]);
                  } else if (campo.keywordParaMostrarEnModal === "rass_dt" && campo.descripcion) {
                    $('#rass_dt').val(campo.descripcion); 
                    $('#puntaje_calculado_rass_dt').val(`${campo.descripcion} | ${intrepretacionRass_dt[campo.descripcion]}`); 
                    $('#desc_rass_dt').html(descripcionRass_dt[campo.descripcion]);
                  } else if (campo.keywordParaMostrarEnModal === "pam_dt") {
                    $("#pam_dt").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "modalidad_dt") {
                    $("#modalidad_final_dt").val(campo.descripcion);
                    if (campo.descripcion == "PRESION") {
                      $("#md_presion_dt").prop('checked', true);
                    }
                    if (campo.descripcion == "VOLUMEN") {
                      $("#md_volumen_dt").prop('checked', true);
                    }
                  } else if (campo.keywordParaMostrarEnModal === "vmni_dt" && campo.descripcion != '') {
                    $("#vmni_dt_toggle").prop('checked', true);
                    $("#vm_desc_dt").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "vmi_dt" && campo.descripcion != '') {
                    $("#vmi_dt_toggle").prop('checked', true);
                    $("#vm_desc_dt").val(campo.descripcion);
                  } else if (campo.keywordParaMostrarEnModal === "fijacion_der_dt" && campo.descripcion != '') {
                    $("#ft_der_dt").prop('checked', true);
                    $("#ft_valor_dt").val(campo.descripcion);
                    /* $("#fijacion_der_dt").val(campo.descripcion); */
                  } else if (campo.keywordParaMostrarEnModal === "fijacion_izq_dt" && campo.descripcion != '') {
                    $("#ft_izq_dt").prop('checked', true);
                    $("#ft_valor_dt").val(campo.descripcion);
                    /* $("#fijacion_izq_dt").val(campo.descripcion); */
                  } else {
                    $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion);
                  }
                });
                const detalles_infusiones = camposActualizablesNecesarios.filter((detalle) => {
                  if (detalle.keywordParaMostrarEnModal == "infusiones_dt") {
                    return detalle;
                  }
                });
                if (detalles_infusiones.length > 0) {
                  renderInfusionesParaDetalle(detalles_infusiones);
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

      function validarExistenciaFirma(ruta_firma, id_monitoreo, fecha_hora) {
        console.log('llega, no valido');
        if (ruta_firma == "" || ruta_firma == null) {
          console.log('esta validando');
          $("#firmaMonitoreo").prop('disabled', false);
          $("#firmaMonitoreo").attr('data-monitoreo-id', id_monitoreo);
          $("#firmaMonitoreo").attr('data-monitoreo-date', fecha_hora);
        } else {
          console.log('esta validando');
          $("#firmaMonitoreo").prop('disabled', true);
          $("#firmaMonitoreo").attr('data-monitoreo-id', '');
          $("#firmaMonitoreo").attr('data-monitoreo-date', '');
        }
      }

      function renderInfusionesParaDetalle(detalles_infusiones) {
        const [detalle_infusion] = detalles_infusiones;
        const infusiones = JSON.parse(detalle_infusion.descripcion);
        infusiones.forEach((infusion, i) => {
          let newRow = "";
              newRow += `<tr class="row-infusion_dt col-md-12" row-counter="${i}">`;
              newRow +=
                  `<td class="table_td_input-xl"><input disabled type="text" data-toggle="tooltip" data-placement="top" readonly title="No se puede editar esto." value="${infusion.nombre}" name="nombre_infusion_dt[]" required="" id="nombre_infusion_dt` +
                  i + '" class="input-sm" style="margin-left:4px; color:red; cursor:not-allowed;" />';
              newRow +=
                  `<td class="table_td_input-sm"><input disabled type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad" value="${infusion.cantidad}" name="infusiones_cantidad_dt[]" required="" id="infusiones_cantidad_dt` +
                  i + '" class="input-sm" style="margin-left:4px; text-align:left" />';
              newRow += '<input disabled type="hidden" class="form-control typeahead tt-query" id="infusiones_dt' + i +
                  `" name="infusiones_dt[]" value="${infusion.id}"></td>`;
              /* newRow +=
                  '<td class="table_td_delete"><button disabled title="No se puede eliminar una infusion ya registrada." type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>'; */
              newRow += '</tr>';
  
              $('#tblInfusiones_dt tbody').append(newRow);
        });
      }

      $('#closeMonitoreoDetalle').click(function() {
        $('#FormularioMonitoreoDetalle').trigger("reset");
        $('#modalMonitoreoDetalle').modal('hide');
        $("#conciencia_monitoreo_dt").val('').change();
        $("#grado_dependencia_dt").val('').change();
        $('#pdtila_izquierda_dt').prop('readonly', true);
        $('#pdtila_derecha_dt').prop('readonly', true);
        $('.calcularEscalaGlasgowMonitoreo_dt').prop('disabled', false);
        $('#calcularEscalaRass_dt').prop('disabled', false);
        $('#ft_valor_dt').prop('disabled', true);
        $('#tblInfusiones_dt tbody').html('');
        // CAMBIAR LOGICA DE REGISTRO Y ACTUALIZACION AL MODELO DE TUBO (esto es vmi y vmni)
        $('#vm_desc_dt').prop('disabled', true);
        $('#vm_desc_dt').prop('name', '');
        const allTabs = document.querySelectorAll('.tabMoni_dt');
        const allAccessTabs = document.querySelectorAll('.accesTab_dt');
        allTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allAccessTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allTabs[0].classList.add('active');
        allTabs[0].classList.add('in');
        allAccessTabs[0].classList.add('active');
        /* todosLosCamposActualizables_dt.forEach((campo) => {
          $("#" + campo).prop('disabled', false);
        }); */
        console.log('El modal ha sido cerrado.');
      });

      $('#modalMonitoreoDetalle').on('hidden.bs.modal', function (e) {
        $('#FormularioMonitoreoDetalle').trigger("reset");
        $('#modalMonitoreoDetalle').modal('hide');
        $("#conciencia_monitoreo_dt").val('').change();
        $("#grado_dependencia_dt").val('').change();
        $('#pdtila_izquierda_dt').prop('readonly', true);
        $('#pdtila_derecha_dt').prop('readonly', true);
        $('.calcularEscalaGlasgowMonitoreo_dt').prop('disabled', false);
        $('#calcularEscalaRass_dt').prop('disabled', false);
        $('#ft_valor_dt').prop('disabled', true);
        $('#tblInfusiones_dt tbody').html('');
        // CAMBIAR LOGICA DE REGISTRO Y ACTUALIZACION AL MODELO DE TUBO (esto es vmi y vmni)
        $('#vm_desc_dt').prop('disabled', true);
        $('#vm_desc_dt').prop('name', '');
        const allTabs = document.querySelectorAll('.tabMoni_dt');
        const allAccessTabs = document.querySelectorAll('.accesTab_dt');
        allTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allAccessTabs.forEach((tab) => {
          tab.classList.remove('active');
        });
        allTabs[0].classList.add('active');
        allTabs[0].classList.add('in');
        allAccessTabs[0].classList.add('active');
        /* todosLosCamposActualizables_dt.forEach((campo) => {
          $("#" + campo).prop('disabled', false);
        }); */
        console.log('El modal ha sido cerrado.');
      });
      
      $("#actualizarMonitoreo").on( "click", function() {
        /* $(this).attr('disabled', true); */
      });

      $('#addInfusion_dt').on('click', function() {
          AddInfusion_dt();
      });

      $('#tblInfusiones_dt tbody').on('click', 'button.deleteFila', function() {
                var obj = $(this);
                obj.parent().parent().remove();
      });
      
      $(document).ready(function () {
        $("#grado_dependencia_dt").select2({
          width: '100%',
          height: '30px',
        });
        $("#conciencia_monitoreo_dt").select2({
          width: '100%',
          height: '30px',
        });
        $('#modalMonitoreoDetalle input, #modalMonitoreoDetalle select').prop('disabled', true);
      });
      
  </script>