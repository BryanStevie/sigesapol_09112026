@extends('frontend.layouts.master')
@section('renderjs')
<script>
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    $tur = $("#frmReceta").find("#turno");
    $tur.select2();
    $grado = $("#frmReceta").find("#grado");
    $grado.select2();
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    $tipo.select2();
   
    /*--------------------------------------------------*/
    $medicos = $('#frmReceta').find("#id_medico")
    $medicos.select2();
    $medicos_segundo = $('#frmReceta').find("#id_medico_segundo")
    $medicos_segundo.select2();
    $medicos_tercero = $('#frmReceta').find("#id_medico_tercero")
    $medicos_tercero.select2();
    $id_anasteciologo = $('#frmReceta').find("#id_anasteciologo")
    $id_anasteciologo.select2();
    $id_enfermera_principal= $('#frmReceta').find("#id_enfermera_principal")
    $id_enfermera_principal.select2();
    $id_enfermera_secundaria= $('#frmReceta').find("#id_enfermera_secundaria")
    $id_enfermera_secundaria.select2();
    /*--------------------------------------------------*/
    
    $tipo_receta = $('#frmReceta').find("#tipo_receta")
    $tipo_receta.select2();
    
    function sumarCantidadLotes(){
        trs=$('#tabla_lote tbody').children('tr');
        total=0;
        lote_id=[];
        lote_cantidad=[];
        $(trs).each(function(ii,oo){
            valor=$(oo).find('#cant').val();
            valor_lote_actual=$(oo).find('#lote_actual').val();                       
            valor_actual=$(oo).find('#cant_actual').val();            
            if(parseFloat(valor_actual)<parseFloat(valor)){
                alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
                return false;
            }
            id=$(oo).find('#id_lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_id.push(id);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        cantidad_prescrita=$(fila_medicamento).find('#nro_med_solictados').val();
        if(total>cantidad_prescrita)
        {
            alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
            return false;
        }        
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_id=lote_id.join("||");
        cadena_lote_cantidad=lote_cantidad.join("||");
        $(fila_medicamento).find('#lotes_id').val(cadena_lote_id);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_id);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModal').modal('hide');
        
    }
    
    
    
    function verlotes(id_farmacia, btn){
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        codigo=objTr.find("#cmbMedicamento").val();
        cantidad_prescrita=objTr.find("#nro_med_solictados").val();
         $.ajax({
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}, 
                success: function(result){
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {                       
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.id +'" id="id_lote">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.nombre_producto + '</td>\n\
                                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                                <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
                            </tr>'
                        );
                    });
                    if(cantidad_prescrita>=10){
                    $("#alerta").html("");
                    $("#alerta").append(
                            'Advertencia: La Cantidad Prescrita es '+cantidad_prescrita+' unidades.'
                    );
                    }else{
                        $("#alerta").html("");
                        $("#alerta").append(
                            'Cantidad Prescrita: '+cantidad_prescrita+' unidades.'
                        );                        
                    }
                }
            });
        $('#squarespaceModal').modal();
    }    
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            bFilter: false,
            bPaginate: false,
            bSort: false,
            "createdRow": function (row, data, index) {
                cmbMedicamento = $("#cmbMedicamento").clone();
                $(cmbMedicamento).attr('name', 'id_producto[]');
                $(cmbMedicamento).on("change", function (e) {
                    cmb = $(this);
                    id_producto = $(cmbMedicamento).val();
                    id_user={{Auth::user()->id}};
                    //alert(id_user);
                    option = {
                        url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        //alert(data);                        
                        $(cmb).closest("tr").find("#nro_stocks").val(data); 
                        $(cmb).closest("tr").find("#nro_med_solictados").val("");
                        //alert($(cmb).closest("tr").html());
                    });
                    //alert($id_producto);
                });
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
                //alert(.html());
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos"
            }
        });
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");
            if (r) {
                t.row($(this).closest('tr')).remove().draw();
            }

        });
        $('#addRow').on('click', function () {
            t.row.add([
                '<div id="dvMedicamento"></div>',                                
                '<input type="number" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" disabled=""/>               <input type="hidden" name="lotes_id[]" id="lotes_id" value=""/>                <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/>',                
                '<input onkeypress="return justNumbers(event);" type="text" name="nro_med_solictados[]" required id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/>',                                
                '<input type="hidden" name="exit" id="" style="width:10px" value="<?php $exit=1; echo $exit?>"/>',                                  
                '<button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
        $("#frmReceta").submit(function (e) {
            //rows = $("#frmReceta").children(".row");            
            rows = $("#example tbody").children('tr');
            //e.preventDefault();             
            //---- Validación de las cantidades prescritas y dispensadas-------------------------------------
                $.each(rows, function (ii, oo) {
                    medicina = $(oo).find("#cmbMedicamento").select2("data");
                    //alert(medicina.text);
                    nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
                    cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
                    stockActual = parseFloat($(oo).find("#nro_stocks").val());
                    //alert(stockActual);
                    if(cantidadPrescrita==0){
                         alert("La cantidad prescrita del Producto: " +medicina[0].text+ ", tiene que ser mayor cero");
                            e.preventDefault();
                            return false;
                    }
                    
                    if (stockActual == 0) {                    
                        if (nuevaCantidad != 0) {                           
                            alert("La medicina: " +medicina[0].text+ ", tiene que ser cantidad cero");
                            e.preventDefault();
                            return false;
                        }
                    } else {
                        if (nuevaCantidad > stockActual) {
                            alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que el stock");                        
                            e.preventDefault();
                            return false;
                        } else if (nuevaCantidad > cantidadPrescrita) {
                            alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que la prescrita");                        
                            e.preventDefault();
                            return false;
                        }
                    }
                });                
                //--------------------------------------------------------------
        });
        
        
        $id_diagnostico_segundo = $('#frmReceta').find("#id_diagnostico_segundo");
        $cies_segundo = $('#frmReceta').find("#cies_segundo");
        //----------------------------------------------------------------------
        $id_diagnostico_tercero = $('#frmReceta').find("#id_diagnostico_tercero");
        $cies_tercero = $('#frmReceta').find("#cies_tercero");                
        //----------------------------------------------------------------------
        $id_diagnostico = $('#frmReceta').find("#id_diagnostico");
        $cies = $('#frmReceta').find("#cies");
        //$cies.select2();
        //----------------------------------------------------------------------
        $id_procedimiento_primero = $('#frmReceta').find("#id_procedimiento_primero");
        $cod_pro_primero = $('#frmReceta').find("#cod_pro_primero");
        //----------------------------------------------------------------------
        $id_procedimiento_segundo = $('#frmReceta').find("#id_procedimiento_segundo");
        $cod_pro_segundo = $('#frmReceta').find("#cod_pro_segundo");
        //----------------------------------------------------------------------
        $id_procedimiento_tercero = $('#frmReceta').find("#id_procedimiento_tercero");
        $cod_pro_tercero = $('#frmReceta').find("#cod_pro_tercero");
        //----------------------------------------------------------------------
        $cies.change(function () {
            $this = $(this);           
            cie = $this.val();
            option = {
                url: 'buscar_diagnostico/' + cie,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboUbigeo($id_diagnostico, data);
            });
        });
        //----------------------------------------------------------------------
        $cies_segundo.change(function () {
            $this = $(this);           
            cie = $this.val();
            option = {
                url: 'buscar_diagnostico/' + cie,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboUbigeo2($id_diagnostico_segundo, data);
            });
        });
        //----------------------------------------------------------------------
        $cies_tercero.change(function () {
            $this = $(this);           
            cie = $this.val();
            option = {
                url: 'buscar_diagnostico/' + cie,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboUbigeo3($id_diagnostico_tercero, data);
            });
        });
        //----------------------------------------------------------------------
        $cod_pro_primero.change(function () {
            $this = $(this);           
            codigo = $this.val();
            option = {
                url: '/buscar_procedimiento/' + codigo,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboProcedimiento1($id_procedimiento_primero, data);
            });
        });        
        $cod_pro_segundo.change(function () {
            $this = $(this);           
            codigo = $this.val();
            option = {
                url: '/buscar_procedimiento/' + codigo,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboProcedimiento2($id_procedimiento_segundo, data);
            });
        });
        $cod_pro_tercero.change(function () {
            $this = $(this);           
            codigo = $this.val();
            option = {
                url: '/buscar_procedimiento/' + codigo,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboProcedimiento3($id_procedimiento_tercero, data);
            });
        });
    });
    //-------------------------------------------------------------------------
    function cargarComboUbigeo($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.nombre + '</option>')
            });
            $('#frmReceta').find("#cies_segundo").focus();
        }else{
            alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies").val("");
            $('#frmReceta').find("#cies").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    function cargarComboUbigeo2($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.nombre + '</option>')
            });
            $('#frmReceta').find("#cies_tercero").focus();
        }else{
            alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies_segundo").val("");
            $('#frmReceta').find("#cies_segundo").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    function cargarComboUbigeo3($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.nombre + '</option>')
            });
            $('#frmReceta').find("#cod_pro_primero").focus();
        }else{
            alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies_tercero").val("");
            $('#frmReceta').find("#cies_tercero").focus();
            $select.html('');
        }
    } 
    //-------------------------------------------------------------------------
    function cargarComboProcedimiento1($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.descripcion + '</option>')
            });
            $('#frmReceta').find("#cod_pro_segundo").focus();
        }else{
            alert('Codigo del Procedimiento que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cod_pro_primero").val("");
            $('#frmReceta').find("#cod_pro_primero").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    function cargarComboProcedimiento2($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.descripcion + '</option>')
            });
            $('#frmReceta').find("#cod_pro_tercero").focus();
        }else{
            alert('Codigo del Procedimiento que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cod_pro_segundo").val("");
            $('#frmReceta').find("#cod_pro_segundo").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    function cargarComboProcedimiento3($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.descripcion + '</option>')
            });
            $('#frmReceta').find("#id_medico").focus();
        }else{
            alert('Codigo del Procedimiento que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cod_pro_tercero").val("");
            $('#frmReceta').find("#cod_pro_tercero").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    
    
    $("#digitos_adicionales").on("keyup", function () {
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        //alert(id_farmacia);
        validar(nro_receta, id_farmacia);
    });    
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen({max_selected_options: 4});   
    //--------------------------------------------------------------------------
    
    function validar(nro_receta, id_farmacia){
            option = {                
                url: '/evitar_duplicados_rectas/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe>0){
                    $('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    $('#frmReceta').find("#nro_receta").focus();
                    alert("La recta: " + nro_receta+ " se encuentra actualmente registrada");                        
                    e.preventDefault();
                }
            });     
        } 
    //--------------------------------------------------------------------------
    $("#btnReg").on("click", function () {
       
        if ($('#frmReceta').find("#id_consultorio").val() == "") {
            alert("Falta seleccionar el Consultorio");
            return false;
        }
        if ($('#frmReceta').find("#grado").val() == "") {
            alert("Falta seleccionar el grado");
            return false;
        }
        
        if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
            alert("Falta seleccionar la fecha de expedición de la receta");
            return false;
        }
        
        
        //----------------------------------------------------------------------
        
        //------------------------------------------------------------------------
        var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1; //hoy es 0!
        var yyyy = hoy.getFullYear();        
        if(dd<10) {
            dd='0'+dd
        } 
        if(mm<10) {
            mm='0'+mm
        } 
        hoy = dd+'-'+mm+'-'+yyyy;        
        fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();                        
        /*alert(fecha_dispensar); false;*/
        var xMonth=fecha_dispensar.substring(3, 5);                 
        var xDay=fecha_dispensar.substring(0, 2);  
        var xYear=fecha_dispensar.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                            return false;  
                        }
                    }
                }  
            }
        }
        
        //----------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frmReceta').find("#fecha_pasada").val();                        
        fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();   
        //alert(fecha_hace_treinta_dias);
        var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
        var xDay=fecha_hace_treinta_dias.substring(0, 2);  
        var xYear=fecha_hace_treinta_dias.substring(6,10);         
        var yMonth=fecha_dispensar.substring(3, 5);          
        var yDay=fecha_dispensar.substring(0, 2);  
        var yYear=fecha_dispensar.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                //alert("comparacion año");
                return false; 
            }  
        else{  
            if (xYear == yYear){                   
                if (xMonth> yMonth){  
                    alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                    //alert("comparacion mes");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha de expedición solo puede estar comprendida entre hoy y 30 días atrás");
                            //alert("comparacion dia");
                            return false;  
                        }
                    }
                }  
            }
        }
        //----------------------------------------------------------------------
        if ($('#frmReceta').find("#fecha_intervencion").val() == "") {
            alert("Falta seleccionar la fecha de intervención de la receta");
            return false;
        }        
        
        if ($('#frmReceta').find("#cies").val() == "") {
            alert("Falta seleccionar el diagnostico Principal (CIE10)");
            return false;
        }
        
        if ($('#frmReceta').find("#cod_pro_primero").val() == "") {
            alert("Falta seleccionar el Procedimiento Principal");
            return false;
        } 
        
        if ($('#frmReceta').find("#id_medico").val() == "") {
            alert("Falta seleccionar el Médico Principal");
            return false;
        }
        
        if ($('#frmReceta').find("#id_anasteciologo").val() == "") {
            alert("Falta seleccionar al Anesteciologo");
            return false;
        }
        
        if ($('#frmReceta').find("#id_enfermera_principal").val() == "") {
            alert("Falta seleccionar a la Enfermera Principal");
            return false;
        } 
        
        if ($('#frmReceta').find("#nro_historia").val() == "") {
            alert("Falta digitar el Nro. de Historia");
            return false;
        }
        
        farmacos=$('#frmReceta').find("#nro_stocks").val();
        if(farmacos==undefined){                    
            alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
            return false;        
        }
        
        if ($('#frmReceta').find("#nro_med_solictados").val() >= 10) {
            if (confirm("Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ¿Desea Grabar?.") == true) {
                //$("#frmReceta").submit();
                return true;
            } else {
                return false;
            }
        }else{
            if (confirm("Revise que los datos de la receta se encunetren correctos antes de registrar. ¿Desea Grabar?.") == true) {
                //$("#frmReceta").submit();
                return true;
            } else {
                return false;
            }
        }
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

    #squarespaceModal{
    width:850px !important;    
    }

    
    
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">        
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE RECETAS</h3>      
                        <div class="pull-right">
                            <a class="btn btn-default" href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia}}">Nueva Receta</a>
                            <a class="btn btn-default" href="/buscar_receta/{{ Auth::user()->id }}">Buscar Receta</a>
                            <a class="btn btn-default" href="/recetas_atendidas/{{ Auth::user()->id }}">Recetas Atendidas</a>  
                            <a class="btn btn-default" href="/stocksbyFamarcia/{{ Auth::user()->id }}">Ir Stock</a>  
                        </div>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua_cirugia_alterno')}}" id="frmReceta">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img src="../../images/logo.png" width="180" height="auto">
                                </div>
                                <div class="col-md-7" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">USO DE INSUMOS ALTERNO</label><br>
                                </div>
                                @if(Auth::user()->id_farmacia==28)
                                <div class="col-md-3">
                                    <label>N° RECETA: </label>                                    
                                    @if($contador->numero<10)               
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HLNS-000000".$contador->numero."-17"}}" readonly="">
                                    @endif  
                                    @if($contador->numero>10 && $contador->numero<100)               
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HLNS-00000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>100 && $contador->numero<1000)               
                                        <input type="text" class="" name="nro_receta"  id="nro_receta" value="{{"HLNS-0000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>1000 && $contador->numero<10000)              
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HLNS-000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>10000 && $contador->numero<100000)              
                                        <input type="text" class="" name="nro_receta"  id="nro_receta" value="{{"HLNS-00".$contador->numero."-17"}}" readonly="">
                                    @endif
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="dni" name="tipo">
                                    <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                </div>
                                @endif
                                @if(Auth::user()->id_farmacia==31)
                                <div class="col-md-3">
                                    <label>N° RECETA: </label>                                    
                                    @if($contador->numero<10)               
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HABL-000000".$contador->numero."-17"}}" readonly="">
                                    @endif  
                                    @if($contador->numero>10 && $contador->numero<100)               
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HABL-00000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>100 && $contador->numero<1000)               
                                        <input type="text" class="" name="nro_receta"  id="nro_receta" value="{{"HABL-0000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>1000 && $contador->numero<10000)              
                                        <input type="text" class="" name="nro_receta" id="nro_receta" value="{{"HABL-000".$contador->numero."-17"}}" readonly="">
                                    @endif 
                                    @if($contador->numero>10000 && $contador->numero<100000)              
                                        <input type="text" class="" name="nro_receta"  id="nro_receta" value="{{"HABL-00".$contador->numero."-17"}}" readonly="">
                                    @endif
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="dni" name="tipo">
                                    <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-3" style="margin-top: 10px">
                                    <label>TIPO RECETA</label>
                                    <select class="form-control" id="tipo_receta" name="tipo_receta" required="" tabindex="3">                                        
                                        <option value="CIRUGIA" selected>CIRUGIA</option>
                                    </select>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px">
                                    <label>CONSULTORIO</label>
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="4" autofocus="">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($consultorio as $c)
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno" required="" tabindex="5">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="M">MAÑANA</option>
                                        <option value="T">TARDE</option>
                                        <option value="N">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                @if($beneficiario[0]->parentesco!="TITULAR")
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>DNI (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular" tabindex="6" required="" maxlength="8">
                                </div>
                                <div class="col-md-7" style="margin-top: 10px">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" tabindex="7">
                                </div>
                                 <div class="col-md-3" style="margin-top: 10px">
                                    <label>GRADO</label>                                   
                                    <select class="form-control" id="grado" name="grado" tabindex="8">
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
                                        <option value="TENIENTE GENERAL">TENIENTE GENERAL</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="CORONEL">CORONEL</option>
                                        <option value="COMANDANTE">COMANDANTE</option>
                                        <option value="MAYOR">MAYOR</option>
                                        <option value="CAPITAN">CAPITAN</option>
                                        <option value="TENIENTE">TENIENTE</option>
                                        <option value="ALFEREZ">ALFEREZ</option>
                                        <option value="SUB OFICIAL SUPERIOR">SUB OFICIAL SUPERIOR</option>
                                        <option value="SUB OFICIAL BRIGADIER">SUB OFICIAL BRIGADIER</option>
                                        <option value="SUB OFICIAL TECNICO DE 1ERA">SUB OFICIAL TÉCNICO DE 1ERA</option>
                                        <option value="SUB OFICIAL TECNICO DE 2DA">SUB OFICIAL TÉCNICO DE 2DA</option>                                        
                                        <option value="SUB OFICIAL TECNICO DE 3ERA">SUB OFICIAL TÉCNICO DE 3ERA</option>
                                        <option value="SUB OFICIAL DE 1ERA">SUB OFICIAL DE 1ERA</option>
                                        <option value="SUB OFICIAL DE 2DA">SUB OFICIAL DE 2DA</option>
                                        <option value="SUB OFICIAL DE 3ERA">SUB OFICIAL DE 3ERA</option>
                                    </select>                                  
                                </div> 
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-2" 
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input value="{{$beneficiario[0]->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control" tabindex="9">
                                </div>
                                <div class="col-md-2">
                                    <label>DNI</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodocafiliado}}" name="dni_beneficiario" tabindex="10" required="" maxlength="8">
                                </div>
                                @if($beneficiario[0]->parentesco=="TITULAR")
                                <div class="col-md-5">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="11">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div> 
                                <div class="col-md-3">
                                    <label>GRADO</label>
                                    <select class="form-control" id="grado" name="grado" tabindex="12">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
                                        <option value="TENIENTE GENERAL">TENIENTE GENERAL</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="CORONEL">CORONEL</option>
                                        <option value="COMANDANTE">COMANDANTE</option>
                                        <option value="MAYOR">MAYOR</option>
                                        <option value="CAPITAN">CAPITAN</option>
                                        <option value="TENIENTE">TENIENTE</option>
                                        <option value="ALFEREZ">ALFEREZ</option>
                                        <option value="SUB OFICIAL SUPERIOR">SUB OFICIAL SUPERIOR</option>
                                        <option value="SUB OFICIAL BRIGADIER">SUB OFICIAL BRIGADIER</option>
                                        <option value="SUB OFICIAL TECNICO DE 1ERA">SUB OFICIAL TÉCNICO DE 1ERA</option>
                                        <option value="SUB OFICIAL TECNICO DE 2DA">SUB OFICIAL TÉCNICO DE 2DA</option>                                        
                                        <option value="SUB OFICIAL TECNICO DE 3ERA">SUB OFICIAL TÉCNICO DE 3ERA</option>
                                        <option value="SUB OFICIAL DE 1ERA">SUB OFICIAL DE 1ERA</option>
                                        <option value="SUB OFICIAL DE 2DA">SUB OFICIAL DE 2DA</option>
                                        <option value="SUB OFICIAL DE 3ERA">SUB OFICIAL DE 3ERA</option>
                                    </select>                                     
                                </div> 
                                @else
                                <div class="col-md-8">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="13">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div>
                                @endif  
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>FEC. EXPEDICIÓN (Receta)</label>
                                    <input type="text" class="form-control datepicker" value="" name="fecha_expedicion" id="fecha_expedicion" tabindex="14">
                                </div> 
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>FEC. INTERVENCION</label>
                                    <input type="text" class="form-control datepicker" value="" name="fecha_intervencion" id="fecha_intervencion" tabindex="15">
                                </div>
                                <div class="col-md-4" style="margin-top: 10px">
                                  
                                </div>
                                <div class="col-md-12" style="margin-top: 10px"><h4>Diagnosticos</h4></div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Cie10</label>
                                    <input class="form-control" name="cies" id="cies"  tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>DIAGNOSTICO PRINCIPAL</label>                                            
                                    <select id="id_diagnostico" name="id_diagnostico" class="form-control"  required="" readonly=""  tabindex="17">                                            
                                    </select>
                                </div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Cie10</label>
                                    <input class="form-control" name="cies_segundo" id="cies_segundo"  tabindex="18" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>DIAGNOSTICO SECUNDARIO</label>                                            
                                    <select id="id_diagnostico_segundo" name="id_diagnostico_segundo" class="form-control"  readonly="" tabindex="19">                                            
                                    </select>
                                </div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Cie10</label>
                                    <input class="form-control" name="cies_tercero" id="cies_tercero"  tabindex="20" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>DIAGNOSTICO SECUNDARIO</label>                                            
                                    <select id="id_diagnostico_tercero" name="id_diagnostico_tercero" class="form-control"  readonly="" tabindex="21">                                            
                                    </select>
                                </div>
                                <!--------------------------------------------------------------------->
                                <div class="col-md-12" style="margin-top: 10px"><h4>Procedimientos</h4></div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Codigo</label>
                                    <input class="form-control" name="cod_pro_primero" id="cod_pro_primero"  tabindex="22" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>PROCEDIMIENTO</label>                                            
                                    <select id="id_procedimiento_primero" name="id_procedimiento_primero" class="form-control"  readonly="" tabindex="23" required="">                                            
                                    </select>
                                </div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Codigo</label>
                                    <input class="form-control" name="cod_pro_segundo" id="cod_pro_segundo"  tabindex="24" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>PROCEDIMIENTO</label>                                            
                                    <select id="id_procedimiento_segundo" name="id_procedimiento_segundo" class="form-control"  readonly="" tabindex="25">                                            
                                    </select>
                                </div>
                                <div class="col-md-2" style="margin-top: 10px">
                                    <label>Codigo</label>
                                    <input class="form-control" name="cod_pro_tercero" id="cod_pro_tercero"  tabindex="26" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                   
                                </div>
                                <div class="col-md-10" style="margin-top: 10px">
                                    <label>PROCEDIMIENTO</label>                                            
                                    <select id="id_procedimiento_tercero" name="id_procedimiento_tercero" class="form-control"  readonly="" tabindex="27">                                            
                                    </select>
                                </div>
                                <!-------------Profesionales de la Salud------------------------------->
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>MEDICO PRINCIPAL</label>
                                    <select class="form-control" name="id_medico"  id="id_medico" tabindex="28">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">{{$m->paterno}} {{$m->materno}}, {{$m->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>   
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>MEDICO SECUNDARIO</label>
                                    <select class="form-control" name="id_medico_segundo" id="id_medico_segundo" tabindex="29">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">{{$m->paterno}} {{$m->materno}}, {{$m->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>MEDICO SECUNDARIO</label>
                                    <select class="form-control" name="id_medico_tercero" id="id_medico_tercero" tabindex="30">
                                        <option value="0">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">{{$m->paterno}} {{$m->materno}}, {{$m->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>ANESTECIOLOGO</label>
                                    <select class="form-control" name="id_anasteciologo"  id="id_anasteciologo" tabindex="31">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($anasteciologos as $a)
                                        <option value="{{$a->id}}">{{$a->paterno}} {{$a->materno}}, {{$a->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>ENFERMERA PRINCIPAL</label>
                                    <select class="form-control" name="id_enfermera_principal"  id="id_enfermera_principal" tabindex="32">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($enfermeras as $en)
                                        <option value="{{$en->id}}">{{$en->paterno}} {{$en->materno}}, {{$en->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px">
                                    <label>ENFERMERA</label>
                                    <select class="form-control" name="id_enfermera_secundaria"  id="id_enfermera_secundaria" tabindex="33">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($enfermeras as $en)
                                        <option value="{{$en->id}}">{{$en->paterno}} {{$en->materno}}, {{$en->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                                @if($numero_historia!="")
                                <div class="col-md-2" style="text-align: center; margin-top: 10px">
                                    <label>HISTORIA CLINICA</label>
                                    <input readonly="" type="text" class="form-control" value="{{$numero_historia}}" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
                                @else
                                <div class="col-md-2" style="text-align: center; margin-top: 10px">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
                                @endif
                                <div class="col-md-2" style="text-align: center; margin-top: 10px">
                                    <label>N° RUA</label>
                                    <input type="text" value="" name="nro_rua" class="form-control" tabindex="35" >
                                </div>
                            </div>  
                            <?php $exit=0; ?>
                            <input type="hidden" class="form-control" value="<?php echo $exit ?>" name="exit">
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-size: 23px">
                                    PRESCRIPCION 
                                </div>                                
                            <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px; margin-left: 3px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                </button>
                                <div style="display: none">
                                    <select class="form-control" id="cmbMedicamento" tabindex="36" name="tmp_id_producto[]" style="width: 500px">
                                        <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                        @foreach($producto as $p)                                                                                                
                                        <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                        @endforeach
                                    </select> 
                                </div>
                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Stock Actual</th>
                                            <th>Cantidad Prescrita</th>                                            
                                            <th></th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="37">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR RECETA Y CONTINUAR" tabindex="38">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:10%">Código</th>   
                                <th style="width:40%">Nombre</th> 
                                <th style="width:10%">Lote</th> 
                                <th style="width:10%">Reg. San.</th> 
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
                            <button type="button" onclick="sumarCantidadLotes()" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Agregar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection