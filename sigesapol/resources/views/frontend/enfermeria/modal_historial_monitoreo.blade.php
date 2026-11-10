<title>Sistema de Farmacia</title>

<style>
    /*****************/
    .modal-dialog {
        width: 90%;
    }

    #tablemodal {
        border-spacing: 0;
        display: flex;
        /*Se ajuste dinamicamente al tamano del dispositivo**/
        max-height: 80vh;
        /*El alto que necesitemos**/
        overflow-y: auto;
        /**El scroll verticalmente cuando sea necesario*/
        overflow-x: hidden;
        /*Sin scroll horizontal*/
        table-layout: fixed;
        /**Forzamos a que las filas tenga el mismo ancho**/
        width: 98vw;
        /*El ancho que necesitemos*/
        border: 1px solid #c4c0c9;
    }

    #tablemodal thead {
        background-color: #e2e3e5;
        position: fixed !important;
    }


    #tablemodal th {
        border-bottom: 1px solid #c4c0c9;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal th {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 10px;
        font-weight: bold;
        height: 3.5vh !important;
        line-height: 12px;
        vertical-align: middle;
        /*height:20px;*/
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal td {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 11px;
        height: 3.5vh !important;
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal tbody tr:hover td,
    #tablemodal1 tbody tr:hover th {
        /*background-color: red!important;*/
        font-weight: bold;
        /*mix-blend-mode: difference;*/

    }

    #tablemodalm4 {}
</style>

