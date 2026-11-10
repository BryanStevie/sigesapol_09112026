@extends('frontend.layouts.master')
@section('renderjs')
<script>

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
    $cmbid_origen = $("#frm_transferencia").find("#id_origen");
    $cmbid_origen.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    $cmbid_motivo = $("#frm_transferencia").find("#id_motivo");
    $cmbid_motivo.select2();
    
    $cmbid_responsable = $("#frm_transferencia").find("#responsable_destino");
    $cmbid_responsable.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        $cmbtipo.change(function () {
            $this = $(this);
            cmbtipo = $cmbtipo.val();
			var flag = $('#tipo option:selected').attr("flag");
            option = {
                url: '/cargardestino/' + cmbtipo + "/"+ flag,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                //alert(data);
                cargarComboDestino($cmbid_lugar, data);
                $cmbid_lugar.val(null).trigger("change");
            });
        });
        
        
        $cmbid_lugar.change(function () {
            $this = $(this); 
            cmbid_lugar = $cmbid_lugar.val();
            
            tipo=$("#frm_transferencia").find("#tipo").val();
            if(tipo==1 || tipo==3){
                $cmbid_responsable.html('');
                option = {
                    url: '/responsable_destino/' + cmbid_lugar,
                    type: 'GET',
                    dataType: 'json',
                    data: {}
                };
                $.ajax(option).done(function (data) {                    
                    cargarComboResponsable($cmbid_responsable, data);
                    $cmbid_responsable.val(null).trigger("change");                                           
                });
            }else{
                $cmbid_responsable.html('');
                option = {
                    url: '/profesionales_destino/' + {{ Auth::user()->id_farmacia }},
                    type: 'GET',
                    dataType: 'json',
                    data: {}
                };
                $.ajax(option).done(function (data) {                    
                    cargarComboResponsable($cmbid_responsable, data);
                    $cmbid_responsable.val(null).trigger("change");                                           
                });
            }
            
        });
    });
    
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
                    <a href="/all_transferencias/{{ Auth::user()->id_farmacia }}" class="btn btn-default pull-right">
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
                        <div class="col-md-4">
                            @if($id_farmacia==46)
                            <label>Nro Pecosa</label>
                            @else
                            <label>Nro Acta</label>
                            @endif
                            <input readonly="" autofocus onkeypress="return soloNumeros(event);" id="nro_acta" name="nro_acta" value="<?php echo $nro_acta?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">     
                            <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                            <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden"> 
                        </div>                        
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input name="fecha" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" id="fecha" class="form-control datepicker" required="">
                        </div>                        
                        <!------- Fila 02--------------------------------------->                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Origen</label>
                            <select class="form-control" required="" name="id_origen" id="id_origen">
                                <option value="{{$farmacia->id}}">{{$farmacia->nombre}}</option>
                            </select>
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Origen)</label>
                            <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <!------- Fila 03--------------------------------------->                        
                        @if($id_farmacia==46)
                        <div class="col-md-12 etiqueta">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="">
                                <option value="" selected="">Seleccionar</option>
                                <option value="3">Transferencia Externa EESS PNP</option>
                            </select>
                        </div>
                        @else
                        <div class="col-md-12 etiqueta">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="">
                                <option value="" selected="">Seleccionar</option>
                                <option value="1" flag="2">Transferencia Externa EESS PNP</option>
                                <option value="1" flag="1">Distribucion Interna de Medicamentos</option>
                                <option value="2">Transferencia Interna Servicios</option> 
                            </select>
                        </div>
                        @endif
                        <!------- Fila 04--------------------------------------->                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Destino</label>
                            <select id="id_lugar" name="id_lugar" class="form-control"  required="">
                                <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                            </select>
                        </div>
                        @if($id_farmacia==46)
                            <div class="col-md-6 etiqueta">
                                <label>Responsable (Lugar Destino)</label>                            
                                <input name="responsable_destino" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">     
                            </div>
                        @else
                            <div class="col-md-6 etiqueta">
                                <label>Responsable (Lugar Destino)</label>                            
                                <select id="responsable_destino" name="responsable_destino" class="form-control"  required="">                                                                
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                                </select>
                            </div>
                        @endif

                        <div style="display: none">  
                            <label>Causa</label>
                            <textarea id="causa" name="causa" class="form-control input-md" ></textarea>
                        </div>
                        @if($id_farmacia==46)
                        <div class="col-md-6" style="margin-top:10px">  
                            <label>Motivo</label>
                            <select onchange="getCargaCampos('<?php echo $nro_acta; ?>', '<?php echo $nro_acta2; ?>');" class="form-control" required="" name="id_motivo" id="id_motivo">
                                <option value="" disabled selected style="color: #888888">- Seleccione Motivo -</option>
                                @foreach($motivo_transferencia as $mot)                                    
                                    <option value="{{$mot->id}}">{{$mot->descripcion_conceptos}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="causa" name="causa" value="">
                        </div>
                        @else
                        <div class="col-md-6" style="margin-top:10px">  
                            <label>Motivo</label>
                            <select class="form-control" required="" name="id_motivo" id="id_motivo">
                                <option value="0">- Seleccione Motivo -</option>
                                @foreach($motivo_transferencia as $mot)
                                <option value="{{$mot->id}}">{{$mot->nombre}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="causa" name="causa" value="">
                        </div>
                        @endif

                        <div class="col-md-6" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" ></textarea>
                        </div>

                        <div class="col-md-4 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar y continuar" type="submit" id="btnReg">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection