<div class="modal fade" id="ipressModal2" tabindex="-1" role="dialog" aria-labelledby="ipressModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-custom">

            <div class="modal-header modal-header-green">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-custom" id="ipressModalLabel">Seleccionar IPRESS</h4>
            </div>

            <div class="modal-body">

                <div class="filter-section">
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group" style="margin-right: 15px;">
                                    <label for="descripcion" class="sr-only">Descripción</label>
                                    <input type="text" class="form-control" id="descripcion"
                                        placeholder="Descripción">
                                </div>
                                <div class="form-group" style="margin-right: 15px;">
                                    <label for="grupoRegion" class="sr-only">Grupo / Región</label>
                                    <select class="form-control" id="grupoRegion">
                                        <option>- Seleccionar -</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group" style="margin-right: 15px;">
                                    <label for="categoria" class="sr-only">Categoría</label>
                                    <select class="form-control" id="categoria">
                                        <option>- Todos -</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-right: 15px;">
                                    <label for="estado" class="sr-only">Estado</label>
                                    <select class="form-control" id="estado">
                                        <option>- Seleccionar -</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-default" style="margin-right: 10px;">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                                </button>
                                <button type="button" class="btn btn-success" style="margin-right: 20px;">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Aceptar
                                </button>

                                <button type="button" class="btn btn-default pull-right" style="margin-left: 10px;">
                                    <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Limpiar Todo
                                </button>
                                <button type="button" class="btn btn-default pull-right">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Seleccionar todo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table table-striped table-hover">
                            <thead class="table-header-custom">
                                <tr>
                                    <th>Cod.</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Tipo Institución</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>123</td>
                                    <td>IPRESS Central</td>
                                    <td>I-4</td>
                                    <td>Público</td>
                                    <td>Activo</td>
                                    <td><button class="btn btn-default btn-xs" style="color: green;"><span
                                                class="glyphicon glyphicon-plus-sign"></span></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-wrapper">
                        <table class="table table-striped table-hover">
                            <thead class="table-header-custom">
                                <tr>
                                    <th>Cod.</th>
                                    <th>Nombre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>456</td>
                                    <td>IPRESS Lima</td>
                                    <td><button class="btn btn-default btn-xs" style="color: red;"><span
                                                class="glyphicon glyphicon-remove-circle"></span></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
