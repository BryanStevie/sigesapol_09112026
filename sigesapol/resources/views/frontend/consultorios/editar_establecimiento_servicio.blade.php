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

            let id_establecimiento = $('#id_establecimiento').val();
            if (parseInt(id_establecimiento) > 0) {
                // $.ajax({
                //     type: 'GET',
                //     url: '/servicio_establecimiento/validar_existe/' + id_establecimiento,
                //     dataType: "json",
                //     success: function(result) {
                //         if (result) {
                //             bootbox.alert("El Establecimiento que desea agregar ya existe, si está como <b>No Activo</b> solo habilítelo.");
                //         } else {
                            $("#frm_editar_servicio_establecimiento").submit();
                //         }
                //     }
                // });
            } else {
                bootbox.alert("Debe seleccionar un establecimiento.");
            }

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
                    <h3 class="panel-title">Servicio - {{$consultorio->nombre}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_servicios">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Editar Centro de Atención</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_editar_servicio_establecimiento" method="POST" action="{{ route('servicio_establecimiento_update')}}" class="form-horizontal">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio_establecimiento" value="{{ $servicio_establecimiento->id }}">

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">IPRESS</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" name="id_establecimiento" id="id_establecimiento" required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($establecimientos as $f)
                                                <option value="{{ $f->id }}" @if($servicio_establecimiento->id_establecimiento == $f->id) selected @endif>{{$f->codigo}} - {{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Cuenta con Guardia 24H</label>
                                        <div class="col-lg-10">
                                            <input type="checkbox" name="has_guardia" value="{{ $servicio_establecimiento->has_guardia }}" @if($servicio_establecimiento->has_guardia == 1) checked @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-10 col-md-2">
                                        <input type="button" id="btnReg" class="btn btn-success form-control" value="Guardar Cambios">
                                    </div>

                                </form>
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