@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .row{
        margin-top:40px;
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
    #squarespaceModal .modal-dialog{
        width: 1200px;
    }
</style>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    
    $(document).ready(function () {
        $('#example2').DataTable({            
            "info": false
        });
    });
    
    
    
    $(function () {
     
    });
});
</script>
@endsection
@section('content')

<?php

$txtfechaDesde_=explode("-",$fecha_inicio);
$txtfechaHasta_=explode("-",$fecha_fin);

$fechaIni=$txtfechaDesde_[2]."/".$txtfechaDesde_[1]."/01"; 
$fechaFin=$txtfechaHasta_[2]."/".$txtfechaHasta_[1]."/01";

$mes = date("m", strtotime($fechaIni));
$ano=  date("Y", strtotime($fechaIni));

$limite=$txtfechaHasta_[1].'/'.$txtfechaHasta_[2];
$nuevo=0;

$arrayMeses=array();
$arrayMeses[]=$mes."/".$ano;


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Recetas Dispensada por Beneficiario</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/13">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>

                @if(count($recetas)>0)
                <div class="panel-body">
                    <!--<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />-->
                    <?php //print_r($beneficiario);exit();?>
                    <div class="col-md-12">
                        <label for="inputEmail" class="control-label col-xs-3">NRO. DOCUMENTO</label>
                        <div class="col-xs-9"><?php echo $beneficiario[0]->dni_beneficiario ?></div>
                    </div>

                    <div class="col-md-12">
                        <label for="inputEmail" class="control-label col-xs-3">APELLIDOS Y NOMBRES</label>
                        <div class="col-xs-9"><?php echo $beneficiario[0]->paterno_beneficiario . " " . $beneficiario[0]->materno_beneficiario . ", " . $beneficiario[0]->nombre_beneficiario ?></div>
                    </div>

                    <div class="col-md-12">
                        <label for="inputEmail" class="control-label col-xs-3">TIPO DE BENEFICARIO</label>
                        <div class="col-xs-9"><?php echo $beneficiario[0]->tipo_beneficiario ?></div>
                    </div>

                </div>

                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Farmacia</th>
                            <th>Numero de Receta</th>
                            <th>Fecha de Dispensación</th>
                            <th>Productos Farmacéuticos y Dispositivos médicos</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        $cantidadTotal = 0;
                        $importe_total = 0;
                        ?>
                        @foreach($recetas as $f)

                        <?php
                        $cantidadTotal += $f->cantidad_dispensada;
                        $importe = $f->cantidad_dispensada * $f->precio_unitario;
                        $importe_total += $importe;
                        ?>

                        <tr>
                            <td><?php
                                $n++;
                                echo $n
                                ?></td>
                            <td>{{$f->farmacia}}</td>
                            <td>{{$f->nro_receta}}</td>
                            <td>{{$f->fecha_expedicion}}</td>
                            <td>{{$f->codigo}} - {{$f->producto}}</td>
                            <td align="right">{{$f->cantidad_dispensada}}</td>
                            <td align="right">{{number_format($f->precio_unitario, 5, '.', ',')}}</td>
                            <td align="right">{{number_format($importe, 5, '.', ',')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td align="right"><?php echo $cantidadTotal ?></td>
                            <td>&nbsp;</td>
                            <td align="right"><?php echo number_format($importe_total, 5, '.', ',') ?></td>
                        </tr>
                    </tfoot>
                </table>
                <br />
                <div class="col-md-12">
                    <label for="inputEmail" class="control-label col-xs-5">Resumen por Producto: </label>
                </div>
                <br /><br />
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="5%">Cantidad Recetas</th>
                            <th>Productos Farmacéuticos y Dispositivos médicos</th>
                            <th style="text-align:right">Cantidad</th>
                            <th style="text-align:right">Precio</th>
                            <th style="text-align:right">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        $cantidadTotal = 0;
                        $importe_total = 0;
                        ?>
                        @foreach($recetasGroup as $f)

                        <?php
                        $cantidadTotal += $f->cantidad_dispensada;
                        $importe = $f->cantidad_dispensada * $f->precio_unitario;
                        $importe_total += $importe;
                        ?>

                        <tr>
                            <td><?php
                                $n++;
                                echo $n
                                ?></td>
                            <td>{{$f->cantidad_recetas}}</td>
                            <td>{{$f->codigo}} - {{$f->producto}}</td>
                            <td align="right">{{$f->cantidad_dispensada}}</td>
                            <td align="right">{{number_format($f->precio_unitario, 5, '.', ',')}}</td>
                            <td align="right">{{number_format($importe, 5, '.', ',')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td align="right"><?php echo $cantidadTotal ?></td>
                            <td>&nbsp;</td>
                            <td align="right"><?php echo number_format($importe_total, 5, '.', ',') ?></td>
                        </tr>
                    </tfoot>
                </table>
                @else                                         
                <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                </div>
                @endif
                <div style="padding-bottom: 10px;"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($recetas)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Recetas según Beneficiario: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_receta_por_beneficiario/{{$fecha_inicio}}/{{$fecha_fin}}/{{$beneficiario[0]->dni_beneficiario}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>                        
                    </div>
                    <!--<div class="col-md-12" style="margin-bottom: 10px">
                          <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#squarespaceModal"  href="">Ver historial de diagnosticos</a>
                    </div>-->
                    @else                                         
                    <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                    </div>
                    @endif
                </div>                
            </div>
        </div>
    </div>
</div>
		 
     
@endsection
