@extends('frontend.layouts.master')
@section('renderjs')
<script>
    var x=2;
    var y=1;

    function getCargaCampos(valor,valor1){

        var concepto  = $('#id_motivo').val();
        if(concepto==13){
            $('#nro_acta').attr('readonly', true);     
            $('#nro_acta').val(valor);             
        }else
        { 
            $('#nro_acta').attr('readonly', true);     
            $('#nro_acta').val(valor1); 
        }
    }
    
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) || (keynum == 45))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 || key == 45)
        }
    
    $cmbtipo = $("#frm_transferencia").find("#tipo");
    $cmbtipo.select2();
    $cmbid_ipress = $("#frm_transferencia").find("#id_ipress");
    $cmbid_ipress.select2();
    $cmbid_origen = $("#frm_transferencia").find("#origen");
    $cmbid_origen.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    $cmbid_motivo = $("#frm_transferencia").find("#id_motivo");
    $cmbid_motivo.select2();
    $('#id_ipress').prop('disabled', true);
    
    $cmbid_responsable = $("#frm_transferencia").find("#responsable_destino");
    $cmbid_responsable.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
	
		$('#btnRegTransferencia').on("click", function (e) {
			guardarTransferencia();
		});
	
        $cmbtipo.change(function () {
            $this = $(this);
            cmbtipo = $cmbtipo.val();			
            option = {
                url: '/cargardestino/' + cmbtipo + '/'+ {{ Auth::user()->id_establecimiento }},
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            
            $.ajax(option).done(function (data) {               
                
                if(cmbtipo==3){
                    $('#id_ipress').prop('disabled', false); 
                    $('#id_lugar').prop('disabled', true);                    
                    cargarComboDestino($cmbid_ipress, data);
                    $cmbid_lugar.val(null).trigger("change"); 
                    $cmbid_ipress.val(null).trigger("change");
                }
                else
                {                    
                    $('#id_ipress').prop('disabled', true);
                    $('#id_lugar').prop('disabled', false); 
                    cargarComboDestino($cmbid_lugar, data);
                    $cmbid_lugar.val(null).trigger("change");
                    $cmbid_ipress.val(null).trigger("change");
                }
            });
            
            if(x==1){            
                    $('#responsable_label').remove();
                    $('#responsable_destino').select2('destroy');
                    $('#responsable_destino').remove();
            }
            else
            {
                if(x==0){ 
                    $('#responsable_label').remove();
                    $('#responsable_destino').text('destroy');
                    $('#responsable_destino').remove();   
                }
            }
        });
        
       $cmbid_ipress.change(function () {
            
            $this = $(this); 
            cmbid_ipress = $cmbid_ipress.val();  

            if(cmbid_ipress!=null){
                tipo=$("#frm_transferencia").find("#tipo").val();
                id_ipress=$("#frm_transferencia").find("#id_ipress").val();

                if(tipo==4){
                    cmbid_ipress = 0;                
                }
                else
                {   if(tipo==3)
                      $('#id_lugar').prop('disabled', true); 
                    if(id_ipress>0)
                      $('#id_lugar').prop('disabled', false);
                }
                $cmbid_lugar.html('');
                option = {
                    url: '/cargardestino/' + 1 + '/' + cmbid_ipress,
                    type: 'GET',
                    dataType: 'json',
                    data: {}
                };
                $.ajax(option).done(function (data) {  
                    cargarComboDestino($cmbid_lugar, data);
                    $cmbid_lugar.val(null).trigger("change");  
                    $cmbid_responsable.val(null).trigger("change");   
                });                   
            }
        });        
        
        $cmbid_lugar.change(function () {
            $this = $(this); 
            cmbid_lugar = $cmbid_lugar.val();
            tipo=$("#frm_transferencia").find("#tipo").val();
            almacen=$("#frm_transferencia").find("#almacen").val();
            
            if(cmbid_lugar!=null){
                if(almacen!=1){
                    switch(tipo){
                        case '1':
                                if(cmbid_lugar==0) {getResponsable(); x=0;} //text
                                else { getResponsable2(); x=1; $cmbid_responsable = $("#frm_transferencia").find("#responsable_destino"); $cmbid_responsable.select2();} 
                               break;
                        case '2':
                                if(cmbid_lugar>0) {getResponsable2(); x=1;$cmbid_responsable = $("#frm_transferencia").find("#responsable_destino"); $cmbid_responsable.select2();}
                               break;
                        case '3':
                                if(cmbid_lugar==0) {getResponsable(); x=0;} //text
                                else {getResponsable2(); x=1;$cmbid_responsable = $("#frm_transferencia").find("#responsable_destino");$cmbid_responsable.select2();}
                               break;
                        case '4':
                                if(cmbid_lugar>0) {getResponsable(); x=0;}
                               break;
                    }
                }
                else
                {
                 getResponsable(); x=0;   
                }
            }            
        });
    });

    function getResponsable(){        
        
        if(x==1){            
            $('#responsable_label').remove();
            $('#responsable_destino').select2('destroy');
            $('#responsable_destino').remove();
        }
        else
        {
            $('#responsable_label').remove();
            $('#responsable_destino').text('destroy');
            $('#responsable_destino').remove();
        }
            

        var label = document.createElement("label"); 
        var text = document.createTextNode("Responsable (Lugar Destino)"); 
        label.setAttribute("name", "responsable_label");
        label.setAttribute("id", "responsable_label");
        label.appendChild(text);        
        $('#cargaresponsable').append(label);

        lugar=$("#frm_transferencia").find("#id_lugar").val();        
        var text = document.createElement("input");
           text.setAttribute("id", "responsable_destino");    
           text.setAttribute("name", "responsable_destino");           
           text.setAttribute("tabindex", "5");             
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");                              
        $('#cargaresponsable').append(text);           
    }

    function getResponsable2(){   

        
        if(x==0){            
            $('#responsable_label').remove();
            $('#responsable_destino').text('destroy');
            $('#responsable_destino').remove();
        }
        else
        {
            $('#responsable_label').remove();
            $('#responsable_destino').select2('destroy');
            $('#responsable_destino').remove();
        }

        var label = document.createElement("label"); 
        var text = document.createTextNode("Responsable (Lugar Destino)"); 
        label.setAttribute("name", "responsable_label");
        label.setAttribute("id", "responsable_label");
        label.appendChild(text);        
        $('#cargaresponsable').append(label);

        lugar=$("#frm_transferencia").find("#id_lugar").val();
        var select = document.createElement("select");
        select.setAttribute("name", "responsable_destino");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "responsable_destino"); 
        select.setAttribute("tabindex", "7");           
        select.style.width = "100%";            
        $('#cargaresponsable').append(select);

        tipo=$("#frm_transferencia").find("#tipo").val();        
        if(tipo!=2){
            $.ajax({
                url: '/responsable_destino/' + lugar,
                success: function (result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";  
                    $(result).each(function (ii, oo) {
                        newOption += '<option value="' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+ '">' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+'</option>';
                    });
                    $('#responsable_destino').html(newOption)
                }
            });    
        }
        else
        {
            $.ajax({
                url: '/profesionales_destino/' + {{ Auth::user()->id_establecimiento }} + '/'+lugar,
                success: function (result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";  
                    $(result).each(function (ii, oo) {
                        newOption += '<option value="' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+ '">' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+'</option>';
                    });
                    $('#responsable_destino').html(newOption)
                }
            });    
        }
    }
    
    function cargarComboDestino($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    }    

    function cargarComboResponsable($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+ '">' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+'</option>')
        });
    }
	
	function guardarTransferencia(){
		
		$("#btnRegTransferencia").attr("disabled",true);
		
		var tipo = $('#tipo').val();
		var id_lugar = $('#id_lugar').val();
		var id_motivo = $('#id_motivo').val();
        var responsable = $('#responsable_destino').val();
		var msg = "";
		if(responsable=="")msg+="Debe ingresar el responsable<br>";
        if(tipo=="")msg+="Debe seleccionar un Tipo de Transferencia<br>";
		if(id_lugar=="" || id_lugar == null)msg+="Debe seleccionar una Lugar de destino<br>";
		if(id_motivo=="")msg+="Debe seleccionar un Motivo<br>";
	
    
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
        fecha=$('#frm_transferencia').find("#fecha").val();                        
        var xMonth=fecha.substring(3, 5);                 
        var xDay=fecha.substring(0, 2);  
        var xYear=fecha.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
				$("#btnRegTransferencia").attr("disabled",false);
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
					$("#btnRegTransferencia").attr("disabled",false);
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
							$("#btnRegTransferencia").attr("disabled",false);
                            return false;  
                        }
                    }
                }  
            }
        }
        
        //---------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frm_transferencia').find("#fecha_hace_treinta_dias").val(); 
        fecha_transferencia=$('#frm_transferencia').find("#fecha").val();                        
        var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
        var xDay=fecha_hace_treinta_dias.substring(0, 2);  
        var xYear=fecha_hace_treinta_dias.substring(6,10);  
        var yMonth=fecha_transferencia.substring(3, 5);          
        var yDay=fecha_transferencia.substring(0, 2);  
        var yYear=fecha_transferencia.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
				$("#btnRegTransferencia").attr("disabled",false);
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
					$("#btnRegTransferencia").attr("disabled",false);
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
							$("#btnRegTransferencia").attr("disabled",false);
                            return false;  
                        }
                    }
                }  
            }
        }
		
		if(msg!=""){
			$("#btnRegTransferencia").attr("disabled",false);
			bootbox.alert(msg); 
			return false;
		}
		else{
			$("#frm_transferencia").submit();
		}
	
	}
	
    //----------------REGISTRAR LA TRANSFERENCIA---------------------------------
    $("#btnReg").on("click", function () {
        //----------------------------------------------------------------------
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
        fecha=$('#frm_transferencia').find("#fecha").val();                        
        var xMonth=fecha.substring(3, 5);                 
        var xDay=fecha.substring(0, 2);  
        var xYear=fecha.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            return false;  
                        }
                    }
                }  
            }
        }
        
        //---------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frm_transferencia').find("#fecha_hace_treinta_dias").val(); 
        fecha_transferencia=$('#frm_transferencia').find("#fecha").val();                        
        var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
        var xDay=fecha_hace_treinta_dias.substring(0, 2);  
        var xYear=fecha_hace_treinta_dias.substring(6,10);  
        var yMonth=fecha_transferencia.substring(3, 5);          
        var yDay=fecha_transferencia.substring(0, 2);  
        var yYear=fecha_transferencia.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            return false;  
                        }
                    }
                }  
            }
        }
    });
