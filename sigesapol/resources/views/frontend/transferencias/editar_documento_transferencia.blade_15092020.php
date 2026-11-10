<?php
use Carbon\Carbon;

        if(Auth::user()->flagresponsablefarmacia==1){         
            //Calcular Fecha limite para las modificaciones
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($transferencia->updated_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=4;
        }

?>
@extends('frontend.layouts.master')
@section('renderjs')
<script>
    var x=2;
    
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
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    $cmbid_motivo = $("#frm_transferencia").find("#id_motivo");
    $cmbid_motivo.select2();
    $('#id_ipress').prop('disabled', true);
    $('#tipo').prop('disabled', true);
    $('#id_lugar').prop('disabled', true);
    
    $cmbid_responsable = $("#frm_transferencia").find("#responsable_destino");
    $cmbid_responsable.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
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

                    if(cmbtipo==4){
                        if(x==2){            
                            $('#responsable_label').remove();
                            $('#responsable_destino').select2('destroy');
                            $('#responsable_destino').remove();
                        }   
                    }   

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
        
        //---------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frm_transferencia').find("#fecha_hace_treinta_dias").val(); 
        fecha_transferencia=$('#frm_transferencia').find("#fecha").val();                        
        var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
        var xDay=fecha_hace_treinta_dias.substring(0, 2);  
        var xYear=fecha_hace_treinta_dias.substring(6,10);  
        var yMonth=fecha_transferencia.substring(3, 5);          
        var yDay=fecha_transferencia.substring(0, 2);  
        var yYear=fecha_transferencia.substring(6,10);         
        
        
        
        
    });	
	
	
</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')

<?php 
    

    if ($transferencia->estado ==1 || $transferencia->estado ==5){
        if(Auth::user()->flagresponsablefarmacia==1):
            $disabled = "";
            $title = "EDITAR/MODIFICA TRANSFERENCIAS";
            $msg = " Modificar Transferencia de Productos Farmacéuticos ";
        else:
    	    $disabled = "disabled='disabled'";
    	    $title = "VER TRANSFERENCIAS";
    	    $msg = "Ver Transferencia Culminada de Productos Farmacéuticos";
        endif;
    }
    else
    {
            $disabled = "disabled='disabled'";
            $title = "VER TRANSFERENCIAS";
            $msg = "Ver Transferencia Culminada de Productos Farmacéuticos";
        
    }
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $title ?></h3>
                    <a href="/all_transferencias/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Transferencias
                    </a>
                </div>
                <div class="panel-body">

                    <form id="frm_transferencia" method="POST" action="{{ route('editar_documento_transferencia')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend>
                            <?php echo $msg ?>
                        </legend>
                        <!------- Fila 01--------------------------------------->                        
                        <div class="row">
                            <div class="col-md-3">
                            @if($farmacia->almacen==1)    
                                <label>Nro Pecosa</label>
                            @else
                                <label>Nro Acta</label>
                            @endif
                                <input readonly onkeypress="return soloNumeros(event);" name="nro_acta" value="<?php echo $transferencia->nro_acta ?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" <?php echo $disabled ?> >
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="id" value="<?php echo $transferencia->id ?>" class="form-control" type="hidden"> 
                                <input name="almacen" value="{{ $almacen }}" class="form-control" id="almacen" type="hidden"> 
                            </div>                        
                            <div class="col-md-3">
                                <label>Fecha</label>
                                <input readonly name="fecha" value="<?php echo date("d-m-Y", strtotime($transferencia->fecha)); ?>" id="fecha" class="form-control datepicker" required="" <?php echo $disabled ?>>
                            </div>                        
                        </div>
                        <!------- Fila 02---------------------------------------> 
                        <div class="row">                    
                            <div class="col-md-6 etiqueta">
                                <label>Lugar Origen</label>
                                <input type="hidden" name="id_origen" id="id_origen" value="<?php echo $farmacia->id ?>">
                                <input readonly="" name="origen" value="<?php echo $farmacia->nombre ?>" id="origen" class="form-control">
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
                                <select name="tipo" id="tipo" class="form-control" required="" <?php echo $disabled ?>>
                                    <option value="" selected="">Seleccionar</option>
                                    <option value="4" <?php if ($transferencia->tipo == 4) echo "selected='selected'" ?>>Transferencia IPRESS NO PNP</option>
                                    <option value="3" <?php if ($transferencia->tipo == 3) echo "selected='selected'" ?>>Transferencia Externa EESS PNP</option>
                                    <option value="1" <?php if ($transferencia->tipo == 1) echo "selected='selected'" ?>>Distribucion Interna de Medicamentos</option>
                                    <option value="2" <?php if ($transferencia->tipo == 2) echo "selected='selected'" ?>>Transferencia Interna Servicios</option> 
                                </select>
                            </div>
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>IPRESS</label>
                                <?php $idIpress = $transferencia->id_ipress;?>
                                <select class="form-control" required="" name="id_ipress" id="id_ipress" <?php echo $disabled; ?>>
                                @if($transferencia->tipo==3)
                                    @foreach($establecimientos as $ip)
                                        <?php $idip = $ip->id;?>
                                        <option value="{{$ip->id}}" <?php if($idIpress == $idip) {echo " selected";}?>>{{$ip->nombre}}</option>
                                    @endforeach
                                @else
                                    <option value=" " selected='selected'></option>
                                @endif
                                </select>
                            </div>
                        </div>

                        <!------- Fila 04--------------------------------------->
                        <div class="row">    
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>Lugar Destino</label>
                                @if($almacen!=1)
                                <?php if ($muestra=='text'):?>
                                    <?php if ($transferencia->tipo!=4):?>
                                        <select id="id_lugar" name="id_lugar" class="form-control"  required="" <?php echo $disabled ?> >
                                            <option value="0">SIN SISTEMA</option>
                                        </select>
                                    <?php else: ?>
                                        <?php 
                                            $idLugar = $lugar;                                    
                                        ?>
                                        <select id="id_lugar" name="id_lugar" class="form-control"  required="" <?php echo $disabled ?> >
                                            @foreach($destinos as $dest)
                                                <?php 
                                                    $iddest= $dest->id;?>
                                                    <option value="{{$dest->id}}" <?php if($idLugar == $iddest) {echo " selected";}?>>{{$dest->nombre}}</option>
                                            @endforeach
                                        </select>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php 
                                    $idLugar = $lugar;                                    
                                     ?>
                                    <select id="id_lugar" name="id_lugar" class="form-control"  required="" <?php echo $disabled ?> >
                                    @foreach($destinos as $dest)
                                        <?php 
                                        $iddest= $dest->id;?>
                                        <option value="{{$dest->id}}" <?php if($idLugar == $iddest) {echo " selected";}?>>{{$dest->nombre}}</option>
                                    @endforeach
                                    </select>
                                <?php endif; ?>
                                @else
                                <?php 
                                            $idLugar = $lugar;                                    
                                        ?>
                                        <select id="id_lugar" name="id_lugar" class="form-control"  required="" <?php echo $disabled ?> >
                                            @foreach($destinos as $dest)
                                                <?php 
                                                    $iddest= $dest->id;?>
                                                    <option value="{{$dest->id}}" <?php if($idLugar == $iddest) {echo " selected";}?>>{{$dest->nombre}}</option>
                                            @endforeach
                                        </select>

                                @endif
                            </div>

                            <div class="col-md-6 etiqueta" id="cargaresponsable">
                                <?php if ($muestra=='text') {?>
                                    <label id="responsable_label">Responsable (Lugar Destino)</label>
                                    <input name="responsable_destino" <?php echo $disabled ?> value="<?php echo $transferencia->nombre_jefe_servicio ?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" <?php echo $disabled ?>>
                                <?php }
                                else
                                {
                                    ?>
                                    <label id="responsable_label">Responsable (Lugar Destino)</label>
                                    <?php 
                                    $idResponsable = $transferencia->nombre_jefe_servicio; ?>
                                    <select class="form-control" required="" name="responsable_destino" id="responsable_destino" <?php echo $disabled ?>>
                                        @foreach($responsable_destinos as $resp)
                                            <?php 
                                            $idRes = $resp->name.' '.$resp->paterno_usuario.' '.$resp->materno_usuario;
                                            ?>                                        
                                            <option value="{{ $resp->name }} {{ $resp->paterno_usuario}} {{ $resp->materno_usuario}}"<?php if($idResponsable == $idRes) {echo " selected";}?>> {{ $resp->name }} {{ $resp->paterno_usuario}} {{ $resp->materno_usuario}}</option>
                                        @endforeach
                                    </select>
                                <?php }?>
                            </div>
                        </div>
                        <!------- Fila 05--------------------------------------->
                        <div class="row">                        
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>Motivo</label>
                                    <?php 
                                    $idMotivo = $transferencia->id_motitransferencia;?>
                                <select class="form-control" required="" name="id_motivo" id="id_motivo" <?php echo $disabled ?> >
                                    @foreach($motivo_transferencia as $mot)
                                        <?php 
                                        $idM = $mot->id;?>                                        
                                        <option value="{{$mot->id}}" <?php if($idMotivo == $idM) {echo " selected";}?>>{{$mot->nombre}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="causa" name="causa" value="">
                            </div>
                            <div class="col-md-6" style="margin-top:10px">  
                                <label>Observaci&oacute;n</label>
                                <textarea id="observacion" name="observacion" class="form-control input-md" <?php echo $disabled ?> ><?php echo $transferencia->observacion ?></textarea>
                            </div>
                        </div>

                        <div class="clearfix" style="margin-bottom:15px"></div>

                        <a href="/imprimir_transferencia/<?php echo $transferencia->id ?>" class="btn btn-danger pull-right" target="_blank">
                            Imprimir<i class="fa fa-file-pdf-o"></i>
                        </a>
                        <div class="row">                        
                            <?php if ($transferencia->estado == 1 || $transferencia->estado==5 ): ?>
                                <div class="col-md-3 pull-right">
                                    <button tabindex="13"  class="btn btn-success form-control" value="" type="submit" id="btnReg" onclick="validateFrm()">
                                        <i class="fa fa-save"></i> Actualizar
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="col-md-3 pull-right">
                                    <button tabindex="13"  disabled="disabled"  class="btn btn-success form-control" value="" type="submit" id="btnReg">
                                        <i class="fa fa-save"></i> Actualizar
                                    </button>
                                </div>

                            <?php endif; ?>
                        </div>         
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection