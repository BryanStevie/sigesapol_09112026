<script>
    $('#fecha_incidencia_inicial').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_incidencia_inicial');
    });

    $('#fecha_incidencia_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_incidencia_final');
    });

    /*=============================================
    AJAX PARA crear Listar Alergias
    =============================================*/
    $('.listarAlergias').click(function() {
        //var id_hospitalizacion = $(this).data('id');
        var id_asegurado = $('#id_asegurado').val();
        var id_servicio = $('#id_servicio').val();
        
        $('#FormularioListarAlergias').trigger("reset");
        $('#tituloFormularioListarAlergias').html("Listado de Alergias Médicas");
        $('#modalListarAlergias').modal('show');
        $('#id_hospitalizacion_Alergias').val(id_hospitalizacion);
       
        tipo_atenciones();
        cargarCabeceraModalA(id_asegurado,id_servicio);
        listar_medicamentos_alergias('','');
    });

    function cargarCabeceraModalA(id_asegurado,id_servicio) {  
      $.ajax({
        url: '{{ url('getAseguradoby_id/') }}/' + id_asegurado,
        type: 'GET',
        dataType: 'JSON',
        success: function(data) { 
           console.log(data);
          $('#numerodoc_al').html(data.nro_doc_ident);
          $('#nombres_al').html(data.paterno +" " +data.materno+" " +data.nombre);
          $('#tiposangre_al').html(data.tipo_sangre);
          $('#edad_al').html(data.edad_corta);
          $('#sexo_al').html(data.sexo);
          $('#hc_al').html(data.nro_historia);

          $('#id_asegurado_alergia').val(id_asegurado);
          $('#id_servicio_alergia').val(id_servicio);
        }
      });
    }

    function getListadoAlergias(){
      
        if(document.getElementById('tipoatenciones').value!=''){
            $("#crearAlergias").attr("disabled",false);
            $("#crearAlergias").attr("style","pointer-events: all");
        }else{
            $("#crearAlergias").attr("disabled",true);
            $("#crearAlergias").attr("style","pointer-events: none");
        }
        
        var tipoatenciones = document.getElementById('tipoatenciones').value;
        var estado = document.getElementById('estado').value;
       listar_medicamentos_alergias(tipoatenciones,estado);
    }

    function tipo_atenciones(){
        $("#tipoatenciones").empty();
        $("#tipoatenciones").append("<option value=''>--SELECCIONAR--</option>");
        var var_url='{{url('tipo_atenciones')}}';
        $.ajax({
          url:var_url,
          type:'get',
          dataType:'JSON',
          success: function(data){
            $.each(data, function (key, value) {
              $("#tipoatenciones").append("<option value='" + value.id + "'>" +value.nombre + "</option>");
            });
          }
        })
    };
    
</script>