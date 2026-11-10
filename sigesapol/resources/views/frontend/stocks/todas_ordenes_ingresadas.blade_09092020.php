<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
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
    $('#l_origen').select2();
    $('#id_proveedor').select2();
    $('#id_tipo_producto').select2();
    $('#idregistrador').select2();
    $('#id_tipo_proceso').select2();
    
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
        $("#id_tipo_proceso").select2({
        width: 'resolve' // need to override the changed default
        });
        $("#id_tipo_proceso").trigger('change');
        });

    $('#id_tipo_proceso').val('<?php echo $id_tipo_proceso; ?>').trigger('change.select2');
    $('#idregistrador').val('<?php echo $idregistrador; ?>').trigger('change.select2');
    $('#id_producto').val('<?php echo $id_producto; ?>').trigger('change.select2');
    $('#id_tipo_producto').val('<?php echo $id_tipo_producto; ?>').trigger('change.select2');
    $('#id_proveedor').val('<?php echo $id_proveedor; ?>').trigger('change.select2');
    $('#l_origen').val('<?php echo $l_origen; ?>').trigger('change.select2');

    //$('#tipo_proceso').val(result[0].tipo_proceso).trigger('change.select2');
    function reporteInternamiento(){

    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var num_doc = $('#num_doc').val();
    var num_siaf = $('#num_siaf').val();
    var reg_sanitario = $('#reg_sanitario').val();
    var lote = $('#lote').val();
    var l_origen = $('#l_origen').val();
    var id_proveedor = $('#id_proveedor').val();
    var id_tipo_proceso = $('#id_tipo_proceso').val();
    var id_producto = $('#id_producto').val();
    var id_tipo_producto = $('#id_tipo_producto').val();
    var idregistrador = $('#idregistrador').val();

    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    if (num_doc == "")num_doc = 0;
    if (num_siaf == "")num_siaf = 0;    
    if (reg_sanitario == "")reg_sanitario = 0;
    if (lote == "")lote = 0;
    if (l_origen == "")l_origen = 0;
    if (id_proveedor == "")id_proveedor = 0;
    if (id_tipo_proceso == "")id_tipo_proceso = 0;
    if (id_tipo_producto == "")id_tipo_producto = 0;
    if (idregistrador == "")idregistrador = 0;
    if (id_producto == "")id_producto = 0;
    
    location.href = 'exportar_ordenes_ingresadas/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + num_doc + '/' + num_siaf + '/' + reg_sanitario + '/' + lote + '/' + l_origen + '/' + id_proveedor + '/' + id_tipo_proceso + '/' + id_tipo_producto + '/' + idregistrador + '/' + id_producto ;
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
            <h3 class="panel-title">Buscar Internamiento de Productos - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('buscar_orden_internamiento')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div clas="row">                    
                        <div class="col-md-2">
                            <label class="help-block">Num Documento</label>
                            <input id="num_doc" name="num_doc" type="text" placeholder="" class="form-control input-sm" maxlength="7" value="<?php echo $num_doc ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="help-block">Num SIAF</label>
                            <input id="num_siaf" name="num_siaf" type="text" placeholder="" class="form-control input-sm" maxlength="10" value="<?php echo $num_siaf ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="help-block">Tipo Proceso</label>
                            <select class="form-control" name="id_tipo_proceso" id="id_tipo_proceso">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($tipo_procesos as $tproc) {
                                    echo '<option value="' . $tproc->id . '"';
                                    if (isset($id_tipo_proceso)) {
                                        if ($id_tipo_proceso == $tproc->id)
                                            echo " selected";
                                    }
                                    echo '>' . $tproc->abreviatura_tipo_proceso . ' - ' . $tproc->descripcion_tipo_proceso . '</option>';
                                }
                                ?>                                
                            </select>
                        </div>
                        <div class="col-md-2">                            
                            <label class="help-block">Fecha Desde</label>
                            <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                            <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                        </div>                        
                        <div class="col-md-2">
                            <label class="help-block">Fecha Hasta</label>
                            <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        </div>
                        <div class="col-md-2">
                            <br/><br/>
                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                        </div>
                    </div>
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
                        <div class="col-md-2">
                            <label class="help-block">Tipo Producto</label>
                            <select class="form-control" name="id_tipo_producto" id="id_tipo_producto">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($tipo_productos as $tpro) {
                                    echo '<option value="' . $tpro->id . '"';
                                    if (isset($id_tipo_producto)) {
                                        if ($id_tipo_producto == $tpro->id)
                                            echo " selected";
                                    }
                                    echo '>' . $tpro->nombre . '</option>';
                                }
                                ?>                                
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="help-block">Reg Sanitario</label>
                            <input id="reg_sanitario" name="reg_sanitario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $reg_sanitario ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="help-block">Lote</label>
                            <input id="lote" name="lote" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $lote ?>">
                        </div>
                        
                        <div class="col-md-2">
                            <br/><br/>
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteInternamiento()" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="help-block">Proveedor</label>
                            <select class="form-control" name="id_proveedor" id="id_proveedor">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($proveedores as $prov) {
                                    echo '<option value="' . $prov->id . '"';
                                    if (isset($id_proveedor)) {
                                        if ($id_proveedor == $prov->id)
                                            echo " selected";
                                    }
                                    echo '>' . $prov->ruc_proveedor . " - " .$prov->nombre_proveedor . '</option>';
                                }
                                ?>
                            </select>
                        </div>    
                        <div class="col-md-3">
                            <label class="help-block">Origen</label>
                            <select class="form-control input-sm" name="l_origen" id="l_origen" tabindex="16" style="width: 100%">
                                <option value="">Todos</option>
                                <?php
                                foreach ($dependencias as $d) {
                                    echo '<option value="' . $d->id . '"';
                                    if (isset($l_origen)) {
                                        if ($l_origen == $d->id)
                                            echo " selected";
                                    }
                                    echo '>' . $d->nombre . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="help-block">Registrador</label>
                            <select class="form-control input-sm" name="idregistrador" id="idregistrador" tabindex="16" style="width: 100%">
                                <option value="">Todos</option>
                                <?php
                                foreach ($registrador as $d) {
                                    echo '<option value="' . $d->id_user . '"';
                                    if (isset($idregistrador)) {
                                        if ($idregistrador == $d->id_user)
                                            echo " selected";
                                    }
                                    echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>            
            <div class="col-md-12">
                <br/><br/>
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar receta">                        
            </div>            
            <div class="col-md-12"  id="global">                
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>                            
                            <th>Origen</th>
                            <th>Fecha</th>
                            <th>Concepto</th>                                        
                            <th>Num Documento</th>
                            <th>Num SIAF</th>                            
                            <th>Tipo Producto</th> 
                            <th>Factura</th>
                            <th>Guia</th>
                            <th>Codigo</th>                            
                            <th>Producto</th>                            
                            <th>R.S.</th>
                            <th>Lote</th>
                            <th>F. Venc</th> 
                            <th>P.U.</th>
                            <th>Cantidad</th>                             
                            <th>Proveedor</th>
                            <th>Proceso</th>
                            <th>Nro Entrega</th>
                            <th>Total Entrega</th>
                            <th>Cuenta</th>
                            <th>Clasificador</th>                            
                            <th>Registrador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($ordenes_compra as $r)
                        <tr>
                            <td><?php
                                $n++; echo $n; ?>                            
                            </td>
                            <td>{{$r->lugar_origen}}</td>
                            <td>
                                <?php
                                $originalDate = $r->fecha_entrada;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->conceptos}}</td>
                            <td>{{$r->num_concepto}}</td>
                            <td>{{$r->siaf}}</td>                            
                            <td>{{$r->tipo_producto}}</td>
                            <td>{{$r->factura}}</td>
                            <td>{{$r->guia_entrada}}</td>
                            <td>{{$r->cod_producto}}</td>
                            <td>{{$r->nombre_producto}}</td>
                            <td>{{$r->registro_sanitario}}</td>
                            <td>{{$r->lote}}</td>
                            <td>
                                <?php
                                $originalDate2 = $r->fecha_vencimiento;
                                $fecha = date("d-m-Y", strtotime($originalDate2));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->p_unitario}}</td>
                            <td>{{$r->cantidad}}</td>
                            <td>{{$r->proveedor}}</td>
                            <td>{{$r->nro_proceso}}-{{$r->abre_proceso}}</td>
                            <td>{{$r->nro_entrega}}</td>
                            <td>{{$r->total_entrega}}</td>
                            <td>{{$r->cuenta}}</td>
                            <td>{{$r->clas_gasto}}</td> 
                            <td>{{$r->paterno_usuario}} {{$r->materno_usuario}}, {{$r->nombre_usuario}}                           
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>   
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
