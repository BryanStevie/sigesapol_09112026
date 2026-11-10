

<link rel="stylesheet" href="{{ asset('js/fullcalendar-2.3.2/fullcalendar.css') }}">
<link rel="stylesheet" href="{{ asset('js/fullcalendar-2.3.2/fullcalendar.print.css') }}" media="print">

{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<style>
  .fc-sun {
    background-color: #DCE2E8;
  }
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }
  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    /*margin:10px;*/
    text-align: left;
    /*margin-top:20px;*/
    /*background:#5cb85c*/
    background: transparent;
    color: #000000
  }
  .fc-day-grid-event :hover {
    color: #000000
  }
  .fc-content {
    padding: 0px !important;
  }
  .fc-title {
    text-align: left;
    font-size: 10px
  }
  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }
  #suggestions {
    width: 350px;
    height: 150px;
    overflow: auto;
  }
  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  }
  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }
  .fc-unthemed .fc-today {
    background: #E1D097!important;
  }

  /* Estilos para el modal Examenes de una orden de laboratorio */
  .cpms-header {
    background-color: #7CB342;
    color: white;
    text-align: center;
    padding: 20px;
    font-size: 18px;
    font-weight: bold;
    letter-spacing: 1px;
    margin: 0;
    border-radius: 10px 10px 0 0;
  }

  .cpms-content {
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
  }

  .cpms-item {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
    align-items: flex-start;
  }

  .cpms-item:last-child {
    border-bottom: none;
  }

  .cpms-codigo {
    font-weight: bold;
    color: #333;
    min-width: 80px;
    margin-right: 20px;
    font-size: 14px;
  }

  .cpms-descripcion {
    text-align: left;
    color: #555;
    flex: 1;
    font-size: 14px;
    line-height: 1.4;
  }
  /* Fin Estilos para el modal Examenes de una orden de laboratorio */
</style>



<script src="{{ asset('js/fullcalendar-2.3.2/lib/moment.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar-2.3.2/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar-2.3.2/lang/es.js') }}"></script>



<script>
  $(document).ready(function() {
    $('input[name="parent"]').on("change", function(e) {
      getServicio();
    });
    // $('#id_servicio,#id_establecimiento').on("change", function(e) {
      //$('#calendar').fullCalendar('destroy'); 
      // fullCalendar();
      //modalTurnos();
      //modalHistorial();
    // });
    obtenerBeneficiario();
  });
  function modalProximaCita(dni_beneficiario,id_consultorio){
    $("#showProximasCitas .modal-dialog").css("width","45%");
    
    $.ajax({
        url: "{{ url('/modal_proxima_cita_cqx') }}/" + dni_beneficiario + "/" + id_consultorio,
        type: "GET",
        success: function (result) {  
            $('#diveditpreg').html(result);
            $('#showProximasCitas').modal('show');
        }
    });
  }
  
  function valorIdentidad(identidad){
    if (identidad == "DNI"){
      return "1";  
    }else if (identidad == "CARNET DE EXTRANJERIA"){
      return "2";  
    }else if (identidad == "PASAPORTE"){
      return "3";  
    }else{
      return "4"
    }
  }
  function obtenerBeneficiario() {
    var id_tiporeceta = 1;
    var tipodoc_beneficiario = valorIdentidad($("#tipodoc_beneficiario_bus").val());
    
    var dni_beneficiario = $("#dnibeneficiario").val();
    let id_consultorio = $('#nro_doc_ident').val();
    var msg = "";
    //if(id_tiporeceta == "")msg += "Debe seleccionar el tipo de receta <br>";
    //if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }
    $.ajax({
      url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
      dataType: "json",
      beforeSend: function() {
        Swal.fire({
          html: '<p style="margin: 20px 0px 0px 0px"><b>Espere un momento por favor, completando la información</b></p>',
          allowOutsideClick: false,
          didOpen: () => {
              Swal.showLoading();
          }
        });
      },
      success: function(result) {
        var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario.apematafiliado + " " + result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
        $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
        $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
        $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
        $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result.beneficiario.apecasafiliado);
        $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#grado').val(result.beneficiario.grado);
        $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
        $('#nomsexo').val(result.beneficiario.nomsexo);
        if (result.numero_historia != null) {
          if (result.numero_historia != '')
            $('#nro_historia').val(result.numero_historia);
          else
            $('#nro_historia').val(result.beneficiario.nrodocafiliado);
        } else
          $('#nro_historia').val(result.beneficiario.nrodocafiliado);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
        $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
        //$('#tipo_beneficiario').val(result.beneficiario.parentesco);
        $('#nombre_titular').val(result.beneficiario.nomtitular);
        $('#paterno_titular').val(result.beneficiario.apepattitular);
        $('#materno_titular').val(result.beneficiario.apemattitular + " " + result.beneficiario.apecastitular);
        //$('#beneficiario').val(beneficiario);
        var parentesco = result.beneficiario.parentesco;
        var ubigeo = result.beneficiario.ubigeo;
        var establecimiento = result.establecimiento;
        var cita = result.cita;
        var id_establecimiento = 0;
        if (establecimiento[0] != undefined) {
          id_establecimiento = establecimiento[0].id_establecimiento;
          nombre_establecimiento = establecimiento[0].establecimiento;
          $('#nombre_establecimiento').html(nombre_establecimiento);
        }
        if (cita[0] != undefined) {
          if (cita[0].id_estado_cita == 1) { // ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
            //bootbox.alert("El beneficiario tiene una cita programada");
            //return false;
            var id_servicio = 8;
            //$("#parent1").prop("checked", true);
            //var id_servicio = cita[0].id_servicio;
            //$("#parent2").prop("checked", true);
          }
          if (cita[0].id_estado_cita == 3) { //ESTADO DERIVADO
            var id_servicio = cita[0].id_sub_consultorio;
            //$('#parent').val("2");
            //$('#nombre_parent').html("DERIVADO");
            //var parent = $('input[name="parent"]:checked').val();
            $("#parent2").prop("checked", true);
          }
        } else {
          var id_servicio = 8;
          //$('#parent').val("1");
          //$('#nombre_parent').html("GENERAL");
          $("#parent1").prop("checked", true);
        }
        //obtenerEstablecimiento(parentesco,ubigeo,id_establecimiento);
        id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
        //obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio);
        //getServicio(id_servicio);
        //modalTurnos();
        if(result.cantidad_cita != null && result.cantidad_cita > 0)
          modalProximaCita(tipodoc_beneficiario, dni_beneficiario);
        //modalTurnos();
        modalHistorial();
        $("#divTurno").html("");
        $('#calendar').fullCalendar('destroy');
        fullCalendar();
        mostrarOrdenesLaboratorioCitaOrigen();
        
        /*
        if(id_tiporeceta == 3){	
          ShowByServicioAndId_farmacia();
        }
        */
      },
      complete: function() {
        Swal.close();
      },
    });
  }
  function anularCita(id_cita) {
    bootbox.confirm({
      size: "small",
      message: "¿Deseas anular la cita?",
      callback: function(result) {
        if (result == true) {
          fn_anularCita(id_cita);
        }
      }
    });
  }
  function fn_anularCita(id_cita) {
    $.ajax({
      url: "{{ url('/anular_cita') }}/" + id_cita,
      dataType: "json",
      success: function(result) {
        if (result.sw == true) {
          obtenerBeneficiario();
          $('.nav-tabs a[href="#tab1default"]').tab('show');
        }
      }
    });
  }
  /*function obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio) {
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#id_establecimiento').html(newOption);
    if (ubigeo == "") ubigeo = 0;
    $.ajax({
      url: "{{ url('/obtener_establecimiento_cita') }}/" + parentesco + "/" + ubigeo,
      success: function(result) {
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function(ii, oo) {
          var sel = "";
          if (id_establecimiento == oo.id) sel = "selected='selected'";
          newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
        });
        $('#id_establecimiento').html(newOption);
        $("#id_establecimiento").select2({
          max_selected_options: 4
        });
        getServicio(id_servicio);
      }
    });
  }*/
  function limpiarBeneficiario() {
    var dni_beneficiario = <?php echo $prestacion->nro_doc_ident;?>//$('#dni_beneficiario').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    if (dni_beneficiario != nrodocafiliado) {
      $('#beneficiario').val("");
      $('#tipo_beneficiario').val("");
      $('#nombre_beneficiario').val("");
      $('#paterno_beneficiario').val("");
      $('#materno_beneficiario').val("");
      $('#grado').val("");
      $('#nro_historia').val("");
      $('#tipodoc_beneficiario').val("");
      $('#cip_beneficiario').val("");
      $('#id_admision').val("");
      $('#grado').val("");
    }
    //obtenerBeneficiario();
  }
  function getServicio(id_servicio) {
    $("#id_establecimiento").css('visibility', 'hidden');
    var newOption = "";
    $('#id_servicio').html(newOption);
    //$("#id_servicio").val('').trigger("change");
    var parent = 3; //$('input[name="parent"]:checked').val();
    //var parent = $('#parent').val();
    //var parent	= $('#parent').val();
    var tipodoc_beneficiario = valorIdentidad($("#tipodoc_beneficiario_bus").val());
    var dni_beneficiario = <?php echo $prestacion->nro_doc_ident;?> //$("#dni_beneficiario").val();
    var id_establecimiento = $('#id_establecimiento').val();
    let id_consultorio = $('#id_consultorio').val();
        fullCalendar();
  }
  function getcargaservicio() {
    var newOption = "";
    $('#id_servicio').html(newOption);
    var parent = $('input[name="parent"]:checked').val();
    var id_establecimiento = $('#id_establecimiento').val();
    let id_consultorio = $('#id_consultorio').val();
    if (parent == 1) url = "{{ url('/obtener_consultorio_cita_general') }}/" + id_establecimiento + "/" + tipodoc_beneficiario + "/" + dni_beneficiario;
    if (parent == 2) url = "{{ url('/obtener_consultorio_cita') }}/" + id_establecimiento + "/" + tipodoc_beneficiario + "/" + dni_beneficiario;
    if (parent == 3) url = "{{ url('/obtener_consultorio_cita_especifico') }}/" + id_establecimiento + "/" + id_consultorio;
    $.ajax({
      url: url,
      success: function(result) {
        var newOption = "";
        $(result).each(function(ii, oo) {
          var sel = "";
          if (id_servicio == oo.id) sel = "selected='selected'";
          newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
        });
        $('#id_servicio').html(newOption);
        $("#id_servicio").select2({
          max_selected_options: 4
        });
        $("#id_establecimiento").css('visibility', 'hidden');
        fullCalendar();
      }
    });
  }
  async function fullCalendar() {
    var fecha = new Date(2022, 4, 10);
    var feriados = await getFeriados(moment().format("YYYY"));
    //var SITEURL = "{{url('/')}}";
    var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
    var iIdTipoCompra = $('#hdIdTipoCompra').val();
    var id_servicio = $('#id_consultorio').val();
    var id_ipress = $('#id_establecimiento').val();
    $("#divHorario").html("");
    $('#calendar').fullCalendar({
      header: {
        //left: 'prev,next today',
        left: 'today',
        center: 'title',
        right: 'prev,next'
      },
      editable: false,
      //events: "submit/cronogramaCompraData.php?iIdMovimientoCompra="+iIdMovimientoCompra,
      //events: "cronograma_cita",
      /*events: "cronograma_cita/" + id_ipress + "/" + id_servicio,
      eventClick: function(calEvent, jsEvent, view) {
        console.log(calEvent);
        console.log(jsEvent);
        console.log(view);
        var start = calEvent.start._i;
        //modalDelegar(start);
      },*/
      annotations: [{
        start: new Date(2000, 1, 1),
        end: new Date(2022, 4, 13),
        title: 'Blocked Day',
        cls: 'open',
        color: '#777777',
        background: '#000'
      }],
      /*
      select: function(start, end) {
        if(start.isBefore(moment())) {
            $('#calendar').fullCalendar('unselect');
            return false;
        }
      },
      */
      selectable: true,
      selectHelper: true,
      select: function(start, end, allDay) {
        //console.log(start.format('YYYY-MM-DD'));
        ///console.log(end.format('YYYY/MM/DD'));
        //console.log(moment().format('YYYY/MM/DD'));
        // moment(start).format('YYYY/MM/DD hh:mm');
        if(start.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
          
          bootbox.alert("No se puede registrar citas con fechas pasadas");
          return;
        } else {
          dateObj = new Date(start);
          dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
          dateObjNTZ = new Date(dateIntNTZ);
          start = dateObjNTZ.toISOString().slice(0, 10);
          start = $.trim(start);
          var sValidaciones = "";
          modalDelegar(start);
        }
      },
      dayRender: function(date, cell) {
        var enabled = true;
        if(date < moment()) {
          cell.addClass("app-fullcalendar-day-disabled");
          enabled = false
        }
        feriados.forEach(element => {
          if (date.isSame(element.feriado)) {
            cell.addClass("app-fullcalendar-feriado");
            enabled = false
          }
        });
        if (enabled)
          cell.addClass("app-fullcalendar-day-enabled");
      }
    });
  }
  /*
  $(document).on('click','.fc-prev-button',async function(){
    let b = $('#calendar').fullCalendar('getDate');
    let anio = b.year();
    let mes = b.month() + 1;
    var list_feriados = await getFeriados(anio,mes);
    $('#calendar').fullCalendar({
      dayRender: function(date, cell) {
        console.log(list_feriados);
        list_feriados.forEach(element => {
          if (date.isSame(element.feriado)) {
            cell.css("background-color","#E66B6B");
        }  
        });
      }
    });
  });
  $(document).on('click','.fc-next-button',async function(){
    let b = $('#calendar').fullCalendar('getDate');
    let anio = b.year();
    let mes = b.month() + 1;
    
    //console.log(feriados);
    
    $('#calendar').fullCalendar({
      dayRender: function(date, cell) {
        console.log('ere');
        //var feriados = await getFeriados(anio,mes);
        //console.log(feriados);
        feriados.forEach(element => {
          if (date.isSame(element.feriado)) {
            cell.css("background-color","#E66B6B");
        }  
        });
      }
    });
  });
  */
  function fn_RegistrarNuevoCronograma(start, iIdMovimientoCompra, iIdTipoCompra) {
    /*
      var oBotones = {
      "Pagar": function () {
              var btnGrabar = $("#ifrModal").contents().find("input[id='btnGrabar']");
              btnGrabar.click();
          },
          "Cerrar": function () {
              $(this).dialog("close");
              $(this).remove();
          }
      };
      var sRuta = "frmCronogramaCompraDetalle.php?param=" + iIdMovimientoCompra + "&param1=" + iIdTipoCompra + "&param2=" + start;
      fn_util_AbreModalNew("Agendar Pago", sRuta, '40', 150, null, oBotones);
    */
    $('#showAtencionesMensual').modal();
  }
  async function modalDelegar(start) {
    var tieneMensaje = false;
    let validacion = JSON.parse(await validateProgramacionCitas(start));
    //$(".modal-dialog").css("width","80%");
    //$('#openOverlayOpc').modal('show');
    //$('#openOverlayOpc .modal-body').css('height', 'auto');
    var id_servicio = $('#id_consultorio').val();
    var id_ipress = $('#id_establecimiento').val();
    var dni_beneficiario = <?php echo $prestacion->nro_doc_ident;?> //$('#dni_beneficiario').val(); 
    var id_cqx_intervencion = <?php echo $id_cqx_intervencion;?> 
    if (id_servicio == '') {
      bootbox.alert('Seleccione el servicio');
    }
    if (validacion['validacion'] == '1') {
      $('#es_asegurado').val(1);
    } else if (validacion['validacion'] == '90') {
      $('#es_asegurado').val(0);
    } else {
      tieneMensaje = true;
      bootbox.alert(validacion['validacion']);
    }
    if (!tieneMensaje) {
      $.ajax({
        url: "{{ url('/modal_cita_horario_lista_cita_medico_cqx') }}/" + id_ipress + "/" + id_servicio + "/" + start + "/" + dni_beneficiario+ "/" + id_cqx_intervencion,
        type: "GET",
        beforeSend: function() {
        Swal.fire({
            html: '<p style="margin: 20px 0px 0px 0px"><b>Buscando datos de la programación médica</b></p>',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
          });
        },
        success: function(result) {
          $("#divHorario").html(result);
          if ($('#modal-cita-quirurgica').hasClass('in') || $('#modal-cita-quirurgica').hasClass('show')) {
            $('body').addClass('modal-open');
          }
        },
        complete: function() {
        Swal.close();
        }
      });
    }
  }
  function modalTurnos() {
    //$(".modal-dialog").css("width","80%");
    //$('#openOverlayOpc').modal('show');
    //$('#openOverlayOpc .modal-body').css('height', 'auto');
    var id_servicio = $('#id_servicio').val();
    var id_ipress = $('#id_establecimiento').val();
    var start = "<?php echo $fechaServidor ?>";
    $("#divTurno").html("");
    $.ajax({
      url: "{{ url('/modal_turnos') }}/" + id_ipress + "/" + id_servicio + "/" + start,
      type: "GET",
      success: function(result) {
        $("#divTurno").html(result);
      }
    });
  }
  function modalHistorial() {
    /*
    var id_servicio	= $('#id_servicio').val();
    var id_ipress	= $('#id_establecimiento').val();;
    var start = '2019-10-24';
    */
    var dni_beneficiario = <?php echo $prestacion->nro_doc_ident;?>//$("#dni_beneficiario").val();
    $.ajax({
      url: "{{ url('/modal_historial_cita') }}/" + dni_beneficiario,
      type: "GET",
      success: function(result) {
        $("#divHistorial").html(result);
      }
    });
  }
  async function getFeriados(anio) {
    let feriados;
    try {
      feriados = await $.ajax({
        url: "{{ url('/listar_feriados') }}/" + anio,
        type: "GET"
      });
      return JSON.parse(feriados);
    } catch (error) {
      console.log(error);
    }
  }
  async function validateProgramacionCitas(fechaCita) {
    try {
      let validacion = await $.ajax({
        url: "{{ url('/valida_programacion_cita') }}",
        data: {
          _token: $("#_token").val(),
          nro_documento: $("#nrodocafiliado").val(),
          id_establecimiento: $("#id_establecimiento").val(),
          id_sub_consultorio: $("#id_consultorio").val(),
          fecha: fechaCita,
        },
        type: "POST"
      });
      return validacion;
    } catch (error) {
    }
  }

  const limpiarContenedoresApoyoDiagRegCitas = () => {
    $('#id_cita_origen_laboratorio').val('');
    $('#tblOrdenesLaboratorioCitaOrigen tbody').html('');
    $('#listOrdenesLaboratorioCitaOrigen').hide();
  }

  const mostrarOrdenesLaboratorioCitaOrigen = () => {
    const dni_beneficiario = $("#dnibeneficiario").val();
    const id_servicio = $('#id_consultorio').val();
    // console.log(`id_servicio : ${id_servicio}`);
    const num_id_servicio = parseInt(id_servicio, 10);
    const id_cqx_intervencion = $('#id_cqx_intervencion').val();

    $.ajax({
      url: `/listar_ordenes_laboratorio_cita_origen2/${dni_beneficiario}/${id_cqx_intervencion}`,
      success: function(response) {
        console.log(response);
        if (response.length === 0) {
          return;
        } else {
          let newOption = "";
          let tipoAtencion = "";
          let servicio = "";
          let medico = "";
          let radiologiaPortatil = "";
          $(response).each(function(ii, oo) {

            tipoAtencion = oo.nombre_tipo_atencion;
            servicio = oo.especialidad_prestacional;
            medico = oo.medico_prestacional;

            newOption += `
              <tr>
                <td>
                  <div data-toggle="tooltip" data-placement="right" data-html="true" title= "id-cita : ${oo.id_cita}">
                    ${oo.id}
                  </div>
                </td>
                <td>${oo.created_at}</td>
                <td>
                  ${tipoAtencion}
                </td>
                <td>
                  ${servicio}
                </td>
                <td style="font-size: 7px;">${oo.consultorio}</td>
                <td>${medico}</td>
                <td>
                  <div class="btn btn-sm btn-primary" onclick="mostrarCPMSDesdeTabla(${JSON.stringify(oo.cpms).replace(/"/g, '&quot;')})" title="Ver CPMS"><i class="fa fa-search" aria-hidden="true"></i></div>
                </td>
              `;
                          
            if (oo.ruta_laboratorio == null || oo.ruta_laboratorio == "") {
              newOption += `
                <td>
                  <div class="btn-app"><span>PENDIENTE FIRMAR</span></div>
                </td>
              `;

            } else {
              newOption += `
                <td>
                  <input type="checkbox" name="uniqueCheckbox" value="${oo.id_cita}" class="single-checkbox-lab-cita-ini">
                </td>
              `; // Id Cita de la Atencion que solicita la intervencion
            }
            newOption += `</tr>`;
          });
          $('#tblOrdenesLaboratorioCitaOrigen tbody').html(newOption);
          $('#listOrdenesLaboratorioCitaOrigen').show();
          // Asegura que solo un checkbox esté seleccionado a la vez
          $('.single-checkbox-lab-cita-ini').on('change', function() {
              $('.single-checkbox-lab-cita-ini').not(this).prop('checked', false);
          });
        }
      }
    });
  }

  $('#id_consultorio').on("change", function(e) {
    limpiarContenedoresApoyoDiagRegCitas(0);
    mostrarOrdenesLaboratorioCitaOrigen();
  });

  function mostrarCPMSDesdeTabla(cpmsArray) {

    // Construir el contenido HTML
    var contenidoHTML = '<div class="cpms-header">LISTA DE EXÁMENES SOLICITADOS</div>';
    contenidoHTML += '<div class="cpms-content">';

    $.each(cpmsArray, function(index, item) {
      contenidoHTML += '<div class="cpms-item">';
      contenidoHTML += '<div class="cpms-codigo">' + item.codigo_lab + '</div>';
      contenidoHTML += '<div class="cpms-descripcion">' + item.descripcion_corta + '</div>';
      contenidoHTML += '</div>';
    });

    contenidoHTML += '</div>';

    // Mostrar SweetAlert
    Swal.fire({
      html: contenidoHTML,
      showCloseButton: true,
      showConfirmButton: false,
      width: '600px',
      customClass: {
        popup: 'cpms-popup'
      }
    });
  }

  $(document).on('change', '.single-checkbox-lab-cita-ini', function() {
    if ($(this).is(':checked')) {
      // Si se marca el checkbox, permitimos la acción
      $(this).prop('checked', true);
    } else {
      // Si se intenta desmarcar, revertimos la acción
      $(this).prop('checked', true);
    }
  });

  $(document).on('change', '.single-checkbox-lab-cita-ini', function() {
    // Obtener el valor del checkbox seleccionado
    const checkboxValue = $(this).val();
    $('#id_cita_origen_laboratorio').val(checkboxValue);
  });

