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
    #global {
        height: 600px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

	.panel{
		margin-bottom:0px
	}
	
</style>

<script>
    $(document).ready(function () {
        $("#id_consultorio").select2({
            width: 'resolve' // need to override the changed default
        });
        $("#id_consultorio").trigger('change');
        
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
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
    });
    $(document).ready(function () {
        $('#example').dataTable( {
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Filtrar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
    });
</script>
<style>
</style>
@endsection
@section('content')
<?php
    
    $pagRoute = "/famarcia_cpm/" . Auth::user()->id;
    
?>
<div class="container">
    <div class="col-md-3">        
        <div class="panel panel-warning">
            <div class="panel-heading">                
                Editar CPM
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Actualiza o Registra el CPM de un producto                            
                        </div>
                        <div class="col-md-2">
                            <a href="/add_producto_cpm" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-primary">
            <div class="panel-heading">
                Productos Criticos
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                            <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/1" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                            <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/4" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                            <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/2" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                            <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/3" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                            <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pagRoute ?>/5" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-info">
            <div class="panel-heading">                
                Nuevos Productos Ingresados
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Revise los productos que ingresaron en la Drogueria UE-020
                        </div>
                        <div class="col-md-2">
                            <a href="/nuevos_registros_sb" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>        
        <div class="panel panel-success">
            <div class="panel-heading">
                Prod. próximos a vencer 
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="col-md-10">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Relacion de Productos que estan por vencerse
                            <br> <span style="font-size:9px">(En los Proximos 06 meses)</span>
                        </div>
                        <div class="col-md-2">
                            <a href="/productos_proximos_vencer_cpm/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <br/><br/>
        <div class="panel panel-danger">
            <div class="panel-heading">
                Productos Vencidos
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">                        
                        <div class="col-md-10" style="margin-top: 5px">
                            <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Relacion de Productos vencidos
                        </div>
                        <div class="col-md-2" style="margin-top: 5px">
                            <a href="/productos_vencidos_cpm/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                        </div> 
                        
                    </div>
                </div>                
            </div>
        </div>           
    </div>
    
    <div class="row col-md-9">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">
                    PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS  
                     <?php 
                    if ($situacion == "1"){
                        echo " - DESABASTECIDO";
                    } else if ($situacion == "2"){
                        echo " - SOBRESTOCK";
                    } else if ($situacion == "3"){
                        echo " - NORMOSTOCK";
                    } else if ($situacion == "4"){
                        echo " - PRODUCTOS CRITICOS";
                    } else if ($situacion == "5"){
                        echo " - SIN ROTACIÓN";
                    } 
                    ?>                   
                </h3>
                <div class="pull-right">                                   
                    @if(count($resultado)>0)
                        <a class="btn btn-success btn-xs" href="/imprimir_stock_bycpmfarmacia/{{ $id_farmacia }}/{{ $situacion }}"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    @endif
                </div>
            </div>
            <div id="global" style="background:#fff !important">
                <br/>
                <table id="example" class="table margenes" style="font-size: 11px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>
                            <th>Código</th>
                            <th>Producto Farmacéutico o Dispositivo Médico</th>
                            <th>Unidad</th>
                            <th>Stock Actual</th>
                            <th>CPM</th>
                            <th>Situación</th>                            
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px !important;">
                        <?php //print_r($resultado); ?>
                        {{-- */$x=0;/* --}}
                        @foreach($resultado as $r)

                        <?php
                        $cpm = "";
                        $cpma = "";
                        $meses_provicion = "";

                        if (($r['cpm']) == 0) {
                            $meses_provicion = $r['cantidad'];
                        } else {
                            $provision = round(($r['cantidad'] / $r['cpm']), 1);
                            $meses_provicion = $provision;
                        }

                        
                        $color = "";
                        $situacionTemp = "";
                       
                        if ($meses_provicion > 0):
                            if ($r['cpm']== 0):
                                $color = "#4472C4"; //Sin Rotacion Azul
                                $situacionTemp = 5;
                            else:
                                if ($meses_provicion < 2):
                                    $color = "#FFC000"; //SubStock amarillo
                                    $situacionTemp = 4;
                                endif;
                                if ($meses_provicion >= 2 && $meses_provicion <= 6):
                                    $color = "#70AD47"; //NormoStock verde
                                    $situacionTemp = 3;
                                endif;
                                 if ($meses_provicion > 6):
                                    $color = "#ED7D31"; //SobreStock anaranjado
                                    $situacionTemp = 2;
                                endif;
                            endif;
                        else:
                            if ($r['cantidad'] <= 0):
                                $color = "#FF0000"; //rojo
                                $situacionTemp = 1;
                            endif;
                        endif;

                        ?>

                        <?php if ($situacion == 0): ?>
                            {{-- */$x++;/* --}}
                            <tr>
                                <td>{{$x}}</td>
                                <td>{{$r['codigo']}}</td>
                                <td>{{$r['nombre']}} </td>
                                <td>{{$r['unidad']}}</td>
                                <td>
                                    {{$r['cantidad']}}
                                </td>
                                <td>{{$r['cpm']}}</td>                                
                                <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                    <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                                    </i>
                                    <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                                </td>                                
                            </tr>
                        <?php elseif ($situacion > 0 && $situacion == $situacionTemp): ?>
                            {{-- */$x++;/* --}}
                            <tr>
                                <td>{{$x}}</td>
                                @if($r['cantidad']<$r['cpm'])
                                    @if($situacion == 4)
                                        <td><p  style="color:red">{{$r['codigo']}}</p></td>
                                        <td><p  style="color:red">{{$r['nombre']}}</p> </td>
                                        <td><p  style="color:red">{{$r['unidad']}}</p></td>
                                        <td><p  style="color:red">{{$r['cantidad']}}</p></td>
                                        <td><p  style="color:red">{{$r['cpm']}}</p></td>
                                        <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                            <i class="fa fa-flag fa-2x" style="color:red;position:relative">
                                            </i>
                                            <div style="color:red;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>                                
                                        </td>
                                    @else
                                        @if($situacion == 1)
                                            <td><p  style="color:red">{{$r['codigo']}}</p></td>
                                            <td><p  style="color:red">{{$r['nombre']}}</p> </td>
                                            <td><p  style="color:red">{{$r['unidad']}}</p></td>
                                            <td><p  style="color:red">{{$r['cantidad']}}</p></td>
                                            <td><p  style="color:red">{{$r['cpm']}}</p></td>
                                            <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                                <i class="fa fa-flag fa-2x" style="color:red;position:relative">
                                                </i>
                                                <div style="color:red;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>                                
                                            </td>
                                        @else
                                            <td><p>{{$r['codigo']}}</p></td>
                                            <td><p>{{$r['nombre']}}</p> </td>
                                            <td><p>{{$r['unidad']}}</p></td>
                                            <td><p>{{$r['cantidad']}}</p></td>
                                            <td><p>{{$r['cpm']}}</p></td>
                                            <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                                <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                                                </i>
                                                <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                                            </td>
                                        @endif                                        
                                    @endif
                                @else
                                    @if($situacion == 1)
                                            <td><p  style="color:red">{{$r['codigo']}}</p></td>
                                            <td><p  style="color:red">{{$r['nombre']}}</p> </td>
                                            <td><p  style="color:red">{{$r['unidad']}}</p></td>
                                            <td><p  style="color:red">{{$r['cantidad']}}</p></td>
                                            <td><p  style="color:red">{{$r['cpm']}}</p></td>
                                            <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                                <i class="fa fa-flag fa-2x" style="color:red;position:relative">
                                                </i>
                                                <div style="color:red;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>                                
                                            </td>
                                    @else
                                            <td><p>{{$r['codigo']}}</p></td>
                                            <td><p>{{$r['nombre']}}</p> </td>
                                            <td><p>{{$r['unidad']}}</p></td>
                                            <td><p>{{$r['cantidad']}}</p></td>
                                            <td><p>{{$r['cpm']}}</p></td>
                                            <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                                <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                                                </i>
                                                <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                                            </td>
                                    @endif
                                @endif
                            </tr>
                        <?php endif; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
