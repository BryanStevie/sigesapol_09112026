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
                    <div class="col-md-2">
                      <label for="fecha_atencion">FECHA DE ATENCION</label>
                      <p>{{$evaluacion->fecha_evaluacion}}</p>
                    </div>
                    <div class="col-md-2">
                      <label for="fecha_atencion">HORA DE ATENCION</label>
                      <p>{{$evaluacion->hora_evaluacion}}</p>
                    </div>
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p>{{$paciente->nro_historia}}</p>
                    </div>
                    <div class="col-md-3">
                      <label>PRIORIDAD</label>
                      <p>{{$evaluacion->servicio}}</p>
                    </div>
                    <div class="col-md-3">
                      <label>TOPICO</label>
                      <p>{{$evaluacion->consultorio}}</p>
                    </div>  
                  </div>
                  <div class="form-group">
                    <div class="col-md-2">
                      <label for="tipo">PABELLON</label><br/>
                      {{$datoadicional->pabellon}}
                    </div>
                    <div class="col-md-2">
                      <label for="dni_beneficiario">PISO</label><br/>
                      {{$datoadicional->piso}}
                    </div>
                    <div class="col-md-2">
                      <label>CUARTO</label><br/>
                      {{$datoadicional->cuarto}}
                    </div>
                    <div class="col-md-3">
                      <label>CAMA</label><br/>
                      {{$datoadicional->cama}}
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
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
                    <p>{{$medico->dni}}</p>
                  </div>
                  <div class="col-md-2">
                    <label>N° COLEGIATURA</label>
                    <p>{{$medico->colegiatura}}</p>
                  </div>
                  <div class="col-md-2">
                    <label>N° RNE</label>
                    <p>{{$medico->rne}}</p>
                  </div>
                  <div class="col-md-3">
                    <label>PROFESION</label>
                    <p>{{$medico->profesion}}</p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-3">
                    <label>APELLIDO PATERNO</label>
                    <p>{{$medico->paterno}}</p>
                  </div>
                  <div class="col-md-3">
                    <label>APELLIDO MATERNO</label>
                    <p>{{$medico->materno}}</p>
                  </div>
                  <div class="col-md-3">
                    <label>NOMBRES</label>
                    <p>{{$medico->nombre}}</p>
                  </div>
                  <div class="col-md-3">
                    <label>ESPECIALIDAD</label>
                    <p>{{$medico->especializacion}}</p>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center" style="position: relative">
                  <b>DIAGNÓSTICOS DE LA EVALUACION</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblDiagnosticoVer" cellspacing="0" width="100%" style="margin-top:5px;">
                  <thead>
                    <tr>
                      <th width="20%">Codigo</th>
                      <th width="60%">Nombre</th>
                      <th width="20%">Tipo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($diagnostico_prestaciones as $row)
                      <?php
                        $tipoDiagnostico = '';
                        switch($row->id_tipo_diagnostico) {
                          case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                          case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                          case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                          default:
                          // Contenido para el caso por defecto
                        }
                      ?>
                      <tr class="row-diagnosticosfinal">
                        <td>{{ $row->codigo }}</td>
                        <td>{{ $row->nombre }}</td>
                        <td>{{ $tipoDiagnostico }}</td>
                        <td>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot style="margin-top: 6px;">
                    <td>
                    </td>
                  </tfoot>
                </table>
              </div>
            </div>
          </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>PROCEDIMIENTOS DE LA EVALUACION</b>
                  </div>
                </div>
                <div class="panel-body">
                  <table class="display" id="tblProcedimientoVer" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                      <tr>
                        <th width="10%">codigo</th>
                        <th width="30%">Nombre</th>
                        <th width="20%">Cantidad</th>
                        <th width="40%">diagnostico</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($procedimiento_evaluacion as $row)
                      <tr class="row2-{{ $row->cod }}">
                        <td>{{ $row->codigo }}</td>
                        <td>{{$row->nombre}}</td>
                        <td>{{ $row->cantidad }}</td>
                        <td><?php
                            $model_diagnosticos = new \App\Models\Producto_receta;
                            $diagnostico_id = $row->id_diagnostico;
                            $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id);
                                echo $diagnostico_nombre;
                            ?>
                        </td>
                        <td>
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                    <tfoot><tr><td></td></tr></tfoot>
                  </table>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>ANTECEDENTES</b>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>ATECEDENTE</label>
                    <p>{{$evaluacion->antecedente}}</p>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>ANTECEDENTE FAMILIAR</label>
                      <p>{{$evaluacion->antecedente_familiar}}</p>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>ANAMNESIS</b>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>MOTIVO DE VISITA</label>
                    <p>{{$evaluacion->motivo_visita}}</p>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>TIEMPO DE ENFERMEDAD/RELATO CRONOLOGICO</label>
                      <p>{{$evaluacion->tiempo_enfermedad}}</p>
                      <p></p>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <label>EXAMENES AUXILIARES</label>
                    <p>{{$evaluacion->examen_auxiliar}}</p>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>EXAMEN FÍSICO</b>
                  </div>
                </div>
                <div class="panel-body">
                  @if($evaluacion->temperatura>0)
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
                          <p>{{$evaluacion->temperatura}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>TALLA (cm) &nbsp;</label>
                          <p>{{$evaluacion->talla_cm}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>PESO (Kg) &nbsp;</label>
                          <p>{{$evaluacion->peso_kg}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>IMC &nbsp;</label>
                          <p>
                          <?php
                            if($evaluacion->talla_cm==0):
                             echo "0";
                            else:
                              if($evaluacion->talla_cm!=0):
                                echo ($evaluacion->peso_kg / (($evaluacion->talla_cm * $evaluacion->talla_cm)*(0.01*0.01)));
                              endif;
                          endif;
                          ?>
                         </p>
                        </div>
                        <div class="col-md-2">
                          <label>PESO IDEAL &nbsp;</label>
                          <p><?php 
                            if($evaluacion->peso_kg==0):
                              echo "0";
                            else:
                              echo 45+(($evaluacion->peso_kg-150)*3/4);
                            endif;
                          ?>
                          </p>
                        </div>
                        <div class="col-md-2">
                          <label>Sa02 (%) &nbsp;</label>
                          <p>{{$evaluacion->sa02_porcentaje}}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                          <label>TAMAÑO DE CINTURA (cm)</label>
                          <p>{{$evaluacion->tamano_cintura_cm}}</p>
                        </div>
                        <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                          <label>FREC. CARDIACA FETAL(/min)</label>
                          <p>{{$evaluacion->frecuencia_cardiaca_fetal}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>PRESIÓN ARTERIAL &nbsp;</label>
                          <div class="row">
                            <div class="col-xs-5">                          
                              <p>{{$evaluacion->presion_arterial_x}}</p>
                            </div>  
                              <div class="col-xs-2">
                                 <label>/</label> 
                              </div>
                            <div class="col-xs-5">
                              <p>{{$evaluacion->presion_arterial_y}}</p>
                            </div>  
                          </div>
                      </div>
                        <div class="col-md-2">
                          <label>FREC.RESPIRATORIA(/min)</label>
                          <p>{{$evaluacion->frecuencia_respiratoria}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>FREC. CARDIACA (/min) &nbsp;</label>
                          <p>{{$evaluacion->frecuencia_cardiaca}}</p>
                        </div>
                        <!--div class="col-md-2">
                          <label>FREC. CARDIACA (/min) &nbsp;</label>
                          <p></p>
                        </div-->
                        <div class="col-md-2">
                          <label>Fi02 (%) &nbsp;</label>
                          <p>{{$evaluacion->fi02}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>EXAMEN FISICO</label>
                        <p>{{$evaluacion->examen_fisico}}</p>
                    </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>EXAMEN CLINICO</label>
                      <p>{{$evaluacion->examen_clinico}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>TRATAMIENTO Y/O TERAPIA</b>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <p>{{$evaluacion->tratamiento_auxiliar}}</p>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <label>PLAN DE TRABAJO</label>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <p>{{$evaluacion->plan_trabajo}}</p>
                  </div>
                </div>
              </div>
            </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PRODUCTOS MEDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body panel-body-receta">
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
                                <p>{{$rowReceta->farmacia }}</p>
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
                                <th width="15%">diagnosticos</th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <?php foreach($medicamentos as $row): ?>
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
                              <td><?php if($row->codigo=='59075700'):
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
                          </div>
                        <div style="clear:both"></div>
                        
                    </div>
                  <div class="form-group">
                    <!-- <div class="col-md-12">    -->
                    <fieldset>
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
                            $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($evaluacion->id_prestacion, $rowLaboratorio->id);
                          ?>
                          <!-- <br/> -->
                          <!-- <div style="clear:both"></div> -->
                          <div class="form-group">
                            <!-- <div class="col-md-12"> -->
                              <div class="col-md-3">
                                <label style="padding-left:0px">N° ORDEN:</label>
                                <p> {{$evaluacion->id_prestacion}} - {{$rowLaboratorio->id}}</p>
                              </div>
                              <div class="col-md-3">
                                <label style="padding-left:0px;text-align:right">FECHA / HORA SOLICITUD:</label>
                                <p> {{date("d/m/Y H:m:s", strtotime($rowLaboratorio->created_at))}}</p>
                              </div>
                              <div class="col-md-3">
                                <label style="padding-left:0px;text-align:right">FECHA / HORA FIRMA SOLICITUD:</label><p> {{$rowLaboratorio->fecha_firma}}</p>
                              </div>
                              <div class="col-md-3">
                                <label style="padding-left:0px;text-align:right">FECHA / HORA TOMA DE MUESTRA:</label>
                                <p> {{$rowLaboratorio->fecha_registro_toma_muestra}}</p>
                              </div>
                            <!-- </div> -->
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
                                              <th width="20%">Codigo</th>
                                              <th width="60%">Nombre</th>
                                              <th width="20%">Cantidad</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <?php foreach($proclaboratorio as $row): ?>
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
                            </div>
                          </div>
                          <?php endforeach; ?>
                          <div style="clear:both"></div>
                          <fieldset>
                            <div class="panel-body">
                              <table class="display"cellspacing="0" width="100%" style="margin-top:5px;">
                                <thead>
                                  <tr>
                                    <th width="90%"></th>
                                    <th width="10%"></th>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </fieldset>
                        </div> <!-- .panel-body -->
                      </div> <!-- .panel -->
                    </fieldset>
                    <!--</div> .col-md-12 -->
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                  <fieldset>
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
                          $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorioEva($evaluacion->id_prestacion, $rowRadiologia->id,3,$evaluacion->id);
                        ?>
                        <br />
                        <div class="form-group">
                          <div class="col-md-12">
                            <label class="col-md-2" style="padding-left:0px">N° ORDEN</label>
                            <div class="col-md-3">
                              <p><?php echo $evaluacion->id_prestacion.' - '. $rowRadiologia->id; ?></p>
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
                                        <th width="8%">Codigo</th>
                                        <th width="60%">Nombre</th>
                                        <th width="25%">Observación</th>
                                        <th width="7%">Cantidad</th>
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
                                        <td><?php if($row->observacion!=0):
                                                echo $row->observacion;
                                              endif;
                                        ?>
                                        </td>
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
                        <?php endforeach; ?>
                        <div style="clear:both"></div>
                        <fieldset>
                          <div class="panel-body">
                            <table class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                              <thead>
                                <tr>
                                  <th width="90%"></th>
                                  <th width="10%"></th>
                                </tr>
                              </thead>
                              <tfoot>
                              </tfoot>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <fieldset id="fsInterconsultaEv">
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
                                      {{$rowi->codigo_interconsulta }} - {{$rowi->servicio }}
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

