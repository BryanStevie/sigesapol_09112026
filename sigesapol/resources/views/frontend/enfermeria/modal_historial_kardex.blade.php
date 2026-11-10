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
    $(document).on('click', '#btnShowDetailKardexEnf', async function() {
        const kardex_id = $(this).data('id');
        const tipo_atencion_id = $(this).data('tipo_atencion_id');

        const res = await getKardexEnfByIdAndTipoAtencionId(tipo_atencion_id, kardex_id);
        if (res) {
            setFormValues(res, tipo_atencion_id);
        }
    });

    $(document).ready(function() {});

    function getKardexEnfByIdAndTipoAtencionId(tipo_atencion_id, kardex_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: `/api/kardex-enfermerias/detalle/${kardex_id}/${tipo_atencion_id}`,
                dataType: "json",
                beforeSend: function() {
                    Swal.fire({
                        html: '<h5>OBTENIENDO INFORMACIÓN..</h5>',
                        showLoaderOnConfirm: true,
                        onOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(err) {
                    reject(err)
                },
                complete: function() {
                    Swal.hideLoading();
                    Swal.close();
                }
            });
        });
    }

    function setFormValues(data, tipoAtencionId) {
        setValuesKardexEnfermeria(data);
        $('#modalShowDetailKardex').modal('show');
        /* const nivel_ipress = $("#nivel_ipress").val();
        if (parseInt(tipoAtencionId) === 2) {}
        if (parseInt(tipoAtencionId) === 3) {} */
    }

    function setValuesKardexEnfermeria(data) {
        if (data && JSON.stringify(data) !== "{}") {
            $("#mkr_fecha_atencion_detalle").val(moment(data.kardex_enfermerias_created_at).format('YYYY-MM-DD') ||
                "-");
            $("#mkr_hora_atencion_detalle").val(moment(data.kardex_enfermerias_created_at).format('HH:mm') || "-");
            $("#mkr_prioridad_atencion_detalle").val(data.consultorio_nombre || "-");
            $("#mkr_turno_detalle").val(data.turno_nombre || "-");

            if (data.medicamentos && data.medicamentos.length > 0) {
                buildRowsTableMedicamentos(data.medicamentos);
            }

            setValuesTableExamenesLab(data.examenesLaboratorio);
            setValuesTableExamenesImagen(data.examenesImagen);
            setValuesCuidadosEnfermero(data.cuidadoEnfermeros);
            setValuesTableInterconsulta(data.interconsultas);
        }
    }

    function buildRowsTableMedicamentos(data) {
        $('#tableMedicamentosKardexDetalle tbody').empty();
        const sections = {
            0: "HIDRATACIONES",
            1: "INFUSIONES",
            2: "ANTIBIOTICOS",
            3: "OTROS",
            4: "INHALADORES",
            5: "TRASFUSIONES",
            6: "HEMOGLUCOTESTES",
        }
        let rowString = "";
        let titleSection = "";
        if (data && data.length > 0) {
            const medicamentosPorFlagRegistro = agruparMedicamentosPorFlagRegistro(data);
            const tbody = $('#tableMedicamentosKardexDetalle tbody');
            $.each(medicamentosPorFlagRegistro, function(flag_registro, medicamentos) {
                titleSectionHtml =
                    `<tr><td colspan="7" class="titulo-categoria"><strong>${sections[parseInt(flag_registro)] || "OTROS"}</strong></td></tr>`;
                tbody.append(titleSectionHtml);

                $.each(medicamentos, function(index, medicamento) {
                    tbody.append(buildRowHtmlString(medicamento));
                });
            });
        }
    }

    function buildRowHtmlString(data) {
        let timesString = "";
        if (data.horarios && data.horarios.length > 0) {
            data.horarios.forEach((horario, index) => {
                if (horario.aplicacion) {
                    timesString +=
                        `<b class='strikethrough'>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b> - `;
                } else {
                    timesString += `<b>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b> - `;
                }
            });
        }
        return ` 
      <tr>
          <td>
              <p>${data.id_infusion ? data.infusion_descripcion : data.medicamento || "-"}</p>
              <p>${timesString}</p>
          </td>
          <td>${data.usuario_nombre || ""} ${data.usuario_paterno || ""} ${data.usuario_materno || ""}</td>
          <td>${data.via_administracion_descripcion || "-"}</td>
          <td>C/${data.frecuencia || "0"}</td>
          <td>${data.dias_administracion || "0"} DÍAS</td>
      </tr>`;
    }

    function setValuesTableExamenesLab(data) {
        $('#tableExamAuxLabDetalle').DataTable().destroy();
        $('#tableExamAuxLabDetalle').DataTable({
            data: data,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            columns: [{
                    data: 'fecha',
                    title: 'FECHA'
                },
                {
                    data: 'hora',
                    title: 'HORA'
                },
                {
                    data: 'nombre_procedimiento',
                    title: 'NOMBRE EXAMEN'
                },
                {
                    data: null,
                    title: 'TOMA DE MUESTRA',
                    render: function(data, type, row) {
                        return parseInt(row.toma_muestra) === 1 ? "SI" : "NO";
                    }
                },
                {
                    data: 'ciudad',
                    title: 'RESULTADO',
                    render: function(data, type, row) {
                        return parseInt(row.resultado) === 1 ? "SI" : "NO";
                    }
                },
                {
                    data: 'ciudad',
                    title: 'SIN REACTIVO',
                    render: function(data, type, row) {
                        return parseInt(row.reactivo) === 1 ? "SI" : "NO";
                    }
                }
            ]
        });
    }

    function setValuesTableExamenesImagen(data) {
        $('#tableExamAuxImagDetalle').DataTable().destroy();
        $('#tableExamAuxImagDetalle').DataTable({
            data: data,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            columns: [{
                    data: 'fecha',
                    title: 'FECHA'
                },
                {
                    data: 'hora',
                    title: 'HORA'
                },
                {
                    data: 'nombre_imagen',
                    title: 'NOMBRE EXAMEN'
                },
                {
                    data: null,
                    title: 'TOMA DE IMAGEN',
                    render: function(data, type, row) {
                        return parseInt(row.toma_imagen) === 1 ? "SI" : "NO";
                    }
                }
            ]
        });
    }

    function setValuesCuidadosEnfermero(data) {
        if (data && JSON.stringify(data) !== "{}") {
            $("#control_funciones_vitales_detalle").html(parseInt(data.cfv) === 1 ? "SI" : "NO");
            $("#barrera_hematoencefalica_detalle").html(parseInt(data.bhe) === 1 ? "SI" : "NO");
            $("#cabecera_detalle").val(data.cabecera || "-");
            $("#id_oxigeno_terapia_detalle").val(data.oxigeno_terapia_descripcion || "-");
            $("#oxigenoterapia_cantidad_detalle").val(data.oxigenoterapia_cantidad || "-");
            $("#movilización_detalle").html(parseInt(data.movilizacion) === 1 ? "SI" : "NO");
            $("#hemoglucotest_detalle").val(data.hemoglucotest || "-");
        }
    }

    function setValuesTableInterconsulta(data) {
        $('#tableInterconsultaDetalle').DataTable().destroy();
        $('#tableInterconsultaDetalle').DataTable({
            data: data,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            columns: [{
                    data: 'consultorio_nombre',
                    title: 'NOMBRE DEL SERVICIO'
                },
                {
                    data: null,
                    title: 'FECHA ENVIO',
                    render: function(data, type, row) {
                        return moment(row.fecha_envio, "YYYY-MM-DD").format("DD/MM/YYYY");
                    }
                },
                {
                    data: null,
                    title: 'FECHA RESPUESTA',
                    render: function(data, type, row) {
                        return moment(row.fecha_respuesta, "YYYY-MM-DD").format("DD/MM/YYYY");
                    }
                }
            ]
        });
    }

    function setValuesTableDiagnosticos(data) {
        $('#mkr_table_diagnostivos_atencion_detalle').DataTable().destroy();
        $('#mkr_table_diagnostivos_atencion_detalle').DataTable({
            data: data,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            columns: [{
                    data: 'codigo_diagnostico',
                    title: 'CODIGO'
                },
                {
                    data: 'nombre_diagnostico',
                    title: 'DESCRIPCIÓN'
                },
                {
                    data: 'tipo_diagnostico',
                    title: 'TIPO'
                },
                {
                    data: 'clasificacion',
                    title: 'CLASIFICACIÓN'
                }
            ]
        });
    }

    const agruparMedicamentosPorFlagRegistro = (data) => {
        return data.reduce((acc, medicamento) => {
            (acc[medicamento.flag_registro] = acc[medicamento.flag_registro] || []).push(medicamento);
            return acc;
        }, {});
    };
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
                                        <th>N°</th>
                                        <th>FECHA/HORA KARDEX</th>
                                        <th>DATOS LA ATENCIÓN</th>
                                        <th>UBICACIÓN</th>
                                        <th>ENFERMERA QUE REALIZO EL KARDEX</th>
                                        <th>VER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($kardexes) && count($kardexes) > 0)
                                        @foreach ($kardexes as $dato)
                                            <tr>
                                                <td>{{ $dato->kardex_enfermerias_id }}</td>
                                                <td>
                                                    <div>
                                                        <p><b>Fecha: </b>{{ $dato->fecha ? $dato->fecha : ' - ' }}</p>
                                                        <p><b>Hora: </b>{{ $dato->hora ? $dato->hora : ' - ' }}</p>
                                                        <p><b>Turno: </b>{{ $dato->turno ? $dato->turno : ' - ' }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($tipo_atencion == 3)
                                                        <div>
                                                            <p>
                                                                <b>Servicio: </b>
                                                                {{ $dato->servicio ? $dato->servicio : ' - ' }}
                                                            </p>
                                                        </div>
                                                    @elseif($tipo_atencion == 2)
                                                        <div>
                                                            <p><b>Area: </b>
                                                                {{ $dato->servicio ? $dato->servicio : ' - ' }}</p>
                                                            <p><b>Prioridad: </b> {{ 'II - Urgencia Mayor' }}</p>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tipo_atencion == 3)
                                                        <div>
                                                            <p><b>Cama: </b> {{ $dato->cama }}</p>
                                                            <p><b>Cuarto: </b> {{ $dato->cuarto }}</p>
                                                        </div>
                                                    @elseif($tipo_atencion == 2)
                                                        <div>
                                                            <p><b>Cuarto: </b> {{ $dato->cuarto }}</p>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $dato->medico_nombre ? $dato->medico_nombre : '' }}
                                                    {{ $dato->medico_paterno ? $dato->medico_paterno : '' }}
                                                    {{ $dato->medico_materno ? $dato->medico_materno : '' }}</td>
                                                <td style="color: #003366;">
                                                    <div>
                                                        <p>
                                                            <button id="btnShowDetailKardexEnf" type="button"
                                                                class="btn btn-success btn-xs"
                                                                data-id="{{ $dato->kardex_enfermerias_id }}"
                                                                data-tipo_atencion_id="{{ $tipo_atencion }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Ver detalle">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </button>
                                                        </p>
                                                    </div>
                                                </td>
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
    <div class="modal fade" id="modalShowDetailKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document" style="width: 85%;">
            <div class="modal-content">

                <div class="modal-header"
                    style="font-size: 20px !important; border-top-left-radius: 5px; border-top-right-radius: 5px">
                    <button id="btnCloseModal" type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b>VER DETALLES KARDEX</b>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" id="alert_kardex_anulado" style="display: none">El
                        kardex ha sido anulado y no se puede realizar ninguna modificación</div>
                    <fieldset>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_kardex" id="id_kardex">
                        <input type="hidden" id="argumentos_kardex" value="" />
                        <input type="hidden" name="doc_firmar" id="doc_firmar">
                        <input type="hidden" name="opt_firma" id="opt_firma">
                        <input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
                        <input type="hidden" name="flag_firmar" id="flag_firmar" value="1">
                    </fieldset>

                    {{-- <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>DIAGNOSTICOS DE LA ATENCIÓN</b>
                        </div>
                        <div class="panel-body">
                            <table id="mkr_table_diagnostivos_atencion_detalle" class="table table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th>TIPO</th>
                                        <th>CLASIFICACIÓN</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading text-center">
                            <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                        </div>
                        <div class="panel-body">
                            <div class="row" style="padding: 0px 15px">
                                <div class="form-group col-12">
                                    <label for="mkr_tratamiento">Tratamiento Auxiliar</label>
                                    <textarea id="mkr_tratamiento_detalle" class="form-control" name="mkr_tratamiento_detalle" rows="2" readonly></textarea>
                                </div>
                            </div> 
                        </div>
                    </div>
                </fieldset> --}}

                    <fieldset>
                        <div class="panel with-nav-tabs panel-default"
                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs" id="tabs_kardex_register_detalle">
                                    <li class="active">
                                        <a href="#mkr_tab_kardex_detalle" data-toggle="tab" style="font-size:12px"
                                            aria-expanded="true">
                                            <b><i class="fa fa-list-ol" aria-hidden="true"></i> KARDEX</b>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#mkr_tab_examenes_auxiliares_detalle" data-toggle="tab"
                                            style="font-size:12px" aria-expanded="true">
                                            <b><i class="fa fa-file-text-o" aria-hidden="true"></i> EXAMENES
                                                AUXILIARES</b>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#mkr_tab_cuidado_enfermero_detalle" data-toggle="tab"
                                            style="font-size:12px" aria-expanded="true">
                                            <b><i class="fa fa-user-md" aria-hidden="true"></i> CUIDADO DEL
                                                ENFERMERO</b>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#mkr_tab_interconsulta_detalle" data-toggle="tab"
                                            style="font-size:12px" aria-expanded="true">
                                            <b><i class="fa fa-phone" aria-hidden="true"></i> INTERCONSULTA</b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel-body" style="padding-bottom: 0px !important;">
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="mkr_tab_kardex_detalle">
                                        <div class="panel panel-info panel-bordered panel-sm"
                                            style="background-color: #F0F0F0">
                                            <div class="panel-body">
                                                <table id="tableMedicamentosKardexDetalle" class="table table-striped"
                                                    style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 30%">MEDICAMENTOS </th>
                                                            <th style="width: 30%">ENFERMER@ QUE REALIZO EL KARDEX</th>
                                                            <th style="width: 20%">VIA</th>
                                                            <th style="width: 10%">FRECUENCIA</th>
                                                            <th style="width: 10%">DIAS DE ADMINISTRACION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="mkr_tab_examenes_auxiliares_detalle">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>EXAMENES DE LABORATORIO</b>
                                            </div>
                                            <div class="panel-body">
                                                <table id="tableExamAuxLabDetalle" class="table table-striped"
                                                    style="width: 100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">FECHA</th>
                                                            <th style="width: 10%">HORA</th>
                                                            <th style="width: 41%">NOMBRE EXAMEN</th>
                                                            <th style="width: 15%">TOMA DE MUESTRA</th>
                                                            <th style="width: 7%">RESULTADO</th>
                                                            <th style="width: 7%">SIN REACTIVO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading text-center">
                                                <b>EXAMENES DE IMAGENOLOGÍA</b>
                                            </div>
                                            <div class="panel-body">
                                                <table id="tableExamAuxImagDetalle" class="table table-striped"
                                                    style="width: 100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">FECHA</th>
                                                            <th style="width: 10%">HORA</th>
                                                            <th style="width: 70%">NOMBRE EXAMEN</th>
                                                            <th style="width: 10%">TOMA DE IMAGEN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="mkr_tab_cuidado_enfermero_detalle">
                                        <fieldset class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Control de Funciones Vitales
                                                    (CFV) :</label>
                                                <div class="col-sm-9">
                                                    <span id="control_funciones_vitales_detalle"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Balance Hídrico y Electrolítico (BHE)
                                                    :</label>
                                                <div class="col-sm-9">
                                                    <span id="barrera_hematoencefalica_detalle"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Cabecera :</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control input-sm"
                                                        id="cabecera_detalle" name="cabecera_detalle" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Oxigenoterapia : </label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control input-sm"
                                                                id="id_oxigeno_terapia_detalle"
                                                                name="id_oxigeno_terapia_detalle" readonly>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control input-sm"
                                                                id="oxigenoterapia_cantidad_detalle"
                                                                name="oxigenoterapia_cantidad_detalle" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Movilización cada 2 horas :
                                                </label>
                                                <div class="col-sm-9">
                                                    <span id="movilización_detalle"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Hemoglucotest :</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control input-sm"
                                                        id="hemoglucotest_detalle" name="hemoglucotest_detalle"
                                                        readonly>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane fade" id="mkr_tab_interconsulta_detalle">
                                        <table id="tableInterconsultaDetalle" class="table table-striped"
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 55%">NOMBRE DEL SERVICIO</th>
                                                    <th style="width: 20%">FECHA ENVIO</th>
                                                    <th style="width: 20%">FECHA RESPUESTA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="modal-footer">
                    <div class="row" style="margin-bottom: 20px">
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"></div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id="btnOkContainer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
