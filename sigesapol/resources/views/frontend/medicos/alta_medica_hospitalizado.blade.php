@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->

<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<style>
  .modal-dialog {
    width: 90%;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
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

  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
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

  input[readonly] {
    background-color: #eeeeee;
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
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }

  .fc-day-grid-event :hover {
    color: #009966
  }

  .fc-content {
    padding: 5px !important;
  }

  .fc-title {
    text-align: center;
    font-size: 12px
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
</style>

<script>
  //<![CDATA[
    var documentName_ = null;
    //
    window.addEventListener('getArguments', async function(e) {
    await ObtieneArgumentosParaFirmaDesdeLaWeb();
    });

    async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient('sendArguments', arg);
    }

    window.addEventListener('invokerOk', function(e) {
    MiFuncionOkWeb();
    });

    window.addEventListener('invokerCancel', function(e) {
    MiFuncionCancel();
    });


    async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

    $.post("{{route('postArguments')}}", {
        _token: CSRF_TOKEN,
        type: "W",
        documentName: $('#doc_firmar').val(),
        entidad: <?=Auth::user()->dni?>
    }, function(data, status) {
        document.getElementById("argumentos").value = data;
        getArguments();
    });

    }

    async function MiFuncionOkWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    var id_resol = $('#id_prestacion').val();
    $.post("{{route('actualiza_prestacion')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "prestacion",
        id_resol: id_resol,
    }, function(data, status) {
        $.post("{{route('actualiza_prestacion')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "receta",
        id_resol: id_resol,
        }, function(data, status) {
        $.post("{{route('actualiza_prestacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "laboratorio",
            id_resol: id_resol,
        }, function(data, status) {
            $.post("{{route('actualiza_prestacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "radiologia",
            id_resol: id_resol,
            }, function(data, status) {
            location.reload();
            });
        });
        });
    });
    }

    function MiFuncionCancel() {
    alert("El proceso de firma digital fue cancelado.");
    }
    //]]>

    function getVentiladorUci(){
	    var uci = $('#id_uci').val();

	    if(uci=='1'){
	        $("#div_uci_ventilador").show();
	    }
	    else{
	        $("#div_uci_ventilador").hide();
	    }
	}

    function getAmbulancia(){
	    var ambulancia = $('#id_ambulancia').val();

	    if(ambulancia=='1'){
	        $("#div_ambulancia").show();
	    }
	    else{
	        $("#div_ambulancia").hide();
	    }
	}

    $(document).ready(function() {


    if ($("#flag_firmar").val() == 0) {
        $("#btnfirmar").hide();
    } else {
        $("#btnfirmar").show();
    }


    $(document).on('click', '#btnfirmar', function(e) {
        e.preventDefault();
        firmar();
    })

    $('#fecha_atencion').datepicker();

    $('#btnPrestacion').on("click", function(e) {
        guardarAltaMedica();
    });

    $('#addDiagnostico').on('click', function() {
        AddDiagnostico();
    });

    $('#addDerivarServicio').on('click', function() {
        var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
        $("#divServicio").hide();
        if (addDerivarServicio == "false") {
        $("#divServicio").show();
        }
    });

    $('#addRecetaVale').on('click', function() {
        var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
        $("#fsRecetaVale").hide();
        if (addRecetaVale == "false") {
        $("#fsRecetaVale").show();
        }
    });

    $('#addExamenLab').on('click', function() {
        var addExamenLab = $('#addExamenLab').attr("aria-pressed");
        $("#fsExamenLab").hide();
        if (addExamenLab == "false") {
        $("#fsExamenLab").show();
        }
    });

    $('#addExamenRad').on('click', function() {
        var addExamenRad = $('#addExamenRad').attr("aria-pressed");
        $("#fsExamenRad").hide();
        if (addExamenRad == "false") {
        $("#fsExamenRad").show();
        }
    });

    $('#addAlta').on('click', function() {
        var addAlta = $('#addAlta').attr("aria-pressed");
        $("#divAlta2").hide();
        if (addAlta == "false") {
        $("#divAlta2").show();
        }
    });

    $('#addReferencia').on('click', function () {
		var addReferencia = $('#addReferencia').attr("aria-pressed");
		$("#fsReferencia").hide();
		if(addReferencia == "false"){
			$("#fsReferencia").show();
		}
	});

    $('#example tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    $('#tblExamenLab tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    
    $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    $("#id_servicio_derivar").select2({
        width: 'resolve'
    });

    $("#id_farmacia_receta").select2({
        width: 'resolve'
    });

    $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");

    modalDiagnostico();
    modalProcedimiento();
    modalLaboratorio();
    modalRadiologia();
    modalInterconsulta();
    modalReferencia();
    modalRecetaVale();
    modalAtencion();
    modalEvolucion();
    });


    function guardarAltaMedica() {

    var msg = "";

    var addReferencia = $('#addReferencia').attr("aria-pressed");
    
    var ind_diag = $('#tblDiagnostico tbody tr').length;
    if(ind_diag > 0){
        fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
        for (i = 0; i < ind_diag; i++) {
        fila_dignostico=fila_dignosticos[i];
        var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
        var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
        if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
        if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
        }
    }else{
        msg+="Debe ingresar minimo un diagnostico<br>";
    }

    var fecha_egreso = $('#fecha_egreso').val();
    if(fecha_egreso==""){msg+="Debe ingresar la Fecha de egreso<br>";}

    var hora_egreso = $('#hora_egreso').val();  
    if(hora_egreso==""){msg+="Debe ingresar la Hora de egreso<br>";}

    var id_tipo_egreso = $('#id_tipo_egreso').val();  
    if(id_tipo_egreso==""){msg+="Debe ingresar el Tipo de egreso<br>";}

    var id_condicion_alta = $('#id_condicion_alta').val();  
    if(id_condicion_alta==""){msg+="Debe ingresar la condición de alta<br>";}

    //alert(id_tipo_egreso);
    if(id_tipo_egreso=="1"){ //ALTA
        var derivar = $('#derivar').val();  
        if(derivar=="SI"){
            var id_servicio_consulta_externa = $('#id_servicio_consulta_externa').val();  
            if(id_servicio_consulta_externa==""){msg+="Debe seleccionar el Servicio de Consulta externa<br>";}
        }

    }else if(id_tipo_egreso=="2"){ //HOSPITALIZACIÓN 
            var id_servicio_hospitalizacion = $('#id_servicio_hospitalizacion').val();  
            if(id_servicio_hospitalizacion==""){msg+="Debe seleccionar el Servicio de hospitalización<br>";}  

    }else if(id_tipo_egreso=="3"){ //DEFUNCIÓN
            var causas_muerte = $('#causas_muerte').val();  
            if(causas_muerte==""){msg+="Debe ingresar las causas de muerte<br>";}
    }

    if(addReferencia == "true"){
        var id_establecimiento_referencia = $('#id_establecimiento_referencia').val();
        var id_uci = $('#id_uci').val();
        if(id_uci==1)
            var id_uci_cv= $('#id_uci_cv').val();
            var id_ambulancia = $('#id_ambulancia').val();
        if(id_ambulancia==1){
            var id_ambulancia_ref = $('#id_ambulancia_ref').val();
            var id_tipo_ambulancia = $('#id_tipo_ambulancia').val();
        }
        var observacion_referencia = $('#observacion_referencia').val();
        var id_pasaje = $('#id_pasaje').val();
        var id_acompanante = $('#id_acompanante').val();
        var id_situacion = $('#id_situacion').val(); 
        var observacion_referencia = $('#observacion_referencia').val();  

        if(id_establecimiento_referencia == "")msg+="Debe elegir un establecimiento<br>";
        if(id_uci == "")msg+="Debe especificar si quiere UCI<br>";
        if(id_uci==1)
            if(id_uci_cv == "")msg+="Debe especificar si quiere o no ventilador<br>";
        if(id_ambulancia == "")msg+="Debe especificar si quiere Ambulancia<br>";
        if(id_ambulancia==1) {
            if(id_ambulancia_ref== "")msg+="Debe especificar si quiere de Ida o Vuelta<br>";
            if(id_tipo_ambulancia== "")msg+="Debe elegir el tipo de ambulancia<br>";
        }
        if(id_pasaje == "")msg+="Debe especificar si requiere pasaje<br>";
        if(id_acompanante == "")msg+="Debe especificar si quiere acompañante<br>";
        if(id_situacion == "")msg+="Debe elegir una situacion<br>";
        if(observacion_referencia == "")msg+="Debe escribir un relato o caracteristicas de la referencia<br>";
        
    }

    if (msg != "") {
        bootbox.alert(msg);
        return false;
    } else {   
        //$("#frmAltaMedica").submit();
        var r = confirm("¿Seguro de Dar el alta médica al Paciente?");
        if (r == true) {
        $('#frmAltaMedica').submit();
        } 
    }
    }

    function AddDiagnostico(){
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;
    
    if(ind > 0){
        $('.id_diagnostico').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
            ind=Number(ind_tmp)+1;
        }
        });
            
        fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];
        
    }
    
    var item_tipo_diagnostico = '';
    item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
    item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
    newRow +='<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow +='<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblDiagnostico tbody').append(newRow);

    var sexo  = $('#nomsexo').val();
    if(sexo=='M'){
        genero=1;
    }
    else{
        if(sexo=='F'){
        genero=2;
        }
    }
    
    $("#nombre_diagnostico"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
        $.ajax({
            //url: '../obtener_diagnostico/' + request.term,
            url: '../../obtener_diagnostico_genero/' + request.term + '/'+ genero,
            dataType: "json",
            success: function(data)  {
            let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
            }         
        })
    
        },select:  function(e, ui) {
        var id_diagnostico = ui.item.id;
        $("#id_diagnostico"+ind).val(id_diagnostico);
        $("#nombre_diagnostico"+ind).prop("readonly",true);
        }
    });
    
    $("#nombre_diagnostico"+ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico"+ind).val();
        if(id_diagnostico == ""){
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico"+ind).val("");
        return false;
        }
    });
    
    }


    function soloNumerosMenosCero(e) {

    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    function firmar() {

    //var rowindex = $('#Grd2 tbody tr td').length;
    //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
    //if (rowindex > 0) {

    $.ajax({
        url: "<?php echo url()?>/genera_csv",
        type: "post",
        //data: p,
        data: $("#frmBloque").serialize(),
        //timeout: 30000,
        dataType: 'json'
        })
        .done(function(resultado) {
        console.log(resultado);
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar").val(`${resultado.csv}.7z`);
            initInvoker('W');
        } else {
            //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
            //dialogError(function () {});
        }
        $('#btnLiquidacionFirmarPdf').attr("disabled", false);

        })
        .fail(function(jqXHR, textStatus) {
        //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
        //dialogError(function () {});
        console.log("Request failed: " + textStatus)
        })
        .always(function() {
        //bootbox.hideAll();
        });

    //} else {

    //dialogWarning("Debe hacer check en minimo una solicitud");
    //} 


    }

    function firmarsintokens() {
    var batchSignatureProcessPoller = setInterval(function() {
        $.ajax({
        url: "<?php echo url()?>/sinTokensEgresoEmergencia",
        type: "post",
        cache: false,
        dataType: 'json',
        data: $("#frmBloque").serialize(),
        success: function(completed) {
            if (completed) {
            location.href = '/all_atenciones_by_emergencia';
            clearInterval(batchSignatureProcessPoller);
            }
        }
        });
    }, 3000);
    }


    function cambiar_tipo_egreso(){
        var id_tipo_egreso=  $("#id_condicion_alta").val();

        if(id_tipo_egreso=='1'){ //MEJORADO
            $("#egreso_alta").show();
            $("#egreso_hospitalizacion").hide();
            $("#egreso_defuncion").hide();
            // $("#id_condicion_alta").val('');

            // cambiar_derivacion();

        } else if(id_tipo_egreso=='2'){ //CURADO
            $("#egreso_alta").show();
            $("#egreso_hospitalizacion").hide();
            $("#egreso_defuncion").hide();
            // $("#id_condicion_alta").val('');

            // cambiar_derivacion();

        } else if(id_tipo_egreso=='3'){ // TRANSFERENCIA  
            $("#egreso_alta").hide();
            $("#egreso_hospitalizacion").show();
            $("#egreso_defuncion").hide();
            // $("#id_condicion_alta").val('3');
        
        } else if(id_tipo_egreso=='4'){ //DEFUNCION  
            $("#egreso_alta").hide();
            $("#egreso_hospitalizacion").hide();
            $("#egreso_defuncion").show();

            // $("#id_condicion_alta").val('4');

        }
    }

    function cambiar_derivacion(){
        var derivar= $("#derivar").val();
        if(derivar=='SI'){ //SI
            $.getJSON("/listar_consultorios_ajax/1", function(json){  
                document.getElementById("id_servicio_consulta_externa").innerHTML = "";
                $('#id_servicio_consulta_externa').append($('<option>').text("Seleccione").attr('value', ""));   
                $('#id_servicio_consulta_externa').append($('<option>').text("OTRO").attr('value', "OTRO"));  

                $.each(json, function(i, obj){ 
                //alert(obj[0].nombre);      alert(obj.length);   
                var len= obj.length;
                for(j=0;j<len;j++){
                    $('#id_servicio_consulta_externa').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));  
                }                        
                                        
                });           
            }); 
        }else{
            document.getElementById("id_servicio_consulta_externa").innerHTML = "";
        }

    } 

    function cambiar_referencia() {
        var referencia = $("#id_referencia").val();
        if (referencia == 'SI') {
            $("#panel_referencia_medica").show();
        } else {
            $("#panel_referencia_medica").hide();
        }
    }



    function getSexoGenero(){
    return ($('#nomsexo').val() == 'M') ? 1 : 2;
    }

    function modalDiagnostico(){
    
    var id_prestacion = $('#id_prestacion').val();
    $.ajax({
        url: "../../modal_diagnostico_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divDiagnostico").html(result);
        }
    });

    }


    function modalProcedimiento(){
    
    var id_prestacion = $('#id_prestacion').val();
    $.ajax({
        url: "../../modal_procedimiento_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divProcedimiento").html(result);
        }
    });

    }

    function modalLaboratorio(){
    
    var id_prestacion = $("#id_prestacion").val();
    $.ajax({
        url: "../../modal_laboratorio_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divLaboratorio").html(result);
        }
    });

    }

    function modalRadiologia(){
    
    var id_prestacion = $("#id_prestacion").val();
    $.ajax({
        url: "../../modal_radiologia_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divRadiologia").html(result);
        }
    });

    }

    function modalInterconsulta(){
    
    var id_prestacion = $("#id_prestacion").val();
    $.ajax({
        url: "../../modal_interconsulta_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divInterconsulta").html(result);
        }
    });

    }

    function modalReferencia(){
    
    var id_prestacion = $("#id_prestacion").val();
    $.ajax({
        url: "../../modal_referencia_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divReferencia").html(result);
        }
    });

    }

    function modalRecetaVale(){
    
    var id_prestacion = $("#id_prestacion").val();
    $.ajax({
        url: "../../modal_productos_paciente/"+id_prestacion,
        type: "GET",
        success: function (result) {  
            $("#divReceta").html(result);
        }
    });

    }

    function modalAtencion(){
    
    var id_emergencia = $("#id_emergencia").val();
    $.ajax({
        url: "../../modal_atencion_paciente/"+id_emergencia,
        type: "GET",
        success: function (result) {  
            $("#divAtencion").html(result);
        }
    });
    }

    function modalEvolucion(){
    
    var id_emergencia = $("#id_emergencia").val();
    $.ajax({
        url: "../../modal_evoluciones_paciente/"+id_emergencia,
        type: "GET",
        success: function (result) {  
            $("#divEvolucion").html(result);
        }
    });
    }


