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
var tblProductividad;

    //--------------------------------------------------------------------------
$(document).ready(function () {

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();

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
      tblProductividad = $('#tblProductividad').DataTable({
      /*"searching": false*/
      "language": {
      "emptyTable": "No se encontraron resultados"
      }
      });

      $("#system-search").keyup(function() {
      var dataTable = $('#tblProductividad').dataTable();
      dataTable.fnFilter(this.value);
      });

    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }


    $('#btnBuscar').on('click', function(){
      productividad_ajax();
    });


    function productividad_ajax(){
      $.ajax({
        url: '/get_productividad_medicos',
        method: 'post',
        data: {
          id_establecimiento : {{ Auth::user()->id_establecimiento }},
          fecha_inicio : $('#fechaDesde').val(),
          fecha_fin : $('#fechaHasta').val()
        },
        success: function (result) {
          console.table(result);
          tblProductividad.destroy();
          tblProductividad = $('#tblProductividad').DataTable({
            "data": result,
            "columns": [
              {"data": "consultorio"},
              {"data": "documento"},
              {"data": "medico"},
              {"data": "citas_atendidas"},
            ]
          });
        }
      })
    }

    function reporteReceta(){
      var fechaDesde = $('#fechaDesde').val();
      var fechaHasta = $('#fechaHasta').val();
      location.href = 'exporta_reporte_productividad_medico/' + fechaDesde + '/' + fechaHasta + '/';
    }
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">PRODUCTIVIDAD POR MEDICO</h3>
        </div>  
        <div class="panel-body">
            <div class="col-sm-10">
                <form class="form-horizontal" method="post" action="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php //echo $fechaDesde ?>" maxlength="10" autocomplete="off">
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php //echo $fechaHasta ?>" maxlength="10" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control btn btn-info input-sm" value="Buscar" id="btnBuscar" type="button">
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <!--<input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar receta">-->
            </div>

            <div class="col-md-12"  id="global">
                <!--<table  class="table table-list-search">-->
                <table id="tblProductividad" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                          <th>Consultorio</th>  
                          <th>Nro DOC</th>
                          <th>Medico</th>
                          <th>Atendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>   
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
