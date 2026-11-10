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
    width: 100%;
    border: 1px solid #ddd;
    margin: 15px
      /* background: #f1f1f1;*/
      /*overflow-y: scroll !important;*/
  }

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .row {
    margin-top: 10px;
    padding: 0 10px;
  }

  .clickable {
    cursor: pointer;
  }

  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }

  .panel-body {
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

  .modal-dialog {
    width: 70% !important;
  }
</style>
<script src='../../funciones/function.js'></script>
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
    
    
    function reporteReferencia(idUser){
		var fecha_ini = $('#fecha_ini').val();
		var fecha_fin = $('#fecha_fin').val();
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nombre_beneficiario = $('#nombre_beneficiario').val();
        var estado = $('#estado').val();
        var id_establecimiento = $('#buscar_ipress_origen').val();
        var buscar_servicio = $('#buscar_servicio').val();
        var buscar_servicio_destino = $('#buscar_servicio_destino').val();
    		
        if (fecha_ini == "")fecha_ini = 0;
    	if (fecha_fin == "")fecha_fin = 0;
        if (dni_beneficiario == "")dni_beneficiario = 0;
        if (nombre_beneficiario == "")nombre_beneficiario = 0;

    	location.href = 'exportar_reporte_referencia/' + fecha_ini + '/' + fecha_fin + '/' + dni_beneficiario + '/' + nombre_beneficiario+ '/' + estado+ '/' + id_establecimiento+ '/' + buscar_servicio+ '/' + buscar_servicio_destino;

    }
	
    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }
