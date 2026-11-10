<script>
    $(document).ready(function() {
        $("#nivelIPRESSCAP").on("change", function(e) {
            const valor = $(this).val();

            $.ajax({
                url: "/buscarTipoExamenCA",
                type: "POST",
                data: {
                    nivel_ipress: valor
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                success: function(response) {
                    $("#tipoExamenCAsub").empty();
                    $("#tipoExamenCAsub").append(
                        $("<option>", {
                            value: "",
                            text: "-- Seleccione --"
                        })
                    );
                    response.forEach(function(examen) {
                        $("#tipoExamenCAsub").append(
                            $("<option>", {
                                value: examen.id,
                                text: examen.nombre
                            })
                        );
                    });
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                }
            });
        });

        $("#btnBuscarSubReporteCAAt, #btnBuscarSubReporteCAFar, #btnBuscarSubReporteCAProc").on("click",
            function(e) {
                var activeTab = $('.nav-tabs-sub li.active a').attr('href');
                console.log(activeTab);
                switch (activeTab) {
                    case "#tabAtenciones":
                        const periodoAt = $("#periodoCA_atenciones").val();
                        const tipoAtencionAt = $("#tipoAtencionCAsub").val();
                        const tipoConsultaAt = $("#tipoConsultaCAsub").val();
                        if (periodoAt == "" /* || tipoAtencionAt == "" || tipoConsultaAt == "" */ ) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe rellenar los campos.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                        Swal.fire({
                            title: "Cargando...",
                            text: "Por favor espere",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "/listarConsolidadoAccionesAtenciones",
                            type: "POST",
                            data: {
                                fechaIni: primerDiaMes(periodoAt),
                                fechaFin: ultimoDiaMes(periodoAt),
                                tipoAtencionAt,
                                tipoConsultaAt,
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tablaReportePorIpress").DataTable();
                                tabla.clear();
                                tabla.rows.add(response);
                                tabla.draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                Swal.fire("Error", "Ocurrió un problema al obtener los datos",
                                    "error");
                            }
                        });
                        break;
                    case "#tabFarmacia":
                        const periodoFar = $("#periodoCA_farmacia").val();
                        if (periodoFar == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe ingresar el periodo.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                        Swal.fire({
                            title: "Cargando...",
                            text: "Por favor espere",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "/listarConsolidadoAccionesFarmacia",
                            type: "POST",
                            data: {
                                fechaIni: primerDiaMes(periodoFar),
                                fechaFin: ultimoDiaMes(periodoFar),
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tablaReportePorIpress2").DataTable();
                                tabla.clear();
                                tabla.rows.add(response);
                                tabla.draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                Swal.fire("Error", "Ocurrió un problema al obtener los datos",
                                    "error");
                            }
                        });
                        break;
                    case "#tabProcedimientos":
                        const periodoProc = $("#periodoCA_procedimientos").val();
                        const tipoExamenProc = $("#tipoExamenCAsub").val();
                        if (periodoProc == "" || tipoExamenProc == "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Advertencia',
                                text: 'Debe rellenar los campos.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            break;
                        }
                        Swal.fire({
                            title: "Cargando...",
                            text: "Por favor espere",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "/listarConsolidadoAccionesProcedimiento",
                            type: "POST",
                            data: {
                                fechaIni: primerDiaMes(periodoProc),
                                fechaFin: ultimoDiaMes(periodoProc),
                                tipo_examen: tipoExamenProc,
                            },
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.close();
                                let tabla = $("#tablaReportePorIpress3").DataTable();
                                tabla.clear();
                                tabla.rows.add(response);
                                tabla.draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                Swal.fire("Error", "Ocurrió un problema al obtener los datos",
                                    "error");
                            }
                        });
                        break;
                }
            });

        $("#btnExportCAAt, #btnExportCAFar, #btnExportCAProc").on("click", function(e) {
            var activeTab = $('.nav-tabs-sub li.active a').attr('href');
            let idBtn = e.currentTarget.id;
            let tipoReporte = null;
            if (idBtn == "btnExportCAAt" || idBtn == "btnExportCAFar" || idBtn == "btnExportCAProc") {
                tipoReporte = "excel";
            }
            switch (activeTab) {
                case "#tabAtenciones":
                    const periodoAt = $("#periodoCA_atenciones").val();
                    const tipoAtencionAt = $("#tipoAtencionCAsub").val();
                    const tipoConsultaAt = $("#tipoConsultaCAsub").val();
                    if (periodoAt == "" /* || tipoAtencionAt == "" || tipoConsultaAt == "" */ ) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe rellenar los campos.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        break;
                    }
                    let urlAt = "/exportConsolidadoAccionesAtenciones?" +
                        "&fechaIni=" + primerDiaMes(periodoAt) +
                        "&fechaFin=" + ultimoDiaMes(periodoAt) +
                        "&tipoAtencion=" + tipoAtencionAt +
                        "&tipoConsulta=" + tipoConsultaAt +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = urlAt;
                    break;
                case "#tabFarmacia":
                    const periodoFar = $("#periodoCA_farmacia").val();
                    if (periodoFar == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe ingresar el periodo.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        break;
                    }
                    let urlFar = "/exportConsolidadoAccionesFarmacia?" +
                        "&fechaIni=" + primerDiaMes(periodoFar) +
                        "&fechaFin=" + ultimoDiaMes(periodoFar) +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = urlFar;
                    break;
                case "#tabProcedimientos":
                    const periodoProc = $("#periodoCA_procedimientos").val();
                    const tipoExamenProc = $("#tipoExamenCAsub").val();
                    if (periodoProc == "" || tipoExamenProc == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe rellenar los campos.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        break;
                    }
                    let urlProc = "/exportConsolidadoAccionesProcedimiento?" +
                        "&fechaIni=" + primerDiaMes(periodoProc) +
                        "&fechaFin=" + ultimoDiaMes(periodoProc) +
                        "&tipoExamen=" + tipoExamenProc +
                        "&tipoReporte=" + tipoReporte;

                    window.location.href = urlProc;
                    break;
            }
        });

        $('#modalReportePorIpress').on('hidden.bs.modal', function() {
            const modal = $(this);

            modal.find('input').val('');
            modal.find('select').prop('selectedIndex', 0);
            modal.find('textarea').val('');

            if ($.fn.DataTable.isDataTable('#tablaReportePorIpress')) {
                $('#tablaReportePorIpress').DataTable().clear().draw();
            }
            if ($.fn.DataTable.isDataTable('#tablaReportePorIpress3')) {
                $('#tablaReportePorIpress3').DataTable().clear().draw();
            }
            if ($.fn.DataTable.isDataTable('#tablaReportePorIpress2')) {
                $('#tablaReportePorIpress2').DataTable().clear().draw();
            }

            modal.find('.nav-tabs-sub li:first a').tab('show');
        });
    });
</script>
<!-- Modal -->
<div class="modal fade" id="modalReportePorIpress" tabindex="-1" role="dialog" aria-labelledby="modalIpressLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- ENCABEZADO -->
            <div class="modal-header" style="border-bottom: 1px solid #000;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalIpressLabel"><strong>Seleccionar IPRESS</strong></h4>
            </div>

            <!-- CUERPO -->
            <div class="modal-body" style="">

                <!-- NAV TABS -->
                <ul class="nav nav-tabs nav-tabs-sub" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tabAtenciones" aria-controls="tabAtenciones" role="tab"
                            data-toggle="tab">Atenciones</a>
                    </li>
                    <li role="presentation">
                        <a href="#tabFarmacia" aria-controls="tabFarmacia" role="tab" data-toggle="tab">Farmacia</a>
                    </li>
                    <li role="presentation">
                        <a href="#tabProcedimientos" aria-controls="tabProcedimientos" role="tab"
                            data-toggle="tab">Procedimientos</a>
                    </li>
                </ul>

                <!-- CONTENIDO DE LAS TABS -->
                <div class="tab-content"
                    style="border: 1px solid #ddd; border-top: none; padding: 20px; padding-bottom: 0;">

                    <!-- TAB ATENCIONES -->
                    <div role="tabpanel" class="tab-pane active" id="tabAtenciones">

                        <!-- FILTROS -->
                        <div class="panel panel-default" style="">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="periodoCA_atenciones">Periodo</label>
                                            <input type="month" id="periodoCA_atenciones" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tipoAtencionCAsub">Tipo de Atención</label>
                                            <select id="tipoAtencionCAsub" class="form-control">
                                                <option value="">- Seleccionar -</option>
                                                <option value="1">Consulta Externa</option>
                                                <option value="2">Emergencia</option>
                                                <option value="3">Hospitalizacion</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tipoConsultaCAsub">Tipo de Consulta</label>
                                            <select id="tipoConsultaCAsub" class="form-control">
                                                <option value="">- Seleccionar -</option>
                                                <option>MEDICA</option>
                                                <option>NO MEDICA</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="button" id="btnExportCAAt">
                                    <i class="fa fa-file-excel-o"></i> Exportar
                                </button>
                                <button class="btn btn-info" type="button" id="btnBuscarSubReporteCAAt">
                                    <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- TABLA -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <table id="tablaReportePorIpress"
                                    class="table table-bordered table-condensed text-center" width="100%">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th>Periodo</th>
                                            <th>IPRESS</th>
                                            <th>Nombre IPRESS</th>
                                            <th>Categoria</th>
                                            <th>Tipo Atencion</th>
                                            <th>Tipo Consulta</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- TAB FARMACIA -->
                    <div role="tabpanel" class="tab-pane" id="tabFarmacia">
                        <!-- FILTROS -->
                        <div class="panel panel-default" style="">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="periodoCA_farmacia">Periodo</label>
                                            <input type="month" id="periodoCA_farmacia" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="button" id="btnExportCAFar">
                                    <i class="fa fa-file-excel-o"></i> Exportar
                                </button>
                                <button class="btn btn-info" type="button" id="btnBuscarSubReporteCAFar">
                                    <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- TABLA -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <table id="tablaReportePorIpress2"
                                    class="table table-bordered table-condensed text-center" width="100%">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th>Periodo</th>
                                            <th>IPRESS</th>
                                            <th>Nombre IPRESS</th>
                                            <th>Categoría</th>
                                            <th>Tipo Atención</th>
                                            <th>Total</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PROCEDIMIENTOS -->
                    <div role="tabpanel" class="tab-pane" id="tabProcedimientos">
                        <div class="panel panel-default" style="">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="periodoCA_procedimientos">Periodo</label>
                                            <input type="month" id="periodoCA_procedimientos" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nivelIPRESSCAP">Nivel IPRESS</label>
                                            <select id="nivelIPRESSCAP" class="form-control">
                                                <option value="">- Seleccionar -</option>
                                                <option value="1">I</option>
                                                <option value="2">II</option>
                                                <option value="3">III</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tipoExamenCAsub">Tipo de Examen</label>
                                            <select id="tipoExamenCAsub" class="form-control">
                                                <option value="">- Seleccionar -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="button" id="btnExportCAProc">
                                    <i class="fa fa-file-excel-o"></i> Exportar
                                </button>
                                <button class="btn btn-info" type="button" id="btnBuscarSubReporteCAProc">
                                    <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- TABLA -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <table id="tablaReportePorIpress3"
                                    class="table table-bordered table-condensed text-center" width="100%">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th>Periodo</th>
                                            <th>IPRESS</th>
                                            <th>Nombre IPRESS</th>
                                            <th>Categoría</th>
                                            <th>Tipo Examen</th>
                                            <th>Cantidad Examen</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
            {{-- <div class="modal-footer">
                <div class="row" style="width: 100%;">
                    <div class="col-md-6 text-left">
                        <label>Mostrar
                            <select class="form-control input-sm" style="display:inline-block; width:auto;">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            registros
                        </label>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary btn-sm">Anterior</button>
                        <input type="text" class="form-control input-sm" value="1"
                            style="display:inline-block; width:40px; text-align:center;">
                        <button class="btn btn-primary btn-sm">Siguiente</button>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</div>
