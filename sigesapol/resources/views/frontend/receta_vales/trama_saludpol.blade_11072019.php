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
        min-height: 650px !important;
        width: auto;
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
        overflow-y: scroll !important;
            /* background: #f1f1f1;*/
            overflow-y: scroll !important;
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

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
    //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

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
    /*
     //something is entered in search form
     $('#system-search').keyup(function () {
     var that = this;
     // affect all table rows on in systems table
     var tableBody = $('.table-list-search tbody');
     var tableRowsClass = $('.table-list-search tbody tr');
     $('.search-sf').remove();
     tableRowsClass.each(function (i, val) {
     
     //Lower text for case insensitive
     var rowText = $(val).text().toLowerCase();
     var inputText = $(that).val().toLowerCase();
     if (inputText != '')
     {
     $('.search-query-sf').remove();
     
     } else
     {
     $('.search-query-sf').remove();
     }
     
     if (rowText.indexOf(inputText) == -1)
     {
     //hide rows
     tableRowsClass.eq(i).hide();
     
     } else
     {
     $('.search-sf').remove();
     tableRowsClass.eq(i).show();
     }
     });
     //all tr elements are hidden
     if (tableRowsClass.children(':visible').length == 0)
     {
     tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No existe la receta que esta buscando.</td></tr>');
     }
     });
     
     */
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
    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    location.href = 'exportar_reporte_trama/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_establecimiento ?>/';
    }

    function ver_det_receta(idUser, nro_receta, id_receta){
    $('#nrorecmodal').text(nro_receta);
    $.ajax({
    url: '/ver_receta_atendida/2/' + idUser + '/' + nro_receta + '/' + id_receta,
            async: false,
            success: function(result){
            $("#muestra-det-receta").html(result);
            },
            complete: function () {
            $("#myModalDetRV").modal();
            }
    });
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
            <h3 class="panel-title">TRAMA SALUDPOL</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-10">
                <form class="form-horizontal" method="post" action="{{ route('listar_trama')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar receta">                        
            </div>

            <?php //print_r($recetas);  ?>
            <div class="col-md-12"  id="global">
                <!--<table  class="table table-list-search">-->
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Nro Receta</th>
                            <th>Tipo DOC</th>
                            <th>N DOC</th>
                            <th>Nombres</th>
                            <th>Paterno</th>
                            <th>Materno</th>                            
                            <th>Tipo Atencion</th>
                            <th>Fec Exp</th>
                            <th>Fec Atencion</th>
                            <th>DNI MEDICO</th>
                            <th>Nombres</th>
                            <th>Paterno</th>
                            <th>Materno</th>
                            <th>Especialidad</th>
                            <th>Servicio</th>
                            <th>Cod Diag</th>
                            <th>Diagnostico</th>
                            <th>Tipo Producto</th>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>IPRESS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($recetas as $r)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n;
                                ?></td>

                            <td>{{$r->nro_receta}}</td> 
                            <td>{{$r->paciente_tipo_documento}}</td>
                            <td>{{$r->paciente_numero_documento}}</td>
                            <td>{{$r->nombre_beneficiario}}</td> 
                            <td>{{$r->paterno_beneficiario}}</td> 
                            <td>{{$r->materno_beneficiario}}</td>                            
                            <td>{{$r->tipo_atencion}}</td> 
                            <td>{{$r->fecha_expedicion}}</td> 
                            <td>{{$r->fecha_registro}}</td> 
                            <td>{{$r->dni_medico}}</td> 
                            <td>{{$r->nomb_medico}}</td> 
                            <td>{{$r->paterno_medico}}</td> 
                            <td>{{$r->materno_medico}}</td>
                            <td>{{$r->especialidad_medico}}</td>
                            <td>{{$r->servicio}}</td> 
                            <td>{{$r->cod_dx}}</td> 
                            <td>{{$r->diagnostico}}</td>
                            <td>{{$r->tipo_producto}}</td> 
                            <td>{{$r->codigo_petitorio}}</td> 
                            <td>{{$r->principio_activo}}</td> 
                            <td>{{$r->cantidad}}</td> 
                            <td>{{$r->precio_unitario}}</td> 
                            <td>{{$r->ipress_codigo}}</td> 
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>   
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-receta"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
