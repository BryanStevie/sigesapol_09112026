<script>
      
   

      $('body').on('click', '#verEvoluciones', function () {  
 
        $("#registrarEvoluciones").attr("id","registrarEvoluciones");
        $("#registrarEvoluciones").attr("disabled",false);
        $('#FormularioEvoluciones').trigger("reset");
        $('#tituloFormularioEvoluciones').html("Proceso de Atención de Enfermería");
        $('#modalEvoluciones').modal('show');
  
        $("#tbl_DIAGNOSTICO_ENFERMERIA tbody").empty(); 
        $("#tbl_INTERVENCIONES_ENFERMERIA tbody").empty(); 
        $("#tbl_PLAN_CUIDADO_ENFERMERIA tbody").empty(); 
        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty(); 
        $("#tbl_CPMS_ENFERMERIA tbody").empty(); 
         
        //cargaremos los datos de vista previa por ajax, bloqueando los campos
        $("#descripcion_turno").attr('disabled','disabled');

        $("#descripcion_subjetivo").attr('disabled','disabled');
        $("#descripcion_objetivo").attr('disabled','disabled');
        $("#descripcion_intervenciones").attr('disabled','disabled');
        $("#descripcion_evaluaciones").attr('disabled','disabled');
        
        $("#add_DIAGNOSTICO_ENFERMERIA").hide();;
        $("#add_PLAN_CUIDADO_ENFERMERIA").hide();
        $("#add_INTERVENCIONES_ENFERMERIA").hide();
        $("#add_EVALUACIONES_ENFERMERIA").hide();
        $("#add_CPMS_ENFERMERIA").hide();


        ///CARGA DE DATOS
        const $p = [
            $(this).data('id_evoluciones'), 
        ]; 
        ver_evoluciones($p);
        
    });

    
 
  function ver_evoluciones($p){
        var var_url='{{url('ver_nota_ingreso_evoluciones')}}';
        $.ajax({
          url:var_url,
          type:'POST',
          dataType:'JSON',
          data: {
            id_evoluciones: $p[0], 
          },  
          success: function(data){
            console.log(data); 
			      $('#descripcion_turno').val(data.nota_enfermeria[0].descripcion_turno).change();  

            $("#descripcion_subjetivo").val(data.nota_enfermeria[0].descripcion_subjetivo);
            $("#descripcion_objetivo").val(data.nota_enfermeria[0].descripcion_objetivo);
            $("#descripcion_intervenciones").val(data.nota_enfermeria[0].descripcion_intervenciones);
            $("#descripcion_evaluaciones").val(data.nota_enfermeria[0].descripcion_evaluaciones);  
            $("#fecha_registro_evoluciones_inicial").val(data.nota_enfermeria[0].fecha_evol);  

 
//====================================================================================================================//

            var cuerpo_DIAGNOSTICO_ENFERMERIA = '' ;
            $("#cuerpo_DIAGNOSTICO_ENFERMERIA").empty();
            $.each(data.nota_enfermeria_diagnostico, function (key, value) { 
              cuerpo_DIAGNOSTICO_ENFERMERIA = cuerpo_DIAGNOSTICO_ENFERMERIA+
                  '<tr >'+ 
                  ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" '+  
                  '   value="'+value.descrip_abrev+'"  disabled>'+  
                  ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                  '   value="'+value.valor_de_seleccion+'"  disabled>'+ 
                  ' </td>'+ 
                  '</tr>';
            }); 
            $("#cuerpo_DIAGNOSTICO_ENFERMERIA").html(cuerpo_DIAGNOSTICO_ENFERMERIA);
//====================================================================================================================//

            var cuerpo_PLAN_CUIDADO_ENFERMERIA = '' ;
            $("#cuerpo_PLAN_CUIDADO_ENFERMERIA").empty();
            $.each(data.nota_enfermeria_plan_cuidado, function (key, value) { 
              cuerpo_PLAN_CUIDADO_ENFERMERIA = cuerpo_PLAN_CUIDADO_ENFERMERIA+
                  '<tr >'+ 
                  ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" '+  
                  '   value="'+value.descrip_abrev+'"  disabled>'+  
                  ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                  '   value="'+value.valor_de_seleccion+'"  disabled>'+ 
                  ' </td>'+ 
                  '</tr>';
            }); 
            $("#cuerpo_PLAN_CUIDADO_ENFERMERIA").html(cuerpo_PLAN_CUIDADO_ENFERMERIA);
//====================================================================================================================//

            var cuerpo_INTERVENCIONES_ENFERMERIA = '' ;
            $("#cuerpo_INTERVENCIONES_ENFERMERIA").empty();
            $.each(data.nota_enfermeria_intervenciones, function (key, value) { 
              cuerpo_INTERVENCIONES_ENFERMERIA = cuerpo_INTERVENCIONES_ENFERMERIA+
                  '<tr >'+ 
                  ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" '+  
                  '   value="'+value.descrip_abrev+'"  disabled>'+  
                  ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                  '   value="'+value.valor_de_seleccion+'"  disabled>'+ 
                  ' </td>'+ 
                  '</tr>';
            }); 
            $("#cuerpo_INTERVENCIONES_ENFERMERIA").html(cuerpo_INTERVENCIONES_ENFERMERIA);
//====================================================================================================================//

            var cuerpo_EVALUACIONES_ENFERMERIA = '' ;
            $("#cuerpo_EVALUACIONES_ENFERMERIA").empty();
            $.each(data.nota_enfermeria_evaluaciones, function (key, value) { 
              cuerpo_EVALUACIONES_ENFERMERIA = cuerpo_EVALUACIONES_ENFERMERIA+
                  '<tr >'+ 
                  ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" '+  
                  '   value="'+value.descrip_abrev+'"  disabled>'+  
                  ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" '+  
                  '   value="'+value.valor_de_seleccion+'"  disabled>'+ 
                  ' </td>'+ 
                  '</tr>';
            }); 
            $("#cuerpo_EVALUACIONES_ENFERMERIA").html(cuerpo_EVALUACIONES_ENFERMERIA);
//====================================================================================================================//

            var cuerpo_CPMS_ENFERMERIA = '' ;
            $("#cuerpo_CPMS_ENFERMERIA").empty();
            $.each(data.nota_enfermeria_cpms, function (key, value) { 
              cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA+
                  '<tr >'+ 
                  ' <td style="width: 100%;"><input type="text" class="form-control input-sm" '+ 
                  ' style="height: 28px !important;font-weight: 800;" value="'+value.descripcion+'"  disabled> '+ 
                  ' </td>'+ 
                  '</tr>';
            }); 
            $("#cuerpo_CPMS_ENFERMERIA").html(cuerpo_CPMS_ENFERMERIA);
//====================================================================================================================//



          }
        })
  };


</script>