</script>
<form class="app-form" method="post" id="frmCita" autocomplete="off">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" id="es_asegurado" name="es_afiliado" value="">
  <input type="hidden" name="id_nivel" id="id_nivel" value="<?php //echo $id_nivel 
                                                            ?>">
  <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
  <input type="hidden" value="{{ $prestacion->correo }}" name="correo" id="correo">
  <input type="hidden" value="{{ $prestacion->domicilio }}" name="domicilio" id="domicilio">
  <input type="hidden" value="{{ $prestacion->telefono }}" name="telefono" id="telefono">
  <input type="hidden" value="{{ $prestacion->telefono2 }}" name="telefono2" id="telefono2">
  <input type="hidden" value="{{ $prestacion->id }}" name="id_prestacion_principal" id="id_prestacion_principal">
  <input type="hidden" value="{{ $id_cqx_cita }}" name="id_cqx_cita">
  <input type="hidden" value="{{ $prestacion->nro_doc_ident }}" id="dnibeneficiario">
  <input type="hidden" value="" name="id_cita_origen_laboratorio" id="id_cita_origen_laboratorio">
  <input type="hidden" value="{{ $id_cqx_intervencion }}" id="id_cqx_intervencion" />

  <div class="row">
    <div class="col-md-12" style="background-color: #E0E9E0; padding: 1rem 0;">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <div class="col-md-2">
              <p style="margin: 0; font-size: 2rem; text-align: center; font-weight: 700;">IPRESS</p>
              {{--<label for="id_establecimiento" style="margin: 0;">ABSCRIPCIÓN</label>--}}
            </div>
            <div class="col-md-8">
              <input readonly="" type="hidden" class="form-control" name="id_establecimiento" id="id_establecimiento" value="{{$prestacion->id_establecimiento}}"/>
              <input readonly="" type="text" class="form-control"  value="{{$prestacion->establecimiento}}" style="text-align:left"/>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="col-md-2">
              <p style="margin: 0; font-size: 2rem; text-align: center; font-weight: 700;">SERVICIO</p>
            </div>
            <div class="col-md-8">
              <!-- <input readonly="" type="hidden" class="form-control" value="{{$servicio->id}}" name="id_consultorio" id="id_consultorio" style="text-align:left"/>
              <input readonly="" type="text" class="form-control" value="{{$servicio->nombre}}" style="text-align:left"/> -->
              <select name="id_consultorio" id="id_consultorio" class="form-control">
                  @foreach($servicios_cqx as $item)
                      <option value="{{ $item->id }}" {{ $item->id == $servicio->id ? 'selected' : '' }}>
                          {{ $item->nombre }}
                      </option>
                  @endforeach
              </select>
              <input type="hidden" name="parent" id="parent2" value="3">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="tipo">TIPO DOC.</label>
          <input type="text" class="form-control" id="tipodoc_beneficiario_bus" name="tipodoc_beneficiario_bus" readonly="" value="{{$prestacion->tipo_doc_ident}}"/>
          <input type="hidden" value="" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
          <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
        </div>
      </div>
      <div class="col-md-4">
        <label for="dni_beneficiario">NRO DOC.</label>
        <div class="form-group">
        <input type="text" placeholder="" class="form-control text-uppercase" maxlength="12" autofocus=""  readonly="" value="{{$prestacion->nro_doc_ident}}">
        <input type="hidden" id="dni_beneficiario_cqx" name="dni_beneficiario"  value="{{$prestacion->nro_doc_ident}}">
        </div>
        <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly="" />
        <input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
        <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly="" />
      </div>
      <div class="col-md-4">
        <label for="dni_beneficiario">SEXO</label>
        <div class="form-group">
          <input {{--onKeyPress="return soloNumeros(event)"--}}id="sexo" name="sexo" type="text" placeholder="" class="form-control text-uppercase" maxlength="12" autofocus="" readonly="" value="{{$prestacion->sexo}}" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>APELLIDO PATERNO</label>
          <input readonly="" type="text" class="form-control" value="{{$prestacion->asegurado_paterno}}" name="apellido_paterno" id="apellido_paterno" style="text-align:left"/>
          <input type="hidden" class="form-control" value="" name="nombre_beneficiario" id="nombre_beneficiario" />
          <input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario" />
          <input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario" />
          <input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular" />
          <input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular" />
          <input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular" />
          <input type="hidden" class="form-control" value="" name="fecnacafiliado" id="fecnacafiliado" />
          <input type="hidden" class="form-control" value="" name="nomsexo" id="nomsexo" />
          <input type="hidden" class="form-control" value="" name="nro_historia" id="nro_historia"/>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>APELLIDO MATERNO</label>
          <input readonly type="text" name="apellido_materno" id="apellido_materno" class="form-control" value="{{$prestacion->asegurado_materno}}" />
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>NOMBRES</label>
          <input readonly type="text" name="nombres" id="nombres" class="form-control" value="{{$prestacion->asegurado_nombre}}" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>FECHA DE</label>
          <input readonly="" type="text" class="form-control" value="{{$prestacion->fecha_nac}}" name="fecha_de" id="fecha_de" style="text-align:left"/>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>PARENTESCO</label>
          <input readonly="" value="{{$prestacion->parentesco}}" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control"/>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>GRADO</label>
          <input readonly="" value="{{$prestacion->grado}}" type="text" name="grado_" id="grado_" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="tipo_cita">TIPO CITA</label>
          <select class="form-control" name="tipo_cita" id="id_tipo_cita" tabindex="8">
            <option value="">--Seleccionar--</option>
            <option value="1" selected>Presencial</option>
            <option value="2">Teleconsulta</option>
          </select>
        </div>
      </div>
    </div>

    <!--  -->
    {{-- ORDENES DE LABORATORIO 1ERO CITA ORIGEN --}}
    <div class="panel panel-info" id="listOrdenesLaboratorioCitaOrigen" style="display: none;">
        <div class="panel-heading">
            <h3 class="panel-title size-titulo">
                Citas con Orden de Laboratorio Inicial
            </h3>
        </div>
        <div class="panel-body" style="overflow-y: scroll;">
            <table id="tblOrdenesLaboratorioCitaOrigen" class="table"
                cellspacing="0" width="100%" style="font-size: 9px;">
                <thead>
                    <tr>
                        <th style="text-align: left;">Nro. Orden</th>
                        <th style="text-align: left;">Fecha. Orden</th>
                        <th style="text-align: left;">Tipo Atención</th>
                        <th style="text-align: left;">servicio</th>
                        <th style="text-align: left;">Consultorio</th>
                        <th style="text-align: left;">Médico</th>
                        <th style="text-align: left;">CPMS</th>
                        <th style="text-align: left;">Visualizar Orden</th>
                        <th style="text-align: left;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ORDENES DE LABORATORIO 2DO CITA ORIGEN CON CITA RESULTADO --}}
    <div class="panel panel-info" id="listOrdenesLaboratorioCitaOrigenCitaResultado" style="display: none;">
        <div class="panel-heading">
            <h3 class="panel-title size-titulo">
                Citas con Orden de Laboratorio Inicial con Cita de Resultado
            </h3>
        </div>
        <div class="panel-body" style="    overflow-y: scroll;">
            <table id="tblOrdenesLaboratorioCitaOrigenCitaResultado"
                class="table" cellspacing="0" width="100%" style="">
                <thead>
                    <tr>
                        <th style="text-align: left;">Nro. Orden</th>
                        <th style="text-align: left;">Fecha. Orden</th>
                        <th style="text-align: left;">Tipo Atención</th>
                        <th style="text-align: left;">servicio</th>
                        <th style="text-align: left;">Consultorio</th>
                        <th style="text-align: left;">Médico</th>
                        <th style="text-align: left;">Próxima Cita</th>
                        <th style="text-align: left;">CPMS</th>
                        <th style="text-align: left;">Visualizar Orden</th>
                        <th style="text-align: left;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!--  -->

  </div>

  <div class="col-md-6">
    <div style="display:flex; justify-content: center ">
          <div class="col-md-8" id="calendar"></div>
      </div>
  </div>

  <ul class="nav nav-tabs app-mt-4" role="tablist">
    <li role="presentation" class="active"><a href="#tabcita" aria-controls="tabcita" role="tab" data-toggle="tab">CITAS DE EMERGENCIA Y REFERENCIA</a></li>
    {{--<li role="presentation"><a href="#tabhistorial" aria-controls="tabhistorial" role="tab" data-toggle="tab">HISTORIAL DE CITA</a></li>--}}
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tabcita">
      {{-- <div class="app-panel-title app-mt-4 app-mb-3"><strong>PROGRAMACIONES MEDICAS</strong></div>--}}
      <div id='divHorario'></div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tabhistorial">
      {{-- <div class="app-panel-title"><strong>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</strong></div> --}}
      <div class="form-group">
        <div id='divHistorial'></div>
      </div>
    </div>
  </div>
  <hr style="clear: both;">
