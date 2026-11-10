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
        border: 1px solid #ddd;
        margin:15px
    }
    @media (max-width: 992px) {
        .table-responsive {
        overflow-x: auto;
        display: block;
        width: 100%;
    }

    .table-responsive table {
        width: max-content; /* Ajusta la tabla al contenido */
    }

    .table-responsive th,
    .table-responsive td {
        white-space: nowrap;
    }
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

    .modal-custom{
        max-width: 600px;
        margin-top: 20rem;
    }

    .tooltip-inner {
        background-color: #ffffff; /* Color de fondo personalizado */
        color: #000000; /* Color del texto */
        border: 2px solid #3c763d; /* Borde opcional */
        width: 100% !important;
    }

    .item-tooltip {
        width: 100%;
        text-align: left;
    }

    /* Cambia el color de la flecha del tooltip */
    
    .btn-app {
		padding: 1px 5px;
		font-size: 12px;
		line-height: 1.5;
		border-radius: 5px; 
		position: relative;
		text-align: center;
		color: #FFF; 
		background-color: #00a65a; 
	}

	.btn-app:hover {
		color: #FFF;
	}

	.btn-success .badge {
        color: white !important; 
        background-color: #fff;
    }

	.btn-app>.badge {
		position: absolute;
		top: -10px;
		right: -15px;
		font-size: 15px;
		font-weight: 400; 
	}

    .icon-doc-firmado {
        color: #005b80;
        font-size: 2rem;
    }

    .btn-app-pendiente-firmar {
        width: auto;
		font-size: 1.2rem;
        font-weight: bold;
		text-align: center;
		color: #c9302c;  
	}

    .btn-app-pendiente-citar {
        width: auto;
		font-size: 1.2rem;
        font-weight: bold;
		text-align: center;
		color: #006638;  
	}

    .select2-container {
    width: 100% !important; 
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
    .table > tbody > tr > td{
    padding: 2px;
    }
</style>

<script>

    //--------------------------------------------------------------------------
    // $(document).ready(function () {

    //     $consul = $("#frmRequerimiento").find("#id_requerimiento");
    //     $consul.select2();
    //     //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
    //     //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

    //     $('#openModalSiteds').on('hidden.bs.modal', function() {
    //             location.reload();
    //         })


    //     $('#fechaDesde').appendDtpicker({
    //     "dateOnly": true,
    //             "autodateOnStart": false,
    //             "minuteInterval": 5,
    //             "amPmInTimeList": false,
    //             "locale": "es",
    //             "closeOnSelected": true,
    //             "dateFormat": "DD-MM-YYYY"
    //     });
    //     $('#fechaHasta').appendDtpicker({
    //     "dateOnly": true,
    //             "autodateOnStart": false,
    //             "minuteInterval": 5,
    //             "amPmInTimeList": false,
    //             "locale": "es",
    //             "closeOnSelected": true,
    //             "dateFormat": "DD-MM-YYYY"
    //             //"dateFormat": "YYYY-MM-DD hh:mm",
    //     });
    //     $('#fechaDesde').click(function () {
    //         var fechaDesdeIni = $('#fechaDesde').val();
    //         $('#fechaDesde').change(function () {
    //             var fechaDesde = $('#fechaDesde').val();
    //             var fechaHasta = $('#fechaHasta').val();
    //             f1 = fechaDesde.split("-");
    //             f2 = fechaHasta.split("-");
    //             f3 = new Date();
    //             var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    //             var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    //             if (f1 > f2) {
    //                 bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
    //                 $('#fechaDesde').val(fechaDesdeIni);
    //                 return false;
    //             }
    //         });
    //     });
    //     $('#fechaHasta').click(function () {
    //         var fechaHastaIni = $('#fechaHasta').val();
    //         $('#fechaHasta').change(function () {
    //             var fechaDesde = $('#fechaDesde').val();
    //             var fechaHasta = $('#fechaHasta').val();
    //             f1 = fechaDesde.split("-");
    //             f2 = fechaHasta.split("-");
    //             f3 = new Date();
    //             var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    //             var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    //             if (f2 < f1) {
    //                 bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
    //                 $('#fechaHasta').val(fechaHastaIni);
    //                 return false;
    //             }
    //         });
    //     });
    //     $('#fechaHasta').trigger('change');
    //     $('#fechaDesde').trigger('change');
    //     var activeSystemClass = $('.list-group-item.active');
    // });
    
    //----------------------------------------------PARA FECHAS------------------------------------------------------------------------------
    $(document).ready(function () {
        // Inicializa select2 si aplica
        $("#frmRequerimiento").find("#id_requerimiento").select2();

        // Refresca la página al cerrar el modal
        $('#openModalSiteds').on('hidden.bs.modal', function () {
            location.reload();
        });

        // Función genérica para aplicar datepicker y validación a pares de fechas
        function aplicarDatepickerYValidacion(idDesde, idHasta) {
            // Configurar datepicker
            $('#' + idDesde).appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
            });

            $('#' + idHasta).appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
            });

            // Validación en Fecha Desde
            $('#' + idDesde).click(function () {
                const fechaDesdeIni = $('#' + idDesde).val();
                $('#' + idDesde).change(function () {
                    const desde = $('#' + idDesde).val().split("-");
                    const hasta = $('#' + idHasta).val().split("-");

                    const fDesde = new Date(desde[2], desde[1] - 1, desde[0]);
                    const fHasta = new Date(hasta[2], hasta[1] - 1, hasta[0]);

                    if (fDesde > fHasta) {
                        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                        $('#' + idDesde).val(fechaDesdeIni);
                    }
                });
            });

            // Validación en Fecha Hasta
            $('#' + idHasta).click(function () {
                const fechaHastaIni = $('#' + idHasta).val();
                $('#' + idHasta).change(function () {
                    const desde = $('#' + idDesde).val().split("-");
                    const hasta = $('#' + idHasta).val().split("-");

                    const fDesde = new Date(desde[2], desde[1] - 1, desde[0]);
                    const fHasta = new Date(hasta[2], hasta[1] - 1, hasta[0]);

                    if (fHasta < fDesde) {
                        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                        $('#' + idHasta).val(fechaHastaIni);
                    }
                });
            });

            // Forzar cambio para validar inicialmente si ya tienen valores
            $('#' + idDesde).trigger('change');
            $('#' + idHasta).trigger('change');
        }

        // Aplicar a todos los pares de fechas
        aplicarDatepickerYValidacion('fechaoDesde', 'fechaoHasta'); // Orden
        aplicarDatepickerYValidacion('fechamDesde', 'fechamHasta'); // Muestra
        aplicarDatepickerYValidacion('fechaDesde', 'fechaHasta');   // Cita
    });

    


    // $('#example').DataTable({
    //   "pageLength": 100
    // });

    function modalSiteds(id,dni) {

        $(".modal-dialog").css("width", "80%");
        $('#openModalSiteds').modal('show');
        $('#openModalSiteds.modal-body').css('height', 'auto');

        $.ajax({
            url: "modal_siteds_imagenologia/" + id+'/'+ dni,
            type: "GET",
            success: function(result) {
                $(".modal-siteds").html(result);
            }
        });

    }

    function validateSitedsFrm(){   

            var msg = "";
            var cod_siteds= $('#codigo_siteds').val();  
            if ($('#frmsiteds').find("#codigo_siteds").val() == "") {
                bootbox.alert("Recuerde que tiene que Ingresar el CÓDIGO SITEDS", function(){     
                document.getElementById("codigo_siteds").click();
                  setTimeout(function (){ 
                    $('#codigo_siteds').focus();
                }, 500);
              });
              return false;
            }else{
              ///tomamos el numero ingresado en el codigo siteds
              $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
            }
            
            var codSiteds= $('#codigo_siteds').val();  
              $.ajax({
                url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
                type:'GET',
                dataType:'JSON', 
                  success: function(data){   
                    if(data!=null){ 
                      console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
                      if(data.cantidad_siteds!=0){ 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Registro </p></div>',
                            html: '<p class="descripcion-alert"><span>EL código SITEDS <b style="color:red;">'+codSiteds+'</b> YA EXISTE, verifique que sea correcto y vuelva a intentarlo.<br><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                          }); 
                          $('#codigo_siteds').val(''); 
                          setTimeout(function (){ 
                              $('#codigo_siteds').focus();
                          }, 2500);
                      } else{ 
                        $("#frmsiteds").submit();
                        return false;
                      } 
                    } else{ 
                      $("#frmsiteds").submit();
                      return false;
                    }
                  }
              });
        };

    function completarConCeros(numero, longitud) {
        let numeroString = numero.toString();
        while (numeroString.length < longitud) {
            numeroString = '0' + numeroString;
        }
        return numeroString;
    }

    function soloNumeros(e)
    {
        var key = window.Event ? e.which : e.keyCode;
        return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    function registrarInforme(id_radiologia, id_servicio_radiologia, id_pp, id_cita) {
		window.location.href = `/radiologia_informe/${id_radiologia}/${id_servicio_radiologia}/${id_pp}/${id_cita}`;
	}

    function registrarInformeAdicional(id_radiologia, id_servicio_radiologia, id_pp, id_cita) {
		window.location.href = `/radiologia_informe_adicional/${id_radiologia}/${id_servicio_radiologia}/${id_pp}/${id_cita}`;
	}

    function modificarOValidarInforme(id_radiologia, id_servicio_radiologia, id_pp) {
        window.location.href = `/radiologia_informe_validacion/${id_radiologia}/${id_servicio_radiologia}/${id_pp}`;
    }

    function modificarOValidarInformeAdicional(id_radiologia, id_servicio_radiologia, id_pp, id_informe_adicional) {
        window.location.href = `/radiologia_informe_validacion_adicional/${id_radiologia}/${id_servicio_radiologia}/${id_pp}/${id_informe_adicional}`;
    }
    
    function atenderOtrosEstablecimientosDiferentesAlCentral(id_radiologia) {
        window.location.href = "/formulario_atencion_radiologia/"+id_radiologia;
    }

    $("#btnBuscar").click(function (e) { 
        e.preventDefault();
        let btnBuscar = document.getElementById("btnBuscar");
        btnBuscar.disabled = true;
        let url = "";
        let id_user = "";
        let id_establecimiento = "";
        let fechaDesde = "";
        let fechaHasta = "";

        let fechaoDesde = "";
        let fechaoHasta = "";

        let fechamDesde = "";
        let fechamHasta = "";

        let dni_beneficiario = "";
        let referido = "";
        let tipo_atencion = "";
        let id_servicio = "";
        let nro_examen = "";
        let id_prioridad = "";
        let estado = "";

        let estado_informe = "";

        id_user = $("#id_user").val();
        id_establecimiento = $("#id_establecimiento").val();
        fechaDesde = $("#fechaDesde").val();
        fechaHasta = $("#fechaHasta").val();

        fechaoDesde = $("#fechaoDesde").val();
        fechaoHasta = $("#fechaoHasta").val();

        fechamDesde = $("#fechamDesde").val();
        fechamHasta = $("#fechamHasta").val();

        dni_beneficiario = $("#dni_beneficiario").val();
        referido = $("#referido").val();
        tipo_atencion = $("#tipo_atencion").val();
        id_servicio = $("#id_servicio").val();
        nro_examen = $("#nro_examen").val();
        id_prioridad = $("#id_prioridad").val();
        estado = $("#estado").val();

        estado_informe=$("#estado_informe").val();

        dataArray = [
            id_user,
            id_establecimiento,
            fechaDesde,
            fechaHasta,

            fechaoDesde,
            fechaoHasta,

            fechamDesde,
            fechamHasta,

            dni_beneficiario,
            referido,
            tipo_atencion,
            id_servicio,
            nro_examen,
            id_prioridad,
            estado,
            estado_informe
        ];

        url = "{{ route('listar_examenes_laboratorios',['dataArray' => ':dataArray']) }}";

        dataString = dataArray.join(',');

        url = url.replace(':dataArray',dataString)

        $.ajax({
                type: "GET",
                url: url,
                
                success: function(response) {
                    $("#divLista").html(response);
                    btnBuscar.disabled = false;
                },
                error: function(e) {
                    // Maneja los errores
                    console.log(e.message);
                    btnBuscar.disabled = false;
                }
            });
    });

    $("#reporteImagenologia").click(function (e) {
        
        // let fechaDesde = $("#fechaDesde").val();
        // let fechaHasta = $("#fechaHasta").val();
        // let dni_beneficiario = $("#dni_beneficiario").val();
        // let nro_prestacion = $("#nro_prestacion").val();
        // let nro_examen = $("#nro_examen").val();
        // let id_tipo_atencion = $("#tipo_atencion").val();
        // let referido = $("#referido").val();
        // let estado = $("#estado").val();
        // // Emergencia
        // let id_prioridad = $("#id_prioridad").val();
        // let id_servicio = $("#id_servicio").val();
        // //Servicio Imagenes
        // let id_servicio_imagenes = $("#id_servicio_imagenes").val();

        // $.ajax({
        //     type: "POST",
        //     url: "exportar_reporte_imagenes",
        //     data: {
        //         "fechaDesde" : fechaDesde,
        //         "fechaHasta" : fechaHasta,
        //         "dni_beneficiario" : dni_beneficiario,
        //         "nro_prestacion" : nro_prestacion,
        //         "nro_examen" : nro_examen,
        //         "id_servicio" : id_servicio,
        //         "id_tipo_atencion" : id_tipo_atencion,
        //         "referido" : referido,
        //         "estado" : estado,
        //         "id_prioridad" : id_prioridad,
        //         "id_servicio_imagenes" : id_servicio_imagenes
        //     },
        //     success: function (response) {
        //         console.log(response);
        //     }
        // }); 
    Swal.fire({
        title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
                header : 'swal2-header-generar'
        },
        onBeforeOpen: () => {
            $('#blur-container').append('<div class="blur-background"></div>');

            let fechaDesde = $("#fechaDesde").val();
            let fechaHasta = $("#fechaHasta").val();

            let fechaoDesde = $("#fechaoDesde").val();
            let fechaoHasta = $("#fechaoHasta").val();
            
            let fechamDesde = $("#fechamDesde").val();
            let fechamHasta = $("#fechamHasta").val();

            let dni_beneficiario = $("#dni_beneficiario").val();
            let nro_prestacion = $("#nro_prestacion").val();
            let nro_examen = $("#nro_examen").val();
            let id_tipo_atencion = $("#tipo_atencion").val();
            let referido = $("#referido").val();
            let estado = $("#estado").val();
                // Emergencia
            let id_prioridad = $("#id_prioridad").val();
            let id_servicio = $("#id_servicio").val();
                //Servicio Imagenes
            let id_servicio_imagenes = $("#id_servicio_imagenes").val();

            //Estado (en proceso, informado)
            let estado_informe = $('#estado_informe').val();

                Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "exportar_reporte_imagenes",
                data: {
                        "fechaDesde" : fechaDesde,
                        "fechaHasta" : fechaHasta,

                        "fechaoDesde" : fechaoDesde,
                        "fechaoHasta" : fechaoHasta,

                        "fechamDesde" : fechamDesde,
                        "fechamHasta" : fechamHasta,

                        "dni_beneficiario" : dni_beneficiario,
                        "nro_prestacion" : nro_prestacion,
                        "nro_examen" : nro_examen,
                        "id_servicio" : id_servicio,
                        "id_tipo_atencion" : id_tipo_atencion,
                        "referido" : referido,
                        "estado" : estado,
                        "id_prioridad" : id_prioridad,
                        "id_servicio_imagenes" : id_servicio_imagenes,
                        "estado_informe" : estado_informe
                },
                success: function (response) {
                    console.log(response);
                        console.log(response.excelNameResponse);
                    if (response.excelNameResponse) {
                            
                            // Obtener la URL del archivo del response
                        let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                        
                            // Crear un enlace de descarga
                        let link = document.createElement('a');
                        link.href = fileUrl;
                        link.download = 'Exportar Reportes de Imagenes.xlsx';
                            link.innerText = 'Descargar archivo';

                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                        link.click();
                            divLinkReporte.removeChild(link);

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
});

    //Contenedor Referidos
    $(document).ready(function () {
        let tipo_perfil = $('#tipo_perfil').val();
        if (tipo_perfil == '1') { //Fiscalizador responsable de laboratorio
            $("#tipo_atencion").change(function (e) { 
                e.preventDefault();
                if ($(this).val() !== 0) {
                    //$("#divReferidos").show();
                } else {
                    //$("#divReferidos").hide();
                }
            });
        }
        if (tipo_perfil == '0') { //Tecnico de laboratorio por farmacia
            let tipo_atencion = $("#tipo_atencion").val();
            if (tipo_atencion !== 0) {
                //$("#divReferidos").show();
            } else {
                //$("#divReferidos").hide();
            }
        }
        //REFERIDOS
        // if ($("#tipo_atencion").val() !== '0') {
        //     $("#divReferidos").show();
        // } else {
        //     $('#referido').val("0").trigger('change');
        //     //$("#divReferidos").hide();
        // }
        //PRIORIDADES
        if (($("#tipo_atencion").val() == '2')) {
            $.ajax({
                url: "obtener_prioridades_emergencia",
                success: function (response) {
                    let newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    let idPrioridadTemp = $('#id_prioridad_temp').val();
                    $(response).each(function(ii, oo) {
                    let selected = (idPrioridadTemp !== '' && idPrioridadTemp == oo.id) ? "selected" : "";
                    newOption += `<option value="${oo.id}" ${selected}>${oo.descripcion}</option>`;
                    });
                    $('#id_prioridad').html(newOption);
                    $("#id_prioridad").select2({
                    max_selected_options: 4
                    });
                }
            });
            $("#divPrioridad").show();
        } else {
            $("#divPrioridad").hide();
        }

        //ESPECIALIDADES DE CONSULTA EXTERNA
        let id_establecimiento = $('#id_establecimiento').val();
        let id_tipo_atencione = $('#tipo_atencion').val();
        if (id_tipo_atencione !== '2' && id_tipo_atencione !== '0') {
            $('#id_prioridad').html("");
            $.ajax({
            url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
            success: function(result) {
                console.log(result);
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar--</option>";
                let idServicioTemp = $('#id_servicio_temp').val();
                $(result).each(function(ii, oo) {
                let selected = (idServicioTemp !== '' && idServicioTemp == oo.id) ? "selected" : "";
                newOption += `<option value="${oo.id}" ${selected}> ${oo.nombre}</option>`;
                });
                $('#id_servicio').html(newOption);
                $("#id_servicio").select2({
                max_selected_options: 4
                });
            }
            });
        } else {
            $('#id_servicio').html("");
        }

        // ESPECIALIDADES DE EMERGENCIA PRIORIDADES
        let id_prioridad_temp = $('#id_prioridad_temp').val();
        if (id_prioridad_temp) {
            if ( id_prioridad_temp !== '0' ) {
                $.ajax({
                    url: "/get_topico_por_id_prioridad/" + id_prioridad_temp,
                    success: function (response) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        let idServicioTemp = $('#id_servicio_temp').val();
                        $(response).each(function(ii, oo) {
                        let selected = (idServicioTemp !== '' && idServicioTemp == oo.id) ? "selected" : "";
                        newOption += `<option value="${oo.id}" ${selected}>${oo.descripcion}</option>`;
                        });
                        $('#id_servicio').html(newOption);
                        $("#id_servicio").select2({
                        max_selected_options: 4
                        });
                    }
                });
            } else {
                $('#id_servicio').html("<option value='0'>--Seleccionar--</option>");
                $('#id_servicio').val("0").trigger('change');
            }
        } 

        //SERVICIOS DE IMAGENES
        //if (($('#tipo_atencion').val() !== '0')) {
            $.ajax({
                url: "/lista_servicios_imagenes",
                success: function (response) {
                    let newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    let idServicioImagenesTemp = $('#id_servicio_imagenes_temp').val();
                    $(response).each(function(ii, oo) {
                    let selected = (idServicioImagenesTemp !== '' && idServicioImagenesTemp == oo.id_servicio) ? "selected" : "";
                    newOption += `<option value="${oo.id_servicio}" ${selected}>${oo.nombre}</option>`;
                    });
                    $('#id_servicio_imagenes').html(newOption);
                    $("#id_servicio_imagenes").select2({
                    max_selected_options: 4
                    });
                }
            });
        //} else {
            //$('#id_servicio_imagenes').html("");
        //}

        // Referidos temp
        // let id_referido_temp = $('#id_referido_temp').val();
        // if (id_referido_temp) {
        //     let newOption = "";
        //     let i = 1;
        //     newOption = "<option value='0'>--Seleccionar--</option>";
        //     let idReferidoTemp = $('#id_referido_temp').val();
        //     for ( i; i <= 2 ; i++) {
        //         let selected = (idReferidoTemp !== '' && idReferidoTemp == i) ? "selected" : "";
        //         newOption += `<option value="${i}" ${selected}>${i == 1 ? 'SI' : 'NO'}</option>`;
        //     }
        //     $('#referido').html(newOption);
        //     $("#referido").select2({ max_selected_options: 4 });
        // }

        // Mostrar registrador
        if (($('#estado').val() == '2')) {
            $.ajax({
                url: "/lista_registrador_imagenes",
                success: function (response) {
                    var newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    let tipoRegistro =  $('#tipo_registro').val();
                    let idUser =  $('#id_user').val();
                    $(response).each(function(ii, oo) {
                    let selected = (idUser == oo.id && tipoRegistro == 7) ? "selected" : "";
                    newOption += `<option value="${oo.id}" ${selected}>${oo.paterno} ${oo.materno}, ${oo.name}</option>`;
                    });
                    $('#id_registrador_imagenes').html(newOption);
                    $("#id_registrador_imagenes").select2({
                    max_selected_options: 4
                    });
                }
            });
            $('#divRegistrador').show();
        } else {
            $('#id_registrador_imagenes').html("<option value='0'>--Seleccionar--</option>");
            $('#id_registrador_imagenes').val("0").trigger('change');
            $('#divRegistrador').hide();
        }
    });

    const limpiarVarTemp = () => {
        $('#id_prioridad_temp').val("");
        $('#id_servicio_temp').val("");
        $('#id_servicio_imagenes_temp').val("");
        $('#id_referido_temp').val("");
    }
    
    $("#tipo_atencion").change(function (e) { 
        e.preventDefault();
        let id_establecimiento = $('#id_establecimiento').val();
        let id_tipo_atencione = $(this).val();
        console.log(id_tipo_atencione);
        if (id_tipo_atencione == '0') {
            limpiarVarTemp()
            $('#id_servicio').html("<option value='0'>--Seleccionar--</option>");
        }
        if (id_tipo_atencione !== '2' && id_tipo_atencione !== '0') {
            limpiarVarTemp()
            $('#id_prioridad').html("");
            $.ajax({
            url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
            success: function(result) {
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar--</option>";
                $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                console.log(newOption);
                $('#id_servicio').html(newOption);
                $("#id_servicio").select2({
                max_selected_options: 4
                });
            }
            });
        }
    });
    
    // Prioridades de emergencia
    $(document).on('change', '#tipo_atencion', function(e) {
        e.preventDefault();
        if (($('#tipo_atencion').val() == '2')) {
            limpiarVarTemp()
            $.ajax({
                url: "obtener_prioridades_emergencia",
                success: function (response) {
                    let newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    $(response).each(function(ii, oo) {
                    newOption += `<option value="${oo.id}">${oo.descripcion}</option>`;
                    });
                    $('#id_prioridad').html(newOption);
                    $("#id_prioridad").select2({
                    max_selected_options: 4
                    });
                }
            });
            $("#divPrioridad").show();
            // Limpiamos el campo especialidad
            $('#id_servicio').html("<option value='0'>--Seleccionar--</option>");
            $('#id_servicio').val("0").trigger('change');
        } else {
            $('#id_prioridad').html("<option value='0'>--Seleccionar--</option>");
            $('#id_prioridad').val("0").trigger('change');
            $("#divPrioridad").hide();
        }
    });

    // Topico de Emergencia "Especialidad"
    $(document).on('change', '#id_prioridad', function(e) {
        e.preventDefault();
        let id_prioridad = $(this).val();
        if ( id_prioridad !== '0') {
            $.ajax({
                url: "/get_topico_por_id_prioridad/" + id_prioridad,
                success: function (response) {
                    var newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    $(response).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                    });
                    $('#id_servicio').html(newOption);
                    $("#id_servicio").select2({
                    max_selected_options: 4
                    });
                }
            });
        } else {
            $('#id_servicio').html("<option value='0'>--Seleccionar--</option>");
            $('#id_servicio').val("0").trigger('change');
        }
    });
    
    //Lista de Servicios Imagenes
    $("#tipo_atencion").change(function (e) { 
        e.preventDefault();
        // if (($(this).val() !== '0')) {
            $.ajax({
                url: "/lista_servicios_imagenes",
                success: function (response) {
                    var newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    $(response).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_servicio_imagenes').html(newOption);
                    $("#id_servicio_imagenes").select2({
                    max_selected_options: 4
                    });
                }
            });
        // } else {
        //     $('#id_servicio_imagenes').html("<option value='0'>--Seleccionar--</option>");
        //     $('#id_servicio_imagenes').val("0").trigger('change');
        // }
    });

    // Lista de Registrador Imagenes
    $(document).on('change', '#estado', function(e) {
        e.preventDefault();
        if (($('#estado').val() == '2')) {
            $.ajax({
                url: "/lista_registrador_imagenes",
                success: function (response) {
                    var newOption = "";
                    newOption = "<option value='0'>--Seleccionar--</option>";
                    let tipoRegistro =  $('#tipo_registro').val();
                    let idUser =  $('#id_user').val();
                    $(response).each(function(ii, oo) {
                    let selected = (idUser == oo.id && tipoRegistro == 7) ? "selected" : "";
                    newOption += `<option value="${oo.id}" ${selected}>${oo.paterno} ${oo.materno}, ${oo.name}</option>`;
                    });
                    $('#id_registrador_imagenes').html(newOption);
                    $("#id_registrador_imagenes").select2({
                    max_selected_options: 4
                    });
                }
            });
            $('#divRegistrador').show();
        } else {
            $('#id_registrador_imagenes').html("<option value='0'>--Seleccionar--</option>");
            $('#id_registrador_imagenes').val("0").trigger('change');
            $('#divRegistrador').hide();
        }
    });

    // Referidos
    // $(document).on('change', '#tipo_atencion', function(e) {
    //     e.preventDefault();
    //     if (($('#tipo_atencion').val() == '0')) {
            
    //         $('#referido').html("<option value='0'>--Seleccionar--</option>");
    //         $('#referido').val("0").trigger('change');

    //     } else {
    //         let newOption = "";
    //         let i = 1;
    //         newOption = "<option value='0'>--Seleccionar--</option>";
    //         for ( i; i <= 2 ; i++) {
    //             newOption += `<option value="${i}">${i == 1 ? 'SI' : 'NO'}</option>`;
    //         }
    //         $('#referido').html(newOption);
    //         $("#referido").select2({ max_selected_options: 4 });
    //     }
    // });

    //Ver pdfs

    $(document).on('click','.verPdf' , function () {

        let idRadiologia = $(this).data('id-rad');
        let idServicioRadiologia = $(this).data('id-servicio-rad');
        let nombreServicioRadiologia = $(this).data('nombre-servicio-rad');
        let id_pp = $(this).data('id-pp');

        $('#nropresmodal').text(nombreServicioRadiologia);
        $.ajax({
            url: `/obtener_pdfs_detalle_radiologia/${idRadiologia}/${idServicioRadiologia}/${id_pp}`,
            async: false,
            success: function(result){
                let listPdfs = "";
                listPdfs += `<div style="display : flex; flex-direction : column;">`;
                $(result).each(function(ii, oo) {
                    listPdfs += `<div class="col-sm-12">`;
                    listPdfs += `<div class="col-sm-10" style="padding-top:5px;">
                                    <label>${oo.codigo} - ${oo.nombre}</label>
                                </div>`;
                    listPdfs += `<div class="col-sm-2" style="padding-top:5px;">
                                    <a href="/informe_imagenes/${idRadiologia}/${idServicioRadiologia}/${oo.id}/0/${oo.flag}/${oo.ruta_prestacion_procedimiento}" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i></a>
                                    <a href="/ver_atencion_radiologia/${idRadiologia}/${idServicioRadiologia}/${id_pp}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                </div>`;
                    listPdfs += `</div>`;
                    $(oo.informes_adicionales).each(function(iii, ooo) {
                        listPdfs += `<div class="col-sm-12">`;
                        listPdfs += `<div class="col-sm-10" style="padding-top:5px;">
                                        <label>${oo.codigo} - ${oo.nombre} - Adenda</label>
                                    </div>`;
                        listPdfs += `<div class="col-sm-2" style="padding-top:5px;">
                                        <a href="/informe_imagenes/${idRadiologia}/${idServicioRadiologia}/${oo.id}/${ooo.id_informe_adicional}/${ooo.flag}/${ooo.ruta_informe_radiologia_adicional}" target="_blank" class="btn btn-xs" style="background-color : #005b80;" ><i style="color : white;" class="fa fa-download"></i></a>
                                        <a href="/ver_atencion_radiologia_adicional/${idRadiologia}/${idServicioRadiologia}/${id_pp}/${ooo.id_informe_adicional}" class="btn btn-success btn-xs" style="background-color : #005b80;" ><i class="fa fa-eye"></i></a>
                                    </div>`;
                        listPdfs += `</div>`;
                    });
                });
                listPdfs += `</div>`;
                $("#detalle-pdf").html(listPdfs);
            },
            complete: function () {
                $("#myModalDetPdfRadiologia").modal();
            }
        });

    });

    $('body').on('click', '#opciones_de_llamado', function () {   
        $('#FormularioOpcionesLlamado').trigger("reset");
        $('#modalOpcionesLlamado').modal({backdrop: 'static', keyboard: false, show: true})
        
        $('#paciente').html($(this).data('asegurado'));
        $('#id_cita').val($(this).data('id-cita'));
        $('#id_rad').val($(this).data('id-rad'));
        $('#id_servicio_rad').val($(this).data('id-servicio-rad'));
        $('#id_pp').val($(this).data('id-pp'));
        $('#modalOpcionesLlamado').modal('show');  
        // $('#fecha_ticket').html($(this).data('fecha_ticket'));
        // $('#cod_totem').html($(this).data('cod_totem'));
        // $('#totem_nombre').html($(this).data('totem_nombre')); 

        llamada($(this).data('id-cita'));
    });

    $('body').on('click', '#llamar_paciente', function () {  
        const id_cita=$('#id_cita').val(); 
        llamada(id_cita); 
    });

    function llamada(id_cita){
        const url = "{{ url('llamada_imagenes') }}";
        $.ajax({
        url: `${url}/${id_cita}`,
        dataType: "json",
        success: function(result){
            console.log(result);
            
            if(result.idCita !== 0){
                $('#cantidad_llamadas').html(result.cantidadLlamadas);
                
                Swal.fire({
                title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                html: '<p class="descripcion-alert"><span><b>ESPERE UN MOMENTO, PROCESO DE LLAMADO EN CURSO.</b><br></span></p>', 
                allowOutsideClick: false,
                timer: 4500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                }); 
            
            }
        }
        });
    }

    $(".close_llamado").click(function() {
        $("#modalOpcionesLlamado").hide();
        const id_cita=$('#id_cita').val(); 
        cancela_llamada(id_cita);
    });

    function cancela_llamada(id_cita){
        const url = "{{ url('cancela_llamada_imagenes') }}";
        $.ajax({
        url: `${url}/${id_cita}`,
        dataType: "json",
        success: function(result){
            console.log(result);
            
            if(result.update > 0){

                Swal.fire({
                    title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                    html: '<p class="descripcion-alert"><span><b>PROCESO DE LLAMADO, POSPUESTO.</b><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 700,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $('#formExamenRadiologia').submit();
                    }
                }); 
            
            }
        }
        });
    }

    $('#atender_paciente').click(function () {
        const id_rad = $('#id_rad').val();  
        const id_servicio_rad = $('#id_servicio_rad').val();  
        const id_pp = $('#id_pp').val();
        const id_cita = $('#id_cita').val();
        const url = "{{ url('radiologia_informe') }}";

        window.location.href = `${url}/${id_rad}/${id_servicio_rad}/${id_pp}/${id_cita}`;
    });

    function modalForm(id_cita) {
        $(".modal-dialog").css("width", "80%");
        $('#modalForm').modal('show');
        $('#modalForm .modal-body').css('height', 'auto');
        const url = "{{ url('modal_comentario_cita_imagenes') }}";
        $.ajax({
            url: `${url}/${id_cita}`,
            type: "GET",
            success: function(result) {
                $("#modalForm .modal-body").html(result);
            }
        });
    }

    function modalFormInvalidarOrden(estado,id_radiologia,id_cita) {
        $(".modal-dialog").css("width", "80%");
        $('#estado_invalidar').val(estado);
        $('#id_radiologia_invalidar').val(id_radiologia);
        $('#id_cita_invalidar').val(id_cita);
        $('#modalFormInvalidarOrden').modal('show');
        $('#modalFormInvalidarOrden .modal-body').css('height', 'auto');
    }

    $(document).on('click','#guardarInvalidaComentario', function () {        
        let estado = $('#estado_invalidar').val();
        let id_radiologia = $('#id_radiologia_invalidar').val();
        let id_cita = $('#id_cita_invalidar').val();
        let invalidaComentario = $('#invalidaComentario').val();

        console.table(`Estado : ${estado}`);
        console.table(`ID de radiologia : ${id_radiologia}`);
        console.table(`ID de la cita : ${id_cita}`);
        console.table(`Comentario : ${invalidaComentario}`);

        if (estado == "-1" || estado == "-2") {
            tipoo = 1;
            msj = "&iquest;Está seguro de invalidar la orden?";
        } else {
            tipoo = 2;
            msj = "Toda cita relacionada con esta orden se invalidará.&iquest;Desea continuar?";
        }
            
        bootbox.confirm({
            size: "small",
            message: msj,
            callback: function(result) {
                if (result == true) {
                    fn_anularOrden(tipoo,id_radiologia,invalidaComentario,id_cita);
                }
            }
        });
    });

    function fn_anularOrden(tipo,id_radiologia,invalidaComentario,id_cita) {
        $.ajax({
            url: `/invalidar_orden_imagenes/2/${tipo}/${id_radiologia}/${invalidaComentario}/${id_cita}`,
            type: "GET",
            success: function(result) {
                console.log('Resultado : ' + result);
                if (result == 'true') {
                    limpiarModalInvalidar();
                    Swal.fire({
                        icon: 'success',
                        title: 'Se guardo con exito!',
                        showConfirmButton: false,
                        timer: 2000,
                        onClose: function() {
                            $('#modalFormInvalidarOrden').modal('hide');
                            $('#formExamenRadiologia').submit();
                        }
                    });
                    
                } else {
                    limpiarModalInvalidar();
                    Swal.fire({
                        icon: 'error',
                        title: 'Alerta',
                        html: 'Hubo un error al invalidar la orden',
                        showConfirmButton: true,
                    });
                }
            }
        });
    }
    $(document).ready(function () {
        // Guardar referencias a los elementos
        const $divcita = $('#divcita');
        const $divcita1 = $('#divcita1');
        const $divmuestra = $('#divmuestra');
        const $divmuestra1 = $('#divmuestra1');
        const $divestadoinf =$('#divestadoinf');
        const $divorden =$('#divorden');
        const $divorden1 =$('#divorden1');
        const $estado = $('#estado');
        
        // Comprobar el valor inicial del #estado
        const estadoInicial = $estado.val().toLowerCase();
        toggleDivs(estadoInicial);

        // Cambio de estado
        $estado.on('change', function () {
            const estado = $(this).val().toLowerCase();
            toggleDivs(estado);
        });

        function toggleDivs(estado) {
            $divcita.show();
            $divcita1.show();
            $divmuestra.show();
            $divmuestra1.show();
            $divorden.show();
            $divorden1.show();
            $divestadoinf.hide();

             // Fecha de hoy en formato DD-MM-YYYY
            const hoy = new Date();
            const fechaHoy = ("0" + hoy.getDate()).slice(-2) + '-' +("0" + (hoy.getMonth() + 1)).slice(-2) + '-' + hoy.getFullYear();

            if(estado == '1'){
                $divmuestra.hide();
                $divmuestra1.hide();
                $divcita.hide();
                $divcita1.hide();
                // $divorden.hide();
                // $divorden1.hide();
                $divestadoinf.hide();
                //fecha predeterminada
                    // Si no hay valores, poner hoy
                    if (!$('#fechaoDesde').val()) $('#fechaoDesde').val(fechaHoy);
                    if (!$('#fechaoHasta').val()) $('#fechaoHasta').val(fechaHoy);
                // Vaciar los campos de fecha
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
                $('#fechamDesde').val('');
                $('#fechamHasta').val('');

            }else if (estado == '2') {
                $divcita.hide();
                $divcita1.hide();
                $divmuestra.hide();
                $divmuestra1.hide()
                // $divorden.hide();
                // $divorden1.hide();
                $divestadoinf.show();
                //fecha predeterminada
                    // Si no hay valores, poner hoy
                    if (!$('#fechaoDesde').val()) $('#fechaoDesde').val(fechaHoy);
                    if (!$('#fechaoHasta').val()) $('#fechaoHasta').val(fechaHoy);
                // Vaciar los campos de fecha
                $('#fechamDesde').val('');
                $('#fechamHasta').val('');
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
            } else if (estado == '-1' || estado == '-2' || estado == '-3') {
                $divcita.hide();
                $divcita1.hide();
                $divmuestra.hide();
                $divmuestra1.hide();
                //fecha predeterminada
                    // Si no hay valores, poner hoy
                    if (!$('#fechaoDesde').val()) $('#fechaoDesde').val(fechaHoy);
                    if (!$('#fechaoHasta').val()) $('#fechaoHasta').val(fechaHoy);
                // Vaciar los campos de fecha
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
                $('#fechamDesde').val('');
                $('#fechamHasta').val('');
            }
        }
    });
    
    document.getElementById('estado').addEventListener('change', function () {
        const estadoInforme = document.getElementById('estado_informe');
        estadoInforme.value = "0";
    });

    $('#modalFormInvalidarOrden').on('click','.cancelarInvalidaComentario', function () {   
        console.log('click cancelarInvalidaComentario');
        limpiarModalInvalidar();
        console.log('limpiado correctamente');
        $('#modalFormInvalidarOrden').modal('hide');
    });

    const limpiarModalInvalidar = () => {
        $('#estado_invalidar').val('');
        $('#id_radiologia_invalidar').val('');
        $('#id_cita_invalidar').val('');
        $('#invalidaComentario').val('');
    }

    // $('#modalFormInvalidarOrden').on('hidden.bs.modal', function () {
    //     console.log('click cancelarInvalidaComentario');
    //     $('#estado_invalidar').val('');
    //     $('#id_radiologia_invalidar').val('');
    //     $('#id_cita_invalidar').val('');
    //     $('#Invalidacomentario').val('');
    // });
    
    function regularizarSiteds(id, id_establecimiento, messageLoading = "Regularizando") {
      Swal.fire ({
        title:  `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>${messageLoading}</span></div>`,
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false, showConfirmButton: false, allowOutsideClick: false, allowEscapeKey: false,
        onBeforeOpen: () => Swal.showLoading(),
      })
      const body = {
        recordId: id,
        moduleId: 5,
        establecimientoId: id_establecimiento,
        tipoAtencionId: 1,
        servicioId: "9"
      }
      $.ajax({
        type: "POST",
        url: "/regularize-multiple-siteds-temporary",
        contentType: "application/json",
        data: JSON.stringify(body),
        dataType: "json",
        success: function (response) {
          const messageSuccess = response.message || "¡La regularización de los códigos SITEDS temporales se completó con éxito!"
          Swal.fire({
            icon: 'success',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageSuccess}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
          });
          $('#frmBloque').submit();
        },
        error: function(xhr, status, error){
            const session = {!! json_encode(Auth::user()) !!}
            const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
            if (hasPermissionUpdateSitedsManual) {
              showModalSitedsManual().then((resultModal) => {
                if (resultModal && resultModal.isConfirmed && resultModal.value) {
                    updateSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
                }
              })
            } else {
              const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
            }
        },
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
      });
    }

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

    function updateSiteds(id, nuAutorizacion, message) {
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

      $.ajax({
        url: `/api/radiologia/${id}/update-code-siteds`,
        type: 'PUT',
        data: { cod_siteds_susalud: nuAutorizacion },
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
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {},
        complete: function(xhr, status) {
            Swal.hideLoading();
            Swal.close();
        }
      });
    };

