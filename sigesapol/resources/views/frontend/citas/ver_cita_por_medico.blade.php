@extends('frontend.layouts.master')

@section('rendercss')
  <style>
    .search-form .form-group {
      float: left !important;
      transition: all 0.35s, border-radius 0s;
      width: 32px;
      height: 32px;
      background-color: #fff;
      box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
      border-radius: 25px;
      border: 1px solid #ccc;
      margin-left: 0px;
    }

    .search-form .form-group input.form-control {
      padding-right: 20px;
      border: 0 none;
      background: transparent;
      box-shadow: none;
      display: block;
    }

    .search-form .form-group input.form-control::-webkit-input-placeholder {
      display: none;
    }

    .search-form .form-group input.form-control:-moz-placeholder {
      /* Firefox 18- */
      display: none;
    }

    .search-form .form-group input.form-control::-moz-placeholder {
      /* Firefox 19+ */
      display: none;
    }

    .search-form .form-group input.form-control:-ms-input-placeholder {
      display: none;
    }

    .search-form .form-group:hover,
    .search-form .form-group.hover {
      width: 60px;
      border-radius: 4px 25px 25px 4px;
    }

    .search-form .form-group span.form-control-feedback {
      position: absolute;
      top: -1px;
      right: -2px;
      z-index: 2;
      display: block;
      width: 34px;
      height: 34px;
      line-height: 34px;
      text-align: center;
      color: #3596e0;
      left: initial;
      font-size: 14px;
    }

    .table {
      padding: 8px;
      line-height: 1.428571429;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }

    .center {
      margin-top: 50px;
    }

    .modal-header {
      padding-bottom: 5px;
    }

    .modal-footer {
      padding: 0;
    }

    .modal-footer .btn-group button {
      height: 40px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
      border-right: 1px solid #ddd;
    }

    .modal-footer .btn-group:last-child>button {
      border-right: 0;
    }

    td {
      padding-left: 10px;
      padding-right: 10px
    }
  </style>

  <style type="text/css" media="print">
    .panel {
      border: none;
    }
    .panel .panel-title {
      font-weight: bold;
    }    
    .panel .btn-print {
      display: none;
    }
    .panel .noprint {
      display: none;
    }
    .app-print-inline span:before {
      content: ": ";
    }
    .app-print-inline div {
      margin-bottom: 0;
    }
    .app-print-inline label {
      width: 145px;
    }
    .app-print-inline span {
      display: inline;
      padding-left: 2px;
      width: inherit;
      border: 0;
    }
    .app-print-show {
      display: block!important;
    }
    .container {
      min-height: inherit!important;
    }
    .panel-body {
      padding: 0;
    }
  </style>
@endsection

@section('renderjs')
  <script>
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    /*$consul = $("#frmConsultorio").find("#id_consultorios");
    $consul.select2();*/

    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();

    $(document).ready(function() {
      $("#frmDispensar").submit(function(e) {
        rows = $("#frmDispensar").children(".row");
        $.each(rows, function(ii, oo) {
          medicina = $(oo).find("#lblNombre").text();
          nuevaCantidad = parseFloat($(oo).find("#nueva_cantidad").val());
          cantidadPrescrita = parseFloat($(oo).find("#txtCantidadPrescrita").val());
          stockActual = parseFloat($(oo).find("#lblStockActual").text());
          if (stockActual == 0) {
            if (nuevaCantidad != 0) {
              alert("La medicina: " + medicina + ", tiene que ser cantidad cero");
              e.preventDefault();
              return false;
            }
          } else {
            if (nuevaCantidad > stockActual) {
              alert("La medicina: " + medicina + ", la cantidad no tiene que ser mayor que el stock");
              e.preventDefault();
              return false;
            } else if (nuevaCantidad > cantidadPrescrita) {
              alert("La medicina: " + medicina + ", la cantidad no tiene que ser mayor que la prescrita");
              e.preventDefault();
              return false;
            }
          }
        });
      });
    });
    //--------------------------------------------------------------------------    
  </script>
@endsection
@section('content')
<?php
  $cita_tipo_nombre = '';
  if (!empty($cita->id_tipo_cita)):
    switch ($cita->id_tipo_cita):
      case 1: $cita_tipo_nombre = 'Presencial'; break;
      case 2: $cita_tipo_nombre = 'Teleconsulta'; break;
    endswitch;
  endif;
