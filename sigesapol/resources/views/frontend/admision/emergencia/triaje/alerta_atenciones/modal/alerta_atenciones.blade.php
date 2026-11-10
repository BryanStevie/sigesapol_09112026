 <!-- Modal -->
<div class="modal fade" id="modalAlertaAtenciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 1200px !important;">
        <div class="modal-content">

            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b style="font-size: 15px !important;">HISTORIAL DE ATENCIONES DE : 
                    <b name="nombre_de_paciente" id="nombre_de_paciente" style="font-size: 20px !important;"></b></b>
            </div>

            <div class="modal-body">
                <form id="FormularioAlertaAtenciones" name="FormularioAlertaAtenciones" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">

                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 

                                    <div class="col-xs-12 "> 
                                        <div class="table-responsive"
                                            style="position: relative; height: 500px; overflow: auto;display: block;">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ESTABLECIMIENTO</th> 
                                                        <th>PRIORIDAD </th>
                                                        <th>SERVICIO/AREA </th>
                                                        <th>FEC.INGRESO </th>  
                                                        <th>FEC.EGRESO </th>  
                                                        <th>TIEMPO DE ESTANCIA</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listaAlertaAtenciones" class="listaAlertaAtenciones" style="">
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
            <div class="modal-footer" style="padding: 10px 0px 0px 10px !important;">
                <p style="text-align: left;font-size: 10px;"><b id="cantidad_de_registros" name="cantidad_de_registros" style="font-size: 10px !important; "></b><b> REGISTRO(S) ENCONTRADOS</b> 
                    </p>
            </div>
        </div>
    </div>
</div>