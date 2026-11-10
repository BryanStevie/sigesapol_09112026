 <script> 

    function click_alerta_atenciones(dni_paciente, nombre_de_paciente){

        ///CARGAMOS LOS DATOS DEL INGRESO
        $.ajax({
            url:'{{url('ver_atenciones_asegurado/')}}/'+dni_paciente,
            type:'GET',
            dataType:'JSON',
            success: function(data){   
                if(data!=null){ 
 
                    if(data.alta_pendiente[0].count!=null){
                        if(data.alta_pendiente[0].count!=0){
                            Swal.fire({
                                title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                                html: '<p class="descripcion-alert"><span>SE HA DETECTADO QUE EL PACIENTE CONSULTADO<br>PRESENTA ALTA(S) PENDIENTE(S).<br><br> <b style="color:red;">RECUERDE REGULARIZAR SU ESTADO.</b></span></p>',
                                allowOutsideClick: false,
                                confirmButtonColor: '#3c763d',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if($('#descripcion_tipo_beneficiario_saludpol').val() == 'CIVIL' || $('#tipo_beneficiario_saludpol').val() == '13'){
                                        Swal.fire({
                                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                                        html: '<p class="descripcion-alert"> <b>El paciente no está asegurado, se admisionará como CIVIL.</b> </span></p>',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false,
                                        });
                                    }
                                }
                            }); 
                        } 
                    }

                    if(data.lista_de_alertas.length!=0){
                        $('#FormularioAlertaAtenciones').trigger("reset"); 
                        $('#modalAlertaAtenciones').modal('show');  

                        $("#listaAlertaAtenciones").empty();
                        var valor = '' ;
                        var cnt_reg=0;
                        $.each(data.lista_de_alertas, function (key, value) {
                            cnt_reg++;
                            valor = valor+
                            '<tr>'+
                            '<td><b style="text-transform: uppercase;">' + value.establecimiento_descripcion + '</b></td>'+ 
                            '<td><b style="text-transform: uppercase;">' + value.prioridad_descripcion + '</b></td>'+ 
                            '<td><b style="text-transform: uppercase;">' + value.servicio_descripcion + '</b></td>'+ 
                            '<td><b style="text-transform: uppercase;">' + value.fecha_registro_ingreso + ' - '+value.hora_registro_ingreso+'</b></td>';

                            if(value.flag_egreso != 1){
                                valor = valor+'<td><b style="text-transform: uppercase;">-</b></td>'; 
                            }else{
                                valor = valor+'<td><b style="text-transform: uppercase;">' + value.fecha_egreso_medico + ' - '+value.hora_registro_ingreso+'</b></td>'; 
                            }

                            if(value.dias_transcurridos ==null){
                                valor = valor+'<td><b style="text-transform: uppercase;color:red;">PENDIENTE DE ALTA</b></td>'+  
                                '</tr>';
                            }else{
                                valor = valor+'<td><b style="text-transform: uppercase;">' + value.dias_transcurridos + ' día(s)</b></td>'+  
                                '</tr>';
                            }
                        });
                        $("#nombre_de_paciente").html(nombre_de_paciente);
                        $("#cantidad_de_registros").html(cnt_reg);
                        $("#listaAlertaAtenciones").html(valor);
                        
                    }else{ 
                                    if($('#descripcion_tipo_beneficiario_saludpol').val() == 'CIVIL' || $('#tipo_beneficiario_saludpol').val() == '13'){
                                        Swal.fire({
                                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                                        html: '<p class="descripcion-alert"> <b>El paciente no está asegurado, se admisionará como CIVIL.</b> </span></p>',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false,
                                        });
                                    }
                    }

                } 
            }
        })

    } 

    $('#modalAlertaAtenciones').on('hidden.bs.modal', function (e) {
        // Código a ejecutar cuando se cierra el modal
        // alert('El modal se ha cerrado');
        Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
            html: '<p class="descripcion-alert"><span>Recuerde que el paciente <b style="color:red;">TIENE AÚN UN ALTA PENDIENTE.</b><br>¿Seleccione una opción para Continuar con el registro de Triaje?</span></p>',
            allowOutsideClick: false, 
            showCancelButton: true,
            confirmButtonText: '<b>CONTINUAR EL REGISTRO</b>',
            confirmButtonColor: '#3085d6',
            cancelButtonText: '<b>CANCELAR EL REGISTRO</b>',
            cancelButtonColor: 'red',
        }).then((result) => {
            if (result.isConfirmed) {
                // Código a ejecutar si se hace clic en "OK"
                // console.log('Se hizo clic en OK');
                $('#dni_beneficiario').focus(); 
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Código a ejecutar si se hace clic en "Cancelar" o se cierra el cuadro de diálogo
                // console.log('Se hizo clic en Cancelar o se cerró el cuadro de diálogo'); 

                limpiar_paciente();
                limpiar_signos_vitales();
                limpiar_datos_adicional();
                $('#dni_beneficiario').val(''); 
                $('#dni_beneficiario').focus(); 

            }
        });
    });
</script>