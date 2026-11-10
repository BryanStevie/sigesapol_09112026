@extends('frontend.layouts.master')
@section('renderjs')

@endsection
@section('content')

<form id="" class="" role="form" method="post"  action="{{ route('final_fema')}}" >  
  <input type="hidden" name="_token" value="{{csrf_token()}}">
  <input type="hidden" name="nro_doc_ident" value="{{$personal->nro_doc_ident}}">
  <input type="hidden" name="id_prestacion" value="@if(isset($personal)) {{$personal->id}} @endif">
  <input type="hidden" name="dni" value="{{$profesional->dni}}">
  <input type="hidden" name="id_profesional" value="{{$profesional->id}}">
  <input type="hidden" name="local_cierre" value="{{$local->id}}">
    <div class="row ">
        <div class="col-md-1"> </div>
        <div class="col-md-10" >
       
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">CIERRE DE FICHAS</h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info ">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N°  @if(isset($locales)) {{$locales->codigo}} @endif - @if(isset($procesos)) {{$procesos->abreviatura}} @endif  - @if(isset($personal)) {{$personal->id}} @endif - 2023</label>
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
                                            <label for="">CIP</label>
                                            <input value="@if(isset($personal)) {{$personal->carnet}} @endif" type="text" name="carnet" id="carnet" class="form-control" readonly="" tabindex="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">GRADO</label>
                                            <input value="@if(isset($personal)) {{$personal->grado}} @endif" type="text" name="grado" id="grado" class="form-control" readonly="" tabindex="" placeholder="">
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
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">UNIDAD DE APERTURA</label>
                                            <input value="@if(isset($localff)) {{$localff->nombre}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA/HORA DE APERTURA</label>
                                            <input value="@if(isset($personal)) {{$personal->created_at}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div> 
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">UNIDAD DE CONCLUSION</label>
                                            <input value="@if(isset($local)) {{$local->nombre}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div> 
                                    @if($cerrado > 0)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA/HORA DE CONCLUSION</label>
                                            <input value="@if(isset($personal)) {{$personal->cierre_updated_at}} @endif" type="text" name="fecha_cierre" id="fecha_cierre" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div> 
                                    @else
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA/HORA DE CONCLUSION</label>
                                            <input value="@if(isset($date)) {{$date}} @endif" type="text" name="fecha_cierre" id="fecha_cierre" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>  
                                    @endif  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <img src="data:image/png;base64,<?php echo $personal->foto?>" title="foto reniec" width="210px" alt=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                   @if($cerrado > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <a href="verpdfcierre/{{$personal->id}}" class="btn btn-danger pull-right"> Ver PDF </a>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="GUARDAR" class="btn btn-primary pull-right">
                        </div>
                    </div>
                    @endif

                    
                    <!-- ***************************** -->
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
    </form>
@include('frontend.includes.footer')
@endsection
