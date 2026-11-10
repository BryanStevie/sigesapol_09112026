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

  .row-infusion {
    width: 100%;
  }

  #tblInfusiones {
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

  .borde-saturacion-hipoxia-grave {
    border-color: red;
    border: 2px red solid;
}

.borde-saturacion-hipoxia-moderada {
    border-color: orange;
    border: 2px orange solid;
}

.borde-saturacion-hipoxia-leve {
    border-color: yellow;
    border: 2px yellow solid;
}

.borde-saturacion-normal {
    border-color: green;
    border: 2px green solid;
}

.borde-imc-bajopeso {
    border-color: skyblue;
    border: 2px skyblue solid;
}

.borde-imc-normal {
    border-color: green;
    border: 2px green solid;
}

.borde-imc-sobrepeso {
    border-color: yellow;
    border: 2px yellow solid;
}

.borde-imc-obesidad1 {
    border-color: orange;
    border: 2px orange solid;
}

.borde-imc-obesidad2 {
    border-color: orangered;
    border: 2px orangered solid;
}

.borde-imc-obesidad3 {
    border-color: red;
    border: 2px red solid;
}

.borde-temperatura-hipotermia {
    border-color: blue;
    border: 2px blue solid;
}

.borde-temperatura-normal {
    border-color: green;
    border: 2px green solid;
}

.borde-temperatura-fiebre {
    border-color: yellow;
    border: 2px yellow solid;
}

.borde-temperatura-fiebre-alta {
    border-color: orange;
    border: 2px orange solid;
}

.borde-temperatura-hipertermia {
    border-color: red;
    border: 2px red solid;
}

</style>

