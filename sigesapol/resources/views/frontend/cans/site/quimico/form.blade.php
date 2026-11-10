<?php 
$suma=$data[0]->mes1+$data[0]->mes2+$data[0]->mes3+$data[0]->mes4+$data[0]->mes5+$data[0]->mes6+$data[0]->mes7+$data[0]->mes8+$data[0]->mes9+$data[0]->mes10+$data[0]->mes11+$data[0]->mes12;
$necesidad=$data[0]->necesidad_anual-$suma;
?>
<script type="text/javascript">
    $('#sProrroteo').html({{$suma}});
    $('#sNecesidad').html({{$data[0]->necesidad_anual}});
    $('#sFalta').html({{$necesidad}});

    function calculo() {
        var total = 0;
        
        $(".cpm").each(function() {
            if (isNaN(parseFloat($(this).val()))) {
              total += 0;
            } else {
              total += parseFloat($(this).val());
            }
        });

        total_necesidad=total*12;
        redondeo=Math.round(total_necesidad);

        document.getElementById('sNecesidad').innerHTML = total_necesidad;
        $("#necesidad_anual").val(redondeo);

        document.getElementById('sNecesidad').innerHTML = redondeo;
        document.getElementById('sFalta').innerHTML = document.getElementById('sNecesidad').innerHTML - document.getElementById('sProrroteo').innerHTML;
    }

    function suma_meses() {
        var total1 = 0;
        
        
        $(".suma_mes").each(function() {
            if (isNaN(parseFloat($(this).val()))) {
              total1 += 0;
            } else {
              total1 += parseFloat($(this).val());
            }
        });
        document.getElementById('sProrroteo').innerHTML = total1;
        document.getElementById('sFalta').innerHTML = document.getElementById('sNecesidad').innerHTML - document.getElementById('sProrroteo').innerHTML;
    }

    function sumar_necesidad() {
        var total1 = 0;
        var total2 = 0;
        
        $(".suma_mes").each(function() {
            if (isNaN(parseFloat($(this).val()))) {
              total1 += 0;
            } else {
              total1 += parseFloat($(this).val());
            }
        });
        $(".necesidad").each(function() {
            if (isNaN(parseFloat($(this).val()))) {
              total2 += 0;
            } else {
              total2 += parseFloat($(this).val());
            }
        });
        document.getElementById('sProrroteo').innerHTML = total1;
        document.getElementById('sNecesidad').innerHTML = total2;
        document.getElementById('sFalta').innerHTML = document.getElementById('sNecesidad').innerHTML - document.getElementById('sProrroteo').innerHTML;
    }


$("#btnGuardarProducto").click(function() {
    event.preventDefault();

    var msg = "";

    var _token = $("#token_editar_producto").val();
    var url = '{{route("grabar_producto_can")}}';
    
    var id = $('#id').val();
    var cpma = $('#cpma').val();
    var necesidad_anual = $('#necesidad_anual').val();
    var mes1 = $('#mes1').val();
    var mes2 = $('#mes2').val();
    var mes3 = $('#mes3').val();
    var mes4 = $('#mes4').val();
    var mes5 = $('#mes5').val();
    var mes6 = $('#mes6').val();
    var mes7 = $('#mes7').val();
    var mes8 = $('#mes8').val();
    var mes9 = $('#mes9').val();
    var mes10 = $('#mes10').val();
    var mes11 = $('#mes11').val();
    var mes12 = $('#mes12').val();
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
            "cpma": cpma,
            "necesidad_anual": necesidad_anual,
            "mes1": mes1,
            "mes2": mes2,
            "mes3": mes3,
            "mes4": mes4,
            "mes5": mes5,
            "mes6": mes6,
            "mes7": mes7,
            "mes8": mes8,
            "mes9": mes9,
            "mes10": mes10,
            "mes11": mes11,
            "mes12": mes12,
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


            <form id="form_can_producto" class="form-horizontal">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_editar_producto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="box-body">
                    <input type="hidden" id="id"  value="<?php echo $id;?>" name="id">
                    <input type="hidden" id="nivel_farma" name="nivel_farma" value="<?php echo $nivel;?>" />
                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-11 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Producto</b></span>
                                <textarea disabled style="font-size:12px;" rows="2"  id="descripcion" placeholder="descripcion"  name="descripcion"  class="form-control">{{$data[0]->descripcion}}                                
                                </textarea>
                                <span class="help-block with-errors"></span>
                            </div>                           
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> CPMA</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->cpma}}" type="text" id="cpma" placeholder="CPMA"   name="cpma" required class="form-control cpm" onkeyup="calculo();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                                                    
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Necesidad</b></span>
                                <input style="font-size:12px;" type="number" min="0" id="necesidad_anual" placeholder="Necesidad Anual"  name="necesidad_anual" value = "{{$data[0]->necesidad_anual}}" required class="form-control necesidad" onkeyup="sumar_necesidad();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error"> 
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 1</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes1}}" type="number" min="0" id="mes1" placeholder="Enero"  name="mes1" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>    
                        <div class="col-md-3 form-group has-error">                     
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 2</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes2}}" type="number" min="0" id="mes2" placeholder="Febrero"  name="mes2" required   class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 3</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes3}}" type="number" min="0" id="mes3" placeholder="Marzo"  name="mes3" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 4</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes4}}" type="number" min="0" id="mes4" placeholder="Abril"  name="mes4" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>                        
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 5</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes5}}" type="number" min="0" id="mes5" placeholder="Mayo"  name="mes5" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 6</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes6}}" type="number" min="0" id="mes6" placeholder="Junio"  name="mes6" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 7</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes7}}" type="number" min="0" id="mes7" placeholder="Julio"  name="mes7" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 8</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes8}}" type="number" min="0" id="mes8" placeholder="Agosto"  name="mes8" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>                        
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 9</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes9}}" type="number" min="0" id="mes9" placeholder="Setiembre"  name="mes9" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>                        
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 10</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes10}}" type="number" min="0" id="mes10" placeholder="Octubre"  name="mes10" required  class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 11</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes11}}" type="number" min="0" id="mes11" placeholder="Noviembre"  name="mes11"  required class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Mes 12</b></span>
                                <input style="font-size:12px;" value = "{{$data[0]->mes12}}" type="number" min="0" id="mes12" placeholder="Diciembre"  name="mes12"  required class="form-control suma_mes" onkeyup="suma_meses();">
                                <span style="font-size:9px;" class="help-block with-errors"></span>
                            </div>                           
                        </div>                        
                    </div>
                </div>
                <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3 form-group">
                            <b><span>Necesidad: </span> <span id="sNecesidad"></span></b>
                        </div>
                        <div class="col-md-3 form-group">
                            <b><span>Total Prorrateo: </span> <span id="sProrroteo"></span></b>
                        </div>
                        <div class="col-md-5 form-group">
                            <b><span>Falta para completar su Prorrateo: </span> <span id="sFalta"></span></b>
                        </div>
                        
                    </div> 
                <div class="modal-footer">
                    <a href="#" style="margin-top: 6px;" class="btn btn-success" id="btnGuardarProducto">Guardar</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
