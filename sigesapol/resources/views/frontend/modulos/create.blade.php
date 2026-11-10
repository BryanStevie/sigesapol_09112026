@extends('frontend.layouts.master')
@section('renderjs')

<script>

    $(document).ready(function() {
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 
    });

    function getServicios() {
        let id_servicio = $('#id_servicio').val();
        
        if (id_servicio != "") {
            
            //Limpiar los servicios ya seleccionados
            $('#nombre_modulo').val('');
        } else {
            $('#nombre_modulo').val('');
        }
        
    }

    function validateFrm() {
        $('#btnReg').prop('disabled', true);

        if ($('#frm').find("#id_servicio").val() == "") {
            bootbox.alert("Debe seleccionar un Servicio.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#nombre_modulo").val() == "") {
            bootbox.alert("El nombre del Módulo no puede estar en vacío.");
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
                <h3 class="panel-title">Nuevo Módulo</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_modulo')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_modulo" value="">
                    <div class="form-group">
                        <label for="id_servicio" class="col-sm-2 control-label">Servicio</label>
                        <div class="col-sm-10">
                            <select name="id_servicio" id="id_servicio" class="form-control js-example-basic-multiple" onchange="getServicios();" required>
                                <option value="">Seleccione</option>
                                @foreach($ticket_servicios as $ts)
                                <option value="{{$ts->id}}">{{$ts->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre_modulo" class="col-sm-2 control-label">Nombre del Módulo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="nombre_modulo" name="nombre_modulo" value="" required>
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