<div class="modal fade" id="modalRegistrarAseguradoEstrategia" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    style="padding-left: 0px; !important">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formRegistrarAseguradoEstrategia">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h4 class="modal-title" id="modalLabelES"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tipo_registro" id="tipo_registro" value="">

                    <!-- Campos para Asegurado -->
                    <div id="campos_asegurado" class="hidden">
                        <div class="form-group">
                            <label class="" for="nombre_asegurado">Nombres</label>
                            <div class="">
                                <input type="text" id="nombre_asegurado_es" name="nombre_asegurado_es"
                                    class="form-control" placeholder="Nombre completo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="" for="paterno">Apellido Paterno</label>
                            <div class="">
                                <input type="text" id="paterno_es" name="paterno_es" class="form-control"
                                    placeholder="Apellido paterno">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="" for="materno">Apellido Materno</label>
                            <div class="">
                                <input type="text" id="materno_es" name="materno_es" class="form-control"
                                    placeholder="Apellido materno">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="" for="materno">DNI</label>
                            <div class="">
                                <input type="text" id="dni_es" name="dni_es" class="form-control"
                                    placeholder="DNI">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="" for="materno"></label>
                            <div class="">
                                <select type="text" id="sexo_es" name="sexo_es" class="form-control"
                                    placeholder="Seleccione el sexo">
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENINO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="" for="materno">Fecha Nac.</label>
                            <div class="">
                                <input type="date" id="nacimiento_es" name="nacimiento_es" class="form-control"
                                    placeholder="Ingrese Fecha Nacimiento">
                            </div>
                        </div>

                    </div>

                    <!-- Campos para Historia -->
                    <div id="campos_historia" class="hidden">
                        <div class="form-group">
                            <label class="" for="nro_historia">Número de Historia</label>
                            <div class="">
                                <input type="text" id="nro_historia" name="nro_historia" class="form-control"
                                    placeholder="Número de historia">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarNroHistoriaAsegurado">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-default" onclick="cerrarModalHijo()">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function cerrarModalHijo() {
            $('#modalRegistrarAseguradoEstrategia').modal('hide');
        }
    </script>
</div>
