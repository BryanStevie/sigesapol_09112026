@extends('frontend.layouts.master')
@push('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('renderjs')
<script src='../funciones/function.js'></script> 
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
@include('frontend.admision.emergencia.triaje.alerta_atenciones.function.alerta_atenciones')
  
<script>
const decimalRegex = /^\d+(\.\d+)?$/;

function getHorario_citas_valida(id_consultorio, documento){   
    //$id_ipress, $id_consultorio, $fecha_atencion, $documento  
    $('#no_amerita').val('0').change(); 
    var value='';
    $.ajax({
        url: '/modal_cita_horario_car/' + id_consultorio+'/'+documento+'/'+1, 
        success: function(result) { 
            console.log('result: result: 111 '+result); 
          if(result == 'null' || result == null){ 

                $.ajax({
                  url: '/modal_cita_horario_car/' + id_consultorio+'/'+documento+'/-1', 
                  success: function(resultadoT) { 
                    console.log(resultadoT);
                    if(resultadoT == 'null' || resultadoT == null){ 
                      $('#hora_cita').val("");
                      $('#funcion_cita').val("");
                      
                      $('#medico_car').val(""); 
                      $('#id_medico').val("");
                      $('#fecha_cita').val("");
                      $('#id_turno').val(""); 

                      $('#seccion_medico_cita').hide();   
                      //$('#id_prioridad').val("").change(); 

                      Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                        html: '<p class="descripcion-alert"><span><b style="color:Red;">SE HA VERIFICADO QUE NO CUENTAS CON CUPOS DISPONIBLES PARA GENERAR UNA CITA.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 3400,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      }); 

                      $('#id_topico').val('19').change(); 
                      $('#no_amerita').val('1').change(); 
                      value = 'false';
                      console.log(value+ '1111111111');
                      //$('#id_prioridad').focus();
                      //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');
                      return;

                    }else{
                      if(result == 'NO-TIENE-CAR-EN-TURNO'){
                          $('#hora_cita').val("");
                          $('#funcion_cita').val("");
                                  
                          $('#id_medico').val("");
                          $('#fecha_cita').val("");
                          $('#id_turno').val("");
                          
                          $('#medico_car').val(""); 
                          $('#seccion_medico_cita').hide();  
                          //$('#id_prioridad').val("").change(); 
 
                          $('#id_topico').val('19').change();  
                          $('#no_amerita').val('1').change(); 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                            html: '<p class="descripcion-alert"><span><b style="color:Red;">RECUERDE QUE EL CONSULTORIO CAR, SE HABILITARÁ A PARTIR DE LAS 07:00 AM.</b><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 4500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                          }); 
                          value = 'false';  
                          console.log(value+ '2222222222');
                          //$('#id_prioridad').focus();
                          //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');
                          return;

                      }else{ 
                        
                        $('#no_amerita').val('0').change();
                        $('#seccion_medico_cita').show(); 
                        contador=0;
                        id_contador="";  
                        $('#medico_car').val(resultadoT.medico.medico);
                        $('#hora_cita').val(resultadoT.horario[0].hora); 
                        // Obtener el texto del elemento   id_medico, fecha_cita, id_turno
                                      
                        var textoReemplazado = resultadoT.horario[0].click.replace('guardarCita(', '').replace(')', '').replace(/'/g, '');
                        $('#funcion_cita').val(textoReemplazado); 
                      
                        // Dividir el texto en un arreglo utilizando el método .split()
                        var arregloTexto = textoReemplazado.split(',');

                        $('#id_medico').val(arregloTexto[0]);
                        $('#fecha_cita').val(arregloTexto[1]);
                        $('#id_turno').val(arregloTexto[2]); 
                        $('#observacion').focus(); 
                        
                        value = 'true'; 
                        console.log(value+ '333333333333');
                        save_triaje_store();
                        
                      }

                    } 
                  }
                }); 
          }else{ 
            console.log('result: result: 222 '+result); 
            if(result == 'NO-TIENE-CAR-EN-TURNO'){
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                        
                $('#id_medico').val("");
                $('#fecha_cita').val("");
                $('#id_turno').val("");

                $('#medico_car').val(""); 
                $('#seccion_medico_cita').hide();  
                //$('#id_prioridad').val("").change(); 
  
                $('#id_topico').val('19').change();   
                      $('#no_amerita').val('1').change(); 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                            html: '<p class="descripcion-alert"><span><b style="color:Red;">RECUERDE QUE EL CONSULTORIO CAR, SE HABILITARÁ A PARTIR DE LAS 07:00 AM.</b><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 4500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                          });
                value = 'false'; 
                console.log(value+ '4444444444444');
                //$('#id_prioridad').focus();
                //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');
                return;

            }else{ 
              if(result.medico.medico=='  '|| result.medico.medico==' ' || result.medico.medico=='' || result.medico.medico==null){
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                        
                $('#id_medico').val("");
                $('#fecha_cita').val("");
                $('#id_turno').val("");

                $('#medico_car').val(""); 
                $('#seccion_medico_cita').hide();  
                //$('#id_prioridad').val("").change(); 
 
                $('#id_topico').val('19').change();
                      $('#no_amerita').val('1').change(); 
                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                  html: '<p class="descripcion-alert"><span><b style="color:Red;">SE HA DETECTADO QUE NO SE CUENTA CON MÉDICO PROGRAMADOS PARA HOY.</b><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 3400,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });  
                value = 'false'; 
                console.log(value+ '55555555555555');
                //$('#id_prioridad').focus();
                //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');
                return;

              }else{ 
                $('#no_amerita').val('0').change(); 
                $('#seccion_medico_cita').show(); 
                contador=0;
                id_contador=""; 
                $('#medico_car').val(result.medico.medico);
                $('#hora_cita').val(result.horario[0].hora); 
                // Obtener el texto del elemento   id_medico, fecha_cita, id_turno
                              
                var textoReemplazado = result.horario[0].click.replace('guardarCita(', '').replace(')', '').replace(/'/g, '');
                $('#funcion_cita').val(textoReemplazado); 
              
                // Dividir el texto en un arreglo utilizando el método .split()
                var arregloTexto = textoReemplazado.split(',');

                $('#id_medico').val(arregloTexto[0]);
                $('#fecha_cita').val(arregloTexto[1]);
                $('#id_turno').val(arregloTexto[2]); 
                $('#observacion').focus(); 
                value = 'true'; 
                console.log(value+ '6666666666666');
                save_triaje_store();

              }
            }
          }
          
        }
      }); 
  }


  $id_forma_ingreso = $("#frmTriajeEmergencia").find("#id_forma_ingreso");
  $id_forma_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });

  $id_estado_ingreso = $("#frmTriajeEmergencia").find("#id_estado_ingreso");
  $id_estado_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });

  $id_motivo_ingreso = $("#frmTriajeEmergencia").find("#id_motivo_ingreso");
  $id_motivo_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });

  $id_prioridad = $("#frmTriajeEmergencia").find("#id_prioridad");
  $id_prioridad.select2({
    width: 'resolve' // need to override the changed default
  });
  
  $id_topico = $("#frmTriajeEmergencia").find("#id_topico");
  $id_topico.select2({
    width: 'resolve' // need to override the changed default
  }); 
  
        $('#hora_parto').on('change', function (e) {
            const hora_parto = e.target.value;

            const timeCurrent = moment().format('HH:mm');
            const horaPartoMoment = moment(hora_parto, 'HH:mm');
            const timeCurrentMonent = moment(timeCurrent, 'HH:mm');

            const diff = horaPartoMoment.diff(timeCurrentMonent, 'minutes');

            const fecha_parto = $("#fecha_parto").val();
            if(fecha_parto) {
              const dateCurrent = moment().format('YYYY-MM-DD');
              const fecha_parto_format = moment(fecha_parto, 'DD-MM-YYYY').format("YYYY-MM-DD");
              if (dateCurrent === fecha_parto_format && Number(diff) >= 0) {
                Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>La Hora de parto debe ser menor o igual a la hora Actual.</span></p>',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    preConfirm: function() {
                        $('#hora_parto').val("");
                        $('#hora_parto').focus();
                    }
                });
              } 
            }
        })
  $tipodoc_beneficiario_bus = $("#frmTriajeEmergencia").find("#tipodoc_beneficiario_bus");
  $tipodoc_beneficiario_bus.select2({
    width: 'resolve' // need to override the changed default
  }); 
 
  function modalHistorial(dni_paciente){

    $.ajax({
        url: "../modal_historial_triaje_emergencia/"+dni_paciente,
        type: "GET",
        success: function (result) {  
            $("#divHistorial").html(result);
            $('#tablemodalm').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }

  var texto_marca_agua_triaje = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_triaje() {
    var overlay = document.getElementById('marca_agua_overlay_triaje');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_triaje;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  $(document).ready(function () {
    $('a[href="#tab4default"]').on('shown.bs.tab', function () {
      generar_marca_agua_triaje();

      $.ajax({
        url: '/auditoria-hc/registrar',
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          origen: 'Triaje EMG(HA)',
          doc_buscado: $('#dni_beneficiario').val(),
          id_tipo_atencion: null
        }
      });
    });

    var resizeMarcaAguaTriajeTimer = null;
    $(window).on('resize', function () {
      clearTimeout(resizeMarcaAguaTriajeTimer);
      resizeMarcaAguaTriajeTimer = setTimeout(function () {
        if ($('#tab4default').is(':visible')) generar_marca_agua_triaje();
      }, 250);
    });
  });

  function evitarLetras(event) {
    var charCode = event.which ? event.which : event.keyCode;
    var inputValue = String.fromCharCode(charCode);

    // Expresión regular para permitir solo números y un punto decimal
    var pattern = /^[\d.]+$/;

    if (!pattern.test(inputValue)) {
      event.preventDefault();
    }

    return true;
  }

  $('#fecha_registro_ingreso_select').datepicker({
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom left" 
 }).on('changeDate',function(e){  
      console.log('click sobre fecha_registro_ingreso_select');
      var oldDate =  $('#fecha_registro_ingreso_select').val();
      if(oldDate!=null){
        var values = oldDate.split("/"); 
        var dia = values[0];
        var mes = values[1];
        var ano = values[2]; 
        $('#fecha_registro_ingreso').val(ano+'-'+mes+'-'+dia);
      }
  });


  function getEstado_ingreso (){ 
    var id_forma_ingreso = $('#id_forma_ingreso').val();
    if(id_forma_ingreso!=''){
      $.ajax({
        url: '/obtener_estadoingreso_por_formaingreso/' + id_forma_ingreso,
        success: function(result) {
          $('#id_estado_ingreso').empty();   
          $('#id_estado_ingreso').append("<option value=''>--SELECCIONAR--</option>");   
          contador=0;
          id_contador=0;
          $(result).each(function(ii, oo) {
            contador++; 
            id_contador=oo.id;
            $('#id_estado_ingreso').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>");    
          }); 
          if(contador==1){
            $('#id_estado_ingreso').val(id_contador).change();
          }else{
            $('#id_estado_ingreso').val("").change();
          }
          const id_estado_ingreso_old = {!! json_encode(old('id_estado_ingreso', false)) !!}
          if (id_estado_ingreso_old) {
            $('#id_estado_ingreso').val(String(id_estado_ingreso_old)).change();
          }
        }
      });
    }else{
        $('#id_estado_ingreso').empty();  
        $('#id_estado_ingreso').append("<option value=''>--SELECCIONAR--</option>"); 
    }
  }

  function getHorario_citas(id_consultorio, documento){   
    //$id_ipress, $id_consultorio, $fecha_atencion, $documento  
    
    $('#no_amerita').val('0').change();
    $.ajax({
        url: '/modal_cita_horario_car/' + id_consultorio+'/'+documento+'/'+1,
        beforeSend: function() {
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert"><span><b>ESPERE UN MOMENTO VERIFICANDO CUPOS DISPONIBLES PARA GENERAR UNA CITA.</b><br></span></p>', 
            allowOutsideClick: false,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
          });
				},
        success: function(result) { 
            console.log('result: result: 111result: result: 111 '+result); 
          if(result == 'null' || result == null){ 

                $.ajax({
                  url: '/modal_cita_horario_car/' + id_consultorio+'/'+documento+'/-1', 
                  success: function(resultadoT) { 
                    console.log(resultadoT);
                    if(resultadoT == 'null' || resultadoT == null){ 
                      $('#hora_cita').val("");
                      $('#funcion_cita').val("");
                      
                      $('#medico_car').val(""); 
                      $('#id_medico').val("");
                      $('#fecha_cita').val("");
                      $('#id_turno').val("");

                      $('#seccion_medico_cita').hide();   
                      //$('#id_prioridad').val("").change(); 

                      Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                        html: '<p class="descripcion-alert"><span><b style="color:Red;">SE HA VERIFICADO QUE NO CUENTAS CON CUPOS DISPONIBLES PARA GENERAR UNA CITA.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 3400,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      }); 

                      //$('#id_topico').val('19').change();   
                      $('#no_amerita').val('1').change(); 
                      //$('#id_prioridad').focus();
                      //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');
                    }else{
                      if(result == 'NO-TIENE-CAR-EN-TURNO'){
                          $('#hora_cita').val("");
                          $('#funcion_cita').val("");
                                  
                          $('#id_medico').val("");
                          $('#fecha_cita').val("");
                          $('#id_turno').val("");
                          
                          $('#medico_car').val(""); 
                          $('#seccion_medico_cita').hide();  
                          //$('#id_prioridad').val("").change(); 

                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                            html: '<p class="descripcion-alert"><span><b style="color:Red;">RECUERDE QUE EL CONSULTORIO CAR, SE HABILITARÁ A PARTIR DE LAS 07:00 AM.</b><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 4500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                          });

                          //$('#id_topico').val('19').change();   
                          $('#no_amerita').val('1').change(); 
                          //$('#id_prioridad').focus();
                          //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');

                      }else{
                        Swal.fire({
                          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                          html: '<p class="descripcion-alert"><span><b style="color:GREEN;">CUPOS DISPONIBLES VERIFICADOS, CONTINÚE CON SU REGISTRO.</b><br><br></span></p>', 
                          allowOutsideClick: false,
                          timer: 2000,
                          timerProgressBar: true,
                          showConfirmButton: false,
                          allowOutsideClick: false,
                        });
                  
                        
                        $('#no_amerita').val('0').change();
                        $('#seccion_medico_cita').show(); 
                        contador=0;
                        id_contador=""; 
                        $('#medico_car').val(resultadoT.medico.medico);
                        $('#hora_cita').val(resultadoT.horario[0].hora); 
                        // Obtener el texto del elemento   id_medico, fecha_cita, id_turno
                                      
                        var textoReemplazado = resultadoT.horario[0].click.replace('guardarCita(', '').replace(')', '').replace(/'/g, '');
                        $('#funcion_cita').val(textoReemplazado); 
                      
                        // Dividir el texto en un arreglo utilizando el método .split()
                        var arregloTexto = textoReemplazado.split(',');

                        $('#id_medico').val(arregloTexto[0]);
                        $('#fecha_cita').val(arregloTexto[1]);
                        $('#id_turno').val(arregloTexto[2]); 
                        $('#observacion').focus(); 
                      }

                    } 
                  }
                }); 
          }else{ 
            console.log('result: result: 222 '+result); 
            if(result == 'NO-TIENE-CAR-EN-TURNO'){
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                        
                $('#id_medico').val("");
                $('#fecha_cita').val("");
                $('#id_turno').val("");

                $('#medico_car').val(""); 
                $('#seccion_medico_cita').hide();  
                //$('#id_prioridad').val("").change(); 

                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                  html: '<p class="descripcion-alert"><span><b style="color:Red;">RECUERDE QUE EL CONSULTORIO CAR, SE HABILITARÁ A PARTIR DE LAS 07:00 AM.</b><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 4500,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });

                //$('#id_topico').val('19').change(); 
                $('#no_amerita').val('1').change();   
                //$('#id_prioridad').focus();
                //$('#id_prioridad').next('.select2-container').addClass('borde-alerta');

            }else{ 
              if(result.medico.medico=='  '|| result.medico.medico==' ' || result.medico.medico=='' || result.medico.medico==null){
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                        
                $('#id_medico').val("");
                $('#fecha_cita').val("");
                $('#id_turno').val("");

                $('#medico_car').val(""); 
                $('#seccion_medico_cita').hide();  
                //$('#id_prioridad').val("").change(); 

                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                  html: '<p class="descripcion-alert"><span><b style="color:Red;">SE HA DETECTADO QUE NO SE CUENTA CON MÉDICO PROGRAMADOS PARA HOY.</b><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 3400,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
                
                //$('#id_topico').val('19').change();  
                $('#no_amerita').val('1').change();  
                //$('#id_prioridad').focus();
                //  $('#id_prioridad').next('.select2-container').addClass('borde-alerta');

              }else{
                    Swal.fire({
                      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                      html: '<p class="descripcion-alert"><span><b style="color:GREEN;">CUPOS DISPONIBLES VERIFICADOS, CONTINÚE CON SU REGISTRO.</b><br><br></span></p>', 
                      allowOutsideClick: false,
                      timer: 2000,
                      timerProgressBar: true,
                      showConfirmButton: false,
                      allowOutsideClick: false,
                    });
              
                $('#no_amerita').val('0').change();
                $('#seccion_medico_cita').show(); 
                contador=0;
                id_contador=""; 
                $('#medico_car').val(result.medico.medico);
                $('#hora_cita').val(result.horario[0].hora); 
                // Obtener el texto del elemento   id_medico, fecha_cita, id_turno
                              
                var textoReemplazado = result.horario[0].click.replace('guardarCita(', '').replace(')', '').replace(/'/g, '');
                $('#funcion_cita').val(textoReemplazado); 
              
                // Dividir el texto en un arreglo utilizando el método .split()
                var arregloTexto = textoReemplazado.split(',');

                $('#id_medico').val(arregloTexto[0]);
                $('#fecha_cita').val(arregloTexto[1]);
                $('#id_turno').val(arregloTexto[2]); 
                $('#observacion').focus(); 
              }
            }
          }
          
        }
      });
  }

  function getArea_topico (){ 
    $('#no_amerita').val('0').change();
    $('#seccion_medico_cita').hide(); 
    $('#medico_car').val(""); 
    $('#hora_cita').val(""); 
    $('#funcion_cita').val("");
    $('#id_medico').val("");
    $('#fecha_cita').val("");
    $('#id_turno').val("");

    var descripcion_tipo_beneficiario_saludpol = $('#descripcion_tipo_beneficiario_saludpol').val(); 
    $('#id_prioridad').next('.select2-container').removeClass('borde-alerta');
    $('#medico_car').val('');
    var dni_paciente = $("#dni_beneficiario").val();
    var id_prioridad = $('#id_prioridad').val(); 
    var sexo = $('#sexo').val();
    var edad = $('#edad_pac').val(); 
    if(id_prioridad!=''){ 
      //valicion de tipo de listado
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad; 
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
        if(id_prioridad=='2'||id_prioridad=='3'){
            url_consulta='/get_topico_por_id_prioridad_sexo_edad_triaje/'+id_prioridad+'/'+sexo+'/'+edad;
        }  
       
        $.ajax({
          url: url_consulta,
          success: function(result) {   
            $('#estado_topico').val(""); 
            $('#id_topico').empty();  
            $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
            val=0;
            id_val=0;
            $(result).each(function(ii, oo) {  
              val++;
              id_val=oo.id ;
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
            }); 
            $('#id_topico').val("").change();  
            if(id_prioridad=='1'){
              
                $('#no_amerita').val('0').change();
                if(descripcion_tipo_beneficiario_saludpol=='CIVIL'){  
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                  });
                } 

                if(val==1){
                  $('#id_topico').val(id_val).change(); 
                }
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                $('#observacion').focus(); 
            }else if(id_prioridad=='4'){
               

                  if(descripcion_tipo_beneficiario_saludpol=='CIVIL'){ 
                    $('#id_topico').val('19').change();   
                      $('#no_amerita').val('1').change(); 
                    Swal.fire({
                      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                      html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                      allowOutsideClick: false,
                      timer: 2500,
                      timerProgressBar: true,
                      showConfirmButton: false,
                      allowOutsideClick: false,
                    });
                  } else{
                    $('#id_topico').val('19').change();   
                  }

            }else{
                $('#no_amerita').val('0').change();
                $('#hora_cita').val("");
                $('#funcion_cita').val("");
                $('#id_topico').val("").change();
                $('#estado_topico').val(""); 
            }
            const id_topico_old = {!! json_encode(old('id_topico', false)) !!}
            if (id_topico_old) {
              $('#id_topico').val(String(id_topico_old)).change();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='4'){
              $('#no_amerita').val('0').change();
              $('#id_topico').empty();  
              $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
              $('#hora_cita').val("");
            $('#funcion_cita').val("");
              $('#id_topico').val("").change(); 
              $('#estado_topico').val(""); 
              $('#observacion').focus(); 
            }else{
              $('#no_amerita').val('0').change();
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
              $('#hora_cita').val("");
            $('#funcion_cita').val("");
              $('#id_topico').val("19").change(); 
              $('#observacion').focus(); 
            }
          }
        }); 
    }else{
      $('#no_amerita').val('0').change();
      $('#id_topico').empty();  
      $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
      $('#id_topico').val("").change(); 
      $('#estado_topico').val(""); 
    }
  }
 
 $('#id_topico').change(function() {  
  $('#no_amerita').val('0').change();
    var id_prioridad = $('#id_prioridad').val(); 
        //  $('#habilitar_es_gestante').hide();  //! TODO: TAG: CHECKGESTACION
        //  document.getElementById('checkbox_es_gestante').checked = false;  //! TODO: TAG: CHECKGESTACION
        //  $('#es_gestantedocument.frmTriajeEme').val('0');  //! TODO: TAG: CHECKGESTACION

   ///id topico en realidad es la consulta a la tabla triaje
   ///id topico en realidad es la consulta a la tabla triaje
     var id_triaje = $('#id_topico').val(); 
     if(id_triaje!=''){
        /*
      */
      /*
      if(id_prioridad==4){
       if(id_triaje==19){  //CAR
          var dni_paciente = $("#dni_beneficiario").val();
          var descripcion_tipo_beneficiario_saludpol = $('#descripcion_tipo_beneficiario_saludpol').val(); 
          if(descripcion_tipo_beneficiario_saludpol=='CIVIL'){   
            $('#no_amerita').val('1').change();   
            Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
              html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
              allowOutsideClick: false,
              timer: 2500,
              timerProgressBar: true,
              showConfirmButton: false,
              allowOutsideClick: false,
            });
          } else{ 
            if(dni_paciente!=null && dni_paciente!='' && sexo!=null && sexo!='' ){ 
              getHorario_citas(8,dni_paciente);
            }else{ 
              $('#observacion').focus(); 
            }
          }  
       } else {
        
        $('#no_amerita').val('0').change();
         $('#seccion_medico_cita').hide(); 
         $('#medico_car').val(""); 
         $('#medico_car').val(""); 
         $('#hora_cita').val(""); 
         $('#funcion_cita').val("");
         $('#id_medico').val("");
         $('#fecha_cita').val("");
         $('#id_turno').val("");
       }
      }
      */
       $.ajax({
         url: '/obtener_codigo_triaje/' + id_triaje, 
         success: function(result) { 
           console.log(result[0].codigo); 
           if(result!=null){
             $('#estado_topico').val(result[0].codigo); 
           }else{ 
             $('#estado_topico').val(""); 
           }
         }
       });
     } 
   ///id topico en realidad es la consulta a la tabla triaje

            // Validar para gestacion on puerpera

   setTimeout(function() {   
     validar_edad(); 
   }, 700);  

            validateGestacionPuerpera();
 });

  const habilitar_registro_manual = document.getElementById("habilitar_registro_manual");
  habilitar_registro_manual.addEventListener("click", function() { 
    if (habilitar_registro_manual.checked) {    
      $('#seccion_habilitra_manual').show(); 
      $('#fecha_registro_ingreso').val(''); 
      $('#fecha_registro_ingreso_select').val(''); 
      $('#hora_registro_ingreso').val(''); 
      $('#registro_manual').val('1'); 
      
    }else{
      $('#seccion_habilitra_manual').hide(); 
      $('#fecha_registro_ingreso').val(''); 
      $('#fecha_registro_ingreso_select').val(''); 
      $('#hora_registro_ingreso').val(''); 
      $('#registro_manual').val('0'); 
    } 
  });   

  //! TODO: TAG: CHECKGESTACION
  // const checkbox_es_gestante = document.getElementById("checkbox_es_gestante");
  // checkbox_es_gestante.addEventListener("click", function() {  
  //   if (checkbox_es_gestante.checked) {    
  //     $('#tamanio_de_cintura').show(); 
  //     $('#frecuencia_fetal').show(); 
  //     $('#seccion_gestante').show(); 
  //     $('#es_gestante').val('1');  
  //   }else{
  //     $('#tamanio_de_cintura').hide(); 
  //     $('#frecuencia_fetal').hide(); 
  //     $('#seccion_gestante').hide(); 
  //     $('#es_gestante').val('0'); 
  //   } 
  // });   
  
  $(document).ready(function() {

    $('.fecha').datepicker({
      language: "es",
      format: "dd/mm/yyyy",
      autoclose: true
    });

    // $('#habilitar_es_gestante').hide(); //! TODO: TAG: CHECKGESTACION
    $('#tamanio_de_cintura').hide(); 
    $('#frecuencia_fetal').hide(); 
    $('#es_gestante').val('0'); 
    $('#seccion_gestante').hide(); 

    $('#registro_manual').val('0'); 
    $('#seccion_habilitra_manual').hide(); 
    getEstado_ingreso();   
    getArea_topico();

   
    $('#topico_toggle').show();   
 

    $('#selectSexo').change(function() { 
      $('#sexo').val($("#selectSexo option:selected").text());
      validar_edad();
      getArea_topico();
                validateGestacionPuerpera();
    });
          
    $('#flag_triaje').val(''); 
    //-------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------
    $obtener_id_de_ticket = '{{request()->segment(2)}}'; 
    if($obtener_id_de_ticket==0 ||$obtener_id_de_ticket==null ||$obtener_id_de_ticket==''){
            console.log('SOLO NUEVO');
    } else {
      
      $('#flag_triaje').val($obtener_id_de_ticket); 
      setTimeout(function() {   
        $('#dni_beneficiario').val(""); 
        $.ajax({
          url:'{{url('buscar_ticket_by_id/')}}/'+$obtener_id_de_ticket,
          type:'GET',
          dataType:'JSON',
            success: function(data){  
              console.log(data[0]); 
              if(data!=null){
                $('#dni_beneficiario').val(data[0].dni_reniec);  
                var boton_btnBuscar_acompaniante = document.getElementById("btnBuscar");
                boton_btnBuscar_acompaniante.click();
              }
            }
          })
        }, 1000);    
    } 
    //-------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------
    

    //------------//-------------------------------------------------------------------------------------
    $("#temperatura").on('blur', function (event) {
      var value = $(this).val();
      //console.log('blur');  
      if (Validar.esVacio(value) == false ) {
        if (!decimalRegex.test(value)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if( !Validar.decimalEnRango(value, 35.0, 40.0)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
            $('#temperatura').addClass('borde-temperatura-hipotermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

        } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
            $('#temperatura').addClass('borde-temperatura-normal');
            $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

        } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
            $('#temperatura').addClass('borde-temperatura-fiebre');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

        } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
            $('#temperatura').addClass('borde-temperatura-fiebre-alta');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

        } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
          $('#temperatura').addClass('borde-temperatura-hipertermia');
          $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
          $('#texto-aceptado strong').addClass('borde-temperatura-valida');
        }
    });

    $("#temperatura").on('input',function(event) { 
    //if (event.type === "click" || event.key === "Tab") {
      
        var value = $(this).val();
        //console.log('input');
         if (Validar.esVacio(value) ) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');
        } else if ( !Validar.decimalEnRango(value, 35.0, 40.0)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');
        } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
            $('#temperatura').addClass('borde-temperatura-hipotermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

        } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
            $('#temperatura').addClass('borde-temperatura-normal');
            $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

        } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
            $('#temperatura').addClass('borde-temperatura-fiebre');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

        } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
            $('#temperatura').addClass('borde-temperatura-fiebre-alta');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

        } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");

        } 
    //}
    });

    $("#temperatura").on('input',function() {
    $(this).removeClass('borde-temperatura borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
    $('#texto-aceptado').html('');
    });
//------------//-------------------------------------------------------------------------------------

    //-------------------------------------------------------------------------------------------------
    $("#saturacion").on('blur', function (event) {
      var value = $(this).val();
      //console.log('blur');  
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 60, 100)) {
            $('#saturacion').addClass('borde-temperatura-hipertermia');
            $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
            $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');

        } else if (value >= 60 && value <= 85){
          //HIPOXIA GRAVE  - ROJO
          $('#saturacion').addClass('borde-saturacion-hipoxia-grave');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
        } else if (value >= 86 && value <= 90){
          //HIPOXIA MODERADA - NARANJA
          $('#saturacion').addClass('borde-saturacion-hipoxia-moderada');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
        } else if (value >= 91 && value <= 94){
          // HIPOXIA LEVE - AMARILLO
          $('#saturacion').addClass('borde-saturacion-hipoxia-leve');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
        } else if (value >= 95 && value <= 100){
          //NORMAL - VERDE
          $('#saturacion').addClass('borde-saturacion-normal');
          $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
          $('#saturacion').addClass('borde-temperatura-hipertermia');
          $('#saturacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
          $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
        }
    
    });

    $("#saturacion").on('input',function(event) {

      var value = $(this).val();

      if (Validar.esVacio(value) ) {
        $('#saturacion').addClass('borde-temperatura-hipertermia');
        $('#saturacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
        $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');

      } else if (!Validar.enteroEnRango(value, 60, 100)) {
        $('#saturacion').addClass('borde-temperatura-hipertermia');
        $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
        $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
      
      } else if (value >= 60 && value <= 85){
        //HIPOXIA GRAVE  - ROJO
        $('#saturacion').addClass('borde-saturacion-hipoxia-grave');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
      } else if (value >= 86 && value <= 90){
        //HIPOXIA MODERADA - NARANJA
        $('#saturacion').addClass('borde-saturacion-hipoxia-moderada');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
      } else if (value >= 91 && value <= 94){
        // HIPOXIA LEVE - AMARILLO
        $('#saturacion').addClass('borde-saturacion-hipoxia-leve');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
      } else if (value >= 95 && value <= 100){
        //NORMAL - VERDE
        $('#saturacion').addClass('borde-saturacion-normal');
        $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
      }
    });

    $("#saturacion").on('input',function() {
      $('#saturacion').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal borde-temperatura-hipertermia');
      $('#saturacion-aceptado').html('');
    });
    
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------

    $("#talla").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
        $('#imc-aceptado').html('');
      });
    
    $("#talla").on('blur',function(event) {  
      var value = $(this).val();
      if (Validar.esVacio(value) == false) {
        if (!decimalRegex.test(value)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.enteroEnRango(value, 30, 200)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');
        }else {
          compruebaIMC();
        }
      }else if (Validar.esVacio(value)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      } 
    });

    $("#talla").on('input',function(event) {  
      var value = $(this).val();
      if (Validar.esVacio(value) ) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');

      } else if (!Validar.enteroEnRango(value, 30, 200)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      }else {
        compruebaIMC();
      } 
    });

    $("#talla").on('input',function() {
      $('#talla').removeClass('borde-temperatura-hipertermia');
      $('#talla-aceptado').html('');
    });
    
