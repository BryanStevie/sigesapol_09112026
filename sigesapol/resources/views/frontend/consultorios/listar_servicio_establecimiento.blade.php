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

        $("#id_establecimiento").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            let id_servicio = $('#id_servicio').val();
            let id_establecimiento = $('#id_establecimiento').val();
            if (parseInt(id_establecimiento) > 0) {
                $.ajax({
                    type: 'GET',
                    url: '/servicio_establecimiento/validar_existe/' + id_servicio + '/' + id_establecimiento,
                    dataType: "json",
                    success: function(result) {
                        if (result) {
                            bootbox.alert("El Establecimiento que desea agregar ya existe en este Servicio, si está como <b>No Activo</b> solo habilítelo.");
                        } else {
                            $("#frm_registrar_servicio_establecimiento").submit();
                        }
                    }
                });
            } else {
                bootbox.alert("Seleccionar un establecimiento para Agregar.");
            }

        });

    });
    
	function desactivar(id){
        bootbox.confirm("¿Se desactivará el Establecimiento para este servicio, desea continuar?", function(result) {
            if (result == true) {				
                $.ajax({
                    url: '/servicio_establecimiento/desactivar/' + id,
                    success: function(result) {
                        location.reload();
                    }
                });
            }
        });
	}

    function activar(id) {
        bootbox.confirm("¿Se activará el Establecimiento para este servicio, desea continuar?", function(result) {
            if (result == true) {				
                $.ajax({
                    url: '/servicio_establecimiento/activar/' + id,
                    success: function(result) {
                        location.reload();
                    }
                });
            }
        });
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
                    <h3 class="panel-title">Servicio - {{$consultorio->nombre}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_servicios">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
					<?php if ($existe == 1): ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Centro de Atención</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_registrar_servicio_establecimiento" method="POST" action="{{ route('servicio_establecimiento_store')}}" class="form-horizontal">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">IPRESS</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" name="id_establecimiento" id="id_establecimiento" required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($establecimientos as $f)
                                                <option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Cuenta con Guardia 24H</label>
                                        <div class="col-lg-10">
                                            <input type="checkbox" name="has_guardia" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-offset-10 col-md-2">
                                        <input type="button" id="btnReg" class="btn btn-success form-control" value="Agregar">
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
					<?php endif; ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Establecimientos seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>IPRESS</th>
                                            <th>Cuenta con Guardia 24H</th>
                                            <th>Estado</th>
                                            <?php if ($existe == 1): ?>
                                            <th class="text-center">Activar / Desactivar</th>
                                            <?php endif; ?>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($establecimientos_registrados as $e)
                                        <tr>
                                            <td>{{$e->nombre}}</td>
                                            <td>@if ($e->has_guardia == 1) SI @else NO @endif</td>
                                            <td>@if ($e->estado == 1) Activo @else No activo @endif</td>
											<?php if ($existe == 1): ?>
											<td class="text-center">
                                                @if ($e->estado == 1)
                                                    <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar({{ $e->id }})"><i class="fa fa-thumbs-up"></i></a>
                                                @elseif ($e->estado == 0)
                                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="activar({{ $e->id }})"><i class="fa fa-thumbs-down"></i></a>
                                                @endif
                                            </td>
											<?php endif; ?>
                                            <td>
                                                <a class="btn btn-success" href="{{ url('/') }}/servicio_establecimiento/editar/{{ $e->id }}"><i class="fa fa-edit"></i></a>
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