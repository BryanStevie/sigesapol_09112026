@extends('frontend.layouts.master')

@section('renderjs')
<link href="{{ asset('css/mesa/solicitud_requerimiento.css') }}" rel="stylesheet">
<script src="{{ asset('js/mesa/solicitud_requerimiento.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="border-panel">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Registrar solicitud de requerimiento</div>                    
                    </div>     
                    <div style="padding-top:30px" class="panel-body" >
                        <div style="display:none" id="login-alert" class="alert alert-danger"></div>
                        <form name="" id="frmSolicitudReq" class="container" role="form" method="post" action="{{route('solicitud_requerimiento_transaccion')}}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Prioridad requerimiento <span class="mandatory-field">*</span></label>
                                    <select class="form-control" id="prio_requerimiento" name="prio_requerimiento" tabindex="5" style="width: 100%">
                                        {{-- <option value="" selected>-Seleccionar-</option>
                                        <option value="1">ALTA</option>
                                        <option value="3">MEDIA</option>
                                        <option value="3">BAJA</option> --}}
                                        <option value="" selected>-Seleccionar-</option>
                                        @foreach ($prioridades as $prioridad)
                                            <option value="{{$prioridad->id}}">{{$prioridad->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">IPRESS Origen <span class="mandatory-field">*</span></label>
                                    <select class="form-control" id="ipress" name="ipress" tabindex="5" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        @foreach ($establecimientos as $establecimiento)
                                            <option nivel="{{$establecimiento->nivel}}" value="{{$establecimiento->id}}" @if($medicoActivoByUserDni->id_establecimiento == $establecimiento->id) selected @endif>{{$establecimiento->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row group-2">
                                <div class="group-element col-sm-6">
                                    <label class="control-label">Tipo atencion <span class="mandatory-field">*</span></label>
                                    <select class="form-control" id="tipo_atencion" name="tipo_atencion" tabindex="5" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        <option value="1">AMBULATORIO</option>
                                        <option value="2">EMERGENCIA</option>
                                        <option value="3">HOSPITALIZACION</option>
                                    </select>
                                </div>
                                <div class="group-element col-sm-6">
                                    <label class="control-label">Servicio <span class="mandatory-field">*</span></label>
                                    <select class="form-control" id="servicio" name="servicio" tabindex="5" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        @foreach ($consultorios as $consultorio)
                                            <option value="{{$consultorio->id}}">{{$consultorio->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">DNI <span class="mandatory-field">*</span></label>
                                    <div class="col-lg-12" style="padding: 0;">
                                        <div class="col-lg-12" style="padding: 0;">
                                            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese su numero de DNI" aria-label="" aria-describedby="basic-addon2" value="{{$user->dni}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row group-2">
                                <div class="group-element col-lg-4">
                                    <label class="control-label">Nombres <span class="mandatory-field">*</span></label>
                                    <input readonly type="text" class="form-control fetch-ws" id="nombres" name="nombres" value="{{$user->name}}" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="group-element col-lg-4">
                                    <label class="control-label">Apellido paterno <span class="mandatory-field">*</span></label>
                                    <input readonly type="text" class="form-control fetch-ws" id="apaterno" name="apaterno" value="{{$user->paterno}}" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="group-element col-lg-4">
                                    <label class="control-label">Apellido materno <span class="mandatory-field">*</span></label>
                                    <input readonly type="text" class="form-control fetch-ws" id="amaterno" name="amaterno" value="{{$user->materno}}" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="control-label">CIP (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-ws" id="cip" name="cip" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="col-sm-5">
                                    <label class="control-label">Grado (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-ws" id="grado" name="grado" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="col-sm-1" style="align-self: flex-end;">
                                    <button class="btn btn-primary" type="button" id="btnBuscarDataUser">Buscar</button>
                                </div>
                            </div>
                            {{-- <div class="form-group row group-2">
                                <div class="group-element col-lg-3">
                                    <label class="control-label">CIP (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-ws" id="cip" name="cip" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="group-element col-lg-6">
                                    <label class="control-label">Grado (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-ws" id="grado" name="grado" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="">
                                    <button class="btn btn-success" type="button" id="btnBuscarDataUser">Buscar</button>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Email <span class="mandatory-field">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" placeholder="Ingrese su email" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular" value="{{$user->celular}}" placeholder="Ingrese su numero de celular" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="form-group row group-2">
                                <div class="group-element col-lg-6">
                                    <label class="control-label">Colegiatura (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-bd" id="colegiatura" name="colegiatura" value="{{$medicoActivoByUserDni->colegiatura}}" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                                <div class="group-element col-lg-6">
                                    <label class="control-label">RNE (opcional)</label>
                                    <input readonly type="text" class="form-control fetch-bd" id="rne" name="rne" value="{{$medicoActivoByUserDni->rne}}" placeholder="" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Especialidad <span class="mandatory-field">*</span></label>
                                    <select disabled class="form-control" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        @foreach ($especializaciones as $especialidad)
                                            <option value="{{$especialidad->id}}"  @if($medicoActivoByUserDni->id_especializacion == $especialidad->id) selected @endif>{{$especialidad->nombre}}</option>
                                        @endforeach
                                    </select>
                                    <input hidden type="text" id="especialidad" name="especialidad" placeholder="" value="{{$medicoActivoByUserDni->id_especializacion}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Asunto del requerimiento <span class="mandatory-field">*</span></label>
                                    <input type="text" class="form-control" id="asunto" name="asunto" value="" placeholder="Ingrese el asusnto de la solicitud" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="group-element">
                                    <label class="control-label">Descripcion de requerimiento <span class="mandatory-field">*</span></label>
                                    <textarea name="desc_requerimiento" id="desc_requerimiento" class="form-control" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 group-sumbit">
                                <input type="button" value="Guardar" class="btn btn-success" id="btnReg">
                            </div>
                        </form>     
                    </div>                     
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
