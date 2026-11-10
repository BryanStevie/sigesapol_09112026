<!-- Modal -->


<div class="modal fade" id="modalAnularEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        >&times;</span></button>
                <b id="tituloFormularioEvoluciones-anular"></b>
            </div>

            <div class="modal-body">
                <form id="anularFormularioEvoluciones" name="anularFormularioEvoluciones" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_evoluciones" name="id_evoluciones">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                          <h2>Motivo de Anulación</h2>
                          <textarea id="anulacion_motivo" class="form-control" name="anulacionMotivo" rows="4" cols="50" placeholder="Escribe el motivo de la anulación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="AnularEvoluciones"
                            value="create">Anular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>