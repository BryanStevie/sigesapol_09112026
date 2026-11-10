<script>

$(document).on('change','input[type="checkbox"]' ,function(e) {
    if(this.id=="medicamento") {
        if(this.checked) $('#medicamento').val(1);
        else $('#medicamento').val(0);
    }
    if(this.id=="dispositivo") {
        if(this.checked) $('#dispositivo').val(1);
        else $('#dispositivo').val(0);
    }
});

$("#btnGuardar1").click(function() {
    event.preventDefault();

    var msg = "";

    var _token = $("#token_activar").val();
    var url = '{{route("update_activar")}}';
    
    var id = $('#id_activar').val();
    var medicamento = $('#medicamento').val();
    
    var dispositivo = $('#dispositivo').val();
    var id_user = $('#id_user').val();
    var accion = $('#accion').val();

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    } else {
        
        $.ajax({
          url: url,
          method: 'POST',
          headers: {'X-CSRF-TOKEN': _token},
          data: {
            "id": id,
            "medicamento": medicamento,
            "dispositivo": dispositivo,
            "id_user": id_user,
            "accion": accion
          }
        }).done(function(res){
            $('#tblCan').DataTable().ajax.reload();
            $('#showActivar').modal('hide');
            Swal.fire({
                title: '<div class="titulo-padding"><p>Excelente</p></div>',
                html: '<p class="descripcion-alert"><span><b>se actualizo correctamente.</b><br></span></p>', 
                allowOutsideClick: false,
                timer: 1500,
                icon: 'success',
                timerProgressBar: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            });
    
        })
    }
  
});

</script>
<div class="container">
    <div class="row">
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Activar / Desactivar {{$nombre_establecimiento}} @if($id_servicio!=0) - {{$servicio->nombre}} @endif</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frm_activar" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_activar">
                        <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                        <input type="hidden" name="id_can" id="id_can" value="<?php echo $id_can ?>">
                        <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">
                        <input type="hidden" name="id_activar" id="id_activar" value="{{$id}}">
                        <input type="hidden" name="accion" id="accion" value="{{$accion}}">
                        @if($activar->habilita_medicamento == 1)
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Medicamento</label>
                            <div class="col-lg-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="{{$activar->medicamento_cerrado}}" id="medicamento" name="medicamento" <?php if($activar->medicamento_cerrado == 1)echo 'checked="checked"';?>/>
                                </label>          
                            </div>
                        </div>
                        @else
                            <input name="medicamento" id="medicamento" value="2" class="form-control" type="hidden">
                        @endif
                        @if($activar->habilita_dispositivo == 1)
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Dispositivo</label>
                            <div class="col-lg-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="{{$activar->dispositivo_cerrado}}" id="dispositivo" name="dispositivo" <?php if($activar->dispositivo_cerrado == 1)echo 'checked="checked"';?>/>
                                </label>          
                            </div>
                        </div>
                        @else
                            <input name="dispositivo" id="dispositivo" value="2" class="form-control" type="hidden">
                        @endif
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                @if($activar->habilita_dispositivo == 1 || $activar->habilita_medicamento == 1)
                                    <a href="#" style="margin-top: 6px;" class="btn btn-warning" id="btnGuardar1">Guardar</a>
                                @endif
                                <?php if($retorno==1): ?>
                                    <a href="/monitorear_can_servicio/{{$id_can}}/{{$id_establecimiento}}" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                                <?php else: ?>
                                    <a href="/monitorear_can/{{$id_establecimiento}}" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                                <?php endif; ?>
                            </div>
                        </div> 

                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>


