
<style>

  .title-dialog {
    font-size: 18px;
    font-family:Arial, Helvetica, sans-serif;
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
    font-family:Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete > li > div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }

  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF!important;
    background: #1d71b8!important;
    cursor: pointer;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  input[readonly]{
      background-color:#eeeeee;
      text-align: center
  }


  span:focus,
  textarea:focus,
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="datetime"]:focus,
  input[type="datetime-local"]:focus,
  input[type="date"]:focus,
  input[type="month"]:focus,
  input[type="time"]:focus,
  input[type="week"]:focus,
  input[type="number"]:focus,
  input[type="email"]:focus,
  input[type="url"]:focus,
  input[type="search"]:focus,
  input[type="tel"]:focus,
  input[type="color"]:focus,
  .uneditable-input:focus{
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }



  /*
  .select2-container:focus {
    border-color: rgba(126, 239, 104, 0.8);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
    outline: 0 none;
  }
  */
  /*
  .select2-container-active .select2-choice {
    border: 1px solid #999;
    box-shadow: inset 0 2px 3px 0 rgba(0,0,0,.06);
    border-radius: 4px;
  }

  .select2-container-active.select2-dropdown-open .select2-choice {
    border-bottom: 2px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-top: 1px solid #ccc;
    box-shadow: none;
  }
  */

  .valor {
    top: 1px;
    position: relative;
    z-index: 1;
    font-size: 14px;
  }

  .marca {
      position: absolute;
      top: 50%;              /* Centramos verticalmente */
      right: 30%;
      transform: translateY(-50%); /* Solo movemos en Y, no en X */
      z-index: 2;
      color: rgba(22, 125, 189, 0.8);
      font-size: 23px;
      font-weight: lighter;
      font-family: Helvetica, Arial, sans-serif;
  }

  .contenedor {
      position: relative;
      width: 30px;  /* Ajusta si necesitas más espacio */
      height: 17px; /* Ajusta según la altura de la P */
  }
  .tom-select {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 14px;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    width: 100%;
    box-shadow: none;
  }

  .tom-select .ts-control {
    border: none;
    box-shadow: none;
    background: none;
    min-height: 36px;
  }

  .tom-select .ts-dropdown {
    border-radius: 0 0 4px 4px;
    border: 1px solid #ccc;
    border-top: none;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
  }
  .select-tom.is-invalid {
      border-color: red !important;
  }

  .error-fila {
    background-color: #fdecea !important;
  }

  .campo-invalido {
    border: 2px solid #d33 !important;
    box-shadow: 0 0 6px rgba(211, 51, 51, 0.6) !important;
    border-radius: 4px;
  }
  </style>
  <style>
    #example {
        table-layout: fixed;
        width: 120%;
        word-wrap: break-word;
    }
    #example th,
    #example td {
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }
</style>

  <script>

    let procedimientosSeleccionadosAnestesia =[];
    let procedimientosSeleccionadosAdicionalAnestesia =[];
    let filasmedicamentosAnestesia = 0;
    let listIdDiagnosticoAnestesia =[];
    let listIdDiagnosticoObjetosAnestesia =[];
    let listIdDiagnosticoAnestesiaAdicionalReceta =[];
    let listIdDiagnosticoObjetosAnestesiaAdicionalReceta =[];
    let idsAdicionalesDiagnostico = null;
    let idsAdicionalesProcedimiento = null;
    let tomMedicamentos = {};

      function soloNumeros(e)
      {
          var key = window.Event ? e.which : e.keyCode;
          //alert(key);
          return ((key >= 48 && key <= 57) || key == 0 || key == 8)
      }

      function soloNumerosMenosCero(e)
      {
          var key = window.Event ? e.which : e.keyCode;
          //alert(key);
          return ((key >= 48 && key <= 57) || key == 0 || key == 8)
      }

    //--------------------------------------------------------------------------
      $(document).ready(function () {

        let codigo_siteds = $('#cod_siteds_susalud').val();
        let cod_siteds_existe = $('#cod_siteds_existe').val();

        if(!codigo_siteds && !cod_siteds_existe){
          // generarCodigoSitedsAnestesio();
        }
        function generarCodigoSitedsAnestesio() {
          const DNI = <?php echo !empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica->nro_doc_ident)?$row_anestesia_quirurgica->nro_doc_ident:''); ?>;
          console.log(DNI);  // Verificar si tiene un valor correcto
            // Mostrar el SweetAlert2 de carga
          Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
            html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Validación previa a la generación de código
          const isValidate = validateGenerateSiteds();
          if (isValidate) {
            Swal.hideLoading();
            Swal.fire({
              icon: 'error',
              title: 'Validación fallida',
              text: 'La validación del formulario falló. Corrige los errores.',
              confirmButtonText: 'Aceptar'
            });
            return;  // Detenemos la ejecución si la validación falla
          }

          // Crear el cuerpo de la solicitud
          const ipressJson = <?php echo json_encode($ipress);?>;
          const body = {
            "idRemitente": ipressJson.codigo,
            "nuRucRemitente": "",
            "idCorrelativo": '00000001',
            "tiDoPaciente": {{ !empty($prestacion->tipo_doc_ident)?$prestacion->tipo_doc_ident: (!empty($row_anestesia_quirurgica) ? $row_anestesia_quirurgica->tipo_doc_ident : null)}},
            "nuDoPaciente": {{ !empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_doc_ident:'')}},
            "noResponsableAut": "",
            "noPaResponsableAut": "",
            "noMaResponsableAut": "",
            "nuDoResponsableAut": "",
            "coAdmisionista": {{!empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_doc_ident:'')}},
            "beMaxInicial": "0",
            "coTiMoneda": "1",
            "coPagoFijo": "0",
            "coCalServicio": "VS",
            "coPagoVariable": "100",
            "flagCaGarantia": "0",
            "deFlagCartaGarantia": "Sigesapol 0.1",
            "tipoAtencionId": 3,
            "servicioId": <?php echo $id_servicio;?>,
            "forceTempCodeGeneration": parseInt($("#statusResponseWsSusalud").val()) === 1 ? true : false
          };

          console.log(body);
          // Hacer la solicitud AJAX
          $.ajax({
            url: '/ws-dirsapol-siteds',
            type: 'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(body),
            success: (result, textStatus, jqXHR) => {
              $("#cod_siteds_susalud").val(result.nuAutorizacion);
              // Actualizar el modal a éxito
              Swal.fire({
                icon: 'success',
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS Generado</span></div>",
                showConfirmButton: false,
                timer: 1500
              });
            },
            error: (xhr, status, error) => {
              $("#cod_siteds_susalud").val("");
              // Obtener el mensaje de error
              const messageError = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : "Servicio no disponible. Intentelo en unos minutos.";

              // Mostrar el modal de error
              Swal.fire({
                icon: 'error',
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
                customClass: 'swal-wide',
              });
            },
            complete: () => {
              // Ocultar el loading después de que la solicitud ha terminado
              Swal.hideLoading();
            }
          });
        }
        function validateGenerateSiteds() {
          const nro_doc_ident = {{!empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_doc_ident:'')}}

          let messageValidate = "";

          if (!nro_doc_ident || parseInt(nro_doc_ident) <= 0) {
            messageValidate += `<p> - Es necesario el numero de documento de identidad.</p>`;
          }

          if (messageValidate !== null && messageValidate.length > 0) {
            Swal.fire({
              icon: "error",
              title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
              html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 14px; text-align: left'>${messageValidate}</span></div>`,
              confirmButtonText: 'ENTENDIDO',
              customClass: 'swal-wide',
            });
            return true;
          }
          return false;
        }

          let prestacionFirmada = $('#prestacionFirmada').val();
          //if (prestacionFirmada == '1') { //Esta firmada

          //} else {
            $('#modalNuevoAnestesia').on('hidden.bs.modal', function () {
                $('#modalAnestesia').modal('handleUpdate');
                $('body').addClass('modal-open');
            });

            let flag_anestesia_quirurgica = $('#flag_anestesia_quirurgica').val();
            console.log(`flag_anestesia_quirurgica : ${flag_anestesia_quirurgica}`);

            if (flag_anestesia_quirurgica == '1') {
              var tiene_intervencion = {{ isset($row_anestesia_quirurgica->tiene_intervencion) && $row_anestesia_quirurgica->tiene_intervencion ? 'true' : 'false' }};
              if(tiene_intervencion){
                $('#addConfirmaIntervencion').click();
                $('.addConfirmaIntervencion').show();
              }
              var tiene_observacion = {{ isset($row_anestesia_quirurgica->tiene_observacion) && $row_anestesia_quirurgica->tiene_observacion ? 'true' : 'false' }};
              if(tiene_observacion){
                $('#addObservacionIntervencionQuirurgica').click();
                $('.addObservacionIntervencionQuirurgica').show();
              }

              idsAdicionalesDiagnostico = {!! json_encode(isset($ids_adicionales_diagnostico) ? $ids_adicionales_diagnostico : []) !!};
              console.table(idsAdicionalesDiagnostico);
              idsAdicionalesProcedimiento =  {!! json_encode(isset($ids_adicionales_procedimiento) ? $ids_adicionales_procedimiento : []) !!};
            }

            // let flag_observacion = $('#flag_observacion').val();

            // if (flag_observacion == '1') {
            //   $('#addObservacionIntervencionQuirurgica').click();
            //   $('.addObservacionIntervencionQuirurgica').show();
            // }
            //modalRadiologico();

            //getProductoAdicionalAnestesia();
            getDiagnosticosDePrestacion();
            getIntervencion();

            $(".js-example-basic-multiple").select2({
              width: 'resolve', // need to override the changed default
              max_selected_options: 4
            });

            var selectedValues = new Array();
            var existeTipoAnestesiacq = false;
            <?php
                if (isset($listaTipoAnestesiacq) && !is_null($listaTipoAnestesiacq)) {
                $n = 0;
                foreach ($listaTipoAnestesiacq as $row):
                    ?>
                    selectedValues[<?php echo $n?>] = "<?php echo $row->id_tipo_anestesia?>";
                    <?php
                    $n++;
                endforeach;
            ?>
              existeTipoAnestesiacq = true;
            <?php
              }
            ?>
            if (existeTipoAnestesiacq) {
              $('.js-example-basic-multiple').val(selectedValues).trigger('change');
            }
          //}

          modalHistorial();
          modalLaboratorio();
          modalRadiologico();
          modalInterconsulta();
        });

      function getIntervencion() {
        let id_anestesia_quirurgica = $('#id_anestesia_quirurgica').val();
        if (id_anestesia_quirurgica == "0") {
          let id_intervencion = $('#id_intervencion').val();
          $.ajax({
            //url: '../obtener_diagnostico/' + request.term,
            url: '../obtener_intervencion_para_anestesia/' + id_intervencion,
            type: "GET",
            dataType: "json",
            success: function(data)  {
              if (data) {
                // $('#addConfirmaIntervencion').attr("aria-pressed", "true");
                // $('#addConfirmaIntervencion').addClass("active");
                $('#id_sala').val(data.id_sala_intervencion);

                let fechaIntervencion = data.fecha_inicio_intervencion.split(" ")[0];  // "19:16:00"
                let horaInicio = data.fecha_inicio_intervencion.split(" ")[1];  // "19:16:00"
                let horaInicioFormatted = horaInicio.substring(0, 5); // "19:16"
                let horaFin = data.fecha_fin_intervencion.split(" ")[1];
                let horaFinFormatted = horaFin.substring(0, 5);

                $('#fecha_intervencion').val(fechaIntervencion);
                $('#hora_inicio_intervencion').val(horaInicioFormatted);
                $('#hora_fin_intervencion').val(horaFinFormatted);
                $('.addConfirmaIntervencion').show();
              }
              //$('.addConfirmaIntervencion').hide();
              console.log(`intervencion : ${data.id}`);


              // let listaDiagnosticoSeleccionado = $(".id_diagnostico_anestesia").map(function(){return parseInt($(this).val());}).get();
              // let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
              // response(filtro);
            }
          })
        }
      }

      // $(document).on("click", ".EditarFilaProductosAnestesia", function() {
      //   let row = $(this).closest("tr");

      //   row.find("input, select").prop("disabled", false);

      //   row.find("input[name='accionRowProductoAnestesia[]']").val("edit");

      //   $(this).closest('tr').css('background-color', '#87c787');
      // });
      $(document).on("click", ".EditarFilaProductosAnestesia", function() {
          let row = $(this).closest("tr");
          // Habilitar inputs/selects
          row.find("input, select").prop("disabled", false);

          // Cambiar valor del input oculto de acción a "edit"
          row.find("input[name='accionRowProductoAnestesia[]']").val("edit");

          // Eliminar los inputs hidden específicos del tr actual
          row.find("input[type='hidden'][name='cantidad_prescrita_editar[]']").remove();
          row.find("input[type='hidden'][name='posologia_editar[]']").remove();
          row.find("input[type='hidden'][name='id_via_administracion[]']").remove();
          row.find("input[type='hidden'][name='tiempo_periodo_editar[]']").remove();
          // Cambiar color de fondo para indicar edición
          row.css('background-color', '#87c787');
      });

      function getDiagnosticosDePrestacion() {
        $('.id_diagcq').each(function() {
          let value = $(this).val();
          console.log(`Valor del diagcq :${value}`);
          addNewArrayAdicionalReceta(value)
        });
      }

      function addNewArrayAdicionalReceta(id_diagnostico) {

      console.log(id_diagnostico);

      if(  listIdDiagnosticoObjetosAnestesiaAdicionalReceta.findIndex( e  => e.id == id_diagnostico) ==-1 ){
        listIdDiagnosticoAnestesiaAdicionalReceta.push(id_diagnostico);// solo es para validar si existen datos

        if (listIdDiagnosticoAnestesiaAdicionalReceta.length > 0) {
          let codigo = JSON.stringify(id_diagnostico);
          console.log(codigo);

          // Realizar la petición AJAX usando el código id_diagnostico
          $.ajax({
              "dataType": 'json',
              "type": "POST",
              "url": "/obtenerdatosDiagnosticos",
              "data":{
                codigo : codigo,
                _token: "{{ csrf_token() }}"
                    },
              "success": function (result) {
                  console.log(`Resultado de obtenerdatosDiagnosticos : ${result.objeto}`);

                  if (result) {
                    listIdDiagnosticoObjetosAnestesiaAdicionalReceta.push(result.objeto);
                    console.log(JSON.stringify(listIdDiagnosticoObjetosAnestesiaAdicionalReceta));
                    updateSelectElements();
                    updateSelectElementsAdicionalReceta();
                  } else {
                    console.log(`Retorna vacio ...!!`);
                  }
              },
              "error": function (msg, textStatus, errorThrown) {
                  //location.href="login";
                  console.log(msg, textStatus, errorThrown);

              }
          });
        }
      }
      }

      function updateSelectElementsAdicionalReceta() {
      // Obtener todos los elementos <select> con la clase "select-diagnostico"
      let selectElements = document.querySelectorAll(".miSelectProdAdicionalAnestesia");
      console.log(selectElements);

      selectElements.forEach((selectElement) => {
        // Almacenar el valor actualmente seleccionado
        let currentValue = selectElement.value;
        console.log(currentValue);

        // Eliminar las opciones actuales
        selectElement.innerHTML = "";


        // Agregar las opciones basadas en listIdDiagnosticoObjetos
        listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
          let option = document.createElement("option");
          option.value = item.id;
          option.textContent = item.codigo+' - '+item.nombre;
          selectElement.appendChild(option);
        });

        // Verificar si el valor seleccionado aún está disponible en las opciones
        let optionExists = Array.from(selectElement.options).some(
          (option) => option.value === currentValue
        );

        // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
        if (!optionExists) {
          selectElement.value = "";
        } else {
          // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
          selectElement.value = currentValue;
          console.log()
        }
      });
      }

      function validarFormulario() {

          const observacion = $('#observacion_examen').val();

          let msg = "";
          let sw = true;

          if(observacion==""){
              msg+="Debe ingresar una observación.<br>";
              sw = false;
          }

          if (sw == false) {
              Swal.fire({
                  icon: 'error',
                  title: 'Alerta',
                  html: msg,
                  showConfirmButton: true,
              });
              $('#btnReg').prop("disabled",false);
              return sw;
          } else {
              submitFrm();
          }

          return false;

      }

    function submitFrm() {
          bootbox.confirm({
              message: "Se registrará todos los examenes ingresados, ¿Está seguro de continuar?",
              buttons: {
                  confirm: {
                      label: 'Si',
                      className: 'btn-success'
                  },
                  cancel: {
                      label: 'No',
                      className: 'btn-danger'
                  }
              },
              callback: function (result) {
                  if (result == true) {
                      document.frmRadiologia.submit();
                  }
              }
          });
      }
      //--------------------------------------------------------------------------

      const resetSelectAndTriggerChange = (selectId) => { // Resetear select
          const selectElement = document.getElementById(selectId);
          if (selectElement) {
              selectElement.value = ''; // Reset value
              const event = new Event('change', { bubbles: true }); // Create a change event
              selectElement.dispatchEvent(event); // Dispatch the event
          }
      }

    function htmlEntities(str) {
        return String(str).replace('&ntilde;', 'ñ')
                          .replace('&Ntilde;', 'Ñ')
                          .replace('&amp;', '&')
                          .replace('&Ntilde;', 'Ñ')
                          .replace('&ntilde;', 'ñ')
                          .replace('&Ntilde;', 'Ñ')
                          .replace('&Agrave;', 'À')
                          .replace('&Aacute;', 'Á')
                          .replace('&Acirc;','Â')
                          .replace('&Atilde;','Ã')
                          .replace('&Auml;','Ä')
                          .replace('&Aring;','Å')
                          .replace('&AElig;','Æ')
                          .replace('&Ccedil;','Ç')
                          .replace('&Egrave;','È')
                          .replace('&Eacute;','É')
                          .replace('&Ecirc;', 'Ê')
                          .replace('&Euml;','Ë')
                          .replace(   '&Igrave;', 'Ì')
                          .replace('&Iacute;', 'Í'    )
                          .replace('&Icirc;', 'Î' )
                          .replace(   '&Iuml;', 'Ï')
                          .replace(   '&ETH;', 'Ð')
                          .replace(   '&Ntilde;', 'Ñ')
                          .replace(   '&Ograve;', 'Ò')
                          .replace(   '&Oacute;', 'Ó')
                          .replace('&Ocirc;', 'Ô' )
                          .replace(   '&Otilde;', 'Õ')
                          .replace('&Ouml;', 'Ö'  )
                          .replace('&Oslash;', 'Ø'    )
                          .replace(   '&Ugrave;' ,'Ù')
                          .replace(   '&Uacute;', 'Ú')
                          .replace(   '&Ucirc;', 'Û')
                          .replace(   '&Uuml;', 'Ü')
                          .replace(   '&Yacute;', 'Ý')
                          .replace('&THORN;', 'Þ' )
                          .replace(   '&szlig;', 'ß')
                          .replace(   '&agrave;', 'à')
                          .replace(   '&aacute;', 'á')
                          .replace(   '&acirc;', 'â')
                          .replace(   '&atilde;', 'ã')
                          .replace('&auml;', 'ä'  )
                          .replace(   '&aring;', 'å')
                          .replace(   '&aelig;', 'æ')
                          .replace(   '&ccedil;', 'ç')
                          .replace('&egrave;', 'è'    )
                          .replace('&eacute;', 'é'    )
                          .replace('&ecirc;', 'ê' )
                          .replace('&euml;', 'ë'  )
                          .replace(   '&igrave;', 'ì')
                          .replace('&iacute;', 'í'    )
                          .replace('&icirc;', 'î' )
                          .replace('&iuml;', 'ï'  )
                          .replace('&eth;', 'ð'   )
                          .replace(   '&ntilde;', 'ñ')
                          .replace('&ograve;','ò')
                          .replace('&oacute;','ó')
                          .replace('&ocirc;','ô')
                          .replace('&otilde;','õ')
                          .replace('&ouml;','ö')
                          .replace('&oslash;','ø')
                          .replace('&ugrave;','ù')
                          .replace('&uacute;','ú')
                          .replace('&ucirc;','û')
                          .replace('&uuml;' , 'ü')
                          .replace('&yacute;', 'ý')
                          .replace('&thorn;', 'þ')
                          .replace('&yuml;', 'ÿ');
      }

      function esEntero(evt){
          // code is the decimal ASCII representation of the pressed key.
          var code = (evt.which) ? evt.which : evt.keyCode;

          if(code==8) { // backspace.
          return true;
          } else if(code>=48 && code<=57) { // is a number.
          return true;
          } else{ // other keys.
          return false;
          }
      }

      $('#addRecetaValeAnestesia').on('click', function () {
        var addRecetaValeAnestesia = $('#addRecetaValeAnestesia').attr("aria-pressed");
            console.log(addRecetaValeAnestesia);

        $("#fsRecetaValeAnestesia").hide();
        if(addRecetaValeAnestesia == "false"){
            //getProductoAndAddProductoAnestesia();
            $("#fsRecetaValeAnestesia").show();
            }
      });

      function getProductoAndAddProductoAnestesia() {
          getProductoAnestesia();
          //AddProducto();
      }

      $("#id_farmacia_receta").select2({
          width: 'resolve'
      });
      function getProductoAnestesia(){

          $('#tblAnestesia tbody').html('');

          if($('#frmCqAnestesia #id_servicio').val()=="") return false;

          //var id_ipress 		= $('#id_ipress').val();
          var id_ipress 		= $('#frmCqAnestesia #id_establecimiento').val();
          //var id_consultorio 	= $('#frmCqAnestesia #id_servicio').val();
          //var tipo_receta		= $('#tipo_receta').val();
          //var id_tiporeceta 	= $('#id_tiporeceta').val();
          var id_tiporeceta 	= $('#frmCqAnestesia #id_tipo_atencion').val();

          var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
          $('#id_farmacia').val(id_farmacia);
          console.log(id_farmacia);

          var nivel     = $('#id_nivel').val();
          var x  = 0;
          var dni = $('#dni_beneficiario').val();
          var interconsulta = $('#interconsulta').val();
          let puede_atennder_servicios_cq = <?=$puede_atennder_servicios_cq ? 'true' : 'false'; ?>;
          if(puede_atennder_servicios_cq){
            let puede_atennder_servicios_cq = <?=$puede_atennder_servicios_cq ? 'true' : 'false'; ?>;
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los productos medicos de la farmacia</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                Swal.showLoading();
                }
            });
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress + '/' + id_farmacia + '/72',
                success: function(result){
                  let sin_paquete = result.productos_sin_paquetizacion;
                  let con_paquete = result.productos_con_aquetizacion;
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  var newOptionPaquetizacion = "";
                  newOptionPaquetizacion = "<option value=''>--Seleccionar-</option>";
                  $(sin_paquete).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                  });
                  $(con_paquete).each(function (ii, oo) {
                    newOptionPaquetizacion += "<option value='"+oo.id+"' ru='"+oo.ru+"' data-tipo-anestesia='"+oo.id_tipo_anestesia+"' data-stock='"+oo.stock+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                  });

                  $('#cmbMedicamentoTempAnestesia').html(newOption);
                  $('#cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia').html(newOptionPaquetizacion);
                  $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
                  $('#cmbMedicamentoTempAnestesiaNuevoTipoAnestesio').html(newOptionPaquetizacion);
                },
                complete: function () {
                  Swal.close();
                },
            });
          }
          // if(id_tiporeceta == "3"){

          //     if(interconsulta=='SI'){
          //         if (id_consultorio==104){
          //             id_consultorio2=104
          //         } else {
          //             id_consultorio2=105
          //         }
          //         $.ajax({
          //         //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
          //         url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
          //         success: function(result){
          //             var newOption = "";
          //             newOption = "<option value=''>--Seleccionar--</option>";
          //             $(result).each(function (ii, oo) {
          //             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //             });
          //             //console.log('Option producto');
          //             //console.log(newOption);
          //             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //         }
          //         });
          //     } else {

          //         if(x==0){ //SIN INTERCONSULTA
          //             if (id_consultorio==104){
          //                 $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
          //                     //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
          //                     success: function(result){
          //                     var newOption = "";
          //                     newOption = "<option value=''>--Seleccionar--</option>";
          //                     $(result).each(function (ii, oo) {
          //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                     });
          //                     //console.log('Option producto');
          //                     //console.log(newOption);
          //                     $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                     $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                     }
          //                 });
          //             } else {
          //                 if (id_consultorio==21){
          //                     $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
          //                     //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
          //                     success: function(result){
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar-</option>";
          //                         $(result).each(function (ii, oo) {
          //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                         });
          //                         // console.log('Option producto');
          //                         // console.log(newOption);
          //                         $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                         $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         //$("#id_medico").select2({max_selected_options: 4});

          //                     }
          //                     });
          //                 } else {
          //                     if (id_consultorio==72){
          //                         $.ajax({
          //                         //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
          //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
          //                         success: function(result){
          //                             var newOption = "";
          //                             newOption = "<option value=''>--Seleccionar-</option>";
          //                             $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                         });
          //                     } else {
          //                         $.ajax({
          //                         url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
          //                         //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
          //                         success: function(result){
          //                             var newOption = "";
          //                             newOption = "<option value=''>--Seleccionar-</option>";
          //                             $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                         });
          //                     }

          //                 }
          //             }
          //         } else {
          //             if (id_consultorio==104){
          //                 $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
          //                     success: function(result){
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar--</option>";
          //                         $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                         });
          //                         // console.log('Option producto');
          //                         // console.log(newOption);
          //                         $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                         $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                     }
          //                 });
          //             } else {
          //                 if (id_consultorio==21){
          //                     $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
          //                     success: function(result){
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar-</option>";
          //                         $(result).each(function (ii, oo) {
          //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                         });
          //                         // console.log('Option producto');
          //                         // console.log(newOption);
          //                         $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                         $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                     }
          //                     });
          //                 } else {
          //                     if (id_consultorio==72){
          //                         $.ajax({
          //                         //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
          //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
          //                         success: function(result){
          //                             var newOption = "";
          //                             newOption = "<option value=''>--Seleccionar-</option>";
          //                             $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                         });
          //                     } else {
          //                         $.ajax({
          //                         url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
          //                         success: function(result){
          //                             var newOption = "";
          //                             newOption = "<option value=''>--Seleccionar-</option>";
          //                             $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                         });
          //                     }
          //                 }
          //             }
          //         }
          //     }
          // }else{
          //     console.log('entro en condición tipo de atención 1 ...');

          //     if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
          //         console.log('entro en condición tipo de atención 1 ...');
          //         if(id_tiporeceta==2){
          //             id_consultorio=76;
          //         }
          //         $.ajax({
          //             url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
          //             success: function(result){
          //                 var newOption = "";
          //                 newOption = "<option value=''>--Seleccionar-</option>";
          //                 $(result).each(function (ii, oo) {
          //                     newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                 });
          //                 // console.log('Option producto');
          //                 // console.log(newOption);
          //                 $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                 $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                 //$("#id_medico").select2({max_selected_options: 4});

          //             }
          //         });

          //     }
          //     if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
          //         if(id_tiporeceta==5 || id_tiporeceta == 1){
          //             id_consultorio = 110;
          //         } else {
          //             id_consultorio = 111;
          //         }

          //         $.ajax({
          //             url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
          //             success: function(result){
          //                 var newOption = "";
          //                 newOption = "<option value=''>--Seleccionar-</option>";
          //                 $(result).each(function (ii, oo) {
          //                     newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                 });
          //                 // console.log('Option producto');
          //                 // console.log(newOption);
          //                 $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                 $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                 //$("#id_medico").select2({max_selected_options: 4});

          //             }
          //         });
          //     }
          //     if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
          //         if(id_tiporeceta == 1){
          //             if(id_farmacia == 25){
          //                 $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
          //                     success: function(result){
          //                     var newOption = "";
          //                     newOption = "<option value=''>--Seleccionar-</option>";
          //                     $(result).each(function (ii, oo) {
          //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                     });
          //                     // console.log('Option producto');
          //                     // console.log(newOption);
          //                     $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                     $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                     }
          //                 });
          //             } else {
          //                 if(id_consultorio == 72){
          //                     $.ajax({
          //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
          //                         '/' + 72,
          //                         success: function(result) {
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar-</option>";
          //                         $(result).each(function(ii, oo) {
          //                             newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
          //                             .nombre + ", " + oo.abreviatura + "</option>";
          //                         });
          //                         // console.log('Option producto');
          //                         // console.log(newOption);
          //                         $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                         $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                     });
          //                 } else {
          //                     $.ajax({
          //                     url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
          //                     success: function(result){
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar-</option>";
          //                         $(result).each(function (ii, oo) {
          //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                     });
          //                 }
          //             }
          //             //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
          //         } else {
          //             if(id_tiporeceta == 4){
          //                 if(id_consultorio == 72){
          //                     $.ajax({
          //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
          //                         '/' + 72,
          //                         success: function(result) {
          //                             var newOption = "";
          //                             newOption = "<option value=''>--Seleccionar-</option>";
          //                             $(result).each(function(ii, oo) {
          //                                 newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
          //                                 .nombre + ", " + oo.abreviatura + "</option>";
          //                             });
          //                             // console.log('Option producto');
          //                             // console.log(newOption);
          //                             $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                             $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         }
          //                     });
          //                 } else {
          //                     $.ajax({
          //                     url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
          //                     success: function(result){
          //                         var newOption = "";
          //                         newOption = "<option value=''>--Seleccionar-</option>";
          //                         $(result).each(function (ii, oo) {
          //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          //                         });
          //                         // console.log('Option producto');
          //                         // console.log(newOption);
          //                         $('#cmbMedicamentoTempAnestesia').html(newOption);
          //                         $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);
          //                         //$("#id_medico").select2({max_selected_options: 4});

          //                     }
          //                     });
          //                 }
          //             }
          //         }
          //     }
          // }
      }
  
      //$('#tblAnestesia tbody').on('click', 'button.deleteFila', function () {
      //   console.log('click');

      //   var obj = $(this);
      //   // Selecciona la fila completa
      //   var fila = obj.closest('tr');

      //   // Obtiene la posición de la fila
      //   var filaPosition = fila.index();

      //   // Imprime la posición del elemento 'input' en la consola
      //   console.log("La posición del input es: " + filaPosition);
      //   eliminarDiagnosticoXProductoAnestesia(filaPosition);
      //   // Elimina la fila
      //   obj.parent().parent().remove();
      //   filasmedicamentosAnestesia--;
      // });

      // function eliminarDiagnosticoXProductoAnestesia(posicion) {
      //   // Se elimina el elemento en la posición dada
      //   procedimientosSeleccionadosAnestesia.splice(posicion, 1);

      //   // Se actualiza la posición de los elementos restantes
      //   procedimientosSeleccionadosAnestesia.forEach((item, index) => {
      //     item.posicion = index;
      //   });
      //   console.log(procedimientosSeleccionadosAnestesia);
      // }

      $('#addProductoAnestesia').on('click', function () {
        AddProductoAnestesia();
      });
      let tomSelectInstancesProductoAnestecia={};
      function AddProductoAnestesia(){
          console.log('Entro en función AddProductoAnestesia() ... ');

          var tipo_receta = 1;
          var id_consultorio = 72;
          if(tipo_receta == ""){
              bootbox.alert("Seleccione el tipo atención");
              return false;
          }
          if(id_consultorio == ""){
              bootbox.alert("Seleccione un servicio");
              return false;
          }
          var newRow = "";
          var ind = $('#tblAnestesia tbody tr').length;
          var ind = filasmedicamentosAnestesia
          var tabindex = 71;
              var nuevalperiodo = "";
          if(ind > 0){
              $('.cmbMedicamentoAnestesia').each(function(){
                  var ind_tmp = $(this).attr("ind");
                  if(ind_tmp >= ind){
                                      ind=Number(ind_tmp)+1;
                                      tabindex+=3;
                  }
              });

              fila_medicamentos=$('#tblAnestesia tbody').children('tr');
              rowIndex=$('#hidden_anestesia_rowIndex').val();
              var idval = ind - 1;
              <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
                  fila_medicamento=fila_medicamentos[idval];
                  var valante = $(fila_medicamento).find('#nro_med_entregados').val();
                  if(valante == ""){
                      bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                      return false;
                  }
              <?php //endif;?>

              //nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
          }

          var item_producto = "";
          $('#cmbMedicamentoTempAnestesia option').each(function() {
              item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
              "</option>"
          });

          var item_via_administracion = '';
          item_via_administracion+='<option value="0">--Seleccionar--</option>';
          item_via_administracion+='<option value="1">Oral</option>';
          item_via_administracion+='<option value="2">Sublingual</option>';
          item_via_administracion+='<option value="3">Rectal</option>';
          item_via_administracion+='<option value="4">Intravenosa</option>';
          item_via_administracion+='<option value="5">Intramuscular</option>';
          item_via_administracion+='<option value="6">Subcutánea</option>';
          item_via_administracion+='<option value="7">Dérmica</option>';
          item_via_administracion+='<option value="8">Nasal</option>';
          item_via_administracion+='<option value="9">Oftalmológica</option>';
          item_via_administracion+='<option value="10">Inhalatoria</option>';
          item_via_administracion+='<option value="11">Epidural</option>';
          item_via_administracion+='<option value="12">Intratecal</option>';
          item_via_administracion+='<option value="13">Vaginal</option>';
          item_via_administracion+='<option value="14">Intraarticular</option>';
          item_via_administracion+='<option value="17">Parenteral</option>';
          item_via_administracion+='<option value="15">No Aplica</option>';
          item_via_administracion+='<option value="16">Otros</option>';

          newRow +='<tr>';
          newRow +='<td data-label="Producto farmacéutico o dispositivo médico"><select class="select-tom cmbMedicamentoAnestesia productos-responsive" id="cmbMedicamentoAnestesia' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 100%">' + item_producto + '</select></td>';

          //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
          //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
          newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
          newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
          newRow +='<td data-label="Stock Farmacia"><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm productos-responsive" style="margin-left:1px; width:100px" readonly=""/>';
          <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
          newRow +='<td data-label="Tiempo(Dias)"><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm productos-responsive" style="margin-left:1px; width:100px" value="' + nuevalperiodo +'"/>';
          newRow +='<td data-label="Via Adm."><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_anestesia productos-responsive" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion_anestesia[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
          newRow += '<td data-label="Posologia"><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
          ind + '" class="nro_solicitado posologia input-sm productos-responsive" style="margin-left:1px; width:100px" value=""/>';

          <?php //endif;?>
          newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
          newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
          newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
          newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
          newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
          newRow +='<td data-label="Cant. Pres."><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_anestesia[]" required="" id="nro_med_solictados_anestesia' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_anestesia input-sm productos-responsive" style="margin-left:1px; width:100px" /></td>';

          <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
          //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:1px; width:100px" readonly=""/></td>';
          <?php //endif;?>

          newRow += '<td data-label="DiagnosticoxProducto"><select tabindex="' + (tabindex + 4) + '" class="form-control productos-responsive miSelectProdAnestesia"  id="miSelectProdAnestesia' + ind + '" name="miSelectProdAnestesia[]"></select></td>';
          newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';

          newRow +='</tr>';
          $('#tblAnestesia tbody').append(newRow);
          // Inicializar Tom Select
          const select = new TomSelect("#cmbMedicamentoAnestesia" + ind, {
            maxItems: 1,
            plugins: ['clear_button']
          });
          tomSelectInstancesProductoAnestecia["cmbMedicamentoAnestesia" + ind] = select;
          select.focus();
          // $("#cmbMedicamentoAnestesia" + ind).select2({max_selected_options: 4});

          const selectElementProd = document.getElementById('miSelectProdAnestesia' + ind);
          console.log(listIdDiagnosticoObjetosAnestesia);


          listIdDiagnosticoObjetosAnestesia.forEach((item) => {
              let option = document.createElement('option');
              option.value = item.id;
              option.textContent = item.nombre;
              selectElementProd.appendChild(option);
          });
          $("#abrir-modal-" + ind).click(function(e) {
              e.stopPropagation();

              abrirModal(ind);
          });

          $("#nro_med_solictados_anestesia"+ind).keypress(function(e){
              if(e.which == 13) {
                  <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                      AddProductoAnestesia();
                  <?php //else:?>
                      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
                  <?php //endif;?>
              }
          });

          $("#add_producto_anestesia"+ind).click(function(e){
              AddProductoAnestesia();
              validproductoseleccionado({{Auth::user()->id_farmacia}},this);
              btnAgregarProducto = $(this);
              $(btnAgregarProducto).attr('disabled', 'disabled');
          });

          function validproductoseleccionado(id_farmacia, btn) {//Jose
              var dni_beneficiario = $('#dni_beneficiario').val();
              var nro_dias = 30;
              var objTr = $(btn).closest("tr");
              var id_producto = objTr.find(".cmbMedicamentoAnestesia").val();
              $.ajax({
                  url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                  success: function (result) {
                  var msg   = "";
                  var n     = 0;
                  var flag  = true;
                      for(var key in result) {
                          if(n == 0){
                              msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
                              msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                                          msg += "<thead>";
                                              msg += "<tr>";
                              msg += "<th>Farmacia</th>";
                              msg += "<th>Nro. Receta</th>";
                              msg += "<th>Fecha Expedicion</th>";
                              msg += "<th>Producto</th>";
                              msg += "<th>Tiempo(Dias)</th>";
                              msg += "<th>Dias Transcurridos</th>";
                              msg += "<th>Dias Pendientes</th>";
                              msg += "<th>Cantidad Prescrita</th>";
                                              msg += "</tr>";
                                          msg += "</thead>";
                              msg += "<tbody>";
                          }

                          msg += "<tr><td>"+(result[key].farmacia)+"</td>";
                          msg += "<td>"+(result[key].nro_receta)+"</td>";
                          msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
                          msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
                          msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
                          msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
                          msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
                          msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                          n++;
                      }
                      if(msg != "") {
                          bootbox.confirm({
                              title: "Alerta",
                              message: msg,
                              buttons: {
                                  cancel: {
                                      label: '<i class="fa fa-times"></i> Cancelar'
                                  },
                                  confirm: {
                                      label: '<i class="fa fa-check"></i> Continuar'
                                  }
                              },
                              callback: function (result) {
                                  if(result==true){
                                  verlotes(id_farmacia, btn);
                                  }
                              }
                          });
                      }
                  }
              });
          }


          $("#cmbMedicamentoAnestesia"+ind).on("change", function (e) {
              // var flagx = 0;
              // cmb = $(this);
              // id_producto = $("#cmbMedicamentoAnestesia"+ind).val();
              // //id_user={{Auth::user()->id}};
              // var listaprueba = [];
              // $('.cmbMedicamentoAnestesia').each(function(){
              //     var ind_tmp = $(this).val();
              //     listaprueba.push(ind_tmp)
              //     if($(this).val() == id_producto)flagx++;
              // });
              // console.log(`El array de productos : ${listaprueba}`);
              var cmb = $(this);
              var id_producto = $("#cmbMedicamentoAnestesia"+ind).val();
              const selectedValue = $(this).val();
              let duplicados = 0;
              $('.cmbMedicamentoAnestesia').each(function () {
                if ($(this).val() === selectedValue) duplicados++;
              });
              if (duplicados > 1 && selectedValue!='') {
                  bootbox.alert({
                    message: "<h4 class='text-center'>El producto farmacéutico ya ha sido ingresado</h4>",
                    closeButton: true,
                    callback: function() {

                        $('.modal.in, .modal.show').modal('show');
                        $('body').addClass('modal-open').css('overflow','');
                    }
                  }).on('shown.bs.modal', function () {

                      $('body').addClass('modal-open').css('overflow','');
                  }).on('hidden.bs.modal', function () {

                    if ($('.modal.in, .modal.show').length > 0) {
                        $('body').addClass('modal-open').css('overflow','');
                    }
                });
                const currentTS = tomSelectInstancesProductoAnestesia["cmbMedicamentoAnestesia" + ind];
                if (currentTS) {
                  currentTS.clear();
                  currentTS.setTextboxValue('');
                  currentTS.blur();
                }
              }


              // if(flagx > 1){
              //     // Guardar referencia al modal principal
              //     let modal = $(this).closest('.modal');

              //     // Bloquear el scroll del body al abrir el bootbox
              //     $('body').addClass('modal-open');

              //     bootbox.alert({
              //         message: "El producto farmaceutico ya ha sido ingresado",
              //         callback: function() {
              //             // Restaurar el estado del modal principal al cerrar el alert
              //             modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
              //             $('body').addClass('modal-open');  // Restaurar el scroll del modal
              //         }
              //     }).on('hidden.bs.modal', function () {
              //         // Restaurar el scroll al cerrar el alert de bootbox
              //         $('body').addClass('modal-open');
              //     });

              //     $("#cmbMedicamentoAnestesia"+ind).val("").trigger("change");
              //                     return false;
              // }
              var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
              option = {
                  url: '../cargarstock_porfarmacia/' + id_producto+ '/' + id_farmacia,
                  type: 'GET',
                  dataType: 'json',
                  data: {}
              };
              $.ajax(option).done(function (data) {

                  var cantidad = data.cantidad;
                  var cantidadEstablecimiento = data.cantidadEstablecimiento;
                  var cantidadAlmacen = data.cantidadAlmacen;
                  $(cmb).closest("tr").find(".limpia_text").val("");
                  //$(cmb).closest("tr").find("#nro_stocks").val(data);
                  $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
                  $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
                  $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
                  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
                  $(cmb).closest("tr").find(".tiempo_periodo").focus();
                  <?php //endif;?>
                  $(cmb).closest("tr").find("#nro_med_solictados_anestesia").val("");
                  $(cmb).closest("tr").find("#nro_med_entregados").val("");
                  $(cmb).closest("tr").find("#lotes_lote").val("");
                  $(cmb).closest("tr").find("#lotes_cantidad").val("");
                  $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
                  $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
              });

          });
          filasmedicamentosAnestesia++;
      }

      $('#tblAnestesia tbody').on('click', 'button.deleteFila', function () {
        var obj = $(this);
        // Selecciona la fila completa
        var fila = obj.closest('tr');

        // Obtiene la posición de la fila
        var filaPosition = fila.index();

        // Imprime la posición del elemento 'input' en la consola
        console.log("La posición del input es: " + filaPosition);
        eliminarDiagnosticoXProductoAnestesia(filaPosition);
        // Elimina la fila
        obj.parent().parent().remove();
        filasmedicamentosAnestesia--;
      });


      function eliminarDiagnosticoXProductoAnestesia(posicion) {
          // Se elimina el elemento en la posición dada
          procedimientosSeleccionadosAnestesia.splice(posicion, 1);

          // Se actualiza la posición de los elementos restantes
          procedimientosSeleccionadosAnestesia.forEach((item, index) => {
              item.posicion = index;
          });
          console.log(procedimientosSeleccionadosAnestesia);
      }

      // Abrir el segundo modal
      function crearAnestesia() {

          $('#modalNuevoAnestesia').modal('show');
      }
      // Abrir el segundo modal
      function crearBalanceHidrico() {

          $('#modalNuevoBalanceHidrico').modal('show');
      }

      $('#addConfirmaIntervencion').on('click', function (e) {
          const addConfirmaIntervencion = $('#addConfirmaIntervencion').attr("aria-pressed");
          if ( addConfirmaIntervencion == "false" ) {
              $('#tiene_intervencion').val(1);
              $('.addConfirmaIntervencion').show();
              if (e.originalEvent) {
                let fechaAtencion = $('#fecha_hora_atencion').val();
                if (fechaAtencion) {
                  $('#fecha_intervencion').val(fechaAtencion.split('T')[0]);
                }
              }
          }else{
             $('#tiene_intervencion').val(0);
             $('.addConfirmaIntervencion').hide();
          }
      });

      $('#fecha_hora_atencion').on('change', function () {
        let fechaAtencion = $(this).val();
        if (fechaAtencion) {
          $('#fecha_intervencion').val(fechaAtencion.split('T')[0]);
        }
      });

      $('#fecha_intervencion').on('change', function () {
        let fechaIntervencion = $(this).val();
        if (fechaIntervencion) {
          let horaActual = ($('#fecha_hora_atencion').val() || '').split('T')[1] || '00:00';
          $('#fecha_hora_atencion').val(fechaIntervencion + 'T' + horaActual);
        }
      });

      $('#addObservacionIntervencionQuirurgica').on('click', function () {
          const addObservacionIntervencionQuirurgica = $('#addObservacionIntervencionQuirurgica').attr("aria-pressed");
          $('.addObservacionIntervencionQuirurgica').hide();
          if ( addObservacionIntervencionQuirurgica == "false" ) {
              $('#tiene_observacion').val(1);
              $('.addObservacionIntervencionQuirurgica').show();
          }else{
            $('#tiene_observacion').val(0);
          }
      });

    $('#tblDiagnosticoAnestesia tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var inputVal = obj.parent().siblings().find('input').val();
      let posicion =inputVal.indexOf("-");
      let codigo = inputVal.substring(0,posicion);
      eliminarDiagnosticosarrAnestesia(codigo);
      console.log("el codigo es" + codigo);
      obj.parent().parent().remove();
    });

    function eliminarDiagnosticosarrAnestesia(codigon){
      let codigo2 =codigon.trim()

      let listIdDiagnosticoObjetos2 =[];
        for (let index = 0; index < listIdDiagnosticoObjetosAnestesia.length; index++) {
          const element = listIdDiagnosticoObjetosAnestesia[index];
          if(element.codigo !== codigo2){
            listIdDiagnosticoObjetos2.push(element)
          }

        }
        listIdDiagnosticoObjetosAnestesia=[];
        listIdDiagnosticoObjetosAnestesia =listIdDiagnosticoObjetosAnestesia.concat(listIdDiagnosticoObjetos2);



        procedimientosSeleccionadosAnestesia= procedimientosSeleccionadosAnestesia.map((item) => {
          if (item.codigo === codigo2) {
            return { ...item, id: "" };
          } else {
            return item;
          }
        });

        console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionadosAnestesia))



      console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetosAnestesia));
      updateSelectElements();

    }

    $('#addDiagnosticoAnestesia').on('click',function(){
      AddDiagnosticoAnestesia();
    });

    function AddDiagnosticoAnestesia(){
    var newRow = "";
    var ind = $('#tblDiagnosticoAnestesia tbody tr').length;

    if(ind > 0){
      $('.id_diagnostico_anestesia').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });

      fila_medicamentos=$('#tblDiagnosticoAnestesia tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];

    }

    var item_tipo_diagnostico = '';
    item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
    item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
    newRow += '<tr>';
    newRow +='<td data-label="Descripción"><div style="position: relative; width: 95%; margin: 0 10px;"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_anestesia[]" tabindex="8" required="" id="nombre_diagnostico_anestesia' +ind + '" class="input-sm" style="margin:0 10px; width:95%; text-align:left" />';
    newRow += '<span id="spinner1' + ind + '" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); display: none;">'+
            '<i class="glyphicon glyphicon-refresh spinning"></i></span></div>';
    newRow +='<input type="hidden" class="form-control id_diagnostico_anestesia typeahead tt-query" id="id_diagnostico_anestesia' + ind +'" name="id_diagnostico_anestesia[]" value="" data-id='+ind+'></td>';
    newRow += '<td data-label="Tipo"><select class="form-control id_tipo_diagnostico_anestesia" id="cmbTipoDiagnostico' + ind +'" name="id_tipo_diagnostico[]" style="margin:0 10px; width:95%">' + item_tipo_diagnostico + '</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin:0 10px;"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblDiagnosticoAnestesia tbody').append(newRow);
    var sexo	= $('#frmCqAnestesia #nomsexo').val();
    if(sexo=='M'){
      genero=1;
    }
    else{
      if(sexo=='F'){
        genero=2;
      }
    }

    $("#nombre_diagnostico_anestesia"+ind).autocomplete({
      minLength: 1,
      delay : 400,
      source: function(request, response) {
        var $spinner = $("#spinner1" + ind);
        $spinner.show();
        $.ajax({
          //url: '../obtener_diagnostico/' + request.term,
          url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
          dataType: "json",
          success: function(data)  {
            let listaDiagnosticoSeleccionado = $(".id_diagnostico_anestesia").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          },
          complete: function() {
            $spinner.hide();
          }
        })

      },select:  function(e, ui) {
        var id_diagnostico_anestesia = ui.item.id;
        $("#id_diagnostico_anestesia"+ind).val(id_diagnostico_anestesia);
        $("#nombre_diagnostico_anestesia"+ind).prop("readonly",true);
      }
    }).autocomplete("widget").css("z-index", 1051);

    $("#nombre_diagnostico_anestesia"+ind).on('blur', function() {
      var id_diagnostico_anestesia = $("#id_diagnostico_anestesia"+ind).val();
      if(id_diagnostico_anestesia == ""){
        // Guardar referencia al modal principal
        let modal = $(this).closest('.modal');

        // Bloquear el scroll del body al abrir el bootbox
        $('body').addClass('modal-open');

        bootbox.alert({
            message: "Falta ingresar el diagnostico en esta fila",
            callback: function() {
                // Restaurar el estado del modal principal al cerrar el alert
                modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
                $('body').addClass('modal-open');  // Restaurar el scroll del modal
            }
        }).on('hidden.bs.modal', function () {
            // Restaurar el scroll al cerrar el alert de bootbox
            $('body').addClass('modal-open');
        });
        $("#nombre_diagnostico_anestesia"+ind).val("");
        return false;
      }else{
        //addNewArray(id_diagnostico_anestesia);
        addNewArrayAdicionalReceta(id_diagnostico_anestesia);
      }
    });

    }


    function addNewArray(id_diagnostico) {

      console.log(id_diagnostico);

      if(  listIdDiagnosticoObjetosAnestesia.findIndex( e  => e.id == id_diagnostico) ==-1 ){
        listIdDiagnosticoAnestesia.push(id_diagnostico);// solo es para validar si existen datos

        if (listIdDiagnosticoAnestesia.length > 0) {
          let codigo = JSON.stringify(id_diagnostico);
          console.log(codigo);

          // Realizar la petición AJAX usando el código id_diagnostico
          $.ajax({
              "dataType": 'json',
              "type": "POST",
              "url": "/obtenerdatosDiagnosticos",
              "data":{
                codigo : codigo,
                _token: "{{ csrf_token() }}"
                    },
              "success": function (result) {
                  console.log(result);

                  if (result) {
                    listIdDiagnosticoObjetosAnestesia.push(result.objeto);
                    console.log(JSON.stringify(listIdDiagnosticoObjetosAnestesia));
                    updateSelectElements();
                  } else {
                    console.log(`Retorna vacio ...!!`);
                  }
              },
              "error": function (msg, textStatus, errorThrown) {
                  //location.href="login";
                  console.log(msg, textStatus, errorThrown);

              }
          });
        }
      }
    }

    function updateSelectElements() {
      // Obtener todos los elementos <select> con la clase "select-diagnostico"
      let selectElements = document.querySelectorAll(".select-diagnostico-anestesia");
      console.log(selectElements);

      selectElements.forEach((selectElement) => {
        // Almacenar el valor actualmente seleccionado
        let currentValue = selectElement.value;
        console.log(currentValue);

        // Eliminar las opciones actuales
        selectElement.innerHTML = "";


        // Agregar las opciones basadas en listIdDiagnosticoObjetos
        listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
          let option = document.createElement("option");
          option.value = item.id;
          option.textContent = item.nombre;
          selectElement.appendChild(option);
        });

        // Verificar si el valor seleccionado aún está disponible en las opciones
        let optionExists = Array.from(selectElement.options).some(
          (option) => option.value === currentValue
        );

        // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
        if (!optionExists) {
          selectElement.value = "";
        } else {
          // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
          selectElement.value = currentValue;
          console.log()
        }
      });
    }

    $('#tblProcedimientoAnestesia tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#addProcedimientoAnestesia').on('click',function(){
      AddProcedimientoAnestesia();
    });

    function AddProcedimientoAnestesia(){
      var newRow = "";
      var ind = $('#tblProcedimientoAnestesia tbody tr').length;

      if(ind > 0){
        $('.id_procedimiento_anestesia').each(function(){
          var ind_tmp = $(this).data("id");
          if(ind_tmp => ind){
            ind=Number(ind_tmp)+1;
          }
        });

        fila_medicamentos=$('#tblProcedimientoAnestesia tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];

      }

      newRow += '<tr>';
      newRow +=
        '<td data-label="Procedimiento"><div style="position: relative; width: 95%; margin: 0 10px;"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento_anestesia[]" tabindex="8" required="" id="nombre_procedimiento_anestesia' +
        ind + '" class="input-sm" style="margin:0 10px; width:95%; text-align:left;" />';
      newRow += '<span id="spinner2' + ind + '" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); display: none;">'+
            '<i class="glyphicon glyphicon-refresh spinning"></i></span></div>';

      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento_anestesia" id="id_procedimiento_anestesia' + ind +
        '" name="id_procedimiento_anestesia[]" value="" data-id='+ind+'></td>';
      newRow +=
        '<td data-label="Cantidad"><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad_anestesia typeahead tt-query" style="margin:0 10px; width:95%;" id="procedimiento_cantidad_anestesia' +
        ind + '" name="procedimiento_cantidad_anestesia[]" onkeypress="return soloNumerosMenosCero(event)"  value="1"></td>';
      newRow += '<td data-label="Diagnósticos"><select class="form-control select-diagnostico-anestesia" style="margin:0 10px; width:95%;" id="miSelectAnestesia' + ind + '" name="miSelectAnestesia[]"></select></td>';
      newRow +=
        '<td data-label="Eliminar"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin:0 10px;"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimientoAnestesia tbody').append(newRow);
      const selectElement = document.getElementById('miSelectAnestesia' + ind);
      let defaultOption = document.createElement('option');
      //si entra la primera vez
      var i = 0;
      listIdDiagnosticoObjetosAnestesia.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        if (i==0){
          option.setAttribute("selected", "selected");
          i++;
        }
        selectElement.appendChild(option);
      });
      var nivel     = $('#frmCqAnestesia #id_nivel').val();
      var sexo	= $('#frmCqAnestesia #nomsexo').val();
      if(sexo=='M')
        genero=1;
      else
        genero=2;

      //tipo = 1

      $("#nombre_procedimiento_anestesia"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          var $spinner = $("#spinner2" + ind);
          $spinner.show();
          $.ajax({
      //  	url: '../obtener_procedimiento/' + request.term,
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data)  {
              let listaProcedimientoSelecionado = $(".id_procedimiento_anestesia").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
            },
            complete: function() {
              $spinner.hide();
            }
          })

        },select:  function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento_anestesia"+ind).val(id_procedimiento);
          $("#nombre_procedimiento_anestesia"+ind).prop("readonly",true);
        }
      }).autocomplete("widget").css("z-index", 1051);

      $("#nombre_procedimiento_anestesia"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_anestesia"+ind).val();
        if(id_procedimiento == ""){
          // Guardar referencia al modal principal
          let modal = $(this).closest('.modal');

          // Bloquear el scroll del body al abrir el bootbox
          $('body').addClass('modal-open');

          bootbox.alert({
              message: "Falta ingresar el procedimiento en esta fila",
              callback: function() {
                  // Restaurar el estado del modal principal al cerrar el alert
                  modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
                  $('body').addClass('modal-open');  // Restaurar el scroll del modal
              }
          }).on('hidden.bs.modal', function () {
              // Restaurar el scroll al cerrar el alert de bootbox
              $('body').addClass('modal-open');
          });
          $("#nombre_procedimiento_anestesia"+ind).val("");
          return false;
        }
      });
      $("#procedimiento_cantidad_anestesia"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_anestesia"+ind).val();
        if(id_procedimiento == ""){
          bootbox.alert("Falta ingresar la cantidad en este procedimiento");
          $("#procedimiento_cantidad_anestesia"+ind).val("");
          return false;
        }
      });
    }

    $('#tblAnestesiologoAnestesia tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });

    $('#addAnestesiologoAnestesia').on('click',function(){
      AddAnestesiologoAnestesia();
    });

    function AddAnestesiologoAnestesia() {
      var newRow = "";
      var ind = $('#tblAnestesiologoAnestesia tbody tr').length;
      console.log(`total de filas de anestesiologos : ${ind}`);

      if (ind > 0) {
        $('.id_anestesiologo_anestesia').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });

        fila_registros = $('#tblAnestesiologoAnestesia tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow +='<td style="width:60%;">'+
                '<input type="hidden" name="flag_personales_anestesiologo_anestesia[]" value="1" />'+
                '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_anestesiologo_anestesia[]" '+
                'id="nombre_anestesiologo_anestesia' +ind + '"  class="form-control" style="height: 28px !important;font-weight: 800;" required/>'+
                '<input type="hidden" class="form-control typeahead tt-query id_anestesiologo_anestesia" id="id_anestesiologo_anestesia' + ind +'" name="id_anestesiologo_anestesia[]"  data-id='+ind+'></td>';
      newRow +='<td style="width:20%;"><select id="tipo_profesional_anestesiologo_anestesia' + ind +'" name="tipo_profesional_anestesiologo_anestesia[]" class="form-control" style="height: 28px !important;font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
      newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblAnestesiologoAnestesia tbody').append(newRow);

      var tipo_profesional_interviene = <?php echo json_encode($tipo_profesional_interviene_anestesiologo); ?>;
      var $select = $('#tipo_profesional_anestesiologo_anestesia' + ind +'');
      $.each(tipo_profesional_interviene, function(index, value) {
        $select.append($('<option></option>').val(value.id).text(value.descripcion));
      });

      $("#nombre_anestesiologo_anestesia" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          url_consulta= '{{url('profesional_que_interviene/')}}/'+request.term;
          $.ajax({
            url: url_consulta,
            dataType: "json",
            success: function(data) {
              let listaSirujanosSeleccionado = $(".id_cirujano").map(function(){
                  return parseInt($(this).val());
              }).get();

              let listaAnestesiologosSelecionado = $(".id_anestesiologo_anestesia").map(function(){
                  return parseInt($(this).val());
              }).get();

              // Filtro que excluye los elementos que estén en ambas listas
              let filtro = data.filter((element) => {
                  return !listaSirujanosSeleccionado.includes(element.id) &&
                        !listaAnestesiologosSelecionado.includes(element.id);
              });

              // Aplicar el límite de los primeros 10 elementos
              let limitedData = filtro.slice(0, 10);
              response(limitedData);
            }
          });
        },
        select: function(e, ui) {
          var id_anestesiologo_anestesia = ui.item.id;
          $("#id_anestesiologo_anestesia" + ind).val(id_anestesiologo_anestesia);
          $("#nombre_anestesiologo_anestesia" + ind).prop("readonly", true);
        }
      });

    }

    let anestesiologosSeleccionados = [];

    // Función para actualizar los inputs hidden
    function actualizarInputsHidden() {
        // Primero eliminamos cualquier input anterior para evitar duplicados
        $('#hiddenInputsContainer').empty();

        // Creamos un input hidden por cada ID en el array
        anestesiologosSeleccionados.forEach(function(id) {
            $('#hiddenInputsContainer').append(
                $('<input>', {
                    type: 'hidden',
                    name: 'anestesiologosEliminados[]',
                    value: id
                })
            );
        });
    }

    $('.AnularFilaAnestesiologos').on('click', function() {
      let id_anestesiologo_registro = $(this).data('id_anestesiologo_registro')

      if (!anestesiologosSeleccionados.includes(id_anestesiologo_registro)) {
          anestesiologosSeleccionados.push(id_anestesiologo_registro);
          $(this).closest('tr').css('background-color', '#f8d7da');
      }

      actualizarInputsHidden();

      console.log(anestesiologosSeleccionados);

    });

    $('#restaurarColores').on('click', function() {
        // Restaurar el color de fondo a su estado original (normalmente blanco)
        anestesiologosSeleccionados = [];
        $('#tblAnestesiologoAnestesia tr').css('background-color', '');
        $('#hiddenInputsContainer').empty();
        let row = $(this).closest("tr");
        row.append(`<input type="hidden" name="cantidad_prescrita_editar[]" value="">`);
        row.append(`<input type="hidden" name="posologia_editar[]" value="">`);
        row.append(`<input type="hidden" name="id_via_administracion[]" value="">`);
        row.append(`<input type="hidden" name="tiempo_periodo_editar[]" value="">`);
        });

    $('.AnularFilaProductosAnestesia').on('click', function() {
      let row = $(this).closest("tr");
      row.find("input:not([type='hidden']), select").prop("disabled", true);
      row.find("input[name='accionRowProductoAnestesia[]']").val("delete");
      $(this).closest('tr').css('background-color', '#f8d7da !important');

    });

    $(document).on('click', '.restaurarColoresMedicamentos', function () {
        const row = $(this).closest('tr');
        row.find("input[type='text']").prop("disabled", true);
        row.find("select").prop("disabled", true);
        row.css('background-color', '');
        row.find("input[name='accionRowProductoAnestesia[]']").val("");

        const tdHidden = row.find('td.campos_hidden_productos');

        if (tdHidden.length) {
            if (tdHidden.find("input[name='accionRowProductoAnestesia[]']").length === 0) {
                tdHidden.append(`<input type="hidden" name="accionRowProductoAnestesia[]" value="">`);
            }
            if (tdHidden.find("input[name='cantidad_prescrita_editar[]']").length === 0) {
                tdHidden.append(`<input type="hidden" name="cantidad_prescrita_editar[]" value="">`);
            }
            if (tdHidden.find("input[name='posologia_editar[]']").length === 0) {
                tdHidden.append(`<input type="hidden" name="posologia_editar[]" value="">`);
            }
            if (tdHidden.find("input[name='id_via_administracion[]']").length === 0) {
                tdHidden.append(`<input type="hidden" name="id_via_administracion[]" value="">`);
            }
            if (tdHidden.find("input[name='tiempo_periodo_editar[]']").length === 0) {
                tdHidden.append(`<input type="hidden" name="tiempo_periodo_editar[]" value="">`);
            }
        }
    });

    $('#tblMedicamentosAdicionalAnestesia tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });

    let filasmedicamentosAdicionalAnestesia = 0;

    // $('#addProductoAdicionalAnestesia').on('click', function () {
    //   //getProductoAdicionalAnestesia();
    //   AddProductoAdicionalAnestesia();
    // });
    let tomSelectInstancesProductoExistenteAnestesio = {};
    function agregarProductoRecetaExistenteAnestesio(id_receta){
        console.log('Entro en función AddProductoAdicionalAnestesia() ... ');
        
        var tipo_receta = 1;
        var id_consultorio = 72;
        if(tipo_receta == ""){
            bootbox.alert("Seleccione el tipo atención");
            return false;
        }
        if(id_consultorio == ""){
            bootbox.alert("Seleccione un servicio");
            return false;
        }
        var newRow = "";
        var ind = $('#tblMedicamentosAnestesia_'+id_receta+' tbody tr').length;
        console.log('Antes de agregar fila, número de filas:', $('#tblMedicamentosAnestesia_'+id_receta+' tbody tr').length);
        var ind = filasmedicamentosAdicionalAnestesia;
        var tabindex = 71;
        var nuevalperiodo = "";
        if(ind > 0){
            $('.cmbMedicamentoAdicionalAnestesia'+id_receta).each(function(){
                var ind_tmp = $(this).attr("ind");
                if(ind_tmp >= ind){
                  ind=Number(ind_tmp)+1;
                  tabindex+=3;
                }
            });

            fila_medicamentos=$('#tblMedicamentosAnestesia_'+id_receta+' tbody').children('tr');
            rowIndex=$('#hidden_adicional_rowIndex').val();
            var idval = ind - 1;
            <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
                fila_medicamento=fila_medicamentos[idval];
                var valante = $(fila_medicamento).find('#nro_med_adicional_entregados').val();
                if(valante == ""){
                    bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                    return false;
                }
            <?php //endif;?>

            //nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
        }

        var item_producto = "";
        $('#cmbMedicamentoTempAnestesia option').each(function() {
            item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
            "</option>"
        });
        console.log(`El  item de medicamentoTemp : ${item_producto}`);

        // var item_diagnostico_option = "";
        // $('#diagnosticosAdicionalTempAnestesia{{$id_intervencion}} option').each(function() {
        //     item_diagnostico_option += "<option value=" + $(this).val() + ">" + $(this).html() +
        //     "</option>"
        // });
        var item_via_administracion = '';
        item_via_administracion+='<option value="0">--Seleccionar--</option>';
        item_via_administracion+='<option value="1">Oral</option>';
        item_via_administracion+='<option value="2">Sublingual</option>';
        item_via_administracion+='<option value="3">Rectal</option>';
        item_via_administracion+='<option value="4">Intravenosa</option>';
        item_via_administracion+='<option value="5">Intramuscular</option>';
        item_via_administracion+='<option value="6">Subcutánea</option>';
        item_via_administracion+='<option value="7">Dérmica</option>';
        item_via_administracion+='<option value="8">Nasal</option>';
        item_via_administracion+='<option value="9">Oftalmológica</option>';
        item_via_administracion+='<option value="10">Inhalatoria</option>';
        item_via_administracion+='<option value="11">Epidural</option>';
        item_via_administracion+='<option value="12">Intratecal</option>';
        item_via_administracion+='<option value="13">Vaginal</option>';
        item_via_administracion+='<option value="14">Intraarticular</option>';
        item_via_administracion+='<option value="15">No Aplica</option>';
        item_via_administracion+='<option value="16">Otros</option>';

        newRow +='<tr class="nuevos_productos_existentes_anestesio">';
        newRow +='<td data-label="Producto farmacéutico o dispositivo médico"><select class="select-tom  cmbMedicamentoAdicionalAnestesia'+id_receta+' productos-responsive" id="cmbMedicamentoAdicionalAnestesia' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto_adicional['+id_receta+']['+ind+']" style="width: 100%">'+item_producto+'</select></td>';
        //newRow +='<td data-label="Producto farmacéutico o dispositivo médico"></td>';

        //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
        //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
        newRow +='<input type="hidden" name="nro_stocks_almacen_adicional['+id_receta+']['+ind+']" id="nro_stocks_almacen_adicional" value=""/>';
        newRow +='<input type="hidden" name="nro_stocks_establecimiento_adicional['+id_receta+']['+ind+']" id="nro_stocks_establecimiento_adicional" value=""/>';
        newRow +='<td data-label="Stock Farmacia"><input type="text" name="nro_stocks_adicional['+id_receta+']['+ind+']" required id="nro_stocks_adicional" class="limpia_text nro_solicitado input-sm productos-responsive" style="margin-left:1px; width:100px" readonly=""/>';
        <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        newRow +='<td data-label="Tiempo(Dias)"><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo_adicional['+id_receta+']['+ind+']" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo_adicional' + ind +'" class="nro_solicitado tiempo_periodo_adicional input-sm productos-responsive" style="margin-left:1px; width:100px" value="' + nuevalperiodo +'"/></td>';

        newRow += '<td data-label="Posologia"><input type="text" name="posologiaAdicional['+id_receta+']['+ind+']" tabindex="' + (tabindex + 2) + '" required="" id="posologiaAdicional' +
        ind + '" class="nro_solicitado posologiaAdicional input-sm productos-responsive" style="margin-left:1px; width:200px" value=""/>';

        <?php //endif;?>
        newRow +='<input type="hidden" name="lotes_lote_adicional['+id_receta+']['+ind+']" id="lotes_lote_adicional" value=""/>';
        newRow +='<input type="hidden" name="lotes_registro_sanitario_adicional['+id_receta+']['+ind+']" id="lotes_registro_sanitario_adicional" value=""/>';
        newRow +='<input type="hidden" name="lotes_fecha_vencimiento_adicional['+id_receta+']['+ind+']" id="lotes_fecha_vencimiento_adicional" value=""/>';
        newRow +='<input type="hidden" name="lotes_precio_unitario_adicional['+id_receta+']['+ind+']" id="lotes_precio_unitario_adicional" value=""/>';
        newRow +='<input type="hidden" name="lotes_cantidad_adicional['+id_receta+']['+ind+']" id="lotes_cantidad_adicional" value=""/></td>';

        newRow +='<td data-label="Via Adm."><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_anestesia_adicional productos-responsive" id="cmbTipoViaAdministracionAdicional'+ind+'" name="id_via_administracion_anestesia_adicional['+id_receta+']['+ind+']" style="margin-left:1px;width:150px">'+item_via_administracion+'</select></td>';

        newRow +='<td data-label="Cant. Pres."><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_anestesia_adicional['+id_receta+']['+ind+']" required="" id="nro_med_solictados_anestesia_adicional' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_anestesia_adicional form-control productos-responsive" style="height: 28px !important;font-weight: 800;" /></td>';

        <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
        //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
        <?php //endif;?>

        newRow += '<td data-label="DiagnosticoxProducto"><select tabindex="' + (tabindex + 4) + '" class="form-control productos-responsive miSelectProdAdicionalAnestesia"  id="miSelectProdAdicionalAnestesia' + ind + '" name="miSelectProdAdicionalAnestesia['+id_receta+']['+ind+']" style="margin-left:1px;"></select></td>';
        newRow +='<td data-label="Eliminar" colspan="3"><button type="button"  class="btn btn-danger deleteFilaExistenteAnestesio btn-xs" data-id-receta="'+id_receta+'" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';

        newRow +='</tr>';
        $('#tblMedicamentosAnestesia_'+id_receta+' tbody').append(newRow);
        // $("#cmbMedicamentoAdicionalAnestesia" + ind).select2({max_selected_options: 4});
        const select = new TomSelect("#cmbMedicamentoAdicionalAnestesia" + ind, {
          maxItems: 1,
          plugins: ['clear_button']
        });
        tomSelectInstancesProductoExistenteAnestesio["cmbMedicamentoAdicionalAnestesia" + ind] = select;
        select.focus();

        const selectElementProd = document.getElementById('miSelectProdAdicionalAnestesia' + ind);

        listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
            let option = document.createElement('option');
            option.value = item.id;
            option.textContent =  item.codigo+"-"+item.nombre;
            selectElementProd.appendChild(option);
        });
        $("#abrir-modal-" + ind).click(function(e) {
            e.stopPropagation();

            abrirModal(ind);
        });

        $("#nro_med_solictados_anestesia_adicional"+ind).keypress(function(e){
            if(e.which == 13) {
                <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                    AddProductoAdicionalAnestesia();
                <?php //else:?>
                    validproductoAdicionalseleccionado({{Auth::user()->id_farmacia}},this);
                <?php //endif;?>
            }
        });

        $("#add_producto_anestesia"+ind).click(function(e){
            AddProductoAdicionalAnestesia();
            validproductoAdicionalseleccionado({{Auth::user()->id_farmacia}},this);
            btnAgregarProducto = $(this);
            $(btnAgregarProducto).attr('disabled', 'disabled');
        });

        function validproductoAdicionalseleccionado(id_farmacia, btn) {//Jose
          console.log('Entro en función de validproductoAdicionalseleccionado');

            var dni_beneficiario = $('#dni_beneficiario').val();
            var nro_dias = 30;
            var objTr = $(btn).closest("tr");
            var id_producto = objTr.find(".cmbMedicamentoAdicionalAnestesia").val();
            $.ajax({
                url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                success: function (result) {
                var msg   = "";
                var n     = 0;
                var flag  = true;
                    for(var key in result) {
                        if(n == 0){
                            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
                            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                                        msg += "<thead>";
                                            msg += "<tr>";
                            msg += "<th>Farmacia</th>";
                            msg += "<th>Nro. Receta</th>";
                            msg += "<th>Fecha Expedicion</th>";
                            msg += "<th>Producto</th>";
                            msg += "<th>Tiempo(Dias)</th>";
                            msg += "<th>Dias Transcurridos</th>";
                            msg += "<th>Dias Pendientes</th>";
                            msg += "<th>Cantidad Prescrita</th>";
                                            msg += "</tr>";
                                        msg += "</thead>";
                            msg += "<tbody>";
                        }

                        msg += "<tr><td>"+(result[key].farmacia)+"</td>";
                        msg += "<td>"+(result[key].nro_receta)+"</td>";
                        msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
                        msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
                        msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
                        msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
                        msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
                        msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                        n++;
                    }
                    if(msg != "") {
                        bootbox.confirm({
                            title: "Alerta",
                            message: msg,
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancelar'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Continuar'
                                }
                            },
                            callback: function (result) {
                                if(result==true){
                                verlotes(id_farmacia, btn);
                                }
                            }
                        });
                    }
                }
            });
        }


        $("#cmbMedicamentoAdicionalAnestesia"+ind).on("change", function (e) {

            id_producto = $("#cmbMedicamentoAdicionalAnestesia"+ind).val();
            const selectedValue = $(this).val();
            let duplicados = 0;
            $('.cmbMedicamentoAdicionalAnestesia'+id_receta).each(function () {
              if ($(this).val() === selectedValue) duplicados++;
            });
            if (duplicados > 1 && selectedValue!='') {
                bootbox.alert({
                  message: "<h4 class='text-center'>El producto farmacéutico ya ha sido ingresado</h4>",
                  closeButton: true,
                  callback: function() {

                      $('.modal.in, .modal.show').modal('show');
                      $('body').addClass('modal-open').css('overflow','');
                  }
              }).on('shown.bs.modal', function () {

                  $('body').addClass('modal-open').css('overflow','');
              }).on('hidden.bs.modal', function () {

                  if ($('.modal.in, .modal.show').length > 0) {
                      $('body').addClass('modal-open').css('overflow','');
                  }
              });
              const currentTS = tomSelectInstancesProductoExistenteAnestesio["cmbMedicamentoAdicionalAnestesia" + ind];
              if (currentTS) {
                currentTS.clear();
                currentTS.setTextboxValue('');
                currentTS.blur();
              }
            }

            var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
            if(id_producto){
              cmb = $(this);
              option = {
                  url: '../cargarstock_porfarmacia/' + id_producto+ '/' + id_farmacia,
                  type: 'GET',
                  dataType: 'json',
                  data: {}
              };
              $.ajax(option).done(function (data) {

                  var cantidad = data.cantidad;
                  var cantidadEstablecimiento = data.cantidadEstablecimiento;
                  var cantidadAlmacen = data.cantidadAlmacen;
                  $(cmb).closest("tr").find(".limpia_text").val("");
                  //$(cmb).closest("tr").find("#nro_stocks").val(data);
                  $(cmb).closest("tr").find("#nro_stocks_adicional").val(cantidad);

                  $(cmb).closest("tr").find("#nro_stocks_establecimiento_adicional").val(cantidadEstablecimiento);
                  $(cmb).closest("tr").find("#nro_stocks_almacen_adicional").val(cantidadAlmacen);
                  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
                  $(cmb).closest("tr").find(".tiempo_periodo_adicional").focus();
                  <?php //endif;?>
                  $(cmb).closest("tr").find("#nro_med_solictados_anestesia_adicional").val("");
                  $(cmb).closest("tr").find("#nro_med_entregados_adicional").val("");
                  $(cmb).closest("tr").find("#lotes_lote_adicional").val("");
                  $(cmb).closest("tr").find("#lotes_cantidad_adicional").val("");
                  $(cmb).closest("tr").find("#lotes_registro_sanitario_adicional").val("");
                  $(cmb).closest("tr").find("#lotes_fecha_vencimiento_adicional").val("");
              });
            }

        });
        filasmedicamentosAdicionalAnestesia++;
    }

    function getProductoAdicionalAnestesia(){
      $('#tblMedicamentosAdicionalAnestesia tbody').html('');

      if($('#frmCqAnestesia #id_servicio').val()=="") return false;

      var id_ipress 		= $('#frmCqAnestesia #id_establecimiento').val();
      var id_consultorio 	= $('#frmCqAnestesia #id_servicio').val();
      var id_tiporeceta 	= $('#frmCqAnestesia #id_tipo_atencion').val();

      var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
      $('#id_farmacia').val(id_farmacia);
      console.log(id_farmacia);

      var nivel     = $('#id_nivel').val();
      var x  = 0;
      var dni = $('#dni_beneficiario').val();
      var interconsulta = $('#interconsulta').val();
      let puede_atennder_servicios_cq = <?=$puede_atennder_servicios_cq ? 'true' : 'false'; ?>;
      if(puede_atennder_servicios_cq){
         Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los productos medicos de la farmacia</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
            Swal.showLoading();
            }
        });

        $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress + '/' + id_farmacia + '/72',
            success: function(result){
              let sin_paquete = result.productos_sin_paquetizacion;
              let con_paquete = result.productos_con_aquetizacion;

              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(sin_paquete).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                //newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' data-tipo-anestesia='"+oo.id_tipo_anestesia+"' data-stock='"+oo.stock+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              
              //$('#cmbMedicamentoAdicionalTempAnestesia{{$id_intervencion}}').html(newOption);
              $('#cmbMedicamentoTempAnestesia').html(newOption);
              $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);

              var newOptionPaquetizacion = "";
              newOptionPaquetizacion = "<option value=''>--Seleccionar-</option>";
              $(con_paquete).each(function (ii, oo) {
                newOptionPaquetizacion += "<option value='"+oo.id+"' ru='"+oo.ru+"' data-tipo-anestesia='"+oo.id_tipo_anestesia+"' data-stock='"+oo.stock+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTempAnestesiaNuevoTipoAnestesio').html(newOptionPaquetizacion);
              $('#cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia').html(newOptionPaquetizacion);

            },
            complete: function () {
              Swal.close();
            },
        });
      }

      //$('#tblMedicamentosAdicionalAnestesia tbody').html('');

      // if($('#frmCqAnestesia #id_servicio').val()=="") return false;

      // //var id_ipress 		= $('#id_ipress').val();
      // var id_ipress 		= 76//$('#frmCqAnestesia #id_establecimiento').val();
      // var id_consultorio 	= 72//$('#frmCqAnestesia #id_servicio').val();
      // //var tipo_receta		= $('#tipo_receta').val();
      // //var id_tiporeceta 	= $('#id_tiporeceta').val();
      // var id_tiporeceta 	= 3//$('#frmCqAnestesia #id_tipo_atencion').val();

      // var id_farmacia     = 18 //$('#frmCqAnestesia #id_farmacia_receta').val();
      // console.log(id_farmacia);

      // var nivel     = $('#id_nivel').val();
      // var x  = 0;
      // var dni = $('#dni_beneficiario').val();
      // var interconsulta = $('#interconsulta').val();
      // if(id_tiporeceta == "3"){

      //     if(interconsulta=='SI'){
      //         if (id_consultorio==104){
      //             id_consultorio2=104
      //         } else {
      //             id_consultorio2=105
      //         }
      //         $.ajax({
      //         //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
      //         url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
      //         success: function(result){
      //             var newOption = "";
      //             newOption = "<option value=''>--Seleccionar--</option>";
      //             $(result).each(function (ii, oo) {
      //             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //             });
      //             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //         }
      //         });
      //     } else {

      //         if(x==0){ //SIN INTERCONSULTA
      //             if (id_consultorio==104){
      //                 $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
      //                     //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
      //                     success: function(result){
      //                     var newOption = "";
      //                     newOption = "<option value=''>--Seleccionar--</option>";
      //                     $(result).each(function (ii, oo) {
      //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                     });
      //                     $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                     }
      //                 });
      //             } else {
      //                 if (id_consultorio==21){
      //                     $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
      //                     //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
      //                     success: function(result){
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar-</option>";
      //                         $(result).each(function (ii, oo) {
      //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                         });
      //                         $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         //$("#id_medico").select2({max_selected_options: 4});

      //                     }
      //                     });
      //                 } else {
      //                     if (id_consultorio==72){
      //                         console.log('Entro en consultorio de anestesiologia ...');
      //                         $.ajax({
      //                         //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
      //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
      //                         success: function(result){
      //                             console.log(result);
      //                             var newOption = "";
      //                             newOption = "<option value=''>--Seleccionar-</option>";
      //                             $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                             console.log($('#cmbMedicamentoAdicionalTempAnestesia').val());

      //                         }
      //                         });
      //                     } else {
      //                         $.ajax({
      //                         url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
      //                         //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
      //                         success: function(result){
      //                             var newOption = "";
      //                             newOption = "<option value=''>--Seleccionar-</option>";
      //                             $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                         });
      //                     }

      //                 }
      //             }
      //         } else {
      //             if (id_consultorio==104){
      //                 $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
      //                     success: function(result){
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar--</option>";
      //                         $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                         });
      //                         $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                     }
      //                 });
      //             } else {
      //                 if (id_consultorio==21){
      //                     $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
      //                     success: function(result){
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar-</option>";
      //                         $(result).each(function (ii, oo) {
      //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                         });
      //                         $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                     }
      //                     });
      //                 } else {
      //                     if (id_consultorio==72){
      //                         $.ajax({
      //                         //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
      //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
      //                         success: function(result){
      //                             var newOption = "";
      //                             newOption = "<option value=''>--Seleccionar-</option>";
      //                             $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                         });
      //                     } else {
      //                         $.ajax({
      //                         url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
      //                         success: function(result){
      //                             var newOption = "";
      //                             newOption = "<option value=''>--Seleccionar-</option>";
      //                             $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                         });
      //                     }
      //                 }
      //             }
      //         }
      //     }
      // }else{
      //     console.log('entro en condición tipo de atención 1 ...');

      //     if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
      //         console.log('entro en condición tipo de atención 1 ...');
      //         if(id_tiporeceta==2){
      //             id_consultorio=76;
      //         }
      //         $.ajax({
      //             url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
      //             success: function(result){
      //                 var newOption = "";
      //                 newOption = "<option value=''>--Seleccionar-</option>";
      //                 $(result).each(function (ii, oo) {
      //                     newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                 });
      //                 $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                 //$("#id_medico").select2({max_selected_options: 4});

      //             }
      //         });

      //     }
      //     if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
      //         if(id_tiporeceta==5 || id_tiporeceta == 1){
      //             id_consultorio = 110;
      //         } else {
      //             id_consultorio = 111;
      //         }

      //         $.ajax({
      //             url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
      //             success: function(result){
      //                 var newOption = "";
      //                 newOption = "<option value=''>--Seleccionar-</option>";
      //                 $(result).each(function (ii, oo) {
      //                     newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                 });
      //                 $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                 //$("#id_medico").select2({max_selected_options: 4});

      //             }
      //         });
      //     }
      //     if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
      //         if(id_tiporeceta == 1){
      //             if(id_farmacia == 25){
      //                 $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
      //                     success: function(result){
      //                     var newOption = "";
      //                     newOption = "<option value=''>--Seleccionar-</option>";
      //                     $(result).each(function (ii, oo) {
      //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                     });
      //                     $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                     }
      //                 });
      //             } else {
      //                 if(id_consultorio == 72){
      //                     $.ajax({
      //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
      //                         '/' + 72,
      //                         success: function(result) {
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar-</option>";
      //                         $(result).each(function(ii, oo) {
      //                             newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
      //                             .nombre + ", " + oo.abreviatura + "</option>";
      //                         });
      //                         $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                     });
      //                 } else {
      //                     $.ajax({
      //                     url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
      //                     success: function(result){
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar-</option>";
      //                         $(result).each(function (ii, oo) {
      //                             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                     });
      //                 }
      //             }
      //             //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
      //         } else {
      //             if(id_tiporeceta == 4){
      //                 if(id_consultorio == 72){
      //                     $.ajax({
      //                         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
      //                         '/' + 72,
      //                         success: function(result) {
      //                             var newOption = "";
      //                             newOption = "<option value=''>--Seleccionar-</option>";
      //                             $(result).each(function(ii, oo) {
      //                                 newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
      //                                 .nombre + ", " + oo.abreviatura + "</option>";
      //                             });
      //                             $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         }
      //                     });
      //                 } else {
      //                     $.ajax({
      //                     url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
      //                     success: function(result){
      //                         var newOption = "";
      //                         newOption = "<option value=''>--Seleccionar-</option>";
      //                         $(result).each(function (ii, oo) {
      //                         newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
      //                         });
      //                         $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
      //                         //$("#id_medico").select2({max_selected_options: 4});

      //                     }
      //                     });
      //                 }
      //             }
      //         }
      //     }
      // }
    }
    function getProductosRecetasExistentes(){
      $('#tblMedicamentosAdicionalAnestesia tbody').html('');

      if($('#frmCqAnestesia #id_servicio').val()=="") return false;

      var id_ipress 		= $('#frmCqAnestesia #id_establecimiento').val();
      var id_consultorio 	= $('#frmCqAnestesia #id_servicio').val();
      var id_tiporeceta 	= $('#frmCqAnestesia #id_tipo_atencion').val();

      var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
      $('#id_farmacia').val(id_farmacia);
      console.log(id_farmacia);

      var nivel     = $('#id_nivel').val();
      var x  = 0;
      var dni = $('#dni_beneficiario').val();
      var interconsulta = $('#interconsulta').val();
      let puede_atennder_servicios_cq = <?=$puede_atennder_servicios_cq ? 'true' : 'false'; ?>;
      if(puede_atennder_servicios_cq){
        $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress + '/' + id_farmacia + '/72',
            success: function(result){
              let sin_paquete = result.productos_sin_paquetizacion;
              let con_paquete = result.productos_con_aquetizacion;

              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(sin_paquete).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              
              $('#cmbMedicamentoTempAnestesia').html(newOption);
              $('#cmbMedicamentoTempAnestesiaNuevo').html(newOption);

              var newOptionPaquetizacion = "";
              newOptionPaquetizacion = "<option value=''>--Seleccionar-</option>";
              $(con_paquete).each(function (ii, oo) {
                newOptionPaquetizacion += "<option value='"+oo.id+"' ru='"+oo.ru+"' data-tipo-anestesia='"+oo.id_tipo_anestesia+"' data-stock='"+oo.stock+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTempAnestesiaNuevoTipoAnestesio').html(newOptionPaquetizacion);
              $('#cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia').html(newOptionPaquetizacion);

            }
        });
      }
    }

    $('#btnPrestacionCqAnestesia').on('click', function(e) { 
      validarAsegurado().then(function(resultado){
      if(!resultado) return;
      e.preventDefault();

      if (!validarDiagnosticosAdicionales()) return false;

      if ($("#addConfirmaIntervencion").attr("aria-pressed") === "true") {
        if ($('#tblProcedimientoAdicionalAnestesia').length) {
          if (!validarProcedimientosAdicionales()) return false;
        } else if (!validarProcedimientoAnestesia()) {
          return false;
        }
      }


      let flag_anestesia_quirurgica = $('#flag_anestesia_quirurgica').val(); //Si es 1 tiene una atención de anestesia
      // Habilitar inputs y selectores temporalmente
      $(this).find('input[disabled], select[disabled]').prop('disabled', false);
      let msg = "";

      //==================== VALIDACIÓN DIAGNOSTICOS ===================
      if (flag_anestesia_quirurgica !== "1") {
        var ind_diag = $('#tblDiagnosticoAnestesia tbody tr').length;
        if(ind_diag > 0){
          fila_dignosticos=$('#tblDiagnosticoAnestesia tbody').children('tr');
          for (i = 0; i < ind_diag; i++) {
            fila_dignostico=fila_dignosticos[i];
            var id_diagnostico = $(fila_dignostico).find('.id_diagnostico_anestesia').val();
            var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico_anestesia').val();
            if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
            if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
          }
        }else{
          msg+="Debe ingresar minimo un diagnostico<br>";
          msg+="Debe ingresar el tipo de diagnostico<br>";
        }

        //==================== VALIDACIÓN RECETA VALE ===================
        var addRecetaValeAnestesia = $('#addRecetaValeAnestesia').attr("aria-pressed");
        if(addRecetaValeAnestesia == "true"){
          var ind = $('#tblAnestesia tbody tr').length;
          if(ind > 0){
            var nind = ind-1;
            var cmbMedicamento = $("#cmbMedicamentoAnestesia"+nind).val();
            if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
              $('#tblAnestesia .deleteFila').eq(ind - 1).parent().parent().remove();
              ind = ind-1;
            }
              fila_productosmedicos=$('#tblAnestesia tbody').children('tr');
            for (i = 0; i < ind; i++) {
                fila_medicamento=fila_productosmedicos[i];
                var cmbMedicamento = $(fila_medicamento).find('.cmbMedicamentoAnestesia').val();
                var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo').val();
                var posologia = $(fila_medicamento).find('.posologia').val();
                var valmedentre = $(fila_medicamento).find('.nro_med_solictados_anestesia').val();
                var via_administracion =$(fila_medicamento).find('.id_via_administracion_anestesia').val();
                if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == "undefined")msg+="Falta seleccionar un producto (dispositivo médico)<br>";
                if(tiempo_periodo == "" || tiempo_periodo ==0)msg+="Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>";
                if(via_administracion == 0 || via_administracion == "undefined" )msg+="Uno de los productos no cuenta con la via de administración<br>";
                if(posologia == "")msg+="Uno de los productos no cuenta con la posologia<br>";
                if(valmedentre == "" || valmedentre==0 )msg+="Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0<br>";
              }
          }else{
            msg+="Debe ingresar minimo un producto medico a la Receta Vale<br>";
          }
        }else{
          var selectElement  = document.querySelector('select[name="id_producto[]"]');
          if(selectElement ){
            selectElement.selectedIndex = -1;
          }
        }
      }

      $('#addConfirmaIntervencion').on('click', function () {
          const addConfirmaIntervencion = $('#addConfirmaIntervencion').attr("aria-pressed");
          if ( addConfirmaIntervencion == "false" ) {
              let id_tipo_anestesia = $('.js-example-basic-multiple').val();
              console.log(id_tipo_anestesia);
              if (id_tipo_anestesia == null) {
              console.log('Esta vacio el tipo de anestesia');
              msg+="Debe seleccionar al menos un tipo de anestesia<br>";
              }
          }
      });



      $('#tblMedicamentosAdicionalAnestesia tbody tr').each(function(index) {
            // Validar "Tiempo (Días)"
            let tiempoInput = $(this).find('input[name="tiempo_periodo_adicional[]"]');
            let tiempoValor = tiempoInput.val();

            if (!/^\d+$/.test(tiempoValor) || parseInt(tiempoValor, 10) <= 0) {
                isValid = false;
                msg += `El campo "Tiempo (Días)" en la fila ${index + 1} debe ser un número mayor a 0.<br>`;
            }

            // Validar "Cantidad"
            let cantidadInput = $(this).find('input[name="nro_med_solictados_anestesia_adicional[]"]');
            let cantidadValor = cantidadInput.val();

            if (!/^\d+$/.test(cantidadValor) || parseInt(cantidadValor, 10) <= 0) {
                isValid = false;
                msg += `El campo "Cantidad" en la fila ${index + 1} debe ser un número mayor a 0.<br>`;
            }

            // Validar otros campos requeridos excepto "nro_stocks_adicional[]"
            $(this).find('input:not([name="nro_stocks_adicional[]"]), select').each(function() {
                const value = $(this).val();
                const label = $(this).closest('td').attr('data-label') || "Campo"; // Obtener el texto del label o usar "Campo"

                if ($(this).attr("required") && (value === null || value.trim() === "")) {
                    isValid = false;
                    msg += `El campo "${label}" en la fila ${index + 1} es obligatorio.<br>`;
                }
            });
        });


      if ($("#addConfirmaIntervencion").attr("aria-pressed") === "true" &&  moment($("#fecha_hora_atencion").val()).format("YYYY-MM-DD") !== moment($("#fecha_intervencion").val()).format("YYYY-MM-DD")) {
        msg = "La fecha de atención y la fecha de intervención deben ser la misma. <br>Corrija la fecha para continuar";
      }
      //============================== ALERTA DE VALIDACIÓN =============================
      if(msg!=""){
        // Guardar referencia al modal principal
        let modal = $(this).closest('.modal');
        Swal.fire({
          title: "Panel de Validacion",
          html: '<div style="font-weight: bold;font-size: 16px;"> '+msg + "</div>",
          icon: "warning",
          showCancelButton: false,
          confirmButtonText: "De Acuerdo", 
          reverseButtons: true,
          allowOutsideClick: false
        })  
        return false;
      }

      // this.submit();
      e.preventDefault();
      Swal.fire({
          title: '<div class="titulo-padding"><p>Procesando el envío...</p></div>',
          allowOutsideClick: false,
          showConfirmButton: false,
          backdrop: true,
          customClass: {
              header: 'swal2-header-generar'
          },
          didOpen: () => {
              Swal.showLoading();
              $('#frmCqAnestesia').submit(); // Envía el formulario
          }
      });
      });
    });
    // Manejo del envío del formulario
    $('#frmCqAnestesia').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Los datos se han guardado correctamente, ahora veremos el detalle.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                      let id_prestacion = response.id_prestacion;
                      let id_intervencion = <?php echo $id_intervencion; ?>;
                      recargarModalAnestesia(id_prestacion, id_intervencion);
                      //window.location.href = '/cq_lista_pendiente';
                    });
                } else {
                  console.log(response);
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de validación',
                      text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                  });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
    });

    $(document).on('click', '#btnfirmarAnestesia', function(e) {
      e.preventDefault();
      firmarAnestesia();
    })

    function firmarAnestesia() {
      Swal.fire({
        title: "¿Está seguro de firmar la atención de anestesiologia?",
        html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se firmara la <strong>LA ATENCIÓN DE ANESTESIOLOGÍA</strong></span></div>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, firmar",
        cancelButtonText: "No, cancelar",
        reverseButtons: true,
        allowOutsideClick: false
      }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la firma</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              $.ajax({
              url: "<?php echo url()?>/genera_csv_anestesiologia_cqx",
              type: "post",
              //data: p,
              data: $("#frmCqAnestesia").serialize(),
              //timeout: 30000,
              dataType: 'json'
            })
            .done(function(resultado) {
              console.log(resultado);
              if (resultado.sw) {
                var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
                $("#doc_firmar_anestesia").val(`${resultado.csv}.7z`);
                //$("#opt_firma").val(`${resultado.opt}`);
                $("#flagAnestesiaFirma").val("1");
                initInvoker('W');
              }
            })
            .fail(function(jqXHR, textStatus) {
              console.log("Request failed: " + textStatus)
            })
            .always(function() {
            });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
              Swal.fire({
                  icon: 'info',
                  title: 'Firma cancelada',
                  text: 'No se firmó la atención.',
                  timer: 2000,
                  showConfirmButton: false
              });
          }
      });
    }

    window.addEventListener('getArguments', async function(e) {
      await ObtieneArgumentosAnestesiaParaFirmaDesdeLaWeb();
    });

    async function getArguments() {
      arg = document.getElementById("argumentos_anestesia").value;
      await dispatchEventClient('sendArguments', arg);
    }

    window.addEventListener('invokerOk', function(e) {
      MiFuncionAnestesiaOkWeb();
    });

    window.addEventListener('invokerCancel', function(e) {
      MiFuncionAnestesiaCancel();
    });

    async function ObtieneArgumentosAnestesiaParaFirmaDesdeLaWeb() {
      var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
      let flagAnestesiaFirma = $("#flagAnestesiaFirma").val();
      if (flagAnestesiaFirma == "1") {
        $.post("{{route('postArguments')}}", {
          _token: CSRF_TOKEN,
          type: "W",
          documentName: $('#doc_firmar_anestesia').val()/*,
          opt_firma: $('#opt_firma').val(),
          entidad: <?=Auth::user()->dni?>*/
        }, function(data, status) {
          document.getElementById("argumentos_anestesia").value = data;
          getArguments();
        });
      }

      if (flagAnestesiaFirma == "2") {
        //================Firma para informe de anestesiologia
        // $.post("{{route('postArgumentsLaboratorio')}}", {
        // _token: CSRF_TOKEN,
        // type: "W",
        // documentName: $('#doc_firmar_laboratorio').val(),
        // entidad: $('#user_firma').val()
        // }, function(data, status) {
        //   document.getElementById("argumentos").value = data;
        //   getArguments();
        // });
      }

    }

    async function MiFuncionAnestesiaOkWeb() {
      var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
      let flagAnestesiaFirma = $("#flagAnestesiaFirma").val();
      var id_resol = $('#id_prestacion_anestesia').val();
      if (flagAnestesiaFirma == "1") {
        $.post("{{route('actualiza_prestacion')}}", {
          _token: CSRF_TOKEN,
          id_tipo: "hospitalizacion",
          id_resol: id_resol,
        }, function(data, status) {
          $.post("{{route('actualiza_prestacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "receta",
            id_resol: id_resol,
          }, function(data, status) {
              $.post("{{route('actualiza_prestacion')}}", {
              _token: CSRF_TOKEN,
              id_tipo: "interconsulta",
              id_resol: id_resol,
            }, function(data, status) {
                $.post("{{route('actualiza_prestacion')}}", {
                _token: CSRF_TOKEN,
                id_tipo: "referencia",
                id_resol: id_resol,
              }, function(data, status) {
                //location.reload();
              location.href = '/cq_lista_pendiente';
              });
            });
          });
        });
      }

      if (flagServicioFirma == "2") {
        //================Firma para informe de anestesiologia
        // $.post("{{route('actualiza_prestacion')}}", {
        //   _token: CSRF_TOKEN,
        //   id_tipo: "laboratorio",
        //   id_resol: id_resol,
        // }, function(data, status) {
        //   $("#flagServicioFirma").val("");
        //   location.reload();
        // });
      }

    }

    function MiFuncionAnestesiaCancel() {
      alert("El proceso de firma digital fue cancelado.");
    }

    function firmarsintokensAnestesia() {
      var batchSignatureProcessPoller = setInterval(function() {
        $.ajax({
          url: "<?php echo url()?>/sinTokens",
          type: "post",
          cache: false,
          dataType: 'json',
          data: $("#frmCqAnestesia").serialize(),
          success: function(completed) {
            if (completed) {
              location.href = '/cq_lista_pendiente';
              clearInterval(batchSignatureProcessPoller);
            }
          }
        });
      }, 3000);
    }

    function initModalAnestesiaEvents() {
        $('#btnGuardarAnestesia').off('click').on('click', function(e) {
            e.preventDefault();
            guardarAnestesia();
        });
    }
    function guardarAnestesia() {
        var formData = $('#frmCqAnestesia').serialize();

        $.ajax({
            url: $('#frmCqAnestesia').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                if (!response.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Por favor, ingrese los datos correctos.'
                    });
                    return;
                }

                let id_prestacion = response.id_prestacion;
                let id_intervencion = <?php echo $id_intervencion; ?>;

                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Los datos se han guardado correctamente',
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    recargarModalAnestesia(id_prestacion, id_intervencion);
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud.'
                });
            }
        });
    }
    function recargarModalAnestesia(id_prestacion, id_intervencion) {
        Swal.fire({
            title: 'Cargando información actualizada...',
            text: 'Por favor espere un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            type: "GET",
            url: `{{ url('cqanestesia') }}/${id_prestacion}/${id_intervencion}`,
            success: function(responseHtml) {
                Swal.close();

                if (!responseHtml) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo obtener la información.'
                    });
                    return;
                }

                // 1️⃣ Reemplaza solo el cuerpo del modal
                $('#modalAnestesiaBody').html(responseHtml);

                // 2️⃣ Reaplica los eventos dentro del modal
                initModalAnestesiaEvents();

                // 3️⃣ Asegura que el modal principal se muestre correctamente
                $('#modalAnestesia')
                    .appendTo('body')
                    .modal('show');

                // 4️⃣ Mantener scroll
                $('body').addClass('modal-open');
            },
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al obtener los datos.'
                });
            }
        });
    }

    $(document).off('click', '#btnAskAction').on('click', '#btnAskAction', function(e) {
        e.preventDefault();
        let modal = $(this).closest('.modal');
        $('body').addClass('modal-open');

      validarAsegurado().then(function(resultado){
        if(!resultado) return;

        if ($("#addConfirmaIntervencion").attr("aria-pressed") === "true"
          && moment($("#fecha_hora_atencion").val()).format("YYYY-MM-DD") !== moment($("#fecha_intervencion").val()).format("YYYY-MM-DD")
        ) {
          Swal.fire({
            title: "Panel de Validacion",
            html: '<div style="font-weight: bold;font-size: 16px;">La fecha de atención y la fecha de intervención deben ser la misma. <br>Corrija la fecha para continuar</div>',
            icon: "warning",
            showCancelButton: false,
            confirmButtonText: "De Acuerdo",
            reverseButtons: true,
            allowOutsideClick: false
          });
          return;
        }

        function cerrarConfirmModal(modalDialog) {
          modalDialog.remove();
          var backdrops = $('.modal-backdrop');
          if (backdrops.length > 1) {
            backdrops.last().remove();
          }
          if ($('.modal.in, .modal.show').length > 0) {
            $('body').addClass('modal-open');
          }
        }

        let confirmModal = bootbox.dialog({
            size: "small",
            backdrop: 'static',
            keyboard: false,
            title: '¿Desea finalizar la atención?',
            message: '<p></p>',
            buttons: {
                cancel: {
                    label: 'Guardar Cambios',
                    className: 'btn-danger',
                    callback: function() {
                      if ($("#addConfirmaIntervencion").attr("aria-pressed") === "true") {
                        if ($('#tblProcedimientoAdicionalAnestesia').length) {
                          if (!validarProcedimientosAdicionales()) {
                            cerrarConfirmModal(confirmModal);
                                return false;
                            }
                        } else if (!validarProcedimientoAnestesia()) {
                          cerrarConfirmModal(confirmModal);
                            return false;
                        }
                      }

                        // Mostrar modal principal
                        $('#divFirmarAnestesio').hide();
                        $('#divGuardar').show();
                        $('#divAskAction').hide();
                        modal.modal('show');
                        $('body').addClass('modal-open');

                        Swal.fire({
                            title: 'Guardando datos...',
                            html: 'Espere un momento por favor ...',
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading()
                        });
                        guardarAnestesia();
                    }
                },
                confirm: {
                    label: 'Finalizar Atención',
                    className: 'btn-success',
                    callback: function() {
                      if (!validarConfirmaIntervencion()) {
                        cerrarConfirmModal(confirmModal);
                        return false;
                      }
                      if (!validarDiagnosticosAdicionales()) {
                        cerrarConfirmModal(confirmModal);
                        return false;
                      }
                      if ($("#addConfirmaIntervencion").attr("aria-pressed") === "true" && !validarProcedimientosAdicionales()) {
                        cerrarConfirmModal(confirmModal);
                        return false;
                      }
                        let todasValidas = true;
                        $("[id^='tblMedicamentosAnestesia_']").each(function() {
                            if (!validarCamposTablaAnestesio($(this).attr('id'))) {
                                todasValidas = false;
                                return false;
                            }
                        });
                        if (!todasValidas) return false;

                        $.ajax({
                          url: $('#frmCqAnestesia').attr('action'),
                          method: 'POST',
                          data: $('#frmCqAnestesia').serialize(),
                          success: function(response) {
                            if (!response.success) {
                              Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                text: 'Por favor, ingrese los datos correctos.'
                              });
                              return;
                            }

                            let id_prestacion = response.id_prestacion;
                            let id_intervencion = <?php echo $id_intervencion; ?>;

                            let estado_token = {{ isset(Auth::user()->status_token) ? Auth::user()->status_token : 0 }};
                            if (estado_token === 1) {
                              firmarsintokensAnestesia();
                            } else {
                              bootbox.confirm({
                                message: '¿Desea continuar con el proceso de firma digital?',
                                buttons: {
                                    confirm: { label: 'Sí, continuar', className: 'btn-primary' },
                                    cancel: { label: 'No, solo guardar', className: 'btn-secondary' }
                                },
                                callback: function(continuarFirma) {
                                  if (continuarFirma) {
                                    firmarAnestesia();
                                  } else {
                                    Swal.fire({
                                      icon: 'success',
                                      title: 'Éxito',
                                      text: 'Los datos se han guardado correctamente',
                                      timer: 2000,
                                      showConfirmButton: false,
                                      allowOutsideClick: false
                                    }).then(() => {
                                      recargarModalAnestesia(id_prestacion, id_intervencion);
                                    });
                                  }
                                }
                              });
                            }
                          },
                          error: function() {
                            Swal.fire({
                              icon: 'error',
                              title: 'Error',
                              text: 'Ocurrió un error al guardar los datos.'
                            });
                          }
                      });
                    }
                }
            }
        });

        confirmModal.on('hidden.bs.modal', function() {
            // Mantener scroll y modal abierto
            $('body').addClass('modal-open');
        });
      });
    });

    $(document).ready(function() {
        initModalAnestesiaEvents();
    });


    $('#addDiagnosticoAdicionalAnestesia').on('click', function() {
      AddDiagnosticoAdicionalAnestesia();
    });
    function AddDiagnosticoAdicionalAnestesia() {
      var newRow = "";
      var ind = $('#tblDiagnosticoAdicionalAnestesia tbody tr').length;
      if (ind > 0) {
        $('.id_diagnostico_adicional_anestesia').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
        fila_medicamentos = $('#tblDiagnosticoAdicionalAnestesia tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];
      }
      var item_tipo_diagnostico = '';
      item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
      item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
      item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
      item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
      newRow += '<tr>';
      newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_adicional_anestesia[]" tabindex="8" required="" id="nombre_diagnostico_adicional_anestesia' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_diagnostico_adicional_anestesia" id="id_diagnostico_adicional_anestesia' + ind + '" name="id_diagnostico_adicional_anestesia[]" value="" data-id='+ind+'></td>';
      newRow += '<td><select class="form-control id_tipo_diagnostico_adicional_anestesia" id="cmbTipoDiagnosticoAdicionalAnestesia' + ind +'" name="id_tipo_diagnostico_adicional_anestesia[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
      newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblDiagnosticoAdicionalAnestesia tbody').append(newRow);
      var sexo	= $('#frmCqAnestesia #nomsexo').val();
      if(sexo=='M'){
        genero=1;
      }
      else{
        if(sexo=='F'){
          genero=2;
        }
      }
      $("#nombre_diagnostico_adicional_anestesia" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          $.ajax({
            url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
            dataType: "json",
            success: function(data) {
              let listaDiagnosticoSeleccionado = $(".id_diagnostico_adicional_anestesia").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
              response(filtro);
              // let listaDiagnosticoSeleccionado = $(".id_diagnostico_adicional_anestesia").map(function(){return parseInt($(this).val());}).get();
              // listaDiagnosticoSeleccionado = listaDiagnosticoSeleccionado.concat(idsAdicionalesDiagnostico);
              // let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
              // response(filtro);
            }
          })
        },
        select: function(e, ui) {
          var id_diagnostico = ui.item.id;
          $("#id_diagnostico_adicional_anestesia" + ind).val(id_diagnostico);
          $("#nombre_diagnostico_adicional_anestesia" + ind).prop("readonly", true);
        }
      });
      $("#nombre_diagnostico_adicional_anestesia" + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico_adicional_anestesia" + ind).val();
        if (id_diagnostico == "") {
          // Guardar referencia al modal principal
          let modal = $(this).closest('.modal');
          // Bloquear el scroll del body al abrir el bootbox
          $('body').addClass('modal-open');
          bootbox.alert({
              message: "Falta ingresar el diagnostico en esta fila",
              callback: function() {
                  // Restaurar el estado del modal principal al cerrar el alert
                  modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
                  $('body').addClass('modal-open');  // Restaurar el scroll del modal
              }
          }).on('hidden.bs.modal', function () {
              // Restaurar el scroll al cerrar el alert de bootbox
              $('body').addClass('modal-open');
          });
          $("#nombre_diagnostico_adicional_anestesia" + ind).val("");
          return false;
        } else {
          addNewArrayAdicionalReceta(id_diagnostico)
        }
      });
    }
    $('#tblDiagnosticoAdicionalAnestesia tbody').on('input', 'input[name^="nombre_diagnostico_adicional_anestesia"]', function () {
      $(this).removeClass('campo-invalido');
      if ($(this).closest('tr').find('.campo-invalido').length === 0) {
        $(this).closest('tr').removeClass('error-fila');
      }
    });
    $('#tblDiagnosticoAdicionalAnestesia tbody').on('change', '.id_tipo_diagnostico_adicional_anestesia', function () {
      $(this).removeClass('campo-invalido');
      if ($(this).closest('tr').find('.campo-invalido').length === 0) {
        $(this).closest('tr').removeClass('error-fila');
      }
    });
    $('#tblDiagnosticoAdicionalAnestesia tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var inputVal = obj.parent().siblings().find('input').val();
      let posicion =inputVal.indexOf("-");
      let codigo = inputVal.substring(0,posicion);
      eliminarDiagnosticosarrAdicionalAnestesia(codigo);
      console.log("el codigo es" + codigo);
      obj.parent().parent().remove();
    });
    function eliminarDiagnosticosarrAdicionalAnestesia(codigon){
      let codigo2 =codigon.trim()
  
      let listIdDiagnosticoObjetos2 =[];
        for (let index = 0; index < listIdDiagnosticoObjetosAnestesiaAdicionalReceta.length; index++) {
          const element = listIdDiagnosticoObjetosAnestesiaAdicionalReceta[index];
          if(element.codigo !== codigo2){
            listIdDiagnosticoObjetos2.push(element)
          }
          
        }
        listIdDiagnosticoObjetosAnestesiaAdicionalReceta=[];
        listIdDiagnosticoObjetosAnestesiaAdicionalReceta =listIdDiagnosticoObjetosAnestesiaAdicionalReceta.concat(listIdDiagnosticoObjetos2)
        procedimientosSeleccionadosAnestesia= procedimientosSeleccionadosAnestesia.map((item) => {
          if (item.codigo === codigo2) {
            return { ...item, id: "" };
          } else {
            return item;
          }
        });
  
        console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionadosAnestesia))
      console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetosAnestesiaAdicionalReceta));
      console.table(listIdDiagnosticoObjetosAnestesiaAdicionalReceta);
      updateSelectElements();
      updateSelectElementsAdicionalReceta();
    }
    $('#addProcedimientoAdicionalAnestesia').on('click',function(){
      AddProcedimientoAdicionalAnestesia();
    });
  
    function AddProcedimientoAdicionalAnestesia(){
      var newRow = "";
      var ind = $('#tblProcedimientoAdicionalAnestesia tbody tr').length;

      if(ind > 0){
        $('.id_procedimiento_adicional_anestesia').each(function(){
          var ind_tmp = $(this).data("id");
          if(ind_tmp => ind){
            ind=Number(ind_tmp)+1;
          }
        });

        fila_medicamentos=$('#tblProcedimientoAdicionalAnestesia tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];

      }
      // var item_diagnostico_option = "";
      // $('#diagnosticosAdicionalTempAnestesia{{$id_intervencion}}  option').each(function() {
      //     item_diagnostico_option += "<option value=" + $(this).val() + ">" + $(this).html() +
      //     "</option>"
      // });

      newRow += '<tr>';
      newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento_adicional_anestesia[]" tabindex="8" required="" id="nombre_procedimiento_adicional_anestesia' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento_adicional_anestesia" id="id_procedimiento_adicional_anestesia' + ind +
        '" name="id_procedimiento_adicional_anestesia[]" value="" data-id='+ind+'></td>';
      newRow +=
        '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad_adicional_anestesia typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad_adicional_anestesia' +
        ind + '" name="procedimiento_cantidad_adicional_anestesia[]" value="1"></td>';
      newRow += '<td><select class="form-control select-diagnostico-anestesia"  id="miSelectAnestesia' + ind + '" name="miSelectProdAdicionalAnestesia[]"></select></td>';
      newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimientoAdicionalAnestesia tbody').append(newRow);
      const selectElement = document.getElementById('miSelectAnestesia' + ind);
      let defaultOption = document.createElement('option');
      //si entra la primera vez
      var i = 0;
      listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.codigo+'-'+item.nombre;
        if (i==0){
          option.setAttribute("selected", "selected");
          i++;
        }
        selectElement.appendChild(option);
      });
      var nivel     = $('#frmCqAnestesia #id_nivel').val();
      var sexo	= $('#frmCqAnestesia #nomsexo').val();
      if(sexo=='M')
        genero=1;
      else
        genero=2;

      //tipo = 1

      $("#nombre_procedimiento_adicional_anestesia"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          $.ajax({
      //  	url: '../obtener_procedimiento/' + request.term,
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data)  {
              let listaProcedimientoSelecionado = $(".id_procedimiento_adicional_anestesia").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
              // let listaProcedimientoSelecionado = $(".id_procedimiento_adicional_anestesia").map(function(){return parseInt($(this).val());}).get();
              // listaProcedimientoSelecionado = listaProcedimientoSelecionado.concat(idsAdicionalesProcedimiento);
              // let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              // response(filtro);
            }
          })

        },select:  function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento_adicional_anestesia"+ind).val(id_procedimiento);
          $("#nombre_procedimiento_adicional_anestesia"+ind).prop("readonly",true);
        }
      }).autocomplete("widget").css("z-index", 1051);

      $("#nombre_procedimiento_adicional_anestesia"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_adicional_anestesia"+ind).val();
        if(id_procedimiento == ""){
          // Guardar referencia al modal principal
          let modal = $(this).closest('.modal');
          // Bloquear el scroll del body al abrir el bootbox
          $('body').addClass('modal-open');
          bootbox.alert({
              message: "Falta ingresar el procedimiento en esta fila",
              callback: function() {
                  // Restaurar el estado del modal principal al cerrar el alert
                  modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
                  $('body').addClass('modal-open');  // Restaurar el scroll del modal
              }
          }).on('hidden.bs.modal', function () {
              // Restaurar el scroll al cerrar el alert de bootbox
              $('body').addClass('modal-open');
          });
          $("#nombre_procedimiento_adicional_anestesia"+ind).val("");
          return false;
        }
      });
      $("#procedimiento_cantidad_adicional_anestesia"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_adicional_anestesia"+ind).val();
        if(id_procedimiento == ""){
          bootbox.alert("Falta ingresar la cantidad en este procedimiento");
          $("#procedimiento_cantidad_adicional_anestesia"+ind).val("");
          return false;
        }
      });
    }

    $('#tblProcedimientoAdicionalAnestesia tbody').on('input', 'input[name^="nombre_procedimiento_adicional_anestesia"]', function () {
      $(this).removeClass('campo-invalido');
      if ($(this).closest('tr').find('.campo-invalido').length === 0) {
        $(this).closest('tr').removeClass('error-fila');
      }
    });
    $('#tblProcedimientoAdicionalAnestesia tbody').on('change', '.select-diagnostico-anestesia', function () {
      $(this).removeClass('campo-invalido');
      if ($(this).closest('tr').find('.campo-invalido').length === 0) {
        $(this).closest('tr').removeClass('error-fila');
      }
    });
    $('#tblProcedimientoAdicionalAnestesia tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $(document).ready(function () {
      $('#tabs a').on('shown.bs.tab', function (e) {
        const targetTab = $(e.target).attr("href"); // Obtener el id de la pestaña activa
        if (targetTab === "#tab4default") {
          Swal.fire({
            icon: "warning",
            html: `<div style="padding: 0px 20px 20px 20px">
                    <p style="font-size: 16px"><b>La historia clínica contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos</b></p><br>
                    <span style="font-size: 14px; color: #d9534f"><b>Ley N.° 26659</b></span><br>
                    <span style="font-size: 14px; color: #d9534f"><b>Ley de Protección de la Historia Clínica</b></span>
                  </div>`,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
          });
        }
      });
    });

    function modalHistorial(){
      var dni_beneficiario = $("#dni_beneficiario_h").val();
      $.ajax({
          url: "../modal_historial_atenciones/"+dni_beneficiario,
          type: "GET",
          success: function (result) {
              $("#divHistorial").html(result);
              // $('#tablemodalm').DataTable({
              //   "order": [],
              //   "sPaginationType": "full_numbers",
              //   "language": {"url": "/js/Spanish.json"},
              // });
              // 🔹 Antes de volver a inicializar, destruimos si ya existe
              if ($.fn.DataTable.isDataTable('#tablemodalm')) {
                  $('#tablemodalm').DataTable().destroy();
              }
              $('#tablemodalm').DataTable({
                  order: [],
                  sPaginationType: "full_numbers",
                  language: { "url": "/js/Spanish.json" }
              });
            }
      });
    }

    function modalLaboratorio(){

      var dni_beneficiario = $("#dni_beneficiario_h").val();
      $.ajax({
          url: "../modal_historial_laboratorio/"+dni_beneficiario,
          type: "GET",
          success: function (result) {
            $("#divLaboratorio").html(result);
            $tbl = $("#divLaboratorio").find("#tablemodalm1");
            $tbl.DataTable({
              order: [],
              sPaginationType: "full_numbers",
              language: { url: "/js/Spanish.json" }
            });
          }
      });
    }
    function modalRadiologico(){

      var dni_beneficiario = $("#dni_beneficiario_h").val();
      $.ajax({
          url: "../modal_historial_radiologia/"+dni_beneficiario,
          type: "GET",
          success: function (result) {
              $("#divRadiologico").html(result);
              // $('#tablemodalm2').DataTable({
              //   "sPaginationType": "full_numbers",

              //   "language": {"url": "/js/Spanish.json"},
              // });
              if ($.fn.DataTable.isDataTable('#tablemodalm2')) {
                  $('#tablemodalm2').DataTable().destroy();
              }
              $('#tablemodalm2').DataTable({
                  order: [],
                  sPaginationType: "full_numbers",
                  language: { "url": "/js/Spanish.json" }
              });
          }
      });
    }
    function modalInterconsulta(){

      var dni_beneficiario = $("#dni_beneficiario_h").val();
      $.ajax({
          url: "../modal_historial_interconsulta/"+dni_beneficiario,
          type: "GET",
          success: function (result) {
              $("#divInterconsultaH").html(result);
              // $('#tablemodalm5').DataTable({
              //   "sPaginationType": "full_numbers",

              //   "language": {"url": "/js/Spanish.json"},
              // });
              if ($.fn.DataTable.isDataTable('#tablemodalm5')) {
                  $('#tablemodalm5').DataTable().destroy();
              }
              $('#tablemodalm5').DataTable({
                  order: [],
                  sPaginationType: "full_numbers",
                  language: { "url": "/js/Spanish.json" }
              });
          }
      });
    }

    function agregarRecetaAnestesio() {
      var flagReceta = $('#btnNuevoReceetaAnestesio').attr("flag");
      var recetaId = $('#btnNuevoRecetaAnestesio').attr('data-id-receta'); // ID si existe

      if (flagReceta == 0) {
        $('#btnNuevoReceetaAnestesio').attr("flag", 1).html("Ocultar Receta");
        $('#tipo_anestesia_rv').show();
        //getProductoAnestesia();
        getProductoAdicionalAnestesia();
        $("#fsNuevaRecetaValeAnestesio").show();
        $("#btnFirmarRecetaViewAnestesio").show();
        $('#example tbody').empty();
        // $("#divGuardar").show();
        // $("#divFirmarAnestesio").hide();
      } else {
        $('#btnNuevoReceetaAnestesio').attr("flag", 0).html("Agregar Receta");
        $('#tipo_anestesia_rv').hide();
        $("#fsNuevaRecetaValeAnestesio").hide();
        $("#btnFirmarRecetaViewAnestesio").hide();
        if (flagReceta == 1) {
          // $("#divGuardar").hide();
          // $("#divFirmarAnestesio").show();
        }
      }
    }

    var filasmedicamentos = 0;

    $('#addProductoNuevoAnestesio').on('click', function() {
      AddProductoNuevo();
    });
    let tomSelectInstancesProductoAnestesioNuevo = {};
    function AddProductoNuevo() {
      var tipo_receta = $('#tipo_receta').val();
      var id_consultorio = $('#id_consultorio').val();
      var id_farmacia = $('#id_farmacia_receta').val();
      if (tipo_receta == "") {
        bootbox.alert("Seleccione el tipo atenciÃ³n");
        return false;
      }
      if (id_consultorio == "") {
        bootbox.alert("Seleccione un servicio");
        return false;
      }
      var newRow = "";
      // var ind = $('#example tbody tr').length;
      var ind = filasmedicamentos;
      var tabindex = 11;
      var nuevalperiodo = "1";
      if (ind > 0) {
        // $('.cmbMedicamento').each(function() {
        //   var ind_tmp = $(this).attr("ind");
        //   if (ind_tmp => ind) {
        //     ind = Number(ind_tmp) + 1;
        //     tabindex += 3;
        //   }
        // });
        $('.cmbMedicamento').each(function() {
          var ind_tmp = $(this).attr("ind");
          if (ind_tmp => ind) {
            tabindex += 3;
          }
        });

        fila_medicamentos = $('#example tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
        fila_medicamento = fila_medicamentos[idval];
        var valante = $(fila_medicamento).find('#nro_med_entregados').val();
        if (valante == "") {
          bootbox.alert("Ingrese cantidad entregada en el producto anterior");
          return false;
        }
        <?php //endif;?>

      //nuevalperiodo = document.getElementById('tiempo_periodo' + idval).value;
      }

      var item_producto = "";
        $('#cmbMedicamentoTempAnestesiaNuevo option').each(function() {
            var value = $(this).val() || ""; // Verificar si el valor existe
            var ru = $(this).attr("ru") || ""; // Verificar si el atributo existe
            var text = $(this).html() || ""; // Verificar si hay texto en la opción
            //var tipo_anestesia = $(this).attr("data-tipo-anestesia") || ""; // Verificar si el atributo existe

            // Agregar la opción solo si value o text no están vacíos
            if (value && text) {
                item_producto += "<option value='" + value + "' ru='" + ru + "'>" + text + "</option>";
                // item_producto += "<option value='" + value + "' ru='" + ru + "' data-id-tipo_anestesia='" + tipo_anestesia + "'>" + text + "</option>";
            }
        });
      // var item_diagnostico_option = "";
      // $('#diagnosticosAdicionalTempAnestesia{{$id_intervencion}}  option').each(function() {
      //     item_diagnostico_option += "<option value=" + $(this).val() + ">" + $(this).html() +
      //     "</option>"
      // });

      var item_via_administracion = '';
      item_via_administracion+='<option value="0">--Seleccionar--</option>';
      item_via_administracion+='<option value="1">Oral</option>';
      item_via_administracion+='<option value="2">Sublingual</option>';
      item_via_administracion+='<option value="3">Rectal</option>';
      item_via_administracion+='<option value="4">Intravenosa</option>';
      item_via_administracion+='<option value="5">Intramuscular</option>';
      item_via_administracion+='<option value="6">Subcutánea</option>';
      item_via_administracion+='<option value="7">Dérmica</option>';
      item_via_administracion+='<option value="8">Nasal</option>';
      item_via_administracion+='<option value="9">Oftalmológica</option>';
      item_via_administracion+='<option value="10">Inhalatoria</option>';
      item_via_administracion+='<option value="11">Epidural</option>';
      item_via_administracion+='<option value="12">Intratecal</option>';
      item_via_administracion+='<option value="13">Vaginal</option>';
      item_via_administracion+='<option value="14">Intraarticular</option>';
      item_via_administracion+='<option value="15">No Aplica</option>';
      item_via_administracion+='<option value="16">Otros</option>';

      newRow += '<tr class="producto_adicional">';
      newRow += '<td data-label="Producto farmaceútico o dispositivo médico">';
      newRow += '<select class="select-tom cmbMedicamento productos-responsive" id="cmbMedicamento' + ind + '" ind="' + ind + '" tabindex="' + tabindex + '" name="id_producto_adicional_nuevo[]" style="width: 100%;">'+item_producto+'</select></td>';

      newRow += '<input type="hidden" name="nro_stocks_almacen_adicional_nuevo[]" id="nro_stocks_almacen" value=""/>';
      newRow += '<input type="hidden" name="nro_stocks_establecimiento_adicional_nuevo[]" id="nro_stocks_establecimiento" value=""/>';

      newRow += '<td data-label="Stock Farmacia">';
      newRow += '<input type="text" name="nro_stocks_adicional_nuevo[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm productos-responsive" style="margin-left:1px; width:100%;" readonly=""/></td>';

      newRow += '<td data-label="Tiempo(Dias)">';
      newRow += '<input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo_adicional_nuevo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind + '" class="nro_solicitado tiempo_periodo input-sm productos-responsive" style="margin-left:1px; width:100%;" value="' + nuevalperiodo + '"/></td>';

      newRow += '<td data-label="Posología">';
      newRow += '<input type="text" name="posologiaAdicional_nuevo[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' + ind + '" class="nro_solicitado posologia input-sm productos-responsive" style="margin-left:1px; width:100%;" value="SOP"/></td>';

      newRow += '<td data-label="Via Adm.">';
      newRow += '<select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion productos-responsive" id="cmbTipoViaAdministracion' + ind + '" name="id_via_administracion_anestesia_adicional_nuevo[]" style="margin-left:1px; width:100%;">' + item_via_administracion + '</select></td>';

      newRow += '<td data-label="Cant. Pres.">';
      newRow += '<input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) + '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_anestesia_adicional_nuevo[]" required="" id="nro_med_solictados' + ind + '" class="limpia_text nro_solicitado nro_med_solictados input-sm productos-responsive" style="margin-left:1px; width:100%;" /></td>';

      newRow += '<td data-label="Diagnóstico x Producto">';
      newRow += '<select tabindex="' + (tabindex + 4) + '" class="form-control productos-responsive miSelectProdAdicionalAnestesia" id="miSelectProdAdicionalAnestesia' + ind + '" name="miSelectProdAdicionalAnestesia_nuevo[]"></select></td>';

      newRow += '<td data-label="Eliminar">';
      newRow += '<button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
      newRow += '</tr>';

      $('#example tbody').append(newRow);
      // Inicializar Select2 para el nuevo elemento
      // $('#cmbMedicamento' + ind).select2({
      //     placeholder: "--Seleccionar Producto--",
      //     allowClear: true,
      //     max_selected_options: 4
      // });
      const select = new TomSelect("#cmbMedicamento" + ind, {
        maxItems: 1,
        plugins: ['clear_button']
      });
      tomSelectInstancesProductoAnestesioNuevo["cmbMedicamento" + ind] = select;
      select.focus();

      // const selectElementProd = document.getElementById('miSelectProdAdicionalAnestesia' + ind);
      const selectElementsProdNuevo = document.getElementsByClassName('miSelectProdAdicionalAnestesia');

      // Recorre todos los selects encontrados
      Array.from(selectElementsProdNuevo).forEach((select) => {

        // Limpia opciones anteriores (opcional, por si ya tenía)
        select.innerHTML = '';

        // Recorre la lista de objetos y crea las opciones
        listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
          const option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.codigo + " - " + item.nombre;
          select.appendChild(option);
        });
      });
      $("#abrir-modal-" + ind).click(function(e) {
        e.stopPropagation();
      
        abrirModal(ind);
      });
      
      $("#nro_med_solictados" + ind).keypress(function(e) {
        if (e.which == 13) {
          <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
          AddProducto();
          <?php //else:?>
          validproductoseleccionado({{Auth::user()->id_farmacia}},this);
          <?php //endif;?>
        }
      });

      $("#add_producto"+ind).click(function(e){
          AddProducto();
          validproductoseleccionado({{Auth::user()->id_farmacia}},this);
          btnAgregarProducto = $(this);
          $(btnAgregarProducto).attr('disabled', 'disabled');
      });

      function validproductoseleccionado(id_farmacia, btn) {//Jose
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nro_dias = 30;
        var objTr = $(btn).closest("tr");
        var id_producto = objTr.find(".cmbMedicamento").val();
        $.ajax({
          url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
          success: function (result) {
          var msg   = "";
          var n     = 0;
          var flag  = true;
          for(var key in result) {
            if(n == 0){
              msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
              msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                          msg += "<thead>";
                            msg += "<tr>";
              msg += "<th>Farmacia</th>";
              msg += "<th>Nro. Receta</th>";
              msg += "<th>Fecha Expedicion</th>";
              msg += "<th>Producto</th>";
              msg += "<th>Tiempo(Dias)</th>";
              msg += "<th>Dias Transcurridos</th>";
              msg += "<th>Dias Pendientes</th>";
              msg += "<th>Cantidad Prescrita</th>";
                            msg += "</tr>";
                          msg += "</thead>";
              msg += "<tbody>";
            }

            msg += "<tr><td>"+(result[key].farmacia)+"</td>";
            msg += "<td>"+(result[key].nro_receta)+"</td>";
            msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
            msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
            msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
            msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
            msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
            msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
            n++;
                                                }
            if(msg != "")/*{
                verlotes(id_farmacia, btn);
            }
            else */
            {
              bootbox.confirm({
                  title: "Alerta",
                  message: msg,
                  buttons: {
                      cancel: {
                          label: '<i class="fa fa-times"></i> Cancelar'
                      },
                      confirm: {
                          label: '<i class="fa fa-check"></i> Continuar'
                      }
                  },
                  callback: function (result) {
                    if(result==true){
                      verlotes(id_farmacia, btn);
                    }
                  }
              });
            }
          }
        });
      }


      $("#cmbMedicamento" + ind).on("change", function(e) {
        // var flagx = 0;
        // cmb = $(this);
        // id_producto = $("#cmbMedicamento" + ind).val();
        // id_user = <?php //echo Auth::user()->id ?>;

        // $('.cmbMedicamento').each(function() {
        //   var ind_tmp = $(this).val();
        //   if ($(this).val() == id_producto) flagx++;
        // });

        // if (flagx > 1) {
        //   bootbox.alert({
        //     message: `<div style="max-height: 300px; overflow-y: auto;">El producto farmaceutico ya ha sido ingresado</div>`,
        //     size: 'large',
        //     backdrop: true,
        //     callback: function () {
        //       $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
        //       $('#modalAnestesia').css('overflow-y', 'auto');
        //       $("#cmbMedicamento"+ind).val("").trigger("change");
        //     }
        //   });
        // }
      cmb = $(this);
      id_producto = $("#cmbMedicamento"+ind).val();
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbMedicamento').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });
      if (duplicados > 1 && selectedValue!='') {
         bootbox.alert({
            message: "<h4 class='text-center'>El producto farmacéutico ya ha sido ingresado</h4>",
            closeButton: true,
            callback: function() {

                $('.modal.in, .modal.show').modal('show');
                $('body').addClass('modal-open').css('overflow','');
            }
        }).on('shown.bs.modal', function () {

            $('body').addClass('modal-open').css('overflow','');
        }).on('hidden.bs.modal', function () {

            if ($('.modal.in, .modal.show').length > 0) {
                $('body').addClass('modal-open').css('overflow','');
            }
        });
        const currentTS = tomSelectInstancesProductoAnestesioNuevo["cmbMedicamento" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }
        var id_farmacia = $('#id_farmacia').val();
        if(id_producto){
          option = {
            url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia,
            type: 'GET',
            dataType: 'json',
            data: {}
          };
          $.ajax(option).done(function(data) {

            var cantidad = data.cantidad;
            var cantidadEstablecimiento = data.cantidadEstablecimiento;
            var cantidadAlmacen = data.cantidadAlmacen;
            $(cmb).closest("tr").find(".limpia_text").val("");
            //$(cmb).closest("tr").find("#nro_stocks").val(data);
            $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
            $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
            $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
            <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
            $(cmb).closest("tr").find(".tiempo_periodo").focus();
            <?php //endif;?>
            $(cmb).closest("tr").find("#nro_med_solictados").val("");
            $(cmb).closest("tr").find("#nro_med_entregados").val("");
            $(cmb).closest("tr").find("#lotes_lote").val("");
            $(cmb).closest("tr").find("#lotes_cantidad").val("");
            $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
            $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
          });
        }

      });
      filasmedicamentos++;
    }

    $('#example tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
    function validarCamposTablaAnestesio(tbl) {
        let camposVacios = [];

        $('#' + tbl + ' tbody tr').each(function(index, fila) {
            let filaInvalida = false;

            $(fila).find('input, select, textarea').each(function() {
                const $campo = $(this);

                if ($campo.is(':hidden') || $campo.is(':disabled') || !$campo.attr('name')) {
                    return; // ignorar campos ocultos, deshabilitados o sin name
                }

                let valor;
                if ($campo.hasClass('select-tom')) {
                    valor = $campo[0].tomselect ? $campo[0].tomselect.getValue() : $campo.val();
                } else {
                    valor = $campo.val();
                }

                if (valor == null || valor.trim() === '') {
                    filaInvalida = true;
                }
            });

            if (filaInvalida) {
                camposVacios.push(index + 1);
            }
        });

        if (camposVacios.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Existen campos vacíos en las siguientes filas: ' + camposVacios.join(', ')
            });
            return false;
        }

        return true;
    }



    $(document).on('click', '.deleteFilaExistenteAnestesio', function () {
      const fila = $(this).closest('tr');
      const tabla = fila.closest('table');
      if (tabla.attr('id') && tabla.attr('id').startsWith('tblMedicamentosAnestesia_')) {
          fila.remove();
      } else {
          console.warn('La fila no pertenece a una tabla de anestesia');
      }
      // const idReceta = $(this).data('id-receta');
      // const fila = $(this).closest('tr');
      // const tablaPadre = fila.closest('table');

      // if (tablaPadre.attr('id') === 'tblMedicamentosAnestesia_' + idReceta) {
      //     fila.remove();
      // } else {
      //     console.warn('La fila no pertenece a la tabla esperada');
      // }
    });

  /////////////////FIRMA RECETA EXISTENTE/////////////////
  function btnFirmarRecetaExistente(id_receta,codigo){
    if(validarCamposTablaAnestesio('tblMedicamentosRecetaExistente_'+id_receta)){
      $('#firma_receta_existente_anestesio'+id_receta).val(1);
      $('#flag_firma_receta_anestesio').val(1);
      if(existenFilasNuevasRecetaExistente(id_receta)) {
        var formData = $('#frmCqAnestesia').serialize();
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Antes de firmar se guardara los medicamentos</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: $('#frmCqAnestesia').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if (response.success) {
                  Swal.fire({
                      title: "¿Está seguro de firmar la receta?",
                      html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Antes de firmar se guardarán los medicamentos.</span></div>",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonText: "Sí, firmar",
                      cancelButtonText: "No, cancelar",
                      reverseButtons: true,
                      allowOutsideClick: false
                  }).then((result) => {
                      if (result.isConfirmed) {
                          firmarRecetaAnestesio(response.id_receta);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $('#firma_receta_existente_anestesio'+id_receta).val(0);
                        $('#flag_firma_receta_anestesio').val(0);
                          // Cancelado por el usuario
                          Swal.fire({
                              icon: 'info',
                              title: 'Firma cancelada',
                              text: 'No se firmó la receta.',
                              timer: 2000,
                              showConfirmButton: false
                          });
                      }
                  });
                } else {
                  console.log(response);
                  // Muestra un mensaje de error
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de validación',
                      text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                  });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
      }else{
       Swal.fire({
          title: "¿Está seguro de firmar la receta?",
          html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se firmara la <strong>RECETA VALE N°"+codigo+"</strong></span></div>",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, firmar",
          cancelButtonText: "No, cancelar",
          reverseButtons: true,
          allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la firma</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              firmarRecetaAnestesio(id_receta);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#firma_receta_existente_anestesio'+id_receta).val(0);
              $('#flag_firma_receta_anestesio').val(0);

                // Cancelado por el usuario
                Swal.fire({
                    icon: 'info',
                    title: 'Firma cancelada',
                    text: 'No se firmó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
      }
    }
  }

  function btnFirmarRecetaExistenteSinToken(id_receta,codigo) {
    if(validarCamposTablaAnestesio('tblMedicamentosRecetaExistente_'+id_receta)){
      $('#firma_receta_existente_anestesio'+id_receta).val(1);
      $('#flag_firma_receta_anestesio').val(1);
      if(existenFilasNuevasRecetaExistente(id_receta)) {
        var formData = $('#frmCqAnestesia').serialize();
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Antes de firmar se guardara los medicamentos</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });

        $.ajax({
            url: $('#frmCqAnestesia').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if (response.success) {
                  Swal.fire({
                      title: "¿Está seguro de firmar la receta?",
                      html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Antes de firmar se guardarán los medicamentos.</span></div>",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonText: "Sí, firmar",
                      cancelButtonText: "No, cancelar",
                      reverseButtons: true,
                      allowOutsideClick: false
                  }).then((result) => {
                      if (result.isConfirmed) {
                          firmarRecetaAnestesioSinToken(response.id_receta);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $('#firma_receta_existente_anestesio'+id_receta).val(0);
                        $('#flag_firma_receta_anestesio').val(0);
                          // Cancelado por el usuario
                          Swal.fire({
                              icon: 'info',
                              title: 'Firma cancelada',
                              text: 'No se firmó la receta.',
                              timer: 2000,
                              showConfirmButton: false
                          });
                      }
                  });
                } else {
                  console.log(response);
                  // Muestra un mensaje de error
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de validación',
                      text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                  });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
      }else{
       Swal.fire({
          title: "¿Está seguro de firmar la receta?",
          html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se firmara la <strong>RECETA VALE N°"+codigo+"</strong></span></div>",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, firmar",
          cancelButtonText: "No, cancelar",
          reverseButtons: true,
          allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la firma</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              firmarRecetaAnestesioSinToken(id_receta);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#firma_receta_existente_anestesio'+id_receta).val(0);
              $('#flag_firma_receta_anestesio').val(0);

                // Cancelado por el usuario
                Swal.fire({
                    icon: 'info',
                    title: 'Firma cancelada',
                    text: 'No se firmó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
      }
    }
  }

  function existenFilasNuevasRecetaExistente(id_receta) {
    return $('#tblMedicamentosAnestesia_' + id_receta + ' tr.nuevos_productos_existentes_anestesio').length > 0;
  }
  /////////////////FIN FIRMA RECETA EXISTENTE/////////////////

  /////////////////FIRMA RECETA ADICIONAL/////////////////////
  $('#btnFirmarRecetaAdicionalAnestesio').on('click', function () {
    if(validarCamposTablaAnestesio('example')){
      if(existenFilasRecetaAdicionalAnestesio()){
        $('#firma_receta_adicional_anestesio').val(1);
        $('#flag_firma_receta_anestesio').val(1);
        var formData = $('#frmCqAnestesia').serialize();
        Swal.fire({
            title: "¿Está seguro de firmar la receta?",
            html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Antes de firmar se guardarán los medicamentos.</span></div>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, firmar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Guardando los medicamentos</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              $.ajax({
                url: $('#frmCqAnestesia').attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.close();
                    $('input, select, textarea').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    if (response.success) {
                      firmarRecetaAnestesio(response.id_receta);
                    } else {
                      console.log(response);
                      Swal.fire({
                          icon: 'error',
                          title: 'Error de validación',
                          text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                      });
                    }
                },
                error: function(xhr) {
                    console.log('Ocurrió un error en la solicitud AJAX');
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                    });
                }
              });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#firma_receta_adicional_anestesio').val(0);
              $('#flag_firma_receta_anestesio').val(0);
                // Cancelado por el usuario
                Swal.fire({
                    icon: 'info',
                    title: 'Firma cancelada',
                    text: 'No se firmó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
      }else{
        Swal.fire({
            icon: 'warning',
            title: 'No existe registro',
            text: 'Mínimo debe existir un registro'
        });
      }
    }
  });
  function existenFilasRecetaAdicionalAnestesio() {
    return $('#example tr.producto_adicional').length > 0;
  }
  /////////////////FIN FIRMA RECETA ADICIONAL/////////////////////

  function firmarRecetaAnestesio(id_receta){
    $("#id_resol_receta_anestesio").val(id_receta);
    $.ajax({
        url: `/atencion_anestesio_cqx_receta_firmar/${id_receta}`,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    })
    .done(function(resultado) {
      if(resultado){
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar_receta_anestesio").val(`${resultado.csv}.7z`);
            tipo_envio = "receta_anestesio";
            initInvoker('W');
        }
      }else{
        Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "No se puede procesar la información de la receta",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
        });
      }
    })
    .fail(function(xhr, status, error) {
        console.log('Error:', error);
    })
    .always(function() {
        console.log('Esta función siempre se ejecuta, éxito o error');
    });
  }

  function firmarRecetaAnestesioSinToken(id_receta){

    $("#id_resol_receta_anestesio").val(id_receta);
    $.ajax({
        url: `/atencion_anestesio_cqx_receta_firmar_sin_token/${id_receta}`,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    })
    .done(function(resultado) {
      if(resultado){
        if (resultado.sw) {
          let id_prestacion = resultado.id_prestacion;
          let id_intervencion = <?php echo $id_intervencion; ?>;
          recargarModalAnestesia(id_prestacion, id_intervencion);
        } else {
          Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "Ha ocurrido un error al realizar la firma.",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
          });
        }
      }else{
        Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "No se puede procesar la información de la receta",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
        });
      }
    })
    .fail(function(xhr, status, error) {
        console.log('Error:', error);
    })
    .always(function() {
        console.log('Esta función siempre se ejecuta, éxito o error');
    });
  }

  async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxRecetaAnestesio() {
    const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    const docName = $('#doc_firmar_receta_anestesio').val();

    try {
        const response = await fetch("{{ route('atencion_anestesio_cqx_receta_post_arguments') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                type: "W",
                documentName: docName
            })
        });

        const data = await response.text();
        await dispatchEventClient('sendArguments', data);
        $('#flag_firma_receta_anestesio').val(0);

    } catch (error) {
        console.error("Error al obtener argumentos receta quirúrgica:", error);
        $('#flag_firma_receta_anestesio').val(0);
    }
  }
  async function actualiza_datos_firma_con_token_io_receta_anestesio() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_resol_receta_anestesio').val();
    $.post("{{route('atencion_anestesio_cqx_receta_actualiza_datos_firma_con_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol_receta: id_resol,
    }, function(data, status) {
      if (data && data.status === 'ok') {
        location.reload();
      } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
           text: data?.message || 'No se pudo actualizar la firma de la receta.'
        });
        console.error("Error:", data);
      }
    });
  }

  /*********************************/
  function invalidarReceta(elementId, id_receta, id_farmacia) {
    $('#id_receta_invalidar_anestesio').val(id_receta);
    $('#id_farmacia_invalidar').val(id_farmacia);
    const $btn = $('#' + elementId);  // ← aseguramos jQuery usando el ID
    if ($btn.length === 0) {
      console.warn('Elemento no encontrado:', elementId);
      return;
    }
    const offset = $btn.offset();
    const height = $btn.outerHeight();
    const $modal = $('#squarespaceModalAnestesio');
    // Aseguramos que esté en el body para posicionarlo sin restricciones
    $('body').append($modal);

    $modal.css({
      position: 'absolute',
      top: offset.top + height + 10 + 'px',
      left: (offset.left - 0) + 'px', // ← mueve 50px a la izquierda
      display: 'block',
      zIndex: 1050
    });

    $modal.modal({
      backdrop: false,
      keyboard: true,
      show: true
    });
  }
  function anularRecetaAnestesiologia() {
    $('#squarespaceModalAnestesio').modal('hide');
    $('#squarespaceModalAnestesio').on('hidden.bs.modal', function () {
      if ($('#modalAnestesia').hasClass('in') || $('#modalAnestesia').is(':visible')) {
          $('body').addClass('modal-open');
      }
    });
  }

  $(document).on('click', '#btnInvalidar', function (e) {
    e.preventDefault();
    const motivo = $('#motivo_invalidar_anestesio').val().trim();

    if (motivo === '') {
        $('#motivo_invalidar_anestesio').css('border', '1px solid red');
        Swal.fire({
            icon: 'error',
            title: 'Campo requerido',
            text: 'Debe ingresar un motivo para invalidar la receta.'
        });
        return;
    } else {
        $('#motivo_invalidar_anestesio').css('border', '');
    }

   var formData = $('#form_invalidar_anestesio').serialize();
        Swal.fire({
            title: "¿Está seguro de anular la receta?",
            html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se procedera a anular la receta.</span></div>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, firmar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Anulando la receta</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              $.ajax({
                url: $('#form_invalidar_anestesio').attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.close();
                    $('input, select, textarea').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    if (response.success) {
                      $('#squarespaceModalAnestesio').hide();
                      var id_receta_invalidar_anestesio = $('#id_receta_invalidar_anestesio').val();
                      $('#'+id_receta_invalidar_anestesio).remove();
                      $('#motivo_invalidar_anestesio').val('');
                      Swal.fire({
                          icon: 'success',
                          title: 'Receta anulada',
                          text: response.msg
                      });

                    } else {
                      console.log(response);
                      Swal.fire({
                          icon: 'warning',
                          title: 'No puede ser anulada la receta',
                          text: response.msg
                      });
                    }
                },
                error: function(xhr) {
                    console.log('Ocurrió un error en la solicitud AJAX');
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                    });
                }
              });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#squarespaceModalAnestesio').hide();
                Swal.fire({
                    icon: 'info',
                    title: 'Invalidar receta, cancelada',
                    text: 'No se invalidó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
  });
  $(document).ready(function(){
    $('#motivo_invalidar_anestesio').on('input', function () {
        if ($(this).val().trim() !== '') {
            $(this).css('border', '');
        }
    });
  });
  function filtrarNumeroDecimal(input) {
        input.value = input.value
        .replace(/[^0-9.]/g, '')
        .replace(/(\..*?)\..*/g, '$1');
  }

  $('#id_tipo_anestesia').select2();

  var seleccionados = {!! json_encode($idsSeleccionados) !!};
  $('#id_tipo_anestesia').val(seleccionados).trigger('change');

  $(document).on('click', '#btnSalir1, #btnSalir2, #btnSalir3', function() {
    $('#modalAnestesia').modal('hide');
  });

  function scrollAModal(el, offsetTop) {
    var $el = $(el);
    var $contenedor = $('#modalAnestesiaBody');
    if (!$el.length) return;
    if ($contenedor.length && $.contains($contenedor[0], $el[0])) {
        var destino = $contenedor.scrollTop() + $el.offset().top - $contenedor.offset().top - offsetTop;
        $contenedor.animate({ scrollTop: destino }, 400);
    } else {
        $('html, body').animate({ scrollTop: $el.offset().top - offsetTop }, 400);
    }
  }

  function validarConfirmaIntervencion() {
    if ($('#addConfirmaIntervencion').attr('aria-pressed') === 'true') {
        $('#addConfirmaIntervencion').removeClass('campo-invalido');
        return true;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Validación requerida',
        html: 'Debe activar <b>CONFIRMAR INTERVENCIÓN</b> antes de guardar.',
        confirmButtonText: 'ENTENDIDO',
        confirmButtonColor: '#d33',
    }).then(() => {
        $('#addConfirmaIntervencion').addClass('campo-invalido');
        scrollAModal('#addConfirmaIntervencion', 120);
    });

    return false;
  }

  $('#addConfirmaIntervencion').on('click', function () {
    setTimeout(() => {
        if ($(this).attr('aria-pressed') === 'true') {
            $(this).removeClass('campo-invalido');
        }
    }, 0);
  });

  function validarDiagnosticosAdicionales() {
    let errores = [];
    let filas1 = $("#tblDiagnostico tbody tr");
    let filas2 = $("#tblDiagnosticoAdicionalAnestesia tbody tr");

    let total1 = filas1.length;
    let total2 = filas2.length;

    // Validar si no hay filas
    let id_prestacion = Number($('#id_prestacion_anestesia').val() || 0);
    let sinFilas = total2 === 0 && id_prestacion === 0 ||  total1=== 0 && total2 === 0 && id_prestacion>0;
    if (sinFilas) {
        errores.push("Debe ingresar mínimo <b>un diagnóstico adicional</b>.");
    }
    let filaConError = null;
    filas2.each(function (index) {

        let idDiagnostico = $(this).find(".id_diagnostico_adicional_anestesia").val();
        let tipoDiag = $(this).find(".id_tipo_diagnostico_adicional_anestesia").val();

        // limpiar estado previo
        $(this).removeClass("error-fila");
        $(this).find('input[name^="nombre_diagnostico_adicional_anestesia"]').removeClass("campo-invalido");
        $(this).find(".id_tipo_diagnostico_adicional_anestesia").removeClass("campo-invalido");

        if (!idDiagnostico || idDiagnostico.trim() === "") {
            errores.push(`Fila ${index + 1}: Debe ingresar el diagnóstico.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find('input[name^="nombre_diagnostico_adicional_anestesia"]').addClass("campo-invalido");
        }

        if (!tipoDiag || tipoDiag === "0") {
            errores.push(`Fila ${index + 1}: Debe seleccionar el tipo de diagnóstico.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find(".id_tipo_diagnostico_adicional_anestesia").addClass("campo-invalido");
        }
    });
    // Mostrar errores
    if (errores.length > 0) {

        Swal.fire({
            icon: "warning",
            title: "Validación requerida",
            html: errores.join("<br>"),
            confirmButtonText: "ENTENDIDO",
            confirmButtonColor: "#d33",
        }).then(() => {
          if (sinFilas) {
            $('#addDiagnosticoAdicionalAnestesia').trigger('click');
            setTimeout(() => {
              let nuevaFila = $('#tblDiagnosticoAdicionalAnestesia tbody tr').last();
              nuevaFila.addClass('error-fila');
              nuevaFila.find('input[name^="nombre_diagnostico_adicional_anestesia"]').addClass('campo-invalido');
              nuevaFila.find('.id_tipo_diagnostico_adicional_anestesia').addClass('campo-invalido');
              scrollAModal(nuevaFila, 120);
            }, 100);
          } else if (filaConError) {
            scrollAModal(filaConError, 120);
          }
        });

        return false;
    }
    return true; // Todo OK
  }

  function validarProcedimientosAdicionales() {

    let errores = [];

    let filas1 = $("#tblProcedimiento tbody tr");
    let filas2 = $("#tblProcedimientoAdicionalAnestesia tbody tr");
    let total1 = filas1.length;
    let total2 = filas2.length;

    // Si no hay filas
    let id_prestacion = Number($('#id_prestacion_anestesia').val() || 0);
    let sinFilas = total2 === 0 && id_prestacion === 0 ||  total1=== 0 && total2 === 0 && id_prestacion>0;
    if (sinFilas) {
        errores.push("Debe ingresar mínimo <b>un procedimiento adicional</b>.");
    }

    let filaConError = null;

    filas2.each(function (index) {

        let idProc = $(this).find(".id_procedimiento_adicional_anestesia").val();
        let idDiag = $(this).find(".select-diagnostico-anestesia").val();

        // Limpiar estilos anteriores
        $(this).removeClass("error-fila");
        $(this).find('input[name^="nombre_procedimiento_adicional_anestesia"]').removeClass("campo-invalido");
        $(this).find(".select-diagnostico-anestesia").removeClass("campo-invalido");

        if (!idProc || idProc.trim() === "") {
            errores.push(`Fila ${index + 1}: Debe ingresar el nombre del procedimiento.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find('input[name^="nombre_procedimiento_adicional_anestesia"]').addClass("campo-invalido");
        }

        if (!idDiag || idDiag === "0") {
            errores.push(`Fila ${index + 1}: Debe seleccionar un diagnóstico.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find(".select-diagnostico-anestesia").addClass("campo-invalido");
        }
    });

    // Si hay errores → mostrar Swal
    if (errores.length > 0) {

      Swal.fire({
          icon: "warning",
          title: "Validación requerida",
          html: errores.join("<br>"),
          confirmButtonText: "ENTENDIDO",
          confirmButtonColor: "#d33"
      }).then(() => {
        if (sinFilas) {
          $('#addProcedimientoAdicionalAnestesia').trigger('click');
          setTimeout(() => {
            let nuevaFila = $('#tblProcedimientoAdicionalAnestesia tbody tr').last();
            nuevaFila.addClass('error-fila');
            nuevaFila.find('input[name^="nombre_procedimiento_adicional_anestesia"]').addClass('campo-invalido');
            scrollAModal(nuevaFila, 100);
          }, 100);
        } else if (filaConError) {
          scrollAModal(filaConError, 100);
        }
      });
      return false;
    }
    return true;
  }

  function validarProcedimientoAnestesia() {

    let errores = [];

    let filas = $('#tblProcedimientoAnestesia tbody tr');
    let total = filas.length;

    let sinFilas = total === 0;
    if (sinFilas) {
        errores.push("Debe ingresar mínimo <b>un procedimiento</b>.");
    }

    let filaConError = null;

    filas.each(function (index) {

        let idProcedimiento = $(this).find('.id_procedimiento_anestesia').val();
        let cantidad = $(this).find('.procedimiento_cantidad_anestesia').val();
        let miSelect = $(this).find('.select-diagnostico-anestesia').val();

        $(this).removeClass("error-fila");
        $(this).find('input[name^="nombre_procedimiento_anestesia"]').removeClass("campo-invalido");
        $(this).find('.procedimiento_cantidad_anestesia').removeClass("campo-invalido");
        $(this).find('.select-diagnostico-anestesia').removeClass("campo-invalido");

        if (!idProcedimiento || idProcedimiento.trim() === "") {
            errores.push(`Fila ${index + 1}: Debe ingresar el nombre del procedimiento.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find('input[name^="nombre_procedimiento_anestesia"]').addClass("campo-invalido");
        }

        if (!cantidad || cantidad === "" || Number(cantidad) === 0) {
            errores.push(`Fila ${index + 1}: Debe ingresar la cantidad del procedimiento, mayor a 0.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find('.procedimiento_cantidad_anestesia').addClass("campo-invalido");
        }

        if (!miSelect || miSelect === "0") {
            errores.push(`Fila ${index + 1}: Debe seleccionar el diagnóstico para el procedimiento.`);
            if (!filaConError) filaConError = this;
            $(this).addClass("error-fila");
            $(this).find('.select-diagnostico-anestesia').addClass("campo-invalido");
        }
    });

    if (errores.length > 0) {

        Swal.fire({
            icon: "warning",
            title: "Validación requerida",
            html: errores.join("<br>"),
            confirmButtonText: "ENTENDIDO",
            confirmButtonColor: "#d33"
        }).then(() => {
            if (sinFilas) {
                $('#addProcedimientoAnestesia').trigger('click');
                setTimeout(() => {
                    let nuevaFila = $('#tblProcedimientoAnestesia tbody tr').last();
                    nuevaFila.addClass('error-fila');
                    nuevaFila.find('input[name^="nombre_procedimiento_anestesia"]').addClass('campo-invalido');
                    scrollAModal(nuevaFila, 100);
                }, 100);
            } else if (filaConError) {
                scrollAModal(filaConError, 100);
            }
        });

        return false;
    }

    return true; // Todo OK
  }

    // PAQUETIZACION PARA RECETA EXISTENTE
    // $(document).on('change', '[id^="tipo_anestesia_rv_existente"]', function () {
      
    //   const tipo = $(this).val();
    //   const tipo_anestesia_text = $(this).find('option:selected').text().trim();
    //   const id_receta = $(this).data('id_receta');
    //   $('#tipo_anestesia_rv_existente'+id_receta).show();
    //   if (tipo === '') return;
    //   Swal.fire({
    //       title: 'Productos de tipo de anestesiología '+tipo_anestesia_text,
    //       text: '¿Cómo desea cargar los productos?',
    //       icon: 'warning',
    //       showCancelButton: true,
    //       showDenyButton: true,
    //       confirmButtonText: 'Cargar TODOS',
    //       denyButtonText: 'Solo SIN STOCK',
    //       cancelButtonText: 'Cancelar',
    //       customClass: {
    //           confirmButton: 'btn-red',
    //           denyButton: 'btn-orange',
    //           cancelButton: 'btn-cancel'
    //       }
    //   }).then((result) => {

    //       if (!result.isConfirmed && !result.isDenied) return;
    //       const soloSinStock = result.isDenied;
    //       cargarProductosFiltradaRecetaExistente(id_receta, tipo, soloSinStock,tipo_anestesia_text );
    //   });
    // });
    // $(document).on('change', '[id^="tipo_anestesia_rv_existente"]', function () {

    //     const tipo = $(this).val();
    //     if (!tipo) return;

    //     const tipo_anestesia_text = $(this).find('option:selected').text().trim();
    //     const id_receta = $(this).data('id_receta');

    //     Swal.fire({
    //         title: 'Productos de tipo de anestesiología ' + tipo_anestesia_text,
    //         text: '¿Cómo desea cargar los productos?',
    //         icon: 'question',
    //         showCancelButton: true,
    //         showDenyButton: true,
    //         confirmButtonText: 'Cargar TODOS',
    //         denyButtonText: 'Solo SIN STOCK',
    //         cancelButtonText: 'Cancelar',
    //         customClass: {
    //             confirmButton: 'btn-red',
    //             denyButton: 'btn-orange',
    //             cancelButton: 'btn-cancel'
    //         }
    //     }).then((result) => {
    //         if (!result.isConfirmed && !result.isDenied) return;
    //         cargarProductosFiltradaRecetaExistente(id_receta, tipo, result.isDenied, tipo_anestesia_text);
    //     });
    // });
    $(document).on('change', '[id^="tipo_anestesia_rv_existente"]', function () {

      const tipo = $(this).val();
      if (!tipo) return;

      const tipo_anestesia_text = $(this).find('option:selected').text().trim();
      const id_receta = $(this).data('id_receta');

      const $tbody = $('#tblMedicamentosAnestesia_' + id_receta + ' tbody');
      const filas = $tbody.find('tr.producto_adicional_paquetizacion');

      const hayOtroTipo = filas.length > 0 && filas.filter(function () {
          return $(this).data('tipo') != tipo;
      }).length > 0;

      if (hayOtroTipo) {

          Swal.fire({
              title: 'Cambio de tipo de anestesia',
              html: `
                  <b>Se limpiarán los productos cargados del tipo:</b><br>
                  <span style="color:#c0392b">${tipo_anestesia_text}</span>
              `,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Sí, limpiar y cargar',
              cancelButtonText: 'Cancelar'
          }).then(r => {
              if (!r.isConfirmed) return;
              limpiarProductosAnestesiaRecetaExistente(id_receta);
              Swal.fire({
                  title: 'Productos de tipo de anestesiología ' + tipo_anestesia_text,
                  text: '¿Cómo desea cargar los productos?',
                  icon: 'question',
                  showCancelButton: true,
                  showDenyButton: true,
                  confirmButtonText: 'Cargar TODOS',
                  denyButtonText: 'Solo SIN STOCK',
                  cancelButtonText: 'Cancelar',
                  customClass: {
                      confirmButton: 'btn-red',
                      denyButton: 'btn-orange',
                      cancelButton: 'btn-cancel'
                  }
              }).then((result) => {
                  if (!result.isConfirmed && !result.isDenied) return;
                  cargarProductosFiltradaRecetaExistente(id_receta, tipo, result.isDenied, tipo_anestesia_text);
              });
          });

      } else if (filas.length === 0) {

          Swal.fire({
              title: 'Productos de tipo de anestesiología ' + tipo_anestesia_text,
              text: '¿Cómo desea cargar los productos?',
              icon: 'question',
              showCancelButton: true,
              showDenyButton: true,
              confirmButtonText: 'Cargar TODOS',
              denyButtonText: 'Solo SIN STOCK',
              cancelButtonText: 'Cancelar',
              customClass: {
                  confirmButton: 'btn-red',
                  denyButton: 'btn-orange',
                  cancelButton: 'btn-cancel'
              }
          }).then((result) => {
              if (!result.isConfirmed && !result.isDenied) return;
              cargarProductosFiltradaRecetaExistente(id_receta, tipo, result.isDenied, tipo_anestesia_text);
          });
      }
  });



    // Inicializar todos los combos existentes al cargar la página
    $('.cmbMedicamento').each(function() {
        const select = this;
        if (!select.tomselect) {
            select.tomselect = new TomSelect(select, {
                plugins: ['clear_button'],
                placeholder: 'Seleccionar Producto',
                allowEmptyOption: true
            });
        }
    });
    
    // PAQUETIZACIÓN PARA NUEVA RECETA
    $(document).on('change', '#tipo_anestesia_rv', function () {
      const tipo = $(this).val();
      const texto = $('#tipo_anestesia_rv option:selected').text().trim();
      if (!tipo) return;
      // const filasAnestesia = $('#example tbody tr.producto_adicional').not('.producto_adicional_paquetizacion');
      const filasAnestesia = $('#example tbody tr.producto_adicional_paquetizacion');
      const hayOtroTipo = filasAnestesia.length > 0 && filasAnestesia.filter(function(){
        return $(this).data('tipo') != tipo;
      }).length > 0;

      if (hayOtroTipo) {

        Swal.fire({
          title: 'Cambio de tipo de anestesia',
          html: `
            <b>Se limpiarán los productos cargados del tipo:</b><br>
            <span style="color:#c0392b">${texto}</span>
          `,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, limpiar y cargar',
          cancelButtonText: 'Cancelar'
        }).then(r => {
          if (r.isConfirmed) {
            limpiarProductosAnestesia();
            lanzarCarga(tipo, texto);
          }
        });

      } else if (filasAnestesia.length === 0) {
        lanzarCarga(tipo, texto);
      }
    });

    function limpiarProductosAnestesia() {
       $('#example tbody tr.producto_adicional_paquetizacion').remove();
    }
    function limpiarProductosAnestesiaRecetaExistente(id_receta) {
       $('#tblMedicamentosAnestesia_'+id_receta+' tbody tr.producto_adicional_paquetizacion').remove();
    }

    function lanzarCarga(tipo, texto){
      Swal.fire({
        title: 'Productos de tipo de anestesiología ' + texto,
        text: '¿Cómo desea cargar los productos?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Cargar TODOS',
        denyButtonText: 'Solo SIN STOCK',
        cancelButtonText: 'Cancelar',
        customClass: {
          confirmButton: 'btn-red',
          denyButton: 'btn-orange',
          cancelButton: 'btn-cancel'
        }
      }).then((result) => {
        if (!result.isConfirmed && !result.isDenied) return;
        cargarProductosFiltrada(tipo, result.isDenied, texto);
      });
    }

    // function cargarProductosFiltrada(tipo, soloSinStock, tipo_anestesia_text) {
    //   const $base = $('#cmbMedicamentoTempAnestesiaNuevo');
    //   const $complemento = $('#cmbMedicamentoTempAnestesiaNuevoTipoAnestesio');
    //   const productos = [];
    //   let existe = false;

    //   $complemento.find('option').each(function () {

    //     const stock = Number(this.dataset.stock || 0);
    //     const tipoAnestesia = this.dataset.tipoAnestesia;

    //     if (tipoAnestesia == tipo) {
    //         existe = true;
    //         if (
    //             (!soloSinStock && stock >= 0) ||   // TODOS
    //             (soloSinStock && stock === 0)      // SOLO SIN STOCK
    //         ) {
    //             productos.push({
    //                 value: this.value,
    //                 ru: $(this).attr("ru") || "",
    //                 text: $(this).text().trim()
    //             });
    //         }
    //     }
    //   });

    //   if (!existe || productos.length === 0) {
    //     Swal.fire({
    //       title: 'No existen productos con el tipo de anestesia ' + tipo_anestesia_text,
    //       icon: 'warning',
    //       confirmButtonText: 'OK'
    //     });
    //     return;
    //   }

    //   //construir lista UNA sola vez
    //   let optionsHTML = `<option value="">-- Seleccione --</option>`;
    //   productos.forEach(p=>{
    //     optionsHTML += `<option value="${p.value}" ru="${p.ru}">${p.text}</option>`;
    //   });

    //   // cuántas filas crear (1 por producto)
    //   const start = $('#example tbody tr').length;
    //   productos.forEach((p, i)=>{
    //     crearListadoProductosTipoAnestesiaPaquetizacion(start + i + 1, optionsHTML);
    //   });
    // }
    function cargarProductosFiltrada(tipo, soloSinStock, tipo_anestesia_text) {

      const $base = $('#cmbMedicamentoTempAnestesiaNuevo option');
      const $complemento = $('#cmbMedicamentoTempAnestesiaNuevoTipoAnestesio option');
      console.log($complemento);
      

      const productosBase = [];
      const productosComplemento = [];

      // BASE (para llenar combos)
      $base.each(function () {
          productosBase.push({
              value: this.value,
              ru: $(this).attr("ru") || "",
              text: $(this).text().trim()
          });
      });

      // COMPLEMENTO (para filas)
      $complemento.each(function () {

          const stock = Number(this.dataset.stock || 0);
          const tipoAnestesia = this.dataset.tipoAnestesia;

          if (tipoAnestesia == tipo) {
              if (
                  (!soloSinStock && stock >= 0) ||
                  (soloSinStock && stock === 0)
              ) {
                  productosComplemento.push({
                      value: this.value,
                      ru: $(this).attr("ru") || "",
                      text: $(this).text().trim()
                  });
              }
          }
      });

      if (productosComplemento.length === 0) {
          Swal.fire({
              title: 'No existen productos con el tipo de anestesia ' + tipo_anestesia_text,
              icon: 'warning',
              confirmButtonText: 'OK'
          });
          return;
      }

      //Construir HTML BASE + COMPLEMENTO
      let optionsHTML = `<option value="">-- Seleccione --</option>`;

      productosBase.forEach(p => {
          optionsHTML += `<option value="${p.value}" ru="${p.ru}">${p.text}</option>`;
      });

      productosComplemento.forEach(p => {
          if (!productosBase.some(b => b.value == p.value)) {
              optionsHTML += `<option value="${p.value}" ru="${p.ru}">${p.text}</option>`;
          }
      });

      // Crear filas (1 por producto complemento)
      const start = $('#example tbody tr').length;

      productosComplemento.forEach((p, i) => {
          const idx = start + i + 1;
          crearListadoProductosTipoAnestesiaPaquetizacion(idx, optionsHTML, p.value);
      });
    }


    // function crearListadoProductosTipoAnestesiaPaquetizacion(ind, item_producto) {

    //   let newRow = ''; // ← reset obligatorio

    //   const vias = `
    //     <option value="0">--Seleccionar--</option>
    //     <option value="1">Oral</option>
    //     <option value="2">Sublingual</option>
    //     <option value="3">Rectal</option>
    //     <option value="4">Intravenosa</option>
    //     <option value="5">Intramuscular</option>
    //     <option value="6">Subcutánea</option>
    //     <option value="7">Dérmica</option>
    //     <option value="8">Nasal</option>
    //     <option value="9">Oftalmológica</option>
    //     <option value="10">Inhalatoria</option>
    //     <option value="11">Epidural</option>
    //     <option value="12">Intratecal</option>
    //     <option value="13">Vaginal</option>
    //     <option value="14">Intraarticular</option>
    //     <option value="15">No Aplica</option>
    //     <option value="16">Otros</option>`;

    //   newRow += `<tr class="producto_adicional_paquetizacion" data-ind="${ind}">
    //     <td>
    //       <select class="select-tom cmbMedicamento" id="cmbMedicamento${ind}" name="id_producto_adicional_nuevo[]">
    //         ${item_producto}
    //       </select>
    //     </td>

    //     <input type="hidden" name="nro_stocks_almacen_adicional_nuevo[]" class="nro_stocks_almacen">
    //     <input type="hidden" name="nro_stocks_establecimiento_adicional_nuevo[]" class="nro_stocks_establecimiento">

    //     <td><input type="text" class="form-control nro_stocks" readonly></td>

    //     <td><input type="text" class="nro_solicitado tiempo_periodo input-sm productos-responsive" style="margin-left:1px; width:100%;"></td>

    //     <td><select class="form-control id_via_administracion" style="margin-left:1px; width:100%;">${vias}</select></td>

    //     <td><input type="text" class="nro_solicitado posologia input-sm productos-responsive" style="margin-left:1px; width:100%;"></td>

    //     <td><input type="text" class="limpia_text nro_solicitado nro_med_solictados input-sm productos-responsive" style="margin-left:1px; width:100%;"></td>

    //     <td><select class="form-control miSelectProdAdicionalAnestesia"></select></td>

    //     <td><button type="button" class="btn btn-danger deleteFila btn-xs">✖</button></td>
    //   </tr>`;

    //   $('#example tbody').append(newRow);

    //   /* TOMSELECT */
    //   const ts = new TomSelect("#cmbMedicamento" + ind, { plugins:['clear_button'] });
    //   tomSelectInstancesProductoAnestesioNuevo["cmbMedicamento" + ind] = ts;

    //   /* SOLO al select nuevo, no a todos */
    //   const $diag = $(`#example tbody tr:last .miSelectProdAdicionalAnestesia`).empty();
    //   listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach(d =>
    //     $diag.append(`<option value="${d.id}">${d.codigo} - ${d.nombre}</option>`)
    //   );

    //   /* EVENTO DEL SELECT NUEVO */
    //   ts.on('change', function(value){
    //     validarDuplicadoProducto(value, ind);
    //     cargarStockFila(value, ind);
    //   });
    // }
    function crearListadoProductosTipoAnestesiaPaquetizacion(ind, item_producto, selectedValue) {

      const vias = `
        <option value="0">--Seleccionar--</option>
        <option value="1">Oral</option>
        <option value="2">Sublingual</option>
        <option value="3">Rectal</option>
        <option value="4">Intravenosa</option>
        <option value="5">Intramuscular</option>
        <option value="6">Subcutánea</option>
        <option value="7">Dérmica</option>
        <option value="8">Nasal</option>
        <option value="9">Oftalmológica</option>
        <option value="10">Inhalatoria</option>
        <option value="11">Epidural</option>
        <option value="12">Intratecal</option>
        <option value="13">Vaginal</option>
        <option value="14">Intraarticular</option>
        <option value="15">No Aplica</option>
        <option value="16">Otros</option>`;

      const selectID = `cmbMedicamentoAdicionalAnestesia_${ind}`;

      const newRow = `<tr class="producto_adicional_paquetizacion" data-ind="${ind}">
          <td>
            <select class="select-tom cmbMedicamento productos-responsive" id="${selectID}" ind="${ind}"  name="id_producto_adicional_nuevo[]" style="width: 100%;"></select>  
          </td>
          <input type="hidden" name="nro_stocks_almacen_adicional_nuevo[]" class="nro_stocks_almacen">
          <input type="hidden" name="nro_stocks_establecimiento_adicional_nuevo[]" class="nro_stocks_establecimiento">
          <td><input type="text" name="nro_stocks_adicional_nuevo[]" class="form-control nro_stocks"  readonly></td>
          <td><input type="text" name="tiempo_periodo_adicional_nuevo[]" onkeypress="return soloNumerosMenosCero(event)" class="nro_solicitado tiempo_periodo input-sm productos-responsive"  style="margin-left:1px; width:100%;" value="1"></td>
          <td><input type="text" name="posologiaAdicional_nuevo[]" class="nro_solicitado posologia input-sm productos-responsive" style="margin-left:1px; width:100%;" value="SOP"></td>
          <td><select class="form-control id_via_administracion" name="id_via_administracion_anestesia_adicional_nuevo[]">${vias}</select></td>
          <td><input type="text" onkeypress="return soloNumerosMenosCero(event)" name="nro_med_solictados_anestesia_adicional_nuevo[]" class="limpia_text nro_solicitado nro_med_solictados input-sm productos-responsive" style="margin-left:1px; width:100%;"></td>
          <td><select class="form-control miSelectProdAdicionalAnestesia" name="miSelectProdAdicionalAnestesia_nuevo[]"></select></td>
          <td colspan="3"><button type="button" class="btn btn-danger deleteFila btn-xs">✖</button></td>
      </tr>`;

      const $tbody = $('#example tbody');
      $tbody.append(newRow);

      const ts = new TomSelect("#" + selectID, { plugins:['clear_button'] });

      ts.clear(true);
      ts.clearOptions();

      $(item_producto).each(function(){
          ts.addOption({ value: this.value, text: this.text });
      });
      ts.refreshOptions(false);

      // limpiar stocks antes de precargar
      const fila = $tbody.find('tr:last');
      fila.find('.nro_stocks, .nro_stocks_almacen, .nro_stocks_establecimiento').val('');

      if(selectedValue){
          ts.setValue(selectedValue, true);
          cargarStockFila(selectedValue, ind);
      }
      const $diag = fila.find('.miSelectProdAdicionalAnestesia').empty();
      listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach(d =>
          $diag.append(`<option value="${d.id}">${d.codigo} - ${d.nombre}</option>`)
      );

      ts.on('change', function(value){
          const fila = $(this.control).closest('tr');
          fila.find('.nro_stocks, .nro_stocks_almacen, .nro_stocks_establecimiento').val('');
          validarDuplicadoProducto(value, ind);
          cargarStockFila(value, ind);
      });
    }
    function crearListadoProductosTipoAnestesiaPaquetizacionRecetaExistente(id_receta, ind, item_producto, selectedValue) {

      const vias = `
        <option value="0">--Seleccionar--</option>
        <option value="1">Oral</option>
        <option value="2">Sublingual</option>
        <option value="3">Rectal</option>
        <option value="4">Intravenosa</option>
        <option value="5">Intramuscular</option>
        <option value="6">Subcutánea</option>
        <option value="7">Dérmica</option>
        <option value="8">Nasal</option>
        <option value="9">Oftalmológica</option>
        <option value="10">Inhalatoria</option>
        <option value="11">Epidural</option>
        <option value="12">Intratecal</option>
        <option value="13">Vaginal</option>
        <option value="14">Intraarticular</option>
        <option value="15">No Aplica</option>
        <option value="16">Otros</option>`;

      const selectID = `cmbMedicamentoAdicionalAnestesia${id_receta}_${ind}`;

      const newRow = `<tr class="producto_adicional_paquetizacion" data-ind="${ind}">
          <td>
            <select class="select-tom cmbMedicamento productos-responsive" id="${selectID}" ind="${ind}"  name="id_producto_adicional[${id_receta}][${ind}]" style="width: 100%;"></select>
          </td>
          <input type="hidden" name="nro_stocks_almacen_adicional[${id_receta}][${ind}]" class="nro_stocks_almacen">
          <input type="hidden" name="nro_stocks_establecimiento_adicional[${id_receta}][${ind}]" class="nro_stocks_establecimiento">
          <input type="hidden" name="lotes_lote_adicional[${id_receta}][${ind}]" id="lotes_lote_adicional" value=""/>';

          <input type="hidden" name="lotes_registro_sanitario_adicional[${id_receta}][${ind}]" id="lotes_registro_sanitario_adicional" value=""/>;
          <input type="hidden" name="lotes_fecha_vencimiento_adicional[${id_receta}][${ind}]" id="lotes_fecha_vencimiento_adicional" value=""/>;
          <input type="hidden" name="lotes_precio_unitario_adicional[${id_receta}][${ind}]" id="lotes_precio_unitario_adicional" value=""/>;
          <input type="hidden" name="lotes_cantidad_adicional[${id_receta}][${ind}]" id="lotes_cantidad_adicional" value=""/></td>;

          <td><input type="text" class="form-control nro_stocks" name="nro_stocks_adicional[${id_receta}][${ind}]"  readonly></td>
          <td><input type="text" onkeypress="return soloNumerosMenosCero(event)" class="nro_solicitado tiempo_periodo input-sm productos-responsive" name="tiempo_periodo_adicional[${id_receta}][${ind}]"  style="margin-left:1px; width:100%;"></td>
          
          <td><input type="text" class="nro_solicitado posologia input-sm productos-responsive" name="posologiaAdicional[${id_receta}][${ind}]" style="margin-left:1px; width:100%;"></td>
          <td><select class="form-control id_via_administracion" name="id_via_administracion_anestesia_adicional[${id_receta}][${ind}]">${vias}</select></td>
          <td><input type="text" onkeypress="return soloNumerosMenosCero(event)" name="nro_med_solictados_anestesia_adicional[${id_receta}][${ind}]" class="limpia_text nro_solicitado nro_med_solictados input-sm productos-responsive" style="margin-left:1px; width:100%;"></td>
          <td><select class="form-control miSelectProdAdicionalAnestesia" name="miSelectProdAdicionalAnestesia[${id_receta}][${ind}]"></select></td>
          <td colspan="3"><button type="button" class="btn btn-danger deleteFilaExistenteAnestesio btn-xs">✖</button></td>
      </tr>`;

      const $tbody = $('#tblMedicamentosAnestesia_'+id_receta+' tbody');
      $tbody.append(newRow);

      const ts = new TomSelect("#" + selectID, { plugins:['clear_button'] });

      ts.clear(true);
      ts.clearOptions();

      $(item_producto).each(function(){
          ts.addOption({ value: this.value, text: this.text });
      });
      ts.refreshOptions(false);

      // limpiar stocks antes de precargar
      const fila = $tbody.find('tr:last');
      fila.find('.nro_stocks, .nro_stocks_almacen, .nro_stocks_establecimiento').val('');

      if(selectedValue){
          ts.setValue(selectedValue, true);
          cargarStockFilaRecetaExistente(id_receta, selectedValue, ind);
      }

      const $diag = fila.find('.miSelectProdAdicionalAnestesia').empty();
      listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach(d =>
          $diag.append(`<option value="${d.id}">${d.codigo} - ${d.nombre}</option>`)
      );

      ts.on('change', function(value){
          const fila = $(this.control).closest('tr');
          fila.find('.nro_stocks, .nro_stocks_almacen, .nro_stocks_establecimiento').val('');
          validarDuplicadoProducto(value, ind);
          cargarStockFilaRecetaExistente(id_receta, value, ind);
      });
    }

    function cargarStockFilaRecetaExistente(id_receta, id_producto, ind){

        if(!id_producto) return;
        const fila = $(`#tblMedicamentosAnestesia_${id_receta} tbody tr[data-ind="${ind}"]`);

        if(fila.data('xhr')){
            fila.data('xhr').abort();
        }

        fila.find('.nro_stocks').val('');
        fila.find('.nro_stocks_establecimiento').val('');
        fila.find('.nro_stocks_almacen').val('');

        fila.data('producto_actual', id_producto);

        const xhr = $.getJSON('/cargarstock_porfarmacia/' + id_producto + '/' + $('#id_farmacia').val(), function(d){

            if(fila.data('producto_actual') != id_producto) return;

            fila.find('.nro_stocks').val(d.cantidad);
            fila.find('.nro_stocks_establecimiento').val(d.cantidadEstablecimiento);
            fila.find('.nro_stocks_almacen').val(d.cantidadAlmacen);
            fila.find('.tiempo_periodo').focus();
        });

        fila.data('xhr', xhr);
    }

    // function validarDuplicadoProducto(valor, ind){
    //   let rep = 0;
    //   $('.cmbMedicamento').each(function(){
    //     if($(this).val() === valor) rep++;
    //   });
    //   if(rep > 1 && valor){
    //     bootbox.alert("Este producto ya fue agregado");
    //     tomSelectInstancesProductoAnestesioNuevo["cmbMedicamento"+ind].clear();
    //   }
    // }
    function validarDuplicadoProducto(valor, ind){

        if(!valor) return;
        const $tabla = $('#example:visible, [id^="tblMedicamentosAnestesia_"]:visible');

        let rep = 0;

        $tabla.find('.cmbMedicamento').each(function(){
            if($(this).val() == valor) rep++;
        });

        if(rep > 1){
            bootbox.alert("Este producto ya fue agregado");
            const ts = tomSelectInstancesProductoAnestesioNuevo["cmbMedicamento"+ind];
            if(ts){
                ts.clear(true); 
            }
        }
    }


    function cargarStockFila(id_producto, ind){
      if(!id_producto) return;
      $.getJSON('/cargarstock_porfarmacia/'+id_producto+'/'+$('#id_farmacia').val(), function(d){
        const fila = $(`#example tbody tr[data-ind="${ind}"]`);
        fila.find('.nro_stocks').val(d.cantidad);
        fila.find('.nro_stocks_establecimiento').val(d.cantidadEstablecimiento);
        fila.find('.nro_stocks_almacen').val(d.cantidadAlmacen);
        fila.find('.tiempo_periodo').focus();
      });
    }

    function cargarProductosFiltradaRecetaExistente(id_receta, tipo, soloSinStock, tipo_anestesia_text) {

        const $base = $('#cmbMedicamentoTempAnestesia option');
        const $complemento = $('#cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia option');

        const productosBase = [];
        const productosComplemento = [];

        // BASE
        $base.each(function () {
            productosBase.push({
                value: this.value,
                ru: $(this).attr("ru") || "",
                text: $(this).text().trim()
            });
        });

        // COMPLEMENTO
        $complemento.each(function () {
            const stock = Number(this.dataset.stock || 0);
            const tipoAnestesia = this.dataset.tipoAnestesia;

            if (tipoAnestesia == tipo) {
                if ((!soloSinStock && stock >= 0) || (soloSinStock && stock === 0)) {
                    productosComplemento.push({
                        value: this.value,
                        ru: $(this).attr("ru") || "",
                        text: $(this).text().trim()
                    });
                }
            }
        });

        if (productosComplemento.length === 0) {
            Swal.fire({
                title: 'No existen productos con el tipo de anestesia ' + tipo_anestesia_text,
                icon: 'warning'
            });
            return;
        }

        // Construir BASE + COMPLEMENTO
        let optionsHTML = `<option value="">-- Seleccione --</option>`;
        productosBase.forEach(p => {
            optionsHTML += `<option value="${p.value}" ru="${p.ru}">${p.text}</option>`;
        });

        productosComplemento.forEach(p => {
            if (!productosBase.some(b => b.value == p.value)) {
                optionsHTML += `<option value="${p.value}" ru="${p.ru}">${p.text}</option>`;
            }
        });

        //TABLA DINÁMICA
        const $tbody = $(`#tblMedicamentosAnestesia_${id_receta} tbody`);
        const start = $tbody.find('tr').length;

        productosComplemento.forEach((p, i) => {
            const idx = start + i + 1;
            crearListadoProductosTipoAnestesiaPaquetizacionRecetaExistente(id_receta,idx, optionsHTML, p.value, $tbody);
        });
    }
    $(document).ready(function(){
      var recetacq = {!! json_encode(isset($recetacq) ? $recetacq : []) !!};
      if (Array.isArray(recetacq) && recetacq.length > 0) {
          getProductosRecetasExistentes();
      }
    });

    function validarAsegurado(){
      var tipodoc_beneficiario = {!! json_encode(isset($row_anestesia_quirurgica->id_tipo_doc_ident) ? trim($row_anestesia_quirurgica->id_tipo_doc_ident) : '') !!};
      var dni_beneficiario = {!! json_encode(isset($row_anestesia_quirurgica->nro_doc_ident) ? trim($row_anestesia_quirurgica->nro_doc_ident) : '') !!};
      ipodoc_beneficiario = tipodoc_beneficiario.trim();
      dni_beneficiario = dni_beneficiario.trim();
      var deferred = $.Deferred();
      if(tipodoc_beneficiario && dni_beneficiario){
          var id_tiporeceta = "1";
          $.ajax({
              url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
              dataType: "json"
          })
          .done(function(result){

              if(result.sw == false){

                  Swal.fire({
                      icon: 'warning',
                      title: 'Advertencia',
                      html: result.msg || 'El paciente no se encuentra afiliado',
                      confirmButtonText: 'Cerrar',
                      allowOutsideClick: false
                  });

                  deferred.resolve(false);
                  return;
              }

              deferred.resolve(true);

          })
          .fail(function(){

              Swal.fire({
                  icon:'error',
                  text:'Error al validar el asegurado'
              });

              deferred.resolve(false);
          });

      }else{
        deferred.resolve(true);
      }
      return deferred.promise();
    }
  </script>
  <style>

      #modalNuevoAnestesia .modal-dialog {
          position: fixed; /* Fija el modal en relación a la ventana */
          top: 80%; /* Mueve hacia abajo el modal */
          left: 64%; /* Mueve hacia la derecha el modal */
          transform: translate(-70%, -70%); /* Mantiene el centro de la caja en la nueva posición */
      }

      #modalNuevoBalanceHidrico .modal-dialog {
          position: fixed; /* Fija el modal en relación a la ventana */
          top: 70%; /* Mueve hacia abajo el modal */
          left: 64%; /* Mueve hacia la derecha el modal */
          transform: translate(-70%, -70%); /* Mantiene el centro de la caja en la nueva posición */
      }

    /* .modal-dialog{
      width: 850px !important;
    }     */
    .suggest-element{
      margin-left:5px;
      margin-top:5px;
      width:350px;
      cursor:pointer;
    }
    #suggestions {
      width:350px;
      height:150px;
      overflow: auto;
    }

    .alert {
    padding: 10px;
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    /*background-color: rgba(0, 0, 0, 0);*/
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    font-size: 14px;
    margin-bottom: 10px;
    }
    .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
    }
    .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top:1px solid rgba(255, 165, 0,0.4);
    border-bottom:1px solid rgba(255, 165, 0,0.4);
    color:  rgb(225, 75, 0);
    }
    .alert.alert-success {
    // background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top:1px solid limegreen;
    border-bottom:1px solid limegreen;
    }

    .form-control{
        height: 28px;
      text-align:left!important;
    }

    .form-group{
        margin-bottom: 10px;
    }

      .contenedor-datos-generales input,
      .contenedor-especialista input,
      .contenedor-paciente input,
      .contenedor-informacion-atencion input,
      .contenedor-referencia input{
          text-align: center !important;
      }

      input[type=checkbox]
      {
      /* Doble-tamaño Checkboxes */
      -ms-transform: scale(1); /* IE */
      -moz-transform: scale(1); /* FF */
      -webkit-transform: scale(1); /* Safari y Chrome */
      -o-transform: scale(1); /* Opera */
      padding: 5px;
      }

      .checkboxtexto
      {
      /* Checkbox texto */
      font-size: 130%;
      display: inline;
      }

      .btn-toggle-heading {
          margin: 0 4rem;
          padding: 0;
          position: relative;
          border: none;
          height: 1.5rem;
          width: 3rem;
          border-radius: 1.5rem;
          color: #6b7381;
          background: #bdc1c8;
      }
      .btn-toggle-heading:focus,
      .btn-toggle-heading.focus,
      .btn-toggle-heading:focus.active,
      .btn-toggle-heading.focus.active {
          outline: none;
      }
      .btn-toggle-heading:before,
      .btn-toggle-heading:after {
          line-height: 1.5rem;
          width: 4rem;
          text-align: center;
          font-weight: 600;
          font-size: 0.75rem;
          text-transform: uppercase;
          letter-spacing: 2px;
          position: absolute;
          bottom: 0;
          transition: opacity 0.25s;
      }
      .btn-toggle-heading:before {
          content: 'NO';
          left: -4rem;
      }
      .btn-toggle-heading:after {
          content: 'SI';
          right: -4rem;
          opacity: 0.5;
      }
      .btn-toggle-heading>.handle-heading {
          position: absolute;
          top: 0.3125rem;
          left: 0.1875rem;
          width: 1.125rem;
          height: 1.125rem;
          border-radius: 1.125rem;
          background: #fff;
          transition: left 0.25s;
      }
      .btn-toggle-heading.active {
          transition: background-color 0.25s;
      }
      .btn-toggle-heading.active>.handle-heading {
          left: 1.6875rem;
          transition: left 0.25s;
      }
      .btn-toggle-heading.active:before {
          opacity: 0.5;
      }
      .btn-toggle-heading.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-sm:before,
      .btn-toggle-heading.btn-sm:after {
          line-height: -0.5rem;
          color: #fff;
          letter-spacing: 0.75px;
          left: 0.4125rem;
          width: 2.325rem;
      }
      .btn-toggle-heading.btn-sm:before {
          text-align: right;
      }
      .btn-toggle-heading.btn-sm:after {
          text-align: left;
          opacity: 0;
      }
      .btn-toggle-heading.btn-sm.active:before {
          opacity: 0;
      }
      .btn-toggle-heading.btn-sm.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-xs:before,
      .btn-toggle-heading.btn-xs:after {
          display: none;
      }
      .btn-toggle-heading:before,
      .btn-toggle-heading:after {
          color: #6b7381;
      }
      .btn-toggle-heading.active {
          background-color: #29b5a8;
      }
      .btn-toggle-heading.btn-lg {
          margin: 0 5rem;
          padding: 0;
          position: relative;
          border: none;
          height: 2.5rem;
          width: 5rem;
          border-radius: 2.5rem;
      }
      .btn-toggle-heading.btn-lg:focus,
      .btn-toggle-heading.btn-lg.focus,
      .btn-toggle-heading.btn-lg:focus.active,
      .btn-toggle-heading.btn-lg.focus.active {
          outline: none;
      }
      .btn-toggle-heading.btn-lg:before,
      .btn-toggle-heading.btn-lg:after {
          line-height: 2.5rem;
          width: 5rem;
          text-align: center;
          font-weight: 600;
          font-size: 1rem;
          text-transform: uppercase;
          letter-spacing: 2px;
          position: absolute;
          bottom: 0;
          transition: opacity 0.25s;
      }
      .btn-toggle-heading.btn-lg:before {
          content: 'NO';
          left: -5rem;
      }
      .btn-toggle-heading.btn-lg:after {
          content: 'SI';
          right: -5rem;
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-lg>.handle-heading {
          position: absolute;
          top: 0.3125rem;
          left: 0.3125rem;
          width: 1.875rem;
          height: 1.875rem;
          border-radius: 1.875rem;
          background: #fff;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-lg.active {
          transition: background-color 0.25s;
      }
      .btn-toggle-heading.btn-lg.active>.handle-heading {
          left: 2.8125rem;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-lg.active:before {
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-lg.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-lg.btn-sm:before,
      .btn-toggle-heading.btn-lg.btn-sm:after {
          line-height: 0.5rem;
          color: #fff;
          letter-spacing: 0.75px;
          left: 0.6875rem;
          width: 3.875rem;
      }
      .btn-toggle-heading.btn-lg.btn-sm:before {
          text-align: right;
      }
      .btn-toggle-heading.btn-lg.btn-sm:after {
          text-align: left;
          opacity: 0;
      }
      .btn-toggle-heading.btn-lg.btn-sm.active:before {
          opacity: 0;
      }
      .btn-toggle-heading.btn-lg.btn-sm.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-lg.btn-xs:before,
      .btn-toggle-heading.btn-lg.btn-xs:after {
          display: none;
      }
      .btn-toggle-heading.btn-sm {
          margin: 0 0.5rem;
          padding: 0;
          position: relative;
          border: none;
          height: 1.5rem;
          width: 3rem;
          border-radius: 1.5rem;
      }
      .btn-toggle-heading.btn-sm:focus,
      .btn-toggle-heading.btn-sm.focus,
      .btn-toggle-heading.btn-sm:focus.active,
      .btn-toggle-heading.btn-sm.focus.active {
          outline: none;
      }
      .btn-toggle-heading.btn-sm:before,
      .btn-toggle-heading.btn-sm:after {
          line-height: 1.5rem;
          width: 0.5rem;
          text-align: center;
          font-weight: 600;
          font-size: 0.55rem;
          text-transform: uppercase;
          letter-spacing: 2px;
          position: absolute;
          bottom: 0;
          transition: opacity 0.25s;
      }
      .btn-toggle-heading.btn-sm:before {
          content: 'NO';
          left: -0.5rem;
      }
      .btn-toggle-heading.btn-sm:after {
          content: 'SI';
          right: -0.5rem;
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-sm>.handle-heading {
          position: absolute;
          top: 0.3125rem;
          left: 0.1875rem;
          width: 1.125rem;
          height: 1.125rem;
          border-radius: 1.125rem;
          background: #fff;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-sm.active {
          transition: background-color 0.25s;
      }
      .btn-toggle-heading.btn-sm.active>.handle-heading {
          left: 1.6875rem;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-sm.active:before {
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-sm.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-sm.btn-sm:before,
      .btn-toggle-heading.btn-sm.btn-sm:after {
          line-height: -0.5rem;
          color: #fff;
          letter-spacing: 0.75px;
          left: 0.4125rem;
          width: 2.325rem;
      }
      .btn-toggle-heading.btn-sm.btn-sm:before {
          text-align: right;
      }
      .btn-toggle-heading.btn-sm.btn-sm:after {
          text-align: left;
          opacity: 0;
      }
      .btn-toggle-heading.btn-sm.btn-sm.active:before {
          opacity: 0;
      }
      .btn-toggle-heading.btn-sm.btn-sm.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-sm.btn-xs:before,
      .btn-toggle-heading.btn-sm.btn-xs:after {
          display: none;
      }
      .btn-toggle-heading.btn-xs {
          margin: 0 0;
          padding: 0;
          position: relative;
          border: none;
          height: 1rem;
          width: 2rem;
          border-radius: 1rem;
      }
      .btn-toggle-heading.btn-xs:focus,
      .btn-toggle-heading.btn-xs.focus,
      .btn-toggle-heading.btn-xs:focus.active,
      .btn-toggle-heading.btn-xs.focus.active {
          outline: none;
      }
      .btn-toggle-heading.btn-xs:before,
      .btn-toggle-heading.btn-xs:after {
          line-height: 1rem;
          width: 0;
          text-align: center;
          font-weight: 600;
          font-size: 0.75rem;
          text-transform: uppercase;
          letter-spacing: 2px;
          position: absolute;
          bottom: 0;
          transition: opacity 0.25s;
      }
      .btn-toggle-heading.btn-xs:before {
          content: 'NO';
          left: 0;
      }
      .btn-toggle-heading.btn-xs:after {
          content: 'SI';
          right: 0;
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-xs>.handle-heading {
          position: absolute;
          top: 0.3125remrem;
          left: 0.125rem;
          width: 0.75rem;
          height: 0.75rem;
          border-radius: 0.75rem;
          background: #fff;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-xs.active {
          transition: background-color 0.25s;
      }
      .btn-toggle-heading.btn-xs.active>.handle-heading {
          left: 1.125rem;
          transition: left 0.25s;
      }
      .btn-toggle-heading.btn-xs.active:before {
          opacity: 0.5;
      }
      .btn-toggle-heading.btn-xs.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-xs.btn-sm:before,
      .btn-toggle-heading.btn-xs.btn-sm:after {
          line-height: -1rem;
          color: #fff;
          letter-spacing: 0.75px;
          left: 0.275rem;
          width: 1.55rem;
      }
      .btn-toggle-heading.btn-xs.btn-sm:before {
          text-align: right;
      }
      .btn-toggle-heading.btn-xs.btn-sm:after {
          text-align: left;
          opacity: 0;
      }
      .btn-toggle-heading.btn-xs.btn-sm.active:before {
          opacity: 0;
      }
      .btn-toggle-heading.btn-xs.btn-sm.active:after {
          opacity: 1;
      }
      .btn-toggle-heading.btn-xs.btn-xs:before,
      .btn-toggle-heading.btn-xs.btn-xs:after {
          display: none;
      }
      .btn-toggle-heading.btn-secondary {
          color: #6b7381;
          background: #bdc1c8;
      }
      .btn-toggle-heading.btn-secondary:before,
      .btn-toggle-heading.btn-secondary:after {
          color: #6b7381;
      }
      .btn-toggle-heading.btn-secondary.active {
          background-color: #ff8300;
      }
      .size-titulo {
          font-size: 1.2rem;
          font-weight: 700;
          text-align: center;
      }
      .panel-body .analisis-clinico{
          overflow-x: scroll;
      }

      .swal2-header-popstate {
          margin-top: 1rem;
      }

      /** pRODUCTOS mÉDICO RESPONSIVE **/
      @media (max-width: 950px) {
        .table-responsive table {
            border: 0; /* Quita bordes de la tabla */
        }

        .table-responsive thead {
            display: none; /* Oculta el encabezado */
        }

        .table-responsive tbody tr {
            display: block; /* Convierte las filas en bloques */
            margin-bottom: 1rem; /* Espacio entre filas */
            border: 1px solid #ddd; /* Agrega borde a cada bloque */
            border-radius: 5px; /* Bordes redondeados */
            padding: 10px;
        }

        .table-responsive tbody td {
            display: flex;
            justify-content: space-between; /* Etiqueta y valor alineados */
            padding: 5px 0; /* Espaciado interno */
            border: 0; /* Sin bordes entre celdas */
        }

        .table-responsive tbody td::before {
            content: attr(data-label); /* Usa el atributo personalizado data-label */
            font-weight: bold; /* Resalta la etiqueta */
            width: 50%; /* Asegura espacio para la etiqueta */
            text-align: left;
        }

        .productos-responsive {
            width: 100% !important;
        }
      }
  </style>
  <style>
    .spinning {
      font-size: 18px;
      animation: spin 1s linear infinite;
      -webkit-animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @-webkit-keyframes spin {
      0%   { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }
  </style>
  <?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
  ?>

  <div class="panel panel-default filterable">
    <input type="hidden" value="{{ $prestacionFirmada }}" name="prestacionFirmada" id="prestacionFirmada" />
      <div class="panel-heading">
          <ul class="nav nav-tabs">
              <li class="active"><a href="#tabNuevoAnestesia" data-toggle="tab" style="font-size:10px">NUEVO ATENCIÓN</a></li>
              <li><a href="#tabInformeAnestesia" data-toggle="tab" style="font-size:10px">INFORME DE ANESTESIA</a></li>
              <li><a href="#tabUrpa" data-toggle="tab" style="font-size:10px">URPA</a></li>
          </ul>
      </div>
      <div style=" font-size: 11px" class="panel-body">
          <div class="tab-content">
              <div class="tab-pane fade in active" id="tabNuevoAnestesia">
                  <div class="panel panel-default filterable">
                      <div class="panel-heading">
                          <ul class="nav nav-tabs" id="tabs">
                              <li class="active"><a href="#tabRegistroImagenes" data-toggle="tab" style="font-size:10px">NUEVO ATENCIÓN</a></li>
                              <li><a href="#tab4default" data-toggle="tab" style="font-size:10px">HISTORIAL DE ATENCIONES</a></li>
                              <li><a href="#tab5default" data-toggle="tab" style="font-size:10px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
                              <li><a href="#tab6default" data-toggle="tab" style="font-size:10px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
                              <li><a href="#tab1007default" data-toggle="tab" style="font-size:10px">HISTORIAL DE INTERCONSULTA</a></li>
                          </ul>
                      </div>
                      <div style=" font-size: 11px" class="panel-body">
                          <div class="tab-content">
                              <div class="tab-pane fade in active" id="tabRegistroImagenes">
                                <form name="frmCqAnestesia" id="frmCqAnestesia" class="form-horizontal" role="form" method="post" novalidate  action="{{ route('cqanestesia.storeOrUpdate') }}">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                  <input type="hidden" value="{{ !empty($id_prestacion)?$id_prestacion:'' }}" name="id_prestacion" id="id_prestacion" />
                                  <input type="hidden" value="{{ !empty($prestacion->id_establecimiento)?$prestacion->id_establecimiento:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->id_establecimiento:'') }}" name="id_establecimiento" id="id_establecimiento" />
                                  <input type="hidden" value="{{ $id_servicio }}" name="id_servicio" id="id_servicio" />
                                  <input type="hidden" value="1" name="id_tipo_atencion" id="id_tipo_atencion" />
                                  <input type="hidden" value="{{ $nivel }}" name="id_nivel" id="id_nivel" />

                                  <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{ !empty($prestacion->id_asegurado)?$prestacion->id_asegurado:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->id_asegurado:'') }}" />
                                  <input type="hidden" name="id_cita" id="id_cita" value="0" />
                                  <input type="hidden" name="id_medico" id="id_medico" value="{{ !empty($prestacion->id_medico)?$prestacion->id_medico:(!empty($medico)?$medico->id:'') }}" />
                                  <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user" />
                                  <input type="hidden" value="{{ !empty($prestacion->grado)?$prestacion->grado:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->grado:'')}}" type="hidden" name="grado" id="grado" />
                                  <input type="hidden" value="{{ !empty($prestacion->fecha_nac)?$prestacion->fecha_nac:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->fecha_nac:'') }}" name="fecnacafiliado" id="fecnacafiliado" />
                                  <input type="hidden" value="{{ isset($prestacion->cip_asegurado) ? $prestacion->cip_asegurado : (!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->cip:'') }}" name="cip_beneficiario" id="cip_beneficiario"/>
                                  <input type="hidden" value="{{ isset($prestacion->tipodoc_titular) ? $prestacion->tipodoc_titular : (!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->tipo_doc_titular:'') }}" name="tipodoc_titular" id="tipodoc_titular" />
                                  <input type="hidden" value="{{ !empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->dni_titular:'' }}" name="nrodoctitular" id="nrodoctitular" />
                                  <input type="hidden" value="{{ !empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nombre_titular:'' }}" name="nombre_titular" id="nombre_titular" />
                                  <input type="hidden" value="{{ !empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->paterno_titular:'' }}" name="paterno_titular" id="paterno_titular" />
                                  <input type="hidden" value="{{ !empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->materno_titular:'' }}" name="materno_titular" id="materno_titular" />
                                  <input type="hidden" value="{{ isset($prestacion->domicilio) ? $prestacion->domicilio : (!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->domicilio:'') }}" name="domicilio" id="domicilio" />

                                  <input type="hidden" value="{{ !empty($prestacion->telefono)?$prestacion->telefono:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->telefono:'') }} {{ !empty($prestacion->telefono2)?$prestacion->telefono2:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->telefono2:'') }}" name="telefono" id="telefono" />
                                  <input type="hidden" value="1" name="id_modalidad" id="id_modalidad" />
                                  <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="6">
                                  <input type="hidden" id="ipress_bene" name="ipress_bene" value="NUEVO">
                                  <input type="hidden" id="upss_bene" name="upss_bene" value="NUEVO">
                                  <input type="hidden" value="{{ $id_prestacion_anestesia }}" name="id_prestacion_anestesia" id="id_prestacion_anestesia" />
                                  <input type="hidden" value="{{ $id_anestesia_quirurgica }}" name="id_anestesia_quirurgica" id="id_anestesia_quirurgica" />
                                  <input type="hidden" value="{{ $flag_anestesia_quirurgica }}" name="flag_anestesia_quirurgica" id="flag_anestesia_quirurgica" />
                                  <input type="hidden" value="{{ $flag_observacion }}" name="flag_observacion" id="flag_observacion" />

                                  <input type="hidden" value="{{ $id_intervencion }}" name="id_intervencion" id="id_intervencion" />
                                  <input type="hidden" value="{{ !empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_doc_ident:'') }}" name="dni_beneficiario_h" id="dni_beneficiario_h" />
                                  <input type="hidden" name="id_farmacia" id="id_farmacia" value="18"/>


                                  <input type="hidden"  name="tiene_intervencion" id="tiene_intervencion" value="{{ isset($row_anestesia_quirurgica->tiene_intervencion) && $row_anestesia_quirurgica->tiene_intervencion ? 1 : 0 }}" />
                                  <input type="hidden"  name="tiene_observacion" id="tiene_observacion" value="{{ isset($row_anestesia_quirurgica->tiene_observacion) && $row_anestesia_quirurgica->tiene_observacion ? 1 : 0 }}" />
                                  <input type="hidden" id="firma_receta_adicional_anestesio" name="firma_receta_adicional_anestesio" value="0">
                                  <!-- FIRMA RECETA -->
                                  <input type="hidden" id="id_resol_receta_anestesio">
                                  <input type="hidden" id="argumentos_receta_anestesio"/>
                                  <input type="hidden" id="doc_firmar_receta_anestesio" />
                                  <input type="hidden" id="flag_firma_receta_anestesio" value="0">
                                  <!-- FIN FIRMA RECETA -->
                                  <input type="hidden"  id="flag_firma_centro_quirurgico" value="4">
                                  <div class="panel panel-info contenedor-datos-generales">
                                      <div class="panel-heading">
                                          <h3 class="panel-title size-titulo">
                                              DATOS GENERALES
                                          </h3>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-lg-3">
                                                  <label>TIPO ATENCION</label>
                                                  <?php
                                                  switch ($id_tipo_atencion) {
                                                  case '1':$tipo_atencion = "AMBULATORIO"; break;
                                                  case '2':$tipo_atencion = "EMERGENCIA"; break;
                                                  case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                                                  case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                                                  case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                                                  }
                                              ?>
                                                  <input type="text" readonly="" value="{{ $tipo_atencion }}" name="tipo_atencion" id="tipo_atencion" class="form-control" />
                                              </div>
                                              @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 1 || !empty($intervencion) && $intervencion->es_nuevo_admision_hospitalizacion)
                                                  <div class="col-lg-3">
                                                      <label class="control-label">CONSULTORIO DE PROCEDENCIA</label>
                                                      <input type="text" class="form-control" readonly=""  value="{{ $servicio->nombre }}" name="servicio_procedencia" id="servicio_procedencia">
                                                  </div>
                                                  <div class="col-lg-3">
                                                      <label>FECHA DE ORDEN</label>
                                                      <input type="text" class="form-control" readonly="" value="<?php echo date('d-m-Y', strtotime(!empty($hospitalizacion) && !empty($hospitalizacion->fecha_ingreso)? $hospitalizacion->fecha_ingreso: (!empty($datos_servicio) && !empty($datos_servicio->fecha_derivacion)? $datos_servicio->fecha_derivacion: ''))); ?>"  name="fecha_orden" id="fecha_orden" style="text-transform:uppercase;">
                                                  </div>
                                              @endif
                                              @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 2)
                                                  @if(isset($prestacion->prioridades))
                                                  <div class="col-md-3">
                                                      <label>PRIORIDAD</label>
                                                      <input class="form-control" readonly="" value="<?php echo $prestacion->prioridades?>"  name="medico" id="medico" class="form-control" style="text-transform:uppercase;">
                                                  </div>
                                                  @endif
                                                  <div class="col-md-3">
                                                      <label>SERVICIO</label>
                                                      <input class="form-control" readonly="" value=" {{ (!empty($servicio) ? $servicio->nombre : '-' ) }}"  name="medico" id="medico" style="text-transform:uppercase;">
                                                  </div>
                                              @endif
                                              @if ($intervencion->es_traslado_hospitalizacion_acqx)
                                                <div class="col-md-3">
                                                <label>SERVICIO</label>
                                                <input class="form-control" readonly="" value="<?php echo $servicio->nombre;?>"  name="medico" id="medico" style="text-transform:uppercase;">
                                                </div>
                                              @endif
                                              <!-- <div class="col-lg-3">
                                                  <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                                  @if(isset($row_anestesia_quirurgica->id))
                                                    <input type="hidden" class="form-control"  id="cod_siteds_existe"  value="true"  maxlength="12">
                                                    <input type="text" class="form-control"  value="{{$row_anestesia_quirurgica->cod_siteds_susalud}}" disabled>
                                                  @else
                                                    <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud" value="{{$codigo_siteds}}"  maxlength="12">
                                                  @endif
                                              </div> -->
                                          </div>
                                          <div class="row">
                                             <div class="col-lg-3">
                                                  <label for="tipo"><b>FECHA DE ATENCI&Oacute;N</b></label>
                                                  @if ($prestacionFirmada == true)
                                                      <input tabindex="6" type="datetime-local" class="form-control" value="{{ !empty($intervencion->fecha_probable_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_probable_intervencion)->format('Y-m-d\TH:i') : (!empty($prestacion->fecha_hora_atencion) ? \Carbon\Carbon::parse($prestacion->fecha_hora_atencion)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                                                      name="fecha_hora_atencion" id="fecha_hora_atencion" min="{{ !empty($intervencion->fecha_probable_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_probable_intervencion)->format('Y-m-d\TH:i') : (!empty($prestacion->fecha_hora_atencion) ? \Carbon\Carbon::parse($prestacion->fecha_hora_atencion)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" max="{{ date('Y-m-d\TH:i') }}" style="text-align:left;">
                                                  @else
                                                      <input tabindex="6" type="datetime-local" class="form-control" value="{{ !empty($intervencion->fecha_probable_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_probable_intervencion)->format('Y-m-d\TH:i') : (!empty($prestacion->fecha_hora_atencion) ? \Carbon\Carbon::parse($prestacion->fecha_hora_atencion)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                                                      name="fecha_hora_atencion" id="fecha_hora_atencion" min="{{ !empty($intervencion->fecha_probable_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_probable_intervencion)->format('Y-m-d\TH:i') : (!empty($prestacion->fecha_hora_atencion) ? \Carbon\Carbon::parse($prestacion->fecha_hora_atencion)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" max="{{ date('Y-m-d\TH:i') }}" style="text-align:left;">
                                                  @endif
                                              </div>
                                              @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 2)
                                                @if(isset($prestacion->nmro_cama))
                                                  <div class="col-md-3">
                                                      <label>CAMA</label>
                                                      <input class="form-control" readonly=""  value="<?php echo !empty($prestacion->nmro_cama)?$prestacion->nmro_cama:''?>"  name="medico" id="medico">
                                                  </div>
                                                @endif
                                                <div class="col-lg-3">
                                                    <label>FECHA DE ORDEN</label>
                                                    <input type="text" class="form-control" readonly="" value="<?php echo date('d-m-Y', strtotime(!empty($hospitalizacion->fecha_ingreso)?$hospitalizacion->fecha_ingreso:  (!empty($datos_servicio) && !empty($datos_servicio->fecha_derivacion)? $datos_servicio->fecha_derivacion: '')   )); ?>"  name="fecha_orden" id="fecha_orden" style="text-transform:uppercase;">
                                                </div>
                                              @endif
                                              <div class="col-lg-6">
                                                  <label>PROCEDENCIA</label>
                                                  <input type="text" class="form-control" readonly=""  value="{{ !empty($prestacion->establecimiento)?$prestacion->establecimiento: (!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->establecimiento:'') }}" name="procedencia" id="procedencia">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="panel panel-info contenedor-especialista">
                                      <div class="panel-heading">
                                          <h3 class="panel-title size-titulo">ESPECIALISTA</h3>
                                      </div>
                                      <div class="panel-body">
                                          <div class="col-lg-3">
                                              <label for="nro_dni_paciente">NRO DOC</label>
                                              <input class="form-control" readonly="" value="<?php echo $especialista->dni?>"  onKeyPress="return soloNumeros(event)" type="text" name="nro_dni_medico" id="nro_dni_medico" style="text-transform:uppercase;">
                                          </div>
                                          <div class="col-lg-5">
                                              <label>NOMBRE Y APELLIDOS</label>
                                              <input type="text" readonly="" value="<?php echo $especialista->medico_paterno." ".$especialista->medico_materno.", ".$especialista->medico_nombre ?>" name="medico" id="medico" class="form-control">
                                          </div>
                                          <div class="col-lg-4">
                                              <label>PROFESION</label>
                                              <input type="text" readonly="" value="<?php echo $especialista->profesion ?>" name="consultorio" id="consultorio" class="form-control">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="panel panel-info contenedor-paciente">
                                      <div class="panel-heading">
                                          <h3 class="panel-title size-titulo">PACIENTE</h3>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row" style="padding-bottom: 1rem;">
                                              <?php
                                              use Carbon\Carbon;
                                              $fecha = explode("/", !empty($prestacion->fecha_nac)?$prestacion->fecha_nac:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->fecha_nac:''));
                                              $Born = Carbon::create($fecha[2],$fecha[1],$fecha[0]);
                                              $age = $Born->diff(Carbon::now())->format('%y Años, %m meses %d dias');
                                              ?>
                                              <div class="col-lg-3">
                                                  <label for="nro_dni_paciente">NRO DOC.</label>
                                                  <input type="text" readonly="" value="<?php echo !empty($prestacion->nro_doc_ident)?$prestacion->nro_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_doc_ident:'') ?>" name="dni_beneficiario" id="dni_beneficiario" class="form-control"/>
                                                  <input type="hidden" value="{{ !empty($prestacion->tipo_doc_ident)?$prestacion->tipo_doc_ident:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->tipo_doc_ident:'') }}" name="tipo_doc_ident" id="tipo_doc_ident" />
                                              </div>
                                              <div class="col-lg-3">
                                                  <label>APELLIDOS Y NOMBRES</label>
                                                  <input readonly="" type="text" class="form-control" value="{{!empty($prestacion)?$prestacion->asegurado_paterno:$row_anestesia_quirurgica->asegurado_paterno}} {{!empty($prestacion)?$prestacion->asegurado_materno:$row_anestesia_quirurgica->asegurado_materno}}, {{!empty($prestacion)?$prestacion->asegurado_nombre:$row_anestesia_quirurgica->asegurado_nombre}}" name="beneficiario" id="beneficiario">
                                                  <input type="hidden" value="{{!empty($prestacion)?$prestacion->asegurado_paterno:$row_anestesia_quirurgica->asegurado_paterno}}" name="paterno_beneficiario" id="paterno_beneficiario">
                                                  <input type="hidden" value="{{!empty($prestacion)?$prestacion->asegurado_materno:$row_anestesia_quirurgica->asegurado_materno}}" name="materno_beneficiario" id="materno_beneficiario">
                                                  <input type="hidden" value="{{!empty($prestacion)?$prestacion->asegurado_nombre:$row_anestesia_quirurgica->asegurado_nombre}}" name="nombre_beneficiario" id="nombre_beneficiario">
                                              </div>
                                               @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 1)
                                                  <div class="col-lg-2">
                                                      <label>TIPO BENEFICIARIO</label>
                                                      <input readonly="" type="text" class="form-control" value="{{!empty($prestacion->parentesco)?$prestacion->parentesco:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->tipo_parentesco:'')}}" id="tipo_beneficiario" name="tipo_beneficiario" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                  </div>
                                              @endif
                                               @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 2)
                                                  @if(isset($prestacion->descripcion))
                                                  <div class="col-lg-2">
                                                      <label>TIPO BENEFICIARIO</label>
                                                      <input readonly="" type="text" class="form-control" value="{{!empty($prestacion->descripcion)?$prestacion->descripcion:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->tipo_parentesco:'')}}" id="tipo_beneficiario" name="tipo_beneficiario" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                  </div>
                                                  @endif
                                              @endif

                                              <div class="col-lg-2">
                                                  <label>NRO HC</label>
                                                  <input readonly="" type="text" class="form-control" value="<?php echo !empty($prestacion->nro_historia)?$prestacion->nro_historia:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->nro_historia:''); ?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                              </div>
                                              <div class="col-lg-1">
                                                  <label>SEXO</label>
                                                  <input readonly=""type="text" class="form-control" value="<?php echo !empty($prestacion->sexo)?$prestacion->sexo:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->sexo:''); ?>" id="nomsexo" name="nomsexo" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                              </div>
                                              <div class="col-lg-1">
                                                  <label>EDAD</label>
                                                  <input readonly="" type="text" class="form-control" value="<?php echo !empty($prestacion->edadpaciente)?$prestacion->edadpaciente:(!empty($row_anestesia_quirurgica)?$row_anestesia_quirurgica->edadafiliado:''); ?>" id="edad" name="edad" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                              </div>
                                          </div>
                                          <div class="row">
                                              @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 1)
                                                  <div class="col-md-2">
                                                      <label>REFERIDO</label>
                                                      <input type="text" readonly="" value="{{ $referencia }}" name="referido" id="referido" class="form-control">
                                                  </div>
                                              @endif
                                               @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 2)
                                                  <div class="col-md-2">
                                                      <label>REFERIDO</label>
                                                      <input type="text" readonly="" value="{{ $referencia == 4 ? 'SI' : 'NO' }}" name="referido" id="referido" class="form-control">
                                                  </div>
                                              @endif
                                          </div>
                                      </div>
                                  </div>
                                  @if ($referencia == 4 || $referencia == 'SI')
                                      <div class="panel panel-info contenedor-referencia" id="contenedorReferencia">
                                          <div class="panel-heading">
                                              <h3 class="panel-title size-titulo">INFORMACIÓN DE REFERENCIA</h3>
                                          </div>
                                          <div class="panel-body">
                                               @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 1)
                                                  @if ($referencia == "SI")
                                                      <div class="col-md-5" id="establecimientoReferencia">
                                                          <label>ESTABLECIMIENTO REFERENCIA</label>
                                                          <input type="text" readonly="" value="<?php echo !empty($prestacion->establecimiento_referencia)?$prestacion->establecimiento_referencia:'' ?>" name="establecimiento_referencia" id="establecimiento_referencia" class="form-control">
                                                      </div>
                                                      <div class="col-md-3" id="documentoReferencia">
                                                          <label>DOCUMENTO REFERENCIA</label>
                                                          <input type="text" readonly="" value="{{!empty($prestacion->numero_doc)?$prestacion->numero_doc:''}}" name="nro_doc_referencia" id="nro_doc_referencia" class="form-control">
                                                      </div>
                                                  @endif
                                              @endif
                                               @if (!empty($id_tipo_atencion) && $id_tipo_atencion == 2)
                                                  @if ($referencia == 4)
                                                      <div class="col-md-5" id="establecimientoReferencia">
                                                          <label>ESTABLECIMIENTO REFERENCIA</label>
                                                          <input type="text" readonly="" value="{{!empty($prestacion->esta_eva)?$prestacion->esta_eva:''}}" id="unidad_policia" name="unidad_policia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control">
                                                      </div>
                                                      <div class="col-md-3" id="documentoReferencia">
                                                          <label>DOCUMENTO REFERENCIA</label>
                                                          <input type="text" readonly="" value="{{!empty($prestacion->numero_oficio)?$prestacion->numero_oficio:''}}" name="consultorio" id="consultorio" class="form-control">
                                                      </div>
                                                  @endif
                                              @endif
                                          </div>
                                      </div>
                                  @endif

                                  {{-- DIAGNOSTICOS --}}
                                  @if ($flag_anestesia_quirurgica == true)
                                    {{-- VER DIAGNOSTICOS REGISTRADOS EN ATENCIÓN --}}
                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">DIAGNOSTICOS</h3>
                                        </div>
                                        <div class="panel-body">
                                            @if (isset($diagnosticocq) && !is_null($diagnosticocq))
                                              @if (!$prestacionFirmada)
                                              <div class="col-md-12" style="padding-bottom: 1rem;">
                                                <button type="button" id="addDiagnosticoAdicionalAnestesia" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">
                                                  Agregar Diagnostico
                                                </button>
                                              </div>
                                              @endif
                                              <div class="row">
                                                <table id="tblDiagnostico" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                        <tr>
                                                            <th>N°</th>
                                                            <th class="col-lg-1">CÓDIGO</th>
                                                            <th class="col-lg-10">DESCRIPCIÓN</th>
                                                            <th class="col-lg-2">TIPO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 1;
                                                            foreach($diagnosticocq as $row):
                                                        ?>
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <input type="hidden" class="id_diagcq" name="id_diagcq" id = "id_diagcq" value="{{$row->id_diagnostico}}" />
                                                            <td class="col-lg-1">{{$row->codigo}}</td>
                                                            <td class="col-lg-10">{{$row->nombre}}</td>
                                                            <td class="col-lg-2">{{$row->descripcion}}</td>
                                                        </tr>
                                                        <?php
                                                            $i++;
                                                            endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <table class="display table-condensed" id="tblDiagnosticoAdicionalAnestesia" cellspacing="0" width="100%" style="margin-top:5px;">
                                                  <thead>
                                                    <tr>
                                                      <th width="70%">Codigo - Nombre</th>
                                                      <th width="20%">Tipo</th>
                                                      <th width="10%">Eliminar</th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                              </div>
                                            @endif
                                        </div>
                                    </div>
                                  @else
                                    {{-- REGISTRAR DIAGNOSTICOS --}}
                                    @if ( is_null($diagnosticocq) )
                                      <fieldset> {{-- AGREGAR DIAGNOSTICOS --}}
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>DIAGNÓSTICOS</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                          <fieldset>
                                              <div class="panel panel-bordered panel-success">
                                                  <div class="panel-heading">PRE DIAGNOSTICOS</div>
                                                  <div class="panel-body">
                                                    <table class="table table-bordered"  cellspacing="0" width="100%">
                                                      <tr>
                                                        <td class="celda_plomo1" style="width: 1%;">N°</td>
                                                        <td class="celda_plomo1" style="width: 6%;">CIE - 10</td>
                                                        <td  class="celda_plomo1">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
                                                        <td class="celda_plomo1" colspan="3" style="width: 5%;"> TIPO DE DIAGNÓSTICO</td>
                                                      </tr>
                                                      @if(!empty($diagnosticos_pre))
                                                      @foreach($diagnosticos_pre as $key=> $dig)
                                                      <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$dig->codigo}}</td>
                                                        <td >{{$dig->nombre}}</td>
                                                        <td>
                                                          <div class="contenedor" style="display: flex; align-items: center; gap: 5px;">
                                                            <div class="valor"  style="font-size: 14px;"> P </div>
                                                            <div class="marca"><?php if($dig->descripcion=='PRESUNTIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                        <td>
                                                         <div class="contenedor" style="display: flex; align-items: center; gap: 5px;">
                                                          <div class="valor"  style="font-size: 12px;">D</div>
                                                          <div class="marca"><?php if($dig->descripcion=='DEFINITIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                        <td>
                                                         <div class="contenedor" style="display: flex; align-items: center; gap: 5px;">
                                                            <div class="valor"  style="font-size: 12px;">R</div>
                                                            <div class="marca"><?php if($dig->descripcion=='REPETITIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                      </tr>
                                                      @endforeach
                                                      @endif
                                                    </table>
                                                  </div>
                                              </div>
                                          </fieldset>

                                          <fieldset>
                                              <div class="panel panel-bordered panel-success">
                                                  <div class="panel-heading">POST DIAGNOSTICOS</div>
                                                  <div class="panel-body">
                                                    <table class="table table-bordered"  cellspacing="0" width="100%">
                                                      <tr>
                                                        <td class="celda_plomo1" style="width: 1%;">N°</td>
                                                        <td class="celda_plomo1" style="width: 6%;">CIE - 10</td>
                                                        <td  class="celda_plomo1">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
                                                        <td class="celda_plomo1" colspan="3" style="width: 5%;"> TIPO DE DIAGNÓSTICO</td>
                                                      </tr>
                                                      @if(!empty($diagnosticos_post))
                                                      @foreach($diagnosticos_post as $key=> $post)
                                                      <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$post->codigo}}</td>
                                                        <td >{{$post->nombre}}</td>
                                                        <td>
                                                          <div class="contenedor">
                                                            <div class="valor"  style="font-size: 14px;"> P </div>
                                                            <div class="marca"><?php if($post->descripcion=='PRESUNTIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                        <td>
                                                          <div class="contenedor">
                                                          <div class="valor"  style="font-size: 12px;">D</div>
                                                          <div class="marca"><?php if($post->descripcion=='DEFINITIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                        <td>
                                                          <div class="contenedor">
                                                            <div class="valor"  style="font-size: 12px;">R</div>
                                                            <div class="marca"><?php if($post->descripcion=='REPETITIVO'){echo 'X';}?></div>
                                                          </div>
                                                        </td>
                                                      </tr>
                                                      @endforeach
                                                      @endif
                                                    </table>
                                                  </div>
                                              </div>
                                          </fieldset>


                                            @if (!$prestacionFirmada)
                                            <table>
                                              <tr>
                                                <td>
                                                  <button type="button" id="addDiagnosticoAnestesia" class="btn btn-primary"
                                                    data-toggle="modal" data-target="#addClassModal">Agregar
                                                    Diagnostico</button>
                                                </td>
                                              </tr>
                                            </table>
                                            @endif
                                            <br />
                                            <div class="table-responsive">
                                              <table class="display" id="tblDiagnosticoAnestesia" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th style="text-align: center;" width="80%">Descripción</th>
                                                    <th style="text-align: center;" width="15%">Tipo</th>
                                                    <th style="text-align: center;" width="5%"></th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </fieldset>
                                    @endif
                                  @endif
                                  {{-- PROCEDIMIENTOS --}}
                                  @if ($flag_anestesia_quirurgica == true)
                                    {{-- VER DIAGNOSTICOS REGISTRADOS EN ATENCIÓN --}}
                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PROCEDIMIENTOS</h3>
                                        </div>
                                        <div class="panel-body">
                                          <fieldset>
                                              <div class="panel panel-bordered panel-success">
                                                  <div class="panel-heading">PROCEDIMIENTOS PRE/POST OPERATORIO</div>
                                                  <div class="panel-body">
                                                      <table class="table table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                          <tr>
                                                            <th width="15%">CODIGO</th>
                                                            <th width="50%">DESCRIPCION</th>
                                                            <th width="15%">TIPO</th>
                                                          </tr>
                                                        </thead>
                                                        <tfoot>
                                                        </tfoot>
                                                        <tbody>
                                                          @foreach ($procedimientos_intervencion as $pintervencion)
                                                          <tr>
                                                            <td>{{$pintervencion->codigo}}</td>
                                                            <td>{{$pintervencion->descripcion}}</td>
                                                            <td>{{ $pintervencion->flag_pre_post == 1 ? 'Pre Operatorio' : 'Post Operatorio' }}</td>
                                                          </tr>
                                                          @endforeach
                                                        </tbody>
                                                      </table>
                                                  </div>
                                              </div>
                                          </fieldset>
                                          @if (isset($procedimientocq) && !is_null($procedimientocq))
                                            @if (!$prestacionFirmada)
                                            <div class="col-md-12" style="padding-bottom: 1rem;">
                                              <button type="button" id="addProcedimientoAdicionalAnestesia" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">
                                                Agregar Procedimiento
                                              </button>
                                            </div>
                                            @endif
                                            <div class="row">
                                              <div  class="form-group" style="display: none">
                                                <select class="form-control" id="diagnosticosAdicionalTempAnestesia{{$id_intervencion}}"
                                                  style="width: 500px">
                                                  <option value="">Seleccionar Diagnostico</option>
                                                </select>
                                              </div>
                                                <table id="tblProcedimiento" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                        <tr>
                                                            <th>N°</th>
                                                            <th class="col-lg-1">CÓDIGO</th>
                                                            <th class="col-lg-10">DESCRIPCIÓN</th>
                                                            <th class="col-lg-2">DIAGNOSTICO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 1;
                                                            foreach($procedimientocq as $row):
                                                        ?>
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td class="col-lg-1">{{$row->codigo}}</td>
                                                            <td class="col-lg-10">{{$row->nombre}}</td>
                                                            <td class="col-lg-2"><?php $model_diagnosticos = new \App\Models\Producto_receta; $diagnostico_id_cq = $row->id_diagnostico; $diagnostico_nombre_cq = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id_cq); echo $diagnostico_nombre_cq; ?></td>
                                                        </tr>
                                                        <?php
                                                            $i++;
                                                            endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <table class="display table-condensed" id="tblProcedimientoAdicionalAnestesia" cellspacing="0" width="100%" style="margin-top:5px;">
                                                  <thead>
                                                    <tr>
                                                      <th width="45%">Procedimiento</th>
                                                      <th width="5%">Cantidad</th>
                                                      <th width="45%">Diagnósticos</th>
                                                      <th width="5%">Eliminar</th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                            </div>
                                          @endif
                                        </div>
                                    </div>
                                  @else
                                    {{-- REGISTRAR PROCEDIMIENTOS --}}
                                    @if ( is_null($procedimientocq) )
                                      <fieldset> {{-- AGREGAR PROCEDIMIENTO --}}
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>PROCEDIMIENTOS</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <fieldset>
                                                  <div class="panel panel-bordered panel-success">
                                                      <div class="panel-heading">PROCEDIMIENTOS PRE/POST OPERATORIO</div>
                                                      <div class="panel-body">
                                                          <table class="table table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                              <tr>
                                                                <th width="15%">CODIGO</th>
                                                                <th width="50%">DESCRIPCION</th>
                                                                <th width="15%">TIPO</th>
                                                              </tr>
                                                            </thead>
                                                            <tfoot>
                                                            </tfoot>
                                                            <tbody>
                                                              @foreach ($procedimientos_intervencion as $pintervencion)
                                                              <tr>
                                                                <td>{{$pintervencion->codigo}}</td>
                                                                <td>{{$pintervencion->descripcion}}</td>
                                                                <td>{{ $pintervencion->flag_pre_post == 1 ? 'Pre Operatorio' : 'Post Operatorio' }}</td>
                                                              </tr>
                                                              @endforeach
                                                            </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                            </fieldset>
                                            <table>
                                              <tr>
                                                <td>
                                                  <button type="button" id="addProcedimientoAnestesia" class="btn btn-primary"
                                                    data-toggle="modal" data-target="#addClassModal">Agregar
                                                    Procedimiento</button>
                                                </td>
                                              </tr>
                                            </table>
                                            <br />
                                            <div class="table-responsive">
                                              <table class="display" id="tblProcedimientoAnestesia" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="60%">Procedimiento</th>
                                                    <th width="10%">Cantidad</th>
                                                    <th width="25%">Diagnósticos</th>
                                                    <th width="5%"></th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </fieldset>
                                    @endif
                                  @endif
                                  {{-- MEDICAMENTOS --}}
                                  @if ($flag_anestesia_quirurgica == true)
                                    {{-- VER MEDICAMENTOS REGISTRADOS EN ATENCIÓN--}}
                                    <fieldset id="fsRecetaVale">
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>PRODUCTOS MEDICOS</b>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          @if (isset($recetacq) && !is_null($recetacq) && count($recetacq)>0)
                                            <div class="row">
                                              <?php foreach($recetacq as $rowReceta):
                                                if ($rowReceta->fecha_firma == "") {
                                                                $flag_firmar = 1;
                                                              }

                                                $model_productos_receta = new \App\Models\Producto_receta;

                                                $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale_cqanestesia($rowReceta->id);

                                              ?>
                                              <input type="hidden" id="firma_receta_existente_anestesio{{$rowReceta->id}}" name="firma_receta_existente_anestesio[{{$rowReceta->id}}]" value="0">
                                               <fieldset id="{{$rowReceta->id}}">
                                                <div class="panel-body">
                                                  <div class="form-group">
                                                    <div class="clearfix" style="margin-bottom:10px;">
                                                      <?php if($rowReceta->estado=="1"){?>
                                                        <div style="margin: 0px 2px 5px 2px">
                                                          <div class="alert alert-success" role="alert" style="padding-left: 5px; font-weight: 800"><span><i class="fa fa-check" aria-hidden="true"></i> LA RECETA HA SIDO DISPENSADA EL {{ \Carbon\Carbon::parse($rowReceta->updated_at)->format('d-m-Y') }}</span></div>
                                                        </div>
                                                        <button class="btn btn-danger " disabled="disabled" type="button"><i class="fa fa-times"></i> Invalidar Receta</button>
                                                       <?php }else{ ?>
                                                          
                                                        <div class="form-inline">
                                                          <a id="invalidarRecetaExistente{{ $rowReceta->id }}" href="javascript:void(0)"
                                                          onclick="invalidarReceta('invalidarRecetaExistente{{ $rowReceta->id }}','{{ $rowReceta->id }}','{{$rowReceta->id_farmacia}}')"
                                                          class="btn btn-danger">
                                                          <i class="fa fa-times" style="margin-left:10px;"></i> Invalidar Receta
                                                        </a>
                                                          <select id="tipo_anestesia_rv_existente{{ $rowReceta->id }}" class="form-control" data-id_receta="{{ $rowReceta->id }}">
                                                                <option value="">-- Tipo de Anestesia --</option>
                                                                  @foreach ($tipo_anestesia as $ta)
                                                                  <option value="{{$ta->id}}">{{$ta->descripcion}}</option>
                                                                  @endforeach
                                                            </select>
                                                        </div>
                                                      <?php } ?>

                                                      @if ($prestacionFirmada == false)
                                                        <?php if($rowReceta->estado=="2" && intval($rowReceta->flag) != 1 && intval($rowReceta->flag) != 2){?>
                                                          <a id="btnFirmarRecetaExistente" onclick="btnFirmarRecetaExistenteSinToken(<?php echo $rowReceta->id;?>,<?php echo $rowReceta->nro_receta; ?>)" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                        <?php }else{ ?>
                                                                @if (intval($rowReceta->flag) == 1 || intval($rowReceta->flag) == 2)
                                                                <a href="/{{$rowReceta->flag}}/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado"
                                                                class="btn btn-xs"
                                                                style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                                </a>
                                                                @endif
                                                        <?php } ?>
                                                      @else
                                                          @if (intval($rowReceta->flag) == 1 || intval($rowReceta->flag) == 2)
                                                            <a href="/{{$rowReceta->flag}}/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado"
                                                            class="btn btn-xs"
                                                            style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                            </a>
                                                          @endif
                                                      @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                      <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                                                      <div class="col-md-1">
                                                        <p><?php echo $rowReceta->nro_receta; ?></p>
                                                        <input type="hidden" value="{{$rowReceta->id}}" name="id_receta_vale_editar" id="id_receta_vale_editar" />
                                                      </div>
                                                      <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                      <div class="col-md-2">
                                                        <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?></p>
                                                      </div>
                                                      <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                      <div class="col-md-4" style="padding:0px">
                                                        <p><?php echo $rowReceta->farmacia; ?></p>
                                                      </div>
                                                    </div>

                                                    @if ($prestacionFirmada == false && $rowReceta->flag==0)
                                                      <div class="col-md-12">
                                                        <button tabindex="75" type="button"  onclick="agregarProductoRecetaExistenteAnestesio(<?php echo $rowReceta->id?>)"  class="btn btn-primary" data-toggle="modal" data-target="#addClassModal" @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top"
                                                      title="Solo el médico está autorizado para emitir recetas si cuenta con el servicio de atención de ANESTESIOLOGÍA."@endif>
                                                          Agregar Producto
                                                        </button>
                                                      </div>
                                                    @endif
                                                  </div>
                                                  <div>
                                                  <table class="table table-bordered" id="tblMedicamentosAnestesia_{{$rowReceta->id}}" cellspacing="0" width="100%">
                                                    <thead>
                                                      <tr>
                                                        <!-- <th width="10%">Codigo</th> -->
                                                        <th width="30%">Producto farmacético o dispositivo médico</th>
                                                        <!-- <th width="10%">Unidad Medida</th> -->
                                                        <th width="10%">Stock Farmacia</th>
                                                        <th width="7%">Tiempo(Dias)</th>
                                                        <th width="10%">Posologia</th>
                                                        <th width="10%">Via Administración</th>
                                                        <th width="8%">Cantidad Prescrita</th>
                                                        <th width="5%">diagnosticos</th>
                                                        @if ($prestacionFirmada == false && $rowReceta->flag==0 && $puede_atennder_servicios_cq)
                                                          <th width="5%">Editar</th>
                                                          <th width="5%">Eliminar</th>
                                                          <th width="5%">Cancelar</th>
                                                        @endif
                                                      </tr>
                                                      <tr>
                                                      </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody>
                                                      @if (isset($medicamentos))
                                                      <?php foreach($medicamentos as $key=> $row): ?>
                                                        <tr class="medicamentos_registrados">
                                                          <td data-label="Producto farmacético o dispositivo médico"><?php echo $row->codigo.' - '.$row->nombre;?>
                                                          <input type="hidden" class="cmbMedicamentoAdicionalAnestesia<?php echo $rowReceta->id?>" value="{{ $row->id_producto }}"/>
                                                              <?php
                                                                if($row->codigo=='59075700'):
                                                                  echo "(".$row->cantidad_prescrita."L)";
                                                                endif;
                                                              ?>
                                                          </td>
                                                          <!-- <td data-label="Unidad Medida">
                                                            <?php //echo $row->abreviatura?>
                                                          </td> -->
                                                          <td class="campos_hidden_productos">
                                                            <input type="hidden" value="" id="accionRowProductoAnestesia" name="accionRowProductoAnestesia[]"/>
                                                            <input type="hidden" value=""  id="cantidad_prescrita_editar" name="cantidad_prescrita_editar[]"/>
                                                            <input type="hidden" value=""  id="posologia_editar" name="posologia_editar[]"/>
                                                            <input type="hidden" value=""  id="id_via_administracion" name="id_via_administracion[]"/>
                                                            <input type="hidden" value=""  id="tiempo_periodo_editar" name="tiempo_periodo_editar[]"/>
                                                            <input name="id_producto_receta_editar[]" type="hidden" value="{{ $row->id_producto_receta }}"/>
                                                          </td>
                                                          <td data-label="Tiempo(Dias)"><input name="tiempo_periodo_editar[]" type="text" value="<?php echo $row->tiempo_periodo?>" disabled class="form-control productos-responsive" style="height: 28px !important;font-weight: 800;" required/></td>
                                                          <td data-label="Posologia"><input name="posologia_editar[]" type="text" value="<?php echo $row->posologia?>" disabled class="form-control productos-responsive" style="height: 28px !important;font-weight: 800;" required/></td>
                                                          <td data-label="Via Administración">
                                                            <select name="id_via_administracion[]" class="form-control id_via_administracion_anestesia productos-responsive" style="margin:0 1px;width:150px" disabled>
                                                              <option value="0">--Seleccionar--</option>
                                                              @foreach ($via_administracion as $va)
                                                              <option @if ($row->id_via_administracion == $va->id) selected @endif value="{{ $va->id }}">{{ $va->descripcion }}</option>
                                                              @endforeach
                                                            </select>
                                                          </td>
                                                          <td data-label="Cantidad Prescrita"><input name="cantidad_prescrita_editar[]" type="text" value="<?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                                    echo $row->cantidad_prescrita/1000;
                                                                    else:
                                                                      echo $row->cantidad_prescrita;
                                                                    endif; ?>" class="form-control productos-responsive" disabled style="height: 28px !important;font-weight: 800;" required/></td>
                                                          <td data-label="diagnosticos"><?php
                                                              $model_productos_receta = new \App\Models\Producto_receta;
                                                              $diagnostico_id = $row->id_diagnosticos;
                                                              $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                              echo $diagnostico_nombre; ?>
                                                          </td>
                                                          @if ($prestacionFirmada == false && $rowReceta->flag==0 && $puede_atennder_servicios_cq)
                                                            <td data-label="Editar">
                                                              <button type="button"  class="btn btn-primary btn-xs EditarFilaProductosAnestesia" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                <i class="fa fa-edit"></i>
                                                              </button>
                                                            </td>
                                                            <td data-label="Eliminar">
                                                              <button type="button"  class="btn btn-danger AnularFilaProductosAnestesia btn-xs" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                <i class="fa fa-times"></i>
                                                              </button>
                                                            </td>
                                                            <td data-label="Cancelar Acción">
                                                              <button type="button" class="btn btn-warning btn-xs restaurarColoresMedicamentos" style="width: auto;">
                                                                <i class="fa fa-times"></i>
                                                              </button>
                                                            </td>
                                                          @endif
                                                        </tr>
                                                      <?php endforeach;?>
                                                    @endif

                                                    </tbody>
                                                  </table>
                                                  </div>
                                                </div>
                                              </fieldset>
                                              <?php endforeach; ?>
                                            </div>
                                            @if($prestacionFirmada == false)
                                              <div  class="form-group" style="display: none">
                                                <select class="form-control cmbMedicamentoTempAnestesia{{$rowReceta->id}}" id="cmbMedicamentoTempAnestesia"
                                                  style="width: 500px">
                                                  <option value="">Seleccionar Producto</option>
                                                </select>
                                                <select class="form-control cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia{{$rowReceta->id}}" id="cmbMedicamentoAdicionalTempAnestesiaTipoAnestesia"
                                                  style="width: 500px">
                                                  <option value="">Seleccionar Producto</option>
                                                </select>
                                              </div>
                                              <!-- <div  class="form-group" style="display: none">
                                                <select class="form-control" id="diagnosticosAdicionalTempAnestesia{{$id_intervencion}}"
                                                  style="width: 500px">
                                                  <option value="">Seleccionar Diagnostico</option>
                                                </select>
                                              </div> -->
                                              <input type="hidden" value="0" name="hidden_adicional_rowIndex" id="hidden_adicional_rowIndex">
                                              <div class="form-group" style="padding: 0 2rem;">
                                                <div>
                                                <table id="tblMedicamentosAdicionalAnestesia" class="display" cellspacing="0" width="100%">
                                                  <thead>
                                                      <tr>
                                                      <th>Producto farmacéutico o dispositivo médico</th>
                                                      <th style="width:10%">Stock Farmacia</th>
                                                      <th>Tiempo(Dias)</th>
                                                      <th> Via Adm. </th>
                                                      <th>Posologia</th>
                                                      <th> Cant. Pres.</th>
                                                      <th> DiagnosticoxProducto </th>
                                                      <th>  </th>
                                                      <th>  </th>
                                                      </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                                </div>
                                              </div>
                                            @endif
                                          @endif
                                          {{-- Agregar Nueva Receta Vale Inicio--}}
                                          @if (!$prestacionFirmada)
                                          <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">
                                            @if(!$puede_atennder_servicios_cq)
                                              <button class="btn btn-success" title="Solo el médico está autorizado para emitir recetas si cuenta con el servicio de atención de ANESTESIOLOGÍA." disabled>Agregar Receta</button>
                                            @else
                                              <!-- <a onclick="agregarRecetaAnestesio()" id="btnNuevoReceetaAnestesio" flag="0" class="btn btn-success">Agregar Receta</a> -->
                                               <div class="form-inline">
                                                  <a onclick="agregarRecetaAnestesio()"
                                                    id="btnNuevoReceetaAnestesio"
                                                    flag="0"
                                                    data-id-receta=""
                                                    class="btn btn-success">
                                                      Agregar Receta
                                                  </a>
                                                  <select id="tipo_anestesia_rv" class="form-control" style="display:none;">
                                                      <option value="">-- Tipo de Anestesia --</option>
                                                      @foreach ($tipo_anestesia as $ta)
                                                      <option value="{{$ta->id}}">{{$ta->descripcion}}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                            @endif
                                              <div id="btnFirmarRecetaViewAnestesio" style="display:none;">
                                                <a id="btnFirmarRecetaAdicionalAnestesio" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                              </div>
                                          </div>
                                          @endif
                                          <div id="fsNuevaRecetaValeAnestesio" style="display:none">
                                            <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-body">
                                                <div class="form-group">
                                                  <div class="col-md-2">
                                                    <label>FEC. EXPEDICIÓN (A)</label>
                                                    <input type="text" class="form-control datepicker"
                                                      value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion"
                                                      id="fecha_expedicion" tabindex="2" autocomplete="off" />
                                                  </div>
                                                  <div class="col-md-5">
                                                    <label>FARMACIA</label>
                                                    <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                                      style="width:100%" onchange="getProductoAnestesia()" disabled>
                                                      <option value="">-Seleccionar-</option>
                                                      <?php foreach($farmacias as $row):?>
                                                      <option value="<?php echo $row->id?>"
                                                      <?php if($row->id==18) echo "selected='selected'"?>>
                                                      <?php echo $row->nombre?></option>
                                                      <?php endforeach;?>
                                                    </select>
                                                  </div>
                                                </div>
                                                <br />
                                                <table>
                                                  <tr>
                                                    <td>
                                                      <button type="button" id="addProductoNuevoAnestesio" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#addClassModal" @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top"
                                                    title="El servicio no está en su lista de servicios de atención, por lo tanto no puede registrar una receta"@endif>Agregar Producto</button>
                                                    </td>
                                                  </tr>
                                                </table>
                                                <br />
                                                <div style="display: none">
                                                  <select class="form-control" id="cmbMedicamentoTempAnestesiaNuevo" style="width: 500px">
                                                    <option value="">Seleccionar Producto</option>
                                                  </select>
                                                  <select class="form-control" id="cmbMedicamentoTempAnestesiaNuevoTipoAnestesio" style="width: 500px">
                                                     <option value="">Seleccionar Producto</option>
                                                  </select>
                                                </div>
                                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">

                                                <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th width="30%">Producto farmacético o dispositivo médico</th>
                                                      <th width="10%">Stock Farmacia</th>
                                                      <th width="14%">Tiempo(Dias)</th>
                                                      <th width="16%">Posologia</th>
                                                      <th width="21%">Via Administración</th>
                                                      <th width="10%">Cantidad Prescrita</th>
                                                      <th width="15%">diagnosticos</th>
                                                      <th width="10%"></th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                  </tbody>
                                                </table>

                                              </div>
                                            </div>
                                          </div>
                                          {{-- Agregar Nueva Receta Vale Fin--}}
                                        </div>
                                      </div>
                                    <fieldset>
                                  @else
                                    {{-- REGISTRAR MEDICAMENTOS --}}
                                    @if ( is_null($recetacq) )
                                      <div class="panel panel-info panel-sm">
                                          <div class="panel-body">
                                              <div class="form-group">
                                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                      <label>PRESCRIBIR RECETA</label>
                                                  </div>
                                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                      <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top"
                                                    title="Solo el médico está autorizado para emitir recetas en este caso si el servicio de tencion es de ANESTESIOLOGÍA"@endif>
                                                      <div class="handle"></div>
                                                      </button>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <fieldset id="fsRecetaValeAnestesia" style="display:none">
                                          <div class="panel panel-info">
                                              <div class="panel-heading">
                                                  <h3 class="panel-title size-titulo">PRODUCTOS MEDICOS</h3>
                                              </div>

                                              <div class="panel-body">
                                                  <div class="form-group">
                                                      <div class="col-md-2">
                                                          <label>FEC. EXPEDICIÓN (A)</label>
                                                          <input tabindex="71" type="text" class="form-control datepicker"
                                                              value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                                              name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                                                              data-date-end-date="0d" autocomplete="off" />
                                                      </div>
                                                      <div class="col-md-5">
                                                          <label>FARMACIA</label>
                                                          <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                                              style="width:100%" onchange="getProductoAndAddProductoAnestesia()" disabled>
                                                              <option value="">-Seleccionar-</option>
                                                              <?php foreach($farmacias as $row):?>
                                                              <option value="<?php echo $row->id?>"
                                                              <?php if($row->id==18) echo "selected='selected'"?>>
                                                              <?php echo $row->nombre?></option>
                                                              <?php endforeach;?>
                                                          </select>
                                                      </div>
                                                  </div>
                                                  <div class="form-group">
                                                      <div class="col-sm-12">
                                                          <table>
                                                              <tr>
                                                              <td>
                                                                  <button tabindex="75" type="button" id="addProductoAnestesia" class="btn btn-primary"
                                                                  data-toggle="modal" data-target="#addClassModal" @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top"
                                                    title="Solo el médico está autorizado para emitir recetas en este caso si el servicio de tencion es de ANESTESIOLOGÍA"@endif>Agregar Producto</button>
                                                              </td>
                                                              </tr>
                                                          </table>
                                                      </div>
                                                  </div>
                                                  <br />
                                                  <div  class="form-group" style="display: none">
                                                      <select class="form-control" id="cmbMedicamentoTempAnestesia"
                                                      style="width: 500px">
                                                      <option value="">Seleccionar Producto</option>
                                                      </select>
                                                  </div>
                                                  <input type="hidden" value="0" name="hidden_anestesia_rowIndex" id="hidden_anestesia_rowIndex">
                                                  <div class="form-group">
                                                      <div>
                                                        <div id="spinner_tabla" class="text-center my-3">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="sr-only">Cargando...</span>
                                                            </div>
                                                        </div>
                                                      <table id="tblAnestesia" class="display" cellspacing="0" width="100%">
                                                      <thead>
                                                          <tr>
                                                          <th>Producto farmacéutico o dispositivo médico</th>
                                                          <!--th style="width:10%">Stock UE-020</th>
                                                          <th style="width:10%">Stock Establecimiento</th-->
                                                          <th style="width:10%">Stock Farmacia</th>
                                                          <th>Tiempo(Dias)</th>
                                                          <th> Via Adm. </th>
                                                          <th>Posologia</th>
                                                          <th> Cant. Pres.</th>
                                                          <th> DiagnosticoxProducto </th>
                                                          <th>  </th>
                                                          <th>  </th>
                                                          </tr>
                                                      </thead>
                                                      <tfoot>
                                                      </tfoot>
                                                      <tbody>
                                                      </tbody>
                                                      </table>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </fieldset>
                                    @endif
                                  @endif

                                  <div class="panel panel-info contenedor-paciente">
                                      <div class="panel-heading">
                                          <h3 class="panel-title size-titulo">PROGRAMACIÓN</h3>
                                      </div>
                                      @if ($prestacionFirmada == true)
                                        <div class="panel-body">
                                          <div class="row">
                                              <div class="col-lg-6">
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label>CONFIRMAR INTERVENCIÓN</label>
                                                  </div>
                                                  <div class="col-lg-3" style="margin-left:0px;padding-left:0px">
                                                      <label>SI</label>
                                                  </div>
                                                  <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <label>FECHA DE INTERVENCIÓN</label>
                                                    </div>
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label>{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->fecha_intervencion : "" }}</label>
                                                    </div>
                                                  </div>
                                              </div>
                                              <div class="col-lg-6">
                                                <!-- <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label style="margin-left: 2rem;">HORA DE INICIO</label>
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label style="margin-left: 2rem;">{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_inicio_intervencion : "" }}</label>
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label style="margin-left: 2rem;">HORA DE TÉRMINO</label>
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label>{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_fin_intervencion : "" }}</label>
                                                  </div>
                                                </div> -->
                                              </div>
                                          </div>
                                          <div class="row" id="contenedorAnestesiaProgramacion">
                                              <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                  <div class="row">
                                                      <div class="col-lg-4">
                                                          <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                              <label style="margin-left: 2rem;">SALA DE INTERVENCIÓN</label>
                                                          </div>
                                                          <div class="col-lg-6" style="padding-bottom: 1rem;">
                                                              <select id="id_sala" disabled name="id_sala" class="form-control" style="width: 100%">
                                                                <option value="0">-Seleccionar-</option>
                                                                @foreach ($salas as $sa)
                                                                <option value="{{ $sa->id }}"
                                                                  @if ($flag_anestesia_quirurgica == '1')
                                                                    @if ($row_anestesia_quirurgica->id_sala_intervencion == $sa->id)
                                                                      selected
                                                                    @endif
                                                                  @endif
                                                                >{{ $sa->nombre }}</option>
                                                                @endforeach
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-8">
                                                          <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                              <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
                                                          </div>
                                                          <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                              <select class="form-control js-example-basic-multiple" name="id_tipo_anestesia[]" id="id_tipo_anestesia" required="" style="width: 100%" multiple="multiple" disabled>
                                                                @foreach ($tipo_anestesia as $ta)
                                                                  <option value="{{$ta->id}}">{{$ta->descripcion}}</option>
                                                                  @endforeach
                                                              </select>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="row intro-contenedor">
                                                    <div class="col-lg-12">
                                                      <table class="table table-bordered" id="tblAnestesiologoAnestesia" cellspacing="0" width="100%" style="margin-top:5px;">
                                                        <thead>
                                                          <tr>
                                                            <th colspan="2" style="text-align: center;">Anestesiologos que Intervienen Operación</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          @if(!empty($anetesiologos))
                                                            @foreach($anetesiologos as $key => $row)
                                                              @if ($row->estado !== 3)
                                                                <tr>
                                                                  <td>
                                                                    {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}
                                                                  </td>
                                                                  <td>
                                                                    {{$row->descripcion}}
                                                                  </td>
                                                                </tr>
                                                              @endif
                                                            @endforeach
                                                          @endif
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                              </div>
                                              <fieldset>
                                                  <div class="panel panel-info panel-sm">
                                                      <div class="panel-body">
                                                         @if (!$prestacionFirmada)
                                                          <div class="row">
                                                              <div class="col-lg-5" style="display: flex; align-items: center;">
                                                                  <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                                      <label>OBSERVACIÓN DE INTERVENCIÓN QUIRÚRGICA</label>
                                                                  </div>
                                                                  <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                                      <button tabindex="70" type="button" id="addObservacionIntervencionQuirurgica" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                      <div class="handle"></div>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          @endif
                                                          <div class="row addObservacionIntervencionQuirurgica" style="margin-bottom: 3rem; display: none;">
                                                              <div class="row" style="padding: 0 3rem;" >
                                                                  <label>MOTIVO DE OBSERVACION PARA INTERVENCION QX</label>
                                                              </div>
                                                              <div class="row" style="padding: 0 3rem;" >
                                                                  @if (!$prestacionFirmada)
                                                                  <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->observacion : '' }}" type="text" data-toggle="tooltip" data-placement="top" name="observacion" id="observacion" class="input-sm" style="width:100%; text-align:left !important;" />
                                                                  @else
                                                                  <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->observacion : '' }}" type="text" data-toggle="tooltip" data-placement="top" class="input-sm" style="width:100%; text-align:left !important;" disabled />
                                                                  @endif
                                                                </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </fieldset>
                                          </div>
                                        </div>
                                      @else
                                        <div class="panel-body">
                                          <div class="row">
                                              <div class="col-lg-6">
                                                  <div class="row">
                                                    <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                        <label>CONFIRMAR INTERVENCIÓN</label>
                                                    </div>
                                                    <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                        <button tabindex="70" type="button" id="addConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                        <div class="handle"></div>
                                                        </button>
                                                    </div>
                                                  </div>
                                                  <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                    <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <label>FECHA DE INTERVENCIÓN </label>
                                                    </div>
                                                    <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <input class="form-control input-sm" type="date" name="fecha_intervencion" id="fecha_intervencion" placeholder="Fecha Intervención" value="{{ ($flag_anestesia_quirurgica == '1')
                                                          ? \Carbon\Carbon::parse($row_anestesia_quirurgica->fecha_intervencion)->format('Y-m-d')
                                                          : (!empty($row_anestesia_quirurgica->fecha_intervencion_validacion)
                                                                ? \Carbon\Carbon::parse($row_anestesia_quirurgica->fecha_intervencion_validacion)->format('Y-m-d')
                                                                : '') }}" $romaxlength="10">
                                                    </div>
                                                  </div>
                                              </div>
                                              <div class="col-lg-6">
                                                <div class="row">
                                                    <br><br>
                                                </div>
                                                 <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                  <div class="col-lg-6" style="padding-bottom:5px;margin-right:0px;padding-right:0px;">
                                                        <label>TIEMPO DE DURACIÓN</label>
                                                  </div>
                                                 @if ($prestacionFirmada == true)
                                                    <div class="col-lg-3" style="padding-bottom:5px;margin-right:0px;padding-right:0px;">
                                                      <p>
                                                        {{
                                                            !empty($row_anestesia_quirurgica) && !empty($row_anestesia_quirurgica->duracion_cirugia)
                                                                ? $row_anestesia_quirurgica->duracion_cirugia
                                                                : (!empty($row_anestesia_quirurgica) && isset($row_anestesia_quirurgica->emergencia_duracion_cirugia)
                                                                    ? rtrim(rtrim(number_format($row_anestesia_quirurgica->emergencia_duracion_cirugia, 2, '.', ''), '0'), '.') . ' horas'
                                                                    : ''
                                                                )
                                                        }}
                                                    </p>
                                                    </div>
                                                  @else
                                                      <div class="col-lg-3" style="padding-bottom:5px;margin-right:0px;padding-right:0px;">
                                                        <input type="text" name="duracion_cirugia" id="duracion_cirugia" class="form-control"
                                                        placeholder="Ej: 2.5 horas" style="width: 105px;"
                                                        oninput="filtrarNumeroDecimal(this)"
                                                        value="{{
                                                              !empty($row_anestesia_quirurgica) && $row_anestesia_quirurgica->duracion_cirugia
                                                                  ? $row_anestesia_quirurgica->duracion_cirugia
                                                                  : (isset($row_anestesia_quirurgica->emergencia_duracion_cirugia)
                                                                      ? rtrim(rtrim(number_format($row_anestesia_quirurgica->emergencia_duracion_cirugia, 0, '.', ''), '0'), '.')
                                                                      : ''
                                                                  )
                                                          }}">
                                                      </div>
                                                  @endif
                                                  </div>
                                                <!-- <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label style="margin-left: 2rem;">HORA DE INICIO</label>
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_inicio_intervencion : "" }}" type="time" class="form-control" style="width: 100%;" id="hora_inicio_intervencion" name="hora_inicio_intervencion" />
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <label style="margin-left: 2rem;">HORA DE TÉRMINO</label>
                                                  </div>
                                                  <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                      <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_fin_intervencion : "" }}" type="time" class="form-control" style="width: 100%;" id="hora_fin_intervencion" name="hora_fin_intervencion" />
                                                  </div>
                                                </div> -->
                                              </div>
                                          </div>
                                          <div class="row" id="contenedorAnestesiaProgramacion">
                                              <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                  <div class="row">
                                                      <div class="col-lg-6">
                                                          <div class="col-lg-6" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                              <label style="margin-left: 2rem;">SALA DE INTERVENCIÓN</label>
                                                          </div>
                                                          <div class="col-lg-6" style="padding-bottom: 1rem;">
                                                              <select id="id_sala" name="id_sala" class="form-control" style="width: 100%">
                                                                <option value="0">-Seleccionar-</option>
                                                                @foreach ($salas as $sa)
                                                                <option value="{{ $sa->id }}"
                                                                    {{ (
                                                                    !empty($row_anestesia_quirurgica) && !empty($row_anestesia_quirurgica->id_sala_intervencion_anestesio)
                                                                        ? $row_anestesia_quirurgica->id_sala_intervencion_anestesio
                                                                        : (!empty($row_anestesia_quirurgica) ? $row_anestesia_quirurgica->id_sala_intervencion : null)
                                                                    ) == $sa->id ? 'selected' : '' }}

                                                                >{{ $sa->nombre }}</option>
                                                                @endforeach
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                              <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
                                                          </div>
                                                          <div class="col-lg-8" style="padding-bottom: 1rem;">
                                                              <select class="form-control js-example-basic-multiple"
                                                                    name="id_tipo_anestesia[]" id="id_tipo_anestesia"
                                                                    required style="width: 100%" multiple="multiple">
                                                                @foreach ($tipo_anestesia as $ta)
                                                                    <option value="{{ $ta->id }}"
                                                                        {{ in_array($ta->id, $idsSeleccionados) ? 'selected' : '' }}>
                                                                        {{ $ta->descripcion }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                      </div>
                                                  </div>

                                                  <div class="row intro-contenedor">
                                                    <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                      <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addAnestesiologoAnestesia">Anestesiologos que Intervienen Operación</button>
                                                      <span class="invalid-feedback" style="display: none; color: red;">
                                                        {{ $errors->first('addAnestesiologoAnestesia') }}
                                                      </span>
                                                    </div>
                                                    <div class="col-lg-8" style="display: flex; justify-content: right; padding-right: 16rem;">
                                                      <div class="col-lg-6" style="padding-bottom: 1rem;">
                                                        <button type="button" class="btn btn-danger btn-xs" style="width: 100%;" id="restaurarColores"><i class="fa fa-times"></i> Filtro</button>
                                                      </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                      <table class="table table-bordered" id="tblAnestesiologoAnestesia" cellspacing="0" width="100%" style="margin-top:5px;">
                                                        <tbody>
                                                          @if(!empty($anetesiologos))
                                                            @foreach($anetesiologos as $key => $row)
                                                              @if ($row->estado !== 3)
                                                                <tr>
                                                                  <td>
                                                                    {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}
                                                                  </td>
                                                                  <td>
                                                                    {{$row->descripcion}}
                                                                  </td>
                                                                  <td>
                                                                    <button type="button"  class="btn btn-danger AnularFilaAnestesiologos btn-xs" data-id_anestesiologo_registro="{{ $row->id }}" data-toggle="modal" style="margin-left:4px">
                                                                      <i class="fa fa-times"></i> Eliminar
                                                                    </button>
                                                                  </td>
                                                                </tr>
                                                              @endif
                                                            @endforeach
                                                          @endif
                                                        </tbody>
                                                      </table>
                                                      <div id="hiddenInputsContainer"></div>
                                                    </div>
                                                  </div>
                                              </div>
                                              <fieldset>
                                                  <div class="panel panel-info panel-sm">
                                                      <div class="panel-body">
                                                          <div class="row">
                                                              <div class="col-lg-5" style="display: flex; align-items: center;">
                                                                  <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                                      <label>OBSERVACIÓN DE INTERVENCIÓN QUIRÚRGICA</label>
                                                                  </div>
                                                                  <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                                      <button tabindex="70" type="button" id="addObservacionIntervencionQuirurgica" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                      <div class="handle"></div>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="row addObservacionIntervencionQuirurgica" style="margin-bottom: 3rem; display: none;">
                                                              <div class="row" style="padding: 0 3rem;" >
                                                                  <label>MOTIVO DE OBSERVACION PARA INTERVENCION QX</label>
                                                              </div>
                                                              <div class="row" style="padding: 0 3rem;" >
                                                                  <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->observacion : "" }}" type="text" data-toggle="tooltip" data-placement="top" name="observacion" id="observacion" class="input-sm" style="width:100%; text-align:left !important;" />
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </fieldset>
                                          </div>
                                        </div>
                                      @endif
                                  </div>

                                  <div class="col-sm-12 col-md-12"> {{-- Buttons --}}
                                      <br/>
                                      <div class="form-group">
                                          <div class="col-md-5">
                                          </div>
                                          <div class="col-md-7">
                                              {{--<a class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>--}}
                                              @if ($prestacionFirmada)
                                                <div class="col-md-12">
                                                  <div class="col-md-6">
                                                    <a href="/{{$prestacion->flag_atencion_medica}}/{{$prestacion->ruta_prestacion_atencion_medica}}" target="_blank" data-toggle="tooltip" title="PDF Atención"
                                                      class="btn btn-xs input-sm"
                                                      style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">PDF ATENCIÓN
                                                      <i class="fa fa-pencil" aria-hidden="true"></i>
                                                      <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                  </div>
                                                  <!-- <div class="col-md-6">
                                                    @foreach ($recetacq as $rcq)
                                                      <a href="/{{$rcq->flag}}/{{$rcq->ruta_receta}}" target="_blank" data-toggle="tooltip" title="PDF Receta"
                                                        class="btn btn-xs input-sm"
                                                        style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">PDF RECETA
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                      </a>
                                                    @endforeach
                                                  </div> -->
                                                </div>
                                              @else
                                                @if ($id_prestacion_anestesia>0)
                                                  <div id="divFirmarAnestesio" style="display: none;">
                                                    <input name="mov[]" id="mov[]" value="<?php echo $id_prestacion_anestesia?>" type="hidden" />
                                                    <input type="hidden" value="1" name="proviene_formulario_hosp" id="proviene_formulario_hosp">
                                                    <input type="hidden" value="3" name="proviene_formulario" id="proviene_formulario">
                                                    <?php if(Auth::user()->status_token==1): ?>
                                                      <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                                                        onclick="firmarsintokensAnestesia()" style="margin-left:20px" />
                                                    <?php else: ?>
                                                      <?php if(Auth::user()->tipo_registro==3): ?>
                                                      <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                                                        onclick="firmarsintokensAnestesia()" style="margin-left:20px" />
                                                      <?php else: ?>
                                                      <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmarAnestesia"
                                                        style="margin-left:20px" />
                                                      <?php endif; ?>
                                                    <?php endif; ?>
                                                  </div>
                                                  <div id="divGuardar" style="display: none;">
                                                    <input class="btn btn-primary pull-right" style="margin-left: 5px;" value="Guardar datos" name="crea" type="button" form="frmCqAnestesia" id="btnPrestacionCqAnestesia" />
                                                    <input class="btn btn-danger pull-right" value="Salir" type="button" id="btnSalir1" />
                                                  </div>
                                                  <div id="divAskAction">
                                                    <input class="btn btn-primary pull-right"  style="margin-left: 5px;"value="Guardar datos" type="button" id="btnAskAction" />
                                                    <input class="btn btn-danger pull-right" value="Salir" type="button" id="btnSalir2" />
                                                  </div>
                                                @else
                                                  <input class="btn btn-primary pull-right" style="margin-left: 5px;" value="Guardar datos" name="crea" type="button" form="frmCqAnestesia" id="btnPrestacionCqAnestesia" />
                                                  <input class="btn btn-danger pull-right" value="Salir" type="button" id="btnSalir3" />
                                                @endif
                                              @endif
                                          </div>
                                      </div>
                                  </div>
                                </form>
                                <input type="hidden" id="flagAnestesiaFirma" value="" />
                                <input type="hidden" id="argumentos_anestesia" value="" />
                                <input type="hidden" id="doc_firmar_anestesia" value="" />
                                <input type="hidden" id="opt_firma" value="" />
                              </div>
                              <div class="tab-pane fade" id="tab4default">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div id='divHistorial'></div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="tab-pane fade" id="tab5default">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div id='divLaboratorio'></div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="tab-pane fade" id="tab6default">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div id='divRadiologico'></div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="tab-pane fade" id="tab1007default">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>HISTORIAL DE INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div id='divInterconsultaH'></div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="tab-pane fade" id="tabInformeAnestesia" style="font-size: 11px;">
                @include('frontend.centro_quirurgico.anestesiologia.components.form_registro_informe_anestesia')
              </div>
              {{-- <div class="tab-pane fade" id="tabInformeAnestesia" >
                  <div class="panel panel-default filterable">
                      <div class="panel-heading">
                          <h3 class="panel-title size-titulo">
                              INFORME DE ANESTESIA
                          </h3>
                      </div>
                      <div style=" font-size: 11px" class="panel-body">
                          <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data" method="POST">
                              <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                              <div class="nav-tabs-custom">

                                  <div class="tab-pane active" style="font-size: 11px;">
                                      <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                              <h3 class="panel-title size-titulo">
                                                  DATOS GENERALES
                                              </h3>
                                          </div>
                                          <div class="panel-body">
                                              <div class="d-flex">
                                                  <div class="col-md-8">
                                                      <div class="row">
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label for="nombre">FECHA:</label>
                                                                  <input type="text" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label>HORA</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label>EDAD</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label>PESO</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="row">
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label for="telefono">HB</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label for="direccion">HLO</label>
                                                                  <input type="number" class="form-control" >
                                                              </div>
                                                          </div>
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label>INR</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label>GRUPO FACTOR</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label>NNEUMOLOGO</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-2">
                                                              <div class="form-group">
                                                                  <label>CARDIOLOGICO</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="row">
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label for="ciudad">GLUCOSA:</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label for="pais">CREATININA:</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label>UREA:</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3">
                                                              <div class="form-group">
                                                                  <label>ASA:</label>
                                                                  <input type="number" class="form-control">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="row">
                                                          <div class="col-md-12">
                                                          <label>COMPLICACIONES PRE OPERATORIO</label>
                                                          <textarea name="" id="" cols="120" rows="2" class="form-control"></textarea>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-4 d-flex flex-column justify-content-between">
                                                      <div class="col-md-12">
                                                          <label>PREDITACIÓN</label>
                                                          <textarea name="" id="" cols="50" rows="13" class="form-control"></textarea>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading d-flex justify-content-between align-items-center">
                                              <h3 class="panel-title size-titulo" style="display: inline;">
                                                  INFORMACIÓN ANESTESIAR
                                              </h3>
                                              <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                  <div class="handle-heading"></div>
                                              </button>
                                          </div>
                                          <div class="panel-body">
                                              <div class="row">
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">INC ANEST :</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">INDUCCION :</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">TEM ANEST:</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">INC CIRUGIA :</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">INDUCCION :</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-4">
                                                      <div class="col-lg-6">
                                                          <label for="nombre">TEM CIRUGIA:</label>
                                                      </div>
                                                      <div class="col-lg-6">
                                                          <input type="text" class="form-control">
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading d-flex justify-content-between align-items-center">
                                              <h3 class="panel-title size-titulo" style="display: inline;">
                                                  METODO DE ANESTESIA
                                              </h3>
                                              <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                  <div class="handle-heading"></div>
                                              </button>
                                          </div>
                                          <div class="panel-body">
                                              <div style="display: flex; flex-direction: column; gap: 1rem; padding-bottom: 3rem;">
                                                  <div style="display: flex; gap: 0.5rem;">
                                                      <div class="col-sm-1">
                                                          <label>INC ANEST :</label>
                                                      </div>
                                                      <div class="col-sm-10">
                                                          <input type="checkbox" name="" id="" value="1" class="" checked/>
                                                      </div>
                                                  </div>
                                                  <div style="display: flex; gap: 0.5rem;">
                                                      <div class="col-sm-1">
                                                          <label>INC CIRUGIA :</label>
                                                      </div>
                                                      <div class="col-sm-10">
                                                          <input type="checkbox" name="" id="" value="1" class="" checked/>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row" style="border: 1px solid #d3d3d3; border-radius: 5px; margin-left: 0.2rem; margin-right: 0.2rem;">
                                          <div class="row">
                                              <div class="col-lg-4" style="display: flex; align-items: center;">
                                                  <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                      <label>REGISTRAR SIGNOS VITALES</label>
                                                  </div>
                                                  <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                      <button tabindex="70" type="button" id="saddConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                      <div class="handle"></div>
                                                      </button>
                                                  </div>
                                              </div>
                                              <div class="col-lg-8"></div>
                                          </div>
                                          <br />
                                          <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-heading">
                                                  <h3 class="panel-title size-titulo">
                                                      HISTORIAL DE ANESTESIA
                                                  </h3>
                                              </div>
                                              <div class="panel-body">
                                                  <div class="row">
                                                      <div class="col-lg-6"></div>
                                                      <div class="col-lg-6">
                                                          <div class="col-lg-6">
                                                              <a href="javascript:void(0)" aria-hidden="true" class="btn btn-info btn-sm"  onClick="verGrafica()" >
                                                                  <i class="fa fa-line-chart" aria-hidden="true">
                                                                  </i> Ver Graficas
                                                              </a>
                                                          </div>
                                                          <div class="col-lg-6">
                                                              <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="crearAnestesia()" >
                                                                  NUEVO ANESTESIA
                                                              </a>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-lg-12">
                                                          <table class="table">
                                                              <thead>
                                                                  <tr>
                                                                      <th class="text-center">N°</th>
                                                                      <th class="text-center">FECHA</th>
                                                                      <th class="text-center">HORA</th>
                                                                      <th class="text-center">SAT 02</th>
                                                                      <th class="text-center">P.A</th>
                                                                      <th class="text-center">PULSO</th>
                                                                      <th class="text-center">CX</th>
                                                                      <th class="text-center">ANEST.</th>
                                                                      <th class="text-center">PAM.</th>
                                                                      <th class="text-center">PVC</th>
                                                                      <th class="text-center">02/L/Min</th>
                                                                      <th class="text-center">VENTIRACION</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                                  <tr>
                                                                      <td class="text-center" >01</td>
                                                                      <td class="text-center" >15/08/2024</td>
                                                                      <td class="text-center" >06:30 AM</td>
                                                                      <td class="text-center" >100</td>
                                                                      <td class="text-center" >34</td>
                                                                      <td class="text-center" >100</td>
                                                                      <td class="text-center" >120</td>
                                                                      <td class="text-center" >47</td>
                                                                      <td class="text-center" >65</td>
                                                                      <td class="text-center" >23</td>
                                                                      <td class="text-center" >45</td>
                                                                      <td class="text-center" >24</td>
                                                                  </tr>
                                                                  <tr>
                                                                      <td class="text-center" >02</td>
                                                                      <td class="text-center" >15/08/2024</td>
                                                                      <td class="text-center" >09:00 AM</td>
                                                                      <td class="text-center" >98</td>
                                                                      <td class="text-center" >45</td>
                                                                      <td class="text-center" >68</td>
                                                                      <td class="text-center" >140</td>
                                                                      <td class="text-center" >34</td>
                                                                      <td class="text-center" >76</td>
                                                                      <td class="text-center" >43</td>
                                                                      <td class="text-center" >65</td>
                                                                      <td class="text-center" >25</td>
                                                                  </tr>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                                  {{--<table class="table"  cellspacing="0" width="100%" style="margin-top:5px;">
                                                  <tr>
                                                      <td width="10%">
                                                      FECHA
                                                      <input type="text" class="form-control datepicker"
                                                                  style="height: 28px !important;" autocomplete="off"
                                                                  name="fecha" id="fecha"
                                                                  value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" >
                                                      </td>
                                                      <td width="10%">HORA INICIO
                                                      <input type="time" class="form-control"
                                                              style="height: 28px !important;" autocomplete="off"
                                                              name="hora_inicio" id="hora_inicio"
                                                              value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                                                      </td>
                                                      <td width="10%">HORA FIN
                                                      <input type="time" class="form-control"
                                                              style="height: 28px !important;" autocomplete="off"
                                                              name="hora_fin" id="hora_fin"
                                                              value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                                                      </td>
                                                      <td width="25%"><br>
                                                      <a href="javascript:void(0)" aria-hidden="true"
                                                          class="btn btn-success btn-sm" onClick="crearAnestesia()" >NUEVO ANESTESIA
                                                      </a>
                                                      </td>
                                                  </tr>
                                                  </table>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row" style="border: 1px solid #d3d3d3; border-radius: 5px; margin-left: 0.2rem; margin-right: 0.2rem;">
                                          <div class="row">
                                              <div class="col-lg-4" style="display: flex; align-items: center;">
                                                  <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                      <label>REGISTRAR BALANCE HIDRICO</label>
                                                  </div>
                                                  <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                      <button tabindex="70" type="button" id="baddConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                      <div class="handle"></div>
                                                      </button>
                                                  </div>
                                              </div>
                                              <div class="col-lg-8"></div>
                                          </div>
                                          <br />
                                          <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-heading">
                                                  <h3 class="panel-title size-titulo">
                                                      BALANCE HIDRICO
                                                  </h3>
                                              </div>
                                              <div class="panel-body">
                                                  <div class="row">
                                                      <div class="col-lg-6"></div>
                                                      <div class="col-lg-6">
                                                          <div class="col-lg-6">
                                                              <a href="javascript:void(0)" aria-hidden="true" class="btn btn-info btn-sm"  onClick="verGrafica()" >
                                                                  <i class="fa fa-line-chart" aria-hidden="true">
                                                                  </i> Ver Graficas
                                                              </a>
                                                          </div>
                                                          <div class="col-lg-6">
                                                              <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="crearBalanceHidrico()" >
                                                                  NUEVO ANESTESIA
                                                              </a>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-lg-12">
                                                          <table class="table">
                                                          <thead>
                                                              <tr>
                                                                  <th class="text-center">N°</th>
                                                                  <th class="text-center">FECHA</th>
                                                                  <th class="text-center">HORA</th>
                                                                  <th class="text-center">AYUNO</th>
                                                                  <th class="text-center">REQ. BASAL</th>
                                                                  <th class="text-center">PER INSENSIBLE</th>
                                                                  <th class="text-center">SANGRADO</th>
                                                                  <th class="text-center">DEB URINARIO</th>
                                                                  <th class="text-center">SNG</th>
                                                                  <th class="text-center">3ER<br/>ESPACIO</th>
                                                                  <th class="text-center">OTROS</th>
                                                                  <th class="text-center">TOTAL</th>
                                                                  <th class="text-center">BALANCE</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <tr>
                                                                  <td class="text-center" >01</td>
                                                                  <td class="text-center" >15/08/2024</td>
                                                                  <td class="text-center" >06:30 AM</td>
                                                                  <td class="text-center" >100</td>
                                                                  <td class="text-center" >34</td>
                                                                  <td class="text-center" >100</td>
                                                                  <td class="text-center" >120</td>
                                                                  <td class="text-center" >47</td>
                                                                  <td class="text-center" >65</td>
                                                                  <td class="text-center" >23</td>
                                                                  <td class="text-center" >45</td>
                                                                  <td class="text-center" >24</td>
                                                                  <td class="text-center" >324</td>
                                                              </tr>
                                                              <tr>
                                                                  <td class="text-center" >01</td>
                                                                  <td class="text-center" >15/08/2024</td>
                                                                  <td class="text-center" >06:30 AM</td>
                                                                  <td class="text-center" >100</td>
                                                                  <td class="text-center" >34</td>
                                                                  <td class="text-center" >100</td>
                                                                  <td class="text-center" >120</td>
                                                                  <td class="text-center" >47</td>
                                                                  <td class="text-center" >65</td>
                                                                  <td class="text-center" >23</td>
                                                                  <td class="text-center" >45</td>
                                                                  <td class="text-center" >24</td>
                                                                  <td class="text-center" >324</td>
                                                              </tr>
                                                          </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-12 col-md-12">
                                          <br/>
                                          <div class="form-group">
                                              <div class="col-md-8"></div>
                                              <div class="col-md-2">
                                                  <a class="form-control btn btn-success" style="text-align:center!important">GUARDAR</a>
                                              </div>
                                              <div class="col-md-2">
                                                  <a class="form-control btn btn-danger" style="text-align:center!important">FIRMAR</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </div> --}}
              <div class="tab-pane fade" id="tabUrpa" style="font-size: 11px;">
                @include('frontend.centro_quirurgico.anestesiologia.components.form_registro_urpa')
              </div>
          </div>
      </div>
  </div>

  <!-- Modal NUEVO ANESTESIA -->
  <div class="modal fade" id="modalNuevoAnestesia" tabindex="-1" role="dialog" aria-labelledby="modalAnestesiaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
          <div class="modal-content">
              <div class="modal-header" style="font-size: 20px !important;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                          aria-hidden="true">&times;</span></button>
                  <b>NUEVO ANESTESIA</b>
              </div>
              <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data"  method="POST">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="nav-tabs-custom" style="margin-bottom: -40px;">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                              {{--<fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="panel-title text-center">
                                        <b>DATOS GENERALES <p id="id_area_disabled"></p></b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="d-flex">
                                      <div class="col-md-8">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="nombre">FECHA:</label>
                                                      <input type="text" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label>HORA</label>
                                                      <input type="text" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>EDAD</label>
                                                    <input type="number" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>PESO</label>
                                                  <input type="number" class="form-control" >
                                              </div>
                                          </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-2">
                                                  <div class="form-group">
                                                      <label for="telefono">HB</label>
                                                      <input type="number" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group">
                                                      <label for="direccion">HLO</label>
                                                      <input type="number" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>INR</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>GRUPO FACTOR</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>NNEUMOLOGO</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>CARDIOLOGICO</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="ciudad">GLUCOSA:</label>
                                                      <input type="number" class="form-control" id="ciudad">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="pais">CREATININA:</label>
                                                      <input type="number" class="form-control" id="pais">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>UREA:</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>ASA:</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-12">
                                              <label>COMPLICACIONES PRE OPETATORIO</label>
                                              <textarea name="" id="" cols="120" rows="3" class="form-control"></textarea>
                                            </div>
                                          </div>
                                      </div>
                                      <div class="col-md-4 d-flex flex-column justify-content-between">
                                        <div class="col-md-12">
                                          <label>PREDITACIÓN</label>
                                          <textarea name="" id="" cols="50" rows="15" class="form-control"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>--}}

                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="panel-title text-center">
                                        <b>AGREGAR INFORMACION DE ANESTESIA </b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="nombre">HORA:</label>
                                                      <input type="time" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label>SAT 02</label>
                                                      <input type="email" class="form-control">
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>ETC02</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label>P.A</label>
                                                      <input type="email" class="form-control">
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-1">
                                                  <div class="form-group">
                                                      <label for="telefono">PULSO</label>
                                                      <input type="tel" class="form-control" id="telefono" >
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group">
                                                      <label for="direccion">CX</label>
                                                      <input type="text" class="form-control" id="direccion" >
                                                  </div>
                                              </div>
                                              <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>ANEST</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>PAM.</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                              </div>

                                          </div>
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="ciudad">PVC:</label>
                                                      <input type="text" class="form-control" id="ciudad" >
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="pais">TEMP:</label>
                                                      <input type="text" class="form-control" id="pais" >
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>02/L/Min:</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>VENTILACIÓN:</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                {{-- INDUCCION --}}
                                <div class="panel panel-bordered panel-success">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">INDUCCION</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addInduccion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddInduccion" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label>MIDAZ:</label>
                                                    <input type="text" class="form-control form-control-sm" >
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label >PROFOF:</label>
                                                    <input type="text" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                  <label >ROCUR:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>SENTAN/REMI:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >PENTOTAL SCO:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >VERUR:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>
                                {{-- MANTENIMIENTO --}}
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">MANTENIMIENTO</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addMantenimiento" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddMantenimiento" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label>SEVOFLUORANC:</label>
                                                    <input type="text" class="form-control form-control-sm" >
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label >REMIFENILO:</label>
                                                    <input type="text" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                  <label >PROPOFOL:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>ISOFLUORANE:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >FETANILO:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >KETAMAMINA:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>
                                {{-- FLUIDOTERAPIA --}}
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">FLUIDOTERAPIA</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addFluidoTerapia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddFluidoTerapia" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label>DEXCTROSA:</label>
                                                    <input type="text" class="form-control form-control-sm" >
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label >SANGRE:</label>
                                                    <input type="text" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                  <label >POLIGELINA:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>NACI:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >PLASMA:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>
                                {{-- METODO DE ANESTESIA --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">METODO DE ANESTESIA</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addMetodoAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                  <fieldset id="fsaddMetodoAnestesia" style="display:none">
                                    <div class="col-md-6">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <label for="abierto"><b>GENERAL</b></label>
                                          <div class="custom-form-group">
                                            <label for="abierto">ABIERTO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">SEMI ABIERTO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">CERRADO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">SEMI CERRADO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BALANCEADO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">TIVA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <label for="abierto"> <b>REGIONAL</b></label>
                                          <div class="custom-form-group">
                                            <label for="abierto">RAQUIDEA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">EPIDURALCAUDAL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BLOQUEO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">OTRO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>--}}
                                {{-- TECNICA DE ANESTESIA GENERAL --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">TECNICA DE ANESTESIA GENERAL</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addTecnicaAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                  <fieldset id="fsaddTecnicaAnestesia" style="display:none">
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <label for="abierto"><b>RESP. CONTROLADA</b></label>
                                          <div class="custom-form-group">
                                            <label for="abierto">INTUB. ORAL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">FACIL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">C. MAC:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BONSFILS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">FIBROSCOD:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <br>
                                          <div class="custom-form-group">
                                            <label for="abierto">TUBO NRO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <br>
                                          <div class="custom-form-group">
                                            <label for="abierto">INTUB. NASAL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">FACIL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">A CLEGAS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">MAGIL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <br><br><br>
                                          <div class="custom-form-group">
                                            <label for="abierto">TUBO ANILLADO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <br>

                                          <div class="custom-form-group">
                                            <label for="abierto">MASCARA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">AIRE CC:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BLOQ. LARINGEO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>

                                          <br><br><br><br>
                                          <div class="custom-form-group">
                                            <label for="abierto">LARINGOS.<br>HOJA NRO.</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>--}}
                                {{-- OBSERVACIONES EN INDUCCION --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">OBSERVACIONES EN INDUCCIÓN</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addObservacionesInduccion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddObservacionesInduccion" style="display:none">
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">SATISFACTORIA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">NRO SATISFACTORIA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">ASPIRACIÓN:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">EXCIT</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">NAUSEAS:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">VOMITOS:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">LARINGOESPASMO:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                           <div class="custom-form-group">
                                              <label for="abierto">TOS:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                           <div class="custom-form-group">
                                              <label for="abierto">CIANOSIS:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}

                                {{-- TECNICA DE ANESTESIA EPIDURAL/RAQUIDEA/COMBINADA/CAUDAL/BLOQUEO --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">TECNICA DE ENESTESIA EPIDURAL/RAQUIDEA/COMBINADA/CAUDAL/BLOQUEO</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addTecnicaAnestesiaERCCB" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                  <fieldset id="fsaddTecnicaAnestesiaERCCB" style="display:none">
                                    <div class="row">
                                      <div class="col-md-2">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group form-group-sm">
                                              <label for="abierto">POSICIÓN:</label>
                                             <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group form-group-sm">
                                              <label for="abierto">PUNCIÓN:</label>
                                             <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group form-group-sm">
                                              <label for="abierto">NIVEL:</label>
                                           <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">SIMPLE:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">CONTINUA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">VECES:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">AGUJA NRO</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-8">
                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="row">
                                              <div class="col-md-12">
                                                <div class="custom-form-group form-group-sm">
                                                  <label for="abierto">LIDOCAINA:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 200px;">
                                                </div>
                                                <div class="custom-form-group form-group-sm">
                                                  <label>VOL:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                  <label>MLGR:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="row">
                                              <div class="col-md-12">
                                                <div class="custom-form-group form-group-sm">
                                                  <label for="abierto">BUPIVACAINA:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 200px;">
                                                </div>
                                                <div class="custom-form-group form-group-sm">
                                                  <label>VOL:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                  <label>MLGR:</label>
                                                  <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group form-group-sm">
                                              <label for="abierto">OTROS:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">

                                      </div>
                                      <div class="col-md-8">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group form-group-sm">
                                              <label for="abierto">BLOQUEO:</label>
                                             <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                    </div>
                                  </fieldset>
                                </div>--}}
                                {{-- POSICIÓN DE PACIENTE --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">POSICIÓN DE PACIENTE</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addPosicionPaciente" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddPosicionPaciente" style="display:none">
                                      <div class="col-md-3">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">DESC. DORSAL:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">TRENDELENBURG:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">TRENDEL INVERTIDO:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">LITOTOMIA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">DESC. VENTRAL</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">KRASKE:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">LAMINECTOMIA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">CREANEAOOTOMIA:</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">SIDE IZQ.</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                           <div class="custom-form-group">
                                              <label for="abierto">SIDE DER.</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">SIMS IZQ.</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                           <div class="custom-form-group">
                                              <label for="abierto">SIMS DER.</label>
                                              <input type="checkbox" id="abierto" class="form-check-input">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- QUIROFANO --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">QUIROFANO</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addQuirofano" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddQuirofano" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>QUIROFANO NRO:</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label>DIAG. PRE. OP:</label>
                                                    <input type="text" class="form-control" >
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <label >OP. INDICADA:</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                  <label >CIRUJANO:</label>
                                                  <input type="text" class="form-control">
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>DIAG. POST. OP:</label>
                                                  <input type="text" class="form-control" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >OP. REALIZADA:</label>
                                                  <input type="text" class="form-control">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >ANESTESIOLOGO:</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>PACIENTE:</label>
                                                  <input type="text" class="form-control" >
                                              </div>
                                              <div class="col-md-2 d-flex align-items-center">
                                                  <label >EDAD:</label>
                                                  <input type="text" class="form-control">
                                              </div>
                                              <div class="col-md-2 d-flex align-items-center">
                                                <label >SEXO:</label>
                                                <input type="text" class="form-control">
                                              </div>
                                              <div class="col-md-2 d-flex align-items-center">
                                                <label >CIP:</label>
                                                <input type="text" class="form-control">
                                              </div>
                                              <div class="col-md-2 d-flex align-items-center">
                                                <label >HC:</label>
                                                <input type="text" class="form-control">
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- COMPLICACIONES INTRA OPERATORIO --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">COMPLICACIONES INTRA OPERATORIO</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addComplicacionesIntraoperatorio" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddComplicacionesIntraoperatorio" style="display:none">
                                      <div class="col-md-3">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <label for="abierto">HIPERTENSIÓN</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">HIPOTENSIÓN:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">TAQUICARDIA:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">ARRITMIA:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">BLOUEO ALTO:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">BLOUEO TOTAL:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <label for="abierto">ASPIRACIÓN:</label>
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                            <div class="custom-form-group">
                                              <input type="text" id="abierto" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- ANALGESICOS / OTROS --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">ANALGESICOS / OTROS</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addAnalgesicosOtros" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddAnalgesicosOtros" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                            <div class="row">
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                    <textarea name="" id="" cols="5" rows="5" class="form-control form-control-sm"></textarea>
                                                  </div>
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- OBSERVACIONES --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">OBSERVACIONES</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addObservaciones" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddObservaciones" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12">
                                            <div class="row">
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                    <textarea name="" id="" cols="5" rows="2" class="form-control form-control-sm"></textarea>
                                                  </div>
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>-}}
                                {{-- BALANCE HIDRICO --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <label>BALANCE HIDRICO</label>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addBalanceHidrico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddBalanceHidrico" style="display:none">
                                      <div class="d-flex">
                                        <div class="col-md-12"><br>
                                          <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: 14;">EGRESOS</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- 11 filas de ejemplo -->
                                                <tr>
                                                    <td>HORA</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>AYUNO</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>RE. BASAL</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>PER. INSENSIBLE</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>SANGRADO</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>DEB. URINARIO</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>SNG</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>3ER ESPACIO</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>OTROS</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                            </tbody>
                                          </table><br>
                                          <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: 14;">INGRESOS</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                    <th>SOP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- 11 filas de ejemplo -->
                                                <tr>
                                                    <td>CINA 9%</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>DEXT. 5%</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>COLOIDE</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>PAQ. GLOBULAR</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>PLASMA</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>PLAQUETAS</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>CRIOPRECIPITADO</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>OTROS</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                    <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                            </tbody>
                                          </table><br>
                                          <table class="table table-bordered">
                                            <tr>
                                                <td style="font-size: 14; width:10.5%;">BALANCE</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- INGRESO URPA --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">INGRESO URPA</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addIngresoUrpa" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddIngresoUrpa" style="display:none">
                                      <div class="row">
                                        <div class="col-md-3"><br>
                                          <div class="form-group">
                                              <label>FECHA INGRESO:</label>
                                              <input type="date" class="form-control form-control-sm" >
                                          </div>
                                          <div class="form-group">
                                              <label>HORA INGRESO:</label>
                                              <input type="time" class="form-control form-control-sm" >
                                          </div>
                                          <div class="form-group">
                                            <label>CAMA Nª:</label>
                                            <input type="number" class="form-control form-control-sm" >
                                          </div>
                                        </div>
                                        <div class="col-md-2">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SIGNOS SÍNTOMAS</th>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">PALIDEZ</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">CLANOSIS</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">RUBOR</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">RASH CUTÁNEO</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">DIAFORESIS</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">TOS</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">BRONCOESPASMO</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">EXITACIÓN</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">HIPERTERMIA</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-7">
                                          <br>
                                          <div class="row">
                                            <div class="col-md-3">
                                              <table class="table" style="font-size: 12px; width: 100%;">
                                                <tr>
                                                  <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">VÍA AEREA</th>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">LIBRE</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">T. ENDOTRAQUEAL</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">T. OTRO-FARING</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">TRAQUEOSTOMIA</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                              </table>


                                            </div>
                                            <div class="col-md-3">
                                              <table class="table" style="font-size: 12px; width: 100%;">
                                                <tr>
                                                  <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">APOY. VENTILAT.</th>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">VENT. MEC.</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">MASCARILLA</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">VENTURI</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">TUBO EN T.</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                              </table>
                                            </div>
                                            <div class="col-md-3">
                                              <table class="table" style="font-size: 12px; width: 100%;">
                                                <tr>
                                                  <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">CATÉTERES.</th>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">VIA PERIF.</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">VIA CENTRAL</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">LINEA ART.</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">CAT. EPIDURI.</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                              </table>
                                            </div>
                                            <div class="col-md-3">
                                              <table class="table" style="font-size: 12px; width: 100%;">
                                                <tr>
                                                  <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2"></th>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">S. NASOG</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">S. VESICAL</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">DRENAJES</td>
                                                  <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                                </tr>
                                              </table>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-12">
                                              <table class="table" style="font-size: 12px; width: 100%;">
                                                <tr>
                                                  <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="10">FUNCIONES VITALES</th>
                                                </tr>
                                                <tr>
                                                  <td style="padding: 5px;">PA:</td>
                                                  <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td style="padding: 5px;">FC:</td>
                                                  <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td style="padding: 5px;">FR:</td>
                                                  <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td style="padding: 5px;">SAT 02:</td>
                                                  <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td style="padding: 5px;">Tª</td>
                                                  <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                </tr>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <hr>
                                      <div class="row">
                                        <div class="col-md-3">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="4">SCORE ALDERETE</th>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">PARAMETROS</td>
                                                <td style="padding: 5px;">0</td>
                                                <td style="padding: 5px;">1</td>
                                                <td style="padding: 5px;">2</td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">ACTIV. MUSCULAR</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RESPIRACIÓN</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CIRCULACIÓN (PA)</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CONCIENCIA (PA)</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">COLOR / SET 02</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"><b>TOTAL</b></td>
                                              <td style="padding: 5px;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-5">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE ANDERSEN - DOLOR CRITERIOS</th>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;"></td>
                                                <td style="padding: 5px;"><b>PUNTOS</b></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">SIN DOLOR AL REPOSO Y DOLOR LIGERO AL MOVIMIENTO O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR LIGERO EN REPOSO Y MODERADO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR MOD. EN REPOSO Y SEVERO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR SEVERO EN REPOSO Y EXTREMO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR INTENSO AL REPOSO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-2">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE BROMAGE BLOQUEO MOTOR</th>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">COMPLETO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CASI COMPLETO</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">PARCIAL</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">NULO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-2">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">GLASGOW</th>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">AO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RV</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RM</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">TUBO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <label>INDICACIONES Y TERAPIA DE DOLOR / OBSERVACIONES</label>
                                          <textarea name="" id="" cols="5" rows="5" class="form-control form-control-sm"></textarea>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}
                                {{-- condicion de alta --}}
                                {{--<div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading2">
                                    <div class="form-group">
                                      <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                        <div class="panel-title">CONDICION ALTA</div>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="70" type="button" id="addCondicionAlta" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                  </div>

                                    <fieldset id="fsaddCondicionAlta" style="display:none">
                                      <div class="row">
                                        <div class="col-md-3">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="4">SCORE ALDERETE</th>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">PARAMETROS</td>
                                                <td style="padding: 5px;">0</td>
                                                <td style="padding: 5px;">1</td>
                                                <td style="padding: 5px;">2</td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">ACTIV. MUSCULAR</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RESPIRACIÓN</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CIRCULACIÓN (PA)</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CONCIENCIA (PA)</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">COLOR / SET 02</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"><b>TOTAL</b></td>
                                              <td style="padding: 5px;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-5">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE ANDERSEN - DOLOR CRITERIOS</th>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;"></td>
                                                <td style="padding: 5px;"><b>PUNTOS</b></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">SIN DOLOR AL REPOSO Y DOLOR LIGERO AL MOVIMIENTO O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR LIGERO EN REPOSO Y MODERADO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR MOD. EN REPOSO Y SEVERO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR SEVERO EN REPOSO Y EXTREMO CON EL MOV. O TOS</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">DOLOR INTENSO AL REPOSO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-2">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE BROMAGE BLOQUEO MOTOR</th>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">COMPLETO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">CASI COMPLETO</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">PARCIAL</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">NULO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div class="col-md-2">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">GLASGOW</th>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">AO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RV</td>
                                              <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">RM</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">TUBO</td>
                                              <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="10">FUNCIONES VITALES</th>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">PA:</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">FC:</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">FR:</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">SAT 02:</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">Tª</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">EXTUBADO</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">RETIRO DE CATETER EPIDRURAL</td>
                                              <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-8">
                                          <label>INDICACIONES DE ALTA URPA / OBSERVACIONES</label>
                                          <textarea name="" id="" cols="5" rows="2" class="form-control form-control-sm"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                          <table class="table" style="font-size: 12px; width: 100%;">

                                            <tr>
                                                <td style="padding: 5px;">FECHA EGRESO:</td>
                                                <td style="padding: 5px;"><input type="date" class="form-control form-control-sm"></td>
                                            </tr>
                                            <tr>
                                              <td style="padding: 5px;">HORA EGRESO:</td>
                                              <td style="padding: 5px;"><input type="time" class="form-control form-control-sm"></td>
                                          </tr>
                                            <tr>
                                              <td style="padding: 5px;">PASA A:</td>
                                              <td style="padding: 5px;"><input type="text" class="form-control form-control-sm" style="width: 70%;"></td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <table class="table" style="font-size: 12px; width: 100%;">
                                            <tr>
                                              <td style="padding: 5px;">PACIENTE:</td>
                                              <td style="padding: 5px;"><input type="text" class="form-control form-control-sm" style="width: 70%;"></td>
                                              <td style="padding: 5px;">EDAD:</td>
                                              <td style="padding: 5px;"><input type="text" class="form-control form-control-sm"></td>
                                              <td style="padding: 5px;">HC:</td>
                                              <td style="padding: 5px;"><input type="text" class="form-control form-control-sm"></td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                    </fieldset>
                                </div>--}}

                              </fieldset>
                        </div><br></div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-success" id="registrarAnestesia"
                      value="create">Guardar</button>
                </div>
              </form>
          </div>
      </div>
  </div>

  <!-- Modal NUEVO BALANCE HIDRICO -->
  <div class="modal fade" id="modalNuevoBalanceHidrico" tabindex="-1" role="dialog" aria-labelledby="modalAnestesiaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
          <div class="modal-content">
              <div class="modal-header" style="font-size: 20px !important;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                          aria-hidden="true">&times;</span></button>
                  <b>BALANCE HIDRICO</b>
              </div>
              <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data"  method="POST">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                      <div class="col-lg-6">
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">EGRESO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <label for="ciudad">SOAP</label>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">HORA:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="time" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">AYUNO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">REQ. BASAL:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">PER INSENSIBLES:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">SANGRADO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">DEB. URUBARIO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">SNG:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">3ER ESPACIO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">OTROS:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">TOTAL:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">INGRESOS:</label>
                              </div>
                              <div class="col-lg-6">
                                  <label for="ciudad">SOAP</label>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">HORA:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="time" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">AYUNO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">REQ. BASAL:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">PER INSENSIBLES:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">SANGRADO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">DEB. URUBARIO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">SNG:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">3ER ESPACIO:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">OTROS:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-4">
                                  <label for="ciudad">TOTAL:</label>
                              </div>
                              <div class="col-lg-6">
                                  <input type="text" class="form-control" id="ciudad" >
                              </div>
                          </div>
                      </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-success" id="registrarAnestesia"
                      value="create">Guardar</button>
                </div>
              </form>
          </div>
      </div>
  </div>

  <div class="modal" id="squarespaceModalAnestesio" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="display: none; position: absolute; z-index: 9999; width: 700px; max-width: 90%;">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="anularRecetaAnestesiologia()"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
      </div>
      <div class="modal-body">
        <form  id="form_invalidar_anestesio" method="post" action="{{ route('atencion_cqx_invalidar_receta_digital')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" class="form-control" id="id_farmacia_invalidar" name="id_farmacia">
            <input type="hidden" class="form-control" id="id_receta_invalidar_anestesio" name="id_receta">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
            <textarea class="form-control" id="motivo_invalidar_anestesio" name="motivo" placeholder="Ingrese el motivo" required rows="3" cols="3"></textarea>
          </div>
          <div class="form-group">
            <input id="btnInvalidar" class="btn btn-success" type="button" value="Invalidar">
            <input onclick="anularRecetaAnestesiologia()" class="btn btn-danger" type="button" value="Cancelar">
          </div>
        </form>
      </div>
  </div>
  </div>
</div>