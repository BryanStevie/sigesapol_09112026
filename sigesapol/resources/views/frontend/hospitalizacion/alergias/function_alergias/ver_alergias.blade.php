<script>
  
    $('body').on('click', '#verAlergias', function () {
      
      $('#verTituloFormularioAlergiasM').html("Ver datos Alergia Médica");
      $('#verModalAlergiasM').modal('show');
      var id_alergia = $(this).data('id_alergia');
      $("#verTblAlergia tbody tr").remove();
      ver_medicina_alergias(id_alergia);
    });

    function ver_medicina_alergias(id){
     
        var route='{{url('ver_lergias_medicas')}}';
        $.ajax({
          url:route,
          type:'POST',
          dataType:'JSON',
          data: {
            id_alergia: id, 
          },
          success: function(data){
            if(data!=null){
                  var newRow = "";
                  newRow += '<tr>';
                  newRow +=
                      '<td><div class="panel panel-info panel-bordered panel-sm">'+
                        '<div class="form-group"> '+
                          '<div class="panel-body"> '+
                            '<div class="col-md-3" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>MEDICAMENTO</label></div> '+
                            '<div class="col-md-4" style="margin-left:0px;padding-left:0px"> '+
                                '<input type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio"  tabindex="112" value="'+data[0].medicamento+'" '+
                                'class="input-sm" style="margin-left:14px; width:100%; text-align:left" disabled /> '+
                            '</div>'+
                            '<div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>CAUSA</label> </div>'+ 
                            '<div class="col-md-3"> '+
                              '<input type="text" class="form-control" value="'+data[0].causa+'"  tabindex="113" data-date-end-date="0d" autocomplete="off" disabled/>'+
                            '</div> '+
                            '<div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="2" placeholder="Relato m&eacute;dico o caracteristicas de la Alergia" disabled>'+data[0].resumen+'</textarea>'+
                            '</div></div></div></div></td>';
                            newRow += '</tr>';
                  $('#verTblAlergia tbody').append(newRow);
                }
            }
        });
    }

</script>