</form>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-header" id="modal-header">
          {{--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>--}}
          <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
          <div id="alerta" class="alert alert-danger"></div>
        </div>
        <div class="modal-body">
          <table border="0px" id="tabla_lote">
            <thead>
              <tr>
                <th style="width:10%">CÓdigo</th>
                <th style="width:40%">Nombre</th>
                <th style="width:10%">Lote</th>
                <th style="width:10%">Reg. San.</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Fec. ven.</th>
                <th style="width:10%">Cant. Actual.</th>
                <th style="width:10%">Cant. Entregar</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <div class="btn-group btn-group-justified" role="group" aria-label="group button">
            <div class="btn-group" role="group">
              <div class="col-md-6 text-center">
                <button type="button" onclick="sumarCantidadLotes(0)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar</button>
              </div>
              <div class="col-md-6 text-center">
                <button type="button" onclick="sumarCantidadLotes(1)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar y Agregar Nueva
                  Fila</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="openOverlayOpc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">
      <div class="modal-body">
        <div id="diveditpregOpc"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showProximasCitas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">

      <div class="modal-body">
        <div id="diveditpreg"></div>
      </div>

  </div>
</div>

<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 900px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes
            actual</small></h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th><small>Atenciones</small></th>
                <th><small>Fecha <br />Dispensación</small></th>
                <th><small>Fecha <br />Expedición</small></th>
                <th><small>Consultorio</small></th>
                <th><small>Farmacia</small></th>
                <th><small>Médico</small></th>
                <th><small>Diagnóstico</small></th>
                <th><small>Nro. Aten-<br>ciones</small></th>
                <th><small>Detalle<br />Producto</small></th>
              </tr>
            </thead>
            <tbody id="muestra-atenciones"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showAtencionesDetMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 900px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="font-size: 22px; color: #fff">Nro. Atenciones según fecha de expedición</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered" style="font-size: 13px;">
            <thead>
              <tr>
                <th><small>Nro. <br />Atención</small></th>
                <th><small>Farmacia</small></th>
                <th><small>Fecha <br />Expedición</small></th>
                <th><small>Consultorio</small></th>
                <th><small>Médico</small></th>
                <th><small>Nro Receta</small></th>
                <th><small>Fecha <br />Dispensación</small></th>
                <th><small>Detalle<br />Producto</small></th>
              </tr>
            </thead>
            <tbody id="muestra-det-atenciones"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th><small>Código</small></th>
                <th><small>Descipción</small></th>
                <th><small>Unidad</small></th>
                <th><small>Pedido</small></th>
                <th><small>Despachado</small></th>
              </tr>
            </thead>
            <tbody id="muestra-productos"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showStockEstablecimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width: 900px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><small style="color: #fff">PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</small></h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th><small>Nro</small></th>
                <th><small>Farmacia</small></th>
                <th><small>Código</small></th>
                <th><small>Producto Farmacéutico o Dispositivo Médico</small></th>
                <th><small>Unidad</small></th>
                <th><small>Stock Actual</small></th>
              </tr>
            </thead>
            <tbody id="muestra-stock"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>