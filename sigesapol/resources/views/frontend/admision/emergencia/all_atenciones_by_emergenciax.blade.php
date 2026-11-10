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
        
    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
            "emptyTable": "No se encontraron resultados"
            }
        });
    
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
    });
    
    
    function getTopico (){ 
    var id_prioridad = $('#id_prioridad').val(); 
    var sexo = $('#sexo').val();
    var edad = $('#edadpaciente').val();
    
    if(id_prioridad!=''){
      //valicion de tipo de listado
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad; 
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
        if(id_prioridad=='2'||id_prioridad=='3'){
            url_consulta='/get_topico_por_id_prioridad_sexo_edad/'+id_prioridad+'/'+sexo+'/'+edad;
        }  
       
        $.ajax({
          url: url_consulta,
          success: function(result) {   
            $('#id_topico').empty();  
            $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>"); 
            $(result).each(function(ii, oo) {  
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
            }); 
            $('#id_topico').val("").change();  
            if(id_prioridad=='1'){
                $('#id_topico').val("1").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='0'){
                $('#id_topico').val("0").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='2'){
                $('#id_topico').val("10").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='3'){
                $('#id_topico').val("11").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='4'){
                $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                $('#id_topico').val("19").change(); 
                $('#observacion').focus(); 
            }else{
                $('#id_topico').val("").change();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='4'){
              $('#id_topico').empty();  
              $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
              $('#id_topico').val("").change(); 
              $('#observacion').focus(); 
            }else{
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
              $('#id_topico').val("19").change(); 
              $('#observacion').focus(); 
            }
          }
        }); 
    }else{
      $('#id_topico').empty();  
      $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
      $('#id_topico').val("").change(); 
    }
  }

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



    $(document).ready(function () {
    	
    	$('#btnBuscar').click(function () {
    		fn_ListarBusqueda();
    	});
    	
    	datatablenew();
    	 	
    });

    

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

    function datatablenew(){
        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_atencion_emergencia_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
    		"language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [[10, 50, 100, 200, 60000], [10, 50, 100, 200, "Todos"]],
            "aoColumns": [
                            {},
            ],
    		"dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var sEcho           = aoData[0].value;
                var iNroPagina 	= parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar 	= aoData[4].value;
    			var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var dni_beneficiario = $('#dni_beneficiario').val();
                var nombre_beneficiario = $('#nombre_beneficiario').val();
                var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                var estado = $('#estado').val();
                var id_prioridad = $('#id_prioridad').val();
                var id_topico = $('#id_topico').val();
                var opcion = $('#opcion').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				"dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,opcion:opcion,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,id_establecimiento:id_establecimiento,estado:estado,id_prioridad,id_topico,
    						_token:_token
                           },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs":
                [	
                   
                    {
                        "mRender": function (data, type, row) {
                            var id = "";
        					if(row.id!= null)id = row.id;
        					return id;
                        },
        				"bSortable": false,
                        "aTargets": [0]
                        },
    				{
                        "mRender": function (data, type, row) {
                            var fecha_ingreso = "";var hora_ingreso = "";
                            if(row.fecha_registro_ingreso!= null)fecha_ingreso = row.fecha_registro_ingreso;
                            if(row.hora_registro_ingreso!= null)hora_ingreso = row.hora_registro_ingreso;
                            return fecha_ingreso + ' ' + hora_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var fecha_alta = "";var hora_atencion = "";var estado_de = $('#estado').val();
                            if(estado_de==10){
                            if(row.fecha_alta_medica_ama!= null)fecha_alta = row.fecha_alta_medica_ama;
                            if(row.hora_alta_medica_ama!= null)hora_atencion = row.hora_alta_medica_ama;
			                                
				}
                            else{
                            if(row.fecha_alta_medica!= null)fecha_alta = row.fecha_alta_medica;
                            if(row.hora_alta_medica!= null)hora_atencion = row.hora_alta_medica;
				}

                            return fecha_alta + ' ' + hora_atencion ;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var dni_beneficiario = "";
                            if(row.dni_beneficiario!= null)dni_beneficiario = row.dni_beneficiario;
                            return dni_beneficiario;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var asegurado = "";
                            if(row.paterno!= null)asegurado += row.paterno;
                            if(row.materno!= null)asegurado += " "+row.materno;
                            if(row.nombre!= null)asegurado += " ,"+row.nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var grado = "";
                              if(row.grado!= null){ 
                                  grado = row.grado;
                              }
                              else{
                                if (row.parentesco!='TITULAR'){
                                  grado = row.grado2;
                                }
                              }
                              return grado;
                        },
                        "bSortable": false,
                        "aTargets": [5]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var parentesco = "";
                            if(row.parentesco!= null)parentesco = row.parentesco;
                                return parentesco;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var sexo = "";
                            if(row.sexo!= null)sexo = row.sexo;
                            return sexo;
                        },
                        "bSortable": false,
                        "aTargets": [7]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.edadpaciente!= null)edad = row.edadpaciente;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [8] 
                        
                    },
                    {
                            "mRender": function(data, type, row) {
                              var telefono = "";
                              if (row.telefono_paciente != null) telefono = row.telefono_paciente;
                              return telefono;
                            },
                            "bSortable": false,
                            "aTargets": [9]
                          },
                    {
                        "mRender": function (data, type, row) {
                            var prioridad = "";
                            if(row.prioridad!= null)prioridad = row.prioridad;
                            return prioridad;
                        },
                        "bSortable": false,
                        "aTargets": [10]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var descripcion = "";
                            if(row.descripcion!= null)descripcion = row.descripcion;
                            return descripcion;
                        },
                        "bSortable": false,
                        "aTargets": [11]
                        
                    },
    				{
                        "mRender": function (data, type, row) {
    					   var html = "";var estado_de = $('#estado').val();
                           if(estado_de==10){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Médica</b>" style="float:left;padding-left:3px"><button class="btn btn-warning btn-xs" disabled=""><i class="fa fa-edit"></i></button></div>';
                           }
                           else{
                           if(row.estado==3){
    					           html +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Médica</b>">';
                                    html += '<a href="/alta_medica/<?php echo Auth::user()->id ?>/' + row.id +
                                    '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                            }else if(row.estado==4){
    					           html +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Médica</b>">';
                                    html += '<a href="/alta_medica/<?php echo Auth::user()->id ?>/' + row.id +
                                    '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                            }else{
                                if(row.estado==9){
                                    html +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Firmar</b>">';
                                    html += '<a href="/<?php echo Auth::user()->id ?>/' + row.id +
                                    '" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a></div>';
                                }else{
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Médica</b>" style="float:left;padding-left:3px"><button class="btn btn-warning btn-xs" disabled=""><i class="fa fa-edit"></i></button></div>';
                                }
                            }
                            }   
    					   return html;
                        },
                        "bSortable": false,
                        "aTargets": [12]
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";var estado_de = $('#estado').val();
                           if(estado_de==10){
                                  html +=
                                            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Alta Médica</b>">';
                                            html += '<a href="/ver_alta_medica_anulada/<?php echo Auth::user()->id ?>/' + row.id +
                                            '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                           }
                           else{
                           if( row.estado==5){
                            html +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Alta Médica</b>">';
                                    html += '<a href="/alta_medica_ver/<?php echo Auth::user()->id ?>/' + row.id +
                                    '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                                
                            }else{
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Médica</b>" style="float:left;padding-left:3px"><button class="btn btn-info btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                           }  
                           }
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [13]
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";var estado_de = $('#estado').val();
                           if(estado_de==10){
                                 html +=
                                            '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                           }
                           else{

                           if(row.ruta_egreso!= null)ruta = row.ruta_egreso;

                           if(row.estado==5){

                            if(row.ruta_egreso!= null){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>" style="float:left;padding-left:3px">';
                               
                                html += '<a href="' + row.ruta_egreso +
                                        '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                            }
                            else{
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>" style="float:left;padding-left:3px">';
                               
                                html += '<a href="pdf_alta_medica/<?php echo Auth::user()->id ?>/' + row.id +
                                        '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';  
                            };


                                  

                                        if( row.tipo_egreso==2){
                                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>INDICACION-HOSP</b>" style="float:left;padding-left:3px">';
                                            html += '<a href="pdf_indicaciones/<?php echo Auth::user()->id ?>/' + row.id +
                                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';      
                                            }else{
                                                
                                            }
                                            if( row.tipo_egreso==4){
                                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>EPICRISIS FALLECIDO</b>" style="float:left;padding-left:3px">';
                                            html += '<a href="pdf_epicrisis_emer/<?php echo Auth::user()->id ?>/' + row.id +
                                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';      
                                            }else{
                                                
                                            }
                                            if( row.tipo_egreso==3){
                                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>EPICRISIS TRASLADO</b>" style="float:left;padding-left:3px">';
                                            html += '<a href="pdf_epicrisis_emer/<?php echo Auth::user()->id ?>/' + row.id +
                                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';   

                                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>HOJA TRASLADO</b>" style="float:left;padding-left:3px">';
                                            html += '<a href="pdf_ref_contra_emer/<?php echo Auth::user()->id ?>/' + row.id +
                                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';   
                                            }else{
                                                
                                            }
                           }else{
                                html +=
                                        '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                           }
                            }
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [14]
                    },
                    <?php if (Auth::user()->flagfiscalizador==1): ?>
                    {
                        "mRender": function (data, type, row) {
                           var html = "";var estado_de = $('#estado').val();
                           if(estado_de==10){
                                 html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Alta Médica</b>" style="float:left;padding-left:3px"><button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button></div>';
                           }
                           else{

                               if( row.estado==5){
                                html +=
                                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Alta Médica</b>">';
                                        html += '<a href="/anular_alta_medica_ver/<?php echo Auth::user()->id ?>/' + row.id +
                                        '" class="btn btn-danger btn-xs"><i class="fa fa-xing"></i></a></div>';
                                    
                                }else{
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Alta Médica</b>" style="float:left;padding-left:3px"><button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button></div>';
                               }
                            }  
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [15]
                    },
                    <?php endif; ?>
                ]
        });
    }

    function fn_ListarBusqueda() {
       datatablenew();
    }
    function reporteAtencion(idUser) {

var fecha_ini = $('#fecha_ini').val();
var fecha_fin = $('#fecha_fin').val();
var dni = $('#dni_beneficiario').val();
var paciente = $('#nombre_beneficiario').val();
var estado = $('#estado').val();
var id_prioridad = $('#id_prioridad').val();
var id_topico = $('#id_topico').val();


if (fecha_ini == "") fecha_ini = 0;
if (fecha_fin == "") fecha_fin = 0;
if (dni == "") dni = 0;
if (paciente == "") paciente = 0;
if (id_prioridad == "") id_prioridad = 0;
if (id_topico == "") id_topico = 0;

location.href = 'exportar_reporte_alta_emergencia/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' +
  paciente+ '/' + estado+ '/' + id_prioridad+ '/' + id_topico;

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

    function fn_anularAtencion(id){
        
        $.ajax({
            url: '/cerrar_emergencia_triaje/' + id,
            dataType: "json",
            success: function(result){
                
                if(result.sw == true){
                    location.href="all_triajes_atendidas_by_emergencia";
                }
            }
        });
    }

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista Para Dar El Alta Médica - Emergencia </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_atencion_emergencia_ajax')}}">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
    			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
    			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}">
    			<div class="row">
        			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        				<label class="form-control-sm">Fecha Desde</label>
        				<input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
        			</div>
        			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        				<label class="form-control-sm">Fecha Hasta</label>
        				<input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10">
        			</div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label class="form-control-sm">Nro. Documento</label>
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                          class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                      </div>

                      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="form-control-sm">Nombres</label>
                        <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                          class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
                      </div>
        			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
        				
        			</div>
    			</div>
                <div class="row">
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Prioridad</label>
                    <select id="id_prioridad" name="id_prioridad"   onchange="getTopico();" class="form-control">
                      <option value="0">-Seleccionar-</option>
                      <?php //foreach($opcionesprioridad as $e) ?>
                        <!--option value="{//{$e->id}}">{//{$e->descripcion}}</option-->
                        <option value="1">I - GRAVEDAD SÚBITA EXTREMA</option>
                        <option value="2">II - URGENCIA MAYOR</option>
                        <option value="3">III - URGENCIA MENOR</option>
                      <?php //endforeach ?>
                    </select>
                  </div>
                  
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                      <label class="form-control-sm">Area</label>
                      <select class="form-control input-sm" name="id_topico" id="id_topico" required="" style="width: 100%">
                        <option value="0">-Seleccionar-</option>
                        <option value="1">SHOCK TRAUMA</option>
                        <option value="2">OBS TRAUMATOLOGIA</option>
                        <option value="4">OBS PEDIATRIA</option>
                        <option value="6">OBS GINECOLOGÍA Y OBSTETRICIA</option>
                        <option value="10">OBS CIRUGIA</option>
                        <option value="9">OBS MEDICINA</option>
                        <option value="3">TOP TRAUMATOLOGIA</option>
                        <option value="5">TOP PEDIATRIA</option>
                        <option value="7">TOP GINECOLOGÍA Y OBSTETRICIA</option>
                        <option value="11">TOP CIRUGIA</option>
                        <option value="8">TOP MEDICINA</option>
                      </select>
                    </div>
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Estado</label>
                        <select class="form-control" name="estado"  id="estado" tabindex="15">
                            <option value="4" <?php if($estado == 4)echo "selected='selected'";?>>Pendiente</option>
                            <option value="5" <?php if($estado == 5)echo "selected='selected'";?>>Atendida</option>
                            <?php if (Auth::user()->flagfiscalizador==1): ?>
                                <option value="10" <?php if($estado ==10)echo "selected='selected'";?>>Anulado</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                            onclick="reporteAtencion('{{ Auth::user()->id }}')">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                        </a>
                    </div>
                </div>
            </form>
            <!-- <div class="form-group"> -->
            <div class="col-md-12"  id="global">
                <div class="table-responsive">
                    <table id="tblEmergenciaTriaje" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Fecha Ingreso</th>
                                <th>Fecha Alta</th>
                                <th>Numero Doc</th>
                                <th>Paciente</th>
                                <th>Grado</th>
                                <th>Parentesco</th>
                                <th>Sexo</th>
                                <th>Edad</th>
                                <th>Telefono</th>
                                <th>Prioridad</th>
                                <th>Area</th>
                                <th style="text-align:center">Alta Médica</th>
                                <th style="text-align:center">Ver Alta</th>
                                <th style="text-align:center">PDF</th>
                                <?php if (Auth::user()->flagfiscalizador==1): ?>
                                <th style="text-align:center">Anular</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
