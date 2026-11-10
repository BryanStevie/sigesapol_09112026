@extends('frontend.layouts.master')
@section('renderjs')

@endsection
@section('content')
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
                    <a href="/cierre_ficha" class="btn btn-sm btn-info pull-right"> <i class="fa fa-arrow-left"></i> Regresar </a>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info ">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$dato}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="FECHA Y HORA REGISTRO">
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
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N° @if(isset($local)) {{$local->codigo}} @endif - @if(isset($procesos)) {{$procesos->abreviatura}} @endif  - @if(isset($personal)) {{$personal->id}} @endif - 2023</label>
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
                                        <input value="@if(isset($triaje)) {{$triaje->historia_clinica_ipress}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">HISTORIA CLINICA</label>
                                        <input value="@if(isset($triaje)) {{$triaje->historia_clinica}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">EDAD</label>
                                        <input value="@if(isset($personal)) {{$personal->edad}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
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
                            <h3 class="panel-title">INFORMACION DEL MEDICO</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                           
                            <div class="row">
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" >APE MATERNO</label>
                                        <input value="@if(isset($derivador)){{$derivador->paterno}} {{$derivador->materno}}  {{$derivador->name}}@endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">DIAGNOSTICOS</h3>
                        </div>
                        <div class="panel-body ">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="10%">CODIGO</th>
                                        <th width="40%">DIAGNOSTICOS</th>
                                        <th width="20%">SERVICIO A DERIVAR</th>
                                        @if ($existederivacion2>0)
                                        <th width="20%">SERVICIO A DERIVAR 2</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @foreach($nomdiag as $f)
                                    <tr>
                                        <input type="hidden" name="id_fema_diagnostico" id="id_fema_diagnostico" value="{{$f->id}}">
                                             @if($f->estado_fema == '1')
                                                <td><input value="{{$f->codigo}}" type="text" name="codigo_diagnostico" id="codigo_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>

                                                <td><input value="{{$f->nombre}}" type="text" name="nombre_diagnostico" id="nombre_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>
                                             
                                                <td><input value="{{$f->nombre_consultorio1}}" type="text" name="nombre_diagnostico" id="nombre_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>
                                                @if ($existederivacion2>0)
                                                @if ($f->id_servicio_derivacion2!='0')
                                                <td><input value="{{$f->nombre_consultorio2}}" type="text" name="nombre_diagnostico" id="nombre_diagnostico" class="form-control" readonly="" placeholder="" tabindex=""></td>
                                                @endif
                                                @endif
                                                @endif
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!--input type="submit" value="Cerrar" class="btn btn-primary pull-right"-->
                            <a target="_blank" href="/verderivacion/verpdfderivado/{{$personal->id}}" class="btn btn-sm btn-danger pull-right"> <i class="fa fa-file-pdf-o"></i> Ver PDF </a>
                        </div>
                        
                    </div>

                    <!-- ***************************** -->
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
</div>
@include('frontend.includes.footer')
@endsection
