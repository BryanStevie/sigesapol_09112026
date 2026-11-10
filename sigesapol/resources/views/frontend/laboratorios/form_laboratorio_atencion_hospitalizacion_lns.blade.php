@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

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

.cod_siteds_susalud{
      font-weight: bold;
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

  .size-titulo {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
    }

    .table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
      padding: 2px 8px !important;
      border: 0px solid;
    }
    .table-borderless{
          margin-bottom: 0px !important;
    }
</style>

<script>
 /*    <?php if (!isset($codigo_siteds)): ?>
            generarCodigoSiteds();
    <?php endif; ?> */

    $(document).ready(function () {
        generarCodigoSiteds();
        let referencia = "<?php echo $referencia ?>"; // Obtén el valor de referencia desde PHP
        if (referencia === "SI") {
            document.getElementById("contenedorReferencia").style.display = "block";
        }
    });

  $('#idjefelaboratorio').select2(); $('#idresponsable').select2();

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
    
    
	jQuery(document).on('focus', '.select2', function() {
    	jQuery(this).siblings('select').select2('open');
	});


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

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
  //------------------------------------------------------------------
	
	$(".chosen-select").select2(
		{
			minimumInputLength: 3,
  			tags: true,
			cache: true,
			language: { inputTooShort: function () { return 'Ingresar 3 caracteres para buscar el diagnostico'; } },

		}
	);
	
	
	$('.chosen-select').on("select2:select", function (e) {
	 	if(e.params.data.id > 0){
		
		}else{
			$(this).select2('val', '');
		}
		
	 });
	
    function validarFormulario() {
        const cod_siteds_susalud = $('#cod_siteds_susalud').val();

        if (cod_siteds_susalud === "") {
            Swal.fire({
                icon: 'warning',
                // title: 'Campo vacío',
                html: 'Por favor, debe ingresar el <b>CODIGO SITEDS de SUSALUD</b>',
                confirmButtonText: 'ENTENDIDO'
            });
            return;
        }
        console.log("antes de las pruebas checkeadas");

        const pruebasCheckeadas = document.querySelectorAll('input[type=checkbox]:checked');
        if (pruebasCheckeadas.length == 0) {
            Swal.fire({
                // title: '¿Estás seguro?',
                html: "No esta seleccionando ningún Exámen.<br><br><b>¿Deseas continuar con el registro?<b>",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'SÍ, CONTINUAR',
                cancelButtonText: 'NO, CANCELAR',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("confirmó antes");
                    registrarFormulario();

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    console.log("no confirmó antes");
                }
            });
        } else {
            registrarFormulario();
        }
    }

    function registrarFormulario() { console.log("funcion registrarFormulario");

        Swal.fire({
            title: '<h5><b>Registrando la Orden ...</b></h5>',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const texto = $('#nro_doc_ident').val();

        // API moderna para copiar al portapapeles
        if (navigator.clipboard) {
            navigator.clipboard.writeText(texto).then(function () {
                $('#mensaje').fadeIn().delay(1500).fadeOut();
            }).catch(function (err) {
                alert('Error al copiar el texto: ' + err);
            });
        } else {
            // Fallback para navegadores antiguos
            const tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(texto).select();
            document.execCommand('copy');
            tempInput.remove();
            $('#mensaje').fadeIn().delay(1500).fadeOut();
        }

        $("#frmOrdenLaboratorio").submit();

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

    $("#btnDetallePrestaciones").click(function (e) { 
        e.preventDefault();
        let dni_paciente = $("#nro_dni_paciente").val();

        $.ajax({
            url: "/obtener_detalle_prestaciones_laboratorio/" + dni_paciente,
            beforeSend: function() {
              $('#detalle-prestacion').html("<center><h2>Cargando...</h2></center>");
            },
            success: function (response) {
                //console.table(response);
                let listaDetalle = "";
                $(response).each(function (i,v) { 
                     listaDetalle += `<tr>
                                        <td>${v.id_prestacion}</td>
                                        <td>${v.fecha_orden}</td>`;
                     if (v.id_tipo_atencion == "1") {
                        listaDetalle += `<td>${v.tipo_atencion_nombre}</td>`;   
                     }
                     if (v.id_tipo_atencion == "2") {
                        if (v.id_evaluacion > 0) {
                            listaDetalle += `<td>EMERGENCIA - EVALUACIÓN</td>`;   
                        } else {
                            listaDetalle += `<td>${v.tipo_atencion_nombre}</td>`;   
                        }
                     }
                     listaDetalle += `<td>${v.establecimiento_procedencia}</td>`;   
                     if (v.id_tipo_atencion == "1") {
                        listaDetalle += `<td>${v.nom_consultorio}</td>`;   
                     }
                     if (v.id_tipo_atencion == "2") {
                        listaDetalle += `<td>${v.nombre_servicio_emergencia}</td>`; 
                     }
                     if (v.id_tipo_atencion == "1") {
                        listaDetalle += `<td>${v.paterno_medico} ${v.materno_medico}, ${v.nombre_medico}</td>`;   
                     }
                     if (v.id_tipo_atencion == "2") {
                        if (v.id_evaluacion > 0) {
                            listaDetalle += `<td>${v.paterno_medico_evaluacion} ${v.materno_medico_evaluacion}, ${v.nombre_medico_evaluacion}</td>`; 
                        } else {
                            listaDetalle += `<td>${v.paterno_medico} ${v.materno_medico}, ${v.nombre_medico}</td>`;  
                        }
                     }
                     listaDetalle += `<td><a data-toggle="modal" class="btn btn-primary btn-xs btnDetalleItemsExamen" data-registro = "${v.id_laboratorio}"><i class="fa fa-list"></i></a></td>
                                    </tr>`;
                });
                $('#detalle-prestacion').html(listaDetalle);
            }
        });
        $("#showDetallePrestacionLab").modal();

    });

    //funcion para eventos dinamicos on()
    $(document).on('click', '.btnDetalleItemsExamen', function(e) {
        e.preventDefault();
        let id_laboratorio = $(this).data('registro');

        //console.log(id_laboratorio);
        $.ajax({
            url: "/obtener_items_detalle_prestaciones_laboratorio/" + id_laboratorio,
            success: function (response) {
                let listaDetalle = "";
                $(response).each(function (i,v) { 
                     listaDetalle += `<tr>
                                        <td>${v.codigo_lab}</td>
                                        <td>${v.descripcion_corta}</td>
                                        <td>${v.cantidad}</td>
                                        <td>${v.dias_transcurridos}</td>
                                    </tr>`;
                });
                $('#muestra-examenes').html(listaDetalle);
            }
        });
        $("#showExamenesLabo").modal();
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
    // VER SOLICITUD PROCEDIMIENTO
    $(document).on('click', '.verSolicitudProcedimiento', function () {
        let i = $(this).data('id');
        let idSolicitudProcedimiento = $(this).data('id-solicitud-procedimiento');
        $.ajax({
            url: "/obtener_solicitud_procedimiento/" + idSolicitudProcedimiento,
            success: function (response) {
                console.log(response);
                if (response[0].descripcion_examen == 'TEST DE TOLERANCIA A LA GLUCOSA ORAL') {
                    let id_consultorio = $('#id_consultorio').val();
                    //      ENDOCRINOLOGÍA      
                    if (id_consultorio == '15') {

                        $('#tolerancia_paciente_pediatrico_ttgo').val(response[0].pediatrico_ttgo == "true" ? "SI" : "NO");
                        if (response[0].pediatrico_ttgo == "true") {
                            $('#fecha_tolerancia_paciente_pediatrico_ttgo').val(response[0].fecha_pediatrico_ttgo);
                            $('#contenedor_tolerancia_paciente_pediatrico_ttgo').show();
                        }
                        
                        $('#tolerancia_paciente_pediatrico_glucosa_anhidra').val(response[0].pediatrico_glucosa_anhidra == "true" ? "SI" : "NO");
                        if (response[0].pediatrico_glucosa_anhidra == "true") {
                            $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val(response[0].cantidad_pediatrico_glucosa_anhidra);
                            $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').show();
                        }

                        $('#tolerancia_paciente_adulto_ttgo').val(response[0].adulto_ttgo == "true" ? "SI" : "NO");
                        if (response[0].adulto_ttgo == "true") {
                            $('#fecha_tolerancia_paciente_adulto_ttgo').val(response[0].fecha_adulto_ttgo);
                            $('#contenedor_tolerancia_paciente_adulto_ttgo').show();
                        }

                        $('#tolerancia_paciente_adulto_glucosa_anhidra').val(response[0].adulto_glucosa_anhidra == "true" ? "SI" : "NO");
                        if (response[0].adulto_glucosa_anhidra == "true") {
                            $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val(response[0].cantidad_adulto_glucosa_anhidra);
                            $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').show();
                        }

                        $('#tolerancia_paciente_gestante_g').val(response[0].gravidez);

                        $('#tolerancia_paciente_gestante_p_1').val(response[0].paridad_1);
                        $('#tolerancia_paciente_gestante_p_2').val(response[0].paridad_2);
                        $('#tolerancia_paciente_gestante_p_3').val(response[0].paridad_3);
                        $('#tolerancia_paciente_gestante_p_4').val(response[0].paridad_4);

                        $('#tolerancia_paciente_gestante_edad_gestacional_meses').val(response[0].edad_gestacional_meses);
                        $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val(response[0].edad_gestacional_semanas);
                        $('#tolerancia_paciente_gestante_edad_gestacional_dias').val(response[0].edad_gestacional_dias);
                        
                        $('#tolerancia_paciente_gestante_ttgo').val(response[0].gestante_ttgo == "true" ? "SI" : "NO");
                        if (response[0].gestante_ttgo == "true") {
                            $('#fecha_tolerancia_paciente_gestante_ttgo').val(response[0].fecha_gestante_ttgo);
                            $('#contenedor_tolerancia_paciente_gestante_ttgo').show();
                        }
                        $('#tolerancia_paciente_gestante_glucosa_anhidra').val(response[0].gestante_glucosa_anhidra == "true" ? "SI" : "NO");
                        if (response[0].gestante_glucosa_anhidra == "true") {
                            $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val(response[0].cantidad_gestante_glucosa_anhidra);
                            $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').show();
                        }
                        $('#tolerancia_paciente_gestante_factores_riesgo').val(response[0].gestante_factores_riesgo == "true" ? "SI" : "NO");
                        if (response[0].gestante_factores_riesgo == "true") {
                            $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val(response[0].especifique_gestante_factores_riesgo);
                            $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').show();
                        }
                        
                        $('#tolerancia_paciente_pediatrico').show();
                        $('#tolerancia_paciente_adulto').show();
                        $('#tolerancia_paciente_gestante').show();
                        $('#modalSolicitudTestToleranciaGlucosaOral').modal();
                    }
                    //        PEDIATRÍA
                    if (id_consultorio == '49') { 
                        
                        $('#tolerancia_paciente_pediatrico_ttgo').val(response[0].pediatrico_ttgo == "true" ? "SI" : "NO");
                        if (response[0].pediatrico_ttgo == "true") {
                            $('#fecha_tolerancia_paciente_pediatrico_ttgo').val(response[0].fecha_pediatrico_ttgo);
                            $('#contenedor_tolerancia_paciente_pediatrico_ttgo').show();
                        }
                        
                        $('#tolerancia_paciente_pediatrico_glucosa_anhidra').val(response[0].pediatrico_glucosa_anhidra == "true" ? "SI" : "NO");
                        if (response[0].pediatrico_glucosa_anhidra == "true") {
                            $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val(response[0].cantidad_pediatrico_glucosa_anhidra);
                            $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').show();
                        }

                        $('#tolerancia_paciente_pediatrico').show();
                        $('#modalSolicitudTestToleranciaGlucosaOral').modal();
                    }
                    //         GINECOLOGÍA      
                    if (id_consultorio == '77') {

                        $('#tolerancia_paciente_gestante_g').val(response[0].gravidez);

                        $('#tolerancia_paciente_gestante_p_1').val(response[0].paridad_1);
                        $('#tolerancia_paciente_gestante_p_2').val(response[0].paridad_2);
                        $('#tolerancia_paciente_gestante_p_3').val(response[0].paridad_3);
                        $('#tolerancia_paciente_gestante_p_4').val(response[0].paridad_4);

                        $('#tolerancia_paciente_gestante_edad_gestacional_meses').val(response[0].edad_gestacional_meses);
                        $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val(response[0].edad_gestacional_semanas);
                        $('#tolerancia_paciente_gestante_edad_gestacional_dias').val(response[0].edad_gestacional_dias);
                        
                        $('#tolerancia_paciente_gestante_ttgo').val(response[0].gestante_ttgo == "true" ? "SI" : "NO");
                        if (response[0].gestante_ttgo == "true") {
                            $('#fecha_tolerancia_paciente_gestante_ttgo').val(response[0].fecha_gestante_ttgo);
                            $('#contenedor_tolerancia_paciente_gestante_ttgo').show();
                        }
                        $('#tolerancia_paciente_gestante_glucosa_anhidra').val(response[0].gestante_glucosa_anhidra == "true" ? "SI" : "NO");
                        if (response[0].gestante_glucosa_anhidra == "true") {
                            $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val(response[0].cantidad_gestante_glucosa_anhidra);
                            $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').show();
                        }
                        $('#tolerancia_paciente_gestante_factores_riesgo').val(response[0].gestante_factores_riesgo == "true" ? "SI" : "NO");
                        if (response[0].gestante_factores_riesgo == "true") {
                            $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val(response[0].especifique_gestante_factores_riesgo);
                            $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').show();
                        }

                        $('#tolerancia_paciente_gestante').show();
                        $('#modalSolicitudTestToleranciaGlucosaOral').modal();
                    }

                    $('#modalSolicitudTestToleranciaGlucosaOral').modal();
                }
                if (response[0].descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {

                    $('#antecedentes_patologicos_personales').val(response[0].antecedentes_patologicos_personales);
                    $('#antecedentes_patologicos_familiares').val(response[0].antecedentes_patologicos_familiares);
                    if (response[0].liquido_cefaloraquideo == 'true') {
                        $('#liquido_cefaloraquideo').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#liquido_cefaloraquideo').prop('disabled', true);
                    }
                    if (response[0].liquido_pleural == 'true') {
                        $('#liquido_pleural').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#liquido_pleural').prop('disabled', true);
                    }
                    if (response[0].liquido_ascitico == 'true') {
                        $('#liquido_ascitico').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#liquido_ascitico').prop('disabled', true);
                    }
                    if (response[0].liquido_sinovial == 'true') {
                        $('#liquido_sinovial').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#liquido_sinovial').prop('disabled', true);
                    }
                    if (response[0].liquido_otro == 'true') {
                        $('#liquido_otro').prop('checked', true).prop('disabled', true);
                        $('#liquido_otro_especifique').val(response[0].liquido_otro_especifique);
                        $('#liquido_otro_especifique').show();
                    } else {
                        $('#liquido_otro').prop('disabled', true);
                        $('#liquido_otro_especifique').hide();
                    }
                    $('#num_frasco_muestra_opcion').val(response[0].num_frasco_muestra_opcion);

                    $('#modalSolicitudCitoquimicosLiquidosBiologicos').modal();
                }
                if (response[0].descripcion_examen == 'HEMOCULTIVO NEONATO Y PEDRIATRIA') {
                    $('#pedriatria_fecha_tiempo_enfermedad').val(response[0].fecha_tiempo_enfermedad);
                    // EDAD GESTIONAL
                    if (response[0].postermino == 'true') {
                        $('#pedriatria_postermino').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#pedriatria_postermino').prop('disabled', true);
                    }
                    if (response[0].termino == 'true') {
                        $('#pedriatria_termino').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#pedriatria_termino').prop('disabled', true);
                    }
                    if (response[0].prematuro_leve == 'true') {
                        $('#pedriatria_prematuro_leve').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#pedriatria_prematuro_leve').prop('disabled', true);
                    }
                    if (response[0].prematuro_moderado == 'true') {
                        $('#pedriatria_prematuro_moderado').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#pedriatria_prematuro_moderado').prop('disabled', true);
                    }
                    if (response[0].prematuro_extremo == 'true') {
                        $('#pedriatria_prematuro_extremo').prop('checked', true).prop('disabled', true);
                    } else {
                        $('#pedriatria_prematuro_extremo').prop('disabled', true);
                    }
                    // APGAR
                    $('#pedriatria_apariencia').val(response[0].apariencia);
                    $('#pedriatria_pulso').val(response[0].pulso);
                    $('#pedriatria_gesto').val(response[0].gesto);
                    $('#pedriatria_actividad_respiratoria').val(response[0].actividad_respiratoria);
                    $('#pedriatria_total_apgar').val(response[0].total_apgar);
                    // ESCALA DE GLASGOW
                    $('#pedriatria_respuesta_ocular').val(response[0].respuesta_ocular);
                    $('#pedriatria_respuesta_verbal').val(response[0].respuesta_verbal);
                    $('#pedriatria_respuesta_motora').val(response[0].respuesta_motora);
                    $('#pedriatria_total_escala_glasgow').val(response[0].total_escala_glasgow);
                    // SIGNOS VITALES
                    $('#pedriatria_presion_arterial_x').val(response[0].presion_arterial_x);
                    $('#pedriatria_presion_arterial_y').val(response[0].presion_arterial_y);
                    $('#pedriatria_frecuencia_cardiaca').val(response[0].frecuencia_cardiaca);
                    $('#pedriatria_frecuencia_respiratorio').val(response[0].frecuencia_respiratorio);
                    $('#pedriatria_temperatura').val(response[0].temperatura);
                    // EXÁMENES DE LABORATORIO
                    $('#pedriatria_creatinina').val(response[0].creatinina);
                    $('#pedriatria_diuresis').val(response[0].diuresis);
                    $('#pedriatria_procalcitonina').val(response[0].procalcitonina);
                    $('#pedriatria_pcr').val(response[0].pcr);
                    $('#pedriatria_fiO2').val(response[0].fio2);
                    $('#pedriatria_leucocitos').val(response[0].leucocitos);
                    $('#pedriatria_plaquetas').val(response[0].plaquetas);
                    $('#pedriatria_bilirrubina').val(response[0].bilirrubina);
                    $('#pedriatria_glucosa').val(response[0].glucosa);
                    $('#pedriatria_alt').val(response[0].glucosa);
                    $('#pedriatria_vcg').val(response[0].vcg);
                    $('#pedriatria_tp').val(response[0].tp);
                    $('#pedriatria_sodio').val(response[0].sodio);
                    $('#pedriatria_potasio').val(response[0].potasio);
                    $('#pedriatria_calcio').val(response[0].calcio);
                    $('#pedriatria_pco2').val(response[0].pco2);
                    $('#pedriatria_tpta').val(response[0].tpta);
                    $('#pedriatria_examen_laboratorio_otros').val(response[0].examen_laboratorio_otros == "true" ? "SI" : "NO");
                    if (response[0].examen_laboratorio_otros == 'true') {
                        $('#pedriatria_examen_laboratorio_otros_especifique').val(response[0].examen_laboratorio_otros_especifique);
                        $('#pedriatria_examen_laboratorio_otros_especifique').show();
                    }

                    $('#pedriatria_terapia_antimicrobiana').val(response[0].terapia_antimicrobiana == "true" ? "SI" : "NO");
                    $('#pedriatria_frasco_1').val(response[0].frasco_1 == "true" ? "SI" : "NO");
                    if (response[0].frasco_1 == 'true') {
                        $('#pedriatria_frasco_1_opcion_1').val(response[0].frasco_1_opcion_1);
                        $('#pedriatria_frasco_1_opcion_2').val(response[0].frasco_1_opcion_2);
                        $('#pedriatria_frasco_1_opcion_1').show();
                        $('#pedriatria_frasco_1_opcion_2').show();
                    }
                    $('#pedriatria_frasco_2').val(response[0].frasco_2 == "true" ? "SI" : "NO");
                    if (response[0].frasco_2 == 'true') {
                        $('#pedriatria_frasco_2_opcion_1').val(response[0].frasco_2_opcion_1);
                        $('#pedriatria_frasco_2_opcion_2').val(response[0].frasco_2_opcion_2);
                        $('#pedriatria_frasco_2_opcion_1').show();
                        $('#pedriatria_frasco_2_opcion_2').show();
                    }
                    $('#pedriatria_frasco_3').val(response[0].frasco_3 == "true" ? "SI" : "NO");
                    if (response[0].frasco_3 == 'true') {
                        $('#pedriatria_frasco_3_opcion_1').val(response[0].frasco_3_opcion_1);
                        $('#pedriatria_frasco_3_opcion_2').val(response[0].frasco_3_opcion_2);
                        $('#pedriatria_frasco_3_opcion_1').show();
                        $('#pedriatria_frasco_3_opcion_2').show();
                    }
                    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').val(response[0].hemocultivo_seguimiento_y_vigilancia == "true" ? "SI" : "NO");
                    if (response[0].hemocultivo_seguimiento_y_vigilancia == 'true') {
                        $('#pedriatria_fecha_primer_resultado_hemocultivo').val(response[0].fecha_primer_resultado_hemocultivo);
                        $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').show();
                    }
                    $('#modalSolicitudHemocultivoPedriatria').modal();
                }
                if (response[0].descripcion_examen == 'HEMOCULTIVO ADULTO') {
                    $('#adulto_fecha_tiempo_enfermedad').val(response[0].fecha_tiempo_enfermedad);
                    $('#adulto_respuesta_ocular').val(response[0].respuesta_ocular);
                    $('#adulto_respuesta_verbal').val(response[0].respuesta_verbal);
                    $('#adulto_respuesta_motora').val(response[0].respuesta_motora);
                    $('#adulto_total_escala_glasgow').val(response[0].total_escala_glasgow);
                    $('#adulto_presion_arterial_x').val(response[0].presion_arterial_x);
                    $('#adulto_presion_arterial_y').val(response[0].presion_arterial_y);
                    $('#adulto_frecuencia_cardiaca').val(response[0].frecuencia_cardiaca);
                    $('#adulto_frecuencia_respiratorio').val(response[0].frecuencia_respiratorio);
                    $('#adulto_temperatura').val(response[0].temperatura);
                    $('#adulto_creatinina').val(response[0].creatinina);
                    $('#adulto_diuresis').val(response[0].diuresis);
                    $('#adulto_procalcitonina').val(response[0].procalcitonina);
                    $('#adulto_pcr').val(response[0].pcr);
                    $('#adulto_fiO2').val(response[0].fio2);
                    $('#adulto_leucocitos').val(response[0].leucocitos);
                    $('#adulto_plaquetas').val(response[0].plaquetas);
                    $('#adulto_bilirrubina').val(response[0].bilirrubina);
                    $('#adulto_ima').val(response[0].ima == "true" ? "SI" : "NO");
                    $('#adulto_icc').val(response[0].icc == "true" ? "SI" : "NO");
                    $('#adulto_evp').val(response[0].evp == "true" ? "SI" : "NO");
                    $('#adulto_ecv').val(response[0].ecv == "true" ? "SI" : "NO");
                    $('#adulto_demencia').val(response[0].demencia == "true" ? "SI" : "NO");
                    $('#adulto_dm').val(response[0].dm == "true" ? "SI" : "NO");
                    $('#adulto_diabetes_lesion_organica').val(response[0].diabetes_lesion_organica == "true" ? "SI" : "NO");
                    $('#adulto_hemiplejia').val(response[0].hemiplejia == "true" ? "SI" : "NO");
                    $('#adulto_epoc').val(response[0].epoc == "true" ? "SI" : "NO");
                    $('#adulto_patologia_tejido_conectivo').val(response[0].patologia_tejido_conectivo == "true" ? "SI" : "NO");
                    $('#adulto_enfermedad_ulcerosa').val(response[0].enfermedad_ulcerosa == "true" ? "SI" : "NO");
                    $('#adulto_patologia_renal_moderada_o_grave').val(response[0].patologia_renal_moderada_o_grave == "true" ? "SI" : "NO");
                    $('#adulto_neoplasias').val(response[0].neoplasias == "true" ? "SI" : "NO");
                    if (response[0].neoplasias == 'true') {
                        $('#adulto_neoplasias_especifique').val(response[0].neoplasias_especifique);
                        $('#adulto_neoplasias_especifique').show();
                    }
                    $('#adulto_leucemias').val(response[0].leucemias == "true" ? "SI" : "NO");
                    if (response[0].leucemias == 'true') {
                        $('#adulto_leucemias_especifique').val(response[0].leucemias_especifique);
                        $('#adulto_leucemias_especifique').show();
                    }
                    $('#adulto_linfomas_malignos').val(response[0].linfomas_malignos == "true" ? "SI" : "NO");
                    $('#adulto_metastasis_colida').val(response[0].metastasis_colida == "true" ? "SI" : "NO");
                    $('#adulto_sida').val(response[0].sida == "true" ? "SI" : "NO");
                    $('#adulto_antecedentes_patologicos_otros_1').val(response[0].antecedentes_patologicos_otros_1 == "true" ? "SI" : "NO");
                    if (response[0].antecedentes_patologicos_otros_1 == 'true') {
                        $('#adulto_antecedentes_patologicos_otros_1_especifique').val(response[0].antecedentes_patologicos_otros_1_especifique);
                        $('#adulto_antecedentes_patologicos_otros_1_especifique').show();
                    }
                    $('#adulto_antecedentes_patologicos_otros_2').val(response[0].antecedentes_patologicos_otros_2 == "true" ? "SI" : "NO");
                    if (response[0].antecedentes_patologicos_otros_2 == 'true') {
                        $('#adulto_antecedentes_patologicos_otros_2_especifique').val(response[0].antecedentes_patologicos_otros_1_especifique);
                        $('#adulto_antecedentes_patologicos_otros_2_especifique').show();
                    }
                    $('#adulto_terapia_antimicrobiana').val(response[0].terapia_antimicrobiana == "true" ? "SI" : "NO");
                    $('#adulto_frasco_1').val(response[0].frasco_1 == "true" ? "SI" : "NO");
                    if (response[0].frasco_1 == 'true') {
                        $('#adulto_frasco_1_opcion_1').val(response[0].frasco_1_opcion_1);
                        $('#adulto_frasco_1_opcion_2').val(response[0].frasco_1_opcion_2);
                        $('#adulto_frasco_1_opcion_1').show();
                        $('#adulto_frasco_1_opcion_2').show();
                    }
                    $('#adulto_frasco_2').val(response[0].frasco_2 == "true" ? "SI" : "NO");
                    if (response[0].frasco_2 == 'true') {
                        $('#adulto_frasco_2_opcion_1').val(response[0].frasco_2_opcion_1);
                        $('#adulto_frasco_2_opcion_2').val(response[0].frasco_2_opcion_2);
                        $('#adulto_frasco_2_opcion_1').show();
                        $('#adulto_frasco_2_opcion_2').show();
                    }
                    $('#adulto_frasco_3').val(response[0].frasco_3 == "true" ? "SI" : "NO");
                    if (response[0].frasco_3 == 'true') {
                        $('#adulto_frasco_3_opcion_1').val(response[0].frasco_3_opcion_1);
                        $('#adulto_frasco_3_opcion_2').val(response[0].frasco_3_opcion_2);
                        $('#adulto_frasco_3_opcion_1').show();
                        $('#adulto_frasco_3_opcion_2').show();
                    }
                    $('#adulto_hemocultivo_seguimiento_y_vigilancia').val(response[0].hemocultivo_seguimiento_y_vigilancia == "true" ? "SI" : "NO");
                    if (response[0].hemocultivo_seguimiento_y_vigilancia == 'true') {
                        $('#adulto_fecha_primer_resultado_hemocultivo').val(response[0].fecha_primer_resultado_hemocultivo);
                        $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').show();
                    }
                    $("#modalSolicitudHemocultivoAdulto").modal();
                }
            }
        });
        
    });

    $(document).ready(function() {
        $('#modalSolicitudCitoquimicosLiquidosBiologicos').on('hidden.bs.modal', function() {
            // Limpiamos los campos de entrada al ocultar el modal
            $('#modalSolicitudTestToleranciaGlucosaOral input').val("");

            $('#contenedor_tolerancia_paciente_pediatrico_ttgo').hide();
            $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').hide();
            $('#tolerancia_paciente_pediatrico').hide();

            $('#contenedor_tolerancia_paciente_adulto_ttgo').hide();
            $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').hide();
            $('#tolerancia_paciente_adulto').hide();

            $('#contenedor_tolerancia_paciente_gestante_ttgo').hide();
            $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').hide();
            $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').hide();
            $('#tolerancia_paciente_gestante').hide();

        });
        $('#modalSolicitudCitoquimicosLiquidosBiologicos').on('hidden.bs.modal', function() {
            // Limpiamos los campos de entrada al ocultar el modal
            $('#modalSolicitudCitoquimicosLiquidosBiologicos input').val("");
            $('#antecedentes_patologicos_personales').val('');
            $('#antecedentes_patologicos_familiares').val();
            $('#liquido_cefaloraquideo').prop('checked', false).prop('disabled', false);
            $('#liquido_pleural').prop('checked', false).prop('disabled', false);
            $('#liquido_ascitico').prop('checked', false).prop('disabled', false);
            $('#liquido_sinovial').prop('checked', false).prop('disabled', false);
            $('#liquido_otro').prop('checked', false).prop('disabled', false);
            $('#liquido_otro_especifique').val('');
            $('#liquido_otro_especifique').hide();
            $('#num_frasco_muestra_opcion').val('0');

        });
        $('#modalSolicitudHemocultivoAdulto').on('hidden.bs.modal', function() {
            // Limpiamos los campos de entrada al ocultar el modal
            $('#modalSolicitudHemocultivoAdulto input').val("");
            $('#adulto_frasco_1_opcion_1').val('0');
            $('#adulto_frasco_1_opcion_1').hide();
            $('#adulto_frasco_1_opcion_2').val('0');
            $('#adulto_frasco_1_opcion_2').hide();
            $('#adulto_frasco_2_opcion_1').val('0');
            $('#adulto_frasco_2_opcion_1').hide();
            $('#adulto_frasco_2_opcion_2').val('0');
            $('#adulto_frasco_2_opcion_2').hide();
            $('#adulto_frasco_3_opcion_1').val('0');
            $('#adulto_frasco_3_opcion_1').hide();
            $('#adulto_frasco_3_opcion_2').val('0');
            $('#adulto_frasco_3_opcion_2').hide();
        
        });
        $('#modalSolicitudHemocultivoPedriatria').on('hidden.bs.modal', function() {
            // Limpiamos los campos de entrada al ocultar el modal
            $('#modalSolicitudHemocultivoPedriatria input').val("");
            $('#pedriatria_postermino').prop('checked', false).prop('disabled', false);
            $('#pedriatria_termino').prop('checked', false).prop('disabled', false);
            $('#pedriatria_prematuro_leve').prop('checked', false).prop('disabled', false);
            $('#pedriatria_prematuro_moderado').prop('checked', false).prop('disabled', false);
            $('#pedriatria_prematuro_extremo').prop('checked', false).prop('disabled', false);
            $('#pedriatria_examen_laboratorio_otros_especifique').hide();
            $('#adulto_frasco_1_opcion_1').val('0');
            $('#adulto_frasco_1_opcion_1').hide();
            $('#adulto_frasco_1_opcion_2').val('0');
            $('#adulto_frasco_1_opcion_2').hide();
            $('#adulto_frasco_2_opcion_1').val('0');
            $('#adulto_frasco_2_opcion_1').hide();
            $('#adulto_frasco_2_opcion_2').val('0');
            $('#adulto_frasco_2_opcion_2').hide();
            $('#adulto_frasco_3_opcion_1').val('0');
            $('#adulto_frasco_3_opcion_1').hide();
            $('#adulto_frasco_3_opcion_2').val('0');
            $('#adulto_frasco_3_opcion_2').hide();
        
        });
    });


    // TAGSITEDS
    async function generarCodigoSiteds() {
        Swal.fire ({
            title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
            html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading ()
        },
        })
        $("#btn_generate_siteds").prop("disabled", true);
        $("#cod_siteds_susalud").prop("readonly", true);

        const prestacion = {!! json_encode($prestacion) !!}
        const id_ipress = {!! json_encode($id_ipress) !!}

        const codigoSitedsValid = await buscarSitedsGeneradoHoy(prestacion.nro_doc_ident, prestacion.id_tipo_atencion,id_ipress);
        if (codigoSitedsValid && codigoSitedsValid.cod_siteds_susalud) {
            $("#cod_siteds_susalud").val(codigoSitedsValid.cod_siteds_susalud);
            $("#cod_siteds_susalud").html(codigoSitedsValid.cod_siteds_susalud);
            Swal.fire({
                icon: "success",
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
                showConfirmButton: false,
                timer: '1500'
            })
            Swal.hideLoading();
            return codigoSitedsValid;
        }

        const body = {
            "idRemitente": prestacion.establecimiento_codigo,
            "nuRucRemitente": "",
            "idCorrelativo": prestacion.id,
            "tiDoPaciente": prestacion.id_tipo_doc_ident || "1",
            "nuDoPaciente": prestacion.nro_doc_ident,
            "noResponsableAut": "",
            "noPaResponsableAut": "",
            "noMaResponsableAut": "",
            "nuDoResponsableAut": "",
            "coAdmisionista": prestacion.nro_doc_ident,
            "beMaxInicial": "0",
            "coTiMoneda": "1",
            "coPagoFijo": "0",
            "coCalServicio": "VS",
            "coPagoVariable": "100",
            "flagCaGarantia": "0",
            "deFlagCartaGarantia": "Sigesapol 1.0",
            "tipoAtencionId": prestacion.id_tipo_atencion || "19",
            "servicioId": "172"
        }
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url:'/ws-dirsapol-siteds',
            type:'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(body),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: (result, textStatus, jqXHR) => {
                $("#cod_siteds_susalud").val(result.nuAutorizacion);
                $(".cod_siteds_susalud").html(result.nuAutorizacion);
                Swal.fire({
                    icon: "success",
                    title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
                    //html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${result.message || " Operación Exitosa"}</span></div>`,
                    //confirmButtonText: 'ENTENDIDO',
                    //allowOutsideClick: false,
                    //allowEscapeKey: false,
                    showConfirmButton: false,
                    timer: '1500'
                });
            },
            error: function(xhr, status, error){
                $("#cod_siteds_susalud").val("");
                $("#cod_siteds_susalud").prop("readonly", false);
                $("#btn_generate_siteds").prop("disabled", false);
                const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                Swal.fire({
                    icon: 'error',
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                    html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
            }, 
            complete: function(xhr, status) {
                Swal.hideLoading();
            }
        })
    }

    function buscarSitedsGeneradoHoy(asegurado_dni, tipo_atencion_id,id_ipress) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "get",
                url: `/api/laboratorio/buscar-siteds-generado-hoy/${asegurado_dni}/${tipo_atencion_id}/${id_ipress}`,
                dataType: "json",
                success: function (response) {
                    resolve(response);
                },
                error: function (error) {
                    reject(error);
                }
            });
        });
    }


