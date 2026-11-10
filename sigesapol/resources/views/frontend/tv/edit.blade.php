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

.obligatorio{
  color:red;
}
.borde-alerta {
  border-color: red !important;
  border: 2px red solid !important;
  outline: 0;
  border-radius: 4px;
  box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important; 
}
.sub_consultorios_registrados tr td {
    font-size: 1.2rem !important;
}
</style>

<script>

    function eliminar(id, estado) {
        if (estado == 0) {
            bootbox.confirm("¿Desea activar el Televisor seleccionado?", function(result) {
                if (result == true) {
                    return false;
                }
            });
        } else if (estado == 1) {
            bootbox.confirm("¿Desea desactivar el Televisor seleccionado?", function(result) {
                if (result == true) {
                    return false;
                }
            });
        }
    }

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
        newRow +='<td class="col-sm-9"><input type="text" class="form-control consultorio" name="input_ip[]" id="input_ip'+ ind +'" onkeypress="return soloIP(event, \'input_ip' + ind + '\');" placeholder="000.000.000.000"></td>';
        newRow +='<td class="col-sm-3"><button type="button" class="btn btn-danger deleteFila btn-sm"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow +='</tr>';

        $('#table-ip tbody').append(newRow);
    }


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

        $('#addInputIP').on('click', function() {
            addInputIP();
        });
        $('#table-ip tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            obj.parent().parent().remove();
        });

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

    $(document).on('click', '#agregarTvSubConsultorio', function () {
        agregarTvSubConsultorio();
    });

    const agregarTvSubConsultorio = () => {
        let id_tv = $('#id_tv').val();
        let id_sub_consultorio = $('#id_sub_consultorio').val();
        console.log({
            'id_tv' : id_tv,
            'id_sub_consultorio' : id_sub_consultorio,
        });
        if (id_sub_consultorio == null) {
            console.log('Debe seleccionar el consultorio');
            return;
        }
        $.ajax({
            url: `/registrar_sub_consultorio_para_tv/${id_tv}/${id_sub_consultorio}`,
            success: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Se guardo con exito!',
                    showConfirmButton: false,
                    timer: 2000,
                    onClose: function() {
                        console.log(result);
                        location.reload();
                    }
                });
            }
        });
    }

    $(document).on('change', '#id_servicio', function () {
        getSubConsultorio();
    });

    const getSubConsultorio = () => {
        let id_establecimiento = $('#cbo_establecimiento').val();
        let id_servicio = $('#id_servicio').val();
        let id_tv = $('#id_tv').val();
        if (id_servicio != "") {
            $.ajax({
                url: `/obtener_sub_consultorio_para_tv/${id_establecimiento}/${id_servicio}/${id_tv}`,
                success: function(result) {
                    let newOption = "";
                    newOption = "<option value=''>--Seleccione--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_sub_consultorio').html(newOption);
                    
                }
            });
        } else {
            $('#id_sub_consultorio').html('').select2({data: [{id: '', text: ''}]});
        }
    }

    $(document).on('click', '.deleteFilaTvSubConsultorio', function () {
        let id_tv_sub_consultorio = $(this).data('id-tv-sub-consultorio');
        console.log(id_tv_sub_consultorio);
        $.ajax({
            url: `/delete_tv_sub_consultorio/${id_tv_sub_consultorio}`,
            success: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Se guardo con exito!',
                    showConfirmButton: false,
                    timer: 2000,
                    onClose: function() {
                        console.log(result);
                        location.reload();
                    }
                });
            }
        });
    });

    $(document).ready(function () {
        $('#table_sub_consultorios_registrados').DataTable({
        "language": {
        "emptyTable": "No se encontraron resultados"
        }
        });
    });

</script>
<script src='../../funciones/function.js'></script> 
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Editar Televisor</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('registrar_tv')}}" id="frm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_tv" id="id_tv" value="{{$id}}">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Establecimientos</label>
                        <div class="col-sm-10">
                            <select name="cbo_establecimiento" id="cbo_establecimiento" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $f)
                                <option value="{{$f->id}}" 
                                    @if($tv->id_establecimiento == $f->id) selected @endif >
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
                                <option value="{{$corredor->id}}" @if($corredor->id==$tv->id_corredor) selected @endif>{{$corredor->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Totem</label>
                        <div class="col-sm-10">
                            <select name="id_totem" id="id_totem" class="form-control">
                                <option value="">--Seleccione--</option>
                                @foreach($totems as $totem)
                                <option value="{{$totem->id}}" @if($totem->id==$tv->id_totem) selected @endif>{{$totem->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Descripción TV</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control solomayusculas" id="descripcion" name="descripcion" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'descripcion');" value="{{$tv->descripcion}}">
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
                        <label for="inputEmail3" class="col-sm-2 control-label">Agregar Sub Consultorios</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="id_servicio" id="id_servicio">
                                <option value="">-Seleccionar-</option>
                                @foreach($servicios_elegidos as $f)
                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="id_sub_consultorio" id="id_sub_consultorio">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-success btn-sm" id="agregarTvSubConsultorio"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Sub Consultorios</label>
                        <div class="col-sm-10" id="tv_sub_consultorio">
                            <table class="table table-sm table-condensed sub_consultorios_registrados" id="table_sub_consultorios_registrados">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>SERVICIO</th>
                                        <th>CONSULTORIO</th>
                                        <th>&nbsp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n=1; ?>
                                    @foreach ($tv_sub_consultorios as $tvsc )    
                                        <tr>
                                            <td class="col-sm-1" style="font-weight: 700;">{{$n}}</td>
                                            <td class="col-sm-2">{{$tvsc->nombre_consultorio}}</td>
                                            <td class="col-sm-7">{{$tvsc->nombre_sub_consultorio}}</td>
                                            <td class="col-sm-2"><button type="button" class="btn btn-danger deleteFilaTvSubConsultorio btn-sm" data-id-tv-sub-consultorio="{{$tvsc->id}}"><i class="fa fa-times"></i> Eliminar</button></td>
                                        </tr>
                                    <?php $n++; ?>
                                    @endforeach
                                </tbody>
                            </table>
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
                                        <tbody>
                                            @for ($i = 0; $i < count($lista_ips); $i++)
                                            <tr>
                                                <td class="col-sm-9"><input type="text" class="form-control" name="input_ip[]" id="input_ip{{$i}}" onkeypress="return soloIP(event,'input_ip{{$i}}');" value="{{ $lista_ips[$i]->ip }}"></td>
                                                <td class="col-sm-3"><button type="button" class="btn btn-danger deleteFila btn-sm"><i class="fa fa-times"></i> Eliminar</button></td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