//====================================================================================================

    $("#peso").on('blur',function(event) {
        var value = $(this).val();
        if (Validar.esVacio(value) == false) {
          if (!decimalRegex.test(value)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

          } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

          } else {
            compruebaIMC();
          }
        }else if (Validar.esVacio(value)) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');
        }
    });

    $("#peso").on('input',function(event) {
        var value = $(this).val();
        if (Validar.esVacio(value) ) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else {
          compruebaIMC();
        }
    });

    $("#peso").on('input',function() {
      $('#peso').removeClass('borde-temperatura-hipertermia');
      $('#peso-aceptado').html('');
    });

    $("#peso").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });
//-------------------------------------------------------------------------------------------------

$("#tamanio_cintura").keydown(function(event) {
      if (event.key === "Tab" ) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        var value = $('#tamanio_cintura').val();
        if (Validar.esVacio(value) ) {
                alert('Ingrese el tamanio de la cintura ( cm ) ');
                event.preventDefault(); 
                $('#tamanio_cintura').focus();
                return 0;

            } else if (!Validar.enteroEnRango(value, 10, 45)) {
                alert('El valor del tamanio de la cintura debe ser entre 10 a 45 ( cm )');
                event.preventDefault(); 
                $('#tamanio_cintura').focus();
                return 0; 
            } 
      }
    }); 

 

$("#tamanio_cintura").on('blur',function(event) { 
  var value = $('#tamanio_cintura').val();
  if(value.length==0){
        $('#tamanio_cintura').addClass('borde-alerta');
        $('#tamanio_cintura-aceptado').html("<p><strong>" + 'El valor del Tamaño de Cintura debe ser entre 10 a 45 ( /cm )' + "</strong></p>"); 
        $('#tamanio_cintura-aceptado strong').addClass('borde-temperatura-valida');
  }else{
    if (Validar.esVacio(value) == false ) {
      if (!Validar.enteroEnRango(value, 10, 45)) {
        $('#tamanio_cintura').addClass('borde-temperatura-valida');
        $('#tamanio_cintura-aceptado').html("<p><strong>" + 'El valor del Tamaño de Cintura debe ser entre 10 a 45 ( /cm )' + "</strong></p>"); 
        $('#tamanio_cintura-aceptado strong').addClass('borde-temperatura-valida');
      }else{ 
        $('#tamanio_cintura-aceptado').html("");
      }
    }else {
      $('#tamanio_cintura').removeClass('borde-temperatura-valida');
      $('#tamanio_cintura-aceptado').html("");
      $('#tamanio_cintura-aceptado strong').removeClass('borde-temperatura-valida');
    } 
  }
});

//-------------------------------------------------------------------------------------------------
$("#frecuencia_cardiaca_fetal").on('blur',function(event) { 
  var value = $('#frecuencia_cardiaca_fetal').val();
  if(value.length==0){
        $('#frecuencia_cardiaca_fetal').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca_fetal-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 0 a 170 ( /min )' + "</strong></p>"); 
        $('#frecuencia_cardiaca_fetal-aceptado strong').addClass('borde-temperatura-valida');
  }else{
    if (Validar.esVacio(value) == false ) {
      if (!Validar.enteroEnRango(value, 0, 170)) {
        $('#frecuencia_cardiaca_fetal').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca_fetal-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 0 a 170 ( /min )' + "</strong></p>"); 
        $('#frecuencia_cardiaca_fetal-aceptado strong').addClass('borde-temperatura-valida');
      }
    }else {
      $('#frecuencia_cardiaca_fetal').removeClass('borde-temperatura-hipertermia');
      $('#frecuencia_cardiaca_fetal-aceptado').html("");
      $('#frecuencia_cardiaca_fetal-aceptado strong').removeClass('borde-temperatura-valida');
    } 
  }
});
      $('#frecuencia_cardiaca_fetal').removeClass('borde-temperatura-hipertermia');
      $('#frecuencia_cardiaca_fetal-aceptado').html("");
      $('#frecuencia_cardiaca_fetal-aceptado strong').removeClass('borde-temperatura-valida');

//-------------------------------------------------------------------------------------------------
$("#presion_arterial_x").on('blur',function(event) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

        if (beneficiario == "" && nro_doc_ident == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });
        } else {
          var value = $(this).val();
            if (Validar.esVacio(value) == false ) {
              if (!Validar.enteroEnRango(value, 0, 250)) {
                $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_x-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
                $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
              }
            } else if(Validar.esVacio(value)){
                $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_x-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
                $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
            }
        }
});

$("#presion_arterial_x").on('input',function(event) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

        if (beneficiario == "" && nro_doc_ident == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });
        } else {
            var value = $('#presion_arterial_x').val();
            if (Validar.esVacio(value) ) {
                $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_x-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
                $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');

            } else if (!Validar.enteroEnRango(value, 0, 250)) {
                $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_x-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
                $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
            } 
      }
});

  $("#presion_arterial_x").on('input',function() {
    $('#presion_arterial_x').removeClass('borde-temperatura-hipertermia');
    $('#presion_arterial_x-aceptado').html('');
  });
//-------------------------------------------------------------------------------------------------

$("#presion_arterial_y").on('blur',function(event) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

        if (beneficiario == "" && nro_doc_ident == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });
        } else {
          var value = $(this).val();
            if (Validar.esVacio(value) == false ) {
              if (!Validar.enteroEnRango(value, 0, 140)) {
                $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_y-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
                $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
              }
            } else if(Validar.esVacio(value)){
                $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_y-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
                $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
            }
        }
});

$("#presion_arterial_y").on('input',function(event) {
  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
  var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

        if (beneficiario == "" && nro_doc_ident == "") {

          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });

        } else {          
        var value = $('#presion_arterial_y').val();
        if (Validar.esVacio(value) ) {
                $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_y-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
                $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');

            } else if (!Validar.enteroEnRango(value, 0, 140)) {
                $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
                $('#presion_arterial_y-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
                $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
            } 
        }
    });

  $("#presion_arterial_y").on('input',function() {
    $('#presion_arterial_y').removeClass('borde-temperatura-hipertermia');
    $('#presion_arterial_y-aceptado').html('');
  });
//-------------------------------------------------------------------------------------------------

$("#frecuencia_respiratoria").on('blur',function(event) {
  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
  var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
  
  if (beneficiario == "" && nro_doc_ident == "") {

    Swal.fire({
    title: '<div class="titulo-padding"><p>Alerta de Frecuencia Cardiaca</p></div>',
    html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK',
    allowOutsideClick: false,
    preConfirm: function(){
      limpiar_signos_vitales();
      limpiar_datos_adicional();
      $('#tipodoc_beneficiario_bus').focus();
    }
    });
  } else {   
      var value = $('#frecuencia_respiratoria').val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 10, 35)) {
          $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
          $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
          $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
        } 
              
      }else if (Validar.esVacio(value)) {
          $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
          $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
          $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
      }
  }    
    });

$("#frecuencia_respiratoria").on('input',function(event) {
  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
  var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

      if (beneficiario == "" && nro_doc_ident == "") {

        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Frecuencia Respiratoria</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });

      } else {          
        var value = $('#frecuencia_respiratoria').val();
        if (Validar.esVacio(value) ) {
              $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
              $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
              $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
              
        } else if (!Validar.enteroEnRango(value, 10, 35)) {
            $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
            $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');

        } 
      }
    });

  $("#frecuencia_respiratoria").on('input',function() {
    $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_respiratoria-aceptado').html('');
  });
//-------------------------------------------------------------------------------------------------
$("#frec_cardiaca").on('blur',function(event) {
  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
  var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

      if (beneficiario == "" && nro_doc_ident == "") {

        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Frecuencia Cardiaca</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });

      } else {          
        var value = $('#frec_cardiaca').val();
        if (Validar.esVacio(value) == false ) {
          if (!Validar.enteroEnRango(value, 50, 150)) {
            $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
            $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
            $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
          }
        }else if (Validar.esVacio(value)) {
          $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
          $('#frec_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
          $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
        }
      }
        
    });

$("#frec_cardiaca").on('input',function(event) {
  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
  var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

      if (beneficiario == "" && nro_doc_ident == "") {

        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Frecuencia Cardiaca</p></div>',
        html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          limpiar_signos_vitales();
          limpiar_datos_adicional();
          $('#tipodoc_beneficiario_bus').focus();
        }
        });

      } else {          
        var value = $('#frec_cardiaca').val();
        if (Validar.esVacio(value) ) {
            $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
            $('#frec_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
            $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
            
        } else if (!Validar.enteroEnRango(value, 50, 150)) {
            $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
            $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
            $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
        } 
      }
        
    });

    $("#frec_cardiaca").on('input',function() {
      $('#frec_cardiaca').removeClass('borde-temperatura-hipertermia');
      $('#frec_cardiaca-aceptado').html('');
    });
//-------------------------------------------------------------------------------------------------

      $("#oxigenacion").on('blur',function(event) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        if (beneficiario == "" && nro_doc_ident == "") {
              Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
              html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'OK',
              allowOutsideClick: false,
              preConfirm: function(){
                limpiar_signos_vitales();
                limpiar_datos_adicional();
                $('#tipodoc_beneficiario_bus').focus();
              }
              });

        } else {          
          var value = $('#oxigenacion').val();
          if (Validar.esVacio(value) == false ){
            if (!Validar.enteroEnRango(value, 21, 100)) {
              $('#oxigenacion').addClass('borde-temperatura-hipertermia');
              $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
              $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
            } 
          }else if (Validar.esVacio(value)) {
            $('#oxigenacion').addClass('borde-temperatura-hipertermia');
            $('#oxigenacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Oxigenacion ( % )' + "</strong></p>");
            $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
          }
        }
      });

      $("#oxigenacion").on('input',function(event) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        if (beneficiario == "" && nro_doc_ident == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });

        } else {          
        var value = $('#oxigenacion').val();
          if (Validar.esVacio(value) ) {
              $('#oxigenacion').addClass('borde-temperatura-hipertermia');
              $('#oxigenacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Oxigenacion ( % )' + "</strong></p>");
              $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
          } else if (!Validar.enteroEnRango(value, 21, 100)) {
              $('#oxigenacion').addClass('borde-temperatura-hipertermia');
              $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
              $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
          } 
        }
      });

    $("#oxigenacion").on('input',function() {
      $('#oxigenacion').removeClass('borde-temperatura-hipertermia');
      $('#oxigenacion-aceptado').html('');
    });
//-------------------------------------------------------------------------------------------------

      function validarReniecCampo(){
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        if (beneficiario == "" && nro_doc_ident == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#tipodoc_beneficiario_bus').focus();
          }
          });
        } 
      }

     function validarCamposSignosVitales() {
                  var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;

        if (beneficiario == "" && nro_doc_ident == "") {
            Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function(){ 
              $('#tipodoc_beneficiario_bus').focus();
            }
            });
        } else {
 
        }
      }
        $('#id_prioridad, #id_topico, #observacion, #observacion_4, #prioridad_4').on('input click', function() {
          validarCamposSignosVitales();
        });


        $('#temperatura, #talla, #saturacion, #peso, #tamanio_cintura, #presion_arterial_x, #presion_arterial_y, #frecuencia_respiratoria, #frec_cardiaca, #oxigenacion').on('input click', function() {
          validarReniecCampo();
        });
        });

 
  function limpiar_paciente() {
    $('#seccion_datos_titular').hide(); 
    //alert("limpiar");
    $('#beneficiario').val("");
    $('#tipo_doc_ident').val("");
    $('#nro_doc_ident').val("");
    $('#nombre').val("");
    $('#paterno').val("");
    $('#materno').val("");
    $('#sexo').val("");
    $('#fecha_nac').val("");  
    $('#edad_pac').val('');
    $('#edad_paciente2').val('');
    $('#parentesco_paciente').val('');
    
    $('#foto_paciente').val("");  
    var imagen = document.getElementById("foto_imagen_paciente");   
    imagen.src =  "";
    $('#id_departamento').val('');
    $('#id_provincia').val('');
    $('#id_distrito').val('');
    $('#codigo_ubig').val('');
    $('#domicilio').val('');
    $('#descripcion_tipo_beneficiario_saludpol').val('');
    // $('#habilitar_es_gestante').hide(); //! TODO: TAG: CHECKGESTACION
    
    $('#id_prioridad').val('').change();
    $('#id_topico').val('').change();

    $('#id_forma_ingreso').val('').change();
    $('#id_estado_ingreso').val('').change();
    $('#id_motivo_ingreso').val('').change();

    
    $('#documento_titular').val('');
    $('#descripcion_titular').val('');
    $('#cip_titular').val('');
    $('#grado_titular').val('');
  }
        var controlPrenatalInput = document.getElementById("control_prenatal");