</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR</h3>                    
                    <a href="/all_transferencias/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Transferencias
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('registrar_documento_transferencia')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend>
                            Registrar Transferencia de Productos Farmacéuticos
                        </legend>
                        <!------- Fila 01--------------------------------------->
                        <div class="row">
                            <div class="col-md-3">
                                @if($almacen==1)
                                <label>Nro Pecosa</label>
                                @else
                                <label>Nro Acta</label>
                                @endif
                                <input readonly="" onkeypress="return soloNumeros(event);" id="nro_acta" name="nro_acta" value="<?php echo $nro_acta?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">     
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="almacen" value="{{ $almacen }}" class="form-control" id="almacen" type="hidden"> 
                                <input name="id_origen" value="{{ $farmacia->id }}" class="form-control" id="id_origen" type="hidden"> 
                                <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden"> 
                            </div>                        
                            <div class="col-md-3">
                                <label>Fecha</label>
                                @if($almacen!=1)
                                    <input name="fecha" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" id="fecha" class="form-control datepicker" required="">
                                @else
                                    <input name="fecha" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" id="fecha" class="form-control" required="">
                                @endif

                            </div>                        
                        </div>
                        <!------- Fila 02--------------------------------------->                        
                        <div class="row">
                            <div class="col-md-6 etiqueta">
                                <label>Farmacia Origen</label>
                                <select class="form-control" required="" name="origen" disabled="disabled" id="origen">
                                    <option value="{{$farmacia->id}}">{{$farmacia->nombre}}</option>
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>Responsable (Lugar Origen)</label>
                                <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                        </div>
                        <!------- Fila 03--------------------------------------->                        
                        <div class="row">
                            <div class="col-md-6 etiqueta">
                                <label>Tipo</label>
                                <select name="tipo" id="tipo" class="form-control" required="required">
                                    <option value="" selected="">Seleccionar</option>
                                    <option value="4">Transferencia IPRESS NO PNP</option>
                                    <option value="3">Transferencia Externa EESS PNP</option>
                                    @if($almacen!=1)
                                        <option value="1">Distribucion Interna de Medicamentos</option>
                                        <option value="2">Transferencia Interna Servicios</option> 
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>IPRESS</label>
                                <select id="id_ipress" name="id_ipress" class="form-control"   required="required">                                    
                                </select>
                            </div>
                        </div>
                        <!------- Fila 04--------------------------------------->
                        <div class="row">                            
                            <div class="col-md-6 etiqueta">
                                <label>Lugar Destino</label>
                                <select id="id_lugar" name="id_lugar" class="form-control"  required="required">
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta" id="cargaresponsable"></div>
                        </div>
                        <!------ Fila 05 --------------------------------------------->
                        <div class="row">
                            <div style="display: none">  
                                <label>Causa</label>
                                <textarea id="causa" name="causa" class="form-control input-md" ></textarea>
                            </div>
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>Motivo</label>
                                <select class="form-control" required="required" name="id_motivo" id="id_motivo">
                                    <option value="">- Seleccione Motivo -</option>
                                    @foreach($motivo_transferencia as $mot)
                                    <option value="{{$mot->id}}">{{$mot->nombre}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="causa" name="causa" value="">
                            </div>
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>Observaci&oacute;n</label>
                                <textarea id="observacion" name="observacion" class="form-control input-md" ></textarea>
                            </div>
                        </div>
                        <!-------- Fila 06 ----------------------------------------------------->
                        <div class="row">
                            <div class="col-md-4 etiqueta pull-right">
                                <!--<input class="btn btn-success form-control" value="Registrar y continuar" type="submit" id="btnReg">-->
								<input class="btn btn-success form-control" value="Registrar y continuar" type="button" id="btnRegTransferencia">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection