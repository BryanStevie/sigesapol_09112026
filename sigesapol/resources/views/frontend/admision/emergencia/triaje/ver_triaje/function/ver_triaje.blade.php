<script> 

    function click_ver_triaje(id_triaje){
        $('#FormularioVerTriaje').trigger("reset"); 
        $('#modalVerTriaje').modal('show');  

        ///CARGAMOS LOS DATOS DEL INGRESO
        $.ajax({
          url:'{{url('ver_datos_triaje_modal/')}}/'+id_triaje,
          type:'GET',
          dataType:'JSON',
          success: function(data){  
            console.log(data.data);
            
            // Crear un objeto Date a partir del timestamp
            const fecha = new Date(data.data.fecha_registro_emergencia_triaje);

            // Obtener los componentes de la fecha
             dia = fecha.getDate();
             mes = fecha.getMonth() + 1; // Los meses en JavaScript son base 0, por lo tanto, se suma 1
             anio = fecha.getFullYear();
            if (dia<10) {
                dia='0'+dia;
            }
            if (mes<10) {
                mes='0'+mes;
            }
            
            $('#feha_triaje').html(dia+'/'+mes+'/'+anio); 
            $('#numero_registro').html(data.data.id_triaje); 
            $('#nombre_establecimiento').html(data.nombre_establecimiento); 


            $('#tipo_documento').html(data.data.tipo_documento); 
            $('#numero_documento').html(data.data.nro_dni); 
            $('#sexo_paciente').html(data.data.sexo); 
            $('#edad_paciente').html(data.data.edadpaciente); 
            $('#nombre_paciente').html(data.data.nombres_paciente + ' ' + data.data.apellido_pat_paciente + ' ' + data.data.apellido_mat_paciente); 
            $('#fecha_nacimiento').html(data.data.fecha_nac_paciente); 

            
            $('#temperatura_paciente').html(data.data.temperatura); 
            $('#talla_paciente').html(data.data.talla_cm); 
            $('#peso_paciente').html(data.data.peso_kg); 

            
            $('#forma_ingreso').html(data.data.descripcion_forma_ingreso); 
            $('#estado_ingreso').html(data.data.descripcion_estado_ingreso); 
            $('#motivo_ingreso').html(data.data.descripcion_motivo_ingreso); 

            
            $talla_cm = data.data.talla_cm;
            $peso_kg = data.data.peso_kg;
            $edadpaciente = data.data.edadpaciente;
            $sexo = data.data.sexo;

            if ($talla_cm != "") { 
                //PESO POR ALTURA
                $pesoIdeal=50+(($talla_cm-150)/4)*3+($edadpaciente-20)/4;
                if($sexo=="M") {
                    $pesoIdeal=$pesoIdeal*0.9;
                }
            }else{
                $pesoIdeal = "";
            }

            if ($peso_kg != "" && $talla_cm != "") { 
                $talla_en_metros =  $talla_cm*0.01;     
                $imc=   $peso_kg / ($talla_en_metros * $talla_en_metros).toFixed(2);
            } else {
                $imc = '';
            }

            if($imc!=''){
                $('#imc_paciente').html($imc.toFixed(2)); 
            }else{ 
                $('#imc_paciente').html($imc); 
            }
            if($pesoIdeal!=''){
                $('#imc_paciente').html($pesoIdeal.toFixed(2)); 
            }else{ 
                $('#imc_paciente').html($pesoIdeal); 
            }   
             
            $('#sa02_paciente').html(data.data.sa02_porcentaje); 
            $('#tam_cintura_paciente').html(data.data.tamano_cintura_cm); 
            $('#fre_car_fetal').html(data.data.frecuencia_cardiaca_fetal); 
            $('#pre_art_paciente').html(data.data.presion_arterial_x + '/' + data.data.presion_arterial_y ); 
            $('#fre_resp_paciente').html(data.data.frecuencia_respiratoria); 
            $('#fre_car_paciente').html(data.data.frecuencia_cardiaca); 
            $('#fio2_paciente').html(data.data.fi02); 
             

            $('#prioridad_paciente').html(data.data.prioridad_nombre);  
            $('#topico_paciente').html(data.data.topico_nombre);  
            $('#motivo_paciente').html(data.data.observacion_triaje); 
          }
        })

    } 
</script>