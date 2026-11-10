<script>
    $(document).ready(function() {
        $('#tablaReportePorIpress3').on('click', '.btn-export-CAP', function() {
            $("#modalReporteProc1").modal("show");
        });

        const table3 = $('#tablaReportePorIpress3').DataTable();

        $('#tablaReportePorIpress3 tbody').on('click', '.btn-export-CAP', function() {
            const $button = $(this);
            const $row = $button.closest('tr');
            const rowData = table3.row($row).data();
            $("#ipress_id_extra").val(rowData.id_ipress);
        });

        $('#modalReporteProc1').on('hidden.bs.modal', function() {
            const modal = $(this);

            modal.find('input').val('');
            modal.find('select').prop('selectedIndex', 0).trigger('change');
            modal.find('textarea').val('');

            if ($.fn.DataTable.isDataTable('#tablaReportePorIpress5')) {
                $('#tablaReportePorIpress5').DataTable().clear().draw();
            }
        });

        $("#periodoExtraPROC").on("change", function(e) {
            const periodoProcExtra = $("#periodoExtraPROC").val();
            const tipoExamen = $("#tipoExamenCAsub").val();
            const ipress_id = $("#ipress_id_extra").val();
            if (periodoProcExtra == "") {
                return;
            }

            Swal.fire({
                title: "Cargando...",
                text: "Obteniendo información",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "/listarConsolidadoAccionesProcedimientoExtra",
                type: "POST",
                data: {
                    ipress_id,
                    tipo_examen: tipoExamen,
                    fechaIni: primerDiaMes(periodoProcExtra),
                    fechaFin: ultimoDiaMes(periodoProcExtra),
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.close();
                    let tabla = $("#tablaReportePorIpress5").DataTable();
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
        });

        $("#btnExportCAP_extra").on("click", function(e) {
            const periodoExtraPROC = $("#periodoExtraPROC").val();
            const tipoExamen = $("#tipoExamenCAsub").val();
            let tipoReporte = "excel";
            if (periodoExtraPROC == "") {
                alert("Debe ingresar el periodo.");
                return;
            }
            let urlProcExtra = "/exportConsolidadoAccionesProcedimientoExtra?" +
                "&fechaIni=" + primerDiaMes(periodoExtraPROC) +
                "&fechaFin=" + ultimoDiaMes(periodoExtraPROC) +
                "&tipoExamen=" + tipoExamen +
                "&tipoReporte=" + tipoReporte;

            window.location.href = urlProcExtra;
        });
    });
</script>
<!-- Modal -->
<div class="modal fade" id="modalReporteProc1" tabindex="-1" role="dialog" aria-labelledby="modalIpressLabel"
    style="z-index: 1051;">
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

                <!-- CONTENIDO DE LAS TABS -->
                <div class="tab-content" style="border: 1px solid #ddd; border-top: none; padding: 20px;">

                    <!-- TAB ATENCIONES -->
                    <div role="tabpanel" class="tab-pane active" id="">

                        <!-- FILTROS -->
                        <div class="panel panel-default" style="border: 1px solid #000;">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="periodoExtraPROC">Periodo</label>
                                            <input type="month" id="periodoExtraPROC" class="form-control"
                                                placeholder="">
                                            <input type="hidden" id="ipress_id_extra">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button id="btnExportCAP_extra" class="btn btn-primary" type="button">
                                    <i class="fa fa-file-excel-o"></i> Exportar
                                </button>
                            </div>
                        </div>

                        <!-- TABLA -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <table id="tablaReportePorIpress5"
                                    class="table table-bordered table-condensed text-center" width="100%">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th>IPRESS</th>
                                            <th>Nombre IPRESS</th>
                                            <th>Cod. Procedimiento</th>
                                            <th>Nombre Procedimiento</th>
                                            <th>Cantidad Atendida</th>
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

        </div>
    </div>
</div>
