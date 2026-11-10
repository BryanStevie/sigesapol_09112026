<!-- Modal -->
<div class="modal fade" id="editarModalIncidenteEA" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioIncidente"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioIncidentesEdit" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_incidencia" name="id_incidencia">
                    <input type="hidden" id="id_categoria_adverso">
                    <input type="hidden" id="id_categoria_tipo_adverso">
                   
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label for="tipo">DNI - PACIENTE: </label> <br>
                                                <label style="font-size: 14px;">
                                                    <span id="numero_doc_edit"></span> - <span id="nombres_edit"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">G.S.: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="tipo_sangre_edit" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">ALERGIA: </label> <br>
                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                                                title="Alergia" class="listarAlergias" id="listarAlergias"><label id="" style="font-size: 14px;cursor: pointer;">SI</label></a>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">EDAD: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="edad_edit" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">SEXO: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="sexo_edit" ></span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">HC: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="hc_edit" ></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') --}}
                                    <div class="form-group">
                                        <div class="col-md-6"> <br>
                                            <label for="tipo">SERVICIO: </label> <br>
                                            <label style="font-size: 14px;"><span id="nombre_servicio_edit"></span></label>
                                        </div>
                                        <div class="col-md-3"> <br>
                                            <label for="tipo">FECHA DE NOTIFICACIÓN</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" autocomplete="off"
                                                name="fecha_notificacion" id="fecha_notificacion_edit"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" >
                                        </div>
                                        <div class="col-md-3"> <br>
                                            <label for="tipo">FECHA DE VERIFICACIÓN</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" autocomplete="off"
                                                name="fecha_incidente_verificacion" id="fecha_incidente_verificacion_edit"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" >
                                        </div>
                                    </div>
                                </fieldset><br>
                                <div class="panel with-nav-tabs panel-default"
                                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                    <div class="panel-heading">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab0defaultIncidente_edit" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        DIAGNÓSTICOS</b></a></li>
                                            <li><a href="#tab1defaultIncidente_edit" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        NOTIFICADOR</b></a></li>
                                            <li><a href="#tab2defaultIncidente_edit" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        RESPONSABLE</b></a></li>
                                            <li><a href="#tab3defaultIncidente_edit" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        PLAN DE MEJORA</b></a></li>
                                            <li><a href="#tab4defaultIncidente_edit" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        VERIFICACIÓN DE PLAN.M</b></a></li>
                                        </ul>
                                    </div>
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade active in" id="tab0defaultIncidente_edit">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <tbody id="diagnosticos_edit">
                                                                        {{-- @for($i = 1; $i <= 10; $i++) <tr>
                                                                            <td>DESCRIPCION DE DIAGNÓSTICOS {{$i}}</td>
                                                                            </tr>
                                                                            @endfor --}}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab1defaultIncidente_edit">
                                                <fieldset>
                                                    <div class="col-md-4">
                                                        <label for="tipo">PERSONAL QUE NOTIFICA</label> <br>
                                                        <input type="radio" checked="" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica1_edit"
                                                            value="MEDICO">  
                                                            <a href="javascript:void(0)" id="click_personal_notifica_medico" 
                                                            name="click_personal_notifica_medico"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_medico','personal_notifica1_edit','personal_que_notifica_edit','hide')">MÉDICO</a>                                                            
                                                        <input type="radio" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica2_edit"
                                                            value="NO MEDICO" style="margin-left: 10px;">  
                                                            <a href="javascript:void(0)" id="click_personal_notifica_no_medico" 
                                                            name="click_personal_notifica_no_medico"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_no_medico','personal_notifica2_edit','personal_que_notifica_edit','show')">NO MÉDICO</a>
                                                        <input type="radio" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica3_edit"
                                                            value="ESTUDIANTE" style="margin-left: 10px;">
                                                            <a href="javascript:void(0)" id="click_personal_notifica_estudiante" 
                                                            name="click_personal_notifica_estudiante"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_estudiante','personal_notifica3_edit','personal_que_notifica_edit','show')">ESTUDIANTE</a>
                                                    </div>
                                                    <div class="col-md-8" id="personal_que_notifica_n_edit"
                                                        name="personal_que_notifica">
                                                        <label for="descripcion">DESCRIPCIÓN</label>
                                                        <input value="" type="text" name="notificador_tipo_personal_descripcion" id="notificador_tipo_personal_descripcion_n_edit" class="form-control"
                                                            style="height: 28px !important;" pattern="[A-Za-z0-9_-]{1,15}" required/>
                                                    </div>
                                                </fieldset>
                                                <fieldset>
                                                    <hr>
                                                    <div class="col-md-2">
                                                        <label for="tipo_incidente" style="visibility: hidden;">TIPO</label> <br>
                                                        <input type="radio" checked="" class="regular-radio"
                                                            name="notificador_tipo_registro" id="tipo_incidente1_edit"
                                                            value="INCIDENTE"> 
                                                            
                                                            <a href="javascript:void(0)" id="click_personal_notifica_incidente" 
                                                            name="click_personal_notifica_incidente"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_incidente','tipo_incidente1_edit','tipo_de_eventos_adversos2','hide')">INCIDENTE</a>  
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <label for="tipo_incidente"
                                                                    style="visibility: hidden;">TIPO</label> <br>
                                                                <input type="radio" class="regular-radio"
                                                                    name="notificador_tipo_registro" id="tipo_incidente2_edit"
                                                                    value="EVENTO ADVERSO" style="margin-left: 10px;">
                                                            
                                                                    <a href="javascript:void(0)" id="click_personal_notifica_eve_adverso" 
                                                                    name="click_personal_notifica_eve_adverso"  style="color: #333333;" 
                                                                    onclick="seleccionar_radio_por_texto('click_personal_notifica_eve_adverso','tipo_incidente2_edit','tipo_de_eventos_adversos2','show')">EVENTO ADVERSO</a> 
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" id="tipo_de_eventos_adversos2"
                                                            name="tipo_de_eventos_adversos">
                                                            <label for="sel_event_adver">TIPO DE EVENTO ADVERSO</label> <br>
                                                            <select class="form-control select2" name="selecTipoAdv" id="selecTipoAdv_edit" style="width: 100%">
                                                                <option value="">SELECCIONAR</option>
                                                                <option value="LEVE">LEVE</option>
                                                                <option value="MODERADO">MODERADO</option>
                                                                <option value="GRAVE">GRAVE</option>
                                                                <option value="MUERTE">MUERTE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="tipo">FECHA DE SUCESO</label>
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;" autocomplete="off"
                                                            name="notificador_fecha_evento" id="fecha_incidente_suceso_n_edit"
                                                            value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>HORA</label>
                                                        <input type="time" name="notificador_hora_evento" id="hora_edit" class="form-control"
                                                            style="height: 28px !important;" />
                                                    </div>
                                                </fieldset>
                                                <fieldset>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <label for="tipo">OBSERVACIONES</label>
                                                        <textarea class="form-control" rows="6" name="notificador_observaciones" id="notificador_observaciones_edit"
                                                            style="text-transform:uppercase"></textarea>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab2defaultIncidente_edit">
                                                <fieldset>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="col-md-6"> 
                                                                    <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                        <a  href="javascript:void(0)" id="add_PERS_CONV_EVEAD2" name="add_PERS_CONV_EVEAD" 
                                                                            class="btn btn-success btn-sm add_PERS_CONV_EVEAD2"> 
                                                                            <i class="fa fa-plus" aria-hidden="true"></i> PERSONAL CONVOCADO 
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                        <table id="tbl_PERS_CONV_EVEAD2" style="margin-bottom: 10px !important;"> 
                                                                            <tbody  id="cuerpo_PERS_CONV_EVEAD2" name="cuerpo_PERS_CONV_EVEAD" 
                                                                                    class="cuerpo_PERS_CONV_EVEAD2">
                                                                            </tbody>
                                                                        </table> 
                                                                    </div>    
                                                                </div>
                                                                <div class="col-md-6" style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                    <label for="tipo">ANÁLISIS DE CAUSAS:</label><br>
                                                                    <p>(Identifique las acciones inseguras y factores
                                                                        contributivos)</p>
                                                                    <textarea class="form-control" name="responsable_analisis_causas" id="responsable_analisis_causas2" rows="5" required
                                                                        style="text-transform:uppercase"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="col-md-4">
                                                                    <label for="tipo">CATEGORÍA DE E.ADVERSOS</label> 
                                                                    <select name="categoria_evento_adverso" id="categoria_evento_adverso2" 
                                                                        class="form-control input-sm select2 w-full" style="width: 100%;"> 
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label for="tipo">TIPOS DE E.ADVERSOS</label> 
                                                                    <select name="categoria_tipo_evento_adverso" id="categoria_tipo_evento_adverso2" 
                                                                        class="form-control input-sm select2 w-full" style="width: 100%;"> 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab3defaultIncidente_edit">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                <a  href="javascript:void(0)" id="add_ACCI_CORRECT2" name="add_ACCI_CORRECT" 
                                                                    class="btn btn-success btn-sm add_ACCI_CORRECT2"> 
                                                                    <i class="fa fa-plus" aria-hidden="true"></i> ACCIONES CORRECTIVAS
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                <table id="tbl_ACCI_CORRECT2" style="margin-bottom: 10px !important;"> 
                                                                    <tbody  id="cuerpo_ACCI_CORRECT2" name="cuerpo_ACCI_CORRECT" 
                                                                            class="cuerpo_ACCI_CORRECT2 
                                                                            ">
                                                                    </tbody>
                                                                </table> 
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                <a  href="javascript:void(0)" id="add_ACCI_PREVENT2" name="add_ACCI_PREVENT" 
                                                                    class="btn btn-success btn-sm add_ACCI_PREVENT2"> 
                                                                    <i class="fa fa-plus" aria-hidden="true"></i> ACCIONES PREVENTIVAS
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                <table id="tbl_ACCI_PREVENT2" style="margin-bottom: 10px !important;"> 
                                                                    <tbody  id="cuerpo_ACCI_PREVENT2" name="cuerpo_ACCI_PREVENT" 
                                                                            class="cuerpo_ACCI_PREVENT2">
                                                                    </tbody>
                                                                </table> 
                                                            </div>  
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab4defaultIncidente_edit">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <label for="tipo">VERIFICACIÓN DE LA EFECTIVIDAD DEL PLAN DE
                                                                MEJORA</label>
                                                            <textarea class="form-control" name="verificacion_plan_mejora" id="verificacion_plan_mejora_v_edit" rows="6" required
                                                                style="text-transform:uppercase"></textarea>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left"
                                data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" name="actualizarIncidentes"  id="actualizarIncidentes" 
                                value="create">Guardar Datos</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>