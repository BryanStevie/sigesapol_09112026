@extends('frontend.layouts.master')
@section('renderjs')
    <style>
        .filterable {
            margin-top: 0px;
        }

        .filterable .panel-heading .pull-right {
            margin-top: -20px;
        }

        .filterable .filters input[disabled] {
            background-color: transparent;
            border: none;
            cursor: auto;
            box-shadow: none;
            padding: 0;
            height: auto;
        }

        .filterable .filters input[disabled]::-webkit-input-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]::-moz-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]:-ms-input-placeholder {
            color: #333;
        }

        #global {
            height: 610px !important;
            width: auto;
            border: 1px solid #ddd;
            background: #f1f1f1;
            overflow-y: scroll !important;
        }

        .margin {

            margin-bottom: 20px;
        }

        .margin-buscar {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .panel {
            margin-bottom: 0px
        }

        .counts-loading {
            display: inline-block;
            width: 12px;
            height: 12px;
        }

        .counts-loading>img {
            width: 100%;
            height: 100%;
        }

        .imageloader {
            display: none;
        }

        .imageloader.active {
            display: block;
        }

        .btn-exportar-not-empty,
        .btn-exportar-empty {
            display: none;
        }

        .btn-exportar-not-empty.active,
        .btn-exportar-empty.active {
            display: inline-block;
        }

        .stock-not-empty,
        .stock-empty {
            display: none;
        }

        .stock-not-empty.active,
        .stock-empty.active {
            display: table;
        }

        .stock-not-empty>thead,
        tbody {
            width: 100%;
        }

        .panel-head_1 {
            display: none;
        }

        .panel-head_2 {
            display: none;
        }

        .panel-head_1.active {
            display: block;
        }

        .panel-head_2.active {
            display: block;
        }
    </style>

    <script>
        function calcularMesesTranscurridos(anioSeleccionado, mesSeleccionado) {
            const hoy = new Date();
            const anioActual = hoy.getFullYear();
            const mesActual = hoy.getMonth(); // 0 = enero, 4 = mayo

            // Ajustamos el mes seleccionado (de 1-12 a 0-11)
            const mesSeleccionadoIndex = mesSeleccionado - 1;

            // Calculamos la diferencia en meses
            const diferenciaMeses = (anioSeleccionado - anioActual) * 12 + (mesSeleccionadoIndex - mesActual);

            // Si es el mismo mes y año, debe devolver 0
            return Math.max(0, diferenciaMeses);
        }
        $(document).ready(function() {

            $('.filterable .btn-filter').click(function() {
                var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
                if ($filters.prop('disabled') == true) {
                    $filters.prop('disabled', false);
                    $filters.first().focus();
                } else {
                    $filters.val('').prop('disabled', true);
                    $tbody.find('.no-result').remove();
                    $tbody.find('tr').show();
                }
            });

            $('.filterable .filters input').keyup(function(e) {
                /* Ignore tab key */
                var code = e.keyCode || e.which;
                if (code == '9')
                    return;
                /* Useful DOM data and selectors */
                var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
                /* Dirtiest filter function ever ;) */
                var $filteredRows = $rows.filter(function() {
                    var value = $(this).find('td').eq(column).text().toLowerCase();
                    return value.indexOf(inputContent) === -1;
                });
                /* Clean previous no-result if exist */
                $table.find('tbody .no-result').remove();
                /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
                $rows.show();
                $filteredRows.hide();
                /* Prepend no-result row if all rows are filtered */
                if ($filteredRows.length === $rows.length) {
                    $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' +
                        $table.find('.filters th').length +
                        '">No se encuentra el laboratorio</td></tr>'));
                }
            });
        });
        $(document).ready(function() {
            function armarTabla(r, i) {
                const tableContent = `
                                        <tr>
                                            <td>${++i}</td>
                                            <td>${r['codigo_producto']}</a></td>
                                            <td>${r['prod_farmaceutico']}</td>                            
                                            <td>${r['abreviatura']}</td>
                                            <td>${r['lote']}</td>
                                            <td>${r['registro_sanitario']}</td>
                                            <td>${r['cantidad']}</td>
                                            <td>${r['fecha_vencimiento']}</td>
                                        </tr>`;
                const table = $('#finalTable');
                table.append(tableContent);
            }
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Stock</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                url: '/obtener_cantidades_para_leyenda_stock',
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    const allLoadersGif = document.querySelectorAll('.imageloader');
                    const emptyResultDivButtons = document.querySelector('.btn-exportar-empty');
                    const notEmptyResultDivButtons = document.querySelector('.btn-exportar-not-empty');
                    const mesesTranscurridos = !document.getElementById('mesesTranscurridos') ? '' :
                        document.getElementById('mesesTranscurridos').innerHTML;
                    allLoadersGif.forEach((loader) => {
                        loader.classList.remove('active');
                        const spanForRenderCountRes = loader.parentNode;
                        spanForRenderCountRes.innerHTML =
                            `(${response[spanForRenderCountRes.id]})`;
                    });
                    /* const spanTitle = document.querySelector('#cantidad_proximos_vencer2');
                    spanTitle.innerHTML = `(${response['cantidad_proximos_vencer']})`; */
                    /* Swal.fire({
                            icon: "success",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        }); */
                    $.ajax({
                        url: '/obtener_reporte_kardex_lotes2',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            'mesesTranscurridos': mesesTranscurridos,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            const emptyResultDiv = document.querySelector('.stock-empty');
                            const notEmptyResultDiv = document.querySelector(
                                '.stock-not-empty');
                            console.log(response);
                            Swal.fire({
                                icon: "success",
                                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                                confirmButtonText: "ENTENDIDO",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: "ENTENDIDO",
                            });
                            if (response.stocks?.length > 0) {
                                if (!(notEmptyResultDiv.classList.contains('active'))) {
                                    notEmptyResultDiv.classList.toggle('active');
                                }
                                if (emptyResultDiv.classList.contains('active')) {
                                    emptyResultDiv.classList.toggle('active');
                                }
                            } else {
                                if (!emptyResultDiv.classList.contains('active')) {
                                    emptyResultDiv.classList.toggle('active');
                                }
                                if (notEmptyResultDiv.classList.contains('active')) {
                                    notEmptyResultDiv.classList.toggle('active')
                                }
                            }
                            response.stocks?.forEach((row, i) => {
                                armarTabla(row, i);
                            });
                            $('#example').dataTable({
                                "language": {
                                    "sProcessing": "Procesando...",
                                    "sLengthMenu": "Mostrar _MENU_ registros",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Filtrar:",
                                    "sUrl": "",
                                    "sInfoThousands": ",",
                                    "sLoadingRecords": "Cargando...",
                                    "oPaginate": {
                                        "sFirst": "Primero",
                                        "sLast": "Último",
                                        "sNext": "Siguiente",
                                        "sPrevious": "Anterior"
                                    },
                                    "oAria": {
                                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                    }
                                }
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error:', textStatus, errorThrown);
                            Swal.fire({
                                icon: "error",
                                title: "ERROR ",
                                text: "Servicio no disponible. intentelo en un minutos.",
                            });
                        },
                        complete: function(jqXHR, textStatus) {
                            Swal.hideLoading();
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error:', textStatus, errorThrown);
                    console.log(jqXHR);
                    Swal.fire({
                        icon: "error",
                        title: "ERROR ",
                        text: "Servicio no disponible. intentelo en un minutos.",
                    });
                },
            });
            $('#btnExportarEnBaseFecha').on('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

                // Mostrar el modal de carga
                Swal.fire({
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Reporte EXCEL</span></div>",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                });
                // Iniciar la descarga
                var url = $(this).attr('href');

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Crear un enlace temporal para la descarga
                        var downloadUrl = URL.createObjectURL(blob);
                        var link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download =
                            'Reporte de Productos cerca a vencer'; // Puedes establecer el nombre del archivo aquí si lo deseas
                        document.body.appendChild(link);
                        link.click();

                        // Remover el enlace temporal
                        document.body.removeChild(link);
                        URL.revokeObjectURL(downloadUrl);

                        // Ocultar el modal de carga
                        Swal.fire({
                            icon: "success",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se descargo el archivo correctamente, revisar la carpeta de descargas.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la descarga:', error);
                        Swal.fire({
                            icon: "error",
                            title: "ERROR ",
                            text: "Servicio no disponible. intentelo en un minutos.",
                        });
                    });
            });
            $('#btnExportarEnBaseFechav2').on('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

                // Mostrar el modal de carga
                Swal.fire({
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Reporte EXCEL</span></div>",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                });
                // Iniciar la descarga
                const id_farmacia = $("#id_farmacia").val();
                const meses = calcularMesesTranscurridos($("#ano").val(), $("#mes").val());
                /* window.location.href = `/imprimir_productos_proximos_vencer/${ id_farmacia }/${ meses }`; */
                $.ajax({
                    url: `/imprimir_productos_proximos_vencer/${id_farmacia}/${meses}`,
                    method: 'GET',
                    success: function(response) {
                        // Llamada exitosa, redirigir para descargar el archivo
                        window.location.href =
                            `/imprimir_productos_proximos_vencer/${id_farmacia}/${meses}`;

                        // Cerrar el modal de carga
                        Swal.close();
                    },
                    error: function(xhr, status, error) {
                        // En caso de error, cerrar el modal y mostrar mensaje de error
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema generando el reporte, intente nuevamente.',
                            icon: 'error'
                        });
                    }
                });
            });

            function mesesToFechaMmYy(dias) {
                var fechaActual = new Date();
                var milisegundosPorDia = 24 * 60 * 60 * 1000;
                var tiempoAdicional = dias * milisegundosPorDia;
                var nuevaFecha = new Date(fechaActual.getTime() + tiempoAdicional);
                var mes = nuevaFecha.getMonth() + 1;
                var anio = nuevaFecha.getFullYear();
                return `${mes}-${anio}`;
            }

            function actualizarMesesTranscurridosParaExportar() {
                const fechaSeleccionada =
                    `${$("#ano").val()}-${$("#mes").val()}-${$("#mes").val() == '02' ? '28': '30'}`;
                var fechaActual = new Date();
                var fechaFutura = new Date(fechaSeleccionada);
                var anioActual = fechaActual.getFullYear();
                var mesActual = fechaActual.getMonth() +
                    1; // Los meses en JavaScript van de 0 a 11, por eso sumamos 1
                var anioFuturo = fechaFutura.getFullYear();
                var mesFuturo = fechaFutura.getMonth() + 1;
                var diferenciaAnios = anioFuturo - anioActual;
                var diferenciaMeses = (diferenciaAnios * 12) + (mesFuturo - mesActual);
                const exportar = document.getElementById('btnExportarEnBaseFecha');
                const urlObj = new URL(exportar.href);
                const path = urlObj.pathname;
                const nuevoPath = [path.split('/')[0], path.split('/')[1]].join('').concat(
                    `/{{ $id_farmacia }}/${diferenciaMeses}`);
                urlObj.pathname = nuevoPath;
                exportar.href = urlObj.href;
            }

            const btnBuscarProxVenc = document.getElementById('btnBuscarProxVenc');
            btnBuscarProxVenc.addEventListener('click', (e) => {
                $("#example").dataTable().fnDestroy();
                const table = document.getElementById('finalTable');
                table.innerHTML = '';
                const head1 = document.querySelector('.panel-head_1');
                const head2 = document.querySelector('.panel-head_2');
                const nodeToSet = document.getElementById('formFecha');
                if (head1.classList.contains('active')) {
                    head1.classList.toggle('active');
                    head2.classList.toggle('active');
                }
                nodeToSet.innerHTML =
                    `${$("#mes").val()}-${$("#ano").val()} <span style="font-size:11px;">(mm-aaaa)</span>`;
                Swal.fire({
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Stock</span></div>",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                });
                /* actualizarMesesTranscurridosParaExportar(); */
                $.ajax({
                    url: '/obtener_reporte_kardex_lotes2',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        ano: $("#ano").val(),
                        mes: $("#mes").val(),
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        const emptyResultDiv = document.querySelector('.stock-empty');
                        const notEmptyResultDiv = document.querySelector('.stock-not-empty');
                        console.log(response);
                        Swal.fire({
                            icon: "success",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                            confirmButtonText: "ENTENDIDO",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: "ENTENDIDO",
                        });
                        if (response.stocks?.length > 0) {
                            if (!(notEmptyResultDiv.classList.contains('active'))) {
                                notEmptyResultDiv.classList.toggle('active');
                            }
                            if (emptyResultDiv.classList.contains('active')) {
                                emptyResultDiv.classList.toggle('active');
                            }
                        } else {
                            if (!emptyResultDiv.classList.contains('active')) {
                                emptyResultDiv.classList.toggle('active');
                            }
                            if (notEmptyResultDiv.classList.contains('active')) {
                                notEmptyResultDiv.classList.toggle('active')
                            }
                        }
                        response.stocks?.forEach((row, i) => {
                            armarTabla(row, i);
                        });
                        $('#example').dataTable({
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Filtrar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', textStatus, errorThrown);
                        Swal.fire({
                            icon: "error",
                            title: "ERROR ",
                            text: "Servicio no disponible. intentelo en un minutos.",
                        });
                    },
                    complete: function(jqXHR, textStatus) {
                        Swal.hideLoading();
                    }
                });
            });

            function disabledOutdatedMonths() {
                const anio = $("#ano").val();
                const selectMonth = document.getElementById('mes');
                let contMes = new Date().getMonth() + 1;
                var meses = [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ];
                const actualOption = `<option value="{{ date('m') }}">Actual</option>`;
                selectMonth.innerHTML = actualOption;
                if (anio == new Date().getFullYear()) {
                    while (contMes < 12) {
                        contMes++;
                        const option =
                            `<option value="${contMes < 10 ? "0".concat(contMes) : contMes}">${meses[contMes - 1]}</option>`;
                        selectMonth.innerHTML = selectMonth.innerHTML.concat(option);
                    }
                } else {
                    selectMonth.innerHTML = `
                            <option value="{{ date('m') }}">Actual</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
          `;
                }
            }
            disabledOutdatedMonths();
            const selectAnio = document.getElementById('ano');
            selectAnio.addEventListener('change', (e) => {
                disabledOutdatedMonths();
            });

        });
    </script>
@endsection
@section('content')
    <div class="container">
        @if (isset($meses))
            <span hidden id="mesesTranscurridos">{{ $meses }}</span>
        @endif
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    {{ $farma->nombre }}
                </div>
                <?php
                $pagRoute = '/stocksbyFamarcias/' . $id_farmacia;
                ?>

                <div class="panel-body">

                    <div class="col-md-12">
                        <b>Leyenda:</b>
                    </div>

                    <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                        title="<em>Stock Local del Centro de Atenci&oacute;n</em><br>">
                        <i class="fa fa-medkit" style="color:#000000"></i> Stock en General
                    </div>
                    <div class="col-md-2" style="padding-left:18px">
                        <a href="<?php echo $pagRoute; ?>" class="btn btn-default btn-xs">ver</a>
                    </div>


                    <div class="row">

                        <div class="col-md-11 col-md-offset-1">

                            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                                title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                <i class="fa fa-flag" style="color:#FF0000"></i> Desabastecido
                            </div>
                            <div class="col-md-2">
                                <a href="<?php echo $pagRoute; ?>/1" class="btn btn-default btn-xs">ver</a>
                            </div>
                            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                                title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                <i class="fa fa-flag" style="color:#ED7D31"></i> SobreStock
                            </div>
                            <div class="col-md-2">
                                <a href="<?php echo $pagRoute; ?>/2" class="btn btn-default btn-xs">ver</a>
                            </div>
                            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                                title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                <i class="fa fa-flag" style="color:#70AD47"></i> NormoStock
                            </div>
                            <div class="col-md-2">
                                <a href="<?php echo $pagRoute; ?>/3" class="btn btn-default btn-xs">ver</a>
                            </div>
                            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                                title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                <i class="fa fa-flag" style="color:#FFC000"></i> SubStock
                            </div>
                            <div class="col-md-2">
                                <a href="<?php echo $pagRoute; ?>/4" class="btn btn-default btn-xs">ver</a>
                            </div>
                            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                                title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                <i class="fa fa-flag" style="color:#4472C4"></i> Sin Rotaci&oacute;n
                            </div>
                            <div class="col-md-2">
                                <a href="<?php echo $pagRoute; ?>/5" class="btn btn-default btn-xs">ver</a>
                            </div>

                        </div>

                    </div>



                    <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
                        title="<em>Stock por lotes</em>">
                        <i class="fa fa-medkit" style="color: orange"></i> Stock por Lotes
                    </div>
                    <div class="col-md-2" style="padding-left:18px">
                        <a href="/productos_porlotes/" class="btn btn-default btn-xs">ver</a>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Clasificación de Stock por Tipo de Producto
                </div>
                <div class="panel-body" style="font-size:11px">
                    <div class="col-md-12">
                        <b>Leyenda:</b>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Medicamentos <span
                            class="counts-loading active" id="countMedicamento"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/1" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Biomédicos <span
                            class="counts-loading active" id="countbiomedico"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/3" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material Dental <span
                            class="counts-loading active" id="countMaterialDental"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/5" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material de Laboratorio
                        <span class="counts-loading active" id="countMaterialLaboratorio"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/4" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Insumos de Laboratorio
                        <span class="counts-loading active" id="countinsumolaboratorio"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/2" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Fotográfico y Fonot.
                        <span class="counts-loading active" id="countMatFotograficoAndFonotecnico"><img
                                class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="loader-count col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Quirurgico
                        <span class="counts-loading active" id="countMaterialQuirurgico"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="loader-count col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/7" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="loader-count col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos Afines <span
                            class="counts-loading active" id="countProductosAfines"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="loader-count col-md-2">
                        <a href="/productos_por_tipo/{{ $id_farmacia }}/8" class="btn btn-default btn-xs">ver</a>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Alertas de Vencimiento
                </div>
                <div class="panel-body" style="font-size:11px">
                    <div class="col-md-12">
                        <b>Leyenda:</b>
                    </div>
                    <div class="loader-count col-md-10">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Prod. próximos a vencer (En los
                        Proximos 06 meses)
                        <span class="counts-loading active" id="cantidad_proximos_vencer"><img class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2">
                        <a href="/productos_proximos_vencer/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                    </div>
                    <div class="loader-count col-md-10" style="margin-top: 5px">
                        <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos vencidos
                        <span class="counts-loading active" id="cantidad_productos_vencidos"><img
                                class="imageloader active"
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif"
                                alt=""></span>
                    </div>
                    <div class="col-md-2" style="margin-top: 5px">
                        <a href="/productos_vencidos/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<h4>
                                Stocks - {{ $farma->nombre }}
                                <span class="pull-right">Hoy: {{ $fecha }}</span>
                            </h4>-->
        <div class="row col-md-9">

            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">STOCK LOCAL DE CENTRO DE ATENCIÓN - PROD. PRÓXIMOS A VENCER</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{ $id_farmacia }}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="buscar_vencidos" action="{{ route('buscar_prodvencidos_porfecha') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input value="{{ $id_farmacia }}" name="id_farmacia" id="id_farmacia" type="hidden">
                        <p>Buscar productos vencidos por año y/o mes</p>
                        <label class="col-md-1 control-label" for="textinput">Año(s)</label>
                        <div class="col-md-2">
                            <select name="ano" id="ano">
                                <option value="{{ date('Y') }}">Actual</option>
                                <option value="{{ date('Y') + 1 }}"><?php echo date('Y') + 1; ?></option>
                                <option value="{{ date('Y') + 2 }}"><?php echo date('Y') + 2; ?></option>
                                <option value="{{ date('Y') + 3 }}"><?php echo date('Y') + 3; ?></option>
                                <option value="{{ date('Y') + 4 }}"><?php echo date('Y') + 4; ?></option>
                                <option value="{{ date('Y') + 5 }}"><?php echo date('Y') + 5; ?></option>
                                <option value="{{ date('Y') + 6 }}"><?php echo date('Y') + 6; ?></option>
                                <option value="{{ date('Y') + 7 }}"><?php echo date('Y') + 7; ?></option>
                                <option value="{{ date('Y') + 8 }}"><?php echo date('Y') + 8; ?></option>
                                <option value="{{ date('Y') + 9 }}"><?php echo date('Y') + 9; ?></option>
                                <option value="{{ date('Y') + 10 }}"><?php echo date('Y') + 10; ?></option>
                            </select>
                        </div>
                        <label class="col-md-2 control-label" for="textinput">mes(es)</label>
                        <div class="col-md-2">
                            <select name="mes" id="mes">
                            </select>
                        </div>
                        <div class="col-md-2"><input id="btnBuscarProxVenc" type="button" class="btn btn-info btn-xs"
                                value="Buscar"></div>
                        <div class="pull-right">
                            {{-- <a hidden id="btnExportarEnBaseFecha" class="btn btn-success btn-xs" href="/imprimir_productos_proximos_vencer/{{ $id_farmacia }}/{{$meses}}"><i class="fa fa-file-excel-o"></i> Exportar</a>  --}}
                            <button id="btnExportarEnBaseFechav2" type="button" class="btn btn-success btn-xs">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title panel-head_1 active" id="">PROD. PRÓXIMOS A VENCER @if (isset($meses))
                            {{ 'En los Proximos ' . $meses . ' meses' }}
                        @else
                            {{ '(En los Proximos 06 meses)' }}
                        @endif {{-- <span class="counts-loading active" id="cantidad_proximos_vencer2"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span> --}}</h3>
                    <h3 class="panel-title panel-head_2" id="">PROD. A VENCERCE ANTES DEL <span
                            id="formFecha"></span></h3>
                </div>
                <div id="global">
                    <br />
                    <table id="example" class="table stock-not-empty" style="font-size: 11px !important;">
                        <thead>
                            <tr class="filters">
                                <th>Nro</th>
                                <th>Código</th>
                                <th>Producto Farmacéutico o Dispositivo Médico</th>
                                <th>Unidad</th>
                                <th>Lote</th>
                                <th>R.S.</th>
                                <th>Stock Actual</th>
                                <th>Fec. Vencimiento <br> (mm-aaaa)</th>
                            </tr>
                        </thead>
                        <tbody id="finalTable" style="font-size: 11px !important;">
                        </tbody>
                    </table>
                    <div class="stock-empty col-sm-12 col-md-12" style="margin-top:15px">
                        <div class="alert alert-warning">
                            <span class="glyphicon glyphicon-info-sign"></span> <strong>Aviso</strong>
                            <hr class="message-inner-separator">
                            <p>No existe registros de productos proximos a vencer...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.includes.footer')
@endsection
