@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>

<script>

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a></li>
                <li class="active"><a href="/create_personal_institucional/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>
                <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
                <li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>
                <li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Personal de Salud</h3>                      
                    </div>
                    <div style="margin-top: 20px; margin-bottom: 20px">
                        <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_medico') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <!-- Text input-->                               
                                <div class="col-md-6">
                                    <a href="/create_personal_institucional/{{Auth::user()->id}}" class="form-control btn btn-success"><i class="fa fa-search"></i> Personal Institucional</a>
                                </div>  
                                <div class="col-md-6">
                                    <a href="/create_medico/{{Auth::user()->id}}" class="btn btn-info form-control"><i class="fa fa-user"></i> Personal Contratado</a>
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