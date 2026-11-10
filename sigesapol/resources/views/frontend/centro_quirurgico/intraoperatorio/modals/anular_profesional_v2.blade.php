<div class="modal fade" id="modalAnularProfesional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <div style="display: flex; flex-direction: row; justify-content: space-between; gap: 10px;">
                    <b id="tituloFormularioProfesional-anular"></b>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>
            </div>

            <div class="modal-body">
                <form id="anularFormularioProfesional" name="anularFormularioProfesional" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="id_medico_anular" name="id_medico">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <h2>Motivo de Anulación</h2>
                            <textarea id="anulacion_motivo" class="form-control" name="anulacionMotivo" rows="4" cols="50"
                                placeholder="Escribe el motivo de la anulación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCancelar" class="btn btn-danger pull-left">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="GuardarAnularProfesional"
                            value="create">Anular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>