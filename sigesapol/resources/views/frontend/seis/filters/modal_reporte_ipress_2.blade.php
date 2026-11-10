<script>
    $(document).ready(function() {
        $('#tablaReportePorIpress2').on('click', '.btn-export-CAF', function() {
            $("#modalReporteFarm1").modal("show");
        });

        $('#modalReporteFarm1').on('hidden.bs.modal', function() {
            const modal = $(this);

            modal.find('input').val('');
            modal.find('select').prop('selectedIndex', 0).trigger('change');
            modal.find('textarea').val('');

            if ($.fn.DataTable.isDataTable('#tablaReportePorIpress4')) {
                $('#tablaReportePorIpress4').DataTable().clear().draw();
            }
        });

        $("#periodoExtraFAR").on("change", function(e) {
            const periodoFarExtra = $("#periodoExtraFAR").val();
            if (periodoFarExtra == "") {
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
                url: "/listarConsolidadoAccionesFarmaciaExtra",
                type: "POST",
                data: {
                    fechaIni: primerDiaMes(periodoFarExtra),
                    fechaFin: ultimoDiaMes(periodoFarExtra),
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.close();
                    let tabla = $("#tablaReportePorIpress4").DataTable();
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

        $("#btnExportCAF_extra").on("click", function(e) {
            const periodoFarExtra = $("#periodoExtraFAR").val();
            let tipoReporte = "excel";
            if (periodoFarExtra == "") {
                alert("Debe ingresar el periodo.");
                return;
            }
            let urlFarExtra = "/exportConsolidadoAccionesFarmaciaExtra?" +
                "&fechaIni=" + primerDiaMes(periodoFarExtra) +
                "&fechaFin=" + ultimoDiaMes(periodoFarExtra) +
                "&tipoReporte=" + tipoReporte;

            window.location.href = urlFarExtra;
        });
    });
</script>
<!-- Modal -->
<div class="modal fade" id="modalReporteFarm1" tabindex="-1" role="dialog" aria-labelledby="modalIpressLabel"
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
                                            <label for="periodoExtraFAR">Periodo</label>
                                            <input type="month" id="periodoExtraFAR" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button id="btnExportCAF_extra" class="btn btn-primary" type="button">
                                    <i class="fa fa-file-excel-o"></i> Exportar
                                </button>
                            </div>
                        </div>

                        <!-- TABLA -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <table id="tablaReportePorIpress4"
                                    class="table table-bordered table-condensed text-center" width="100%">
                                    <thead>
                                        <tr style="background-color: #e5f2e0;">
                                            <th>IPRESS</th>
                                            <th>Nombre IPRESS</th>
                                            <th>Cod. Producto</th>
                                            <th>Nombre Producto</th>
                                            <th>Cantidad Solicitada</th>
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
