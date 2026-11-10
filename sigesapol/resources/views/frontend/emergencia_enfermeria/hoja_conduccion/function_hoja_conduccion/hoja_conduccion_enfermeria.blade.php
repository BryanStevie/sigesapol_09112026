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
    AJAX PARA crear Listar
    =============================================*/
    
  $('body').on('click', '#listarHojaConduccion', function () {   
        
        var id_hospitalizacion = $(this).data('id');
        $('#FormularioListarHojaConduccion').trigger("reset");
        $('#tituloFormularioListarHojaConduccion').html("Lista de Hojas de Conducción");
        $('#modalListarHojaConduccion').modal('show');

        $('#id_lista_hoja_conduccion').val(id_hospitalizacion);
    });
</script>