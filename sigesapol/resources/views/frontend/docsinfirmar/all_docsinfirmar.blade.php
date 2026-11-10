<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .table-sortable tbody tr {
        cursor: move;
    }

    #global {
        min-height: 650px !important;
        width: 100%;
        border: 1px solid #ddd;
        margin: 15px

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

    #modalReporteFirmasEnfermeria .modal-dialog {
        width: 95% !important;
        max-width: 1600px !important;
    }

    #modalReporteFirmasEnfermeria .modal-body {
        max-height: calc(100vh - 150px);
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        #modalReporteFirmasEnfermeria .modal-dialog {
            width: 98% !important;
            margin: 5px auto;
        }
    }

    .swal2-icon.swal2-warning {
        font-size: 15px;
    }

    .swal-wide-form{
        width: 500px !important;
        padding: 0px 20px !important;
    }

    #dni_beneficiario, #fecha_inicio, #fecha_fin,#estado_firma {
        padding-top: 8px; 
        padding-bottom: 8px;
        height: 38px;
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important;
    }

    .btn-buscar_dni {
        height: 38px; 
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important;
        border: 2px solid #A1A1A1;
        background-color: #fff;
        color: #8a8a8adb;
        transition: all 0.2s ease;
    }

    .btn-buscar_dni:hover {
        background-color: #bebebedb;
        color: #333;
        border-color: #b7b7b7ff;
    }

    .fecha-input .input-group-addon {
        background: transparent;  
        border: none;             
    }

    .fecha-input .input-group-addon i {
        color: #555;              
        font-size: 27px;          
    }

    .btn-buscar-pequeno {
        height: 38px;
        padding-top: 8px;
        padding-bottom: 8px;
        width: 90%;          
        display: block;
        margin: 0 auto;      
        border-radius: 6px;
    }

    .btn-firmar-pequeno {
        background-color: #0085FC !important;
        height: 38px;
        padding-top: 8px;
        padding-bottom: 8px;
        width: 90%;       
        display: block;
        margin: 0 auto;   
        border-radius: 6px;
    }
    
    .btn-firmar-pequeno:hover {
        background-color: #0076deff !important;
        color: #ffff;
        border-color: #0076deff !important;
    }

    .btn-exp-emg {
        background-color: #267E6C !important;
        height: 38px;
        padding-top: 8px;
        padding-bottom: 8px;
        width: 90%;       
        display: block;
        margin: 0 auto;   
        border-radius: 6px;
    }

    .btn-exp-emg:hover {
        background-color: #48C9B0 !important;
        color: #ffff;
        border-color: #48C9B0 !important;
    }

    .btn-exp-hsp {
        background-color: #118FC0 !important;
        height: 38px;
        padding-top: 8px;
        padding-bottom: 8px;
        width: 90%;       
        display: block;
        margin: 0 auto;   
        border-radius: 6px;
    }

    .btn-exp-hsp:hover {
        background-color: #4EC3F0 !important;
        color: #ffff;
        border-color: #4EC3F0 !important;
    }
    
    .btn-pdf {
        background: transparent;
        border: none;
    }

    .btn-pdf.sin-firma {
        color: #5cb85c; 
    }

    .btn-pdf.firmado {
        color: #EB1515; 
    }

    .btn-pdf-nota-enf {
        background: transparent;
        border: none;
    }

    .btn-pdf-nota-enf.sin-firma {
        color: #5cb85c;
    }

    .btn-pdf-nota-enf.firmado {
        color: #EB1515;
    }

    #pdfModal .modal-header {
    position: relative;
    padding: 1rem;
    }

    #pdfModal .modal-header .close {
    position: absolute;
    top: 0.25rem;
    right: 0.5rem;
    }

    .chkFirmar, .chkFirmarNotaEnfermeria {
        width: 20px;
        height: 20px;
        /*accent-color: green;*/
        cursor: pointer;
        margin-top: 3px;
    }

    .disabled-link {
        pointer-events: none !important;
        opacity: 0.6 !important;
        cursor: not-allowed !important;
    }
    #prevPdf {
        background-color: #5DD10F;
        color: #ffff;
        border-color: #5DD10F;
    }

    #nextPdf {
        background-color: #5DD10F;    
        border-color: #5DD10F;
        color: white;
    }

    .btn-conf-pequeno {
        height: 38px;
        padding-top: 8px;
        padding-bottom: 8px;
        width: 20%;
        display: block;          
        margin-left: auto;      
        border-radius: 6px;
        
    }
    .select2-container .select2-selection--single {
        height: 34px !important;
        font-size: 12px;
    }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>

<script>
  //--------------------------------------------------------------------------
    $(document).ready(function () {
        $('#spinnerOverlayfirm').hide();
        $('#fecha_inicio').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD/MM/YYYY"
        });

        $('#fecha_fin').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD/MM/YYYY"
        });

        $('#fecha_inicio').click(function () {
            var fechaDesdeIni = $('#fecha_inicio').val();

            $('#fecha_inicio').change(function () {
                var fechaDesde = $('#fecha_inicio').val();
                var fechaHasta = $('#fecha_fin').val();

                if (fechaDesde && fechaHasta) {
                    var f1 = fechaDesde.split("/");
                    var f2 = fechaHasta.split("/");

                    var d1 = new Date(parseInt(f1[2]), parseInt(f1[1]) - 1, parseInt(f1[0]));
                    var d2 = new Date(parseInt(f2[2]), parseInt(f2[1]) - 1, parseInt(f2[0]));

                    if (d1 > d2) {
                        $('#spinnerOverlayfirm').hide();
                        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta", function() {
                            $('#fecha_inicio').val(fechaDesdeIni);
                        });
                        return false;
                    }
                }
            });
        });

        $('#fecha_fin').click(function () {
            var fechaHastaIni = $('#fecha_fin').val();
            $('#fecha_fin').change(function () {
                var fechaDesde = $('#fecha_inicio').val();
                var fechaHasta = $('#fecha_fin').val();

                if (fechaDesde && fechaHasta) {
                    var f1 = fechaDesde.split("/");
                    var f2 = fechaHasta.split("/");

                    var d1 = new Date(parseInt(f1[2]), parseInt(f1[1]) - 1, parseInt(f1[0]));
                    var d2 = new Date(parseInt(f2[2]), parseInt(f2[1]) - 1, parseInt(f2[0]));

                    if (d2 < d1) {
                        $('#spinnerOverlayfirm').hide();
                        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde", function() {
                            $('#fecha_fin').val(fechaHastaIni);
                        });
                        return false;
                    }
                }
            });
        });

        $('#fecha_fin').trigger('change');
        $('#fecha_inicio').trigger('change');

        $("#id_servicio").select2({
            width: 'resolve'
        });

        <?php if ($id_servicio > 0) { ?>
            $("#id_servicio").val("<?php echo $id_servicio; ?>").prop('disabled', true).select2();
        <?php } ?>

        var activeSystemClass = $('.list-group-item.active');

    });
        
    $(document).ready(function () {
        $('#tbldocsinfirmar').on('draw.dt', function() {
        $(this).find('tbody .dataTables_empty').text('No se encontraron resultados');
        });

        $("#system-search").on("keyup", function () {
            table.search(this.value).draw();
        });
    });


    $('#tbldocsinfirmar').on('draw.dt', function() {
        let showHeaderCheckbox = false;

        $('#tbldocsinfirmar tbody tr').each(function() {
            let rowData = $('#tbldocsinfirmar').DataTable().row(this).data();
            if(rowData && (rowData.flag == 0||rowData.flag == 2)){
                showHeaderCheckbox = true;
                return false; 
            }else if(rowData && rowData.documento_por_firmar=== "ALTA EMERGENCIA" && rowData.flag == 1){
                showHeaderCheckbox = true;
                return false;
            }
        });

        if(showHeaderCheckbox){
            $('#chkAllFirmar').show();
        } else {
            $('#chkAllFirmar').hide().prop('checked', false); 
        }

        if(!showHeaderCheckbox){
            $('.chkFirmar').prop('checked', false);
        }
    });


    $(document).on('click', '#chkAllFirmar', function () {
        $('.chkFirmar:visible').prop('checked', this.checked);
    });

    $(document).on('click', '.chkFirmar', function () {
        let total = $('.chkFirmar:visible').length;
        let checked = $('.chkFirmar:visible:checked').length;
        $('#chkAllFirmar').prop('checked', total > 0 && total === checked);
    });

    function process(fecha) {
        if (!fecha) return 0;

        if (fecha.includes('-')) {
            return new Date(fecha).getTime();
        }

        if (fecha.includes('/')) {
            const [d, m, y] = fecha.split('/');
            return new Date(`${y}-${m}-${d}T00:00:00`).getTime();
        }

        return 0;
    }


    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }
    
    const puedeFirmar = {{ (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0) ? 'true' : 'false' }};
    const USER_ID = {{ Auth::id() }};

    function datatablenew(){
        var oTable1 = $('#tbldocsinfirmar').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_docsinfirmar_ajax",
            //"bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
    		    "language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "pageLength": 35,
            "lengthMenu": [[20, 35, 50, 100, 200, 60000], [20, 35, 50, 100, 200, "Todos"]],
            "aoColumns": [
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null }
            ],
    		"dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnPreDrawCallback": function () {
                $("#spinnerOverlay").fadeIn(200);
            },
            "fnDrawCallback": function (json) {
                $("#spinnerOverlay").fadeOut(200);
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover({ container: 'body' });
            },

            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                
                var sEcho                   = aoData[0].value;
                var iNroPagina 	            = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar 	        = aoData[4].value;
    			var fecha_ini               = $('#fecha_inicio').val();
                var fecha_fin               = $('#fecha_fin').val();
                var dni_beneficiario        = $('#dni_beneficiario').val();
                var id_establecimiento      = <?php echo Auth::user()->id_establecimiento; ?>;
                var estado                  = $('#estado_firma').val();
    			var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				      "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{
                        NumeroPagina:iNroPagina,
                        NumeroRegistros:iCantMostrar,
    					fecha_ini:fecha_ini,
                        fecha_fin:fecha_fin,
                        dni_beneficiario:dni_beneficiario,
                        id_establecimiento:id_establecimiento,
                        estado:estado,
    					_token:_token
                    },
                    "beforeSend": function() {
                    $("#spinnerOverlay").show();
                    },
                    "success": function(result) {
                    $("#spinnerOverlay").hide();
                    fnCallback(result);
                    },
                    "error": function(msg, textStatus, errorThrown) {
                    $("#spinnerOverlay").hide();
                    }
                });
            },
            "aoColumnDefs":
                [	
                    {
                        "mRender": function (data, type, row) {
                            return row.id_prestacion;
                        },
        				        "bSortable": false,
                        "aTargets": [0]
                    },
                    {
                        "mRender": function (data, type, row) {                                           
                            let datos_paciente = `
                              <div style="position: relative;">
                                  <!-- Contenido principal alineado a la izquierda -->
                                  <b>${row.tipo_doc_ident}</b>: ${row.nro_doc_ident}<br>
                                  <b>PACIENTE: </b>${row.nombre_paciente}<br>
                                  <b>EDAD:</b> ${row.edad}<br>
                                  <b>SEXO:</b> (${row.sexo})<br>
                              </div>
                              `;
                            return datos_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {     
                            let colorTipo;
                            if (row.tipo_atencion === "AMBULATORIO") {
                                colorTipo = "#32d032ff";       
                            } else if (row.tipo_atencion === "HOSPITALIZACION") {
                                colorTipo = "#0085FC";       
                            } else if (row.tipo_atencion === "EMERGENCIA") {
                                colorTipo = "#EB1515";     
                            } else {
                                colorTipo = "#333333";       
                            }

                            let datos_atencion = `
                                <div style="position: relative;">
                                    <!-- Contenido de texto -->
                                    <b>TIPO ATENCIÓN:</b> <span style="color: ${colorTipo}">${row.tipo_atencion}</span><br>
                                    <b>SERVICIO:</b> ${row.servicio} <br>
                                    <b>FECHA:</b> <b><span style="color: #3c3c3ccd ">${row.fecha_atencion_prestacion}</span></b><br>
                                </div>
                            `;
                            return datos_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
    				{
                        "mRender": function (data, type, row) {
                            let Tipo = `
                              ${row.documento_por_firmar} <br>
                              `;
                            return Tipo;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let color = row.tiempo_firma.includes("Sin Firmar") ? "#EB1515" : "#333333";
                            let datos_atencion = `
                                <div style="position: relative;">
                                    <span style="color: ${color}">${row.tiempo_firma}</span><br>
                                </div>
                            `;
                            return datos_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                      "mRender": function (data, type, row) {
                        let boton_ver_atencion = "";

                        if (row.flag == 0 || row.flag == 2) {
                             //console.log("Entra al if del flag", row);
                            if(row.documento_por_firmar.toUpperCase() == 'ATENCIÓN'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma" data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}" data-flag="${row.flag}" data-id_evol="${row.id_evaluacion}" data-url="/ver_prestacion_atencion_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE DIAGNOSTICO POR IMÁGENES'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma" data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}" data-id_evol="${row.id_evaluacion}"  data-url="/ver_radiologia_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'RECETA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}" data-id_evol="${row.id_evaluacion}" data-url="/ver_receta_vale_sinfirma/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE LABORATORIO'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}" data-id_evol="${row.id_evaluacion}" data-url="/ver_laboratorio_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INTERCONSULTA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/ver_interconsulta_imprimir/${row.id_principal}/false" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INTERCONSULTA-RESPUESTA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/ver_interconsulta_rpta/${row.id_principal}/false" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'EVOLUCION'){
                                if(row.tipo_atencion === "EMERGENCIA"){
                                    boton_ver_atencion += `
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                            <a href="#" class="btn btn-xs btn-pdf sin-firma" data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/ver_evaluacion_firmar/${row.id_principal}" data-toggle="modal" data-target="#pdfModal">
                                                <i class="fa fa-file-pdf-o fa-3x"></i>
                                            </a>
                                        </div>
                                    `;
                                }else if(row.tipo_atencion === "HOSPITALIZACION"){
                                    boton_ver_atencion += `
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                            <a href="#" class="btn btn-xs btn-pdf sin-firma" data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/ver_evolucion_firmar/${row.id_principal}" data-toggle="modal" data-target="#pdfModal">
                                                <i class="fa fa-file-pdf-o fa-3x"></i>
                                            </a>
                                        </div>
                                    `;
                                }
                            }else if(row.documento_por_firmar.toUpperCase() == 'ALTA HOSPITALIZACION'){
                                let pdfs = [];

                                if(row.ruta_firma != null) pdfs.push(row.ruta_firma);
                                else pdfs.push(`/ver_alta_hospitalizacion/${row.id_principal}`);

                                if(row.ruta_epicrisis != null) pdfs.push(row.ruta_epicrisis);
                                else pdfs.push(`/print_hospitalizacion_epicrisis/${row.id_principal}`);

                                if(row.ruta_papeleta_egreso != null) pdfs.push(row.ruta_papeleta_egreso);
                                else pdfs.push(`/print_hospitalizacion_papeleta_egreso/${row.id_principal}`);

                                let pdfsAttr = pdfs.join('||');

                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#"
                                        class="btn btn-xs btn-pdf sin-firma open-multi-pdf-single"
                                        data-pdfs="${pdfsAttr}"
                                        data-tipo-doc="${row.documento_por_firmar}"
                                        data-id_prest="${row.id_prestacion}"
                                        data-id="${row.id_principal}"
                                        data-flag="${row.flag}"
                                        data-id_evol="${row.id_evaluacion}"
                                        data-id_usuario="${USER_ID}"
                                        data-toggle="modal"
                                        data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INFORME RADIOLOGIA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/print_examen_radiologia_lns/${row.id_rad}/${row.id_servicio_radiologia}/${row.id_principal}" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INFORME RADIOLOGIA ADICIONAL(ADENDA)'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/print_examen_radiologia_lns_adicional/${row.id_rad}/${row.id_servicio_radiologia}/${row.id_ira}/${row.id_principal}" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }else if(row.flag == 1){
                           if(row.documento_por_firmar.toUpperCase() == 'ALTA EMERGENCIA'){
                                let pdfs = [];

                                if(row.ruta_egreso != null) pdfs.push(row.ruta_egreso);
                                else pdfs.push(`/pdf_alta_medica/${USER_ID}/${row.id_principal}`);

                                if(row.tipo_egreso == 2){
                                    if(row.ruta_indicaciones != null) pdfs.push(row.ruta_indicaciones);
                                    else pdfs.push(`/pdf_indicaciones/${USER_ID}/${row.id_principal}`);
                                }

                                if(row.tipo_egreso == 4){
                                    if(row.ruta_epicrisis != null) pdfs.push(row.ruta_epicrisis);
                                    else pdfs.push(`/pdf_epicrisis_emer/${USER_ID}/${row.id_principal}`);
                                }

                                if(row.tipo_egreso == 3){
                                    pdfs.push(`/pdf_epicrisis_emer/${USER_ID}/${row.id_principal}`);
                                    pdfs.push(`/pdf_ref_contra_emer/${USER_ID}/${row.id_principal}`);
                                }

                                let pdfsAttr = pdfs.join('||');

                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#"
                                        class="btn btn-xs btn-pdf sin-firma open-multi-pdf-single"
                                        data-pdfs="${pdfsAttr}"
                                        data-tipo-doc="${row.documento_por_firmar}"
                                        data-id_prest="${row.id_prestacion}"
                                        data-id="${row.id_principal}"
                                        data-flag="${row.flag}"
                                        data-id_evol="${row.id_evaluacion}"
                                        data-id_usuario="${USER_ID}"
                                        data-toggle="modal"
                                        data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }else {
                            boton_ver_atencion += `
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF FIRMADO</b>">
                                    <a href="#" class="btn btn-xs btn-pdf firmado" 
                                    data-url="/${row.flag}/${row.ruta_prestacion_ambulatorio}" 
                                    data-toggle="modal" data-target="#pdfModal">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                    </a>
                                </div>
                            `;
                        }
                                                    
                        let datos_paciente = `
                            <div style="position: relative;">
                                <!-- Botón CENTRADO -->
                                <div style="position: absolute; top: 0; left: 50%; transform: translate(-50%, 40%);">
                                    ${boton_ver_atencion}
                                </div>
                            </div>
                            `;
                        return datos_paciente;
                      },
                      "bSortable": false,
                      "aTargets": [5]
                    },
                    {
                    "mRender": function (data, type, row) {
                        if ((row.flag == 0||row.flag == 2) && puedeFirmar) {
                            <?php if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0) : ?>
                                if (row.documento_por_firmar && row.documento_por_firmar.toUpperCase() === 'EVOLUCION') {
                                    return `
                                        <div style="text-align:center;">
                                            <input type="checkbox" class="chkFirmar" data-tipo-at="${row.id_tipo_atencion}" data-id-prestacion="${row.id_prestacion}" data-tipo-doc="${row.documento_por_firmar}" data-id_evol="${row.id_evaluacion}" data-id-interconsulta="${row.id_interconsulta}" data-flag="${row.flag_prestacion}" data-id_principal="${row.id_principal}" data-sub_flag="${row.flag}" value="${row.id_prestacion}">
                                            <input type="hidden" data-id-prestacion="${row.id_prestacion}" data-tipo-at="${row.id_tipo_atencion}" data-id-principal="${row.id_principal}" value="${row.documento_por_firmar}">
                                        </div>
                                    `;
                                } else {
                                    return `
                                        <div style="text-align:center;">
                                            <input type="checkbox" class="chkFirmar" data-tipo-at="${row.id_tipo_atencion}" data-id-prestacion="${row.id_prestacion}" data-tipo-doc="${row.documento_por_firmar}" data-id_evol="${row.id_evaluacion}" data-id-interconsulta="${row.id_interconsulta}" data-flag="${row.flag_prestacion}" data-id_principal="${row.id_principal}" data-sub_flag="${row.flag}" value="${row.id_prestacion}">
                                            <input type="hidden" data-id-prestacion="${row.id_prestacion}" data-tipo-at="${row.id_tipo_atencion}" data-id-principal="${row.id_principal}" value="${row.documento_por_firmar}">
                                        </div>
                                    `;
                                }
                            <?php endif; ?>
                        } else if (row.tipo_atencion === "EMERGENCIA" && row.flag == 1 && puedeFirmar) {
                            <?php if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0): ?>
                                return `
                                    <div style="text-align:center;">
                                        <input type="checkbox" class="chkFirmar" data-tipo-at="${row.id_tipo_atencion}" data-id-prestacion="${row.id_prestacion}" data-tipo-doc="${row.documento_por_firmar}" data-flag="${row.flag_prestacion}" data-id_principal="${row.id_principal}" data-id_evol="${row.id_evaluacion}" data-id-interconsulta="${row.id_interconsulta}" data-sub_flag="${row.flag}" value="${row.id_prestacion}">
                                        <input type="hidden" data-id-prestacion="${row.id_prestacion}" data-tipo-at="${row.id_tipo_atencion}" data-id-principal="${row.id_principal}" value="${row.documento_por_firmar}">
                                    </div>
                                `;
                            <?php endif; ?>
                        } else {
                            return `<div style="text-align:center;"></div>`;
                        }
                    },
                    "bSortable": false,
                    "aTargets": [6]
                    }

                ]
        });
    }
//================================================================== PARA FECHAS ================================================================================================
    $(document).ready(function () {
        function fechaHoy() {
            const hoy = new Date();
            const dd = String(hoy.getDate()).padStart(2, '0');
            const mm = String(hoy.getMonth() + 1).padStart(2, '0');
            const yyyy = hoy.getFullYear();
            return `${dd}/${mm}/${yyyy}`;
        }

        function parseFecha(fecha) {
            const partes = fecha.split('/');
            return new Date(partes[2], partes[1] - 1, partes[0]);
        }

        function configurarFechas(prefijo) {
            const $fechaIni = $('#fecha_ini_' + prefijo);
            const $fechaFin = $('#fecha_fin_' + prefijo);

            $fechaIni.appendDtpicker({
                dateOnly: true,
                autodateOnStart: false,
                minuteInterval: 5,
                amPmInTimeList: false,
                locale: "es",
                closeOnSelected: true,
                dateFormat: "DD/MM/YYYY"
            });

            $fechaFin.appendDtpicker({
                dateOnly: true,
                autodateOnStart: false,
                minuteInterval: 5,
                amPmInTimeList: false,
                locale: "es",
                closeOnSelected: true,
                dateFormat: "DD/MM/YYYY"
            });

            if (!$fechaIni.val()) $fechaIni.val(fechaHoy());
            if (!$fechaFin.val()) $fechaFin.val(fechaHoy());

            $fechaIni.on('change', function () {
                if (!$fechaIni.val() || !$fechaFin.val()) return;

                if (parseFecha($fechaIni.val()) > parseFecha($fechaFin.val())) {
                    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                    $fechaIni.val(fechaHoy());
                }
            });

            $fechaFin.on('change', function () {
                if (!$fechaIni.val() || !$fechaFin.val()) return;

                if (parseFecha($fechaFin.val()) < parseFecha($fechaIni.val())) {
                    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                    $fechaFin.val(fechaHoy());
                }
            });
        }

        configurarFechas('firm');
        configurarFechas('fp');
        configurarFechas('fa');
        configurarFechas('fah');
        configurarFechas('fpe');

        // Select2
        $("#id_servicio").select2({
            width: 'resolve'
        });

        <?php if($id_servicio > 0){ ?>
        $("#id_servicio")
            .val("<?php echo $id_servicio; ?>")
            .prop('disabled', true)
            .trigger('change');
        <?php } ?>
    });
        
    $(document).ready(function () {
        $('#tblfirmados').on('draw.dt', function() {
        $(this).find('tbody .dataTables_empty').text('No se encontraron resultados');
        });

        $("#system-search").on("keyup", function () {
            table.search(this.value).draw();
        });
    });
    
    function datatablenewFirmados() {
        var oTable2 = $('#tblfirmados').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_docsfirmados_ajax", 
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": true,
            "info": true,
            "language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [[20, 50, 100, 200, 60000], [20, 50, 100, 200, "Todos"]],
            "aoColumns": [
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null }
            ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnPreDrawCallback": function () {
                $("#spinnerOverlayfirm").fadeIn(200);
            },
            "fnDrawCallback": function (json) {
                $("#spinnerOverlayfirm").fadeOut(200);
            },
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var sEcho        = aoData[0].value;
                var iNroPagina 	 = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar = aoData[4].value;
                var fecha_ini    = $('#tab-firmado #fecha_ini_firm').val();
                var fecha_fin    = $('#tab-firmado #fecha_fin_firm').val();
                var _token       = "{{ csrf_token() }}";

                oSettings.jqXHR = $.ajax({
    				"dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{
                        NumeroPagina:iNroPagina,
                        NumeroRegistros:iCantMostrar,
    					fecha_ini:fecha_ini,
                        fecha_fin:fecha_fin,
    					_token:_token
                    },
                    "beforeSend": function() {
                    $("#spinnerOverlayfirm").show();
                    },
                    "success": function(result) {
                    $("#spinnerOverlayfirm").hide();
                    fnCallback(result);
                    },
                    "error": function(msg, textStatus, errorThrown) {
                    $("#spinnerOverlayfirm").hide();
                    }
                });
            },
            "aoColumnDefs": [
                {
                    "mRender": function(data, type, row) { return row.id_prestacion; },
                    "bSortable": true,
                    "aTargets": [0]
                },
                {
                    "mRender": function(data, type, row) {
                        return `<b>${row.tipo_doc_ident}</b>: ${row.nro_doc_ident}<br>
                                <b>PACIENTE: </b>${row.nombre_paciente}<br>
                                <b>EDAD:</b> ${row.edad}<br>
                                <b>SEXO:</b> (${row.sexo})`;
                    },
                    "bSortable": true,
                    "aTargets": [1]
                },
                {
                    "mRender": function(data, type, row) {
                        let colorTipo;
                            if (row.tipo_atencion === "AMBULATORIO") {
                                colorTipo = "#32d032ff";       
                            } else if (row.tipo_atencion === "HOSPITALIZACION") {
                                colorTipo = "#0085FC";       
                            } else if (row.tipo_atencion === "EMERGENCIA") {
                                colorTipo = "#EB1515";     
                            } else {
                                colorTipo = "#333333";       
                            }

                        let datos_atencion = `
                            <div style="position: relative;">
                                <!-- Contenido de texto -->
                                <b>TIPO ATENCIÓN:</b> <span style="color: ${colorTipo}">${row.tipo_atencion}</span><br>
                                <b>SERVICIO:</b> ${row.servicio} <br>
                                <b>FECHA:</b> <b><span style="color: #3c3c3ccd ">${row.fecha_atencion}</span></b><br>
                            </div>
                        `;
                        return datos_atencion;
                    },
                    "bSortable": true,
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) { return row.documento_por_firmar; },
                    "bSortable": true,
                    "aTargets": [3]
                },
                {
                    "mRender": function(data, type, row) { return row.fecha_firma; },
                    "bSortable": true,
                    "aTargets": [4]
                },
                {
                    "mRender": function(data, type, row) { 
                        let boton_ver_atencion = "";

                        if (row.flag == 0) {
                            if(row.documento_por_firmar.toUpperCase() == 'ATENCIÓN'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}" data-flag="${row.flag}"  data-url="/ver_prestacion_atencion_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE DIAGNOSTICO POR IMÁGENES'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_radiologia_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'RECETA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_receta_vale_sinfirma/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE LABORATORIO'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_laboratorio_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INTERCONSULTA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_interconsulta_imprimir/${row.id_principal}/false" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'INTERCONSULTA-RESPUESTA'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf sin-firma"  data-tipo-doc="${row.documento_por_firmar}" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-id_evol="${row.id_evaluacion}" data-url="/ver_interconsulta_rpta/${row.id_principal}/false" data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }else if(row.documento_por_firmar.toUpperCase() == 'ALTA EMERGENCIA' && row.flag == 1){
                            let pdfs = [];

                            if(row.ruta_firma != null){
                                pdfs.push(`/${row.flag}/${row.ruta_firma}`);
                            }
                            if(row.tipo_egreso == 2 && row.ruta_indicaciones != null){
                                pdfs.push(`/${row.flag}/${row.ruta_indicaciones}`);
                            }
                            if(row.tipo_egreso == 4 && row.ruta_epicrisis != null){
                                pdfs.push(`/${row.flag}/${row.ruta_epicrisis}`);
                            }
                            if(row.tipo_egreso == 3 /*&& row.ruta_epicrisis != null*/){
                                pdfs.push(`/pdf_epicrisis_emer/${USER_ID}/${row.id_principal}`);
                                pdfs.push(`/pdf_ref_contra_emer/${USER_ID}/${row.id_principal}`);
                            }
                            let pdfsAttr = pdfs.join('||');

                            boton_ver_atencion += `
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF FIRMADO</b>">
                                    <a href="#" 
                                        class="btn btn-xs btn-pdf firmado open-multi-pdf-single"
                                        data-pdfs="${pdfsAttr}"
                                        data-flag="${row.flag}"
                                        data-toggle="modal"
                                        data-target="#pdfModal">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                    </a>
                                </div>`;
                        }else if(row.documento_por_firmar.toUpperCase() == 'ALTA HOSPITALIZACION' && row.flag == 1){
                                let pdfs = [];

                                if(row.ruta_firma != null) pdfs.push(row.ruta_firma);

                                if(row.ruta_epicrisis != null) pdfs.push(row.ruta_epicrisis);

                                if(row.ruta_indicaciones != null) pdfs.push(row.ruta_indicaciones);

                                let pdfsAttr = pdfs.join('||');

                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#"
                                        class="btn btn-xs btn-pdf firmado open-multi-pdf-single"
                                        data-pdfs="${pdfsAttr}"
                                        data-tipo-doc="${row.documento_por_firmar}"
                                        data-id_prest="${row.id_prestacion}"
                                        data-id="${row.id_principal}"
                                        data-flag="${row.flag}"
                                        data-id_evol="${row.id_evaluacion}"
                                        data-id_usuario="${USER_ID}"
                                        data-toggle="modal"
                                        data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                        }
                        else {
                            if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE DIAGNOSTICO POR IMÁGENES'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF FIRMADO</b>">
                                        <a href="#" class="btn btn-xs btn-pdf firmado" data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_radiologia_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else if(row.documento_por_firmar.toUpperCase() == 'ORDEN DE LABORATORIO'){
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF SIN FIRMAR</b>">
                                        <a href="#" class="btn btn-xs btn-pdf firmado"  data-id_prest="${row.id_prestacion}" data-id="${row.id_principal}"  data-flag="${row.flag}"  data-url="/ver_laboratorio_imprimir/${row.id_principal}"  data-toggle="modal" data-target="#pdfModal">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }else{
                                boton_ver_atencion += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>VER PDF FIRMADO</b>">
                                        <a href="#" class="btn btn-xs btn-pdf firmado" 
                                        data-flag="${row.flag}" 
                                        data-url="/${row.flag}/${row.ruta_firma}" 
                                        data-toggle="modal" data-target="#pdfModal">
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                                                    
                        let datos_paciente = `
                            <div style="position: relative;">
                                <!-- Botón CENTRADO -->
                                <div style="position: absolute; top: 0; left: 50%; transform: translate(-50%, 40%);">
                                    ${boton_ver_atencion}
                                </div>
                            </div>
                            `;
                        return datos_paciente;
                    },
                    "bSortable": true,
                    "aTargets": [5]
                }
            ]
        });
    }
    //================================================================== NOTAS DE ENFERMERÍA PENDIENTES ================================================================================================
    function datatablenewNotasEnfermeria() {
        var oTableNotasEnf = $('#tblnotasenfermeria').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_notas_enfermeria_ajax",
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
            "language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "pageLength": 35,
            "lengthMenu": [[20, 35, 50, 100, 200, 60000], [20, 35, 50, 100, 200, "Todos"]],
            "aoColumns": [
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null }
            ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnPreDrawCallback": function () {
                $("#spinnerOverlayNotasEnf").fadeIn(200);
            },
            "fnDrawCallback": function (json) {
                $("#spinnerOverlayNotasEnf").fadeOut(200);
                $('[data-toggle="tooltip"]').tooltip();
            },
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var iNroPagina    = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar  = aoData[4].value;
                var fecha_ini     = $('#fecha_inicio_enf').val();
                var fecha_fin     = $('#fecha_fin_enf').val();
                var dni_beneficiario = $('#dni_beneficiario_enf').val();
                var estado = $('#estado_notas_enf').val();
                var _token = $('#frmBloqueEnfermeria input[name="_token"]').val();
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": {
                        NumeroPagina: iNroPagina,
                        NumeroRegistros: iCantMostrar,
                        fecha_ini: fecha_ini,
                        fecha_fin: fecha_fin,
                        dni_beneficiario: dni_beneficiario,
                        estado: estado,
                        _token: _token
                    },
                    "beforeSend": function() {
                        $("#spinnerOverlayNotasEnf").show();
                    },
                    "success": function(result) {
                        $("#spinnerOverlayNotasEnf").hide();
                        fnCallback(result);
                    },
                    "error": function() {
                        $("#spinnerOverlayNotasEnf").hide();
                    }
                });
            },
            "aoColumnDefs": [
                {
                    "mRender": function (data, type, row) { return row.id_principal; },
                    "bSortable": false,
                    "aTargets": [0]
                },
                {
                    "mRender": function (data, type, row) {
                        return `
                            <div style="position: relative;">
                                <b>${row.tipo_doc_ident}</b>: ${row.nro_doc_ident}<br>
                                <b>PACIENTE: </b>${row.nombre_paciente}<br>
                                <b>EDAD:</b> ${row.edad}<br>
                                <b>SEXO:</b> (${row.sexo})<br>
                            </div>
                        `;
                    },
                    "bSortable": false,
                    "aTargets": [1]
                },
                {
                    "mRender": function (data, type, row) {
                        let colorTipo;
                        if (row.tipo_atencion === "AMBULATORIO") {
                            colorTipo = "#32d032ff";
                        } else if (row.tipo_atencion === "HOSPITALIZACION") {
                            colorTipo = "#0085FC";
                        } else if (row.tipo_atencion === "EMERGENCIA") {
                            colorTipo = "#EB1515";
                        } else {
                            colorTipo = "#333333";
                        }
                        return `
                            <div style="position: relative;">
                                <b>TIPO ATENCIÓN:</b> <span style="color: ${colorTipo}">${row.tipo_atencion}</span><br>
                                <b>SERVICIO:</b> ${row.servicio ?? ''} <br>
                                <b>FECHA:</b> <b><span style="color: #3c3c3ccd ">${row.fecha_atencion}</span></b><br>
                            </div>
                        `;
                    },
                    "bSortable": false,
                    "aTargets": [2]
                },
                {
                    "mRender": function (data, type, row) {
                        return `<span>${row.documento_por_firmar}</span>`;
                    },
                    "bSortable": false,
                    "aTargets": [3]
                },
                {
                    "mRender": function (data, type, row) {
                        let color = row.tiempo_firma.includes("Sin Firmar") ? "#EB1515" : "#333333";
                        return `<span style="color: ${color}">${row.tiempo_firma}</span>`;
                    },
                    "bSortable": false,
                    "aTargets": [4]
                },
                {
                    "mRender": function (data, type, row) {
                        const firmado = row.flag >= 1 && !!row.ruta_firma;
                        let url = '';

                        if (firmado) {
                            if (row.ruta_firma.indexOf('/') !== -1) {
                                url = `/${row.flag}/${row.ruta_firma}`;
                            } else {
                                const hoy = new Date();
                                const anio = hoy.getFullYear();
                                const mes = String(hoy.getMonth() + 1).padStart(2, '0');
                                const dia = String(hoy.getDate()).padStart(2, '0');
                                url = `/${row.flag}/${anio}/${mes}/${dia}/${row.ruta_firma}`;
                            }
                        } else {
                            const esHospi = row.id_tipo_atencion == 3;
                            switch (row.documento_por_firmar) {
                                case 'NOTA DE ENFERMERIA':
                                    if (row.id_tipo_atencion == 1) {
                                        url = `/enfermeria/consulta-externa/pdf/${row.id_principal}`;
                                    } else if (row.id_tipo_atencion == 2) {
                                        url = `/pdf_nota_ingreso_enfermeria_emergencia/${row.id_principal}/2`;
                                    } else {
                                        url = `/pdf_nota_ingreso_enfermeria_hospitalizacion/${row.id_principal}/3`;
                                    }
                                    break;
                                // case 'SIGNOS VITALES':
                                //     url = esHospi ? `/ver_pdf_signos_vitales_hospi/${row.id_asegurado}/${row.id_principal}`: `/ver_pdf_signos_vitales/${row.id_asegurado}/${row.id_principal}`;
                                //     break;
                                // case 'KARDEX':
                                //     url = esHospi ? `/api/kardex-enfermerias/pdf/hospi/${row.id_principal}` : `/api/kardex-enfermerias/pdf/${row.id_principal}`;
                                //     break;
                                case 'BALANCE HIDRICO':
                                    url = esHospi ? `/pdf_balance_hidrico_hospi/${row.id_principal}`: `/pdf_balance_hidrico/${row.id_principal}`;
                                    break;
                                case 'EVOLUCION DE ENFERMERIA':
                                    url = `/pdf_nota_ingreso_evoluciones/${row.id_principal}`;
                                    break;
                                case 'CPT':
                                    url = `/pdf_nota_ingreso_cpt/${row.id_principal}`;
                                    break;
                            }
                        }

                        const claseEstado = firmado ? 'firmado' : 'sin-firma';
                        const tooltip = firmado ? 'VER PDF FIRMADO' : 'VER PDF SIN FIRMAR';

                        return `
                            <div style="text-align:center;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>${tooltip}</b>">
                                <a href="#" class="btn btn-xs btn-pdf-nota-enf ${claseEstado}" data-url="${url}" data-toggle="modal" data-target="#pdfModal">
                                    <i class="fa fa-file-pdf-o fa-3x"></i>
                                </a>
                            </div>
                        `;
                    },
                    "bSortable": false,
                    "aTargets": [5]
                },
                {
                    "mRender": function (data, type, row) {
                        if (row.flag == 0 && puedeFirmar) {
                            <?php if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0) : ?>
                                return `
                                    <div style="text-align:center;">
                                        <input type="checkbox" class="chkFirmarNotaEnfermeria" data-tipo-at="${row.id_tipo_atencion}" data-doc="${row.documento_por_firmar}" value="${row.id_principal}">
                                    </div>
                                `;
                            <?php endif; ?>
                        }
                        return `<div style="text-align:center;"></div>`;
                    },
                    "bSortable": false,
                    "aTargets": [6]
                }
            ]
        });
    }

    $(document).ready(function () {
        $('#fecha_inicio_enf').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD/MM/YYYY"
        });
        $('#fecha_fin_enf').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD/MM/YYYY"
        });
    });

    $(document).on('click', '#btnBuscarNotasEnf', function () {
        datatablenewNotasEnfermeria();
    });

    $(document).ready(function () {
        if (new URLSearchParams(location.search).get('tab') === 'enfermeria') {
            $('#tab-notasenfermeria-tab').tab('show');

            $('#estado_notas_enf').val('P');
            $('#filtroEstadoNotasEnf button[data-estado="P"]').removeClass('btn-default').addClass('btn-primary');
            $('#filtroEstadoNotasEnf button[data-estado="F"]').removeClass('btn-primary').addClass('btn-default');
            $('#btnFirmarNotasEnf').show();
            $('#colReporteFirmasPendEnf').show();

            datatablenewNotasEnfermeria();
        }
    });

    $(document).on('click', '#filtroEstadoNotasEnf button', function () {
        var estado = $(this).data('estado');
        $('#estado_notas_enf').val(estado);

        $('#filtroEstadoNotasEnf button').removeClass('btn-primary').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-primary');

        $('#btnFirmarNotasEnf').toggle(estado === 'P');
        $('#colReporteFirmasPendEnf').toggle(estado === 'P');

        datatablenewNotasEnfermeria();
    });

    $('#tblnotasenfermeria').on('draw.dt', function() {
        $(this).find('tbody .dataTables_empty').text('No se encontraron resultados');
        seleccionadosEnfermeria = [];
        $('#enfermeriaSeleccionadas').val('[]');
        $('#tipoAtencionSeleccionada').val('[]');
        $('#tipoDocumentoSeleccionado').val('[]');
        $('#chkAllFirmarNotasEnf').prop('checked', false);

        let hayPendientes = $('#tblnotasenfermeria tbody .chkFirmarNotaEnfermeria').length > 0;
        $('#chkAllFirmarNotasEnf').toggle(hayPendientes);
    });

    $(document).on('click', '#chkAllFirmarNotasEnf', function () {
        const limite = obtenerLimiteFirmasEnfermeria();
        const checked = this.checked;

        $('.chkFirmarNotaEnfermeria:visible:checked').prop('checked', false).trigger('change');

        if (checked) {
            const visibles = $('.chkFirmarNotaEnfermeria:visible');
            visibles.slice(0, limite).prop('checked', true).trigger('change');

            if (visibles.length > limite) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite de selección',
                    text: `Solo puede seleccionar hasta ${limite} documentos para firmar.`,
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    });

    $(document).on('change', '.chkFirmarNotaEnfermeria', function () {
        const limite = obtenerLimiteFirmasEnfermeria();
        const $chk = $(this);
        const id = String($chk.val());
        const tipoAt = $chk.data('tipo-at');
        const doc = $chk.data('doc');

        if ($chk.is(':checked')) {
            const totalSeleccionados = $('.chkFirmarNotaEnfermeria:checked').length;
            if (totalSeleccionados > limite) {
                $chk.prop('checked', false);
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite de selección',
                    text: `Solo puede seleccionar hasta ${limite} documentos para firmar.`,
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            seleccionadosEnfermeria.push({ id, tipoAt, doc });
        } else {
            const index = seleccionadosEnfermeria.findIndex(x => x.id === id && x.doc === doc);
            if (index !== -1) seleccionadosEnfermeria.splice(index, 1);
        }

        $('#enfermeriaSeleccionadas').val(JSON.stringify(seleccionadosEnfermeria.map(x => x.id)));
        $('#tipoAtencionSeleccionada').val(JSON.stringify(seleccionadosEnfermeria.map(x => x.tipoAt)));
        $('#tipoDocumentoSeleccionado').val(JSON.stringify(seleccionadosEnfermeria.map(x => x.doc)));

        let total = $('.chkFirmarNotaEnfermeria:visible').length;
        let checked = $('.chkFirmarNotaEnfermeria:visible:checked').length;
        let maxSeleccionable = Math.min(total, limite);
        $('#chkAllFirmarNotasEnf').prop('checked', maxSeleccionable > 0 && checked === maxSeleccionable);
    });

    $(document).on('click', '.btn-pdf-nota-enf', function (e) {
        e.preventDefault();
        const url = $(this).data('url') || '';
        $('#pdfFrame').attr('src', url);
        $('#pdfModal .modal-footer').hide();
        $('#pdfModal').modal('show');
    });

    function firmarNotasEnfermeria() {
        if (seleccionadosEnfermeria.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar al menos una nota de enfermería para firmar.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Procesando...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "<?php echo url()?>/genera_csv_notas_enfermeria",
            type: "post",
            data: $("#frmBloqueEnfermeria").serialize(),
            dataType: 'json'
        })
        .done(function (resultado) {
            if (resultado.sw) {
                firmaContexto = 'enfermeria';
                $("#doc_firmar").val(`${resultado.csv}.7z`);
                $("#opt_firma").val(`${resultado.opt}`);
                $("#flagServicioFirma").val("1");
                initInvoker('W');
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Se está cargando el módulo de refirma.',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Atención',
                    text: resultado.message || 'No se encontraron documentos de enfermería para firmar.'
                });
            }
        })
        .fail(function (jqXHR, textStatus) {
            Swal.fire({ icon: 'error', title: 'Error', text: textStatus });
        });
    }

    $(document).on('click', '#btnFirmarNotasEnf', function (e) {
        e.preventDefault();
        firmarNotasEnfermeria();
    });

    async function MiFuncionOkWebNotasEnfermeria() {
        const CSRF_TOKEN = $('meta[name="_token"]').attr('content');

        Swal.fire({
            icon: 'success',
            title: 'Firmando documento(s) de enfermería',
            html: 'Espere un momento...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const firmadosExitosos = [];
            for (const item of seleccionadosEnfermeria) {
                try {
                    let reconocido = true;
                    switch (item.doc) {
                        case 'NOTA DE ENFERMERIA':
                            if (parseInt(item.tipoAt) === 1) {
                                await $.ajax({
                                    url: `/api/enfermeria/consulta-externa/actualizar-firma/${item.id}`,
                                    type: 'PUT',
                                    data: { _token: CSRF_TOKEN }
                                });
                            } else {
                                await $.ajax({
                                    url: `/api/actualizarEstadoFirmaDeNotaDeEnfermeria/${item.id}`,
                                    type: 'POST',
                                    data: { _token: CSRF_TOKEN }
                                });
                            }
                            break;

                        // case 'SIGNOS VITALES':
                        //     await $.ajax({
                        //         url: `/api/postFirmaSV/${item.id}`,
                        //         type: 'POST',
                        //         data: { _token: CSRF_TOKEN }
                        //     });
                        //     break;

                        // case 'KARDEX':
                        //     await $.ajax({
                        //         url: `/refirma/registrar_firma_kardex_enfermeria`,
                        //         type: 'POST',
                        //         data: { _token: CSRF_TOKEN, id_kardex: item.id }
                        //     });
                        //     break;

                        case 'BALANCE HIDRICO':
                            await $.ajax({
                                url: `/actualiza_datos_firma_con_token`,
                                type: 'POST',
                                data: { _token: CSRF_TOKEN, id_resol: item.id }
                            });
                            break;

                        case 'EVOLUCION DE ENFERMERIA':
                            await $.ajax({
                                url: `/actualiza_datos_firma_con_token_evolucion`,
                                type: 'POST',
                                data: { _token: CSRF_TOKEN, id_resol: item.id }
                            });
                            break;

                        case 'CPT':
                            await $.ajax({
                                url: `/actualiza_datos_firma_con_token_cpt`,
                                type: 'POST',
                                data: { _token: CSRF_TOKEN, id_resol: item.id }
                            });
                            break;

                        default:
                            console.warn(`Tipo de documento de enfermería no reconocido: ${item.doc}`);
                            reconocido = false;
                            break;
                    }
                    if (reconocido) {
                        firmadosExitosos.push(item);
                    }
                } catch (errItem) {
                    console.error(`Error actualizando firma de ${item.doc} ${item.id}:`, errItem);
                }
            }

            if (firmadosExitosos.length > 0) {
                try {
                    await $.ajax({
                        url: "<?php echo url()?>/actualizar_flag_docsinfirmar_enfermeria",
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            enfermeriaSeleccionadas: JSON.stringify(firmadosExitosos.map(x => x.id)),
                            tipoDocumentoSeleccionado: JSON.stringify(firmadosExitosos.map(x => x.doc))
                        }
                    });
                } catch (errFlag) {
                    console.error('Error actualizando flag_docsinfirmar de notas de enfermería:', errFlag);
                }
            }

            Swal.close();
            location.href = '/all_docs_sin_firmar?tab=enfermeria';
        } catch (error) {
            Swal.close();
            console.error("Error general en MiFuncionOkWebNotasEnfermeria:", error);
            alert("No se pudo procesar la solicitud.");
        }
    }

    //////////////////////////////////////////////////////////////////////////////// limite max firmas
    function obtenerLimiteFirmas() {
        const now = new Date();
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;
        const userId = {{ Auth::user()->id }};
        
        // Dr JOSE ANSELMO QUIROZ GARCIA || VALDERRAMA HERESI, ZAIRA YANETT || CARRANZA GONZALES, ROSARIO MARGARITA || LLALLERI BOCANGEL, LOURDES MARISOL || BARREDA FUENTES, ANDRES DANIEL 
        if (userId == 4000 || userId == 3657 || userId == 334 || userId == 3655 || userId == 2821) {
            return 50;
        } else if (horaActual >= '16:00' || horaActual <= '07:00') {
            return 35;// Se aumenta de forma progresiva de 20 a 35
        } else {
            return 10;// Se aumenta de forma progresiva de 5 a 10
        }
    }
    //////////////////////////////////////////////////////////////////////////////// limite max firmas enfermería
    function obtenerLimiteFirmasEnfermeria() {
        const now = new Date();
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;
        if (horaActual >= "16:00" || horaActual <= "07:00") {
            return 35;
        }
        return 10;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////
    
    $(document).ready(function () {
        $('#btnBuscar').on('click', fn_ListarBusqueda);
        $('#fecha_inicio, #fecha_fin, #estado_firma,#dni_beneficiario').on('change', fn_ListarBusqueda);
        datatablenew();
    });

    function fn_ListarBusqueda() {
       datatablenew();
    }

    $(document).ready(function () {
        $('#btnBuscarfirm').on('click', fn_ListarBusquedafirm);
        $('#fecha_ini_firm, #fecha_fin_firm').on('change', fn_ListarBusquedafirm);
            datatablenewFirmados();
        });
        function fn_ListarBusquedafirm() {
            datatablenewFirmados();
        }
        
        $(document).on('click', '.btn-pdf.firmado', function() {
            let urlPdf = $(this).data('url');
            let flag = parseInt($(this).data('flag'));

            $('#pdfFrame').attr('src', urlPdf);
            $('#id_prestacion_hidden').val(''); 
            if(flag === 1){
                $('#pdfModal .modal-footer').hide();
            } else {
                $('#pdfModal .modal-footer').show();
            }

            $('#pdfModal').modal('show');
        });

        $('#pdfModal').on('hidden.bs.modal', function () {
            $('#pdfFrame').attr('src', '');
            $('#id_prestacion_hidden').val('');
            $('#id_interconsulta_hidden').val('');
            $('#pdfModal .modal-footer').show();
        });
    // ======================================= VISOR PDF MÚLTIPLE ==================================
    let pdfIndex = 0;
    let pdfList = [];
    function actualizarPdf() {
        $('#pdfFrame').attr('src', pdfList[pdfIndex]);
        $('#prevPdf').toggle(pdfIndex > 0);
        $('#nextPdf').toggle(pdfIndex < pdfList.length - 1);
        $('#pdfNavButtons').toggle(pdfList.length > 1);
    }

    $(document).on('click', '.btn-pdf', function(e){
        e.preventDefault();
        let pdfsAttr = $(this).data('pdfs') || '';
        pdfList = pdfsAttr.split('||').filter(p => p.trim() !== '');
        if (pdfList.length === 0) return;
        pdfIndex = 0;
        let documento_por_firmar = $(this).data('tipo-doc') || '';
        let flag = parseInt($(this).data('flag') || 0, 10);
        let id_prestacion = $(this).data('id') || '';
        $('#id_prestacion_hidden').val(id_prestacion);
        $('#btnRegresar').data('id', id_prestacion);
        $('#btnFirmarModal').data('id-prestacion', id_prestacion);
        if (documento_por_firmar.toUpperCase() === "ALTA EMERGENCIA" || flag === 0) {
            $('#btnRegresar, #btnFirmarModal').show();
        } else {
            $('#btnRegresar, #btnFirmarModal').hide();
        }
        $('#pdfModal').modal('show');
        $('#pdfModal').off('shown.bs.modal').on('shown.bs.modal', function () {
            actualizarPdf();
        });
    });

    $('#nextPdf').on('click', function () {
        if (pdfIndex < pdfList.length - 1) {
            pdfIndex++;
            actualizarPdf();
        }
    });

    $('#prevPdf').on('click', function () {
        if (pdfIndex > 0) {
            pdfIndex--;
            actualizarPdf();
        }
    });

    $('#pdfModal').on('hidden.bs.modal', function () {
        $('#pdfFrame').attr('src', '');
        $('#pdfNavButtons').hide();
        $('#id_prestacion_hidden').val('');
        $('#btnFirmarModal').removeData('id-prestacion');
    });
    
    //======================================= OBTENER INFO PARA CONDICIONALES =========================================
    let prestacionesSeleccionadas = [];
    let tipoantencseleccionado = [];
    let tipodocseleccionado = [];
    let id_principal_selec = [];
    let evaluacionesseleccionadas = [];
    let interconsultaseleccionada = [];
    let firmaContexto = 'principal';
    let seleccionadosEnfermeria = [];

    //=====================================================================================
    //========== PERMITIR MARCAR INDIVIDUALMENTE HASTA X DOCUMENTOS =======================
    function limpiarSeleccionCompleta($chk) {
        $chk.prop('checked', false);
        prestacionesSeleccionadas = [];
        tipodocseleccionado = [];
        tipoantencseleccionado = [];
        id_principal_selec = [];
        evaluacionesseleccionadas = [];
        interconsultaseleccionada = [];
        $("#prestacionesSeleccionadas").val("[]");
        $("#tipodocseleccionado").val("[]");
        $("#tipoantencseleccionado").val("[]");
        $("#id_principal_selec").val("[]");
        $("#evaluacionesseleccionadas").val("[]");
        $("#interconsultaseleccionada").val("[]");
    }

    function agregarSeleccion($chk) {
        const idPrestacion = String($chk.data('id-prestacion'));
        const tipoDoc = ($chk.data('tipo-doc') || '').toUpperCase();
        const tipoAt = ($chk.data('tipo-at') || '');
        const idInterconsultaChk = $chk.data('id-interconsulta') || 0;

        if (!prestacionesSeleccionadas.includes(idPrestacion)) {
            prestacionesSeleccionadas.push(idPrestacion);
            tipodocseleccionado.push(tipoDoc);
            tipoantencseleccionado.push(tipoAt);
            interconsultaseleccionada.push(idInterconsultaChk);
        }

        $("#prestacionesSeleccionadas").val(JSON.stringify(prestacionesSeleccionadas));
        $("#tipodocseleccionado").val(JSON.stringify(tipodocseleccionado));
        $("#tipoantencseleccionado").val(JSON.stringify(tipoantencseleccionado));
        $("#interconsultaseleccionada").val(JSON.stringify(interconsultaseleccionada));

        $('#btnFirmarModal').removeClass('disabled-link').removeAttr('aria-disabled');
    }

    $(document).on('change', '.chkFirmar', function() {
        const limite = obtenerLimiteFirmas();
        const $chk = $(this);
        const idPrestacion = String($chk.data('id-prestacion'));
        const tipoDoc = ($chk.data('tipo-doc') || '').toUpperCase();
        const tipoAt = ($chk.data('tipo-at') || '');
        const idPrestacionBase = $chk.data('id-prestacion'); 
        const flag = parseInt($chk.data('flag')) || 0;
        const sub_flag = parseInt($chk.data('sub_flag')) || 0;
        const idPrincipal = $(this).data('id_principal');
        const idEv = parseInt($chk.data('id_evol'));

        if ($chk.is(':checked')) {
            const totalSeleccionados = $('.chkFirmar:checked').length;
            if (totalSeleccionados > limite) {
                $(this).prop('checked', false);
                const totalVisible = $('.chkFirmar:visible').length;
                const totalVisibleChecked = $('.chkFirmar:visible:checked').length;
                $('#chkAllFirmar').prop('checked', totalVisible > 0 && totalVisible === totalVisibleChecked);
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite de selección',
                    text: `Solo puede seleccionar hasta ${limite} documentos para firmar.`,
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            if (tipoDoc == 'EVOLUCION') {
                const $evolucionChk = $(`.chkFirmar[data-id-principal='${idPrincipal}'][data-tipo-doc^='EVOL']`);
                const evolucionMarcada = $evolucionChk.is(':checked');
                const evolucionFirmada = parseInt($evolucionChk.data('sub_flag')) === 1;
                const prestacionFirmada = flag === 1;

                if (!evolucionMarcada && !evolucionFirmada && !prestacionFirmada) {
                    $chk.prop('checked', false);
                    setTimeout(() => $chk.trigger('change'), 0);
                    limpiarSeleccionCompleta($chk);
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        text: 'Primero debe FIRMAR LA ATENCIÓN para poder firmar la EVOLUCIÓN.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $('#btnFirmarModal')
                            .addClass('disabled-link')
                            .attr('aria-disabled', 'true');
                    });

                    return;
                }   

            } else if (tipoDoc !== 'ATENCIÓN' && tipoDoc !== 'EVOLUCION' && tipoDoc !== 'ALTA EMERGENCIA' && tipoDoc !== 'ALTA HOSPITALIZACION') {
                const $atencionChk = $(`.chkFirmar[data-id-prestacion='${idPrestacionBase}'][data-tipo-doc^='ATEN']`);
                let idPrestacion = $(this).data('id-prestacion');
                const atencionMarcada = $atencionChk.is(':checked');
                const atencionFirmada = parseInt($atencionChk.data('flag')) === 1;
                const prestacionFirmada = flag === 1;
                const docFirmada = parseInt($atencionChk.data('sub_flag')) === 1;
                const idEv = $chk.data('id_evol');
                const tieneEvolucion = idEv != null && idEv !== ''&& idEv !== 0;
                const idInterconsulta = parseInt($chk.data('id-interconsulta')) || 0;

                const tiposDocDependenEvol = [
                    'RECETA',
                    'ORDEN DE LABORATORIO',
                    'ORDEN DE DIAGNOSTICO POR IMÁGENES',
                    'INTERCONSULTA',
                    'INTERCONSULTA-RESPUESTA'
                ];
                const $evolChkGate = $(`.chkFirmar[data-id-prestacion='${idPrestacionBase}'][data-tipo-doc='EVOLUCION']`);
                const evolucionMarcadaGate = $evolChkGate.is(':checked');

                const continuarValidacionFirma = function () {
                    $.ajax({
                        url: `/firmado_evol_id/${idEv}`,
                        method: 'GET',
                        success: function (response) {
                            const evolucionFirmada = Number(response.flag_evolucion) === 1;
                            const $evolChk = $evolChkGate;
                            const evolucionMarcada = evolucionMarcadaGate;
                            $.ajax({
                                url: `/es_quirurgico_docsinfirmar/${idPrestacion}`,
                                type: 'POST',
                                data: {
                                },
                                success: function (response) {
                                    let esQuirurgico = Number(response.esquirurgico) === 1;

                                    if (!esQuirurgico && !prestacionFirmada && !atencionFirmada && !atencionMarcada) {
                                    limpiarSeleccionCompleta($chk);
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Firma requerida',
                                        text: 'Primero debe FIRMAR LA ATENCIÓN.',
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        $('#btnFirmarModal').addClass('disabled-link').attr('aria-disabled', 'true');
                                    });
                                    return;
                                    }
                                    if ($chk.is(':checked')) {
                                        if ($atencionChk.length && !$atencionChk.is(':checked')) {
                                            $atencionChk.prop('checked', true);
                                            agregarSeleccion($atencionChk);
                                        }
                                        if (tieneEvolucion && evolucionFirmada) {
                                            if ($evolChk.length && !$evolChk.is(':checked')) {
                                                $evolChk.prop('checked', true);
                                                agregarSeleccion($evolChk);
                                            }
                                        }
                                        agregarSeleccion($chk);
                                    }
                                }
                            });
                        }
                    });
                };

                const esInterconsultaRespuesta = tipoDoc === 'INTERCONSULTA-RESPUESTA';
                const idInterconsultaCheck = esInterconsultaRespuesta ? idPrincipal : idInterconsulta;
                const $interconsultaChk = idInterconsulta
                    ? $(`.chkFirmar[data-tipo-doc='INTERCONSULTA-RESPUESTA'][data-id_principal='${idInterconsulta}']`)
                    : $();
                const interconsultaMarcada = $interconsultaChk.is(':checked');

                const necesitaCheckInterconsulta = !!(idInterconsultaCheck && (esInterconsultaRespuesta || !interconsultaMarcada));
                const necesitaCheckEvolucion = !!(prestacionFirmada && tieneEvolucion && !evolucionMarcadaGate && tiposDocDependenEvol.includes(tipoDoc));

                $.when(
                    necesitaCheckInterconsulta
                        ? $.get(`/firmado_interconsulta_rpta_id/${idInterconsultaCheck}`)
                        : $.Deferred().resolve({ flag_interconsulta: 1, flag_interconsulta_rpta: 1 }, 'success', null).promise(),
                    necesitaCheckEvolucion
                        ? $.get(`/firmado_evol_id/${idEv}`)
                        : $.Deferred().resolve({ flag_evolucion: 1 }, 'success', null).promise()
                ).done(function (respInterconsulta, respEvolucion) {
                    const dataInterconsulta = respInterconsulta[0];
                    const dataEvolucion = respEvolucion[0];

                    const faltaInterconsultaPrincipal = necesitaCheckInterconsulta && Number(dataInterconsulta.flag_interconsulta) !== 1;
                    const faltaInterconsultaRespuesta = necesitaCheckInterconsulta && !esInterconsultaRespuesta && Number(dataInterconsulta.flag_interconsulta_rpta) !== 1;
                    const faltaEvolucion = necesitaCheckEvolucion && Number(dataEvolucion.flag_evolucion) !== 1;

                    if (faltaInterconsultaPrincipal || faltaInterconsultaRespuesta || faltaEvolucion) {
                        const partes = [];
                        if (faltaEvolucion) partes.push('LA EVOLUCIÓN');
                        if (faltaInterconsultaPrincipal) partes.push('LA INTERCONSULTA');
                        if (faltaInterconsultaRespuesta) partes.push('LA INTERCONSULTA RESPUESTA');

                        limpiarSeleccionCompleta($chk);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Firma requerida',
                            text: 'PRIMERO DEBE FIRMAR ' + partes.join(' Y '),
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#btnFirmarModal').addClass('disabled-link').attr('aria-disabled', 'true');
                        });
                        return;
                    }

                    continuarValidacionFirma();
                });
                return;
            } else if (tipoDoc == 'ALTA EMERGENCIA') {
                const $altaChk = $(`.chkFirmar[data-id-principal='${idPrincipal}'][data-tipo-doc^='ALTA']`);
                const AltaMarcada = $altaChk.is(':checked');
                const AltaFirmada = parseInt($altaChk.data('sub_flag')) === 1;
                const tipoatAlta = $chk.data('tipo-at');
                const prestacionFirmada = flag === 1;
                const idEv = $chk.data('id_evol');
                const tieneEvolucion = idEv != null && idEv !== '' && idEv !== 0;
                $.ajax({
                    url: `/verificar_firma_evol/${idPrincipal}/${tipoatAlta}`,
                    method: 'GET',
                    success: function(response) {
                        const evolucionFirmada = Number(response.flag_evolucion) === 1;

                        if (!AltaMarcada && !prestacionFirmada ) {
                            $chk.prop('checked', false);
                            limpiarSeleccionCompleta($chk);
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                text: 'Primero debe FIRMAR LA ATENCIÓN.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#btnFirmarModal')
                                    .addClass('disabled-link')
                                    .attr('aria-disabled', 'true');
                            });

                            return;
                        } else if (tieneEvolucion && !AltaMarcada && prestacionFirmada && !evolucionFirmada) {
                            $chk.prop('checked', false);
                            limpiarSeleccionCompleta($chk);
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                text: 'Primero debe FIRMAR LA EVOLUCIÓN.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#btnFirmarModal')
                                    .addClass('disabled-link')
                                    .attr('aria-disabled', 'true');
                            });

                            return;
                        }

                        if ($chk.is(':checked')) {
                            agregarSeleccion($chk);
                        }
                    }
                });
                return;
            }else if (tipoDoc == 'ALTA HOSPITALIZACION') {
                const $altaChk = $(`.chkFirmar[data-id-principal='${idPrincipal}'][data-tipo-doc^='ALTA']`);
                const AltaMarcada = $altaChk.is(':checked');
                const AltaFirmada = parseInt($altaChk.data('sub_flag')) === 1;
                const tipoatAlta = $chk.data('tipo-at');
                const prestacionFirmada = flag === 1;
                const idEv = $chk.data('id_evol');
                const tieneEvolucion = idEv != null && idEv !== '' && idEv !== 0;
                $.ajax({
                    url: `/verificar_firma_evol/${idPrincipal}/${tipoatAlta}`,
                    method: 'GET',
                    success: function(response) {
                        const evolucionFirmada = Number(response.flag_evolucion) === 1;

                        if (!AltaMarcada && !prestacionFirmada ) {
                            $chk.prop('checked', false);
                            limpiarSeleccionCompleta($chk);
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                text: 'Primero debe FIRMAR LA ATENCIÓN.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#btnFirmarModal')
                                    .addClass('disabled-link')
                                    .attr('aria-disabled', 'true');
                            });

                            return;
                        } else if (tieneEvolucion && !AltaMarcada && prestacionFirmada && !evolucionFirmada) {
                            $chk.prop('checked', false);
                            limpiarSeleccionCompleta($chk);
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                text: 'Primero debe FIRMAR LA EVOLUCIÓN.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#btnFirmarModal')
                                    .addClass('disabled-link')
                                    .attr('aria-disabled', 'true');
                            });

                            return;
                        }

                        if ($chk.is(':checked')) {
                            agregarSeleccion($chk);
                        }
                    }
                });
                return;
            }

            $('#btnFirmarModal').removeClass('disabled-link').removeAttr('aria-disabled');

            if (!prestacionesSeleccionadas.includes(idPrestacion)) {
                prestacionesSeleccionadas.push(idPrestacion);
                tipodocseleccionado.push(tipoDoc);
                tipoantencseleccionado.push(tipoAt);
                interconsultaseleccionada.push($chk.data('id-interconsulta') || 0);
            }

        } else {
            if (tipoDoc === 'ATENCIÓN' || tipoDoc === 'ATENCION') {
                $(`.chkFirmar[data-id-prestacion='${idPrestacionBase}']`).each(function() {
                    $(this).prop('checked', false);
                    const idAux = String($(this).data('id-prestacion')); 
                    prestacionesSeleccionadas = prestacionesSeleccionadas.filter(id => id !== idAux);
                });
            } else if (tipoDoc === 'EVOLUCION') {
                const tiposDocDependenEvol = [
                    'RECETA',
                    'ORDEN DE LABORATORIO',
                    'ORDEN DE DIAGNOSTICO POR IMÁGENES',
                    'INTERCONSULTA'
                ];
                tiposDocDependenEvol.forEach(dep => {
                    const $depChk = $(`.chkFirmar[data-id-prestacion='${idPrestacionBase}'][data-tipo-doc='${dep}']`);

                    if ($depChk.length && $depChk.is(':checked')) {
                        $depChk.prop('checked', false);
                        const idAux = String($depChk.data('id-prestacion'));
                        prestacionesSeleccionadas = prestacionesSeleccionadas.filter(id => id !== idAux);
                    }
                });

            } else if (tipoDoc === 'INTERCONSULTA-RESPUESTA') {
                $(`.chkFirmar[data-id-interconsulta='${idPrincipal}']`).each(function() {
                    if ($(this).is(':checked')) {
                        $(this).prop('checked', false);
                        const idAux = String($(this).data('id-prestacion'));
                        prestacionesSeleccionadas = prestacionesSeleccionadas.filter(id => id !== idAux);
                    }
                });
            } else {
                prestacionesSeleccionadas = prestacionesSeleccionadas.filter(id => id !== idPrestacion);
            }
        }

        if ($('.chkFirmar:checked').length === 0) {
            prestacionesSeleccionadas = [];
            tipodocseleccionado = [];
            tipoantencseleccionado = [];
            id_principal_selec =[];
            interconsultaseleccionada = [];
        }

        $("#prestacionesSeleccionadas").val(JSON.stringify(prestacionesSeleccionadas));
        $("#tipodocseleccionado").val(JSON.stringify(tipodocseleccionado));
        $("#tipoantencseleccionado").val(JSON.stringify(tipoantencseleccionado));
        $("#interconsultaseleccionada").val(JSON.stringify(interconsultaseleccionada));
    });


    $(document).on('change', '#chkAllFirmar', function () {
        const limite = obtenerLimiteFirmas();
        const checked = this.checked;

        $('.chkFirmar').prop('checked', false);
        prestacionesSeleccionadas = [];
        tipoantencseleccionado = [];
        tipodocseleccionado = [];
        id_principal_selec = [];
        evaluacionesseleccionadas = [];
        interconsultaseleccionada = [];

        if (checked) {
            const visibles = $('.chkFirmar:visible');
            const cantidad_firma = visibles.slice(0, limite);

            cantidad_firma.each(function() {
                const $chk = $(this);
                const idPrestacion = String($chk.data('id-prestacion'));

                const tipoDoc = ($chk.data('tipo-doc') || '').toUpperCase();
                const idPrincipal = ($chk.data('id_principal') || '');
                const tipoAt = ($chk.data('tipo-at') || '');
                const idEvolucion = ($chk.data('id_evol') || '');
                const idInterconsulta = ($chk.data('id-interconsulta') || 0);

                $chk.prop('checked', true);

                prestacionesSeleccionadas.push(idPrestacion);
                tipoantencseleccionado.push(tipoAt);
                tipodocseleccionado.push(tipoDoc);
                id_principal_selec.push(idPrincipal);
                evaluacionesseleccionadas.push(idEvolucion);
                interconsultaseleccionada.push(idInterconsulta);
            });

            if (visibles.length > limite) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite de selección',
                    text: `Solo puede seleccionar hasta ${limite} documentos para firmar.`,
                    confirmButtonText: 'Aceptar'
                });
            }
        }

        $("#prestacionesSeleccionadas").val(JSON.stringify(prestacionesSeleccionadas));
        $("#tipoantencseleccionado").val(JSON.stringify(tipoantencseleccionado));
        $("#tipodocseleccionado").val(JSON.stringify(tipodocseleccionado));
        $("#interconsultaseleccionada").val(JSON.stringify(interconsultaseleccionada));
        $("#id_principal_selec").val(JSON.stringify(id_principal_selec));
        $("#evaluacionesseleccionadas").val(JSON.stringify(evaluacionesseleccionadas));
    });
           
    //======================================================================================


    $(document).on('click', '.btn-pdf.sin-firma', function(e) {
        e.preventDefault();

        const idPrest = $(this).data('id_prest');
        const idPrincipal = $(this).data('id');
        const tipo_doc_hidden  = $(this).data('tipo-doc');
        const id_evolucion = $(this).data('id_evol');

        $('#id_prestacion_hidden').val(idPrest);
        $('#id_principal_hidden').val(idPrincipal);
        $('#tipo_doc_hidden').val(tipo_doc_hidden);
        $('#id_evolucion_hidden').val(id_evolucion);

        // Interconsulta-respuesta o documento derivado de ella (id_interconsulta > 0 en la fila)
        const $chkFila = $(this).closest('tr').find('input.chkFirmar').first();
        const idInterconsultaFila = parseInt($chkFila.data('id-interconsulta')) || 0;
        let idInterconsultaRegreso = 0;
        if (String(tipo_doc_hidden || '').toUpperCase() === 'INTERCONSULTA-RESPUESTA') {
            idInterconsultaRegreso = idPrincipal;
        } else if (idInterconsultaFila > 0) {
            idInterconsultaRegreso = idInterconsultaFila;
        }
        $('#id_interconsulta_hidden').val(idInterconsultaRegreso || '');
    });

    let idPrestacionModal = null; 
    let checkboxEstadoPrevioModal = false;

    $(document).on('click', '.btn-pdf', function(e){
        e.preventDefault();

        const idPrestacion = $(this).data('id_prest'); 
        const $row = $(this).closest('tr'); 
        const $checkbox = $row.find('input.chkFirmar[data-id-prestacion="' + idPrestacion + '"]');

        if($checkbox.length){
            checkboxEstadoPrevioModal = $checkbox.is(':checked');
            idPrestacionModal = idPrestacion; 
            if(!checkboxEstadoPrevioModal){
                $checkbox.prop('checked', true).trigger('change');
            }
        }

        const url = $(this).data('url') || '';
        $('#pdfFrame').attr('src', url);
        $('#pdfModal').modal('show');
    });

    $('#pdfModal').on('hidden.bs.modal', function(){
        if(idPrestacionModal){
            const $checkbox = $('input.chkFirmar[data-id-prestacion="' + idPrestacionModal + '"]');
            if($checkbox.length && !checkboxEstadoPrevioModal){
                $checkbox.prop('checked', false).trigger('change');
            }
            idPrestacionModal = null;
        }
        $('#pdfFrame').attr('src', '');
    });

    $(document).on('click', '#btnfirmar, #btnFirmarModal, #btnFirmar', function(e) {
        e.preventDefault();

        const idBoton = $(this).attr('id');
        let seleccionadosArray = [];

        if (idBoton === 'btnFirmarModal') {
            let id_principal = $('#id_prestacion_hidden').val();

            if (!id_principal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontró el id_principal asociado al modal.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            seleccionadosArray = [String(id_principal)];

        } else {
            prestacionesSeleccionadas = [];
            tipodocseleccionado = [];
            tipoantencseleccionado = [];
            id_principal_selec = [];
            evaluacionesseleccionadas = [];
            interconsultaseleccionada = [];

            $('.chkFirmar:checked').each(function() {
                const $c = $(this);
                const idP = String($c.data('id-prestacion'));
                prestacionesSeleccionadas.push(idP);
                tipodocseleccionado.push(($c.data('tipo-doc') || '').toUpperCase());
                tipoantencseleccionado.push($c.data('tipo-at') || '');
                id_principal_selec.push($c.data('id_principal') || '');
                evaluacionesseleccionadas.push($c.data('id_evol') || '');
                interconsultaseleccionada.push($c.data('id-interconsulta') || 0);
            });

            $("#prestacionesSeleccionadas").val(JSON.stringify(prestacionesSeleccionadas));
            $("#tipodocseleccionado").val(JSON.stringify(tipodocseleccionado));
            $("#tipoantencseleccionado").val(JSON.stringify(tipoantencseleccionado));
            $("#id_principal_selec").val(JSON.stringify(id_principal_selec));
            $("#evaluacionesseleccionadas").val(JSON.stringify(evaluacionesseleccionadas));
            $("#interconsultaseleccionada").val(JSON.stringify(interconsultaseleccionada));

            seleccionadosArray = prestacionesSeleccionadas.map(e => e.split('_')[0]);

            if (!seleccionadosArray || seleccionadosArray.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debe seleccionar al menos una prestación para firmar.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
        }

        $("#prestacionesSeleccionadas").val(JSON.stringify(seleccionadosArray));

        if (idBoton === 'btnFirmarModal' && seleccionadosArray.length === 1) {
            // $('#id_prestacion').val(seleccionadosArray[0]);
            $('#id_principal').val(seleccionadosArray[0]);
            $('#btnFirmarModal').data('id-prestacion', seleccionadosArray[0]);
        }

        firmar();
    });

    $('#tbldocsinfirmar').on('draw.dt', function () {
        prestacionesSeleccionadas = [];
        $('#prestacionesSeleccionadas').val('[]');
        $('.chkFirmar:checked').prop('checked', false).trigger('change');
        $('#chkAllFirmar').prop('checked', false);
    });

//============================================ BTN REGRESAR A LA ATENCIÓN ==============================================
    $(document).on('click', '#btnRegresar', function(e) {
        e.preventDefault();

        let id_prestacion = $('#id_prestacion_hidden').val();
        let id_principal = $('#id_principal_hidden').val();
        let id_evolucion = $('#id_evolucion_hidden').val();
        let id_user = {{ auth()->user()->id }};
        let tipo_doc = $('#tipo_doc_hidden').val();
        let id_interconsulta_regreso = parseInt($('#id_interconsulta_hidden').val()) || 0;

        if (id_interconsulta_regreso > 0) {
            $.get(`/datos_regreso_interconsulta_rpta/${id_interconsulta_regreso}`, function(resp) {
                if (!resp || !resp.ok) {
                    alert('No se pudo obtener los datos de la interconsulta.');
                    return;
                }
                window.location.href = `/ver_atencion_interconsulta_rptas/${resp.id_prestacion}/${resp.id_interconsulta}/${resp.id_evaluacion}/${resp.id_tipo_atencion}`;
            }).fail(function() {
                alert('No se pudo obtener los datos de la interconsulta.');
            });
            return;
        }

        const url = (tipo_doc === "EVOLUCION") ? `/obtener_tipo_atencion_prestacion/${id_principal}/${tipo_doc}`: `/obtener_tipo_atencion_prestacion/${id_prestacion}/${tipo_doc}`;

        $.ajax({
            //url: `/obtener_tipo_atencion_prestacion/${id_prestacion}/${tipo_doc}`,
            url: url,
            type: 'GET',
            success: function(response) {
                let tipo = parseInt(response.tipo);
                let url = '';

                if (tipo_doc == "ALTA EMERGENCIA") {
                    url = `/alta_medica_ver/${id_user}/${id_principal}`;
                }else if(tipo_doc == "ALTA HOSPITALIZACION"){
                    url = `/hospitalizacion_ver_alta_medica/${id_user}/${id_principal}`;
                }else if(tipo_doc == "INFORME RADIOLOGIA" || tipo_doc =="INFORME RADIOLOGIA ADICIONAL(ADENDA)"){
                    url = `/all_radiologia_prescritas_by_ipress`;
                }else {
                    switch (tipo) {
                        case 1: // Ambulatorio
                            url = `/ver_prestacion/${id_user}/${id_prestacion}`;
                            break;
                        case 2: // Emergencia
                            if (id_evolucion != 0) {
                                const id_ev = (tipo_doc === "EVOLUCION") ? id_principal : id_evolucion;
                                url = `/ver_evaluacion/${id_user}/${id_ev}`;
                            }else{
                                url = `/ver_prestacion_emergencias/${id_user}/${id_prestacion}`;
                            }
                            break;
                        case 3: // Hospitalización
                            if (id_evolucion != 0) {
                                const id_ev = (tipo_doc === "EVOLUCION") ? id_principal : id_evolucion;
                                url = `/ver_evaluacion_hospitalizacion/${id_user}/${id_ev}`;
                            } else {
                                url = `/ver_prestacion_hospitalizacion/${id_user}/${id_prestacion}`;
                            }
                            break;
                        default:
                            alert('Tipo de atención no reconocido.');
                            return;
                    }
                }

                window.location.href = url;
            },
            error: function() {
                alert('No se pudo obtener el tipo de atención.');
            }
        });
    });

// ======================================================= FIRMA ===========================================================
    var documentName_ = null;
    
    window.addEventListener('getArguments', async function(e) {
        await ObtieneArgumentosParaFirmaDesdeLaWeb();
    });

    async function getArguments() {
        arg = document.getElementById("argumentos").value;
        await dispatchEventClient('sendArguments', arg);
    }

    window.addEventListener('invokerOk', function(e) {
        if (firmaContexto === 'enfermeria') {
            MiFuncionOkWebNotasEnfermeria();
        } else {
            MiFuncionOkWeb();
        }
    });

    window.addEventListener('invokerCancel', function(e) {
        console.error("Evento invokerCancel recibido:", e);
        MiFuncionCancel();
    });

    window.addEventListener('invokerError', function(e) {
        console.error("Evento invokerError recibido:", e);
    });

    async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
        try {
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var documentName = $('#doc_firmar').val();
            var opt_firma = $('#opt_firma').val();
            var entidad = "<?=Auth::user()->dni?>";
            return new Promise((resolve, reject) => {
                $.post("{{route('postArguments')}}", {
                    _token: CSRF_TOKEN,
                    type: "W",
                    documentName: documentName,
                    opt_firma: opt_firma,
                    entidad: entidad
                })
                .done(function(data, status, xhr) {
                    if (document.getElementById("argumentos")) {
                        document.getElementById("argumentos").value = data;
                    } else {
                        console.warn("No se encontró el elemento input#argumentos en el DOM");
                    }
                    getArguments();
                    resolve(data);
                })
                .fail(function(xhr, status, error) {
                    console.error("Error en la solicitud POST a postArguments:");
                    reject(error);
                });
            });

        } catch (err) {
            console.error("ocurrió error en ObtieneArgumentosParaFirmaDesdeLaWeb():", err);
        }
    }
    async function MiFuncionOkWeb() {
        const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        const prestSel = $('#prestacionesSeleccionadas').val();
        const docseleccionados = $('#tipodocseleccionado').val();
        const id_princ_selecc = $('#id_principal_selec').val();

        function safeParse(value) {
            try {
                return JSON.parse(value);
            } catch {
                return value; 
            }
        }

        const prestaciones = safeParse(prestSel);
        const tipo_doc = safeParse(docseleccionados);
        const id_principal = safeParse(id_princ_selecc);

        Swal.fire({
            icon: 'success',
            title: 'Firmando documentos',
            html: 'Espere un momento...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            for (const id_resol of prestaciones) {
                let documentosPorFirmar = [];

                const tipoDocFila = $(`input[type="checkbox"][data-id_principal='${id_resol}']:checked`).data('tipo-doc');
                const tipoDocUpper = (tipoDocFila || "").toString().toUpperCase();
                const esEvolucionOAlta =
                    tipoDocUpper.includes("EVOLUCION") ||
                    tipoDocUpper.includes("ALTA EMERGENCIA")||
                    tipoDocUpper.includes("ALTA HOSPITALIZACION");

                if (esEvolucionOAlta) {
                    documentosPorFirmar = $(`input[type="checkbox"][data-id_principal='${id_resol}']:checked`)
                        .map(function () {
                            return {
                                doc: $(this).closest('tr')
                                    .find(`input[type="hidden"][data-id-principal='${id_resol}']`).val(),
                                id_principal: $(this).data('id_principal'),
                            };
                        }).get();
                } else {
                    documentosPorFirmar = $(`input[type="checkbox"][data-id-prestacion='${id_resol}']:checked`)
                        .map(function () {
                            return {
                                doc: $(this).closest('tr').find(`input[type="hidden"][data-id-prestacion='${id_resol}']`).val(),
                                id_principal: $(this).data('id_principal'),
                            };
                        }).get();
                }

                if (documentosPorFirmar.length === 0) continue;

                for (const item of documentosPorFirmar) {
                    const doc = item.doc;
                    const idPrincipal = item.id_principal;
                    try {
                        const esEspecial = ["EVOLUCION", "ALTA EMERGENCIA"].includes(doc);
                        const url = `/obtener_tipo_atencion_prestacion/${esEspecial ? idPrincipal : id_resol}/${doc}`;
                        const response = await $.ajax({ url, type: "GET" });
                        const tipo = parseInt(response.tipo);

                        let id_tipo_envio = '';
                        if (doc === 'ATENCIÓN') id_tipo_envio = (tipo === 3) ? 'hospitalizacion' : 'prestacion';
                        else if (doc === 'EVOLUCION') id_tipo_envio = (tipo === 3) ? 'evaluacion' : (tipo === 2) ? 'evolucion' : null;
                        else if (doc === 'ORDEN DE DIAGNOSTICO POR IMÁGENES') id_tipo_envio = 'radiologia';
                        else if (doc === 'ORDEN DE LABORATORIO') id_tipo_envio = 'laboratorio';
                        else if (doc === 'RECETA') id_tipo_envio = 'receta';
                        else if (doc === 'INTERCONSULTA') id_tipo_envio = 'interconsulta';
                        else if (doc === 'ALTA EMERGENCIA') id_tipo_envio = 'alta_medica';
                        else if (doc === 'ALTA HOSPITALIZACION') id_tipo_envio = 'alta_hospitalizacion';
                        else if (doc === 'INFORME RADIOLOGIA') id_tipo_envio = 'imagenologia';
                        else if (doc === 'INFORME RADIOLOGIA ADICIONAL(ADENDA)') id_tipo_envio = 'imagenologia_adicional';
                        else if (doc === 'INTERCONSULTA-RESPUESTA') id_tipo_envio = 'interconsulta_rpta';
                        else id_tipo_envio = 'prestacion';

                        const check = await $.ajax({
                            url: `/verificar_firma_doc/${id_tipo_envio}/${id_resol}/${idPrincipal}`,
                            type: 'GET'
                        });

                        if (check.flag === 1) continue;

                        await $.ajax({
                            url: "{{ route('actualiza_prestacion_mod_docsinfirmar') }}",
                            type: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                                id_tipo: id_tipo_envio,
                                id_resol: id_resol,
                                id_principal: idPrincipal
                            }
                        });

                    } catch (errItem) {
                        console.error(`Error procesando documento ${doc} de prestación ${id_resol}:`, errItem);
                        continue;
                    }
                }
            }

            Swal.close(); 
            location.href = '/all_docs_sin_firmar';

        } catch (error) {
            Swal.close();
            console.error("Error general en MiFuncionOkWeb:", error);
            alert("No se pudo procesar la solicitud.");
        }
    }

    function MiFuncionCancel() {
        Swal.fire({
            icon: 'error',
            title: 'Proceso cancelado',
            text: 'El proceso de firma digital fue cancelado.',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }

    function firmar() {
        firmaContexto = 'principal';
        Swal.fire({
            icon: 'success',
            title: 'Procesando...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => {
            Swal.showLoading();
            }
        });
        $.ajax({
            url: "<?php echo url()?>/genera_csv_docsinfirmar",
            type: "post",
            data: $("#frmBloque").serialize(),
            dataType: 'json'
        })
        .done(function(resultado) {
            if (resultado.sw) {
                var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
                $("#doc_firmar").val(`${resultado.csv}.7z`);
                $("#opt_firma").val(`${resultado.opt}`);        
                $("#flagServicioFirma").val("1");        
                initInvoker('W');
            }
            $('#btnLiquidacionFirmarPdf').attr("disabled", false);
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Se esta cargando el modulo de refirma.',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false, 
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });
        })
        .fail(function(jqXHR, textStatus) {
            console.log("Request failed: " + textStatus)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: textStatus
            });
        })
        .always(function() {
            //bootbox.hideAll();
        });
    }

//====================================================== REPORTE DE ALTAS EMERGENCIA DETALLADO ============================================
$(document).ready(function () {
    $('#btnExportarReporteaedet').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fa"]').val();
        let fechaHasta = $('input[name="fecha_fin_fa"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }
        let departamento = $('#division_alta').val();
        let porf_salud = $('#prof_salud_alta').val();
        let formData = $('#frmBloquealtasemg').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_altasemg_pendientes_det',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte de altas emergencia se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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

//====================================================== REPORTE DE ALTAS EMERGENCIA PENDIENTES ============================================
$(document).ready(function () {
    $('#btnExportarReporteae').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fa"]').val();
        let fechaHasta = $('input[name="fecha_fin_fa"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }
        let departamento = $('#division_alta').val();
        let porf_salud = $('#prof_salud_alta').val();
        let formData = $('#frmBloquealtasemg').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_altasemg_pendientes',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte de altas emergencia se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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

//====================================================== REPORTE DE ALTAS HOSPITALIZACIÓN PENDIENTES ============================================
$(document).ready(function () {
    $('#btnExportarReporteah').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fah"]').val();
        let fechaHasta = $('input[name="fecha_fin_fah"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }
        let departamento = $('#division_alta_hsp').val();
        let porf_salud = $('#prof_salud_alta_hsp').val();
        let formData = $('#frmBloquealtashsp').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_altashsp_pendientes',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte de altas hospitalización se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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
//====================================================== REPORTE DETALLADO DE ALTAS HOSPITALIZACIÓN PENDIENTES ============================================
$(document).ready(function () {
    $('#btnExportarReporteahdet').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fah"]').val();
        let fechaHasta = $('input[name="fecha_fin_fah"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }
        let departamento = $('#division_alta_hsp').val();
        let porf_salud = $('#prof_salud_alta_hsp').val();
        let formData = $('#frmBloquealtashsp').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_altashsp_pendientes_det',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte de altas hospitalización se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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
//====================================================== REPORTE DE FIRMAS PENDIENTES ============================================
$(document).ready(function () {
    $('#btnExportarReporte').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fp"]').val();
        let fechaHasta = $('input[name="fecha_fin_fp"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return; 
        }
        let departamento = $('#division').val();
        let porf_salud = $('#prof_salud').val();
        let formData = $('#frmBloquefirmas').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_firmas_pendientes',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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
//====================================================== REPORTE DE FIRMAS PENDIENTES ENFERMERÍA ============================================
$(document).ready(function () {
    $('#btnExportarReporteEnf').click(function () {

        let fechaDesde = $('input[name="fecha_ini_fpe"]').val();
        let fechaHasta = $('input[name="fecha_fin_fpe"]').val();
        if (!fechaDesde || !fechaHasta || fechaHasta === "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes completar las fechas para poder generar el reporte.',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        let formData = $('#frmBloquefirmasenf').serialize();

        Swal.fire({
            html: '<strong style ="font-size: 30px">Generando Reporte...</strong><br>Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/exportar_reporte_firmas_pendientes_enfermeria',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.excelNameResponse) {
                    let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                    let link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = response.excelNameResponse;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.hideLoading();

                    Swal.fire({
                        icon: 'success',
                        title: 'El reporte se ha generado correctamente!',
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            $('.blur-background').remove();
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

    $('#division_enf').on('change', function () {
        cargarDepartamentos($(this).val(), '#departamento_enf');
    });

    $("#prof_salud_enf").select2({
        width: '100%',
        dropdownParent: $('#modalReporteFirmasEnfermeria')
    }).next('.select2-container').addClass('text-12');
});
//////////////////////////////////////////////////////////////////////////////////////////// DEPARTAMENTOS X DIVISIONES
function cargarDepartamentos(idDivision, targetSelector, selected = null) {
    const $target = $(targetSelector);
    $target.html('<option value="">Cargando...</option>');
    if (!idDivision) {
        $target.html('<option value="">-- Seleccionar --</option>');
        return;
    }
    $.ajax({
        url: '/obtener-departamentos',
        type: 'GET',
        data: { id_division: idDivision },
        success: function (response) {
            let html = '<option value="">-- Seleccionar --</option>';
            $.each(response, function (i, item) {
                html += `<option value="${item.id}">${item.nombre}</option>`;
            });
            $target.html(html);
            if (selected) {
                $target.val(selected);
            }
        }
    });
}

////////////////////////////////////////////////////////////////////////////
// Select principal
$('#division').change(function () {
    cargarDepartamentos($(this).val(), '#departamento');
});

// Alta emergencia
$('#tab-altase_pendientes-tab').on('shown.bs.tab', function () {
    cargarDepartamentos($('#division_alta').val(), '#departamento_alta');
});
$('#division_alta').on('change', function () {
    cargarDepartamentos($(this).val(), '#departamento_alta');
});

// Alta hospi
$('#tab-altash_pendientes-tab').on('shown.bs.tab', function () {
    cargarDepartamentos($('#division_alta_hsp').val(), '#departamento_alta_hsp');
});
$('#division_alta_hsp').on('change', function () {
    cargarDepartamentos($(this).val(), '#departamento_alta_hsp');
});
//////////////////////////////////////////////////////////////////////////////////////////////// LISTAR Y DATATABLE CANTIDAD FIRMAS PENDIENTES USUARIOS CON ACCESO
var oTable3;
var oTable4;
//****************************************************************** Comentado para que no muestre automáticamente ni bien muestra el modulo de firmas pendientes reporte ***********************************************
// $(document).ready(function () {
    
//     $('#btnBuscarfp').on('click', function () {
//         oTable3.ajax.reload();
//         datatablenewFP();
//     });

//     $('#fecha_ini_fp, #fecha_fin_fp').on('change', function () {
//         oTable3.ajax.reload();
//     });

//     $('#division').on('change', function () {
//         oTable3.ajax.reload();
//     });

//     $('#departamento').on('change', function () {
//         oTable3.ajax.reload();
//     });

//     $('#prof_salud').on('change', function () {
//         oTable3.ajax.reload();
//     });

//     $('#tblfirmpend').on('draw.dt', function () {
//         $(this).find('tbody .dataTables_empty').text('No se encontraron resultados');
//     });

// });
var oTable5 = null;

$(document).ready(function () {
    $('#btnBuscaraph').on('click', function () {

        let $btn = $(this);
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i><strong> Buscando... </strong>');

        if (!$.fn.DataTable.isDataTable('#tblalthsppend')) {
            datatablenewAPHSP();
        } else {
            oTable5.ajax.reload();
        }

        $('#tblalthsppend').one('draw.dt', function () {
            $btn.prop('disabled', false);
            $btn.html('<i class="fa fa-search"></i><strong> Buscar </strong>');
        });

    });
});


function datatablenewAPHSP() {
    oTable5 = $('#tblalthsppend').DataTable({
        processing: false,
        serverSide: true,
        ajax: {
            url: "/listar_altas_pendientes_hsp_ajax",
            type: "POST",

            data: function (d) {
                let prof_salud   = $('#prof_salud_alta_hsp').val().trim();
                let division     = $('#division_alta_hsp').val();
                let departamento = $('#departamento_alta_hsp').val();

                d.NumeroPagina     = d.start / d.length + 1;
                d.NumeroRegistros  = d.length;
                d.fecha_ini        = $('#fecha_ini_fah').val();
                d.fecha_fin        = $('#fecha_fin_fah').val();
                d.prof_salud       = prof_salud !== '' ? prof_salud : null;
                d.division         = division != 0 ? division : null;
                d.departamento     = departamento != 0 ? departamento : null;
                d._token           = "{{ csrf_token() }}";
                //console.log(d);
            },

            beforeSend: function () {
                $("#spinnerOverlayaphsp").fadeIn(200);
            },
            complete: function () {
                $("#spinnerOverlayaphsp").fadeOut(200);
            },
            error: function (xhr) {
                $("#spinnerOverlayaphsp").fadeOut(200);
                console.log(xhr.responseText);
            }
        },
        language: {
            url: "/js/Spanish.json"
        },
        autoWidth: false,
        bLengthChange: true,
        pageLength: 20,
        lengthMenu: [
            [20, 50, 100, 200, 60000],
            [20, 50, 100, 200, "Todos"]
        ],
        searching: false,
        ordering: true,
        info: true,
        dom: '<"top">rt<"bottom"flpi><"clear">',
        columns: [
            { data: null },
            { data: "medico" },
            { data: "unidad_pertenece" },
            { data: "sub_unidad_pertenece" },
            { data: "sub_unidad_atencion" },
            { data: "total_altas_pendientes" }
        ],
        columnDefs: [
            {
                targets: 0,
                //orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: 1,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 2,
                render: function (data) {

                    return data ? data : '';
                }
            },
            {
                targets: 3,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 4,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 5,
                className: 'text-center',
                render: function (data) {
                    return '<strong>' + (data ? data : '') + '</strong>';
                }
            }
        ]
    });
}
$("#prof_salud_alta_hsp").select2({
    width: '100%', 
  }).next('.select2-container')
    .addClass('text-12') ;
//**********************************************************************************************************************************************************************************************************************
var oTable4 = null;

$(document).ready(function () {
    $('#btnBuscarap').on('click', function () {

        let $btn = $(this);
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i><strong> Buscando... </strong>');

        if (!$.fn.DataTable.isDataTable('#tblaltpend')) {
            datatablenewAP();
        } else {
            oTable4.ajax.reload();
        }

        $('#tblaltpend').one('draw.dt', function () {
            $btn.prop('disabled', false);
            $btn.html('<i class="fa fa-search"></i><strong> Buscar </strong>');
        });

    });
});


function datatablenewAP() {
    oTable4 = $('#tblaltpend').DataTable({
        processing: false,
        serverSide: true,
        ajax: {
            url: "/listar_altas_pendientes_ajax",
            type: "POST",

            data: function (d) {
                let prof_salud   = $('#prof_salud_alta').val().trim();
                let division     = $('#division_alta').val();
                let departamento = $('#departamento_alta').val();

                d.NumeroPagina     = d.start / d.length + 1;
                d.NumeroRegistros  = d.length;
                d.fecha_ini        = $('#fecha_ini_fa').val();
                d.fecha_fin        = $('#fecha_fin_fa').val();
                d.prof_salud       = prof_salud !== '' ? prof_salud : null;
                d.division         = division != 0 ? division : null;
                d.departamento     = departamento != 0 ? departamento : null;
                d._token           = "{{ csrf_token() }}";
                //console.log(d);
            },

            beforeSend: function () {
                $("#spinnerOverlayap").fadeIn(200);
            },
            complete: function () {
                $("#spinnerOverlayap").fadeOut(200);
            },
            error: function (xhr) {
                $("#spinnerOverlayap").fadeOut(200);
                console.log(xhr.responseText);
            }
        },
        language: {
            url: "/js/Spanish.json"
        },
        autoWidth: false,
        bLengthChange: true,
        pageLength: 20,
        lengthMenu: [
            [20, 50, 100, 200, 60000],
            [20, 50, 100, 200, "Todos"]
        ],
        searching: false,
        ordering: true,
        info: true,
        dom: '<"top">rt<"bottom"flpi><"clear">',
        columns: [
            { data: null },
            { data: "medico" },
            { data: "unidad_pertenece" },
            { data: "sub_unidad_pertenece" },
            { data: "sub_unidad_atencion" },
            { data: "total_altas_pendientes" }
        ],
        columnDefs: [
            {
                targets: 0,
                //orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: 1,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 2,
                render: function (data) {

                    return data ? data : '';
                }
            },
            {
                targets: 3,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 4,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 5,
                className: 'text-center',
                render: function (data) {
                    return '<strong>' + (data ? data : '') + '</strong>';
                }
            }
        ]
    });
}
$("#prof_salud_alta").select2({
    width: '100%', 
  }).next('.select2-container')
    .addClass('text-12') ;
//**********************************************************************************************************************************************************************************************************************
var oTable3 = null;

$(document).ready(function () {
    $('#btnBuscarfp').on('click', function () {

        let $btn = $(this);
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i><strong> Buscando... </strong>');

        if (!$.fn.DataTable.isDataTable('#tblfirmpend')) {
            datatablenewFP();
        } else {
            oTable3.ajax.reload();
        }

        $('#tblfirmpend').one('draw.dt', function () {
            $btn.prop('disabled', false);
            $btn.html('<i class="fa fa-search"></i><strong> Buscar </strong>');
        });

    });
});


function datatablenewFP() {
    oTable3 = $('#tblfirmpend').DataTable({
        processing: false,
        serverSide: true,
        ajax: {
            url: "/listar_firm_pend_ajax",
            type: "POST",

            data: function (d) {
                let prof_salud   = $('#prof_salud').val().trim();
                let division     = $('#division').val();
                let departamento = $('#departamento').val();

                d.NumeroPagina     = d.start / d.length + 1;
                d.NumeroRegistros  = d.length;
                d.fecha_ini        = $('#fecha_ini_fp').val();
                d.fecha_fin        = $('#fecha_fin_fp').val();
                d.prof_salud       = prof_salud !== '' ? prof_salud : null;
                d.division         = division != 0 ? division : null;
                d.departamento     = departamento != 0 ? departamento : null;
                d._token           = "{{ csrf_token() }}";
                //console.log(d);
            },

            beforeSend: function () {
                $("#spinnerOverlayfp").fadeIn(200);
            },
            complete: function () {
                $("#spinnerOverlayfp").fadeOut(200);
            },
            error: function (xhr) {
                $("#spinnerOverlayfp").fadeOut(200);
                console.log(xhr.responseText);
            }
        },
        language: {
            url: "/js/Spanish.json"
        },
        autoWidth: false,
        bLengthChange: true,
        pageLength: 20,
        lengthMenu: [
            [20, 50, 100, 200, 60000],
            [20, 50, 100, 200, "Todos"]
        ],
        searching: false,
        ordering: true,
        info: true,
        dom: '<"top">rt<"bottom"flpi><"clear">',
        columns: [
            { data: null },
            { data: "medico" },
            { data: "estado_medico" },
            { data: "unidad_pertenece" },
            { data: "sub_unidad_pertenece" },
            { data: "sub_unidad_atencion" },
            { data: "total_pendientes_firmas" }
        ],
        columnDefs: [
            {
                targets: 0,
                //orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: 1,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 2,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 3,
                render: function (data) {

                    return data ? data : '';
                }
            },
            {
                targets: 4,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 5,
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 6,
                className: 'text-center',
                render: function (data) {
                    return '<strong>' + (data ? data : '') + '</strong>';
                }
            }
        ]
    });
}

  $("#prof_salud").select2({
    width: '100%', 
  }).next('.select2-container')
    .addClass('text-12') ;

//////////////////////////////////////////////////////////////////////////////////////////////// LISTAR Y DATATABLE FIRMAS PENDIENTES ENFERMERÍA
var oTable6;
$(document).ready(function () {
    $('#btnBuscarfpEnf').on('click', function () {

        let $btn = $(this);
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i><strong> Buscando... </strong>');

        if (!$.fn.DataTable.isDataTable('#tblfirmpendenf')) {
            datatablenewFPEnf();
        } else {
            oTable6.ajax.reload();
        }

        $('#tblfirmpendenf').one('draw.dt', function () {
            $btn.prop('disabled', false);
            $btn.html('<i class="fa fa-search"></i><strong> Buscar </strong>');
        });

    });
});

function datatablenewFPEnf() {
    oTable6 = $('#tblfirmpendenf').DataTable({
        processing: false,
        serverSide: true,
        ajax: {
            url: "/listar_firm_pend_enfermeria_ajax",
            type: "POST",

            data: function (d) {
                let prof_salud   = $('#prof_salud_enf').val().trim();
                let division     = $('#division_enf').val();
                let departamento = $('#departamento_enf').val();

                d.NumeroPagina     = d.start / d.length + 1;
                d.NumeroRegistros  = d.length;
                d.fecha_ini        = $('#fecha_ini_fpe').val();
                d.fecha_fin        = $('#fecha_fin_fpe').val();
                d.prof_salud       = prof_salud !== '' ? prof_salud : null;
                d.division         = division != 0 ? division : null;
                d.departamento     = departamento != 0 ? departamento : null;
                d._token           = "{{ csrf_token() }}";
            },

            beforeSend: function () {
                $("#spinnerOverlayfpenf").fadeIn(200);
            },
            complete: function () {
                $("#spinnerOverlayfpenf").fadeOut(200);
            },
            error: function (xhr) {
                $("#spinnerOverlayfpenf").fadeOut(200);
                console.log(xhr.responseText);
            }
        },
        language: {
            url: "/js/Spanish.json"
        },
        autoWidth: false,
        bLengthChange: true,
        pageLength: 20,
        lengthMenu: [
            [20, 50, 100, 200, 60000],
            [20, 50, 100, 200, "Todos"]
        ],
        searching: false,
        ordering: true,
        info: true,
        dom: '<"top">rt<"bottom"flpi><"clear">',
        columns: [
            { data: null },
            { data: "enfermero" },
            { data: "estado_enfermero" },
            { data: "unidad_pertenece" },
            { data: "sub_unidad_pertenece" },
            { data: "sub_unidad_atencion" },
            { data: "total_pendientes_firmas" }
        ],
        columnDefs: [
            {
                targets: 0,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: [1, 2, 3, 4, 5],
                render: function (data) {
                    return data ? data : '';
                }
            },
            {
                targets: 6,
                className: 'text-center',
                render: function (data) {
                    return '<strong>' + (data ? data : '') + '</strong>';
                }
            }
        ]
    });
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
    $('#tab-altase_pendientes-tab').parent().hide();
    $('#tab-altash_pendientes-tab').parent().hide();

    $('#division').on('change', function () {
        const valor = $(this).val();
        // ALTAS EMERGENCIA PENDIENTES
        if (valor === '4') {
            $('#tab-altase_pendientes-tab').parent().show();
        } else {
            $('#tab-altase_pendientes-tab').parent().hide();

            if ($('#tab-altase_pendientes-tab').hasClass('active')) {
                $('.nav-link:first').tab('show');
            }
        }

        // TAB ALTAS H PENDIENTES
        if (valor !== '4' && valor > 0) {
            $('#tab-altash_pendientes-tab').parent().show();
        } else {
            $('#tab-altash_pendientes-tab').parent().hide();

            if ($('#tab-altash_pendientes-tab').hasClass('active')) {
                $('.nav-link:first').tab('show');
            }
        }
    });
});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
</script>

@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">MÓDULO DE ALERTA DE DOCUMENTOS SIN FIRMAR</h3>
    </div>
    <div class="panel-body">
      <ul class="nav nav-tabs" id="tabsDoc" role="tablist">
        <li class="nav-item active">
          <a class="nav-link active" id="tab-sinfirmar-tab" data-toggle="tab" href="#tab-sinfirmar" role="tab" aria-controls="tab-sinfirmar" aria-selected="true">
            <i class="fa fa-list"></i> DOCUMENTOS SIN FIRMAR
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-firmado-tab" data-toggle="tab" href="#tab-firmado" role="tab" aria-controls="tab-firmado" aria-selected="false">
            <i class="fa fa-file-text-o"></i> DOCUMENTOS FIRMADOS
          </a>
        </li>
        @if(Auth::user()->flag_firmas_pendientes == 1)
        <li class="nav-item">
          <a class="nav-link" id="tab-firmas_pendientes-tab" data-toggle="tab" href="#tab-firmas_pendientes" role="tab" aria-controls="tab-firmas_pendientes" aria-selected="false" style="background: #f0ad4e; color: #000000ff">
            <i class="fa fa-clock-o"></i><strong> REPORTE DE FIRMAS PENDIENTES</strong>
          </a>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" id="tab-altase_pendientes-tab" data-toggle="tab" href="#tab-altase_pendientes" role="tab" aria-controls="tab-altase_pendientes" aria-selected="false" /*style="background: #48C9B0; color: #000000ff"*/>
            <i class="fa fa-ambulance"></i> REPORTE DE ALTAS PENDIENTES
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-altash_pendientes-tab" data-toggle="tab" href="#tab-altash_pendientes" role="tab" aria-controls="tab-altash_pendientes" aria-selected="false" /*style="background: #4EC3F0; color: #000000ff"*/>
            <i class="fa fa-ambulance"></i> REPORTE DE ALTAS PENDIENTES
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-notasenfermeria-tab" data-toggle="tab" href="#tab-notasenfermeria" role="tab" aria-controls="tab-notasenfermeria" aria-selected="false" style="background: #8E44AD; color: #ffffff">
            <i class="fa fa-heartbeat"></i> FIRMAS ENFERMERÍA
          </a>
        </li>
      </ul>

      <div class="tab-content" id="tabsDocContent" style="margin-top:20px;">

        <!-- BÚSQUEDA -->
        <div class="tab-pane fade in active" id="tab-sinfirmar" role="tabpanel" aria-labelledby="tab-sinfirmar-tab">
          <form class="form-horizontal" method="post" id="frmBloque" action="{{ route('listar_docsinfirmar_ajax')}}">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" >
            <input type="hidden" value="<?php echo $id_medico ?>" name="id_medico" id="id_medico" >
            <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento" >
            <input type="hidden" value="" name="prestacionesSeleccionadas" id="prestacionesSeleccionadas">
            <input type="hidden" value="" name="id_principal_selec" id="id_principal_selec">
            <input type="hidden" value="" name="evaluacionesseleccionadas" id="evaluacionesseleccionadas">
            <input type="hidden" value="" name="tipoantencseleccionado" id="tipoantencseleccionado">
            <input type="hidden" value="" name="tipodocseleccionado" id="tipodocseleccionado">
            <input type="hidden" value="" name="interconsultaseleccionada" id="interconsultaseleccionada">
            <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">

            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nro. Documento</label>
                  <div class="input-group fecha-input">
                      <input type="text" class="form-control input-sm" placeholder="Ingrese el número..." id="dni_beneficiario" name="dni_beneficiario" maxlength="20" onkeypress="return soloNumeros(event)" value="<?php echo $dni_beneficiario ?>">
                      <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Desde</label>
                  <div class="input-group fecha-input">
                      <input class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fechaDesde?>" maxlength="10">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Hasta</label>
                  <div class="input-group fecha-input">
                      <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" value="<?php echo $fechaHasta?>" maxlength="10">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Estado</label>
                  <select class="form-control input-sm" id="estado_firma" name="estado_firma" disabled>
                      <option value="1" selected>Sin Firmar</option>
                  </select>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label for="">&nbsp;</label>
                  <button id="btnBuscar" class="btn btn-success input-sm btn-buscar-pequeno" type="button">
                      <i class="fa fa-search"></i> Buscar
                  </button>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  @if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0)
                      <label for="">&nbsp;</label>
                      <a class="btn btn-info input-sm btn-firmar-pequeno" id="btnfirmar">
                          <i class="fa fa-pencil-square-o"></i> Firmar
                      </a>
                  @endif
              </div>
            </div>
          </form>
          <br>

          <div class="table-responsive">
            <table id="tbldocsinfirmar" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PACIENTE</th>
                  <th style="text-align:center">ATENCIÓN</th>
                  <th style="text-align:center">DOCUMENTO POR FIRMAR</th>
                  <th style="text-align:center">FECHA FIRMA</th>
                  <th style="text-align:center">VER DOCUMENTO</th>
                  @if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0)
                    <th style="text-align:center">FIRMAR <input type="checkbox" id="chkAllFirmar" style="display:none;width:20px;height:20px;"></th>
                  @else
                    <th style="text-align:center">FIRMAR</th>
                  @endif
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <div id="spinnerOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
              <div style="position:relative;top:40%;">
                <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                  Cargando documentos sin firmar, por favor espere...
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- DOCUMENTOS FIRMADOS -->
        <div class="tab-pane fade" id="tab-firmado" role="tabpanel" aria-labelledby="tab-firmado-tab">
        <form class="form-horizontal" method="post" id="frmBloque" action="{{ route('listar_docsinfirmar_ajax')}}">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Desde</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_ini_firm" id="fecha_ini_firm" value="<?php echo $fechaDesdefirm?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Hasta</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_fin_firm" id="fecha_fin_firm" value="<?php echo $fechaHastafirm?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label for="">&nbsp;</label>
                  <button id="btnBuscarfirm" class="btn btn-success input-sm btn-buscar-pequeno" type="button">
                      <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
            </div>
        </form>
        <br>
          <div class="table-responsive">
            <table id="tblfirmados" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PACIENTE</th>
                  <th style="text-align:center">ATENCIÓN</th>
                  <th style="text-align:center">DOCUMENTO POR FIRMAR</th>
                  <th style="text-align:center">FECHA FIRMA</th>
                  <th style="text-align:center">PDF FIRMADO</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div id="spinnerOverlayfirm" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
              <div style="position:relative;top:40%;">
                <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                  Cargando documentos firmados, por favor espere...
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- FIRMAS PENDIENTES -->
        <div class="tab-pane fade" id="tab-firmas_pendientes" role="tabpanel" aria-labelledby="tab-firmas_pendientes-tab">
        <form class="form-horizontal" method="post" id="frmBloquefirmas" action="{{ route('listar_firm_pend_ajax')}}">
            <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento" >
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Desde</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_ini_fp" id="fecha_ini_fp" style="height: 34px;" value="<?php //echo $fechaDesdefp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Hasta</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_fin_fp" id="fecha_fin_fp" style="height: 34px;" value="<?php //echo $fechaHastafp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Division</label>
                    <div class="input-group fecha-input">
                        <select id="division" name="division" class="form-control" required="" style="font-size: 12px;">
                            <option value="0">-- Seleccionar --</option>
                            @foreach ($listar_divisiones as $item)
                                <option value="{{ $item->id }}">{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                            @endforeach
                        </select><span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Departamento</label>
                    <div class="input-group fecha-input">
                        <select id="departamento" name="departamento" class="form-control" required="" style="font-size: 12px;">
                            <option value="0">-- Seleccionar --</option>
                        </select>
                        <span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Profesional de Salud</label>
                    <div class="input-group fecha-input">
                        <select class="form-control input-sm" name="prof_salud" id="prof_salud" style="width: 100%; height: 34px !important;">
                            <option value="0">-Seleccionar-</option>
                            <?php foreach ($medicos as $row) : ?>
                                <option value="<?php echo $row->id ?>">
                                    <?php echo $row->paterno ?> <?php echo $row->materno ?>, <?php echo $row->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select><span class="input-group-addon"><i class="fa fa-user-md"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnExportarReporte" class="btn btn-info input-sm btn-firmar-pequeno" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte </strong></button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnBuscarfp" class="btn btn-success input-sm btn-buscar-pequeno" type="button"><i class="fa fa-search"></i><strong> Buscar </strong></button>
                </div>
            </div>
        </form>
        <br>
          <div class="table-responsive">
            <table id="tblfirmpend" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PROFESIONAL DE LA SALUD</th>
                  <th style="text-align:center">ESTADO PROF. SALUD</th>
                  <th style="text-align:center">DIVISIÓN</th>
                  <th style="text-align:center">DEPARTAMENTO</th>
                  <th style="text-align:center">ORIGEN DE ATENCION</th>
                  <th style="text-align:center">FIRMAS PENDIENTES</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div id="spinnerOverlayfp" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
                <div style="position:relative;top:40%;">
                    <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                    <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                        Cargando datos, por favor espere...
                    </p>
                </div>
            </div>
          </div>
        </div>

        <!-- ALTAS PENDIENTES EMERGENCIA -->
        <div class="tab-pane fade" id="tab-altase_pendientes" role="tabpanel" aria-labelledby="tab-altase_pendientes-tab">
        <form class="form-horizontal" method="post" id="frmBloquealtasemg" action="{{ route('listar_altas_pendientes_ajax')}}">
            <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento" >
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Desde</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_ini_fa" id="fecha_ini_fa" style="height: 34px;" value="<?php //echo $fechaDesdefp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Hasta</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_fin_fa" id="fecha_fin_fa" style="height: 34px;" value="<?php //echo $fechaHastafp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Division</label>
                    <div class="input-group fecha-input">
                        <select id="division_alta" name="division_alta" class="form-control" required style="font-size: 12px;" disabled>
                            <option value="0">-- Seleccionar --</option>
                            @foreach ($listar_divisiones as $item)
                                <option value="{{ $item->id }}" {{ $item->id == 4 ? 'selected' : '' }}>{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="division_alta" value="4">
                        <span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Departamento</label>
                    <div class="input-group fecha-input">
                        <select id="departamento_alta" name="departamento_alta" class="form-control" required="" style="font-size: 12px;">
                            <option value="0">-- Seleccionar --</option>
                        </select>
                        <span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Profesional de Salud</label>
                    <div class="input-group fecha-input">
                        <select class="form-control input-sm" name="prof_salud_alta" id="prof_salud_alta" style="width: 100%; height: 34px !important;">
                            <option value="0">-Seleccionar-</option>
                            <?php foreach ($medico_emergencia as $row) : ?>
                                <option value="<?php echo $row->id ?>">
                                    <?php echo $row->paterno ?> <?php echo $row->materno ?>, <?php echo $row->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select><span class="input-group-addon"><i class="fa fa-user-md"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnExportarReporteaedet" class="btn btn-info input-sm btn-exp-hsp" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte DETALLADO</strong></button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnExportarReporteae" class="btn btn-info input-sm btn-exp-emg" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte GENERAL </strong></button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnBuscarap" class="btn btn-success input-sm btn-buscar-pequeno" type="button"><i class="fa fa-search"></i><strong> Buscar </strong></button>
                </div>
            </div>
        </form>
        <br>
          <div class="table-responsive">
            <table id="tblaltpend" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PROFESIONAL DE LA SALUD</th>
                  <th style="text-align:center">DIVISIÓN</th>
                  <th style="text-align:center">DEPARTAMENTO</th>
                  <th style="text-align:center">ORIGEN ATENCIÓN</th>
                  <th style="text-align:center">ALTAS PENDIENTES</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div id="spinnerOverlayap" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
                <div style="position:relative;top:40%;">
                    <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                    <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                        Cargando datos, por favor espere...
                    </p>
                </div>
            </div>
          </div>
      </div>

      <!-- ALTAS PENDIENTES HOSPITALIZACION -->
        <div class="tab-pane fade" id="tab-altash_pendientes" role="tabpanel" aria-labelledby="tab-altash_pendientes-tab">
        <form class="form-horizontal" method="post" id="frmBloquealtashsp" action="{{ route('listar_altas_pendientes_hsp_ajax')}}">
            <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento" >
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Desde</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_ini_fah" id="fecha_ini_fah" style="height: 34px;" value="<?php //echo $fechaDesdefp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Fecha Hasta</label>
                    <div class="input-group fecha-input">
                        <input class="form-control input-sm" type="text" name="fecha_fin_fah" id="fecha_fin_fah" style="height: 34px;" value="<?php //echo $fechaHastafp?>" maxlength="10">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Division</label>
                    <div class="input-group fecha-input">
                        <select id="division_alta_hsp" name="division_alta_hsp" class="form-control" required style="font-size: 12px;">
                            <option value="0">-- Seleccionar --</option>
                            @foreach ($listar_divisiones as $item)
                                <option value="{{ $item->id }}">{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Departamento</label>
                    <div class="input-group fecha-input">
                        <select id="departamento_alta_hsp" name="departamento_alta_hsp" class="form-control" required="" style="font-size: 12px;">
                            <option value="0">-- Seleccionar --</option>
                        </select>
                        <span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Profesional de Salud</label>
                    <div class="input-group fecha-input">
                        <select class="form-control input-sm" name="prof_salud_alta_hsp" id="prof_salud_alta_hsp" style="width: 100%; height: 34px !important;">
                            <option value="0">-Seleccionar-</option>
                            <?php foreach ($medicos as $row) : ?>
                                <option value="<?php echo $row->id ?>">
                                    <?php echo $row->paterno ?> <?php echo $row->materno ?>, <?php echo $row->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select><span class="input-group-addon"><i class="fa fa-user-md"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnExportarReporteahdet" class="btn btn-info input-sm btn-exp-emg" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte DETALLADO</strong></button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnExportarReporteah" class="btn btn-info input-sm btn-exp-hsp" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte GENERAL </strong></button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                    <label for="">&nbsp;</label>
                    <button id="btnBuscaraph" class="btn btn-success input-sm btn-buscar-pequeno" type="button"><i class="fa fa-search"></i><strong> Buscar </strong></button>
                </div>
            </div>
        </form>
        <br>
          <div class="table-responsive">
            <table id="tblalthsppend" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PROFESIONAL DE LA SALUD</th>
                  <th style="text-align:center">DIVISIÓN</th>
                  <th style="text-align:center">DEPARTAMENTO</th>
                  <th style="text-align:center">ORIGEN ATENCIÓN</th>
                  <th style="text-align:center">ALTAS PENDIENTES</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div id="spinnerOverlayaphsp" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
                <div style="position:relative;top:40%;">
                    <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                    <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                        Cargando datos, por favor espere...
                    </p>
                </div>
            </div>
          </div>
      </div>

        <!-- NOTAS DE ENFERMERÍA PENDIENTES DE FIRMA -->
        <div class="tab-pane fade" id="tab-notasenfermeria" role="tabpanel" aria-labelledby="tab-notasenfermeria-tab">
          <form class="form-horizontal" method="post" id="frmBloqueEnfermeria" action="{{ route('listar_notas_enfermeria_ajax')}}">
            <input type="hidden" value="{{ csrf_token() }}" name="_token">
            <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento">
            <input type="hidden" value="" name="enfermeriaSeleccionadas" id="enfermeriaSeleccionadas">
            <input type="hidden" value="" name="tipoAtencionSeleccionada" id="tipoAtencionSeleccionada">
            <input type="hidden" value="" name="tipoDocumentoSeleccionado" id="tipoDocumentoSeleccionado">
            <input type="hidden" value="P" name="estado" id="estado_notas_enf">

            <div class="row" style="margin-bottom: 10px;">
              <div class="col-lg-12">
                  <div class="btn-group" role="group" id="filtroEstadoNotasEnf">
                      <button type="button" class="btn btn-sm btn-primary" data-estado="P">Pendientes</button>
                      <button type="button" class="btn btn-sm btn-default" data-estado="F">Firmados</button>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nro. Documento</label>
                  <div class="input-group fecha-input">
                      <input type="text" class="form-control input-sm" placeholder="Ingrese el número..." id="dni_beneficiario_enf" name="dni_beneficiario" maxlength="20" onkeypress="return soloNumeros(event)">
                      <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Desde</label>
                  <div class="input-group fecha-input">
                      <input class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio_enf" value="<?php echo $fechaDesde?>" maxlength="10">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Hasta</label>
                  <div class="input-group fecha-input">
                      <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin_enf" value="<?php echo $fechaHasta?>" maxlength="10">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <label for="">&nbsp;</label>
                  <button id="btnBuscarNotasEnf" class="btn btn-success input-sm btn-buscar-pequeno" type="button">
                      <i class="fa fa-search"></i> Buscar
                  </button>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  @if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0)
                      <label for="">&nbsp;</label>
                      <a class="btn btn-info input-sm btn-firmar-pequeno" id="btnFirmarNotasEnf">
                          <i class="fa fa-pencil-square-o"></i> Firmar
                      </a>
                  @endif
              </div>

              @if (Auth::user()->flagfiscalizador == 1)
              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" id="colReporteFirmasPendEnf">
                  <label for="">&nbsp;</label>
                  <button type="button" id="btnReporteFirmasPendEnf" class="btn input-sm btn-buscar-pequeno" data-toggle="modal" data-target="#modalReporteFirmasEnfermeria" style="background:#8E44AD;border-color:#8E44AD;color:#fff;font-weight:bold;">
                      <i class="fa fa-bar-chart"></i> Reporte de Firmas Pendientes
                  </button>
              </div>
              @endif
            </div>
          </form>
          <br>

          <div class="table-responsive">
            <table id="tblnotasenfermeria" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="text-align:center">N</th>
                  <th style="text-align:center">PACIENTE</th>
                  <th style="text-align:center">ATENCIÓN</th>
                  <th style="text-align:center">DOCUMENTO POR FIRMAR</th>
                  <th style="text-align:center">TIEMPO FIRMA</th>
                  <th style="text-align:center">VER DOCUMENTO</th>
                  @if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0)
                    <th style="text-align:center">FIRMAR <input type="checkbox" id="chkAllFirmarNotasEnf" style="display:none;width:20px;height:20px;"></th>
                  @else
                    <th style="text-align:center">FIRMAR</th>
                  @endif
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <div id="spinnerOverlayNotasEnf" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
              <div style="position:relative;top:40%;">
                <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">
                  Cargando documentos sin firmar, por favor espere...
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- REPORTE DE FIRMAS PENDIENTES ENFERMERÍA (POR UNIDAD/SUBUNIDAD) -->
        <div class="modal fade" id="modalReporteFirmasEnfermeria" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header" /*style="background:#8E44AD;color:#fff;"*/>
                <h5 class="modal-title"><i class="fa fa-heartbeat"></i> Reporte de Firmas Pendientes - Enfermería</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff;">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" method="post" id="frmBloquefirmasenf">
                    <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Desde</label>
                            <div class="input-group fecha-input">
                                <input class="form-control input-sm" type="text" name="fecha_ini_fpe" id="fecha_ini_fpe" style="height: 34px;" maxlength="10">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Hasta</label>
                            <div class="input-group fecha-input">
                                <input class="form-control input-sm" type="text" name="fecha_fin_fpe" id="fecha_fin_fpe" style="height: 34px;" maxlength="10">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Division</label>
                            <div class="input-group fecha-input">
                                <select id="division_enf" name="division_enf" class="form-control" style="font-size: 12px;">
                                    <option value="0">-- Seleccionar --</option>
                                    @foreach ($listar_div_enf as $item)
                                        <option value="{{ $item->id }}">{{ $item->codigo_division }} - {{ $item->nombre }}</option>
                                    @endforeach
                                </select><span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Departamento</label>
                            <div class="input-group fecha-input">
                                <select id="departamento_enf" name="departamento_enf" class="form-control" style="font-size: 12px;">
                                    <option value="0">-- Seleccionar --</option>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Profesional de Enfermería</label>
                            <div class="input-group fecha-input">
                                <select class="form-control input-sm" name="prof_salud_enf" id="prof_salud_enf" style="width: 100%; height: 34px !important;">
                                    <option value="0">-Seleccionar-</option>
                                    <?php foreach ($medicos as $row) : ?>
                                        <option value="<?php echo $row->id ?>">
                                            <?php echo $row->paterno ?> <?php echo $row->materno ?>, <?php echo $row->name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select><span class="input-group-addon"><i class="fa fa-user-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row text-right">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                            <label for="">&nbsp;</label>
                            <button id="btnExportarReporteEnf" class="btn btn-info input-sm btn-firmar-pequeno" type="button"><i class="fa fa-file-excel-o"></i><strong> Exportar Reporte </strong></button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right">
                            <label for="">&nbsp;</label>
                            <button id="btnBuscarfpEnf" class="btn btn-success input-sm btn-buscar-pequeno" type="button"><i class="fa fa-search"></i><strong> Buscar </strong></button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="table-responsive">
                  <table id="tblfirmpendenf" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style="text-align:center">N</th>
                        <th style="text-align:center">PROFESIONAL DE ENFERMERÍA</th>
                        <th style="text-align:center">ESTADO</th>
                        <th style="text-align:center">DIVISIÓN</th>
                        <th style="text-align:center">DEPARTAMENTO</th>
                        <th style="text-align:center">ORIGEN DE ATENCION</th>
                        <th style="text-align:center">FIRMAS PENDIENTES</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                  <div id="spinnerOverlayfpenf" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
                      <div style="position:relative;top:40%;">
                          <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                          <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;">Cargando datos, por favor espere...</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<input type="hidden" id="flagServicioFirma" value="" />
<input type="hidden" id="id_radiologia_actualizar" value="" />
<input type="hidden" id="id_laboratorio_actualizar" value="" />
<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="id_prestacion" name="id_prestacion" value="" />
<input type="hidden" id="id_principal" name="id_principal" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" id="nombre_doc_sin_firmar" value="" />
<input type="hidden" id="opt_firma" value="" />
<div id="addComponent"></div>

<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document" style="max-width:1200px; height:90vh;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pdfTitle">Visor de PDF</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <iframe id="pdfFrame" src="" frameborder="0" style="width:100%; height:calc(90vh - 120px);"></iframe>
        <div id="pdfNavButtons" class="d-flex justify-content-center mt-2" style="display:none;">
            <button id="prevPdf" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Anterior</button>
            <button id="nextPdf" class="btn btn-secondary ">Siguiente <i class="fa fa-arrow-right"></i></button>
        </div>
        <input type="hidden" id="id_prestacion_hidden" value="">
        <input type="hidden" id="id_principal_hidden" value="">
        <input type="hidden" id="id_evolucion_hidden" value="">
        <input type="hidden" id="tipo_doc_hidden" value="">
        <input type="hidden" id="id_interconsulta_hidden" value="">
      </div>

      <div class="modal-footer">
        <a href="#" id="btnRegresar" class="btn btn-secondary" style="float: left;">
            <i class="fa fa-arrow-left"></i> Regresar a la Atención
        </a>
        @if (Auth::user()->tipo_registro == 1 && Auth::user()->status_token == 0)
        <a id="btnFirmarModal" class="btn btn-success">
            <i class="fa fa-pencil"></i> Firmar
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection