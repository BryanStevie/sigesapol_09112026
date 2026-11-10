@extends('frontend.layouts.master')
@section('renderjs')

<script>

    $(document).ready(function() {
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 
    });

    function validateFrm() {
        $('#btnReg').prop('disabled', true);

        if ($('#frm').find("#id_establecimiento").val() == "") {
            bootbox.alert("Debe seleccionar un Establecimiento Médico.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#nombre").val() == "") {
            bootbox.alert("El nombre del Corredor no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        submitFrm();
        return false;
    }

    function submitFrm() {
        bootbox.confirm({
            message: "¿Desea registrar los datos ingresados?.",
            className: 'title-dialog',
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result == true) {
                    $("#frm").submit();
                } else {
                    $('#btnReg').prop('disabled', false);
                }
            }
        });
    }

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Editar Corredor</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('editar_corredor')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_corredor" value="{{$corredor->id}}">
                    <div class="form-group">
                        <label for="id_establecimiento" class="col-sm-2 control-label">Establecimiento</label>
                        <div class="col-sm-10">
                            <select name="id_establecimiento" id="id_establecimiento" class="form-control js-example-basic-multiple" required>
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}" @if($corredor->id_establecimiento==$f->id)selected @endif>{{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-sm-2 control-label">Nombre del Corredor</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="nombre" name="nombre" value="{{$corredor->nombre}}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-default" onclick="history.go(-1);">Cancelar</button>
                            <button id="btnReg" type="button" class="btn btn-primary pull-right" onclick="validateFrm()">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection