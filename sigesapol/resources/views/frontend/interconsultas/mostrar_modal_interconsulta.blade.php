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
            <form class="form-horizontal" method="post" id="frmBloque"
              action="{{ route('registrar_receta_prestacion')}}">
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-4">
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
                      @if($prestacion->id_tipo_atencion==1)
                      <div class="col-md-2">
                        <label>SERVICIO</label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      <div class="col-md-2">
                        <label>CONSULTORIO</label>
                        <p><?php echo $prestacion->consultorio?></p>
                      </div>
                      @else
                      <div class="col-md-2">
                        <label>PRIORIDAD</label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      <div class="col-md-2">
                        <label>AREA</label>
                        <p><?php echo $prestacion->consultorio?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
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
                      <div class="col-md-2">
                        <label>CUARTO</label><br/>
                        <?php echo $datoadicional->cuarto?>
                      </div>
                      <div class="col-md-2">
                        <label>CAMA/CAMILLA</label><br/>
                        <?php echo $datoadicional->cama?>
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
                      <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p>DNI: <?php echo $prestacion->dni?></p>
                      </div>
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
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p><?php echo $prestacion->especializacion?></p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p><?php echo $prestacion->profesion?></p>
                      </div>
                      <div class="col-md-3">
                        <label>N° COLEGIATURA</label>
                        <p><?php echo $prestacion->colegiatura?></p>
                      </div>
                      <div class="col-md-3">
                        <label>N° RNE</label>
                        <p><?php echo $prestacion->rne?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
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
              @if($id_evaluacion!=0)
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS EVALUACION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
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
                        <?php foreach($diagnostico_evaluacion as $row):
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
              @endif
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS MÉDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
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
              @if($id_evaluacion!=0)
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS EVALUACION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
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
                        <?php foreach($procedimiento_evaluacion as $row):?>
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
              @endif
              <fieldset id="fsInterconsulta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DESCRIPCION DE LA INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="col-md-12">
                        <!-- MOSTRAR INTERCONSULTAS---->
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="form-group"> 
                            <div class="panel-body"> 
                              <div class="col-md-12">
                                <div class="col-md-2"> 
                                  <label>SERVICIO: </label>
                                </div> 
                                <div class="col-md-10"> 
                                  <?php echo $interconsulta->codigo_interconsulta ?> - <?php echo $interconsulta->servicio ?>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-2">
                                  <label>MEDICO DE INTERCONSULTA</label><br/>
                                </div> 
                                <div class="col-md-10"> 
                                  {{$interconsulta->medico_nombre_tr}} {{$interconsulta->medico_paterno_tr}} {{$interconsulta->medico_materno_tr}}
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <label>MOTIVO:</label><br/>
                                  <?php echo $interconsulta->motivo_interconsulta ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        
      </div>
    </div>
  </div>
</div>
</div>
