@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script>
    
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) || (keynum == 45))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    function getCargaCampos(valor){

        var concepto  = $('#concepto').val();               
        
        option = {
            url: '/cargarorigen/' + concepto,
            type: 'GET',
            dataType: 'json',
            data: {}
        };
        
        $.ajax(option).done(function (data) {   
            $('#id_lugar_origen').prop('disabled', false);            
            cargarComboDestino($cmbid_id_lugar_origen, data);
            $cmbid_id_lugar_origen.val(null).trigger("change");                 
        });



        if(concepto==1){
            $('#divPresentacion').show();            
            $('#cargasiaf').show();
            $('#divNumero').show();
            getSiaf();
            getNro();
            getTotalNro();
            getNroContrato();
            getRB();
            getProceso();            
            getNroProceso();            
            getCG();
            getProveedor(); 
            $id_proveedor = $("#frm_transferencia").find("#id_proveedor");
            $id_proveedor.select2();
            $('#id_proveedor').attr('required', true); 
            $('#f_r').attr('required', true); 
            $('#tipo_proceso').attr('required', true); 
            $('#nro_exp_siaf').attr('required', true); 
            $('#nro_entrega').attr('required', true); 
            $('#total_entrega').attr('required', true); 
            $('#c_g').attr('required', true); 
            $('#nota_entrada_nro_guia').attr('required', true); 
            cargareferencia.innerText = 'Certificacion'; 
            
        }else
        { 
                //ELIMINAR
                $('#divNumero').hide();  
                $('#cargasiaf').hide();   
                $('#divPresentacion').hide();                
                $('#proveedor_label').remove();
                $('#id_proveedor').select2('destroy');
                $('#id_proveedor').remove();
                $('#siaf_label').remove();
                $('#nro_contrato').remove();
                $('#contrato_label').remove();
                $('#nro_exp_siaf').remove();
                $('#nro_label').remove();    
                $('#nro_entrega').select2('destroy');
                $('#nro_entrega').remove();
                $('#total_label').remove();
                $('#total_entrega').select2('destroy');                
                $('#total_entrega').remove();
                $('#rb_label').remove();
                $('#f_r').select2('destroy');
                $('#f_r').remove();
                $('#cg_label').remove();
                $('#c_g').select2('destroy');
                $('#c_g').remove();
                $('#tipo_proceso_label').remove();
                $('#tipo_proceso').select2('destroy');
                $('#tipo_proceso').remove();
                $('#nro_proceso').remove();
                $('#nro_proceso_label').remove();                         
                cargareferencia.innerText = 'Referencia'; 
        }
    }

    function getnronotaautomatico() {
        id_farmacia=<?php echo Auth::user()->id_farmacia; ?>,
        $.ajax({
            url: '/cargarnronota/'+ id_farmacia,
            dataType: "text",
            success : function (data) {
                //$(".text").html(data);
                $('#nota_entrada_nro_guia').val(data); 
            }
        });    
    }

    function getSiaf(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Nro Exp. SIAF"); 
          
          label.setAttribute("name", "siaf_label");
          label.setAttribute("id", "siaf_label");
          label.appendChild(text);
        $('#cargasiaf').append(label);

        var text = document.createElement("input");
           text.setAttribute("onkeypress", "return soloNumeros(event)");
           text.setAttribute("id", "nro_exp_siaf");    
           text.setAttribute("name", "nro_exp_siaf");
           text.setAttribute("tabindex", "5");         
           text.setAttribute("maxlength", "10");      
           text.setAttribute("required", "required");
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");          
                          
        $('#cargasiaf').append(text);
        
    }

    function getNroContrato(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Nro Contrato"); 
          
          label.setAttribute("name", "contrato_label");
          label.setAttribute("id", "contrato_label");
          label.appendChild(text);
        $('#carganrocontrato').append(label);

        var text = document.createElement("input");           
           text.setAttribute("id", "nro_contrato");    
           text.setAttribute("name", "nro_contrato");
           text.setAttribute("tabindex", "6");         
           text.setAttribute("maxlength", "50");      
           text.setAttribute("required", "required");
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");          
                          
        $('#carganrocontrato').append(text);
        
    }

    function getNro(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Nro Entrega");           
        label.setAttribute("name", "nro_label");
        label.setAttribute("id", "nro_label");
        label.appendChild(text);
        $('#carganro').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "nro_entrega");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "nro_entrega");        
        select.setAttribute("required", "required");   
        select.setAttribute("tabindex", "8");           
        select.style.width = "100%";            
        $('#carganro').append(select);

        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";  
        newOption += "<option value='1' selected>1</option>";  
        newOption += "<option value='2' >2</option>";  
        newOption += "<option value='3' >3</option>";  
        newOption += "<option value='4' >4</option>";  
        newOption += "<option value='5' >5</option>";  
        newOption += "<option value='6' >6</option>";  
        newOption += "<option value='7' >7</option>";  
        newOption += "<option value='8' >8</option>";  
        newOption += "<option value='9' >9</option>";  
        newOption += "<option value='10' >10</option>";  
        newOption += "<option value='11' >11</option>";  
        newOption += "<option value='12' >12</option>";  
        $('#nro_entrega').html(newOption);

        $nro_entrega = $("#frm_transferencia").find("#nro_entrega");
        $nro_entrega.select2();        
    }
    
    function getTotalNro(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Total Entrega"); 
          
        label.setAttribute("name", "total_label");
        label.setAttribute("id", "total_label");
        label.appendChild(text);
        $('#cargatotal').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "total_entrega");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "total_entrega");        
        select.setAttribute("required", "required");   
        select.setAttribute("tabindex", "9");                   
        select.style.width = "100%";            
        $('#cargatotal').append(select);

        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";  
        newOption += "<option value='1' selected>1</option>";  
        newOption += "<option value='2' >2</option>";  
        newOption += "<option value='3' >3</option>";  
        newOption += "<option value='4' >4</option>";  
        newOption += "<option value='5' >5</option>";  
        newOption += "<option value='6' >6</option>";  
        newOption += "<option value='7' >7</option>";  
        newOption += "<option value='8' >8</option>";  
        newOption += "<option value='9' >9</option>";  
        newOption += "<option value='10' >10</option>";  
        newOption += "<option value='11' >11</option>";  
        newOption += "<option value='12' >12</option>";  
        $('#total_entrega').html(newOption);
        $total_entrega = $("#frm_transferencia").find("#total_entrega");
        $total_entrega.select2();
    }

    
    
    function getProveedor(){

        var label = document.createElement("label"); 
        var text = document.createTextNode("Proveedor"); 
        label.setAttribute("name", "proveedor_label");
        label.setAttribute("id", "proveedor_label");
        label.appendChild(text);        
        $('#cargaproveedor').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "id_proveedor");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "id_proveedor"); 
        select.setAttribute("required", "required"); 
        select.setAttribute("tabindex", "6");           
        select.style.width = "100%";            
        $('#cargaproveedor').append(select);


        $.ajax({
            url: '/cargaproveedor/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.ruc_proveedor+ ' - ' +oo.nombre_proveedor+'</option>';
                });
                $('#id_proveedor').html(newOption)
            }
        });
    }

    function getProceso(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("T/Proceso"); 
        label.setAttribute("name", "tipo_proceso_label");
        label.setAttribute("id", "tipo_proceso_label");

        label.appendChild(text);        
        $('#cargartipoproceso').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "tipo_proceso");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "tipo_proceso");
        select.setAttribute("required", "required");   
        select.setAttribute("tabindex", "10");           
        select.style.width = "100%";            
        $('#cargartipoproceso').append(select);

        $.ajax({
            url: '/cargartipoproceso/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.abreviatura_tipo_proceso+ ' - ' +oo.descripcion_tipo_proceso+'</option>';
                });
                $('#tipo_proceso').html(newOption)
            }
        });

        $tipo_proceso = $("#frm_transferencia").find("#tipo_proceso");
        $tipo_proceso.select2();
    }


    
    function getNroProceso(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Nro Proceso"); 
          
          label.setAttribute("name", "nro_proceso_label");
          label.setAttribute("id", "nro_proceso_label");
          label.appendChild(text);
        $('#carganro_proceso').append(label);

        var text = document.createElement("input");
           text.setAttribute("name", "nro_proceso");
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");
           text.setAttribute("tabindex", "11");        
           text.setAttribute("maxlength", "50");   
           text.setAttribute("id", "nro_proceso");
        $('#carganro_proceso').append(text);
    }
    

    function getRB(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("FF/RB"); 
        label.setAttribute("name", "rb_label");
        label.setAttribute("id", "rb_label");
        label.appendChild(text);        
        $('#cargarrb').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "f_r");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "f_r");        
        select.setAttribute("required", "required");   
        select.setAttribute("tabindex", "12");           
        select.style.width = "100%";            
        $('#cargarrb').append(select);

        
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";              
        newOption += "<option value='1' >00-RO</option>";  
        newOption += "<option value='2' selected>09-RDR</option>";  
        $('#f_r').html(newOption);

        $f_r = $("#frm_transferencia").find("#f_r");
        $f_r.select2();

    }

    
    function getCG(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("C. G."); 
        label.setAttribute("name", "cg_label");
          label.setAttribute("id", "cg_label");

        label.appendChild(text);        
        $('#cargacg').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "c_g");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "c_g");
        select.setAttribute("required", "required");   
        select.setAttribute("tabindex", "13");           
        select.style.width = "100%";            
        $('#cargacg').append(select);

        $.ajax({
            url: '/cargarcg/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.descripcion_cg+'</option>';
                });
                $('#c_g').html(newOption)
            }
        });

        $c_g = $("#frm_transferencia").find("#c_g");
        $c_g.select2();

    }
    
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 )
        }

    function soloNumeros2(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 || key == 45 )
        }
    
    $cmbconcepto = $("#frm_transferencia").find("#concepto");
    $cmbconcepto.select2();
    $cmbid_id_lugar_origen = $("#frm_transferencia").find("#id_lugar_origen");
    $cmbid_id_lugar_origen.select2();
    $cmbproveedor = $("#frm_transferencia").find("#id_proveedor");
    $cmbproveedor.select2();
    $cmbid_tipo_proceso = $("#frm_transferencia").find("#tipo_proceso");
    $cmbid_tipo_proceso.select2();
    $cmbc_g = $("#frm_transferencia").find("#c_g");
    $cmbc_g.select2();
    $cmbf_r = $("#frm_transferencia").find("#f_r");
    $cmbf_r.select2();
    $cmbnro_entrega = $("#frm_transferencia").find("#nro_entrega");
    $cmbnro_entrega.select2();
    $cmbtotal_entrega = $("#frm_transferencia").find("#total_entrega");
    $cmbtotal_entrega.select2();
        


        $("#nota_entrada_nro_guia").blur(function (event) {
            var concepto=$('#concepto').val();
            if(concepto==1)
                getCargaDatos();
        });

        function validar(nro, id_farmacia, tipo){
            
            option = {                
                url: '/evitar_duplicados/' + nro + '/' + tipo +  '/' + id_farmacia, 
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(tipo==1){
                    texto=' de la Nota de entrada ';
                }else{
                    texto=' de SIAF ';
                }
                
                if(existe>0){
                    bootbox.confirm({
                         message: "Aviso: El numero: " + nro + texto + " se encuentra actualmente registrada. ¿Desea Modificar?.",
                         className: 'title-dialog',
                         buttons: {
                             confirm: {
                                 label: 'Si',
                                 className: 'btn-success btn-lg'
                             },
                             cancel: {
                                 label: 'No',
                                 className: 'btn-danger btn-lg'
                             }
                         },
                         callback: function (result) {
                             if (result==false) {
                                 $('#frm_transferencia').find("#nota_entrada_nro_guia").val("");
                                 $('#nota_entrada_nro_guia').focus();
                             }
                         }
                     });
                }
            });     
        }

        function validateFrm(){
            $('#btnReg').prop('disabled', true);

            var concepto=$('#concepto').val();
            var id_lugar_origen=$('#id_lugar_origen').val();
            if(concepto==1){
                nro_entrega=parseInt($('#frm_transferencia').find("#nro_entrega").val());
                
                total_entrega=parseInt($('#frm_transferencia').find("#total_entrega").val());
                
                

                if ($('#frm_transferencia').find("#nota_entrada_nro_guia").val() == "") {
                    bootbox.alert("El numero del documento no puede estar vacío.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#nro_exp_siaf").val() == "") {
                    bootbox.alert("El numero del SIAF no debe estar vacio.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#referencia").val() == "") {
                    bootbox.alert("Debe ingresar la certificacion.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#id_proveedor").val() == "" || $('#frm_transferencia').find("#id_proveedor").val()==null) {
                    bootbox.alert("Debe seleccionar el proveedor.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
                
                if ($('#frm_transferencia').find("#nro_contrato").val() == "") {
                    bootbox.alert("Debe ingresar numero de contrato.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#nro_entrega").val() == "" || $('#frm_transferencia').find("#nro_entrega").val() ==null) {
                    bootbox.alert("Debe seleccionar un numero de entrega");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#total_entrega").val() == "" || $('#frm_transferencia').find("#total_entrega").val()==null) {
                    bootbox.alert("Debe seleccionar el total de entrega");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if(nro_entrega>total_entrega){
                    bootbox.alert("El numero de entrega debe ser menor al total de entrega.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#tipo_proceso").val() == "" || $('#frm_transferencia').find("#tipo_proceso").val()==null) {
                    bootbox.alert("Debe seleccionar tipo proceso");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#nro_proceso").val() == "") {
                    bootbox.alert("Debe seleccionar numero de proceso");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#f_r").val() == "" || $('#frm_transferencia').find("#f_r").val() ==null) {
                    bootbox.alert("Debe seleccionar un F R");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

                if ($('#frm_transferencia').find("#c_g").val() == "" || $('#frm_transferencia').find("#c_g").val()==null) {
                    bootbox.alert("Debe seleccionar un C G");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

            }
            else
            {
                if ($('#frm_transferencia').find("#referencia").val() == "") {
                    bootbox.alert("Debe ingresar la referencia.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

            }
            
            

            if ($('#frm_transferencia').find("#id_lugar_origen").val() == "") {
                    bootbox.alert("Elegir el lugar de origen.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

            if (id_lugar_origen == null) {
                    bootbox.alert("Elegir el lugar de origen.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }

            


            submitFrm();
            return false;
        }
        function submitFrm() {
            bootbox.confirm({
                message: "Aviso: Se guardarán los registros ingresados. ¿Desea continuar?.",
                className: 'title-dialog',
                buttons: {
                    confirm: {
                        label: 'Si',
                        className: 'btn-success btn-lg'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger btn-lg'
                    }
                },
                callback: function (result) {
                    if (result==true) {
                        $("#frm_transferencia").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
    }


        function getCargaDatos() {

            var nro_nota_entrada=$('#nota_entrada_nro_guia').val();
            var concepto=$('#concepto').val();
            var valor;
            var contar;
            id_farmacia=<?php echo Auth::user()->id_farmacia; ?>;

            $('.table tbody').html("");

            $.ajax({
                url: '/buscar_nota_entrada/' + nro_nota_entrada + '/' + id_farmacia + '/' + concepto,
                success: function (result) {

                    if(result.length>0){

                        if(result[0].nro_entrega!=result[0].total_entrega){
                            valor=parseInt(result[0].nro_exp_siaf);
                            $('#nro_exp_siaf').val(valor).attr("readonly", true);
                            $('#fecha_doc_recepcion').val(result[0].fecha_doc_recepcion).attr("readonly", true);                            
                            $('#id_lugar_origen').val(result[0].lugar_origen).trigger('change.select2');
                            $('#id_lugar_origen').prop('disabled', true);
                            $('#nro_contrato').val(result[0].nro_contrato).attr("readonly", true);                            
                            $('#referencia').val(result[0].referencia).attr("readonly", true);
                            $('#id_proveedor').val(result[0].proveedor_id).trigger('change.select2');
                            $('#id_proveedor').prop('disabled', true);
                            $('#total_entrega').prop('disabled', true);
                            $('#tipo_proceso').val(result[0].tipo_proceso).trigger('change.select2');
                            $('#tipo_proceso').prop('disabled', true);
                            $('#nro_proceso').val(result[0].nro_proceso).attr("readonly", true);
                            $('#f_r').val(result[0].ff_rb).trigger('change.select2');
                            $('#f_r').prop('disabled', true);
                            $('#c_g').val(result[0].c_g).trigger('change.select2');
                            $('#c_g').prop('disabled', true);
                            if(result[0].nro_entrega==result[0].total_entrega){
                                $('#btnReg').prop('disabled', true);
                                $('#nro_entrega').val(result[0].nro_entrega).trigger('change.select2');
                            }
                            else
                            {
                                contar=parseInt(result[0].nro_entrega);
                                $('#btnReg').prop('disabled', false);
                                $('#nro_entrega').val(contar+1).trigger('change.select2');

                            }
                            
                            $('#total_entrega').val(result[0].total_entrega).trigger('change.select2');
                        }
                        else
                        {

                             valor=parseInt(result[0].nro_exp_siaf);                            
                            $('#nro_exp_siaf').val(valor).attr("readonly", true);
                            $('#nro_proceso').val(valor).attr("readonly", true);
                            $('#referencia').val(result[0].referencia).attr("readonly", true);
                            $('#nro_contrato').val(result[0].nro_contrato).attr("readonly", true);
                            $('#id_proveedor').val(result[0].proveedor_id).trigger('change.select2');
                            $('#id_proveedor').prop('disabled', true);
                            $('#nro_entrega').prop('disabled', true);
                            $('#referencia').prop('disabled', true);
                            $('#total_entrega').prop('disabled', true);
                            $('#tipo_proceso').val(result[0].tipo_proceso).trigger('change.select2');
                            $('#tipo_proceso').prop('disabled', true);
                            $('#nro_proceso').val(result[0].nro_proceso);
                            $('#f_r').val(result[0].ff_rb).trigger('change.select2');
                            $('#f_r').prop('disabled', true);
                            $('#c_g').val(result[0].c_g).trigger('change.select2');
                            $('#c_g').prop('disabled', true);
                            if(result[0].nro_entrega==result[0].total_entrega){
                                $('#btnReg').prop('disabled', true);
                                $('#nro_entrega').val(result[0].nro_entrega).trigger('change.select2');
                            }
                            else
                            {
                                contar=parseInt(result[0].nro_entrega);
                                $('#btnReg').prop('disabled', false);
                                $('#nro_entrega').val(contar+1).trigger('change.select2');

                            }
                            
                            $('#total_entrega').val(result[0].total_entrega).trigger('change.select2');

                        }
                    }
                    else
                    {
                        $('#id_lugar_origen').prop('disabled', false);
                        $('#nro_exp_siaf').val("").attr("readonly", false);
                        $('#nro_exp_siaf').prop('disabled', false);
                        $('#nro_contrato').val("").attr("readonly", false);
                        $('#nro_contrato').prop('disabled', false);
                        $('#nro_proceso').val("").attr("readonly", false);
                        $('#nro_proceso').prop('disabled', false);
                        $('#id_proveedor').prop('disabled', false);
                        $('#total_entrega').prop('disabled', false);
                        $('#tipo_proceso').prop('disabled', false);
                        $('#nro_entrega').prop('disabled', false);    
                        $('#id_proveedor').val(null).trigger('change.select2');
                        $('#total_entrega').val(null).trigger('change.select2');
                        $('#tipo_proceso').val(null).trigger('change.select2');
                        $('#nro_entrega').val(null).trigger('change.select2');
                        $('#c_g').val(null).trigger('change.select2');
                        $('#f_r').prop('disabled', false);       
                        $('#c_g').prop('disabled', false);                        
                        $('#referencia').val("").attr("readonly", false);  
                        $('#referencia').prop('disabled', false);
                        $('#btnReg').prop('disabled', false);                    
                    }                
                }

            });

            
        }
        function cargarComboDestino($select, data) {
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
            });
        } 

$('#divPresentacion').hide();
//$('#cargasiaf').hide();   
$('#divNumero').hide();   

$(document).ready(function () {

    $('#fecha_entrada').change(function(){
        var concepto=$('#concepto').val();
        if(concepto==1){
            var fecha_entrada = $('#fecha_entrada').val();
            var fecha_doc_recepcion = $('#fecha_doc_recepcion').val();
            
            
            f1 = fecha_doc_recepcion.split("-");
            f2 = fecha_entrada.split("-");
            f3 = new Date();

            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014
            
    
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));

            $('#fecha_expedicion').focus();
        }

    });

              
});
</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<?php 
    
    if ($pecosa->estado==1){
            $disabled = "";
            $title = "EDITAR";
            $msg = " Modificar Nota de Entrada de Productos Farmacéuticos ";

            if($pecosa->concepto==1):
                $nota_entrada=ltrim($pecosa->nota_entrada,"0");
                $nro_exp_siaf=ltrim($pecosa->nro_exp_siaf,"0");
            else:                
                $disabled2 ="";                
            endif;
    }
    else
    {
        $disabled = "disabled='disabled'";
        $title = "VER";
        $msg = "Ver Nota de Enrada Culminada de Productos Farmacéuticos";

    }

    if($mostrar==0)
        $disabled2= "";
    else
        $disabled2= "disabled='disabled'";

    
    
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR NOTA DE ENTRADA</h3>                    
                    <a href="/all_notas_entradas/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Nota Entrada 
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('editar_documento_nota_entrada_almacen')}}">   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!------- Fila 01--------------------------------------->
                        <div class="row">                            
                                <input name="nro_nota_entrada" value="<?php echo $pecosa->nota_entrada?>" class="form-control" type="hidden">
                                <input name="mostrar" value="<?php echo $mostrar?>" class="form-control" type="hidden">
                                <input name="concepto" value="<?php echo $pecosa->concepto?>" class="form-control" type="hidden">
                                <input id="id_farmacia" name="id_farmacia" value="{{ $id_farmacia }}" class="form-control" type="hidden"> 
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="id" value="{{ $pecosa->id }}" id="id" class="form-control" type="hidden"> 
                                <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden"> 
                                <input type="hidden" name="ano_actual" value="<?php echo date("Y") ?>" id="ano_actual" class="form-control">
                            
                            <!--div class="col-md-2 etiqueta">
                                    <label>Nro</label>
                                    <input type="text" class="form-control" value="<?php echo $pecosa->numero; ?>" name="nota_entrada_nro_guia_1" id="nota_entrada_nro_guia_1"  readonly=""/>
                            </div-->
                            <div class="col-md-2 etiqueta">
                                    <label>FEC. REGISTRO (R)</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_registro" id="fecha_registro"  readonly=""/>
                            </div>
                            <input name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" class="form-control" type="hidden"> 
                            <div class="col-md-3 etiqueta">
                                <label>Concepto</label>
                                <select class="form-control" required="" tabindex="1" onchange="getCargaCampos('<?php echo $pecosa->nota_entrada; ?>');" name="concepto" id="concepto" disabled="disabled">
                                    <?php $idConcepto = $pecosa->concepto;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione Concepto -</option>
                                    @foreach($concepto as $con)
                                    <?php $idCo = $con->id;?>
                                        <option value="{{$con->id}}" <?php if($idConcepto == $idCo) {echo " selected";}?>>{{$con->descripcion_conceptos}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 etiqueta">
                                <label>Lugar Origen</label>                                
                                <select name="id_lugar_origen" autofocus  tabindex="2" id="id_lugar_origen" class="form-control"  <?php echo $disabled ?> <?php echo $disabled2 ?> required="">
                                    <?php $idOrigen = $pecosa->lugar_origen;?>
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    @foreach($dependencia as $dep)
                                    <?php $idO = $dep->id;?>
                                        <option value="{{$dep->id}}" <?php if($idOrigen == $idO) {echo " selected";}?>>{{$dep->nombre}}</option>
                                    @endforeach

                                </select>
                            </div>
                            @if($pecosa->concepto==1)
                            <div class="col-sm-2 col-md-2 etiqueta">
                                <label>Fecha Orden Compra</label>
                                <input type="text" tabindex="3" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($pecosa->fecha_doc_recepcion)); ?>" name="fecha_doc_recepcion" id="fecha_doc_recepcion" <?php echo $disabled ?> <?php echo $disabled2 ?>  data-date-end-date="0d" autocomplete="off"/>
                            </div>
                            @endif 
                        </div>
                        <!------- Fila 02--------------------------------------->
                        <div class="row">                            
                            <div class="col-md-2 etiqueta">
                                <label>Fecha Internamiento</label>
                                <input type="text" tabindex="3" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($pecosa->fecha_entrada)); ?>" name="fecha_entrada" id="fecha_entrada"  <?php echo $disabled ?> data-date-end-date="0d" autocomplete="off"/>
                            </div>
                            @if($pecosa->concepto==1)
                            <div class="col-md-2 etiqueta">
                                <label>Nro Documento</label>
                                <input  onKeyPress="return soloNumeros2(event)" id="nota_entrada_nro_guia" name="nota_entrada_nro_guia" required="" tabindex="4" class="form-control" type="text"  value="<?php echo $pecosa->nota_entrada; ?>"  <?php echo $disabled ?> maxlength="20" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-2 etiqueta" id="cargasiaf">                            
                                <label id="siaf_label">SIAF</label>
                                <input  onKeyPress="return soloNumeros(event)" id="nro_exp_siaf" name="nro_exp_siaf" required="" <?php echo $disabled ?> <?php echo $disabled2 ?> tabindex="5" value="<?php echo $pecosa->nro_exp_siaf; ?>" class="form-control" type="text" style="text-transform:uppercase;">                            
                            </div>
                            @endif
                            <div class="col-md-6 etiqueta">
                            @if($pecosa->concepto==1)       
                                <label id="referencia_label">Certificacion</label>
                            @else
                                <label id="referencia_label">Referencia</label>
                            @endif
                                <input  <?php echo $disabled ?> <?php echo $disabled2 ?> id="referencia" name="referencia" required="" tabindex="6" value="<?php echo $pecosa->referencia; ?>" class="form-control" type="text" style="text-transform:uppercase;">                            
                            </div>
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-md-5 etiqueta" id="cargaproveedor">
                            @if($pecosa->concepto==1)
                                <label id="proveedor_label">PROVEEDOR</label>
                                <select <?php echo $disabled ?> <?php echo $disabled2 ?> class="form-control" required="" tabindex="7" name="id_proveedor" id="id_proveedor">
                                <?php $idProveedor = $pecosa->proveedor_id;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione Proveedor -</option>
                                    @foreach($proveedores as $prov)
                                    <?php $idProv = $prov->id;?>
                                        <option value="{{$prov->id}}" <?php if($idProv == $idProveedor) {echo " selected";}?>>{{$prov->nombre_proveedor}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="carganrocontrato">
                            @if($pecosa->concepto==1)
                                <label id="contrato_label">Nro Contrato</label>
                                <input  id="nro_contrato" name="nro_contrato" required="" <?php echo $disabled ?> <?php echo $disabled2 ?> tabindex="7" value="{{$pecosa->nro_contrato}}" class="form-control" type="text">
                            @endif
                            </div>
                            
                            <div class="col-md-2 etiqueta" id="carganro">
                            @if($pecosa->concepto==1)
                                <label id="nro_label">Nro Entrega</label>
                                <select <?php echo $disabled ?>  class="form-control" required="" tabindex="7" name="nro_entrega" id="nro_entrega">
                                <?php $idNE = $pecosa->nro_entrega;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                        <option value='1' <?php if($idNE == '1') {echo " selected";}?>>1</option>
                                        <option value='2' <?php if($idNE == '2') {echo " selected";}?>>2</option>     
                                        <option value='3' <?php if($idNE == '3') {echo " selected";}?>>3</option>
                                        <option value='4' <?php if($idNE == '4') {echo " selected";}?>>4</option>     
                                        <option value='5' <?php if($idNE == '5') {echo " selected";}?>>5</option>
                                        <option value='6' <?php if($idNE == '6') {echo " selected";}?>>6</option>     
                                        <option value='7' <?php if($idNE == '7') {echo " selected";}?>>7</option>
                                        <option value='8' <?php if($idNE == '8') {echo " selected";}?>>8</option>     
                                        <option value='9' <?php if($idNE == '9') {echo " selected";}?>>9</option>
                                        <option value='10' <?php if($idNE == '10') {echo " selected";}?>>10</option>     
                                        <option value='11' <?php if($idNE == '11') {echo " selected";}?>>11</option>
                                        <option value='12' <?php if($idNE == '12') {echo " selected";}?>>12</option>     
                                </select>             
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="cargatotal">
                            @if($pecosa->concepto==1)
                                    <label id="total_label">Total Entrega</label>
                                    <select <?php echo $disabled ?> <?php echo $disabled2 ?> class="form-control" required="" tabindex="8" name="total_entrega" id="total_entrega">
                                    <?php $idCT = $pecosa->total_entrega;?>
                                        <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                            <option value='1' <?php if($idCT == '1') {echo " selected";}?>>1</option>
                                            <option value='2' <?php if($idCT == '2') {echo " selected";}?>>2</option>     
                                            <option value='3' <?php if($idCT == '3') {echo " selected";}?>>3</option>
                                            <option value='4' <?php if($idCT == '4') {echo " selected";}?>>4</option>     
                                            <option value='5' <?php if($idCT == '5') {echo " selected";}?>>5</option>
                                            <option value='6' <?php if($idCT == '6') {echo " selected";}?>>6</option>     
                                            <option value='7' <?php if($idCT == '7') {echo " selected";}?>>7</option>
                                            <option value='8' <?php if($idCT == '8') {echo " selected";}?>>8</option>     
                                            <option value='9' <?php if($idCT == '9') {echo " selected";}?>>9</option>
                                            <option value='10' <?php if($idCT == '10') {echo " selected";}?>>10</option>     
                                            <option value='11' <?php if($idCT == '11') {echo " selected";}?>>11</option>
                                            <option value='12' <?php if($idCT == '12') {echo " selected";}?>>12</option>     
                                    </select>             
                            @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 etiqueta" id="cargartipoproceso">
                            @if($pecosa->concepto==1)
                                <label id="tipo_proceso_label">T/Proceso</label>
                                <select <?php echo $disabled ?> <?php echo $disabled2 ?> class="form-control" required="" tabindex="9" name="tipo_proceso" id="tipo_proceso">
                                <?php $idTipo = $pecosa->tipo_proceso;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($tipo_procesos as $tp)
                                    <?php $idTp = $tp->id;?>
                                        <option value="{{$tp->id}}" <?php if($idTp == $idTipo) {echo " selected";}?>>{{$tp->abreviatura_tipo_proceso}}-{{$tp->descripcion_tipo_proceso}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>

                            <div class="col-md-4 etiqueta" id="carganro_proceso">
                            @if($pecosa->concepto==1)
                                <label id="nro_proceso_label">Nro Proceso</label>
                                <input  <?php echo $disabled ?> <?php echo $disabled2 ?> id="nro_proceso" name="nro_proceso" tabindex="10" value="<?php echo $pecosa->nro_proceso; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="cargarrb">
                            @if($pecosa->concepto==1)
                                <label id="rb_label">FF/RB</label>
                                <select <?php echo $disabled ?> <?php echo $disabled2 ?> class="form-control" required="" tabindex="11" name="f_r" id="f_r">
                                <?php $idFRS = $pecosa->ff_rb;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                        <option value='1' <?php if($idFRS == '1') {echo " selected";}?>>00-RO</option>
                                        <option value='2' <?php if($idFRS == '2') {echo " selected";}?>>09-RDR</option>     
                                </select>             
                            @endif                  
                            </div>
                            <div class="col-md-2 etiqueta" id="cargacg">
                            @if($pecosa->concepto==1)
                                <label id="cg_label">C.G.</label>
                                <select <?php echo $disabled ?> <?php echo $disabled2 ?> class="form-control" required="" tabindex="12" name="c_g" id="c_g">
                                <?php $idCGS = $pecosa->c_g;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($cgs as $cg)
                                    <?php $idcg = $cg->id;?>
                                        <option value="{{$cg->id}}" <?php if($idCGS == $idcg) {echo " selected";}?>>{{$cg->descripcion_cg}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>
                        </div>
                        <!-------------------------------------------------------------------------------------------->                     
                        <div class="clearfix" style="margin-bottom:15px"></div>
                        <a href="/print_nota_entrada_almacen/<?php echo $pecosa->id ?>" class="btn btn-danger pull-right" target="_blank">
                             Imprimir <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <!------------------------------------------------------------------------------->

                        <div class="col-md-3 pull-right">
                            <button tabindex="13" <?php echo $disabled ?>    class="btn btn-success form-control" value="" type="submit" id="btnReg" onclick="validateFrm()">
                                <i class="fa fa-save"></i> Registrar y continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection