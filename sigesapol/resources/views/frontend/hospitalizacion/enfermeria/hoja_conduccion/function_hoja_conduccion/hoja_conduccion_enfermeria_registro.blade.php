<script>
    function lista_table_tr_campo_detalleHojaConduccion(
        $tabla_relacionada
    ) {

        var ind = $('#' + $tabla_relacionada + ' tbody tr').length;
        if (ind > 0) {
            $("#" + $tabla_relacionada).each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
            fila_medicamentos = $('#' + $tabla_relacionada + ' tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];
        }



        var newRow = '<tr style="height: 65px !important;">'

        
        newRow += '<td style="width: 13%;">';
        newRow += '<label>HORA:</label><input name="" id="' +
            '" type="time" class="form-control" style="height: 32px !important;width: 95%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 12%;">';
        newRow += '<label>P. ARTERIAL:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 10%;">';
        newRow += '<label>FREC. RESP.:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 9%;">';
        newRow += '<label>PULSO:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 9%;">';
        newRow += '<label>TEMP:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 22%;">';
        newRow += '<label>PALIDEZ DE PIEL Y MUCOSA:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 10%;">';
        newRow += '<label>CIANOSIS:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 15%;">';
        newRow += '<label>SANGRADO(VOL.):</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
            
           
        
        newRow += '<td> <button type="button" class="btn btn-danger'+
            ' btn-xs" style="margin-left:10px; margin-top:18px"><i class="fa fa-times"></i> </button></td>'
        newRow += '</tr>'
        

        $('#' + $tabla_relacionada + ' tbody').append(newRow);

        
       
    }
    function lista_table_tr_campo_detalleHojaConduccion_2(
        $tabla_relacionada
    ) {

        var ind = $('#' + $tabla_relacionada + ' tbody tr').length;
        if (ind > 0) {
            $("#" + $tabla_relacionada).each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
            fila_medicamentos = $('#' + $tabla_relacionada + ' tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];
        }



        var newRow = '<tr style="height: 65px !important;">'
        newRow += '<td style="width: 18%;">';
        newRow += '<label>FECHA:</label><input name="" id="' +
            '" type="date" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        
        newRow += '<td style="width: 12%;">';
        newRow += '<label>HORA:</label><input name="" id="' +
            '" type="time" class="form-control" style="height: 32px !important;width: 98%;" required>';
        newRow += '</td>'
        
        newRow += '<td style="width: 20%;">';
        newRow += '<label>COMPONENTE:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 20%;">';
        newRow += '<label>REACCIONES ADVERSAS:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 15%;">';
        newRow += '<label>LUGAR:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        newRow += '<td style="width: 25%;">';
        newRow += '<label>OBSERVACIONES:</label><input name="" id="' +
            '" type="text" class="form-control" style="height: 32px !important;width: 90%;" required>';
        newRow += '</td>'
        
        newRow += '<td> <button type="button" class="btn btn-danger'+
            ' btn-xs" style="margin-left:10px; margin-top:18px"><i class="fa fa-times"></i> </button></td>'
        newRow += '</tr>'
        

        $('#' + $tabla_relacionada + ' tbody').append(newRow);

        
       
    }
    
    function delete_table_tr_campo($tabla_relacionada) {
        $('#' + $tabla_relacionada + ' tbody').on('click', 'button', function() {
            var obj = $(this);
            obj.parent().parent().remove();

        });
    }


    $('#add_registro_hoja').on('click', function() {
        console.log("click add_registro_hoja");
        lista_table_tr_campo_detalleHojaConduccion(
            'tbl_registro_hoja'

        );

    });
    $('#add_transfusion_previa').on('click', function() {
        console.log("click add_transfusion_previa");
        lista_table_tr_campo_detalleHojaConduccion_2(
            'tbl_transfusion_previa'

        );

    });
    delete_table_tr_campo('tbl_registro_hoja');
    delete_table_tr_campo('tbl_transfusion_previa');
    $('.select2').select2();

    /*=============================================
    AJAX PARA crear Listar
    =============================================*/
    $('.registrarHojaConduccion').click(function() {
        
        var id_hospitalizacion = $(this).data('id');
        $('#FormularioRegistrarHojaConduccion').trigger("reset");
        $('#tituloFormularioRegistrarHojaConduccion').html("Registrar Hoja de Conducción");
        $('#modalRegistrarHojaConduccion').modal('show');

        $('#id_registrar_hoja_conduccion').val(id_hospitalizacion);
        $("#tbl_registro_hoja tbody").empty();
        $("#tbl_transfusion_previa tbody").empty();
    });
</script>