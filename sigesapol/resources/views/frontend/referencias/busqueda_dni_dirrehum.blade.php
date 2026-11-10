@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script>
</script>
<style>
  #black_contenido {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: black;
      opacity: 1;
      z-index: 9999;
      display: none;
  }
</style>

@endsection
@section('content')
<div id="black_contenido"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE HISTORIA CLINICA DE ATENCIONES</h3>
          </div>
          <div class="panel-body">
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <div class="panel with-nav-tabs panel-default">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabdefault" data-toggle="tab" style="font-size:12px">DATOS DEL PACIENTE</a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade in active" id="tabdefault">                        
                          <div class="row">
                            <div class="form-group">
                              <div class="col-md-4">
                                <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $paciente->tipo_documento?></p>
                                <input type="hidden" value="<?php echo $paciente->nro_doc_ident?>" name="dnibeneficiario" id="dnibeneficiario" class="form-control">
                              </div>        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                <p><?php echo $paciente->nro_doc_ident?></p>
                              </div>        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">SEXO</label>
                                <p><?php echo $paciente->sexo?></p>
                              </div>
                            </div>        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>APELLIDO PATERNO</label>
                                <p><?php echo $paciente->asegurado_paterno?></p>
                              </div>        
                              <div class="col-md-4">
                                <label>APELLIDO MATERNO</label>
                                <p><?php echo $paciente->asegurado_materno?></p>
                              </div>        
                              <div class="col-md-4">
                                <label>NOMBRES</label>
                                <p><?php echo $paciente->asegurado_nombre?></p>
                              </div>
                            </div>        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>FECHA DE NACIMIENTO</label>
                                <p><?php echo $paciente->fecha_nac?></p>
                              </div>
                              <div class="col-md-4">
                                <label>PARENTESCO &nbsp;</label>
                                <p><?php echo $paciente->parentesco?></p>
                              </div>
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
                        <b>REFERENCIAS</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive">
                          <table class="table table-hover" id="tblAtenciones">
                            <thead>
                              <tr>
                              <th>#</th>
                              <th>Establecimiento Origen</th>
                              <th>Servicio Origen</th>
                              <th>Establecimiento Destino</th>
                              <th>Estado Referencia</th>
                              <th>Profesional Referencia</th>
                              </tr>
                            </thead>
                              <?php $i = 1 ?>
                            <tbody>
                              @foreach($referencias as $a)
                              <tr>
                              <td>{{$i}}</td>
                              <td>{{$a->nombre_establecimiento_origen}}</td>
                              <td>{{$a->nombre_consultorio}}</td>
                              <td>{{$a->nombre_establecimiento_destino}}</td>
                              <td>{{$a->estado_referencia}}</td>
                              <td>{{$a->nombre_medico}} {{$a->paterno_medico}} {{$a->materno_medico}}</td>  
                              </tr>
                              <?php $i++; ?>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                      <div class="panel-title text-center">
                      <b>CITAS</b>
                      </div>
                      </div>
                      <div class="panel-body">
                      <div class="table-responsive">
                      <table class="table table-hover" id="tblAtenciones">
                      <thead>
                      <tr>
                      <th>#</th>
                      <th>Fecha Cita</th>
                      <th>Establecimiento</th>
                      <th>Cita por Referencia</th>
                      <th>Servicio</th>
                      <th>Estado Cita</th>
                      </tr>
                      </thead>
                      <?php $i = 1 ?>
                      <tbody>
                      @foreach($citas as $a)
                      <tr>
                      <td>{{$i}}</td>
                      <td>{{$a->fecha_hora}}</td>
                      <td>{{$a->nombre_establecimiento}}</td>
                      <td>{{$a->referido_cita}}</td>
                      <td>{{$a->nombre_consultorio}}</td>
                      <td>{{$a->nombre_estado}}</td>  
                      </tr>
                      <?php $i++; ?>
                      @endforeach
                      </tbody>
                      </table>
                      </div>
                      </div>
                    </div>
                  </fieldset>
                <fieldset>

<div class="panel panel-info panel-bordered panel-sm">
  <div class="panel-heading">
    <div class="panel-title text-center">
      <b>ATENCIONES</b>
    </div>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-hover" id="tblAtenciones">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Establecimiento</th>
            <th>Tipo de Atención</th>
            <th>Servicio</th>
            <th>Profesional</th>
          </tr>
        </thead>
        <?php $i = 1 ?>
        <tbody>
          @foreach($atenciones as $a)
          <tr>
            <td>{{$i}}</td>
            <td>{{date('d/m/Y', strtotime($a->dia))}}</td>
            <td>{{$a->establecimiento}}</td>
            <td>{{$a->tipo_atencion}}</td>
            <td>{{$a->servicio}}</td>
            <td>{{$a->medico_nombre}} {{$a->medico_paterno}} {{$a->medico_materno}}</td>  
          </tr>
          <?php $i++; ?>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</fieldset>



              </div>

             
              <a class="btn btn-primary btn-sm hidden-print pull-right" href="/busqueda_hc_electronica"> <span
                  class="glyphicon glyphicon-list"></span> &nbsp; Busqueda</a>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