<script>  

      // TODO: SI LOS SELECT NO TIENEN UNA OPCION SELECCIONADA, DEBE MOSTRAR UN SPAN
      // TODO: IMPLEMENTAR MANJEO DE ERRORES PARA LAS PETICIONES HTTP
      // TODO: BLOQUEAR FUNCIONES DE EDITAR CUANDO YA ESTE FIRMADO EL MONITOREO POR TURNO
      // TODO: IMPLEMENTAR VALIDADORES EN EL REGISTRO
      // TODO: COREGIR TEMPLATE DE PDF
      // TODO: IMPLEMENTAR LA FIRMA DE MONITOREO

      // Solo puede ser de 3 a 15
      const valoracionGlasgow = {
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

      $('#crearMonitoreo').click(function () {
        $("#registrarMonitoreo").attr("disabled",false);
        $('#FormularioMonitoreo').trigger("reset");
        $('#tituloFormularioMonitoreo').html("Registro Nuevo Monitoreo");
        $('#modalMonitoreo').modal('show');
        $("#tipo_proceso").select2({ width: 'resolve'}); 
        $("#tamanio_proceso").select2({ width: 'resolve'}); 
        const puntajeGlasgowPrevio = this.getAttribute('data-user-glasgow');
        $("#glasgow").val(`${puntajeGlasgowPrevio}`);
        $("#desc_glasgow").html(`${valoracionGlasgow[puntajeGlasgowPrevio.toString()]}`);
        renderUserData();
        renderUserDiagnosticos(JSON.parse(this.nextElementSibling.innerHTML));
        $('#asegurado_id_monitoreo').val(this.getAttribute('data-user-id'));
        $('#id_enfermeria').val(this.getAttribute('data-enfermeria-id'));
      });

      $('#closeMonitoreo').click(function() {
        $('#FormularioMonitoreo').trigger("reset");
        /* $('#modalMonitoreo').modal('hide'); */
        /* $('#ft_valor').prop('disabled', true);
        $('#ft_valor').prop('name', ''); */
        $("#conciencia_monitoreo").val('').change();
        $("#grado_dependencia").val('').change();
        $('#pupila_izquierda').prop('readonly', true);
        $('#pupila_derecha').prop('readonly', true);
        $('#ft_valor').prop('readonly', true);
        $('#desc_rass').html('');
        $('#temp').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado').html('');
        $('#pulso_fc').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html('');
        $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html('');
        $('#sa02').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado').html('');
        $('#fi02').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado').html('');
        // Implementar nueva logica
        $('#vm_desc').prop('readonly', true);
        /* $('#vm_desc').prop('disabled', true);
        $('#vm_desc').prop('name', ''); */
        $('#tblInfusiones tbody').html('');
        const allTabs = document.querySelectorAll('.tabMoni');
        const allAccessTabs = document.querySelectorAll('.accesTab');
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
      });

      $('#modalMonitoreo').on('hidden.bs.modal', function (e) {
        $('#FormularioMonitoreo').trigger("reset");
        /* $('#modalMonitoreo').modal('hide'); */
        /* $('#ft_valor').prop('disabled', true);
        $('#ft_valor').prop('name', ''); */
        $("#conciencia_monitoreo").val('').change();
        $("#grado_dependencia").val('').change();
        $('#pupila_izquierda').prop('readonly', true);
        $('#pupila_derecha').prop('readonly', true);
        $('#ft_valor').prop('readonly', true);
        $('#desc_rass').html('');
        $('#temperatura').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado').html('');
        $('#pulso_fc').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html('');
        $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html('');
        $('#sa02').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado').html('');
        $('#fi02').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado').html('');
        // Implementar nueva logica
        $('#vm_desc').prop('readonly', true);
        /* $('#vm_desc').prop('disabled', true);
        $('#vm_desc').prop('name', ''); */
        $('#tblInfusiones tbody').html('');
        const allTabs = document.querySelectorAll('.tabMoni');
        const allAccessTabs = document.querySelectorAll('.accesTab');
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
      });

      $('#addInfusion').on('click', function() {
          AddInfusion();
      });

      $('#tblInfusiones tbody').on('click', 'button.deleteFila', function() {
          var obj = $(this);
          obj.parent().parent().remove();
      });

      // MENSAJES DE ALERTA:
      $("#fi02").on('blur', function(event) {         
        if (this.value != '') {
          if (parseInt(this.value) < 21 || parseInt(this.value) > 100) {
            console.log('no esta vacio');
            $('#fi02').addClass('borde-temperatura-hipertermia');
            $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
            $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
          } 
        }
      });
      $("#fi02").on('input',function() {
        $('#fi02').removeClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado').html('');
      });

      $("#sa02").on('blur', function(event) {         
        var value = $(this).val();
        if (value != '') {
          if (parseInt(this.value) < 60 || parseInt(this.value) > 100) {
            $('#sa02').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02').addClass('borde-temperatura-hipertermia');
            $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
            $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
          } else if (value >= 60 && value <= 85){
            //HIPOXIA GRAVE  - ROJO
            $('#sa02').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02').addClass('borde-saturacion-hipoxia-grave');
            $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
          } else if (value >= 86 && value <= 90){
            //HIPOXIA MODERADA - NARANJA
            $('#sa02').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02').addClass('borde-saturacion-hipoxia-moderada');
            $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
          } else if (value >= 91 && value <= 94){
            // HIPOXIA LEVE - AMARILLO
            $('#sa02').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02').addClass('borde-saturacion-hipoxia-leve');
            $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
          } else if (value >= 95 && value <= 100){
            //NORMAL - VERDE
            $('#sa02').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
            $('#sa02').addClass('borde-saturacion-normal');
            $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
          }
        }
      });
      $("#sa02").on('input',function() {
        $('#sa02').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
        $('#saturacion-aceptado').html('');
      });

      $("#temp").on('blur', function (event) {
        var value = $(this).val();
        if (value != '') {
          if(parseInt(this.value) < 35 || parseInt(this.value) > 40) {
            $('#temp').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');
          } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
              $('#temp').addClass('borde-temperatura-hipotermia');
              $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

          } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
              $('#temp').addClass('borde-temperatura-normal');
              $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

          } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
              $('#temp').addClass('borde-temperatura-fiebre');
              $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

          } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
              $('#temp').addClass('borde-temperatura-fiebre-alta');
              $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

          } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
              $('#temp').addClass('borde-temperatura-hipertermia');
              $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
          }
        }
      });
      $("#temp").on('input',function() {
        $('#temp').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
        $('#texto-aceptado').html('');
      });

      $("#fc").on('blur',function(event) {
        var value = $('#fc').val();
        if (value != '') {
          if (parseInt(value) < 50 || parseInt(value) > 150) {
            $('#fc').addClass('borde-temperatura-hipertermia');
            $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
            $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
          }
        }
      });
      $("#fc").on('input',function() {
        $('#fc').removeClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html('');
      });

      $("#fr").on('blur',function(event) {
        var value = $('#fr').val();
        if (value != '') {
          if (parseInt(value) < 10 || parseInt(value) > 35) {
            $('#fr').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria debe estar entre 10 a 35 ( /min )' + "</strong></p>");
            $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
          }
        }
      });
      $("#fr").on('input',function() {
        $('#fr').removeClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html('');
      });
      
      $(document).ready(function () {
        $("#grado_dependencia").select2({
          width: '100%',
          height: '30px',
        });
        $("#grado_conciencia").select2({
          width: '100%',
          height: '30px',
        });
        /* $('#FormularioMonitoreo input[type="text"]').on('input', function() {
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        }); */
        $('#registrarMonitoreo').click(function (e) {
          if ($("#tubo_izq").val() == '1') {
            $("#fijacion_izq").val($("#ft_valor").val());
          } else {
            $("#fijacion_izq").val('');
          }
          if ($("#tubo_der").val() == '1') {
            $("#fijacion_der").val($("#ft_valor").val());
          } else {
            $("#fijacion_der").val('');
          }
          if ($("#active_vmi").val() == '1') {
            $("#vmi").val($("#vm_desc").val());
          } else {
            $("#vmi").val('');
          }
          if ($("#active_vmni").val() == '1') {
            $("#vmni").val($("#vm_desc").val());
          } else {
            $("#vmni").val('');
          }
          $("#turno_reg").val($('#turno').val());
          if ($("#puntaje_calculado_rass_memoria").val() == '') {
            $("#puntaje_calculado_rass_memoria").val("0");
          }
          const formData = $("#FormularioMonitoreo").serialize();
          const formData2 = $("#FormularioMonitoreo").serializeArray();
          e.preventDefault(); 
          const msg = validarCamposObligatorios();
          if (msg == '') {
            swal({
              buttons: true,
              title: "¿Desea Realizar el Registro?",
              text: "Recuerde verificar que todos los datos sean correctos.",
              icon: "warning",
              dangerMode: true,
            }).then((willDelete) => {
              $(this).attr('disabled', true);
              if (willDelete) { 
                $.ajax({
                      url: '/RegistrarDetalleDeMonitoreo',
                      type: 'POST',
                      data: formData,
                      success: function(response) {
                          console.log(response);
                          $('#FormularioMonitoreo').trigger("reset");
                          $('#modalMonitoreo').modal('hide');
                          /* $('#ft_valor').prop('disabled', true);
                          $('#ft_valor').prop('name', ''); */
                          $("#conciencia_monitoreo").val('').change();
                          $("#grado_dependencia").val('').change();
                          $('#pupila_izquierda').prop('readonly', true);
                          $('#pupila_derecha').prop('readonly', true);
                          $('#ft_valor').prop('readonly', true);
                          $('#desc_rass').html('');
                          // Implementar nueva logica
                          $('#vm_desc').prop('readonly', true);
                          /* $('#vm_desc').prop('disabled', true);
                          $('#vm_desc').prop('name', ''); */
                          $('#tblInfusiones tbody').html('');
                          $('#temperatura').removeClass('borde-temperatura-hipotermia borde-temperatura-hipotermia borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
                          $('#texto-aceptado').html('');
                          $('#pulso_fc').removeClass('borde-temperatura-hipertermia');
                          $('#frec_cardiaca-aceptado').html('');
                          $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
                          $('#frecuencia_respiratoria-aceptado').html('');
                          $('#sa02').removeClass('borde-temperatura-hipertermia borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
                          $('#saturacion-aceptado').html('');
                          $('#fi02').removeClass('borde-temperatura-hipertermia');
                          $('#oxigenacion-aceptado').html('');
                          const allTabs = document.querySelectorAll('.tabMoni');
                          const allAccessTabs = document.querySelectorAll('.accesTab');
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
                          $("#listarMonitoreoDetalles").click();
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
          } else {
            bootbox.alert(msg);
            return false;
          }
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

      function AddInfusion() {
        const allRowsRenderized = document.querySelectorAll('.row-infusion');
        const allInfusionesIngresadas = Array.from(allRowsRenderized).map(function(row) {
          return row.querySelector(`#infusiones${row.getAttribute('row-counter')}`).value;
        });
        const lastRow = allRowsRenderized[allRowsRenderized.length - 1];
        var ind = 0;

        if (lastRow) {
          const rowId = lastRow.getAttribute('row-counter');
          var lastValue = lastRow.querySelector(`#infusiones${rowId}`).value;
          if(lastValue == ""){
            bootbox.alert("Ingrese la infusion anterior primero.");
            return false;
          }
          var ind = parseInt(lastRow.getAttribute('row-counter')) + 1;
        }

        var newRow = "";
        newRow += `<tr class="row-infusion col-md-12" row-counter="${ind}">`;
        newRow +=
            '<td class="table_td_input-xl"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la infusion" name="nombre_infusion[]" required="" id="nombre_infusion' +
            ind + '" class="input-sm" style="margin-left:4px; color:red; text-align:left" />';
        newRow +=
            '<td class="table_td_input-sm"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad" name="infusiones_cantidad[]" required="" id="infusiones_cantidad' +
            ind + '" class="input-sm" style="margin-left:4px; text-align:left" onkeypress="return esEntero(event)"/>';
        newRow += '<input type="hidden" class="form-control typeahead tt-query" id="infusiones' + ind +
            '" name="infusiones[]" value=""></td>';
        newRow +=
            '<td class="table_td_delete"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';

        $('#tblInfusiones tbody').append(newRow);

        $("#nombre_infusion" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                $.ajax({
                    url: `/api/intervenciones-infusiones/listado?data=${$("#nombre_infusion" + ind).val().toUpperCase()}`,
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
                $("#infusiones" + ind).val(infusiones);
                $("#infusiones" + ind).prop("readonly", true);
                $("#nombre_infusion" + ind).prop("readonly", true);
            }
        });
      }

      function validarCamposObligatorios() {
        let msg = '';
        const fecha_hora_monitoreo = $('#fecha_hora_monitoreo').val();
        const activo_pupila_der = $('#activo_pupila_der');
        const activo_pupila_izq = $('#activo_pupila_izq');
        const turno = $('#turno').val();
        const pa_1 = $('#pa_1').val();
        const pa_2 = $('#pa_2').val();
        console.log({fecha_hora_monitoreo, turno});
        if (fecha_hora_monitoreo == "") {
          msg += 'Debe seleccionar la hora del monitoreo.<br>';
        }
        if (turno == "") {
          msg += 'Debe seleccionar el turno del monitoreo.<br>';
        }
        if (pa_1 != '') {
          if (pa_2 == '') {
            msg += 'Debe colocar la presion diastolica.<br>';
          }
        }
        if (pa_2 != '') {
          if (pa_1 == '') {
            msg += 'Debe colocar la presion sistolica.<br>';
          }
        }
        if (!activo_pupila_der.is(':checked')) {
          if ($('#cantidad_pupila_der').val() == '') {
            msg += 'Debe ingresar el valor de la pupila derecha.<br>';
          }
        }
        if (!activo_pupila_izq.is(':checked')) {
          if ($('#cantidad_pupila_izq').val() == '') {
            msg += 'Debe ingresar el valor de la pupila izquierda.<br>';
          }
        }
        return msg;
      }

      function getHoraFromInput(datetime) {
        const {0: hora} = datetime.split("T").reduce((acc, curr, index) => {
          acc[index] = curr;
          return acc;
        }, {});
        const [hours, minutes] = hora.split(':');
        const formattedTime = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
        return {formattedTime};
      }

      function isTimeInRange(time, startTime, endTime) {
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

      const inputFechaConHora = document.getElementById('fecha_hora_monitoreo');
      inputFechaConHora.addEventListener('change', function(e) {
        const {fecha, formattedTime: hora} = getHoraFromInput(e.target.value);
        console.log(hora);
        $("#turno").val(isTimeInRange(hora, "07:30", "13:29") ? "2" : isTimeInRange(hora, "13:30", "19:29") ? "3" : isTimeInRange(hora, "19:30", "07:29") ? "4" : "").change();
      });

      const selectTurno = document.getElementById('turno');
      selectTurno.addEventListener('change', function(e) {
        if (inputFechaConHora.value) {
          const {fecha, formattedTime: hora} =  getHoraFromInput(inputFechaConHora.value);
          if ($("#turno").val() == '2') {
            console.log('turno ma;ana');
            if(!isTimeInRange(hora, "07:30", "13:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno").val(isTimeInRange(hora, "07:30", "13:29") ? "1" : isTimeInRange(hora, "13:30", "19:29") ? "2" : isTimeInRange(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
          if ($("#turno").val() == '3') {
            console.log('turno tarde');
            if(!isTimeInRange(hora, "13:30", "19:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno").val(isTimeInRange(hora, "07:30", "13:29") ? "1" : isTimeInRange(hora, "13:30", "19:29") ? "2" : isTimeInRange(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
          if ($("#turno").val() == '4') {
            console.log('turno noche');
            if(!isTimeInRange(hora, "19:30", "07:29")) {
              Swal.fire({
                  icon: "error",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                  confirmButtonText: "ENTENDIDO",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: "ENTENDIDO",
              });
              e.preventDefault();
              $("#turno").val(isTimeInRange(hora, "07:30", "13:29") ? "1" : isTimeInRange(hora, "13:30", "19:29") ? "2" : isTimeInRange(hora, "19:30", "07:29") ? "3" : "").change();
            }
          }
        }
      });

      const activoPupIzq = document.getElementById("activo_pupila_izq");
      activoPupIzq.addEventListener('change', function(e) {
        if (this.checked) {
          $("#cantidad_pupila_izq").attr('readonly', 'readonly');
          $("#cantidad_pupila_izq").val('');
        } else {
          $("#cantidad_pupila_izq").removeAttr('readonly');
        }
      });
      const activoPupDer = document.getElementById("activo_pupila_der");
      activoPupDer.addEventListener('change', function(e) {
        if (this.checked) {
          $("#cantidad_pupila_der").attr('readonly', 'readonly');
          $("#cantidad_pupila_der").val('');
        } else {
          $("#cantidad_pupila_der").removeAttr('readonly');
        }
      });

      const pa_1 = document.getElementById("pa_1");
      const pam = document.getElementById("pam");
      pa_1.addEventListener('keyup', function(e) {
        console.log({pas: pa_1.value, pad: pa_2.value});
        pam.value = (parseInt(pa_1.value == '' ? 0 : pa_1.value) + (parseInt(pa_2.value == '' ? 0 : pa_2.value) * 2))/3;
      });

      const pa_2 = document.getElementById("pa_2");
      pa_2.addEventListener('keyup', function(e) {
        console.log({pas: pa_1.value, pad: pa_2.value});
        pam.value = (parseInt(pa_1.value == '' ? 0 : pa_1.value) + (parseInt(pa_2.value == '' ? 0 : pa_2.value) * 2))/3;
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
      }); */

      // FIJACION DE TUBO
      const fijacion_der = document.getElementById('ft_der');
      const fijacion_izq = document.getElementById('ft_izq');
      const fijacion_valor = document.getElementById('ft_valor');

      fijacion_der.addEventListener('change', function(e) {
        if (fijacion_der.value == 'on') {
          $('#tubo_der').val('1');
          $('#tubo_izq').val('0');
        }
        if (fijacion_valor.readOnly) {
          fijacion_valor.readOnly = false;
        }
      });
      fijacion_izq.addEventListener('change', function(e) {
        if (fijacion_izq.value == 'on') {
          $('#tubo_der').val('0');
          $('#tubo_izq').val('1');
        }
        if (fijacion_valor.readOnly) {
          fijacion_valor.readOnly = false;
        }
      });
      /* fijacion_der.addEventListener('click', function(e) {
        fijacion_valor.setAttribute('name', 'fijacion_der');
      });
      fijacion_izq.addEventListener('click', function(e) {
        fijacion_valor.setAttribute('name', 'fijacion_izq');
      }); */

      const vmi = document.getElementById('vmi_toggle');
      const vmni = document.getElementById('vmni_toggle');
      const vm_desc = document.getElementById('vm_desc');

      vmi.addEventListener('change', function(e) {
        if (vmi.value == 'on') {
          $('#active_vmi').val('1');
          $('#active_vmni').val('0');
        }
        if (vm_desc.readOnly) {
          vm_desc.readOnly = false;
        }
      });
      vmni.addEventListener('change', function(e) {
        if (vmni.value == 'on') {
          $('#active_vmni').val('1');
          $('#active_vmi').val('0');
        }
        if (vm_desc.readOnly) {
          vm_desc.readOnly = false;
        }
      });
      /* vmi.addEventListener('click', function(e) {
          vm_desc.setAttribute('name', 'vmi');
      });
      vmni.addEventListener('click', function(e) {
        vm_desc.setAttribute('name', 'vmni');
      }); */

      const md_presion = document.getElementById('md_presion');
      const md_volumen = document.getElementById('md_volumen');

      md_presion.addEventListener('change', function(e) {
        if (md_presion.value == 'on') {
          $('#modalidad_final').val('PRESION');
        }
      });
      md_volumen.addEventListener('change', function(e) {
        if (md_volumen.value == 'on') {
          $('#modalidad_final').val('VOLUMEN');
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
      }
      
  </script>