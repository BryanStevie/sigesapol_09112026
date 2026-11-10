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
                <h3 class="panel-title">Nuevo Area Televisor</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_tv_area')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_tv_area">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control" onchange="getServicios();">
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Televisor</label>
                        <div class="col-sm-10">
                            <select name="cbo_televisor" id="cbo_televisor" class="form-control" onchange="getSubConsultorios();">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nombre Area</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="text_nombre_area" name="text_nombre_area">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Estado</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="text_estado" value="ACTIVO" readonly="true">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Consultorios</label>
                        <div class="col-sm-10">
                            <select name="cbo_sub_consultorio" id="cbo_sub_consultorio" class="form-control">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Consultorios</label>
                        <div class="col-sm-10">
                            
                            <div id="entregar_receta" class="row col-sm-12" style="padding-top:10px; padding-bottom:0px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar Servicio(s)</button>
                                <div style="display: none;">
                                    <select class="form-control" name="id_consultoriotemp" id="id_consultoriotemp">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>Descripción</th>
                                        <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tfoot></tfoot>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="button" class="btn btn-default">Cancelar</button>
                        </div>
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection