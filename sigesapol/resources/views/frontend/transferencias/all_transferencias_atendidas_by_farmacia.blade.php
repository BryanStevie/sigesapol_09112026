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
            <h3 class="panel-title">Buscar Recetas Registradas - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-8">
                <form class="form-horizontal" method="post" action="{{ route('listar_recetas_nuevo')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <!--
<div class="col-md-12">Fecha</div>
<div class="col-md-3">
    <input type="text" class="form-control datepicker" name="fecha" required="">
    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
</div>
                    -->
                    <label class="col-md-2 control-label" for="textinput">Nro. Pecosa</label>  
                    <div class="col-md-3">
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                    </div>
                    <label class="col-md-2 control-label">Producto</label>
                    <div class="col-md-3">
                        <select class="form-control input-sm" name="iddispensador" id="iddispensador" tabindex="16" style="width: 100%">
                            <?php
                            foreach ($dispensador as $d) {
                                echo '<option value="' . $d->id_user . '"';
                                if (isset($iddispensador)) {
                                    if ($iddispensador == $d->id_user)
                                        echo " selected";
                                }
                                echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                    </div>

                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Clasificador Gastos</label>
                    <div class="col-md-3">
                        <select class="form-control input-sm" name="iddispensador" id="iddispensador" tabindex="16" style="width: 100%">
                            <option value="">Todos</option>
                            <?php
                            foreach ($dispensador as $d) {
                                echo '<option value="' . $d->id_user . '"';
                                if (isset($iddispensador)) {
                                    if ($iddispensador == $d->id_user)
                                        echo " selected";
                                }
                                echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label">IPRESS Destino</label>
                    <div class="col-md-3">                    
                        <select class="form-control input-sm" name="iddispensador" id="iddispensador" tabindex="16" style="width: 100%">
                            <option value="">Todos</option>
                            <?php
                            foreach ($dispensador as $d) {
                                echo '<option value="' . $d->id_user . '"';
                                if (isset($iddispensador)) {
                                    if ($iddispensador == $d->id_user)
                                        echo " selected";
                                }
                                echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                            }
                            ?>
                        </select>                    
                    </div>
                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-12" style="margin-top: 10px">
                <legend>
                    <div class="pull-right">Total: {{$cantidad}} receta(s)
                        <?php
                        if (isset($estado) && $estado == 1)
                            echo "Atendida(s)";
                        if (isset($estado) && $estado == 2)
                            echo "Invalidada(s)";
                        if (!isset($estado))
                            echo "Atendida(s)";
                        ?>
                    </div>
                </legend>
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
                            <?php if ($farmacia->id == 21): ?>
                                <th>TIPO USO</th>
                            <?php endif; ?>
                            <th>Nro. Receta</th>
                            <?php if ($farmacia->id == 22 || $farmacia->id == 14): ?>
                                <th>RECETA</th>
                            <?php endif; ?>
                            <?php if ($farmacia->id == 20): ?>
                            <th>Interconsulta</th>
                            <?php endif; ?>
                            <th>Especialidad</th>
                            <th>Hora<br/>Registro</th>
                            <th>Fecha<br/>Dispensación</th>
                            <th>Fecha<br/>Expedición</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>                            
                            <th>Tipo</th>
                            <th>Registrador</th>
                            <th>Invalidar</th>
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
                            <?php if ($farmacia->id == 21): ?>
                                <td>{{$r->tipo_via}}</td>
                            <?php endif; ?>
                            <td>{{$r->nro_receta}}</td>
                            <?php if ($farmacia->id == 22 || $farmacia->id == 14): ?>
                                <td>{{$r->tipo_receta}}</td>
                            <?php endif; ?>
                            <?php if ($farmacia->id == 20): ?>
                            <td>{{$r->interconsulta}}</td>
                            <?php endif; ?>
                            <td>{{$r->consultorio}}</td>
                            <td>
                                <?php
                                $originalDate3 = $r->created_at;
                                $horaR = date("H:i:s", strtotime($originalDate3));
                                echo $horaR;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate1 = $r->fecha_expedicion;
                                $fechaE = date("d-m-Y", strtotime($originalDate1));
                                echo $fechaE;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate2 = $r->fecha_registro;
                                $fecha = date("d-m-Y", strtotime($originalDate2));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->tipodoc_beneficiario}}</td> 
                            <td>{{$r->dni_beneficiario}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <td>{{$r->tipo_beneficiario}}</td> 
                            <td>{{$r->paterno_usuario}} {{$r->materno_usuario}}, {{$r->nombre_usuario}}</td>
                            <td class="text-center">
                                <?php
                                $id_receta = (isset($r->id_receta) && $r->id_receta > 0) ? $r->id_receta : "";
                                if (isset($estado) && $estado == 2) {
                                    ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                        <button type="button" class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button>
                                    </div>
                                <?php } else { ?>
                                    <?php if ($farmacia->id == 20){ 
                                            if (Auth::user()->flagresponsablefarmacia == 1){ ?>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                    <a href="/ver_receta_atendida/1/{{ Auth::user()->id }}/{{$r->nro_receta}}/{{$r->id}}" class="btn btn-danger btn-xs"><i class="fa fa-xing"></i></a>
                                                </div>
                                      <?php }
                                            else
                                            { ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                <button type="button" class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button>
                                            </div>                                    
                                    <?php   }
                                        }
                                        else
                                        { ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                <a href="/ver_receta_atendida/1/{{ Auth::user()->id }}/{{$r->nro_receta}}/{{$r->id}}" class="btn btn-danger btn-xs"><i class="fa fa-xing"></i></a>
                                            </div>
                                <?php   } 
                                    }
                                 ?>
                            </td>
                            <td class="text-center">
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver detalle receta</b>">
                                    <button type="button" class="btn btn-success btn-xs" onclick="ver_det_receta('{{ Auth::user()->id }}','{{$r->nro_receta}}','{{$r->id}}')"><i class="fa fa-search"></i></button>
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
@include('frontend.includes.footer')
@endsection
