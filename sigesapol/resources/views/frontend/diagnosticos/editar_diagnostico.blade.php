@extends('frontend.layouts.master')
@section('renderjs')
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
                    <h3 class="panel-title">Editar Diagnosticos</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('update_diagnostico')}}" id="frmlotizar" name="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id" name="id" value="{{$diagnostico->id}}" type="hidden">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Nombre o Descripcion del Diagnostico</label>
                                <input name="nombre" value="{{ $diagnostico->nombre }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>  
                            <div class="col-md-2">
                                    <label>Abreviatura</label>
                                    <input name="codigo" value="{{ $diagnostico->codigo }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="col-md-4">
                                    <label>Genero</label>
                                    <select class="form-control" id="genero" name="genero" tabindex="5" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        <option value="1" <?php if($diagnostico->genero == 1)echo "selected='selected'";?>>MASCULINO</option>
                                        <option value="2" <?php if($diagnostico->genero == 2)echo "selected='selected'";?>>FEMENINO</option>
                                        <option value="3" <?php if($diagnostico->genero == 3)echo "selected='selected'";?>>AMBOS</option>
                                    </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <a href="/listar_diagnosticos" class="form-control btn btn-danger" >Cancelar</a>
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
