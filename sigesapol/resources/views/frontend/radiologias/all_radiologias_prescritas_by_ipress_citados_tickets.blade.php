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
		color: #FFF !important; 
		background-color: #00a65a; 
	}

    .btn-success {
        background-color: #c4e3c5 !important;
        color: #2b620a !important;
        border-color: #c4e3c5 !important;
    }

    .btn-success-llamado .badge {
        color: white !important; 
        background-color: #fff;
    }

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

    .btn-reporte.btn-success {
        color: #fff !important;
        background-color: #5cb85c !important;
        border-color: #4cae4c !important;
    }
    .btn-reporte.btn-success:hover {

        background-color: #c4e3c5 !important;
        color: #2b620a !important;
        border-color: #c4e3c5 !important;
    }
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
    
    .ui-autocomplete {
        z-index: 2000 !important; 
    }
    .btn-secondary {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }
    .btn-secondary:hover {
        color: #fff;
        background-color: #c9302c;
        border-color: #ac2925;
    }

    .btn-exportar.btn-success {
        color: #fff !important;
        background-color: #3c763d !important;
        border-color: #3c763d !important;
    }

    .btn-exportar.btn-success:hover {
        color: #fff !important;
        background-color: #5cb85c !important;
        border-color: #4cae4c !important;
    }
    
    .ui-tooltip {
        background: rgba(0, 0, 0, 0.8);  
        color: #fff;                     
        border-radius: 4px;               
        padding: 4px 8px;                 
        font-size: 11px;                  
        text-align: center;               
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        max-width: 200px;                 
        white-space: normal;              
    }

    .ui-tooltip::after {
        content: "";
        position: absolute;
        border-width: 5px;
        border-style: solid;
    }

    .ui-tooltip.ui-tooltip-top::after {
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-color: rgba(0,0,0,0.8) transparent transparent transparent;
    }

    .ui-tooltip.ui-tooltip-bottom::after {
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-color: transparent transparent rgba(0,0,0,0.8) transparent;
    }
</style>

<script>

    //----------------------------------------------PARA FECHAS------------------------------------------------------------------------------
    $(document).ready(function () {

        // $('#example').DataTable({
        //     language: {
        //         emptyTable: "Recuerde que si tiene una orden física, no se mostrará en el listado"
        //     }
        // });
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
        
        //$('#example').DataTable();
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
        let estado_informe = "0";
        let estado_llamado = "";

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
        estado_llamado = $("#estado_llamado").val();

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
            estado_informe,
            estado_llamado
        ];

        url = "{{ route('listar_examenes_radiologicos_ticket',['dataArray' => ':dataArray']) }}";

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
        // console.log("idRadiologia",idRadiologia);
        // console.log("idServicioRadiologia",idServicioRadiologia);
        // console.log("nombreServicioRadiologia",nombreServicioRadiologia);
        // console.log("id_pp",id_pp);

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
        // console.log("id_rad",id_rad);
        // console.log("id_cita",id_cita);
        // console.log("id_servicio",id_servicio);
        // console.log("id_pp",id_pp);

        $.ajax({
            url: "/radiologia_atencion_vista/"+id_rad+"/"+id_servicio+"/"+id_pp+"/"+id_cita,
            type: "GET",
            success: function (result) {  
                $('#iframeAtencion').html(result);
                $('#modalVerAtencion').modal('show');
            }
        });
    });


    /*$('#atender_paciente').click(function () {
        const id_rad = $('#id_rad').val();  
        const id_servicio_rad = $('#id_servicio_rad').val();  
        const id_pp = $('#id_pp').val();
        const id_cita = $('#id_cita').val();
        const url = "{{ url('radiologia_informe') }}";

        window.location.href = `${url}/${id_rad}/${id_servicio_rad}/${id_pp}/${id_cita}`;
    });*/

    function modalForm(id_cita, id_pp) {
        $(".modal-dialog").css("width", "80%");
        $('#modalForm').modal('show');
        $('#modalForm .modal-body').css('height', 'auto');
        const url = "{{ url('modal_comentario_cita_imagenes') }}";
        $.ajax({
            url: `${url}/${id_cita}/${id_pp}`,
            type: "GET",
            success: function(result) {
                $("#modalForm .modal-body").html(result);
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
            backdrop: true
        });
    });

