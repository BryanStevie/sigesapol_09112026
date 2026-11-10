<?php if($status == 1) { ?>
  <h5 class="app-mt-4"><strong>Programaci&oacute;n Medica:</strong> <?php echo date("d/m/Y", strtotime($fecha_atencion)); ?></h5>
  <div class="alert alert-danger" role="alert">No hay médicos programados para esta especialidad o, no se tiene un horario registrado.</div>
<?php } else if ($status == 2) { ?>  
  <h5 class="app-mt-4"><strong>Programaci&oacute;n Medica:</strong> <?php echo date("d/m/Y", strtotime($fecha_atencion)); ?></h5>
  <div class="alert alert-danger" role="alert">No se puede realizar una cita los días domingos ni feriados.</div>
<?php } else if ($status == 3) { ?>  
  <div class="alert alert-danger" role="alert">No se puede crear más de cuatro citas médicas en este mes seleccionado.</div>
<?php } else if ($status == 4) { ?>
  <div class="alert alert-danger" role="alert">Tiene una cita programada para hoy día.</div>
<?php } else if ($status == 5) { ?>
  <br>
  <div class="alert alert-success" role="alert">
    <strong style="font-size: 1.2em;">No se le puede dar cita antes de los <?php echo $aDetalle['intervalo_cita'] ?> día(s) hábiles de su cita ya registrada.</strong>  
    <br><br>
    Paciente: <strong><?php echo $aDetalle['paciente']; ?></strong><br>
    Fecha cita: <strong><?php echo $aDetalle['fecha']; ?></strong><br>
    Servicio: <strong><?php echo $aDetalle['servicio'] ?></strong><br> 
    Médico: <strong><?php echo $aDetalle['medico']; ?></strong>
  </div>
  
<?php } else { ?>
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

    function guardarCita(id_sub_consultorio, fecha_cita, id_turno, adicional, id_laboratorio) {
      var msg = "";
      // var dni_beneficiario = $("#nrodocafiliado").val();
      // var id_ipress = $('#id_establecimiento').val();
      // var id_consultorio = $('#id_servicio').val();
      // var fecha_atencion = $('#fecha_atencion').val();
      // let tipo_cita = $('#id_tipo_cita').val();
      // let correo_input = $("#correo").val();
      let fecha_actual = moment();
      // let id_intervencion_quirurgica = parseInt($("#id_intervencion_quirurgica").val(), 10);

      if(adicional==0){
        if (fecha_actual.diff(moment(fecha_cita).format("YYYY-MM-DD HH:mm"), 'minutes', true) >= 0) {
          msg += `Fecha de cita incorrecta, revisar que la hora sea mayor a la del sistema ${fecha_cita}<br>`;
        }  
      }
      
      // if (dni_beneficiario == "") msg += "Debe ingresar el numero de documento <br>";
      // if (correo_input.trim() != "" && !validarFormatoEmail(correo_input)) msg += "Debe ingresar un correo válido<br>";
      // if (id_ipress == "") msg += "Debe ingresar una Ipress<br>";
      // if (id_consultorio == "") msg += "Debe ingresar un Servicio<br>";
      // if (fecha_atencion == "") msg += "Debe ingresar una fecha de atencion<br>";
      // if (tipo_cita =="") msg += "Debe ingresar un tipo de cita<br>";

      if (msg != "") {
        Swal.fire({ icon: 'error', title: 'Error', text: msg});
        return false;
      }
      const fechaCitaFormatted = moment(fecha_cita, "YYYY-MM-DD HH:mm").format("DD-MM-YYYY HH:mm");
        // let msg_centro_quirurgico = (id_intervencion_quirurgica > 0 ? "<br><b>Importante</b> El registro de la Cita <b>SI PERTENECE</b> a una Intervención Quirúrgica." : "");
      Swal.fire({
        title: 'Confirmación de Reprogramación',
        html: `
          <div style="text-align: center; font-family: Arial, sans-serif;">
            <p style="font-size: 16px; margin: 0 0 10px;">
              ¿Está seguro de reprogramar la cita del laboratorio?
            </p>
            <p style="font-size: 14px; margin: 0;">
              <strong>Nueva Fecha de Cita:</strong>
              <span style="color: #007bff; font-weight: bold;">
                ${fechaCitaFormatted}
              </span>
            </p>
          </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-check"></i> Sí, reprogramar',
        cancelButtonText: '<i class="fa fa-times"></i> No, cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        reverseButtons: true, 
        focusCancel: true,
        allowOutsideClick: false,
        allowEscapeKey: false
      }).then((result) => {
        if (result.isConfirmed) {
          reprogramarCitaLaboratorio(id_sub_consultorio, fecha_cita, id_turno, adicional, id_laboratorio);
        }
      });
    }

      // 'idTipoAtencion' => $request->id_tipo_atencion,
      //   'idSubConsultorio' => $request->id_sub_consultorio,
      //   'fecha' => $request->fecha,
      //   'idLaboratorio' => $request->id_laboratorio,

    function reprogramarCitaLaboratorio(id_sub_consultorio, fecha_cita, id_turno, adicional, id_laboratorio) {
      const body = {
        idSubConsultorio: id_sub_consultorio,
        fechaHora: fecha_cita,
        idLaboratorio: id_laboratorio,
      };
      $.ajax({
        url: `/api/nivel-1-2/laboratorio/${id_laboratorio}/reprogramar-cita`,
        type: "PUT",
        contentType: "application/json",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        data: JSON.stringify(body),
        beforeSend: function() {
          Swal.fire({
            title: 'Reprogramando cita...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });
        }
      })
      .done(function(response) {
        $('#modalReprogramar').modal('hide');
        const mensaje = response.message || "Cita reprogramada exitosamente.";
        Swal.fire({
          icon: 'success',
          title: 'Reprogramación Laboratorio',
          text: mensaje,
        });
        location.reload();
      })
      .fail(function(xhr, status, error) {
        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error al reprogramar la cita.";
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: errorMessage,
        });
      })
      .always(function() {
      });
    }
  </script>
  <?php if ($status == 7) { ?>
    <br/>
    <div class="alert alert-danger" role="alert">Tiene una cita programada para hoy día en este servicio.</div>
    <br/>
  <?php } ?>
  <div style="display: flex; ">
    <div style="display: flex; width: 100%;">
      <h5 class="app-mt-4"><strong>Programaci&oacute;n Medica:</strong> <?php echo date("d/m/Y", strtotime($fecha_atencion)); ?></h5>
    </div>
      <div style="display: flex; width: 100%;">
        <p style="margin: 0; align-content: center !important; font-weight: 700;">Total de Citas :</p>
        <p style="margin: 0; align-content: center !important;">&nbsp;&nbsp;&nbsp;&nbsp;{{$total_citas}}</p>
      </div>
  </div>
  
  <?php  foreach ($aHorarios as $kHorario => $vHorario): 
      // Ordenar consultorios alfabéticamente por nombre
      usort($vHorario['consultorios'], function ($a, $b) {
          return strcmp($a['consultorio'], $b['consultorio']);
      });
  ?>
    <?php foreach ($vHorario['consultorios'] as $kConsultorio => $vConsultorio): ?>
      <ul class="list-group app-gestion-cita-lista app-mt-4">
      <li class="list-group-item">
        <strong>Medico:</strong> <?php echo $vHorario['medico'] ?><br>
        <strong>Consultorio:</strong> <?php echo $vConsultorio['consultorio'] ?>
        <div class="text-right">
          <span class="toggle-horario btn btn-sm btn-primary">+</span>
        </div>
      </li>
      <li class="list-group-item horario-container" style="display: none;">
        <div class="btn-group" role="group">
          
        <?php 
          // Ordenar horarios de cada consultorio por hora de inicio
          usort($vConsultorio['horario'], function ($a, $b) {
              $horaA = \Carbon\Carbon::createFromFormat('g:i A', explode(' - ', $a['hora'])[0]);
              $horaB = \Carbon\Carbon::createFromFormat('g:i A', explode(' - ', $b['hora'])[0]);
              return $horaA->gt($horaB);
          });

          foreach ($vConsultorio['horario'] as $itemHora): 
          ?>
          <?php
            $horas = explode(' - ', $itemHora['hora']);
            $horaSolicitada = \Carbon\Carbon::createFromFormat('g:i A', $horas[0]);

            $style = $itemHora['color'] == '' ? '' : ' style="' . $itemHora['color'] . '"';
            $click = $itemHora['click'] == '' ? '' : ' onclick="' . $itemHora['click'] . '"';
            $asegurado = $itemHora['asegurado'] == '' ? '' : $itemHora['asegurado'];
          ?>
            <?php if ($fecha_actual < $fecha_atencion): ?>
              <button type="button" data-toggle="tooltip" data-placement="top" title="<?php echo  $asegurado; ?>"  class="btn btn-default app-reserva-cita-hora-deparacion"<?php echo $style . $click  ?>><?php echo $itemHora['hora']  ?></button>
            <?php else: ?>
              <?php if(\Carbon\Carbon::now()->gt($horaSolicitada)):  ?>
                <button type="button" data-toggle="tooltip" data-placement="top" 
                class="btn btn-danger app-reserva-cita-hora-deparacion"<?php echo  $style . $click  ?> disabled><?php echo $itemHora['hora']  ?></button>
              <?php else:?>
                <button type="button" data-toggle="tooltip" data-placement="top" 
                class="btn btn-default app-reserva-cita-hora-deparacion"<?php echo  $style . $click  ?> ><?php echo $itemHora['hora']  ?></button>
            <?php endif ?>
          <?php endif ?>
            <?php endforeach; ?>
        </div>
      </li>
      </ul>
    <?php endforeach; ?>  
  <?php endforeach; ?>
  <!-- jQuery para manejar la funcionalidad de mostrar/ocultar -->
<script>
    $(document).ready(function(){
        $(".toggle-horario").click(function(){
            let horarioContainer = $(this).closest("ul").find(".horario-container");
            horarioContainer.slideToggle();
            
            let icon = $(this).text() === "+" ? "-" : "+";
            $(this).text(icon);
        });
    });
</script>
<?php } ?>
