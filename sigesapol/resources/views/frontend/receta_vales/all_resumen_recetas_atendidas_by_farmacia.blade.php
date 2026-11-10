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

</style>

<script>
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        $consul = $("#frmRequerimiento").find("#id_requerimiento");
        $consul.select2();
        /*
         $('#fechaDesde').appendDtpicker({
         "autodateOnStart": false,
         "minuteInterval": 5,
         "amPmInTimeList": true,
         "locale": "es",
         "dateFormat": "YYYY-MM-DD hh:mm",
         });
         
         $('#fechaHasta').appendDtpicker({
         "autodateOnStart": false,
         "minuteInterval": 5,
         "amPmInTimeList": true,
         "locale": "es",
         "dateFormat": "YYYY-MM-DD hh:mm",
         });
         */


        $('#fechaDesde').appendDtpicker({
            "closeOnSelected": true,
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": true,
            "locale": "es",
            "dateFormat": "DD-MM-YYYY",
        });


        $('#fechaHasta').appendDtpicker({
            "closeOnSelected": true,
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": true,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY",
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

        /*
         $('#fechaHasta').change(function() {
         //$('#fechaDesde').handleDtpicker('destroy');
         $('#fechaDesde').appendDtpicker({
         "futureOnly": true,
         "closeOnSelected": true,
         "dateFormat": "YYYY-MM-DD hh:mm",
         "minTime":"07:00",
         "maxTime":"23:00",
         "locale": "es",
         "amPmInTimeList": true,
         "autodateOnStart": false,
         "minuteInterval": 5,
         //minDate: $('#fechaHasta').val(),
         maxDate: '2017-08-05',
         });
         $('#fechaDesde').trigger('change');
         });
         */
        /*
         $('#fechaHasta').change(function() {
         $('#fechaDesde').appendDtpicker({
         "futureOnly": true,
         "closeOnSelected": true,
         //"dateFormat": "DD/MM/YYYY hh:mm",
         "dateFormat": "YYYY-MM-DD hh:mm",
         "minTime":"07:00",
         "maxTime":"23:00",
         "locale": "es",
         "amPmInTimeList": true,
         "autodateOnStart": false,
         "minuteInterval": 5,
         maxDate: $('#fechaHasta').val() 
         });
         });
         
         $('#fechaDesde').change(function() {
         $('#fechaHasta').appendDtpicker({
         "futureOnly": true,
         "closeOnSelected": true,
         //"dateFormat": "DD/MM/YYYY hh:mm",
         "dateFormat": "YYYY-MM-DD hh:mm",
         "minTime":"07:00",
         "maxTime":"23:00",
         "locale": "es",
         "amPmInTimeList": true,
         "autodateOnStart": false,
         "minuteInterval": 5,
         minDate: $('#fechaDesde').val() 
         });
         });                     
         */
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

        $("#system-search").keyup(function () {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
        /*$("#system-search").keyup(function () {
         var dataTable = $('#example').dataTable();
         dataTable.fnFilter(this.value);
         });*/
    });


    function reporteQuirurgico() {

        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nro_quirurgico = $('#nro_quirurgico').val();

        if (fechaDesde == "")
            fechaDesde = 0;
        if (fechaHasta == "")
            fechaHasta = 0;
        if (dni_beneficiario == "")
            dni_beneficiario = 0;
        if (nro_quirurgico == "")
            nro_quirurgico = 0;
        location.href = 'exportar_reporte_quirurgico/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + dni_beneficiario + '/' + nro_quirurgico;
    }

</script>
@endsection
@section('content')
<div class="container">

    <h4>

        <a href="{{ url('/stocks/create') }}" class="btn btn-primary pull-right btn-sm" style="display:none">
            <i class="fa fa-plus"></i> Agregar Stock
        </a>
    </h4>
    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">Buscar Recetas Atendidas y No Atendidas</h3>
            </div>  
            <div class="panel-body">
                <legend style="text-align: center; text-transform: uppercase;">{{$farmacia->nombre}}</legend>
                <!--recetas_atendidas_by_fecha_and_farmacia-->
                <form class="form-horizontal" method="post" action="{{ route('listar_resumen_recetas_atendidas')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <!--
<div class="col-md-12">Fecha</div>
<div class="col-md-3">
    <input type="text" class="form-control datepicker" name="fecha" required="">
    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
</div>
                    -->

                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-3">
                        <input class="form-control datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>">
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-3">
                        <input class="form-control" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>">
                    </div>
                    <!--
                    <div class="col-md-2">
    <a class="btn btn-success form-control" href="javascript:void(0)" onclick="reporteQuirurgico()" >
        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
    </a>
</div>
                    -->

                    <div class="col-md-2">
                        <input class="form-control btn btn-info" value="Buscar" type="submit">
                    </div>

                </form>
                <div class="col-md-12" style="margin-top: 10px">
                    <legend>
                        Resumen de Recetas Atendidas y No Atendidas
                        <div class="pull-right">Total: {{$cantidad}} resultados encontrados</div>
                    </legend>
                </div>                
                <div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar Resumen">                        
                </div>

                <?php //print_r($recetas); ?>
                <div class="col-md-12" id="global">
                    <!--<table  class="table table-list-search">-->
                    <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                        <thead>
                            <tr>
                                <th colspan="3" style="border:0"></th>
                                <th colspan="5" style="border:1px solid #999999;text-align:center">Recetas Atendidas</th>
                                <th colspan="5" style="border:1px solid #999999;text-align:center">Recetas No Atendidas</th>
                                <th style="border:0"></th>
                            </tr>
                            <tr>
                                <th rowspan="2" style="border:1px solid #999999">N.</th>
                                <th rowspan="2" style="border:1px solid #999999">Dia</th>
                                <th rowspan="2" style="border:1px solid #999999">Responsable</th>
                                <th style="border:1px solid #999999">Nro Receta</th>
                                <th style="border:1px solid #999999">Nro Items</th>
                                <th style="border:1px solid #999999">Nro Unid</th>
                                <th style="border:1px solid #999999">Nro Titular</th>
                                <th style="border:1px solid #999999">Nro Familiar</th>

                                <th style="border:1px solid #999999">Nro Receta</th>
                                <th style="border:1px solid #999999">Nro Items</th>
                                <th style="border:1px solid #999999">Nro Unid</th>
                                <th style="border:1px solid #999999">Nro Titular</th>
                                <th style="border:1px solid #999999">Nro Familiar</th>
                                <th style="border:1px solid #999999">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $nra=0;$nia=0;$nua=0;$nta=0;$nfa=0;$nrna=0;$nina=0;$nuna=0;$ntna=0;$nfna=0;$ntotal=0;
                            if (isset($resumen)):
                                foreach ($resumen as $key => $rowResumen):
                                    ?>
                                    <?php 
                                            $nra=$nra+$rowResumen->_nra;
                                            $nia=$nia+$rowResumen->_nia;
                                            $nua=$nua+$rowResumen->_nua;
                                            $nta=$nta+$rowResumen->_nta;
                                            $nfa=$nfa+$rowResumen->_nfa;
                                            $nrna=$nrna+$rowResumen->_nrna;
                                            $nina=$nina+$rowResumen->_nina;
                                            $nuna=$nuna+$rowResumen->_nuna;
                                            $ntna=$ntna+$rowResumen->_ntna;
                                            $nfna=$nfna+$rowResumen->_nfna;
                                            $ntotal=$ntotal+$rowResumen->_nra + $rowResumen->_nrna;
                                            
                                        ?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td align="left"><?= $rowResumen->_fecha ?></td>
                                        <td align="left"><?= $rowResumen->_usuario ?></td>
                                        <td align="right"><?= $rowResumen->_nra ?></td>
                                        <td align="right"><?= $rowResumen->_nia ?></td>
                                        <td align="right"><?= $rowResumen->_nua ?></td>
                                        <td align="right"><?= $rowResumen->_nta ?></td>
                                        <td align="right"><?= $rowResumen->_nfa ?></td>
                                        <td align="right"><?= $rowResumen->_nrna ?></td>
                                        <td align="right"><?= $rowResumen->_nina ?></td>
                                        <td align="right"><?= $rowResumen->_nuna ?></td>
                                        <td align="right"><?= $rowResumen->_ntna ?></td>
                                        <td align="right"><?= $rowResumen->_nfna ?></td>
                                        <td align="right"><strong><?= $rowResumen->_nra + $rowResumen->_nrna ?></strong></td>
                                        
                                    </tr>
                                <?php endforeach; ?>
                                <tr>    <td colspan="3"><strong>TOTAL</strong></td>
                                        <td align="right"><strong><?= $nra ?></strong></td>
                                        <td align="right"><strong><?= $nia ?></strong></td>
                                        <td align="right"><strong><?= $nua ?></strong></td>
                                        <td align="right"><strong><?= $nta ?></strong></td>
                                        <td align="right"><strong><?= $nfa ?></strong></td>
                                        <td align="right"><strong><?= $nrna ?></strong></td>
                                        <td align="right"><strong><?= $nina ?></strong></td>
                                        <td align="right"><strong><?= $nuna ?></strong></td>
                                        <td align="right"><strong><?= $ntna ?></strong></td>
                                        <td align="right"><strong><?= $nfna ?></strong></td>
                                        <td align="right"><strong><?= $ntotal ?></strong></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection
