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
    function addInputIP() {
        var newRow = "";

        var ind = $('#table-ip tbody tr').length;

        if (ind > 0) {
            var flagx = 0;
            $('.consultorio').each(function() {
                if ($(this).val() == "") flagx++;
            });
            if (flagx > 0) {
                bootbox.alert("Hay campos de IP que se encuentran vacíos.");
                return false;
            }
        }
        
        newRow +='<tr>';
        newRow +='<td class="col-sm-9"><input type="text" class="form-control consultorio" name="input_ip[]" id="input_ip'+ ind +'" placeholder="000.000.000.000"></td>';
        newRow +='<td class="col-sm-3"><button type="button" class="btn btn-danger deleteFila btn-sm"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow +='</tr>';

        $('#table-ip tbody').append(newRow);

    }

    $(document).ready(function() {
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 

        $('#addInputIP').on('click', function() {
            addInputIP();
        });

        $('#table-ip tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            obj.parent().parent().remove();
        });

        addInputIP();
    });

    function getServicios() {
        var id_establecimiento = $('#cbo_establecimiento').val();
        if (id_establecimiento != "") {

            //Limpiar los servicios ya seleccionados
            $('#id_consultorio').html('').select2({data: [{id: '', text: ''}]});

            $.ajax({
                url: '/get_consultorio_por_establecimiento/' + id_establecimiento,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>Seleccione</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_consultorio').html(newOption);
                }
            });
        } else {
            $('#id_consultorio').html('').select2({data: [{id: '', text: ''}]});
        }

        if (id_establecimiento != "") {
            $.ajax({
                url: '/get_corredores_por_establecimiento/' + id_establecimiento,
                success: function(result) {
                    console.log(result);
                    var newOption = "";
                    newOption = "<option value=''>Seleccione</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_corredor').html(newOption);
                }
            });
        } else {
            $('#id_corredor').html('').select2({data: [{id: '', text: ''}]});
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

        if ($('#frm').find("#descripcion").val() == "") {
            bootbox.alert("El nombre de la TV no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#id_consultorio").val() == "") {
            bootbox.alert("Debe seleccionar un Servicio como mínimo.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        var ind = $('#table-ip tbody tr').length;
        if (ind > 0) {
            var fila_medicamentos = $('#table-ip tbody').children('tr');
            var fila_medicamento = "";
            var valmedentre = "";
            var nind = 0;
            for (var i = 0; i < ind; i++) {
                fila_medicamento = fila_medicamentos[i];
                valmedentre = $("#input_ip" + nind).val();
                if (valmedentre == "") {
                    bootbox.alert("Hay campos de IP que se encuentran vacíos.");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
                nind++;
            }
        } else {
            bootbox.alert("Debe ingresar alguna IP para continuar.");
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
                <h3 class="panel-title">Nuevo Televisor</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_tv')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_tv">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control" onchange="getServicios();" required>
                                <option value="">Seleccione</option>
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
                                @foreach ($corredores as $c)
                                <option value="{{$c->id}}">{{$c->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Descripción TV</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="descripcion" name="descripcion" value="" required>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Estado</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="text_estado" value="ACTIVO" readonly="true">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Servicios</label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-basic-multiple" name="id_consultorio[]" id="id_consultorio" required="" style="width: 100%" multiple="multiple">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">N° IP</label>
                        <div class="col-sm-10">
                            <div class="row col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-condensed" id="table-ip">
                                        <thead>
                                            <tr>
                                                <th><button type="button" class="btn btn-sm btn-success" id="addInputIP" >Agregar Nueva IP</button></th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
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