<script type="text/javascript">
    // Solo puede ser de 3 a 15
    const valoracionGlasgow_dt = {
        '3': 'TRAUMA GRAVE',
        '4': 'TRAUMA GRAVE',
        '5': 'TRAUMA GRAVE',
        '6': 'TRAUMA GRAVE',
        '7': 'TRAUMA GRAVE',
        '8': 'TRAUMA GRAVE',
        '9': 'TRAUMA MODERADO',
        '10': 'TRAUMA MODERADO',
        '11': 'TRAUMA MODERADO',
        '12': 'TRAUMA MODERADO',
        '13': 'TRAUMA LEVE',
        '14': 'TRAUMA LEVE',
        '15': 'TRAUMA LEVE',
    }

    const intrepretacionRass_dt = {
        "4": 'COMBATIVO',
        "3": 'MUY AGITADO',
        "2": 'AGITADO',
        "1": 'INQUIETO',
        "0": 'ALERTA Y TRANQUILO',
        "-1": 'SOMNOLIENTO',
        "-2": 'SEDACION LIGERA',
        "-3": 'SEDACION MODERADA',
        "-4": 'SEDACION PROFUNDA',
        "-5": 'NO ESTIMULABLE',
    }

    const descripcionRass_dt = {
        "4": 'Violento o combativo, con riesgo para el personal',
        "3": 'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
        "2": 'Movimientos descoordinados o desadaptacion del respirador',
        "1": 'Ansioso, pero sin movimientos agresivos o vigorosos',
        "0": '',
        "-1": 'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
        "-2": 'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
        "-3": 'Movimientos (sin apertura de ojos) a la llamada',
        "-4": 'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
        "-5": 'Sin respuesta a la voz o al estimulo fisico',
    };

    $(document).on('click', '#btnShowDetailMoniEnf', async function() {
        const monitoreo_id = $(this).data('id');
        const monitoreo_date = $(this).data('monitoreo_date');
        const tipo_atencion_id = $(this).data('tipo_atencion_id');
        renderDetalleModal(monitoreo_date, monitoreo_id);
    });

    function renderInfusionesParaDetalle(detalles_infusiones) {
        const [detalle_infusion] = detalles_infusiones;
        const infusiones = JSON.parse(detalle_infusion.descripcion);
        infusiones.forEach((infusion, i) => {
            let newRow = "";
            newRow += `<tr class="row-infusion_dt col-md-12" row-counter="${i}">`;
            newRow +=
                `<td class="table_td_input-xl"><input disabled type="text" data-toggle="tooltip" data-placement="top" readonly title="No se puede editar esto." value="${infusion.nombre}" name="nombre_infusion_dt[]" required="" id="nombre_infusion_dt` +
                i + '" class="input-sm" style="margin-left:4px; color:red; cursor:not-allowed;" />';
            newRow +=
                `<td class="table_td_input-sm"><input disabled type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad" value="${infusion.cantidad}" name="infusiones_cantidad_dt[]" required="" id="infusiones_cantidad_dt` +
                i + '" class="input-sm" style="margin-left:4px; text-align:left" />';
            newRow +=
                '<input disabled type="hidden" class="form-control typeahead tt-query" id="infusiones_dt' + i +
                `" name="infusiones_dt[]" value="${infusion.id}"></td>`;
            newRow += '</tr>';

            $('#tblInfusiones_dt tbody').append(newRow);
        });
    }

    function renderDetalleModal(fecha_hora, id_monitoreo) {
        $.ajax({
            url: `/api/monitoreo/detalleMonitoreo/${id_monitoreo}/${fecha_hora}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.length > 0) {
                    $("#firmarMonitoreo").attr("disabled", false);
                    $('#FormularioMonitoreoDetalle').trigger("reset");
                    $('#tituloFormularioMonitoreoDetalle').html("Detalle Monitoreo");
                    $('#modalMonitoreoDetalle').modal('show');
                    /* $("#tipo_proceso").select2({ width: 'resolve'}); 
                    $("#tamanio_proceso").select2({ width: 'resolve'});  */
                    $("#id_monitoreo_dt").val(res[0].id_monitoreo);
                    $("#date_monitoreo_dt").val((res[0]
                        .hora_monitoreo
                    )); // SOLO CARGAMOS LA HORA, YA QUE LA FECHA SE OBTIENE EN BASE A LA FECHA ACTUAL DEL SERVIDOR.
                    $("#fecha_hora_monitoreo_dt").val((res[0].hora_monitoreo).split(' ')[1]);
                    $("#turno_dt").val(res[0].turno).change();
                    $("#id_turno_dt").val(res[0].turno);
                    const camposActualizablesNecesarios = res.map(detalle => {
                        const {
                            keyword,
                            descripcion
                        } = detalle;
                        const keywordParaMostrarEnModal = keyword.concat("_dt");
                        return {
                            keywordParaMostrarEnModal,
                            descripcion
                        };
                    });
                    camposActualizablesNecesarios.forEach(campo => {
                        if (campo.keywordParaMostrarEnModal === "conciencia_monitoreo_dt" || campo
                            .keywordParaMostrarEnModal === "grado_dependencia_dt") {
                            $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion)
                                .change();
                        } else if (campo.keywordParaMostrarEnModal ===
                            "pupila_derecha_dt" /*  && campo.descripcion != "" */ ) {
                            /* $("#activo_pupila_der_dt").prop('checked', false); */
                            $("#pupila_derecha_dt").val(campo.descripcion);
                        } else if (campo.keywordParaMostrarEnModal ===
                            "pupila_izquierda_dt" /*  && campo.descripcion != "" */ ) {
                            /* $("#activo_pupila_izq_dt").prop('checked', false); */
                            $("#pupila_izquierda_dt").val(campo.descripcion);
                        } else if (campo.keywordParaMostrarEnModal === "glasgow_dt") {
                            $("#glasgow_dt").val(campo.descripcion);
                            $("#desc_glasgow_dt").html(valoracionGlasgow_dt[campo.descripcion]);
                        } else if (campo.keywordParaMostrarEnModal === "rass_dt" && campo
                            .descripcion) {
                            $('#rass_dt').val(campo.descripcion);
                            $('#puntaje_calculado_rass_dt').val(
                                `${campo.descripcion} | ${intrepretacionRass_dt[campo.descripcion]}`
                            );
                            $('#desc_rass_dt').html(descripcionRass_dt[campo.descripcion]);
                        } else if (campo.keywordParaMostrarEnModal === "pam_dt") {
                            $("#pam_dt").val(campo.descripcion);
                        } else if (campo.keywordParaMostrarEnModal === "modalidad_dt") {
                            $("#modalidad_final_dt").val(campo.descripcion);
                            if (campo.descripcion == "PRESION") {
                                $("#md_presion_dt").prop('checked', true);
                            }
                            if (campo.descripcion == "VOLUMEN") {
                                $("#md_volumen_dt").prop('checked', true);
                            }
                        } else if (campo.keywordParaMostrarEnModal === "vmni_dt" && campo
                            .descripcion != '') {
                            $("#vmni_dt_toggle").prop('checked', true);
                            $("#vm_desc_dt").val(campo.descripcion);
                        } else if (campo.keywordParaMostrarEnModal === "vmi_dt" && campo
                            .descripcion != '') {
                            $("#vmi_dt_toggle").prop('checked', true);
                            $("#vm_desc_dt").val(campo.descripcion);
                        } else if (campo.keywordParaMostrarEnModal === "fijacion_der_dt" && campo
                            .descripcion != '') {
                            $("#ft_der_dt").prop('checked', true);
                            $("#ft_valor_dt").val(campo.descripcion);
                            /* $("#fijacion_der_dt").val(campo.descripcion); */
                        } else if (campo.keywordParaMostrarEnModal === "fijacion_izq_dt" && campo
                            .descripcion != '') {
                            $("#ft_izq_dt").prop('checked', true);
                            $("#ft_valor_dt").val(campo.descripcion);
                            /* $("#fijacion_izq_dt").val(campo.descripcion); */
                        } else {
                            $(`#${campo.keywordParaMostrarEnModal}`).val(campo.descripcion);
                        }
                    });
                    const detalles_infusiones = camposActualizablesNecesarios.filter((detalle) => {
                        if (detalle.keywordParaMostrarEnModal == "infusiones_dt") {
                            return detalle;
                        }
                    });
                    if (detalles_infusiones.length > 0) {
                        renderInfusionesParaDetalle(detalles_infusiones);
                    }
                } else {
                    alert(
                        'no se encontraron registros de este monitoreo, porque se uso para realizar pruebas.'
                    );
                }
            },
            error: function(err) {
                alert('Ocurrio un error inesperado.');
            }
        });
    }
    $('#modalMonitoreoDetalle input, #modalMonitoreoDetalle select').prop('disabled', true);
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm6"
                                style="font-size: 11px; margin: 0;">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>FECHA / HORA REGISTRO</th>
                                        <th>UBICACION</th>
                                        <th>PROFESIONAL DE SALUD RESPONSABLE</th>
                                        <th>VER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($monitoreos) && count($monitoreos) > 0)
                                        @foreach ($monitoreos as $dato)
                                            <tr>
                                                <th>{{ $dato->id }}</th>
                                                <th><b>FECHA: </b>{{ $dato->fecha }} <b>HORA: </b>{{ $dato->hora }}
                                                </th>
                                                @if ($tipo_atencion == 3)
                                                    <th>HOSPITALIZACION<br><b>Cuarto:</b>
                                                        {{ explode('-', $dato->ubicacion)[0] }}
                                                        <br><b>Cama:</b> {{ explode('-', $dato->ubicacion)[1] }}
                                                    </th>
                                                @elseif($tipo_atencion == 2)
                                                    <th>EMERGENCIA<br>{{ $dato->ubicacion }}
                                                    </th>
                                                @endif
                                                <th>{{ $dato->responsable }}</th>
                                                <th style="color: #003366;">
                                                    <div>
                                                        <p>
                                                            <button id="btnShowDetailMoniEnf" type="button"
                                                                class="btn btn-success btn-xs"
                                                                data-id="{{ $dato->id }}"
                                                                data-tipo_atencion_id="{{ $tipo_atencion }}"
                                                                data-monitoreo_date="{{ $dato->fecha . ' ' . $dato->hora }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Ver detalle">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </button>
                                                        </p>
                                                    </div>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="13" style="text-align: center;">No hay registros.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalMonitoreoDetalle" role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header" style="font-size: 20px !important;">
                    <button id="closeMonitoreoDetalle" type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b id="tituloFormularioMonitoreoDetalle"></b>
                </div>

                <div class="modal-body">
                    <form id="FormularioMonitoreoDetalle" name="FormularioMonitoreoActualizar"
                        enctype="multipart/form-data" method="">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="id_monitoreo_dt" name="id_monitoreo_dt">
                        <input type="hidden" id="date_monitoreo_dt" name="date_monitoreo_dt">
                        <input type="hidden" id="id_turno_dt" name="id_turno_dt">
                        <div class="nav-tabs-custom">
                            <div class="tab-content">
                                <div class="tab-pane active" style="font-size: 11px;">
                                    <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm"
                                            id="datosPacienteParaCargarMonitoreoRegistro"></div>
                                    </fieldset>
                                    <fieldset>
                                        <form action="">
                                            <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="panel-body" id="">
                                                    <fieldset>
                                                        <div class="form-group" style="margin-bottom: 0; !important">
                                                            <div class="table-responsive">
                                                                <table id=""
                                                                    class="table table-striped table-list-search"
                                                                    style="margin-bottom: 0px !important;">
                                                                    <tbody>
                                                                        <tr class="modal-tr">
                                                                            <td class="monitoreo-td">
                                                                                <label><b>FECHA & HORA:</b></label>
                                                                                <input readonly
                                                                                    id="fecha_hora_monitoreo_dt"
                                                                                    name="fecha_hora_monitoreo_dt"
                                                                                    type="time" class="form-control"
                                                                                    style="height: 30px !important;">
                                                                            </td>
                                                                            <td class="monitoreo-td">
                                                                                <label><b>TURNO</b></label>
                                                                                <select disabled
                                                                                    class="form-control input-sm"
                                                                                    name="id_turno_dt" id="turno_dt">
                                                                                    <option value="">
                                                                                        --Seleccionar--</option>
                                                                                    <option value="2">MAÑANA
                                                                                    </option>
                                                                                    <option value="3">TARDE
                                                                                    </option>
                                                                                    <option value="4">NOCHE
                                                                                    </option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="panel with-nav-tabs panel-default"
                                                style="font-size: 11px;padding-bottom: 0px !important;">
                                                <div class="panel-heading">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active accesTab_dt">
                                                            <a href="#tab0defaultMonitoreo_dt" data-toggle="tab"
                                                                style="font-size:12px" aria-expanded="true">
                                                                <b>
                                                                    <i class="fa fa-file-text-o"
                                                                        aria-hidden="true"></i>M. HEMODINAMICO
                                                                </b>
                                                            </a>
                                                        </li>
                                                        <li class="accesTab_dt">
                                                            <a href="#tab1defaultMonitoreo_dt" data-toggle="tab"
                                                                style="font-size:12px" aria-expanded="true">
                                                                <b>
                                                                    <i class="fa fa-file-text-o"
                                                                        aria-hidden="true"></i>SIGNOS VITALES
                                                                </b>
                                                            </a>
                                                        </li>
                                                        <li class="accesTab_dt">
                                                            <a href="#tab2defaultMonitoreo_dt" data-toggle="tab"
                                                                style="font-size:12px" aria-expanded="true">
                                                                <b>
                                                                    <i class="fa fa-file-text-o"
                                                                        aria-hidden="true"></i>M. RESPIRATORIO
                                                                </b>
                                                            </a>
                                                        </li>
                                                        <li class="accesTab_dt">
                                                            <a href="#tab3defaultMonitoreo_dt" data-toggle="tab"
                                                                style="font-size:12px" aria-expanded="true">
                                                                <b>
                                                                    <i class="fa fa-file-text-o"
                                                                        aria-hidden="true"></i>INFUSIONES
                                                                </b>
                                                            </a>
                                                        </li>
                                                        <li class="accesTab_dt">
                                                            <a href="#tab4defaultMonitoreo_dt" data-toggle="tab"
                                                                style="font-size:12px" aria-expanded="true">
                                                                <b>
                                                                    <i class="fa fa-file-text-o"
                                                                        aria-hidden="true"></i>ADICIONAL
                                                                </b>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade active in tabMoni_dt"
                                                            id="tab0defaultMonitoreo_dt">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <table id="example"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <thead>
                                                                                <tr class="modal-tr">
                                                                                    <th style="text-align: center;">
                                                                                        INDICADORES</th>
                                                                                    <th style="text-align: center;">
                                                                                        VALOR</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                {{-- <tr class="modal-tr">
                                                                                <td><b>HORA</b></td>
                                                                                <td class="monitoreo-td"><input id="hora" name="hora" type="time" class="form-control"
                                                                                        style="height: 30px !important;"></td>
                                                                            </tr> --}}
                                                                                <tr class="modal-tr">
                                                                                    <td><b>G. CONCIENCIA</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <select
                                                                                            class="form-control input-sm"
                                                                                            name="conciencia_monitoreo_dt"
                                                                                            id="conciencia_monitoreo_dt">
                                                                                            <option value="">
                                                                                                --Seleccionar--</option>
                                                                                            <option value="RELAJACION">
                                                                                                RELAJACION</option>
                                                                                            <option value="ANALGESIA">
                                                                                                ANALGESIA</option>
                                                                                            <option value="SEDACION">
                                                                                                SEDACION</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>PUPILAS</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <table
                                                                                            class="table table-striped table-list-search">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th
                                                                                                        class="cell-pupila">
                                                                                                        <b>Descripcion</b>
                                                                                                    </th>
                                                                                                    {{-- <th class="cell-pupila"><b>No evaluable</b></th> --}}
                                                                                                    <th
                                                                                                        class="cell-pupila">
                                                                                                        <b>Cantidad</b>
                                                                                                    </th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr class="">
                                                                                                    <td
                                                                                                        class="cell-pupila">
                                                                                                        Der</td>
                                                                                                    {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_der_dt" name="activo_pupila_der_dt" checked></td> --}}
                                                                                                    <td
                                                                                                        class="cell-pupila">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            type="text"
                                                                                                            id="pupila_derecha_dt"
                                                                                                            name="pupila_derecha_dt">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="">
                                                                                                    <td
                                                                                                        class="cell-pupila">
                                                                                                        Izq</td>
                                                                                                    {{-- <td class="cell-pupila"><input class="form-control" type="checkbox" id="activo_pupila_izq_dt" name="activo_pupila_der_dt" checked></td> --}}
                                                                                                    <td
                                                                                                        class="cell-pupila">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            type="text"
                                                                                                            id="pupila_izquierda_dt"
                                                                                                            name="pupila_izquierda_dt">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>G. DEPENDENCIA</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <select
                                                                                            class="form-control input-sm"
                                                                                            id="grado_dependencia_dt"
                                                                                            name="grado_dependencia_dt"
                                                                                            style="width: 100%;">
                                                                                            <option value="">
                                                                                                --Seleccionar--</option>
                                                                                            <option value="GRADO I">
                                                                                                GRADO I</option>
                                                                                            <option value="GRADO II">
                                                                                                GRADO II</option>
                                                                                            <option value="GRADO III">
                                                                                                GRADO III</option>
                                                                                            <option value="GRADO IV">
                                                                                                GRADO IV</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>ESCALA DE GLASGOW</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <label for="puntaje_glasgow"
                                                                                            class="desc_glasgow">Descripcion
                                                                                            GLASGOW: <b><span
                                                                                                    id="desc_glasgow_dt"></span></b></label>
                                                                                        <div class="glasgow">
                                                                                            <input readonly
                                                                                                type="text"
                                                                                                class="form-control puntaje-glasgow"
                                                                                                style="height: 30px !important;"
                                                                                                id="glasgow_dt"
                                                                                                name="glasgow_dt">
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                {{-- <button class="btn btn-success btn-xs calcularEscalaGlasgowMonitoreo_dt" id=""
                                                                                            name="" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button> --}}
                                                                                            </span>
                                                                                            <input type="hidden"
                                                                                                value=""
                                                                                                id="puntaje_calculado_glasgow_memoria_dt"
                                                                                                name="puntaje_calculado_glasgow_memoria_dt">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>ESCALA DE RASS</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <label for="puntaje_rass"
                                                                                            class="desc_glasgow">Descripcion
                                                                                            RASS: <b><span
                                                                                                    id="desc_rass_dt"></span></b></label>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                id="puntaje_calculado_rass_dt"
                                                                                                name="puntaje_calculado_rass_dt"
                                                                                                class="form-control puntaje_calculado_rass"
                                                                                                style="height: 30px !important;"
                                                                                                readonly>
                                                                                            <input type="hidden"
                                                                                                value=""
                                                                                                id="rass_dt"
                                                                                                name="rass_dt">
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                {{-- <button class="btn btn-success btn-xs calcularEscalaRass_dt" id="calcularEscalaRass_dt"
                                                                                            name="calcularEscalaRass_dt" style="height: 30px !important;" type="button">
                                                                                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button> --}}
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="tab-pane tabMoni_dt" id="tab1defaultMonitoreo_dt">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <table id="example1"
                                                                            class="table table-striped table-list-search"
                                                                            style="margin-bottom: 0px !important;">
                                                                            <thead>
                                                                                <tr class="modal-tr">
                                                                                    <th style="text-align: center;">
                                                                                        INDICADORES</th>
                                                                                    <th style="text-align: center;">
                                                                                        VALOR</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>PRESION ARTERIAL (PA)</b>
                                                                                    </td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <label for=""><b>COMPLETAR
                                                                                                    CAMPOS EN CASO DE
                                                                                                    CAMBIAR EL PAM
                                                                                                    PREVIAMENTE
                                                                                                    INGRESADO.</b></label>
                                                                                            <input
                                                                                                placeholder="P.A. Sisdolica"
                                                                                                title="Presion arterial Sisdolica"
                                                                                                class="form-control"
                                                                                                type="text"
                                                                                                id="pa_sisdolica_dt"
                                                                                                name="pa_sisdolica_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                            <span
                                                                                                class="input-divisor">
                                                                                                / </span>
                                                                                            <input
                                                                                                placeholder="P.A. Diastolica"
                                                                                                title="Presion arterial Diastolica"
                                                                                                class="form-control"
                                                                                                type="text"
                                                                                                id="pa_diastolica_dt"
                                                                                                name="pa_diastolica_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>PRESION ARTERIAL MEDIA
                                                                                            (PAM)</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input
                                                                                                title="PRIMERO INGRESE LA PRESION ARTERIAL"
                                                                                                readonly
                                                                                                class="form-control"
                                                                                                type="text"
                                                                                                id="pam_dt"
                                                                                                name="pam_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>FREC. CARDIACA (/min)</b>
                                                                                    </td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input class="form-control"
                                                                                                type="text"
                                                                                                id="pulso_fc_dt"
                                                                                                name="pulso_fc_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>FREC. RESPIRATORIA (/min)</b>
                                                                                    </td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input class="form-control"
                                                                                                type="text"
                                                                                                id="frecuencia_respiratoria_dt"
                                                                                                name="frecuencia_respiratoria_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>TEMPERATURA (°C)</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input class="form-control"
                                                                                                type="text"
                                                                                                id="temperatura_dt"
                                                                                                name="temperatura_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>Sa02 (%)</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input class="form-control"
                                                                                                type="text"
                                                                                                id="sa02_dt"
                                                                                                name="sa02_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="modal-tr">
                                                                                    <td><b>Fi02 (%)</b></td>
                                                                                    <td class="monitoreo-td">
                                                                                        <div class="various-inputs">
                                                                                            <input class="form-control"
                                                                                                type="text"
                                                                                                id="fi02_dt"
                                                                                                name="fi02_dt"
                                                                                                onkeypress="return esEntero(event)">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="tab-pane tabMoni_dt" id="tab2defaultMonitoreo_dt">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span class=""><b>FIJACION DE
                                                                                        TUBO (F/V)</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class="" id="ft_der_dt"
                                                                                    name="ft_options_dt"
                                                                                    type="radio">
                                                                                <input type="text" hidden
                                                                                    id="tubo_der_dt"
                                                                                    name="tubo_der_dt" value="0">
                                                                                <label for="ft_der_dt">DERECHO</label>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class="" id="ft_izq_dt"
                                                                                    name="ft_options_dt"
                                                                                    type="radio">
                                                                                <input type="text" hidden
                                                                                    id="tubo_izq_dt"
                                                                                    name="tubo_izq_dt" value="0">
                                                                                <label
                                                                                    for="ft_izq_dt">IZQUIERDO</label>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="ft_valor_dt">VALOR</label>
                                                                                <input disabled class="form-control"
                                                                                    id="ft_valor_dt"
                                                                                    name="ft_valor_dt" type="text">
                                                                                <input class="form-control"
                                                                                    id="fijacion_der_dt"
                                                                                    name="fijacion_der_dt"
                                                                                    type="hidden">
                                                                                <input class="form-control"
                                                                                    id="fijacion_izq_dt"
                                                                                    name="fijacion_izq_dt"
                                                                                    type="hidden">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span class=""><b>MODOS DE
                                                                                        VENTILACION</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class=""
                                                                                    id="vmi_dt_toggle" name="vm_dt"
                                                                                    type="radio" disabled>
                                                                                <input type="text" hidden
                                                                                    id="active_vmi_dt"
                                                                                    name="active_vmi_dt"
                                                                                    value="0">
                                                                                <label for="vmi_dt">V.M.I.</label>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class=""
                                                                                    id="vmni_dt_toggle" name="vm_dt"
                                                                                    type="radio" disabled>
                                                                                <input type="text" hidden
                                                                                    id="active_vmni_dt"
                                                                                    name="active_vmni_dt"
                                                                                    value="0">
                                                                                <label for="vmni_dt">V.M.N.I</label>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label
                                                                                    for="">DESCRIPCION</label>
                                                                                <input disabled class="form-control"
                                                                                    id="vm_desc_dt" name="vm_desc_dt"
                                                                                    type="text">
                                                                                <input class="form-control"
                                                                                    id="vmi_dt" name="vmi_dt"
                                                                                    type="hidden">
                                                                                <input class="form-control"
                                                                                    id="vmni_dt" name="vmni_dt"
                                                                                    type="hidden">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span
                                                                                    class=""><b>MODALIDAD:ASIS./CONTR.</b></span>
                                                                                <input type="hidden"
                                                                                    name="modalidad_dt"
                                                                                    id="modalidad_final_dt">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class=""
                                                                                    id="md_presion_dt" name="md_dt"
                                                                                    type="radio">
                                                                                <label
                                                                                    for="md_presion_dt">PRESION</label>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input class=""
                                                                                    id="md_volumen_dt" name="md_dt"
                                                                                    type="radio">
                                                                                <label
                                                                                    for="md_volumen_dt">VOLUMEN</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>VOLUMEN TIDAL</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="volumen_tiral_dt"
                                                                                    name="volumen_tiral_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>PEEP</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    name="peed_dt" id="peed_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>FR (M/P) ?</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="frecuencia_m_dt"
                                                                                    name="frecuencia_m_dt">
                                                                                <span style="margin: 0 5px;">/</span>
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="frecuencia_p_dt"
                                                                                    name="frecuencia_p_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>VOL/MIN</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="vol_min_dt" name="vol_min_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>T. INSPIRACION:T.
                                                                                        EXPIRACION</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="i_e_dt" name="i_e_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>PRESION SOPORTE</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="presion_soporte_dt"
                                                                                    name="presion_soporte_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>P. MAXIMA</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="p_maxima_dt"
                                                                                    name="p_maxima_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>P. LIMITE</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="p_limite_dt"
                                                                                    name="p_limite_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>CO2</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="co2_dt" name="co2_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>P. PICCO</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="p_picco_dt" name="p_picco_dt">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="tab-pane tabMoni_dt" id="tab3defaultMonitoreo_dt">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <div class="row col-md-12"
                                                                            style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                                                            <table id="tblInfusiones_dt"
                                                                                class="display">
                                                                                <thead></thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="tab-pane tabMoni_dt" id="tab4defaultMonitoreo_dt">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <div class="table-responsive">
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span
                                                                                    style="color: green"><b>DIURESIS</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="diurisis_horaria_dt"
                                                                                    name="diurisis_horaria_dt">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 m-respiratorio">
                                                                            <div class="col-md-3">
                                                                                <span><b>HEMOGLUCOTEST</b></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    id="hemoglucotest_dt"
                                                                                    name="hemoglucotest_dt">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
