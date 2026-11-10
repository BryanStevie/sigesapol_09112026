{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script>

  var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];
  var filasmedicamentos = 0;
  var filasmedicamentospostquirurgico = 0;


  // function validaCampos() {
  //   let cantidadCirujano = 0; // Contador de cirujanos válidos
  //   let esValido = true; // Bandera para determinar si los campos son válidos

  //   // Validación de nombre del cirujano
  //   $('input[name="nombre_cirujano[]"]').each(function() {
  //       if ($(this).val() === '') {
  //           $(this).addClass('is-invalid'); // Agregar clase de error
  //           $(this).next('.invalid-feedback').text('Debe ingresar el nombre del cirujano').show(); // Mostrar mensaje de error
  //           esValido = false; // Marcar como no válido
  //       } else {
  //           $(this).removeClass('is-invalid'); // Eliminar clase de error
  //           $(this).next('.invalid-feedback').hide(); // Ocultar mensaje de error
  //           cantidadCirujano++; // Incrementar si el cirujano es válido
  //       }
  //   });

  //   // Validación del tipo de profesional del cirujano
  //   $('select[name="tipo_profesional_cirujano[]"]').each(function() {
  //       if ($(this).val() === '') {
  //           $(this).addClass('is-invalid'); // Agregar clase de error
  //           $(this).next('.invalid-feedback').text('Debe seleccionar el tipo profesional').show(); // Mostrar mensaje de error
  //           esValido = false; // Marcar como no válido
  //       } else {
  //           $(this).removeClass('is-invalid'); // Eliminar clase de error
  //           $(this).next('.invalid-feedback').hide(); // Ocultar mensaje de error
  //       }
  //   });

  //   // Validación general: al menos un cirujano debe ser válido
  //   if (cantidadCirujano === 0) {
  //       $('#AddCirujano2').addClass('is-invalid');
  //       $('#AddCirujano2').next('.invalid-feedback').text('Debe ingresar al menos un cirujano y su tipo').show();
  //       esValido = false; // Marcar como no válido
  //   } else {
  //       $('#AddCirujano2').removeClass('is-invalid');
  //       $('#AddCirujano2').next('.invalid-feedback').hide();
  //   }

  //   return esValido; // Retorna true si todos los campos son válidos, false si alguno es inválido
  // }
  function formatear_fechaDate(fecha) {
      if (/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
          return fecha; // Ya está en YYYY-MM-DD
      }
      if (/^\d{2}-\d{2}-\d{4}$/.test(fecha)) {
          return fecha.split('-').reverse().join('-');
      }
      return null;
  } 
  function validarFechaEgreso1(mostrarEstancia = true, soloConfigurar = false) {
      const input = document.getElementById('fecha_egreso');
      const atencion = document.getElementById('fecha_atencion');
      const receta = document.getElementById('fecha_expedicion_postqx');

      if (!input || !atencion) return false;
      if (input.dataset.validandoEgreso === '1') return false;

      const fechaIngreso = formatear_fechaDate(
          input.getAttribute('data-fecha-ingreso') ||
          input.getAttribute('min') ||
          ''
      );

      const fechaAtencion = formatear_fechaDate(
          atencion.value.split('T')[0]
      );

      if (!fechaIngreso || !fechaAtencion) {
          Swal.fire({
              icon: 'error',
              title: 'Datos incompletos',
              text: 'Verifique las fechas de ingreso y atención.'
          });
          return false;
      }

      const fechaMinima = fechaIngreso > fechaAtencion
          ? fechaIngreso
          : fechaAtencion;

      input.min = fechaMinima;

      if (soloConfigurar) return true;

      const domiciliaria = String($('#id_tipo_egreso').val()) === '1';

      if (!domiciliaria) return true;

      if (!input.value) {
          if (receta) receta.value = '';

          Swal.fire({
              icon: 'warning',
              title: 'Fecha de egreso requerida',
              text: 'Ingrese la fecha de egreso para el destino domiciliaria.'
          });

          return false;
      }

      const fechaEgreso = formatear_fechaDate(input.value);

      if (!fechaEgreso || fechaEgreso < fechaMinima) {
          input.dataset.validandoEgreso = '1';

          input.value = fechaMinima;
          if (receta) receta.value = fechaMinima;

          Swal.fire({
              icon: 'warning',
              title: 'Fecha de egreso corregida',
              text: 'El egreso no puede ser anterior al ingreso ni a '
                  + 'la atención. Se ajustó a ' + fechaMinima + '.'
          }).finally(function () {
              delete input.dataset.validandoEgreso;
          });

          return false;
      }

      if (receta) receta.value = fechaEgreso;

      const dias = Math.round(
          (
              new Date(fechaEgreso + 'T00:00:00Z') -
              new Date(fechaAtencion + 'T00:00:00Z')
          ) / 86400000
      );

      if (mostrarEstancia && dias > 0) {
          Swal.fire({
              icon: 'info',
              title: 'Días de estancia',
              text: dias + ' día(s)'
          });
      }

      return true;
  }


  function AddCirujano2() {
    var newRow = "";
    var ind = $('#tblCirujanoAtencion tbody tr').length;

    if (ind > 0) {
      $('.id_cirujano').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });
            
      fila_registros = $('#tblCirujanoAtencion tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_registro = fila_registros[idval];
    }

    newRow += '<tr>';
    newRow +='<td style="width:60%;">'+
              '<input type="hidden" name="flag_personales_cirujano" value="0" />'+
              '<input type="text"  title="Ingresar el medico" name="nombre_cirujano[]" '+
              'id="cqx_nombre_cirujano'+ ind +'" class="form-control" style="height: 31px !important;font-weight: 800;" required />'+
              '<input type="hidden" class="form-control typeahead tt-query id_cirujano" id="id_cirujano' + ind +'" name="id_cirujano[]"  data-id='+ind+'></td>';
    newRow +='<td style="width:20%;"><select id="cqx_tipo_profesional_cirujano' + ind +'" name="tipo_profesional_cirujano[]" class="form-control" style="height: 31px !important;font-weight: 800;"><option value="">Seleccionar Tipo</option></select></td>';
    newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblCirujanoAtencion tbody').append(newRow);

    var tipo_profesional_interviene = <?php echo json_encode($tipo_profesional_interviene_cirujano); ?>;
    var $select = $('#cqx_tipo_profesional_cirujano' + ind +'');
    $.each(tipo_profesional_interviene, function(index, value) {
      $select.append($('<option></option>').val(value.id).text(value.descripcion));
    });

    $("#cqx_nombre_cirujano" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        url_consulta= '{{url('cirujanos_que_interviene/')}}/'+request.term ;  
        $.ajax({
          url: url_consulta,
          dataType: "json",
          success: function(data) {
            let listaSirujanosSeleccionado = $(".id_cirujano").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaSirujanosSeleccionado.includes(element.id)); 
            var limitedData = filtro.slice(0, 10); 
            response(limitedData);
          }
        });
      },
      select: function(e, ui) {
        var id_relacionado = ui.item.id;
        //var descripcion = ui.item.value; 
        $("#id_cirujano" + ind).val(id_relacionado);
        $("#cqx_nombre_cirujano" + ind).prop("readonly", true);
      }
    });
    
  }

  function eliminarDiagnosticoXProducto(posicion) {
    // Se elimina el elemento en la posición dada
    procedimientosSeleccionados.splice(posicion, 1);

    // Se actualiza la posición de los elementos restantes
    procedimientosSeleccionados.forEach((item, index) => {
      item.posicion = index;
    });
    console.log(procedimientosSeleccionados);
  }

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

    $('body').on('click', '.AnularFilaCirujano2', function () {
      var id_medico2 = $(this).closest('tr').find('.AnularFilaCirujano2').attr('data-id_cirujano_registro');
      var $tr = $(this).closest('tr');
      $('#modalAnularProfesional2').data('tr', $tr); // Guardar el <tr> en el modal
      $('#modalAnularProfesional2').data('cirujano', id_medico2); 
      $('#anularFormularioProfesional2').trigger("reset");
      $('#tituloFormularioProfesional-anular2').html("Anular profesional que interviene en la operación");
      $('#modalAnularProfesional2').modal('show');
      $('#id_medico2').val(id_medico2);
    });


    $('#GuardarAnularProfesional2').click(function (e) {
      e.preventDefault();
      var motivoAnulacion2=$('#anulacion_motivo2').val();
      
      if(motivoAnulacion2 == null || motivoAnulacion2.length <=5){ 
        
        Swal.fire({
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un motivo de la anulación mayor a 5 caracteres para el campo motivo de anulación.",
          icon: "error",
          showConfirmButton: false,
          timer: 2500
        });
       
        $('#anulacion_motivo2').addClass('borde-alerta');
        setTimeout(function() {
          $('#anulacion_motivo2').focus();  
        }, 1000);
        return;
      }else{
        $('#anulacion_motivo2').removeClass('borde-alerta');
      }
      var token=$('#_token').val();
      var route='{{url('motivo_cirujano_anulado')}}';
      var $tr = $('#modalAnularProfesional2').data('tr'); 
      var cirujano = $('#modalAnularProfesional2').data('cirujano');
      
      Swal.fire({
            title: "¿Desea Anular el registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            showCancelButton: true, // Muestra el botón de cancelación
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar",
            dangerMode: true,
            customClass: {
                confirmButton: 'btn-red', // Clase personalizada para el botón de confirmación
                cancelButton: 'btn-cancel' // Opcional: Clase para el botón de cancelar
            }
          }).then((willDelete) => {
            if (willDelete) { 

              var formData = new FormData($("#anularFormularioProfesional2") [0]);
              $.ajax({
                data: formData,
                url:route,
                contentType: false,
                processData: false,
                headers: {'X-CSRF-TOKEN':token},
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'JSON',
                success: function(data){
                  if(data!=null){
                      if(data!=0){

                          $('#anularFormularioProfesional2').trigger("reset");
                          $('#modalAnularProfesional2').modal('hide');
                          $tr.addClass('eliminado');
                          if(cirujano){
                            $('button[data-cirujano="' + cirujano + '"]').hide();
                          }
                           
                          Swal.fire({
                            buttons: false,
                            title: "Anulación Exitosa",
                            text: "El registro fue anulado exitosamente.",
                            icon: "success",
                            timer: 1500,
                          });
                      }else{
                  
                        Swal.fire({
                          buttons: false,
                          title: "Error de Servicio de Registros",
                          text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                          icon: "error",
                          timer: 2500,
                        });
    
                      }
                }else{
                
                  Swal.fire({
                      buttons: false,
                      title: "Error de Servicio de Registros",
                      text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                      icon: "error",
                      timer: 2500,
                    });

                }
              }
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

      $('#btnCancelar').on('click', function() {
      $('#modalAnularProfesional2').modal('hide');
     });

      $('#btnCancelar').on('hidden.bs.modal', function () {
      $('#modalAnularProfesional2').modal('handleUpdate');
      $('body').addClass('modal-open'); 
     });

      $('#modalAnularProfesional2').on('hidden.bs.modal', function () {
      $('#modalAnularProfesional2').modal('handleUpdate');
      $('body').addClass('modal-open'); 
  });

  const validaExamenSeleccionado = (id_examen_lab) => {
    let dni_beneficiario = $('#dni_beneficiario').val();
    console.log({
      'dni_beneficiario' : dni_beneficiario,
      'id_examen_lab' : id_examen_lab,
    });
    $.ajax({
        url: '/validacion_beneficiario_dias_por_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
        success: function (result) {
          let msg = "";
          let n = 0;
          console.log(["result",result]);
          if (result.length > 0) {
            console.log(["result > 0",result]);
            for (let key of result) {
              if (n == 0) {
                  msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.nombre_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
                  msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
                  msg += "<thead>";
                  msg += "<tr>";
                  msg += "<th>Código</th>";
                  msg += "<th>Descripción Corta</th>";
                  msg += "<th>Tipo de Atención</th>";
                  msg += "<th>Servicio</th>";
                  msg += "<th>Especialista</th>";
                  msg += "<th>Fecha de Orden</th>";
                  msg += "<th>Dias Transcurridos</th>";
                  msg += "</tr>";
                  msg += "</thead>";
                  msg += "<tbody>";
              }
              msg += "<tr><td>" + key.codigo_lab + "</td>";
              msg += "<td>" + key.descripcion_corta + "</td>";
              if (key.id_tipo_atencion == 1) {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
              if (key.id_tipo_atencion == 2) {
                if (key.id_evaluacion > 0) {
                  msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
                } else {
                  msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
                }
              }
              if (key.id_tipo_atencion == 1) {
                msg += "<td>" + `${key.nombre_servicio}` + "</td>";
              }
              if (key.id_tipo_atencion == 2) {
                msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
              }
              msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
              msg += "<td>" + (key.fecha_expedicion) + "</td>";
              msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
              n++;
            }
            msg += "</tbody></table>";
            $("#contenido-table-pendiente").html(msg);
            // Swal.fire({
              //   title: "Alerta Laboratorio",
              //   html: msg,
              //   width: 1000  
              // });
              $("#showDetalleExamenPendiente").modal(); 
            }
          }
        });
  }

  $(document).on('click', '.toggleExamenesResult', function () {
    let registro = $(this).data('registro');
      $(".elementoOcultarResult" + registro).toggle();

      let icono = $(this).find('i');

      // icono.toggleClass('icon-visible');
      icono.toggleClass('icon-hidden-result');
  });

  const consultaWsResultExamenSeleccionado = (id_examen_lab) => {
    let dni_beneficiario = $('#dni_beneficiario').val();
    let nombreCompleto = `${$('#paterno_beneficiario').val()} ${$('#materno_beneficiario').val()}, ${$('#nombre_beneficiario').val()}`;
    console.log({
      'dni_beneficiario' : dni_beneficiario,
      'id_examen_lab' : id_examen_lab,
    });
    $.ajax({
      url: '/consulta_ws_result_beneficiario_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
      success: function (result) {
        let res = result;
        console.log("valor ws");
        console.log(res);

        let numerosImpresos = new Set();
        let n = 0;
        let n2 = 0;
        let msg = "";
        
        if (res == "vacio") {
          return;
        }
        if (res.length > 0) {
          console.log("Entro en la condición");
          if (n == 0) {
                // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los últimos 3 meses</h1>";
                msg += `<table class="table table-list-search" style="font-size: 11px !important;">`; 
                msg += `<thead class="thead-resultados">`;
                msg += "<tr>";
                msg += `<th class="encabezado"></th>`;
                msg += `<th class="encabezado">Código de orden</th>`;
                msg += `<th class="encabezado">F.Toma de Muestra</th>`;
                msg += `<th class="encabezado">Tipo de Atención</th>`;
                msg += `<th class="encabezado">Establecimiento</th>`;
                msg += "</tr>";
                msg += "</thead>";
                msg += "<tbody>";
            }
          for (let key of res) {
            console.log( key.p_nombre );
            let numeroActual = key.o_numero;
            let elementoOcultar = "elementoOcultarResult" + n;
            console.log(numeroActual);
            // Verificar si el número ya se ha impreso
            if (!numerosImpresos.has(numeroActual)) {
              // Si no se ha impreso, imprimir y agregar al conjunto
              console.log('o_numero:', numeroActual);
              
              msg += `<tr><td><a class="toggleExamenesResult" data-registro="${n}"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a></td>`;
              msg += `<td>${numeroActual}</td>`;
              msg += `<td>${key.o_fecha_toma}</td>`;
              msg += `<td>${key.s_nombre}</td>`;
              msg += `<td>${key.c_nombre}</td>`;
              msg += `</tr>`;
              msg += `<tr class="thead-resultados-detalle ${elementoOcultar} hide-element-result">`;
              msg += `<th class="encabezado">Examen</th>`;
              msg += `<th class="encabezado">Resultado</th>`;
              msg += `<th class="encabezado">Rango Referencial</th>`;
              msg += "</tr>";
              numerosImpresos.add(numeroActual);
              n2 = n;
              n++;
            }
            elementoOcultar = "elementoOcultarResult" + n2;
            msg += `<tr class="tbody-resultados-detalle ${elementoOcultar} hide-element-result">`
            msg += `<td>${key.p_nombre}</td>`;
            msg += `<td>${key.l_resultado}</td>`;
            msg += `<td>${key.l_ref_inf} - ${key.l_ref_sup}</td>`;
            msg += "</tr>";
              
          }
            msg += "</tbody></table>";
            $("#contenido-table-result").html(msg);
      
            console.log('$("#showDetalleExamenResult").modal();');
            
            $("#showDetalleExamenResult").modal(); 
        }
      }
    });
  }

  function getProducto2(){
    
    $('#tblAtencionQ tbody').html('');
    
    if($('#frmPrestacion #id_servicio').val()=="") return false;
    
    //var id_ipress 		= $('#id_ipress').val();
    var id_ipress 		= $('#frmPrestacion #id_establecimiento').val();
    var id_consultorio 	= 	<?php echo $id_servicio;?>;
    //var tipo_receta		= $('#tipo_receta').val();
    //var id_tiporeceta 	= $('#id_tiporeceta').val();
    var id_tiporeceta 	= $('#frmPrestacion #id_tipo_atencion').val();
    var id_farmacia     = $('#frmPrestacion #id_farmacia_receta2').val();
    $('#id_farmacia').val(id_farmacia);
    //console.log(id_farmacia);
    var nivel     = $('#id_nivel').val();
    var x  = $('#nro_interconsultas').val();
    var dni = $('#dni_beneficiario2').val();
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
            $('#cmbMedicamentoTempAQ').html(newOption);
          },
          complete: function () {
                  Swal.close();
                },
      }); 
    }
  }

  function esEntero(evt){     
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
  }

  // function calcularTrimestre() {
  //   //obtencion de fechas    
  //   var ultimaMestruacion = $("#ultimaMestruacion").datepicker("getDate");
  //   var fechaHoy = new Date();

  //   //álculo de la diferencia en semanas
  //   var diffMilisegundos = fechaHoy.getTime() - ultimaMestruacion.getTime();
  //   var diffSemanas = Math.floor(diffMilisegundos / (1000 * 60 * 60 * 24 * 7)); // Obtener la diferencia en semanas
  //   console.log("estas en la semana ",diffSemanas);
  //   // Determinar el trimestre
  //   var trimestre;
  //   var semanas;
  //   if (diffSemanas >=45 ) {
  //     bootbox.alert("INGRESAR UNA FECHA CORRECTA MAXIMO DE 45 SEMANAS");
  //   } else {
  //       semanas = "SEMANA N° " + diffSemanas;
  //   }
  //   // Mostrar el trimestre en el campo de entrada correspondiente
  //     $("#semana_gesta").val(semanas);
  // }

  function obtenerBeneficiario(){
      
      var id_tipo_atencion = 1;
      var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
      var dni_beneficiario = $("#dni_beneficiario").val();
      var msg = "";
      
      //if(id_tipo_atencion == "")msg += "Debe seleccionar el tipo de receta <br>";
      //if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
      
      if (msg != "") {
        bootbox.alert(msg);
        return false;
      }
      
      let usarApi = "{{ $flag_api_sp ? 'api' : 'ws' }}";
      var origen = 'atencion_qx';

      $.ajax({
        url: '../obtener_beneficiario/' + id_tipo_atencion + '/' + tipodoc_beneficiario + '/' + dni_beneficiario + '/' + usarApi + '/' + origen,
        dataType: "json",
        success: function(result){
          if(result.sw == false){
            bootbox.alert(result.msg);
            $('#beneficiario').val("");
            $('#tipo_beneficiario').val("");
            $('#nombre_beneficiario').val("");
            $('#paterno_beneficiario').val("");
            $('#materno_beneficiario').val("");
            $('#tipodoc_beneficiario').val("");
            $('#cip_beneficiario').val("");
            $('#grado').val("");
            return false;
          }
          
          var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
          $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
          $('#beneficiario').val(beneficiario);
          $('#tipo_beneficiario').val(result.beneficiario.parentesco);
          $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
          $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
          $('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
          $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
          $('#cip_beneficiario').val(result.beneficiario.cip);
          $('#grado').val(result.beneficiario.grado);
          
          var parentesco 	= result.beneficiario.parentesco;
          var ubigeo 		= result.beneficiario.ubigeo;
          
          obtenerEstablecimiento(parentesco,ubigeo);
          modalHistorial();
          modalLaboratorio();
          modalRadiologico2();
          //getProducto2();
          AddDiagnostico();
          AddProcedimiento2();
          AddCirujano2();
          /*
          if(id_tipo_atencion == 3){	
            ShowByServicioAndId_farmacia();
          }
          */
        },
        error: function(response) {
          location.reload();
        }
        
      });
    }

    function obtenerBeneficiario_dirrehum(){
      var id_tiporeceta = 1;
      var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
      var dni_beneficiario = $("#dni_beneficiario").val();
      var msg = "";
      
      if (msg != "") {
        bootbox.alert(msg);
        return false;
      }
      
      $.ajax({
        url: '/obtener_beneficiario_dirrehum/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
        dataType: "json",
        success: function(result){
          if(result.sw == false){
            bootbox.alert(result.msg);
            $('#beneficiario').val("");
            $('#tipo_beneficiario').val("");
            $('#nombre_beneficiario').val("");
            $('#paterno_beneficiario').val("");
            $('#materno_beneficiario').val("");
            $('#tipodoc_beneficiario').val("");
            $('#cip_beneficiario').val("");
            $('#grado').val("");
            $('#fecnacafiliado').val("");
            $('#nomsexo').val("");
            $('#nro_historia').val("");
            $('#cip_beneficiario').val("");
            return false;
          }
          
          var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
          $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
          $('#beneficiario').val(beneficiario);
          $('#tipo_beneficiario').val(result.beneficiario.parentesco);
          $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
          $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
          $('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
          $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
          $('#cip_beneficiario').val(result.beneficiario.cip);
          $('#grado').val(result.beneficiario.grado);
          $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
          $('#nomsexo').val(result.beneficiario.nomsexo);
          
          if(result.numero_historia!=null){
            if(result.numero_historia!='')
              $('#nro_historia').val(result.numero_historia);
            else
              $('#nro_historia').val(result.beneficiario.nrodocafiliado);	
          }
          else				
            $('#nro_historia').val(result.beneficiario.nrodocafiliado);
          
          $('#cip_beneficiario').val(result.beneficiario.cip);
          
          $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
          $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
          $('#nombre_titular').val(result.beneficiario.nomtitular);
          $('#paterno_titular').val(result.beneficiario.apepattitular);
          $('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
          
          var parentesco 			= result.beneficiario.parentesco;
          var ubigeo 				= result.beneficiario.ubigeo;
          var establecimiento 	= result.establecimiento;
          var cita 				= result.cita;
          var id_establecimiento 	= 0;
          
          if(establecimiento[0]!=undefined){
            id_establecimiento = establecimiento[0].id_establecimiento;
            nombre_establecimiento = establecimiento[0].establecimiento;
            $('#nombre_establecimiento').html(nombre_establecimiento);
          }
          
          if(cita[0]!=undefined){
            if(cita[0].id_estado_cita == 1){
            
              var id_servicio = 8;
              $("#parent1").prop("checked", true);
            }
            if(cita[0].id_estado_cita == 3){
              var id_servicio = cita[0].id_servicio;
            
              $("#parent2").prop("checked", true);
            }
          }else{
            var id_servicio = 8;
            $("#parent1").prop("checked", true);
          }
          
          modalHistorial();
          modalLaboratorio();
          modalRadiologico2();
          //getProducto2();
          AddDiagnostico();
          AddProcedimiento2();
          AddCirujano2();
    
          $("#divTurno").html("");
    
          
        },
        error: function(response) {
          location.reload();
        }
      });
    }

    function obtenerEstablecimiento(parentesco,ubigeo){
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#id_establecimiento').html(newOption);
    
    $.ajax({
      url: '../obtener_establecimiento/'+parentesco+"/"+ubigeo,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
        });                 
        $('#id_establecimiento').html(newOption);
        //$("#id_establecimiento").select2({max_selected_options: 4});
        const select = new TomSelect('#id_establecimiento', {
          maxItems: 4 
        });
      
      }
    });
  }

  function limpiarBeneficiario(){
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    if(dni_beneficiario!=nrodocafiliado){
      $('#beneficiario').val("");
      $('#tipo_beneficiario').val("");
      $('#nombre_beneficiario').val("");
      $('#paterno_beneficiario').val("");
      $('#materno_beneficiario').val("");
      $('#grado').val("");
      $('#nro_historia').val("");
      $('#tipodoc_beneficiario').val("");
      $('#cip_beneficiario').val("");
      $('#id_admision').val("");
      $('#grado').val("");
    }
    if(dni_beneficiario!="")obtenerBeneficiario();
  }

  function getServicio(){
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#frmPrestacion #id_servicio').html(newOption);
    var parent = $('input[name="parent"]:checked').val();
    
    $.ajax({
      url: '../obtener_consultorio_parent/'+parent,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
        });                 
        $('#frmPrestacion #id_servicio').html(newOption);
        $("#id_servicio").select2({max_selected_options: 4});
        const select = new TomSelect('#id_servicio', {
          maxItems: 4 
        });
      
      }
    });
    
  }

  function fullCalendar(){
    var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
    var iIdTipoCompra		= $('#hdIdTipoCompra').val();
    var id_servicio	= $('#frmPrestacion #id_servicio').val();
    var id_ipress	= $('#id_establecimiento').val();
    $("#divHorario").html("");
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
      },
      editable: false,

      events: "cronograma_cita/"+id_ipress+"/"+id_servicio,
      selectable: true,
      selectHelper: true,
      select: function(start, end, allDay) {
        dateObj = new Date(start); /* Or empty, for today */
        dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
        dateObjNTZ = new Date(dateIntNTZ);
        start = dateObjNTZ.toISOString().slice(0, 10);
        start = $.trim(start);
        var sValidaciones = "";
          modalDelegar(start)
    
      }
    });	
      
  }

  function modalDelegar(start){
    var id_servicio	= $('#frmPrestacion #id_servicio').val();
    var id_ipress	= $('#id_establecimiento').val();;
        
    $.ajax({
        url: "../modal_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start,
        type: "GET",
        success: function (result) {  
            $("#divHorario").html(result);
        }
    });
  }

  function modalTurnos(){
    var id_servicio	= $('#frmPrestacion #id_servicio').val();
    var id_ipress	= $('#id_establecimiento').val();;
    var start = "<?php echo $fechaServidor?>";
    $.ajax({
        url: "../modal_turnos/"+id_ipress+"/"+id_servicio+"/"+start,
        type: "GET",
        success: function (result) {  
            $("#divTurno").html(result);
        }
    });
  }

  function modalHistorial(){
    var dni_beneficiario = '<?php echo $titular->nro_doc_ident;?>';
    $.ajax({
        url: "../modal_historial_atenciones/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divHistorial").html(result);
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
    
    var dni_beneficiario = '<?php echo $titular->nro_doc_ident;?>';
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {
            $("#divLaboratorio").html(result);
            var $tbl = $("#divLaboratorio").find("#tablemodalm1");
            // Asegurarse que la tabla existe realmente en el DOM
            if ($tbl.length) {
              if ($.fn.DataTable.isDataTable($tbl)) {
                $tbl.DataTable().clear().destroy();
              }

              $tbl.DataTable({
                order: [],
                sPaginationType: "full_numbers",
                language: { url: "/js/Spanish.json" }
              });
            } else {
              console.warn('No se encontró la tabla con id tablemodalm1');
            }
        }
    });
  }

  function modalRadiologico2(){
    
    var dni_beneficiario = '<?php echo $titular->nro_doc_ident;?>';
    $.ajax({
        url: "../modal_historial_radiologia/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divRadiologico").html(result);
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

  function updateSelectElements() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll("#frmPrestacion .select-diagnostico");

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
    if(  listIdDiagnosticoObjetos.findIndex( e  => e.id == id_diagnostico) ==-1 ){
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

  function AddDiagnostico(){
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;
    
    if(ind > 0){
      $('.id_diagnostico').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });
            
      fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
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

    newRow += '<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind +'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow +='<td><select tabindex="45" class="form-control id_categoria_diagnostico" id="cmbCategoriaDiagnostico'+ind+'" name="id_categoria_diagnostico[]" style="margin:0 0 0 20px;width:150px">'+item_categoria_diagnostico+'</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblDiagnostico tbody').append(newRow);
    var sexo	= $('#nomsexo').val();
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
            let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
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
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico"+ind).val("");
        return false;
      }else{
        addNewArray(id_diagnostico);
      }
    });
  }

  function AddProcedimiento2(){
    var newRow = "";
    var ind = $('#tblProcedimiento2 tbody tr').length;
    
    if(ind > 0){
      $('.id_procedimiento').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });
            
      fila_medicamentos=$('#tblProcedimiento2 tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];
      
    }
    
      newRow += '<tr>';
      newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento_form' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento_form' + ind +
        '" name="id_procedimiento[]" value="" data-id='+ind+'></td>';
      newRow +=
        '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1"></td>';
      newRow += '<td><select class="form-control select-diagnostico"  id="miSelectForm' + ind + '" name="miSelect[]"></select></td>';
      newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimiento2 tbody').append(newRow);
      const selectElement = document.getElementById('miSelectForm' + ind);
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
      var nivel     = $('#id_nivel').val();
      var sexo	= $('#nomsexo').val();
      if(sexo=='M')
        genero=1;
      else
        genero=2;
    
    
      $("#nombre_procedimiento_form"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          $.ajax({
    //				url: '../obtener_procedimiento/' + request.term,
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data)  {
              let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
            }					
          })
      
        },select:  function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento_form"+ind).val(id_procedimiento);
          $("#nombre_procedimiento_form"+ind).prop("readonly",true);

          // Agrega el procedimiento seleccionado a la tabla
          var cpt = ui.item.value;
          $("#table_procedimiento_intervencion tbody").append(`
            <tr data-id="${id_procedimiento}">
              <td>${cpt}</td>
            </tr>
          `);
        }
      });
    
      $("#nombre_procedimiento_form"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_form"+ind).val();
        if(id_procedimiento == ""){
          bootbox.alert("Falta ingresar el procedimiento en esta fila");
          $("#nombre_procedimiento_form"+ind).val("");
          return false;
        }
      });
      $("#procedimiento_cantidad"+ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento_form"+ind).val();
        if(id_procedimiento == ""){
          bootbox.alert("Falta ingresar la cantidad en este procedimiento");
          $("#procedimiento_cantidad"+ind).val("");
          return false;
        }
      });
  } 

  function esNumero(event, id) {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  function actualizararray(selectedId,selectedcodigo,ind) {
      if (procedimientosSeleccionados.length < 1) {
      let obj = {
        posicion: ind,
        id: selectedId,
        codigo:selectedcodigo
      }
      procedimientosSeleccionados.push(obj);
    } else {
      let newArray = procedimientosSeleccionados.map((elemento) => {
        if (elemento.posicion === ind) {
          elemento.id = selectedId; // si el objeto existe, actualiza el id
          elemento.codigo =selectedcodigo;
        }
        return elemento;
      });
      if (!newArray.some((elemento) => elemento.posicion === ind)) {
        let obj = {
          posicion: ind,
          id: selectedId,
          codigo:selectedcodigo
        }
        newArray.push(obj); // si no existe, agrega un nuevo objeto
      }
      procedimientosSeleccionados = newArray; // actualiza el array original
    }
    console.log(procedimientosSeleccionados);
  }

  function abrirModal(ind) {
    $('#modal-content').empty();
    let arraydeprocedimientos =listIdDiagnosticoObjetos;
    console.log(arraydeprocedimientos);

    // Agregando los objetos del array a la tabla del modal
    let tableRows = arraydeprocedimientos.map(function (item) {
    return '<tr class="table-row"><td>' + item.id + '</td><td>' + item.codigo + '</td><td>' + item.nombre + '</td></tr>';
    }).join('');
    $('#modalArrayList .modal-body #procedimientosTable tbody').html(tableRows);

    // Agregar controlador de eventos al hacer clic en las filas de la tabla
    $('#modalArrayList .modal-body #procedimientosTable tbody tr').on('click', function() {
    let selectedId = $(this).find('td:first').text();//aqui
    let selectedcodigo = $(this).find('td:nth-child(2)').text();
    let selectedName = $(this).find('td:nth-child(3)').text();
    console.log("posicion" + ind + "el id es" +selectedId );
    actualizararray(selectedId,selectedcodigo,ind);
    $('#modalArrayList').modal('hide');
    });
    $('#modalArrayList').modal('show');
  }
  let tomSelectInstancesProducto = {};
  function AddProducto2() {
    var tipo_receta = $('#tipo_receta').val();
    var id_consultorio = $('#id_consultorio').val();
    var id_farmacia = $('#id_farmacia_receta2').val();
    if (tipo_receta == "") {
      bootbox.alert("Seleccione el tipo atencion");
      return false;
    }
    if (id_consultorio == "") {
      bootbox.alert("Seleccione un servicio");
      return false;
    }
    var newRow = "";
    var ind = filasmedicamentos;
    var tabindex = 11;
    var nuevalperiodo = "";
    if (ind > 0) {
      $('.cmbMedicamentoAQ').each(function() {
        var ind_tmp = $(this).attr("ind");
        if (ind_tmp => ind) {
          //ind = Number(ind_tmp) + 1;
          tabindex += 3;
        }
      });

      fila_medicamentos = $('#frmPrestacion #tblAtencionQ tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):  ?>
      fila_medicamento = fila_medicamentos[idval];
      var valante = $(fila_medicamento).find('#nro_med_entregados').val();
      if (valante == "") {
        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
        return false;
      }
      <?php //endif; ?>

    }

    var item_producto = "";
    $('#cmbMedicamentoTempAQ option').each(function() {
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
    item_via_administracion+='<option value="9">Oftalmologica</option>';
    item_via_administracion+='<option value="10">Inhalatoria</option>';
    item_via_administracion+='<option value="11">Epidural</option>';
    item_via_administracion+='<option value="12">Intratecal</option>';
    item_via_administracion+='<option value="13">Vaginal</option>';
    item_via_administracion+='<option value="14">Intraarticular</option>';
    item_via_administracion+='<option value="17">Parenteral</option>';
    item_via_administracion+='<option value="15">No Aplica</option>';
    item_via_administracion+='<option value="16">Otros</option>';
    
    
    newRow +='<tr>';
    newRow +='<td><select class="select-tom cmbMedicamentoAQ" id="cmbMedicamentoAQ' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
      newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
      newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
      newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
      newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
    newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
      ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
    
    <?php //endif;?>
    newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
    newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
    
    <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
    //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
    <?php //endif;?>
    
    newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
    newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto2'+ind+'"><i class="fa fa-plus"></i> </button></td>';
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';

    newRow +='</tr>';
    $('#frmPrestacion #tblAtencionQ tbody').append(newRow);
    // //$("#cmbMedicamentoAQ" + ind).select2({max_selected_options: 4}).focus();
    // const select = new TomSelect("#cmbMedicamentoAQ" + ind, {
    //   maxItems: 1 // Limita el número de selecciones a 4
    // });
    // select.focus();
    // Inicializar Tom Select
    const select = new TomSelect("#cmbMedicamentoAQ" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstancesProducto["cmbMedicamentoAQ" + ind] = select;
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
    
    $("#nro_med_solictados" + ind).keypress(function(e) {
      if (e.which == 13) {
        <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
        AddProducto2();
        <?php //else:?>
        validproductoseleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
        <?php //endif;?>
      }
    });


    $("#add_producto2"+ind).click(function(e){
        AddProducto2();
        validproductoseleccionado(<?php echo Auth::user()->id_farmacia;?>,this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    function validproductoseleccionado(id_farmacia, btn) {//Jose
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nro_dias = 30;
      var objTr = $(btn).closest("tr");
      var id_producto = objTr.find(".cmbMedicamentoAQ").val();
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
          if(msg != "")
          {
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
    

    $("#cmbMedicamentoAQ"+ind).on("change", function (e) {
      //var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicamentoAQ"+ind).val();
      //id_user=<?php // echo Auth::user()->id;?>;
      // var listaprueba = [];
      // $('.cmbMedicamentoAQ').each(function(){
      //     var ind_tmp = $(this).val();
      //     listaprueba.push(ind_tmp)
      //     if($(this).val() == id_producto)flagx++;
      // });
      //console.log(`El array de productos : ${listaprueba}`);
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbMedicamentoAQ').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });
      if (duplicados > 1 && selectedValue!='') {
        bootbox.alert("El Producto ya ha sido ingresado");
        const currentTS = tomSelectInstancesProducto["cmbMedicamentoAQ" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }
      
      // if(flagx > 1){
      //     // Guardar referencia al modal principal
      //     let modal = $(this).closest('.modal');

      //     // Bloquear el scroll del body al abrir el bootbox
      //     $('body').addClass('modal-open');

      //     bootbox.alert({
      //         message: "El producto farmaceutico ya ha sido ingresado",
      //         callback: function() {
      //             // Restaurar el estado del modal principal al cerrar el alert
      //             modal.modal('show');  // Volver a mostrar el modal principal si estaba abierto
      //             $('body').addClass('modal-open');  // Restaurar el scroll del modal
      //         }
      //     }).on('hidden.bs.modal', function () {
      //         // Restaurar el scroll al cerrar el alert de bootbox
      //         $('body').addClass('modal-open');
      //     });

      //     $("#cmbMedicamentoAQ"+ind).val("").trigger("change");
      //                     return false;
      // }
      var id_farmacia_receta = $('#id_farmacia_receta2').val();
      if(id_producto){
        option = {
          url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
          type: 'GET',
          dataType: 'json',
          data: {}
        };

        $.ajax(option).done(function(data) {

          var cantidad = data.cantidad;
          var cantidadEstablecimiento = data.cantidadEstablecimiento;
          var cantidadAlmacen = data.cantidadAlmacen;
          $(cmb).closest("tr").find(".limpia_text").val("");
          //$(cmb).closest("tr").find("#nro_stocks").val(data);
          $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
          $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
          $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
          <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
            ?>
          $(cmb).closest("tr").find(".tiempo_periodo").focus();
          <?php //endif;
            ?>
          $(cmb).closest("tr").find("#nro_med_solictados").val("");
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

  function getProducto2AndAddProducto() {
    getProducto2();
  }
  // TAGSITEDS
  async function generarCodigoSiteds() {
    return new Promise((resolve, reject) => {
      Swal.fire ({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
      })
      
      const body = {
          "idCorrelativo": {{ !empty($prestacion->id) ? $prestacion->id : 'null' }},
          "aseguradoId": {{ !empty($prestacion->id_asegurado) ? $prestacion->id_asegurado : (!empty($hospitalizacion->id_asegurado) ? $hospitalizacion->id_asegurado : 'null') }},
          "establecimientoId": {{ !empty($prestacion->id_establecimiento) ? $prestacion->id_establecimiento : (!empty($hospitalizacion->id_establecimiento) ? $hospitalizacion->id_establecimiento : 'null') }},
          "tipoAtencionId": 3,
          "servicioId": {{ $id_servicio }}
      };
      $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result) => {
          $("#codigo_siteds").val(result.nuAutorizacion);
          Swal.fire({
          icon: 'success',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS Generado</span></div>",
          showConfirmButton: false,
          timer: 1500
        });
          
          //resolve(result);
        },
        error: function(xhr, status, error){
          $("#codigo_siteds").val("");
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            reject(xhr.responseJSON);
        }, 
        complete: (xhr, status) => {
          Swal.hideLoading();
          Swal.close();
        }
      })
    });
  }

  function getCodeSubCoverage() {
    const cita = {!! json_encode($cita)!!};
    if (!cita || !cita.id_servicio) {
      return 100;
    }
    if (parseInt(cita.id_servicio) === 22 || parseInt(cita.id_servicio) === 130) {
      return 502
    }
    if (parseInt(cita.id_servicio) === 90 || parseInt(cita.id_servicio) === 100) {
      return 501
    }
    return 100;
  }

  //===============================CENTRO QUIRURGICO======================================================//
  $('#addInterQuirurgica').on('click', function () {
      let addInterQuirurgica = $('#addInterQuirurgica').attr("aria-pressed");
      $("#fsInterQuirurgica").hide();
      if(addInterQuirurgica == "false"){
        $("#fsInterQuirurgica").show();
      }
  });

  $('#addInterPreQuirurgica').on('click', function () {
      let addInterPreQuirurgica = $('#addInterPreQuirurgica').attr("aria-pressed");
      $("#fsInterPreQuirurgica").hide();
      if(addInterPreQuirurgica == "false"){
        $("#fsInterPreQuirurgica").show();
      }
  });

  $('#addObservacionIntQx').on('click', function () {
    let addObservacionIntQx = $('#addObservacionIntQx').attr("aria-pressed");
    $("#fsObservacionIntQx").hide();
    if(addObservacionIntQx == "false"){
      $("#fsObservacionIntQx").show();
    }
  });

  $('#switch1').on('click', function () {
      var switch1 = $('#switch1').attr("aria-pressed");
      $("#fsSwitch1").hide();
      if(switch1 == "false"){
      $("#fsSwitch1").show();
  }
  });

  $('#switch2').on('click', function () {
      var switch2 = $('#switch2').attr("aria-pressed");
      $("#fsSwitch2").hide();
      if(switch2 == "false"){
      $("#fsSwitch2").show();
      }
  });

  $('#addProgramacionQx').on('click', function () {
    let addProgramacionQx = $('#addProgramacionQx').attr("aria-pressed");
    $("#has_programacion_quirurgica").val("0");
    $("#fsProgramacionQx").hide();
    if(addProgramacionQx == "false"){
      $("#fsProgramacionQx").show();
      $("#has_programacion_quirurgica").val("1");
    }
  });

  $('#addDiasHospitalizadoPostQx').on('click', function () {
    let addDiasHospitalizadoPostQx = $('#addDiasHospitalizadoPostQx').attr("aria-pressed");
    $("#has_requiere_hospitalizacion_postqx").val("0");
    $("#fsDiasHospitalizadoPostQx").hide();
    if(addDiasHospitalizadoPostQx == "false"){
      $("#has_requiere_hospitalizacion_postqx").val("1");
      $("#fsDiasHospitalizadoPostQx").show();
    }
  });

  $('#modalId').on('shown.bs.modal', function () {
      $(this).find('.modal-body').css({
          'overflow-y': 'auto',
          'max-height': '70vh'
      });
  });

  function getCamas2ByIdConsultorio2(idConsultorio) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/obtener_cama_por_idcuarto/${idConsultorio}`,
        dataType: "json",
        beforeSend: function() {
          $('#traslado_cama').prop('disabled', true);
          markInputError("traslado_cama", "cargando resultados...")
        },
        success: function (response) {
          resolve(response)
        },
        error: function (error) {
          reject(error)
        },
        complete: function() {
          $('#traslado_cama').prop('disabled', false);
          clearInputError("traslado_cama");
        }
      });
    });
  }

  // $("#id_cama_destino").on("change", function () {
  //   const dato_cama = {!! json_encode($dato_cama) !!}
  //   const value_selected = $(this).val();
  //   if (parseInt(dato_cama.id) === parseInt(value_selected)) {
  //     handlerEnabledTraslado();
  //   } else {
  //     handlerDisabledTraslado();
  //   }
  // });

  function getCamas(){ 
    var id_ipress = $('#id_establecimiento').val();
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
          // $('#id_cama_destino').append("<option value='0'>SELECCIONAR</option>"); 
          // $(result).each(function(ii, oo) {  
          //   $('#id_cama_destino').append("<option value='" + oo.id + "'>" + oo.nombre + "</option>");
          // }); 
          // $('#id_cama_destino').val("0").change();  
        },
      }); 
    }else{
      $('#id_cama_destino').empty();  
      $('#id_cama_destino').append("<option value='0'>SELECCIONAR</option>"); 
      $('#id_cama_destino').val("0").change(); 
    }
  }

  function mostrarModalInfoPaciente() {
      $('#modal-info-paciente').modal({
          backdrop: false,
          keyboard: false,
          show: true
      });

      $('#modal-info-paciente').css({
          'z-index': 1060, 
          'overflow-y': 'auto'
      });
  }

  $('#modal-info-paciente').on('hidden.bs.modal', function () {
      $('#modal-atencion-quirurgica').css('z-index', 1050).modal('show'); 
      $('body').addClass('modal-open'); 
  });

  $('#modal-info-paciente .close').off('click').on('click', function (e) {
      e.stopPropagation();
      $('#modal-info-paciente').modal('hide');
  });

  $('#modal-atencion-quirurgica').on('shown.bs.modal', function () {
      $('body').addClass('modal-open'); 
  });

  $('#modal-info-paciente').on('shown.bs.modal', function () {
      $('body').addClass('modal-open'); 
  });

  function cambiar_tipo_egreso(){
    var id_tipo_egreso=  $("#id_tipo_egreso").val();

    if(id_tipo_egreso=='1'){ //ALTA
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#id_condicion_alta").val('');
      $("#egreso_traslado").hide();
      $("#alta_condicion").show('');
      $("#destino_domiciliaria").show('');
      $("#destino_hospitalizacion").hide();
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
      $("#receta-postquirurgico").show();
      hora_egreso();
    }else if(id_tipo_egreso=='2'){ //HOSPITALIZACIÓN
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").show();
      $("#egreso_defuncion").hide();
      $("#id_condicion_alta").val('');
      $("#egreso_traslado").hide();
      $("#alta_condicion").hide('');
      $("#alta_condicion_tra").show('');
      $("#destino_domiciliaria").hide();
      $("#destino_hospitalizacion").show('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
      $("#receta-postquirurgico").show();
      // $.getJSON("/listar_consultorios_ajax/3", function(json){  
      //     document.getElementById("id_servicio_hospitalizacion").innerHTML = "";
      //     $('#id_servicio_hospitalizacion').append($('<option>').text("Seleccione").attr('value', ""));   
      //     $('#id_servicio_hospitalizacion').append($('<option>').text("OTRO").attr('value', "OTRO"));  

      //     $.each(json, function(i, obj){      
      //       var len= obj.length;
      //       for(j=0;j<len;j++){
      //         $('#id_servicio_hospitalizacion').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id).attr('data-flag-quirurgico', obj[j].flag_quirurgico));  
      //       }                        
                                  
      //     });           
      //   });
    
    }else if(id_tipo_egreso=='4'){ //DEFUNCION  
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
    }else if(id_tipo_egreso=='3'){ //TRASLADO
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
    }else if(id_tipo_egreso=='7'){ // RETIRO VOLUNTARIO
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
    }else if(id_tipo_egreso=='6'){ // FUGA
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
    }else{ //HOSPITALIZACIÓN  
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
      $("#destino_domiciliaria").hide();
      $("#destino_hospitalizacion").hide();
    }
    //const fecha_egreso = $("#fecha_egreso").val();
  }
  function hora_egreso() {
    // 1. Intentamos obtener el input
    var inputHora = document.getElementById("hora_egreso");
    // 2. Si no existe, salimos sin hacer nada
    if (!inputHora) return;

    // 3. Validación: solo dígitos y dos puntos
    inputHora.addEventListener("keypress", function (e) {
      var char = String.fromCharCode(e.which || e.keyCode);
      if (!/[0-9:]/.test(char)) {
        e.preventDefault();
      }
    });

    // 4. Validación en blur (formato HH:MM o HH:MM:SS)
    inputHora.addEventListener("blur", function () {
      var valor = this.value.trim();
      var regex = /^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/;
      if (!regex.test(valor)) {
        this.classList.add("input-error");
      } else {
        this.classList.remove("input-error");
      }
    });
  }


  $('#addProcedimientoIntervencion').on('click', function () {
		AddProcedimientoIntervencion();
	});
  let tomSelectInstances = {};
  function AddProcedimientoIntervencion() {
    let newRow = "";
    let ind = $('#table_procedimiento_intervencion tbody tr').length;

    // Obtener opciones desde el select oculto plantilla
    let item_procedimiento = "";
    $('#cmbProcedimientoIntervencionTemp option').each(function () {
      item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    // Crear la nueva fila con select dinámico
    newRow += '<tr>';
    newRow += '<td>';
    newRow += '<select class="select-tom cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' + ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]">';
    newRow += item_procedimiento;
    newRow += '</select>';
    newRow += '</td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i></button></td>';
    newRow += '</tr>';

    $('#table_procedimiento_intervencion tbody').append(newRow);

    // Inicializar Tom Select
    const select = new TomSelect("#cmbProcedimientoIntervencion" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstances["cmbProcedimientoIntervencion" + ind] = select;
    select.focus();

    // Validación de duplicados
    $("#cmbProcedimientoIntervencion" + ind).on("change", function () {
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbProcedimientoIntervencion').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });

      if (duplicados > 1) {
        bootbox.alert("El Procedimiento ya ha sido ingresado");
        const currentTS = tomSelectInstances["cmbProcedimientoIntervencion" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }
    });
  }

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
      $('#tiene_pieza').val(0);
      $("#fsPiezaOpe").find("input, textarea,select").val('');
    }
  });
  $('#addExamenPatologico').on('click', function () {
    var addExamenPatologico = $('#addExamenPatologico').attr("aria-pressed");
    $("#fsExamenPatologico").hide();
    if(addExamenPatologico == "false"){
      $('#tiene_examen_pat').val(1);
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
          $('#cmbProcedimientoExamenPatologiaTemp').html(newOption);
        }
      });
      $("#fsExamenPatologico").show();
    }else{
      $('#tiene_examen_pat').val(0);
      var $select = $('#table_procedimiento_examen_patologia [name="id_procedimiento_examen_patologia[]"]');
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
      $('#tiene_examen_bact').val(0);
      $("#fsExamenBacteriologico").find("input, textarea,select").val('');
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
  

  setTimeout(function() {
    $('body').addClass('modal-open');
    $('#modal-atencion-quirurgica').css('overflow', 'auto');
  }, 300);

  $('#addRecetaVale2').on('click', function () {
    var addRecetaVale = $('#addRecetaVale2').attr("aria-pressed");
    $("#fsRecetaVale2").hide();
    if(addRecetaVale == "false"){
      getProducto2AndAddProducto();
      $("#fsRecetaVale2").show();
    }
  });

   $('#btnPrestacion').on("click", function (e) {
    $('#FormularioQuirurgico .validado_incorrecto').removeClass('validado_incorrecto');
    validarAsegurado().then(function(resultado){
      if(!resultado) return; 
      let errorCqx = false;
      var addRecetaVale = $('#addRecetaVale2').attr("aria-pressed");
      var addCondicion = $('#addCondicion').attr("aria-pressed");
      var addExamenPatologico = $('#addExamenPatologico').attr("aria-pressed");
      var addDestino = $('#addDestino').attr("aria-pressed");
      const arrays_validador=[
        { campo: 'input', panel_id: null, cumple_validacion: '', selector: '#frmPrestacion #codigo_siteds',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe ingresar un Codigo siteds'},
        { campo: 'input', panel_id: null, cumple_validacion: '', selector: '#id_tipo_atencion',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe ingresar un Tipo de Atencion'}, 
        { campo: 'input', panel_id: null, cumple_validacion: '', selector: '#fecha_atencion',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe ingresar una Fecha de Atención'},
        { campo: 'input', panel_id: null, cumple_validacion: '0', selector: '#id_sub_consultorio',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe ingresar un Sub-Consultorio'},
        { campo: 'input', panel_id: null, cumple_validacion: '', selector: '#dni_beneficiario2',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe buscar un Aseguradoo'}, 
        { campo: 'input', panel_id: null, cumple_validacion: '0', selector: '#codigo_upss',  titulo:'SECCIÓN DE DATOS DE ATENCIÓN',mensaje: 'Debe seleccionar un Código de UPSS'},
        { campo: 'Longitud', panel_id: '#tabDiagnostico', cumple_validacion: '0', selector: '#tblDiagnostico tbody tr',  titulo:'SECCIÓN DE DIAGNOSTICOS',mensaje: 'Debe ingresar mínimo un diagnóstico'},
        { campo: 'input', panel_id: '#tabDiagnostico', cumple_validacion: '', selector: '#nombre_diagnostico0',  titulo:'SECCIÓN DE DIAGNOSTICOS',mensaje: 'Debe ingresar los datos del Primer de diagnóstico'},
        { campo: 'Longitud', panel_id: '#tabProcedimiento2', cumple_validacion: '0', selector: '#tblProcedimiento2 tbody tr',  titulo:'SECCIÓN DE PROCEDIMIENTOS',mensaje: 'Debe ingresar mínimo un procedimiento'},  
      ]  

      


      function lanzarError(e){
        console.log("Error de validación: ", e);
        if (errorCqx) {    return;  }
        errorCqx = true; 
        Swal.fire({icon: 'error', timer: 3500, showConfirmButton: false, 
                    html: `<strong class="texto-negrita-14">${e.nombre}</strong><br>${e.msje}`,
                     scrollbarPadding: false,
                  heightAuto: false,
                  returnFocus: false,
                    
        });
        var vlr_campo = (e.id_campo.includes(' ')==true) ?  e.id_campo.split(' ')[0] : e.id_campo ; 
        $(vlr_campo).addClass("validado_incorrecto");   
        if (vlr_campo && $(vlr_campo).length) { 
             setTimeout(() => { 
                    const modal = document.querySelector('#modal-atencion-quirurgica');
                    const position_content = document.querySelector('#modal-atencion-quirurgica '+vlr_campo);  
                    const posicion =position_content.getBoundingClientRect().top -  modal.getBoundingClientRect().top +  modal.scrollTop - 150;
                    modal.scrollTo({  top: posicion,   behavior: 'smooth' }); 
            }, 150);
        }
        if(e.content_panel!=null){  $(e.content_panel).click();  }  
        

      }


      for (let item of arrays_validador) {  
          if(item.campo=='input'){ //Caso de inputs
              if($(item.selector).val() ==item.cumple_validacion){ 
                  lanzarError({content_panel: item.panel_id, id_campo: item.selector,  nombre: item.titulo, msje: item.mensaje  });
                  return false;
              }
              if( item.selector=='#dni_beneficiario2' && $('#beneficiario').val() ==item.cumple_validacion){ //Caso Beneficiario
                  lanzarError({content_panel: item.panel_id, id_campo: item.selector,  nombre: item.titulo, msje: item.mensaje  });
                  return false;
              }
              if( item.selector=='#id_tipo_atencion' && $(item.selector).val() ==1 && $("#id_modalidad").val()=="0"){ //Caso modalidad de atención
                  lanzarError({content_panel: item.panel_id, id_campo: "#id_modalidad",  nombre: item.titulo, msje: "Debe elegir una modalidad de atención"  });
                  return false;
              }
          }
          
          if(item.campo=='Longitud'){ 
              if($(item.selector).length == item.cumple_validacion){ 
                  lanzarError({content_panel: item.panel_id, id_campo: item.selector,  nombre: item.titulo, msje: item.mensaje  });
                  return false;
              }
          }
      }

      $('#tblDiagnostico tbody tr').each(function () {
            var panel_id ='#tabDiagnostico'; var title_dx = 'SECCIÓN DE DIAGNOSTICOS';
            let id_diagnostico = $(this).find('.id_diagnostico');
            let id_tipo_diagnostico = $(this).find('.id_tipo_diagnostico');
            let id_categoria_diagnostico = $(this).find('.id_categoria_diagnostico'); 

            if (id_diagnostico.val() === "") {   
              lanzarError({content_panel: panel_id, id_campo: "#"+id_diagnostico.attr("id"),  nombre: title_dx, msje: "Debe ingresar el nombre de diagnóstico"  });
              return false;
            }

            if (id_tipo_diagnostico.val() === "0") {  
              lanzarError({content_panel: panel_id, id_campo: "#"+id_tipo_diagnostico.attr("id"),  nombre: title_dx, msje: "Debe seleccionar el tipo de diagnóstico"  });
              return false;
            }

            if (id_categoria_diagnostico.val() === "0") {  
              lanzarError({content_panel: panel_id, id_campo: "#"+id_categoria_diagnostico.attr("id"),  nombre: title_dx, msje: "Debe seleccionar la categoría del diagnóstico"  });
              return false;
            } 
      });

      $('#tblProcedimiento2 tbody tr').each(function (index) {
          var panel_id ='#tabProcedimiento2'; var title_proc = 'SECCIÓN DE PROCEDIMIENTOS';
          var id_procedimiento = $(this).find('.id_procedimiento');
          var cantidad = $('#procedimiento_cantidad' + index);
          var miSelect = $('#miSelectForm' + index);
          
          if (id_procedimiento.val() === "") {
              lanzarError({content_panel: panel_id, id_campo: '#'+id_procedimiento.attr("id"),  nombre: title_proc, msje: "Debe seleccionar la categoría del diagnóstico"  }); 
              return false;
          }
          if (cantidad.val() === "") {
              lanzarError({content_panel: panel_id, id_campo: '#'+ cantidad.attr("id"),nombre: title_proc, msje: "Debe ingresar la cantidad del procedimiento"  }); 
              return false;
          } else if (parseInt(cantidad.val()) <= 0) {
              lanzarError({content_panel: panel_id, id_campo: '#'+ cantidad.attr("id"),nombre: title_proc, msje: "La cantidad del procedimiento debe ser mayor a 0"  }); 
              return false;
          }
          if (miSelect.val() === "" || miSelect.val() == null) {
              lanzarError({content_panel: panel_id, id_campo:  '#'+ miSelect.attr("id") ,  nombre: title_proc, msje: "Debe seleccionar el diagnóstico para el procedimiento"  }); 
              return false;
          }
      });  
      if(addRecetaVale=='true'){   
            let tbl_atencionQ= $("#tblAtencionQ"); 
            var  v_Fecha_atencion = $('#fecha_atencion').val().split('T'); 
            v_Fecha_atencion=v_Fecha_atencion[0]; 
            var vFechaExpedicion=$("#fecha_expedicion"); 

              if(vFechaExpedicion.val()==''){ 
                lanzarError({content_panel: null, id_campo: `#${vFechaExpedicion.attr('id')}`,  nombre: 'SECCIÓN DE RECETA MEDICA', msje: 'Debe ingresar la fecha de expedición'  });
                return false;
              }

          if(tbl_atencionQ.find('tbody tr').length==0){
            lanzarError({content_panel: null, id_campo: '#'+tbl_atencionQ.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Debe ingresar minimo un producto medico a la Receta Vale"   });
            return false;
          }

          if(tbl_atencionQ.find('tbody tr').length > 0){
            var tbl_atencionQ_filas = tbl_atencionQ.find('tbody tr').length;
            var count_fila = tbl_atencionQ_filas - 1; 
            fila_productosmedicos =  tbl_atencionQ.find('tbody').children('tr'); 
            for (i = 0; i < tbl_atencionQ_filas; i++) { 
                fila_medicamento=fila_productosmedicos[i];
                var cmbMedicamentoAQ = $(fila_medicamento).find('.cmbMedicamentoAQ');
                var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo');
                var posologia = $(fila_medicamento).find('.posologia');
                var valmedentre = $(fila_medicamento).find('.nro_med_solictados');
                var via_administracion =$(fila_medicamento).find('.id_via_administracion');

                if(cmbMedicamentoAQ.val() == "ru='undefined'" || cmbMedicamentoAQ.val() == "undefined" ||  cmbMedicamentoAQ.val()==undefined){
                    lanzarError({content_panel: null, id_campo: "#"+cmbMedicamentoAQ.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Falta seleccionar un producto (dispositivo médico)"  });
                    return false;
                } 
                if(tiempo_periodo.val() == "" || tiempo_periodo.val() ==0) {
                    lanzarError({content_panel: null, id_campo: "#"+tiempo_periodo.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0"  });
                    return false;
                }   
                if(via_administracion.val() == 0 || via_administracion.val() == "undefined" ){
                    lanzarError({content_panel: null, id_campo: "#"+via_administracion.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la via de administración"  });
                    return false;
                }
                if(posologia.val() == ""){
                    lanzarError({content_panel: null, id_campo: "#"+posologia.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la posologia"  });
                    return false;
                }
                if(valmedentre.val() == "" || valmedentre.val()==0 ){
                    lanzarError({content_panel: null, id_campo: "#"+valmedentre.attr("id") ,  nombre: 'SECCIÓN DE RECETA', msje: "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0"  });
                    return false;
                } 

            }


          }
 
      }else{
          var selectElement  = document.querySelector('select[name="id_producto[]"]');
          if(selectElement ){ selectElement.selectedIndex = -1; }
      }
          
      if($('#fecha_intervencion').length > 0){
        var  vFechaAtencion = $('#fecha_atencion').val().split('T'); 
        vFechaAtencion=vFechaAtencion[0]; 
      }
 
      if($('#addDescripcionProcedimiento').attr("aria-pressed") == "true" && $("#descripcion_procedimiento").val()==''){ 
          lanzarError({content_panel: null, id_campo: "#descripcion_procedimiento",  nombre:  'SECCIÓN DE DESCRIPCIÓN DE PROCEDIMIENTO', msje: "Debe de ingresar la descripción del procedimiento"  });
          return false;
      }

      if($('#addAllazgoOpe').attr("aria-pressed") == "true" && $("#allazgo_operatorio").val()==''){ 
          lanzarError({content_panel: null, id_campo: "#allazgo_operatorio",  nombre:  'SECCIÓN DE HALLAZGOS OPERATORIO', msje: "Ingrese el dato de hallazgo operatorio"  });
          return false;
      } 
 
      if(addExamenPatologico == "true"){ 
        var tbl_examen_Patologico=$('#table_procedimiento_examen_patologia tbody tr'); 
        if(tbl_examen_Patologico.length==0){
                  lanzarError({content_panel: null, id_campo: "#table_procedimiento_examen_patologia",  nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Debe ingresar mínimo un procedimiento (Examen patologico)"  });
                  return false;
        } 
        if(tbl_examen_Patologico.length>0){

            tbl_examen_Patologico.each(function (index) {
                var id_procedimiento = $(this).find('.cmbProcedimientoExamenPatologia').val();
                if (id_procedimiento === "") { 
                  lanzarError({content_panel: null, id_campo:  "#"+id_procedimiento.attr("id"),  nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Debe ingresar el nombre del procedimiento (Examen patologico)"  });
                  return false;
                }  
            });

        } 

        if($('#addPiezaOpe').attr("aria-pressed")=="true" &&  $("#pieza_operatorio").val()==''){
              lanzarError({content_panel: null, id_campo: "#pieza_operatorio",  nombre: 'SECCIÓN DE EXAMEN PATOLOGICO', msje: "Ingrese el dato de pieza operatorio"  });
              return false;
        } 
        
      }

      
 
      if($('#addExamenBacteriologico').attr("aria-pressed") == "true" && $("#examen_bacteriologico").val()=="" ){
          lanzarError({content_panel: null, id_campo: "#examen_bacteriologico",  nombre: 'SECCIÓN DE EXAMEN BACTERIOLOGICO', msje: "Ingrese el dato de examen bacteriologico"  }); 
          return false;
      }

      if( $('#addComplicacionMedica').attr("aria-pressed") == "true" && $("#complicacion_medica").val() ==""){
        lanzarError({content_panel: null, id_campo:  "#complicacion_medica",  nombre: 'SECCIÓN DE REGISTRO MÉDICO', msje: "Ingrese el dato de registro médico de complicaciones"  }); 
        return false;
      }

      $('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados)); 

      
      

      if (addDestino === "true") {
        var id_tipo_egreso = $('#id_tipo_egreso').val();
        var $visibleSelect = $('select.condicion-alta:visible:not(:disabled)');
        if (!id_tipo_egreso) {
            lanzarError({content_panel: null, id_campo:  "#id_tipo_egreso",  nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar el destino"  }); 
            return false;
        }

        if ($visibleSelect.length) {
              var id_condicion_alta = $visibleSelect.val();
              if (id_condicion_alta.length === 0) { 
                lanzarError({content_panel: null, id_campo: '#'+$visibleSelect.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe seleccionar la condición de alta"  }); 
                return false;
              }
        } 

        if(id_tipo_egreso==1){
            const validar2 = validarFechaExpedicionVsEgreso();
            let hora_egreso = $('#hora_egreso').val().trim();
            const fechaAtencion = $('#fecha_atencion').val().split('T')[0];
            const fechaEgreso = $('#fecha_egreso').val();

            if(addRecetaVale=='true'){  // Valida2 es validacion de fecha de egreso de Destino + Fecha de Expedicion de Receta Vale
              if (validar2 && !validarFechaExpedicionVsEgreso()) {  return false;  }
            }
            if (fechaEgreso < fechaAtencion) {
              lanzarError({content_panel: null, id_campo: '#fecha_egreso' ,  nombre: 'SECCIÓN DE DESTINO', msje: "La fecha de egreso no puede ser anterior a la fecha de atención"  });
              return false;

            }
            // Expresión regular que acepta HH:mm o HH:mm:ss (24 horas)
            const regex = /^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/;
            if (hora_egreso === '') {
              lanzarError({content_panel: null, id_campo: '#hora_egreso' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la hora de egreso"  });
              return false;
            } else if (hora_egreso === '00:00' || hora_egreso === '00:00:00') {
              lanzarError({content_panel: null, id_campo: '#hora_egreso' ,  nombre: 'SECCIÓN DE DESTINO', msje: "La hora de egreso no puede ser 00:00:00."  });
              return false;
            }else if (!regex.test(hora_egreso)) {
              lanzarError({content_panel: null, id_campo: '#hora_egreso' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Formato de hora inválido. Ingrese al menos hora y minutos (HH:mm)."  });
              return false;
            }

            var es_receta_postquirurgico = Number($('#es_receta_postquirurgico').val());

            if (es_receta_postquirurgico === 1) {
               var tbl_postQuirurgico = $('#frmPrestacion #tablapostquirurgico');
               var ind = tbl_postQuirurgico.find('tbody tr').length;

               if(ind == 0){ 
                  lanzarError({content_panel: null, id_campo: '#'+ tbl_postQuirurgico.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar minimo un producto medico a la Receta Vale PostQuirurgico"  });
                  return false;
               } 

               if(ind > 0){
                    var nind = ind-1;
                    var cmbMedicamentoAQ = $("#cmbMedicamentoAQ"+nind).val();
                    if(cmbMedicamentoAQ == "ru='undefined'" || cmbMedicamentoAQ == ""){
                      tbl_postQuirurgico.find('.deleteFila').eq(ind - 1).parent().parent().remove();
                      ind = ind-1;
                    }
                    fila_productosmedicos= tbl_postQuirurgico.find('tbody').children('tr');
                    for (i = 0; i < ind; i++) {
                      fila_medicamento=fila_productosmedicos[i];
                      var cmbMedicamentoAQ = $(fila_medicamento).find('.cmbMedicamentoAQ');
                      var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo');
                      var posologia = $(fila_medicamento).find('.posologia');
                      var valmedentre = $(fila_medicamento).find('.nro_med_solictados');
                      var via_administracion =$(fila_medicamento).find('.id_via_administracion');
                      if(cmbMedicamentoAQ.val() == "ru='undefined'" || cmbMedicamentoAQ.val() == "undefined"){
                          lanzarError({content_panel: null, id_campo:'#'+ cmbMedicamentoAQ.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Falta seleccionar un producto (dispositivo médico) (Postquirurgico)"  });
                          return false;
                      } 
                      if(tiempo_periodo.val() == "" || tiempo_periodo.val() ==0){
                          lanzarError({content_panel: null, id_campo:'#'+ tiempo_periodo.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0 (Postquirurgico)"  });
                          return false;
                      } 
                      if(via_administracion.val() == 0 || via_administracion.val() == "undefined" ){
                          lanzarError({content_panel: null, id_campo: '#'+via_administracion.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Uno de los productos no cuenta con la via de administración (Postquirurgico)"  });
                          return false;
                      } 
                      if(posologia.val() == ""){
                          lanzarError({content_panel: null, id_campo:'#'+ posologia.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Uno de los productos no cuenta con la posologia (Postquirurgico)"  });
                          return false;
                      } 
                      if(valmedentre.val() == "" || valmedentre.val()==0 ){
                          lanzarError({content_panel: null, id_campo: '#'+valmedentre.attr("id") ,  nombre: 'SECCIÓN DE DESTINO', msje: "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0 (Postquirurgico)"  });
                          return false;
                      } 
                    }
               }
            }else{
                var selectElement  = document.querySelector('select[name="id_producto[]"]');
                if(selectElement ){
                  selectElement.selectedIndex = -1;
                }
            }

        }  
      }
      


 
      
         
 
 
      if(id_tipo_egreso=='1'){
        <?php if(($id_tipo_atencion==1 || $id_tipo_atencion==2) && !$datos_intervencion->es_nuevo_admision_hospitalizacion){?>
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
            lanzarError({content_panel: null, id_campo: '#egreso_resumen' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el resumen de egreso (EPICRISIS)"});
            return false;
        }
        if (egreso_examen_clinico === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_examen_clinico' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen clínico (EPICRISIS)"});
            return false;
        }
        if (egreso_examen_auxiliar === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_examen_auxiliar' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el examen auxiliar (EPICRISIS)"});
            return false;
        }
        if (egreso_evolucion === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_evolucion' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la evolución (EPICRISIS)"});
            return false;
        }
        if (egreso_tratamiento === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_tratamiento' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el tratamiento (EPICRISIS)"});
            return false;
        }
        if (egreso_procedimiento === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_procedimiento' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el procedimiento (EPICRISIS)"});
            return false;
        }
        if (egreso_complicacion === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_complicacion' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar la complicación (EPICRISIS)"});
            return false;
        }
        if (egreso_medidas_generales === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_medidas_generales' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar las medidas generales (EPICRISIS)"});
            return false;
        }
        if (egreso_medicamentos_prescritos === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_medicamentos_prescritos' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar los medicamentos prescritos (EPICRISIS)"});
            return false;
        }
        if (egreso_pronostico === "") {
            lanzarError({content_panel: null, id_campo: '#egreso_pronostico' ,  nombre: 'SECCIÓN DE DESTINO', msje: "Debe ingresar el pronóstico (EPICRISIS)"});
            return false;
        }
        <?php }?>
      }
      
      let inicio = document.getElementById('hora_inicio_operatorio');
      let fin     = document.getElementById('hora_fin_operatorio');

      let inicioAlt = document.getElementById('cirugia_inicio_intra');
      let finAlt    = document.getElementById('cirugia_termino_intra');

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

      if (
          horaInicio && 
          horaFin && 
          horaInicio !== '00:00' && 
          horaFin !== '00:00'
      ) {
          if (horaFin <= horaInicio) { 
               lanzarError({content_panel: null, id_campo: '#hora_fin_operatorio' ,  nombre: 'SECCIÓN DE DESTINO', msje: "La hora de término debe ser mayor que la hora de inicio"});
               return false;  
          }
      }

       
        e.preventDefault(); 
        Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envío...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            backdrop: true, 
            customClass: {
                header: 'swal2-header-generar'
            },
            didOpen: () => {
                Swal.showLoading();
                $('#frmPrestacion').submit(); // Envía el formulario
            }
        }); 
    });

  });

  // Manejo del envío del formulario
  $('#frmPrestacion').on('submit', function(e) {
      e.preventDefault(); // Detiene el envío normal del formulario

      var formData = $(this).serialize(); // Serializa los datos del formulario
      formData += '&' + $.param({ id_farmacia_receta_postqx: $('#id_farmacia_receta_postqx').val()});
      $.ajax({
          url: $(this).attr('action'), // La URL a la que enviar los datos
          method: 'POST', // Método HTTP
          data: formData, // Los datos del formulario serializados
          beforeSend: function() {
             Swal.fire({
                  title: '<div class="titulo-padding"><p>Guardando datos...</p></div>',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  backdrop: true, 
                  customClass: {
                      header: 'swal2-header-generar'
                  },
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
          },
          success: function(response) {
              Swal.close(); // Cierra el loading
              $('input, select, textarea').removeClass('is-invalid');
              $('.invalid-feedback').hide(); // Asegúrate de ocultar todos los mensajes
              if (response.success) {
                if(response.success && response.existe_atencion){
                   Swal.fire({
                      icon: 'warning',
                      title: 'Advertencia',
                      text: 'Ya existe un registro para esta atención quirúrgica. Por favor, verifique la información e intente nuevamente.',
                      timer: 3000,
                      showConfirmButton: false
                  }).then(() => {
                     location.reload();
                  });
                }else{
                  $('#tblIntervencionQx').DataTable().ajax.reload(null, false);
                  Swal.fire({
                      icon: 'success',
                      title: 'Éxito',
                      text: 'Los datos se han guardado correctamente, ahora veremos el detalle.',
                      timer: 3000, // Tiempo en milisegundos antes de cerrar automáticamente (3 segundos)
                      showConfirmButton: false // Elimina el botón "OK"
                  }).then(() => {
                    const id_user = response.id_user; 
                    const id_prestacion = response.id_prestacion;
                    const id_intervencion = response.id_intervencion_quirurgica;
                    const id_cita = response.id_cita;
                    const id_hospitalizacion = response.id_hospitalizacion;
                    $.ajax({
                        url: "/detalle_atencion_quirurgica/"+id_intervencion+"/"+id_prestacion+"/"+id_cita+"/"+id_hospitalizacion,
                        type: "GET",
                        beforeSend: function () {
                           $('#modal-atencion-quirurgica').modal('hide');
                            Swal.fire({
                                title: '<div class="titulo-padding"><p>Cargando datos...</p></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                backdrop: true, 
                                customClass: {
                                    header: 'swal2-header-generar'
                                },
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (result) {
                          $('#modal-detalle-atencion-quirurgica').modal({
                                backdrop: 'static', 
                                keyboard: false
                          });
                          $('#modal-atencion-quirurgica').find('.modal-body').html("");
                          $("#div-modal-detalle-atencion-quirurgica").html(result);
                          $('#modal-detalle-atencion-quirurgica .select-tom').each(function () {
                                new TomSelect(this, {
                                  create: false,
                                  allowEmptyOption: true,
                                  placeholder: 'Seleccione una opción',
                                  sortField: {
                                    field: "text",
                                    direction: "asc"
                                  }
                                });
                          });
                          let hora_duracion = $('#hora_duracion_operatorio').val() || 0;
                          const minutosRaw = parseInt(hora_duracion, 10);
                          if (!isNaN(minutosRaw) && minutosRaw > 0) {
                              const minutos = minutosRaw < 10 ? minutosRaw * 60 : minutosRaw;
                              const horas = Math.floor(minutos / 60);
                              const min = minutos % 60;
                              const hhmm = (String(horas).padStart(2, '0')) + ':' + (String(min).padStart(2, '0'));
                              document.getElementById("hora_duracion_operatorio_calculada").value = hhmm;
                          }
                          $('#modal-detalle-atencion-quirurgica').modal('show');
                          Swal.close();
                          Swal.fire({
                              icon: "success",
                              html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                              timer: 2000,
                              timerProgressBar: true,
                          });
                        },
                        error: function () {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "No se pudo cargar el detalle de la atención quirúrgica.",
                            });
                        },
                    });
                    
                    //const url = `/observar_prestacion/${id_user}/${id_prestacion}/${id_intervencion}`;
                    //window.location.href = url;
                  });
                }
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
  });

  $("#fecha_intervencion").datepicker({
      dateFormat: 'dd-mm-yy',
      minDate: 0,
      autoclose: true,
      changeMonth: true,
      changeYear: true
  });

  // $("#fecha_intervencion").datepicker({
  //     dateFormat: 'dd-mm-yy',
  //     minDate: 0,
  //     autoclose: true,
  //     changeMonth: true,
  //     changeYear: true
  // });

  function soloNumerosMenosCero(e){
    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
  }

  $(document).ready(function(){
      let complicaciones = {!! json_encode(isset($intraoperatorio->complicaciones) ? $intraoperatorio->complicaciones : '') !!};
       if (complicaciones && complicaciones.trim() !== '') {
        $('#complicacionDescripcion').show();
      } else {
        $('#complicacionDescripcion').hide();
      }
     
      $('input[name="complicacion"]').on('change', function() {
          if ($(this).val() === 'si') {
              $('#complicacionDescripcion').show(); 
          } else {
              $('#complicacionDescripcion').hide();
          }
      });

      $('#addComplicacionMedica').on('click', function () {
        var addComplicacionMedica = $('#addComplicacionMedica').attr("aria-pressed");
        $("#complicacionMedica").hide();
        if(addComplicacionMedica == "false"){
          $('#tiene_complicacion_medica').val(1);
          $("#complicacionMedica").show();
        }else{
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
          $('#tiene_descripcion_procedimiento').val(0);
        }
      });
    
      
      
  });
  $(document).ready(function(){
    $('.cmbProcedimientoIntervencion').removeAttr('multiple');
    $.ajax({
        url: '/get_procedimientos',
        type: "GET",
        dataType: "json",
        success: function(result){
          let newOption = "";
          newOption = "<option value=''>--Seleccionar--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' >"+oo.codigo+" - "+oo.descripcion+"</option>";
          });                 
          $('#cmbProcedimientoIntervencionTemp').html(newOption);
        }
      });
  });
  $(document).ready(function () {
    $('#codigo_upss').on('change', function () {
      var dataId = $(this).find('option:selected').attr('data-id_upss');
      $('#id_upss_encargado_atencion').val(id_upss);
    });
    $('#id_upss_encargado').on('change', function () {
      var id_servicio_encargado = $(this).find('option:selected').attr('data-id_servicio_encargado');
      $('#id_servicio_encargado').val(id_servicio_encargado);
    });
    

    var id_upss = $('#codigo_upss option:selected').data('id_upss');
    $('#id_upss_encargado_atencion').val(id_upss);


  });
  $(document).ready(function() {
    // $("#fecha_atencion").datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     maxDate: '0', 
    //     changeMonth: true,
    //     changeYear: true
    // });
    // $('#fecha_atencion').datepicker();
  
  
    $('input[name="parent"]').on("change", function (e) {
      getServicio();
    });
	
    $('#id_servicio,#id_establecimiento').on("change", function (e) {
      $('#calendar').fullCalendar('destroy'); 
      fullCalendar();
      modalTurnos();
      modalHistorial();
    });
    
    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });

    $('#addProcedimientoForm').on('click',function(){
      AddProcedimiento2();
    });
    
    $('#addProducto2').on('click', function () {
      AddProducto2();
    });

    $('#AddCirujano2').on('click', function () {
      AddCirujano2();
    });

    //Registrar Destino
    $('#addDestino').on('click', function () {
      var addDestino = $('#addDestino').attr("aria-pressed");
      if(addDestino == "false"){
        $("#fsDestino").show();
        $('#registrar_destino').val(1);
      }else{
        $('#registrar_destino').val(0);
        $("#fsDestino").hide();
        if($("#id_tipo_egreso").val()=='2'){ //  HOSPITALIZACION
            $("#alta_condicion_tra").hide();
            var value_encargado =  $(`#destino_hospitalizacion #id_upss_encargado option[data-id_servicio_encargado="${$("#id_servicio_encargado").val()}"]`).val() || '';
            $(`#destino_hospitalizacion`).find("#id_upss_encargado")[0].tomselect.setValue(value_encargado);
            $(`#destino_hospitalizacion`).find("#id_pabellon_destino")[0].tomselect.setValue('0');
            $(`#destino_hospitalizacion`).find("#id_area_destino")[0].tomselect.setValue('');
            $(`#destino_hospitalizacion`).find("#id_cama_destino")[0].tomselect.setValue('');
            $("#destino_hospitalizacion").hide();
        }
        if($("#id_tipo_egreso").val()=='1'){ //  DOMICILIARIA
            var fecha_valor_egreso = $(`#destino_domiciliaria`).find("#fecha_egreso").data('fecha_valor');  
            var hora_egreso = new Date(); hora_egreso = hora_egreso.toTimeString().slice(0, 8); // "HH:MM:SS"
            var fecha_valor = $(`#fsNuevaRecetaValePostQuirurgico`).find("#fecha_expedicion_postqx").data('fecha_valor');   
            let valor_farmacia = $(`#fsNuevaRecetaValePostQuirurgico`).find("#id_farmacia_receta_postqx").val();  

            $(`#alta_condicion`).find("#id_condicion_alta")[0].tomselect.setValue('');
            $(`#alta_condicion`).hide();
            $(`#destino_domiciliaria`).find("input,select,textarea").val(''); 
            $(`#destino_domiciliaria`).find("#fecha_egreso").val(fecha_valor_egreso);
            $(`#destino_domiciliaria`).find("#hora_egreso").val(hora_egreso);
            $(`#destino_domiciliaria`).hide();
            if($("#btnNuevoRecetaPostQuirurgico").attr("flag")=='1'){//Si receta esta activa 
                $(`#fsNuevaRecetaValePostQuirurgico`).find("#fecha_expedicion_postqx").val(fecha_valor);
                $(`#fsNuevaRecetaValePostQuirurgico`).find("#id_farmacia_receta_postqx").val(valor_farmacia);
                var $select = $('#tablapostquirurgico [name="id_producto2[]"]');
                if ($select.length > 0) { 
                    $select.closest("tr").remove();
                }
                $("#btnNuevoRecetaPostQuirurgico").click();
            }
        }
        $(`#fsDestino`).find("#id_tipo_egreso")[0].tomselect.setValue('');
      }
    });
    
    $('#frmPrestacion #tblAtencionQ tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Imprime la posición del elemento 'input' en la consola
      console.log("La posición del input es: " + filaPosition);
      eliminarDiagnosticoXProducto(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentos--;
    });
    
    $('#tblCirujanoAtencion tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        // Selecciona la fila completa
        var fila = obj.closest('tr');
        // Obtiene la posición de la fila
        var filaPosition = fila.index();
        // Elimina la fila
        obj.parent().parent().remove();
    });

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var inputVal = obj.parent().siblings().find('input').val();
      let posicion =inputVal.indexOf("-");
      let codigo = inputVal.substring(0,posicion);
      eliminarDiagnosticosarr(codigo);
      console.log("el codigo es" + codigo);
      obj.parent().parent().remove();
    });

    $('#tblProcedimiento2 tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
      const fila = $(this).closest('tr');
      const id_procedimiento = fila.find('input.id_procedimiento').val();
      console.log(id_procedimiento);
      $(`#table_procedimiento_intervencion tbody tr[data-id="${id_procedimiento}"]`).remove();
    });
    AddDiagnostico();
    AddCirujano2();
    //getProducto2();
    modalHistorial();
    modalLaboratorio();
    modalRadiologico2();
    modalRC();

    <?php if (empty($cita->codigo_siteds)): ?> 
      // generarCodigoSiteds();
    <?php endif; ?>

    function modalRC(){
      var id_asegurado = $("#id_asegurado").val();
      $.ajax({
      url: "../modal_historial_rc/"+id_asegurado,
      type: "GET",
      success: function (result) {  

          $("#divRC").html(result);
          // $('#tablemodalmRC').DataTable({
          //   "sPaginationType": "full_numbers",
            
          //   "language": {"url": "/js/Spanish.json"},
          // });
          if ($.fn.DataTable.isDataTable('#tablemodalmRC')) {
            $('#tablemodalmRC').DataTable().destroy();
          }
          $('#tablemodalmRC').DataTable({
            order: [],
            sPaginationType: "full_numbers",
            language: { "url": "/js/Spanish.json" }
          });
      }
      });
    }
	
  });
  $(document).ready(function() {
    // Inicializar los datepickers
    $(".datepicker").datepicker({
      dateFormat: 'dd-mm-yy'
    });
  });
  $('#addProcedimientoExamenPatologia').on('click', function () {
		AddProcedimientoExamenPatologia();
	});
  let tomSelectInstancesProcedimientoExamenPatologia = {};
  function AddProcedimientoExamenPatologia() {
    let newRow = "";
    let ind = $('#table_procedimiento_examen_patologia tbody tr').length;
    
    let item_procedimiento = "";
    $('#cmbProcedimientoExamenPatologiaTemp option').each(function() {
      item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    newRow +='<tr>';
    newRow +='<td><select class="select-tom cmbProcedimientoExamenPatologia" id="cmbProcedimientoExamenPatologia' + ind + '" ind="' + ind + '" name="id_procedimiento_examen_patologia[]" >' + item_procedimiento + '</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';

    newRow +='</tr>';
    $('#table_procedimiento_examen_patologia tbody').append(newRow);
    //$("#cmbProcedimientoExamenPatologia" + ind).select2({max_selected_options: 4}).focus();
    const select = new TomSelect("#cmbProcedimientoExamenPatologia" + ind, {
      maxItems: 1,
      plugins: ['clear_button']
    });
    tomSelectInstancesProcedimientoExamenPatologia["cmbProcedimientoExamenPatologia" + ind] = select;
    select.focus();
    
    $("#cmbProcedimientoExamenPatologia"+ind).on("change", function (e) {
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbProcedimientoExamenPatologia').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });
      if (duplicados > 1 && selectedValue!='') {
        bootbox.alert("El Procedimiento ya ha sido ingresado");
        const currentTS = tomSelectInstancesProcedimientoExamenPatologia["cmbProcedimientoExamenPatologia" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }

      // var flagx = 0;
      // cmb = $(this);
      // id_producto = $("#cmbProcedimientoExamenPatologia"+ind).val();
      // id_user=<?php //echo Auth::user()->id;?>;
      
      // $('.cmbProcedimientoExamenPatologia').each(function(){
      //   var ind_tmp = $(this).val();
      //   if($(this).val() == id_producto)flagx++;
      // });
      
      // if(flagx > 1){
      //   bootbox.alert("El Procedimiento ya ha sido ingresado");
      //   $("#cmbProcedimientoExamenPatologia"+ind).val("").trigger("change");
      //   return false;
      // }
    
    });
    item_procedimiento++;
  }
  $('#table_procedimiento_examen_patologia tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
    var fila = obj.closest('tr');
    var filaPosition = fila.index();
    obj.parent().parent().remove();
  });

  $(document).ready(function(){
      let inicio = document.getElementById('hora_inicio_operatorio');
      let fin = document.getElementById('hora_fin_operatorio');

      if (inicio) inicio.addEventListener('change', calcularDuracion);
      if (fin) fin.addEventListener('change', calcularDuracion);

      function calcularDuracion() {
        const inicioInput = document.getElementById('hora_inicio_operatorio');
        const finInput = document.getElementById('hora_fin_operatorio');
        const inputDuracion = document.getElementById('hora_duracion_operatorio_calculada');
        const inputMinutos = document.getElementById('hora_duracion_operatorio'); 

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



  $(document).ready(function(){
      let cirugia_inicio2 = document.getElementById('cirugia_inicio_intra');
      let cirugia_termino2 = document.getElementById('cirugia_termino_intra');

      if (cirugia_inicio2) cirugia_inicio2.addEventListener('change', calcularDuracionIntra);
      if (cirugia_termino2) cirugia_termino2.addEventListener('change', calcularDuracionIntra);

      function calcularDuracionIntra() {
        const inicioInput = document.getElementById('cirugia_inicio_intra');
        const finInput = document.getElementById('cirugia_termino_intra');
        const inputDuracion = document.getElementById('tipo_real2');
        const inputMinutos = document.getElementById('hora_duracion_operatorio');

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

        const inicioMillis = convertirATiempoEnMilisegundosIntra(inicio);
        let terminoMillis = convertirATiempoEnMilisegundosIntra(termino);

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

        inputDuracion.value = convertirMilisegundosATiempoIntra(diferenciaMillis);
        const minutosTotales = Math.floor(diferenciaMillis / 60000);
        inputMinutos.value = minutosTotales;
      }
      function convertirATiempoEnMilisegundosIntra(hora) {
          const partes = hora.split(':');
          const horas = parseInt(partes[0], 10);
          const minutos = parseInt(partes[1], 10);
          return (horas * 60 * 60 * 1000) + (minutos * 60 * 1000);
      }
      function convertirMilisegundosATiempoIntra(milis) {
          const horas = Math.floor(milis / (1000 * 60 * 60));
          const minutos = Math.floor((milis % (1000 * 60 * 60)) / (1000 * 60));
          return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
      }

  });

  function agregarRecetaPostQuirurgico() {
    var flagReceta = $('#btnNuevoRecetaPostQuirurgico').attr("flag");
    var recetaId = $('#btnNuevoRecetaPostQuirurgico').attr('data-id-receta');

    if (flagReceta == 0) {
      $('#btnNuevoRecetaPostQuirurgico').attr("flag", 1).html("Ocultar Receta");
      $('#es_receta_postquirurgico').val(1);

      getProductosPostQx();
      $("#fsNuevaRecetaValePostQuirurgico").show();
      //$("#btnFirmarRecetaViewPostQuirurgico").show();
      $('#tablapostquirurgico tbody').empty();

    } else {
      $('#btnNuevoRecetaPostQuirurgico').attr("flag", 0).html("Agregar Receta");
      $('#es_receta_postquirurgico').val(0);

      var fecha_valor = $(`#fsNuevaRecetaValePostQuirurgico`).find("#fecha_expedicion_postqx").data('fecha_valor');  
      $(`#fsNuevaRecetaValePostQuirurgico`).find("#fecha_expedicion_postqx").val(fecha_valor);
      var $select = $('#tablapostquirurgico [name="id_producto2[]"]');
      if ($select.length > 0) { 
          $select.closest("tr").remove();
      }
      $("#fsNuevaRecetaValePostQuirurgico").hide();
     // $("#btnFirmarRecetaViewPostQuirurgico").hide();
    }
  }

  $('#addProductoNuevoPostQuirurgico').on('click', function() {
    AddProductoNuevoPostQuirurgico();
  });
  let tomSelectInstancesProductoPostQx = {};
  function AddProductoNuevoPostQuirurgico() {
    var tipo_receta = $('#tipo_receta').val();
    var id_consultorio = $('#id_consultorio').val();
    var id_farmacia = $('#id_farmacia_receta2').val();
    if (tipo_receta == "") {
      bootbox.alert("Seleccione el tipo atencion");
      return false;
    }
    if (id_consultorio == "") {
      bootbox.alert("Seleccione un servicio");
      return false;
    }
    var newRow = "";
    var ind = filasmedicamentospostquirurgico;
    var tabindex = 11;
    var nuevalperiodo = "";
    if (ind > 0) {
      $('.cmbMedicamentoAQ').each(function() {
        var ind_tmp = $(this).attr("ind");
        if (ind_tmp => ind) {
          tabindex += 3;
        }
      });

      fila_medicamentos = $('#frmPrestacion #tablapostquirurgico tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;

      fila_medicamento = fila_medicamentos[idval];
      var valante = $(fila_medicamento).find('#nro_med_entregados').val();
      if (valante == "") {
        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
        return false;
      }

    }

    var item_producto = "";
    $('#cmbMedicamentoTempAQPostQx option').each(function() {
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
    item_via_administracion+='<option value="9">Oftalmologica</option>';
    item_via_administracion+='<option value="10">Inhalatoria</option>';
    item_via_administracion+='<option value="11">Epidural</option>';
    item_via_administracion+='<option value="12">Intratecal</option>';
    item_via_administracion+='<option value="13">Vaginal</option>';
    item_via_administracion+='<option value="14">Intraarticular</option>';
    item_via_administracion+='<option value="17">Parenteral</option>';
    item_via_administracion+='<option value="15">No Aplica</option>';
    item_via_administracion+='<option value="16">Otros</option>';


    newRow +='<tr>';
    newRow +='<td><select class="select-tom cmbMedicamentoAQ2" id="cmbMedicamentoAQP' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto2[]" style="width: 450px">' + item_producto + '</select></td>';
      newRow +='<input type="hidden" name="nro_stocks_almacen2[]" id="nro_stocks_almacen" value=""/>';
      newRow +='<input type="hidden" name="nro_stocks_establecimiento2[]" id="nro_stocks_establecimiento" value=""/>';
      newRow +='<td><input type="text" name="nro_stocks2[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';

    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo2[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
      newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion2[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
    newRow += '<td><input type="text" name="posologia2[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
      ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';


    newRow +='<input type="hidden" name="lotes_lote2[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario2[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento2[]" id="lotes_fecha_vencimiento" value=""/>';
    newRow +='<input type="hidden" name="lotes_precio_unitario2[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad2[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados2[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';


    newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd2' + ind + '" name="miSelectProd2[]"></select></td>';
    newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto2'+ind+'"><i class="fa fa-plus"></i> </button></td>';
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';

    newRow +='</tr>';
    $('#frmPrestacion #tablapostquirurgico tbody').append(newRow);
    const selector = "#cmbMedicamentoAQP" + ind;
    const el = document.querySelector(selector);
    if (el) {
        if (el.tomselect) {
            el.tomselect.destroy();
        }

      const select = new TomSelect("#cmbMedicamentoAQP" + ind, {
        maxItems: 1,
        plugins: ['clear_button']
      });
      tomSelectInstancesProductoPostQx["cmbMedicamentoAQP" + ind] = select;
      select.focus();
    }

    const selectElementProd = document.getElementById('miSelectProd2' + ind);

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

    $("#nro_med_solictados" + ind).keypress(function(e) {
      if (e.which == 13) {
        AddProductoNuevoPostQuirurgico();
        validproductoseleccionadopostqx(<?php echo Auth::user()->id_farmacia;?>,this);
      }
    });


    $("#add_producto2"+ind).click(function(e){
        AddProductoNuevoPostQuirurgico();
        validproductoseleccionadopostqx(<?php echo Auth::user()->id_farmacia;?>,this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    function validproductoseleccionadopostqx(id_farmacia, btn) {
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nro_dias = 30;
      var objTr = $(btn).closest("tr");
      var id_producto = objTr.find(".cmbMedicamentoAQ2").val();
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
          if(msg != "")
          {
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


    $("#cmbMedicamentoAQP"+ind).on("change", function (e) {
      cmb = $(this);
      id_producto = $("#cmbMedicamentoAQP"+ind).val();
      const selectedValue = $(this).val();
      let duplicados = 0;
      $('.cmbMedicamentoAQ2').each(function () {
        if ($(this).val() === selectedValue) duplicados++;
      });
      if (duplicados > 1 && selectedValue!='') {
        bootbox.alert("El Producto ya ha sido ingresado");
        const currentTS = tomSelectInstancesProductoPostQx["cmbMedicamentoAQP" + ind];
        if (currentTS) {
          currentTS.clear();
          currentTS.setTextboxValue('');
          currentTS.blur();
        }
      }


      var id_farmacia_receta = $('#id_farmacia_receta2').val();
      if(id_producto){
        option = {
          url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
          type: 'GET',
          dataType: 'json',
          data: {}
        };

        $.ajax(option).done(function(data) {

          var cantidad = data.cantidad;
          var cantidadEstablecimiento = data.cantidadEstablecimiento;
          var cantidadAlmacen = data.cantidadAlmacen;
          $(cmb).closest("tr").find(".limpia_text").val("");
          //$(cmb).closest("tr").find("#nro_stocks").val(data);
          $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
          $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
          $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
          <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
            ?>
          $(cmb).closest("tr").find(".tiempo_periodo").focus();
          <?php //endif;
            ?>
          $(cmb).closest("tr").find("#nro_med_solictados").val("");
          $(cmb).closest("tr").find("#nro_med_entregados").val("");
          $(cmb).closest("tr").find("#lotes_lote").val("");
          $(cmb).closest("tr").find("#lotes_cantidad").val("");
          $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
          $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
        });
      }
    });
    filasmedicamentospostquirurgico++;
  }

  $('#frmPrestacion #tablapostquirurgico tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    var fila = obj.closest('tr');
    var filaPosition = fila.index();
    console.log("La posición del input es: " + filaPosition);
    eliminarDiagnosticoXProducto(filaPosition);
    obj.parent().parent().remove();
    filasmedicamentospostquirurgico--;
  });

  function getProductosPostQx(){

    $('#tablapostquirurgico tbody').html('');

    if($('#frmPrestacion #id_servicio').val()=="") return false;

    //var id_ipress 		= $('#id_ipress').val();
    var id_ipress 		= $('#frmPrestacion #id_establecimiento').val();
    var id_consultorio 	= 	<?php echo $id_servicio;?>;
    //var tipo_receta		= $('#tipo_receta').val();
    //var id_tiporeceta 	= $('#id_tiporeceta').val();
    var id_tiporeceta 	= $('#frmPrestacion #id_tipo_atencion').val();
    var id_farmacia     = $('#frmPrestacion #id_farmacia_receta_postqx').val();
    $('#id_farmacia_postqx').val(id_farmacia);
    //console.log(id_farmacia);
    var nivel     = $('#id_nivel').val();
    var x  = $('#nro_interconsultas').val();
    var dni = $('#dni_beneficiario2').val();
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
          //url: '/obtener_productos_atencion_quirurgica/'+id_ipress+'/' + id_farmacia+ '/' + id_consultorio,
          success: function(result){
            var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
            $('#cmbMedicamentoTempAQPostQx').html(newOption);
          },
          complete: function () {
                  Swal.close();
                },
      });

    }
  }

  $(document)
    .off('change.sincronizarEgreso', '#fecha_egreso')
    .on('change.sincronizarEgreso', '#fecha_egreso', function () {
        $('#fecha_expedicion_postqx').val(this.value);
  });
  function validarFechaExpedicionPostQX1() {
      const $expedicion = $('#fecha_expedicion_postqx');
      const $egreso = $('#fecha_egreso');

      if (!$expedicion.length || !$egreso.length) return false;

      const fechaExpedicion = formatear_fechaDate($expedicion.val());
      const fechaEgreso = formatear_fechaDate($egreso.val());

      if (!fechaEgreso) {
          Swal.fire({
              icon: 'warning',
              title: 'Fecha de egreso requerida',
              text: 'Ingrese una fecha de egreso válida.'
          });

          return false;
      }

      if (fechaExpedicion !== fechaEgreso) {
          $expedicion.val(fechaEgreso);

          Swal.fire({
              icon: 'warning',
              title: 'Validación de fecha',
              text: 'La fecha de expedición debe ser igual a la fecha de egreso. Se corrigió automáticamente.'
          });

          return false;
      }

      return true;
  }

  function validarFechaExpedicionVsEgreso() {
    const egresoInput = document.getElementById('fecha_egreso');
    const expedicionInput = document.getElementById('fecha_expedicion_postqx');

    if (!egresoInput || !expedicionInput) return false;

    const fechaEgreso = formatear_fechaDate(egresoInput.value);
    const fechaExpedicion = formatear_fechaDate(expedicionInput.value);

    if (!fechaEgreso) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha de egreso requerida',
            text: 'Ingrese una fecha de egreso válida.'
        });

        return false;
    }

    if (fechaExpedicion !== fechaEgreso) {
        expedicionInput.value = fechaEgreso;

        Swal.fire({
            icon: 'warning',
            title: 'Fecha de expedición corregida',
            text: 'La fecha de expedición debe ser igual a la fecha de egreso.'
        });

        return false;
    }

    return true;
}

</script>

<style>
  .validado_incorrecto{
        border: 2px red solid !important;
    }
  
  .loader {
    width: 100%;
    height: 100%;
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  }
  
  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }
  .modal-dialog {
    width: 90%;
  }
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }
  .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 7px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }
  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }
  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
    cursor: pointer;
  }
  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }
  input[readonly] {
    background-color: #eeeeee;
    text-align: center
  }
  span:focus,
  textarea:focus,
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="datetime"]:focus,
  input[type="datetime-local"]:focus,
  input[type="date"]:focus,
  input[type="month"]:focus,
  input[type="time"]:focus,
  input[type="week"]:focus,
  input[type="number"]:focus,
  input[type="email"]:focus,
  input[type="url"]:focus,
  input[type="search"]:focus,
  input[type="tel"]:focus,
  input[type="color"]:focus,
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }
  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    background: transparent;
    color: #009966
  }
  .fc-day-grid-event :hover {
    color: #009966
  }
  .fc-content {
    padding: 5px !important;
  }
  .fc-title {
    text-align: center;
    font-size: 12px
  }
    .custom-col {
    margin-left: 10px; 
  }
  .modal-header {
    padding: 0.5rem 1rem 0 1rem;
  }

  .swal2-styled.swal2-confirm {
    background-color:#3c763d !important; 
  }
  .swal2-modal .swal2-content td{
    text-align: left !important;
  }
  .encabezado {
    color: white !important;
  }
  .thead-resultados{
    background-color:#0b5a00 !important;
  }
  .thead-resultados-detalle{
    background-color:#54ac48 !important;
  }
  .tbody-resultados-detalle{
    background-color:#f1f1f1 !important;
  }
  .icon-visible-result {
      color: rgb(95, 129, 95);
  }
  .icon-visible-result:hover {
      color: green;
  }
  .icon-hidden-result {
      color: red;
  }
  .toggleExamenesResult {
      font-size: 2rem;
  }
  .hide-element-result {
      display: none;
  }
  .modal-header-examen {
    padding: 0.5rem;
  }
  .swal2-icon.swal2-warning {
      font-size: 15px;
  }
  .btn-toggle {
      margin: 0 4rem;
      padding: 0;
      position: relative;
      border: none;
      height: 1.5rem;
      width: 3rem;
      border-radius: 1.5rem;
      color: #6b7381;
      background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
      outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
      line-height: 1.5rem;
      width: 4rem;
      text-align: center;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: absolute;
      bottom: 0;
      transition: opacity 0.25s;
  }
  .btn-toggle:before {
      content: 'NO';
      left: -4rem;
  }
  .btn-toggle:after {
      content: 'SI';
      right: -4rem;
      opacity: 0.5;
  }
  .btn-toggle>.handle {
      position: absolute;
      top: 0.1875rem;
      left: 0.1875rem;
      width: 1.125rem;
      height: 1.125rem;
      border-radius: 1.125rem;
      background: #fff;
      transition: left 0.25s;
  }
  .btn-toggle.active {
      transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
      left: 1.6875rem;
      transition: left 0.25s;
  }
  .btn-toggle.active:before {
      opacity: 0.5;
  }
  .btn-toggle.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
      line-height: -0.5rem;
      color: #fff;
      letter-spacing: 0.75px;
      left: 0.4125rem;
      width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
      text-align: right;
  }
  .btn-toggle.btn-sm:after {
      text-align: left;
      opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
      opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
      display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
      color: #6b7381;
  }
  .btn-toggle.active {
      background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
      margin: 0 5rem;
      padding: 0;
      position: relative;
      border: none;
      height: 2.5rem;
      width: 5rem;
      border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
      outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
      line-height: 2.5rem;
      width: 5rem;
      text-align: center;
      font-weight: 600;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: absolute;
      bottom: 0;
      transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
      content: 'NO';
      left: -5rem;
  }
  .btn-toggle.btn-lg:after {
      content: 'SI';
      right: -5rem;
      opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
      position: absolute;
      top: 0.3125rem;
      left: 0.3125rem;
      width: 1.875rem;
      height: 1.875rem;
      border-radius: 1.875rem;
      background: #fff;
      transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
      transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
      left: 2.8125rem;
      transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
      opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
      line-height: 0.5rem;
      color: #fff;
      letter-spacing: 0.75px;
      left: 0.6875rem;
      width: 3.875rem;
  }
  .btn-toggle.btn-lg.btn-sm:before {
      text-align: right;
  }
  .btn-toggle.btn-lg.btn-sm:after {
      text-align: left;
      opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:before {
      opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
      display: none;
  }
  .btn-toggle.btn-sm {
      margin: 0 0.5rem;
      padding: 0;
      position: relative;
      border: none;
      height: 1.5rem;
      width: 3rem;
      border-radius: 1.5rem;
  }
  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
      outline: none;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
      line-height: 1.5rem;
      width: 0.5rem;
      text-align: center;
      font-weight: 600;
      font-size: 0.55rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: absolute;
      bottom: 0;
      transition: opacity 0.25s;
  }
  .btn-toggle.btn-sm:before {
      content: 'NO';
      left: -0.5rem;
  }
  .btn-toggle.btn-sm:after {
      content: 'SI';
      right: -0.5rem;
      opacity: 0.5;
  }
  .btn-toggle.btn-sm>.handle {
      position: absolute;
      top: 0.1875rem;
      left: 0.1875rem;
      width: 1.125rem;
      height: 1.125rem;
      border-radius: 1.125rem;
      background: #fff;
      transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active {
      transition: background-color 0.25s;
  }
  .btn-toggle.btn-sm.active>.handle {
      left: 1.6875rem;
      transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active:before {
      opacity: 0.5;
  }
  .btn-toggle.btn-sm.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
      line-height: -0.5rem;
      color: #fff;
      letter-spacing: 0.75px;
      left: 0.4125rem;
      width: 2.325rem;
  }
  .btn-toggle.btn-sm.btn-sm:before {
      text-align: right;
  }
  .btn-toggle.btn-sm.btn-sm:after {
      text-align: left;
      opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:before {
      opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
      display: none;
  }
  .btn-toggle.btn-xs {
      margin: 0 0;
      padding: 0;
      position: relative;
      border: none;
      height: 1rem;
      width: 2rem;
      border-radius: 1rem;
  }
  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
      outline: none;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
      line-height: 1rem;
      width: 0;
      text-align: center;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: absolute;
      bottom: 0;
      transition: opacity 0.25s;
  }
  .btn-toggle.btn-xs:before {
      content: 'NO';
      left: 0;
  }
  .btn-toggle.btn-xs:after {
      content: 'SI';
      right: 0;
      opacity: 0.5;
  }
  .btn-toggle.btn-xs>.handle {
      position: absolute;
      top: 0.125rem;
      left: 0.125rem;
      width: 0.75rem;
      height: 0.75rem;
      border-radius: 0.75rem;
      background: #fff;
      transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active {
      transition: background-color 0.25s;
  }
  .btn-toggle.btn-xs.active>.handle {
      left: 1.125rem;
      transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active:before {
      opacity: 0.5;
  }
  .btn-toggle.btn-xs.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
      line-height: -1rem;
      color: #fff;
      letter-spacing: 0.75px;
      left: 0.275rem;
      width: 1.55rem;
  }
  .btn-toggle.btn-xs.btn-sm:before {
      text-align: right;
  }
  .btn-toggle.btn-xs.btn-sm:after {
      text-align: left;
      opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:before {
      opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:after {
      opacity: 1;
  }
  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
      display: none;
  }
  .btn-toggle.btn-secondary {
      color: #6b7381;
      background: #bdc1c8;
  }
  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
      color: #6b7381;
  }
  .btn-toggle.btn-secondary.active {
      background-color: #ff8300;
  }

  .panel-heading div {
  margin-top: -18px;
  font-size: 15px;
  }

  .panel-heading div span {
  margin-left: 5px;
  }

  .panel-body {
  display: block;
  }


  .intro-contenedor {
      padding-top: 1rem;
  }

  #blur-container {
    position: relative;
  }
  .blur-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    z-index: 999;
  }

  .eliminado {
    position: relative;
  }

  .eliminado::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 92%;
    height: 2px;
    background-color: red;
    transform: translateY(-50%);
    z-index: 1;
  }

  .eliminado td {
    position: relative;
    z-index: 0;
  }

  .swal2-popup {
    width: 50em !important;
  }

  .swal2-popup,
  .swal2-header {
      padding: 0 !important;
  }

  .swal2-header-generar {
      background: #3c763d;
  }

  .swal2-html-container {
      margin: 0 !important;
      padding: 1rem 0 !important;
  }

  .swal2-modal {
      min-height: auto;
  }

  .swal2-actions {
      margin-bottom: 2rem;
  }

  .custom-swal-button-cerrar {
    background-color: #3c763d !important;
    color: white;
  }

  .custom-swal-button-cerrar:hover {
    -webkit-box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
    box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
  }

  .modal-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh; /* Hace que el modal esté centrado en la ventana completa */
  }
  .swal2-icon.swal2-warning {
    font-size: 15px;
    line-height: 80px;
    text-align: center;
  }
  .btn-red {
    background-color: red !important; /* Cambia el color de fondo a rojo */
    color: white !important;         /* Cambia el texto a blanco */
  }

  .btn-cancel {
    background-color: grey !important; /* Opcional: Cambia el color de fondo del botón de cancelar */
    color: white !important;
  }

  /* Estilo para el input con error */
  .is-invalid {
      border-color: #dc3545; /* Color de borde rojo */
  }

  /* Estilo para el mensaje de error */
  .invalid-feedback {
      display: none; /* Ocultar por defecto */
      color: #dc3545; /* Color del texto rojo */
  }

  input[type="checkbox"].is-invalid {
    box-shadow: 0 0 0 2px red; /* Simula un borde rojo */
  }

  /* Mostrar el mensaje de error cuando el input tiene clase is-invalid */
  .is-invalid + .invalid-feedback {
      display: block; /* Mostrar el mensaje de error */
  }
  .input-error {
    border: 2px solid red !important;
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



<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
    $complicaciones = ['SIN COMPLICACIONES', 'COMPLICADO'];
?>

<div class="modal fade" id="modal-info-paciente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
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
                            <input type="text" class="form-control" value="<?php if(!empty($hospitalizacion->descripcion)){ echo $hospitalizacion->descripcion;}?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="">DOC. IDENTIDAD Y NOMBRES</label>
                            <input type="text" class="form-control" value="<?php if(!empty($hospitalizacion)){ echo $hospitalizacion->tipo_doc_acompaniante . ' ' . $hospitalizacion->nro_doc_acompaniante . ' ' . $hospitalizacion->nombre_acompaniante; }?>" style="text-align:left" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="">TELÉFONO</label>
                            <input type="text" class="form-control" value="<?php if(!empty($hospitalizacion)){ echo $hospitalizacion  ->telefono_acompaniante;}?>" style="text-align:left" readonly>
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

<div class="row"> 
    <div class="panel with-nav-tabs panel-success">
        <div class="panel-heading">
          <?php 
            $disabled_atencion = "";
            if($flag==1){
              $disabled_atencion = "disabled='disabled'";
              echo '<div class="col-sm-12">
                  <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                    El paciente citado, ya no esta asegurado y por consiguiente no tiene derecho de atención.
                  </div>
                </div>';
            }
          ?>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCIÓN</a></li>
                  <li><a href="#tab2" data-toggle="tab" style="font-size:12px">HISTORIAL CENTRO QUIRÚRGICO</a></li>
                  <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a></li>
                  <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
                  <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab2default">
                    <div id="FormularioQuirurgico" name="FormularioQuirurgico" class="form-horizontal">
                        <div class="panel panel-default">
                            <div class="panel-heading">DATOS DE LA ATENCIÓN</div>
                            <div class="panel-body">
                                <div class="form-group">
                                  <form class="form-horizontal" method="post" action="{{ route('registro_prestacion_transaction')}}"
                                     id="frmPrestacion" autocomplete="off">  
                                     <div class="form-group"> 
                                      <div class="col-md-2">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
                                            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                            <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                            <input type="hidden" name="id_cita" id="id_cita" value="0">
                                            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{!empty($cita->id_establecimiento)?$cita->id_establecimiento:(!empty($hospitalizacion->id_establecimiento)?$hospitalizacion->id_establecimiento:'')}}">
                                            <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{!empty($cita->id_asegurado)?$cita->id_asegurado:(!empty($hospitalizacion->id_asegurado)?$hospitalizacion->id_asegurado:'')}}">
                                            <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio;?>">

                                            <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="{{!empty($cita->id_sub_consultorio)?$cita->id_sub_consultorio:''}}">
                                            <!-- <input type="hidden" name="id_medico" id="id_medico" value="{{!empty($cita->id_medico)?$cita->id_medico:(!empty($hospitalizacion->id_medico)?$hospitalizacion->id_medico:'')}}"> -->
                                            <!-- <input type="hidden" value="<?php //echo $cita->dni?>" name="dniMedico" id="dniMedico"> -->
                                            <input type="hidden" name="referencia_activo" id="referencia_activo" value="">
                                            <input type="hidden" name="contrareferencia_activo" id="contrareferencia_activo" value="">
                                            <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $id_tipo_atencione;?>">
                                            <input type="hidden" name="id_tipo_atencion_origen" id="id_tipo_atencion_origen" value="<?php echo $id_tipo_atencion;?>">
                                            <input type="hidden" value="0" name="id_prestacion">
                                            <!-- <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="<?php //echo $cita->id_sub_consultorio?>"> -->


                                            <input type="hidden" value="{{ !empty($prestacion->tipo_doc_ident)?$prestacion->tipo_doc_ident:(!empty($titular->tipo_doc_ident)?$titular->tipo_doc_ident:'') }}" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
                                            <input type="hidden" class="form-control" value="{{ $titular->nro_doc_ident }}" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                                            <input type="hidden" name="id_intervencion_quirurgica" value="{{ $intervencion->id }}">
                                            <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                                            <!-- <input type="hidden" value="@if(!empty($dato_cama)){{$dato_cama->id_cuarto}}@endif" name="id_area_actual">
                                            <input type="hidden" value="@if(!empty($dato_cama)){{$dato_cama->id}}@endif" name="id_cama_actual" id="id_cama_actual"> -->
                                            <input type="hidden" value="@if($hospitalizacion){{$hospitalizacion->id}}@endif" name="id_hospitalizacion" id="id_hospitalizacion">
                                            <input type="hidden"  name="id_upss_encargado_atencion" id="id_upss_encargado_atencion">
                                            <input type="hidden"  name="id_servicio_encargado" id="id_servicio_encargado" value="{{$id_servicio}}">
                                            
                                            <input type="hidden" id="dni_beneficiario2" value="{{$titular->nro_doc_ident}}">
                                            <input type="hidden" id="registrar_destino" name="registrar_destino" value="0">

                                            <input type="hidden"  name="tiene_hallazgo" id="tiene_hallazgo" value="0" />
                                            <input type="hidden"  name="tiene_pieza" id="tiene_pieza" value="0" />
                                            <input type="hidden"  name="tiene_examen_bact" id="tiene_examen_bact" value="0" />
                                            <input type="hidden"  name="tiene_examen_pat" id="tiene_examen_pat" value="0" />
                                            <input type="hidden"  name="tiene_complicacion_medica" id="tiene_complicacion_medica" value="0" />
                                            <input type="hidden"  name="tiene_descripcion_procedimiento" id="tiene_descripcion_procedimiento" value="0"/>

                                            <input type="hidden"  name="tipodoc_titular" id="tipodoc_titular" value="{{$titular->tipo_doc_titular}}"/>
                                            <input type="hidden"  name="nrodoctitular" id="nrodoctitular" value="{{$titular->dni_titular}}"/>
                                            <input type="hidden"  name="nombre_titular" id="nombre_titular" value="{{$titular->nombre_titular}}"/>
                                            <input type="hidden"  name="paterno_titular" id="paterno_titular" value="{{$titular->paterno_titular}}"/>
                                            <input type="hidden"  name="materno_titular" id="materno_titular" value="{{$titular->materno_titular}}"/>                                         
                                            <input type="hidden" id="hora_duracion_operatorio" name="hora_duracion_operatorio" >
                                            <!-- <input type="hidden" id="id_farmacia_receta_postquirurgico" name="id_farmacia_receta_postquirurgico" value="128"> -->
                                            <input type="hidden" id="id_farmacia_postqx" name="id_farmacia_postqx" >
                                            <input type="hidden" id="es_receta_postquirurgico" name="es_receta_postquirurgico" value="0">

                                            
                                          
                                            @if (isset($salud_materna->id_gestacion))
                                              <input type="hidden" name="esta_gestando" value="true">
                                            @else
                                              <input type="hidden" name="esta_gestando" value="false">
                                            @endif
                                            
                                            <?php 
                                            switch ($id_tipo_atencione) {
                                              case '1': //AMBULATORIO
                                                $descripcion='SERVICIO'; $descripcion2='CONSULTORIO'; $atencion='AMBULATORIO';break;
                                              case '2': //EMERGENCIA
                                                $descripcion='PRIORIDAD'; $descripcion2='TOPICO';$atencion='EMERGENCIA';break;
                                              case '3': //HOSPITALIZACION
                                                $descripcion='SALA'; $descripcion2='SERVICIO';$atencion='HOSPITALIZACION';break;                                      		
                                            }
                                          ?>
                                            <label>TIPO DE ATENCI&Oacute;N</label>
                                            <input tabindex="6" readonly="" type="text" class="form-control"
                                              value="<?php echo $atencion?>" name="tipo_atencione" id="tipo_atencione"
                                              style="text-align:left">
                                        </div>
                                        <div class="col-md-2" id="divModalidad" <?php //if($id_tipo_atencione!=3) echo 'style="display:none"'; ?>>
                                            <label for="">MODALIDAD</label>
                                            <select class="select-tom" name="id_modalidad" id="id_modalidad"
                                              tabindex="8" style="width: 100%">
                                              <option value="">--Seleccionar--</option>
                                              <option value="1" <?php if($modalidad == 1 ) echo 'selected'; ?>>Presencial</option>
                                              <option value="2" <?php if($modalidad == 2 ) echo 'selected'; ?>>Virtual</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <label for="">COD. SITEDS SUSALUD</label>
                                            <input tabindex="1" type="text" 
                                            class="form-control"  id="codigo_siteds" name="codigo_siteds"  onkeypress="return esEntero(event);" 
                                            value="{{!empty($cita->codigo_siteds)?$cita->codigo_siteds:''}}" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important;text-align:left;" 
                                            maxlength="12" pattern="[0-9]*">   -->
                                            <!-- @if (!isset($cita->codigo_siteds))  -->
                                                <!--span class="input-group-btn">
                                                  <button type="button" id="btn_generate_siteds" onclick="generarCodigoSiteds();"
                                                    class="btn btn-sm btn-info" style="margin-top: -1px">Generar</button>
                                                </span-->
                                            <!-- @endif                                                   -->
                                        <!-- </div> -->
                                        <div class="col-md-3">
                                            <label for="">SERVICIO</label>
                                              <input type="text" name="servicio" class="form-control" value="{{ !empty($datos_interconsulta)? $servicio->nombre : (isset($hospitalizacion->servicio_destino) ? $hospitalizacion->servicio_destino: $servicio->nombre)}}" style="text-align:left" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">SALA</label>
                                            <input type="text" name="sala" class="form-control" value="<?php echo isset($hospitalizacion->cama)?$hospitalizacion->cama: (isset($datos_intervencion->sala) ? $datos_intervencion->sala : '');?>" style="text-align:left" readonly>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-2" style="margin-left:13px;margin-top:10px;">
                                            <label for="">FECHA DE ATENCIÓN</label>
                                            <!-- <input tabindex="6" type="text" class="form-control datepicker"
                                            value="<?php //echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                            name="fecha_atencion" id="fecha_atencion" style="text-align:left;" data-date-end-date="0d" readonly>
                                             -->
                                            <!-- <input 
                                                    tabindex="6" 
                                                    type="date" 
                                                    class="form-control"
                                                    value="<?php //echo date('Y-m-d', strtotime($fechaServidor)); ?>"
                                                    name="fecha_atencion" 
                                                    id="fecha_atencion" 
                                                    max="<?php //echo date('Y-m-d'); ?>" 
                                                    style="text-align:left;"> -->
                                          <input 
                                              tabindex="6" 
                                              type="datetime-local" 
                                              class="form-control"
                                              value="{{   \Carbon\Carbon::parse((!empty($intervencion->fecha_intervencion) ? $intervencion->fecha_intervencion: $fechaServidor))->format('Y-m-d\TH:i')   }}" 
                                              name="fecha_atencion" 
                                              id="fecha_atencion" 
                                              max="{{ date('Y-m-d\TH:i') }}"
                                              style="text-align:left;">
                                        </div>
                                        <!-- <div class="col-md-2" style="margin-top:10px;">
                                            <label for="">HORA DE ATENCION</label>
                                            <input tabindex="7" type="time" class="form-control" value="{{date('H:m')}}" style="text-align:left" readonly>
                                        </div> -->
                                        <div class="col-md-5" style="margin-left:-8px;margin-top:10px;">
                                            <label for="">CODIGO UPSS</label>
                                            <select tabindex="1" class="select-tom" name="codigo_upss" id="codigo_upss" style="width:100%">
                                              <?php if(count($codigo_upss)>1) : ?>
                                                <option value="">-Seleccionar-</option>
                                              <?php endif; ?>
                                              <?php foreach($codigo_upss as $row):?>
                                              <option value="<?php echo $row->codigo?>" data-id_upss="<?php echo $row->id?>">
                                                <?php echo $row->codigo?> - <?php echo $row->descripcion_upss?></option>
                                                <?php endforeach;?>
                                          </select>                                                
                                        </div>
                                        <!-- <div class="col-md-3" style="margin-left:-7px;margin-top:10px;">
                                            <label for="">NOMBRE DE LA UPSS</label>
                                            <input type="text" class="form-control"  style="text-align:left" readonly>
                                        </div> -->
                                        <div class="col-md-3" style="margin-left:-7px;margin-top:10px;">
                                            <label for="tipo">NOMBRE DE LA IPRESS</label>
                                            <input tabindex="4" readonly="" type="text" class="form-control"
                                            value="{{!empty($cita->establecimiento)?$cita->establecimiento:(!empty($hospitalizacion->establecimiento)?$hospitalizacion->establecimiento:'')}}" name="cc" id="cc"
                                            style="text-align:left">
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="panel panel-success">
                                              <div class="panel-heading">INFORMACIÓN DEL PACIENTE <a href="javascript:void(0)" class="btn btn-xs btn-success pull-right" onclick="mostrarModalInfoPaciente()">VER</a></div>
                                              <div class="panel-body">
                                                  <div class="form-group">
                                                      <div class="col-md-2">
                                                          <label for="">DOCUMENTO DE IDENTIDAD</label>
                                                          <input tabindex="21" readonly="" type="text" class="form-control" value="<?php echo $titular->tipo_doc_ident . ' ' . $titular->nro_doc_ident?>" name="cc" id="cc" style="text-align:left">
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">APELLIDO PATERNO</label>
                                                          <input tabindex="15" readonly="" type="text" class="form-control"
                                                          value="{{!empty($cita->asegurado_paterno)?$cita->asegurado_paterno:(!empty($titular->paterno)?$titular->paterno:'')}}" name="paterno_beneficiario"
                                                          id="paterno_beneficiario" style="text-align:left">
                                                        
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">APELLIDO MATERNO</label>
                                                          <input tabindex="16" readonly="" type="text" class="form-control"
                                                          value="{{!empty($cita->asegurado_materno)?$cita->asegurado_materno:(!empty($titular->materno)?$titular->materno:'')}}" name="materno_beneficiario"
                                                          id="materno_beneficiario" style="text-align:left">
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">NOMBRES</label>
                                                          <input tabindex="17" readonly="" type="text" class="form-control"
                                                          value="{{!empty($cita->asegurado_nombre)?$cita->asegurado_nombre:(!empty($titular->nombre)?$titular->nombre:'')}}" name="nombre_beneficiario"
                                                          id="nombre_beneficiario" style="text-align:left">
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">PARENTESCO</label>
                                                          <input tabindex="14" readonly="" value="{{!empty($cita->parentesco)?$cita->parentesco:(!empty($hospitalizacion->descripcion)?$hospitalizacion->descripcion:'')}}" type="text"
                                                          name="tipo_beneficiario" id="tipo_beneficiario" class="form-control" style="text-align:left">                                                    
                                                      </div>
                                                  </div>
                                                  <div class="form-group">
                                                      <div class="col-md-2">
                                                          <label for="">F. DE NACIMIENTO</label>
                                                          <input tabindex="18" readonly="" type="text" class="form-control"
                                                          value="{{!empty($cita->fecha_nac)?$cita->fecha_nac:(!empty($titular->fecha_nac)?$titular->fecha_nac:'')}}" name="fecnacafiliado"
                                                          id="fecnacafiliado" style="text-align:left">                                                   
                                                      </div>
                                                      <div class="col-md-1">
                                                          <label for="">CIP</label>
                                                          <input type="text" class="form-control" value="{{!empty($cita->asegurado_cip)?$cita->asegurado_cip:(!empty($titular->cip)?$titular->cip:'')}}" style="text-align:left" readonly>
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">GRADO</label>
                                                          <input tabindex="19" readonly="" value="{{!empty($cita->grado)?$cita->grado:(!empty($titular->grado)?$titular->grado:'')}}" type="text" name="grado"
                                                          id="grado" class="form-control" style="text-align:left">
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">SITUACION</label>
                                                          <input tabindex="19" readonly="" value="{{!empty($cita->situacion_paciente)?$cita->situacion_paciente:''}}" type="text" name="situacion_paciente"
                                                            id="situacion_paciente" class="form-control" style="text-align:left">
                                                      </div>
                                                      <div class="col-md-1">
                                                          <label for="">SEXO</label>
                                                          <input tabindex="13" readonly="" type="text" class="form-control" 
                                                          value="{{ !empty($cita->sexo)? $cita->sexo: (!empty($titular->sexo)?$titular->sexo:'') }}" name="nomsexo" id="nomsexo"
                                                          style="text-align:left">
                                                      </div>
                                                      <div class="col-md-1">
                                                          <label for="">EDAD</label>
                                                          <input type="text" class="form-control" value="{{!empty($cita->asegurado_edad)?$cita->asegurado_edad:(!empty($titular->edadafiliado)?$titular->edadafiliado:'')}}" style="text-align:left" readonly>
                                                      </div>
                                                      <div class="col-md-1">
                                                          <label for="">T. SANGRE</label>
                                                          <input type="text" class="form-control" value="{{!empty($cita->descripcion_corta)?$cita->descripcion_corta:(!empty($titular->tipo_sangre)?$titular->tipo_sangre:'')}}" style="text-align:left" readonly>
                                                      </div>
                                                      <div class="col-md-2">
                                                          <label for="">DIRECCIÓN</label>
                                                          <input tabindex="19" readonly="" value="{{!empty($cita->domicilio)?$cita->domicilio:(!empty($titular->domicilio)?$titular->domicilio:'')}}" type="text" name="domicilio"
                                                            id="domicilio" class="form-control" style="text-align:left">
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="panel panel-success">
                                          <div class="panel-heading">INFORMACIÓN DEL RESPONSABLE DE LA ATENCION</div>
                                          <div class="panel-body">
                                              <div class="form-group">
                                                  <div class="col-md-3">
                                                      <label for="">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                                      <input tabindex="11" readonly="" type="text" class="form-control" value="DNI" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="">N° DE DOCUMENTO DE IDENTIDAD</label>
                                                      <input tabindex="12" readonly="" type="text" class="form-control" value="{{!empty($cita->dni)?$cita->dni:(!empty($hospitalizacion->dni_medico)?$hospitalizacion->dni_medico:'')}}" name="cc" id="cc" style="text-align:left">
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <div class="col-md-3">
                                                      <label for="">APELLIDO PATERNO</label>
                                                      <input tabindex="15" readonly="" type="text" class="form-control" value="{{!empty($cita->medico_paterno)?$cita->medico_paterno:(!empty($hospitalizacion->paterno_medico)?$hospitalizacion->paterno_medico:'')}}" name="cc" id="cc" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="">APELLIDO MATERNO</label>
                                                      <input tabindex="16" readonly="" type="text" class="form-control" value="{{!empty($cita->medico_materno)?$cita->medico_materno:(!empty($hospitalizacion->materno_medico)?$hospitalizacion->materno_medico:'')}}" name="cc" id="cc" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="">NOMBRES</label>
                                                      <input tabindex="17" readonly="" type="text" class="form-control" value="{{!empty($cita->medico_nombre)?$cita->medico_nombre:(!empty($hospitalizacion->nombre_medico)?$hospitalizacion->nombre_medico:'')}}" name="cc" id="cc" style="text-align:left">
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <div class="col-md-3">
                                                      <label for="">PROFESIÓN</label>
                                                      <input tabindex="27" readonly="" value="{{!empty($cita->profesion)?$cita->profesion:(!empty($hospitalizacion->profesion)?$hospitalizacion->profesion:'')}}" type="text" name="profesion" id="profesion" class="form-control" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="">N° COLEGIATURA</label>
                                                      <input tabindex="25" readonly="" type="text" class="form-control" value="{{!empty($cita->colegiatura)?$cita->colegiatura:(!empty($hospitalizacion->colegiatura)?$hospitalizacion->colegiatura:'')}}" name="cc" id="cc" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="">N° RNE</label>
                                                      <input tabindex="26" readonly="" value="{{!empty($cita->rne)?$cita->rne:(!empty($hospitalizacion->rne)?$hospitalizacion->rne:'')}}" type="text" name="rne" id="rne" class="form-control" style="text-align:left">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="">ESPECIALIDAD</label>
                                                      <input tabindex="28" readonly="" value="{{!empty($cita->especializacion)?$cita->especializacion:(!empty($hospitalizacion->especializacion)?$hospitalizacion->especializacion:'')}}" type="text" name="especializacion" id="especializacion" class="form-control" style="text-align:left">
                                                  </div>
                                              </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="panel-body">
                                          <div >
                                              <div class=" with-nav-tabs panel-success">
                                                <div class="panel-heading">
                                                    <ul class="nav nav-tabs">
                                                        <!-- <li class="active"><a href="#tabAntecedentes" data-toggle="tab" style="font-size:12px">ANTECEDENTE</a></li> -->
                                                        <!-- <li><a href="#tabAnamnesis" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                                        <li><a href="#tabExamenClinico" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                                                        <li><a href="#tabTratamiento" data-toggle="tab" style="font-size:12px">TRATAMIENTO Y/O TERAPEUTICA</a></li> -->
                                                        <li class="active"><a href="#tabDiagnosticos" id="tabDiagnostico" data-toggle="tab" style="font-size:12px">DIAGNOSTICO</a></li>
                                                        <li><a href="#tabProcedimientos" id="tabProcedimiento2" data-toggle="tab" style="font-size:12px">PROCEDIMIENTO Y PLAN DE TRABAJO</a></li>
                                                    </ul>
                                                </div>
                                                <div class="tab-content">
                                                  <!-- <div class="tab-pane fade in active" id="tabAntecedentes">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>ANTECEDENTE PERSONAL</label>
                                                                <textarea class="form-control" cols="5" id="antecedentes" name="antecedente"
                                                                placeholder="NIEGA"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>ANTECEDENTE FAMILIAR</label>
                                                                <textarea class="form-control" cols="5" id="antecedentes_familiares" name="antecedente_familiar"
                                                                placeholder="Antecedente Familiar"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div> -->
                                                  <!-- <div class="tab-pane fade" id="tabAnamnesis">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">ANAMNESIS</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>MOTIVO DE VISITA</label>
                                                                <textarea class="form-control" cols="5" id="motivo_visita" name="motivo_visita"
                                                                placeholder="Relato m&eacute;dico del motivo de la visita"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>TIEMPO DE ENFERMEDAD</label>
                                                                <textarea class="form-control" cols="5" id="tiempo_enfermedad" name="tiempo_enfermedad"
                                                                placeholder="Relato m&eacute;dico del tiempo de enfermedad"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <label>RELATO CRONOLOGICO</label>
                                                                <textarea class="form-control" cols="5" id="anamnesis" name="anamnesis"
                                                                placeholder="Relato m&eacute;dico del relato cronologico"></textarea>
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
                                                                <textarea class="form-control" cols="5" id="examen_fisico" name="examen_fisico"
                                                                placeholder="Examen Físico"></textarea>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label>EXAMEN CLINICO</label>
                                                                <textarea class="form-control" cols="5" id="examen_clinico" name="examen_clinico"
                                                                placeholder="Examen Clínico"></textarea>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                              <div class="col-md-12">
                                                                  <label>EXAMENES AUXILIARES</label>
                                                                  <textarea class="form-control" cols="5" id="examen_auxiliar" name="examen_auxiliar"
                                                                  placeholder="Examenes auxiliares"></textarea>
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
                                                                <textarea class="form-control" cols="5" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                                                placeholder="Tratamiento auxiliar"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div> -->
                                                  <div class="tab-pane fade in active" id="tabDiagnosticos">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">DIAGNÓSTICOS</h3>
                                                        </div>
                                                        <fieldset>
                                                          <div class="panel-body">
                                                            <table>
                                                              <tr>
                                                                <td>
                                                                  <button type="button" id="addDiagnostico" class="btn btn-primary"
                                                                    data-toggle="modal" data-target="#addClassModal">Agregar
                                                                    Diagnostico</button>
                                                                </td>
                                                              </tr>
                                                            </table>
                                                            <br />
                                                            <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                                              style="margin-top:5px;">
                                                              <thead>
                                                                <tr>
                                                                  <th width="90%">Diagnóstico</th>
                                                                  <th width="10%" style="padding-left:20px;">Tipo</th>
                                                                  <th width="10%" style="padding-left:20px;">Prioridad</th>
                                                                </tr>
                                                              </thead>
                                                              <tfoot>
                                                              </tfoot>
                                                              <tbody>
                                                              </tbody>
                                                            </table>
                                                          </div>
                                                        </fieldset>                                      
                                                    </div>
                                                  </div> 
                                                  <div class="tab-pane fade" id="tabProcedimientos">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title size-titulo">PROCEDIMIENTOS Y PLAN DE TRABAJO</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                          <table>
                                                            <tr>
                                                              <td>
                                                                <button type="button" id="addProcedimientoForm" class="btn btn-primary"
                                                                  data-toggle="modal" data-target="#addClassModal">Agregar
                                                                  Procedimiento</button>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                          <br />
                                                          <table class="display" id="tblProcedimiento2" cellspacing="0" width="100%"
                                                            style="margin-top:5px;">
                                                            <thead>
                                                              <tr>
                                                                <th width="45%" style="padding-left:6px;">Procedimiento</th>
                                                                <th width="5%" style="padding-left:20px;">Cantidad</th>
                                                                <th width="45%">Diagnósticos</th>
                                                                <th width="5%"></th>
                                                              </tr>
                                                            </thead>
                                                            <tfoot>
                                                            </tfoot>
                                                            <tbody>
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                        <div class="panel-body">
                                                          <div class="col-md-12" style="margin-left: -10px;">
                                                              <label>PLAN DE TRABAJO</label>
                                                              <textarea class="form-control" cols="5" id="plan_trabajo" name="plan_trabajo"
                                                              placeholder="Plan de Trabajo"></textarea>
                                                          </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                            <div class="panel panel-bordered panel-success">
                                              <div class="panel-body">
                                                  <div class="form-group">
                                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                          <label>REGISTRAR RECETA</label>
                                                      </div>
                                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                          <button tabindex="70" type="button" id="addRecetaVale2" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" 
                                                          @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top" 
                                                          title="El servicio {{$servicio->nombre}} no está en su lista de servicios de atención, por lo tanto no puede registrar una receta"
                                                          @endif>
                                                          <div class="handle"></div>
                                                          </button>
                                                      </div>
                                                  </div>
                                                  <fieldset id="fsRecetaVale2" style="display:none">
                                                      <div class="panel panel-success panel-bordered">
                                                          <div class="panel-heading">
                                                              <b>PRODUCTOS MEDICOS</b>
                                                          </div>
                                                          <div class="panel-body">
                                                              <div class="form-group">
                                                                  <div class="col-md-2">
                                                                      <label>FEC. EXPEDICIÓN (A)</label>
                                                                      <input tabindex="71" type="date" class="form-control" 
                                                                      value="{{   \Carbon\Carbon::parse($fechaServidor)->format('Y-m-d')   }}" 
                                                                      name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                                                                      data-date-end-date="0d" autocomplete="off" />                                                                
                                                                  </div>
                                                                  <div class="col-md-5">
                                                                      <label>FARMACIA</label>
                                                                      <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta2"
                                                                        style="width:100%" onchange="getProducto2AndAddProducto()" disabled>
                                                                        <option value="">-Seleccionar-</option>
                                                                        <?php foreach($farmacias as $row):?>
                                                                        <option value="<?php echo $row->id?>"
                                                                          <?php if($row->id == 18) echo "selected='selected'"; ?>>
                                                                          <?php echo $row->nombre?></option>
                                                                        <?php endforeach;?>
                                                                      </select>
                                                                  </div>
                                                              </div>
                                                              <div class="form-group">
                                                                  <div class="col-sm-12">
                                                                      <table>
                                                                          <tr>
                                                                              <td>
                                                                                  <button tabindex="75" type="button" id="addProducto2" class="btn btn-primary"
                                                                                  data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                                              </td>
                                                                          </tr>
                                                                      </table>
                                                                  </div>
                                                              </div>
                                                              <br />
                                                              <div class="form-group" style="display: none">
                                                                  <select class="form-control" id="cmbMedicamentoTempAQ" tabindex="16"
                                                                    style="width: 500px">
                                                                    <option value="">Seleccionar Producto</option>
                                                                  </select>
                                                              </div>
                                                              <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                                              <div class="form-group">
                                                                  <table id="tblAtencionQ" class="display" cellspacing="0" width="100%">
                                                                      <thead>
                                                                          <tr>
                                                                          <th>Producto farmacéutico o dispositivo médico</th>
                                                                          <th style="width:10%">Stock Farmacia</th>
                                                                          <th>Tiempo(Dias)</th>
                                                                          <th> Via Adm. </th>
                                                                          <th>Posologia</th>
                                                                          <th> Cant. Pres.</th>
                                                                          <th> DiagnosticoxProducto </th>
                                                                          <th></th>
                                                                          <th> </th>
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
                                                  </fieldset>
                                              </div>
                                            </div>
                                      </div>
                                      <div class="panel-body"> 
                                          <div class="panel panel-bordered panel-success">
                                            <div class="panel-heading">REGISTRAR PROGRAMACIÓN QUIRÚRGICA</div>
                                            <div class="panel-body">
                                              @if($id_tipo_atencion==1)
                                                <div class="form-group">
                                                    <div class="col-sm-2">
                                                        <label for="">TURNOS QUIRURGICOS</label>
                                                    </div>
                                                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                    <div class="col-md-2">
                                                        <!-- <select name="id_prioridad_programacion" id="id_prioridad_programacion" class="select-tom">
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
                                                            <select name="id_tiempo_internamiento_preqx" id="id_tiempo_internamiento_preqx" class="select-tom">
                                                                <option value="">Seleccione</option>
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
                                                                <option value="">Seleccione</option>
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
                                                <?php
                                                $lugar_operacion = "";
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
                                                  <input 
                                                    tabindex="6" 
                                                    type="datetime-local" 
                                                    class="form-control" 
                                                    value="{{   \Carbon\Carbon::parse((!empty($intervencion->fecha_intervencion) ? $intervencion->fecha_intervencion: $fechaServidor))->format('Y-m-d\TH:i')   }}" 
                                                    name="fecha_intervencion" 
                                                    id="fecha_intervencion" 
                                                    max="{{ date('Y-m-d\TH:i') }}"
                                                    style="text-align:left;">
                                                    <!-- <p>{{ date('d-m-Y', strtotime($intervencion->fecha_intervencion)) }}</p> -->
                                                </div>
                                                @endif
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
                                              
                                                <!-- @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                  <input type="time" class="select-tom" name="hora_inicio_intervencion" id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                                                </div>
                                                @else
                                                <div class="col-md-2">
                                                    <p>{{ date('H:i', strtotime($intervencion->fecha_inicio_intervencion)) }}</p>
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
                                                @endif -->
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
                                                  <div class="col-md-2">
                                                      <p>{{ $intervencion->fecha_probable? date('d-m-Y', strtotime($intervencion->fecha_probable)) : date('d-m-Y', strtotime($intervencion->created_at)) }} 
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
                                          <fieldset>
                                            <div class="form-group">
                                                <div class="col-md-2" style="margin-left:30px;">
                                                    <label for="">SALA DE INTERVENCIÓN:</label>
                                                </div>
                                                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                <div class="col-md-2" style="margin-left:-10px;">
                                                  <select name="id_sala_intervencion" id="id_sala_intervencion" class="select-tom">
                                                    <option value="">Seleccione</option>
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
                                                    <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="select-tom">
                                                        <option value="">Seleccion</option>
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
                                            <div class="row intro-contenedor">
                                              <div class="col-lg-3" style="padding-bottom: 1rem;">
                                              <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="AddCirujano2">Cirujanos que Intervienen Operación</button>
                                              <span class="invalid-feedback" style="display: none; color: red;">
                                                {{ $errors->first('AddCirujano2') }}
                                              </span>
                                              </div>
                                              <div class="col-lg-12">
                                              <table class="table table-bordered" cellspacing="0" width="100%" id="tblCirujanoAtencion" cellspacing="0" width="100%" style="margin-top:5px;" >
                                              <tbody>

                                              @if(!empty($cirujanos))
                                                @foreach($cirujanos as $key => $row)
                                                <?php
                                                $isEliminadoCirujano = !empty(old('id_cirujano_anulado.' . $key)) || ($row->estado == 3);
                                                ?>
                                              <tr class="<?php echo $isEliminadoCirujano ? 'eliminado' : ''; ?>">
                                                <td>
                                                  {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} 
                                                </td>
                                                <td>
                                                  {{$row->descripcion}}
                                                </td>
                                              @if($row->estado == 0 || $row->estado == 1)
                                                <td> 
                                                  <button type="button"  class="btn btn-danger AnularFilaCirujano2 btn-xs" data-id_cirujano_registro="{{ $row->id }}" data-toggle="modal" style="margin-left:4px">
                                                    <i class="fa fa-times"></i> Eliminar
                                                  </button> 
                                                </td>
                                              @endif
                                              </tr>
                                              @endforeach
                                              @endif
                                              </tbody>
                                              </table>
                                            </div>
                                          </fieldset>
                                      </div>
                                      <fieldset>
                                          <div class="panel panel-bordered panel-success">
                                              <div class="panel-heading">PROCEDIMIENTOS PRE OPERATORIO</div>
                                              <div class="panel-body">
                                                 <table class="table table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                      <tr>
                                                        <th width="15%">Codigo</th>
                                                        <th width="50%">DESCRIPCION</th>
                                                      </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody>
                                                      @foreach ($procedimientos_intervencion as $pintervencion)
                                                      <tr>
                                                        <td>{{$pintervencion->codigo}}</td>
                                                        <td>{{$pintervencion->descripcion}}</td>
                                                      </tr>
                                                      @endforeach
                                                    </tbody>
                                                </table>
                                              </div>
                                          </div>
                                      </fieldset>
                                      <fieldset>
                                      <div class="panel panel-bordered panel-success">
                                        <div class="panel-heading">PROCEDIMIENTOS POST OPERATORIO
                                        </div>
                                        <div class="panel-body">
                                          <!--  -->
                                          <div class="form-group">
                                            <div class="col-sm-12">
                                              <table id="table_procedimiento_intervencion" class="table table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                  <tr>
                                                    <th>Código y Descripción</th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                          <!--  -->
                                        </div>
                                      </div>
                                      </fieldset>

                                      <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>DESCRIPCIÓN DE PROCEDIMIENTO</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="110" type="button" id="addDescripcionProcedimiento" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                          </div>
                                        </div>
                                        <div id="fsDescripcionProcedimiento" style="display: none">
                                          <fieldset class="panel panel-info panel-bordered panel-sm">
                                          <div class="form-group">
                                              <div class="col-md-12">
                                                <textarea class="form-control" id="descripcion_procedimiento" name="descripcion_procedimiento" rows="4" cols="50"></textarea>
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
                                              <button tabindex="110" type="button" id="addAllazgoOpe" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                            </div>
                                          </div>
                                          <div id="fsAllazgoOpe" style="display: none">
                                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                <textarea class="form-control" id="allazgo_operatorio" name="allazgo_operatorio" rows="4" cols="50"></textarea>
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
                                              <button tabindex="110" type="button" id="addExamenPatologico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                            </div>
                                          </div>
                                          <div id="fsExamenPatologico" style="display: none">
                                               <div class="panel panel-info panel-sm">
                                                    <div class="panel-heading">
                                                      <div class="panel-title text-center"><b>PROCEDIMIENTO PATOLOGICO</b></div>
                                                    </div>
                                                    <div class="panel-body">
                                                      <!--  -->
                                                      <div class="form-group">
                                                          <div class="col-sm-2">
                                                            <button tabindex="75" type="button" id="addProcedimientoExamenPatologia" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button>
                                                          </div>
                                                      </div>
                                                      <div class="form-group" style="display:none">
                                                        <select id="cmbProcedimientoExamenPatologiaTemp"></select>
                                                      </div>
                                                      <div class="form-group">
                                                        <div class="col-sm-12">
                                                          <table id="table_procedimiento_examen_patologia" class="display" cellspacing="0" width="100%">
                                                            <thead>
                                                              <tr>
                                                                <th>Código y Descripción</th>
                                                                <th style="width:10%;padding-left:30px;">Eliminar</th>
                                                              </tr>
                                                            </thead>
                                                            <tfoot>
                                                            </tfoot>
                                                            <tbody>
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                      </div>
                                                      <!--  -->
                                                    </div>
                                                  </div>
                                           
                                              <!-- <div class="form-group">
                                                <div class="col-md-12">
                                                <textarea class="form-control" id="examen_patologico" name="examen_patologico" rows="4" cols="50"></textarea>
                                                </div>
                                              </div> -->

                                              <div class="form-group">
                                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                  <label>PIEZA OPERATORIO</label>
                                                </div>
                                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                  <button tabindex="110" type="button" id="addPiezaOpe" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                                </div>
                                              </div>
                                              <div id="fsPiezaOpe" style="display: none">
                                                
                                                  <div class="form-group">
                                                    <div class="col-md-12">
                                                    <textarea class="form-control" id="pieza_operatorio" name="pieza_operatorio" rows="4" cols="50"></textarea>
                                                    </div>
                                                  </div>
                                                
                                              </div>  
                                            
                                          </div>
                                      </div>
                                      <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                                          <div class="form-group">
                                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                              <label>EXAMEN BACTERIOLOGICO</label>
                                            </div>
                                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                              <button tabindex="110" type="button" id="addExamenBacteriologico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                            </div>
                                          </div>
                                          <div id="fsExamenBacteriologico" style="display: none">
                                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                <textarea class="form-control" id="examen_bacteriologico" name="examen_bacteriologico" rows="4" cols="50"></textarea>
                                                </div>
                                              </div>
                                            </fieldset>
                                          </div>  
                                      </div>

                                      <div class="panel panel-bordered panel-info">
                                          <div class="panel-heading">REGISTRO DE INTRAOPERATORIO</div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label for="">CLASIFICACION DECIRUGIA</label>
                                                </div>
                                                <div class="col-md-2">
                                                  <select id="id_clasificacion_cirugia" name="id_clasificacion_cirugia" class="form-control" >
                                                      <option value="2" {{ isset($intervencion->id_clasificacion_cirugia) && (int)$intervencion->id_clasificacion_cirugia === 2 ? 
                                                        'selected' : '' }}>CIRUGIA MENOR</option>
                                                      <option value="3" {{ isset($intervencion->id_clasificacion_cirugia) && (int)$intervencion->id_clasificacion_cirugia === 3 ? 
                                                        'selected' : '' }}>CIRUGIA MEDIANA</option>
                                                      <option value="1" {{ isset($intervencion->id_clasificacion_cirugia) && (int)$intervencion->id_clasificacion_cirugia === 1 ? 
                                                        'selected' : '' }}>CIRUGIA MAYOR</option>
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
                                                      @if(empty($intraoperatorio->cirugia_inicio))
                                                      <input type="time" id="hora_inicio_operatorio" name="hora_inicio_operatorio" class="form-control" >
                                                      @else
                                                      <input type="time" id="cirugia_inicio_intra" name="cirugia_inicio2" class="form-control"  value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->cirugia_inicio;?>@endif" @if(!isset($intraoperatorio->cirugia_inicio)) disabled @endif>
                                                      @endif
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="">TÉRMINO DE CIRUGÍA</label>
                                                  </div>
                                                  <div class="col-md-2">
                                                      @if(empty($intraoperatorio->cirugia_termino))
                                                      <input type="time" id="hora_fin_operatorio" name="hora_fin_operatorio" class="form-control" >
                                                      @else
                                                      <input type="time" id="cirugia_termino_intra" name="cirugia_termino2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->cirugia_termino;?>@endif" @if(!isset($intraoperatorio->cirugia_termino)) disabled @endif>
                                                      @endif
                                                      
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="">TIEMPO REAL</label>
                                                  </div>
                                                  <div class="col-md-2">
                                                      @if(empty($intraoperatorio->cirugia_inicio) && empty($intraoperatorio->cirugia_termino2))
                                                      <input type="time" id="hora_duracion_operatorio_calculada" name="hora_duracion_operatorio_calculada" class="form-control" readonly>
                                                      @else
                                                      <input type="time" id="tipo_real2" name="tipo_real2" class="form-control" value="@if(!empty($intraoperatorio))<?php echo $intraoperatorio->tipo_real;?>@endif" @if(!isset($intraoperatorio->tipo_real)) @endif readonly>
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

                                      <div  class="panel panel-info panel-bordered panel-sm panel-body">
                                          <div class="form-group">
                                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                              <label>Registro Médico (Complicaciones)</label>
                                            </div>
                                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                              <button tabindex="110" type="button" id="addComplicacionMedica" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                            </div>
                                          </div>
                                          <div id="complicacionMedica" style="display: none">
                                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                <textarea class="form-control" id="complicacion_medica" name="complicacion_medica" rows="4" cols="50"></textarea>
                                                </div>
                                              </div>
                                            </fieldset>
                                          </div>  
                                      </div>

                                      <div id="panelTraslado" class="panel panel-info panel-bordered panel-sm panel-body">
                                          <div class="form-group">
                                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                              <label>REGISTRAR DESTINO</label>
                                            </div>
                                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                  @if(empty($datos_intervencion->id_hospitalizacion))
                                                    <button tabindex="110" type="button"  class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" disabled><div class="handle"></div></button>
                                                  @else
                                                    <button tabindex="110" type="button" id="addDestino" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                                  @endif
                                            </div>
                                          </div>
                                          <div id="fsDestino" style="display: none">
                                            <fieldset class="panel panel-info panel-bordered panel-sm">
                                                <div class="row">
                                                  <div class="col-md-3">
                                                    <label>DESTINO</label>
                                                    <select class="select-tom" id="id_tipo_egreso" name="id_tipo_egreso" style="width: 100%" onchange='cambiar_tipo_egreso()'>
                                                    <option value="" >SELECCIONAR</option>    
                                                    <option value="1" >DOMICILIARIA</option>
                                                        <option value="2" >HOSPITALIZACIÓN</option>
                                                        <!-- <option value="3" >TRANSFERIDO</option>
                                                        <option value="6"  >FUGA</option>
                                                        <option value="4" >FALLECIDO</option>
                                                        <option value="7" >RETIRO VOLUNTARIO</option> -->
                                                      </select>
                                                  </div>
                                                  <div class="col-md-6">
                                                    <div class="form-group"  id="alta_condicion"  style="display:none">
                                                     
                                                        <label>CONDICION ALTA</label><br>
                                                      
                                                      <div class="col-sm-6" >
                                                        <select class="select-tom condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" >
                                                          <option value="">Elegir</option>
                                                          <option value="1" >MEJORADO</option>
                                                          <option value="2" >CURADO</option>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <div class="form-group"  id="alta_condicion_de"  style="display:none">
                                                       
                                                          <label>CONDICION ALTA</label><br>
                                                      
                                                        <div class="col-sm-6" >
                                                          <select class="select-tom condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                            <option >FALLECIDO</option>
                                                        
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group"  id="alta_condicion_ret"  style="display:none">
                                                        
                                                          <label>CONDICION ALTA</label><br>
                                                       
                                                        <div class="col-sm-6" >
                                                          <select class="select-tom condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                            <option >RETIRO VOLUNTARIO</option>
                                                        
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group"  id="alta_condicion_fuga"  style="display:none">
                                                        
                                                          <label>CONDICION ALTA</label><br>
                                                       
                                                        <div class="col-sm-6" style="margin-left:0px;padding-left:0px">
                                                          <select class="select-tom condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                            <option >FUGA</option>
                                                        
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group"  id="alta_condicion_tra"  style="display:none">
                                                        
                                                          <label>CONDICION ALTA</label><br>
                                                        
                                                        <div class="col-sm-6" style="margin-left:0px;padding-left:0px">
                                                          <select class="select-tom condicion-alta" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" disabled>
                                                            <option >TRASLADO</option>
                                                        
                                                          </select>
                                                          <input type="hidden" name="hospitalizacion_id_condicion_alta" value="3">
                                                        </div>
                                                    </div>

                                                  </div>
                                                </div>
                                               
                                                <div class="row"> 
                                                  <div id="destino_hospitalizacion" style="display: none;">
                                                    <div class="col-md-3" style="padding-top:0px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                      <label>SERVICIO DE HOSPITALIZCION / UPSS</label>
                                                    </div>
                                                    <div class="col-sm-8" style="margin-left:0px;padding-left:0px">
                                                      <select class="select-tom" id="id_upss_encargado"  name="id_upss_encargado" style="width: 100%">
                                                          <option value="">Elegir</option>
                                                          <?php  
                                                            foreach($upss as $row): 
                                                          ?>
                                                          <option value="<?php echo $row->id_upss?>" <?php if($row->id_consultorio==$id_servicio): echo 'selected'; endif; ?> data-id_servicio_encargado="<?php echo $row->id_consultorio;?>"> <?php echo $row->nombre ?> <?php echo $row->id_upss?></option>
                                                          <?php endforeach; ?>
                                                        
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
                                                      <select id="id_area_destino" name="id_area_destino"class="select-tom" style="width:100%" onchange="getCamas();">
                                                        <option value="">SELECCIONAR</option>
                                                       @if(!empty($cuartos))
                                                          @foreach($cuartos as $e)
                                                            <option value="{{$e->id}}"@if(isset($dato_cama) && $dato_cama->id_cuarto == $e->id) selected @endif>{{$e->pabellon}} - {{$e->nombre}}</option>
                                                          @endforeach
                                                       @endif
                                                      </select>
                                                    </div>
                                                    <div class="col-md-3" >
                                                      <label>CAMA</label>
                                                      <select id="id_cama_destino" name="id_cama_destino" class="select-tom" style="width:100%">
                                                        <option value="">SELECCIONAR</option>
                                                        @if(!empty($camas))
                                                          @foreach($camas as $e)
                                                            <option value="{{$e->id}}" @if($e->id == $hospitalizacion->id_cama_estado) selected @endif>{{$e->nombre}}</option>
                                                          @endforeach
                                                        @endif
                                                      </select>
                                                      <input type="hidden" name="id_cama_actual" value="{{isset($hospitalizacion)&& !empty($hospitalizacion->id_cama)?$hospitalizacion->id_cama:''}}">                                                   
                                                    </div>
                                                  </div>

                                                  <div id="destino_domiciliaria" style="display: none;">
                                                    <div class="row">
                                                      <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                        <label>FECHA EGRESO</label>
                                                      </div>
                                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                          <input 
                                                              type="date" 
                                                              class="form-control"
                                                              name="fecha_egreso" 
                                                              id="fecha_egreso"
                                                              onfocus="validarFechaEgreso1(false, true);"
                                                              onchange="validarFechaEgreso1();"
                                                              value="{{\Carbon\Carbon::parse((!empty($intervencion->fecha_intervencion) ? $intervencion->fecha_intervencion: $fechaServidor))->format('Y-m-d')}}" 
                                                              data-fecha_valor="{{\Carbon\Carbon::parse((!empty($intervencion->fecha_intervencion) ? $intervencion->fecha_intervencion: $fechaServidor))->format('Y-m-d')}}"
                                                              style="text-align:left;"
                                                              data-fecha-ingreso="<?php echo date('Y-m-d', strtotime(!empty($hospitalizacion->fecha_ingreso)?$hospitalizacion->fecha_ingreso:$datos_intervencion->fecha_derivacion)); ?>" >
                                                      </div>
                                                      <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                        <label>HORA EGRESO</label>
                                                      </div>
                                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                        <input type="time" class="form-control" 
                                                        title="Formato válido: HH:mm:ss (por ejemplo, 14:30:45)" step="1" value="{{date("G:i:s", strtotime($horaEgreso))}}"  name="hora_egreso" id="hora_egreso" style="text-align:left" />
                                                      </div>
                                                    </div>
                                                    @if(($id_tipo_atencion==1 || $id_tipo_atencion==2) && !$datos_intervencion->es_nuevo_admision_hospitalizacion)
                                                    <div class="row" id="receta-postquirurgico" style="display: none;">
                                                      <div class="col-md-12">
                                                        <fieldset>
                                                          <div class="panel panel-info panel-bordered panel-sm">
                                                            <div class="panel-heading">
                                                              <div class="panel-title text-center">
                                                                <b>PRODUCTOS MÉDICOS POSTQUIRÚRGICOS</b>
                                                              </div>
                                                            </div>
                                                            <div class="panel-body">

                                                                  <div class="col-md-12" style="margin-bottom:10px;padding-left:0px">

                                                                    @if(!$puede_atennder_servicios_cq)
                                                                      <button class="btn btn-success"  disabled  data-toggle="tooltip" data-placement="top"
                                                                        title="El servicio {{$servicio->nombre}} no está en su lista de servicios de atención, por lo tanto no puede registrar una receta"
                                                                        >Agregar Receta</button>
                                                                    @else
                                                                      <div class="form-inline">
                                                                          <a onclick="agregarRecetaPostQuirurgico()"
                                                                            id="btnNuevoRecetaPostQuirurgico"
                                                                            flag="0"
                                                                            data-id-receta=""
                                                                            class="btn btn-success">
                                                                              Agregar Receta
                                                                          </a>
                                                                      </div>
                                                                    @endif
                                                                  </div>

                                                                <div id="fsNuevaRecetaValePostQuirurgico" style="display:none">
                                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                                    <div class="panel-body">
                                                                      <div class="form-group">
                                                                        <div class="col-md-2">
                                                                          <label>FEC. EXPEDICIÓN (A)</label>
                                                                          <input type="date" class="form-control"
                                                                            name="fecha_expedicion_postqx"
                                                                            onchange="validarFechaExpedicionPostQX1()"
                                                                            value="{{ !empty($intervencion->fecha_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_intervencion)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                            data-fecha_valor="{{ !empty($intervencion->fecha_intervencion) ? \Carbon\Carbon::parse($intervencion->fecha_intervencion)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                            id="fecha_expedicion_postqx" tabindex="2" autocomplete="off" />
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                          <label>FARMACIA</label>
                                                                          <select class="form-control" name="id_farmacia_receta_postqx" id="id_farmacia_receta_postqx"
                                                                            style="width:100%" onchange="getProducto2AndAddProducto()" disabled>
                                                                            <option value="">-Seleccionar-</option>
                                                                            <?php foreach($farmacias as $row):?>
                                                                            <option value="<?php echo $row->id?>"
                                                                            <?= in_array($row->id, [128,22,107]) ? "selected='selected'" : "" ?>>
                                                                            <?php echo $row->nombre?>
                                                                            </option>
                                                                            <?php endforeach;?>
                                                                          </select>
                                                                        </div>
                                                                      </div>
                                                                      <br />
                                                                      <table>
                                                                        <tr>
                                                                          <td>
                                                                            <button type="button" id="addProductoNuevoPostQuirurgico" class="btn btn-primary" data-toggle="modal"
                                                                              data-target="#addClassModal" @if(!$puede_atennder_servicios_cq) disabled  data-toggle="tooltip" data-placement="top"
                                                                          title="El servicio no está en su lista de servicios de atención, por lo tanto no puede registrar una receta"@endif>Agregar Producto</button>
                                                                          </td>
                                                                        </tr>
                                                                      </table>
                                                                      <br />
                                                                      <div class="form-group" style="display: none">
                                                                        <select class="form-control" id="cmbMedicamentoTempAQPostQx" tabindex="16"
                                                                          style="width: 500px">
                                                                          <option value="">Seleccionar Producto</option>
                                                                        </select>
                                                                    </div>
                                                                      <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">

                                                                      <table id="tablapostquirurgico" class="table table-bordered" cellspacing="0" width="100%">
                                                                        <thead>
                                                                          <tr>
                                                                            <th width="30%">Producto farmacético o dispositivo médico</th>
                                                                            <th width="10%">Stock Farmacia</th>
                                                                            <th width="14%">Tiempo(Dias)</th>
                                                                            <th width="21%">Via Administración</th>
                                                                            <th width="16%">Posologia</th>
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
                                                          </div>
                                                        </fieldset>
                                                      </div>
                                                    </div>

                                                    <div class="row">
                                                      <div class="col-md-12">
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
                                                                  <textarea class="form-control" rows="3" id="egreso_resumen" name="egreso_resumen" placeholder="Resumen de la enfermedad actual"></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>EXAMEN CLÍNICO</label>
                                                                  <textarea class="form-control" rows="3" id="egreso_examen_clinico" name="egreso_examen_clinico" placeholder="Examen Clínico"></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>EXAMENES AUXILIARES</label>
                                                                  <textarea class="form-control" rows="3" id="egreso_examen_auxiliar" name="egreso_examen_auxiliar" placeholder="Examenes Auxiliares"></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>EVOLUCIÓN</label>
                                                                  <textarea class="form-control" rows="3" id="egreso_evolucion" name="egreso_evolucion" placeholder="Evolución"></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>TRATAMIENTO</label>
                                                                  <textarea class="form-control" rows="3" id="egreso_tratamiento" name="egreso_tratamiento" placeholder="Resumen de la enfermedad actual, examen físico, exámenes auxiliares, evolución y tratamiento."></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>PROCEDIMIENTOS TERAPÉUTICOS Y/O DIAGNÓSTICOS</label>
                                                                  <textarea class="form-control" rows="3" id="egreso_procedimiento" name="egreso_procedimiento" placeholder="Procedimientos terapéuticos y/o diagnósticos"></textarea>
                                                                </div>
                                                              </div>
                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>COMPLICACIONES</label>
                                                                  <textarea class="form-control" rows="5" id="egreso_complicacion" name="egreso_complicacion" placeholder="Complicaciones."></textarea>
                                                                </div>
                                                              </div>

                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>MEDIDAS GENERALES AL ALTA</label>
                                                                  <textarea class="form-control" rows="5" id="egreso_medidas_generales" name="egreso_medidas_generales" placeholder="Complicaciones."></textarea>
                                                                </div>
                                                              </div>

                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>MEDICAMENTOS PRESCRITOS AL ALTA</label>
                                                                  <textarea class="form-control" rows="5" id="egreso_medicamentos_prescritos" name="egreso_medicamentos_prescritos" placeholder="Complicaciones."></textarea>
                                                                </div>
                                                              </div>

                                                              <div class="from-group">
                                                                <div class="col-lg-12 col-md-12">
                                                                  <label>PRONÓSTICO</label>
                                                                  <input class="form-control" id="egreso_pronostico" name="egreso_pronostico" placeholder="Pronóstico" maxlength="255">
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </fieldset>
                                                      </div>
                                                    </div>
                                                    @endif
                                                  </div>
                                                </div>
                                              
                                            </fieldset>
                                          </div>  
                                      </div>
                                      <input tabindex="140" <?php echo $disabled_atencion?> class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button"
                                      form="prestacionescrea" id="btnPrestacion" />
                                   </form>
                                </div>
                            </div>
                        </div>
                     </div>
                 </div>
            </div>
            <div class="tab-pane fade" id="tab4default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading2">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab5default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading2">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divLaboratorio'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab6default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading2">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divRadiologico'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
        </div>
     </div>
</div>


<div class="modal fade" id="modalAnularProfesional2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        >&times;</span></button>
                <b id="tituloFormularioProfesional-anular2"></b>
            </div>

            <div class="modal-body">
                <form id="anularFormularioProfesional2" name="anularFormularioProfesional2" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_medico" id="id_medico2">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                          <h2>Motivo de Anulación</h2>
                          <textarea id="anulacion_motivo2" class="form-control" name="anulacionMotivo2" rows="4" cols="50" placeholder="Escribe el motivo de la anulación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCancelar" class="btn btn-danger pull-left">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="GuardarAnularProfesional2" value="create">Anular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('frontend.laboratorios.modal_solicitud_citoquimicos_liquidos_biologicos')
{{-- MODAL SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL  --}}
@include('frontend.laboratorios.modal_solicitud_test_tolerancia_glucosa_oral')