controlPrenatalInput.addEventListener("input", function() {
    var value = this.value.trim(); // Obtener el valor y eliminar espacios en blanco

    // Verificar si el valor no está vacío y no es un número de un solo dígito
    if (value !== "" && !/^\d$/.test(value)) {
        // Restablecer el valor a un dígito si se ingresa más de uno
        this.value = value.charAt(0);
    }
});

controlPrenatalInput.addEventListener("keypress", function(event) {
    var key = event.key;

    // Permitir solo números y la tecla de retroceso (backspace)
    if (!/^\d$/.test(key) && key !== "Backspace") {
        event.preventDefault(); // Prevenir la entrada de otros caracteres
    }
});

    function validar_edad(){  
      var sexo = $('#sexo').val(); 
      var edad = $('#edad_pac').val(); 
      var id_topico = $('#id_topico').val();
      var estado_topico = $('#estado_topico').val();
      
      $('#tamanio_cintura').val('');
      $('#frecuencia_cardiaca_fetal').val('');
      
      if(sexo == ''){
        sexo = $("#selectSexo option:selected").text();
      }
      //if($("#id_topico option:selected").text() =='GINECOLOGÍA Y OBSTETRICIA' || $("#id_topico option:selected").text() =='GINECOLOGIA Y OBSTETRICIA'){ 
      if(estado_topico=='4'){ 
        if(edad!=''){
          if(edad>=7){
            if (sexo == "F") {
              // $('#habilitar_es_gestante').show(); //! TODO: TAG: CHECKGESTACION
              $('#tamanio_cintura').val(''); 
              $('#frecuencia_cardiaca_fetal').val(''); 
            } else {
              // $('#habilitar_es_gestante').hide();  //! TODO: TAG: CHECKGESTACION
              // document.getElementById('checkbox_es_gestante').checked = false; //! TODO: TAG: CHECKGESTACION
              $('#es_gestante').val('0'); 
              $('#tamanio_cintura').val(''); 
              $('#frecuencia_cardiaca_fetal').val(''); 
            }
          }else{ 
            // $('#habilitar_es_gestante').hide(); //! TODO: TAG: CHECKGESTACION
            // document.getElementById('checkbox_es_gestante').checked = false; //! TODO: TAG: CHECKGESTACION
            $('#es_gestante').val('0'); 
              $('#tamanio_cintura').val(''); 
              $('#frecuencia_cardiaca_fetal').val(''); 
          }
        }else{
          // $('#habilitar_es_gestante').hide(); //! TODO: TAG: CHECKGESTACION
          // document.getElementById('checkbox_es_gestante').checked = false; //! TODO: TAG: CHECKGESTACION
          $('#es_gestante').val('0'); 
              $('#tamanio_cintura').val(''); 
              $('#frecuencia_cardiaca_fetal').val(''); 
        }
      }else{
        // $('#habilitar_es_gestante').hide(); //! TODO: TAG: CHECKGESTACION
        $('#tamanio_de_cintura').hide(); 
        $('#frecuencia_fetal').hide(); 
        // document.getElementById('checkbox_es_gestante').checked = false; //! TODO: TAG: CHECKGESTACION
        $('#es_gestante').val('0'); 
              $('#tamanio_cintura').val(''); 
              $('#frecuencia_cardiaca_fetal').val(''); 
      }

            validateGestacionPuerpera();
    }
 

  function limpiar_signos_vitales() {
    $('#temperatura').val("");
    $('#talla').val("");
    $('#peso').val("");
    $('#imc').val("");
    $('#peso_x_altura').val("");
    $('#saturacion').val("");
    $('#tamanio_cintura').val("");
    $('#frecuencia_cardiaca_fetal').val("");
    $('#presion_arterial_x').val("");
    $('#presion_arterial_y').val("");
    $('#frecuencia_respiratoria').val("");
    $('#frec_cardiaca').val("");
    $('#oxigenacion').val("");
  }

  function limpiar_datos_adicional() {
    $("#id_prioridad").val(0);
    $("#id_forma_ingreso").val('');
    $("#id_estado_ingreso").val('');
    $("#id_motivo_ingreso").val('');
    $("#id_topico").empty();
    $('#observacion').val("");
  }

  $(document).ready(function() {
    $("#selectSexo").hide(); 

    $("#seccion_datos_titular").hide(); 
    $("#seleccionaTipoBeneficiario").hide(); 


    $("#fecha_nacimiento_editable").hide(); 
    $('#edad_nacimiento_editable').hide();
    $('#seccion_medico_cita').hide(); 

    $('#dni_beneficiario').focus();

    $("#dni_beneficiario").keydown(function(e) {
      if (e.which == 13 || e.which == 9 || event.key === "Tab") {
        getReniecByDni();
      }
    });

    $("#sexo").change(function(e) {
      validar_edad();
    }); 


            
    $('#fecha_nac2').change(function() {
      var fechaIni = $('#fecha_nac2').val();
      f1 = fechaIni.split("/");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
      if (f1 > f3) {
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>La Fecha de Nacimiento debe ser menor o igual a la fecha Actual.</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            $('#fecha_nac2').val("");
            $('#fecha_nac2').focus();
          }
        });
      }else{ 
        calcular_edad_paciente(fechaIni); 
      }  
    });
 
  });  

  
// ------------------------------- RENIEC - PACIENTE --------------------------------------------------------------------------------------
  function calcular_edad_paciente(fecha_nac){

    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      //console.log('oldDate: '+oldDate);
      var values = oldDate.split("/");
      //console.log('values.length: '+values.length);
      if(values.length==1){
        var values = oldDate.split("-");
        var dia = values[2];
        var mes = values[1];
        var ano = values[0];
        console.log('dia.mes:ano '+dia+'/'+mes+'/'+ano);
        $('#fecha_nac').val(dia+'/'+mes+'/'+ano);
      }else{
        var dia = values[0];
        var mes = values[1];
        var ano = values[2];
        $('#fecha_nac').val(dia+'/'+mes+'/'+ano);
      }  

      $('#edad_pac').val('');
      $('#edad_paciente2').val('');
      calcularEdadPersona(ano,mes,dia,'input','edad_paciente2','edad_pac');       
      getArea_topico();
      validar_edad();
    }

  }
  
  
  // function getTipo_beneficiarioDescrip(id_tipo_beneficiario){  

  //   var tipo_beneficiario_saludpol='';
  //   if(id_tipo_beneficiario=='PADRES'){
  //     tipo_beneficiario_saludpol = "1";
  //   }else if(id_tipo_beneficiario=='HIJO MENOR DE EDAD'){
  //     tipo_beneficiario_saludpol = "2";
  //   }else if(id_tipo_beneficiario=='CONCUBINA(O)'){
  //     tipo_beneficiario_saludpol = "3";
  //   }else if(id_tipo_beneficiario=='CONYUGE'){
  //     tipo_beneficiario_saludpol = "4";
  //   }else if(id_tipo_beneficiario=='HIJO MAYOR DE EDAD'){
  //     tipo_beneficiario_saludpol = "5";
  //   }else if(id_tipo_beneficiario=='HIJO INCAPACITADO'){
  //     tipo_beneficiario_saludpol = "6";
  //   }else if(id_tipo_beneficiario=='DERECHOHABIENTE'){
  //     tipo_beneficiario_saludpol = "7";
  //   }else if(id_tipo_beneficiario=='TITULAR'){
  //     tipo_beneficiario_saludpol = "8";
  //   }else if(id_tipo_beneficiario=='PADRES SOBREVIVIENTE'){
  //     tipo_beneficiario_saludpol = "9";
  //   }else if(id_tipo_beneficiario=='ESPOSA'){
  //     tipo_beneficiario_saludpol = "10";
  //   }else if(id_tipo_beneficiario=='HIJO NO INCAPACITADO'){
  //     tipo_beneficiario_saludpol = "11";
  //   }else if(id_tipo_beneficiario=='OTROS'){
  //     tipo_beneficiario_saludpol = "12";
  //   }else if(id_tipo_beneficiario=='CIVIL'){
  //     tipo_beneficiario_saludpol = "13";
  //   } else{
  //     tipo_beneficiario_saludpol = "13";
  //   }
  //   $('#tipo_beneficiario_saludpol').val(tipo_beneficiario_saludpol);    
  // }

  function getTipo_beneficiario(id_tipo_beneficiario, descripcion_tipo_beneficiario_saludpol){ 
    //console.log('---113333--wfwfwefwe'+id_tipo_beneficiario);

    var tipo_beneficiario_saludpol='';
    if(id_tipo_beneficiario=='1'){
      tipo_beneficiario_saludpol = "PADRES";
    }else if(id_tipo_beneficiario=='2'){
      tipo_beneficiario_saludpol = "HIJO MENOR DE EDAD";
    }else if(id_tipo_beneficiario=='3'){
      tipo_beneficiario_saludpol = "CONCUBINA(O)";
    }else if(id_tipo_beneficiario=='4'){
      tipo_beneficiario_saludpol = "CONYUGE";
    }else if(id_tipo_beneficiario=='5'){
      tipo_beneficiario_saludpol = "HIJO MAYOR DE EDAD";
    }else if(id_tipo_beneficiario=='6'){
      tipo_beneficiario_saludpol = "HIJO INCAPACITADO";
    }else if(id_tipo_beneficiario=='7'){
      tipo_beneficiario_saludpol = "DERECHOHABIENTE";
    }else if(id_tipo_beneficiario=='8'){
      tipo_beneficiario_saludpol = "TITULAR";
    }else if(id_tipo_beneficiario=='9'){
      tipo_beneficiario_saludpol = "PADRES SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='10'){
      tipo_beneficiario_saludpol = "ESPOSA";
    }else if(id_tipo_beneficiario=='11'){
      tipo_beneficiario_saludpol = "HIJO NO INCAPACITADO";
    }else if(id_tipo_beneficiario=='12'){
      tipo_beneficiario_saludpol = "OTROS";
    }else if(id_tipo_beneficiario=='13'){
      tipo_beneficiario_saludpol = "CIVIL";
    }else if(id_tipo_beneficiario=='14'){
      tipo_beneficiario_saludpol = "HIJO SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='15'){
      tipo_beneficiario_saludpol = "CONYUGE SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='16'){
      tipo_beneficiario_saludpol = "HIJO INCAPACITADO SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='17'){
      tipo_beneficiario_saludpol = "HIJO NO INCAPACITADO SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='18'){
      tipo_beneficiario_saludpol = "PADRES SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='19'){
      tipo_beneficiario_saludpol = "TITULAR SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='20'){
      tipo_beneficiario_saludpol = "HIJO ESTUDIANTE";
    }else if(id_tipo_beneficiario=='21'){
      tipo_beneficiario_saludpol = "HIJO MAYOR DE EDAD NO ESTUDIANTE";
    }else if(id_tipo_beneficiario=='22'){
      tipo_beneficiario_saludpol = "CONCUBINA(O) SOBREVIVIENTE";
    } 
    
    // console.log('123.:: - '+tipo_beneficiario_saludpol);
    // console.log('123.:id_tipo_beneficiario: - '+id_tipo_beneficiario);
    $("#parentesco_paciente").val(id_tipo_beneficiario); 
    $("#tipo_beneficiario_saludpol").val(id_tipo_beneficiario); 
    $("#"+descripcion_tipo_beneficiario_saludpol).val(tipo_beneficiario_saludpol); 
  } 
  function getReniecByDni() {
     
    $('#id_prioridad').empty();   
    $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
    $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>"); 
    $('#id_prioridad').append("<option value='2'>II - URGENCIA MAYOR</option>"); 
    $('#id_prioridad').append("<option value='3'>III - URGENCIA MENOR</option>"); 
    $('#id_prioridad').append("<option value='4'>IV - PATOLOGÍA AGUDA COMÚN</option>"); 

              $('#grado').val(''); 
              $('#situaciontitular').val(''); 
              $('#nrodoctitular').val(''); 
              $('#ciptitular').val(''); 
              $('#unidad_pnptitular').val(''); 
              $('#situacionpaciente').val('');
              $('#tipo_beneficiario_saludpol').val('');
              $('#parentesco_paciente').val('');

            $('#listGestacionesAtendidas').empty('');
            $('#divGestacionesAtendidas').hide();

    limpiar_paciente();
    limpiar_signos_vitales();
    limpiar_datos_adicional();
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_paciente = $("#dni_beneficiario").val();
    let usarApi = "{{ $flag_api_sp ? 'api' : 'ws' }}";
    console.log(usarApi);
    var origen = 'triaje_emg';
    
    function promptRegistrarPacienteManual() {
      Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Paciente NO encontrado. ¿Desea registrarlo manualmente?</span></p>',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'SI',
          allowOutsideClick: false,
        }).then((result) => {
          if (result.isConfirmed) {
            //habilitar campos para registro
            $('#menor_edad').val('1');
            $('#tipo_registro').val('0');

            $("#seccion_datos_titular").hide();

            var tipo_beneficiario_saludpol = 13;
            $('#tipo_beneficiario_saludpol').val(tipo_beneficiario_saludpol);
            getTipo_beneficiario($('#tipo_beneficiario_saludpol').val(), "descripcion_tipo_beneficiario_saludpol");
            document.getElementById("fecha_nac2").focus();

            var combo = document.getElementById("tipodoc_beneficiario_bus");
            var tipo_doc_ident = combo.options[combo.selectedIndex].text;
            document.getElementById("tipo_doc_ident").value = tipo_doc_ident;
            var dni_beneficiario = $("#dni_beneficiario").val();
            $("#nro_doc_ident").val(dni_beneficiario);
            llenar_manual_datos_paciente(true);
            //Swal.fire('Confirmado', 'La acción se realizó correctamente', 'success');

            $('#id_prioridad').empty();
            $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
            $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>");
            $('#id_prioridad').append("<option value='4'>IV - PATOLOGÍA AGUDA COMÚN</option>");
            $('#id_prioridad').val("").change();
            modalHistorial(dni_beneficiario);
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            document.getElementById("dni_beneficiario").focus();
            limpiar_paciente();
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            //Swal.fire('Cancelado', 'La acción ha sido cancelada', 'error');
          }
        });
    }

    if (dni_paciente == "") { 
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que ingresar el Documento del paciente</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
      });
      $('#dni_beneficiario').addClass('borde-alerta');
      $('#dni_beneficiario').focus(); 
      return;
    }else{ 
      $('#dni_beneficiario').removeClass('borde-alerta');
    }


    if (tipodoc_beneficiario == "1") {
      if (dni_paciente.length != 8) {
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Cuidado, recuerde que el N° DNI debe tener 8 dígitos.</p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
        });
        $('#dni_beneficiario').addClass('borde-alerta');
        $('#dni_beneficiario').focus(); 
        return;
      }
    }else{ 
      $('#dni_beneficiario').removeClass('borde-alerta');
    }


    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Espere un momento, por favor...</div>');
    $('#btnBuscar').prop('disabled', true); 
    var paciente_no_existe = false;

    Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>ESPERE UN MOMENTO POR FAVOR, VALIDANDO DOCUMENTO DE IDENTIDAD.<br><br></span></p>', 
        allowOutsideClick: false,
        timer: 6800,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
      });
      
    $.ajax({ 
      url: "{{ route('obtener_datos_persona_ajax') }}",
      type: "GET",
      dataType: "JSON",
      data: {
        dni: dni_paciente,
        tipo_documento: tipodoc_beneficiario,
        usarApi: usarApi,
        origen: origen
      },
      success: function(result) {
        const jsonResult = JSON.stringify(result);
        //console.log(jsonResult)

        if (usarApi == 'api' && result != null && result.tipo_seguro_estadows != undefined && !(result.id > 0) && (result.nompaisdelafiliado == undefined || result.nompaisdelafiliado === '')) {
          var esNoActivo = (result.tipo_seguro_estadows == 'El asegurado no esta activo');
          Swal.fire({
           icon: 'warning',
            title: 'ALERTA',
            html: esNoActivo ? `
                <div style="font-size:16px; line-height:1.6; text-align:center;">
                    El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                    <br>
                    Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                    <br>
                    Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                </div>
            ` : `
                <div style="font-size:16px; line-height:1.6; text-align:center;">
                    <strong>NO SE ENCUENTRA REGISTRO</strong> de paciente en <strong>SALUDPOL</strong>.
                    <br>
                    Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                    <br>
                    Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                </div>
            `,
            confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
            confirmButtonColor: '#3085d6',
            width: '38em',
            customClass: {
              popup: esNoActivo ? 'swal-noafiliado' : 'swal-noafiliadosp',
              title: 'swal-title',
              htmlContainer: 'swal-content'
            }
          }).then(() => {
            promptRegistrarPacienteManual();
          });
          return;
        }

        if (result != null) {
          $("#beneficiario_ajax").val(jsonResult);
          if (result.situacion_paciente != 'FALLECIDO') {
            if (result.codigoMensaje == "MR") {
              paciente_no_existe = true;
   
            }else if (result[0] != undefined) { 
              paciente_no_existe = true;
             
            } else if ( (result.id > 0  && result.id_tipo_doc_ident > 0 )){
              //console.log('---- ASEGURADOS TABLE ----');  
              $("#id_prioridad").val("");
             
               //  --------------   ---------------------- // tabla asegurados
  
              $('#tipo_registro').val('1');
  
              apepatafiliado=result.paterno;
              apematafiliado=result.materno;
              nomafiliado=result.nombre;
              if(apepatafiliado != undefined){
                apepatafiliado =result.paterno; 
              }
              if(apematafiliado != undefined){
                apematafiliado =result.materno;
              }
              if(nomafiliado != undefined){
                nomafiliado =result.nombre;
              }
              paciente = apepatafiliado + ' ' + apematafiliado + ' ' + nomafiliado;
              
              $('#beneficiario').val(paciente);
              $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
              $('#tipo_doc_ident').val(result.tipo_doc_ident);
              $('#nro_doc_ident').val(result.nro_doc_ident);
              $('#nombre').val(result.nombre);
              $('#paterno').val(result.paterno);
              $('#materno').val(result.materno);
              $('#sexo').val(result.sexo);
              $('#fecha_nac').val(result.fecha_nac);
              $('#domicilio').val(result.domicilio);
  
              
              $('#codigo_ubig').val(result.codigo_ubig);
              $('#id_departamento').val(result.id_departamento);
              $('#id_provincia').val(result.id_provincia);
              $('#id_distrito').val(result.id_distrito); 
   
  
              calcular_edad_paciente(result.fecha_nac);
  
              ////obtenemos la foto 
              var edad_pac= $('#edad_pac').val();
                if(edad_pac>=218){  
                if(result.foto != undefined && result.foto!=null){  
                  $('#foto_paciente').val(result.foto);  
                  var imagen = document.getElementById("foto_imagen_paciente");  
                  imagen.src = "data:image/jpeg;base64," + result.foto;
                }
              }
                
              id_tipo_beneficiario=result.id_tipo_beneficiario;
              if(id_tipo_beneficiario==null){
                id_tipo_beneficiario=result.id_tipo_beneficiario;
              }
              //console.log('---- ASEGURADOS TABLE22222 ----'+id_tipo_beneficiario);  
              $('#tipo_beneficiario_saludpol').val('');
              if(id_tipo_beneficiario==null){
                $('#tipo_beneficiario_saludpol').val('13');
              }else{
                $('#tipo_beneficiario_saludpol').val(id_tipo_beneficiario);
              } 
              getTipo_beneficiario($('#tipo_beneficiario_saludpol').val(), "descripcion_tipo_beneficiario_saludpol"); 
                
  
                if(result.id_tipo_beneficiario=='8'||result.id_tipo_beneficiario=='TITULAR'){   //titular
                  if(result.grado!=null && result.grado!=''){
                    $('#grado').val(result.grado);
                    $('#grado_titular').val(result.grado);
                  }
  
                  if(result.situacion!=null && result.situacion!=''){
                    $('#situaciontitular').val(result.situacion);
                  }
                  
                  if(result.id_situacion!=null && result.id_situacion!=''){
                    $('#situacionpaciente').val(result.id_situacion);
                  } 
                } else{  
                  if(result.gradotitular!=null && result.gradotitular!=''){
                    $('#grado').val(result.gradotitular);
                    $('#grado_titular').val(result.gradotitular);
                  }
                  
                  if(result.id_situacion!=null && result.id_situacion!=''){
                    $('#situacionpaciente').val(result.id_situacion);
                  } 
                }
  
                if(result.situaciontitular!=null && result.situaciontitular!=''){
                  $('#situaciontitular').val(result.situaciontitular);
                } 
  
                if(result.ciptitular!=null && result.ciptitular!=''){
                  $('#ciptitular').val(result.ciptitular);
                  $('#cip_titular').val(result.ciptitular);
                }
  
                if(result.nrodoctitular!=null && result.nrodoctitular!=''){
                  $('#nrodoctitular').val(result.nrodoctitular);
                  $('#documento_titular').val(result.nrodoctitular); 
                }
  
                if(result.unidad_pnptitular!=null && result.unidad_pnptitular!=''){
                  $('#unidad_pnptitular').val(result.unidad_pnptitular);
                }
  
                if(result.id_tipo_beneficiario!=null && result.id_tipo_beneficiario!=''){
                  $('#parentesco_paciente').val(result.id_tipo_beneficiario); 
                }
  
                apepattitular=result.apepattitular;
                apemattitular=result.apemattitular;
                nomtitular=result.nomtitular;
                if(apepattitular != undefined){
                  apepattitular =result.apepattitular; 
                }else{
                  apepattitular ='';
                }
  
                if(apemattitular != undefined){
                  apemattitular =result.apemattitular;
                }else{
                  apemattitular ='';
                }
  
                if(nomtitular != undefined){
                  nomtitular =result.nomtitular;
                }else{
                  nomtitular ='';
                }
  
                titular = apepattitular + ' ' + apemattitular + ' ' + nomtitular;
                $('#descripcion_titular').val(titular); 
  
                if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                  if($("#grado_titular").val()==null || $("#grado_titular").val()==''){
                    if ($("#situacionpaciente").val().indexOf('ALUMNO') !== -1) {
                      $('#grado_titular').val($("#situacionpaciente").val());
                    } 
                  }
                }
              
  
                var valida_civil= $('#descripcion_tipo_beneficiario_saludpol').val();
                if(valida_civil=='CIVIL'){
                  $("#seccion_datos_titular").hide(); 
  
                  $('#id_prioridad').empty();  
                  $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
                  $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>"); 
                  
                  $('#id_prioridad').val("").change(); 
                  
                  if(result.tipo_seguro_estadows != undefined){
                    if(result.tipo_seguro_estadows == 'El asegurado no esta activo'){
                      Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: `
                            <div style="font-size:16px; line-height:1.6; text-align:center;">
                                El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                                <br>
                                Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                                <br>
                                Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                            </div>
                        `,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3085d6',
                        width: '38em',
                        customClass: {
                          popup: 'swal-noafiliado',
                          title: 'swal-title',
                          htmlContainer: 'swal-content'
                        }
                      });
                    }else if(result.tipo_seguro_estadows == 'No se encuentra afiliado'){
                      Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: `
                            <div style="font-size:16px; line-height:1.6; text-align:center;">
                                <strong>NO SE ENCUENTRA REGISTRO</strong> de paciente en <strong>SALUDPOL</strong>.
                                <br>
                                Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                                <br>
                                Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                            </div>
                        `,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3085d6',
                        width: '38em',
                        customClass: {
                          popup: 'swal-noafiliadosp',
                          title: 'swal-title',
                          htmlContainer: 'swal-content'
                        }
                      });
                    }else{ 
                      Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                        html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 1200,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      });
                    }
                  }else{
                    Swal.fire({
                      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                      html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                      allowOutsideClick: false,
                      timer: 1200,
                      timerProgressBar: true,
                      showConfirmButton: false,
                      allowOutsideClick: false,
                    });
                  }
  
                }else{ 
  
                  if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                    $("#seccion_datos_titular").show(); 
                  }
  
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 1200,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                  });
                  click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                  modalHistorial(dni_paciente);
                }
              
              //validar_edad();
                              validateGestacionPuerpera()
  
            
            } else if ( tipodoc_beneficiario == "1" && result.codigoMensaje == '00'){ // --- Reniec
     
              $("#id_prioridad").val("");
   
              $('#tipo_registro').val('1');
              paciente = result.paterno + ' ' + result.materno + ' ' + result.nombres;
              $('#beneficiario').val(paciente);
              $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
              $('#tipo_doc_ident').val("DNI");
              $('#nro_doc_ident').val(result.dni);
              $('#nombre').val(result.nombres);
              $('#paterno').val(result.paterno);
              $('#materno').val(result.materno); 
              var sexoPaciente = result.sexo == 'MASCULINO' ? 'M' : 'F';
              $('#sexo').val(sexoPaciente);  
              $('#fecha_nac').val(result.vfecha_nac);
  
              $('#domicilio').val(result.direccionDomicilio); 
              $('#codigo_ubig').val(result.codigo_ubig);
              $('#id_departamento').val(result.id_departamento);
              $('#id_provincia').val(result.id_provincia);
              $('#id_distrito').val(result.id_distrito); 
  
              if(result.id_tipo_beneficiario!=null && result.id_tipo_beneficiario!=''){
                $('#parentesco_paciente').val(result.id_tipo_beneficiario); 
              }
  
  
              $('#grado').val(''); 
              $('#situaciontitular').val(''); 
  
              //MOSTRAR FORMULARIO  
              calcular_edad_paciente(result.fecha_nac);
  
              ////obtenemos la foto 
              var edad_pac= $('#edad_pac').val();
              if(edad_pac>=218){
                if(result.foto != undefined && result.foto!=null){  
                  $('#foto_paciente').val(result.foto);  
                  var imagen = document.getElementById("foto_imagen_paciente");  
                  imagen.src = "data:image/jpeg;base64," + result.foto;
                }
              }
  
              var tipo_beneficiario_saludpol = 13;
              $('#tipo_beneficiario_saludpol').val(tipo_beneficiario_saludpol);
              
              getTipo_beneficiario($('#tipo_beneficiario_saludpol').val(), "descripcion_tipo_beneficiario_saludpol");  
                
              $('#padreForm').hide();
              $('#madreForm').hide();
              //validar_edad();
              //alert("Paciente es Civil");
              var valida_civil= $('#descripcion_tipo_beneficiario_saludpol').val();
              if(valida_civil=='CIVIL'){
  
                  $("#seccion_datos_titular").hide(); 
                  $('#id_prioridad').empty();  
                  $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
                  $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>"); 
                  
                  $('#id_prioridad').val("").change(); 
  
                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                  html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 1200,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
              }else{ 
   
                if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                  $("#seccion_datos_titular").show(); 
                }
  
                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                  html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 1200,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
                click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                modalHistorial(dni_paciente);
              }
  
  
            }  else if ((tipodoc_beneficiario == "1" || tipodoc_beneficiario == "2" || tipodoc_beneficiario == "3" || tipodoc_beneficiario == "4") && result.nompaisdelafiliado != "") {    
              // -------------- SALUDPOL -----------------------------    
  
              console.log('---- SALUDPOL ----'); 
  
              $("#id_prioridad").val("");
              $('#tipo_registro').val('1');
  
              apepatafiliado=result.paterno;
              apematafiliado=result.materno;
              nomafiliado=result.nombres;
              if(apepatafiliado != undefined){
                apepatafiliado =result.paterno; 
              }
              if(apepatafiliado == null || apepatafiliado == ''){
                apepatafiliado =result.apepatafiliado; 
              }
  
              if(apematafiliado != undefined){
                apematafiliado =result.materno;
              }
              if(apematafiliado == null || apematafiliado == ''){
                apematafiliado =result.apematafiliado; 
              }
  
              if(nomafiliado != undefined){
                nomafiliado =result.nombres;
              }
              if(nomafiliado == null || nomafiliado == ''){
                nomafiliado =result.nomafiliado; 
              }
  
              paciente = apepatafiliado + ' ' + apematafiliado + ' ' + nomafiliado;
   
              $('#beneficiario').val(paciente);
              $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
              $('#tipo_doc_ident').val("DNI");
              
              nrodocafiliado=result.nrodocafiliado;
              if(result.nrodocafiliado == undefined){
                nrodocafiliado =result.dni;
              }
  
              $('#nro_doc_ident').val(nrodocafiliado);
              $('#nombre').val(nomafiliado);
              $('#paterno').val(apepatafiliado);
              $('#materno').val(apematafiliado);
              
              nomsexo=result.nomsexo;
              if(result.nomsexo == undefined){
                  nomsexo = 'F';
                if(result.sexo == 'MASCULINO'){
                  nomsexo = 'M';
                } 
              }
              $('#sexo').val(nomsexo);
              
              fecnacafiliado=result.fecnacafiliado; 
              if(result.fecnacafiliado == undefined || result.fecnacafiliado == null|| result.fecnacafiliado == ''){
                fecnacafiliado =result.fecha_nac;
              }
  
              $('#fecha_nac').val(fecnacafiliado);
              calcular_edad_paciente(fecnacafiliado);
  
              ////obtenemos la foto 
              var edad_pac= $('#edad_pac').val();
              if(edad_pac>=218){ 
                if(result.foto != undefined && result.foto!=null){  
                  $('#foto_paciente').val(result.foto);  
                  var imagen = document.getElementById("foto_imagen_paciente"); 
                  imagen.src = "data:image/jpeg;base64," + result.foto;
                } 
              }
   
  
              if(result.ciptitular!=null && result.ciptitular!=''){
                $('#ciptitular').val(result.ciptitular);
                $('#cip_titular').val(result.ciptitular);
              }
              if($('#cip_titular').val() == null || $('#cip_titular').val()  == ''){
                $('#ciptitular').val(result.cip);
                $('#cip_titular').val(result.cip);
              }
  
    
              if(result.id_tipo_beneficiario!=null && result.id_tipo_beneficiario!=''){
                $('#parentesco_paciente').val(result.id_tipo_beneficiario); 
                $('#tipo_beneficiario_saludpol').val(result.id_tipo_beneficiario);
                getTipo_beneficiario(result.id_tipo_beneficiario, "descripcion_tipo_beneficiario_saludpol");  
              }
  
              if(result.nrodoctitular!=null && result.nrodoctitular!=''){
                $('#nrodoctitular').val(result.nrodoctitular);
                $('#documento_titular').val(result.nrodoctitular); 
              }
    
              if(result.gradotitular!=null && result.gradotitular!=''){
                $('#grado').val(result.gradotitular);
                $('#grado_titular').val(result.gradotitular);
              }
  
  
              apepattitular=result.apepattitular;
              apemattitular=result.apemattitular;
              nomtitular=result.nomtitular;
              if(apepattitular != undefined){
                apepattitular =result.apepattitular; 
              }else{
                apepattitular ='';
              }
  
              if(apemattitular != undefined){
                apemattitular =result.apemattitular;
              }else{
                apemattitular ='';
              }
  
              if(nomtitular != undefined){
                nomtitular =result.nomtitular;
              }else{
                nomtitular ='';
              }
  
              titular = apepattitular + ' ' + apemattitular + ' ' + nomtitular;
              $('#descripcion_titular').val(titular); 
  
              if(result.situaciontitular!=null && result.situaciontitular!=''){
                $('#situaciontitular').val(result.situaciontitular);
              } 
  
              if(result.id_situacion!=null && result.id_situacion!=''){
                $('#situacionpaciente').val(result.id_situacion);
              } 
  
              if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                if($("#grado_titular").val()==null || $("#grado_titular").val()==''){
                  if ($("#situacionpaciente").val().indexOf('ALUMNO') !== -1) {
                    $('#grado_titular').val($("#situacionpaciente").val());
                  } 
                }
              }
              
              $('#domicilio').val(result.direccionDomicilio); 
              $('#codigo_ubig').val(result.codigo_ubig);
              $('#id_departamento').val(result.id_departamento);
              $('#id_provincia').val(result.id_provincia);
              $('#id_distrito').val(result.id_distrito); 
  
                //console.log(result.parentesco);
                //getTipo_beneficiarioDescrip(result.parentesco);   
                 
                //validar_edad();
   
                if(result.id_tipo_beneficiario=='13'){
  
                  $("#seccion_datos_titular").hide(); 
                  $('#id_prioridad').empty();  
                  $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
                  $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>"); 
                  $('#id_prioridad').append("<option value='4'>IV - PATOLOGÍA AGUDA COMÚN</option>");  
                  $('#id_prioridad').val("").change(); 
  
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 1200,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                  });
                }else{ 
  
                  if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                    $("#seccion_datos_titular").show(); 
                  }
  
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 1200,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                  });
                  click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                  modalHistorial(dni_paciente);
                }
   
            }  else {
                //alert("No se encontro al paciente");
                paciente_no_existe = true;
            }
                          validateGestacionPuerpera();
                          if (result.caducidad_seguro || result.caducidad) {
                            const caducidad = result.caducidad ? result.caducidad : result.caducidad_seguro;
                            alertVencimientoSeguro(caducidad);
                          }
  
          } else {
            Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                html: '<p class="descripcion-alert"><span><b>EL PACIENTE SE ENCUENTRA COMO </b><b style="color:red;">FALLECIDO</b><b>, VERIFIQUE EL NÚMERO DE DOCUMENTO INGRESADO.</b></span></p>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                showConfirmButton: true,
            });
          } 
        } else {
            //alert("No se encontro al paciente");
            paciente_no_existe = true;
        }

