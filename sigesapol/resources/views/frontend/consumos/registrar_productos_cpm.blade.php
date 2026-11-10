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

            if ($("#cpm").val() == "") {
                bootbox.alert("Falta ingresar el consumo promedio mensual");
                return false;
            }
			$("#frmReceta").submit();
		});
				
    });

    $(document).ready(function () {
        $('#productos_cpm').dataTable({
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

<?php
    $pagRoute = "/famarcia_cpm/" . Auth::user()->id;
?>

<div class="container">
        <div class="col-md-3">        
        <div class="panel panel-warning">
            <div class="panel-heading">                
                Editar CPM
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Actualiza o Registra el CPM de un producto                            
                        </div>
                        <div class="col-md-2">
                            <a href="/add_producto_cpm" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-primary">
            <div class="panel-heading">
                Productos Criticos
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                            <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/1" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                            <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/4" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                            <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/2" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                            <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/3" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                            <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/5" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-info">
            <div class="panel-heading">                
                Nuevos Productos Ingresados
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Revise los productos que ingresaron en la Drogueria UE-020
                        </div>
                        <div class="col-md-2">
                            <a href="/nuevos_registros_sb" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>        
        <div class="panel panel-success">
            <div class="panel-heading">
                Prod. próximos a vencer 
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Relacion de Productos que estan por vencerse
                            <br> <span style="font-size:9px">(En los Proximos 06 meses)</span>
                        </div>
                        <div class="col-md-2">
                            <a href="/productos_proximos_vencer_cpm/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-danger">
            <div class="panel-heading">
                Productos Vencidos
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">                        
                        <div class="col-md-10" style="margin-top: 5px">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Relacion de Productos vencidos
                        </div>
                        <div class="col-md-2" style="margin-top: 5px">
                            <a href="/productos_vencidos_cpm/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                        </div> 
                        
                    </div>
                </div>                
            </div>
        </div>           
    </div>
        <div class="row col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"> Farmacia - {{$farmacia->nombre}} </h3>                    
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Producto</h3>                    
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_producto_cpm')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    

                                    <div class="col-md-6">
                                        <label>Seleccionar el Producto</label>
                                        <select class="form-control" name="id_producto" id="id_producto">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($productos as $pro)
                                            <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo . " " . $pro->nombre ?></option>
                                            @endforeach
                                        </select>

                                        <input value="<?php echo $id_farmacia ?>" name="id_farmacia" id="id_farmacia" type="hidden">
                                    </div> 
                                    <div class="col-md-2">
                                        <label>CPM</label>
                                        <input  type="text" class="form-control" name="cpm" value="" placeholder="CPM" required="" maxlength="8">
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
                                <table class="table" id="productos_cpm" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Producto</th>
                                            <th>Unidad</th>
                                            <th>Tipo</th>
                                            <th>CPM</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x=1;?>
                                        @foreach($productos_cpm as $p)
                                        <tr>
                                            <td>{{$x++}}</td>
                                            <td>{{$p->codigo}} - {{$p->nombre}}</td>
                                            <td>{{$p->unidad}}</td>
                                            <td>{{$p->tipo}}</td>
                                            <td>{{$p->cpm}}</td>
                                            <td><a class="btn btn-info btn-xs" href="/edit_producto_cpm/{{$p->id}}">Editar</a></td>
                                            <td><a class="btn btn-danger btn-xs" href="/eliminar_producto_consumo/{{$p->id}}">Eliminar</a></td>

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
@include('frontend.includes.footer')
@endsection