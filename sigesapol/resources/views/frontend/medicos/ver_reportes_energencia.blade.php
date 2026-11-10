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
        /*width: auto;*/
        /* width: 100%; */
        /* border: 1px solid #ddd; */ 
        margin-top:15px
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
	
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
    //--------------------------------------------------------------------------
    $(document).ready(function () {

        $('#fecha_ini').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_fin').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_ini').click(function () {
            var fechaDesdeIni = $('#fecha_ini').val();
        
            $('#fecha_ini').change(function () {
                var fechaDesde = $('#fecha_ini').val();
                var fechaHasta = $('#fecha_fin').val();
                f1 = fechaDesde.split("-");
                f2 = fechaHasta.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

                if (f1 > f2) {
                    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                    $('#fecha_ini').val(fechaDesdeIni);
                    return false;
                }
            });
        });

        $('#fecha_fin').click(function () {
            var fechaHastaIni = $('#fecha_fin').val();
            $('#fecha_fin').change(function () {
                var fechaDesde = $('#fecha_ini').val();
                var fechaHasta = $('#fecha_fin').val();
                f1 = fechaDesde.split("-");
                f2 = fechaHasta.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

                if (f2 < f1) {
                    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                    $('#fecha_fin').val(fechaHastaIni);
                    return false;
                }
    	
            });
        });

        $('#fecha_fin').trigger('change');
        
        $('#fecha_ini').trigger('change');
        
        var activeSystemClass = $('.list-group-item.active');
        
    });
    
    function reporteEmergenciaTriaje(idUser){

    		var fecha_ini = $('#fecha_ini').val();
    		var fecha_fin = $('#fecha_fin').val();
    		
            if (fecha_ini == "")fecha_ini = 0;
    		if (fecha_fin == "")fecha_fin = 0;
    		
            location.href = 'exportar_reporte_emergencia_triaje/' + idUser + '/' + fecha_ini + '/' + fecha_fin ;
            
    		
    }
	
    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }


    function Edad(dateString) 
    {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
        {
            age--;
        }
        return age;
    }

    var m = 0;

    function guardarAltaMedica() {
       
            $('#frmBuscaReporte').submit();
       
    }

    function anularAtencion(id_atencion){
    
        bootbox.confirm({ 
            size: "small",
            message: "&iquest;Deseas finalizar la atenci&oacute;n de triaje?", 
            callback: function(result){
                if (result==true) {
                    fn_anularAtencion(id_atencion);
                }
            }
        });
        
    }
    function reporte(idopcion){

        var fecha_ini = $('#fecha_ini').val();
        var fecha_fin = $('#fecha_fin').val();


        if (fecha_ini == "")fecha_ini = 0;
        if (fecha_fin == "")fecha_fin = 0;

        switch (idopcion){
            case 1: location.href = 'exportar_reporte_emergencia_1/' + fecha_ini + '/' + fecha_fin ;break;
            case 2: location.href = 'exportar_reporte_emergencia_2/' + fecha_ini + '/' + fecha_fin ;break;
            case 3: location.href = 'exportar_reporte_emergencia_3/' + fecha_ini + '/' + fecha_fin ;break;
            case 4: location.href = 'exportar_reporte_emergencia_4/' + fecha_ini + '/' + fecha_fin ;break;
            case 5: location.href = 'exportar_reporte_emergencia_5/' + fecha_ini + '/' + fecha_fin ;break;
            case 6: location.href = 'exportar_reporte_emergencia_6/' + fecha_ini + '/' + fecha_fin ;break;
            case 7: location.href = 'exportar_reporte_emergencia_7/' + fecha_ini + '/' + fecha_fin ;break;
            case 8: location.href = 'exportar_reporte_emergencia_8/' + fecha_ini + '/' + fecha_fin ;break;
            case 9: location.href = 'exportar_reporte_emergencia_9/' + fecha_ini + '/' + fecha_fin ;break;
            case 10: location.href = 'exportar_reporte_emergencia_10/' + fecha_ini + '/' + fecha_fin ;break;
            case 11: location.href = 'exportar_reporte_emergencia_11/' + fecha_ini + '/' + fecha_fin ;break;
            case 12: location.href = 'exportar_reporte_emergencia_12/' + fecha_ini + '/' + fecha_fin ;break;
            case 13: location.href = 'exportar_reporte_emergencia_13/' + fecha_ini + '/' + fecha_fin ;break;
            case 14: location.href = 'exportar_reporte_emergencia_14/' + fecha_ini + '/' + fecha_fin ;break;
            case 15: location.href = 'exportar_reporte_emergencia_15/' + fecha_ini + '/' + fecha_fin ;break;
            case 16: location.href = 'exportar_reporte_emergencia_16/' + fecha_ini + '/' + fecha_fin ;break;
            case 17: location.href = 'exportar_reporte_emergencia_17/' + fecha_ini + '/' + fecha_fin ;break;
            case 18: location.href = 'exportar_reporte_emergencia_18/' + fecha_ini + '/' + fecha_fin ;break;
            case 19: location.href = 'exportar_reporte_emergencia_19/' + fecha_ini + '/' + fecha_fin ;break;
            case 20: location.href = 'exportar_reporte_emergencia_20/' + fecha_ini + '/' + fecha_fin ;break;
            case 21: location.href = 'exportar_reporte_emergencia_21/' + fecha_ini + '/' + fecha_fin ;break;
            case 22: location.href = 'exportar_reporte_emergencia_22/' + fecha_ini + '/' + fecha_fin ;break;
        }

    }

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista de los Usuarios Modificados </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" id="frmBuscaReporte" action="{{ route('buscar_reporte_alta')}}">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
    			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
    			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}">
    			<div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <div class="col-sm-12">
                                <div class="col-sm-2">
                                    <label class="form-control-sm">Fecha Desde</label>
                                    <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-control-sm">Fecha Hasta</label>
                                    <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10">
                                </div>  
                            </div>           
                        </div>
                    </div>
                    
                    <div class="col-sm-12"><br/><br/>
                        <table id="tblReportes" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>OPCIONES</th>
                                    <th>DESCARGAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.-</td>
                                    <td>FECHA DE INGRESO, EGRESO, SEXO, PARENTESCO, EDAD</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(1)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.-</td>
                                    <td>DEFUNCIONES</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(2)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3.-</td>
                                    <td>POR PRIORIDAD</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(3)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4.-</td>
                                    <td>POR TRIAJE</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(4)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5.-</td>
                                    <td>MORBILIDAD URGENCIA MAYORES (II)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(5)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6.-</td>
                                    <td>MORBILIDAD URGENCIA MENORES (III)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(6)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7.-</td>
                                    <td>MORBILIDAD DE EMERGENCIA (PRIORIDAD I)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(7)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8.-</td>
                                    <td>MORBILIDAD DE PATOLOGIAS COMUNES (PRIORIDAD IV)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(8)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>9.-</td>
                                    <td>PERFILES EPIDEMIOLOGICOS (GENERAL)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(9)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>10.-</td>
                                    <td>PERFIL EPIDEMIOLOGICOS (NIÑOS DE 0 DIAS A 13 AÑOS, 11 MESES Y 29 DIAS)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(10)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>11.-</td>
                                    <td>PERFIL EPIDEMIOLOGICO ADULTOS (APARTIR DE LOS 14 AÑOS A MAS)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(11)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>12.-</td>
                                    <td>REFERIDOS</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(12)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>13.-</td>
                                    <td>CONTRAREFERIDOS</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(13)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>14.-</td>
                                    <td>REINGRESOS</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(14)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.-</td>
                                    <td>REGISTROS DIARIOS(CONSOLIDADO)</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(15)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>16.-</td>
                                    <td>HOSPITALIZACIONES</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(16)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>17.-</td>
                                    <td>ALTA</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(17)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>18.-</td>
                                    <td>FUGA</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(18)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>19.-</td>
                                    <td>RETIRO VOLUNTARIO</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(19)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20.-</td>
                                    <td>DIAS DE ESTANCIA EN EMERGENCIA</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(20)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>21.-</td>
                                    <td>NOTIFICACIÓN SEMANAL EPIDEMIOLÓGICO</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(21)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>22.-</td>
                                    <td>ENFERMEDADES DE NOTIFICACIÓN OBLIGATORIA</td>
                                    <td><a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporte(22)" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                    </a>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
            </form>
           <!-- <div class="col-md-12"  id="global">
                <div class="table-responsive">
                    
                </div> 
            </div>-->
            <!-- <div class="form-group"> -->
                <!--
            <div class="col-md-12"  id="global">
                <div class="table-responsive">
                    <table id="tblSuperUsuario" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Fecha Edit</th>
                                <th>Hora Edit</th>
                                <th>Estado</th>
                                <th>DNI User</th>
                                <th>Datos Usuario</th>
                                <th>Se Actualizo</th>
                                <th>Detalle Actualizacion Datos</th>
                                <th>Detalle Actualizacion Flags</th>
                                <th>Establecimiento Usuario</th>
                                <th>DNI Editador</th>
                                <th>Datos Editador</th>
                                <th>Establecimiento Editador</th>
                                <th>Antiguedad de Usuario</th>
                                <th>Historial Permisos</th>
                                <th>Bitacora de Modificaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
                    -->
            <!-- </div> -->
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
