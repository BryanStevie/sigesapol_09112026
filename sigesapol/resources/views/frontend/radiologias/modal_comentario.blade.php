<?php //$comentario = $cita->observacion_cita_imagenes_por_pp;
//dd($cita->id);
  $cantidad = \App\Models\Prestacion_procedimiento::where('id_citas_multiples_rad', $cita->id)->count();
  $activar_autoenvio = session('activar_autoenvio', false);
?>
  <form name="frm" id="frm" role="form" method="post" data-toggle="validator">
    <div class="form-group">
      <label>Escribir el motivo para observar la cita</label>
      <!--textarea class="form-control" rows="5" id="comentario" name="comentario"<-?php if (!empty($comentario)): echo ' readonly'; endif; ?>><-?php echo $comentario; ?></textarea-->
      <textarea class="form-control" rows="5" id="comentario" name="comentario"></textarea>
      <input type="hidden" id="comentario_anterior" value="<?php echo e($comentario); ?>">
    </div>
    <div class="form-group">
      <input type="button" id="guardarComentario" value="Guardar" class="btn btn-success">
      <a href="#" class="btn btn-danger" id="cancelarComentario" data-dismiss="modal">Cancelar</a>
    </div>
  </form>
<!--?php if (empty($comentario)): ?-->
<script>
  $('#guardarComentario').click(function() {
    var comentarioNuevo = $('#comentario').val().trim();
    var comentarioAnterior = $('#comentario_anterior').val().trim();
    var comentarioFinal = comentarioAnterior ? comentarioAnterior + ' | ' + comentarioNuevo : comentarioNuevo;
      console.log("Valores enviados:", {
    _token: '{{ csrf_token() }}',
    id: <?php echo $cita->id ?>,
    id_pp:<?php echo $id_pp ?>,
    comentario: comentarioFinal,
    cantidad: <?php echo $cantidad; ?>
  });
    // $.ajax('{{ route('update_comentario') }}', {
    $.ajax('{{ route('update_comentario_radiologia') }}', {
      type: 'POST',
      data: { 
        _token: '{{ csrf_token() }}', 
        id: <?php echo $cita->id ?>, 
        id_pp: <?php echo $id_pp ?>, 
        comentario: comentarioFinal, 
        cantidad: <?php echo $cantidad; ?> 
      },
      success: function (data, status, xhr) {
        $('#cancelarComentario').trigger('click');
        window.location.href = "{{ url('all_radiologia_prescritas_by_ipress') }}";
      },
      error: function (jqXhr, textStatus, errorMessage) {
        alert("Ocurrió un error al guardar el comentario.");
      }
    });
  });     
</script>
<!--?php endif; ?-->