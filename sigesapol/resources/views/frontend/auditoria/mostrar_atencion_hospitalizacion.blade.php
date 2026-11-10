<script>
    @if ($obj_hospitalizacion_atencion->exam_reg_19 != "") // Primer registro de Pares craneales
      let toggleButton = document.getElementById('addParesCraneales');
      toggleButton.classList.add('active');      // Añadir la clase activa
      toggleButton.setAttribute('aria-pressed', 'true');  // Cambiar el atributo aria
      toggleButton.setAttribute('disabled', 'true');
      $("#fsParesCraneales").show();
    @endif
  </script>
  <div class="container" style="width:100%">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="panel panel-primary filterable">
            <div class="panel-body">
              
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <fieldset>
                    <legend>
                      <?php if ($prestacion->id_estado_reg==0): ?>
                        <b><span style="color:red;"> DATOS DE LA ATENCI&Oacute;N ANULADA</span></b>
                      <?php else: ?>
                        <b>DATOS DE LA ATENCI&Oacute;N</b>
                      <?php endif; ?>
                    </legend>
                    <div>
                      <div class="form-group">
                        <div class="col-md-2">
                          <label for="fecha_atencion">FECHA DE ATENCION</label>
                          <p>{{$prestacion->fecha_atencion}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>N° HISTORIA CLINICA</label>
                          <p>{{$prestacion->nro_historia}}</p>
                        </div>
                        <div class="col-md-3">
                          <label for="nombre_ipres">AREA</label>
                          <p>{{$prestacion->servicio}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>CODIGO UPSS</label>
                          <p>{{$prestacion->codigo_upss}}</p>
                        </div>
                        
                      </div>
                      
                      @if($sw==1)
                      <div class="form-group">
                        <div class="col-md-2">
                          <label for="tipo">PABELLON</label>
                          <p>{{$datoadicional->pabellon}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="dni_beneficiario">PISO</label>
                          <p>{{$datoadicional->piso}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>HABITACION</label>
                          <p>{{$datoadicional->cuarto}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>CAMA/CAMILLA</label>
                          <p>{{$datoadicional->cama}}</p>
                        </div>
                      </div>
                      @endif
                    </div>
                  </fieldset>
                </div>
  
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL PACIENTE</b>
                      </div>
                    </div>
                    <div class="panel-body" id="collapseprestacionespaciente">
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                          <p>{{$prestacion->tipo_doc_ident}}: {{$prestacion->nro_doc_ident}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO PATERNO</label>
                          <p>{{$prestacion->asegurado_paterno}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO MATERNO</label>
                          <p>{{$prestacion->asegurado_materno}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>NOMBRES</label>
                          <p>{{$prestacion->asegurado_nombre}}</p>
                        </div>
                      </div>
                      @if($prestacion->parentesco=='TITULAR')
                      <div class="form-group">
                        <div class="col-md-2">
                          <label>FECHA DE NACIMIENTO</label>
                          <p>{{$prestacion->fecha_nac}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>PARENTESCO &nbsp;</label>
                          <p>{{$prestacion->parentesco}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="cip">CIP</label>
                          <p>{{$prestacion->cip}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="grado">GRADO</label>
                          <p>{{$prestacion->grado}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="nomsexo">SEXO</label>
                          <p>{{$prestacion->sexo}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="edadpaciente">EDAD</label>
                          <p>{{$prestacion->edadpaciente}}</p>
                        </div>
                      </div>
                      @else
                      <div class="form-group">
                        <div class="col-md-3">
                          <label>FECHA DE NACIMIENTO</label>
                          <p>{{$prestacion->fecha_nac}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>PARENTESCO &nbsp;</label>
                          <p>{{$prestacion->parentesco}}</p>
                        </div>
                        <div class="col-md-3">
                          <label for="nomsexo">SEXO</label>
                          <p>{{$prestacion->sexo}}</p>
                        </div>
                        <div class="col-md-3">
                          <label for="edadpaciente">EDAD</label>
                          <p>{{$prestacion->edadpaciente}}</p>
                        </div>
                          @if($prestacion->situacion_paciente!='CIVIL' && $flag_no_encontrado==0)
                          <div class="col-md-3"><br/>
                            <label>DOCUMENTO TITULAR</label>
                            <p>{{$dato_titular[0]->tipo_doc_ident}} : {{$dato_titular[0]->nro_doc_ident}}</p>
                          </div>
                          <div class="col-md-3"><br/>
                            <label>DATO DEL TITULAR &nbsp;</label>
                            <p>{{$dato_titular[0]->nombre}}, {{$dato_titular[0]->paterno}} {{$dato_titular[0]->materno}}</p>
                          </div>
                          <div class="col-md-3"><br/>
                            <label for="cip">CIP</label>
                            <p>{{$dato_titular[0]->cip}}</p>
                          </div>
                          <div class="col-md-3"><br/>
                            <label for="grado">GRADO</label>
                            <p>{{$dato_titular[0]->grado}}</p>
                          </div>
                        </div>
                        @endif
                      @endif
                    </div>
                  </div>
                </fieldset>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                          <p>DNI: {{$prestacion->dni}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO PATERNO</label>
                          <p>{{$prestacion->medico_paterno}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO MATERNO</label>
                          <p>{{$prestacion->medico_materno}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>NOMBRES</label>
                          <p>{{$prestacion->medico_nombre}}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label>ESPECIALIDAD</label>
                          <p>{{$prestacion->especializacion}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>PROFESION</label>
                          <p>{{$prestacion->profesion}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>N° COLEGIATURA</label>
                          <p>{{$prestacion->colegiatura}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>N° RNE</label>
                          <p>{{$prestacion->rne}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
  
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>ENFERMEDAD ACTUAL</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-lg-3 col-md-12">
                          <label>TIEMPO DE ENFERMEDAD</label>
                          <input type="text" class="form-control" id="tiempo_enfermedad" name="tiempo_enfermedad" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->tiempo_enfermedad}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>FORMA DE INICIO</label>
                          <input type="text" class="form-control" id="forma_inicio" name="forma_inicio" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->forma_inicio}}">
                        </div>
                        <div class="col-lg-3 col-md-12">
                          <label>CURSO</label>
                          <input type="text" class="form-control" id="curso" name="curso" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->curso}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>SINTOMAS Y SIGNOS PRINCIPALES</label>
                          <textarea class="form-control" name="sintomas_signos_principal" id="sintomas_signos_principal" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->sintomas_signos_principal)) !!}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>RELATO CRONOLÓGICO</label>
                          <textarea tabindex="" class="form-control" rows="10" id="relato" name="relato" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->relato)) !!}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>TRATAMIENTO RECIBIDO ANTES DE LA HOSPITALIZACIÓN</label>
                          <textarea class="form-control" name="tratamiento_recibido_antes" id="tratamiento_recibido_antes" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->tratamiento_recibido_antes)) !!}</textarea>
                        </div>
                      </div>
                      <div>
                        <label for="">FUNCIONES BIOLÓGICAS</label>
                        <div class="form-group">
                          <div class="col-lg-3 col-md-12">
                            <label>APETITO</label>
                            <input type="text" class="form-control" id="fn_bio_1" name="fn_bio_1" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_1}}">
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <label>SED</label>
                            <input type="text" class="form-control" id="fn_bio_2" name="fn_bio_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_2}}">
                          </div>
                          <div class="col-lg-3 col-md-12" style="display:none">
                            <label>PESO</label>
                            <input type="text" class="form-control" id="fn_bio_3" name="fn_bio_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_3}}">
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <label>MICCIÓN</label>
                            <input type="text" class="form-control" id="fn_bio_4" name="fn_bio_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_4}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-3 col-md-12">
                            <label>DEPOSICIONES</label>
                            <input type="text" class="form-control" id="fn_bio_5" name="fn_bio_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_5}}">
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <label>SUEÑO</label>
                            <input type="text" class="form-control" id="fn_bio_6" name="fn_bio_6" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->fn_bio_6}}">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
  
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>ANTECEDENTES</b>
                      </div>
                    </div>
  
                    <div class="panel-body">
                      <div class="panel panel-primary panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title">
                            <b>PERSONALES</b>
                          </div>
                        </div>
                        <div class="panel-body">
                            <label for=""><span>Generales:</span></label>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Grupo sanguíneo y factor RH:</label>
                                <input type="text" class="form-control" id="antc_1" name="antc_1" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_1}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Alergías-RAM:</label>
                                <input type="text" class="form-control" id="antc_2" name="antc_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_2}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Drogas:</label>
                                <input type="text" class="form-control" id="antc_3" name="antc_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_3}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Alcohol:</label>
                                <input type="text" class="form-control" id="antc_4" name="antc_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_4}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Tabaco:</label>
                                <input type="text" class="form-control" id="antc_5" name="antc_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_5}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Café:</label>
                                <input type="text" class="form-control" id="antc_6" name="antc_6" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_6}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Transfusiones:</label>
                                <input type="text" class="form-control" id="antc_7" name="antc_7" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_7}}">
                              </div>
                              <div class="col-lg-10 col-md-12">
                                <label>Otros de importancia:</label>
                                <textarea class="form-control" name="antc_8" id="antc_8" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->antc_8)) !!}</textarea>
                              </div>
                            </div>
                            <label for=""><span>Fisiológicos:</span></label>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Nacimiento:</label>
                                <input type="text" class="form-control" id="antc_9" name="antc_9" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_9}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Desarrollo Psicomotor:</label>
                                <input type="text" class="form-control" id="antc_10" name="antc_10" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_10}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Vacunas:</label>
                                <input type="text" class="form-control" id="antc_11" name="antc_11" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_11}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Inicio de relaciones sexuales:</label>
                                <input type="text" class="form-control" id="antc_12" name="antc_12" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_12}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Comportamiento Sexual:</label>
                                <input type="text" class="form-control" id="antc_13" name="antc_13" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_13}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Menarquía:</label>
                                <input type="text" class="form-control" id="antc_14" name="antc_14" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_14}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Régimen Catamenial:</label>
                                <input type="text" class="form-control" id="antc_15" name="antc_15" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_15}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Fecha de última regla:</label>
                                <input type="text" class="form-control" id="antc_16" name="antc_16" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_16}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Método Anticonceptivo:</label>
                                <input type="text" class="form-control" id="antc_17" name="antc_17" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_17}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Uso de hormonas:</label>
                                <input type="text" class="form-control" id="antc_18" name="antc_18" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_18}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Gesta:</label>
                                <input type="text" class="form-control" id="antc_19" name="antc_19" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_19}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>PARA:</label>
                                <input type="text" class="form-control" id="antc_20" name="antc_20" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_20}}">
                              </div>
                            </div>
                            <label for=""><span>Patológicos:</span></label>
                            <div class="form-group">
                              <div class="col-lg-12 col-md-12">
                                <label>Enfermedades de la infancia:</label>
                                <textarea class="form-control" name="antc_21" id="antc_21" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->antc_21)) !!}</textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Diabetes:</label>
                                <input type="text" class="form-control" id="antc_22" name="antc_22" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_22}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Hipertensión:</label>
                                <input type="text" class="form-control" id="antc_23" name="antc_23" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_23}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>TBC:</label>
                                <input type="text" class="form-control" id="antc_24" name="antc_24" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_24}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Dislipidemias:</label>
                                <input type="text" class="form-control" id="antc_25" name="antc_25" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_25}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Hepatitis:</label>
                                <input type="text" class="form-control" id="antc_26" name="antc_26" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_26}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Convulsiones:</label>
                                <input type="text" class="form-control" id="antc_27" name="antc_27" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_27}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-2 col-md-12">
                                <label>Asma:</label>
                                <input type="text" class="form-control" id="antc_28" name="antc_28" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_28}}">
                              </div>
                              <div class="col-lg-2 col-md-12">
                                <label>Otros:</label>
                                <input type="text" class="form-control" id="antc_29" name="antc_29" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->antc_29}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-12 col-md-12">
                                <label>Intervenciones Quirúrgicas:</label>
                                <input type="text" class="form-control" id="antc_30" name="antc_30" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_30}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-12 col-md-12">
                                <label>Traumatismo y Accidentes:</label>
                                <input type="text" class="form-control" id="antc_31" name="antc_31" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_31}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-12 col-md-12">
                                <label>Medicación de uso habitual:</label>
                                <input type="text" class="form-control" id="antc_32" name="antc_32" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_32}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-12 col-md-12">
                                <label>Hospitalizaciones previas:</label>
                                <input type="text" class="form-control" id="antc_33" name="antc_33" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->antc_33}}">
                              </div>
                            </div>
                        </div>
                      </div>
  
                      <div class="panel panel-primary panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title">
                            <b>FAMILIARES</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-lg-12 col-md-12">
                              <textarea class="form-control" name="antecedente_familiar" id="antecedente_familiar" rows="3" readonly></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
  
                    </div>
                    
                  </div>
                </fieldset>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>EXAMEN CLÍNICO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="panel-body" id="VerSignosVitales">
                        <div class="form-group">
                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>SIGNOS VITALES</label>
                          </div>
                        </div>
                        <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales">
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label>TEMPERATURA (°C)</label>
                                <input tabindex="" placeholder="00.00" value="{{$prestacion->temperatura}}" type="text" name="temperatura" id="temperatura" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="5" readonly/>
                              </div>
                              <div class="col-md-2">
                                <label>TALLA (cm)</label>
                                <input tabindex="" value="{{$prestacion->talla_cm}}" type="text" name="talla" id="talla" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>PESO (Kg)</label>
                                <input tabindex="" placeholder="00.00" value="{{$prestacion->peso_kg}}" type="text" name="peso" id="peso" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>IMC</label>
                                <input value="{{$imc}}" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="" >
                              </div>
                              <div class="col-md-2">
                                <label>PESO IDEAL</label>
                                <input value="{{$peso_ideal}}" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="" >
                              </div>
                              <div class="col-md-2">
                                <label>Sa02 (%)</label>
                                <input value="{{$prestacion->sa02_porcentaje}}" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                <label>FREC. CARDIACA FETAL(/min)</label>
                                <input tabindex="" value="{{$prestacion->frecuencia_cardiaca_fetal}}" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>PRESIÓN ARTERIAL</label>
                                <input value="{{$prestacion->presion_arterial_x}} / {{$prestacion->presion_arterial_y}}" tabindex="" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>FREC. RESPIRATORIA (/min)</label>
                                <input value="{{$prestacion->frecuencia_respiratoria}}" type="text" name="frecuencia_respiratoria" tabindex="" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>FREC. CARDIACA (/min)</label>
                                <input value="{{$prestacion->frecuencia_cardiaca}}" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                              <div class="col-md-2">
                                <label>Fi02 (%)</label>
                                <input value="{{$prestacion->fi02}}" type="text" name="oxigenacion" id="oxigenacion" tabindex="" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" readonly>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-lg-12 col-md-12">
                            <label>EXAMEN CLÍNICO GENERAL</label>
                            
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>Ectoscopía:</label>
                                    <textarea class="form-control" name="exam_gen_1" id="exam_gen_1" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_gen_1)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-3 col-md-12">
                                    <label>Tipo morfológico:</label>
                                    <input type="text" class="form-control" id="exam_gen_2" name="exam_gen_2" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_2}}">
                                  </div>
                                  <div class="col-lg-3 col-md-12">
                                    <label>Fascies:</label>
                                    <input type="text" class="form-control" id="exam_gen_3" name="exam_gen_3" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_3}}">
                                  </div>
                                  <div class="col-lg-3 col-md-12" style="display:none">
                                    <label>Talla:</label>
                                    <input type="text" class="form-control" id="exam_gen_4" name="exam_gen_4" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_4}}">
                                  </div>
                                  <div class="col-lg-3 col-md-12" style="display:none">
                                    <label>Peso:</label>
                                    <input type="text" class="form-control" id="exam_gen_5" name="exam_gen_5" maxlength="20" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_5}}">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>Piel y faneras:</label>
                                    <input type="text" class="form-control" id="exam_gen_6" name="exam_gen_6" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_6}}">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>Tejido celular subcutáneo:</label>
                                    <input type="text" class="form-control" id="exam_gen_7" name="exam_gen_7" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_gen_7}}">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>Sistema Linfático:</label>
                                    <textarea class="form-control" name="exam_gen_8" id="exam_gen_8" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_gen_8)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>Sistema Locomotor:</label>
                                    <textarea class="form-control" name="exam_gen_9" id="exam_gen_9" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_gen_9)) !!}</textarea>
                                  </div>
                                </div>
  
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-12 col-md-12">
                            <label>EXAMEN CLÍNICO REGIONAL</label>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-body">
  
                                <label for="">CABEZA Y CUELLO</label>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Cráneo:</label>
                                        <input type="text" class="form-control" id="exam_reg_1" name="exam_reg_1" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_1}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Cabello:</label>
                                        <input type="text" class="form-control" id="exam_reg_2" name="exam_reg_2" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_2}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Ojos:</label>
                                        <input type="text" class="form-control" id="exam_reg_3" name="exam_reg_3" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_3}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Nariz:</label>
                                        <input type="text" class="form-control" id="exam_reg_4" name="exam_reg_4" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_4}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Oídos:</label>
                                        <input type="text" class="form-control" id="exam_reg_5" name="exam_reg_5" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_5}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Boca:</label>
                                        <input type="text" class="form-control" id="exam_reg_6" name="exam_reg_6" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_6}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Cuello:</label>
                                        <input type="text" class="form-control" id="exam_reg_7" name="exam_reg_7" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_7}}">
                                      </div>
                                    </div>
                                  </div>
                                </div>
  
                                <label for="">MAMAS</label>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Derecha:</label>
                                        <input type="text" class="form-control" id="exam_reg_8" name="exam_reg_8" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_8}}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Izquierda:</label>
                                        <input type="text" class="form-control" id="exam_reg_9" name="exam_reg_9" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_9}}">
                                      </div>
                                    </div>
                                  </div>
                                </div>
  
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>TÓRAX Y PULMONES:</label>
                                    <textarea class="form-control" name="exam_reg_10" id="exam_reg_10" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_10)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>CARDIOVASCULAR:</label>
                                    <textarea class="form-control" name="exam_reg_11" id="exam_reg_11" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_11)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>ABDOMEN:</label>
                                    <textarea class="form-control" name="exam_reg_12" id="exam_reg_12" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_12)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>GENITO URINARIO:</label>
                                    <textarea class="form-control" name="exam_reg_13" id="exam_reg_13" rows="3" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_13)) !!}</textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12 col-md-12">
                                    <label>TACTO RECTAL:</label>
                                    <textarea class="form-control" name="exam_reg_14" id="exam_reg_14" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_14)) !!}</textarea>
                                  </div>
                                </div>
  
                                <label for="">NEUROLÓGICO</label>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Estado mental (conciencia-orientación):</label>
                                        <textarea class="form-control" name="exam_reg_15" id="exam_reg_15" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_15)) !!}</textarea>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Funciones superiores (gnosis, praxis, lenguaje y memoria):</label>
                                        <textarea class="form-control" name="exam_reg_16" id="exam_reg_16" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_16)) !!}</textarea>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Función motora (fuerza y tono muscular, movimientos involuntarios, coordinación y reflejos):</label>
                                        <textarea class="form-control" name="exam_reg_17" id="exam_reg_17" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_17)) !!}</textarea>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>Función sensitiva (superficie y profunda):</label>
                                        <textarea class="form-control" name="exam_reg_18" id="exam_reg_18" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_18)) !!}</textarea>
                                      </div>
                                    </div>
  
                                    <!-- <label for="">Pares craneales:</label> -->
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>Pares craneales:</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button tabindex="110" type="button" id="addParesCraneales" class="btn btn-lg btn-toggle"
                                            data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                      </div>
                                      
                                      <div class="panel panel-info panel-bordered panel-sm" style="display: none;" id="fsParesCraneales">
                                        <div class="panel-body">
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>I Olfatorio:</label>
                                              <input type="text" class="form-control" id="exam_reg_19" name="exam_reg_19" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_19}}">
                                              <input type="hidden" id="flag_pares_craneales" name="flag_pares_craneales" value="0">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>II Óptico (agudeza visual, campos visuales, colore, fondo de ojo, presión intraocular):</label>
                                              <textarea class="form-control" name="exam_reg_20" id="exam_reg_20" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_20)) !!}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>III IV VI Oculomotores (movimientos oculares, pupilas):</label>
                                              <textarea class="form-control" name="exam_reg_21" id="exam_reg_21" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_21)) !!}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>V Trigésimo (reflejo corneal, sensibilidad facial, fuerza músculos masticadores):</label>
                                              <textarea class="form-control" name="exam_reg_22" id="exam_reg_22" rows="2" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->exam_reg_22)) !!}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>VII Facial (simetría facial, gusto):</label>
                                              <input type="text" class="form-control" id="exam_reg_23" name="exam_reg_23" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_23}}">
                                            </div>
                                          </div>
  
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>VIII Cocleovesticular (audición, equilibrio):</label>
                                              <input type="text" class="form-control" id="exam_reg_24" name="exam_reg_24" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_24}}">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>IX y X Glosofaríngeo y Neumogástrico (voz, deglución, gusto):</label>
                                              <input type="text" class="form-control" id="exam_reg_25" name="exam_reg_25" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_25}}">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>XI Espinal (esternocleidomastoideo):</label>
                                              <input type="text" class="form-control" id="exam_reg_26" name="exam_reg_26" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_26}}">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                              <label>XII Hipogloso (lengua):</label>
                                              <input type="text" class="form-control" id="exam_reg_27" name="exam_reg_27" maxlength="255" readonly value="{{$obj_hospitalizacion_atencion->exam_reg_27}}">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
  
                                    </div> <!-- Panel switch Pares craneales -->
  
                                  </div>
                                </div>
  
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <div class="col-lg-12 col-md-12">
                          <label>AMPLIACIÓN DEL EXAMEN FÍSICO (si fuese necesario):</label>
                          <textarea class="form-control" name="ampliacion_exam_fisico" id="ampliacion_exam_fisico" rows="4" readonly>{!! nl2br(e($obj_hospitalizacion_atencion->ampliacion_exam_fisico)) !!}</textarea>
                        </div>
                      </div>
  
                    </div>
                  </div>
                </fieldset>
  
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>DIAGNÓSTICOS</b>
                        <!-- boton para habilitar la edicion (al ahacr clic tiene q activar los campos de formulario)
                          generar el evento click en javascript
                        -->
                        <button type="button" class="btn btn-primary" id="btnEditDiagnostico" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button>
  
                      </div>
                    </div>
                    <div class="panel-body">
                      
                      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_tag">
                      <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="10%">Código</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Tipo</th>
                            <th width="10%">Categoria</th>
                          </tr>
                        </thead>
  
                        <tbody>
                          @foreach($diagnostico as $row)
                            @if ($row->id_clasificacion_diagnosticos != 9)
                            <?php
                              $tipoDiagnostico = '';
                              switch($row->id_tipo_diagnostico) {
                                case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                                case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                                case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                              }
                              $categoria = '';
                              if ($row->id_categoria != null) {
                                $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                              }
                            ?>
                            <tr class="row-diagnosticosfinal">
                              <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                              <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                              <td><input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipoDiagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                              <td><input type="text" value="{{ $categoria }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                            </tr>
                            @endif
                          @endforeach
                        </tbody>
                      </table>
                      <div style="margin-top: 10px;">
                      
                    </div>
                  </div>
                </fieldset>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>PROCEDIMIENTOS</b>
                        <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="10%">Código</th>
                            <th width="70%">Nombre</th>
                            <th width="10%">Cantidad</th>
                            <th width="10%">Diagnóstico</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($procedimiento as $row)
                            <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                            <tr class="row2-{{ $row->cod }}">
                              <td><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                              <td>
                                {{$row->nombre}}
                                <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                              <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                              <td>
                                <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly value="{{$row->codigo_diagnostico}}">
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
                  <!-- <div class="form-group">
                    <div class="col-md-12"> -->
  
                <fieldset id="fsAntecedente">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>PLAN DE TRABAJO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <!-- <div class="col-lg-6 col-md-12"> -->
                        <label>EXÁMENES DE AYUDA DIAGNÓSTICA</label>
                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                          <!-- <div class="panel-body"> -->
                            <!-- Laboratorio -->
                            <div class="form-group">
                              <div class="col-md-12">   
                                <fieldset id="fsExamenLab">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>EXAMEN DE LABORATORIO</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                    <?php foreach($laboratorio as $rowLaboratorio):
                                      if ($rowLaboratorio->fecha_firma == "") {
                                        $flag_firmar_lab = 1;
                                      }
                                      $model_prestacion = new \App\Models\Prestaciones;
                                      $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio2($id_prestacion, $rowLaboratorio->id);
                                    ?>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                                        <div class="col-md-3">
                                            <p><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></p>
                                        </div>
                                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                                        <div class="col-md-3">
                                          <p><?php echo $rowLaboratorio->fecha_firma; ?></p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <fieldset>
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-body">
                                              <div class="form-group">
                                                <div class="col-md-12">
                                                  <table class="display" cellspacing="0" width="100%">
                                                    <thead>
                                                      <tr>
                                              <th width="5%">Codigo</th>
                                              <th width="45%">Nombre</th>
                                              <th width="30%">Observacion</th>
                                              <th width="10%">Cantidad</th>
                                              <th width="10%">Diagnosticos</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($proclaboratorio as $row):?>
                                                      <tr>
                                              <td><?php echo $row->codigo?></td>
                                              <td><?php echo $row->nombre?></td>
                                              <td><?php echo $row->observacion?></td>
                                              <td><?php echo $row->cantidad?></td>
                                              <td><?php echo $row->codigo_diagnostico?></td>
                                                      </tr>
                                                      <?php endforeach;?>
                                                    </tbody>
                                                  </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </fieldset>
                                    <?php endforeach;?>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <!-- Fin Laboratorio -->
  
                            <!-- Imagenologia -->
                            <div class="form-group">
                              <div class="col-md-12">
                                <fieldset id="fsExamenRad">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>EXAMEN DE IMAGENOLOGIA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <?php foreach($radiologia as $rowRadiologia):
                                        if ($rowRadiologia->fecha_firma == "") {
                                            $flag_firmar_lab = 1;
                                        }
                                        $model_prestacion = new \App\Models\Prestaciones;
                                        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $rowRadiologia->id,3);
                                      ?>
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                                          <div class="col-md-3">
                                            <p>{{$id_prestacion.' - '. $rowRadiologia->id}}</p>
                                          </div>
                                          <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                                          <div class="col-md-3">
                                            <p><p><?php echo $rowRadiologia->fecha_firma; ?></p></p>
                                          </div>
                                        </div>
                                      </div>
                                      <br />
                                      <div style="clear:both"></div>                    
                                      <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <table class="display" cellspacing="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th width="5%">Codigo</th>
                                                      <th width="45%">Nombre</th>
                                                      <th width="10%">Comparativo</th>
                                                      <th width="10%">Lado</th>
                                                      <th width="15%">Observacion</th>
                                                      <th width="5%">Cantidad</th>
                                                      <th width="10%">Diagnostico</th>
                                                      <th width="5%"></th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                    <?php foreach($procradiologia as $row):?>
                                                    <tr>
                                                      <td>{{$row->codigo}}</td>
                                                      <td>{{$row->nombre}}</td>
                                                      <td><?php if($row->comparativo_radio!=null):
                                                                if($row->comparativo_radio==1):
                                                                  echo "SI";
                                                                else:
                                                                  echo "NO";
                                                                endif;
                                                              endif;
                                                        ?>
                                                      </td>
                                                      <td><?php 
                                                          if($row->lado_radio!=null):
                                                            if($row->lado_radio==1):
                                                              echo "Izquierdo";
                                                            else:
                                                              echo "Derecho";
                                                            endif;
                                                          endif;
                                                          ?>
                                                      </td>
                                                      <td><?php if($row->observacion!='0'):
                                                                  echo $row->observacion;
                                                                endif;
                                                          ?>
                                                      </td>
                                                      <td>{{$row->cantidad}}</td>
                                                      <td>{{$row->cod_diag}}</td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </fieldset>
                                      <?php endforeach;?>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <!-- Fin Imagenologia -->
  
                          <!-- </div> -->
                        </div>
                      <!-- </div> -->
                      <!-- <div class="col-lg-6 col-md-12"> -->
                        <!-- <label>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</label>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-body">
                            <p>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</p>
                          </div>
                        </div> -->
                      <!-- </div> -->
                    <!-- </div> -->
                    <!-- <div class="panel-body"> -->
                      <!-- <div class="col-lg-12 col-md-12"> -->
                        <label>INTERCONSULTAS</label>
                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                          <!-- <div class="panel-body"> -->
                            <!-- Interconsulta -->
                            <div class="form-group">
                              <div class="col-md-12">
                                <fieldset id="fsInterconsulta">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <br />
                                      <div style="clear:both"></div>
                                      <fieldset>
                                        <div class="col-md-12">
                                          <?php foreach($interconsultas as $rowi):?>
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="form-group"> 
                                              <div class="panel-body"> 
                                                <div class="col-md-12">
                                                  <div class="col-md-2"> 
                                                    <label>SERVICIO: </label>
                                                  </div> 
                                                  <div class="col-md-10"> 
                                                    {{$rowi->codigo_interconsulta}} - {{$rowi->servicio}}
                                                  </div>
                                                </div>
                                                <div class="col-md-12">
                                                  <div class="col-md-2"> 
                                                    <label>MOTIVO:</label> 
                                                  </div> 
                                                  <div class="col-md-10"> 
                                                    {{$rowi->motivo_interconsulta}}
                                                  </div>
                                                </div>
                                                <div class="col-md-12">
                                                  <div class="col-md-2">
                                                    <label>MEDICO DE INTERCONSULTA</label><br/>
                                                  </div>
                                                  <div class="col-md-10"> 
                                                    {{$rowi->medico_nombre_tr}} {{$rowi->medico_paterno_tr}} {{$rowi->medico_materno_tr}}
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <?php endforeach;?>
                                        </div>
                                      </fieldset>
                                    </div>
                                  </div>
                                </fieldset>
                                
                              </div>
                            </div>
                            <!-- Fin Interconsulta -->
                          <!-- </div> -->
                        </div>
  
                        <label>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</label>
                        <textarea class="form-control" rows="5" id="procedimiento_medico_quirurgico" name="procedimiento_medico_quirurgico" placeholder="Procedimientos médico – quirúrgicos" readonly>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</textarea>
                      <!-- </div> -->
                    </div>
                  </div>
                </fieldset>
  
                <fieldset id="fsAntecedente">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>TRATAMIENTO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-12">
                          <label>DIETA</label>
                          <textarea class="form-control" tabindex="57" rows="5" id="dieta_terapeutica" name="dieta_terapeutica" placeholder="Dieta." readonly>{{$obj_hospitalizacion_atencion->dieta_terapeutica}}</textarea>
                        </div>
                        <div class="col-md-12">
                          <label>INDICACIONES TERAPÉUTICAS</label>
                          <textarea class="form-control" tabindex="57" rows="5" id="indicacion_terapeutica" name="indicacion_terapeutica" placeholder="Indicaciones terapeutica." readonly>{{$obj_hospitalizacion_atencion->indicacion_terapeutica}}</textarea>
                        </div>
                        <div class="col-md-12">
                          <label>INDICACION COMPLEMENTARIO</label>
                          <textarea class="form-control" tabindex="57" rows="5" id="complementario_terapeutica" name="complementario_terapeutica" placeholder="Cuidados de enfermería y de otros profesionales que sean considerados necesarios." readonly>{{$obj_hospitalizacion_atencion->complementario_terapeutica}}</textarea>
                        </div>
                        
                        <div class="col-md-12">
                          <br>
                          <label for="">PRESCRIBIR RECETA</label>
                          <!-- Receta Vale -->
                          <fieldset id="fsRecetaVale">
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>PRODUCTOS MEDICOS</b>
                                </div>
                              </div>
                              <div class="panel-body panel-body-receta">
                                <?php foreach($receta as $rowReceta):
                                if ($rowReceta->fecha_firma == "") {
                                    $flag_firmar = 1;
                                  }
                                  $model_productos_receta = new \App\Models\Producto_receta;
                                  $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                                ?>
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                                          <div class="col-md-1">
                                            <p>{{$rowReceta->nro_receta}}</p>
                                          </div>
                                          <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                          <div class="col-md-2">
                                            <p><?php date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                          </div>
                                          <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                          <div class="col-md-4" style="padding:0px">
                                            <p>{{$rowReceta->farmacia}}</p>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="table-responsive">
                                      <table class="table table-striped display" cellspacing="0" width="100%">
                                        <thead>
                                          <tr>
                                            <th width="10%">Codigo</th>
                                            <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                            <th width="10%">Unidad Medida</th>
                                            <th width="7%">Tiempo (Días)</th>
                                            <th width="15%">Posologia</th>
                                            <th width="10%">Via Administración</th>
                                            <th width="8%">Cantidad Prescrita</th>
                                            <th width="10%">Diagnóstico</th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <?php foreach($medicamentos as $row):?>
                                        <tr>
                                          <td>{{$row->codigo}}</td>
                                          <td>{{$row->nombre}}
                                              <?php 
                                                if($row->codigo=='59075700'):
                                                  echo "(".$row->cantidad_prescrita."L)";
                                                endif; 
                                              ?>
                                          </td>
                                          <td>{{$row->abreviatura}}</td>
                                          <td>{{$row->tiempo_periodo}}</td>
                                          <td>{{$row->posologia}}</td>
                                          <td>{{$row->via}}</td>
                                          <td><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                            echo $row->cantidad_prescrita/1000;
                                            else:
                                              echo $row->cantidad_prescrita;
                                            endif; ?>
                                          </td>
                                          <td><?php
                                          $model_productos_receta = new \App\Models\Producto_receta;
                                                $diagnostico_id = $row->id_diagnosticos;
                                                $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                echo $diagnostico_nombre; ?>    
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tbody>
                                        </tbody>
                                      </table>
                                      </div>
                                    </div>
                                  </div>
                                  <?php endforeach;?>
                                </fieldset>
                              </div>
                            </div>
                          </fieldset>
                          <!-- Fin Receta Vale -->
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
  
                <!-- <fieldset id="fsAltaServicio">
                  <div class="panel panel-success panel-bordered panel-sm">
                    <div class="panel-heading">
                    <div class="panel-title text-center"><b>ALTA DE SERVICIO</b></div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-2">
                          <label for="">FECHA DE ALTA</label>
                          <input type="text" data-date-end-date="0d" class="form-control datepicker" value="" name="fecha_egreso" id="fecha_egreso"/>
                        </div>
  
                        <div class="col-md-2">
                          <label for="">HORA DE ALTA</label>
                          <input type="time" name="hora_egreso" id="hora_egreso" value="" class="form-control">
                        </div>
  
                        <div class="col-md-2">
                          <label for="">SERVICIO</label>
                          <select id="id_area_destino" name="id_area_destino"class="form-control" onchange="getCamas();" style="width:100%">
                            <option value="0">-Seleccione-</option>
                            
                          </select>
                        </div>
  
                        <div class="col-md-2">
                          <label for="">CAMA/CAMILLA</label>
                          <select id ="id_cama_destino" name="id_cama_destino" class="form-control" style="width:100%">
                            <option value="0">-Seleccione-</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8">
                          <label for="">OBSERVACION</label>
                          <textarea rows="2" class="form-control" id="observacion_alta_servicio" name="observacion_alta_servicio"></textarea>
                        </div>
                      </div>
                      <hr>
                      <label for=""><b>DIAGNOSTICOS</b></label>
                      <p>Los Diagnósticos de Alta de Servicio se tomarán de los Diagnósticos ya ingresados.</p>
                    </div>
                  </div>
                </fieldset> -->
             
  
              <!-- </div> -->
  
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>