<style> 
  .leyenda_grafico {
      list-style-type: none;
      margin: 0;
      padding: 0;
  }

  .leyenda_grafico li {
      margin-bottom: 10px;
      padding-left: 30px;
      position: relative;
  }

  .leyenda_grafico li .icono {
      position: absolute;
      left: 0;
      top: 0;
      height: 20px;
      width: 20px;
  } 
</style>

<script>  
      document.getElementById('plano').addEventListener('click', function(event) {
          //alert("Le has dado click");
          var rect = document.getElementById('plano').getBoundingClientRect();
          var x = event.clientX - rect.left;
          var y = event.clientY - rect.top;
          $('#x').val(x)
          $('#y').val(y)
          var id_hospitalizacion=$('#id_hospitalizacion').val();
    
          guardarPunto();  
          pintar_puntos_by_hospitalizacion(id_hospitalizacion, "control_div_puntos_monitoreo", "control_div_puntos_monitoreo_leyenda", "MONITOREO",'REGISTRO'); 
      });
 
  
      $('#crearMonitoreo').click(function () {
      
		    var id_hospitalizacion_registro_monitoreo = $('#id_hospitalizacion_listado_monitoreo').val();
        $("#registrarMonitoreo").attr("id","registrarMonitoreo");
        $("#registrarMonitoreo").attr("disabled",false);
        $('#FormularioMonitoreo').trigger("reset");
        $('#tituloFormularioMonitoreo').html("Registro Nuevo Monitoreo");
        $('#modalMonitoreo').modal('show');
        $("#tipo_proceso").select2({ width: 'resolve'}); 
        $("#tamanio_proceso").select2({ width: 'resolve'}); 
        $('#id_hospitalizacion').val(id_hospitalizacion_registro_monitoreo);

        lista_parametros('TIPO_PROCESO_PUNTO','tipo_proceso','combo','TABLA_PARAMETROS',0);
        lista_parametros('TAMANIO_PUNTOS','tamanio_proceso','combo','TABLA_PARAMETROS',0); 

        pintar_puntos_by_hospitalizacion(id_hospitalizacion_registro_monitoreo, "control_div_puntos_monitoreo", "control_div_puntos_monitoreo_leyenda", "MONITOREO",'REGISTRO');
 
      });
      
      $( "#registrarMonitoreo" ).on( "click", function() {
        $(this).attr('disabled', true);
      });
      
      $(document).ready(function () {
        $('#registrarMonitoreo').click(function (e) {
          e.preventDefault(); 
      
          swal({
            buttons: true,
            title: "¿Desea Realizar el Registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete) { 
  
              $('#FormularioMonitoreo').trigger("reset");
              $('#modalMonitoreo').modal('hide');
  
              swal({
                buttons: false,
                title: "Registro Exitoso",
                text: "Datos registrados Exitosamente.",
                icon: "success",
                timer: 1500,
              });
  
            } else {
              
              $(this).attr('disabled', false);
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
       
      
  </script>