</script>
<script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
        <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; color: rgb(255, 255, 255);"></span> Buscar Examenes de Radiologia - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <form id="formExamenRadiologia" class="form-horizontal" method="post" action="{{ route('listar_examenes_radiologicos')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="id_user" name="id_user" value="{{ auth()->user()->id }}">
                <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{ $id_establecimiento }}">
                <input type="hidden" id="tipo_perfil" name="tipo_perfil" value="{{ $tipo_perfil }}">
                <input type="hidden" id="tipo_registro" name="tipo_registro" value="{{ auth()->user()->tipo_registro }}">
                {{-- variables de retorno --}}
                <input type="hidden" id="id_prioridad_temp" name="id_prioridad_temp" value="{{ $id_prioridad_temp }}">
                <input type="hidden" id="id_servicio_temp" name="id_servicio_temp" value="{{ $id_servicio_temp }}">
                <input type="hidden" id="id_servicio_imagenes_temp" name="id_servicio_imagenes_temp" value="{{ $id_servicio_imagenes_temp }}">
                <input type="hidden" id="id_referido_temp" name="id_referido_temp" value="{{ $id_referido_temp }}">
                <div class="row">
                    <div class="form-group" style="margin: 0; padding: 0.5rem 0 0.5rem 0;">
                        
                        <div id="divorden" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Orden Desde</label>
                            <input class="form-control input-sm" type="text" name="fechaoDesde" id="fechaoDesde" placeholder="Fecha Orden Desde" value="<?php echo $fechaoDesde ?>" maxlength="10">  
                        </div>
                        <div id="divorden1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Orden Hasta</label>
                            <input class="form-control input-sm" type="text" name="fechaoHasta" id="fechaoHasta" placeholder="Fecha Orden Hasta" value="<?php echo $fechaoHasta ?>" maxlength="10">
                        </div>

                        <!-- <div id="divcita" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Desde</label>
                            <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Cita Desde" value="<?php echo $fechaDesde ?>" maxlength="10">  
                        </div>
                        <div id="divcita1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Hasta</label>
                            <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Cita Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        </div>
                        <div id="divmuestra" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Muestra Desde</label>
                            <input class="form-control input-sm" type="text" name="fechamDesde" id="fechamDesde" placeholder="Fecha Muestra Desde" value="<?php echo $fechamDesde ?>" maxlength="10">  
                        </div>
                        <div id="divmuestra1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Muestra Hasta</label>
                            <input class="form-control input-sm" type="text" name="fechamHasta" id="fechamHasta" placeholder="Fecha Muestra Hasta" value="<?php echo $fechamHasta ?>" maxlength="10">
                        </div> -->
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" for="textinput">Nro. Documento</label>  
                            <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Tipo Atención</label>
                                <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion">
                                    <option value='0'>--Seleccionar--</option>
                                    <?php foreach ($tipo_atenciones as $row) : ?>
                                    <option value="<?php echo $row->id  ?>" @if ( isset($id_tipo_atencion)) @if ($row->id == $id_tipo_atencion) selected @endif @endif><?php echo $row->nombre  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <!-- {{--<div id="divReferidos" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Referido</label>
                            <select class="form-control input-sm" name="referido" id="referido">
                                
                            </select>
                        </div>--}}
                        {{--<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Farmacia</label>
                            <select class="form-control input-sm" name="id_farmacia" id="id_farmacia">
                                <option value="0">--Seleccionar--</option>
                                php //foreach ($farmacias as $row) : ?>
                                <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                php //endforeach; ?>
                            </select>
                        </div>--}}
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Servicios de Imagenes</label>
                            <select class="form-control input-sm" name="id_servicio_imagenes" id="id_servicio_imagenes">
                                <option value='0'>--Seleccionar--</option>
                                @foreach ( $listServiciosImagenes as $row )
                                    <option value="{{$row->id_servicio}}" @if ( $row->id_servicio == $id_servicio_imagenes_temp ) selected @endif >{{$row->nombre}}</option>
                                @endforeach
                            </select>
                        </div> -->
                        @if(auth()->user()->tipo_registro == 6)
                                @if (auth()->user()->flagfiscalizador == 1)
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Estado</label>
                                            <select class="form-control input-sm" name="estado" id="estado">
                                                <!-- <option value="-2" @if ($id_estado_temp == "-2") selected @endif>Ordenes Sin Firmar</option> -->
                                                <option value="-1" @if ($id_estado_temp == "-1") selected @endif>Ordenes Sin Cita</option> 
                                                <option value="-3" @if ($id_estado_temp == "-3") selected @endif>Atendido Sin Cita</option>
                                                <option value="1" @if ($id_estado_temp == "1") selected @endif>Pendiente</option> 
                                                <option value="2" @if ($id_estado_temp == "2") selected @endif>Atendido</option>
                                                <!-- <option value="3" @if ($id_estado_temp == "3") selected @endif>Todos </option>
                                                <option value="4" @if ($id_estado_temp == "4") selected @endif>Observado</option>
                                                <option value="7" @if ($id_estado_temp == "7") selected @endif>Anulado</option>  -->
                                            </select>
                                    </div>
                                @else
                                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Estado</label>
                                        <select class="form-control input-sm" name="estado" id="estado">
                                            <!-- <option value="-2" @if ($id_estado_temp == "-2") selected @endif>Ordenes Sin Firmar</option-->
                                            <option value="-1" @if ($id_estado_temp == "-1") selected @endif>Ordenes Sin Cita</option>
                                            <!--option value="-3" @if ($id_estado_temp == "-3") selected @endif>Atendido Sin Cita</option>  -->
                                            <!-- <option value="1" @if ($id_estado_temp == "1") selected @endif>Pendiente</option>  -->
                                            <option value="1" @if (!isset($id_estado_temp) || $id_estado_temp == "1" || $id_estado_temp == "") selected @endif>Pendiente</option>
                                            <!-- <option value="2" @if ($id_estado_temp == "2") selected @endif>Atendido</option>
                                            <!-- <option value="3" @if ($id_estado_temp == "3") selected @endif>Todos </option> -->
                                            <!-- <option value="4" @if ($id_estado_temp == "4") selected @endif>Observado</option>
                                            <option value="7" @if ($id_estado_temp == "7") selected @endif>Anulado</option>  --> -
                                        </select>
                                </div>
                                @endif
                            @elseif( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7)
                                @if (auth()->user()->flagfiscalizador == 1)
                                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Estado</label>
                                        <select class="form-control input-sm" name="estado" id="estado">
                                            <!-- <option value="-2" @if ($id_estado_temp == "-2") selected @endif>Ordenes Sin Firmar</option> -->
                                            <option value="-1" @if ($id_estado_temp == "-1") selected @endif>Ordenes Sin Cita</option> 
                                            <option value="-3" @if ($id_estado_temp == "-3") selected @endif>Atendido Sin Cita</option>
                                            <option value="1" @if ($id_estado_temp == "1") selected @endif>Pendiente</option> 
                                            <option value="2" @if ($id_estado_temp == "2") selected @endif>Atendido</option>
                                            <!-- <option value="3" @if ($id_estado_temp == "3") selected @endif>Todos </option>
                                            <option value="4" @if ($id_estado_temp == "4") selected @endif>Observado</option>
                                            <option value="7" @if ($id_estado_temp == "7") selected @endif>Anulado</option>  -->
                                        </select>
                                </div>
                                @else
                                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Estado</label>
                                        <select class="form-control input-sm" name="estado" id="estado">
                                            <!-- <option value="-2" @if ($id_estado_temp == "-2") selected @endif>Ordenes Sin Firmar</option>
                                            <option value="-1" @if ($id_estado_temp == "-1") selected @endif>Ordenes Sin Cita</option--> 
                                            <option value="-3" @if ($id_estado_temp == "-3") selected @endif>Atendido Sin Cita</option>
                                            <!--option value="1" @if ($id_estado_temp == "1") selected @endif>Pendiente</option>  -->
                                            <!-- <option value="2" @if ($id_estado_temp == "2") selected @endif>Atendido</option> -->
                                            <option value="2" @if (!isset($id_estado_temp) || $id_estado_temp == "2" || $id_estado_temp == "") selected @endif>Atendido</option>
                                            <!-- <option value="3" @if ($id_estado_temp == "3") selected @endif>Todos </option>  -->
                                            <!-- <option value="4" @if ($id_estado_temp == "4") selected @endif>Observado</option>
                                            <option value="7" @if ($id_estado_temp == "7") selected @endif>Anulado</option> -->
                                        </select>
                                </div>
                                @endif
                                
                            @endif
                        
                        <div id= "divestadoinf" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Estado Informe</label>
                                <select class="form-control input-sm" name="estado_informe" id="estado_informe"> 
                                        <option value="0" @if ($id_estado_inf_temp == "0") selected @endif>--Seleccionar--</option>
                                        <option value="1" @if ($id_estado_inf_temp == "1") selected @endif>En proceso</option>
                                        <option value="2" @if ($id_estado_inf_temp == "2") selected @endif>Informado</option>
                                </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="form-group" style="margin: 0; padding: 0.5rem 0 0.5rem 0;"> -->
                        <!--div class="form-group" style="margin: 0; padding: 0.5rem 0 0.5rem 0;"-->
                            
                            <!-- <div id="divRegistrador" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                                <label class="control-label">Registrador</label>
                                <select class="form-control input-sm" name="id_registrador_imagenes" id="id_registrador_imagenes"></select>
                            </div>
                            <div id="divPrioridad" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                                <label class="control-label">Prioridad</label>
                                <select class="form-control input-sm" name="id_prioridad" id="id_prioridad"> </select>
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12" >
                                <label class="control-label">Especialidad</label>
                                <select class="form-control input-sm" name="id_servicio" id="id_servicio">
                                </select>
                            </div> -->
<!--                             
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label" for="textinput">Nro. Ex Radiologico</label>  
                                <input id="nro_examen" name="nro_examen" type="text" placeholder="" class="form-control input-sm" value="<-?php echo $nro_examen ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                            </div> -->
                        
                        
                            
                            
                    <!-- </div> -->
                </div>
                
                <div class="row">
                    <div class="form-group" style="margin: 0; padding: 0 ;display: flex; justify-content: flex-end;">
                        
                        <!--<div class="col-md-2">
                            <label class="col-md-2 control-label" for="textinput">Nro. Prestacion</label>  
                            <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="nro_prestacion" id="nro_prestacion" value="<-?php echo $nro_prestacion ?>"  style="text-transform:uppercase;" maxlength="12">
                        </div>-->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: flex; justify-content: flex-end;">
                            <div class="col-md-4">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 2.6rem; padding-bottom: 2.6rem;">
                                    <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                                </div>
                            </div>
                            @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 7 || Auth::user()->tipo_registro == 6)
                                <!-- <div class="col-md-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 2.6rem; padding-bottom: 2.6rem">
                                        <a class="btn btn-success form-control input-sm"
                                            id="reporteImagenologia">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                        </a>
                                    </div>
                                </div> -->
                            @endif
                            @if (auth()->user()->flagfiscalizador == 1)
                                <!-- <div class="col-md-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 2.6rem; padding-bottom: 2.6rem;/*display: none;*/ ">
                                        <button class="btn btn-danger form-control input-sm" id="firmarResultadoLaboratorio">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Firmar
                                        </button>
                                    </div>
                                </div> -->
                            @endif
                        </div>    
                    </div> 
                </div>
                <!-- <div class="row">
                    <div class="col-lg-6">
                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 2.6rem; padding-bottom: 2.6rem; display: none;">
                            <button class="btn btn-danger form-control input-sm" id="firmarResultadoLaboratorio">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Firmar
                            </button>
                        </div> --}}
                    </div>
                </div> -->
            </form>
            <div class="col-md-12">
                <legend>
                    <div class="pull-right" style="font-size: 16px; margin: 5px">Total: {{$cantidad}} examen(es) encontrado(s)</div>
                </legend>
            </div>                
            {{-- <div class="col-md-12">
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar examen">
            </div> --}}  
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr class="table-header">
                                <th>N</th>
                                <th>Paciente</th>
                                <th>Solicitud</th>
                                <th>Examen</th>
                                <th>Cita</th>
                                <th>Toma Muestra</th>
                                <th>Informe Radiologico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $n = 0;
                        function calcularEdad($fechaNacimiento) {
                            // Convertir la fecha de formato DD/MM/YYYY a un objeto DateTime
                            $partes = explode('-', $fechaNacimiento);
                            //dd($partes);
                            $dia = (int)$partes[0];
                            $mes = (int)$partes[1];
                            $anio = (int)$partes[2];
                            
                            $fechaNac = new DateTime("$anio-$mes-$dia");
                            $hoy = new DateTime('now');
                            
                            // Calculamos la diferencia entre las fechas
                            $diferencia = $hoy->diff($fechaNac);
                            
                            // Retornamos un array con los años, meses y días
                            return array(
                                'anios' => $diferencia->y,
                                'meses' => $diferencia->m,
                                'dias' => $diferencia->d
                            );
                        }
                        ?>
                            @foreach ( $radiologias as $r )
                            <tr>
                                {{-- Número --}}
                                <td > <?php $n++; echo $n; ?> </td>
                                {{-- Paciente --}}
                                <td>
                                    <div> <?php echo $r->nombre_beneficiario . ", ".  $r->paterno_beneficiario ." ".  $r->materno_beneficiario ?></div>
                                    <div>  {{$r->tipo_doc_ident}} - {{$r->nro_doc_ident}} - ({{$r->sexo_beneficiario == "F" ? "FEMENINO":"MASCULINO"}})</div>
                                    <div>  {{\Carbon\Carbon::parse($r->fecha_nac_beneficiario)->format('d/m/Y')}} -
                                    <?php
                                    $edad = calcularEdad($r->fecha_nac_beneficiario);
                                    echo $edad['anios'] . " A, " . $edad['meses'] . " M y " . $edad['dias'] . " D";  
                                    
                                    ?>
                                    </div>
                                    <div><strong>Condicion:</strong>{{$r->referido == "NO" ? "NO REFERIDO":"REFERIDO"}}</div>
                                </td> 
                                <td style="position: relative; width: 215px;">
                                    <div><strong>N°:</strong> {{$r->id_rad}} {{\Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y')}}</div>
                                    <div>
                                        @if ($r->id_tipo_atencion_prestacion == 1)
                                            {{$r->nombre_tipo_atencion}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            @if ($r->id_evaluacion > 0)
                                                EMERGENCIA - EVALUACIÓN
                                            @else
                                                {{$r->nombre_tipo_atencion}}
                                            @endif
                                        @elseif ($r->id_tipo_atencion_prestacion == 3)
                                            @if ($r->id_evaluacion > 0)
                                                HOSPITALIZACIÓN - EVOLUCIÓN
                                            @else
                                                {{$r->nombre_tipo_atencion}}
                                            @endif
                                        @endif
                                    </div>
                                    <div>
                                        @if ($r->id_tipo_atencion_prestacion == 1)
                                            {{$r->consultorio}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            {{$r->especialidad_emergencia}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 3 )
                                            {{$r->ubicacion_cama->consultorio}}            
                                        @endif
                                    </div>
                                    <div class="div-icon" > 
                                        <a href="/ver_radiologia_imprimir/<?php
                                            if ($r->ruta_radiologia !== ""){
                                                echo $r->id_rad;  // Resultado: 2025/05/19
                                            } else {
                                                echo "id_radiología incorrecta" ;
                                            }
                                            
                                            ?>"
                                            target="_blank">
                                                <i class="fa fa-search font-iconos-tables" title="Ver Orden (RUA)" aria-hidden="true" ></i>
                                        </a>                                
                                    </div>   
                                </td>
                                <td style="position: relative; width: 190px;">
                                    <div><strong>CPMS</strong>  - {{$r->codigo_cpms_alterno}} </div>
                                    <div>  {{$r->descripcion_cpms}}</div>   
                                       
                                </td>
                                
                                <td style="position: relative;width: 190px;">
                                    <div>  {{ \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A') }}</div>  
                                    
                                    
                                </td>
                                {{-- Toma de muestras --}}
                                <td style="position: relative;">
                                    <div>{{$r->confirmar_toma_created_at == null ? "Pendiente" :\Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') }}  </div>
                                    <div>{{$r->nom_servicio}}</div>  
                                    @if (strlen($r->cod_siteds_susalud) < 4 || substr($r->cod_siteds_susalud, -1) === "T")
                                        <div style="display: inline-flex; align-items: center;">
                                            <span class="label label-warning" >{{$r->cod_siteds_susalud}}</span><br>
                                            <button 
                                                type="button" 
                                                style="margin-top: 5px; font-size: 10px, ; padding: 1px 1px;margin-left: 2px;"
                                                class="btn btn-xs btn-warning"
                                                data-toggle="tooltip"
                                                data-html="true"
                                                title="<b>Regularizar</b>"
                                                onclick="regularizarSiteds({{$r->id_rad}}, {{$id_establecimiento}})"
                                            ><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                        </div>
                                    @else
                                        <span class="label label-success">{{$r->cod_siteds_susalud}}</span>
                                    @endif 
                                </td>
                                {{-- resultado --}}
                                <td style="position: relative;">
                                    
                                </td>
                                @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 6 || Auth::user()->tipo_registro == 7)
                                    <td>
                                        @if($farmacia->id == 76)
                                            <div style="display: flex; flex-direction: row; gap: 1rem;">
                                                <table>
                                                    <tr>
                                                    @foreach($r->servicio_examen as $servicio)
                                                        <td>
                                                        @if($servicio->estado_examen !== null )
                                                            @if($servicio->flag_validated == "1")
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")    
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button class="btn btn-default btn-xs verPdf" id="verPdf" data-id-rad="{{$r->id_rad}}" data-id-servicio-rad="{{$servicio->id_servicio_radiologia}}" data-nombre-servicio-rad="{{$servicio->servicio}}" data-id-pp = "{{$r->id_pp}}" >
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                @elseif ($id_estado_temp == "1")
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button class="btn btn-default btn-xs verPdf" disabled >
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")   
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                @elseif ($id_estado_temp == "1")
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button disabled class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($id_estado_temp == "1" || $id_estado_temp == "3" || $id_estado_temp == "-1")    
                                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                    @if ($id_estado_temp == "-1")    
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},0)"><i class="fa fa-edit"></i></button>
                                                                    @elseif ($id_estado_temp == "3")
                                                                        @if ($r->ruta_radiologia != "")
                                                                            <button class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},0)">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                                    @endif
                                                                </div>
                                                            @elseif ($id_estado_temp == "2")
                                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                    <button disabled class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                                </div>
                                                            @endif
                                                        @endif
                                                        </td>
                                                    @endforeach
                                                    </tr>
                                                </table>
                                                @if(($r->cod_siteds_susalud!='') /*&& ($r->fecha_hora_cita_radiologia !== "") && ($r->fecha_hora_cita_radiologia !== null)*/)
                                                    <table>
                                                        <tr>
                                                        @foreach($r->informe_adicional as $informe)
                                                            <td>
                                                            @if($informe->estado_examen !== null )
                                                                @if($informe->flag_validated == "1")
                                                                    @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")    
                                                                        <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Informe Adicional<br>{{$informe->servicio}}</b>">
                                                                            <div id="icon-doc-firmado_imagenes" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                                                                <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")   
                                                                        <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$informe->servicio}}</b>">
                                                                            <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-xs" onclick="modificarOValidarInformeAdicional({{$r->id_rad}},{{$informe->id_servicio_radiologia}},{{$r->id_pp}},{{$informe->id_informe_adicional}})" style="background-color : #005b80;" ><i class="fa fa-edit" style="color: white;"></i></button>
                                                                        </div>
                                                                    @elseif ($id_estado_temp == "1")
                                                                        <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$informe->servicio}}</b>">
                                                                            <button disabled class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            </td>
                                                        @endforeach
                                                        </tr>
                                                    </table>
                                                @else
                                                    {{--<button disabled class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>--}}
                                                @endif
                                            </div>
                                        @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender Examen</b>">
                                            <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="atenderOtrosEstablecimientosDiferentesAlCentral({{$r->id_rad}})"><i class="fa fa-edit"></i></button>
                                        </div>
                                        @endif
                                    </td>
                                @endif
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>









                    <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                        <thead>
                            <tr>
                                <th>N</th>
                                {{--<th>Nro. Examen</th>--}}
                                <th>Fecha Orden</th>
                                @if ($id_estado_temp !== "-2" && $id_estado_temp !== "-1" && $id_estado_temp !== "-3") {{--Solo entra si tiene cita--}}
                                    <!-- <th>Hora<br/>Cita</th> -->
                                    <th>Fecha Cita</th>
                                    {{--<th>Fecha Muestra</th>--}}
                                    <th>Consultorio</th>
                                    @elseif ($id_estado_temp === "-3")
                                    {{--<th>Fecha Muestra</th>--}}
                                @endif
                                {{-- <th>Nro. Prestación</th> --}}
                                <th>Tipo de Atención</th>
                                <!-- <th>Especialidad</th> -->
                                <th>Procedencia</th>
                                {{-- <th>Farmacia</th> --}}
                                {{-- <th>Hora<br/>Registro</th>
                                <th>Fecha<br/>Orden</th> --}}
                                {{-- @if ($id_tipo_atencion == 1)
                                    <th>Hora<br/>Cita Próxima del Servicio</th>
                                    <th>Fecha<br/>Cita Próxima del Servicio</th>
                                @endif --}}
                                {{-- <th>Tipo y Nro. Doc.</th> --}}
                                <th>Doc. Identidad</th>
                                <th>Beneficiario</th>
                                <th>Servicio Imagenes</th>
                                <th>Examen</th>
                                {{-- <th>Referido</th> --}}
                                <th style="text-align:center">Pdf Examen</th>
                                @if ($id_estado_temp !== "-2"/* && $id_estado_temp !== "-1"*/)
                                    <th>Cod SITEDS</th>
                                @endif
                                @if ($id_estado_temp !== "-2"/* && $id_estado_temp !== "-1"*/)   
                                    @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 6 || Auth::user()->tipo_registro == 7)
                                        <th width="100px">Atender</th>
                                    @endif
                                @endif
                                @if ($id_estado_temp !== "-2" && $id_estado_temp !== "-1")    
                                    <th>Totem</th>
                                    <th>Fecha<br/>Presencia<br/>Totem</th>
                                    <th>Hora<br/>Presencia<br/>Totem</th>
                                @endif
                                @if (($id_estado_temp == "1" && $id_estado_temp !== "-2" && $id_estado_temp !== "-1" ) && auth()->user()->id_establecimiento == 76 && auth()->user()->flagllamadacita == 1) <th>Llamar</th> @endif
                                @if ($id_estado_temp == "1" ) <th>Observación</th> @endif
                                @if ($id_estado_temp == "1" || $id_estado_temp == "-2" || $id_estado_temp == "-1" ) <th>Anular</th> @endif
                                @if ($id_estado_temp !== "1" && $id_estado_temp !== "-2" && $id_estado_temp !== "-1" ) <th>Nuevo Informe</th> @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($radiologias))
                            <?php $n = 0; ?>
                            @foreach($radiologias as $r)
                                <tr>
                                <td><?php
                                //dd($r);
                                    $n++;
                                    echo $n;
                                    ?></td>
                                {{--<td>{{$r->id_rad}}</td>--}}
                                @if(!empty($r->id_evaluacion)&& ($r->id_tipo_atencion_prestacion ==3) )
                                <td>{{date("d-m-Y",strtotime($r->fecha_evolucion))}}</td>
                                @else
                                <td>{{date("d-m-Y",strtotime($r->fecha_orden))}}</td>
                                @endif
                                @if ($id_estado_temp !== "-2" && $id_estado_temp !== "-1" && $id_estado_temp !== "-3") {{--Solo entra si tiene cita--}}
                                    {{-- Hora / Cita --}}
                                    <!--?php
                                        $originalHoraCita = $r->fecha_hora_cita_radiologia;
                                        $horaCita = date("H:i:s", strtotime($originalHoraCita));
                                        echo "<td>" . $horaCita ."</td>";
                                    ?-->
                                    {{-- Fecha / Cita --}}
                                    <!--?php
                                        $originalFechaCita = $r->fecha_hora_cita_radiologia;
                                        $FechaCita = date("d-m-Y", strtotime($originalFechaCita));
                                         $originalHoraCita = $r->fecha_hora_cita_radiologia;
                                        $horaCita = date("H:i:s", strtotime($originalHoraCita)); 
                                        echo "<td>" . $FechaCita . " ".$horaCita. "</td>";
                                    ?-->
                                    <?php

                                        if (!empty($r->fecha_hora_cita_radiologia) ) {
                                            $originalFechaCita = $r->fecha_hora_cita_radiologia;
                                            $FechaCita = date("d-m-Y", strtotime($originalFechaCita));
                                            $originalHoraCita = $r->fecha_hora_cita_radiologia;
                                            $horaCita = date("H:i:s", strtotime($originalHoraCita));
                                            echo "<td>" . $FechaCita . " ".$horaCita. "</td>";
                                        } else {
                                            echo "<td>"."-"."</td>";
                                        }
                                    ?>
                                    {{--<td>
                                        {{ !empty($r->confirmar_toma_created_at) ? \Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d-m-Y') : '-' }}
                                    </td>--}}
                                    <td>
                                        {{$r->servicio_imagen}}
                                    </td>

                                    @elseif ($id_estado_temp === "-3") 
                                    {{--<td>
                                        {{ !empty($r->confirmar_toma_created_at) ? \Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d-m-Y') : '-' }}
                                    </td>--}}
                                @endif
                                {{-- <td>{{$r->id_prestacion}}</td> --}}
                                <td>
                                        @if ($r->id_tipo_atencion_prestacion == 1)
                                            {{$r->nombre_tipo_atencion}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            @if ($r->id_evaluacion > 0)
                                                EMERGENCIA - EVALUACIÓN
                                            @else
                                                {{$r->nombre_tipo_atencion}}
                                            @endif
                                        @elseif ($r->id_tipo_atencion_prestacion == 3)
                                            @if ($r->id_evaluacion > 0)
                                                HOSPITALIZACIÓN - EVOLUCIÓN
                                            @else
                                                {{$r->nombre_tipo_atencion}}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    
                                    @if ($r->id_tipo_atencion_prestacion == 1)
                                        {{$r->consultorio}}
                                    @elseif ($r->id_tipo_atencion_prestacion == 2)
                                        {{$r->especialidad_emergencia}}
                                    @elseif ($r->id_tipo_atencion_prestacion == 3 )
                                        {{$r->ubicacion_cama->consultorio}}            
                                    @endif
                                </td>
                                <td>{{$r->tipo_doc_ident}} - {{$r->nro_doc_ident}}</td>
                                <td>
                                        {{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}}
                                </td>
                                <td>
                                    {{$r->nom_servicio}} 
                                </td>
                                <td>{{$r->codigo_cpms_alterno}} - {{$r->descripcion_cpms}}</td>
                                {{-- <td> 
                                    php 
                                        if ($r->id_tipo_atencion_prestacion == 1) {
                                            echo isset($r->referido) ? $r->referido : "&nbsp"; 
                                        } else {
                                            echo $r->unidad_policial == null ? "NO" : "SI"; 
                                        }
                                    >
                                </td> --}}
                                {{-- servicios por categoría Pendiente dinamico--}}
                                <td style="text-align:center">
                                    {{-- @if ($r->ruta_radiologia == "")
                                        <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                    @else --}}
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Examen</b>">
                                            <a href="/ver_radiologia_imprimir/{{$r->id_rad}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                        </div>
                                    {{-- @endif --}}
                                </td>
                                @if ($id_estado_temp !== "-2"/* && $id_estado_temp !== "-1"*/)
                                    <td>@if ( ($r->ruta_radiologia == "")/* || ($r->fecha_hora_cita_radiologia == "") && ($r->fecha_hora_cita_radiologia == null) */)
                                            {{-- <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:center">
                                                <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                                            </div> --}}
                                        @else
                                            @if ($r->cod_siteds_susalud == "")
                                                {{-- <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Generar</b>" style="float:center">
                                                    <a 
                                                        href="javascript:void(0)"
                                                        onclick="modalSiteds({{$r->id_rad}},{{$r->id_asegurado}})"
                                                        class="btn btn-success btn-xs"
                                                    >
                                                    <i class="fa fa-edit"></i></a>
                                                </div> --}}
                                            @else
                                                @if (strlen($r->cod_siteds_susalud) < 4 || substr($r->cod_siteds_susalud, -1) === "T")
                                                    <div>
                                                        <span class="label label-warning">{{$r->cod_siteds_susalud}}</span><br>
                                                        <button 
                                                            type="button" 
                                                            style="margin-top: 5px"
                                                            class="btn btn-xs btn-warning"
                                                            data-toggle="tooltip"
                                                            data-html="true"
                                                            title="<b>Regularizar</b>"
                                                            onclick="regularizarSiteds({{$r->id_rad}}, {{$id_establecimiento}})"
                                                        ><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                                    </div>
                                                @else
                                                    <span class="label label-success">{{$r->cod_siteds_susalud}}</span>
                                                @endif
                                            @endif    
                                        @endif
                                    </td>
                                @endif
                                @if ($id_estado_temp !== "-2"/* && $id_estado_temp !== "-1"*/) 
                                    @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 6 || Auth::user()->tipo_registro == 7)
                                    <td>
                                        @if($farmacia->id == 76)
                                            <div style="display: flex; flex-direction: row; gap: 1rem;">
                                                    <table><tr>
                                                    @foreach($r->servicio_examen as $servicio)
                                                        <td>
                                                        @if($servicio->estado_examen !== null )
                                                            @if($servicio->flag_validated == "1")
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")    
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button class="btn btn-default btn-xs verPdf" id="verPdf" data-id-rad="{{$r->id_rad}}" data-id-servicio-rad="{{$servicio->id_servicio_radiologia}}" data-nombre-servicio-rad="{{$servicio->servicio}}" data-id-pp = "{{$r->id_pp}}" >
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                @elseif ($id_estado_temp == "1")
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button class="btn btn-default btn-xs verPdf" disabled >
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")   
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                @elseif ($id_estado_temp == "1")
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                        <button disabled class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($id_estado_temp == "1" || $id_estado_temp == "3" || $id_estado_temp == "-1")    
                                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                    @if ($id_estado_temp == "-1")    
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},0)"><i class="fa fa-edit"></i></button>
                                                                    @elseif ($id_estado_temp == "3")
                                                                        @if ($r->ruta_radiologia != "")
                                                                            <button class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},0)">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                                    @endif
                                                                </div>
                                                            @elseif ($id_estado_temp == "2")
                                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar<br>Informe<br>{{$servicio->servicio}}</b>">
                                                                    <button disabled class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                                </div>
                                                            @endif
                                                        @endif
                                                        </td>
                                                    @endforeach
                                                    </tr></table>
                                                @if(($r->cod_siteds_susalud!='') /*&& ($r->fecha_hora_cita_radiologia !== "") && ($r->fecha_hora_cita_radiologia !== null)*/)
                                                    <table><tr>
                                                    @foreach($r->informe_adicional as $informe)
                                                        <td>
                                                        @if($informe->estado_examen !== null )
                                                            @if($informe->flag_validated == "1")
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")    
                                                                    <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Informe Adicional<br>{{$informe->servicio}}</b>">
                                                                        <div id="icon-doc-firmado_imagenes" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                                                            <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if ($id_estado_temp == "2" || $id_estado_temp == "3" || $id_estado_temp == "-3")   
                                                                    <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$informe->servicio}}</b>">
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-xs" onclick="modificarOValidarInformeAdicional({{$r->id_rad}},{{$informe->id_servicio_radiologia}},{{$r->id_pp}},{{$informe->id_informe_adicional}})" style="background-color : #005b80;" ><i class="fa fa-edit" style="color: white;"></i></button>
                                                                    </div>
                                                                @elseif ($id_estado_temp == "1")
                                                                    <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar<br>o Validar<br>Informe<br>{{$informe->servicio}}</b>">
                                                                        <button disabled class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        </td>
                                                    @endforeach
                                                    </tr></table>
                                                @else
                                                    {{--<button disabled class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>--}}
                                                @endif
                                            </div>
                                        @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender Examen</b>">
                                            <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="atenderOtrosEstablecimientosDiferentesAlCentral({{$r->id_rad}})"><i class="fa fa-edit"></i></button>
                                        </div>
                                        @endif
                                    </td>
                                    @endif
                                @endif
                                @if ($id_estado_temp !== "-2" && $id_estado_temp !== "-1")    
                                    <td>@if ($r->estado_presencia == '1') En Triaje @elseif ($r->estado_presencia == '2') Presente @endif</td>
                                    <td><?php
                                        if ($r->fecha_presencia !== null && $r->fecha_presencia !== '') {
                                            $originalFechaPresencia = $r->fecha_presencia;
                                            $fechaPresencia = date("d-m-Y", strtotime($originalFechaPresencia));
                                            echo $fechaPresencia;
                                        }
                                    ?></td>
                                    <td><?php
                                        if ($r->fecha_presencia !== null && $r->fecha_presencia !== '') {
                                            $originalHoraPresencia = $r->fecha_presencia;
                                            $horaPresencia = date("H:i:s", strtotime($originalHoraPresencia));
                                            echo $horaPresencia;
                                        }
                                    ?></td>
                                @endif
                                @if ( auth()->user()->id_establecimiento == 76 && auth()->user()->flagllamadacita == 1 )
                                    @if ( $id_estado_temp == "1" && $id_estado_temp !== "-2" && $id_estado_temp !== "-1" )<td>@if ($r->estado_presencia !== null && $r->estado_presencia !== '' && $r->cod_siteds_susalud !== null && $r->cod_siteds_susalud !== '')
                                        {{--anteriormente se validaba con el codigo siteds: && $r->cod_siteds_susalud !== null && $r->cod_siteds_susalud !== ''--}}
                                        <button class="btn btn-app  btn-success btn-xs" id="opciones_de_llamado" name="opciones_de_llamado" data-id-cita="{{$r->id_cita}}" data-asegurado="{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}}" data-id-rad="{{$r->id_rad}}" data-id-servicio-rad="{{$servicio->id_servicio_radiologia}}" data-id-pp="{{$r->id_pp}}"><i class="fa fa-tv"></i> LLAMAR @if ($r->cantidad_llamada > 0) <span class="badge bg-purple" style="background-color: red;"><b>{{$r->cantidad_llamada}}</b></span>@endif</button>
                                    @else
                                        <button class="btn btn-app  btn-success btn-xs" disabled><i class="fa fa-tv"></i> LLAMAR</button>
                                    @endif</td>@endif
                                @endif
                                @if ($id_estado_temp == "1" ) <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>"><a href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}')" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a></div></td> @endif

                                @if ($id_estado_temp == "1" || $id_estado_temp == "-2" || $id_estado_temp == "-1" ) <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>"><a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="modalFormInvalidarOrden({{$id_estado_temp}},{{$r->id_rad}},{{$r->id_cita}})"><i class="fa fa-remove"></i></a></div></td> @endif
                                
                                <td>@if( ($r->cod_siteds_susalud !== null) && ($r->cod_siteds_susalud !== '') )
                                        @foreach($r->servicio_examen as $servicio)
                                            @if($servicio->estado_examen !== null )
                                                @if($servicio->flag_validated == "1")
                                                    <button type="button" class="btn btn-info btn-xs" style="background-color: #0098d4" data-toggle="tooltip" data-placement="top" data-html="true" title="Modificar" onclick="registrarInformeAdicional({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif</td>
                            </tr>
                            @endforeach     
                            @endif                 
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>

