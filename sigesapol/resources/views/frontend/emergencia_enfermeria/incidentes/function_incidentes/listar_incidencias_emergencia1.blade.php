<script> 
    $('#fecha_incidencia_inicial').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      var f_inicial = $('#fecha_incidencia_inicial').val();
      var f_final = $('#fecha_incidencia_final').val();
      var tipo = $('#tipo_atenciones').val();
      
      listar_incidencias_eventos_a(tipo,f_inicial,f_final);
    });
   
    $('#fecha_incidencia_final').datepicker({
      useCurrent: false,
      format: 'dd/mm/yyyy', 
      todayHighlight: true,
      autoclose: true,  
      orientation: "bottom left" 
    }).on('changeDate',function(e){ 
      
      var f_inicial = $('#fecha_incidencia_inicial').val();
      var f_final = $('#fecha_incidencia_final').val();
      var tipo = $('#tipo_atencione_incs').val();
      
      listar_incidencias_eventos_a(tipo,f_inicial,f_final);
    });
    
    $('body').on('click', '#listarIncidentes', function () { 
      $("#tipo_atenciones").empty();
      $("#tipo_atenciones").append("<option value=''>--SELECCIONAR--</option>"); 
      var id_emergencia = $(this).data('id');
      var id_asegurado = $(this).data('id_asegurado'); 
      var id_servicio = $(this).data('id_servicio'); 
      var nombre_servicio = $(this).data('nombre_servicio'); 
      var id_nota_enfermeria = $(this).data('id_nota_enfermeria'); 
      

      $('#FormularioListarIncidentes').trigger("reset");
      $('#tituloFormularioListarIncidentes').html("Listado de Incidentes y Eventos Adversos");
      $('#modalListarIncidentes').modal('show');
      
      $('#id_hospitalizacion_incidencia').val(id_emergencia); 
      $('#id_asegurado').val(id_asegurado); 
      $('#id_servicio').val(id_servicio); 
      $('#nombre_servicio').val(nombre_servicio);
      $('#id_nota_enfermeria').val(id_nota_enfermeria);
      cargarCabeceraModal(id_emergencia,id_asegurado);
      listar_tipo_atenciones();

      var f_inicial = $('#fecha_incidencia_inicial').val();
      var f_final = $('#fecha_incidencia_final').val();
      var tipo = $('#tipo_atenciones').val();
      listar_incidencias_eventos_a(tipo,f_inicial,f_final);
    }); 


    function cargarCabeceraModal(id_emergencia,id_asegurado) {  
      $.ajax({
        url: '{{ url('getAseguradoby_id/') }}/' + id_asegurado,
        type: 'GET',
        dataType: 'JSON',
        success: function(data) { 
          $('#numero_doc_inc').html(data.nro_doc_ident);
          $('#nombres_inc').html(data.paterno +" " +data.materno+" " +data.nombre);
          $('#tipo_sangre_inc').html(data.tipo_sangre);
          $('#edad_inc').html(data.edad_corta);
          $('#sexo_inc').html(data.sexo);
          $('#hc_inc').html(data.nro_historia);
   
          
          $('#numero_doc2').val(data.nro_doc_ident);
          $('#nombres2').val(data.paterno +" " +data.materno+" " +data.nombre);
          $('#tipo_sangre2').val(data.tipo_sangre);
          $('#edad2').val(data.edad_corta);
          $('#sexo2').val(data.sexo);
          $('#hc2').val(data.nro_historia);

          getDiagnosticos(id_emergencia,data.nro_doc_ident);
        }
      });
    }

    function getListadoIncidencias() {
    var tipo = document.getElementById('tipo_atenciones').value;

    if (tipo == '2') {
        $("#crearIncidencias").attr("disabled", false);
        $("#crearIncidencias").attr("style", "pointer-events: all");
    } else {
        $("#crearIncidencias").attr("disabled", true);
        $("#crearIncidencias").attr("style", "pointer-events: none");
    }

    console.log("Valor de tipo_atenciones:", tipo);

    var fecha_inicio = document.getElementById('fecha_incidencia_inicial').value;
    var fecha_final = document.getElementById('fecha_incidencia_final').value;
    lista_incidencias_eventos_a_emergencias(tipo, fecha_inicio, fecha_final);
}

// Llamar a getListadoIncidencias() inmediatamente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    getListadoIncidencias();
});

// vincular getListadoIncidencias() al evento onchange del <select> para evitar el select
document.getElementById('tipo_atenciones').addEventListener('change', function() {
    getListadoIncidencias();
});




    function listar_tipo_atenciones(){
        var var_url='{{url('tipo_atenciones')}}';
        $.ajax({
          url:var_url,
          type:'get',
          dataType:'JSON',
          success: function(data){
            $.each(data, function (key, value) {
              $("#tipo_atenciones").append("<option value='" + value.id + "'>" +value.nombre + "</option>");
            });
          }
        })
    };

    function getDiagnosticos(id_h,dni_p){
        var var_url='{{url('incidente_diagnosticos_emergencia')}}';
        $("#diagnosticos").empty();
        $.ajax({
          url:var_url,
          type:'POST',
          data: {
              id_emergencia: id_h,
              dni_paciente: dni_p
          }, 
          dataType:'JSON',
          success: function(data){
            $.each(data, function (key, value) {
              console.log(value.diagnosticos);
              $("#diagnosticos").append("<tr><td>"+(key+1)+". "+value.diagnosticos+"</td></tr>");
            });
          }
        })
      }
    
    </script>
    
    