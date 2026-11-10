<!-- Modal -->
<div class="modal fade" id="modalBalanceHidrico" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
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
                    <input type="hidden" id="id_asegurado_bh" name="id_asegurado">
                    <input type="hidden" id="peso" name="peso">
                    <input type="hidden" id="ubicacion" name="ubicacion">
                    <input type="hidden" id="id_area" name="id_area">
                    <input type="hidden" id="id_enfermeria_bh_reg" name="id_enfermeria">
                    
                    
                    <div class="nav-tabs-custom" style="margin-bottom: -40px;">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center"><br>
                                        <b>INFORMACIÓN DEL PACIENTE <p id="id_area_disabled"></p></b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <tr>
                                        <th>DOCUMENTO</th>
                                        <th>PACIENTE</th>
                                        <th>PARENTESCO</th>
                                        <th>SEXO</th>
                                        <th>EDAD</th>
                                        <th>T. SANG.</th>
                                        <th>H. CLINICA</th>
                                        {{-- <th>Alergia: <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                                          title="Alergia" class="listarAlergias" id="listarAlergias"><label id=""
                                            style="font-size: 14px;cursor: pointer; color:red;">SI</label></a>
                                        </th> --}}
                                        <th>ALERGIA: <a href="javascript:void(0)" aria-hidden="true"
                                            data-toggle="tooltip" style="cursor: pointer; outline: none;"
                                            title="Alergia" class=""
                                            id=""><label id="alergia_bh_hospi_reg"
                                                style="cursor: pointer;"></label></a>
                                        </th>
                                      </tr>
                                      <tr>
                                        <td>
                                          <div  class="input_general" id="num_documento_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_paciente" id="nom_paciente_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_m" id="parentesgo_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="sexo_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="edad_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="tipo_sangre_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_hc" id="hc_bh_nuevo"></div>
                                        </td>
                                        <td>
                                        
                                        </td>
                                      </tr>
                                  </table>
                                  </div>
                                </div>
                            </fieldset>
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                            
                                  <div class="panel-body">
                                    <table class="table" id="cpms_listado" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                        
                                        <tr>
                                          <th colspan="5"><b>DATOS DE LA ATENCIÓN </b></th>
                                        </tr>
                                        <tr>
                                          <th width="20%">TIPO DE ATENCIÓN</th>
                                          <th width="30%">SERVICIO</th>
                                          <th width="20%">FECHA DE ATENCIÓN</th>
                                          <th width="15%">HORA DE ATENCIÓN</th>
                                          <th width="25%">TURNO</th>
                                        </tr>
                                      </thead>
                                      
                                        <tr>
                                          <td width="20%">
                                            <div  class="input_general" id="tipo_atencion_disabled"></div>
                                          </td>
                                          <td width="25%">
                                            <span id="servicio_info" hidden></span>
                                            <div  class="input_300" id="servicio_disabled"></div>
                                          </td>
                                          <td width="20%">
                                            <div  class="input_general" id="fecha_atencion_disabled"></div>
                                          </td>
                                          <td width="15%">
                                            <input type="time" id="hora_bh" name="hora_bh" class="input_general">
                                            {{-- <div  class="input_general" id="hora_atencion_disabled"></div> --}}
                                          </td>
                                          <td width="30%">
                                            <input id="turno_bh" name="turno_bh" hidden>
                                            <select class="form-control" style="width: 200px;" id="turnos_ver"disabled>
                                            </select>
                                          </td>
                                        </tr>
                                    </table>
                                 </div>
                              </div>
                            </fieldset>
                                <fieldset style="margin-bottom: 0px !important;">
                                    <div class="form-group" style="margin-bottom: 0px !important">
                                      <div class="row">
                                        <div class="col-md-6"> 
                                          <table class="table table-striped table-list-search" style="margin-bottom: 0px !important;">
                                            <td style="display: flex; align-items: center; gap: 2px;">
                                              <span style="vertical-align: middle; text-align: center">PESO (TRIAJE): </span>
                                              <input id="peso_kg" name="peso_kg" style="font-weight: bold; width: 55px; height: 30px; font-size: 11px;" class="form-control">
                                              <span style="vertical-align: middle; text-align: center">KG</span>
                                              <div class="fiebre-bh">
                                                <label style="user-select: none;" for="fiebre" title="Seleccionar en caso de que el paciente tenga fiebre.">FIEBRE</label>
                                                <input type="checkbox" name="fiebre" id="fiebre">
                                              </div>
                                            </td>
                                          </table>
                                        </div>
                                        <div class="col-md-6"></div>
                                      </div>
                                      <div class="row" id="balance_hidrico">
                                          
                                      </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarBalanceHidricoNuevo"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>