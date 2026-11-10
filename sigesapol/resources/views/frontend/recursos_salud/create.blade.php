@extends('frontend.layouts.master')

@section('renderjs')
<script src='../../funciones/function.js'></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>

<script>
  
$(document).ready(function () {
    $("#id_ugipress").select2();
    $("#id_ipress").select2();
});


$(document).ready(function() {
    $("#frm_recurso_salud").submit(function() {

        // Habilitar campos deshabilitados antes de enviar
        $(this).find(":disabled").prop("disabled", false);
    });
});

$("#periodo").inputmask("99/9999", {
  placeholder: "MM/YYYY",
  showMaskOnHover: false
});

</script>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
        <div class="panel-heading">
          <div class="panel-title">Nuevo Recurso</div>
        </div>

        <div class="panel-body">
          <form id="frm_recurso_salud"
      method="POST"
      action="{{ $modo == 'editar' ? route('recursos_salud.update', $recurso->id) : route('recursos_salud.store') }}"
      class="form-horizontal">
  {{ csrf_field() }}

            {{ csrf_field() }}
             @if($modo == 'editar')
              <input type="hidden" name="id" value="{{ $recurso->id }}">
            @endif
  <input type="hidden" name="id_ipress_recurso_salud" value="{{ $id_ipress_recurso_salud }}">
            <!-- Fila: Fecha, UGIPRESS, IPRESS -->
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-md-2">
                <label>FECHA</label>
                 <input type="text" name="periodo" id="periodo" 
                  class="form-control"
                  value="{{ old('periodo', isset($recurso->periodo) ? $recurso->periodo : date('m/Y')) }}"
                  {{ $modo == 'ver' ? 'readonly' : 'required' }}
                  placeholder="MM/YYYY">
              </div>
              <div class="col-md-5">
                <label>UGIPRESS</label>
                <select name="id_ugipress" id="id_ugipress" class="form-control"
                        {{ $modo != 'crear' ? 'disabled' : 'required' }}>
                  @foreach($ugipress as $item)
                    <option value="{{ $item->id }}"
                      {{ (isset($recurso) && $recurso->id_ugipress == $item->id) ? 'selected' : '' }}>
                      {{ $item->codigo }} - {{ $item->nombre }}
                    </option>
                  @endforeach
                </select>
              </div>
                            <div class="col-md-5">
                <label>IPRESS</label>
                <select name="id_ipress" id="id_ipress" class="form-control" {{ $modo != 'crear' ? 'disabled' : '' }}>
  @foreach($establecimiento as $item)
    <option value="{{ $item->id }}"
      {{ (isset($recurso) && $recurso->id_ipress == $item->id) ? 'selected' : '' }}>
      {{ $item->codigo }} - {{ $item->nombre }}
    </option>
  @endforeach
</select>

              </div>
            </div>
<hr>

            <div class="row" style="margin-bottom: 15px;">
               <div class="col-md-3">
    <label>Consultorios Físicos</label>
    <input type="number" name="consultorio_fisico" class="form-control" min="0"
           value="{{ old('consultorio_fisico', isset($recurso->consultorio_fisico) ? $recurso->consultorio_fisico : '') }}"

           {{ $modo == 'ver' ? 'readonly' : 'required' }}>
  </div>
                
<div class="col-md-3">
                <label>Consultorios Funcionales</label>
                <input type="number" name="consultorio_funcional" class="form-control" min="0"
                       value="{{ old('consultorio_funcional', isset($recurso->consultorio_funcional) ?$recurso->consultorio_funcional: '') }}"
                       {{ $modo == 'ver' ? 'readonly' : 'required' }}>
              </div>

              <div class="col-md-3">
                <label>Camas Hospitalarias</label>
                <input type="number" name="camas_hospitalarias" class="form-control" min="0"
                       value="{{ old('camas_hospitalarias', isset($recurso->camas_hospitalarias) ?$recurso->camas_hospitalarias: '') }}"
                       {{ $modo == 'ver' ? 'readonly' : '' }}>
              </div>

               <div class="col-md-3">
                <label>Sillones Dentales</label>
                <input type="number" name="sillones_dentales" class="form-control" min="0"
                       value="{{ old('sillones_dentales', isset($recurso->sillas_dentales)?$recurso->sillas_dentales: '') }}"
                       {{ $modo == 'ver' ? 'readonly' : '' }}>
              </div>
</div>
              <div class="row" style="margin-bottom: 15px;">
              <div class="col-md-3">
                <label>Piezas de Mano</label>
                <input type="number" name="piezas_mano" class="form-control" min="0"
                       value="{{ old('piezas_mano', isset($recurso->piezas_mano) ? $recurso->piezas_mano: '') }}"
                       {{ $modo == 'ver' ? 'readonly' : '' }}>
              </div>
              <div class="col-md-3">
                <label>Ambulancias</label>
                <input type="number" name="ambulancias" class="form-control" min="0"
                       value="{{ old('ambulancias',isset($recurso->nro_ambulancias)? $recurso->nro_ambulancias: '') }}"
                       {{ $modo == 'ver' ? 'readonly' : '' }}>
              </div>
              <div class="col-md-3">
                <label>Salas Quirúrgicas Operativas</label>
                <input type="number" name="salas_quirurgicas" class="form-control" min="0"
                       value="{{ old('salas_quirurgicas', isset($recurso->nro_salas_quirurgicas) ?$recurso->nro_salas_quirurgicas:'') }}"
                       {{ $modo == 'ver' ? 'readonly' : '' }}>
              </div>
              <div class="col-md-3"></div>
            </div>

            <hr>
                <div class="row text-right">
              <div class="col-md-12">
                @if($modo == 'ver')
  <a href="/listar_recursos_salud" class="btn btn-default">Volver</a>
@else
  <a href="/listar_recursos_salud" class="btn btn-danger"><i class="fa fa-times"></i> CANCELAR</a>
  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> GUARDAR</button>
@endif

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
