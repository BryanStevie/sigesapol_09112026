<script>
  
    function listar_atenciones_by_asegurado(dni_beneficiario){  
        var var_url="{{url('asegurados_atenciones_by_post')}}";
          $.ajax({
            url: var_url,
            type: 'POST',  // Cambio de GET a POST
            dataType: 'json',
            data: {
                dni_beneficiario:dni_beneficiario 
            }, 
            beforeSend: function() { 
              Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                html: '<p class="descripcion-alert">Espere un momento mientras se genera la consulta de datos.</p><br>',
                confirmButtonColor: '#3085d6', 
                allowOutsideClick: false,
                showConfirmButton: false  
              });
            },
            success: function(data){ 
                console.log(data.length);
                if(data==null || data.length ==0){
                    Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Búsqueda</p></div>',
                        html: '<p class="descripcion-alert"><span style="color:red;">NO CUENTA CON ATENCIONES REALIZADAS.<br><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                }else{
                    
                    $("#listaAtenciones_by_asegurado").empty();
                    var valor = '' ; 
                      $.each(data, function (key, value) {
                          valor = valor+
                          '<tr style="cursor:pointer">';
                            
                          if(value.fecha_registro_ingreso==null || value.fecha_registro_ingreso==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{
                            valor = valor+' <td style="vertical-align: middle;">' + value.fecha_registro_ingreso + '</td>'; 
                          } 

                          var secuencia = value.secuencia.toString();
                          var cerosCompletos = "0".repeat(8 - secuencia.length) + secuencia;
                          valor = valor+' <td style="vertical-align: middle;">' + cerosCompletos  + '</td>';
                        
                          if(value.triaje==null || value.triaje==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{
                            valor = valor+' <td style="vertical-align: middle;">' + value.triaje + '</td>';
                          }

                          if(value.id_tipo_egres_med == null || value.id_tipo_egres_med==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{
                            if(value.id_tipo_egres_med == 1){
                                valor = valor+' <td style="vertical-align: middle;">ALTA</td>';
                            }else if(value.id_tipo_egres_med == 2){
                                valor = valor+' <td style="vertical-align: middle;">HOSPITALIZACIÓN</td>';
                            }else if(value.id_tipo_egres_med == 3){
                                valor = valor+' <td style="vertical-align: middle;">TRANSFERIDO</td>';
                            }else if(value.id_tipo_egres_med == 4){
                                valor = valor+' <td style="vertical-align: middle;">FALLECIDO</td>';
                            } else if(value.id_tipo_egres_med == 6){
                                valor = valor+' <td style="vertical-align: middle;">FUGA</td>';
                            } else if(value.id_tipo_egres_med == 7){
                                valor = valor+' <td style="vertical-align: middle;">RETIRO VOLUNTARIO</td>';
                            } 
                          }
                          
                          if(value.fecha_alta_medica==null || value.fecha_alta_medica==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{
                            valor = valor+' <td style="vertical-align: middle;">' + value.fecha_alta_medica + '</td>'; 
                          }

                          if(value.oficial_guardia==null || value.oficial_guardia==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{ 
                            valor = valor+' <td style="vertical-align: middle;">' + value.oficial_guardia + '</td>';
                          }

                          if(value.numero_boleta==null || value.numero_boleta==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{ 
                            valor = valor+' <td style="vertical-align: middle;">' + value.numero_boleta + '</td>';
                          }


                          if(value.observacion_admision==null || value.observacion_admision==''){
                            valor = valor+' <td style="vertical-align: middle;">-</td>';
                          }else{ 
                            valor = valor+' <td style="vertical-align: middle;text-transform:uppercase;">' + value.observacion_admision + '</td>';
                          }

                          valor = valor+'</tr>';
                      }); 
                  
                      $("#listaAtenciones_by_asegurado").html(valor);
                    Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Búsqueda</p></div>',
                        html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                }
            }
          })
      }; 
      
  </script>