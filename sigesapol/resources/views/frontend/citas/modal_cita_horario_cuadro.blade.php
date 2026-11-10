<script type="text/javascript">
  function validacion() {

    var msg = "";
    var cobservaciones = $("#frmComentar #cobservaciones").val();

    if (cobservaciones == "") {
      msg += "Debe ingresar una Observacion <br>";
    }

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }
  }

  function guardarCita(id_medico, fecha_cita, id_turno) {
    bootbox.confirm({    
      size: "small",
      message: "&iquest;Deseas guardar la cita?",
      callback: function(result) {
        if (result == true) {
          fn_save_cita(id_medico, fecha_cita, id_turno);
        }
      }
    });
  }

  function fn_save_cita(id_medico, fecha_cita, id_turno) {

    var msg = "";
    var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    let tipo_cita = $('#id_tipo_cita').val();
    let fecha_actual = moment();

    if (fecha_actual.diff(moment(fecha_cita).format("YYYY-MM-DD HH:mm"), 'minutes', true) >= 0) {
      msg += `Fecha de cita incorrecta, revisar que la hora sea mayor a la del sistema ${fecha_cita}<br>`;
    }

    var dni_beneficiario = $("#dni_beneficiario").val();
    if (dni_beneficiario == "") msg += "Debe ingresar el numero de documento <br>";
    if (id_ipress == "") {
      msg += "Debe ingresar una Ipress<br>";
    }
    if (id_consultorio == "") {
      msg += "Debe ingresar un Consultorio<br>";
    }
    if (fecha_atencion == "") {
      msg += "Debe ingresar una fecha de atencion<br>";
    }

    if (tipo_cita =="") {
      msg += "Debe ingresar un tipo de cita<br>";
    }

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }

    var correo = $('#correo').val();
    var msgLoader = "";
    if (correo == "") msgLoader = "Procesando, espere un momento por favor";
    else msgLoader = "Enviado correo, espere un momento por favor";

    $('#btnCita').prop("disabled", true);

    var heightBrowser = $(window).width() + 500;

    $('.loader').css("opacity", "0.8").css("height", heightBrowser).html(
      "<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>" +
      msgLoader + "</div></div>");
    $('.loader').show();

    var fecha_atencion_original = $('#fecha_atencion').val();
    var id_user = $('#id_user').val();

    $.ajax({
      url: "registrar_cita",
      type: "POST",
      data: $("#frmCita").serialize() + "&id_consultorio=" + id_medico + "&fecha_cita=" + fecha_cita + "&id_turno=" + id_turno,
      success: function(result) {
        $('#btnCita').prop("disabled", false);
        $('.loader').hide();
        location.href = "ver_cita/" + id_user + "/" + result;
      }
    });
  }

</script>

<p><strong>Programaci&oacute;n Medica:</strong> <?php echo date("d/m/Y", strtotime($fecha_atencion)); ?></p>
<div class="row">
  <input type="hidden" name="fecha_atencion" id="fecha_atencion" value="<?php echo $fecha_atencion ?>" />
  <input type="hidden" name="id_ipress" id="id_ipress" value="<?php echo $id_ipress ?>" />
  <input type="hidden" name="id_consultorio" id="id_consultorio" value="<?php echo $id_consultorio ?>" />
  <div class="col-md-12">
<?php
  $tableTotalColumnas = count($aHorarios[0]);
  $tableTotalFilas = count($aHorarios);
?>
<div class="table-responsive">
  <table class="table table-bordered app-fix-table-horario">
    <thead>
      <tr>
        <?php for($ic = 0; $ic < $tableTotalColumnas; $ic++) { ?>
        <th class="text-center"><?php echo $aHorarios[0][$ic]['value']; ?></th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php for($if = 1; $if < $tableTotalFilas; $if++) { ?>
      <tr>
        <?php for($ic = 0; $ic < $tableTotalColumnas; $ic++) { ?>
        <?php 
          $style = isset($aHorarios[$if][$ic]['color']) ? ' style="' . $aHorarios[$if][$ic]['color'] . '"' : '';
          $click = isset($aHorarios[$if][$ic]['click']) ? ' onclick="' . $aHorarios[$if][$ic]['click'] . '"' : '';
        ?>
        <td<?php echo $style . $click; ?>>
          <?php 
          $value = '';
          if (isset($aHorarios[$if][$ic])) {
            $value = $aHorarios[$if][$ic]['value'];
          }
          echo $value;
          ?>
        </td>
        <?php } ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>