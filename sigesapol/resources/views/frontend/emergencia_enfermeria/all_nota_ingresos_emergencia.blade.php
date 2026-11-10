<?php ini_set('memory_limit', '-1');
set_time_limit(0); ?>
@extends('frontend.layouts.master3')
@section('renderjs')
    <style>
        td {
            border-top: 0px solid #ddd !important;
        }

        .form-control:focus {
            box-shadow: inset 0 1px 1px white, 0 0 8px white !important;
        }

        .solomayusculas {
            text-transform: uppercase;
        }

        .uneditable-input:focus {
            box-shadow: 0 1px 1px #FFF inset, 0 0 8px #FFF !important;
        }

        a:link {
            text-decoration: none !important;
        }

        a:visited {
            text-decoration: none !important;
        }


        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10000000;
            display: none;
            float: left;
            min-width: 400px;
            padding: 5px 7px;
            margin: 2px 0 0;
            list-style: none;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

        .ui-autocomplete>li>div {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333333;
            white-space: nowrap;
        }

        #tblListadoEnfermeria td {
            height: 80px;
            /* Ajusta la altura */
        }

        /* Estilos Generales para Botones */
        .enf_btn {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .enf_btn:hover {
            text-decoration: none;
        }

        .enf_btn:focus {
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Botón Celeste */
        .enf_btn-celeste {
            color: #fff;
            background-color: #00BFFF;
            border-color: #00BFFF;
        }

        .enf_btn-celeste:hover {
            color: #fff;
            background-color: #0084ff;
            border-color: #0084ff;
        }

        .enf_btn-verde-oscuro {
            color: #fff;
            background-color: #025f26;
            border-color: #025f26;
        }

        .enf_btn-verde-oscuro:hover {
            color: #fff;
            background-color: #004a1d;
            border-color: #004a1d;
        }


        /* Botón Secundario */
        .enf_btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .enf_btn-secondary:hover {
            color: #fff;
            background-color: #545b62;
            border-color: #4e555b;
        }

        /* Botón de Éxito */
        .enf_btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }

        .enf_btn-success:hover {
            color: #fff;
            background-color: #218838;
            border-color: #1e7e34;
        }

        /* Botón de Peligro */
        .enf_btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .enf_btn-danger:hover {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Botón de Advertencia */
        .enf_btn-warning {
            color: #212529;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .enf_btn-warning:hover {
            color: #212529;
            background-color: #e0a800;
            border-color: #d39e00;
        }

        /* Botón de Información */
        .enf_btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .enf_btn-info:hover {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b;
        }

        /* Botón Claro */
        .enf_btn-light {
            color: #212529;
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .enf_btn-light:hover {
            color: #212529;
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }

        /* Botón Oscuro */
        .enf_btn-dark {
            color: #fff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .enf_btn-dark:hover {
            color: #fff;
            background-color: #23272b;
            border-color: #1d2124;
        }

        /* Botón de Enlace */
        .enf_btn-link {
            font-weight: 400;
            color: #007bff;
            background-color: transparent;
            border: none;
        }

        .enf_btn-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        #hemoglucotest_cuidado_enfermero_kardex_div {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
        }

        #hemoglucotest_cuidado_enfermero_kardex_div > label {
            flex: 0 0 25%;
            width: 25%;
            margin: 0;
        }

        #container_horas {
            display: flex;
            min-width: 0;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 5px;
        }

        #container_horas .col-sm-1 {
            flex: 0 0 100px;
            width: 100px;
            padding-left: 5px;
            padding-right: 5px;
        }

        #container_horas input {
            width: 100%;
        }

        #hemoglucotest_cuidado_enfermero_kardex_div .container_add {
            display: flex;
            flex: 0 0 auto;
            gap: 5px;
            margin-left: 5px;
            white-space: nowrap;
        }
    </style>
    <style>
        #tblListadoEnfermeria th,
        #tblListadoEnfermeria td {
            border: 1px solid rgba(193, 231, 243, 0.5);
            /* Color celeste claro */
            padding: 5px;
            /* Ajusta el padding */
            text-align: center;
            /* Centrar texto */
        }

        #tblListadoEnfermeria th {
            background-color: #f9f9f9;
            /* Opcional: color de fondo para encabezados */
        }

        #tblListadoEnfermeria th:last-child,
        #tblListadoEnfermeria td:last-child {
            border-right: none;
        }

        #tblListadoEnfermeria th:first-child,
        #tblListadoEnfermeria td:first-child {
            border-left: none;
        }

        /* .text-left {
                                                                                                  text-align: left;
                                                                                                 } */
        .table-responsive {
            padding: 0;
            /* Elimina el padding del contenedor */
        }

        .borde-alerta {
            border-color: red !important;
            border: 2px red solid !important;
            outline: 0;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
        }

        .table-sortable tbody tr {
            cursor: move;
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

        .btn-app {
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
            position: relative;
            text-align: center;
            color: #FFF;
            background-color: #00a65a;
        }

        .btn-app:hover {
            color: #FFF;
        }

        .btn-app>.badge {
            position: absolute;
            color: white;
            padding: 2px 5px;
            top: -8px;
            right: -15px;
            font-size: 14px;
            font-weight: 400;
        }

        .error {
            color: red;
        }

        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10000000;
            display: none;
            float: left;
            min-width: 400px;
            padding: 5px 7px;
            margin: 2px 0 0;
            list-style: none;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

        .ui-autocomplete>li>div {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333333;
            white-space: nowrap;
        }

        .enf_btn-success:hover {
            color: #fff;
            background-color: #218838;
            border-color: #1e7e34;
        }

        /* Botón de Peligro */
        .enf_btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .enf_btn-danger:hover {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Botón de Advertencia */
        .enf_btn-warning {
            color: #212529;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .enf_btn-warning:hover {
            color: #212529;
            background-color: #e0a800;
            border-color: #d39e00;
        }

        /* Botón de Información */
        .enf_btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .enf_btn-info:hover {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b;
        }

        /* Botón Claro */
        .enf_btn-light {
            color: #212529;
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .enf_btn-light:hover {
            color: #212529;
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }

        /* Botón Oscuro */
        .enf_btn-dark {
            color: #fff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .enf_btn-dark:hover {
            color: #fff;
            background-color: #23272b;
            border-color: #1d2124;
        }

        /* Botón de Enlace */
        .enf_btn-link {
            font-weight: 400;
            color: #007bff;
            background-color: transparent;
            border: none;
        }

        .enf_btn-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .disabled-link {
            pointer-events: none;
            cursor: default;
            color: #B0B0B0;
            text-decoration: none;
            opacity: 0.6;
        }

        .disabled-link i {
            opacity: 0.6;
            /* Opacidad para el ícono */
            pointer-events: none;
            /* Evitar eventos de puntero en el ícono */
        }

        .disabled-link {
            color: gray;
            text-decoration: none;
            cursor: default;
        }

        .cell-pupila {
            font-size: 12.5px;
        }

        .cell-pupila>input {
            margin: 0;
        }

        .cell-pupila>input[type="text"] {
            height: 25px;
            width: 80px;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }
    </style>
    @include('frontend.emergencia_enfermeria.firma_enfermeria_handler')
    <!-- listar_parametros de configuracion -->
    @include('frontend.emergencia_enfermeria.listar_parametros')
    {{-- @include('frontend.emergencia_enfermeria.nota_ingreso.function_nota_ingreso.puntos_grafico.control_de_puntos') --}}

    <!-- alergias -->
    @include('frontend.emergencia_enfermeria.alergias.function_alergias.listar_alergias')
    @include('frontend.emergencia_enfermeria.alergias.function_alergias.registrar_alergias')
    @include('frontend.emergencia_enfermeria.alergias.function_alergias.ver_alergias')
    @include('frontend.emergencia_enfermeria.alergias.function_alergias.editar_alergias')

    <!-- monitoreo -->
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.listar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.registrar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.actualizar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.detalle_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.cpot')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.morse')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.norton')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.tiss')
    <!-- kadex -->
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.listar_kardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.registrar_kardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.editar_medicamento')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.kardex.horario_de_kardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.kardex.generarHorario_x_Medicamento')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.kardex.suspender_MedicamentoKardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.invasivos.suspender_pro_invasivo')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.listar_interconsulta_kardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.listar_ex_cul_ven')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.ver_detalle_kardex')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.formulario_medicamento')
    @include('frontend.emergencia_enfermeria.kardex.function_kardex.shared')
    <!-- resultados -->
    {{-- @include('frontend.emergencia_enfermeria.resultados.function_resultados.listar_resultados') --}}
    <!-- balance hídrico -->
    @include('frontend.emergencia_enfermeria.balance_hidrico.function_balance_hidrico.listar_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.function_balance_hidrico.registrar_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.function_balance_hidrico.hoja_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.function_balance_hidrico.editar_balance_hidrico')
    <!-- hoja gràfica -->
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.graficos.barras.grafico_pa')
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.graficos.lineal.grafico_temp')
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.listar_hoja_grafica')
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.registrar_hoja_grafica')
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.visualizar_hoja_grafica')
    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.ver_registro_grafico')

    <!-- CPT -->
    @include('frontend.emergencia_enfermeria.cpt.function.listar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.function.registrar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.function.editar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.function.ver_cpt')
    @include('frontend.emergencia_enfermeria.cpt.function.anular_cpt')

    @include('frontend.emergencia_enfermeria.hoja_grafica.function_hoja_grafica.ver_registro_nuevo')
    <!-- evoluciones -->
    @include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.listar_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.anular_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.registrar_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.ver_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.editar_evoluciones')
    <!-- incidentes -->
    @include('frontend.emergencia_enfermeria.incidentes.function_incidentes.listar_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.function_incidentes.registrar_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.function_incidentes.ver_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.function_incidentes.editar_incidencias')
    <!-- hoja conduccion -->
    @include('frontend.emergencia_enfermeria.hoja_conduccion.function_hoja_conduccion.hoja_conduccion_enfermeria')
    @include('frontend.emergencia_enfermeria.hoja_conduccion.function_hoja_conduccion.hoja_conduccion_enfermeria_registro')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.rass')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.glasgow')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.actualizar_glasgow')
    @include('frontend.emergencia_enfermeria.monitoreo.function_monitoreo.escalas.actualizar_rass')

    <!-- Historial -->
    @include('frontend.emergencia_enfermeria.historial.functions.listar_historial')

    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <style>
        #tblListadoEnfermeria th,
        #tblListadoEnfermeria td {
            border: 1px solid rgba(193, 231, 243, 0.5);
            /* Color celeste claro */
            padding: 5px;
            /* Ajusta el padding */
            text-align: center;
            /* Centrar texto */
        }

        #tblListadoEnfermeria th {
            background-color: #f9f9f9;
            /* Opcional: color de fondo para encabezados */
        }

        #tblListadoEnfermeria th:last-child,
        #tblListadoEnfermeria td:last-child {
            border-right: none;
        }

        #tblListadoEnfermeria th:first-child,
        #tblListadoEnfermeria td:first-child {
            border-left: none;
        }

        /* .text-left {
                                                                                               text-align: left;
                                                                                              } */
        .table-responsive {
            padding: 0;
            /* Elimina el padding del contenedor */
        }

        .borde-alerta {
            border-color: red !important;
            border: 2px red solid !important;
            outline: 0;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
        }

        .table-sortable tbody tr {
            cursor: move;
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

        .btn-app {
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
            position: relative;
            text-align: center;
            color: #FFF;
            background-color: #00a65a;
        }

        .btn-app:hover {
            color: #FFF;
        }

        .btn-app>.badge {
            position: absolute;
            color: white;
            padding: 2px 5px;
            top: -8px;
            right: -15px;
            font-size: 14px;
            font-weight: 400;
        }

        .error {
            color: red;
        }

        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10000000;
            display: none;
            float: left;
            min-width: 400px;
            padding: 5px 7px;
            margin: 2px 0 0;
            list-style: none;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

        .ui-autocomplete>li>div {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333333;
            white-space: nowrap;
        }

        .glasgow {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }

        .modal-tr>td {
            width: 50%;
        }

        #modalListarEscalaRass {
            z-index: 1000000;
        }

        #modalListarEscalaGlasgow {
            z-index: 1000000;
        }

        #modalListarEscalaGlasgow_up {
            z-index: 1000000;
        }

        #modalListarEscalaRass_up {
            z-index: 1000000;
        }

        td>input,
        select {
            width: 100%;
            height: 30px;
        }

        /* Ajusta la altura del contenedor de la selección */
        .select2-selection__rendered {
            height: 30px;
            /* Ajusta el valor según tus necesidades */
            line-height: 30px;
            /* Alinea el texto verticalmente */
        }

        /* Ajusta la altura del contenedor de selección múltiple */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            max-height: 30px;
            /* Ajusta el valor según tus necesidades */
        }

        .row-monitoreo {
            height: 125px;
        }

        .funciones>a {
            width: 25px;
            height: 25px;
        }

        .funciones {
            display: flex;
            flex-direction: column;
            padding: 0 !important;
            height: 100%;
            justify-content: center;
            align-items: center;
            gap: 3px;
            margin-top: 5px;
        }

        table {
            border: 0;
        }

        tbody {
            border: 0;
        }

        .container-filters {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .monitoreo-td {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .modal-tr {
            width: 100%;
            display: flex;
        }

        .modal-tr>th {
            width: 50%;
        }

        .monitoreo-td>* {
            width: 100%;
        }

        .desc_glasgow {
            font-weight: 100;
        }

        .glasgow>* {
            width: auto;
        }

        .glasgow>input {
            width: calc(100% - 38px);
        }

        #modalListarEscalaGlasgowMonitoreo {
            z-index: 1000000000;
        }

        .modal {
            overflow: auto;
        }

        .modal-open .modal {
            overflow: auto;
        }

        .modal-xl {
            width: 1200px;
        }
    </style>
    <script>
        $('.select2').select2();

        const nivelIpress = $("#nivel_ipress").val();
        const columns = [{
                "mRender": function(data, type, row) {
                    var id = "";
                    if (row.id != null) id = row.id;
                    return id;
                },
                "bSortable": false,
                "aTargets": [0]
            },
            {
                "mRender": function(data, type, row) {
                    var fecha_ingreso = row.fecha_ingreso != null ? row.fecha_ingreso : "";
                    var fecha_hora = row.fecha_hora != null ? row.fecha_hora : "";
                    var fecha_alta = row.fecha_alta != null ? row.fecha_alta : "";
                    var hora_alta = row.hora_alta != null ? row.hora_alta : "";
                    return "<div style='text-align: left;'>Ingreso : " + fecha_ingreso +
                        " <br><span style='font-weight: bold; color: black;'>EGRESO:</span>" +
                        fecha_hora + "<br>" + fecha_alta + "<br>" + hora_alta + "</div>";
                },
                "bSortable": false,
                "aTargets": [1]
            },
            {
                "mRender": function(data, type, row) {
                    var fecha_ingreso = "";
                    if (row.fecha_ingreso != null) {
                        var fecha_hora = row.fecha_hora_enfermeria?.split(".");
                        fecha_ingreso = fecha_hora ? fecha_hora[0] :
                            "<span>Nota no registrada</span>";
                    }
                    return fecha_ingreso;
                },
                "bSortable": false,
                "aTargets": [2]
            },
            {
                "mRender": function(data, type, row) {
                    var descripcion1 = "";
                    if (row.descripcion != null) {
                        var descripcion = row.descripcion.split(" ");
                        var des_prioridades = row.des_prioridades.split("-");
                        descripcion1 = "<div style='text-align: left;'>INGRESO :" + descripcion[0] +
                            "<br> <span style='font-weight: bold; color: black;'>PRIORIDAD :</span>" +
                            des_prioridades;
                    }
                    return descripcion1;
                },
                "bSortable": false,
                "aTargets": [3]
            },
            {
                "mRender": function(data, type, row) {
                    var tipo_doc = row.tipo_doc != null ? row.tipo_doc : "";
                    var dni_beneficiario = row.dni_beneficiario != null ? row.dni_beneficiario : "";
                    var nombre_completo = (row.nombre_beneficiario != null ? row
                        .nombre_beneficiario : "") + ' ' + (row.paterno_beneficiario != null ?
                        row.paterno_beneficiario : "") + ' ' + (row.materno_beneficiario !=
                        null ? row.materno_beneficiario : "");
                    var parentesco = row.des_parentesco != null ? row.des_parentesco : "";
                    var sexo_as = row.sexo_as != null ? row.sexo_as : "";
                    var edad = row.edad != null ? row.edad : "";
                    var cip = row.cip != null ? row.cip : "";
                    var situacion_paciente = row.situacion_paciente != null ? row
                        .situacion_paciente : "";
                    var grado_as = row.grado_as != null ? row.grado_as : "";
                    var referido = row.id_motivo_ingreso == 4 ? "SI" : "NO";
                    return "<div style='text-align: left;'>Documento : " + tipo_doc + " : " +
                        dni_beneficiario +
                        "<br> <span style='font-weight: bold; color: black;'>BENEFICIARIO :</span> " +
                        nombre_completo +
                        "<br> <span style='font-weight: bold; color: black;'>PARENTESCO :</span>: " +
                        parentesco +
                        "<br> <span style='font-weight: bold; color: black;'>SEXO :</span> " +
                        sexo_as +
                        " <br> <span style='font-weight: bold; color: black;'>EDAD :</span> " +
                        edad + "<br> <span style='font-weight: bold; color: black;'>CIP :</span> " +
                        cip + "<br> <span style='font-weight: bold; color: black;'>GRADO :</span>" +
                        grado_as +
                        "<br> <span style='font-weight: bold; color: black;'>SITUACION :</span>" +
                        situacion_paciente +
                        "<br> <span style='font-weight: bold; color: black;'>REFERIDO :</span>" +
                        referido + "</div>";
                },
                "bSortable": false,
                "aTargets": [4]
            },
            {
                "mRender": function(data, type, row) {
                    var dni_conducido = row.dni_conducido != null ? row.dni_conducido : "";
                    var nombre_conducido = row.nombre_conducido != null ? row.nombre_conducido : "";
                    var telf_conducido = row.telf_conducido != null ? row.telf_conducido : "";
                    var tipo_des = row.tipo_des != null ? row.tipo_des : "";
                    if (dni_conducido === "" && nombre_conducido === "") {
                        return "<div style='text-align: left;'>VINO SOLO : " + "<br>Contacto  " +
                            telf_conducido + "</div>";
                    } else {
                        return "<div style='text-align: left;'>Documento : " + dni_conducido +
                            "<br> <span style='font-weight: bold; color: black;'>Acompañante :</span>" +
                            nombre_conducido +
                            "<br> <span style='font-weight: bold; color: black;'>Contacto :</span> " +
                            telf_conducido +
                            "<br> <span style='font-weight: bold; color: black;'>Parentesco :</span> " +
                            tipo_des + "</div>";
                    }
                },
                "bSortable": false,
                "aTargets": [5]
            },
            {
                "mRender": function(data, type, row) {
                    var medico = "";
                    if (row.tiene_prestacion) {
                        medico = row.nombre_medico + ' ' + (row.paterno_medico != null ? row
                            .paterno_medico : "") + ' ' + (
                            row.materno_medico != null ? row.materno_medico : "");
                    } else {
                        medico = "<span>No se encuentra atendido.</span>"
                    }
                    var nombre_cuarto = row.nombre_cuarto != null ? row.nombre_cuarto : "";
                    var nombre_cama = row.nombre_cama != null ? row.nombre_cama : "";
                    var result = nombre_cuarto + " " + nombre_cama;
                    return "<div style='text-align: left;'><b>MEDICO: </b>" + medico +
                        "<br> <b>UBICACION: </b>" + result + "</div>";
                },
                "bSortable": false,
                "aTargets": [6]
            },
            {
                "mRender": function(data, type, row) {
                    var html = "";
                    var tienePrestacion = row.tiene_prestacion;
                    if (row.estado == 1) {
                        html +=
                            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>No se puede registrar ya que aun no ha sido admisionado.</b>" class="contenido-btn-div"> ';
                        html +=
                            '<span class="btn btn-success btn-xs disabled"><i class="fa fa-refresh" aria-hidden="true"></i></span>'; // Si el estado es 5, el botón está deshabilitado
                    } else if (row.estado > 1) {
                        if (tienePrestacion) {
                            html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Rotación de Cama</b>" class="contenido-btn-div"> ';
                            html += '<a href="/rotacion_cama_emergencia/{{ $id_farmacia }}/' + row
                                .id +
                                '" class="btn btn-success btn-xs"><i class="fa fa-refresh" aria-hidden="true"></i></a>'; // Si no, se crea el enlace normalmente
                        } else {
                            html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Se requiere una atencion medica para registrar rotacion.</b>" class="contenido-btn-div"> ';
                            html +=
                                '<span class="btn btn-success btn-xs disabled"><i class="fa fa-refresh" aria-hidden="true"></i></span>'; // Si el estado es 5, el botón está deshabilitado
                        }
                    }
                    html += '</div>';
                    return html;
                },
                "bSortable": false,
                "aTargets": [7]
            },
            {
                "mRender": function(data, type, row) {
                    return `<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Historial</b>" class="contenido-btn-div">
                                        <button type="button" class="btn btn-success btn-xs btn-mostrarModalHistorial" data-id_asegurado=${row.id_asegurado} data-id_ext=${row.id} data-dni_beneficiario=${row.dni_beneficiario}>
                                            <i class="fa fa-fw fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    `;
                },
                "bSortable": false,
                "aTargets": [8]
            },
            {
                "mRender": function(data, type, row) {
                    var html = "";
                    // SE CAMBIO EL FLAG_ENFERMERIA EN ESTADO DE LA TABLA HOSPITALIZACIONES PARA QUE CUANDO EL MEDICO REALICE UNA ATENCION SE PUEDA REGISTRAR EN ENFERMERIA
                    // EL ESTADO ES = 3 ATENCION DE HOSPITALIZACION CUANDO FINALIZA ATENCION EL ESTADO SE PONE A 3.
                    if (row.estado == 5) {
                        if (row.id_enfermeria != null && row.id_enfermeria != 0) {
                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>PDF Registro</b>" style="margin-bottom: 5px;">';
                            if (row.ruta_firma == null) {
                                html += '<a href="/pdf_nota_ingreso_enfermeria_emergencia/' + row
                                    .id_enfermeria +
                                    '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                            } else {
                                html += '<a href="' + `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`/* row.ruta_firma */ +
                                    '" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                            }
                            html +=
                                '<i class="fa fa-fw fa-download" style="color: white;"></i></a></div>';

                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>Vista</b>" style="margin-bottom: 5px;">';
                            html += '<a href="/ver_nota_ingreso_enfermeria_emergencia/' + row
                                .id_enfermeria +
                                '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ;border: 1px solid white;">';
                            html +=
                                '<i class="fa fa-fw fa-eye" style="color: white;"></i></a></div>';
                        } else {
                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>El paciente ya se encuentra de alta y no se puede registrar una nota de ingreso.</b>" style="margin-bottom: 5px;">';
                            html +=
                                '<button class="btn btn-success btn-xs" style="background-color: #00BFFF; border: 1px solid white;" disabled><i class="fa fa-sign-out" style="color: white;"></i></button>';
                            html += '</div>';
                        }
                    } else if ((row.estado == 2 || row.estado == 3 || row.estado == 4) && (row
                            .id_enfermeria ==
                            null || row.id_enfermeria == 0)) {
                        html += '<div data-toggle="tooltip" data-id="' + row.id +
                            '" data-placement="top" data-html="true" title="<b>Ingresar al Paciente</b>" style="margin-bottom: 5px;">';
                        html += '<a href="/formulario_nota_ingreso_emergencia/' + row.id +
                            '/{{ $id_tipo_atenciones }}" class="btn btn-success btn-xs" ; border: 1px solid white;"><i class="fa fa-fw fa-book" style="color: white;"></i></a>';
                        html += '</div>';
                    } else if ((row.estado == 2 || row.estado == 3 || row.estado == 4) && (row
                            .id_enfermeria !=
                            null && row.id_enfermeria != 0)) {
                        html += '<div data-toggle="tooltip" data-id="' + row.id +
                            '" data-placement="top" data-html="true" title="<b>PDF Registro</b>" style="margin-bottom: 5px;">';
                        if (row.ruta_firma == null) {
                            html += '<a href="/pdf_nota_ingreso_enfermeria_emergencia/' + row
                                .id_enfermeria +
                                '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                        } else {
                            html += '<a href="' + `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`/* row.ruta_firma */ +
                                '" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                        }
                        html +=
                            '<i class="fa fa-fw fa-download" style="color: white;"></i></a></div>';

                        html += '<div data-toggle="tooltip" data-id="' + row.id +
                            '" data-placement="top" data-html="true" title="<b>Vista</b>" style="margin-bottom: 5px;">';
                        html += '<a href="/ver_nota_ingreso_enfermeria_emergencia/' + row
                            .id_enfermeria +
                            '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ;border: 1px solid white;">';
                        html += '<i class="fa fa-fw fa-eye" style="color: white;"></i></a></div>';

                    } else if (row.estado == 1) {
                        html += '<div data-toggle="tooltip" data-id="' + row.id +
                            '" data-placement="top" data-html="true" title="<b>El paciente aun no ha pasado admision.</b>" style="margin-bottom: 5px;">';
                        html +=
                            '<button class="btn btn-success btn-xs" style="background-color: #00BFFF; border: 1px solid white;" disabled><i class="fa fa-circle-o" style="color: white;"></i></button>';
                        html += '</div>';
                    } else if (row.estado == 0) {
                        if (row.id_enfermeria != null && row.id_enfermeria != 0) {
                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>PDF Registro</b>" style="margin-bottom: 5px;">';
                            if (row.ruta_firma == null) {
                                html += '<a href="/pdf_nota_ingreso_enfermeria_emergencia/' + row
                                    .id_enfermeria +
                                    '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                            } else {
                                html += '<a href="' + `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`/* row.ruta_firma */ +
                                    '" target="_blank" class="btn btn-primary btn-xs" ; border: 1px solid white;">';
                            }
                            html +=
                                '<i class="fa fa-fw fa-download" style="color: white;"></i></a></div>';

                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>Vista</b>" style="margin-bottom: 5px;">';
                            html += '<a href="/ver_nota_ingreso_enfermeria_emergencia/' + row
                                .id_enfermeria +
                                '/{{ $id_tipo_atenciones }}" target="_blank" class="btn btn-primary btn-xs" ;border: 1px solid white;">';
                            html +=
                                '<i class="fa fa-fw fa-eye" style="color: white;"></i></a></div>';
                        } else {
                            html += '<div data-toggle="tooltip" data-id="' + row.id +
                                '" data-placement="top" data-html="true" title="<b>Esta emergencia se encuentra en estado de anulado.</b>" style="margin-bottom: 5px;">';
                            html +=
                                '<button class="btn btn-success btn-xs" style="background-color: #00BFFF; border: 1px solid white;" disabled><i class="fa fa-times" style="color: white;"></i></button>';
                            html += '</div>';
                        }
                    }
                    return html;
                },
                "bSortable": false,
                "aTargets": [9]
            },
            {
                "mRender": function(data, type, row) {
                    var html = "";
                    html += '<div class="btn-group">';
                    var tieneNota = row.tiene_enfermeria;
                    var tienePrestacion = row.tiene_prestacion;
                    html +=
                        '<button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"';
                    if (!tieneNota) {
                        html += ' disabled ';
                    }
                    html += ' aria-expanded="false"> DIARIO <span class="caret"></span> ';
                    html += ' </button>';
                    html += ' <ul class="dropdown-menu" style="left: auto !important;"> ';
                    html += ' <li>';
                    html += ' <a href="/signos_vitales_listado/' + row.id_asegurado + '/' + row
                        .id_enfermeria + '" id="listarHojaGrafica" name="listarHojaGrafica">';
                    html += ' 	<i class="fa fa-line-chart" aria-hidden="true"></i> Signos Vitales';
                    html += ' </a>';
                    html += ' </li>';
                    html += ' <li>';
                    html += ` <a href="javascript:void(0)" data-asegurado='${JSON.stringify(row)}'` +
                        'data-id_hospitalizacion="' + row.id +
                        '" id="listarBalanceHidrico" name="listarBalanceHidrico">';
                    html += ' 	<i class="fa fa-level-up" aria-hidden="true"></i> Balance Hídrico';
                    html += ' </a>  ';
                    html += ' </li>';
                   // if (row.id_triaje == 1 || row.id_triaje == 12) {
                        html += ' <li>';
                        html += ` <a href="javascript:void(0)" data-asegurado-m='${JSON.stringify(row)}'` +
                            'data-id_hospitalizacion="' + row.id +
                            '" id="listarMonitoreos" name="listarMonitoreos">';
                        id_prestacion
                        html += ' <i class="fa fa-heartbeat" aria-hidden="true"></i> Monitoreo ';
                        html += ' </a>  ';
                        html += ' </li>  ';
                 //   }
                    html += ' <li>';
                    html += ' <a href="javascript:void(0)" data-id="' + row.id + '"  data-id_asegurado="' + row
                        .id_asegurado + '" data-id_consultorio_servicio="' + row.id_consultorio +
                        '" data-id_establecimiento="' + row.id_establecimiento + '" data-prioridad="' + row
                        .prioridad + '" data-id_prestacion="' + row.id_prestacion + '" data-id_enfermeria="' + row
                        .id_enfermeria + '" id="listarKardex" name="listarKardex">';
                    html += ' 	<i class="fa fa-check-square" aria-hidden="true"></i> Kardex';
                    html += ' </a> ';
                    html += ' </li>';
                    html += ' <li>';
                    html +=
                        ` <a href="javascript:void(0)" data-principal='${JSON.stringify(row)}' data-id_tipo_atenciones_listado="{{ $id_tipo_atenciones }}"`;
                    html +=
                        ` id="listarEvoluciones" name="listarEvoluciones"> <i class="fa fa-plus-square" aria-hidden="true"></i> Evoluciones `;
                    html += ' </a> ';
                    html += ' </li>';
                    html += ' </ul>';
                    html += ' </div>';
                    return html;
                },
                "bSortable": false,
                "aTargets": [10]
            },
        ];
        if (nivelIpress == 2) {
            columns.push({
                "mRender": function(data, type, row) {
                    let html = "";
                    if (row.estado > 0 && row.id_prioridad == 4 && row.tiene_prestacion) {
                        html +=
                            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver CPT</b>" class="contenido-btn-div">';
                        html +=
                            `<a href="#cptEmergencia" id="listarCpt" role="button" class="btn btn-success btn-xs" data-toggle="modal" data-principal='${JSON.stringify(row)}'>`;
                        html +=
                            '<i class="fa fa-fw fa-pencil" style="color: white;"></i></a></div>';
                    } else {
                        html += '<div data-toggle="tooltip" data-id="' + row.id +
                            '" data-placement="top" data-html="true" title="<b>Debe ser prioridad IV y tener una receta medica prescrita.</b>" style="margin-bottom: 5px;">';
                        html +=
                            '<button class="btn btn-success btn-xs" style="background-color: #00BFFF; border: 1px solid white;" disabled><i class="fa fa-ban" style="color: white;"></i></button>';
                        html += '</div>';
                    }
                    return html;
                },
                "bSortable": false,
                "aTargets": [11]
            });
            const procesosTh = '<th style="width: 5%; text-align: left; vertical-align: middle;">R. PROCESOS</th>';
            $('#tblListadoEnfermeria thead tr').append(procesosTh);
        }

        function getTopico() {
            var id_prioridad = $('#id_prioridad').val();
            var sexo = $('#sexo').val();
            var edad = $('#edadpaciente').val();

            if (id_prioridad != '') {
                //valicion de tipo de listado
                var url_consulta = '/get_topico_por_id_prioridad/' + id_prioridad;
                ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
                if (id_prioridad == '2' || id_prioridad == '3') {
                    url_consulta = '/get_topico_por_id_prioridad_sexo_edad/' + id_prioridad + '/' + sexo + '/' + edad;
                }

                $.ajax({
                    url: url_consulta,
                    success: function(result) {
                        $('#id_topico').empty();
                        $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>");
                        $(result).each(function(ii, oo) {
                            $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion +
                                "</option>");
                        });
                        $('#id_topico').val("").change();
                        if (id_prioridad == '1') {
                            $('#id_topico').val("1").change();
                            $('#observacion').focus();
                        } else if (id_prioridad == '0') {
                            $('#id_topico').val("0").change();
                            $('#observacion').focus();
                        } else if (id_prioridad == '2') {
                            $('#id_topico').val("10").change();
                            $('#observacion').focus();
                        } else if (id_prioridad == '3') {
                            $('#id_topico').val("11").change();
                            $('#observacion').focus();
                        } else if (id_prioridad == '4') {
                            //$('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                            $('#id_topico').val("19").change();
                            $('#observacion').focus();
                        } else {
                            $('#id_topico').val("").change();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (id_prioridad != '5') {
                            $('#id_topico').empty();
                            $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");
                            $('#id_topico').val("").change();
                            $('#observacion').focus();
                        } else {
                            $('#id_topico').empty();
                            $('#id_topico').append(
                                "<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>");
                            $('#id_topico').val("19").change();
                            $('#observacion').focus();
                        }
                    }
                });
            } else {
                $('#id_topico').empty();
                $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");
                $('#id_topico').val("").change();
            }
            /* },
            error: function(jqXHR, textStatus, errorThrown) {
            	if(id_prioridad!='5'){
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
            } */
        };

        function validarSelectoresPorTipoAtencion(tipoAtencion) {
            if (tipoAtencion == 1) {
                $('#selector_area').attr('hidden', 'true');
                $('#selector_prioridad').attr('hidden', 'true');
                $('#selector_servicioDestino').removeAttr('hidden');
            } else if (tipoAtencion == 0) {
                $('#selector_area').removeAttr('hidden');
                $('#selector_prioridad').removeAttr('hidden');
                $('#selector_servicioDestino').attr('hidden', 'true');
            }
        }

        $(document).ready(function() {
            $('#id_consultorio').change(function() {
                datatablenew();
            });
            $('#seleccionar_estado').change(function() {
                datatablenew();
            });
            $('#fecha_ini_enfermeria').keypress(function(event) {
                if (event.which === 13) {
                    datatablenew();
                }
            });
            $('#fecha_fin_enfermeria').keypress(function(event) {
                if (event.which === 13) {
                    datatablenew();
                }
            });
            $('#busqueda_datos').keypress(function(event) {
                if (event.which === 13) {
                    datatablenew();
                }
            });
            $('#fecha_ini_enfermeria').datepicker({
                useCurrent: false,
                format: 'dd/mm/yyyy',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom left"
            }).on('changeDate', function(e) {
                var fechaDesdeIni = $('#fecha_ini_enfermeria').val();
                $('#fecha_ini_enfermeria').change(function() {
                    var fechaDesde = $('#fecha_ini_enfermeria').val();
                    var fechaHasta = $('#fecha_fin_enfermeria').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[
                        0])); //30 de noviembre de 2014

                    if (f1 > f2) {
                        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                        $('#fecha_ini_enfermeria').val(fechaDesdeIni);
                        return false;
                    }
                });
            });
            $('#fecha_fin_enfermeria').datepicker({
                useCurrent: false,
                format: 'dd/mm/yyyy',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom left"
            }).on('changeDate', function(e) {
                var fechaHastaIni = $('#fecha_fin_enfermeria').val();
                $('#fecha_fin_enfermeria').change(function() {
                    var fechaDesde = $('#fecha_ini_enfermeria').val();
                    var fechaHasta = $('#fecha_fin_enfermeria').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[
                        0])); //30 de noviembre de 2014
                    if (f2 < f1) {
                        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                        $('#fecha_fin_enfermeria').val(fechaHastaIni);
                        return false;
                    }
                });
            });
            $("#system-search").keyup(function() {
                var dataTable = $('#tblListadoEnfermeria').dataTable();
                dataTable.fnFilter(this.value);
            });
            $('#tblListadoEnfermeria').DataTable({
                "language": {
                    "emptyTable": "No se encontraron resultados"
                }
            });
            datatablenew();
        });
        $('#btnBuscar').click(function() {
            datatablenew();
        });

        function calcularEdadPersonaReturn(fecha_nac) {
            var calday = '';
            var calmon = '';
            var calyear = '';
            if (fecha_nac != null && fecha_nac != undefined && fecha_nac.length > 8) {
                var oldDate = fecha_nac; //20/04/1946
                var values = oldDate.split("/");
                if (values.length == 1) {
                    var values = oldDate.split("-");
                    calday = values[2];
                    calmon = values[1];
                    calyear = values[0];
                } else {
                    calday = values[0];
                    calmon = values[1];
                    calyear = values[2];
                }

                const fecha = new Date();
                var curyear = fecha.getFullYear();
                var curmon = fecha.getMonth() + 1;
                var curday = fecha.getDate()

                var curd = new Date(curyear, curmon - 1, curday);
                var cald = new Date(calyear, calmon - 1, calday);

                var diff = Date.UTC(curyear, curmon, curday, 0, 0, 0) - Date.UTC(calyear, calmon, calday, 0, 0, 0);

                var dife = datediff(curd, cald);
                return dife[0] + " AÑOS, " + dife[1] + " MESES Y " + dife[2] + " DÍAS";
            }
        }

        function datediff(date1, date2) {
            var y1 = date1.getFullYear(),
                m1 = date1.getMonth(),
                d1 = date1.getDate(),
                y2 = date2.getFullYear(),
                m2 = date2.getMonth(),
                d2 = date2.getDate();

            if (d1 < d2) {
                m1--;
                d1 += DaysInMonth(y2, m2);
            }
            if (m1 < m2) {
                y1--;
                m1 += 12;
            }
            return [y1 - y2, m1 - m2, d1 - d2];
        }

        function DaysInMonth(Y, M) {
            with(new Date(Y, M, 1, 12)) {
                setDate(0);
                return getDate();
            }
        }

        function datatablenew() {
            const oTable1 = $('#tblListadoEnfermeria').dataTable({
                "bServerSide": true,
                "sAjaxSource": "/lista_pcientes_enfermeria",
                "bProcessing": true,
                "sPaginationType": "full_numbers",
                "bFilter": false,
                "bSort": false,
                "info": true,
                "language": {
                    "url": "/js/Spanish.json"
                },
                "autoWidth": false,
                "bLengthChange": true,
                "destroy": true,
                "lengthMenu": [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                "dom": '<"top">rt<"bottom"flpi><"clear">',
                "fnDrawCallback": function(json) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                    var sEcho = aoData[0].value;
                    var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value))
                        .toFixed();
                    var iCantMostrar = aoData[4].value;
                    var fecha_ini = $('#fecha_ini_enfermeria').val();
                    var fecha_fin = $('#fecha_fin_enfermeria').val();
                    var dni_beneficiario = $('#dni_beneficiario').val();
                    var nombre_beneficiario = $('#nombre_beneficiario').val();
                    var seleccionar_estado = $('#seleccionar_estado').val();
                    var id_consultorio = $('#id_consultorio').val();
                    var tipo_atencion = $('#tipo_atencion').val();
                    var id_servicio_destino = $('#id_servicio_destino').val();
                    var id_prioridad = $('#id_prioridad').val();
                    var id_topico = $('#id_topico').val();
                    var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                    var _token = $('#_token').val();
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fecha_ini: fecha_ini,
                            fecha_fin: fecha_fin,
                            id_establecimiento: id_establecimiento,
                            dni_beneficiario: dni_beneficiario,
                            nombre_beneficiario: nombre_beneficiario,
                            id_servicio_destino: id_servicio_destino,
                            seleccionar_estado: seleccionar_estado,
                            id_consultorio: id_consultorio,
                            tipo_atencion: tipo_atencion,
                            id_prioridad: id_prioridad,
                            id_topico: id_topico,
                            _token: _token
                        },
                        "success": function(result) {
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                            //location.href="login";
                        }
                    });
                },
                "aoColumnDefs": columns,
            });
        }

        function diff_years(fechaNacimiento) {
            const fechaNacimientoDate = new Date(fechaNacimiento);
            const fechaActual = new Date();
            let edad = fechaActual.getFullYear() - fechaNacimientoDate.getFullYear();
            const mesNacimiento = fechaNacimientoDate.getMonth();
            const diaNacimiento = fechaNacimientoDate.getDate();
            const mesActual = fechaActual.getMonth();
            const diaActual = fechaActual.getDate();
            if (mesActual < mesNacimiento || (mesActual === mesNacimiento && diaActual < diaNacimiento)) {
                edad--;
            }
            return edad;
        }

        function removeIdsFromHtml(htmlString) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');

            function removeIds(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    node.removeAttribute('id');
                    node.childNodes.forEach(removeIds);
                }
            }
            removeIds(doc.body);
            return doc.body.innerHTML;
        }


        function removeIdsFromHtml(htmlString) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');

            function removeIds(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    node.removeAttribute('id');
                    node.childNodes.forEach(removeIds);
                }
            }
            removeIds(doc.body);
            return doc.body.innerHTML;
        }

        function renderUserData(asegurado) {
            if (asegurado) {
                const date = new Date();
                $('#dni_paciente').html(`${asegurado[0].dni}`);
                $('#nombre_paciente').html(
                    `${asegurado[0].asegurado_nombre} ${asegurado[0].asegurado_paterno} ${asegurado[0].asegurado_materno}`
                );
                $('#parentesco_paciente').html(`${asegurado[0].asegurado_tipo_beneficiario}`);
                $("#edad_paciente").html(asegurado[0].asegurado_edad_anios);
                $("#ts_paciente").html(asegurado[0].asegurado_tipo_sangre ?? "Sin data.");
                $("#sexo_paciente").html(asegurado[0].sexo);
                $("#hc_paciente").html(asegurado[0].historia_clinica);
                $("#es_alergico_paciente").html(asegurado[0].tiene_alergias_registradas
                        ? `<i style="color: blue;">SI</i>`
                        : `<i style="color: red;">NO</i>`);
            }
            const renderData = document.getElementById('datosPacienteCargado').innerHTML;
            const parsedRenderData = removeIdsFromHtml(renderData);
            $("#datosPacienteParaCargarMonitoreo").html(parsedRenderData);
            $("#datosPacienteParaCargarMonitoreoRegistro").html(parsedRenderData);
            //$("#datosPacienteParaCargarBalance").html(renderData); 
        }

        function renderUserDiagnosticos(arrDiagnosticos) {
            const toRender = document.createDocumentFragment();
            const table = document.createElement('table');
            const thead = document.createElement('thead');
            thead.innerHTML = `<tr>
									<th>N</th>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>TIPO</th>
									<th>CLASIFICACION</th>
								</tr>`;
            const tbody = document.createElement('tbody');
            tbody.id = 'tblDiagnosticosEnf'
            table.appendChild(thead);
            table.appendChild(tbody);
            table.classList.add('diagnosticos_enfermeria');
            table.classList.add('table');
            toRender.appendChild(table);
            if (arrDiagnosticos.length == 0) {
                const tbody = toRender.getElementById('tblDiagnosticosEnf');
                tbody.innerHTML =
                    `<tr><td colspan="5" style="font-size:14px; text-align:center;">No existe una atencion medica por el momento.</td></tr>`;
            } else {
                arrDiagnosticos.forEach((diagnostico, i) => {
                    const row = `<tr>
                                    <td>${++i}</td>
                                    <td>${diagnostico.codigo_diagnostico}</td>
                                    <td>${diagnostico.nombre_diagnostico}</td>
                                    <td>${diagnostico.tipo_diagnostico}</td>
                                    <td><b>ATENCION</b></td>
                                </tr>`;
                    const tbody = toRender.getElementById('tblDiagnosticosEnf');
                    let tableContent = tbody.innerHTML;
                    const rowJoin = tableContent.concat(row);
                    tbody.innerHTML = rowJoin;
                });
            }
            $("#datosPacienteParaCargarMonitoreoRegistroDiagnosticos").html(toRender);
        }

        $(document).ready(function() {
            const sessionFlashError = {!! json_encode(session('error')) !!};
            const sessionFlashSuccess = {!! json_encode(session('success')) !!};
            if (sessionFlashError || sessionFlashSuccess) {
                Swal.fire({
                    icon: sessionFlashSuccess ? 'success' : 'error',
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>ROTACIÓN/TRASLACION CAMA</span></div>",
                    html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${sessionFlashError || sessionFlashSuccess}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
            }
        });
    </script>
@endsection
@section('content')
    <div id="addComponent"></div>
    <div class="container-fluid">
        <div class="row col-md-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Pacientes de Enfemería</h3>
                </div>
                <div class="panel-body" style="font-size: 14px;">
                    <form class="form-horizontal" method="post" action="{{ route('emergencia_by_enfermeria_ajax') }}">
                        <input type="hidden" name="flag_firma_enfermeria" id="flag_firma_enfermeria">
                        <input type="hidden" name="nivel_ipress" id="nivel_ipress" value="{{ $establecimiento->nivel }}">
                        <input type="hidden" name="id_establecimiento_final" id="id_establecimiento_final" value="{{ $establecimiento->id }}">
                        <input type="hidden" id="id_user" name="id_user" value="{{ $id_user }}">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-control-sm">Fecha Desde</label>
                                    <input class="form-control input-sm" type="text" name="fecha_ini_enfermeria"
                                        id="fecha_ini_enfermeria" placeholder="Fecha Desde"
                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" maxlength="10">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-control-sm">Fecha Hasta</label>
                                    <input class="form-control input-sm" type="text" name="fecha_fin_enfermeria"
                                        id="fecha_fin_enfermeria" placeholder="Fecha Hasta"
                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" maxlength="10">
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo">Nro. Documento</label><br />
                                    <input class="form-control" id="dni_beneficiario" name="dni_beneficiario"
                                        style="height: 30px !important; text-transform: uppercase;" autocomplete="off"
                                        placeholder="BUSCAR ..."><br />
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo">Apellidos y Nombres</label><br />
                                    <input class="form-control" id="nombre_beneficiario" name="nombre_beneficiario"
                                        style="height: 30px !important; text-transform: uppercase;" autocomplete="off"
                                        placeholder="BUSCAR ..."><br />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="tipo">Tipo Atencion</label><br />
                                    <select disabled id="tipo_atencion" name="tipo_atencion" class="form-control"
                                        style="width: 100%; height: 30px !important; font-size: 13px;">
                                        <option value="0" selected>EMERGENCIA</option>
                                        <option value="1">HOSPITALIZACION</option>
                                    </select>
                                </div>




                                <div class="col-lg-2 col-md-2" id="selector_prioridad">
                                    <label class="form-control-sm">Prioridad</label>
                                    <select id="id_prioridad" name="id_prioridad" onchange="getTopico();"
                                        class="form-control input-sm">
                                        <option value="0">-Seleccionar-</option>
                                        <?php //foreach($opcionesprioridad as $e)
                                        ?>
                                        <option value="1">I - GRAVEDAD SÚBITA EXTREMA</option>
                                        <option value="2">II - URGENCIA MAYOR</option>
                                        <option value="3">III - URGENCIA MENOR</option>
                                        <option value="4">IV - PATOLOGÍA AGUDA COMÚN</option>
                                        <?php //endforeach
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" id="selector_area">
                                    <label class="form-control-sm">Area</label>
                                    <select class="form-control input-sm" name="id_topico" id="id_topico" required=""
                                        style="width: 100%">
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
                                        <option value="19">CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo">Estado</label><br />
                                    <select id="seleccionar_estado" name="seleccionar_estado" class="form-control"
                                        style="width: 100%; height: 30px !important; font-size: 13px;">
                                        <option value="-1">TODOS</option>
                                        <option value="0">PENDIENTES</option>
                                        <option value="1">REGISTRADOS</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo" style="color: white">Estado</label><br />
                                    <input id="btnBuscar" name="btnBuscar" class="form-control btn btn-info input-sm"
                                        value="Buscar" type="button" style="height: 30px !important;">
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo" style="color: white">FIRMAR</label><br />
                                    <input id="btnBuscar" name="btnBuscar" class="form-control btn btn-danger input-sm"
                                        value="Firmar" type="button" style="height: 30px !important;">
                                </div>
                                <input type="hidden" id="modalAtencionAjax" value="1">
                            </div>
                        </div>

                    </form>

                    <div class="col-md-12">
                        <div id="global">
                            <div class="table-responsive">
                                <span hidden id="flag_consultorio">{{ $id_user_consultorio }}</span>
                                <table id="tblListadoEnfermeria"
                                    class="table table-striped table-list-search dataTable no-footer"
                                    style="width:100%; font-size: 12px; border-collapse: collapse;">
                                    <thead id="tblEnfermeriaHead">
                                        <tr>
                                            <th style="width: 5%">N°</th>
                                            <th style="width: 5% text-align:left">FECHA/HORA <br>INGRESO-EGRESO<br></th>
                                            <th style="width: 5%; text-align:left">FECHA/HORA <br>REGISTRO DE NOTA<br></th>
                                            <th style="width: 10%; text-align:left">DATOS <br>ATENCION</th>
                                            <th style="width: 15%; text-align: left; vertical-align: middle;">PACIENTE</th>
                                            <th style="width: 15%; text-align: left; vertical-align: middle;">ACOMPAÑANTE
                                            </th>
                                            <th style="width: 15%; text-align: left; vertical-align: middle;"
                                                id="enfermeriaUbicacion">MEDICO & UBICACION</th>
                                            <th style="width: 5%; text-align: left; vertical-align: middle;">ROTACIÓN</th>
                                            <th style="width: 5%;text-align: left; vertical-align: middle;"><br>HISTORIAL
                                            </th>
                                            <th style="width: 5%; text-align: left; vertical-align: middle;">NOTA<br>ENF
                                            </th>
                                            <th style="width: 5%; text-align: left; vertical-align: middle;">SEGUIMIENTO
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblEnfermeriaBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-wrapper -->
    <div style="z-index: 900000000;" class="modal fade" id="myModalInterconsulta1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">DETALLE DEL EXAMEN DE INTERCONSULTA. <span id="nrorecmodal1"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="muestra-det-interconsulta1"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div style="z-index: 900000000;" class="modal fade" id="myModalInterconsulta2" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">DETALLE DEL RPTA DE INTERCONSULTA. <span id="nrorecmodal2"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="muestra-det-interconsulta2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial -->
    @include('frontend.emergencia_enfermeria.historial.views.listar_historial')

    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.rass')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.glasgow')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.actualizar_glasgow')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.actualizar_rass')

    <!-- monitoreo -->
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.listar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.registrar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.actualizar_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.detalle_monitoreo')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.cpot')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.morse')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.norton')
    @include('frontend.emergencia_enfermeria.monitoreo.modal_monitoreo.escalas.tiss')

    <!-- kadex -->
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.lista_de_kadex')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.registrar_kardex')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.editar_medicamento')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.horario_de_kardex')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.listar_interconsulta_kardex')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.listar_ex_cul_ven')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.ver_detalle_kardex')
    @include('frontend.emergencia_enfermeria.kardex.modal_kardex.formulario_medicamento')

    <!-- resultados -->
    @include('frontend.emergencia_enfermeria.resultados.modal_resultados.listar_resultados')

    <!-- balance hídrico -->
    @include('frontend.emergencia_enfermeria.balance_hidrico.modal_balance_hidrico.listar_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.modal_balance_hidrico.registrar_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.modal_balance_hidrico.hoja_balance_hidrico')
    @include('frontend.emergencia_enfermeria.balance_hidrico.modal_balance_hidrico.editar_balance_hidrico')

    <!-- hoja gràfica -->
    @include('frontend.emergencia_enfermeria.hoja_grafica.modal_hoja_grafica.listar_hoja_grafica')
    @include('frontend.emergencia_enfermeria.hoja_grafica.modal_hoja_grafica.registrar_hoja_grafica')
    @include('frontend.emergencia_enfermeria.hoja_grafica.modal_hoja_grafica.ver_registro_grafico')

    <!-- cpt -->
    @include('frontend.emergencia_enfermeria.cpt.modal.listar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.modal.anular_cpt')
    @include('frontend.emergencia_enfermeria.cpt.modal.registrar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.modal.editar_cpt')
    @include('frontend.emergencia_enfermeria.cpt.modal.ver_cpt')

    <!-- evoluciones -->
    @include('frontend.emergencia_enfermeria.evoluciones.modal_evoluciones.lista_de_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.modal_evoluciones.anular_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.modal_evoluciones.registrar_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.modal_evoluciones.ver_evoluciones')
    @include('frontend.emergencia_enfermeria.evoluciones.modal_evoluciones.editar_evoluciones')

    <!-- incidentes -->
    @include('frontend.emergencia_enfermeria.incidentes.modal_incidentes.lista_de_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.modal_incidentes.registrar_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.modal_incidentes.ver_incidencias')
    @include('frontend.emergencia_enfermeria.incidentes.modal_incidentes.editar_incidencias')

    <!-- hoja conduccion -->
    @include('frontend.emergencia_enfermeria.hoja_conduccion.modal_hoja_conduccion.hoja_conduccion_enfermeria')
    @include('frontend.emergencia_enfermeria.hoja_conduccion.modal_hoja_conduccion.hoja_conduccion_enfermeria_registro')

    <!-- datos del paciente -->
    @include('frontend.emergencia_enfermeria.datos_paciente_header')

    <!-- alergias -->
    @include('frontend.emergencia_enfermeria.alergias.modal_alergias.lista_de_alergias')
    @include('frontend.emergencia_enfermeria.alergias.modal_alergias.registrar_alergias')
    @include('frontend.emergencia_enfermeria.alergias.modal_alergias.ver_alergias')
    @include('frontend.emergencia_enfermeria.alergias.modal_alergias.editar_alergias')

    <!-- modal atencion -->
    @include('frontend.emergencia_enfermeria.historial.views.ver_atencion_modal')

    @include('frontend.includes.footer')
@endsection
