  <style>
    .ui-menu-item {
        max-width: 800px; /* Establece el ancho máximo en píxeles */
        width: 100%; /* Esto garantiza que el campo se ajuste al ancho máximo pero pueda reducirse si es necesario */
    }
  </style>
 <script> 

$('#add_DIAGNOSTICO_ENFERMERIA').on('click',function(){
    
  lista_table_tr_campo_detalle_evolucion(
        'tbl_DIAGNOSTICO_ENFERMERIA', 
        'descripcion_DIAGNOSTICO_ENFERMERIA', 
        'cantidad_detalle_DIAGNOSTICO_ENFERMERIA', 
        'id_relacionado_DIAGNOSTICO_ENFERMERIA', 
        'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA',
        'NANDA',
        'input',
        'null',
        'null',
        'SI'
        );

  });

  function evitarLetras(event) {
    var charCode = event.which ? event.which : event.keyCode;
    var inputValue = String.fromCharCode(charCode);

    // Expresión regular para permitir solo números y un punto decimal
    var pattern = /^[\d.]+$/;

    if (!pattern.test(inputValue)) {
      event.preventDefault();
    }

    return true;
  }
  
  $('#add_INTERVENCIONES_ENFERMERIA').on('click',function(){
    
    lista_table_tr_campo_detalle_evolucion(
        'tbl_INTERVENCIONES_ENFERMERIA', 
        'descripcion_INTERVENCIONES_ENFERMERIA', 
        'cantidad_detalle_INTERVENCIONES_ENFERMERIA', 
        'id_relacionado_INTERVENCIONES_ENFERMERIA', 
        'boton_eliminar_item_INTERVENCIONES_ENFERMERIA',
        'NIC',
        'input',
        'null',
        'null',
        'SI'
        );

  });

  $('#add_PLAN_CUIDADO_ENFERMERIA').on('click',function(){
    
    lista_table_tr_campo_detalle_evolucion(
        'tbl_PLAN_CUIDADO_ENFERMERIA', 
        'descripcion_PLAN_CUIDADO_ENFERMERIA', 
        'cantidad_detalle_PLAN_CUIDADO_ENFERMERIA', 
        'id_relacionado_PLAN_CUIDADO_ENFERMERIA', 
        'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA',
        'NOC',
        'combo',
        '1||2||3||4||5',
        '1||2||3||4||5',
        'SI'
        );
  
  });

