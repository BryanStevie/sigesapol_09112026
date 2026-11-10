<script>
  
  function pintar_puntos_by_hospitalizacion_monitoreo($id_hospitalizacion, $control_div_puntos, $control_div_puntos_leyenda, $tipo_registro, $tipo_uso, $id_tipo_atenciones){
    var arrayTextos = [];
    $.ajax({
    url:'{{url('pintar_puntos_monitoreo/')}}/'+$id_hospitalizacion+'/'+$tipo_registro+'/'+$id_tipo_atenciones,
    type:'GET',
    dataType:'JSON',
    success: function(data){
      $("#"+$control_div_puntos).empty(); 

      if($control_div_puntos_leyenda != ''){
        $("#"+$control_div_puntos_leyenda).empty(); 
      }

      $.each(data, function (key, valu) { 
        var created_at = new Date(valu.created_at);
        var year = created_at.getFullYear();
        var month = created_at.getMonth() + 1; // Sumamos 1 porque getMonth() devuelve un valor entre 0 y 11
        var day = created_at.getDate();
        var hours = created_at.getHours();
        var minutes = created_at.getMinutes();
        var seconds = created_at.getSeconds();

        var fecha = day + '/' + month + '/' + year;
        var hora = hours + ':' + minutes + ':' + seconds; 
        var campoMostrar='Fecha: '+ fecha+' - Hora: '+ hora+' - Proceso: '+ valu.descripcion_proceso;

        var valor=' <a data-id="'+ valu.id +'" data-id_atencion_delete="'+ $id_hospitalizacion +'" data-toggle="tooltip" data-placement="top" ' 
        valor= valor+' title="'+campoMostrar+'" href="javascript:void(0)" data-tipo_registro="'+ $tipo_registro +'"  data-id_tipo_atenciones="'+ $id_tipo_atenciones +'" '
        if($tipo_uso == 'REGISTRO'){
          valor= valor+' class="eliminarPuntoSeleccionado" data-control_div_puntos="'+ $control_div_puntos +'" ' 
        }else{
          valor= valor+' data-control_div_puntos="'+ $control_div_puntos +'" '
        } 
        valor= valor+' data-control_div_puntos_leyenda="'+ $control_div_puntos_leyenda +'" ' 
        valor= valor+' style="position:absolute;left:'+ valu.x +'px;top:'+ valu.y +'px; '
        valor= valor+' width:'+ valu.tama +'px;height:'+ valu.tama +'px; '
        valor= valor+' background-color:'+ valu.color +';border-radius:50%; '
        valor= valor+' border: 1px solid #000000;"> '
        if(valu.tama == 10){
          valor= valor+' <b style="color: #000;font-size:15px;padding:10px!important;">'+valu.descripcion_proceso+'</b></a > '; 
        }else if(valu.tama == 40){ 
          valor= valor+' <b style="color: #000;font-size:15px;padding-left:35px!important;">'+valu.descripcion_proceso+'</b></a > '; 
        }else if(valu.tama == 60){ 
          valor= valor+' <b style="color: #000;font-size:15px;padding-left:55px!important;">'+valu.descripcion_proceso+'</b></a > '; 
        }
        $("#"+$control_div_puntos).append(valor);

        var textos=valu.descripcion_proceso+'-'+valu.color;  
        var textosSeparados = textos.split(",");
        textosSeparados.forEach(function (texto) {
            if (arrayTextos.indexOf(texto) === -1) {
                arrayTextos.push(texto);
            }
        }); 

        arrayTextos.sort();
      });   

      if($control_div_puntos_leyenda != ''){
        for (var i = 0; i < arrayTextos.length; i++) {
          var texto = arrayTextos[i].split('-');
          var valor_leyenda='<li><span class="icono" style="background-color: '+texto[1]+';"></span>'+ texto[0] +'</li>';
          $("#"+$control_div_puntos_leyenda).append(valor_leyenda);    
        }  
      }  
                    
      }
    })
  };  

  
  function guardarPuntoMonitoreo(){ 
      var punto_x=$('#x').val(); 
      var punto_y=$('#y').val(); 
      var id_atencion=$('#id_hospitalizacion').val();
      var id_tipo_atenciones=$('#id_tipo_atenciones').val();
      var color=$('#tipo_proceso').val(); 
      var tamanio_proceso=$('#tamanio_proceso').val(); 
      var descripcion_proceso=$("#tipo_proceso option:selected").text()
      var tipo_registro=$('#tipo_registro').val(); 
        
      if(color == '*'){
        swal({
          buttons: false,
          title: "Operación Cancelada, Proceso no válido",
          text: "Seleccione un Proceso válido.",
          icon: "error",
          timer: 1500,
        }); 
        return;
      }

      if(x==''||y==''){
        alert('Seleccione una ubicación dentro del grófico mostrado');
        return;
      }
      
      registro_de_puntos_monitoreo(punto_x, punto_y, id_atencion, color, tamanio_proceso, descripcion_proceso, tipo_registro, id_tipo_atenciones);
  }

  function registro_de_puntos_monitoreo($punto_x, $punto_y, $id_hospitalizacion, $color, $tamanio_proceso, $descripcion_proceso, $tipo_registro, $id_tipo_atenciones){ 
    $.ajax({
      data: { 
        x: $punto_x,
        y: $punto_y,
        id_atencion: $id_hospitalizacion ,
        color: $color,
        tamanio_proceso: $tamanio_proceso,
        descripcion_proceso: $descripcion_proceso ,
        tipo_registro: $tipo_registro,
        id_tipo_atenciones: $id_tipo_atenciones
      }, 
      url:'{{url('plano_agregar_monitoreo')}}', 
      type:'POST', 
      dataType:'JSON',
      success: function(data){  
        console.log(data); 
      } 
    }) 
  }


  $('body').on('click', '.eliminarPuntoSeleccionado', function () { 
      var punto_id = $(this).data('id');   
      var control_div_puntos = $(this).data('control_div_puntos');   
      var control_div_puntos_leyenda = $(this).data('control_div_puntos_leyenda'); 
      var id_atencion_delete = $(this).data('id_atencion_delete');  
      var tipo_registro = $(this).data('tipo_registro'); 
      var id_tipo_atenciones = $(this).data('id_tipo_atenciones');    

      swal({
            buttons: true,
            title: "¿Desea Eliminar el Registro Seleccionado?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete) { 
              
              $.ajax({
                url:'{{url('destroy_pintar_puntos_monitoreo')}}/'+punto_id,
                type:'GET',
                dataType:'JSON',
                success: function(data){
                  if(data==1){

                    $('#FormularioPunto').trigger("reset");  
                    pintar_puntos_by_hospitalizacion(id_atencion_delete, control_div_puntos, control_div_puntos_leyenda, tipo_registro,'REGISTRO', id_tipo_atenciones); 

                    swal({
                      buttons: false,
                      title: "Eliminación Exitosa",
                      text: "Datos eliminados de forma correcta.",
                      icon: "success",
                      timer: 1000,
                    });
                  }
                }
              }) 
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


  function guardarMonitoreo()
  { 
      var id_atencion = $('#id_hospitalizacion').val();
      var frec_cardiaca = $('#frec_cardiaca').val();
      var time_flujo_correguido = $('#time_flujo_correguido').val();
      var volumen_latido = $('#volumen_latido').val();
      var indice_resistencia_vascular = $('#indice_resistencia_vascular').val();
      var resistencia_vascular = $('#resistencia_vascular').val();
      var presion_venosa_central = $('#presion_venosa_central').val();
      var ivrs = $('#ivrs').val();
      var paq_glob_actual = $('#paq_glob_actual').val();
      var paq_glob_acumulado = $('#paq_glob_acumulado').val();
      var crio_actual = $('#crio_actual').val();
      var crio_acumulado = $('#crio_acumulado').val();
      var plaqueta_actual = $('#plaqueta_actual').val();
      var plaqueta_acumulado = $('#plaqueta_acumulado').val();
      var plasma_actual = $('#plasma_actual').val();
      var plasma_acumulado = $('#plasma_acumulado').val();
      var pool_actual = $('#pool_actual').val();
      var pool_acumulado = $('#pool_acumulado').val();
      var val_tiss = $('#putaje_calculado_tiss').val();
      var descrip_tiss = $('#valoracion_calculado_tiss').val();
      var val_cpot = $('#putaje_calculado_cpot').val();
      var descrip_cpot = $('#valoracion_calculado_cpot').val();
      var val_norton = $('#putaje_calculado_norton').val();
      var descrip_norton = $('#valoracion_calculado_norton').val();
      var val_morse = $('#putaje_calculado_morse').val();
      var descrip_morse = $('#valoracion_calculado_morse').val();
      var color = $('#tipo_proceso').val(); 
      var tipo_registro = $('#tipo_rgs').val();
      var img_puntos_base = $('#img_puntos_base').val();
     
      console.log('2222222222222222222222222');
     
      if(id_atencion == ''){
        swal({
          buttons: false,
          title: "Operación Cancelada, Proceso no válido",
          text: "Seleccione un Proceso válido.",
          icon: "error",
          timer: 1500,
        }); 
        return;
      }

      registro_monitoreo(id_atencion,frec_cardiaca,time_flujo_correguido,volumen_latido,indice_resistencia_vascular,resistencia_vascular,presion_venosa_central,ivrs,paq_glob_actual,paq_glob_acumulado,crio_actual,crio_acumulado,plaqueta_actual,plaqueta_acumulado,plasma_actual,plasma_acumulado,pool_actual,pool_acumulado,val_tiss,descrip_tiss,val_cpot,descrip_cpot,val_norton,descrip_norton,val_morse,descrip_morse,color,tipo_registro,img_puntos_base);
      
  }


  function registro_monitoreo($id_atencion,$frec_cardiaca,$time_flujo_correguido,$volumen_latido,$indice_resistencia_vascular,$resistencia_vascular,$presion_venosa_central,$ivrs,$paq_glob_actual,$paq_glob_acumulado,$crio_actual,$crio_acumulado,$plaqueta_actual,$plaqueta_acumulado,$plasma_actual,$plasma_acumulado,$pool_actual,$pool_acumulado,$val_tiss,$descrip_tiss,$val_cpot,$descrip_cpot,$val_norton,$descrip_norton,$val_morse,$descrip_morse,$color,$tipo_registro,$img_puntos_base)
  { 
    var imgpuntosbase = $('#img_puntos_base').val();
    console.log('--------------------------');
    console.log(imgpuntosbase);
    console.log($img_puntos_base);
    console.log('--------------------------');
    $.ajax({
      data: { 
        id_atencion: $id_atencion,
        frec_cardiaca: $frec_cardiaca,
        time_flujo_correguido: $time_flujo_correguido,
        volumen_latido: $volumen_latido,
        indice_resistencia_vascular: $indice_resistencia_vascular,
        resistencia_vascular: $resistencia_vascular,
        presion_venosa_central: $presion_venosa_central,
        ivrs: $ivrs,
        paq_glob_actual: $paq_glob_actual,
        paq_glob_acumulado: $paq_glob_acumulado,
        crio_actual: $crio_actual,
        crio_acumulado: $crio_acumulado,
        plaqueta_actual: $plaqueta_actual,
        plaqueta_acumulado: $plaqueta_acumulado,
        plasma_actual: $plasma_actual,
        plasma_acumulado: $crio_acumulado,
        pool_actual: $pool_actual,
        pool_acumulado: $pool_acumulado,
        val_tiss: $val_tiss,
        descrip_tiss: $descrip_tiss,
        val_cpot: $val_cpot,
        descrip_cpot: $descrip_cpot,
        val_norton: $val_norton,
        descrip_norton: $descrip_norton,
        val_morse: $val_morse,
        descrip_morse: $descrip_morse,
        color: $color,
        tipo_registro: $tipo_registro,
        img_puntos_base: $img_puntos_base
      }, 
      url:'{{url('agregar_monitoreo')}}', 
      type:'POST', 
      dataType:'JSON',
      success: function(data){  
        console.log(data);
      } 
    }) 

    function mostrar_monitoreo_notaEnfermeria($id_atencion)
    {
      $.ajax({
                url:'{{url('mostrar_nota_enfermeria')}}/'+$id_atencion,
                type:'GET',
                dataType:'JSON',
                success: function(data){
                  console.log(data );
            $("#listadoDatosMonitoreo").empty();
           
              var valor = '' ;
              $.each(data, function (key, value) {
                  
                  valor = valor+
                  
                  
              });
              $("#listadoDatosMonitoreo").html(valor);
              
              
        
                }
              }) 
    }

  }

</script>