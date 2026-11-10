<!-- Modal -->
<div class="modal fade" id="modalBalanceHidrico" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioBalanceHidrico"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioBalanceHidrico" name="FormularioBalanceHidrico" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id" name="id">
                    <div class="nav-tabs-custom" style="margin-bottom: -40px;">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')  
                                    <div class="form-group">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">TURNO</label>
                                            <select class="form-control select2" style="width: 100%">
                                                <option value="M">MAÑANA</option>
                                                <option value="T">TARDE</option>
                                                <option value="N1">NOCHE 1</option>
                                                <option value="N2">NOCHE 2</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipo">FECHA</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;"
                                                name="fecha_registro_hoja_grafica_inicial"
                                                id="fecha_registro_hoja_grafica_inicial" autocomplete="off"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>
                                <fieldset style="margin-bottom: 0px !important;">
                                    <div class="form-group" style="margin-bottom: 0px !important">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>INGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>HIDRATACIÓN</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_ENDOVENOSO" name="add_ENDOVENOSO" 
                                                                        class="btn btn-success btn-sm add_ENDOVENOSO"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> ENDOVENOSO
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_ENDOVENOSO" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_ENDOVENOSO" name="cuerpo_ENDOVENOSO" class="cuerpo_ENDOVENOSO">
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody> 
                                                            <tr>
                                                                <td><b>NUTRICIÓN PARENTERAL</b></td> 
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;"> 
                                                                    <select class="form-control select2"
                                                                        style="width: 100%">
                                                                        <option value="2:1"><b>2:1</b></option>
                                                                        <option value="3:1"><b>3:1</b></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control" style="height: 28px !important;" required="">
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td><b>DIETA ORAL</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>DIETA ENTERAL</b></td>
                                                                <td></td> 
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;"> 
                                                                    <select class="form-control input-sm" id="item_dieta_enteral" name="item_dieta_enteral" style="width: 100%;"> 
                                                                    </select>  
                                                                </td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control" style="height: 28px !important;" required="">
                                                                </td> 
                                                            </tr> 
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_INGRESOS_OTROS" name="add_INGRESOS_OTROS" 
                                                                        class="btn btn-success btn-sm add_INGRESOS_OTROS"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> OTROS
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_INGRESOS_OTROS" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_INGRESOS_OTROS" name="cuerpo_INGRESOS_OTROS" class="cuerpo_INGRESOS_OTROS">
                                                        </tbody>
                                                    </table> 
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 70%;"><b>TOTAL DE INGRESOS </b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required
                                                                        disabled>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>EGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                                                <th style="text-align: center;">VALOR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><b>DIURESIS</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>DEPOSICIÓN</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>RESIDUO GÁSTRICO</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>VÓMITO</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SECRECIÓN BRONQUIAL</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SECRECIÓN BOCA</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>TEMPERATURA</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>DIAFORESIS</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HEMODIALISIS</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr> 
                                                            <tr>
                                                                <td><b>HIDRATACIÓN</b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_DRENAJES" name="add_DRENAJES" 
                                                                        class="btn btn-success btn-sm add_DRENAJES"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> DRENAJES
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_DRENAJES" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_DRENAJES" name="cuerpo_DRENAJES" class="cuerpo_DRENAJES">
                                                        </tbody>
                                                    </table> 
                                                    <table id="example" class="table table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td> 
                                                                    <a href="javascript:void(0)" id="add_EGRESOS_OTROS" name="add_EGRESOS_OTROS" 
                                                                        class="btn btn-success btn-sm add_EGRESOS_OTROS"> 
                                                                        <i class="fa fa-plus" aria-hidden="true"></i> OTROS
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                    <table id="tbl_EGRESOS_OTROS" style="margin-bottom: 10px !important; margin-left: 8px;"> 
                                                        <tbody id="cuerpo_EGRESOS_OTROS" name="cuerpo_EGRESOS_OTROS" class="cuerpo_EGRESOS_OTROS">
                                                        </tbody>
                                                    </table>
                                                    <table id="example" class="table table-striped table-list-search"
                                                        style="margin-bottom: 0px !important;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 70%;"><b>TOTAL DE EGRESOS </b></td>
                                                                <td>
                                                                    <input value="" type="text" class="form-control"
                                                                        style="height: 28px !important;" required
                                                                        disabled>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarBalanceHidrico"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>