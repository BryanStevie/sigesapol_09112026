<!-- Modal -->
<div class="modal fade" id="modalListarHojaBalanceHidrico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarHojaBalanceHidrico"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarHojaBalanceHidrico" name="FormularioListarHojaBalanceHidrico"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_balance">
                    <input type="hidden" id="argumentos"/>
                    <input type="hidden" id="doc_firmar_bh" />
                    <input type="hidden" id="opt_firma_bh" />
                    <input type="hidden" name="flag_firma" id="flag_firma">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">

                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center"><br>
                                            <b>INFORMACIÓN DEL PACIENTE</b>
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
                                                  style="font-size: 14px;cursor: pointer;">SI</label></a>
                                              </th> --}}
                                              <th>ALERGIA: <a href="javascript:void(0)" aria-hidden="true"
                                                data-toggle="tooltip" style="cursor: pointer; outline: none;"
                                                title="Alergia" class=""
                                                id=""><label id="alergia_bh_hospi_det"
                                                    style="cursor: pointer;"></label></a>
                                              </th>
                                            </tr>
                                            <tr>
                                              <td>
                                                <div  class="input_general" id="num_documento_bh_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_paciente" id="nom_paciente_bh_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_m" id="parentesgo_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_p" id="sexo_bh_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_p" id="edad_bh_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_p" id="tipo_sangre_ver"></div>
                                              </td>
                                              <td>
                                                <div  class="input_hc" id="hc_bh_ver"></div>
                                              </td>
                                              <td>
                                               
                                              </td>
                                            </tr>
                                        </table>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-2">
                                            <table  class="table table-striped table-list-search">
                                              <tr>
                                                  <td style="text-align: center;">PESO (REGISTRADO): </td>
                                                  <td style="text-align: center;" ><b><span id="peso_hoja_bh"></span></b></td>
                                              </tr>
                                          </table>
                                          </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>INGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;" width="40%">DESCRIPCIÓN</th>
                                                                <th style="text-align: center;" width="5%">M</th>
                                                                <th style="text-align: center;" width="5%">T</th>
                                                                <th style="text-align: center;" width="5%">N</th>
                                                                <th style="text-align: center;" width="5%">MD</th>
                                                                <th style="text-align: center;"width="5%"> TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="lista_ingresos">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <b>EGRESOS</b>
                                                    </div>
                                                    <table id="example" class="table table-striped table-list-search">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;" width="40%">DESCRIPCIÓN</th>
                                                                <th style="text-align: center;" width="5%">M</th>
                                                                <th style="text-align: center;">T</th>
                                                                <th style="text-align: center;">N</th>
                                                                <th style="text-align: center;">MD</th>
                                                                <th style="text-align: center;">TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="lista_egresos">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </fieldset>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <table width="100%">
                  <tr>
                    <td>
                      @if (Auth::user()->status_token==1)
                        <a class="btn btn-info" id="btnFinalizarBalance">
                          <i class="fa-solid fa-file-signature"></i> FINALIZAR BALANCE
                        </a>
                      @else 
                          <a class="btn btn-info" id="btnFirmaBalance">
                            <i class="fa-solid fa-file-signature"></i> FIRMAR BALANCE
                          </a>
                      @endif
                      <div id="icon-doc-firmado" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                        <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i><span style="font-size: 1rem; font-weight: 600;">Documento Firmado</span>
                      </div>
                    </td>
                    <td>
                      <div><button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button></div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
    </form>
</div>