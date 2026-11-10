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
                            url: '/desactivar_nivel_categoria/'+id+'/'+estado,
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
                            url: '/desactivar_nivel_categoria/'+id+'/'+estado,
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
                    <h3 class="panel-title">{{$descripcion_nivel}} - {{$descripcion_tipo}}</h3>
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
                                <h3 class="panel-title">Agregar Categoría en RUA</h3>
                            </div>
                            <div class="panel-body">
                                <form id="frm_transferencia" method="POST" action="{{ route('agregar_categoria_rua_atencion')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="nivel" id="nivel" value="<?php echo $nivel ?>">
                                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">Categoría</label>
                                        <div class="col-lg-6">
                                            <select class="form-control" name="id_categoria" id="id_categoria" required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($categorias as $f)
                                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-lg-1 control-label">Orden</label>
                                        <div class="col-lg-2">
                                            <input name="orden" id="orden" type="text" class="form-control" required="" style="text-align:right">
                                        </div>
                                        <div class="col-lg-2">
                                            <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
					<div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Establecimientos seleccionados</h3>  
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
                                        @foreach($categoria_laboratorios as $e)
                                        <tr>
                                            <td>{{$e->nombre}}</td>
                                            <td>{{$e->orden}}</td>
                                            <td class="text-center">
                                                    <a class="btn btn-primary" href="/add_rua_nivel/{{ $nivel }}/{{$tipo}}/{{$e->categoria}}"><i class="fa fa-medkit"></i></a>
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
@include('frontend.includes.footer')
@endsection