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

        $("#id_categoria").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            if ($("#id_categoria").val() == "") {
                bootbox.alert("Falta seleccionar la Categoria");
                return false;
            }
            $("#frmReceta").submit();
        });
    });

    //-------------------------------------------------------------------------
    function desactivar(id, estado){
            if(estado == 0){
                    bootbox.confirm("¿Se activará el Nivel, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_nivel_categoria_rad/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará el Nivel, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_nivel_categoria_rad/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            }
	}

    $(document).on('click', '#btnColumnaReporte', function () {
        let tipo_atencion = $('#tipo').val();
        let id_consultorio = $('#id_consultorio').val();
        
        $.ajax({
            url: `/obtener_columnas_radiologia_consultorios/${tipo_atencion}/${id_consultorio}`,
            success: function (response) {
                if (response.reporte_columnas_rad === null || response.reporte_columnas_rad === '') {
                    $('#num_columnas').val('0');
                } else {
                    $('#num_columnas').val(response.reporte_columnas_rad);
                }
            }
        });
        $('#modalColumnasReporte').modal()
    });

    $(document).on('click', '#btnGuardarColumnasReporte', function () {
        let num_columnas = $('#num_columnas').val();
        let tipo_atencion = $('#tipo').val();
        let id_consultorio = $('#id_consultorio').val();
        
        if (num_columnas == 0) {
            Swal.fire({
            icon: 'error',
            title: 'Alerta',
            html: '<span>Debe seleccionar una opción</span>',
            showConfirmButton: true,
            });
            return;
        }
        
        $.ajax({
            url: "../../registrar_columna_reporte_radiologia",
            type: "POST",
			data : { num_columnas : num_columnas,  tipo_atencion : tipo_atencion, id_consultorio : id_consultorio},
            success: function (result) {  
				console.log(result);
                if (result == 'true') {
                    $('#modalColumnasReporte').modal('hide');
                    $('#num_columnas').val('0');
                    Swal.fire({
                        icon: 'success',
                        title: 'Se guardo con exito!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
        
    });

    $(document).ready(function() {
    $('#modalColumnasReporte').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        $('#num_columnas').val('0');
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
    .modal-dialog {
        width: 600px;
        margin: 200px auto !important;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$consultorio->nombre}}</h3>
                    
                    <input type="hidden" name="id_consultorio" id="id_consultorio" value="{{$consultorio->id}}">
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_rua">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Categoría en RAD</h3>
                            </div>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_categoria_radiologia_atencion')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="nivel" id="nivel" value="{{$nivel}}">
                                    <input type="hidden" name="tipo" id="tipo" value="{{$id_tipo_atencion}}">
                                    <input type="hidden" name="id_consultorio" id="id_consultorio" value="{{$consultorio->id}}">
                                    <input type="hidden" name="proviene" id="proviene" value="2">

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label" style="padding-top: 1rem;">Categoría</label>
                                        <div class="col-lg-4" style="padding-top: 1rem;">
                                            <select class="form-control" name="id_categoria" id="id_categoria" required="" placeholder="Seleccionar un Hospital" style="width: 100%;">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($categorias as $f)
                                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-lg-1 control-label" style="padding-top: 1rem;">Orden</label>
                                        <div class="col-lg-1" style="padding-top: 1rem;">
                                            <input name="orden" id="orden" type="text" class="form-control" required="" style="text-align:right">
                                        </div>
                                        <div class="col-lg-2" style="padding-top: 1rem;">
                                            <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                        </div>
                                        <div class="col-lg-2" style="padding-top: 1rem;">
                                            <a id="btnColumnaReporte" class="btn btn-danger"><i class="fa fa-columns" aria-hidden="true"></i>&nbsp;Columna PDF</a>
                                        </div>
                                        <div class="col-lg-1" style="padding-top: 1rem; ">
                                            @if($nivel==3)
                                                <a target="_blank" href="/ver_plantilla_rad/{{$nivel}}/{{$id_tipo_atencion}}/{{$consultorio->id}}" class="btn btn-danger form-control"><i class="fa fa-file-pdf-o"></i></a>
                                            @else
                                                <a target="_blank" href="/ver_plantilla_rad_nivel_1y2/{{$nivel}}/{{$id_tipo_atencion}}/{{$consultorio->id}}" class="btn btn-danger form-control"><i class="fa fa-file-pdf-o"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Categorias seleccionadas</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>Categorías</th>
                                            <th>Orden</th>
                                            <th>Agregar Productos</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categoria_radiologias as $e)
                                        <tr>
                                            <td>{{$e->nombre}}</td>
                                            <td>{{$e->orden}}</td>
                                            <td class="text-center">
                                                <?php if($e->estado == "1"){ ?>
                                                    <a class="btn btn-primary" href="/add_rad_nivel/{{ $nivel }}/{{$id_tipo_atencion}}/{{$e->categoria}}/{{$consultorio->id}}"><i class="fa fa-medkit"></i></a>
                                                <?php } else {?>
                                                    <a class="btn btn-primary" disabled><i class="fa fa-medkit"></i></a>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($e->estado == "1"){ ?>
                                                    <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->estado ?>)"><i class="fa fa-thumbs-up"></i></a>                                    
                                                <?php } else {?>
                                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->estado ?>)"><i class="fa fa-thumbs-down"></i></a>
                                                <?php }?>
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

{{-- VER SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS INICIO --}}
<div class="modal fade" id="modalColumnasReporte" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">AGREGAR O EDITAR COLUMNAS PARA EL REPORTE<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-4" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>N° de columnas</label>
                </div>
                <div class="col-sm-6">
                    <select id="num_columnas" name="num_columnas" class="form-control" style="width:100%;">
                        <option value="0">-Seleccione-</option>
                        <option value="1">1 columna</option>
                        <option value="2">2 columna</option>
                        <option value="3">3 columna</option>
                        <option value="4">4 columna</option>
                    </select>
                </div>  
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarColumnasReporte" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection