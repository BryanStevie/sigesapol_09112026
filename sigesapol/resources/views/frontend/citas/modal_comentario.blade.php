<?php $comentario = $cita->comentario; ?>
  <form name="frm" id="frm" role="form" method="post" data-toggle="validator">
    <div class="form-group">
      <label>Escribir el motivo para observar la cita</label>
      <textarea class="form-control" rows="5" id="comentario" name="comentario"<?php if (!empty($comentario)): echo ' readonly'; endif; ?>><?php echo $comentario; ?></textarea>
    </div>
    <div class="form-group">
      <?php if (empty($comentario)): ?>
      <input type="button" id="guardarComentario" value="Guardar" class="btn btn-success">
      <?php endif; ?>
      <a href="#" class="btn btn-danger" id="cancelarComentario" data-dismiss="modal">Cancelar</a>
    </div>
  </form>
<?php if (empty($comentario)): ?>
<script>
  $('#guardarComentario').click(function() {
    $.ajax('{{ route('update_comentario') }}', {
      type: 'POST',  // http method
      data: { _token: '{{ csrf_token() }}', id: <?php echo $cita->id ?>, comentario: $('#comentario').val() },  // data to submit
      success: function (data, status, xhr) {
        $('#cancelarComentario').trigger('click');
      },
      error: function (jqXhr, textStatus, errorMessage) {
      },
      complete: function(data) {
        window.location.href = "/all_citas_medico_paciente";
      }
    });
  });     
</script>
<?php endif; ?>