?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-9 col-md-offset-2">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h2 class="panel-title noprint">CITA</h2>
          <h2 class="panel-title app-print-show" style="text-align:center; display: none;"><?php echo $cita->establecimiento ?></h2>
        </div>
        @if(count($cita)>0)
        <div class="panel-body" style="padding-top:30px; font-size: 13px">
          <p class="app-print-show" style="display: none; border-bottom:1px solid #c1c1c1;">
            Estimado <strong>Sr(a). <?php echo $cita->asegurado_nombre . " " . $cita->asegurado_paterno . " " . $cita->asegurado_materno ?></strong> <span style="float: right;"><?php echo date('d/m/Y - H:i:s') ?></span><br>
            Se ha realizado satisfactoriamente la programación de su Cita:<br><br>
          </p>
          <div class="row">
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>FECHA DE CITA</label>
                <span class="form-control"><?php echo \App\Helpers\Util::GetFechaTexto($cita->dia) ?></span>
              </div>
            </div>
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>HORA DE CITA</label>
                <span class="form-control"><?php echo $cita->hora ?></span>
              </div>
            </div>
            <div class="col-md-6 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>SERVICIO</label>
                <span class="form-control"><?php echo $cita->servicio ?></span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>CONSULTORIO</label>
                <span class="form-control"><?php echo $cita->consultorio ?></span>
              </div>
            </div>
            <div class="col-md-6 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>MEDICO</label>
                <span class="form-control"><?php echo $cita->medico_nombre . " " . $cita->medico_paterno . " " . $cita->medico_materno ?></span>
              </div>
            </div>
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>TIPO CITA</label>
                <span class="form-control"><?php echo $cita_tipo_nombre ?></span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>DNI</label>
                <span class="form-control"><?php echo $cita->nro_doc_ident ?></span>
              </div>
            </div>
            <div class="col-md-9 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>NOMBRE Y APELLIDOS</label>
                <span class="form-control"><?php echo $cita->asegurado_nombre . " " . $cita->asegurado_paterno . " " . $cita->asegurado_materno ?></span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 app-print-inline">
              <div class="form-group app-form-group-span">
                <label>PARENTESCO</label>
                <span class="form-control"><?php echo $cita->parentesco ?></span>
              </div>
            </div>
            <div class="col-md-3 noprint">
              <div class="form-group app-form-group-span">
                <label>GRADO</label>
                <span class="form-control"><?php echo $cita->grado ?></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group app-form-group-span noprint">
                <label>CORREO</label>
                <span class="form-control"><?php echo $cita->correo ?></span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 noprint">
              <div class="form-group app-form-group-span">
                <label>ESTABLECIMIENTO</label>
                <span class="form-control"><?php echo $cita->establecimiento ?></span>
              </div>
            </div>
          </div>

          <div class="row btn-print">
            <div class="col-md-6">
              <a id="btnReg" class="btn btn-success" href="/all_citas_medico_atenciones">Ir Atenciones Médicas</a>
            </div>
            <div class="col-md-6 text-right">
              <button onclick="window.print();" class="btn btn-danger float-right" target="_blank">Imprimir <i class="fa fa-file-pdf-o"></i></button>
            </div>            
          </div>
          {{-- 
            <div class="col-md-3">
                <label>CREADO POR </label>
                <p></p> 
            </div> 
            <div class="col-md-3">
                <label>FECHA DE CREACION</label>  
                < ? php
                //$originalDate5 = $admision[0]->fecha_creacion;
                //$fecha_creacion = date("d-m-Y (H:i:s)", strtotime($originalDate5));                                    
                ? >
                <p>< ? php //echo $fecha_creacion ? ></p> 
            </div> 
            --}}
          {{-- 
          <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 20px">
            <div class="col-md-3" style="padding-left:0px;margin-left:0px">
              <a id="btnReg" class="form-control btn btn-success" href="/formulario_cita">Nueva Cita XXX</a>
            </div>
            <a href="/imprimir_admision/" class="btn btn-danger pull-right" target="_blank">Imprimir <i class="fa fa-file-pdf-o"></i></a>
          </div>
          --}}
        </div>
        @else
        <div class="panel-body" style="padding-top:10px; font-size: 13px">
          No se encuentra el numero de receta que digito.
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')

@endsection