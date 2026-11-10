@extends('frontend.layouts.master')
@section('renderjs')
<style>
.filterable {
    margin-top: 0px;
}

.filterable .panel-heading .pull-right {
    margin-top: -20px;
}

.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
}

.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
}

.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}

.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}
</style>

<script>
    
    function getModulos(){
        let id_servicio_ticket = $('#id_servicio_ticket').val();
        
        if (id_servicio_ticket != "") {
            
            //Limpiar los servicios ya seleccionados
            $('#id_ventanilla').html('').select2({data: [{id: '', text: '--Seleccione--'}]});

            $.ajax({
                url: '/modulos_por_servicio/'+id_servicio_ticket,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccione--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_ventanilla').html(newOption);
                    
                }
            });
        } else {
            $('#id_ventanilla').html('').select2({data: [{id: '', text: '--Seleccione--'}]});
        }
    }

    function getServicios() {
        var id_establecimiento = $('#cbo_establecimiento').val();
        if (id_establecimiento != "") {
            $('#id_corredor').html('').select2({data: [{id: '', text: '--Seleccione--'}]});
            $.ajax({
                url: '/get_corredores_por_establecimiento/' + id_establecimiento,
                success: function(result) {
                    console.log(result);
                    var newOption = "";
                    newOption = "<option value=''>--Seleccione--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_corredor').html(newOption);
                }
            });
        } else {
            $('#id_corredor').html('').select2({data: [{id: '', text: '--Seleccione--'}]});
        }

        if (id_establecimiento != "") {
            
            //Limpiar los servicios ya seleccionados
            $('#id_servicio_ticket').html('').select2({data: [{id: '', text: '--Seleccione--'}]});

            $.ajax({
                url: '/get_servicio_ticket',
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccione--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_servicio_ticket').html(newOption);
                    
                }
            });
        } else {
            $('#id_servicio_ticket').html('').select2({data: [{id: '', text: '--Seleccione--'}]});
        }

        if (id_establecimiento != "") {
            
            //Limpiar los servicios ya seleccionados
            $('#id_ventanilla').html('').select2({data: [{id: '', text: '--Seleccione--'}]});

        } else {
            $('#id_ventanilla').html('').select2({data: [{id: '', text: '--Seleccione--'}]});
        }
        
    }

    function validateFrm() {
        $('#btnReg').prop('disabled', true);

        if ($('#frm').find("#cbo_establecimiento").val() == "") {
            bootbox.alert("Debe seleccionar un Establecimiento Médico.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#id_corredor").val() == "") {
            bootbox.alert("Debe seleccionar un Corredor.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#id_servicio_ticket").val() == "") {
            bootbox.alert("Debe seleccionar un Servicio.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#id_ventanilla").val() == "") {
            bootbox.alert("Debe seleccionar un Módulo.");
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
                <h3 class="panel-title">Nuevo Corredor Detalle</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_corredores_detalle')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_tv" value="">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control" onchange="getServicios();" required>
                                <option value="">--Seleccione--</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Corredor</label>
                        <div class="col-sm-10">
                            <select name="id_corredor" id="id_corredor" class="form-control">
                                <option value="">--Seleccionar--</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Servicio de Tickets</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="id_servicio_ticket" id="id_servicio_ticket" onchange="getModulos()">
                                <option value="">--Seleccionar--</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Módulo</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="id_ventanilla" id="id_ventanilla" style="width:100%">
                                <option value="">-Seleccionar-</option>
                            </select>
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