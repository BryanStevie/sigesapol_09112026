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
    
    function reporteHospitalizado(){

    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_hospitalizacion = $('#nro_hospitalizacion').val();
    var estado = $('#estado').val();
    var id_dispensador = $('#iddispensador').val();
    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    if (dni_beneficiario == "")dni_beneficiario = 0;
    if (nro_hospitalizacion == "")nro_hospitalizacion = 0;
    if (id_dispensador == "")id_dispensador = 0;
    location.href = 'exportar_hospitalizados/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + dni_beneficiario + '/' + nro_hospitalizacion + '/' + estado + '/' + id_dispensador;
    }

    function ver_det_receta(idUser, nro_hospitalizacion, id_receta){
    $('#nrorecmodal').text(nro_hospitalizacion);
    var estado = $('#estado').val();
    $.ajax({
    url: '/ver_hospitalizado/2/' + idUser + '/' + nro_hospitalizacion + '/' + id_receta + '/' + estado,
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
            <h3 class="panel-title">Buscar Pacientes Hospitalizados - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-8">
                <form class="form-horizontal" method="post" action="{{ route('listar_hospitalizados')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <!--
<div class="col-md-12">Fecha</div>
<div class="col-md-3">
    <input type="text" class="form-control datepicker" name="fecha" required="">
    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
</div>
                    -->
                    <label class="col-md-2 control-label" for="textinput">Nro. Documento</label>  
                    <div class="col-md-3">
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                    </div>
                    <label class="col-md-2 control-label" for="textinput">Nro. Hospitalizacion</label>  
                    <div class="col-md-3">
                        <input id="nro_hospitalizacion" name="nro_hospitalizacion" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_hospitalizacion ?>" onKeyPress="return soloNumeros(event)" maxlength="8">
                    </div>

                    <div class="col-md-2">
                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                    </div>

                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Registrador</label>
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
                    <label class="col-md-2 control-label">Estado</label>
                    <div class="col-md-3">
                        <select class="form-control input-sm" name="estado"  id="estado" tabindex="15">
                            <option value="1" <?php if (isset($estado) && $estado == 1) echo "selected='selected'"; ?>>Hospitalizados</option>
                            <option value="2" <?php if (isset($estado) && $estado == 2) echo "selected='selected'"; ?>>Altas</option>
                        </select>
                    </div>
                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$id_farmacia}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteHospitalizado()" >
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
            <div class="col-md-12" style="margin-top: 10px">
                <legend>
                    <div class="pull-right">Total: {{$cantidad}} paciente(s)
                        <?php
                        if (isset($estado) && $estado == 1)
                            echo "Hospitalizado(s)";
                        if (isset($estado) && $estado == 2)
                            echo "Alta(s)";
                        if (!isset($estado))
                            echo "Hospitalizado(s)";
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
                            <?php if ($modo_validacion == 3): ?>
                                <th>Nro. Admisi&oacute;n</th>
                            <?php endif; ?>
                            <th>Nro. Hospitalizacion</th>
                            <th>Sala</th>
                            <th>Fecha<br/>Ingreso</th>
                            <th>Fecha<br/>Alta</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>
                            <th>Tipo</th>
                            <th>Diagnostico</th>
                            <th>Medico</th>
                            <th>Detalle</th>
                            <th>Imprimir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($hospitalizado as $r)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n;
                                ?></td>
                            <?php if ($modo_validacion == 3): ?>
                                <td>{{$r->nro_hospitalizacion}}</td>
                            <?php endif; ?>
                            <td>{{$r->nro_hospitalizacion}}</td>
                            <td>{{$r->consultorio}}</td>
                            <td>
                                <?php
                                $originalDate1 = $r->fecha_ingreso;
                                $fechaE = date("d-m-Y", strtotime($originalDate1));
                                echo $fechaE;
                                ?> 
                            </td>
                            <td>
                                <?php
                                if(!is_null($r->fecha_salida)){
                                    $originalDate2 = $r->fecha_salida;
                                    $fecha = date("d-m-Y", strtotime($originalDate2));
                                    echo $fecha;
                                }
                                ?> 
                            </td>
                            <td>{{$r->dni_beneficiario}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <td>{{$r->tipo_beneficiario}}</td> 
                            <td>{{$r->codigo_diagnostico}} {{$r->nombre_diagnostico}}</td>
                            <td>{{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}</td>
                            <td class="text-center">
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver detalle paciente</b>">
                                    <button type="button" class="btn btn-success btn-xs" onclick="ver_det_receta('{{ Auth::user()->id }}','{{$r->nro_hospitalizacion}}','{{$r->id}}')"><i class="fa fa-search"></i></button>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                if(!is_null($r->fecha_salida)){ ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Imprimir Alta</b>">
                                        <a target="_blank" href="/imprimir_nuevo_alta/{{$r->id}}/{{ $id_farmacia }}" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                <?php } else {?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Imprimir Hospitalizado</b>">
                                        <a target="_blank" href="/imprimir_nuevo_hospitalizado/{{$r->id}}/{{ $id_farmacia }}/{{Auth::user()->id}}" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                    
                                <?php } ?>
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
                <h5 class="modal-title">DETALLE DE PACIENTE HOSPITALIZADO - NRO. <span id="nrorecmodal"></span></h5>
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
