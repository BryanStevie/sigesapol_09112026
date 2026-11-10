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

    $(document).ready(function () {

        $("#id_establecimiento_recurso").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            if ($("#id_producto").val() == "") {
                bootbox.alert("Falta seleccionar el Producto Farmaceutico");
                return false;
            }

            $("#frmReceta").submit();

        });

    });

    function getFarmacia() {

        var id_establecimiento = $('#id_establecimiento_recurso').val();
        $.ajax({
            url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
            success: function (result) {
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                $('#id_farmacia').html(newOption);
                $("#id_farmacia").select2({max_selected_options: 4});

            }
        });

    }

    //-------------------------------------------------------------------------
    function cargarData(data) {
        $(data).each(function (ii, oo) {
            $("#frm_transferencia").find("#lote").val(oo.lote);
            $("#frm_transferencia").find("#codigo").val(oo.codigo_producto);
            $("#frm_transferencia").find("#producto").val(oo.prod_farmaceutico);
            $("#frm_transferencia").find("#registro_sanitario").val(oo.registro_sanitario);
            $("#frm_transferencia").find("#fecha_vencimiento").val(oo.fecha_vencimiento);
            $("#frm_transferencia").find("#cantidad_actual").val(oo.cantidad);
            $("#frm_transferencia").find("#id_producto").val(oo.id_producto);
            $("#frm_transferencia").find("#id_stock_farmacia").val(oo.id_stock_farmacia);
        });
    }
    //-------------------------------------------------------------------------
    
	function desactivar(id, estado){
            if(estado == 3){
                    bootbox.confirm("¿Se activará el IPRESS, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_ipress_recursos_salud/'+id,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará la IPRESS, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_ipress_recursos_salud/'+id,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            }
	}
    
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">RECURSOS DE SALUD</h3>
                    <div class="pull-right">
                        <!--a class="btn btn-default btn-xs" href="/all_servicios">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a-->
                    </div>
                </div>
                <div class="panel-body">
					<?php if ($existe == 1): ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar IPRESS</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_ipress_recurso')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    

                                    <div class="form-group col-md-8">
                                        <label class="col-lg-2 control-label">IPRESS</label>
                                        <div class="col-lg-7">
                                            <select class="form-control" name="id_establecimiento_recurso" id="id_establecimiento_recurso" onchange="getFarmacia()" required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($establecimientos as $f)
                                                <option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group col-md-2">
                                            <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                        </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
					<?php endif; ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista Establecimientos seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>Establecimiento</th>
                                            <th class="text-center">Agregar Recursos</th>

                                            <?php if ($existe == 1): ?>
                                            <th class="text-center">Estado</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($consultorio_servicio as $e)
                                        <tr>
                                            <td >{{$e->nombre}}</td>
                                            
											<?php //if ($existe == 1): ?>
											<?php //endif; ?>
                                            
                                            <td class="text-center">
                                                    <a class="btn btn-primary" href="/add_ipress_recurso_detalle/{{$e->id_ipress_recurso_salud}}/{{$e->id_ipress}}"><i class="fa fa-medkit"></i></a>
                                            </td>
                                            
											<?php if ($existe == 1): ?>
											<td class="text-center">
                                                <?php if($e->estado == "1"){ ?>
                                                    <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id_ipress_recurso_salud ?>,<?php echo $e->estado ?>)"><i class="fa fa-thumbs-up"></i></a>                                    
                                                <?php } else {?>
                                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id_ipress_recurso_salud ?>,<?php echo $e->estado ?>)"><i class="fa fa-thumbs-down"></i></a>
                                                <?php }?>
                                            </td>
											<?php endif; ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection