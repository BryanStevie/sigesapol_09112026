{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script>
      var procedimientosSeleccionados =[];
      var listIdDiagnostico =[];
      var listIdDiagnosticoObjetos =[];
      var filasmedicamentos = 0;

     function agregarRecetaPostQuirurgica() { 
       let _btn =$("#btnNuevoReceetaPostQuirurgico");
      if(_btn.attr("data-status_atencion")==0){
          Swal.fire({
              icon: 'warning',
              title: 'Advertencia',
              html: _btn.attr("data-msje") || 'El servicio no está en su lista de servicios de atención, por lo tanto no puede registrar una receta',
              confirmButtonText: 'Cerrar',
              allowOutsideClick: false
          });
          return false;
      }
      
      if (_btn.attr("flag") == 0) {
          $('#btnNuevoReceetaPostQuirurgico').attr("flag", 1).html("Ocultar Receta");
          $('#es_receta_postquirurgico2').val(1);
          getProductoTempPostQuirurgico();
          $("#fsNuevaRecetaValePostQuirurgico2").show();
          $('#tblMedicamentosAdicionalAtencionPQ tbody').empty();
      } else {
          $('#btnNuevoReceetaPostQuirurgico').attr("flag", 0).html("Agregar Receta");
          $('#es_receta_postquirurgico2').val(0);
          var fecha_valor = $(`#fsNuevaRecetaValePostQuirurgico2`).find("#fecha_expedicion_postqx").data('fecha_valor');  
          $(`#fsNuevaRecetaValePostQuirurgico2`).find("#fecha_expedicion_postqx").val(fecha_valor);
          var $select = $('#tblMedicamentosAdicionalAtencionPQ [name="id_producto_adicional_pq[]"]');
          if ($select.length > 0) { 
              $select.closest("tr").remove();
          }
          $("#fsNuevaRecetaValePostQuirurgico2").hide();
      }
    }
    function getProductoTempPostQuirurgico(){
        var id_ipress 		= $('#id_ipress').val();
        var id_consultorio 	= $('#id_servicio_encargado').val();
        var id_tiporeceta 	= $('#frmAtencionQuirurgicaAdicional #id_tipo_atencion').val();
        var id_farmacia= $('#id_farmacia_receta_postqx').val(); //var id_farmacia     = 128;//id de la Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz
        $('#id_farmacia_postqx2').val(id_farmacia);
        var nivel     = $('#id_nivel').val();
        var x  = $('#nro_interconsultas').val();
        var dni = $('#dni_beneficiario_final').val();
        var interconsulta = $('#interconsulta').val();
        let puede_atennder_servicios_cq = <?= $puede_atennder_servicios_cq ? 'true' : 'false' ?>;
        if(puede_atennder_servicios_cq){
          Swal.fire({
                  title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los productos medicos de la farmacia</span></div>",
                  html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  didOpen: () => {
                  Swal.showLoading();
                  }
          });
          $.ajax({
              url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
              success: function(result){
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });
                $('#cmbMedicamentoTempAdicionalAtencionPQ{{$id_intervencion}}').html(newOption);
                $('#cmbMedicamentoTempPostQxExistente{{$id_intervencion}}').html(newOption);       
              },
              complete: function () {
                    Swal.close();
                  },
          });
        }
    }

    var filasmedicamentosAdicionalPostQuirurgico = 0;
    $('#addProductoNuevoPostQuirurgico2').off('click').on('click', function () {
      AddProductoAdicionalAtencionPostQuirurgico();
    });
    let tomSelectInstancesProductoAdicionalPQ={};
    function AddProductoAdicionalAtencionPostQuirurgico(){
        var newRow = "";
        var ind = filasmedicamentosAdicionalPostQuirurgico;
        var tabindex = 1;
        var nuevalperiodo = "";
        if(ind > 0){
            $('.cmbMedicamentoAdicionalAtencion_pq').each(function(){
                var ind_tmp = $(this).attr("ind");
                if(ind_tmp >= ind){
                  ind=Number(ind_tmp)+1;
                  tabindex+=3;
                }
            });

            fila_medicamentos=$('#tblMedicamentosAdicionalAtencionPQ tbody').children('tr');
            rowIndex=$('#hidden_adicional_rowIndex').val();
            var idval = ind - 1;
            <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
                fila_medicamento=fila_medicamentos[idval];
                var valante = $(fila_medicamento).find('#nro_med_adicional_entregados_pq').val();
                if(valante == ""){
                    bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                    return false;
                }
            <?php //endif;?>

        }
        console.log('Agregando fila con índice:', ind);
        var item_producto2 = "";
        $('#cmbMedicamentoTempAdicionalAtencionPQ{{$id_intervencion}} option').each(function() {
            item_producto2 += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
            "</option>"
        });

        var item_via_administracion = '';
        item_via_administracion+='<option value="0">--Seleccionar--</option>';
        item_via_administracion+='<option value="1">Oral</option>';
        item_via_administracion+='<option value="2">Sublingual</option>';
        item_via_administracion+='<option value="3">Rectal</option>';
        item_via_administracion+='<option value="4">Intravenosa</option>';
        item_via_administracion+='<option value="5">Intramuscular</option>';
        item_via_administracion+='<option value="6">Subcutánea</option>';
        item_via_administracion+='<option value="7">Dérmica</option>';
        item_via_administracion+='<option value="8">Nasal</option>';
        item_via_administracion+='<option value="9">Oftalmológica</option>';
        item_via_administracion+='<option value="10">Inhalatoria</option>';
        item_via_administracion+='<option value="11">Epidural</option>';
        item_via_administracion+='<option value="12">Intratecal</option>';
        item_via_administracion+='<option value="13">Vaginal</option>';
        item_via_administracion+='<option value="14">Intraarticular</option>';
        item_via_administracion+='<option value="15">No Aplica</option>';
        item_via_administracion+='<option value="16">Otros</option>';

        newRow +='<tr class="producto_adicional">';
        newRow +='<td data-label="Producto farmacéutico o dispositivo médico"><select class="select-tom cmbMedicamentoAdicionalAtencion_pq productos-responsive" id="cmbMedicamentoAdicionalAtencion_pq' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto_adicional_pq[]" style="width: 100%">' + item_producto2 + '</select></td>';

        newRow +='<input type="hidden" name="nro_stocks_almacen_adicional_pq[]" id="nro_stocks_almacen_adicional_pq" value=""/>';
        newRow +='<input type="hidden" name="nro_stocks_establecimiento_adicional_pq[]" id="nro_stocks_establecimiento_adicional_pq" value=""/>';
        newRow +='<td data-label="Stock Farmacia"><input type="text" name="nro_stocks_adicional_pq[]" required id="nro_stocks_adicional_pq" class="limpia_text_pq nro_solicitado input-sm productos-responsive" style="margin-left:1px; width: 100%;" readonly=""/></td>';

        newRow +='<td data-label="Tiempo(Dias)"><input onkeypress="return soloNumerosMenosCeroDetalle(event)" type="text" name="tiempo_periodo_adicional_pq[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo_adicional_pq' + ind +'" class="nro_solicitado tiempo_periodo_adicional input-sm productos-responsive" style="margin-left:1px; width: 100%;" value="' + nuevalperiodo +'"/></td>';
        newRow += '<td data-label="Posologia"><input type="text" name="posologiaAdicional_pq[]" tabindex="' + (tabindex + 2) + '" required="" id="posologiaAdicional_pq' +
        ind + '" class="nro_solicitado posologiaAdicional input-sm productos-responsive" style="margin-left:1px; width: 100%;" value=""/>';
        newRow +='<td data-label="Via Adm."><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_adicional productos-responsive" id="id_via_administracion_adicional_pq'+ind+'" name="id_via_administracion_adicional_pq[]" style="margin-left:1px;width: 100%;">'+item_via_administracion+'</select></td>';
        newRow +='<input type="hidden" name="lotes_lote_adicional_pq[]" id="lotes_lote_adicional_pq" value=""/>';
        newRow +='<input type="hidden" name="lotes_registro_sanitario_adicional_pq[]" id="lotes_registro_sanitario_adicional_pq" value=""/>';
        newRow +='<input type="hidden" name="lotes_fecha_vencimiento_adicional_pq[]" id="lotes_fecha_vencimiento_adicional_pq" value=""/>';
        newRow +='<input type="hidden" name="lotes_precio_unitario_adicional_pq[]" id="lotes_precio_unitario_adicional_pq" value=""/>';
        newRow +='<input type="hidden" name="lotes_cantidad_adicional_pq[]" id="lotes_cantidad_adicional_pq" value=""/></td>';
        newRow +='<td data-label="Cant. Pres."><input onKeyPress="return soloNumerosMenosCeroDetalle(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_atencion_adicional_pq[]" required="" id="nro_med_solictados_atencion_adicional_pq' +ind +'" class="limpia_text_pq nro_solicitado nro_med_solictados_atencion_adicional_pq input-sm productos-responsive" style="margin-left:1px; width:100%;" /></td>';
        newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico2"  id="miSelectProdAdicional_pq' + ind + '" name="miSelectProdAdicional_pq[]"></select></td>';
        newRow +='<td data-label="Eliminar"><button type="button" title="Eliminar" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
        newRow +='</tr>';
        $('#frmAtencionQuirurgicaAdicional #tblMedicamentosAdicionalAtencionPQ tbody').append(newRow);
        const selectAdicional2 = new TomSelect("#cmbMedicamentoAdicionalAtencion_pq" + ind, {
          maxItems: 1,
          plugins: ['clear_button']
        });
        tomSelectInstancesProductoAdicionalPQ["cmbMedicamentoAdicionalAtencion_pq" + ind] = selectAdicional2;
        selectAdicional2.focus();

        const selectElementProd = document.getElementById('miSelectProdAdicional_pq' + ind);
        listIdDiagnosticoObjetos.forEach((item) => {
            let option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.nombre;
            selectElementProd.appendChild(option);
        });
        //PEGAR AQUI
        $("#abrir-modal-" + ind).click(function(e) {
            e.stopPropagation();
            abrirModal(ind);
        });
        $("#nro_med_solictados_atencion_adicional_pq"+ind).keypress(function(e){
            if(e.which == 13) { 
                    AddProductoAdicionalAtencionPostQuirurgico(); 
                    validproductoAdicionalseleccionado($('#id_farmacia_postqx2').val(),this);
            }
        });  
        function validproductoAdicionalseleccionado(id_farmacia, btn) {//Jose
            console.log('Entro en función de validproductoAdicionalseleccionado');
            var dni_beneficiario = $('#dni_beneficiario_final').val();
            var nro_dias = 30;
            var objTr = $(btn).closest("tr");
            var id_producto = objTr.find(".cmbMedicamentoAdicionalAtencion_pq").val();
            $.ajax({
                url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                success: function (result) {
                var msg   = "";
                var n     = 0;
                var flag  = true;
                    for(var key in result) {
                        if(n == 0){
                            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
                            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                                        msg += "<thead>";
                                            msg += "<tr>";
                            msg += "<th>Farmacia</th>";
                            msg += "<th>Nro. Receta</th>";
                            msg += "<th>Fecha Expedicion</th>";
                            msg += "<th>Producto</th>";
                            msg += "<th>Tiempo(Dias)</th>";
                            msg += "<th>Dias Transcurridos</th>";
                            msg += "<th>Dias Pendientes</th>";
                            msg += "<th>Cantidad Prescrita</th>";
                                            msg += "</tr>";
                                        msg += "</thead>";
                            msg += "<tbody>";
                        }

                        msg += "<tr><td>"+(result[key].farmacia)+"</td>";
                        msg += "<td>"+(result[key].nro_receta)+"</td>";
                        msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
                        msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
                        msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
                        msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
                        msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
                        msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                        n++;
                    }
                    if(msg != "") {
                        bootbox.confirm({
                            title: "Alerta",
                            message: msg,
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancelar'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Continuar'
                                }
                            },
                            callback: function (result) {
                                if(result==true && typeof verlotes === 'function'){
                                verlotes(id_farmacia, btn);
                                }
                            }
                        });
                    }
                }
            });
        }

        $("#cmbMedicamentoAdicionalAtencion_pq"+ind).on("change", function (e) {
            var flagx = 0;
            let valida=true;
            cmb = $(this);
            id_producto = $("#cmbMedicamentoAdicionalAtencion_pq"+ind).val();
            id_user=<?php echo Auth::user()->id;?>;
            $('.cmbMedicamentoAdicionalAtencion_pq').each(function(){
                var ind_tmp = $(this).val();
                if($(this).val() == id_producto)flagx++;
            });
            if(flagx > 1 && id_producto !=''){
                bootbox.alert({
                  message: `<div style="max-height: 300px; overflow-y: auto;">El producto farmaceutico ya ha sido ingresado</div>`,
                  size: 'large',
                  backdrop: true,
                  callback: function () {
                    $('body').addClass('modal-open');
                    $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
                    const currentTS = tomSelectInstancesProductoAdicionalPQ["cmbMedicamentoAdicionalAtencion_pq" + ind];
                    if (currentTS) {
                      currentTS.clear();
                      currentTS.setTextboxValue('');
                      currentTS.blur();
                    }
                  }
                });
            }
            if(id_producto && id_user){
              var id_farmacia_receta = $('#id_farmacia_postqx2').val();

              option = {
                url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
                type: 'GET',
                dataType: 'json',
                data: {}
              };
              $.ajax(option).done(function (data) {
                  var cantidad = data.cantidad;
                  var cantidadEstablecimiento = data.cantidadEstablecimiento;
                  var cantidadAlmacen = data.cantidadAlmacen;
                  $(cmb).closest("tr").find(".limpia_text_pq").val("");
                  //$(cmb).closest("tr").find("#nro_stocks").val(data);
                  $(cmb).closest("tr").find("#nro_stocks_adicional_pq").val(cantidad);
                  $(cmb).closest("tr").find("#nro_stocks_establecimiento_adicional_pq").val(cantidadEstablecimiento);
                  $(cmb).closest("tr").find("#nro_stocks_almacen_adicional_pq").val(cantidadAlmacen);
                  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
                  $(cmb).closest("tr").find(".tiempo_periodo_adicional").focus();
                  <?php //endif;?>
                  $(cmb).closest("tr").find(".nro_med_solictados_atencion_adicional_pq").val("");
                  $(cmb).closest("tr").find("#nro_med_entregados_adicional_pq").val("");
                  $(cmb).closest("tr").find("#lotes_lote_adicional_pq").val("");
                  $(cmb).closest("tr").find("#lotes_cantidad_adicional_pq").val("");
                  $(cmb).closest("tr").find("#lotes_registro_sanitario_adicional_pq").val("");
                  $(cmb).closest("tr").find("#lotes_fecha_vencimiento_adicional_pq").val("");
              });
            }
        });
        filasmedicamentosAdicionalPostQuirurgico++;
    }
    
    $('#tblMedicamentosAdicionalAtencionPQ tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      $(this).parent().parent().remove();
    });




    
    
  
  $('#tblDiagnosticoDetalle tr').each(function() {
    let idDiagnostico = $(this).find('input.nuevo_id_diagnostico').val();
    let nombreDiagnostico = $(this).find('input.nuevo_nombre_diagnostico').val();
    let codigoDiagnostico = $(this).find('input.nuevo_codigo_diagnostico').val();
    if (idDiagnostico && nombreDiagnostico) {
        if (listIdDiagnosticoObjetos.findIndex(e => e.id === idDiagnostico) === -1) {
            listIdDiagnosticoObjetos.push({
                id: idDiagnostico,
                nombre: nombreDiagnostico,
                codigo: codigoDiagnostico
            });
        }
        updateSelectElements();
    }
  });

  function formatear_fechaDate(fecha) {
      if (/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
          return fecha; // Ya está en YYYY-MM-DD
      }
      if (/^\d{2}-\d{2}-\d{4}$/.test(fecha)) {
          return fecha.split('-').reverse().join('-');
      }
      return null;
  }
    function validarAsegurado(){
      var tipodoc_beneficiario = {!! json_encode(isset($titular->id_tipo_doc_ident) ? trim($titular->id_tipo_doc_ident) : '') !!};
      var dni_beneficiario = {!! json_encode(isset($titular->nro_doc_ident) ? trim($titular->nro_doc_ident) : '') !!};
      ipodoc_beneficiario = tipodoc_beneficiario.trim();
      dni_beneficiario = dni_beneficiario.trim();
      var deferred = $.Deferred();
      if(tipodoc_beneficiario && dni_beneficiario){
          var id_tiporeceta = "1";
          let usarApi = "{{ $flag_api_sp ? 'api' : 'ws' }}";
          var origen = 'atencion_qx';
          $.ajax({
              url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario + '/' + usarApi + '/' + origen,
              dataType: "json"
          })
          .done(function(result){

              if(result.sw == false){

                  Swal.fire({
                      icon: 'warning',
                      title: 'Advertencia',
                      html: result.msg || 'El paciente no se encuentra afiliado',
                      confirmButtonText: 'Cerrar',
                      allowOutsideClick: false
                  });

                  deferred.resolve(false);
                  return;
              }

              deferred.resolve(true);

          })
          .fail(function(){

              Swal.fire({
                  icon:'error',
                  text:'Error al validar el asegurado'
              });

              deferred.resolve(false);
          });

      }else{
        deferred.resolve(true);
      }
      return deferred.promise();
  }
    function validarFechaEgreso() {
      const egresoInput = document.getElementById('fecha_egreso');
      const atencionInput = document.getElementById('fecha_hora_atencion');

      if (!egresoInput.value || !atencionInput.value) {
          return true;
      }

      const fechaEgreso = new Date(egresoInput.value + 'T00:00:00');
      const fechaAtencion = new Date(atencionInput.value.substring(0, 10) + 'T00:00:00');

      const diferenciaMs = fechaEgreso.getTime() - fechaAtencion.getTime();
      const dias = Math.abs(
          Math.floor(diferenciaMs / (1000 * 60 * 60 * 24))
      );

      // Si son iguales no mostrar nada
      if (fechaEgreso.getTime() === fechaAtencion.getTime()) {
          return true;
      }

      // Fecha egreso menor
      if (fechaEgreso < fechaAtencion) {

          Swal.fire({
              icon: 'warning',
              title: 'Fecha inválida',
              html:
                  'La fecha de egreso no puede ser menor que la fecha de atención.' +
                  '<br><br>' +
                  'Diferencia de días: <span style="color:red;font-weight:bold;">' +
                  ' -' + dias +
                  '</span>'
          }).then(function(){
              var fechaAtencionVal = atencionInput.value.split("T")[0];
              fechaAtencionVal=formatear_fechaDate(fechaAtencionVal);  
              egresoInput.value = fechaAtencionVal;

          }); 

          return false;
      }

      // Fecha egreso mayor
      Swal.fire({
          icon: 'info',
          title: 'Días de estancia',
          html:
              '<span style="color:blue;font-weight:bold;">' +
              dias + ' día(s)' +
              '</span>'
      });

      return true;
  }
    function validarFechaRecetaPostQX() {
    let estado = true;
    function validar() {
        let id_prestacion = $('#id_prestacion_atq').val();
        let fecha_egreso = $('#fecha_egreso').val();
        $.ajax({
            url: '/validar_fechas_receta_postqx',
            type: 'POST',
            async: false,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_prestacion: id_prestacion,
                fecha_egreso: fecha_egreso
            },
            success: function(resp){
                if (resp.status === 'error') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validación de fecha',
                        html: `
                            ${resp.mensaje}<br><br>
                            <b>Fecha receta:</b> ${resp.fecha_expedicion}<br>
                            <b>Diferencia:</b> ${resp.dias} día(s)
                        `
                    });
                    estado = false;
                } else {
                    estado = true;
                }
            },
            error: function(){
                estado = false;
            }
        });
        return estado;
    }
    return validar;
  } 

  function mostrarModalInfoPaciente2() {
      $('#modal-info-paciente2').modal({
          backdrop: false,
          keyboard: false,
          show: true
      });

      $('#modal-info-paciente2').css({
          'z-index': 1060,
          'overflow-y': 'auto'
      });
  }

  $('#modal-info-paciente2').on('hidden.bs.modal', function () {
      $('#modal-detalle-atencion-quirurgica').css('z-index', 1050).modal('show');
      $('body').addClass('modal-open');
  });

  $('#modal-info-paciente2 .close').off('click').on('click', function (e) {
      e.stopPropagation();
      $('#modal-info-paciente2').modal('hide');
  });

  $('#modal-detalle-atencion-quirurgica').on('shown.bs.modal', function () {
      $('body').addClass('modal-open');
  });

  $('#modal-info-paciente2').on('shown.bs.modal', function () {
      $('body').addClass('modal-open');
  });

    function cambiar_tipo_egreso(){
      let vId_Intervencion= $("#id_intervencion").val();
      var id_tipo_egreso=  $("#id_tipo_egreso"+ vId_Intervencion).val();
      switch(id_tipo_egreso){
        case '1': //ALTA
          $("#egreso_alta"+vId_Intervencion).hide();
          $("#egreso_hospitalizacion").hide();
          $("#egreso_defuncion").hide();
          $("#id_condicion_alta").val('');
          $("#egreso_traslado").hide();
          $("#alta_condicion"+vId_Intervencion).show('');
          $("#destino_domiciliaria"+vId_Intervencion).show('');
          $("#epicrisis"+vId_Intervencion).show('');
          $("#destino_hospitalizacion"+vId_Intervencion).hide();
          $("#alta_condicion_tra"+vId_Intervencion).hide('');
          $("#alta_condicion_fuga").hide('');
          $("#alta_condicion_de").hide('');
          $("#alta_condicion_ret").hide('');
          $("#egreso_retiro").hide();
          $("#egreso_fuga").hide();
          $("#btnNuevoReceetaPostQuirurgico").show();
        break;
        case '2': //HOSPITALIZACIÓN
          $("#egreso_alta"+vId_Intervencion).hide();
          $("#egreso_hospitalizacion").show();
          $("#egreso_defuncion").hide();
          $("#id_condicion_alta").val('');
          $("#egreso_traslado").hide();
          $("#alta_condicion"+ vId_Intervencion).hide('');
          $("#alta_condicion_tra"+ vId_Intervencion).show('');
          $("#destino_domiciliaria"+ vId_Intervencion).hide();
          $("#epicrisis"+ vId_Intervencion).hide();
          $("#destino_hospitalizacion"+ vId_Intervencion).show('');
          $("#alta_condicion_fuga").hide('');
          $("#alta_condicion_de").hide('');
          $("#alta_condicion_ret").hide('');
          $("#egreso_retiro").hide();
          $("#egreso_fuga").hide();
          $("#btnNuevoReceetaPostQuirurgico").hide();
        break; 
        case '3': //TRASLADO
          $("#egreso_alta").hide();
          $("#egreso_hospitalizacion").hide();
          $("#egreso_defuncion").hide();
          $("#egreso_traslado").show();
          $("#id_condicion_alta").val('');
          $("#alta_condicion_fuga").hide('');
          $("#alta_condicion").hide('');
          $("#alta_condicion_de").hide('');
          $("#alta_condicion_tra").show('')
          $("#alta_condicion_ret").hide('');
          $("#egreso_retiro").hide();
          $("#egreso_fuga").hide();
          $("#btnNuevoReceetaPostQuirurgico").hide();
        break;
        case '4': //DEFUNCION
          $("#egreso_alta").hide();
          $("#egreso_hospitalizacion").hide();
          $("#egreso_traslado").hide();
          $("#egreso_defuncion").show();
          $("#alta_condicion").hide('');
          $("#alta_condicion_de").show('');
          $("#alta_condicion_fuga").hide('');
          $("#alta_condicion_tra").hide('');
          $("#alta_condicion_ret").hide('');
          $("#egreso_retiro").hide();
          $("#egreso_fuga").hide();
          $("#btnNuevoReceetaPostQuirurgico").hide();
        break;
        case '6': // FUGA
          $("#egreso_alta").hide();
          $("#egreso_hospitalizacion").hide();
          $("#egreso_defuncion").hide();
          $("#egreso_traslado").hide();
          $("#id_condicion_alta").val('');
          $("#alta_condicion_fuga").show('');
          $("#alta_condicion").hide('');
          $("#alta_condicion_de").hide('');
          $("#alta_condicion_tra").hide('');
          $("#alta_condicion_ret").hide('');
          $("#egreso_fuga").show();
          $("#egreso_retiro").hide();
          $("#btnNuevoReceetaPostQuirurgico").hide();
        break;
        case '7': // RETIRO VOLUNTARIO
          $("#egreso_alta").hide();
          $("#egreso_hospitalizacion").hide();
          $("#egreso_defuncion").hide();
          $("#egreso_traslado").hide();
          $("#id_condicion_alta").val('');
          $("#alta_condicion_fuga").hide('');
          $("#alta_condicion").hide('');
          $("#alta_condicion_de").hide('');
          $("#alta_condicion_tra").hide('');
          $("#alta_condicion_ret").show('');
          $("#egreso_retiro").show();
          $("#egreso_fuga").hide();
          $("#btnNuevoReceetaPostQuirurgico").hide();
        break;
        default: //HOSPITALIZACIÓN
            $("#alta_condicion").hide();
            $("#egreso_alta").hide();
            $("#egreso_hospitalizacion").hide();
            $("#egreso_defuncion").hide();
            $("#alta_condicion_fuga").hide();
            $("#id_condicion_alta").val('');
            $("#egreso_traslado").hide();
            $("#alta_condicion_tra").hide('');
            $("#alta_condicion_de").hide('');
            $("#alta_condicion_ret").hide('');
            $("#egreso_retiro").hide();
            $("#egreso_fuga").hide();
            $("#destino_domiciliaria"+ vId_Intervencion).hide();
            $("#destino_hospitalizacion"+ vId_Intervencion).hide();
            $("#btnNuevoReceetaPostQuirurgico").hide();
      }
    }
    function change_tipo_traslado() {
      let id_tipo_traslado =  $("#id_tipo_traslado").val();
      $("#id_establecimiento_traslado").val("").change();
      if (id_tipo_traslado == 2) {
        $("#egreso_traslado").show();
      } else {
        $("#egreso_traslado").hide();
      }
    }

    function cambiar_derivacion(){
      var derivar= $("#derivar").val();
      if(derivar=='SI'){ //SI
        $.getJSON("/listar_consultorios_ajax/1", function(json){
          document.getElementById("id_servicio_consulta_externa").innerHTML = "";
          $('#id_servicio_consulta_externa').append($('<option>').text("Seleccione").attr('value', ""));
          $('#id_servicio_consulta_externa').append($('<option>').text("OTRO").attr('value', "OTRO"));
          $.each(json, function(i, obj){
            //alert(obj[0].nombre);      alert(obj.length);
            var len= obj.length;
            for(j=0;j<len;j++){
              $('#id_servicio_consulta_externa').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));
            }

          });
        });
      }else{
        document.getElementById("id_servicio_consulta_externa").innerHTML = "";
      }
    }

    $('#btnGuardarAtencionAdicional').on('click', function(e) { 
    $('#frmAtencionQuirurgicaAdicional .validado_incorrecto').removeClass('validado_incorrecto');
    validarAsegurado().then(function(resultado){  
        if(!resultado) return; 
          e.preventDefault();
          var validacionMostrada = false; 
          function lanzarError(e){ 
              if (validacionMostrada) {    return;  }
              validacionMostrada = true; 
               console.log("Error de validación: ", e);

              Swal.fire({icon: 'error', timer: 3500, showConfirmButton: false,scrollbarPadding: false,
                          html: `<strong class="texto-negrita-14">${e.nombre}</strong><br>${e.msje}`,
              }).then(function(){ 
                  if (typeof e.fnc === 'function') {
                      e.fnc();
                  }
              });
              var vlr_campo = (e.id_campo.includes(' ')==true) ?  e.id_campo.split(' ')[0] : e.id_campo ; 
              $(vlr_campo).addClass("validado_incorrecto");   
              if (vlr_campo && $(vlr_campo).length) { 
                  setTimeout(() => { 
                          const modal = document.querySelector('#modal-detalle-atencion-quirurgica');
                          const position_content = document.querySelector('#modal-detalle-atencion-quirurgica '+vlr_campo);  
                          const posicion =position_content.getBoundingClientRect().top -  modal.getBoundingClientRect().top +  modal.scrollTop - 150;
                          modal.scrollTo({  top: posicion,   behavior: 'smooth' }); 
                  }, 150);
              }
              if(e.content_panel!=null){  $(e.content_panel).click();  }   

          } 
          
          // Validación de diagnósticos
          let tbl_dxDetalle=  $('#tblDiagnosticoDetalle');
          if (tbl_dxDetalle.find('tbody tr').length == 0) {
              lanzarError({content_panel: '#tab_DiagnosticoX', id_campo: `#${tbl_dxDetalle.attr('id')}`,  nombre: 'SECCIÓN DE DIAGNOSTICO', msje: 'Debe ingresar al menos un diagnóstico'  });
              return false;
          } 
          if(tbl_dxDetalle.find('tbody tr').length>0){
              tbl_dxDetalle.find('tbody tr').each(function () {
                  let id_diagnostico = $(this).find('.id_diagnostico'); 
                  let name_diagnostico = $(this).find('input[name="nombre_diagnostico[]"]');
                  let id_tipo_diagnostico = $(this).find('.id_tipo_diagnostico');
                  let id_categoria_diagnostico = $(this).find('.id_categoria_diagnostico');

                  if (id_diagnostico.val() === "") {  
                      lanzarError({content_panel: '#tabDiagnostico', id_campo: `#${name_diagnostico.attr('id')}`,  nombre: 'SECCIÓN DE DIAGNOSTICO', msje: 'Debe ingresar el nombre de diagnóstico'  });
                      return false;  
                  }
                  if (id_tipo_diagnostico.val() === "0") {   
                      lanzarError({content_panel: '#tabDiagnostico', id_campo: `#${id_tipo_diagnostico.attr('id')}`,  nombre: 'SECCIÓN DE DIAGNOSTICO', msje: 'Debe ingresar el tipo de diagnóstico'  });
                      return false;  
                  }
                  if (id_categoria_diagnostico.val() === "0") {  
                      lanzarError({content_panel: '#tabDiagnostico', id_campo: `#${id_categoria_diagnostico.attr('id')}`,  nombre: 'SECCIÓN DE DIAGNOSTICO', msje: 'Debe ingresar la prioridad del diagnóstico'  });
                      return false;  
                  }
              });   
          } 

          //  Validación de Procedimiento    
          let tbl_procedimientoDetalle=  $('#tblProcedimientoDetalle'); 
         
          if(tbl_procedimientoDetalle.find('tbody tr').length > 0) {

              tbl_procedimientoDetalle.find('tbody tr').each(function (index) {
                  var id_procedimiento = $(this).find('.id_procedimiento');
                  var cantidad = $('#procedimiento_cantidad' + index);
                  var miSelect = $('#miSelect' + index);

                  if (id_procedimiento.val() === "") { 
                      lanzarError({content_panel: '#tab_ProcedimientoX', id_campo: `#${tbl_procedimientoDetalle.attr('id')}`,  nombre: 'SECCIÓN DE PROCEDIMIENTO', msje: 'Debe ingresar el nombre del procedimiento'  });
                      return false;
                  }
                  if (cantidad.val() === "") {
                        lanzarError({content_panel: '#tab_ProcedimientoX', id_campo: `#${cantidad.attr('id')}`,  nombre: 'SECCIÓN DE PROCEDIMIENTO', msje: 'Debe ingresar la cantidad del procedimiento'  });
                        return false;
                  } else if (parseInt(cantidad.val()) <= 0) {
                        lanzarError({content_panel: '#tab_ProcedimientoX', id_campo: `#${cantidad.attr('id')}`,  nombre: 'SECCIÓN DE PROCEDIMIENTO', msje: 'La cantidad del procedimiento debe ser mayor a 0'  });
                        return false;
                  }
              }); 
          }
          //  Validación de Procedimiento  

          //RECETA EXISTENTE Y PRODUCTO   
          var resultadoValidacion = true; 
          if($('.tblMedicamentosRecetaExistente').length > 0){
            $('.tblMedicamentosRecetaExistente').each(function() {
                if ($(this).find('tbody tr').length > 0) {
  
                    var Receta_Id = $(this).data('receta');
                    var $tabla = $(this);
                    var ind = $tabla.find('tbody tr').length;
  
                    var nind = ind - 1;
                    var cmbMedicamentoAQ_check = $tabla.find('.cmbMedicamento').eq(nind).val();  
                    var fila_productosmedicos = $tabla.find('tbody').children('tr');
                    for (i = 0; i < ind; i++) {
  
                        fila_medicamento = fila_productosmedicos[i];
                        var $fila = $(fila_medicamento);
                        /******************** PRODUCTOS EDITAR ********************/
                        var tiempo_periodo_editar     = $fila.find('.tiempo_periodo_editar').val();
                        var posologia_editar          = $fila.find('.posologia_editar').val();
                        var valmedentre_editar        = $fila.find('.cantidad_prescrita_editar').val();
                        var via_administracion_editar = $fila.find('.id_via_administracion_editar').val();
  
                        if (tiempo_periodo_editar == "" || tiempo_periodo_editar == 0) {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.tiempo_periodo_editar').attr('id'), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos en editar no cuenta el tiempo(dias), tiene que ser mayor a 0" });
                            return false; 
                        }
  
                        if (via_administracion_editar == 0 || via_administracion_editar == "undefined") {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.id_via_administracion_editar').attr('id'), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos en editar no cuenta con la via de administración" });
                            return false;
                        }
  
                        if (posologia_editar == "") {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.posologia_editar').attr('id'), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos en editar no cuenta con la posologia" });
                            return false;
                        }
  
                        if (valmedentre_editar == "" || valmedentre_editar == 0) {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.cantidad_prescrita_editar').attr('id'), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos en editar no cuenta con la cantidad prescrita, tiene que ser mayor a 0" });
                            return false;
                        }
                        /***************************************************************/
                        var cmbMedicamentoAQ   = $fila.find('.cmbMedicamento').val();
                        var tiempo_periodo     = $fila.find('.tiempo_periodo').val();
                        var posologia          = $fila.find('.posologia').val();
                        var valmedentre        = $fila.find('.nro_med_solictados').val();
                        var via_administracion = $fila.find('.id_via_administracion').val();
  
                        if (cmbMedicamentoAQ == "ru='undefined'" || cmbMedicamentoAQ == "undefined") {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.cmbMedicamento').attr("id"), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Falta seleccionar un producto (dispositivo médico)" });
                            return false;
                        }
  
                        if (tiempo_periodo == "" || tiempo_periodo == 0) {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.tiempo_periodo').attr("id"), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0" });
                            return false;
                        }
  
                        if (via_administracion == 0 || via_administracion == "undefined") {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.id_via_administracion').attr("id"), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos no cuenta con la via de administración" });
                            return false;
                        }
  
                        if (posologia == "") {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.posologia').attr("id"), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos no cuenta con la posologia" });
                            return false;
                        }
  
                        if (valmedentre == "" || valmedentre == 0) {
                            resultadoValidacion = lanzarError({content_panel: null, id_campo: '#'+$fila.find('.nro_med_solictados').attr("id"), nombre: 'SECCIÓN DE RECETA (Receta N° ' + Receta_Id + ')', msje: "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0" });
                            return false;
                        }
                    }
  
                    if (resultadoValidacion === false) {
                        return false; // ---> CORTA el .each() de tablas (por si el for interno terminó sin "return false" propagarse)
                    }
                }
            }); 
            if(resultadoValidacion==false){
              return false;   
            }
          }
          //FIN RECETA EXISTENTE Y PRODUCTO  
          //NUEVA RECETA Y PRODUCTO
          if($("#btnNuevoReceeta").attr("flag") === '1' ){
              var  v_Fecha_atencion = $('#fecha_hora_atencion').val().split('T'); 
              v_Fecha_atencion=v_Fecha_atencion[0]; 
              var vFechaExpedicion=$("#fecha_expedicion"); 

              if(vFechaExpedicion.val()==''){ 
                lanzarError({content_panel: null, id_campo: `#${vFechaExpedicion.attr('id')}`,  nombre: 'SECCIÓN DE RECETA MEDICA', msje: 'Debe ingresar la fecha de expedición'  });
                return false;
              }
              var ind = $('#frmAtencionQuirurgicaAdicional #tblMedicamentosAdicionalAtencion tbody tr').length;
              if(ind==0){
                lanzarError({content_panel: null, id_campo: `#tblMedicamentosAdicionalAtencion`,  nombre: 'SECCIÓN DE RECETA MEDICA', msje: 'Debe ingresar al menos un medicamento para la receta'  });
                return false;
              }
              if(ind > 0){
                  var fila_productosmedicos = $('#frmAtencionQuirurgicaAdicional #tblMedicamentosAdicionalAtencion tbody').children('tr');
                  for (i = 0; i < ind; i++) {
                      fila_medicamento = fila_productosmedicos[i];
                      var $fila = $(fila_medicamento);
    
                      var cmbMedicamentoAQ   = $fila.find('.cmbMedicamentoAdicionalAtencion');
                      var tiempo_periodo     = $fila.find('.tiempo_periodo_adicional');
                      var posologia          = $fila.find('.posologiaAdicional');
                      var valmedentre        = $fila.find('.nro_med_solictados_atencion_adicional');
                      var via_administracion = $fila.find('.id_via_administracion_adicional');
    
                      if(cmbMedicamentoAQ.val() == "ru='undefined'" || cmbMedicamentoAQ.val() == "undefined"){
                          lanzarError({content_panel: null, id_campo: '#'+cmbMedicamentoAQ.attr("id"), nombre: 'SECCIÓN DE RECETA', msje: "Falta seleccionar un producto (dispositivo médico)" });
                          return false;
                      }
    
                      if(tiempo_periodo.val() == "" || tiempo_periodo.val() == 0){
                          lanzarError({content_panel: null, id_campo: '#'+ tiempo_periodo.attr("id"), nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0" });
                          return false;
                      }
    
                      if(via_administracion.val() == 0 || via_administracion.val() == "undefined"){
                          lanzarError({content_panel: null, id_campo: '#'+ via_administracion.attr("id"), nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la via de administración" });
                          return false;
                      }
    
                      if(posologia.val() == ""){
                          lanzarError({content_panel: null, id_campo: '#'+ posologia.attr("id"), nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la posologia" });
                          return false;
                      }
    
                      if(valmedentre == "" || valmedentre == 0){
                          lanzarError({content_panel: null, id_campo: '#'+ valmedentre.attr("id"), nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0" });
                          return false;
                      }
                  }
              } 
          }
          //FIN NUEVA RECETA Y PRODUCTO
          var  vFechaAtencion = $('#fecha_hora_atencion').val().split('T'); 
          vFechaAtencion=vFechaAtencion[0];



          //DESCRIPCIÓN DE PROCEDIMIENTO 
          var addDescripcionProcedimiento = $('#addDescripcionProcedimiento').attr("aria-pressed");
          var descripcion_procedimiento = $('#descripcion_procedimiento'); 
          if (addDescripcionProcedimiento === "true") { 
             if (descripcion_procedimiento.val().trim() === '') {
                lanzarError({content_panel: null, id_campo: '#'+ descripcion_procedimiento.attr("id"), nombre: 'SECCIÓN DE DESCRIPCIÓN DE PROCEDIMIENTO', msje: "Debe de ingresar la descripción del procedimiento" });
                return false;
            }
          } 
          //FIN DESCRIPCIÓN DE PROCEDIMIENTO 
          //HALLAZGOS OPERATORIO
          var addAllazgoOpe = $('#addAllazgoOpe').attr("aria-pressed");
          var allazgo_operatorio = $('#allazgo_operatorio');
          if (addAllazgoOpe === "true") { 
            if (allazgo_operatorio.val().trim() === '') { 
                lanzarError({content_panel: null, id_campo: '#'+ allazgo_operatorio.attr("id"), nombre: 'SECCIÓN DE HALLAZGOS OPERATORIO', msje: "Debe de ingresar datos en HALLAZGOS OPERATORIO" });
                return false;
            }
          }
          //FIN HALLAZGOS OPERATORIO
          //EXAMEN PATOLOGICO
          var addExamenPatologico2 = $('#addExamenPatologico2').attr("aria-pressed");
          if (addExamenPatologico2 === "true") {  
              let tbl_procedimiento_examen_patologico2 =  $('#table_procedimiento_examen_patologia2');
              var addPiezaOpe = $('#addPiezaOpe').attr("aria-pressed");
              var pieza_operatorio = $('#pieza_operatorio');

              if(tbl_procedimiento_examen_patologico2.find('tbody tr').length ==  0){ 
                  lanzarError({content_panel: null, id_campo: '#'+ tbl_procedimiento_examen_patologico2.attr("id"), nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Debe ingresar mínimo un procedimiento (Examen patologico)" });
                  return false;
              }

              if(tbl_procedimiento_examen_patologico2.find('tbody tr').length >  0){ 
                  tbl_procedimiento_examen_patologico2.find('tbody tr').each(function (index) {
                    var id_procedimiento = $(this).find('.cmbProcedimientoExamenPatologia');
                    if (id_procedimiento.val() === "") { 
                      lanzarError({content_panel: null, id_campo: '#'+ id_procedimiento.attr("id"), nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Debe ingresar el nombre del procedimiento (Examen patologico)" });
                      return false;
                    }
                  });
              } 


              if (addPiezaOpe === "true" && pieza_operatorio.val().trim() === '') {  
                    lanzarError({content_panel: null, id_campo: '#'+ pieza_operatorio.attr("id"), nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Debe de ingresar datos en PIEZA OPERATORIO" });
                    return false; 
              }
          }
          //FIN EXAMEN PATOLOGICO

          //EXAMEN BACTERIOLOGICO
          var addExamenBacteriologico = $('#addExamenBacteriologico').attr("aria-pressed");
          var examen_bacteriologico = $('#examen_bacteriologico');
          if (addExamenBacteriologico === "true" && examen_bacteriologico.val().trim() === '') {   
                lanzarError({content_panel: null, id_campo: '#'+ examen_bacteriologico.attr("id"), nombre: 'SECCIÓN DE EXAMEN BACTERIOLOGICO', msje: "Debe de ingresar datos en EXAMEN BACTERIOLOGICO" });
                return false;
          }
          //FIN EXAMEN BACTERIOLOGICO

          //EXAMEN REGISTRO MEDICO
          var addComplicacionMedica = $('#addComplicacionMedica').attr("aria-pressed");
          var complicacion_medica = $('#complicacion_medica');
          if (addComplicacionMedica === "true" && complicacion_medica.val().trim() === '') {   
                lanzarError({content_panel: null, id_campo: '#'+ complicacion_medica.attr("id"), nombre: 'SECCIÓN DE REGISTRO MEDICO ', msje: "Debe de ingresar el registro Médico (Complicaciones)" });
                return false;
          }
          //FIN EXAMEN REGISTRO MEDICO 
          //EXAMEN DESTINO 
          var IdxIntervencion=  $("#frmAtencionQuirurgicaAdicional #id_intervencion").val();
          var addDestino = $('#addDestino'+ IdxIntervencion).attr("aria-pressed"); 
          var id_tipo_egreso = $('#id_tipo_egreso'+ IdxIntervencion);   

          if (addDestino === "true") {

               if (tbl_procedimientoDetalle.find('tbody tr').length == 0   ) {
                  lanzarError({content_panel: '#tab_ProcedimientoX', id_campo: `#${tbl_procedimientoDetalle.attr('id')}`,  nombre: 'SECCIÓN DE PROCEDIMIENTO', msje: 'Debe ingresar al menos un procedimiento'  });
                  return false;
              }

              if (!id_tipo_egreso.val()) {
                   lanzarError({content_panel: null, id_campo: '#'+id_tipo_egreso.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar el destino" });
                   return false;
              }

              if (id_tipo_egreso.val() == 2) { // HOSPITALIZACION
                  let id_area = $('#id_area_destino');
                  let id_cama_destino = $('#id_cama_destino');

                  if (!id_area.val()) {
                      lanzarError({content_panel: null, id_campo: '#'+id_area.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar SALA A CARGO/SALA DESTINO" });
                      return false;
                  }

                  if (!id_cama_destino.val()) {
                      lanzarError({content_panel: null, id_campo: '#'+id_cama_destino.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar la CAMA" });
                      return false;
                  }
              }

              var $visibleSelect = $('select.condicion-alta:visible:not(:disabled)');
              if ($visibleSelect.length) {
                  var id_condicion_alta = $visibleSelect.val();
                  if (id_condicion_alta.length === 0) {
                      lanzarError({content_panel: null, id_campo: '#'+$visibleSelect.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar la condición de alta" });
                      return false;
                  }
              }

              if (id_tipo_egreso.val() == 1) { // DOMICILARIA
                  let fechaEgreso = $('#fecha_egreso').val();
                  var  v_Fecha_atencion = $('#fecha_hora_atencion').val().split('T'); 
                  v_Fecha_atencion=v_Fecha_atencion[0];

                  if (!validarFechaRecetaPostQX()) { return false; }
                  var fechaEgreso_date = new Date(  formatear_fechaDate(fechaEgreso)  );
                  var fechaAtencion_date = new Date(  formatear_fechaDate(v_Fecha_atencion) ); 
                  if(fechaEgreso_date < fechaAtencion_date){
                      lanzarError({content_panel: null, id_campo: '#fecha_egreso', nombre: 'SECCIÓN DE DESTINO', msje: "La fecha de egreso debe de ser mayor o igual a la fecha de la atencón." });
                      return false;
                  } 

                  if($("#btnNuevoReceetaPostQuirurgico").attr("flag") === '1' ){ 
                      let fechaExpedicion = $('#fecha_expedicion_postqx').val();     

                      if (formatear_fechaDate(fechaExpedicion) != formatear_fechaDate(fechaEgreso)) {
                        lanzarError({content_panel: null, id_campo: '#fecha_expedicion_postqx', nombre: 'SECCIÓN DE DESTINO', msje: "La fecha de expedición debe coincidir con la fecha de egreso.",
                            fnc:function(){
                              Swal.fire({"icon":'warning',html:'<label>PANEL DE VALIDACION</label><br><span class="texto-negrita-14">La fecha de expedición se asignará automáticamente según la fecha de egreso.<span>'}).then(function(){
                                $('#fecha_expedicion_postqx').val(fechaEgreso).trigger('change').focus();  
                              });
                            } 
                        }); 
                        return false;
                      }  
                      let tbl_receta_postquirurgico= $("#tblMedicamentosAdicionalAtencionPQ");
                      if(tbl_receta_postquirurgico.find('tbody tr').length==0){
                           lanzarError({content_panel: null, id_campo:  '#'+tbl_receta_postquirurgico.attr("id")  , nombre: 'SECCIÓN DE PRODUCTOS MÉDICOS POSTQUIRÚRGICOS', msje: "Debe ingresar al menos un medicamento a la receta POSTQUIRÚRGICO"  }); 
                           return false;
                      } 
                  }

                  let hora_egreso = $('#hora_egreso').val().trim();
                  const regex = /^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/;

                  if (hora_egreso === '') {
                      lanzarError({content_panel: null, id_campo: '#hora_egreso', nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la hora de egreso" });
                      return false;
                  } else if (hora_egreso === '00:00' || hora_egreso === '00:00:00') {
                      lanzarError({content_panel: null, id_campo: '#hora_egreso', nombre: 'SECCIÓN DE DESTINO', msje: "La hora de egreso no puede ser 00:00:00." });
                      return false;
                  } else if (!regex.test(hora_egreso)) {
                      lanzarError({content_panel: null, id_campo: '#hora_egreso', nombre: 'SECCIÓN DE DESTINO', msje: "Formato de hora inválido. Ingrese al menos hora y minutos (HH:mm)." });
                      return false;
                  }


                  // Obtener valores de cada textarea por su id
                  var egreso_resumen= $('#egreso_resumen');
                  if (egreso_resumen.length > 0 && egreso_resumen.val().trim() === "") { 
                      lanzarError({content_panel: null, id_campo: '#'+egreso_resumen.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el resumen de egreso (EPICRISIS)" });
                      return false;
                  }
                  var egreso_examen_clinico= $('#egreso_examen_clinico');
                  if (egreso_examen_clinico.length > 0 && egreso_examen_clinico.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_examen_clinico.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen clínico (EPICRISIS)" });
                      return false;
                  }
                  var egreso_examen_auxiliar= $('#egreso_examen_auxiliar');
                  if (egreso_examen_auxiliar.length > 0 && egreso_examen_auxiliar.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_examen_auxiliar.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen auxiliar (EPICRISIS)" });
                      return false;
                  }
                  var egreso_evolucion= $('#egreso_evolucion');
                  if (egreso_evolucion.length > 0 && egreso_evolucion.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_evolucion.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la evolución (EPICRISIS)" });
                      return false;
                  }
                  var egreso_tratamiento= $('#egreso_tratamiento');
                  if (egreso_tratamiento.length > 0 && egreso_tratamiento.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_tratamiento.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el tratamiento (EPICRISIS)" });
                      return false;
                  }
                  var egreso_procedimiento= $('#egreso_procedimiento');
                  if (egreso_procedimiento.length > 0 && egreso_procedimiento.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_procedimiento.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el procedimiento (EPICRISIS)" });
                      return false;
                  }
                  var egreso_complicacion= $('#egreso_complicacion');
                  if (egreso_complicacion.length > 0 && egreso_complicacion.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_complicacion.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la complicación (EPICRISIS)" });
                      return false;
                  }
                  var egreso_medidas_generales= $('#egreso_medidas_generales');
                  if (egreso_medidas_generales.length > 0 && egreso_medidas_generales.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_medidas_generales.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar las medidas generales (EPICRISIS)" });
                      return false;
                  }
                  var egreso_medicamentos_prescritos = $('#egreso_medicamentos_prescritos');
                  if (egreso_medicamentos_prescritos.length > 0 && egreso_medicamentos_prescritos.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_medicamentos_prescritos.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar los medicamentos prescritos (EPICRISIS)" });
                      return false;
                  }
                  var egreso_pronostico= $('#egreso_pronostico');  
                  if (egreso_pronostico.length > 0 && egreso_pronostico.val().trim() === "") {
                      lanzarError({content_panel: null, id_campo: '#'+egreso_pronostico.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el pronóstico (EPICRISIS)" });
                      return false;
                  }
              }
          }

         

          if($('#id_tipo_egreso'+ IdxIntervencion )=='1'){
              <?php if(($intervencion->id_tipo_atencion_origen==1 || $intervencion->id_tipo_atencion_origen==2) && !$intervencion->es_nuevo_admision_hospitalizacion){ ?>
              // Obtener valores de cada textarea por su id
              var egreso_resumen               = $('#egreso_resumen');
              var egreso_examen_clinico        = $('#egreso_examen_clinico');
              var egreso_examen_auxiliar       = $('#egreso_examen_auxiliar');
              var egreso_evolucion             = $('#egreso_evolucion');
              var egreso_tratamiento           = $('#egreso_tratamiento');
              var egreso_procedimiento         = $('#egreso_procedimiento');
              var egreso_complicacion          = $('#egreso_complicacion');
              var egreso_medidas_generales     = $('#egreso_medidas_generales');
              var egreso_medicamentos_prescritos = $('#egreso_medicamentos_prescritos');
              var egreso_pronostico            = $('#egreso_pronostico');

              // Validaciones
              if (egreso_resumen.length > 0 && egreso_resumen.val().trim() === "") { 
                  lanzarError({content_panel: null, id_campo: '#'+egreso_resumen.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el resumen de egreso (EPICRISIS)" });
                  return false;
              }
              if (egreso_examen_clinico.length > 0 && egreso_examen_clinico.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_examen_clinico.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen clínico (EPICRISIS)" });
                  return false;
              }
              if (egreso_examen_auxiliar.length > 0 && egreso_examen_auxiliar.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_examen_auxiliar.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen auxiliar (EPICRISIS)" });
                  return false;
              }
              if (egreso_evolucion.length > 0 && egreso_evolucion.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_evolucion.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la evolución (EPICRISIS)" });
                  return false;
              }
              if (egreso_tratamiento.length > 0 && egreso_tratamiento.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_tratamiento.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el tratamiento (EPICRISIS)" });
                  return false;
              }
              if (egreso_procedimiento.length > 0 && egreso_procedimiento.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_procedimiento.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el procedimiento (EPICRISIS)" });
                  return false;
              }
              if (egreso_complicacion.length > 0 && egreso_complicacion.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_complicacion.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la complicación (EPICRISIS)" });
                  return false;
              }
              if (egreso_medidas_generales.length > 0 && egreso_medidas_generales.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_medidas_generales.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar las medidas generales (EPICRISIS)" });
                  return false;
              }
              if (egreso_medicamentos_prescritos.length > 0 && egreso_medicamentos_prescritos.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_medicamentos_prescritos.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar los medicamentos prescritos (EPICRISIS)" });
                  return false;
              }
              if (egreso_pronostico.length > 0 && egreso_pronostico.val().trim() === "") {
                  lanzarError({content_panel: null, id_campo: '#'+egreso_pronostico.attr("id"), nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el pronóstico (EPICRISIS)" });
                  return false;
              }
            <?php }?>
          }

          let inicio = document.getElementById('hora_inicio_operatorio2');
          let fin     = document.getElementById('hora_fin_operatorio2');

          let inicioAlt = document.getElementById('cirugia_inicio3');
          let finAlt    = document.getElementById('cirugia_termino3');

          let horaInicio = null;
          let horaFin = null; 
          /* Detectar qué campos existen */
          if (inicio && fin) {
              horaInicio = inicio.value;    horaFin = fin.value;
          } else if (inicioAlt && finAlt) {
              horaInicio = inicioAlt.value;  horaFin = finAlt.value;
          }
 

          if (  horaInicio &&   horaFin &&    horaInicio !== '00:00' &&    horaFin !== '00:00' ) {
              if (horaFin <= horaInicio) {
                  msg += "La hora de término debe ser mayor que la hora de inicio.<br>";
                  lanzarError({content_panel: null, id_campo: '#hora_fin_operatorio2', nombre: 'SECCIÓN DE DESTINO', msje: "La hora de término debe ser mayor que la hora de inicio." });
                  return false;
              }
          } 
 
 
          var formData = $('#frmAtencionQuirurgicaAdicional').serialize();
          formData += '&' + $.param({ id_farmacia_receta_postqx: $('#id_farmacia_receta_postqx').val()});
          Swal.fire({
              title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Guardando datos</span></div>",
              html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
              showCancelButton: false,
              showConfirmButton: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              onBeforeOpen: () => {
                  Swal.showLoading();
              },
          });
          $.ajax({
              url: $('#frmAtencionQuirurgicaAdicional').attr('action'),
              method: 'POST',
              data: formData,
              success: function(response) {
                    Swal.close();
                    $('input, select, textarea').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    if(response.admsionado_hospitalizacion){
                          Swal.fire({
                              icon: 'info',  title: 'Información', text: response.msg,
                              timer: 3000,timerProgressBar: true,showConfirmButton: false,
                                  didClose: () => {
                                    $('#id_hospitalizacion').val(response.id_hospitalizacion);
                                    $('#addDestino' + $("#id_intervencion").val() ).prop("disabled", false);
                                    $('#addDestino' + $("#id_intervencion").val()).attr("aria-pressed", "true");
                                    $('#addDestino' + $("#id_intervencion").val()).addClass("active");
                                    $("#fsDestino" + $("#id_intervencion").val()).show();
                                    $('#registrar_destino').val(1);
                                  }
                          });
                    }else{
                          if (response.success) {
                                Swal.fire({
                                    icon: 'success',   title: 'Éxito',   text: 'Los datos se han guardado correctamente',
                                    timer: 3000,  timerProgressBar: true,  showConfirmButton: false,
                                    didClose: () => {
                                      $('#modal-detalle-atencion-quirurgica').modal('hide');
                                      window.location.href = '/cq_lista_pendiente';
                                    }
                                });
                          } else {
                                if (!response.destino) {
                                    Swal.fire({ icon: 'warning', title: 'Error de validación', text: response.msg  });
                                }else{
                                    console.log(response); 
                                    Swal.fire({ icon: 'error',  title: 'Error de validación',  text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.' });
                                }
                          }
                    }
              },
              error: function(xhr) {
                  console.log('Ocurrió un error en la solicitud AJAX');
                  Swal.close();
                  Swal.fire({   icon: 'error',  title: 'Error',   text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'  });
              }
          }); 
  
  
    });
  });
 


  function updateSelectElements() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll("#frmAtencionQuirurgicaAdicional .select-diagnostico2");

    selectElements.forEach((selectElement) => {
      // Almacenar el valor actualmente seleccionado
      let currentValue = selectElement.value;

      // Eliminar las opciones actuales
      selectElement.innerHTML = "";


      // Agregar las opciones basadas en listIdDiagnosticoObjetos
      listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement("option");
        option.value = item.id;
        option.textContent = item.nombre;
        selectElement.appendChild(option);
      });

      // Verificar si el valor seleccionado aún está disponible en las opciones
      let optionExists = Array.from(selectElement.options).some(
        (option) => option.value === currentValue
      );

      // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
      if (!optionExists) {
        selectElement.value = "";
      } else {
        // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
        selectElement.value = currentValue;
        console.log()
      }
    });


  }

  function addNewArray(id_diagnostico) {
    if(listIdDiagnosticoObjetos.findIndex( e  => e.id == id_diagnostico) ==-1 ){
      listIdDiagnostico.push(id_diagnostico);// solo es para validar si existen datos

      if (listIdDiagnostico.length > 0) {
        let codigo = JSON.stringify(id_diagnostico);
        // Realizar la petición AJAX usando el código id_diagnostico
        $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "/obtenerdatosDiagnosticos",
                "data":{
                  codigo : codigo
                        },
                "success": function (result) {
                  listIdDiagnosticoObjetos.push(result.objeto);
                      console.log(JSON.stringify(listIdDiagnosticoObjetos));
                      updateSelectElements();
                },
                "error": function (msg, textStatus, errorThrown) {
                    //location.href="login";
                }
        });
      }
    }
  }

  $('#addDiagnostico2').on('click',function(){
    addDiagnostico2();
  });
  $('#addProcedimiento2').on('click',function(){
    addProcedimiento2();
  });

  // function getDiagnosticosDePrestacion() {
  //   $('.nuevo_id_diagnostico').each(function() {
  //     let value = $(this).val();
  //     addNewArray(value)
  //   });
  // }

  function addDiagnostico2(){
    var newRow = "";
    var ind = $('#tblDiagnosticoDetalle tbody tr').length;

    if(ind > 0){
      $('.id_diagnostico').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });

      fila_medicamentos=$('#tblDiagnosticoDetalle tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];

    }

    

    var item_tipo_diagnostico = '';
    item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
    item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';

    let item_categoria_diagnostico = '';
    item_categoria_diagnostico+='<option value="0">--Seleccionar--</option>';
    item_categoria_diagnostico+='<option value="1">PRINCIPAL</option>';
    item_categoria_diagnostico+='<option value="2">SECUNDARIO</option>';

    newRow += '<tr><td></td>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind +'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow +='<td><select tabindex="45" class="form-control id_categoria_diagnostico" id="cmbCategoriaDiagnostico'+ind+'" name="id_categoria_diagnostico[]" style="margin:0 0 0 20px;width:150px">'+item_categoria_diagnostico+'</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblDiagnosticoDetalle tbody').append(newRow);
    var sexo	= "{{ isset($prestacion->sexo) ? $prestacion->sexo : '' }}";
    if(sexo=='M'){
      genero=1;
    }
    else{
      if(sexo=='F'){
        genero=2;
      }
    }

    $("#nombre_diagnostico"+ind).autocomplete({
      minLength: 1,
      delay : 400,
      source: function(request, response) {
        $.ajax({
          //url: '../obtener_diagnostico/' + request.term,
          url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
          dataType: "json",
          success: function(data)  {
            let listaDiagnosticoSeleccionado = $(".nuevo_id_diagnostico, .id_diagnostico").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          }
        })

      },select:  function(e, ui) {
        var id_diagnostico = ui.item.id;
        $("#id_diagnostico"+ind).val(id_diagnostico);
        $("#nombre_diagnostico"+ind).prop("readonly",true);
      }
    });

    $("#nombre_diagnostico"+ind).on('blur', function() {
      var id_diagnostico = $("#id_diagnostico"+ind).val();
      if(id_diagnostico == ""){
        // bootbox.alert("Falta ingresar el diagnostico en esta fila");
        // $("#nombre_diagnostico"+ind).val("");
        // return false;
        bootbox.alert({
          message: `<div style="max-height: 300px; overflow-y: auto;">Falta ingresar el diagnostico en esta fila</div>`,
          size: 'large',
          backdrop: true,
          callback: function () {
            $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
            $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
          }
        });
      }else{
        addNewArray(id_diagnostico);
      }
    });
  }

  function addProcedimiento2(){
    var newRow = "";
    var ind = $('#tblProcedimientoDetalle tbody tr').length;

    if(ind > 0){
      $('.id_procedimiento').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });

      fila_medicamentos=$('#tblProcedimientoDetalle tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];

    }

      newRow += '<tr>';
      newRow +='<td></td>';
      newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="detalle_nombre_procedimiento' +
        ind + '" class="input-sm id_procedimiento" style="margin-left:4px; width:100%; text-align:left" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
        '" name="id_procedimiento[]" value="" data-id='+ind+'></td>';
      newRow +=
        '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1"></td>';
      newRow += '<td><select class="form-control select-diagnostico2"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
      newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimientoDetalle tbody').append(newRow);
      const selectElement = document.getElementById('miSelect' + ind);
      let defaultOption = document.createElement('option');
    //si entra la primera vez
      var i = 0;
    listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        if (i==0){
          option.setAttribute("selected", "selected");
          i++;
        }
        selectElement.appendChild(option);
    });
      var nivel     = "{{ isset($nivel) ? $nivel : '' }}";
      var sexo	= "{{ isset($prestacion->sexo) ? $prestacion->sexo : '' }}";
      if(sexo=='M')
        genero=1;
      else
        genero=2;


      $("#detalle_nombre_procedimiento"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          $.ajax({
    //				url: '../obtener_procedimiento/' + request.term,
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data)  {//id_procedimiento
              let listaProcedimientoSelecionado = $(".id_procedimiento, .id_diagnostico_general").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
            }
          })

        },select:  function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento"+ind).val(id_procedimiento);
          $("#detalle_nombre_procedimiento"+ind).prop("readonly",true);
        }
      });

      $("#detalle_nombre_procedimiento"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento"+ind).val();
        if(id_procedimiento == ""){
          // bootbox.alert("Falta ingresar el procedimiento en esta fila");
          // $("#nombre_procedimiento"+ind).val("");
          // return false;
          bootbox.alert({
            message: `<div style="max-height: 300px; overflow-y: auto;">Falta ingresar el procedimiento en esta fila</div>`,
            size: 'large',
            backdrop: true,
            callback: function () {
              $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
              $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
            }
          });

        }
      });
      $("#procedimiento_cantidad"+ind).on('blur', function() {
        var procedimiento_cantidad = $("#procedimiento_cantidad"+ind).val();
        if(procedimiento_cantidad == ""){
          // bootbox.alert("Falta ingresar la cantidad en este procedimiento");
          // $("#procedimiento_cantidad"+ind).val("");
          // return false;
          bootbox.alert({
            message: `<div style="max-height: 300px; overflow-y: auto;">Falta ingresar la cantidad en este procedimiento</div>`,
            size: 'large',
            backdrop: true,
            callback: function () {
              $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
              $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
            }
          });
        }
      });
  }

  $('#tblDiagnosticoDetalle tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    var inputVal = obj.parent().siblings().find('input').val();
    let posicion =inputVal.indexOf("-");
    let codigo = inputVal.substring(0,posicion);
    eliminarDiagnosticosarr(codigo);
    console.log("el codigo es" + codigo);
    obj.parent().parent().remove();
  });

  $('#tblProcedimientoDetalle tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    obj.parent().parent().remove();
  });
  function eliminarDiagnosticosarr(codigon){
    let codigo2 =codigon.trim()

    let listIdDiagnosticoObjetos2 =[];
    for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
      const element = listIdDiagnosticoObjetos[index];
      if(element.codigo !== codigo2){
        listIdDiagnosticoObjetos2.push(element)
      }

    }
    listIdDiagnosticoObjetos=[];
    listIdDiagnosticoObjetos =listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);



    procedimientosSeleccionados= procedimientosSeleccionados.map((item) => {
      if (item.codigo === codigo2) {
        return { ...item, id: "" };
      } else {
        return item;
      }
    });

    console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionados))
    console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetos));
    updateSelectElements();

  }

  $(document).on("click", ".EditarFilaProducto", function() {
    let row = $(this).closest("tr");

    row.find("input, select").prop("disabled", false);

    row.find("input[name='accionRowProducto[]']").val("edit");

    row.find("input[type='hidden'][name='cantidad_prescrita_editar[]']").remove();
    row.find("input[type='hidden'][name='posologia_editar[]']").remove();
    row.find("input[type='hidden'][name='id_via_administracion_editar[]']").remove();
    row.find("input[type='hidden'][name='tiempo_periodo_editar[]']").remove();


    $(this).closest('tr').css('background-color', '#87c787');
    EditarReceta();
  });
  function restaurarColoresMedicamentos(id_receta){
     $("input[name='accionRowProducto[]']").val("");

    // Deshabilita todos los inputs de tipo 'text' en la tabla
    $("#tblMedicamentosRecetaExistente"+id_receta+" .medicamentos_registrados").find("input[type='text']").prop("disabled", true);

    // Deshabilita todos los selects en la tabla
    $("#tblMedicamentosRecetaExistente"+id_receta+" .medicamentos_registrados").find("select").prop("disabled", true);

   // Recorre cada fila de la tabla
    $('#tblMedicamentosRecetaExistente' + id_receta + ' tr').each(function () {
        const tdCamposHidden = $(this).find('td.campos_hidden');
        // Verifica si ya existen los inputs antes de agregar nuevos
        if (tdCamposHidden.length > 0 && tdCamposHidden.find("input").length === 0) {
            tdCamposHidden.append(`<input type="hidden" name="accionRowProducto[]" value="">`);
            tdCamposHidden.append(`<input type="hidden" name="cantidad_prescrita_editar[]" value="">`);
            tdCamposHidden.append(`<input type="hidden" name="posologia_editar[]" value="">`);
            tdCamposHidden.append(`<input type="hidden" name="id_via_administracion_editar[]" value="">`);
            tdCamposHidden.append(`<input type="hidden" name="tiempo_periodo_editar[]" value="">`);
        }
    });

    $('#tblMedicamentosRecetaExistente'+id_receta+' tr').css('background-color', '');
  }

  $('.AnularFilaProductos').on('click', function() {
    let row = $(this).closest("tr");

    // row.find("input, select").prop("disabled", true);
    row.find("input:not([type='hidden']), select").prop("disabled", true);

    row.find("input[name='accionRowProducto[]']").val("delete");

    row.find("input[type='hidden'][name='cantidad_prescrita_editar[]']").remove();
    row.find("input[type='hidden'][name='posologia_editar[]']").remove();
    row.find("input[type='hidden'][name='id_via_administracion_editar[]']").remove();
    row.find("input[type='hidden'][name='tiempo_periodo_editar[]']").remove();

    $(this).closest('tr').css('background-color', '#f8d7da !important');
    EliminarReceta();
  });
  function EditarReceta() {
    $("input[name='accionRowProducto[]']").each(function () {
          console.log('Valor inicial de accionRowProducto: ' + $(this).val());
          $("#divGuardar").show();
          $("#divFirmar").hide();
      });
  }

  function EliminarReceta() {
    $("input[name='accionRowProducto[]']").each(function () {
          console.log('Valor inicial de accionRowProducto: ' + $(this).val());
          $("#divGuardar").show();
          $("#divFirmar").hide();
      });
  }
  function getProductoTemp1(){
      var id_ipress 		= $('#id_ipress').val();
      var id_consultorio 	= '{{$id_servicio_encargado}}';//$('#id_servicio_encargado').val()
      //var tipo_receta		= $('#tipo_receta').val();
      //var id_tiporeceta 	= $('#id_tiporeceta').val();
      var id_tiporeceta 	= $('#frmAtencionQuirurgicaAdicional #id_tipo_atencion').val();
      var id_farmacia     = $('#frmAtencionQuirurgicaAdicional #id_farmacia_receta1').val();
      let idfarmacia=id_farmacia?id_farmacia:$('#frmAtencionQuirurgicaAdicional #id_farmacia_receta2').val();
      $('#id_farmacia').val(idfarmacia);
      //console.log(id_farmacia);
      var nivel     = $('#id_nivel').val();
      var x  = $('#nro_interconsultas').val();
      var dni = $('#dni_beneficiario_final').val();
      var interconsulta = $('#interconsulta').val();

      let puede_atennder_servicios_cq = <?= $puede_atennder_servicios_cq ? 'true' : 'false' ?>;

      if(puede_atennder_servicios_cq && id_ipress && idfarmacia && id_consultorio){
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los productos medicos de la farmacia</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
            Swal.showLoading();
            }
        });
        $.ajax({
            url: '/obtener_productos_atencion_quirurgica/' + id_ipress + '/' + idfarmacia + '/' + id_consultorio,
            success: function(result){
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result.productos_sin_paquetizacion).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTempAdicionalAtencion{{$id_intervencion}}').html(newOption);
              $('#cmbMedicamentoTemp{{$id_intervencion}}').html(newOption);
            },
            complete: function () {
              Swal.close();
            },
        });
      }
  }
  function getProductoTemp2(){
      var id_ipress 		= $('#id_ipress').val();
      var id_consultorio 	= $('#id_servicio_encargado').val()
      //var tipo_receta		= $('#tipo_receta').val();
      //var id_tiporeceta 	= $('#id_tiporeceta').val();
      var id_tiporeceta 	= $('#frmAtencionQuirurgicaAdicional #id_tipo_atencion').val();
      var id_farmacia     = $('#frmAtencionQuirurgicaAdicional #id_farmacia_receta2').val();
      $('#id_farmacia').val(id_farmacia);
      //console.log(id_farmacia);
      var nivel     = $('#id_nivel').val();
      var x  = $('#nro_interconsultas').val();
      var dni = $('#dni_beneficiario_final').val();
      var interconsulta = $('#interconsulta').val();

      let puede_atennder_servicios_cq = <?= $puede_atennder_servicios_cq ? 'true' : 'false' ?>;

      if(puede_atennder_servicios_cq){
        Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando los productos medicos de la farmacia</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                Swal.showLoading();
                }
        });
        $.ajax({
            url: '/obtener_productos_atencion_quirurgica/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
            success: function(result){
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result.productos_sin_paquetizacion).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              //$('#cmbMedicamentoTempAdicionalAtencion').html(newOption);
              $('#cmbMedicamentoTempAdicionalAtencion{{$id_intervencion}}').html(newOption);
            },
            complete: function () {
                  Swal.close();
                },
        });
      }
  }
  function getProductos(){
      var id_ipress 		= $('#id_ipress').val();
      var id_consultorio 	= $('#id_servicio_encargado').val()
      var id_tiporeceta 	= $('#frmAtencionQuirurgicaAdicional #id_tipo_atencion').val();
      var id_farmacia     = $('#frmAtencionQuirurgicaAdicional #id_farmacia_receta2').val();
      $('#id_farmacia').val(id_farmacia);
      var nivel     = $('#id_nivel').val();
      var x  = $('#nro_interconsultas').val();
      var dni = $('#dni_beneficiario_final').val();
      var interconsulta = $('#interconsulta').val();

      let puede_atennder_servicios_cq = <?= $puede_atennder_servicios_cq ? 'true' : 'false' ?>;

      if(puede_atennder_servicios_cq){
        $.ajax({
            url: '/obtener_productos_atencion_quirurgica/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
            success: function(result){
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result.productos_sin_paquetizacion).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTemp{{$id_intervencion}}').html(newOption);
            }
        });
      }
  }

  $(document).ready(function() {
    //getProductoTemp1();
    $.ajax({
      url: '/get_procedimientos_examen_patologia',
      type: "GET",
      dataType: "json",
      success: function(result){
        let newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"' >"+oo.codigo+" - "+oo.descripcion+"</option>";
        });                 
        $('#cmbProcedimientoExamenPatologiaTemp2').html(newOption);
      }
    });
    var recetas = {!! json_encode(isset($receta) ? $receta : []) !!};
    if (Array.isArray(recetas) && recetas.length > 0) {
        getProductos();
    }
    //getProductoTemp2();
  });

  //PRODUCTOS EXISTENTES
  var filasmedicamentos = 0;
  //$('#addProductoRecetaExistente').off('click').on('click', function () {
    //AddProductoRecetaExistente(id_receta);
  //});
  let tomSelectInstancesProducto2={};
  function agregarProductoRecetaExistente(id_receta){
    var newRow = "";
    var ind = filasmedicamentos;
    var tabindex = 1;
    var nuevalperiodo = "";
    if(ind > 0){
        $('.cmbMedicamento'+id_receta).each(function(){
            var ind_tmp = $(this).attr("ind");
            if(ind_tmp >= ind){
              ind=Number(ind_tmp)+1;
              tabindex+=3;
            }
        });

        fila_medicamentos=$('#tblMedicamentosRecetaExistente'+id_receta+' tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
            fila_medicamento=fila_medicamentos[idval];
            var valante = $(fila_medicamento).find('#nro_med_entregados').val();
            if(valante == ""){
                bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                return false;
            }
        <?php //endif;?>

    }
    console.log('Agregando fila con índice:', ind);
    var item_producto = "";
    $('#cmbMedicamentoTemp{{$id_intervencion}} option').each(function() {
        item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
        "</option>"
    });

    var item_via_administracion = '';
    item_via_administracion+='<option value="0">--Seleccionar--</option>';
    item_via_administracion+='<option value="1">Oral</option>';
    item_via_administracion+='<option value="2">Sublingual</option>';
    item_via_administracion+='<option value="3">Rectal</option>';
    item_via_administracion+='<option value="4">Intravenosa</option>';
    item_via_administracion+='<option value="5">Intramuscular</option>';
    item_via_administracion+='<option value="6">Subcutánea</option>';
    item_via_administracion+='<option value="7">Dérmica</option>';
    item_via_administracion+='<option value="8">Nasal</option>';
    item_via_administracion+='<option value="9">Oftalmológica</option>';
    item_via_administracion+='<option value="10">Inhalatoria</option>';
    item_via_administracion+='<option value="11">Epidural</option>';
    item_via_administracion+='<option value="12">Intratecal</option>';
    item_via_administracion+='<option value="13">Vaginal</option>';
    item_via_administracion+='<option value="14">Intraarticular</option>';
    item_via_administracion+='<option value="15">No Aplica</option>';
    item_via_administracion+='<option value="16">Otros</option>';

    newRow +='<tr class="nuevos_productos_existentes">';
    newRow +='<td colspan="2" data-label="Producto farmacéutico o dispositivo médico"><select class="select-tom cmbMedicamento' + id_receta + ' productos-responsive" id="cmbMedicamento' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 100%">' + item_producto + '</select></td>';

    newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
    newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
    newRow +='<td data-label="Stock Farmacia"><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm productos-responsive" style="margin-left:1px; width: 100%;" readonly=""/></td>';
    newRow +='<td></td>';

    newRow +='<td data-label="Tiempo(Dias)"><input onkeypress="return soloNumerosMenosCeroDetalle(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm productos-responsive" style="margin-left:1px; width: 100%;" value="' + nuevalperiodo +'"/></td>';
    newRow += '<td data-label="Posologia"><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
    ind + '" class="nro_solicitado posologia input-sm productos-responsive" style="margin-left:1px; width: 100%;" value=""/>';
    newRow +='<td data-label="Via Adm."><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion productos-responsive" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin-left:1px;width: 100%;">'+item_via_administracion+'</select></td>';

    newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
    newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td data-label="Cant. Pres."><input onKeyPress="return soloNumerosMenosCeroDetalle(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm productos-responsive" style="margin-left:1px; width:100%;" /></td>';
    newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico2"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';
    newRow +='<td data-label="Eliminar"></td>';
    newRow +='<td data-label="Eliminar"><button type="button" title="Eliminar" class="btn btn-danger deleteFilaExistente btn-xs"  data-id-receta="'+id_receta+'" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow +='</tr>';
    $('#frmAtencionQuirurgicaAdicional #tblMedicamentosRecetaExistente'+id_receta+' tbody').append(newRow);
    console.log('add producto receta existente');


    // $("#cmbMedicamento" + ind).select2({max_selected_options: 4});
    const select = new TomSelect("#cmbMedicamento" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstancesProducto2["cmbMedicamento" + ind] = select;
    select.focus();

    const selectElementProd = document.getElementById('miSelectProd' + ind);
    listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        selectElementProd.appendChild(option);
    });

    $("#abrir-modal-" + ind).click(function(e) {
        e.stopPropagation();
        abrirModal(ind);
    });

    $("#nro_med_solictados"+ind).keypress(function(e){
        if(e.which == 13) {
            <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                AddProducto();
            <?php //else:?>
                validproductoSeleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
            <?php //endif;?>
        }
    });

    $("#add_producto"+ind).click(function(e){
        AddProducto();
        validproductoSeleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    function validproductoSeleccionado(id_farmacia, btn) {
        var dni_beneficiario = $('#dni_beneficiario_final').val();
        var nro_dias = 30;
        var objTr = $(btn).closest("tr");
        var id_producto = objTr.find(".cmbMedicamento").val();
        $.ajax({
            url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
            success: function (result) {
            var msg   = "";
            var n     = 0;
            var flag  = true;
                for(var key in result) {
                    if(n == 0){
                        msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
                        msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                                    msg += "<thead>";
                                        msg += "<tr>";
                        msg += "<th>Farmacia</th>";
                        msg += "<th>Nro. Receta</th>";
                        msg += "<th>Fecha Expedicion</th>";
                        msg += "<th>Producto</th>";
                        msg += "<th>Tiempo(Dias)</th>";
                        msg += "<th>Dias Transcurridos</th>";
                        msg += "<th>Dias Pendientes</th>";
                        msg += "<th>Cantidad Prescrita</th>";
                                        msg += "</tr>";
                                    msg += "</thead>";
                        msg += "<tbody>";
                    }

                    msg += "<tr><td>"+(result[key].farmacia)+"</td>";
                    msg += "<td>"+(result[key].nro_receta)+"</td>";
                    msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
                    msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
                    msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
                    msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
                    msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
                    msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                    n++;
                }
                if(msg != "") {
                    bootbox.confirm({
                        title: "Alerta",
                        message: msg,
                        buttons: {
                            cancel: {
                                label: '<i class="fa fa-times"></i> Cancelar'
                            },
                            confirm: {
                                label: '<i class="fa fa-check"></i> Continuar'
                            }
                        },
                        callback: function (result) {
                            if(result==true){
                            verlotes(id_farmacia, btn);
                            }
                        }
                    });
                }
            }
        });
    }

    $("#cmbMedicamento"+ind).on("change", function (e) {
        var flagx = 0;
        let valida=true;
        cmb = $(this);
        id_producto = $("#cmbMedicamento"+ind).val();
        id_user=<?php echo Auth::user()->id;?>;

        $('.cmbMedicamento'+id_receta).each(function(){
            var ind_tmp = $(this).val();
            if($(this).val() == id_producto)flagx++;
        });

        if(flagx > 1 && id_producto !=''){
            // bootbox.alert("El producto farmaceutico ya ha sido ingresado");
            // $("#cmbMedicamento"+ind).val("").trigger("change");
            // valida = false;
            bootbox.alert({
            message: `<div style="max-height: 300px; overflow-y: auto;">El producto farmaceutico ya ha sido ingresado</div>`,
            size: 'large',
            backdrop: true,
            callback: function () {
              $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
              $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
              //$("#cmbMedicamento"+ind).val("").trigger("change");
              const currentTS = tomSelectInstancesProducto2["cmbMedicamento" + ind];
              if (currentTS) {
                currentTS.clear();
                currentTS.setTextboxValue('');
                currentTS.blur();
              }
            }
          });
        }
        if(id_producto){
          var id_farmacia_receta = $('#id_farmacia').val();

          option = {
            url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
            type: 'GET',
            dataType: 'json',
            data: {}
          };
          $.ajax(option).done(function (data) {

              var cantidad = data.cantidad;
              var cantidadEstablecimiento = data.cantidadEstablecimiento;
              var cantidadAlmacen = data.cantidadAlmacen;
              $(cmb).closest("tr").find(".limpia_text").val("");
              //$(cmb).closest("tr").find("#nro_stocks").val(data);
              $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
              $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
              $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
              <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
              $(cmb).closest("tr").find(".tiempo_periodo").focus();
              <?php //endif;?>
              $(cmb).closest("tr").find("#nro_med_solictados_atencion").val("");
              $(cmb).closest("tr").find("#nro_med_entregados").val("");
              $(cmb).closest("tr").find("#lotes_lote").val("");
              $(cmb).closest("tr").find("#lotes_cantidad").val("");
              $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
              $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
          });
        }
    });
    filasmedicamentos++;
  }
  //FIN PRODUCTOS EXISTENTES

  //////////////////PRODUCTOS ADICIONALES//////////////////
  var filasmedicamentosAdicional = 0;
  $('#addProductoNuevo').off('click').on('click', function () {
    AddProductoAdicionalAtencion();
  });
  let tomSelectInstancesProductoAdicional={};
  function AddProductoAdicionalAtencion(){
    var newRow = "";
    var ind = filasmedicamentosAdicional;
    var tabindex = 1;
    var nuevalperiodo = "";
    if(ind > 0){
        $('.cmbMedicamentoAdicionalAtencion').each(function(){
            var ind_tmp = $(this).attr("ind");
            if(ind_tmp >= ind){
              ind=Number(ind_tmp)+1;
              tabindex+=3;
            }
        });

        fila_medicamentos=$('#tblMedicamentosAdicionalAtencion tbody').children('tr');
        rowIndex=$('#hidden_adicional_rowIndex').val();
        var idval = ind - 1;
        <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
            fila_medicamento=fila_medicamentos[idval];
            var valante = $(fila_medicamento).find('#nro_med_adicional_entregados').val();
            if(valante == ""){
                bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                return false;
            }
        <?php //endif;?>

    }
    console.log('Agregando fila con índice:', ind);
    var item_producto2 = "";
    $('#cmbMedicamentoTempAdicionalAtencion{{$id_intervencion}} option').each(function() {
        item_producto2 += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
        "</option>"
    });

    // var item_diagnostico_option = "";
    // $('#diagnosticosAdicionalTempAnestesia option').each(function() {
    //     item_diagnostico_option += "<option value=" + $(this).val() + ">" + $(this).html() +
    //     "</option>"
    // });


    var item_via_administracion = '';
    item_via_administracion+='<option value="0">--Seleccionar--</option>';
    item_via_administracion+='<option value="1">Oral</option>';
    item_via_administracion+='<option value="2">Sublingual</option>';
    item_via_administracion+='<option value="3">Rectal</option>';
    item_via_administracion+='<option value="4">Intravenosa</option>';
    item_via_administracion+='<option value="5">Intramuscular</option>';
    item_via_administracion+='<option value="6">Subcutánea</option>';
    item_via_administracion+='<option value="7">Dérmica</option>';
    item_via_administracion+='<option value="8">Nasal</option>';
    item_via_administracion+='<option value="9">Oftalmológica</option>';
    item_via_administracion+='<option value="10">Inhalatoria</option>';
    item_via_administracion+='<option value="11">Epidural</option>';
    item_via_administracion+='<option value="12">Intratecal</option>';
    item_via_administracion+='<option value="13">Vaginal</option>';
    item_via_administracion+='<option value="14">Intraarticular</option>';
    item_via_administracion+='<option value="15">No Aplica</option>';
    item_via_administracion+='<option value="16">Otros</option>';

    newRow +='<tr class="producto_adicional">';
    newRow +='<td data-label="Producto farmacéutico o dispositivo médico"><select class="select-tom cmbMedicamentoAdicionalAtencion productos-responsive" id="cmbMedicamentoAdicionalAtencion' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto_adicional[]" style="width: 100%">' + item_producto2 + '</select></td>';

    newRow +='<input type="hidden" name="nro_stocks_almacen_adicional[]" id="nro_stocks_almacen_adicional" value=""/>';
    newRow +='<input type="hidden" name="nro_stocks_establecimiento_adicional[]" id="nro_stocks_establecimiento_adicional" value=""/>';
    newRow +='<td data-label="Stock Farmacia"><input type="text" name="nro_stocks_adicional[]" required id="nro_stocks_adicional" class="limpia_text nro_solicitado input-sm productos-responsive" style="margin-left:1px; width: 100%;" readonly=""/></td>';

    newRow +='<td data-label="Tiempo(Dias)"><input onkeypress="return soloNumerosMenosCeroDetalle(event)" type="text" name="tiempo_periodo_adicional[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo_adicional' + ind +'" class="nro_solicitado tiempo_periodo_adicional input-sm productos-responsive" style="margin-left:1px; width: 100%;" value="' + nuevalperiodo +'"/></td>';
    newRow += '<td data-label="Posologia"><input type="text" name="posologiaAdicional[]" tabindex="' + (tabindex + 2) + '" required="" id="posologiaAdicional' +
    ind + '" class="nro_solicitado posologiaAdicional input-sm productos-responsive" style="margin-left:1px; width: 100%;" value=""/>';
    newRow +='<td data-label="Via Adm."><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_adicional productos-responsive" id="cmbTipoViaAdministracionAdicional'+ind+'" name="id_via_administracion_adicional[]" style="margin-left:1px;width: 100%;">'+item_via_administracion+'</select></td>';

    newRow +='<input type="hidden" name="lotes_lote_adicional[]" id="lotes_lote_adicional" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario_adicional[]" id="lotes_registro_sanitario_adicional" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento_adicional[]" id="lotes_fecha_vencimiento_adicional" value=""/>';
    newRow +='<input type="hidden" name="lotes_precio_unitario_adicional[]" id="lotes_precio_unitario_adicional" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad_adicional[]" id="lotes_cantidad_adicional" value=""/></td>';
    newRow +='<td data-label="Cant. Pres."><input onKeyPress="return soloNumerosMenosCeroDetalle(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_atencion_adicional[]" required="" id="nro_med_solictados_atencion_adicional' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_atencion_adicional input-sm productos-responsive" style="margin-left:1px; width:100%;" /></td>';
    newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico2"  id="miSelectProdAdicional' + ind + '" name="miSelectProd[]"></select></td>';
    newRow +='<td data-label="Eliminar"><button type="button" title="Eliminar" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow +='</tr>';
    $('#frmAtencionQuirurgicaAdicional #tblMedicamentosAdicionalAtencion tbody').append(newRow);
    // $("#cmbMedicamentoAdicionalAtencion" + ind).select2({max_selected_options: 4});
    const selectAdicional = new TomSelect("#cmbMedicamentoAdicionalAtencion" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstancesProductoAdicional["cmbMedicamentoAdicionalAtencion" + ind] = selectAdicional;
    selectAdicional.focus();

    const selectElementProd = document.getElementById('miSelectProdAdicional' + ind);
    listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        selectElementProd.appendChild(option);
    });
    //PEGAR AQUI
    $("#abrir-modal-" + ind).click(function(e) {
        e.stopPropagation();
        abrirModal(ind);
    });

    $("#nro_med_solictados_atencion_adicional"+ind).keypress(function(e){
        if(e.which == 13) {
            <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                AddProductoAdicionalAtencion();
            <?php //else:?>
                validproductoAdicionalseleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
            <?php //endif;?>
        }
    });

    $("#add_producto_atencion"+ind).click(function(e){
        AddProductoAdicionalAtencion();
        validproductoAdicionalseleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    function validproductoAdicionalseleccionado(id_farmacia, btn) {//Jose
      console.log('Entro en función de validproductoAdicionalseleccionado');

        var dni_beneficiario = $('#dni_beneficiario_final').val();
        var nro_dias = 30;
        var objTr = $(btn).closest("tr");
        var id_producto = objTr.find(".cmbMedicamentoAdicionalAtencion").val();
        $.ajax({
            url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
            success: function (result) {
            var msg   = "";
            var n     = 0;
            var flag  = true;
                for(var key in result) {
                    if(n == 0){
                        msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";
                        msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                                    msg += "<thead>";
                                        msg += "<tr>";
                        msg += "<th>Farmacia</th>";
                        msg += "<th>Nro. Receta</th>";
                        msg += "<th>Fecha Expedicion</th>";
                        msg += "<th>Producto</th>";
                        msg += "<th>Tiempo(Dias)</th>";
                        msg += "<th>Dias Transcurridos</th>";
                        msg += "<th>Dias Pendientes</th>";
                        msg += "<th>Cantidad Prescrita</th>";
                                        msg += "</tr>";
                                    msg += "</thead>";
                        msg += "<tbody>";
                    }

                    msg += "<tr><td>"+(result[key].farmacia)+"</td>";
                    msg += "<td>"+(result[key].nro_receta)+"</td>";
                    msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
                    msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
                    msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
                    msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
                    msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
                    msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                    n++;
                }
                if(msg != "") {
                    bootbox.confirm({
                        title: "Alerta",
                        message: msg,
                        buttons: {
                            cancel: {
                                label: '<i class="fa fa-times"></i> Cancelar'
                            },
                            confirm: {
                                label: '<i class="fa fa-check"></i> Continuar'
                            }
                        },
                        callback: function (result) {
                            if(result==true){
                            verlotes(id_farmacia, btn);
                            }
                        }
                    });
                }
            }
        });
    }

    $("#cmbMedicamentoAdicionalAtencion"+ind).on("change", function (e) {
        var flagx = 0;
        let valida=true;
        cmb = $(this);
        id_producto = $("#cmbMedicamentoAdicionalAtencion"+ind).val();
        id_user=<?php echo Auth::user()->id;?>;

        $('.cmbMedicamentoAdicionalAtencion').each(function(){
            var ind_tmp = $(this).val();
            if($(this).val() == id_producto)flagx++;
        });

       if(flagx > 1 && id_producto !=''){
            bootbox.alert({
              message: `<div style="max-height: 300px; overflow-y: auto;">El producto farmaceutico ya ha sido ingresado</div>`,
              size: 'large',
              backdrop: true,
              callback: function () {
                $('body').addClass('modal-open');
                $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
                const currentTS = tomSelectInstancesProductoAdicional["cmbMedicamentoAdicionalAtencion" + ind];
                if (currentTS) {
                  currentTS.clear();
                  currentTS.setTextboxValue('');
                  currentTS.blur();
                }
              }
            });
        }
        if(id_producto && id_user){
          var id_farmacia_receta = $('#id_farmacia').val();

          option = {
            url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
            type: 'GET',
            dataType: 'json',
            data: {}
          };
          $.ajax(option).done(function (data) {

              var cantidad = data.cantidad;
              var cantidadEstablecimiento = data.cantidadEstablecimiento;
              var cantidadAlmacen = data.cantidadAlmacen;
              $(cmb).closest("tr").find(".limpia_text").val("");
              //$(cmb).closest("tr").find("#nro_stocks").val(data);
              $(cmb).closest("tr").find("#nro_stocks_adicional").val(cantidad);
              $(cmb).closest("tr").find("#nro_stocks_establecimiento_adicional").val(cantidadEstablecimiento);
              $(cmb).closest("tr").find("#nro_stocks_almacen_adicional").val(cantidadAlmacen);
              <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
              $(cmb).closest("tr").find(".tiempo_periodo_adicional").focus();
              <?php //endif;?>
              $(cmb).closest("tr").find("#nro_med_solictados_atencion_adicional").val("");
              $(cmb).closest("tr").find("#nro_med_entregados_adicional").val("");
              $(cmb).closest("tr").find("#lotes_lote_adicional").val("");
              $(cmb).closest("tr").find("#lotes_cantidad_adicional").val("");
              $(cmb).closest("tr").find("#lotes_registro_sanitario_adicional").val("");
              $(cmb).closest("tr").find("#lotes_fecha_vencimiento_adicional").val("");
          });
        }
    });
    filasmedicamentosAdicional++;
  }

  function getProducto2AndAddProducto() {
    AddProductoAdicionalAtencion();
  }
  //////////////////FIN PRODUCTOS ADICIONALES////////////////

  // $('#tblMedicamentosRecetaExistente tbody').on('click', 'button.deleteFilaExistente', function () {
  //   var obj = $(this);
  //   obj.parent().parent().remove();
  // });
  $(document).on('click', '.deleteFilaExistente', function () {
    const idReceta = $(this).data('id-receta');
    const fila = $(this).closest('tr');

    // Verificamos que la fila está dentro de la tabla correcta
    const tablaPadre = fila.closest('table');

    if (tablaPadre.attr('id') === 'tblMedicamentosRecetaExistente' + idReceta) {
        fila.remove();
    } else {
        console.warn('La fila no pertenece a la tabla esperada');
    }
  });

  $('#tblMedicamentosAdicionalAtencion tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    obj.parent().parent().remove();
  });


  //Registrar Destino
  $('#addDestino'+<?php echo $id_intervencion;?>).on('click', function () {
    let vIdIntervencion=$("#id_intervencion").val();
    var addDestino = $('#addDestino'+ vIdIntervencion).attr("aria-pressed");
    if(addDestino == "false"){
      $("#fsDestino"+vIdIntervencion).show();
      $('#registrar_destino').val(1);
    }else{
      $("#fsDestino"+ vIdIntervencion).hide();
      $('#registrar_destino').val(0);
      if($("#id_tipo_egreso"+ vIdIntervencion).val()=='2'){ //  HOSPITALIZACION
          var value_encargado =  $(`#destino_hospitalizacion${vIdIntervencion} #id_upss_encargado option[data-id_servicio_encargado="${$("#id_servicio_encargado").val()}"]`).val() || '';
          $(`#destino_hospitalizacion${vIdIntervencion}`).find("#id_upss_encargado")[0].tomselect.setValue(value_encargado);
          $("#alta_condicion_tra"+ vIdIntervencion).hide();
          $(`#destino_hospitalizacion${vIdIntervencion}`).find("#id_pabellon_destino")[0].tomselect.setValue('0');
          $(`#destino_hospitalizacion${vIdIntervencion}`).find("#id_area_destino")[0].tomselect.setValue('');
          $(`#destino_hospitalizacion${vIdIntervencion}`).find("#id_cama_destino")[0].tomselect.setValue('');
          $("#destino_hospitalizacion"+ vIdIntervencion).hide();
      }

      if($("#id_tipo_egreso"+ vIdIntervencion).val()=='1'){ //  DOMICILIARIA
           var fecha_valor = $(`#fsNuevaRecetaValePostQuirurgico2`).find("#fecha_expedicion_postqx").data('fecha_valor');  
           let valor_farmacia = $(`#fsNuevaRecetaValePostQuirurgico2`).find("#id_farmacia_receta_postqx").val();  
           var fecha_valor_egreso = $(`#destino_domiciliaria${vIdIntervencion}`).find("#fecha_egreso").data('fecha_valor');  
           var ahora_egreso = new Date();
           ahora_egreso = ahora_egreso.toTimeString().slice(0, 8); // "HH:MM:SS"
           $(`#alta_condicion${vIdIntervencion}`).find("#id_condicion_alta").val('');
           $(`#alta_condicion${vIdIntervencion}`).hide();
           $(`#destino_domiciliaria${vIdIntervencion}`).find("input,select").val('');
           $(`#destino_domiciliaria${vIdIntervencion}`).find("#fecha_egreso").val(fecha_valor_egreso);
           $(`#destino_domiciliaria${vIdIntervencion}`).find("#hora_egreso").val(ahora_egreso);
           $(`#destino_domiciliaria${vIdIntervencion}`).hide();
           if($("#btnNuevoReceetaPostQuirurgico").attr("flag")=='1'){//Si receta esta activa
              $(`#fsNuevaRecetaValePostQuirurgico2`).find("#id_farmacia_receta_postqx").val(valor_farmacia);
              $(`#fsNuevaRecetaValePostQuirurgico2`).find("#fecha_expedicion_postqx").val(fecha_valor);
              var $select = $('#tblMedicamentosAdicionalAtencionPQ [name="id_producto_adicional_pq[]"]');
              if ($select.length > 0) { 
                  $select.closest("tr").remove();
              }
              $("#btnNuevoReceetaPostQuirurgico").click();
           }
           $("#btnNuevoReceetaPostQuirurgico").hide();
           $(`#epicrisis${vIdIntervencion}`).find("input,select,textarea").val('');
           $(`#epicrisis${vIdIntervencion}`).hide();
      }
      $("#id_tipo_egreso"+ vIdIntervencion).val('');
    }
  });

  $(document).ready(function () {
    var registrar_destino = "<?php echo $registrar_destino;?>";
    var id_hospitalizacion = "<?php echo $id_hospitalizacion;?>";
    console.log('REGISTRADOR DESTINO:'+registrar_destino);
    if(registrar_destino>0){
      $('#addDestino'+<?php echo $id_intervencion;?>).attr("aria-pressed", "true");
      $('#addDestino'+<?php echo $id_intervencion;?>).addClass("active");
      $("#fsDestino"+<?php echo $id_intervencion;?>).show();
      $('#registrar_destino').val(1);
    }
    if(id_hospitalizacion=="null"){
      $('#addDestino' + <?php echo $id_intervencion; ?>).prop("disabled", true).attr("title", "El paciente falta pasar por admision de hospitalización.");
        bootbox.alert({
          title: '<i class="fa fa-exclamation-triangle text-warning"></i> Atención',
          message: "El paciente debe pasar por admisión de hospitalización.",
          buttons: {
            ok: {
              label: 'Cerrar',
              className: 'btn-warning'
            }
          },
          callback: function () {
            $('body').addClass('modal-open');
            $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
          }
        });
    }

    $('#id_upss_encargado').on('change', function () {
      var id_servicio_encargado = $(this).find('option:selected').attr('data-id_servicio_encargado');
     // $('#id_servicio_encargado').val(id_servicio_encargado);
    });


    const $input = $('#fecha_egreso');
    const fechaIngresoStr = $input.data('fecha-ingreso');

    function convertirAISO(fecha) {
        const partes = fecha.split('-');
        return `${partes[2]}-${partes[1]}-${partes[0]}`;
    }

    const fechaIngresoISO = convertirAISO(fechaIngresoStr);
    const hoy = hoyISO();

    // bloquear rango válido
    $input.attr('min', fechaIngresoISO);
    $input.attr('max', hoy);

    $input.on('input', function () {

        const fechaSeleccionada = $(this).val();

        if (!fechaSeleccionada) return;

        if (fechaSeleccionada < fechaIngresoISO) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha no permitida',
                text: 'No puede ser menor a la fecha de ingreso: ' + fechaIngresoStr
            });
            $(this).val(fechaIngresoISO);
        }

        if (fechaSeleccionada > hoy) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha no permitida',
                text: 'No puede ser mayor a la fecha actual'
            });
            $(this).val(hoy);
        }
    });

    function hoyISO() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }


    var id_tipo_egreso = $('#id_tipo_egreso'+<?php echo $id_intervencion;?>).val();
    if(id_tipo_egreso==1){
      $("#alta_condicion"+<?php echo $id_intervencion;?>).show('');
      $("#destino_domiciliaria"+<?php echo $id_intervencion;?>).show('');
      $("#epicrisis"+<?php echo $id_intervencion;?>).show('');
      $("#alta_condicion_tra"+<?php echo $id_intervencion;?>).hide();
      $("#destino_hospitalizacion"+<?php echo $id_intervencion;?>).hide();
    }
    if(id_tipo_egreso==2){
      $("#alta_condicion"+<?php echo $id_intervencion;?>).hide();
      $("#destino_domiciliaria"+<?php echo $id_intervencion;?>).hide();
      $("#epicrisis"+<?php echo $id_intervencion;?>).hide();
      $("#alta_condicion_tra"+<?php echo $id_intervencion;?>).show('');
      $("#destino_hospitalizacion"+<?php echo $id_intervencion;?>).show('');

    }


  });

  function agregarReceta() {
    var flagReceta = $('#btnNuevoReceeta').attr("flag");

    if (flagReceta == 0) {
      $('#btnNuevoReceeta').attr("flag", 1).html("Ocultar Receta");
      getProductoTemp2();
      $("#fsNuevaRecetaVale").show();
      $("#btnFirmarRecetaView").show();
      $('#tblMedicamentosAdicionalAtencion tbody').empty();
      // $("#divGuardar").show();
      // $("#divFirmar").hide();
    } else {
      $('#btnNuevoReceeta').attr("flag", 0).html("Agregar Receta");
      $("#fsNuevaRecetaVale").hide();
      $("#btnFirmarRecetaView").hide();
      //$("#btnFirmarRecetaView").show();
      if (flagReceta == 1) {
        // $("#divGuardar").hide();
        // $("#divFirmar").show();
      }
    }
  }
  function soloNumerosMenosCeroDetalle(e){
    console.log(e);

    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
  }
  function modalHistorial(){
    var dni_beneficiario = $('#dni_beneficiario_final').val();
    $.ajax({
        url: "../modal_historial_atenciones/"+dni_beneficiario,
        type: "GET",
        success: function (result) {
            $("#divHistorial2").html(result);
            // $('#tablemodalm').DataTable({
            //   "sPaginationType": "full_numbers",

            //   "language": {"url": "/js/Spanish.json"},
            // });
            if ($.fn.DataTable.isDataTable('#tablemodalm')) {
              $('#tablemodalm').DataTable().destroy();
            }
            $('#tablemodalm').DataTable({
              order: [],
              sPaginationType: "full_numbers",
              language: { "url": "/js/Spanish.json" }
            });
        }
    });
  }
  function modalLaboratorio(){

    var dni_beneficiario = '<?php echo $cita->nro_doc_ident;?>';
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {
            $("#divLaboratorio2").html(result);
            $tbl = $("#divLaboratorio2").find("#tablemodalm1");
            $tbl.DataTable({
              order: [],
              sPaginationType: "full_numbers",
              language: { url: "/js/Spanish.json" }
            });
        }
    });
  }

  function modalRadiologico2(){

    var dni_beneficiario = '<?php echo $cita->nro_doc_ident;?>';
    $.ajax({
        url: "../modal_historial_radiologia/"+dni_beneficiario,
        type: "GET",
        success: function (result) {
            $("#divRadiologico2").html(result);
            // $('#tablemodalm2').DataTable({
            //   "sPaginationType": "full_numbers",

            //   "language": {"url": "/js/Spanish.json"},
            // });
            if ($.fn.DataTable.isDataTable('#tablemodalm2')) {
              $('#tablemodalm2').DataTable().destroy();
            }
            $('#tablemodalm2').DataTable({
              order: [],
              sPaginationType: "full_numbers",
              language: { "url": "/js/Spanish.json" }
            });
        }
    });
  }
  /////////////////FIRMA RECETA EXISTENTE/////////////////
  //$('#btnFirmarRecetaExistente').on('click', function () {
  function btnFirmarRecetaExistente(id_receta,codigo){
    if(validarCamposTabla('tblMedicamentosRecetaExistente'+id_receta)){
      $('#firma_receta_existente'+id_receta).val(1);
      $('#flag_firma_receta').val(1);
      if(existenFilasNuevasRecetaExistente(id_receta)) {
        var formData = $('#frmAtencionQuirurgicaAdicional').serialize();
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Antes de firmar se guardara los medicamentos</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: $('#frmAtencionQuirurgicaAdicional').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if (response.success) {
                  Swal.fire({
                      title: "¿Está seguro de firmar la receta?",
                      html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Antes de firmar se guardarán los medicamentos.</span></div>",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonText: "Sí, firmar",
                      cancelButtonText: "No, cancelar",
                      reverseButtons: true,
                      allowOutsideClick: false
                  }).then((result) => {
                      if (result.isConfirmed) {
                          firmarReceta(response.id_receta);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $('#firma_receta_existente'+id_receta).val(0);
                        $('#flag_firma_receta').val(0);
                          // Cancelado por el usuario
                          Swal.fire({
                              icon: 'info',
                              title: 'Firma cancelada',
                              text: 'No se firmó la receta.',
                              timer: 2000,
                              showConfirmButton: false
                          });
                      }
                  });
                } else {
                  console.log(response);
                  // Muestra un mensaje de error
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de validación',
                      text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                  });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
      }else{
       Swal.fire({
          title: "¿Está seguro de firmar la receta?",
          html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se firmara la <strong>RECETA VALE N°"+codigo+"</strong></span></div>",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, firmar",
          cancelButtonText: "No, cancelar",
          reverseButtons: true,
          allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la firma</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              firmarReceta(id_receta);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#firma_receta_existente'+id_receta).val(0);
              $('#flag_firma_receta').val(0);
                // Cancelado por el usuario
                Swal.fire({
                    icon: 'info',
                    title: 'Firma cancelada',
                    text: 'No se firmó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
      }
    }
  }
  //});
  function existenFilasNuevasRecetaExistente(id_receta) {
    return $('#tblMedicamentosRecetaExistente' + id_receta + ' tr.nuevos_productos_existentes').length > 0;
  }
  /////////////////FIN FIRMA RECETA EXISTENTE/////////////////

  /////////////////FIRMA RECETA ADICIONAL/////////////////////
  $('#btnFirmarRecetaAdicional').on('click', function () {
    if(validarCamposTabla('tblMedicamentosAdicionalAtencion')){
      console.log(existenFilasRecetaAdicional());

      if(existenFilasRecetaAdicional()){
        $('#firma_receta_adicional').val(1);
        $('#flag_firma_receta').val(1);
        var formData = $('#frmAtencionQuirurgicaAdicional').serialize();
        Swal.fire({
            title: "¿Está seguro de firmar la receta?",
            html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Antes de firmar se guardarán los medicamentos.</span></div>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, firmar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Guardando los medicamentos</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              $.ajax({
                url: $('#frmAtencionQuirurgicaAdicional').attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.close();
                    $('input, select, textarea').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    if (response.success) {
                      firmarReceta(response.id_receta);
                    } else {
                      console.log(response);
                      Swal.fire({
                          icon: 'error',
                          title: 'Error de validación',
                          text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                      });
                    }
                },
                error: function(xhr) {
                    console.log('Ocurrió un error en la solicitud AJAX');
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                    });
                }
              });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#firma_receta_adicional').val(0);
              $('#flag_firma_receta').val(0);
                // Cancelado por el usuario
                Swal.fire({
                    icon: 'info',
                    title: 'Firma cancelada',
                    text: 'No se firmó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
      }else{
        Swal.fire({
            icon: 'warning',
            title: 'No existe registro',
            text: 'Mínimo debe existir un registro'
        });
      }
    }
  });
  function existenFilasRecetaAdicional() {
    return $('#tblMedicamentosAdicionalAtencion tr.producto_adicional').length > 0;
  }
  /////////////////FIN FIRMA RECETA ADICIONAL/////////////////////

  /////////////////FIRMA TODA LA ATENCION INCLUIDO LAS RECETAS/////////////////////
  $('#btnFirmarTodo').on('click', function () {
    var msg ="";

    let inicio = document.getElementById('hora_inicio_operatorio2');
    let fin     = document.getElementById('hora_fin_operatorio2');

    let inicioAlt = document.getElementById('cirugia_inicio3');
    let finAlt    = document.getElementById('cirugia_termino3');

    let horaInicio = null;
    let horaFin = null;

    /* Detectar qué campos existen */
    if (inicio && fin) {
        horaInicio = inicio.value;
        horaFin = fin.value;
    } else if (inicioAlt && finAlt) {
        horaInicio = inicioAlt.value;
        horaFin = finAlt.value;
    }

    /* Validaciones */
    if (!horaInicio || horaInicio === '00:00') {
        msg += "Debe ingresar una hora válida de inicio de cirugía.<br>";
    }

    if (!horaFin || horaFin === '00:00') {
        msg += "Debe ingresar una hora válida de término de cirugía.<br>";
    }

    if (
        horaInicio && 
        horaFin && 
        horaInicio !== '00:00' && 
        horaFin !== '00:00'
    ) {
        if (horaFin <= horaInicio) {
            msg += "La hora de término debe ser mayor que la hora de inicio.<br>";
        }
    }

    var addDescripcionProcedimiento = $('#addDescripcionProcedimiento').attr("aria-pressed");
    var descripcion_procedimiento = $('#descripcion_procedimiento').val().trim();
    if (addDescripcionProcedimiento === "false") {
      // msg += "Debe seleccionar DESCRIPCIÓN PROCEDIMIENTO<br>";
      const continuar = confirm("Debe seleccionar DESCRIPCIÓN PROCEDIMIENTO.\n¿Desea continuar de todos modos?");
      if (!continuar) {
          return;
      }
    }else{
      if (allazgo_operatorio === '') {
          msg += "Debe de ingresar datos en DESCRIPCIÓN PROCEDIMIENTO<br>";
      }
    }

    var addAllazgoOpe = $('#addAllazgoOpe').attr("aria-pressed");
    var allazgo_operatorio = $('#allazgo_operatorio').val().trim();
    if (addAllazgoOpe === "false") {
      // msg += "Debe seleccionar HALLAZGOS OPERATORIO<br>";
      const continuar = confirm("Debe seleccionar HALLAZGOS OPERATORIO.\n¿Desea continuar de todos modos?");
      if (!continuar) {
          return;
      }
    }else{
      if (allazgo_operatorio === '') {
          msg += "Debe de ingresar datos en HALLAZGOS OPERATORIO<br>";
      }
    }
    //var examen_patologico = $('#examen_patologico').val().trim();
    var ind_proced = $('#tblProcedimientoDetalle tbody tr').length;
    if (ind_proced > 0) {
      $('#tblProcedimientoDetalle tbody tr').each(function (index) {
        var id_procedimiento = $(this).find('.id_procedimiento').val();
        var procedimiento_cantidad  = $(this).find('.procedimiento_cantidad ').val();
        if (id_procedimiento === "") {
          msg += "Debe ingresar el nombre del procedimiento<br>";
        }
        if (procedimiento_cantidad === null ||  procedimiento_cantidad === "" || parseInt(procedimiento_cantidad, 10) === 0) {
          msg += "Debe ingresar la cantidad del procedimiento<br>";
        }
      });
    }else{
      msg += "Debe ingresar mínimo un procedimiento<br>";
    }

    var addExamenPatologico2 = $('#addExamenPatologico2').attr("aria-pressed");
    //var examen_patologico = $('#examen_patologico').val().trim();

    if (addExamenPatologico2 === "false") {
      // msg += "Debe seleccionar EXAMEN PATOLOGICO<br>";
      const continuar = confirm("Debe seleccionar EXAMEN PATOLOGICO.\n¿Desea continuar de todos modos?");
      if (!continuar) {
          return;
      }
    }else{
      var ind_proced_post_examen_patologia = $('#table_procedimiento_examen_patologia2 tbody tr').length;
      var errorEnProcedimientosExamenPatologia = false;
      if (ind_proced_post_examen_patologia > 0) {
        $('#table_procedimiento_examen_patologia2 tbody tr').each(function (index) {
          var id_procedimiento = $(this).find('.cmbProcedimientoExamenPatologia').val();
          if (id_procedimiento === "") {
            msg += "Debe ingresar el nombre del procedimiento (Examen patologico)<br>";
            errorEnProcedimientosExamenPatologia = true;
          }
        });

        if (!errorEnProcedimientosExamenPatologia){
          msg = msg.replace("Debe ingresar el nombre del procedimiento (Examen patologico)<br>", "");
        }
      }else{
        msg += "Debe ingresar mínimo un procedimiento (Examen patologico)<br>";
      }
      // if (examen_patologico === '') {
      //     msg += "Debe de ingresar datos en EXAMEN PATOLOGICO<br>";
      // }
      var addPiezaOpe = $('#addPiezaOpe').attr("aria-pressed");
      var pieza_operatorio = $('#pieza_operatorio').val().trim();
      if (addPiezaOpe === "true") {
        if (pieza_operatorio === '') {
          msg += "Debe de ingresar datos en PIEZA OPERATORIO<br>";
        }
      }
    }

    var addExamenBacteriologico = $('#addExamenBacteriologico').attr("aria-pressed");
    var examen_bacteriologico = $('#examen_bacteriologico').val().trim();

    if (addExamenBacteriologico === "false") {
      // msg += "Debe seleccionar EXAMEN BACTERIOLOGICO<br>";
      const continuar = confirm("Debe seleccionar EXAMEN BACTERIOLOGICO.\n¿Desea continuar de todos modos?");
      if (!continuar) {
          return;
      }
    }else{
      if (examen_bacteriologico === '') {
          msg += "Debe de ingresar datos en EXAMEN BACTERIOLOGICO<br>";
      }
    }

    //DESTINO
    var addDestino = $('#addDestino'+<?php echo $id_intervencion;?>).attr("aria-pressed");
    var id_tipo_egreso = $('#id_tipo_egreso'+<?php echo $id_intervencion;?>).val();

    if (addDestino === "false") {
       msg += "Debe seleccionar el destino<br>";
    }else{
      if (!id_tipo_egreso) {
          msg += "Debe seleccionar el tipo de destino<br>";
      } else {
        if(id_tipo_egreso==2){
          var id_area = $('#id_area_destino').val();
          if(!id_area){
              msg += "Debe seleccionar SALA A CARGO/SALA DESTINO<br>";
          }
          var id_cama_destino = $('#id_cama_destino').val();
          if(!id_cama_destino){
              msg += "Debe seleccionar la CAMA<br>";
          }
        }
        var $visibleSelect = $('select.condicion-alta:visible:not(:disabled)');
        if ($visibleSelect.length) {
            var id_condicion_alta = $visibleSelect.val();
            if (id_condicion_alta.length === 0) {
              msg += "Debe seleccionar la condición de alta<br>";
            }
        }
      }
    }
    if(id_tipo_egreso=='1'){
        <?php if(($intervencion->id_tipo_atencion_origen==1 || $intervencion->id_tipo_atencion_origen==2) && !$intervencion->es_nuevo_admision_hospitalizacion){?>
        // Obtener valores de cada textarea por su id
        var egreso_resumen               = $('#egreso_resumen').val().trim();
        var egreso_examen_clinico        = $('#egreso_examen_clinico').val().trim();
        var egreso_examen_auxiliar       = $('#egreso_examen_auxiliar').val().trim();
        var egreso_evolucion             = $('#egreso_evolucion').val().trim();
        var egreso_tratamiento           = $('#egreso_tratamiento').val().trim();
        var egreso_procedimiento         = $('#egreso_procedimiento').val().trim();
        var egreso_complicacion          = $('#egreso_complicacion').val().trim();
        var egreso_medidas_generales     = $('#egreso_medidas_generales').val().trim();
        var egreso_medicamentos_prescritos = $('#egreso_medicamentos_prescritos').val().trim();
        var egreso_pronostico            = $('#egreso_pronostico').val().trim();

        // Validaciones
        if (egreso_resumen === "") {
            msg += "Debe ingresar el resumen de egreso (EPICRISIS)<br>";
        }
        if (egreso_examen_clinico === "") {
            msg += "Debe ingresar el examen clínico (EPICRISIS)<br>";
        }
        if (egreso_examen_auxiliar === "") {
            msg += "Debe ingresar el examen auxiliar (EPICRISIS)<br>";
        }
        if (egreso_evolucion === "") {
            msg += "Debe ingresar la evolución (EPICRISIS)<br>";
        }
        if (egreso_tratamiento === "") {
            msg += "Debe ingresar el tratamiento (EPICRISIS)<br>";
        }
        if (egreso_procedimiento === "") {
            msg += "Debe ingresar el procedimiento (EPICRISIS)<br>";
        }
        if (egreso_complicacion === "") {
            msg += "Debe ingresar la complicación (EPICRISIS)<br>";
        }
        if (egreso_medidas_generales === "") {
            msg += "Debe ingresar las medidas generales (EPICRISIS)<br>";
        }
        if (egreso_medicamentos_prescritos === "") {
            msg += "Debe ingresar los medicamentos prescritos (EPICRISIS)<br>";
        }
        if (egreso_pronostico === "") {
            msg += "Debe ingresar el pronóstico (EPICRISIS)<br>";
        }
        <?php }?>
      }


    if (msg != "") {
          bootbox.alert({
          message: `<div style="max-height: 300px; overflow-y: auto;">${msg}</div>`,
          size: 'large',
          backdrop: true,
          callback: function () {
            $('body').addClass('modal-open'); // fuerza scroll en modales Bootstrap
            $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
          }
        });
    } else {
      $('#antesdefirmar_guadardatos').val(1);
      var formData = $('#frmAtencionQuirurgicaAdicional').serialize();
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Antes de firmar se guardara los datos</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: $('#frmAtencionQuirurgicaAdicional').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if (response.success && response.firmar=='ok') {
                   Swal.fire({
                    title: "¿Está seguro de firmar la atención?",
                    html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se firmara la <strong>LA ATENCIÓN QUIRURGICA</strong></span></div>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, firmar",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true,
                    allowOutsideClick: false
                  }).then((result) => {
                      if (result.isConfirmed) {
                        Swal.fire({
                          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la firma</span></div>",
                          html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                          showCancelButton: false,
                          showConfirmButton: false,
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          onBeforeOpen: () => {
                              Swal.showLoading();
                          },
                        });
                        firmarAtencionCqx();
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                          // Cancelado por el usuario
                          Swal.fire({
                              icon: 'info',
                              title: 'Firma cancelada',
                              text: 'No se firmó la atención.',
                              timer: 2000,
                              showConfirmButton: false
                          });
                      }
                  });
                } else {
                  console.log(response);
                  // Muestra un mensaje de error
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de validación',
                      text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                  });
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
    }
  });
  /////////////////FIRMA TODA LA ATENCION INCLUIDO LAS RECETAS/////////////////////
  function validarCamposTabla(tbl) {
    let camposVacios = [];

    $('#'+tbl+' tbody tr').each(function(index, fila) {
        let filaInvalida = false;

        $(fila).find('input, select, textarea').each(function() {
            const $campo = $(this);
            const tipo = $campo.attr('type');
            const valor = $campo.val();
            if (
                $campo.is(':hidden') ||
                $campo.is(':disabled') ||
                tipo === 'button' || tipo === 'submit'
            ) {
                return;
            }
            // Verifica si está vacío
            if (valor == null || valor.trim() === '') {
                filaInvalida = true;
            }
        });

        if (filaInvalida) {
            camposVacios.push(index + 1);
        }
    });

    if (camposVacios.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Existen campos vacíos en las siguientes filas: ' + camposVacios.join(', ')
        });
        return false;
    }

    return true;
  }
  /////////////////////////////////////////////////////////////////////////
  ///////////////////////////FIRMA DE LA RECETA////////////////////////////
  async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxReceta() {
    const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    const docName = $('#doc_firmar_receta').val();

    try {
        const response = await fetch("{{ route('atencion_cqx_receta_post_arguments') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                type: "W",
                documentName: docName
            })
        });

        const data = await response.text();
        await dispatchEventClient('sendArguments', data);
        $('#flag_firma_receta').val(0);

    } catch (error) {
        console.error("Error al obtener argumentos receta quirúrgica:", error);
        $('#flag_firma_receta').val(0);
    }
  }
  // async function getArgumentsReceta() {
  //   arg = document.getElementById("argumentos_receta").value;
  //   await dispatchEventClient('sendArguments', arg);
  // }
  // async function getArgumentsReceta() {
  //   const arg = document.getElementById("argumentos_receta").value;
  //   if (!arg) {
  //     console.warn("argumentos_receta está vacío.");
  //     return;
  //   }
  //   await dispatchEventClient('sendArguments', arg);
  // }
  // async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxReceta() {
  //   const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  //   const documentName = $('#doc_firmar_receta').val();

  //   try {
  //     const response = await fetch("{{ route('atencion_cqx_receta_post_arguments') }}", {
  //       method: "POST",
  //       headers: {
  //         'Content-Type': 'application/json',
  //         'X-CSRF-TOKEN': CSRF_TOKEN
  //       },
  //       body: JSON.stringify({
  //         type: "W",
  //         documentName: documentName
  //       })
  //     });
  //     const data = await response.text();
  //     document.getElementById("argumentos_receta").value = data;
  //     await getArgumentsReceta();
  //   } catch (error) {
  //     console.error("Error al obtener argumentos para receta quirúrgica:", error);
  //   }
  // }
  // async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqxReceta() {
  //   var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  //   $.post("{{route('atencion_cqx_receta_post_arguments')}}", {
  //   _token: CSRF_TOKEN,
  //   type: "W",
  //   documentName: $('#doc_firmar_receta').val()
  //   }, function(data, status) {
  //     document.getElementById("argumentos_receta").value = data;
  //     getArgumentsReceta();
  //   });
  // }
  async function actualiza_datos_firma_con_token_io_receta() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_resol_receta').val();
    $.post("{{route('atencion_cqx_receta_actualiza_datos_firma_con_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol_receta: id_resol,
    }, function(data, status) {
      if (data && data.status === 'ok') {
         window.location.href = '/cq_lista_pendiente';
      } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
           text: data?.message || 'No se pudo actualizar la firma de la receta.'
        });
        console.error("Error:", data);
      }
    });
  }

  // Firma electronica, marca la receta como firmada y vuelve a la lista de pendientes
  function firmarRecetaSinToken(id_receta){
    $("#id_resol_receta").val(id_receta);
    $.ajax({
        url: `/atencion_anestesio_cqx_receta_firmar_sin_token/${id_receta}`,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    })
    .done(function(resultado) {
      Swal.close();
      if(resultado){
        if (resultado.sw) {
          window.location.href = '/cq_lista_pendiente';
        } else {
          $('#firma_receta_existente'+id_receta).val(0);
          $('#flag_firma_receta').val(0);
          Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "Ha ocurrido un error al realizar la firma.",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
          });
        }
      }else{
        Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "No se puede procesar la información de la receta",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
        });
      }
    })
    .fail(function(xhr, status, error) {
        console.log('Error:', error);
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
        });
    });
  }

  // Firma digital
  function firmarReceta(id_receta){
    $("#id_resol_receta").val(id_receta);
    $.ajax({
        url: `/atencion_cqx_receta_firmar/${id_receta}`,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    })
    .done(function(resultado) {
      Swal.close();
      console.log('--------------');
      console.log(resultado);
      console.log('--------------');
      if(resultado){
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar_receta").val(`${resultado.csv}.7z`);
            tipo_envio = "receta";
            initInvoker('W');
        }
      }else{
        Swal.fire({
            title: "Información de Servicio de Firma, Receta",
            text: "No se puede procesar la información de la receta",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
        });
      }
    })
    .fail(function(xhr, status, error) {
        console.log('Error:', error);
    })
    .always(function() {
        console.log('Esta función siempre se ejecuta, éxito o error');
    });
  }
  /////////////////////////////////////////////////////////////////////////
  ///////////////////////////FIN FIRMA DE LA RECETA////////////////////////////

  /////////////////////////////////////////////////////////////////////////
  ///////////////////////////FIRMA DE LA ATENCION CQX INCLUIDO LAS RECETAS QUE NO HAN SIDO FIRMADOS////////////////////////////
  async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqx() {
    const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    const docName = $('#doc_firmar_atencion_cqx').val();

    try {
        const response = await fetch("{{ route('atencion_cqx_post_arguments') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                type: "W",
                documentName: docName
            })
        });

        const data = await response.text();
        await dispatchEventClient('sendArguments', data);

    } catch (error) {
        console.error("Error al obtener argumentos atención quirúrgica:", error);
    }
  }
  // async function getArgumentsAtencion() {
  //   const arg = document.getElementById("argumentos_atencion_cqx").value;
  //   await dispatchEventClient('sendArguments', arg);
  // }
  // async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqx() {
  //   const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  //   const docName = $('#doc_firmar_atencion_cqx').val();

  //   try {
  //     const response = await fetch("{{ route('atencion_cqx_post_arguments') }}", {
  //       method: "POST",
  //       headers: {
  //         'Content-Type': 'application/json',
  //         'X-CSRF-TOKEN': CSRF_TOKEN
  //       },
  //       body: JSON.stringify({
  //         type: "W",
  //         documentName: docName
  //       })
  //     });

  //     const data = await response.text();
  //     document.getElementById("argumentos_atencion_cqx").value = data;

  //     await getArgumentsAtencion();
  //   } catch (error) {
  //     console.error("Error al obtener argumentos para atención quirúrgica:", error);
  //   }
  // }
  // async function ObtieneArgumentosParaFirmaDesdeLaWebAtencionCqx() {
  //   var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
  //   $.post("{{route('atencion_cqx_post_arguments')}}", {
  //   _token: CSRF_TOKEN,
  //   type: "W",
  //   documentName: $('#doc_firmar_atencion_cqx').val()
  //   }, function(data, status) {
  //     document.getElementById("argumentos_atencion_cqx").value = data;
  //     getArgumentsAtencion();
  //   });
  // }
  async function actualiza_datos_firma_con_token_io_atencion_cqx() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_resol_atencion_cqx').val();
    $.post("{{route('atencion_cqx_actualiza_datos_firma_con_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol_atencion_cqx: id_resol,
    }, function(data, status) {
      if (data && data.status === 'ok') {
         window.location.href = '/cq_lista_pendiente';
      } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data?.message || 'No se pudo actualizar la firma de la atencion.'
        });
        console.error("Error:", data);
      }
    });
  }
  function firmarAtencionCqx(){
    var id_atencion = $("#id_resol_atencion_cqx").val();
    $.ajax({
        url: `/atencion_cqx_firmar/${id_atencion}`,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    })
    .done(function(resultado) {
      Swal.close();
      console.log(resultado);
      if(resultado){
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar_atencion_cqx").val(`${resultado.csv}.7z`);
            tipo_envio = "atencion";
            initInvoker('W');
        }
      }else{
        Swal.fire({
            title: "Información de Servicio de Firma",
            text: "No se puede procesar la información de la atención",
            icon: "info",
            timer: 4000,
            showConfirmButton: true
        });
      }
    })
    .fail(function(xhr, status, error) {
        console.log('Error:', error);
    })
    .always(function() {
        console.log('Esta función siempre se ejecuta, éxito o error');
    });
  }

  $(document).on('click', '#btnFinalizarAtencion', function(e) {
    e.preventDefault();
      var addDestino = $('#addDestino'+<?php echo $id_intervencion;?>).attr("aria-pressed");
      var id_tipo_egreso = $('#id_tipo_egreso'+<?php echo $id_intervencion;?>).val();
      let otros_datos=false;
      //OTROS CAMPOS
      var addAllazgoOpe = $('#addAllazgoOpe').attr("aria-pressed");
      var addPiezaOpe = $('#addPiezaOpe').attr("aria-pressed");
      var addExamenPatologico2 = $('#addExamenPatologico2').attr("aria-pressed");
      var addExamenBacteriologico = $('#addExamenBacteriologico').attr("aria-pressed");
      var allazgo_operatorio = $('#allazgo_operatorio').val().trim();
      var pieza_operatorio = $('#pieza_operatorio').val().trim();
      //var examen_patologico = $('#examen_patologico').val().trim();
      var examen_bacteriologico = $('#examen_bacteriologico').val().trim();
      var addComplicacionMedica = $('#addComplicacionMedica').attr("aria-pressed");
      var complicacion_medica = $('#complicacion_medica').val().trim();
      var msg ="";

      let inicio = document.getElementById('hora_inicio_operatorio2');
      let fin     = document.getElementById('hora_fin_operatorio2');

      let inicioAlt = document.getElementById('cirugia_inicio3');
      let finAlt    = document.getElementById('cirugia_termino3');

      let horaInicio = null;
      let horaFin = null;

      /* Detectar qué campos existen */
      if (inicio && fin) {
          horaInicio = inicio.value;
          horaFin = fin.value;
      } else if (inicioAlt && finAlt) {
          horaInicio = inicioAlt.value;
          horaFin = finAlt.value;
      }

      /* Validaciones */
      if (!horaInicio || horaInicio === '00:00') {
          msg += "Debe ingresar una hora válida de inicio de cirugía.<br>";
      }

      if (!horaFin || horaFin === '00:00') {
          msg += "Debe ingresar una hora válida de término de cirugía.<br>";
      }

      if (
          horaInicio && 
          horaFin && 
          horaInicio !== '00:00' && 
          horaFin !== '00:00'
      ) {
          if (horaFin <= horaInicio) {
              msg += "La hora de término debe ser mayor que la hora de inicio.<br>";
          }
      }
      
      if (addDestino === "true") {
        otros_datos=true;
        if (!id_tipo_egreso) {
            msg += "Debe seleccionar el destino<br>";
        } else {
          if(id_tipo_egreso==2){
            var id_area = $('#id_area_destino').val();
            if(!id_area){
               msg += "Debe seleccionar SALA A CARGO/SALA DESTINO<br>";
            }
            var id_cama_destino = $('#id_cama_destino').val();
            if(!id_cama_destino){
               msg += "Debe seleccionar la CAMA<br>";
            }
          }
          var $visibleSelect = $('select.condicion-alta:visible:not(:disabled)');
          if ($visibleSelect.length) {
              var id_condicion_alta = $visibleSelect.val();
              if (id_condicion_alta.length === 0) {
                msg += "Debe seleccionar la condición de alta<br>";
              }
          }
          if(id_tipo_egreso==1){
            let hora_egreso = $('#hora_egreso').val().trim();
            // Expresión regular que acepta HH:mm o HH:mm:ss (24 horas)
            const regex = /^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/;
            if (hora_egreso === '') {
              msg += "Debe ingresar la hora de egreso<br>";
            } else if (!regex.test(hora_egreso)) {
              msg += "Formato de hora inválido. Ingrese al menos hora y minutos (HH:mm).<br>";
            }
          }
        }

        if(otros_datos){

          if (addAllazgoOpe === "false") {
            msg += "Debe seleccionar HALLAZGOS OPERATORIO<br>";
          }else{
            if (allazgo_operatorio === '') {
                msg += "Debe de ingresar datos en HALLAZGOS OPERATORIO<br>";
            }
          }
          if (addPiezaOpe === "false") {
            msg += "Debe seleccionar PIEZA OPERATORIO<br>";
          }else{
            if (pieza_operatorio === '') {
                msg += "Debe de ingresar datos en PIEZA OPERATORIO<br>";
            }
          }
          if (addExamenPatologico2 === "false") {
            msg += "Debe seleccionar EXAMEN PATOLOGICO<br>";
          }else{
            var ind_proced_post_examen_patologia = $('#table_procedimiento_examen_patologia2 tbody tr').length;
            var errorEnProcedimientosExamenPatologia = false;
            if (ind_proced_post_examen_patologia > 0) {
              $('#table_procedimiento_examen_patologia2 tbody tr').each(function (index) {
                var id_procedimiento = $(this).find('.cmbProcedimientoExamenPatologia').val();
                if (id_procedimiento === "") {
                  msg += "Debe ingresar el nombre del procedimiento (Examen patologico)<br>";
                  errorEnProcedimientosExamenPatologia = true;
                }
              });

              if (!errorEnProcedimientosExamenPatologia){
                msg = msg.replace("Debe ingresar el nombre del procedimiento (Examen patologico)<br>", "");
              }
            }else{
              msg += "Debe ingresar mínimo un procedimiento (Examen patologico)<br>";
            }
            // if (examen_patologico === '') {
            //     msg += "Debe de ingresar datos en EXAMEN PATOLOGICO<br>";
            // }
          }
          if (addExamenBacteriologico === "false") {
            msg += "Debe seleccionar EXAMEN BACTERIOLOGICO<br>";
          }else{
            if (examen_bacteriologico === '') {
                msg += "Debe de ingresar datos en EXAMEN BACTERIOLOGICO<br>";
            }
          }
          if (addComplicacionMedica === "false") {
            msg += "Debe seleccionar registro Médico (Complicaciones)<br>";
          }else{
            if (complicacion_medica === '') {
                msg += "Debe de ingresar el registro Médico (Complicaciones)<br>";
            }
          }
        }

      }else{
         msg += "Debe seleccionar el destino<br>";
      }

      var ind_proced = $('#tblProcedimientoDetalle tbody tr').length;
      if (ind_proced > 0) {
        $('#tblProcedimientoDetalle tbody tr').each(function (index) {
          var id_procedimiento = $(this).find('.id_procedimiento').val();
          var procedimiento_cantidad  = $(this).find('.procedimiento_cantidad ').val();
          if (id_procedimiento === "") {
            msg += "Debe ingresar el nombre del procedimiento<br>";
          }
          if (procedimiento_cantidad === null ||  procedimiento_cantidad === "" || parseInt(procedimiento_cantidad, 10) === 0) {
            msg += "Debe ingresar la cantidad del procedimiento<br>";
          }
        });
      }else{
        msg += "Debe ingresar mínimo un procedimiento<br>";
      }

      if (msg != "") {
          bootbox.alert({
          message: `<div style="max-height: 300px; overflow-y: auto;">${msg}</div>`,
          size: 'large',
          backdrop: true,
          callback: function () {
            $('body').addClass('modal-open');
            $('#modal-detalle-atencion-quirurgica').css('overflow-y', 'auto');
          }
        });
      } else {
        var formData = $('#frmAtencionQuirurgicaAdicional').serialize();
        formData += '&' + $.param({ id_farmacia_receta_postqx: $('#id_farmacia_receta_postqx').val()});
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Guardando datos</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: $('#frmAtencionQuirurgicaAdicional').attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if(response.admsionado_hospitalizacion){
                  Swal.fire({
                      icon: 'info',
                      title: 'Información',
                      text: response.msg,
                      timer: 3000,
                      timerProgressBar: true,
                      showConfirmButton: false,
                      didClose: () => {
                        $('#id_hospitalizacion').val(response.id_hospitalizacion);
                        $('#addDestino' + <?php echo $id_intervencion; ?>).prop("disabled", false);
                        $('#addDestino'+<?php echo $id_intervencion;?>).attr("aria-pressed", "true");
                        $('#addDestino'+<?php echo $id_intervencion;?>).addClass("active");
                        $("#fsDestino"+<?php echo $id_intervencion;?>).show();
                        $('#registrar_destino').val(1);
                      }
                  });
                }else{
                  if (response.success) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Éxito',
                          text: 'Los datos se han guardado correctamente',
                          timer: 3000,
                          timerProgressBar: true,
                          showConfirmButton: false,
                          didClose: () => {
                            ActualizaAtencionSinTokens();
                          }
                      });
                  } else {
                    if (!response.destino) {
                      Swal.fire({
                          icon: 'warning',
                          title: 'Error de validación',
                          text: response.msg
                      });
                    }else{
                      console.log(response);
                      // Muestra un mensaje de error
                      Swal.fire({
                          icon: 'error',
                          title: 'Error de validación',
                          text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                      });
                    }
                  }
                }
            },
            error: function(xhr) {
                console.log('Ocurrió un error en la solicitud AJAX');
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });

      }
  })
  const ActualizaAtencionSinTokens = () => {
    Swal.fire({
          title: "¿Está seguro de continuar?",
          html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'><strong>La atención quirurgica sera finalizada</strong></span></div>",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, finalizar",
          cancelButtonText: "No, cancelar",
          reverseButtons: true,
          allowOutsideClick: false
        }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Procesando la finalizacion de la atención</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
          });
          var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
          let id_resol = $('#id_resol_atencion_cqx').val();
          $.post("{{route('atencion_cqx_actualiza_datos_firma_sin_token_io')}}", {
            _token: CSRF_TOKEN,
            id_resol_atencion_cqx: id_resol,
          }, function(data, status) {
             window.location.href = '/cq_lista_pendiente';
          });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                icon: 'info',
                title: 'El proceso ha sido cancelado',
                text: 'No se altero ninguna información.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
  }
  /////////////////////////////////////////////////////////////////////////
  ///////////////////////////FIN FIRMA DE LA ATENCION////////////////////////////

  $(document).ready(function() {
    modalHistorial();
    modalLaboratorio();
    modalRadiologico2();
  });

  function invalidarReceta(elementId, id_receta, id_farmacia) {
    $('#id_receta_invalidar').val(id_receta);
    $('#id_farmacia_invalidar').val(id_farmacia);
    const $btn = $('#' + elementId);  // ← aseguramos jQuery usando el ID
    if ($btn.length === 0) {
      console.warn('Elemento no encontrado:', elementId);
      return;
    }
    const offset = $btn.offset();
    const height = $btn.outerHeight();
    const $modal = $('#squarespaceModal');
    // Aseguramos que esté en el body para posicionarlo sin restricciones
    $('body').append($modal);

    $modal.css({
      position: 'absolute',
      top: offset.top + height + 10 + 'px',
      left: (offset.left - 0) + 'px', // ← mueve 50px a la izquierda
      display: 'block',
      zIndex: 1050
    });

    $modal.modal({
      backdrop: false,
      keyboard: true,
      show: true
    });
  }
  function cerrarRecetaInvalidar() {
    $('#squarespaceModal').modal('hide');
    $('#squarespaceModal').on('hidden.bs.modal', function () {
      if ($('#modal-detalle-atencion-quirurgica').hasClass('in') || $('#modal-detalle-atencion-quirurgica').is(':visible')) {
          $('body').addClass('modal-open');
      }
    });
  }

  $(document).on('click', '#btnInvalidar', function (e) {
    e.preventDefault();
    const motivo = $('#motivo_invalidar').val().trim();

    if (motivo === '') {
        $('#motivo_invalidar').css('border', '1px solid red');
        Swal.fire({
            icon: 'error',
            title: 'Campo requerido',
            text: 'Debe ingresar un motivo para invalidar la receta.'
        });
        return;
    } else {
        $('#motivo_invalidar').css('border', '');
    }

   var formData = $('#form_invalidar').serialize();
        Swal.fire({
            title: "¿Está seguro de anular la receta?",
            html: "<div style='margin-top:10px'><span style='color: #000000; font-size: 16px'>Se procedera a anular la receta.</span></div>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, firmar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Anulando la receta</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
              });
              $.ajax({
                url: $('#form_invalidar').attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.close();
                    $('input, select, textarea').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    if (response.success) {
                      $('#squarespaceModal').hide();
                      var id_receta_invalidar = $('#id_receta_invalidar').val();
                      $('#'+id_receta_invalidar).remove();
                      $('#motivo_invalidar').val('');
                      Swal.fire({
                          icon: 'success',
                          title: 'Receta anulada',
                          text: response.msg
                      });

                    } else {
                      console.log(response);
                      Swal.fire({
                          icon: 'warning',
                          title: 'No puede ser anulada la receta',
                          text: response.msg
                      });
                    }
                },
                error: function(xhr) {
                    console.log('Ocurrió un error en la solicitud AJAX');
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                    });
                }
              });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
              $('#squarespaceModal').hide();
                Swal.fire({
                    icon: 'info',
                    title: 'Invalidar receta, cancelada',
                    text: 'No se invalidó la receta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
  });
  $(document).ready(function(){
    $('#motivo_invalidar').on('input', function () {
        if ($(this).val().trim() !== '') {
            $(this).css('border', '');
        }
    });
  });
  function getCamas(){
    var id_ipress = $('#id_ipress').val();
    var id_area_destino = $('#id_area_destino').val();

    if(id_area_destino!='0') {
      var url_consulta='/get_camas_por_id_cuarto/' + id_ipress + '/' + id_area_destino + '/2';
      $.ajax({
        url: url_consulta,
        success: function(result) {
          let selectCamas = $('#id_cama_destino')[0].tomselect;
          selectCamas.clearOptions();
          result.forEach(function(item) {
            selectCamas.addOption({ value: item.id, text: item.nombre });
          });
          selectCamas.setValue('');
          // $('#id_cama_destino').empty();
          // $('#id_cama_destino').append("<option value='0'>-Seleccione-</option>");
          // $(result).each(function(ii, oo) {
          //   $('#id_cama_destino').append("<option value='" + oo.id + "'>" + oo.nombre+"-"+oo.cuarto+"-"+oo.tipo_cama + "</option>");
          // });
          // $('#id_cama_destino').val("0").change();
          // CAMA SELECCIONADA
          //mantenerCamaSeleccionadaEnCasoDeError();
        },
      });
    }else{
      $('#id_cama_destino').empty();
      $('#id_cama_destino').append("<option value='0'>-Seleccione-</option>");
      $('#id_cama_destino').val("0").change();
    }
  }

  $(document).ready(function(){
    // var tiene_descripcion_procedimiento = {{ isset($ultimoRegistro->tiene_descripcion_procedimiento) && $ultimoRegistro->tiene_descripcion_procedimiento ? 'true' : 'false' }};
    // if(tiene_descripcion_procedimiento){
    //    $('#addDescripcionProcedimiento').click();
    //    $("#fsDescripcionProcedimiento").show();
    // }

    // var tiene_hallazgo = {{ isset($ultimoRegistro->tiene_hallazgo) && $ultimoRegistro->tiene_hallazgo ? 'true' : 'false' }};
    // if(tiene_hallazgo){
    //    $('#addAllazgoOpe').click();
    //    $("#fsAllazgoOpe").show();
    // }

    // var tiene_pieza = {{ isset($ultimoRegistro->tiene_pieza) && $ultimoRegistro->tiene_pieza ? 'true' : 'false' }};
    // if(tiene_pieza){
    //    $('#addPiezaOpe').click();
    //    $("#fsPiezaOpe").show();
    // }

    // var tiene_examen_bact = {{ isset($ultimoRegistro->tiene_examen_bact) && $ultimoRegistro->tiene_examen_bact ? 'true' : 'false' }};
    // if(tiene_examen_bact){
    //    $('#addExamenPatologico2').click();
    //    $("#fsExamenPatologico").show();
    // }

    // var tiene_examen_pat = {{ isset($ultimoRegistro->tiene_examen_pat) && $ultimoRegistro->tiene_examen_pat ? 'true' : 'false' }};
    // if(tiene_examen_pat){
    //    $('#addExamenBacteriologico').click();
    //    $("#fsExamenBacteriologico").show();
    // }

    // var tiene_complicacion_medica = {{ isset($ultimoRegistro->tiene_complicacion_medica) && $ultimoRegistro->tiene_complicacion_medica ? 'true' : 'false' }};
    // if(tiene_complicacion_medica){
    //    $('#addComplicacionMedica').click();
    //    $("#complicacionMedica").show();
    // }

  });

  $('#addAllazgoOpe').on('click', function () {
    var addAllazgoOpe = $('#addAllazgoOpe').attr("aria-pressed");
    $("#fsAllazgoOpe").hide();
    if(addAllazgoOpe == "false"){
      $('#tiene_hallazgo').val(1);
      $("#fsAllazgoOpe").show();
    }else{
       // Desabilitar el segmento y limpiar los campos
       $('#tiene_hallazgo').val(0);
       $("#fsAllazgoOpe").find("input, textarea,select").val('');
    }
  });
  $('#addPiezaOpe').on('click', function () {
    var addAllasgoOpe = $('#addPiezaOpe').attr("aria-pressed");
    $("#fsPiezaOpe").hide();
    if(addAllasgoOpe == "false"){
      $('#tiene_pieza').val(1);
      $("#fsPiezaOpe").show();
    }else{
      // Desabilitar el segmento y limpiar los campos
      $('#tiene_pieza').val(0);
      $("#fsPiezaOpe").find("input, textarea,select").val('');
    }
  });
  $('#addExamenPatologico2').on('click', function () {
    var addExamenPatologico2 = $('#addExamenPatologico2').attr("aria-pressed");
    $("#fsExamenPatologico").hide();
    if(addExamenPatologico2 == "false"){
      $('#tiene_examen_pat').val(1);
      $("#fsExamenPatologico").show();
    }else{
      // Desabilitar el segmento y limpiar los campos
      $('#tiene_examen_pat').val(0);
      var $select = $('#table_procedimiento_examen_patologia2 [name="id_procedimiento_examen_patologia[]"]');
      if ($select.length > 0) {
          $select.each(function () {
              if ($(this).hasClass("select2-hidden-accessible")) {
                  $(this).select2('destroy');
              }
          });

          $select.closest("tr").remove();
      }
      if($('#addPiezaOpe').attr("aria-pressed")=='true'){
        $('#addPiezaOpe').click();
      }
      $("#fsPiezaOpe").find("input, textarea,select").val('');
    }
  });
  $('#addExamenBacteriologico').on('click', function () {
    var addExamenBacteriologico = $('#addExamenBacteriologico').attr("aria-pressed");
    $("#fsExamenBacteriologico").hide();
    if(addExamenBacteriologico == "false"){
      $('#tiene_examen_bact').val(1);
      $("#fsExamenBacteriologico").show();
    }else{
      // Desabilitar el segmento y limpiar los campos
      $('#tiene_examen_bact').val(0);
      $("#fsExamenBacteriologico").find("input, textarea,select").val('');
    }
  });

  $('#addComplicacionMedica').on('click', function () {
    var addComplicacionMedica = $('#addComplicacionMedica').attr("aria-pressed");
    $("#complicacionMedica").hide();
    if(addComplicacionMedica == "false"){
      $('#tiene_complicacion_medica').val(1);
      $("#complicacionMedica").show();
    }else{
      // Desabilitar el segmento y limpiar los campos
      $('#tiene_complicacion_medica').val(0);
      $("#complicacionMedica").find("input, textarea,select").val('');
    }
  });

  $('#addDescripcionProcedimiento').on('click', function () {
    var addDescripcionProcedimiento = $('#addDescripcionProcedimiento').attr("aria-pressed");
    $("#fsDescripcionProcedimiento").hide();
    if(addDescripcionProcedimiento == "false"){
      $('#tiene_descripcion_procedimiento').val(1);
      $("#fsDescripcionProcedimiento").show();
    }else{
      // Accion para desabilitar el segmento
      $('#tiene_descripcion_procedimiento').val(0);
      $("#fsDescripcionProcedimiento").find("input, textarea,select").val('');

    }
  });

  $('#addProcedimientoExamenPatologia2').on('click', function () {
		AddProcedimientoExamenPatologia2();
	});
  let tomSelectInstancesProcedimientoExamenPatologia2 = {};
  function AddProcedimientoExamenPatologia2() {
    let newRow = "";
    let ind = $('#table_procedimiento_examen_patologia2 tbody tr').length;
    
    let item_procedimiento = "";
    $('#cmbProcedimientoExamenPatologiaTemp2 option').each(function() {
      item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    newRow +='<tr>';
    newRow +='<td colspan="2"><select class="select-tom cmbProcedimientoExamenPatologia2" id="cmbProcedimientoExamenPatologia2' + ind + '" ind="' + ind + '" name="id_procedimiento_examen_patologia[]" >' + item_procedimiento + '</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';

    newRow +='</tr>';
    $('#table_procedimiento_examen_patologia2 tbody').append(newRow);
    //$("#cmbProcedimientoExamenPatologia" + ind).select2({max_selected_options: 4}).focus();
    const select = new TomSelect("#cmbProcedimientoExamenPatologia2" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstancesProcedimientoExamenPatologia2["cmbProcedimientoExamenPatologia2" + ind] = select;
    select.focus();
    
    $("#cmbProcedimientoExamenPatologia2"+ind).on("change", function (e) {
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbProcedimientoExamenPatologia2').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });
      if (duplicados > 1 && selectedValue!='') {
        bootbox.alert("El Procedimiento ya ha sido ingresado");
        const currentTS = tomSelectInstancesProcedimientoExamenPatologia2["cmbProcedimientoExamenPatologia2" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }
    
    });
    item_procedimiento++;
  }
  $('#table_procedimiento_examen_patologia2 tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
    var fila = obj.closest('tr');
    var filaPosition = fila.index();
    obj.parent().parent().remove();
  });

</script>
<style>
  .validado_incorrecto{
        border: 2px red solid !important;
    }
  #tblMedicamentosAdicionalAtencion {
      table-layout: fixed;
      width: 120%;
      word-wrap: break-word;
  }

  #tblMedicamentosAdicionalAtencion th,
  #tblMedicamentosAdicionalAtencion td {
      word-break: break-word;
      overflow-wrap: break-word;
      white-space: normal;
  }
  #squarespaceModal .modal-dialog {
    margin: -0 !important;
  }
  .tom-select {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 14px;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    width: 100%;
    box-shadow: none;
  }

  .tom-select .ts-control {
    border: none;
    box-shadow: none;
    background: none;
    min-height: 36px;
  }

  .tom-select .ts-dropdown {
    border-radius: 0 0 4px 4px;
    border: 1px solid #ccc;
    border-top: none;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
  }
  .select-tom.is-invalid {
    border-color: red !important;
  }
</style>
<script>

  const inicio = document.getElementById('cirugia_inicio3');
  const termino = document.getElementById('cirugia_termino3');

  if (inicio && termino) {
      inicio.addEventListener('change', calcularDuracion3);
      termino.addEventListener('change', calcularDuracion3);
  }

  function calcularDuracion3() {

      const inicio = document.getElementById('cirugia_inicio3').value;
      const termino = document.getElementById('cirugia_termino3').value;

      if (!inicio || !termino) return;

      const inicioMin = convertirATiempoEnMinutos(inicio);
      const terminoMin = convertirATiempoEnMinutos(termino);

      const diferencia = terminoMin - inicioMin;

      if (diferencia < 0) {
          Swal.fire({
              icon: 'warning',
              title: 'Horario inválido',
              text: 'El término debe ser posterior al inicio'
          });
          document.getElementById('tipo_real2').value = '';
          return;
      }
      document.getElementById('tiempo_real').value = convertirMinutosATiempo(diferencia);
      document.getElementById('tipo_real2').value = convertirMinutosATiempo(diferencia);
  }

  function convertirATiempoEnMinutos(hora) {
      const [h, m] = hora.split(':').map(Number);
      return h * 60 + m;
  }

  function convertirMinutosATiempo(min) {
      const h = Math.floor(min / 60);
      const m = min % 60;
      return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
  }

  $('input[name="complicacion"]').on('change', function() {
    if ($(this).val() === 'si') {
        $('#complicacionDescripcion').show(); 
    } else {
        $('#complicacionDescripcion').hide();
    }
});
$(document).ready(function() {
   var complicaciones = {!! json_encode(isset($intraoperatorio) ? $intraoperatorio->complicaciones : '') !!};
    if (complicaciones && complicaciones.trim() !== "") {
        $('#complicacionDescripcion').show();
    } else {
        $('#complicacionDescripcion').hide();
    }
});
$(document).ready(function(){
    let hora_duracion = $('#hora_duracion_operatorio2').val() || 0;
    if (!hora_duracion) return;
    const minutosRaw = parseInt(hora_duracion, 10);

    if (!isNaN(minutosRaw) && minutosRaw > 0) {
        const minutos = minutosRaw < 10 ? minutosRaw * 60 : minutosRaw;
        const horas = Math.floor(minutos / 60);
        const min = minutos % 60;
        const hhmm = (String(horas).padStart(2, '0')) + ':' + (String(min).padStart(2, '0'));
        const campo = document.getElementById("hora_duracion_operatorio_calculada2");
        if (campo) {
          campo.value = hhmm;
        }
    }

    let inicio = document.getElementById('hora_inicio_operatorio2');
    let fin = document.getElementById('hora_fin_operatorio2');

    if (inicio) inicio.addEventListener('change', calcularDuracion);
    if (fin) fin.addEventListener('change', calcularDuracion);

    function calcularDuracion() {
        const inicioInput = document.getElementById('hora_inicio_operatorio2');
        const finInput = document.getElementById('hora_fin_operatorio2');
        const inputDuracion = document.getElementById('hora_duracion_operatorio_calculada2');
        const inputMinutos = document.getElementById('hora_duracion_operatorio2'); 

        if (!inicioInput || !finInput || !inputDuracion) return;

        const inicio = inicioInput.value;
        const termino = finInput.value;

        if (!inicio || inicio === '00:00') {
            Swal.fire({
                icon: 'warning',
                title: 'Hora de inicio inválida',
                text: 'La hora de inicio debe ser mayor a 00:00.'
            });
            inicioInput.focus();
            return;
        }

        if (!termino || termino === '00:00') {
            Swal.fire({
                icon: 'warning',
                title: 'Hora de término inválida',
                text: 'La hora de término debe ser mayor a 00:00.'
            });
            finInput.value = '';
            finInput.focus();
            return;
        }

        const inicioMillis = convertirATiempoEnMilisegundos(inicio);
        let terminoMillis = convertirATiempoEnMilisegundos(termino);

        // Cruce de medianoche
        if (terminoMillis < inicioMillis) {
            terminoMillis += 24 * 60 * 60 * 1000;
        }

        const diferenciaMillis = terminoMillis - inicioMillis;

        if (diferenciaMillis <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Horario inválido',
                text: 'La hora de término debe ser mayor que la hora de inicio.'
            });
            finInput.value = '';
            return;
        }

        inputDuracion.value = convertirMilisegundosATiempo(diferenciaMillis);
        const minutosTotales = Math.floor(diferenciaMillis / 60000);
        inputMinutos.value = minutosTotales;
    }
    function convertirATiempoEnMilisegundos(hora) {
        const partes = hora.split(':');
        const horas = parseInt(partes[0], 10);
        const minutos = parseInt(partes[1], 10);
        return (horas * 60 * 60 * 1000) + (minutos * 60 * 1000);
    }
    function convertirMilisegundosATiempo(milis) {
        const horas = Math.floor(milis / (1000 * 60 * 60));
        const minutos = Math.floor((milis % (1000 * 60 * 60)) / (1000 * 60));
        return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
    } 
});
</script>


