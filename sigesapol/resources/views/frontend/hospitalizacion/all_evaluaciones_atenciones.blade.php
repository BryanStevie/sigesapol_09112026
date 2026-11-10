<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
.table-sortable tbody tr {
  cursor: move;
}

#global {
  min-height: 650px !important;
  /*width: auto;*/
  width: 100%;
  border: 1px solid #ddd;
  margin: 15px
    /* background: #f1f1f1;*/
    /*overflow-y: scroll !important;*/
}

.margin {

  margin-bottom: 20px;
}

.margin-buscar {
  margin-bottom: 5px;
  margin-top: 5px;
}

.row {
  margin-top: 10px;
  padding: 0 10px;
}

.clickable {
  cursor: pointer;
}

.panel-heading div {
  /* margin-top: -18px; */
  font-size: 15px;
}

.panel-heading div span {
  margin-left: 5px;
}

.panel-body {
  display: block;
}

.dataTables_filter {
  display: none;
}

.help-block {
  display: block;
  margin-top: 1px;
  margin-bottom: 10px;
  color: #737373;
}

.modal-dialog {
  width: 70% !important;
}

.modal-dialog-hc{
  width: 40% !important;
}

  .form-control {
    height: 28px;
  }

  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle:before {
    content: 'NO';
    left: -4rem;
  }
  .btn-toggle:after {
    content: 'SI';
    right: -4rem;
    opacity: 0.5;
  }
  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.active:before {
    opacity: 0.5;
  }
  .btn-toggle.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }
  .btn-toggle.active {
    background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
    content: 'NO';
    left: -5rem;
  }
  .btn-toggle.btn-lg:after {
    content: 'SI';
    right: -5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }
  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }
  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }
  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }
  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }
  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }
  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }
  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }
  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }
  .form-group {
    margin-bottom: auto;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>


$(document).ready(function() {
  modalHistorialEvaluaciones();
  modalHistorial();
});

var m = 0;

function modalHistorialEvaluaciones(){
  
  var id_hospitalizacion = $("#id_hospitalizacion").val();
  

  $.ajax({
      url: "../../../modal_historial_evaluaciones/"+id_hospitalizacion+"/4/3",
      type: "GET",
      success: function (result) {  
          $("#divEvaluacione").html(result);

          $('#tablemodalm3').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

function modalHistorial(){
  var id_hospitalizacion = $("#id_hospitalizacion").val();
  
  $.ajax({
      url: "../../../modal_historial_evaluaciones_atenciones/"+id_hospitalizacion+'/'+3,
      type: "GET",
      success: function (result) {  
          $("#divHistorial").html(result);
          $('#tablemodal1').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Historia Clínica del Paciente</h3>
    </div>
    <div class="panel-body">
      <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
          <div class="panel-title text-center" style="margin-top:-18px;"><br>
            <b>INFORMACIÓN DEL PACIENTE</b>
          </div>
        </div>
        <div class="panel-body">
          <form>

            <div class="row">
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-12">
                  <label>NOMBRE DE LA IPRESS</label>
                  <p>{{$paciente->establecimiento}}</p>
                  <?php //dd($paciente->establecimiento);?>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>DOCUMENTO</label>
                  <p>{{$paciente->tipo_doc_ident}} - {{$paciente->nro_doc_ident}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>N° HISTORIA CLINICA</label>
                  <p>{{$paciente->nro_historia}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>APELLIDO PATERNO</label>
                  <p>{{$paciente->asegurado_paterno}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>APELLIDO MATERNO</label>
                  <p>{{$paciente->asegurado_materno}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>NOMBRES</label>
                  <p>{{$paciente->asegurado_nombre}}</p>
                </div>
              </div>
            </div>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input  value="<?php echo $id_hospitalizacion?>" type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" class="form-control">
              <input  value="<?php echo $estado?>" type="hidden" name="estado" id="estado" class="form-control">
              <input  value="<?php echo $id_atencione?>" type="hidden" name="id_atencione" id="id_atencione" class="form-control">
              <input type="hidden" name="fecha_ini" id="fecha_ini" value="<?php echo $fechaDesde ?>">
              <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fechaHasta ?>">
              <input type="hidden" name="id_servicio" id="id_servicio" value="0">
              <input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">
              <input type="hidden" name="doc_firmar" id="doc_firmar">
              <input type="hidden" name="opt_firma" id="opt_firma">
              <input type="hidden" name="id_evaluacion_selected" id="id_evaluacion_selected">
              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$farmacia->id_establecimiento}}">
            <div class="row">
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label>PARENTESCO</label>
                  <p>{{$paciente->parentesco}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="dni_beneficiario">SEXO</label>
                  <p>{{$paciente->sexo}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="tipo">SALA</label>
                  <p>{{$datoadicional->consultorio}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label for="tipo">PABELLON</label>
                  <p>{{$datoadicional->pabellon}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label for="dni_beneficiario">PISO</label>
                  <p>{{$datoadicional->piso}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label>HABITACION</label>
                  <p>{{$datoadicional->cuarto}}</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-1 col-md-1 col-sm-4">
                  <label>CAMA</label>
                  <p>{{$datoadicional->cama}}</p>
                </div>
              </div>
            </div>
          </form>
      
        <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading">
              <div class="panel-title text-center">
                <b>ATENCION MEDICA</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div class="col-md-12">
                  <div id='divHistorial'></div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading">
              <div class="panel-title text-center">
                <b>HISTORIAL DE EVOLUCIONES MEDICAS</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div class="col-md-12">
                  <div id='divEvaluacione'></div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalAtencionEvaluacion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE LA EVOLUCIÓN MEDICA: <span id="nrorecmodal2"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-evaluacion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="addComponent"></div>

@include('frontend.includes.footer')
@endsection
