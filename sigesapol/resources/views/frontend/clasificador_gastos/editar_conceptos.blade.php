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
                    <h3 class="panel-title">Editar Concepto</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ route('update_concepto')}}" id="frmlotizar" name="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id_concepto" name="id_concepto" value="{{$conceptos->id}}" type="hidden">

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Nombre o Descripcion del Concepto</label>
                                <input name="descripcion_concepto" value="{{ $conceptos->descripcion_conceptos }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>  
                            <div class="col-md-6">
                                <label>Tipo de Conceptos</label>
                                    <select class="form-control" id="tipo" name="tipo" style="width: 100%" required="">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="I" <?php if($conceptos->opcion=='I')echo "selected='selected'";?> >INGRESO</option>
                                        <option value="S" <?php if($conceptos->opcion=='S')echo "selected='selected'";?> >SALIDA</option>
                                        <option value="A" <?php if($conceptos->opcion=='A')echo "selected='selected'";?> >AMBOS</option>
                                    </select>                                     
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
