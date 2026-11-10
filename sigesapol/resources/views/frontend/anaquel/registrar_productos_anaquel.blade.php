@extends('frontend.layouts.master')
@section('renderjs')
<script>

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) )
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    $(document).ready(function () {
		
                $("#id_producto").select2({
                    width: 'resolve'
                });

		$("#btnReg").on("click", function () {
			
			if ($("#id_producto").val() == "") {
				bootbox.alert("Falta seleccionar el Producto Farmaceutico");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
				
    });

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
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Anaquel
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_anaquel">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                    </h3>      
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Producto</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_producto_anaquel')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_anaquel" id="id_anaquel" value="<?php echo $id_anaquel ?>">

                                    <div class="col-md-8">
                                        <label>Seleccionar el Producto</label>
                                        <select class="form-control" name="id_producto" id="id_producto" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($productos as $pro)
                                            <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo . " " . $pro->nombre ?></option>
                                            @endforeach
                                        </select>
                                        <input value="{{ Auth::user()->id_farmacia }}" name="id_farmacia" id="id_farmacia" type="hidden">
                                    </div>                         

                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <button type="submit" id="btnReg" class="btn btn-success form-control"><i class="fa fa fa-floppy-o"></i> Agregar</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Productos seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Anaquel</th>
                                                <th>Producto</th>
                                                <!--<th>Editar</th>-->
                                                <th>Quitar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $cont=1;?>
                                            @foreach($producto_anaquel as $p)
                                            <tr>
                                                <td class="text-center"><?php echo $cont++; ?></td>
                                                <td class="text-center"><?php echo $p->anaquel ?></td>
                                                <td>{{$p->codigo}} - {{$p->nombre}}</td>
                                                <td class="text-center"><a class="btn btn-danger btn-xs" href="/eliminar_producto_anaquel/{{$p->id}}"><i class="fa fa-remove"></i></a></td>
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
</div>
@include('frontend.includes.footer')
@endsection