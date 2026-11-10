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
    $("#divTipoEgreso").hide();

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
    
    function getTipoEgreso(){
      var estado = $('#estado').val();
      
      if (estado == 6) {
        $("#divTipoEgreso").show();
        $('#divBlanco').attr("class","col-lg-1 col-md-1 col-sm-6 col-xs-12");
      } else {
        $("#divTipoEgreso").hide();
        $('#divBlanco').attr("class","col-lg-3 col-md-3 col-sm-6 col-xs-12");
        $("#id_tipo_egreso").val(0);
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
        let estado = $('#estado').val();

        if (estado == 4) {
            $('#columna_12').html('Registrar<br>Alta Médica');
        }else if (estado == 6) {
            $('#columna_12').html('Ver Alta Médica');
        }

        let id_farmacia = $('#id_farmacia').val();

        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_atencion_hospitalizacion_ajax",
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
                let id_servicio = $('#id_servicio').val();
                var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                // var estado = $('#estado').val();
                var id_prioridad = 0;
                var id_topico = 0;
                var opcion = $('#opcion').val();
                var id_tipo_egreso = $('#id_tipo_egreso').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				"dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,opcion:opcion,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,id_establecimiento:id_establecimiento,estado:estado,id_prioridad,id_topico,id_tipo_egreso,
                            id_servicio:id_servicio,
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
                            return row.fecha_ingreso + ' ' + row.hora_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var fecha_salida = (row.fecha_salida != null) ? row.fecha_salida : '';
                            var hora_salida = (row.hora_salida != null) ? row.hora_salida : '';
                            return fecha_salida + ' ' + hora_salida ;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var tipo_egreso_paciente = "";
                            if(row.tipo_egreso_paciente!= null)tipo_egreso_paciente = row.tipo_egreso_paciente;
                            return tipo_egreso_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return row.paciente_nro_doc_ident;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var asegurado = "";
                            if(row.paciente_paterno != null) asegurado += row.paciente_paterno;
                            if(row.paciente_materno != null) asegurado += " "+ row.paciente_materno;
                            if(row.paciente_nombre != null) asegurado += " ,"+ row.paciente_nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [5]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.paciente_parentesco != null) ? row.paciente_parentesco : '';
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.paciente_sexo != null) ? row.paciente_sexo : '';
                        },
                        "bSortable": false,
                        "aTargets": [7]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.paciente_edad != null) ? row.paciente_edad : '';
                        },
                        "bSortable": false,
                        "aTargets": [8]
                    },
                    {
                        "mRender": function(data, type, row) {
                            return (row.paciente_telefono != null) ? row.paciente_telefono : '';
                        },
                        "bSortable": false,
                        "aTargets": [9]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.servicio_encargado != null) ? row.servicio_encargado : 'No registrado';
                        },
                        "bSortable": false,
                        "aTargets": [10]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let servicio_ubicacion = '';
                            /*if (row.servicio_ubicacion_fisica != null) {
                                servicio_ubicacion += '<b>Servicio: </b>' + row.servicio_ubicacion_fisica;
                            } else {*/
                                servicio_ubicacion += (row.cama_servicio_nombre != null) ? '<b>Servicio: </b>' + row.cama_servicio_nombre : ''
                            //}
                            servicio_ubicacion += (row.cuarto_nombre != null) ? '<br><b>Cuarto: </b>' + row.cuarto_nombre : '';
                            servicio_ubicacion += (row.cama_nombre != null) ? '<br><b>Cama: </b>' + row.cama_nombre : '';
                            return servicio_ubicacion;
                        },
                        "bSortable": false,
                        "aTargets": [11],
                        // "visible": false
                    },
    				{
                        "mRender": function (data, type, row) {
    					    var html = "";
                            if(estado == "4") {
                                @if($permiso->crear == "1")
                                //if (row.id_establecimiento != 75) {
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar</b>">';
                                    html += '<a href="/hospitalizacion_alta_medica/<?php echo Auth::user()->id ?>/' + row.id + '/2" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                                /*} else {
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Dar de Alta</b>">';
                                    html += '<a href="/alta_hospitalizado/' + id_farmacia + '/' + row.id + '" class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i></a></div>';
                                }*/
                                @endif
                            } else if (estado == "6") {
                                //if (row.id_establecimiento != 75) {
                                //if (row.id_intervencion == null) {
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Alta Médica</b>">';
                                    html += '<a href="/hospitalizacion_ver_alta_medica/<?php echo Auth::user()->id ?>/' + row.id +'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>';
                                    html += '</div>';
                                // } else {
                                //     html += '<span class="btn-alta-wrapper" rel="popover" style="display:inline-block; cursor:default;" ';
                                //     html += 'data-html="true" data-placement="top" ';
                                //     html += 'data-title="<b>Información</b>" ';
                                //     html += 'data-content="No es posible visualizar la atención.<br>El paciente tiene alta domiciliaria en Centro Quirúrgico.">';
                                //     html += '<button type="button" class="btn btn-success btn-xs" disabled><i class="fa fa-eye"></i></button>';
                                //     html += '</span>';
                                // }
                                /*} else {
                                    if (row.diferencia_dias <= 1) {
                                        html += '<table>';
                                        html += '<tr>';
                                        html += '<td>';
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Alta Paciente</b>">';
                                    	html += '<button type="button" class="btn btn-danger btn-xs" onclick="ver_det_receta(<?php echo Auth::user()->id ?>,'+row.nro_hospitalizacion+','+row.id+')"><i class="fa fa-xing"></i></button>';
                                	    html += '</div>';
                                        html += '</td>';
                                        html += '<td style="padding-left:5px;">';
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar Alta</b>">';
                                        html += '<a href="/modifica_alta_hospitalizado/'+id_farmacia+'/'+row.id+'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>';
                                        html += '</div>';
                                        html += '</td>';
                                        html += '</tr>';
                                        html += '</table>';
                                    } else {
                                        html += '<table>';
                                        html += '<tr>';
                                        html += '<td>';
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true">';
                                    	html += '<button type="button" class="btn btn-danger btn-xs" disabled><i class="fa fa-xing"></i></button>';
                                	    html += '</div>';
                                        html += '</td>';
                                        html += '<td style="padding-left:5px;">';
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true">';
                                        html += '<button type="button" class="btn btn-success btn-xs" disabled><i class="fa fa-edit"></i></button>';
                                        html += '</div>';
                                        html += '</td>';
                                        html += '</tr>';
                                        html += '</table>';
                                    }
                                }*/
                            }
    					    return html;
                        },
                        "bSortable": false,
                        "aTargets": [12],
                        "className": "text-center"
                    },
                    {
                        "mRender": function (data, type, row) {
                            var html = "";
                            // var estado_de = $('#estado').val();
                            var fecha_salida = (row.fecha_salida != null) ? row.fecha_salida : ''; 
                            if (estado == "4") {
                            } else if(estado == "6") {
                                if (row.id_establecimiento != 76) {
                                    html += '<a href="/print_hospitalizacion_informe_egreso/' + row.id +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-file-pdf-o" style="color: blue;"></i></a></div>';
                                    }
                                    else
                                    {
                                        
                                }
                                //if (row.id_establecimiento != 75) {
                                    html += '<table><tr><td style="padding-right:2px">';
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF</b>">';
                                    //if (row.id_intervencion == null) {
                                    if (Number(row.estado) > 5 && row.id_cama_estado !== null) {
                                        if(row.ruta_alta_medica != null && row.ruta_alta_medica != ""){
                                            html += '<a href="' + row.ruta_alta_medica +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                                        }
                                        else
                                        {
                                            html += '<a href="/print_hospitalizacion_alta_medica_hc/' + row.id +'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a></div>';
                                        }
                                    }else{
                                        if (row.ruta_firma_reporte_operatorio != null && row.ruta_firma_reporte_operatorio != "") {
                                            html += '<a href="' + row.ruta_firma_reporte_operatorio +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
                                        } else {
                                            html +=
                                            '<span ' +
                                                'data-toggle="popover" ' +
                                                'data-trigger="hover" ' +
                                                'data-placement="top" ' +
                                                'data-html="true" ' +
                                                'title="Reporte operatorio" ' +
                                                'data-content="Descarga el reporte operatorio completo">' +
                                                '<a ' +
                                                'href="/print_hospitalizacion_reporte_operatorio_cq/' + row.id_intervencion + '" ' +
                                                'target="_blank" ' +
                                                'class="btn btn-primary btn-xs">' +
                                                '<i class="fa fa-download"></i>' +
                                                '</a>' +
                                            '</span>';
                                        }
                                    }
                                    html += '</td>';
                                    html += '<td style="padding-right:2px">';
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Consolidado</b>">';
                                    if(row.ruta_alta_medica != null && row.ruta_alta_medica != "" && process(fecha_salida) > process('09/03/2025')){
                                        html += '<a href="/pdf_alta_hospitalizacion/' + row.id +'" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i></a></div>';
                                    }
                                    html += '</td>';
                                    html += '<td style="padding-right:2px"><div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Epicrisis</b>">';
                                    //PARA QUIRURGICO
                                    let origen = Number(row.id_tipo_atencion_origen);
                                    if (
                                        row.id_intervencion != null &&
                                        row.ruta_epicrisis != null &&
                                        row.ruta_epicrisis != "" &&
                                        row.tipo_egreso_paciente === 'DOMICILIARIA' &&
                                        (origen === 1 || origen === 2)
                                    ) {
                                        html += '<a href="' + row.ruta_epicrisis +'" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a></div>';
                                    }else{
                                    //FIN PARA QUIRURGICO
                                        if (row.ruta_epicrisis != null && row.ruta_epicrisis != "") {
                                            html += '<a href="' + row.ruta_epicrisis +'" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a></div>';
                                        } else {
                                            html += '<a href="/print_hospitalizacion_epicrisis/' + row.id +'" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a>';
                                        }
                                    }

                                    html += '</div></td>';

                                    html += '<td><div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Papeleta de egreso</b>">';
                                    if (row.ruta_papeleta_egreso != null && row.ruta_papeleta_egreso != "") {
                                        html += '<a href="' + row.ruta_papeleta_egreso +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                                    } else {
                                        html += '<a href="/print_hospitalizacion_papeleta_egreso/' + row.id +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
                                    }
                                    html += '</div></td>';

                                    html+= '</tr></table>';
                                /*} else {
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Imprimir Alta</b>">';
                                    html += '<a href="/imprimir_nuevo_alta/' + row.id +'/'+ id_farmacia + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a>';
                                    html += '</div>';
                                }*/
                            }
                            
                           return html;
                        },
                        "bSortable": false,
                        "aTargets": [13]
                    },
                ]
        });
    }

    function process(date){
       var parts = date.split("/");
       return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    function fn_ListarBusqueda() {
       datatablenew();
    }

    function reporteAtencion() {
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                let fecha_ini = $('#fecha_ini').val();
                let fecha_fin = $('#fecha_fin').val();
                let dni_beneficiario = $('#dni_beneficiario').val();
                let nombre_beneficiario = $('#nombre_beneficiario').val();
                let estado = $('#estado').val();
                let id_servicio = $('#id_servicio').val();
                let id_tipo_egreso = $('#id_tipo_egreso').val();

                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_alta_hospitalizacion",
                    data: {
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni_beneficiario" : dni_beneficiario,
                        "nombre_beneficiario" : nombre_beneficiario,
                        "estado" : estado,
                        "id_tipo_egreso" : id_tipo_egreso,
                        "id_servicio" : id_servicio
                    },
                    success: function (response) {
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = 'Exportar_Hospitalizacion_Alta_.xlsx';
                            link.innerText = 'Descargar archivo';
                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = $('#link-reporte');
                            divLinkReporte.append(link);
                            link.click();
                            divLinkReporte.empty(); // o divLinkReporte.remove(); dependiendo de lo que se necesite hacer

                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
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

    function ver_det_receta(idUser, nro_hospitalizacion, id_receta){
        $('#nrorecmodal').text(nro_hospitalizacion);
        document.getElementById("id_hospitalizacion").value = id_receta;
        var estado = $('#estadoo').val();
        $.ajax({
            url: '/ver_hospitalizado/2/' + idUser + '/' + nro_hospitalizacion + '/' + id_receta + '/' + estado,
            async: false,
            success: function(result){
                $("#squarespaceModal").modal();
            }
        });
    }

    $(document).on('mouseenter', '.btn-alta-wrapper', function () {
        var $el = $(this);
        if (!$el.data('bs.popover')) {
            $el.popover({
                html: true,
                trigger: 'hover',
                placement: 'top',
                container: 'body',
                title: $el.data('title'),
                content: $el.data('content')
            }).popover('show');
            $el.on('mouseleave', function () { $el.popover('hide'); });
        }
    });

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading" style="padding: 0;">
            <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(236, 236, 117); color: green;">H</span> Lista Para Dar El Alta Médica - Hospitalización </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_atencion_hospitalizacion_ajax')}}">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
    			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
    			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" id="id_farmacia" value="{{$id_farmacia}}">
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
                          class="form-control input-sm" maxlength="12" value="">
                      </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="form-control-sm">Nombres</label>
                        <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                          class="form-control input-sm text-uppercase" maxlength="60" value="">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Servicio</label>
                        <select id="id_servicio" name="id_servicio" class="form-control" style="width: 100%">
                            <option value="0">--Seleccionar--</option>
                            @foreach($upss as $row)
                            <option value="{{$row->id_upss}}">{{$row->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
    			</div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label class="form-control-sm">Estado</label>
                        <select class="form-control" name="estado"  id="estado" tabindex="15" onchange="getTipoEgreso()">
                            <option value="4" <?php if($estado == 4)echo "selected='selected'";?>>Pendiente</option>
                            <option value="6" <?php if($estado == 6)echo "selected='selected'";?>>Atendida</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12"  id="divTipoEgreso" style="display:none">
                        <label class="form-control-sm">Tipo Egreso</label>
                        <select class="form-control input-sm" id="id_tipo_egreso" name="id_tipo_egreso" style="width: 100%">
                            <option value="0" <?php if($id_tipo_egreso=="0"){ ?>selected <?php } ?> >Elegir</option>
                            <option value="1" <?php if($id_tipo_egreso=="1"){ ?>selected <?php } ?> >DOMICILIARIA</option>
                            <option value="2" <?php if($id_tipo_egreso=="2"){ ?>selected <?php } ?> >HOSPITALIZACIÓN</option>
                            <option value="3" <?php if($id_tipo_egreso=="3"){ ?>selected <?php } ?> >TRANSFERIDO</option>
                            <option value="6" <?php if($id_tipo_egreso=="6"){ ?>selected <?php } ?> >FUGA</option>
                            <option value="4" <?php if($id_tipo_egreso=="4"){ ?>selected <?php } ?> >FALLECIDO</option>
                            <option value="7" <?php if($id_tipo_egreso=="7"){ ?>selected <?php } ?> >RETIRO VOLUNTARIO</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="">&nbsp;</label>
                        <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
        				
        			</div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="">&nbsp;</label>
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                            onclick="reporteAtencion('{{ Auth::user()->id }}')">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                        </a>
                    </div>
                </div>
            </form>
            <br>
            <div class="table-responsive">
                <table id="tblEmergenciaTriaje" class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Alta</th>
                            <th>Tipo Egreso</th>
                            <th>Numero Doc</th>
                            <th>Paciente</th>
                            <th>Parentesco</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th>Telefono</th>
                            <th>Servicio A Cargo</th>
                            <th>Ubicación</th>
                            <th id="columna_12" style="text-align:center">Registrar<br>Alta Médica</th>
                            <th style="text-align:center">PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">INVALIDAR ALTA PACIENTE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <form class="" method="post" action="{{ route('cambiar_estado_hospitalizado')}}" id="modal_estado" name="modal_estado">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para invalidar el alta del paciente</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_hospitalizacion" name="id_hospitalizacion">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
