@extends('frontend.layouts.master')

@section('rendercss')
<style>
  textarea {
    resize: none;
  }
</style>
@endsection
@section('renderjs')
<style>

</style>
<script src='../../funciones/function.js'></script>
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
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Editar Establecimiento de Salud</h3>
        </div>
        <div class="panel-body">
          <form method="post" action="{{ route('update_establecimiento')}}" id="frmlotizar" name="frmlotizar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input id="id_establecimiento" name="id_establecimiento" value="{{$establecimiento->id}}" type="hidden">
            <div class="row">
              <div class="form-group col-md-4">
                <label>Código</label>
                <input type="text" tabindex="1" class="form-control" onkeypress="return noEspaciosInicioSoloNumero(event,'selector_codigo_establecimiento');" id="selector_codigo_establecimiento" name="codigo" required="" value="<?php echo $establecimiento->codigo; ?>" placeholder="Código" maxlength="8">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label>Nombre o Descripcion del Establecimiento</label>
                <textarea id="nombre_establecimiento" onkeypress="return noEspaciosInicioSoloLetras(event,'nombre_establecimiento');" name="nombre_establecimiento" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"><?php echo $establecimiento->nombre; ?></textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label>Dirección del establecimiento</label>
                <input type="text" tabindex="3" class="form-control" id="direccion_establecimiento" onkeypress="return noEspaciosInicioSoloLetras(event,'direccion_establecimiento');" name="direccion_establecimiento" value="<?php echo $establecimiento->direccion_establecimiento; ?>" required="" placeholder="Dirección del establecimiento" maxlength="100">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label>Ubigeo</label>
                <input type="text" class="form-control" id="ubigeo_establecimiento" onkeypress="return noEspaciosInicioSoloNumero(event,'ubigeo_establecimiento');" name="ubigeo" tabindex="3" value="<?php echo $establecimiento->ubigeo; ?>" placeholder="Ubigeo" maxlength="6">
              </div>
              <div class="form-group col-md-4">
                <label>Red / Región</label>
                <input type="text" class="form-control" id="red_establecimiento"  onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'red_establecimiento');" name="red" value="<?php echo $establecimiento->red_region; ?>" tabindex="4" placeholder="Red / Región" maxlength="100">
              </div>
              <div class="form-group col-md-4">
                <label>Nivel</label>
                <select name="id_nivel" autofocus tabindex="5" id="id_nivel" class="form-control" required>
                  <option value="0" <?php if ('0' == $establecimiento->nivel) echo "selected='selected'"; ?>> 0</option>
                  <option value="1" <?php if ('1' == $establecimiento->nivel) echo "selected='selected'"; ?>>1</option>
                  <option value="2" <?php if ('2' == $establecimiento->nivel) echo "selected='selected'"; ?>>2</option>
                  <option value="3" <?php if ('3' == $establecimiento->nivel) echo "selected='selected'"; ?>>3</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label>Intervalo de días en cita</label>
                <input type="text" tabindex="5" class="form-control" name="intervalo_cita" value="<?php echo $establecimiento->intervalo_cita; ?>" maxlength="1" required>
              </div>
              <div class="form-group col-md-4">
                <label>Firma Electronica</label></br>
                <input type="checkbox" value="1" name="flag_firma_electronica" <?php if($establecimiento->flag_firma_electronica == true)echo 'checked="checked"';?>/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <input id="btnReg" class="btn btn-success form-control" value="Actualizar" type="submit">
              </div>
              <div class="form-group col-md-6">
                <a href="/listar_establecimientos" class="form-control btn btn-danger">Cancelar</a>
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