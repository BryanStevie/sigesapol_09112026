<script>

    $('.generarHorario_x_Medicamento').click(function () {
    // Define la hora inicial como las 00:00 horas de hoy
  const horaInicial = new Date();
  horaInicial.setHours(16);

  // Crea un array vacío para almacenar las horas
  const horas = [];

  // Genera 10 horas con un intervalo de 30 minutos cada una
  for (let i = 0; i < 8; i++) {
    const hora = new Date(horaInicial.getTime() + 180*60000*i);
    const horaFormateada = hora.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    horas.push(horaFormateada);
  }

  console.log(horas);

        swal({
          buttons: true,
          title: "¿Desea Realizar el Registro?",
          text: "Recuerde verificar que todos los datos sean correctos.",
          icon: "warning",
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) { 
 
			      $('#modalHorarioKardex').modal('hide');

            swal({
              buttons: false,
              title: "Registro Exitoso",
              text: "Datos registrados Exitosamente.",
              icon: "success",
              timer: 1500,
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
    
</script>