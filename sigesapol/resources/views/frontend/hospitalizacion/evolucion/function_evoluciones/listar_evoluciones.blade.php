<script> 

  $('#fecha_evolucion_bus_inicial_listado').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom left" 
  }).on('changeDate',function(e){  
    const $p = [
      $('#fecha_evolucion_bus_inicial_listado').val(),
      $('#fecha_evolucion_bus_final_listado').val(),
      $('#descripcion_turno_bus_listado').val(),
    ];
    listar_evoluciones($p);
  });

$('#fecha_evolucion_bus_final_listado').datepicker({
  useCurrent: false,
  format: 'dd/mm/yyyy', 
  todayHighlight: true,
  autoclose: true,  
  orientation: "bottom left" 
}).on('changeDate',function(e){  
    const $p = [
      $('#fecha_evolucion_bus_inicial_listado').val(),
      $('#fecha_evolucion_bus_final_listado').val(),
      $('#descripcion_turno_bus_listado').val(),
    ];
    listar_evoluciones($p);
});

$('#descripcion_turno_bus_listado').change(function() { 
    const $p = [
      $('#fecha_evolucion_bus_inicial_listado').val(),
      $('#fecha_evolucion_bus_final_listado').val(),
      $('#descripcion_turno_bus_listado').val(),
    ];
    listar_evoluciones($p);
});
  /*=============================================
  AJAX PARA crear ListarEvolucionesS
  =============================================*/ 
  $('body').on('click', '#listarEvoluciones', function () {  
    var id_atencion = $(this).data('id_atencion_listado');  
    var id_tipo_atenciones = $(this).data('id_tipo_atenciones_listado');  
    var id_nota_enfermeria_listado = $(this).data('id_nota_enfermeria_listado');  
    var id_asegurado = $(this).data('id_asegurado');  

    $('#FormularioListarEvoluciones').trigger("reset");
    $('#tituloFormularioListarEvoluciones').html("Evoluciones Realizadas");
    $('#modalListarEvoluciones').modal('show');    
    $('#id_atencion_evolucion_listado').val(id_atencion);  
    $('#id_tipo_atenciones_evolucion_listado').val(id_tipo_atenciones);  
    $('#id_nota_enfermeria_evolucion_listado').val(id_nota_enfermeria_listado);  
    
    cargarCabeceraModal(id_asegurado);

    ///LISTADO DE REGISTROS  
    const $p = [
      $('#fecha_evolucion_bus_inicial_listado').val(), 
      $('#fecha_evolucion_bus_final_listado').val(),
      $('#descripcion_turno_bus_listado').val(),
      $(this).data('id_nota_enfermeria_listado'),
    ];
    listar_evoluciones($p);
 
  }); 

  function cargarCabeceraModal(id_asegurado) {  
    
    $.ajax({
      url: '{{ url('getAseguradoby_id/') }}/' + id_asegurado,
      type: 'GET',
      dataType: 'JSON',
      success: function(data) {  
        $('#num_documento_evolucion').html(data.nro_doc_ident);
        $('#nom_paciente_evolucion').html(data.paterno +" " +data.materno+" " +data.nombre);
        $('#tipo_sangre_evolucion').html(data.tipo_sangre);
        $('#edad_evolucion').html(data.edad_corta);
        $('#sexo_evolucion').html(data.sexo);
        $('#hc_evolucion').html(data.nro_historia);
      }
    }) 
  }
 
  function listar_evoluciones($p){
        var var_url='{{url('lista_nota_ingreso_evoluciones')}}';
        $.ajax({
          url:var_url,
          type:'POST',
          dataType:'JSON',
          data: {
              fecha_evolucion_bus_inicial_listado: $p[0],
              fecha_evolucion_bus_final_listado: $p[1],
              descripcion_turno_bus_listado: $p[2]
          }, 
          beforeSend: function() {
            swal({
              buttons: false,
              title: "Realizando Consulta ...",
              text: "Espere un momento mientras se genera la solicitud.",
              icon: "warning",
            });
          }, 
          success: function(data){
            console.log(data );
            $("#listaDatosEvoluciones").empty();
            if(data!=null){
              var valor = '' ;
              $.each(data, function (key, value) {
                  var descripcion_turno='';
                  if(value.descripcion_turno == 'M'){
                    descripcion_turno='MAÑANA';
                  }else if(value.descripcion_turno == 'T'){
                    descripcion_turno='TARDE';
                  }else if(value.descripcion_turno == 'N'){
                    descripcion_turno='NOCHE';
                  }
                  valor = valor+
                  '<tr >'+
                  ' <td style="vertical-align: middle;"><b>FECHA:</b> '+value.fecha_evol+'<br><b>HORA:</b> '+value.hora_evol+'<br><b>TURNO:</b> '+descripcion_turno+'</td>'+
                  ' <td style="vertical-align: middle;">NESTOR ALBERTO BUSTAMANTE QUISPE</td>'+
                  ' <td style="vertical-align: middle;text-transform: uppercase;"><b style="font-size: 12px !important">SUBJETIVO:</b> '+value.descripcion_objetivo+'<br><b style="font-size: 12px !important">OBJETIVO:</b> '+value.descripcion_subjetivo+''+
                  ' </td>'+ 
                  ' <td style="vertical-align: middle;">'+
                  '   <a href="javascript:void(0)" aria-hidden="true" data-id_evoluciones="'+value.id_evoluciones+'" id="verEvoluciones" name="verEvoluciones" '+ 
                  '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs verEvoluciones"> <i class="fa fa-eye"></i>'+ 
                  '   </a>'+ 
                  /*
                  '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Editar"'+
                  '     class="btn btn-warning btn-xs editarEvaluacion"> <i class="fa fa-pencil-square-o"></i>'+
                  '   </a>'+
                  */
                  '   <a href="/pdf_nota_ingreso_evoluciones/'+value.id+'" target="_blank" aria-hidden="true" data-toggle="tooltip" title="PDF"'+
                  '     class="btn btn-danger btn-xs"> <i class="fa fa-file-pdf-o"></i>'+
                  '   </a>'+
                  ' </td>'+
                  '</tr>';
              });
              $("#listaDatosEvoluciones").html(valor);
              
              swal({
                buttons: false,
                title: "Registros encontrados",
                text: "List de registros cargados de forma correcta.",
                icon: "warning",
                timer: 1500,
              }); 
              
            }else{
              
              swal({
                buttons: false,
                title: "Sin Historial",
                text: "No se han detectado registros durante la búsqueda realizada.",
                icon: "warning",
                timer: 1500,
              }); 

            }
          }
        })
  };


  
  </script>
  
  