<script>
$("#btnGuardar3").click(function() {
    event.preventDefault();

    var msg = "";

    var _token = $("#token_ampliar_tiempo").val();
    var url = '{{route("update_tiempo")}}';
    var id = $('#id_actualizar').val();
    var id_hora = $('#id_hora').val();
    var id_tiempo = $('#id_tiempo').val();
    var id_servicio = $('#id_servicio').val();
    var id_user = $('#id_user').val();
    var id_can = $('#id_can').val();
    var id_establecimiento = $('#id_establecimiento').val();
    var accion = 1;
    

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
            "id_establecimiento": id_establecimiento,
            "id_servicio": id_servicio,
            "id_can": id_can,
            "id_hora": id_hora,
            "id_tiempo": id_tiempo,
            "accion": accion
            
          }
        }).done(function(res){

            $('#tblCan').DataTable().ajax.reload();
            $('#showAmpliarTiempo').modal('hide');
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
                    <div class="panel-title">Ampliar Tiempo al {{$nombre_establecimiento}} @if($id_servicio!=0) - {{$servicio->nombre}} @endif</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frm_ampliar" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_ampliar_tiempo">
                        <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                        <input type="hidden" name="id_can" id="id_can" value="<?php echo $id_can ?>">
                        <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        <input type="hidden" name="accion" id="accion" value="1">
                        <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">
                        <input type="hidden" name="id_actualizar" id="id_actualizar" value="{{$id}}">
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Dias</label>
                            <div class="col-lg-10">
                                <select class="form-control select2" name="id_tiempo" id="id_tiempo">
                                    <option value="0" > 0 dia </option>
                                    <option value="1" > 1 dia </option>
                                    <option value="2" > 2 dias </option>
                                    <option value="3" > 3 dias </option>
                                    <option value="4" > 4 dias </option>
                                    <option value="5" > 5 dias </option>
                                    <option value="6" > 6 dias </option>
                                    <option value="7" > 7 dias </option>
                                </select>        
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Horas</label>
                            <div class="col-lg-10">
                                <select class="form-control select2" name="id_hora" id="id_hora">
                                    <option value="0" > 0 hora </option>
                                    <option value="1" > 1 hora </option>
                                    <option value="2" > 2 horas </option>
                                    <option value="3" > 3 horas </option>
                                    <option value="4" > 4 horas </option>
                                    <option value="5" > 5 horas </option>
                                    <option value="6" > 6 horas </option>
                                    <option value="7" > 7 horas </option>
                                    <option value="8" > 8 horas </option>
                                    <option value="9" > 9 horas </option>
                                    <option value="10" > 10 horas </option>
                                    <option value="11" > 11 horas </option>
                                    <option value="12" > 12 horas </option>
                                    <option value="13" > 13 horas </option>
                                    <option value="14" > 14 horas </option>
                                    <option value="15" > 15 horas </option>
                                    <option value="16" > 16 horas </option>
                                    <option value="17" > 17 horas </option>
                                    <option value="18" > 18 horas </option>
                                    <option value="19" > 19 horas </option>
                                    <option value="20" > 20 horas </option>
                                    <option value="21" > 21 horas </option>
                                    <option value="22" > 22 horas </option>
                                    <option value="23" > 23 horas </option>
                                </select>        
                            </div>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                @if($activar->habilita_dispositivo == 1 || $activar->habilita_medicamento == 1)
                                    <a href="#" style="margin-top: 6px;" class="btn btn-warning" id="btnGuardar3">Guardar</a>
                                @endif
                                <a href="/monitorear_can_servicio/{{$id_can}}/{{$id_establecimiento}}" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                            </div>
                        </div> 

                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>


