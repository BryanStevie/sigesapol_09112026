<!-- Modal -->
<div class="modal fade" id="modalIncidentes" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioIncidentes"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioIncidentes" name="FormularioIncidentes" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_i" name="id_hospitalizacion_i">
                    <input type="hidden" id="id_asegurado_i" name="id_asegurado_i">
                    <input type="hidden" id="id_servicio_i" name="id_servicio_i">
                    <input type="hidden" id="tipo_atencion" name="tipo_atencion">
                    <input type="hidden" id="id_nota_enfermeria2" name="id_nota_enfermeria2">
                    
                    
                    
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
                                                    <span id="numero_a"></span> - <span id="nombre_a"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">G.S.: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="tipo_sangre_a" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">ALERGIA: </label> <br>
                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                                                title="Alergia" class="listarAlergias" id="listarAlergias"><label id="" style="font-size: 14px;cursor: pointer;">SI</label></a>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">EDAD: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="edad_a" ></span></label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">SEXO: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="sexo_a" ></span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">HC: </label> <br>
                                                <label id="" style="font-size: 14px;"><span id="hc_a" ></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') --}}
                                    <div class="form-group">
                                        <div class="col-md-6"> <br>
                                            <label for="tipo">SERVICIO: </label> <br>
                                            <label style="font-size: 14px;"><span id="nombre_servicio2"></span></label>
                                        </div>
                                        <div class="col-md-3"> <br>
                                            <label for="tipo">FECHA DE NOTIFICACIÓN</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" autocomplete="off"
                                                name="fecha_incidente_notificacion" id="fecha_incidente_notificacion"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-3"> <br>
                                            <label for="tipo">FECHA DE VERIFICACIÓN</label>
                                            <input type="text" class="form-control datepicker"
                                                style="height: 28px !important;" autocomplete="off"
                                                name="fecha_incidente_verificacion" id="fecha_incidente_verificacion"
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                    </div>
                                </fieldset><br>
                                <div class="panel with-nav-tabs panel-default"
                                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                    <div class="panel-heading">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab0defaultIncidentes" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        DIAGNÓSTICOS</b></a></li>
                                            <li><a href="#tab1defaultIncidentes" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        NOTIFICADOR</b></a></li>
                                            <li><a href="#tab2defaultIncidentes" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        RESPONSABLE</b></a></li>
                                            <li><a href="#tab3defaultIncidentes" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        PLAN DE MEJORA</b></a></li>
                                            <li><a href="#tab4defaultIncidentes" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        VERIFICACIÓN DE PLAN.M</b></a></li>
                                        </ul>
                                    </div>
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade active in" id="tab0defaultIncidentes">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <tbody id="diagnosticos">
                                                                        {{-- @for($i = 1; $i <= 10; $i++)
                                                                         <tr>
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

                                            <div class="tab-pane" id="tab1defaultIncidentes">
                                                <fieldset>
                                                    <div class="col-md-4">
                                                        <label for="tipo">PERSONAL QUE NOTIFICA</label> <br>
                                                        <input type="radio" checked="" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica1"
                                                            value="MEDICO">  
                                                            <a href="javascript:void(0)" id="click_personal_notifica_medico" 
                                                            name="click_personal_notifica_medico"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_medico','personal_notifica1','personal_que_notifica','hide')">MÉDICO</a>                                                            
                                                        <input type="radio" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica2"
                                                            value="NO MEDICO" style="margin-left: 10px;">  
                                                            <a href="javascript:void(0)" id="click_personal_notifica_no_medico" 
                                                            name="click_personal_notifica_no_medico"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_no_medico','personal_notifica2','personal_que_notifica','show')">NO MÉDICO</a>
                                                        <input type="radio" class="regular-radio"
                                                            name="notificador_tipo_personal" id="personal_notifica3"
                                                            value="ESTUDIANTE" style="margin-left: 10px;">
                                                            <a href="javascript:void(0)" id="click_personal_notifica_estudiante" 
                                                            name="click_personal_notifica_estudiante"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_estudiante','personal_notifica3','personal_que_notifica','show')">ESTUDIANTE</a>
                                                    </div>
                                                    <div class="col-md-8" id="personal_que_notifica"
                                                        name="personal_que_notifica">
                                                        <label for="descripcion">DESCRIPCIÓN</label>
                                                        <input value="" type="text" name="notificador_tipo_personal_descripcion" class="form-control"
                                                            style="height: 28px !important;" />
                                                    </div>
                                                </fieldset>
                                                <fieldset>
                                                    <hr>
                                                    <div class="col-md-2">
                                                        <label for="tipo_incidente" style="visibility: hidden;">TIPO</label> <br>
                                                        <input type="radio" checked="" class="regular-radio"
                                                            name="notificador_tipo_registro" id="tipo_incidente1"
                                                            value="INCIDENTE"> 
                                                            
                                                            <a href="javascript:void(0)" id="click_personal_notifica_incidente" 
                                                            name="click_personal_notifica_incidente"  style="color: #333333;" 
                                                            onclick="seleccionar_radio_por_texto('click_personal_notifica_incidente','tipo_incidente1','tipo_de_eventos_adversos','hide')">INCIDENTE</a>  
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <label for="tipo_incidente"
                                                                    style="visibility: hidden;">TIPO</label> <br>
                                                                <input type="radio" class="regular-radio"
                                                                    name="notificador_tipo_registro" id="tipo_incidente2"
                                                                    value="EVENTO ADVERSO" style="margin-left: 10px;">
                                                            
                                                                    <a href="javascript:void(0)" id="click_personal_notifica_eve_adverso" 
                                                                    name="click_personal_notifica_eve_adverso"  style="color: #333333;" 
                                                                    onclick="seleccionar_radio_por_texto('click_personal_notifica_eve_adverso','tipo_incidente2','tipo_de_eventos_adversos','show')">EVENTO ADVERSO</a> 
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" id="tipo_de_eventos_adversos"
                                                            name="tipo_de_eventos_adversos">
                                                            <label for="sel_event_adver">TIPO DE EVENTO ADVERSO</label> <br>
                                                            <select class="form-control select2" name="selecTipoAdv" id="selecTipoAdv" style="width: 100%">
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
                                                            name="notificador_fecha_evento" id="fecha_incidente_suceso"
                                                            value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>HORA</label>
                                                        <input type="time" name="notificador_hora_evento" id="hora" class="form-control"
                                                            style="height: 28px !important;" />
                                                    </div>
                                                </fieldset>
                                                <fieldset>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <label for="tipo">OBSERVACIONES</label>
                                                        <textarea class="form-control" rows="6" id="notificador_observaciones" name="notificador_observaciones"
                                                            style="text-transform:uppercase"></textarea>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab2defaultIncidentes">
                                                <fieldset>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="col-md-6"> 
                                                                    <div class="col-md-12" style="margin-bottom: 10px; padding-left: 0px !important;">
                                                                        <a href="javascript:void(0)" id="add_PERS_CONV_EVEAD" name="add_PERS_CONV_EVEAD" class="btn btn-success btn-sm add_PERS_CONV_EVEAD">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i> PERSONAL CONVOCADO
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-12" style="padding-left: 0px !important;">
                                                                        <table id="tbl_PERS_CONV_EVEAD" style="margin-bottom: 10px !important;">
                                                                            <tbody id="cuerpo_PERS_CONV_EVEAD" name="cuerpo_PERS_CONV_EVEAD" class="cuerpo_PERS_CONV_EVEAD">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>  
                                                                </div>
                                                                <div class="col-md-6" style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                    <label for="tipo">ANÁLISIS DE CAUSAS:</label><br>
                                                                    <p>(Identifique las acciones inseguras y factores
                                                                        contributivos)</p>
                                                                    <textarea class="form-control" name="responsable_analisis_causas" rows="5" required
                                                                        style="text-transform:uppercase"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="col-md-4">
                                                                    <label for="tipo">CATEGORÍA DE E.ADVERSOS</label> 
                                                                    <select name="categoria_evento_adverso" id="categoria_evento_adverso" 
                                                                        class="form-control input-sm select2 w-full" style="width: 100%;"> 
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label for="tipo">TIPOS DE E.ADVERSOS</label> 
                                                                    <select name="categoria_tipo_evento_adverso" id="categoria_tipo_evento_adverso" 
                                                                        class="form-control input-sm select2 w-full" style="width: 100%;"> 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab3defaultIncidentes">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                <a  href="javascript:void(0)" id="add_ACCI_CORRECT" name="add_ACCI_CORRECT" 
                                                                    class="btn btn-success btn-sm add_ACCI_CORRECT"> 
                                                                    <i class="fa fa-plus" aria-hidden="true"></i> ACCIONES CORRECTIVAS
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                <table id="tbl_ACCI_CORRECT" style="margin-bottom: 10px !important;"> 
                                                                    <tbody  id="cuerpo_ACCI_CORRECT" name="cuerpo_ACCI_CORRECT" 
                                                                            class="cuerpo_ACCI_CORRECT">
                                                                    </tbody>
                                                                </table> 
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12" style="margin-bottom: 10px;padding-left: 0px !important;"> 
                                                                <a  href="javascript:void(0)" id="add_ACCI_PREVENT" name="add_ACCI_PREVENT" 
                                                                    class="btn btn-success btn-sm add_ACCI_PREVENT"> 
                                                                    <i class="fa fa-plus" aria-hidden="true"></i> ACCIONES PREVENTIVAS
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12" style="padding-left: 0px !important;"> 
                                                                <table id="tbl_ACCI_PREVENT" style="margin-bottom: 10px !important;"> 
                                                                    <tbody  id="cuerpo_ACCI_PREVENT" name="cuerpo_ACCI_PREVENT" 
                                                                            class="cuerpo_ACCI_PREVENT">
                                                                    </tbody>
                                                                </table> 
                                                            </div>  
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="tab-pane" id="tab4defaultIncidentes">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <label for="tipo">VERIFICACIÓN DE LA EFECTIVIDAD DEL PLAN DE
                                                                MEJORA</label>
                                                            <textarea class="form-control" name="verificacion_plan_mejora" rows="6"
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
                            <button type="submit" class="btn btn-success" id="registrarIncidentes"
                                value="create">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>