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
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
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
    var id_consultorio = $('#idconsultorio').val();
    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    if (id_consultorio == "")id_consultorio = 0;
    location.href = 'exportar_reporte_servicio/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + id_consultorio;
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

    function show_Atenciones(dni,fecha_ini,fecha_fin,id_consultorio){
        
        var id_farmacia = $('#id_farmacia').val();              

            var dni_beneficiario = dni;
            var newOption = "";
            $.ajax({
                url: '/obtenerBeneficiarioByServicio/' + dni_beneficiario+ '/'+fecha_ini+'/'+ fecha_fin+ '/'+id_farmacia+'/'+id_consultorio,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    var mesactu = parseInt("<?php echo date("m")?>");
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.colspan;
                        var fecexpe = oo.fecha_registro;
                        var res = fecexpe.split("-");
                        var mesexpe = parseInt(res[1]);
                        
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta_vale + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<tr><td><small>" + oo.nro_receta + "</small></td><td><small>" + oo.fecha_expedicion + "</small></td><td><small>" + oo.fecha_registro + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td><small>" + oo.nom_diagnostico + "</small></td>";
                        
                        newOption += "<td class=\"text-center\" style=\"vertical-align: middle\">" + btn + "</td></tr>";
                        
                        if(i == oo.colspan){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
                    });
                    $('#muestra-atenciones').html(newOption);
                }
            });
            $('#showAtencionesMensual').modal();
        }

        function show_Det_Atenciones(id_receta_vale){
            $.ajax({
                url: '/obtenerProductobyRecetasVale/' + id_receta_vale,
                success: function (result) {
                    var newOption = "";
                    $(result).each(function (ii, oo) {
                        newOption += "<tr><td class=\"text-center\"><small>" + oo.codigo + "</small></td><td><small>" + oo.nombre + "</small></td><td class=\"text-center\"><small>" + oo.abreviatura + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_prescrita + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_dispensada + "</small></td>";
                    });
                    $('#muestra-productos').html(newOption);
                }
            });
            $('#showProductosReceta').modal();
        }


</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Beneficiarios por Servicio - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('listar_beneficiarios_servicios')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <!--
<div class="col-md-12">Fecha</div>
<div class="col-md-3">
    <input type="text" class="form-control datepicker" name="fecha" required="">
    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
</div>
                    -->
                    
                    <label class="col-md-1 control-label">Servicios</label>
                    <div class="col-md-2">
                        <select class="form-control input-sm" name="idconsultorio" id="idconsultorio" tabindex="16" style="width: 100%">
                            <option value="">Todos</option>
                            <?php
                            foreach ($consultorio as $d) {
                                echo '<option value="' . $d->id . '"';
                                if (isset($idconsultorio)) {
                                    if ($idconsultorio == $d->id)
                                        echo " selected";
                                }
                                echo '>' . $d->nombre . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-1 control-label">F. Desde</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">                        
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-1 control-label">F. Hasta</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">                        
                    </div>
                    
                    <div class="col-md-1">
                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>
                    </div>
                    <!--		
                    <div class="col-md-2 col-md-offset-5">
                            <input class="form-control btn btn-info" value="Buscar" type="submit">
                    </div>
                    -->
                    <!--
<div class="col-md-6">
    <button class="btn btn-info" type="submit">
        <i class="fa fa-search"></i>
    </button>
</div>
                    -->
                    <!--
<div class="col-md-3">
    <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}" class="btn btn-info" type="submit">
        <i class="fa fa-list"></i> Lista de Recetas atendidas
    </a>
</div>
                    -->
                </form>
            </div>
            <div class="col-md-12">
                <br/>
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar beneficiario">                        

            </div>

            <?php //print_r($recetas);  ?>
            <div class="col-md-12"  id="global">
                <!--<table  class="table table-list-search">-->
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>
                            @if($id_farmacia==22)
                            <th>Tipo Receta</th>
                            @endif
                            <th>Especialidad</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>                            
                            <th>Tipo</th>
                            <th>Detalle</th>
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
                            @if($id_farmacia==22)
                            <td>{{$r->tipo_receta}}</td> 
                            @endif
                            <td>{{$r->nombre}}</td>
                            <td>{{$r->tipodoc_beneficiario}}</td> 
                            <td>{{$r->dni_beneficiario}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <td>{{$r->tipo_beneficiario}}</td> 
                            <td class="text-center">
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver detalle receta</b>">
                                    <button type="button" class="btn btn-success btn-xs" onclick="show_Atenciones('{{$r->dni_beneficiario}}','{{$fechaDesde}}','{{$fechaHasta}}','{{$r->id_consultorio}}')"><i class="fa fa-search"></i></button>
                                </div>
                            </td>
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
<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes actual</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Nro. Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                
                                
                                <th><small>Médico</small></th>
                                <th><small>Diagnóstico</small></th>
                                
                                <th><small>Detalle<br/>Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showAtencionesDetMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 22px; color: #fff">Nro. Atenciones según fecha de expedición</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th><small>Nro. <br/>Atención</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Nro Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Detalle<br/>Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-det-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código</small></th>
                                <th><small>Descipción</small></th>
                                <th><small>Unidad</small></th>
                                <th><small>Pedido</small></th>
                                <th><small>Despachado</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-productos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
