<!-- Modal -->
<div class="modal fade" id="modalAlergiasM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioAlergiasM"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioAlergiasM" name="FormularioAlergiasM" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    {{-- <input type="hidden" id="id_hospitalizacion_Alergias" name="id_hospitalizacion_Alergias"> --}}
                    <input type="hidden" id="detectado_en" name="detectado_en">
                    <input type="hidden" id="id_asegurado_alergia" name="id_asegurado">
                    <input type="hidden" id="id_servicio_alergia" name="id_servicio_alergia">
                    <input type="hidden" id="id_tipo_atencion_alergia" name="id_tipo_atencion" value="2">
                   
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">


                                <fieldset>
                                    <div class="form-group">
                                        <div class="panel-body" style="padding-bottom: 0px !important;">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <button tabindex="111" type="button" id="addAlergia2"
                                                            class="btn btn-success" data-toggle="modal"
                                                            data-target="#addClassModal">Agregar
                                                            Alergia</button>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br />
                                            <table class="display" id="tblAlergia" cellspacing="0" width="100%"
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
                <button type="submit" class="btn btn-success" id="registrarAlergias"
                    value="create">Registrar</button>
            </div>
        </div>
    </div>
</div>