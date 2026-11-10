@extends('frontend.layouts.master')
@section('renderjs')
<script>
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/add_consultorios_servicio/{{ $subconsultorio->id_consultorio }}"><i class="fa fa-table fa-fw"></i> Consultorios </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Consultorio {{$subconsultorio->descripcion}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmConsultorio" class="form-horizontal" role="form" method="POST" action="{{ route('update_consultorios_servicio') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$subconsultorio->id}}"> 
                        <input type="hidden" class="form-control" name="proviene"  value="1"> 

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo de Atencion</label>
                            <div class="col-lg-10">
                               <!-- <input  name="id_tipo_atencion_1" type="text" class="form-control" value="{{$subconsultorio->id_tipo_atencion_1}}" required="" placeholder="Consultorio" autocomplete="off">
                                    -->

                                    <select name="id_tipo_atencion_1" id="id_tipo_atencion_1" class="form-control" required="" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($tipo_atenciones as $f)
                                                    <option value="{{$f->id}}" <?php if($f->id == $subconsultorio->id_tipo_atencion_1){ ?>selected <?php } ?> >{{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Consultorio</label>
                            <div class="col-lg-10">
                                <input  name="consultorio" type="text" class="form-control" value="{{$subconsultorio->descripcion}}" required="" placeholder="Consultorio" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Abreviatura</label>
                            <div class="col-lg-10">
                                <input name="abreviatura" type="text" class="form-control" value="{{$subconsultorio->abreviatura}}" required="" placeholder="Abreviatura">             
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tiempo Atención</label>
                            <div class="col-lg-10">
                                <input name="tiempo_atencion" type="text" class="form-control" value="{{$subconsultorio->tiempo_atencion}}" required="" placeholder="Abreviatura">             
                            </div>
                        </div>
                        @if($subconsultorio->id_establecimiento==76)
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Descripción para TV</label>
                                <div class="col-lg-10">
                                  <input name="descripcion_tv" type="text" class="form-control" autocomplete="off" value="{{$subconsultorio->descripcion_tv}}">
                                </div>
                            </div>
                            <div class="form-group">
                              <label class="col-lg-2 control-label">Consultorio permite cupo adicional</label>
                              <div class="col-lg-10">
                                <input type="checkbox" value="1" <?php if($subconsultorio->adicional == 1)echo 'checked="checked"';?> name="adicional" />
                              </div>
                          </div>
                        @endif
                        @if (!empty($pp_habilitado))
                        <div class="form-group">
                          <label class="col-lg-2 control-label">Consultorio de procedimientos programados</label>
                          <div class="col-lg-10">
                            <input type="checkbox" value="1" name="flag_procedimiento" <?php if(isset($subconsultorio->flag_procedimiento) && $subconsultorio->flag_procedimiento == 1)echo 'checked="checked"';?> />
                          </div>
                        </div>
                        @endif
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/add_consultorios_servicio/{{ $subconsultorio->id_consultorio }}" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection