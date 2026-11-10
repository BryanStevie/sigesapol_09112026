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
                      <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                      @if($evolucion->id_tipo_atencion==2)
                      <p>EMERGENCIA</p>
                      @else
                      <p>HOSPITALIZACION</p>
                      @endif
                    </div>
                    <div class="col-md-2">
                      <label for="fecha_atencion">FECHA DE ATENCION</label>
                       <p><?php echo $evolucion->fecha_atencion?></p>
                    </div>
                    <div class="col-md-2">
                      <label for="fecha_atencion">HORA DE ATENCION</label>
                       <p><?php echo $evolucion->hora_ingreso?></p>
                    </div>
                    @if($evolucion->id_tipo_atencion==2)
                    <div class="col-md-2">
                      <label for="fecha_atencion">CAMA</label>
                       <p><?php echo $evolucion->id_cama?></p>
                    </div>
                    @endif
                    <div class="col-md-2">
                      <label>N° HISTORIA CLINICA</label>
                      <p><?php echo $paciente->nro_historia?></p>
                    </div>
                    <div class="col-md-2">
                      <label>SERVICIO</label>
                      <p><?php echo $paciente->consultorio?></p>
                    </div>  
                  </div>
                  @if($evolucion->id_tipo_atencion==3)
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">PABELLON</label><br/>
                          <?php echo $paciente->pabellon?>
                        </div>
                        <div class="col-md-3">
                          <label for="dni_beneficiario">PISO</label><br/>
                          <?php echo $paciente->piso?>
                        </div>
                        <div class="col-md-3">
                          <label>CUARTO</label><br/>
                          <?php echo $paciente->cuarto?>
                        </div>
                        <div class="col-md-3">
                          <label>CAMA</label><br/>
                          <?php echo $paciente->cama?>
                        </div>
                      </div>
                    @endif
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
                    <p><?php echo $medico->dni?></p>
                  </div>
                  <div class="col-md-2">
                    <label>N° COLEGIATURA</label>
                    <p><?php echo $medico->colegiatura?></p>
                  </div>
                  <div class="col-md-2">
                    <label>N° RNE</label>
                    <p><?php echo $medico->rne?></p>
                  </div>
                  <div class="col-md-3">
                    <label>PROFESION</label>
                    <p><?php echo $medico->profesion?></p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-3">
                    <label>APELLIDO PATERNO</label>
                    <p><?php echo $medico->paterno?></p>
                  </div>
                  <div class="col-md-3">
                    <label>APELLIDO MATERNO</label>
                    <p><?php echo $medico->materno?></p>
                  </div>
                  <div class="col-md-3">
                    <label>NOMBRES</label>
                    <p><?php echo $medico->nombre?></p>
                  </div>
                  <div class="col-md-3">
                    <label>ESPECIALIDAD</label>
                    <p><?php echo $medico->especializacion?></p>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
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
                        <p><?php echo $evolucion->temperatura?></p>
                  </div>
                  <div class="col-md-2">
                      <label>TALLA (cm) &nbsp;</label>
                      <p><?php echo $evolucion->talla_cm?></p>
                  </div>
                  <div class="col-md-2">
                      <label>PESO (Kg) &nbsp;</label>
                      <p><?php echo $evolucion->peso_kg?></p>
                  </div>
                  <div class="col-md-2">
                      <label>IMC &nbsp;</label>
                      <p></p>
                  </div>
                  <div class="col-md-2">
                      <label>PESO POR ALTURA &nbsp;</label>
                      <p></p>
                  </div>
                  <div class="col-md-2">
                      <label>Sa02 (%) &nbsp;</label>
                      <p><?php echo $evolucion->sa02_porcentaje?></p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-2">
                      <label>TAMAÑO DE CINTURA (cm) &nbsp;</label>
                      <p><?php echo $evolucion->tamano_cintura_cm?></p>
                  </div>
                  <div class="col-md-2">
                      <label>FREC. CARDIACA FETAL (/min) &nbsp;</label>
                      <p><?php echo $evolucion->frecuencia_cardiaca_fetal?></p>
                  </div>
                  <div class="col-md-2">
                      <label>PRESIÓN ARTERIAL &nbsp;</label>
                      <p><?php echo $evolucion->presion_arterial_x?></p>
                  </div>
                  <div class="col-md-2">
                      <label>FREC. RESPIRATORIA (/min) &nbsp;</label>
                      <p><?php echo $evolucion->frecuencia_respiratoria?></p>
                  </div>
                  <div class="col-md-2">
                      <label>FREC. CARDIACA (/min) &nbsp;</label>
                      <p><?php echo $evolucion->frecuencia_cardiaca?></p>
                  </div>
                  <div class="col-md-2">
                      <label>Fi02 (%) &nbsp;</label>
                      <p><?php echo $evolucion->fi02?></p>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>INFORMACIÓN DEL DETALLE DE EVOLUCIÓN</b>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label class="col-md-2" style="padding-left:0px">SUBJETIVO</label>
                      <div class="col-md-10">
                        <p><?php echo $evolucion->subjetivo?></p>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label class="col-md-2" style="padding-left:0px">OBJETIVO</label>
                      <div class="col-md-10">
                        <p><?php echo $evolucion->objetivo?></p>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label class="col-md-2" style="padding-left:0px">ANALISIS</label>
                      <div class="col-md-10">
                        <p><?php echo $evolucion->analisis?></p>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label class="col-md-2" style="padding-left:0px">TRATAMIENTO Y DIETA</label>
                      <div class="col-md-10">
                        <p><?php echo $evolucion->tratamiento_dieta?></p>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label class="col-md-2" style="padding-left:0px">INDICACIONES</label>
                      <div class="col-md-10">
                        <p><?php echo $evolucion->indicaciones?></p>
                      </div>
                    </div>
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

