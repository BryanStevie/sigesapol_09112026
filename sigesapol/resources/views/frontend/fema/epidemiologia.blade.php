@extends('frontend.layouts.master')
@section('renderjs')
<script>

 function asignarValor(){
    var selects = document.querySelectorAll('select[orden]');
  var valoresYOrden = [];

  selects.forEach(function(select) {
    var valor = select.value;
    var orden = select.getAttribute('orden');

    valoresYOrden.push({valor});
  });
  let valorstring =JSON.stringify(valoresYOrden)

  const myInput = document.querySelector('input[name="id_diagnostico"]');

  myInput.value = valorstring;
  

console.log(myInput.value);

 
}

 </script>

@endsection
@section('content')
<form id="" class="" role="form" method="post"  action="{{ route('derivacion_epidemiologia')}}" >  
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="nro_doc_ident" id="nro_doc_ident" value="{{$personal->nro_doc_ident}}">
    <input type="hidden" name="id_prestacion" id="id_prestacion" value="@if(isset($personal)) {{$personal->id}} @endif">
    <input type="hidden" name="dni" id="dni" value="{{$profesional->dni}}">
    <input type="hidden" name="id_profesional" id="id_profesional" value="{{$profesional->id}}">
<div class="container">
    <div class="row ">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">EPIDEMIOLOGIA</h3>
                    <a href="/all_epidemiologias_fema/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                      <i class="fa fa-list"></i> Listado de Epidemiologia
                  </a>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info ">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="@if(isset($date)) {{$date}} @endif" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="FECHA Y HORA REGISTRO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="@if(isset($procesos)) {{$procesos->nombre}} @endif" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="TIPO PROCESO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="@if(isset($subprocesos)) {{$subprocesos->nombre}} @endif" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="SUB PROCESO">    
                                    </div>
                                </div>
                                <!--<div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-sm form-control">LISTA DE ATENCION MEDICA</button>
                                    </div>
                                </div>-->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N° @if(isset($profesional)) {{$profesional->id_establecimiento}} @endif - @if(isset($procesos)) {{$procesos->abreviatura}} @endif - @if(isset($personal)) {{$personal->id}} @endif - 2023</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACION PERSONAL</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                          <div class="col-md-9">
                              <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">TIPO DOCUMENTO</label>
                                            <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° DOCUMENTO</label>
                                            <input value="@if(isset($personal)) {{$personal->nro_doc_ident}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">GRADO</label>
                                            <input value="@if(isset($personal)) {{$personal->grado}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE PATERNO</label>
                                            <input value="@if(isset($personal)) {{$personal->paterno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE MATERNO</label>
                                            <input value="@if(isset($personal)) {{$personal->materno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">NOMBRES</label>
                                            <input value="@if(isset($personal)) {{$personal->nombre}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HISTORIA CLINICA DE IPRESS</label>
                                            <input value="{{$triaje->historia_clinica_ipress}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HISTORIA CLINICA</label>
                                            <input value="{{$triaje->historia_clinica}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° CIP</label>
                                            <input value="@if(isset($personal)) {{$personal->carnet}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">EDAD</label>
                                            <input value="@if(isset($personal)) {{$personal->edad}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">SEXO</label>
                                            <input value="@if(isset($personal)) @if($personal->sexo=='M') MASCULINO @else FEMENINO @endif @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="">CELULAR</label>
                                            <input value="@if(isset($personal)) {{$personal->celular}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="row">
                                      <div class="col-md-3">
                                          <div class="form-group">
                                      </div>
                                      </div>
                                </div>
                              </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title"><b>USUARIO DEL SISTEMA</b></h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" ><b>PERSONAL DE DIRSAPOL</b></label>
                                        <input value="@if(isset($profesional)){{$profesional->paterno}}  {{$profesional->materno}} {{$profesional->name}}@endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>

                    <!-- TRIAJE -->
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">SIGNOS VITALES</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <input value="@if(isset($triaje)) {{$triaje->temperatura}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TALLA (CM)</label>
                                        <input value="@if(isset($triaje)) {{$triaje->talla_cm}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PESO (KG)</label>
                                        <input value="@if(isset($triaje)) {{$triaje->peso_kg}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >IMC</label>
                                        <input value="{{$imc}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >DESCRIPCION IMC</label>
                                        <input value="{{$indice}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PRESION ARTERIAL (MM/HG)</label>
                                        <input value="@if(isset($triaje)) {{$triaje->presion_arterial_x}}/{{$triaje->presion_arterial_y}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <input value="@if(isset($triaje)) {{$triaje->frecuencia_respiratoria}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <input value="@if(isset($triaje)) {{$triaje->frecuencia_cardiaca}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >SaO2 (%)</label><br>
                                        <br>
                                        <input value="@if(isset($triaje)) {{$triaje->sa02_porcentaje}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!--
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <div class="row">
                                    <div class="col-md-6">CALIFICACION Y OBSERVACIONES (2 AÑOS) <button class="btn btn-sm btn-success">DETALLE</button></div>
                                    <div class="col-md-6">VACUNAS COVID-19 <button class="btn btn-sm btn-success">DETALLE</button></div>
                                </div>
                            </h3>
                        </div>
                    </div>
                        -->
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">DIAGNOSTICOS</h3>
                        </div>
                        <div class="panel-body ">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="20%">CODIGO</th>
                                        <th width="40%">DIAGNOSTICOS</th>
                                        <th width="20%">SERVICIO A DERIVAR</th>
                                        @if($verttatu>0)
                                          <th width="20%">SERVICIO A DERIVAR 2</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=2; ?>
                                    <input type="hidden" class="form-control typeahead tt-query" name="id_diagnostico" value="{{$i}}">
                                    @foreach($nomdiag as $f)
                                    <tr>
                                        <input type="hidden" name="id_fema_diagnostico" id="id_fema_diagnostico" value="{{$f->id}}">
                                             @if($f->estado_fema == '1')
                                                <td><input value="{{$f->codigo}}" type="text" name="codigo_diagnostico" id="codigo_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>

                                                <td><input value="{{$f->nombre}}" type="text" name="nombre_diagnostico" id="nombre_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>
                                             
                                                <td>
                                                    <select name="derivacion1" id="derivacion1_{{$i}}" class="form-control" orden="{{$i}}" onchange="asignarValor()">
                                                        <option value="">-Seleccionar-</option>
                                                            @foreach($nomcon as $c)
                                                            <option value="{{$c->id}}">{{$c->nombre}}</option>
                                                            @endforeach
                                                    </select>
                                                    
                                                </td>
                                                @if($f->codigo == 'L81.8')
                                                <td>
                                                    <select name="derivacion2" id="derivacion2" class="form-control">
                                                        <option value="">-Seleccionar-</option>
                                                            @foreach($nomcon as $c)
                                                            <option value="{{$c->id}}">{{$c->nombre}}</option>
                                                            @endforeach
                                                    </select>
                                                    <!-- <input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico2_{{$i}}" name="id_diagnostico2[]" value=""> -->
                                                </td>
                                                @endif
                                                <?php $i++; ?>
                                            @endif
                                    </tr>
                                    
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="GUARDAR" class="btn btn-primary pull-right">
                        </div>
                    </div>

                    <!-- ***************************** -->
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
</div>
</form>
@include('frontend.includes.footer')
@endsection