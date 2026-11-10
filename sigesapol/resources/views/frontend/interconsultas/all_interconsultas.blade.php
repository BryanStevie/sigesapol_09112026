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

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }

  .swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
        }

  .font-iconos-tables{
    font-size: 10px;
    cursor: pointer;
    background-color: #c4e3c5;
    color: #2b620a;
    padding: 5px;
    border-radius: 3px;
  }

  .div-icon{
    position: absolute;
    right: 10px;
    top: 1px;
    bottom: 8px;
    display: flex;
    align-items: flex-start;
    align-content: stretch;
    flex-wrap: wrap;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
  //--------------------------------------------------------------------------
    $(document).ready(function () {
        // Detener el intervalo (creado en el nav.blade.php) cuando se ingrese a esta pagina
        //clearInterval(intervalId);
        
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

        $("#id_servicio").select2({
          width: 'resolve'
        });
        
        <?php if($id_servicio > 0){?>
        $("#id_servicio").val("<?php echo $id_servicio; ?>").prop('disabled', true).select2();
        <?php }?>
        
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
    
    
    function exportarLista(idUser) {
      let fecha_ini = $('#fecha_ini').val();
      let fecha_fin = $('#fecha_fin').val();
      let dni_beneficiario = $('#dni_beneficiario').val();
      let nombre_beneficiario = $('#nombre_beneficiario').val();
      let id_medico = $('#id_medico').val();
      let id_servicio = $('#id_servicio').val();
      let estado = $('#estado').val();
      let tipo_atencion = $('#tipo_atencion').val();

      if (fecha_ini == "") fecha_ini = "0";
      if (fecha_fin == "") fecha_fin = "0";
      if (dni_beneficiario == "") dni_beneficiario = "0";
      if (nombre_beneficiario == "") nombre_beneficiario = "0";
      
      location.href = 'exportar_lista_interconsulta/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni_beneficiario + '/' + nombre_beneficiario + '/' + estado + '/' + id_medico + '/' + id_servicio + '/' + tipo_atencion;
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
            "sAjaxSource": "/listar_interconsulta_ajax",
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
                var id_medico = $('#id_medico').val();
                var id_servicio = $('#id_servicio').val();
                let tipo_atencion = $('#tipo_atencion').val();
    			var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				      "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,
                dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,id_establecimiento:id_establecimiento,id_medico:id_medico,id_servicio:id_servicio,estado:estado,tipo_atencion:tipo_atencion,
    						_token:_token
                           },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error al consultar los registros.'
                        });
                        $(".dataTables_processing").hide();
                    }
                });
            },
            "aoColumnDefs":
                [	
                    {
                        "mRender": function (data, type, row) {
                            return row.id;
                        },
        				        "bSortable": false,
                        "aTargets": [0]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let boton_ver_atencion = "";
                            let condicion = "";

                            if (row.id_estado_reg == 1) {
                              boton_ver_atencion += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>">';
                              boton_ver_atencion += '<a href="/ver_atencion_interconsultas/'+row.id_prestacion+'/'+row.id+'/'+row.id_tipo_atencion+'/'+row.id_evaluacion+'/'+row.id_establecimiento+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>';
                              boton_ver_atencion += '</div>';
                            } else {
                              boton_ver_atencion += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }

                            if (row.id_tipo_atencion == 2) {
                              condicion = row.es_referido + " REFERIDO";
                            } else if (row.id_tipo_atencion == 3) {
                              condicion = "NO REFERIDO";
                            }
                                                        
                            let datos_paciente = `
                              <div style="position: relative;">
                                  <!-- Botón en la esquina superior derecha -->
                                  <div style="position: absolute; top: 0; right: 0;">
                                      ${boton_ver_atencion}
                                  </div>

                                  <!-- Contenido principal alineado a la izquierda -->
                                  ${row.paterno} ${row.materno}, ${row.nombre}<br>
                                  <b>${row.tipo_doc_ident}</b>: ${row.nro_doc_ident} / (${row.sexo})<br>
                                  <b>F.NAC:</b> ${row.fecha_nac} - <b>EDAD:</b> ${row.edadpaciente}<br>
                                  <b>CONDICIÓN:</b> ${condicion}
                              </div>
                              `;
                            return datos_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let evaluacion = "";
                            let ubicacion = "";
                            let especialidad = "";

                            if (row.id_evaluacion != 0) {
                              evaluacion = row.id_tipo_atencion == 3 ? " - Evolución" : " - Evaluación";
                            }

                            if (row.id_tipo_atencion == 3) {
                              ubicacion += row.nombre_area != null ? row.nombre_area : "";
                              ubicacion += row.cama != null ? " / " + row.cama : "";
                            } else {
                              ubicacion += row.nombre_area != null ? row.nombre_area : "";
                              ubicacion += row.cuarto != null ? " / " + row.cuarto : "";
                              ubicacion += row.cama != null ? " / " + row.cama : "";
                            }

                            especialidad = row.id_tipo_atencion == 2 ? "EMERGENCIA" : row.servicio_atencion;

                            let boton_pdf_interconsulta = "";
                            if (parseInt(row.id_estado_reg) === 1 && parseInt(row.flag) !== 0) {
                              boton_pdf_interconsulta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>">';
                              boton_pdf_interconsulta += '<a href="/' + row.flag + '/' + row.ruta_interconsulta +
                                      '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
                              boton_pdf_interconsulta += '</div>';
                            }else{
                              boton_pdf_interconsulta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
                            }

                            let boton_responder = "";
                            if (parseInt(row.id_estado_reg) === 1 && parseInt(row.flag) !== 0 && row.fecha_rpta_atencion == null) {
                              boton_responder += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Responder Interconsulta</b>">';
                              boton_responder += '<a href="/atender_interconsulta/'+row.id_prestacion+'/'+row.id+'/'+row.id_evaluacion+'" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>';
                              boton_responder += '</div>'
                            } else if (parseInt(row.id_estado_reg) === 1 && parseInt(row.flag) === 0) {
                              boton_responder += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Falta firmar Solicitud</b>"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }
                            
                            let datos_solicitud = `
                              <div style="position: relative;">
                                <!-- Botones alineados verticalmente en la esquina superior derecha -->
                                <div style="position: absolute; top: 0; right: 0; display: flex; flex-direction: column; gap: 10px;">
                                  ${boton_pdf_interconsulta}
                                  ${boton_responder}
                                </div>

                                <!-- Contenido de texto -->
                                <b>FECHA DE SOLICITUD:</b> ${row.created_at}<br>
                                <b>FECHA DE FIRMA:</b> ${row.fecha_firma_interconsulta}<br>
                                <b>ORIGEN:</b> ${row.tipo_atencion} ${evaluacion}<br>
                                <b>ESPECIALIDAD:</b> ${especialidad}<br>
                                <b>UBICACIÓN:</b> ${ubicacion}
                              </div>
                            `;
                            return datos_solicitud;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
    				        {
                        "mRender": function (data, type, row) {
                          let paciente_condicion = row.is_estable != null ? row.is_estable ? "ESTABLE" : "INESTABLE" : "SIN INFORMACIÓN";
                          let datos_coordinacion = `<b>CONDICIÓN DEL PACIENTE:</b> ${paciente_condicion}<br>`;
                          
                          if (row.personal_coordinacion != null) {
                            datos_coordinacion += `<b>PERSONAL EN COORDINACIÓN:</b> ${row.personal_coordinacion}`;
                          }
                          return datos_coordinacion;
                            /*var fecha_atencion = "";
                            if(row.fecha_atencion!= null)fecha_atencion = row.fecha_atencion + " " + row.hora_atencion;
                            return fecha_atencion;*/
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    /*{
                        "mRender": function (data, type, row) {
                            return row.created_at;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },*/
                    {
                      "mRender": function (data, type, row) {
                        let fecha_rpta_atencion = "";
                        let fecha_firma_rpta_atencion = "";
                        let profesional_respuesta = "";

                        let boton_ver_respuesta = "";
                        if (parseInt(row.id_estado_reg) === 1 && row.fecha_rpta_atencion != null) {
                          boton_ver_respuesta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>">';
                          boton_ver_respuesta += '<a href="/ver_atencion_interconsulta_rptas/'+row.id_prestacion+'/'+row.id+'/'+row.id_evaluacion+'/'+row.id_tipo_atencion+'" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>';
                          boton_ver_respuesta += '</div>';
                        } else {
                          boton_ver_respuesta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Respuesta Pendiente</b>"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                        }

                        let boton_pdf_respuesta = "";
                        if (parseInt(row.id_estado_reg) === 1 && parseInt(row.flag_rpta) !== 0) {
                          boton_pdf_respuesta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Descargar Respuesta</b>">';
                          boton_pdf_respuesta += '<a href="/' + row.flag_rpta + '/' + row.ruta_rpta_interconsulta +
                                '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
                          boton_pdf_respuesta += '</div>';
                        } else {
                          boton_pdf_respuesta += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Falta firmar Respuesta</b>"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
                        }


                        // if (row.fecha_rpta_atencion != null) fecha_rpta_atencion = row.fecha_rpta_atencion;
                        if (row.fecha_rpta_atencion != null && parseInt(row.flag_rpta) !== 0) { // Si se Respondió y se firmó la Rpta de IC
                          fecha_rpta_atencion = row.fecha_rpta_atencion;
                          fecha_firma_rpta_atencion = row.fecha_rpta_firma_interconsulta;
                        } else if (row.fecha_rpta_atencion != null && parseInt(row.flag_rpta) === 0) { // Si se Respondió y NO se firmó la Rpta de IC
                          fecha_rpta_atencion = row.fecha_rpta_atencion;
                          fecha_firma_rpta_atencion = "<span style='color: red;'>PENDIENTE DE FIRMA</span>";
                        }
                        if (row.paterno_medico2 != null) profesional_respuesta += row.paterno_medico2;
                        if (row.materno_medico2 != null) profesional_respuesta += " " + row.materno_medico2;
                        if (row.nombre_medico2 != null) profesional_respuesta += ", " + row.nombre_medico2;

                        let respuesta = `
                          <div style="position: relative;">
                            <!-- Botones alineados verticalmente en la esquina superior derecha -->
                            <div style="position: absolute; top: 0; right: 0; display: flex; flex-direction: column; gap: 10px;">
                              ${boton_ver_respuesta}
                              ${boton_pdf_respuesta}
                            </div>

                            <!-- Contenido de texto -->
                            <b>F. DE RESPUESTA:</b> ${fecha_rpta_atencion}<br>
                            <b>FECHA DE FIRMA:</b> ${fecha_firma_rpta_atencion}<br>
                            <b>ESPECIALIDAD:</b> ${row.nombre_consultorio}<br>
                            <b>PROFESIONAL:</b> ${profesional_respuesta}
                          </div>
                        `;
                        return respuesta;
                      },
                      "bSortable": false,
                      "aTargets": [4]
                    },
    				        /*{
                        "mRender": function (data, type, row) {
                            var tipo_atencion = ""; var evaluacion = "";
        					          if(row.tipo_atencion!= null)tipo_atencion = row.tipo_atencion;
                            if(row.id_evaluacion!=0) {
                              evaluacion = row.id_tipo_atencion == 3 ? " - Evolución" : " - Evaluacion";
                            }
        					          return tipo_atencion+evaluacion;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let ubicacion = "";
                            if (row.id_tipo_atencion == 3) {
                              ubicacion += row.nombre_area != null ? "<b>Servicio: </b>" + row.nombre_area + "<br>": "";
                              ubicacion += row.cama != null ? "<b>Cama: </b>" + row.cama : "";
                            } else {
                              ubicacion += row.nombre_area != null ? row.nombre_area : "";
                              ubicacion += row.cuarto != null ? " - " + row.cuarto : "";
                              ubicacion += row.cama != null ? " - " + row.cama : "";
                            }
                            return ubicacion;
                        },
                        "bSortable": false,
                        "aTargets": [5]
                        
                    },*/
                    /*{
                        "mRender": function (data, type, row) {
                            var medico = "";
                            if(row.paterno_medico!= null)medico += row.paterno_medico;
                            if(row.materno_medico!= null)medico += " "+row.materno_medico;
                            if(row.nombre_medico!= null)medico += " ,"+row.nombre_medico;
                            return medico;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                        
                    },*/
                    // {
                    //     "mRender": function (data, type, row) {
                    //       var nombre_consultorio = row.nombre_consultorio ? row.nombre_consultorio : "";var medico2 = "";
                    //         /* if(row.nombre_consultorio!= null)nombre_consultorio = row.nombre_consultorio; */
                    //         /* if (row.id_servicio == '16' || row.id_servicio == '114') {
                    //             nombre_consultorio = $descripcion_limpia ? row.descripcion_upss : "";
                    //         } */
                    //         if (row.id_servicio == '16' || row.id_servicio == '114') {
                    //             if (row.descripcion_upss) {
                    //                 nombre_consultorio = row.descripcion_upss
                    //                     .replace("HOSPITALIZACIÓN QUIRÚRGICA-", "")
                    //                     .replace("CONSULTA EXTERNA-", "");
                    //             }
                    //         }
                    //         if(row.paterno_medico2!= null)medico2 += row.paterno_medico2;
                    //         if(row.materno_medico2!= null)medico2 += " "+row.materno_medico2;
                    //         if(row.nombre_medico2!= null)medico2 += " ,"+row.nombre_medico2;
                    //         return nombre_consultorio+" - "+ medico2;
                    //     },
                    //     "bSortable": false,
                    //     "aTargets": [7]
                        
                    // },
                    /*{
                        "mRender": function (data, type, row) {
                            var nro_doc_ident = "";
                            if(row.nro_doc_ident!= null)nro_doc_ident = row.nro_doc_ident;
                            return nro_doc_ident;
                        },
                        "bSortable": false,
                        "aTargets": [8]
        				
                    },*/
    				        /*{
                        "mRender": function (data, type, row) {
                            var asegurado = "";
                            if(row.paterno!= null)asegurado += row.paterno;
                            if(row.materno!= null)asegurado += " "+row.materno;
                            if(row.nombre!= null)asegurado += " ,"+row.nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [9]
                    },*/
                    /*{
                        "mRender": function (data, type, row) {
                            return (row.parentesco != null) ? row.parentesco : "";
                        },
                        "bSortable": false,
                        "aTargets": [10]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var grado = "";
                            if(row.grado!= null)grado = row.grado;
                            return grado;
                        },
                        "bSortable": false,
                        "aTargets": [11]
                        
                    },*/
                    /*{
                        "mRender": function (data, type, row) {
                            var sexo = "";
                            if(row.sexo!= null)sexo = row.sexo;
                            return sexo;
                        },
                        "bSortable": false,
                        "aTargets": [12]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.edadpaciente!= null)edad = row.edadpaciente;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [13] 
                        
                    },*/
    				        /*{
                        "mRender": function (data, type, row) {
                            var html = "";
                            if(row.id_estado_reg==1){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                              html += '<a href="/ver_atencion_interconsultas/'+row.id_prestacion+'/'+row.id+'/'+row.id_tipo_atencion+'/'+row.id_evaluacion+'/'+row.id_establecimiento+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';       
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }

    					              return html;
                        },
                        "bSortable": false,
                        "aTargets": [5]
        				
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";
                           if(row.id_estado_reg==1 && row.fecha_firma_interconsulta!=null){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/' + row.flag + '/' + row.ruta_interconsulta +
                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';      
                           }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
                           }
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                        
                    },
    				        {
                        "mRender": function (data, type, row) {
                            var html = "";
                            if(row.id_estado_reg==1 && row.fecha_firma_interconsulta!=null){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Responder</b>" style="float:left;padding-left:3px">';
                              html += '<a href="/atender_interconsulta/'+row.id_prestacion+'/'+row.id+'/'+row.id_evaluacion+'" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a></div>';       
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Falta firmar solicitud</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }	
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [7]
        				
                    },*/
    				        /*{
                        "mRender": function (data, type, row) {
                            var html = "";
                            if(row.id_estado_reg==1 && row.fecha_rpta_atencion!=null){
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
                              html += '<a href="/ver_atencion_interconsulta_rptas/'+row.id_prestacion+'/'+row.id+'/'+row.id_evaluacion+'/'+row.id_tipo_atencion+'" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a></div>';       
                            }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Respuesta</b>" style="float:left;padding-left:3px"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                            }	
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [8]
        				
                    },
                    {
                        "mRender": function (data, type, row) {
                           var html = "";
                           if(row.id_estado_reg==1 && row.fecha_rpta_firma_interconsulta!=null){
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Interconsulta</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/' + row.flag_rpta + '/' + row.ruta_rpta_interconsulta +
                                    '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';      
                           }else{
                              html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
                           }
                           
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [9]
                        
                    },*/
                    {
                      "mRender": function (data, type, row) {
                          let htmlString = '<div style="text-align: center">';
                          let isSitedsTemp = false;
                          if (parseInt(row.id_tipo_atencion) === 2) { // cambiar por 2
                            // htmlString += `<p><span class="label label-success">${row.siteds_emergencia || " - "}</span></p>`;
                            if (row.siteds_emergencia) {
                              if (row.siteds_emergencia && (row.siteds_emergencia[row.siteds_emergencia.length - 1] === 'T' || row.siteds_emergencia.length === 4)) {
                                  isSitedsTemp = true;
                              }
                              htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.siteds_emergencia}</span></p>`;
                              if (isSitedsTemp) {
                                  htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.emergencia_id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_servicio}, 2)"><i class="fa fa-check-square"></i></button>`
                              }
                            }
                          }
                          if (parseInt(row.id_tipo_atencion) === 3) { // cambiar por 3
                            if (row.siteds_emergencia) {
                              if (row.siteds_emergencia && (row.siteds_emergencia[row.siteds_emergencia.length - 1] === 'T' || row.siteds_emergencia.length === 4)) {
                                  isSitedsTemp = true;
                              }
                              htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.siteds_emergencia}</span></p>`;
                              if (isSitedsTemp) {
                                  htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.hospitalizacion_id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_servicio}, 3)"><i class="fa fa-check-square"></i></button>`
                              }
                            }
                          }
                          return htmlString += "</div>";
                      },
                      "bSortable": false,
                      "aTargets": [5]
                    }
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

    // TAGSITEDS
    function regularizeCodeSiteds(id, aseguradoId, establecimientoId, servicioId, tipoAtencionId) {
      Swal.fire ({
          title:  "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
          html: "<div style='margin: 0px 0px 20px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          onBeforeOpen: () => {
              Swal.showLoading()
          },
      })
      const body = {
          "idCorrelativo": id,
          "aseguradoId": aseguradoId,
          "establecimientoId": establecimientoId,
          "tipoAtencionId": tipoAtencionId,
          "servicioId": servicioId
      };
      $.ajax({
          url:'/v2/ws-dirsapol-siteds',
          type:'POST',
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(body),
          success: (result, textStatus, jqXHR) => {
              if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
                  updateSiteds(id, result.nuAutorizacion, result.message, tipoAtencionId);
              } else {
                const session = {!! json_encode(Auth::user()) !!}
                const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                
                Swal.fire({
                  icon: 'warning',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                          <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                        </div>`,
                  showCancelButton: true,
                  showConfirmButton: hasPermissionUpdateSitedsManual,
                  cancelButtonText: hasPermissionUpdateSitedsManual ? "CANCELAR" : "ENTENDIDO",
                  confirmButtonColor: "#5cb85c",
                  cancelButtonColor: "#d9534f",
                  confirmButtonText: "INGRESAR MANUALMENTE",
                }).then((result) => {
                  if (result.isConfirmed) {
                    showModalSitedsManual().then((resultModal) => {
                      if (resultModal && resultModal.isConfirmed && resultModal.value) {
                        updateSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", tipoAtencionId)
                      }
                    })
                  }
                });
                  // Swal.fire({
                  //     icon: 'error',
                  //     title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                  //     html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                  //             <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                  //             </div>`,
                  //     confirmButtonText: 'ENTENDIDO',
                  // });
              }
          },
          error: function(xhr, status, error){
              const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
          }, 
          complete: function(xhr, status) {
              Swal.hideLoading();
          }
      })
    }

    function updateSiteds(id, nuAutorizacion, message, tipoAtencionId) {
      Swal.fire({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onOpen: () => {
            Swal.showLoading();
        }
      })

      let body = {};
      let url = "";
      const auth = {!! json_encode(Auth::user()) !!};
      if (parseInt(tipoAtencionId) === 2) {
        url = `/emergencia/${id}/update-code-siteds`;
        body = {
          cod_siteds_susalud: nuAutorizacion,
          updated_us_cod_siteds: auth.id
        };
      } else {
        url = `/hospitalizacion/${id}/update-code-siteds`;
        body = {
          cod_siteds_susalud: nuAutorizacion,
        };
      }
     
      $.ajax({
        url: url,
        type: 'PUT',
        data: body,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon:'success',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
                html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${message || "Código SITEDS actualizado correctamente"}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            fn_ListarBusqueda();
        },
        error: function(jqXHR, textStatus, errorThrown) {},
        complete: function(xhr, status) {
            Swal.hideLoading();
            Swal.close();
        }
      });
    };


    function showModalSitedsManual(id) {
      return new Promise((resolve, reject) => {
        Swal.fire({
            title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
            html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
            input: 'text',
            inputLabel: 'Código SITEDS',
            inputPlaceholder: "000000000001",
            showCancelButton: true,
            cancelButtonText: 'CANCELAR',
            confirmButtonText: 'GUARDAR',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
                popup: 'swal-wide-form',
            },
            inputAttributes: {
                maxlength: 12,
                pattern: '[A-Za-z0-9 ]*'
            },
            buttonsStyling: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'Ingresa el código SITED para continuar';
                } else if (value.length < 12) {
                    return 'El código SITEDS debe tener 12 caracteres';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(result);
            } else {
                reject(result);
            }
        }).catch((error) => {
            reject(error);
        });
      });
    }

