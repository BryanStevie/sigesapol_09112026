@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

@endsection
@section('content')

<div class="container" style="width:100%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel-body">
          <div class="panel panel-info panel-bordered panel-sm panel-body">
            <div class="col-sm-12 col-md-12">
              <fieldset>
                <div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="fecha_atencion">COD. SITEDS SUSALUD</label>
                      <p>{{strtoupper($evaluacion->cod_siteds_susalud)}}</p>
                    </div>  
                  </div>
                  <div class="form-group">
                    <div class="col-md-2">
                      <label for="fecha_atencion">FECHA DE ATENCION</label>
                      <p><?php echo date('d-m-Y', strtotime($evaluacion->fecha_atencion)) ?></p>
                    </div>
                    <div class="col-md-2">
                      <label for="fecha_atencion">HORA DE ATENCION</label>
                      <p><?php echo date('H:i:s', strtotime($evaluacion->fecha_atencion)) ?></p>
                    </div>
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p><?php echo $paciente->nro_historia?></p>
                    </div>
                    
                    <div class="col-md-3">
                      <label>AREA</label>
                      <p>EVOLUCION</p>
                    </div>  
                  </div>
                  <div class="form-group">
                    <div class="col-md-2">
                      <label for="tipo">PABELLON</label><br/>
                      <?php echo $datoadicional->pabellon?>
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">PISO</label><br/>
                      <?php echo $datoadicional->piso?>
                    </div>
                    <div class="col-md-2">
                      <label>CUARTO</label><br/>
                      <?php echo $datoadicional->cuarto?>
                    </div>
                    <div class="col-md-3">
                      <label>CAMA/CAMILLA</label><br/>
                      <?php echo $datoadicional->cama?>
                    </div>
                  </div>
                </div>
              </fieldset>
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
                        <p><?php echo $prestacion->tipo_doc_ident?>: <?php echo $prestacion->nro_doc_ident?></p>
                    </div>
                    <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                    </div>
                    <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                    </div>
                    <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                    </div>
                    </div>
                    @if($prestacion->parentesco=='TITULAR')
                    <div class="form-group">
                    <div class="col-md-2">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                    </div>
                    <div class="col-md-2">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                    </div>
                    <div class="col-md-2">
                        <label for="cip">CIP</label>
                        <p><?php echo $prestacion->cip?></p>
                    </div>
                    <div class="col-md-2">
                        <label for="grado">GRADO</label>
                        <p><?php echo $prestacion->grado?></p>
                    </div>
                    <div class="col-md-2">
                        <label for="nomsexo">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                    </div>
                    <div class="col-md-2">
                        <label for="edadpaciente">EDAD</label>
                        <p><?php echo $prestacion->edadpaciente?></p>
                    </div>
                    </div>
                    @else
                    <div class="form-group">
                    <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                        <p> <?php echo $prestacion->fecha_nac?></p>
                    </div>
                    <div class="col-md-3">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                    </div>
                    <div class="col-md-3">
                        <label for="nomsexo">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                    </div>
                    <div class="col-md-3">
                        <label for="edadpaciente">EDAD</label>
                        <p><?php echo $prestacion->edadpaciente?></p>
                    </div>
                    
                    </div>
                    @endif
                </div>
                </div>
            </fieldset>
                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                        <div class="panel-title text-center">
                            <b>INFORMACIÓN DEL RESPONSABLE DE LA EVOLUCIÓN</b>
                        </div>
                        </div>
                        <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                            <p>DNI: <?php echo $evaluacion->dni?></p>
                            </div>
                            <div class="col-md-3">
                            <label>APELLIDO PATERNO</label>
                            <p><?php echo $evaluacion->medico_paterno?></p>
                            </div>
                            <div class="col-md-3">
                            <label>APELLIDO MATERNO</label>
                            <p><?php echo $evaluacion->medico_materno?></p>
                            </div>
                            <div class="col-md-3">
                            <label>NOMBRES</label>
                            <p><?php echo $evaluacion->medico_nombre?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                            <label>ESPECIALIDAD</label>
                            <p><?php echo $evaluacion->especializacion?></p>
                            </div>
                            <div class="col-md-3">
                            <label>PROFESION</label>
                            <p><?php echo $evaluacion->profesion?></p>
                            </div>
                            <div class="col-md-3">
                            <label>N° COLEGIATURA</label>
                            <p><?php echo $evaluacion->colegiatura?></p>
                            </div>
                            <div class="col-md-3">
                            <label>N° RNE</label>
                            <p><?php echo $evaluacion->rne?></p>
                            </div>
                        </div>
                        </div>
                    </div>
                </fieldset>
              <fieldset id="fsAnamnesis">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                          <b>EVOLUCIÓN</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div style="display:none">
                      <label>SIGNOS VITALES</label>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-2">
                              <label>TEMPERATURA (°C) &nbsp;</label>
                              <p><?php echo $evaluacion->temperatura?></p>
                            </div>
                            <div class="col-md-2">
                              <label>TALLA (cm) &nbsp;</label>
                              <p><?php echo $evaluacion->talla_cm?></p>
                            </div>
                            <div class="col-md-2">
                              <label>PESO (Kg) &nbsp;</label>
                              <p><?php echo $evaluacion->peso_kg?></p>
                            </div>
                            <div class="col-md-2">
                              <label>IMC &nbsp;</label>
                              <p>{{$imc}}</p>
                            </div>
                            <div class="col-md-2">
                              <label>PESO IDEAL &nbsp;</label>
                              <p>{{$peso_ideal}}</p>
                            </div>
                            <div class="col-md-2">
                              <label>Sa02 (%) &nbsp;</label>
                              <p><?php echo $evaluacion->sa02_porcentaje?></p>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                              <label>TAMAÑO DE CINTURA (cm)</label>
                              <p><?php echo $evaluacion->tamano_cintura_cm?></p>
                            </div>
                            <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                              <label>FREC. CARDIACA FETAL (/min)</label>
                              <p><?php echo $evaluacion->frecuencia_cardiaca_fetal?></p>
                            </div>
                            <div class="col-md-2">
                              <label>PRESIÓN ARTERIAL &nbsp;</label>
                              <!-- <div class="row">
                                <div class="col-xs-5">                           -->
                                  <p><?php echo $evaluacion->presion_arterial_x?> / <?php echo $evaluacion->presion_arterial_y?></p>
                                <!-- </div>  
                              </div> -->
                            </div>
                            <div class="col-md-2">
                              <label>FREC. RESPIRATORIA (/min)</label>
                              <p><?php echo $evaluacion->frecuencia_respiratoria?></p>
                            </div>
                            <div class="col-md-2">
                              <label>FREC. CARDIACA (/min) &nbsp;</label>
                              <p><?php echo $evaluacion->frecuencia_cardiaca?></p>
                            </div>
                            <div class="col-md-2">
                              <label>Fi02 (%) &nbsp;</label>
                              <p><?php echo $evaluacion->fi02?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6 col-md-12">
                          <label>APRECIACIÓN SUBJETIVA</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <p>{!! nl2br(e($evaluacion->apreciacion_subjetiva)) !!}</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                          <label>APRECIACIÓN OBJETIVA</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <p>{!! nl2br(e($evaluacion->apreciacion_objetiva)) !!}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6 col-md-12">
                          <label>VERIFICACIÓN DEL TRATAMIENTO Y DIETA</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <p>{!! nl2br(e($evaluacion->verificacion_tratamiento)) !!}</p>
                            </div>
                          </div>
                        </div>
                       <div class="col-lg-6 col-md-12">
                          <label>INTERPRETACIÓN DE EXÁMENES Y COMENTARIO</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <p>{!! nl2br(e($evaluacion->interpretacion_examenes)) !!}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12 col-md-12"> 
                          <label>TERAPÉUTICA Y PLAN DE TRABAJO</label>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-body">
                              <p>{!! nl2br(e($evaluacion->plan_trabajo)) !!}</p>
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
                    <div class="panel-title text-center" style="position: relative">
                      <b>DIAGNÓSTICOS ATENCIÓN</b>
                      <!--button type="button" class="btn btn-primary" id="btnEditDiagnostico" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button-->
                    </div>
                  </div>
                  @if(count($diagnostico_atencion)>0)
                  <div class="panel-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_tag">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="50%">Nombre</th>
                          <th width="20%">Tipo</th>
                          <th width="10%">Categoría</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diagnostico_atencion as $row)
                          @if ($row->id_clasificacion_diagnosticos != 9)
                          <?php
                            $tipoDiagnostico = '';
                            switch($row->id_tipo_diagnostico) {
                              case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                              case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                              case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                              default:
                              // Contenido para el caso por defecto
                            }
                            $categoria = '';
                            if ($row->id_categoria != null) {
                              $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                            }
                            ?>
                          <tr>
                            <td>{{ $row->codigo }}</td>
                            <td>{{$row->nombre}}</td>
                            <td>{{ $tipoDiagnostico }}</td>
                            <td>{{ $categoria }}</td>
                          </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
               </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                      <b>DIAGNÓSTICOS EVOLUCIÓN</b>
                    </div>
                  </div>
                  @if(count($diagnostico_evaluacion)>0)
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="20%">Codigo</th>
                          <th width="50%">Nombre</th>
                          <th width="20%">Tipo</th>
                          <th width="10%">Categoría</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diagnostico_evaluacion as $rowi)
                          @if ($rowi->id_clasificacion_diagnosticos != 9)
                          <?php
                            $tipoDiagnostico = '';
                            switch($rowi->id_tipo_diagnostico) {
                              case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                              case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                              case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                              default:
                              // Contenido para el caso por defecto
                            }
                            $categoria = '';
                            if ($rowi->id_categoria != null) {
                              $categoria = ($rowi->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                            }
                            ?>
                          <tr>
                            <td>{{ $rowi->codigo }}</td>
                            <td>{{$rowi->nombre}}</td>
                            <td>{{ $tipoDiagnostico }}</td>
                            <td>{{ $categoria }}</td>
                          </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
               </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm" @if(empty($gestacion) && empty($puerperio) && empty($natis) && !isset($evaluacion->id_puerperio) && empty($natisEvaluacion)) style="display: none;" @endif>
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <b>SALUD MATERNA</b>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if (!empty($gestacion))
                        <div class="form-group">
                            <div class="panel-title text-center">
                                <b>GESTANTES</b>
                            </div>
                            <div class="col-md-3">
                            
                                <label for="tipo">Última menstruación</label>
                                <p>{{ $gestacion['fech_ult_regla'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha probable de parto</label>
                                <p>{{ $gestacion['fech_prob_parto'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>Semanas de gestación</label>
                                <p>{{ $gestacion['semanas_gestacion'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>N° de controles prenatales:</label>
                                <p>{{ $gestacion['num_controles'] }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                <label>DUDA</label>
                                <p>{{ $gestacion['duda'] ? 'Sí' : 'No' }}</p>
                            </div>
                        </div>
                        @endif

                        @if (!empty($puerperio) || isset($evaluacion->id_puerperio))
                        <div class="panel-title text-center">
                            <b>PUERPERIAS</b>
                        </div>
                        @endif
                        @if (!empty($puerperio))
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>FECHA PARTO</label>
                                <p>{{ $puerperio['fecha_parto'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>TIPO DE PARTO</label>
                                <p>
                                    @if ($puerperio['id_tipo_parto'] == 1)
                                        Vaginal
                                    @elseif ($puerperio['id_tipo_parto'] == 2)
                                        Cesárea
                                    @else
                                        Otro
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                            <label>COMPLICACION</label>
                            <p>{{ $puerperio['complicaciones'] }}</p>
                            </div>
                        </div>
                        @endif

                        @if (isset($evaluacion->id_puerperio))
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>FECHA Y HORA DE PARTO</label>
                                <p>{{ \Carbon\Carbon::parse($evaluacion->puerperio_fecha_parto)->format('d-m-Y H:i') }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>TIPO DE PARTO</label>
                                <p>{{$evaluacion->puerperio_tipo_parto}}</p>
                            </div>
                            <div class="col-md-3">
                            <label>COMPLICACION</label>
                            <p>{{ $evaluacion->puerperio_complicaciones }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="panel-title text-center">
                        <b>NEONATOS</b>
                        </div>
                        @if (!empty($natis))
                        @foreach ($natis as $neonato)
                            <div class="form-group">
                            <div class="col-md-3">
                                <label>ESTADO DE VIDA</label>
                                <p>{{ $neonato['estado'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>NUM. DOCUMENTOS</label>
                                <p>{{ $neonato['num_doc'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <label>SEXO</label>
                                <p>
                                    @if ($neonato['sexo'] == 'M')
                                        MASCULINO
                                    @elseif ($neonato['sexo'] == 'F')
                                        FEMENINO
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-1">
                                <label>TALLA(cm)</label>
                                <p>{{ $neonato['talla'] }}</p>
                            </div>
                            <div class="col-md-1">
                                <label>PESO(gr)</label>
                                <p>{{ $neonato['peso'] }}</p>
                            </div>
                            </div>
                        @endforeach
                        @endif

                        @if (!empty($natisEvaluacion))
                            @foreach ($natisEvaluacion as $neonatoEvaluacion)
                                <div class="form-group">
                                <div class="col-md-3">
                                    <label>ESTADO DE VIDA</label>
                                    <p>{{ $neonatoEvaluacion->estado }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label>NUM. DOCUMENTOS</label>
                                    <p>{{ $neonatoEvaluacion->num_doc }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label>SEXO</label>
                                    <p>{{$neonatoEvaluacion->sexo == 'M' ? "MASCULINO" : "FEMENINO"}}</p>
                                </div>
                                <div class="col-md-1">
                                    <label>TALLA(cm)</label>
                                    <p>{{ $neonatoEvaluacion->talla }}</p>
                                </div>
                                <div class="col-md-1">
                                    <label>PESO(gr)</label>
                                    <p>{{ $neonatoEvaluacion->peso }}</p>
                                </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                    <b>PROCEDIMIENTOS ATENCIÓN</b>
                    <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                    </div>
                </div>
                @if(count($procedimiento_atencion)>0)
                <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                        <tr>
                        <th width="20%">codigo</th>
                        <th width="60%">Nombre</th>
                        <th width="20%">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    @foreach($procedimiento_atencion as $row)
                        <tr>
                        <td>{{ $row->codigo }}</td>
                        <td>{{$row->nombre}}</td>
                        <td>{{ $row->cantidad }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
                @endif
                </div>
            </fieldset>
              
            <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center" style="position: relative">
                    <b>PROCEDIMIENTOS EVOLUCIÓN</b>
                    <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                    </div>
                </div>
                @if(count($procedimiento_evaluacion)>0)
                <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                        <tr>
                        <th width="20%">Código</th>
                        <th width="55%">Nombre</th>
                        <th width="20%">Cantidad</th>
                        <th width="5%">Diagnóstico</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    @foreach($procedimiento_evaluacion as $rowx)
                        <input type="hidden" value="{{ $rowx->id_prestacion_procedimiento }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                        <tr>
                        <td>{{ $rowx->codigo_procedimiento }}</td>
                        <td>{{ $rowx->descripcion_procedimiento }}</td>
                        <td>{{ $rowx->cantidad }}</td>
                        <td>{{ $rowx->codigo_diagnostico }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    
                    </table>
                </div>
                @endif
                <br/><br/>
                </div>
            </fieldset>
            
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
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12">
                            <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                            <div class="col-md-1">
                                <p><?php echo $rowReceta->nro_receta; ?></p>
                            </div>
                            <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                            <div class="col-md-2">
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                            </div>
                            <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                            <div class="col-md-4" style="padding:0px">
                                <p><?php echo $rowReceta->farmacia; ?></p>
                            </div>
                            </div>
                        </div>
                        <table class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="15%">Codigo</th>
                                <th width="30%">Producto farmacéutico o dispositivo médico</th>
                                <th width="15%">Unidad Medida</th>
                                <th width="10%">Tiempo(Dias)</th>
                                <th width="15%">Posologia</th>
                                <th width="10%">Via Administración</th>
                                <th width="15%">Cantidad Prescrita</th>
                                <th width="15%">Diagnosticos</th>
                            </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row):?>
                            <tr>
                            <td><?php echo $row->codigo?></td>
                            <td><?php echo $row->nombre?>
                                <?php 
                                if($row->codigo=='59075700'):
                                    echo "(".$row->cantidad_prescrita."L)";
                                endif; 
                                ?>
                            </td>
                            <td><?php echo $row->abreviatura?></td>
                            <td><?php echo $row->tiempo_periodo?></td>
                            <td><?php echo $row->posologia?></td>
                            <td><?php echo $row->via?></td>
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
                    </fieldset>
                    <?php endforeach;?>
                    <div style="clear:both"></div>
                </div>
                </div>
            </fieldset>
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
                                $proclaboratorio = $model_prestacion->GetPrestacionLaboratorioEvaluacion2($prestacion->id, $rowLaboratorio->id, $id_evaluacion);
                              ?>
                              <br/>
                              <div style="clear:both"></div>
                              <div class="form-group">
                                <div class="col-md-12">
                                  <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                                  <div class="col-md-3">
                                      <p><?php echo $prestacion->id.' - '. $rowLaboratorio->id; ?></p>
                                  </div>
                                  <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                                  <div class="col-md-3">
                                    <p><?php echo date("d-m-Y", strtotime($rowLaboratorio->created_at)); ?></p>
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
                              <div style="clear:both"></div>
                            </div>
                          </fieldset>
                        </div>
                      </div>
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
                            @if ( count($ordenImagenesAnulada) > 0 )
                              @foreach ($ordenes as $orden )
                                <div class="alert alert-danger" role="alert">
                                  <div class="col-lg-12">
                                    Examenes Invalidados por el servicio de imagenes
                                  </div>
                                  <div class="col-lg-6">
                                    <label class="control-label">Tecnologo o Profesional de Imagenes: </label>
                                    <span>{{ $orden->usuario }}</span>
                                  </div>
                                  <div class="col-lg-3">
                                      <label class="control-label">Orden: </label>
                                      <span>{{ $orden->id_radiologia }}</span>
                                  </div>
                                  <div class="col-lg-3">
                                      <label class="control-label">Fecha de Anulación: </label>
                                      <span>{{ $orden->fecha_orden_invalidado }}</span>
                                  </div>
                                  <div class="col-lg-12">
                                    <label class="control-label">Motivo: </label>
                                    <span>{{ $orden->obs_examen_invalidado }}</span>
                                  </div>
                                  <table  class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                          <tr>
                                              <th>N°</th>
                                              <th class="col-lg-2">CÓDIGO</th>
                                              <th class="col-lg-8">DESCRIPCIÓN</th>
                                              <th class="col-lg-2">DIAGNOSTICO</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php 
                                              $i = 1;
                                              foreach( $orden->cpms as $row):
                                          ?>
                                          <tr>
                                              <td>{{$i}}</td>
                                              <td class="col-lg-2">{{$row->codigo_rad}}</td>
                                              <td class="col-lg-8">{{$row->descripcion_corta}}</td>
                                              <td class="col-lg-2">{{$row->diagnostico}}</td>
                                          </tr>
                                          <?php 
                                              $i++;
                                              endforeach;
                                          ?>
                                      </tbody>
                                  </table>
                                </div>  
                              @endforeach
                            @endif

                            <?php foreach($radiologia as $rowRadiologia):

                                if ($rowRadiologia->fecha_firma == "") {
                                    $flag_firmar_lab = 1;
                                }

                              $model_prestacion = new \App\Models\Prestaciones;
                              $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorioEva($prestacion->id, $rowRadiologia->id,3,$id_evaluacion);
                            ?>

                            <br />
                            <div style="clear:both"></div>
                            <div class="form-group">
                              <div class="col-md-12">
                                <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                                <div class="col-md-3">
                                  <p><?php echo $prestacion->id.' - '. $rowRadiologia->id; ?></p>
                                </div>
                                <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                                <div class="col-md-3">
                                  <p><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
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
                                    <th width="40%">Nombre</th>
                                    <th width="10%">Comparativo</th>
                                    <th width="10%">Lado</th>
                                    <th width="15%">Observacion</th>
                                    <th width="5%">Cantidad</th>
                                    <th width="10%">Diagnostico</th>
                                    <th width="10%">Radiología Portátil</th>
                                          </tr>
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
                                        <td><?php echo $row->radiologia_portatil?></td>
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
                            <div style="clear:both"></div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
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
                                          <?php echo $rowi->codigo_interconsulta ?> - <?php echo $rowi->servicio ?>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-2"> 
                                          <label>MOTIVO:</label> 
                                        </div> 
                                        <div class="col-md-10"> 
                                          <?php echo $rowi->motivo_interconsulta ?>
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
                <div class="form-group">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center" style="position: relative">
                                <b>TRASLADO</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                @if (isset($traslado))
                                <div class="form-group" style="padding: 0px 5px">
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                                    <label for="tipo">UPSS A CARGO ORIGEN</label>
                                    <p>{{$traslado->upss_encargado_origen_codigo ? $traslado->upss_encargado_origen_codigo . "-" . $traslado->upss_encargado_origen_descripcion : ""}}</p>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                                    <label for="tipo">SERVICIO A CARGO ORIGEN</label>
                                    <p>{{$traslado->servicio_encargado_origen_nombre ? $traslado->servicio_encargado_origen_nombre : ""}}</p>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                                    <label for="tipo">SERVICIO FÍSICO ORIGEN</label>
                                    <p>{{$traslado->servicio_origen_nombre ? $traslado->servicio_origen_nombre : ""}}</p>
                                    </div>
                                </div>
                                <div class="form-group" style="padding: 0px 5px">
                                    {{--<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <label for="tipo">SERVICIO FÍSICO</label>
                                    <p>{{$traslado->servicio_origen_nombre ? $traslado->servicio_origen_nombre : ""}}</p>
                                    </div>--}}
                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">                            
                                    <label>TIPO DE TRASLADO/TRANSFERENCIA</label>
                                    <p>{{$traslado->tipo_traslado}}</p>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                                    <label for="tipo">UPSS A CARGO DESTINO</label>
                                    <p>{{$traslado->upss_encargado_destino_codigo ? $traslado->upss_encargado_destino_codigo . "-" . $traslado->upss_encargado_destino_descripcion : ""}}</p>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                                    <label for="tipo">SERVICIO A CARGO DESTINO</label>
                                    <p>{{$traslado->servicio_encargado_destino_nombre ? $traslado->servicio_encargado_destino_nombre : ""}}</p>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">                          
                                    <label>SERVICIO FÍSICO DESTINO</label>
                                    <p>{{$traslado->servicio_destino_nombre ? $traslado->servicio_destino_nombre : ""}}</p>
                                    </div>
                                    @if ($traslado->cama_destino_nombre)
                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">                            
                                        <label>CAMA DESTINO</label>
                                        <p>{{$traslado->cama_destino_nombre}}</p>
                                    </div>
                                    @endif
                                    @if ($traslado->fecha_hora_inicio)
                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">                            
                                        <label>FECHA Y HORA TRASLADO</label>
                                        <p>{{ date('d-m-Y H:i', strtotime($traslado->fecha_hora_inicio)) }}</p>
                                    </div>
                                    @endif
                                </div>
                                <br>
                                @if ( isset($diagnosticos_traslado) && !empty($diagnosticos_traslado) && is_array($diagnosticos_traslado) && count($diagnosticos_traslado) > 0)
                                <label>DIAGNOSTICOS DEL TRASLADO</label>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <table class="table table-striped" style="margin: 0px -8px; font-size: 11px; width: 100%" id="tableDiagTraslado">
                                        <thead>
                                            <tr>
                                            <th width="10%">CODIGO</th>
                                            <th width="70%">DIAGNÓSTICO</th>
                                            <th width="20%">TIPO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($diagnosticos_traslado as $diagnostico_traslado)
                                            <tr>
                                                <td>{{$diagnostico_traslado->codigo}}</td>
                                                <td>{{$diagnostico_traslado->nombre}}</td>
                                                <td>{{$diagnostico_traslado->tipo_diagnostico_descripcion}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    </div>
                                @endif
                                <div style="display: flex; flex-direction: row-reverse">
                                    <button type="button" class="btn btn-success btn-xs" onclick="firmarTraslado()"><i class="fa fa-pencil" id="btnFirmarTraslado"></i> FIRMAR TRASLADO</button>  
                                </div>
                                @endif
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
