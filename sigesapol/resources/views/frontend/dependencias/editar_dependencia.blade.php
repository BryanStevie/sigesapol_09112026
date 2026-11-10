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
                    <h3 class="panel-title">Editar Dependencia de Salud</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ route('update_dependencia')}}" id="frmlotizar" name="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id_dependencia" name="id_dependencia" value="{{$dependencia->id}}" type="hidden">
                        <div class="col-md-12">
                            <label>Nombre o Descripcion de la Dependencia</label>
                            <textarea id="nombre_dependencia" name="nombre_dependencia" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" ><?php echo $dependencia->nombre_dependencia; ?>
                            </textarea> 
                        </div>
                        <div class="col-md-6">
                            <br/>
                            <label>Opcion</label>
                            <select name="id_opcion" autofocus  tabindex="1" id="id_opcion" class="form-control" required="">
                                <option value="" disabled selected style="color: #888888">Seleccionar</option>
                                <option value="I" <?php if ('I' == $dependencia->opcion) echo "selected='selected'"; ?>> INGRESO</option>
                                <option value="S"<?php if ('S' == $dependencia->opcion) echo "selected='selected'"; ?>>SALIDA</option>
                                <option value="A"<?php if ('A' == $dependencia->opcion) echo "selected='selected'"; ?>>AMBOS</option>                                                
                            </select>
                        </div>  
                        <div class="col-md-12">
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <a href="/listar_dependencias" class="form-control btn btn-danger" >Cancelar</a>
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