</script>
{!! HTML::script('js/laboratorio/crear_Ins.js') !!}

<style>
  .readonly-select {
    background-color: #eee;
    color: #999;
    cursor: not-allowed;
}

  .readonly-select {
    background-color: #eee;
    color: #999;
    cursor: not-allowed;
}
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
        text-align:left;
    }

    .form-group{
        margin-bottom: 10px;
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
    /* font-size: 130%; */
    font-weight: bold;
    display: inline;
    }

    .contenedor-datos-generales input,
    .contenedor-especialista input,
    .contenedor-paciente input,
    .contenedor-referencia input{
        text-align: center;
    }

    .panel-body .analisis-clinico{
        overflow-x: scroll;
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
        <div class="panel panel-primary filterable">   

            <div class="panel-heading">
                <h3 class="panel-title">MÓDULO DE EXAMENES DE LABORATORIO</h3>      
            </div>

            <div style=" font-size: 11px" class="panel-body">
                <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_examen_laboratorio_lns') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" value="<?php echo $prestacion->id?>" name="id_prestacion" id="prestacion">
                <input type="hidden" value="<?php echo $id_ipress?>" name="id_ipress" id="id_ipress">
                <!-- <input type="hidden" value="" name="id_consultorio" id="id_consultorio"> -->
                <input type="hidden" value="{{ $id_tipo_atencion }}" name="id_tipo_atencion" id="id_tipo_atencion">
                <input type="hidden" value="{{ $laboratorio->id_evaluacion }}" name="id_evaluacion" id="id_evaluacion">
                <input type="hidden" value="{{ $laboratorio->id }}" name="id_laboratorio" id="id_laboratorio">
              
                    <div class="row" style="padding-bottom: 1rem; display: none;">
                        <div class="col-md-4 text-center">
                            <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        </div>
                        <div class="col-md-4" style="text-align: center">
                            <label><?php echo $prestacion->establecimiento ?></label><br>
                            <label style="font-size: 24px">ANALISIS DE LABORATORIO</label><br>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                            $disabled_laboratorio = "";
                            if($laboratorio->ruta_laboratorio==""){
                                $disabled_laboratorio = "disabled='disabled'";
                                echo '<div class="col-sm-12">
                                            <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                                                EL examen de laboratorio aun no ha sido firmada por el m&eacute;dico
                                            </div>
                                        </div>';
                            }
                        ?>
                    </div>


                    <div class="row">
                      <div class="col-md-6" style="height: 100%;">
                            <div class="panel panel-info" style="height: 190px;">
                                  <div class="panel-heading">
                                      <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                  </div>
                                  <div class="panel-body" style="margin: 0px 10px;">
                                 <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                    <tr>
                                        <th style="text-align: left; border: 0px solid;width: 30%;"><strong>N° DOCUMENTO</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                                            <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                                            <span id="nro_doc_ident" class="texto-negrita-14"> 
                                              <?php echo ($prestacion->tipodoc_titular); ?> - <?php  echo isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; border: 0px solid;width: 30%;"><strong>N° HISTORIA CLÍNICA</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                         
                                              <span class="texto-12"><?php echo isset($prestacion->nro_historia) ? $prestacion->nro_historia : ''; ?></span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                                            <span id="nombre_paciente" class="texto-negrita-14" >
                                                <?php echo $prestacion->asegurado_paterno . " " . $prestacion->asegurado_materno . ", " . $prestacion->asegurado_nombre; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid; text-transform: uppercase;">
                                            <span id="edad">
                                              <?php echo isset($prestacion->fecha_nac) ? $prestacion->fecha_nac : ''; ?>
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
                                        <th style="text-align: left; border: 0px solid;"><strong>SEXO</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid; text-transform: uppercase;">
                                            <span id="sexo"><?php echo isset($prestacion->sexo) ? ($prestacion->sexo == "M" ? "MASCULINO":"FEMENINO") : ''; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid; text-transform: uppercase;">
                                            <span id="tipo_beneficiario"><?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?></span>
                                            
                                            <strong id="referido">({{ $referencia == 4 ? 'REFERIDO' : 'NO REFERIDO' }})</strong>
                                        </td>
                                    </tr>
                                </table>

                                  </div>
                            </div>
                      </div>
                      <!-- Columna derecha: Datos Generales -->
                      <div class="col-md-6">
                          <div class="panel panel-info contenedor-datos-generales" style="height: 190px;">
                            <div class="panel-heading">
                              <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                            </div>
                            <div class="panel-body" style="margin: 0px 10px;">
                              <table class="table table-borderless" style="width: 100%; margin: 0px;">
                                                                <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>COD.SITEDS SUSALUD</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid; text-transform: uppercase;">
                                    @if (isset($codigo_siteds))
                                      <span id="cod_siteds_susalud">{{ $codigo_siteds->cod_siteds_susalud }}</span>
                                    @else
                                    <span class="cod_siteds_susalud"></span>
                                      <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud"
                                            onkeypress="return esEntero(event);" maxlength="12" pattern="[0-9]*"
                                            style="text-transform: uppercase; border-color: red !important; border: 2px red solid !important;">
                                    @endif
                                  </td>
                                </tr>
                                <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>TIPO ATENCIÓN</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid; text-transform: uppercase;">
                                    <span id="tipo_atencion">{{ $prestacion->tipo_atencion }}</span>
                                  </td>
                                </tr>
                                <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid; text-transform: uppercase;">
                                    <span id="procedencia">  {{ $prestacion->servicio_cargo }}</span>
                                  </td>
                                </tr>
                                 <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>UBICACIÓN DE PACIENTE</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid; text-transform: uppercase;">
                                    <strong id="procedencia" style="font-size: 14px;"> {{ $prestacion->ubicacion }} / {{ $prestacion->nmro_cama }}</strong>
                                  </td>
                                </tr>

                                <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>FECHA ATENCIÓN</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                    <span id="fecha_atencion">{{ $prestacion->fecha_atencion }}</span>
                                  </td>
                                </tr>
                           
                                <tr>
                                  <th style="text-align: left; border: 0px solid;"><strong>PROFESIONAL DE SALUD</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                    <span id="nombre_medico">
                                      <?php echo isset($prestacion->dni) ? $prestacion->dni : ''; ?> -
                                      <?php echo $prestacion->medico_paterno . ' ' . $prestacion->medico_materno . ', ' . $prestacion->medico_nombre; ?>
                                      <strong>
                                        (<?php echo isset($prestacion->profesion) ? $prestacion->profesion : ''; ?>)
                                      </strong>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>

                    </div>


     
                         
        
     
                    <div class="panel panel-default" style="display: none;">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">DIAGNOSTICO</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                            <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="">
                                <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                            </select>
                            <?php else: ?>
                                <div id="entregar_receta" >
                                    <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                        <thead>
                                            <tr>
                                                <th class="col-lg-8">Nombre y Código :</th> <!-- Columna "Nombre y Código" ahora ocupa la mitad del ancho -->
                                                <th class="col-lg-4">Tipo de DX :</th>
                                                <!-- Columna "Descripción" también ocupa la mitad del ancho -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($diagnostico as $row):?>
                                            <tr>
                                                <td class="col-lg-8">
                                                    <!-- Aquí se muestra el nombre y el código -->
                                                    <input type="text" class="form-control input-sm" style="text-align: left;" readonly="" value="<?php echo $row->codigo." - ".$row->nombre?>" />
                                                </td>
                                                <td class="col-lg-4">
                                                    <!-- Aquí se muestra la descripción -->
                                                    <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="<?php echo $row->descripcion?>" />
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading" style="position: relative; text-align: center;">
                            <h3 class="panel-title size-titulo" style="margin: 0;">LISTA DE EXÁMENES CLÍNICOS</h3>
                            
                            <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); white-space: nowrap;">
                                <strong>Leyenda:</strong>
                                <span style="margin-left: 10px; color: green; font-weight: bold;">R</span>
                                <span style="margin-right: 15px;">Con reactivo</span>
                                <span style="color: red; font-weight: bold;">SR</span>
                                <span>Sin Reactivo</span>
                            </div>
                        </div>
                        <div class="panel-body analisis-clinico">
                            <table id="tblExamenes" cellspacing="0" style="width: 150rem;">
                                <thead>
                                <tr>
                                        <th style="text-align: left; width: 20rem;">Nombre de Exámen</th>                                         
                                        <th style="text-align: center; width: 5.5rem;">Cantidad</th>    
                                        <th style="text-align: center; width: 5.5rem;">¿Pendiente?</th>                                                             
                                        <th style="text-align: center; width: 5.5rem;">Diagnóstico</th>
                                        <th style="text-align: center; width: 10rem;">Tipo de Muestra</th>
                                        <th style="text-align: left; width: 20rem;">Observación</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0; 
                                    foreach($proclaboratorio as $row):
;                                ?>
                                <tr style="border-bottom: 1px solid #e0e9e0 ;">
                                    <td>
                                        <input type="checkbox" class="exam-checkbox exam-checkbox" name="prueba[]" checked value="<?php echo $row->id ?>">

                                        <span class="status-indicator ml-2" style="margin-left: 15px; color: green; font-weight: bold; font-size: 20px;">R</span>
                                        <span class="checkboxtexto texto-negrita-mayuscula" >&nbsp;&nbsp;&nbsp;<?php echo $row->codigo." - ".$row->nombre?></span>

                                        
                                    </td>
                                    <td style="text-align: center;">
                                    <span class=" ml-2" >{{ $row->cantidad }}</span>
                                        <input type="hidden" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->cantidad }}" />
                                    </td>
                                    <td>
                                    <select class="form-control pendiente" name="pendiente[]">
                                        <option value="NO" >NO</option>
                                            <option VALUE ="SI">SI</option>
                                    </select>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class=" ml-2 texto-con-puntos" title="{{ $row->relacion_diagnostico }}" >{{ $row->cod_diag }}</span>
                                        <input type="hidden" class="form-control input-sm" style="text-align: left;" readonly="" value="{{ $row->relacion_diagnostico }}" />
                                    </td>
                                        <td style="padding-left: 1rem; text-align: center;">
                                            <span class=" ml-2" >{{ $row->nombre_tipo_muestra }}</span>
                                            <input type="hidden" class="form-control input-sm" style="text-align: left;" readonly="" value="{{ $row->nombre_tipo_muestra }}" />
                                        </td>
                                    <td style="padding-left: 1rem;">
                                        <span class=" ml-2" >{{ $row->observacion }}</span>
                                        <input type="hidden" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->observacion }}" />
                                    </td>
                                    {{-- <td>
                                        @if (!empty($row->id_solicitud_procedimiento))    
                                            <div style="display: flex; width: 100%; height: 100%; justify-content: center;">
                                                <button type="button" class="btn btn-success btn-xs verSolicitudProcedimiento" id="verSolicitudProcedimiento{{ $i }}" data-id='{{ $i }}' data-id-solicitud-procedimiento='{{ $row->id_solicitud_procedimiento }}' style="margin-left: 0.5rem;"><i class="fa fa-search"></i></button>
                                            </div>
                                        @endif
                                    </td> --}}
                                </tr>
                                <?php
                                    $i++; 
                                    endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <label class="control-label col-lg-3" style="text-align: left; padding-bottom: 2rem;">CANTIDAD DE ANÁLISIS CLÍNICO: {{$total_examenes_clinicos}}</label>
                        <label class="control-label col-lg-4" style="text-align: left; padding-bottom: 2rem;"></label>
                        <label class="control-label col-lg-4" style="text-align: left; padding-bottom: 2rem;">ENCARGADO: {{ Auth::user()->name }}, {{ Auth::user()->paterno }} {{ Auth::user()->materno }}</label>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <label class="control-label" for="observacion">OBSERVACION</label>
                            <input type="text" name="observacion" id="observacion" class="form-control input-sm text-uppercase" autocomplete="off" >
                        </div>
                        <div class="col-lg-4" style="padding-top: 2rem;">
                            <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" style="padding: 5.3px;display: none;" id="btnDetallePrestaciones"><i class="fa fa-list"></i> Historial de atenciones</button>
                        </div>
                    </div>
                    <div class="row" style="padding: 1.5rem 0;">
                        <div class="col-sm-6">
                            <a href="/all_laboratorio_prescritos_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                        </div>
                        <div class="col-sm-6">
                            <input onclick="validarFormulario();" type="button" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                        </div>
                    </div>
                </form>
            </div>  
        </div> <!-- .panel -->
      </div><!-- .row -->
    </div><!-- .col -->
  </div><!-- .row -->
</div><!-- .container -->

<div class="modal fade" id="showDetallePrestacionLab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">Atenciones Médicas</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código de Atención</small></th>
                                <th><small>Fecha <br/>Orden</small></th>
                                <th><small>Tipo de Atención</small></th>
                                <th><small>Establecimiento de Referencia</small></th>
                                <th><small>Consultorio de Procedencia</small></th>
                                <th><small>Médico</small></th>
                                {{-- <th><small>Nro. Aten-<br>ciones</small></th> --}}
                                <th><small>Detalle<br/>Examenes</small></th>
                            </tr>
                        </thead>
                        <tbody id="detalle-prestacion"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showExamenesLabo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Examenes</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código</small></th>
                                <th><small>Descipción</small></th>
                                <th><small>Cantidad</small></th>
                                <th><small>Días Transcurridos</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-examenes"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.laboratorios.modal_solicitud_procedimiento')
@include('frontend.includes.footer')
@endsection
