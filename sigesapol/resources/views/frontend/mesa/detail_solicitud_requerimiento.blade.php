@extends('frontend.layouts.master')

@section('renderjs')
<link href="{{ asset('css/mesa/detail_solicitud.css') }}" rel="stylesheet">
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>

<script src="{{ asset('js/mesa/detail_requerimiento.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">DETALLE DE REQUERIMIENTO</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group app-form-group-span">
                                        <label for="" class="encabezado">Prioridad de requerimiento</label>
                                        <span class="form-control @if($requerimiento->prioridad->id == 1){{'alta'}}@elseif($requerimiento->prioridad->id == 2){{'media'}}@elseif($requerimiento->prioridad->id == 3){{'baja'}}@endif">{{$requerimiento->prioridad->nombre}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group app-form-group-span">
                                        <label for="">IPRESS Origen</label>
                                        <span class="form-control">{{$requerimiento->ipress->nombre}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group app-form-group-span">
                                        <label for="">Tipo de atencion</label>
                                        <span class="form-control">{{$requerimiento->tipoAtencion->nombre}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group app-form-group-span">
                                        <label for="">Servicio</label>
                                        <span class="form-control">{{$requerimiento->servicio->nombre}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-data">
                                        <label class="encabezado">Datos del usuario solicitante</label>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">DNI</label>
                                                    <span class="form-control">{{$requerimiento->dni}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Nombres</label>
                                                    <span class="form-control">{{$requerimiento->nombres}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Apellido paterno</label>
                                                    <span class="form-control">{{$requerimiento->paterno}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Apellido materno</label>
                                                    <span class="form-control">{{$requerimiento->materno}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Email</label>
                                                    <span class="form-control">{{$requerimiento->email}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Celular</label>
                                                    <span class="form-control">{{$requerimiento->celular}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">CIP</label>
                                                    <span class="form-control">{{$requerimiento->cip}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Grado</label>
                                                    <span class="form-control">{{$requerimiento->grado}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Colegiatura</label>
                                                    <span class="form-control">{{$requerimiento->colegiatura}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">RNE</label>
                                                    <span class="form-control">{{$requerimiento->rne}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group app-form-group-span">
                                                    <label for="">Especializacion</label>
                                                    <span class="form-control">{{$requerimiento->especializacion->nombre}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group app-form-group-span">
                                        <label for="" class="encabezado">Asunto de requerimiento</label>
                                        <span style="padding: 8px" class="form-control">{{$requerimiento->asunto}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group app-form-group-span">
                                        <label for="" class="encabezado">Descripcion del requerimiento</label>
                                        <div class="req-descripcion">{{$requerimiento->descripcion}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <form hidden action="" id="frmBloque">
                                    <input type="hidden" id="flagServicioFirma" value="" />
                                    <input type="hidden" id="argumentos" value="" />
                                    <input type="hidden" id="doc_firmar" value="" />
                                    <input type="hidden" id="opt_firma" value="" />
                                    <input hidden id="id_req" name="id_req" type="text" value="{{$requerimiento->id}}">
                                    <input hidden id="ipress" name="ipress" type="text" value="{{$requerimiento->id_establecimiento}}">
                                    <input hidden id="tipo_atencion" name="tipo_atencion" type="text" value="{{$requerimiento->id_tipo_atenciones}}">
                                    <input hidden id="servicio" name="servicio" type="text" value="{{$requerimiento->id_consultorio}}">
                                    <input hidden id="dni" name="dni" type="text" value="{{$requerimiento->dni}}">
                                    <input hidden id="id_requerimiento" name="id_requerimiento" type="text" value="{{$requerimiento->id}}">
                                    <input hidden id="nombres" name="nombres" type="text" value="{{$requerimiento->nombres}}">
                                    <input hidden id="paterno" name="paterno" type="text" value="{{$requerimiento->paterno}}">
                                    <input hidden id="materno" name="materno" type="text" value="{{$requerimiento->materno}}">
                                    <input hidden id="cip" name="cip" type="text" value="{{$requerimiento->cip}}">
                                    <input hidden id="grado" name="grado" type="text" value="{{$requerimiento->grado}}">
                                    <input hidden id="email" name="email" type="text" value="{{$requerimiento->email}}">
                                    <input hidden id="celular" name="celular" type="text" value="{{$requerimiento->celular}}">
                                    <input hidden id="colegiatura" name="colegiatura" type="text" value="{{$requerimiento->colegiatura}}">
                                    <input hidden id="rne" name="rne" type="text" value="{{$requerimiento->rne}}">
                                    <input hidden id="id_especializacion" name="id_especializacion" type="text" value="{{$requerimiento->id_especializacion}}">
                                    <input hidden id="descripcion" name="descripcion" type="text" value="{{$requerimiento->descripcion}}">
                                    <input hidden id="id_prioridad_requerimiento" name="id_prioridad_requerimiento" type="text" value="{{$requerimiento->id_prioridad_requerimiento}}">
                                    <input hidden id="created_at" name="created_at" type="text" value="{{$requerimiento->created_at}}">
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="divFirmar">
                                        @if (Auth::user()->status_token==1)
                                            <input class="btn btn-info input-sm pull-right" value="FINALIZAR" type="button" onclick="firmarsintokens()" style="margin-left:20px" />
                                        @else
                                            <input class="btn btn-info input-sm pull-right" value="FIRMAR" type="button" id="btnfirmar" style="margin-left:20px" />
                                        @endif
                                        <a class="btn btn-primary btn-sm hidden-print pull-right" href="/mesa/all_solicitudes_requerimiento"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                                    </div>
                                </div>
                            </div>
                            <div id="addComponent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
@endsection
