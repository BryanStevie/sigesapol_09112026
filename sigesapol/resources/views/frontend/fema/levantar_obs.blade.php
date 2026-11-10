@extends('frontend.layouts.master')
@section('renderjs')

@endsection
@section('content')

<form id="" class="" role="form" method="post"  action="{{ route('levantar_observacion')}}" >  
  <input type="hidden" name="_token" value="{{csrf_token()}}">
  <input type="hidden" name="nro_doc_ident" value="{{$personal->nro_doc_ident}}">
  <input type="hidden" name="id_prestacion" value="@if(isset($personal)) {{$personal->id}} @endif">
  <input type="hidden" name="dni" value="{{$profesional->dni}}">
  <input type="hidden" name="id_profesional" value="{{$profesional->id}}">
    <div class="row ">
        <div class="col-md-1"> </div>
        <div class="col-md-10" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">LEVANTE DE OBSERVACIÓN </h3>
                    <a href="/cierre_ficha" class="btn btn-sm btn-info pull-right"> <i class="fa fa-arrow-left"></i> Regresar </a>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info ">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N°  @if(isset($profesional)) {{$profesional->id_establecimiento}} @endif - @if(isset($procesos)) {{$procesos->abreviatura}} @endif  - @if(isset($personal)) {{$personal->id}} @endif - @if(isset($personal)) {{$personal->dato_year_ficha}} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">DATOS DE REGISTROS</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-3">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <input value="@if(isset($procesos)) {{$procesos->nombre}} @endif" type="text" name="hora_registro_general" id="hora_registro_general"
                                            class="form-control" readonly="" tabindex="" placeholder="TIPO PROCESO">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <input value="@if(isset($subprocesos)) {{$subprocesos->nombre}} @endif" type="text" name="nombre_ipress_general" id="nombre_ipress_general"
                                            class="form-control" readonly="" tabindex="" placeholder="SUB PROCESO">    
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <input value="@if(isset($date)) {{$date}} @endif" type="text" name="tipo_proceso" id="tipo_proceso" class="form-control"
                                            readonly="" tabindex="" placeholder="FECHA ATENCION">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input value="@if(isset($establecimiento)) {{$establecimiento->nombre}} @endif " type="text" name="hora_registro_general" id="hora_registro_general" class="form-control" readonly="" tabindex="" placeholder="NOMBRE ESTABLECIMIENTO">
                                    <!-- <br> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACION PERSONAL</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° DOCUMENTO</label>
                                            <input value="@if(isset($personal)) {{$personal->nro_doc_ident}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
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
                                    <div class="col-md-4">  
                                        <div class="form-group">
                                            <label for="">SEXO</label>
                                            <input value="@if(isset($personal)) @if($personal->sexo=='M') MASCULINO @else FEMENINO @endif @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACION DEL RESPONSABLE DEL LEVANTAMIENTO DE LA OBSERVACION FEMA</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TIPO DOCUMENTO</label>
                                        <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >N° DOCUMENTO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->dni}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >NOMBRES</label>
                                        <input value="@if(isset($profesional)) {{$profesional->name}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >APE PATERNO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->paterno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >APE MATERNO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->materno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >N° CIP</label>
                                        <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACION A REGISTRAR</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="" >Establecimiento donde se evaluo y se emitio la constancia:</label>
                                        <select name="localle" id="localle" class="form-control">
                                                <option value="">-Seleccionar-</option>
                                                @foreach($local as $c)
                                                <option value="{{$c->id}}">{{$c->nombre}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >Fecha de Evaluacion</label>
                                        <input type="date" name="fechaeva" id="fechaeva" class="form-control" placeholder="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="" >Servicio</label>
                                        <select name="serviciole" id="serviciole" class="form-control">
                                                <option value="">-Seleccionar-</option>
                                                @foreach($servicio as $c)
                                                <option value="{{$c->id}}">{{$c->nombre}}</option>
                                                @endforeach
                                            </select>  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <label for="" ><br> Ingrese los datos del Medico que realizo la evaluacion y emitio la constancia<br></label>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >APE PATERNO</label>
                                        <input type="text" name="paternodoc" id="paternodoc" class="form-control" placeholder="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >APE MATERNO</label>
                                        <input type="text" name="maternodoc" id="maternodoc" class="form-control" placeholder="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >NOMBRE</label>
                                        <input type="text" name="nombredoc" id="nombredoc" class="form-control" placeholder="" tabindex="">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="" >Ingrese motivo de la constancia</label>
                                </div>
                                <div class="col-md-3">
                                    <select name="etapatratamiento" id="etapatratamiento" class="form-control">
                                        <option value="">-Seleccionar-</option>
                                        <option value="1"> Tratamiento en proceso </option>
                                        <option value="2"> Tratamiento concluido </option>                                      
                                    </select> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" ><br> Observacion<br></label>
                                    <input type="text" name="obslevantar" id="obslevantar" class="form-control" placeholder="" tabindex=""> 
                                </div>
                            </div>
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
    </form>
@include('frontend.includes.footer')
@endsection
