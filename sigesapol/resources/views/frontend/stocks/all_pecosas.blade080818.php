@extends('frontend.layouts.master')
@section('renderjs')

<style>
    .filterable {
        margin-top: 15px;
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
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)// || key == 45)
        }
        
    /*
     Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
     */
    $(document).ready(function () {
    $("#id_requerimiento").select2();
        
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No result found</td></tr>'));
            }
        });
        
        $('#fecha_entrada').click(function () {
            var fechaVencimientoIni = $('#fecha_entrada').val();
            $('#fecha_entrada').change(function () {
                var fechaVencimiento = $('#fecha_entrada').val();
                f1 = fechaVencimiento.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0,59));
                
                if (f1 > f3) {
                    bootbox.alert("La Fecha de Pecosa no debe ser mayor a la Fecha Actual");
                    
                    $('#fecha_entrada').val(fechaVencimientoIni);
                    return false;
                }
            });
        });
        $('#fecha_entrada').trigger('change');
        
    });
    
    $("#btnReg").on("click", function () {               
        var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1; //hoy es 0!
        var yyyy = hoy.getFullYear();        
        if(dd<10) {
            dd='0'+dd
        } 
        if(mm<10) {
            mm='0'+mm
        } 
        hoy = dd+'-'+mm+'-'+yyyy;        
        fecha_entrada=$('#frmPecosa').find("#fecha_entrada").val();                        
        var xMonth=fecha_entrada.substring(3, 5);                 
        var xDay=fecha_entrada.substring(0, 2);  
        var xYear=fecha_entrada.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha solo puede estar comprendida entre hoy y 30 días atrás");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha solo puede estar comprendida entre hoy y 30 días atrás");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha solo puede estar comprendida entre hoy y 30 días atrás");
                            return false;  
                        }
                    }
                }  
            }
        }
			
            if ($('#frmPecosa').find("#numero").val() == "") {
                alert("Falta digitar el número de pecosa");
                return false;
            }
			
            if ($('#frmPecosa').find("#fecha_entrada").val() == "") {
                alert("Falta Seleccionar la Fecha");
                return false;
            } 
        
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <form class="form-horizontal" method="post" action="{{ route('add_pecosa')}}" id="frmPecosa">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Recepcionar Pecosa
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-3">   
                                <label>Requerimiento</label>
                                <select class="form-control" id="id_requerimiento" name="id_requerimiento">
                                    <option value="">Seleccionar Requerimiento</option> 
                                    @foreach($requerimientopen as $pf)
                                    <option value="{{$pf->id_reque}}">{{$pf->nro_reque}}-{{$pf->anio_reque}}</option>
                                    @endforeach
                                </select>                
                            </div>
                        <div class="col-md-3">
                            <label>Nro. Pecosa</label>
                            <input id="numero" name="numero" type="text" placeholder="Nro. Pecosa" class="form-control input-md" onKeyPress="return soloNumeros(event)" autocomplete="off" maxlength="9">
                        </div>
                        <div class="col-md-2">
                            <label>Año Pecosa</label>
                            <select class="form-control" id="ano" name="ano">
                                @foreach($anos as $a)
                                <option value="{{$a->ano}}">{{$a->ano}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input id="fecha_entrada" name="fecha_entrada" type="text" placeholder="Fecha" class="form-control input-md datepicker" >                     
                            <input hidden="" name="id_farmacia" value="{{ Auth::user()->id_farmacia}}">
                            <input hidden="" name="id_user" value="{{ Auth::user()->id}}">
                        </div>
                        <div class="col-md-11" style="padding-top: 15px;">
                            @if($count>0)
                            <span style="font-size: 11px"><i class="fa fa-warning" style="color:red"></i> No se puede ingresar productos, tienes de pecosa(s) pendiente(s).</span>
                            @else
                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                <input type="submit" class="btn btn-success pull-right" value="Recepcionar Pecosa" id="btnReg">                     
                            <?php endif; ?>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <div class="row">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Pecosas</h3>
                </div>
                <div class="panel-body">
                    <legend>{{$farmacia->nombre}}</legend>
                    <div style="margin-top: 20px">
                        <table class="table">
                            <thead>
                                <tr class="filters">
                                    <th>N°</th>
                                    <th>Nro. Pecosa</th>
                                    <th>Nro. Requerimiento</th>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                    <th>Fecha</th>
                                    <th>Recepcionar</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; ?>
                                @foreach($pecosas as $p)
                                <tr>                            
                                    <th><?php $n = $n + 1;
                                echo $n;
                                ?></th>
                                    <td>{{$p->numero}}-{{$p->ano_nota}}</td>
                                    <td class="text-center">{{$p->nro_reque}}-{{$p->anio_reque}}</td>
                                    <td>Almacen Central</td>
                                    <td>{{$farmacia->nombre}}</td>
                                    <td>
                                        <?php
                                        $originalDate2 = $p->fecha_entrada;
                                        $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                        echo $newDate2;
                                        ?> 
                                    </td>
                                    @if($p->fecha_entrada>'2016-12-20')
                                        @if($p->estado==1)
                                            <td>
                                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a class="btn btn-success btn-xs"  href="/recepcionar_productos_pecosa/{{$p->id}}/{{ Auth::user()->id}}">
                                                        <i class="glyphicon glyphicon-list-alt"></i> Por recepcionar
                                                    </a>
                                                <?php if($permiso->eliminar == 1):?>
                                                    <a class="btn btn-danger btn-xs"  href="/eliminar_pecosa/{{$p->id}}/{{ Auth::user()->id_farmacia}}">
                                                        <i class="fa fa-warning"></i> Eliminar Pecosa
                                                    </a>
                                            <?php endif; endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($permiso->crear == 1 && $permiso->modificar == 1): ?>
                                                <button type="button" class="btn btn-success btn-sm" disabled=""><i class="fa fa-edit"></i></button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-success btn-sm" disabled=""><i class="fa fa-edit"></i></button>
                                                <?php endif; ?>
                                            </td>
                                        @else
                                            <td>
                                                <a class="btn btn-default btn-xs"  href="/recepcionar_productos_pecosa/{{$p->id}}/{{ Auth::user()->id}}">
                                                    <i class="fa fa-check"></i> Ver pecosa recepcionada
                                                </a>                                        
                                            </td>                                        
                                            <td class="text-center">
                                                <button type="button" class="btn btn-success btn-sm" disabled=""><i class="fa fa-edit"></i></button>
                                            </td>
                                        @endif
                                        @else
                                        <td>
                                            <a class="btn btn-default btn-xs"  href="/pecosa_anterior_kardex/{{$p->numero}}/{{ Auth::user()->id_farmacia}}/{{$p->fecha_entrada}}">
                                                <i class="fa fa-check"></i> Ver pecosa recepcionada
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
