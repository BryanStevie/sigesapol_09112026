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

$("#btnGuardar2").click(function() {
    event.preventDefault();

    var msg = "";

    var _token = $("#token_activar_todos").val();
    var url = '{{route("update_activar_todos")}}';
    var id_establecimiento = $('#id_establecimiento').val();
    var id_can = $('#id_can').val();
    var medicamento = $('#medicamento').val();
    
    var dispositivo = $('#dispositivo').val();
    var id_user = $('#id_user').val();
    

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    } else {
        
        $.ajax({
          url: url,
          method: 'POST',
          headers: {'X-CSRF-TOKEN': _token},
          data: {
            "id_establecimiento": id_establecimiento,
            "medicamento": medicamento,
            "dispositivo": dispositivo,
            "id_can":id_can,
            "id_user": id_user
            
          }
        }).done(function(res){
            $('#tblCan').DataTable().ajax.reload();
            $('#showActivarTodos').modal('hide');
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
                    <div class="panel-title">Activar / Desactivar todos los servicios del {{$nombre_establecimiento}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frm_activar_todos" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_activar_todos">
                        <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                        <input type="hidden" name="id_can" id="id_can" value="<?php echo $id_can ?>">
                        <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Medicamento</label>
                            <div class="col-lg-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox"  id="medicamento" value="1" name="medicamento" checked />
                                </label>          
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Dispositivo</label>
                            <div class="col-lg-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox"  id="dispositivo" value="1" name="dispositivo" checked />
                                </label>          
                            </div>
                        </div>
                        
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <a href="#" style="margin-top: 6px;" class="btn btn-warning" id="btnGuardar2">Guardar</a>
                                <a href="/monitorear_can_servicio/{{$id_can}}/{{$id_establecimiento}}" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                                
                            </div>
                        </div> 

                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>


