<div class="modal fade" id="modalDetalleRequerimiento" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <input type="hidden" id="req_id-det">
    
    <div class="modal-dialog modal-xxl">

        <div class="modal-content">

            <div class="modal-header"
                style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tituloModalDetalleRequerimiento"></h4>
            </div>

            <div class="modal-body">

                <form id="">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row row-flex">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Requerimiento: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_req-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Fecha y hora de registro:
                                                        </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="fecha_registro-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Tipo de Producto: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="tipo_producto-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-detalle-info">
                                                        <label class="form-control-static">Nro. Pedido SIGA: </label>
                                                        <p class="form-control-static content_requerimiento"
                                                            id="nro_pedido_siga-det">REQ-000123
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="container_button-recepcionar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-enviar-proceso" type="button"
                                                    id="btnEnviarRequerimiento" disabled>ENVIAR</button>
                                                <span id="info_req-anulado" style="color: red; display: block;"><i>El
                                                        registro se
                                                        encuentra anulado.</i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="container_estado-recepcionar" class="col-md-2 col-center-vertical"
                                    style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p class=""
                                                    style="font-weight: bold; color: #3c763d; font-style: italic;" id="descripcion_estado-req">
                                                    ENVIADO</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="" class="control-label">Denominacion del
                                        Requerimiento</label>
                                    <textarea class="form-control" disabled id="denominacion_req-det">Este es un texto largo que viene del backend...</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <fieldset class="mi-fieldset">

                        <div class="row">
                            <div class="col-sm-12" style="padding: 5px 15px;">
                                <div class="form-group">
                                    <label for="pfdm_nombre-det" class="control-label">Producto Requerido</label>
                                    <input type="text" id="pfdm_nombre-det" name="pfdm_nombre-det"
                                        class="form-control" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="container_inputs-pfdm">

                            <div class="col-sm-3" style="padding: 5px 15px;">
                                <div class="form-group">
                                    <label for="codigo_siga-producto-det" class="control-label">Codigo
                                        SIGA</label>
                                    <input type="text" class="form-control" id="codigo_siga-producto-det" disabled>
                                </div>
                            </div>

                            <div class="col-sm-3" style="padding: 5px 15px;">
                                <div class="form-group">
                                    <label for="codigo_sismed-producto-det" class="control-label">Codigo
                                        SISMED</label>
                                    <input type="text" class="form-control" disabled
                                        id="codigo_sismed-producto-det">
                                </div>
                            </div>

                            <div class="col-sm-3" style="padding: 5px 15px;">
                                <div class="form-group">
                                    <label for="unidad_medida-producto-det" class="control-label">Unidad
                                        Medida</label>
                                    <input type="text" class="form-control" disabled
                                        id="unidad_medida-producto-det">
                                </div>
                            </div>

                            <div class="col-sm-3" style="padding: 5px 15px;">
                                <div class="form-group">
                                    <label for="cantidad_total-det" class="control-label">Cantidad Total</label>
                                    <input type="text" class="form-control" id="cantidad_total-det"
                                        name="cantidad_total-det" disabled>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <fieldset class="mi-fieldset" style="padding: 15px 10px; margin-bottom: 0;"
                                id="fieldsetTableEntregas">
                                <table class="display" id="tableEntregas-det" cellspacing="0" width="100%">
                                    <tbody id="tblRowEntregas-det" class="fill-rows_required">
                                    </tbody>
                                </table>
                            </fieldset>
                            <p style="margin: 0; margin: 17px; margin-top: 1px;">
                                <span class="error-text" id="errorEntregas">Debe registrar entregas</span>
                            </p>
                        </div>
                    </fieldset>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