</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Interconsultas Médicas</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_interconsulta_ajax')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input value="<?php echo $id_medico ?>" name="id_medico" id="id_medico" type="hidden">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{Auth::user()->id_establecimiento}}">
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Desde</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Hasta</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaHasta?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Nro. Documento</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <label class="form-control-sm">Nombres</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Tipo de Atención</label>
            <select name="tipo_atencion" id="tipo_atencion" class="form-control input-sm">
              <option value="2" @if($id_tipo_atencion == 2) selected @endif>Emergencia</option>
              <option value="3" @if($id_tipo_atencion == 3) selected @endif>Hospitalización</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Servicio</label>
            <select class="form-control input-sm" name="id_servicio" id="id_servicio" style="width:100%">
              <option value="0">-Seleccionar-</option>
              @foreach($servicios as $row)
              <option value="{{$row->id}}">{{$row->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <input type="hidden" value="<?php echo $estado?>" name="estado" id="estado" class="form-control">
            <label for="">&nbsp;</label>
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            <!--</div>-->
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label for="">&nbsp;</label>
            <a class="btn btn-primary form-control input-sm" href="javascript:void(0)" onclick="exportarLista('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
            </a>
            <!--div class="col-md-6" style="padding-right:0px;padding-left:0px">
              <a class="btn btn-success form-control input-sm" href="{{ url('nueva_interconsulta') }}">Nuevo</a>
            </div-->
          </div>
        </div>
      </form>
      <br>
      <div class="table-responsive">
        <table id="tblEmergenciaTriaje" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>N</th>
              <th>DATOS DEL PACIENTE</th>
              <th>SOLICITUD DE INTERCONSULTA</th>
              <th>DATOS DE COORDINACIÓN</th>
              <th>RESPUESTA DE INTERCONSULTA</th>
              <!-- <th>Tipo de Atencion</th> -->
              <!-- <th>Ubicación del Paciente<br>(Area - Sala - Cama)</th> -->
              <!-- <th>Médico Solicitante</th> -->
              <!-- <th>Especialista</th> -->
              <!-- <th>Numero Doc</th> -->
              <!-- <th>Paciente</th>
              <th>Parentesco</th>
              <th>Grado</th>
              <th>Sexo</th>
              <th>Edad</th> -->
              <!-- <th style="text-align:center">Ver Atencion</th>
              <th style="text-align:center">PDF</th>
              <th style="text-align:center">Responder</th>
              <th style="text-align:center">Ver Rpta</th>
              <th style="text-align:center">PDF Rpta</th> -->
              <th style="text-align:center">Siteds</th>
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