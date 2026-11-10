@extends('frontend.layouts.master')
@section('renderjs')
<script>

function desactivar(id, estado){
            if(estado == 3){
                    bootbox.confirm("¿Se activará, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_ipress_recurso_detalle/'+id,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_ipress_recurso_detalle/'+id,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            }
	}

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) )
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }



    $(document).ready(function () {
		
		$('#id_producto').select2();

		$("#btnReg").on("click", function () {
			
			if ($("#id_producto").val() == "") {
				bootbox.alert("Falta seleccionar el Producto Farmaceutico");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
			
    
        $('#fecha_desde, #fecha_hasta').change(function() {
        var fechaDesde = new Date($('#fecha_desde').val());
        var fechaHasta = new Date($('#fecha_hasta').val());
        
        if($('#fecha_desde').val() && $('#fecha_hasta').val()) {
            if(fechaHasta < fechaDesde) {
                alert('La fecha Hasta no puede ser menor que la fecha Desde');
                // Resetear el campo que se acaba de modificar
                $(this).val('');
            }
        }
    });

    // Validación al enviar el formulario
    $('form').submit(function(e) {
        var fechaDesde = new Date($('#fecha_desde').val());
        var fechaHasta = new Date($('#fecha_hasta').val());
        
        if($('#fecha_desde').val() && $('#fecha_hasta').val()) {
            if(fechaHasta < fechaDesde) {
                alert('Error: La fecha Hasta no puede ser menor que la fecha Desde');
                e.preventDefault(); // Detener el envío del formulario
                return false;
            }
        }
        return true;
    });
    
    });

    $(document).ready(function () {
        $('#producto_servicio').dataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ning�n dato disponible en esta tabla",
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
                    "sLast": "�ltimo",
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
                    <h3 class="panel-title">Recurso de Salud  </h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/listar_recursos_salud">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Recurso de Salud</h3>                    
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('recursos_salud.filtrar_por_fecha')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id_ipress_recuso_salud" value="{{ $id_ipress_recuso_salud }}">
                                <input type="hidden" name="id_ipress" value="{{ $id_ipress }}">
                                      <div class="pull-left" style="display: flex; gap: 10px; align-items: flex-end;">

                                        <div class="form-group">
                                            <label for="fecha_desde">Fecha Desde</label>
                                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="fecha_hasta">Fecha Hasta</label>
                                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                                        </div>

                                        <div class="form-group" style="margin-top: 25px;">
                                            <button type="submit" class="btn btn-dark">Buscar</button>
                                        </div>

                                    </div>
                                </form>
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('recursos_salud.create', [
                                            'id_ipress_recurso_salud' => $id_ipress_recuso_salud,
                                            'id_ipress' => $id_ipress
                                        ]) }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Generar Registro
                                        </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Listado de Recursos de Salud</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" id="producto_servicio" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>PERIODO</th>
                                            <th>FEC. REGISTRO</th>
                                            <th>RESP. REGISTRO</th>
                                            <th>ESTADO</th>
                                            <th>VER</th>
                                            <th>MODIFICAR</th>
                                            <th>ANULAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recurso_salud_detalle as $i => $reg)
                                        <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $reg->periodo }}</td>
                                                <td>{{ \Carbon\Carbon::parse($reg->fecha_creacion_recurso)->format('d/m/Y') }}</td>

                                                <td>{{ $reg->paterno }} {{ $reg->materno }}, {{ $reg->name }}</td>
                                                <td>
                                                    <span class="badge {{ $reg->estado == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $reg->estado == 1 ? 'Activo' : 'Eliminado' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('recursos_salud.show', [
                                                            'id' => $reg->id_ipress_recurso_salud_detalle
                                                        ]) }}" class="btn btn-success">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                                
                                                <td>
                                                    <a href="{{ route('recursos_salud.edit', [
                                                             'id' => $reg->id_ipress_recurso_salud_detalle
                                                        ]) }}" class="btn btn-success">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="btn btn-<?php echo ($reg->estado_rec == 1) ? 'danger' : 'success'; ?>" 
                                                        href="javascript:void(0)"  
                                                        onclick="desactivar(<?php echo $reg->id_ipress_recurso_salud_detalle ?>, <?php echo $reg->estado_rec ?>)">
                                                        <i class="fa fa-<?php echo ($reg->estado_rec == 1) ? 'times' : 'check'; ?>"></i>
                                                    </a>
                                                </td>

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