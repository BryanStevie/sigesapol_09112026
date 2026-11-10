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
              action="">
              <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $interconsulta->tipo_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $interconsulta->nro_doc_ident?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $interconsulta->sexo?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $interconsulta->asegurado_paterno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $interconsulta->asegurado_materno?></p>
                      </div>

                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $interconsulta->asegurado_nombre?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $interconsulta->fecha_nac?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $interconsulta->parentesco?></p>
                      </div>

                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $interconsulta->grado?></p>
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
                    <table class="display" id="tblDiagnostico2" cellspacing="0" width="100%" style="margin-top:5px;">
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
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>PROCEDIMIENTOS MÉDICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblProcedimientoM" cellspacing="0" width="100%" style="margin-top:5px;">
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
              <fieldset id="fsInterconsulta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>RESPUESTA DE LA INTERCONSULTA</b>
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
                                <div class="col-md-12">
                                  <?php echo $interconsulta->evaluacion ?>
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
              <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-4">
                                <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                <p>DNI</p>
                              </div>
                              <div class="col-md-4">
                                <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $interconsulta->dni?></p>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>APELLIDO PATERNO</label>
                                <p><?php echo $interconsulta->medico_paterno_tr?></p>
                              </div>
                              <div class="col-md-4">
                                <label>APELLIDO MATERNO</label>
                                <p><?php echo $interconsulta->medico_materno_tr?></p>
                              </div>
                              <div class="col-md-4">
                                <label>NOMBRES</label>
                                <p><?php echo $interconsulta->medico_nombre_tr?></p>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label>N° COLEGIATURA</label>
                                <p><?php echo $interconsulta->colegiatura_medico_tr?></p>
                              </div>
                              <div class="col-md-2">
                                <label>N° RNE</label>
                                <p><?php echo $interconsulta->tr_rne?></p>
                              </div>
                              <div class="col-md-4">
                                <label>PROFESION</label>
                                <p><?php echo $interconsulta->profesion?></p>
                              </div>
                              <div class="col-md-4">
                                <label>ESPECIALIDAD</label>
                                <p><?php echo $interconsulta->especializacion?></p>
                              </div>
                            </div>
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
