<script>


    function delete_table_tr_campo($tabla_relacionada, $boton_eliminar_item, $copiar_tabla_datos){
        $('#'+$tabla_relacionada+' tbody').on('click', 'button.'+$boton_eliminar_item+'', function () {
            var obj = $(this);
            obj.parent().parent().remove();  
        });
    }
  
    function delete_table_tr_campo_balance($tabla_relacionada, $boton_eliminar_item, $copiar_tabla_datos){
        $('#'+$tabla_relacionada+' tbody').on('click', 'button.'+$boton_eliminar_item+'', function () {
            var obj = $(this);
            obj.parent().parent().remove();  
            actualizarTotalIngreso();
            actualizarTotalEgreso();
        });
    }
  
    function calculo_horas_peso(peso,hora,ao,pi){
      var peso = parseFloat($('#' + peso).val());
      var hora = parseFloat($('#' + hora).val());
      console.log(peso+' - '+hora);
      
      let resultado = 0;
      if (!isNaN(peso) && !isNaN(hora)) {
          resultado = peso * 0.5 * hora;
          $('#' + pi).val(Math.round(resultado)); // Mostrar resultado redondeado en #pi
          $('#' + ao).val(Math.round((resultado / 3))); // Mostrar resultado / 3 redondeado en #ao
      } else {
          $('#' + pi).val('');
          $('#' + ao).val('');
      }
      
    }
  
    function calculo_horas_peso2(peso,hora,ao,pi){
      var peso = parseFloat($('#' + peso).val());
      var hora = parseFloat($('#' + hora).val());
      let resultado = 0;
      if (!isNaN(peso) && !isNaN(hora)) {
          resultado = peso * 0.5 * hora;
          $('#' + pi).val(Math.round(resultado)); // Mostrar resultado redondeado en #pi
          $('#' + ao).val(Math.round((resultado / 3))); // Mostrar resultado / 3 redondeado en #ao
      } else {
          $('#' + pi).val('');
          $('#' + ao).val('');
      }
    }
    
    function lista_table_balanceH(
    $tabla_relacionada, 
    $input_descripcion, 
    $descripcion_caracter_fisico_id,
    $input_descripcion_detalle, 
    $input_id_relacionado, 
    $boton_eliminar_item,
    $tipo_balance,
    $valor_tabla,
    $id_caracter_fisico,
    id,
    tabla_tipo,
    secuencia
    ){ 
      var ind = $('#'+$tabla_relacionada+' tbody tr').length; 
      if(ind > 0){
          $("#"+$input_id_relacionado).each(function(){
              var ind_tmp = $(this).data("id");
              if(ind_tmp >= ind){
                  ind=Number(ind_tmp)+1;
              }
          });           
      } 
      
      let camposHidden ='';
      camposHidden +='<input type="hidden" name="es_visible[]" value="1">';
      camposHidden +='<input type="hidden" name="secuencia[]" value="'+secuencia+'">';
     
      if (id <= 9) {
          camposHidden +='<input type="hidden" name="'+$descripcion_caracter_fisico_id+'[]" value="'+id+'0'+ind+'">';
      } else if (id >= 10 && id <= 99) {
          camposHidden +='<input type="hidden" name="'+$descripcion_caracter_fisico_id+'[]" value="'+id+''+ind+'">';
      }else{
        camposHidden +='<input type="hidden" name="'+$descripcion_caracter_fisico_id+'[]" >';
      }
  
      if($tipo_balance=='ingreso'){
          camposHidden +='<input type="hidden" name="id_detalle_ingreso[]" value="0">';
          camposHidden +='<input type="hidden" name="descripcion_ingreso[]">';
      } else {
          camposHidden +='<input type="hidden" name="id_detalle_egreso[]" value="0">';
          camposHidden +='<input type="hidden" name="descripcion_egreso[]">';
      }
      camposHidden +='<input type="hidden" name="'+$id_caracter_fisico+'[]" value="'+id+'">';
      
      camposHidden +='<input type="hidden" class="form-control '+$input_id_relacionado+' typeahead tt-query" id="'+$input_id_relacionado+''+ind+'" name="'+$input_id_relacionado+'[]" data-id='+ind+' style="height: 28px !important;" required>';
  
      let newRow = '<tr class="limpiar_al_iniciar">';
      newRow += '<td colspan="2" width="60%">';
      newRow += camposHidden;
      
      if(tabla_tipo > 0){
        newRow +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
          $.ajax({
              url: 'intervencion_terapeuticas_ajax', 
              type: 'GET', 
              dataType: 'json', 
              success: function(response) {
                  let item_valor_combo = '';
                  response.forEach(function(item) {
                      if(tabla_tipo == item.tipo) {
                          item_valor_combo += '<option value=' + item.id + '>' + item.descripcion_intervencion_t + '</option>';
                      }
                  });
                  newRow += '<select class="form-control" name="valor_tabla_ingreso[]">' + item_valor_combo + '</select>';
                  
                  newRow += '</td>';
                  newRow += '<td colspan="2">';
                  newRow += '<input name="'+$input_descripcion_detalle+'[]" id="'+$input_descripcion_detalle+''+ind+'" type="number" step="1" type="text" class="form-control '+$tipo_balance+'" required>';
                  newRow += '</td>';
                  newRow += '<td>';
                  newRow += '<button type="button" tabindex="46" class="btn btn-danger '+$boton_eliminar_item+' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
                  newRow += '</td>';
                  newRow += '</tr>';
                  
                  $('#'+$tabla_relacionada+' tbody').append(newRow);
                  $('#'+$input_descripcion+id+''+ind).focus();  
              },
              error: function(xhr, status, error) {
                  console.error('Error:', error);
              }
          });
      } else {
          newRow +='<input type="hidden" name="'+$valor_tabla+'[]">';
          newRow += '<input type="text" required="" name="'+$input_descripcion+'[]" id="'+$input_descripcion+id+''+ind+'" class="form-control '+$input_descripcion+id+'">';
          newRow += '</td>';
          newRow += '<td colspan="2">';
          newRow += '<input name="'+$input_descripcion_detalle+'[]" id="'+$input_descripcion_detalle+''+ind+'" type="number" step="1" type="text" class="form-control '+$tipo_balance+'" required>';
          newRow += '</td>';
          newRow += '<td>';
          newRow += '<button type="button" tabindex="46" class="btn btn-danger '+$boton_eliminar_item+' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
          newRow += '</td>';
          newRow += '</tr>';
  
          $('#'+$tabla_relacionada+' tbody').append(newRow); 
          $('#'+$input_descripcion+id+''+ind).focus();  
      }
    }
  /**********************INGRESOS**********************/
    function actualizarTotalIngreso() {
      let total = 0;
      $('.ingreso').each(function() {
          total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
      });
      $('#total_ingreso').val(total); // Establecer el valor total en el input de total_ingreso
    }
    
    /**********************EGRESOS**********************/
    function actualizarTotalEgreso() {
      let total = 0;
      $('.egreso').each(function() {
          total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
      });
      $('#total_egreso').val(total); // Establecer el valor total en el input de total_ingreso
    }
  </script>