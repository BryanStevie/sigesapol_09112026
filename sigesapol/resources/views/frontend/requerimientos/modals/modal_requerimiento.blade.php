<div class="modal fade" id="modalRequerimiento" tabindex="-1" role="dialog" aria-labelledby="nuevoRequerimientoLabel"
    aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header"
                style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tituloModalRequerimiento"></h4>
            </div>

            <div class="modal-body">
                <form id="frmSubmitReq">
                    <input type="hidden" name="req_id_upt" id="req_id_upt">

                    <div class="row">
                        <div class="col-md-3">
                            <label for="nro_requerimiento" class="control-label">Nro. Requerimiento</label>
                            <input type="text" class="form-control required" name="nro_requerimiento"
                                id="nro_requerimiento">
                            <span class="error-text">Debe ingresar el campo</span>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" style="visibility: hidden;">Espacio</label>
                                    <div>
                                        <input type="radio" name="tipo_documento" id="td_th" class=""
                                            style="margin-right: 3px;" checked value="HT">
                                        <label for="td_th" class="control-label">HT</label>
                                    </div>
                                </div>
                                <div class="col-md-8" id="container-desc_th">
                                    <label for="td_descripcion_th" class="control-label"
                                        style="visibility:hidden;">Espacio</label>
                                    <input type="text" class="form-control required" id="td_descripcion_th"
                                        name="td_descripcion_th">
                                    <span class="error-text">Debe ingresar el campo</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" style="visibility: hidden;">Espacio</label>
                                    <div>
                                        <input type="radio" name="tipo_documento" id="td_sgd" class=""
                                            style="margin-right: 3px;" value="SGD">
                                        <label for="td_sgd" class="control-label">SGD</label>
                                    </div>
                                </div>
                                <div class="col-md-8" id="container-desc_sgd" style="display: none;">
                                    <label for="td_descripcion_sgd" class="control-label"
                                        style="visibility:hidden;">Espacio</label>
                                    <input type="text" class="form-control" id="td_descripcion_sgd"
                                        name="td_descripcion_sgd" disabled>
                                    <span class="error-text">Debe ingresar el campo</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="mi-fieldset">
                            <legend>Datos de Requerimiento</legend>

                            <div class="row">

                                <div class="col-sm-2" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="nro_oficio" class="control-label">Nro. de Oficio</label>
                                        <input type="text" class="form-control required" name="nro_oficio"
                                            id="nro_oficio">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-4" style="padding: 5px 0;">
                                    <label class="" style="visibility:hidden;">Espacio</label>
                                    <div class=""
                                        style="display: flex; align-items: center; justify-content: left; height: 34px;">
                                        <p style="margin: 0;">
                                            <span style="font-weight: bold;">COMGEN
                                                PNP-DIRPSP-DIRSAPOL-OFIGES-UGPFDMPS</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-sm-6" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="fecha_oficio" class="control-label">Fec. Oficio</label>
                                        <input type="date" class="form-control required" name="fecha_oficio"
                                            id="fecha_oficio">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-2" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="nro_informe" class="control-label">Nro. de Informe</label>
                                        <input type="text" class="form-control required" name="nro_informe"
                                            id="nro_informe">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-4" style="padding: 5px 0;">
                                    <label class="" style="visibility:hidden;">Espacio</label>
                                    <div class=""
                                        style="display: flex; align-items: center; justify-content: left; height: 34px;">
                                        <p style="margin: 0;">
                                            <span style="font-weight: bold;">COMGEN
                                                PNP-DIRPSP-DIRSAPOL-OFIGES-UGPFDMPS</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-sm-6" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="fecha_informe" class="control-label">Fec. Informe</label>
                                        <input type="date" class="form-control required" name="fecha_informe"
                                            id="fecha_informe">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="tipo_requerimiento" class="control-label">Tipo de
                                            Requerimiento</label>
                                        <select class="form-control required" name="tipo_requerimiento"
                                            id="tipo_requerimiento">
                                            <option value="" selected>--- Seleccionar ---</option>
                                            <option value="1">ANUAL</option>
                                            <option value="2">EXTRAORDINARIO</option>
                                            <option value="3">EMERGENCIA</option>
                                            <option value="4">OTRO</option>
                                        </select>
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-9" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="obs_requerimiento" class="control-label">Observacion del
                                            Req.</label>
                                        <input type="text" class="form-control" name="obs_requerimiento"
                                            id="obs_requerimiento">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="tipo_prioridad" class="control-label">Prioridad</label>
                                        <select class="form-control required" name="tipo_prioridad" id="tipo_prioridad">
                                            <option value="" selected>--- Seleccionar ---</option>
                                            <option value="1">ALTA</option>
                                            <option value="2">MEDIA</option>
                                            <option value="3">BAJA</option>
                                        </select>
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-9" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="obs_prioridad" class="control-label">Observacion Prioridad</label>
                                        <input type="text" class="form-control required" name="obs_prioridad"
                                            id="obs_prioridad">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-10">

                                    <div class="row">

                                        <div class="col-sm-3" style="padding: 5px 15px;">
                                            <div class="form-group">
                                                <label for="proc_seleccion" class="control-label">Proced. de
                                                    Seleccion</label>
                                                <select class="form-control required" id="proc_seleccion"
                                                    name="proc_seleccion">
                                                    <option value="" selected>--- Seleccionar ---</option>
                                                    <option value="1">Compra menor a 8 UIT</option>
                                                    <option value="2">Compra mayor a 8 UIT</option>
                                                </select>
                                                <span class="error-text">Debe ingresar el campo</span>
                                            </div>
                                        </div>

                                        <div id="col-tipo_proceso" class="col-sm-3"
                                            style="padding: 5px 15px; display: none;">
                                            <div class="form-group">
                                                <label for="tipo_proceso" class="control-label">Tipo de
                                                    Proceso</label>
                                                <select class="form-control" disabled id="tipo_proceso"
                                                    name="tipo_proceso">
                                                    <option value="" selected>--- Seleccionar ---</option>
                                                    <option value="1">SUBASTA INVERSA ELECTRONICA</option>
                                                    <option value="2">LICITACION PUBLICA</option>
                                                    <option value="4">LICITACION PUBLICA ABREVIADA</option>
                                                    <option value="3">COMPRA DIRECTA</option>
                                                </select>
                                                <span class="error-text">Debe ingresar el campo</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-3" style="padding: 5px 15px;">
                                            <div class="form-group">
                                                <label for="tipo_producto" class="control-label">Tipo de
                                                    Producto</label>
                                                <select class="form-control required" id="tipo_producto"
                                                    name="tipo_producto">
                                                    <option value="" selected>--- Seleccionar ---</option>
                                                    @foreach ($tipos_producto as $tipo_producto)
                                                        <option value="{{ $tipo_producto->id }}">
                                                            {{ $tipo_producto->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error-text">Debe ingresar el campo</span>
                                            </div>
                                        </div>

                                        <div id="col-servicio_producto" class="col-sm-3" style="padding: 5px 15px;">
                                            <div class="form-group">
                                                <label for="servicio_producto" class="control-label">Servicio</label>
                                                <select class="form-control required" id="servicio_producto"
                                                    name="servicio_producto">
                                                    <option value="">--- Seleccionar ---</option>
                                                    @foreach ($servicio as $serv)
                                                        <option value="{{ $serv->id }}">
                                                            {{ $serv->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error-text">Debe ingresar el campo</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-sm-2" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="nro_pedido_siga" class="control-label">Nro. Pedido SIGA</label>
                                        <input class="form-control required" name="nro_pedido_siga"
                                            id="nro_pedido_siga">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="denominacion_requerimiento" class="control-label">Denominacion del
                                            Requerimiento</label>
                                        <textarea class="form-control required" name="denominacion_requerimiento"
                                            id="denominacion_requerimiento"></textarea>
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <fieldset class="mi-fieldset">
                            <legend>Agregar </legend>

                            <div class="row">
                                <div class="col-sm-12" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="buscadorPfDm" class="control-label">Productos Farmaceuticos
                                            y/o
                                            Dispositivos Medicos</label>
                                        <select name="buscadorPfDm" id="buscadorPfDm" class="form-control"
                                            style="width: 100%;">
                                        </select>
                                        <input type="hidden" id="pfdm_id" name="pfdm_id" class="required_pfdm">
                                        <span class="error-text">Debe seleccionar algun producto</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="container_inputs-pfdm">

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="codigo_siga-producto" class="control-label">Codigo
                                            SIGA</label>
                                        <input type="text" class="form-control" id="codigo_siga-producto" disabled>
                                    </div>
                                </div>

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="codigo_sismed-producto" class="control-label">Codigo
                                            SISMED</label>
                                        <input type="text" class="form-control" disabled id="codigo_sismed-producto">
                                    </div>
                                </div>

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="unidad_medida-producto" class="control-label">Unidad
                                            Medida</label>
                                        <input type="text" class="form-control" disabled id="unidad_medida-producto">
                                        <input type="hidden" id="unidad_medida" name="unidad_medida">
                                    </div>
                                </div>

                                <div class="col-sm-3" style="padding: 5px 15px;">
                                    <div class="form-group">
                                        <label for="cantidad_total" class="control-label">Cantidad Total</label>
                                        <input type="text" class="form-control required" id="cantidad_total"
                                            name="cantidad_total">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="total_entregas" class="control-label">Nro. Total de
                                            Entregas</label>
                                        <input type="number" min="1" class="form-control required" id="total_entregas"
                                            name="total_entregas" value="1">
                                        <span class="error-text">Debe ingresar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <fieldset class="mi-fieldset" style="padding: 15px 10px; margin-bottom: 0;"
                                    id="fieldsetTableEntregas">
                                    <table class="display" id="tableEntregas" cellspacing="0" width="100%">
                                        <tbody id="tblRowEntregas" class="fill-rows_required">
                                        </tbody>
                                    </table>
                                </fieldset>
                                <p style="margin: 0; margin: 17px; margin-top: 1px;">
                                    <span class="error-text" id="errorEntregas">Debe registrar entregas</span>
                                </p>
                            </div>
                        </fieldset>

                        <fieldset class="mi-fieldset" id="container-destino-requerimiento">

                            <legend>Destino</legend>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="destino_unidad" class="control-label">Unidad</label>
                                        <select name="destino_unidad" id="destino_unidad" class="form-control required">
                                            <option value="">--- Seleccionar ---</option>
                                            @foreach ($unidades as $unidad)
                                                <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text">Debe seleccionar el campo</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="destino_etapa" class="control-label">Etapa</label>
                                        <select name="destino_etapa" id="destino_etapa" class="form-control required">
                                            <option value="">--- Seleccionar ---</option>
                                            @foreach ($etapas as $etapa)
                                                <option value="{{ $etapa->id }}">{{ $etapa->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text">Debe seleccionar el campo</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="destino_responsable" class="control-label">Responsable</label>
                                        <select name="destino_responsable" id="destino_responsable"
                                            class="form-control required">
                                            <option value="">--- Seleccionar ---</option>
                                        </select>
                                        <input type="hidden" id="responsable_id" name="responsable_id"
                                            class="required_pfdm">
                                        <span class="error-text">Debe seleccionar el responsable</span>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <fieldset class="mi-fieldset" style="border: 0; margin: 0; padding: 0 15px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary pull-right"
                                        id="btnGuardarReq">Guardar</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>