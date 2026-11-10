<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    /*
#global {        
    width: 95%;        
    margin: 15px 15px 15px 15px;     
    height: 380px !important;        
    border: 1px solid #ddd;
    overflow-y: scroll !important;
}
    */
    #global {
        /*min-height: 650px !important;*/
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px;
        overflow-x: scroll !important;
        /*overflow-y: scroll !important;*/
        /* background: #f1f1f1;*/
            
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }

    .dataTables_filter {
        display: none;
    }
    
    .help-block {
        display: block;
        margin-top: 1px;
        margin-bottom: 10px;
        color: #737373;
    }

</style>

<script>

    //--------------------------------------------------------------------------
    $(document).ready(function () {

    $('#id_producto').select2();
    $('#l_establecimiento').select2();
    $('#l_dependencia').select2();
    $('#id_tipo_producto').select2();
    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });
    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });
    $('#fechaDesde').click(function () {
    var fechaDesdeIni = $('#fechaDesde').val();
    $('#fechaDesde').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f1 > f2) {
    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
    $('#fechaDesde').val(fechaDesdeIni);
    return false;
    }
    });
    });
    $('#fechaHasta').click(function () {
    var fechaHastaIni = $('#fechaHasta').val();
    $('#fechaHasta').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f2 < f1) {
    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }

    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    });
    });
    $('#fechaHasta').trigger('change');
    $('#fechaDesde').trigger('change');
    var activeSystemClass = $('.list-group-item.active');
    });
    $(document).ready(function () {
        $('#example').DataTable({
        /*"searching": false*/
        "language": {
        "emptyTable": "No se encontraron resultados"
        }
        });
        $("#system-search").keyup(function() {
        var dataTable = $('#example').dataTable();
        dataTable.fnFilter(this.value);
        });
        $("#id_productoid_producto").select2({
        width: 'resolve' // need to override the changed default
        });
        $("#id_producto").trigger('change');
        });

    $('#id_producto').val('<?php echo $id_producto; ?>').trigger('change.select2');    

    //$('#tipo_proceso').val(result[0].tipo_proceso).trigger('change.select2');
    function reporteInternamiento(tipo){

        var fechaDesde = $('#fecha_inicio').val();
        var fechaHasta = $('#fecha_fin').val();
        var id_producto = $('#id_producto').val();
        

        if (fechaDesde == "")fechaDesde = 0;
        if (fechaHasta == "")fechaHasta = 0;
        if (id_producto == "")id_producto = 0;
        
        
        location.href = 'exportar_ordenes_ingresadas_producto_farmacia/' + fechaDesde + '/' + fechaHasta + '/' + id_producto + '/' + '<?php echo Auth::user()->id ?>' ;
        
    
    }

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }


