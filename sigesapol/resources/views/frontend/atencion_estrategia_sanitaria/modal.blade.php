<div class="modal fade" id="modalAtencionEstrategia" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formAtencionEstrategia">
            {!! csrf_field() !!}
            <input type="hidden" name="id_registro_edit" id="id_registro_edit">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h4 class="modal-title" id="modalLabel">Nueva Atención de Estrategia Sanitaria</h4>
                </div>
                <div class="modal-body">
                    @include('frontend.atencion_estrategia_sanitaria._form')
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
