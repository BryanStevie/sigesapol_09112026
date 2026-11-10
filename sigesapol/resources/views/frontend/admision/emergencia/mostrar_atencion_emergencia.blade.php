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
                      <p><?php echo $prestacion->tipo_atencion?></p>
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">FECHA DE ATENCION</label>
                       <p><?php echo $prestacion->fecha_atencion?></p>
                    </div>
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p><?php echo $prestacion->nro_historia?></p>
                    </div>
                    <div class="col-md-3">
                      <label for="dni_beneficiario">SERVICIO</label>
                      <p><?php echo $prestacion->servicio?></p>
                    </div>
                    <div class="col-md-1">
                      <label>CONSULTORIO</label>
                      <p><?php echo $prestacion->consultorio?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-3">
                      <label for="tipo">NOMBRE DE LA IPRESS</label>
                      <p><?php echo $prestacion->establecimiento?></p>
                    </div>
                    <div class="col-md-2">
                      <label for="tipo">PABELLON</label><br/>
                      <?php echo $datoadicional->pabellon?>
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">PISO</label><br/>
                      <?php echo $datoadicional->piso?>
                    </div>
                    <div class="col-md-3">
                      <label>CUARTO</label><br/>
                      <?php echo $datoadicional->cuarto?>
                    </div>
                    <div class="col-md-1">
                      <label>CAMA</label><br/>
                      <?php echo $datoadicional->cama?>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div></div>
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
                        <p><?php echo $prestacion->dni?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° COLEGIATURA</label>
                        <p><?php echo $prestacion->colegiatura?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° RNE</label>
                        <p><?php echo $prestacion->rne?></p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p><?php echo $prestacion->profesion?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->medico_paterno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->medico_materno?></p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->medico_nombre?></p>
                      </div>
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p><?php echo $prestacion->especializacion?></p>
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
                        <p><?php echo $dato_triaje->temperatura?></p>
                      </div>
                      <div class="col-md-2">
                        <label>TALLA (cm) &nbsp;</label>
                        <p><?php echo $dato_triaje->talla_cm?></p>
                      </div>
                      <div class="col-md-2">
                        <label>PESO (Kg) &nbsp;</label>
                        <p><?php echo $dato_triaje->peso_kg?></p>
                      </div>
                      <div class="col-md-2">
                        <label>IMC &nbsp;</label>
                        <p>
                        <?php
                          if($dato_triaje->talla_cm!=0):
                           echo ($dato_triaje->peso_kg / (($dato_triaje->talla_cm * $dato_triaje->talla_cm)*(0.01*0.01)));
                          endif;
                        ?>
                         
                       </p>
                      </div>
                      <div class="col-md-2">
                        <label>PESO IDEAL &nbsp;</label>
                        <p><?php echo 45+(($dato_triaje->peso_kg-150)*3/4)?></p>
                      </div>
                      <div class="col-md-2">
                        <label>Sa02 (%) &nbsp;</label>
                        <p><?php echo $dato_triaje->sa02_porcentaje?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label>TAMAÑO DE CINTURA (cm)</label>
                        <p><?php echo $dato_triaje->tamano_cintura_cm?></p>
                      </div>
                      <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                        <label>FREC. CARDIACA FETAL(/min)</label>
                        <p><?php echo $dato_triaje->frecuencia_cardiaca_fetal?></p>
                      </div>
                      <div class="col-md-2">
                        <label>PRESIÓN ARTERIAL &nbsp;</label>
                        <div class="row">
                          <div class="col-xs-5">                          
                            <p><?php echo $dato_triaje->presion_arterial_x?></p>
                          </div>  
                            <div class="col-xs-2">
                               <label>/</label> 
                            </div>
                          <div class="col-xs-5">
                            <p><?php echo $dato_triaje->presion_arterial_y?></p>
                          </div>  
                        </div>
                    </div>
                      <div class="col-md-2">
                        <label>FREC.RESPIRATORIA(/min)</label>
                        <p><?php echo $dato_triaje->frecuencia_respiratoria?></p>
                      </div>
                      <div class="col-md-2">
                        <label>FREC. CARDIACA (/min) &nbsp;</label>
                        <p><?php echo $dato_triaje->frecuencia_cardiaca?></p>
                      </div>
                      <!--div class="col-md-2">
                        <label>FREC. CARDIACA (/min) &nbsp;</label>
                        <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                      </div-->
                      <div class="col-md-2">
                        <label>Fi02 (%) &nbsp;</label>
                        <p><?php echo $dato_triaje->fi02?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
                <div class="panel with-nav-tabs panel-default">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab800default" data-toggle="tab" style="font-size:12px">ANTECEDENTES</a></li>
                      <li><a href="#tab801default" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                      <li><a href="#tab802default" data-toggle="tab" style="font-size:12px">EXAMEN FISICO</a></li>
                      <li><a href="#tab803default" data-toggle="tab" style="font-size:12px">TRATAMIENTO Y/O TERAPEUTICA</a></li>
                      <li><a href="#tab804default" data-toggle="tab" style="font-size:12px">PLAN DE TRABAJO</a></li>
                      <li><a href="#tab805default" data-toggle="tab" style="font-size:12px">DIAGNÓSTICOS</a></li>
                      <li><a href="#tab806default" data-toggle="tab" style="font-size:12px">PROCEDIMIENTOS MÉDICOS</a></li>
                    </ul>
                  </div>
                  <div class="panel-body">
                    <div class="tab-content">
                      <div class="tab-pane fade in active" id="tab800default">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>ATECEDENTE</label>
                            <p><?php echo $prestacion->antecedente?></p>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>ANTECEDENTE FAMILIAR</label>
                              <p><?php echo $prestacion->antecedente_familiar?></p>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab801default">
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>MOTIVO DE VISITA</label>
                            <p><?php echo $prestacion->motivo_visita?></p>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>TIEMPO DE ENFERMEDAD/RELATO CRONOLOGICO</label>
                              <p><?php echo $prestacion->tiempo_enfermedad?></p>
                              <p><?php echo $prestacion->anamnesis?></p>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>EXAMENES AUXILIARES</label>
                            <p><?php echo $prestacion->examen_auxiliar?></p>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab802default">
                        <div class="panel-body">
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
                                  <p><?php echo $prestacion->temperatura?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>TALLA (cm) &nbsp;</label>
                                  <p><?php echo $prestacion->talla_cm?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>PESO (Kg) &nbsp;</label>
                                  <p><?php echo $prestacion->peso_kg?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>IMC &nbsp;</label>
                                  <p>
                                  <?php
                                    if($prestacion->talla_cm!=0):
                                     echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01)));
                                    endif;
                                  ?>
                                   
                                 </p>
                                </div>
                                <div class="col-md-2">
                                  <label>PESO IDEAL &nbsp;</label>
                                  <p><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>Sa02 (%) &nbsp;</label>
                                  <p><?php echo $prestacion->sa02_porcentaje?></p>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label>TAMAÑO DE CINTURA (cm)</label>
                                  <p><?php echo $prestacion->tamano_cintura_cm?></p>
                                </div>
                                <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                  <label>FREC. CARDIACA FETAL(/min)</label>
                                  <p><?php echo $prestacion->frecuencia_cardiaca_fetal?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>PRESIÓN ARTERIAL &nbsp;</label>
                                  <div class="row">
                                    <div class="col-xs-5">                          
                                      <p><?php echo $prestacion->presion_arterial_x?></p>
                                    </div>  
                                      <div class="col-xs-2">
                                         <label>/</label> 
                                      </div>
                                    <div class="col-xs-5">
                                      <p><?php echo $prestacion->presion_arterial_y?></p>
                                    </div>  
                                  </div>
                              </div>
                                <div class="col-md-2">
                                  <label>FREC.RESPIRATORIA(/min)</label>
                                  <p><?php echo $prestacion->frecuencia_respiratoria?></p>
                                </div>
                                <div class="col-md-2">
                                  <label>FREC. CARDIACA (/min) &nbsp;</label>
                                  <p><?php echo $prestacion->frecuencia_cardiaca?></p>
                                </div>
                                <!--div class="col-md-2">
                                  <label>FREC. CARDIACA (/min) &nbsp;</label>
                                  <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                                </div-->
                                <div class="col-md-2">
                                  <label>Fi02 (%) &nbsp;</label>
                                  <p><?php echo $prestacion->fi02?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN FISICO</label>
                                <p><?php echo $prestacion->examen_fisico?></p>
                            </div>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>EXAMEN CLINICO</label>
                              <p><?php echo $prestacion->examen_clinico?></p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab802default">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>TRATAMIENTO Y/O TERAPIA</label>
                              <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab803default">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>PLAN DE TRABAJO</label>
                              <p><?php echo $prestacion->plan_trabajo?></p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab804default">
                        <div class="form-group">
                          <div class="col-md-12">
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>DIAGNOSTICOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <table class="display" id="tblDiagnosticoAtencion" cellspacing="0" width="100%" style="margin-top:5px;">
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
                                        <td><?php echo $row->codigo?></td>
                                        <td><?php echo $row->nombre?></td>
                                        <td><?php echo $tipo_diagnostico?></td>
                                      </tr>
                                      <?php endforeach;?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </fieldset>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="tab805default">
                        <div class="form-group">
                          <div class="col-md-12">
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>PROCEDIMIENTOS MÉDICOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <table class="display" id="tblProcedimientoAtencion" cellspacing="0" width="100%" style="margin-top:5px;">
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
                                      <?php foreach($procedimiento as $row):?>
                                      <tr>
                                        <td><?php echo $row->codigo?></td>
                                        <td><?php echo $row->nombre?></td>
                                        <td><?php echo $row->cantidad?></td>
                                      </tr>
                                      <?php endforeach;?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </fieldset>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </fieldset>
              @if(count($receta)>0)
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
                            LA RECETA HA SIDO DISPENSADA EL <?php echo $rowReceta->fecha_expedicion ?>          
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
                                <th width="15%">Cantidad Prescrita</th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row):?>
                            <tr>
                              <td><?php echo $row->codigo?></td>
                              <td><?php echo $row->nombre?></td>
                              <td><?php echo $row->abreviatura?></td>
                              <td><?php echo $row->tiempo_periodo?></td>
                              <td><?php echo $row->posologia?></td>
                              <td><?php echo $row->cantidad_prescrita?></td>
                            </tr>
                            <?php endforeach;?>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                  </div>
                </div>
              </fieldset>
              @endif
              @if(count($laboratorio)>0)
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
                                  <?php foreach($laboratorio as $row):?>
                                  <tr>
                                    <td><?php echo $row->codigo?></td>
                                    <td><?php echo $row->nombre?></td>
                                    <td><?php echo $row->cantidad?></td>
                                  </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </fieldset>
                    <div style="clear:both"></div>
                  </div>
                </div>
              </fieldset>
              @endif
              @if(count($radiologia)>0)
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
                              <?php foreach($radiologia as $row):?>
                              <tr>
                                <td><?php echo $row->codigo?></td>
                                <td><?php echo $row->nombre?></td>
                                <td><?php echo $row->cantidad?></td>
                              </tr>
                              <?php endforeach;?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div style="clear:both"></div>
                </div>
              </fieldset>
              @endif
              @if(count($interconsultas)>0)
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
                            <?php foreach($interconsultas as $rowi):?>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group"> 
                                <div class="panel-body"> 
                                  <div class="col-md-12">
                                    <div class="col-md-2"> 
                                      <label>SERVICIO: </label>
                                    </div> 
                                    <div class="col-md-10"> 
                                      <?php echo $rowi->servicio ?>
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
              @endif
          </div>
      </div>
    </div>
  </div>
</div>
</div>

