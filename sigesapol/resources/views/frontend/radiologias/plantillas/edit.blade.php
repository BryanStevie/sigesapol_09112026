@extends('frontend.layouts.master')
@section('renderjs')
<link rel="stylesheet" href="../../sceditor/minified/themes/office.min.css" id="theme-style" />
<script>
    $tipo = $("#frmfarmacia").find("#id_servicio");
    $tipo.select2();
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_plantilla_radiologias"><i class="fa fa-table fa-fw"></i> Tipo de Plantilla </a></li>
                <li class="active"><a href="/create_plantilla_radiologia"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Tipo de Plantilla</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_tipo_plantilla') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo de Plantilla</label>
                            <div class="col-lg-10">
                                <input name="nombre_plantilla" id="nombre_plantilla" type="text" class="form-control" required="" placeholder="Nombre de la Plantilla" value="{{$plantilla->nombre_plantilla}}"  onkeypress="return solo_letra(event)" autocomplete="OFF">
                                <input type="hidden" class="form-control" name="id"  value="{{$plantilla->id}}"> 
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>SERVICIO</label>
                                    <select class="form-control" name="id_servicio" id="id_servicio" required="">
                                        <option value="">Seleccionar</option>
                                        @foreach ( $lista_categorias as $lc )
                                        <option value="{{ $lc->id }}" @if ( $plantilla->id_categoria_radiologia == $lc->id ) selected @endif >{{ $lc->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Título de Plantilla</label>
                              <textarea id="titulo" required="" name="titulo" rows="2" style="height:300px;width:100%;">{{$plantilla->cabecera}}</textarea>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Descripción de Plantilla</label>
                              <textarea id="cuerpo_plantilla" required="" name="cuerpo_plantilla" rows="2" style="height:300px;width:100%;">{{$plantilla->cuerpo}}</textarea>
                            </div>
                          </div>
                        </div>
                        <!--div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Footer de Plantilla</label>
                              <textarea id="footer_plantilla" required="" name="footer_plantilla" rows="2" style="height:300px;width:100%;">{{$plantilla->pie_pagina}}</textarea>
                            </div>
                          </div>
                        </div-->
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_plantilla_radiologias" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('../../sceditor/minified/sceditor.min.js') }}"></script>
<script src="{{ asset('../../sceditor/languages/es.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/icons/monocons.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/formats/bbcode.js') }}"></script>
<script>
    var textarea = document.getElementById('titulo');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        style: '../../sceditor/minified/themes/content/default.min.css'
    });
    var textarea = document.getElementById('cuerpo_plantilla');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        style: '../../sceditor/minified/themes/content/default.min.css'
    });
    var textarea = document.getElementById('footer_plantilla');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        style: '../../sceditor/minified/themes/content/default.min.css'
    });
</script>
@include('frontend.includes.footer')
@endsection