$('#add_CPMS_ENFERMERIA').on('click',function(){
    
  lista_table_tr_campo_detalle_evolucion(
        'tbl_CPMS_ENFERMERIA', 
        'descripcion_CPMS_ENFERMERIA', 
        'descripcion_detalle_CPMS_ENFERMERIA', 
        'id_relacionado_CPMS_ENFERMERIA', 
        'boton_eliminar_item_CPMS_ENFERMERIA',
        'null',
        'sin_elemento_adicional',
        'null',
        'null',
        'SI',
        'SI'
        );

  });

  $('#add_EVALUACIONES_ENFERMERIA').on('click',function(){
    copiar_datos_otra_table();
  }); 

  delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA','boton_eliminar_item_DIAGNOSTICO_ENFERMERIA','');
  delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA','boton_eliminar_item_INTERVENCIONES_ENFERMERIA','');
  delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA','boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA','copiar_tabla_datos');
  delete_table_tr_campo('tbl_CPMS_ENFERMERIA','boton_eliminar_item_CPMS_ENFERMERIA','');
   
  $('#crearEvoluciones').click(function () {

    $('#id').val('');
    $("#registrarEvoluciones").attr("id","registrarEvoluciones");
    $("#registrarEvoluciones").attr("disabled",false);
    $('#FormularioEvoluciones').trigger("reset");
    $('#tituloFormularioEvoluciones').html("Proceso de Atención de Enfermería");
    $('#modalEvoluciones').modal('show');

    $("#descripcion_turno").removeAttr('disabled');

    $("#descripcion_subjetivo").removeAttr('disabled');
    $("#descripcion_objetivo").removeAttr('disabled');
    $("#descripcion_intervenciones").removeAttr('disabled');
    $("#descripcion_evaluaciones").removeAttr('disabled');

    $("#add_DIAGNOSTICO_ENFERMERIA").show();;
    $("#add_PLAN_CUIDADO_ENFERMERIA").show();
    $("#add_INTERVENCIONES_ENFERMERIA").show();
    $("#add_EVALUACIONES_ENFERMERIA").show();
    $("#add_CPMS_ENFERMERIA").show();

    var id_atencion_evolucion_listado = $('#id_atencion_evolucion_listado').val();
    var id_tipo_atenciones_evolucion_listado = $('#id_tipo_atenciones_evolucion_listado').val(); 
    var id_nota_enfermeria_evolucion_listado = $('#id_nota_enfermeria_evolucion_listado').val(); 
    
    $('#id_atencion_evolucion_registro').val(id_atencion_evolucion_listado);
    $('#id_tipo_atenciones_evolucion_registro').val(id_tipo_atenciones_evolucion_listado); 
    $('#id_nota_enfermeria_evolucion_registro').val(id_nota_enfermeria_evolucion_listado);  
    $("#fecha_registro_evoluciones_inicial").val('{{\Carbon\Carbon::now()->format('d/m/Y')}}');   
 
    $("#tbl_DIAGNOSTICO_ENFERMERIA tbody").empty(); 
    $("#tbl_INTERVENCIONES_ENFERMERIA tbody").empty(); 
    $("#tbl_PLAN_CUIDADO_ENFERMERIA tbody").empty(); 
    $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty(); 
    $("#tbl_CPMS_ENFERMERIA tbody").empty(); 

  });

  
  function cambiar_de_PestanaTab(descripcion_de_tab) {  
    var tab_seleccionado = document.querySelector('a[href="#' + descripcion_de_tab + '"]'); 
    tab_seleccionado.click();
  } 

  $(document).ready(function () {
    $('#registrarEvoluciones').click(function (e) {
      e.preventDefault(); 

      var descripcion_turno=$('#descripcion_turno').val();
      var descripcion_subjetivo=$('#descripcion_subjetivo').val();
      var descripcion_objetivo=$('#descripcion_objetivo').val();
      var descripcion_intervenciones=$('#descripcion_intervenciones').val();
      var descripcion_evaluaciones=$('#descripcion_evaluaciones').val(); 
      let tbl_DIAGNOSTICO_ENFERMERIA = $('#tbl_DIAGNOSTICO_ENFERMERIA tbody tr').length;
      let tbl_INTERVENCIONES_ENFERMERIA = $('#tbl_INTERVENCIONES_ENFERMERIA tbody tr').length;
      let tbl_PLAN_CUIDADO_ENFERMERIA = $('#tbl_PLAN_CUIDADO_ENFERMERIA tbody tr').length;
      let tbl_EVALUACIONES_ENFERMERIA = $('#tbl_EVALUACIONES_ENFERMERIA tbody tr').length;
      let tbl_CPMS_ENFERMERIA = $('#tbl_CPMS_ENFERMERIA tbody tr').length;
      
      if(descripcion_turno == null || descripcion_turno == '' || descripcion_turno.length <1){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Seleccione un TURNO VÁLIDO para el registro correspondiente.",
          icon: "error",
          timer: 2500,
        });
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        $('#descripcion_turno').next('.select2-container').addClass('borde-alerta');  
        setTimeout(function() {
          $('#descripcion_turno').focus();  
        }, 1000);
        return;
      }else{ 
        $('#descripcion_turno').next('.select2-container').removeClass('borde-alerta'); 
      }

      if(descripcion_subjetivo == null || descripcion_subjetivo.length <=5){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo SUBJETIVO.",
          icon: "error",
          timer: 2500,
        });
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        $('#descripcion_subjetivo').addClass('borde-alerta');
        setTimeout(function() {
          $('#descripcion_subjetivo').focus();  
        }, 1000);
        return;
      }else{
        $('#descripcion_subjetivo').removeClass('borde-alerta');
      }

      if(descripcion_objetivo == null || descripcion_objetivo.length <=5){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo OBJETIVO.",
          icon: "error",
          timer: 2500,
        });
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        $('#descripcion_objetivo').addClass('borde-alerta');
        setTimeout(function() {
          $('#descripcion_objetivo').focus();  
        }, 1000);
        return;
      }else{
        $('#descripcion_objetivo').removeClass('borde-alerta');
      }

      if(tbl_DIAGNOSTICO_ENFERMERIA <= 0){
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de DIAGNÓSTICOS.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        setTimeout(function() {
          $('#add_DIAGNOSTICO_ENFERMERIA').focus();  
        }, 1000);
        return;
      }

      if(tbl_INTERVENCIONES_ENFERMERIA <= 0){
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de EJECUCIÓN.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones"); 
        setTimeout(function() {
          $('#add_INTERVENCIONES_ENFERMERIA').focus();  
        }, 1000);
        return;
      }

      if(tbl_PLAN_CUIDADO_ENFERMERIA <= 0){
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de PLANEACIÓN.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        setTimeout(function() {
          $('#add_PLAN_CUIDADO_ENFERMERIA').focus();  
        }, 1000);
        return;
      }

      if(tbl_EVALUACIONES_ENFERMERIA <= 0){
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de EVALUACIÓN.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        setTimeout(function() {
          $('#add_EVALUACIONES_ENFERMERIA').focus();  
        }, 1000);
        return;
      }

      if(descripcion_intervenciones == null || descripcion_intervenciones.length <=5){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo DETALLE DE INTERVENCIONES.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        $('#descripcion_intervenciones').addClass('borde-alerta');
        setTimeout(function() {
          $('#descripcion_intervenciones').focus();  
        }, 1000);
        return;
      }else{
        $('#descripcion_intervenciones').removeClass('borde-alerta');
      }

      if(descripcion_evaluaciones == null || descripcion_evaluaciones.length <=5){ 
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo DETALLE DE EVALUACIONES.",
          icon: "error",
          timer: 2500,
        });
        //cambiar_de_PestanaTab("tab1defaultEvoluciones");
        cambiar_de_PestanaTab("tab0defaultEvoluciones");
        $('#descripcion_evaluaciones').addClass('borde-alerta');
        setTimeout(function() {
          $('#descripcion_evaluaciones').focus();  
        }, 1000);
        return;
      }else{
        $('#descripcion_evaluaciones').removeClass('borde-alerta');
      }

      if(tbl_CPMS_ENFERMERIA <= 0){
        swal({
          buttons: false,
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de CPMS.",
          icon: "error",
          timer: 2500,
        });
        cambiar_de_PestanaTab("tab2defaultEvoluciones");
        setTimeout(function() {
          $('#add_CPMS_ENFERMERIA').focus();  
        }, 1000);
        return;
      }

      var token=$('#_token').val();
      var route='{{url('registrar_nota_ingreso_evoluciones')}}';

      swal({
            buttons: true,
            title: "¿Desea Realizar el Registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete) { 

              var formData = new FormData($("#FormularioEvoluciones") [0]);
              $.ajax({
                data: formData,
                url:route,
                contentType: false,
                processData: false,
                headers: {'X-CSRF-TOKEN':token},
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'JSON',
                success: function(data){
                  if(data!=null){
                      if(data!=0){

                          $('#FormularioEvoluciones').trigger("reset");
                          $('#modalEvoluciones').modal('hide');

                          swal({
                            buttons: false,
                            title: "Registro Exitoso",
                            text: "Datos registrados Exitosamente.",
                            icon: "success",
                            timer: 1500,
                          });

                          window.open(('/pdf_nota_ingreso_evoluciones/'+data), '_blank');
                          ///LISTADO DE REGISTROS  
                          const $p = [
                            $('#fecha_registro_hoja_grafica_inicial').val(), 
                            $('#fecha_registro_hoja_grafica_inicial').val(),
                            $('#descripcion_turno_bus_listado').val(),
                            $(this).data('id_nota_enfermeria_listado'),
                          ];
                          listar_evoluciones($p);
                          //Actualizar Datos de la Tabla
                          //var oTable = $('#listaCategorias').dataTable();
                          //oTable.fnDraw(false);
                      }else{
                  
                        swal({
                          buttons: false,
                          title: "Error de Servicio de Registros",
                          text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                          icon: "error",
                          timer: 2500,
                        });
    
                      }
                }else{
                
                  swal({
                      buttons: false,
                      title: "Error de Servicio de Registros",
                      text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                      icon: "error",
                      timer: 2500,
                    });

                }
              }
            });
 
          } else {
               
              swal({
                buttons: false,
                title: "Operación Cancelada",
                text: "Proceso de Registro Cancelado.",
                icon: "error",
                timer: 1500,
              });

            }
          });


    });
  });


  function lista_table_tr_campo_detalle_evolucion(
        $tabla_relacionada,
        $input_descripcion,
        $input_descripcion_detalle,
        $input_id_relacionado,
        $boton_eliminar_item,
        $descrip_tipo_parametro,
        $tipo_de_elemento,
        $lista_de_value_en_combo,
        $lista_de_datos_en_combo,
        $habilita_busqueda
    ) {

        var ind = $('#' + $tabla_relacionada + ' tbody tr').length;
        if (ind > 0) {
            $("#" + $input_id_relacionado).each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
            fila_medicamentos = $('#' + $tabla_relacionada + ' tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];
        }

        var porcentaje = '';
        if ($tipo_de_elemento == 'input') {
            porcentaje = '80%';
        } else if ($tipo_de_elemento == 'combo') {
            porcentaje = '77%';
        } else if ($tipo_de_elemento == 'checkbox') {
            porcentaje = '82%';
        } else if ($tipo_de_elemento == 'fecha') {
            porcentaje = '76%';
        } else if ($tipo_de_elemento == 'nueve_elementos') {
            porcentaje = '12%';
        } else {
            porcentaje = '100%';
        }

        if ($tipo_de_elemento == 'combo') {
            if ($lista_de_value_en_combo != '' && $lista_de_datos_en_combo != '') {
                var item_valor_combo = '';
                const lista_de_value_en_combo_Array = $lista_de_value_en_combo.split("||");
                const lista_de_datos_en_combo_Array = $lista_de_datos_en_combo.split("||");
                for (var i = 0; i < lista_de_datos_en_combo_Array.length; i++) {
                    item_valor_combo += '<option value=' + lista_de_value_en_combo_Array[i] + '>' +
                        lista_de_datos_en_combo_Array[i] + '</option>';
                }
            }
        }

        var newRow = '<tr>'
        if ($tipo_de_elemento != 'nueve_elementos') {
            newRow += '<td style="width: ' + porcentaje + ';"><input type="text" name="' + $input_descripcion +
                '[]" id="' +
                $input_descripcion + '' + ind +
                '" class="form-control input-sm" value="" style="height: 28px !important;"/></td>'
            newRow += '<td> <input type="hidden" class="form-control ' + $input_id_relacionado +
                '  typeahead tt-query" id="' + $input_id_relacionado + '' + ind + '" name="' + $input_id_relacionado +
                '[]" data-id=' + ind + ' style="height: 28px !important;" required> </td>'

            if ($tipo_de_elemento == 'input') {
                newRow += '<td>';
                newRow += '<input name="' + $input_descripcion_detalle + '[]" id="' + $input_descripcion_detalle + '' +
                    ind + '" type="text" class="form-control" style="height: 28px !important;" onkeypress="return evitarLetras(event)" required>';
                newRow += '</td>'
            } else if ($tipo_de_elemento == 'combo') {
                newRow += '<td>';
                newRow += '<select class="form-control ' + $input_descripcion_detalle + '" id="' +
                    $input_descripcion_detalle + '' + ind + '" name="' + $input_descripcion_detalle +
                    '[]"  style="height: 28px !important;cursor:pointer;">' + item_valor_combo + '</select>';
                newRow += '</td>'
            } else if ($tipo_de_elemento == 'checkbox') {
                newRow += '<td>';
                newRow += '<input type="checkbox" id="' + $input_descripcion_detalle + '' + ind + '" name="' +
                    $input_descripcion_detalle + '' + ind + '" style="margin-left: 10px;cursor: pointer;">';
                newRow += '<label for="' + $input_descripcion_detalle + '' + ind +
                    '" style="cursor: pointer;"> IMPLEMENTADO </label> ';
                newRow += '</td>';
            } else if ($tipo_de_elemento == 'fecha') {
                newRow += '<td>';
                newRow +=
                    '<input type="text" class="form-control datepicker" style="height: 28px !important;" autocomplete="off"';
                newRow += 'name="' + $input_descripcion_detalle + '[]" id="' + $input_descripcion_detalle + '' + ind +
                    '" value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">';
                newRow += '</td>'
            }
        }
        
        newRow += '<td> <button type="button" tabindex="46" class="btn btn-danger ' + $boton_eliminar_item +
            ' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button>'
        newRow += '</tr>'

        $('#' + $tabla_relacionada + ' tbody').append(newRow);

        ///SI ES FECHA GENERAMOS FORMATO PARA FECHAS
        if ($tipo_de_elemento == 'fecha') {
            agregar_formato_fecha_campo_input_descripcion_detalle($input_descripcion_detalle + '' + ind);
        }


        if ($habilita_busqueda == 'SI') {
            $("#" + $input_descripcion + '' + ind).autocomplete({
                minLength: 2,
                delay: 500,
                source: function(request, response) {
                    var url_consulta = '';
                    //buscamos cuando es relacionado a CPMS
                    if ($input_descripcion_detalle == 'descripcion_detalle_CPMS_ENFERMERIA') {
                        url_consulta = '{{ url('obtener_procedimiento_convenio/') }}/' + request.term +
                            '/1/3/4';
                        //buscamos cuando es relacionado a personal convocado en incidencias
                    } else if ($input_descripcion_detalle == 'descripcion_detalle_PERS_CONV_EVEAD') {
                        url_consulta = '{{ url('parametro/personal_convocado/') }}/' + request.term;
                    } else {
                        //busqueda general de la tabla de parametros
                        url_consulta = '{{ url('parametro/listar_tipo_parametro/') }}/' + request.term +
                            '/' + $descrip_tipo_parametro;
                    }
                    console.log(url_consulta);
                    $.ajax({
                        url: url_consulta,
                        dataType: "json",
                        success: function(data) {
                            let listaDGSeleccionado = $("." + $input_id_relacionado).map(
                                function() {
                                    return parseInt($(this).val());
                                }).get();
                            let filtro = data.filter((element) => !listaDGSeleccionado.includes(
                                element.id));
                            response(filtro);
                        }
                    })
                },
                select: function(e, ui) {
                    var id_relacionado = ui.item.id;
                    var descripcion = ui.item.value;
                    $("#" + $input_id_relacionado + '' + ind).val(id_relacionado);
                    $("#" + $input_descripcion + '' + ind).attr('disabled', 'disabled');

                    if ($tabla_relacionada == 'tbl_PLAN_CUIDADO_ENFERMERIA') {
                        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
                    }
                }
            });


            $("#" + $input_descripcion + '' + ind).on('blur', function() {
                var id_relacionado = $("#" + $input_id_relacionado + '' + ind).val();
                if (id_relacionado == "") {
                    $("#" + $input_descripcion + '' + ind).val("");
                    return false;
                }
            });
        }

    }

</script>