// ------------------    RENIEC NO ENCUENTRA A MENORES DE EDAD       ------------------ 

        if (paciente_no_existe == true) { 
          promptRegistrarPacienteManual();
        } else { // EXISTE PACIENTE

          //VALIDAMS QUE NO SE ENCUENTRE FALLECIDO
          if($('#situacionpaciente').val() == 'FALLECIDO'){

            Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                html: '<p class="descripcion-alert"><span><b>EL PACIENTE SE ENCUENTRA COMO </b><b style="color:red;">FALLECIDO</b><b>, VERIFIQUE EL NÚMERO DE DOCUMENTO INGRESADO.</b></span></p>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                preConfirm: function(){
                  limpiar_paciente();
                  limpiar_signos_vitales();
                  limpiar_datos_adicional();
                  $('#dni_beneficiario').val(''); 
                  $('#dni_beneficiario').focus(); 
              }
            });

          }else{
            llenar_manual_datos_paciente(false);
            
            var paterno = $("#paterno").val();
            var materno = $("#materno").val(); 
            var nombre = $("#nombre").val();
            var nombre_de_paciente=paterno+' '+materno+' '+nombre;

            

            //paciente registrado
            if (result.id != undefined) {
              $.ajax({
                url: "{{ route('Buscar_persona_triaje_ajax') }}",
                type: "GET",
                dataType: "JSON",
                data: {
                  id_asegurado: result.id
                },
                success: function(result) {
                  
                  //alerta --- diferencia de horas x 24
                  if (result!=null && result.dif_horas <= 24) {
                    Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert">EL PACIENTE TIENE REGISTRO DE TRIAJE EN LA ÚLTIMAS 24 HORAS:<span>El '+ result.fecha_ingreso +'</span> A LAS <span>'+ result.hora_ingreso +'</span> EN EL TÓPICO <span>'+ result.consultorio +'</span>.<br><br>¿DESEA CONTINUAR?</p>',
                    allowOutsideClick: false, 
                    showCancelButton: true,
                    confirmButtonText: '<b>CONTINUAR EL REGISTRO</b>',
                    confirmButtonColor: '#3085d6',
                    cancelButtonText: '<b>CANCELAR EL REGISTRO</b>',
                    cancelButtonColor: 'red',
                    }).then((result) => {
                      if (result.isConfirmed) {
                        click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        
                        limpiar_paciente();
                        limpiar_signos_vitales();
                        limpiar_datos_adicional();
                        $('#dni_beneficiario').val(''); 
                        $('#dni_beneficiario').focus(); 

                      }
                    });
                  }
                  else{
                    if (result!=null && result.dia_estancia <= 30) {
                      Swal.fire({
                      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                      html: '<p class="descripcion-alert">EL PACIENTE TIENE REGISTRO DE TRIAJE EN LOS ÚLTIMOS 30 DÍAS:<span>El '+ result.fecha_ingreso +' EN EL TÓPICO <span>'+ result.consultorio +'</span>.<br><br>¿DESEA CONTINUAR?</p>',
                      allowOutsideClick: false, 
                      showCancelButton: true,
                      confirmButtonText: '<b>CONTINUAR EL REGISTRO</b>',
                      confirmButtonColor: '#3085d6',
                      cancelButtonText: '<b>CANCELAR EL REGISTRO</b>',
                      cancelButtonColor: 'red',
                      }).then((result) => {
                        if (result.isConfirmed) {
                          click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                          
                          limpiar_paciente();
                          limpiar_signos_vitales();
                          limpiar_datos_adicional();
                          $('#dni_beneficiario').val(''); 
                          $('#dni_beneficiario').focus(); 

                        }
                      });
                    }
                  }
                }
              });
            }

          }

        }
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false); 
      },
      error: function(xhr, status, error) {
        // Lógica para manejar el error
        //console.log(xhr.responseText);
        let existeLocal=false;
        var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
        var dni_paciente = $("#dni_beneficiario").val();
        //SI ALGUN SERVICIO FALLA, DESDE AQUI BUSCARA DATOS SOLO DEL LOCAL SI EXISTIERA
        $.ajax({
            url: "{{ route('obtener_datos_persona_ajax2') }}",
            type: "GET",
            dataType: "JSON",
            data: {
              dni: dni_paciente,
              tipo_documento: tipodoc_beneficiario
            },
            success: function (result) {
              console.log(result);
              if (result !== null) {
                if (result.situacion_paciente != 'FALLECIDO') {
                  $("#id_prioridad").val("");
                  $('#tipo_registro').val('1');
                  apepatafiliado=result.paterno;
                  apematafiliado=result.materno;
                  nomafiliado=result.nombre;
                  if(apepatafiliado != undefined) {
                    apepatafiliado =result.paterno; 
                  }
                  if(apematafiliado != undefined) {
                    apematafiliado =result.materno;
                  }
                  if(nomafiliado != undefined) {
                    nomafiliado =result.nombre;
                  }
                  paciente = apepatafiliado + ' ' + apematafiliado + ' ' + nomafiliado;
                  $('#beneficiario').val(paciente);
                  $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
                  $('#tipo_doc_ident').val(result.tipo_doc_ident);
                  $('#nro_doc_ident').val(result.nro_doc_ident);
                  $('#nombre').val(result.nombre);
                  $('#paterno').val(result.paterno);
                  $('#materno').val(result.materno);
                  $('#sexo').val(result.sexo);
                  $('#fecha_nac').val(result.fecha_nac);
                  $('#domicilio').val(result.domicilio);
                  $('#codigo_ubig').val(result.codigo_ubig);
                  $('#id_departamento').val(result.id_departamento);
                  $('#id_provincia').val(result.id_provincia);
                  $('#id_distrito').val(result.id_distrito); 
                  calcular_edad_paciente(result.fecha_nac);
                  ////obtenemos la foto 
                  var edad_pac= $('#edad_pac').val();
                  if(edad_pac>=218) {  
                    if(result.foto != undefined && result.foto!=null) {  
                      $('#foto_paciente').val(result.foto);  
                      var imagen = document.getElementById("foto_imagen_paciente");  
                      imagen.src = "data:image/jpeg;base64," + result.foto;
                    }
                  }
                  id_tipo_beneficiario=result.id_tipo_beneficiario;
                  if(id_tipo_beneficiario==null){
                    id_tipo_beneficiario=result.id_tipo_beneficiario;
                  }
                  console.log('---- ASEGURADOS TABLE22222 ----'+id_tipo_beneficiario);  
                  $('#tipo_beneficiario_saludpol').val('');
                  if(id_tipo_beneficiario==null){
                    $('#tipo_beneficiario_saludpol').val('13');
                  }else{
                    $('#tipo_beneficiario_saludpol').val(id_tipo_beneficiario);
                  } 
                  getTipo_beneficiario($('#tipo_beneficiario_saludpol').val(), "descripcion_tipo_beneficiario_saludpol"); 
  
                  if(result.id_tipo_beneficiario=='8'||result.id_tipo_beneficiario=='TITULAR') {   //titular
                    if(result.grado!=null && result.grado!=''){
                      $('#grado').val(result.grado);
                      $('#grado_titular').val(result.grado);
                    }
  
                    if(result.situacion!=null && result.situacion!=''){
                      $('#situaciontitular').val(result.situacion);
                    }
                    
                    if(result.id_situacion!=null && result.id_situacion!=''){
                      $('#situacionpaciente').val(result.id_situacion);
                    } 
                  } else {  
                    if(result.gradotitular!=null && result.gradotitular!=''){
                      $('#grado').val(result.gradotitular);
                      $('#grado_titular').val(result.gradotitular);
                    }
                    
                    if(result.id_situacion!=null && result.id_situacion!=''){
                      $('#situacionpaciente').val(result.id_situacion);
                    } 
                  }
                  if(result.situaciontitular!=null && result.situaciontitular!=''){
                    $('#situaciontitular').val(result.situaciontitular);
                  } 
                  if(result.ciptitular!=null && result.ciptitular!=''){
                    $('#ciptitular').val(result.ciptitular);
                    $('#cip_titular').val(result.ciptitular);
                  }
                  if(result.nrodoctitular!=null && result.nrodoctitular!=''){
                    $('#nrodoctitular').val(result.nrodoctitular);
                    $('#documento_titular').val(result.nrodoctitular); 
                  }
                  if(result.unidad_pnptitular!=null && result.unidad_pnptitular!=''){
                    $('#unidad_pnptitular').val(result.unidad_pnptitular);
                  }
                  if(result.id_tipo_beneficiario!=null && result.id_tipo_beneficiario!=''){
                    $('#parentesco_paciente').val(result.id_tipo_beneficiario); 
                  }
                  apepattitular=result.apepattitular;
                  apemattitular=result.apemattitular;
                  nomtitular=result.nomtitular;
                  if(apepattitular != undefined){
                    apepattitular =result.apepattitular; 
                  }else{
                    apepattitular ='';
                  }
                  if(apemattitular != undefined){
                    apemattitular =result.apemattitular;
                  }else{
                    apemattitular ='';
                  }
                  if(nomtitular != undefined){
                    nomtitular =result.nomtitular;
                  }else{
                    nomtitular ='';
                  }
                  titular = apepattitular + ' ' + apemattitular + ' ' + nomtitular;
                  $('#descripcion_titular').val(titular); 
                  if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                    if($("#grado_titular").val()==null || $("#grado_titular").val()==''){
                      if ($("#situacionpaciente").val().indexOf('ALUMNO') !== -1) {
                        $('#grado_titular').val($("#situacionpaciente").val());
                      } 
                    }
                  }
                  
                  var valida_civil= $('#descripcion_tipo_beneficiario_saludpol').val();
                  if(valida_civil=='CIVIL') {
                    $("#seccion_datos_titular").hide(); 
  
                    $('#id_prioridad').empty();  
                    $('#id_prioridad').append("<option value='' selected>--SELECCIONAR--</option>");
                    $('#id_prioridad').append("<option value='1'>I - GRAVEDAD SÚBITA EXTREMA</option>"); 
                    
                    $('#id_prioridad').val("").change(); 
                    
                    if(result.tipo_seguro_estadows != undefined){
                      if(result.tipo_seguro_estadows == 'El asegurado no esta activo'){
                        Swal.fire({
                          icon: 'warning',
                          title: 'ALERTA',
                          html: `
                              <div style="font-size:16px; line-height:1.6; text-align:center;">
                                  El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                                  <br>
                                  Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                                  <br>
                                  Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                              </div>
                          `,
                          confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                          confirmButtonColor: '#3085d6',
                          width: '38em',
                          customClass: {
                            popup: 'swal-noafiliado',
                            title: 'swal-title',
                            htmlContainer: 'swal-content'
                          }
                        });
                      }else if(result.tipo_seguro_estadows == 'No se encuentra afiliado'){
                        Swal.fire({
                          icon: 'warning',
                          title: 'ALERTA',
                          html: `
                              <div style="font-size:16px; line-height:1.6; text-align:center;">
                                  <strong>NO SE ENCUENTRA REGISTRO</strong> de paciente en <strong>SALUDPOL</strong>.
                                  <br>
                                  Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación.
                                  <br>
                                  Será atendido en condición de <strong>CIVIL</strong> por la Ley de Emergencia de Salud en el Perú (Ley N.º 27604).
                              </div>
                          `,
                          confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                          confirmButtonColor: '#3085d6',
                          width: '38em',
                          customClass: {
                            popup: 'swal-noafiliadosp',
                            title: 'swal-title',
                            htmlContainer: 'swal-content'
                          }
                        });
                      }else{
                        Swal.fire({
                          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                          html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                          allowOutsideClick: false,
                          timer: 1200,
                          timerProgressBar: true,
                          showConfirmButton: false,
                          allowOutsideClick: false,
                        });
                      }
                    }else{
                      Swal.fire({
                        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                        html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL PACIENTE SERÁ ATENDIDO COMO CIVIL.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 1200,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      });
                    }
  
                  } else { 
  
                    if($("#descripcion_titular").val() != null && $("#descripcion_titular").val() != '' ){
                      $("#seccion_datos_titular").show(); 
                    }
  
                    Swal.fire({
                      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                      html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                      allowOutsideClick: false,
                      timer: 1200,
                      timerProgressBar: true,
                      showConfirmButton: false,
                      allowOutsideClick: false,
                    });
                    click_alerta_atenciones(dni_paciente, nombre_de_paciente);
                    modalHistorial(dni_paciente);
                  }
                  if (result.caducidad_seguro || result.caducidad) {
                    const caducidad = result.caducidad ? result.caducidad : result.caducidad_seguro;
                    alertVencimientoSeguro(caducidad);
                  }
                } else {
                  Swal.fire({
                          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                          html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><b style="color:red;">EL SEGURO DEL PACIENTE NO SE ENCUENTRA ACTIVO POR SU ESTADO DE FALLECIDO.</b><br></span></p>', 
                          allowOutsideClick: false,
                          timer: null,
                          timerProgressBar: true,
                          showConfirmButton: true,
                          allowOutsideClick: false,
                        });
                }
              } else {
                try {
                  var errorMessage = JSON.parse(xhr.responseText);
                } catch (e) {
                  console.log('No existe información de la persona en la base de datos local');
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                    html: '<p class="descripcion-alert">No existe datos de la persona al consultar N° Documento del paciente.<span> ¿Desea registrar manualmente?</span></p>',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'SI',
                    allowOutsideClick: false,
                    }).then((result) => {
                      if (result.isConfirmed) {
                        let combo = document.getElementById("tipodoc_beneficiario_bus");
                        let tipo_doc_ident = combo.options[combo.selectedIndex].text;
                        document.getElementById("tipo_doc_ident").value = tipo_doc_ident;
                        let dni_beneficiario = $("#dni_beneficiario").val();
                        $("#nro_doc_ident").val(dni_beneficiario);
                        llenar_manual_datos_paciente(true);
                        modalHistorial(dni_beneficiario);
                        var tipo_beneficiario_saludpol = 13;
                        $('#tipo_beneficiario_saludpol').val(tipo_beneficiario_saludpol);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        return "0";
                      }
                    });
                }
              }
            },
            error: function (xhr, status, error) {
              try {
                  var errorMessage = JSON.parse(xhr.responseText);
                } catch (e) {
                  console.log('Error de web services de reniec');
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                    html: '<p class="descripcion-alert">Ha ocurrido un error al consultar el N° Documento del paciente.<span> ¿Desea registrar manualmente?</span></p>',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'SI',
                    allowOutsideClick: false,
                    }).then((result) => {
                      if (result.isConfirmed) {
                        let combo = document.getElementById("tipodoc_beneficiario_bus");
                        let tipo_doc_ident = combo.options[combo.selectedIndex].text;
                        document.getElementById("tipo_doc_ident").value = tipo_doc_ident;
                        let dni_beneficiario = $("#dni_beneficiario").val();
                        $("#nro_doc_ident").val(dni_beneficiario);
                        llenar_manual_datos_paciente(true);
                        modalHistorial(dni_beneficiario);
                        var tipo_beneficiario_saludpol = 13;
                        $('#tipo_beneficiario_saludpol').val(tipo_beneficiario_saludpol);
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        return "0";
                      }
                    });
                }
            }
        });
      },
      complete: function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
      }
    });
  }


  function llenar_manual_datos_paciente(valor) {
    if (valor == true) {
      $('#tipo_registro').val('0');
      $("#paterno").val("").attr("readonly", false);
      $("#materno").val("").attr("readonly", false);
      $("#nombre").val("").attr("readonly", false);
      $("#fecha_nac2").val("").attr("readonly", false);
      $("#edad_paciente2").val("").attr("readonly", false);
      $('#divLabelEdadPaciente').show();
      $("#edad_paciente").show();
      $("#edad_paciente").val(""); 
      $("#sexo").hide();

      $("#selectSexo").val("");
      $("#selectSexo").show();
      $("#seleccionaTipoBeneficiario").show(); 
      $("#seleccionaTipoBeneficiario").val("");

      $("#fecha_nac").hide();
      $("#fecha_nac2").val("");
      //$('#edad2').val("");
      $("#fecha_nacimiento_editable").show();
      $("#edad_nacimiento_editable").show();

      document.getElementById("paterno").focus();
    } else {
      $("#nro_doc_ident").attr("readonly", true);
      $("#paterno").attr("readonly", true);
      $("#materno").attr("readonly", true);
      $("#nombre").attr("readonly", true);
      $('#divLabelEdadPaciente').show(); 
      $("#edad_paciente").hide();
      $("#fecha_nac").attr("readonly", true);
      $("#sexo").show();
      $("#selectSexo").hide();
      $("#seleccionaTipoBeneficiario").hide(); 
      $("#fecha_nac").show();
      $("#fecha_nacimiento_editable").hide();
      $("#edad_nacimiento_editable").hide(); 
      document.getElementById("dni_beneficiario").focus();
    }
  }

  function compruebaIMC() {
    
    var peso = document.frmTriajeEmergencia.peso.value;
    if(peso==''){
      peso=0;
    }
    var talla = document.frmTriajeEmergencia.talla.value;
    if(talla==''){
      talla=0;
    }
    var temperatura = document.frmTriajeEmergencia.temperatura.value;
    var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
    var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
    
    if (beneficiario == "" && nro_doc_ident == "") {
      Swal.fire({
      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
      html: '<p class="descripcion-alert"><span>Debes buscar un paciente</span></p>',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK',
      allowOutsideClick: false,
      preConfirm: function(){ 
        limpiar_signos_vitales();
        limpiar_datos_adicional();
        $('#dni_beneficiario').focus();
      }
      });
    }


    if (talla != "") {
      //PESO POR ALTURA
      var sexo = document.frmTriajeEmergencia.sexo.value; //MASCULINO , FEMENINO
      //edad
      var tipo_registro = document.frmTriajeEmergencia.tipo_registro.value; // 0: Registro manual, 1: Registro automatico
      var fecha_nac = document.frmTriajeEmergencia.fecha_nac.value; //12/05/1992
      var fecha_nac2 = document.frmTriajeEmergencia.fecha_nac2.value;
      //var edad2 = document.frmTriajeEmergencia.edad2.value;
      var fechax = (tipo_registro == "1") ? gfecha(fecha_nac) : gfecha(fecha_nac2);

      var datos_edad = calcularEdad(fechax);
      var edad_anos = 0;

      if (datos_edad != "") {
        var arregloedad = datos_edad.split("|");
        if (arregloedad.length > 0) {
          var edad_anos = arregloedad[0];
        }
      }
      var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4
      if (sexo == "M") {
        pesoIdeal = pesoIdeal * 0.9;
      }
      document.frmTriajeEmergencia.peso_x_altura.value = pesoIdeal.toFixed(2);
    } else {
      document.frmTriajeEmergencia.peso_x_altura.value = "";
    }

    
    if (peso != "" && talla != "") {
      var talla_en_metros = talla * 0.01;
      //IMC
      var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
      document.frmTriajeEmergencia.imc.value = imcx;

      if (imcx < 18.5) {
        $('#imc').addClass('borde-imc-bajopeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Bajo de Peso' + "</strong>");

      } else if (imcx >= 18.5 && imcx< 24.9) {
        $('#imc').addClass('borde-imc-normal');
        $('#imc-aceptado').html("<strong>" + 'Paciente Peso Normal' + "</strong>");
        //NORMAL
      } else if (imcx >= 25.0 && imcx< 29.9){
        $('#imc').addClass('borde-imc-sobrepeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Sobrepeso' + "</strong>");
        //SOBREPESO
      } else if (imcx >= 30.0 && imcx< 34.9){
        $('#imc').addClass('borde-imc-obesidad1');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad I' + "</strong>");
        //OBESIDAD I
      } else if (imcx >= 35.0 && imcx< 39.9){
        $('#imc').addClass('borde-imc-obesidad2');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad II' + "</strong>");
        // OBESIDAD II
      } else if (imcx >= 40.0){
        $('#imc').addClass('borde-imc-obesidad3');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad III' + "</strong>");
        // OBESIDAD III
      }

    } else {
      document.frmTriajeEmergencia.imc.value = '';
    }
  }

  //------ ALERT DIALOG CONFIRM TRIAJE SUBMIT --------
  function submitFrm() {

    
    var id_forma_ingreso = document.frmTriajeEmergencia.id_forma_ingreso.value;
    if (id_forma_ingreso == "") { 
      $('#id_forma_ingreso').focus();  
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un TIPO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
      }); 
      $('#id_forma_ingreso').focus();
      $('#id_forma_ingreso').next('.select2-container').addClass('borde-alerta');
      return;
    }else{
      $('#id_forma_ingreso').next('.select2-container').removeClass('borde-alerta');
    }


    var id_estado_ingreso = document.frmTriajeEmergencia.id_estado_ingreso.value;
    if (id_estado_ingreso == "") {    
      $('#id_estado_ingreso').focus();  
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un ESTADO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
      });
      $('#id_estado_ingreso').focus();
      $('#id_estado_ingreso').next('.select2-container').addClass('borde-alerta');
      return;
    }else{
      $('#id_estado_ingreso').next('.select2-container').removeClass('borde-alerta');
    }


    var id_motivo_ingreso = document.frmTriajeEmergencia.id_motivo_ingreso.value;
    if (id_motivo_ingreso == "") { 
      $('#id_motivo_ingreso').focus();  
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un MOTIVO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
      });
      $('#id_motivo_ingreso').focus();
      $('#id_motivo_ingreso').next('.select2-container').addClass('borde-alerta');
      return;
    }else{
      $('#id_motivo_ingreso').next('.select2-container').removeClass('borde-alerta');
    }


    //AJUSTES EN GESTANTES  AJUSTES EN GESTANTES AJUSTES EN GESTANTES AJUSTES EN GESTANTES 
    //validamos la embarazada si se marco el check
    // var es_gestante=document.frmTriajeEmergencia.es_gestante.value;
    // if(es_gestante == 1){ 
    //   var tamanio_cintura = document.frmTriajeEmergencia.tamanio_cintura.value;
    //   if (tamanio_cintura == "") { 
    //     Swal.fire({
    //       title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
    //       html: '<p class="descripcion-alert">Recuerde que tiene que ingresar el TAMAÑO DE CINTURA.</p>',
    //       confirmButtonColor: '#3085d6',
    //       confirmButtonText: 'OK',
    //       allowOutsideClick: false,
    //     });
    //     $('#tamanio_cintura').focus(); 
    //     $('#tamanio_cintura').addClass('borde-alerta');
    //     return;
    //   }else{ 
        
    //     if (!Validar.enteroEnRango(tamanio_cintura, 10, 45)) {
    //       Swal.fire({
    //         title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
    //         html: '<p class="descripcion-alert">Recuerde que tiene que ingresar un ALTURA UTERINA CORRECTA, verifique los rangos permitidos.</p>',
    //         confirmButtonColor: '#3085d6',
    //         confirmButtonText: 'OK',
    //         allowOutsideClick: false,
    //       }); 
    //       $('#tamanio_cintura').addClass('borde-alerta');
    //       $('#tamanio_cintura').focus();  
    //       return;
    //     }else{ 
    //       $('#tamanio_cintura').removeClass('borde-temperatura-valida');
    //       $('#tamanio_cintura-aceptado').html("");
    //       $('#tamanio_cintura-aceptado strong').removeClass('borde-temperatura-valida');
    //       $('#tamanio_cintura').removeClass('borde-alerta');
    //     }
        
    //   }

    //   var frecuencia_cardiaca_fetal = document.frmTriajeEmergencia.frecuencia_cardiaca_fetal.value;
    //   if (frecuencia_cardiaca_fetal == "") { 
    //     Swal.fire({
    //       title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
    //       html: '<p class="descripcion-alert">Recuerde que tiene que ingresar la FRECUENCIA CARDIACA FETAL.</p>',
    //       confirmButtonColor: '#3085d6',
    //       confirmButtonText: 'OK',
    //       allowOutsideClick: false,
    //     }); 
    //     $('#frecuencia_cardiaca_fetal').addClass('borde-alerta');
    //     $('#frecuencia_cardiaca_fetal').focus(); 
    //     return;
    //   }else{
        
    //     if (!Validar.enteroEnRango(frecuencia_cardiaca_fetal, 0, 170)) {
    //       Swal.fire({
    //         title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
    //         html: '<p class="descripcion-alert">Recuerde que tiene que ingresar la FRECUENCIA CARDIACA FETAL CORRECTA.</p>',
    //         confirmButtonColor: '#3085d6',
    //         confirmButtonText: 'OK',
    //         allowOutsideClick: false,
    //       }); 
    //       $('#frecuencia_cardiaca_fetal').addClass('borde-alerta');
    //       $('#frecuencia_cardiaca_fetal').focus();  
    //       return;
    //     }else{
    //       $('#frecuencia_cardiaca_fetal').removeClass('borde-temperatura-hipertermia');
    //       $('#frecuencia_cardiaca_fetal-aceptado').html("");
    //       $('#frecuencia_cardiaca_fetal-aceptado strong').removeClass('borde-temperatura-valida');
    //       $('#frecuencia_cardiaca_fetal').removeClass('borde-alerta');
    //     }
    //   }
    // }
    
    var id_prioridad = document.frmTriajeEmergencia.id_prioridad.value;
    if (id_prioridad == "") { 
      $('#id_prioridad').focus();
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar una PRIORIDAD VÁLIDA.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
      });
      $('#id_prioridad').focus();
      $('#id_prioridad').next('.select2-container').addClass('borde-alerta');
      return;
    }else{
      $('#id_prioridad').next('.select2-container').removeClass('borde-alerta');
    }
    
    var id_topico = document.frmTriajeEmergencia.id_topico.value; 
    if(id_prioridad!='4'){
      if (id_topico == "") { 
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un ÁREA Y/O SERVICIO VÁLIDO.</p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
        });
        $('#id_topico').focus();
        $('#id_topico').next('.select2-container').addClass('borde-alerta');
        return;
      }else{
        $('#id_topico').next('.select2-container').removeClass('borde-alerta');
      }
    }


    // OBS0001
    // validar la temperatura
    $('#temperatura').removeClass('borde-alerta');
    const temperatura = $('#temperatura').val();
    if (temperatura && !decimalRegex.test(temperatura)){
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Debe registrar una temperatura válida.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function() {
          $('#temperatura').focus();
          $('#temperatura').addClass('borde-alerta');
        }
      });
      return;
    };

    // validar la talla
    $('#talla').removeClass('borde-alerta');
    const talla = $('#talla').val();
    if (talla && !decimalRegex.test(talla)){
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Debe registrar una talla válida.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function() {
          $('#talla').focus();
          $('#talla').addClass('borde-alerta');
        }
      });
      return;
    };

    // validar el peso
    $('#peso').removeClass('borde-alerta');
    const peso = $('#peso').val();
    if (peso && !decimalRegex.test(peso)){
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Debe registrar un peso válido.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function() {
          $('#peso').focus();
          $('#peso').addClass('borde-alerta');
        }
      });
      return;
    };



    $("#ultimaMestruacion").removeProp('disabled');
    $("#parto_probable").removeProp('disabled');
    $("#control_prenatal").removeProp('disabled');
    $("#duda").removeProp('disabled');

  

    // Validar la section de Gestacion - BUG
    const esta_gestando = $('#esta_gestando').attr("aria-pressed");
    $("#esta_gestando").remove();
    $("#frmTriajeEmergencia").append('<input type="hidden" name="esta_gestando" id="esta_gestando">');
    $("#esta_gestando").val(esta_gestando);
    if (esta_gestando === "true") {
      const idGestacion = $("#id_gestacion").val();
      $('#ultimaMestruacion').removeClass('borde-alerta');
      if ($("#ultimaMestruacion").val() === "") {
        if (idGestacion && idGestacion !== '' && idGestacion !== null) {
          $("#ultimaMestruacion").prop('disabled', true);
        }
        Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert">Debe ingresar la fecha de última menstruación.</p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function() {
              $('#ultimaMestruacion').focus();
              $('#ultimaMestruacion').addClass('borde-alerta');
            }
        });
        return;
      }
      if ($("#parto_probable").val() === "") {
        if (idGestacion && idGestacion !== '' && idGestacion !== null) {
          $("#parto_probable").prop('disabled', true);
        }
      }
      $('#semana_gesta').removeClass('borde-alerta');
      if ($("#semana_gesta").val() === "") {
        if (idGestacion && idGestacion !== '' && idGestacion !== null) {
          $("#semana_gesta").prop('disabled', true);
        }
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Debe ingresar o calcular sus semanas de gestacion.</p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function() {
            $('#semana_gesta').focus();
            $('#semana_gesta').addClass('borde-alerta');
          }
        });
        return;
      }
      $('#control_prenatal').removeClass('borde-alerta');
      if ($("#control_prenatal").val() === "") {
        if (idGestacion && idGestacion !== '' && idGestacion !== null) {
          $("#control_prenatal").prop('disabled', true);
        }
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Debe ingresar el numero de control prenatales</p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function() {
            $('#control_prenatal').focus();
            $('#control_prenatal').addClass('borde-alerta');
          }
        });
        return;
      }
    }

    // Validar la section de Puerperia
    const addPuerpera = $('#addPuerpera').attr("aria-pressed");
    if (addPuerpera == "true") {
        $('#fecha_parto').removeClass('borde-alerta');
        let fecha_parto = $('#fecha_parto').val();
        if (fecha_parto == ""){
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert">Debe registrar la fecha de parto.</p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function() {
              $('#fecha_parto').focus();
              $('#fecha_parto').addClass('borde-alerta');
            }
          });
          return;
        };

        $('#hora_parto').removeClass('borde-alerta');
        let hora_parto = $('#hora_parto').val();
        if (hora_parto == ""){
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert">Debe registrar la hora de parto.</p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function() {
              $('#hora_parto').focus();
              $('#hora_parto').addClass('borde-alerta');
            }
          });
          return;
        }; 

        $('#tipo_parto').removeClass('borde-alerta');
        let tipo_parto = $('#tipo_parto').val();
        if (tipo_parto === "" || tipo_parto === "0"){
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert">Debe registrar el tipo de parto.</p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function() {
              $('#tipo_parto').focus();
              $('#tipo_parto').addClass('borde-alerta');
            }
          });
          return;
        };

        $('#complicacion_parto').removeClass('borde-alerta');
        let complicacion_parto = $('#complicacion_parto').val();
        if (complicacion_parto === "" || complicacion_parto === "0"){
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert">Debe registrar la complicación de parto.</p>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            preConfirm: function() {
              $('#complicacion_parto').focus();
              $('#complicacion_parto').addClass('borde-alerta');
            }
          });
          return;
        };
    }

    $("#esta_en_puerperio").remove()
    $("#frmTriajeEmergencia").append('<input type="hidden" name="esta_en_puerperio" id="esta_en_puerperio">');
    $("#esta_en_puerperio").val(addPuerpera);

    var valida_civil= $('#descripcion_tipo_beneficiario_saludpol').val();  

    save_triaje_store();


  }

  function save_triaje_store(){
    
    Swal.fire({
      title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
      html: '<p class="descripcion-alert"><b>¿Seguro de Guardar el Triaje de Emergencia?</b></span></p>',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'SI',
      allowOutsideClick: false,
    }).then((result) => {
          if (result.isConfirmed) { 
            var valida_civil= $('#descripcion_tipo_beneficiario_saludpol').val(); 
            $("#frmTriajeEmergencia").submit();
            
            limpiar_paciente();
            limpiar_signos_vitales();
            limpiar_datos_adicional();
            $('#dni_beneficiario').val(''); 
            $('#dni_beneficiario').focus(); 

            $('#medico_car').val(""); 
            $('#hora_cita').val("");
            $('#funcion_cita').val("");
                          
            $('#id_medico').val("");
            $('#fecha_cita').val("");
            $('#id_turno').val("");


          } else if (result.dismiss === Swal.DismissReason.cancel) {
            $('#btnReg').prop('disabled', false);
          }
    });  
  }

  function maxlength_doc_usu() {
    $('#dni_beneficiario').val('');
    limpiar_paciente();
    limpiar_signos_vitales();
    limpiar_datos_adicional();
    //llenar texto tipodocumento
    var combo = document.getElementById("tipodoc_beneficiario_bus");
    var tipo_doc_ident = combo.options[combo.selectedIndex].text;
    document.getElementById("tipo_doc_ident").value = tipo_doc_ident;
    document.getElementById("id_tipo_doc_ident").value = combo.value;

    if ($("#tipodoc_beneficiario_bus").val() == "1") {

      $("#dni_beneficiario").attr('maxlength', '8');
      $("#nro_doc_ident").attr('maxlength', '8');

    } else {
      $("#dni_beneficiario").attr('maxlength', '12');
      $("#nro_doc_ident").attr('maxlength', '12');
    }

    $('#dni_beneficiario').val('');
        if ($("#tipodoc_beneficiario_bus").val() == "3") {
          $("#dni_beneficiario").attr('onKeyPress', '');
        } else {
          $("#dni_beneficiario").attr('onKeyPress', 'return soloNumeros(event)');
        }
    document.getElementById("dni_beneficiario").focus();
  }
 
  $('#guardarTriaje').click(function() { 
      guardarTriaje(); 
  });

  function guardarCita(id_medico, fecha_cita, id_turno) { 
    fn_save_cita(id_medico, fecha_cita, id_turno); 
  }
                                            //$('#tipo_registro').val('1');  // + 1: Registro Automatico
  function guardarTriaje() { 
    var id_tipo_doc_ident           = document.frmTriajeEmergencia.id_tipo_doc_ident.value;  //---
    var dni_beneficiario            = document.frmTriajeEmergencia.dni_beneficiario.value;
    var beneficiario                = document.frmTriajeEmergencia.beneficiario.value;
    var nro_doc_ident               = document.frmTriajeEmergencia.nro_doc_ident.value;
    var paterno                     = document.frmTriajeEmergencia.paterno.value;
    var materno                     = document.frmTriajeEmergencia.materno.value;
    var nombre                      = document.frmTriajeEmergencia.nombre.value;
    var sexo                        = document.frmTriajeEmergencia.sexo.value;
    var fecha_nac                   = document.frmTriajeEmergencia.fecha_nac.value;
    var fecha_nac2                  = document.frmTriajeEmergencia.fecha_nac2.value;
    //var edad2                       = document.frmTriajeEmergencia.edad2.value;
    var tipo_registro               = document.frmTriajeEmergencia.tipo_registro.value; // 0: manual - 1: automatico
    var selectSexo                  = document.frmTriajeEmergencia.selectSexo.value;
    var fechax                      = (tipo_registro == "1") ? fecha_nac.split("/") : fecha_nac2.split("/");
    var cant_array_fecha            = fechax.length;
    var id_prioridad                = document.frmTriajeEmergencia.id_prioridad.value;
    var id_topico                   = document.frmTriajeEmergencia.id_topico.value;
 

    var prioridad_4 = $('#id_prioridad').val();
    
    if (prioridad_4 == "4") { 
      document.frmTriajeEmergencia.prioridadSeleccionada.value = 4;
      document.frmTriajeEmergencia.topicoSeleccionado.value =document.frmTriajeEmergencia.id_topico.value;
      document.frmTriajeEmergencia.observacionSeleccionado.value= document.frmTriajeEmergencia.observacion.value;

    } else if( prioridad_4 == "1" || prioridad_4 == "2" || prioridad_4 == "3"){
      document.frmTriajeEmergencia.prioridadSeleccionada.value = document.frmTriajeEmergencia.id_prioridad.value;
      document.frmTriajeEmergencia.topicoSeleccionado.value = document.frmTriajeEmergencia.id_topico.value;
      document.frmTriajeEmergencia.observacionSeleccionado.value= document.frmTriajeEmergencia.observacion.value;
    }


 

    var  menor_edad   = document.frmTriajeEmergencia.menor_edad.value;
    var  tipo_beneficiario_saludpol = document.frmTriajeEmergencia.tipo_beneficiario_saludpol.value;


    if ($('#paterno').attr('readonly') == 'readonly') {
      if (dni_beneficiario == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta buscar un paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#dni_beneficiario').focus();
        }
        });
      } else if (beneficiario == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta buscar un paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#dni_beneficiario').focus();
        }
        });
      }
    } else {
      //alert("false");
      if (nro_doc_ident == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta ingresar el Documento del paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#nro_doc_ident').focus();
        }
        });
      } else if (paterno == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta ingresar el apellido paterno del paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#paterno').focus();
        }
        });
      } else if (materno == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta ingresar el apellido materno del paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#materno').focus();
        }
        });
      } else if (nombre == "") {
        Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>Falta ingresar el/los nombres del paciente</span></p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        preConfirm: function(){
          $('#nombre').focus();
        }
        });
      } else if (tipo_registro == "0") {
        if (selectSexo == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Falta seleccionar el sexo del paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          preConfirm: function(){
            $('#selectSexo').focus();
          }
          });
        }
      }

      if (tipo_registro == "1") {
        if (fecha_nac == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Falta ingresar la fecha de nacimiento del paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
        allowOutsideClick: false,
          preConfirm: function(){
            $('#fecha_nac').focus();
          }
          });
        } else if (cant_array_fecha != 3 || fecha_nac.length != 10) {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>La fecha de nacimiento NO tiene un formato válido</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
        allowOutsideClick: false,
          preConfirm: function(){
            $('#fecha_nac').focus();
          }
          });
        }
      } else if (tipo_registro == "0") {
        if (fecha_nac2 == "") {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>Falta ingresar la fecha de nacimiento del paciente</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
        allowOutsideClick: false,
          preConfirm: function(){
            $('#fecha_nac2').focus();
          }
          });
        } else if (cant_array_fecha != 3 || fecha_nac2.length != 10) {
          Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
          html: '<p class="descripcion-alert"><span>La fecha de nacimiento NO tiene un formato válido</span></p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
        allowOutsideClick: false,
          preConfirm: function(){
            $('#fecha_nac2').focus();
          }
          });
        }
      }
    }
 
    // Validar información de signos vitales
    // ---------------------- TEMPERATURA --------------------------------
   // OBS0001
    var value = '';

    value = $('#temperatura').val();  //!      
    if (!decimalRegex.test(value)) {
      $('#temperatura').addClass('borde-temperatura-hipertermia');
      $('#texto-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
      $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    } else {                                
      if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 35.0, 40.0)) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
      }
    }

    

    // ---------------------- TALLA --------------------------------
    value = $('#talla').val();
    if (!decimalRegex.test(value)) {
      $('#talla').addClass('borde-temperatura-hipertermia');
      $('#talla-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
      $('#talla-aceptado strong').addClass('borde-temperatura-valida');
    } else {
      if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 30, 200)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'La talla debe estar entre 30 a 200 ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      }
    }

    // ---------------------- PESO --------------------------------
    value = $('#peso').val();
    if (!decimalRegex.test(value)) {
      $('#peso').addClass('borde-temperatura-hipertermia');
      $('#peso-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
      $('#peso-aceptado strong').addClass('borde-temperatura-valida');
    } else {
      if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 1.0, 180.0)) {
        $('#peso').addClass('borde-temperatura-hipertermia');
        $('#peso-aceptado').html("<p><strong>" + 'El peso debe estar entre 1 a 180 ( Kg )' + "</strong></p>");
        $('#peso-aceptado strong').addClass('borde-temperatura-valida');
      }
    }

    // ---------------------- SATURACION --------------------------------
    value = $('#saturacion').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 60, 100)) {
      $('#saturacion').addClass('borde-temperatura-hipertermia');
      $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
      $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
    }
 

     // ---------------------- PRESION ARTERIAL X --------------------------------
    value = $('#presion_arterial_x').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 250)) {
      $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
      $('#presion_arterial_x-aceptado').html("<p><strong>" + 'La presión arterial debe estar entre 0 a 250' + "</strong></p>");
      $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
    }

    // ---------------------- PRESION ARTERIAL Y --------------------------------
    value = $('#presion_arterial_y').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 250)) {
      $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
      $('#presion_arterial_y-aceptado').html("<p><strong>" + 'La presión arterial debe estar entre 0 a 140' + "</strong></p>");
      $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
    }

    // ---------------------- FRECUENCIA RESPIRATORIA  (/min)  --------------------------------
    value = $('#frecuencia_respiratoria').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 60)) {
      $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
      $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 0 a 60 ( /min )' + "</strong></p>");
      $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    }

    // ---------------------- FRECUENCIA CARDIACA  --------------------------------
    value = $('#frec_cardiaca').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 170)) {
      $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
      $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frec. C. debe ser entre 0 a 170 ( / min )' + "</strong></p>");
      $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } 

  // ---------------------- OXIGENACIÓN  --------------------------------
    value = $('#oxigenacion').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 21, 100)) {
      $('#oxigenacion').addClass('borde-temperatura-hipertermia');
      $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
      $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
    }
 
 
      submitFrm();
      
      return 0; 
  }

  function fn_save_cita(id_medico, fecha_cita, id_turno) {
 
    var id_user = $('#id_user').val();
    $.ajax({
      url: "../registrar_cita_triaje",
      type: "POST",
      data: $("#frmTriajeEmergencia").serialize() + "&id_consultorio=" + id_medico + "&fecha_cita=" + fecha_cita + "&id_turno=" + id_turno,
      success: function(result) { 
        console.log(result);
        if(result==null){ 
          Swal.fire({
            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
            html: '<p class="descripcion-alert"><span><b style="color:Red;">SE HA DETECTADO QUE NO TIENE CUPOS DISPONIBLES PARA PODER AGENDAR UNA CITA.</b><br></span></p>', 
            allowOutsideClick: false,
            timer: 2800,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
          });
        }
      }
    });
    
  }


        // ----------------------- GESTACIÓN INIT ---------------------------------
        function showDivGestacion() {
            $('#divGestacion').show();
        }

        function hideDivGestacion() {
            $('#divGestacion').hide();
        }

        $('#esta_gestando').on('click', function() {
            var esta_gestando = $('#esta_gestando').attr("aria-pressed");
            // clearValuesFormGestante();
            // clearValuesFormPuerpera();
            if (esta_gestando === "false") {
                $("#frsgetante").show();
                $("#formPurperio").hide();
                changeTogglePuerpera(false);
            }else {
                $("#frsgetante").hide();
            }
        });

        function toggleFields() {
            seleccionarNoPorDefecto();
        }

        function calcularTrimestre() {
          //obtencion de fechas    
          var ultimaMestruacion = $("#ultimaMestruacion").datepicker("getDate");
          var fechaHoy = new Date();
          var diffMilisegundos = fechaHoy.getTime() - ultimaMestruacion.getTime();
          var diffSemanas = Math.floor(diffMilisegundos / (1000 * 60 * 60 * 24 * 7)); // Obtener la diferencia en semanas
          var semanas;
          if (diffSemanas >=45 ) {
            bootbox.alert("INGRESAR UNA FECHA CORRECTA MAXIMO DE 45 SEMANAS");
          } else {
            semanas = "SEMANA N° " + diffSemanas;
          }
          // Mostrar el trimestre en el campo de entrada correspondiente
          $("#semana_gesta").val(semanas);
        }

        // Llamar a la función de cálculo cuando se cambie la fecha de la última menstruación
        $("#ultimaMestruacion").change(calcularTrimestre);  

        function calcularFechaProbableParto(dateLastMenstruation) {
            dateProbable = moment(dateLastMenstruation).add(7, 'days').subtract(3, 'month').add(1, 'year');
            const dateProbableFormat = dateProbable.format('DD-MM-YYYY');
            $("#parto_probable").val(dateProbableFormat);
        }

        function clearValuesFormGestante() {
            $('#ultimaMestruacion').val('');
            $('#parto_probable').val('');
            $('#semana_gesta').val('');
            $('#control_prenatal').val('');
            $('#duda').val('0');
            $("#establecimiento_nombre").val('');
            $("#control_ipress_origin").hide();
        }

        function changeToggleGestante(newValue) {
            if (newValue) {
                $('#esta_gestando').addClass('active');
                $('#esta_gestando').attr('aria-pressed', 'true');
            }else {
                $('#esta_gestando').removeClass('active');
                $('#esta_gestando').attr('aria-pressed', 'false');
            }
        }

        function seleccionarNoPorDefecto() {
            var radioNo = $('input[name="duda"][value="0"]');
            radioNo.prop('checked', true);
        }

        // receive date on format (YYYY-MM-DD)
        function valiDateIsMaxCurrentDate(date) {
            let isValid = false;
            const dateMoment = moment(date);
            const current = moment().startOf('day');
            const diffDays = moment.duration(dateMoment.diff(current)).asDays();
            if(Number(diffDays) <= 0) {
                isValid = true
            }
            return isValid;
        }

        // receive date on format (YYYY-MM-DD)
        function valiDateIsMinCurrentDate(date) {
            let isValid = false;
            const dateMoment = moment(date);
            const current = moment().startOf('day');
            const diffDays = moment.duration(dateMoment.diff(current)).asDays();
            if(Number(diffDays) >= 0) {
                isValid = true
            }
            return isValid;
        }

        function valiDateIsMinCurrentDateAndMinusOneDay(date) {
            let isValid = false;
            const dateMoment = moment(date);
            const current = moment().startOf('day');
            const diffDays = moment.duration(dateMoment.diff(current)).asDays();
            if(Number(diffDays) >= -1) {
                isValid = true
            }
            return isValid;
        }

            
        // ----------------------- GESTACIÓN END ---------------------------------
        document.addEventListener("DOMContentLoaded", function() {
        var controlPrenatalInput = document.getElementById("control_prenatal");

        controlPrenatalInput.addEventListener("input", function() {
            var value = this.value.trim(); // Obtener el valor y eliminar espacios en blanco

            // Verificar si el valor no está vacío y no es un número de un solo dígito
            if (value !== "" && !/^\d$/.test(value)) {
                // Restablecer el valor a un dígito si se ingresa más de uno
                this.value = value.charAt(0);
            }
        });

        controlPrenatalInput.addEventListener("keypress", function(event) {
            var key = event.key;

            // Permitir solo números y la tecla de retroceso (backspace)
            if (!/^\d$/.test(key) && key !== "Backspace") {
                event.preventDefault(); // Prevenir la entrada de otros caracteres
            }
        });
        });


        // ----------------------- PUERPERA INIT ---------------------------------
        function showDivPuerpera() {
            $('#divPuerpera').show();
        }

        function hideDivPuerpera() {
            $('#divPuerpera').hide();
        }

        function changeTogglePuerpera(newValue) {
            if (newValue) {
                $('#addPuerpera').addClass('active');
                $('#addPuerpera').attr('aria-pressed', 'true');
            }else {
                $('#addPuerpera').removeClass('active');
                $('#addPuerpera').attr('aria-pressed', 'false');
            }
        }

        $('#addPuerpera').on('click', function() {
            const addPuerpera = $('#addPuerpera').attr("aria-pressed");
            // clearValuesFormPuerpera();
            // clearValuesFormGestante();
            showDivPuerpera();
            if (addPuerpera == "false") {
                $("#formPurperio").show();
                $("#frsgetante").hide();
                changeToggleGestante(false);
            } else {
                $("#formPurperio").hide();
            }
        });

        $('#fecha_parto').datepicker().on('changeDate', (e) => {
            $("#hora_parto").val('');
            const dateSelected = moment(e.date).format('YYYY-MM-DD');
            const isValid = valiDateIsMinCurrentDateAndMinusOneDay(dateSelected)
            if (!isValid) {
              Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                html: '<p class="descripcion-alert"><span>La Fecha de parto debe ser mayor o igual a la fecha Actual.</span></p>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                preConfirm: function() {
                    $('#fecha_parto').val("");
                    $('#fecha_parto').focus();
                }
            });
	} else if (moment(dateSelected).isAfter(moment().format('YYYY-MM-DD'))) {
		// Si la fecha de parto es posterior a la fecha actual, muestra una alerta
		Swal.fire({
		    title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
		    html: '<p class="descripcion-alert"><span>La Fecha de parto no puede ser posterior a la fecha Actual.</span></p>',
		    confirmButtonColor: '#3085d6',
		    confirmButtonText: 'OK',
		    allowOutsideClick: false,
		    preConfirm: function() {
			$('#fecha_parto').val("");
			$('#fecha_parto').focus();
		    }
		});
	    }
	});

        function clearValuesFormPuerpera() {
            $('#fecha_parto').val('');
            $('#hora_parto').val('');
            $('#tipo_parto').val('0');
            $('#complicacion_parto').val('0');
            $('#descripcion_parto').val('');
        }
        // ----------------------- PUERPERA END ---------------------------------


        function diff_weeks(dt2, dt1) {
          // Calculate the difference in milliseconds between dt2 and dt1
          var diff =(dt2.getTime() - dt1.getTime()) / 1000;
          // Convert the difference from milliseconds to weeks by dividing it by the number of milliseconds in a week
          diff /= (60 * 60 * 24 * 7);
          // Return the absolute value of the rounded difference as the result
          return Math.abs(Math.round(diff));
        }

          //TAG: BUG
        $('#ultimaMestruacion').datepicker().on('changeDate', (e) => {
            const dateSelected = moment(e.date).format('YYYY-MM-DD');
            const isValid = valiDateIsMaxCurrentDate(dateSelected)
            if (isValid) {
                calcularTrimestre(dateSelected)
                calcularFechaProbableParto(dateSelected)   
            } else {
                Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>La Fecha de la ULTIMA MESTRUACIÓN debe ser menor o igual a la fecha Actual.</span></p>',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    preConfirm: function() {
                        $('#ultimaMestruacion').val("");
                        $('#ultimaMestruacion').focus();
                        $("#parto_probable").val("");
                        $("#semana_gesta").val("");
                        $("#control_prenatal").val("");
                    }
                });
            }
        });

        $('#parto_probable').datepicker().on('changeDate', (e) => {
            const dateSelected = moment(e.date).format('YYYY-MM-DD');
            const isValid = valiDateIsMinCurrentDate(dateSelected)
            if (!isValid) {
              Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
                html: '<p class="descripcion-alert"><span>La Fecha del probable parto debe ser mayor o igual a la fecha Actual.</span></p>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                preConfirm: function() {
                    $('#parto_probable').val("");
                    $('#parto_probable').focus();
                }
            });
            }
        });

        //TAG: BUG
        async function getGestacionesByAseguradoDNI() {
            const aseguradoDNI = $("#dni_beneficiario").val();
            $("#id_gestacion").val('');
            if (aseguradoDNI) {
                $.ajax({
                    url: "/obtener_ultima_gestacion_dni_asegurado_ajax/"+ aseguradoDNI,
                    type: 'GET',
                    dataType: "JSON",
                    success: (result) => {
                        if (result && result.length > 0) {
                            const lastGestation = result[0];
                            if (!lastGestation.fecha_parto) {
                              setTimeout(() => {
                                  const fechaDeHoy = moment();
                                  const fechaUltimaMenstruacion = moment(lastGestation.fech_ult_regla, 'YYYY-MM-DD');
                                  const diff = fechaDeHoy.diff(fechaUltimaMenstruacion, 'week');

                                  $("#control_ipress_origin").show();
                                  $("#establecimiento_nombre").show();

                                  $("#establecimiento_nombre").val(lastGestation.establecimiento_nombre);

                                  $('#esta_gestando').addClass('active');
                                  $('#esta_gestando').attr('aria-pressed', 'true');

                                  showDivGestacion();

                                  $("#frsgetante").show();

                                  $("#id_gestacion").val(lastGestation.id_gestacion);
                                  $("#ultimaMestruacion").val(lastGestation.fech_ult_regla_format);

                                  $("#parto_probable").val(lastGestation.fech_prob_parto_format);
                                  $("#semana_gesta").val(`SEMANA N° ${diff}`);
                                  // $("#control_prenatal").val(Number(lastGestation.num_controles) + 1);
                                  $("#control_prenatal").val(Number(lastGestation.num_controles));

                                  $("#ultimaMestruacion").prop('disabled', true);
                                  $("#parto_probable").prop('disabled', true);
                                  $("#control_prenatal").prop('disabled', true);
                                  $("#duda").prop('disabled', true);
                              }, 500); 
                            }
                        } else {
                          $("#ultimaMestruacion").removeProp('disabled');
                          $("#parto_probable").removeProp('disabled');
                          $("#control_prenatal").removeProp('disabled');
                          $("#duda").removeProp('disabled');
                        }
                        if ($("#puerpera").val("true") == "true") {
                          $("#formPurperio").show();
                        }
                    },
                    error: (xhr, status, error) => {
                        Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                            html: `<p class="descripcion-alert"><span>${error}<br><br></span></p>`,
                            allowOutsideClick: false,
                            timer: 6800,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                })
            }
        }

        $('#id_topico').on('change', () => {
            const _id_topico = $('#id_topico').val();
        })

        function buildOptionsSelectReasonsByServiceId() {
            const _id_topico = $('#id_topico').val();
            const motivo_ingreso = {!! json_encode($motivo_ingreso) !!};

            $('#id_motivo_ingreso').empty().trigger('change');

            $('#id_motivo_ingreso').append($('<option>', {
                value: '',
                text: '-- SELECCIONAR --'
            }));
            if (motivo_ingreso && motivo_ingreso.length > 0) {
                if (_id_topico === '') {
                    $.each(motivo_ingreso, function (index, item) {
                        if (item.id !== 5) $('#id_motivo_ingreso').append($('<option>', {value: item.id, text: item.descripcion}));
                    });
                } else {
                    if (parseInt(_id_topico) === 6 || parseInt(_id_topico) === 7) {
                        $.each(motivo_ingreso, function (index, item) {$('#id_motivo_ingreso').append($('<option>', {value: item.id, text: item.descripcion}));});
                    } else {
                        $.each(motivo_ingreso, function (index, item) {
                            if (item.id !== 5)$('#id_motivo_ingreso').append($('<option>', {value: item.id, text: item.descripcion}));
                        });
                    }
                }
            }
            $('#id_motivo_ingreso').select2();
            $('#id_motivo_ingreso').val('').trigger('change');
            const id_motivo_ingreso_old = {!! json_encode(old('id_motivo_ingreso', false)) !!};
            if (id_motivo_ingreso) {
              $('#id_motivo_ingreso').val(String(id_motivo_ingreso_old)).trigger('change');
            }
        }

        //buildOptionsSelectReasonsByServiceId();

        function validateGestacionPuerpera() {
            $("#formPurperio").hide();
            $("#frsgetante").hide();
            clearValuesFormGestante();
            clearValuesFormPuerpera();
            changeToggleGestante(false);
            changeTogglePuerpera(false);
            const _edad = $('#edad_pac').val();
            const _sexo = $('#sexo').val();
            const _id_topico = $('#id_topico').val();
            if (_edad !== '' && _sexo !== '' && _id_topico !== '' && _edad > 0 && _id_topico > 0) {
                if (
                    parseInt(_edad) >= 5 &&
                    parseInt(_edad) <= 60 &&
                    String(_sexo).trim().toUpperCase() === 'F' &&
                    (parseInt(_id_topico) === 6 || parseInt(_id_topico) === 7)
                ) {
                    showDivSaludMaterna();
                    showDivGestacion();
                    showDivPuerpera();
                    getGestacionesByAseguradoDNI();
                    //buildOptionsSelectReasonsByServiceId();
                }else {
                    hideDivSaludMaterna()
                    hideDivGestacion();
                    hideDivPuerpera();
                    //buildOptionsSelectReasonsByServiceId();
                }
            }else {
                hideDivSaludMaterna();
                hideDivPuerpera();
                hideDivPuerpera();
                //buildOptionsSelectReasonsByServiceId();
                
            }
        }

        function showDivSaludMaterna() {
            $("#divSaludMaterna").show();
        }

        function hideDivSaludMaterna() {
            $("#divSaludMaterna").hide();
        }

        $(document).ready(function() {
          function handlerErrorsController() {
            const inputsConError = [];
            let message = "";
            @foreach ($errors->keys() as $input)
                inputsConError.push('{{ $input }}');
            @endforeach
           

            if (inputsConError && inputsConError.length > 0) {
              inputsConError.forEach(function(inputName) {
                $(`#${inputName}`).addClass("borde-alerta");
                $(`#${inputName}`).next('.select2-container').addClass('borde-alerta');
                if (inputName == 'edad_pac') {
                  $(`#edad_paciente2`).addClass("borde-alerta");
                }
              });
              @foreach ($errors->all() as $error)
                message += `<span style="color: red; font-size: 1.5rem;font-weight: 700px;">- {{ $error }}</span> <br/>`;
              @endforeach
              Swal.fire({
                title: '<div class="titulo-padding"><p>Campos incorrectos</p></div>',
                html: `<div style="text-align: left; margin: 0px 20px 0px 20px;">${message}</div>`,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ENTENDIDO',
                allowOutsideClick: false,
              });
            }
          };
          handlerErrorsController();
        })

        if ("{{old('dni_beneficiario')}}".length > 0) {
          validateGestacionPuerpera(); 
        }

        function alertVencimientoSeguro(fechaVigencia) {
          if (fechaVigencia) {
            formato = "YYYYMMDD";
            if (moment(fechaVigencia, "DD/MM/YYYY", true).isValid()) {
                formato = "DD/MM/YYYY";
            } else if (moment(fechaVigencia, "YYYYMMDD", true).isValid()) {
                formato = "YYYYMMDD";
            }
            const fechaVigenciaMoment = moment(fechaVigencia, formato, true).startOf('day');
            if (fechaVigenciaMoment) {
              const fechaActual = moment().startOf('day');
              const diferenciaDias = fechaVigenciaMoment.diff(fechaActual, "days");
              if (diferenciaDias > -1 && diferenciaDias <= 30) {
                let htmlString = `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vencerá en ${diferenciaDias} días. Es importante informarle.</b></span>`;
                Swal.fire({
                  icon: 'info',
                  title: 'AVISO',
                  html: `
                    <div style="font-size:16px; line-height:1.6; text-align:center;">
                      <b>El seguro del paciente vencerá en ${diferenciaDias} ${diferenciaDias === 1 ? 'día' : 'días'}.</b><br>
                      Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                    </div>
                  `,
                  confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                  confirmButtonColor: '#3085d6',
                  width: '38em',
                  customClass: {
                    popup: 'swal-afiliado_a_vencer',
                    title: 'swal-title',
                    htmlContainer: 'swal-content'
                  }
                });
                if (diferenciaDias === 0) {
                  htmlString = `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vence hoy. Es importante informarle.</b></span>`;
                  Swal.fire({
                    icon: 'info',
                    title: 'AVISO',
                    html: `
                      <div style="font-size:16px; line-height:1.6; text-align:center;">
                        <b>El seguro del paciente vence hoy.</b><br>
                        Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                      </div>
                    `,
                    confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                    confirmButtonColor: '#3085d6',
                    width: '38em',
                    customClass: {
                      popup: 'swal-afiliado_a_vencer',
                      title: 'swal-title',
                      htmlContainer: 'swal-content'
                    }
                  }); 
                }
                $("#alertVigenciaSeguro").show();
                $("#alertVigenciaSeguro").html(htmlString);
              } else {
                $("#alertVigenciaSeguro").hide();
              }
            } else {
              $("#alertVigenciaSeguro").hide();
            }
          }
        }
</script>

<style>
   .sweet-alert { margin: auto; transform: translateX(-50%); }
  .form-control:focus {
    border-color: #66afe9;
    outline: 0;
    box-shadow: inset 0 1px 1px white, 0 0 8px white;
  }

  .select2-container {
    font-size: 11px !important;
  }

  .borde-saturacion-hipoxia-grave {
    border-color: red;
    border: 2px red solid;
  }

  .borde-saturacion-hipoxia-moderada {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-saturacion-hipoxia-leve {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-saturacion-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-bajopeso {
    border-color: skyblue;
    border: 2px skyblue solid;
  }

  .borde-imc-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-sobrepeso {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-imc-obesidad1 {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-imc-obesidad2 {
    border-color: orangered;
    border: 2px orangered solid;
  }

  .borde-imc-obesidad3 {
    border-color: red;
    border: 2px red solid;
  }

  .borde-temperatura-hipotermia {
    border-color: blue;
    border: 2px blue solid;
  }

  .borde-temperatura-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-temperatura-fiebre {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-temperatura-fiebre-alta {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-temperatura-hipertermia {
    border-color: red;
    border: 2px red solid;
  }


  .borde-talla {
    border-color: green;
    border: 2px green solid;
  }

  .mensaje-temperatura {
    color: green;
    margin-left: 10px;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    /*margin:10px;*/
    text-align: left;
    /*margin-top:20px;*/
    /*background:#5cb85c*/
    background: transparent;
    color: #000000
  }

  .fc-day-grid-event :hover {
    color: #000000
  }

  .fc-content {
    padding: 0px !important;
  }

  .fc-title {
    text-align: left;
    font-size: 10px
  }

  /* .modal-dialog {
    width: 1200px !important;
  } */
  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }

  #suggestions {
    width: 350px;
    height: 150px;
    overflow: auto;
  }

  .alert {
    padding: 10px;
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    /*background-color: rgba(0, 0, 0, 0);*/
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
  }

  .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }

  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }

  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .form-group {
    margin-bottom: 10px;
  }

  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }

  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
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
    /*font-size: 1.7em;*/
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }

  // -------

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

  /*************Styles de sweetAlert2*************/

  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
    margin: 15rem auto auto auto !important;
  }

  .swal2-popup {
    width: 50em !important;
  }

  .swal2-popup,
  .swal2-header {
    padding: 0 !important;
  }

  .swal2-header {
    background: #3c763d;
  }

  .swal2-html-container {
    margin: 0 !important;
    padding: 1rem 0 !important;
  }

  .titulo-padding {
    padding: 1rem 0 0 0;
  }

  .titulo-padding p {
    color: white;
    margin: 0;
    font-size: 2.5rem;
  }

  .descripcion-alert {
    font-size: 1.5rem;
    padding: 0 1rem;
  }

  .descripcion-alert {
    margin: 0;
  }

  .descripcion-alert span {
    font-weight: 700;
  }

  .swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }
  .swal-noafiliado .swal2-icon {
    transform: scale(0.5);
    margin: 0;
    font-size: 32px!important;
  }
  .swal-noafiliado{
    border-radius:12px;
    border: 3px solid orange;
  }
  .swal-noafiliado .swal-title{
    font-size:23px !important;
    font-weight:700;
  }
  .swal-noafiliado .swal-content{
    font-size:17px !important;
    color:#444;
  }
  .swal-noafiliado .swal2-confirm{
    font-size:15px !important;
    padding:10px 28px !important;
  }

  .swal-noafiliadosp .swal2-icon {
    transform: scale(0.5);
    margin: 0;
    font-size: 32px!important;
  }
  .swal-noafiliadosp .swal2-icon.swal2-warning {
    border-color: #F2421F;
    color: #F2421F;
  }
  .swal-noafiliadosp .swal2-icon.swal2-warning .swal2-icon-content {
    color: #F2421F;
  }
  .swal-noafiliadosp{
    border-radius:12px;
    border: 3px solid #F2421F;
  }
  .swal-noafiliadosp .swal-title{
    font-size:23px !important;
    font-weight:700;
  }
  .swal-noafiliadosp .swal-content{
    font-size:17px !important;
    color:#444;
  }
  .swal-noafiliadosp .swal2-confirm{
    font-size:15px !important;
    padding:10px 28px !important;
  }

  .swal-noafiliado .swal2-header,
  .swal-noafiliadosp .swal2-header{
    background: #fff !important;
  }

  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal.swal-noafiliado,
  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal.swal-noafiliadosp {
    margin: auto !important;
  }

  .swal-afiliado_a_vencer{
    border-radius: 12px;
  }

  .swal-noafiliado .swal2-title,
  .swal-afiliado_a_vencer .swal2-title{
    font-size: 23px !important;
    font-weight: 700;
    font-family: 'Roboto' !important;
  }

  .swal-noafiliado .swal2-html-container,
  .swal-afiliado_a_vencer .swal2-html-container{
    font-size: 17px !important;
    color: #444;
    font-family: 'Roboto' !important;
  }

  .swal-noafiliado .swal2-confirm,
  .swal-afiliado_a_vencer .swal2-confirm{
    font-size: 15px !important;
    padding: 10px 28px !important;
    color: #fff !important; 
    background-color: rgb(48, 133, 214) !important;
  }

  .swal-noafiliado .swal2-icon,
  .swal-afiliado_a_vencer .swal2-icon{
    transform: scale(0.5);
    margin: 0;
    font-size: 32px!important;
  }

  /* Diferencias */
  .swal-noafiliado .swal2-header,
  .swal-afiliado_a_vencer .swal2-header{
    background: #fff !important;
  }
  .swal-noafiliado{
    background: #fff;
    border: 3px solid orange;
  }

  .swal-afiliado_a_vencer{
    border: 3px solid skyblue;
  }

  /**************************** MARCA DE AGUA - HISTORIAL DE TRIAJE ***********************/
  .marca_agua_container {
    position: relative;
  }
  .marca_agua_overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 999;
    overflow: hidden;
  }
  .marca_agua_overlay span {
    position: absolute;
    color: rgba(5, 98, 24, 0.12);
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transform: rotate(-35deg);
    user-select: none;
    letter-spacing: 1px;
  }
  @media print {
    .marca_agua_overlay span {
      display: none !important;
    }
    .marca_agua_overlay {
      z-index: 0 !important;
      bottom: 0 !important;
      height: auto !important;
      background-image: var(--marca-agua-print) !important;
      background-repeat: no-repeat !important;
      background-size: 100% 100% !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .marca_agua_container > :not(.marca_agua_overlay) {
      position: relative;
      z-index: 1;
    }
    .marca_agua_container .panel,
    .marca_agua_container .panel-heading,
    .marca_agua_container .panel-body,
    .marca_agua_container .modal-content,
    .marca_agua_container .modal-body,
    .marca_agua_container .well,
    .marca_agua_container .form-control,
    .marca_agua_container table,
    .marca_agua_container tr,
    .marca_agua_container td,
    .marca_agua_container th {
      background: transparent !important;
    }
    .container:has(.marca_agua_container) {
      width: 1200px !important;
      max-width: none !important;
      zoom: 0.6;
    }
    .marca_agua_container a[href]:after {
      content: none !important;
    }
    .marca_agua_container .table-responsive {
      overflow: visible !important;
    }
  }
</style>
<script>
  (function () {
    if (!window.matchMedia || window.marcaAguaImpresionRegistrada) return;
    window.marcaAguaImpresionRegistrada = true;
    var mq = window.matchMedia('print');
    var ctx = document.createElement('canvas').getContext('2d');
    ctx.font = '14px Arial, sans-serif';
    function svg_marca_agua(W, H, texto) {
      var txt = String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      var anchoTexto = ctx.measureText(texto).width + texto.length;
      var textos = '';
      for (var r = 0; r < Math.ceil(H / 100) + 2; r++) {
        for (var c = 0; c < Math.ceil(W / 220) + 2; c++) {
          var x = c * 220 - 40 + anchoTexto / 2;
          var y = r * 100 - 20 + 9;
          textos += '<text x="' + x + '" y="' + y + '" text-anchor="middle" dominant-baseline="middle" transform="rotate(-35 ' + x + ' ' + y + ')">' + txt + '</text>';
        }
      }
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">' +
        '<g font-family="Arial, sans-serif" font-size="14" letter-spacing="1" fill="#e6e6e6">' + textos + '</g></svg>';
      return 'url("data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg) + '")';
    }
    function al_cambiar_impresion(e) {
      if (!e.matches) return;
      var overlays = document.querySelectorAll('.marca_agua_overlay');
      for (var i = 0; i < overlays.length; i++) {
        var span = overlays[i].querySelector('span');
        if (!span || !overlays[i].offsetWidth) continue;
        overlays[i].style.setProperty('--marca-agua-print', svg_marca_agua(overlays[i].offsetWidth, overlays[i].offsetHeight, span.textContent));
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', al_cambiar_impresion);
    else if (mq.addListener) mq.addListener(al_cambiar_impresion);
  })();
</script>

@endsection
@section('content')
    <?php 
      $complicaciones = ['SIN COMPLICACIONES', 'COMPLICADO'];
    ?>
<div class="loader"></div>
<div class="container">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12">
            <h3 class="panel-title" style="padding-top: 5px;">MÓDULO DE GESTION DE TRIAJE </h3>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-12" style="text-align: right">
            <a href="/all_triajes_atendidos" class="btn btn-default btn-filter btn-sm">LISTA DE PACIENTES CON TRIAJE</a>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <form class="app-form" method="post" action="{{ route('store_emergencia_triaje') }}" id="frmTriajeEmergencia"
          name="frmTriajeEmergencia" autocomplete="off">
          <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}" class="form-control">
          <input type="hidden" name="id_consultorio" id="id_consultorio" value="1848" class="form-control">


          
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="flag_triaje" id="flag_triaje" class="form-control">
          <input type="hidden" name="fecha_atencion" id="fecha_atencion" class="form-control" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">

          <input type="hidden" value="{{Auth::user()->id}}" name="id_user" id="id_user">
          <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
          <input type="hidden" value="1" name="id_tipo_doc_ident" id="id_tipo_doc_ident" class="form-control">
          <input type="hidden" value="DNI" name="tipo_doc_ident" id="tipo_doc_ident" class="form-control">
          <input type="hidden" class="form-control" value="{{old('domicilio')}}" name="domicilio" id="domicilio" />

          <input type="hidden" name="codigo_ubig" id="codigo_ubig" value="{{old('codigo_ubig')}}">
          <input type="hidden" name="id_departamento" id="id_departamento" value="{{('id_departamento')}}">
          <input type="hidden" name="id_provincia" id="id_provincia" value="{{old('id_provincia')}}">
          <input type="hidden" name="id_distrito" id="id_distrito" value="{{old('id_distrito')}}">

          <input type="hidden" name="grado" id="grado" value="{{old('grado')}}">
          <input type="hidden" name="unidad_pnptitular" id="unidad_pnptitular" value="{{old('unidad_pnptitular')}}">
          <input type="hidden" name="id_tipo_parentesco" id="id_tipo_parentesco" value="8">
          <input type="hidden" name="ciptitular" id="ciptitular" value="{{old('ciptitular')}}">

          <input type="hidden" name="beneficiario_ajax" id="beneficiario_ajax" value="">

          <input type="hidden" name="situaciontitular" id="situaciontitular" value="{{old('situaciontitular')}}">
          <input type="hidden" name="nrodoctitular" id="nrodoctitular" value="{{old('nrodoctitular')}}">
          <input type="hidden" name="situacionpaciente" id="situacionpaciente" value="{{old('situacionpaciente')}}">
          <input type="hidden" name="parentesco_paciente" id="parentesco_paciente" value="{{old('parentesco_paciente')}}">
          <input type="hidden" name="puerpera" id="puerpera" @if(old('puerpera') != null) value="true" @else value="false" @endif>

          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES DE TRIAJE DE EMERGENCIA</a></li>
          </ul>
          <div class="panel-body">
            <div class="tab-content">
              <div class="tab-pane fade in active" id="tab2default">

                <fieldset style="display: none;">
                  <div class="form-group col-sm-9" style="text-align: right;">
                  </div>
                  <div class="form-group col-sm-3" style="text-align: right;">
                    <label style="cursor: pointer;"> REGISTRO MANUAL </label>
                    <input type="checkbox" id="habilitar_registro_manual" name="habilitar_registro_manual"
                      style="margin-left: 10px;cursor: pointer;" {{ old('habilitar_registro_manual') ? 'checked' : '' }}>>
                    <input id="registro_manual" name="registro_manual" type="hidden" value="{{old('registro_manual')}}">
                  </div>
                </fieldset>
                <div class="alert alert-warning" role="alert" id="alertVigenciaSeguro" style="padding-left: 12px" hidden></div>

                <fieldset>
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i>
                              INFORMACIÓN DEL PACIENTE</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px !important;">
                      <div class="tab-content">

                        <!----------------------------- DATOS PACIENTE --------------------------------->
                        <!----------------------------- DATOS PACIENTE --------------------------------->
                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                          <fieldset>
                            <div class="row">
                              <div class="col-lg-10 col-md-8 col-sm-12">
                                <div class="form-group" style="margin-bottom: 5px;">
                                  <fieldset>
                                    <div class="row">
                                      <div class="form-group col-lg-3  col-md-4">
                                        <label for="tipo">TIPO DOC</label>
                                        <select class="form-control input-sm" id="tipodoc_beneficiario_bus"
                                          name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="1">
                                          <option value="1" @if(old('tipodoc_beneficiario_bus') == '1') selected @endif>DNI</option>
                                          <option value="2" @if(old('tipodoc_beneficiario_bus') == '2') selected @endif>CARNET DE EXTRANJERIA</option>
                                          <option value="3" @if(old('tipodoc_beneficiario_bus') == '3') selected @endif>PASAPORTE</option>
                                          <option value="4" @if(old('tipodoc_beneficiario_bus') == '4') selected @endif>DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                          <option value="11" @if(old('tipodoc_beneficiario_bus') == '11') selected @endif>SDI</option>
                                          <option value="12" @if(old('tipodoc_beneficiario_bus') == '12') selected @endif>CUI</option>
                                          <option value="13" @if(old('tipodoc_beneficiario_bus') == '13') selected @endif>CNV</option>
                                          <option value="14" @if(old('tipodoc_beneficiario_bus') == '14') selected @endif>PTP</option>
                                        </select>
                                      </div>
                                      <div class="form-group col-lg-3  col-md-4">
                                        <label for="dni_beneficiario">NRO DOC</label>
                                        <div class="input-group">
                                          <input id="dni_beneficiario" name="dni_beneficiario" type="text"
                                            class="form-control input-sm text-uppercase" maxlength="8" pattern="[0-9]*"
                                            onkeypress="return esEntero(event);" tabindex="2" value="{{old('dni_beneficiario')}}" />
                                          <span class="input-group-btn">
                                            <button type="button" id="btnBuscar" onclick="getReniecByDni();"
                                              class="btn btn-info">Buscar</button>
                                          </span>
                                        </div>
                                        <input type="hidden" name="tipo_registro" id="tipo_registro" value="{{old('tipo_registro')}}">
                                        <input type="hidden" name="menor_edad" id="menor_edad" value="{{old('menor_edad')}}">
                                        <input type="hidden" name="estado_11_triaje" id="estado_11_triaje" value="{{old('estado_11_triaje')}}">
                                        <input type="hidden" name="prioridadSeleccionada" id="prioridadSeleccionada" value="{{old('prioridadSeleccionada')}}">
                                        <input type="hidden" name="topicoSeleccionado" id="topicoSeleccionado" value="{{old('topicoSeleccionado')}}">
                                        <input type="hidden" name="observacionSeleccionado" id="observacionSeleccionado" value="{{old('observacionSeleccionado')}}">
                                        <input type="hidden" name="nro_doc_ident" id="nro_doc_ident" value="{{old('nro_doc_ident')}}">
                                      </div>
                                      <div class="form-group col-lg-2 col-md-4">
                                        <label>F.NACIMIENTO</label>
                                        <input value="{{old('fecha_nac')}}" type="text" name="fecha_nac" id="fecha_nac" class="form-control"
                                          readonly="">
                                        <div id="fecha_nacimiento_editable">
                                          <div class="input-group date fecha">
                                            <input readonly="" type="text" name="fecha_nac2" id="fecha_nac2"
                                              class="form-control" placeholder="dd/mm/aaaa" value="{{old('fecha_nac2')}}"><span class="input-group-addon">
                                            </span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group col-lg-4 col-md-12">
                                        <label>EDAD</label>
                                        <input value="{{old('edad_paciente2')}}" type="text" name="edad_paciente2" id="edad_paciente2"
                                          class="form-control" readonly="">
                                        <input value="{{old('edad_pac')}}" type="hidden" name="edad_pac" id="edad_pac" class="form-control" 
                                          readonly="">
                                      </div> 
                                    </div>
                                  <fieldset>
                                    <div class="row">
                                      <div class="form-group col-lg-2  col-md-6">
                                        <label>SEXO</label>
                                        <input value="{{old('sexo')}}" tabindex="7" type="text" name="sexo" id="sexo"
                                          class="form-control text-uppercase text-center" readonly="">
                                        <select class="form-control input-sm" id="selectSexo" name="selectSexo" tabindex="5">
                                          <option value="M" selected="">M</option>
                                          <option value="F">F</option>
                                        </select>
                                      </div>
                                      <div class="form-group col-lg-3  col-md-6">
                                        <label>AP.PATERNO</label>
                                        <input readonly="" type="hidden" class="form-control" value="{{old('beneficiario')}}" name="beneficiario"
                                          id="beneficiario" style="text-align:left" tabindex="4">
                                        <input value="{{old('paterno')}}" tabindex="5" type="text" name="paterno" id="paterno"
                                          class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="form-group col-lg-3  col-md-6">
                                        <label>AP.MATERNO</label>
                                        <input value="{{old('materno')}}" tabindex="5" type="text" name="materno" id="materno"
                                          class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="form-group col-lg-4  col-md-6">
                                        <label>NOMBRES</label>
                                        <input value="{{old('nombre')}}" tabindex="6" type="text" name="nombre" id="nombre"
                                          class="form-control text-uppercase" readonly="">
                                      </div>

                                      <input  type="hidden" name="descripcion_tipo_beneficiario_saludpol" value="{{old('descripcion_tipo_beneficiario_saludpol')}}"
                                        id="descripcion_tipo_beneficiario_saludpol" class="form-control text-uppercase"
                                        readonly="">
                                      <input type="hidden" name="tipo_beneficiario_saludpol" id="tipo_beneficiario_saludpol" value="{{old('tipo_beneficiario_saludpol')}}">
                                    </div>
                                  </fieldset> 
                                </div>
                              </div>
                              <div class="col-lg-2 col-md-4 col-sm-12" style="text-align: center;">
                                <input id="foto_paciente" name="foto_paciente" type="hidden">
                                <img id="foto_imagen_paciente" src="" style="width: 50%;"></img>
                                </input>
                              </div>

                              <div class="row"> 
                                <div class="col-lg-12 col-md-12 col-sm-12" >
                                <!------------------    INFORMACIÓN DEL INGRESO     display: none;------------------> 
                                      <fieldset id="seccion_datos_titular" name="seccion_datos_titular" style="padding-bottom: 10px !important;">  
                                        <hr>
                                          <div class="col-lg-2 col-md-6 col-sm-12" >
                                            <label>DOC.TITULAR</label>
                                            <input value="{{old('documento_titular')}}" type="text" name="documento_titular" id="documento_titular" class="form-control" readonly="">
                                          </div>
                                          <div class="col-lg-4 col-md-6 col-sm-12" >
                                            <label>NOM.TITULAR</label>
                                            <input value="{{old('descripcion_titular')}}" type="text" name="descripcion_titular" id="descripcion_titular" class="form-control" readonly="">
                                          </div>
                                          <div class="col-lg-2 col-md-6 col-sm-12" >
                                            <label>CIP.TITULAR</label>
                                            <input value="{{old('cip_titular')}}" type="text" name="cip_titular" id="cip_titular" class="form-control" readonly="">
                                          </div>
                                          <div class="col-lg-4 col-md-6 col-sm-12" >
                                            <label>GRADO.TITULAR</label>
                                            <input value="{{old('grado_titular')}}" type="text" name="grado_titular" id="grado_titular" class="form-control" readonly="">
                                          </div> 
                                      </fieldset>
                                  <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
                                </div>
                              </div>

                            </div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                </fieldset>

                <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
                <fieldset>
                  <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important; ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-book" aria-hidden="true"></i>
                              INFORMACIÓN DEL INGRESO</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px !important;">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">

                          <fieldset style="padding-bottom: 10px !important;">
                            <div class="row">
                              <div class="form-group col-lg-3">
                                <label>FORMA DE INGRESO</label>
                                <select class="form-control input-sm" id="id_forma_ingreso" name="id_forma_ingreso"
                                  onchange="getEstado_ingreso();">
                                  <option value="" selected="">--SELECCIONAR--</option>
                                  @foreach($formas_ingreso as $for_ing)
                                    <option value="{{$for_ing->id}}" @if(old('id_forma_ingreso') == $for_ing->id) selected @endif>{{$for_ing->descripcion}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="form-group col-lg-3">
                                <label>ESTADO DE INGRESO</label>
                                <select class="form-control input-sm" id="id_estado_ingreso" name="id_estado_ingreso">
                                  <option value="" selected="">--SELECCIONAR--</option>
                                </select>
                              </div>
                              <div class="form-group col-lg-3">
                                <label>MOTIVO DE INGRESO</label>
                                <select class="form-control input-sm" id="id_motivo_ingreso" name="id_motivo_ingreso">
                                  <option value="" selected="">--SELECCIONAR--</option>
                                  @foreach($motivo_ingreso as $mot_ing)
                                  <option value="{{$mot_ing->id}}" @if(old('id_motivo_ingreso') == $mot_ing->id) selected @endif>{{$mot_ing->descripcion}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </fieldset>

                          <fieldset name="seccion_habilitra_manual" id="seccion_habilitra_manual"
                            style="margin-top: -13px !important;">
                            <div class="row">
                              <div class="form-group col-lg-3">
                                <label>FECHA DE INGRESO</label>
                                <input id="fecha_registro_ingreso" name="fecha_registro_ingreso" type="hidden">
                                <input type="text" class="form-control" autocomplete="off" id="fecha_registro_ingreso_select"
                                  name="fecha_registro_ingreso_select" value="{{old('fecha_registro_ingreso')}}"/>
                              </div>
                              <div class="form-group col-lg-3">
                                <label>HORA DE INGRESO</label>
                                <input type="time" class="form-control" autocomplete="off" min="00:00:00" max="23:59:59"
                                  step="1" id="hora_registro_ingreso" name="hora_registro_ingreso" value="{{old('hora_registro_ingreso')}}">
                              </div>
                            </div>
                          </fieldset>

                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
                <fieldset>
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                              INFORMACIÓN ADICIONAL</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px !important;">
                      <div class="tab-content">

                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                          <fieldset>
                            <div class="row">
                              <div class="form-group col-sm-3">
                                <label>PRIORIDAD</label>
                                <select class="form-control input-sm" id="id_prioridad" name="id_prioridad"
                                  onchange="getArea_topico();">
                                  <option value="" selected="">--SELECCIONAR--</option>
                                  @foreach($prioridades as $p)
                                    @if($p->id==4)
                                      @if($hora>'07:29:59' && $hora<'19:30:01')
                                        <option value="{{$p->id}}" @if(old('id_prioridad') == $p->id) selected @endif>{{$p->prioridad}}</option>
                                      @endif
                                    @else
                                      <option value="{{$p->id}}" @if(old('id_prioridad') == $p->id) selected @endif>{{$p->prioridad}}</option>
                                    @endif
                                  @endforeach
                                </select>
                              </div>

                              <div id="topico_toggle" class="form-group col-lg-3" style="display: none">
                                <label for="topico">AREA/SERVICIO</label>
                                <select class="form-control input-sm" id="id_topico" name="id_topico">
                                  <option value="" selected="">--SELECCIONAR--</option>
                                </select>
                                <input id="estado_topico" name="estado_topico" value="{{old('estado_topico')}}" type="hidden"> 
                              </div>

                              <div id="seccion_medico_cita" name="seccion_medico_cita">
                                <div class="form-group col-lg-6 col-md-6">
                                  <label>MEDICO</label>
                                  <input value="{{old('medico_car')}}" type="text" name="medico_car" id="medico_car" class="form-control"
                                    style="text-transform: uppercase;" readonly>
                                </div>
        
                                <div id="topico_toggle"  style="text-align: center !important;"> 
                                  <input type="hidden" name="hora_cita" id="hora_cita" value="{{old('hora_cita')}}" class="form-control"
                                    style="text-transform: uppercase; text-align: center !important;" readonly>  

                                    <input type="hidden" name="funcion_cita" id="funcion_cita" value="{{old('funcion_cita')}}" class="form-control" readonly> 
                                    <input type="hidden" name="id_medico" id="id_medico" value="{{old('id_medico')}}" class="form-control" readonly> 
                                    <input type="hidden" name="fecha_cita" id="fecha_cita" value="{{old('fecha_cita')}}" class="form-control" readonly> 
                                    <input type="hidden" name="id_turno" id="id_turno" value="{{old('id_turno')}}" class="form-control" readonly>  
                                </div>
                              </div>

                              <div class="form-group col-lg-6 col-md-8">
                                <label>MOTIVO DE LA CONSULTA</label>
                                <input value="{{old("observacion")}}" type="text" name="observacion" id="observacion" class="form-control"
                                  style="text-transform: uppercase;">
                                    <select class="form-control input-sm" id="no_amerita" name="no_amerita" style="display: none;">
                                      <option value="0">--SELECCIONAR--</option>
                                      <option value="1">NO AMERITA</option>
                                    </select>
                              </div>
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </fieldset>

                <!------------------    INFORMACIÓN DE vitales    ------------------>
                <fieldset>
                  <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important; ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-heartbeat" aria-hidden="true"></i>
                              INFORMACIÓN DE SIGNOS VITALES</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px !important;">
                      <div class="tab-content">

                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                          <fieldset style="margin-top: -5px !important;">
                            <div class="row">
                              <div class="form-group col-sm-3">
                                <label>PRESIÓN ARTERIAL</label>
                                <div class="row">
                                  <div class="col-xs-5">
                                    <input value="{{old('presion_arterial_x')}}" tabindex="8" type="text" name="presion_arterial_x" id="presion_arterial_x"
                                      class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                    <span id="presion_arterial_x-aceptado"></span>
                                  </div>
                                  <div class="col-xs-2 text-center">
                                    <label>/</label>
                                  </div>
                                  <div class="col-xs-5">
                                    <input value="{{old('presion_arterial_y')}}" type="text" tabindex="9" name="presion_arterial_y" id="presion_arterial_y"
                                      class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                    <span id="presion_arterial_y-aceptado"></span>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-sm-3">
                                <label>FREC. CARDIACA (/min)</label>
                                <input value="{{old('frec_cardiaca')}}" type="text" tabindex="10" name="frec_cardiaca" id="frec_cardiaca"
                                  class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                <span id="frec_cardiaca-aceptado"></span>
                              </div>
                              <div class="form-group col-sm-3">
                                <label>FREC. RESPIRATORIA (/min)</label>
                                <input value="{{old('frecuencia_respiratoria')}}" type="text" tabindex="11" name="frecuencia_respiratoria"
                                  id="frecuencia_respiratoria" class="form-control" onkeypress="return esEntero(event);"
                                  maxlength="2">
                                <span id="frecuencia_respiratoria-aceptado"></span>
                              </div>
                              <div class="form-group col-sm-3">
                                <label>Fi02 (%)</label>
                                <input value="{{old('oxigenacion')}}" type="text" tabindex="12" name="oxigenacion" id="oxigenacion" tabindex="19"
                                  class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                <span id="oxigenacion-aceptado"></span>
                              </div>
                            </div>
                          </fieldset>



                          <fieldset style="margin-top: -5px !important;">
                            <div class="row">
                              <div class="form-group col-sm-3">
                                <label>Sa02 (%) &nbsp;</label>
                                <input value="{{old('saturacion')}}" type="text" tabindex="13" name="saturacion" id="saturacion"
                                  class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                <span id="saturacion-aceptado"></span>
                              </div>
                              <div class="form-group col-sm-3">
                                <label>TEMPERATURA (°C)</label>
                                <input placeholder="00.00" value="{{old('temperatura')}}" tabindex="14" type="text" name="temperatura"
                                  id="temperatura" class="form-control" maxlength="5"
                                  onkeypress="return evitarLetras(event)" />
                                <span id="texto-aceptado"></span>
                              </div>
                              
                              <div class="form-group col-sm-3">
                                <label>TALLA (cm)</label>
                                <input value="{{old('talla')}}" type="text" tabindex="15" name="talla" id="talla" class="form-control"
                                  onkeyup="compruebaIMC()" maxlength="5" onkeypress="return evitarLetras(event)">
                                <span id="talla-aceptado"></span>
                              </div> 


                              <div class="form-group col-sm-3">
                                <label>PESO (Kg)</label>
                                <input placeholder="00.00" value="{{old('peso')}}" tabindex="16" type="text" name="peso" id="peso"
                                  class="form-control" onkeyup="compruebaIMC()" onkeypress="return evitarLetras(event)"
                                  maxlength="6">
                                <span id="peso-aceptado"></span>
                              </div>
                            </div>
                          </fieldset>

                          <fieldset style="margin-top: -5px !important;">
                            <div class="row">
                              <div class="form-group col-sm-2">
                                <label>IMC</label>
                                <input value="{{old('imc')}}" type="text" name="imc" id="imc" class="form-control" readonly="">
                                <span id="imc-aceptado"></span>
                              </div>

                              <div class="form-group col-sm-2">
                                <label>PESO IDEAL</label>
                                <input value="{{old('peso_x_altura')}}" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control"
                                  readonly="" max="0">
                              </div>

                              {{-- TODO: TAG: CHECKGESTACION --}}
                              {{-- <div class="form-group col-sm-2" style="text-align: right;padding-top: 5px;"
                                id="habilitar_es_gestante" name="habilitar_es_gestante"> <br>
                                <label style="cursor: pointer;"> ES GESTANTE? </label>
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" id="checkbox_es_gestante"
                                  name="checkbox_es_gestante">
                                <input id="es_gestante" name="es_gestante" type="hidden">
                              </div> --}}

                              <div id="seccion_gestante" name="seccion_gestante">
                                <div class="form-group col-sm-3" id="tamanio_de_cintura" style="display:none">
                                  <label>ALTURA UTERINA (cm)</label>
                                  <input value="{{old('tamanio_cintura')}}" placeholder="00.00" type="text" name="tamanio_cintura" id="tamanio_cintura"
                                    class="form-control" onkeypress="return evitarLetras(event)">
                                  <span id="tamanio_cintura-aceptado"></span>
                                </div>

                                <div class="form-group col-sm-3" id="frecuencia_fetal" style="display:none">
                                  <label>FREC. CARDIACA FETAL (/min)</label>
                                  <input value="{{old('frecuencia_cardiaca_fetal')}}" placeholder="00.00" type="text" name="frecuencia_cardiaca_fetal"
                                    id="frecuencia_cardiaca_fetal" class="form-control"
                                    onkeypress="return evitarLetras(event)">
                                  <span id="frecuencia_cardiaca_fetal-aceptado"></span>
                                </div>
                              </div>

                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </fieldset>
                <!------------------    INFORMACIÓN DEL INGRESO  
                    <a class="btn btn-success" onclick="guardarTriaje()" tabindex="25">GUARDAR</a>
                ------------------>
              {{-- -------------------------- GESTACIÓN --------------------------- --}}
              <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important;display: none;" id="divSaludMaterna">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab10saludMaterna" data-toggle="tab" style="font-size:12px" aria-expanded="true"><b><i class="fa fa-heartbeat" aria-hidden="true"></i>SALUD MATERNA</b></a></li>
                    </ul>
                </div>
                    
                <div id="divGestacion" class="panel panel-default" style="margin: 10px" style="display: none">
                  <div class="form-group" style="margin: 20px 0px 10px 20px">
                      <div class="row">
                          <div class="col-sm-2">
                              <label>GESTANTE</label>
                          </div>
                          <div class="col-sm-2" >
                              <button tabindex="90" type="button" id="esta_gestando"
                                  class="btn btn-lg btn-toggle" data-toggle="button"
                                  aria-pressed="false" autocomplete="off" onclick="toggleFields()">
                                  <div class="handle"></div>
                              </button>
                          </div>
                      </div>
                  </div>
                  <fieldset id="frsgetante" style="display:none">
                      <div class="panel-body">
                          <div class="row" id="control_ipress_origin">
                              <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <div class="form-group" id="">
                                      <label for="establecimiento_nombre">IPRESS ORIGEN:</label>
                                      <input type="text" id="establecimiento_nombre" name="establecimiento_nombre" class="form-control input-sm" readonly>
                                  </div>
                              </div>
                          </div>
                          <br>
                          <input type="hidden" id="id_gestacion" name="id_gestacion">

                          <div class="row">
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <div class="form-group">
                                      <label for="ultimaMestruacion">ULTIMA MESTRUACIÓN:</label>
                                      <input readonly type="text" id="ultimaMestruacion" name="ultimaMestruacion" class="form-control input-sm typeahead tt-query datepicker" value="{{old('ultimaMestruacion')}}">
                                  </div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <div class="form-group">
                                  <label for="parto_probable">FECHA PROBABLE DE PARTO:</label>
                                  <input readonly type="text" id="parto_probable" name="parto_probable" class="form-control input-sm typeahead tt-query datepicker" value="{{old('parto_probable')}}">
                                  </div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <div class="form-group">
                                  <label for="semana_gesta">SEMANAS DE GESTACIÓN:</label>
                                  <input readonly type="text" id="semana_gesta" name="semana_gesta" class="form-control input-sm" value="{{old('semana_gesta')}}">
                                  </div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <div class="form-group">
                                  <label for="otro_nuevo_campo">N° DE CONTROLES PRENATALES:</label>
                                  <input type="number" id="control_prenatal"  name="control_prenatal" min="0" max="9" class="form-control input-sm" placeholder="Ingresar desde n° 0 hasta 9" value="{{old('control_prenatal')}}">
                                  </div>
                              </div>

                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <div class="form-group">
                                      <label>DUDA:</label><br>
                                      <label class="radio-inline"><input type="radio" name="duda" value="1" > SI</label>
                                      <label class="radio-inline"><input type="radio" name="duda" value="0" checked>NO</label>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </fieldset>
                </div>
                {{-- -------------------------- GESTACIÓN END --------------------------- --}}

                {{-- -------------------------- PUERPERA INIT --------------------------- --}}
                <br>
                <div id="divPuerpera" class="panel panel-default" style="margin: 10px" style="display: none">
                  <div class="form-group" style="margin: 20px 0px 10px 20px">
                    <div class="row">
                      <div class="col-sm-2">
                          <label>PUERPERA</label>
                      </div>
                      <div class="col-sm-2">
                          <button tabindex="90" type="button"
                              id="addPuerpera"
                              class="btn btn-lg btn-toggle"
                              data-toggle="button"
                              aria-pressed="false" autocomplete="off">
                              <div class="handle"></div>
                          </button>
                      </div>
                    </div>
                  </div> 
                  <fieldset id="formPurperio" style="display: none">
                      <div class="panel-body">
                          <div class="row">
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <label for="fecha_parto">FECHA PARTO</label>
                                  <input 
                                      class="form-control input-sm datepicker" 
                                      type="text" 
                                      id="fecha_parto" 
                                      name="fecha_parto" 
                                      placeholder="01-01-2020" 
                                      value="{{old('fecha_parto')}}"
                                      readonly>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <label for="hora_parto">HORA PARTO</label>
                                  <input 
                                      class="form-control input-sm" 
                                      type="time" 
                                      id="hora_parto" 
                                      name="hora_parto" 
                                      placeholder="00:00:00"
                                      {{old('hora_parto')}}
                                      >
                              </div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <label for="tipo_parto">TIPO DE PARTO</label>
                                  <select tabindex="8" class="form-control input-sm" name="tipo_parto" id="tipo_parto"  style="width:100%">
                                  <option value="0" selected> -Seleccionar-</option>
                                  @if (count($tipoPartos) > 0)
                                      @foreach ($tipoPartos as $row)
                                          <option value="{{$row->id}}" @if(old('tipo_parto') == $row->id) selected @endif>{{ $row->nombre }}</option>
                                      @endforeach
                                  @endif 
                                  </select>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                  <label for="complicacion_parto">COMPLICACIÓN</label>
                                  <select tabindex="8" class="form-control input-sm" name="complicacion_parto" id="complicacion_parto" style="width:100%">
                                  <option value="0" selected> -Seleccionar-</option>
                                  @if (count($complicaciones) > 0)
                                      @foreach ($complicaciones as $row)
                                          <option value="{{$row}}" @if(old('complicacion_parto') == $row) selected @endif>{{ $row }}</option>
                                      @endforeach
                                  @endif
                                  </select>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 10px">
                                  <label for="descripcion_parto">DESCRIPCIÓN</label>
                                  <textarea class="form-control input-sm" name="descripcion_parto" id="descripcion_parto" cols="2" rows="2" maxlength="255" >{{old('descripcion_parto')}}</textarea>
                              </div>
                          </div>                                           
                      </div>
                  </fieldset>
                </div>
              </div>                                
              {{-- -------------------------- PUERPERA END --------------------------- --}}

              <div class="row">
                  <div class="form-group col-sm-12"
                      style="display: flex; justify-content: flex-end; gap: 1rem;">
                      <a href="/all_triajes_atendidos" class="btn btn-danger"
                          tabindex="24">CANCELAR</a>
                      <a class="btn btn-success" tabindex="25" id="guardarTriaje"
                          name="guardarTriaje">GUARDAR</a>
                  </div>
              </div>
              </div>
              <div class="tab-pane fade marca_agua_container" id="tab4default" style="position:relative;">
                <div class="marca_agua_overlay" id="marca_agua_overlay_triaje"></div>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>HISTORIAL DE ATENCIONES DE TRIAJE DE EMERGENCIA</b>
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
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@include('frontend.admision.emergencia.triaje.alerta_atenciones.modal.alerta_atenciones')

@include('frontend.includes.footer')
@endsection