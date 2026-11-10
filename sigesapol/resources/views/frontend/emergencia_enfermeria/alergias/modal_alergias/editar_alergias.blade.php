<!-- Modal -->
<div class="modal fade" id="editarModalAlergiasM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="editTituloFormularioAlergiasM"></b>
            </div>

            <div class="modal-body">
                <form id="editarFormularioAlergiasM" name="editarFormularioAlergiasM" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="id_alergia_edit" name="id_alergia">
                                       
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">


                                <fieldset>
                                    <div class="form-group">
                                        <div class="panel-body" style="padding-bottom: 0px !important;">
                                            
                                            <table class="display" id="tblAlergia_editar" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                    <tr>
                                                        <th width="90%"></th>
                                                        <th width="10%"></th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left"
                    data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="actualizarAlergias"
                value="create">Guardar datos</button>
            </div>
        </div>
    </div>
</div>