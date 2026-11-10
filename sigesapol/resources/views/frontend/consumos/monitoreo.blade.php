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
    <div class="row col-md-12">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">
                    STOCK LOCAL DE CENTRO DE ATENCIÓN
                    <?php 
                    if ($situacion == "1"){
                        echo " - DESABASTECIDO";
                    } else if ($situacion == "2"){
                        echo " - SOBRESTOCK";
                    } else if ($situacion == "3"){
                        echo " - NORMOSTOCK";
                    } else if ($situacion == "4"){
                        echo " - SUBSTOCK";
                    } else if ($situacion == "5"){
                        echo " - SIN ROTACIÓN";
                    } 

                    if($nivel==1){
                        echo " - NIVEL I";
                    } else if ($nivel == "2"){
                         echo " - NIVEL II";
                    } else if ($situacion == "3"){
                        echo " - NIVEL III";                    
                    } 

                    ?>

                </h3>
                <div class="pull-right">
                    @if(count($resultado)>0)                                        
                    <?php if (isset($farmaciaActual) && $farmaciaActual == 2): ?>
                        <a class="btn btn-success btn-xs" href="/imprimir_stock_byidfarmacia/{{ $id_farmacia }}/{{ $situacion }}"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    <?php else: ?>
                        <a class="btn btn-success btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}/{{ $situacion }}"><i class="fa fa-file-excel-o"></i> Exportar </a> 
                    <?php endif; ?>          
                    @endif
                    @if(count($resultado)==0)
                        <a class="btn btn-default btn-xs" href="/from_registrar_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}"><i class="fa fa-plus"></i> Agregar Stock Inicial</a>
                        @if($estado_carga==0)
                            <a class="btn btn-default btn-xs" href="/formulario_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-list"></i> Importar Stock Inicial</a>
                        @endif
                        <a class="btn btn-default btn-xs" href="/formato_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-download"></i> Formato-importar</a>
                    @endif
                    <?php 
                    if ($situacion <> "0"){
                        ?>
                     <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                    <?php
                    }
                    ?>
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
                            <th>Situación</th>
                            <th>Ver Lotes</th>                            
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

                        if ($r['cantidad'] > 0 && ($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $cpma = -1; //-1
                        } else {
                            if ($r['nro_mes'] == 0) {
                                $cpma = -1;
                            } else {
                                if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                    $cpma = 0;
                                } else {
                                        $cpma = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                }
                            }
                        }
                        if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $meses_provicion = 0;
                        } else {
                            $provision = round(($r['cantidad'] / $cpma), 1);
                            $meses_provicion = $provision;
                        }

                        $color = "";
                        $situacionTemp = "";
                        if ($r['cantidad'] <= 0):
                            $color = "#FF0000"; //rojo
                            $situacionTemp = 1;
                        else:
                            if ($cpma <= 0):
                                $color = "#4472C4"; //Sin Rotacion Azul
                                $situacionTemp = 5;
                            endif;
                            if ($meses_provicion >= 0 && $meses_provicion < 2):
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
                                <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                    <?php //echo $r['egresos']."|".$r['recetas_devueltas']."|".$cpma;  ?>
                                    <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                                    </i>
                                    <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                                </td>
                                <td>                                    
                                    <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php elseif ($situacion > 0 && $situacion == $situacionTemp): ?>
                            {{-- */$x++;/* --}}
                            <tr>
                                @if($meses_provicion<=1)
                                    <td><p  style="color:red">{{$x}}</p></td>
                                    <td><p  style="color:red">{{$r['codigo']}}</p></td>
                                    <td><p  style="color:red">{{$r['nombre']}}</p> </td>
                                    <td><p  style="color:red">{{$r['unidad']}}</p></td>
                                    <td><p  style="color:red">{{$r['cantidad']}}</p></td>
                                    <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                        <i class="fa fa-flag fa-2x" style="color:<?php echo 'red'; ?>;position:relative">
                                        </i>
                                        <div style="color:<?php echo 'red'; ?>;float:right;margin-right:0%;font-size:11px"><p  style="color:red">{{ $meses_provicion }} meses</p></div>
                                    </td>
                                    <td>
                                        <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                @else
                                    <td>{{$x}}</td>
                                    <td>{{$r['codigo']}}</td>
                                    <td>{{$r['nombre']}} </td>
                                    <td>{{$r['unidad']}}</td>
                                    <td>{{$r['cantidad']}}</td>
                                    <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                                        <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                                        </i>
                                        <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                                    </td>
                                    <td>
                                        <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
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