</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Movimiento de Productos</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('buscar_movimiento_producto')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                    
                    <div class="row">
                        <div class="col-md-4">
                            <label class="help-block">Producto</label>
                            <select class="form-control" name="id_producto" id="id_producto">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($productosnivel as $pro) {
                                    echo '<option value="' . $pro->id . '"';
                                    if (isset($id_producto)) {
                                        if ($id_producto == $pro->id)
                                            echo " selected";
                                    }
                                    echo '>' . $pro->codigo . ' - ' . $pro->nombre . '</option>';
                                }
                                ?>                                
                            </select>
                        </div>
                        
                        <div class="col-sm-2">
                            <label class="help-block">Fecha Desde</label>
                            <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required=""  value="{{$fecha_inicio}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10">
                        </div>
                        <div class="col-sm-2">
                            <label class="help-block">Fecha Hasta</label>
                            <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_fin}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                            
                        </div>
                        <div class="col-md-2">
                            <br/><br/>
                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                        </div>
                        <div class="col-md-1">
                            <br/><br/>
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteInternamiento(1)" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                            </a>
                        </div>               
                    </div>
                    <div class="row">
                        <input value="{{ Auth::user()->id}}" name="id_user" class="form-control" type="hidden">
                    </div>
                </form>
            </div> 
        </div>   
    </div>
    
    <div class="panel panel-primary" >        
        <div class="panel-body">    
            <div class="col-md-12">      
                <?php $n2 = 0; $cantidad_total2=0; $total2=0;$subtotal2=0;$canttotal=0;?>          
            @if($id_producto!='')
                @if(count($ingresos_transferencias)>0)
                    <table class="table table-hover" style="font-size: 14px !important;" >
                        <thead>
                            <tr>                            
                                <th style="background: #3c763d;" ><p style="color:white;">N</p></th>                            
                                <th style="background: #3c763d;" ><p style="color:white;">Numero</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Ingreso</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Motivo</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">Origen</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Lote</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">R.Sanitario</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Vence</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Cantidad</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Precio</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Total</p></th>                                                     
                            </tr>
                        </thead>
                        <tbody>                   
                            <?php $n2 = 0; ?>
                            @foreach($ingresos_transferencias as $r)
                            <?php 
                                if($r->pc!=0)
                                    $descripcion =  $r->nombre_producto.' ('.$r->cantidad.' X '.$r->presentacion_comercial.') ';
                                else
                                    $descripcion =  $r->nombre_producto;
                            ?>
                            <tr>
                                <td><?php
                                    $n2++; echo $n2; ?>                            
                                </td>
                                <td><?php
                                    echo $r->nro_acta; ?>                            
                                </td>
                                <td>
                                    <?php
                                    $originalDate = $r->fecha_salida;
                                    $fecha = date("d-m-Y", strtotime($originalDate));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td>{{$r->motivo}}</td>
                                
                                <td><?php 
                                        switch($r->tipo ){
                                            case 1: echo $r->lugar_origen_farmacia;   break;
                                            case 3: echo $r->lugar_origen_farmacia;  break;
                                        }                                      
                                    ?>
                                </td>
                                <td>{{$r->lote}}</td>
                                <td>{{$r->registro_sanitario}}</td>
                                <td>
                                    <?php
                                    $originalDate = $r->fecha_vencimiento;
                                    $fechav = date("d-m-Y", strtotime($originalDate));
                                    echo $fechav;
                                    ?> 
                                </td>                
                                <td>{{$r->cantidad*$r->presentacion_comercial}}</td>
                                <td>{{$r->p_unitario}}</td>                            
                                <td><?php $subtotal2=$r->cantidad*$r->p_unitario*$r->presentacion_comercial; 
                                          $total2 += $subtotal2;
                                          $canttotal+=$r->cantidad*$r->presentacion_comercial;
                                          echo $subtotal2;
                                ?></td>                            
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8"><strong>TOTAL</strong></td>
                                <td><b><?php echo $canttotal; ?></b></td>
                                <td></td>
                                <td><b><?php echo number_format($total2, 8, '.', ''); ?></b></td>                            
                            </tr>
                        </tfoot>                    
                    </table>   
                @else
                    No hay datos de ingresos registrados
                @endif                      
            @endif 
            </div>
        </div>
    </div> 
    <div class="panel panel-primary" >        
        <div class="panel-body">    
            <div class="col-md-12">      
                <?php $n2 = 0; ?>          
            @if($id_producto!='')
                @if(count($ingresos_nota_entradas)>0)
                    <table class="table table-hover" style="font-size: 14px !important;" >
                        <thead>
                            <tr>                            
                                <th style="background: #3c763d;" ><p style="color:white;">N</p></th>                            
                                <th style="background: #3c763d;" ><p style="color:white;">Numero</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Ingreso</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Motivo</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">Origen</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Lote</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">R.Sanitario</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Vence</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Cantidad</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Precio</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Total</p></th>                                                     
                            </tr>
                        </thead>
                        <tbody>                   
                            <?php $n2 = 0; ?>
                            @foreach($ingresos_nota_entradas as $r)
                            <?php 
                                if($r->pc!=0)
                                    $descripcion =  $r->nombre_producto.' ('.$r->cantidad.' X '.$r->presentacion_comercial.') ';
                                else
                                    $descripcion =  $r->nombre_producto;
                            ?>
                            <tr>
                                <td><?php
                                    $n2++; echo $n2; ?>                            
                                </td>
                                <td><?php
                                    echo $r->numero; ?>                            
                                </td>
                                <td>
                                    <?php
                                    $originalDate = $r->fecha_entrada;
                                    $fecha = date("d-m-Y", strtotime($originalDate));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td>{{$r->motivo}}</td>
                                
                                <td><?php 
                                        switch($r->tipo ){
                                            case 2: echo $r->lugar_origen_consultorio;   break;
                                            case 5: echo $r->lugar_origen_ipress;  break;
                                            case 4: echo $r->lugar_origen_dep; break;
                                        }                                      
                                    ?>
                                </td>
                                <td>{{$r->lote}}</td>
                                <td>{{$r->registro_sanitario}}</td>
                                <td>
                                    <?php
                                    $originalDate = $r->fecha_vencimiento;
                                    $fechav = date("d-m-Y", strtotime($originalDate));
                                    echo $fechav;
                                    ?> 
                                </td>                
                                <td>{{$r->cantidad*$r->presentacion_comercial}}</td>
                                <td>{{$r->p_unitario}}</td>                            
                                <td><?php $subtotal2=$r->cantidad*$r->p_unitario*$r->presentacion_comercial; 
                                          $total2 += $subtotal2;
                                          $canttotal+=$r->cantidad*$r->presentacion_comercial;
                                          echo $subtotal2;
                                ?></td>                            
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8"><strong>TOTAL</strong></td>
                                <td><b><?php echo $canttotal; ?></b></td>
                                <td></td>
                                <td><b><?php echo number_format($total2, 8, '.', ''); ?></b></td>                            
                            </tr>
                        </tfoot>                    
                    </table>   
                @else
                    No hay datos de ingresos registrados
                @endif                      
            @endif 
            </div>
        </div>
    </div>    
    <div class="panel panel-primary" >        
        <div class="panel-body">    
            <div class="col-md-12">                
                <?php $n1 = 0; $cantidad_totals3=0; $totals3=0; $subtotals3=0;$subcantidadtotals3=0;?>
            @if($id_producto!='')                
                @if(count($salidas_producto)>0)
                    <table class="table table-hover" style="font-size: 14px !important;" >
                        <thead>
                            <tr>
                                <th style="background: #3c763d;" ><p style="color:white;">N</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Numero Pecosa</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Pecosa</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">F. Atencion</p></th>
                                <th style="background: #3c763d;" ><p style="color:white;">Destino</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">Lote</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">Registro Sanitario</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">F. Vencimiento</p></th> 
                                <th style="background: #3c763d;" ><p style="color:white;">Cantidad Entregada</p></th>                            
                                <th style="background: #3c763d;" ><p style="color:white;">Precio Unit.</p></th>                            
                                <th style="background: #3c763d;" ><p style="color:white;">Total</p></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salidas_producto as $s)
                            <?php 
                                if($s->pc!=0)
                                    $descripcion =  $s->nombre_producto.' ('.$s->cantidad.' X '.$s->presentacion_comercial.') ';
                                else
                                    $descripcion =  $s->nombre_producto;
                            ?>
                            <tr>
                                <td><?php
                                    $n1++; echo $n1; ?>                            
                                </td>
                                <td>{{$s->nro_acta}}</td>
                                <td>
                                    <?php
                                    $originalDate = $s->fecha_salida;
                                    $fecha = date("d-m-Y", strtotime($originalDate));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td>
                                    <?php
                                    $originalDate = $s->fecha_salida;
                                    $fecha = date("d-m-Y", strtotime($originalDate));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td>
                                    <?php 
                                        switch($s->tipo ){
                                            case 1: echo $s->lugar_destino_farmacia; break;
                                            case 2: echo $s->consultorio;   break;
                                            case 3: echo $s->lugar_destino_establecimiento;  break;
                                            case 4: echo $s->lugar_destino_dependencia; break;
                                            
                                        }                                      
                                    ?>
                                </td>
                                <td>{{$s->lote}}</td>
                                <td>{{$s->registro_sanitario}}</td>                                
                                <td>
                                    <?php
                                    $originalDate2 = $s->fecha_vencimiento;
                                    $fechav = date("d-m-Y", strtotime($originalDate2));
                                    echo $fechav;
                                    ?> 
                                </td>
                                <td><?php $subcantidadtotals3=$s->cantidad*$s->presentacion_comercial;
                                          $cantidad_totals3 += $subcantidadtotals3;
                                          echo $subcantidadtotals3;
                                    ?>                                
                                </td> 
                                <td>{{$s->p_unitario}}</td>                            
                                <td><?php $subtotals3=$s->p_unitario*$s->cantidad*$s->presentacion_comercial; 
                                          $totals3 += $subtotals3;
                                          echo number_format($subtotals3, 6, '.', '');
                                ?></td>                            
                            </tr>
                            @endforeach          
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8"><strong>TOTAL</strong></td>
                                <td><b><?php echo $cantidad_totals3; ?></b></td>
                                <td></td>
                                <td><b><?php echo number_format($totals3, 6, '.', ''); ?></b></td>                            
                            </tr>
                        </tfoot>
                    </table>   
                @else
                    No hay datos de salidas registradas
                @endif
            @endif
            </div>
        </div>
    </div>
    <div class="panel panel-primary" >        
        <div class="panel-body">    
            <div class="col-md-3">
                @if($id_producto!='')
                    <table class="table table-hover" width="100%" style="font-size: 14px !important;" >
                        <tbody> 
                            <tr>                            
                                <td width="50%"  style="background: #3c763d;" ><p style="color:white;">Saldo Inicial</p></td>                            
                                <td width="50%"><?php $saldo=$saldo_inicial;
                                    echo $saldo;
                                ?></td>
                            </tr>
                            <tr>                            
                                <td width="50%"  style="background: #3c763d;" ><p style="color:white;">Total Ingresos</p></td>                            
                                <td width="50%"><?php echo  $canttotal; ?></td>
                            </tr>
                            <tr>                            
                                <td style="background: #3c763d;" ><p style="color:white;">Total Salidas</p></td>                            
                                <td><?php echo  $cantidad_totals3; ?></td>
                            </tr>
                            <tr>                            
                                <td style="background: #3c763d;" ><p style="color:white;">Stock Actual</p></td>                            
                                <td><?php echo  $saldo_final; ?></td>                            
                            </tr>   
                        </tbody>
                    </table>   
                @else
                    No hay datos registrados
                @endif
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