$(document).on('click', '.btn-ver-comentario', function () {
    const id = $(this).data('id');

    $.ajax({
        url: '/resultado_validacion_referencia/' + id, 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#modalAprobado').text(data.flag_valida_ref == 1 ? 'Aprobado' : 'Rechazado'); 
            $('#modalComentarioTexto').text(data.recomendaciones || 'Sin comentario');
            $('#modalPersona').text(data.usuario_nombre || 'Desconocido');  

            $('#modalComentario').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
            alert('No se pudo cargar la información del comentario.');
        }
    });
});

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
            "sAjaxSource": "/listar_referencia_ajax",
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
                var id_establecimiento = $('#buscar_ipress_origen').val();
                var estado = $('#estado').val();
                var buscar_servicio = $('#buscar_servicio').val();
                var buscar_servicio_destino = $('#buscar_servicio_destino').val();

    			var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				      "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,id_establecimiento:id_establecimiento
                ,estado:estado,buscar_servicio:buscar_servicio,buscar_servicio_destino:buscar_servicio_destino,
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
                            var fecha_atencion = "";
        					if(row.fecha_atencion!= null)fecha_atencion = row.fecha_atencion;
        					return fecha_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nombre_establecimiento = "";
                            if(row.establecimiento!= null)nombre_establecimiento = row.establecimiento;
                            return nombre_establecimiento;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nombre_servicio_referencia = "";
                            if(row.nombre_servicio_referencia!= null)nombre_servicio_referencia = row.nombre_servicio_referencia;
                            return nombre_servicio_referencia;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var medico = "";
                            if(row.paterno_medico!= null)medico += row.paterno_medico;
                            if(row.materno_medico!= null)medico += " "+row.materno_medico;
                            if(row.nombre_medico!= null)medico += " ,"+row.nombre_medico;
                            return medico;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nro_doc_ident = "";
        					if(row.nro_doc_ident!= null)nro_doc_ident = row.nro_doc_ident;
        					return nro_doc_ident;
                        },
                        "bSortable": false,
                        "aTargets": [5]
        				
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
                        "mRender": function (data, type, row) {
                          var grado = "";
                            if(row.grado!= null)grado = row.grado;
                            return grado;
                        },
                        "bSortable": false,
                        "aTargets": [9] 
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                          var nombre_servicio_destino = "";
                            if(row.nombre_servicio_destino!= null)nombre_servicio_destino = row.nombre_servicio_destino;
                            return nombre_servicio_destino;
                        },
                        "bSortable": false,
                        "aTargets": [10] 
                        
                    },
    				        {
                        "mRender": function (data, type, row) {
                            var html = "";
                            
                            if(row.estado_proceso==1 && row.id_establecimiento == {{Auth::user()->id_establecimiento}}){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formulario</b>" style="float:left;padding-left:3px">';
                              html += '<a href="/form_solicitud/'+row.id+'/'+row.id_asegurado+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';       
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formulario</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }	
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [11]
        				
                    },
                    {
                      "mRender": function (data, type, row) {
                            var html = "";
                             if(row.referencias_ipress_no_pnp == '1'){
                                    html = "  REFERIDO A IPRESS NO PNP";
                          }else{
                            if(row.estado_proceso>1){
html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF ref</b>" style="float:left;padding-left:3px">';
html += '<a href="/pdf_referencia_ver_antes/'+row.id_prestacion+'/'+row.id+'" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';    
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF ref</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }	 }	
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [12]
                    },
                    {
                        "mRender": function (data, type, row) {
                          var html = "";
                           if(row.referencias_ipress_no_pnp == '1'){
                                    html = "  REFERIDO A IPRESS NO PNP";
                          }else{
                          if(row.id_establecimiento_des == {{Auth::user()->id_establecimiento}} ){
                              var asegurado = "";
                              if(row.paterno!= null)asegurado += row.paterno;
                              if(row.materno!= null)asegurado += " "+row.materno;
                              if(row.nombre!= null)asegurado += " ,"+row.nombre;

                                if(row.estado_proceso>1 || row.estado_proceso<4 ){
                                if(row.flag_valida_ref==1){

                                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validado</b>" style="float:left;padding-left:3px">'; 
                                  html += '<a href="#" class="btn btn-success btn-xs" disabled=""><i class="fa fa-thumbs-up"></i></a></div>';      
html += `
<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Comentario</b>" style="float:left;padding-left:3px">
  <a href="javascript:void(0)" 
     class="btn btn-xs btn-ver-comentario" 
     data-id="${row.id}"
     style="background-color:#007bff; border:1px solid white; color:white;">
     <i class="fa fa-eye" style="color:white;"></i>
  </a>
</div>
`;


                                }else if(row.flag_valida_ref==2){

                                  html += '<a href="javascript:void(0)" aria-hidden="true" data-asegurado="'+asegurado+'" data-servicio="'+row.nombre_servicio+'"  data-id_referencia="'+row.id+'" id="validarReferencia" data-toggle="tooltip" title="Rechazado"'+
                                          'class="btn btn-warning btn-xs "> <i class="fa fa-thumbs-down"></i></a>';  
html += `
<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Comentario</b>" style="float:left;padding-left:3px">
  <a href="javascript:void(0)" 
     class="btn btn-xs btn-ver-comentario" 
     data-id="${row.id}"
     style="background-color:#003366; border:1px solid white; color:white;">
     <i class="fa fa-eye" style="color:white;"></i>
  </a>
</div>
`;

                                }else if(row.flag_valida_ref==0){

                                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anulado</b>" style="float:left;padding-left:3px">';  
                                  html += '<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></a></div>';            
                                }else{

                                  html += '<a href="javascript:void(0)" aria-hidden="true" data-asegurado="'+asegurado+'"  data-servicio="'+row.nombre_servicio+'" data-id_referencia="'+row.id+'" id="validarReferencia" data-toggle="tooltip" title="Validar referencia"'+
                                          'class="btn btn-info btn-xs "> <i class="fa fa-pencil-square-o"></i></a>';
                                }
                              }else{
                                html += '<a href="#" class="btn btn-primary btn-xs" disabled=""><i class="fa fa-pencil-square-o"></i></a></div>';     
                                  html += `
<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Comentario</b>" style="float:left;padding-left:3px">
  <a href="javascript:void(0)" 
     class="btn btn-xs btn-ver-comentario" 
     data-id="${row.id}"
     style="background-color:#003366; border:1px solid white; color:white;">
     <i class="fa fa-eye" style="color:white;"></i>
  </a>
</div>
`;
                              }
                            }else{
                               if(row.estado_proceso>=3 ){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validar Referencia</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            html += `
<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Comentario</b>" style="float:left;padding-left:3px">
  <a href="javascript:void(0)" 
     class="btn btn-xs btn-ver-comentario" 
     data-id="${row.id}"
     style="background-color:#007bff; border:1px solid white; color:white;">
     <i class="fa fa-eye" style="color:white;"></i>
  </a>
</div>
`;
   } else{
      html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validar Referencia</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
   }
                           } } 
                        return html;
                        },
                        "bSortable": false,
                        "aTargets": [13]
        				
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";
 if(row.referencias_ipress_no_pnp == '1'){
                                    html = "  REFERIDO A IPRESS NO PNP";
                          }else{
                           if(row.estado_proceso==4){
                            if(row.flag==1){
                              if(row.id_establecimiento_des == {{Auth::user()->id_establecimiento}} ){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Cita</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/formulario_cita_referencia/'+row.id+'" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"></i></a></div>';   
                              }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                              } 
                            }else{
                              
                              if(row.id_establecimiento_des == {{Auth::user()->id_establecimiento}} ){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Emergencia Triaje</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/all_emergencia_triaje_ref/'+row.id_tipo_doc_ident+'/'+row.nro_doc_ident+'" class="btn btn-primary btn-xs"><i class="fa fa-ambulance"></i></a></div>';
                                
                              }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                              } 

                            
                            }
                              
                            
                           }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                           }  }   
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [14]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                          var estado = "";
                          if(row.referencias_ipress_no_pnp == '1'){
                                    estado = "  REFERIDO A IPRESS NO PNP";
                          }else{
                          switch (row.estado_proceso) {
                                case 1:        estado =                            "Referencia Pendiente";   break;
                                case 2:        estado =                           "Referencia Solicitada";   break;
                                case 3:        estado =                           "Referencia Invalidada";   break;
                                case 4:        estado =                             "Referencia Validado";   break;
                                case 5:      
                                  if(row.flag == 1){
                                          estado =      "Referencia Cita Registrada";
                                      } else{ 
                                          estado =      "Referencia Triaje Registrada";
                                        }   break;
                                case 6:        estado =                      "Contrareferencia Pendiente";   break;
                                case 7:        estado =                      "Contrareferencia Culminada";   break;
                                case 8:        estado =       "Contrareferencia Culminada Por Emergencia";   break;
                                case 9:        estado =   "Contrareferencia Corte Culminado enEmergencia";   break;
                                case 10:        estado =   "Proceso Finalizado";   break;
                                default: estado = "---";  break;
                              } 
                          }

                            return estado;
                        },
                        
                        "bSortable": false,
                        "aTargets": [15]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";
                           if(row.estado_proceso==5){
                            if(row.flag == 1){
                                if(row.id_establecimiento_des == {{Auth::user()->id_establecimiento}} || row.id_establecimiento == {{Auth::user()->id_establecimiento}}){
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px">';
                                    html += '<a href="/ver_cita_referido/'+row.id_registrador_cita+'/'+row.id_cita_triaje_referencia+'" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';       
                                    
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px">';
                                    html += '<a href="/ver_anexo_3/'+row.id+'/'+row.id_cita_triaje_referencia+'" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';         
                                }else{
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                                }
                            }else{
                                if(row.id_establecimiento_des == {{Auth::user()->id_establecimiento}} || row.id_establecimiento == {{Auth::user()->id_establecimiento}}){
                                      html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px">';
                                      html += '<a href="/PdfEmergenciaTriaje/'+row.id_cita_triaje_referencia+'" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';         
                                  }else{
                                      html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                                  }
                            }

                           
                           }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Cita</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                           }    
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [16]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                          var destino_establecimiento = "";
                            if(row.destino_establecimiento!= null)destino_establecimiento = row.destino_establecimiento;
                            return destino_establecimiento;
                        },
                        "bSortable": false,
                        "aTargets": [17]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var html = "";
                            
                            if(row.estado_proceso==6){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formulario</b>" style="float:left;padding-left:3px">';
                              html += '<a href="/form_solicitud_contrareferencia/'+row.id+'/'+row.id_asegurado+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';       
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formulario</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }	
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [18]
        				
                    },
                    {
                        "mRender": function (data, type, row) {
                          var ipress_cref = "";
                            if(row.ipress_cref!= null)ipress_cref = row.ipress_cref;
                            return ipress_cref;
                        },
                        "bSortable": false,
                        "aTargets": [19]
                        
                    },
                ]
        });
    }

    function fn_ListarBusqueda() {
       datatablenew();
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
    $('body').on('click', '#validarReferencia', function () {
        var id_referencia = $(this).data('id_referencia');
        var asegurado = $(this).data('asegurado');
        var servicio = $(this).data('servicio');
        console.log(servicio);
        $('#nombre_asegurado').html(asegurado);
        $('#nombre_servicio').html(servicio);
        $('#id_referencia').val(id_referencia);
        $('#tituloFormularioReferencia').html("Validar Referencia");
        $('#verFormValidacionReferencia').modal('show');
    });
    $('#rechazar').click(function () {
      var id_referencia = $('#id_referencia').val();
      var rechazar = $('#rechazar').val();
      var r = confirm("¿Seguro de Rechazar la referencia?");
      if (r == true && rechazar=='2') {
        aprobar_rechazar(id_referencia,2);
      } 
    });
    $('#aprobar').click(function () {
      var id_referencia = $('#id_referencia').val();
      var aprobar = $('#aprobar').val();
      var r = confirm("¿Seguro de Aprobar la referencia?");
      if (r == true && aprobar=='1') {
        aprobar_rechazar(id_referencia,1); 
      }   
    });
    function aprobar_rechazar(id_ref,estado){
      var recomendaciones = $('#recomendaciones').val();
      var postData = {
          id: id_ref,
          flag_valida_ref: estado,
          recomendaciones: recomendaciones
        };
      $.ajax({
          url: 'validar_referencia',
          type: 'POST',
          data: postData,
          success: function(response) {
              console.log('Success:', response);
              datatablenew();
              $('#verFormValidacionReferencia').modal('hide');
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
          }
      });
    }
    
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Referencias Médicas</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_referencia_ajax')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{Auth::user()->id_establecimiento}}">
        <div class="row">
          <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Desde</label>
                  <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
                    value="<?php echo $fechaDesde?>" maxlength="10">
                </div>
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Hasta</label>
                  <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
                    value="<?php echo $fechaHasta?>" maxlength="10">
                </div>
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nro. Documento</label>
                  <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                    class="form-control input-sm" maxlength="12" onkeypress="return esEntero(event);" value="<?php echo $dni_beneficiario ?>">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nombres</label>
                  <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                    class="form-control input-sm text-uppercase" maxlength="60" onkeypress="return noEspaciosInicioSoloLetras(event,'nombre_beneficiario');" value="<?php echo $nombre_beneficiario ?>">
                </div>

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                
                <div class="col-md-6">
                  <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Estado</label>
                        <select class="form-control input-sm " id="estado" name="estado"  tabindex="3">
                          <option value="0">TODOS</option>
                          <option value="1">Referencia Pendiente</option>
                          <option value="2">Referencia Solicitada</option>
                          <option value="3">Referencia Invalidada</option>
                          <option value="4">Referencia Validado</option>
                          <option value="5">Referencia Cita Registrada</option>
                          <option value="6">ContraReferencia Pendiente</option>
                          <option value="7">ContraReferencia Culminada</option>
                        </select>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">SERVICIO ORIGEN</label>
                        <select class="form-control input-sm " id="buscar_servicio" name="buscar_servicio"  tabindex="3">
                          <option value="0">TODOS</option>
                          <?php foreach($servicio as $row):  ?>
                            <option value="<?php echo $row->id?>" ><?php echo $row->nombre?></option>
                          <?php endforeach;?>
                        </select>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">SERVICIO DESTINO</label>
                        <select class="form-control input-sm " id="buscar_servicio_destino" name="buscar_servicio_destino"  tabindex="3">
                          <option value="0">TODOS</option>
                          <?php foreach($servicio as $row):  ?>
                            <option value="<?php echo $row->id?>" ><?php echo $row->nombre?></option>
                          <?php endforeach;?>
                        </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">IPRESS ORIGEN</label>
                        <select class="form-control input-sm " id="buscar_ipress_origen" name="buscar_ipress_origen"  tabindex="3">
                          <option value="0">TODOS</option>
                          <?php foreach($establecimiento as $row):  ?>
                            <option value="<?php echo $row->id?>" ><?php echo $row->nombre?></option>
                          <?php endforeach;?>
                        </select>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12"><br/>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br/>
                    <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                        onclick="reporteReferencia('{{ Auth::user()->id }}')">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                    </a>
                </div>
            </div>
          <!--div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-md-offset-1" style="padding-top:20px;padding-right:0px">
            <div class="col-md-6">
              <a class="btn btn-primary form-control input-sm" href="javascript:void(0)"
                onclick="reporteReferencia('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
            <div class="col-md-6" style="padding-right:0px;padding-left:0px">
              <a class="btn btn-success form-control input-sm" href="{{ url('nueva_referencia') }}">Nuevo</a>
            </div>
          </div-->
        </div>
      </form>
      <div class="col-md-12" id="global">
        <table id="tblEmergenciaTriaje" class="table table-bordered">
          <thead>
            <tr>
            <th>N</th>
          <th>Fecha</th>
          <th>IPRESS Origen de Ref</th>
          <th>Servicio Origen de Ref</th>
          <th>Médico que Refiere</th>
          <th>DNI Paciente</th>
          <th>Paciente</th>
          <th>Sexo</th>
          <th>Edad</th>
          <th>Grado</th>
          <th>Servicio Destino de Ref</th>
          <th style="text-align:center">Derivar Ref</th>
          <th style="text-align:center">PDF Ref</th>
          <th style="text-align:center">Validacíon Ref</th>
          <th style="text-align:center">Registrar Cita/Triaje Ref</th>
          <th style="text-align:center">Estado</th>
          <th style="text-align:center">PFD Cita/Triaje</th>
          <th>IPRESS Destino de Ref</th>
          <th style="text-align:center">Derivar ContraRef</th>
          <th>IPRESS Destino de Contra-Ref</th>
        </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Aprobado:</strong> <span id="modalAprobado"></span></p>
        <p><strong>Comentario:</strong> <span id="modalComentarioTexto"></span></p>
        <p><strong>Realizado por:</strong> <span id="modalPersona"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="verFormValidacionReferencia" tabindex="-1"  role="dialog" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <input type="hidden" name="id_referencia" id="id_referencia">
        <div class="modal-header" style="font-size: 20px !important;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <b id="tituloFormularioReferencia"></b>
        </div>

        <div class="modal-body">

            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" style="font-size: 11px;">
                        <fieldset>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label style="font-size: 14px;">Paciente:  </label>  <span id="nombre_asegurado"></span><br>
                                        <label style="font-size: 14px;">Servicio:  </label>  <span id="nombre_servicio"></span><br>
                                        <label style="font-size: 14px;">
                                          Motivo de validación e invalidación:
                                        </label>
                                        <textarea class="form-control" id="recomendaciones" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left"
                        data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="rechazar" class="btn btn-success" 
                        value="2" >Rechazar</button>
                    <button type="submit" id="aprobar" class="btn btn-success" 
                        value="1" >Aprobar</button>
                </div>
            </div>

        </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