</script>
<style>
  .modal-dialog {
    width: 1200px !important;
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
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }

  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }

  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .form-group {
    margin-bottom: 10px;
  }

  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }


  /********************/

  body {
    font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2;
  }

  .jumbotron {
    background: #6b7381;
    color: #bdc1c8;
  }

  .jumbotron h1 {
    color: #fff;
  }

  .example {
    margin: 4rem auto;
  }

  .example>.row {
    margin-top: 2rem;
    height: 5rem;
    vertical-align: middle;
    text-align: center;
    border: 1px solid rgba(189, 193, 200, 0.5);
  }

  .example>.row:first-of-type {
    border: none;
    height: auto;
    text-align: left;
  }

  .example h3 {
    font-weight: 400;
  }

  .example h3>small {
    font-weight: 200;
    font-size: 0.75em;
    color: #939aa5;
  }

  .example h6 {
    font-weight: 700;
    font-size: 0.65rem;
    letter-spacing: 3.32px;
    text-transform: uppercase;
    color: #bdc1c8;
    margin: 0;
    line-height: 5rem;
  }

  .example .btn-toggle {
    top: 50%;
    transform: translateY(-50%);
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
</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ALTAS MEDICAS - <b>HOSPITALIZACION</b></h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <!-- <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $prestacion->id_cita?>" name="id_cita" id="id_cita">
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacionx" id="id_prestacionx">
              <input type="hidden" value="<?php echo $prestacion->id_ext ;?>" name="id_emergencia" id="id_emergencia">
              <input type="hidden" value="<?php echo $prestacion->id_tipo_atencion ;?>" name="id_tipo_atencion" id="id_tipo_atencion"> -->
              <input name="mov[]" id="mov[]" value="<?php echo $emergencia->id?>" type="hidden" />
              <input type="hidden" value="1" name="proviene_formulario" id="proviene_formulario">
              <?php
              $flag_firmar = 0;
                if($flag_pendinte_firma == 1) {
                  $flag_firmar = 1;
                }
              ?>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $prestacion->nro_historia?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <p><?php echo $prestacion->establecimiento?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">SERVICIO</label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      <div class="col-md-2">
                        <label>CONSULTORIO</label>
                        <p><?php echo $prestacion->consultorio?></p>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                      </div>
                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                      </div>
                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                      </div>
                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1default" data-toggle="tab" style="font-size:12px">DIAGNOSTICOS</a></li>
                    <li><a href="#tab2default" data-toggle="tab" style="font-size:12px">ATENCION</a></li>
                    <li><a href="#tab3default" data-toggle="tab" style="font-size:12px">PROCEDIMIENTO MEDICOS</a></li>
                    <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">PRODUCTOS MEDICOS</a></li>
                    <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">EX. DE LABORATORIO</a></li>
                    <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">EX. DE IMAGENOLOGIA</a></li>
                    <li><a href="#tab7default" data-toggle="tab" style="font-size:12px">INTERCONSULTAS</a></li>
                    <li><a href="#tab8default" data-toggle="tab" style="font-size:12px">EVOLUCION</a></li>
                    <li><a href="#tab9default" data-toggle="tab" style="font-size:12px">REFERENCIA</a></li>
                  </ul>
                </div>
                <div class="panel-body">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>DIAGNOSTICOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divDiagnostico'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade in" id="tab2default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>ATENCION</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divAtencion'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab3default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>PROCEDIMIENTOS MEDICOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divProcedimiento'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab4default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>PRODUCTOS MEDICOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divReceta'></div>
                              </div>
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
                              <b>EXAMENES DE LABORATORIO</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divLaboratorio'></div>
                              </div>
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
                              <b>EXAMENES DE IMAGENOLOGIA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divRadiologia'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab7default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INTERCONSULTAS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divInterconsulta'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab8default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>ATENCION - EVOLUCION</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divEvolucion'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab9default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>REFERENCIA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divReferencia'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <form class="form-horizontal" method="post" id="frmAltaMedica"
              action="{{ route('registrar_alta_medica_hospitalizado')}}"> <!--registrar_receta_prestacion -->
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $prestacion->id_cita?>" name="id_cita" id="id_cita">
              <input type="hidden" value="<?php echo $prestacion->id_asegurado?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento?>" name="id_establecimiento"
                id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio?>" name="id_sub_consultorio"
                id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->id_medico?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel?>" name="id_nivel" id="id_nivel">
              <input type="hidden" value="<?php echo $id_prestacion?>" name="id_prestacionx" id="id_prestacionx">
              <input type="hidden" value="<?php echo $prestacion->id_ext ;?>" name="id_emergencia" id="id_emergencia">
              <input type="hidden" value="<?php echo $prestacion->id_tipo_atencion ;?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input name="id_hospitalizado" id="id_hospitalizado" value="<?php echo $emergencia->id?>" type="hidden" />
              <input type="hidden" value="<?php echo $prestacion->fecha_atencion ;?>" name="fecha_atencion" id="fecha_atencion">

            @if($emergencia->estado==1 )
              <!-- ALTA MEDICA -->        
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICO DE EGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table>
                      <tr>
                        <td>
                          <button type="button" id="addDiagnostico" class="btn btn-primary"
                            data-toggle="modal" data-target="#addClassModal">Agregar
                            Diagnostico</button>
                        </td>
                      </tr>
                    </table>
                    <br />
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                      style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="90%"></th>
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
              </fieldset>
              <fieldset id="fsAlta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ALTA DE PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div style="clear:both"></div>
                    <fieldset id="divAlta2" >
                        <div class="form-group">  
                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>FECHA EGRESO</label>
                                </div>
                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_egreso" id="fecha_egreso" style="text-align:left">
                                </div>
                                <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>HORA EGRESO</label>
                                </div>
                                <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                <input type="text" class="form-control" value="<?php echo date("h:i:s", strtotime($horaservidor)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left">
                                </div>
                                <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>CONDICION ALTA</label>
                                </div>
                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <select class="form-control input-sm" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" onchange="cambiar_tipo_egreso()">
                                    <option value="">Elegir</option>
                                    <option value="1">MEJORADO</option>
                                    <option value="2">CURADO</option>
                                    <option value="3">TRANSFERIDO</option>
                                    <option value="4">FALLECIDO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="egreso_alta" style="display:none" >         
                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>DERIVAR CONSULTA</label>
                            </div>
                            <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                <select class="form-control input-sm" id="derivar" name="derivar" style="width: 100%" onchange="cambiar_derivacion()">
                                    <option value="NO">NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>SERVICIO</label>
                            </div>
                            <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                                <select class="form-control input-sm" id="id_servicio_consulta_externa" name="id_servicio_consulta_externa" style="width: 100%">
                                    <option value="">Elegir</option> 
                                    <?php if($consulta_externa!=NULL && $emergencia->derivar=="SI"){ 
                                        foreach($consulta_externa as $row): 
                                    ?>
                                    <option value="<?php echo $row->id?>" <?php if($row->id==$emergencia->id_servicio_destino){ ?>selected <?php } ?>><?php echo $row->nombre?></option>
                                    <?php endforeach; }?>                      
                                </select>
                            </div>
                        </div>
                        <!-- TRANSFERENCIA -->
                        <!-- <div class="form-group" id="egreso_hospitalizacion" <?php if($emergencia->id_tipo_egreso!="3"){ ?> style="display:none" <?php } ?> >         
                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>REFERENCIA</label>
                            </div>
                            <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                                <select class="form-control input-sm" id="id_referencia" name="id_referencia" style="width: 100%" onchange="cambiar_referencia()">
                                    <option value="NO">NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- FIN TRANSFERENCIA -->

                        <div class="form-group" id="egreso_defuncion"  <?php if($emergencia->id_tipo_egreso!="3"){ ?> style="display:none" <?php } ?> >         
                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                            <label>NECROPSIA</label>
                            </div>
                            <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                            <select class="form-control input-sm" id="necropsia" name="necropsia" style="width: 100%">
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>             
                            </select>
                            </div>
                            <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                            <label>CAUSAS DE MUERTE</label>
                            </div>
                            <div class="col-sm-5" style="margin-left:0px;padding-left:0px">
                            <input type="text" class="form-control" value="{{ $emergencia->causas_muerte }}" name="causas_muerte" id="causas_muerte" style="text-align:left">
                            </div>
                        </div>
                    </fieldset>
                  </div>
                </div>

                <div class="panel panel-info panel-bordered panel-sm panel-body" id="egreso_hospitalizacion" style="display:none">
                    <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>REFERENCIA</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                        <button type="button" id="addReferencia" class="btn btn-lg btn-toggle"
                            data-toggle="button" aria-pressed="false" autocomplete="off">
                            <div class="handle"></div>
                        </button>
                        </div>
                    </div>
                    <fieldset id="fsReferencia" style="display:none">
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>REFERENCIA MÉDICA</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>ESTABLECIMIENTO</label>
                                            <select class="form-control" name="id_establecimiento_referencia" id="id_establecimiento_referencia"
                                            tabindex="8" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                            <?php foreach($establecimientos as $row):?>
                                            <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                            <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>ESPECIFICAR SI REQUIERE UCI</label>
                                            <select class="form-control" name="id_uci" onchange="getVentiladorUci()" id="id_uci"
                                            tabindex="8" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                        <div  id="div_uci_ventilador" style="display:none">
                                            <div class="col-md-4">
                                                <label><br/></label>
                                                <select class="form-control" name="id_uci_cv" id="id_uci_cv"
                                                tabindex="8" style="width: 100%">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="1">CON VENTILADOR MECANICO</option>
                                                    <option value="2">SIN VENTILADOR MECANICO</option>
                                                    <option value="3">NINGUNO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <br/>
                                            <label>REQUIERE TRASLADO EN AMBULANCIA</label>
                                            <select class="form-control" name="id_ambulancia" onchange="getAmbulancia()" id="id_ambulancia"
                                            tabindex="8" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                            <label><br/></label>
                                        </div>
                                        <div  id="div_ambulancia" style="display:none">
                                            <div class="col-md-4">
                                                <br/>
                                                <label><br/></label>
                                                <select class="form-control" name="id_ambulancia_ref" id="id_ambulancia_ref"
                                                tabindex="8" style="width: 100%">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="1">IDA</option>
                                                    <option value="2">VUELTA</option>				                                        
                                                </select>
                                                <label><br/></label>
                                            </div>
                                            <div class="col-md-4">
                                                <br/>
                                                <label>TIPO DE AMBULANCIA</label>
                                                <select class="form-control" name="id_tipo_ambulancia" id="id_tipo_ambulancia" tabindex="8" style="width: 100%">
                                                        <option value="">--Seleccionar--</option>
                                                        <option value="1">Tipo I</option>
                                                        <option value="2">Tipo II</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>REQUIERE PASAJE</label>
                                            <select class="form-control" name="id_pasaje" id="id_pasaje"
                                            tabindex="8" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>NECESITA ACOMPAÑANTE</label>
                                            <select class="form-control" name="id_acompanante" id="id_acompanante"
                                            tabindex="8" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>SITUACION CLINICA DEL PACIENTE</label>
                                            <select class="form-control" name="id_situacion" id="id_situacion"
                                            tabindex="8" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                <option value="1">ELECTIVO</option>
                                                <option value="2">URGENCIA</option>
                                                <option value="3">EMERGENCIA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <br/>
                                            <label>OBSERVACION</label>
                                            <textarea class="form-control" cols="5" id="observacion_referencia" name="observacion_referencia"
                                                placeholder="Relato m&eacute;dico o caracteristicas de la referencia"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
              </fieldset>
              <div id="divGuardar">
                <input class="btn btn-primary input-sm pull-right" value="GUARDAR" name="crea" type="button"
                        form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_hospitalizado_by_establecimiento"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              </div>
            @else
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ALTA DE PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div style="clear:both"></div>
                    <fieldset id="divAlta2" >
                      <div class="form-group">  
                        <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                          <label>FECHA EGRESO:</label><br/>
                          {{$emergencia->fecha_egreso}}
                        </div>
                        <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                          <label>HORA EGRESO</label><br/>
                          {{$emergencia->hora_egreso}}
                        </div>
                        <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                          <label>TIPO EGRESO</label><br/>
                          <?php 
                              switch($emergencia->id_tipo_egreso){
                                case '1' : echo "MEJORADO"; break;
                                case '2' : echo "CURADO"; break;
                                case '3' : echo "TRANSFERIDO"; break;
                                case '4' : echo "FALLECIDO"; break;
                              }
                          ?>
                        </div>
                      </div>

                      @if($emergencia->id_tipo_egreso=="1" || $emergencia->id_tipo_egreso=="2")
                      <div class="form-group">         
                        <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                          <label>DERIVAR CONSULTA</label><br/>
                          <?php 
                              switch($emergencia->derivar){
                                case 'SI' : echo "SI"; break;
                                case 'NO' : echo "NO"; break;
                              }
                          ?>
                        </div>
                        @if($emergencia->derivar=="SI")
                        <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                          <label>SERVICIO</label><br/>
                             <?php if($consulta_externa!=NULL && $emergencia->derivar=="SI"){ 
                              foreach($consulta_externa as $row):
                                if($row->id==$emergencia->id_servicio_deriva){
                                  echo $row->nombre;
                                } 
                              endforeach; }
                            ?>                    
                          
                        </div>
                        @endif
                      </div>
                      @else
                        @if($emergencia->id_tipo_egreso=="3")
                        <div class="form-group">
                            <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>REFERENCIA</label><br/>
                                <?php 
                                    switch($emergencia->referencia){
                                    case 'SI' : echo "SI"; break;
                                    case 'NO' : echo "NO"; break;
                                    }
                                ?>
                            </div>
                            
                        </div>
                        @else
                          @if($emergencia->id_tipo_egreso=="4")
                            <div class="form-group">
                              <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>NECROPSIA</label><br/>
                                <?php 
                                    switch($emergencia->necropsia){
                                      case 'SI' : echo "SI"; break;
                                      case 'NO' : echo "NO"; break;
                                    }
                                ?>
                              </div>
                              <div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                <label>CAUSAS DE MUERTE</label><br/>
                                {{ $emergencia->causas_muerte }}
                              </div>
                            </div>
                          @endif
                        @endif
                      @endif
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              <div id="divFirmar">
                @if($emergencia->ruta_egreso==null)
                  <?php if(Auth::user()->status_token==1): ?>
                    <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                      onclick="firmarsintokens()" style="margin-left:20px" />
                  <?php else: ?>
                    <?php if(Auth::user()->tipo_registro==3): ?>
                      <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button"
                        onclick="firmarsintokens()" style="margin-left:20px" />
                    <?php else: ?>
                      <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar"
                        style="margin-left:20px" />
                    <?php endif; ?>
                  <?php endif; ?>
                @endif
              <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_hospitalizado_by_establecimiento"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              </div>
            @endif  
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" value="<?php echo $flag_firmar?>" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>


@include('frontend.includes.footer')
@endsection