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

    $(document).ready(function() {
        
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 
            
        var selectedValues = new Array();
        <?php 
            $n = 0;
            foreach ($servicios_elegidos as $rowConsul):
                ?>
                selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id?>";
                <?php
                $n++;
            endforeach;
        ?>
        $('.js-example-basic-multiple').val(selectedValues).trigger('change');

        var selectedValues2 = new Array();
        <?php 
            $n = 0;
            foreach ($opciones_elegidos as $rowConsul):
                ?>
                selectedValues2[<?php echo $n?>] = "<?php echo $rowConsul->id?>";
                <?php
                $n++;
            endforeach;
        ?>
        $('#opcion_totem').val(selectedValues2).trigger('change');

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
                    newOption = "<option value=''>--Seleccione--</option>";
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
                    newOption = "<option value=''>--Seleccione--</option>";
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
            bootbox.alert("El nombre del Totem no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#id_consultorio").val() == "") {
            bootbox.alert("Debe seleccionar un Servicio como mínimo.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#ip").val() == "") {
            bootbox.alert("El campo N° IP no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frm').find("#opcion_totem").val() == "") {
            bootbox.alert("Debe seleccionar una Opción como mínimo.");
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
                <h3 class="panel-title">Editar Totem</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_totem')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_totem" value="{{$id}}">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}" 
                                    @if($totem->id_establecimiento == $f->id) selected @endif >
                                    {{$f->codigo}} - {{$f->nombre}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Corredor</label>
                        <div class="col-sm-10">
                            <select name="id_corredor" id="id_corredor" class="form-control">
                                <option value="">--Seleccione--</option>
                                @foreach($corredores as $corredor)
                                <option value="{{$corredor->id}}" @if($corredor->id==$totem->id_corredor) selected @endif>{{$corredor->nombre}}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Descripción Totem</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="descripcion" name="descripcion" value="{{$totem->descripcion}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Servicios</label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-basic-multiple" name="id_consultorio[]" id="id_consultorio" required="" style="width: 100%" multiple="multiple">
                                @foreach($servicios as $f)
                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">N° IP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="ip" name="ip" value="{{$totem->ip}}" required placeholder="000.000.000.000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Opciones</label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-basic-multiple" name="opcion_totem[]" id="opcion_totem" required="" style="width: 100%" multiple="multiple">
                                @foreach($opciones_totem as $f)
                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-default" onclick="history.go(-1);">Cancelar</button>
                            <button id="btnReg" type="button" class="btn btn-primary pull-right" onclick="validateFrm()">Actualizar</button>
                        </div>
                        <!-- <div class="col-sm-offset-2 col-sm-4">
                            <button type="button" class="btn btn-default" onclick="history.go(-1);">Cancelar</button>
                        </div>
                        <div class="col-sm-offset-2 col-sm-4">
                            <button id="btnReg" type="button" class="btn btn-primary" onclick="validateFrm()">Actualizar</button>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection