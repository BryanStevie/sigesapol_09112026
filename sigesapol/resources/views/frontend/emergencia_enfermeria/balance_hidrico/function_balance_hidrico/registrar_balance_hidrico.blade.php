<script> 

  $(document).on('click', '[id^=add_tabla_]', function() {
    var idString = $(this).attr('id').replace('add_tabla_', '');
    var tabla_tipo = $(this).data('tabla_tipo');
    var secuencia = $(this).data('secuencia');
    var id = parseInt(idString, 10);
    var classList = $(this).attr('class').split(' ');
    // Obtener la última clase del array
    var tipo_balance = classList[classList.length - 1];
  
    let nombre_campo='';
    let descripcion_caracter_fisico='';
    let descripcion_caracter_fisico_id='';
    let valor_tabla='';
    let id_caracter_fisico='';
    let tipo_tabla=0;

    if (tipo_balance === 'ingreso') {
      tipo_balance='ingreso';
      nombre_campo='valor_ingreso';
      descripcion_caracter_fisico='descripcion_caracter_fisico_ingreso';
      descripcion_caracter_fisico_id='descripcion_caracter_fisico_id_ingreso';
      valor_tabla='valor_tabla_ingreso';
      id_caracter_fisico='id_caracter_fisico_ingreso';
    }else if (tipo_balance === 'egreso') {
      tipo_balance='egreso';
      nombre_campo='valor_egreso';
      descripcion_caracter_fisico='descripcion_caracter_fisico_egreso';
      descripcion_caracter_fisico_id='descripcion_caracter_fisico_id_egreso';
      valor_tabla='valor_tabla_egreso';
      id_caracter_fisico='id_caracter_fisico_egreso';
    }
    if(tabla_tipo){
      tipo_tabla = tabla_tipo;
    }
    
    lista_table_balanceH(
    'tbl_tabla_'+id, 
    descripcion_caracter_fisico,
    descripcion_caracter_fisico_id,  
    nombre_campo, 
    'id_relacionado_tabla_'+id, 
    'boton_eliminar_item_tabla_'+id,
    tipo_balance,
    valor_tabla,
    id_caracter_fisico,
    id,
    tipo_tabla,
    secuencia
    );
    delete_table_tr_campo_balance('tbl_tabla_'+id,'boton_eliminar_item_tabla_'+id,'');
  });
    
  $('#crearBalanceHidrico').click(function () {
    $('#id').val('');
    $("#registrarBalanceHidrico").attr("id","registrarBalanceHidrico");
    $("#registrarBalanceHidrico").attr("disabled",false);
    $('#FormularioBalanceHidrico').trigger("reset");
    $('#tituloFormularioBalanceHidrico').html("Registro de Balance Hídrico");
    $('#modalBalanceHidrico').modal('show'); 

    // $("#item_dieta_enteral").select2({ width: 'resolve'}); 
    // lista_parametros('DIETA_ENTERAL','item_dieta_enteral','combo','TABLA_PARAMETROS',0);

    
    $("table[id^='tbl_tabla_'] tbody").empty();
    
    var id_asegurado = $('#id_asegurado_bh').val();
    var id_area = $('#id_area').val();//para validar si es UVI=1 o SHOCK TRAUMA=12 PARA QUE LE TRAIGA EN TURNOS MADRUGADA
    
    var peso = $('#peso').val();
    $('#peso_kg').html(peso);
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });
    cargarDatosGlobales2(id_asegurado,id_area);
    Swal.fire({
        icon: "success",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
        confirmButtonText: "ENTENDIDO",
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: "ENTENDIDO",
        timer: 2000,
        timerProgressBar: true,
    });
    
  });
  
  $(document).ready(function () {
    $('#registrarBalanceHidricoNuevo').click(function (e) {
        let valid = true;
        // Validación de campos de ingreso y egreso
        $('#modalBalanceHidrico').find('input[name="valor_ingreso[]"], input[name="valor_egreso[]"]').each(function() {
            if ($(this).val() < 0 || $(this).val() === '') {
                valid = false;
                $(this).addClass('is-invalid'); // Agrega la clase para indicar un error
                Swal.fire({
                    title: "Información de Servicio de Registros",
                    text: "Todos los campos de cantidad de ingresos y egresos deben ser 0 ó mayores",
                    icon: "info",
                    timer: 4000,
                    showConfirmButton: true
                });
                return false; // salir del bucle each
            }else{
              $(this).removeClass('is-invalid'); 
            }
        });

        // Validación del campo hora_registro
        if ($('#hora_registro').val() <= 0 || $('#hora_registro').val() === '') {
            valid = false;
            Swal.fire({
                title: "Información de Servicio de Registros",
                text: "El campo de hora debe ser mayor a 0",
                icon: "info",
                timer: 4000,
                showConfirmButton: true
            });
        }

        if (valid) {
            e.preventDefault(); // prevenir el envío del formulario

            var token = $('#_token').val();
            var route = '{{url('registrar_balance_hidrico')}}';
            
            Swal.fire({
                title: "¿Desea Realizar el Registro?",
                text: "Recuerde verificar que todos los datos sean correctos.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, registrar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData($("#FormularioBalanceHidrico")[0]);
                    $.ajax({
                        data: formData,
                        url: route,
                        contentType: false,
                        processData: false,
                        headers: {'X-CSRF-TOKEN': token},
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        dataType: 'JSON',
                        success: function(data) {
                            if (data != null) {
                                if (data.existe_balance) {
                                    Swal.fire({
                                        title: "Información de Servicio de Registros",
                                        text: "Ya existe un registro con el mismo turno",
                                        icon: "info",
                                        timer: 3000,
                                        showConfirmButton: true
                                    });
                                } else {
                                    if (data != 0) {
                                        $('#FormularioBalanceHidrico').trigger("reset");
                                        $('#modalBalanceHidrico').modal('hide');

                                        var codigo_fecha_24 = data.codigo_fecha_24; 
                                        var id_balance = data.id; 
                                        var flag_firma = data.flag_firma; 
                                        $('#flag_firma').val(flag_firma);
                                        if (flag_firma<=0 || flag_firma ===null) {
                                          $("#btnFirmaBalance").show();
                                          $("#btnFinalizarbalance").show();
                                          $("#icon-doc-firmado").hide();
                                          console.log('icon-doc-firmado   hidden');
                                        } else {
                                          $("#btnFirmaBalance").hide();
                                          $("#btnFinalizarBalance").hide();
                                          $("#icon-doc-firmado").show();
                                        }
                                        $('#FormularioListarHojaBalanceHidrico').trigger("reset");
                                        $('#tituloFormularioListarHojaBalanceHidrico').html("Hoja de Balance Hídrico de Enfermería");
                                        /***********ABRIR EL MODAL DE HOJA D EBALANCE***************/
                                        /* $('#modalBalanceHidrico').on('hidden.bs.modal', function() {
                                          $('#modalListarHojaBalanceHidrico').modal('show'); 
                                        }); */
                                         /***********FIN ABRIR EL MODAL DE HOJA D EBALANCE***************/
                                        $('#id_balance').val(id_balance); 
                                        ver_hoja_balance(codigo_fecha_24,id_balance);

                                        Swal.fire({
                                            title: "Registro Exitoso",
                                            text: "Datos registrados Exitosamente.",
                                            icon: "success",
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Error de Servicio de Registros",
                                            text: "Verifique que todos los datos ingresados sean los correctos, en caso que el problema persista, comuníquese con el área de informática.",
                                            icon: "error",
                                            timer: 2500,
                                            showConfirmButton: false
                                        });
                                    }
                                }
                            } else {
                                Swal.fire({
                                    title: "Error de Servicio de Registros",
                                    text: "Verifique que todos los datos ingresados sean los correctos, en caso que el problema persista, comuníquese con el área de informática.",
                                    icon: "error",
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Operación Cancelada",
                        text: "Proceso de Registro Cancelado.",
                        icon: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        } else {
            e.preventDefault(); // prevenir el envío del formulario si no es válido
        }
    });
  });
  
  function cargarDatosGlobales2(id_asegurado,id_area){
    var var_url='{{url('datos_globales_balance')}}';
    $.ajax({
      url:var_url,
      type:'POST',
      dataType:'JSON',
      data: {
        id_asegurado: id_asegurado,
        id_area: id_area, 
      },  
      success: function(data){
        /***********DATOS DEL PACIENTE***********/
        $('#num_documento_bh_nuevo').html(data.datos_paciente.nro_doc_ident);
        $('#nom_paciente_bh_nuevo').html(data.datos_paciente.paterno +" " +data.datos_paciente.materno+" " +data.datos_paciente.nombre);
        $('#tipo_sangre_bh_nuevo').html(data.datos_paciente.tipo_sangre);
        $('#edad_bh_nuevo').html(data.datos_paciente.edad_corta);
        $('#sexo_bh_nuevo').html(data.datos_paciente.sexo);
        $('#hc_bh_nuevo').html(data.datos_paciente.nro_historia);
        $('#parentesgo_bh_nuevo').html(data.datos_paciente.parentesgo);
        $('#tipo_sangre_bh_nuevo').html(data.datos_paciente.tipo_sangre ?? 'Sin data');
        $('#alergia_bh_nuevo').html(
            data.datos_paciente.tiene_alergias
                ? `<i style="color: blue;">SI</i>`
                : `<i style="color: red;">NO</i>`
        );
        
        /*****************TURNOS**********/;
        $("#turnos_ver").empty(); // Vaciar antes de añadir nuevos turnos
        $.each(data.turnos, function (key, value) { 
          $("#turnos_ver").append("<option value='" + value.id + "'>" +value.descripcion + "</option>");
        }); 
        
        /***********CARACTER FISICO*********/
        let html_caracter_fisico = '' ;
        $("#balance_hidrico").empty();
        html_caracter_fisico +='<div class="col-md-6">';
        html_caracter_fisico +='<div class="table-responsive">';
        html_caracter_fisico +='<div class="col-md-12" style="text-align: center;">';
        html_caracter_fisico +='<b>INGRESOS</b>';
        html_caracter_fisico +='</div>';
        html_caracter_fisico +='<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
        html_caracter_fisico +=' <tbody id="balance_ingreso">';
        $.each(data.caracter_fisico, function (key, value) {
          if(value.tipo_balance==1){//INGRESO
              
            if ([1, 2, 3].includes(value.secuencia)) {
              if ([1].includes(value.secuencia)) {
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2">';
                html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
                html_caracter_fisico +='<input type="hidden"  name="descripcion_ingreso[]" value="'+value.descripcion+'">';
                html_caracter_fisico +='<input type="hidden"  name="es_visible[]" value="0">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">'+value.descripcion;
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='<td colspan="2"><input type="hidden" name="valor_tabla_ingreso[]">';
                html_caracter_fisico +='<input type="number" step="1"   class="form-control" name="valor_ingreso[]" id="hora_registro">';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
              }
              if ([2].includes(value.secuencia)) {
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2">';
                html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
                html_caracter_fisico +='<input type="hidden"  name="descripcion_ingreso[]" value="'+value.descripcion+'">';
                html_caracter_fisico +='<input type="hidden"  name="es_visible[]" value="1">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">'+value.descripcion;
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='<td colspan="2"><input type="hidden" name="valor_tabla_ingreso[]">';
                html_caracter_fisico +='<input type="number" step="1"   class="form-control ingreso"  name="valor_ingreso[]" id="INGRESO_AO" disabled>';
                html_caracter_fisico +='<input type="hidden" name="valor_ingreso[]" id="INGRESO_AO2">';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
              }
              if ([3].includes(value.secuencia)) {
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2">';
                html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
                html_caracter_fisico +='<input type="hidden"  name="descripcion_ingreso[]" value="'+value.descripcion+'">';
                html_caracter_fisico +='<input type="hidden"  name="es_visible[]" value="1">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">'+value.descripcion;
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='<td colspan="2"><input type="hidden" name="valor_tabla_ingreso[]">';
                html_caracter_fisico +='<input type="number" step="1"   class="form-control egreso"  name="valor_ingreso[]" id="INGRESO_PI" disabled>';
                html_caracter_fisico +='<input type="hidden" name="valor_ingreso[]" id="INGRESO_PI2">';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
              }                                                         
            }else{
              if(value.multiple==1 && value.tabla==null){
                
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="4">';
                html_caracter_fisico +='<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                html_caracter_fisico +='<tbody><tr><td>';
                html_caracter_fisico +='<a href="javascript:void(0)" data-secuencia="'+value.secuencia+'" id="add_tabla_'+value.id+'" name="add_tabla_'+value.id+'" class="btn btn-success btn-sm add_tabla_'+value.id+' ingreso">';
                html_caracter_fisico +='<i class="fa fa-plus" ></i>'+value.descripcion;
                html_caracter_fisico +='</a>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='<table id="tbl_tabla_'+value.id+'" style="margin-bottom: 10px !important; margin-left: 8px;" class="tablas"> ';
                html_caracter_fisico +='<tbody id="cuerpo_tabla_'+value.id+'" name="cuerpo_tabla_'+value.id+'" class="cuerpo_tabla_'+value.id+'">';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';

              }else if(value.multiple==null && value.tabla==1){
                //  1  DIETA_ORAL
                //  2 INFUCION_CONTINUA
                //  3 DIETA_ENTERAL
                //  4 TRANSFUSIONES
                let item_valor_combo = '';
                $.each(data.intervencion_terapeuticas, function (key, t) { 
                  console.log(value.tabla_tipo+' - '+t.tipo);
                  if(value.tabla_tipo==t.tipo){
                    item_valor_combo+='<option value='+t.id+' >'+t.descripcion_intervencion_t+'</option>';
                  }
                }); 
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="4">'+value.descripcion+'</td>';
                html_caracter_fisico +='</tr>';
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2"><input type="hidden"  name="secuencia[]" value="'+value.secuencia+'"><input type="hidden"  name="descripcion_ingreso[]" value="'+value.descripcion+'"><input type="hidden"  name="es_visible[]" value="1"><input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]"><input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'"><input type="hidden" name="descripcion_caracter_fisico_ingreso[]"><select class="form-control" name="valor_tabla_ingreso[]">'+item_valor_combo+'</select></td> <td colspan="2"><input type="number" step="1"   name="valor_ingreso[]" class="form-control ingreso" > </td>';
                html_caracter_fisico +='</tr>';
                
              }else if(value.multiple==1 && value.tabla==1){
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="4">';
                html_caracter_fisico +='<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                html_caracter_fisico +='<tbody><tr><td>';
                html_caracter_fisico +='<a href="javascript:void(0)" data-secuencia="'+value.secuencia+'" data-tabla_tipo="'+value.tabla_tipo+'" id="add_tabla_'+value.id+'" name="add_tabla_'+value.id+'" class="btn btn-success btn-sm add_tabla_'+value.id+' ingreso">';
                html_caracter_fisico +='<i class="fa fa-plus" ></i>'+value.descripcion;
                html_caracter_fisico +='</a>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='<table id="tbl_tabla_'+value.id+'" style="margin-bottom: 10px !important; margin-left: 8px;" class="tablas"> ';
                html_caracter_fisico +='<tbody id="cuerpo_tabla_'+value.id+'" name="cuerpo_tabla_'+value.id+'" class="cuerpo_tabla_'+value.id+'">';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
              }else{
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2"><input type="hidden"  name="descripcion_ingreso[]" value="'+value.descripcion+'">'+value.descripcion+'</td>'; 
                html_caracter_fisico +='<td colspan="2">'; 
                html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
                html_caracter_fisico +='<input type="hidden" name="es_visible[]" value="1">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'">';
                html_caracter_fisico +='<input type="hidden" name="valor_tabla_ingreso[]">';
                html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
                html_caracter_fisico +='<input type="number" step="1"    name="valor_ingreso[]" class="form-control ingreso">';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
              }
            }
          }
        }); 
        html_caracter_fisico +='</tbody>';
        html_caracter_fisico +='</table>';
        html_caracter_fisico +='<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
        html_caracter_fisico +='<tbody>';
        html_caracter_fisico +='<tr>';
        html_caracter_fisico +='<td style="width: 70%;"><b>TOTAL DE INGRESOS </b></td>';
        html_caracter_fisico +='<td>';
        html_caracter_fisico +='<input  class="form-control" style="height: 28px !important;" id="total_ingreso" required disabled></td>';
        html_caracter_fisico +='</tr>';
        html_caracter_fisico +='</tbody>';
        html_caracter_fisico +='</table>';
        html_caracter_fisico +='<hr>';
        html_caracter_fisico +='</div>';
        html_caracter_fisico +='</div>';

        html_caracter_fisico +='<div class="col-md-6">';
        html_caracter_fisico +='<div class="table-responsive">';
        html_caracter_fisico +='<div class="col-md-12" style="text-align: center;"><b>EGRESOS</b></div>';
        html_caracter_fisico +='<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
        html_caracter_fisico +='<thead>';
        html_caracter_fisico +='<tr>';
        html_caracter_fisico +='<th style="text-align: center;">DESCRIPCIÓN</th>';
        html_caracter_fisico +='<th style="text-align: center;">VALOR</th>';
        html_caracter_fisico +='</tr>';
        html_caracter_fisico +='</thead>';
        html_caracter_fisico +='<tbody id="balance_egreso">';
          $.each(data.caracter_fisico, function (key, value) {
          
          if(value.tipo_balance==2){//EGRESO
                // <input type="hidden" id="tipo_balance'+value.tipo_balance+'" name="tipo_balance[]" value="ingreso">';
            if(value.multiple==1 && value.tabla==null){
                html_caracter_fisico +='<tr>';
                html_caracter_fisico +='<td colspan="2">';
                html_caracter_fisico +='<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                html_caracter_fisico +='<tbody><tr><td>';
                html_caracter_fisico +='<a href="javascript:void(0)" data-secuencia="'+value.secuencia+'" id="add_tabla_'+value.id+'" name="add_tabla_'+value.id+'" class="btn btn-success btn-sm add_tabla_'+value.id+' egreso">';
                html_caracter_fisico +='<i class="fa fa-plus" ></i>'+value.descripcion;
                html_caracter_fisico +='</a>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='<table id="tbl_tabla_'+value.id+'" style="margin-bottom: 10px !important; margin-left: 8px;"> ';
                html_caracter_fisico +='<tbody id="cuerpo_tabla_'+value.id+'" name="cuerpo_tabla_'+value.id+'" class="cuerpo_tabla_'+value.id+'">';
                html_caracter_fisico +='</tbody>';
                html_caracter_fisico +='</table>';
                html_caracter_fisico +='</td>';
                html_caracter_fisico +='</tr>';

            }else if(value.multiple==null && value.tabla==1){
              //  1  DIETA_ORAL
              //  2 INFUCION_CONTINUA
              //  3 DIETA_ENTERAL
              //  4 TRANSFUSIONES
              let item_valor_combo = '';
              $.each(data.intervencion_terapeuticas, function (key, t) { 
                if(value.tabla_tipo==t.tipo){
                  item_valor_combo+='<option value='+t.id+' >'+t.descripcion_intervencion_t+'</option>';
                }
              }); 
              html_caracter_fisico +='<tr>';
              html_caracter_fisico +='<td>';
              html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_egreso[]">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_egreso[]">';
              html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_egreso[]" value="'+value.id+'">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_egreso[]">'+value.descripcion+'</td>';
              
              html_caracter_fisico +='<td><select class="form-control" name="valor_tabla_egreso[]">'+item_valor_combo+'</select><input type="number" step="1"   name="valor_egreso[]" class="form-control egreso" > </td>';
              html_caracter_fisico +='</tr>';
              
            }else{
              html_caracter_fisico +='<tr>';
              html_caracter_fisico +='<td>';
              html_caracter_fisico +='<input type="hidden"  name="secuencia[]" value="'+value.secuencia+'">';
              html_caracter_fisico +='<input type="hidden" name="es_visible[]" value="1">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_egreso[]" value="'+value.descripcion+'">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_egreso[]">';
              html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_egreso[]" value="'+value.id+'">';
              html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_egreso[]">';
              html_caracter_fisico +='<input type="hidden" name="valor_tabla_egreso[]">'+value.descripcion+'</td> <td ><input type="number" step="1"   name="valor_egreso[]" class="form-control egreso" > </td>';
              html_caracter_fisico +='</tr>';
            }
          }
        }); 
        html_caracter_fisico +='</tbody>';
        html_caracter_fisico +='</table>';

        html_caracter_fisico +='<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
        html_caracter_fisico +=' <tbody>';
        html_caracter_fisico +='<tr>';
        html_caracter_fisico +='<td style="width: 70%;"><b>TOTAL DE EGRESOS </b></td>';
        html_caracter_fisico +='<td>';
        html_caracter_fisico +='<input id="total_egreso"  class="form-control" style="height: 28px !important;" required disabled>';
        html_caracter_fisico +='</td>';
        html_caracter_fisico +='</tr>';
        html_caracter_fisico +='</tbody>';
        html_caracter_fisico +='</table>';
        html_caracter_fisico +='<hr>';
        html_caracter_fisico +='</div>';
        html_caracter_fisico +='</div>';
        $('#balance_hidrico').append(html_caracter_fisico);
        

        $('#peso, #hora_registro, #INGRESO_AO, #INGRESO_PI').on('input', function() {
          calculo_horas_peso('peso', 'hora_registro', 'INGRESO_AO', 'INGRESO_PI');
          calculo_horas_peso('peso', 'hora_registro', 'INGRESO_AO2', 'INGRESO_PI2');
          actualizarTotalIngreso();
          actualizarTotalEgreso();
        });
        // $(document).on('click', '[id^=add_tabla_]', function() {
          
        //   var idString = $(this).attr('id').replace('add_tabla_', '');
        //   var id = parseInt(idString, 10);
        //   var datos = data.caracter_fisico.find(item => item.id === id);
        //   let tipo_balance='';
        //   let nombre_campo='';
        //   let descripcion_caracter_fisico='';
        //   let valor_tabla='';
        //   let id_caracter_fisico='';
        //   if (datos.tipo_balance === 1) {
        //     tipo_balance='ingreso';
        //     nombre_campo='valor_ingreso';
        //     descripcion_caracter_fisico='descripcion_caracter_fisico_ingreso';
        //     descripcion_caracter_fisico_id='descripcion_caracter_fisico_id_ingreso';
        //     valor_tabla='valor_tabla_ingreso';
        //     id_caracter_fisico='id_caracter_fisico_ingreso';
        //   } else if (datos.tipo_balance === 2) {
        //     tipo_balance='egreso';
        //     nombre_campo='valor_egreso';
        //     descripcion_caracter_fisico='descripcion_caracter_fisico_egreso';
        //     descripcion_caracter_fisico_id='descripcion_caracter_fisico_id_egreso';
        //     valor_tabla='valor_tabla_egreso';
        //     id_caracter_fisico='id_caracter_fisico_egreso';
        //   }
          
        //   lista_table_balance(
        //   'tbl_tabla_'+id, 
        //    descripcion_caracter_fisico,
        //    descripcion_caracter_fisico_id,  
        //    nombre_campo, 
        //   'id_relacionado_tabla_'+id, 
        //   'boton_eliminar_item_tabla_'+id,
        //   tipo_balance,
        //   valor_tabla,
        //   id_caracter_fisico,
        //   id
        //   );
        //   delete_table_tr_campo('tbl_tabla_'+id,'boton_eliminar_item_tabla_'+id,'');
        // });
  
        /**********************INGRESOS**********************/
        

        // Llamar a la función actualizarTotalIngreso cada vez que cambie el valor de cualquier input
        $(document).on('input', '.ingreso', function() {
          actualizarTotalIngreso();
        });
        $(document).on('input', '.egreso', function() {
          actualizarTotalEgreso();
        });
        // Llamar a la función al cargar la página para inicializar el valor
        $(document).ready(function() {
          $('input[type="number"]').val(0);
          actualizarTotalIngreso();
          actualizarTotalEgreso();
        });

        

      }
    });
  } 
</script>