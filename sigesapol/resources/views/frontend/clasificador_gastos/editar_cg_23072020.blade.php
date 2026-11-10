@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>
function jsDecimals(e) {

        var evt = (e) ? e : window.event;
        var key = (evt.keyCode) ? evt.keyCode : evt.which;
        if (key != null) {
            key = parseInt(key, 10);
            if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
                //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
                if (!jsIsUserFriendlyChar(key, "Decimals")) {
                    return false;
                }
            } else {
                if (evt.shiftKey) {
                    return false;
                }
            }
        }
        return true;
    }
    
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Tipo de Proceso</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ route('update_cg')}}" id="frmlotizar" name="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id_cg" name="id_cg" value="{{$cg->id}}" type="hidden">

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Nombre o Descripcion de Clasificador de Gastos</label>
                                <input name="descripcion_cg" value="{{ $cg->descripcion_cg }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>  
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <a href="/listar_cgs" class="form-control btn btn-danger" >Cancelar</a>
                            </div>

                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <input id="btnReg" class="btn btn-success form-control" value="Actualizar" type="submit">
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
