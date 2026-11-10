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
                               
                              </div>        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                               
                              </div>        
                              <div class="col-md-4">
                                <label for="dni_beneficiario">SEXO</label>
                             
                              </div>
                            </div>        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>APELLIDO PATERNO</label>
                              
                              </div>        
                              <div class="col-md-4">
                                <label>APELLIDO MATERNO</label>
                              
                              </div>        
                              <div class="col-md-4">
                                <label>NOMBRES</label>
                             
                              </div>
                            </div>        
                            <div class="form-group">
                              <div class="col-md-4">
                                <label>FECHA DE NACIMIENTO</label>
                             
                              </div>
                              <div class="col-md-4">
                                <label>PARENTESCO &nbsp;</label>
                    
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
