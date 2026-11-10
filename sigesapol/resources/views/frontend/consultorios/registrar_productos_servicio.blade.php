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
		
		$('#id_producto').select2();

		$("#btnReg").on("click", function () {
			
			if ($("#id_producto").val() == "") {
				bootbox.alert("Falta seleccionar el Producto Farmaceutico");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
				
    });

    $(document).ready(function () {
        $('#producto_servicio').dataTable({
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
                    "sLast": "Último",
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

    $(".js-example-basic-multiple").select2({
        width: 'resolve', 
        max_selected_options: 4
    });

    var id_producto_servicio = null;

    $(document).on('click','.btn-editar-anestesia',function(){

        let id = $(this).data('id');

        $('#btn-editar-anestesia-' + id).hide();
        $('#btn-cancelar-anestesia-' + id).show();

        let fila = $(this).closest('tr');
        $('#fila_editar_anestesia'+id).insertAfter(fila).show();

        let tipos = $(this).data('tipos');
        let ids = [];
        for(let i=0; i<tipos.length; i++){
            ids.push(tipos[i].id);
        }

        $('#id_tipo_anestesia'+id).val(ids).trigger('change');
    });
    
    $(document).on('click','.btn-cancelar-anestesia',function(){
       let id = $(this).data('id');

        $('#btn-cancelar-anestesia-' + id).hide();
        $('#btn-editar-anestesia-' + id).show();

        let fila = $(this).closest('tr');
        $('#fila_editar_anestesia'+id).insertAfter(fila).hide();
    });

    $(document).on('click','.btn-guardar-anestesia',function(){
        let id = $(this).data('id');
        let tipos = $('#id_tipo_anestesia'+id).val();
        
        let tipos_anestesias = $('#id_tipo_anestesia'+id+' option:selected').map(function() {
            return $(this).text().trim();
        }).get().join(', ');
        
        let producto = $(this).data('producto-descripcion');
        if(!tipos || tipos.length==0){
            Swal.fire('Seleccione al menos un tipo de anestesia','','warning');
            return;
        }
       
        Swal.fire({
            title: '¿Confirmar cambios?',
            html: 'Se actualizará los tipos de anestesia: <strong>'+tipos_anestesias+'</strong> para el producto <br><strong>'+producto+'</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url:'/guardar_tipo_anestesia_producto',
                    type:'POST',
                    data:{
                        _token:'{{csrf_token()}}',
                        id_producto_servicio:id,
                        id_tipo_anestesia:tipos
                    },
                    success:function(){
                        Swal.fire('Guardado','Tipos de anestesia actualizado','success');
                        location.reload();
                    }
                });

            }

        });
    });

    $(function () {
        $('[data-toggle="popover"]').popover({
            trigger: 'hover', // o 'click' si prefieres
            placement: 'top'
        });
    });
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
                        <a class="btn btn-default btn-xs" href="/add_centro_atencion_servicio/{{ $id_servicio }}/{{$tipo}}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Producto</h3>                    
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_producto_servicio')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">

                                    <div class="col-md-8">
                                        <label>Seleccionar el Producto</label>
                                        <select class="form-control" name="id_producto" id="id_producto">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($productos as $pro)
                                            <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo . " " . $pro->nombre ?></option>
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
                                <h3 class="panel-title">Productos seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" id="producto_servicio" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Eliminar</th>
                                            @if(isset($id_servicio) && $id_servicio == 72)
                                                <th>Tipo de anestesia</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($producto_servicio as $p)
                                        <tr>
                                            <td>{{$p->codigo}} - {{$p->nombre}}</td>
                                            <td><a class="btn btn-danger btn-xs" href="/eliminar_producto_en_servicio/{{$p->id}}">Eliminar</a></td>
                                            @if(isset($id_servicio) && $id_servicio == 72)
                                            <td>
                                                <div style="display:inline-flex; gap:6px; align-items:center;">
                                                    @if(count($p->tipos_anestesia))
                                                    
                                                        <?php 
                                                            $colores = ['#e74c3c', '#3498db', '#f1c40f', '#2ecc71', '#9b59b6', '#fd7e14'];
                                                            $content = '';
                                                            $i = 0;
                                                            foreach($p->tipos_anestesia as $tipo){
                                                                $color = $colores[$i % count($colores)];
                                                                $content .= '<div style="display:flex; align-items:center; width:100%; margin-bottom:4px;">';
                                                                $content .= '<span style="width:12px; height:12px; background-color:'.$color.'; border-radius:50%; display:inline-block; margin-right:8px;"></span>';
                                                                $content .= '<span style="color:black; white-space: nowrap;">'.$tipo['descripcion'].'</span>';
                                                                $content .= '</div>';
                                                                $i++;
                                                            }
                                                            $content = htmlspecialchars($content, ENT_QUOTES);
                                                        ?>
                                                        <button type="button" 
                                                                class="btn btn-info btn-xs btn-tipos-popover"
                                                                data-toggle="popover" 
                                                                data-html="true"
                                                                data-content='<?php echo $content; ?>'>
                                                            Ver Tipos
                                                        </button>
                                                    @endif
                                                    <button id="btn-editar-anestesia-{{$p->id}}"
                                                        class="btn btn-warning btn-xs btn-editar-anestesia"
                                                        data-id="{{$p->id}}"
                                                        data-id_producto_servicio="{{$p->id}}"
                                                        data-tipos='{{ htmlspecialchars(json_encode($p->tipos_anestesia), ENT_QUOTES, "UTF-8") }}'>
                                                        Editar
                                                    </button>
                                                </div>
                                            </td>
                                            @endif                                                  
                                        </tr>
                                        @if(isset($id_servicio) && $id_servicio == 72)
                                        <tr id="fila_editar_anestesia{{$p->id}}" style="display:none;background:#f9f9f9">
                                            <td colspan="3">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <strong>Tipo de anestesia</strong>
                                                        <select class="form-control js-example-basic-multiple"
                                                                id="id_tipo_anestesia{{$p->id}}"
                                                                name="id_tipo_anestesia[]"
                                                                multiple style="width:100%">
                                                            @foreach ($tipo_anestesia as $ta)
                                                                <option value="{{$ta->id}}">{{$ta->descripcion}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3" style="padding-left: 32px;">
                                                        <button data-id="{{$p->id}}"   data-producto-descripcion="{{$p->codigo}} - {{$p->nombre}}" class="btn btn-success btn-xs btn-guardar-anestesia">
                                                            ?? Guardar
                                                        </button>
                                                        
                                                       <button id="btn-cancelar-anestesia-{{$p->id}}"
                                                            class="btn btn-danger btn-xs btn-cancelar-anestesia"
                                                            data-id="{{$p->id}}"
                                                            style="display:none;">
                                                        &times; Cancelar
                                                    </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
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