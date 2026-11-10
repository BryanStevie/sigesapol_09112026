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
    $("#iddispensador").select2({
    width: 'resolve' // need to override the changed default
    });
    $("#iddispensador").trigger('change');
    });
    function reporteReceta(){

    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_receta = $('#nro_receta').val();
    var estado = $('#estado').val();
    var id_dispensador = $('#iddispensador').val();
    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    if (dni_beneficiario == "")dni_beneficiario = 0;
    if (nro_receta == "")nro_receta = 0;
    if (id_dispensador == "")id_dispensador = 0;
    location.href = 'exportar_reporte_receta/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + dni_beneficiario + '/' + nro_receta + '/' + estado + '/' + id_dispensador;
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
                            <label class="help-block">Num Acta</label>
                            <input id="num_acta" name="num_acta" type="text" placeholder="" class="form-control input-sm" maxlength="10" value="<?php echo $num_acta ?>">
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
                                    echo '>' . $pro->nombre . '</option>';
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
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
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
                                    echo '>' . $prov->ruc_proveedor . " " .$prov->nombre_proveedor . '</option>';
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
                                        if ($l_origen == $d->nombre)
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
                            <th>Fecha</th>
                            <th>Concepto</th>                                        
                            <th>Nro Concepto</th>
                            <th>Nro SIAF</th>
                            <th>Nro Acta</th>
                            <th>Tipo Prod</th> 
                            <th>Codigo</th>                            
                            <th>Producto</th>                            
                            <th>R.S.</th>
                            <th>Lote</th>
                            <th>F. Venc</th> 
                            <th>P.U.</th>
                            <th>Cantidad</th>                             
                            <th>Proveedor</th>
                            <th>Tipo Proceso</th>
                            <th>Factura</th>
                            <th>Cuenta</th>
                            <th>Clasificador</th>                            
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($ordenes_compra as $r)
                        <tr>
                            <td><?php
                                $n++; echo $n; ?>                            
                            </td>                            
                            <td>{{$r->concepto}}</td>
                            <td>{{$r->num_concepto}}</td>
                            <td>{{$r->siaf}}</td>
                            <td>{{$r->acta}}</td>
                            <td>{{$r->tipo_producto}}</td>
                            <td>{{$r->cod_producto}}</td>
                            <td>{{$r->nombre_producto}}</td>
                            <td>{{$r->registro_sanitario}}</td>
                            <td>{{$r->lote}}</td>
                            <td>
                                <?php
                                $originalDate2 = $r->fecha_entrada;
                                $fecha = date("d-m-Y", strtotime($originalDate2));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->p_unitario}}</td>
                            <td>{{$r->cantidad}}</td>
                            <td>{{$r->proveedor}}</td>
                            <td>{{$r->nro_proceso}}-{{$r->abre_proceso}}</td>
                            <td>{{$r->factura}}</td>
                            <td>{{$r->cuenta}}</td>
                            <td>{{$r->clas_gasto}}</td>
                            <td>{{$r->cuenta}}</td>
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
