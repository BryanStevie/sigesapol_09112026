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

	/* .btn-app:hover {
		color: #FFF;
	}*/
    .btn-success {
        background-color: #c4e3c5 !important;
        color: #2b620a !important;
        border-color: #c4e3c5 !important;
    }

    /* .btn-success:hover {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c; 
    }  */
    .btn-buscar.btn-success {
        color: #fff !important;
        background-color: #3c763d !important;
        border-color: #3c763d !important;
    }

    .btn-buscar.btn-success:hover {
        color: #fff !important;
        background-color: #5cb85c !important;
        border-color: #4cae4c !important;
    }
    .btn-buscar {
    margin-bottom: 5px !important;
    }

    /* .anular_izq{
        position: absolute;
        bottom: 1px;
        left: 1px;
    }

    .anular_der{
        position: absolute;
        bottom: 2px;
        right: 2px;
    }  */

    .adenda{
        position: absolute;
        bottom: 2px;
        right: 2px;
    }
    
    .observar{
        position: absolute;
        bottom: 2px;
        right: 2px;
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
        font-size: 1.2rem;
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

    .label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
        font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
    }   
</style>

<script>
    
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
        
        $('#example').DataTable();
    });

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

    function soloNumeros(e) {
        var key = window.Event ? e.which : e.keyCode;

        if (key === 32 || key === 44 || key === 46) return false;
        
        return ((key >= 48 && key <= 57) || key == 8 || key == 0);
    }

    function limpiarPegado(e) {
        e.preventDefault();

        var texto = (e.clipboardData || window.clipboardData).getData('text');

        texto = texto.replace(/[^0-9]/g, '');

        document.getElementById('dni_beneficiario').value = texto;
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
        let tipo_atencion = "";
        let id_servicio = "";
        let estado_muestra = "";
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
        tipo_atencion = $("#tipo_atencion").val();
        id_servicio = $("#id_servicio").val();
        estado_muestra = $("#estado_muestra").val();
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
            tipo_atencion,
            id_servicio,
            estado_muestra,
            estado_informe
        ];

        url = "{{ route('listar_examenes_laboratorios',['dataArray' => ':dataArray']) }}";

        dataString = dataArray.join(',');

        url = url.replace(':dataArray',dataString)

        $.ajax({
                type: "GET",
                url: url,
                 beforeSend: function() {
                    // Aquí tu función antes de enviar
                    $("#example").html("<center><h3>Cargando...</h1></center>");
                },
                success: function(response) {
                    $("#example").html(response);
                    btnBuscar.disabled = false;
                },
                error: function(e) {
                    // Maneja los errores
                    console.log(e.message);
                    btnBuscar.disabled = false;
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

        //PRIORIDADES
        if (($("#tipo_atencion").val() == '2')) {
            $.ajax({
                url: "obtener_prioridades_emergencia",
                success: function (response) {
                    let newOption = "";
                    newOption = "<option value='0'>TODOS</option>";
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
        $.ajax({
            url: "/lista_servicios_imagenes",
            success: function (response) {
                let newOption = "";
                newOption = "<option value='0'>Todos</option>";
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
            $('#id_servicio').html("<option value='0'>Todos</option>");
        }
        if (id_tipo_atencione !== '2' && id_tipo_atencione !== '0') {
            limpiarVarTemp()
            $('#id_prioridad').html("");
            $.ajax({
            url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
            success: function(result) {
                var newOption = "";
                newOption = "<option value='0'>Todos</option>";
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
                    newOption = "<option value='0'>Todos</option>";
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
            $('#id_servicio').html("<option value='0'>Todos</option>");
            $('#id_servicio').val("0").trigger('change');
        } else {
            $('#id_prioridad').html("<option value='0'>Todos</option>");
            $('#id_prioridad').val("0").trigger('change');
            $("#divPrioridad").hide();
        }
    });

    //Lista de Servicios Imagenes
    $("#tipo_atencion").change(function (e) { 
        e.preventDefault();
        $.ajax({
            url: "/lista_servicios_imagenes",
            success: function (response) {
                var newOption = "";
                newOption = "<option value='0'>Todos</option>";
                $(response).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                $('#id_servicio_imagenes').html(newOption);
                $("#id_servicio_imagenes").select2({
                max_selected_options: 4
                });
            }
        });
    });


    //Ver pdfs
    $(document).on('click','.verPdf' , function () {

        let idRadiologia = $(this).data('id-rad');
        let idServicioRadiologia = $(this).data('id-servicio-rad');
        let nombreServicioRadiologia = $(this).data('nombre-servicio-rad');
        let id_pp = $(this).data('id-pp');
        console.log("idRadiologia",idRadiologia);
        console.log("idServicioRadiologia",idServicioRadiologia);
        console.log("nombreServicioRadiologia",nombreServicioRadiologia);
        console.log("id_pp",id_pp);

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
                                    <!--a href="/ver_informe_para_firmar_imagenologia_lns/${idRadiologia}/${idServicioRadiologia}/${id_pp}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a-->
                                </div>`;
                    listPdfs += `</div>`;
                    $(oo.informes_adicionales).each(function(iii, ooo) {
                        listPdfs += `<div class="col-sm-12">`;
                        listPdfs += `<div class="col-sm-10" style="padding-top:5px;">
                                        <label>${oo.codigo} - ${oo.nombre} - Adenda</label>
                                    </div>`;
                        listPdfs += `<div class="col-sm-2" style="padding-top:5px;">
                                        <a href="${ooo.ruta_informe_radiologia_adicional ? `/informe_imagenes/${idRadiologia}/${idServicioRadiologia}/${oo.id}/${ooo.id_informe_adicional}/${ooo.flag}/${ooo.ruta_informe_radiologia_adicional}` : '#'}" target="_blank" class="btn btn-xs" style="background-color : #005b80; ${!ooo.ruta_informe_radiologia_adicional ? 'pointer-events: none; opacity: 0.5; cursor: default;' : ''}"><i style="color : white;" class="fa fa-download"></i></a>
                                        <!--a href="/ver_informe_para_firmar_imagenologia_lns_adicional/${idRadiologia}/${idServicioRadiologia}/${id_pp}/${ooo.id_informe_adicional}" class="btn btn-success btn-xs" style="background-color : #005b80 !important;" ><i style="color : white;" class="fa fa-eye"></i></a-->
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

    $(document).on('click', '.veratencionPdf', function () {

        const id_rad = $(this).data('id_rad');
        const id_cita = $(this).data('id_cita');
        const id_servicio = $(this).data('id_servicio');
        const id_pp = $(this).data('id_pp');
        console.log("id_rad",id_rad);
        console.log("id_cita",id_cita);
        console.log("id_servicio",id_servicio);
        console.log("id_pp",id_pp);

        $.ajax({
            url: "/radiologia_atencion_vista/"+id_rad+"/"+id_servicio+"/"+id_pp+"/"+id_cita,
            type: "GET",
            success: function (result) {  
                $('#iframeAtencion').html(result);
                $('#modalVerAtencion').modal('show');
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
    const $divcita = $('#divcita');
    const $divcita1 = $('#divcita1');
    const $divmuestra = $('#divmuestra');
    const $divmuestra1 = $('#divmuestra1');
    const $divorden = $('#divorden');
    const $divorden1 = $('#divorden1');
    const $tipo_atencion = $('#tipo_atencion');
    const $tipo_registro = $('#tipo_registro');
    const $divestadoinf = $('#divestadoinf');
    const $divestadomuestra = $('#divestadomuestra');
    const $estado_muestra_select = $('#estado_muestra_select'); 
    const $estado_informe_select = $('#estado_informe');
    const flagFiscalizador = parseInt($('#flagfiscalizador').val());

    const hoy = new Date();
    const fechaHoy = ("0" + hoy.getDate()).slice(-2) + '-' + ("0" + (hoy.getMonth() + 1)).slice(-2) + '-' + hoy.getFullYear();

    function actualizarUI() {
        const tipoReg = parseInt($tipo_registro.val());
        const tipoAtencion = parseInt($tipo_atencion.val());

        if((tipoReg === 1 || tipoReg === 7) && flagFiscalizador === 1) {
            $divmuestra.show();
            $divmuestra1.show();
            $divestadoinf.show();
            $divestadomuestra.hide();
            $divorden.hide().find('input, select').val('');
            $divorden1.hide().find('input, select').val('');
            $divcita.hide().find('input, select').val('');
            $divcita1.hide().find('input, select').val('');
            // $divorden.hide();
            // $divorden1.hide();
            // $divcita.hide();
            // $divcita1.hide();
            $estado_muestra_select.val("0").trigger('change');
            $estado_informe_select.val("0");

                if (!$('#fechamDesde').val()) $('#fechamDesde').val(fechaHoy);
                if (!$('#fechamHasta').val()) $('#fechamHasta').val(fechaHoy);
        }else if (tipoReg === 1 || tipoReg === 7) {
            $divmuestra.show();
            $divmuestra1.show();
            $divestadoinf.show();
            $divestadomuestra.hide();
            $estado_muestra_select.val("0").trigger('change');

            if (tipoAtencion === 0 ||tipoAtencion === 1 || tipoAtencion === 2 || tipoAtencion === 3) {
                $divorden.hide().find('input, select').val('');
                $divorden1.hide().find('input, select').val('');
                $divcita.hide().find('input, select').val('');
                $divcita1.hide().find('input, select').val('');

                // Solo asignar si están vacías
                if (!$('#fechamDesde').val()) $('#fechamDesde').val(fechaHoy);
                if (!$('#fechamHasta').val()) $('#fechamHasta').val(fechaHoy);
            }

        } else if (tipoReg === 6) {
            $divestadomuestra.show();
            $divestadoinf.hide();
            $estado_informe_select.val("0").trigger('change');
            $divmuestra.hide().find('input, select').val('');
            $divmuestra1.hide().find('input, select').val('');

            if (tipoAtencion === 1){
                $divcita.show();
                $divcita1.show();
                $divorden.hide().find('input, select').val('');
                $divorden1.hide().find('input, select').val('');

                if (!$('#fechaDesde').val()) $('#fechaDesde').val(fechaHoy);
                if (!$('#fechaHasta').val()) $('#fechaHasta').val(fechaHoy);
            }else if (tipoAtencion === 0 || tipoAtencion === 2 || tipoAtencion === 3) {
                $divorden.show();
                $divorden1.show();
                $divcita.hide().find('input, select').val('');
                $divcita1.hide().find('input, select').val('');

                if (!$('#fechaoDesde').val()) $('#fechaoDesde').val(fechaHoy);
                if (!$('#fechaoHasta').val()) $('#fechaoHasta').val(fechaHoy);
            }
        }else{
            $divmuestra.hide().find('input, select').val('');
            $divmuestra1.hide().find('input, select').val('');
            $divestadoinf.hide();
            $divestadomuestra.hide();
            $divorden.hide().find('input, select').val('');
            $divorden1.hide().find('input, select').val('');
            $divcita.hide().find('input, select').val('');
            $divcita1.hide().find('input, select').val('');
            $estado_muestra_select.val("0");
            $estado_informe_select.val("0");
        }
    }

    $tipo_atencion.on('change', actualizarUI);
    $tipo_registro.on('change', actualizarUI);

    actualizarUI(); 
});

    
    // document.getElementById('estado').addEventListener('change', function () {
    //     const estadoInforme = document.getElementById('estado_informe');
    //     estadoInforme.value = "0";
    // });

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
//=================================GUARDAR FILTROS ==================
    $('#formExamenRadiologia').on('submit', function () {
        const filtros = {
            tipo_atencion: $('#tipo_atencion').val(),
            id_servicio_imagenes: $('#id_servicio_imagenes').val(),
            estado_muestra_select: $('#estado_muestra_select').val(),
            estado_informe: $('#estado_informe').val(),
            dni_beneficiario: $('#dni_beneficiario').val(),
            fechaoDesde: $('#fechaoDesde').val(),
            fechaoHasta: $('#fechaoHasta').val(),
            fechaDesde: $('#fechaDesde').val(),
            fechaHasta: $('#fechaHasta').val(),
            fechamDesde: $('#fechamDesde').val(),
            fechamHasta: $('#fechamHasta').val()
        };

        localStorage.setItem('filtros_radiologia_form', JSON.stringify(filtros));

        Swal.fire({
            title: 'Cargando lista de exámenes...',
            html: '<img src="{{ asset('images/loader.gif') }}" width="60" height="60">',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: true,
            didOpen: () => {
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        });
    });

//================================RECUPERAR Y AUTOENVIAR===================
    // $(document).ready(function () {
    //     const filtrosGuardados = localStorage.getItem('filtros_radiologia_form');

    //     if (filtrosGuardados) {
    //         const filtros = JSON.parse(filtrosGuardados);

    //         $('#tipo_atencion').val(filtros.tipo_atencion);
    //         $('#id_servicio_imagenes').val(filtros.id_servicio_imagenes);
    //         $('#estado_muestra_select').val(filtros.estado_muestra_select);
    //         $('#estado_informe').val(filtros.estado_informe);
    //         $('#dni_beneficiario').val(filtros.dni_beneficiario);
    //         $('#fechaoDesde').val(filtros.fechaoDesde);
    //         $('#fechaoHasta').val(filtros.fechaoHasta);
    //         $('#fechaDesde').val(filtros.fechaDesde);
    //         $('#fechaHasta').val(filtros.fechaHasta);
    //         $('#fechamDesde').val(filtros.fechamDesde);
    //         $('#fechamHasta').val(filtros.fechamHasta);

    //         $('#tipo_atencion').trigger('change');
    //         $('#id_servicio_imagenes').trigger('change');
    //         $('#estado_muestra_select').trigger('change');
    //         $('#estado_informe').trigger('change');
    //     }

    //     const yaEnviado = sessionStorage.getItem('radiologia_form_autoenviado');

    //     if (!yaEnviado) {
    //         sessionStorage.setItem('radiologia_form_autoenviado', '1'); 

    //         setTimeout(function () {
    //             console.log("Formulario enviado automáticamente");
    //             $('#formExamenRadiologia').submit();
    //         }, 500);
    //     }
    // });
$(document).ready(function () {
    // Recuperar filtros guardados
    const filtrosGuardados = localStorage.getItem('filtros_radiologia_form');

    if (filtrosGuardados) {
        const filtros = JSON.parse(filtrosGuardados);
        $('#tipo_atencion').val(filtros.tipo_atencion);
        $('#id_servicio_imagenes').val(filtros.id_servicio_imagenes);
        $('#estado_muestra_select').val(filtros.estado_muestra_select);
        $('#estado_informe').val(filtros.estado_informe);
        $('#dni_beneficiario').val(filtros.dni_beneficiario);
        $('#fechaoDesde').val(filtros.fechaoDesde);
        $('#fechaoHasta').val(filtros.fechaoHasta);
        $('#fechaDesde').val(filtros.fechaDesde);
        $('#fechaHasta').val(filtros.fechaHasta);
        $('#fechamDesde').val(filtros.fechamDesde);
        $('#fechamHasta').val(filtros.fechamHasta);
    }

    @if (session('activar_autoenvio'))
        setTimeout(function () {
            Swal.fire({
                title: 'Cargando lista de exámenes...',
                html: '<img src="{{ asset('images/loader.gif') }}" width="60" height="60">',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: true,
                didOpen: () => {
                    setTimeout(() => {
                        Swal.close(); // por si quieres cerrarlo manual
                    }, 2000);
                }
            });

            console.log("⏎ Enviando formulario automáticamente desde redirect");
            $('#formExamenRadiologia').submit();
        }, 600);
    @endif


    $('#formExamenRadiologia').on('submit', function () {
        const filtros = {
            tipo_atencion: $('#tipo_atencion').val(),
            id_servicio_imagenes: $('#id_servicio_imagenes').val(),
            estado_muestra_select: $('#estado_muestra_select').val(),
            estado_informe: $('#estado_informe').val(),
            dni_beneficiario: $('#dni_beneficiario').val(),
            fechaoDesde: $('#fechaoDesde').val(),
            fechaoHasta: $('#fechaoHasta').val(),
            fechaDesde: $('#fechaDesde').val(),
            fechaHasta: $('#fechaHasta').val(),
            fechamDesde: $('#fechamDesde').val(),
            fechamHasta: $('#fechamHasta').val()
        };

        localStorage.setItem('filtros_radiologia_form', JSON.stringify(filtros));

   
        Swal.fire({
            //icon: 'success',
            title: 'Cargando lista de exámenes...',
            html: '<img src="{{ asset('images/loader.gif') }}" width="60" height="60">',
            //html: '<img src="{{ asset('images/loader.gif') }}" style="width: 60px; height: 60px; margin-top: 10px;">',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: true,
            didOpen: () => {
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        });
    });
});

    //===============================================LIMPIAR==================================
    $('#btnLimpiar').on('click', function (e) {
        e.preventDefault();

        const hoy = new Date();
        const fechaHoy = ("0" + hoy.getDate()).slice(-2) + '-' + ("0" + (hoy.getMonth() + 1)).slice(-2) + '-' + hoy.getFullYear();

        localStorage.removeItem("filtros_radiologia_form");

        $("#formExamenRadiologia")[0].reset();

        $('#tipo_atencion').val('0');
        $('#id_servicio_imagenes').val('0');
        $('#estado_muestra_select').val('0');
        $('#estado_informe').val('0');
        $('#dni_beneficiario').val('');
        $('#fechaoDesde').val('');
        $('#fechaoHasta').val('');
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
        $('#fechamDesde').val('');
        $('#fechamHasta').val('');

        $('#tipo_atencion').trigger('change');
        $('#id_servicio_imagenes').trigger('change');
        $('#estado_muestra_select').trigger('change');
        $('#estado_informe').trigger('change');
    });
//=======================================================BTN BUSCAR SIN FECHAS(SOLO DNI)======================================
    $('#btnBuscarSF').on('click', function () {
    const form = $('#formExamenRadiologia');
    const dni = $('#dni_beneficiario').val().trim(); 

    if (dni === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe ingresar el DNI para realizar esta búsqueda',
        });
        return; 
    }

    form.find('input[name="ignorar_fechas"]').remove();

    $('<input>').attr({
        type: 'hidden',
        name: 'ignorar_fechas',
        value: '1'
    }).appendTo(form);

    if (flagfiscalizador === '1') {
        $('#estado_muestra_select').val('0');
        $('#estado_informe').val('0');
    }

    form.submit();
});
//===========================================================================================================================
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
                <input type="hidden" id="flagfiscalizador" name="flagfiscalizador" value="{{ auth()->user()->flagfiscalizador }}">
                {{-- variables de retorno --}}
                <input type="hidden" id="id_prioridad_temp" name="id_prioridad_temp" value="{{ $id_prioridad_temp }}">
                <input type="hidden" id="id_servicio_temp" name="id_servicio_temp" value="{{ $id_servicio_temp }}">
                <input type="hidden" id="id_servicio_imagenes_temp" name="id_servicio_imagenes_temp" value="{{ $id_servicio_imagenes_temp }}">
                <input type="hidden" id="id_referido_temp" name="id_referido_temp" value="{{ $id_referido_temp }}">
                <div class="row">
                    <div class="form-group" style="margin: 0; padding: 0.5rem 0 0.5rem 0;">
                        
                        <div id="divorden" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Orden Desde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></i></span>
                                <input class="form-control input-sm" type="text" name="fechaoDesde" id="fechaoDesde" placeholder="Fecha Orden Desde" value="<?php echo $fechaoDesde ?>" maxlength="10">  
                            </div>   
                        </div>
                        <div id="divorden1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Orden Hasta</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaoHasta" id="fechaoHasta" placeholder="Fecha Orden Hasta" value="<?php echo $fechaoHasta ?>" maxlength="10">
                            </div>   
                        </div>

                        <div id="divcita" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Desde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Cita Desde" value="<?php echo $fechaDesde ?>" maxlength="10">  
                            </div>    
                        </div>
                        <div id="divcita1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Hasta</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Cita Hasta" value="<?php echo $fechaHasta ?>" maxlength="10"> 
                            </div>    
                        </div>
                        <div id="divmuestra" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Muestra Desde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechamDesde" id="fechamDesde" placeholder="Fecha Muestra Desde" value="<?php echo $fechamDesde ?>" maxlength="10">  
                            </div>  
                        </div>
                        <div id="divmuestra1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Muestra Hasta</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechamHasta" id="fechamHasta" placeholder="Fecha Muestra Hasta" value="<?php echo $fechamHasta ?>" maxlength="10">
                            </div>  
                        </div>
                        <!-- <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" for="textinput">Nro. Documento</label>  
                            <!-- <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="8" value="<-?php echo $dni_beneficiario ?>"> 
                            <input onkeypress="return soloNumeros(event)" onpaste="limpiarPegado(event)" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="20" value="<?php echo $dni_beneficiario ?>">
                        </div> -->
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" for="form1">Nro. Documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" placeholder="Buscar..."id="dni_beneficiario" name="dni_beneficiario"
                                    maxlength="20"
                                    onkeypress="return soloNumeros(event)"
                                    onpaste="limpiarPegado(event)"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value="{{ $dni_beneficiario }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-success btn-sm" id="btnBuscarSF" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Tipo Atención</label>
                                <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion">
                                    <option value='0'>Todos</option>
                                    <?php foreach ($tipo_atenciones as $row) : ?>
                                        @if($nivel==1)
                                            @if($row->id == 1)
                                            <option value="<?php echo $row->id  ?>" @if ( isset($id_tipo_atencion)) @if ($row->id == $id_tipo_atencion) selected @endif @endif><?php echo $row->nombre  ?></option>
                                            @endif
                                        @else
                                            <option value="<?php echo $row->id  ?>" @if ( isset($id_tipo_atencion)) @if ($row->id == $id_tipo_atencion) selected @endif @endif><?php echo $row->nombre  ?></option>
                                        @endif
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Servicios de Imagenes</label>
                            <select class="form-control input-sm" name="id_servicio_imagenes" id="id_servicio_imagenes">
                                <option value='0'>--Seleccionar--</option>
                                @foreach ( $listServiciosImagenes as $row )
                                    <option value="{{$row->id_servicio}}" @if ( $row->id_servicio == $id_servicio_imagenes_temp ) selected @endif >{{$row->nombre}}</option>
                                @endforeach
                            </select>
                        </div> 

                        <div id="divestadomuestra" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                            <label class="control-label">Estado Toma de Muestra</label>
                            <select class="form-control input-sm" name="estado_muestra" id="estado_muestra_select">
                                <option value="0" {{ $id_estado_muestra_temp == "0" ? 'selected' : '' }}>Todos</option> 
                                <option value="1" {{ $id_estado_muestra_temp == "1" ? 'selected' : '' }}>Pendiente</option>
                                <option value="2" {{ $id_estado_muestra_temp == "2" ? 'selected' : '' }}>Atendido</option> 
                            </select>
                        </div>


                        <div id="divestadoinf" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                            <label class="control-label">Estado Informe</label>
                            <select class="form-control input-sm" name="estado_informe" id="estado_informe">
                                <option value="0" {{ $id_estado_informe_temp == "0" ? 'selected' : '' }}>Todos</option> 
                                <option value="1" {{ $id_estado_informe_temp == "1" ? 'selected' : '' }}>Pendiente</option>
                                <option value="2" {{ $id_estado_informe_temp == "2" ? 'selected' : '' }}>Atendido</option> 
                            </select>
                        </div>
                        
                    </div>
                </div>              
                <div class="row" style="margin: 0; padding: 0; display: flex; justify-content: flex-end;">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: flex; justify-content: flex-end;">
                        <div class="col-md-4" style="padding: 0; margin: 5px;">
                            <button type="submit" class="form-control btn btn-success input-sm btn-buscar">
                                        <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                        <div class="col-md-4" style="padding: 0; margin: 5px;">
                            <a class="form-control btn btn-danger input-sm" id="btnLimpiar" style="font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;">
                                <i class="fa fa-trash-o fa-lg"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <div style="font-size: 15px; margin: 5px; text-align: left;font-weight: bold;">
                    Total: {{$cantidad}} examen(es) encontrado(s)
                </div>
            </div>           
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered" style="font-size: 12px;">
                        <thead>
                            <tr class="table-header">
                                <!--Nro examen-->
                                <th>N</th>
                                <!--datos del paciente-->
                                <th>Paciente</th>
                                <!--Nro de orden y tipo de atención-->
                                <th>Orden de Radiología</th>
                                @if($id_tipo_atencion == 0 ||$id_tipo_atencion == 3 || $id_tipo_atencion == 2)
                                    <th>Programación de examen</th>
                                @else
                                    <th>Cita</th>
                                @endif
                                <!--fecha, estado y servicio en que se ralizó la toma de muestra-->
                                <th>Toma Muestra</th>
                                @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 7) 
                                    <th>Informe Radiologico</th>
                                @endif
                                <!--Acciones como atención, adendas,...-->
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
                            
                            $diferencia = $hoy->diff($fechaNac);
                            
                            return array(
                                'anios' => $diferencia->y,
                                'meses' => $diferencia->m,
                                'dias' => $diferencia->d
                            );
                        }
                        ?>
                            @foreach ( $radiologias as $r )
                            <tr>
                                {{-- Número de examen --}}
                                <td style="width: 10px; text-align:center;font-size: 12px;"> <?php $n++; echo $n; ?> </td>
                                {{-- Paciente --}}
                                <td style="width: 250px;font-size: 12px;">
                                    <div> <?php echo $r->nombre_beneficiario . ", ".  $r->paterno_beneficiario ." ".  $r->materno_beneficiario ?></div>
                                    <div> <?php echo $r->tipo_doc_ident . " - ".  $r->nro_doc_ident ." - ". '(' . ($r->sexo_beneficiario == "F" ? "FEMENINO" : "MASCULINO") . ')'  ?></div>
                                    <div><strong> F.NAC: </strong>{{ \Carbon\Carbon::parse($r->fecha_nac_beneficiario)->format('d/m/Y') }} -
                                    <?php
                                    $edad = calcularEdad($r->fecha_nac_beneficiario);
                                    echo "<strong>Edad: </strong>". $edad['anios'] . " A, " . $edad['meses'] . " M y " . $edad['dias'] . " D";  
                                    
                                    ?>
                                    </div>
                                    <div><strong>CONDICIÓN: </strong>{{$r->referido == "NO" ? "NO REFERIDO":"REFERIDO"}}</div>
                                </td> 
                                {{-- Orden de radiología --}}
                                <td style="position: relative; width: 230px;font-size: 12px;">
                                    <div><strong>N°:</strong> {{$r->id_rad}} {{\Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y')}}</div>
                                    <div>
                                        @if ($r->id_tipo_atencion_prestacion == 1)
                                            <b>ORIGEN: </b>{{$r->nombre_tipo_atencion}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            @if ($r->id_evaluacion > 0)
                                            <b>ORIGEN: </b> EMERGENCIA - EVALUACIÓN
                                            @else
                                            <b>ORIGEN: </b> {{$r->nombre_tipo_atencion}}
                                            @endif
                                        @elseif ($r->id_tipo_atencion_prestacion == 3)
                                            @if ($r->id_evaluacion > 0)
                                                <b>ORIGEN: </b>HOSPITALIZACIÓN - EVOLUCIÓN
                                            @else
                                                <b>ORIGEN: </b>{{$r->nombre_tipo_atencion}}
                                            @endif
                                        @endif
                                    </div>
                                    <div>
                                        @if ($r->id_tipo_atencion_prestacion == 1)
                                            <b>SERVICIO: </b>{{$r->consultorio}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            <b>SERVICIO: </b>{{$r->especialidad_emergencia}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 3 )
                                            @if ($r->ubicacion_cama)
                                                {{ $r->ubicacion_cama->consultorio }}
                                                <b>SERVICIO: </b>{{ $r->ubicacion_cama->consultorio }}
                                            @else
                                                <b>SERVICIO: </b> No asignado
                                            @endif         
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
                                                <i class="fa fa-search font-iconos-tables" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Orden (RUA)</b>" aria-hidden="true" ></i>
                                        </a>                                
                                    </div>
                                    <!-- @if (/*$id_estado_muestra_temp == "0" ||*/ $id_estado_muestra_temp == "1") 
                                        <div class="{{ $id_estado_muestra_temp == '1' ? 'anular_izq' : 'anular_der' }}" style="font-size: 10px;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>">
                                            <a style="font-size: 10px;" href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="modalFormInvalidarOrden({{$id_estado_muestra_temp}},{{$r->id_rad}},{{$r->id_cita}})">
                                                <i class="fa fa-remove">
                                                </i>
                                            </a>
                                        </div>
                                    @endif    -->
                                </td>
                                {{-- Cita --}}
                                <td style="position: relative; width: 190px;">
                                    @if($r->id_tipo_atencion_prestacion == 1)
                                    <div><strong>F.CITA:</strong>  {{ \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A') }}</div>
                                    <div><strong>CONSULTORIO: </strong>{{$r->consultorio}}</div>
                                    @elseif($r->id_tipo_atencion_prestacion == 2)
                                        @if(!empty($r->id_evaluacion))
                                            <div><strong>F.PROGRAMADA:</strong> {{ \Carbon\Carbon::parse($r->fecha_evolucion)->format('d/m/Y') }}</div>
                                        @else
                                            <div><strong>F.PROGRAMADA:</strong> {{ \Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y') }}</div>
                                        @endif
                                    @elseif($r->id_tipo_atencion_prestacion == 3)
                                        @if(!empty($r->id_evaluacion))
                                            <div><strong>F.PROGRAMADA:</strong> {{ \Carbon\Carbon::parse($r->fecha_evolucion)->format('d/m/Y') }}</div>
                                        @else
                                            <div><strong>F.PROGRAMADA:</strong> {{ \Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y') }}</div>
                                        @endif
                                    @endif
                                    {{--dd($r)--}}
                                    <div><strong>EXAMEN: </strong>{{$r->codigo_cpms_alterno}} - {{$r->descripcion_cpms}}</div>
                                       
                                    <!--@if (($id_estado_muestra_temp == "0" ||$id_estado_muestra_temp == "1")&& Auth::user()->tipo_registro == 6 ) -->
                                    {{--dd($r)--}}
                                        <!-- @if($r->flag_validated == 1 || ($r->confirmar_toma_created_at !== null && $r->confirmar_toma_created_at !== ""))
                                            @if($r->id_tipo_atencion_prestacion == 1 && !empty($r->comentario))
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px; background-color: #ccc; pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                                href="javascript:void(0)"
                                                class="btn btn-info btn-xs disabled">
                                                    <i class="fa fa-exclamation-circle" title="Informe pendiente por observar"></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 2 && $r->comentario != null)
                                                <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                    <a style="font-size: 10px; background-color: #ccc; pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                                    href="javascript:void(0)"
                                                    class="btn btn-info btn-xs disabled">
                                                        <i class="fa fa-exclamation-circle" title="Informe pendiente por observar"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        @else -->
                                            <!-- @if($r->id_tipo_atencion_prestacion == 1)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle"  title="Informe pendiente por observar"></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 2 && $r->id_cita != null)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle"  title="Informe pendiente por observar"></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 3 && $r->id_cita != null)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle"  title="Informe pendiente por observar"></i>
                                                </a>
                                            </div>
                                            @endif -->
                                        <!-- @endif 
                                    @endif -->
                                </td>
                                <!-- <td style="position: relative;width: 190px;">
                                    <div>  {{ \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A') }}</div>  
                                </td> -->
                                {{-- Toma de muestra --}}
                                <td style="position: relative;width: 190px;">
                                    <!-- <div>{{$r->confirmar_toma_created_at == null ? "Pendiente" :\Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') }}  </div>
                                    <div>{{$r->nom_servicio}}</div>    -->
                                    <div style="color: {{ $r->confirmar_toma_created_at == null ? '#d43f3a' : 'green' }};font-weight:bold;">
                                        {{ $r->confirmar_toma_created_at == null 
                                            ? 'PENDIENTE' 
                                            : \Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') 
                                        }}<br>
                                         {{ $r->nom_servicio }}
                                    </div>
                                    <!--?php dd($r)?-->
                                    <!-- <div style="color: 
                                        {{ $r->confirmar_toma_created_at == null && $r->id_estado_cita == '7' ? '#800080' : 
                                        ($r->confirmar_toma_created_at == null ? '#f8bc0f' : '#5cb85c') }};
                                        font-weight: bold;">

                                        @if (!empty($r->id_tipo_atencion_prestacion) /*&& $r->id_tipo_atencion_prestacion == 1*/ && $r->confirmar_toma_created_at == null && $r->id_estado_cita == '7')
                                            {{$r->fecha_observado== null ? '': $r->fecha_observado}}<br>
                                            {{ $r->nom_servicio }}<br>
                                            @if (!empty($r->comentario))
                                                <span style="color: #800080;">OBS: {{ $r->comentario }}</span>
                                            @endif
                                        @else
                                            {{ $r->confirmar_toma_created_at == null ? 'PENDIENTE' : \Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') }}<br>
                                            {{ $r->nom_servicio }}
                                        @endif
                                    </div> -->
                                    
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
                                @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 7) 
                                    {{-- Informe Radiológico --}}
                                    <td style="position: relative; width: 190px;">
                                        <div style="color: {{ $r->flag_validated == '0' ? 'red' : 'green' }}; font-weight: bold;">
                                            <b style="color:#000000">Estado: </b>{{ $r->flag_validated == '0' ? 'PENDIENTE' : 'INFORMADO' }}
                                        </div>
                                        <div>{{$r->validated_at == '' ? ' - / - / - ' :\Carbon\Carbon::parse($r->validated_at)->format('d/m/Y') }}  </div>
                                        @if( ($r->cod_siteds_susalud !== null) && ($r->cod_siteds_susalud !== '') )                                       
                                            @foreach($r->servicio_examen as $servicio)                                               
                                                @if($servicio->estado_examen !== null )
                                                    @if($servicio->flag_validated == "1")
                                                    <div class="adenda" > 
                                                        <button type="button" class="btn btn-info btn-xs" style="background-color: #83996a;font-size: 10px;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Agregar Adenda</b>" onclick="registrarInformeAdicional({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                    </div> 
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                    </td>
                                @endif
                                {{-- Acciones --}}
                                @if(Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 6 || Auth::user()->tipo_registro == 7)
                                    <td style="position: relative; width: 190px;">
                                        
                                            <div style="display: flex; flex-direction: row; gap: 1rem;">
                                                <table>
                                                    <tr>
                                                        @foreach($r->servicio_examen as $servicio)
                                                            <td>
                                                                @if($servicio->estado_examen !== null )
                                                                    @if($servicio->flag_validated == "1")
                                                                        @if (($id_estado_informe_temp == "0" || $id_estado_informe_temp == "2")&&(Auth::user()->tipo_registro == 7 || Auth::user()->tipo_registro == 1) ) 
                                                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                                <button class="btn btn-default btn-xs verPdf" id="verPdf" data-id-rad="{{$r->id_rad}}" data-id-servicio-rad="{{$servicio->id_servicio_radiologia}}" data-nombre-servicio-rad="{{$servicio->servicio}}" data-id-pp = "{{$r->id_pp}}" >
                                                                                    <i class="fa fa-search"></i>
                                                                                </button>
                                                                            </div>
                                                                        <!--Cuando es tecnólogo radiólogo se oculta la vista del examen realizado-->
                                                                            <!-- <div style="display: none;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                                <button class="btn btn-default btn-xs verPdf" id="verPdf">
                                                                                    <i class="fa fa-search"></i>
                                                                                </button>
                                                                            </div> -->
                                                                        @elseif (($id_estado_informe_temp == "0" || $id_estado_informe_temp == "2")&& Auth::user()->tipo_registro == 6)
                                                                            <!--?php dd($id_estado_informe_temp)?-->  
                                                                            <!--div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Informe<br>{{$servicio->servicio}}</b>">
                                                                                <button class="btn btn-default btn-xs verPdf" disabled >
                                                                                    <i class="fa fa-search"></i>
                                                                                </button>
                                                                            </div-->
                                                                            
                                                                            <!-- <a href="" target="_blank" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>">
                                                                                <button style="background-color: #c4e3c5;color: #2b620a;font-size: 10px;" class="btn btn-default btn-xs">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </button>
                                                                            </a> -->
                                                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>">
                                                                                <button 
                                                                                    style="background-color: #c4e3c5;color: #2b620a;font-size: 10px;" 
                                                                                    class="btn btn-default btn-xs veratencionPdf" 
                                                                                    data-id_rad="{{ $r->id_rad }}" 
                                                                                    data-id_cita="{{ $r->id_cita }}"
                                                                                    data-id_servicio="{{$servicio->id_servicio_radiologia }}"
                                                                                    data-id_pp="{{ $r->id_pp }}">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        @if (($id_estado_informe_temp == "0" || $id_estado_informe_temp == "1")&&(Auth::user()->tipo_registro =="7"||Auth::user()->tipo_registro =="1"))
                                                                            <!--?php dd($id_estado_informe_temp)?-->   
                                                                            <div style="font-size: 10px;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b><b>Validar<br>e Informar<br>{{$servicio->servicio}}</b>">
                                                                                <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})"><i class="fa fa-edit"></i></button>
                                                                            </div>
                                                                        @elseif(($id_estado_muestra_temp == "0" || $id_estado_muestra_temp == "2")&& Auth::user()->tipo_registro == 6)
                                                                        
                                                                            <!-- <div style="font-size: 10px;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validar<br>e Informar<br>{{$servicio->servicio}}</b>">
                                                                                <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-success btn-xs" onclick="modificarOValidarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}})" disabled><i class="fa fa-edit"></i></button>
                                                                            </div> -->
                                                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atención</b>">
                                                                                <button 
                                                                                    style="background-color: #c4e3c5;color: #2b620a;font-size: 10px;" 
                                                                                    class="btn btn-default btn-xs veratencionPdf" 
                                                                                    data-id_rad="{{ $r->id_rad }}" 
                                                                                    data-id_cita="{{ $r->id_cita }}"
                                                                                    data-id_servicio="{{$servicio->id_servicio_radiologia }}"
                                                                                    data-id_pp="{{ $r->id_pp }}">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <?php echo $servicio->estado_examen; ?>
                                                                    @if ($id_estado_muestra_temp == "0" || $id_estado_muestra_temp == "1")    
                                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>ATENDER<br>{{$servicio->servicio}}</b>">
                                                                            @if ($id_estado_muestra_temp == "1")    
                                                                                <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},0)"><i class="fa fa-edit"></i></button>
                                                                            @else
                                                                                <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-default btn-xs" onclick="registrarInforme({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                                            @endif
                                                                        </div>
                                                                    @elseif ($id_estado_informe_temp == "1")
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
                                                                @if (($id_estado_informe_temp == "0" || $id_estado_informe_temp == "2") && (Auth::user()->tipo_registro =="7"||Auth::user()->tipo_registro =="1"))
                                                                    <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Informe Adicional<br>{{$informe->servicio}}</b>">
                                                                        <div id="icon-doc-firmado_imagenes" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                                                            <!--?php dd($r)?-->
                                                                            <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i>
                                                                            
                                                                        </div>
                                                                        
                                                                    </div>
                                                                {{--@elseif(($id_estado_informe_temp == "0" || $id_estado_informe_temp == "2") && Auth::user()->tipo_registro =="6")--}}
                                                                    <!--div style="margin-left: 0.5rem; opacity: 0.5; pointer-events: none; cursor: not-allowed;" 
                                                                        data-toggle="tooltip" data-placement="top" data-html="true" 
                                                                        title="<b>Informe Adicional<br>{{$informe->servicio}}</b>">
                                                                        <div id="icon-doc-firmado_imagenes" 
                                                                            style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                                                            <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i>
                                                                        </div>
                                                                    </div-->
                                                                @endif
                                                            @else
                                                                @if (($id_estado_informe_temp == "0" || $id_estado_informe_temp == "2")&&(Auth::user()->tipo_registro == 7 || Auth::user()->tipo_registro == 1))   
                                                                    <!--?php dd($id_estado_muestra_temp)?-->
                                                                    <div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validar<br>e Informar (Adenda)<br>{{$informe->servicio}}</b>">
                                                                        <button @if($r->ruta_radiologia == "") disabled @endif class="btn btn-xs" onclick="modificarOValidarInformeAdicional({{$r->id_rad}},{{$informe->id_servicio_radiologia}},{{$r->id_pp}},{{$informe->id_informe_adicional}})" style="background-color : #83996a;;font-size: 10px;" ><i class="fa fa-edit" style="color: white;"></i></button>
                                                                    </div>
                                                                {{--@elseif (($id_estado_muestra_temp == "2")&& Auth::user()->tipo_registro == 6)--}}
                                                                    <!--div style="margin-left: 0.5rem;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Validar<br>e Informar (Adenda)<br>{{$informe->servicio}}</b>">
                                                                        <button disabled class="btn btn-success btn-xs" disabled><i class="fa fa-edit"></i></button>
                                                                    </div-->
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
                                        
                                    </td>
                                @endif
                            </tr>
                            @endforeach
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

<div class="modal fade" id="modalVerAtencion" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ATENCIÓN</h5>
      </div>
      <div class="modal-body">
        <div id="iframeAtencion" src="" width="100%" height="600px" style="border:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" Style="background-color: #c4e3c5;color: #2b620a;font-size: 13px;font-weight: bold;">CERRAR</button>
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
