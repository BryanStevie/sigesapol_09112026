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
		
		$('#id_procedimiento').select2();
		$('#id_proveedor').select2();

		$("#btnReg").on("click", function () {
			
			if ($("#id_procedimiento").val() == "") {
				bootbox.alert("Falta seleccionar el Procedimiento de Laboratorio");
				return false;
			}
			if ($("#id_proveedor").val() == "") {
				bootbox.alert("Falta seleccionar el Proveedor");
				return false;
			}
			if ($("#descripcion_corta").val() == "") {
				bootbox.alert("Falta ingresar la descripción corta");
				return false;
			}
			if ($("#codigo_personalizado").val() == "") {
				bootbox.alert("Falta ingresar el código personalizado");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
				
    });

    $(document).ready(function () {
        $('#examen_laboratorio').dataTable({
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

    $(document).on('change', '#id_procedimiento', function(e) {
        e.preventDefault();
        let selectedOption = $(this).find('option:selected');
        let codigo = selectedOption.data('codigo');
        $('#codigo_personalizado').val(codigo);
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
                    <h3 class="panel-title">RUA - {{$descripcion_tipo}} - {{$descripcion_nivel}} - {{$categoria->nombre}} </h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_categoria_rua2/{{ $id_consultorio }}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Agregar Examen de Laboratorio</h3>                    
                                </div>
                                <?php //exit();?>
                                <div class="panel-body">
                                    <form id="frm_transferencia" method="POST" action="{{ route('agregar_rua_nivel_tipo_atencion')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $tipo ?>">
                                        <input value="<?php echo $nivel ?>" name="id_nivel" id="id_nivel" type="hidden">
                                        <input value="<?php echo $id_categoria ?>" name="id_categoria" id="id_categoria" type="hidden">
                                        <input value="{{$id_consultorio}}" name="id_consultorio" id="id_consultorio" type="hidden">
                                        <input type="hidden" name="id_categoria_rua_nivel" value="{{ $id_categoria_rua_nivel }}">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Seleccionar el Exámen de Laboratorio</label>
                                                <select class="form-control" name="id_procedimiento" id="id_procedimiento">
                                                    <option value="">--Seleccionar--</option>
                                                    @foreach($listar_procedimientos_laboratorios as $pro)
                                                    <option value="<?php echo $pro->id ?>" data-codigo="{{ $pro->codigo }}"><?php echo $pro->codigo . " " . $pro->descripcion ?></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Seleccionar el Proveedor</label>
                                                <select class="form-control" name="id_proveedor" id="id_proveedor">
                                                    <option value="">--Seleccionar--</option>
                                                    @foreach($list_proveedores as $pro)
                                                    <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo_proveedor . " - " . $pro->nombre ?></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Descripción corta</label>
                                                <input name="descripcion_corta" id="descripcion_corta" type="text" class="form-control" required="" style="text-align:left">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Código Personalizado</label>
                                                <input name="codigo_personalizado" id="codigo_personalizado" type="text" class="form-control" required="" style="text-align:left">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Exámenes seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" id="examen_laboratorio" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>Examen</th>
                                            <th>Descripción Corta</th>
                                            <th>Código Personalizado</th>
                                            <th>Proveedor</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listar_examenes as $p)
                                        <tr>
                                            <td>{{$p->codigo}} - {{$p->descripcion}}</td>
                                            <td>{{$p->descripcion_corta}}</td>
                                            <td>{{$p->cpms_personalizado}}</td>
                                            <td>{{$p->descripcion_proveedor}}</td>
                                            <td><a class="btn btn-danger btn-xs" href="/eliminar_rua_nivel_tipo_atencion/{{$p->id}}/{{$tipo}}/{{$nivel}}/{{$id_categoria}}/{{$id_consultorio}}/{{ $id_categoria_rua_nivel }}">Eliminar</a></td>
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