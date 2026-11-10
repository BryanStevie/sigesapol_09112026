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
                  <div class="form-group">
                    <div class="col-md-3">
                      <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                      <p>{{$prestacion->tipo_atencion}}</p>
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">FECHA DE ATENCION</label>
                       <p>{{$prestacion->fecha_atencion}}</p>
                    </div>
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p>{{$prestacion->nro_historia}}</p>
                    </div>
                    <div class="col-md-3">
                      <label for="dni_beneficiario">SERVICIO</label>
                      <p>{{$prestacion->servicio}}</p>
                    </div>
                    <div class="col-md-1">
                      <label>CONSULTORIO</label>
                      <p>{{$prestacion->consultorio}}</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-3">
                      <label for="tipo">NOMBRE DE LA IPRESS</label>
                      <p>{{$prestacion->establecimiento}}</p>
                    </div>
                    @if($sw == 1)
                      <div class="col-md-2">
                        <label for="tipo">PABELLON</label><br/>
                        {{$datoadicional->pabellon}}
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">PISO</label><br/>
                        {{$datoadicional->piso}}
                      </div>
                      <div class="col-md-3">
                        <label>CUARTO</label><br/>
                        {{$datoadicional->cuarto}}
                      </div>
                      <div class="col-md-1">
                        <label>CAMA</label><br/>
                        {{$datoadicional->cama}}
                      </div>
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
                        <label for="tipo">TIPO DE DOCUMENTO</label>
                        <p>DNI</p>
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                        <p>{{$prestacion->dni}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>N° COLEGIATURA</label>
                        <p>{{$prestacion->colegiatura}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>N° RNE</label>
                        <p>{{$prestacion->rne}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p>{{$prestacion->profesion}}</p>
                      </div>
                    </div>
                    <div class="form-group">
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
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p>{{$prestacion->especializacion}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DATOS TRIAJE</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-2">
                        <label>TEMPERATURA (°C) &nbsp;</label>
                        <p>{{$dato_triaje->temperatura}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>TALLA (cm) &nbsp;</label>
                        <p>{{$dato_triaje->talla_cm}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>PESO (Kg) &nbsp;</label>
                        <p>{{$dato_triaje->peso_kg}}</p>
                          
                      </div>
                      <div class="col-md-2">
                        <label>IMC &nbsp;</label>
                        <p>
                        <?php 
                          if ($dato_triaje->peso_kg != "" && $dato_triaje->talla_cm != "") { 
                            if ($dato_triaje->peso_kg > 0 && $dato_triaje->talla_cm > 0) {
                              $talla_en_metros =  $dato_triaje->talla_cm*0.01;     
                              $imc=   $dato_triaje->peso_kg / ($talla_en_metros * $talla_en_metros);
                              $imc=   number_format($imc, 2, '.', '');
                            }
                          } else {
                            $imc = '';
                          }
                        ?>
                       </p>
                      </div>
                      <div class="col-md-2">
                        <label>PESO IDEAL &nbsp;</label>
                        <p><?php 
                              if ($dato_triaje->talla_cm != "") {  
                                if ($dato_triaje->talla_cm > 0) {  
                                  $pesoIdeal=50+(($dato_triaje->talla_cm-150)/4)*3+($prestacion->edadpaciente-20)/4;
                                  if($prestacion->sexo=="M") {
                                        echo $pesoIdeal=$pesoIdeal*0.9;
                                  }
                                }
                                else{
                                  echo $pesoIdeal = "";
                                }
                              }else{
                                echo $pesoIdeal = "";
                              }

                          ?></p>
                      </div>
                      <div class="col-md-2">
                        <label>Sa02 (%) &nbsp;</label>
                        <p>{{$dato_triaje->sa02_porcentaje}}</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label>TAMAÑO DE CINTURA (cm)</label>
                        <p>{{$dato_triaje->tamano_cintura_cm}}</p>
                      </div>
                      <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                        <label>FREC. CARDIACA FETAL(/min)</label>
                        <p>{{$dato_triaje->frecuencia_cardiaca_fetal}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>PRESIÓN ARTERIAL &nbsp;</label>
                        <div class="row">
                          <div class="col-xs-5">                          
                            <p>{{$dato_triaje->presion_arterial_x}}</p>
                          </div>  
                            <div class="col-xs-2">
                               <label>/</label> 
                            </div>
                          <div class="col-xs-5">
                            <p>{{$dato_triaje->presion_arterial_y}}</p>
                          </div>  
                        </div>
                    </div>
                      <div class="col-md-4">
                        <label>FREC.RESPIRATORIA(/min)</label>
                        <p>{{$dato_triaje->frecuencia_respiratoria}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>FREC. CARDIACA (/min) &nbsp;</label>
                        <p>{{$dato_triaje->frecuencia_cardiaca}}</p>
                      </div>
                      <div class="col-md-2">
                        <label>Fi02 (%) &nbsp;</label>
                        <p>{{$dato_triaje->fi02}}</p>
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
                    <div class="col-md-12">
                      <label>ATECEDENTE</label>
                      <p>{{$prestacion->antecedente}}</p>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>ANTECEDENTE FAMILIAR</label>
                        <p>{{$prestacion->antecedente_familiar}}</p>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ANAMNESIS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>MOTIVO DE VISITA</label>
                      <p>{{$prestacion->motivo_visita}}</p>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>TIEMPO DE ENFERMEDAD/RELATO CRONOLOGICO</label>
                        <p>{{$prestacion->tiempo_enfermedad}}</p>
                        <p>{{$prestacion->anamnesis}}</p>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <label>EXAMENES AUXILIARES</label>
                      <p>{{$prestacion->examen_auxiliar}}</p>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN FISICO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if($prestacion->temperatura>0)
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>INFORMACIÓN DE SIGNOS VITALES</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-2">
                            <label>TEMPERATURA (°C) &nbsp;</label>
                            <p>{{$prestacion->temperatura}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>TALLA (cm) &nbsp;</label>
                            <p>{{$prestacion->talla_cm}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>PESO (Kg) &nbsp;</label>
                            <p>{{$prestacion->peso_kg}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>IMC &nbsp;</label>
                            <p>
                             <?php 
                                if ($prestacion->peso_kg != "" && $prestacion->talla_cm != "") { 
                                  if ($prestacion->peso_kg > 0 && $prestacion->talla_cm > 0) {
                                    $talla_en_metros =  $prestacion->talla_cm*0.01;     
                                    $imc=   $prestacion->peso_kg / ($talla_en_metros * $talla_en_metros);
                                    $imc=   number_format($imc, 2, '.', '');
                                  }
                                } else {
                                  $imc = '';
                                }
                              ?>
                           </p>
                          </div>
                          <div class="col-md-2">
                            <label>PESO IDEAL &nbsp;</label>
                            <p><?php 
                                if ($prestacion->talla_cm != "") {  
                                  if ($prestacion->talla_cm > 0) {  
                                    $pesoIdeal=50+(($prestacion->talla_cm-150)/4)*3+($prestacion->edadpaciente-20)/4;
                                    if($prestacion->sexo=="M") {
                                          echo $pesoIdeal=$pesoIdeal*0.9;
                                    }
                                  }
                                  else{
                                    echo $pesoIdeal = "";
                                  }
                                }else{
                                  echo $pesoIdeal = "";
                                }

                            ?></p>
                          </div>
                          <div class="col-md-2">
                            <label>Sa02 (%) &nbsp;</label>
                            <p>{{$prestacion->sa02_porcentaje}}</p>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-2">
                            <label>TAMAÑO DE CINTURA (cm)</label>
                            <p>{{$prestacion->tamano_cintura_cm}}</p>
                          </div>
                          <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                            <label>FREC. CARDIACA FETAL(/min)</label>
                            <p>{{$prestacion->frecuencia_cardiaca_fetal}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>PRESIÓN ARTERIAL &nbsp;</label>
                            <div class="row">
                              <div class="col-xs-5">                          
                                <p>{{$prestacion->presion_arterial_x}}</p>
                              </div>  
                              <div class="col-xs-2">
                                   <label>/</label> 
                              </div>
                              <div class="col-xs-5">
                                <p>{{$prestacion->presion_arterial_y}}</p>
                              </div>  
                            </div>
                          </div>
                          <div class="col-md-2">
                            <label>FREC.RESPIRATORIA(/min)</label>
                            <p>{{$prestacion->frecuencia_respiratoria}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>FREC. CARDIACA (/min) &nbsp;</label>
                            <p>{{$prestacion->frecuencia_cardiaca}}</p>
                          </div>
                          <div class="col-md-2">
                            <label>Fi02 (%) &nbsp;</label>
                            <p>{{$prestacion->fi02}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>EXAMEN FISICO</label>
                          <p>{{$prestacion->examen_fisico}}</p>
                      </div>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label>EXAMEN CLINICO</label>
                        <p>{{$prestacion->examen_clinico}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <p>{{$prestacion->tratamiento_auxiliar}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PLAN DE TRABAJO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <p>{{$prestacion->plan_trabajo}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNÓSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <table class="display" id="tblDiagnosticoAtencionEM" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="20%">Codigo</th>
                              <th width="60%">Nombre</th>
                              <th width="20%">Tipo</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            <?php foreach($diagnostico as $row):
                              $tipo_diagnostico = "";
                              if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                              if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                              if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
                            ?>
                            <tr>
                              <td>{{$row->codigo}}</td>
                              <td>{{$row->nombre}}</td>
                              <td>{{$tipo_diagnostico}}</td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS MÉDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-12">
                        <table class="display" id="tblProcedimientoAtencionEM" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="20%">Codigo</th>
                              <th width="60%">Nombre</th>
                              <th width="20%">Cantidad</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            <?php foreach($procedimiento as $row): ?>
                            <tr>
                              <td>{{$row->codigo}}</td>
                              <td>{{$row->nombre}}</td>
                              <td>{{$row->cantidad}}</td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsRecetaVale100">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PRODUCTOS MEDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <?php foreach($receta as $rowReceta):
              				if ($rowReceta->estado == 1) { ?>
                        
                        <div style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold">
                            LA RECETA HA SIDO DISPENSADA EL {{$rowReceta->fecha_expedicion }}          
                        </div>
                      <?php                
                      }
                      else{ //estado 2 
                      ?>
                        <div style="padding-bottom:0px;line-height:30px;color:#FF5151;font-weight:bold">
                            LA RECETA AUN NO HA SIDO DISPENSADA
                        </div>    
                      <?php }
                      
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
                                <p>{{$rowReceta->nro_receta}}</p>
                              </div>
                              <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                              <div class="col-md-2">
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                              </div>
                              <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                              <div class="col-md-4" style="padding:0px">
                                <p>{{$rowReceta->farmacia}}</p>
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
                                <th width="15%">Cantidad Prescrita</th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row): ?>
                            <tr>
                              <td>{{$row->codigo}}</td>
                              <td>{{$row->nombre}}</td>
                              <td>{{$row->abreviatura}}</td>
                              <td>{{$row->tiempo_periodo}}</td>
                              <td>{{$row->posologia}}</td>
                              <td>{{$row->cantidad_prescrita}}</td>
                            </tr>
                            <?php endforeach; ?>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach; ?>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsExamenLab100">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <table class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th width="20%">Codigo</th>
                                    <th width="60%">Nombre</th>
                                    <th width="20%">Cantidad</th>
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($laboratorio as $row): ?>
                                  <tr>
                                    <td>{{$row->codigo}}</td>
                                    <td>{{$row->nombre}}</td>
                                    <td>{{$row->cantidad}}</td>
                                  </tr>
                                  <?php endforeach; ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>                   
                    <div style="clear:both"></div>
                  </div>  
                </div>
              </fieldset>
              
              
              <fieldset id="fsExamenRad100">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b><b>EXAMEN DE IMAGENOLOGÍA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-12">
                          <table class="display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th width="20%">Codigo</th>
                                <th width="60%">Nombre</th>
                                <th width="20%">Cantidad</th>
                              </tr>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                              <?php foreach($radiologia as $row): ?>
                              <tr>
                                <td>{{$row->codigo}}</td>
                                <td>{{$row->nombre}}</td>
                                <td>{{$row->cantidad}}</td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div style="clear:both"></div>
                </div>
              </fieldset>
              
              <div class="form-group">
                <div class="col-md-12">
                  <fieldset id="fsInterconsulta100">
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
                            <?php foreach($interconsultas as $rowi): ?>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group"> 
                                <div class="panel-body"> 
                                  <div class="col-md-12">
                                    <div class="col-md-2"> 
                                      <label>SERVICIO: </label>
                                    </div> 
                                    <div class="col-md-10"> 
                                      {{$rowi->servicio }}
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="col-md-2"> 
                                      <label>MOTIVO:</label> 
                                    </div> 
                                    <div class="col-md-10"> 
                                      {{$rowi->motivo_interconsulta }}
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
                            <?php endforeach; ?>
                          </div>
                        </fieldset>
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