<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
    $complicaciones = ['SIN COMPLICACIONES', 'COMPLICADO'];
    $v_Fecha_por_Defecto  = !empty($hospitalizacion->fecha_alta_medica) ? \Carbon\Carbon::parse($hospitalizacion->fecha_alta_medica)->format('Y-m-d') 
                                                  : (!empty($intervencion->fecha_probable) ? \Carbon\Carbon::parse($intervencion->fecha_probable)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d'));
?>

<div class="modal fade" id="modal-info-paciente2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>INFORMACIÓN DEL PACIENTE</b>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <label for="">DATOS DEL TITULAR</label>
                    <hr>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">DOCUMENTO DE IDENTIDAD</label>
                            <input tabindex="21" readonly="" type="text" class="form-control" value="<?php echo $titular->tipo_doc_ident . ' ' . $titular->dni_titular?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">TITULAR</label>
                            <input type="text" class="form-control" value="<?php echo $titular->nombre . ' ' . $titular->paterno . ' ' . $titular->materno?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">CIP</label>
                            <input type="text" class="form-control" value="<?php echo $titular->cip?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">GRADO</label>
                            <input tabindex="19" value="<?php echo $titular->grado?>" type="text" name="grado"
                            id="grado" class="form-control" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">SITUACIÓN</label>
                            <input tabindex="19" value="<?php echo $titular->situacion_paciente?>" type="text" name="situacion_paciente"
                            id="situacion_paciente" class="form-control" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">TELÉFONO</label>
                            <input tabindex="19" value="<?php echo $titular->telefono?>" type="text" name="telefono" id="telefono" class="form-control" style="text-align:left" readonly>
                        </div>
                    </div>

                    <label for="">DATOS DEL ACOMPAÑANTE</label>
                    <hr>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="">CONDUCIDO POR:</label>
                            <input type="text" class="form-control" value="<?php //echo $acompaniante->descripcion?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="">DOC. IDENTIDAD Y NOMBRES</label>
                            <input type="text" class="form-control" value="<?php //echo $acompaniante->tipo_doc_acompaniante . ' ' . $acompaniante->nro_doc_acompaniante . ' ' . $acompaniante->nombre_acompaniante?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">TELÉFONO</label>
                            <input type="text" class="form-control" value="<?php //echo $acompaniante->telefono_acompaniante?>" style="text-align:left" readonly>
                        </div>
                    </div>
                    @if (isset($gestacion->id))
                    <div class="panel panel-success">
                        <div class="panel-heading">GESTACIÓN</div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>ULTIMA MESTRUACIÓN:</label>
                              <p>{{$gestacion->fech_ult_regla}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>FECHA PROBABLE DE PARTO:</label>
                              <p>{{$gestacion->fech_prob_parto}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>SEMANAS DE GESTACIÓN:</label>
                              <p>{{$gestacion->semanas_gestacion}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>N ° DE CONTROLES PRENATALES:</label>
                              <p>{{$gestacion->num_controles}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>DUDA:</label>
                              <p>{{$gestacion->duda ? 'SI' : 'NO'}}</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    @endif

                    @if (isset($puerperio->id))
                    <div class="panel panel-success">
                        <div class="panel-heading">PUERPERA</div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>FECHA Y HORA PARTO:</label>
                              <p>{{$puerperio->fecha_parto}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>TIPO DE PARTO:</label>
                              <p>{{$puerperio->id_tipo_parto}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>COMPLICACIÓN:</label>
                              <p>{{$puerperio->complicaciones}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                              <label>DESCRIPCIÓN:</label>
                              <p>{{$puerperio->descripcion}}</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">NEONATOS</div>
                        <div class="panel-body">
                          @if (isset($natis) && count($natis) > 0)
                          <div class="container-fluid">
                            @foreach ($natis as $row)
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>ESTADO DE VIDA:</label>
                                  <p><?php echo $row->estado?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TIPO DE DOCUMENTOS:</label>
                                  <p><?php echo $row->medico_paterno?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>NUM. DOCUMENTOS:</label>
                                  <p><?php echo $row->num_doc?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>SEXO:</label>
                                  <p><?php echo $row->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' ?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TALLA:</label>
                                  <p><?php echo $row->talla?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                  <label>PESO:</label>
                                  <p><?php echo $row->peso?></p>
                                </div>
                              </div>
                            @endforeach
                          </div>
                          @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="display: none; position: absolute; z-index: 9999; width: 700px; max-width: 90%;">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="cerrarRecetaInvalidar()"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
      </div>
      <div class="modal-body">
        <form  id="form_invalidar" method="post" action="{{ route('atencion_cqx_invalidar_receta_digital')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" class="form-control" id="id_farmacia_invalidar" name="id_farmacia">
            <input type="hidden" class="form-control" id="id_receta_invalidar" name="id_receta">
            <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
            <textarea class="form-control" id="motivo_invalidar" name="motivo" placeholder="Ingrese el motivo" required rows="3" cols="3"></textarea>
          </div>
          <div class="form-group">
            <input id="btnInvalidar" class="btn btn-success" type="button" value="Invalidar">
            <input onclick="cerrarRecetaInvalidar()" class="btn btn-danger" type="button" value="Cancelar">
          </div>
        </form>
      </div>
  </div>
  </div>
</div>
<div class="panel with-nav-tabs panel-success">
  <div class="panel-heading">
    <ul class="nav nav-tabs">
      <li class="active">
        <a href="#tab1default_atencioncqx" data-toggle="tab" style="font-size:12px">NUEVA ATENCIÓN</a>
      </li>
      <li>
        <a href="#tab2default2" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a>
      </li>
      <li>
        <a href="#tab3default2" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXÁMENES DE LABORATORIO</a>
      </li>
      <li>
        <a href="#tab4default2" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXÁMENES DE IMAGENOLOGÍA</a>
      </li>
    </ul>
  </div>
  <div class="panel-body">
    <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default_atencioncqx">
        <div id="FormularioQuirurgico" name="FormularioQuirurgico" class="form-horizontal">
          <div class="panel-body"> 
              <div>
                  <form class="form-horizontal" method="post" action="{{ route('atencion_quirurgica_adicional')}}" id="frmAtencionQuirurgicaAdicional" autocomplete="off">
                    <div class="form-group">
                        <!--SEGMENTO DE DATOS DE LA ATENCION-->
                        <div class="panel panel-default">
                          <div class="panel-heading">DATOS DE LA ATENCIÓN</div>
                          <div class="panel-body">
                              <div class="col-md-3">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                <!-- <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia"> -->
                                <input type="hidden"  name="id_farmacia" id="id_farmacia" value="18">
                                <!-- <input type="hidden" value="<?php //echo $id_prestacion?>" name="id_resol" id="id_resol"> -->
                                <input type="hidden" value="<?php echo $id_intervencion?>" name="id_intervencion" id="id_intervencion">
                                <input type="hidden" value="@if($hospitalizacion){{$hospitalizacion->id}}@endif" name="id_hospitalizacion" id="id_hospitalizacion">
                                <input type="hidden" value="@if(!empty($dato_cama)){{$dato_cama->id_cuarto}}@endif" name="id_area_actual">
                                <input type="hidden" value="@if(!empty($dato_cama)){{$dato_cama->id}}@endif" name="id_cama_actual" id="id_cama_actual">
                                <!-- <input type="hidden" id="argumentos_atencionquirurgica"/>
                                <input type="hidden" id="doc_firmar_atencionquirurgica" /> -->
                                <input type="hidden" id="id_ipress" value="<?php echo $id_ipress;?>" />
                                <input type="hidden" id="id_servicio_destino" name="id_servicio_destino" value="{{$id_servicio_encargado}}" />
                                <input type="hidden" id="id_servicio_encargado" name="id_servicio_encargado" value="{{$id_servicio_encargado}}">
                                <input type="hidden" id="id_upss_origen" name="id_upss_origen"
                                value="@if(!empty($hospitalizacion)){{$hospitalizacion->id_upss_origen}} @elseif(!empty($datos_servicio))  {{$datos_servicio->id_upss_responsable}} @endif">

                                <input type="hidden" id="id_prestacion_atq" name="id_prestacion_atq" value="<?php echo $id_prestacion_atq;?>" />
                                <input type="hidden" id="registrar_destino" name="registrar_destino" value="0">
                                <input type="hidden" id="dni_beneficiario_final" value="{{$titular->dni_titular}}">
                                <input type="hidden" id="firma_receta_adicional" name="firma_receta_adicional" value="0">
                                <!-- FIRMA ATENCION QUIRURGICA -->
                                <input type="hidden" id="id_resol_atencion_cqx" value="{{$id_atencion}}">
                                <input type="hidden" id="argumentos_atencion_cqx"/>
                                <input type="hidden" id="doc_firmar_atencion_cqx" />
                                <input type="hidden"  id="flag_firma_centro_quirurgico" value="3">
                                <!-- FIN FIRMA RECETA -->
                                <!-- FIRMA RECETA -->
                                <input type="hidden" id="id_resol_receta">
                                <input type="hidden" id="argumentos_receta"/>
                                <input type="hidden" id="doc_firmar_receta" />
                                <input type="hidden"  id="flag_firma_receta" value="0">
                                <!-- FIN FIRMA RECETA -->

                                <input type="hidden"  name="tiene_hallazgo" id="tiene_hallazgo" value="{{ isset($ultimoRegistro->tiene_hallazgo) && $ultimoRegistro->tiene_hallazgo ? 1 : 0 }}" />
                                <input type="hidden"  name="tiene_pieza" id="tiene_pieza" value="{{ isset($ultimoRegistro->tiene_pieza) && $ultimoRegistro->tiene_pieza ? 1 : 0 }}" />
                                <input type="hidden"  name="tiene_examen_bact" id="tiene_examen_bact" value="{{ isset($ultimoRegistro->tiene_examen_bact) && $ultimoRegistro->tiene_examen_bact ? 1 : 0 }}" />
                                <input type="hidden"  name="tiene_examen_pat" id="tiene_examen_pat" value="{{ isset($ultimoRegistro->tiene_examen_pat) && $ultimoRegistro->tiene_examen_pat ? 1 : 0 }}" />
                                <input type="hidden"  name="tiene_complicacion_medica" id="tiene_complicacion_medica" value="{{ isset($ultimoRegistro->tiene_complicacion_medica) && $ultimoRegistro->tiene_complicacion_medica ? 1 : 0 }}" />
                                <input type="hidden"  name="codigo_upss_atencioncqx"  value="{{ isset($ultimoRegistro->codigo_upss) && $ultimoRegistro->codigo_upss ? $ultimoRegistro->codigo_upss : null }}" />
                                <input type="hidden"  name="tiene_descripcion_procedimiento" id="tiene_descripcion_procedimiento" value="{{ isset($ultimoRegistro->tiene_descripcion_procedimiento) && $ultimoRegistro->tiene_descripcion_procedimiento ? 1 : 0 }}" />
                                <input type="hidden" name="tipo_real" id="tiempo_real" value="{{ !empty($intraoperatorio->tipo_real)? substr($intraoperatorio->tipo_real, 0, 5): '00:00' }}">
                                <input type="hidden" name="hora_duracion_operatorio" id="hora_duracion_operatorio2" value="{{ !empty($datos_intervencion->hora_duracion_operatorio)? substr($datos_intervencion->hora_duracion_operatorio, 0, 5): '0' }}">


                                <input type="hidden"  id="antesdefirmar_guadardatos" name="antesdefirmar_guadardatos" value="0">
                                <input type="hidden" id="id_farmacia_postqx2" name="id_farmacia_postqx" >
                                <input type="hidden" id="es_receta_postquirurgico2" name="es_receta_postquirurgico" value="0"> 
                                <input type="hidden" name="medico_id_actual" value="{{ $medico_id_actual }}">

                                  <label>TIPO DE ATENCI&Oacute;N</label>
                                  <p><?php echo $prestacion->tipo_atencion?></p>
                              </div>
                              <div class="col-md-3" id="divModalidad" <?php if($id_tipo_atencione!=3) echo 'style="display:none"'; ?>>
                                  <label for="">MODALIDAD</label><br>
                                  <?php
                                    if($prestacion->id_modalidad==1){
                                        echo "PRESENCIAL";
                                    }
                                    else
                                    {
                                        echo "VIRTUAL";
                                    }
                                    ?>
                              </div>
                              <?php
                                if($servicio!=null):
                                  $nombre_servicio=$servicio->nombre;
                                else:
                                    $nombre_servicio='';
                                endif;
                                // switch ($prestacion->id_tipo_atencion) {
                                //     case '1': //AMBULATORIO
                                //     $descripcion='SERVICIO'; $descripcion2='CONSULTORIO';break;

                                //     case '2': //EMERGENCIA
                                //     $descripcion='PRIORIDAD'; $descripcion2='TOPICO';break;

                                //     case '3': //HOSPITALIZACION
                                //     $descripcion='SALA'; $descripcion2='SERVICIO';break;
                                // }
                              ?>
                              <div class="col-md-3">
                                <label for="nombre_ipres">SERVICIO</label>
                                <p><?php echo isset($hospitalizacion->servicio_cargo)?$hospitalizacion->servicio_cargo:$nombre_servicio;?><?php //echo $prestacion->servicio?></p>
                              </div>
                              

                              <div class="col-md-3">
                                  <label>SALA</label>
                                  <p><?php echo isset($hospitalizacion->cama)?$hospitalizacion->cama: (isset($datos_intervencion->sala) ? $datos_intervencion->sala : '');?></p>
                              </div>

                              <div class="col-md-3">
                                  <label for="">FECHA DE ATENCI&Oacute;N</label>
                                  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                    <p>{{ date('d-m-Y', strtotime($prestacion->fecha_hora_atencion)) }}</p>
                                  @else
                                  <input 
                                      tabindex="6" 
                                      type="datetime-local" 
                                      class="form-control"
                                      value="{{ \Carbon\Carbon::parse( !empty($intervencion->fecha_probable) ? $intervencion->fecha_probable : $intervencion->created_at )->format('Y-m-d\TH:i') }}"
                                      name="fecha_hora_atencion" 
                                      id="fecha_hora_atencion" 
                                      max="{{ date('Y-m-d\TH:i') }}"
                                      style="text-align:left;">
                                  @endif
                              </div>
                              <!-- <div class="col-md-3">
                                  <label for="fecha_atencion">HORA DE ATENCI&Oacute;N</label>
                                  <p>{{ date('H:i', strtotime($prestacion->created_at)) }}</p>
                              </div> -->
                              <div class="col-md-3">
                                    <label>CODIGO UPSS</label>
                                    <p><?php echo isset($datos_servicio->codigo)?$datos_servicio->codigo:(isset($datos_upss_ambulatorio->codigo) ? $datos_upss_ambulatorio->codigo : '');?></p>
                              </div>
                              <div class="col-lg-3">
                              <label>NOMBRE DE LA UPSS</label>
                                <p><?php echo isset($datos_servicio->descripcion_upss)?$datos_servicio->descripcion_upss:(isset($datos_upss_ambulatorio->descripcion_upss) ? $datos_upss_ambulatorio->descripcion_upss : '');?></p>
                              </div>
                              <div class="col-md-3">
                                  <label for="tipo">NOMBRE DE LA IPRESS</label>
                                  <p><?php echo $prestacion->establecimiento?></p>
                              </div>
                          </div>
                        </div>
                        <!--FIN SEGMENTO DE DATOS DE LA ATENCION-->
                        <!--SEGMENTO DE DATOS DE INFORMACION DEL PACIENTE -->
                        <div class="panel panel-success">
                              <div class="panel-heading">INFORMACIÓN DEL PACIENTE <a href="javascript:void(0)" class="btn btn-xs btn-success pull-right" onclick="mostrarModalInfoPaciente2()">VER</a></div>
                              <div class="panel-body">
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">DOCUMENTO DE IDENTIDAD</label>
                                          <p><?php echo $prestacion->nro_doc_ident?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">APELLIDO PATERNO</label>
                                          <p><?php echo $prestacion->asegurado_paterno?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">APELLIDO MATERNO</label>
                                          <p><?php echo $prestacion->asegurado_materno?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">NOMBRES</label>
                                          <p><?php echo $prestacion->asegurado_nombre?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">PARENTESCO</label>
                                          <p><?php echo $prestacion->parentesco?></p>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">F. DE NACIMIENTO</label>
                                          <p><?php echo $prestacion->fecha_nac?></p>
                                      </div>
                                      <div class="col-md-1">
                                          <label for="">CIP</label>
                                          <p><?php echo $prestacion->cip?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">GRADO</label>
                                          <p><?php echo $prestacion->grado?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">SITUACION</label>
                                          <p><?php echo $prestacion->situacion_paciente?></p>
                                      </div>
                                      <div class="col-md-1">
                                          <label for="">SEXO</label>
                                          <p><?php echo $prestacion->sexo?></p>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">DIRECCIÓN</label>
                                          <p><?php echo $prestacion->domicilio?></p>
                                      </div>
                                  </div>
                              </div>
                        </div>
                        <!--FIN SEGMENTO DE DATOS DE INFORMACION DEL PACIENTE -->
                        <!--SEGMENTO DE DATOS DE RESPONSABLE DE ATENCION -->
                        <div class="panel panel-success">
                          <div class="panel-heading">INFORMACIÓN DEL RESPONSABLE DE LA ATENCION</div>
                          <div class="panel-body">
                              <div class="form-group">
                                  <div class="col-md-3">
                                      <label for="">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                      <p><?php echo $prestacion->tipo_doc_ident?></p>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="">N° DE DOCUMENTO DE IDENTIDAD</label>
                                      <p><?php echo $prestacion->dni?></p>
                                      </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-3">
                                      <label for="">APELLIDO PATERNO</label>
                                      <p><?php echo $prestacion->medico_paterno?></p>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="">APELLIDO MATERNO</label>
                                      <p><?php echo $prestacion->medico_materno?></p>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="">NOMBRES</label>
                                      <p><?php echo $prestacion->medico_nombre?></p>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-3">
                                      <label for="">PROFESIÓN</label>
                                      <p><?php echo $prestacion->profesion?></p>
                                  </div>
                                  <div class="col-md-2">
                                      <label for="">N° COLEGIATURA</label>
                                      <p><?php echo $prestacion->colegiatura?></p>
                                  </div>
                                  <div class="col-md-2">
                                      <label for="">N° RNE</label>
                                      <p><?php echo $prestacion->rne?></p>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="">ESPECIALIDAD</label>
                                      <p><?php echo $prestacion->especializacion?></p>
                                  </div>
                              </div>
                          </div>
                        </div>
                        <!--FIN SEGMENTO DE DATOS DE RESPONSABLE DE ATENCION -->
                        <!--SEGMENTO DE DATOS DE DX -->
                        <div class="row">
                          <div class="panel with-nav-tabs panel-success">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <!-- <li class="active"><a href="#tabAntecedentes" data-toggle="tab" style="font-size:12px">ANTECEDENTE</a></li>
                                    <li><a href="#tabAnamnesis" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                    <li><a href="#tabExamenClinico" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                                    <li><a href="#tabTratamiento" data-toggle="tab" style="font-size:12px">TRATAMIENTO Y/O TERAPEUTICA</a></li> -->
                                    <li  class="active"><a href="#tabDiagnosticos{{$id_intervencion}}" id="tabDiagnostico" data-toggle="tab" style="font-size:12px">DIAGNOSTICO</a></li>
                                    <li><a href="#tabProcedimientos{{$id_intervencion}}" id="tab_ProcedimientoX" data-toggle="tab" style="font-size:12px">PROCEDIMIENTO Y PLAN DE TRABAJO</a></li>
                                </ul>
                            </div>
                            <div class="tab-content">
                              <!-- <div class="tab-pane fade in active" id="tabAntecedentes">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label>ANTECEDENTE PERSONAL</label>
                                            <p><?php echo $prestacion->antecedente?></p>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="col-md-12">
                                          <label>ANTECEDENTE FAMILIAR</label>
                                          <p><?php echo $prestacion->antecedente_familiar?></p>
                                      </div>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tabAnamnesis">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">ANAMNESIS</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label>MOTIVO DE VISITA</label>
                                            <p><?php //echo $prestacion->motivo_visita?></p>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label>TIEMPO DE ENFERMEDAD</label>
                                            <p><?php //echo $prestacion->tiempo_enfermedad?></p>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label>RELATO CRONOLOGICO</label>
                                            <p><?php //echo $prestacion->anamnesis?></p>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tabExamenClinico">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">EXAMEN CLINICO</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                        <div class="col-md-12">
                                            <label>EXAMEN FISICO</label>
                                            <p><?php echo $prestacion->examen_fisico?></p>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                        <div class="col-md-12">
                                            <label>EXAMEN CLINICO</label>
                                            <p><?php echo $prestacion->examen_clinico?></p>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                              <label>EXAMENES AUXILIARES</label>
                                              <p><?php echo $prestacion->examen_auxiliar?></p>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tabTratamiento">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">TRATAMIENTO Y/O TERAPEUTICA</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label>TRATAMIENTO AUXILIAR</label>
                                            <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                                        </div>
                                    </div>
                                </div>
                              </div> -->
                              
                              <div class="tab-pane fade in active" id="tabDiagnosticos{{$id_intervencion}}">
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">DIAGNÓSTICOS</h3>
                                    </div>
                                    <fieldset>
                                      <div class="panel-body">
                                      @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                      <table>
                                        <tr>
                                          <td>
                                            <button type="button" id="addDiagnostico2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Diagnostico</button>
                                          </td>
                                        </tr>
                                      </table>
                                      @endif
                                      <br />
                                          <table style="width: 100%;" id="tblDiagnosticoDetalle" class="tbl_diagnostico">
                                              <tr>
                                                  <th width="20%">Codigo</th>
                                                  <th width="60%">Nombre</th>
                                                  <th width="10%">Tipo</th>
                                                  <th width="10%" style="padding-left:20px;">Prioridad</th>
                                              </tr>
                                              <?php $i = 1;?>
                                              @foreach($diagnostico as $key=> $row)
                                              <tr>
                                                  <?php $tipo = "";
                                                  switch($row->id_tipo_diagnostico):
                                                      case 1: $tipo = "PRESUNTIVO"; break;
                                                      case 2: $tipo = "DEFINITIVO"; break;
                                                      case 3: $tipo = "REITERATIVO"; break;
                                                  endswitch;
                                                  ?>
                                                  <?php $categoria = "";
                                                  switch($row->id_categoria):
                                                      case 1: $categoria = "PRINCIPAL"; break;
                                                      case 2: $categoria = "SECUNDARIO"; break;
                                                  endswitch;
                                                  ?>
                                                  <td style="width:20%; 1px solid gray;">
                                                  <input type="hidden" class="nuevo_id_diagnostico" id="id_diagnostico{{$key}}" value="{{ $row->id_diagnostico}}">
                                                  <input type="hidden" id="tipo_diag{{$key}}" value="{{ $tipo }}">
                                                  <input type="hidden" class="nuevo_nombre_diagnostico" id="descripcion{{$key}}" value="{{ $row->nombre }}">
                                                  {{ $row->codigo }}
                                                  </td>
                                                  <td style="width:60%; 1px solid gray;"><p>{{ $row->nombre }}</p></td>
                                                  <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                                  <td style="width:10%; 1px solid gray;">{{ $categoria }}</td>
                                                  <?php $i++; ?>
                                              </tr>
                                              @endforeach
                                          </table>
                                      </div>
                                    </fieldset>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tabProcedimientos{{$id_intervencion}}">
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">PROCEDIMIENTOS Y PLAN DE TRABAJO</h3>
                                    </div>
                                    <div class="panel-body">
                                      @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                      <table>
                                        <tr>
                                          <td>
                                            <button type="button" id="addProcedimiento2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Procedimiento</button>
                                          </td>
                                        </tr>
                                      </table>
                                      @endif
                                      <br />
                                      <table class="display" id="tblProcedimientoDetalle" cellspacing="0" width="100%" style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                              <th width="10%">codigo</th>
                                              <th width="40%">Nombre</th>
                                              <th width="10%">Cantidad</th>
                                              @if($id_prestacion>1910698)
                                                <th width="40%">diagnostico</th>
                                              @endif
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($procedimiento as $row)
                                            <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                                              <tr class="row2-{{ $row->cod }}">
                                                  <td>
                                                  <input type="hidden" class="id_diagnostico_general"  value="{{ $row->id_procedimiento }}">
                                                    <input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                  <td>
                                                      {{$row->nombre}}
                                                      <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                                  </td>
                                                  <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                  @if($id_prestacion>1910698)
                                                      <td>
                                                          <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly
                                                          value="<?php $model_diagnosticos = new \App\Models\Producto_receta;
                                                          $diagnostico_id = $row->id_diagnostico;
                                                          $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id);
                                                          echo $diagnostico_nombre;
                                                          ?>">
                                                      </td>
                                                  @endif
                                              </tr>
                                          @endforeach
                                        </tbody>
                                        </table>
                                      </div>
                                    <div class="panel-body">
                                      <div class="col-md-12" style="margin-left: -10px;">
                                          <label>PLAN DE TRABAJO</label>
                                          <p><?php echo $prestacion->plan_trabajo?></p>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--FIN SEGMENTO DE DATOS DE DX -->
                        <div class="panel panel-bordered panel-success">
                          <div class="panel-body">
                              <fieldset id="fsRecetaVale2">
                                <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>PRODUCTOS MEDICOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <!-- RECETA YA REGISTRADA PRE QUIRURGICO-->
                                  @if(!empty($receta))
                                      <?php
                                        $filtrado = array_filter($receta, function ($item) {return $item->flg_post_quirurgico != 1; });
                                        foreach($filtrado as $rowReceta):
                                              if ($rowReceta->fecha_firma == "") {$flag_firmar = 1; }
                                              $model_productos_receta = new \App\Models\Producto_receta;
                                              $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale_cqanestesia($rowReceta->id);
                                    ?>
                                    <input type="hidden" id="firma_receta_existente{{$rowReceta->id}}" name="firma_receta_existente[{{$rowReceta->id}}]" value="0">
                                    <fieldset id="{{$rowReceta->id}}">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="clearfix" style="margin-bottom:10px;">
                                                          @if( $rowReceta->estado==1)
                                                            <div style="margin: 0px 2px 5px 2px">
                                                              <div class="alert alert-success" role="alert" style="padding-left: 5px; font-weight: 800"><span><i class="fa fa-check" aria-hidden="true"></i> LA RECETA HA SIDO DISPENSADA EL {{ \Carbon\Carbon::parse($rowReceta->updated_at)->format('d-m-Y') }}</span></div>
                                                            </div>
                                                            <button class="btn btn-danger " disabled="disabled" type="button"><i class="fa fa-times"></i> Invalidar Receta</button>
                                                          @else
                                                            <a id="invalidarRecetaExistente{{ $rowReceta->id }}" href="javascript:void(0)"
                                                              onclick="invalidarReceta('invalidarRecetaExistente{{ $rowReceta->id }}','{{ $rowReceta->id }}','{{$rowReceta->id_farmacia}}')"
                                                              class="btn btn-danger">
                                                              <i class="fa fa-times" style="margin-left:10px;"></i> Invalidar Receta
                                                            </a>
                                                          @endif

                                                          @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null) {{-- Atencion no esta firmado --}}
                                                            @if ($rowReceta->estado=="2" && intval($rowReceta->flag) === 0) {{-- Receta esta registrada (No anulado ni dispensado) && receta no esta firmado --}}
                                                              @if ($is_habilitado_firma_electronica)
                                                                <button type="button" class="btn btn-info pull-right" onclick="firmarRecetaSinToken({{ $rowReceta->id }})" style="margin-left:20px">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                              @else
                                                                <a id="btnFirmarRecetaExistente" onclick="btnFirmarRecetaExistente(<?php echo $rowReceta->id;?>,<?php echo $rowReceta->nro_receta; ?>)" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                              @endif
                                                            
                                                            @elseif (intval($rowReceta->estado) === 2 && intval($rowReceta->flag) === 2) {{-- receta esta registrada (No anulado ni dispensado) && receta no esta firmado digitalmente --}}
                                                              <!-- PDF Receta -->
                                                              <a href="/{{$rowReceta->flag}}/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado" class="btn btn-xs"
                                                                style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado (E)
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                              </a>
                                                              <!-- Boton Firmar Digitalmente -->
                                                              <a id="btnFirmarRecetaExistente" onclick="btnFirmarRecetaExistente(<?php echo $rowReceta->id;?>,<?php echo $rowReceta->nro_receta; ?>)" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            @else
                                                                <!-- Receta registrado (2) o dispensado (1) && Receta firmado digitalmente -->
                                                                @if (intval($rowReceta->flag) === 1)
                                                                  <a href="/{{$rowReceta->flag}}/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado"
                                                                  class="btn btn-xs"
                                                                  style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                                  </a>
                                                                @endif
                                                            @endif
                                                          @else {{-- Atencion firmada --}}
                                                              @if(intval($rowReceta->flag) === 1 || intval($rowReceta->flag) === 2) {{-- Receta firmada digitalmente o electronicamente --}}
                                                                <a href="/{{$rowReceta->flag}}/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado"
                                                                class="btn btn-xs"
                                                                style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                                </a>
                                                                @if (intval($rowReceta->flag) === 2)
                                                                  <!-- Boton Firmar Digitalmente -->
                                                                  <a id="btnFirmarRecetaExistente" onclick="btnFirmarRecetaExistente(<?php echo $rowReceta->id;?>,<?php echo $rowReceta->nro_receta; ?>)" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                @endif
                                                              @endif
                                                          @endif
                                                        </div>
                                                        <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>

                                                        <div class="col-md-1">
                                                          <p><?php echo $rowReceta->nro_receta; ?></p>
                                                          <input type="hidden" value="{{$rowReceta->id}}" name="id_receta_vale_editar" id="id_receta_vale_editar" />
                                                        </div>
                                                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                        <div class="col-md-2">
                                                            <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?></p>
                                                        </div>
                                                        <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                        <input type="hidden" id="id_farmacia_receta1{{$id_intervencion}}" name="id_farmacia_receta1" value="<?php echo $rowReceta->id_farmacia; ?>">

                                                        <div class="col-md-4" style="padding:0px">
                                                            <p><?php echo $rowReceta->farmacia; ?></p>
                                                        </div>
                                                    </div>
                                                    @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)) {{-- Atencion no esta firmado --}}
                                                      @if ($rowReceta->estado != 1 && intval($rowReceta->flag) === 0) {{-- Receta no esta dispensado && receta no esta firmado --}}
                                                        <div class="col-md-12">
                                                          <button tabindex="75" type="button" onclick="agregarProductoRecetaExistente(<?php echo $rowReceta->id?>)" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">
                                                            Agregar Producto
                                                          </button>
                                                        </div>
                                                      @endif
                                                    @endif
                                                    <br />
                                                    <div style="display: none">
                                                      <select class="form-control" id="cmbMedicamentoTemp{{$id_intervencion}}" style="width: 500px">
                                                        <option value="">Seleccionar Producto</option>
                                                      </select>
                                                    </div>

                                                    <div  class="form-group" style="display: none">
                                                    <select class="form-control" id="diagnosticosTemp"
                                                        style="width: 500px">
                                                        <option value="">Seleccionar Diagnostico</option>
                                                      </select>
                                                    </div>

                                                </div>

                                                <table class="table table-bordered" id="tblMedicamentosRecetaExistente{{$rowReceta->id}}" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">Codigo</th>
                                                            <th width="30%">Producto farmacético o dispositivo médico</th>
                                                            <th width="10%">Stock Farmacia</th>
                                                            <th width="10%">Unidad Medida</th>
                                                            <th width="14%">Tiempo(Dias)</th>
                                                            <th width="16%">Posologia</th>
                                                            <th width="21%">Via Administración</th>
                                                            <th width="10%">Cantidad Prescrita</th>
                                                            <th width="15%">diagnosticos</th>
                                                            @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null))
                                                              @if ($rowReceta->estado !=1 && $rowReceta->flag != 1)
                                                              <th width="10%" colspan="2">
                                                                <div style="width: 100%; display: flex; justify-content: space-around">
                                                                  <span>Cancelar Acción</span>
                                                                  <button type="button" class="btn btn-danger btn-xs" style="width: auto;display:none;" onclick="restaurarColoresMedicamentos(<?php echo $rowReceta->id?>)">
                                                                    <i class="fa fa-times"></i>
                                                                  </button>
                                                                </div>
                                                              </th>
                                                              @else
                                                              @endif
                                                            @endif

                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody>
                                                    <?php foreach($medicamentos as $key=> $row):?>
                                                        <tr class="medicamentos_registrados">
                                                            <td>
                                                              <input type="hidden" class="cmbMedicamento<?php echo $rowReceta->id?>" value="{{ $row->id_producto }}"/>
                                                              <?php echo $row->codigo?>
                                                            </td>
                                                            <td><?php echo $row->nombre?>
                                                                <?php
                                                                    if($row->codigo=='59075700'):
                                                                    echo "(".$row->cantidad_prescrita."L)";
                                                                    endif;
                                                                ?>
                                                            </td>
                                                            <td class="campos_hidden">
                                                              <input type="hidden" value=""  id="accionRowProducto" name="accionRowProducto[]"/>
                                                              <input type="hidden" value=""  id="cantidad_prescrita_editar" name="cantidad_prescrita_editar[]"/>
                                                              <input type="hidden" value=""  id="posologia_editar" name="posologia_editar[]"/>
                                                              <input type="hidden" value=""  id="id_via_administracion_editar" name="id_via_administracion_editar[]"/>
                                                              <input type="hidden" value=""  id="tiempo_periodo_editar" name="tiempo_periodo_editar[]"/>
                                                            </td>
                                                            <td>
                                                                <?php echo $row->abreviatura;?>
                                                                <input name="id_producto_receta_editar[]" type="hidden" value="{{ $row->id_producto_receta }}"/>
                                                            </td>
                                                            <td><input onkeypress="return soloNumerosMenosCeroDetalle(event)" name="tiempo_periodo_editar[]" type="text" value="<?php echo $row->tiempo_periodo?>" disabled class="form-control tiempo_periodo_editar" style="height: 28px !important;font-weight: 800;" required/></td>
                                                            <td><input name="posologia_editar[]" type="text" value="<?php echo $row->posologia?>" disabled class="form-control posologia_editar" style="height: 28px !important;font-weight: 800;" required/></td>
                                                            <td>
                                                                <select name="id_via_administracion_editar[]" class="form-control id_via_administracion_editar" style="margin:0 20px;width:150px" disabled>
                                                                <option value="0">--Seleccionar--</option>
                                                                @foreach ($via_administracion as $va)
                                                                <option @if ($row->id_via_administracion == $va->id) selected @endif value="{{ $va->id }}">{{ $va->descripcion }}</option>
                                                                @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input onkeypress="return soloNumerosMenosCeroDetalle(event)" name="cantidad_prescrita_editar[]" type="text" value="<?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                                echo $row->cantidad_prescrita/1000;
                                                                else:
                                                                echo $row->cantidad_prescrita;
                                                                endif; ?>" class="form-control cantidad_prescrita_editar" disabled style="height: 28px !important;font-weight: 800;" required/>
                                                            </td>
                                                            <td><?php $model_productos_receta = new \App\Models\Producto_receta;
                                                                $diagnostico_id = $row->id_diagnosticos;
                                                                $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                                echo $diagnostico_nombre; ?>
                                                            </td>
                                                            @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null))
                                                              @if ($rowReceta->estado !=1 && $rowReceta->flag != 1)
                                                              <td>
                                                                <button type="button"  class="btn btn-primary btn-xs EditarFilaProducto" title="Editar" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                  <i class="fa fa-edit"></i>
                                                                </button>
                                                              </td>
                                                              <td>
                                                                <button type="button"  class="btn btn-danger AnularFilaProductos btn-xs" title="Anular" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                  <i class="fa fa-times"></i>
                                                                </button>
                                                              </td>
                                                              @else
                                                              @endif
                                                            @endif
                                                      </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>

                                              </div>
                                          </div>
                                    </fieldset>
                                    <?php endforeach;?>
                                  @endif
                                  <!-- FIN RECETA YA REGISTRADA -->

                                  <!-- NUEVA RECETA -->
                                   @if($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                  <!-- atencion no esta firmado -->
                                  <div class="col-md-12">
                                    @if($puede_atennder_servicios_cq)
                                        <a onclick="agregarReceta()" id="btnNuevoReceeta" flag="0" class="btn btn-success">
                                            Agregar Receta
                                        </a>
                                    @else
                                        <a class="btn btn-success" title="El servicio {{ $nombre_servicio }} no está en su lista de servicios de atención, por lo tanto no puede registrar una receta" disabled>
                                            Agregar Receta
                                        </a>
                                    @endif

                                    <div id="btnFirmarRecetaView" style="display:none;">
                                      <a id="btnFirmarRecetaAdicional" class="btn btn-info pull-right">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    </div>
                                  </div>
                                  @endif

                                  <div id="fsNuevaRecetaVale" style="display:none">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>FEC. EXPEDICIÓN (A)</label>
                                            <input type="hidden" name="receta_nuevo" value="1">
                                            <input type="text" class="form-control datepicker"
                                              value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion"
                                              id="fecha_expedicion" tabindex="2" autocomplete="off" />
                                          </div>
                                          <div class="col-md-5">
                                            <label>FARMACIA</label>
                                            <select class="form-control" name="id_farmacia_receta2" id="id_farmacia_receta2"
                                              style="width:100%" disabled>
                                              <?php foreach($farmacias as $row):?>
                                              <option value="<?php echo $row->id?>"
                                              <?php if($row->id==18) echo "selected='selected'"?>>
                                              <?php echo $row->nombre?></option>
                                              <?php endforeach;?>
                                            </select>
                                          </div>
                                        </div>
                                        <br />
                                        @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                        <table>
                                          <tr>
                                            <td>
                                              <button type="button" id="addProductoNuevo" class="btn btn-primary" data-toggle="modal"
                                                data-target="#addClassModal">Agregar Producto</button>
                                            </td>
                                          </tr>
                                        </table>
                                        @endif
                                        <br />
                                        <div style="display: none">
                                          <select class="form-control" id="cmbMedicamentoTempAdicionalAtencion{{$id_intervencion}}" style="width: 500px">
                                            <option value="">Seleccionar Producto</option>
                                          </select>
                                        </div>
                                        <div  class="form-group" style="display: none">
                                          <select class="form-control" id="diagnosticosTempAtencion"
                                            style="width: 500px">
                                            <option value="">Seleccionar Diagnostico</option>
                                          </select>
                                        </div>
                                        <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                        <div>
                                        <table id="tblMedicamentosAdicionalAtencion" class="table table-bordered" cellspacing="0" width="100%">
                                          <thead>
                                            <tr>
                                                <th width="30%">Producto farmacético o dispositivo médico</th>
                                                <th width="10%">Stock Farmacia</th>
                                                <th width="14%">Tiempo(Dias)</th>
                                                <th width="16%">Posologia</th>
                                                <th width="21%">Via Administración</th>
                                                <th width="10%">Cantidad Prescrita</th>
                                                <th width="15%">diagnosticos</th>
                                                <th width="10%"></th>
                                            </tr>
                                          </thead>
                                          <tfoot>
                                          </tfoot>
                                          <tbody>
                                          </tbody>
                                        </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                    <!-- FIN NUEVA RECETA -->
                                </div>
                              </fieldset>
                          </div>
                        </div>
                        
                        <div class="panel panel-bordered panel-success">
                          <div class="panel-heading">REGISTRAR PROGRAMACIÓN QUIRÚRGICA</div>
                            <div class="panel-body">
                              <!-- $atencion_emergencia trae los tipos de atencion inicial -->
                              @if(!empty($atencion_emergencia) && $atencion_emergencia->id_tipo_atencion==1)
                              <div class="form-group">
                                  <div class="col-sm-2">
                                      <label for="">TURNOS QUIRURGICOS</label>
                                  </div>
                                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                  <div class="col-md-2">
                                      <!-- <select name="id_prioridad_programacion" id="id_prioridad_programacion" class="form-control input-sm">
                                          <option value="0">Seleccione</option>
                                          @foreach ($cqx_dias as $row)
                                              @if ($row->flag == 1)
                                              <option value="{{ $row->id }}" @if($intervencion->id_prioridad_programacion==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                              @endif
                                          @endforeach
                                      </select> -->
                                  </div>
                                  @else
                                  <div class="col-md-2">
                                      <p>{{!empty($intervencion->id_prioridad_programacion)?$intervencion->id_prioridad_programacion:0}}</p>
                                  </div>
                                  @endif
                              </div>
                              <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>REQUIERE INTERNAMIENTO PRE QX</label>
                                  </div>
                                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="100" type="button" id="addRequiereInternamientoPreQx" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                      <input type="hidden" id="has_requiere_internamiento_preqx" name="has_requiere_internamiento_preqx" value="0">
                                  </div>
                                  <div class="col-md-2">
                                      <fieldset id="fsRequiereInternamientoPreQx">
                                          <select name="id_tiempo_internamiento_preqx" id="id_tiempo_internamiento_preqx" class="form-control input-sm">
                                              <option value="0">Seleccione</option>
                                              @foreach ($cqx_dias as $row)
                                                  @if ($row->flag == 2)
                                                  <option value="{{ $row->id }}" @if ($intervencion->is_requiere_internamiento_preqx) @if($intervencion->id_tiempo_internamiento_preqx==$row->id) selected @endif @endif>{{ $row->descripcion }}</option>
                                                  @endif
                                              @endforeach
                                          </select>
                                      </fieldset>
                                  </div>
                                  @else
                                      @if ($intervencion->is_requiere_internamiento_preqx)
                                      <div class="col-md-2"><p>SI</p></div>
                                      <div class="col-md-2"><p>{{ $intervencion->tiempo_internamiento_preqx }}</p></div>
                                      @else
                                      <div class="col-md-2">NO</div>
                                      @endif
                                  @endif
                              </div>
                              <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>DIAS DE HOSPITALIZADO POST QX</label>
                                  </div>
                                  @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="100" type="button" id="addDiasHospitalizadoPostQx" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      <input type="hidden" id="has_requiere_hospitalizacion_postqx" name="has_requiere_hospitalizacion_postqx" value="0">
                                  </div>
                                  <div class="col-sm-2">
                                      <fieldset id="fsDiasHospitalizadoPostQx">
                                          <select name="id_tiempo_hospitalizacion_postqx" id="id_tiempo_hospitalizacion_postqx" class="form-control">
                                              <option value="0">Seleccione</option>
                                              @foreach ($cqx_dias as $row)
                                                  @if ($row->flag == 3)
                                                  <option value="{{ $row->id }}" @if($intervencion->id_tiempo_hospitalizacion_postqx==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                                  @endif
                                              @endforeach
                                          </select>
                                      </fieldset>
                                  </div>
                                  @else
                                      @if ($intervencion->is_hospitalizacion_postqx)
                                      <div class="col-md-2">
                                          <p>SI</p>
                                      </div>
                                      <div class="col-md-2">
                                          <p>{{ $intervencion->tiempo_hospitalizacion_postqx }}</p>
                                      </div>
                                      @else
                                      <div class="col-md-2">
                                          <p>NO</p>
                                      </div>
                                      @endif
                                  @endif
                              </div>
                              <?php $lugar_operacion = "";
                                switch ($intervencion->id_lugar_operacion) {
                                  case 1: $lugar_operacion = "HOSPITALIZADO"; break;
                                  case 2: $lugar_operacion = "CLÍNICA DE DÍA"; break;
                              }
                              ?>
                              <div class="form-group">
                                  <div class="col-sm-12 col-md-6">
                                      <label for="">A SER OPERADO EN:</label><p> {{ $lugar_operacion }}</p>
                                  </div>
                                  <div class="col-sm-12 col-md-6">
                                      <label for="">FECHA PROBABLE DE INTERVENCIÓN:</label><p> {{ $intervencion->fecha_probable }}</p>
                                  </div>
                              </div>
                              <div class="form-group">
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>FECHA DE INTERVENCION</label>
                                </div>
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                  <input type="text" class="form-control datepicker input-sm" id="fecha_intervencion" name="fecha_intervencion">
                                </div>
                                @else
                                <div class="col-md-2">
                                  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                    <p>{{ $intervencion->fecha_probable ? $intervencion->fecha_probable : date('d-m-Y', strtotime($intervencion->fecha_intervencion)) }} </p>
                                  @else
                                    <input 
                                      tabindex="6" 
                                      type="datetime-local" 
                                      class="form-control"
                                      value="{{ \Carbon\Carbon::parse($intervencion->fecha_intervencion)->format('Y-m-d\TH:i') }}"
                                      name="fecha_intervencion" 
                                      id="fecha_intervencion" 
                                      max="{{ date('Y-m-d\TH:i') }}"
                                      style="text-align:left;">
                                  @endif
                                </div>
                                @endif
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>HORA DE INICIO</label>
                                </div>
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                  <input type="time" class="form-control input-sm" name="hora_inicio_intervencion" id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                                </div>
                                @else
                                <div class="col-md-2">
                                    <p>{{ date('H:i', strtotime($intervencion->fecha_intervencion)) }}</p>
                                </div>
                                @endif
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>HORA DE FIN</label>
                                </div>
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <input type="time" name="hora_inicio" class="form-control">
                                </div>
                                @else
                                <div class="col-md-2">
                                    <p>{{ date('H:i', strtotime($intervencion->fecha_fin_intervencion)) }}</p>
                                </div>
                                @endif
                              </div>
                              <div class="form-group">
                                <div class="col-md-2" style="margin-left:30px;">
                                  <label for="">SALA DE INTERVENCIÓN:</label>
                                </div>
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-md-2" style="margin-left:-10px;">
                                  <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                                    <option value="0">Seleccione</option>
                                    @foreach($cq_salas as $row)
                                    <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                    @endforeach
                                  </select>
                                </div>
                                @else
                                <div class="col-md-2">
                                    <p>{{ $intervencion->sala_intervencion }}</p>
                                </div>
                                @endif
                                <div class="col-md-2" style="margin-left:-11px;">
                                    <label for="">TIPO DE ANESTESIA:</label>
                                </div>
                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <div class="col-md-6">
                                    <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                                        <option value="0">Seleccione</option>
                                        @foreach($tipo_anestesia as $row)
                                        <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <div class="col-md-2">
                                    <p>{{ $intervencion->tipo_anestesia }}</p>
                                </div>
                                @endif
                              </div>
                              @else
                                  <div class="form-group">
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label for="">TIPO DE CIRUGIA:</label><p> </p>
                                      </div>
                                        <div class="col-md-2">
                                          <p>{{ $intervencion->tipo_cirugia }}</p>
                                      </div>
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label for="">TIEMPO DE DURACIÓN:</label><p> </p>
                                      </div>
                                      <div class="col-md-2">
                                          <?php

                                            $horas = 0;
                                            $horasFormateadas = '';

                                            if (isset($intervencion->emergencia_duracion_cirugia)) {

                                                // SIEMPRE VIENE EN MINUTOS
                                                $horas = $intervencion->emergencia_duracion_cirugia / 60;

                                                $horasFormateadas = rtrim(
                                                    rtrim(number_format($horas, 2, '.', ''), '0'),
                                                    '.'
                                                );
                                            }

                                            ?>

                                            <p>
                                                {{
                                                    $horasFormateadas != ''
                                                    ? $horasFormateadas . ' ' . ($horas == 1 ? 'hora' : 'horas')
                                                    : ''
                                                }}
                                            </p>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        <label>FECHA DE INTERVENCION</label>
                                    </div>
                                    <div class="col-md-2">v
                                        <p>{{ $intervencion->fecha_probable ? date('d-m-Y', strtotime($intervencion->fecha_probable)) : date('d-m-Y', strtotime($intervencion->created_at)) }} 
                                        </p>
                                    </div>
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        <label>HORA DE INICIO</label>
                                    </div>
                                    <div class="col-md-2">
                                        <p>{{ date('H:i', strtotime($intervencion->created_at)) }}</p>
                                    </div>
                                </div>
                              @endif

                            </div>
                        </div>
                        
                        <fieldset>
                          <div class="panel panel-bordered panel-success">
                            <div class="panel-heading">CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</div>
                            <div class="panel-body">
                              <table class="table table-bordered" cellspacing="0" width="100%" id="tblCirujano" cellspacing="0" width="100%" style="margin-top:5px;" >
                                  <thead>
                                  <tr>
                                      <th>Apellidos y Nombres</th>
                                      <th style="width:30%">Cargo</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                      @if(!empty($cirujanos))
                                          @foreach($cirujanos as $key => $row)
                                          <?php $isEliminadoCirujano = !empty(old('id_cirujano_anulado.' . $key)) || ($row->estado == 3);
                                          ?>
                                          <tr class="<?php echo $isEliminadoCirujano ? 'eliminado' : ''; ?>">
                                              <td>
                                              {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}
                                              </td>
                                              <td>
                                              {{$row->descripcion}}
                                              </td>
                                          </tr>
                                          @endforeach
                                      @endif
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </fieldset>
                        <fieldset>
                            <div class="panel panel-bordered panel-success">
                                <div class="panel-heading">PROCEDIMIENTOS PRE/POST OPERATORIO</div>
                                <div class="panel-body">
                                    <table class="table table-bordered" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th width="15%">CODIGO</th>
                                          <th width="50%">DESCRIPCION</th>
                                          <th width="15%">TIPO</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                        @foreach ($procedimientos_intervencion as $pintervencion)
                                        <tr>
                                          <td>{{$pintervencion->codigo}}</td>
                                          <td>{{$pintervencion->descripcion}}</td>
                                          <td>{{ $pintervencion->flag_pre_post == 1 ? 'Pre Operatorio' : 'Post Operatorio' }}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                        @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)       
                          <div class="panel panel-bordered panel-success">
                              <div class="panel-heading">REGISTRO DE INTRAOPERATORIO</div>
                              <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label for="">CLASIFICACIÓN DE CIRUGÍA</label>
                                    </div>
                                  
                                    <div class="col-md-2">
                                      <select  class="form-control" disabled>
                                          <option value="2" @if($ultimoRegistro->id_clasificacion_cirugia==2) selected @endif>CIRUGIA MENOR</option>
                                          <option value="3" @if($ultimoRegistro->id_clasificacion_cirugia==3) selected @endif>CIRUGIA MEDIANA</option>
                                          <option value="1" @if($ultimoRegistro->id_clasificacion_cirugia==1) selected @endif>CIRUGIA MAYOR</option>
                                      </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">TIPO DE CIRUGÍA</label>
                                    </div>
                                    <div class="col-md-2">
                                      <select class="form-control" disabled>
                                          @foreach ($tipo_cirugias as $row)
                                          <option value="{{ $row->id }}" @if($intervencion->emergencia_tipo_cirugia==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-2">
                                        <label for="">TIPO DE ANESTESIA</label>
                                    </div>
                                    <div class="col-md-2">
                                      @if(!empty($intraoperatorio))
                                        <p>{{ $intraoperatorio->descripcion }}</p>
                                      @endif
                                    </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">INICIO DE ANESTESIA</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->anestesia_inicio }}</p>
                                        @endif
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TÉRMINO DE ANESTESIA</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->anestesia_termino }}</p>
                                        @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">INICIO DE CIRUGÍA</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->cirugia_inicio }}</p>
                                        @endif
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TÉRMINO DE CIRUGÍA</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->cirugia_termino }}</p>
                                        @endif
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TIEMPO REAL</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->tipo_real }}</p>
                                        @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">SALIDA DE PACIENTE</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->salida_paciente }}</p>
                                        @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">HORA LLEGADA URPA</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                            <p>{{ $intraoperatorio->hora_llegada_urpa }}</p>
                                        @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">COMPLICACIÓN</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($intraoperatorio))
                                          <p>{{ $intraoperatorio->complicaciones }}</p>
                                        @endif
                                      </div>
                                  </div>
                              </div>
                          </div>
                        @else
                          <div class="panel panel-bordered panel-info">
                              <div class="panel-heading">REGISTRO DE INTRAOPERATORIO</div>
                              <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label for="">CLASIFICACION DECIRUGIA</label>
                                    </div>
                                    <div class="col-md-2">
                                      <select id="id_clasificacion_cirugia" name="id_clasificacion_cirugia" class="form-control" >
                                          <option value="2" @if($ultimoRegistro->id_clasificacion_cirugia==2) selected @endif>CIRUGIA MENOR</option>
                                          <option value="3"  @if($ultimoRegistro->id_clasificacion_cirugia==3) selected @endif>CIRUGIA MEDIANA</option>
                                          <option value="1"  @if($ultimoRegistro->id_clasificacion_cirugia==1) selected @endif>CIRUGIA MAYOR</option>
                                      </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">TIPO DE CIRUGÍA</label>
                                    </div>
                                    <div class="col-md-2">
                                      <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                          @foreach ($tipo_cirugias as $row)
                                          <option value="{{ $row->id }}" @if($intervencion->emergencia_tipo_cirugia==$row->id) selected @endif>{{ $row->descripcion }}</option>
                                          @endforeach
                                      </select> 
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <div class="col-md-2">
                                        <label for="">TIPO DE ANESTESIA</label>
                                    </div>
                                    <div class="col-md-2">
                                      <select id="id_cqx_tipo_anestesia" name="id_cqx_tipo_anestesia2" class="select-tom" @if(!isset($intraoperatorio->anestesia_tipo_id)) disabled @endif>
                                          <option value="">Seleccionar</option>
                                          @if(!empty($intraoperatorio))
                                          @foreach($tipo_anestesia as $tipo)
                                              <option value="{{ $tipo->id }}" {{ $tipo->id == $intraoperatorio->id ? 'selected' : '' }}>
                                                  {{ $tipo->descripcion }}
                                              </option>
                                          @endforeach
                                          @else
                                          @foreach($tipo_anestesia as $tipo)
                                              <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                          @endforeach
                                          @endif
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">INICIO DE ANESTESIA</label>
                                      </div>
                                      <div class="col-md-2">
                                          <input type="time" name="anestesia_inicio2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->anestesia_inicio;?>@endif" @if(!isset($intraoperatorio->anestesia_inicio)) disabled @endif>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TÉRMINO DE ANESTESIA</label>
                                      </div>
                                      <div class="col-md-2">
                                          <input type="time" name="anestesia_termino2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->anestesia_termino;?>@endif" @if(!isset($intraoperatorio->anestesia_termino)) disabled @endif>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">INICIO DE CIRUGÍA</label>
                                      </div>
                                      <div class="col-md-2">
                                          @if(!empty($datos_intervencion->hora_inicio_operatorio) || empty($intraoperatorio->cirugia_inicio))
                                          <input type="time" id="hora_inicio_operatorio2" name="hora_inicio_operatorio" class="form-control" value="{{ !empty($datos_intervencion->hora_inicio_operatorio)?$datos_intervencion->hora_inicio_operatorio: ''}}">
                                          @else
                                          <input type="time" id="cirugia_inicio3" name="cirugia_inicio2" class="form-control"  value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->cirugia_inicio;?>@endif" @if(!isset($intraoperatorio->cirugia_inicio)) disabled @endif>
                                          @endif
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TÉRMINO DE CIRUGÍA</label>
                                      </div>
                                      <div class="col-md-2">
                                         @if(!empty($datos_intervencion->hora_fin_operatorio) || empty($intraoperatorio->cirugia_termino))
                                          <input type="time" id="hora_fin_operatorio2" name="hora_fin_operatorio" class="form-control" value="{{!empty($datos_intervencion->hora_fin_operatorio)?$datos_intervencion->hora_fin_operatorio:''}}">
                                          @else
                                          <input type="time" id="cirugia_termino3" name="cirugia_termino2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->cirugia_termino;?>@endif" @if(!isset($intraoperatorio->cirugia_termino)) disabled @endif>
                                          @endif
                                      </div>
                                      <div class="col-md-2">
                                          <label for="">TIEMPO REAL</label>
                                      </div>
                                      <div class="col-md-2">
                                        @if(!empty($datos_intervencion->hora_duracion_operatorio) || empty($intraoperatorio->tipo_real))
                                          <input type="time" id="hora_duracion_operatorio_calculada2" name="hora_duracion_operatorio_calculada" value="{{!empty($datos_intervencion->hora_duracion_operatorio)?$datos_intervencion->hora_duracion_operatorio:0}}" class="form-control" readonly>
                                        @else
                                          <input type="time" id="tipo_real2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->tipo_real;?>@endif" @if(!isset($intraoperatorio->tipo_real)) disabled @endif>
                                        @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <!-- <div class="col-md-2">
                                          <label for="">INICIO DE PACIENTE</label>
                                      </div> -->
                                      <!-- <div class="col-md-2">
                                          <input type="time" name="inicio_paciente2" class="form-control" value="@if(!empty($intraoperatorio))<?php //echo $intraoperatorio->inicio_paciente;?>@endif" @if(!isset($intraoperatorio->inicio_paciente)) disabled @endif>
                                      </div> -->
                                      <div class="col-md-2">
                                          <label for="">SALIDA DE PACIENTE</label>
                                      </div>
                                      <div class="col-md-2">
                                          <input type="time" name="salida_paciente2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->salida_paciente;?>@endif" @if(!isset($intraoperatorio->salida_paciente)) disabled @endif>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">HORA LLEGADA URPA</label>
                                      </div>
                                      <div class="col-md-2">
                                          <input type="time" name="hora_llegada_urpa2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->hora_llegada_urpa;?>@endif" @if(!isset($intraoperatorio->hora_llegada_urpa)) disabled @endif>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="">COMPLICACIÓN</label>
                                      </div>
                                      <div class="col-md-2">
                                        <input type="radio" name="complicacion"  value="si" id="complicacionSi"
                                          @if(empty($intraoperatorio)) disabled @endif
                                          @if(!empty($intraoperatorio) && !empty($intraoperatorio->complicaciones)) checked @endif
                                      > SÍ
                                      </div>
                                      <div class="col-md-2">
                                          
                                        <input type="radio" name="complicacion" value="no" id="complicacionNo" 
                                        @if(empty($intraoperatorio)) disabled @endif 
                                        @if(empty($intraoperatorio->complicaciones)) checked @endif> NO
                                      </div>
                                      <div class="col-md-4" id="complicacionDescripcion">
                                          <label for="">DESCRIPCIÓN DE LA COMPLICACIÓN</label>
                                          <textarea class="form-control" id="complicaciones" name="complicaciones" rows="4" cols="50" @if(!isset($intraoperatorio->complicaciones)) disabled @endif>@if(!empty($intraoperatorio)){{ $intraoperatorio->complicaciones }}@endif</textarea>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        @endif
                        
                        <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>DESCRIPCIÓN DE PROCEDIMIENTO</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                 @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                  <button tabindex="110" type="button"  class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                 @else
                                  <button tabindex="110" type="button" id="addDescripcionProcedimiento" class="btn btn-lg btn-toggle {{ $ultimoRegistro->tiene_descripcion_procedimiento == 1 ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_descripcion_procedimiento == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                 @endif
                              </div>
                            </div>
                            <div id="fsDescripcionProcedimiento" style="{{ (isset($ultimoRegistro->tiene_descripcion_procedimiento) && $ultimoRegistro->tiene_descripcion_procedimiento) ? '' : 'display: none;' }}">
                              <fieldset class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group">
                                  <div class="col-md-12">
                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                        <textarea class="form-control" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->descripcion_procedimiento)?$ultimoRegistro->descripcion_procedimiento:''}}</textarea>
                                    @else
                                        <textarea class="form-control" id="descripcion_procedimiento" name="descripcion_procedimiento" rows="4" cols="50"> {{!empty($ultimoRegistro->descripcion_procedimiento)?$ultimoRegistro->descripcion_procedimiento:''}}</textarea>
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                        </div>

                        <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>HALLAZGOS OPERATORIO</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                 @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                  <button tabindex="110" type="button"  class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                 @else
                                  <button tabindex="110" type="button" id="addAllazgoOpe" class="btn btn-lg btn-toggle {{ $ultimoRegistro->tiene_hallazgo == 1 ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_hallazgo == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                 @endif
                              </div>
                            </div>
                            <div id="fsAllazgoOpe" style="{{ (isset($ultimoRegistro->tiene_hallazgo) && $ultimoRegistro->tiene_hallazgo) ? '' : 'display: none;' }}">
                              <fieldset class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group">
                                  <div class="col-md-12">
                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                        <textarea class="form-control" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->allazgo_operatorio)?$ultimoRegistro->allazgo_operatorio:''}}</textarea>
                                    @else
                                        <textarea class="form-control" id="allazgo_operatorio" name="allazgo_operatorio" rows="4" cols="50"> {{!empty($ultimoRegistro->allazgo_operatorio)?$ultimoRegistro->allazgo_operatorio:''}}</textarea>
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                        </div>


                        <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>EXAMEN PATOLOGICO</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                 @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                  <button tabindex="110" type="button"  class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                 @else
                                  <button tabindex="110" type="button" id="addExamenPatologico2" class="btn btn-lg btn-toggle {{ $ultimoRegistro->tiene_examen_pat == 1 ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_examen_pat == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                 @endif
                              </div>
                            </div>
                            <div id="fsExamenPatologico" style="{{ (isset($ultimoRegistro->tiene_examen_pat) && $ultimoRegistro->tiene_examen_pat) ? '' : 'display: none;' }}">
                              <fieldset class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="col-md-12">
                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                        <!-- <textarea class="form-control" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->examen_patologico)?$ultimoRegistro->examen_patologico:''}}</textarea> -->
                                    @else
                                      <div class="panel panel-info panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center"><b>PROCEDIMIENTO PATOLOGICO</b></div>
                                          </div>
                                          <div class="panel-body">
                                            <!--  -->
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                  <button tabindex="75" type="button" id="addProcedimientoExamenPatologia2" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                              <select id="cmbProcedimientoExamenPatologiaTemp2"></select>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-sm-12">
                                                <table class="display" id="table_procedimiento_examen_patologia2" cellspacing="0" width="100%" style="margin-top:5px;">
                                                  <thead>
                                                    <tr>
                                                      <th width="10%">codigo</th>
                                                      <th width="60%">Nombre</th>
                                                      <th width="30%"></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      @foreach($procedimientos_examen_patologia as $proc)
                                                          <tr>
                                                            <td> {{ $proc->codigo }}</td>
                                                            <td>{{ $proc->descripcion }}</td>
                                                            <td></td>
                                                          </tr>
                                                      @endforeach
                                                    </tbody>
                                                </table>
                                                
                                              </div>
                                            </div>
                                            <!--  -->
                                          </div>
                                        </div>
                                       <!-- <textarea class="form-control" id="examen_patologico" name="examen_patologico" rows="4" cols="50">{{!empty($ultimoRegistro->examen_patologico)?$ultimoRegistro->examen_patologico:''}}</textarea> -->
                                    @endif
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>PIEZA OPERATORIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                      <button tabindex="110" type="button"  class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                    @else
                                      <button tabindex="110" type="button" id="addPiezaOpe" class="btn btn-lg btn-toggle {{ $ultimoRegistro->tiene_pieza == 1 ? 'active' : '' }} " data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_pieza == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                    @endif
                                  </div>
                                </div>
                                <div id="fsPiezaOpe" style="{{ (isset($ultimoRegistro->tiene_pieza) && $ultimoRegistro->tiene_pieza) ? '' : 'display: none;' }}">
                                  <fieldset class="panel panel-info panel-bordered panel-sm">
                                  <div class="form-group">
                                      <div class="col-md-12">
                                        @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                            <textarea class="form-control" id="pieza_operatorio" name="pieza_operatorio" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->pieza_operatorio)?$ultimoRegistro->pieza_operatorio:''}}</textarea>
                                        @else
                                          <textarea class="form-control" id="pieza_operatorio" name="pieza_operatorio" rows="4" cols="50">{{!empty($ultimoRegistro->pieza_operatorio)?$ultimoRegistro->pieza_operatorio:''}}</textarea>
                                        @endif
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>

                              </fieldset>
                            </div>
                        </div>

                        <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>EXAMEN BACTERIOLOGICO</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                 @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                  <button tabindex="110" type="button" class="btn btn-lg btn-toggle active " data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                 @else
                                  <button tabindex="110" type="button" id="addExamenBacteriologico" class="btn btn-lg btn-toggle {{ isset($ultimoRegistro->tiene_examen_bact) && $ultimoRegistro->tiene_examen_bact ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_examen_bact == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                 @endif
                              </div>
                            </div>
                            <div id="fsExamenBacteriologico" style="{{ (isset($ultimoRegistro->tiene_examen_bact) && $ultimoRegistro->tiene_examen_bact) ? '' : 'display: none;' }}">
                              <fieldset class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group">
                                  <div class="col-md-12">
                                     @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                       <textarea class="form-control" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->examen_bacteriologico)?$ultimoRegistro->examen_bacteriologico:''}}</textarea>
                                    @else
                                      <textarea class="form-control" id="examen_bacteriologico" name="examen_bacteriologico" rows="4" cols="50">{{!empty($ultimoRegistro->examen_bacteriologico)?$ultimoRegistro->examen_bacteriologico:''}}</textarea>
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                        </div>
                        <div  class="panel panel-info panel-bordered panel-sm panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>Registro Médico (Complicaciones)</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                 <button tabindex="110" type="button" class="btn btn-lg btn-toggle active " data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                @else
                                  <button tabindex="110" type="button" id="addComplicacionMedica" class="btn btn-lg btn-toggle {{ isset($ultimoRegistro->tiene_complicacion_medica) && $ultimoRegistro->tiene_complicacion_medica ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $ultimoRegistro->tiene_complicacion_medica == 1 ? 'true' : 'false' }}" autocomplete="off"><div class="handle"></div></button>
                                @endif
                              </div>
                            </div>
                            <div id="complicacionMedica" style="{{ (isset($ultimoRegistro->tiene_complicacion_medica) && $ultimoRegistro->tiene_complicacion_medica) ? '' : 'display: none;' }}">
                              <fieldset class="panel panel-info panel-bordered panel-sm">
                              <div class="form-group">
                                  <div class="col-md-12">
                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                       <textarea class="form-control" rows="4" cols="50" disabled>{{!empty($ultimoRegistro->complicacion_medica)?$ultimoRegistro->complicacion_medica:''}}</textarea>
                                    @else
                                      <textarea class="form-control" id="complicacion_medica" name="complicacion_medica" rows="4" cols="50">{{!empty($ultimoRegistro->complicacion_medica)?$ultimoRegistro->complicacion_medica:''}}</textarea>
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                        </div>

                        <div class="panel panel-bordered panel-success">
                          <div class="panel-heading">REGISTRO DE DESTINO</div>
                          <div id="panelTraslado" class="panel-body">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>DESTINO</label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                 @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                  <button tabindex="110" type="button" class="btn btn-lg btn-toggle active " data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                 @else
                                    @if(!empty($id_hospitalizacion))
                                      <button tabindex="110" type="button" id="addDestino{{$id_intervencion}}" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                     @else
                                      <button tabindex="110" type="button"  class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                     @endif
                                 @endif

                              </div>
                            </div>

                              <div id="fsDestino{{$id_intervencion}}" style="display: none">
                                <fieldset class="panel panel-info panel-bordered panel-sm">
                                    <div class="row">
                                      <div class="col-md-4 col-xs-12" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                          <div class="col-md-6" style="padding-left:0px;"><label>DESTINO</label></div>
                                          <div class="col-md-6" style="padding-left:0px;">
                                            <select class="form-control" id="id_tipo_egreso{{$id_intervencion}}" name="id_tipo_egreso" style="width: 100%" onchange='cambiar_tipo_egreso()' @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                              <option value="" >SELECCIONAR</option>
                                              <option value="1" {{ !empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso == 1 ? 'selected' : '' }}>DOMICILIARIA</option>
                                              <!-- <option value="2" {{ !empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso == 2 ? 'selected' : '' }}>HOSPITALIZACIÓN</option> -->
                                              <option value="2"
                                                {{ 
                                                    (
                                                        (!empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso>2)
                                                            ? (!empty($datos_intervencion) && $datos_intervencion->id_tipo_egreso == 2)
                                                            : (!empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso == 2)
                                                    ) ? 'selected' : '' 
                                                }}>
                                                    HOSPITALIZACIÓN
                                                </option>
                                              <!-- <option value="3" >TRANSFERIDO</option>
                                              <option value="6"  >FUGA</option>
                                              <option value="4" >FALLECIDO</option>
                                              <option value="7" >RETIRO VOLUNTARIO</option> -->
                                            </select>
                                          </div>
                                      </div>
                                      <div class="col-md-4 col-xs-12" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                        <div   id="alta_condicion{{$id_intervencion}}"  style="display:none">
                                            <div class="col-md-6" style="padding-left:0px;"><label>CONDICION ALTA </label></div>
                                            <div class="col-md-6"  style="padding-left:0px;">
                                              <select class="form-control condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%"  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                                <option value="">Elegir</option>
                                                <option value="1" {{ !empty($hospitalizacion) && $hospitalizacion->id_condicion_alta == "1" ? 'selected' : '' }}>MEJORADO</option>
                                                <option value="2" {{ !empty($hospitalizacion) && $hospitalizacion->id_condicion_alta == "2" ? 'selected' : '' }}>CURADO</option>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group"  id="alta_condicion_de"  style="display:none">

                                              <label>CONDICION ALTA</label><br>

                                            <div class="col-sm-6" >
                                              <select class="form-control condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                <option >FALLECIDO</option>

                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group"  id="alta_condicion_ret"  style="display:none">

                                              <label>CONDICION ALTA</label><br>

                                            <div class="col-sm-6" >
                                              <select class="form-control condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                <option >RETIRO VOLUNTARIO</option>

                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group"  id="alta_condicion_fuga"  style="display:none">

                                              <label>CONDICION ALTA</label><br>

                                            <div class="col-sm-6" style="margin-left:0px;padding-left:0px">
                                              <select class="form-control condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                <option >FUGA</option>

                                              </select>
                                            </div>
                                        </div>
                                        <div class=""  id="alta_condicion_tra{{$id_intervencion}}"  style="display:none">
                                            <div class="col-md-6" style="padding-left:0px;"><label>CONDICION ALTA</label></div>
                                            <div class="col-sm-6" style="margin-left:0px;padding-left:0px">
                                              <select class="form-control condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                <option >TRASLADO</option> 
                                              </select>
                                              <input type="hidden" name="hospitalizacion_id_condicion_alta" value="3">
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div id="destino_hospitalizacion{{$id_intervencion}}" style="display: none;">
                                        <div class="col-md-3" style="padding-top:0px;margin-left:0px;margin-right:0px;padding-right:0px">
                                          <label>SERVICIO DE HOSPITALIZCION / UPSS</label>
                                        </div>
                                        <div class="col-sm-8" style="margin-left:0px;padding-left:0px">
                                          <select class="select-tom" id="id_upss_encargado"  name="id_upss_encargado" style="width: 100%"  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                              <option value="">Elegir</option>
                                              @if(!empty($upss))
                                              <?php
                                                foreach($upss as $row):
                                              ?>
                                              <option value="<?php echo $row->id_upss?>" <?php if($row->id_consultorio==$id_servicio_encargado): echo 'selected'; endif; ?> data-id_servicio_encargado="<?php echo $row->id_consultorio;?>"> <?php echo $row->nombre ?> <?php echo $row->id_upss?></option>
                                              <?php endforeach; ?>
                                              @endif

                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>PABELLÓN/PISO 
                                            </label>
                                            <select name="id_pabellon_destino" id="id_pabellon_destino" class="select-tom" style="width:100%" >
                                              <option value="0">--SELECCIONAR--</option>
                                              @foreach ($pabellones as $row)
                                              <option value="{{ $row->id }}"
                                                {{ isset($hospitalizacion) && !empty($hospitalizacion->id_pabellon) && $hospitalizacion->id_pabellon == $row->id ? 'selected' : '' }}>
                                                {{ $row->pabellon }}
                                            </option>
                                              @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="id_pabellon_actual" value="{{isset($hospitalizacion) && !empty($hospitalizacion->id_pabellon)?$hospitalizacion->id_pabellon:''}}">
                                        
                                        <div class="col-md-3">
                                          <label for="">SALA DESTINO</label>
                                          <select id="id_area_destino" name="id_area_destino"class="select-tom" style="width:100%" onchange="getCamas();"  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                            <option value="">SELECCIONAR</option>
                                            @if(!empty($cuartos))
                                            @foreach($cuartos as $e)
                                              <option value="{{$e->id}}" @if(isset($dato_cama->id_cuarto) && $e->id == $dato_cama->id_cuarto) selected @endif>{{$e->pabellon}} - {{$e->nombre}}</option>
                                            @endforeach
                                            @endif
                                          </select>
                                        </div>


                                        <div class="col-md-3" >
                                          <label>CAMA</label>
                                          <select id="id_cama_destino" name="id_cama_destino" class="select-tom" style="width:100%"  @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                            <option value="">SELECCIONAR</option>
                                            @if(!empty($camas))
                                            @foreach($camas as $e)
                                              <option value="{{$e->id}}" @if($e->id == $hospitalizacion->id_cama_estado) selected @endif>{{$e->nombre}}</option>
                                            @endforeach
                                            @endif
                                          </select>
                                        </div>
                                      </div>
                                      <div id="destino_domiciliaria{{$id_intervencion}}" style="display: none;">
                                        <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                          <label>FECHA EGRESO</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <input 
                                                  type="date" 
                                                  class="form-control"
                                                  name="fecha_egreso" 
                                                  id="fecha_egreso"
                                                  onchange="validarFechaEgreso();validarFechaRecetaPostQX();" 
                                                  value="{{ $v_Fecha_por_Defecto }}" 
                                                  style="text-align:left;" data-fecha_valor="{{ $v_Fecha_por_Defecto }}"
                                                  data-fecha-ingreso="<?php echo date('d-m-Y', strtotime(!empty($hospitalizacion->fecha_alta_medica)?$hospitalizacion->fecha_ingreso:$datos_intervencion->fecha_derivacion)); ?>" @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif>
                                        </div>
                                        <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                          <label>HORA EGRESO</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <input type="time" class="form-control"
                                          title="Formato válido: HH:mm:ss (por ejemplo, 14:30:45)" step="1" value="<?php echo date("G:i:s", strtotime($horaEgreso)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left"   @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2) disabled @endif/>
                                        </div>
                                      </div>
                                    </div>
                                    <br>
                                    <!-- RECETA POST QUIRURGICA - RECETA EXISTENTE -->
                                    @if(!empty($receta))
                                        <?php
                                          $filtrado_Receta_PostQ = array_filter($receta, function ($item) {return $item->flg_post_quirurgico == 1; });
                                        ?>
                                        @if(!empty($filtrado_Receta_PostQ))
                                          <div class="panel panel-bordered panel-info" style="{{ !empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso == 1 ? '' : 'display:none'}}">
                                              <div class="panel-heading">RECETA POST QUIRURGICA </div>
                                              <div class="panel-body">
                                                @foreach($filtrado_Receta_PostQ as $rowReceta)
                                                  <?php
                                                      if ($rowReceta->fecha_firma == "") {$flag_firmar = 1; }
                                                      $model_productos_receta = new \App\Models\Producto_receta;
                                                      $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale_cqanestesia($rowReceta->id);
                                                  ?>
                                                  <input type="hidden" id="firma_receta_existente{{$rowReceta->id}}" name="firma_receta_existente[{{$rowReceta->id}}]" value="0">
                                                  <fieldset id="{{$rowReceta->id}}">
                                                      <div class="panel panel-default panel-bordered panel-sm">
                                                          <div class="panel-heading">
                                                              <label   style="padding-left:0px">RECETA VALE N ° {{ $rowReceta->nro_receta }}</label>
                                                              <div class="pull-right">
                                                                 @if( $rowReceta->estado==1)
                                                                    <button class="btn btn-danger btn-sm" disabled="disabled" type="button"><i class="fa fa-times"></i> Invalidar Receta</button>
                                                                 @else
                                                                    <a id="invalidarRecetaExistente{{ $rowReceta->id }}" href="javascript:void(0)" onclick="invalidarReceta('invalidarRecetaExistente{{ $rowReceta->id }}','{{ $rowReceta->id }}','{{$rowReceta->id_farmacia}}')"class="btn btn-danger btn-sm">
                                                                     <i class="fa fa-times" style="margin-left:10px;"></i> Invalidar Receta
                                                                    </a>
                                                                 @endif
                                                                 @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                                                    @if($rowReceta->estado=="2" && $rowReceta->flag!=1)
                                                                     <a id="btnFirmarRecetaExistente" onclick="btnFirmarRecetaExistente(<?php echo $rowReceta->id;?>,<?php echo $rowReceta->nro_receta; ?>)" class="btn btn-info btn-sm ">FIRMAR <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                    @else
                                                                       @if($rowReceta->flag==1)
                                                                        <a href="/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado" class="btn btn-sm" style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                                          <i class="fa fa-pencil" aria-hidden="true"></i><i class="fa fa-download" aria-hidden="true"></i>
                                                                        </a>
                                                                       @endif
                                                                    @endif
                                                                 @else
                                                                     @if($rowReceta->flag==1)
                                                                        <a href="/{{$rowReceta->ruta_receta}}" target="_blank" data-toggle="tooltip" title="Receta PDF firmado" class="btn btn-sm" style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">Receta PDF firmado
                                                                          <i class="fa fa-pencil" aria-hidden="true"></i> <i class="fa fa-download" aria-hidden="true"></i>
                                                                        </a>
                                                                     @endif
                                                                 @endif
                                                              </div>
                                                          </div>
                                                          <div class="panel-body">
                                                              <div class="form-group">
                                                                  <div class="col-md-12">
                                                                      <div class="clearfix" style="margin-bottom:10px;">
                                                                        @if( $rowReceta->estado==1)
                                                                          <div style="margin: 0px 2px 5px 2px">
                                                                            <div class="alert alert-success" role="alert" style="padding-left: 5px; font-weight: 800"><span><i class="fa fa-check" aria-hidden="true"></i> LA RECETA HA SIDO DISPENSADA EL {{ \Carbon\Carbon::parse($rowReceta->updated_at)->format('d-m-Y') }}</span></div>
                                                                          </div>
                                                                        @endif
                                                                      </div> 
                                                                      <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                                      <div class="col-md-2">
                                                                          <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_expedicion)); ?></p>
                                                                      </div>
                                                                      <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                                      <input type="hidden" id="id_farmacia_receta1{{$id_intervencion}}" name="id_farmacia_receta1" value="<?php echo $rowReceta->id_farmacia; ?>">
                                                                      <div class="col-md-4" style="padding:0px">
                                                                          <p><?php echo $rowReceta->farmacia; ?></p>
                                                                      </div>
                                                                  </div>
                                                                  @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null))
                                                                    @if ($rowReceta->estado != 1 && $rowReceta->flag != 1)
                                                                      <div class="col-md-12">
                                                                        <button tabindex="75" type="button" onclick="agregarProductoRecetaExistente(<?php echo $rowReceta->id?>)" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">
                                                                          Agregar Producto
                                                                        </button>
                                                                      </div>
                                                                    @endif
                                                                  @endif
                                                                  <br />
                                                                  <div style="display: none">
                                                                    <select class="form-control" id="cmbMedicamentoTemp{{$id_intervencion}}" style="width: 500px">
                                                                      <option value="">Seleccionar Producto</option>
                                                                    </select>
                                                                  </div>
                                                                  <div  class="form-group" style="display: none">
                                                                  <select class="form-control" id="diagnosticosTemp"
                                                                      style="width: 500px">
                                                                      <option value="">Seleccionar Diagnostico</option>
                                                                    </select>
                                                                  </div>
                                                              </div>
                                                              <table class="table table-bordered" id="tblMedicamentosRecetaExistente{{$rowReceta->id}}" cellspacing="0" width="100%">
                                                                  <thead>
                                                                      <tr>
                                                                          <th width="10%">Codigo</th>
                                                                          <th width="30%">Producto farmacético o dispositivo médico</th>
                                                                          <th width="10%">Stock Farmacia</th>
                                                                          <th width="10%">Unidad Medida</th>
                                                                          <th width="14%">Tiempo(Dias)</th>
                                                                          <th width="16%">Posologia</th>
                                                                          <th width="21%">Via Administración</th>
                                                                          <th width="10%">Cantidad Prescrita</th>
                                                                          <th width="15%">diagnosticos</th>
                                                                          @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null))
                                                                            @if ($rowReceta->estado !=1 && $rowReceta->flag != 1)
                                                                            <th width="10%" colspan="2">
                                                                              <div style="width: 100%;  justify-content: space-around">
                                                                                <span>Cancelar Acción</span>
                                                                                <button type="button" class="btn btn-danger btn-xs" style="width: auto;display: none;" onclick="restaurarColoresMedicamentos(<?php echo $rowReceta->id?>)">
                                                                                  <i class="fa fa-times"></i>
                                                                                </button>
                                                                              </div>
                                                                            </th>
                                                                            @else
                                                                            @endif
                                                                          @endif
                                                                      </tr>
                                                                  </thead>
                                                                  <tfoot>
                                                                  </tfoot>
                                                                  <tbody>
                                                                  <?php foreach($medicamentos as $key=> $row):?>
                                                                      <tr class="medicamentos_registrados">
                                                                          <td>
                                                                            <input type="hidden" class="cmbMedicamento<?php echo $rowReceta->id?>" value="{{ $row->id_producto }}"/>
                                                                            <?php echo $row->codigo?>
                                                                          </td>
                                                                          <td><?php echo $row->nombre?>
                                                                              <?php
                                                                                  if($row->codigo=='59075700'):
                                                                                  echo "(".$row->cantidad_prescrita."L)";
                                                                                  endif;
                                                                              ?>
                                                                          </td>
                                                                          <td class="campos_hidden">
                                                                            <input type="hidden" value=""  id="accionRowProducto" name="accionRowProducto[]"/>
                                                                            <input type="hidden" value=""  id="cantidad_prescrita_editar" name="cantidad_prescrita_editar[]"/>
                                                                            <input type="hidden" value=""  id="posologia_editar" name="posologia_editar[]"/>
                                                                            <input type="hidden" value=""  id="id_via_administracion_editar" name="id_via_administracion_editar[]"/>
                                                                            <input type="hidden" value=""  id="tiempo_periodo_editar" name="tiempo_periodo_editar[]"/>
                                                                          </td>
                                                                          <td>
                                                                              <?php echo $row->abreviatura;?>
                                                                              <input name="id_producto_receta_editar[]" type="hidden" value="{{ $row->id_producto_receta }}"/>
                                                                          </td>
                                                                          <td><input onkeypress="return soloNumerosMenosCeroDetalle(event)" name="tiempo_periodo_editar[]" type="text" value="<?php echo $row->tiempo_periodo?>" disabled class="form-control tiempo_periodo_editar" style="height: 28px !important;font-weight: 800;" required/></td>
                                                                          <td><input name="posologia_editar[]" type="text" value="<?php echo $row->posologia?>" disabled class="form-control posologia_editar" style="height: 28px !important;font-weight: 800;" required/></td>
                                                                          <td>
                                                                              <select name="id_via_administracion_editar[]" class="form-control id_via_administracion_editar" style="margin:0 20px;width:150px" disabled>
                                                                              <option value="0">--Seleccionar--</option>
                                                                              @foreach ($via_administracion as $va)
                                                                              <option @if ($row->id_via_administracion == $va->id) selected @endif value="{{ $va->id }}">{{ $va->descripcion }}</option>
                                                                              @endforeach
                                                                              </select>
                                                                          </td>
                                                                          <td><input onkeypress="return soloNumerosMenosCeroDetalle(event)" name="cantidad_prescrita_editar[]" type="text" value="<?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                                              echo $row->cantidad_prescrita/1000;
                                                                              else:
                                                                              echo $row->cantidad_prescrita;
                                                                              endif; ?>" class="form-control cantidad_prescrita_editar" disabled style="height: 28px !important;font-weight: 800;" required/>
                                                                          </td>
                                                                          <td><?php $model_productos_receta = new \App\Models\Producto_receta;
                                                                              $diagnostico_id = $row->id_diagnosticos;
                                                                              $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                                              echo $diagnostico_nombre; ?>
                                                                          </td>
                                                                          @if(($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null))
                                                                            @if ($rowReceta->estado !=1 && $rowReceta->flag != 1)
                                                                            <td>
                                                                              <button type="button"  class="btn btn-primary btn-xs EditarFilaProducto" title="Editar" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                                <i class="fa fa-edit"></i>
                                                                              </button>
                                                                            </td>
                                                                            <td>
                                                                              <button type="button"  class="btn btn-danger AnularFilaProductos btn-xs" title="Anular" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                                                <i class="fa fa-times"></i>
                                                                              </button>
                                                                            </td>
                                                                            @else
                                                                            @endif
                                                                          @endif
                                                                    </tr>
                                                                  <?php endforeach;?>
                                                                  </tbody>
                                                              </table>
                                                            </div>
                                                        </div>
                                                  </fieldset>
                                                  @endforeach
                                              </div>
                                          </div>
                                        @endif
                                    @endif
                                    <!-- FIN RECETA POST QUIRURGICA - RECETA EXISTENTE -->
                                    <!-- SEGMENTO DE RECETA POST QUIRURGICO - NUEVA RECETA -->
                                    @if($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                      <div class="col-md-12">
                                          <a onclick="agregarRecetaPostQuirurgica()" id="btnNuevoReceetaPostQuirurgico" 
                                              data-status_atencion="{{ $puede_atennder_servicios_cq==true ? 1 : 0 }}" data-msje="{{$puede_atennder_servicios_cq!=true ? 'El servicio ' . (($servicio) ? $servicio->nombre : '') . ' no está en su lista de servicios de atención, por lo tanto no puede registrar una receta' : '' }}" flag="0" style="{{ !empty($hospitalizacion) && $hospitalizacion->id_tipo_egreso == 1 ? '' : 'display:none'}}" class="btn btn-success"> Agregar Receta</a>
                                      </div>
                                    @endif

                                    <div id="fsNuevaRecetaValePostQuirurgico2"  style="display:none">
                                      <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-body">
                                                <div class="form-group">
                                                      <div class="col-md-2">
                                                          <label>FEC. EXPEDICIÓN (A) </label>
                                                          <input  type="date"  class="form-control  "
                                                                  value="{{ $v_Fecha_por_Defecto }}" data-fecha_valor="{{ $v_Fecha_por_Defecto }}"
                                                                    name="fecha_expedicion_postqx"
                                                                    id="fecha_expedicion_postqx"
                                                                    tabindex="2"
                                                                    autocomplete="off" />
                                                      </div>
                                                      <div class="col-md-5">
                                                          <label>FARMACIA</label>
                                                          <select class="form-control" name="id_farmacia_receta_postqx" id="id_farmacia_receta_postqx" style="width:100%" disabled>
                                                              <?php foreach($farmacias as $row):?>
                                                                    <option value="<?php echo $row->id?>"
                                                                    <?= in_array($row->id, [128,22,107]) ? 'selected' : '' ?>>
                                                                    <?php echo $row->nombre?></option>
                                                                    <?php endforeach;?>
                                                          </select>
                                                      </div>
                                                </div>
                                                <br />
                                                @if ($ultimoRegistro->flag_firma==0 || $ultimoRegistro->flag_firma==null)
                                                    <table>
                                                      <tr>
                                                        <td>
                                                          <button type="button" id="addProductoNuevoPostQuirurgico2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                @endif
                                                <br />
                                                <div style="display: none">
                                                  <select class="form-control" id="cmbMedicamentoTempAdicionalAtencionPQ{{$id_intervencion}}" style="width: 500px">
                                                    <option value="">Seleccionar Producto</option>
                                                  </select>
                                                </div>
                                                <div  class="form-group" style="display: none">
                                                  <select class="form-control" id="diagnosticosTempAtencionPQ"
                                                    style="width: 500px">
                                                    <option value="">Seleccionar Diagnostico</option>
                                                  </select>
                                                </div>
                                                <div>
                                                    <table id="tblMedicamentosAdicionalAtencionPQ" class="table table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                          <tr>
                                                              <th width="30%">Producto farmacético o dispositivo médico</th>
                                                              <th width="10%">Stock Farmacia</th>
                                                              <th width="14%">Tiempo(Dias)</th>
                                                              <th width="16%">Posologia</th>
                                                              <th width="21%">Via Administración</th>
                                                              <th width="10%">Cantidad Prescrita</th>
                                                              <th width="15%">diagnosticos</th>
                                                              <th width="10%"></th>
                                                          </tr>
                                                        </thead>
                                                        <tfoot></tfoot>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                          </div>
                                      </div>
                                    </div>
                                    <!-- FIN SEGMENTO DE RECETA POST QUIRURGICO -->
                                    <div class="row">
                                        <div class="col-md-12" id="epicrisis{{$id_intervencion}}" style="display: none;">
                                          @if(($intervencion->id_tipo_atencion_origen==1 || $intervencion->id_tipo_atencion_origen==2) && !$intervencion->es_nuevo_admision_hospitalizacion)
                                          <fieldset>
                                            <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-heading">
                                                <div class="panel-title text-center">
                                                  <b>EPICRISIS</b>
                                                </div>
                                              </div>
                                              <div class="panel-body">
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>RESUMEN DE LA ENFERMEDAD ACTUAL</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3"  placeholder="Resumen de la enfermedad actual" disabled>{{$ultimoRegistro->egreso_resumen}}</textarea>
                                                    @else
                                                    <textarea class="form-control" rows="3" id="egreso_resumen" name="egreso_resumen" placeholder="Resumen de la enfermedad actual">{{$ultimoRegistro->egreso_resumen}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>EXAMEN CLÍNICO</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3"  placeholder="Examen Clínico" disabled>{{$ultimoRegistro->egreso_examen_clinico}}</textarea>
                                                   @else
                                                    <textarea class="form-control" rows="3" id="egreso_examen_clinico" name="egreso_examen_clinico" placeholder="Examen Clínico">{{$ultimoRegistro->egreso_examen_clinico}}</textarea>
                                                   @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>EXAMENES AUXILIARES</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3" placeholder="Examenes Auxiliares" disabled>{{$ultimoRegistro->egreso_examen_auxiliar}}</textarea>
                                                    @else
                                                   <textarea class="form-control" rows="3" id="egreso_examen_auxiliar" name="egreso_examen_auxiliar" placeholder="Examenes Auxiliares">{{$ultimoRegistro->egreso_examen_auxiliar}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>EVOLUCIÓN</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3" placeholder="Evolución" disabled>{{$ultimoRegistro->egreso_evolucion}}</textarea>
                                                    @else
                                                    <textarea class="form-control" rows="3" id="egreso_evolucion" name="egreso_evolucion" placeholder="Evolución">{{$ultimoRegistro->egreso_evolucion}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>TRATAMIENTO</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3" placeholder="Resumen de la enfermedad actual, examen físico, exámenes auxiliares, evolución y tratamiento." disabled>{{$ultimoRegistro->egreso_tratamiento}}</textarea>
                                                   @else
                                                    <textarea class="form-control" rows="3" id="egreso_tratamiento" name="egreso_tratamiento" placeholder="Resumen de la enfermedad actual, examen físico, exámenes auxiliares, evolución y tratamiento.">{{$ultimoRegistro->egreso_tratamiento}}</textarea>
                                                   @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>PROCEDIMIENTOS TERAPÉUTICOS Y/O DIAGNÓSTICOS</label>
                                                     @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="3" placeholder="Procedimientos terapéuticos y/o diagnósticos" disabled>{{$ultimoRegistro->egreso_procedimiento}}</textarea>
                                                    @else
                                                    <textarea class="form-control" rows="3" id="egreso_procedimiento" name="egreso_procedimiento" placeholder="Procedimientos terapéuticos y/o diagnósticos">{{$ultimoRegistro->egreso_procedimiento}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>
                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>COMPLICACIONES</label>
                                                     @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="5" placeholder="Complicaciones." disabled>{{$ultimoRegistro->egreso_complicacion}}</textarea>
                                                     @else
                                                    <textarea class="form-control" rows="5" id="egreso_complicacion" name="egreso_complicacion" placeholder="Complicaciones.">{{$ultimoRegistro->egreso_complicacion}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>

                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>MEDIDAS GENERALES AL ALTA</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="5" placeholder="Complicaciones." disabled>{{$ultimoRegistro->egreso_medidas_generales}}</textarea>
                                                    @else
                                                    <textarea class="form-control" rows="5" id="egreso_medidas_generales" name="egreso_medidas_generales" placeholder="Complicaciones.">{{$ultimoRegistro->egreso_medidas_generales}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>

                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>MEDICAMENTOS PRESCRITOS AL ALTA</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <textarea class="form-control" rows="5" placeholder="Complicaciones." disabled>{{$ultimoRegistro->egreso_medicamentos_prescritos}}</textarea>
                                                    @else
                                                    <textarea class="form-control" rows="5" id="egreso_medicamentos_prescritos" name="egreso_medicamentos_prescritos" placeholder="Complicaciones.">{{$ultimoRegistro->egreso_medicamentos_prescritos}}</textarea>
                                                    @endif
                                                  </div>
                                                </div>

                                                <div class="from-group">
                                                  <div class="col-lg-12 col-md-12">
                                                    <label>PRONÓSTICO</label>
                                                    @if($ultimoRegistro->flag_firma===1 || $ultimoRegistro->flag_firma===2)
                                                    <input class="form-control"  placeholder="Pronóstico" maxlength="255" value="{{$ultimoRegistro->egreso_pronostico}}" disabled>
                                                     @else
                                                    <input class="form-control" id="egreso_pronostico" name="egreso_pronostico" placeholder="Pronóstico" maxlength="255" value="{{$ultimoRegistro->egreso_pronostico}}">
                                                    @endif
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </fieldset>
                                          @endif
                                        </div>
                                      </div>

                                </fieldset>
                              </div>

                          </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-12">
                            <div class="clearfix" style="margin-bottom:10px;">
                              @if($ultimoRegistro->flag_firma===1)
                                <a href="/{{$ultimoRegistro->ruta_firma}}" target="_blank" data-toggle="tooltip" title="PDF Firmado"
                                class="btn btn-xs pull-right"
                                style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">PDF FIRMADO
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                <i class="fa fa-download" aria-hidden="true"></i>
                                </a>
                              @elseif($ultimoRegistro->flag_firma===2)
                                <a href="/pdf_atencion_quirurgica_sin_firma/{{$id_atencion}}" target="_blank"  data-toggle="tooltip" title="PDF sin Firma"
                                class="btn btn-xs pull-right" style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;"> PDF SIN FIRMA <i class="fa fa-download"></i>
                                </a>
                              @else
                                @if (Auth::user()->status_token==1)
                                  <a class="btn btn-info pull-right" id="btnFinalizarAtencion">
                                    <i class="fa-solid fa-file-signature"></i> FINALIZAR
                                  </a>
                                  <a id="btnGuardarAtencionAdicional" class="btn btn-success pull-right" style="margin-left:10px;">
                                      GUARDAR DATOS
                                  </a>
                                @else
                                    <a id="btnGuardarAtencionAdicional" class="btn btn-success pull-right" style="margin-left:10px;">
                                       GUARDAR DATOS
                                    </a>
                                    <a id="btnFirmarTodo" class="btn btn-info pull-right">
                                      FIRMAR TODO <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                @endif
                              @endif
                              

                            </div>
                        </div>
                    </div>
                  </form>
              </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="tab2default2">
        <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading">
              <div class="panel-title text-center">
                <b>HISTORIAL DE ATENCIONES MEDICAS</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div id='divHistorial2'></div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="tab-pane fade" id="tab3default2">
        <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading2">
              <div class="panel-title text-center">
                <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div id='divLaboratorio2'></div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="tab-pane fade" id="tab4default2">
        <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading2">
              <div class="panel-title text-center">
                <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
              </div>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div id='divRadiologico2'></div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>

<?php if(Auth::user()->id == 115):
      //dd($hospitalizacion);
    endif; ?>