
<script> 
    $('body').on('click', '#verFormatoIncicentesEA', function () {
        var id_formato = $(this).data('id_formato');
        $('#tituloFormularioIncidente').html("Formato de registro y reporte de Incidentes y Eventos Adversos");
        $('#verModalIncidenteEA').modal('show');
        ver_incidencia(id_formato);
    });
    
    function ver_incidencia(id){
        getDiagnosticosVer();
        var route='{{url('ver_incidencia_emergencia')}}';
        $.ajax({
          url:route,
          type:'POST',
          dataType:'JSON',
          data: {
            id_incidencia: id, 
          },  
          success: function(data){
                if(data!=null){
                    $("#add_PERS_CONV_EVEAD_R").attr("disabled",true);
                    $("#add_ACCI_CORRECT_P").attr("disabled",true);
                    $("#add_ACCI_PREVENT_P").attr("disabled",true);

                    verCargarCabeceraModal(data.id_asegurado);  
                    $('#nombre_servicio_ver').html(data.nombre_servicio);
                    $('#fecha_notificacion').val(data.fecha_notificacion);
                    $('#fecha_incidente_v').val(data.fecha_verificacion);
                    
                    //NOTIFICADOR
                    if(data.notificador_tipo_personal==='MEDICO'){
                        document.getElementById("personal_notifica1_n").checked = true;
                        $('#notificador_tipo_personal_descripcion_n').val(data.notificador_tipo_personal_descripcion); 
                        $("#personal_que_notifica_n").hide();
                    }
                    if(data.notificador_tipo_personal==='NO MEDICO'){
                        document.getElementById("personal_notifica2_n").checked = true;
                        $('#personal_que_notifica_n').show(); 
                        $('#notificador_tipo_personal_descripcion_n').val(data.notificador_tipo_personal_descripcion); 
                    }
                    if(data.notificador_tipo_personal==="ESTUDIANTE"){
                        document.getElementById("personal_notifica3_n").checked = true;
                        $('#personal_que_notifica_n').show(); 
                        $('#notificador_tipo_personal_descripcion_n').val(data.notificador_tipo_personal_descripcion); 
                    }
                    if(data.notificador_tipo_registro==="INCIDENTE"){
                        document.getElementById("tipo_incidente1_n").checked = true; 
                        $("#tipo_de_eventos_adversos_n").hide();
                    }
                    if(data.notificador_tipo_registro==="EVENTO ADVERSO"){
                        document.getElementById("tipo_incidente2_n").checked = true;
                        $('#selecTipoAdv_n').val(data.notificador_tipo_evento_a).trigger('change.select2');
                        $('#tipo_de_eventos_adversos_n').show(); 
                    }
                    $('#fecha_incidente_suceso_n').val(data.notificador_fecha_evento);
                    $('#hora_n').val(data.notificador_hora_evento);
                    $('#notificador_observaciones_n').val(data.notificador_observaciones);
                    //RESPONSABLE
                    var cuerpo_PERS_CONV_EVEAD_R = '' ;
                    
                    $.each(data.convocados, function (key, value) { 
                        cuerpo_PERS_CONV_EVEAD_R = cuerpo_PERS_CONV_EVEAD_R+
                        '<tr >'+ 
                        ' <td style="width: 100%;"><input type="text" class="form-control  input-sm"  '+  
                        '   value="'+value['nombre']+'" style="width:350px;"  disabled>'+ 
                        '</tr>';
                    }); 
                    $("#cuerpo_PERS_CONV_EVEAD_R").html(cuerpo_PERS_CONV_EVEAD_R);
                    $('#responsable_analisis_causas_r').val(data.analisis_causas);
                    
                    if(data.responsable_categoria_adverso){
                        lista_parametros('CATEGORIA_EV_ADV','categoria_evento_adverso_r','combo','TABLA_PARAMETROS',data.responsable_categoria_adverso);
                    }
                    
                    if(data.responsable_tipo_adverso){
                        lista_parametros_by_id(data.responsable_categoria_adverso,'categoria_tipo_evento_adverso_r','combo','TABLA_PARAMETROS',data.responsable_tipo_adverso); 
                    }
                    //PLAN DE MEJORA
                    var cuerpo_ACCI_CORRECT_P = '' ;
                    $.each(data.correctivas, function (key, value) { 
                        if(value.implementado==='on'){
                            cuerpo_ACCI_CORRECT_P = cuerpo_ACCI_CORRECT_P+
                        '<tr >'+ 
                        ' <td style="width: 100%;"><input type="text" class="form-control  input-sm"  '+  
                        '   value="'+value.acciones_correctivas+'" style="width:350px;"  disabled></td>'+
                        ' <td style="width: 13%;"><input type="checkbox" checked class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                        '  disabled> Implementado'+ 
                        ' </td>'+  
                        '</tr>';
                        }else{
                            cuerpo_ACCI_CORRECT_P = cuerpo_ACCI_CORRECT_P+
                        '<tr >'+ 
                        ' <td style="width: 100%;"><input type="text" class="form-control  input-sm"  '+  
                        '   value="'+value.acciones_correctivas+'" style="width:350px;"  disabled></td>'+
                        ' <td style="width: 13%;"><input type="checkbox"  class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                        '  disabled> Implementado'+ 
                        ' </td>'+  
                        '</tr>';
                        }
                    }); 
                    $("#cuerpo_ACCI_CORRECT_P").html(cuerpo_ACCI_CORRECT_P);

                    var cuerpo_ACCI_PREVENT_P = '' ;
                    $.each(data.preventivas, function (key, value) { 
                        if(value.implementado==='on'){
                            cuerpo_ACCI_PREVENT_P = cuerpo_ACCI_PREVENT_P+
                        '<tr >'+ 
                        ' <td style="width: 100%;"><input type="text" class="form-control  input-sm"  '+  
                        '   value="'+value.acciones_preventivas+'" style="width:350px;"  disabled></td>'+
                        ' <td style="width: 13%;"><input type="checkbox" checked class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                        '  disabled> Implementado'+ 
                        ' </td>'+  
                        '</tr>';
                        }else{
                            cuerpo_ACCI_PREVENT_P = cuerpo_ACCI_PREVENT_P+
                        '<tr >'+ 
                        ' <td style="width: 100%;"><input type="text" class="form-control  input-sm"  '+  
                        '   value="'+value.acciones_preventivas+'" style="width:350px;"  disabled></td>'+
                        ' <td style="width: 13%;"><input type="checkbox"  class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                        '  disabled> Implementado'+ 
                        ' </td>'+  
                        '</tr>';
                        }
                    }); 
                    $("#cuerpo_ACCI_PREVENT_P").html(cuerpo_ACCI_PREVENT_P);
                    $("#verificacion_plan_mejora_v").val(data.verificacion_plan_mejora);
                }
            }
        });
    }

    function verCargarCabeceraModal(id_asegurado) {  
      $.ajax({
        url: '{{ url('getAseguradoby_id/') }}/' + id_asegurado,
        type: 'GET',
        dataType: 'JSON',
        success: function(data) { 
          $('#numero_doc_ver').html(data.nro_doc_ident);
          $('#nombres_ver').html(data.paterno +" " +data.materno+" " +data.nombre);
          $('#tipo_sangre_ver').html(data.tipo_sangre);
          $('#edad_ver').html(data.edad_corta);
          $('#sexo_ver').html(data.sexo);
          $('#hc_ver').html(data.nro_historia);
        }
      });
    }

    function getDiagnosticosVer(){
        var id_emergencia = $('#id_hospitalizacion_incidencia').val();
        var numero_doc = $('#numero_doc2').val();
        

        var var_url='{{url('incidente_diagnosticos_emergencia')}}';
        $("#diagnosticos_ver").empty();
        $.ajax({
          url:var_url,
          type:'POST',
          data: {
            id_emergencia: id_emergencia,
              dni_paciente: numero_doc
          }, 
          dataType:'JSON',
          success: function(data){
            $.each(data, function (key, value) {
                console.log(value.diagnosticos);
              $("#diagnosticos_ver").append("<tr><td>"+(key+1)+". "+value.diagnosticos+"</td></tr>");
            });
          }
        })
    }
</script>
  