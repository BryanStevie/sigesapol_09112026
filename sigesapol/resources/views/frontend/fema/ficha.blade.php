@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<style>
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
</style>
<script>

    $(document).ready(function () {
        
        <?php 
        if ($id_proceso == 1):
            switch($id_tipo_examen):
                case 1: ?> 
                    $('#fsOdontologicoGeneral').show();
                    $('#alerta').show(); <?php
                    break;
                case 3: ?> 
                    $('#fsClinicoGeneral').show(); 
                    $('#alertas').show(); <?php
                    break;
                case 4: ?> 
                    $('#fsSaludMentalGeneral').show();
                    $('#alerta').show();  <?php
                    break;
                case 5: ?> 
                    $('#fsRespiratorioGeneral').show(); <?php
                    break;
                case 6: ?> 
                    $('#fsGinecologicoGeneral').show(); 
                    $('#alerta').show(); <?php
                    break;
                case 7: ?> 
                    $('#fsCardiovascularGeneral').show(); <?php
                    break;
                case 8: ?> 
                    $('#fsOftalmologicoGeneral').show();
                    $('#alerta').show();  <?php
                    break;
                case 2: ?> 
                    $('#fsLaboratorioGeneral').show(); <?php
                    break;
                case 9: ?> 
                    $('#fsOtorrinoGeneral').show();
                    $('#alerta').show();  <?php
                    break;
                    
            endswitch;
        elseif ($id_proceso == 2):
            switch($id_tipo_examen):
                case 1: ?> 
                    $('#fsOdontologicoGeneral').show();
                    $('#alerta').show(); <?php
                    break;
                case 3: ?> 
                    $('#fsClinicoGeneral').show(); 
                    $('#alertas').show(); <?php
                    break;
                case 4: ?> 
                    $('#fsSaludMentalGeneral').show();
                    $('#alerta').show();  <?php
                    break;
                case 5: ?> 
                    $('#fsRespiratorioGeneral').show(); <?php
                    break;
                case 6: ?> 
                    $('#fsGinecologicoGeneral').show(); 
                    $('#alerta').show(); <?php
                    break;
                case 7: ?> 
                    $('#fsCardiovascularGeneral').show(); <?php
                    break;
                case 8: ?> 
                    $('#fsOftalmologicoGeneral').show();
                    $('#alerta').show();  <?php
                    break;
                case 2: ?> 
                    $('#fsLaboratorioGeneral').show(); <?php
                    break;
                case 9: ?> 
                    $('#fsOtorrinoGeneral').show();
                    $('#alerta').show();  <?php
                    break;
            endswitch;
        endif;
        ?>

        //$('#addObservacion').on('click',function(){
        //   event.preventDefault(); 
       //     AddObservacion();
   //     });

        $('#addObservacion').click(function(event) {
  event.preventDefault(); //lo paramos para continuar con flujo
  AddObservacion(); // 
       // $('#addDiagnostico').on('click',function(){
         //   AddDiagnostico();
        //});
    });
        $('#addDiagnostico').click(function(event) {
  event.preventDefault(); //lo paramos para continuar con flujo
  AddDiagnostico(); // 
       // $('#addDiagnostico').on('click',function(){
         //   AddDiagnostico();
        //});
    });
        $('#tblObservacion tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
            obj.parent().parent().remove();
        });
        
        $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
            obj.parent().parent().remove();
        });

        $('#btnPrestacion').on("click", function (e) {
            guardarPrestacion();
        });
    });

    function AddObservacion(){
        var newRow = "";
        var ind = $('#tblObservacion tbody tr').length;
        
        if(ind > 0) {//id_observacion
            $('.id_observacion').each(function() {
                var ind_tmp = $(this).data("id");
                if(ind_tmp => ind){
                    ind=Number(ind_tmp)+1;
                }
            });
                        
            fila_medicamentos=$('#tblObservacion tbody').children('tr');
            rowIndex=$('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento=fila_medicamentos[idval];
            
        }
        
        newRow +='<tr>';
        newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la observacion" name="nombre_observacion[]" tabindex="44" required="" id="nombre_observacion'+ind+'" class="input-sm nombre_observacion" style="margin-left:4px; width:100%; text-align:left" />';
        newRow +='<input type="hidden" class="form-control id_observacion typeahead tt-query" id="id_observacion'+ind+'" name="id_observacion[]" value="" data-id='+ind+'></td>';
        newRow +='<td><button type="button" tabindex="45" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow +='</tr>';
        $('#tblObservacion tbody').append(newRow);
        
    }

    function AddDiagnostico(){
        var newRow = "";
        var ind = $('#tblDiagnostico tbody tr').length;
        
        if(ind > 0) {
            $('.id_diagnostico').each(function() {
                var ind_tmp = $(this).data("id");
                if(ind_tmp => ind){
                    ind = Number(ind_tmp)+1;
                }
            });
                        
            fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];
         
        }

        var item_tipo_diagnostico = '';
	item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
	item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
	item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="44" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
	newRow +='<td><select tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
	newRow +='<td><button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';

        $('#tblDiagnostico tbody').append(newRow);
        var sexo = $('#nomsexo').val();
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
                var id_tipo_examen = $('#id_tipo_examen').val();
                console.log(id_tipo_examen);
                $.ajax({
                    url: '../../../obtener_diagnostico_genero_fema/' + request.term + '/'+ genero + '/' + id_tipo_examen,
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

    function guardarPrestacion(){
    
        var msgLoader = "";
        msgLoader = "Procesando, espere un momento por favor";
        $('#btnPrestacion').prop("disabled",true);
        var heightBrowser = $(window).width()/2;
        $('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
        $('.loader').show();
        
        var msg = "";
        
        var id_proceso = $('#id_proceso').val();
        var id_tipo_examen = $('#id_tipo_examen').val();
        
        var codigo_odontograma = $('#codigo_odontograma').val();
        var descripcion_odontograma = $('#descripcion_odontograma').val();
        var capacidadmasticatoria = $('#capacidadmasticatoria').val();
        
        var cli_gen_antecedentes = $('#cli_gen_antecedentes').val();
        var cli_gen_examen_clinico = $('#cli_gen_examen_clinico').val();

        var sm_area_emocional = $('#sm_area_emocional').val();
        var sm_area_cognitiva = $('#sm_area_cognitiva').val();
        var sm_area_neurobiologica = $('#sm_area_neurobiologica').val();
        var sm_derivacion = $("input[type=radio][name=sm_derivacion]:checked").val();
        var sm_observaciones = $('#sm_observaciones').val();

        var r_examen_clinico = $('#r_examen_clinico').val();
        var r_examen_radiologico = $('#r_examen_radiologico').val();
        var r_examen_esputo = $('#r_examen_esputo').val();

        var g_g = $('#g_g').val();
        var g_p_1 = $('#g_p_1').val();
        var g_p_2 = $('#g_p_2').val();
        var g_p_3 = $('#g_p_3').val();
        var g_p_4 = $('#g_p_4').val();
        var g_menarquia = $('#g_menarquia').val();
        var g_mamografia = $('#g_mamografia').val();
        //var g_irs = $('#g_irs').val();
        var g_fur = $('#g_fur').val();
        var g_mac = $('#g_mac').val();
        //var g_urs = $('#g_urs').val();
        //var g_andria = $('#g_andria').val();
        var g_antecedentes = $('#g_antecedentes').val();
        var g_examen_pap = $("input[type=radio][name=g_examen_pap]:checked").val();
        var g_fecha = $('#g_fecha').val();
        var g_resultado_pap = $('#g_resultado_pap').val();
        var g_prueba_iva = $("input[type=radio][name=g_prueba_iva]:checked").val();
        var g_resultado_iva = $('#g_resultado_iva').val();

        var c_examen_clinico = $('#c_examen_clinico').val();
        var c_pa_sistolica = $('#c_pa_sistolica').val();
        var c_pa_diastolica = $('#c_pa_diastolica').val();
        var c_ecg = $('#c_ecg').val();

        var o_vision_cercana_od = $('#o_vision_cercana_od').val();
        var o_vision_cercana_od_corregida = $('#o_vision_cercana_od_corregida').val();
        var o_vision_cercana_oi = $('#o_vision_cercana_oi').val();
        var o_vision_cercana_oi_corregida = $('#o_vision_cercana_oi_corregida').val();
        var o_vision_cercana_od2 = $('#o_vision_cercana_od2').val();
        var o_vision_cercana_od_corregida2 = $('#o_vision_cercana_od_corregida2').val();
        var o_vision_cercana_oi2 = $('#o_vision_cercana_oi2').val();
        var o_vision_cercana_oi_corregida2 = $('#o_vision_cercana_oi_corregida2').val();
        var o_tonometria = $('#o_tonometria').val();
        var o_pupilas = $('#o_pupilas').val();
        var o_motil_ocular = $('#o_motil_ocular').val();
        var o_fondo_ojo = $('#o_fondo_ojo').val();
        var o_vision_colores = $('#o_vision_colores').val();

        var lab_vdrl = $('#lab_vdrl').val();
        //var lab_leucocitos = $('#lab_leucocitos').val();
        //var lab_hematies = $('#lab_hematies').val();
        //var lab_glucosa_paquete = $('#lab_glucosa_paquete').val();
        //var lab_albumina = $('#lab_albumina').val();
        //var lab_nitritos = $('#lab_nitritos').val();
        var lab_hiv = $('#lab_hiv').val();
        var lab_glucosa = $('#lab_glucosa').val();
        var lab_colesterol = $('#lab_colesterol').val();
        var lab_trigliceridos = $('#lab_trigliceridos').val();
        var lab_hepatitis_b = $('#lab_hepatitis_b').val();
        var lab_anti_hct = $('#lab_anti_hct').val();
        //var lab_hto = $('#lab_hto').val();
        var lab_psa = $('#lab_psa').val();
        var lab_otros = $('#lab_otros').val();

        //************************************************
        var id_observacion = $('input[name="id_observacion[]"]').map(function(){return $(this).val();}).get();
        var id_diagnostico = $('input[name="id_diagnostico[]"]').map(function(){return $(this).val();}).get();
       
        let apoyo = JSON.stringify(id_diagnostico);
        $('#id_diagno200').val(apoyo);
        
        var id_tipo_diagnostico = $('input[name="id_tipo_diagnostico[]"]').map(function(){return $(this).val();}).get();
        var alerta = $('#alerta').val();

        //msg+="id_proceso:"+id_proceso+"<br>";
        //msg+="id_tipo_examen:"+id_tipo_examen+"<br>";
        switch(id_tipo_examen) {
            case '1':
                //msg+="entro al case 1<br>";
                if (id_proceso == 1) {
                    if(codigo_odontograma=="")msg+="Debe ingresar el Codigo del Odontograma<br>";
                    if (capacidadmasticatoria == "") {
                        msg+="Falta registrar capacidad masticatoria<br>";
                    }else{
                        if (capacidadmasticatoria <0 || capacidadmasticatoria >150) {
                            msg+="La capacidad masticatoria debe ser mayor que 0 a 150<br>";
                        }
                    }
                    if(descripcion_odontograma=="")msg+="Debe ingresar la Descripción de Odontograma<br>";
                }
            break;
            case '3':
                //msg+="entro al case 19<br>";
                if (id_proceso == 1) {
                    if(cli_gen_antecedentes=="")msg+="Debe ingresar el Antecedente<br>";
                    if(cli_gen_examen_clinico=="")msg+="Debe ingresar el Examen Clinico<br>";
                }
            break;
            case '4':
                //msg+="entro al case 19<br>";
                if (id_proceso == 1) {
                    if(sm_area_emocional=="")msg+="Debe ingresar el Area Emocional<br>";
                    if(sm_area_cognitiva=="")msg+="Debe ingresar el Area Cognitiva<br>";
                    if(sm_area_neurobiologica=="")msg+="Debe ingresar el Area Neurobiologica<br>";
                    if(sm_derivacion==undefined)msg+="Debe seleccionar la Derivacion<br>";
                    if(sm_observaciones=="")msg+="Debe seleccionar la Observación<br>";
                }
            break;
            case '5':
                if (id_proceso == 1) {
                    if(r_examen_clinico=="")msg+="Debe ingresar el Examen Clinico<br>";
                    if(r_examen_radiologico=="")msg+="Debe ingresar el Examen Radiologico<br>";
                    if(r_examen_esputo=="")msg+="Debe ingresar el Examen de Esputo<br>";
                }
            break;
            case '6':
                if (id_proceso == 1) {
                    if(g_g=="")msg+="Debe ingresar el campo G<br>";
                    if(g_p_1=="" || g_p_2=="" || g_p_3=="" || g_p_4=="")msg+="Debe ingresar el campo P<br>";
                    if(g_menarquia=="")msg+="Debe ingresar el campo Menarquia<br>";

                    if(g_mamografia=="")msg+="Debe ingresar el campo Mamografia<br>";
                    //if(g_irs=="")msg+="Debe ingresar el campo IRS<br>";
                    if(g_fur=="")msg+="Debe ingresar el campo FUR<br>";
                    if(g_mac=="")msg+="Debe ingresar el campo MAC<br>";
                    //if(g_urs=="")msg+="Debe ingresar el campo URS<br>";
                    //if(g_andria=="")msg+="Debe ingresar el campo ANDRIA<br>";

                    if(g_antecedentes=="")msg+="Debe ingresar el campo ANTECEDENTES PERSONALES Y FAMILIARES<br>";
                    if(g_examen_pap==undefined) {
                        msg+="Debe seleccionar el EXAMEN COMPLEMENTARIO PAP<br>";
                    }else if(g_examen_pap!="") {
                        if(g_examen_pap=="2") { 
                            if(g_fecha=="")msg+="Debe ingresar el campo FECHA DEL EXAMEN PAP<br>";
                            if(g_resultado_pap=="")msg+="Debe seleccionar el campo RESULTADO DEL EXAMEN PAP<br>";
                        }
                    }

                    if(g_prueba_iva==undefined) {
                        msg+="Debe seleccionar la PRUEBA IVA<br>";
                    }else if(g_prueba_iva!="") {
                        if(g_prueba_iva=="1") {
                            if(g_resultado_iva=="")msg+="Debe seleccionar el campo RESULTADO DE LA PRUEBA IVA<br>";
                        }
                    }
                }
            break;
            case '7':
                if (id_proceso == 1) {
                    if(c_examen_clinico=="")msg+="Debe ingresar el Examen Clinico<br>";
                    if(c_pa_sistolica=="")msg+="Debe ingresar el PA Sistolica<br>";
                    if(c_pa_diastolica=="")msg+="Debe ingresar el PA Diastolica<br>";
                    if(c_ecg=="")msg+="Debe ingresar el ECG<br>";
                }
            break;
            case '8':
                if (id_proceso == 1) {
                    if(o_vision_cercana_od=="")msg+="Debe ingresar el campo Visión Cercana OD<br>";
                    if(o_vision_cercana_od_corregida=="")msg+="Debe ingresar el campo Visión Cercana OD Corregida<br>";
                    if(o_vision_cercana_oi=="")msg+="Debe ingresar el campo Visión Cercana OI<br>";
                    if(o_vision_cercana_oi_corregida=="")msg+="Debe ingresar el campo Visión Cercana OI Corregida<br>";

                    if(o_vision_cercana_od2=="")msg+="Debe ingresar el campo Visión Cercana OD 2<br>";
                    if(o_vision_cercana_od_corregida2=="")msg+="Debe ingresar el campo Visión Cercana OD Corregida 2<br>";
                    if(o_vision_cercana_oi2=="")msg+="Debe ingresar el campo Visión Cercana OI 2<br>";
                    if(o_vision_cercana_oi_corregida2=="")msg+="Debe ingresar el campo Visión Cercana OI Corregida 2<br>";

                    //if(o_tonometria=="")msg+="Debe ingresar el campo TONOMETRIA<br>";
                    if(o_pupilas=="")msg+="Debe ingresar el campo PUPILAS<br>";
                    if(o_motil_ocular=="")msg+="Debe ingresar el campo MOTIL OCULAR<br>";
                    //if(o_fondo_ojo=="")msg+="Debe ingresar el campo FONDO OJO<br>";
                    if(o_vision_colores=="")msg+="Debe ingresar el campo VISION DE COLORES (TEST HISHIHARA)<br>";
                }
            break;
            case '2':
                if (id_proceso == 1) {
                    if(lab_vdrl=="")msg+="Debe ingresar el campo VDRL<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo LEUCOCITOS<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo HEMATIES<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo GLUCOSA EN PAQUETE<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo ALBUMINA<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo NITRITOS<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo HIV<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo GLUCOSA<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo COLESTEROL<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo TRIGLICERIDOS<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo HEPATITIS B<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo ANTI. HCT<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo HTO<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo PSA<br>";
                    if(lab_vdrl=="")msg+="Debe ingresar el campo OTROS<br>";
                }
            break;
        }

        if($('#check_alerta').prop('checked')){
            if(alerta=="")msg+="Debe ingresar la Alerta<br>";
        }

        var ind_obs = $('#tblObservacion tbody tr').length;
        if(ind_obs > 0){
            fila_observacion=$('#tblObservacion tbody').children('tr');
            for (i = 0; i < ind_diag; i++) {
                fila_observacion = fila_observacion[i];
                var nombre_observacion = $(fila_observacion).find('.nombre_observacion').val();
                if(nombre_observacion == "")msg+="Debe ingresar el nombre de observacion<br>";
            }
        }else{
            if(id_tipo_examen>1){
                if(id_tipo_examen!=9){
                    msg+="Debe ingresar minimo una observacion<br>";
                }
            }
               
        }

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
            msg+="Debe ingresar el tipo de diagnostico<br>";
        }

        if(msg!=""){
            bootbox.alert(msg);
            $('#btnPrestacion').prop("disabled",false);
            $('.loader').hide();
            return false;
        }
        else{

          
            $("#frm").submit();
        }
    }

</script>
@endsection
@section('content')

<div class="container">
    <div class="row ">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">EXAMEN MEDICO</h3>
                </div>
                <div class="panel-body">
                    <form id="frm" action="{{route('guardarFichaFema')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_diagno200" id="id_diagno200" value="">
                    <input type="hidden" name="id_ficha" value="{{$ficha->id}}">
                    <input type="hidden" name="id_proceso" id="id_proceso" value="{{$id_proceso}}">
                    <input type="hidden" name="id_tipo_examen" id="id_tipo_examen" value="{{$id_tipo_examen}}">
                    <input type="hidden" name="id_profesional" value="{{$profesional->id}}">
                    <input type="hidden" id="nomsexo" value="{{$ficha->sexo}}">
                    <div class="panel panel-info ">
                        <div class="panel-body "> 
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->fecha_reg}} {{$ficha->hora_reg}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="FECHA Y HORA REGISTRO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->proceso}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="TIPO PROCESO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->tipo_proceso}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="SUB PROCESO">    
                                    </div>
                                </div>
                                <!--
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-sm form-control">LISTA DE ATENCION MEDICA</button>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N° {{$establecimientos->codigo}} - {{$ficha->abreviatura}} - {{$ficha->id}} - 2023</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACIÓN PERSONAL</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">TIPO DOCUMENTO</label>
                                            <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° DOCUMENTO</label>
                                            <input value="{{$ficha->nro_doc_ident}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">GRADO</label>
                                            <input value="{{$ficha->grado}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE PATERNO</label>
                                            <input value="{{$ficha->paterno}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE MATERNO</label>
                                            <input value="{{$ficha->materno}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">NOMBRES</label>
                                            <input value="{{$ficha->nombre}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HISTORIA CLÍNICA DE IPRESS</label>
                                            <input value="{{$ficha->historia_clinica_ipress}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HISTORIA CLÍNICA</label>
                                            <input value="{{$ficha->historia_clinica}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° CIP</label>
                                            <input value="{{$ficha->carnet}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">SEXO</label>
                                            <input value="{{$sexo}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">CELULAR</label>
                                            <input value="{{$ficha->celular}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">EDAD</label>
                                            <input value="{{$ficha->edad}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">UNIDAD</label>
                                            <input value="{{$ficha->unidades_pnp}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <center>
                                    <img src="data:image/png;base64," title="foto reniec" width="210px" alt=""/>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title"> <b>USUARIO DEL SISTEMA</b></h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <!--div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >TIPO DOCUMENTO</label>
                                        <input value=" DNI " type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >N° DOCUMENTO</label>
                                        <input value="{{$profesional->dni}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >SERVICIO</label>
                                        <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-3">

                                </div>
                            </div-->
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" >PERSONAL DE DIRSAPOL</label>
                                        <input  value="{{$profesional->paterno}} {{$profesional->materno}} {{$profesional->name}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- TRIAJE -->
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">SIGNOS VITALES</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <input value="{{$ficha->temperatura}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TALLA (CM)</label>
                                        <input value="{{$ficha->talla_cm}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PESO (KG)</label>
                                        <input value="{{$ficha->peso_kg}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >IMC</label>
                                        <input value="{{$imc}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >DESCRIPCIÓN IMC</label>
                                        <input value="{{$indice}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PRESIÓN ARTERIAL (MM/HG)</label>
                                        <input value="{{$ficha->presion_arterial_x}}/{{$ficha->presion_arterial_y}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <input value="{{$ficha->frecuencia_respiratoria}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <input value="{{$ficha->frecuencia_cardiaca}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >SaO2<br></label>
                                        <br>
                                        </br> <input value="{{$ficha->sa02_porcentaje}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <div class="row">
                                    <div class="col-md-6">CALIFICACION Y OBSERVACIONES (2 AÑOS)</div>
                                    <div class="col-md-6">VACUNAS COVID-19</div>
                                </div>
                            </h3>
                        </div>
                    </div>
                            -->
                    <div class="modalito">
                        <!-- ****************** -->
                        
                        <!-- ****************** -->
                        <fieldset id="fsOdontologicoGeneral" style="display:none">
                            @include('frontend.fema.odonto_general')
                        </fieldset>
                        <fieldset id="fsOdontologicoEspecial" style="display:none">
                            @include('frontend.fema.odonto_especifico')
                        </fieldset>
                        <fieldset id="fsLaboratorioGeneral" style="display:none">
                            @include('frontend.fema.laboratorio')
                        </fieldset>
                        <fieldset id="fsLaboratorioGeneralpre" style="display:none">
                            @include('frontend.fema.laboratoriopre')
                        </fieldset>
                        <fieldset id="alertas" style="display:none">
                            <div class="panel panel-info ">
                                <div class="panel-heading">
                                    <h3 class="panel-title">ALERTAS</h3>
                                </div>
                                <div class="panel-body "> <!-- .col-md-12 -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            @if($datos->codigo_cardiologia > 0)
                                            <input value="PRE CARDIOLOGIA" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @if($datos->codigo_laboratorio > 0)
                                            <input value="PRE LABORATORIO" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @if($datos->codigo_respiratorio > 0)
                                            <input value="PRE RESPIRATORIO" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @foreach($alert as $f)
                                            <input value="@if(isset($f)){{$f->nombre}}@endif" @if(isset($f)) readonly @endif type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                            @if($datos->codigo_cardiologia > 0)
                                            <input value="@if(isset($datos)){{$datos->alerta_cardiologia}}@endif" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @if($datos->codigo_laboratorio > 0)
                                            <input value="@if(isset($datos)){{$datos->alerta_laboratorio}}@endif" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @if($datos->codigo_respiratorio > 0)
                                            <input value="@if(isset($datos)){{$datos->alerta_respiratorio}}@endif" type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endif
                                            @foreach($alert as $f)
                                            <input value="@if(isset($f)){{$f->alerta}}@endif" @if(isset($f)) readonly @endif type="text" name="" id="" class="form-control" placeholder="" tabindex="" disabled>
                                            @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset id="fsClinicoGeneral" style="display:none">
                            @include('frontend.fema.clinico_general')
                        
                        </fieldset>
                        <fieldset id="fsClinicoEspecial" style="display:none">
                            @include('frontend.fema.clinico_general_especifico')
                        </fieldset>
                        <fieldset id="fsSaludMentalGeneral" style="display:none">
                            @include('frontend.fema.salud_mental')
                        </fieldset>
                        <fieldset id="fsSaludMentalEspecial" style="display:none">
                            @include('frontend.fema.salud_mental_especifico')
                        </fieldset>
                        <fieldset id="fsRespiratorioGeneral" style="display:none">
                            @include('frontend.fema.aparato_respiratorio')
                        </fieldset>
                        <fieldset id="fsRespiratorioEspecial" style="display:none">
                            @include('frontend.fema.aparato_respiratorio_especifico')
                        </fieldset>
                        <fieldset id="fsGinecologicoGeneral" style="display:none">
                            @include('frontend.fema.ginecologico')
                        </fieldset>
                        <fieldset id="fsCardiovascularGeneral" style="display:none">
                            @include('frontend.fema.cardiovascular')
                        </fieldset>
                        <fieldset id="fsCardiovascularEspecial" style="display:none">
                            @include('frontend.fema.cardiovascular_especifico')
                        </fieldset>
                        <fieldset id="fsOftalmologicoGeneral" style="display:none">
                            @include('frontend.fema.oftalmologico')
                        </fieldset>
                        <fieldset id="fsOtorrinoGeneral" style="display:none">
                            @include('frontend.fema.otorrinolaringologico')
                        </fieldset>
                        <fieldset id="fsDigestivoGeneral" style="display:none">
                            @include('frontend.fema.digestivo')
                        </fieldset>
                        
                    
                        

                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">OBSERVACIONES</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button id="addObservacion" class="btn btn-success btn-sm">AGREGAR OBSERVACIÓN</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="display" id="tblObservacion" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <thead>
                                                <tr>
                                                    <th width="90%"></th>
                                                    <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">DIAGNOSTICOS</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button id="addDiagnostico" class="btn btn-success btn-sm">AGREGAR DIAGNOSTICO</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <thead>
                                                <tr>
                                                    <th width="90%"></th>
                                                    <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <fieldset id="alerta" style="display:none">
                            <div class="panel panel-info ">
                                <div class="panel-heading">
                                    <h3 class="panel-title">ALERTA</h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input value="" type="checkbox" name="check_alerta" id="check_alerta" class="" readonly="" tabindex=""> ALERTA
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <textarea name="alerta" id="alerta" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" id="btnPrestacion" value="GUARDAR" class="btn btn-primary pull-right">
                        </div>
                    </div>

                    <!-- ***************************** -->
                    </form>
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
</div>
@include('frontend.includes.footer')
@endsection
