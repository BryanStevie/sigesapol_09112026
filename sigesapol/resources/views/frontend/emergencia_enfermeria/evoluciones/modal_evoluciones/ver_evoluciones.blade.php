<!-- Modal -->
<div class="modal fade" id="modalVerEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1350px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                <b id="tituloFormularioEvoluciones-ver"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioEvolucionesVer" name="FormularioEvolucionesVer" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_evolucion">
                    <input type="hidden" id="argumentos_evolucion" />
                    <input type="hidden" id="doc_firmar_evolucion" />
                    <input type="hidden" name="flag_firma" id="flag_firma_evolucion">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">

                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                        <div class="panel-title text-center"><br>
                                            <b>INFORMACIÓN DEL PACIENTE</b>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table" id="tblDiagnosticos" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <tr>
                                                <th>DOCUMENTO</th>
                                                <th width="50%">PACIENTE</th>
                                                <th>PARENTESCO</th>
                                                <th>SEXO</th>
                                                <th>EDAD</th>
                                                <th>T. SANG.</th>
                                                <th>H. CLINICA</th>
                                                <th>Cel. ACOMP.</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="input_general" id="num_documento_evolucion_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_paciente" id="nom_paciente_evolucion_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_m" id="parentesgo_ver_1"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="sexo_evolucion_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_p" id="edad_evolucion_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="tipo_sangre_ver_1"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="hc_evolucion_ver"></div>
                                                </td>
                                                <td>
                                                    <div class="input_hc" id="cel_acomp_evolucion_ver"></div>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                        <div class="panel-title text-center"><br>
                                            <b>DATOS DE LA ATENCIÓN</b>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table" id="cpms_listado_ver" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                                <tr>
                                                    <th width="20%">TIPO DE ATENCIÓN</th>
                                                    <th width="20%">FECHA DE ATENCIÓN</th>
                                                    <th width="15%">HORA DE ATENCIÓN</th>
                                                    <th width="30%">PRIORIDAD</th>
                                                    <th width="25%">TURNO</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td width="20%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;"
                                                        id="nombre_tipo_atencion_registro_ver" disabled>
                                                </td>
                                                <td width="20%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" autocomplete="off"
                                                        name="fecha_incidente_notificacion"
                                                        id="fecha_incidente_notificacion"
                                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" disabled>
                                                </td>
                                                <td width="15%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" autocomplete="off"
                                                        name="fecha_incidente_notificacion"
                                                        id="fecha_incidente_notificacion"
                                                        value="{{ \Carbon\Carbon::now()->format('H:m:s') }}" disabled>
                                                </td>
                                                <td width="30%">
                                                    <input type="text" class="form-control datepicker"
                                                        style="height: 28px !important;" id="prioridad_registro_ver"
                                                        disabled>
                                                </td>
                                                <td width="25%">
                                                    <select class="form-control select2" disabled style="width: 100%"
                                                        id="turno_ver">
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="panel with-nav-tabs panel-default"
                                        style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                        <div class="panel-heading">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab0defaultEvoluciones-ver"
                                                        data-toggle="tab" style="font-size:12px"
                                                        aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o"></i>SOAPIE
                                                        </b></a></li>
                                                <!--li><a href="#tab1defaultEvoluciones-ver" data-toggle="tab"
                                                  style="font-size:12px" aria-expanded="true"><b><i
                                                          class="fa fa-file-text-o" ></i>
                                                      ATENCIÓN DE ENFERMERÍA</b></a></li-->
                                                <li><a href="#tab2defaultEvoluciones-ver" id="tabEvolucionesCPMS_ver"
                                                        data-toggle="tab" style="font-size:12px"
                                                        aria-expanded="true"><b><i class="fa fa-file-text-o"></i>
                                                            CPMS/CPT</b></a></li>
                                                <li><a href="#tab3defaultEvoluciones-ver" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o"></i>
                                                            INVASIVOS</b></a></li>
                                                <!--li><a href="#tab4defaultEvoluciones-ver" data-toggle="tab"
                                            style="font-size:12px" aria-expanded="true"><b><i
                                            class="fa fa-file-text-o" ></i>
                                            HISTORIAL DE INVASIVOS</b></a>
                                            </li-->
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultEvoluciones-ver">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <div class="col-md-12">
                                                            <label for="tipo">S</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                id="descripcion_subjetivo_ver" name="descripcion_subjetivo" disabled></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">O</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                id="descripcion_objetivo_ver" name="descripcion_objetivo" disabled></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">A</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                id="descripcion_analisis_ver" name="descripcion_analisis" disabled></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">P</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase" id="descripcion_plan_ver"
                                                                name="descripcion_plan" disabled></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">I</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                id="descripcion_intervenciones_ver" name="descripcion_intervenciones" disabled></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">E</label>
                                                            <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                id="descripcion_evaluaciones_ver" name="descripcion_evaluaciones" disabled></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="tab-pane" id="tab1defaultEvoluciones-ver">
                                              <fieldset>
                                                  <div class="row"> <br>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <div class="col-md-12" style="margin-bottom: 10px;">
                                                                  <label for="">DIAGNÓTICO DE
                                                                      ENFERMERÍA</label><br>
                                                                  <a href="javascript:void(0)"
                                                                      id="add_DIAGNOSTICO_ENFERMERIA_VER"
                                                                      name="add_DIAGNOSTICO_ENFERMERIA_VER"
                                                                      class="btn btn-success btn-sm disabled"><i
                                                                          class="fa fa-plus"
                                                                           ></i> AGREGAR
                                                                      DIAGNÓTICO
                                                                  </a>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <table id="tbl_DIAGNOSTICO_ENFERMERIA_VER"
                                                                      style="margin-bottom: 10px !important;">
                                                                      <tbody id="cuerpo_DIAGNOSTICO_ENFERMERIA_VER"
                                                                          name="cuerpo_DIAGNOSTICO_ENFERMERIA_VER">
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <div class="row">
                                                                  <div class="col-md-12"
                                                                      style="margin-bottom: 10px;">
                                                                      <label for="">PLANEACIÓN</label><br>
                                                                      <a href="javascript:void(0)"
                                                                          id="add_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                          name="add_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                          class="btn btn-success btn-sm disabled"><i
                                                                              class="fa fa-plus"
                                                                               disabled></i> AGREGAR NOC
                                                                      </a>
                                                                  </div>
                                                                  <div class="col-md-12">
                                                                      <table id="tbl_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                          style="margin-bottom: 10px !important;">
                                                                          <tbody
                                                                              id="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                              name="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                              class="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER">
                                                                          </tbody>
                                                                      </table>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <div class="col-md-12" style="margin-bottom: 10px;">
                                                                  <label for="">EJECUCIÓN</label><br>
                                                                  <a href="javascript:void(0)"
                                                                      id="add_INTERVENCIONES_ENFERMERIA_VER"
                                                                      name="add_INTERVENCIONES_ENFERMERIA_VER"
                                                                      class="btn btn-success btn-sm disabled"><i
                                                                          class="fa fa-plus"
                                                                           disabled></i> AGREGAR NIC
                                                                  </a>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <table id="tbl_INTERVENCIONES_ENFERMERIA_VER"
                                                                      style="margin-bottom: 10px !important;">
                                                                      <tbody id="cuerpo_INTERVENCIONES_ENFERMERIA_VER"
                                                                          name="cuerpo_INTERVENCIONES_ENFERMERIA_VER"
                                                                          class="cuerpo_INTERVENCIONES_ENFERMERIA_VER">
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                              <div class="col-md-12">
                                                                  <label for="tipo">DETALLE DE
                                                                      INTERVENCIONES</label>
                                                                  <textarea class="form-control" rows="3" required style="text-transform:uppercase"
                                                                      id="descripcion_intervenciones_ver3" name="descripcion_intervenciones3" disabled></textarea>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">
                                                              <div class="col-md-12"
                                                                  style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                  <label for="">EVALUACIÓN</label><br>
                                                                  <a href="javascript:void(0)"
                                                                      id="add_EVALUACIONES_ENFERMERIA_VER"
                                                                      name="add_EVALUACIONES_ENFERMERIA_VER"
                                                                      class="btn btn-success btn-sm disabled"><i
                                                                          class="fa fa-plus"
                                                                          ></i> GENERAR
                                                                      EVALUACIÓN
                                                                  </a>
                                                              </div>
                                                              <div class="col-md-12"
                                                                  style="padding-left: 0px !important;margin-bottom: 10px !important;width: 100%">
                                                                  <table id="tbl_EVALUACIONES_ENFERMERIA_VER">
                                                                      <tbody id="cuerpo_EVALUACIONES_ENFERMERIA_VER"
                                                                          name="cuerpo_EVALUACIONES_ENFERMERIA_VER"
                                                                          class="cuerpo_EVALUACIONES_ENFERMERIA_VER">
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                              <div class="col-md-12"
                                                                  style="padding-left: 0px !important;">
                                                                  <label for="tipo">DETALLE DE
                                                                      EVALUACIONES</label>
                                                                  <textarea class="form-control" rows="3" required style="text-transform:uppercase"
                                                                      id="descripcion_evaluaciones_ver3" name="descripcion_evaluaciones3" disabled></textarea>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </fieldset>
                                          </div> --}}

                                                <div class="tab-pane fade" id="tab2defaultEvoluciones-ver">
                                                    <fieldset>
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-heading">
                                                                <div class="panel-title text-center"><br>
                                                                    <b>DIAGNOSTICO DE LA ATENCION</b>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <table class="table table-bordered"
                                                                    id="tblDiagnosticos" cellspacing="0"
                                                                    width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="15%">Codigo</th>
                                                                            <th width="50%">Nombre</th>
                                                                            <th width="15%">Tipo</th>
                                                                            <th width="15%">Clasificación</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tfoot>
                                                                    </tfoot>
                                                                    <tbody id="diagnosticos_ver">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-12"
                                                                        style="margin-bottom: 10px;">
                                                                        <a href="javascript:void(0)"
                                                                            id="add_CPMS_ENFERMERIA_VER"
                                                                            name="add_CPMS_ENFERMERIA_VER"
                                                                            class="btn btn-success btn-sm disabled">
                                                                            <i class="fa fa-plus"></i> Agregar
                                                                            Procedimiento
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_CPMS_ENFERMERIA_VER"
                                                                            style="margin-bottom: 10px !important;width: 100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="40%">Procedimiento
                                                                                    </th>
                                                                                    <th width="10%">Cantidad</th>
                                                                                    <th width="10%" id="tiempo_cpms_det">Tiempo (min)
                                                                                    </th>
                                                                                    <th width="40%">Diagnosticos
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                name="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                class="cuerpo_CPMS_ENFERMERIA_VER">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab3defaultEvoluciones-ver">
                                                    <fieldset>
                                                        <form id="FormularioPunto" name="FormularioPunto"
                                                            enctype="multipart/form-data" method="GET">
                                                            <div class="row">
                                                                {{-- <div class="col-md-6">
                                                    <table  class="table table-hover table-sm">
                                                      <thead>
                                                        <tr>
                                                          <th width="5%">#</th>
                                                          <th width="10%">Tipo</th>
                                                          <th width="15%">Fecha</th>
                                                          <th width="25%">Lugar</th>
                                                          <th width="25%">Acción</th>
                                                        </tr>
                                                      </thead>
                                                      <tfoot>
                                                      </tfoot>
                                                      <tbody id="lista_invasivos_ver">
                                                      </tbody>
                                                    </table>
                                                </div>  --}}
                                                                <div class="col-md-6">
                                                                    {{-- <div class="form-group">  
                                                    <div class="col-md-6"> 
                                                        <label for="tipo">SELECCIONE PROCESO</label>
                                                        <select class="form-control input-sm"  style="width: 100%;" disabled> 
                                                        </select>  
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <label for="tipo">TAMAÑO DE PUNTOS</label>
                                                        <select class="form-control input-sm" style="width: 100%;" disabled> 
                                                        </select> 
                                                    </div>   
                                                   
                                                  </div> --}}
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                    </div>
                                                                    <input type="hidden" name="img_puntos_base"
                                                                        id="img_puntos_base">
                                                                    <div class="col-md-10"
                                                                        style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                                        <div class="col-md-12"
                                                                            style="cursor: pointer;" id="celda1"
                                                                            name="celda1">
                                                                            <div class="form-group">
                                                                                <div class="grid-block"
                                                                                    style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                                    <canvas id="plano_evolucion"
                                                                                        name="plano" width="512"
                                                                                        height="512"></canvas>
                                                                                    <input type="hidden"
                                                                                        id="_token" name="_token"
                                                                                        value="{{ csrf_token() }}">
                                                                                    <input type="hidden"
                                                                                        name="x" id="x">
                                                                                    <input type="hidden"
                                                                                        name="y" id="y">
                                                                                    <div id="control_div_puntos_evolucion_ver"
                                                                                        name="control_div_puntos_evolucion_ver">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <ul class="leyenda_grafico"
                                                                            id="control_div_puntos_evolucion_leyenda_ver"
                                                                            name="control_div_puntos_evolucion_leyenda_ver">
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </fieldset>
                                                </div>

                                                <!--div class="tab-pane fade" id="tab4defaultEvoluciones-ver">
                                            <fieldset>
                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="panel-heading">
                                                  <div class="panel-title text-center"><br>
                                                      <b>HISTORIAL DE INVASIVOS</b>
                                                  </div>
                                                </div>
                                                <div class="panel-body">
                                                  <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                      <tr>
                                                        <th width="15%">N°</th>
                                                        <th width="15%">Fecha</th>
                                                        <th width="50%">Tipo</th>
                                                        <th width="15%">Lugar</th>
                                                        <th width="15%">Acción</th>
                                                      </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody id="historial_invasivos_ver">
                                                  
                                                    </tbody>
                                                  </table>
                                                  <div id="load-more-container-ver">
                                                  </div>
                                                 </div>
                                              </div>
                                               
                                            </fieldset>
                                          </div-->


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <table width="100%">
                            <tr>
                                <td>
                                    @if (Auth::user()->status_token == 1)
                                        <a class="btn btn-info" id="btnFinalizarEvolucion">
                                            <i class="fa-solid fa-file-signature"></i> FINALIZAR EVOLUCIONES
                                        </a>
                                    @else
                                        <a class="btn btn-info" id="btnFirmaEvolucion">
                                            <i class="fa-solid fa-file-signature"></i> FIRMAR EVOLUCIONES
                                        </a>
                                    @endif
                                    <div id="icon-doc-firmado-evolucion"
                                        style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
                                        <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i><span
                                            style="font-size: 1rem; font-weight: 600;">Documento Firmado</span>
                                    </div>
                                </td>
                                <td>
                                    <div><button type="button" class="btn btn-danger pull-left"
                                            data-dismiss="modal">Cancelar</button></div>
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
