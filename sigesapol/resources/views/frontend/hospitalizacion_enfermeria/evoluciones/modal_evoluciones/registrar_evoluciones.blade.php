<!-- Modal -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script> --}}
<div class="modal fade" id="modalRegistrarEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1400px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        >&times;</span></button>
                <b id="tituloFormularioEvoluciones"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioEvoluciones" name="FormularioEvoluciones" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_enfermeria_evolucion" name="id_enfermeria">
                    <input type="hidden" id="id_prestacion_listado" name="id_prestacion_listado">
                    <input type="hidden" id="id_tipo_atenciones_listado" name="id_tipo_atenciones_listado">
                    <input type="hidden" id="id_tipo_atenciones_registro" name="id_tipo_atenciones_registro">
                    <input type="hidden" id="nombre_tipo_atencion" name="nombre_tipo_atencion">
                    <input type="hidden" id="fecha_hora_ing_reg" name="fecha_hora_ing_reg">
                    <input type="hidden" id="fecha_hora_eg_reg" name="fecha_hora_eg_reg">
                    <input type="hidden" id="prioridad" name="prioridad">
                    <input type="hidden" id="id_asegurado2" name="id_asegurado2">
                    <input type="hidden" id="id_prestacion_registro" name="id_prestacion_registro">
                    {{-- <input type="hidden" id="id_asegurado2" name="id_asegurado2">
                    <input type="hidden" id="id_atencion_evolucion_registro" name="id_atencion_evolucion_registro">
                    <input type="hidden" id="id_nota_enfermeria_evolucion_registro" name="id_nota_enfermeria_evolucion_registro">--}}
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
                                  <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <tr>
                                        <th>DOCUMENTO</th>
                                        <th>PACIENTE</th>
                                        <th>PARENTESCO</th>
                                        <th>SEXO</th>
                                        <th>EDAD</th>
                                        <th>T. SANG.</th>
                                        <th>H. CLINICA</th>
                                      </tr>
                                      <tr>
                                        <td>
                                          <div  class="input_general" id="num_documento_evolucion_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_paciente" id="nom_paciente_evolucion_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_m" id="parentesgo_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="sexo_evolucion_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="edad_evolucion_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p_2" id="tipo_sangre_n"></div>
                                        </td>
                                        <td>
                                          <div  class="input_hc" id="hc_evolucion_n"></div>
                                        </td>
                                        <td>
                                         
                                        </td>
                                      </tr>
                                  </table>
                                 </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm">
                                
                                <div class="panel-body">
                                  <table class="table" id="cpms_listado" cellspacing="0" width="100%" style="margin-top:5px;">
                                    <thead>
                                      
                                      <tr>
                                        <th colspan="5"><b>DATOS DE LA ATENCIÓN</b></th>
                                      </tr>
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
                                          <input type="text" class="form-control datepicker" style="height: 28px !important;" id="nombre_tipo_atencion_registro_n" disabled>
                                        </td>
                                        <td width="30%">
                                          <input type="text" class="form-control datepicker" style="height: 28px !important;" id="prioridad_registro_n" disabled>
                                        </td>
                                        <td width="15%">
                                          <input type="date" class="form-control"
                                                style="height: 28px !important;" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"
                                                name="fecha_registro_evolucion" id="fecha_registro_evolucion">
                                        </td>
                                        <td width="15%">
                                          <input type="time" class="form-control"
                                                style="height: 28px !important;"
                                                name="hora_registro_evolucion" id="hora_registro_evolucion"
                                                value="{{\Carbon\Carbon::now()->format('H:i')}}">
                                        </td>
                                        <td width="20%">
                                          <select class="form-control select2" style="width: 100%" id="turnos_nuevo" name="turno">
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
                                                <li class="active"><a href="#tab0defaultEvoluciones" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" ></i>
                                                            VALORACIÓN DE ENFERMERÍA </b></a></li>
                                                <li><a href="#tab1defaultEvoluciones" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" ></i>
                                                            ATENCIÓN DE ENFERMERÍA</b></a></li>
                                                <li><a href="#tab3defaultEvoluciones" data-toggle="tab"
                                                style="font-size:12px" aria-expanded="true"><b><i
                                                class="fa fa-file-text-o" ></i>
                                                INVASIVOS</b></a>
                                                </li>
                                                <li><a href="#tab4defaultEvoluciones" data-toggle="tab"
                                                  style="font-size:12px" aria-expanded="true"><b><i
                                                  class="fa fa-file-text-o" ></i>
                                                  HISTORIAL DE INVASIVOS</b></a>
                                                  </li>
                                                
                                            </ul>
                                        </div>

                                        <div class="panel-body">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultEvoluciones">
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <div class="col-md-12">
                                                            <label for="tipo">SUBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_subjetivo"
                                                                name="descripcion_subjetivo"></textarea>
                                                        </div>

                                                        <div class="col-md-12"> <br>
                                                            <label for="tipo">OBJETIVO</label>
                                                            <textarea class="form-control" rows="6" required
                                                                style="text-transform:uppercase"
                                                                id="descripcion_objetivo"
                                                                name="descripcion_objetivo"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="tab1defaultEvoluciones">
                                                    <fieldset>
                                                        <div class="row"> <br>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <label for="">DIAGNÓTICO DE
                                                                            ENFERMERÍA</label><br>
                                                                        <a href="javascript:void(0)"
                                                                            id="add_DIAGNOSTICO_ENFERMERIA"
                                                                            name="add_DIAGNOSTICO_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_DIAGNOSTICO_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                ></i> AGREGAR
                                                                            DIAGNÓTICO
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_DIAGNOSTICO_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_DIAGNOSTICO_ENFERMERIA"
                                                                                name="cuerpo_DIAGNOSTICO_ENFERMERIA"
                                                                                class="cuerpo_DIAGNOSTICO_ENFERMERIA">
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
                                                                                id="add_PLAN_CUIDADO_ENFERMERIA"
                                                                                name="add_PLAN_CUIDADO_ENFERMERIA"
                                                                                class="btn btn-success btn-sm add_PLAN_CUIDADO_ENFERMERIA"><i
                                                                                    class="fa fa-plus"
                                                                                    ></i> AGREGAR NOC
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_PLAN_CUIDADO_ENFERMERIA"
                                                                                style="margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_PLAN_CUIDADO_ENFERMERIA"
                                                                                    name="cuerpo_PLAN_CUIDADO_ENFERMERIA"
                                                                                    class="cuerpo_PLAN_CUIDADO_ENFERMERIA">
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
                                                                            id="add_INTERVENCIONES_ENFERMERIA"
                                                                            name="add_INTERVENCIONES_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_INTERVENCIONES_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                ></i> AGREGAR NIC
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <table id="tbl_INTERVENCIONES_ENFERMERIA"
                                                                            style="margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_INTERVENCIONES_ENFERMERIA"
                                                                                name="cuerpo_INTERVENCIONES_ENFERMERIA"
                                                                                class="cuerpo_INTERVENCIONES_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="tipo">DETALLE DE
                                                                            INTERVENCIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_intervenciones"
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
                                                                            id="add_EVALUACIONES_ENFERMERIA"
                                                                            name="add_EVALUACIONES_ENFERMERIA"
                                                                            class="btn btn-success btn-sm add_EVALUACIONES_ENFERMERIA"><i
                                                                                class="fa fa-plus"
                                                                                ></i> GENERAR
                                                                            EVALUACIÓN
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;margin-bottom: 10px !important;width: 100%">
                                                                        <table id="tbl_EVALUACIONES_ENFERMERIA">
                                                                            <tbody id="cuerpo_EVALUACIONES_ENFERMERIA"
                                                                                name="cuerpo_EVALUACIONES_ENFERMERIA"
                                                                                class="cuerpo_EVALUACIONES_ENFERMERIA">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12"
                                                                        style="padding-left: 0px !important;">
                                                                        <label for="tipo">DETALLE DE
                                                                            EVALUACIONES</label>
                                                                        <textarea class="form-control" rows="3" required
                                                                            style="text-transform:uppercase"
                                                                            id="descripcion_evaluaciones"
                                                                            name="descripcion_evaluaciones"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab3defaultEvoluciones">
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
                                                                <tbody id="lista_invasivos">
                                                                </tbody>
                                                              </table>
                                                            </div>
                                                          </div> 
                                                          <div class="col-md-6">
                                                            <div class="form-group">  
                                                              <div class="col-md-6"> 
                                                                  <label for="tipo">SELECCIONE PROCESO</label>
                                                                  <select class="form-control input-sm" id="tipo_proceso_nuevo" name="tipo_proceso" style="width: 100%;"> 
                                                                  </select>  
                                                              </div>
                                                              <div class="col-md-6"> 
                                                                  <label for="tipo">TAMAÑO DE PUNTOS</label>
                                                                  <select class="form-control input-sm" id="tamanio_proceso_nuevo" name="tamanio_proceso" style="width: 100%;"> 
                                                                  </select> 
                                                              </div>   
                                                              
                                                              <input type="hidden" name="tipo_registro" id="tipo_registro_nuevo" value="EVOLUCION">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <hr>
                                                            </div> 
                                                            <br><br>
                                                            <input type="hidden" name="img_puntos_base" id="img_puntos_base"> 
                                                            <div class="col-md-10" style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                              <div class="col-md-12" style="cursor: pointer;" id="celda0" name="celda0">
                                                                <div class="form-group">
                                                                    <div class="grid-block" style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                      <canvas id="plano_evolucion" name="plano" width="512" height="512"></canvas>
                                                                      <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                                                      <input type="hidden" name="x" id="x">
                                                                      <input type="hidden" name="y" id="y"> 
                                                                      <div id="control_div_puntos_evolucion" name="control_div_puntos_evolucion"> 
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                              </div>
                                                            </div> 
                                                            <div class="col-md-2" >
                                                              <ul class="leyenda_grafico" id="control_div_puntos_evolucion_leyenda" name="control_div_puntos_evolucion_leyenda"> 
                                                              </ul>
                                                            </div> 
                                                          </div> 
                                                          
                                                        </div>
                                                      </form>
                                                      
                                                  </fieldset>
                                                </div>
                                                <div class="tab-pane fade" id="tab4defaultEvoluciones">
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
                                                              <th width="15%">Fecha y hora</th>
                                                              <th width="50%">Tipo</th>
                                                              <th width="15%">Lugar</th>
                                                              <th width="15%">Acción</th>
                                                            </tr>
                                                          </thead>
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody id="historial_invasivos">
                                                        
                                                          </tbody>
                                                        </table>
                                                        <div id="load-more-container-registrar">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                       <div id="imagen_captura">
                        <img id="imagenCapturada" style="display: none;" />
                       </div>
                        <button type="submit" class="btn btn-success" id="registrarEvoluciones"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>