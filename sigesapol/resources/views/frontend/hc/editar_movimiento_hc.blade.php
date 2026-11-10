<?php
use Carbon\Carbon;

        if(Auth::user()->flagresponsablefarmacia==1){         
            //Calcular Fecha limite para las modificaciones
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($movimiento_historias->updated_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=30;
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
    
    $cmbid_area = $("#frm_archivo").find("#id_area");
    $cmbid_area.select2();
    $cmbid_tipo_doc = $("#frm_archivo").find("#id_tipo_doc");
    $cmbid_tipo_doc.select2();
    $cmbid_origen = $("#frm_archivo").find("#origen");
    $cmbid_origen.select2();
    $cmbid_citado = $("#frm_archivo").find("#id_tipo_citado");
    $cmbid_citado.select2();
    $cmbid_turno = $("#frm_archivo").find("#id_turno");
    $cmbid_turno.select2();
    $cmbid_consultorio = $("#frm_archivo").find("#id_consultorio");
    $cmbid_consultorio.select2();
    $cmbid_responsable = $("#frm_archivo").find("#responsable_destino");
    $cmbid_responsable.select2();
    
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        
        $cmbid_consultorio.change(function () {
            $this = $(this); 
            cmbid_consultorio = $cmbid_consultorio.val();
            if(cmbid_consultorio!=null){
                $cmbid_responsable.html('');
                option = {
                    url: '/profesionales_destino/' + {{ Auth::user()->id_establecimiento }} + '/'+cmbid_consultorio,
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
        fecha=$('#frm_archivo').find("#fecha").val();                        
        var xMonth=fecha.substring(3, 5);                 
        var xDay=fecha.substring(0, 2);  
        var xYear=fecha.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        
        //---------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frm_archivo').find("#fecha_hace_treinta_dias").val(); 
        fecha_transferencia=$('#frm_archivo').find("#fecha").val();                        
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
    if ($movimiento_historias->estado ==1 || $movimiento_historias->estado ==5){
        if(Auth::user()->flagresponsablefarmacia==1):
            $disabled = "";
        else:
    	    $disabled = "disabled='disabled'";
        endif;
    }
    else
    {
        $disabled = "disabled='disabled'";
    }

    if($tiempo<31){
        $disabled = "";
        $disabled2= "";
    }

    if (Auth::user()->flagestablecimientos==1){
        $disabled = "";
        $disabled2= "";
    }
?>
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-2">
            <div class="col-sm-12 pull-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/all_movimiento_hc/"><i class="fa fa-table fa-fw"></i> Listar Salidas de Historias</a></li>
                    <li><a href="/add_hc_registro_salida/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Registrar Salida</a></li>
                    <li class="active"><a href="/editar_movimiento_hc/{{ $id }}"><i class="fa fa-table fa-fw"></i> Editar Salida</a></li>
                    <li><a href="/all_historias_recibidas/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Ingreso de Historias</a></li>
                    <li><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
                    <li><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
                </ul>
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-body">
                
            <legend style="text-align:center">{{$farmacia->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>
            <?php 
                $disabled_mov_hc = "";
                if($flag==1){
                  $disabled_mov_hc = "disabled='disabled'";
                  echo '<div class="col-sm-12">
                      <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                        Debe ser estar asignado como profesional de archivo y/o tener un servicio asignado para poder hacer el registro.
                      </div>
                    </div>';
                }
            ?>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">EDITAR MOVIMIENTO DE HISTORIA CLINICA</h3>
                    </div>
                    <div class="panel-body">

                        <form id="frm_archivo" method="POST" action="{{ route('registrar_salida_hc')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!------- Fila 01--------------------------------------->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label>Nro Orden</label>
                                        <input readonly="" onkeypress="return soloNumeros(event);" id="nro_mov" name="nro_mov" value="<?php echo $movimiento_historias->nro_movimiento ?>" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">     
                                        <input name="ano_mov" value="{{ $ano_mov }}" class="form-control" type="hidden"> 
                                        <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                        <input name="id_establecimiento" value="{{ Auth::user()->id_establecimiento }}" class="form-control" type="hidden"> 
                                        <input name="id_origen" value="{{ $origen }}" class="form-control" id="id_origen" type="hidden"> 
                                        <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden">
                                        <input name="id" value="{{ $id }}" class="form-control" type="hidden"> 
                                    </div>                        
                                    <div class="col-md-3">
                                        <label>Fecha Salida</label>
                                        <input readonly name="fecha" value="<?php echo date("d-m-Y", strtotime($movimiento_historias->fecha)); ?>" id="fecha" class="form-control datepicker" required="" <?php echo $disabled ?>>
                                    </div>   
                                    <div class="col-md-6">
                                        <label>Responsable (Lugar Origen)</label>
                                        <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    </div>
                                </div>                    
                            </div>
                            <!------- Fila 02---------------------------------------> 
                            <div class="app-panel-title app-mt-4 app-mb-3"><strong>INFORMACIÓN DEL SERVICIO SOLICITANTE</strong></div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 etiqueta">
                                        <label>Servicio Solicitante</label>
                                        <?php $idLugar = $lugar; ?>
                                        <select class="form-control" required="required" name="id_consultorio" id="id_consultorio">
                                            <option value="">- Seleccione -</option>
                                            @foreach($consultorios as $dest)
                                            <?php $iddest= $dest->id;?>
                                            <option value="{{$dest->id}}" <?php if($idLugar == $iddest) {echo " selected";}?>>{{$dest->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 etiqueta">
                                        <label>Responsable (Lugar Destino)</label>
                                        <?php $idResponsable = $movimiento_historias->responsable_destino; ?>
                                        <select class="form-control" required="required" name="responsable_destino" id="responsable_destino">
                                            <option value="">- Seleccione -</option>
                                            @foreach($responsable_destinos as $resp)
                                                <?php $idRes = $resp->name.' '.$resp->paterno_usuario.' '.$resp->materno_usuario; ?>
                                                <option value="{{ $resp->name }} {{ $resp->paterno_usuario}} {{ $resp->materno_usuario}}"<?php if($idResponsable == $idRes) {echo " selected";}?>> {{ $resp->name }} {{ $resp->paterno_usuario}} {{ $resp->materno_usuario}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                <!------- Fila 03--------------------------------------->
                                <div class="row">                            
                                    <div class="col-md-3 etiqueta">
                                        <label>Area</label>
                                        <?php $idArea = $movimiento_historias->id_area; ?>
                                        <select id="id_area" name="id_area" class="form-control"  required="required">
                                            <option value="" selected style="color: #888888">Seleccionar uno</option>  
                                            <option value="1" <?php if($idArea == 1)echo "selected='selected'";?>>CONSULTORIO 1</option>
                                            <option value="2" <?php if($idArea == 2)echo "selected='selected'";?>>CONSULTORIO 2</option>
                                            <option value="3" <?php if($idArea == 3)echo "selected='selected'";?>>CONSULTORIO 3</option>   
                                        </select>
                                    </div>
                                    <div class="col-md-3 etiqueta">
                                        <label>Turno</label>
                                        <?php $idTurno = $movimiento_historias->id_turno; ?>
                                        <select id="id_turno" name="id_turno" class="form-control"  required="required">
                                            <option value="" selected style="color: #888888">Seleccionar uno</option>  
                                            <option value="1" <?php if($idTurno == 1)echo "selected='selected'";?>>MAÑANA</option>
                                            <option value="2" <?php if($idTurno == 2)echo "selected='selected'";?>>TARDE</option>
                                            <option value="3" <?php if($idTurno == 3)echo "selected='selected'";?>>NOCHE</option>
                                            <option value="4" <?php if($idTurno == 4)echo "selected='selected'";?>>MADRUGADA</option>    
                                        </select>
                                    </div>
                                    <div class="col-md-3 etiqueta">
                                        <label>Tipo de Citados</label>
                                        <?php $idTipoCitado = $movimiento_historias->id_tipo_citado; ?>
                                        <select id="id_tipo_citado" name="id_tipo_citado" class="form-control"  required="required">
                                            <option value="" selected style="color: #888888">Seleccionar uno</option>    
                                            <option value="1" <?php if($idTipoCitado == 1)echo "selected='selected'";?>>DIRECTA 1</option>
                                            <option value="2" <?php if($idTipoCitado == 2)echo "selected='selected'";?>>INDIRECTA 2</option>  
                                        </select>
                                    </div>
                                    <div class="col-md-3 etiqueta">
                                        <label>Tipo de Documento</label>
                                        <?php $idTipoDoc = $movimiento_historias->id_tipo_doc; ?>
                                        <select id="id_tipo_doc" name="id_tipo_doc" class="form-control"  required="required">
                                            <option value="" selected style="color: #888888">Seleccionar uno</option>    
                                            <option value="1" <?php if($idTipoDoc == 1)echo "selected='selected'";?>>TICKET</option>
                                            <option value="2" <?php if($idTipoDoc == 2)echo "selected='selected'";?>>NOTA</option>
                                            <option value="3" <?php if($idTipoDoc == 3)echo "selected='selected'";?>>OFICIO</option>  
                                        </select>
                                    </div>
                                    
                                </div>
                                <!------ Fila 05 --------------------------------------------->
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:10px">  
                                        <label>Observaci&oacute;n</label>
                                        <textarea id="observacion" name="observacion" class="form-control input-md" >{{$movimiento_historias->observacion}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!------- Fila 05--------------------------------------->
                            <div class="clearfix" style="margin-bottom:15px"></div>

                            <a href="/imprimir_historias_servicios/<?php echo $movimiento_historias->id ?>" class="btn btn-danger pull-right" target="_blank">
                                Imprimir<i class="fa fa-file-pdf-o"></i>
                            </a>
                            <div class="row">                        
                                <?php if ($movimiento_historias->estado == 1 || $movimiento_historias->estado==5 ): ?>
                                    <div class="col-md-3 pull-right">
                                        <button tabindex="13"  class="btn btn-success form-control" value="" type="submit" id="btnReg" onclick="validateFrm()">
                                            <i class="fa fa-save"></i> Actualizar
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-3 pull-right">
                                        <button tabindex="13" <?php echo $disabled ?>  class="btn btn-success form-control" value="" type="submit" id="btnReg">
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
</div>
@include('frontend.includes.footer')
@endsection