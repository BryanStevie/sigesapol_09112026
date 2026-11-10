@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<style>

    .small-input {
        width: 50px; /* Adjust the width as per your preference */
    }
    .small-inputr {
        width: 150px; /* Adjust the width as per your preference */
    }
    .small-inputr1 {
        width: 150px; /* Adjust the width as per your preference */
    }

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

  .table-borderless{
         margin-bottom: 0px !important;
  }
  .table-borderless tbody > tr > td,.table-borderless tbody > tr > th
  {
  padding: 2px 8px !important;
  border: 0px solid;
  }
  .cod_siteds_susalud{
    font-weight: bold;
  }

  .readonly-select {
    background-color: #eee;
    color: #999;
    cursor: not-allowed;
}           
</style>

<script>
    $(document).ready(function () {
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
	
    function seleccionGrupo(id_procedimiento, obj){
        let fila = $(obj).closest('tr');
        let statusIndicator = fila.find('.status-indicator');
        let input_pendiente = fila.find('.pendiente');
        // let input_confirmar_toma = fila.find('input[name="confirmar_toma[]"');
        
        // Si no está checkeado
        if($(obj).is(':checked')) {

            //if ($('#estadooriginal_'+id_procedimiento).val() == "" || $('#estadooriginal_'+id_procedimiento).val() == "1" || $('#estadooriginal_'+id_procedimiento).val() == "4") {
                $('#estado_'+id_procedimiento).prop("value", "3");
                $('#estado_'+id_procedimiento).prop("disabled", false);
                $('#idRua_'+id_procedimiento).prop("disabled", false);
                $('#id_prestacion_procedimiento_'+id_procedimiento).prop("disabled", false);

                $('#desc_estado_'+id_procedimiento).prop("value", "AGREGADO");
                $('#desc_estado_'+id_procedimiento).css("color", "blue");

                input_pendiente.prop("disabled", false);
                input_pendiente.val("NO");
                statusIndicator.text('R').css('color', 'green');
            /*} else {
                $('#estado_'+id_procedimiento).prop("value", "3");
                $('#desc_estado_'+id_procedimiento).prop("value", "REGISTRADO");
                $('#desc_estado_'+id_procedimiento).css("color", "green");
            }*/
        
        
        } else {
            // if ($('#estadooriginal_'+id_procedimiento).val() == "" || $('#estadooriginal_'+id_procedimiento).val() == "1" || $('#estadooriginal_'+id_procedimiento).val() == "4") {
                $('#estado_'+id_procedimiento).prop("value", "4");
                $('#estado_'+id_procedimiento).prop("disabled", true);
                $('#idRua_'+id_procedimiento).prop("disabled", true);
                $('#id_prestacion_procedimiento_'+id_procedimiento).prop("disabled", true);

                $('#desc_estado_'+id_procedimiento).prop("value", "SR");
                $('#desc_estado_'+id_procedimiento).css("color", "black");

                input_pendiente.prop("disabled", true);
                input_pendiente.val("NO");
                statusIndicator.text('SR').css('color', 'red');
            /*} else {
                $('#estado_'+id_procedimiento).prop("value", "1");
                $('#desc_estado_'+id_procedimiento).prop("value", "ELIMINADO");
                $('#desc_estado_'+id_procedimiento).css("color", "red");
            }*/
        }
    }

    /*var modificados = []; // Variable global para almacenar cambios

    // Al detectar un cambio en cualquier checkbox dentro de la tabla
    $("input[type='checkbox'][name='prueba[]']").change(function() {
        var id = $(this).attr("id"); // Obtener el ID del checkbox
        var id_solo = id.replace("check_", ""); // Obtener solo el número del ID
        var estadoOriginal = $("#estadooriginal_" + id_solo).val(); // Obtener estado original
        var estadoActual = $(this).prop("checked") ? "0" : "4"; // Definir estado nuevo

        // Verificar si ya existe en la lista de modificados
        var index = modificados.findIndex(item => item.id === id);

        if (estadoOriginal !== estadoActual) {
            if (index === -1) {
                // Si no está en la lista, lo agregamos
                modificados.push({
                    id: id,
                    estado_original: estadoOriginal,
                    estado_actual: estadoActual
                });
            } else {
                // Si ya está en la lista, actualizamos su estado
                modificados[index].estado_actual = estadoActual;
            }
        } else {
            // Si vuelve a su estado original, lo eliminamos del array
            if (index !== -1) {
                modificados.splice(index, 1);
            }
        }

        // Mostrar los cambios en consola
        console.log("Checkboxes modificados:", modificados);
    });*/

    function validarFormulario() {
        Swal.fire({
            title: '<h5><b>Guardando cambios de la Orden ...</b></h5>',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $("#frmOrdenLaboratorio").submit();
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
    }

    .form-group{
        margin-bottom: 10px;
    }

    input[type=checkbox] {
        /* Doble-tamaño Checkboxes */
        -ms-transform: scale(2); /* IE */
        -moz-transform: scale(2); /* FF */
        -webkit-transform: scale(2); /* Safari y Chrome */
        -o-transform: scale(2); /* Opera */
        padding: 10px;
    }

    .checkboxtexto {
        /* Checkbox texto */
        /* font-size: 130%; */
        display: inline;
    }
    
    .readonly-center {
        background-color:#eeeeee;
        text-align: center!important;
    }

    
    .contenedor-datos-generales input,
    .contenedor-especialista input,
    .contenedor-paciente input,
    .contenedor-referencia input{
        text-align: center;
    }

    .size-titulo {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
    }
    
    .panel-body .analisis-clinico{
        overflow-x: scroll;
    }
</style>
@endsection
@section('content')

<div class="container" style="width:80%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title">MÓDULO DE EXAMENES DE LABORATORIO</h3>      
                    </div>
                    <div style=" font-size: 11px" class="panel-body">
                        <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" class="form-horizontal" role="form" method="post"  action="{{ route('editar_examen_laboratorio_lns') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" value="<?php echo $prestacion->id?>" name="id_prestacion" id="prestacion">
                            <input type="hidden" value="<?php echo $id_ipress?>" name="id_ipress" id="id_ipress">
                            <input type="hidden" value="{{ $prestacion->id_consultorio }}" name="id_consultorio" id="id_consultorio">
                            <input type="hidden" value="{{ $id_tipo_atencion }}" name="id_tipo_atencion" id="id_tipo_atencion">
                            <input type="hidden" value="{{ $laboratorio->id }}" name="id_laboratorio" id="id_laboratorio">
                            <input type="hidden" value="{{ $laboratorio->id_evaluacion }}" name="id_evaluacion" id="id_evaluacion">

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
                                <div class="col-md-6" style="">
                                    <div class="panel panel-info" style="height: 100%;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                        </div>
                                        <div class="panel-body" style="margin: 0px 10px;">
                                            <div class="row">
                                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;width: 30%;"><strong>N° DE DOCUMENTO</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="border: 0px solid;">
                                                            <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />                                                
                                                            <span id="nro_doc_ident" style="font-weight: bold; font-size: 14px;"> {{$prestacion->tipo_doc_ident}} - {{$prestacion->nro_doc_ident}}</span>
                                                            <strong class="texto-padding-l-10 texto-negrita-14">N° HISTORIA CLÍNICA:</strong>
                                                            <span id="nro_historia" class="texto-negrita-14"><?php echo isset($prestacion->nro_historia) ? $prestacion->nro_historia : ''; ?></span>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="nombre_paciente" style="font-weight: bold; font-size: 14px;">
                                                                <?php echo $prestacion->asegurado_paterno . " " . $prestacion->asegurado_materno . ", " . $prestacion->asegurado_nombre ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style=" border: 0px solid;">
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
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="sexo"><?php echo isset($prestacion->sexo) ? ($prestacion->sexo =="M" ? "MASCULINO":"FEMENINO") : ''; ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="tipo_beneficiario"><?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?></span>
                                                            <strong style="margin-left: 15px;">CONDICIÓN:</strong>
                                                            <span id="referido">{{ $referencia == 4 ? 'REFERIDO' : 'NO REFERIDO' }}</span>
                                                        </td>
                                                    </tr>                                    
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha: Datos Generales -->
                                <div class="col-md-6">
                                    <div class="panel panel-info contenedor-datos-generales" style="height: 175px;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                                        </div>
                                        <div class="panel-body" style="margin: 0px 10px;">
                                            <div class="row">
                                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>COD.SITEDS SUSALUD</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            @if (isset($prestacion->codigo_siteds))
                                                                <span id="cod_siteds_susalud texto-negrita-14">{{ $prestacion->codigo_siteds }}</span>
                                                            @else
                                                                <span class="cod_siteds_susalud texto-negrita-14"></span>
                                                                <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud"
                                                                    onkeypress="return esEntero(event);"
                                                                    style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"
                                                                    maxlength="12" pattern="[0-9]*">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>TIPO ATENCIÓN</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="prioridad">{{ $prestacion->tipo_atencion }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="procedencia" style="font-size: 14px; font-weight: bold;">  {{ $prestacion->servicio }}</span>
                                                        </td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>FECHA ATENCIÓN</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="border: 0px solid;">
                                                            <span id="fecha_atencion">{{ $prestacion->fecha_atencion }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; border: 0px solid;"><strong>PROFESIONAL DE SALUD</strong></th>
                                                        <th style="text-align: left; border: 0px solid;">:</th>
                                                        <td style="text-transform: uppercase; border: 0px solid;">
                                                            <span id="dni_medico">
                                                            <?php echo isset($prestacion->dni) ? $prestacion->dni : ''; ?> -
                                                            <?php echo $prestacion->medico_paterno . " " . $prestacion->medico_materno . ", " . $prestacion->medico_nombre ?>
                                                            <strong style="font-weight: bold;">(<?php echo isset($prestacion->especializacion) ? $prestacion->especializacion : ''; ?>)</strong>
                                                            </span>
                                                            
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                    
                            <div class="panel panel-info contenedor-referencia" id="contenedorReferencia" style="display: none;">
                                <div class="panel-heading">
                                    <h3 class="panel-title size-titulo">INFORMACIÓN DE REFERENCIA</h3>
                                </div>
                                <div class="panel-body">
                                    @if (isset($referencia_datos))
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label>ESTABLECIMIENTO REFERENCIA</label>
                                                <input type="text" readonly="" value="{{ $referencia_datos->nombre_establecimiento_referencia }}" id="unidad_policia" name="unidad_policia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <label>DOCUMENTO REFERENCIA</label>
                                                <input type="text" readonly="" value="{{ $referencia_datos->id_referencia }}" name="consultorio" id="consultorio" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label>SERVICIO DE REFERENCIA</label>
                                                <input type="text" class="form-control" readonly=""  value="{{ $referencia_datos->nombre_servicio_referencia }}" name="servicio_procedencia" id="servicio_procedencia">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <br>
                                            <div class="col-md-4">
                                                <label>ESPECIALISTA DE REFERENCIA</label>
                                                <input type="text" class="form-control" readonly=""  value="{{ $referencia_datos->nombres_medico_referencia }}" name="servicio_procedencia" id="servicio_procedencia">
                                            </div>
                                            <div class="col-md-8">
                                                <label>OBSERVACIÓN DE REFERENCIA</label>
                                                <input type="text" class="form-control" readonly=""  value="{{ $referencia_datos->observacion_referencia }}" name="servicio_procedencia" id="servicio_procedencia">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title size-titulo">DIAGNOSTICO DE REFERENCIA</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div id="entregar_receta">
                                                    <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                                        <thead>
                                                            <tr>
                                                                <th width="90%">Nombre y Código</th> <!-- Columna "Nombre y Código" ahora ocupa la mitad del ancho -->
                                                                <th width="10%"  style="text-align:center;">Tipo de DX</th>
                                                                <!-- Columna "Descripción" también ocupa la mitad del ancho -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ( isset($diagnostico_referencia) )
                                                                @foreach ($diagnostico_referencia as $row)
                                                                <tr>
                                                                    <td>
                                                                        <!-- Aquí se muestra el nombre y el código -->
                                                                        <input type="text" class="form-control input-sm" style="text-align:left;width:95%" readonly="" value="{{ $row->codigo." - ".$row->nombre }}" />
                                                                    </td>
                                                                    <td>
                                                                        <!-- Aquí se muestra la descripción -->
                                                                        <input type="text" class="form-control input-sm" style="text-align:center;width:100%" readonly="" value="{{ $row->descripcion }}" />
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                    <table id="tblExamenes" cellspacing="0" style="width: 120rem;">
                                        <thead>
                                        <tr>
                                            <th style="display:none">id_rua</th>
                                            <th style="text-align: center; width: 5.5rem;"></th>
                                            <th style="text-align: left; width: 23rem;">Examen</th>
                                            <th style="text-align: left; width: 3rem;">Estado</th>
                                            <th style="text-align: left; width: 3rem;">¿Pendiente?</th>
                                            <th style="text-align: center; width: 20rem;">Observación</th>
                                            <th style="text-align: center; width: 5.5rem;">Cantidad</th>
                                            <th style="text-align: center; width: 5.5rem;">Diagnóstico</th>
                                            <th style="text-align: center; width: 20rem;">Tipo de Muestra</th>
                                            <th style="display:none">id_prestacion_procedimiento</th>
                                            <th style="display:none">migrado_lab</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($proclaboratorio as $row)
                                                <?php
                                                    $is_toma_confirmada = false;
                                                    $is_checked = "";
                                                    $is_disabled_checkbox = "";
                                                    $is_disabled = "";

                                                    if ($row->confirmar_toma == "0" || $row->confirmar_toma == "3"):
                                                        $is_toma_confirmada = true;
                                                        $is_checked = "checked";
                                                        $is_disabled_checkbox = "disabled";
                                                        $is_disabled = "disabled";

                                                    elseif (is_null($row->confirmar_toma)):

                                                        if ($row->pendiente_laboratorio):
                                                            $is_checked = "checked";
                                                        elseif (!$row->pendiente_laboratorio):
                                                            $is_disabled = "disabled";
                                                        endif;
                                                    endif;
                                                ?>
                                                <tr>
                                                    <td style="width:10%; display:none">
                                                        <input type="text" name="idRua[]" id="idRua_{{ $row->id_rua }}" value="{{ $row->id_rua }}" {{$is_disabled}} class="form-control" />
                                                    </td>
                                                    <td>
                                                        <input class="exam-checkbox" type="checkbox" name="prueba[]" {{$is_checked}} {{$is_disabled_checkbox}} id="check_{{ $row->id_rua }}"  
                                                        value="{{ $row->id_rua }}" onChange="seleccionGrupo('{{ $row->id_rua }}',this);" />

                                                        @if ($is_toma_confirmada || $row->pendiente_laboratorio)
                                                            <span class="status-indicator ml-2" style="margin-left: 15px; color: green; font-weight: bold; font-size: 20px;">R</span>
                                                        @else
                                                            <span class="status-indicator ml-2" style="margin-left: 15px; color: red; font-weight: bold; font-size: 20px;">SR</span>
                                                        @endif
                                                        <input type="hidden" class="form-control" name="confirmar_toma[]" id="confirmar_toma_{{ $row->id_rua }}" value="{{ $row->confirmar_toma }}" {{$is_disabled}} />
                                                    </td>
                                                    <td>                                  
                                                        <span class="checkboxtexto" style="font-size: 14px;font-weight: bold;"><?php echo $row->codigo." - ".$row->nombre?></span>
                                                    </td>
                                                    <td>
                                                        <!-- Campo de "Estado" aquí -->
                                                        @if ($is_toma_confirmada)
                                                            <input type="text" style="color: green; font-weight: bold; font-size: 16px; text-align: center !important;" id="<?php echo "desc_estado_".$row->id_rua ?>" value="REGISTRADO" readonly="" class="form-control small-inputr">
                                                        @elseif ($row->pendiente_laboratorio)
                                                            <input type="text" style="color: green; font-weight: bold; font-size: 16px; text-align: center !important;" id="<?php echo "desc_estado_".$row->id_rua ?>" value="PENDIENTE" readonly="" class="form-control small-inputr">
                                                        @else
                                                            <input type="text" style="color: black; font-weight: bold; font-size: 16px; text-align: center !important;" id="<?php echo "desc_estado_".$row->id_rua ?>" value="SR" readonly="" class="form-control small-inputr1">
                                                        @endif
                                                    </td>
                                                    <td style="padding-left: 1rem;">
                                                        <select class="form-control pendiente" name="pendiente[]" id="pendiente_{{ $row->id_rua }}" {{$is_disabled}}>
                                                            <option value="SI" @if($row->pendiente_laboratorio) selected @endif >SI</option>
                                                            <option value="NO" @if(!$row->pendiente_laboratorio || $row->pendiente_laboratorio == null) selected @endif >NO</option>
                                                        </select>
                                                    </td>
                                                    <td style="padding-left: 1rem;">
                                                        <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->observacion }}" />
                                                    </td>
                                                    <td style="padding-left: 1rem;">
                                                        <input type="text" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->cantidad }}" />
                                                    </td>
                                                    <td style="padding-left: 1rem;">
                                                        <input type="text" class="form-control input-sm" style="text-align: left;" readonly="" value="@if ($row->relacion_codigo_diagnostico !== "") {{ $row->relacion_codigo_diagnostico }} - {{ $row->relacion_nombre_diagnostico }} @else  @endif" />
                                                    </td>
                                                    <td style="padding-left: 1rem;">
                                                    
                                                        <input type="text" class="form-control input-sm" style="text-align: left;" readonly="" value="{{ $row->tipo_muestra_nombre }}" />
                                                    </td>
                                                    <td style="display:none">
                                                        <input type="text" class="form-control" name="id_prestacion_procedimiento[]" id="id_prestacion_procedimiento_{{ $row->id_rua }}" value="{{ $row->id_prestacion_procedimientos }}" {{$is_disabled}} />
                                                    </td>
                                                    <td style="display:none">
                                                        <input type="text" class="form-control" name="migrado_lab[]" id="migrado_lab_{{ $row->id_rua }}" value="{{ $row->migrado_lab }}" {{$is_disabled}} />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-lg-6" style="text-align: left; padding-bottom: 2rem;">CANTIDAD DE ANALISIS CLINICOS : {{$total_examenes_clinicos}} </label>
                            </div>
                            <div class="row" style="padding: 0 1.5rem;">
                                <label class="control-label" for="observacion">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" value="{{$laboratorio->observacion_toma_muestra}}" class="form-control input-sm text-uppercase" readonly="false" >
                            </div>
                            <div class="row" style="padding: 0 1.5rem;">
                                <label for="observacion_modificacion">OBSERVACION DE LA MODIFICACIÓN</label>
                                <input type="text" name="observacion_modificacion" id="observacion_modificacion" value="{{$laboratorio->obs_lastupdate_tomamuestra}}" class="form-control input-sm text-uppercase" autocomplete="off" >
                            </div>
                            <div class="row" style="padding: 0 1.5rem; display: none;">
                                <label class="control-label">Nro. Examenes de laboratorio por atención: </label>&nbsp;&nbsp;
                                <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" id="btnDetallePrestaciones"><i class="fa fa-list"></i> Detalle</button>
                            </div>
                            <div class="row" style="padding: 1.5rem 0;">
                                <div class="col-md-6">
                                    <a href="/all_laboratorio_prescritos_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                                </div>

                                <div class="col-md-6">
                                    <input type="button" onclick="validarFormulario();" value="GUARDAR CAMBIOS" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
    
@include('frontend.includes.footer')
@endsection
