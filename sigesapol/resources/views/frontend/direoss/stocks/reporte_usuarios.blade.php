@extends('frontend.layouts.master')
@section('renderjs')
<script>

</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Personal Usuario del Sistema</h3>
                    <div class="pull-right">                        
                        <a class="btb btn-default btn-xs" href="/allFarmacias_withStocks">
                            <i class="fa fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('usuarios_por_ipress')}}" id="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend> Por Centro de Atenci&oacuten</legend>
                        <div class="col-md-10" style="margin-bottom: 15px">                            
                            <select name="id_farmacia" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach($farmacias as $f)
                                <option value="{{$f->id}}">{{$f->nombre}}</option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="col-md-2" style="margin-bottom: 15px">
                            <input class="btn btn-primary form-control" value="Buscar" type="submit">
                        </div>
                    </form>
                    <form>
						<!--
                        <legend>En General</legend>                                            
                        <div class="col-md-2">
                            <a class="btn btn-success form-control" href="/usuarios_all_ipress_excel">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </a>
                        </div>
						-->
                    </form>
                </div>
            </div>
        </div>       
    </div>
</div>

@include('frontend.includes.footer')
@endsection
