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
		
		$('#id_tipo_atencion').select2();

		$("#btnReg").on("click", function () {
			
			if ($("#id_tipo_atencion").val() == "") {
				bootbox.alert("Falta seleccionar el Tipo Atención Farmaceutico");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
				
    });
	
    $(document).ready(function () {
        $('#tipo_atencion_servicio_farmacia').dataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Filtrar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "ltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
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
                    <h3 class="panel-title">Servicio - {{$consultorio->nombre}} / Farmacia - {{$farmacia->nombre}} </h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_centro_atencion_servicio/{{ $id_servicio }}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Tipo Atención</h3>                    
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_tipo_atencion_farmacia_servicio')}}">                                
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">

                                    <div class="col-md-8">
                                        <label>Seleccionar el Tipo Atención</label>
                                        <select class="form-control" name="id_tipo_atencion" id="id_tipo_atencion">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($TipoAtencionFarmacia as $pro)
                                            <option value="<?php echo $pro->id_tipo_atencion ?>"><?php echo $pro->nombre_tipo_atencion ?></option>
                                            @endforeach
                                        </select>
                                        <input value="<?php echo $id_farmacia ?>" name="id_farmacia" id="id_farmacia" type="hidden">
                                    </div>                         

                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tipo Atenciones seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" id="tipo_atencion_servicio_farmacia" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                                                                        <!--<th>Anaquel</th>-->
                                            <th>Tipo Atención</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($TipoAtencionServicioFarmacia as $p)
                                        <tr>
                                                                                        <!--<td><?php //echo $p->anaquel ?></td>-->
                                            <td>{{$p->id}} - {{$p->nombre}}</td>
                                            <td><a class="btn btn-danger btn-xs" href="/eliminar_tipo_atencion_servicio_farmacia/{{$p->id}}">Eliminar</a></td>

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