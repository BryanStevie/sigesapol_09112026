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
  width: 100%;
  min-height: 650px !important;
  max-width: 100%;
  border: 1px solid #ddd;
  margin: 15px auto;
  padding: 15px;
  box-sizing: border-box; /* Asegura que el padding no afecte el tamaño total del contenedor */
  overflow-x: auto; /* Permite desplazamiento horizontal si es necesario */
  overflow-y: hidden; /* No permite el desplazamiento vertical */
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
    margin-top: -18px;
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
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>



document.addEventListener("DOMContentLoaded", function() {
  controlarMeses();
});

    $(document).ready(function () {
    	
 
    	actualizarUbigeo();
    	 	
    });

    $('body').on('click', '#validarws', function () {
        var id_trama = $(this).data('id_trama');
        var id_estado = $(this).data('id_estado');
        $id_trama = id_trama;
        $id_estado = id_estado;
        $('#id_estado').html(id_estado);
        $('#id_trama').html(id_trama);
       if(id_trama == '0'){
        console.log(id_trama);
        console.log(id_estado);
        validar_x_ws(id_trama,id_estado)
       }
    });

   
window.onload = function() {
    controlarEdicion();
};
  
    
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="padding: 0;">
      <h3 class="panel-title" style="display: block; height: 100%; padding: 0 15px;">
        <span style="display: inline-block; padding: 10px 15px; background-color: rgb(51, 61, 183); color: white;">A</span> Vista Detalle Código Paquete 
      </h3>
    </div>
    <div class="panel-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 2px solid #ddd; padding: 10px; margin-bottom: 20px;">
          <div class="container-fluid">
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
                  <label class="form-control-sm">Detalle Codigo Paquete</label>
                  <label class="form-control-sm"><?php echo $mostrar_1['v_cod_paquete']; ?></label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
                  <label class="form-control-sm">Detalle Usuario Envio DNI</label>
                  <label class="form-control-sm"><?php echo $mostrar_1['v_usuario_crea']; ?></label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
                  <label class="form-control-sm">Detalle Fecha Envio</label>
                  <label class="form-control-sm"><?php echo $mostrar_1['fecha_envio']; ?></label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                  <label class="form-control-sm">Detalle Cantidad Prestaciones Enviadas</label><br>
                  <label class="form-control-sm"><?php echo $mostrar_1['cantidad_prestaciones_enviadas']; ?></label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                  <label class="form-control-sm">Detalle Cantidad Prestaciones Observadas</label><br>
                  <label class="form-control-sm"><?php echo $mostrar_1['cantidad_prestaciones_observadas']; ?></label>
                </div>
              </div>
              <div class="row">
              <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="border-collapse: collapse;">
  <thead>
    <tr>
      <th width="10%" style="text-align: center; border: 1px solid black;">Código fups</th>
      <th width="10%" style="text-align: center; border: 1px solid black;">fecha proceso</th>
      <th width="10%" style="text-align: center; border: 1px solid black;">descripcion fups</th>
      <th width="10%" style="text-align: center; border: 1px solid black;">cantidad campos observados</th>
      <th width="60%" style="text-align: center; border: 1px solid black;">Observaciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($mostrar_2 as $row): ?>
      <?php if($row['estado_fups'] == '5'): ?>
        <tr>
          <td style="text-align: center; border: 1px solid black;"><?php echo $row['v_numero_fups']; ?></td>
          <td style="text-align: center; border: 1px solid black;"><?php echo $row['fecha_proceso']; ?></td>
          <td style="text-align: center; border: 1px solid black;"><?php echo $row['descripcion_fups']; ?></td>
          <td style="text-align: center; border: 1px solid black;"><?php echo $row['cantidad_campos_observados']; ?></td>
          <td style="text-align: center; border: 1px solid black;">
            <?php foreach($row['observaciones'] as $de): ?>
              <?php echo 'Regla: '.$de['codigo_regla'].', Dato ' .$de['valor_observado'].'. '; ?>
            <?php endforeach; ?>
          </td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
  </tbody>
</table>

              </div>
            </div>
            </div>

        </div>
    </div>
  </div>
</div>


@include('frontend.includes.footer')
@endsection