<div id="modalForm" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div id="modalFormInvalidarOrden" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invalidar Orden</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="estado_invalidar" name="estado_invalidar">
                <input type="hidden" id="id_radiologia_invalidar" name="id_radiologia_invalidar">
                <input type="hidden" id="id_cita_invalidar" name="id_cita_invalidar">
                <label>Escribir el motivo para Invalidar la orden</label>
                <textarea class="form-control" rows="5" id="invalidaComentario" name="invalidaComentario"></textarea>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger cancelarInvalidaComentario" id="cancelarInvalidaComentario" >Cancelar</button>
            <button type="button" id="guardarInvalidaComentario" class="btn btn-success">Invalidar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOpcionesLlamado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" style="width: 30% !important; position: relative !important; top: 40% !important; transform: translateY(-50%) !important;" role="document">
        <div class="modal-content"> 
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close close_llamado" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>OPCIONES DE LLAMADO</b>
            </div> 
            <div class="modal-body">
                <form id="FormularioOpcionesLlamado" name="FormularioOpcionesLlamado" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <input type="hidden" id="id_cita" name="id_cita">
                    <input type="hidden" id="id_rad" name="id_rad">
                    <input type="hidden" id="id_servicio_rad" name="id_servicio_rad">
                    <input type="hidden" id="id_pp" name="id_pp">

                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;"> 
                                <fieldset>
                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-success btn-md" id="llamar_paciente" name="llamar_paciente" 
                                        style="margin: 0px 20px 0px 20px"> <i class="fa fa-tv"></i> LLAMAR PACIENTE
                                      </a>
                                      <a href="javascript:void(0)" class="btn btn-warning btn-md"  id="atender_paciente" name="atender_paciente" 
                                        style="margin: 0px 20px 0px 20px"> <i class="fa fa-tv"></i> ATENDER PACIENTE
                                      </a>
                                    </div>

                                    <div class="form-group" style="font-size: 14px; text-align: center;"> 
                                      <br>
                                      <p>
                                        <b>PACIENTE</b><br><paciente><b id="paciente" name="paciente">---</b></paciente> <br>
                                        <ticket style="color: red;">
                                          <b>CANTIDAD DE LLAMADAS</b>
                                          <b id="cantidad_llamadas" name="cantidad_llamadas">---</b> <br> 
                                        </ticket>
                                      </p>
                                      <br>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<div class="modal fade" id="myModalDetPdfRadiologia" role="dialog">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">VER PDFS DE <span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="detalle-pdf"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
<div id="openModalSiteds" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-hc">
            <div id="" class="modal-content" style="padding: 0px;margin: 0px;">
                <div class="modal-body modal-siteds">
                </div>
            </div>
        </div>
    </div>
    <div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection
