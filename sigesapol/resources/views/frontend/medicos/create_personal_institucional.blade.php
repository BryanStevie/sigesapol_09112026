@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .filterable {
        margin-top: 0px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }

</style>

<script>
    function verpersonal() {
        dni = $("#frmfarmacia").find("#dni_buscar").val();
        //alert(dni);
             
        option = {                
                url: '/verpersonal/' + dni,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
                       
        $.ajax(option).done(function (data) {
            var existe=data.length;
            if(existe>0){
            $(data).each(function (ii, oo) { 
                $("#frmfarmacia").find("#nombre").val(oo.nomtitular);
                $("#frmfarmacia").find("#paterno").val(oo.apepattitular);
                $("#frmfarmacia").find("#materno").val(oo.apemattitular);
                $("#frmfarmacia").find("#dni").val(oo.nrodoctitular);
                $("#frmfarmacia").find("#dni_buscar").val("");
                $("#frmfarmacia").find("#cmp").focus();
            });  
            }else{
                alert("No se encuentra en la BD, ingrese manualmente al Profesional de Salud");
                $("#frmfarmacia").find("#dni_buscar").val("");
                 $("#frmfarmacia").find("#nombre").val("");
                $("#frmfarmacia").find("#paterno").val("");
                $("#frmfarmacia").find("#materno").val("");
                $("#frmfarmacia").find("#dni").val(dni);
                $("#frmfarmacia").find("#nombre").focus();
            }                                   
        });

    }

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a></li>
                <li class="active"><a href="/create_personal_institucional/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo </a></li>
                <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
                <!--<li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
                <!--<li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Personal de Salud</h3>

                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_medico') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">DNI</label>  
                                    <div class="col-md-4">
                                        <input id="dni_buscar" name="dni_buscar" type="text" placeholder="" class="form-control input-md" maxlength="8">
                                    </div>
                                    <div class="col-md-4">                                        
                                        <button  class="btn btn-info" type="button" onclick="verpersonal()">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>DNI</label>
                                    <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" required="" maxlength="8">
                                    <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
                                </div>
                                <div class="col-md-6">
                                    <label>Nombre</label>
                                    <input id="nombre" name="nombre" type="text" placeholder="" class="form-control input-md" required="" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-6">
                                    <label>Apellido Paterno</label>
                                    <input id="paterno" name="paterno" type="text" placeholder="" class="form-control input-md" required="" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-6">
                                    <label>Apellido Materno</label>
                                    <input id="materno" name="materno" type="text" placeholder="" class="form-control input-md" required="" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-6">
                                    <label>Colegio Médico del Perú</label>
                                    <input id="colegiatura" name="colegiatura" type="text" placeholder="" class="form-control input-md">
                                </div>
                                <div class="col-md-6">
                                    <label>RNE</label>
                                    <input id="rne" name="rne" type="text" placeholder="" class="form-control input-md">
                                </div>
                                <div class="col-md-6">
                                    <label>Especialidad</label>
                                    <input id="especialidad" name="especialidad" type="text" placeholder="" class="form-control input-md" required="" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-6">
                                    <label>E-mail</label>
                                    <input id="email" name="email" type="email" placeholder="" class="form-control input-md">
                                </div>
                                <div class="col-md-12">
                                    <label>Establecimiento (IPRESS PNP)</label>
                                    <select name="id_establecimiento" class="form-control" required="">
                                        @foreach($establecimiento as $e)
                                        <option value="{{$e->id}}">{{$e->nombre}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                    <a href="/gestion_medicos/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                    <input type="submit" class="form-control btn btn-success" value="Guardar">
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection