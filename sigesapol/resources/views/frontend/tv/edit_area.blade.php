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

function AddFila() {

    var newRow = "";

    var ind = $('#example tbody tr').length;

    var item_servicio = "";
    $('#id_consultoriotemp option').each(function() {
        item_servicio += "<option value=" + $(this).val() + ">" + $(this).html() + "</option>";
    });
    newRow += '<tr>';
    newRow += '<td class="col-sm-9"><select class="form-control consultorio" id="consultorio' + ind +'" name="id_consultorio[]" style="width:100%" >' + item_servicio + '</select></td>';
    newRow += '<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    //alert(newRow);
    $('#example tbody').append(newRow);
    $("#consultorio" + ind).select2({
        max_selected_options: 4
    }).focus();

    var nind = 0;
    $("#consultorio" + ind).on("change", function(e) {
        var flagx = 0;
        cmb = $(this);
        id_consultorio = $("#consultorio" + ind).val();

        $('.consultorio').each(function() {
            var ind_tmp = $(this).val();
            if ($(this).val() == id_consultorio) flagx++;
        });

        if (flagx > 1) {
            bootbox.alert("El servicio de atención, ya ha sido seleccionado");
            $("#consultorio" + ind).val("").trigger("change");
            return false;
        }
    });
}

$(document).ready(function() {
    $(".js-example-basic-multiple").select2({
        width: 'resolve', // need to override the changed default
        max_selected_options: 4
    }); 
        
    var selectedValues = new Array();
    <?php 
        $n = 0;
        foreach ($subconsultorios_elegidos as $rowConsul):
            ?>
            selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id_sub_consultorio?>";
            <?php
            $n++;
        endforeach;
    ?>
    $('.js-example-basic-multiple').val(selectedValues).trigger('change')

    $('#addRow').on('click', function() {
        AddFila();
    });

    $('#example tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();
    });

//   AddFila();
});

// function select_prioridad() {select_establecimiento
//     $("#id_topico").val('');
//     $('#id_topico').trigger('change');

//     if ($("#id_prioridad").val() == "") {
//         $('#id_topico').attr('disabled', true);
//     } else {
//         $('#id_prioridad').attr('disabled', false);
//         getTopico();
//     }
// }

function getServicios() {
    var id_establecimiento = $('#cbo_establecimiento').val();
    if (id_establecimiento != "") {
        $.ajax({
            url: '/get_tv_por_establecimiento/' + id_establecimiento,
            success: function(result) {
                var newOption = "";
                newOption = "<option value=''>Seleccione</option>";
                $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                $('#cbo_televisor').html(newOption);
            }
        });
    }
}

function getSubConsultorios() {
    var id_televisor = $('#cbo_televisor').val();
    if (id_televisor != "") {
        var obj = $('#example tbody');
        obj.empty();

        $.ajax({
            url: '/get_subconsultorio_por_televisor/' + id_televisor,
            success: function(result) {
                var newOption = "";
                newOption = "<option value=''>Seleccione</option>";
                $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                });
                $('#id_consultoriotemp').html(newOption);
                AddFila();
            }
        });

    }
}


function eliminar(id, estado) {
    if (estado == 0) {
        bootbox.confirm("¿Desea activar el Televisor seleccionado?", function(result) {
            if (result == true) {
                return false;
                // $.ajax({
                //     url: '/desactivar_diagnostico/' + id + '/' + estado,
                //     success: function(result) {
                //         location.reload();
                //     }
                // });
            }
        });
    } else if (estado == 1) {
        bootbox.confirm("¿Desea desactivar el Televisor seleccionado?", function(result) {
            if (result == true) {
                return false;
                // $.ajax({
                //     url: '/desactivar_diagnostico/' + id + '/' + estado,
                //     success: function(result) {
                //         location.reload();
                //     }
                // });
            }
        });
    }
}
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Editar Area Televisor</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_tv_area')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_tv_area" value="{{$tv_area->id}}">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control" onchange="getServicios();">
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}" 
                                    @if($id_establecimiento == $f->id) selected @endif >
                                    {{$f->codigo}} - {{$f->nombre}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Televisor</label>
                        <div class="col-sm-10">
                            <select name="cbo_televisor" id="cbo_televisor" class="form-control" onchange="getSubConsultorios();">
                                <option value="">Seleccione</option>
                                @foreach($tvs as $f)
                                <option value="{{$f->id}}" 
                                    @if($tv_area->id_tv == $f->id) selected @endif >
                                    {{$f->nombre}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nombre Area</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="text_nombre_area" name="text_nombre_area" value="{{$tv_area->descripcion}}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Consultorios</label>
                        <div class="col-sm-10">

                            <!-- <div class="col-md-12" style="margin-top:10px"> -->
                            <!-- <label>Servicio de Atenci&oacute;n</label> -->
                            <select class="form-control js-example-basic-multiple" name="id_consultorio[]" required="" style="width: 100%" multiple="multiple">
                                @foreach($subconsultorios as $f)
                                <option value="{{$f->id}}">{{$f->descripcion}}</option>
                                @endforeach
                            </select>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="button" class="btn btn-default">Cancelar</button>
                        </div>
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-primary">Modificar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection