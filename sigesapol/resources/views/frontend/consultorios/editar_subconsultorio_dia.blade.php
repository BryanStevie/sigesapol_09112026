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
        <div class="col-md-12">
            <div class="panel panel-primary" >
                <div class="panel-heading" style="height: 4rem">
                    <div class="pull-left">
                        <h3 class="panel-title">Editar Consultorio 7-C1-PIP I</h3>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_consultorio_turnos_dia/{{$id_sub_consultorio.'/'.$id_servicio}}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmConsultorio" class="form-horizontal" role="form" method="POST" action="{{route('update_subconsultorios_turnos')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id_subconsultorio_turno" value="{{$id_subconsultorio_turno}}"> 
                        <input type="hidden" class="form-control" name="id_sub_consultorio" value="{{$id_sub_consultorio}}"> 
                        <input type="hidden" class="form-control" name="id_servicio" value="{{$id_servicio}}">
                        <?php 
                            $hi = new DateTime($hora_ini); 
                            $hf = new DateTime($hora_fin);
                        ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Día</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_dia" id="id_dia" required="" style="width: 100%">
                                    @foreach ($diasDeLaSemana as $key => $value)
                                        @if (isset($keyDia) && $keyDia == $key)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option> 
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Turno</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_turno" id="id_turno" required="" style="width: 100%">
                                    <option value="0">-Seleccionar-</option>
                                    @foreach ($turno as $t)
                                        @if (isset($turno_selected) && $turno_selected == $t->descripcion)
                                            <option selected value="{{$t->id}}">{{$t->descripcion}}</option>
                                        @else
                                            <option value="{{$t->id}}">{{$t->descripcion}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Hora Inicio</label>
                            <div class="col-lg-8">
                                <input value="{{$hi->format('H:i:s')}}" name="hora_ini" class="form-control" required="" placeholder="Hora Inicio" type="time" maxlength="8">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Hora Fin</label>
                            <div class="col-lg-8">
                                <input value="{{$hf->format('H:i:s')}}" name="hora_fin" class="form-control" required="" placeholder="Hora Fin" type="time" maxlength="8">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Fecha de Inicio</label>
                            <div class="col-lg-4">
                            <input name="fecha_inicio" id= "fecha_inicio" type="text" data-date-start-date="0d" class="form-control datepicker" placeholder="Fecha Inicial" value="{{$fecha_inicio_turno == '0' ? '' : $fecha_inicio_turno}}">
                                            
                            </div>
                            <label class="col-lg-2 control-label">Fecha de Final</label>
                            <div class="col-lg-4">
                            <input name="fecha_final" type="text" data-date-start-date="0d"  class="form-control datepicker" placeholder="Fecha Finalizar"  autocomplete="off" value="{{$fecha_fin_turno == '0' ? '' : $fecha_fin_turno}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tiempo de Atención</label>
                            <div class="col-lg-8">
                              <input name="tiempo_atencion" type="text" class="form-control" autocomplete="off" value="{{$tiem_atencion}}">
                            </div>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/add_consultorio_turnos_dia/{{$id_sub_consultorio.'/'.$id_servicio}}" class="btn btn-danger">Cancelar</a>
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