$(document).ready(function () {
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
            });

            //console.log("Enviando formulario automáticamente desde redirect");
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

//====================================================== REPORTE DE IMAGENOLOGÍA ============================================
$(document).ready(function () {
    $('#btnExportarReporte').click(function () {

        let fechaDesde = $('input[name="fecha_desde"]').val();
        let fechaHasta = $('input[name="fecha_hasta"]').val();
        if (!fechaDesde || !fechaHasta  === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }

        let formData = $('#frmReporteImagenologia').serialize();

            Swal.fire({
                title: 'Generando informe...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

        $.ajax({
            url: '/exportar_reporte_imagenes',
            type: 'POST',
            data: formData,
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

                    let divLinkReporte = document.getElementById('link-reporte');
                    divLinkReporte.appendChild(link);
                    link.click();
                    divLinkReporte.removeChild(link);

                    Swal.hideLoading();
                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove(); 
                            $('#modal_reportes_imagenologia').modal('hide'); 
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
                        $('.blur-background').remove(); 
                    }
                });
            }
        });
    });
});

$(document).ready(function () {
  console.log('jQuery', $.fn.jquery, 'jQuery UI', !!$.ui && !!$.ui.autocomplete);

    $('#modal_reportes_imagenologia').on('shown.bs.modal', function () {
        const $modal = $(this);
        const $inputNivel = $modal.find('#id_nivel');
        const id_user = {{ Auth::id() }};
        $.getJSON(`/obtener_nivel_usuario/${id_user}`)
            .done(function(data) {
                $inputNivel.val(data.nivel || 0);
            })
            .fail(function() {
                console.error('No se pudo obtener el nivel del usuario');
                $inputNivel.val(0);
            });
        //const $modal = $(this);
        const $tipo = $modal.find('#tipo_atencion');
        const $exam = $modal.find('#nombre_examen_rad0');
        const $id_examen = $modal.find('#id_examen_rad0');
        const $id_codigo = $modal.find('#id_codigo_rad0');
        const $fechaDesde = $modal.find('#fecha_desde');
        const $fechaHasta = $modal.find('#fecha_hasta');

        function formatDateToInput(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Iniciar fechas al abrir modal
        const hoy = new Date();
        $fechaHasta.val(formatDateToInput(hoy));

        const unMesAntes = new Date();
        unMesAntes.setMonth(unMesAntes.getMonth() - 1);
        $fechaDesde.val(formatDateToInput(unMesAntes));

        let prevFechaDesde = $fechaDesde.val();
        let prevFechaHasta = $fechaHasta.val();

        // Autocompletar teniendo en cuenta que debe ser 1 mes desde la fecha_desde
        $fechaDesde.on('change', function() {
            const desdeStr = $(this).val();
            if (!desdeStr) return;

            const desde = new Date(desdeStr + 'T00:00');

            const nuevaHasta = new Date(desde);
            nuevaHasta.setMonth(nuevaHasta.getMonth() + 1);

            $fechaHasta.val(formatDateToInput(nuevaHasta));

            prevFechaDesde = $(this).val();
            prevFechaHasta = $fechaHasta.val();
        });

        $fechaHasta.on('change', function() {
            const desde = new Date($fechaDesde.val() + 'T00:00');
            let hasta = new Date($(this).val() + 'T00:00');

            if (hasta < desde) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fecha inválida',
                    text: 'La fecha hasta no puede ser menor a la fecha desde.'
                });

                const nuevaHasta = new Date(desde);
                nuevaHasta.setDate(nuevaHasta.getDate() + 1);
                $(this).val(formatDateToInput(nuevaHasta));
                prevFechaHasta = $(this).val();
                return;
            }

            // validamos que solo se pueda exportar el reporte de 2 meses
            const maxHasta = new Date(desde);
            maxHasta.setMonth(maxHasta.getMonth() + 2);
            if (hasta > maxHasta) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rango máximo excedido',
                    text: 'La fecha hasta no puede ser mayor a 2 meses después de la fecha desde.'
                });
                $(this).val(formatDateToInput(maxHasta));
                hasta = maxHasta;
            }

            prevFechaHasta = $(this).val();
        });


        function resetExamField() {
            $exam.val('').prop('readonly', false);
            $id_examen.val('');
            $id_codigo.val('');
        }

        function setExamDisabled(disabled) {
            $exam.prop('disabled', disabled);
            if (disabled) {
                resetExamField();
                $exam.attr('placeholder', 'Seleccione primero un tipo de atención');
            } else {
                $exam.attr('placeholder', 'Ingrese el examen de Radiografía...');
            }
        }

            setExamDisabled(!$tipo.val());

            $tipo.off('change.autocompleteModal').on('change.autocompleteModal', function () {
                const disabled = !$(this).val(); 
                setExamDisabled(disabled);
                if (!disabled) {
                    resetExamField();
                    $exam.focus();
                }
            });

            if (!$exam.data('autocomplete-initialized')) {
            $exam.autocomplete({
                minLength: 1,
                delay: 250,
                source: function (request, response) {
                const nivel = $modal.find('#id_nivel').val() || 0;
                const id_tipo_atencion = $modal.find('#tipo_atencion').val();

                if (id_tipo_atencion == null || id_tipo_atencion === '') {
                    return response([]);
                }

                const termEnc = encodeURIComponent(request.term);
                const url = `../obtener_radiologia_convenio/${termEnc}/${nivel}/0/${id_tipo_atencion}/105`;

                $.getJSON(url)
                    .done(function (data) {
                    response(data);
                    })
                    .fail(function (jqXhr, textStatus, errorThrown) {
                    console.error('Autocomplete AJAX error:', textStatus, errorThrown);
                    response([]);
                    });
                },
                select: function (e, ui) {
                $id_examen.val(ui.item.id);
                $id_codigo.val(ui.item.codigo_procedimiento);
                $exam.val(ui.item.value).prop('readonly', true);
                return false;
                },
                focus: function (e, ui) {
                return false;
                },
                appendTo: $modal
            }).data('autocomplete-initialized', true);
            $exam.on('blur', function () {
                if ($exam.val().trim() !== '' && !$id_codigo.val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Examen inválido',
                        text: 'El examen seleccionado no existe. Por favor elija uno de la lista.'
                    }).then(() => {
                        resetExamField();
                        $exam.focus();
                    });
                }
            });
        }
    });

    // limpiar el modal al finalizar
    $('#modal_reportes_imagenologia').on('hidden.bs.modal', function () {
        const $m = $(this);
        $m.find('#frmReporteImagenologia')[0].reset();
        $m.find('#tipo_atencion').val('0'); 
        $m.find('#id_servicio_imagenes').val('0');
        $m.find('#nombre_examen_rad0').prop('disabled', false).prop('readonly', false).val('');
        $m.find('#id_examen_rad0, #id_codigo_rad0').val('');
    });
});
    var table = $('#example').DataTable({
        language: {
            emptyTable: "Recuerde que si tiene una orden física, no se mostrará en el listado"
        }
    });
    function initTooltips() {
        $('[data-toggle="tooltip"]').tooltip({
            content: function () {
            return $(this).attr("title");
            },
            position: {
            my: "center bottom-10",
            at: "center top",
            using: function (position, feedback) {
                $(this).css(position)
                    .addClass(feedback.vertical === "top" ? "ui-tooltip-top" : "ui-tooltip-bottom");
            }
            }
        });
    }
    initTooltips();

    table.on('draw.dt', function () {
        initTooltips();
    });

    //

    $('body').on('click', '#opciones_de_llamado', function () {   
        $('#FormularioOpcionesLlamado').trigger("reset");
        $('#modalOpcionesLlamado').modal({backdrop: 'static', keyboard: false, show: true})
        
        $('#paciente').html($(this).data('asegurado'));
        $('#id_cita').val($(this).data('id-cita'));
        $('#id_rad').val($(this).data('id-rad'));
        $('#id_servicio_rad').val($(this).data('id-servicio-rad'));
        $('#id_pp').val($(this).data('id-pp'));
        $('#id_ticket').val($(this).data('id-ticket'));
        $('#modalOpcionesLlamado').modal('show');  
        // $('#fecha_ticket').html($(this).data('fecha_ticket'));
        // $('#cod_totem').html($(this).data('cod_totem'));
        // $('#totem_nombre').html($(this).data('totem_nombre')); 

        llamada($(this).data('id-cita'),$(this).data('id-ticket'));
    });

    $('body').on('click', '#llamar_paciente', function () {  
        const id_cita=$('#id_cita').val(); 
        const id_ticket=$('#id_ticket').val(); 
        llamada(id_cita,id_ticket); 
    });

    function llamada(id_cita,id_ticket){
        console.log(`ticket : ${id_ticket}`);
        
        const url = "{{ url('llamada_imagenes') }}";
        $.ajax({
        url: `${url}/${id_cita}/${id_ticket}`,
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

    $('body').on('click', '#atender_paciente', function () {  
        const id_cita=$('#id_cita').val(); 
        const id_ticket=$('#id_ticket').val(); 
        llamadaAtendido(id_cita, id_ticket); 
    });

    function llamadaAtendido(id_cita, id_ticket){
        const url = "{{ url('llamadaAtendido_imagenes') }}";
        $.ajax({
        url: `${url}/${id_cita}/${id_ticket}`,
        dataType: "json",
        success: function(result){
            console.log(result);
            
            if(result.idCita !== 0){
                Swal.fire({
                    title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                    html: '<p class="descripcion-alert"><span><b>PROCESO DE LLAMADO, ATENDIDO.</b><br></span></p>', 
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

    $(".close_llamado").click(function() {
        $("#modalOpcionesLlamado").hide();
        const id_cita=$('#id_cita').val(); 
        const id_ticket=$('#id_ticket').val(); 
        cancela_llamada(id_cita,id_ticket);
    });

    function cancela_llamada(id_cita,id_ticket){
        const url = "{{ url('cancela_llamada_imagenes') }}";
        $.ajax({
        url: `${url}/${id_cita}/${id_ticket}`,
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

    /*$('#atender_paciente').click(function () {
        const id_rad = $('#id_rad').val();  
        const id_servicio_rad = $('#id_servicio_rad').val();  
        const id_pp = $('#id_pp').val();
        const id_cita = $('#id_cita').val();
        const url = "{{ url('radiologia_informe') }}";

        window.location.href = `${url}/${id_rad}/${id_servicio_rad}/${id_pp}/${id_cita}`;
    });*/

</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
        <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; color: rgb(255, 255, 255);"></span> Buscar Examenes de Radiologia - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <form id="formExamenRadiologia" class="form-horizontal" method="post" action="{{ route('listar_examenes_radiologicos_ticket')}}">
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
 
                        <div id="divcitaa" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Desde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Cita Desde" value="<?php echo $fechaDesde ?>" maxlength="10" readonly>  
                            </div>    
                        </div>
                        <div id="divcitaa1" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Fecha Cita Hasta</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Cita Hasta" value="<?php echo $fechaHasta ?>" maxlength="10" readonly> 
                            </div>    
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" for="form1">Nro. Documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" placeholder="Buscar..."id="dni_beneficiario" name="dni_beneficiario"
                                    maxlength="20"
                                    onkeypress="return soloNumeros(event)"
                                    onpaste="limpiarPegado(event)"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value="{{ $dni_beneficiario }}">
                                <!--span class="input-group-btn">
                                    <button class="btn btn-success btn-sm" id="btnBuscarSF" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span-->
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Tipo Atención</label>
                                <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion">
                                    <?php foreach ($tipo_atenciones as $row) : ?>
                                    @if( $row->id === 1 )
                                        <option value="<?php echo $row->id  ?>"<?php echo ($row->id == 1) ? 'selected' : '' ?>><?php echo $row->nombre  ?></option>
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


                        <!--div id="divestadoinf" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                            <label class="control-label">Estado Informe</label>
                            <select class="form-control input-sm" name="estado_informe" id="estado_informe">
                                <option value="0" {{ $id_estado_informe_temp == "0" ? 'selected' : '' }}>Todos</option> 
                                <option value="1" {{ $id_estado_informe_temp == "1" ? 'selected' : '' }}>Pendiente</option>
                                <option value="2" {{ $id_estado_informe_temp == "2" ? 'selected' : '' }}>Atendido</option> 
                            </select>
                        </div-->

                        <div id="divestadoLlamado" class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">Estado Informe</label>
                            <select class="form-control input-sm" name="estado_llamado" id="estado_llamado">
                                <option value="1" selected >Pendiente</option>
                                <option value="2">Atendido</option> 
                            </select>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-4">
                    
                </div>              
                <div class="row" style="margin: 0; padding: 0; display: flex; justify-content: flex-end;">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: flex; justify-content: flex-end;">
                        {{--@if((auth()->user()->tipo_registro== 1 || auth()->user()->tipo_registro==7) && auth()->user()->flag_reporte_imagenes== 1) --}}
                            <!--div class="col-md-4" style="padding: 0; margin: 5px;">
                                <button type="button" 
                                        class="btn btn-success form-control input-sm btn-reporte" 
                                        data-toggle="modal" 
                                        data-target="#modal_reportes_imagenologia"
                                        style="font-family: Roboto, 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    <i class="fa fa-file-text-o"></i> Generar Reporte
                                </button>
                            </div-->
                        {{--@endif--}}
                        <div class="col-md-4" style="padding: 0; margin: 5px;">
                            <button type="submit" class="form-control btn btn-success input-sm btn-buscar">
                                        <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                        <!--div class="col-md-4" style="padding: 0; margin: 5px;">
                            <a class="form-control btn btn-danger input-sm" id="btnLimpiar" style="font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;">
                                <i class="fa fa-trash-o fa-lg"></i> Limpiar
                            </a>
                        </div-->
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
                                <!--th>Acciones</th-->
                                <th>Llamar</th>
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
                                    <div> <?php echo  $r->paterno_beneficiario." ".  $r->materno_beneficiario ." , ". $r->nombre_beneficiario  ?></div>
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
                                    <div><strong>N°:</strong> {{$r->id_rad}} 
                                        @if($r->id_tipo_atencion_prestacion == 1)
                                            {{ \Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y') }}
                                        @elseif(in_array($r->id_tipo_atencion_prestacion, [2, 3]))
                                            {{ \Carbon\Carbon::parse(!empty($r->id_evolucion) ? $r->fecha_evolucion : $r->fecha_orden)->format('d/m/Y') }}
                                        @endif
                                    </div>
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
                                            <b>SERVICIO: </b>{{$r->consultorio}}<br>
                                            <b>CONSULTORIO: </b>{{$r->sub_consultorio_desc}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 2)
                                            <b>SERVICIO: </b>{{$r->especialidad_emergencia}}<br>
                                            <b>CONSULTORIO: </b>{{$r->sub_consultorio_desc}}
                                        @elseif ($r->id_tipo_atencion_prestacion == 3 )
                                            @if ($r->ubicacion_cama)
                                                {{ $r->ubicacion_cama->consultorio }}
                                                <b>SERVICIO: </b>{{ $r->ubicacion_cama->consultorio }}<br>
                                                <b>CONSULTORIO: </b>{{$r->sub_consultorio_desc}}
                                            @else
                                                <b>SERVICIO: </b> No asignado
                                            @endif         
                                        @endif
                                    </div>
                                    <!--div class="div-icon" > 
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
                                    </div-->
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
                                        <div><strong>F.CITA:</strong> {{ \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A') }}</div>
                                        <div><strong>CONSULTORIO: </strong>{{ $r->consultorio }}</div>
                                    @elseif(in_array($r->id_tipo_atencion_prestacion, [2, 3]))
                                        <div>
                                            <strong>F.PROGRAMADA:</strong>
                                            {{ !empty($r->id_evaluacion) ? (!empty($r->fecha_hora_cita_radiologia) ? \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A'):
                                                \Carbon\Carbon::parse($r->fecha_evolucion)->format('d/m/Y')) : (!empty($r->fecha_hora_cita_radiologia) ? 
                                                \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A'): \Carbon\Carbon::parse($r->fecha_orden)->format('d/m/Y'))
                                            }}
                                        </div>
                                    @endif
                                    <div><strong>EXAMEN: </strong>{{$r->codigo_cpms_alterno}} - {{$r->descripcion_cpms}}</div>
                                    <!--@if (($id_estado_muestra_temp == "0" ||$id_estado_muestra_temp == "1")&& Auth::user()->tipo_registro == 6 ) -->
                                        @if($r->flag_validated == 1 || ($r->confirmar_toma_created_at !== null && $r->confirmar_toma_created_at !== ""))
                                            @if($r->id_tipo_atencion_prestacion == 1 && !empty($r->comentario))
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px; background-color: #ccc; pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                                href="javascript:void(0)"
                                                class="btn btn-info btn-xs disabled">
                                                    <i class="fa fa-exclamation-circle" ></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 2 && $r->comentario != null)
                                                <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                    <a style="font-size: 10px; background-color: #ccc; pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                                    href="javascript:void(0)"
                                                    class="btn btn-info btn-xs disabled">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        @else 
                                            @if($r->id_tipo_atencion_prestacion == 1)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}','{{$r->id_pp}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 2 && $r->id_cita != null)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}','{{$r->id_pp}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle" ></i>
                                                </a>
                                            </div>
                                            @elseif($r->id_tipo_atencion_prestacion == 3 && $r->id_cita != null)
                                            <div class="observar" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Observar/Reprogramar</b>">
                                                <a style="font-size: 10px;background-color: #ee4a34; " href="javascript:void(0)" onclick="modalForm('{{$r->id_cita}}','{{$r->id_pp}}')" class="btn btn-info btn-xs">
                                                    <i class="fa fa-exclamation-circle" ></i>
                                                </a>
                                            </div>
                                            @endif 
                                        @endif 
                                    @endif 
                                </td>
                                <!-- <td style="position: relative;width: 190px;">
                                    <div>  {{ \Carbon\Carbon::parse($r->fecha_hora_cita_radiologia)->format('d/m/Y h:i:s A') }}</div>  
                                </td> -->
                                {{-- Toma de muestra --}}
                                <td style="position: relative;width: 190px;">
                                    <!-- <div>{{$r->confirmar_toma_created_at == null ? "Pendiente" :\Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') }}  </div>
                                    <div>{{$r->nom_servicio}}</div>    -->
                                    <div style="color: {{ $r->confirmar_toma_created_at == null ? '#d43f3a' : 'green' }};font-weight:bold;">
                                        {{ $r->confirmar_toma_created_at == null ? 'PENDIENTE' : \Carbon\Carbon::parse($r->confirmar_toma_created_at)->format('d/m/Y h:i:s A') }}<br>{{ $r->nom_servicio }}
                                    </div>
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
                                        <!--div>{{$r->validated_at == '' ? ' - / - / - ' :\Carbon\Carbon::parse($r->validated_at)->format('d/m/Y') }}  </div-->
                                        {{--@if( ($r->cod_siteds_susalud !== null) && ($r->cod_siteds_susalud !== '') )--}}                                    
                                            {{--@foreach($r->servicio_examen as $servicio)                                               
                                                @if($servicio->estado_examen !== null )
                                                    @if($servicio->flag_validated == "1" && $servicio->ruta_prestacion_procedimiento !== null && $servicio->ruta_prestacion_procedimiento !== "")
                                                    <div class="adenda" > 
                                                        <button type="button" class="btn btn-info btn-xs" style="background-color: #83996a;font-size: 10px;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Agregar Adenda</b>" onclick="registrarInformeAdicional({{$r->id_rad}},{{$servicio->id_servicio_radiologia}},{{$r->id_pp}},{{$r->id_cita}})"><i class="fa fa-edit"></i></button>
                                                    </div> 
                                                    @endif
                                                @endif
                                            @endforeach--}}
                                        {{--@endif--}}
                                        
                                    </td>
                                @endif
                                {{-- LLAMADA --}}
                                @if ( auth()->user()->id_establecimiento == 76 && auth()->user()->flagllamadacita == 1 )
                                    @if ( $r->id_estado_cita = 1 )
                                        <td>@if ($r->estado_presencia !== null && $r->estado_presencia !== '' && $r->id_estado_ticket !== 3)
                                                {{--anteriormente se validaba con el codigo siteds: && $r->cod_siteds_susalud !== null && $r->cod_siteds_susalud !== ''--}}
                                                <button class="btn btn-app  btn-success-llamado btn-xs" id="opciones_de_llamado" name="opciones_de_llamado" data-id-cita="{{$r->id_cita}}" data-asegurado="{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}}" data-id-rad="{{$r->id_rad}}" data-id-servicio-rad="{{--$servicio->id_servicio_radiologia--}}" data-id-pp="{{$r->id_pp}}" data-id-ticket="{{$r->id_ticket}}"><i class="fa fa-tv"></i> LLAMAR @if ($r->cantidad_llamada > 0) <span class="badge bg-purple" style="background-color: red;"><b>{{$r->cantidad_llamada}}</b></span>@endif</button>
                                            @else
                                                <button class="btn btn-app  btn-success-llamado btn-xs" disabled><i class="fa fa-tv"></i> LLAMAR</button>
                                            @endif
                                            @if ($r->hora_presencia !== null && $r->hora_presencia !== '')    
                                                <span style = "margin-left: 3rem;" >
                                                    <b style="color:#000000">Hora de presencia :</b> {{$r->hora_presencia}}
                                                </span>
                                            @endif
                                        </td>
                                    @endif
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
                    <input type="hidden" id="id_ticket" name="id_ticket">

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

<!--============================================ MODAL REPORTE IMÁGENES =========================================================-->
<div class="modal fade" id="modal_reportes_imagenologia" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">REPORTE DE IMAGENOLOGÍA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form id="frmReporteImagenologia">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{ $id_establecimiento }}">
            <input type="hidden" id="id_nivel" value="{{ isset($nivel) ? $nivel : 0 }}">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="fecha_desde">Fecha Orden desde:</label>
                    <input type="date" class="form-control form-control-sm" id="fecha_desde" name="fecha_desde" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="fecha_hasta">Fecha Orden hasta:</label>
                    <input type="date" class="form-control form-control-sm" id="fecha_hasta" name="fecha_hasta" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="nro_doc">Nro. Documento</label>
                    <input type="text" class="form-control form-control-sm"
                        placeholder="Ingrese el número de documento..."
                        id="nro_doc" name="nro_doc" maxlength="20"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="form-group col-md-3">
                    <label for="tipo_atencion">Tipo Atención</label>
                    <select class="form-control form-control-sm" name="tipo_atencion" id="tipo_atencion">
                        <option value="0">Todos</option>
                        <?php foreach ($tipo_atenciones as $row) : ?>
                            <option value="<?php echo $row->id ?>"
                                    @if(isset($id_tipo_atencion) && $row->id == $id_tipo_atencion) selected @endif>
                                    <?php echo $row->nombre ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="id_servicio_imagenes">Servicios de Imágenes</label>
                    <select class="form-control form-control-sm" name="id_servicio_imagenes" id="id_servicio_imagenes">
                        <option value="0">--Seleccionar--</option>
                        @foreach ($listServiciosImagenes as $row)
                            <option value="{{$row->id_servicio}}" @if($row->id_servicio == $id_servicio_imagenes_temp) selected @endif>
                                {{$row->nombre}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="cod_examen">Examen Radiológico</label>
                    <input type="text" class="form-control form-control-sm nombre_examen_rad"
                        name="nombre_examen_rad[]" id="nombre_examen_rad0"
                        placeholder="Ingrese el examen de Radiografía..." required>
                    <input type="hidden" name="id_codigo_rad[]" id="id_codigo_rad0">
                </div>
            </div>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="button" id="btnExportarReporte" class="btn btn-success btn-exportar"><i class="fa fa-file-excel-o"></i> Exportar</button>
      </div>
      
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
