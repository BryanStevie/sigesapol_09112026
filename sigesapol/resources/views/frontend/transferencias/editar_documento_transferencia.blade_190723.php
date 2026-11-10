@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
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
    $cmbid_motivo = $("#frm_transferencia").find("#id_motivo");
    $cmbid_motivo.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    
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
            if(tipo==1||tipo==3){
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
		
		recuperar_tipo("<?php echo ($transferencia->id_farmacia_destino>0)?$transferencia->id_farmacia_destino:$transferencia->id_consultorio?>");
                $("#id_motivo").trigger('change');
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
	
	
	
	function recuperar_tipo(id){
		var cmbtipo = $('#tipo').val();
		var flag = $('#tipo option:selected').attr("flag");
		option = {
			url: '/cargardestino/' + cmbtipo + "/"+ flag,
			type: 'GET',
			dataType: 'json',
			data: {}
		};
		$.ajax(option).done(function (data) {
			cargarComboDestino($cmbid_lugar, data,id);
			$cmbid_lugar.val(id).trigger("change");
			setTimeout(function(){ 
				$cmbid_responsable.val("<?php echo $transferencia->nombre_jefe_servicio?>").trigger("change");
			 }, 1000);
		});        
	
	}
		
	
	
</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')

<?php 
	if($transferencia->estado<=2):
		$disabled = "";
		$title = "EDITAR";
		$msg = " Modificar Transferencia de Productos Farmacéuticos ";
	else:
        if(Auth::user()->id_farmacia==46):
            if(Auth::user()->flagresponsablefarmacia==1):
                $disabled = "";
                $title = "EDITAR";
                $msg = " Modificar Transferencia de Productos Farmacéuticos ";
            else:
    		    $disabled = "disabled='disabled'";
    		    $title = "VER";
    		    $msg = "Ver Transferencia Culminada de Productos Farmacéuticos";
            endif;
        else:
                $disabled = "disabled='disabled'";
                $title = "VER";
                $msg = "Ver Transferencia Culminada de Productos Farmacéuticos";
            endif;
	endif;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $title ?></h3>
                    <a href="/all_transferencias/{{ Auth::user()->id_farmacia }}" class="btn btn-default pull-right">
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
                        <div class="col-md-6">
                        @if($id_farmacia==46)    
                            <label>Nro Pecosa</label>
                        @else
                            <label>Nro Acta</label>
                        @endif
                            <input readonly onkeypress="return soloNumeros(event);" name="nro_acta" value="<?php echo $transferencia->nro_acta ?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" <?php echo $disabled ?> >
                            <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                            <input name="id" value="<?php echo $transferencia->id ?>" class="form-control" type="hidden"> 
                        </div>                        
                        <div class="col-md-6">
                            <label>Fecha</label>
                            <input name="fecha" value="<?php echo date("d-m-Y", strtotime($transferencia->fecha)); ?>" id="fecha" class="form-control datepicker" required="" <?php echo $disabled ?>>
                        </div>                        
                        <!------- Fila 02--------------------------------------->                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Origen</label>
                            <!--
<select class="form-control" required="" name="id_origen" id="id_origen">
    <option value="{{$farmacia->id}}">{{$farmacia->nombre}}</option>
</select>
                            -->
                            <input type="hidden" name="id_origen" id="id_origen" value="<?php echo $farmacia->id ?>">
                            <input readonly="" name="origen" value="<?php echo $farmacia->nombre ?>" id="origen" class="form-control">
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Origen)</label>
                            <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <!------- Fila 03--------------------------------------->                        
                        @if($id_farmacia==46)
                        <div class="col-md-12 etiqueta">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="" <?php echo $disabled ?>>
                                <option value="" selected="">Seleccionar</option>
                                <option value="3"<?php if ($transferencia->tipo == 3) echo "selected='selected'" ?>>Transferencia Externa EESS PNP</option>
                            </select>
                        </div>
                        @else
                        <div class="col-md-12 etiqueta">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="" <?php echo $disabled ?>>
                                <option value="" selected="">Seleccionar</option>
                                <option value="1" flag="2" <?php if ($transferencia->tipo == 1 and $flag == 2) echo "selected='selected'" ?>>Transferencia Externa EESS PNP</option>
                                <option value="1" flag="1" <?php if ($transferencia->tipo == 1 and $flag == 1) echo "selected='selected'" ?>>Distribucion Interna de Medicamentos</option>
                                <option value="2" <?php if ($transferencia->tipo == 2) echo "selected='selected'" ?>>Transferencia Interna Servicios</option> 
                            </select>
                        </div>
                        @endif
                        <!------- Fila 04--------------------------------------->                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Destino</label>
                            <select id="id_lugar" name="id_lugar" class="form-control"  required="" <?php echo $disabled ?> >
                                <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                            </select>
                        </div>
                        @if($id_farmacia==46)
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Destino)</label>                            
                            <input name="responsable_destino" value="<?php echo $transferencia->nombre_jefe_servicio ?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" <?php echo $disabled ?> >
                        </div>
                        @else
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Destino)</label>                            
                            <select id="responsable_destino" name="responsable_destino" class="form-control"  required="" <?php echo $disabled ?> >
                                <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                            </select>
                        </div>
                        @endif

                        <div class="col-md-6" style="margin-top:10px">  
                            <label>Motivo</label>
                                <?php 
                                $idMotivo = $transferencia->id_motitransferencia;?>
                            <select class="form-control" required="" name="id_motivo" id="id_motivo">
                                @foreach($motivo_transferencia as $mot)
                                <?php 
                                $idM = $mot->id;?>
                                    @if($id_farmacia==46)
                                        <option value="{{$mot->id}}" <?php if($idMotivo == $idM) {echo " selected";}?>>{{$mot->descripcion_conceptos}}</option>
                                    @else
                                        <option value="{{$mot->id}}" <?php if($idMotivo == $idM) {echo " selected";}?>>{{$mot->nombre}}</option>
                                    @endif
                                
                                @endforeach
                            </select>
                            <input type="hidden" id="causa" name="causa" value="">
                        </div>
                        
                        <div class="col-md-6" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" <?php echo $disabled ?> ><?php echo $transferencia->observacion ?></textarea>
                        </div>

                        <div class="clearfix" style="margin-bottom:15px"></div>

                        <a href="/imprimir_transferencia/<?php echo $transferencia->id ?>" class="btn btn-danger pull-right" target="_blank">
                            Imprimir<i class="fa fa-file-pdf-o"></i>
                        </a>

                        <?php if ($transferencia->estado <= 2): ?>
                            <div class="col-md-3 pull-right">
                                <input class="btn btn-success form-control" value="Editar Transferencia" type="submit" id="btnReg">
                            </div>

                        <?php else: ?>
                            <?php if (Auth::user()->id_farmacia == 46): ?>
                                <?php if (Auth::user()->flagresponsablefarmacia == 1): ?>
                                    <div class="col-md-3 pull-right">
                                        <input class="btn btn-success form-control" value="Editar Transferencia" type="submit" id="btnReg">
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection