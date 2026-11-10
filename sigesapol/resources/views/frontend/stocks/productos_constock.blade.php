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
        height: 580px !important;
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

</style>

<script>
    $(document).ready(function () {
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
        $('#example').DataTable();
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                Stocks - {{$farma->nombre}}  
            </div>            
            <div class="panel-body">   
                @if($minstock<=100)
                <!--<div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <strong><i class="fa fa-warning"></i> Alerta!</strong> <marquee SCROLLAMOUNT=2 direction="up" Behavior = slide><p style="font-family: arial; font-size: 10pt">Existen Productos que pronto van agotarse</p></marquee>
                </div>-->
                @endif
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-flag" style="color:red"></i> Sin Stock ({{$sinstock}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_sinstock/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-flag" style="color:green"></i> Stock Regular ({{$constock}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_constock/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-flag" style="color:#FFC106"></i> Stock Mínimo ({{$minstock}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_minstock/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
                
                <div class="col-md-10">
                    <i class="fa fa-medkit" style="color: orange"></i> Stock por Lotes
                </div>
                <div class="col-md-2">
                    <a href="/productos_porlotes/" class="btn btn-default btn-xs">ver</a>
                </div>                                                
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Clasificación de Stock por Tipo de Producto 
            </div>            
            <div class="panel-body" style="font-size:11px">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Medicamentos ({{$countMedicamento}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/1" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Biomédicos ({{$countbiomedico}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_por_tipo/{{ $id_farmacia }}/3" class="btn btn-default btn-xs">ver</a>
                </div>                
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material Dental ({{$countMaterialDental}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/5" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material de Laboratorio ({{$countMaterialLaboratorio}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_por_tipo/{{ $id_farmacia }}/4" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Insumos de Laboratorio ({{$countinsumolaboratorio}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_por_tipo/{{ $id_farmacia }}/2" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Fotográfico y Fonot. ({{$countMatFotograficoAndFonotecnico}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_por_tipo/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Quirurgico ({{$countMaterialQuirurgico }})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/7" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos Afines ({{$countProductosAfines}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/8" class="btn btn-default btn-xs">ver</a>
                </div>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Alertas de Vencimiento 
            </div>            
            <div class="panel-body" style="font-size:11px">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Prod. próximos a vencer ({{$count_proximos_vencer}})
                    <br> <span style="font-size:9px">(En los Proximos 06 meses)</span>
                </div>
                <div class="col-md-2">
                    <a href="/productos_proximos_vencer/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                     <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos vencidos ({{$count_productos_vencidos}})
                </div>
                <div class="col-md-2">
                     <a href="/productos_vencidos/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>                
            </div>
        </div>
    </div>
    <!--<h4>
        Stocks - {{$farma->nombre}}         
        <span class="pull-right">Hoy: {{$fecha}}</span>
    </h4>-->
    <div class="row col-md-9">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">PRODUCTOS CON STOCK (Total: {{$constock}})</h3>
                <div class="pull-right">
                    <!----------------------------------------------------------------------------------------------------------------------------->
                    @if($existe_stock>0)                                        
                        @role('Almacenero_recetas')
                        <a class="btn btn-default btn-xs" href="/form_generar_rerporte_ici/{{ Auth::user()->id }}"><i class="fa fa-search"></i> Reporte ICI</a>
                        <!--<a class="btn btn-default btn-xs" href="/modulo_ingresos"><i class="fa fa-plus-square" style="color:green"></i> Ingresar Productos</a>-->
                        <!--<a class="btn btn-default btn-xs" href="/seleccion_requerimiento/{{ Auth::user()->id }}"><i class="fa fa-plus-square" style="color:green"></i> Ingresos Regulares</a>-->                                        
                        <!--<a class="btn btn-default btn-xs" href="/formulario_add_juntamedica/{{ Auth::user()->id }}"><i class="fa fa-plus-square" style="color:green"></i> Ingresos J. Médica</a>-->
                        <!--<a class="btn btn-default btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}"><i class="fa fa-print"></i> Imprimir stock</a> -->
                        @endauth
                        @role('Almacenero_consolidados')
                        <a class="btn btn-default btn-xs" href="/form_generar_rerporte_ici/{{ Auth::user()->id }}"><i class="fa fa-search"></i> Reporte ICI</a> 
                        <a class="btn btn-default btn-xs" href="/modulo_ingresos"><i class="fa fa-plus-square" style="color:green"></i> Ingresar Productos</a>
                        <!--<a class="btn btn-default btn-xs" href="/ver_requerimiento/{{ Auth::user()->id }}"><i class="fa fa-plus-square" style="color:green"></i> Ingresos Regulares</a>                                        
                        <a class="btn btn-default btn-xs" href="/formulario_add_juntamedica/{{ Auth::user()->id }}"><i class="fa fa-plus-square" style="color:green"></i> Ingresos J. Médica</a>-->
                        <a class="btn btn-default btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}"><i class="fa fa-print"></i> Imprimir stock</a> 
                        @endauth
                        @role('Dispensador_recetas')
                        <a class="btn btn-default btn-xs" href="/stocksbyFamarcia/{{ Auth::user()->id }}"><i class="fa fa-medkit"></i> Stock en General</a>
                        <!--<a class="btn btn-default btn-xs" href="/resumen_diario/{{ Auth::user()->id }}"><i class="fa fa-minus-square" style="color:red"></i> Recetas</a>-->
                        <!--<a class="btn btn-default btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}"><i class="fa fa-print"></i> Imprimir stock</a>  -->
                        @endauth
                        @role('Dispensador_consolidados')
                        <a class="btn btn-default btn-xs" href="/stocksbyFamarcia/{{ Auth::user()->id }}">Stock actual</a>                    
                        <a class="btn btn-default btn-xs" href="/formulario_salida_diaria/{{ Auth::user()->id }}"><i class="fa fa-minus-square" style="color:red"></i> Salidas Diarias</a>
                        <a class="btn btn-default btn-xs" href="/formulario_consumo_juntamedica/{{ Auth::user()->id }}"><i class="fa fa-minus-square" style="color:red"></i> Salidas J. Médica</a>
                        <a class="btn btn-default btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}"><i class="fa fa-print"></i> Imprimir stock</a>  
                        @endauth                                                         
                        <!--<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>-->
                    @endif
                    <!----------------------------------------------------------------------------------------------------------------------------->
                    @if($existe_stock==0)
                        @role('Almacenero_consolidados')
                        <a class="btn btn-default btn-xs" href="/formulario_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-list"></i> Importar</a>
                        <a class="btn btn-default btn-xs" href="/formato_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-download"></i> Formato para importar</a>
                        @endauth
                        @role('Almacenero_recetas')
                        <a class="btn btn-default btn-xs" href="/formulario_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-list"></i> Importar</a>
                        <a class="btn btn-default btn-xs" href="/formato_importar_stock/{{ Auth::user()->id }}"><i class="fa fa-download"></i> Formato para importar</a>
                        @endauth
                    @endif 
                    <!----------------------------------------------------------------------------------------------------------------------------->
                    <a class="btn btn-default btn-xs" href="/imprimir_constock_byidfarmacia/{{ $id_farmacia }}"><i class="fa fa-print"></i> Imprimir</a> 
                </div>
            </div>
            <div id="global">
                @if(count($stocks)>0)
                <table id="example" class="table " style="font-size: 11px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>
                            <th>Código</th>
                            <th>Producto Farmacéutico o Dispositivo Médico</th>
                            <th>Unidad</th>
                            <th>Stock Actual</th>
                            <th>Situación</th>
                            <th>Ver Lote(s)</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px !important;">
                        {{-- */$x=0;/* --}}
                        @foreach($stocks as $item)
                        {{-- */$x++;/* --}}
                        <tr>
                            <td>{{ $x }}</td>
                            <td>{{ $item->codigo_producto }}</a></td>
                            <td>{{ $item->prod_farmaceutico }}</td>
                            <td>{{ $item->abreviatura }}</td>
                            <td>{{ $item->cantidad }}</td>
                            @if($item->cantidad==0)
                            <td>
                                <i class="fa fa-flag fa-2x" style="color:red"></i>
                            </td>
                            @else
                            @if($item->cantidad<=100)
                            <td>                                   
                                <i class="fa fa-flag fa-2x" style="color:#FFC106"></i>
                            </td>
                            @else
                            <td>                                   
                                <i class="fa fa-flag fa-2x" style="color:green"></i>
                            </td>
                            @endif
                            @endif
                            <td>
                                <a href="/asignar_lote_vencimiento/{{$item->id}}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else               
                <div class="col-sm-12 col-md-12" style="margin-top:15px">
                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-info-sign"></span> <strong>Aviso</strong>
                        <hr class="message-inner-separator">
                        <p>No existe registros de productos agotados...</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
