@extends('frontend.layouts.master')
@section('renderjs')
<script>

</script>
<style>
    .ancho{width: 85px}
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR STOCK INICIAL</h3>                    
                </div>
                <div class="panel-body">
                    <form id="frm_carga" method="POST" action="{{ route('add_carga_inicial_personalizado')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 etiqueta">                                
                            <label>Farmacia</label>
                            <input value="{{$farmacia->nombre}}" class="form-control" name="nombre_farmacia" type="text" readonly="">
                            <input value="{{ Auth::user()->id_farmacia }}" class="form-control" name="id_farmacia" type="hidden">
                            <input value="{{ Auth::user()->id }}" class="form-control" name="id_user" type="hidden">
                        </div>
                        <div class="col-md-4 etiqueta">                                
                            <label>Fecha Carga Inicial</label>
                            <input value="" class="form-control datepicker" name="fecha_corte" type="date" required="">
                        </div>
                        <div class="col-md-4 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection