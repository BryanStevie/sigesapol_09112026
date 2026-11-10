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
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
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

    #tarjetas .panel {
        text-align: center;
        font-weight: bold;
    }

    .checkbox-grande {
        width: 20px;
        height: 20px;
        accent-color: green;
        cursor: pointer;
        margin-top: 3px;
    }

    .sin-circulo {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        color: inherit !important;
    }

    .label-exito {
        padding: 0.7em 12em;        
        background-color: #28a745; 
    }

    .estado-cama {
        display: inline-block;
        min-width: 281px;
        text-align: center;
        font-size: 14px;
        padding: 7px 43px;
    }

</style>

<script>

    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
                "emptyTable": "No se encontraron resultados"
            }
        });

        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }

        $("#establecimiento").select2({
            width: 'resolve'
        });
        $("#tipo_atencion").select2({
            width: 'resolve'
        });
        $('#servicio_se').select2();
        @if(isset($id_servicio))
            var servicioSelect = '{{ $id_servicio }}';
        @else
            var servicioSelect = '';
        @endif
        console.log(servicioSelect);

        function getFiltros(){
            var establecimiento = $("#establecimiento").val();
            var atencion = $("#tipo_atencion").val();
            
            if (!establecimiento || !atencion) {
                return;
            }

            $.ajax({
                url: "/censo_camas_filtro/getFiltros", 
                type: "POST",              
                data: { 
                    _token: "{{ csrf_token() }}",
                    atencion: atencion,
                    establecimiento: establecimiento 
                },  
                dataType: "json",         
                success: function (response) {
                    // console.log('Servicios obtenidos:', response);
                    $('#servicio_se').empty().append(
                        $('<option>', {
                            value: '',
                            text: 'Seleccione'
                        })
                    );
                    response.forEach(function (item) {
                        $('#servicio_se').append(
                            $('<option>', {
                                value: item.id,
                                text: item.nombre
                            })
                        );
                    });
                        
                    if (servicioSelect) {
                        $('#servicio_se').val(servicioSelect).trigger('change');
                        servicioSelect = ''; 
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud:", status, error);
                }
            });
        }

        getFiltros();

        $("#tipo_atencion").on("change", function() {
            getFiltros();
        });

        $("#establecimiento").on("change", function() {
            getFiltros();
        });

        $('#establecimiento, #tipo_atencion, #servicio_se').on('change', function () {
            aplicarFiltros();
        });
    });

    function aplicarFiltros() {
        var establecimiento = $('#establecimiento').val();
        var tipo_atencion = $('#tipo_atencion').val();
        var servicio_se = $('#servicio_se').val();
        var id_user = $('input[name="id_user"]').val();

        if (!establecimiento || !tipo_atencion) {
            return;
        }

        $.ajax({
            url: "/censo_camas_filtro",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_user: id_user,
                establecimiento: establecimiento,
                tipo_atencion: tipo_atencion,
                servicio_se: servicio_se
            },
            success: function(response) {
                var $html = $(response);
                var $tarjetas = $html.find('#tarjetas');
                if ($tarjetas.length) {
                    $('#tarjetas').html($tarjetas.html());
                }
                
                if ($.fn.DataTable.isDataTable('#tablaCensoCamas')) {
                    $('#tablaCensoCamas').DataTable().ajax.reload(null, false);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al aplicar filtros:", status, error);
            }
        });
    }

    function getAtencion(){
        $('#tipo_atencion').empty();
        $('#servicio').empty();
        $('#pabellon').empty();

        var id_establecimiento = $('#establecimiento').val();
        if (id_establecimiento != "") {
            $.ajax({
                url: '/obtener_atencion_por_idestablecimiento/' + id_establecimiento,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        if (oo.nombre_atencion == "EMERGENCIA") {
                            newOption += "<option value='"+oo.nombre_atencion+"'>"+oo.nombre_atencion+"</option>";
                        }
                    });                 
                    $('#tipo_atencion').html(newOption);
                }
            });
        }
    }

    function getServicio(){
        $('#servicio').empty();
        $('#pabellon').empty();
        var tipo_atencion = $('#tipo_atencion').val();
        var id_establecimiento = $('#id_establecimiento').val();

        if (tipo_atencion != "") {
            $.ajax({
                url: '/obtener_servicio_por_idestablecimiento_tipoatencion2/' + id_establecimiento + '/' + tipo_atencion,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    newOption += "<option value='0'>TODOS</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
                    });                 
                    $('#servicio').html(newOption);
                    if (tipo_atencion == "EMERGENCIA") {
                        $('#servicio').val("76");
                        $('#servicio').change();
                    }
                }
            });
        }
    }
   
   $(document).ready(function () {

    $('#frmlistadocenso').on('submit', function(e) {
        e.preventDefault();
    });

    let camasCensadasSesion = JSON.parse(localStorage.getItem('camasCensadasSesion')) || [];
    let mapaCensos = JSON.parse(localStorage.getItem('mapaCensos')) || {};

    function generarClaveCama(row) {
        return `${row.nombre_cama}|${row.paciente || ''}|${row.servicio_encargado || ''}|${row.fecha_ingreso || ''}`;
    }

    function EstadoCamaBD(valor) {
        switch (String(valor)) {
            case "1": return "LIBRE";
            case "2": return "REPARACION";
            case "3": return "MALOGRADA";
            case "4": return "OCUPADO";
            default:  return "DESCONOCIDO";
        }
    }
    
    let tablaCenso = $('#tablaCensoCamas').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        deferLoading: 0,
        ajax: {
            url: "{{ route('listado_censo_camas') }}",
            type: "POST",
            data: function (d) {
                d.id_establecimiento = $('#establecimiento').val();
                d.id_servicio = $('#servicio_se').val();
                d.nombre_atencion = $('#tipo_atencion').val();
                d.search = { value: $('#buscar_dni_nombre').val() };
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    alert('Sesión expirada, vuelva a iniciar sesión');
                    location.reload();
                } else {
                    console.error(xhr.responseText);
                }
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return row.paciente ? `<input type="checkbox" class="checkbox-grande">` : '';
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { data: 'nombre_cama', title: 'Nro. CAMA', className: 'text-center' },
            {
                data: null,
                title: 'PACIENTE',
                render: function (data, type, row) {
                    let estadoGuardado = localStorage.getItem("estado_cama_" + row.id_cama);

                    const mapaEstadosNumericos = {
                        1: "LIBRE",
                        2: "EN REPARACION",
                        3: "MALOGRADO",
                        4: "OCUPADO"
                    };

                    let estadoCama = estadoGuardado ? estadoGuardado : row.estado_cama;

                    if (!isNaN(estadoCama)) {
                        estadoCama = mapaEstadosNumericos[estadoCama] || "LIBRE";
                    }
                    
                    let estadoFinal = estadoCama.toUpperCase();

                    function getClassByEstado(estado) {
                        switch (estado) {
                            case "LIBRE": return "label-exito";
                            case "EN REPARACION": return "label-danger";
                            case "MALOGRADO": return "label-default";
                            case "OCUPADO": return "label-info";
                            default: return "label-exito";
                        }
                    }

                    let claseEstado = getClassByEstado(estadoFinal);

                    if (!row.paciente) {
                        return `
                            <span class="label ${claseEstado} estado-cama">
                                ${estadoFinal}
                            </span>
                        `;                    
                    }

                    let sexo = row.sexo === 'M' ? 'MASCULINO' :
                            row.sexo === 'F' ? 'FEMENINO' : '-';

                    return `
                        <div><b>DNI:</b> ${row.paciente || '-'}</div>
                        <div><b>NOMBRE:</b> ${row.paciente_datos || '-'}</div>
                        <div><b>EDAD:</b> ${row.edad || '-'}</div>
                        <div><b>SEXO:</b> ${sexo}</div>
                    `;
                }
            },
            {
                data: null,
                title: 'ESPECIALIDAD',
                render: function (row) {
                    return `
                        <div><b>SERVICIO A CARGO:</b> ${row.servicio_destino || '-'}</div>
                        <div><b>FECHA INGRESO:</b> ${row.fecha_ingreso || '-'} ${row.hora_ingreso || '-'}</div>
                        <div><b>FECHA TRANSFERENCIA:</b> ${row.fecha_traslado || '-'} ${row.hora_traslado || '-'}</div>
                    `;
                }
            },
            {
                data: null,
                title: 'UBICACIÓN ACTUAL',
                render: function (row) {
                    return `
                        <div><b>SERVICIO DESTINO:</b> ${row.servicio_destino || '-'}</div>
                        <div><b>PABELLÓN:</b> ${row.nombre_pabellon || '-'}</div>
                        <div><b>PISO:</b> ${row.nombre_piso || '-'}</div>
                        <div><b>CUARTO:</b> ${row.nombre_cuarto || '-'}</div>
                    `;
                }
            },
            { data: 'descripcion_estado', title: 'ORIGEN' },
            {
                data: null,
                title: 'FECHA CENSO',
                render: function (row) {
                    let tiempo = row.tiempo_transcurrido ? row.tiempo_transcurrido : '';
                    let color = '';
                    if (tiempo.includes('0 día')) color = 'label label-exito';
                    else if (tiempo !== '') color = 'label label-danger';
                    return `
                       <div class="text-center">
                            <div>${row.fecha_censo || ''} ${row.hora_censo || ''}</div>
                            ${tiempo ? `<div style="margin-top:8px;">
                                <span class="${color}" style="font-size:13px; padding:5px 8px; border-radius:6px;">${tiempo}</span>
                            </div>` : ''}
                        </div>
                    `;
                }
            },
            {
                data: null,
                title: 'ACCIONES',
                render: function (row) {
                    // console.log(row);
                    const esLibre = !row.paciente || (row.descripcion_estado || '').toString().trim().toUpperCase() === 'LIBRE';
                    if (esLibre) {
                        return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    Acción <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" 
                                        class="btn-modificar-estado"
                                        data-id_cama="${row.id_cama}"
                                        data-nombre_cama="${row.nombre_cama}"
                                        data-servicio_encargado="${row.servicio_encargado}"
                                        data-id_servicio_actual="${row.servicio_origen}">
                                        Modificar Estado
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }

                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                Acción <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#"
                                    class="btn-rotar-cama"
                                    data-id_ext="${row.id_ext}" 
                                    data-tipo_atencion="${row.tipo_atencion}"
                                    data-id_cama="${row.id_cama}"
                                    data-nombre_cama="${row.nombre_cama}"
                                    data-id_consultorio="${row.id_consultorio}"
                                    data-id_servicio_actual="${row.servicio_origen}"
                                    data-prestacion="${row.prestacion}"
                                    data-id_evaluacion="${row.id_evaluacion}">
                                    Rotar
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                    class="btn-transferir"
                                    data-id_ext="${row.id_ext}" 
                                    data-id_estado="${row.id_estado}"
                                    data-id_cama="${row.id_cama}"
                                    data-nombre_cama="${row.nombre_cama}"
                                    data-nombre_upss="${row.nombre_upss}"
                                    data-id_consultorio="${row.id_consultorio}"
                                    data-servicio_destino="${row.servicio_destino}"
                                    data-establecimiento="${row.establecimiento}"
                                    data-prestacion="${row.prestacion}"
                                    data-id_evaluacion="${row.id_evaluacion}"
                                    data-tipo_atencion="${row.tipo_atencion}"
                                    data-id_servicio_encargado="${row.id_servicio_encargado}"
                                    data-id_upss_encargado="${row.id_upss_encargado}">
                                    Transferir
                                    </a>
                                </li>
                                <!--
                                <li>
                                     <a href="#"
                                        class="btn-liberar-cama"
                                        data-id_cama="${row.id_cama}"
                                        data-nombre_cama="${row.nombre_cama}"
                                        data-servicio_encargado="${row.servicio_encargado}"
                                        data-id_servicio_actual="${row.servicio_origen}">
                                        Liberar Cama
                                    </a>
                                </li>
                                -->
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        rowCallback: function(row, data) {
            let clave = generarClaveCama(data);
            if (camasCensadasSesion.includes(clave)) {
                $(row).css('background-color', 'rgb(223, 240, 216)');
            } else {
                $(row).css('background-color', '');
            }
        },
        language: {
            emptyTable: "No se encontraron resultados",
            processing: "Procesando...",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron registros coincidentes"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    $('#establecimiento, #tipo_atencion, #servicio_se').on('change', function () {
        tablaCenso.ajax.reload();
    });

    $('#buscar_dni_nombre').on('keyup', function (e) {
        if (e.keyCode === 13) tablaCenso.ajax.reload();
    });

    $('#btnBuscar').on('click', function () {
        tablaCenso.ajax.reload();
    });

    $('#btnCensar').on('click', function (e) {
        e.preventDefault();

        let filasSeleccionadas = $('#tablaCensoCamas tbody tr').has('.checkbox-grande:checked');

        if (!filasSeleccionadas.length) {
            Swal.fire({
                iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Seleccione al menos una cama para censar.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                customClass: { icon: 'sin-circulo' }
            });
            return;
        }

        let censos = filasSeleccionadas.map(function() {
            return tablaCenso.row(this).data();
        }).get();

        $.ajax({
            url: "{{ route('registrar_censos') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", censos: censos },
            beforeSend: function () {
                $('#btnCensar').prop('disabled', true).text('Censando...');
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#btnCensar').prop('disabled', false).text('CENSAR');
                        filasSeleccionadas.each(function() {
                            let dataFila = tablaCenso.row(this).data();
                            let clave = generarClaveCama(dataFila);

                            if (!camasCensadasSesion.includes(clave)) {
                                camasCensadasSesion.push(clave);
                                localStorage.setItem('camasCensadasSesion', JSON.stringify(camasCensadasSesion));
                            }

                            $(this).css('background-color', 'rgb(223, 240, 216)');
                            $(this).find('.checkbox-grande').prop('checked', false);
                        });
                
                    tablaCenso.ajax.reload(null, false);
                });
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Error desconocido.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error al registrar censos',
                    text: msg,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                }).then(() => {
                    $('#btnCensar').prop('disabled', false).text('CENSAR');
                });
            }
        });
    });

        $('#btnLimpiarCensos').on('click', function () {
            camasCensadasSesion = [];
            localStorage.removeItem('camasCensadasSesion');
            tablaCenso.ajax.reload();
        });
    });

    $(document).on('click', '.btn-rotar-cama', function (e) {
        e.preventDefault();

        const idExt = $(this).attr('data-id_ext');
        const tipoAtencion = $(this).data('tipo_atencion');
        const idCama = $(this).data('id_cama');
        const nombreCama = $(this).data('nombre_cama');
        const idConsultorio = $(this).data('id_consultorio');
        const idServicioActual = $(this).data('id_servicio_actual');

        const idEstablecimiento = $('#id_establecimiento').val();
        const prestacion = $(this).data('prestacion');
        const idEvaluacion = $(this).data('id_evaluacion');

        $('#id_ext').val(idExt);
        $('#modalRotarCama #tipo_atencion').val(tipoAtencion);
        $('#id_servicio_actual').val(idServicioActual);
        $('#id_consultorio').val(idConsultorio);
        $('#cama_anterior').html(`<option selected value="${idCama}">${nombreCama}</option>`);
        $('#id_cama').val(idCama);
        $('#id_prestacion').val(prestacion);
        $('#id_evaluacion').val(idEvaluacion);
        $('#observacion').val('');
        $('#fecha_rotar').val('');
        $('#cama_actual').html('<option value="">Cargando camas...</option>');

        $.get(`/obtener_servicio_cama_consultorio/${idEstablecimiento}/${idConsultorio}/${tipoAtencion}`, function (data) {
            let opciones = '<option value="">Seleccione cama</option>';

            $.each(data, function (index, c) {
                if (c.id_cama && c.id_cama != idCama) {
                    opciones += `
                        <option 
                            value="${c.id_cama}"
                            data-id_ext="${c.id_ext}"
                            data-id_evaluacion="${c.id_evaluacion}"
                        >
                            ${c.cama}
                        </option>
                    `;
                }
            });

            $('#cama_actual').html(opciones);
            const ahora = new Date();
            const offsetMs = ahora.getTimezoneOffset() * 60000;
            const fechaLocal = new Date(ahora - offsetMs).toISOString().slice(0, 16);
            $('#fecha_rotar').val(fechaLocal);
            $('#modalRotarCama').modal('show');

        }).fail(function () {
            $('#cama_actual').html('<option value="">Error al cargar camas</option>');
        });
    });

    $('#cama_actual').on('change', function () {
        let selected = $(this).find('option:selected');

        let idExtDestino = selected.data('id_ext') || 0;
        let idEvaluacionDestino = selected.data('id_evaluacion') || 0;
        let idCamaNueva = selected.val();

        $('#id_ext_destino').val(idExtDestino);
        $('#id_evaluacion_destino').val(idEvaluacionDestino);
        $('#id_cama').val(idCamaNueva);
    });

    $('#formRotarCama').on('submit', function (e) {
        e.preventDefault();

        let tipoAtencionTexto = $('#tipo_atencion').val();

        let tipoAtencionValor = (tipoAtencionTexto === 'HOSPITALIZACION') ? 3 :
                                (tipoAtencionTexto === 'EMERGENCIA') ? 2 : 0;

        const datos = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_ext: $('#id_ext').val(),
            tipo_atencion: tipoAtencionValor,
            cama_anterior: $('#cama_anterior').val(),
            cama_actual: $('#cama_actual').val(),
            observacion: $('#observacion').val(),
            fecha_rotar: $('#fecha_rotar').val(),
            id_servicio_actual: $('#id_servicio_actual').val(),
            id_prestacion: $('#id_prestacion').val(),
            id_evaluacion: $('#id_evaluacion').val(),
            id_consultorio: $('#id_consultorio').val(),

            id_ext_destino: $('#id_ext_destino').val(),
            id_evaluacion_destino: $('#id_evaluacion_destino').val(),
        };

        if (!datos.fecha_rotar) {
            return Swal.fire({
                iconHtml: '<i style="font-size:55px;color:#f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Debe ingresar la fecha.',
                customClass: { icon: 'sin-circulo' }
            });
        }

        if (!datos.cama_actual) {
            return Swal.fire({
                iconHtml: '<i style="font-size:55px;color:#f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Debe seleccionar la cama.',
                customClass: { icon: 'sin-circulo' }
            });
        }

        $.ajax({
            url: '/registrar_rotacion_cama',
            type: 'POST',
            data: datos,
            beforeSend: function () {
                $('#btnGuardarRotacion').prop('disabled', true).text('Guardando...');
            },
            success: function (response) {
                if (response.success) {

                    window.idExtRotado = datos.id_ext;
                    localStorage.setItem('ultimoIdRotado', datos.id_ext);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message,
                    }).then(() => {
                        $('#modalRotarCama').modal('hide');
                        $('#btnGuardarRotacion').prop('disabled', false).text('Guardar');
                        $('#tablaCensoCamas').DataTable().ajax.reload(null, false);
                    });

                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                    $('#btnGuardarRotacion').prop('disabled', false).text('Guardar');
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el servidor',
                    text: xhr.responseJSON?.message || 'Ocurrió un error inesperado.',
                });
                $('#btnGuardarRotacion').prop('disabled', false).text('Guardar');
            }
        });
    });

    $('#tablaCensoCamas').on('draw.dt', function () {
        let idRotado = window.idExtRotado || localStorage.getItem('ultimoIdRotado');
        if (!idRotado) return;
        let tabla = $('#tablaCensoCamas').DataTable();

        tabla.rows().every(function () {
            let row = this.data();
            if (row.id_ext == idRotado) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

    $('#tablaCensoCamas').on('search.dt', function () {
        let idRotado = window.idExtRotado || localStorage.getItem('ultimoIdRotado');
        if (!idRotado) return;
        let tabla = $('#tablaCensoCamas').DataTable();

        tabla.rows({ search: 'applied' }).every(function () {
            let row = this.data();
            if (row.id_ext == idRotado) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

    $(document).ready(function () {

    let dataUpss = [];

    $(document).on('click', '.btn-transferir', function (e) {
        e.preventDefault();

        const ahora = new Date();
        const offsetMs = ahora.getTimezoneOffset() * 60000;
        const fechaLocal = new Date(ahora - offsetMs).toISOString().slice(0, 16);

        const idExt = $(this).data('id_ext');
        const idEstado = $(this).data('id_estado');
        const idCamaActual = $(this).data('id_cama');
        const idConsultorioActual = $(this).data('id_consultorio');
        const idEstablecimiento = $(this).data('establecimiento');
        const prestacion = $(this).data('prestacion');
        const idEvaluacion = $(this).data('id_evaluacion');
        const TipoAtencion = $(this).data('tipo_atencion');
        const idServicioEncargado = $(this).data('id_servicio_encargado');
        const idUpssEncargado = $(this).data('id_upss_encargado');

        $('#id_ext_transferencia').val(idExt);
        $('#id_estado').val(idEstado);
        $('#fecha_rotar_transferencia').val(fechaLocal);
        $('#id_cama_actual_hidden').val(idCamaActual);
        $('#id_consultorio_hidden').val(idConsultorioActual);
        $('#id_establecimiento_hidden').val(idEstablecimiento);
        $('#id_prestacion_hidden').val(prestacion);
        $('#id_evaluacion_hidden').val(idEvaluacion);
        $('#tipo_atencion_hidden').val(TipoAtencion);
        $('#id_servicio_encargado').val(idServicioEncargado);
        $('#id_upss_encargado').val(idUpssEncargado);

        $('#nombre_upss').val($(this).data('nombre_upss'));
        $('#nombre_servicio_actual').val($(this).data('servicio_destino'));
        $('#cama_actual_input').val($(this).data('nombre_cama'));

        $('#upss').html('<option value="">Seleccione</option>');
        $('#especialidad').html('<option value="">Seleccione</option>');
        $('#cama').html('<option value="">Seleccione</option>');
        $('#medico').html('<option value="">Seleccione</option>');
        $('#observacion_transferencia').val('');

        $('#modalTransferencia').modal('show');

        $.get('/obtener_upss/' + idConsultorioActual)
            .done(function (data) {
                dataUpss = data;
                cargarUpss();
        });

        cargarMedicosServicioActual(idConsultorioActual);
    });

    function cargarUpss() {
        $('#upss').html('<option value="">Seleccione</option>');

        dataUpss.forEach(item => {
            $('#upss').append(`
                <option value="${item.id_upss}">
                     ${item.codigo} - ${item.nombre}
                </option>
            `);
        });

        $("#upss").select2({
            width: '100%',       
            allowClear: true,
        });

    }

    $(document).on('change', '#upss', function () {
        let id_upss = $(this).val();

        $('#especialidad').html('<option value="">Cargando...</option>');
        $('#cama').html('<option value="">Seleccione</option>');

        if (!id_upss) {
            $('#especialidad').html('<option value="">Seleccione</option>');
            return;
        }

        $.get('/obtener_especialidades/' + id_upss)
            .done(function (data) {
                $('#especialidad').html('<option value="">Seleccione</option>');

                data.forEach(item => {
                    $('#especialidad').append(`
                        <option value="${item.id}">
                            ${item.servicio}
                        </option>
                    `);
                });
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar especialidades'
                });
            });
        });

        $(document).on('change', '#especialidad', function () {
            var idConsultorio = $(this).val();

            $('#cama').html('<option value="">Cargando...</option>');

            if (!idConsultorio) {
                $('#cama').html('<option value="">Seleccione</option>');
                return;
            }

            $.get('/obtener_upss_cama/' + idConsultorio)
                .done(function (data) {
                    $('#cama').html('<option value="">Seleccione</option>');

                    let map = {};
                    data.forEach(function(item){
                        if (!map[item.id_cama]) {
                            map[item.id_cama] = true;

                            $('#cama').append(`
                                <option value="${item.id_cama}">
                                    ${item.cama}
                                </option>
                            `);
                        }
                    });
                })
                .fail(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar las camas.'
                    });
                });
        });

        function cargarMedicosServicioActual(idConsultorio) {
            $.get('/medicos/' + idConsultorio)
                .done(function (response) {
                    $('#medico').html('<option value="">Seleccione</option>');
                    response.forEach(item => {
                        $('#medico').append(`<option value="${item.id}">${item.doctor}</option>`);
                    });

                    $("#medico").select2({
                        width: 'resolve',
                        minimumResultsForSearch: 0,
                        allowClear: true
                    });

                    $(document).off('focusin.modal');
                });
        }

        $(document).on('submit', '#formTransferencia', function (e) {
            e.preventDefault();

            const datos = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_ext: $('#id_ext_transferencia').val(),
                tipo_atencion: $('#tipo_atencion_hidden').val(),
                id_establecimiento: $('#id_establecimiento_hidden').val(),
                id_servicio_encargado: $('#id_servicio_encargado').val(),
                id_upss_encargado: $('#id_upss_encargado').val(),
                id_prestacion: $('#id_prestacion_hidden').val(),
                id_evaluacion: $('#id_evaluacion_hidden').val(),
                id_estado: $('#id_estado').val(),
                cama_anterior: $('#id_cama_actual_hidden').val(),
                id_upss: $('#upss').val(),
                id_servicio_actual: $('#especialidad').val(),
                id_cama_actual: $('#cama').val(),
                id_medico_ordena: $('#medico').val(),
                observacion: $('#observacion_transferencia').val(),
                fecha_rotar: $('#fecha_rotar_transferencia').val()
            };
            
            if (!datos.fecha_rotar) {
                return Swal.fire({
                    iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                    title: 'Atención',
                    text: 'Debe ingresar la fecha.',
                    customClass: { icon: 'sin-circulo' }
                });
            }

            if (!datos.id_upss) {
                return Swal.fire({
                    iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                    title: 'Atención',
                    text: 'Debe seleccionar el la especialidad.',
                    customClass: { icon: 'sin-circulo' }
                });
            }
             if (!datos.id_servicio_actual) {
                return Swal.fire({
                    iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                    title: 'Atención',
                    text: 'Debe seleccionar la Ubicación del paciente.',
                    customClass: { icon: 'sin-circulo' }
                });
            }

            if (!datos.id_cama_actual) {
                return Swal.fire({
                    iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                    title: 'Atención',
                    text: 'Debe seleccionar la cama.',
                    customClass: { icon: 'sin-circulo' }
                });
            }

            if (!datos.id_medico_ordena) {
                return Swal.fire({
                    iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                    title: 'Atención',
                    text: 'Debe seleccionar el médico.',
                    customClass: { icon: 'sin-circulo' }
                });
            }

            $.ajax({
                url: '/transferencia_cama',
                type: 'POST',
                data: datos,
                beforeSend: function () {
                    $('#btnGuardarTranseferencia')
                        .prop('disabled', true)
                        .text('Guardando...');
                },
                success: function (response) {
                    if (response.success) {
                        window.idExtTransferido = datos.id_ext;
                        localStorage.setItem('ultimoIdTransferido', datos.id_ext);

                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message || 'Transferencia registrada correctamente',
                        }).then(() => {
                            $('#modalTransferencia').modal('hide');

                            if ($('#tablaCensoCamas').length) {
                                $('#tablaCensoCamas').DataTable().ajax.reload(null, false);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Ocurrió un error al registrar la transferencia',
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en el servidor',
                        text: xhr.responseJSON?.message || 'Ocurrió un error inesperado.',
                    });
                },
                complete: function () {
                    $('#btnGuardarTranseferencia')
                        .prop('disabled', false)
                        .text('Guardar');
                }
            });
        });

        $('#modalTransferencia').on('hidden.bs.modal', function () {
            $('#formTransferencia')[0].reset();
            $('#cama').html('<option value="">Seleccione</option>');
            $('#especialidad').html('<option value="">Seleccione</option>');
            $('#medico').html('<option value="">Seleccione</option>');
            $('#id_cama_actual_hidden').val('');

            if ($('#medico').data('select2')) {
                $('#medico').val(null).trigger('change');
            }
        });


        $('#tablaCensoCamas').on('draw.dt', function () {
            let idTransferido = window.idExtTransferido || localStorage.getItem('ultimoIdTransferido');
            if (!idTransferido) return;

            let tabla = $('#tablaCensoCamas').DataTable();
            tabla.rows().every(function () {
                let row = this.data();
                if (row.id_ext == idTransferido) {
                    $(this.node()).css('background-color', 'rgb(223,240,216)');
                    return false;
                }
            });
        });
   
        $('#tablaCensoCamas').on('search.dt', function () {
            let idTransferido = window.idExtTransferido || localStorage.getItem('ultimoIdTransferido');
            if (!idTransferido) return;

            let tabla = $('#tablaCensoCamas').DataTable();
            tabla.rows({ search: 'applied' }).every(function () {
                let row = this.data();
                if (row.id_ext == idTransferido) {
                    $(this.node()).css('background-color', 'rgb(223,240,216)');
                    return false;
                }
            });
        });
    });
    
    $(document).on('click', '.btn-liberar-cama', function (e) {
        e.preventDefault();

        const idCama = $(this).data('id_cama');
        const idServicio = $(this).data('id_servicio_actual');
        const servicio = $(this).data('servicio_encargado');
        const nombreCama = $(this).data('nombre_cama');

        $('#id_cama_actual_hidden_l').val(idCama);
        $('#id_servicio_actual_hidden_l').val(idServicio);

        $('#nombre_servicio_actual_l').html(`<option value="${idServicio}" selected>${servicio}</option>`);
        $('#cama_actual_input_l').html(`<option value="${idCama}" selected>${nombreCama}</option>`);

        $('#observacion_liberar').val('');
        $('#liberarCheck').prop('checked', true);

        $('#modalLiberarCama').modal('show');
    });

    $(document).on('submit', '#formLiberarCama', function(e) {
        e.preventDefault();

        const observacion = $('#observacion_liberar').val().trim();
        if (observacion === '') {
            return Swal.fire({
                iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Debe ingresar una observación.',
                customClass: { icon: 'sin-circulo' }
            });
        }

        const datos = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_cama_actual: $('#id_cama_actual_hidden_l').val(),
            id_servicio_actual: $('#id_servicio_actual_hidden_l').val(),
            observacion: $('#observacion_liberar').val(),
        };

        $('#btnGuardarLiberacion').prop('disabled', true).text('Guardando...');

        $.ajax({
            url: '/liberar_cama',
            type: 'POST',
            data: datos,
            success: function(response) {
                if (response.success) {

                    window.idCamaLiberada = datos.id_cama_actual;
                    localStorage.setItem('ultimaCamaLiberada', datos.id_cama_actual);

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message,
                    }).then(() => {
                        $('#modalLiberarCama').modal('hide');
                        $('#btnGuardarLiberacion').prop('disabled', false).text('Guardar');

                        if ($.fn.DataTable.isDataTable('#tablaCensoCamas')) {
                            $('#tablaCensoCamas').DataTable().ajax.reload(null, false);
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                    $('#btnGuardarLiberacion').prop('disabled', false).text('Guardar');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el servidor',
                    text: xhr.responseJSON?.message || 'Ocurrió un error inesperado.'
                });
                $('#btnGuardarLiberacion').prop('disabled', false).text('Guardar');
            }
        });
    });

    $('#tablaCensoCamas').on('draw.dt', function () {

        let idLiberada = window.idCamaLiberada || localStorage.getItem('ultimaCamaLiberada');
        if (!idLiberada) return;

        let tabla = $('#tablaCensoCamas').DataTable();
        tabla.rows().every(function () {
            let row = this.data();
            if (row.id_cama == idLiberada) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

    $('#tablaCensoCamas').on('search.dt', function () {

        let idLiberada = window.idCamaLiberada || localStorage.getItem('ultimaCamaLiberada');
        if (!idLiberada) return;

        let tabla = $('#tablaCensoCamas').DataTable();
        tabla.rows({ search: 'applied' }).every(function () {
            let row = this.data();
            if (row.id_cama == idLiberada) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

    $(document).on('click', '.btn-modificar-estado', function(e) {
        e.preventDefault();

        const idCama = $(this).data('id_cama');
        const idServicio = $(this).data('id_servicio_actual');
        const servicio = $(this).data('servicio_destino');
        const nombreCama = $(this).data('nombre_cama');

        $('#id_cama_actual_hidden_m').val(idCama);
        $('#id_servicio_actual_hidden_m').val(idServicio);

        $('#nombre_servicio_actual_m').html(`<option value="${idServicio}" selected>${servicio}</option>`);
        $('#cama_actual_input_m').html(`<option value="${idCama}" selected>${nombreCama}</option>`);

        $('#observacion_estado').val('');

        $('#modalModificarEstado').modal('show');

         $('#estado').html('<option value="">Cargando...</option>');

        $.get('/obtener_situacion_cama', function(data) {
            let opciones = '<option value="">Seleccione estado</option>';
            $.each(data, function(index, item) {
                opciones += `<option value="${item.id}">${item.descripcion}</option>`;
            });
            $('#estado').html(opciones);
            $('#modalModificarEstado').modal('show');
        }).fail(function() {
            $('#estado').html('<option value="">Error al cargar</option>');
        });
    });

   $('#formModificarEstado').on('submit', function(e) {
        e.preventDefault();

        const datos = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_cama_actual: $('#id_cama_actual_hidden_m').val(),
            id_servicio_actual: $('#id_servicio_actual_hidden_m').val(),
            estado: $('#estado').val(),
            observacion: $('#observacion_estado').val()
        };

        if (!datos.estado) {
            return Swal.fire({
                iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Debe seleccionar un estado.',
                customClass: { icon: 'sin-circulo' }
            });
        }

        const observacion = $('#observacion_estado').val().trim();
        if (observacion === '') {
            return Swal.fire({
                iconHtml: '<i style="font-size: 55px; color: #f8bb86;">&#9888;</i>',
                title: 'Atención',
                text: 'Debe ingresar una observación.',
                customClass: { icon: 'sin-circulo' }
            });
        }

        $.ajax({
            url: '/modificar_estado_cama',
            type: 'POST',
            data: datos,
            beforeSend: function () {
                $('#btnGuardarModificacion').prop('disabled', true).text('Guardando...');
            },
            success: function (response) {
                if (response.success) {

                    window.camaModificada = datos.id_cama_actual;
                    localStorage.setItem('ultimaCamaModificada', datos.id_cama_actual);

                    localStorage.setItem(
                        "estado_cama_" + datos.id_cama_actual,
                        datos.estado
                    );

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message,
                    }).then(() => {
                        $('#modalModificarEstado').modal('hide');
                        $('#btnGuardarModificacion').prop('disabled', false).text('Guardar');

                        if ($.fn.DataTable.isDataTable('#tablaCensoCamas')) {
                            $('#tablaCensoCamas').DataTable().ajax.reload(null, false);
                        }

                        aplicarFiltros();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                    $('#btnGuardarModificacion').prop('disabled', false).text('Guardar');
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el servidor',
                    text: xhr.responseJSON?.message || 'Ocurrió un error inesperado.',
                });
                $('#btnGuardarModificacion').prop('disabled', false).text('Guardar');
            }
        });
    });

    $('#tablaCensoCamas').on('draw.dt', function () {
        let idModificada = window.camaModificada || localStorage.getItem('ultimaCamaModificada');
        if (!idModificada) return;

        let tabla = $('#tablaCensoCamas').DataTable();
        tabla.rows().every(function () {
            let row = this.data();
            if (row.id_cama == idModificada) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

    $('#tablaCensoCamas').on('search.dt', function () {
        let idModificada = window.camaModificada || localStorage.getItem('ultimaCamaModificada');
        if (!idModificada) return;

        let tabla = $('#tablaCensoCamas').DataTable();
        tabla.rows({ search: 'applied' }).every(function () {
            let row = this.data();
            if (row.id_cama == idModificada) {
                $(this.node()).css('background-color', 'rgb(223,240,216)');
                return false;
            }
        });
    });

</script>

@endsection
@section('content')
<div class="container-fluid" style="max-width: 82%;">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center">Censo de Camas</h3>
        </div>  
        <div class="panel-body">
            <div id="tarjetas">
                <div class="row">
                    <div class="col-md-12">
                        <h5><b>Actualizado al {{$fecha_formato}} a las {{$hora_formato}}</b></h5>
                    </div>
                 </div>
                <div class="row" style="display: flex; flex-wrap: nowrap; overflow-x: auto;">
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-primary" style="background-color: rgb(223, 240, 216);">
                            <div class="panel-body text-center">
                                Total<br>Camas<br>
                                <i class="fa fa-bed fa-2x"></i><br>
                                {{$sumas['total']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-default">
                            <div class="panel-body text-center">
                                Camas<br>Libres<br>
                                <span class="glyphicon glyphicon-bed" style="font-size: 25px;"></span><br>
                                {{$sumas['c_libres']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-info" style="background-color: rgb(217, 237, 247);">
                            <div class="panel-body text-center">
                                Camas<br>Ocupadas<br>
                                <i class="fa fa-bed fa-2x"></i><br>
                                {{$sumas['c_ocupadas']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-danger" style="background-color: rgb(242, 222, 222);">
                            <div class="panel-body text-center">
                                Camas<br>Reparación<br>
                                <i class="fa fa-wrench fa-2x"></i><br>
                                {{$sumas['c_bloqueadas']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-default" style="background-color: rgb(176, 176, 176);">
                            <div class="panel-body text-center">
                                Camas<br>Malogradas<br>
                                <i class="fa fa-question-circle fa-2x"></i><br>
                                {{$sumas['c_sin_situacion']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-default" style="background-color: rgb(0, 112, 192);">
                            <div class="panel-body text-center">
                                Camas<br>Aislados<br>
                                <i class="fa fa-bed fa-2x"></i><br>
                                {{$sumas['c_aislados']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-default" style="background-color: rgb(186, 85, 211);">
                            <div class="panel-body text-center">
                                <span style="display: block; font-weight: bold;">Camas Cuidados<br>Complejos</span>
                                <i class="fa fa-heartbeat fa-2x"></i><br>
                                {{$sumas['c_cuidados_complejos']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2" style="min-width: 180px;">
                        <div class="panel panel-default" style="background-color: rgb(255, 140, 0);">
                            <div class="panel-body text-center">
                                Camas<br>Postquirúrgicos<br>
                                <i class="fa fa-medkit fa-2x"></i><br>
                                {{$sumas['c_postquirurgicos']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <div id="censo_cama_filtros">
                <div class="row">
                    <form id="frmlistadocenso" class="form-horizontal" method="post">
                        <div class="col-md-1"></div>
                        <div class="col-md-12">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id_user" value="{{ $id_user }}">
                            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{ $id_establecimiento_user}}">

                           <div class="row" style="display: flex; flex-wrap: nowrap; gap: 10px;">
                                <div class="col-md-2" style="display: flex; align-items: flex-end;">
                                    <button id="btnCensar" type="button" class="btn btn-success btn-block">CENSAR</button>
                                </div>

                                <div class="col-md-5">
                                    <label><b>Establecimiento</b></label>
                                    <select name="establecimiento" id="establecimiento" class="form-control input-sm" onchange="getAtencion()">
                                        <option value="">Seleccione</option>
                                        @if (isset($id_establecimiento))
                                            @foreach ($establecimientos as $item)
                                                <option value="{{$item->id}}" @if($item->id==$id_establecimiento) selected @endif>
                                                    {{$item->establecimiento}}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($establecimientos as $item)
                                                <option value="{{$item->id}}" @if($item->id==$id_establecimiento_user) selected @endif>
                                                    {{$item->establecimiento}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><b>Atención</b></label>
                                    <select name="tipo_atencion" id="tipo_atencion" class="form-control input-sm" onchange="getServicio()">
                                        <option value="">Seleccione</option>
                                        <?php $i=0; ?>
                                        @if (isset($tipo_atencion))
                                            @foreach ($tipo_atenciones as $item)
                                                <option value="{{$item->nombre_atencion}}" @if($item->nombre_atencion==$tipo_atencion) selected @endif>
                                                    {{$item->nombre_atencion}}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($tipo_atenciones as $item)
                                                <option value="{{$item->nombre_atencion}}" @if($i==0) selected @endif>
                                                    {{$item->nombre_atencion}}
                                                </option>
                                                <?php $i++; ?>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><b>Servicio</b></label>
                                    <select class="form-control input-sm" id="servicio_se" name="servicio_se">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label>Buscar</label>
                                    <input type="text" id="buscar_dni_nombre" name="buscar_dni_nombre" class="form-control input-sm">
                                </div>

                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <table id="tablaCensoCamas" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>CENSO</th>
                        <th style="width:10%;">Nro. CAMA</th>
                        <th>PACIENTE</th>
                        <th style="width:18%;">ESPECIALIDAD</th>
                        <th>UBICACION ACTUAL</th>
                        <th>ORIGEN</th>
                        <th style="width:14%;">FECHA CENSO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>

<!-- MODAL: Rotación de Camas -->
<div class="modal fade" id="modalRotarCama" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white d-flex align-items-center justify-content-between">
        <h3 class="panel-title mb-0">Rotación de Camas</h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="margin-top: -24px;">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formRotarCama">
          {{ csrf_field() }}
     
          <input type="hidden" id="id_ext" name="id_ext">
          <input type="hidden" id="tipo_atencion" name="tipo_atencion">
          <input type="hidden" id="id_servicio_actual" name="id_servicio_actual">
          <input type="hidden" id="id_consultorio" name="id_consultorio">
          <input type="hidden" id="id_establecimiento" name="id_establecimiento">
          
          <input type="hidden" id="id_ext_destino" name="id_ext_destino">
          <input type="hidden" id="id_evaluacion_destino" name="id_evaluacion_destino">
          <input type="hidden" id="id_cama" name="id_cama">

          <input type="hidden" id="id_prestacion" name="id_prestacion">
          <input type="hidden" id="id_evaluacion" name="id_evaluacion">

          <div class="form-group">
            <label for="fecha_rotar">Fecha Hora</label>
            <input type="datetime-local" class="form-control input-sm" id="fecha_rotar" name="fecha_rotar" placeholder="dd/mm/aaaa">
          </div>
          <div class="form-group">
            <label for="cama_anterior"># Cama Anterior</label>
            <select class="form-control" id="cama_anterior" name="cama_anterior" disabled style="background-color: #6c757d; color: white;">
              <option value="">Cargando...</option>
            </select>
          </div>
          <div class="form-group">
            <label for="cama_actual"># Cama Actual</label>
            <select id="cama_actual" name="cama_actual" class="form-control">
                <option value="">Seleccione cama</option>
            </select>
          </div>
          <div class="form-group">
            <label for="observacion">Observación</label>
            <textarea class="form-control" id="observacion" name="observacion" rows="2"></textarea>
          </div>
          <div class="form-group text-center" style="margin-top: 10px;">
            <button id="btnGuardarRotacion" type="submit" class="btn btn-primary btn-sm" style="background-color: #0d6efd; border-color: #0d6efd; width:100%;">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Transferencia -->
<div class="modal fade" id="modalTransferencia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white d-flex align-items-center justify-content-between">
        <h3 class="panel-title mb-0">Transferencia</h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="margin-top: -24px;">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formTransferencia">
          {{ csrf_field() }}
          <input type="hidden" id="id_ext_transferencia" name="id_ext">
          <input type="hidden" id="tipo_atencion_hidden" name="tipo_atencion_hidden">
          <input type="hidden" id="id_servicio_encargado" name="id_servicio_encargado">
          <input type="hidden" id="id_upss_encargado" name="id_upss_encargado">
          <input type="hidden" id="id_estado" name="id_estado">
          <input type="hidden" id="id_cama_actual_hidden" name="id_cama_actual_hidden">
          <input type="hidden" id="id_consultorio_hidden" name="id_consultorio_hidden">
          <input type="hidden" id="id_establecimiento_hidden" name="id_establecimiento_hidden">
          <input type="hidden" id="id_prestacion_hidden" name="id_prestacion_hidden">
          <input type="hidden" id="id_evaluacion_hidden" name="id_evaluacion_hidden">
          <div class="col-sm-12">
            <label for="fecha_rotar_transferencia">Fecha y hora</label>
            <input type="datetime-local" class="form-control input-sm" id="fecha_rotar_transferencia" name="fecha_rotar" placeholder="dd/mm/aaaa">
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <label>Upss a Cargo</label>
              <input type="text" class="form-control" id="nombre_upss" disabled style="background-color: #6c757d; color: white;">
            </div>
            <div class="col-sm-6">
              <label>Servicio Actual</label>
              <input type="text" class="form-control" id="nombre_servicio_actual" disabled style="background-color: #6c757d; color: white;">
            </div>
            <div class="col-sm-6">
              <label># Cama Actual</label>
              <input type="text" class="form-control" id="cama_actual_input" disabled style="background-color: #6c757d; color: white;">
            </div>
            <div class="col-sm-12">
              <label>Especialidad a Cargo</label>
              <select id="upss" class="form-control">
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Ubicacion del paciente</label>
              <select id="especialidad" class="form-control">
                <option value="">Seleccione</option>
               </select>
            </div>
            <div class="col-sm-6">
              <label># Cama</label>
              <select id="cama" class="form-control">
                <option value="">Seleccione</option>
               </select>
            </div>
            <div class="col-sm-12">
             <label for="medico">Médico que ordena</label>
             <select id="medico" name="medico" class="form-control input-sm">
               <option value="">Seleccione Médico</option>
             </select>
            </div>
            <div class="col-sm-12">
                <label for="observacion_transferencia">Observación</label>
                <textarea class="form-control" id="observacion_transferencia" name="observacion" rows="2"></textarea>
            </div>
            <div class="col-sm-12" style="margin-top: 12px;">
                <button id="btnGuardarTranseferencia" type="submit" class="btn btn-primary btn-sm" style="background-color: #0d6efd; border-color: #0d6efd; width:100%;">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Liberar Cama -->
<div class="modal fade" id="modalLiberarCama" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white d-flex align-items-center justify-content-between">
         <h3 class="panel-title mb-0">Liberar Camas</h3>
         <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="margin-top: -24px;">&times;</button>
      </div>
      <div class="modal-body" style="padding: 15px 30px;">
        <form id="formLiberarCama" class="form-horizontal" style="font-size: 12px;">
         {{ csrf_field() }}

          <input type="hidden" id="id_cama_actual_hidden_l" name="id_cama_actual">
          <input type="hidden" id="id_servicio_actual_hidden_l" name="id_servicio_actual">
          <div class="form-group">
            <label for="nombre_servicio_actual_l">Servicio:</label>
            <select class="form-control" id="nombre_servicio_actual_l" disabled>
              <option value="">Cargando...</option>
            </select>
          </div>
          <div class="form-group">
            <label for="cama_actual_input_l"># Cama:</label>
            <select class="form-control" id="cama_actual_input_l" disabled>
               <option value="">Cargando...</option>
            </select>
          </div>
          <div class="form-group">
            <label>
                <input type="checkbox" id="liberarCheck" name="confirmar_liberacion" style="margin-right: 6px;"> Liberar Cama
            </label>
          </div>
          <div class="form-group">
            <label for="observacion_transferencia">Observación</label>
            <textarea class="form-control" id="observacion_liberar" name="observacion" rows="2"></textarea>
          </div>
          <div class="form-group text-center" style="margin-top: 12px;">
            <button id="btnGuardarLiberacion" type="submit" class="btn btn-primary btn-sm" style="background-color: #0d6efd; border-color: #0d6efd; width:100%;">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Modificacion de Estado -->
<div class="modal fade" id="modalModificarEstado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white d-flex align-items-center justify-content-between">
        <h3 class="panel-title mb-0">Modificación de estado</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="margin-top: -24px;">&times;</button>
      </div>
      <div class="modal-body" style="padding: 15px 30px;">
        <form id="formModificarEstado" class="form-horizontal" style="font-size: 12px;">
          {{ csrf_field() }}
          <input type="hidden" id="id_cama_actual_hidden_m" name="id_cama_actual">
          <input type="hidden" id="id_servicio_actual_hidden_m" name="id_servicio_actual">
          <div class="form-group">
            <label for="cama_actual_input_m"># Cama:</label>
            <select class="form-control" id="cama_actual_input_m" disabled>
               <option value="">Cargando...</option>
            </select>
          </div>
          <div class="form-group">
            <label for="estado">Estado:</label>
              <select id="estado" name="estado" class="form-control input-sm">
                <option value="">Cargando...</option>
              </select>
          </div>
          <div class="form-group">
            <label for="observacion_estado">Observación</label>
            <textarea id="observacion_estado" name="observacion" class="form-control input-sm" rows="2" style="resize:none;"></textarea>
          </div>
          <div class="form-group text-center" style="margin-top: 10px;">
            <button id="btnGuardarModificacion" type="submit" class="btn btn-primary btn-sm" style="background-color: #0d6efd; border-color: #0d6efd; width:100%;">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



@include('frontend.includes.footer')
@endsection
