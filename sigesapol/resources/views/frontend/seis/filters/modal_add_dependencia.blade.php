<div class="modal fade" id="seleccionarDependenciaModal" tabindex="-1" role="dialog"
    aria-labelledby="seleccionarDependenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" style="background-color: #6AA84F; border-color: #4F783B; color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="seleccionarDependenciaLabel">Seleccionar Dependencia</h4>
            </div>

            <div class="modal-body">

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="inputCodigo" class="control-label col-xs-4"
                                    style="text-align: left;">Código</label>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" id="inputCodigo">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="inputDescripcion" class="control-label col-xs-4"
                                    style="text-align: left;">Descripcion</label>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" id="inputDescripcion">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row" style="margin-top: 15px; margin-bottom: 20px;">
                    <div class="col-xs-12 text-right">
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive" style="border: 1px solid #ccc;">
                            <table class="table table-condensed" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th style="width: 20%; border-bottom: 1px solid #000;">Cod.</th>
                                        <th style="border-bottom: 1px solid #000;">Descripcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="height: 50px;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
