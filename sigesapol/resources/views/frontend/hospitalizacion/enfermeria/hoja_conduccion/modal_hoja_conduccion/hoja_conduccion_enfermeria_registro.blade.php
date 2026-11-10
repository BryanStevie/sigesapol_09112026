<!-- Modal -->
<div class="modal fade" id="modalRegistrarHojaConduccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioRegistrarHojaConduccion"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioRegistrarHojaConduccion" name="FormularioRegistrarHojaConduccion"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_incidencia" name="id_hospitalizacion_incidencia">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label for="tipo">TIPO DE ATENCIÓN:</label> <br>
                                                <label id="" style="font-size: 14px;">HOSPITALIZADO</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">SERVICIO:</label> <br>
                                                <label id="" style="font-size: 14px;">CARDIOLOGIA</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">ANEXO:</label> <br>
                                                <label id="" style="font-size: 14px;">963258</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">CAMA:</label> <br>
                                                <label id="" style="font-size: 14px;">2</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">PISO:</label> <br>
                                                <label id="" style="font-size: 14px;">2</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">CONDICIÓN:</label> <br>
                                                <label id="" style="font-size: 14px;">HIJO NO INCAPACITADO</label>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="margin-top: 0px;margin-bottom: 5px;">
                                            </div>
                                            <div class="col-md-8">
                                                <label for="tipo">DNI - MÉDICO:</label> <br>
                                                <label style="font-size: 14px;">8888888 - PEREZ QUISPE, JHONATAN MARCO</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">COLEG.:</label> <br>
                                                <label id="" style="font-size: 14px;">12546</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">RNE:</label> <br>
                                                <label id="" style="font-size: 14px;">9632</label>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="margin-top: 0px;margin-bottom: 5px;">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">TRANSFUSIONES PREVIAS:</label> <br>
                                                <label id="" style="font-size: 14px;">SI</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">REACCIONES TRANSF. ANT.:</label> <br>
                                                <label id="" style="font-size: 14px;">DESCONOCIDO</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">EMBARAZOS PREVIOS:</label> <br>
                                                <label id="" style="font-size: 14px;">SI</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">ABORTOS:</label> <br>
                                                <label id="" style="font-size: 14px;">SI</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">INCOMP. MATERNO:</label> <br>
                                                <label id="" style="font-size: 14px;">SI</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label for="tipo">DIAGNOSTICO:</label> <br>
                                                <label id="" style="font-size: 14px;">Y90.5 - NIVEL DE ALCOHOL EN LA SANGRE DE 100 A 119 MG/100 ML</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">TIPO:</label> <br>
                                                <label id="" style="font-size: 14px;">DEFINITIVO</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr style="margin-top: 0px;margin-bottom: 15px;">
                                    </div>
                                    
                                </fieldset>
                                <fieldset>

                                    <div class="panel with-nav-tabs panel-default"
                                        style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                        <div class="panel-heading">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab0defaultConduccion" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            COMP. A TRANSFUNDIR</b></a></li>
                                                <li><a href="#tab1defaultConduccion" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            ANTECEDENTES</b></a></li>
                                                <li><a href="#tab2defaultConduccion" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            TRANSF. PREVIAS</b></a></li>
                                                <li><a href="#tab3defaultConduccion" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        CONDUC. DE LA TRANSFUSION</b></a></li>
                                                <li><a href="#tab4defaultConduccion" data-toggle="tab"
                                                    style="font-size:12px" aria-expanded="true"><b><i
                                                            class="fa fa-file-text-o" aria-hidden="true"></i>
                                                        REAC. ADVERSAS</b></a></li>            
                                            </ul>
                                        </div>
                                        <div class="panel-body" style="padding-bottom: 0px !important;">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultConduccion">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example" border="0"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 40%;">HEMOCOMPONENTE</th>
                                                                                <th style="text-align: center;">LOTE</th>
                                                                                
                                                                                <th style="text-align: center;">GRUPO/RH</th>
                                                                                <th style="text-align: center;">SELLO CALIDAD</th>
                                                                                <th style="text-align: center;">COMPATIBILIDAD</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @for($i = 1; $i <= 5; $i++) <tr>
                                                                                @if ($i%2==0)
                                                                                <td>PAQUETE GLOBULAR {{$i }} 
                                                                                </td>
                                                                                <td style="text-align: center;"><b>25896</b></td>
                                                                                
                                                                                <td style="text-align: center;">AB+</td>
                                                                                @else
                                                                                <td>PLAQUETAS X AFÉRESIS {{$i }} 
                                                                                </td>
                                                                                <td style="text-align: center;"><b>96547</b></td>
                                                                                
                                                                                <td style="text-align: center;">O+</td>
                                                                                @endif
                                                                                <td style="text-align: center;">
                                                                                    <b>8521-664</b>
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    COMPATIBLE
                                                                                </td>
                                                                                </tr>
                                                                                @endfor
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab1defaultConduccion">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">ALERGIA A MEDICAMENTOS</th>
                                                                            <th style="text-align: center;">OPERACIONES ANTERIORES</th>
                                                                            <th style="text-align: center;">CONSUMO ALCOHOL/DROGA</th>
                                                                            <th style="text-align: center;">HIPERTENSIÓN ARTERIAL</th>
                                                                            <th style="text-align: center;">DIABETES MELLITUS</th>
                                                                            <th style="text-align: center;">CONTACTO SEXUAL DE RIESGO</th>
                                                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">HIV/SIDA</th>
                                                                            <th style="text-align: center;">HEPATITIS</th>
                                                                            <th style="text-align: center;">SÍFILIS</th>
                                                                            <th style="text-align: center;">ASMA</th>
                                                                            <th style="text-align: center;">DIÁLISIS</th>
                                                                            <th style="text-align: center;">OTRO</th>
                                                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="text" class="form-control"></td>
                                                                            
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>


                                                <div class="tab-pane fade" id="tab2defaultConduccion">
                                                    <fieldset>
                                                        <div class="col-md-12">
                                                            <div class="col-md-12"
                                                                style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                <a href="javascript:void(0)"
                                                                    id="add_transfusion_previa" name="add_transfusion_previa"
                                                                    class="btn btn-success btn-sm add_transfusion_previa">
                                                                    <i class="fa fa-plus"
                                                                        aria-hidden="true"></i>
                                                                        AGREGAR TRANSFUSION PREVIA
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12"
                                                                style="padding-left: 0px !important;">
                                                                <table id="tbl_transfusion_previa" border="0"
                                                                    style="margin-bottom: 10px !important;">
                                                                    <tbody id="cuerpo_transfusion_previa"
                                                                        name="cuerpo_transfusion_previa"
                                                                        class="cuerpo_transfusion_previa">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="tab-pane fade" id="tab3defaultConduccion">
                                                    <fieldset>
                                                        <div class="form-group" >
                                                            <div class="col-md-12" style="margin-bottom: 20px !important;">
                                                                <div class="col-md-3">
                                                                    <label for="">Fecha de Inicio</label><br>
                                                                    <input class="form-control" style="width: 100%;" type="date" name="" id="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Hora de Inicio</label><br>
                                                                    <input class="form-control" style="width: 100%;" type="time" name="" id="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Fecha de Fin</label><br>
                                                                    <input class="form-control" style="width: 100%;" type="date" name="" id="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="">Hora de Fin</label><br>
                                                                    <input class="form-control" style="width: 100%;" type="time" name="" id="">
                                                                </div>
                                                            </div>
   
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="col-md-12">
                                                            <div class="col-md-12"
                                                                style="margin-bottom: 10px;padding-left: 0px !important;">
                                                                <a href="javascript:void(0)"
                                                                    id="add_registro_hoja" name="add_registro_hoja"
                                                                    class="btn btn-success btn-sm add_registro_hoja">
                                                                    <i class="fa fa-plus"
                                                                        aria-hidden="true"></i>
                                                                        AGREGAR REGISTRO
                                                                </a>
                                                            </div>
                                                            <div class="col-md-12"
                                                                style="padding-left: 0px !important;">
                                                                <table id="tbl_registro_hoja" border="0"
                                                                    style="margin-bottom: 10px !important;">
                                                                    <tbody id="cuerpo_registro_hoja"
                                                                        name="cuerpo_registro_hoja"
                                                                        class="cuerpo_registro_hoja">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align: center;" colspan="4">ESTADOS CLÍNICOS</th>
                                                                                
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="text-align: left; width: 20%;"><b>ESTADO DE CONSCIENCIA</b></td>
                                                                                <td style="text-align: left;"><input type="radio" name="con" id=""> CONSCIENTE</td>
                                                                                <td style="text-align: left;"><input type="radio" name="con" id=""> SOMNOLIENTO O DESORIENTADO</td>
                                                                                <td style="text-align: left;"><input type="radio" name="con" id=""> COMA</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="text-align: left;"><b>ESTADO CARDIOVASCULAR</b></td>
                                                                                <td style="text-align: left;"><input type="radio" name="car" id="" > NORMAL </td>
                                                                                <td style="text-align: left;"><input type="radio" name="car" id=""> ARRITMIA, HTA NO CONTROLADA, IMA, VALVULOPATÍA, OTROS</td>
                                                                                <td style="text-align: left;"><input type="radio" name="car" id=""> PARO CARDIACO</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="text-align: left;"><b>ESTADO PULMONAR</b></td>
                                                                                <td style="text-align: left;"><input type="radio" name="pul" id=""> NORMAL</td>
                                                                                <td style="text-align: left;"><input type="radio" name="pul" id=""> BRONCOESPASMO, EDEMA PULMONAR, INSUF. RESPI., OTROS</td>
                                                                                <td style="text-align: left;"><input type="radio" name="pul" id=""> PARO RESPIRATORIO</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="tab-pane fade" id="tab4defaultConduccion">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">DOLOR TORÁCICO</th>
                                                                            <th style="text-align: center;">DOLOR LUMBAR</th>
                                                                            <th style="text-align: center;">HEMOGLOBINURIA</th>
                                                                            <th style="text-align: center;">ESCALOFRÍOS</th>
                                                                            <th style="text-align: center;">HIPOTENSIÓN</th>
                                                                            <th style="text-align: center;">ALERGIA/URTICARIA</th>
                                                                            <th style="text-align: center;">CIANOSIS</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">CEFALEA</th>
                                                                            <th style="text-align: center;">NAUSEA/VÓMITO</th>
                                                                            <th style="text-align: center;">FIEBRE</th>
                                                                            <th style="text-align: center;">DISNEA</th>
                                                                            <th style="text-align: center;">EDEMA FACIAL</th>
                                                                            <th style="text-align: center;">ANAFILAXIA</th>
                                                                            <th style="text-align: center;">OTRO</th>
                                                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="checkbox"></td>
                                                                            <td style="text-align: center;"><input type="text" class="form-control"></td>
                                                                            
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left"
                                data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" id="registrarHojaConduccionRecepcion"
                                value="create">FIRMAR Y GUARDAR</button>
                            <button type="submit" class="btn btn-success" id="registrarHojaConduccionRecepcion"
                                value="create">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
