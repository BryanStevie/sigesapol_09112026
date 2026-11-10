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

    // function addInputIP() {
    //     var newRow = "";

    //     var ind = $('#table-ip tbody tr').length;

    //     if (ind > 0) {
    //         var flagx = 0;
    //         $('.consultorio').each(function() {
    //             if ($(this).val() == "") flagx++;
    //         });
    //         if (flagx > 0) {
    //             bootbox.alert("Hay campos de IP que se encuentran vacíos.");
    //             return false;
    //         }
    //     }
        
    //     newRow +='<tr>';
    //     newRow +='<td class="col-sm-6"><input type="text" class="form-control consultorio" name="input_ip[]" id="input_ip'+ ind +'" placeholder="000.000.000.000"></td>';
    //     newRow +='<td class="col-sm-3"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-arrow"></i> Opciones</button></td>';
    //     newRow +='<td class="col-sm-3"><button type="button" class="btn btn-danger deleteFila btn-sm"><i class="fa fa-times"></i> Eliminar</button></td>';
    //     newRow +='</tr>';

    //     $('#table-ip tbody').append(newRow);
    // }


    $(document).ready(function() {
        
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 
            
        var selectedValues = new Array();
        <?php 
            $n = 0;
            foreach ($opciones_elegidos as $rowConsul):
                ?>
                selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id?>";
                <?php
                $n++;
            endforeach;
        ?>
        $('.js-example-basic-multiple').val(selectedValues).trigger('change');

        // $('#addInputIP').on('click', function() {
        //     addInputIP();
        // });
        // $('#table-ip tbody').on('click', 'button.deleteFila', function() {
        //     var obj = $(this);
        //     obj.parent().parent().remove();
        // });

    });

    function validateFrm() {
        $('#btnReg').prop('disabled', true);

        // if ($('#frm').find("#cbo_establecimiento").val() == "") {
        //     bootbox.alert("Debe seleccionar un Establecimiento Médico.");
        //     $('#btnReg').prop('disabled', false);
        //     return false;
        // }

        // if ($('#frm').find("#descripcion").val() == "") {
        //     bootbox.alert("El nombre del Totem no puede estar en vacío.");
        //     $('#btnReg').prop('disabled', false);
        //     return false;
        // }

        if ($('#frm').find("#id_consultorio").val() == "") {
            bootbox.alert("Debe seleccionar un Servicio como mínimo.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        // var ind = $('#table-ip tbody tr').length;
        // if (ind > 0) {
        //     var fila_medicamentos = $('#table-ip tbody').children('tr');
        //     var fila_medicamento = "";
        //     var valmedentre = "";
        //     var nind = 0;
        //     for (var i = 0; i < ind; i++) {
        //         fila_medicamento = fila_medicamentos[i];
        //         valmedentre = $("#input_ip" + nind).val();
        //         if (valmedentre == "") {
        //             bootbox.alert("Hay campos de IP que se encuentran vacíos.");
        //             $('#btnReg').prop('disabled', false);
        //             return false;
        //         }
        //         nind++;
        //     }
        // } else {
        //     bootbox.alert("Debe ingresar alguna IP para continuar.");
        //     $('#btnReg').prop('disabled', false);
        //     return false;
        // }

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
                <h3 class="panel-title">Registrar opciones del totem</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('store_opciones_totem')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_dispositivo" value="{{$id_dispositivo}}">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="" name="" readonly value="{{$establecimiento->nombre}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Descripción Totem</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="" name="" readonly value="{{$totem->descripcion}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Servicios</label>
                        <div class="col-sm-10">
                            <?php $cant_servicios = count($servicios_elegidos) ?>
                            <?php $servicios = ''; ?>
                            @foreach($servicios_elegidos as $s)
                            <?php $servicios .= $s->nombre.', ';?>
                            {{-- @if($cant_servicios == count($servicios_elegidos))--}}

                            {{-- @endif --}}
                            @endforeach
                            <input type="text" class="form-control solomayusculas" id="" name="" readonly value="{{$servicios}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">N° IP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="descripcion" name="descripcion" readonly value="{{$ip}}">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection