@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
<!-- <script>
  tinymce.init({
    selector: '#mytextarea'
  });
</script> -->
<link rel="stylesheet" href="{{ asset('sceditor/minified/themes/office.min.css') }}" id="theme-style" />
<style>

.title-dialog {
  font-size: 18px;
  font-family:Arial, Helvetica, sans-serif;
}
/* .table th, .table td {
    border: none !important;
} */
    
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

input.id_pres_proc {
    transform: scale(1.0) !important;
    margin-right: 4px;
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

    .swal2-icon.swal2-warning {
        font-size: 15px !important; 
    }

.table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
  
  padding: 2px 8px !important;
  border: 0px solid;
}
  .table-borderless{
         margin-bottom: 0px !important;
  }

#modalMotivoObservacion .modal-dialog {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
    width: 600px !important;
    max-width: 95% !important;
}

</style>

<script>
  function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) && (keynum == 9))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
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
    	
	// jQuery(document).on('focus', '.select2', function() {
    // 	jQuery(this).siblings('select').select2('open');
	// });


// return the id of the next focusable field
function getNextFocusableFieldId(idIn) {
    var focusables = jQuery("input, select, textarea");
    var reachedId = false;
    var id = '';
    var nextId = '';
    jQuery.each(focusables, function(index, value) {
        id = jQuery(this).attr('id');
        // if we reached the id last time set the nextId and exit each
        if (reachedId) {
            nextId = id;
            return false;
        }
        // if the ids match set the flag for the next iteration
        if (id == idIn) {
            reachedId = true;
        }
    });
    return nextId;
}

	//--------------------------------------------------------------------------
        $(document).ready(function () {
            $("#divexamenplantilla").hide();
            $("#divexamenplantilla2").hide();

            $cmbPlantilla = $("#frmRadiologia").find("#id_plantilla");
            $cmbPlantilla.select2();
            $cmbPlantilla7 = $("#frmRadiologia").find("#id_plantilla1");
            $cmbPlantilla7.select2();
            $cmbPlantilla6 = $("#frmRadiologia").find("#id_examen_plantilla_2");
            $cmbPlantilla6.select2();

            $cmbPlantilla.change(function () {
                cmbPlantilla = $cmbPlantilla.val();
                const url = "{{ url('cargarplantilla') }}";
                $.ajax({
                type: "GET",
                url: `${url}/${cmbPlantilla}`,
                data: {}
                }).done(function( msg ) {
                    var textarea = document.getElementById('parrafo1');
                    var instance = sceditor.instance(textarea);
                    instance.insert(msg);
                });
            });
            
        
            $cmbPlantilla7.change(function () {
                cmbPlantilla7 = $cmbPlantilla7.val();
                let textarea = document.getElementById('parrafo1');
                let instance = sceditor.instance(textarea);
                if (cmbPlantilla7 == '') {
                    console.log('VALUE DE LA PLANTILLA VACIO');
                    instance.val('');
                    $('#parrafo1').val('');
                } else {
                    console.log('VALUE DE PLANTILLA 1 CORRECTA');
                    const url = "{{ url('cargarplantilla') }}";
                    $.ajax({
                    type: "GET",
                    url: `${url}/${cmbPlantilla7}`,
                    data: {}
                    }).done(function( msg ) {
                        instance.val('');
                        $('#parrafo1').val('');
                        instance.insert(msg);
                    });
                }
            });

            modalRadiologico();
            modalLaboratorio();

            const radiologia = {!! json_encode($radiologia) !!}
            if (radiologia && !radiologia.cod_siteds_susalud) {
                generarCodigoSiteds();
            }
        });
    function validarFormulario() {
        const observacion = $('#observacion_examen').val();
        const tipo_registro = $('#tipo_registro').val();
        const bool_contenedor_profesional_imagenes = $('#bool_contenedor_profesional_imagenes').val();
        let id_plantilla = "";
        let msg = "";
        let sw = true;

        const pruebasCheckeadas = document.querySelectorAll('input[type=checkbox]:checked'); 
        total_proc= {{count($procradiologia)}};
        console.log(`Checklist de Examenes : ${pruebasCheckeadas}`);
        console.log(`Total de Examenes : ${pruebasCheckeadas.length}`);
        console.log(`Total de Examenes 2 : ${total_proc}`);
        if (tipo_registro == '6') {
            if(observacion==""){
                msg+="Debe ingresar una observación.<br>";
                sw = false;
            }
            if (bool_contenedor_profesional_imagenes == 'false') { //valida desmarco todos los examnes para cambiar el estado y no registrar los informes 
                if(observacion==""){
                    msg+="Debe ingresar una observación.<br>";
                    sw = false;
                }
            }
        }
        if (tipo_registro == '1' || tipo_registro == '7') {
            if (bool_contenedor_profesional_imagenes == 'true') {  
                if (total_proc == 1) { //Tiene 1 examen registrado en la orden de atención
                    id_plantilla = $('#id_plantilla1').val();
                    let parrafo1 = sceditor.instance($('#parrafo1').get(0)).val();
                    if ($.trim(parrafo1) === '<p><br></p>') {
                        msg += 'Falta completar el informe.<br>';
                        sw = false;
                    } else {
                        $('#parrafo1').val(sceditor.instance($('#parrafo1').get(0)).val());
                    }
                } 
            }
            if (bool_contenedor_profesional_imagenes == 'false') { //valida desmarco todos los examnes para cambiar el estado y no registrar los informes 
                //ningun item seleccionado
                if(observacion==""){
                    msg+="Debe ingresar una observación.<br>";
                    sw = false;
                }
            }
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

    const limpiarContenedorPorOpcion = (op) => {
        switch (op) {
            case 0:
                console.log(`Opcion 0`);
                resetSelectAndTriggerChange('id_plantilla1');
                $("#divexamenplantilla2").empty();
                $("#divexamenplantilla").hide();
                $("#divexamenplantilla2").hide();
                break;
            case 1: // Todos los examenes en una sola plantilla
                console.log(`Opcion 1`);
                $("#divexamenplantilla2").empty();
                $("#divexamenplantilla").show();
                $("#divexamenplantilla2").hide();
                break;
            case 2: // Una Plantilla por Examen
                console.log(`Opcion 2`);
                resetSelectAndTriggerChange('id_plantilla1');
                $("#divexamenplantilla").hide();
                break;
        }
    }
        
    function getMostrarExamenPlantilla(){

        let examen_plantilla = $('#id_examen_plantilla_2').val();

        if (examen_plantilla == '') {
            limpiarContenedorPorOpcion(0);
        } else if(examen_plantilla == '1'){ // Todos los examenes en una sola plantilla
            limpiarContenedorPorOpcion(1);
        } else if(examen_plantilla == '2'){ // Una Plantilla por Examen
            limpiarContenedorPorOpcion(2);
            const dataArray = [];
            let url = '';

            $('input[name="id_pres_proc[]"]:checked').each(function() { // Seleccionamos solo los inputs que están seleccionados (checked)
                let id_pres_proc = $(this).data('id');
                dataArray.push(id_pres_proc);
            });

            url = "{{ route('list_plantillas_informes',['dataArray' => ':dataArray']) }}";

            dataString = dataArray.join(',');

            url = url.replace(':dataArray',dataString)
            
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $("#divexamenplantilla2").html(response);
                },
                error: function(e) {
                    console.log(`route - list_plantillas_informes : ${e.message}`);
                }
            });
            $("#divexamenplantilla2").show();
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

    $('#addInforme').on('click', function () {
		let addInforme = $('#addInforme').attr("aria-pressed");
		$("#fsInforme").hide();
        resetSelectAndTriggerChange('id_examen_plantilla_2')
		if(addInforme == "false"){
			$("#fsInforme").show();
		}
	});

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
    let ultimoExamenDesmarcado = null;

    $(document).on('change', '.id_pres_proc', function() {

        const totalMarcados = $('.id_pres_proc:checked').length;
        const anyChecked = totalMarcados > 0;

        $('#cantidad_examenes').text(totalMarcados);

        if ( $('#tipo_registro').val() == '1' || $('#tipo_registro').val() == '7' ) {
            if (anyChecked) {
                $('#contenedor-profesional-imagenes').show();
                $('#bool_contenedor_profesional_imagenes').val('true');
                console.log(`Hay al menos un checkbox marcado.`);
            } else {
                $('#contenedor-profesional-imagenes').hide();
                $('#bool_contenedor_profesional_imagenes').val('false');
                console.log(`Todos los checkboxes están desmarcados.`);
            }
        }

        if (anyChecked) {
            if ($('#observacion_examen').data('desde-motivo')) {
                $('#observacion_examen').val('').data('desde-motivo', false);
            }
            $('#observacion_examen').prop('readonly', false).css('background-color', '');
        } else {
            ultimoExamenDesmarcado = this;
            $('#observacion_examen').prop('readonly', true).css('background-color', '#eee');
            abrirModalMotivoObservacion();
        }
    });

    function abrirModalMotivoObservacion() {
        $('input[name="motivo_observacion_examen"]').prop('checked', false);
        $('#motivo_observacion_detalle').val('').prop('disabled', true).hide();
        $('#btnGuardarMotivoObservacion, #btnCancelarMotivoObservacion').prop('disabled', false);
        $('#modalMotivoObservacion .modal-dialog').css('width', '600px');
        $('#modalMotivoObservacion').modal({ backdrop: 'static', keyboard: false, show: true });
    }

    function detalleOtrosValido() {
        const letras = ($('#motivo_observacion_detalle').val() || '').match(/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/g);
        return !!letras && letras.length >= 5;
    }

    function actualizarBtnGuardarMotivo() {
        const motivo = $('input[name="motivo_observacion_examen"]:checked').val();
        $('#btnGuardarMotivoObservacion').prop('disabled', motivo === 'Otros' && !detalleOtrosValido());
    }

    $(document).on('change', 'input[name="motivo_observacion_examen"]', function () {
        $('input[name="motivo_observacion_examen"]').not(this).prop('checked', false);
        const esOtros = $(this).val() === 'Otros';
        $('#motivo_observacion_detalle').prop('disabled', !esOtros);
        if (esOtros) {
            $('#motivo_observacion_detalle').show().focus();
        } else {
            $('#motivo_observacion_detalle').val('').hide();
        }
        actualizarBtnGuardarMotivo();
    });

    $(document).on('input', '#motivo_observacion_detalle', actualizarBtnGuardarMotivo);

    $(document).on('click', '#btnGuardarMotivoObservacion', function () {
        const motivo  = $('input[name="motivo_observacion_examen"]:checked').val();
        const detalle = motivo === 'Otros' ? $.trim($('#motivo_observacion_detalle').val()) : '';

        if (!motivo) {
            Swal.fire({ icon: 'warning', title: 'Alerta', html: 'Debe seleccionar un motivo de observación.' });
            return;
        }
        if (motivo === 'Otros' && !detalleOtrosValido()) {
            return;
        }

        const observacion = detalle !== '' ? `${motivo}: ${detalle}` : motivo;

        const ids_pp = $('input[name="id_pres_proc_total[]"]').map(function () { return $(this).val(); }).get();

        $('#btnGuardarMotivoObservacion, #btnCancelarMotivoObservacion').prop('disabled', true);

        $.ajax({
            url: "{{ route('registrar_observacion_examen_imagenes') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                id_radiologia: $('#id_radiologia').val(),
                id_pres_proc_total: ids_pp,
                obs_imagenes: observacion
            },
            success: function (resp) {
                $('#modalMotivoObservacion').modal('hide');
                ultimoExamenDesmarcado = null;
                Swal.fire({
                    title: 'Examen observado. Volviendo al listado...',
                    html: '<img src="{{ asset('images/loader.gif') }}" width="60" height="60">',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                window.location.href = resp.redirect;
            },
            error: function (xhr) {
                const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'No se pudo registrar la observación.';
                Swal.fire({ icon: 'error', title: 'Error', html: msg });
                $('#btnGuardarMotivoObservacion, #btnCancelarMotivoObservacion').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '#btnCancelarMotivoObservacion', function () {
        $('#modalMotivoObservacion').modal('hide');
        if (ultimoExamenDesmarcado) {
            $(ultimoExamenDesmarcado).prop('checked', true).trigger('change');
            ultimoExamenDesmarcado = null;
        }
    });
    const modalLaboratorio= () =>{
	
        const dni_beneficiario = $("#nro_dni_paciente").val();
        const url = "{{ url('modal_historial_laboratorio') }}";
        $.ajax({
                url: `${url}/${dni_beneficiario}`,
                type: "GET",
                success: function (result) {  
                        $("#divLaboratorio").html(result);
                        $('#tablemodalm1').DataTable({
                            "sPaginationType": "full_numbers",
                            "language": {"url": "/js/Spanish.json"},
                        });
                }
        });
    }
    const modalRadiologico = () => {
        const dni_beneficiario = $("#nro_dni_paciente").val();
        //console.log("DNI del beneficiario:", dni_beneficiario);
        const url = "{{ url('modal_historial_radiologia') }}";
        $.ajax({
            url: `${url}/${dni_beneficiario}`,
            type: "GET",
            success: function (result) {
                $("#divRadiologico").html(result);
                try {
                    $('#tablemodalm2').DataTable({
                        "sPaginationType": "full_numbers",
                        "language": { "url": "/js/Spanish.json" },
                    });
                } catch (err) {
                    console.error("Error al inicializar DataTable:", err);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error AJAX:", status, error);
                $("#divRadiologico").html("<div class='alert alert-danger'>No se pudo cargar el historial.</div>");
            }
        });
    }

    function cancelarFormulario() {
        const id_cita = $('#id_cita').val();
        cancela_llamada(id_cita);
    }

    function cancela_llamada(id_cita){
        if (id_cita == '0') {
            const url2 = "{{ url('all_radiologia_prescritas_by_ipress') }}";
            window.location.href = url2;
        } else {
            const url = "{{ url('cancela_llamada_imagenes') }}";
            $.ajax({
            url: `${url}/${id_cita}`,
            dataType: "json",
            success: function(result){
                console.log(result);
                
                if(result.update > 0){

                    Swal.fire({
                        title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                        html: '<p class="descripcion-alert"><span><b>PROCESO DE LLAMADO, POSPUESTO.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 700,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            const url2 = "{{ url('all_radiologia_prescritas_by_ipress') }}";
                            window.location.href = url2;
                        }
                    }); 
                
                }
            }
            });
        }
    }
    
    // Inicialmente empuja el estado en la pila del historial
    history.pushState(null, null, window.location.href);

    window.addEventListener("popstate", function(event) {
        // Pregunta de confirmación

        Swal.fire({
        title: `<span style="padding-top : 1rem;">Desea cancelar el registro?</span>`,
        text: "",
        showCancelButton: true,
        confirmButtonColor: "#00a65a",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI",
        customClass: {
            header : 'swal2-header-popstate'
        }
        }).then((result) => {
        if (result.isConfirmed) {
            const id_cita=$('#id_cita').val(); 
            const url = "{{ url('cancela_llamada_imagenes') }}";
            $.ajax({
            url: `${url}/${id_cita}`,
            dataType: "json",
            success: function(result){
                console.log(result);
                
                if(result.update > 0){

                    Swal.fire({
                        title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                        html: '<p class="descripcion-alert"><span><b>PROCESO DE LLAMADO, POSPUESTO.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 700,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.history.back();
                        }
                    }); 
                
                }
            }
            });
        } else {
            // Si el usuario cancela, volvemos a empujar el estado para bloquear la navegación
            history.pushState(null, null, window.location.href);
        }
        });
        
    });

    function generarCodigoSiteds() {
        const prestacion = {!! json_encode($prestacion) !!}
        Swal.fire ({
            title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO CÓDIGO SITEDS</span></div>",
            html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })
        const id_cita = $('#nro_prestacion').val();
        const id_asegurado = prestacion.id_asegurado;
        const id_servicio = prestacion.id_consultorio || 61;
        const id_establecimiento = $('#id_establecimiento').val();
        const body = {
            "idCorrelativo": id_cita,
            "aseguradoId": id_asegurado,
            "establecimientoId": id_establecimiento,
            "tipoAtencionId": 1,
            "servicioId": 9
        };
        $.ajax({
            url:'/v2/ws-dirsapol-siteds',
            type:'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(body),
            success: (result, textStatus, jqXHR) => {
                Swal.close();
                const nuAutorizacion = result.nuAutorizacion;
                if (result && result.nuAutorizacion) {
                    $("#cod_siteds_susalud").val(result.nuAutorizacion);
                    const flagSitedsTemp = isSitedsTemp(result.nuAutorizacion);
                    if (flagSitedsTemp) {

                        let message = result.message || "El código SITEDS generado es temporal."
                        const session = {!! json_encode(Auth::user()) !!}
                        let hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                        if (hasPermissionUpdateSitedsManual) {
                            message += "<br><br><span style='font-size: 15px; color: #d9534f'>Sin embargo, puedes ingresar manualmente al SITEDS, pero recuerda que cualquier acción será bajo tu responsabilidad.</span>";
                            $("#cod_siteds_susalud").prop("readonly", false); 
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO CÓDIGO SITEDS</span></div>",
                            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${message}</span></div>`,
                            confirmButtonText: 'ENTENDIDO',
                        });
                    }
                }
            },
            error: function(xhr, status, error){
                const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                Swal.fire({
                    icon: 'error',
                    title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO CÓDIGO SITEDS</span></div>",
                    html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
            }, 
            complete: function(xhr, status) {
                Swal.hideLoading();
            }
        })
    }

    function isSitedsTemp(codigo) {
        return codigo.length < 4 || codigo.endsWith("T");
    }

    //PARA CAPTURAR EL VALOR OCULTO DEL HIDDEN DE COD SITEDS ==========================================
    const hiddenInput = document.getElementById('cod_siteds_susalud');
    const span = document.querySelector('.cod_siteds_susalud');

    const observer = new MutationObserver(() => {
    span.textContent = hiddenInput.value;
    });

    observer.observe(hiddenInput, { attributes: true, attributeFilter: ['value'] });

    span.textContent = hiddenInput.value;
    //=====================================================================================================
    function modalForm(id_cita, id_pp) {
        $(".modal-dialog").css("width", "80%");
        $('#modalForm').modal('show');
        $('#modalForm .modal-body').css('height', 'auto');
        const url = "{{ url('modal_comentario_cita_imagenes') }}";
        $.ajax({
            url: `${url}/${id_cita}/${id_pp}`,
            type: "GET",
            success: function(result) {
                $("#modalForm .modal-body").html(result);
            }
        });
    }
</script>
<style>
  .modal-dialog{
    width: 850px !important;
  }    
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
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
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
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari y Chrome */
    -o-transform: scale(2); /* Opera */
    padding: 10px;
    }

    .checkboxtexto
    {
    /* Checkbox texto */
    font-size: 130%;
    display: inline;
    }

    .btn-toggle {
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
    .btn-toggle:focus,
    .btn-toggle.focus,
    .btn-toggle:focus.active,
    .btn-toggle.focus.active {
        outline: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
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
    .btn-toggle:before {
        content: 'NO';
        left: -4rem;
    }
    .btn-toggle:after {
        content: 'SI';
        right: -4rem;
        opacity: 0.5;
    }
    .btn-toggle>.handle {
        position: absolute;
        top: 0.1875rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.active>.handle {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle.active:before {
        opacity: 0.5;
    }
    .btn-toggle.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm:before,
    .btn-toggle.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs:before,
    .btn-toggle.btn-xs:after {
        display: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
        color: #6b7381;
    }
    .btn-toggle.active {
        background-color: #29b5a8;
    }
    .btn-toggle.btn-lg {
        margin: 0 5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 2.5rem;
        width: 5rem;
        border-radius: 2.5rem;
    }
    .btn-toggle.btn-lg:focus,
    .btn-toggle.btn-lg.focus,
    .btn-toggle.btn-lg:focus.active,
    .btn-toggle.btn-lg.focus.active {
        outline: none;
    }
    .btn-toggle.btn-lg:before,
    .btn-toggle.btn-lg:after {
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
    .btn-toggle.btn-lg:before {
        content: 'NO';
        left: -5rem;
    }
    .btn-toggle.btn-lg:after {
        content: 'SI';
        right: -5rem;
        opacity: 0.5;
    }
    .btn-toggle.btn-lg>.handle {
        position: absolute;
        top: 0.3125rem;
        left: 0.3125rem;
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 1.875rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-lg.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-lg.active>.handle {
        left: 2.8125rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-lg.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-lg.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-lg.btn-sm:before,
    .btn-toggle.btn-lg.btn-sm:after {
        line-height: 0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.6875rem;
        width: 3.875rem;
    }
    .btn-toggle.btn-lg.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-lg.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-lg.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-lg.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-lg.btn-xs:before,
    .btn-toggle.btn-lg.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-sm {
        margin: 0 0.5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 1.5rem;
        width: 3rem;
        border-radius: 1.5rem;
    }
    .btn-toggle.btn-sm:focus,
    .btn-toggle.btn-sm.focus,
    .btn-toggle.btn-sm:focus.active,
    .btn-toggle.btn-sm.focus.active {
        outline: none;
    }
    .btn-toggle.btn-sm:before,
    .btn-toggle.btn-sm:after {
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
    .btn-toggle.btn-sm:before {
        content: 'NO';
        left: -0.5rem;
    }
    .btn-toggle.btn-sm:after {
        content: 'SI';
        right: -0.5rem;
        opacity: 0.5;
    }
    .btn-toggle.btn-sm>.handle {
        position: absolute;
        top: 0.1875rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-sm.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-sm.active>.handle {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-sm.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm.btn-sm:before,
    .btn-toggle.btn-sm.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle.btn-sm.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-sm.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-sm.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-sm.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm.btn-xs:before,
    .btn-toggle.btn-sm.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-xs {
        margin: 0 0;
        padding: 0;
        position: relative;
        border: none;
        height: 1rem;
        width: 2rem;
        border-radius: 1rem;
    }
    .btn-toggle.btn-xs:focus,
    .btn-toggle.btn-xs.focus,
    .btn-toggle.btn-xs:focus.active,
    .btn-toggle.btn-xs.focus.active {
        outline: none;
    }
    .btn-toggle.btn-xs:before,
    .btn-toggle.btn-xs:after {
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
    .btn-toggle.btn-xs:before {
        content: 'NO';
        left: 0;
    }
    .btn-toggle.btn-xs:after {
        content: 'SI';
        right: 0;
        opacity: 0.5;
    }
    .btn-toggle.btn-xs>.handle {
        position: absolute;
        top: 0.125rem;
        left: 0.125rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 0.75rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-xs.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-xs.active>.handle {
        left: 1.125rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-xs.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-xs.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs.btn-sm:before,
    .btn-toggle.btn-xs.btn-sm:after {
        line-height: -1rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.275rem;
        width: 1.55rem;
    }
    .btn-toggle.btn-xs.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-xs.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-xs.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-xs.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs.btn-xs:before,
    .btn-toggle.btn-xs.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-secondary {
        color: #6b7381;
        background: #bdc1c8;
    }
    .btn-toggle.btn-secondary:before,
    .btn-toggle.btn-secondary:after {
        color: #6b7381;
    }
    .btn-toggle.btn-secondary.active {
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
</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:80%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-default filterable">            
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tabRegistroImagenes" data-toggle="tab" style="font-size:12px">REGISTRAR EXAMEN</a></li>
                            <li><a href="#tabHistorialImagenes" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE IMAGENOLOGIA</a></li>
                            <li><a href="#tabHistorialLaboratorio" data-toggle="tab" style="font-size:12px">HIST. DE EXAMENES DE LABORATORIO</a></li>
                        </ul>      
                    </div>
                    <div style=" font-size: 11px" class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tabRegistroImagenes">
                                <form name="frmRadiologia" id="frmRadiologia" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_radiologia_informe') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" value="<?php echo $radiologia->id?>" name="nro_prestacion" id="nro_prestacion">
                                    <input type="hidden" value="<?php echo $radiologia->id_prestacion?>" name="id_prestacion" id="id_prestacion">
                                    <input type="hidden" value="<?php echo $radiologia->id_establecimiento?>" name="id_establecimiento" id="id_establecimiento">
                                    <input type="hidden" value="<?php echo $radiologia->id?>" name="id_radiologia" id="id_radiologia">
                                    <input type="hidden" value="{{$id_servicio_radiologia}}" name="id_servicio_radiologia" id="id_servicio_radiologia">
                                    <input type="hidden" value="{{ $flagfiscalizador }}" name="flagfiscalizador" id="flagfiscalizador">
                                    <input type="hidden" value="{{count($procradiologia)}}" name="total_examenes" id="total_examenes">
                                    <input type="hidden" value="{{ auth()->user()->tipo_registro }}" name="tipo_registro" id="tipo_registro">
                                    <input type="hidden" value="true" name="bool_contenedor_profesional_imagenes" id="bool_contenedor_profesional_imagenes">
                                    <input type="hidden" value="{{ $id_cita }}" id="id_cita" name="id_cita" />
                                    <!-- <div class="row" style="padding-bottom: 1rem;">
                                        <div class="col-md-4 text-center">
                                            <img src="{{ asset('images/logo_sanidad.png') }}" width="50" height="auto">
                                        </div>
                                        <div class="col-md-4" style="text-align: center">
                                            <label><-?php echo $prestacion->establecimiento ?></label><br>
                                            <label style="font-size: 24px">ANALISIS DE IMAGENOLOGÍA</label><br>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <img src="{{ asset('images/logo_sanidad.png') }}" width="50" height="auto">
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <?php 
                                            $disabled_radiologia = "";
                                            if($radiologia->ruta_radiologia==""){
                                                $disabled_radiologia = "disabled='disabled'";
                                                echo '<div class="col-sm-12">
                                                            <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                                                                El examen radiologico  aun no ha sido firmada por el m&eacute;dico
                                                            </div>
                                                        </div>';
                                            }
                                        ?>
                                    </div>

                                    <div class="row">
                                        <!-- Columna izquierda: Datos del Paciente -->
                                        <div class="col-md-6" style="height: 100%;">
                                            <div class="panel panel-info" style="height: 100%;">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                                </div>
                                                <div class="panel-body" style="margin: 0px 10px;">
                                                    <div class="row">
                                                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                            <tr>
                                                                <th style="text-align: left; width: 20%; border: 0px;"><strong>N° DE DOCUMENTO</strong></th>
                                                                <th style="text-align: left; width: 1%; border: 0px;"><strong>:</strong></th>
                                                                <input id="nro_dni_paciente" type="hidden" value="{{ $prestacion->nro_doc_ident }}" />
                                                                <td style="border: 0px; width: 34%;">
                                                                    <span id="nro_doc_ident" style="font-weight: bold; font-size: 13px;">
                                                                        {{ $prestacion->tipo_doc_ident }} - <?php echo isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?>
                                                                    </span>
                                                                </td>
                                                                <th style="text-align: left; width: 8%; border: 0px;"><strong>N° H.C</strong></th>
                                                                <th style="text-align: left; width: 1%; border: 0px;"><strong>:</strong></th>
                                                                <td style="border: 0px; width: 20%; text-transform: uppercase;">
                                                                    <span id="nro_historia" style="font-weight: bold;">
                                                                        <?php echo isset($prestacion->nro_historia) ? $prestacion->nro_historia : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia" style="font-weight: bold;font-size: 13px;"><?php  echo (isset($prestacion->asegurado_paterno) ? $prestacion->asegurado_paterno : '') . " " . 
                                                                                (isset($prestacion->asegurado_materno) ? $prestacion->asegurado_materno : '') . ", " . 
                                                                                (isset($prestacion->asegurado_nombre) ? $prestacion->asegurado_nombre : ''); ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                    <?php  echo isset($prestacion->fecha_nac) ? $prestacion->fecha_nac : ''; ?>
                                                                    - <strong>EDAD (
                                                                                        <?php
                                                                            $fecha_nac = DateTime::createFromFormat('d/m/Y', $prestacion->fecha_nac);
                                                                            $hoy = new DateTime();

                                                                            if ($fecha_nac !== false) {
                                                                                $edad = $fecha_nac->diff($hoy);
                                                                                echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                                            } else {
                                                                                echo "Formato de fecha inválido";
                                                                            }
                                                                            ?>
                                                                        )</strong>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>SEXO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        <?php echo isset($prestacion->sexo) ? ($prestacion->sexo == "F" ? "Femenino":"Masculino") : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        <?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>CONDICIÓN</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        {{ $referencia == 'NO' ? 'REFERIDO' : 'NO REFERIDO' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <!-- Columna derecha: Datos Generales -->
                                        <div class="col-md-6">
                                            <div class="panel panel-info contenedor-datos-generales">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                                                </div>
                                                <div class="panel-body" style="margin: 0px 10px;">
                                                    <table class="table table-borderless" style="width: 100%;MARGIN: 0px;">
                                                        <tr>
                                                            <th><strong>COD.SITEDS SUSALUD</strong></th>
                                                            <th>:</th>                        
                                                            <td style="text-transform: uppercase;">
                                                                @if (isset($radiologia->cod_siteds_susalud))
                                                                    <span id="cod_siteds_susalud">{{ $radiologia->cod_siteds_susalud }}</span>
                                                                @else
                                                                    <span class="cod_siteds_susalud"></span>
                                                                    <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud" onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important" maxlength="12" pattern="[0-9]*">
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 35%;"><strong>TIPO ATENCIÓN</strong></th>
                                                            <th style="width: 1%;">:</th>
                                                            <td>
                                                                <?php
                                                                    switch ($prestacion->id_tipo_atencion) {
                                                                    case '1':$tipo_atencion = "AMBULATORIO"; break;
                                                                    case '2':$tipo_atencion = "EMERGENCIA"; break;
                                                                    case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                                                                    case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                                                                    case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if ($prestacion->id_tipo_atencion == 2 && $radiologia->id_evaluacion > 0){
                                                                        $tipo_atencion = "EMERGENCIA - EVALUACION";                                                                    
                                                                    }elseif($prestacion->id_tipo_atencion == 3 && $radiologia->id_evaluacion > 0){
                                                                        $tipo_atencion = "HOSPITALIZACION - EVOLUCION";
                                                                    }else{
                                                                        $tipo_atencion = $prestacion->tipo_atencion;
                                                                    }
                                                                        echo '<span id="tipo_atencion" style="font-size: 14px;font-weight: bold;">' . $tipo_atencion . '</span>';
                                                                ?>
                                                            </td>
                                                        </tr>
                                                
                                                        @if($id_tipo_atencion== 2)
                                                        <tr>
                                                            <th style="color:red;"><strong>PRIORIDAD</strong></th>
                                                            <th style="color:red;">:</th>
                                                            <td style="text-transform: uppercase;color:red;">
                                                                <span id="prioridad"><?php echo isset($prestacion->prioridades) ? $prestacion->prioridades : ''; ?></span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                                            <th>:</th>
                                                            <td style="text-transform: uppercase;">
                                                                <span id="servicio"><?php echo isset($prestacion->servicio) ? $prestacion->servicio : ''; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><strong>FECHA ATENCIÓN</strong></th>
                                                            <th>:</th>
                                                            <td>                                                              
                                                                @if($id_tipo_atencion == 1)
                                                                    <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                @elseif($id_tipo_atencion == 2)
                                                                    @if(!empty($prestacion->id_evaluacion))
                                                                        <span id="fecha_atencion"><?php echo isset($prestacion->fecha_evaluacion) ? date('d/m/Y', strtotime($prestacion->fecha_evaluacion)) : ''; ?></span>
                                                                    @else
                                                                        <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                    @endif
                                                                @elseif($id_tipo_atencion == 3)
                                                                    @if(!empty($evolucion->id_evaluacion))
                                                                        <span id="fecha_atencion"><?php echo isset($evolucion->fecha_evolucion) ? date('d/m/Y', strtotime($evolucion->fecha_evolucion)) : ''; ?></span>
                                                                    @else
                                                                        <span id="fecha_atencion"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if($id_tipo_atencion == 2 || $id_tipo_atencion==3)
                                                        <tr>
                                                            <th><strong>UBICACIÓN DE PACIENTE</strong></th>
                                                            <th>:</th>
                                                            <td>
                                                                <!--?php dd($prestacion)?-->
                                                                <span id="ubicacion" style="font-size: 14px;font-weight: bold;"><?php echo isset($prestacion->ubicacion) ? $prestacion->ubicacion : ''; ?> / <strong> CAMA: <?php echo isset($prestacion->nmro_cama) ? $prestacion->nmro_cama : ''; ?> </strong> </span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th><strong>PROFESIONAL DE SALUD</strong></th>
                                                            <th>:</th>
                                                            <td style="text-transform: uppercase;">
                                                                <span id="dni_medico"><b></b><strong>{{$prestacion->descripcion}} -</strong></th>
                                                                <?php echo isset($prestacion->dni) ? $prestacion->dni : ''; ?>
                                                                - <?php 
                                                                    echo (isset($prestacion->medico_paterno) ? $prestacion->medico_paterno : '') . " " . 
                                                                        (isset($prestacion->medico_materno) ? $prestacion->medico_materno : '') . ", " . 
                                                                        (isset($prestacion->medico_nombre) ? $prestacion->medico_nombre : ''); 
                                                                ?>
                                                                (<?php echo isset($prestacion->profesion) ? $prestacion->profesion : ''; ?>)
                                                                / <b>N.Coleg: </b>{{$prestacion->colegiatura}}
                                                            
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th colspan="3">
                                                        
                                                            </th>
                                                        </tr> -->
                                                    
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>                                      
                                    <div class="panel panel-info" style="height: 100%;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PROFESIONAL QUE REGISTRA EXAMEN</h3>
                                        </div>
                                        <div class="panel-body" style="margin: 0px 10px;">
                                            <div class="row">
                                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                    <tr>
                                                        <th class="label-th" style="text-align: left; border: 0px solid; width: 10%;"><strong>N° DE DOCUMENTO</strong></th>
                                                        <th class="label-th" style="font-size: 11px; color: #333333; text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                        <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                                                        <td class="label-td" style="font-weight: bold; border: 0px solid; width: 30%;">
                                                            <span id="nro_doc_ident" style="font-size: 11px;">
                                                                <?php 
                                                                    echo (!empty($procradiologia[0]->created_nrodoc_digitador_radiologia)) 
                                                                        ? $procradiologia[0]->created_nrodoc_digitador_radiologia 
                                                                        : auth()->user()->dni;
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <th class="label-th" style="text-align: left; border: 0px solid; width: 10%;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                                        <th class="label-th" style="font-size: 11px; color: #333333; text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                        <td class="label-td" style="border: 0px solid;">
                                                            <span id="nro_historia">
                                                                <?php 
                                                                    echo (!empty($procradiologia[0]->created_nombre_digitador_radiologia)) 
                                                                        ? $procradiologia[0]->created_nombre_digitador_radiologia 
                                                                        : auth()->user()->paterno . " " . auth()->user()->materno . " " . auth()->user()->name;
                                                                ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 7)
                                    <div class="panel panel-info contenedor-informacion-atencion">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">INFORMACIÓN DE LA ATENCIÓN</h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tabAntecedentes" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li>
                                                <li><a href="#tabAnamnesis" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                                <li><a href="#tabExamenClinico" data-toggle="tab" style="font-size:12px">EXAMEN CLINICO</a></li>
                                                <li><a href="#tabTratamiento" data-toggle="tab" style="font-size:12px">TRATAMIENTO Y/O TERAPEUTICA</a></li>
                                                <li><a href="#tabDiagnosticos" data-toggle="tab" style="font-size:12px">DIAGNÓSTICOS</a></li>
                                                <li><a href="#tabProcedimientos" data-toggle="tab" style="font-size:12px">PROCEDIMIENTOS Y PLAN DE TRABAJO</a></li>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade in active" id="tabAntecedentes">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">ANTECEDENTES</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>ANTECEDENTES</label>
                                                                <!-- <textarea class="form-control" cols="5" id="antecedente" name="antecedente"
                                                                placeholder="Antecedentes personales" disabled>{{$prestacion->antecedente}}</textarea> -->
                                                                @if(isset($prestacion->antecedente))
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->antecedente}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>ANTECEDENTE FAMILIARES</label>
                                                                <!-- <textarea class="form-control" cols="5" id="antecedente_familiar" name="antecedente_familiar"
                                                                placeholder="Antecedente Familiar" disabled>{{$prestacion->antecedente_familiar}}</textarea> -->
                                                                @if(isset($prestacion->antecedente_familiar))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->antecedente_familiar}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="tabAnamnesis">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">ANAMNESIS</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>MOTIVO DE VISITA</label>
                                                                <!-- <textarea class="form-control" cols="5" id="motivo_visita" name="motivo_visita"
                                                                placeholder="Relato m&eacute;dico del motivo de la visita" disabled>{{$prestacion->motivo_visita}}</textarea> -->
                                                                @if(isset($prestacion->motivo_visita))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->motivo_visita}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>TIEMPO DE ENFERMEDAD</label>
                                                                <!-- <textarea class="form-control" cols="5" id="tiempo_enfermedad" name="tiempo_enfermedad"
                                                                placeholder="Relato m&eacute;dico del tiempo de enfermedad" disabled>{{$prestacion->tiempo_enfermedad}}</textarea> -->
                                                                @if(isset($prestacion->tiempo_enfermedad))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->tiempo_enfermedad}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>RELATO CRONOLOGICO</label>
                                                                <!-- <textarea class="form-control" cols="5" id="anamnesis" name="anamnesis"
                                                                placeholder="Relato m&eacute;dico del relato cronologico" disabled>{{$prestacion->anamnesis}}</textarea> -->
                                                                @if(isset($prestacion->anamnesis))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->anamnesis}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="tabExamenClinico">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">EXAMEN CLINICO</h3>
                                                        </div>
                                                        @if($prestacion->temperatura>0)
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <div class="col-md-2">
                                                                    <label>TEMPERATURA (°C) &nbsp;</label>
                                                                    <!-- <input placeholder="00.00" value="{{$prestacion->temperatura}}" type="text" name="temperatura" id="temperatura" class="form-control" disabled/> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->temperatura}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>TALLA (cm) &nbsp;</label>
                                                                    <!-- <input value="" type="text" name="talla" id="talla" class="form-control" value="{{$prestacion->talla_cm}}" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->talla_cm}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>PESO (Kg) &nbsp;</label>
                                                                    <!-- <input placeholder="00.00" type="text" name="peso" id="peso" class="form-control" value="{{$prestacion->peso_kg}}" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->peso_kg}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>IMC &nbsp;</label>
                                                                    <!-- <input value="<-?php if($prestacion->talla_cm!=0): echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01))); endif; ?>" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="26" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0"><?php if($prestacion->talla_cm!=0): echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01))); endif; ?></p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>PESO IDEAL &nbsp;</label>
                                                                    <!-- <input value="<?php echo 45+(($prestacion->peso_kg-150)*3/4)?>" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0"><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>Sa02 (%) &nbsp;</label>
                                                                    <!-- <input value="{{$prestacion->sa02_porcentaje}}" type="text" name="saturacion" id="saturacion" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->sa02_porcentaje}}</p>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-2">
                                                                    <label>TAMAÑO DE CINTURA (cm)</label>
                                                                    <!-- <input value="{{$prestacion->tamano_cintura_cm}}" type="text" name="tamanio_cintura" id="tamanio_cintura" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->tamano_cintura_cm}}</p>
                                                                </div>
                                                                <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                                                    <label>FREC. CARDIACA FETAL(/min)</label>
                                                                    <!-- <input value="{{$prestacion->frecuencia_cardiaca_fetal}}" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->frecuencia_cardiaca_fetal}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>PRESIÓN ARTERIAL &nbsp;</label>
                                                                    <div class="row">
                                                                        <div class="col-xs-5">                          
                                                                            <!-- <input value="{{$prestacion->presion_arterial_x}}" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" disabled /> -->
                                                                            <p class="form-control-plaintext text-uppercase m-0"> {{ $prestacion->presion_arterial_x }} / {{ $prestacion->presion_arterial_y }}</p>
                                                                        </div>  
                                                                        <!-- <div class="col-xs-2">
                                                                            <label>/</label> 
                                                                        </div> -->
                                                                        <!-- <div class="col-xs-5">
                                                                            <!-- <input value="{{$prestacion->presion_arterial_y}}" type="text" name="presion_arterial_y" id="presion_arterial_y" class="form-control" disabled /> 
                                                                            <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->presion_arterial_y}}</p>
                                                                        </div>   -->
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>FREC.RESPIRATORIA(/min)</label>
                                                                    <!-- <input value="{{$prestacion->frecuencia_respiratoria}}" type="text" name="frecuencia_respiratoria" id="frecuencia_respiratoria" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->frecuencia_respiratoria}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                                    <!-- <input value="{{$prestacion->frecuencia_cardiaca}}" type="text" name="frec_cardiaca" id="frec_cardiaca" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->frecuencia_cardiaca}}</p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label>Fi02 (%) &nbsp;</label>
                                                                    <!-- <input value="{{$prestacion->fi02}}" type="text" name="oxigenacion" id="oxigenacion" class="form-control" disabled /> -->
                                                                    <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->fi02}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label>EXAMEN FISICO</label>
                                                                <!-- <textarea class="form-control" cols="5" id="examen_fisico" name="examen_fisico"
                                                                placeholder="Examen Físico" disabled>{{$prestacion->examen_fisico}}</textarea> -->
                                                                @if(isset($prestacion->examen_fisico))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->examen_fisico}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif                                                                
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label>EXAMEN CLINICO</label>
                                                                <!-- <textarea class="form-control" cols="5" id="examen_clinico" name="examen_clinico"
                                                                placeholder="Examen Clínico" disabled>{{$prestacion->examen_clinico}}</textarea> -->
                                                                @if(isset($prestacion->examen_clinico))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->examen_clinico}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label>EXAMENES AUXILIARES</label>
                                                                <!-- <textarea class="form-control" cols="5" id="examen_auxiliar" name="examen_auxiliar"
                                                                placeholder="Examenes auxiliares" disabled>{{$prestacion->examen_auxiliar}}</textarea> -->                                                                
                                                                @if(isset($prestacion->examen_auxiliar))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->examen_auxiliar}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="tabTratamiento">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">TRATAMIENTO Y/O TERAPEUTICA</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>TRATAMIENTO AUXILIAR</label>
                                                                <!-- <textarea class="form-control" cols="5" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                                                placeholder="Tratamiento auxiliar" disabled>{{$prestacion->tratamiento_auxiliar}}</textarea> -->
                                                                @if(isset($prestacion->tratamiento_auxiliar))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{$prestacion->tratamiento_auxiliar}}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="tabDiagnosticos">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">DIAGNÓSTICOS</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                                                            <select class="form-control" name="id_diagnostico" id="id_diagnostico" /*required=""*/ style="border: none; background: transparent; text-transform: uppercase;font-size: 12px;">
                                                                <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                                                            </select>
                                                            <?php else: ?>
                                                                <div id="entregar_receta" >
                                                                    <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-lg-6 text-uppercase">Código y Nombre :</th> <!-- Columna "Nombre y Código" ahora ocupa la mitad del ancho -->
                                                                                <th class="col-lg-6 text-uppercase">Tipo de DX :</th>
                                                                                <!-- Columna "Descripción" también ocupa la mitad del ancho -->
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                // filtramos los duplicados
                                                                                $diagnosticos_filtrados = [];
                                                                                foreach ($diagnostico as $row) {
                                                                                    $clave = $row->codigo . ' - ' . $row->nombre;
                                                                                    if (!isset($diagnosticos_filtrados[$clave]) || $row->descripcion === 'DEFINITIVO') {
                                                                                        $diagnosticos_filtrados[$clave] = $row;
                                                                                    }
                                                                                }
                                                                            ?>

                                                                            <?php foreach($diagnosticos_filtrados as $row): ?>
                                                                            <tr>
                                                                                <td class="col-lg-6">
                                                                                    <p class="form-control-plaintext text-uppercase m-0 " style="font-size: 12px;margin-top: 2px;">{{ $row->codigo . ' - ' . $row->nombre }}</p>
                                                                                </td>
                                                                                <td class="col-lg-6">
                                                                                    <p class="form-control-plaintext text-uppercase m-0" style="font-size: 12px;margin-top: 2px;">{{ $row->descripcion }}</p>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="panel-body">
                                                            <!-- <label class="control-label col-lg-2" style="text-align: left; padding-bottom: 2rem;">CANTIDAD DE DIAGNÓSTICOS: {{ count($diagnostico) }}</label> -->
                                                            <label class="control-label" style="text-align: left; padding-left: 18px; font-size: 11px;">CANTIDAD DE DIAGNÓSTICOS: {{ count($diagnostico) }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="tabProcedimientos">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">PROCEDIMIENTOS Y PLAN DE TRABAJO</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            
                                                            <div id="entregar_receta" >
                                                                <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-lg-10 text-uppercase m-0">Código y Nombre :</th>
                                                                        @if($prestacion->id>1910698)
                                                                            <th class="col-lg-2 text-uppercase m-0">Diagnostico :</th>
                                                                        @endif
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($procedimiento as $row)
                                                                        <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                                                                        <tr class="row2-{{ $row->cod }}">
                                                                            <td class="col-lg-10">
                                                                                <p class="form-control-plaintext text-uppercase m-0 text-left">{{ $row->codigo . ' - ' . $row->nombre }}</p>
                                                                            </td>
                                                                        @if($prestacion->id>1910698)
                                                                            <td class="col-lg-2">                                                                              
                                                                                <p class="form-control-plaintext text-uppercase m-0" style="font-size: 12px;margin-top: 2px"><?php $model_diagnosticos = new \App\Models\Producto_receta; $diagnostico_id = $row->id_diagnostico; $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id); echo $diagnostico_nombre; ?></p>
                                                                            </td>
                                                                        @endif
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>PLAN DE TRABAJO</label>
                                                                <!-- <textarea class="form-control" cols="5" id="plan_trabajo" name="plan_trabajo"
                                                                placeholder="Plan de Trabajo" disabled >{{ $prestacion->plan_trabajo }}</textarea> -->                                                                
                                                                @if(isset($prestacion->plan_trabajo))
                                                                <p class="form-control-plaintext text-uppercase m-0">{{ $prestacion->plan_trabajo }}</p>
                                                                @else
                                                                <p class="form-control-plaintext text-uppercase m-0" style="padding-left: 18px; ">{{" - "}}</p>
                                                                @endif   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>         
                                    </div>
                                @endif
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">EXÁMENES SOLICITADOS</h3>
                                        </div>
                                        <div class="panel-body analisis-clinico">
                                            <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="font-size: 12px !important;">
                                                <thead>
                                                    <tr>
                                                        <th width="35%" style="text-align: center;">Nombre de Examen</th>
                                                        <th width="5%" style="text-align: center;">Cantidad</th>
                                                        <th width="12%" style="text-align: center;">Lado</th>
                                                        <th width="12%" style="text-align: center;">Comparativo</th>
                                                        <th width="8%" style="text-align: center;">DX</th>
                                                        <th width="23%" style="text-align: center;">Obervacion</th>
                                                        <th width="5%" style="text-align: center;">Radiología Portátil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $i=0;
                                                    foreach($procradiologia as $row):
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="id_pres_proc[]" class="id_pres_proc" checked value="<?php echo $row->id ?>" data-id="{{ $row->id }}"><span class="checkboxtexto" style="font-size: 12px !important;">&nbsp;&nbsp;&nbsp;<?php echo $row->codigo." - ".$row->nombre?></span><br><br/>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->cantidad }}" /> -->
                                                                <p class="form-control-plaintext text-center m-0">{{ $row->cantidad }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="<-?php 
                                                                    switch($row->lado_radio){ 
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "Izquierdo"; break;
                                                                        case 2 : echo "Derecho"; break;  
                                                                    }
                                                            ?>"/> -->
                                                                <p class="form-control-plaintext text-center m-0" >
                                                                <?php
                                                                    switch($row->lado_radio){
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "Izquierdo"; break;
                                                                        case 2 : echo " Derecho "; break;
                                                                    }
                                                                ?>  
                                                                </p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="<-?php 
                                                                    switch($row->comparativo_radio){ 
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "SI"; break;
                                                                        case 2 : echo "NO"; break;  
                                                                    }
                                                            ?>" /> -->
                                                                <p class="form-control-plaintext text-center m-0" >
                                                                <?php
                                                                    switch($row->comparativo_radio){ 
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "SI"; break;
                                                                        case 2 : echo "NO"; break;  
                                                                    }
                                                                ?> 
                                                                </p> 
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->diagnostico}}" />  -->
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->diagnostico }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="{{$row->observacion}}" /> -->
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->observacion }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <!-- <input type="text" class="form-control input-sm" style="text-align: center !important;" readonly="" value="{{$row->radiologia_portatil}}" /> -->
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->radiologia_portatil }}</p>
                                                            </td>
                                                            <input type="hidden" name="id_pres_proc_total[]" class="id_pres_proc_total" value="<?php echo $row->id ?>" />
                                                        </tr>
                                                    <?php 
                                                        $i++;
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <label class="control-label" style="text-align: left;">
                                                CANTIDAD DE EXÁMENES: <span id="cantidad_examenes">{{$i}}</span>
                                            </label>
                                        </div>
                                        @if((auth()->user()->flagfiscalizador == 1 && auth()->user()->tipo_registro == 1) && $id_cita != 0)
                                            <div class="col-lg-4 text-right">
                                                <button type="button" class="btn btn-warning" onclick="modalForm({{$id_cita}},{{$id_pp}})">
                                                    Observar Cita / Reprogramar
                                                </button>
                                            </div>
                                        @elseif(auth()->user()->tipo_registro == 6)
                                            @if(($id_tipo_atencion == 1 || $id_tipo_atencion == 2 || $id_tipo_atencion == 3) && $id_cita != 0)
                                                <div class="col-lg-4 text-right">
                                                    <button type="button" class="btn btn-warning" onclick="modalForm({{$id_cita}},{{$id_pp}})">
                                                        Observar Cita / Reprogramar
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="row" style="margin-bottom: 2rem;">
                                        <label class="control-label col-lg-3" style="text-align: left; padding-bottom: 2rem;">OBSERVACION: </label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="3" id="observacion_examen" name="observacion_examen"></textarea>
                                        </div>    
                                    </div>
                                    @if ( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7  )
                                        <div id="contenedor-profesional-imagenes">
                                            <div class="row" style="margin-bottom: 2rem;">
                                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                    <label>INFORME</label>
                                                </div>
                                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                    <button tabindex="90" type="button" id="addInforme" class="btn btn-lg btn-toggle"
                                                    data-toggle="button" aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>
                                            <fieldset id="fsInforme" style="display:none; margin-bottom: 2rem;">
                                                <?php if(count($procradiologia)==1): ?>
                                                    <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                                                        <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" >
                                                            <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                                                        </select>
                                                    <?php else: ?>
                                                        <div class="col-md-12" style="text-align: left; font-size: 18px">
                                                            PLANTILLA
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="col-md-10">
                                                                <select  class="form-control input-sm" name="id_plantilla1" id="id_plantilla1"  style="width: 100%">
                                                                    <option value="">-Seleccionar-</option>
                                                                    <?php
                                                                    foreach ($plantillas as $row):
                                                                        echo '<option value="' . $row->id . '" >' . $row->nombre_plantilla. '</option>';
                                                                    endforeach
                                                                    ?>
                                                                    </select>
                                                                <br/>
                                                            </div>
                                                            <div class="col-md-2">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-12"><br/>
                                                            <label>PÁRRAFO</label>
                                                            <textarea id="parrafo1" name="parrafo1" rows="2" style="height:300px;width:100%;"></textarea>
                                                            <!--input type="hidden" value="1" name="id_examen_plantilla_2" id="id_examen_plantilla_2"-->
                                                            </div>
                                                        </div>
                                                        </div>
                                                        
                                                    <?php endif; ?>
                                                <?php else:  //cuando tiene mas opciones   ?>
                                                    <div class="col-md-12" style="text-align: left; font-size: 12px">
                                                            Que desea hacer ?
                                                    </div>
                                                    <div class="col-md-12" style="text-align: left; font-size: 12px">
                                                        <select required="" class="form-control input-sm" name="id_examen_plantilla_2" id="id_examen_plantilla_2" onchange="getMostrarExamenPlantilla();" style="width: 100%">
                                                            <option value="">-Seleccionar-</option>
                                                            <option value="2">Una Plantilla por Examen</option>
                                                            <option value="1">Todos los examenes en una sola plantilla</option>
                                                        </select>
                                                        <br/>
                                                    </div>   
                                                    <div id="divexamenplantilla" class="row col-md-12" style="display:none">
                                                        <br/>
                                                        <div class="col-md-12" style="text-align: left; font-size: 18px">
                                                            PLANTILLA
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="col-md-10">
                                                                <select  class="form-control input-sm" name="id_plantilla1" id="id_plantilla1" style="width: 100%">
                                                                    <option value="">-Seleccionar-</option>
                                                                    <?php
                                                                    foreach ($plantillas as $row):
                                                                        echo '<option value="' . $row->id . '" >' . $row->nombre_plantilla. '</option>';
                                                                    endforeach
                                                                    ?>
                                                                </select>
                                                                <br/>
                                                            </div>
                                                            <div class="col-md-2">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-12"><br/>
                                                                    <label>PÁRRAFO</label>
                                                                    <textarea id="parrafo1" name="parrafo1" rows="2" style="height:300px;width:100%;"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="divexamenplantilla2" class="row col-md-12" style="display:none"></div>
                                                <?php endif; ?>
                                            </fieldset>
                                        </div>
                                    @endif
                                    <div class="col-sm-12 col-md-12">
                                        <br/>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                            <a class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important" onclick="cancelarFormulario()">CANCELAR</a>
                                            </div>
                                            <div class="col-md-6">
                                                <!--input <!?php echo $disabled_radiologia?> type="submit" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important"-->
                                                <a <?php echo $disabled_radiologia?> class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabHistorialImagenes">
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
                            <div class="tab-pane fade" id="tabHistorialLaboratorio">
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
                        </div>    
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalForm" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- Modal: motivo de observación cuando se desmarcan todos los exámenes -->
<div id="modalMotivoObservacion" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document" style="width: 600px;">
        <div class="modal-content" style="overflow: hidden;">
            <div class="modal-header" style="background-color: #5cb85c; color: #fff !important; text-align: center; padding: 12px 15px;">
                <h5 class="modal-title" style="color: #fff !important; font-weight: bold; font-size: 13px; margin: 0;">MOTIVO DE OBSERVACIÓN DE EXAMEN</h5>
            </div>
            <div class="modal-body" style="padding: 20px 25px;">
                <div class="radio" style="margin: 0 0 12px 0;"><label><input type="radio" name="motivo_observacion_examen" value="Inasistencia del paciente"> Inasistencia del paciente.</label></div>
                <div class="radio" style="margin: 0 0 12px 0;"><label><input type="radio" name="motivo_observacion_examen" value="Suspensión por condición clínica"> Suspensión por condición clínica.</label></div>
                <div class="radio" style="margin: 0 0 12px 0;"><label><input type="radio" name="motivo_observacion_examen" value="Indisponibilidad de equipo/servicio"> Indisponibilidad de equipo/servicio.</label></div>
                <div class="radio" style="margin: 0 0 12px 0;"><label><input type="radio" name="motivo_observacion_examen" value="Falta de preparación previa"> Falta de preparación previa.</label></div>
                <div class="radio" style="margin: 0 0 12px 0;"><label><input type="radio" name="motivo_observacion_examen" value="Otros"> Otros (campo de texto libre).</label></div>
                <textarea class="form-control" rows="4" id="motivo_observacion_detalle" maxlength="500" style="margin-top: 5px; display: none; resize: vertical;" placeholder="Describa el motivo" disabled></textarea>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-xs-6" style="padding-right: 5px;">
                        <button type="button" class="btn btn-success btn-block" id="btnGuardarMotivoObservacion">Guardar</button>
                    </div>
                    <div class="col-xs-6" style="padding-left: 5px;">
                        <button type="button" class="btn btn-danger btn-block" id="btnCancelarMotivoObservacion">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--script src="{{ asset('/ckeditor/ckeditor.js') }}"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('sceditor/minified/sceditor.min.js') }}"></script>
<script src="{{ asset('sceditor/languages/es.js') }}"></script>
<script src="{{ asset('sceditor/minified/icons/monocons.js') }}"></script>
<script src="{{ asset('sceditor/minified/formats/bbcode.js') }}"></script>
<script>
    let textarea1 = document.getElementById('parrafo1');
    if (textarea1) {
        sceditor.create(textarea1, {
            icons: 'monocons',
            locale: 'es-ES',
            style: '{{ asset("sceditor/minified/themes/content/default.min.css") }}',
            emoticonsRoot: '{{ asset("sceditor/emoticons/") }}/'
        });
    }
</script>
@include('frontend.includes.footer')
@endsection

