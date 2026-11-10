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
	
	.modal-dialog{
        width: 70% !important;
    }
	
    @media screen and (max-width: 767px) {
    div.dataTables_wrapper div.dataTables_length, div.dataTables_wrapper div.dataTables_filter, div.dataTables_wrapper div.dataTables_info, div.dataTables_wrapper div.dataTables_paginate {
        text-align: left;
    }
}
.dataTables_scrollBody {
    overflow-x: auto !important;
    overflow-y: auto !important;
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
}

table.dataTable {
    width: 100% !important; /* Asegura que la tabla ocupe el ancho necesario */
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
	/*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
	*/
    });
    });
    $('#fechaHasta').trigger('change');
    $('#fechaDesde').trigger('change');
    var activeSystemClass = $('.list-group-item.active');
    });
    //$(document).ready(function () {
    /*$('#example').DataTable({
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });*/
    $(document).ready(function () {
    $('#example').DataTable({
        "scrollY": "400px",  
        "scrollX": true,    
        "scrollCollapse": true,
        "paging": true, 
        "searching": false, 
        "language": {
            "emptyTable": "No se encontraron resultados"
        }
    });
});


    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

   // });

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Examenes de Radiologia - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_examenes_radiologicos_atendidos')}}">
                <div class="col-sm-10">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_user" value="{{ $id_user }}">
                    <label class="col-md-2 control-label" for="textinput">Nro. Documento</label>  
                    <div class="col-md-2">
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                    </div>
                    <label class="col-md-2 control-label" for="textinput">Nro. Prestacion</label>  
                    <div class="col-md-2">
                        <input id="nro_prestacion" name="nro_prestacion" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_prestacion ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                    </div>
                    <label class="col-md-2 control-label" for="textinput">Nro. Ex Radiologico</label>  
                    <div class="col-md-2">
                        <input id="nro_examen" name="nro_examen" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_examen ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                    </div>
                    
                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Orden</p>
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Orden</p>
                    </div>
                    
                
                </div>
                <div class="col-sm-2">
                    <div class="col-md-10">
                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                        </div>
                </div>
            </form>
            <div class="col-md-12" style="margin-top: 10px">
                <legend>
                    <div class="pull-right" style="font-size: 16px; margin: 5px" >Total: {{$cantidad}} examen(es) encontrado(s)
                     </div>
                </legend>
            </div>                
            <div class="col-md-12">
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar examen">
            </div>

            <div class="col-md-12"  id="global">
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Nro. Prestacion</th>
                            <th>Nro. Ex. Rad</th>
                            <th>Especialidad</th>
                            <th>Hora<br/>Registro</th>
                            <th>Fecha<br/>Orden</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>
                            <th>Medico</th>
							<th style="text-align:center">Pdf Atencion</th>
							<th style="text-align:center">Pdf Examen</th>
							<th>Resultado</th>
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
                            <td>{{$r->id}}</td>
                            <td>{{$r->id_lab}}</td>
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
                                $originalDate1 = $r->firma_radiologia;
                                $fechaE = date("d-m-Y", strtotime($originalDate1));
                                echo $fechaE;
                                ?> 
                            </td>
                            <td>{{$r->tipo_doc_ident}}</td> 
                            <td>{{$r->nro_doc_ident}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <!--td></td--> 
                            <td>{{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}</td>
							<td class="text-center">
								<?php 
								if ($r->ruta_radiologia == "") {
								?>
									<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
								<?php 
								} else {
								?>
									<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
										<a href="/{{$r->flag}}/{{$r->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
									</div>
								<?php 
								}
								?>
                            </td>
							<td class="text-center">
								<?php 
								if ($r->ruta_radiologia == "") {
								?>
									<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
								<?php 
								} else {
								?>
									<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Examen</b>">
										<a href="/{{$r->flag}}/{{$r->ruta_radiologia}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
									</div>
								<?php 
								}
								?>
                            </td>
							
							<td class="text-center">
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Resultado</b>">
                                    <a href="/print_examen_radiologia/{{$r->id}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
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
