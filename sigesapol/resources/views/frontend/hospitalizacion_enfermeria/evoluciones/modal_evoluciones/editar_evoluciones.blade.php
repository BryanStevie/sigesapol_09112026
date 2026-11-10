<!-- Modal -->
<style>
.is-invalid {
    border: 1px solid red;
}
</style>

<div class="modal fade" id="modalEditarEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <input type="text" hidden id="evolucion_id">
    <div class="modal-dialog modal-lg" style="width: 1400px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        >&times;</span></button>
                <b id="tituloFormularioEvoluciones-editar"></b>
            </div>

            <div class="modal-body">
                <form id="EditarFormularioEvoluciones" name="EditarFormularioEvoluciones" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_enfermeria2" name="id_enfermeria">
                    <input type="hidden" id="id_evoluciones_registro2" name="id_evoluciones">
                    {{-- <input type="hidden" id="id_atencion_evolucion_registro2" name="id_atencion_evolucion_registro"> --}}
                    <input type="hidden" id="id_tipo_atenciones_evolucion_registro2" name="id_tipo_atenciones_evolucion_registro">
                    <input type="hidden" id="id_asegurado_registro2" name="id_asegurado">
                    <input type="hidden" id="fecha_hora_ing_upt" name="fecha_hora_ing_upt">
                    <input type="hidden" id="fecha_hora_eg_upt" name="fecha_hora_eg_upt">
                    <input type="hidden" id="id_prestacion_registro2" name="id_prestacion_registro">
                    
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
                                    <table class="table" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
                                        <tr>
                                          <th>DOCUMENTO</th>
                                          <th width="50%">PACIENTE</th>
                                          <th>PARENTESCO</th>
                                          <th>SEXO</th>
                                          <th>EDAD</th>
                                          <th>T. SANG.</th>
                                          <th>H. CLINICA</th>
                                        </tr>
                                        <tr>
                                          <td>
                                            <div  class="input_general" id="num_documento_evolucion_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_paciente" id="nom_paciente_evolucion_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_m" id="parentesgo_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_p" id="sexo_evolucion_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_p" id="edad_evolucion_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_p" id="tipo_sangre_editar"></div>
                                          </td>
                                          <td>
                                            <div  class="input_hc" id="hc_evolucion_editar"></div>
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
                                    <table class="table" id="cpms_listado" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                        <tr>
                                          <th width="20%">TIPO DE ATENCIÓN</th>
                                          <th width="30%">PRIORIDAD</th>
                                          <th width="15%">FECHA</th>
                                          <th width="15%">HORA</th>
                                          <th width="20%">TURNO</th>
                                        </tr>
                                      </thead>
                                        <tr>
                                          <td width="20%">
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;" id="nombre_tipo_atencion_registro2" disabled>
                                          </td>
                                          <td width="30%">
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;" id="prioridad_registro2" disabled>
                                          </td>
                                          {{-- <td width="15%">
                                            <input type="text" class="form-control datepicker"
                                                  style="height: 28px !important;" autocomplete="off"
                                                  name="fecha_incidente_notificacion" id="fecha_incidente_notificacion"
                                                  value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" disabled>
                                          </td>
                                          <td width="15%">
                                            <input type="text" class="form-control datepicker"
                                                  style="height: 28px !important;" autocomplete="off"
                                                  name="fecha_incidente_notificacion" id="fecha_incidente_notificacion"
                                                  value="{{\Carbon\Carbon::now()->format('H:m:s')}}" disabled>
                                          </td> --}}
                                          <td width="15%">
                                            <input type="date" class="form-control"
                                                  style="height: 28px !important;"
                                                  name="fecha_registro_evolucion_2" id="fecha_registro_evolucion_2">
                                          </td>
                                          <td width="15%">
                                            <input type="time" class="form-control"
                                                  style="height: 28px !important;"
                                                  name="hora_registro_evolucion_2" id="hora_registro_evolucion_2">
                                          </td>
                                          <td width="25%">
                                            <select class="form-control" style="width: 100%; height: 28px !important;" id="turno2" name="turno2">
                                                @if ($id_establecimiento == 76) 
                                                <option value="5">GUARDIA DIURNA</option>
                                                <option value="6">GUARDIA NOCTURNA</option>
                                                @else
                                                <option value="1">MADRUGADA</option>
                                                <option value="2">MAÑANA</option>
                                                <option value="3">TARDE</option>
                                                <option value="4">NOCHE</option>
                                                @endif
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
                                                <li class="active"><a href="#tab0defaultEvoluciones-editar" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" ></i>
                                                            VALORACIÓN DE ENFERMERÍA </b></a></li>
                                                <li><a href="#tab1defaultEvoluciones-editar" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" ></i>
                                                            ATENCIÓN DE ENFERMERÍA</b></a></li>
                                                {{-- <li><a id="tabEvolucionesCPMS_editar" href="#tab2defaultEvoluciones-editar" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" ></i>
                                                                CPMS/CPT</b></a></li> --}}
                                                <li><a href="#tab3defaultEvoluciones-editar" data-toggle="tab"
                                                  style="font-size:12px" aria-expanded="true"><b><i
                                                          class="fa fa-file-text-o" ></i>
                                                          INVASIVOS</b></a></li>
                                              <li><a href="#tab4defaultEvoluciones-editar" data-toggle="tab"
                                                style="font-size:12px" aria-expanded="true"><b><i
                                                class="fa fa-file-text-o" ></i>
                                                HISTORIAL DE INVASIVOS</b></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultEvoluciones-editar">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <div class="col-md-12">
                                                            <label for="tipo">SUBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_subjetivo2"
                                                                name="descripcion_subjetivo"></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">OBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_objetivo2"
                                                                name="descripcion_objetivo"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="tab1defaultEvoluciones-editar">
                                                    <fieldset>
                                                        <div class="row"> <br>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <label for="">DIAGNÓTICO DE
                                                                            ENFERMERÍA</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_DIAGNOSTICO_ENFERMERIA_EDITAR"
                                                                            name="add_DIAGNOSTICO_ENFERMERIA_EDITAR"
                                                                            class="btn btn-success btn-sm add_DIAGNOSTICO_ENFERMERIA_EDITAR"><i
                                                                                class="fa fa-plus"
                                                                                ></i> AGREGAR
                                                                            DIAGNÓTICO
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_DIAGNOSTICO_ENFERMERIA_EDITAR"
                                                                            style="width: 100%; margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_DIAGNOSTICO_ENFERMERIA_EDITAR"
                                                                                name="cuerpo_DIAGNOSTICO_ENFERMERIA_EDITAR"
                                                                                class="cuerpo_DIAGNOSTICO_ENFERMERIA_EDITAR">
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
                                                                                id="add_PLAN_CUIDADO_ENFERMERIA_EDITAR"
                                                                                name="add_PLAN_CUIDADO_ENFERMERIA_EDITAR"
                                                                                class="btn btn-success btn-sm add_PLAN_CUIDADO_ENFERMERIA_EDITAR"><i
                                                                                    class="fa fa-plus"
                                                                                    ></i> AGREGAR NOC
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR"
                                                                                style="margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_PLAN_CUIDADO_ENFERMERIA_EDITAR"
                                                                                    name="cuerpo_PLAN_CUIDADO_ENFERMERIA_EDITAR"
                                                                                    class="cuerpo_PLAN_CUIDADO_ENFERMERIA_EDITAR">
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
                                                                            id="add_INTERVENCIONES_ENFERMERIA_EDITAR"
                                                                            name="add_INTERVENCIONES_ENFERMERIA_EDITAR"
                                                                            class="btn btn-success btn-sm add_INTERVENCIONES_ENFERMERIA_EDITAR"><i
                                                                                class="fa fa-plus"
                                                                                ></i> AGREGAR NIC
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_INTERVENCIONES_ENFERMERIA_EDITAR"
                                                                            style="width: 100%; margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_INTERVENCIONES_ENFERMERIA_EDITAR"
                                                                                name="cuerpo_INTERVENCIONES_ENFERMERIA_EDITAR"
                                                                                class="cuerpo_INTERVENCIONES_ENFERMERIA_EDITAR">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="tipo">DETALLE DE
                                                                            INTERVENCIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_intervenciones2"
                                                                            name="descripcion_intervenciones"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12"
                                                                        style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                        <label for="">EVALUACIÓN</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_EVALUACIONES_ENFERMERIA_EDITAR"
                                                                            name="add_EVALUACIONES_ENFERMERIA_EDITAR"
                                                                            class="btn btn-success btn-sm add_EVALUACIONES_ENFERMERIA_EDITAR"><i
                                                                                class="fa fa-plus"
                                                                                ></i> GENERAR
                                                                            EVALUACIÓN
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;margin-bottom: 10px !important;width: 100%">
                                                                        <table id="tbl_EVALUACIONES_ENFERMERIA_EDITAR">
                                                                            <tbody id="cuerpo_EVALUACIONES_ENFERMERIA_EDITAR"
                                                                                name="cuerpo_EVALUACIONES_ENFERMERIA_EDITAR"
                                                                                class="cuerpo_EVALUACIONES_ENFERMERIA_EDITAR">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;">
                                                                        <label for="tipo">DETALLE DE
                                                                            EVALUACIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_evaluaciones2"
                                                                            name="descripcion_evaluaciones"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab2defaultEvoluciones-editar">
                                                    <fieldset>
                                                        <div class="row">
                                                          <div class="col-md-12">
                                                            <div class="box box-primary">
                                                              <br>
                                                              <div class="box-body">
                                                                  <div class="table-responsive">
                                                                      <fieldset>
                                                                        <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
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
                                                                          <tbody id="cuerpo_diagnosticos_ENFERMERIA_EDITAR">
                                                                        
                                                                          </tbody>
                                                                        </table>
                                                                      </fieldset>  
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <a href="javascript:void(0)"
                                                                            id="add_CPMS_ENFERMERIA_EDITAR"
                                                                            name="add_CPMS_ENFERMERIA_EDITAR"
                                                                            class="btn btn-success btn-sm add_CPMS_ENFERMERIA_EDITAR">
                                                                            <i class="fa fa-plus"
                                                                                ></i> Agregar Procedimiento
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_CPMS_ENFERMERIA_EDITAR"
                                                                            style="margin-bottom: 10px !important;width: 100%;">
                                                                            <tbody id="cuerpo_CPMS_ENFERMERIA_EDITAR"
                                                                                name="cuerpo_CPMS_ENFERMERIA_EDITAR"
                                                                                class="cuerpo_CPMS_ENFERMERIA_EDITAR">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab3defaultEvoluciones-editar">
                                                  <fieldset>
                                                    <form id="FormularioPunto" name="FormularioPunto" enctype="multipart/form-data" method="GET"> 
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                          <div id="scrollableDiv">
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
                                                              <tbody id="lista_invasivos_editar">
                                                              </tbody>
                                                            </table>
                                                          </div>
                                                        </div> 
                                                        <div class="col-md-6">
                                                          <div class="form-group">  
                                                            <div class="col-md-6"> 
                                                                <label for="tipo">SELECCIONE PROCESO</label>
                                                                <select class="form-control input-sm" id="tipo_proceso_editar" name="tipo_proceso" style="width: 100%;"> 
                                                                </select>  
                                                            </div>
                                                            <div class="col-md-6"> 
                                                                <label for="tipo">TAMAÑO DE PUNTOS</label>
                                                                <select class="form-control input-sm" id="tamanio_proceso_editar" name="tamanio_proceso" style="width: 100%;"> 
                                                                </select> 
                                                            </div>   
                                                            <input type="hidden" name="tipo_registro" id="tipo_registro_editar" value="EVOLUCION">
                                                          </div>
                                                          <div class="col-md-12">
                                                              <hr>
                                                          </div> 
                                                          <br><br>
                                                          <input type="hidden" name="img_puntos_base_update" id="img_puntos_base_update"> 
                                                          <div class="col-md-10" style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                             
                                                              <div class="col-md-12" style="cursor: pointer;" id="celda2" name="celda2">
                                                                <div class="form-group">
                                                                    <div class="grid-block" style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                      <canvas id="plano_evolucion2" name="plano" width="512" height="512"></canvas>
                                                                      <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                                                      <input type="hidden" name="x" id="x">
                                                                      <input type="hidden" name="y" id="y"> 
                                                                      <div id="control_div_puntos_evolucion_editar" name="control_div_puntos_evolucion"> 
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                              </div>
                                                           
                                                          </div> 
                                                          <div class="col-md-2" >
                                                            <ul class="leyenda_grafico" id="control_div_puntos_evolucion_leyenda_editar" name="control_div_puntos_evolucion_leyenda"> 
                                                            </ul>
                                                          </div> 
                                                        </div> 
                                                        
                                                      </div>
                                                    </form>
                                                  </fieldset>
                                                </div>
                                                <div class="tab-pane fade" id="tab4defaultEvoluciones-editar">
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
                                                          <tbody id="historial_invasivos_editar">
                                                        
                                                          </tbody>
                                                        </table>
                                                        <div id="load-more-container">
                                                        </div>
                                                       </div>
                                                    </div>
                                                     
                                                  </fieldset>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <div id="imagen_captura2">
                          <img id="imagenCapturada2" style="display: none;" />
                        </div>
                        <button type="submit" class="btn btn-success" id="ActualizarEvoluciones"
                            value="create">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>